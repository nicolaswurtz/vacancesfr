<?php
// Logiquement les liens sont à jour
$academies_csv = fopen('https://www.data.gouv.fr/fr/datasets/r/b363e051-9649-4879-ae78-71ef227d0cc5','r');
$json = json_decode(file_get_contents('https://data.education.gouv.fr/explore/dataset/fr-en-calendrier-scolaire/download/?format=json&timezone=Europe/Berlin'));

$ligne = fgetcsv($academies_csv);
while ($ligne = fgetcsv($academies_csv)) {
    $nom_academie = str_replace(['Académie de ','Académie d\''],'',$ligne[0]);
    $nom_zone = str_replace('Zone ','',$ligne[2]);
    $num_dep = intval($ligne[3]);
    if (!empty($nom_zone)) {
        $academies[$nom_academie][] = array('zone' => $nom_zone, 'num_dep' => $num_dep);
    }
}

// Trouver fin des vacances d'été
foreach ($json as $evenement) {
    if ($evenement->fields->description == "Rentrée scolaire des élèves") {
        $zones = explode(' ',str_replace(['ZONE ','ZONES '],'',strtoupper($evenement->fields->zones)));
        foreach ($zones as $zone) {
            $fin_de_l_ete[$zone.date('Y',strtotime($evenement->fields->start_date))] = date('Y-m-d',strtotime($evenement->fields->start_date . ' -1 day'));
        }
    }
}

foreach ($json as $evenement) {
    $titre = $evenement->fields->description;
    $zones = explode(' ',str_replace(['ZONE ','ZONES '],'',strtoupper($evenement->fields->zones)));
    $academie = $evenement->fields->location;
    $debut = date('Y-m-d',strtotime($evenement->fields->start_date));
    $fin = (empty($evenement->fields->end_date)) ? '' : date('Y-m-d',strtotime($evenement->fields->end_date));
    foreach ($zones as $zone) {
        foreach ($academies[$academie] as $dep) {
            if ($titre != "Rentrée scolaire des élèves" and $titre != "Prérentrée des enseignants") {
                if ($titre == "Vacances d'été") {
                    $fin = $fin_de_l_ete[$zone.substr($debut,0,4)];
                }
                $liste[] = array(
                    'titre'         => $titre,
                    'zone'          => $zone,
                    'departement'   => $dep['num_dep'],
                    'academie'      => $academie,
                    'debut'         => $debut,
                    'fin'           => $fin
                );
                $csv .= $zone.','.$academie.','.$dep['num_dep'].','.$debut.','.$fin.','.$titre."\n";
            }
        }
    }
}

// Ici on obtient un array $liste avec chaque zone/departement/periode proposée, ou une liste type CSV dans $csv
echo json_encode($liste);

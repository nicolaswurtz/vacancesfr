# vacancesfr
Transforme les données opendata des vacances scolaires françaises en quelque chose de plus simple à utiliser.

## Datasets concernés
Contours géographiques des Académies : https://www.data.gouv.fr/fr/datasets/contours-geographiques-des-academies/#_

Vacances scolaires : https://data.education.gouv.fr/explore/dataset/fr-en-calendrier-scolaire/

## Pourquoi ?
L'état français propose plein de données en opendata, dont les vacances scolaires ! Mais ces données, bien que lisibles, sont compliquées à exploiter sans un minimum de traitement.

Voici quelques anomalies relevées :
- le champ zone contient sa description dans la donnée, exemple « Zone A » ou « Zones A,B »
- il n'y a pas de distingo entre un évènement ponctuel (une seule date) et une période (debut - fin)
- les vacances d'été n'ont pas de date de fin
- il manque une notion de département qui serait appréciable pour filtrer plus simplement les académies

## Comment ?
Petit script en PHP qui linéarise les deux sets de données à la quick & dirty, avec une ligne par zone/periode/departement, très facile ensuite à interroger dans une BDD. Les données de sortie **ne contiennent pas** les évènements ponctuels (la prérentrée des enseignants pour le coup).

Il suffit d'exporter ensuite $liste (un array) ou $csv (une liste de lignes avec les champs séparés par des virgules).

**Par défaut le script renvoie $liste en json**.

## C'est tout ?
C'est tout.

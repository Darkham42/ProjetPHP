<?php

$hote = '127.0.0.1';
$port = '1521'; // port par défaut
$service = 'xe';
$utilisateur = 'copie_tdf';
$motdepasse = 'copie_tdf';

$lien_base =
"oci:dbname=(DESCRIPTION =
(ADDRESS_LIST =
  (ADDRESS =
    (PROTOCOL = TCP)
    (Host = ".$hote .")
    (Port = ".$port."))
)
(CONNECT_DATA =
  (SERVICE_NAME = ".$service.")
)
)";

try {
  // connexion à la base Oracle et création de l'objet
  $connexion = new PDO($lien_base, $utilisateur, $motdepasse);
}
catch (PDOException $erreur) {
  echo $erreur->getMessage();
}

?>
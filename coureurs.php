<?php

include_once('connBDD.php');

$reponse = $connexion->query('SELECT * FROM vt_coureur');
while ($donnees = $reponse->fetch())
{
?>
  <p>
  <?php if (isset($donnees['NOM']) && isset($donnees['PRENOM']))
  {?>
  <strong>Coureur :</strong> <?php echo $donnees['NOM'];?> <?php echo $donnees['PRENOM'];?><br/>
  
  <?php
	}
  ?>

  </p>


<?php
}
$reponse->closeCursor(); // Termine le traitement de la requête
?>
<?php
include_once('connBDD.php');
?>

<head>
	<title>Coureur</title>
	<meta http-equiv="content-Type" Content="text/css;charset=utf-8">
	<meta name="author" content="PICARD Thomas">	
</head>

<?php
$reponse = $connexion->query('SELECT NOM, PRENOM, CODE_TDF, ANNEE_NAISSANCE FROM vt_coureur ORDER BY NOM ASC');
while ($donnees = $reponse->fetch())
{
?>
  <p>
  <?php if (isset($donnees['NOM']) && isset($donnees['PRENOM']))
  {?>
  <strong>NOM :</strong> <?php echo $donnees['NOM'];?><br/>
  <strong>PRENOM :</strong> <?php echo $donnees['PRENOM'];?><br/>
  <strong>PAYS :</strong> <?php echo $donnees['CODE_TDF'];?><br/>
  <strong>AGE :</strong> <?php echo $donnees['ANNEE_NAISSANCE'];?><br/>
  
  <?php
	}
  ?>

  </p>


<?php
}
$reponse->closeCursor(); // Termine le traitement de la requête
?>
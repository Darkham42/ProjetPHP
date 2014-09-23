<!DOCTYPE html>

<?php
include_once('connBDD.php');
?>

<head>
	<title>Application Tour de France</title>
	<meta http-equiv="content-Type" Content="text/css;charset=utf-8">
	<meta name="author" content="PICARD Thomas">
</head>


<body>

	<form name="FormCoureur" action="<?php $_SERVER['PHP_SELF']?>" method="post">
		<input type="submit" name="ajout_coureur" value="Ajouter coureur">
		<input type="submit" name="modif_coureur" value="Modifier coureur">
		<input type="submit" name="supr_coureur" value="Supprimer coureur">
	</form>
	
	<?php 
		if (isset($_POST['ajout_coureur'])) {
				include ("ajouter_coureur.php");
				
				if (isset($_POST['nom']))
					echo "Vérification conforme";
				}

		if (isset($_POST['modif_coureur']))
				include ("modifier_coureur.php");
			
		if (isset($_POST['supr_coureur']))
				include ("supprimer_coureur.php");
	?>

</body>

</html>
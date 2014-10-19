	<?php 
		include ('topMenu.php');
		include ('Econnexion.php');
		include ("ajout_sponsor.php");
	?>
	
	<form name="formAjoutCoureur" action="#" method="post" >
			<div align="center" style="margin-left:20%; margin-right:20%">
				<fieldset >
					<tr>
						<td>
							<input type="submit" name="choix1" value="Ajouter un sponsor">
						</td>
						<td>
							<input type="submit" name="choix2" value="Créer une nouvelle équipe">
						</td>
					</tr>
<?php 
	if(isset($_POST['choix1'])){
			include("form_ajout_sponsor.php");
	}
	else if(isset($_POST['choix2'])){
			include("form_ajout_equipe.php");
	}
?>
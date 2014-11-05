<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html;">
		<meta charset="utf-8">
		<link rel="icon" type="image/png" href="favicon.ico" />
		<title>Suppression d'un sponsor || Mise à jour TDF</title>
	</head>
	<body>
<?php
			include ('topMenu.php');
			include ('Econnexion.php');
?>
<h2 align="center">Suppression d'un sponsor</h2>
<?php 
	if(!isset($valeurTestEquipe)){
		$valeurTestEquipe="";
	}
//on analyse l'enregistrement
	if(isset($_POST['SupprimerSponsor'])){
		//on test si les champs ont bien été rempli
		if($_POST['equipe'] == "Selectionnez une equipe"){
			$valeurTestEquipe = "Veuillez selectionner une equipe";	
		}else{
			//requete suppression
			$login = 'copie_tdf';
			$mdp = 'copie_tdf';
			$instance = 'xe';
			$conn = OuvrirConnexion($login, $mdp,$instance);
			$req = "select max(n_sponsor) as nb_sponsor from tdf_sponsor where n_equipe='".$_POST['equipe']."'";
			$cur = preparerRequete($conn, $req);
			$tab = executerRequete($cur);
			$maxNumSponsor = array();
			oci_fetch_all($cur,$maxNumSponsor);
			$numSponsor=$maxNumSponsor['NB_SPONSOR'][0];
			$req = "delete from tdf_sponsor where n_equipe='".$_POST['equipe']."' and n_sponsor='".$numSponsor."'";
			$cur = preparerRequete($conn, $req);
			$tab1 = executerRequete($cur);
			oci_commit($conn);
			FermerConnexion($conn);
			$valeurTestEquipe=" ";
		}
	}
?>
		

<form name="formAjoutCoureur" action="<?php $_SERVER['PHP_SELF'] ?>" method="post" >
	<div align="center" style="margin-left:20%; margin-right:20%">
		<fieldset >
			<legend>Supprimer Sponsor</legend>
			<table border=0 cellpadding=10>
				<tr>
					<td>
						Choisissez l'équipe concerné :
					</td>
					<td>
						<?php
							$login = 'copie_tdf';
							$mdp = 'copie_tdf';
							$instance = 'xe';
							$conn = OuvrirConnexion($login, $mdp,$instance);
							$req = 'select n_equipe,nom,n_sponsor from tdf_equipe join tdf_sponsor using (n_equipe) where annee_disparition is null and (n_equipe,n_sponsor) in (select n_equipe,max(n_sponsor) as dernier_sponsor from tdf_sponsor group by n_equipe) order by nom';
							$cur = preparerRequete($conn, $req);
							$tab = executerRequete($cur);
							FermerConnexion($conn);
							$nbLignes = oci_fetch_all($cur, $tab2,0,-1,OCI_FETCHSTATEMENT_BY_ROW);
							echo "<select name='equipe' size=1>";
							echo "<option value=''>Selectionnez une equipe</option>";
							for ($i=0;$i<$nbLignes;$i++){
								echo '<option value="'.$tab2[$i]["N_EQUIPE"].'">'.$tab2[$i]["NOM"];
								echo '</option>';
							} 	
							echo "</select> ";	
						?>	
					</td>
					<td>
						<font color='red'><?php  echo $valeurTestEquipe; ?></font>
					</td>
				</tr>
				<tr>
					<td colspan=2 align="center">
						<input type='submit' name='SupprimerSponsor' value='Supprimer' >
					</td>
				</tr>
			</table>
		</fieldset>
	</div>
</form>
</body>
</html>
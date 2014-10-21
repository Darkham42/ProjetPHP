<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html;">
		<meta charset="utf-8">
		<title>Supprimer un directeur du Tour de France</title>
	</head>
	<body>

		<?php
			include ('../menuBarre.php');
			include_once ('../connBDD.php');
			include_once ('../fonc_test.php');
		?>

		<h2 align="center">Suppression d'un directeur</h2>
		
	<!-- FORMULAIRE SUPPRESSION D'UN DIRECTEUR -->

		<?php
			if(!isset($valeurTestDirecteur)) {
				$valeurTestDirecteur="";
			}

			if(isset($_POST['Supprimer'])) {
				
				if($_POST['directeur'] == "Selectionnez un directeur") {
					$valeurTestDirecteur = "Veuillez selectionner un directeur.";	
				}
				
				else {
					$conn = OuvrirConnexion();
					$req = "DELETE from tdf_directeur where n_directeur = '".$_POST['directeur']."'";
					$cur = preparerRequete($conn, $req);
					$tab = executerRequete($cur);
					oci_commit($conn);
					FermerConnexion($conn);
					$valeurTestDirecteur="Directeur supprimé de la base avec succès.";					
				}
			}
		?>
		
		<form name="formDelDirecteur" action="" method="post" >
			<div align="center" style="margin-left:10%; margin-right:10%">

				<fieldset >
					<table border=0 cellpadding=10>
						<tr>
							<td>
								Choisissez le directeur à supprimer de la BDD :
							</td>
							<td>
								<?php

									$conn = OuvrirConnexion();
									$req = 'SELECT n_directeur, nom, prenom from tdf_directeur order by nom';
									$cur = preparerRequete($conn, $req);
									$tab = executerRequete($cur);
									$nbLignes = oci_fetch_all($cur, $tab,0,-1,OCI_FETCHSTATEMENT_BY_ROW);
									
									echo "<select name='directeur' size=1>";
										echo "<option value='Selectionnez un directeur'>Selectionnez un directeur</option>";
										for ($i=0;$i<$nbLignes;$i++){
										  echo '<option value="'.$tab[$i]["N_DIRECTEUR"].'">'.$tab[$i]["NOM"]." ".utf8_encode($tab[$i]["PRENOM"]);
										  echo '</option>';
										} 	
									echo "</select> ";
									
									FermerConnexion($conn);
								?>	
							</td>
							<td>
								<font color='red'><?php echo $valeurTestDirecteur; ?></font>
							</td>
						</tr>
						<tr>
							<td colspan=2 align="center">
								<input type='submit' name='Supprimer' value='Supprimer un directeur' >
							</td>
						</tr>
					</table>
					
				</fieldset>
			</div>
		</form>

	</body>
</html>
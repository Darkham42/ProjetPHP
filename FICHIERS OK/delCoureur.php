<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html;">
		<meta charset="utf-8">
		<title>Supprimer un coureur du Tour de France</title>
	</head>
	<body>

		<?php
				include_once ('connBDD.php');
				include_once ('fonc_test.php');
		?>

		<h2 align="center">Suppression d'un coureur</h2>
		
	<!-- FORMULAIRE SUPPRESSION D'UN' COUREUR -->

		<?php
			if(!isset($valeurTestCoureur)) {
				$valeurTestCoureur="";
			}

			if(isset($_POST['Supprimer'])) {
				
				if($_POST['coureur'] == "Selectionnez un coureur") {
					$valeurTestCoureur = "Veuillez selectionner un coureur !";	
				}
				
				else {
					$conn = OuvrirConnexion();
					$req = "delete from tdf_coureur where n_coureur='".$_POST['coureur']."'";
					$cur = preparerRequete($conn, $req);
					$tab = executerRequete($cur);
					oci_commit($conn);
					FermerConnexion($conn);
					$valeurTestCoureur="";					
				}
			}
		?>
		
		<form name="formDelCoureur" action="<?php $_SERVER['PHP_SELF'] ?>" method="post" >
			<div align="center" style="margin-left:10%; margin-right:10%">

				<fieldset >
					<table border=0 cellpadding=10>
						<tr>
							<td>
								Choisissez le coureur à supprimer de la BDD :
							</td>
							<td>
								<?php

									$conn = OuvrirConnexion();
									$req = 'select n_coureur, nom, prenom from tdf_coureur where n_coureur > 0 and n_coureur in (select n_coureur from tdf_coureur minus select n_coureur from tdf_participation) order by nom';
									$cur = preparerRequete($conn, $req);
									$tab = executerRequete($cur);
									$nbLignes = oci_fetch_all($cur, $tab,0,-1,OCI_FETCHSTATEMENT_BY_ROW);
									
									echo "<select name='coureur' size=1>";
										echo "<option value='Selectionnez un coureur'>Selectionnez un coureur</option>";
										for ($i=0;$i<$nbLignes;$i++){
										  echo '<option value="'.$tab[$i]["N_COUREUR"].'">'.$tab[$i]["NOM"]." ".utf8_encode($tab[$i]["PRENOM"]);
										  echo '</option>';
										} 	
									echo "</select> ";
									
									FermerConnexion($conn);
								?>	
							</td>
							<td>
								<font color='red'><?php echo $valeurTestCoureur; ?></font>
							</td>
						</tr>
						<tr>
							<td colspan=2 align="center">
								<input type='submit' name='Supprimer' value='Supprimer un coureur' >
								
							</td>
						</tr>
					</table>
					
				</fieldset>
			</div>
		</form>

	</body>
</html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html;">
		<meta charset="utf-8">
		<title>Modifier une année du Tour de France</title>
	</head>
	<body>

		<?php
			include_once ('../menuBarre.php');
			include_once ('../connBDD.php');
			include_once ('../fonc_test.php');
		?>

		<h2 align="center">Modification d'une année</h2>

	<!-- FORMULAIRE POUR MODIFIER UNE ANNEE A LA BASE -->

		<?php

			// Initialisation des variables
			$erreur = 0;

			if(!isset($valeurTestAjout)) {
				$valeurTestAjout = "";
			}

			if(!isset($valeurTestRepos)) {
				$valeurTestRepos = "";
			}

			if(!isset($valeurTestAnnee)) {
				$valeurTestAnnee = "";
			}

			if(isset($_POST['Modifier'])) {
				
				if($_POST['annee'] != "Selectionnez une année" && $_POST['repos'] != null){
					
					$valeurAnnee = $_POST['annee'];
					$valeurRepos = $_POST['repos'];

					if(is_numeric($valeurRepos)){
						if($valeurRepos < 0){
							$erreur = 1;
							$valeurTestRepos = "Minimum 0 jours de repos.";
						}
					}
					else {
						$erreur = 1;
						$valeurTestRepos = "Valeur invalide.";
					}
					
					//MODIFICATION
					if($erreur != 1) {
						
						$conn = OuvrirConnexion();
						$req = "UPDATE tdf_annee set jour_repos = $valeurRepos where annee = $valeurAnnee";
						$cur = preparerRequete($conn, $req);
						$tab = executerRequete($cur);
						oci_commit($conn);
						$valeurTestAjout = "L'année a été modifiée avec succès.";
						
						$valeurAnnee = "";
						$valeurRepos = "";
							
						FermerConnexion($conn);
					}
				}
				else {
					$valeurAnnee = $_POST['annee'];
					$valeurRepos = $_POST['repos'];

					if(empty($_POST['annee'])) {
						$valeurTestAnnee = "Veuillez saisir une année.";
					}
					if(empty($_POST['repos'])) {
						$valeurTestRepos = "Veuillez saisir un nombre de jours de repos.";
					}
				}	
			}
	
		?>
		
		<form name="formModAnnee" action="" method="post" >
			<div align="center" style="margin-left:10%; margin-right:10%">
				<fieldset>
					<table border=0 cellpadding=10>
						<tr>
							<td>
								Année<sup>*</sup> :
							</td>
							<td>
								<?php
									$conn = OuvrirConnexion();
									$req = "SELECT annee FROM tdf_annee ORDER BY annee DESC";
									$cur = preparerRequete($conn, $req);
									$tab = executerRequete($cur);
									FermerConnexion($conn);
									$nbLignes = oci_fetch_all($cur, $tab,0,-1,OCI_FETCHSTATEMENT_BY_ROW);
									echo "<select name='annee' size=1>";
										echo "<option value='Selectionnez une année'>Selectionnez une année</option>";
										for ($i=0; $i<$nbLignes; $i++){
											if($tab[$i]["ANNEE"] == $valeurAnnee) {
												echo '<option value="'.$tab[$i]["ANNEE"].'" selected>'.$tab[$i]["ANNEE"];
											}
											else {
												echo '<option value="'.$tab[$i]["ANNEE"].'">'.$tab[$i]["ANNEE"];
											}
										  echo '</option>';
										} 	
									echo "</select> ";
								?>
							</td>
							<td>
								<font color="red"><?php echo $valeurTestAnnee; ?></font>
							</td>
						</tr>
						<tr>
							<td>
								Nombre de jour de repos<sup>*</sup> :
							</td>
							<td>
								<input type="text" name="repos" size=32 maxlength=1 value="<?php if(isset($valeurRepos)) echo $valeurRepos; ?>" placeholder="Saisir le nombre de jour de repos"> 
							</td>
							<td>
								<font color="red"><?php echo $valeurTestRepos; ?></font>
							</td>
						</tr>
						<tr>
							<td>
								<font size=1><sup>*</sup>Champs obligatoires</font>
							</td>
							<td align="center">
								<input type='submit' name='Modifier' value='Modifier une année' >
								
							</td>
							<td>
								<font color='green'><?php echo $valeurTestAjout; ?></font>
							</td>
						</tr>
					</table>
					
				</fieldset>
			</div>
		</form>
		
		
	</body>
</html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html;">
		<meta charset="utf-8">
		<title>Modifier un coureur du Tour de France</title>
	</head>
	<body>

		<?php
			include ('../menuBarre.php');
			include_once ('../connBDD.php');
			include_once ('../fonc_test.php');
		?>

		<h2 align="center">Modification d'un coureur</h2>

	<!-- FORMULAIRE POUR MODIFIER UN COUREUR DE LA BASE -->
		
		<?php

			// Initialisation des variables
			$erreur = 0;

			if(!isset($valeurNumCoureur))
				$valeurNumCoureur = "";

			if(!isset($valeurTestSelectionCoureur))
				$valeurTestSelectionCoureur = "";
			if(!isset($valeurTestNom))
				$valeurTestNom = "";

			if(!isset($valeurTestPrenom))
				$valeurTestPrenom = "";

			if(!isset($valeurTestPays))
				$valeurTestPays = "";

			if(!isset($valeurTestModif))
				$valeurTestModif = "";

			if(!isset($valeurAnnee))
				$valeurAnnee = "";

			if(!isset($valeurPays))
				$valeurPays = "";

			if(!isset($valeurParticipation))
				$valeurParticipation = "";

			if(!isset($valeurTestAjout)) {
				$valeurTestAjout = "";
			}

			if(!isset($valeurTestAnneeNaissance))
				$valeurTestAnneeNaissance = "";$valeurPays = "";

			if(isset($_POST['Modifierier'])) {

				if($_POST['numCoureur'] != 'Selectionnez un coureur') {

					if($_POST['nom'] != "" || $_POST['prenom'] != "" || $_POST['annee_naissance'] != "" || $_POST['annee_tour'] != "" || $_POST['pays'] != "") {
						
						$valeurInsertion = '';
						$valeurNumCoureur = $_POST['numCoureur'];

						if($_POST['nom'] != "") {
							if((substr($_POST['nom'], -1)) == "-" || (substr($_POST['nom'], 0 , 1)) == "-" || is_numeric($_POST['nom']) ||!ctype_alpha($_POST['nom'])) {	
								$erreur = 1;
								$valeurTestNom = "Veuillez entrer un nom valide !";
							}
							else {
								$valeurNom = $_POST['nom'];
								$nom = strtoupper(trim($_POST['nom']));
								$valeurInsertion = $valeurInsertion." nom = '".$nom."'";
							}
						}
						
						if($_POST['prenom'] != "") {
							if(!ctype_alpha($_POST['prenom']) || is_numeric($_POST['prenom'])) {
								$erreur = 1;
								$valeurTestPrenom = "Veuillez entrer un prénom valide !";
							}
							else {
								$valeurPrenom = $_POST['prenom'];
								$valeurPrenom = strtolower($valeurPrenom);
								$prenom = ucwords($valeurPrenom);

								if($valeurInsertion == "")
									$valeurInsertion = $valeurInsertion." prenom = '".$prenom."'";
								else
									$valeurInsertion = $valeurInsertion.", prenom = '".$prenom."'";
							}
						}
						
						if($_POST['annee_naissance'] != "") {
								$annee_naissance = $_POST['annee_naissance'];
								$valeurAnnee = $annee_naissance;
								if($valeurInsertion == "") {
									$valeurInsertion = $valeurInsertion.' annee_naissance = '.$annee_naissance;
								}
								else {
									$valeurInsertion = $valeurInsertion.', annee_naissance = '.$annee_naissance;
								}
						}
						
						if($_POST['annee_tour'] != "") {
							$annee_tour = $_POST['annee_tour'];
							$valeurParticipation = $annee_tour;
							if($valeurInsertion == "")
								$valeurInsertion = $valeurInsertion.' annee_tdf = '.$annee_tour;
							else
								$valeurInsertion = $valeurInsertion.', annee_tdf = '.$annee_tour;
						}
						 
						if($_POST['pays'] != "") {
							$pays = $_POST['pays'];
							$valeurPays = $pays;
							if($valeurInsertion == "")
								$valeurInsertion = $valeurInsertion." code_tdf = '".$pays."'";
							else
								$valeurInsertion = $valeurInsertion.", code_tdf = '".$pays."'";
						}
						
						
						if($erreur != 1) {

							$conn = OuvrirConnexion();
							$req = "UPDATE tdf_coureur set".$valeurInsertion." where n_coureur =".$_POST['numCoureur'];
							$cur = preparerRequete($conn, $req);
							$tab = executerRequete($cur);
							oci_commit($conn);
							
							FermerConnexion($conn);
							
							$valeurTestModif = "Modification correctement exécuté !";
							
							//on reinitialise les variables test
							$valeurTestSelectionCoureur = "";
							$valeurTestNom = "";
							$valeurNom = "";
							$valeurNumCoureur = "";
							$valeurPrenom = "";
							$valeurTestPrenom = "";
							$valeurAnnee = "";
							$valeurParticipation = "";
							$valeurPays = "";
						}
						
					}
					else { // si aucun des champs de sont remplis
							$valeurTestSelectionCoureur = "Veuillez au moins changer un des champs <br>ci-dessous pour modifier le coureur !";
							$valeurNumCoureur = $_POST['numCoureur'];
						
						}
				}
				else{
					$valeurTestSelectionCoureur = "Veuillez selectionner un coureur</br>pour le modifié";
				}
			}
		?>

		<form name="formModCoureur" action="<?php $_SERVER['PHP_SELF'] ?>" method="post" >
			<div align="center" style="margin-left:10%; margin-right:10%">
				
				<fieldset >
					<table border=0 cellpadding=10>
						<tr>
							<td>
								Choisissez le coureur à modifier :
							</td>
							<td>
								<?php

									$conn = OuvrirConnexion();
									$req = 'select n_coureur, nom, prenom from tdf_coureur where n_coureur > 0 order by nom';
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
						</tr>
						<tr>
							<td>
								Nom :
							</td>
							<td>
								<input type="text" name="nom" size=32 maxlength=20 value="<?php if(isset($valeurNom)) echo $valeurNom; ?>" > 
							</td>
							<td>
								<font color="red" size=2><b><?php echo $valeurTestNom; ?></b></font>
							</td>
						</tr>
						<tr>
							<td>
								Prenom :
							</td>
							<td>
								<input type="text" name="prenom" size=32 maxlength=30 value="<?php if(isset($valeurPrenom)) echo $valeurPrenom; ?>" >
							</td>
							<td>
								<font color="red" size=2><b><?php echo $valeurTestPrenom; ?></b></font>
							</td>
						</tr>
						<tr>
							<td>
								Année de naissance :
							</td>
							<td>
								<select name="annee_naissance" size=1>
									<?php 
									echo "<option value=''>Année de naissance</option>";
									for($i=1997; $i>1949; $i--) {
										if($i == $valeurAnnee) {
											echo "<option value=".$i." selected>".$i."</option>";
										}
										else {
											echo "<option value=".$i.">".$i."</option>";
										}
									}
									?>
								</select>
							</td>
							<td>
								<font color="red" size=2><b><?php echo $valeurTestAnneeNaissance; ?></b></font>
							</td>
						</tr>
						<tr>
							<td>
								Année de premier tour de France :
							</td>
							<td>
								<select name="annee_tour" size=1>
									<?php 
									echo "<option value=''>Année de la première participation</option>";
									for($i=2015;$i>1950;$i--) {
										if($i == $valeurParticipation) {
											echo "<option value=".$i." selected>".$i."</option>";
										}
										else {
											echo "<option value=".$i.">".$i."</option>";
										}
									}
									?>
								</select>
							</td>
							<td>
							</td>
						</tr>
						<tr>
							<td>
								Pays :
							</td>
							<td>
								<?php

									$conn = OuvrirConnexion();
									$req = 'SELECT code_tdf, c_pays, nom from TDF_PAYS where nom not like \'-%\' order by nom';
									$cur = preparerRequete($conn, $req);
									$tab = executerRequete($cur);
									FermerConnexion($conn);
									$nbLignes = oci_fetch_all($cur, $tab,0,-1,OCI_FETCHSTATEMENT_BY_ROW);
									
									echo "<select name='pays' size=1>";
										echo "<option value='Selectionnez un pays'>Selectionnez un pays</option>";
										for ($i=0;$i<$nbLignes;$i++) {
											if($tab[$i]["CODE_TDF"] == $valeurPays ){
												echo '<option value="'.$tab[$i]["CODE_TDF"].'" selected>'.$tab[$i]["NOM"];
											}
											else {
												echo '<option value="'.$tab[$i]["CODE_TDF"].'">'.$tab[$i]["NOM"];
											}
										  echo '</option>';
										} 	
									echo "</select> ";
									
								?>	
							</td>
							<td>
								<font color='red' size=2><b><?php echo $valeurTestPays; ?></b></font>
							</td>
						</tr>
						<tr>
							<td>
								<font size=1>Champs obligatoires</font>
							</td>
							<td align="center">
								<input type='submit' name='Modifier' value='Modifier le coureur' >
							</td>
							<td>
								<font color='green' size=2><b><?php echo $valeurTestAjout; ?></b></font>
							</td>
						</tr>
					</table>
					
				</fieldset>
			</div>
		</form>

	</body>
</html>
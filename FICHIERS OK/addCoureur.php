<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html;">
		<meta charset="utf-8">
		<title>Ajouter un coureur du Tour de France</title>
	</head>
	<body>

		<?php
				include_once ('connBDD.php');
				include_once ('fonc_test.php');
		?>

		<h2 align="center">Ajout d'un coureur</h2>


	<!-- FORMULAIRE POUR AJOUTER UN COUREUR A LA BASE -->
		
		<?php
			
			// Initialisation des variables
			$erreur = 0;

			if(!isset($valeurTestNom)) {
				$valeurTestNom = "";
			}

			if(!isset($valeurTestPrenom)) {
				$valeurTestPrenom = "";
			}

			if(!isset($valeurTestPays)) {
				$valeurTestPays = "";
			}

			if(!isset($valeurTestAnneeNaissance)) {
				$valeurTestAnneeNaissance = "";
			}

			if(!isset($valeurTestAjout)) {
				$valeurTestAjout = "";
			}

			if(!isset($valeurParticipation)) {
				$valeurParticipation = "";
			}

			if(!isset($valeurAnnee)) {
				$valeurAnnee = "";
			}

			if(!isset($valeurPays)) {
				$valeurPays = "";
			}
			

			if(isset($_POST['Ajouter'])) {

				// Champs remplis ?
				if($_POST['nom'] != null && $_POST['prenom'] != null && $_POST['pays'] != "Selectionnez un pays") {
						
						
						// NOM
							if(!testNom($_POST['nom'])) {
								$erreur = 1;
								$valeurTestNom="Veuillez entrer un nom valide !";
							}
							else {
								$valeurNom = testNom($_POST['nom']);
								$nom = $valeurNom;
							}
						
						
						// PRENOM
							if(!testPrenom($_POST['prenom'])) {
								$erreur = 1;
								$valeurTestPrenom = "Veuillez entrer un prénom valide !";
							}

							$valeurPrenom = testPrenom($_POST['prenom']);
							$prenom = $valeurPrenom;						
							$prenom=utf8_decode($prenom);
						
						// ANNEE
							if($_POST['annee_naissance'] != null) {
								$annee = $_POST['annee_naissance'];
								$type = gettype($annee);

									if (($_POST['annee_tour']-$_POST['annee_naissance'])>18) {
										$valeurAnnee = $_POST['annee_naissance'];
									}

									else {
										$valeurTestAnneeNaissance = "Impossible de participer au TDF si vous n'êtes pas né !";
										$erreur = 1;
									}
							}
							else {
								$erreur=1;
								$valeurTestAnneeNaissance="Veuillez entrer une date valide !";
							}
							
						// PREMIERE PARTICIPATION
							if($erreur == 1){
								$valeurParticipation = $_POST['annee_tour'];
							}
							
						// PAYS
							if($erreur == 1){
								$valeurPays = $_POST['pays'];
							}
							
						
						
						if($erreur != 1){

							// N_COUREUR MAX
							$conn = OuvrirConnexion();
							$maxNCoureur = preparerRequete($conn, "SELECT max(n_coureur) as maxi from tdf_coureur");
							$maxNcour = executerRequete($maxNCoureur);
							
							// On met le résultat dans un tableau
							$maxNumArray = array();
							oci_fetch_all($maxNCoureur, $maxNumArray);
							
							// +1 coureur
							$numCoureur = $maxNumArray['MAXI'][0];
							$numCoureur = $numCoureur+5;
							
							// INSERTION
							$req = "INSERT INTO tdf_coureur(n_coureur, nom, prenom, annee_naissance, annee_tdf, code_tdf,compte_oracle, date_insert) values($numCoureur,'".$nom."','".$prenom."','".$_POST['annee_naissance']."','".$_POST['annee_tour']."','".$_POST['pays']."',user, sysdate)";
							$cur = preparerRequete($conn, $req);
							$tab = executerRequete($cur);
							oci_commit($conn);
							$valeurTestAjout = "Votre coureur a été ajouté avec succès à la BDD.";
							
							// Remise à 0 du form
							$valeurNom = "";
							$valeurPrenom = "";
							$valeurAnnee = "";
							$valeurParticipation = "";
							

							FermerConnexion($conn);
						}
				}
				else {
						
						//permet d'afficher les variables dans les differents element de la page
						if(empty($_POST['nom'])) {
							$valeurTestNom = "Veuillez entrer un nom valide !";
						}
						else {
							$valeurNom = $_POST['nom'];
						}
							
						if(empty($_POST['prenom'])) {
							$valeurTestPrenom = "Veuillez entrer un prénom valide !";
						}
						else {
							$valeurPrenom = $_POST['prenom'];
						}
						
						if(!empty($_POST['annee_naissance'])) {
							$valeurAnnee = $_POST['annee_naissance'];
						}
						
						if(!empty($_POST['annee_tour'])) {
							$valeurParticipation = $_POST['annee_tour'];
						}
						
						if($_POST['pays'] == "Selectionnez un pays") {
								$valeurTestPays = "Veuillez choisir un pays !";
						}
						else {
							$valeurPays = $_POST['pays'];
						}
						
				}
			}
		?>
			
			
			
		<form name="formAddCoureur" action="<?php $_SERVER['PHP_SELF'] ?>" method="post" >
			<div align="center" style="margin-left:10%; margin-right:10%">
				
				<fieldset >
					<table border=0 cellpadding=10>
						<tr>
							<td>
								Nom<sup>*</sup> :
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
								Prenom<sup>*</sup> :
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
								Année de naissance<sup>*</sup> :
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
								Pays<sup>*</sup> :
							</td>
							<td>
								<?php

									$conn = OuvrirConnexion();
									$req = 'SELECT code_tdf, c_pays, nom from TDF_PAYS order by nom';
									$cur = preparerRequete($conn, $req);
									$tab = executerRequete($cur);
									FermerConnexion($conn);
									$nbLignes = oci_fetch_all($cur, $tab,0,-1,OCI_FETCHSTATEMENT_BY_ROW);
									
									echo "<select name='pays' size=1>";
										echo "<option value='Selectionnez un pays'>Selectionnez un pays</option>";
										for ($i=0;$i<$nbLignes;$i++){
											if($tab[$i]["CODE_TDF"] == $valeurPays){//si la variable $valeurpays precedement rentré vaut celle de la case actuelle du tableau
											//alors on la met en selected
											echo '<option value="'.$tab[$i]["CODE_TDF"].'" selected>'.$tab[$i]["NOM"];
											}
											else{
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
								<font size=1><sup>*</sup>Champs obligatoires</font>
							</td>
							<td align="center">
								<input type='submit' name='Ajouter' value='Ajouter un coureur' >
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
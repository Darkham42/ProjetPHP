<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html;">
		<meta charset="utf-8">
		<title>Ajouter une année du Tour de France</title>
	</head>
	<body>

		<?php
			include_once ('../menuBarre.php');
			include_once ('../connBDD.php');
			include_once ('../fonc_test.php');
		?>

		<h2 align="center">Ajout d'une année</h2>

	<!-- FORMULAIRE POUR AJOUTER UNE ANNEE A LA BASE -->
		
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

			
			if(isset($_POST['Ajouter'])) {
				
				if($_POST['annee'] != null && $_POST['repos'] != null) {

					$valeurAnnee = $_POST['annee'];
					$valeurRepos = $_POST['repos'];
					
					//Le premier TDF a eu lieu en 1903
					if(is_numeric($valeurAnnee)) {
						if($valeurAnnee < 1903) { 
							$erreur = 1;
							$valeurTestAnnee = "Le premier Tour de France a eu lieu en 1903.";
						}

					}
					else{
						$erreur = 1;
						$valeurTestAnnee = "Veuillez entrer une année valide.";
					}

					if(is_numeric($valeurRepos)){
						if($valeurRepos < 0){
							$erreur = 1;
							$valeurTestRepos = "Minimum 0 jours de repos.";
						}
					}
					else{
						$erreur = 1;
						$valeurTestRepos = "Valeur invalide.";
					}
						
					
					$anneeDejaExistante = 0;

					if($erreur != 1){
						$erreur = 0;
						
						$conn = OuvrirConnexion();
						$req1 = "SELECT annee from tdf_annee";
						$cur = preparerRequete($conn, $req1);
						$tab = executerRequete($cur);
						$nbLignes = oci_fetch_all($cur, $tab,0,-1,OCI_FETCHSTATEMENT_BY_ROW);
						for($i = 0; $i<$nbLignes; $i++) {
							if($valeurAnnee == $tab[$i]['ANNEE']){
								$anneeDejaExistante = 1;
								$valeurTestAnnee = "L'année existe déjà dans la Base de Données.";
							}
						}

						if($anneeDejaExistante != 1) {
							$req = "INSERT INTO tdf_annee(annee, jour_repos, compte_oracle, date_insert) values($valeurAnnee,$valeurRepos,user, sysdate)";
							$cur = preparerRequete($conn, $req);
							$tab = executerRequete($cur);
							oci_commit($conn);
							$valeurTestAjout = "Année ajoutée.";
							
							$valeurAnnee = "";
							$valeurRepos = "";
						}
							
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
		
		<form name="formAddAnnee" action="<?php $_SERVER['PHP_SELF'] ?>" method="post" >
			<div align="center" style="margin-left:20%; margin-right:20%">
				<fieldset >
					<table border=0 cellpadding=10>
						<tr>
							<td>
								Année<sup>*</sup> :
							</td>
							<td>
								<input type="text" name="annee" size=32 maxlength=4 value="<?php if(isset($valeurAnnee)) echo $valeurAnnee; ?>" placeholder="Saisir une année"> 
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
								<input type='submit' name='Ajouter' value='Ajouter une année' >
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
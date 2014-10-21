<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html;">
		<meta charset="utf-8">
		<title>Ajouter une épreuve du Tour de France</title>
	</head>
	<body>

		<?php
			include_once ('../menuBarre.php');
			include_once ('../connBDD.php');
			include_once ('../fonc_test.php');
		?>

		<h2 align="center">Ajout d'une épreuve</h2>

	<!-- FORMULAIRE POUR AJOUTER UNE EPREUVE A LA BASE -->

		<?php

			$erreur = 0;

			if(!isset($valeurTestAnnee))
				$valeurTestAnnee ="";

			if(!isset($valeurTestNumeroEpr))
				$valeurTestNumeroEpr ="";

			if(!isset($valeurTestVilleD))
				$valeurTestVilleD ="";

			if(!isset($valeurTestVilleA))
				$valeurTestVilleA = "";

			if(!isset($valeurTestDistance))
				$valeurTestDistance = "";

			if(!isset($valeurTestMoyenne))
				$valeurTestMoyenne = "";

			if(!isset($valeurTestCodeTDF_D))
				$valeurTestCodeTDF_D = "";

			if(!isset($valeurTestCodeTDF_A))
				$valeurTestCodeTDF_A = "";

			if(!isset($valeurTestJour))
				$valeurTestJour ="";

			if(!isset($valeurTestMois))
				$valeurTestMois = "";

			if(!isset($valeurTestCAT))
				$valeurTestCAT = "";

			if(!isset($valeurTestAjout))
				$valeurTestAjout = "";

			if(isset($_POST['Ajouter'])) {

				$valeurVilleD = $_POST['villeD'];
				$valeurVilleA = $_POST['villeA'];
				$valeurDistance = $_POST['distance'];
				$valeurAnnee = $_POST['annee'];
				$valeurNumeroEpr = $_POST['numeroEpr'];
				$valeurCodeTDF_D = $_POST['codeTDF_D'];
				$valeurCodeTDF_A = $_POST['codeTDF_A'];
				$valeurJour = $_POST['jour'];
				$valeurMois = $_POST['mois'];
				$valeurTestCAT = $_POST['CAT'];
				$valeurMoyenne = $_POST['moyenne'];


				if( $_POST['annee'] != "Selectionnez une année" && 
					$_POST['numeroEpr'] != "Selectionnez un numéro d'épreuve" && 
					$_POST['villeD'] != null && 
					$_POST['villeA'] != null && 
					$_POST['distance'] != null && 
					$_POST['moyenne'] != null && 
					$_POST['codeTDF_D'] != "Selectionnez un pays de depart" && 
					$_POST['codeTDF_A'] != "Selectionnez un pays d'arriver" && 
					$_POST['jour'] != "Selectionnez un jour" && 
					$_POST['mois'] != "Selectionnez un mois" && 
					$_POST['CAT'] != "Selectionnez une catégorie d'épreuve") {

					// VILLE DEPART
					if(!testNom($valeurVilleD)) {
						$erreur = 1;
						$valeurTestVilleD = "Veuillez saisir une ville valide.";
					}
					else {
						$valeurVilleD = testNom($valeurVilleD);
					}

					// VILLE ARRIVEE
					if(!testNom($valeurVilleA)) {
						$erreur = 1;
						$valeurTestVilleA = "Veuillez saisir une ville valide.";
					}
					else {
						$valeurVilleA = testNom($valeurVilleA);
					}

					if (strlen($valeurVilleD) > 40 || strlen($valeurVilleA) > 40 ) {
						$erreur = 1;
						$valeurTestAjout = "Nombre de charactères incorects."; 
					}

					// DISTANCE
					if(!isFloat($valeurDistance)) {
						$erreur = 1;
						$valeurTestDistance = "Veuillez saisir une distance valide.";
					}
					else {
						echo "$valeurDistance";
						(float)(string)$valeurDistance;
					}

					// MOYENNE
					if(!isFloat($valeurMoyenne)){
						$erreur = 1;
						$valeurTestMoyenne = "Veuillez entrer une moyenne valide !";
					}
					else {
						echo "$valeurMoyenne";
						(float)(string)$valeurMoyenne;
					}

					// DATE
					$date = $valeurJour."/".$valeurMois."/".substr($valeurAnnee,2,4);

					if($erreur != 1){

						// PAS DE DOUBLONS
						$conn = OuvrirConnexion();
						$reqTEST = "SELECT * from tdf_epreuve where annee= '".$valeurAnnee."' and n_epreuve = '".$_POST['numeroEpr']."' ";
						$cur = preparerRequete($conn, $reqTEST);
						$tab = executerRequete($cur);
						$nbLignesBDD = oci_fetch_all($cur, $tab,0,-1,OCI_FETCHSTATEMENT_BY_ROW);

						if($nbLignesBDD != 0) {
							$erreur = 1;
							$valeurTestNumeroEpr = "Epreuve déjà existante cette année là.";
						}
						else {

						$reqINS = "INSERT INTO tdf_epreuve(annee, n_epreuve,ville_d,ville_a,distance,moyenne,code_tdf_d,code_tdf_a,jour,cat_code, compte_oracle, date_insert)
						values($valeurAnnee, $valeurNumeroEpr, 
							'".$valeurVilleD."','".$valeurVilleA."', 
							$valeurDistance, $valeurMoyenne, 
							'".$valeurCodeTDF_D."','".$valeurCodeTDF_A."', 
							'".$date."','".$valeurTestCAT."', user, sysdate)";

						$cur = preparerRequete($conn, $reqINS);
						$tab = executerRequete($cur);
						oci_commit($conn);
						$valeurTestAjout = "Epreuve correctement ajoutée.";
						}

						$valeurVilleD = "";
						$valeurVilleA = "";
						$valeurDistance = "";
						$valeurMoyenne = "";
						$valeurAnnee = "";
						$valeurNumeroEpr = "";
						$valeurCodeTDF_D = "";
						$valeurCodeTDF_A = "";
						$valeurJour = "";
						$valeurMois = "";
						$valeurTestCAT = "";
						
						FermerConnexion($conn);
					}
				}
				else{
					if(empty($_POST['villeD']))
						$valeurTestVilleD = "Veuillez saisir une ville valide.";

					if(empty($_POST['villeA']))
						$valeurTestVilleA = "Veuillez saisir une ville valide.";

					if(empty($_POST['distance']))
						$valeurTestDistance = "Veuillez saisir une distance valide.";

					if(empty($_POST['moyenne']))
						$valeurTestMoyenne =  "Veuillez saisir une moyenne valide.";

					if($_POST['annee'] == "Selectionnez une année")
						$valeurTestAnnee = "Veuillez sélectionner une annnée.";

					if($_POST['numeroEpr'] == "Selectionnez un numéro d'épreuve")
						$valeurTestNumeroEpr = "Veuillez sélectionner un numéro d'épreuve.";

					if($_POST['codeTDF_D'] == "Selectionnez un pays de depart")
						$valeurTestCodeTDF_D = "Veuillez sélectionner un pays de départ.";

					if($_POST['codeTDF_A'] == "Selectionnez un pays d")
						$valeurTestCodeTDF_A = "Veuillez sélectionner un pays d'arriver.";

					if($_POST['jour'] == "Selectionnez un jour")
						$valeurTestJour = "Veuillez sélectionner un jour.";

					if($_POST['mois'] == "Selectionnez un mois")
						$valeurTestMois = " Veuillez sélectionner un mois.";

					if($_POST['CAT'] == "Selectionnez une catégorie d'épreuve")
						$valeurTestCAT = "Veuillez sélectionner une catégorie d'épreuve.";
				}
			}
		
		?>
		
		
		
		<form name="formAddEpr" action="" method="post" >
			<div align="center" style="margin-left:10%; margin-right:10%">
				<fieldset >

					<table border=0 cellpadding=10>
						<tr>
							<td>
								Année<sup>*</sup> :
							</td>
							<td>
								<?php
									$conn = OuvrirConnexion();
									$req = 'SELECT annee from tdf_annee order by annee desc';
									$cur = preparerRequete($conn, $req);
									$tab = executerRequete($cur);
									$nbLignes = oci_fetch_all($cur, $tab,0,-1,OCI_FETCHSTATEMENT_BY_ROW);
									
									echo "<select name='annee' size=1>";
										echo "<option value='Selectionnez une année'>Selectionnez une année</option>";
										for ($i=0;$i<$nbLignes;$i++) {
											if($tab[$i]['ANNEE'] == $valeurAnnee)
												echo '<option value="'.$tab[$i]["ANNEE"].'" selected>'. $tab[$i]["ANNEE"];
											else
												echo '<option value="'.$tab[$i]["ANNEE"].'">'. $tab[$i]["ANNEE"];
											echo '</option>';
										} 	
									echo "</select> ";
									
									FermerConnexion($conn);
								?>
							</td>
							<td>
								<font color="red"><?php echo $valeurTestAnnee; ?></font>
							</td>
						</tr>
						<tr>
							<td>
								Numéro d'épreuve<sup>*</sup> :
							</td>
							<td>
								<?php
									echo "<select name='numeroEpr' size=1";
									echo "<option value='Selectionnez un numéro d'épreuve'>Selectionnez un numéro d'épreuve</option>";
									for($i = 0;$i <22; $i++){
										if($i == $valeurNumeroEpr)
											echo "<option value = $i selected>$i</option>";
										else
											echo "<option value = $i>$i</option>";
									}
									echo "</select>";
								?>
							</td>
							<td>
								<font color="red"><?php echo $valeurTestNumeroEpr; ?></font>
							</td>
						</tr>
						<tr>
							<td>
								Ville de départ<sup>*</sup> :
							</td>
							<td>
								<input type="text" name="villeD" size=40 placeholder="Entrez une ville de départ" value="<?php if(isset($valeurVilleD)) echo $valeurVilleD; ?>"> 
							</td>
							<td>
								<font color="red"><?php echo $valeurTestVilleD; ?></font>
							</td>
						</tr>
						<tr>
							<td>
								Ville d'arrivée<sup>*</sup> :
							</td>
							<td>
								<input type="text" name="villeA" size=40 placeholder="Entrez une ville d'arrivée" value="<?php if(isset($valeurVilleA)) echo $valeurVilleA; ?>">
							</td>
							<td>
								<font color="red"><?php echo $valeurTestVilleA; ?></font>
							</td>
						</tr>
						<tr>
							<td>
								Distance<sup>*</sup> :
							</td>
							<td>
								<input type="text" name="distance" size=32 placeholder="Entrez une distance" value="<?php if(isset($valeurDistance)) echo $valeurDistance; ?>">
							</td>
							<td>
								<font color="red"><?php echo $valeurTestDistance; ?></font>
							</td>
						</tr>
						<tr>
							<td>
								Moyenne<sup>*</sup> :
							</td>
							<td>
								<input type="text" name="moyenne" size=32 placeholder="Entrez une moyenne" value="<?php if(isset($valeurMoyenne)) echo $valeurMoyenne; ?>">
							</td>
							<td>
								<font color="red"><?php echo $valeurTestMoyenne; ?></font>
							</td>
						</tr>
						<tr>
							<td>
								Pays de depart<sup>*</sup> :
							</td>
							<td>
								<?php
									$conn = OuvrirConnexion();
									$req = 'SELECT code_tdf, c_pays, nom from TDF_PAYS where nom not like \'-%\' order by nom';
									$cur = preparerRequete($conn, $req);
									$tab = executerRequete($cur);
									$nbLignes = oci_fetch_all($cur, $tab,0,-1,OCI_FETCHSTATEMENT_BY_ROW);
									
									echo "<select name='codeTDF_D' size=1>";
										echo "<option value='Selectionnez un pays de depart'>Selectionnez un pays de depart</option>";
										for ($i=0;$i<$nbLignes;$i++) {
											if($tab[$i]["CODE_TDF"] == $valeurCodeTDF_D){
											echo '<option value="'.$tab[$i]["CODE_TDF"].'" selected>'.$tab[$i]["NOM"];
											}
											else{
											echo '<option value="'.$tab[$i]["CODE_TDF"].'">'.$tab[$i]["NOM"];
											}
										  echo '</option>';
										} 	
									echo "</select> ";
									FermerConnexion($conn);
								?>	
							</td>
							<td>
								<font color='red'><?php echo $valeurTestCodeTDF_D; ?></font>
							</td>
						</tr>
						<tr>
							<td>
								Pays d'arriver<sup>*</sup> :
							</td>
							<td>
								<?php
									$conn = OuvrirConnexion();
									$req = 'SELECT code_tdf, c_pays, nom from TDF_PAYS where nom not like \'-%\' order by nom';
									$cur = preparerRequete($conn, $req);
									$tab = executerRequete($cur);
									$nbLignes = oci_fetch_all($cur, $tab,0,-1,OCI_FETCHSTATEMENT_BY_ROW);
									
									echo "<select name='codeTDF_A' size=1>";
										echo "<option value='Selectionnez un pays d\'arriver'>Selectionnez un pays d'arriver</option>";
										for ($i=0;$i<$nbLignes;$i++) {
											if($tab[$i]["CODE_TDF"] == $valeurCodeTDF_A){
											echo '<option value="'.$tab[$i]["CODE_TDF"].'" selected>'.$tab[$i]["NOM"];
											}
											else{
											echo '<option value="'.$tab[$i]["CODE_TDF"].'">'.$tab[$i]["NOM"];
											}
										  echo '</option>';
										} 	
									echo "</select> ";
									FermerConnexion($conn);
								?>	
							</td>
							<td>
								<font color='red'><?php echo $valeurTestCodeTDF_A; ?></font>
							</td>
						</tr>
						<tr>
							<td>
								Date de l'épreuve<sup>*</sup> :
							</td>
							<td>
							<?php
								echo "<select name='jour' size=1>";
									echo "<option value='Selectionnez un jour'>Selectionnez un jour</option>";
									for($i = 1;$i <32; $i++) {
										if($i == $valeurJour)
											echo "<option value = $i selected>$i</option>";
										else
											echo "<option value = $i>$i</option>";
									}
								echo "</select>";

								echo "<select  name='mois' size=1>";
									echo "<option value='Selectionnez un mois'>Selectionnez un mois</option>";
									for($i = 1;$i <13; $i++) {
										if($i == $valeurMois)
											echo "<option value = $i selected>$i</option>";
										else
											echo "<option value = $i>$i</option>";
									}
								echo "</select>";
							?>
							</td>
							<td>
								<font color='red'><?php echo $valeurTestJour; echo $valeurTestMois; ?></font>
							</td>
						</tr>
							<td>
								Catégorie de l'épreuve<sup>*</sup> :
							</td>
							<td>
								<?php
									echo "<select name='CAT' size=1>";
									echo "<option value='Selectionnez une catégorie d'épreuve'>Selectionnez un type d'épreuve</option>";

										if($valeurTestCAT == 'ETA')
											echo "<option value='ETA' selected>Etape normale</option>";
										else
											echo "<option value='ETA'>Etape normale</option>";

										if($valeurTestCAT == 'CMI')
											echo "<option value='CMI' selected>Contre-la-montre individuel</option>";
										else
											echo "<option value='CMI'>Contre-la-montre individuel</option>";

										if($valeurTestCAT == 'CME')
											echo "<option value='CME' selected>Contre-la-montre par équipe</option>";
										else
											echo "<option value='CME'>Contre-la-montre par équipe</option>";

									echo "</select>";
								?>
							</td>
							<td>
								<font color='red'><?php echo $valeurTestCAT; ?></font>
							</td>
						<tr>
							<td>
								<font size=1><sup>*</sup>Champs obligatoires</font>
							</td>
							<td align="center">
								<input type='submit' name='Ajouter' value='Ajouter une épreuve' >
								
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
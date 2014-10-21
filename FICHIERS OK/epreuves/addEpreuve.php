<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html;">
		<meta charset="utf-8">
		<link rel="icon" type="image/png" href="favicon.ico" />
		<title>Ajout d'une épreuve || Mise à jour TDF</title>
	</head>
	<body>
		<?php
			include ('topMenu.php');
			include ('Econnexion.php');
			include ('fonc_regex.php');
		?>
		<h2 align="center">Ajout d' une épreuve</h2>
		
		<!-- FORMULAIRE D'AJOUT DE COUREUR -->
		<?php
			//initialisation des variables
			if(!isset($valeurTestAnnee))
				$valeurTestAnnee ="";
			if(!isset($valeurTestNumero))
				$valeurTestNumero ="";
			if(!isset($valeurTestVilleDepart))
				$valeurTestVilleDepart ="";
			if(!isset($valeurTestVilleArriver))
				$valeurTestVilleArriver = "";
			if(!isset($valeurTestDistance))
				$valeurTestDistance = "";
			if(!isset($valeurTestMoyenne))
				$valeurTestMoyenne = "";
			if(!isset($valeurTestPaysDepart))
				$valeurTestPaysDepart = "";
			if(!isset($valeurTestPaysArriver))
				$valeurTestPaysArriver = "";
			if(!isset($valeurTestJour))
				$valeurTestJour ="";
			if(!isset($valeurTestMois))
				$valeurTestMois = "";
			if(!isset($valeurTestType))
				$valeurTestType = "";
			if(!isset($valeurTestAjout))
				$valeurTestAjout = "";
			if(!isset($valeurAnnee))
				$valeurAnnee = "";
			if(!isset($valeurNumero))
				$valeurNumero = "";
			if(!isset($valeurPaysDepart))
				$valeurPaysDepart = "";
			if(!isset($valeurPaysArriver))
				$valeurPaysArriver = "";
			if(!isset($valeurJour ))
				$valeurJour  = "";
			if(!isset($valeurMois ))
				$valeurMois  = "";
			if(!isset($valeurType ))
				$valeurType  = "";
			$erreur = 0;
		
			//on analyse l'enregistrement
			if(isset($_POST['Ajouter'])){
				$valeurVilleDepart = $_POST['villeDepart'];
				$valeurVilleArriver = $_POST['villeArriver'];
				$valeurDistance = $_POST['distance'];
				$valeurAnnee = $_POST['annee'];
				$valeurNumero = $_POST['numEpreuve'];
				$valeurPaysDepart = $_POST['paysDepart'];
				$valeurPaysArriver = $_POST['paysArriver'];
				$valeurJour = $_POST['jourEpreuve'];
				$valeurMois = $_POST['moisEpreuve'];
				$valeurType = $_POST['typeEpreuve'];
				$valeurMoyenne = $_POST['moyenne'];
				//on test si les champs ont bien été rempli
				if($_POST['annee'] != "Selectionnez une année" && $_POST['numEpreuve'] != "Selectionnez un numéro d'épreuve" 
					&& $_POST['villeDepart'] != null && $_POST['villeArriver'] != null && $_POST['distance'] != null
					&& $_POST['paysDepart'] != "Selectionnez un pays de depart" && $_POST['paysArriver'] != "Selectionnez un pays d'arriver"
					&& $_POST['jourEpreuve'] != "Selectionnez un jour" && $_POST['moisEpreuve'] != "Selectionnez un mois" 
					&& $_POST['typeEpreuve'] != "Selectionnez un type d'épreuve"){
					//analyse de ce qui est envoyer
					
					$erreur = 0;

					//on verifie si il y a pas deja une epreuve de ce numero et de cette année
					$login = 'copie_tdf';
					$mdp = 'copie_tdf';
					$instance = 'xe';
					
					$conn = OuvrirConnexion($login, $mdp,$instance);
					$req = "SELECT count(*) as TOTAL from tdf_epreuve where annee=$valeurAnnee and n_epreuve = $valeurNumero";
					$countEpreuve = preparerRequete($conn, $req);
					$count = executerRequete($countEpreuve);
					$nbEnregistrement = array();
					oci_fetch_all($countEpreuve, $nbEnregistrement);
					$total = $nbEnregistrement['TOTAL'][0];

					if($total > 0){
						$erreur = 1;
						$valeurTestNumero = "Il existe déjà une épreuve ayant cette année($valeurAnnee) et ce numéro($valeurNumero)";
					}

					//ville depart
					if(!testNom($valeurVilleDepart)){ //on utilise testNom pour tester et si c'est bon renmplacer la ville de départ
						$erreur = 1;
						$valeurTestVilleDepart = "Veuillez entrer une ville de depart valide !";
					}else{
						$valeurVilleDepart = testNom($valeurVilleDepart);
					}

					//ville arriver
					if(!testNom($valeurVilleArriver)){//on utilise testNom pour tester et si c'est bon renmplacer la ville d'arrivée
						$erreur = 1;
						$valeurTestVilleArriver = "Veuillez entrer une ville d'arrivée valide !";
					}else{
						$valeurVilleArriver = testNom($valeurVilleArriver);
					}

					//si la distance est numeric
					if(!is_numeric($valeurDistance)){
						$erreur = 1;
						$valeurTestDistance = "Veuillez entrer une distance valide !";
					}

					//si la moyenne est numeric
					if($valeurMoyenne != ""){
						if(!is_numeric($_POST['moyenne'])){
							$erreur = 1;
							$valeurTestMoyenne = "Veuillez entrer une moyenne valide !";
						}
					}

					//on établie la date
					$date=$valeurJour."/".$valeurMois."/".substr($valeurAnnee,2,4);



					if($erreur != 1){
						//requete insertion
						$login = 'copie_tdf';
						$mdp = 'copie_tdf';
						$instance = 'xe';
						
						$conn = OuvrirConnexion($login, $mdp,$instance);
						$maxNCoureur = preparerRequete($conn, "SELECT max(n_coureur) as maxi from tdf_coureur");
						$maxNcour = executerRequete($maxNCoureur);
						
						//on cree un tableau
						$maxNumArray = array();
						
						//on y met le resultat de la requete
						oci_fetch_all($maxNCoureur, $maxNumArray);
						
						//on recupert le resultat de la requete et on lui donne la valeur +5
						$numCoureur = $maxNumArray['MAXI'][0];
						$numCoureur = $numCoureur+5;
						
						//on fait l'include
						$req = "INSERT INTO tdf_epreuve(annee, n_epreuve,ville_d,ville_a,distance,moyenne,code_tdf_d,code_tdf_a,jour,cat_code, compte_oracle, date_insert)
						values($valeurAnnee,$valeurNumero,'".$valeurVilleDepart."','".$valeurVilleArriver."',$valeurDistance,$valeurMoyenne,'".$valeurPaysDepart."','".$valeurPaysArriver."'
							,'".$date."','".$valeurType."', user, sysdate)";
						
						$cur = preparerRequete($conn, $req);
						$tab = executerRequete($cur);
						oci_commit($conn);
						$valeurTestAjout = "Epreuve correctement ajouter !";
						

						//on met toute les valeur a 0
						$valeurVilleDepart = "";
						$valeurVilleArriver = "";
						$valeurDistance = "";
						$valeurAnnee = "";
						$valeurNumero = "";
						$valeurPaysDepart = "";
						$valeurPaysArriver = "";
						$valeurJour = "";
						$valeurMois = "";
						$valeurType = "";
						$valeurMoyenne = "";
						
						FermerConnexion($conn);
					}
				}
				else{
					if(empty($_POST['villeDepart']))
						$valeurTestVilleDepart = "Veuillez entrer une ville de départ !";
					if(empty($_POST['villeArriver']))
						$valeurTestVilleArriver = "Veuillez entrer une ville de départ !";
					if(empty($_POST['distance']))
						$valeurTestDistance = "Veuillez entrer une distance !";
					if($_POST['annee'] == "Selectionnez une année")
						$valeurTestAnnee = "Veuillez choisir une annnée !";
					if($_POST['numEpreuve'] == "Selectionnez un numéro d'épreuve")
						$valeurTestNumero = "Veuillez choisir un numéro d'épreuve !";
					if($_POST['paysDepart'] == "Selectionnez un pays de depart")
						$valeurTestPaysDepart = "Veuillez choisir un pays de départ !";
					if($_POST['paysArriver'] == "Selectionnez un pays d")
						$valeurTestPaysArriver = "Veuillez choisir un pays d'arriver !";
					if($_POST['jourEpreuve'] == "Selectionnez un jour")
						$valeurTestJour = "Veuillez choisir un jour !";
					if($_POST['moisEpreuve'] == "Selectionnez un mois")
						$valeurTestMois = " Veuillez choisir un mois !";
					if($_POST['typeEpreuve'] == "Selectionnez un type d")
						$valeurTestType = "Veuillez choisir un type d'épreuve !";
					

					
				}
			}
		
		
		
		?>
		
		
		
		<form name="formAjoutEpreuve" action="<?php $_SERVER['PHP_SELF'] ?>" method="post" >
			<div align="center" style="margin-left:20%; margin-right:20%">
				<fieldset >
					<legend>Ajout d' épreuve</legend>
					<table border=0 cellpadding=10>
						<tr>
							<td>
								Année * :
							</td>
							<td>
								<?php
									$login = 'copie_tdf';
									$mdp = 'copie_tdf';
									$instance = 'xe';
									$conn = OuvrirConnexion($login, $mdp,$instance);
									$req = 'select annee from tdf_annee order by annee desc';
									$cur = preparerRequete($conn, $req);
									$tab = executerRequete($cur);
									$nbLignes = oci_fetch_all($cur, $tab,0,-1,OCI_FETCHSTATEMENT_BY_ROW);
									
									echo "<select name='annee' size=1>";
										echo "<option value='Selectionnez une année'>Selectionnez une année</option>";
										for ($i=0;$i<$nbLignes;$i++){//si la valeur de la case du tab vaut la valeur entré précédement alors elle est selected
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
								Numéro * :
							</td>
							<td>
								<?php
									echo "<select width='500px' name='numEpreuve' size=1";
									echo "<option value='Selectionnez un numéro d'épreuve'>Selectionnez un numéro d'épreuve</option>";
									for($i = 0;$i <31; $i++){//si la valeur de la case du tab vaut la valeur entré précédement alors elle est selected
										if($i == $valeurNumero)
											echo "<option value = $i selected>$i</option>";
										else
											echo "<option value = $i>$i</option>";
									}
									echo "</select>";
								?>
							</td>
							<td>
								<font color="red"><?php echo $valeurTestNumero; ?></font>
							</td>
						</tr>
						<tr>
							<td>
								Ville de départ * :
							</td>
							<td>
								<input type="text" name="villeDepart" size=32 placeholder="Entrez une ville de départ" value="<?php if(isset($valeurVilleDepart)) echo $valeurVilleDepart; ?>"> 
							</td>
							<td>
								<font color="red"><?php echo $valeurTestVilleDepart; ?></font>
							</td>
						</tr>
						<tr>
							<td>
								Ville d'arrivée * :
							</td>
							<td>
								<input type="text" name="villeArriver" size=32 placeholder="Entrez une ville d'arrivée" value="<?php if(isset($valeurVilleArriver)) echo $valeurVilleArriver; ?>">
							</td>
							<td>
								<font color="red"><?php echo $valeurTestVilleArriver; ?></font>
							</td>
						</tr>
						<tr>
							<td>
								Distance * :
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
								Moyenne :
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
								Pays de depart * :
							</td>
							<td>
								<?php
									$login = 'copie_tdf';
									$mdp = 'copie_tdf';
									$instance = 'xe';
									$conn = OuvrirConnexion($login, $mdp,$instance);
									$req = 'SELECT code_tdf, c_pays, nom from TDF_PAYS order by nom';
									$cur = preparerRequete($conn, $req);
									$tab = executerRequete($cur);
									FermerConnexion($conn);
									$nbLignes = oci_fetch_all($cur, $tab,0,-1,OCI_FETCHSTATEMENT_BY_ROW);
									
									echo "<select name='paysDepart' size=1>";
										echo "<option value='Selectionnez un pays de depart'>Selectionnez un pays de depart</option>";
										for ($i=0;$i<$nbLignes;$i++){//si la valeur de la case du tab vaut la valeur entré précédement alors elle est selected
											if($tab[$i]["CODE_TDF"] == $valeurPaysDepart){
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
								<font color='red'><?php echo $valeurTestPaysDepart; ?></font>
							</td>
						</tr>
						<tr>
							<td>
								Pays d'arriver * :
							</td>
							<td>
								<?php
									$login = 'copie_tdf';
									$mdp = 'copie_tdf';
									$instance = 'xe';
									$conn = OuvrirConnexion($login, $mdp,$instance);
									$req = 'SELECT code_tdf, c_pays, nom from TDF_PAYS order by nom';
									$cur = preparerRequete($conn, $req);
									$tab = executerRequete($cur);
									FermerConnexion($conn);
									$nbLignes = oci_fetch_all($cur, $tab,0,-1,OCI_FETCHSTATEMENT_BY_ROW);
									
									echo "<select name='paysArriver' size=1>";
										echo "<option value='Selectionnez un pays d\'arriver'>Selectionnez un pays d'arriver</option>";
										for ($i=0;$i<$nbLignes;$i++){//si la valeur de la case du tab vaut la valeur entré précédement alors elle est selected
											if($tab[$i]["CODE_TDF"] == $valeurPaysArriver){
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
								<font color='red'><?php echo $valeurTestPaysArriver; ?></font>
							</td>
						</tr>
						<tr>
							<td>
								Date de l'épreuve * :
							</td>
							<td>
							<?php
								echo "<select name='jourEpreuve' size=1>";
									echo "<option value='Selectionnez un jour'>Selectionnez un jour</option>";
									for($i = 1;$i <32; $i++){//si la valeur de la case du tab vaut la valeur entré précédement alors elle est selected
										if($i == $valeurJour)
											echo "<option value = $i selected>$i</option>";
										else
											echo "<option value = $i>$i</option>";
									}
								echo "</select>";
								echo "<select  name='moisEpreuve' size=1>";
									echo "<option value='Selectionnez un mois'>Selectionnez un mois</option>";
									if($valeurMois == '07'){
										echo "<option value='07' selected>Juillet</option>";
									}else{
										echo "<option value='07'>Juillet</option>";
									}
									if($valeurMois == '08'){
										echo "<option value='08' selected>Août</option>";
									}else{
										echo "<option value='08'>Août</option>";
									}
								echo "</select>";
							?>
							</td>
							<td>
								<font color='red'><?php echo $valeurTestJour; echo $valeurTestMois; ?></font>
							</td>
						</tr>
							<td>
								Type de l'épreuve * :
							</td>
							<td>
								<?php
									echo "<select name='typeEpreuve' size=1>";
									echo "<option value='Selectionnez un type d'épreuve'>Selectionnez un type d'épreuve</option>";
									if($valeurType == 'ETA')
										echo "<option value='ETA' selected>Etape normale</option>";
									else
										echo "<option value='ETA'>Etape normale</option>";
									if($valeurType == 'CMI')
										echo "<option value='CMI' selected>Contre-la-montre individuel</option>";
									else
										echo "<option value='CMI'>Contre-la-montre individuel</option>";
									if($valeurType == 'CME')
										echo "<option value='CME' selected>Contre-la-montre par équipe</option>";
									else
										echo "<option value='CME'>Contre-la-montre par équipe</option>";
									echo "</select>";
								?>
							</td>
							<td>
								<font color='red'><?php echo $valeurTestType; ?></font>
							</td>
						<tr>
							<td>
								* Partie obligatoire !
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
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html;">
		<meta charset="utf-8">
		<link rel="icon" type="image/png" href="favicon.ico" />
		<title>Modification d'une épreuve || Mise à jour TDF</title>
	</head>
	<body>
		<?php
			include ('topMenu.php');
			include ('Econnexion.php');
			include ('fonc_regex.php');
		?>
		<h2 align="center">Modification d' une épreuve</h2>
		
		<!-- FORMULAIRE D'AJOUT DE COUREUR -->
		<?php
			//initialisation des variables
			if(!isset($valeurTestRownum))
				$valeurTestRownum = "";
			if(!isset($valeurTestVilleDepart))
				$valeurTestVilleDepart ="";
			if(!isset($valeurTestVilleArriver))
				$valeurTestVilleArriver = "";
			if(!isset($valeurTestDistance))
				$valeurTestDistance = "";
			if(!isset($valeurTestMoyenne))
				$valeurTestMoyenne = "";
			if(!isset($valeurTestJour))
				$valeurTestJour ="";
			if(!isset($valeurTestModif))
				$valeurTestModif = "";
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
			if(!isset($valeurRownum ))
				$valeurRownum  = "";
				
			
			
			$erreur = 0;
		
			//on analyse l'enregistrement
			if(isset($_POST['Modifier'])){
				
				//on test si les champs ont bien été rempli
				if($_POST['rownum'] != "Selectionnez une épreuve"){
					if($_POST['villeDepart'] != "" || $_POST['villeArriver'] != "" ||  $_POST['paysDepart'] != "Selectionnez un pays de depart"
						|| $_POST['paysArriver'] != "Selectionnez un pays d" || $_POST['distance'] != "" || $_POST['moyenne'] != ""
						|| $_POST['jourEpreuve'] != "Selectionnez un jour" || $_POST['moisEpreuve'] != "Selectionnez un mois" 
						|| $_POST['typeEpreuve'] != "Selectionnez un type d"){
					//analyse de ce qui est envoyer
						$erreur = 0;
						$valeurRequeteInsertion = "";
						$valeurRownum = $_POST['rownum'];

						$login = 'copie_tdf';
						$mdp = 'copie_tdf';
						$instance = 'xe';
						
						$conn = OuvrirConnexion($login, $mdp,$instance);
						
						//on recupert l'annee et le n_epreuve
						$req = 'select annee, n_epreuve from tdf_epreuve order by annee desc, n_epreuve desc';
						$cur = preparerRequete($conn, $req);
						$tab = executerRequete($cur);
						$nbLignes = oci_fetch_all($cur, $tab,0,-1,OCI_FETCHSTATEMENT_BY_ROW);
						
						for ($i=0;$i<$nbLignes;$i++){
							if($i == $valeurRownum){
								$valeurAnnee = $tab[$i]['ANNEE'];
								$valeurNumEpreuve = $tab[$i]['N_EPREUVE'];
							}
						} 	
						
						/*
							On etablie une variable que l'on complet avec ce que l'on va modifier

						*/

						if($_POST['villeDepart']  != ""){
							$valeurVilleDepart = testNom($_POST['villeDepart']);
							if(!testNom($_POST['villeDepart'])){//on tst si la valeur entré est correcte 

								$erreur = 1;
								$valeurTestVilleDepart = "Veuillez entrer une ville de départ valide !";
							}else{
								$valeurRequeteInsertion = " ville_d = '".testNom($_POST['villeDepart'])."'";
							}
						}

						if($_POST['villeArriver']  != ""){
							$valeurVilleArriver = testNom($_POST['villeArriver']);
							if(!testNom($_POST['villeArriver'])){//on tst si la valeur entré est correcte 

								$erreur = 1;
								$valeurTestVilleDepart = "Veuillez entrer une ville d'arrivée valide !";
							}else{
								if($valeurRequeteInsertion == "")
									$valeurRequeteInsertion = " ville_a = '".testNom($_POST['villeArriver'])."'";
								else
									$valeurRequeteInsertion = $valeurRequeteInsertion.", ville_a = '".testNom($_POST['villeArriver'])."'";
							}
						}
						
						if($_POST['distance'] != ""){
							$valeurDistance = $_POST['distance'];
							if(is_numeric($_POST['distance'])){//on tst si la valeur entré est correcte 
								if($valeurRequeteInsertion == "")
									$valeurRequeteInsertion = " distance =".$_POST['distance'];
								else
									$valeurRequeteInsertion = $valeurRequeteInsertion.", distance =".$_POST['distance'];
							}else{
								$erreur = 1;
								$valeurTestDistance = "Veuillez entrer une distance valide !";
							}
						}

						if($_POST['moyenne'] != ""){
							$valeurMoyenne = $_POST['moyenne'];
							if(is_numeric($_POST['moyenne'])){//on tst si la valeur entré est correcte 

								if($valeurRequeteInsertion == "")
									$valeurRequeteInsertion = " moyenne =".$_POST['moyenne'];
								else
									$valeurRequeteInsertion = $valeurRequeteInsertion.", moyenne =".$_POST['moyenne'];
							}else{
								$erreur = 1;
								$valeurTestMoyenne = "Veuillez entrer une moyenne valide !";
							}
						}

						if($_POST['paysDepart'] != "Selectionnez un pays de depart"){//on tst si la valeur entré est correcte 

							$valeurPaysDepart = $_POST['paysDepart'];
							if($valeurRequeteInsertion == "")
								$valeurRequeteInsertion = " code_tdf_d = '".$_POST['paysDepart']."'";
							else
								$valeurRequeteInsertion = $valeurRequeteInsertion.", code_tdf_d = '".$_POST['paysDepart']."'";
							
						}

						if($_POST['paysArriver'] != "Selectionnez un pays d"){//on tst si la valeur entré est correcte 

							$valeurPaysArriver = $_POST['paysArriver'];
							if($valeurRequeteInsertion == "")
								$valeurRequeteInsertion = " code_tdf_a = '".$_POST['paysArriver']."'";
							else
								$valeurRequeteInsertion = $valeurRequeteInsertion.", code_tdf_a = '".$_POST['paysArriver']."'";
							
						}

						if($_POST['jourEpreuve'] != "Selectionnez un jour" || $_POST['moisEpreuve'] != "Selectionnez un mois"){
							$valeurJour = $_POST['jourEpreuve'];
							$valeurMois = $_POST['moisEpreuve'];
							if($_POST['jourEpreuve'] != "Selectionnez un jour" && $_POST['moisEpreuve'] != "Selectionnez un mois"){//on tst si la valeur entré est correcte 

								$date = $_POST['jourEpreuve']."/".$_POST['moisEpreuve']."/".substr($valeurAnnee,2,4);
								if($valeurRequeteInsertion == "")
									$valeurRequeteInsertion = " jour = '".$date."'";
								else
									$valeurRequeteInsertion = $valeurRequeteInsertion.", jour = '".$date."'";
							
							}else{
								$valeurTestJour = "Veuillez entrer la date complète pour effectuer la modification !";
								$erreur = 1;
							}
						}

						if($_POST['typeEpreuve'] != "Selectionnez un type d"){
							$valeurType = $_POST['typeEpreuve'];
							if($valeurRequeteInsertion == "")
								$valeurRequeteInsertion = " cat_code = '".$_POST['typeEpreuve']."'";
							else
								$valeurRequeteInsertion = $valeurRequeteInsertion.", cat_code = '".$_POST['typeEpreuve']."'";
							
						}



						if($erreur != 1){
							

							//on fait l'update
							$req = "UPDATE tdf_epreuve set ".$valeurRequeteInsertion." where annee =".$valeurAnnee." and n_epreuve =".$valeurNumEpreuve;
							echo $req;
							$cur = preparerRequete($conn, $req);
							$tab = executerRequete($cur);
							oci_commit($conn);
							$valeurTestModif = "Epreuve correctement modifiée !";
							
							//reinitialisation des variables
							$valeurRownum = "";
							$valeurVilleDepart = "";
							$valeurVilleArriver = "";
							$valeurDistance = "";
							$valeurMoyenne = "";
							$valeurPaysDepart = "";
							$valeurPaysArriver = "";
							$valeurJour = "";
							$valeurMois = "";
							$valeurType = "";

							
							FermerConnexion($conn);
						}
					}else{//si tout les champs sont vides
						$valeurTestRownum = "Veuillez modifier au moins un des champs !";

						$valeurRownum = $_POST['rownum'];
					}
				}
				else{ //si aucun rownum est selectionné
					$valeurTestRownum = "Veuillez choisir une épreuve à modifier !";

					
				}
			}
		
		
		
		?>
		
		
		
		<form name="formAjoutEpreuve" action="<?php $_SERVER['PHP_SELF'] ?>" method="post" >
			<div align="center" style="margin-left:10%; margin-right:10%">
				<fieldset >
					<legend>Modification d' épreuve</legend>
					<table border=0 cellpadding=10>
						<tr>
							<td width="250">
								Epreuve à modifier :
							</td>
							<td>
								<?php
									$login = 'copie_tdf';
									$mdp = 'copie_tdf';
									$instance = 'xe';
									$conn = OuvrirConnexion($login, $mdp,$instance);
									$req = 'select annee, n_epreuve, ville_d, ville_a from tdf_epreuve order by annee desc, n_epreuve desc';
									$cur = preparerRequete($conn, $req);
									$tab = executerRequete($cur);
									$nbLignes = oci_fetch_all($cur, $tab,0,-1,OCI_FETCHSTATEMENT_BY_ROW);
									
									echo "<select name='rownum' size=1>";
										echo "<option value='Selectionnez une épreuve'>Selectionnez une épreuve</option>";
										for ($i=0;$i<$nbLignes;$i++){//si la valeur de la case du tab vaut la valeur entré précédement alors elle est selected
											if($i == $valeurRownum)
												echo '<option value="'.$i.'" selected>'. $tab[$i]["ANNEE"]." | Numéro : ".$tab[$i]["N_EPREUVE"]." | Depart : ".$tab[$i]["VILLE_D"].
											" | Arrivée : ".$tab[$i]["VILLE_A"];
											else
												echo '<option value="'.$i.'">'. $tab[$i]["ANNEE"]." | Numéro : ".$tab[$i]["N_EPREUVE"]." | Depart : ".$tab[$i]["VILLE_D"].
											" | Arrivée : ".$tab[$i]["VILLE_A"];
											echo '</option>';
										} 	
									echo "</select> ";
									
									FermerConnexion($conn);
								?>
							</td>
							<td width="300">
								<font color="red"><?php echo $valeurTestRownum; ?></font>
							</td>
						</tr>
						<tr>
							<td>
								Nouvelle ville de départ * :
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
								Nouvelle ville d'arrivée * :
							</td>
							<td>
								<input type="text" name="villeArriver" size=32 placeholder="Entrez une ville d'arriver" value="<?php if(isset($valeurVilleArriver)) echo $valeurVilleArriver; ?>">
							</td>
							<td>
								<font color="red"><?php echo $valeurTestVilleArriver; ?></font>
							</td>
						</tr>
						<tr>
							<td>
								Nouvelle distance * :
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
								Nouvelle moyenne * :
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
								Nouveau pays de depart * :
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
								
							</td>
						</tr>
						<tr>
							<td>
								Nouveau pays d'arriver * :
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
										echo "<option value='Selectionnez un pays d'>Selectionnez un pays d'arriver</option>";
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
								
							</td>
						</tr>
						<tr>
							<td>
								Nouvelle date de l'épreuve * :
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
								<font color='red'><?php echo $valeurTestJour;?></font>
							</td>
						</tr>
							<td>
								Nouveau type de l'épreuve * :
							</td>
							<td>
								<?php
									echo "<select name='typeEpreuve' size=1>";
									echo "<option value='Selectionnez un type d'>Selectionnez un type d'épreuve</option>";
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
								
							</td>
						<tr>
							<td>
								* Si vide ou par defaut alors aucune modification.
							</td>
							<td align="center">
								<input type='submit' name='Modifier' value='Modifier une épreuve' >
								
							</td>
							<td>
								<font color='green'><?php echo $valeurTestModif; ?></font>
							</td>
						</tr>
					</table>
					
				</fieldset>
			</div>
		</form>
		
		
	</body>
</html>
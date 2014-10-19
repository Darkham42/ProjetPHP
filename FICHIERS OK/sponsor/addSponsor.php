<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html;">
		<meta charset="utf-8">
		<title>Ajouter un sponsor au Tour de France</title>
	</head>
	<body>

		<?php
			include_once ('../menuBarre.php');
			include_once ('../connBDD.php');
			include_once ('../fonc_test.php');
		?>

		<h2 align="center">Ajout d'un sponsor</h2>

	<!-- FORMULAIRE POUR MODIFIER UN COUREUR DE LA BASE -->

<?php
if(!isset($valeurTestEquipe)){
				$valeurTestEquipe="";	
			}
			if(!isset($valeurTestNom)){
				$valeurTestNom="";
			}
			if(!isset($valeurTestTag)){
				$valeurTestTag="";
			}
			if(!isset($valeurPays)){
				$valeurTestPays="";
			}
			$erreur=0;
			$tag="";
			$nom="";
?>
<?php
			//on analyse l'enregistrement
			if(isset($_POST['AjouterSponsor'])){
				//on test si les champs ont bien été rempli
				if(($_POST['equipe'] != "Selectionnez une equipe")&&($_POST['nom'] != "")&&($_POST['tag'] != "")&&($_POST['annee'] != "Selectionnez une annee")){
					//traitement de l'ajout
					//Test du nom de sponsor
					if(!testSponsor($_POST['nom'])){
							$erreur = 1;
							$valeurTestNom="Veuillez entrer un nom valide !";
						}
					else{
						//on transforme la valeur du nom
						$valeurSponsor = testSponsor($_POST['nom']);
						$nom = $valeurSponsor;
					}
					//Test du nom du tag
					$tagValue=testTag($_POST['tag']);
					if($tagValue==1){
							$erreur=1;
							$valeurTestTag="Veuillez entrer un Tag à 3 caractères";
					}
					if($tagValue==-1){
							$erreur=1;
							$valeurTestTag="Veuillez entrer un Tag alphanumérique";
					}
					else{
							$valeurTag = $tagValue;
							$tag = $valeurTag;
					}
					
					//requete 
					$conn = OuvrirConnexion();
					$req = "select max(n_sponsor)+1 as nb_sponsor from tdf_sponsor where n_equipe=".$_POST['equipe']." group by n_equipe";
					$cur = preparerRequete($conn, $req);
					$tab1 = executerRequete($cur);
					FermerConnexion($conn);
					$maxNumSponsor = array();
					oci_fetch_all($cur,$maxNumSponsor);
					$numSponsor=$maxNumSponsor['NB_SPONSOR'][0];
					//requete ajout
					$conn = OuvrirConnexion();
					$req = "insert into tdf_sponsor (n_equipe,n_sponsor,nom,na_sponsor,code_tdf,annee_sponsor,compte_oracle,date_insert) values (".$_POST['equipe'].",".$numSponsor.",'".$nom."','".$tag."','".$_POST['pays']."',".$_POST['annee'].",user,sysdate)";
					$cur = preparerRequete($conn, $req);
					$tab = executerRequete($cur);
					oci_commit($conn);

					FermerConnexion($conn);
					
					$valeurTestEquipe="";
				}
				else{
					if($_POST['equipe']=="Selectionnez une equipe"){
						$valeurTestEquipe = "Veuillez selectionner une equipe";
					}
					if($_POST['nom']==""){
						$valeurTestNom = "Veuillez entrer un nom";
					}
					if($_POST['tag']==""){
						$valeurTestTag = "Veuillez entrer un tag";
					}
					if($_POST['annee']=="Selectionnez une annee"){
						$valeurTestAnnee = "Veuillez selectionner une annee";
					}
										
				}
			}
		?>
		<form name="formAjoutCoureur" action="<?php $_SERVER['PHP_SELF'] ?>" method="post" >
			<div align="center" style="margin-left:20%; margin-right:20%">
				<fieldset >
					<legend>Ajout d'un sponsor</legend>
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
									$req = 'select n_equipe,nom from tdf_equipe join tdf_sponsor using (n_equipe) where annee_disparition is null and (n_equipe,n_sponsor) in (select n_equipe,max(n_sponsor) as dernier_sponsor from tdf_sponsor group by n_equipe) order by nom';
									$cur = preparerRequete($conn, $req);
									$tab2 = executerRequete($cur);
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
								<font color='red'><?php echo $valeurTestEquipe; ?></font>
							</td>
							</tr>
							<tr>
							<td>
							Nom :
							</td>
							<td>
								<input type="text" name="nom" size=32 value="<?php if(isset($valeurNom)) echo $valeurNom ?>" placeholder="Entrez un nom">
							</td>
							<td>
							<font color='red'><?php echo $valeurTestNom; ?></font>
							</td>
							</tr>
							<tr>
							<td>
							Tag :
							</td>
							<td>
								<input type="text" name="tag" size=32 value="<?php if(isset($valeurTag)) echo $valeurTag; ?>" placeholder="Entrez un tag">
							</td>
							<td>
							<font color='red'><?php echo $valeurTestTag; ?></font>
							</td>
							</tr>
							<tr>
							<td>
								Pays :
							</td>
							<td>
								<?php
									$login = 'copie_tdf';
									$mdp = 'copie_tdf';
									$instance = 'xe';
									$conn = OuvrirConnexion($login, $mdp,$instance);
									$req = 'SELECT code_tdf, c_pays, nom from TDF_PAYS order by nom';
									$cur = preparerRequete($conn, $req);
									$tab3 = executerRequete($cur);
									FermerConnexion($conn);
									$nbLignes = oci_fetch_all($cur, $tab3,0,-1,OCI_FETCHSTATEMENT_BY_ROW);
									
									echo "<select name='pays' size=1>";
										echo "<option value='Selectionnez un pays'>Selectionnez un pays</option>";
										for ($i=0;$i<$nbLignes;$i++){
											echo '<option value="'.$tab3[$i]["CODE_TDF"].'">'.$tab3[$i]["NOM"];
										  echo '</option>';
										} 	
									echo "</select> ";
									
								?>	
							</td>
							<td>
								<font color='red'><?php echo $valeurTestPays; ?></font>
							</td>
						</tr>
						<tr>
							<tr>
							<td>
							Annee :
							</td>
							<td>
							<?php
									$login = 'copie_tdf';
									$mdp = 'copie_tdf';
									$instance = 'xe';
									$conn = OuvrirConnexion($login, $mdp,$instance);
									$req = 'select unique(annee_sponsor) from tdf_sponsor order by annee_sponsor desc';
									$cur = preparerRequete($conn, $req);
									$tab3 = executerRequete($cur);
									FermerConnexion($conn);
									$nbLignes = oci_fetch_all($cur, $tab3,0,-1,OCI_FETCHSTATEMENT_BY_ROW);
									
									echo "<select name='annee' size=1>";
										echo "<option value=''>Selectionnez une annee</option>";
										for ($i=0;$i<$nbLignes;$i++){
										  echo '<option value="'.$tab3[$i]["ANNEE_SPONSOR"].'">'.$tab3[$i]["ANNEE_SPONSOR"];
										  echo '</option>';
										} 	
									echo "</select> ";
									
								?>	
							</td>
							</tr>
							<td>
							</td>
						</tr>
						<tr>
							<td colspan=2 align="center">
								<input type='submit' name='AjouterSponsor' value='Ajouter' >
								
							</td>
						</tr>
					</table>
					
				</fieldset>
			</div>
		</form>
	</body>
</html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html;">
		<meta charset="utf-8">
		<link rel="icon" type="image/png" href="favicon.ico" />
		<title>Suppression d'une épreuve || Mise à jour TDF</title>
	</head>
	<body>
		<?php
			include ('topMenu.php');
			include ('Econnexion.php');
			include ('fonc_regex.php');
		?>
		<h2 align="center">Suppression d' une épreuve</h2>
		
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
			
			
			$erreur = 0;
		
			//on analyse l'enregistrement
			if(isset($_POST['supprimer'])){
				
				//on test si les champs ont bien été rempli
				if($_POST['rownum'] != "Selectionnez une épreuve"){
					
					//analyse de ce qui est envoyer
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
							//on recupert l'annee et le num de l'épreuve pour supprimer la bonne épreuve
							$valeurAnnee = $tab[$i]['ANNEE'];
							$valeurNumEpreuve = $tab[$i]['N_EPREUVE'];
						}
					} 	
					



					if($erreur != 1){
						

						//on fait l'update
						$req = "DELETE FROM tdf_epreuve WHERE annee =".$valeurAnnee." and n_epreuve =".$valeurNumEpreuve;
						//echo $req;
						$cur = preparerRequete($conn, $req);
						$tab = executerRequete($cur);
						oci_commit($conn);
						$valeurTestModif = "Epreuve correctement supprimée !";
						
						//reinitialisation des variables
						$valeurRownum = "";

						
						FermerConnexion($conn);
					}
				}
				else{ //si aucun rownum est selectionné
					$valeurTestRownum = "Veuillez choisir une épreuve à supprimer !";

					
				}
			}
		
		
		
		?>
		
		
		
		<form name="formSupprEpreuve" action="<?php $_SERVER['PHP_SELF'] ?>" method="post" >
			<div align="center" style="margin-left:10%; margin-right:10%">
				<fieldset >
					<legend>Suppression d' épreuve</legend>
					<table border=0 cellpadding=10>
						<tr>
							<td width="250">
								Epreuve à supprimer :
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
											" | Arriver : ".$tab[$i]["VILLE_A"];
											else
												echo '<option value="'.$i.'">'. $tab[$i]["ANNEE"]." | Numéro : ".$tab[$i]["N_EPREUVE"]." | Depart : ".$tab[$i]["VILLE_D"].
											" | Arriver : ".$tab[$i]["VILLE_A"];
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
							<td align="center" colspan=2>
								<input type='submit' name='supprimer' value='Supprimer une épreuve' >
								
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
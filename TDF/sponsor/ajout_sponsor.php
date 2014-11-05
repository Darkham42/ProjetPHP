		<?php
			//on analyse l'enregistrement
			if(isset($_POST['AjouterSponsor'])){
				//on test si les champs ont bien été rempli
				if(($_POST['equipe'] != "Selectionnez une equipe")&&($_POST['nom'] != "")&&($_POST['tag'] != "")&&($_POST['annee'] != "Selectionnez une annee")){
					//traitement de l'ajout
					
					
					//requete 
					$login = 'copie_tdf';
					$mdp = 'copie_tdf';
					$instance = 'xe';
					$conn = OuvrirConnexion($login, $mdp,$instance);
					$req = "select max(n_sponsor)+1 as nb_sponsor from tdf_sponsor where n_equipe=".$_POST['equipe']." group by n_equipe";
					$cur = preparerRequete($conn, $req);
					$tab1 = executerRequete($cur);
					FermerConnexion($conn);
					$maxNumSponsor = array();
					oci_fetch_all($cur,$maxNumSponsor);
					$numSponsor=$maxNumSponsor['NB_SPONSOR'][0];
					echo $numSponsor;
					//requete ajout
					$login = 'copie_tdf';
					$mdp = 'copie_tdf';
					$instance = 'xe';
					$conn = OuvrirConnexion($login, $mdp,$instance);
					$req = "insert into tdf_sponsor (n_equipe,n_sponsor,nom,na_sponsor,code_tdf,annee_sponsor) values (".$_POST['equipe'].",".$numSponsor.",'".$_POST['nom']."','".$_POST['tag']."','".$_POST['pays']."',".$_POST['annee'].")";
					echo $req;
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
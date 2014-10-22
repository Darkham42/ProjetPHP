<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html;">
		<meta charset="utf-8">
		<title>Supprimer un sponsor du Tour de France</title>
	</head>
	<body>

		<?php
			include_once ("../menuBarre.php");
			include_once ("../connBDD.php");
			include_once ("../fonc_test.php");
			include_once ("../log.php");
		?>

		<h2 align="center">Suppression d'un sponsor</h2>
		
	<!-- FORMULAIRE SUPPRESSION D'UN SPONSOR -->

		<?php
			if(!isset($valeurTestSponsor)) {
				$valeurTestSponsor = "";
			}

			if(isset($_POST['Supprimer'])) {
				
				if($_POST['sponsor'] == "Selectionnez un sponsor") {
					$valeurTestSponsor = "Veuillez selectionner un sponsor.";	
				}
				
				else {
					$conn = OuvrirConnexion();

					$req = "SELECT max(n_sponsor) AS nb_sponsor FROM tdf_sponsor WHERE n_equipe='".$_POST['sponsor']."'";
					$cur = preparerRequete($conn, $req);
					$tab = executerRequete($cur);
					$maxNumSponsor = array();
					oci_fetch_all($cur,$maxNumSponsor);
					$numSponsor=$maxNumSponsor['NB_SPONSOR'][0];

					$req2 = "DELETE from tdf_sponsor WHERE n_sponsor='".$numSponsor."' and n_equipe='".$_POST['sponsor']."' ";
					$cur = preparerRequete($conn, $req2);
					$tab = executerRequete($cur);
					oci_commit($conn);
					FermerConnexion($conn);
					$valeurTestSponsor="Sponsor supprimé de la base avec succès.";
					$message = "\r\n\r\nSuppresion avec succès du sponsor '".$_POST['sponsor']."' \r\n$req\r\n\r\n";
					traceLog($fp, $message);				
				}
			}
		?>
		
		<form name="formDelsponsor" action="" method="post" >
			<div align="center" style="margin-left:10%; margin-right:10%">

				<fieldset >
					<table border=0 cellpadding=10>
						<tr>
							<td>
								Choisissez le sponsor à supprimer de la BDD :
							</td>
							<td>
								<?php

									$conn = OuvrirConnexion();
									$req = 'SELECT n_equipe,nom,n_sponsor FROM tdf_equipe join tdf_sponsor using (n_equipe) WHERE annee_disparition is NULL AND (n_equipe,n_sponsor) IN (SELECT n_equipe, max(n_sponsor) AS dernier_sponsor FROM tdf_sponsor GROUP BY n_equipe) ORDER BY nom';
									$cur = preparerRequete($conn, $req);
									$tab = executerRequete($cur);
									$nbLignes = oci_fetch_all($cur, $tab,0,-1,OCI_FETCHSTATEMENT_BY_ROW);
									
									echo "<select name='sponsor' size=1>";
										echo "<option value='Selectionnez un sponsor'>Selectionnez un sponsor</option>";
										for ($i=0;$i<$nbLignes;$i++){
										  echo '<option value="'.$tab[$i]["N_sponsor"].'">'.$tab[$i]["NOM"]." ".utf8_encode($tab[$i]["PRENOM"]);
										  echo '</option>';
										} 	
									echo "</select> ";

									FermerConnexion($conn);
								?>	
							</td>
							<td>
								<font color='red'><?php echo $valeurTestSponsor; ?></font>
							</td>
						</tr>
						<tr>
							<td colspan=2 align="center">
								<input type='submit' name='Supprimer' value='Supprimer un sponsor' >
							</td>
						</tr>
					</table>
					
				</fieldset>
			</div>
		</form>

	</body>
</html>
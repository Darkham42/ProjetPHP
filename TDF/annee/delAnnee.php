<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html;">
		<meta charset="utf-8">
		<title>Supprimer une année du Tour de France</title>
	</head>
	<body>

		<?php
			include ('../menuBarre.php');
			include_once ('../connBDD.php');
			include_once ('../fonc_test.php');
		?>

		<h2 align="center">Suppression d'une année</h2>
		
	<!-- FORMULAIRE SUPPRESSION D'UNE ANNEE -->

		<?php
			if(!isset($valeurTestAnnee)) {
				$valeurTestAnnee="";
			}

			if(isset($_POST['Supprimer'])) {
				
				if($_POST['annee'] == "Selectionnez une année") {
					$valeurTestAnnee = "Veuillez selectionner une année.";	
				}
				
				else {
					$conn = OuvrirConnexion();
					$req = "DELETE from vt_annee where annee='".$_POST['annee']."'";
					$cur = preparerRequete($conn, $req);
					$tab = executerRequete($cur);
					oci_commit($conn);
					FermerConnexion($conn);
					$valeurTestAnnee="Année supprimé avec succès.";					
				}
			}
		?>
		
		<form name="formDelAnnee" action="" method="post" >
			<div align="center" style="margin-left:10%; margin-right:10%">

				<fieldset >
					<table border=0 cellpadding=10>
						<tr>
							<td>
								Choisissez l'année à supprimer de la BDD :
							</td>
							<td>
								<?php

									$conn = OuvrirConnexion();
									$req = 'SELECT annee from vt_annee where annee in (select annee from vt_annee minus select annee from tdf_participation) order by annee DESC';
									$cur = preparerRequete($conn, $req);
									$tab = executerRequete($cur);
									$nbLignes = oci_fetch_all($cur, $tab,0,-1,OCI_FETCHSTATEMENT_BY_ROW);
									
									echo "<select name='annee' size=1>";
										echo "<option value='Selectionnez une année'>Selectionnez une année</option>";
										for ($i=0; $i<$nbLignes; $i++){
											if($tab[$i]["ANNEE"] == $valeurAnnee) {
												echo '<option value="'.$tab[$i]["ANNEE"].'" selected>'.$tab[$i]["ANNEE"];
											}
											else {
												echo '<option value="'.$tab[$i]["ANNEE"].'">'.$tab[$i]["ANNEE"];
											}
										  echo '</option>';
										} 	
									echo "</select> ";
									
									FermerConnexion($conn);
								?>	
							</td>
							<td>
								<font color='red'><?php echo $valeurTestAnnee; ?></font>
							</td>
						</tr>
						<tr>
							<td colspan=2 align="center">
								<input type='submit' name='Supprimer' value='Supprimer une année' >
								
							</td>
						</tr>
					</table>
					
				</fieldset>
			</div>
		</form>

	</body>
</html>
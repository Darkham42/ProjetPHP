<!DOCTYPE html>
<html lang="fr">
	<head>

	<meta http-equiv="content-Type" Content="text/css;charset=utf-8">
	<meta name="author" content="PICARD Thomas">
	<title>Ajouter un coureur</title>

		<script>
			function tester() {
				var res = confirm("Êtes-vous sûr ?");  
				return res;
			}
		</script>

	</head>

	<body>
	<form name="formNewCoureur" action= "<?php $_SERVER['PHP_SELF'] ?>" method="post" enctype="multipart/form-data">

		<fieldset>

			<legend><b>Nouveau coureur</b></legend><br/>
			
			<table>

				<tr>
					<th align="left"><label for="nom">Nom :</label></th>
					<td>
						<input type="text" id="nom" name="nom" size="20" maxlength="20" required="required" placeholder="Nom">
					</td>
				</tr>
				<tr>
					<th align="left"><label for="prenom">Prénom :</label></th>
					<td>
						<input type="text" id="prenom" size="30"  maxlength="30" required="required" placeholder="Prénom">
					</td>
				</tr>
				<tr>
					<th align="left"><label for="pays">Pays :</label>
					<td><select name="pays" id="pays">
					<option value="allemagne">Allemagne</option>
					<option value="france" selected="selected">France</option>
                    </select></td>
                	</th>
				</tr>

			<input type="reset" value="Effacer">
        &nbsp;&nbsp;&nbsp;
		<input type="submit" value="Envoyer">
		&nbsp;&nbsp;&nbsp;
		<input type="button" value="Incrementer" onclick="alert(MAX_FILE_SIZE.value++)">
		
		</fieldset>

	</form>
	</body>
</html>
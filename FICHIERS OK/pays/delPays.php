<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html;">
		<meta charset="utf-8">
		<title>Supprimer un pays du Tour de France</title>
	</head>
	<body>

		<?php
			include_once ('../menuBarre.php');
			include_once ('../connBDD.php');
			include_once ('../fonc_test.php');
		?>

		<h2 align="center">Suppression d'un pays</h2>

	<!-- FORMULAIRE POUR SUPPRIMER UN PAYS DE LA BASE -->

<p>Fonction en cours de création.</p>
	<li><p>A faire</p>
		<ul>
			<li>Page création</li>
			<li>Page modification</li>
			<li>Page suppression</li>
		</ul>
	</li>
	<li><p>A penser</p>
		<ul>
			<li>select * from vt_pays;</li>
			<li>Tout en MAJ comme le nom</li>
			<li><p>Intégrité</p>
				<ul>
					<li>CODE_TDF CHAR(3)</li>
					<li>C_PAYS CHAR(3)</li>
					<li>NOM VARCHAR2(20)</li>
				</ul>
			</li>
		</ul>
	</li>

	</body>
</html>
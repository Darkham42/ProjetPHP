<?php

	// Connexion à la BDD-----------------------------------
	
	function ouvrirConnexion() {
		$conn = oci_connect('copie_tdf', 'copie_tdf', 'xe');
			if (!$conn) {  
				$e = oci_error();  
				print htmlentities($e['Il y a un problème de connexion (verifiez le MDP, l\'instance ou le nom de connexion).']);  
				exit;
			}
		return $conn;
	}

	// Préparation de requete-------------------------------

	function preparerRequete($conn,$req) {
		$cur = oci_parse($conn, $req);
		if (!$cur) {  
			$e = oci_error($conn);  
			print htmlentities($e['Il y a un problème dans la tentative de préparation de la requete.']);  
			exit;
		}
	return $cur;
	}

	// Execution de la requete------------------------------
	
	function executerRequete($cur) {
		$e = oci_error($cur); 
		$r = oci_execute($cur);
		if (!$r) {  
			$e = oci_error($cur);
			echo htmlentities($e['Il y a un problème lors de l\'execution de la requete.']);  
			exit;
		}
		return $r;
	}

	// Fermeture de la connection---------------------------

	function fermerConnexion($conn) {
		oci_close($conn);
	}

?>
<?php

	function supprimer_coureur ($donnee, $id, $conn){
		
		if(isset($donnee[$id])) {

			if(isset($donnee['annee_tdf'][$id]){
				$sup = $connexion->query('DROP * FROM vt_coureur WHERE n_coureur = .$id');	
			}

			else {
				echo "Le coureur n'existe pas !";
			}
		}

		else {
			echo "Le coureur selectionné ayant déjà participé au Tour de France il vous est impossible de le surpprimer !";
		}
	
	}

?>
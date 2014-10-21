<?php

	// Fonctions retirant les accents----------------------
	// Trouvés sur développez.net

	function retireAcc($string) {
		return $string = utf8_encode(strtr(utf8_decode($string),utf8_decode("ÀÁÂÃÄÅàáâãäåÒÓÔÕÖØòóôõöøÈÉÊËèéêëÇçÌÍÎÏìíîïÙÚŬÛÜùúûüÿÑñ"),
			utf8_decode("AAAAAAaaaaaaOOOOOOooooooEEEEeeeeCcIIIIiiiiUUUUUuuuuyNn")));

   }

	function retireAccMAJ($string){
		return $string=utf8_encode(strtr(utf8_decode($string),utf8_decode("ÀÁÂÃÄÅÒÓÔÕÖØÈÉÊËÇÌÍÎÏÙÚŬÛÜÑ"),
			utf8_decode("AAAAAAOOOOOOEEEECIIIIUUUUUN")));
	}

	function toSQL($string){
		return utf8_decode(str_replace(array("\'","'"), "''", $string));
	}

	// Met en majuscule------------------------------------

	function maj($s) {
		
		$s = strtolower($s);
		$sMaj = explode("-", $s);
		$sTab = array();
		
		foreach($sMaj as $mot) {
		    $mot = ucwords($mot);
	    	array_push($sTab, $mot);
		}

		$string = implode("-", $sTab);
		return $string;
	}
	
	// Fonction testant le prénom--------------------------

	function testPrenom($var) {

		$fchar1 = '/^-|-$|^\'|\'$|^ | $/';
		$fchar2 = '/---+/';
		$fchar3 = '/\'\'+/';
		$fchar4 = '/  +/';
		$fchar5 = '/1|2|3|4|5|6|7|8|9|0/';
		$fchar6 = '/€|\$|£|@|\?|\&|\||\æ|\!/';
		$tchar1 = '';
		$tchar2 = '--';
		$tchar3 = '\'';
		$tchar4 = '" "';
		
		$var = retireAccMAJ($var);

		while(preg_match($fchar1,$var) == 1) {
			$var = preg_replace($fchar1,$tchar1,$var);
		}

		if(preg_match($fchar2,$var) == 1) {
			$var = preg_replace($fchar2,$tchar2,$var);
		}

		if(preg_match($fchar3,$var) == 1) {
			$var = preg_replace($fchar3,$tchar3,$var);
		}

		if(preg_match($fchar4,$var) == 1) {
			$var = preg_replace($fchar4,$tchar4,$var);
		}

		while(preg_match($fchar5,$var) == 1) {
			$var = preg_replace($fchar5,$tchar1,$var);
		}

		while(preg_match($fchar6,$var) == 1) {
			$var = preg_replace($fchar6,$tchar1,$var);
		}

		$var = maj($var);
		$var2 = preg_replace('/-|\'| /','',$var);
		$var2 = retireAcc($var2);

		if(ctype_alpha($var2) == true) {
			return $var;
		}
		else {
			return false;
		}

		if (strlen(var2) > 30) {
			$erreur = 1;
		}
	}

	// Fonction testant le nom-----------------------------

	function testNom($var) {

	$var = retireAcc($var);
	$fchar1 = '/^-|-$|^\'|\'$|^ | $/';
	$fchar2 = '/---+/';
	$fchar3 = '/\'\'+/';
	$fchar4 = '/  +/';
	$fchar5 = '/1|2|3|4|5|6|7|8|9|0/';
	$fchar6 = '/€|\$|£|@|\?|\&|\||\æ|\!/';
	$tchar1 = '';
	$tchar2 = '--';
	$tchar3 = '\'';
	$tchar4 = '" "';
	
		while(preg_match($fchar1,$var) == 1) {
			$var=preg_replace($fchar1,$tchar1,$var);
		}

		if(preg_match($fchar2,$var) == 1) {
			$var=preg_replace($fchar2,$tchar2,$var);
		}

		if(preg_match($fchar3,$var) == 1) {
			$var=preg_replace($fchar3,$tchar3,$var);
		}
		if(preg_match('/\'/',$var) == 1) {
			$var=preg_replace('/\'/',$tchar3,$var);
		}

		if(preg_match($fchar4,$var) == 1) {
			$var = preg_replace($fchar4,$tchar4,$var);
		}

		while(preg_match($fchar5,$var) == 1) {
			$var = preg_replace($fchar5,$tchar1,$var);
		}

		while(preg_match($fchar6,$var) == 1) {
			$var = preg_replace($fchar6,$tchar1,$var);
		}

		$var = maj($var);

		$var2 = preg_replace('/-|\'| /','',$var);

		if(ctype_alpha($var2) == true) {
			$var = mb_strtoupper($var);

			return $var;
		}
		else {
			return false;
		}
	}

?>
<?php

	// Fonction retirant les accents-----------------------

	function retireAccents($string) {
		return $string=utf8_encode(strtr(utf8_decode($string),utf8_decode('àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ'),
	utf8_decode('aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY')));
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
		$fchar6 = '/€|\$|£|@/';
		$tchar1 = '';
		$tchar2 = '--';
		$tchar3 = '\'';
		$tchar4 = '" "';
		
		$var = retireAccents($var);

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

		//spec chars
		while(preg_match($fchar6,$var) == 1) {
			$var = preg_replace($fchar6,$tchar1,$var);
		}

		$var = maj($var);
		$var2 = preg_replace('/-|\'| /','',$var);
		$var2 = retireAccents($var2);

		if(ctype_alpha($var2) == true) {
			return $var;
		}
		else {
			return false;
		}
	}

	// Fonction testant le nom-----------------------------

	function testNom($var) {

	$var = retireAccents($var);
	$fchar1 = '/^-|-$|^\'|\'$|^ | $/';
	$fchar2 = '/---+/';
	$fchar3 = '/\'\'+/';
	$fchar4 = '/  +/';
	$fchar5 = '/1|2|3|4|5|6|7|8|9|0/';
	$tchar1 = '';
	$tchar2 = '--';
	$tchar3 = '\'';
	$tchar4 = '\'\'';
	$tchar5 = '" "';
	
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
			$var=preg_replace('/\'/',$tchar4,$var);
		}

		if(preg_match($fchar4,$var) == 1) {
			$va = preg_replace($fchar4,$tchar5,$var);
		}

		while(preg_match($fchar5,$var) == 1) {
			$var = preg_replace($fchar5,$tchar1,$var);
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
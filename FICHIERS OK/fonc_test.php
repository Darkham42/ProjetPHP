<?php

function retireAccents($string){
	return $string=utf8_encode(strtr(utf8_decode($string),utf8_decode('ЮАБЦДГХИЙКЛМНОЯРСТУЖЫЗШЭЩЪюабцдгхийклмноярстужызшэщ'),
utf8_decode('aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY')));
}

function retireAccentsMajuscules($string){
	return $string=utf8_encode(strtr(utf8_decode($string),utf8_decode('юабцдгхийклмноярстужызшэщ'),
utf8_decode('AAAAACEEEEIIIINOOOOOUUUUY')));
}

function majuscules($s) {
	
	$s = strtolower($s);
	$sMaj = explode("-", $s);
	$sTab = array();
	
	foreach($sMaj as $mot) {
	    $mot = ucfirst($mot);
    	array_push($sTab, $mot);
	}

	$s_nouveau = implode("-", $sTab);
	return $s_nouveau;
}

function testNom($var){

	$badchar1='/^-|-$|^\'|\'$|^ | $/';
	$badchar2='/---+/';
	$badchar22='/\'\'+/';
	$badchar222='/  +/';
	$badchar3='/1|2|3|4|5|6|7|8|9|0/';
	$truchar1='';
	$truchar2='--';
	$truchar22='\'';
	$truchar223='\'\'';
	$truchar222='" "';
	$truchar3='';
	//Retire les accents
	$var=retireAccents($var);
	//Retire les espaces,apostrophes et guillemets aux ectremitИs des chaines
	while(preg_match($badchar1,$var)==1){
		$var=preg_replace($badchar1,$truchar1,$var);
	}
	//Retire les tirets supИrieurs ц═ 3
	if(preg_match($badchar2,$var)==1){
		$var=preg_replace($badchar2,$truchar2,$var);
	}
	//Retire les apostrophes supИrieures ц═ 3
	if(preg_match($badchar22,$var)==1){
		$var=preg_replace($badchar22,$truchar22,$var);
	}
	if(preg_match('/\'/',$var)==1){
		$var=preg_replace('/\'/',$truchar223,$var);
	}
	//Retire les espaces supИrieurs ц═ 3
	if(preg_match($badchar222,$var)==1){
		$var=preg_replace($badchar222,$truchar222,$var);
	}
	//Retire les chiffres
	while(preg_match($badchar3,$var)==1){
		$var=preg_replace($badchar3,$truchar1,$var);
	}
	$var=majuscules($var);
	//Retire les tirets,espaces et apostrophes le temps du dernier test, stockИ dans var2 
	$var2=preg_replace('/-|\'| /','',$var);
	//Test s'il n'y a plus de caractХres anormaux (@,$,&,etc...)
	if(ctype_alpha($var2)==true){
		$var = mb_strtoupper($var);
		//Retourne la valeur s'il n'y a plus de cractц╗res
		return $var;
	}
	else{
		//Sinon faux
		return false;
	}
}

function testPrenom($var){

	$badchar1='/^-|-$|^\'|\'$|^ | $/';
	$badchar2='/---+/';
	$badchar22='/\'\'+/';
	$badchar222='/  +/';
	$badchar3='/1|2|3|4|5|6|7|8|9|0/';
	$truchar1='';
	$truchar2='--';
	$truchar22='\'';
	$truchar223='\'\'';
	$truchar222='" "';
	$truchar3='';
	
	$var=retireAccentsMajuscules($var);
	while(preg_match($badchar1,$var)==1){
		$var=preg_replace($badchar1,$truchar1,$var);
	}
	if(preg_match($badchar2,$var)==1){
		$var=preg_replace($badchar2,$truchar2,$var);
	}
	if(preg_match($badchar22,$var)==1){
		$var=preg_replace($badchar22,$truchar22,$var);
	}
	if(preg_match('/\'/',$var)==1){
		$var=preg_replace('/\'/',$truchar223,$var);
	}
	if(preg_match($badchar222,$var)==1){
		$var=preg_replace($badchar222,$truchar222,$var);
	}
	while(preg_match($badchar3,$var)==1){
		$var=preg_replace($badchar3,$truchar1,$var);
	}
	$var=majuscules($var);
	$var2=preg_replace('/-|\'| /','',$var);
	$var2=retireAccents($var2);
	if(ctype_alpha($var2)==true){
		return $var;
	}
	else{
		return false;
	}
}



//Fonction prise sur developpez.net

?>
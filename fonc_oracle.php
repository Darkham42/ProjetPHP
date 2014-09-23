<?php
// E.Porcq  fonc_oracle.php  12/10/2009 

//---------------------------------------------------------------------------------------------
function OuvrirConnexion($session,$mdp,$instance)
{
  $conn = oci_connect($session, $mdp,$instance);
  if (!$conn) //si pas de connexion retourne une erreur
  {  
	$e = oci_error();
	//avec un message pour pouvoir revenir à la page de connexion
	echo "<br>Votre nom d'utilisateur ou votre mot de passe est &eacute;ronn&eacute;e, veuillez vous reconnecter...<br>";
	echo "<form action = 'p1202_1.htm' method='post' enctype='application/x-www-form-urlencoded'>
				<input type='submit' value='Retour'>
		  </form>";
	exit;
  }
  return $conn;
}
//---------------------------------------------------------------------------------------------
function PreparerRequete($conn,$req)
{
  $cur = oci_parse($conn, $req);
  
  if (!$cur) 
  {  
	$e = oci_error($conn);  
	print htmlentities($e['message']);  
	exit;
  }
  return $cur;
}
//---------------------------------------------------------------------------------------------
function ExecuterRequete($cur)
{
  $r = oci_execute($cur, OCI_DEFAULT);
  echo "<br>résultat de la requête: $r<br />";
  if (!$r) 
  {  
	$e = oci_error($r);  
	echo htmlentities($e['message']);  
	exit;
  }
  return $r;
}
//---------------------------------------------------------------------------------------------
function FermerConnexion($conn)
{
  oci_close($conn);
}
//---------------------------------------------------------------------------------------------
function LireDonnees1($cur,&$tab)
{
  $nbLignes = oci_fetch_all($cur, $tab,0,-1,OCI_FETCHSTATEMENT_BY_ROW); //OCI_FETCHSTATEMENT_BY_ROW, OCI_ASSOC, OCI_NUM
  return $nbLignes;
}
//---------------------------------------------------------------------------------------------
function LireDonnees2($cur,&$tab)
{
  $nbLignes = 0;
  $i=0;
  while ($row = oci_fetch_array ($cur, OCI_BOTH  )) 
  {    
    $tab[$nbLignes][$i]  = $row[0];
    $tab[$nbLignes][$i+1]  = $row[1];
    $tab[$nbLignes][$i+2]  = $row[2];
	$nbLignes++;
  }
  return $nbLignes;
}
//---------------------------------------------------------------------------------------------
function LireDonnees3($cur,&$tab)
{
  $nbLignes = 0;
  $i=0;
  while ($row = oci_fetch ($cur)) 
  {    
	$tab[$nbLignes][$i] = oci_result($cur,'VAL'); // respecter la casse
    $tab[$nbLignes][$i+1] = oci_result($cur,'TYPE');
	$tab[$nbLignes][$i+2] = oci_result($cur,'COULEUR');
	$nbLignes++;
  }
  return $nbLignes;
}
//---------------------------------------------------------------------------------------------
// fonctions autres
function AfficherDonnee1($tab,$nbLignes)
{
  if ($nbLignes > 0) 
  {
    echo "<table border=\"1\">\n";
    echo "<tr>\n";
    foreach ($tab as $key => $val)  // lecture des noms de colonnes
    {
      echo "<th>$key</th>\n";
    }
    echo "</tr>\n";
    for ($i = 0; $i < $nbLignes; $i++) // balayage de toutes les lignes
    {
      echo "<tr>\n";
      foreach ($tab as $data) // lecture des enregistrements de chaque colonne
	  {
        echo "<td>$data[$i]</td>\n";
      }
      echo "</tr>\n";
    }
    echo "</table>\n";
  } 
  else 
  {
    echo "Pas de ligne<br />\n";
  } 
  echo "$nbLignes Lignes lues<br />\n";
}
//---------------------------------------------------------------------------------------------
function AfficherDonnee2($tab)
{
  foreach($tab as $ligne)
  {
    foreach($ligne as $valeur)
	  echo $valeur." ";
    echo "<br/>";
  }
}
//---------------------------------------------------------------------------------------------
function AfficherDonnee3($tab,$nb)
{
  for($i=0;$i<$nb;$i++)
    echo $tab[$i][0]." ".$tab[$i][1]." ".$tab[$i][2]."\n";
}
//---------------------------------------------------------------------------------------------
function RecupererDonnees($cur, &$nbLignes)
{
  $tab = oci_fetch_all($cur, $tab, 0, -1, OCI_FETCHSTATEMENT_BY_ROW);
  return $tab;
}
//---------------------------------------------------------------------------------------------
?>




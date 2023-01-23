<?php

//autoload class 
function custom_autoloader($class) {
    define(DIR, $_SERVER['DOCUMENT_ROOT']);
    include DIR . '/lib/' . $class . '.php';
  }
   
spl_autoload_register('custom_autoloader');


// Global value
$url='https://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];


// end global value


 function formatujGET($tekst)
 {
     return addslashes(trim($_GET[''.$tekst.'']));
 }

 function formatujPOST($tekst)
 {
    return addslashes(trim($_POST[''.$tekst.'']));
 }


 class BAZA 
 {
     function BAZA() 
     {
   $this->host = 'localhost';
   $this->baza = '';
   $this->uzytkownik = '';
   $this->haslo = '';
   $this->polacz = mysqli_connect($this->host, $this->uzytkownik,$this->haslo);
   mysqli_select_db($this->polacz, $this->baza);
     }
 }


function sql_fetch_array($wynik)
{
	$result  = NULL;
	if( $wynik )
		$result = $wynik->fetch_array(MYSQLI_ASSOC);
	return $result; 
}

function sqlPOST($tekst,$baza,$use)
{
	$polacz = new BAZA ;
    if ($use == insert) 
    {

	for( $x = 0, $cnt = count($tekst); $x < $cnt; $x++ )
	{
	    $zmienna[$x] = addslashes(trim($_POST[''.$tekst[$x].''])) ;

	    $ta[$x] = "$tekst[$x] = '$zmienna[$x]'";
	    $doimportu = implode(",", $ta );
}

	
GetSQL("insert into $baza set $doimportu ;") ;
    }

    if ($use == update) 
    {
	for( $x = 0, $cnt = count($tekst); $x < $cnt; $x++ )
	{
	    $zmienna[$x] = addslashes(trim($_POST[''.$tekst[$x].''])) ;
	    $ta[$x] = "$tekst[$x] = '$zmienna[$x]'";
	    $doimportu = implode(",", $ta );

	}
	if(preg_match('/id(.*?)\,/ims', $doimportu, $m)) ;
	$id = $presing = str_replace("=", "", $m[1]);
	$id = $presing = str_replace("'", "", $id);

	GetSQL("update $baza set $doimportu where id= $id ;") ;
    }

    if ($use == del) 
    {
	for( $x = 0, $cnt = count($tekst); $x < $cnt; $x++ )
	{
	    $zmienna[$x] = addslashes(trim($_POST[''.$tekst[$x].''])) ;
	    $ta[$x] = "$tekst[$x] = '$zmienna[$x]'";
	    $doimportu = implode(",", $ta );
	}
	if(preg_match('/id(.*?)\,/ims', $doimportu, $m)) ;
	$id = $presing = str_replace("=", "", $m[1]);
	$id = $presing = str_replace("'", "", $id);

	GetSQL ("delete from $baza  where id= $id ;") ;

    }

}




function GetSQL( $query )
{
    $polacz = new BAZA ;
    $zapytanie = NULL;
    if ( $query != "" )
    {
        $zapytanie = $polacz->polacz->query ($query) or DIE (mysqli_error($polacz->polacz));
    }
    return $zapytanie;
}

function sql_num_rows($res)
{
    return mysqli_num_rows($res);
}



//lista do sql sqlPOST
function grab_sql($tekst) {
$b = explode(",", $tekst);
return  $b ;
}


//pobieranie rekordu z bazy
//list ($nazwa,$id) = show_sql("nazwapl,id","kategoria","where id='6'") ;
//echo $id ;
function show_sql($co,$baza,$end) 
{
    $name=$co;
    $co=array($co);
    $bumm = explode(",", $co[0]);

    $zapytanie = GetSQL("select $name from $baza $end  ");

    while($row = sql_fetch_array($zapytanie)) 
    {

	for( $x = 0, $cnt = count($bumm); $x < $cnt; $x++ )
	{
	    $dane = $row[''.$bumm[$x].''];
	    if ($x == 0 ) 
	    { 
		$tablica[]= "$dane" ;  
	    }
	    else 
	    { 
		$tablica[]= "$dane" ; 
	    }
	}
    }
    return $tablica ;
}


function mysql_q($query, $default_value="") 
{

    $result = GetSQL($query);
    if (mysqli_num_rows($result)==0)
         return $default_value;
    else
    {
	$row = $result->fetch_row();
//     return mysqli_result($result,0);
        return $row[0];
    }
}

function passwd($value) {
    $pass=hash('sha256', $value );
    return $pass ;
    }



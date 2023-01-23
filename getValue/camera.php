<?php

session_start();
ini_set("default_socket_timeout", 1); 

    if ($_SESSION['access'] != 'TRUE') {
    header("Location: login.html");
    }
    ini_set('display_errors',1);
    error_reporting(E_ERROR);

    include ("../lib/main.php") ;



$device = GetSQL("select * from device where id = '".formatujPOST('userid')."' ");
while($w = mysqli_fetch_array($device)) { 

$json = new json($w['ip'],$w['key']);
$data = $json->connectCammera();
echo '<table class="table table-striped">';

    for( $i = 0; $i < sizeof($data); $i++ )
    {

    if ($data[$i]['status'] == 0 ) { $text = 'OFFLINE'; }
    if ($data[$i]['status'] == 1 ) { $text = 'ONLINE'; }
    if ($data[$i]['status'] > 1 ) { $text = 'ERROR'; }

       echo "
       <tr>
       <td>
       <img src=\"{$data[$i]['urlpng']}\" style=\"width: 150px;>\">
       </td>
       <td> 
       <span class=\"h5 mb-0 font-weight text-gray-900\"> {$data[$i]['nazwa']} </span>
       </td>
       <td>
       {$text } 
       </td>
       </tr>
       "; 
	
       // echo "LP ".$data[$i]['lp'] ."\n";
	// echo "CamId ".$data[$i]['camId'] ."\n";
	// echo "nazwa ".$data[$i]['nazwa'] ."\n";
	// echo "status ".$data[$i]['status'] ."\n";
	// echo "dni ".$data[$i]['dni'] ."\n";
    //     echo "quota ".$data[$i]['quota'] ."\n";
    //     echo "nadpisywanie ".$data[$i]['nadpisywanie'] ."\n";
	// echo "lasterror ".$data[$i]['lasterror'] ."\n";
    //    echo "urlpng ".$data[$i]['urlpng'] ."\n";
	// echo "dash ".$data[$i]['dash'] ."\n";
    //     echo "hls ".$data[$i]['hls'] ."\n";
	// echo "\n";
    }



        
    }
echo '</table>';


?>
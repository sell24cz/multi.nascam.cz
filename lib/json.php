<?php

    class json {

    private $ip;
    private $key;
    
    public function __construct($ip,$key) {

        $this->ip = $ip;
        $this->key = $key;

    }

    public function connect() {

    $postdata = http_build_query(
	array(
    'apikey' => ''.$this->key.'',
	'ip' => ''.$this->ip.'',
    'status' => 'check'
	)
    );
    $opts = array('http' =>
	array(
        'method'  => 'POST',
        'header'  => 'Content-Type: application/x-www-form-urlencoded',
        'content' => $postdata
	)
    );
    $context  = stream_context_create($opts);
    $result = file_get_contents("http://".$this->ip."/api.php", false, $context);
    $data = json_decode($result, true);

    return $data ;
 
    }

    public function connectCammera() {

        $postdata = http_build_query(
        array(
        'apikey' => ''.$this->key.'',
        'ip' => ''.$this->ip.'',
        'status' => '',
        'idCam' => 'all'    
        )
        );
        $opts = array('http' =>
        array(
            'method'  => 'POST',
            'header'  => 'Content-Type: application/x-www-form-urlencoded',
            'content' => $postdata
        )
        );
        $context  = stream_context_create($opts);
        $result = file_get_contents("http://".$this->ip."/api.php", false, $context);
        $data = json_decode($result, true);
    
        return $data ;
     
        }




    }

?>
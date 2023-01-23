<?php




function notification($type,$text) {

echo '<div class="alert alert-'.$type.'" role="alert">'.$text.' </div>';

}     

//check password

class login {

public $login;
public $password;

public function __construct(string $login, string $password) {

    $this->login = $login;
    $this->password = passwd($password);
}

public function __call($name,$argument) {
    return "Method: {$name}  not exist." ;
}

public function checkLogin() {

  $getPass =  mysql_q("select pass from account where login = '{$this->login}'");

if ( isset($this->password) and  isset($this->login) ) {
  if (trim($getPass) == trim($this->password) ) { 
    
    $_SESSION['access'] = 'TRUE' ;
    $_SESSION['login'] = $this->login ;
    header("Location: index.html");

   }  else  { return  notification(danger,_("Incorrect credentials. Try again") );  }
}
}


}




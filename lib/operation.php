<?php 

include('lib/notice.php');


class operation extends notice {



public function saveHostname($string,$id){
mysql_q("update device  set hostname = '{$string}' where id ='{$id}' ");
}

public function addDevice($ip,$api){
    $mysqli = mysql_q("insert into device set ip='".$ip."' , `key`='".$api."' ");
     $this->showAlertOK();  
}
public function addEmail($smtpserver,$smtpport,$smtpssl,$smtpuser,$smtppassword,$smtpfrom){
    $mysqli = mysql_q("update  emailSetting set 
    smtpserver='".$smtpserver."' , 
    smtpport='".$smtpport."' ,
    smtpssl='".$smtpssl."' , 
    smtpuser='".$smtpuser."' , 
    smtppassword='".$smtppassword."' , 
    smtpfrom='".$smtpfrom."' where id='1'
    ");
     $this->showAlertOK();  
}

public function deleteDevice($id){
    $mysqli = mysql_q("delete from device where  `id`='".$id."' ");
    $this->showAlertOK();
}

public function changePass($data1,$data2,$id) {

if ($data1 === $data2) {

    $NewPassword = passwd($data1);
    mysql_q("update account set pass = '".$NewPassword."' where login = '{$id}' ");  
    $this->showAlertOK();
}
else {
    $this->showAlertBad();
}

}

}
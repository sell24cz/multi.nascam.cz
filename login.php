<?php
session_start();
ini_set('display_errors',1);
error_reporting(E_ERROR);
include ("lib/main.php") ;
require("lib/sendEmail.php");
require "Twig-Extensions/autoload.php";

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

$loader = new FilesystemLoader(__DIR__ . "/templates");
$twig = new Environment($loader);
$operation = new operation();


if ( formatujPOST('login') != NULL  and formatujPOST('password') != NULL ) 
{
	$checkLogin = new login(formatujPOST('login'),formatujPOST('password')) ;
	$checkLogin->checkLogin(); 
}

echo $twig->render("login.html.twig", [
    "title" => _("Multi Nascam"),
    "cardTitle" => _("Multi NasCam Login"),
    "email" => _("E-Mail Address"),
	"emailInvalid" => _("Email is invalid"),
	"password" => _("Password"),
	"passwordForget" => _("Forgot Password?"), 
	"passwordRequired" => _("Password is required"),
    "footer" => _('Copyright  NasCam 2023'),
    "login" => _('Login'),
    "newPass" => _('Send new password'),
    "type" =>_('Type email'),
    "sendEmail" => _('send email')
]); 

if (formatujPOST('newpass') != NULL) 
{
	$notice =  new notice ;
	$sqlCheckEmailExist =  mysql_q("select login from account where login = '".formatujPOST('newpass')."'");

if ($sqlCheckEmailExist != NULL ) 
{  
	$pwd = bin2hex(openssl_random_pseudo_bytes(4)); $HashPwd = passwd($pwd);
	email_send("$sqlCheckEmailExist", "new email", "new emai: {$pwd}");
	$notice->showAlertOK();
}
else 
{
	$notice->showAlertBad();
}
}
?>
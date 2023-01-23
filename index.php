<?php
session_start();
ini_set("default_socket_timeout", 1);
include "lib/main.php";

if (formatujPOST("logout") == "1") {
    unset($_SESSION["access"]);
    unset($_SESSION["login"]);
}

if ($_SESSION["access"] != "TRUE") {
    header("Location: login.html");
}
ini_set("display_errors", 1);
error_reporting(E_ERROR);

require "Twig-Extensions/autoload.php";

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

$loader = new FilesystemLoader(__DIR__ . "/templates");
$twig = new Environment($loader);
$operation = new operation();

//<!--- head --->
echo $twig->render("head.html.twig", [
    "title" => _("NasCam Multi"),
]);
//<!--- head --->

if (formatujPOST("delete") != null) {
    $operation->deleteDevice(formatujPOST("delete"));
}
if (formatujPOST("api") != null and formatujPOST("ip")) {
    $operation->addDevice(formatujPOST("ip"), formatujPOST("api"));
}
if (formatujPOST("smtpserver") != null and formatujPOST("smtpport")) {
    $operation->addEmail(formatujPOST("smtpserver"), formatujPOST("smtpport"), formatujPOST("smtpssl"), formatujPOST("smtpuser"), formatujPOST("smtppassword"), formatujPOST("smtpfrom"));
}
if (formatujPOST("data1") != null and formatujPOST("data2")) {
    $operation->changePass(
        formatujPOST("data1"),
        formatujPOST("data2"),
        formatujPOST("id")
    );
}

//<!-- Topbar Navbar + body -->
echo $twig->render("topbar.html.twig", [
    "Setting" => _("Setting"),
    "Password" => _("Password"),
    "email" => _('Email'),
    "newDevice" => _("Add New Device"),
    "Logout" => _("Logout"),
]);
//<!-- End of Topbar -->

// generate node on loop

$device = GetSQL("select * from device ");
while ($w = mysqli_fetch_array($device)) {
    $json = new json($w["ip"], $w["key"]);
    $data = $json->connect();

    if ($data == null) {
        $hostname = $w["ip"] . " | " . $w["hostname"];
        $color = "danger";
        $alert = "Connection problem";
    } else {
        $hostname = $data["hostname"];
        $color = "info";
    }

    echo $twig->render("on.html.twig", [
        "ip" => $w["ip"],
        "id" => $w["id"],
        "status" => $data["status"],
        "cpu" => $data["cpu"],
        "hdd" => $data["hdd"],
        "camonline" => $data["camonline"],
        "camoff" => $data["camoff"],
        "camoffuser" => $data["camoffuser"],
        "hostname" => $hostname,
        "color" => $color,
        "alert" => $alert,
        "uptime" => $data["uptime"],
    ]);

    if ($data != null) {
        $operation->saveHostname($hostname, $w["id"]);
    }
}

//<!-- Logout Modal-->
echo $twig->render("logoutModal.html.twig", [
    "leave" => _("Ready to Leave?"),
    "slogan" => _(
        'Select "Logout" below if you are ready to end your current session.'
    ),
    "Close" => _("Close"),
    "Logout" => _("Logout"),
]);

//<!-- Modal Camera list -->
echo $twig->render("cameraListModal.html.twig", [
    "cameraList" => _("Camera List"),
    "Submit" => _("Submit"),
    "Close" => _("Close"),
]);

//<!-- Modal define api-->
echo $twig->render("apiModal.html.twig", [
    "device" => _("Add New Device"),
    "ip" => _("IP Address"),
    "exampleIP" => _("example: 192.168.0.1, 192.168.0.1:8080"),
    "exampleAPI" => _("API Key defined in NVR."),
    "Submit" => _("Submit"),
    "Close" => _("Close"),
    "APIKey" => _("API Key"),
]);


//<!-- Modal define email-->

$email = GetSQL("select * from emailSetting limit 1");
while ($w = mysqli_fetch_array($email)) {
    $data1 = $w["smtpserver"];
    $data2 = $w["smtpport"];
    $data3 = $w["smtpssl"];
    $data4 = $w["smtpuser"];
    $data5 = $w["smtppassword"];
    $data6 = $w["smtpfrom"];
}

if ($data == null) {
    $hostname = $w["ip"] . " | " . $w["hostname"];
}
echo $twig->render("emailModal.html.twig", [

    "data1" => $data1,
    "data2" => $data2,
    "data3" => $data3,
    "data4" => $data4,
    "data5" => $data5,
    "data6" => $data6,

    "header" => _("Email Setting"),
    "smtpserver" => _("Smtp server"),
    "smtpport" => _("Smtp port"),
    "smtpssl" => _("Smtp ssl"),
    "smtpuser" => _("Smtp user"),
    "smtppassword" => _("Smtp password"),
    "smtpfrom" => _("Sender header"),
    "Submit" => _("Submit"),
    "Close" => _("Close"),
    "APIKey" => _("API Key"),
]);

//<!-- Modal define password-->
echo $twig->render("passModal.html.twig", [
    "ChangePass" => _("Change password"),
    "id" => $_SESSION["login"],
    "password" => _("password"),
    "repassword" => _("retype password"),
    "Submit" => _("Submit"),
    "Close" => _("Close"),
    "APIKey" => _("API Key"),
]);

///<!-- Footer close page-->
echo $twig->render("footer.html.twig", [
    "footer" => _("Copyright  NasCam 2023"),
]);
?>

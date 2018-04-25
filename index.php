<?php

require_once("db/db.php");
require_once("controllers/home_controller.php");
require_once("controllers/login_controller.php");

session_start();
//session_destroy();
$homeController = new home_controller();
$login_controller = new login_controller();

if (isset($_GET['action'])) {

    if ($_GET['action'] == "login") {
        $loged = $login_controller->login();
        if(!$loged){
            $homeController->view();
        }
    }

    if ($_GET['action'] == "logout") {
        $_SESSION['user'] = [];
        $_SESSION['userType'] = [];
        $homeController->view();
    }
} else {
    $homeController = new home_controller();
    $homeController->view();
}
?>

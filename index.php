<?php

require_once("db/db.php");
require_once("controllers/home_controller.php");
require_once("controllers/login_controller.php");

session_start();

if (isset($_GET['action'])) {

  if ($_GET['action'] == "login") {
    $login_controller = new login_controller();
    $loged = $login_controller->login();

  }

} else {
    $homeController = new home_controller();
    $homeController->view();
}

?>

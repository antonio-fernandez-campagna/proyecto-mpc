<?php

require_once("db/db.php");
require_once("controllers/home_controller.php");
require_once("controllers/login_controller.php");
require_once("controllers/pets_controller.php");

session_start();
//session_destroy();
$homeController = new home_controller();
$login_controller = new login_controller();
$pets_controller = new pets_controller();

if (isset($_GET['action'])) {

    if ($_GET['action'] == "login") {
        $loged = $login_controller->login();
        if (!$loged) {
            $homeController->view();
        }
    }

    if ($_GET['action'] == "logout") {
        $_SESSION['user'] = [];
        $_SESSION['userType'] = [];
        $homeController->view();
    }

    if ($_GET['action'] == "searchMedicine") {
        $homeController->view("", $_POST['medicineName']);
    }

    if ($_GET['action'] == "petAddView") {
        $pets_controller->add_view();
    }

    if ($_GET['action'] == "addPet") {
        $pets_controller->add_pet();
    }

    if ($_GET['action'] == "searchPetDni") {
        $pets_controller->pet_dni_view();
    }

    if ($_GET['action'] == "addPresciption") {
        $pets_controller->pet_dni_view();
    }
} else {
    $homeController = new home_controller();
    $homeController->view();
}
?>

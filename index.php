<?php

require_once("db/db.php");
require_once("controllers/home_controller.php");
require_once("controllers/login_controller.php");
require_once("controllers/pets_controller.php");
require_once("controllers/prescriptions_controller.php");

session_start();
//session_destroy();
$homeController = new home_controller();
$login_controller = new login_controller();
$pets_controller = new pets_controller();
$prescriptions_controller = new prescriptions_controller();
$mecicines_controller = new medicines_controller();

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
        $homeController->view($_POST['medicineName']);
    }

    if ($_GET['action'] == "petAddView") {
        $pets_controller->add_view();
    }

    if ($_GET['action'] == "addPet") {
        $result = $pets_controller->add_pet();
        if ($result == false) {
            $pets_controller->add_view("", $errorDate = true);
        }
    }

    if ($_GET['action'] == "searchPet") {
        $result = $pets_controller->pet_view();
        if ($result == true) {
            $homeController->view("", "", "", true);
        }
    }

    if ($_GET['action'] == "basicInfoFarm") {
        $result = $pets_controller->basic_info_vet();
        if ($result == true) {
            $homeController->view("", "", "", true);
        }
    }

    if ($_GET['action'] == "more_info_farm_pet") {
        $pets_controller->more_info_farm_view();
    }

    if ($_GET['action'] == "recogido") {
        $prescriptions_controller->collected();
    }

    if ($_GET['action'] == "toPrescribe") {
        $chip = $_GET['chip'];
        $homeController->view("", "", "", "", "", $chip);
    }

    if ($_GET['action'] == "addPresciption") {
        $prescriptions_controller->add_prescription();
    }

    if ($_GET['action'] == "edit") {
        $pets_controller->edit_pet_view();
    }

    if ($_GET['action'] == "deletePet") {
        $pets_controller->delete_pet();
    }

    if ($_GET['action'] == "deletePrescription") {
        $pets_controller->delete_prescription();
    }

    if ($_GET['action'] == "edit_pet") {
        $pets_controller->edit_pet();
    }

    if ($_GET['action'] == "prescribeView") {
        $pets_controller->prescribe_view();
    }


    if ($_GET['action'] == "graph") {
        $mecicines_controller->medicine_graph();
    }

    if ($_GET['action'] == "generarRecetas") {
        $prescriptions_controller->generar_recetas();
    }
} else {
    $homeController = new home_controller();
    $homeController->view();
}
?>

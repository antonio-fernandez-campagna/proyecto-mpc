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
        $homeController->view("", $_POST['medicineName']);
    }

    if ($_GET['action'] == "petAddView") {
        $pets_controller->add_view();
    }

    if ($_GET['action'] == "addPet") {
        $pets_controller->add_pet();
    }

    if ($_GET['action'] == "searchPet") {
        $result = $pets_controller->pet_view();
        if ($result == true){
          $homeController->view("","","","",true);
        }
    }

    // if ($_GET['action'] == "edit_pet") {
    //     $pets_controller->pet_modify();
    // }

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
} else {
    $homeController = new home_controller();
    $homeController->view();
}
?>

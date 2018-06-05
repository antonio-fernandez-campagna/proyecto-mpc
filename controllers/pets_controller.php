<?php

require_once 'models/pets_model.php';

class pets_controller {

    // Función que muestra la página principal
    function add_view($errorAdd = "", $errorDate = "") {

        if (!empty($_SESSION['user'])) {

            $pets_model = new pets_model();
            $data = $pets_model->get_species();

            $pet_view = "yes";
            require_once("views/vet_add_pet_view.phtml");
        } else {
            require_once("views/home_view.phtml");
        }
    }

    function add_pet() {
        $pets_model = new pets_model();

        $conexion = $pets_model->db;

        // comprobaciíon de mysql injection

        $name = !empty($_POST['petName']) ? $_POST['petName'] : "";
        $specie = !empty($_POST['specie']) ? $_POST['specie'] : "";
        $dniProp = !empty($_POST['dni']) ? $_POST['dni'] : "";
        $chip = !empty($_POST['chip']) ? $_POST['chip'] : "";
        $birthDate = !empty($_POST['birth']) ? $_POST['birth'] : "";
        $sex = !empty($_POST['sex']) ? $_POST['sex'] : "";
        $weight = !empty($_POST['weight']) ? $_POST['weight'] : "";

        $name = mysqli_real_escape_string($conexion, $name);
        $specie = mysqli_real_escape_string($conexion, $specie);
        $dniProp = mysqli_real_escape_string($conexion, $dniProp);
        $chip = mysqli_real_escape_string($conexion, $chip);
        $birthDate = mysqli_real_escape_string($conexion, $birthDate);
        $sex = mysqli_real_escape_string($conexion, $sex);
        $weight = mysqli_real_escape_string($conexion, $weight);

        $today = date("Y-m-d");


        if ($birthDate > $today) {
            return false;
        }


        $pets_model->setName($name);
        $pets_model->setSpecie($specie);
        $pets_model->setDniProp($dniProp);
        $pets_model->setChip($chip);
        $pets_model->setBirthDate($birthDate);
        $pets_model->setSex($sex);
        $pets_model->setWeight($weight);

        $error = $pets_model->add_pet();
        $this->add_view($error);
    }

    function pet_view($id_pet = "", $chip = "") {
        $pets_model = new pets_model();


        if (!empty($_POST['dni'])) {
            $dni = $_POST['dni'];
            $data['pets'] = $pets_model->get_info_pet_dni($dni);
            if (empty($data['pets'])) {
                return true;
            }
        } elseif (!empty($_POST['chip'])) {
            $chip = $_POST['chip'];
            $data['pets'] = $pets_model->get_info_pet_chip($chip);
            if (empty($data['pets'])) {
                return true;
            }
            $pet_view = "yes";
            include 'views/pet_prescribe_view.phtml';
            return false; // hago return para que no haga include y acabe la función
        } elseif (empty($_POST['dni']) && empty($_POST['chip'])) {
            return true;
        } elseif (empty($_POST['chip']) && empty($_POST['dni']) && $id_pet != "") {
            $data['pets'] = $pets_model->get_pet_from_id($id_pet);
        }



        $pet_view = "yes";

        // se manda a una vista para ver la información básica
        include 'views/pet_info_view.phtml';
    }

    function edit_pet_view() {
        $pets_model = new pets_model();
        $chip = $_GET['pet'];

        $data['pets'] = $pets_model->get_info_pet_chip($chip);

        $pet_view = "yes";

        include 'views/pet_edit_view.phtml';
    }

    function edit_pet() {
        $pets_model = new pets_model();
        $conexion = $pets_model->db;
        $id_chip = $_GET['pet_chip'];

        $name = mysqli_real_escape_string($conexion, $_POST['name']);
        $dniProp = mysqli_real_escape_string($conexion, $_POST['dni']);
        $weight = mysqli_real_escape_string($conexion, $_POST['weight']);

        $pets_model->setName($name);
        $pets_model->setDniProp($dniProp);
        $pets_model->setWeight($weight);

        $pets_model->set_pet($id_chip);

        if (empty($dniProp)) {
            $this->show_view_after_delete();
            return;
        }

        $this->pet_view(); // todo: añadir para despues de eliminar perro/receta
    }

    function prescribe_view() {
        $pets_model = new pets_model();
        $chip = $_GET['pet'];
        $pet_view = "yes";

        $data['pets'] = $pets_model->get_info_pet_chip($chip);

        include 'views/pet_prescribe_view.phtml';
    }

    function delete_pet() {
        $pets_model = new pets_model();
        $homeController = new home_controller();

        $idPet = $_GET['pet'];

        $data['pets'] = $pets_model->delete_pet($idPet);

        $homeController->view("", "", "", "", true);
    }

    function delete_prescription() {
        $pets_model = new pets_model();

        $idPrescription = $_GET['prescription'];
        $chip = $_GET['chip'];
        $pets_model->delete_prescription($idPrescription);

        $this->show_view_after_delete($chip);
    }

    function show_view_after_delete($chip = "") {
        $pets_model = new pets_model();
        $data['pets'] = $pets_model->get_info_pet_chip($chip);
        $pet_view = "yes";

        // se manda a una para ver la información completa
        include 'views/pet_prescribe_view.phtml';
    }

    function basic_info_vet() {

        $pets_model = new pets_model();

        $dni = !empty($_POST['dni']) ? $_POST['dni'] : "";

        if (!empty($dni)) {
            $data['pets'] = $pets_model->get_info_pet_dni($dni);
            if (empty($data['pets'])) {
                return true;
            }
            include 'views/farm_info_pet_view.phtml';
        } else {
            return true;
        }
    }

    function more_info_farm_view($chip = "") {

        $pets_model = new pets_model();

        if (empty($chip)) {
            $chip = !empty($_REQUEST['pet']) ? $_REQUEST['pet'] : "";
        }
 
        if (!empty($chip)) {
            $data['pets'] = $pets_model->get_more_info($chip);
            if (empty($data['pets'])) {
                $emptyPrescription = true;
            }
            include 'views/farm_more_info_pet.phtml';
        } else {
            return true;
        }
    }

}

<?php

require_once 'models/pets_model.php';

class pets_controller {

    // Función muestra la página de añadir mascotas
    // si se inserta una fecha inválida mostrará error
    // se ha habido un error al insertar la mascota mostrará un error
    function add_view($errorAdd = "", $errorDate = "") {

        if (!empty($_SESSION['user'])) {

            $pets_model = new pets_model();
            $data = $pets_model->get_species();

            // si pet_view es igual a yes bloqueará los buscadores de arriba a la derecha de la página (buscar medicamentos)
            $pet_view = "yes";
            require_once("views/vet_add_pet_view.phtml");
        } else {
            require_once("views/home_view.phtml");
        }
    }
    
    
    // función para añadir mascotas 
    function add_pet() {
        $pets_model = new pets_model();

        $conexion = $pets_model->db;

        // comprobaciíon de que los campos no estén vacíos y de mysql injection

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

        // guarda la fecha de hoy
        $today = date("Y-m-d");


        // si la fecha introducida es mayor al día de hoy devolverá false y por lo tanto
        // mostrará un error
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

    // función para los veterinarios para ver la información de las mascotas
    function pet_view($id_pet = "", $chip = "") {
        $pets_model = new pets_model();

        // Si se ha buscado por dni, mostrará todas las mascotas que tenga esa persona
        // Si se ha buscado por chip mostrará la información detallada de la mascota (solo mostrará las recetas crónicas)
        // si dni y chip están vacios no se buscará y devolverá true para posteriormente mostrar un error
        if (!empty($_POST['dni'])) {
            $dni = $_POST['dni'];
            $data['pets'] = $pets_model->get_info_pet_dni($dni);
            // si no existe $data['pets]`(la mascota)devolverá true y por lo tanto mostrará un eror
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
        }

        $pet_view = "yes";

        // se manda a una vista para ver la información básica
        include 'views/pet_info_view.phtml';
    }

    // muestra la vista de edición de mascota
    function edit_pet_view() {
        $pets_model = new pets_model();
        $chip = $_GET['pet'];

        // se guarda la información de la mascota
        $data['pets'] = $pets_model->get_info_pet_chip($chip);

        $pet_view = "yes";

        include 'views/pet_edit_view.phtml';
    }

    // función que guarda los valores editados para las mascotas
    function edit_pet() {
        $pets_model = new pets_model();
        $conexion = $pets_model->db;
        $id_chip = $_GET['pet_chip'];

        // prevención de sql injection
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

        $this->pet_view(); 
    }

    // muestra la la información detalla de la mascota si se hace clic desde "ver" si se ha buscado por DNI anteriormente
    function prescribe_view() {
        $pets_model = new pets_model();
        $chip = $_GET['pet'];
        $pet_view = "yes";

        $data['pets'] = $pets_model->get_info_pet_chip($chip);

        include 'views/pet_prescribe_view.phtml';
    }

    // función para eliminar una mascota
    function delete_pet() {
        $pets_model = new pets_model();
        $homeController = new home_controller();

        $idPet = $_GET['pet'];

        $data['pets'] = $pets_model->delete_pet($idPet);

        $homeController->view("", "", "", "", true);
    }

    // función para eliminar una receta
    function delete_prescription() {
        $pets_model = new pets_model();

        $idPrescription = $_GET['prescription'];
        $chip = $_GET['chip'];
        $pets_model->delete_prescription($idPrescription);

        $this->show_view_after_delete($chip);
    }

    // función para mostrar nuevamente la información de las mascota después de haber eliminado una mascota
    function show_view_after_delete($chip = "") {
        $pets_model = new pets_model();
        $data['pets'] = $pets_model->get_info_pet_chip($chip);
        $pet_view = "yes";

        // se manda a una para ver la información completa
        include 'views/pet_prescribe_view.phtml';
    }

    // muestra la información básica de la mascota (cuando se busca por dni) para el usuario de tipo farmacia
    function basic_info_farm() {

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

    // muestra la información detallada de la mascota para el usuario de tipo farmacia
    function more_info_farm_view($chip = "") {

        $pets_model = new pets_model();

        if (empty($chip)) {
            $chip = !empty($_REQUEST['pet']) ? $_REQUEST['pet'] : "";
        }
 
        // si no hay recetas mostrará un aviso de que no hay recetas para esa mascota
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

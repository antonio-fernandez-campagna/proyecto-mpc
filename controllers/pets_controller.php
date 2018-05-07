<?php

require_once 'models/pets_model.php';

class pets_controller {

    // Función que muestra la página principal
    function add_view($errorAdd = "") {

        if (!empty($_SESSION['user'])) {

            $pets_model = new pets_model();
            $data = $pets_model->get_species();

            require_once("views/vet_add_pet_view.phtml");
        } else {
            require_once("views/home_view.phtml");
        }
    }

    function add_pet() {
        $pets_model = new pets_model();

        $conexion = $pets_model->db;

        // comprobaciíon de mysql injection
        $name = mysqli_real_escape_string($conexion, $_POST['petName']);
        $specie = mysqli_real_escape_string($conexion, $_POST['specie']);
        $dniProp = mysqli_real_escape_string($conexion, $_POST['dni']);
        $chip = mysqli_real_escape_string($conexion, $_POST['chip']);
        $birthDate = mysqli_real_escape_string($conexion, $_POST['birth']);
        $sex = mysqli_real_escape_string($conexion, $_POST['sex']);
        $weight = mysqli_real_escape_string($conexion, $_POST['weight']);

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

    function pet_dni_view() {
        $pets_model = new pets_model();
        $dni = $_POST['dni'];
        
        $data['pets'] = $pets_model->get_pet_dni($dni);
        echo "<pre>".print_r($data['pets'], 1)."</pre>";
        include 'views/petVet_view.phtml';
    }

}

<?php

require_once 'models/prescriptions_model.php';
require_once 'models/pets_model.php';
require_once 'controllers/home_controller.php';

class prescriptions_controller {

    function add_prescription() {
        $prescription_model = new prescriptions_model();
        $pets_model = new pets_model();
        $home_controller = new home_controller();

        $conexion = $prescription_model->db;

        // comprobaciíon de mysql injection
        $medicine = mysqli_real_escape_string($conexion, $_GET['id']);

        $quantity = mysqli_real_escape_string($conexion, $_POST['quantity']);
        $chronic = !empty($_POST['chronic']) ? $_POST['chronic'] : "n";
        $observation = mysqli_real_escape_string($conexion, $_POST['observation']);

        $chip = mysqli_real_escape_string($conexion, $_POST['chip']);

        $id_pet = $pets_model->get_pet_id($chip);

        $prescription_model->setMedicine($medicine);
        $prescription_model->setQuantity($quantity);
        $prescription_model->setChronic($chronic);
        $prescription_model->setObservation($observation);
        $prescription_model->setPet($id_pet);

        $presError = $prescription_model->add_prescription();
        $home_controller->view("", $presError);
    }

    function generar_recetas() {
        $prescription_model = new prescriptions_model();
        $prescription_model->add_prescriptions_script();
    }

    function collected() {
        $prescription_model = new prescriptions_model();
        $pet_controller = new pets_controller();

        $id_prescription = !empty($_GET['prescription']) ? $_GET['prescription'] : "";
        $chip =  !empty($_GET['chip']) ? $_GET['chip'] : "";

        if ($id_prescription) {
            $prescription_model->set_collected($id_prescription);
           
            $pet_controller->more_info_farm_view($chip); 
        } else {
            $home_controller = new home_controller();
            $home_controller->view();
        }
    }

}

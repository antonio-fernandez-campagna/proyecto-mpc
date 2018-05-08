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
        $home_controller->view("", "", $presError);
    }
    
}

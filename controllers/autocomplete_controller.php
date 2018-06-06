<?php

require_once("../db/db.php");
require_once '../models/medicines_model.php';

$medicine = new medicines_model();

// recoge los nombres de las medicinas para la función de autocompletado
$allMedicines = $medicine->get_name_medicine_autocomplete();

echo json_encode($allMedicines, 1);

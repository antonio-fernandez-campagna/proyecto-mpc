<?php

require_once("../db/db.php");
require_once '../models/medicines_model.php';

$medicine = new medicines_model();

$allMedicines = $medicine->get_name_medicine_autocomplete();

// hace un echo del array de los productos que recojo en anteriormente en formato JSON
echo json_encode($allMedicines, 1);

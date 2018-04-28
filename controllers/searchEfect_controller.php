<?php
require_once '../db/db.php';
require_once '../models/medicines_model.php';

$medicine = new medicines_model();
$word = $_POST['word'];

$allMedicines = $medicine->get_medicine_by_efect($word);

// hace un echo del array de los productos que recojo en anteriormente en formato JSON
echo json_encode($allMedicines, 1);

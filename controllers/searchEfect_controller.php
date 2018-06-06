<?php
require_once '../db/db.php';
require_once '../models/medicines_model.php';


$medicine = new medicines_model();

$word = $_POST['word'];

// muestra las medicinas que tengan la palabra buscada como efecto del medicamento
$allMedicines = $medicine->get_medicine_by_efect($word);

echo json_encode($allMedicines, 1);

<?php
require_once '../db/db.php';
require_once '../models/medicines_model.php';


$medicine = new medicines_model();
$cat = $_REQUEST['word']; //todo: change to post after debug

$medicinesByCat = $medicine->get_medicine_graphics($cat);
$out = [];

foreach($medicinesByCat as  $m){
  //array_push($out,$m['nombre_medicamento']);
  $out[$m['id_medicamento']] = $m['nombre_medicamento'];
}


// hace un echo del array de los productos que recojo en anteriormente en formato JSON
echo json_encode($out, 1);



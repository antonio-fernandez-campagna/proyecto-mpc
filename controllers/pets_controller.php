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

    function pet_view($id_pet = "", $chip = "") {
        $pets_model = new pets_model();


        if (!empty($_POST['dni'])) {
            $dni = $_POST['dni'];
            $data['pets'] = $pets_model->get_info_pet_dni($dni);
            if(empty($data['pets'])){
              return true;
            }
        } elseif (!empty($_POST['chip'])) {
            $chip = $_POST['chip'];
            $data['pets'] = $pets_model->get_info_pet_chip($chip);
            if(empty($data['pets'])){
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

        include 'views/edit_pet_view.phtml';
    }

    function edit_pet() {
        $pets_model = new pets_model();
        $conexion = $pets_model->db;
        $id_chip = $_GET['pet'];

        $name = mysqli_real_escape_string($conexion, $_POST['name']);
        $dniProp = mysqli_real_escape_string($conexion, $_POST['dni']);
        $weight = mysqli_real_escape_string($conexion, $_POST['weight']);

        $pets_model->setName($name);
        $pets_model->setDniProp($dniProp);
        $pets_model->setWeight($weight);

        $pets_model->set_pet($id_pet);

        if(empty($dniProp)){
          $this->show_view_after_delete();
          return ;
        }

        $this->pet_view(); // todo: añadir para despues de eliminar perro/receta
    }


    function pet_modify() {
      $pets_model = new pets_model();

      $chip = $_GET['pet'];

      if (!empty($_POST['dni'])) {
          $dni = $_POST['dni'];
          $data['pets'] = $pets_model->get_pet_dni($dni);
      } elseif (!empty($_POST['chip'])) {
          $chip = $_POST['chip'];
          $data['pets'] = $pets_model->get_pet_chip($chip);
      } elseif (empty($_POST['chip']) && empty($_POST['dni']) && $chip != "") {
          $data['pets'] = $pets_model->get_info_pet_chip($id_pet);
      }

      $pet_view = "yes";

      include 'views/petVet_view.phtml';
    }

    function prescribe_view(){
      $pets_model = new pets_model();
      $chip = $_GET['pet'];
      $pet_view = "yes";

      $data['pets'] = $pets_model->get_info_pet_chip($chip);

    //  echo "<pre>".print_r($data['pets'], 1)."</pre>";

      include 'views/pet_prescribe_view.phtml';
    }

// todo
    function delete_pet(){
      $pets_model = new pets_model();
      $homeController = new home_controller();

      $idPet = $_GET['pet'];

      $data['pets'] = $pets_model->delete_pet($idPet);

    //  echo "<pre>".print_r($data['pets'], 1)."</pre>";
      $homeController->view("","","","","",true);

      //include 'views/pet_prescribe_view.phtml';
    }

// todo
    function delete_prescription(){
      $pets_model = new pets_model();

      $idPrescription = $_GET['prescription'];
      $chip = $_GET['chip'];
      $pets_model->delete_prescription($idPrescription);

      $this->show_view_after_delete($chip);

    //  include 'views/pet_prescribe_view.phtml';
    }

    function show_view_after_delete($chip = ""){
      $pets_model = new pets_model();
      $data['pets'] = $pets_model->get_info_pet_chip($chip);
      $pet_view = "yes";

      // se manda a una para ver la información completa
      include 'views/pet_prescribe_view.phtml';
    }

}

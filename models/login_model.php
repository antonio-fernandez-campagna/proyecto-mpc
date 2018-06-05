<?php

class login_model {

    private $users;
    public $db;

    private $name;
    private $surname;
    private $dni;
    private $tlf;
    private $username;
    private $password;
    private $type;
    private $nCollege;

    public function __construct() {
        $this->db = Conectar::conexion();
    }

    /* GETTERS & SETTERS */

    function getName() {
        return $this->name;
    }

    function getSurname() {
        return $this->surname;
    }

    function getDni() {
        return $this->dni;
    }

    function getTlf() {
        return $this->tlf;
    }

    function getUsername() {
        return $this->username;
    }

    function getPassword() {
        return $this->password;
    }

    function getType() {
        return $this->type;
    }

    function getNCollege() {
        return $this->nCollege;
    }

    function setName($name) {
        $this->name = $name;
    }

    function setSurname($surname) {
        $this->surname = $surname;
    }

    function setDni($dni) {
        $this->dni = $dni;
    }

    function setTlf($tlf) {
        $this->tlf = $tlf;
    }

    function setUsername($username) {
        $this->username = $username;
    }

    function setPassword($password) {
        $this->password = $password;
    }

    function setType($type) {
        $this->type = $type;
    }

    function setNCollege($nCollege) {
        $this->nCollege = $nCollege;
    }


    // Función para logear (comprueba que el usuario existe en la BD)

    public function verifyUser() {

        $crypt = md5($this->password);

        $query = "SELECT u.*, t.tipo as tipo_usuario FROM usuarios u, tipo_usuario t WHERE u.usuario ='{$this->username}' AND u.contrasenya = '{$crypt}' AND u.tipo = t.id;";

        $consulta = $this->db->query($query);
        while ($filas = $consulta->fetch_assoc()) {
            $this->users = $filas;
        }
        return $this->users;
    }

}

?>

<?php

require_once("models/login_model.php");
require_once("controllers/home_controller.php");

// clase que controla el login
class login_controller {

    // Función para logearse
    function login() {
        $user_model = new login_model();
        $conexion = $user_model->db;

        // comprobación de que los campos no estén vacios
        $user = !empty($_POST['username']) ? $_POST['username'] : "";
        $contrasenya = !empty($_POST['password']) ? $_POST['password'] : "";

        // prevención de SQL Injection
        $user = mysqli_real_escape_string($conexion, $user);
        $contrasenya = mysqli_real_escape_string($conexion, $contrasenya);

        $user_model->setUsername($user);
        $user_model->setPassword($contrasenya);

        $userType = $user_model->verifyUser();

        // funciones para el google captcha

        $stream_opts = [
            "ssl" => [
                "verify_peer" => false,
                "verify_peer_name" => false,
            ]
        ];

        $secretKey = "6LfoTFwUAAAAAJZ-ygQqu9d9FnfcVAJZz3lfTErU";
        $responseKey = $_POST['g-recaptcha-response'];
        $userIP = $_SERVER['REMOTE_ADDR'];

        $url = "https://www.google.com/recaptcha/api/siteverify?secret=$secretKey&response=$responseKey&remoteip=$userIP";

        $response = file_get_contents($url, false, stream_context_create($stream_opts));

        $response = json_decode($response);

        // si el response ha devuelto success se hará el intento de login, si no, no dejará logearse
        if ($response->success) {
            // se hace comprobación de que se haya logeado y se asignan parámetros
            if (!empty($userType)) {
                $_SESSION['user'] = $user;

                $_SESSION['userType'] = $userType["tipo_usuario"];

                $home = new home_controller();
                $_SESSION['userType'];

                // se llama a la vista principal
                $home->view();

                return true;
            } else {
                $home = new home_controller();
                $home->view("", "", "", false);
            }
        }
    }

}

?>

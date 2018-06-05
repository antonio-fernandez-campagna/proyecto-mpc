<?php

require_once("models/login_model.php");
require_once("controllers/home_controller.php");

//require_once "recaptchal/ib.php";
// clase que controla el Login, registro y comprueba el estado del cart
class login_controller {

    // Función para logearse
    function login() {
        $user_model = new login_model();
        $conexion = $user_model->db;

        $user = !empty($_POST['username']) ? $_POST['username'] : "";
        $contrasenya = !empty($_POST['password']) ? $_POST['password'] : "";

        $user_model->setUsername($user);
        $user_model->setPassword($contrasenya);


        $userType = $user_model->verifyUser();


        // your secret key
        // $secret = "6LfoTFwUAAAAAHLU-wIipNaqppNqHQ0MquEuxF5a";
        //
        // // empty response
        // $response = null;
        //
        // // check secret key
        // $reCaptcha = new ReCaptcha($secret);
        //
        // // if submitted check response
        // if ($_POST["g-recaptcha-response"]) {
        //     $response = $reCaptcha->verifyResponse(
        //         $_SERVER["REMOTE_ADDR"],
        //         $_POST["g-recaptcha-response"]
        //     );
        // }
        // si se ha logeado correctamente muestra la página principal y devuelve true, sino false y mostrará un intento
        // de inicio de sesión fallido
        // if (!empty($userType) && $response != null && $response->success) {

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


        //$response = file_get_contents($url);
        $response = json_decode($response);
        if ($response->success) {
            if (!empty($userType)) {
                $_SESSION['user'] = $user;

                $_SESSION['userType'] = $userType["tipo_usuario"];


                $home = new home_controller();
                $_SESSION['userType'];
                $home->view();

                return true;
            } else {
                $home = new home_controller();
                $home->view("", "", "", false);
            }
        }
    }

    // Función para registrarse
    function register() {
        
    }

    // Función que muestra un error
    function loginFailed() {
        
    }

}

?>

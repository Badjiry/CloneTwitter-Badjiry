<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once '../Config/dbconnect.php';
require_once '../Models/LoginModel.php';

$loginModel = new Login($db); 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['email']) && isset($_POST['password'])) { 
        $email = $_POST['email'];
        $password = $_POST['password'];

        $user = $loginModel->verifConnexion($email, $password); 

        if ($user) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_email'] = $user['email'];
            header("Location: ../Views/TimelineView.php");
            exit;
        } else {
            echo "Identifiants incorrects.";
        }
    }
}

class LoginController {
    public function logout() {
        if (session_status() == PHP_SESSION_ACTIVE) {
            session_unset();
            session_destroy();
        }
        header("Location: ../Views/LoginView.php");
        exit();
    }
}

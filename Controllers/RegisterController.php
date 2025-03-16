<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once '../Config/dbconnect.php';
require_once '../Models/RegisterModel.php';
require('../Views/RegisterView.php');

$registerModel = new Register($db);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if ($registerModel->verifEmail($_POST['email'])) {
        echo "<script>alert('Cet email est déjà utilisé.'); window.location.href='../Views/RegisterView.php';</script>";
        exit();
    }

    $firstname = htmlspecialchars($_POST['firstname']);
    $lastname = htmlspecialchars($_POST['lastname']);
    $username = htmlspecialchars($_POST['username']);
    $birthdate = $_POST['birthdate'];
    $genre = htmlspecialchars($_POST['genre']);
    $country = htmlspecialchars($_POST['country']);
    $city = htmlspecialchars($_POST['city']);
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    $password = $_POST['password'];
    $creation_date = date("Y-m-d");

    
    $birthDateTime = new DateTime($birthdate);
    $today = new DateTime();
    $age = $today->diff($birthDateTime)->y;

    if ($age < 13) {
        echo "<script>alert('Vous devez avoir au moins 13 ans pour vous inscrire.'); window.location.href='../Views/RegisterView.php';</script>";
        exit();
    }

    if ($firstname && $lastname && $username && $birthdate && $city && $country && $email && $password && $genre && $creation_date) {
        $result = $registerModel->registerUser($email, $firstname, $lastname, $username, $birthdate, $city, $country, $password, $genre, $creation_date);

        if (isset($result["error"])) {
            echo "<script>alert('" . $result["error"] . "'); window.location.href='../Views/RegisterView.php';</script>";
            exit();
        } else {
            $_SESSION['user_id'] = $result["user_id"];
            echo "<script>alert('Inscription réussie !'); window.location.href='../Views/TimelineView.php';</script>";
            exit();
        }
    } else {
        echo "<script>alert('Veuillez remplir tous les champs.'); window.location.href='../Views/RegisterView.php';</script>";
        exit();
    }
    
    $datebirthdate = new DateTime($birthdate);
    $today = new DateTime();
    $age = $today->diff($datebirthdate)->y;

    if ($age < 13) {
        echo "<script>alert('Vous devez avoir au moins 13 ans pour vous inscrire.');</script>";
    } else {
        if ($firstname && $lastname && $username && $birthdate && $city && $country && $email && $password && $genre && $creation_date) {
            $userId = $registerModel->registerUser($email, $firstname, $lastname, $username, $birthdate, $city, $country, $password, $genre, $creation_date);

            if ($userId) {
                $_SESSION['user_id'] = $userId;
                header("Location: ../Views/TimelineView.php");
                exit();
            } else {
                echo "Erreur lors de l'inscription.";
            }
        } else {
            echo "Veuillez remplir tous les champs.";
        }
    }
}

?>

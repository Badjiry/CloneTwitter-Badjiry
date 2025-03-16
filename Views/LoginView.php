<?php
include "../Config/dbconnect.php";
require_once '../Controllers/LoginController.php';

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['logout'])) {
    $controller = new LoginController();
    $controller->logout();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../Assets/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
        };
    </script>
    <title>Connexion</title>
</head>
<body class="dark:bg-black dark:text-white">
    <button id="theme-toggle" class="fixed top-5 right-5 p-2 bg-gray-800 text-white rounded-full">
    🌙
    </button>   
    <div class="flex items-center justify-center mt-20">
        <img src="../Assets/logo/Black_Illustration_Ninja_Esport_Or_Gaming_Mascot_Logo_-3-removebg-preview.png" alt="">
        <div class="ml-5 p-10 border rounded-xl shadow-xl shadow-blue-400">
            <form action="../Controllers/LoginController.php" method="POST" class="">
                <label for="email"></label>
                <div class="mt-5">
                    <i class="fa-solid fa-user dark:text-white"></i>
                    <input type="email" name="email" class="border rounded-md p-0.3 ml-1 text-black" placeholder="Adresse mail" required>
                </div>

                <label for="password"></label>
                <div class="mt-5">
                    <i class="fa-solid fa-lock dark:text-white"></i>
                    <input type="password" name="password" class="border rounded-md p-0.3 ml-1 text-black" placeholder="Mot de passe" required>
                </div>

                <button type="submit" class="border-2 border-blue-300 rounded-md p-1.5  bg-blue-400 hover:bg-gray-800 mt-5 text-gray-50 text-[14px]">Se connecter</button>
            </form>
            <div class="mt-10">
                <p>T'as pas encore de compte soldat?</p>
                <button class="border-2 border-blue-300 rounded-md p-1.5  bg-blue-400 hover:bg-gray-800 mt-2 text-gray-50 text-[14px]">
                    <a href="RegisterView.php" class="text-gray-100 text-[14px]">S'inscrire !</a>
                </button>
            </div>
        </div>
    </div>
    <script src="../Assets/SwitchTheme.js"></script>
    </body>
</html>
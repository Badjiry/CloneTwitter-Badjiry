
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
  tailwind.config = {
    darkMode: 'class',
  };
</script>
    <title>Page inscription</title>
</head>
<body class="bg-white text-black dark:bg-black dark:text-white">
    
<?php
session_start();
if (isset($_SESSION['error_message'])) {
    echo "<p class='error-message'>" . $_SESSION['error_message'] . "</p>";
    unset($_SESSION['error_message']); 
}
?>

    <button id="theme-toggle" class="fixed top-5 right-5 p-2 bg-gray-800 text-white rounded-full">
        🌙
    </button>


    <div class="logo-container mt-3">

        <div class="flex items-center justify-center ">

            <img src="../Assets/logo/TweetChain.png" alt="">

            <div class="ml-5 pt-8 pb-8 pl-10 pr-10 border rounded-xl shadow-xl shadow-blue-400 text-black">
                <form id="Form_inscription" method="post" action="../Controllers/RegisterController.php">

                    <label for= "firstname" name="firstname">
                    <div>
                        <i class="fa-solid fa-user dark:text-white"></i>
                        <input type="text" id="firstname" name="firstname" class="border rounded-md p-0.3 ml-1" placeholder="Prénom" required>
                    </div>

                    <label for= "lastname" name="lastname">
                    <div class="mt-5">
                        <i class="fa-solid fa-user dark:text-white"></i>
                        <input type="text" id="lastname" name="lastname" class="border rounded-md p-0.3 ml-1" placeholder="Nom" required>
                    </div>

                    <label for="pseudo" name="username">
                    <div class="mt-5">
                        <i class="fa-solid fa-user-tag dark:text-white"></i>
                        <input type="text" id="pseudo" name="username" class="border rounded-md p-0.3 ml-1" placeholder="Nom d'utilisateur" required>
                    </div>

                    <label for="birthdate" name="birthdate">
                    <div class="mt-5">
                        <i class="fa-solid fa-calendar dark:text-white"></i>
                        <input type="date" id="birthdate" name="birthdate" class="border rounded-md p-0.3 ml-1" placeholder="date de naissance" required>
                    </div>


                    <label for="genre" name="genre">
                    <div class="mt-5">
                        <i class="fa-solid fa-venus-mars dark:text-white"></i>
                        <select id="genre" class="border rounded-md p-0.3 ml-1" name="genre" required>
                            <option value="" disabled selected>Genre</option>
                            <option value="Homme">Homme</option>
                            <option value="Femme">Femme</option>
                            <option value="Autre">Autre</option>
                        </select>
                    </div>

                    <label for="country" name="country">
                    <div class="mt-5">
                        <i class="fa-solid fa-globe dark:text-white"></i>
                        <input type="text" id="country" name="country" class="border rounded-md p-0.3 ml-1" placeholder="Pays" required>
                    </div>

                    <label for="city" name="city">
                    <div class="mt-5">
                        <i class="fa-solid fa-globe dark:text-white"></i>
                        <input type="text" id="city" name="city" class="border rounded-md p-0.3 ml-1" placeholder="Ville" required>
                    </div>


                    <label for="email" name="email">
                    <div class="mt-5">
                        <i class="fa-solid fa-envelope dark:text-white"></i>
                        <input type="email" id="email" name="email" class="border rounded-md p-0.3 ml-1" placeholder="Adresse mail" required>
                    </div>

                    <label for="password" name="password">
                    <div class="mt-5">
                        <i class="fa-solid fa-lock dark:text-white"></i>
                        <input type="password" id="password" name="password" class="border rounded-md p-0.3 ml-1" placeholder="Mot de passe" required>
                    </div>

                    <button type="submit" class="border-2 border-blue-300 rounded-md p-1.5  bg-blue-400 hover:bg-gray-800 mt-5 text-gray-50 text-[14px]">S'inscrire</button>

                </form>

                <div class="mt-3">
                    <p class="dark:text-white">T'as déjà un compte mon soldat?</p>
                    <button class="border-2 border-blue-300 rounded-md p-1.5  bg-blue-400 hover:bg-gray-800 mt-3 text-gray-50 text-[14px]">
                        <a href="../Views/LoginView.php">Connecte-toi</a>
                    </button>
                </div>
            </div>        
        </div>
    </div>
    <script src="../Assets/register.js"></script>
    <script src="../Assets/SwitchTheme.js"></script>
    </body>
</html>
<?php
require_once '../Models/MessageModel.php';
require_once '../Config/dbconnect.php';

session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../LoginView.php");
    exit;
}

$messageModel = new MessageModel($db);
$id_sender = $_SESSION['user_id']; // Utilisateur connecté

$messages = [];
if (isset($_GET['id_receiver'])) {
    $id_receiver = $_GET['id_receiver'];
    $messages = $messageModel->getMessages($id_sender, $id_receiver);
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messagerie Privée</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
        };
    </script>
    <style>
        .chat-box {  height: 300px; overflow-y: scroll; padding: 10px; }
        .message { margin: 5px 0; padding: 8px; border-radius: 5px; max-width: 60%; }
        .sent { background-color: #dcf8c6; text-align: right; float: right; clear: both; }
        .received { background-color: #f1f0f0; text-align: left; float: left; clear: both; }
    </style>
</head>
<body class="min-h-screen overflow-x-hidden dark:bg-black dark:text-white">
    <header>
        <!-- bar laterale gauche -->
        <nav class="fixed left-0 top-0 h-full w-1/6 dark:bg-black border-r border-gray-400 flex flex-col items-center py-6 z-50 ">                
            <img src="../Assets/logo/Black_Illustration_Ninja_Esport_Or_Gaming_Mascot_Logo_-3-removebg-preview.png" class="h-[100px] w-[100px] object-contain" alt="Logo">
            <ul class="w-full flex flex-col items-center mt-20 space-y-4">
                <li class="w-4/5 flex items-center text-xl md:text-lg lg:text-base xl:text-xl hover:text-blue-500 md:justify-start justify-center">
                    <a href="/Views/TimelineView.php"><i class="fa-solid fa-house text-xl md:text-lg lg:text-base xl:text-xl"></i></a>
                    <a href="/Views/TimelineView.php" class="hidden md:inline-block ml-3">Accueil</a>
                </li>
                <li class="w-4/5 flex items-center text-xl md:text-lg lg:text-base xl:text-xl hover:text-blue-500 md:justify-start justify-center">
                    <a href="/Views/ProfilView.php"><i class="fa-solid fa-user text-xl md:text-lg lg:text-base xl:text-xl"></i></a>
                    <a href="/Views/ProfilView.php" class="hidden md:inline-block ml-3">Profil</a>
                </li>
                <li class="w-4/5 flex items-center text-xl md:text-lg lg:text-base xl:text-xl hover:text-blue-500 md:justify-start justify-center">
                    <a href="/Views/MessageView.php"><i class="fa-solid fa-envelope text-xl md:text-lg lg:text-base xl:text-xl"></i></a>
                    <a href="/Views/MessageView.php" class="hidden md:inline-block ml-3">Messages</a>
                </li>
                <!-- Bouton Tweeter  -->
                <li class="py-3 mt-4 w-4/5 flex items-center md:justify-start justify-center">
                    <button onclick="openPopup('AddTweetPopup')" class="flex items-center justify-center bg-black text-white dark:bg-white dark:text-black font-semibold rounded-full border border-gray-700 dark:border-gray-300 hover:bg-gray-700 dark:hover:bg-gray-400 w-auto px-4 py-3 md:w-[50px] lg:w-full md:text-lg lg:text-base xl:text-xl">
                        <i class="fa-solid fa-pen-to-square text-xl md:text-lg lg:text-base xl:text-xl lg:hidden"></i>
                        <span class="hidden lg:inline-block ml-3">Poster</span>
                    </button>
                </li>
            </ul>
            <!-- Pop up Tweeter  -->
            <div id="AddTweetPopup" class="fixed inset-0 flex items-center justify-center bg-black/50 backdrop-blur-md hidden">
                <div class="bg-white dark:bg-gray-900 p-6 rounded-xl shadow-xl w-full max-w-lg ">
                    <h2 class="text-lg font-semibold text-black dark:text-white mb-4">Ajouter un tweet</h2>
                    <form action="../Controllers/ProfileController.php?action=addTweet" method="POST" enctype="multipart/form-data" class="space-y-4">
                        <textarea name="addTweet" placeholder="Azy Tweet mon soldat..." maxlength="140" required class="w-full bg-gray-200 dark:bg-gray-800 text-black dark:text-white border border-gray-300 dark:border-gray-700 rounded-lg p-3 focus:outline-none focus:ring-2 focus:ring-blue-500 transition"></textarea>
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Ajouter des médias (max 4) :</p>
                            <div class="flex flex-wrap gap-2">
                                <input type="file" name="media1" class="hidden" id="media1">
                                <input type="file" name="media2" class="hidden" id="media2">
                                <input type="file" name="media3" class="hidden" id="media3">
                                <input type="file" name="media4" class="hidden" id="media4">

                                <label for="media1" class="cursor-pointer bg-gray-300 dark:bg-gray-700 text-gray-900 dark:text-gray-300 px-4 py-2 rounded-lg hover:bg-gray-400 dark:hover:bg-gray-600 transition">Média 1</label>
                                <label for="media2" class="cursor-pointer bg-gray-300 dark:bg-gray-700 text-gray-900 dark:text-gray-300 px-4 py-2 rounded-lg hover:bg-gray-400 dark:hover:bg-gray-600 transition">Média 2</label>
                                <label for="media3" class="cursor-pointer bg-gray-300 dark:bg-gray-700 text-gray-900 dark:text-gray-300 px-4 py-2 rounded-lg hover:bg-gray-400 dark:hover:bg-gray-600 transition">Média 3</label>
                                <label for="media4" class="cursor-pointer bg-gray-300 dark:bg-gray-700 text-gray-900 dark:text-gray-300 px-4 py-2 rounded-lg hover:bg-gray-400 dark:hover:bg-gray-600 transition">Média 4</label>
                            </div>
                        </div>
                        <div class="flex justify-end gap-2">
                            <button type="button" onclick="closePopup('AddTweetPopup')" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition">Annuler</button>
                            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-800 transition">Tweet</button>
                        </div>
                    </form>
                </div>
            </div>
                <!-- bouton deconnexion -->
                <form method="POST" action="LoginView.php" class="mt-auto w-full flex justify-center pb-4">
                    <button type="submit" name="logout" class="flex items-center justify-center bg-white text-black dark:bg-black dark:text-white font-semibold rounded-full border border-gray-700 dark:border-gray-300 hover:bg-gray-200 dark:hover:bg-gray-800  w-auto px-4 py-3 md:w-[50px] lg:w-4/5 md:text-lg lg:text-base xl:text-xl">
                        <i class="fa-solid fa-right-to-bracket text-xl md:text-lg lg:text-base xl:text-xl lg:hidden"></i>
                        <span class="hidden lg:inline-block ml-3">Déconnexion</span>
                    </button>
        </nav>
        <!-- top bar fixe -->
        <div class="fixed top-0 left-0 w-full h-16 dark:bg-black/50 backdrop-blur-md flex items-center justify-center px-40 z-40">
            <h1 class="text-xl font-bold dark:text-white transition-all duration-700">WELCOME TO THE FUTURE 🥷🏿 NIGGACHAIN A.I 2.0</h1>
        </div>
        <!-- bar laterale droite -->
        <div class="fixed right-0 top-0 h-full w-1/6 dark:bg-black border-l border-gray-400 p-2 z-50">
            <form action="SearchView.php" method="POST" class="space-y-4 w-full flex flex-col items-center">
                <input type="text" name="search" placeholder="Recherche..." class="w-full lg:w-4/5 md:w-[50px] px-4 py-2 text-gray-700 dark:bg-gray-900 dark:text-white rounded outline-none focus:ring-2 focus:ring-blue-500 transition-all duration-700 text-center">
                <button type="submit" class="flex items-center justify-center w-full lg:w-4/5 md:w-[50px] bg-blue-600 hover:bg-blue-800 text-white py-2 rounded ">
                    <i class="fa-solid fa-search text-xl lg:hidden"></i>
                    <span class="hidden lg:inline-block">Rechercher</span>
                </button>
            </form>
            <button id="theme-toggle" class="fixed bottom-3 right-5 p-2 bg-blue-600 hover:bg-blue-800 text-white rounded-full">🌙</button>
        </div>
    </header>
        
    <main class="p-10 pt-20 pl-[16.6%] pr-[16.6%]">

    <h2 class="flex justify-center text-2xl">Messagerie Privée</h2>
    
    <form method="GET" class="mt-20">
        <label for="id_receiver" class="ml-5 mr-2">ID du destinataire :</label>
        <input class="mr-3 px-4 py-2 text-gray-700 dark:bg-gray-900 dark:text-white rounded focus:ring-2 focus:ring-blue-500" type="text" name="id_receiver" required>
        <button type="submit">Charger les messages</button>
    </form>

    <?php if (!empty($messages)): ?>
        <div class="chat-box">
            <?php foreach ($messages as $msg): ?>
                <div class="border border-gray-400 bg-blue-400 rounded-2xl p-2 <?= ($msg['id_sender'] == $id_sender) ? 'sent' : 'received' ?>">
                    <strong class="text-sm"><?= ($msg['id_sender'] == $id_sender) ? "Vous" : "Utilisateur " . $msg['id_sender'] ?> :</strong>
                    <?= htmlspecialchars($msg['content']) ?>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <?php if (isset($id_receiver)): ?>
        <form class="mt-5" method="POST" action="../Controllers/MessageController.php">
            <input type="hidden" name="id_receiver" value="<?= $id_receiver ?>">
            <input class="mr-3 px-4 py-2 text-gray-700 dark:bg-gray-900 dark:text-white rounded focus:ring-2 focus:ring-blue-500" type="text" name="content" placeholder="Écrire un message" required>
            <button class="text-lg" type="submit">Envoyer</button>
        </form>
    <?php endif; ?>
    </main>
    <script src="../Assets/SwitchTheme.js"></script>
    <script>
function openPopup(popupId) {
    document.getElementById(popupId).style.display = "block";
}
function closePopup(popupId) {
    document.getElementById(popupId).style.display = "none";
}
window.onclick = function(event) {
    let popups = document.getElementsByClassName("popup");
    for (let i = 0; i < popups.length; i++) {
        if (event.target == popups[i]) {
            popups[i].style.display = "none";
        }
    }
}
function openPopup(id) {
    document.getElementById(id).classList.remove('hidden');
}

function closePopup(id) {
    document.getElementById(id).classList.add('hidden');
}
</script>
</body>
</html>


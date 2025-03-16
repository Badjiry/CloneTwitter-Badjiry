<?php
require_once('../Models/SearchModel.php');
require_once('../Config/dbconnect.php');

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: LoginView.php");
    exit();
}

$model = new Search($db);
$searchTerm = "";
$tweets = [];
$users = [];

if (isset($_POST['search']) && !empty(trim($_POST['search']))) {
    $searchTerm = htmlspecialchars(trim($_POST['search']));
    $tweets = $model->search($searchTerm);
    $users = $model->searchUsers($searchTerm);
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Résultats de la recherche</title>
    <link href="../Assets/styles.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
        };
    </script>
</head>
<body class="overflow-x-hidden dark:bg-black dark:text-white transition-all duration-700">
<header>

    <nav class="fixed left-0 top-0 h-full w-1/6 dark:bg-black border-r border-gray-400 px-12 py-6 z-50 transition-all duration-700">
    <img src="../Assets/logo/Black_Illustration_Ninja_Esport_Or_Gaming_Mascot_Logo_-3-removebg-preview.png" class="h-[100px] w-[100px]" alt="">
        <ul class="mt-20">
            <li class="py-2 text-xl flex hover:text-blue-500">
                <i class="fa-solid fa-house mr-2 mt-0.5"></i>
                <h2><a href="/Views/TimelineView.php">Acceuil</a></h2>
            </li>
            <li class="py-3 text-xl flex hover:text-blue-500">
                <i class="fa-solid fa-user mr-2 mt-0.5"></i>
                <h2><a href="/Views/ProfilView.php">Profil</a></h2>
            </li>
            <li class="py-3 text-xl flex hover:text-blue-500">
            <i class="fa-solid fa-envelope mr-2 mt-0.5"></i>
                <a href="/Views/MessageView.php">Messages</a>
            </li>
            <li class="py-3 mt-4">
                <button onclick="openPopup('AddTweetPopup')" 
                    class="w-full bg-black text-white dark:bg-white dark:text-black font-semibold py-3 rounded-full border border-gray-700 dark:border-gray-300 hover:bg-gray-700 dark:hover:bg-gray-400 transition transition-all duration-700">
                    Poster
                </button>
            </li>


            <form method="POST" action="LoginView.php" class="mt-4">
                <button type="submit" name="logout" 
                    class="fixed bottom-3 w-auto bg-white text-black dark:bg-black dark:text-white font-semibold px-4 py-3 rounded-full border border-gray-300 dark:border-gray-700 hover:bg-gray-200 dark:hover:bg-gray-800 transition transition-all duration-700">
                    <i class="fa-solid fa-right-to-bracket"></i> Déconnexion
                </button>
            </form>
        </ul>
    </nav>
    <div class="fixed top-0 left-0 w-full h-16 dark:bg-black/50 backdrop-blur-md flex items-center justify-center px-40 z-40 transition-all duration-700">
        <h1 class="text-xl font-bold dark:text-white transition-all duration-700">WELCOME TO THE FUTURE 🥷🏿 NIGGACHAIN A.I 2.0</h1>
    </div>
    <div class="fixed right-0 top-0 h-full w-1/5 dark:bg-black border-l border-gray-400 p-6 z-50 transition-all duration-700">
        <form action="SearchView.php" method="POST" class="space-y-4">
            <input type="text" name="search" placeholder="Recherche de haters..." 
                class="w-full px-4 py-2 text-gray-700 dark:bg-gray-900 dark:text-white rounded outline-none focus:ring-2 focus:ring-blue-500 transition-all duration-700">
            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-800 text-white py-2 rounded">
                Rechercher
            </button>
        </form>
        <button id="theme-toggle" class="fixed bottom-3 right-5 p-2 bg-blue-600 hover:bg-blue-800 text-white rounded-full">
            🌙
        </button>
    </div>
</header>
<main class="p-10 pt-20 pl-[16.6%] pr-[20%]">

    <h1 class="flex justify-center text-2xl">Résultats de haters !</h1>
    <div class="container">
        <div class="ml-10 mt-6">
            <h2 class="text-xl">Utilisateurs</h2>
            <?php if (!empty($users)): ?>
                <div class="mt-4">
                    <?php foreach ($users as $user): ?>
                        <div class="flex " >
                            <img class="w-[40px] h-[40px] rounded-[50%] mt-5" src="<?= !empty($user['picture']) ? htmlspecialchars($user['picture']) : '../Assets/pfdefault.png'; ?>" class="tweet-avatar">

                            <a class="mt-6 ml-5 mr-5" href="PublicProfileView.php?username=<?= urlencode($user['username']) ?>">
                                @<?= htmlspecialchars($user['username']) ?>
                            </a>
                            <a href="PublicProfileView.php?username=<?= urlencode($user['username']) ?>">
                            <?php if (!empty ($user['display_name'])) { echo htmlspecialchars($user['display_name']);}?>
                            </a>
                            <?php if ($user['id'] !== $_SESSION['user_id']): ?>
                                <form action="../Controllers/SearchController.php" method="POST">
                                    <input type="hidden" name="followed_id" value="<?= $user['id'] ?>">
                                    <?php if ($model->isFollowing($_SESSION['user_id'], $user['id'])): ?>
                                        <input type="hidden" name="action" value="unfollow">
                                        <button class="mt-6 hover:text-red-500" type="submit">Se désabonner</button>
                                    <?php else: ?>
                                        <button class="mt-6 hover:text-blue-300" type="submit">S'abonner</button>
                                    <?php endif; ?>
                                </form>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p>Aucun utilisateur trouvé pour "<?= $searchTerm ?>".</p>
            <?php endif; ?>
        </div>

        <div class="mt-10">
            <h2 class="text-xl ml-10">Tweets</h2>
            <?php if (!empty($tweets)): ?>
                <div class="w-full mt-4">
                    <?php foreach ($tweets as $tweet): ?>
                        <div class="border border-gray-400 p-6">
                            <div class="flex align-middle gap-1">
                            <img class="w-[40px] h-[40px] rounded-[50%] mb-3 ml-5" src="<?= !empty($tweet['picture']) ? htmlspecialchars($tweet['picture']) : '../Assets/pfdefault.png'; ?>" class="tweet-avatar">
                            <small>
                                <a class="text-base ml-5 mt-5" href="PublicProfileView.php?username=<?= urlencode($tweet['username']) ?>">
                                @<?= htmlspecialchars($tweet['username']) ?>
                                </a>
                            </small>
                            </div>
                            <p class="ml-5 mt-3"><?= nl2br(htmlspecialchars($tweet['content'])) ?></p>
                            <small class="ml-4 mt-3">Posté le <?= date('d/m/Y H:i', strtotime($tweet['creation_date'])) ?></small>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p>Aucun tweet trouvé pour "<?= $searchTerm ?>".</p>
            <?php endif; ?>
        </div>
    </div>
<script src="../Assets/SwitchTheme.js"></script>

</body>
</html>
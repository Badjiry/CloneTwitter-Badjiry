<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once '../Models/ProfileModel.php';
require_once '../Config/dbconnect.php';

if (!isset($_GET['username']) || empty($_GET['username'])) {
    echo "Aucun utilisateur spécifié.";
    exit;
}
$username = htmlspecialchars($_GET['username']);

$profileModel = new Profile($db);
$userProfile = $profileModel->getUserByUsername($username);

if (!$userProfile) {
    echo "Utilisateur non trouvé.";
    exit;
}


if ($userProfile['id'] == $_SESSION['user_id']) {
    header("Location: ../Views/ProfilView.php");
    exit;
}



$userId = $userProfile['id']; 

$followers = $profileModel->Followers($userId);
$following = $profileModel->Following($userId);
$profileTweets = $profileModel->TweetsUser($userId);
$countFollowing = $profileModel->CountFollowing($userId);
$countFollowers = $profileModel->CountFollowers($userId);
$profileTweets = $profileModel->TweetsUser($userId);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php if (!empty ($userProfile['display_name'])) {echo htmlspecialchars($userProfile['display_name']);} else {echo htmlspecialchars($userProfile['firstname']).' '. htmlspecialchars($userProfile['lastname']);} echo ' (@'.htmlspecialchars($userProfile['username']).') / NxggaChain';?></title>
    <link href="../Assets/style.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
        };
    </script>
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
    <section class="relative w-full max-w-3xl mx-auto bg-white text-black dark:bg-black dark:text-white rounded-lg shadow-md pb-3">
    <!-- Couverture -->
    <div class="w-full h-56 overflow-hidden relative">
        <img class="w-full h-full object-cover transition-all duration-700" src="<?php if (!empty($userProfile['header'])) { echo htmlspecialchars($userProfile['header']);} else {echo '../Assets/pfdefault.png';}?>">
    </div>

    <!-- Image de Profil -->
    <div class="relative flex items-center px-6">
        <div class="absolute -top-16 left-6">
            <img class="w-24 h-24 lg:w-36 lg:h-36 object-cover rounded-full border-4 border-white shadow-md dark:border-black transition-all duration-700 lg:mt-2" src="<?php if (!empty($userProfile['picture'])) { echo htmlspecialchars($userProfile['picture']);} else {echo '../Assets/pfdefault.png';}?>">
        </div>
    </div>

    <!-- Infos Profil -->
    <div class="mt-10 lg:mt-24 px-6">
        <strong class="text-sm md:text-lg lg:text-xl xl:text-2xl font-bold transition-all duration-700">
            <?php if (!empty ($userProfile['display_name'])) {echo htmlspecialchars($userProfile['display_name']);} else {echo htmlspecialchars($userProfile['firstname']).' '. htmlspecialchars($userProfile['lastname']);} ?>
        </strong>
        <p class="text-[10px] md:text-sm lg:text-base xl:text-lg text-gray-600 dark:text-gray-400 transition-all duration-700">@<?php echo htmlspecialchars($userProfile['username']); ?></p>
        <p class="mt-2 text-[10px] md:text-sm lg:text-base xl:text-lg text-black dark:text-white transition-all duration-700"><?php if (!empty($userProfile['biography'])){echo htmlspecialchars($userProfile['biography']);}?> </p>
        <!-- Lien -->
        <?php if (!empty($userProfile['url'])): ?>
            <a class="mt-2 text-[10px] md:text-sm lg:text-base xl:text-lg flex items-center gap-2 dark:text-blue-500 transition-all duration-700" href="<?php echo htmlspecialchars($userProfile['url']); ?>" target="_blank">
                <i class="fa-solid fa-link text-[12px] md:text-sm lg:text-base xl:text-lg text-gray-500 dark:text-gray-400 transition-all duration-700"></i>
                <span class="text-[10px] md:text-sm lg:text-base xl:text-lg text-blue-600 hover:underline dark:text-blue-500 transition-all duration-700">
                    <?php echo htmlspecialchars($userProfile['url']); ?>
                </span>
            </a>
        <?php endif; ?>
        <!-- Informations -->
        <small class="block text-[10px] md:text-sm lg:text-base xl:text-lg text-gray-600 mt-3 mb-3 dark:text-gray-400 transition-all duration-700">
            <i class="fa-solid fa-location-dot"></i> <?php echo htmlspecialchars($userProfile['city']); ?>&nbsp;&nbsp;
            <i class="fa-solid fa-cake-candles"></i> Naissance le <?php echo htmlspecialchars($userProfile['birthdate']); ?>&nbsp;&nbsp;
            <i class="fa-solid fa-calendar"></i> À rejoint NxggaChain le <?php echo htmlspecialchars($userProfile['creation_date']); ?>
        </small>
        <!-- Abonnés et Abonnements -->
        <a href="javascript:void(0);" onclick="openPopup('followersPopup')" 
            class="text-black font-bold text-sm hover:underline dark:text-white transition-all duration-700">
            <?= htmlspecialchars($countFollowers); ?>
            <span class="font-normal text-gray-600 text-xs dark:text-white/80 transition-all duration-700"> abonnés</span>
        </a>
        <a href="javascript:void(0);" onclick="openPopup('followingPopup')" 
            class="text-black font-bold text-sm hover:underline dark:text-white transition-all duration-700">
            <?= htmlspecialchars($countFollowing); ?>
            <span class="font-normal text-gray-600 text-xs dark:text-white/80 transition-all duration-700"> abonnements</span>
        </a>
    </div>
</section>



        <!-- Popup Abonnés -->
        <div id="followersPopup" class="fixed inset-0 flex items-center justify-center bg-black/50 backdrop-blur-md hidden z-50">
            <div class="relative bg-white dark:bg-gray-900 p-6 rounded-xl shadow-xl w-full max-w-md">
                <button onclick="closePopup('followersPopup')" class="absolute top-3 right-3 text-2xl text-black dark:text-white hover:text-red-500">&times;</button>
                <h2 class="text-lg font-bold mb-4 text-black dark:text-white">Liste des abonnés</h2>
                
                <?php if (!empty($followers)): ?>
                    <ul class="space-y-3 max-h-72 overflow-y-auto">
                    <?php foreach ($followers as $follower): ?>
                        <li class="flex justify-between items-center p-2 border-b dark:border-gray-700">
                            <a href="PublicProfileView.php?username=<?= urlencode($follower['username']); ?>" class="text-black dark:text-white hover:underline">
                                <strong>
                                    <?php 
                                    if (!empty($follower['display_name'])) {
                                        echo htmlspecialchars($follower['display_name']);
                                    } else { 
                                        echo htmlspecialchars($follower['firstname'] . ' ' . $follower['lastname']);
                                    } 
                                    ?>
                                </strong>
                                <span class="text-gray-600 dark:text-gray-400">(@<?= htmlspecialchars($follower['username']); ?>)</span>
                            </a>
                        </li>
                    <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p class="text-gray-700 dark:text-gray-300">Aucun abonné trouvé.</p>
                <?php endif; ?>
            </div>
        </div>
        <!-- Popup Abonnements -->
        <div id="followingPopup" class="fixed inset-0 flex items-center justify-center bg-black/50 backdrop-blur-md hidden z-50">
            <div class="relative bg-white dark:bg-gray-900 p-6 rounded-xl shadow-xl w-full max-w-md">
                <button onclick="closePopup('followingPopup')" class="absolute top-3 right-3 text-2xl text-black dark:text-white hover:text-red-500">&times;</button>
                <h2 class="text-lg font-bold mb-4 text-black dark:text-white">Liste des abonnements</h2>
                
                <?php if (!empty($following)): ?>
                    <ul class="space-y-3 max-h-72 overflow-y-auto">
                    <?php foreach ($following as $followed): ?>
                        <li class="flex justify-between items-center p-2 border-b dark:border-gray-700">
                            <a href="PublicProfileView.php?username=<?= urlencode($followed['username']); ?>" class="text-black dark:text-white hover:underline">
                                <strong>
                                    <?php 
                                    if (!empty($followed['display_name'])) {
                                        echo htmlspecialchars($followed['display_name']);
                                    } else { 
                                        echo htmlspecialchars($followed['firstname'] . ' ' . $followed['lastname']);
                                    } 
                                    ?>
                                </strong>
                                <span class="text-gray-600 dark:text-gray-400">(@<?= htmlspecialchars($followed['username']); ?>)</span>
                            </a>
                        </li>
                    <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p class="text-gray-700 dark:text-gray-300">Aucun abonnement trouvé.</p>
                <?php endif; ?>
            </div>
        </div>
   

        </div>
<?php if (!empty($profileTweets)): ?>
    <div class="w-full border-t border-gray-600 dark:border-gray-700">
        <?php foreach ($profileTweets as $tweet): ?>
            <div class="p-4 border-b border-gray-600 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-900 transition">
                <!-- Si c'est un retweet -->
                <?php if ($tweet['is_retweet'] == 1 && !empty($tweet['retweeted_by'])): ?>
                    <p class="text-[10px] md:text-sm lg:text-base text-gray-500 mb-2">
                        <i class="fa-solid fa-retweet"></i> Retweeté par <strong>@<?= htmlspecialchars($tweet['retweeted_by']) ?></strong>
                    </p>
                <?php endif; ?>

                <div class="flex items-start gap-3">
                    <!-- Avatar -->
                    <img src="<?= !empty($tweet['picture']) ? htmlspecialchars($tweet['picture']) : '../Assets/pfdefault.png'; ?>"
                         class="w-10 h-10 rounded-full object-cover border border-gray-600">

                    <div class="w-full">
                        <!-- Nom, Username et Date -->
                        <div class="flex items-center gap-2">
                            <a href="PublicProfileView.php?username=<?= urlencode($tweet['username']); ?>">
                                <strong class="text-[8px] md:text-base lg:text-lg text-black mt-1 dark:text-white transition-all duration-700">
                                    <?php 
                                        if (!empty ($tweet['display_name'])) {
                                            echo htmlspecialchars($tweet['display_name']);
                                        } else {
                                            echo htmlspecialchars($tweet['firstname']).' '. htmlspecialchars($tweet['lastname']);
                                        }
                                    ?>
                                </strong>
                            </a>
                            <span class="text-[8px] md:text-sm lg:text-base text-gray-500">
                                @<?= htmlspecialchars($tweet['username']) ?> • <?= !empty($tweet['tweet_date']) ? date('d M', strtotime($tweet['tweet_date'])) : 'Date inconnue'; ?>
                            </span>
                        </div>

                        <!-- Contenu du tweet -->
                        <p class="text-[10px] md:text-base lg:text-lg text-black mt-1 dark:text-white transition-all duration-700"><?= nl2br(htmlspecialchars($tweet['content'])) ?></p>

                        <!-- Images -->
                        <?php 
                        $images = [];
                        for ($i = 1; $i <= 4; $i++) {
                            if (!empty($tweet["media$i"]) && !in_array($tweet["media$i"], $images)) {
                                $images[] = htmlspecialchars($tweet["media$i"]);
                            }
                        }
                        $imageCount = count($images);
                        ?>

                        <?php if ($imageCount > 0): ?>
                            <div class="mt-2 grid gap-2 
                                <?= $imageCount === 1 ? 'grid-cols-1 flex justify-center' : '' ?>
                                <?= $imageCount === 2 ? 'grid-cols-2' : '' ?>
                                <?= $imageCount === 3 ? 'grid-cols-2 grid-rows-2' : '' ?>
                                <?= $imageCount === 4 ? 'grid-cols-2 grid-rows-2' : '' ?>
                            ">
                                <?php foreach ($images as $index => $image): ?>
                                    <img src="<?= $image ?>" alt="Image du tweet"
                                         class="rounded-lg object-cover 
                                         <?= $imageCount === 1 ? 'max-w-[700px] max-h-[500px] w-auto h-auto' : '' ?>
                                         <?= $imageCount === 2 ? 'w-full h-auto' : '' ?>
                                         <?= $imageCount === 3 && $index < 2 ? 'w-full h-auto object-cover' : '' ?>  
                                         <?= $imageCount === 3 && $index === 2 ? 'max-w-[700px] max-h-[500px] w-auto h-auto mx-auto' : '' ?>
                                         <?= $imageCount === 4 ? 'w-full h-auto' : '' ?>">
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                        <!-- Bouton Retweeter dans la public profile view -->
                        <div class="flex justify-end gap-4 mt-3 text-gray-400">
                            <!-- On vérifie que l'utilisateur n'est pas l'auteur du tweet et que ce n'est pas un retweet déjà -->
                            <?php if ($tweet['user_id'] != $_SESSION['user_id'] && $tweet['is_retweet'] == 0): ?>
                                <form action="../Controllers/ProfileController.php?action=retweetUser" method="POST">
                                    <input type="hidden" name="tweet_id" value="<?= $tweet['tweet_id'] ?>">
                                    <div class="relative group">
                                        <button type="submit" class="hover:text-green-500 transition">
                                            <i class="fa-solid fa-retweet"></i>
                                        </button>
                                        <span class="absolute left-[-200%] -translate-x-1/2 -top-8 bg-black text-white text-xs px-2 py-1 whitespace-nowrap rounded opacity-0 group-hover:opacity-100 transition-opacity">
                                            Retweeter
                                        </span>
                                    </div>
                                </form>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <p class="text-[10px] md:text-sm lg:text-base text-gray-600 dark:text-gray-400">Aucun tweet à afficher.</p>
<?php endif; ?>

</main>


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
<script src="../Assets/SwitchTheme.js"></script>

</body>
</html>
<?php
require_once '../Models/ProfileModel.php';
require_once '../Config/dbconnect.php';
require_once '../Models/TimelineModel.php';

session_start();
$userId = $_SESSION['user_id'];



if (!isset($_SESSION['user_id'])) {
    die("Erreur : Utilisateur non connecté.");
}

$username = isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : "";
$profileModel = new Profile($db);
$timelineModel = new TimelineModel($db);

function uploadMedia($file) {
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'video/mp4'];

    if (!in_array($file['type'], $allowedTypes)) {
        die("Erreur : Format non autorisé (JPG, PNG, GIF, MP4 uniquement).");
    }
  
    $uploadDir = "../Assets/MediaTweets/";
    $fileName = uniqid() . "_" . basename($file['name']);
    $uploadPath = $uploadDir . $fileName;

    if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
        return $uploadPath;
    } else {
        return false;
    }
}



if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['action'])) {
    $action = $_GET['action'];

    if ($action === 'addTweet') {
        $content = trim($_POST['addTweet'] ?? '');

        if (empty($content) || strlen($content) > 140) {
            die("Erreur : Le tweet doit contenir entre 1 et 140 caractères.");
        }

        $media = [];
        for ($i = 1; $i <= 4; $i++) {
            if (!empty($_FILES["media$i"]['name'])) {
                $upload = uploadMedia($_FILES["media$i"]);
                if ($upload !== false) {
                    $media[] = $upload;
                }
            }
        }

        if ($timelineModel->addTweet($userId, $content, $media)) {
            header("Location: ../Views/ProfilView.php");
            exit;
        } else {
            die("Erreur lors de l'ajout du tweet.");
        }
    }
    if (isset($_POST['tweet_id'])) {
        $tweetId = $_POST['tweet_id'];

        if ($action === 'deleteTweet') {
            if ($timelineModel->deleteTweet($tweetId, $userId)) {
                header("Location: ../Views/ProfilView.php?success=delete&t=" . time());
                exit;
            } else {
                die("Erreur lors de la suppression du tweet.");
            }
        }
        if ($action === 'unRetweetUser') {
            if ($timelineModel->unRetweetUser($userId, $tweetId)) {
                header("Location: ../Views/ProfilView.php?success=unretweet&t=" . time());
                exit;
            } else {
                die("Erreur : Impossible d'annuler le retweet.");
            }
        }
        if ($action === 'deleteTweet') {
            if ($timelineModel->deleteTweet($tweetId, $userId)) {
                header("Location: ../Views/ProfilView.php?success=delete&t=" . time());
                exit;
            } else {
                die("Erreur lors de la suppression du tweet.");
            }
    }
}

if ($action === 'editProfile') {
    $firstname = $_POST['editFirstname'] ?? '';
    $lastname = $_POST['editLastname'] ?? '';
    $displayname = $_POST['editDisplayname'] ?? '';
    $bio = $_POST['editBio'] ?? '';

    if (!empty($firstname)) {
        $profileModel->UpdateFirstName($userId, $firstname);
    }
    if (!empty($lastname)) {
        $profileModel->UpdateLastName($userId, $lastname);
    }
    if (!empty($displayname)) {
        $profileModel->UpdateDisplayName($userId, $displayname);
    }
    if (!empty($bio)) {
        $profileModel->UpdateBio($userId, $bio);
    }

    if (!empty($_FILES['picture']['name'])) {
        $uploadDir = '../Assets/ProfilePictures/';
        $fileName = basename($_FILES['picture']['name']);
        $targetFilePath = $uploadDir . $fileName;

        if (move_uploaded_file($_FILES['picture']['tmp_name'], $targetFilePath)) {
            $profileModel->UpdateProfilePicture($userId, $targetFilePath);
        }
    }

    if (!empty($_FILES['header']['name'])) {
        $uploadDir = '../Assets/ProfileHeaders/';
        $fileName = basename($_FILES['header']['name']);
        $targetFilePath = $uploadDir . $fileName;

        if (move_uploaded_file($_FILES['header']['tmp_name'], $targetFilePath)) {
            $profileModel->UpdateHeader($userId, $targetFilePath);
        }
    }

    header("Location: ../Views/ProfilView.php?success=edit&t=" . time());
    exit;
}
}


require_once '../Views/ProfilView.php';
require_once '../Views/PublicProfileView.php';


?>

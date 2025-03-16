<?php
require_once '../Models/TimelineModel.php';
require_once '../Config/dbconnect.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    die("Erreur : utilisateur non connecté.");
}

$userId = $_SESSION['user_id'];
$timelineModel = new TimelineModel($db);

// Récupérer les tweets
$tweets = $timelineModel->getTimelineTweets($userId);

// Gestion des actions (Ajouter, Supprimer, Retweeter, Annuler Retweet)
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
            header("Location: ../Views/TimelineView.php");
            exit;
        } else {
            die("Erreur lors de l'ajout du tweet.");
        }
    }

    if (isset($_POST['tweet_id'])) {
        $tweetId = $_POST['tweet_id'];

        if ($action === 'deleteTweet') {
            if ($timelineModel->deleteTweet($tweetId, $userId)) {
                header("Location: ../Views/TimelineView.php?success=delete&t=" . time());
                exit;
            } else {
                die("Erreur lors de la suppression du tweet.");
            }
        }

        if ($action === 'RetweetsUser') {
            if ($timelineModel->RetweetsUser($userId, $tweetId)) {
                header("Location: ../Views/TimelineView.php?success=retweet&t=" . time());
                exit;
            } else {
                die("Erreur : Vous avez déjà retweeté ce tweet.");
            }
        }

        if ($action === 'unRetweetUser') {
            if ($timelineModel->unRetweetUser($userId, $tweetId)) {
                header("Location: ../Views/TimelineView.php?success=unretweet&t=" . time());
                exit;
            } else {
                die("Erreur : Impossible d'annuler le retweet.");
            }
        }
    }
}

require '../Views/TimelineView.php';

// Fonction d'upload des médias
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
    
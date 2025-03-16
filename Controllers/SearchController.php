<?php
require_once('../Models/SearchModel.php');
require_once('../Config/dbconnect.php');

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: LoginView.php");
    exit();
}

$model = new Search($db);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['followed_id'])) {
    $currentUserId = $_SESSION['user_id'];
    $followedId = intval($_POST['followed_id']);

    if (isset($_POST['action']) && $_POST['action'] === 'unfollow') {
        // Désabonnement
        try {
            $query = "DELETE FROM follow WHERE id_user_follow = :currentUser AND id_user_followed = :followedUser";
            $stmt = $db->prepare($query);
            $stmt->execute([
                'currentUser' => $currentUserId,
                'followedUser' => $followedId
            ]);
        } catch (PDOException $e) {
            error_log("Erreur SQL dans SearchController.php : " . $e->getMessage());
        }
    } else {
        // Abonnement
        if (!$model->isFollowing($currentUserId, $followedId)) {
            try {
                $query = "INSERT INTO follow (id_user_follow, id_user_followed) VALUES (:currentUser, :followedUser)";
                $stmt = $db->prepare($query);
                $stmt->execute([
                    'currentUser' => $currentUserId,
                    'followedUser' => $followedId
                ]);
            } catch (PDOException $e) {
                error_log("Erreur SQL dans SearchController.php : " . $e->getMessage());
            }
        }
    }
}

header("Location: ../Views/SearchView.php");
exit();

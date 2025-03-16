<?php
require_once '../Models/MessageModel.php';
require_once '../Config/dbconnect.php';

session_start();

$messageModel = new MessageModel($db);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_receiver'], $_POST['content'])) {
    $id_sender = $_SESSION['user_id'];
    $id_receiver = $_POST['id_receiver'];
    $content = $_POST['content'];
    
    if ($messageModel->sendMessage($id_sender, $id_receiver, $content)) {
        header("Location: ../Views/MessageView.php?id_receiver=$id_receiver");
        exit;
    } else {
        echo "Erreur lors de l'envoi du message.";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id_receiver'])) {
    $id_sender = $_SESSION['user_id'];
    $id_receiver = $_GET['id_receiver'];
    $messages = $messageModel->getMessages($id_sender, $id_receiver);
} else {
    $messages = [];
}
?>


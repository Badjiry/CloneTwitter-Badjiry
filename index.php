<?php

    header("Location: Views/LoginView.php");


require_once 'Config/dbconnect.php';
require_once 'Models/LoginModel.php';

$loginModel = new Login($db);
$user = $loginModel->infoUser($_SESSION['user_id']);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <p> TEST TEST </p>
</body>
</html>

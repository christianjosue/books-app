<?php

require('database.php');

session_start();

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

$id = $_GET['id'];

$statement = $connection->prepare("SELECT * FROM book WHERE id=:id");
$statement->execute(['id' => $id]);

if ($statement->rowCount() == 0) {
    http_response_code(404);
    echo 'HTTP 404 NOT FOUND';
    exit;
}

$book = $statement->fetch(PDO::FETCH_ASSOC);

if ($book['user_id'] !== $_SESSION['user']['id']) {
    http_response_code(403);
    echo 'HTTP 403 NOT AUTHORIZED';
    exit;
}

$connection->prepare('DELETE FROM book WHERE id=:id')->execute(['id' => $id]);

$_SESSION['flash'] = ['message' => "{$book['title']} has been deleted"];

header('Location: home.php');
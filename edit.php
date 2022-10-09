<?php

require('database.php');

session_start();

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

$id = $_GET['id'];

$statement = $connection->prepare('SELECT * FROM book WHERE id=:id');
$statement->execute(['id' => $id]);

if ($statement->rowCount() == 0) {
    http_response_code(404);
    echo 'HTTP 404 NOT FOUND';
    exit;
}

$book = $statement->fetch(PDO::FETCH_ASSOC);

if ($_SESSION['user']['id'] !== $book['user_id']) {
    http_response_code(403);
    echo 'HTTP 403 NOT AUTHORIZED';
    exit;
}

$error = null;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST["title"]) || empty($_POST["author"]) || empty($_POST["pages"]) || empty($_POST["rating"])) {
        $error = "Please fill all the fields";
        echo $error;
    } elseif (!is_int((int)$_POST["pages"])) {
        $error = "Check the number of pages";
        echo $error;
    } else {
        $connection->prepare('UPDATE book SET title=:title, author=:author, pages=:pages, rating=:rating
                        WHERE id=:id')
            ->execute([
                'id' => $id,
                'title' => $_POST['title'],
                'author' => $_POST['author'],
                'pages' => $_POST['pages'],
                'rating' => $_POST['rating']
            ]);

            $_SESSION['flash'] = ['message' => "{$_POST['title']} has been updated"];

        header('Location: home.php');
        exit;
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Books App</title>
    <!-- Register CSS -->
    <link rel="stylesheet" href="css/register.css">

    <!-- Add CSS -->
    <link rel="stylesheet" href="css/add.css">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Mouse+Memoirs&family=Oswald:wght@200;300;400&family=Uchen&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <script src="https://kit.fontawesome.com/7e4ab6ef04.js" crossorigin="anonymous" defer></script>

    <!-- Rating JS -->
    <script src="js/rating.js" defer></script>
</head>

<body>
    <div class="navbar">
        <div class="logo">
            <a href="index.php"><i class="fa-solid fa-book"></i></a>
            <h2><a href="index.php">Books App</a></h2>
        </div>
        <ul class="navbar-items">
            <li><a href="register.php">Register</a></li>
            <li><a href="login.php">Login</a></li>
        </ul>
    </div>

    <div class="main">
        <div class="card">
            <form method="POST" action="edit.php?id=<?= $id ?>" class="register-form">
                <label for="title">Title</label>
                <input type="text" id="title" name="title" autocomplete="title" value="<?= $book['title'] ?>">
                <label for="author">Author</label>
                <input type="text" id="author" name="author" autocomplete="author" value="<?= $book['author'] ?>">
                <label for="pages">Pages</label>
                <input type="number" id="pages" name="pages" autocomplete="pages" value="<?= $book['pages'] ?>">

                <label for="rating">Rating</label>
                <div class="rating"></div>
                <input type="hidden" id="rating" name="rating" autocomplete="rating" value="<?= $book['rating'] ?>">

                <button type="submit" class="btn-submit">Edit</button>
            </form>
        </div>
    </div>

</body>

</html>
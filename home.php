<?php
require("database.php");

session_start();

if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    return;
}

$books = $connection->query("SELECT * FROM book WHERE user_id={$_SESSION["user"]["id"]}");

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Books App</title>
    <!-- Home CSS -->
    <link rel="stylesheet" href="css/home.css">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Mouse+Memoirs&family=Oswald:wght@200;300;400&family=Uchen&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <script src="https://kit.fontawesome.com/7e4ab6ef04.js" crossorigin="anonymous" defer></script>

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

    <?php if (isset($_SESSION['flash'])) : ?>
        <div class="alert">
            <?= $_SESSION['flash']['message']?>
        </div>
        <?php unset($_SESSION['flash'])?>
    <?php endif ?>
    
    <?php if ($books->rowCount() == 0) : ?>
        <div class="main">
            <h3>You haven't read any books</h3>
            <button class="btn-start"><a href="add.php">Start today</a></button>
        </div>
    <?php else : ?>
        <div class="container">
            <table class="books-list">
                <tr>
                    <th>Title</th>
                    <th>Author</th>
                    <th>Pages</th>
                    <th>Rating</th>
                </tr>
                <?php foreach ($books as $book) : ?>
                    <tr>
                        <th><?= $book["title"] ?></th>
                        <th><?= $book["author"] ?></th>
                        <th><?= $book["pages"] ?></th>
                        <th>
                            <?php for ($i = 1; $i < 6; $i++) : ?>
                                <?php if ($i <= $book["rating"]) : ?>
                                    <i class="fa-solid fa-star"></i>
                                <?php else :?>
                                    <i class="fa-regular fa-star"></i>
                                <?php endif ?>
                            <?php endfor ?>
                        </th>
                        <th><a href="edit.php?id=<?= $book['id'] ?>"><i class="fa-solid fa-pencil"></i></a></th>
                        <th><a href="delete.php?id=<?= $book['id'] ?>"><i class="fa-solid fa-trash"></i></a></th>
                    </tr>
                <?php endforeach ?>
            </table>
        </div>
        <a class="btn-add" href="add.php"><i class="fa-solid fa-plus"></i></a>
    <?php endif ?>
</body>

</html>
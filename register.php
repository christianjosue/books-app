<?php  
    require "database.php";

    $error = null;

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (empty($_POST["name"]) || empty($_POST["email"]) || empty($_POST["password"])) {
            $error = "Please fill all the fields";
        } else {
            $statement = $connection->prepare("SELECT * FROM users WHERE email=:email");
            $statement->execute([":email" => $_POST["email"]]);

            if ($statement->rowCount() > 0) {
                $error = "Email is already taken";
            } else {
                $connection->prepare("INSERT INTO users(name, email, password) VALUES (:name, :email, :password)")
                            ->execute([
                                ":name" => $_POST["name"],
                                ":email" => $_POST["email"],
                                ":password" => $_POST["password"]
                            ]);
                
                $statement = $connection->prepare("SELECT * FROM users WHERE email=:email LIMIT 1");
                $statement->execute([":email" => $_POST["email"]]);
                $user = $statement->fetch(PDO::FETCH_ASSOC);

                session_start();
                $_SESSION["user"] = $user;
                
                header("Location: home.php");
            }
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
            <li><a href=".">Register</a></li>
            <li><a href="login.php">Login</a></li>
        </ul>
    </div>

    <div class="main">
        <div class="card">
            <form method="POST" action="register.php" class="register-form">
                <label for="name">Name</label>
                <input type="text" id="name" name="name" autocomplete="name">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" autocomplete="email">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" autocomplete="password">

                <button type="submit" class="btn-submit">Register</button>
            </form>
        </div>
    </div>

</body>

</html>
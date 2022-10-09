<?php

$host = "localhost";
$database = "books_app";
$user = "root";
$password = "";

try {
    $connection = new PDO("mysql:host=$host;dbname=$database", $user, $password);
} catch (PDOException $e) {
    die("PDO Connection Error: " . $e->getMessage());
}
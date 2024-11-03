<?php
session_start();
include 'functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (authenticateUser($username, $password)) {
        setSession('username', $username);
        header("Location: create_topic.php");
        exit();
    } else {
        echo "Invalid credentials";
    }
}
?>

<form method="POST">
    <label>Username: <input type="text" name="username" required></label><br>
    <label>Password: <input type="password" name="password" required></label><br>
    <input type="submit" value="Login">
    <a href="register.php">Register</a>
</form>


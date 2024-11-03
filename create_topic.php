<?php
session_start();
include 'functions.php';

if (!getSession('username')) {
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $creator = getSession('username');

    if (createTopic($creator, $title, $description)) {
        header("Location: topics.php");
        exit();
    }
}
?>

<form method="POST">
    <label>Title: <input type="text" name="title" required></label><br>
    <label>Description: <textarea name="description" required></textarea></label><br>
    <input type="submit" value="Create Topic">
</form>

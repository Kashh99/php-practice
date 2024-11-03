<?php
include 'functions.php';

if (isset($_POST['theme'])) {
    setTheme($_POST['theme']);
    header("Location: index.php");
    exit();
}

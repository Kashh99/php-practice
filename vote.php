<?php
session_start();
include 'functions.php';

if (!getSession('username')) {
    header("Location: index.php");
    exit();
}

$username = getSession('username');
$topicID = $_GET['id'];
$voteType = $_GET['vote'];

// Attempt to record the vote
if (vote($username, $topicID, $voteType)) {
    // Optionally store a success message in session to display after redirect
    $_SESSION['message'] = "Vote recorded!";
} else {
    // Optionally store an error message in session to display after redirect
    $_SESSION['message'] = "You have already voted on this topic.";
}

header("Location: topics.php");
exit();


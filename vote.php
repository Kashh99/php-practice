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

if (vote($username, $topicID, $voteType)) {
    echo "Vote recorded!";
} else {
    echo "You have already voted on this topic.";
}

header("Location: topics.php");
exit();

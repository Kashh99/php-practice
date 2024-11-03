<?php
session_start();
include 'functions.php';

if (!getSession('username')) {
    header("Location: index.php");
    exit();
}

$topics = getTopics();
foreach ($topics as $topic) {
    $results = getVoteResults($topic['id']);
    echo "Topic: {$topic['title']} by {$topic['creator']}<br>";
    echo "Upvotes: {$results['up']}, Downvotes: {$results['down']}<br>";
    echo "<a href='vote.php?id={$topic['id']}&vote=up'>Upvote</a> | ";
    echo "<a href='vote.php?id={$topic['id']}&vote=down'>Downvote</a><br><br>";
}
?>
<a href="profile.php">View Profile</a>
<a href="logout.php">Logout</a>

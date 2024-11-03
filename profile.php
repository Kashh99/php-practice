<?php
session_start();
include 'functions.php';

if (!getSession('username')) {
    header("Location: index.php");
    exit();
}

$username = getSession('username');
$votingHistory = getUserVotingHistory($username);
$totalTopicsCreated = getTotalTopicsCreated($username);
$totalVotesCast = getTotalVotesCast($username);

echo "Voting History:<br>";
foreach ($votingHistory as $history) {
    echo "Topic: {$history['title']} - Vote: {$history['voteType']}<br>";
}

echo "<br>Total Topics Created: $totalTopicsCreated<br>";
echo "Total Votes Cast: $totalVotesCast<br>";
?>
<a href="topics.php">Back to Topics</a>
<a href="logout.php">Logout</a>

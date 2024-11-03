<?php
session_start();
include 'functions.php';

// Check if the user is logged in
if (!getSession('username')) {
    header("Location: index.php");
    exit();
}

// Optionally display any session messages
if (isset($_SESSION['message'])) {
    echo "<div class='message'>{$_SESSION['message']}</div>";
    unset($_SESSION['message']); // Clear the message after displaying it
}

// Fetch topics
$topics = getTopics();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Topics</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .topic { margin-bottom: 20px; }
        .message { color: green; }
        .no-topics { color: red; }
    </style>
</head>
<body>

<h1>Topics</h1>

<?php if (empty($topics)): ?>
    <p class="no-topics">No topics available at the moment.</p>
<?php else: ?>
    <?php foreach ($topics as $topic): 
        $results = getVoteResults($topic['id']);
    ?>
        <div class="topic">
            <h2><?php echo htmlspecialchars($topic['title'] ?? 'Untitled'); ?></h2>
            <p>Created by: <?php echo htmlspecialchars($topic['creator'] ?? 'Unknown'); ?></p>
            <p>Upvotes: <?php echo $results['up']; ?>, Downvotes: <?php echo $results['down']; ?></p>
            <a href="vote.php?id=<?php echo htmlspecialchars($topic['id']); ?>&vote=up">Upvote</a> | 
            <a href="vote.php?id=<?php echo htmlspecialchars($topic['id']); ?>&vote=down">Downvote</a>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

<a href="profile.php">View Profile</a> | 
<a href="logout.php">Logout</a>

</body>
</html>

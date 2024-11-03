<?php
// User Management Functions
function registerUser($username, $password) {
    $file = 'users.txt';
    $users = file($file, FILE_IGNORE_NEW_LINES);

    foreach ($users as $user) {
        list($existingUsername) = explode(":", $user);
        if ($existingUsername == $username) return false;
    }

    file_put_contents($file, "$username:$password\n", FILE_APPEND);
    return true;
}

function authenticateUser($username, $password) {
    $file = 'users.txt';
    $users = file($file, FILE_IGNORE_NEW_LINES);

    foreach ($users as $user) {
        list($storedUsername, $storedPassword) = explode(":", $user);
        if ($storedUsername == $username && $storedPassword == $password) {
            return true;
        }
    }
    return false;
}

// Topic Management Functions
function createTopic($creator, $title, $description) {
    $file = 'topics.txt';
    $id = count(file($file)) + 1; // Consider checking if the file is empty for an accurate ID
    file_put_contents($file, "$id|$creator|$title|$description\n", FILE_APPEND);
    return true;
}

function getTopics() {
    $topics = [];
    $file = 'topics.txt';
    $lines = file($file, FILE_IGNORE_NEW_LINES);

    foreach ($lines as $line) {
        $parts = explode("|", $line);
        
        // Check if we have enough parts
        if (count($parts) < 4) {
            error_log("Malformed topic line: $line");
            continue; // Skip malformed lines
        }
        
        list($id, $creator, $title, $description) = $parts;
        $topics[] = ['id' => $id, 'creator' => $creator, 'title' => $title, 'description' => $description];
    }
    return $topics;
}

// Voting Functions
function vote($username, $topicID, $voteType) {
    if (hasVoted($username, $topicID)) return false;
    file_put_contents('votes.txt', "$username|$topicID|$voteType\n", FILE_APPEND);
    return true;
}

function hasVoted($username, $topicID) {
    $file = 'votes.txt';
    $votes = file($file, FILE_IGNORE_NEW_LINES);

    foreach ($votes as $vote) {
        list($voter, $id, $type) = explode("|", $vote);
        if ($voter == $username && $id == $topicID) return true;
    }
    return false;
}

function getVoteResults($topicID) {
    $upvotes = $downvotes = 0;
    $file = 'votes.txt';
    $votes = file($file, FILE_IGNORE_NEW_LINES);

    foreach ($votes as $vote) {
        list($username, $id, $type) = explode("|", $vote);
        if ($id == $topicID) {
            if ($type == 'up') $upvotes++;
            else $downvotes++;
        }
    }
    return ['up' => $upvotes, 'down' => $downvotes];
}

// Profile Functions
function getUserVotingHistory($username) {
    $file = 'votes.txt';
    $history = [];
    
    $votes = file($file, FILE_IGNORE_NEW_LINES);
    foreach ($votes as $vote) {
        list($voter, $topicID, $voteType) = explode("|", $vote);
        if ($voter == $username) {
            $topicDetails = getTopicById($topicID);
            if ($topicDetails) { // Check if topicDetails is not null
                $history[] = ['title' => $topicDetails['title'], 'voteType' => $voteType];
            } else {
                error_log("Topic with ID $topicID not found for user $username");
            }
        }
    }
    return $history;
}

function getTopicById($topicID) {
    $file = 'topics.txt';
    $lines = file($file, FILE_IGNORE_NEW_LINES);

    foreach ($lines as $line) {
        $data = explode("|", $line);

        // Check that we have exactly 4 parts
        if (count($data) === 4) {
            list($id, $creator, $title, $description) = $data;
            if ($id == $topicID) {
                return ['id' => $id, 'creator' => $creator, 'title' => $title, 'description' => $description];
            }
        } else {
            error_log("Invalid topic line: $line");
        }
    }
    return null; // Return null if the topic ID is not found
}

function getTotalTopicsCreated($username) {
    $file = 'topics.txt';
    $topics = file($file, FILE_IGNORE_NEW_LINES);
    $count = 0;
    
    foreach ($topics as $topic) {
        $data = explode("|", $topic);
        if (count($data) === 4) { // Ensure correct format
            list($id, $creator) = $data;
            if ($creator == $username) $count++;
        } else {
            error_log("Malformed topic line: $topic");
        }
    }
    return $count;
}

function getTotalVotesCast($username) {
    $file = 'votes.txt';
    $votes = file($file, FILE_IGNORE_NEW_LINES);
    $count = 0;
    
    foreach ($votes as $vote) {
        $data = explode("|", $vote);
        if (count($data) === 3) { // Ensure correct format
            list($voter) = $data;
            if ($voter == $username) $count++;
        } else {
            error_log("Malformed vote line: $vote");
        }
    }
    return $count;
}

// Session and Cookie Management Functions
function setSession($key, $value) {
    $_SESSION[$key] = $value;
}

function getSession($key) {
    return $_SESSION[$key] ?? null;
}

function setTheme($theme) {
    setcookie('theme', $theme, time() + (86400 * 30), "/");
}

function getTheme() {
    return $_COOKIE['theme'] ?? 'light';
}

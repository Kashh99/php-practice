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
    $id = count(file($file)) + 1;
    file_put_contents($file, "$id|$creator|$title|$description\n", FILE_APPEND);
    return true;
}

function getTopics() {
    $topics = [];
    $file = 'topics.txt';
    $lines = file($file, FILE_IGNORE_NEW_LINES);

    foreach ($lines as $line) {
        list($id, $creator, $title, $description) = explode("|", $line);
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
            $history[] = ['title' => $topicDetails['title'], 'voteType' => $voteType];
        }
    }
    return $history;
}

function getTopicById($topicID) {
    $file = 'topics.txt';
    $lines = file($file, FILE_IGNORE_NEW_LINES);

    foreach ($lines as $line) {
        list($id, $creator, $title, $description) = explode("|", $line);
        if ($id == $topicID) {
            return ['id' => $id, 'creator' => $creator, 'title' => $title, 'description' => $description];
        }
    }
    return null; // Return null if the topic ID is not found
}

function getTotalTopicsCreated($username) {
    $file = 'topics.txt';
    $topics = file($file, FILE_IGNORE_NEW_LINES);
    $count = 0;
    
    foreach ($topics as $topic) {
        list($id, $creator) = explode("|", $topic);
        if ($creator == $username) $count++;
    }
    return $count;
}

function getTotalVotesCast($username) {
    $file = 'votes.txt';
    $votes = file($file, FILE_IGNORE_NEW_LINES);
    $count = 0;
    
    foreach ($votes as $vote) {
        list($voter) = explode("|", $vote);
        if ($voter == $username) $count++;
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

show_source(__FILE__);

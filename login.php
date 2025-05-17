<?php
session_start();

// Hardcoded users (you can connect this to a DB later)
$users = [
    "admin" => "1234",  // username => password
    "arpan" => "arpan@123"
];

$username = $_POST['username'];
$password = $_POST['password'];

if (isset($users[$username]) && $users[$username] === $password) {
    $_SESSION['username'] = $username;
    header("Location: dashboard.html");
} else {
    echo "Invalid login credentials.";
}

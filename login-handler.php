<?php
session_start();
include 'db.php'; // Include your database connection file

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id ,name,password FROM user WHERE email = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();

    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $name, $hashed_password);
        $stmt->fetch();
        if (password_verify($password, $hashed_password)) {
            $_SESSION['username'] = $name;
            $_SESSION['user_id'] = $id;
            header("Location: admin-panel.php?page=dashboard");
            exit();
        } else {
            $error= "Invalid login credentials.";
            header("Location: login.php?error=" . urlencode("Incorrect password."));
            exit();
        }
    } else {
        $error= "Invalid login credentials.";
            header("Location: login.php?error=" . urlencode("User not found."));
            exit();
    }

    $stmt->close();
    $conn->close();
}

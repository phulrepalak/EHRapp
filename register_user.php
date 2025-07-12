<?php
include 'db.php';

$name = $_POST['name'];
$email = $_POST['email'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Secure hash

$sql = "INSERT INTO user (name, email, password) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $name, $email, $password);

if ($stmt->execute()) {
    // echo "User registered successfully!";
    header("Location: login.php?");
    exit();
} else {
    echo "Error: " . $stmt->error;
}

$conn->close();
?>
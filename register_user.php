<?php
include 'db.php';

$name = $_POST['name'];
$email = $_POST['email'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Secure hash

// Check if email already exists
$checkEmailSql = "SELECT id FROM user WHERE email = ?";
$checkStmt = $conn->prepare($checkEmailSql);
$checkStmt->bind_param("s", $email);
$checkStmt->execute();
$checkStmt->store_result();

if ($checkStmt->num_rows > 0) {
    // Redirect back with error
    header("Location: index.php?error=email_exists");
    exit();
}

// Insert new user
$sql = "INSERT INTO user (name, email, password) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $name, $email, $password);

if ($stmt->execute()) {
    echo "<script>
        alert('User registered successfully!');
        window.location.href = 'login.php';
    </script>";
} else {
    echo "Error: " . $stmt->error;
}

$conn->close();
?>

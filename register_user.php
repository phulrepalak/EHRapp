<?php
include 'db.php';

// Check if all POST fields exist
$name = $_POST['name'] ?? '';
$email = $_POST['email'] ?? '';
$password = isset($_POST['password']) ? password_hash($_POST['password'], PASSWORD_DEFAULT) : '';
$dob = $_POST['dob'] ?? '';
$contact = $_POST['contact'] ?? '';
$role = $_POST['role'] ?? '';
$removeImage = $_POST['remove_image'] ?? '0';

// Set default image path
$profileImgPath = "uploads/default-avatar.png";

// Upload if image is selected and not removed
if (isset($_FILES['profile_img']) && $_FILES['profile_img']['error'] === UPLOAD_ERR_OK && $removeImage !== "1") {
    $uploadDir = "uploads/";
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $fileTmpPath = $_FILES['profile_img']['tmp_name'];
    $fileName = basename($_FILES['profile_img']['name']);
    $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
    $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];

    if (in_array($fileExtension, $allowedExtensions)) {
        $newFileName = uniqid("profile_", true) . "." . $fileExtension;
        $destination = $uploadDir . $newFileName;

        if (move_uploaded_file($fileTmpPath, $destination)) {
            $profileImgPath = $destination;
        }
    }
}

// Prevent duplicate emails
$checkEmailSql = "SELECT id FROM user WHERE email = ?";
$checkStmt = $conn->prepare($checkEmailSql);
$checkStmt->bind_param("s", $email);
$checkStmt->execute();
$checkStmt->store_result();

if ($checkStmt->num_rows > 0) {
    header("Location: index.php?error=email_exists");
    exit();
}

// Insert user
$sql = "INSERT INTO user (name, email, password, dob, contact, role, profile_img)
        VALUES (?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssssss", $name, $email, $password, $dob, $contact, $role, $profileImgPath);

if ($stmt->execute()) {
    session_start(); // Start the session
    $_SESSION['email'] = $email; // Store logged-in user's email

    echo "<script>
        alert('User registered successfully!');
        window.location.href = 'login.php';
    </script>";
} else {
    echo "Error: " . $stmt->error;
}

$conn->close();
?>
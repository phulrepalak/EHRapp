<?php
session_start(); // to store error messages

$host = 'localhost';
$user = 'root';
$pass = '';
$db = 'careyugehrDB';

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $conn->real_escape_string($_POST['name']);
    $age = (int)$_POST['age'];
    $gender = $conn->real_escape_string($_POST['gender']);
    $contact = $conn->real_escape_string($_POST['contact']);

    // Check if contact already exists
    $check = $conn->query("SELECT id FROM patients WHERE contact = '$contact'");
    if ($check->num_rows > 0) {
        $_SESSION['error'] = "Contact already exists!";
        header("Location: dashboard.php");
        exit();
    }
     // ✅ Validate contact: only digits, and exactly 10 digits
    if (!preg_match('/^\d{10}$/', $contact)) {
        $_SESSION['error'] = "Contact number must be exactly 10 digits.";
        header("Location: dashboard.php");
        exit();
    }
    // Insert patient
    $sql = "INSERT INTO patients (name, age, gender, contact) VALUES ('$name', $age, '$gender', '$contact')";
    if ($conn->query($sql) === TRUE) {
        $_SESSION['success'] = "Patient added successfully!";
        header("Location: dashboard.php");
        exit();
    } else {
        $_SESSION['error'] = "Error: " . $conn->error;
        header("Location: dashboard.php");
        exit();
    }
}

$conn->close();
?>

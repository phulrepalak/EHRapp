<?php
include("session.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $patientName = $_POST['patient_name'];
    $fileName = basename($_FILES["document"]["name"]);
    $targetDir = "uploads/";
    $targetFile = $targetDir . $fileName;

    // Optional: Add a unique ID to filename to prevent overwrite
    $targetFile = $targetDir . time() . "_" . $fileName;

    if (move_uploaded_file($_FILES["document"]["tmp_name"], $targetFile)) {
        echo "<p>File uploaded successfully for <strong>$patientName</strong>.</p>";
        echo "<a href='upload.html'>Upload Another</a> | <a href='dashboard.html'>Dashboard</a>";
    } else {
        echo "Error uploading file.";
    }
}

<?php
include "db.php";

$patientId = $_GET['id'] ?? $_POST['patient_id'] ?? null;   

// Fetch patient name for display
$patient = null;
if ($patientId && is_numeric($patientId)) {
    $stmt = $conn->prepare("SELECT name FROM patient WHERE id = ?");
    $stmt->bind_param("i", $patientId);
    $stmt->execute();
    $result = $stmt->get_result();
    $patient = $result->fetch_assoc();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['document']) && $patient) {
    $fileName = $_FILES['document']['name'];
    $tmpName = $_FILES['document']['tmp_name'];
    $uploadDir = "uploads/";
    $filePath = $uploadDir . time() . "_" . basename($fileName);

    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    if (move_uploaded_file($tmpName, $filePath)) {
        $stmt = $conn->prepare("INSERT INTO documents (patient_id, filename, filepath) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $patientId, $fileName, $filePath);
        $stmt->execute();
        echo "<script>alert('Document uploaded successfully'); window.location='view_documents.php?id=$patientId';</script>";
    } else {
        echo "<script>alert('Failed to upload');</script>";
    }
}
?>

<div class="p-6">
    <?php if ($patient): ?>
        <h2 class="text-xl font-semibold mb-4 text-gray-700">Upload Document for <?= htmlspecialchars($patient['name']) ?> (ID: <?= $patientId ?>)</h2>

        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="patient_id" value="<?= $patientId ?>">
            <label class="block mb-2 text-sm">Select PDF file:</label>
            <input type="file" name="document" accept="application/pdf" class="mb-3 p-2 border rounded w-full" required>
            <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Upload</button>
        </form>
    <?php else: ?>
        <p class="text-red-600 font-medium">❌ Invalid or missing patient ID.</p>
    <?php endif; ?>
</div>

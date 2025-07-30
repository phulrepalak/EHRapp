<?php
include "db.php";


$patientId = $_GET['id'] ?? $_POST['patient_id'] ?? null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['document']) && $patientId) {
    $reportName = $_POST['report_name'];
    $uploadedBy = $_POST['uploaded_by'];
    $fileName = $_FILES['document']['name'];
    $tmpName = $_FILES['document']['tmp_name'];
    $uploadDir = "uploads/";
    $filePath = $uploadDir . time() . "_" . basename($fileName);

    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    // Fetch patient name from patient table
    $stmt = $conn->prepare("SELECT name FROM patient WHERE id = ?");
    $stmt->bind_param("i", $patientId);
    $stmt->execute();
    $result = $stmt->get_result();
    $patientName = '';
    if ($row = $result->fetch_assoc()) {
        $patientName = $row['name'];
    }

    if (move_uploaded_file($tmpName, $filePath)) {
        $stmt = $conn->prepare("INSERT INTO document (patient_id, name, file_name, filepath, report_name, uploaded_by) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("isssss", $patientId, $patientName, $fileName, $filePath, $reportName, $uploadedBy);
        $stmt->execute();

        echo "<script>alert('Document uploaded successfully'); window.location='view_documents.php?id=$patientId';</script>";
    } else {
        echo "<script>alert('Failed to upload');</script>";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Upload Document</title>
  <script src="https://cdn.tailwindcss.com"></script> 
</head>
<body class="bg-gray-100">

<?php if ($patientId): ?>
<div class="max-w-3xl mx-auto bg-white p-6 rounded-xl shadow border border-gray-200 mt-10">
  <h2 class="text-xl font-semibold text-gray-800 mb-6">Upload Document</h2>

  <form method="POST" enctype="multipart/form-data">
    <input type="hidden" name="patient_id" value="<?= htmlspecialchars($patientId) ?>">

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <!-- Report Name -->
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Report Name</label>
        <input type="text" name="report_name" placeholder="e.g., Blood Report"
          class="w-full px-4 py-2 border rounded bg-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
      </div>

      <!-- Uploaded By -->
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Uploaded By</label>
        <input type="text" name="uploaded_by" placeholder="e.g., Dr. Sharma"
          class="w-full px-4 py-2 border rounded bg-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
      </div>

      <!-- Upload File -->
      <div class="md:col-span-2">
        <label class="block text-sm font-medium text-gray-700 mb-1">Upload PDF</label>
        <input type="file" name="document" accept="application/pdf"
          class="block w-full border px-4 py-2 text-sm text-gray-700 rounded bg-gray-100 file:bg-blue-600 file:text-white file:font-medium file:px-4 file:py-2 file:border-none file:rounded hover:file:bg-blue-700 cursor-pointer" required>
      </div>
    </div>

    <!-- Upload Button -->
    <div class="text-right mt-6">
      <button type="submit"
        class="bg-green-600 text-white px-6 py-2 rounded-md hover:bg-green-700 transition font-medium">
        Upload Document
      </button>
    </div>
  </form>
</div>
<?php endif; ?>

</body>
</html>

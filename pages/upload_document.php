<?php
include "db.php";

// Get patient ID from GET or POST
$patient_id = $_GET['id'] ?? $_POST['patient_id'] ?? null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['document']) && $patient_id) {
    // Get input values
    $report_name = $_POST['report_name'] ?? '';
    $uploaded_by = $_POST['uploaded_by'] ?? '';
    $file_name = $_FILES['document']['name'];
    $tmp_name = $_FILES['document']['tmp_name'];
    $upload_dir = "uploads/";
    $file_path = $upload_dir . time() . "_" . basename($file_name);
    $uploaded_at = date("Y-m-d H:i:s");

    // Create upload directory if it doesn't exist
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    // Fetch patient name
    $stmt = $conn->prepare("SELECT name FROM patient WHERE id = ?");
    $stmt->bind_param("i", $patient_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $patient_name = '';
    if ($row = $result->fetch_assoc()) {
        $patient_name = $row['name'];
    }

    // Upload and insert
    if (move_uploaded_file($tmp_name, $file_path)) {
        $stmt = $conn->prepare("INSERT INTO document (patient_id, name, report_name, file_name, filepath, uploaded_at, uploaded_by) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("issssss", $patient_id, $patient_name, $report_name, $file_name, $file_path, $uploaded_at, $uploaded_by);
        $stmt->execute();

        echo "<script>alert('Document uploaded successfully'); window.location='patient-profile.php?id=$patient_id';</script>";
    } else {
        echo "<script>alert('Failed to upload document.');</script>";
    }
} elseif (!$patient_id) {
    echo "<script>alert('Patient ID is missing.');</script>";
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

<?php if ($patient_id): ?>
<div class="max-w-3xl mx-auto bg-white p-6 rounded-xl shadow border border-gray-200 mt-10">
  <h2 class="text-xl font-semibold text-gray-800 mb-6">Upload Document</h2>

  <form method="POST" enctype="multipart/form-data">
    <input type="hidden" name="patient_id" value="<?= $patient_id ?>">


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

<?php
include "db.php";

$patientId = $_GET['id'] ?? null;
$patient = null;

if ($patientId && is_numeric($patientId)) {
    $stmt = $conn->prepare("SELECT name FROM patient WHERE id = ?");
    $stmt->bind_param("i", $patientId);
    $stmt->execute();
    $result = $stmt->get_result();
    $patient = $result->fetch_assoc();
}
?>

<div class="p-6">
    <?php if ($patient): ?>
        <h2 class="text-2xl font-semibold mb-4 text-gray-800">Documents of <?= htmlspecialchars($patient['name']) ?> (ID: <?= $patientId ?>)</h2>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            <?php
            $stmt = $conn->prepare("SELECT * FROM documents WHERE patient_id = ?");
            $stmt->bind_param("i", $patientId);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0):
                while ($doc = $result->fetch_assoc()):
            ?>
                <div class="bg-white shadow rounded-xl p-3">
                    <!-- The <embed> tag in HTML is used to embed external content directly into a web page -->
                    <embed src="<?= htmlspecialchars($doc['filepath']) ?>" type="application/pdf" class="w-full h-48 mb-2" />
                    <p class="text-sm font-semibold truncate"><?= htmlspecialchars($doc['filename']) ?></p>
                    <a href="<?= htmlspecialchars($doc['filepath']) ?>" target="_blank" class="block mt-2 text-center bg-blue-600 text-white py-1 rounded hover:bg-blue-700">View Full</a>
                </div>
            <?php
                endwhile;
            else:
                echo "<p class='text-gray-600'>No documents uploaded yet.</p>";
            endif;
            ?>
        </div>
    <?php else: ?>
        <p class="text-red-600 font-medium">❌ Invalid or missing patient ID.</p>
    <?php endif; ?>
</div>

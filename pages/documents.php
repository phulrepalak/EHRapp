<?php
include "db.php";

$sql = "SELECT d.*, p.name 
        FROM document d
        JOIN patient p ON d.patient_id = p.id
        WHERE d.filepath IS NOT NULL AND d.filepath != ''
        ORDER BY d.uploaded_at DESC";

$result = $conn->query($sql);
?>

<!-- Tailwind UI -->
<div class="min-h-screen bg-gray-50 py-10 px-6">
    <h2 class="text-3xl font-bold text-gray-800 mb-8">Uploaded Documents</h2>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php while ($row = $result->fetch_assoc()): ?>
            <?php
            $filePath = 'uploads/' . basename($row['file_name']);
            $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
            $isImage = in_array($extension, ['jpg', 'jpeg', 'png', 'webp']);
            $isPdf = ($extension === 'pdf');

            if (!$isImage && !$isPdf)
                continue;
            ?>
            <div class="bg-white rounded-xl border border-gray-200 shadow hover:shadow-md transition">
                <button
                    onclick="openPreview('<?= htmlspecialchars($filePath) ?>', '<?= htmlspecialchars($row['name']) ?>', '<?= $row['patient_id'] ?>', '<?= htmlspecialchars($row['uploaded_by']) ?>', '<?= date('d M Y, h:i A', strtotime($row['uploaded_at'])) ?>', '<?= htmlspecialchars($row['report_name']) ?>')"
                    class="w-full focus:outline-none">
                    <?php if ($isImage): ?>
                        <img src="<?= htmlspecialchars($filePath) ?>" alt="Document"
                             class="w-full h-64 object-cover rounded-t-xl">
                    <?php else: ?>
                        <div class="w-full h-64 flex items-center justify-center bg-gray-100 text-lg font-medium text-gray-700">
                            PDF Document
                        </div>
                    <?php endif; ?>
                </button>
                <div class="p-4 space-y-1">
                    <a href="/admin-panel.php?page=patient-profile&id=<?= $row['patient_id'] ?>" class="text-blue-600 hover:underline">
                        👤 <?= htmlspecialchars($row['name']) ?>
                    </a>
                    <p class="text-sm text-gray-600">Report: <strong><?= htmlspecialchars($row['report_name']) ?></strong></p>
                    <p class="text-sm text-gray-600">Uploaded by: <?= htmlspecialchars($row['uploaded_by']) ?></p>
                    <p class="text-sm text-gray-500"><?= date("d M Y, h:i A", strtotime($row['uploaded_at'])) ?></p>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</div>

<!-- Preview Modal -->
<div id="previewModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white p-6 rounded-md w-full max-w-3xl relative">
        <button onclick="closePreview()" class="absolute top-2 right-2 text-gray-500 hover:text-black text-2xl font-bold">✖</button>
        <h2 class="text-xl font-semibold mb-4">Document Preview</h2>
        <div class="grid grid-cols-2 gap-4 mb-4">
            <p><strong>Patient Name:</strong> <span id="modalPatientName"></span></p>
            <p><strong>Patient ID:</strong> <span id="modalPatientId"></span></p>
            <p><strong>Uploaded By:</strong> <span id="modalUploadedBy"></span></p>
            <p><strong>Uploaded At:</strong> <span id="modalUploadedAt"></span></p>
            <p><strong>Report Name:</strong> <span id="modalReportName"></span></p>
        </div>
        <div id="previewContent" class="w-full"></div>
    </div>
</div>

<script>
    function openPreview(filePath, name, patientId, uploadedBy, uploadedAt, reportName) {
        const extension = filePath.split('.').pop().toLowerCase();
        const isImage = ['jpg', 'jpeg', 'png', 'webp'].includes(extension);
        const isPdf = extension === 'pdf';

        const preview = document.getElementById('previewContent');
        preview.innerHTML = '';

        if (isImage) {
            preview.innerHTML = `<img src="${filePath}" alt="Image Preview" class="w-full rounded-md">`;
        } else if (isPdf) {
            const encodedPath = encodeURI(filePath);
            preview.innerHTML = `<iframe src="${encodedPath}" class="w-full h-[500px]" frameborder="0"></iframe>`;
        } else {
            preview.innerHTML = `<p class="text-red-500">Unsupported file format.</p>`;
        }

        document.getElementById('modalPatientName').textContent = name;
        document.getElementById('modalPatientId').textContent = patientId;
        document.getElementById('modalUploadedBy').textContent = uploadedBy;
        document.getElementById('modalUploadedAt').textContent = uploadedAt;
        document.getElementById('modalReportName').textContent = reportName;

        document.getElementById('previewModal').classList.remove('hidden');
        document.getElementById('previewModal').classList.add('flex');
    }

    function closePreview() {
        document.getElementById('previewModal').classList.remove('flex');
        document.getElementById('previewModal').classList.add('hidden');
    }
</script>

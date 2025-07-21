<?php
include "db.php";

// Fetch patient list
$sql = "SELECT id, name FROM patient ORDER BY id DESC";
$result = $conn->query($sql);
?>

<div class="p-6">
    <h2 class="text-2xl font-semibold text-gray-800 mb-4">Patient Documents</h2>

    <div class="bg-white rounded-xl shadow-xl border p-4 overflow-x-auto">
        <table class="min-w-full text-sm text-left">
            <thead class="bg-gray-200">
                <tr>
                    <th class="px-4 py-2">Patient ID</th>
                    <th class="px-4 py-2">Patient Name</th>
                    <th class="px-4 py-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr class="border-b">
                            <td class="px-4 py-2"><?= $row['id'] ?></td>
                            <td class="px-4 py-2"><?= htmlspecialchars($row['name']) ?></td>
                            <td class="px-4 py-2 flex gap-2">
                                <a href="upload_document.php?id=<?= $row['id'] ?>" class="bg-green-600 text-white px-3 py-1 rounded hover:bg-green-700">Add</a>
                                <a href="view_documents.php?id=<?= $row['id'] ?>" class="bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700">View</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="3" class="text-center py-4">No patients found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

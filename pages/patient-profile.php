<?php
include "db.php";

$id = $_GET["id"] ?? null;
$row = null;

if ($id && is_numeric($id)) {
    $stmt = $conn->prepare("SELECT * FROM patient WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
    } else {
        echo "<p class='text-red-600 font-semibold'>Patient not found.</p>";
    }

    $stmt->close();
    $conn->close();
}
?>

<?php if ($row): ?>
<div class="w-full bg-white rounded-xl p-6 md:p-8 shadow-lg">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <a href="admin-panel.php?page=patients"
           class="bg-gray-200 hover:bg-gray-300 text-gray-800 text-sm font-semibold px-4 py-2 rounded shadow">
            ← Back
        </a>
        <a href="edit-patient.php?id=<?= $row['id'] ?>" 
           class="bg-blue-500 hover:bg-blue-600 text-white text-sm font-semibold px-4 py-2 rounded shadow">
            ✎ Edit
        </a>
    </div>

    <!-- Profile Section -->
    <div class="flex flex-col md:flex-row gap-8 w-full rounded-xl border border-gray-300 p-6">
        <!-- Profile Image -->
        <div class="w-40 h-40 flex items-center justify-center rounded-full overflow-hidden bg-gray-100 shadow">
            <img class="h-24 w-24 object-cover" src="https://cdn-icons-png.flaticon.com/512/9131/9131529.png" alt="Profile">
        </div>

        <!-- Patient Details -->
        <div class="flex-1 grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Contact Section -->
            <div>
                <h2 class="font-bold text-xl mb-4">Contact Details</h2>
                <p><strong>Name:</strong> <?= htmlspecialchars($row["name"] ?? 'N/A') ?></p>
                <p><strong>Contact:</strong> <?= htmlspecialchars($row["contact"] ?? 'N/A') ?></p>
                <p><strong>Email:</strong> <?= htmlspecialchars($row["email"] ?? 'N/A') ?></p>
                <p><strong>Weight:</strong> <?= htmlspecialchars($row["weight"] ?? 'N/A') ?></p>
            </div>

            <!-- Overview Section -->
            <div>
                <h2 class="font-bold text-xl mb-4">Overview</h2>
                <p><strong>Gender:</strong> <?= htmlspecialchars($row["gender"] ?? 'N/A') ?></p>
                <p><strong>Date of Birth:</strong> <?= htmlspecialchars($row["dob"] ?? 'N/A') ?></p>
                <p><strong>Doctor:</strong> <?= htmlspecialchars($row["doctor"] ?? 'N/A') ?></p>
                <p><strong>Last Visit:</strong> <?= htmlspecialchars($row["last_visit"] ?? 'N/A') ?></p>
                <p><strong>Next Visit:</strong> <?= htmlspecialchars($row["next_visit"] ?? 'N/A') ?></p>
                <p><strong>Disease:</strong> <?= htmlspecialchars($row["disease"] ?? 'N/A') ?></p>
            </div>
        </div>
    </div>

    <!-- Charts Section (placeholders) -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-8">
        <div class="bg-white border rounded-xl shadow-md p-6 text-center">
            <p class="font-semibold text-gray-700">Health Score Chart</p>
        </div>
        <div class="bg-white border rounded-xl shadow-md p-6 text-center">
            <p class="font-semibold text-gray-700">Vital Stats</p>
        </div>
        <div class="bg-white border rounded-xl shadow-md p-6 text-center">
            <p class="font-semibold text-gray-700">Summary</p>
        </div>
    </div>
</div>
<?php endif; ?>

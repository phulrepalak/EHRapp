<?php
include 'db.php';

$patient_id = $_GET['id'] ?? '';
$success = '';
$error = '';
$row = [];

// Fetch doctor list
$doctors = [];
$docResult = $conn->query("SELECT name FROM doctors ORDER BY name");
while ($docRow = $docResult->fetch_assoc()) {
    $doctors[] = $docRow['name'];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save'])) {
    $conn->begin_transaction();

    try {
        // Update patient table
        $stmt1 = $conn->prepare("UPDATE patient SET name = ?, contact = ?, age = ?, gender = ? WHERE id = ?");
        $stmt1->bind_param("ssisi", $_POST['name'], $_POST['contact'], $_POST['age'], $_POST['gender'], $_POST['patient_id']);
        $stmt1->execute();

        // Update appointment table
        $stmt2 = $conn->prepare("UPDATE appointment SET email = ?, dob = ?, weight = ?, diseases = ?, doctor = ?, appointment_date = ?, appointment_time = ?, next_visit = ? WHERE patient_id = ?");
        $stmt2->bind_param("ssssssssi", $_POST['email'], $_POST['dob'], $_POST['weight'], $_POST['diseases'], $_POST['doctor'], $_POST['appointment_date'], $_POST['appointment_time'], $_POST['next_visit'], $_POST['patient_id']);
        $stmt2->execute();

        $conn->commit();
        $success = "Profile updated successfully!";
    } catch (Exception $e) {
        $conn->rollback();
        $error = "Update failed: " . $e->getMessage();
    }
}

if ($patient_id && is_numeric($patient_id)) {
    $stmt = $conn->prepare("SELECT 
        p.id, p.name, p.contact, p.age, p.gender, 
        a.dob, a.email, a.doctor, a.diseases, a.weight, 
        a.appointment_date, a.appointment_time, a.next_visit
        FROM patient p
        LEFT JOIN appointment a ON p.id = a.patient_id
        WHERE p.id = ?
        ORDER BY a.id DESC LIMIT 1");

    $stmt->bind_param("i", $patient_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    // Fetch documents for patient
    $docStmt = $conn->prepare("SELECT * FROM document WHERE patient_id = ? ORDER BY uploaded_at DESC");
    $docStmt->bind_param("i", $patient_id);
    $docStmt->execute();
    $documents = $docStmt->get_result();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>My Page</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<div class="max-w-4xl mx-auto p-6 bg-white rounded-2xl shadow-xl mt-10">
    <?php if ($success): ?>
        <p class="text-green-600 font-medium mb-4"><?= $success ?></p>
    <?php elseif ($error): ?>
        <p class="text-red-600 font-medium mb-4"><?= $error ?></p>
    <?php endif; ?>

    <!-- Back Button
    <div class="text-left mb-4">
      <a href="admin-panel.php?page=patients" class="inline-block bg-gray-200 text-gray-700 px-4 py-2 rounded hover:bg-gray-300">
        ← Back
      </a>
    </div> -->

    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Patient Profile</h2>
        <div class="flex gap-3">
            <a href="admin-panel.php?page=appointment-detail&id=<?= urlencode($row['id']) ?>"
                class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">New Appointment</a>
        </div>
    </div>

    <form method="POST" id="profile-form" class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <input type="hidden" name="patient_id" value="<?= $row['id'] ?>">
        <div>
            <label class="text-gray-600 font-medium">Name</label>
            <input type="text" name="name" readonly value="<?= htmlspecialchars($row['name']) ?>"
                class="w-full px-3 py-2 border rounded bg-gray-100">
        </div>

        <div>
            <label class="text-gray-600 font-medium">Contact</label>
            <input type="text" name="contact" readonly value="<?= htmlspecialchars($row['contact']) ?>"
                class="w-full px-3 py-2 border rounded bg-gray-100">
        </div>

        <div>
            <label class="text-gray-600 font-medium">Email</label>
            <input type="email" name="email" readonly value="<?= htmlspecialchars($row['email']) ?>"
                class="w-full px-3 py-2 border rounded bg-gray-100">
        </div>

        <div class="col-span-1">
            <label for="gender" class="block font-semibold mb-1">Gender</label>
            <select name="gender" id="gender" class="w-full px-4 py-2 border border-gray-300 rounded bg-gray-100"
                disabled>
                <option value="Male" <?= $row['gender'] == 'Male' ? 'selected' : '' ?>>Male</option>
                <option value="Female" <?= $row['gender'] == 'Female' ? 'selected' : '' ?>>Female</option>
                <option value="Other" <?= $row['gender'] == 'Other' ? 'selected' : '' ?>>Other</option>
            </select>
        </div>



        <div>
            <label class="text-gray-600 font-medium">DOB</label>
            <input type="date" name="dob" readonly value="<?= htmlspecialchars($row['dob']) ?>"
                class="w-full px-3 py-2 border rounded bg-gray-100">
        </div>

        <div>
            <label class="text-gray-600 font-medium">Age</label>
            <input type="text" name="age" readonly value="<?= htmlspecialchars($row['age']) ?>"
                class="w-full px-3 py-2 border rounded bg-gray-100">
        </div>

        <div>
            <label class="text-gray-600 font-medium">Weight</label>
            <input type="text" name="weight" readonly value="<?= htmlspecialchars($row['weight']) ?>"
                class="w-full px-3 py-2 border rounded bg-gray-100">
        </div>

        <div>
            <label class="text-gray-600 font-medium">Diseases</label>
            <input type="text" name="diseases" readonly value="<?= htmlspecialchars($row['diseases']) ?>"
                class="w-full px-3 py-2 border rounded bg-gray-100">
        </div>
        <div>
            <label class="text-gray-600 font-medium">Doctor</label>
            <select name="doctor" id="doctor" class="w-full px-3 py-2 border rounded bg-gray-100" disabled>
                <option value="">-- Select Doctor --</option>
                <?php foreach ($doctors as $docName): ?>
                    <option value="<?= htmlspecialchars($docName) ?>" <?= ($row['doctor'] == $docName) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($docName) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>


        <div>
            <label class="text-gray-600 font-medium">Appointment Date</label>
            <input type="date" name="appointment_date" readonly
                value="<?= htmlspecialchars($row['appointment_date']) ?>"
                class="w-full px-3 py-2 border rounded bg-gray-100">
        </div>

        <div>
            <label class="text-gray-600 font-medium">Time</label>
            <input type="time" name="appointment_time" readonly
                value="<?= htmlspecialchars($row['appointment_time']) ?>"
                class="w-full px-3 py-2 border rounded bg-gray-100">
        </div>

        <div>
            <label class="text-gray-600 font-medium">Next Appointment</label>
            <input type="date" name="next_visit" readonly value="<?= htmlspecialchars($row['next_visit']) ?>"
                class="w-full px-3 py-2 border rounded bg-gray-100">
        </div>


        <div class="col-span-2 flex justify-end mt-4 gap-3">
          <a href="pages\upload-document.php?id=<?= urlencode($row['id']) ?>"


                class="px-4 py-2 bg-purple-600 text-white rounded hover:bg-purple-700">Upload Document</a>
            <button type="button" id="editBtn"
                class="px-4 py-2 bg-yellow-500 text-white rounded hover:bg-yellow-600">Edit</button>
            <button type="submit" name="save" id="saveBtn"
                class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 hidden">Save</button>
        </div>
    </form>

    <!-- Documents Section -->
    <h3 class="text-xl font-bold text-gray-800 mt-10 mb-4">Uploaded Documents</h3>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php while ($doc = $documents->fetch_assoc()):
            $filePath = '/' . $doc['filepath'];

            $ext = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
            $isImage = in_array($ext, ['jpg', 'jpeg', 'png', 'webp']);
            $isPdf = $ext === 'pdf';
            ?>
            <div class="bg-white rounded-xl border border-gray-200 shadow hover:shadow-md transition">
                <button onclick="openPreview('<?= $filePath ?>')" class="w-full focus:outline-none">
                    <?php if ($isImage): ?>
                        <img src="<?= $filePath ?>" alt="Document" class="w-full h-64 object-cover rounded-t-xl">
                    <?php elseif ($isPdf): ?>
                        <div class="w-full h-64 flex items-center justify-center bg-gray-100 text-lg font-medium text-gray-700">
                            PDF Document
                        </div>
                    <?php endif; ?>
                </button>
                <div class="p-4 space-y-1">
                    <p class="text-gray-800 font-semibold">📄 <?= htmlspecialchars($doc['report_name']) ?></p>
                    <p class="text-sm text-gray-600">Uploaded by: <?= htmlspecialchars($doc['uploaded_by']) ?></p>
                    <p class="text-sm text-gray-500"><?= date("d M Y, h:i A", strtotime($doc['uploaded_at'])) ?></p>
                    <form method="POST" action="/pages/delete_document.php" onsubmit="return confirm('Are you sure?')">
                        <input type="hidden" name="document_id" value="<?= $doc['id'] ?>">
                        <button type="submit" class="text-red-500 hover:underline text-sm">Delete</button>
                    </form>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</div>

<!-- Modal Preview -->
<div id="previewModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white max-w-4xl w-full rounded-xl overflow-hidden">
        <div class="flex justify-between items-center px-4 py-3 border-b">
            <h3 class="text-lg font-semibold">Document Preview</h3>
            <button onclick="closePreview()" class="text-gray-500 hover:text-red-500 text-xl">&times;</button>
        </div>
        <div class="p-4" id="previewContent"></div>
    </div>
</div>

<script>
    const editBtn = document.getElementById('editBtn');
    const saveBtn = document.getElementById('saveBtn');
    const inputs = document.querySelectorAll('#profile-form input, #profile-form select');

    editBtn.addEventListener('click', () => {
        inputs.forEach(input => {
            if (input.name && input.type !== "hidden") {
                input.readOnly = false;
                input.disabled = false;
                input.classList.remove('bg-gray-100');
                input.classList.add('bg-white');
            }
        });
        editBtn.classList.add('hidden');
        saveBtn.classList.remove('hidden');
    });

    function openPreview(path) {
        const modal = document.getElementById('previewModal');
        const content = document.getElementById('previewContent');
        const ext = path.split('.').pop().toLowerCase();
        content.innerHTML = '';
        if (['jpg', 'jpeg', 'png', 'webp'].includes(ext)) {
            content.innerHTML = `<img src="${path}" class="w-full rounded" />`;
        } else if (ext === 'pdf') {
            content.innerHTML = `<iframe src="${path}" class="w-full h-[500px]" frameborder="0"></iframe>`;
        } else {
            content.innerHTML = `<p class='text-red-500'>Unsupported format.</p>`;
        }
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closePreview() {
        const modal = document.getElementById('previewModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
</script>
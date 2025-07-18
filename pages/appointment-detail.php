<?php
include 'db.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

$patient_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST" && $patient_id > 0) {
    $name    = trim($_POST['name']);
    $email   = trim($_POST['email']);
    $contact = trim($_POST['contact']);
    $doctor  = trim($_POST['doctor']);
    $date    = $_POST['date'];
    $time    = $_POST['time'];

    if ($name && $email && $doctor && $date && $time) {
        // Update patient table
        $stmt1 = $conn->prepare("UPDATE patient SET name=?, contact=? WHERE id=?");
        $stmt1->bind_param("ssi", $name, $contact, $patient_id);
        $stmt1->execute();

        // Check if appointment exists
        $check = $conn->prepare("SELECT id FROM appointment WHERE patient_id=?");
        $check->bind_param("i", $patient_id);
        $check->execute();
        $check_result = $check->get_result();

        if ($check_result->num_rows > 0) {
            // Update existing appointment
            $stmt2 = $conn->prepare("UPDATE appointment SET email=?, doctor_name=?, date=?, time=? WHERE patient_id=?");
            $stmt2->bind_param("ssssi", $email, $doctor, $date, $time, $patient_id);
            if ($stmt2->execute()) {
                $message = "Appointment updated successfully.";
            } else {
                $message = "Failed to update appointment.";
            }
        } else {
            // Insert new appointment
            $stmt3 = $conn->prepare("INSERT INTO appointment (patient_id, email, doctor_name, date, time) VALUES (?, ?, ?, ?, ?)");
            $stmt3->bind_param("issss", $patient_id, $email, $doctor, $date, $time);
            if ($stmt3->execute()) {
                $message = "Appointment saved successfully.";
            } else {
                $message = "Failed to save appointment.";   
            }
        }
    } else {
        $message = "All fields are required.";
    }
}

// Fetch info to display
$data = [];
if ($patient_id > 0) {
    $stmt = $conn->prepare("SELECT p.name, p.contact, a.email, a.doctor_name, a.date, a.time
                            FROM patient p
                            LEFT JOIN appointment a ON p.id = a.patient_id
                            WHERE p.id = ?");
    $stmt->bind_param("i", $patient_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();
}
?>



<!-- HTML + Tailwind UI -->
<div class="w-full h-full border rounded-xl p-4 bg-white">
    <div class="w-full h-auto mx-auto border rounded-xl p-6 bg-white shadow-md">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-semibold text-gray-800">Appointment Detail</h3>
            <button id="editBtn" type="button" class="cursor-pointer bg-blue-500 text-white px-4 py-1 rounded-md text-sm">
                Edit
            </button>
        </div>

        <?php if ($message): ?>
            <div class="mb-4 text-sm text-green-600 font-semibold">
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>

        <div class="h-px bg-gray-300 mb-4"></div>

        <form id="profileForm" method="POST" class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="flex flex-col">
                <label class="font-semibold mb-1">Name</label>
                <input name="name" value="<?= htmlspecialchars($data['name'] ?? '') ?>" type="text" readonly class="read-only:bg-gray-100 border border-gray-300 rounded px-3 py-1 text-sm" />
            </div>

            <div class="flex flex-col">
                <label class="font-semibold mb-1">Doctor</label>
                <input name="doctor" value="<?= htmlspecialchars($data['doctor_name'] ?? '') ?>" type="text" readonly class="read-only:bg-gray-100 border border-gray-300 rounded px-3 py-1 text-sm" />
            </div>

            <div class="flex flex-col">
                <label class="font-semibold mb-1">Date</label>
                <input name="date" value="<?= htmlspecialchars($data['date'] ?? '') ?>" type="date" readonly class="read-only:bg-gray-100 border border-gray-300 rounded px-3 py-1 text-sm" />
            </div>

            <div class="flex flex-col">
                <label class="font-semibold mb-1">Email</label>
                <input name="email" value="<?= htmlspecialchars($data['email'] ?? '') ?>" type="email" readonly class="read-only:bg-gray-100 border border-gray-300 rounded px-3 py-1 text-sm" />
            </div>

            <div class="flex flex-col">
                <label class="font-semibold mb-1">Contact</label>
                <input name="contact" value="<?= htmlspecialchars($data['contact'] ?? '') ?>" type="tel" readonly class="read-only:bg-gray-100 border border-gray-300 rounded px-3 py-1 text-sm" />
            </div>

            <div class="flex flex-col">
                <label class="font-semibold mb-1">Time</label>
                <input name="time" value="<?= htmlspecialchars($data['time'] ?? '') ?>" type="time" readonly class="read-only:bg-gray-100 border border-gray-300 rounded px-3 py-1 text-sm" />
            </div>

            <button type="submit" id="saveBtn" class="hidden col-span-1 md:col-span-3 mt-2 bg-green-600 text-white px-4 py-2 rounded">
                Save Changes
            </button>
        </form>
    </div>
</div>

<script>
    const editBtn = document.getElementById('editBtn');
    const saveBtn = document.getElementById('saveBtn');
    const inputs = document.querySelectorAll('#profileForm input');

    let isEditing = false;

    editBtn.addEventListener('click', () => {
        isEditing = !isEditing;

        inputs.forEach(input => {
            input.readOnly = !isEditing;
            input.classList.toggle('read-only:bg-gray-100', !isEditing);
            input.classList.toggle('border-blue-500', isEditing);
        });

        if (isEditing) {
            editBtn.textContent = 'Cancel';
            editBtn.classList.replace('bg-blue-500', 'bg-red-500');
            saveBtn.classList.remove('hidden');
        } else {
            editBtn.textContent = 'Edit';
            editBtn.classList.replace('bg-red-500', 'bg-blue-500');
            saveBtn.classList.add('hidden');
        }
    });
</script>

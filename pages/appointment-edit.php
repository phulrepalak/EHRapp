<?php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "careyugehrdb";

$conn = new mysqli($host, $user, $pass, $dbname,);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$patient_id = $_GET['id'] ?? '';
$success = '';
$error = '';
$row = [];

if ($patient_id && is_numeric($patient_id)) {
    $stmt = $conn->prepare("SELECT * FROM appointment WHERE patient_id = ?");
    $stmt->bind_param("i", $patient_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save'])) {
    $stmt = $conn->prepare("UPDATE appointment SET appointment_date = ?, appointment_time = ?, doctor = ?, diseases = ?, weight = ?, next_visit = ? WHERE patient_id = ?");
    $stmt->bind_param(
        "ssssssi",
        $_POST['appointment_date'],
        $_POST['appointment_time'],
        $_POST['doctor'],
        $_POST['diseases'],
        $_POST['weight'],
        $_POST['next_visit'],
        $patient_id
    );
    if ($stmt->execute()) {
        $success = "Appointment updated successfully!";
    } else {
        $error = "Error updating: " . $conn->error;
    }
    $stmt->close();

    // Refresh data
    $stmt = $conn->prepare("SELECT * FROM appointment WHERE patient_id = ?");
    $stmt->bind_param("i", $patient_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Appointment Details</title>
    <script>
        function enableEdit() {
            document.querySelectorAll("input, select, textarea").forEach(el => el.removeAttribute("readonly"));
            document.getElementById("saveBtn").classList.remove("hidden");
        }
    </script>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 py-10">
    

<div class="max-w-3xl mx-auto bg-white p-8 shadow-md rounded-lg">
    <h2 class="text-2xl font-bold mb-6 text-center text-blue-700"> Appointment Details</h2>

    <?php if ($success): ?>
        <p class="mb-4 p-3 text-green-700 bg-green-100 border border-green-400 rounded"><?= $success ?></p>
    <?php endif; ?>
    <?php if ($error): ?>
        <p class="mb-4 p-3 text-red-700 bg-red-100 border border-red-400 rounded"><?= $error ?></p>
    <?php endif; ?>

    <form method="POST">
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block font-semibold">Appointment Date</label>
                <input type="date" name="appointment_date" value="<?= htmlspecialchars($row['appointment_date'] ?? '') ?>" readonly class="w-full px-3 py-2 border rounded" />
            </div>
            <div>
                <label class="block font-semibold">Appointment Time</label>
                <input type="time" name="appointment_time" value="<?= htmlspecialchars($row['appointment_time'] ?? '') ?>" readonly class="w-full px-3 py-2 border rounded" />
            </div>
            <div>
                <label class="block font-semibold">Doctor</label>
                <input type="text" name="doctor" value="<?= htmlspecialchars($row['doctor'] ?? '') ?>" readonly class="w-full px-3 py-2 border rounded" />
            </div>
            <div>
                <label class="block font-semibold">Diseases</label>
                <input type="text" name="diseases" value="<?= htmlspecialchars($row['diseases'] ?? '') ?>" readonly class="w-full px-3 py-2 border rounded" />
            </div>
            <div>
                <label class="block font-semibold">Next Visit</label>
                <input type="date" name="next_visit" value="<?= htmlspecialchars($row['next_visit'] ?? '') ?>" readonly class="w-full px-3 py-2 border rounded" />
            </div>
            <div>
                <label class="block font-semibold">Weight (kg)</label>
                <input type="text" name="weight" value="<?= htmlspecialchars($row['weight'] ?? '') ?>" readonly class="w-full px-3 py-2 border rounded" />
            </div>
        </div>

       <div class="mt-6 flex justify-end gap-4">
    <button id="editBtn" type="button" onclick="enableEdit()" class="px-4 py-2 bg-yellow-500 text-white rounded hover:bg-yellow-600">
        Edit
    </button>
    <button id="saveBtn" name="save" type="submit" class="hidden px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
        Save
    </button>
</div>
    </form>
</div>
<script>
    function enableEdit() {
        // Hide Edit button
        document.getElementById("editBtn").classList.add("hidden");

        // Show Save button
        document.getElementById("saveBtn").classList.remove("hidden");

        // Enable all input fields (if you have disabled inputs)
        const inputs = document.querySelectorAll("input, select, textarea");
       inputs.forEach(input => input.removeAttribute("readonly"));

    }
</script>
</body>
</html>

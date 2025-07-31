<?php
include 'db.php';
$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $patient_id = $_POST['patient_id'] ?? '';
  $name= $_POST['name'] ?? '';
  $dob = $_POST['dob'] ?? '';
  $email = $_POST['email'] ?? '';
  $diseases = $_POST['diseases'] ?? '';
  $weight = $_POST['weight'] ?? '';
  $next_visit = $_POST['next_visit'] ?? '';
  $doctor = $_POST['doctor'] ?? '';
  $appointment_date = $_POST['appointment_date'] ?? '';
  $appointment_time = $_POST['appointment_time'] ?? '';

  if (empty($patient_id) || empty($appointment_date) || empty($appointment_time)) {
    $error = "Patient and appointment date/time are required.";
  } else {
    $stmt = $conn->prepare("INSERT INTO appointment 
      (patient_id,name, dob, email, diseases, weight, next_visit, doctor, appointment_date, appointment_time) 
      VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    $stmt->bind_param("issssissss", $patient_id,$name, $dob, $email, $diseases, $weight, $next_visit, $doctor, $appointment_date, $appointment_time);

    if ($stmt->execute()) {
      $success = "Appointment booked successfully!";
    } else {
      $error = "Failed to book appointment. Error: " . $stmt->error;
    }
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Book Appointment</title>
  <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100 p-6">
  <div class="max-w-3xl mx-auto bg-white p-6 rounded shadow">

    <!-- Back Button -->
    <div class="text-left mb-4">
      <a href="admin-panel.php?page=appointments" class="inline-block bg-gray-200 text-gray-700 px-4 py-2 rounded hover:bg-gray-300">
        ← Back
      </a>
    </div>

    <h2 class="text-2xl font-bold mb-4">Book Appointment</h2>

    <?php if ($success): ?>
      <div class="bg-green-100 text-green-800 p-3 rounded mb-4"><?= $success ?></div>
    <?php elseif ($error): ?>
      <div class="bg-red-100 text-red-800 p-3 rounded mb-4"><?= $error ?></div>
    <?php endif; ?>

    <!-- Form -->
    <form method="POST" class="grid grid-cols-2 gap-4">
      <!-- Search -->
      <div class="col-span-2">
        <label class="block mb-2 font-medium">Search Patient</label>
        <input type="text" id="patient_name" placeholder="Search by name/contact/id" class="border px-4 py-2 rounded w-full mb-2" />
      </div>

      <!-- Hidden field (inside form!) -->
      <input type="hidden" name="patient_id" id="patient_id">

      <!-- Patient Info -->
      <input type="text" id="name_display" class="w-full bg-gray-100 px-3 py-2 rounded" disabled>
<input type="hidden" id="name" name="name">

      <div>
        <label class="block font-semibold">Contact</label>
        <input type="text" id="contact" class="w-full bg-gray-100 px-3 py-2 rounded" disabled>
      </div>
      <div>
        <label class="block font-semibold">Gender</label>
        <input type="text" id="gender" class="w-full bg-gray-100 px-3 py-2 rounded" disabled>
      </div>
      <div>
        <label class="block font-semibold">Age</label>
        <input type="text" id="age" class="w-full bg-gray-100 px-3 py-2 rounded" disabled>
      </div>

      <!-- Input fields -->
      <div>
        <label class="block font-semibold">Date of Birth</label>
        <input type="date" id="dob" name="dob" class="w-full px-3 py-2 border rounded">
      </div>
      <div>
        <label class="block font-semibold">Email</label>
        <input type="email" id="email" name="email" class="w-full px-3 py-2 border rounded">
      </div>
      <div>
        <label class="block font-semibold">Diseases</label>
        <input type="text" name="diseases" class="w-full px-3 py-2 border rounded">
      </div>
      <div>
        <label class="block font-semibold">Doctor</label>
        <select name="doctor" id="doctor" class="w-full px-3 py-2 border rounded" required>
          <option value="">-- Select Doctor --</option>
          <option value="Dr. Mehta">Dr. Mehta</option>
          <option value="Dr. Sharma">Dr. Sharma</option>
          <option value="Dr. Verma">Dr. Verma</option>
          <option value="Dr. Reddy">Dr. Reddy</option>
          <option value="Dr. Iyer">Dr. Iyer</option>
        </select>
      </div>
      <div>
        <label class="block font-semibold">Appointment Date</label>
        <input type="date" name="appointment_date" class="w-full px-3 py-2 border rounded" required>
      </div>
      <div>
        <label class="block font-semibold">Appointment Time</label>
        <input type="time" name="appointment_time" class="w-full px-3 py-2 border rounded" required>
      </div>
      <div>
        <label class="block font-semibold">Weight (kg)</label>
        <input type="number" name="weight" class="w-full px-3 py-2 border rounded">
      </div>
      <div>
        <label class="block font-semibold">Next Visit</label>
        <input type="date" name="next_visit" class="w-full px-3 py-2 border rounded">
      </div>

      <!-- Submit -->
      <div class="col-span-2 text-right mt-4">
        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded shadow">
          Book Appointment
        </button>
      </div>
    </form>
  </div>

  <script>
    $(document).ready(function () {
      $("#patient_name").autocomplete({
        source: "/pages/patient-autosuggest.php",
        minLength: 2,
        select: function (event, ui) {
          $("#patient_id").val(ui.item.id);
          $.get('/pages/fetch-patient.php?id=' + ui.item.id, function (data) {
  const p = JSON.parse(data);
  $('#name_display').val(p.name);     // for user view
  $('#name').val(p.name);             // for form submission
  $('#contact').val(p.contact);
  $('#gender').val(p.gender);
  $('#age').val(p.age);
});

        }
      });
    });
  </script>
</body>
</html>

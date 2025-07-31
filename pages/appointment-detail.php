<?php
include 'db.php';
$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $patient_id = $_POST['patient_id'];
  $dob = $_POST['dob'];
  $email = $_POST['email'];
  $diseases = $_POST['diseases'];
  $weight = $_POST['weight'];
  $next_visit = $_POST['next_visit'];
  $doctor = $_POST['doctor'];

  $stmt = $conn->prepare("INSERT INTO appointment (patient_id, dob, email, diseases, weight, next_visit, doctor)
                            VALUES (?, ?, ?, ?, ?, ?, ?)");
  $stmt->bind_param("isssiss", $patient_id, $dob, $email, $diseases, $weight, $next_visit, $doctor);

  if ($stmt->execute()) {
    $success = " Appointment booked successfully!";
  } else {
    $error = " Failed to book appointment.";
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <title>Book Appointment</title>
  <!-- jQuery UI CSS -->
  <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">

  <!-- jQuery + jQuery UI JS -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100 p-6">
  <div class="max-w-2xl mx-auto bg-white p-6 rounded shadow">
    <h2 class="text-2xl font-bold mb-4"> Book Appointment</h2>

    <?php if ($success): ?>
      <div class="bg-green-100 text-green-800 p-3 rounded mb-4"><?= $success ?></div>
    <?php elseif ($error): ?>
      <div class="bg-red-100 text-red-800 p-3 rounded mb-4"><?= $error ?></div>
    <?php endif; ?>

    <label class="block mb-2 font-medium">Search Patient</label>
    <!-- Input Field -->
    <input type="text" id="patient_name" placeholder="Search by name/contact/id"
      class="border px-4 py-2 rounded w-full" />
    <input type="hidden" id="patient_id">
   
    <form method="POST" class="grid grid-cols-2 gap-4 mt-4">
      <input type="hidden" name="patient_id" id="patient_id">

      <!-- Name & Contact -->
      <div>
        <label class="block font-semibold text-gray-700 mb-1"> Name</label>
        <input type="text" id="name" class="w-full bg-gray-100 px-3 py-2 rounded" disabled>
      </div>
      <div>
        <label class="block font-semibold text-gray-700 mb-1"> Contact</label>
        <input type="text" id="contact" class="w-full bg-gray-100 px-3 py-2 rounded" disabled>
      </div>

      <!-- Gender & Age -->
      <div>
        <label class="block font-semibold text-gray-700 mb-1"> Gender</label>
        <input type="text" id="gender" class="w-full bg-gray-100 px-3 py-2 rounded" disabled>
      </div>
      <div>
        <label class="block font-semibold text-gray-700 mb-1"> Age</label>
        <input type="text" id="age" class="w-full bg-gray-100 px-3 py-2 rounded" disabled>
      </div>

      <!-- DOB & Email -->
      <div>
        <label class="block font-semibold text-gray-700 mb-1"> Date of Birth</label>
        <input type="date" id="dob" name="dob" class="w-full px-3 py-2 border rounded">
      </div>
      <div>
        <label class="block font-semibold text-gray-700 mb-1"> Email</label>
        <input type="email" id="email" name="email" class="w-full px-3 py-2 border rounded">
      </div>
      <!-- Diseases & Doctor -->
      <div>
        <label class="block font-semibold text-gray-700 mb-1"> Diseases</label>
        <input type="text" name="diseases" class="w-full px-3 py-2 border rounded">
      </div>
      <div>
        <label class="block font-semibold text-gray-700 mb-1">Select Doctor</label>
        <select name="doctor" id="doctor" class="w-full px-3 py-2 border rounded" required>
          <option value="">-- Select Doctor --</option>
          <option value="Dr. Mehta">Dr. Mehta</option>
          <option value="Dr. Sharma">Dr. Sharma</option>
          <option value="Dr. Verma">Dr. Verma</option>
          <option value="Dr. Reddy">Dr. Reddy</option>
          <option value="Dr. Iyer">Dr. Iyer</option>
        </select>
      </div>

      <!-- Appointment Date & Time -->
      <div>
        <label class="block font-semibold text-gray-700 mb-1">Appointment Date</label>
        <input type="date" name="appointment_date" class="w-full px-3 py-2 border rounded" required>
      </div>
      <div>
        <label class="block font-semibold text-gray-700 mb-1">Appointment Time</label>
        <input type="time" name="appointment_time" class="w-full px-3 py-2 border rounded" required>
      </div>
      <!-- Next Visit & Weight -->
      <div>
        <label class="block font-semibold text-gray-700 mb-1"> Weight (kg)</label>
        <input type="number" name="weight" class="w-full px-3 py-2 border rounded">
      </div>
      <div>
        <label class="block font-semibold text-gray-700 mb-1">Next Visit</label>
        <input type="date" name="next_visit" class="w-full px-3 py-2 border rounded">
      </div>

  <!-- Submit Button -->
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
        source: "patient-autosuggest.php",
        minLength: 2,
        select: function (event, ui) {
          $("#patient_id").val(ui.item.id);
          $("#contact").val(ui.item.contact);
        }
      });
    });

    $(document).on('click', '#suggestions li', function () {
      const id = $(this).data('id');
      $('#patient_id').val(id);
      $('#suggestions').empty().addClass('hidden');

      $.get('fetch-patient.php?id=' + id, function (data) {
        const p = JSON.parse(data);
        $('#name').val(p.name);
        $('#contact').val(p.contact);
        $('#gender').val(p.gender);
        $('#age').val(p.age);
      });

      $('#searchInput').val($(this).text());
    });

    $(document).click(function (e) {
      if (!$(e.target).closest('#searchInput, #suggestions').length) {
        $('#suggestions').addClass('hidden');
      }
    });

  </script>
</body>

</html>
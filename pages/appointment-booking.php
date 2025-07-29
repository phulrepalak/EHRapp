<?php
include 'db.php';

$success = "";
$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $patient_id = $_POST['patient_id'];
    $dob = $_POST['dob'];
    $email = $_POST['email'];
    $diseases = $_POST['diseases'];
    $weight = $_POST['weight'];
    $next_visit = $_POST['next_visit'];
    $doctor = $_POST['doctor'] ?? '';
    $appointment_date = $_POST['appointment_date'];
    $appointment_time = $_POST['appointment_time'];

    $stmt = $conn->prepare("INSERT INTO appointment 
        (patient_id, dob, email, diseases, weight, next_visit, doctor, appointment_date, appointment_time) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        
    $stmt->bind_param("issssssss", $patient_id, $dob, $email, $diseases, $weight, $next_visit, $doctor, $appointment_date, $appointment_time);

    if ($stmt->execute()) {
        $success = "Appointment booked successfully!";
    } else {
        $error = "Failed to book appointment: " . $stmt->error;
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Book Appointment</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  <script>
  function fetchPatientData(query) {
    if (query.trim().length === 0) return;

    // Determine if input is numeric (assume ID) or text (name/contact)
    const isID = /^\d+$/.test(query);

    fetch('pages/fetch_patient.php?' + (isID ? 'id=' : 'query=') + encodeURIComponent(query))
      .then(response => response.json())
      .then(data => {
        if (data && data.name) {
          document.getElementById('name').value = data.name;
          document.getElementById('contact').value = data.contact;
          document.getElementById('gender').value = data.gender;
          document.getElementById('age').value = data.age;
          document.getElementById('dob').value = data.dob;
          document.getElementById('email').value = data.email;
          document.getElementById('patient_id').value = data.id;
        } else {
          // clear fields if no result
          document.getElementById('name').value = '';
          document.getElementById('contact').value = '';
          document.getElementById('gender').value = '';
          document.getElementById('age').value = '';
          document.getElementById('dob').value = '';
          document.getElementById('email').value = '';
          document.getElementById('patient_id').value = '';
        }
      });
  }
   // automatically hide the message after a few seconds (success appointment)
  setTimeout(() => {
    const alerts = document.querySelectorAll('[role="alert"]');
    alerts.forEach(alert => alert.style.display = 'none');
  }, 4000); // hides after 4 seconds
</script>

  </script>
</head>
<body class="bg-gray-100 py-10">
<div class="max-w-3xl mx-auto bg-white p-8 shadow rounded">
  <h2 class="text-3xl font-bold text-center mb-4">🗓️ Book Appointment</h2>
  
 <?php if ($success): ?>
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4 text-center" role="alert">
        <strong class="font-bold">Success!</strong>
        <span class="block sm:inline"><?= htmlspecialchars($success) ?></span>
    </div>
<?php elseif ($error): ?>
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4 text-center" role="alert">
        <strong class="font-bold">Error!</strong>
        <span class="block sm:inline"><?= htmlspecialchars($error) ?></span>
    </div>
<?php endif; ?>


  <label class="block text-center text-lg font-semibold mb-2">🔍 Search Patient</label>
  <input type="text" onkeyup="fetchPatientData(this.value)" placeholder="Search by ID / name / contact"
       class="w-full px-4 py-2 border border-black rounded mb-6">


  <form method="POST" class="grid grid-cols-2 gap-6 p-4 bg-white shadow-md rounded-lg">

  <!-- Hidden Patient ID -->
  <input type="hidden" name="patient_id" id="patient_id">

  <!-- Name & Contact -->
  <div>
    <label class="block font-semibold text-gray-700 mb-1">👤 Name</label>
    <input type="text" id="name" class="w-full bg-gray-100 px-3 py-2 rounded" disabled>
  </div>
  <div>
    <label class="block font-semibold text-pink-600 mb-1">📞 Contact</label>
    <input type="text" id="contact" class="w-full bg-gray-100 px-3 py-2 rounded" disabled>
  </div>

  <!-- Gender & Age -->
  <div>
    <label class="block font-semibold text-gray-700 mb-1">♂ Gender</label>
    <input type="text" id="gender" class="w-full bg-gray-100 px-3 py-2 rounded" disabled>
  </div>
  <div>
    <label class="block font-semibold text-gray-700 mb-1">🎂 Age</label>
    <input type="text" id="age" class="w-full bg-gray-100 px-3 py-2 rounded" disabled>
  </div>

  <!-- DOB & Email -->
  <div>
    <label class="block font-semibold text-gray-700 mb-1">🎈 Date of Birth</label>
    <input type="date" id="dob" name="dob" class="w-full px-3 py-2 border rounded">
  </div>
  <div>
    <label class="block font-semibold text-gray-700 mb-1">📧 Email</label>
    <input type="email" id="email" name="email" class="w-full px-3 py-2 border rounded">
  </div>

  <!-- Diseases & Doctor -->
  <div>
    <label class="block font-semibold text-gray-700 mb-1">⚕️ Diseases</label>
    <input type="text" name="diseases" class="w-full px-3 py-2 border rounded">
  </div>
  <div>
    <label class="block font-semibold text-gray-700 mb-1">👨‍⚕️ Select Doctor</label>
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
    <label class="block font-semibold text-gray-700 mb-1">🗓️ Appointment Date</label>
    <input type="date" name="appointment_date" class="w-full px-3 py-2 border rounded" required>
  </div>
  <div>
    <label class="block font-semibold text-gray-700 mb-1">⏰ Appointment Time</label>
    <input type="time" name="appointment_time" class="w-full px-3 py-2 border rounded" required>
  </div>

  <!-- Next Visit & Weight -->
  <div>
    <label class="block font-semibold text-gray-700 mb-1">📅 Next Visit</label>
    <input type="date" name="next_visit" class="w-full px-3 py-2 border rounded">
  </div>
  <div>
    <label class="block font-semibold text-gray-700 mb-1">⚖️ Weight (kg)</label>
    <input type="number" name="weight" class="w-full px-3 py-2 border rounded">
  </div>


  <!-- Submit Button -->
  <div class="col-span-2 text-right mt-4">
    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded shadow">
      Book Appointment
    </button>
  </div>

</form>
</div>
</body>
</html>

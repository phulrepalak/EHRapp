<?php
include 'db.php'; // include your db connection file

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form inputs and sanitize
    $name = trim($_POST['name']);
    $age = (int)$_POST['age'];
    $phone = trim($_POST['phone']);
    $gender = trim($_POST['gender']);

    // Simple validation    
    if (!empty($name) && $age > 0 && !empty($phone) && !empty($gender)) {
        $stmt = $conn->prepare("INSERT INTO patient (name, age, contact, gender) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("siss", $name, $age, $phone, $gender);

        if ($stmt->execute()) {
            $success = "Patient added successfully.";
        } else {
            $error = "Error adding patient: " . $conn->error;
        }

        $stmt->close();
    } else {
        $error = "Please fill all fields correctly.";
    }
}
?>

<?php if (!empty($success)): ?>
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
        <strong class="font-bold">Success!</strong>
        <span class="block sm:inline"><?php echo $success; ?></span>
    </div>
<?php endif; ?>

<?php if (!empty($error)): ?>
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
        <strong class="font-bold">Error!</strong>
        <span class="block sm:inline"><?php echo $error; ?></span>
    </div>
<?php endif; ?>




<div class="max-w-full mx-auto bg-white p-6 rounded shadow-3">
    <div class="flex items-center mb-4">
        <a href="admin-panel.php?page=patients"
            class="bg-grey-200 hover:bg-gray-300 text-gray-800 text-sm font-semibold px-4 py-2 rounded shadow">
            ← Back
        </a>
    </div>
    <h2 class="text-2xl font-bold mb-4">Add New Patient</h2>
    <form method="post" id="patientForm" class="space-y-4">
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class=" text-sm font-medium ">Name</label>
                <input name="name" type="text" class="w-full border border-grey-500 placeholder-grey-500 rounded p-2" />
            </div>
            <div>
                <label class="block text-sm font-medium">age</label>
                <input name="age" type="number" class="w-full border border-grey-500 placeholder-grey-500 rounded p-2" />
            </div>
            <div>
                <label class="block text-sm font-medium">Phone Number</label>
                <input name="phone" type="tel" class="w-full border border-grey-500 placeholder-grey-500 rounded p-2" />
            </div>
            <div>
                <label class="block text-sm font-medium">Gender</label>
                <select name="gender" class="w-full border border-grey-500 placeholder-grey-500 rounded p-2">
                    <option value="">Select</option>
                    <option>Male</option>
                    <option>Female</option>
                    <option>Other</option>
                </select>
            </div>
        </div>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            Submit
        </button>
    </form>
</div>

<script>
    var patientFormInputs = document.getElementById('patientForm').getElementsByTagName("input");
    document.getElementById('patientForm').addEventListener('submit', function(e) {
        let valid = true;
        const name = this.name.value.trim();
        const age = this.age.value.trim();
        const phone = this.phone.value.trim();
        const gender = this.gender.value;


        if (!name) {
            patientFormInputs[0].placeholder = 'Please enter name';
            patientFormInputs[0].classList.remove("placeholder-grey-500", "border-grey-500");
            patientFormInputs[0].classList.add("placeholder-red-500", "border-red-500");
            valid = false;
        }

        if (!age || age < 0) {
            patientFormInputs[1].value = '';
            patientFormInputs[1].placeholder = 'Please  enter valid age';
            patientFormInputs[1].classList.remove("placeholder-grey-500", "border-grey-500");
            patientFormInputs[1].classList.add("placeholder-red-500", "border-red-500");
            valid = false;
        }

        // Phone validation
        if (phone.length != 10 && !/^\d{10}$/.test(phone)) {
            patientFormInputs[2].value = '';
            patientFormInputs[2].placeholder = 'Please  enter valid Phone Number';
            patientFormInputs[2].classList.remove("placeholder-grey-500", "border-grey-500");
            patientFormInputs[2].classList.add("placeholder-red-500", "border-red-500");
            valid = false;
        }

        if (!gender) {
            patientForm.getElementsByTagName("select")[0].classList.replace("placeholder-grey-500", "placeholder-red-500");
            patientForm.getElementsByTagName("select")[0].classList.replace("border-grey-500", "border-red-500");
            valid = false;
        }

        if (!valid) {
            e.preventDefault(); // Stops form submission
        }
    });
</script>
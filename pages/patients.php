<!-- Patients Page -->
<div>
    <h3 class="text-2xl font-semibold text-gray-800 mb-4">PATIENTS</h3>
    <div class="flex flex-col sm:flex-row justify-end gap-6">
        <a href="?page=add-patient"
            class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium transition">New Patient</a>
    </div>


    <!-- Search Bar -->
   <form method="GET" class="mb-8">
    <input type="hidden" name="page" value="patients">
    <input type="text" name="search" placeholder="Search by name or contact"
        value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>"
        class="border border-gray-300 px-4 py-2 rounded-lg w-full sm:w-1/3">
    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium">Search</button>
</form>


    <!--   -->

    <!-- Patient Table -->
    <div class="bg-white rounded-xl shadow-xl  border p-6 overflow-x-auto ">
        <table id="patientTable" class="min-w-full table-auto text-left text-sm">
            <thead class="bg-gray-200">
                <tr>
                    <th class="px-4 py-2">ID</th>
                    <th class="px-4 py-2">Name</th>
                    <th class="px-4 py-2">Age</th>
                    <th class="px-4 py-2">Gender</th>
                    <th class="px-4 py-2">Contact</th>
                </tr>
            </thead>
            <tbody>

         <?php
$conn = new mysqli('localhost', 'root', '', 'careyugehrdb');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';
$sql = "SELECT * FROM patient";


if (!empty($search)) {
    $sql .= " WHERE name LIKE '%$search%' OR contact LIKE '%$search%'";
}

$sql .= " ORDER BY id DESC";

$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<tr class='border-b'>
                  <td class='px-4 py-2'>{$row['id']}</td>
                  <td class='px-4 py-2'><a class='block' href='?page=patient-profile&id={$row['id']}'>{$row['name']}</a></td>
                  <td class='px-4 py-2'>{$row['age']}</td>
                  <td class='px-4 py-2'>{$row['gender']}</td>
                  <td class='px-4 py-2'>{$row['contact']}</td>
              </tr>";
    }
} else {
    echo "<tr><td colspan='5' class='text-center py-4'>No patients found.</td></tr>";
}
$conn->close();
?>

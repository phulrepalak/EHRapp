<!-- Patients Page -->
<div>
    <h3 class="text-2xl font-semibold text-gray-800 mb-4">PATIENTS</h3>
    <div class="flex flex-col sm:flex-row justify-end gap-6">
        <button onclick="" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium transition">New Patient</button>
    </div>

    <!-- Search Bar -->
    <!--   -->

    <!-- Patient Table -->
    <div class="bg-white rounded-xl shadow-lg p-6 overflow-x-auto">
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

                $conn = new mysqli('localhost', 'root', '', 'careyugehrDB');
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                $result = $conn->query("SELECT * FROM patients ORDER BY id DESC");
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr class='border-b'>
            <td class='px-4 py-2'>{$row['id']}</td>
            <td class='px-4 py-2'>{$row['name']}</td>
            <td class='px-4 py-2'>{$row['age']}</td>
            <td class='px-4 py-2'>{$row['gender']}</td>
            <td class='px-4 py-2'>{$row['contact']}</td>
        </tr>";
                    }
                } else {
                    echo "<tr><td colspan='5' class='text-center py-4'>No patients found.</td></tr>";
                }
                $conn->close();
                // Display success or error messages
                if (isset($_SESSION['error'])) {
                    echo "<div class='bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4'>{$_SESSION['error']}</div>";
                    unset($_SESSION['error']);
                }
                if (isset($_SESSION['success'])) {
                    echo "<div class='bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4'>{$_SESSION['success']}</div>";
                    unset($_SESSION['success']);
                }

                ?>
            </tbody>

        </table>
    </div>
</div>

<!-- Add Patient Modal -->
<div id="addPatientModal" class="fixed inset-0 bg-black bg-opacity-40 flex justify-center items-center hidden z-50">
    <div class="bg-white rounded-xl max-w-md max-w-md p-2 relative">
        <h3 class="text-xl font-semibold mb-4">Add New Patient</h3>
        <form action="add_patient.php" method="POST" class="space-y-4">
            <input type="text" name="name" placeholder="Name" required class="w-full px-4 py-2 border rounded-md focus:ring focus:ring-blue-300">
            <input type="text" name="age" placeholder="Age" required class="w-full px-4 py-2 border rounded-md focus:ring focus:ring-blue-300">
            <select name="gender" required class="w-full px-4 py-2 border rounded-md focus:ring focus:ring-blue-300">
                <option value="">Select Gender</option>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
                <option value="Other">Other</option>
            </select>
            <input type="text" name="contact" placeholder="Contact Number" required class="w-full px-4 py-2 border rounded-md focus:ring focus:ring-blue-300">
            <div class="flex justify-end gap-2">
                <button type="button" onclick="toggleAddPatientModal()" class="px-4 py-2 bg-gray-300 rounded-md">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Add</button>
            </div>
        </form>
    </div>
</div>
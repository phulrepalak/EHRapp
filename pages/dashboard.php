<div id="dashboard">
    <h2 class="text-2xl font-semibold text-gray-800 mb-4 ">Welcome to CareYug EHR Dashboard</h2>

    <div class="flex flex-row gap-8 justify-around ">
        <div id="slider" class="w-2/3 h-80 relative overflow-hidden border-2 bg-white border shadow rounded-xl ">
            <h1 class="font-bold text-xl p-4 text-left">Reports</h1>


            <button id="scrollLeft"
                class="absolute left-0 top-1/2 -translate-y-1/2 bg-transparent shadow hover:bg-gray-100 p-2 rounded-full z-10 font-bold">
                &lt
            </button>


            <button id="scrollRight"
                class="absolute right-0 top-1/2 -translate-y-1/2 bg-transparent shadow hover:bg-gray-100 p-2 rounded-full z-10 font-bold">
                &gt
            </button>


            <div id="scrollArea"
                class="overflow-x-auto whitespace-nowrap p-4 scroll-smooth pr-8"
                style="-ms-overflow-style: none; scrollbar-width: none;">
                <div class="flex space-x-4">
                    <div class="w-48 h-48  space-xl  bg-white rounded-xl shadow p-4 flex-shrink-0 border">
                        <img class="h-full w-full object-fill rounded shadow-2xl" src="/ehr-app/assets/images/a.png" alt="">
                        <button></button>
                    </div>
                    <div class="w-48 h-48  space-xl  bg-white rounded-xl shadow p-4 flex-shrink-0"><img class="h-full w-full object-fill rounded shadow-xl" src="/ehr-app/assets/images/a.png" alt=""></div>
                    <div class="w-48 h-48  space-xl  bg-white rounded-xl shadow p-4 flex-shrink-0"><img class="h-full w-full object-fill rounded shadow-xl" src="/ehr-app/assets/images/a.png" alt=""></div>
                    <div class="w-48 h-48  space-xl  bg-white rounded-xl shadow p-4 flex-shrink-0"><img class="h-full w-full object-fill rounded shadow-xl" src="/ehr-app/assets/images/a.png" alt=""></div>
                    <div class="w-48 h-48  space-xl  bg-white rounded-xl shadow p-4 flex-shrink-0"><img class="h-full w-full object-fill rounded shadow-xl" src="/ehr-app/assets/images/a.png" alt=""></div>
                </div>
            </div>
        </div>
        <!-- Appointments -->


    <?php
include 'db.php';

// Fetch recent appointments (latest 5)


$sql = "SELECT p.name, a.appointment_time, a.appointment_date 
        FROM appointment a
        JOIN patient p ON a.patient_id = p.id
        ORDER BY a.appointment_date DESC, a.appointment_time DESC
        LIMIT 5";

$result = $conn->query($sql);
?>

<div class="w-64 h-80 rounded-xl shadow bg-white border text-left overflow-y-auto">
    <p class="font-bold text-xl p-4">Appointments</p>

    <?php
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $formattedTime = date("h:i A", strtotime($row['appointment_time']));
            $formattedDate = date("d M Y", strtotime($row['appointment_date']));
            ?>
            <div class="px-4 py-2 border-b">
                <p class="font-medium text-lg"><?= htmlspecialchars($row['name']) ?></p>
                <p class="font-thin text-sm text-gray-600">
                    <?= $formattedDate ?> at <?= $formattedTime ?>
                </p>
            </div>
            <?php
        }
    } else {
        echo '<p class="text-gray-400 px-4">No appointments</p>';
    }
    ?>
</div>




        <!-- Hide Scrollbar  -->
        <style>
            #scrollArea::-webkit-scrollbar {
                display: none;
            }
        </style>

        <script>
            const scrollArea = document.getElementById('scrollArea');
            const scrollLeftBtn = document.getElementById('scrollLeft');
            const scrollRightBtn = document.getElementById('scrollRight');

            scrollLeftBtn.addEventListener('click', () => {
                scrollArea.scrollBy({
                    left: -200,
                    behavior: 'smooth'
                });
            });

            scrollRightBtn.addEventListener('click', () => {
                scrollArea.scrollBy({
                    left: 200,
                    behavior: 'smooth'
                });
            });
        </script>

    </div>
    <div class=" w-full bg-white border  h-auto p-2 rounded-xl shadow m-2 text-left">
        <p class="font-bold text-xl p-4">Recent Patient</p>
        <div class="bg-white rounded-xl shadow-xl  border p-6 overflow-x-auto">
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

                    $result = $conn->query("SELECT * FROM patient ORDER BY id DESC");
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
                </tbody>
            </table>
        </div>
    </div>
</div>
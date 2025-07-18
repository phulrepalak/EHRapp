<?php
include 'db.php'; 

// Fetch all patients and their appointment info 
$sql = "SELECT 
            p.id AS patient_id,
            p.name,
            a.date,
            a.time,
            a.doctor_name
        FROM patient p
        LEFT JOIN appointment a ON a.patient_id = p.id
        ORDER BY p.id DESC";

$result = $conn->query($sql);
?>

<div>
    <h2 class="text-2xl font-semibold text-gray-800 mb-4">Appointments</h2>
    <div class="w-full mx-auto bg-white rounded-xl shadow-md p-6">

        <table class="w-full border">
            <thead>
                <tr class="bg-gray-200">
                    <th class="p-2 border">ID</th>
                    <th class="p-2 border">Patient</th>
                    <th class="p-2 border">Date</th>
                    <th class="p-2 border">Time</th>
                    <th class="p-2 border">Doctor</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        ?>
                        <tr class="hover:bg-gray-100">
                            <td class="p-2 border"><?php echo $row['patient_id']; ?></td>
                            <td class="p-2 border">
                                <a href="admin-panel.php?page=appointment-detail&id=<?php echo $row['patient_id']; ?>" class="text-blue-600 hover:underline">
                                    <?php echo htmlspecialchars($row['name']); ?>
                                </a>
                            </td>
                            <td class="p-2 border">
                                <?php echo $row['date'] ?? '<span class="text-gray-400 italic">N/A</span>'; ?>
                            </td>
                            <td class="p-2 border">
                                <?php echo isset($row['time']) ? date("h:i A", strtotime($row['time'])) : '<span class="text-gray-400 italic">N/A</span>'; ?>
                            </td>
                            <td class="p-2 border">
                                <?php echo $row['doctor_name'] ?? '<span class="text-gray-400 italic">N/A</span>'; ?>
                            </td>
                        </tr>
                        <?php
                    }
                } else {
                    echo "<tr><td colspan='5' class='text-center p-2 border'>No patients found</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

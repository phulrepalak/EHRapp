<?php
include 'db.php';

// Handle search input
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

$likeClause = "";
if (!empty($search)) {
    $search = $conn->real_escape_string($search);
    $like = "'%" . $search . "%'";
    $likeClause = "WHERE 
        a.appointment_date LIKE $like OR 
        a.appointment_time LIKE $like OR 
        a.doctor LIKE $like OR 
        a.patient_id LIKE $like OR 
        p.name LIKE $like";
}

$sql = "SELECT 
            
            a.patient_id,
            p.name AS patient_name,
            a.appointment_date,
            a.appointment_time,
            a.doctor
        FROM appointment a
        LEFT JOIN patient p ON a.patient_id = p.id
        $likeClause 
        ORDER BY a.id DESC";

$result = $conn->query($sql);
?>

<div class="max-w-7xl mx-auto px-4 py-10">

    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8">
       <h1 class="text-2xl text-center text-gray-800 mb-4 mx-auto font-semibold">Appointment Records</h1>

        <a href="admin-panel.php?page=appointment-detail"
            class="mt-4 sm:mt-0 inline-flex items-center px-5 py-2.5 bg-green-600 hover:bg-green-700 text-white font-medium text-sm rounded-lg shadow transition">
            New Appointment
        </a>
    </div>

    <!-- Search Bar -->
    <form method="GET" action="admin-panel.php" class="mb-8">
        <input type="hidden" name="page" value="appointments">
        <div class="flex justify-center">
            <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>"
                placeholder=" Search by  Patient Name, Date, Time, Doctor or Patient ID"
                class="w-full max-w-xl border border-gray-300 px-5 py-3 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            <button type="submit" class="ml-3 px-5 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 shadow">
                Search
            </button>
        </div>
    </form>

    <!-- Appointment Table Card -->
    <div class="bg-white rounded-xl shadow-xl  border p-6 overflow-x-auto">
        <table class="min-w-full table-auto text-left text-sm">
            <thead class="bg-gray-200  sticky top-0 z-10">
                <tr>
                    <th class="px-6 py-4 text-left text-sm font-semibold uppercase">Patient ID</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold uppercase">Patient Name</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold uppercase">Date</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold uppercase">Time</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold uppercase">Doctor</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr class="hover:bg-blue-50 transition">
                            <td class="px-6 py-4 text-sm text-gray-800"><?php echo $row['patient_id']; ?></td>
                            <td class="px-6 py-4 text-sm text-gray-800">
                                <a href="pages\appointment-edit.php?id=<?php echo $row['patient_id']; ?>">
                                    
                                    <?php echo htmlspecialchars($row['patient_name']); ?>
                                </a>
                            </td>

                            <td class="px-6 py-4 text-sm text-gray-800">
                                <?php echo htmlspecialchars($row['appointment_date']); ?></td>
                            <td class="px-6 py-4 text-sm text-gray-800">
                                <?php echo date("h:i A", strtotime($row['appointment_time'])); ?></td>
                            <td class="px-6 py-4 text-sm text-gray-800"><?php echo htmlspecialchars($row['doctor']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">No appointments found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
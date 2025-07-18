<?php
include 'db.php';

// Handle search
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

$likeClause = "";
if (!empty($search)) {
    $search = $conn->real_escape_string($search);
    $like = "'%" . $search . "%'";
    $likeClause = "WHERE 
        p.id LIKE $like OR 
        p.name LIKE $like OR 
        a.date LIKE $like OR 
        a.time LIKE $like OR 
        a.doctor_name LIKE $like";
}

// Fetch data
$sql = "SELECT 
            p.id AS patient_id,
            p.name,
            a.date,
            a.time,
            a.doctor_name
        FROM patient p
        LEFT JOIN appointment a ON a.patient_id = p.id
        $likeClause
        ORDER BY p.id DESC";

$result = $conn->query($sql);
?>

<div>
    <!-- Heading -->
    <h2 class="text-2xl font-semibold text-gray-800 mb-4 text-center">Appointments</h2>

    <!-- Search Bar Centered Just Below Heading -->
    <form method="GET" action="admin-panel.php" class="mb-6 flex justify-center">
        <input type="hidden" name="page" value="appointments">
        <input 
            type="text" 
            name="search" 
            value="<?php echo htmlspecialchars($search); ?>" 
            placeholder="Search by ID, Name, Date, Time, or Doctor" 
            class="border border-gray-300 rounded-lg px-4 py-2 w-96 focus:outline-none focus:ring-2 focus:ring-blue-500"
        >
        <button 
            type="submit" 
            class="ml-2 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700"
        >
            Search
        </button>
    </form>

    <!-- Appointments Table -->
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
                            <td class="p-2 border"><?php echo $row['date'] ?? '<span class="text-gray-400 italic">N/A</span>'; ?></td>
                            <td class="p-2 border"><?php echo isset($row['time']) ? date("h:i A", strtotime($row['time'])) : '<span class="text-gray-400 italic">N/A</span>'; ?></td>
                            <td class="p-2 border"><?php echo $row['doctor_name'] ?? '<span class="text-gray-400 italic">N/A</span>'; ?></td>
                        </tr>
                        <?php
                    }
                } else {
                    echo "<tr><td colspan='5' class='text-center p-2 border text-gray-500'>No results found.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

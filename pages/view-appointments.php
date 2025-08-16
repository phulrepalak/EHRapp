<?php
include 'db.php';

$search = $_GET['search'] ?? '';
$query = "SELECT a.*, p.name, p.contact FROM appointment a JOIN patient p ON a.patient_id = p.id";

if ($search !== '') {
    $query .= " WHERE p.name LIKE '%$search%' OR a.doctor LIKE '%$search%' OR a.next_visit LIKE '%$search%'";
}

$result = $conn->query($query);
?>

<form method="get" class="flex gap-4 mb-4">
  <input type="text" name="search" placeholder="Search by name/date/doctor"
         value="<?= htmlspecialchars($search) ?>"
         class="border px-4 py-2 rounded w-full sm:w-1/3" />
  <button class="bg-blue-600 text-white px-4 py-2 rounded">Search</button>
</form>

<table class="w-full border text-sm">
  <thead><tr>
    <th>Patient</th><th>Doctor</th><th>Next Visit</th>
  </tr></thead>
  <tbody>
    <?php while ($row = $result->fetch_assoc()): ?>
      <tr class="border-b">
        <td><?= $row['name'] ?> (<?= $row['contact'] ?>)</td>
        <td><?= $row['doctor'] ?></td>
        <td><?= $row['next_visit'] ?></td>
      </tr>
    <?php endwhile; ?>
  </tbody>
</table>

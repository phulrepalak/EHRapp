<?php
include 'db.php';

$term = $_GET['term'] ?? '';

if (!$term) {
    echo json_encode([]);
    exit;
}

$term = "%$term%";

// Search by name, contact, or ID
$stmt = $conn->prepare("SELECT id, name, contact FROM patient WHERE name LIKE ? OR contact LIKE ? OR id LIKE ?");
$stmt->bind_param("sss", $term, $term, $term);
$stmt->execute();
$result = $stmt->get_result();

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = [
        'label' => $row['name'] . ' (' . $row['contact'] . ')',
        'value' => $row['name'],
        'id' => $row['id'],
        'contact' => $row['contact']
    ];
}

echo json_encode($data);
?>

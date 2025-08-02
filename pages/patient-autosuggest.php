<?php
include 'db.php';

$term = $_GET['term'] ?? '';
$term = $conn->real_escape_string($term);

$sql = "
  SELECT id, name, contact 
  FROM patient 
  WHERE 
    id LIKE '%$term%' OR 
    name LIKE '%$term%' OR 
    contact LIKE '%$term%' 
  LIMIT 10
";
$result = $conn->query($sql);

$suggestions = [];

while ($row = $result->fetch_assoc()) {
  $suggestions[] = [
    'id' => $row['id'],
    'label' => $row['name'] . ' (' . $row['contact'] . ' / ID: ' . $row['id'] . ')',
    'value' => $row['name']
  ];
}

echo json_encode($suggestions);
?>

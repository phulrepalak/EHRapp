<?php
include 'db.php';
$id = $_GET['id'] ?? '';
if ($id) {
    $stmt = $conn->prepare("SELECT * FROM patient WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    echo json_encode($result->fetch_assoc());
}
?>

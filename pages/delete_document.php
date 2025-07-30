<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['document_id'])) {
    $documentId = $_POST['document_id'];

    // First fetch the file path to delete from server
    $stmt = $conn->prepare("SELECT filepath FROM document WHERE id = ?");
    $stmt->bind_param("i", $documentId);
    $stmt->execute();
    $result = $stmt->get_result();
    $doc = $result->fetch_assoc();

    if ($doc) {
        $filePath = $doc['filepath'];

        // Delete from DB
        $stmt = $conn->prepare("DELETE FROM document WHERE id = ?");
        $stmt->bind_param("i", $documentId);
        $stmt->execute();

        // Delete file from server
        if (file_exists($filePath)) {
            unlink($filePath);
        }

        echo json_encode(['success' => true]);
        exit;
    }
}

echo json_encode(['success' => false, 'message' => 'Invalid request']);
exit;
?>

<?php
include "db.php";

// Get the patient ID safely
$id = $_GET["id"] ?? null;

if ($id && is_numeric($id)) {
    // Prepare the SQL statement
    $stmt = $conn->prepare("SELECT * FROM patients WHERE id = ?");
    $stmt->bind_param("i", $id); // 'i' = integer
    $stmt->execute();

    $result = $stmt->get_result();

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
    }
    $stmt->close();
    $conn->close();
}

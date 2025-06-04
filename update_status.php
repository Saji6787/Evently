<?php
include 'config/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_user = $_POST['id_user'] ?? null;
    $status = $_POST['status'] ?? null;

    if (!$id_user || !$status || !in_array($status, ['aktif', 'nonaktif'])) {
        http_response_code(400);
        echo 'Invalid input';
        exit;
    }

    // Prepare and execute update query
    $stmt = $conn->prepare("UPDATE user SET status = ? WHERE id_user = ?");
    $stmt->bind_param("si", $status, $id_user);

    if ($stmt->execute()) {
        echo "Status updated successfully";
    } else {
        http_response_code(500);
        echo "Failed to update status";
    }
    $stmt->close();
} else {
    http_response_code(405);
    echo "Method not allowed";
}
?>

<?php
include '../config/config.php';

header('Content-Type: application/json');

try {
    $gallery_id = $_GET['id'] ?? 0;

    if (empty($gallery_id)) {
        throw new Exception('Gallery ID is required');
    }

    $stmt = $conn->prepare("SELECT * FROM gallery WHERE id = ?");
    $stmt->bind_param("i", $gallery_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        throw new Exception('Gallery item not found');
    }

    $gallery = $result->fetch_assoc();
    echo json_encode($gallery);

} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>

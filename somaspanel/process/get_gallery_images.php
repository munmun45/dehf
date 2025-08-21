<?php
require('../config/config.php');

header('Content-Type: application/json');

if (!isset($_GET['service_id'])) {
    echo json_encode(['success' => false, 'message' => 'Service ID is required']);
    exit;
}

$service_id = intval($_GET['service_id']);

try {
    $stmt = $conn->prepare("SELECT id, image_path FROM service_images WHERE service_id = ? AND image_type = 'gallery' ORDER BY created_at DESC");
    $stmt->bind_param("i", $service_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $images = [];
    while ($row = $result->fetch_assoc()) {
        $images[] = $row;
    }
    
    echo json_encode([
        'success' => true,
        'images' => $images
    ]);
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error fetching gallery images: ' . $e->getMessage()
    ]);
}

$conn->close();
?>

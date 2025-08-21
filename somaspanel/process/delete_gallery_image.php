<?php
require('../config/config.php');

header('Content-Type: application/json');

// Get JSON input
$input = json_decode(file_get_contents('php://input'), true);

if (!isset($input['image_id'])) {
    echo json_encode(['success' => false, 'message' => 'Image ID is required']);
    exit;
}

$image_id = intval($input['image_id']);

try {
    // Get image path before deleting
    $stmt = $conn->prepare("SELECT image_path FROM service_images WHERE id = ?");
    $stmt->bind_param("i", $image_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        echo json_encode(['success' => false, 'message' => 'Image not found']);
        exit;
    }
    
    $row = $result->fetch_assoc();
    $image_path = $row['image_path'];
    
    // Delete from database
    $delete_stmt = $conn->prepare("DELETE FROM service_images WHERE id = ?");
    $delete_stmt->bind_param("i", $image_id);
    
    if ($delete_stmt->execute()) {
        // Delete physical file
        $full_path = '../' . $image_path;
        if (file_exists($full_path)) {
            unlink($full_path);
        }
        
        echo json_encode(['success' => true, 'message' => 'Image deleted successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to delete image from database']);
    }
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error deleting image: ' . $e->getMessage()
    ]);
}

$conn->close();
?>

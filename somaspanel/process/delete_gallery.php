<?php
include '../config/config.php';

try {
    $gallery_id = $_GET['id'] ?? 0;

    if (empty($gallery_id)) {
        throw new Exception('Gallery ID is required');
    }

    // Get gallery item to delete image file
    $stmt = $conn->prepare("SELECT * FROM gallery WHERE id = ?");
    $stmt->bind_param("i", $gallery_id);
    $stmt->execute();
    $gallery = $stmt->get_result()->fetch_assoc();

    if (!$gallery) {
        throw new Exception('Gallery item not found');
    }

    // Delete image file if it exists
    if ($gallery['image_path'] && file_exists('../' . $gallery['image_path'])) {
        unlink('../' . $gallery['image_path']);
    }

    // Delete from database
    $stmt = $conn->prepare("DELETE FROM gallery WHERE id = ?");
    $stmt->bind_param("i", $gallery_id);
    
    if (!$stmt->execute()) {
        throw new Exception('Failed to delete gallery item');
    }

    header('Location: ../gallery.php?message=Gallery image deleted successfully');

} catch (Exception $e) {
    header('Location: ../gallery.php?error=' . urlencode($e->getMessage()));
}
?>

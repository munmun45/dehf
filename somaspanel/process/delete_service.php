<?php
include '../config/config.php';

try {
    $service_id = $_GET['id'] ?? 0;

    if (!$service_id) {
        header("Location: ../service.php?error=Service ID is required");
        exit();
    }

    // Get service images to delete files
    $images_stmt = $conn->prepare("SELECT main_image FROM services WHERE id = ?");
    $images_stmt->bind_param("i", $service_id);
    $images_stmt->execute();
    $service = $images_stmt->get_result()->fetch_assoc();

    $gallery_stmt = $conn->prepare("SELECT image_path FROM service_images WHERE service_id = ?");
    $gallery_stmt->bind_param("i", $service_id);
    $gallery_stmt->execute();
    $gallery_images = $gallery_stmt->get_result()->fetch_all(MYSQLI_ASSOC);

    // Delete main image file
    if ($service && $service['main_image'] && file_exists('../' . $service['main_image'])) {
        unlink('../' . $service['main_image']);
    }

    // Delete gallery image files
    foreach ($gallery_images as $image) {
        if (file_exists('../' . $image['image_path'])) {
            unlink('../' . $image['image_path']);
        }
    }

    // Delete service (cascade will handle related records)
    $stmt = $conn->prepare("DELETE FROM services WHERE id = ?");
    $stmt->bind_param("i", $service_id);
    
    if (!$stmt->execute()) {
        throw new Exception('Failed to delete service');
    }

    header("Location: ../service.php?success=Service deleted successfully");

} catch (Exception $e) {
    header("Location: ../service.php?error=" . urlencode($e->getMessage()));
}
?>

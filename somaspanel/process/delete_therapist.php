<?php
include '../config/config.php';

try {
    $therapist_id = $_GET['id'] ?? 0;
    $service_id = $_GET['service_id'] ?? 0;

    if (!$therapist_id || !$service_id) {
        header("Location: ../manage_therapists.php?service_id=" . $service_id . "&error=Therapist ID is required");
        exit();
    }

    // Get therapist image to delete file
    $image_stmt = $conn->prepare("SELECT therapist_image FROM service_therapists WHERE id = ?");
    $image_stmt->bind_param("i", $therapist_id);
    $image_stmt->execute();
    $therapist = $image_stmt->get_result()->fetch_assoc();

    // Delete image file if exists
    if ($therapist && $therapist['therapist_image'] && file_exists('../' . $therapist['therapist_image'])) {
        unlink('../' . $therapist['therapist_image']);
    }

    $stmt = $conn->prepare("DELETE FROM service_therapists WHERE id = ?");
    $stmt->bind_param("i", $therapist_id);
    
    if ($stmt->execute()) {
        header("Location: ../manage_therapists.php?service_id=" . $service_id . "&success=Therapist deleted successfully");
    } else {
        header("Location: ../manage_therapists.php?service_id=" . $service_id . "&error=Failed to delete therapist");
    }

} catch (Exception $e) {
    header("Location: ../manage_therapists.php?service_id=" . $service_id . "&error=" . urlencode($e->getMessage()));
}
?>

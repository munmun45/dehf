<?php
include '../config/config.php';

try {
    $benefit_id = $_GET['id'] ?? 0;
    $service_id = $_GET['service_id'] ?? 0;

    if (!$benefit_id || !$service_id) {
        header("Location: ../manage_benefits.php?service_id=" . $service_id . "&error=Benefit ID is required");
        exit();
    }

    $stmt = $conn->prepare("DELETE FROM service_benefits WHERE id = ?");
    $stmt->bind_param("i", $benefit_id);
    
    if ($stmt->execute()) {
        header("Location: ../manage_benefits.php?service_id=" . $service_id . "&success=Benefit deleted successfully");
    } else {
        header("Location: ../manage_benefits.php?service_id=" . $service_id . "&error=Failed to delete benefit");
    }

} catch (Exception $e) {
    header("Location: ../manage_benefits.php?service_id=" . $service_id . "&error=" . urlencode($e->getMessage()));
}
?>

<?php
include '../config/config.php';

try {
    $faq_id = $_GET['id'] ?? 0;
    $service_id = $_GET['service_id'] ?? 0;

    if (!$faq_id || !$service_id) {
        header("Location: ../manage_faqs.php?service_id=" . $service_id . "&error=FAQ ID is required");
        exit();
    }

    $stmt = $conn->prepare("DELETE FROM service_faqs WHERE id = ?");
    $stmt->bind_param("i", $faq_id);
    
    if ($stmt->execute()) {
        header("Location: ../manage_faqs.php?service_id=" . $service_id . "&success=FAQ deleted successfully");
    } else {
        header("Location: ../manage_faqs.php?service_id=" . $service_id . "&error=Failed to delete FAQ");
    }

} catch (Exception $e) {
    header("Location: ../manage_faqs.php?service_id=" . $service_id . "&error=" . urlencode($e->getMessage()));
}
?>

<?php
include '../config/config.php';

header('Content-Type: application/json');

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Invalid request method');
    }

    $service_id = $_POST['service_id'] ?? 0;
    $icon_class = $_POST['icon_class'] ?? '';
    $title = $_POST['title'] ?? '';
    $description = $_POST['description'] ?? '';

    if (!$service_id || empty($title)) {
        throw new Exception('Service ID and title are required');
    }

    // Get next sort order
    $sort_stmt = $conn->prepare("SELECT COALESCE(MAX(sort_order), 0) + 1 as next_order FROM service_benefits WHERE service_id = ?");
    $sort_stmt->bind_param("i", $service_id);
    $sort_stmt->execute();
    $sort_order = $sort_stmt->get_result()->fetch_assoc()['next_order'];

    $stmt = $conn->prepare("INSERT INTO service_benefits (service_id, icon_class, title, description, sort_order) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("isssi", $service_id, $icon_class, $title, $description, $sort_order);
    
    if (!$stmt->execute()) {
        throw new Exception('Failed to add benefit');
    }

    header("Location: ../manage_benefits.php?service_id=" . $service_id . "&success=Benefit added successfully");

} catch (Exception $e) {
    header("Location: ../manage_benefits.php?service_id=" . $service_id . "&error=" . urlencode($e->getMessage()));
}
?>

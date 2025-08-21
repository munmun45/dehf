<?php
include '../config/config.php';

header('Content-Type: application/json');

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Invalid request method');
    }

    $service_id = $_POST['service_id'] ?? 0;
    $question = $_POST['question'] ?? '';
    $answer = $_POST['answer'] ?? '';

    if (!$service_id || empty($question)) {
        throw new Exception('Service ID and question are required');
    }

    // Get next sort order
    $sort_stmt = $conn->prepare("SELECT COALESCE(MAX(sort_order), 0) + 1 as next_order FROM service_faqs WHERE service_id = ?");
    $sort_stmt->bind_param("i", $service_id);
    $sort_stmt->execute();
    $sort_order = $sort_stmt->get_result()->fetch_assoc()['next_order'];

    $stmt = $conn->prepare("INSERT INTO service_faqs (service_id, question, answer, sort_order) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("issi", $service_id, $question, $answer, $sort_order);
    
    if (!$stmt->execute()) {
        throw new Exception('Failed to add FAQ');
    }

    header("Location: ../manage_faqs.php?service_id=" . $service_id . "&success=FAQ added successfully");

} catch (Exception $e) {
    header("Location: ../manage_faqs.php?service_id=" . $service_id . "&error=" . urlencode($e->getMessage()));
}
?>

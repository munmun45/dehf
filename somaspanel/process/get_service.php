<?php
include '../config/config.php';

header('Content-Type: application/json');

try {
    $service_id = $_GET['id'] ?? 0;

    if (!$service_id) {
        throw new Exception('Service ID is required');
    }

    $stmt = $conn->prepare("SELECT * FROM services WHERE id = ?");
    $stmt->bind_param("i", $service_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($service = $result->fetch_assoc()) {
        echo json_encode($service);
    } else {
        throw new Exception('Service not found');
    }

} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>

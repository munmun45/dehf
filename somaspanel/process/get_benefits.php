<?php
include '../config/config.php';

header('Content-Type: application/json');

try {
    $service_id = $_GET['service_id'] ?? 0;

    if (!$service_id) {
        throw new Exception('Service ID is required');
    }

    $stmt = $conn->prepare("SELECT * FROM service_benefits WHERE service_id = ? ORDER BY sort_order ASC");
    $stmt->bind_param("i", $service_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $benefits = [];
    while ($row = $result->fetch_assoc()) {
        $benefits[] = $row;
    }

    echo json_encode($benefits);

} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>

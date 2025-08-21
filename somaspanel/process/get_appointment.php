<?php
require('../config/config.php');

header('Content-Type: application/json');

if (!isset($_GET['id'])) {
    echo json_encode(['success' => false, 'message' => 'Appointment ID is required']);
    exit;
}

$appointment_id = intval($_GET['id']);

$stmt = $conn->prepare("SELECT * FROM appointments WHERE id = ?");
$stmt->bind_param("i", $appointment_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $appointment = $result->fetch_assoc();
    echo json_encode([
        'success' => true,
        'appointment' => $appointment
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Appointment not found'
    ]);
}

$stmt->close();
$conn->close();
?>

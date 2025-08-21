<?php
require('../config/config.php');

header('Content-Type: application/json');

if (!isset($_GET['id'])) {
    echo json_encode(['success' => false, 'message' => 'Submission ID is required']);
    exit;
}

$submission_id = intval($_GET['id']);

$stmt = $conn->prepare("SELECT * FROM contact_submissions WHERE id = ?");
$stmt->bind_param("i", $submission_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $submission = $result->fetch_assoc();
    echo json_encode([
        'success' => true,
        'submission' => $submission
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Submission not found'
    ]);
}

$stmt->close();
$conn->close();
?>

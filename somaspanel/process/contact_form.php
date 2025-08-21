<?php
require('../config/config.php');

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

// Validate required fields
$required_fields = ['name', 'phone', 'select', 'message'];
foreach ($required_fields as $field) {
    if (!isset($_POST[$field]) || empty(trim($_POST[$field]))) {
        echo json_encode(['success' => false, 'message' => 'All fields are required']);
        exit;
    }
}

$name = trim($_POST['name']);
$phone = trim($_POST['phone']);
$service = trim($_POST['select']);
$message = trim($_POST['message']);

// Insert contact submission
$stmt = $conn->prepare("INSERT INTO contact_submissions (name, phone, service, message) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $name, $phone, $service, $message);

if ($stmt->execute()) {
    echo json_encode([
        'success' => true,
        'message' => 'Thank you for your message! We will get back to you soon.'
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Sorry, there was an error submitting your message. Please try again.'
    ]);
}

$stmt->close();
$conn->close();
?>

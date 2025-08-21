<?php
require('../config/config.php');

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

// Validate required fields
$required_fields = ['name', 'phone', 'service', 'therapist', 'date', 'time'];
foreach ($required_fields as $field) {
    if (!isset($_POST[$field]) || empty(trim($_POST[$field]))) {
        echo json_encode(['success' => false, 'message' => 'All fields are required']);
        exit;
    }
}

$name = trim($_POST['name']);
$phone = trim($_POST['phone']);
$service = trim($_POST['service']);
$therapist = trim($_POST['therapist']);
$appointment_date = trim($_POST['date']);
$appointment_time = trim($_POST['time']);
$message = isset($_POST['message']) ? trim($_POST['message']) : '';

// Validate date format
if (!DateTime::createFromFormat('Y-m-d', $appointment_date)) {
    echo json_encode(['success' => false, 'message' => 'Invalid date format']);
    exit;
}

// Validate time format
if (!DateTime::createFromFormat('H:i', $appointment_time)) {
    echo json_encode(['success' => false, 'message' => 'Invalid time format']);
    exit;
}

// Check if appointment slot is already taken
$check_stmt = $conn->prepare("SELECT id FROM appointments WHERE appointment_date = ? AND appointment_time = ? AND therapist = ? AND status != 'cancelled'");
$check_stmt->bind_param("sss", $appointment_date, $appointment_time, $therapist);
$check_stmt->execute();
$check_result = $check_stmt->get_result();

if ($check_result->num_rows > 0) {
    echo json_encode(['success' => false, 'message' => 'This time slot is already booked. Please choose a different time.']);
    exit;
}

// Insert appointment
$stmt = $conn->prepare("INSERT INTO appointments (name, phone, service, therapist, appointment_date, appointment_time, message) VALUES (?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("sssssss", $name, $phone, $service, $therapist, $appointment_date, $appointment_time, $message);

if ($stmt->execute()) {
    echo json_encode([
        'success' => true,
        'message' => 'Your appointment has been booked successfully! We will contact you soon to confirm.',
        'appointment_id' => $conn->insert_id
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Sorry, there was an error booking your appointment. Please try again.'
    ]);
}

$stmt->close();
$check_stmt->close();
$conn->close();
?>

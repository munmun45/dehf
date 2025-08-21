<?php
require('../config/config.php');
require('../../email/email.php');

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
    $appointment_id = $conn->insert_id;
    
    // Format appointment date and time for display
    $formatted_date = date('F j, Y', strtotime($appointment_date));
    $formatted_time = date('g:i A', strtotime($appointment_time));
    
    // Prepare email content for admin notification
    $email_body = "
    <html>
    <head>
        <title>New Appointment Booking - Divine Energy Healing Foundation</title>
    </head>
    <body>
        <h2>New Appointment Booking</h2>
        <p>You have received a new appointment booking from your website.</p>
        
        <table border='1' cellpadding='10' cellspacing='0' style='border-collapse: collapse; width: 100%; max-width: 600px;'>
            <tr>
                <td><strong>Appointment ID:</strong></td>
                <td>#" . $appointment_id . "</td>
            </tr>
            <tr>
                <td><strong>Client Name:</strong></td>
                <td>" . htmlspecialchars($name) . "</td>
            </tr>
            <tr>
                <td><strong>Phone Number:</strong></td>
                <td>" . htmlspecialchars($phone) . "</td>
            </tr>
            <tr>
                <td><strong>Service Requested:</strong></td>
                <td>" . htmlspecialchars($service) . "</td>
            </tr>
            <tr>
                <td><strong>Preferred Therapist:</strong></td>
                <td>" . htmlspecialchars($therapist) . "</td>
            </tr>
            <tr>
                <td><strong>Appointment Date:</strong></td>
                <td>" . $formatted_date . "</td>
            </tr>
            <tr>
                <td><strong>Appointment Time:</strong></td>
                <td>" . $formatted_time . "</td>
            </tr>";
    
    if (!empty($message)) {
        $email_body .= "
            <tr>
                <td><strong>Additional Message:</strong></td>
                <td>" . nl2br(htmlspecialchars($message)) . "</td>
            </tr>";
    }
    
    $email_body .= "
            <tr>
                <td><strong>Booked At:</strong></td>
                <td>" . date('Y-m-d H:i:s') . "</td>
            </tr>
        </table>
        
        <p><strong>Action Required:</strong> Please contact the client to confirm this appointment.</p>
        <p><em>This email was automatically generated from Divine Energy Healing Foundation website.</em></p>
    </body>
    </html>
    ";
    
    // Send email to admin
    $email_sent = sendPaymentEmail('New Appointment', $email_body , 'New Appointment Booking');
    
    echo json_encode([
        'success' => true,
        'message' => 'Your appointment has been booked successfully! We will contact you soon to confirm.',
        'appointment_id' => $appointment_id,
        'email_sent' => $email_sent
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

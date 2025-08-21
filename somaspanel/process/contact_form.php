<?php
require('../config/config.php');
require('../../email/email.php');

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
    // Prepare email content for admin notification
    $email_body = "
    <html>
    <head>
        <title>New Contact Form Submission - Divine Energy Healing Foundation</title>
    </head>
    <body>
        <h2>New Contact Form Submission</h2>
        <p>You have received a new contact form submission from your website.</p>
        
        <table border='1' cellpadding='10' cellspacing='0' style='border-collapse: collapse; width: 100%; max-width: 600px;'>
            <tr>
                <td><strong>Name:</strong></td>
                <td>" . htmlspecialchars($name) . "</td>
            </tr>
            <tr>
                <td><strong>Phone:</strong></td>
                <td>" . htmlspecialchars($phone) . "</td>
            </tr>
            <tr>
                <td><strong>Service Interested:</strong></td>
                <td>" . htmlspecialchars($service) . "</td>
            </tr>
            <tr>
                <td><strong>Message:</strong></td>
                <td>" . nl2br(htmlspecialchars($message)) . "</td>
            </tr>
            <tr>
                <td><strong>Submitted At:</strong></td>
                <td>" . date('Y-m-d H:i:s') . "</td>
            </tr>
        </table>
        
        <p>Please respond to this inquiry as soon as possible.</p>
        <p><em>This email was automatically generated from Divine Energy Healing Foundation website.</em></p>
    </body>
    </html>
    ";
    
    // Send email to admin
    $email_sent = sendPaymentEmail('New Contact', $email_body , 'New Contact Form Submission');
    
    echo json_encode([
        'success' => true,
        'message' => 'Thank you for your message! We will get back to you soon.',
        'email_sent' => $email_sent
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

<?php
include '../config/config.php';

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header("Location: ../manage_therapists.php?error=Invalid request method");
        exit();
    }

    $service_id = $_POST['service_id'] ?? 0;
    $therapist_name = $_POST['therapist_name'] ?? '';
    $therapist_title = $_POST['therapist_title'] ?? '';
    $facebook_url = $_POST['facebook_url'] ?? '';
    $twitter_url = $_POST['twitter_url'] ?? '';
    $linkedin_url = $_POST['linkedin_url'] ?? '';
    $instagram_url = $_POST['instagram_url'] ?? '';

    if (!$service_id || empty($therapist_name)) {
        header("Location: ../manage_therapists.php?service_id=" . $service_id . "&error=Service ID and therapist name are required");
        exit();
    }

    // Handle therapist image upload
    $therapist_image = '';
    if (isset($_FILES['therapist_image']) && $_FILES['therapist_image']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = '../images/therapists/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        
        $file_extension = pathinfo($_FILES['therapist_image']['name'], PATHINFO_EXTENSION);
        $image_name = strtolower(str_replace(' ', '_', $therapist_name)) . '_' . time() . '.' . $file_extension;
        $image_path = $upload_dir . $image_name;
        
        if (move_uploaded_file($_FILES['therapist_image']['tmp_name'], $image_path)) {
            $therapist_image = 'images/therapists/' . $image_name;
        }
    }

    // Get next sort order
    $sort_stmt = $conn->prepare("SELECT COALESCE(MAX(sort_order), 0) + 1 as next_order FROM service_therapists WHERE service_id = ?");
    $sort_stmt->bind_param("i", $service_id);
    $sort_stmt->execute();
    $sort_order = $sort_stmt->get_result()->fetch_assoc()['next_order'];

    $stmt = $conn->prepare("INSERT INTO service_therapists (service_id, therapist_name, therapist_title, therapist_image, facebook_url, twitter_url, linkedin_url, instagram_url, sort_order) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("issssssi", $service_id, $therapist_name, $therapist_title, $therapist_image, $facebook_url, $twitter_url, $linkedin_url, $instagram_url, $sort_order);
    
    if (!$stmt->execute()) {
        throw new Exception('Failed to add therapist');
    }

    header("Location: ../manage_therapists.php?service_id=" . $service_id . "&success=Therapist added successfully");

} catch (Exception $e) {
    header("Location: ../manage_therapists.php?service_id=" . $service_id . "&error=" . urlencode($e->getMessage()));
}
?>

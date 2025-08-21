<?php
include '../config/config.php';

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header("Location: ../service.php?error=Invalid request method");
        exit();
    }

    $service_id = $_POST['service_id'] ?? 0;
    $title = $_POST['title'] ?? '';
    $status = $_POST['status'] ?? 'active';
    $about_title = $_POST['about_title'] ?? '';
    $about_description = $_POST['about_description'] ?? '';
    $benefits_title = $_POST['benefits_title'] ?? '';
    $benefits_description = $_POST['benefits_description'] ?? '';

    if (!$service_id || empty($title)) {
        header("Location: ../service.php?error=Service ID and title are required");
        exit();
    }

    // Generate slug from title
    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title)));

    // Handle main image upload
    $main_image_update = '';
    if (isset($_FILES['main_image']) && $_FILES['main_image']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = '../images/services/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        
        $file_extension = pathinfo($_FILES['main_image']['name'], PATHINFO_EXTENSION);
        $main_image_name = $slug . '_main_' . time() . '.' . $file_extension;
        $main_image_path = $upload_dir . $main_image_name;
        
        if (move_uploaded_file($_FILES['main_image']['tmp_name'], $main_image_path)) {
            $main_image = 'images/services/' . $main_image_name;
            $main_image_update = ", main_image = ?";
        }
    }

    // Update service
    if ($main_image_update) {
        $stmt = $conn->prepare("UPDATE services SET title = ?, slug = ?, about_title = ?, about_description = ?, benefits_title = ?, benefits_description = ?, status = ?" . $main_image_update . " WHERE id = ?");
        $stmt->bind_param("sssssssi", $title, $slug, $about_title, $about_description, $benefits_title, $benefits_description, $status, $main_image, $service_id);
    } else {
        $stmt = $conn->prepare("UPDATE services SET title = ?, slug = ?, about_title = ?, about_description = ?, benefits_title = ?, benefits_description = ?, status = ? WHERE id = ?");
        $stmt->bind_param("sssssssi", $title, $slug, $about_title, $about_description, $benefits_title, $benefits_description, $status, $service_id);
    }
    
    if ($stmt->execute()) {
        header("Location: ../service.php?success=Service updated successfully");
    } else {
        header("Location: ../service.php?error=Failed to update service");
    }

} catch (Exception $e) {
    header("Location: ../service.php?error=" . urlencode($e->getMessage()));
}
?>

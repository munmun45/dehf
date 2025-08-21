<?php
include '../config/config.php';

header('Content-Type: application/json');

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Invalid request method');
    }

    $title = $_POST['title'] ?? '';
    $status = $_POST['status'] ?? 'active';
    $about_title = $_POST['about_title'] ?? '';
    $about_description = $_POST['about_description'] ?? '';
    $benefits_title = $_POST['benefits_title'] ?? '';
    $benefits_description = $_POST['benefits_description'] ?? '';

    if (empty($title)) {
        throw new Exception('Service title is required');
    }

    // Generate slug from title
    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title)));

    // Handle main image upload
    $main_image = '';
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
        }
    }

    // Insert service
    $stmt = $conn->prepare("INSERT INTO services (title, slug, main_image, about_title, about_description, benefits_title, benefits_description, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssss", $title, $slug, $main_image, $about_title, $about_description, $benefits_title, $benefits_description, $status);
    
    if (!$stmt->execute()) {
        throw new Exception('Failed to insert service');
    }

    $service_id = $conn->insert_id;

    // Handle gallery images
    if (isset($_FILES['gallery_images'])) {
        $upload_dir = '../images/services/gallery/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        foreach ($_FILES['gallery_images']['tmp_name'] as $key => $tmp_name) {
            if ($_FILES['gallery_images']['error'][$key] === UPLOAD_ERR_OK) {
                $file_extension = pathinfo($_FILES['gallery_images']['name'][$key], PATHINFO_EXTENSION);
                $gallery_image_name = $slug . '_gallery_' . time() . '_' . $key . '.' . $file_extension;
                $gallery_image_path = $upload_dir . $gallery_image_name;
                
                if (move_uploaded_file($tmp_name, $gallery_image_path)) {
                    $gallery_image = 'images/services/gallery/' . $gallery_image_name;
                    
                    $stmt = $conn->prepare("INSERT INTO service_images (service_id, image_path, image_type, sort_order) VALUES (?, ?, 'gallery', ?)");
                    $stmt->bind_param("isi", $service_id, $gallery_image, $key);
                    $stmt->execute();
                }
            }
        }
    }

    echo json_encode(['success' => true, 'message' => 'Service added successfully', 'service_id' => $service_id]);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>

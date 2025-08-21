<?php
include '../config/config.php';

header('Content-Type: application/json');

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Invalid request method');
    }

    $title = $_POST['title'] ?? '';
    $description = $_POST['description'] ?? '';
    $category = $_POST['category'] ?? 'general';
    $status = $_POST['status'] ?? 'active';
    $sort_order = $_POST['sort_order'] ?? 0;

    if (empty($title)) {
        throw new Exception('Gallery title is required');
    }

    // Handle image upload
    $image_path = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = '../images/gallery/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        
        $file_extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $image_name = 'gallery_' . time() . '_' . rand(1000, 9999) . '.' . $file_extension;
        $image_full_path = $upload_dir . $image_name;
        
        if (move_uploaded_file($_FILES['image']['tmp_name'], $image_full_path)) {
            $image_path = 'images/gallery/' . $image_name;
        } else {
            throw new Exception('Failed to upload image');
        }
    } else {
        throw new Exception('Image is required');
    }

    // Insert gallery item
    $stmt = $conn->prepare("INSERT INTO gallery (title, description, image_path, category, status, sort_order) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssi", $title, $description, $image_path, $category, $status, $sort_order);
    
    if (!$stmt->execute()) {
        throw new Exception('Failed to insert gallery item');
    }

    $gallery_id = $conn->insert_id;

    echo json_encode(['success' => true, 'message' => 'Gallery image added successfully', 'gallery_id' => $gallery_id]);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>

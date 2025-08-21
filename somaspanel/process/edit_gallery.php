<?php
include '../config/config.php';

header('Content-Type: application/json');

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Invalid request method');
    }

    $gallery_id = $_POST['gallery_id'] ?? 0;
    $title = $_POST['title'] ?? '';
    $description = $_POST['description'] ?? '';
    $category = $_POST['category'] ?? 'general';
    $status = $_POST['status'] ?? 'active';
    $sort_order = $_POST['sort_order'] ?? 0;

    if (empty($gallery_id) || empty($title)) {
        throw new Exception('Gallery ID and title are required');
    }

    // Get current gallery item
    $stmt = $conn->prepare("SELECT * FROM gallery WHERE id = ?");
    $stmt->bind_param("i", $gallery_id);
    $stmt->execute();
    $current = $stmt->get_result()->fetch_assoc();

    if (!$current) {
        throw new Exception('Gallery item not found');
    }

    $image_path = $current['image_path']; // Keep current image by default

    // Handle image upload if new image provided
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = '../images/gallery/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        
        $file_extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $image_name = 'gallery_' . time() . '_' . rand(1000, 9999) . '.' . $file_extension;
        $image_full_path = $upload_dir . $image_name;
        
        if (move_uploaded_file($_FILES['image']['tmp_name'], $image_full_path)) {
            // Delete old image if it exists
            if ($current['image_path'] && file_exists('../' . $current['image_path'])) {
                unlink('../' . $current['image_path']);
            }
            $image_path = 'images/gallery/' . $image_name;
        } else {
            throw new Exception('Failed to upload new image');
        }
    }

    // Update gallery item
    $stmt = $conn->prepare("UPDATE gallery SET title = ?, description = ?, image_path = ?, category = ?, status = ?, sort_order = ? WHERE id = ?");
    $stmt->bind_param("sssssii", $title, $description, $image_path, $category, $status, $sort_order, $gallery_id);
    
    if (!$stmt->execute()) {
        throw new Exception('Failed to update gallery item');
    }

    echo json_encode(['success' => true, 'message' => 'Gallery image updated successfully']);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>

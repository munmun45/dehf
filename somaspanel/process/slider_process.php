<?php
require('../config/config.php');

header('Content-Type: application/json');

$action = $_GET['action'] ?? $_POST['action'] ?? '';

switch ($action) {
    case 'fetch':
        fetchSliders();
        break;
    case 'create':
        createSlider();
        break;
    case 'update':
        updateSlider();
        break;
    case 'delete':
        deleteSlider();
        break;
    default:
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
}

function fetchSliders() {
    global $conn;
    
    $id = $_GET['id'] ?? null;
    
    if ($id) {
        // Fetch single slider
        $stmt = $conn->prepare("SELECT * FROM slider WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $slider = $result->fetch_assoc();
            echo json_encode(['success' => true, 'data' => $slider]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Slider not found']);
        }
    } else {
        // Fetch all sliders
        $query = "SELECT * FROM slider ORDER BY sort_order ASC, created_at DESC";
        $result = $conn->query($query);
        
        $sliders = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $sliders[] = $row;
            }
        }
        
        echo json_encode(['success' => true, 'data' => $sliders]);
    }
}

function createSlider() {
    global $conn;
    
    // Validate required fields
    if (empty($_POST['title'])) {
        echo json_encode(['success' => false, 'message' => 'Title is required']);
        return;
    }
    
    $title = trim($_POST['title']);
    $description = trim($_POST['description'] ?? '');
    $button_text = trim($_POST['button_text'] ?? 'Book a Consultation');
    $button_link = trim($_POST['button_link'] ?? 'contact.php');
    $text_alignment = $_POST['text_alignment'] ?? 'left';
    $status = $_POST['status'] ?? 'active';
    $sort_order = intval($_POST['sort_order'] ?? 0);
    
    // Handle image upload
    $image_path = '';
    if (isset($_FILES['slider_image']) && $_FILES['slider_image']['error'] === UPLOAD_ERR_OK) {
        $image_path = handleImageUpload($_FILES['slider_image']);
        if (!$image_path) {
            echo json_encode(['success' => false, 'message' => 'Failed to upload image']);
            return;
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Image is required for new slider']);
        return;
    }
    
    // Insert into database
    $stmt = $conn->prepare("INSERT INTO slider (title, description, image_path, button_text, button_link, text_alignment, status, sort_order) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssssi", $title, $description, $image_path, $button_text, $button_link, $text_alignment, $status, $sort_order);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Slider created successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to create slider']);
    }
}

function updateSlider() {
    global $conn;
    
    $id = intval($_POST['id']);
    if (!$id) {
        echo json_encode(['success' => false, 'message' => 'Invalid slider ID']);
        return;
    }
    
    // Validate required fields
    if (empty($_POST['title'])) {
        echo json_encode(['success' => false, 'message' => 'Title is required']);
        return;
    }
    
    $title = trim($_POST['title']);
    $description = trim($_POST['description'] ?? '');
    $button_text = trim($_POST['button_text'] ?? 'Book a Consultation');
    $button_link = trim($_POST['button_link'] ?? 'contact.php');
    $text_alignment = $_POST['text_alignment'] ?? 'left';
    $status = $_POST['status'] ?? 'active';
    $sort_order = intval($_POST['sort_order'] ?? 0);
    
    // Handle image upload (optional for update)
    $image_path = null;
    if (isset($_FILES['slider_image']) && $_FILES['slider_image']['error'] === UPLOAD_ERR_OK) {
        $image_path = handleImageUpload($_FILES['slider_image']);
        if (!$image_path) {
            echo json_encode(['success' => false, 'message' => 'Failed to upload image']);
            return;
        }
        
        // Delete old image
        $old_stmt = $conn->prepare("SELECT image_path FROM slider WHERE id = ?");
        $old_stmt->bind_param("i", $id);
        $old_stmt->execute();
        $old_result = $old_stmt->get_result();
        if ($old_result->num_rows > 0) {
            $old_row = $old_result->fetch_assoc();
            $old_image_path = '../' . $old_row['image_path'];
            if (file_exists($old_image_path)) {
                unlink($old_image_path);
            }
        }
    }
    
    // Update database
    if ($image_path) {
        $stmt = $conn->prepare("UPDATE slider SET title = ?, description = ?, image_path = ?, button_text = ?, button_link = ?, text_alignment = ?, status = ?, sort_order = ? WHERE id = ?");
        $stmt->bind_param("sssssssii", $title, $description, $image_path, $button_text, $button_link, $text_alignment, $status, $sort_order, $id);
    } else {
        $stmt = $conn->prepare("UPDATE slider SET title = ?, description = ?, button_text = ?, button_link = ?, text_alignment = ?, status = ?, sort_order = ? WHERE id = ?");
        $stmt->bind_param("ssssssii", $title, $description, $button_text, $button_link, $text_alignment, $status, $sort_order, $id);
    }
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Slider updated successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update slider']);
    }
}

function deleteSlider() {
    global $conn;
    
    $id = intval($_POST['id']);
    if (!$id) {
        echo json_encode(['success' => false, 'message' => 'Invalid slider ID']);
        return;
    }
    
    // Get image path to delete file
    $stmt = $conn->prepare("SELECT image_path FROM slider WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $image_path = '../' . $row['image_path'];
        
        // Delete from database
        $delete_stmt = $conn->prepare("DELETE FROM slider WHERE id = ?");
        $delete_stmt->bind_param("i", $id);
        
        if ($delete_stmt->execute()) {
            // Delete image file
            if (file_exists($image_path)) {
                unlink($image_path);
            }
            echo json_encode(['success' => true, 'message' => 'Slider deleted successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to delete slider']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Slider not found']);
    }
}

function handleImageUpload($file) {
    $target_dir = "../images/slider/";
    
    // Create directory if it doesn't exist
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }
    
    $file_extension = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));
    $allowed_extensions = array("jpg", "jpeg", "png", "gif", "webp");
    
    if (!in_array($file_extension, $allowed_extensions)) {
        return false;
    }
    
    // Generate unique filename
    $new_filename = uniqid() . '_' . time() . '.' . $file_extension;
    $target_file = $target_dir . $new_filename;
    
    if (move_uploaded_file($file["tmp_name"], $target_file)) {
        return "somaspanel/images/slider/" . $new_filename;
    }
    
    return false;
}

$conn->close();
?>

<?php
// Database connection details for XAMPP
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "clients";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form data was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $message = htmlspecialchars($_POST['message']);
    $old_file_path = $_POST['file_path_old'];
    $file_path = $old_file_path;

    // Check if the delete file checkbox is checked
    if (isset($_POST['delete_file']) && $_POST['delete_file'] == '1') {
        if (!empty($old_file_path) && file_exists($old_file_path)) {
            unlink($old_file_path);
        }
        $file_path = "";
    }

    // Handle new file upload
    if (isset($_FILES['file_upload']) && $_FILES['file_upload']['error'] == 0) {
        // Delete old file if a new one is being uploaded
        if (!empty($old_file_path) && file_exists($old_file_path)) {
            unlink($old_file_path);
        }

        $upload_dir = 'uploads/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        $file_name = uniqid() . '_' . basename($_FILES['file_upload']['name']);
        $target_file = $upload_dir . $file_name;

        if (move_uploaded_file($_FILES['file_upload']['tmp_name'], $target_file)) {
            $file_path = $target_file;
        } else {
            // Optional: Handle upload error
        }
    }

    // Prepare and execute the update statement
    $stmt = $conn->prepare("UPDATE client_details SET name=?, email=?, message=?, file_path=? WHERE id=?");
    $stmt->bind_param("ssssi", $name, $email, $message, $file_path, $id);

    if ($stmt->execute()) {
        $message = "Record updated successfully.";
    } else {
        $message = "Error updating record: " . $stmt->error;
    }
    
    $stmt->close();
    $conn->close();

    // Redirect back to the view records page
    header("Location: view_records.php?status=success&message=" . urlencode($message));
    exit();
}
?>
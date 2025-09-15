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

// Check if form data was submitted (or if id is in GET)
if ($_SERVER["REQUEST_METHOD"] == "POST" || isset($_GET['id'])) {
    $id = isset($_POST['id']) ? $_POST['id'] : $_GET['id'];

    // Before deleting the record, get the file path to delete the file
    $stmt_get_path = $conn->prepare("SELECT file_path FROM client_details WHERE id = ?");
    $stmt_get_path->bind_param("i", $id);
    $stmt_get_path->execute();
    $result_path = $stmt_get_path->get_result();
    $row_path = $result_path->fetch_assoc();
    $file_path = $row_path['file_path'];
    $stmt_get_path->close();

    // Delete the file from the server if it exists
    if (!empty($file_path) && file_exists($file_path)) {
        unlink($file_path);
    }

    // Prepare and execute the delete statement
    $stmt = $conn->prepare("DELETE FROM client_details WHERE id=?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        $message = "Record deleted successfully.";
    } else {
        $message = "Error deleting record: " . $stmt->error;
    }
    
    $stmt->close();
    $conn->close();

    // Redirect back to the view records page
    header("Location: view_records.php?status=success&message=" . urlencode($message));
    exit();
}
?>
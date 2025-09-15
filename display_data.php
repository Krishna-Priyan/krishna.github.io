<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thank You</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body class="thank-you-container">
    <div class="thank-you-card">
        <h2>Thank You!</h2>
        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            include 'connection.php';

            $name = isset($_POST['name']) ? htmlspecialchars($_POST['name']) : 'N/A';
            $email = isset($_POST['email']) ? htmlspecialchars($_POST['email']) : 'N/A';
            $message = isset($_POST['message']) ? htmlspecialchars($_POST['message']) : 'N/A';
            
            $file_path = "";
            if (isset($_FILES['file']) && $_FILES['file']['error'] == UPLOAD_ERR_OK) {
                $file_name = $_FILES['file']['name'];
                $file_tmp_name = $_FILES['file']['tmp_name'];
                $upload_dir = 'uploads/';
                
                if (!is_dir($upload_dir)) {
                    mkdir($upload_dir, 0777, true);
                }

                $destination = $upload_dir . uniqid() . '_' . basename($file_name);
                
                if (move_uploaded_file($file_tmp_name, $destination)) {
                    $file_path = $destination;
                }
            }

            $stmt = $conn->prepare("INSERT INTO client_details (name, email, message, file_path) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $name, $email, $message, $file_path);
            $stmt->execute();
            $stmt->close();
            $conn->close();

            ?>
            <p>Your message has been successfully received.</p>
            <div class="submitted-data">
                <p><strong>Name:</strong> <?php echo $name; ?></p>
                <p><strong>Email:</strong> <?php echo $email; ?></p>
                <p><strong>Message:</strong> <?php echo $message; ?></p>
                <?php if (!empty($file_path)) { ?>
                    <p><strong>File Upload:</strong> Successfully uploaded "<?php echo htmlspecialchars($file_name); ?>"</p>
                <?php } else { ?>
                    <p><strong>File Upload:</strong> No file was uploaded.</p>
                <?php } ?>
            </div>
        <?php
        } else {
            echo "<p>No data submitted.</p>";
        }
        ?>
        <a href="index.html" class="return-home-btn">Return To Home</a>
        <a href="view_records.php" class="view-records-btn">View Records</a>
    </div>
</body>
</html>
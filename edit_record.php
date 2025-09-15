<?php
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

// Check if an ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("No record ID provided.");
}

$id = $_GET['id'];

// Fetch the record details
$stmt = $conn->prepare("SELECT id, name, email, message, file_path FROM client_details WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("Record not found.");
}

$row = $result->fetch_assoc();
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Record</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .edit-container {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
            box-sizing: border-box;
        }

        .edit-card {
            background-color: var(--card-bg-color);
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.4);
            border: 1px solid var(--border-color);
            max-width: 600px;
            width: 100%;
        }

        .edit-card form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        
        .edit-card label {
            font-weight: 600;
            color: var(--accent-color);
        }
        
        .edit-card input, .edit-card textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid var(--border-color);
            background-color: var(--main-bg-color);
            color: var(--text-color);
            border-radius: 5px;
            box-sizing: border-box;
        }
        
        .edit-card .button-group {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 20px;
        }

        .edit-card .save-btn, .edit-card .cancel-btn {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            font-weight: bold;
            cursor: pointer;
            transition: transform 0.2s;
        }

        .edit-card .save-btn {
            background-color: var(--accent-color);
            color: var(--main-bg-color);
        }

        .edit-card .cancel-btn {
            background-color: #6c757d;
            color: #fff;
        }

        .edit-card .save-btn:hover, .edit-card .cancel-btn:hover {
            transform: translateY(-2px);
        }
    </style>
</head>
<body class="light-mode">
    <div class="edit-container">
        <div class="edit-card">
            <h2>Edit Record</h2>
            <form action="update_data.php" method="post" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($row['name']); ?>" required>

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($row['email']); ?>" required>

                <label for="message">Message:</label>
                <textarea id="message" name="message" required><?php echo htmlspecialchars($row['message']); ?></textarea>

                <label>Current File:</label>
                <?php if (!empty($row['file_path'])): ?>
                    <div><a href="<?php echo htmlspecialchars($row['file_path']); ?>" target="_blank">View File</a></div>
                    <label style="margin-top: 5px;"><input type="checkbox" name="delete_file" value="1"> Delete Current File</label>
                <?php else: ?>
                    <p>No file attached.</p>
                <?php endif; ?>
                <input type="hidden" name="file_path_old" value="<?php echo htmlspecialchars($row['file_path']); ?>">
                
                <label for="file_upload" style="margin-top: 15px;">Upload New File (Optional):</label>
                <input type="file" id="file_upload" name="file_upload">

                <div class="button-group">
                    <button type="button" class="cancel-btn" onclick="window.location.href='view_records.php'">Cancel</button>
                    <button type="submit" class="save-btn">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
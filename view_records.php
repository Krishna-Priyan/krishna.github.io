<?php
include 'connection.php';

$sql = "SELECT id, name, email, message, file_path FROM client_details";
$result = $conn->query($sql);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Records</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body class="light-mode">
    <div class="view-records-container content-wrapper">
        <h2>Submitted Records</h2>
        <?php
        if (isset($_GET['status']) && $_GET['status'] == 'success') {
            echo "<p style='color: green;'><strong>" . htmlspecialchars($_GET['message']) . "</strong></p>";
        }

        if ($result->num_rows > 0) {
            echo "<table class='record-table'><thead><tr><th>ID</th><th>Name</th><th>Email</th><th>Message</th><th>File</th><th>Actions</th></tr></thead><tbody>";
            while($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row["id"] . "</td>";
                echo "<td>" . htmlspecialchars($row["name"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["email"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["message"]) . "</td>";
                echo "<td>";
                if (!empty($row["file_path"])) {
                    echo "<a href='" . htmlspecialchars($row["file_path"]) . "' target='_blank'>View File</a>";
                } else {
                    echo "N/A";
                }
                echo "</td>";
                echo "<td class='record-actions'>";
                echo "<a href='edit_record.php?id=" . $row["id"] . "' target='_blank'>Edit</a>";
                echo " | <a href='delete_data.php?id=" . $row["id"] . "' onclick='return confirm(\"Are you sure you want to delete this record?\")' class='delete'>Delete</a>";
                echo "</td>";
                echo "</tr>";
            }
            echo "</tbody></table>";
        } else {
            echo "<p>No records found.</p>";
        }
        $conn->close();
        ?>
        <br>
        <a href="index.html" class="return-home-btn">Return To Home</a>
    </div>
</body>
</html>
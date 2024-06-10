<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Management System</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Post Your Event</h1>
        <form id="eventForm" action="index.php" method="POST" enctype="multipart/form-data">
            <div>
                <label for="userType">User Type:</label>
                <select id="userType" name="userType">
                    <option value="free">Free</option>
                    <option value="featured">Featured</option>
                </select>
            </div>
            <div>
                <label for="eventTitle">Event Title:</label>
                <input type="text" id="eventTitle" name="eventTitle" required>
            </div>
            <div>
                <label for="eventDescription">Event Description:</label>
                <textarea id="eventDescription" name="eventDescription" required></textarea>
            </div>
            <div>
                <label for="eventDate">Event Date:</label>
                <input type="date" id="eventDate" name="eventDate" required>
            </div>
            <div>
                <label for="eventPictures">Event Pictures:</label>
                <input type="file" id="eventPictures" name="eventPictures[]" multiple accept="image/*">
            </div>
            <div id="videoUpload" style="display: none;">
                <label for="eventVideo">Event Video (Max 100MB):</label>
                <input type="file" id="eventVideo" name="eventVideo" accept="video/*">
            </div>
            <div>
                <button type="submit">Post Event</button>
            </div>
        </form>
    </div>

    <script src="script.js"></script>
</body>
</html>

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $userType = $_POST['userType'];
    $eventTitle = $_POST['eventTitle'];
    $eventDescription = $_POST['eventDescription'];
    $eventDate = $_POST['eventDate'];

    // Define upload limits
    $maxPics = $userType === 'featured' ? 100 : 10;
    $maxVideoSize = 100 * 1024 * 1024; // 100MB in bytes
    $uploadDir = 'uploads/';

    // Create uploads directory if it doesn't exist
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $uploadedPictures = [];

    // Handle picture uploads
    if (isset($_FILES['eventPictures'])) {
        if (count($_FILES['eventPictures']['tmp_name']) > $maxPics) {
            die('Exceeded maximum number of picture uploads.');
        }

        foreach ($_FILES['eventPictures']['tmp_name'] as $key => $tmp_name) {
            $fileName = basename($_FILES['eventPictures']['name'][$key]);
            $targetFilePath = $uploadDir . $fileName;
            if (move_uploaded_file($tmp_name, $targetFilePath)) {
                $uploadedPictures[] = $targetFilePath;
            }
        }
    }

    // Handle video upload for featured users
    $uploadedVideo = '';
    if ($userType === 'featured' && isset($_FILES['eventVideo'])) {
        if ($_FILES['eventVideo']['size'] > $maxVideoSize) {
            die('Video file exceeds the maximum allowed size of 100MB.');
        }
        $videoName = basename($_FILES['eventVideo']['name']);
        $targetFilePath = $uploadDir . $videoName;
        if (move_uploaded_file($_FILES['eventVideo']['tmp_name'], $targetFilePath)) {
            $uploadedVideo = $targetFilePath;
        }
    }

    // Save event details to database
    $conn = new mysqli('localhost', 'root', '', 'event_management');
    if ($conn->connect_error) {
        die('Connection failed: ' . $conn->connect_error);
    }

    $pictures = implode(',', $uploadedPictures);
    $sql = "INSERT INTO events (user_type, title, description, event_date, pictures, video)
            VALUES ('$userType', '$eventTitle', '$eventDescription', '$eventDate', '$pictures', '$uploadedVideo')";

    if ($conn->query($sql) === TRUE) {
        echo "Event posted successfully.";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>

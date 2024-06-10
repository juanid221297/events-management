<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $userType = $_POST['userType'];
    $eventTitle = $_POST['eventTitle'];
    $eventDescription = $_POST['eventDescription'];
    $eventDate = $_POST['eventDate'];

    // Define upload limits
    $maxPics = $userType === 'featured' ? 100 : 10;
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
        $videoName = basename($_FILES['eventVideo']['name']);
        $targetFilePath = $uploadDir . $videoName;
        if (move_uploaded_file($_FILES['eventVideo']['tmp_name'], $targetFilePath)) {
            $uploadedVideo = $targetFilePath;
        }
    }

    // Save event details to database (example code, replace with actual database code)
    // Note: Ensure you have a database and table set up for storing event details.
    $conn = new mysqli('localhost', 'root', '', 'event_management');
    if ($conn->connect_error) {
        die('Connection failed: ' . $conn->connect_error);
    }

    $sql = "INSERT INTO events (user_type, title, description, event_date, pictures, video)
            VALUES ('$userType', '$eventTitle', '$eventDescription', '$eventDate', '" . implode(',', $uploadedPictures) . "', '$uploadedVideo')";

    if ($conn->query($sql) === TRUE) {
        echo "Event posted successfully.";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>

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
          <!-- HTML -->
<div>
    <label for="userType">User Type:</label>
    <select id="userType" name="userType">
        <option value="free">Free</option>
        <option value="featured">Featured</option>
    </select>
</div>
<div id="paymentMessage" style="display: none; color: red;">
    Please pay the required amount to access the featured option.
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
            <!-- HTML -->
<div>
    <label for="city">City:</label>
    <select id="city" name="city">
        <option value="">Select City</option>
        <option value="Lahore">Lahore</option>
        <option value="Karachi">Karachi</option>
        <option value="Islamabad">Islamabad</option>
        <!-- Add city options here -->
    </select>
</div>
<div>
    <label for="location">Location:</label>
    <select id="location" name="location" disabled>
        <option value="">Select Location</option>
        <option value="gajju mattah"> gajju mattah</option>
        <!-- Location options will be populated dynamically -->
    </select>
</div>

            <div>
                <label for="eventCategory">Event Category:</label>
                <select id="eventCategory" name="eventCategory">
                    <option value="Music">Music</option>
                    <option value="Sports">Sports</option>
                    <option value="Business">Business</option>
                    <option value="Education">Education</option>
                    <!-- Add more options as needed -->
                </select>
            </div>
            <div>
                <label for="eventTags">Event Tags:</label>
                <input type="text" id="eventTags" name="eventTags" placeholder="Enter tags separated by commas">
            </div>
            <div>
                <label for="eventCapacity">Event Capacity:</label>
                <input type="number" id="eventCapacity" name="eventCapacity" min="1">
            </div>
            <div>
                <label for="eventPrice">Event Price:</label>
                <input type="number" id="eventPrice" name="eventPrice" min="0" step="0.01">
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
    $eventLocation = $_POST['eventLocation'];
    $eventCategory = $_POST['eventCategory'];
    $eventTags = $_POST['eventTags'];
    $eventCapacity = $_POST['eventCapacity'];
    $eventPrice = $_POST['eventPrice'];

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
    $sql = "INSERT INTO events (user_type, title, description, event_date, location, category, tags, capacity, price, pictures, video)
            VALUES ('$userType', '$eventTitle', '$eventDescription', '$eventDate', '$eventLocation', '$eventCategory', '$eventTags', '$eventCapacity', '$eventPrice', '$pictures', '$uploadedVideo')";

    if ($conn->query($sql) === TRUE) {
        echo "Event posted successfully.";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>

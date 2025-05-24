<?php
// Database connection settings
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'smart_garbage';

// Create connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Sanitize input
$name = htmlspecialchars(trim($_POST['name']));
$email = htmlspecialchars(trim($_POST['email']));
$message = htmlspecialchars(trim($_POST['message']));
$time = date('Y-m-d H:i:s');

// Use 'name' instead of 'sender'
$sql = "INSERT INTO messages (name, email, message, time) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("Prepare failed: (" . $conn->errno . ") " . $conn->error);
}

$stmt->bind_param("ssss", $name, $email, $message, $time);

if ($stmt->execute()) {
    echo "<script>alert('Message sent successfully!'); window.location.href = 'contact.html';</script>";
} else {
    echo "<script>alert('Failed to send message.'); window.location.href = 'contact.html';</script>";
}

$stmt->close();
$conn->close();
?>

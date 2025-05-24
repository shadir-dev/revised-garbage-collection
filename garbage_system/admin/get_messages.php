<?php
header('Content-Type: application/json');

// Database connection settings
$host = 'localhost';
$db   = 'smart_garbage';
$user = 'root';
$pass = '';

$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    echo json_encode(["error" => "Connection failed: " . $conn->connect_error]);
    exit;
}

// Fetch messages
$sql = "SELECT name, message, time, email FROM messages ORDER BY id DESC";
$result = $conn->query($sql);

$messages = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $messages[] = [
            "sender" => $row['name'],
            "content" => $row['message'],
            "time" => $row['time'],
            "email" => $row['email']
        ];
    }
}

echo json_encode($messages);
$conn->close();
?>

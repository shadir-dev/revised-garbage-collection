<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['username'])) {
    echo json_encode(['error' => 'Not logged in']);
    exit;
}

require_once 'db.php'; // Database connection

$username = $_SESSION['username'];

// Get the user's email from the registration table
$userQuery = $conn->prepare("SELECT email FROM registration WHERE username = ?");
$userQuery->bind_param("s", $username);
$userQuery->execute();
$userResult = $userQuery->get_result();

if ($userResult->num_rows === 0) {
    echo json_encode(['error' => 'User not found']);
    exit;
}

$userData = $userResult->fetch_assoc();
$email = $userData['email'];

// Fetch garbage request updates based on email
$sql = "SELECT id, status, updated_at 
        FROM garbage_requests 
        WHERE email = ? 
        ORDER BY updated_at DESC 
        LIMIT 5";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

$notifications = [];

while ($row = $result->fetch_assoc()) {
    $requestId = $row['id'];
    $status = ucfirst($row['status']);
    $updatedAt = date("M j, Y H:i", strtotime($row['updated_at']));

    $notifications[] = [
        'id' => $requestId,
        'message' => "Request #$requestId updated: $status",
        'timestamp' => $updatedAt
    ];
}

echo json_encode($notifications);
?>

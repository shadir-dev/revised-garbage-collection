<?php
header('Content-Type: application/json');

$host = "localhost";
$user = "root";
$password = ""; // Change this if your MySQL has a password
$dbname = "smart_garbage";

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    echo json_encode(["error" => "Connection failed"]);
    exit();
}

// Adjust status values if your DB uses different casing like 'Pending' or 'Complete'
$total_requests = $conn->query("SELECT COUNT(*) AS count FROM garbage_requests")->fetch_assoc()['count'];
$pending_requests = $conn->query("SELECT COUNT(*) AS count FROM garbage_requests WHERE status = 'pending'")->fetch_assoc()['count'];
$completed_requests = $conn->query("SELECT COUNT(*) AS count FROM garbage_requests WHERE status = 'completed'")->fetch_assoc()['count'];
$total_trucks = $conn->query("SELECT COUNT(*) AS count FROM trucks")->fetch_assoc()['count'];

echo json_encode([
    "total_requests" => $total_requests,
    "pending_requests" => $pending_requests,
    "completed_requests" => $completed_requests,
    "total_trucks" => $total_trucks
]);

$conn->close();
?>

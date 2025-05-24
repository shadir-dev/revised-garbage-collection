<?php
header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["query"])) {
    $query = trim($_POST["query"]);

    // Database connection
    $conn = new mysqli("localhost", "root", "", "smart_garbage");

    if ($conn->connect_error) {
        echo json_encode(["status" => "error", "message" => "Database connection failed"]);
        exit();
    }

    // SQL statement
    $stmt = $conn->prepare("SELECT id, name, email, status, truck_number, assigned_at, completed_at, location FROM garbage_requests WHERE email = ? OR id = ?");
    $stmt->bind_param("si", $query, $query);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check result
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Format times if available
        $assignedAt = !empty($row["assigned_at"]) ? date("Y-m-d H:i:s", strtotime($row["assigned_at"])) : "Not yet assigned";
        $completedAt = !empty($row["completed_at"]) ? date("Y-m-d H:i:s", strtotime($row["completed_at"])) : "Not yet completed";

        echo json_encode([
            "status" =>$row ["status"],
            "request_status" => $row["status"],
            "truck" => $row["truck_number"],
            "assigned_at" => $assignedAt,
            "completed_at" => $completedAt,
            "completed_for" => $row["name"],
            "location" => $row["location"]
        ]);
    } else {
        echo json_encode(["status" => "not_found"]);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request"]);
}
?>

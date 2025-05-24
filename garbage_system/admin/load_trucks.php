<?php
// DB Connection (inside same file)
$host = 'localhost';
$user = 'root'; // your DB username
$password = ''; // your DB password
$dbname = 'smart_garbage'; // your DB name

$conn = new mysqli($host, $user, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch trucks with assigned or in-progress requests
$sql = "
    SELECT 
        t.id, 
        t.truck_number, 
        t.full_name, 
        t.availability_status, 
        gr.location, 
        gr.assigned_at,
        gr.completed_at
    FROM trucks t
    LEFT JOIN garbage_requests gr 
        ON t.id = gr.truck_id AND gr.status IN ('assigned', 'in_progress', 'completed')
    ORDER BY gr.assigned_at DESC
";

$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>{$row['id']}</td>";
        echo "<td>{$row['truck_number']}</td>";
        echo "<td>{$row['full_name']}</td>";
        echo "<td>{$row['availability_status']}</td>";
        echo "<td>{$row['location']}</td>";
        echo "<td>{$row['assigned_at']}</td>";
        echo "<td>{$row['completed_at']}</td>"; 
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='7'>No active truck assignments found.</td></tr>"; // colspan changed to 7
}

?>

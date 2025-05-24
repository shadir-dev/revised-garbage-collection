<?php
$connection = new mysqli("localhost", "root", "", "smart_garbage");

if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
    
}

$sql = "SELECT id, name,email, location, waste_type, status FROM garbage_requests";
$result = $connection->query($sql);

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row["id"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["name"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["email"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["location"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["waste_type"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["status"]) . "</td>";
        
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='6'>No pickup requests found.</td></tr>";
    
}

$connection->close();
?>

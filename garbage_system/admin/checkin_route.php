<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "smart_garbage";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$truck_number = $_POST['truck_number'] ?? '';
$name = $_POST['name'] ?? '';
$location = $_POST['location'] ?? 'Unknown location';
$latitude = $_POST['latitude'] ?? '';
$longitude = $_POST['longitude'] ?? '';
$checkin_time = date("Y-m-d H:i:s");

// 1. Check if truck exists
$check_truck = $conn->prepare("SELECT * FROM trucks WHERE truck_number = ?");
if ($check_truck === false) {
    die('Error preparing the check_truck query: ' . $conn->error);
}
$check_truck->bind_param("s", $truck_number);
$check_truck->execute();
$result = $check_truck->get_result();

if ($result->num_rows > 0) {
    // 2. Update status to 'Checked In'
    $update = $conn->prepare("UPDATE trucks SET availability_status='Checked In' WHERE truck_number=?");
    if ($update === false) {
        die('Error preparing the update query: ' . $conn->error);
    }
    $update->bind_param("s", $truck_number);
    $update->execute();
} else {
    echo "❌ Truck not found. Check your truck number.";
    exit;
}

// 3. Check if the truck is already assigned
$check_assigned = $conn->prepare("SELECT * FROM garbage_requests WHERE truck_number = ? AND status = 'Assigned'");
if ($check_assigned === false) {
    die('Error preparing the check_assigned query: ' . $conn->error);
}
$check_assigned->bind_param("s", $truck_number);
$check_assigned->execute();
$assigned_result = $check_assigned->get_result();

if ($assigned_result->num_rows > 0) {
    $row = $assigned_result->fetch_assoc();
    echo "✅ Already Assigned!<br>";
    echo "📍 Assigned to: <strong>{$row['full_name']}</strong><br>";
    echo "🗺️ Location: <strong>{$row['location']}</strong><br>";
    echo "🕓 Time: {$checkin_time}<br>";
} else {
    // 4. Assign to new pending request
    // Assuming the correct column name for the date/time is `created_at` (replace it with the actual column name)
    $pending = $conn->query("SELECT * FROM garbage_requests WHERE status = 'Pending' ORDER BY request_time ASC LIMIT 1");

    if ($pending === false) {
        die('Error executing the pending request query: ' . $conn->error); // Error handling
    }

    if ($pending->num_rows > 0) { // Check if there are pending requests
        $req = $pending->fetch_assoc();
        $name = $req['name'];  // Assuming `full_name` is the column name
        $location = $req['location'];
        $id = $req['id'];

        // Insert truck assignment
        $assign = $conn->prepare("INSERT INTO truck_assignments (truck_number, full_name, location, status, assigned_at, checkin_time) VALUES (?, ?, ?, 'Assigned', ?, ?)");
        if ($assign === false) {
            die('Error preparing the assign query: ' . $conn->error);
        }
        $assign->bind_param("sssss", $truck_number, $full_name, $location, $checkin_time, $checkin_time);
        $assign->execute();

        // Update garbage request status
        $update_req = $conn->prepare("UPDATE garbage_requests SET status = 'Assigned' WHERE id = ?");
        if ($update_req === false) {
            die('Error preparing the update_req query: ' . $conn->error);
        }
        $update_req->bind_param("i", $id);
        $update_req->execute();

        echo "✅ Check-in & Assignment Successful!<br>";
        echo "📍 Assigned to: <strong>{$name}</strong><br>";
        echo "🗺️ Location: <strong>{$location}</strong><br>";
        echo "🕓 Assigned At: {$checkin_time}<br>";
    } else {
        echo "✅ Check-in successful!<br>No pending requests to assign.";
    }
}

$conn->close();
?>

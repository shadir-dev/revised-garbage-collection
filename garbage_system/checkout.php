<?php
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $truck_number = $_POST['truck_number'];
    $latitude = $_POST['latitude'];
    $longitude = $_POST['longitude'];
    $location = $_POST['location'];
    $checkout_time = date('Y-m-d H:i:s');

    // Get active assignment
    $assignQuery = $conn->prepare("SELECT * FROM truck_assignments WHERE truck_number = ? AND status = 'In Progress' LIMIT 1");
    $assignQuery->bind_param("s", $truck_number);
    $assignQuery->execute();
    $assignResult = $assignQuery->get_result();

    if ($assignResult->num_rows === 1) {
        $assignment = $assignResult->fetch_assoc();
        $id = $assignment['id'];
        $id = $assignment['id'];
        $id = $assignment['id'];

        // Mark assignment as completed
        $updateAssign = $conn->prepare("UPDATE truck_assignments SET checkout_time = ?, status = 'Completed' WHERE id = ?");
        $updateAssign->bind_param("si", $checkout_time, $assignment_id);
        $updateAssign->execute();

        // Update request status
        $conn->query("UPDATE garbage_requests truck_number, SET status = 'Completed', completed_at = '$checkout_time' WHERE id = $id");

        // Make truck available again
        $conn->query("UPDATE trucks SET availability_status = 'Available' WHERE id = $id");

        echo "✅ Check-out successful at $location ($checkout_time). Good job!";
    } else {
        echo "❌ No active assignment found for this truck.";
    }
} else {
    echo "Invalid request method.";
}
?>

<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "smart_garbage";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

// Sanitize input
$name = mysqli_real_escape_string($conn, $_POST['name']);
$email = mysqli_real_escape_string($conn, $_POST['email']);
$location = mysqli_real_escape_string($conn, $_POST['location']);
$waste_type = mysqli_real_escape_string($conn, $_POST['waste-type']);
$preferred_time = mysqli_real_escape_string($conn, $_POST['preferred-time']);
$contact_info = mysqli_real_escape_string($conn, $_POST['contact-info']);

// Compose email header from user email
$headers = "From: alshadir3@gmail.com\r\n";
$headers .= "Reply-To: $email\r\n";
$headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

// Check for available truck
$truck_query = "SELECT * FROM trucks WHERE availability = 'available' LIMIT 1";
$truck_result = $conn->query($truck_query);

if ($truck_result && $truck_result->num_rows > 0) {
    $truck = $truck_result->fetch_assoc();
    $truck_id = $truck['id'];

    // Assign truck to request
    $insert_request = "INSERT INTO garbage_requests (name, email, location, waste_type, preferred_time, contact_info, truck_id, status)
                       VALUES ('$name', '$email', '$location', '$waste_type', '$preferred_time', '$contact_info', '$truck_id', 'Assigned')";
    if ($conn->query($insert_request)) {
        $request_id = $conn->insert_id;

        // Update truck availability
        $conn->query("UPDATE trucks SET availability = 'assigned' WHERE id = $truck_id");

        // Send email
        $subject = "Garbage Collection Request [ID: $request_id]";
        $message = "Hi $name,\n\nYour garbage collection request has been received and a truck has been assigned.\n\n" .
                   "Request ID: $request_id\nWaste Type: $waste_type\nPreferred Time: $preferred_time\n\n" .
                   "We’ll update you once pickup is completed. Thank you for using our service.\n\n- Garbage Collection Team";

        mail($email, $subject, $message, $headers);

        echo "<script>alert('Request submitted successfully!\\nRequest ID: $request_id'); window.location.href='view_status.html';</script>";
    }
} else {
    // No trucks available
    $insert_request = "INSERT INTO garbage_requests (name, email, location, waste_type, preferred_time, contact_info, status)
                       VALUES ('$name', '$email', '$location', '$waste_type', '$preferred_time', '$contact_info', 'Pending')";
    if ($conn->query($insert_request)) {
        $request_id = $conn->insert_id;

        // Send email
        $subject = "Garbage Collection Request [ID: $request_id]";
        $message = "Hi $name,\n\nYour garbage collection request has been received. Currently, no trucks are available.\n\n" .
                   "Request ID: $request_id\nWaste Type: $waste_type\nPreferred Time: $preferred_time\n\n" .
                   "We’ll notify you once a truck becomes available. Thank you for your patience.\n\n- Garbage Collection Team";

        mail($email, $subject, $message, $headers);

        echo "<script>alert('No trucks available at the moment.\\nYour Request ID: $request_id'); window.location.href='view_status.html';</script>";
    }
}

$conn->close();
?>

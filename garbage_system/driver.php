<?php
// 1. Database connection settings
$servername = "localhost";
$username = "root"; // Default in XAMPP
$password = "";     // Default is empty in XAMPP
$dbname = "smart_garbage"; // Replace with your actual DB name

// 2. Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// 3. Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// 4. Get form data
$full_name = $_POST['full_name'];
$email = $_POST['email'];
$username = $_POST['username'];
$password = $_POST['password'];
$confirm_password = $_POST['confirm_password'];
$truck_number = $_POST['truck_number'];

// 5. Validate password match
if ($password !== $confirm_password) {
    die("Passwords do not match. <a href='driver_registration.html'>Try again</a>");
}

// 6. Hash password
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// 7. Check if email, username, or truck_number already exists
$check_sql = "SELECT * FROM trucks WHERE email=? OR username=? OR truck_number=?";
$stmt = $conn->prepare($check_sql);
$stmt->bind_param("sss", $email, $username, $truck_number);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo "Email, username, or truck number already exists. <a href='driver_register.html'>Go back</a>";
    exit;
}
$stmt->close(); // Close check statement

// 8. Insert into trucks table
$insert_truck = $conn->prepare("INSERT INTO trucks (full_name, email, username, password, truck_number) VALUES (?, ?, ?, ?, ?)");
$insert_truck->bind_param("sssss", $full_name, $email, $username, $hashed_password, $truck_number);

// 9. Insert into users table
$insert_user = $conn->prepare("INSERT INTO users (username, email, role, status, last_activity) VALUES (?, ?, ?, ?, ?)");

// Default values for user table
$role = "driver";
$status = "active";
$last_activity = date("Y-m-d H:i:s");
$insert_user->bind_param("sssss", $username, $email, $role, $status, $last_activity);

// 10. Execute both inserts
if ($insert_truck->execute() && $insert_user->execute()) {
    echo "Registration successful. <a href='driver_login.html'>Login here</a>";
} else {
    echo "Error: " . $conn->error;
}

// 11. Cleanup
$insert_truck->close();
$insert_user->close();
$conn->close();
?>

<?php
// Database connection
$host = "localhost";
$user = "root";
$pass = "";
$db = "smart_garbage";

$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Sanitize input
function clean_input($data) {
    return htmlspecialchars(trim($data));
}

// Handle login
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = clean_input($_POST["username"]);
    $password = $_POST["password"]; // Don't sanitize passwords to preserve special characters

    // Prepare and execute the query
    $stmt = $conn->prepare("SELECT id, password FROM trucks WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();

    $stmt->store_result();

    if ($stmt->num_rows == 1) {
        // Bind the result to variables
        $stmt->bind_result($user_id, $hashed_password);
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {
            // Success - create session
            session_start();
            $_SESSION["user_id"] = $user_id;
            $_SESSION["username"] = $username;

            echo "<script>alert('Login successful!'); window.location.href='truck_driver.php';</script>";
        } else {
            echo "<script>alert('Incorrect password'); window.history.back();</script>";
        }
    } else {
        echo "<script>alert('Username not found'); window.history.back();</script>";
    }

    $stmt->close();
}
$conn->close();
?>

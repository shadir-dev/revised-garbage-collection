<?php
// ✅ DB credentials
$host = "localhost";
$user = "root";
$pass = "";
$db = "smart_garbage";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

function sanitize_input($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullName = sanitize_input($_POST["full_name"]);
    $emailAddress = sanitize_input($_POST["email"]);
    $username = sanitize_input($_POST["username"]);
    $password = $_POST["password"];
    $confirmPassword = $_POST["confirm_password"];

    $isValid = true;
    $errors = [];

    // Check existing email
    $stmt = $conn->prepare("SELECT email FROM users WHERE email = ?");
    $stmt->bind_param("s", $emailAddress);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        $isValid = false;
        $errors[] = "Email already exists.";
    }
    $stmt->close();

    // Check existing username in registration table
    $stmt = $conn->prepare("SELECT username FROM registration WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        $isValid = false;
        $errors[] = "Username already taken.";
    }
    $stmt->close();

    // Validate
    if (!preg_match("/^[a-zA-Z\s]+$/", $fullName)) {
        $isValid = false;
        $errors[] = "Full name must contain only letters and spaces.";
    }

    if (!filter_var($emailAddress, FILTER_VALIDATE_EMAIL)) {
        $isValid = false;
        $errors[] = "Invalid email format.";
    }

    if (!preg_match("/^[a-zA-Z0-9_]+$/", $username)) {
        $isValid = false;
        $errors[] = "Username must contain only letters, numbers, or underscores.";
    }

    if (strlen($password) < 6) {
        $isValid = false;
        $errors[] = "Password must be at least 6 characters.";
    }

    if ($password !== $confirmPassword) {
        $isValid = false;
        $errors[] = "Passwords do not match.";
    }

    // ✅ Insert into DB
    if ($isValid) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Insert into admin table
        $stmt1 = $conn->prepare("INSERT INTO registration (full_name, email, username, password) VALUES (?, ?, ?, ?)");
        $stmt1->bind_param("ssss", $fullName, $emailAddress, $username, $hashedPassword);

        // Prepare user table values
        $role = "client";
        $status = "active";
        $last_activity = date("Y-m-d H:i:s");

        // Insert into users table
        $stmt2 = $conn->prepare("INSERT INTO users (username, email, role, status, last_activity) VALUES (?, ?, ?, ?, ?)");
        $stmt2->bind_param("sssss", $username, $emailAddress, $role, $status, $last_activity);

        // Execute both
        if ($stmt1->execute() && $stmt2->execute()) {
            echo "<script>alert('Registration successful! Please log in.'); window.location.href='login.html';</script>";
        } else {
            echo "<script>alert('❌ Registration failed: " . $stmt1->error . " / " . $stmt2->error . "');</script>";
        }

        $stmt1->close();
        $stmt2->close();
    } else {
        echo "<h3>❌ Errors:</h3><ul>";
        foreach ($errors as $error) {
            echo "<li>" . htmlspecialchars($error) . "</li>";
        }
        echo "</ul>";
    }
}
$conn->close();
?>

<?php
session_start();



// Process the registration if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the form inputs
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $role = $_POST['role'];

    // Validate the inputs
    if ($password !== $confirm_password) {
        echo "Passwords do not match!";
        exit;
    }

    // Hash the password before saving to database
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Database connection
    $conn = new mysqli("localhost", "root", "", "smart_garbage");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Insert the new admin into the database
    $sql = "INSERT INTO admin (full_name, email, username, password, role) 
            VALUES ('$full_name', '$email', '$username', '$hashed_password', '$role')";

    if ($conn->query($sql) === TRUE) {
        // Redirect to admin login page after successful registration
        header("Location: admin_login.php");
        exit;
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Register</title>
  <style>
    button {
      margin-top: 10px;
      color: white;
    }
    a  {
      text-decoration: none;
      color: aliceblue;
    }
    body {
      margin: 0;
      padding: 0;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: linear-gradient(to right, #4caf50, #2e7d32);
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }

    .register-container {
      background-color: white;
      padding: 40px;
      border-radius: 10px;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
      width: 350px;
    }

    h1 {
      text-align: center;
      margin-bottom: 25px;
      margin-top: 250px;
      color: #2e7d32;
    }

    label {
      display: block;
      margin-bottom: 6px;
      font-weight: 500;
    }

    input[type="text"],
    input[type="email"],
    input[type="password"] {
      width: 100%;
      padding: 10px;
      margin-bottom: 15px;
      border: 1px solid #ccc;
      border-radius: 6px;
      font-size: 1rem;
    }

    button {
      width: 100%;
      background-color: #4caf50;
      color: white;
      padding: 10px;
      border: none;
      border-radius: 6px;
      font-size: 1rem;
      cursor: pointer;
      transition: background 0.3s;
    }

    button:hover {
      background-color: #388e3c;
    }

    .login-link {
      text-align: center;
      margin-top: 15px;
    }

    .login-link a {
      color: #2e7d32;
      text-decoration: none;
    }

    .login-link a:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>

<div class="register-container">
  <h1>Register</h1>
  <form method="POST" action="admin_register.php">
    <label>Full Name:</label><br>
    <input type="text" name="full_name" required><br><br>

    <label>Email Address:</label><br>
    <input type="email" name="email" required><br><br>

    <label>Username:</label><br>
    <input type="text" name="username" required><br><br>

    <label>Password:</label><br>
    <input type="password" name="password" required><br><br>

    <label>Confirm Password:</label><br>
    <input type="password" name="confirm_password" required><br><br>

    <label>Role:</label><br>
    <input type="text" name="role" value="admin"> 

    <button type="submit">Register</button>
    <button><a href="admin_login.php">Login</a></button>
  </form>
</div>

</body>
</html>

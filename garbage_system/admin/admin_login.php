<?php
session_start(); // Start session to track user login status

// Redirect to admin dashboard if already logged in
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header("Location: admin_index.html");
    exit;
}

// Process the login if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize and validate user inputs
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Check if the fields are empty
    if (empty($username) || empty($password)) {
        echo "Username and password are required!";
        exit;
    }

    // Database connection
    $conn = new mysqli("localhost", "root", "", "smart_garbage");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Use prepared statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT * FROM admin WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if user exists
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Verify the password using password_verify
        if (password_verify($password, $row['password']))
         {
            // Set session variables to track login status
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_username'] = $row['username'];

            // Redirect to admin dashboard after successful login
            header("Location: admin_index.html");
            exit;
        } else {
            echo "Invalid username or password.";
        }
    } else {
        echo "No such user found.";
    }

    // Close the database connection
    $conn->close();
}
?>

<!-- HTML Form for Login -->
<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <style>
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

        .login-container {
            background-color: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.2);
            width: 300px;
        }

        h1 {
            text-align: center;
            margin-bottom: 25px;
            color: #2e7d32;
        }

        label {
            display: block;
            margin-bottom: 6px;
            font-weight: 500;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 1rem;
        }

        button {
            margin-top: 10px;
            color: white;
        }

        a {
            text-decoration: none;
            color: aliceblue;
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
    </style>
</head>
<body>

    <div class="login-container">
        <h1>Login</h1>
        <form method="POST" action="admin_login.php">
            <label>Username:</label><br>
            <input type="text" name="username" required><br><br>
          
            <label>Password:</label><br>
            <input type="password" name="password" required><br><br>
          
            <button type="submit">Login</button>
            
            <!-- Register button (using anchor tag or JS redirection) -->
            <a href="admin_register.php">
                <button type="button">Register</button>
            </a>
        </form>
    </div>

</body>
</html>

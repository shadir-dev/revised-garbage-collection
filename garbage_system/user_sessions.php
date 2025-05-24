<?php
$conn = new mysqli('localhost', 'root', '', 'smart_garbage');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to insert into users table
function insertUser($conn, $name, $email, $role = 'user') {
    // Check if user already exists
    $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $check->store_result();
    
    if ($check->num_rows === 0) {
        $stmt = $conn->prepare("INSERT INTO users (name, email, role) VALUES (?, ?, ?)");
        if ($stmt) {
            $stmt->bind_param("sss", $name, $email, $role);
            if ($stmt->execute()) {
                echo "✅ Inserted: $name ($email) as $role<br>";
            } else {
                echo "❌ Failed to insert $email: " . $stmt->error . "<br>";
            }
            $stmt->close();
        } else {
            echo "❌ Prepare failed for insert: " . $conn->error . "<br>";
        }
    } else {
        echo "⚠️ User with email $email already exists<br>";
    }

    $check->close();
}

// From admin table
$result = $conn->query("SELECT full_name, email FROM admin");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        insertUser($conn, $row['full_name'], $row['email'], 'admin');
    }
} else {
    echo "❌ Query failed on admin: " . $conn->error . "<br>";
}

// From registration table
$result = $conn->query("SELECT full_name, email FROM registration");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        insertUser($conn, $row['full_name'], $row['email'], 'user');
    }
} else {
    echo "❌ Query failed on registration: " . $conn->error . "<br>";
}

// From trucks table
$result = $conn->query("SELECT full_name AS name, email FROM trucks WHERE email IS NOT NULL");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        insertUser($conn, $row['full_name'], $row['email'], 'user');
    }
} else {
    echo "❌ Query failed on trucks: " . $conn->error . "<br>";
}

$conn->close();
?>

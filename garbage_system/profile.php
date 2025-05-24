<?php
session_start();
include 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

$user_id = $_SESSION['user_id'];

$sql = "SELECT u.id, u.username, u.email, u.profile_picture, r.created_at 
FROM users u 
LEFT JOIN registration r ON u.id = r.id 
WHERE u.id = ?";


$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "User not found.";
    exit;
}

$user = $result->fetch_assoc();
$profile_pic = $user['profile_picture'] ? $user['profile_picture'] : 'https://studentportal.mmu.ac.ke/img/profile_m.png';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($user['username']); ?> - Profile</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            padding: 30px;
        }
        .container {
            max-width: 850px;
            margin: auto;
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0px 2px 10px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
            color: #007bff;
        }
        img {
            border-radius: 50%;
            width: 110px;
            height: 110px;
        }
        .info {
            margin-top: 20px;
        }
        .info p {
            margin: 5px 0;
        }
        .actions {
            margin-top: 20px;
            text-align: center;
        }
        .actions a {
            padding: 10px 15px;
            background: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            margin: 0 10px;
        }
        .actions a:hover {
            background: #0056b3;
        }
        table {
            width: 100%;
            margin-top: 30px;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: left;
        }
        th {
            background: #007bff;
            color: white;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Hi <?php echo htmlspecialchars($user['username']); ?>, welcome to your profile!</h2>

    <div style="display: flex; align-items: center; gap: 20px;">
        <img src="<?php echo $profile_pic; ?>" alt="Profile Picture">
        <div class="info">
            <p><strong>Username:</strong> <?php echo htmlspecialchars($user['username']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
            <p><strong>Phone:</strong> <?php echo htmlspecialchars($user['phone'] ?? 'Not set'); ?></p>
            <p><strong>Address:</strong> <?php echo htmlspecialchars($garbage_requests['location'] ?? 'Not set'); ?></p>
            <p><strong>Joined on:</strong> 
                <?php echo isset($user['created_at']) ? date("F j, Y", strtotime($user['created_at'])) : 'N/A'; ?>
            </p>
        </div>
    </div>

    <div class="actions">
        <a href="edit_profile.php">Edit Profile</a>
        <a href="user_index.php">Home</a>
        <a href="logout.php">Logout</a>
    </div>

    <h3>Your Garbage Collection Requests</h3>
    <table>
        <tr>
            <th>Request ID</th>
            <th>Location</th>
            <th>Completed At</th>
            <th>Truck Assigned</th>
        </tr>
        <?php
        $req_sql = "SELECT gr.id, gr.location, gr.completed_at, t.truck_number 
                    FROM garbage_requests gr
                    LEFT JOIN trucks t ON gr.truck_id = t.id
                    WHERE gr.email = ?";
        $req_stmt = $conn->prepare($req_sql);
        if ($req_stmt) {
            $req_stmt->bind_param("s", $user['email']);
            $req_stmt->execute();
            $req_result = $req_stmt->get_result();

            if ($req_result->num_rows > 0) {
                while ($row = $req_result->fetch_assoc()) {
                    echo "<tr>
                            <td>" . htmlspecialchars($row['id']) . "</td>
                            <td>" . htmlspecialchars($row['location']) . "</td>
                            <td>" . ($row['completed_at'] ? htmlspecialchars($row['completed_at']) : 'Pending') . "</td>
                            <td>" . ($row['truck_number'] ?? 'Not assigned') . "</td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='4'>No requests found.</td></tr>";
            }
        } else {
            echo "<tr><td colspan='4'>Error loading requests.</td></tr>";
        }
        ?>
    </table>
</div>

</body>
</html>

<?php
$conn->close();
?>

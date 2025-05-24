<?php
session_start();
include 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: site_login.html");
    exit();
}

$truck_id = $_SESSION['user_id'];

// Fetch truck info
$truck_stmt = $conn->prepare("SELECT truck_number, full_name FROM trucks WHERE id = ?");
$truck_stmt->bind_param("i", $truck_id);
$truck_stmt->execute();
$truck_result = $truck_stmt->get_result();
$truck = $truck_result->fetch_assoc();

// Check if the truck has an active assignment
$assignment_stmt = $conn->prepare("SELECT * FROM garbage_requests WHERE truck_id = ? AND status = 'In Progress' LIMIT 1");
$assignment_stmt->bind_param("i", $truck_id);
$assignment_stmt->execute();
$assignment = $assignment_stmt->get_result()->fetch_assoc();

// Check-in action
if (isset($_POST['checkin']) && !$assignment) {
    $request = $conn->query("SELECT * FROM garbage_requests WHERE status = 'Pending' ORDER BY request_time ASC LIMIT 1")->fetch_assoc();

    if ($request) {
        $assign_stmt = $conn->prepare("UPDATE garbage_requests SET status = 'In Progress', truck_id = ?, truck_number = ?, assigned_at = NOW() WHERE id = ?");
        $assign_stmt->bind_param("isi", $truck_id, $truck['truck_number'], $request['id']);
        $assign_stmt->execute();

        $assignment = $request;
        $assignment['status'] = 'In Progress';
    }
}

// Check-out action
if (isset($_POST['checkout']) && $assignment) {
    $complete_stmt = $conn->prepare("UPDATE garbage_requests SET status = 'Completed', completed_at = NOW() WHERE id = ?");
    $complete_stmt->bind_param("i", $assignment['id']);
    $complete_stmt->execute();

    // Auto-assign new one
    $next = $conn->query("SELECT * FROM garbage_requests WHERE status = 'Pending' ORDER BY request_time ASC LIMIT 1")->fetch_assoc();
    if ($next) {
        $assign_next_stmt = $conn->prepare("UPDATE garbage_requests SET status = 'In Progress', truck_id = ?, truck_number = ?, assigned_at = NOW() WHERE id = ?");
        $assign_next_stmt->bind_param("isi", $truck_id, $truck['truck_number'], $next['id']);
        $assign_next_stmt->execute();
        $assignment = $next;
        $assignment['status'] = 'In Progress';
    } else {
        $assignment = null;
    }
}
// Fetch all pending garbage requests for notification display
$pending_requests = [];
$pending_stmt = $conn->prepare("SELECT name, contact_info, location, preferred_time FROM garbage_requests WHERE status = 'Pending' ORDER BY request_time ASC");
$pending_stmt->execute();
$pending_result = $pending_stmt->get_result();
while ($row = $pending_result->fetch_assoc()) {
    $pending_requests[] = $row;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Truck Driver Dashboard</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-image:url('truck_driver.gpg.png');
            margin: 0;
        }
        .dashboard {
            max-width: 700px;
            margin: 50px auto;
            background: #fff;
            padding: 30px;
            border-radius: 16px;
            box-shadow: 0 6px 18px rgba(0,0,0,0.1);
            position: relative;
        }
        h2 {
            text-align: center;
            color: #28a745;
        }
        .logout-btn {
            position: absolute;
            top: 20px;
            right: 30px;
            background-color: #ff4d4d;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 8px;
            cursor: pointer;
        }
        .info, .task {
            margin-top: 20px;
            padding: 15px;
            background: #f8f9fb;
            border-radius: 10px;
        }
        .actions {
            margin-top: 25px;
            text-align: center;
        }
        button {
            padding: 12px 20px;
            border: none;
            background-color: #28a745;
            color: white;
            font-size: 16px;
            border-radius: 8px;
            cursor: pointer;
            margin: 10px;
        }
        button:hover {
            background-color: #218838;
        }
        .status {
            text-align: center;
            margin-top: 15px;
            font-weight: bold;
        }
    </style>
</head>
<body>
<div class="dashboard">
    <form method="post">
        <button class="logout-btn" formaction="site_login.html">Logout</button>

        <h2>Welcome, <?php echo htmlspecialchars($truck['full_name']); ?> 🚛</h2>

        <div class="info">
            <p><strong>Truck Number:</strong> <?php echo htmlspecialchars($truck['truck_number']); ?></p>
            <p><strong>Status:</strong> <?php echo $assignment ? $assignment['status'] : 'Idle'; ?></p>
        </div>

        <?php if ($assignment): ?>
            <div class="task">
                <p><strong>Client:</strong> <?php echo htmlspecialchars($assignment['name']); ?></p>
                <p><strong>contact:</strong><?php echo htmlspecialchars($assignment['contact_info']);?></p>
                <p><strong>Location:</strong> <?php echo htmlspecialchars($assignment['location']); ?></p>
                <p><strong>Preferred Time:</strong> <?php echo htmlspecialchars($assignment['preferred_time']); ?></p>
                <?php if ($assignment['status'] == 'Completed'): ?>
                    <p><strong>Completed At:</strong> <?php echo htmlspecialchars($assignment['completed_at']); ?></p>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <div class="task">
                <p>No task assigned yet.</p>
            </div>
        <?php endif; ?>

        <div class="actions">
            <button name="checkin" <?php if ($assignment) echo "disabled"; ?>>🚚 Check In</button>
            <button name="checkout" <?php if (!$assignment) echo "disabled"; ?>>✅ Check Out</button>
        </div>
    </form>
</div>
<div class="dashboard" style="margin-top: 20px;">
    <h3 style="text-align:center; color: #444;">📢 Pending Garbage Requests</h3>
    <?php if (count($pending_requests) > 0): ?>
        <table style="width:100%; border-collapse: collapse; margin-top: 15px;">
            <thead>
                <tr style="background:#007bff; color:white;">
                    <th style="padding:10px; border: 1px solid #ddd;">Name</th>
                    <th style="padding:10px; border: 1px solid #ddd;">Contact</th>
                    <th style="padding:10px; border: 1px solid #ddd;">Location</th>
                    <th style="padding:10px; border: 1px solid #ddd;">Preferred Time</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($pending_requests as $req): ?>
                    <tr>
                        <td style="padding:10px; border: 1px solid #ddd;"><?php echo htmlspecialchars($req['name']); ?></td>
                        <td style="padding:10px; border: 1px solid #ddd;"><?php echo htmlspecialchars($req['contact_info']); ?></td>
                        <td style="padding:10px; border: 1px solid #ddd;"><?php echo htmlspecialchars($req['location']); ?></td>
                        <td style="padding:10px; border: 1px solid #ddd;"><?php echo htmlspecialchars($req['preferred_time']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p style="text-align:center;">No pending requests at the moment.</p>
    <?php endif; ?>
</div>

</body>
</html>

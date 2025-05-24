

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Manage Trucks - Smart Waste</title>
  <style>
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    body {
      font-family: Arial, sans-serif;
      min-height: 100vh;
      background-color: #f0f2f5;
      display: flex;
      flex-direction: column;
    }

    /* Navbar (Mobile Header) */
    .navbar {
      background-color: #158b30;
      color: white;
      padding: 15px 20px;
      display: flex;
      align-items: center;
      justify-content: space-between;
    }

    .menu-toggle {
      font-size: 24px;
      cursor: pointer;
      display: none;
    }

    /* Sidebar */
    .sidebar {
      width: 220px;
      background-color: #158b30;
      color: white;
      padding: 20px;
      position: fixed;
      height: 100vh;
      transition: transform 0.3s ease;
      z-index: 999;
    }

    .sidebar nav ul {
      list-style: none;
    }

    .sidebar nav ul li {
      margin-bottom: 10px;
    }

    .sidebar nav ul li a {
      color: white;
      text-decoration: none;
      font-weight: bold;
      background-color: #34495e;
      display: block;
      padding: 10px;
      border-radius: 5px;
    }

    .sidebar nav ul li a:hover {
      background-color: #1abc9c;
    }

    .main-panel {
      margin-left: 220px;
      padding: 30px;
      flex: 1;
    }

    .dashboard-cards {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
      gap: 20px;
    }

    .card {
      background-color: white;
      padding: 20px;
      border-radius: 8px;
      text-align: center;
      box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .card h3 {
      font-size: 1.2rem;
      margin-bottom: 10px;
    }

    .card span {
      font-size: 1.5rem;
      color: #27ae60;
    }

    /* Mobile Styles */
    @media (max-width: 768px) {
      .menu-toggle {
        display: block;
      }

      .sidebar {
        transform: translateX(-100%);
        position: fixed;
        top: 0;
        left: 0;
        height: 100%;
        width: 200px;
        background-color: #158b30;
        z-index: 1000;
      }

      .sidebar.active {
        transform: translateX(0);
      }

      .main-panel {
        margin-left: 0;
        padding: 20px;
      }
    }.truck-table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 20px;
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  border: 5px solid #ddd;
  border-radius: 20px;
  overflow: hidden;
}
.truck-table th,
.truck-table td {
  padding: 20px 30px; /* Adjusted horizontal and vertical padding for better spacing */
  text-align: left;
  border-bottom: 5px solid #ddd; /* Added bottom borders for a cleaner separation */
  white-space: nowrap; /* Prevents content from wrapping */

}

.truck-table th {
  background-color: #f4f4f4;
  font-size: 16px;
  font-weight: 600;
  color: #222;
  text-transform: uppercase; /* Uppercase for headers to make them stand out */
}

.truck-table td {
  font-size: 14px; /* Slightly smaller text for better clarity */
  color: #555;
}

.truck-table tbody tr:nth-child(even) {
  background-color: #fafafa;
}

.truck-table tbody tr:hover {
  background-color: #eef6ff;
  cursor: pointer; /* Changed cursor to pointer to indicate it's clickable (optional) */
  cell: padding 2px;
}

.truck-table td,
.truck-table th {
  padding-left: 20px; /* Adds padding to the left of all table cells */
  padding-right: 20px; /* Adds padding to the right of all table cells */
}

@media (max-width: 768px) {
  .truck-table th,
  .truck-table td {
    padding: 12px 15px; /* Adjust padding for mobile responsiveness */
    font-size: 13px; /* Adjust font size for mobile */
  }
}


  </style>
    
</head>
<body>


  <!-- Mobile Navbar -->
  <div class="navbar">
    <span class="menu-toggle" onclick="toggleSidebar()">&#9776;</span>
    <h2>Dashboard</h2>
  </div>
  <!-- Sidebar -->
  <div class="sidebar" id="sidebar">
    <nav>
        <ul>
          <li class="nav-item" ><a class="nav-link" href="admin_index.html">Dashboard</a></li>
          <li class="nav-item" ><a class="nav-link" href="manage_request.php">Manage_request</a></li>
          <li class="nav-item" ><a class="nav-link" href="trucks.php">Manage_trucks</a></li>
          <li class="nav-item" ><a class="nav-link" href="users.php">Users</a></li>
          <li class="nav-item" ><a class="nav-link" href="messages.html">Messages</a></li>
          <li>Logout</li>
        </ul>
        </nav>
  </div>

  <!-- Main Panel -->
  <div class="main-panel">
    <h1>Trucks</h1>
    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Reg No.</th>
          <th>full_name</th>
          <th>availability_status</th>
          <th>location</th>
          <th>Assigned_at</th>
          
          <th>completed_at</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
        </tr>
        <!-- You can add more rows here -->
      </tbody>
      <tbody>
        <?php include 'load_trucks.php'; ?>
      </tbody>
    </table>
    
  </div>

  <!-- Toggle Sidebar Script -->
  <script>
    function toggleSidebar() {
      const sidebar = document.getElementById("sidebar");
      sidebar.classList.toggle("active");
    }
  </script>
</body>
</html>

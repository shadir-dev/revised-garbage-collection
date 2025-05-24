
 <!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Manage Requests - Smart Waste</title>
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
        <li class="nav-item"><a class="nav-link" href="admin_index.html">Dashboard</a></li>
        <li class="nav-item"><a class="nav-link" href="manage_request.php">Manage_request</a></li>
        <li class="nav-item"><a class="nav-link" href="trucks.php">Manage_trucks</a></li>
        <li class="nav-item"><a class="nav-link" href="users.php">Users</a></li>
        <li class="nav-item"><a class="nav-link" href="messages.html">Messages</a></li>
        <li>Logout</li>
      </ul>
    </nav>
  </div>

  <!-- Main Panel -->
  <div class="main-panel">
    <h1>Pickup Requests</h1>
    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Name</th>
          <th>EMAIL</th>
          <th>Location</th>
          <th>Waste</th>
          <th>Status</th>
          
        </tr>
      </thead>
      <tbody>
        <?php include 'request.php'; ?>
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
  <script>
  // Disable right-click
  document.addEventListener('contextmenu', event => event.preventDefault());

  // Disable F12, Ctrl+Shift+I, Ctrl+U
  document.onkeydown = function(e) {
    if (e.keyCode === 123) return false; // F12
    if (e.ctrlKey && e.shiftKey && (e.keyCode === 73 || e.keyCode === 74)) return false; // Ctrl+Shift+I/J
    if (e.ctrlKey && e.keyCode === 85) return false; // Ctrl+U
  };
</script>

</body>
</html>

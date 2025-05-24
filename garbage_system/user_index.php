<?php
session_start();

// Redirect to login if user not logged in
if (!isset($_SESSION['username'])) {
    header("Location: site_login.html");
    exit();
}

$username = $_SESSION['username'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Welcome - Smart Garbage Collection</title>
  <video autoplay muted loop id="bg-video">
  <source src="gar.vid.mp4" type="video/mp4" />
  
</video>

  <style>
  
  #bg-video {
  position: fixed;
  top: 0;
  left: 0;
  min-width: 100%;
  min-height: 100%;
  object-fit: cover;
  z-index: -1; /* Keeps it behind your page content */
  opacity: 0.25; /* Optional: adds a fade for readability */
}
body {
  font-family: Arial, sans-serif;
  margin: 0;
  padding: 0;
  background-size: cover;
  background-repeat: no-repeat;
  background-attachment: fixed;
}

header {
  background-color: #158b30;
  color: white;
  padding: 15px 20px;
  display: flex;
  justify-content: space-between;
  align-items: center;
  position: sticky;
  top: 0;
  z-index: 1000;
}

nav ul {
  list-style: none;
  margin: 0;
  padding: 0;
  display: flex;
}

.nav-item {
  margin-right: 20px;
}

.nav-link {
  color: white;
  text-decoration: none;
  font-size: 18px;
  transition: color 0.3s;
}

.nav-link:hover {
  color: #c0ffc0;
}

.profile-img {
  width: 40px;
  height: 40px;
  border-radius: 50%;
  cursor: pointer;
}

.dropdown {
  position: relative;
}

.dropdown-content {
  display: none;
  position: absolute;
  right: 0;
  background-color: #333;
  box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.2);
  min-width: 160px;
  border-radius: 5px;
  overflow: hidden;
}

.dropdown-content a {
  color: white;
  padding: 12px 16px;
  text-decoration: none;
  display: block;
}

.dropdown-content a:hover {
  background-color: #575757;
}

.welcome-container {
  text-align: center;
  margin-top: 80px;
  padding: 20px;
  background-color: rgba(255, 255, 255, 0.37);
  max-width: 600px;
  margin-left: auto;
  margin-right: auto;
  border-radius: 10px;
}

.welcome-container h1 {
  font-size: 36px;
  color: #158b30;
  margin-bottom: 10px;
}

.welcome-container p {
  font-size: 18px;
  color: #333;
  margin-bottom: 10px;
}

button {
  padding: 12px 24px;
  font-size: 16px;
  background-color: #28a745;
  color: white;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  margin-top: 15px;
}

button:hover {
  background-color: #218838;
}

.section {
  padding: 20px;
  margin: 30px auto;
  background-color: #fff;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
  max-width: 900px;
  border-radius: 10px;
}

.section h5 {
  color: #158b30;
  font-size: 22px;
  margin-bottom: 10px;
}

footer {
  text-align: center;
  padding: 10px;
  background-color: #158b30;
  color: white;
  position: fixed;
  width: 100%;
  bottom: 0;
  font-size: 14px;
}
.notification .profile-img {
  width: 30px;
  height: 30px;
}


  </style>
</head>
<body>

<header>
  <nav>
    <div>
      <ul>
        
        <li class="nav-item"><a class="nav-link" href="user_index.php">Home</a></li>
        <li class="nav-item"><a class="nav-link" href="request_form.html">Request Pickup</a></li>
        <li class="nav-item"><a class="nav-link" href="view_status.html">View Status</a></li>
        <li class="nav-item"><a class="nav-link" href="contact.html">Contact</a></li>
        <div class="dropdown notification">
  <img src="https://cdn-icons-png.flaticon.com/512/1827/1827392.png" 
       alt="Notifications" class="profile-img" onclick="toggleNotification()">
  <div class="dropdown-content" id="notificationMenu">
    <p style="padding:10px; color:white;">Loading notifications...</p>
  </div>
</div>


      </ul>

      <aside>
        <div class="dropdown">
          <img src="https://studentportal.mmu.ac.ke/img/profile_m.png" alt="Profile Picture" class="profile-img" onclick="toggleDropdown()">
          <div class="dropdown-content" id="dropdownMenu">
            <a href="profile.php">Profile</a>
            <a href="logout.php">Logout</a>
          </div>
        </div>
      </aside>
    </div>
  </nav>
</header>

<!-- Personalized Welcome Section -->
<div class="welcome-container">
  <h1 class="display-4">Welcome, <span style="color:#158b30;"><?php echo htmlspecialchars($username); ?></span>!</h1>
  <p class="lead">Smart Waste Collection Made Easy</p>
  <p>Book, track, and manage your garbage pickups with ease.</p>
  <a href="request_form.html">
    <button>Request Pickup Now</button>
  </a>
</div>

<!-- Sections -->
<div class="section">
  <h5>How It Works</h5>
  <p>Submit your location, select waste type, and get service within hours.</p>
</div>

<div class="section">
  <h5>Why Choose Us</h5>
  <p>Fast, reliable pickups and environment-conscious waste handling.</p>
</div>

<footer>
  <p>© 2025 SmartWaste - All Rights Reserved</p>
</footer>

<script>
  function toggleDropdown() {
    var menu = document.getElementById("dropdownMenu");
    menu.style.display = menu.style.display === "block" ? "none" : "block";
  }

  window.onclick = function(event) {
    if (!event.target.matches('.profile-img')) {
      var dropdowns = document.getElementsByClassName("dropdown-content");
      for (let i = 0; i < dropdowns.length; i++) {
        let openDropdown = dropdowns[i];
        if (openDropdown.style.display === "block") {
          openDropdown.style.display = "none";
        }
      }
    }
  };

  document.addEventListener('contextmenu', event => event.preventDefault());
  document.onkeydown = function(e) {
    if (e.keyCode === 123 || (e.ctrlKey && e.shiftKey && (e.keyCode === 73 || e.keyCode === 74)) || (e.ctrlKey && e.keyCode === 85)) {
      return false;
    }
  };
</script>
<script>
function toggleNotification() {
  const menu = document.getElementById("notificationMenu");

  // Toggle visibility
  if (menu.style.display === "block") {
    menu.style.display = "none";
    return;
  }

  // Fetch notifications
  fetch("fetch_notifications.php")
    .then(res => res.json())
    .then(data => {
      if (data.error) {
        menu.innerHTML = "<p style='padding:10px; color:white;'>Please login</p>";
      } else if (data.length === 0) {
        menu.innerHTML = "<p style='padding:10px; color:white;'>No new notifications</p>";
      } else {
        menu.innerHTML = data.map(notif => `
          <a href="view_status.html?id=${notif.id}" style="display:block; padding:8px 10px; color:white; text-decoration:none; border-bottom:1px solid #444;">
            <strong>#${notif.id}</strong> - ${notif.message}<br>
            <small style="color:#ccc;">${notif.timestamp}</small>
          </a>
        `).join('');
      }

      menu.style.display = "block";
    })
    .catch(err => {
      menu.innerHTML = "<p style='padding:10px; color:white;'>Error loading notifications</p>";
      menu.style.display = "block";
    });
}
</script>
<!-- Floating Chatbot -->
<div id="chatbot-button" onclick="toggleChat()">Chat</div>

<div id="chat-container" style="display:none;">
    <div id="chat-header">Garbage Assistant</div>
    <div id="messages"></div>
    <input type="text" id="userInput" placeholder="Ask something..." />
    <button onclick="sendMessage()">Send</button>
</div>

<style>
#chatbot-button {
    position: fixed;
    bottom: 20px;
    right: 20px;
    background: white;
    color: green;
    padding: 10px;
    border-radius: 50%;
    cursor: pointer;
    z-index: 999;
}
#chat-container {
    position: fixed;
    bottom: 80px;
    right: 20px;
    width: 280px;
    background: white;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0,0,0,0.2);
    padding: 10px;
    display: none;
    z-index: 1000;
}
#chat-header {
    font-weight: bold;
    text-align: center;
    margin-bottom: 10px;
}
#messages {
    height: 200px;
    overflow-y: auto;
    font-size: 14px;
    margin-bottom: 10px;
}
#userInput {
    width: 70%;
    padding: 5px;
}
button {
    padding: 5px;
}
</style>

<script>
function toggleChat() {
    var chat = document.getElementById("chat-container");
    chat.style.display = chat.style.display === "none" ? "block" : "none";
}

function sendMessage() {
    let input = document.getElementById("userInput").value;
    let messages = document.getElementById("messages");

    messages.innerHTML += `<div><strong>You:</strong> ${input}</div>`;

    fetch("chatbot.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: "message=" + encodeURIComponent(input)
    })
    .then(response => response.text())
    .then(data => {
        messages.innerHTML += `<div><strong>Bot:</strong> ${data}</div>`;
        document.getElementById("userInput").value = "";
        messages.scrollTop = messages.scrollHeight;
    });
}
</script>

</body>
</html>

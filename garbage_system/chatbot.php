<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['message'])) {
    $message = strtolower(trim($_POST['message']));
    $response = "";

    // Vulgar words list (extend as needed)
    $badWords = ["fuck", "shit", "bitch", "idiot"];
    foreach ($badWords as $word) {
        if (strpos($message, $word) !== false) {
            echo "Please use polite language. I'm here to help!";
            exit;
        }
    }

    // Rule-based chatbot logic
    if (strpos($message, "hi") !== false || strpos($message, "hello") !== false || strpos($message, "hey") !== false) {
        $response = "Hi there! I am your garbage collection assistant 😊. You can ask me to help you request a collection, track your status, or message the admin.";
    } elseif (strpos($message, "request") !== false || strpos($message, "collection") !== false) {
        $response = "You can request garbage collection here: <a href='request_form.html' target='_blank'>Request Collection</a>";
    } elseif (strpos($message, "track") !== false || strpos($message, "status") !== false) {
        $response = "Track your request status here: <a href='view_status.html' target='_blank'>Track Status</a>";
    } elseif (strpos($message, "admin") !== false || strpos($message, "message") !== false) {
        $response = "To message the admin, go here: <a href='contact.html' target='_blank'>Contact Admin</a>";
    } else {
        $response = "I'm sorry, I didn't understand that. Try asking about: request collection, track status, or message admin.";
    }

    echo $response;
} else {
    echo "Invalid request.";
}
?>

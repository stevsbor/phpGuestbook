<?php
require 'config.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['name']) && isset($_POST['message']) && !empty($_POST['name']) && !empty($_POST['message'])) {
        $name = htmlspecialchars($_POST['name']);
        $message = htmlspecialchars($_POST['message']);

        $conn = new mysqli($host, $username, $password, $dbname);
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $stmt = $conn->prepare("INSERT INTO entries (name, message) VALUES (?, ?)");
        $stmt->bind_param("ss", $name, $message);
        $stmt->execute();
        $stmt->close();
        $conn->close();

        header("Location: index.php");
        exit();
    } else {
        echo "Name and message fields cannot be empty.";
    }
} else {
    echo "Invalid request method.";
}
?>
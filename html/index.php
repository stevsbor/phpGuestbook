<?php
require_once 'config.php';

$conn = new mysqli($host, $username, $password, $dbname);
if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
}

$entries = [];
$result = $conn->query("SELECT * FROM entries WHERE status != 'Denied' ORDER BY created_at DESC");
if ($result->num_rows > 0) {
	while ($row = $result->fetch_assoc()) {
		$entries[] = [
			'name' => htmlspecialchars($row["name"]),
			'message' => htmlspecialchars($row["message"]),
			'timestamp' => date("d. F Y, H:i", strtotime($row['created_at'])),
			'status' => $row['status']
		];
	}
}

$conn->close();

$title = 'Gästebuch';
$header = 'Gästebuch';

include 'index.tpl.php';
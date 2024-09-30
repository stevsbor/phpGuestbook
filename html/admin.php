<?php
// Lade Config
require_once 'config.php';

$conn = new mysqli($host, $username, $password, $dbname);
if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$entryID = $_POST['entryID'];
	//Entscheidung welcher Knopf betätigt wurde
	if (isset($_POST['approveEntry'])) {
		$status = 'Approved';
	} elseif (isset($_POST['declineEntry'])) {
		$status = 'Denied';
	} elseif (isset($_POST['deleteEntry'])) {
		$stmt = $conn->prepare("DELETE FROM entries WHERE id = ?");
		$stmt->bind_param("i", $entryID);
		$stmt->execute();
		$stmt->close();
		header("Location: admin.php");
		exit();
	}
	//Update der Datenbank nach veränderung
	if (isset($status)) {
		$stmt = $conn->prepare("UPDATE entries SET status = ? WHERE id = ?");
		$stmt->bind_param("si", $status, $entryID);
		$stmt->execute();
		$stmt->close();
		header("Location: admin.php");
		exit();
	}
}

//Ausgabe der Datenbankeinträge
$entries = [];
$result = $conn->query("SELECT * FROM entries ORDER BY created_at DESC");
if ($result->num_rows > 0) {
	while ($row = $result->fetch_assoc()) {
		$entries[] = [
			'id' => $row["id"],
			'name' => $row["name"],
			'message' => $row["message"],
			'timestamp' => date("d. F Y, H:I", strtotime($row['created_at'])),
			'status' => $row['status']
		];
	}
}

$conn->close();

$title = 'Gästebuch Admin';
$header = 'Gästebuch Admin';

include 'admin.tpl.php';
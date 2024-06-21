<!DOCTYPE html>
<html lang="de">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="stylesheet" type="text/css" href="css/style.css" />
		<title>Gästebuch</title>
	</head>
	<body>
		
		<div id="wrapper">
			<div class="inside">
				<h1>Gästebuch</h1>
				<!-- Form für Neueinträge -->
				<form action="submit.php" method="post">
					<!--Eingabefeld für Name-->
    				<label for="name">Name:</label>
    				<input type="text" id="name" name="name" required><br>
					<!--Textfeld für die Nachricht-->
    				<label for="message">Message:</label>
    				<textarea id="message" name="message" required></textarea><br>
					<!--Submit button-->
    				<input type="submit" value="Submit">
				</form>
				<hr>
				<!--Container um Ergebnisse anzuzeigen-->
				<div id="entries">

					<?php
					// Verbindung an die lokale Datenbank
					$conn = new mysqli('localhost', 'root', '', 'guestbook');
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
							header("Location: index.php");
							exit();
						}
						//Update der Datenbank nach veränderung
						if (isset($status)) {
							$stmt = $conn->prepare("UPDATE entries SET status = ? WHERE id = ?");
							$stmt->bind_param("si", $status, $entryID);
							$stmt->execute();
							$stmt->close();
							header("Location: index.php");
							exit();
						}
					}
					//Ausgabe der Datenbankeinträge
					$result = $conn->query("SELECT * FROM entries ORDER BY created_at DESC");
					if ($result->num_rows > 0) {
						while ($row = $result->fetch_assoc()) {
							$entryID = $row["id"];
							$name = htmlspecialchars($row["name"]);
							$message = htmlspecialchars($row["message"]);
							$timestamp = date("d. F Y, H:i", strtotime($row['created_at']));
							$status = $row['status'];
							$statusClass = strtolower($status);
							//Details Anzeigen
							echo "<div class='entry'>";
                    		echo "<div class='author'>$name</div>";
                    		echo "<div class='timestamp'>$timestamp</div>";
                    		echo "<div class='message'>$message</div>";
                    		echo "<div class='status $statusClass'>$status</div>";
                    		echo "<div class='toolbar'>
                        	    <form id='entryForm-$entryID' class='entryForm' method='post'>
                        	        <input type='hidden' name='entryID' value='$entryID' />";
							//Buttons anzeigen nach Status
							if ($status !== 'Approved') {
								echo "<input type='submit' name='approveEntry' value='Freigeben' />";
							}
							if ($status !== 'Denied') {
								echo "<input type='submit' name='declineEntry' value='Ablehnen' />";
							}
							echo "<input type='submit' name='deleteEntry' value='Löschen' />";
							echo "</form></div></div><hr>";
						} 
					} else {echo"No entries found.";}

					$conn->close();
					?>
				</div>
			</div>
		</div>
	</body>
</html>
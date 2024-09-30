<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="de">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="stylesheet" type="text/css" href="css/style.css" />
		<title><?= $title ?></title>
	</head>
	<body>
		<div id="wrapper">
			<div class="inside">
				<h1><?= $header ?></h1>
                <form action="logout.php" method="post">
                    <input type="submit" value="Logout" />
                </form>
				<div id="entries">
                    <?php foreach ($entries as $entry): ?>
                        <div class="entry">
                            <div class="autor"><?= htmlspecialchars($entry['name']) ?></div>
                            <div class="timestamp"><?= htmlspecialchars($entry['timestamp']) ?></div>
                            <div class="message"><?= htmlspecialchars($entry['message']) ?></div>
                            <div class="status <?= strtolower($entry['status']) ?>"><?= htmlspecialchars($entry['status']) ?></div>
                            <div class="toolbar">
                                <form method="post">
                                    <input type="hidden" name="entryID" value="<?= $entry['id'] ?>" />
                                    <?php if ($entry['status'] !== 'Approved'): ?>
                                        <input type="submit" name="approveEntry" value="Freigeben" />
                                    <?php endif; ?>
                                    <?php if ($entry['status'] !== 'Denied'): ?>
                                        <input type="submit" name="declineEntry" value="Ablehnen" />
                                    <?php endif; ?>
                                    <input type="submit" name="deleteEntry" value="Löschen" />
                                </form>
                            </div>
                        </div>
                    <hr>
                    <?php endforeach; ?>
					<?php if (empty($entries)): ?>
						<p>No entries found.</p>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</body>
</html>
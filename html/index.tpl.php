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
                <!-- Form für Neueinträge -->
                <form action="submit.php" method="post">
                    <!-- Eingabefeld für Name -->
                    <label for="name">Name:</label>
                    <input type="text" id="name" name="name" required><br>
                    <!-- Textfeld für die Nachricht -->
                    <label for="message">Message:</label>
                    <textarea id="message" name="message" required></textarea><br>
                    <!-- Submit Button -->
                    <input type="submit" value="Submit">
                </form>
                <hr>
                <!-- Container um Ergebnisse anzuzeigen -->
                <div id="entries">
                    <?php foreach ($entries as $entry): ?>
                        <div class="entry">
                            <div class='author'><?= htmlspecialchars($entry['name']) ?></div>
							<div class='timestamp'><?= htmlspecialchars($entry['timestamp']) ?></div>
							<div class='message'><?= htmlspecialchars($entry['message']) ?></div>
							<div class='status <?= strtolower($entry['status']) ?>'><?= htmlspecialchars($entry['status']) ?></div>
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
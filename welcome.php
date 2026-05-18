<?php
    session_start();
    require "createdb.php";
    
    //---------------------------------
    // User handeling logic 
    //---------------------------------
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST["username"])) { 
        $username = trim($_POST["username"]);

        try {
            $stmt = $db->prepare("SELECT id FROM users WHERE name = :username");
            $stmt->execute([':username' => $username]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                // If user exist, grab ID
                $userId = $user['id'];
            } else {
                // If user dosn't exsist
                $insertStmt = $db->prepare("INSERT INTO users (name) VALUES (:name)");
                $insertStmt->execute([':name' => $username]);
                // Get the ID
                $userId = $db->lastInsertId();
            }

            // Svae into session memory
            $_SESSION['user_id'] = $userId;
            $_SESSION['username'] = $username;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        } 
    }
    if (!isset($_SESSION['username'])) {
        // Send back to "inlogg" page if not given username
        header("Location: index.php");
        exit;
    }

    //---------------------------------
    // Chat bot data load function
    //---------------------------------
    function loadChemicalDatabase() {
    $jsonFile = __DIR__ . '/kemi.json';
    if (!is_readable($jsonFile)) {
        return null;
    }
    $json = file_get_contents($jsonFile);
    $data = json_decode($json, true);
    return is_array($data) ? $data : null;
}

    // Set chat mode
    $mode = 'bot';
    if (isset($_POST['mode']) && in_array($_POST['mode'], ['bot', 'message'], true)) {
        $_SESSION['mode'] = $_POST['mode'];
    }
    if (isset($_SESSION['mode'])) {
        $mode = $_SESSION['mode'];
    }

    //---------------------------------
    // Incoming chat msgs & BOT
    //---------------------------------
    $botReply = null; // Variable to temporarily hold a bot response if generated

    if (isset($_POST['message']) && isset($_POST['send'])) {
        $message = trim($_POST['message']);
        
        if ($message !== '') {
            try {
                // 1. Save the regular user's message
                $msgStmt = $db->prepare("INSERT INTO messages (userID, messages) VALUES (:userID, :messages)");
                $msgStmt->execute([
                    ':userID' => $_SESSION['user_id'],
                    ':messages' => $message
                ]);
            } catch (PDOException $e) {
                echo "<p style='color:red;'>Failed to save message: " . $e->getMessage() . "</p>";
            }

            // 2. Bot mode active?
            if ($mode === 'bot') {
                $chemicals = loadChemicalDatabase();
                $reply = ""; // Clear string for the answer

                if ($chemicals === null) {
                    $reply = "Kunde inte läsa ämnesdatabasen.";
                } else {
                    $key = mb_strtolower($message, 'UTF-8');
                    $chemicalsLower = array_change_key_case($chemicals, CASE_LOWER);

                    if (array_key_exists($key, $chemicalsLower)) {
                        $formula = $chemicalsLower[$key];
                        $reply = "Den kemiska beteckningen för " . $message . " är " . $formula . ".";
                    } else {
                        $reply = "Detta ämne finns inte i vår databas eller detta är inte ett giltigt ämne.";
                    }
                }

                // NEW: Save the Bot's reply directly into the database for everyone to see!
                try {
                    $botStmt = $db->prepare("INSERT INTO messages (userID, messages) VALUES (:userID, :messages)");
                    $botStmt->execute([
                        ':userID' => 0, // 0 is the ID we assigned to our Bot user
                        ':messages' => $reply
                    ]);
                } catch (PDOException $e) {
                    echo "<p style='color:red;'>Failed to save Bot message: " . $e->getMessage() . "</p>";
                }
            }
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title>Ämnesuppslag</title>
</head>
<body>
    <h1>Ämnesuppslag</h1>
    

    <p>Hej, <?php echo htmlspecialchars($_SESSION['username'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); ?>!</p>

    <div class="chat-box" style="border: 1px solid #ccc; height: 300px; overflow-y: scroll; padding: 10px; margin-bottom: 20px;">
        <?php
        try {
            // Pull existing history from SQLite, joining the user table to get the name
            $query = "SELECT m.messages, m.timeStamp, u.name AS username 
                        FROM messages m 
                        JOIN users u ON m.userID = u.ID 
                        ORDER BY m.timeStamp ASC";
            $history = $db->query($query)->fetchAll(PDO::FETCH_ASSOC);

            foreach ($history as $row) {
                echo "<p><strong>[" . $row['timeStamp'] . "] " . htmlspecialchars($row['username']) . ":</strong> " . htmlspecialchars($row['messages']) . "</p>";
            }

        } catch (PDOException $e) {
            echo "<p style='color: red;'>Kunde inte ladda meddelandehistorik: " . $e->getMessage() . "</p>";
        }
        ?>
    </div>
    <!-- Message input form -->
    <form method="post">
        <p>Välj läge och skicka meddelandet med en knapp. Valet sparas tills du ändrar det.</p>
        <label>
            <input type="radio" name="mode" value="bot" <?php echo $mode === 'bot' ? 'checked' : ''; ?>>
            Skicka till bot
        </label><br>
        <label>
            <input type="radio" name="mode" value="message" <?php echo $mode === 'message' ? 'checked' : ''; ?>>
            Bara skriva meddelande
        </label><br>
        <label for="message">Skriv ditt meddelande eller ämne här:</label><br>
        <textarea name="message" id="message" rows="4" cols="50"></textarea><br>
        <button type="submit" name="send" value="1">Skicka</button>
    </form><br>
    <a href="index.php">Tillbaka</a>
</body>
</html>

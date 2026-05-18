<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="kemichatt.css">
    
    <title>Ämnesuppslag</title>
</head>
<body>
    <h1>Ämnesuppslag</h1>
    <?php
    session_start();

    function loadChemicalDatabase()
    {
        $jsonFile = __DIR__ . '/kemi.json';
        if (!is_readable($jsonFile)) {
            return null;
        }

        $json = file_get_contents($jsonFile);
        $data = json_decode($json, true);
        return is_array($data) ? $data : null;
    }

    function renderUsernameMessage($username)
    {
        echo "<p>Hej, " . htmlspecialchars($username, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') . "!</p>";
    }

    if (isset($_POST['username'])) {
        $username = trim($_POST['username']);
        if ($username !== '') {
            $username = htmlspecialchars($username, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
            $_SESSION['username'] = $username;
            renderUsernameMessage($username);
        }
    } elseif (isset($_SESSION['username'])) {
        renderUsernameMessage($_SESSION['username']);
    } else {
        echo "<p>Ingen användare angiven.</p>";
    }

    $mode = 'bot';
    if (isset($_POST['mode']) && in_array($_POST['mode'], ['bot', 'message'], true)) {
        $_SESSION['mode'] = $_POST['mode'];
    }
    if (isset($_SESSION['mode'])) {
        $mode = $_SESSION['mode'];
    }

    if (isset($_POST['message']) && isset($_POST['send']) && isset($_SESSION['username'])) {
        $message = trim($_POST['message']);
        $safeMessage = htmlspecialchars($message, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');

        if ($message !== '') {
            echo "<p>" . $_SESSION['username'] . ": " . $safeMessage . "</p>";

            if ($mode === 'bot') {
                $chemicals = loadChemicalDatabase();
                if ($chemicals === null) {
                    echo "<p>Bot: Kunde inte läsa ämnesdatabasen.</p>";
                } else {
                    $key = mb_strtolower($message, 'UTF-8');
                    $chemicalsLower = array_change_key_case($chemicals, CASE_LOWER);

                    if (array_key_exists($key, $chemicalsLower)) {
                        $formula = htmlspecialchars($chemicalsLower[$key], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
                        echo "<p>Bot: Den kemiska beteckningen för <strong>" . $safeMessage . "</strong> är <strong>" . $formula . "</strong>.</p>";
                    } else {
                        echo "<p>Bot: Detta ämne finns inte i vår databas eller detta är inte ett giltigt ämne.</p>";
                    }
                }
            }
        }
    }
    ?>
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
    </form>
    <a href="index.php">Tillbaka</a>
</body>
</html>

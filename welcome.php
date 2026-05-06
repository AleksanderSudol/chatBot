<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome</title>
</head>
<body>
    <h1>Welcome</h1>
    <?php
    session_start();
    if (isset($_POST['username'])) {
        $username = htmlspecialchars($_POST['username']);
        $_SESSION['username'] = $username;
        echo "<p>Hello, $username!</p>";
    } elseif (isset($_SESSION['username'])) {
        $username = $_SESSION['username'];
        echo "<p>Hello, $username!</p>";
    } else {
        echo "<p>No username provided.</p>";
    }

    if (isset($_POST['message']) && isset($_SESSION['username'])) {
        $message = htmlspecialchars($_POST['message']);
        echo "<p>" . $_SESSION['username'] . ": " . $message . "</p>";
    }
    ?>
    <form method="post">
        <label for="message">Skriv något:</label><br>
        <textarea name="message" id="message" rows="4" cols="50"></textarea><br>
        <input type="submit" value="Skicka">
    </form>
    <a href="index.php">Go back</a>
</body>
</html>

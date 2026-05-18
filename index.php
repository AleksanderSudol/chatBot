<!DOCTYPE html>
<html lang="sv">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chattrum - Välkommen</title>
    <link rel="stylesheet" href="kemichatt.css">
</head>
<body>
    <div class="container">
        <h1>💬 Chattrum</h1>
        
        <form action="welcome.php" method="post">
            <label for="username">Användarnamn:</label>
            <input type="text" name="username" id="username" placeholder="Skriv ditt namn..." required>
            <input type="submit" value="Börja chatta">
        </form>

        <div class="info-cards">
            <div class="card">
                <h2> Chatta med andra</h2>
                <p>Skriv meddelanden som alla användare kan se. Perfekt för att diskutera och ha en trevlig konversation om kemi tillsammans.</p>
            </div>

            <div class="card">
                <h2> Chatta med botten</h2>
                <p>Botten kan berätta den <strong>kemiska beteckningen</strong> för olika ämnen. Skriv bara ett ämnesnamn som <strong>"Vatten"</strong> för att få veta dess formel!</p>
            </div>

            <div class="card">
                <h2> Kemisk databas</h2>
                <p>Vår bot har tillgång till en kemisk databas med många ämnen. <strong>Tips:</strong> Motsatta vägen fungerar inte - botten kan inte gissa ämnet från formeln.</p>
            </div>
        </div>

        <div class="creators">
            <h3> Skapare</h3>
            <p>Alvin Sandgren • Gustav Jakobsson • Aleksander Sudol • Tor Wallberg</p>
        </div>
    </div>
</body>
</html>
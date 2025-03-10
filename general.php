<?php
session_start();

// Ha a felhasználó nincs bejelentkezve, irányítsuk át a bejelentkezés oldalra
if (!isset($_SESSION["user_id"])) {
    header("Location: index.php");
    exit();
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prototípus</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    
    <header>
        <h2 class="logo">Logo</h2>
        <nav class="navigation">
            <a href="main.php">Főoldal</a>
            <a href="general.php">Kérdések</a>
            <a href="#">Kapcsolat</a>
            <a href="#">Súgó</a>
            <button class="btnLogin-popup" onclick="window.location.href='logout.php';">Kijelentkezés</button>
        </nav>
    </header>


    <div class="container">
        <label class="theme-switch">
            <input type="checkbox" id="theme-toggle">
            <span class="slider"></span>
        </label>
    </div>
    <h1>Üdv Generáljunk!</h1>


    <script src="darklight.js"></script>
    <script src="script.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    
</body>
</html>
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
    <link rel="stylesheet" href="style1.css">
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
    
    <div id="generatorSection">
        <select id="categorySelect" onchange="updateCheckboxes()">
            <option value="analizis_1">Analízis 1</option>
            <option value="analizis_2">Analízis 2</option>
        </select>
        <select id="questionCount">
            <option value="5">5</option>
            <option value="10">10</option>
            <option value="15">15</option>
            <option value="20">20</option>
        </select>
        <div id="analizis1" style="display:block;">
            <input type="checkbox" value="gyumolcsok">Gyümölcsök<br>
            <input type="checkbox" value="zoldsegek">Zöldségek<br>
        </div>

        <div id="analizis2" style="display:none;">
            <input type="checkbox" value="tejtermek">Tejtermék<br>
            <input type="checkbox" value="husok">Húsok<br>
        </div>

        <button onclick="generateQuestions()">Kérdések generálása</button>
    </div>

    <div class="loader">
        <span></span>
        <span></span>
        <span></span>
    </div>

    
    <div id="questionsSection" style="display:none;">
        <div id="questions"></div>
        <progress id="progressBar" value="0" max="100" style="width: 100%; height: 20px; display: none;"></progress>
        <button id="backBtn" onclick="backQuestion()" style="display:none;">Vissza</button>
        <button id="nextBtn" onclick="nextQuestion()" style="display:none;">Következő</button>
        <button id="finishBtn" onclick="finishQuiz()" style="display:none;">Befejezés</button>
    </div>

    <script src="general.js"></script>
    <script src="darklight.js"></script>
    <script src="script.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    
</body>
</html>
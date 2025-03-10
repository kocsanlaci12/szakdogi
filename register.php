<?php
session_start();
require_once "config.php"; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST["name"]);
    $neptun = strtoupper(trim($_POST["neptun"])); // nagybetűssé alakítja
    $email = trim($_POST["email"]);
    $pwd = password_hash($_POST["pwd"], PASSWORD_DEFAULT); // Jelszó hashelése

    $name_parts = explode(" ", $name); // A név szóközök alapján történő felosztása
    $first_name = $name_parts[1] ?? '';

    // Ellenőrzés, hogy a Neptun-kód létezik-e
    $check_neptun_query = "SELECT id FROM users WHERE neptun = ?";
    $stmt = $conn->prepare($check_neptun_query);
    $stmt->bind_param("s", $neptun);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        echo "Hiba: A megadott Neptun-kód már regisztrálva van!";
        exit();
    }
    $stmt->close();

    // Ellenőrzés, hogy az email létezik-e
    $check_email_query = "SELECT id FROM users WHERE email = ?";
    $stmt = $conn->prepare($check_email_query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        echo "Hiba: A megadott email már regisztrálva van!";
        exit();
    }
    $stmt->close();

    $sql = "INSERT INTO users (name, neptun, email, pwd, created_at) VALUES (?, ?, ?, ?, NOW())";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $name, $neptun, $email, $pwd);

    if ($stmt->execute()) {
        echo "Sikeres regisztráció!";
    } else {
        echo "Hiba történt a regisztráció során.";
    }

    $stmt->close();
    $conn->close();
}
?>

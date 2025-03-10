<?php
session_start();
require_once "config.php"; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $neptun = strtoupper(trim($_POST["neptun"]));
    $password = trim($_POST["password"]);

    $sql = "SELECT id, name, neptun, pwd FROM users WHERE neptun = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $neptun);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        
        if (password_verify($password, $user["pwd"])) {
            $_SESSION["user_id"] = $user["id"];
            $_SESSION["user_name"] = $user["name"];
            $_SESSION["neptun"] = $user["neptun"];
            header("Location: main.php"); 
            exit();
        } else {
            echo "Hibás jelszó!";
        }
    } else {
        echo "Nincs ilyen Neptun-kóddal rendelkező felhasználó!";
    }

    $stmt->close();
    $conn->close();
}
?>

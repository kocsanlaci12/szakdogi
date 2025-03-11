<?php
header('Content-Type: application/json');
$conn = new mysqli("localhost", "root", "", "quiz_db");

if ($conn->connect_error) {
    die(json_encode(["error" => "Kapcsolódási hiba: " . $conn->connect_error]));
}

$data = json_decode(file_get_contents("php://input"), true);
$topics = $data['topics'];
$questionLimit = isset($data['questionCount']) ? (int)$data['questionCount'] : 5; // Alapértelmezett: 5 kérdés

if (empty($topics)) {
    echo json_encode(["error" => "Nincs kiválasztott kategória"]);
    exit;
}

$questions = [];

// 🔹 1. Összegyűjtjük az összes kérdést az összes kiválasztott kategóriából
foreach ($topics as $topic) {
    $question_table = "{$topic}_questions";
    $answer_table = "{$topic}_answers";

    $stmt = $conn->prepare("SELECT id, question, image_url FROM $question_table");
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $questions[] = [
            "id" => $row['id'],
            "question" => $row['question'],
            "image_url" => $row['image_url'],
            "topic" => $topic, // Mentjük, hogy melyik témából jött
            "answers" => []
        ];
    }
}

// 🔹 2. Véletlenszerűen kiválasztjuk a kívánt számú kérdést
shuffle($questions);
$questions = array_slice($questions, 0, min($questionLimit, count($questions))); // Ha kevesebb kérdés van, csak annyit küldünk vissza

// 🔹 3. Minden kiválasztott kérdéshez lekérdezzük a válaszokat
foreach ($questions as &$q) {
    $answer_table = "{$q['topic']}_answers"; // Az adott témának megfelelő válasz tábla
    $stmt_answers = $conn->prepare("SELECT answer_text, is_correct FROM $answer_table WHERE question_id = ?");
    $stmt_answers->bind_param("i", $q['id']);
    $stmt_answers->execute();
    $result_answers = $stmt_answers->get_result();

    while ($answer = $result_answers->fetch_assoc()) {
        $q['answers'][] = $answer;
    }

    // 🔹 4. A válaszokat is véletlenszerűen összekeverjük
    shuffle($q['answers']);
}

echo json_encode($questions);
$conn->close();
?>

// Függvény a checkboxok frissítéséhez
function updateCheckboxes() {
    let selectValue = document.getElementById("categorySelect").value;
    let options1 = document.getElementById("analizis1");
    let options2 = document.getElementById("analizis2");

    if (selectValue === "analizis_1") {
        options1.style.display = "block";
        options2.style.display = "none";
        clearCheckboxes("analizis2"); // Töröljük az Analízis 2 checkboxokat
    } else {
        options1.style.display = "none";
        options2.style.display = "block";
        clearCheckboxes("analizis1"); // Töröljük az Analízis 1 checkboxokat
    }
}

// Checkboxok törlése egy adott div-en belül
function clearCheckboxes(divId) {
    document.querySelectorAll(`#${divId} input[type="checkbox"]`).forEach(checkbox => {
        checkbox.checked = false;
    });
}

// Globális változók a kérdésekhez
let questions = [];
let currentQuestionIndex = 0;
let correctAnswers = 0; // Helyes válaszok száma
let userAnswers = []; // Az összes válasz, amit a felhasználó adott
let checkedAnswers = []; // Az, hogy a válaszokat már ellenőriztük-e

// Kérdések generálása
function generateQuestions() {
    let selectedTopics = [];
    let questionCount = document.getElementById("questionCount").value;

    document.querySelectorAll('input[type="checkbox"]:checked').forEach((checkbox) => {
        selectedTopics.push(checkbox.value);
    });

    if (selectedTopics.length === 0) {
        alert("Válassz legalább egy kategóriát!");
        return;
    }

    // 1. Generáló rész elrejtése
    document.getElementById("generatorSection").style.display = "none";

    // 2. Loader megjelenítése
    document.querySelector(".loader").style.display = "flex";

    // Szimuláljuk a kérdések betöltését egy kis késleltetéssel (2 másodperc)
    setTimeout(() => {
        // 3. Loader eltüntetése
        document.querySelector(".loader").style.display = "none";

        // 4. Kérdések szakasz megjelenítése
        document.getElementById("questionsSection").style.display = "block";
        document.getElementById("progressBar").style.display = "block"; // Progressziós sáv megjelenítése

        // Kérdések lekérése a szervertől
        fetch("get_questions.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ 
                topics: selectedTopics, 
                questionCount: parseInt(questionCount) 
            })
        })
        .then(response => response.json())
        .then(data => {
            questions = data;
            currentQuestionIndex = 0;
            showQuestion();
        });

    }, 2000); // 2 másodperces késleltetés
}

// Kérdések megjelenítése
function showQuestion() {
    let questionContainer = document.getElementById("questions");
    let nextButton = document.getElementById("nextBtn");
    let finishButton = document.getElementById("finishBtn");
    let backButton = document.getElementById("backBtn");

    if (currentQuestionIndex < questions.length) {
        let q = questions[currentQuestionIndex];
        let imageHtml = q.image_url ? `<img src="${q.image_url}" alt="Kép a kérdéshez" style="max-width:200px; display:block;">` : '';

        let answersHtml = q.answers.map((a, index) => {
            let isChecked = userAnswers[currentQuestionIndex] === a.answer_text ? "checked" : "";
            return `<li><input type="radio" name="q${q.id}" value="${a.answer_text}" data-correct="${a.is_correct}" ${isChecked}> ${a.answer_text}</li>`;
        }).join("");

        questionContainer.innerHTML = `${imageHtml}<p>${q.question}</p><ul>${answersHtml}</ul>`;

        if (currentQuestionIndex === questions.length - 1) {
            nextButton.style.display = "none";
            finishButton.style.display = "inline-block";
        } else {
            nextButton.style.display = "inline-block";
            finishButton.style.display = "none";
        }

        if (currentQuestionIndex > 0) {
            backButton.style.display = "inline-block";
        } else {
            backButton.style.display = "none";
        }

        updateProgressBar(); // A sáv frissítése minden kérdés megjelenítése után
    }
}

// Progressziós sáv frissítése
function updateProgressBar() {
    let progressBar = document.getElementById("progressBar");
    let progress = (currentQuestionIndex + 1) / questions.length * 100;
    progressBar.value = progress; // A sáv értéke
}

// Következő kérdés
function nextQuestion() {
    checkAnswer(); // Ellenőrizzük az adott kérdés válaszát
    currentQuestionIndex++;
    showQuestion();
}

// Válasz ellenőrzése
function checkAnswer() {
    let selectedAnswer = document.querySelector(`input[name="q${questions[currentQuestionIndex].id}"]:checked`);
    if (selectedAnswer) {
        userAnswers[currentQuestionIndex] = selectedAnswer.value; // Mentjük a választ

        if (!checkedAnswers[currentQuestionIndex]) { // Csak akkor számoljuk újra, ha még nem ellenőriztük
            let isCorrect = selectedAnswer.getAttribute("data-correct") === "1";
            if (isCorrect) {
                correctAnswers++;
            }
            checkedAnswers[currentQuestionIndex] = true; // Jelöljük, hogy ezt a kérdést már ellenőriztük
        }
    }
}

// Előző kérdés
function backQuestion() {
    checkAnswer(); // Az előző kérdést is mentjük el
    currentQuestionIndex--;
    showQuestion();
}

// Kvíz befejezése
function finishQuiz() {
    checkAnswer(); // Ellenőrizzük az utolsó kérdést is
    document.getElementById("questions").innerHTML = `<h2>Végeredmény</h2>
        <p>Helyes válaszok: ${correctAnswers} / ${questions.length}</p>
        <button onclick="restartQuiz()">Szeretnél újat generálni?</button>`; // Új generálás gomb
    document.getElementById("finishBtn").style.display = "none";
    document.getElementById("backBtn").style.display = "none"; // Elrejtjük a vissza gombot befejezés után
    document.getElementById("progressBar").style.display = "none";
}

// Új kérdések generálása
function restartQuiz() {
    document.getElementById("generatorSection").style.display = "block"; // Generáló rész megjelenítése
    document.getElementById("questionsSection").style.display = "none"; // Kérdések elrejtése
    document.getElementById("progressBar").style.display = "none"; // A progressziós sáv eltüntetése
    document.getElementById("questions").innerHTML = ""; // A kérdések törlése
    currentQuestionIndex = 0; // A kérdések indexének nullázása
    correctAnswers = 0; // A helyes válaszok számának nullázása
    userAnswers = []; // A válaszok törlése
    checkedAnswers = []; // Az ellenőrzés törlése
}

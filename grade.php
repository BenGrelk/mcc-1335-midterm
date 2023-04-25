<!-- Process and validate the form data -->
<?php

$assignments = array();
for ($i = 1; $i <= 5; $i++) {
    $assignments[] = array($_POST["a" . $i . "e"], $_POST["a" . $i . "p"]);
}

$error = "";
// Check each assignment is valid and a number
foreach ($assignments as $assignment) {
    if (!is_numeric($assignment[0]) || !is_numeric($assignment[1])) {
        $error .= "Please enter a number for each assignment.<br>";
        break;
    }
}

$quizzes = array();
for ($i = 1; $i <= 5; $i++) {
    $quizzes[] = array($_POST["q" . $i . "e"], $_POST["q" . $i . "p"]);
}

foreach ($quizzes as $quiz) {
    if (!is_numeric($quiz[0]) || !is_numeric($quiz[1])) {
        $error .= "Please enter a number for each quiz.<br>";
        break;
    }
}

if ($error != "") {
    // Go back to form, only passing in the error message
    header("Location: index.php?error=" . urlencode($error));
} else {
    // Go back to the form to display the percentages of each assignment and quiz
    header("Location: index.php?assignments=" . urlencode(serialize($assignments)) . "&quizzes=" . urlencode(serialize($quizzes)));
}

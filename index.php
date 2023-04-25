<?php
// Get the letter grade (including +/-) from the percentage
function getGrade($percentage)
{
    if ($percentage >= 97) {
        return "A+";
    } elseif ($percentage >= 93) {
        return "A";
    } elseif ($percentage >= 90) {
        return "A-";
    } elseif ($percentage >= 87) {
        return "B+";
    } elseif ($percentage >= 83) {
        return "B";
    } elseif ($percentage >= 80) {
        return "B-";
    } elseif ($percentage >= 77) {
        return "C+";
    } elseif ($percentage >= 73) {
        return "C";
    } elseif ($percentage >= 70) {
        return "C-";
    } elseif ($percentage >= 67) {
        return "D+";
    } elseif ($percentage >= 63) {
        return "D";
    } elseif ($percentage >= 60) {
        return "D-";
    } else {
        return "F";
    }
}

// Get the arrays from the url if present
if (isset($_GET["assignments"]) && isset($_GET["quizzes"])) {
    $assignments = unserialize(urldecode($_GET["assignments"]));
    $quizzes = unserialize(urldecode($_GET["quizzes"]));
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Grade Calculator</title>
    <link rel="stylesheet" href="main.css">
</head>
<body>
<header>
    <h1>Grade Calculator</h1>
</header>
<main>
    <!-- Display the error message if there is one -->
    <?php if (isset($_GET["error"])): ?>
        <p class="error"><?php echo $_GET["error"] ?></p>
    <?php endif ?>
    <!-- Create two tables, one to get the user's earned and possible points for assignments 1-5 and the other for quizzes 1-5 -->
    <form action="grade.php" method="post">
        <table id="assignment_input">
            <tr>
                <th colspan="3">Assignments</th>
            </tr>
            <tr>
                <th>#</th>
                <th colspan="2">Points</th>
            </tr>
            <tr>
                <th></th>
                <th>Earned</th>
                <th>Possible</th>
            </tr>
            <?php for ($i = 1;
                       $i <= 5;
                       $i++): ?>
                <tr>
                    <td><?php echo $i ?></td>
                    <td><label>
                            <?php
                            if (isset($assignments)) {
                                $evalue = $assignments[$i - 1][0];
                                $pvalue = $assignments[$i - 1][1];
                            }
                            ?>
                            <input type="text" name="a<?php echo $i ?>e" size="5"
                                   value="<?php isset($assignments) ? print($evalue) : print(""); ?>">
                        </label></td>
                    <td><label>
                            <input type="text" name="a<?php echo $i ?>p" size="5"
                                   value="<?php isset($assignments) ? print($pvalue) : print(""); ?>">
                        </label></td>
                </tr>
            <?php endfor ?>
        </table>
        <table id="quiz_input">
            <tr>
                <th colspan="3">Quizzes</th>
            </tr>
            <tr>
                <th>#</th>
                <th colspan="2">Points</th>
            </tr>
            <tr>
                <th></th>
                <th>Earned</th>
                <th>Possible</th>
            </tr>
            <?php for ($i = 1;
                       $i <= 5;
                       $i++): ?>
                <tr>
                    <td><?php echo $i ?></td>
                    <td><label>
                            <?php
                            if (isset($quizzes)) {
                                $evalue = $quizzes[$i - 1][0];
                                $pvalue = $quizzes[$i - 1][1];
                            }
                            ?>
                            <input type="text" name="q<?php echo $i ?>e" size="5"
                                   value="<?php isset($quizzes) ? print($evalue) : print(""); ?>">
                        </label></td>
                    <td><label>
                            <input type="text" name="q<?php echo $i ?>p" size="5"
                                   value="<?php isset($quizzes) ? print($pvalue) : print(""); ?>">
                        </label></td>
                </tr>
            <?php endfor ?>
        </table>

        <input id="submit" type="submit" value="Calculate Grade">
    </form>
    <!-- If the php variables "assignments" and "quizzes" are set, display the percentages of each assignment and
    quiz and the letter grade (A, B, C, ...) in two tables -->
    <?php if (isset($_GET["assignments"]) && isset($_GET["quizzes"])): ?>
        <?php
        // Get the earned and possible points from each assignment from the form in an array
        $assignments = unserialize($_GET["assignments"]);
        $quizzes = unserialize($_GET["quizzes"]);
        ?>
        <table id="assignment_display">
            <tr>
                <th colspan="3">Assignment Grades</th>
            </tr>
            <tr>
                <th>#</th>
                <th>Score</th>
                <th>Grade</th>
            <?php
            $i = 1;
            foreach ($assignments as $assignment): ?>
                <tr>
                    <td><?php
                        echo $i;
                        $i++;
                        ?></td>
                    <td><?php
                        $grade = round($assignment[0] / $assignment[1] * 100, 2);

                        if (is_nan($grade)) {
                            $grade = 0;
                        }

                        echo $grade . "%";
                        ?></td>
                    <td><?php echo getGrade($grade) ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
        <table id="quiz_display">
            <tr>
                <th colspan="3">Quiz Grades</th>
            </tr>
            <tr>
                <th>#</th>
                <th>Score</th>
                <th>Grade</th>
            </tr>
            <?php
            $i = 1;
            foreach ($quizzes as $quiz): ?>
                <tr>
                    <td><?php
                        echo $i;
                        $i++;
                        ?></td>
                    <td><?php
                        $grade = round($quiz[0] / $quiz[1] * 100, 2);

                        if (is_nan($grade)) {
                            $grade = 0;
                        }

                        echo $grade . "%";
                        ?></td>
                    <td><?php echo getGrade($grade) ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
        <br>
        <?php
        // Calculate the total earned and possible points from the assignments and quizzes
        $totalEarned = 0;
        $totalPossible = 0;
        foreach ($assignments as $assignment) {
            $totalEarned += $assignment[0];
            $totalPossible += $assignment[1];
        }
        foreach ($quizzes as $quiz) {
            $totalEarned += $quiz[0];
            $totalPossible += $quiz[1];
        }
        ?>
        <p>
            Total: <?php echo round($totalEarned / $totalPossible * 100, 2) . "%"; ?>
        </p>
    <?php endif; ?>

</main>
<footer>
    <p>
        &copy;
        <?php echo date("Y"); ?>
        <!-- my name in link to email -->
        <a href="mailto:bjgrelk@mail.mccneb.edu">
            Benjamin Grelk
        </a>
    </p>
</footer>
</body>
</html>
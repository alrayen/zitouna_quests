<?php

include_once(__DIR__ . '/../../../../../Controller/question-controller.php');


if (isset($_GET['quiz_id'])) {
    $quizId = intval($_GET['quiz_id']);
    $questionController = new QuestionController();
    $questions = $questionController->getQuestionsForQuiz($quizId);

    if (count($questions) > 0) {
        echo '<div class="p-4 bg-gray-50 rounded-lg shadow-inner">';
        echo '<h6 class="text-sm font-bold text-blue-500 mb-3 uppercase">Questions for this Quiz</h6>';
        echo '<div class="overflow-x-auto">';
        echo '<table class="w-full text-sm text-left text-gray-500">';
        echo '<thead class="text-xs text-gray-700 uppercase bg-gray-200">';
        echo '<tr>
                <th class="px-4 py-2">Question Text</th>
                <th class="px-4 py-2">Option 1</th>
                <th class="px-4 py-2">Option 2</th>
                <th class="px-4 py-2">Option 3</th>
                <th class="px-4 py-2">Option 4</th>
                <th class="px-4 py-2">Correct Answer</th>
              </tr>';
        echo '</thead><tbody>';

        foreach ($questions as $q) {
            
            
            $text = method_exists($q, 'getTextQuestion') ? $q->getTextQuestion() : $q->text;
            $opt1 = method_exists($q, 'getOption1') ? $q->getOption1() : $q->option1;
            $opt2 = method_exists($q, 'getOption2') ? $q->getOption2() : $q->option2;
            $opt3 = method_exists($q, 'getOption3') ? $q->getOption3() : $q->option3;
            $opt4 = method_exists($q, 'getOption4') ? $q->getOption4() : $q->option4;

            $correct = method_exists($q, 'getBonneReponse') ? $q->getBonneReponse() : $q->bonne;

            echo '<tr class="bg-white border-b">';
            echo '<td class="px-4 py-2 font-medium text-gray-900">' . htmlspecialchars($text) . '</td>';
            echo '<td class="px-4 py-2">' . htmlspecialchars($opt1) . '</td>';
            echo '<td class="px-4 py-2">' . htmlspecialchars($opt2) . '</td>';
            echo '<td class="px-4 py-2">' . htmlspecialchars($opt3) . '</td>';
            echo '<td class="px-4 py-2">' . htmlspecialchars($opt4) . '</td>';
            echo '<td class="px-4 py-2 text-green-600 font-bold">' . htmlspecialchars($correct) . '</td>';
            echo '</tr>';
        }

        echo '</tbody></table></div></div>';
    } else {
        echo '<div class="p-4 text-center text-gray-500">No questions found for this quiz.</div>';
    }
}
?>
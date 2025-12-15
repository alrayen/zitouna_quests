<?php
include_once(__DIR__ . '/../../../../../Controller/question-controller.php');

if (isset($_GET['quiz_id'])) {
    $quizId = intval($_GET['quiz_id']);
    $questionController = new QuestionController();
    $questions = $questionController->getQuestionsForQuiz($quizId);

    // 1. ADD BUTTON (Top Right)
    echo '<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:15px; padding:10px; background:#f8f9fe; border-radius:8px;">';
    echo '<h6 style="margin:0; color:#5e72e4; font-weight:bold;">QUESTIONS LIST</h6>';
    echo '<button onclick="openAddQuestionModal('.$quizId.')" style="background:#5e72e4; color:white; border:none; padding:8px 16px; border-radius:5px; cursor:pointer; font-weight:bold;">+ Add Question</button>';
    echo '</div>';

    if (count($questions) > 0) {
        echo '<table style="width:100%; text-align:left; border-collapse:collapse;">';
        echo '<thead style="background:#f6f9fc; color:#8898aa; font-size:12px;">';
        echo '<tr><th style="padding:10px;">Question</th><th style="padding:10px;">Option1</th><th style="padding:10px;">Option2</th><th style="padding:10px;">Option3</th><th style="padding:10px;">Option4</th><th style="padding:10px;">Correct Answer</th><th style="padding:10px; text-align:center;">Actions</th></tr>';
        echo '</thead><tbody>';

        foreach ($questions as $q) {
            // Prepare data for JS
            $qData = htmlspecialchars(json_encode([
                'id' => $q->getIdQuestion(),
                'quiz_id' => $q->getIdQuiz(),
                'text' => $q->getTextQuestion(),
                'o1' => $q->getOption1(), 'o2' => $q->getOption2(),
                'o3' => $q->getOption3(), 'o4' => $q->getOption4(),
                'correct' => $q->getBonneReponse()
            ]), ENT_QUOTES, 'UTF-8');

            echo '<tr style="border-bottom:1px solid #e9ecef;">';
            echo '<td style="padding:12px; font-size:14px; color:#525f7f;">' . htmlspecialchars($q->getTextQuestion()) . '</td>';
            echo '<td style="padding:12px; font-size:14px; color:#525f7f;">' . htmlspecialchars($q->getOption1()) . '</td>';
echo '<td style="padding:12px; font-size:14px; color:#525f7f;">' . htmlspecialchars($q->getOption2()) . '</td>';
echo '<td style="padding:12px; font-size:14px; color:#525f7f;">' . htmlspecialchars($q->getOption3()) . '</td>';
echo '<td style="padding:12px; font-size:14px; color:#525f7f;">' . htmlspecialchars($q->getOption4()) . '</td>';
            echo '<td style="padding:12px; font-size:14px; color:#2dce89; font-weight:bold;">' . htmlspecialchars($q->getBonneReponse()) . '</td>';
            
            // 2. EDIT AND DELETE BUTTONS (In the row)
            echo '<td style="padding:12px; text-align:center;">';
            
            // Edit Button
            echo '<button onclick="openEditQuestionModal(this)" data-question=\''.$qData.'\' style="background:none; border:none; cursor:pointer; color:#11cdef; margin-right:10px; font-size:16px;" title="Edit"><i class="fas fa-pencil-alt"></i></button>';
            
            // Delete Button
            echo '<button onclick="openDeleteQuestionModal('.$q->getIdQuestion().')" style="background:none; border:none; cursor:pointer; color:#f5365c; font-size:16px;" title="Delete"><i class="fas fa-trash"></i></button>';
            
            echo '</td>';
            echo '</tr>';
        }
        echo '</tbody></table>';
    } else {
        echo '<p style="text-align:center; color:#8898aa; padding:20px;">No questions found.</p>';
    }
}
?>
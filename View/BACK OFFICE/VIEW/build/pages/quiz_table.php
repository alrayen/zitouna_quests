<?php
include_once(__DIR__ . '/../../../../../Controller/quiz-controller.php');
include_once(__DIR__ . '/../../../../../Model/quiz.php'); 

$QuizController = new QuizController();


if (isset($_POST['add_quiz'])) {
    $titre = $_POST['add_titre'];
    $categorie = $_POST['add_categorie'];
    $niveau = $_POST['add_niveau'];
    $points = $_POST['add_points'];
    $newQuiz = new Quiz(null, $titre, $categorie, $niveau, $points);
    $QuizController->addQuiz($newQuiz);
    header("Location: quiz_table.php?status=added");
    exit();
}


if (isset($_POST['update_quiz'])) {
    $id = $_POST['edit_quiz_id'];
    $titre = $_POST['edit_titre'];
    $categorie = $_POST['edit_categorie'];
    $niveau = $_POST['edit_niveau'];
    $points = $_POST['edit_points'];
    $updatedQuiz = new Quiz($id, $titre, $categorie, $niveau, $points);
    $QuizController->updateQuiz($updatedQuiz);
    header("Location: quiz_table.php?status=updated");
    exit();
}


if (isset($_POST['delete_quiz'])) {
    $idquiz = $_POST['id_quiz'];
    $QuizController->deleteQuiz($idquiz);
    header("Location: quiz_table.php");
    exit();
}

$quizzes = $QuizController->listQuizzes();
$totalQuizzes = count($quizzes); 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Modern Quiz Admin</title>
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link href="../assets/css/nucleo-icons.css" rel="stylesheet" />
    <link href="../assets/css/nucleo-svg.css" rel="stylesheet" />
    <link href="../assets/css/argon-dashboard-tailwind.css?v=1.0.1" rel="stylesheet" />
    
    <style>
        
        .custom-modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(5px);
            z-index: 9999 !important;
            display: none; 
            align-items: center;
            justify-content: center;
        }
        
        .custom-modal-box {
            background: white;
            border-radius: 1rem;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            overflow: hidden;
            width: 90%; 
            max-width: 500px !important; 
            margin: auto;
        }

.error-msg {
    display: none; 
    color: red;
    font-size: 12px;
    font-weight: bold;
}

.error-msg.active {
    display: block; 
}    
</style>
</head>

<body class="m-0 font-sans text-base antialiased font-normal dark:bg-slate-900 leading-default bg-gray-50 text-slate-500">
    <div class="absolute w-full bg-gradient-to-r from-blue-500 to-cyan-500 dark:hidden min-h-75 h-75 rounded-b-3xl shadow-lg"></div>

    <aside class="fixed inset-y-0 flex-wrap items-center justify-between block w-full p-0 my-4 overflow-y-auto antialiased transition-transform duration-200 -translate-x-full bg-white border-0 shadow-2xl dark:shadow-none dark:bg-slate-850 xl:ml-6 max-w-64 ease-nav-brand z-990 rounded-2xl xl:left-0 xl:translate-x-0" aria-expanded="false">
      <div class="h-19">
        <i class="absolute top-0 right-0 p-4 opacity-50 cursor-pointer fas fa-times dark:text-white text-slate-400 xl:hidden" sidenav-close></i>
        <a class="block px-4 pt-2 pb-0 m-0 text-center" href="../pages/dashboard.html">
          <img src="../assets/img/logouna.png" class="inline h-full max-w-full transition-all duration-200 ease-nav-brand max-h-10" alt="main_logo" />
        </a>
      </div>
      <hr class="h-px mt-0 bg-transparent bg-gradient-to-r from-transparent via-black/40 to-transparent" />
      <div class="items-center block w-auto max-h-screen overflow-auto h-sidenav grow basis-full" style="padding-top: 20px;">
        <ul class="flex flex-col pl-0 mb-0">
            <li class="w-full mt-4">
                <h6 class="pl-6 ml-2 text-xs font-bold leading-tight uppercase opacity-60">Admin Tools</h6>
            </li>
            <li class="w-full mt-2">
              <a class="py-2.7 text-sm ease-nav-brand my-0 mx-2 flex items-center whitespace-nowrap px-4 transition-colors hover:bg-gray-100 rounded-lg" href="../pages/users_table.php">
                <div class="mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-white shadow-sm stroke-0 text-center xl:p-2.5 text-orange-500">
                  <i class="ni ni-single-02"></i>
                </div>
                <span class="ml-1 duration-300 opacity-100 pointer-events-none ease">Users</span>
              </a>
            </li>
            <li class="w-full mt-2">
              <a class="py-2.7 bg-white shadow-md text-sm ease-nav-brand my-0 mx-2 flex items-center whitespace-nowrap rounded-lg px-4 font-semibold text-slate-700 transition-colors" href="../pages/quiz_table.php">
                <div class="mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-gradient-to-tl from-blue-500 to-violet-500 shadow-sm stroke-0 text-center xl:p-2.5 text-white">
                  <i class="ni ni-spaceship"></i>
                </div>
                <span class="ml-1 duration-300 opacity-100 pointer-events-none ease">Quiz Management</span>
              </a>
            </li>
        </ul>
      </div>
    </aside>

    <main class="relative h-full max-h-screen transition-all duration-200 ease-in-out xl:ml-68 rounded-xl">
      <nav class="relative flex flex-wrap items-center justify-between px-0 py-2 mx-6 transition-all ease-in shadow-none duration-250 rounded-2xl lg:flex-nowrap lg:justify-start" navbar-main navbar-scroll="false">
        <div class="flex items-center justify-between w-full px-4 py-1 mx-auto flex-wrap-inherit">
          <nav>
            <ol class="flex flex-wrap pt-1 mr-12 bg-transparent rounded-lg sm:mr-16">
              <li class="text-sm leading-normal">
                <a class="text-white opacity-50" href="javascript:;">Pages</a>
              </li>
              <li class="text-sm pl-2 capitalize leading-normal text-white before:float-left before:pr-2 before:text-white before:content-['/']" aria-current="page">Quizzes</li>
            </ol>
            <h6 class="mb-0 font-bold text-white capitalize">Quiz Dashboard</h6>
          </nav>
        </div>
      </nav>

      <div class="w-full px-6 py-6 mx-auto">
        <div class="flex flex-wrap -mx-3 mb-6">
             <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/4">
              <div class="relative flex flex-col min-w-0 break-words bg-white shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
                <div class="flex-auto p-4">
                  <div class="flex flex-row -mx-3">
                    <div class="flex-none w-2/3 max-w-full px-3">
                      <div>
                        <p class="mb-0 font-sans text-sm font-semibold leading-normal uppercase dark:text-white dark:opacity-60">Total Quizzes</p>
                        <h5 class="mb-2 font-bold dark:text-white"><?php echo $totalQuizzes; ?></h5>
                      </div>
                    </div>
                    <div class="px-3 text-right basis-1/3">
                      <div class="inline-block w-12 h-12 text-center rounded-circle bg-gradient-to-tl from-blue-500 to-violet-500">
                        <i class="ni ni-money-coins text-lg relative top-3.5 text-white"></i>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            
            <div class="w-full max-w-full px-3 sm:w-1/2 sm:flex-none xl:w-1/4 ml-auto">
                 <button type="button" onclick="openAddModal()" class="w-full bg-white hover:scale-[1.02] transition-transform shadow-xl rounded-2xl p-4 flex items-center justify-center gap-3 cursor-pointer border-2 border-transparent hover:border-blue-400">
                    <div class="w-12 h-12 rounded-full bg-gradient-to-tl from-emerald-500 to-teal-400 flex items-center justify-center shadow-lg">
                        <i class="fas fa-plus text-white text-xl"></i>
                    </div>
                    <div class="text-left">
                        <h6 class="text-sm font-bold text-slate-700 mb-0">Create New Quiz</h6>
                        <p class="text-xs text-slate-400 mb-0">Click to add</p>
                    </div>
                 </button>
            </div>
        </div>

        <div class="flex flex-wrap -mx-3">
          <div class="flex-none w-full max-w-full px-3">
            <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border overflow-hidden">
              <div class="p-6 pb-0 mb-0 border-b-0 border-b-solid rounded-t-2xl border-b-transparent bg-gradient-to-r from-gray-50 to-white">
                <h6 class="dark:text-white font-bold text-lg text-slate-700"><i class="fas fa-list mr-2 text-blue-500"></i> Manage Quizzes</h6>
              </div>
              <div class="flex-auto px-0 pt-0 pb-2">
                <div class="p-0 overflow-x-auto">
                  <table class="items-center w-full mb-0 align-top border-collapse dark:border-white/40 text-slate-500">
                    <thead class="align-bottom">
                      <tr class="bg-gray-50 text-left">
                        <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Quiz Details</th>
                        <th class="px-6 py-3 pl-2 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Category</th>
                        <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Difficulty</th>
                        <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Score</th>
                        <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Actions</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php if (!empty($quizzes)): ?>
                          <?php foreach ($quizzes as $quiz): ?>
                          <tr class="hover:bg-gray-50 transition-all duration-200 border-b border-gray-100">
        <td class="p-4 align-middle bg-transparent whitespace-nowrap shadow-transparent">
          <div class="flex px-2 py-1">
            <div>
              <div class="inline-flex items-center justify-center mr-4 text-sm text-white transition-all duration-200 ease-in-out h-10 w-10 rounded-full shadow-md bg-gradient-to-tl from-blue-500 to-violet-500 ring-2 ring-white">
                <i class="fas fa-question text-xs"></i>
              </div>
            </div>
            <div class="flex flex-col justify-center">
              <h6 class="mb-0 text-sm font-bold leading-normal dark:text-white text-slate-700"><?php echo $quiz->getTitre(); ?></h6>
              <p class="mb-0 text-xs text-slate-400">ID: #<?php echo $quiz->getIdQuiz(); ?></p>
            </div>
          </div>
        </td>
        <td class="p-2 align-middle bg-transparent whitespace-nowrap shadow-transparent">
            <p class="mb-0 text-xs font-semibold leading-tight dark:text-white dark:opacity-80"><?php echo $quiz->getCategorie(); ?></p>
        </td>
        <td class="p-2 text-sm leading-normal text-center align-middle bg-transparent whitespace-nowrap shadow-transparent">
          <span class="bg-gradient-to-tl from-emerald-500 to-teal-400 px-3 text-xs rounded-full py-1.5 inline-block whitespace-nowrap text-center align-baseline font-bold uppercase leading-none text-white shadow-sm"><?php echo $quiz->getNiveau(); ?></span>
        </td>
        <td class="p-2 text-sm leading-normal text-center align-middle bg-transparent whitespace-nowrap shadow-transparent">
            <p class="mb-0 text-xs font-bold leading-tight text-slate-600"><?php echo $quiz->getPoints(); ?> pts</p>
        </td>
        
        <td class="p-2 text-center align-middle bg-transparent whitespace-nowrap shadow-transparent">
            <div class="flex justify-center items-center gap-2">
                <button type="button" 
                        onclick="toggleQuestions(<?php echo $quiz->getIdQuiz(); ?>)"
                        class="w-8 h-8 rounded-full bg-gray-100 hover:bg-teal-500 hover:text-white flex items-center justify-center transition-all shadow-sm cursor-pointer border-none"
                        title="View Questions">
                    <i class="fas fa-eye text-xs"></i>
                </button>

                <button type="button" 
                        onclick="openEditModalFromButton(this)"
                        class="w-8 h-8 rounded-full bg-gray-100 hover:bg-blue-500 hover:text-white flex items-center justify-center transition-all shadow-sm cursor-pointer border-none"
                        data-quiz='<?php echo htmlspecialchars(json_encode([
                            'id_quiz' => $quiz->getIdQuiz(),
                            'titre' => $quiz->getTitre(),
                            'categorie' => $quiz->getCategorie(),
                            'niveau' => $quiz->getNiveau(),
                            'points' => $quiz->getPoints()
                        ]), ENT_QUOTES, 'UTF-8'); ?>'>
                    <i class="fas fa-pencil-alt text-xs"></i>
                </button>

                <form method="post" action="quiz_table.php" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this quiz?');">
                    <input type="hidden" name="id_quiz" value="<?php echo $quiz->getIdQuiz(); ?>">
                    <button type="submit" name="delete_quiz" class="w-8 h-8 rounded-full bg-gray-100 hover:bg-red-500 hover:text-white flex items-center justify-center transition-all shadow-sm cursor-pointer border-none">
                        <i class="fas fa-trash text-xs"></i>
                    </button>
                </form>
            </div>
        </td>
      </tr>

      <tr id="questions-row-<?php echo $quiz->getIdQuiz(); ?>" style="display:none;" class="bg-gray-50">
          <td colspan="5" class="p-4">
              <div id="questions-content-<?php echo $quiz->getIdQuiz(); ?>" class="text-center">
                  <i class="fas fa-spinner fa-spin text-blue-500"></i> Loading questions...
              </div>
          </td>
      </tr>

      <?php endforeach; ?>
  <?php else: ?>
      <tr><td colspan="5" class="text-center p-8 text-gray-400">No quizzes found.</td></tr>
  <?php endif; ?>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </main>
    
    <div id="addQuizModal" class="custom-modal-overlay">
        <div class="custom-modal-box">
            <div style="background-color: #5e72e4;" class="p-6 flex justify-between items-center">
                <h6 class="text-white font-bold text-lg m-0">New Quiz</h6>
                <button type="button" onclick="closeAddModal()" class="text-white hover:text-gray-200 border-none bg-transparent cursor-pointer"><i class="fas fa-times text-lg"></i></button>
            </div>
            <form id="addQuizForm" action="quiz_table.php" method="POST" novalidate>
                <input type="hidden" name="add_quiz" value="1">
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Titre</label>
                        <input type="text" id="add_titre" name="add_titre" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-blue-500 outline-none bg-gray-50" />
                        <p id="error_add_titre" class="error-msg"></p>
                    </div>

                    <div class="flex gap-4">
                        <div class="w-1/2">
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Catégorie</label>
                            <select id="add_categorie" name="add_categorie" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-blue-500 outline-none bg-gray-50 cursor-pointer">
                                <option value="Environment">Environment</option>
                                <option value="Science">Science</option>
                                <option value="History">History</option>
                                <option value="Social Impact">Social Impact</option>
                                <option value="Personal Growth">Personal Growth</option>
                            </select>
                            <p id="error_add_categorie" class="error-msg"></p>
                        </div>
                        <div class="w-1/2">
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Niveau</label>
                            <select id="add_niveau" name="add_niveau" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-blue-500 outline-none bg-gray-50 cursor-pointer">
                                <option value="Easy">Easy</option>
                                <option value="Medium">Medium</option>
                                <option value="Hard">Hard</option>
                                <option value="Extreme">Extreme</option>
                            </select>
                            <p id="error_add_niveau" class="error-msg"></p>
                        </div>
                    </div>

                    <div>
                         <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Points</label>
                         <input type="number" id="add_points" name="add_points" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-blue-500 outline-none bg-gray-50" />
                         <p id="error_add_points" class="error-msg"></p>
                    </div>
                </div>
                <div class="px-6 pb-6 flex justify-end gap-3">
                    <button type="button" onclick="closeAddModal()" class="px-6 py-3 text-slate-600 font-bold text-sm bg-gray-100 rounded-xl cursor-pointer border-none">Cancel</button>
                    <button type="submit" style="background-color: #5e72e4; color: white;" class="px-6 py-3 font-bold text-sm rounded-xl cursor-pointer border-none hover:shadow-lg">Create</button>
                </div>
            </form>
        </div>
    </div>

    <div id="editQuizModal" class="custom-modal-overlay">
        <div class="custom-modal-box">
             <div style="background-color: #fb6340;" class="p-6 flex justify-between items-center">
                <h6 class="text-white font-bold text-lg m-0">Edit Quiz</h6>
                <button type="button" onclick="closeEditModal()" class="text-white hover:text-gray-200 border-none bg-transparent cursor-pointer"><i class="fas fa-times text-lg"></i></button>
            </div>
            <form id="editQuizForm" action="quiz_table.php" method="POST" novalidate>
                <input type="hidden" name="update_quiz" value="1">
                <input type="hidden" name="edit_quiz_id" id="modal_quiz_id">
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Titre</label>
                        <input type="text" id="edit_titre" name="edit_titre" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-orange-400 outline-none bg-gray-50" />
                        <p id="error_edit_titre" class="error-msg"></p>
                    </div>
                    <div class="flex gap-4">
                        <div class="w-1/2">
                             <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Categorie</label>
                             <select id="edit_categorie" name="edit_categorie" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-orange-400 outline-none bg-gray-50 cursor-pointer">
                                <option value="Environment">Environment</option>
                                <option value="Science">Science</option>
                                <option value="History">History</option>
                                <option value="Social Impact">Social Impact</option>
                                <option value="Personal Growth">Personal Growth</option>
                            </select>
                            <p id="error_edit_categorie" class="error-msg"></p>
                        </div>
                         <div class="w-1/2">
                             <label class="block text-xs font-bold text-slate-500 uppercase mb-2">level</label>
                             <select id="edit_niveau" name="edit_niveau" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-orange-400 outline-none bg-gray-50 cursor-pointer">
                                <option value="Easy">Easy</option>
                                <option value="Medium">Medium</option>
                                <option value="Hard">Hard</option>
                                <option value="Extreme">Extreme</option>
                            </select>
                            <p id="error_edit_niveau" class="error-msg"></p>
                        </div>
                    </div>
                     <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Points</label>
                        <input type="number" id="edit_points" name="edit_points" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-orange-400 outline-none bg-gray-50" />
                        <p id="error_edit_points" class="error-msg"></p>
                    </div>
                </div>
                <div class="px-6 pb-6 flex justify-end gap-3">
                    <button type="button" onclick="closeEditModal()" class="px-6 py-3 text-slate-600 font-bold text-sm bg-gray-100 rounded-xl cursor-pointer border-none">Cancel</button>
                    <button type="submit" style="background-color: #fb6340; color: white;" class="px-6 py-3 font-bold text-sm rounded-xl cursor-pointer border-none hover:shadow-lg">Save</button>
                </div>
            </form>
        </div>
    </div>
    
    <script src="../assets/js/plugins/perfect-scrollbar.min.js" async></script>
    <script src="../assets/js/argon-dashboard-tailwind.js?v=1.0.1" async></script>
 <script src="validationquiz.js"></script>
    <script>
    
    function openAddModal() {
        document.getElementById('addQuizModal').style.display = 'flex';
        document.body.style.overflow = 'hidden'; 
    }

    function closeAddModal() {
        document.getElementById('addQuizModal').style.display = 'none';
        document.body.style.overflow = ''; 
    }

    function openEditModalFromButton(button) {
        try {
            const quizDataJson = button.getAttribute('data-quiz');
            const quiz = JSON.parse(quizDataJson);
            
            
            document.getElementById('modal_quiz_id').value = quiz.id_quiz;
            document.getElementById('edit_titre').value = quiz.titre;       
            document.getElementById('edit_categorie').value = quiz.categorie; 
            document.getElementById('edit_niveau').value = quiz.niveau;       
            document.getElementById('edit_points').value = quiz.points;       

            document.getElementById('editQuizModal').style.display = 'flex';
            document.body.style.overflow = 'hidden';
        } catch (e) {
            console.error("Error opening edit modal:", e);
            alert("Error loading data. Check console.");
        }
    }

    function closeEditModal() {
        document.getElementById('editQuizModal').style.display = 'none';
        document.body.style.overflow = ''; 
    }

    
    window.onclick = function(event) {
        const addModal = document.getElementById('addQuizModal');
        const editModal = document.getElementById('editQuizModal');
        if (event.target == addModal) closeAddModal();
        if (event.target == editModal) closeEditModal();
    }
    function toggleQuestions(quizId) {
        const row = document.getElementById('questions-row-' + quizId);
        const contentDiv = document.getElementById('questions-content-' + quizId);

        
        if (row.style.display === 'none') {
            
            row.style.display = 'table-row';
            
            
            if (!contentDiv.getAttribute('data-loaded')) {
                fetch(`getquestions.php?quiz_id=${quizId}`)
                    .then(response => response.text())
                    .then(html => {
                        contentDiv.innerHTML = html;
                        contentDiv.setAttribute('data-loaded', 'true'); 
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        contentDiv.innerHTML = '<span class="text-red-500">Error loading questions.</span>';
                    });
            }
        } else {
            
            row.style.display = 'none';
        }
    }
</script>
</body>
</html>
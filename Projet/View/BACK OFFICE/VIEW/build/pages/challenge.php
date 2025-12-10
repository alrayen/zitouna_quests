<?php
// Adjust these paths to match your folder structure
include_once(__DIR__ . '/../../../../../Controller/challenge-controller.php');
include_once(__DIR__ . '/../../../../../Model/challenge.php');

$ChallengeController = new ChallengeController();

// --- HANDLE ADD CHALLENGE ---
if (isset($_POST['add_challenge'])) {
    $titre = $_POST['add_titre'];
    $description = $_POST['add_description'];
    $categorie = $_POST['add_categorie'];
    $points = (int)$_POST['add_points'];
    $time = (int)$_POST['add_time'];
    $difficulty = $_POST['add_difficulty'];
    $status = $_POST['add_status'];
    $place = $_POST['add_place'];

    // ID is 0 for new entries (Auto Increment in DB)
    $newChallenge = new Challenge(0, $titre, $description, $categorie, $points, $time, $difficulty, $status, $place);
    $ChallengeController->addChallenge($newChallenge);
    
    header("Location: challenge.php?status=added");
    exit();
}

// --- HANDLE UPDATE CHALLENGE ---
if (isset($_POST['update_challenge'])) {
    $id = (int)$_POST['edit_challenge_id'];
    $titre = $_POST['edit_titre'];
    $description = $_POST['edit_description'];
    $categorie = $_POST['edit_categorie'];
    $points = (int)$_POST['edit_points'];
    $time = (int)$_POST['edit_time'];
    $difficulty = $_POST['edit_difficulty'];
    $status = $_POST['edit_status'];
    $place = $_POST['edit_place'];

    $updatedChallenge = new Challenge($id, $titre, $description, $categorie, $points, $time, $difficulty, $status, $place);
    $ChallengeController->updateChallenge($updatedChallenge);
    
    header("Location: challenge.php?status=updated");
    exit();
}

// --- HANDLE DELETE CHALLENGE ---
if (isset($_POST['delete_challenge'])) {
    $id_defi = (int)$_POST['id_defi'];
    $ChallengeController->deleteChallenge($id_defi);
    header("Location: challenge.php");
    exit();
}

// --- FETCH DATA ---
$challenges = $ChallengeController->listChallenges();
$totalChallenges = count($challenges); 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Modern Challenge Admin</title>
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
            overflow-y: auto;
            max-height: 90vh;
            width: 90%; 
            max-width: 600px !important; /* Made slightly wider for more fields */
            margin: auto;
        }
        /* Styling for JavaScript Error Messages */
        .error-msg { 
            color: #e53e3e; 
            font-size: 0.75rem; 
            font-weight: bold; 
            margin-top: 0.25rem; 
            display: none; 
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
              <a class="py-2.7 bg-white shadow-md text-sm ease-nav-brand my-0 mx-2 flex items-center whitespace-nowrap rounded-lg px-4 font-semibold text-slate-700 transition-colors" href="../pages/challenge.php">
                <div class="mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-gradient-to-tl from-blue-500 to-violet-500 shadow-sm stroke-0 text-center xl:p-2.5 text-white">
                  <i class="ni ni-trophy"></i>
                </div>
                <span class="ml-1 duration-300 opacity-100 pointer-events-none ease">Challenges</span>
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
              <li class="text-sm pl-2 capitalize leading-normal text-white before:float-left before:pr-2 before:text-white before:content-['/']" aria-current="page">Challenges</li>
            </ol>
            <h6 class="mb-0 font-bold text-white capitalize">Challenge Dashboard</h6>
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
                        <p class="mb-0 font-sans text-sm font-semibold leading-normal uppercase dark:text-white dark:opacity-60">Total Challenges</p>
                        <h5 class="mb-2 font-bold dark:text-white"><?php echo $totalChallenges; ?></h5>
                      </div>
                    </div>
                    <div class="px-3 text-right basis-1/3">
                      <div class="inline-block w-12 h-12 text-center rounded-circle bg-gradient-to-tl from-blue-500 to-violet-500">
                        <i class="ni ni-trophy text-lg relative top-3.5 text-white"></i>
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
                        <h6 class="text-sm font-bold text-slate-700 mb-0">New Challenge</h6>
                        <p class="text-xs text-slate-400 mb-0">Click to add</p>
                    </div>
                 </button>
            </div>
        </div>

        <div class="flex flex-wrap -mx-3">
          <div class="flex-none w-full max-w-full px-3">
            <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border overflow-hidden">
              <div class="p-6 pb-0 mb-0 border-b-0 border-b-solid rounded-t-2xl border-b-transparent bg-gradient-to-r from-gray-50 to-white">
                <h6 class="dark:text-white font-bold text-lg text-slate-700"><i class="fas fa-list mr-2 text-blue-500"></i> Manage Challenges</h6>
              </div>
              <div class="flex-auto px-0 pt-0 pb-2">
                <div class="p-0 overflow-x-auto">
                  <table class="items-center w-full mb-0 align-top border-collapse dark:border-white/40 text-slate-500">
                    <thead class="align-bottom">
                      <tr class="bg-gray-50 text-left">
                        <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Challenge Info</th>
                        <th class="px-6 py-3 pl-2 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Category</th>
                        <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Difficulty</th>
                        <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Points / Time</th>
                        <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Status</th>
                        <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Actions</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php if (!empty($challenges)): ?>
                          <?php foreach ($challenges as $challenge): ?>
                          <tr class="hover:bg-gray-50 transition-all duration-200 border-b border-gray-100">
        <td class="p-4 align-middle bg-transparent whitespace-nowrap shadow-transparent">
          <div class="flex px-2 py-1">
            <div>
              <div class="inline-flex items-center justify-center mr-4 text-sm text-white transition-all duration-200 ease-in-out h-10 w-10 rounded-full shadow-md bg-gradient-to-tl from-blue-500 to-violet-500 ring-2 ring-white">
                <i class="fas fa-puzzle-piece text-xs"></i>
              </div>
            </div>
            <div class="flex flex-col justify-center">
              <h6 class="mb-0 text-sm font-bold leading-normal dark:text-white text-slate-700"><?php echo htmlspecialchars($challenge->getTitre()); ?></h6>
              <p class="mb-0 text-xs text-slate-400">ID: #<?php echo $challenge->getIdDefi(); ?></p>
              <p class="mb-0 text-xs text-slate-400 truncate max-w-xs" title="<?php echo htmlspecialchars($challenge->getDescription()); ?>">
                <?php echo substr(htmlspecialchars($challenge->getDescription()), 0, 30) . '...'; ?>
              </p>
            </div>
          </div>
        </td>
        
        <td class="p-2 align-middle bg-transparent whitespace-nowrap shadow-transparent">
            <p class="mb-0 text-xs font-semibold leading-tight dark:text-white dark:opacity-80"><?php echo htmlspecialchars($challenge->getCategorie()); ?></p>
            <p class="text-xs text-slate-400 mb-0"><i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($challenge->getPlace()); ?></p>
        </td>
        
        <td class="p-2 text-sm leading-normal text-center align-middle bg-transparent whitespace-nowrap shadow-transparent">
          <?php 
            $diffColor = 'from-gray-500 to-slate-400';
            $d = strtolower($challenge->getDifficulty());
            if(strpos($d, 'easy') !== false) $diffColor = 'from-emerald-500 to-teal-400';
            if(strpos($d, 'medium') !== false) $diffColor = 'from-orange-500 to-yellow-400';
            if(strpos($d, 'hard') !== false) $diffColor = 'from-red-600 to-rose-400';
            if(strpos($d, 'expert') !== false) $diffColor = 'from-red-600 to-red-400';
          ?>
          <span class="bg-gradient-to-tl <?php echo $diffColor; ?> px-3 text-xs rounded-full py-1.5 inline-block whitespace-nowrap text-center align-baseline font-bold uppercase leading-none text-white shadow-sm"><?php echo htmlspecialchars($challenge->getDifficulty()); ?></span>
        </td>
        
        <td class="p-2 text-sm leading-normal text-center align-middle bg-transparent whitespace-nowrap shadow-transparent">
            <p class="mb-0 text-xs font-bold leading-tight text-slate-600"><?php echo $challenge->getPoints(); ?> pts</p>
            <p class="mb-0 text-xs text-slate-400"><?php echo $challenge->getTime(); ?> mins</p>
        </td>

        <td class="p-2 text-sm leading-normal text-center align-middle bg-transparent whitespace-nowrap shadow-transparent">
            <span class="text-xs font-bold <?php echo strtolower($challenge->getStatus()) == 'active' ? 'text-emerald-500' : 'text-slate-400'; ?>">
                <?php echo htmlspecialchars($challenge->getStatus()); ?>
            </span>
        </td>
        
        <td class="p-2 text-center align-middle bg-transparent whitespace-nowrap shadow-transparent">
            <div class="flex justify-center items-center gap-2">
                <button type="button" 
                        onclick="openEditModalFromButton(this)"
                        class="w-8 h-8 rounded-full bg-gray-100 hover:bg-blue-500 hover:text-white flex items-center justify-center transition-all shadow-sm cursor-pointer border-none"
                        data-challenge='<?php echo htmlspecialchars(json_encode([
                            'id_defi' => $challenge->getIdDefi(),
                            'titre' => $challenge->getTitre(),
                            'description' => $challenge->getDescription(),
                            'categorie' => $challenge->getCategorie(),
                            'points' => $challenge->getPoints(),
                            'time' => $challenge->getTime(),
                            'difficulty' => $challenge->getDifficulty(),
                            'status' => $challenge->getStatus(),
                            'place' => $challenge->getPlace()
                        ]), ENT_QUOTES, 'UTF-8'); ?>'>
                    <i class="fas fa-pencil-alt text-xs"></i>
                </button>

                <button type="button" 
        onclick="openDeleteModal(<?php echo $challenge->getIdDefi(); ?>)" 
        class="w-8 h-8 rounded-full bg-gray-100 hover:bg-red-500 hover:text-white flex items-center justify-center transition-all shadow-sm cursor-pointer border-none"
        title="Delete Challenge">
    <i class="fas fa-trash text-xs"></i>
</button>
            </div>
        </td>
      </tr>
      <?php endforeach; ?>
  <?php else: ?>
      <tr><td colspan="6" class="text-center p-8 text-gray-400">No challenges found.</td></tr>
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
    
    <div id="addChallengeModal" class="custom-modal-overlay">
        <div class="custom-modal-box">
            <div style="background-color: #5e72e4;" class="p-6 flex justify-between items-center">
                <h6 class="text-white font-bold text-lg m-0">New Challenge</h6>
                <button type="button" onclick="closeAddModal()" class="text-white hover:text-gray-200 border-none bg-transparent cursor-pointer"><i class="fas fa-times text-lg"></i></button>
            </div>
            <form id="addChallengeForm" action="challenge.php" method="POST">
                <input type="hidden" name="add_challenge" value="1">
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Titre</label>
                        <input type="text" id="add_titre" name="add_titre" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-blue-500 outline-none bg-gray-50" />
                        <p id="error_add_titre" class="error-msg"></p>
                    </div>
                    
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Description</label>
                        <textarea id="add_description" name="add_description" rows="3" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-blue-500 outline-none bg-gray-50"></textarea>
                        <p id="error_add_description" class="error-msg"></p>
                    </div>

                    <div class="flex gap-4">
                        <div class="w-1/2">
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Catégorie</label>
                            <select id="add_categorie" name="add_categorie" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-blue-500 outline-none bg-gray-50 cursor-pointer">
                                <option value="Code">Code</option>
                                <option value="Design">Design</option>
                                <option value="Logic">Logic</option>
                                <option value="Innovation">Innovation</option>
                            </select>
                            <p id="error_add_categorie" class="error-msg"></p>
                        </div>
                        <div class="w-1/2">
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Difficulty</label>
                            <select id="add_difficulty" name="add_difficulty" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-blue-500 outline-none bg-gray-50 cursor-pointer">
                                <option value="Easy">Easy</option>
                                <option value="Medium">Medium</option>
                                <option value="Hard">Hard</option>
                                <option value="Expert">Expert</option>
                            </select>
                             <p id="error_add_difficulty" class="error-msg"></p>
                        </div>
                    </div>

                    <div class="flex gap-4">
                         <div class="w-1/2">
                             <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Points</label>
                             <input type="number" id="add_points" name="add_points" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-blue-500 outline-none bg-gray-50" />
                             <p id="error_add_points" class="error-msg"></p>
                         </div>
                         <div class="w-1/2">
                             <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Time (min)</label>
                             <input type="number" id="add_time" name="add_time" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-blue-500 outline-none bg-gray-50" />
                             <p id="error_add_time" class="error-msg"></p>
                         </div>
                    </div>

                    <div class="flex gap-4">
                        <div class="w-1/2">
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Status</label>
                            <select id="add_status" name="add_status" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-blue-500 outline-none bg-gray-50 cursor-pointer">
                                <option value="Active">Active</option>
                                <option value="Inactive">Inactive</option>
                            </select>
                            <p id="error_add_status" class="error-msg"></p>
                        </div>
                        <div class="w-1/2">
                             <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Place</label>
                             <input type="text" id="add_place" name="add_place" placeholder="e.g. Lab 1" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-blue-500 outline-none bg-gray-50" />
                             <p id="error_add_place" class="error-msg"></p>
                        </div>
                    </div>
                </div>
                <div class="px-6 pb-6 flex justify-end gap-3">
                    <button type="button" onclick="closeAddModal()" class="px-6 py-3 text-slate-600 font-bold text-sm bg-gray-100 rounded-xl cursor-pointer border-none">Cancel</button>
                    <button type="submit" style="background-color: #5e72e4; color: white;" class="px-6 py-3 font-bold text-sm rounded-xl cursor-pointer border-none hover:shadow-lg">Create</button>
                </div>
            </form>
        </div>
    </div>

    <div id="editChallengeModal" class="custom-modal-overlay">
        <div class="custom-modal-box">
             <div style="background-color: #fb6340;" class="p-6 flex justify-between items-center">
                <h6 class="text-white font-bold text-lg m-0">Edit Challenge</h6>
                <button type="button" onclick="closeEditModal()" class="text-white hover:text-gray-200 border-none bg-transparent cursor-pointer"><i class="fas fa-times text-lg"></i></button>
            </div>
            <form id="editChallengeForm" action="challenge.php" method="POST">
                <input type="hidden" name="update_challenge" value="1">
                <input type="hidden" name="edit_challenge_id" id="modal_challenge_id">
                <div class="p-6 space-y-4">
                    
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Titre</label>
                        <input type="text" id="edit_titre" name="edit_titre" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-orange-400 outline-none bg-gray-50" />
                        <p id="error_edit_titre" class="error-msg"></p>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Description</label>
                        <textarea id="edit_description" name="edit_description" rows="3" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-orange-400 outline-none bg-gray-50"></textarea>
                        <p id="error_edit_description" class="error-msg"></p>
                    </div>

                    <div class="flex gap-4">
                        <div class="w-1/2">
                             <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Categorie</label>
                             <select id="edit_categorie" name="edit_categorie" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-orange-400 outline-none bg-gray-50 cursor-pointer">
                                <option value="Code">Code</option>
                                <option value="Design">Design</option>
                                <option value="Logic">Logic</option>
                                <option value="Innovation">Innovation</option>
                            </select>
                            <p id="error_edit_categorie" class="error-msg"></p>
                        </div>
                         <div class="w-1/2">
                             <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Difficulty</label>
                             <select id="edit_difficulty" name="edit_difficulty" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-orange-400 outline-none bg-gray-50 cursor-pointer">
                                <option value="Easy">Easy</option>
                                <option value="Medium">Medium</option>
                                <option value="Hard">Hard</option>
                                <option value="Expert">Expert</option>
                            </select>
                            <p id="error_edit_difficulty" class="error-msg"></p>
                        </div>
                    </div>

                    <div class="flex gap-4">
                        <div class="w-1/2">
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Points</label>
                            <input type="number" id="edit_points" name="edit_points" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-orange-400 outline-none bg-gray-50" />
                            <p id="error_edit_points" class="error-msg"></p>
                        </div>
                        <div class="w-1/2">
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Time (min)</label>
                            <input type="number" id="edit_time" name="edit_time" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-orange-400 outline-none bg-gray-50" />
                            <p id="error_edit_time" class="error-msg"></p>
                        </div>
                    </div>

                     <div class="flex gap-4">
                        <div class="w-1/2">
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Status</label>
                            <select id="edit_status" name="edit_status" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-orange-400 outline-none bg-gray-50 cursor-pointer">
                                <option value="Active">Active</option>
                                <option value="Inactive">Inactive</option>
                            </select>
                            <p id="error_edit_status" class="error-msg"></p>
                        </div>
                        <div class="w-1/2">
                             <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Place</label>
                             <input type="text" id="edit_place" name="edit_place" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-orange-400 outline-none bg-gray-50" />
                             <p id="error_edit_place" class="error-msg"></p>
                        </div>
                    </div>
                </div>
                <div class="px-6 pb-6 flex justify-end gap-3">
                    <button type="button" onclick="closeEditModal()" class="px-6 py-3 text-slate-600 font-bold text-sm bg-gray-100 rounded-xl cursor-pointer border-none">Cancel</button>
                    <button type="submit" style="background-color: #fb6340; color: white;" class="px-6 py-3 font-bold text-sm rounded-xl cursor-pointer border-none hover:shadow-lg">Save</button>
                </div>
            </form>
        </div>
    </div>
    <div id="deleteChallengeModal" class="custom-modal-overlay">
    <div class="custom-modal-box">
        <div style="background-color: #f5365c;" class="p-6 flex justify-between items-center">
            <h6 class="text-white font-bold text-lg m-0">Delete Challenge</h6>
            <button type="button" onclick="closeDeleteModal()" class="text-white hover:text-gray-200 border-none bg-transparent cursor-pointer">
                <i class="fas fa-times text-lg"></i>
            </button>
        </div>
        
        <div class="p-6 text-center">
            <div class="mb-4">
                <i class="fas fa-exclamation-triangle text-5xl text-red-500 opacity-50"></i>
            </div>
            <h4 class="font-bold text-slate-700 mb-2">Are you sure?</h4>
            <p class="text-sm text-slate-500 mb-0">Do you really want to delete this challenge? This process cannot be undone.</p>
        </div>

        <form action="challenge.php" method="POST">
            <input type="hidden" name="delete_challenge" value="1">
            <input type="hidden" name="id_defi" id="delete_modal_defi_id">
            
            <div class="px-6 pb-6 flex justify-center gap-3">
                <button type="button" onclick="closeDeleteModal()" class="px-6 py-3 text-slate-600 font-bold text-sm bg-gray-100 rounded-xl cursor-pointer border-none hover:bg-gray-200 transition-colors">Cancel</button>
                <button type="submit" style="background-color: #f5365c; color: white;" class="px-6 py-3 font-bold text-sm rounded-xl cursor-pointer border-none hover:shadow-lg transition-shadow">Yes, Delete</button>
            </div>
        </form>
    </div>
</div>
    <script src="../assets/js/plugins/perfect-scrollbar.min.js" async></script>
    <script src="../assets/js/argon-dashboard-tailwind.js?v=1.0.1" async></script>
    
    <script>
    // Helper function to show errors
    // --- ADD FORM VALIDATION ---
function validateAddForm() {
    // 1. Get all the values from the Add form
    var titre = document.getElementById('add_titre').value;
    var description = document.getElementById('add_description').value;
    var points = document.getElementById('add_points').value;
    var time = document.getElementById('add_time').value;
    var place = document.getElementById('add_place').value;

    // 2. Check Title
    if (titre == "") {
        document.getElementById('error_add_titre').innerHTML = "Title is required";
        document.getElementById('error_add_titre').style.display = "block";
        return false; // Stop here
    } else if (titre.length < 3) {
        document.getElementById('error_add_titre').innerHTML = "Title too short (3 chars min)";
        document.getElementById('error_add_titre').style.display = "block";
        return false;
    } else {
        // Hide error if it's okay
        document.getElementById('error_add_titre').style.display = "none";
    }

    // 3. Check Description
    if (description == "") {
        document.getElementById('error_add_description').innerHTML = "Description is required";
        document.getElementById('error_add_description').style.display = "block";
        return false;
    } else if (description.length < 10) {
        document.getElementById('error_add_description').innerHTML = "Description too short";
        document.getElementById('error_add_description').style.display = "block";
        return false;
    } else {
        document.getElementById('error_add_description').style.display = "none";
    }

    // 4. Check Points
    if (points == "") {
        document.getElementById('error_add_points').innerHTML = "Points required";
        document.getElementById('error_add_points').style.display = "block";
        return false;
    } else if (points <= 0) {
        document.getElementById('error_add_points').innerHTML = "Points must be positive";
        document.getElementById('error_add_points').style.display = "block";
        return false;
    } else {
        document.getElementById('error_add_points').style.display = "none";
    }

    // 5. Check Time
    if (time == "") {
        document.getElementById('error_add_time').innerHTML = "Time required";
        document.getElementById('error_add_time').style.display = "block";
        return false;
    } else {
        document.getElementById('error_add_time').style.display = "none";
    }

    // 6. Check Place
    if (place == "") {
        document.getElementById('error_add_place').innerHTML = "Place is required";
        document.getElementById('error_add_place').style.display = "block";
        return false;
    } else {
        document.getElementById('error_add_place').style.display = "none";
    }

    // If everything is okay
    return true;
}


// --- EDIT FORM VALIDATION (Copy pasted and changed IDs) ---
function validateEditForm() {
    var titre = document.getElementById('edit_titre').value;
    var description = document.getElementById('edit_description').value;
    var points = document.getElementById('edit_points').value;
    var place = document.getElementById('edit_place').value;

    // Check Title
    if (titre == "") {
        document.getElementById('error_edit_titre').innerHTML = "Title is required";
        document.getElementById('error_edit_titre').style.display = "block";
        return false;
    } else {
        document.getElementById('error_edit_titre').style.display = "none";
    }

    // Check Description
    if (description == "") {
        document.getElementById('error_edit_description').innerHTML = "Description is required";
        document.getElementById('error_edit_description').style.display = "block";
        return false;
    } else {
        document.getElementById('error_edit_description').style.display = "none";
    }

    // Check Points
    if (points <= 0) {
        document.getElementById('error_edit_points').innerHTML = "Must be positive";
        document.getElementById('error_edit_points').style.display = "block";
        return false;
    } else {
        document.getElementById('error_edit_points').style.display = "none";
    }

    // Check Place
    if (place == "") {
        document.getElementById('error_edit_place').innerHTML = "Place is required";
        document.getElementById('error_edit_place').style.display = "block";
        return false;
    } else {
        document.getElementById('error_edit_place').style.display = "none";
    }

    return true;
}

// --- MODAL FUNCTIONS ---
function openAddModal() {
        document.getElementById('addChallengeModal').style.display = 'flex';
        document.body.style.overflow = 'hidden'; // Stop scrolling
    }

    function closeAddModal() {
        document.getElementById('addChallengeModal').style.display = 'none';
        document.body.style.overflow = '';
    }

    function closeEditModal() {
        document.getElementById('editChallengeModal').style.display = 'none';
        document.body.style.overflow = '';
    }

    // --- NEW DELETE FUNCTIONS ---
    function openDeleteModal(id) {
        document.getElementById('delete_modal_defi_id').value = id; // Pass ID to form
        document.getElementById('deleteChallengeModal').style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    function closeDeleteModal() {
        document.getElementById('deleteChallengeModal').style.display = 'none';
        document.body.style.overflow = '';
    }
    // ----------------------------

    // Function to fill the edit form
    function openEditModalFromButton(btn) {
        var dataString = btn.getAttribute('data-challenge');
        var data = JSON.parse(dataString);

        document.getElementById('modal_challenge_id').value = data.id_defi;
        document.getElementById('edit_titre').value = data.titre;
        document.getElementById('edit_description').value = data.description;
        document.getElementById('edit_categorie').value = data.categorie;
        document.getElementById('edit_difficulty').value = data.difficulty;
        document.getElementById('edit_points').value = data.points;
        document.getElementById('edit_time').value = data.time;
        document.getElementById('edit_status').value = data.status;
        document.getElementById('edit_place').value = data.place;

        document.getElementById('editChallengeModal').style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    // Close modal when clicking outside
    window.onclick = function(event) {
        var modalAdd = document.getElementById('addChallengeModal');
        var modalEdit = document.getElementById('editChallengeModal');
        var modalDelete = document.getElementById('deleteChallengeModal'); // Add this
        
        if (event.target == modalAdd) closeAddModal();
        if (event.target == modalEdit) closeEditModal();
        if (event.target == modalDelete) closeDeleteModal(); // Add this
    }

    // Linking the forms to the validation functions
    document.getElementById('addChallengeForm').onsubmit = function() {
        return validateAddForm();
    };

    document.getElementById('editChallengeForm').onsubmit = function() {
        return validateEditForm();
    };
    </script>
</body>
</html>
<?php
include_once(__DIR__ . '/../../../../../Controller/challenge-controller.php');
include_once(__DIR__ . '/../../../../../Model/challenge.php');
include_once(__DIR__ . '/../../../../../Controller/ressources-controller.php');
include_once(__DIR__ . '/../../../../../Model/ressources-model.php');

$ChallengeController = new ChallengeController();
$RessourceController = new RessourceController();

// --- CHALLENGE ACTIONS ---
if (isset($_POST['add_challenge'])) {
    $titre = $_POST['add_titre'];
    $description = $_POST['add_description'];
    $categorie = $_POST['add_categorie'];
    $points = (int)$_POST['add_points'];
    $time = (int)$_POST['add_time'];
    $difficulty = $_POST['add_difficulty'];
    $status = $_POST['add_status'];
    $place = $_POST['add_place'];
    $newChallenge = new Challenge(0, $titre, $description, $categorie, $points, $time, $difficulty, $status, $place);
    $ChallengeController->addChallenge($newChallenge);
    header("Location: challenge.php?status=challenge_added");
    exit();
}

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
    header("Location: challenge.php?status=challenge_updated");
    exit();
}

if (isset($_POST['delete_challenge'])) {
    $id_defi = (int)$_POST['id_defi'];
    $ChallengeController->deleteChallenge($id_defi);
    header("Location: challenge.php");
    exit();
}

// --- RESOURCE ACTIONS ---

// 1. ADD RESOURCE (Updated for File Upload)
if (isset($_POST['add_resource'])) {
    $nom = $_POST['res_nom'];
    $type = $_POST['res_type'];
    $description = substr($_POST['res_description'], 0, 500);
    $id_defi = (int)$_POST['res_id_defi']; 
    $ordre = (int)$_POST['res_ordre'];
    $necessite_preuve = (bool)$_POST['res_necessite_preuve'];
    
    $finalUrl = '';

    // Logic: If PDF, handle file upload. Else, use URL input.
    if ($type === 'PDF') {
        if (isset($_FILES['file_upload']) && $_FILES['file_upload']['error'] === 0) {
            $allowed = ['pdf'];
            $filename = $_FILES['file_upload']['name'];
            $fileExt = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

            if (in_array($fileExt, $allowed)) {
                $newFileName = uniqid('res_', true) . '.' . $fileExt;
                // Path relative to where this PHP file runs
                // Adjust this path if your 'assets' folder is somewhere else!
                $uploadDir = '../assets/uploads/resources/'; 
                
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }

                $destPath = $uploadDir . $newFileName;

                if (move_uploaded_file($_FILES['file_upload']['tmp_name'], $destPath)) {
                    // Save relative path for front-end access
                    $finalUrl = 'assets/uploads/resources/' . $newFileName; 
                } else {
                    // Error handling (you might want a redirect with error msg)
                    die("Error moving uploaded file."); 
                }
            } else {
                die("Invalid file type. Only PDF allowed.");
            }
        }
    } else {
        // For Video/Link/Image types, use the text input
        $finalUrl = $_POST['res_url_input'];
    }

    // Fallback if upload failed or input empty
    if(empty($finalUrl)) $finalUrl = "#";

    $newResource = new Ressource(0, $nom, $type, $finalUrl, $description, $id_defi, $ordre, $necessite_preuve);
    $RessourceController->addResource($newResource);
    header("Location: challenge.php?open_resources=" . $id_defi); 
    exit();
}

// 2. UPDATE RESOURCE (Simplified - usually doesn't re-upload file for simplicity here)
if (isset($_POST['update_resource'])) {
    $id = (int)$_POST['res_edit_id'];
    $nom = $_POST['res_nom'];
    $type = $_POST['res_type'];
    
    // In edit mode, we might just keep the old URL if a new one isn't provided
    // For this implementation, we'll assume basic text edit or URL edit
    // (Full re-upload logic in Edit is complex, using text input for now)
    $url = $_POST['res_url_input']; 
    
    $description = substr($_POST['res_description'], 0, 500);
    $id_defi = (int)$_POST['res_id_defi'];
    $ordre = (int)$_POST['res_ordre'];
    $necessite_preuve = (bool)$_POST['res_necessite_preuve'];

    $updatedResource = new Ressource($id, $nom, $type, $url, $description, $id_defi, $ordre, $necessite_preuve);
    $RessourceController->updateResource($updatedResource);
    header("Location: challenge.php?open_resources=" . $id_defi);
    exit();
}

if (isset($_POST['delete_resource'])) {
    $id_ressource = (int)$_POST['del_res_id'];
    $RessourceController->deleteResource($id_ressource);
    header("Location: challenge.php");
    exit();
}

$challenges = $ChallengeController->listChallenges();
$totalChallenges = count($challenges);

$allResources = $RessourceController->listResources();
$resourcesJson = [];
if(!empty($allResources)) {
    foreach($allResources as $r) {
        $resourcesJson[] = [
            'id' => $r->getIdRessource(),
            'nom' => $r->getNom(),
            'type' => $r->getType(),
            'url' => $r->getUrl(),
            'description' => $r->getDescription(),
            'id_defi' => $r->getIdDefi(),
            'ordre' => $r->getOrdre(),
            'necessite_preuve' => $r->getNecessitePreuve() ? 1 : 0
        ];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Challenge & Resource Admin</title>
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link href="../assets/css/nucleo-icons.css" rel="stylesheet" />
    <link href="../assets/css/nucleo-svg.css" rel="stylesheet" />
    <link href="../assets/css/argon-dashboard-tailwind.css?v=1.0.1" rel="stylesheet" />
    
    <style>
        .custom-modal-overlay {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background-color: rgba(0, 0, 0, 0.5); backdrop-filter: blur(5px);
            z-index: 9999 !important; display: none; align-items: center; justify-content: center;
        }
        .custom-modal-box {
            background: white; border-radius: 1rem;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            overflow-y: auto; max-height: 90vh; width: 90%; max-width: 600px; margin: auto;
        }
        .custom-modal-box.wide-modal { max-width: 900px !important; }
        .error-msg { color: #e53e3e; font-size: 0.75rem; font-weight: bold; margin-top: 0.25rem; display: none; }

        /* DRAG & DROP ZONE STYLES */
        .drop-zone {
            border: 2px dashed #cbd5e1;
            border-radius: 10px;
            padding: 30px;
            text-align: center;
            transition: all 0.3s;
            background: #f8fafc;
            cursor: pointer;
            position: relative;
            margin-top: 5px;
        }
        .drop-zone:hover, .drop-zone.dragover {
            border-color: #5e72e4;
            background: #eef2ff;
        }
        .drop-zone i { font-size: 2.5rem; color: #94a3b8; margin-bottom: 10px; display: block; }
        .drop-zone p { margin: 0; color: #64748b; font-weight: 600; font-size: 0.9rem; }
        .drop-zone span { font-size: 0.75rem; color: #94a3b8; }
        
        .file-input {
            position: absolute; top: 0; left: 0; width: 100%; height: 100%;
            opacity: 0; cursor: pointer;
        }

        .drop-zone.has-file { border-color: #2dce89; background: #f0fff4; }
        .drop-zone.has-file i { color: #2dce89; }
    </style>
</head>

<body class="m-0 font-sans text-base antialiased font-normal dark:bg-slate-900 leading-default bg-gray-50 text-slate-500">
    <div class="absolute w-full bg-gradient-to-r from-blue-500 to-cyan-500 dark:hidden min-h-75 h-75 rounded-b-3xl shadow-lg"></div>

    <aside class="fixed inset-y-0 flex-wrap items-center justify-between block w-full p-0 my-4 overflow-y-auto antialiased transition-transform duration-200 -translate-x-full bg-white border-0 shadow-2xl dark:shadow-none dark:bg-slate-850 xl:ml-6 max-w-64 ease-nav-brand z-990 rounded-2xl xl:left-0 xl:translate-x-0">
      <div class="h-19">
        <i class="absolute top-0 right-0 p-4 opacity-50 cursor-pointer fas fa-times dark:text-white text-slate-400 xl:hidden" sidenav-close></i>
        <a class="block px-4 pt-2 pb-0 m-0 text-center" href="../pages/dashboard.html">
          <img src="../assets/img/logouna.png" class="inline h-full max-w-full transition-all duration-200 ease-nav-brand max-h-10" alt="main_logo" />
        </a>
      </div>
      <hr class="h-px mt-0 bg-transparent bg-gradient-to-r from-transparent via-black/40 to-transparent" />
      <div class="items-center block w-auto max-h-screen overflow-auto h-sidenav grow basis-full" style="padding-top: 20px;">
        <ul class="flex flex-col pl-0 mb-0">
            <li class="w-full mt-4"><h6 class="pl-6 ml-2 text-xs font-bold leading-tight uppercase opacity-60">Admin Tools</h6></li>
            <li class="w-full mt-2">
              <a class="py-2.7 text-sm ease-nav-brand my-0 mx-2 flex items-center whitespace-nowrap px-4 transition-colors hover:bg-gray-100 rounded-lg" href="../pages/users_table.php">
                <div class="mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-white shadow-sm stroke-0 text-center xl:p-2.5 text-orange-500"><i class="ni ni-single-02"></i></div>
                <span class="ml-1 duration-300 opacity-100 pointer-events-none ease">Users</span>
              </a>
            </li>
            <li class="w-full mt-2">
              <a class="py-2.7 bg-white shadow-md text-sm ease-nav-brand my-0 mx-2 flex items-center whitespace-nowrap rounded-lg px-4 font-semibold text-slate-700 transition-colors" href="challenge.php">
                <div class="mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-gradient-to-tl from-blue-500 to-violet-500 shadow-sm stroke-0 text-center xl:p-2.5 text-white"><i class="ni ni-trophy"></i></div>
                <span class="ml-1 duration-300 opacity-100 pointer-events-none ease">Challenges</span>
              </a>
            </li>
        </ul>
      </div>
    </aside>

    <main class="relative h-full max-h-screen transition-all duration-200 ease-in-out xl:ml-68 rounded-xl">
      <nav class="relative flex flex-wrap items-center justify-between px-0 py-2 mx-6 transition-all ease-in shadow-none duration-250 rounded-2xl lg:flex-nowrap lg:justify-start">
        <div class="flex items-center justify-between w-full px-4 py-1 mx-auto flex-wrap-inherit">
          <nav>
            <ol class="flex flex-wrap pt-1 mr-12 bg-transparent rounded-lg sm:mr-16">
              <li class="text-sm leading-normal"><a class="text-white opacity-50" href="javascript:;">Pages</a></li>
              <li class="text-sm pl-2 capitalize leading-normal text-white before:float-left before:pr-2 before:text-white before:content-['/']">Challenges</li>
            </ol>
            <h6 class="mb-0 font-bold text-white capitalize">Challenge & Resources</h6>
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
                        <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Challenge</th>
                        <th class="px-6 py-3 pl-2 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Details</th>
                        <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Stats</th>
                        <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Resources</th>
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
                                  <div class="inline-flex items-center justify-center mr-4 text-sm text-white h-10 w-10 rounded-full shadow-md bg-gradient-to-tl from-blue-500 to-violet-500 ring-2 ring-white">
                                    <i class="fas fa-puzzle-piece text-xs"></i>
                                  </div>
                                </div>
                                <div class="flex flex-col justify-center">
                                  <h6 class="mb-0 text-sm font-bold leading-normal dark:text-white text-slate-700"><?php echo htmlspecialchars($challenge->getTitre()); ?></h6>
                                  <p class="mb-0 text-xs text-slate-400">ID: #<?php echo $challenge->getIdDefi(); ?></p>
                                </div>
                              </div>
                            </td>
                            
                            <td class="p-2 align-middle bg-transparent whitespace-nowrap shadow-transparent">
                                <p class="mb-0 text-xs font-semibold leading-tight"><?php echo htmlspecialchars($challenge->getCategorie()); ?></p>
                                <?php 
                                    $d = strtolower($challenge->getDifficulty());
                                    $diffColor = match(true) {
                                        str_contains($d, 'easy') => 'text-emerald-500',
                                        str_contains($d, 'medium') => 'text-orange-500',
                                        str_contains($d, 'hard') => 'text-red-500',
                                        default => 'text-slate-500'
                                    };
                                ?>
                                <p class="text-xs <?php echo $diffColor; ?> font-bold mb-0"><?php echo htmlspecialchars($challenge->getDifficulty()); ?></p>
                            </td>
                            
                            <td class="p-2 text-sm text-center align-middle bg-transparent whitespace-nowrap shadow-transparent">
                                <span class="badge bg-gray-100 rounded-full px-2 py-1 text-xs font-bold text-slate-600"><?php echo $challenge->getPoints(); ?> pts</span>
                                <span class="badge bg-gray-100 rounded-full px-2 py-1 text-xs text-slate-500"><?php echo $challenge->getTime(); ?> min</span>
                            </td>

                            <td class="p-2 text-center align-middle bg-transparent whitespace-nowrap shadow-transparent">
                                <button type="button" 
                                    onclick="openResourceManager(<?php echo $challenge->getIdDefi(); ?>, '<?php echo addslashes($challenge->getTitre()); ?>')"
                                    class="inline-block px-4 py-2 text-xs font-bold text-white uppercase align-middle transition-all bg-gradient-to-tl from-emerald-500 to-teal-400 rounded-lg shadow-md hover:shadow-lg hover:scale-105 active:opacity-85 leading-pro cursor-pointer border-none">
                                    <i class="fas fa-search mr-1"></i> Explore
                                </button>
                            </td>
                            
                            <td class="p-2 text-center align-middle bg-transparent whitespace-nowrap shadow-transparent">
                                <div class="flex justify-center items-center gap-2">
                                    <button type="button" onclick="openEditModalFromButton(this)" class="w-8 h-8 rounded-full bg-gray-100 hover:bg-blue-500 hover:text-white flex items-center justify-center transition-all shadow-sm cursor-pointer border-none"
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
                                    <button type="button" onclick="openDeleteModal(<?php echo $challenge->getIdDefi(); ?>)" class="w-8 h-8 rounded-full bg-gray-100 hover:bg-red-500 hover:text-white flex items-center justify-center transition-all shadow-sm cursor-pointer border-none">
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
                <button type="button" onclick="closeAddModal()" class="text-white border-none bg-transparent cursor-pointer"><i class="fas fa-times text-lg"></i></button>
            </div>
            <form id="addChallengeForm" action="challenge.php" method="POST">
                <input type="hidden" name="add_challenge" value="1">
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Titre</label>
                        <input type="text" id="add_titre" name="add_titre" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-blue-500 outline-none bg-gray-50" />
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Description</label>
                        <textarea id="add_description" name="add_description" rows="3" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-blue-500 outline-none bg-gray-50"></textarea>
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
                        </div>
                        <div class="w-1/2">
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Difficulty</label>
                            <select id="add_difficulty" name="add_difficulty" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-blue-500 outline-none bg-gray-50 cursor-pointer">
                                <option value="Easy">Easy</option>
                                <option value="Medium">Medium</option>
                                <option value="Hard">Hard</option>
                                <option value="Expert">Expert</option>
                            </select>
                        </div>
                    </div>
                    <div class="flex gap-4">
                         <div class="w-1/2">
                             <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Points</label>
                             <input type="number" id="add_points" name="add_points" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-blue-500 outline-none bg-gray-50" />
                         </div>
                         <div class="w-1/2">
                             <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Time (min)</label>
                             <input type="number" id="add_time" name="add_time" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-blue-500 outline-none bg-gray-50" />
                         </div>
                    </div>
                    <div class="flex gap-4">
                        <div class="w-1/2">
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Status</label>
                            <select id="add_status" name="add_status" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-blue-500 outline-none bg-gray-50 cursor-pointer">
                                <option value="Active">Active</option>
                                <option value="Inactive">Inactive</option>
                            </select>
                        </div>
                        <div class="w-1/2">
                             <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Place</label>
                             <input type="text" id="add_place" name="add_place" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-blue-500 outline-none bg-gray-50" />
                        </div>
                    </div>
                </div>
                <div class="px-6 pb-6 flex justify-end gap-3">
                    <button type="button" onclick="closeAddModal()" class="px-6 py-3 text-slate-600 font-bold text-sm bg-gray-100 rounded-xl cursor-pointer border-none">Cancel</button>
                    <button type="submit" style="background-color: #5e72e4;" class="text-white px-6 py-3 font-bold text-sm rounded-xl cursor-pointer border-none hover:shadow-lg">Create</button>
                </div>
            </form>
        </div>
    </div>

    <div id="editChallengeModal" class="custom-modal-overlay">
        <div class="custom-modal-box">
             <div style="background-color: #fb6340;" class="p-6 flex justify-between items-center">
                <h6 class="text-white font-bold text-lg m-0">Edit Challenge</h6>
                <button type="button" onclick="closeEditModal()" class="text-white border-none bg-transparent cursor-pointer"><i class="fas fa-times text-lg"></i></button>
            </div>
            <form id="editChallengeForm" action="challenge.php" method="POST">
                <input type="hidden" name="update_challenge" value="1">
                <input type="hidden" name="edit_challenge_id" id="modal_challenge_id">
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Titre</label>
                        <input type="text" id="edit_titre" name="edit_titre" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-orange-400 outline-none bg-gray-50" />
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Description</label>
                        <textarea id="edit_description" name="edit_description" rows="3" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-orange-400 outline-none bg-gray-50"></textarea>
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
                        </div>
                         <div class="w-1/2">
                             <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Difficulty</label>
                             <select id="edit_difficulty" name="edit_difficulty" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-orange-400 outline-none bg-gray-50 cursor-pointer">
                                <option value="Easy">Easy</option>
                                <option value="Medium">Medium</option>
                                <option value="Hard">Hard</option>
                                <option value="Expert">Expert</option>
                            </select>
                        </div>
                    </div>
                    <div class="flex gap-4">
                        <div class="w-1/2">
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Points</label>
                            <input type="number" id="edit_points" name="edit_points" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-orange-400 outline-none bg-gray-50" />
                        </div>
                        <div class="w-1/2">
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Time (min)</label>
                            <input type="number" id="edit_time" name="edit_time" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-orange-400 outline-none bg-gray-50" />
                        </div>
                    </div>
                     <div class="flex gap-4">
                        <div class="w-1/2">
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Status</label>
                            <select id="edit_status" name="edit_status" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-orange-400 outline-none bg-gray-50 cursor-pointer">
                                <option value="Active">Active</option>
                                <option value="Inactive">Inactive</option>
                            </select>
                        </div>
                        <div class="w-1/2">
                             <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Place</label>
                             <input type="text" id="edit_place" name="edit_place" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-orange-400 outline-none bg-gray-50" />
                        </div>
                    </div>
                </div>
                <div class="px-6 pb-6 flex justify-end gap-3">
                    <button type="button" onclick="closeEditModal()" class="px-6 py-3 text-slate-600 font-bold text-sm bg-gray-100 rounded-xl cursor-pointer border-none">Cancel</button>
                    <button type="submit" style="background-color: #fb6340;" class="text-white px-6 py-3 font-bold text-sm rounded-xl cursor-pointer border-none hover:shadow-lg">Save</button>
                </div>
            </form>
        </div>
    </div>

    <div id="deleteChallengeModal" class="custom-modal-overlay">
        <div class="custom-modal-box">
            <div style="background-color: #f5365c;" class="p-6 flex justify-between items-center">
                <h6 class="text-white font-bold text-lg m-0">Delete Challenge</h6>
                <button type="button" onclick="closeDeleteModal()" class="text-white border-none bg-transparent cursor-pointer"><i class="fas fa-times text-lg"></i></button>
            </div>
            <div class="p-6 text-center">
                <div class="mb-4"><i class="fas fa-exclamation-triangle text-5xl text-red-500 opacity-50"></i></div>
                <h4 class="font-bold text-slate-700 mb-2">Are you sure?</h4>
                <p class="text-sm text-slate-500 mb-0">Do you really want to delete this challenge? It will delete all associated resources too.</p>
            </div>
            <form action="challenge.php" method="POST">
                <input type="hidden" name="delete_challenge" value="1">
                <input type="hidden" name="id_defi" id="delete_modal_defi_id">
                <div class="px-6 pb-6 flex justify-center gap-3">
                    <button type="button" onclick="closeDeleteModal()" class="px-6 py-3 text-slate-600 font-bold text-sm bg-gray-100 rounded-xl cursor-pointer border-none hover:bg-gray-200">Cancel</button>
                    <button type="submit" style="background-color: #f5365c;" class="text-white px-6 py-3 font-bold text-sm rounded-xl cursor-pointer border-none hover:shadow-lg">Yes, Delete</button>
                </div>
            </form>
        </div>
    </div>

    <div id="resourceManagerModal" class="custom-modal-overlay">
        <div class="custom-modal-box wide-modal">
            <div style="background-color: #fb6340;" class="p-6 flex justify-between items-center">
                <div class="text-white">
                    <h6 class="font-bold text-lg m-0"><i class="fas fa-layer-group mr-2"></i>Resources</h6>
                    <span id="resourceManagerTitle" class="text-xs opacity-80">For Challenge: ...</span>
                </div>
                <button type="button" onclick="closeResourceManager()" class="text-white border-none bg-transparent cursor-pointer"><i class="fas fa-times text-lg"></i></button>
            </div>
            
            <div class="p-6 bg-gray-50 border-b border-gray-200 flex justify-end">
                <button onclick="openResourceForm('add')" class="bg-gradient-to-tl from-emerald-500 to-teal-400 text-white px-4 py-2 rounded-lg text-xs font-bold hover:shadow-md transition-all border-none cursor-pointer">
                    <i class="fas fa-plus mr-1"></i> Add Resource
                </button>
            </div>

            <div class="p-0 overflow-x-auto">
                <table class="items-center w-full mb-0 align-top border-collapse text-slate-500">
                    <thead class="align-bottom bg-white text-xs uppercase font-bold text-slate-400">
                        <tr>
                            <th class="px-6 py-3 text-left">Resource Name</th>
                            <th class="px-6 py-3 text-left">Type / Link</th>
                            <th class="px-6 py-3 text-center">Order</th>
                            <th class="px-6 py-3 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="resourceListBody" class="bg-white">
                    </tbody>
                </table>
                <div id="noResourcesMsg" class="hidden p-8 text-center text-gray-400 text-sm">No resources added yet.</div>
            </div>
            <div class="h-4"></div>
        </div>
    </div>

    <div id="resourceFormModal" class="custom-modal-overlay">
        <div class="custom-modal-box">
             <div id="resFormHeader" class="p-6 flex justify-between items-center">
                <h6 class="text-white font-bold text-lg m-0" id="resFormTitle">Add Resource</h6>
                <button type="button" onclick="closeResourceForm()" class="text-white border-none bg-transparent cursor-pointer"><i class="fas fa-times text-lg"></i></button>
            </div>
            <form id="resourceForm" action="challenge.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="add_resource" id="action_add_res" disabled>
                <input type="hidden" name="update_resource" id="action_update_res" disabled>
                <input type="hidden" name="res_id_defi" id="res_id_defi">
                <input type="hidden" name="res_edit_id" id="res_edit_id"> 

                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Name</label>
                        <input type="text" id="res_nom" name="res_nom" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-blue-500 outline-none bg-gray-50" required />
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Description</label>
                        <textarea id="res_description" name="res_description" rows="3" maxlength="500" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-blue-500 outline-none bg-gray-50" required></textarea>
                    </div>
                    
                    <div class="flex gap-4">
                        <div class="w-1/2">
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Type</label>
                            <select id="res_type" name="res_type" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-blue-500 outline-none bg-gray-50" onchange="toggleInput()">
                                <option value="PDF">PDF Document (File Upload)</option>
                                <option value="Video">Video (URL)</option>
                                <option value="Link">External Link (URL)</option>
                                <option value="Image">Image (URL)</option>
                            </select>
                        </div>
                        <div class="w-1/2">
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Order</label>
                            <input type="number" id="res_ordre" name="res_ordre" value="1" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-blue-500 outline-none bg-gray-50" />
                        </div>
                    </div>

                    <div id="fileInputGroup" class="mb-3">
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Upload File</label>
                        <div class="drop-zone" id="dropZone">
                            <i class="fas fa-cloud-upload-alt"></i>
                            <p>Drag & Drop your PDF here</p>
                            <span>or click to browse</span>
                            <input type="file" name="file_upload" class="file-input" accept=".pdf" onchange="updateDropZone(this)">
                        </div>
                        <div id="fileNameDisplay" style="margin-top:10px; font-weight:bold; color:#2dce89; display:none; font-size: 0.8rem; text-align: center;"></div>
                    </div>

                    <div id="urlInputGroup" class="mb-3" style="display:none;">
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Resource URL</label>
                        <input type="text" id="res_url_input" name="res_url_input" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-blue-500 outline-none bg-gray-50" placeholder="https://..." />
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Requires Proof?</label>
                        <select id="res_necessite_preuve" name="res_necessite_preuve" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-blue-500 outline-none bg-gray-50">
                            <option value="0">No</option>
                            <option value="1">Yes</option>
                        </select>
                    </div>
                </div>
                <div class="px-6 pb-6 flex justify-end gap-3">
                    <button type="button" onclick="closeResourceForm()" class="px-6 py-3 text-slate-600 font-bold text-sm bg-gray-100 rounded-xl cursor-pointer border-none">Cancel</button>
                    <button type="submit" id="resFormSubmitBtn" class="text-white px-6 py-3 font-bold text-sm rounded-xl cursor-pointer border-none hover:shadow-lg">Save</button>
                </div>
            </form>
        </div>
    </div>
    
    <div id="deleteResourceModal" class="custom-modal-overlay">
        <div class="custom-modal-box">
            <div style="background-color: #f5365c;" class="p-6 flex justify-between items-center">
                <h6 class="text-white font-bold text-lg m-0">Delete Resource</h6>
                <button type="button" onclick="closeDelResModal()" class="text-white border-none bg-transparent cursor-pointer"><i class="fas fa-times text-lg"></i></button>
            </div>
            <div class="p-6 text-center">
                <h4 class="font-bold text-slate-700 mb-2">Confirm Deletion</h4>
                <p class="text-sm text-slate-500">Are you sure you want to remove this resource?</p>
            </div>
            <form action="challenge.php" method="POST">
                <input type="hidden" name="delete_resource" value="1">
                <input type="hidden" name="del_res_id" id="del_res_id">
                <div class="px-6 pb-6 flex justify-center gap-3">
                    <button type="button" onclick="closeDelResModal()" class="px-6 py-3 text-slate-600 bg-gray-100 rounded-xl border-none cursor-pointer">Cancel</button>
                    <button type="submit" style="background-color: #f5365c;" class="text-white px-6 py-3 rounded-xl border-none cursor-pointer">Delete</button>
                </div>
            </form>
        </div>
    </div>

    <script src="../assets/js/plugins/perfect-scrollbar.min.js" async></script>
    <script src="../assets/js/argon-dashboard-tailwind.js?v=1.0.1" async></script>
    
    <script>
    const ALL_RESOURCES = <?php echo json_encode($resourcesJson); ?>;
    let currentChallengeId = null;

    // --- DRAG & DROP LOGIC ---
    function toggleInput() {
        const type = document.getElementById('res_type').value;
        const fileGroup = document.getElementById('fileInputGroup');
        const urlGroup = document.getElementById('urlInputGroup');
        const fileInput = document.querySelector('.file-input');
        const urlInput = document.getElementById('res_url_input');

        if (type === 'PDF') {
            fileGroup.style.display = 'block';
            urlGroup.style.display = 'none';
            // In add mode, make file required. In edit, it's optional (keep old file)
            if(document.getElementById('action_add_res').disabled === false) {
                 fileInput.required = true;
            }
            urlInput.required = false;
        } else {
            fileGroup.style.display = 'none';
            urlGroup.style.display = 'block';
            fileInput.required = false;
            urlInput.required = true;
        }
    }

    const dropZone = document.getElementById('dropZone');
    const fileNameDisplay = document.getElementById('fileNameDisplay');

    // Visual feedback for drag
    ['dragenter', 'dragover'].forEach(eventName => {
        dropZone.addEventListener(eventName, (e) => {
            e.preventDefault();
            dropZone.classList.add('dragover');
        });
    });

    ['dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, (e) => {
            e.preventDefault();
            dropZone.classList.remove('dragover');
        });
    });

    function updateDropZone(input) {
        if (input.files && input.files[0]) {
            const file = input.files[0];
            dropZone.classList.add('has-file');
            dropZone.querySelector('p').innerText = "File Selected!";
            fileNameDisplay.innerText = file.name;
            fileNameDisplay.style.display = 'block';
            dropZone.querySelector('i').className = "fas fa-check-circle";
        }
    }
    // -------------------------

    function openAddModal() {
        document.getElementById('addChallengeModal').style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }
    function closeAddModal() {
        document.getElementById('addChallengeModal').style.display = 'none';
        document.body.style.overflow = '';
    }
    function closeEditModal() {
        document.getElementById('editChallengeModal').style.display = 'none';
        document.body.style.overflow = '';
    }
    function openDeleteModal(id) {
        document.getElementById('delete_modal_defi_id').value = id;
        document.getElementById('deleteChallengeModal').style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }
    function closeDeleteModal() {
        document.getElementById('deleteChallengeModal').style.display = 'none';
        document.body.style.overflow = '';
    }
    function openEditModalFromButton(btn) {
        var data = JSON.parse(btn.getAttribute('data-challenge'));
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

    function openResourceManager(defiId, defiTitle) {
        currentChallengeId = defiId;
        document.getElementById('resourceManagerTitle').innerText = "For Challenge: " + defiTitle;
        
        const relevantResources = ALL_RESOURCES.filter(r => r.id_defi == defiId);
        const tbody = document.getElementById('resourceListBody');
        tbody.innerHTML = ''; 

        if (relevantResources.length === 0) {
            document.getElementById('noResourcesMsg').classList.remove('hidden');
        } else {
            document.getElementById('noResourcesMsg').classList.add('hidden');
            relevantResources.forEach(res => {
                const tr = document.createElement('tr');
                tr.className = "border-b border-gray-100 hover:bg-gray-50";
                tr.innerHTML = `
                    <td class="p-4 align-middle">
                        <div class="flex items-center">
                            <div class="bg-blue-100 text-blue-500 rounded-full h-8 w-8 flex items-center justify-center mr-3"><i class="fas fa-file"></i></div>
                            <div>
                                <h6 class="text-sm font-bold text-slate-700 mb-0">${res.nom}</h6>
                                <p class="text-xs text-slate-400 mb-0 truncate w-40">${res.description.substring(0,30)}...</p>
                            </div>
                        </div>
                    </td>
                    <td class="p-4 align-middle text-sm text-slate-500">
                        <span class="badge bg-slate-100 px-2 py-1 rounded text-xs font-bold uppercase mr-2">${res.type}</span>
                        <a href="${res.url}" target="_blank" class="text-blue-500 hover:underline text-xs"><i class="fas fa-external-link-alt"></i></a>
                    </td>
                    <td class="p-4 align-middle text-center text-sm font-bold text-slate-600">${res.ordre}</td>
                    <td class="p-4 align-middle text-center">
                        <button onclick='openResourceForm("edit", ${JSON.stringify(res)})' class="text-blue-500 hover:text-blue-700 mx-1 border-none bg-transparent cursor-pointer"><i class="fas fa-pencil-alt"></i></button>
                        <button onclick="openDelResModal(${res.id})" class="text-red-500 hover:text-red-700 mx-1 border-none bg-transparent cursor-pointer"><i class="fas fa-trash"></i></button>
                    </td>
                `;
                tbody.appendChild(tr);
            });
        }

        document.getElementById('resourceManagerModal').style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    function closeResourceManager() {
        document.getElementById('resourceManagerModal').style.display = 'none';
        document.body.style.overflow = '';
        currentChallengeId = null;
    }

    function openResourceForm(mode, resData = null) {
        const form = document.getElementById('resourceForm');
        form.reset();
        
        // Reset Drop Zone Visuals
        dropZone.classList.remove('has-file');
        dropZone.querySelector('p').innerText = "Drag & Drop your PDF here";
        dropZone.querySelector('i').className = "fas fa-cloud-upload-alt";
        fileNameDisplay.style.display = 'none';
        
        const header = document.getElementById('resFormHeader');
        const submitBtn = document.getElementById('resFormSubmitBtn');

        if (mode === 'add') {
            document.getElementById('resFormTitle').innerText = "Add Resource";
            header.style.backgroundColor = '#5e72e4'; 
            submitBtn.style.backgroundColor = '#5e72e4'; 

            document.getElementById('action_add_res').disabled = false;
            document.getElementById('action_update_res').disabled = true;
            document.getElementById('res_id_defi').value = currentChallengeId; 
            
            // Trigger input check for default (PDF)
            toggleInput();
        } else {
            document.getElementById('resFormTitle').innerText = "Edit Resource";
            header.style.backgroundColor = '#fb6340'; 
            submitBtn.style.backgroundColor = '#fb6340'; 

            document.getElementById('action_add_res').disabled = true;
            document.getElementById('action_update_res').disabled = false;
            
            document.getElementById('res_edit_id').value = resData.id;
            document.getElementById('res_id_defi').value = resData.id_defi;
            document.getElementById('res_nom').value = resData.nom;
            document.getElementById('res_description').value = resData.description;
            document.getElementById('res_type').value = resData.type;
            
            // Populate URL input regardless of type (in case they want to switch)
            document.getElementById('res_url_input').value = resData.url;
            
            document.getElementById('res_ordre').value = resData.ordre;
            document.getElementById('res_necessite_preuve').value = resData.necessite_preuve;
            
            toggleInput();
            
            // In edit mode, file input is optional
            document.querySelector('.file-input').required = false;
        }

        document.getElementById('resourceFormModal').style.display = 'flex';
    }

    function closeResourceForm() {
        document.getElementById('resourceFormModal').style.display = 'none';
    }

    function openDelResModal(id) {
        document.getElementById('del_res_id').value = id;
        document.getElementById('deleteResourceModal').style.display = 'flex';
    }

    function closeDelResModal() {
        document.getElementById('deleteResourceModal').style.display = 'none';
    }

    const urlParams = new URLSearchParams(window.location.search);
    const openResId = urlParams.get('open_resources');
    if (openResId) {
        setTimeout(() => {
             history.replaceState(null, "", "challenge.php"); 
             openResourceManager(openResId, "Current Selection");
        }, 100);
    }
    
    window.onclick = function(event) {
        if (event.target == document.getElementById('addChallengeModal')) closeAddModal();
        if (event.target == document.getElementById('editChallengeModal')) closeEditModal();
        if (event.target == document.getElementById('deleteChallengeModal')) closeDeleteModal();
        if (event.target == document.getElementById('resourceManagerModal')) closeResourceManager();
        if (event.target == document.getElementById('resourceFormModal')) closeResourceForm();
        if (event.target == document.getElementById('deleteResourceModal')) closeDelResModal();
    }
    </script>
</body>
</html>
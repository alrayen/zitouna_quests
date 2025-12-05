<?php
include_once(__DIR__ . '/../../../../../Controller/userController.php');

$userController = new UserController();

if (isset($_POST['ban_user'])) {
    $userId = $_POST['user_id'];
    $userController->banUser($userId); 
    header("Location: users_table.php");
    exit();
}

if (isset($_POST['unban_user'])) {
    $userId = $_POST['user_id'];
    $userController->unbanUser($userId); 
    header("Location: users_table.php");
    exit();
}

if (isset($_POST['delete_user'])) {
    $userId = $_POST['user_id'];
    $userController->deleteUser($userId);
    header("Location: users_table.php");
    exit();
}

if (isset($_POST['reset_face_data'])) {
    $userId = $_POST['user_id'];
    $userController->resetFaceData($userId); 
    header("Location: users_table.php?status=face_reset");
    exit();
}

if (isset($_POST['update_user'])) {
    $userId = $_POST['edit_user_id'];
    $nom = $_POST['edit_nom'];
    $prenom = $_POST['edit_prenom'];
    $email = $_POST['edit_email'];
    $niveau = $_POST['edit_niveau'];
    $points = $_POST['edit_points'];
    $role = $_POST['edit_role']; 

    $result = $userController->adminUpdateUser($userId, $nom, $prenom, $email, $niveau, $points, $role);
    
    if ($result['success'] ?? true) { 
        header("Location: users_table.php?status=success");
        exit();
    } else {
        header("Location: users_table.php?status=error");
        exit();
    }
}

$users = $userController->getAllUsers();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Modern User Admin</title>

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
            max-width: 600px !important; 
            margin: auto;
        }

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
              <a class="py-2.7 bg-white shadow-md text-sm ease-nav-brand my-0 mx-2 flex items-center whitespace-nowrap rounded-lg px-4 font-semibold text-slate-700 transition-colors" href="../pages/users_table.php">
                <div class="mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-gradient-to-tl from-blue-500 to-violet-500 shadow-sm stroke-0 text-center xl:p-2.5 text-white">
                  <i class="ni ni-single-02"></i>
                </div>
                <span class="ml-1 duration-300 opacity-100 pointer-events-none ease">Users</span>
              </a>
            </li>
             <li class="w-full mt-2">
              <a class="py-2.7 text-sm ease-nav-brand my-0 mx-2 flex items-center whitespace-nowrap px-4 transition-colors hover:bg-gray-100 rounded-lg" href="../pages/quiz_table.php">
                <div class="mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-white shadow-sm stroke-0 text-center xl:p-2.5 text-orange-500">
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
              <li class="text-sm pl-2 capitalize leading-normal text-white before:float-left before:pr-2 before:text-white before:content-['/']" aria-current="page">Users</li>
            </ol>
            <h6 class="mb-0 font-bold text-white capitalize">User Dashboard</h6>
          </nav>
        </div>
      </nav>

      <div class="w-full px-6 py-6 mx-auto">
        <div class="flex flex-wrap -mx-3">
          <div class="flex-none w-full max-w-full px-3">
            <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border overflow-hidden">
              <div class="p-6 pb-0 mb-0 border-b-0 border-b-solid rounded-t-2xl border-b-transparent bg-gradient-to-r from-gray-50 to-white">
                <h6 class="dark:text-white font-bold text-lg text-slate-700"><i class="fas fa-users mr-2 text-blue-500"></i> Manage Users</h6>
              </div>
              <div class="flex-auto px-0 pt-0 pb-2">
                <div class="p-0 overflow-x-auto">
                  <table class="items-center w-full mb-0 align-top border-collapse dark:border-white/40 text-slate-500">
                    <thead class="align-bottom">
                      <tr class="bg-gray-50 text-left">
                        <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">User Details</th>
                        <th class="px-6 py-3 pl-2 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Level</th>
                        <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Points</th>
                        <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Role</th>
                        <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Actions</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach ($users as $user): ?>
                      <tr class="hover:bg-gray-50 transition-all duration-200 border-b border-gray-100">
                        <td class="p-4 align-middle bg-transparent whitespace-nowrap shadow-transparent">
                          <div class="flex px-2 py-1">
                            <div>
                                <?php 
                                    $photoPath = '../../../../../uploads/profiles/' . $user['photo'];
                                    $displayPhoto = (!empty($user['photo']) && file_exists($photoPath)) ? $photoPath : '../assets/img/team-2.jpg';
                                ?>
                              <img src="<?php echo $displayPhoto; ?>" class="inline-flex items-center justify-center mr-4 text-sm text-white transition-all duration-200 ease-in-out h-10 w-10 rounded-full shadow-md object-cover" alt="user photo" />
                            </div>
                            <div class="flex flex-col justify-center">
                              <h6 class="mb-0 text-sm font-bold leading-normal dark:text-white text-slate-700"><?php echo $user['nom'] . ' ' . $user['Prenom']; ?></h6>
                              <p class="mb-0 text-xs text-slate-400"><?php echo $user['email']; ?></p>
                            </div>
                          </div>
                        </td>
                        <td class="p-2 align-middle bg-transparent whitespace-nowrap shadow-transparent">
                           <span class="bg-gradient-to-tl from-emerald-500 to-teal-400 px-3 text-xs rounded-full py-1.5 inline-block whitespace-nowrap text-center align-baseline font-bold uppercase leading-none text-white shadow-sm"><?php echo $user['niveau']; ?></span>
                        </td>
                        <td class="p-2 text-sm leading-normal text-center align-middle bg-transparent whitespace-nowrap shadow-transparent">
                          <p class="mb-0 text-xs font-bold leading-tight text-slate-600"><?php echo $user['points']; ?> pts</p>
                        </td>
                        <td class="p-2 text-sm leading-normal text-center align-middle bg-transparent whitespace-nowrap shadow-transparent">
                           <span class="text-xs font-bold <?php echo ($user['role'] == 'admin' || $user['role'] == 1) ? 'text-blue-500' : 'text-slate-500'; ?>">
                                <?php echo ($user['role'] == 'admin' || $user['role'] == 1) ? 'ADMIN' : 'USER'; ?>
                           </span>
                        </td>
                        <td class="p-2 text-center align-middle bg-transparent whitespace-nowrap shadow-transparent">
                            <div class="flex justify-center items-center gap-2">
                                <button type="button" 
                                        onclick="openEditModalFromButton(this)"
                                        class="w-8 h-8 rounded-full bg-gray-100 hover:bg-blue-500 hover:text-white flex items-center justify-center transition-all shadow-sm cursor-pointer border-none"
                                        data-user='<?php echo htmlspecialchars(json_encode([
                                            'id_user' => $user['id_user'],
                                            'nom' => $user['nom'],
                                            'prenom' => $user['Prenom'], 
                                            'email' => $user['email'],
                                            'niveau' => $user['niveau'],
                                            'points' => $user['points'],
                                            'role' => $user['role']
                                        ]), ENT_QUOTES, 'UTF-8'); ?>'>
                                    <i class="fas fa-pencil-alt text-xs"></i>
                                </button>

                                <?php if ($user['etat'] == 0): ?>
                                    <button type="button" 
                                            onclick="openBanModal(<?php echo $user['id_user']; ?>)" 
                                            class="w-8 h-8 rounded-full bg-gray-100 hover:bg-red-500 hover:text-white flex items-center justify-center transition-all shadow-sm cursor-pointer border-none"
                                            title="Ban User">
                                        <i class="fas fa-ban text-xs"></i>
                                    </button>
                                <?php else: ?>
                                    <button type="button" 
                                            onclick="openUnbanModal(<?php echo $user['id_user']; ?>)" 
                                            class="w-8 h-8 rounded-full bg-gray-100 hover:bg-green-500 hover:text-white flex items-center justify-center transition-all shadow-sm cursor-pointer border-none"
                                            title="Unban User">
                                        <i class="fas fa-check-circle text-xs"></i>
                                    </button>
                                <?php endif; ?>

                                <button type="button" 
                                        onclick="openDeleteModal(<?php echo $user['id_user']; ?>)" 
                                        class="w-8 h-8 rounded-full bg-gray-100 hover:bg-red-700 hover:text-white flex items-center justify-center transition-all shadow-sm cursor-pointer border-none"
                                        title="Delete User">
                                    <i class="fas fa-trash text-xs"></i>
                                </button>

                                <?php if (!empty($user['face_descriptor'])): ?>
                                    <button type="button" 
                                            onclick="openResetFaceModal(<?php echo $user['id_user']; ?>)" 
                                            class="w-8 h-8 rounded-full bg-gray-100 hover:bg-yellow-500 hover:text-white flex items-center justify-center transition-all shadow-sm cursor-pointer border-none"
                                            title="Reset Facial Data">
                                        <i class="fas fa-user-slash text-xs"></i>
                                    </button>
                                <?php endif; ?>
                            </div>
                        </td>
                      </tr>
                      <?php endforeach; ?>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </main>
    
    <div id="editUserModal" class="custom-modal-overlay">
        <div class="custom-modal-box">
             <div style="background-color: #fb6340;" class="p-6 flex justify-between items-center">
                <h6 class="text-white font-bold text-lg m-0">Edit User</h6>
                <button type="button" onclick="closeEditModal()" class="text-white hover:text-gray-200 border-none bg-transparent cursor-pointer"><i class="fas fa-times text-lg"></i></button>
            </div>
            <form id="editUserForm" action="users_table.php" method="POST">
                <input type="hidden" name="update_user" value="1">
                <input type="hidden" name="edit_user_id" id="modal_user_id">
                
                <div class="p-6 space-y-4">
                    <div class="flex gap-4">
                        <div class="w-1/2">
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Nom</label>
                            <input type="text" id="modal_nom" name="edit_nom" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-orange-400 outline-none bg-gray-50" />
                            <p id="error_edit_nom" class="error-msg"></p>
                        </div>
                        <div class="w-1/2">
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Prénom</label>
                            <input type="text" id="modal_prenom" name="edit_prenom" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-orange-400 outline-none bg-gray-50" />
                            <p id="error_edit_prenom" class="error-msg"></p>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Email</label>
                        <input type="text" id="modal_email" name="edit_email" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-orange-400 outline-none bg-gray-50" />
                        <p id="error_edit_email" class="error-msg"></p>
                    </div>

                    <div class="flex gap-4">
                        <div class="w-1/2">
                             <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Role (0=User, 1=Admin)</label>
                             <input type="text" id="modal_role" name="edit_role" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-orange-400 outline-none bg-gray-50" />
                             <p id="error_edit_role" class="error-msg"></p>
                        </div>
                        <div class="w-1/2">
                             <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Niveau</label>
                             <input type="text" id="modal_niveau" name="edit_niveau" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-orange-400 outline-none bg-gray-50" />
                             <p id="error_edit_niveau" class="error-msg"></p>
                        </div>
                    </div>

                    <div>
                         <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Points</label>
                         <input type="text" id="modal_points" name="edit_points" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-orange-400 outline-none bg-gray-50" />
                         <p id="error_edit_points" class="error-msg"></p>
                    </div>
                </div>

                <div class="px-6 pb-6 flex justify-end gap-3">
                    <button type="button" onclick="closeEditModal()" class="px-6 py-3 text-slate-600 font-bold text-sm bg-gray-100 rounded-xl cursor-pointer border-none">Cancel</button>
                    <button type="submit" style="background-color: #fb6340; color: white;" class="px-6 py-3 font-bold text-sm rounded-xl cursor-pointer border-none hover:shadow-lg">Save Changes</button>
                </div>
            </form>
        </div>
    </div>

    <div id="banUserModal" class="custom-modal-overlay">
        <div class="custom-modal-box">
             <div style="background-color: #f5365c;" class="p-6 flex justify-between items-center">
                <h6 class="text-white font-bold text-lg m-0">Ban User</h6>
                <button type="button" onclick="closeBanModal()" class="text-white hover:text-gray-200 border-none bg-transparent cursor-pointer">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>
            
            <div class="p-6 text-center">
                <div class="mb-4">
                    <i class="fas fa-exclamation-triangle text-5xl text-red-500 opacity-50"></i>
                </div>
                <h4 class="font-bold text-slate-700 mb-2">Are you sure?</h4>
                <p id="banModalText" class="text-sm text-slate-500 mb-0">Do you really want to ban this user? This action can be reversed.</p>
            </div>

            <form id="banForm" action="users_table.php" method="POST">
                <input type="hidden" name="ban_user" value="1">
                <input type="hidden" name="user_id" id="modal_ban_user_id">
                <div class="px-6 pb-6 flex justify-center gap-3">
                    <button type="button" onclick="closeBanModal()" class="px-6 py-3 text-slate-600 font-bold text-sm bg-gray-100 rounded-xl cursor-pointer border-none hover:bg-gray-200 transition-colors">Cancel</button>
                    <button id="banSubmitButton" type="submit" style="background-color: #f5365c; color: white;" class="px-6 py-3 font-bold text-sm rounded-xl cursor-pointer border-none hover:shadow-lg transition-shadow">Yes, Ban User</button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Delete User Modal -->
    <div id="deleteUserModal" class="custom-modal-overlay">
        <div class="custom-modal-box">
             <div style="background-color: #f5365c;" class="p-6 flex justify-between items-center">
                <h6 class="text-white font-bold text-lg m-0">Delete User</h6>
                <button type="button" onclick="closeDeleteModal()" class="text-white hover:text-gray-200 border-none bg-transparent cursor-pointer">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>
            
            <div class="p-6 text-center">
                <div class="mb-4">
                    <i class="fas fa-exclamation-triangle text-5xl text-red-500 opacity-50"></i>
                </div>
                <h4 class="font-bold text-slate-700 mb-2">Are you sure?</h4>
                <p class="text-sm text-slate-500 mb-0">Do you really want to permanently delete this user? This action cannot be undone.</p>
            </div>

            <form id="deleteForm" action="users_table.php" method="POST">
                <input type="hidden" name="delete_user" value="1">
                <input type="hidden" name="user_id" id="modal_delete_user_id">
                <div class="px-6 pb-6 flex justify-center gap-3">
                    <button type="button" onclick="closeDeleteModal()" class="px-6 py-3 text-slate-600 font-bold text-sm bg-gray-100 rounded-xl cursor-pointer border-none hover:bg-gray-200 transition-colors">Cancel</button>
                    <button type="submit" style="background-color: #f5365c; color: white;" class="px-6 py-3 font-bold text-sm rounded-xl cursor-pointer border-none hover:shadow-lg transition-shadow">Yes, Delete User</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Reset Face Data Modal -->
    <div id="resetFaceModal" class="custom-modal-overlay">
        <div class="custom-modal-box">
             <div style="background-color: #fbbf24;" class="p-6 flex justify-between items-center">
                <h6 class="text-white font-bold text-lg m-0">Reset Facial Data</h6>
                <button type="button" onclick="closeResetFaceModal()" class="text-white hover:text-gray-200 border-none bg-transparent cursor-pointer">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>
            
            <div class="p-6 text-center">
                <div class="mb-4">
                    <i class="fas fa-exclamation-triangle text-5xl text-yellow-500 opacity-50"></i>
                </div>
                <h4 class="font-bold text-slate-700 mb-2">Are you sure?</h4>
                <p class="text-sm text-slate-500 mb-0">Do you really want to delete this user's facial recognition data? The user will need to re-enroll their face.</p>
            </div>

            <form id="resetFaceForm" action="users_table.php" method="POST">
                <input type="hidden" name="reset_face_data" value="1">
                <input type="hidden" name="user_id" id="modal_reset_face_user_id">
                <div class="px-6 pb-6 flex justify-center gap-3">
                    <button type="button" onclick="closeResetFaceModal()" class="px-6 py-3 text-slate-600 font-bold text-sm bg-gray-100 rounded-xl cursor-pointer border-none hover:bg-gray-200 transition-colors">Cancel</button>
                    <button type="submit" style="background-color: #fbbf24; color: white;" class="px-6 py-3 font-bold text-sm rounded-xl cursor-pointer border-none hover:shadow-lg transition-shadow">Yes, Reset Data</button>
                </div>
            </form>
        </div>
    </div>

    <script src="../assets/js/plugins/perfect-scrollbar.min.js" async></script>
    <script src="../assets/js/argon-dashboard-tailwind.js?v=1.0.1" async></script>
    
    <script>

        function closeEditModal() {
            document.getElementById('editUserModal').style.display = 'none';
            document.body.style.overflow = ''; 
        }

        function openEditModalFromButton(btn) {
            try {
                const userDataJson = btn.getAttribute('data-user');
                const user = JSON.parse(userDataJson);
                
                document.getElementById('modal_user_id').value = user.id_user;
                document.getElementById('modal_nom').value = user.nom;
                document.getElementById('modal_prenom').value = user.prenom; 
                document.getElementById('modal_email').value = user.email;
                document.getElementById('modal_niveau').value = user.niveau;
                document.getElementById('modal_points').value = user.points;
                let roleVal = 0;
                if(user.role === 'admin' || user.role == 1) roleVal = 1;
                document.getElementById('modal_role').value = roleVal;

                document.getElementById('editUserModal').style.display = 'flex';
                document.body.style.overflow = 'hidden';
            } catch (e) {
                console.error("Error parsing user data:", e);
                alert("Could not load user data.");
            }
        }

        function openBanModal(userId) {
            document.getElementById('modal_ban_user_id').value = userId;
            document.getElementById('banUserModal').style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }

        function openUnbanModal(userId) {
            const modal = document.getElementById('banUserModal');
            const form = document.getElementById('banForm');
            const submitButton = document.getElementById('banSubmitButton');
            
            modal.querySelector('h6').innerText = 'Unban User';
            document.getElementById('banModalText').innerText = 'Are you sure you want to unban this user?';
            
            form.querySelector('input[name="ban_user"]').name = 'unban_user';
            
            submitButton.innerText = 'Yes, Unban User';
            submitButton.style.backgroundColor = '#2dce89'; // Vert

            document.getElementById('modal_ban_user_id').value = userId;
            modal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }

        function closeBanModal() {
            document.getElementById('banUserModal').style.display = 'none';
            document.body.style.overflow = '';
        }

        function openDeleteModal(userId) {
            document.getElementById('modal_delete_user_id').value = userId;
            document.getElementById('deleteUserModal').style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }

        function closeDeleteModal() {
            document.getElementById('deleteUserModal').style.display = 'none';
            document.body.style.overflow = '';
        }

        function openResetFaceModal(userId) {
            document.getElementById('modal_reset_face_user_id').value = userId;
            document.getElementById('resetFaceModal').style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }

        function closeResetFaceModal() {
            document.getElementById('resetFaceModal').style.display = 'none';
            document.body.style.overflow = '';
        }
        document.getElementById('editUserForm').onsubmit = function() {
            let valid = true;
            const nom = document.getElementById('modal_nom').value.trim();
            const prenom = document.getElementById('modal_prenom').value.trim();
            const email = document.getElementById('modal_email').value.trim();
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

            if (!nom) {
                document.getElementById('error_edit_nom').innerText = "Name required";
                document.getElementById('error_edit_nom').style.display = "block";
                valid = false;
            } else { document.getElementById('error_edit_nom').style.display = "none"; }

            if (!prenom) {
                document.getElementById('error_edit_prenom').innerText = "Surname required";
                document.getElementById('error_edit_prenom').style.display = "block";
                valid = false;
            } else { document.getElementById('error_edit_prenom').style.display = "none"; }

            if (!email || !emailRegex.test(email)) {
                document.getElementById('error_edit_email').innerText = "Valid email required";
                document.getElementById('error_edit_email').style.display = "block";
                valid = false;
            } else { document.getElementById('error_edit_email').style.display = "none"; }

            return valid;
        };

        window.onclick = function(event) {
            const editModal = document.getElementById('editUserModal');
            const banModal = document.getElementById('banUserModal');
            const deleteModal = document.getElementById('deleteUserModal');
            const resetFaceModal = document.getElementById('resetFaceModal');
            
            if (event.target == editModal) closeEditModal();
            if (event.target == banModal) closeBanModal();
            if (event.target == deleteModal) closeDeleteModal();
            if (event.target == resetFaceModal) closeResetFaceModal();
        }
    </script>
</body>
</html>
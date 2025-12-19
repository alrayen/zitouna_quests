<?php
// Adjust these paths to match your folder structure
include_once(__DIR__ . '/../../../../../Controller/SponsorC.php');
include_once(__DIR__ . '/../../../../../Model/Sponsor.php'); 

$SponsorController = new SponsorController();

// --- 1. HANDLE ADD SPONSOR ---
if (isset($_POST['add_sponsor'])) {
    $nom = $_POST['add_nom'];
    $secteur = $_POST['add_secteur'];
    $contact = $_POST['add_contact'];
    $contribution = $_POST['add_contribution'];

    // Constructor order: nom, secteur, contact, contribution, (id is null)
    $newSponsor = new Sponsor($nom, $secteur, $contact, (float)$contribution);
    $SponsorController->addSponsor($newSponsor);
    
    header("Location: sponsor.php?status=added");
    exit();
}

// --- 2. HANDLE UPDATE SPONSOR ---
if (isset($_POST['update_sponsor'])) {
    $id = $_POST['edit_sponsor_id'];
    $nom = $_POST['edit_nom'];
    $secteur = $_POST['edit_secteur'];
    $contact = $_POST['edit_contact'];
    $contribution = $_POST['edit_contribution'];

    // Constructor order: nom, secteur, contact, contribution, id
    $updatedSponsor = new Sponsor($nom, $secteur, $contact, (float)$contribution, (int)$id);
    $SponsorController->updateSponsor($updatedSponsor);

    header("Location: sponsor.php?status=updated");
    exit();
}

// --- 3. HANDLE DELETE SPONSOR ---
if (isset($_POST['delete_sponsor'])) {
    $idSponsor = $_POST['id_sponsor'];
    $SponsorController->deleteSponsor($idSponsor);
    
    header("Location: sponsor.php?status=deleted");
    exit();
}

// Fetch Data
$sponsors = $SponsorController->listSponsors();
$totalSponsors = count($sponsors); 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Sponsor Admin</title>
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link href="../assets/css/nucleo-icons.css" rel="stylesheet" />
    <link href="../assets/css/nucleo-svg.css" rel="stylesheet" />
    <link href="../assets/css/argon-dashboard-tailwind.css?v=1.0.1" rel="stylesheet" />
    
    <style>
        .custom-modal-overlay {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background-color: rgba(0, 0, 0, 0.5); backdrop-filter: blur(5px);
            z-index: 9999 !important; display: none; 
            align-items: center; justify-content: center;
        }
        .custom-modal-box {
            background: white; border-radius: 1rem;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
            overflow: hidden; width: 90%; max-width: 500px !important; margin: auto;
        }
        .error-msg { display: none; color: red; font-size: 12px; font-weight: bold; }
        .error-msg.active { display: block; }    
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
              <a class="py-2.7 text-sm ease-nav-brand my-0 mx-2 flex items-center whitespace-nowrap px-4 transition-colors hover:bg-gray-100 rounded-lg" href="users_table.php">
                <div class="mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-white shadow-sm stroke-0 text-center xl:p-2.5 text-orange-500">
                  <i class="ni ni-single-02"></i>
                </div>
                <span class="ml-1 duration-300 opacity-100 pointer-events-none ease">Users</span>
              </a>
            </li>

            <li class="w-full mt-2">
              <a class="py-2.7 text-sm ease-nav-brand my-0 mx-2 flex items-center whitespace-nowrap px-4 transition-colors hover:bg-gray-100 rounded-lg" href="quiz_table.php">
                <div class="mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-white shadow-sm stroke-0 text-center xl:p-2.5 text-cyan-500">
                  <i class="ni ni-bullet-list-67"></i>
                </div>
                <span class="ml-1 duration-300 opacity-100 pointer-events-none ease">Quiz</span>
              </a>
            </li>

            <li class="w-full mt-2">
              <a class="py-2.7 text-sm ease-nav-brand my-0 mx-2 flex items-center whitespace-nowrap px-4 transition-colors hover:bg-gray-100 rounded-lg" href="challenge.php">
                <div class="mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-white shadow-sm stroke-0 text-center xl:p-2.5 text-blue-500">
                  <i class="ni ni-trophy"></i>
                </div>
                <span class="ml-1 duration-300 opacity-100 pointer-events-none ease">Challenges</span>
              </a>
            </li>

            <li class="w-full mt-2">
              <a class="py-2.7 text-sm ease-nav-brand my-0 mx-2 flex items-center whitespace-nowrap px-4 transition-colors hover:bg-gray-100 rounded-lg" href="posts.php">
                <div class="mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-white shadow-sm stroke-0 text-center xl:p-2.5 text-orange-500">
                  <i class="ni ni-calendar-grid-58"></i>
                </div>
                <span class="ml-1 duration-300 opacity-100 pointer-events-none ease">Forum</span>
              </a>
            </li>

            <li class="w-full mt-2">
              <a class="py-2.7 bg-white shadow-md text-sm ease-nav-brand my-0 mx-2 flex items-center whitespace-nowrap rounded-lg px-4 font-semibold text-slate-700 transition-colors" href="listSponsor.php">
                <div class="mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-gradient-to-tl from-blue-500 to-violet-500 shadow-sm stroke-0 text-center xl:p-2.5 text-white">
                  <i class="ni ni-badge"></i>
                </div>
                <span class="ml-1 duration-300 opacity-100 pointer-events-none ease">Sponsor</span>
              </a>
            </li>

            <li class="w-full mt-2">
              <a class="py-2.7 text-sm ease-nav-brand my-0 mx-2 flex items-center whitespace-nowrap px-4 transition-colors hover:bg-gray-100 rounded-lg" href="listDonation.php">
                <div class="mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-white shadow-sm stroke-0 text-center xl:p-2.5 text-emerald-500">
                  <i class="ni ni-favourite-28"></i>
                </div>
                <span class="ml-1 duration-300 opacity-100 pointer-events-none ease">Donation</span>
              </a>
            </li>

            <li class="w-full mt-2">
              <a class="py-2.7 text-sm ease-nav-brand my-0 mx-2 flex items-center whitespace-nowrap px-4 transition-colors hover:bg-gray-100 rounded-lg" href="dashboardAI.php">
                <div class="mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-white shadow-sm stroke-0 text-center xl:p-2.5 text-purple-500">
                  <i class="ni ni-bulb-61"></i>
                </div>
                <span class="ml-1 duration-300 opacity-100 pointer-events-none ease">DonationAI</span>
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
              <li class="text-sm leading-normal"><a class="text-white opacity-50" href="javascript:;">Pages</a></li>
              <li class="text-sm pl-2 capitalize leading-normal text-white before:float-left before:pr-2 before:text-white before:content-['/']">Sponsors</li>
            </ol>
            <h6 class="mb-0 font-bold text-white capitalize">Sponsor Dashboard</h6>
          </nav>
        </div>
      </nav>

      <div class="w-full px-6 py-6 mx-auto">
        <div class="flex flex-wrap -mx-3 mb-6">
             <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/4">
              <div class="relative flex flex-col min-w-0 break-words bg-white shadow-xl dark:bg-slate-850 rounded-2xl bg-clip-border">
                <div class="flex-auto p-4">
                  <div class="flex flex-row -mx-3">
                    <div class="flex-none w-2/3 max-w-full px-3">
                      <div>
                        <p class="mb-0 font-sans text-sm font-semibold leading-normal uppercase dark:text-white dark:opacity-60">Total Sponsors</p>
                        <h5 class="mb-2 font-bold dark:text-white"><?php echo $totalSponsors; ?></h5>
                      </div>
                    </div>
                    <div class="px-3 text-right basis-1/3">
                      <div class="inline-block w-12 h-12 text-center rounded-circle bg-gradient-to-tl from-emerald-500 to-teal-400">
                        <i class="fas fa-handshake text-lg relative top-3.5 text-white"></i>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            
            <div class="w-full max-w-full px-3 sm:w-1/2 sm:flex-none xl:w-1/4 ml-auto">
                 <button type="button" onclick="openAddModal()" class="w-full bg-white hover:scale-[1.02] transition-transform shadow-xl rounded-2xl p-4 flex items-center justify-center gap-3 cursor-pointer border-2 border-transparent hover:border-emerald-400">
                    <div class="w-12 h-12 rounded-full bg-gradient-to-tl from-blue-500 to-violet-500 flex items-center justify-center shadow-lg">
                        <i class="fas fa-plus text-white text-xl"></i>
                    </div>
                    <div class="text-left">
                        <h6 class="text-sm font-bold text-slate-700 mb-0">Add New Sponsor</h6>
                        <p class="text-xs text-slate-400 mb-0">Click to create</p>
                    </div>
                 </button>
            </div>
        </div>

        <div class="flex flex-wrap -mx-3">
          <div class="flex-none w-full max-w-full px-3">
            <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-xl dark:bg-slate-850 rounded-2xl bg-clip-border overflow-hidden">
              <div class="p-6 pb-0 mb-0 border-b-0 border-b-solid rounded-t-2xl border-b-transparent bg-gradient-to-r from-gray-50 to-white">
                <h6 class="dark:text-white font-bold text-lg text-slate-700"><i class="fas fa-list mr-2 text-emerald-500"></i> Manage Sponsors</h6>
              </div>
              <div class="flex-auto px-0 pt-0 pb-2">
                <div class="p-0 overflow-x-auto">
                  <table class="items-center w-full mb-0 align-top border-collapse text-slate-500">
                    <thead class="align-bottom">
                      <tr class="bg-gray-50 text-left">
                        <th class="px-6 py-3 font-bold uppercase align-middle text-xxs text-slate-400 opacity-70">Sponsor Name</th>
                        <th class="px-6 py-3 font-bold uppercase align-middle text-xxs text-slate-400 opacity-70">Sector</th>
                        <th class="px-6 py-3 font-bold uppercase align-middle text-xxs text-slate-400 opacity-70">Contact Info</th>
                        <th class="px-6 py-3 font-bold text-center uppercase align-middle text-xxs text-slate-400 opacity-70">Contribution</th>
                        <th class="px-6 py-3 font-bold text-center uppercase align-middle text-xxs text-slate-400 opacity-70">Actions</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php if (!empty($sponsors)): ?>
                          <?php foreach ($sponsors as $sponsor): ?>
                          <tr class="hover:bg-gray-50 transition-all duration-200 border-b border-gray-100">
                            <td class="p-4 align-middle whitespace-nowrap">
                              <div class="flex px-2 py-1">
                                <div>
                                  <div class="inline-flex items-center justify-center mr-4 text-sm text-white h-10 w-10 rounded-full shadow-md bg-slate-200">
                                    <span class="font-bold text-slate-600"><?php echo substr($sponsor->getNom(), 0, 1); ?></span>
                                  </div>
                                </div>
                                <div class="flex flex-col justify-center">
                                  <h6 class="mb-0 text-sm font-bold leading-normal text-slate-700"><?php echo $sponsor->getNom(); ?></h6>
                                  <p class="mb-0 text-xs text-slate-400">ID: #<?php echo $sponsor->getId(); ?></p>
                                </div>
                              </div>
                            </td>
                            <td class="p-2 align-middle whitespace-nowrap">
                                <p class="mb-0 text-xs font-semibold leading-tight"><?php echo $sponsor->getSecteur(); ?></p>
                            </td>
                            <td class="p-2 align-middle whitespace-nowrap">
                                <a href="mailto:<?php echo $sponsor->getContact(); ?>" class="text-xs font-bold text-blue-500 underline"><?php echo $sponsor->getContact(); ?></a>
                            </td>
                            <td class="p-2 text-center align-middle whitespace-nowrap">
                                <span class="bg-gradient-to-tl from-emerald-500 to-teal-400 px-3 text-xs rounded-full py-1.5 inline-block font-bold text-white shadow-sm">
                                    <?php echo number_format($sponsor->getContribution(), 2); ?> DT
                                </span>
                            </td>
                            <td class="p-2 text-center align-middle whitespace-nowrap">
                                <div class="flex justify-center items-center gap-2">
                                    <button type="button" 
                                            onclick="openEditModalFromButton(this)"
                                            class="w-8 h-8 rounded-full bg-gray-100 hover:bg-blue-500 hover:text-white flex items-center justify-center transition-all shadow-sm cursor-pointer border-none"
                                            data-sponsor='<?php echo htmlspecialchars(json_encode([
                                                'id' => $sponsor->getId(),
                                                'nom' => $sponsor->getNom(),
                                                'secteur' => $sponsor->getSecteur(),
                                                'contact' => $sponsor->getContact(),
                                                'contribution' => $sponsor->getContribution()
                                            ]), ENT_QUOTES, 'UTF-8'); ?>'>
                                        <i class="fas fa-pencil-alt text-xs"></i>
                                    </button>

                                    <button type="button" 
                                            onclick="openDeleteModal(<?php echo $sponsor->getId(); ?>)" 
                                            class="w-8 h-8 rounded-full bg-gray-100 hover:bg-red-500 hover:text-white flex items-center justify-center transition-all shadow-sm cursor-pointer border-none"
                                            title="Delete Sponsor">
                                        <i class="fas fa-trash text-xs"></i>
                                    </button>
                                </div>
                            </td>
                          </tr>
                          <?php endforeach; ?>
                      <?php else: ?>
                          <tr><td colspan="5" class="text-center p-8 text-gray-400">No sponsors found.</td></tr>
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
    
    <div id="addSponsorModal" class="custom-modal-overlay">
        <div class="custom-modal-box">
            <div style="background-color: #5e72e4;" class="p-6 flex justify-between items-center">
                <h6 class="text-white font-bold text-lg m-0">New Sponsor</h6>
                <button type="button" onclick="closeAddModal()" class="text-white hover:text-gray-200 border-none bg-transparent cursor-pointer"><i class="fas fa-times text-lg"></i></button>
            </div>
            <form id="addSponsorForm" action="sponsor.php" method="POST">
                <input type="hidden" name="add_sponsor" value="1">
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Name (Nom)</label>
                        <input type="text" name="add_nom" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-blue-500 outline-none bg-gray-50" placeholder="Company Name" />
                    </div>

                    <div class="flex gap-4">
                        <div class="w-1/2">
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Sector</label>
                            <select name="add_secteur" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-blue-500 outline-none bg-gray-50 cursor-pointer">
                                <option value="IT">IT / Tech</option>
                                <option value="Telecom">Telecommunications</option>
                                <option value="Finance">Finance</option>
                                <option value="Education">Education</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        <div class="w-1/2">
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Contribution (DT)</label>
                            <input type="number" step="0.01" name="add_contribution" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-blue-500 outline-none bg-gray-50" placeholder="0.00" />
                        </div>
                    </div>

                    <div>
                         <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Contact Info</label>
                         <input type="text" name="add_contact" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-blue-500 outline-none bg-gray-50" placeholder="Email or Phone" />
                    </div>
                </div>
                <div class="px-6 pb-6 flex justify-end gap-3">
                    <button type="button" onclick="closeAddModal()" class="px-6 py-3 text-slate-600 font-bold text-sm bg-gray-100 rounded-xl cursor-pointer border-none">Cancel</button>
                    <button type="submit" style="background-color: #5e72e4; color: white;" class="px-6 py-3 font-bold text-sm rounded-xl cursor-pointer border-none hover:shadow-lg">Add Sponsor</button>
                </div>
            </form>
        </div>
    </div>

    <div id="editSponsorModal" class="custom-modal-overlay">
        <div class="custom-modal-box">
             <div style="background-color: #fb6340;" class="p-6 flex justify-between items-center">
                <h6 class="text-white font-bold text-lg m-0">Edit Sponsor</h6>
                <button type="button" onclick="closeEditModal()" class="text-white hover:text-gray-200 border-none bg-transparent cursor-pointer"><i class="fas fa-times text-lg"></i></button>
            </div>
            <form id="editSponsorForm" action="sponsor.php" method="POST">
                <input type="hidden" name="update_sponsor" value="1">
                <input type="hidden" name="edit_sponsor_id" id="modal_sponsor_id">
                
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Name (Nom)</label>
                        <input type="text" id="edit_nom" name="edit_nom" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-orange-400 outline-none bg-gray-50" />
                    </div>
                    <div class="flex gap-4">
                        <div class="w-1/2">
                             <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Sector</label>
                             <select id="edit_secteur" name="edit_secteur" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-orange-400 outline-none bg-gray-50 cursor-pointer">
                                <option value="IT">IT / Tech</option>
                                <option value="Telecom">Telecommunications</option>
                                <option value="Finance">Finance</option>
                                <option value="Education">Education</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        <div class="w-1/2">
                             <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Contribution</label>
                             <input type="number" step="0.01" id="edit_contribution" name="edit_contribution" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-orange-400 outline-none bg-gray-50" />
                        </div>
                    </div>
                    <div>
                         <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Contact</label>
                         <input type="text" id="edit_contact" name="edit_contact" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-orange-400 outline-none bg-gray-50" />
                    </div>
                </div>
                <div class="px-6 pb-6 flex justify-end gap-3">
                    <button type="button" onclick="closeEditModal()" class="px-6 py-3 text-slate-600 font-bold text-sm bg-gray-100 rounded-xl cursor-pointer border-none">Cancel</button>
                    <button type="submit" style="background-color: #fb6340; color: white;" class="px-6 py-3 font-bold text-sm rounded-xl cursor-pointer border-none hover:shadow-lg">Save Changes</button>
                </div>
            </form>
        </div>
    </div>

    <div id="deleteSponsorModal" class="custom-modal-overlay">
        <div class="custom-modal-box">
            <div style="background-color: #f5365c;" class="p-6 flex justify-between items-center">
                <h6 class="text-white font-bold text-lg m-0">Delete Sponsor</h6>
                <button type="button" onclick="closeDeleteModal()" class="text-white hover:text-gray-200 border-none bg-transparent cursor-pointer">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>
            <div class="p-6 text-center">
                <div class="mb-4"><i class="fas fa-exclamation-triangle text-5xl text-red-500 opacity-50"></i></div>
                <h4 class="font-bold text-slate-700 mb-2">Are you sure?</h4>
                <p class="text-sm text-slate-500 mb-0">Do you really want to remove this sponsor? This cannot be undone.</p>
            </div>
            <form action="sponsor.php" method="POST">
                <input type="hidden" name="delete_sponsor" value="1">
                <input type="hidden" name="id_sponsor" id="delete_modal_sponsor_id">
                <div class="px-6 pb-6 flex justify-center gap-3">
                    <button type="button" onclick="closeDeleteModal()" class="px-6 py-3 text-slate-600 font-bold text-sm bg-gray-100 rounded-xl cursor-pointer border-none hover:bg-gray-200">Cancel</button>
                    <button type="submit" style="background-color: #f5365c; color: white;" class="px-6 py-3 font-bold text-sm rounded-xl cursor-pointer border-none hover:shadow-lg">Yes, Delete</button>
                </div>
            </form>
        </div>
    </div>

    <script src="../assets/js/plugins/perfect-scrollbar.min.js" async></script>
    <script src="../assets/js/argon-dashboard-tailwind.js?v=1.0.1" async></script>
    
    <script>
    function openAddModal() {
        document.getElementById('addSponsorModal').style.display = 'flex';
        document.body.style.overflow = 'hidden'; 
    }
    function closeAddModal() {
        document.getElementById('addSponsorModal').style.display = 'none';
        document.body.style.overflow = ''; 
    }

    function openDeleteModal(id) {
        document.getElementById('delete_modal_sponsor_id').value = id;
        document.getElementById('deleteSponsorModal').style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }
    function closeDeleteModal() {
        document.getElementById('deleteSponsorModal').style.display = 'none';
        document.body.style.overflow = '';
    }

    function openEditModalFromButton(button) {
        try {
            const json = button.getAttribute('data-sponsor');
            const data = JSON.parse(json);
            
            document.getElementById('modal_sponsor_id').value = data.id;
            document.getElementById('edit_nom').value = data.nom;       
            document.getElementById('edit_secteur').value = data.secteur; 
            document.getElementById('edit_contact').value = data.contact;        
            document.getElementById('edit_contribution').value = data.contribution;        

            document.getElementById('editSponsorModal').style.display = 'flex';
            document.body.style.overflow = 'hidden';
        } catch (e) {
            console.error("Error opening edit modal:", e);
            alert("Error loading data.");
        }
    }
    function closeEditModal() {
        document.getElementById('editSponsorModal').style.display = 'none';
        document.body.style.overflow = ''; 
    }

    window.onclick = function(event) {
        if (event.target == document.getElementById('addSponsorModal')) closeAddModal();
        if (event.target == document.getElementById('editSponsorModal')) closeEditModal();
        if (event.target == document.getElementById('deleteSponsorModal')) closeDeleteModal();
    }
    </script>
</body>
</html>
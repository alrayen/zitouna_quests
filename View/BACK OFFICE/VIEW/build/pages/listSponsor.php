<?php
// Using dirname(__DIR__, 5) to safely reach the project root
$root = dirname(__DIR__, 5);
require_once $root . '/Controller/SponsorC.php';
require_once $root . '/Model/Sponsor.php';
require_once $root . '/Controller/DonationC.php';

$sponsorC = new SponsorC();
$donationC = new DonationC();
$liste = $sponsorC->afficherSponsors();

$totalSponsors = 0;
if ($liste) {
    $liste = $liste->fetchAll();
    $totalSponsors = count($liste);
} else {
    $liste = [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Sponsors Management</title>
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
        .details-row { display: none; background-color: #f8fafc; }
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
      <nav class="relative flex flex-wrap items-center justify-between px-0 py-2 mx-6 transition-all ease-in shadow-none duration-250 rounded-2xl lg:flex-nowrap lg:justify-start">
        <div class="flex items-center justify-between w-full px-4 py-1 mx-auto flex-wrap-inherit">
          <nav>
            <ol class="flex flex-wrap pt-1 mr-12 bg-transparent rounded-lg sm:mr-16">
              <li class="text-sm leading-normal"><a class="text-white opacity-50" href="javascript:;">Pages</a></li>
              <li class="text-sm pl-2 capitalize leading-normal text-white before:float-left before:pr-2 before:text-white before:content-['/']">Sponsors</li>
            </ol>
            <h6 class="mb-0 font-bold text-white capitalize">Sponsors Management</h6>
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
                        <p class="mb-0 font-sans text-sm font-semibold leading-normal uppercase dark:text-white dark:opacity-60">Total Sponsors</p>
                        <h5 class="mb-2 font-bold dark:text-white"><?php echo $totalSponsors; ?></h5>
                      </div>
                    </div>
                    <div class="px-3 text-right basis-1/3">
                      <div class="inline-block w-12 h-12 text-center rounded-circle bg-gradient-to-tl from-blue-500 to-violet-500">
                        <i class="ni ni-badge text-lg relative top-3.5 text-white"></i>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            
            <div class="w-full max-w-full px-3 sm:w-1/2 sm:flex-none xl:w-1/4 ml-auto">
                 <a href="addSponsor.php" class="w-full bg-white hover:scale-[1.02] transition-transform shadow-xl rounded-2xl p-4 flex items-center justify-center gap-3 cursor-pointer border-2 border-transparent hover:border-blue-400 no-underline">
                    <div class="w-12 h-12 rounded-full bg-gradient-to-tl from-emerald-500 to-teal-400 flex items-center justify-center shadow-lg">
                        <i class="fas fa-plus text-white text-xl"></i>
                    </div>
                    <div class="text-left">
                        <h6 class="text-sm font-bold text-slate-700 mb-0">New Sponsor</h6>
                        <p class="text-xs text-slate-400 mb-0">Click to add</p>
                    </div>
                 </a>
            </div>
        </div>

        <div class="flex flex-wrap -mx-3">
          <div class="flex-none w-full max-w-full px-3">
            <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border overflow-hidden">
              <div class="p-6 pb-0 mb-0 border-b-0 border-b-solid rounded-t-2xl border-b-transparent bg-gradient-to-r from-gray-50 to-white">
                <h6 class="dark:text-white font-bold text-lg text-slate-700"><i class="fas fa-list mr-2 text-blue-500"></i> Manage Sponsors</h6>
              </div>
              <div class="flex-auto px-0 pt-0 pb-2">
                <div class="p-0 overflow-x-auto">
                  <table class="items-center w-full mb-0 align-top border-collapse dark:border-white/40 text-slate-500">
                    <thead class="align-bottom">
                      <tr class="bg-gray-50 text-left">
                        <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Sponsor</th>
                        <th class="px-6 py-3 pl-2 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Sector</th>
                        <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Donations</th>
                        <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Actions</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php if (!empty($liste)): ?>
                          <?php foreach ($liste as $row): 
                              $mesDons = $donationC->recupererDonsParSponsor($row['id']);
                          ?>
                          <tr class="hover:bg-gray-50 transition-all duration-200 border-b border-gray-100">
                            <td class="p-4 align-middle bg-transparent whitespace-nowrap shadow-transparent">
                              <div class="flex px-2 py-1">
                                <div>
                                  <div class="inline-flex items-center justify-center mr-4 text-sm text-white h-10 w-10 rounded-full shadow-md bg-gradient-to-tl from-blue-500 to-violet-500 ring-2 ring-white">
                                    <i class="fas fa-building text-xs"></i>
                                  </div>
                                </div>
                                <div class="flex flex-col justify-center">
                                  <h6 class="mb-0 text-sm font-bold leading-normal dark:text-white text-slate-700"><?php echo htmlspecialchars($row['nom']); ?></h6>
                                  <p class="mb-0 text-xs text-slate-400"><?php echo htmlspecialchars($row['contact']); ?></p>
                                </div>
                              </div>
                            </td>
                            
                            <td class="p-2 align-middle bg-transparent whitespace-nowrap shadow-transparent">
                                <span class="bg-blue-100 text-blue-600 px-2.5 py-0.5 rounded-full text-xs font-bold"><?php echo htmlspecialchars($row['secteur']); ?></span>
                            </td>
                            
                            <td class="p-2 text-center align-middle bg-transparent whitespace-nowrap shadow-transparent">
                                <button type="button" 
                                    onclick="toggleDetails(<?php echo $row['id']; ?>)"
                                    class="inline-block px-4 py-2 text-xs font-bold text-white uppercase align-middle transition-all bg-gradient-to-tl from-emerald-500 to-teal-400 rounded-lg shadow-md hover:shadow-lg hover:scale-105 active:opacity-85 leading-pro cursor-pointer border-none">
                                    <i class="fas fa-gift mr-1"></i> View (<?php echo count($mesDons); ?>)
                                </button>
                            </td>
                            
                            <td class="p-2 text-center align-middle bg-transparent whitespace-nowrap shadow-transparent">
                                <div class="flex justify-center items-center gap-2">
                                    <a href="addDonation.php?sponsor_id=<?php echo $row['id']; ?>&nom=<?php echo urlencode($row['nom']); ?>" class="w-8 h-8 rounded-full bg-emerald-100 text-emerald-600 hover:bg-emerald-500 hover:text-white flex items-center justify-center transition-all shadow-sm" title="Add Donation">
                                        <i class="fas fa-plus text-xs"></i>
                                    </a>
                                    <a href="updateSponsor.php?id=<?php echo $row['id']; ?>" class="w-8 h-8 rounded-full bg-gray-100 hover:bg-blue-500 hover:text-white flex items-center justify-center transition-all shadow-sm" title="Edit Sponsor">
                                        <i class="fas fa-pencil-alt text-xs"></i>
                                    </a>
                                    <button type="button" onclick="confirmDelete(<?php echo $row['id']; ?>)" class="w-8 h-8 rounded-full bg-gray-100 hover:bg-red-500 hover:text-white flex items-center justify-center transition-all shadow-sm cursor-pointer border-none" title="Delete Sponsor">
                                        <i class="fas fa-trash text-xs"></i>
                                    </button>
                                </div>
                            </td>
                          </tr>
                          
                          <tr id="details-<?php echo $row['id']; ?>" class="details-row">
                              <td colspan="4" class="p-4">
                                  <div class="bg-white rounded-xl shadow-inner p-4 border border-gray-100">
                                      <h6 class="text-sm font-bold text-slate-700 mb-4 ml-2"><i class="fas fa-history mr-2 text-blue-500"></i> Donation History for <?php echo htmlspecialchars($row['nom']); ?></h6>
                                      <?php if(count($mesDons) > 0): ?>
                                          <div class="overflow-x-auto">
                                              <table class="w-full text-left border-collapse">
                                                  <thead>
                                                      <tr class="text-xxs uppercase text-slate-400 font-bold border-b border-gray-100">
                                                          <th class="px-4 py-2">Date</th>
                                                          <th class="px-4 py-2">Type</th>
                                                          <th class="px-4 py-2">Amount</th>
                                                          <th class="px-4 py-2">Status</th>
                                                      </tr>
                                                  </thead>
                                                  <tbody>
                                                      <?php foreach($mesDons as $don): ?>
                                                      <tr class="text-xs border-b border-gray-50 last:border-0">
                                                          <td class="px-4 py-3"><?php echo $don['date_don']; ?></td>
                                                          <td class="px-4 py-3"><span class="font-semibold text-slate-600"><?php echo $don['type_don']; ?></span></td>
                                                          <td class="px-4 py-3 font-bold text-emerald-500"><?php echo $don['montant']; ?> DT</td>
                                                          <td class="px-4 py-3">
                                                              <?php if($don['etat'] == 'Validé'): ?>
                                                                  <span class="text-emerald-500 font-bold"><i class="fas fa-check-circle mr-1"></i> Validated (+<?php echo $don['points_gagnes']; ?> pts)</span>
                                                              <?php else: ?>
                                                                  <span class="text-orange-400 font-bold"><i class="fas fa-clock mr-1"></i> Pending</span>
                                                              <?php endif; ?>
                                                          </td>
                                                      </tr>
                                                      <?php endforeach; ?>
                                                  </tbody>
                                              </table>
                                          </div>
                                      <?php else: ?>
                                          <div class="text-center py-4 text-slate-400 text-xs italic">No donations found for this sponsor.</div>
                                      <?php endif; ?>
                                  </div>
                              </td>
                          </tr>
                          <?php endforeach; ?>
                      <?php else: ?>
                          <tr><td colspan="4" class="text-center p-8 text-gray-400">No sponsors found.</td></tr>
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

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function toggleDetails(id) {
            const row = document.getElementById("details-" + id);
            if (row.style.display === "none" || row.style.display === "") {
                row.style.display = "table-row";
            } else {
                row.style.display = "none";
            }
        }

        function confirmDelete(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "This will delete the sponsor and all their donation records!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#f5365c',
                cancelButtonColor: '#8392ab',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'deleteSponsor.php?id=' + id;
                }
            })
        }
    </script>
</body>
</html>

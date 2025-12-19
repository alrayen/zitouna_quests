<?php
// Using dirname(__DIR__, 5) to safely reach the project root
$root = dirname(__DIR__, 5);
require_once $root . '/Controller/DonationC.php';
require_once $root . '/Model/Donation.php';

if (!isset($_GET['sponsor_id'])) {
    header('Location: listSponsor.php');
    exit();
}

$sponsor_id = $_GET['sponsor_id'];
$sponsor_nom = $_GET['nom'];
$success = "";
$error = "";

if (isset($_POST["montant"]) && isset($_POST["type"])) {
    if (!empty($_POST["montant"])) {
        $date = date('Y-m-d');
        $don = new Donation(null, $sponsor_nom, $_POST['type'], $_POST['montant'], $date);
        
        $donationC = new DonationC();
        $donationC->createDonation($don, $sponsor_id);
        
        $success = "Donation of <strong>{$_POST['montant']} DT</strong> registered for <strong>$sponsor_nom</strong>!";
    } else {
        $error = "Please enter a valid amount.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Add Donation</title>
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link href="../assets/css/nucleo-icons.css" rel="stylesheet" />
    <link href="../assets/css/nucleo-svg.css" rel="stylesheet" />
    <link href="../assets/css/argon-dashboard-tailwind.css?v=1.0.1" rel="stylesheet" />
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
              <a class="py-2.7 text-sm ease-nav-brand my-0 mx-2 flex items-center whitespace-nowrap px-4 transition-colors hover:bg-gray-100 rounded-lg" href="listSponsor.php">
                <div class="mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-white shadow-sm stroke-0 text-center xl:p-2.5 text-violet-500">
                  <i class="ni ni-badge"></i>
                </div>
                <span class="ml-1 duration-300 opacity-100 pointer-events-none ease">Sponsor</span>
              </a>
            </li>
            <li class="w-full mt-2">
              <a class="py-2.7 bg-white shadow-md text-sm ease-nav-brand my-0 mx-2 flex items-center whitespace-nowrap rounded-lg px-4 font-semibold text-slate-700 transition-colors" href="listDonation.php">
                <div class="mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-gradient-to-tl from-blue-500 to-violet-500 shadow-sm stroke-0 text-center xl:p-2.5 text-white">
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
              <li class="text-sm pl-2 capitalize leading-normal text-white before:float-left before:pr-2 before:text-white before:content-['/']">Donations</li>
            </ol>
            <h6 class="mb-0 font-bold text-white capitalize">Add Donation</h6>
          </nav>
        </div>
      </nav>

      <div class="w-full px-6 py-6 mx-auto">
        <div class="flex flex-wrap -mx-3">
          <div class="w-full max-w-full px-3 mx-auto mt-0 md:flex-0 shrink-0 md:w-7/12">
            <div class="relative flex flex-col min-w-0 break-words bg-white border-0 shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
              <div class="p-6 pb-0 mb-0 border-b-0 border-b-solid rounded-t-2xl border-b-transparent bg-gradient-to-r from-emerald-50 to-white">
                <h6 class="dark:text-white font-bold text-lg text-slate-700">New Donation for <?php echo htmlspecialchars($sponsor_nom); ?></h6>
              </div>
              <div class="flex-auto p-6">
                <?php if(!empty($success)): ?>
                    <div class="p-4 mb-4 text-white bg-emerald-500 rounded-lg font-bold text-sm">
                        <i class="fas fa-check-circle mr-2"></i> <?php echo $success; ?>
                    </div>
                <?php endif; ?>
                <?php if(!empty($error)): ?>
                    <div class="p-4 mb-4 text-white bg-red-500 rounded-lg font-bold text-sm">
                        <i class="fas fa-exclamation-circle mr-2"></i> <?php echo $error; ?>
                    </div>
                <?php endif; ?>

                <form role="form" action="" method="POST" id="donationForm" novalidate>
                  <div class="mb-4">
                    <label class="block mb-2 ml-1 text-xs font-bold text-slate-700 uppercase">Sponsor (Donor)</label>
                    <input type="text" id="sponsor_name" value="<?php echo htmlspecialchars($sponsor_nom); ?>" class="focus:shadow-primary-outline dark:bg-slate-850 dark:text-white text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-gray-50 bg-clip-padding px-3 py-2 font-normal text-gray-500 outline-none transition-all" readonly />
                  </div>
                  <div class="mb-4">
                    <label class="block mb-2 ml-1 text-xs font-bold text-slate-700 uppercase">Donation Type</label>
                    <select name="type" id="type" class="focus:shadow-primary-outline dark:bg-slate-850 dark:text-white text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all focus:border-emerald-500 focus:outline-none">
                        <option value="">-- Select Type --</option>
                        <option value="Money">💵 Money</option>
                        <option value="Equipment">🛠️ Equipment</option>
                        <option value="Trees">🌳 Trees</option>
                        <option value="Service">🤝 Service</option>
                    </select>
                    <span id="errorType" class="text-red-500 text-xs font-bold mt-1 block"></span>
                  </div>
                  <div class="mb-4">
                    <label class="block mb-2 ml-1 text-xs font-bold text-slate-700 uppercase">Estimated Value (DT)</label>
                    <input type="number" name="montant" id="montant" placeholder="Ex: 500" class="focus:shadow-primary-outline dark:bg-slate-850 dark:text-white text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-emerald-500 focus:outline-none" />
                    <span id="errorMontant" class="text-red-500 text-xs font-bold mt-1 block"></span>
                  </div>
                  <div class="text-center">
                    <button type="submit" class="inline-block w-full px-16 py-3.5 mt-6 mb-0 font-bold text-center text-white uppercase align-middle transition-all border-0 rounded-lg cursor-pointer hover:scale-102 active:opacity-85 hover:shadow-lg leading-pro text-xs ease-in tracking-tight-rem shadow-md bg-gradient-to-tl from-emerald-500 to-teal-400">Register Donation</button>
                    <a href="listSponsor.php" class="inline-block w-full px-16 py-3.5 mt-3 mb-0 font-bold text-center text-slate-700 uppercase align-middle transition-all border-0 rounded-lg cursor-pointer hover:scale-102 active:opacity-85 leading-pro text-xs ease-in tracking-tight-rem bg-gray-100">Back to Sponsors</a>
                  </div>
                </form>

    <script>
        document.getElementById('donationForm').addEventListener('submit', function(e) {
            let isValid = true;
            const type = document.getElementById('type');
            const montant = document.getElementById('montant');

            const errorType = document.getElementById('errorType');
            const errorMontant = document.getElementById('errorMontant');

            // Reset errors
            [errorType, errorMontant].forEach(el => el.innerText = "");

            // Type validation
            if (type.value === "") {
                errorType.innerText = "Please select a donation type.";
                isValid = false;
            }

            // Amount validation
            if (montant.value.trim() === "") {
                errorMontant.innerText = "Estimated value is required.";
                isValid = false;
            } else if (isNaN(montant.value) || parseFloat(montant.value) <= 0) {
                errorMontant.innerText = "Value must be a positive number.";
                isValid = false;
            }

            if (!isValid) {
                e.preventDefault();
            }
        });
    </script>
              </div>
            </div>
          </div>
        </div>
      </div>
    </main>
</body>
</html>

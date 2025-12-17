<!--

=========================================================
* Argon Dashboard 2 Tailwind - v1.0.1
=========================================================

* Product Page: https://www.creative-tim.com/product/argon-dashboard-tailwind
* Copyright 2022 Creative Tim (https://www.creative-tim.com)

* Coded by www.creative-tim.com

=========================================================

* The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
-->
<?php 
include "../../../../../Controller/crudSujet.php";

$sujets=afficherSujet();

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png" />
    <link rel="icon" type="image/png" href="../assets/img/favicon.png" />
    <title>Argon Dashboard 2 Tailwind by Creative Tim</title>
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
    <link href="../assets/css/nucleo-icons.css" rel="stylesheet" />
    <link href="../assets/css/nucleo-svg.css" rel="stylesheet" />
    <link href="../assets/css/argon-dashboard-tailwind.css?v=1.0.1" rel="stylesheet" />
    <style>
      .error-message {
        color: #dc2626;
        font-size: 0.875rem;
        margin-top: 0.25rem;
        display: none;
      }
      .error-message.show {
        display: block;
      }
      .input-error {
        border-color: #dc2626 !important;
        background-color: #fef2f2 !important;
      }
      .success-message {
        background-color: #d1fae5;
        border: 1px solid #10b981;
        color: #065f46;
        padding: 1rem;
        border-radius: 0.5rem;
        margin-bottom: 1rem;
        display: none;
      }
      .success-message.show {
        display: block;
      }
    </style>
  </head>

  <body class="m-0 font-sans text-base antialiased font-normal dark:bg-slate-900 leading-default bg-gray-50 text-slate-500">
    <div class="absolute w-full bg-cyan-500 dark:hidden min-h-75"></div>

    <aside class="fixed inset-y-0 flex-wrap items-center justify-between block w-full p-0 my-4 overflow-y-auto antialiased transition-transform duration-200 -translate-x-full bg-white border-0 shadow-xl dark:shadow-none dark:bg-slate-850 xl:ml-6 max-w-64 ease-nav-brand z-990 rounded-2xl xl:left-0 xl:translate-x-0" aria-expanded="false">
      <div class="h-19">
        <i class="absolute top-0 right-0 p-4 opacity-50 cursor-pointer fas fa-times dark:text-white text-slate-400 xl:hidden" sidenav-close></i>
        <a class="block px-4 pt-2 pb-0 m-0 text-center" href="../pages/dashboard.html">
          <img src="../assets/img/logouna.png" class="inline h-full max-w-full transition-all duration-200 ease-nav-brand max-h-10" alt="main_logo" />
        </a>
      </div>

      <hr class="h-px mt-0 bg-transparent bg-gradient-to-r from-transparent via-black/40 to-transparent dark:bg-gradient-to-r dark:from-transparent dark:via-white dark:to-transparent" />

      <div class="items-center block w-auto max-h-screen overflow-auto h-sidenav grow basis-full" style="padding-top: 50px;">
        <ul class="flex flex-col pl-0 mb-0">
          <li class="w-full" style="margin-top: 10px;">
            <a class="py-2.7 bg-cyan-500/13 dark:text-white dark:opacity-80 text-sm ease-nav-brand my-0 mx-2 flex items-center whitespace-nowrap rounded-lg px-4 font-semibold text-slate-700 transition-colors" href="../pages/dashboard.html">
              <div class="mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-center stroke-0 text-center xl:p-2.5">
                <i class="relative top-0 text-sm leading-normal text-cyan-500 ni ni-tv-2"></i>
              </div>
              <span class="ml-1 duration-300 opacity-100 pointer-events-none ease">Dashboard</span>
            </a>
          </li>

          <li class="w-full" style="margin-top: 0px;">
            <a class=" dark:text-white dark:opacity-80 py-2.7 text-sm ease-nav-brand my-0 mx-2 flex items-center whitespace-nowrap px-4 transition-colors" href="../pages/posts.php">
              <div class="mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-center stroke-0 text-center xl:p-2.5">
                <i class="relative top-0 text-sm leading-normal text-orange-500 ni ni-calendar-grid-58"></i>
              </div>
              <span class="ml-1 duration-300 opacity-100 pointer-events-none ease">Posts</span>
            </a>
          </li>

          <li class="w-full" style="margin-top: 0px;">
            <a class=" dark:text-white dark:opacity-80 py-2.7 text-sm ease-nav-brand my-0 mx-2 flex items-center whitespace-nowrap px-4 transition-colors" href="../pages/commentaires.php">
              <div class="mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-center stroke-0 text-center xl:p-2.5">
                <i class="relative top-0 text-sm leading-normal text-orange-500 ni ni-calendar-grid-58"></i>
              </div>
              <span class="ml-1 duration-300 opacity-100 pointer-events-none ease">Commentaires</span>
            </a>
          </li>

          <li class="w-full" style="margin-top: 0px;">
            <h6 class="pl-6 ml-2 text-xs font-bold leading-tight uppercase dark:text-white opacity-60">Account pages</h6>
          </li>

          <li class="w-full" style="margin-top: 0px;">
            <a class=" dark:text-white dark:opacity-80 py-2.7 text-sm ease-nav-brand my-0 mx-2 flex items-center whitespace-nowrap px-4 transition-colors" href="../pages/profile.html">
              <div class="mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-center stroke-0 text-center xl:p-2.5">
                <i class="relative top-0 text-sm leading-normal text-slate-700 ni ni-single-02"></i>
              </div>
              <span class="ml-1 duration-300 opacity-100 pointer-events-none ease">Profile</span>
            </a>
          </li>
        </ul>
      </div>
    </aside>

    <main class="relative h-full max-h-screen transition-all duration-200 ease-in-out xl:ml-68 rounded-xl">
      <!-- Navbar -->
      <nav class="relative flex flex-wrap items-center justify-between px-0 py-2 mx-6 transition-all ease-in shadow-none duration-250 rounded-2xl lg:flex-nowrap lg:justify-start" navbar-main navbar-scroll="false">
        <div class="flex items-center justify-between w-full px-4 py-1 mx-auto flex-wrap-inherit">
          <nav>
            <ol class="flex flex-wrap pt-1 mr-12 bg-transparent rounded-lg sm:mr-16">
              <li class="text-sm leading-normal">
                <a class="text-white opacity-50" href="javascript:;">Pages</a>
              </li>
              <li class="text-sm pl-2 capitalize leading-normal text-white before:float-left before:pr-2 before:text-white before:content-['/']" aria-current="page">Tables</li>
            </ol>
            <h6 class="mb-0 font-bold text-white capitalize">Tables</h6>
          </nav>

          <div class="flex items-center mt-2 grow sm:mt-0 sm:mr-6 md:mr-0 lg:flex lg:basis-auto">
            <div class="flex items-center md:ml-auto md:pr-4">
              <div class="relative flex flex-wrap items-stretch w-full transition-all rounded-lg ease">
                <span class="text-sm ease leading-5.6 absolute z-50 -ml-px flex h-full items-center whitespace-nowrap rounded-lg rounded-tr-none rounded-br-none border border-r-0 border-transparent bg-transparent py-2 px-2.5 text-center font-normal text-slate-500 transition-all">
                  <i class="fas fa-search"></i>
                </span>
                <input type="text" class="pl-9 text-sm focus:shadow-primary-outline ease w-1/100 leading-5.6 relative -ml-px block min-w-0 flex-auto rounded-lg border border-solid border-gray-300 dark:bg-slate-850 dark:text-white bg-white bg-clip-padding py-2 pr-3 text-gray-700 transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none focus:transition-shadow" placeholder="Type here..." />
              </div>
            </div>
            <ul class="flex flex-row justify-end pl-0 mb-0 list-none md-max:w-full">
              <li class="flex items-center">
                <a href="javascript:;" class="block px-0 py-2 text-sm font-semibold text-white transition-all ease-nav-brand">
                  <i class="fa fa-user sm:mr-1"></i>
                  <span class="hidden sm:inline">hello admin</span>
                </a>
              </li>
              <li class="flex items-center pl-4 xl:hidden">
                <a href="javascript:;" class="block p-0 text-sm text-white transition-all ease-nav-brand" sidenav-trigger>
                  <div class="w-4.5 overflow-hidden">
                    <i class="ease mb-0.75 relative block h-0.5 rounded-sm bg-white transition-all"></i>
                    <i class="ease mb-0.75 relative block h-0.5 rounded-sm bg-white transition-all"></i>
                    <i class="ease relative block h-0.5 rounded-sm bg-white transition-all"></i>
                  </div>
                </a>
              </li>
              <li class="flex items-center px-4">
                <a href="javascript:;" class="p-0 text-sm text-white transition-all ease-nav-brand">
                  <i fixed-plugin-button-nav class="cursor-pointer fa fa-cog"></i>
                </a>
              </li>

              <li class="relative flex items-center pr-2">
                <p class="hidden transform-dropdown-show"></p>
                <a href="javascript:;" class="block p-0 text-sm text-white transition-all ease-nav-brand" dropdown-trigger aria-expanded="false">
                  <i class="cursor-pointer fa fa-bell"></i>
                </a>
              </li>
            </ul>
          </div>
        </div>
      </nav>

      <div class="w-full px-6 py-6 mx-auto">
        <!-- Message de succès -->
        <div id="successMessage" class="success-message">
          <i class="fas fa-check-circle mr-2"></i>
          <span>Le post a été ajouté avec succès!</span>
        </div>

        <!-- Formulaire d'ajout de post -->
        <div class="flex flex-wrap -mx-3 mb-6">
          <div class="flex-none w-full max-w-full px-3">
            <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
              <div class="p-6 pb-0 mb-0 border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
                <h6 class="dark:text-white">Ajouter un nouveau post</h6>
              </div>
              <div class="flex-auto px-6 py-4">
                <form id="postForm" action="../../../../../Controller/ajouterSujetController.php?position=back" method="POST" enctype="multipart/form-data" novalidate>
                  
                  <div class="flex flex-wrap -mx-3 mb-4">
                    <div class="w-full px-3">
                      <label class="block uppercase tracking-wide text-slate-600 text-xs font-bold mb-2 dark:text-white" for="contenu">
                        Contenu du post <span style="color: #dc2626;">*</span>
                      </label>
                      <textarea 
                        class="appearance-none block w-full bg-white text-slate-700 border border-slate-300 rounded-lg py-3 px-4 leading-tight focus:outline-none focus:border-blue-500 dark:bg-slate-800 dark:border-slate-600 dark:text-white" 
                        id="contenu" 
                        name="nom" 
                        rows="4" 
                        placeholder="Écrivez le contenu de votre post ici..."
                        minlength="10"
                        maxlength="1000"></textarea>
                      <div id="contenuError" class="error-message">
                        <i class="fas fa-exclamation-circle mr-1"></i>
                        <span id="contenuErrorText"></span>
                      </div>
                      <div class="text-xs text-slate-500 mt-1">
                        <span id="charCount">0</span> / 1000 caractères
                      </div>
                    </div>
                  </div>

                  <div class="flex flex-wrap -mx-3 mb-4">
                    <div class="w-full px-3">
                      <label class="block uppercase tracking-wide text-slate-600 text-xs font-bold mb-2 dark:text-white" for="imageInput">
                        Image (optionnelle)
                      </label>
                      <div class="relative">
                        <input 
                          type="file" 
                          name="image" 
                          id="imageInput"
                          accept="image/jpeg,image/jpg,image/png,image/gif,image/webp"
                          class="appearance-none block w-full bg-white text-slate-700 border border-slate-300 rounded-lg py-3 px-4 leading-tight focus:outline-none focus:border-blue-500 dark:bg-slate-800 dark:border-slate-600 dark:text-white cursor-pointer">
                      </div>
                      <div id="imageError" class="error-message">
                        <i class="fas fa-exclamation-circle mr-1"></i>
                        <span id="imageErrorText"></span>
                      </div>
                      <div class="text-xs text-slate-500 mt-1">
                        Formats acceptés: JPG, JPEG, PNG, GIF, WEBP (Max: 5MB)
                      </div>
                      <!-- Prévisualisation de l'image -->
                      <div id="imagePreview" class="mt-3" style="display: none;">
                        <img id="previewImg" src="" alt="Aperçu" class="max-w-xs rounded-lg border border-slate-300">
                        <button type="button" id="removeImage" class="mt-2 text-xs text-red-600 hover:text-red-800">
                          <i class="fas fa-times mr-1"></i> Supprimer l'image
                        </button>
                      </div>
                    </div>
                  </div>

                  <div class="flex justify-end items-center gap-3">
                    <button 
                      type="reset" 
                      id="resetBtn"
                      class="bg-slate-200 hover:bg-slate-300 px-6 py-2.5 text-sm rounded-lg font-bold uppercase leading-normal text-slate-700 shadow-md transition-all duration-150 ease-in">
                      Réinitialiser
                    </button>
                    <button 
                      type="submit" 
                      id="submitBtn"
                      class="bg-gradient-to-tl from-emerald-500 to-teal-400 px-6 py-2.5 text-sm rounded-lg font-bold uppercase leading-normal text-white shadow-md transition-all duration-150 ease-in hover:shadow-lg active:opacity-85">
                      <i class="fas fa-plus mr-2"></i>
                      Ajouter le post
                    </button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>

        <footer class="pt-4">
          <div class="w-full px-6 mx-auto">
            <div class="flex flex-wrap items-center -mx-3 lg:justify-between">
              <div class="w-full max-w-full px-3 mt-0 mb-6 shrink-0 lg:mb-0 lg:w-full lg:flex-none">
                <div class="text-sm leading-normal text-center text-slate-500">
                  created to be zitouna quests backoffice
                </div>
              </div>
            </div>
          </div>
        </footer>
      </div>
    </main>

    <script>
      // Récupération des éléments du formulaire
      const form = document.getElementById('postForm');
      const contenuInput = document.getElementById('contenu');
      const imageInput = document.getElementById('imageInput');
      const contenuError = document.getElementById('contenuError');
      const contenuErrorText = document.getElementById('contenuErrorText');
      const imageError = document.getElementById('imageError');
      const imageErrorText = document.getElementById('imageErrorText');
      const charCount = document.getElementById('charCount');
      const submitBtn = document.getElementById('submitBtn');
      const resetBtn = document.getElementById('resetBtn');
      const imagePreview = document.getElementById('imagePreview');
      const previewImg = document.getElementById('previewImg');
      const removeImageBtn = document.getElementById('removeImage');

      // Compteur de caractères
      contenuInput.addEventListener('input', function() {
        const length = this.value.length;
        charCount.textContent = length;
        
        if (length > 1000) {
          charCount.style.color = '#dc2626';
        } else if (length > 900) {
          charCount.style.color = '#f59e0b';
        } else {
          charCount.style.color = '#64748b';
        }

        // Validation en temps réel
        if (this.value.trim().length > 0 && contenuError.classList.contains('show')) {
          validateContenu();
        }
      });

      // Validation du contenu
      function validateContenu() {
        const value = contenuInput.value.trim();
        let isValid = true;
        
        contenuInput.classList.remove('input-error');
        contenuError.classList.remove('show');

        if (value === '') {
          contenuErrorText.textContent = 'Le contenu du post est obligatoire.';
          contenuInput.classList.add('input-error');
          contenuError.classList.add('show');
          isValid = false;
        } else if (value.length < 10) {
          contenuErrorText.textContent = 'Le contenu doit contenir au moins 10 caractères.';
          contenuInput.classList.add('input-error');
          contenuError.classList.add('show');
          isValid = false;
        } else if (value.length > 1000) {
          contenuErrorText.textContent = 'Le contenu ne peut pas dépasser 1000 caractères.';
          contenuInput.classList.add('input-error');
          contenuError.classList.add('show');
          isValid = false;
        } else if (!/[a-zA-Z0-9]/.test(value)) {
          contenuErrorText.textContent = 'Le contenu doit contenir au moins un caractère alphanumérique.';
          contenuInput.classList.add('input-error');
          contenuError.classList.add('show');
          isValid = false;
        }

        return isValid;
      }

      // Validation de l'image
      function validateImage() {
        const file = imageInput.files[0];
        let isValid = true;

        imageInput.classList.remove('input-error');
        imageError.classList.remove('show');
        
        if (file) {
          const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
          const maxSize = 5 * 1024 * 1024; // 5MB

          if (!allowedTypes.includes(file.type)) {
            imageErrorText.textContent = 'Format d\'image non valide. Formats acceptés: JPG, JPEG, PNG, GIF, WEBP.';
            imageInput.classList.add('input-error');
            imageError.classList.add('show');
            isValid = false;
          } else if (file.size > maxSize) {
            imageErrorText.textContent = 'La taille de l\'image ne doit pas dépasser 5MB.';
            imageInput.classList.add('input-error');
            imageError.classList.add('show');
            isValid = false;
          }
        }

        return isValid;
      }

      // Prévisualisation de l'image
      imageInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        
        if (file && validateImage()) {
          const reader = new FileReader();
          
          reader.onload = function(e) {
            previewImg.src = e.target.result;
            imagePreview.style.display = 'block';
          };
          
          reader.readAsDataURL(file);
        } else if (!file) {
          imagePreview.style.display = 'none';
        }
      });

      // Supprimer l'image
      removeImageBtn.addEventListener('click', function() {
        imageInput.value = '';
        imagePreview.style.display = 'none';
        imageInput.classList.remove('input-error');
        imageError.classList.remove('show');
      });

      // Validation du formulaire à la soumission
      form.addEventListener('submit', function(e) {
        e.preventDefault();

        const isContenuValid = validateContenu();
        const isImageValid = validateImage();

        if (isContenuValid && isImageValid) {
          // Désactiver le bouton pour éviter les doubles soumissions
          submitBtn.disabled = true;
          submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Envoi en cours...';
          
          // Soumettre le formulaire
          this.submit();
        } else {
          // Faire défiler vers la première erreur
          const firstError = document.querySelector('.input-error');
          if (firstError) {
            firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
            firstError.focus();
          }
        }
      });

      // Réinitialiser le formulaire
      resetBtn.addEventListener('click', function() {
        contenuInput.classList.remove('input-error');
        imageInput.classList.remove('input-error');
        contenuError.classList.remove('show');
        imageError.classList.remove('show');
        imagePreview.style.display = 'none';
        charCount.textContent = '0';
        charCount.style.color = '#64748b';
      });

      // Validation en temps réel pour éviter les caractères invalides
      contenuInput.addEventListener('keypress', function(e) {
        if (this.value.length >= 1000) {
          e.preventDefault();
        }
      });
    </script>
  </body>
</html>
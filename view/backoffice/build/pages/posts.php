<?php 
include "../../../../controller/crudSujet.php";
include "../../../../controller/crudCommentaire.php";

$sujets = afficherSujet();
$commentaires = afficherCommentaire();

// Organiser les commentaires par sujet_id pour un accès plus facile
$commentairesParSujet = [];
foreach($commentaires as $commentaire) {
    $sujetId = $commentaire["poste"];
    if (!isset($commentairesParSujet[$sujetId])) {
        $commentairesParSujet[$sujetId] = [];
    }
    $commentairesParSujet[$sujetId][] = $commentaire;
}

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
    <!--     Fonts and icons     -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
    <!-- Font Awesome Icons -->
    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
    <!-- Nucleo Icons -->
    <link href="../assets/css/nucleo-icons.css" rel="stylesheet" />
    <link href="../assets/css/nucleo-svg.css" rel="stylesheet" />
    <!-- Main Styling -->
    <link href="../assets/css/argon-dashboard-tailwind.css?v=1.0.1" rel="stylesheet" />
    <style>
/* Animations améliorées pour le popup */
@keyframes modalEnter {
  0% {
    opacity: 0;
    transform: scale(0.7) translateY(-20px);
  }
  100% {
    opacity: 1;
    transform: scale(1) translateY(0);
  }
}

@keyframes fadeIn {
  from {
    opacity: 0;
  }
  to {
    opacity: 1;
  }
}

.modal-open {
  animation: modalEnter 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards;
}

.modal-backdrop {
  animation: fadeIn 0.3s ease-out forwards;
}

/* Effet de profondeur amélioré */
.modal-container {
  box-shadow: 
    0 25px 50px -12px rgba(0, 0, 0, 0.25),
    0 0 0 1px rgba(255, 255, 255, 0.1),
    0 10px 30px -5px rgba(0, 0, 0, 0.3);
}

/* Animation de pulse pour l'icône d'avertissement */
@keyframes pulseWarning {
  0%, 100% {
    transform: scale(1);
    opacity: 1;
  }
  50% {
    transform: scale(1.1);
    opacity: 0.8;
  }
}

.fa-exclamation-triangle {
  animation: pulseWarning 2s infinite ease-in-out;
}

/* Animation de chargement */
@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

.fa-spinner {
  animation: spin 1s linear infinite;
}

/* Responsive design amélioré */
@media (max-width: 640px) {
  .modal-container {
    margin: 1rem;
    width: calc(100% - 2rem);
  }
}

/* Empêcher le défilement quand le modal est ouvert */
body.modal-open {
  overflow: hidden;
}

/* Transition pour les boutons */
.btn-transition {
  transition: all 0.2s ease-in-out;
}

.btn-transition:hover {
  transform: translateY(-1px);
}

.btn-transition:active {
  transform: translateY(0);
}

/* Styles pour les sections de commentaires */
.comment-section {
  max-height: 0;
  overflow: hidden;
  transition: max-height 0.3s ease-out;
}

.comment-section.expanded {
  max-height: 1000px;
}

.comment-item {
  border-left: 3px solid #e2e8f0;
  padding-left: 1rem;
  margin-bottom: 1rem;
  background: #f8fafc;
  border-radius: 0.5rem;
  padding: 1rem;
}

.comment-item:last-child {
  margin-bottom: 0;
}

.toggle-comments {
  transition: all 0.3s ease;
}

.toggle-comments:hover {
  background-color: #f7fafc;
}

/* Badge pour le nombre de commentaires */
.comment-badge {
  background-color: #4299e1;
  color: white;
  border-radius: 9999px;
  padding: 0.25rem 0.5rem;
  font-size: 0.75rem;
  margin-left: 0.5rem;
}

/* Styles pour le mode sombre */
.dark .comment-item {
  background: #2d3748;
  border-left-color: #4a5568;
}

.dark .toggle-comments:hover {
  background-color: #2d3748;
}

/* Styles pour les boutons de commentaire */
.comment-buttons {
  display: flex;
  gap: 0.5rem;
  margin-top: 0.5rem;
}

.comment-btn {
  padding: 0.25rem 0.5rem;
  font-size: 0.75rem;
  border-radius: 0.375rem;
  transition: all 0.2s ease;
}

.comment-btn:hover {
  transform: translateY(-1px);
}

/* Styles pour les images des posts */
.post-image-container {
  width: 100%;
  margin: 1rem 0;
  border-radius: 0.5rem;
  overflow: hidden;
  background: #f1f5f9;
  display: flex;
  justify-content: center;
  align-items: center;
  min-height: 200px;
  max-height: 400px;
}

.post-image {
  max-width: 100%;
  max-height: 400px;
  object-fit: contain;
  border-radius: 0.5rem;
  transition: transform 0.3s ease;
  cursor: pointer;
}

.post-image:hover {
  transform: scale(1.02);
}

.image-modal {
  display: none;
  position: fixed;
  z-index: 9999;
  padding-top: 100px;
  left: 0;
  top: 0;
  width: 100%;
  height: 100%;
  overflow: auto;
  background-color: rgba(0, 0, 0, 0.9);
  animation: fadeIn 0.3s;
}

.image-modal-content {
  margin: auto;
  display: block;
  max-width: 90%;
  max-height: 80vh;
}

.close-modal {
  position: absolute;
  top: 15px;
  right: 35px;
  color: #f1f1f1;
  font-size: 40px;
  font-weight: bold;
  transition: 0.3s;
  cursor: pointer;
}

.close-modal:hover {
  color: #bbb;
}

.no-image {
  padding: 3rem;
  text-align: center;
  color: #94a3b8;
  background: #f8fafc;
  border-radius: 0.5rem;
  border: 2px dashed #e2e8f0;
}

.no-image i {
  font-size: 2.5rem;
  margin-bottom: 1rem;
  color: #cbd5e1;
}

/* Styles pour l'affichage des posts */
.post-content-container {
  padding: 1rem;
  background: #f8fafc;
  border-radius: 0.5rem;
  margin: 1rem 0;
  border-left: 4px solid #3b82f6;
}

.post-content-text {
  white-space: pre-wrap;
  word-break: break-word;
  line-height: 1.6;
  color: #334155;
}

.dark .post-content-container {
  background: #1e293b;
  border-left-color: #60a5fa;
}

.dark .post-content-text {
  color: #cbd5e1;
}

/* Animation pour le contenu du post */
@keyframes slideIn {
  from {
    opacity: 0;
    transform: translateY(10px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.post-row {
  animation: slideIn 0.3s ease-out;
}

/* Styles pour la table avec images */
.table-image-cell {
  max-width: 300px;
}

.image-preview-cell {
  padding: 1rem !important;
}

/* Responsive pour les images */
@media (max-width: 768px) {
  .post-image-container {
    min-height: 150px;
    max-height: 300px;
  }
  
  .post-image {
    max-height: 300px;
  }
}
    </style>
  </head>

  <body class="m-0 font-sans text-base antialiased font-normal dark:bg-slate-900 leading-default bg-gray-50 text-slate-500">
    <div class="absolute w-full bg-cyan-500 dark:hidden min-h-75"></div>

<!-- Popup de confirmation AMÉLIORÉ -->
<div id="confirmationModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-gray-900/60 backdrop-blur-sm transition-all duration-300 modal-backdrop">
  <div class="relative w-full max-w-md mx-4">
    <div class="bg-white dark:bg-slate-850 rounded-2xl shadow-2xl transform transition-all duration-300 scale-95 opacity-0 modal-container modal-open">
      
      <!-- En-tête du popup -->
      <div class="flex items-center justify-between p-6 border-b border-slate-200 dark:border-slate-700">
        <div class="flex items-center">
          <div class="flex items-center justify-center w-12 h-12 bg-red-100 dark:bg-red-900/20 rounded-xl">
            <i class="text-xl text-red-600 dark:text-red-400 fas fa-exclamation-triangle"></i>
          </div>
          <div class="ml-4">
            <h3 class="text-lg font-bold text-slate-900 dark:text-white">Confirmer la suppression</h3>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Action irréversible</p>
          </div>
        </div>
        <button type="button" onclick="closeModal()" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 transition-colors duration-200">
          <i class="fas fa-times text-lg"></i>
        </button>
      </div>

      <!-- Contenu du popup -->
      <div class="p-6">
        <div class="flex items-start">
          <div class="flex-shrink-0">
            <div class="flex items-center justify-center w-10 h-10 bg-red-50 dark:bg-red-900/20 rounded-lg">
              <i class="text-red-500 dark:text-red-400 fas fa-trash-alt"></i>
            </div>
          </div>
          <div class="ml-4">
            <p class="text-slate-700 dark:text-slate-300 font-medium" id="modalMessage">
              Êtes-vous sûr de vouloir supprimer cet élément ?
            </p>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-2">
              Cette action ne peut pas être annulée. Toutes les données associées seront définitivement supprimées.
            </p>
          </div>
        </div>
      </div>

      <!-- Actions du popup -->
      <div class="flex justify-end space-x-3 p-6 bg-slate-50 dark:bg-slate-800/50 rounded-b-2xl">
        <button type="button" id="cancelDelete" class="px-6 py-2.5 text-sm font-medium text-slate-700 dark:text-slate-300 bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-600 transition-all duration-200 btn-transition">
          Annuler
        </button>
        <button type="button" id="confirmDelete" class="px-6 py-2.5 text-sm font-medium text-white bg-gradient-to-r from-red-500 to-red-600 rounded-lg hover:from-red-600 hover:to-red-700 transition-all duration-200 btn-transition shadow-lg shadow-red-500/25">
          <i class="fas fa-trash-alt mr-2"></i>Supprimer
        </button>
      </div>
    </div>
  </div>
</div>

<!-- Modal pour afficher l'image en grand -->
<div id="imageModal" class="image-modal">
  <span class="close-modal">&times;</span>
  <img class="image-modal-content" id="fullImage">
</div>

<script>
let elementIdToDelete = null;
let isDeleting = false;
let currentType = null; // 'post' ou 'comment'

function confirmDelete(elementId, type) {
  if (isDeleting) return;
  
  elementIdToDelete = elementId;
  currentType = type;
  const modal = document.getElementById('confirmationModal');
  const modalMessage = document.getElementById('modalMessage');
  document.body.classList.add('modal-open');
  modal.classList.remove('hidden');
  
  // Mettre à jour le texte selon le type
  if (type === 'post') {
    modalMessage.textContent = 'Êtes-vous sûr de vouloir supprimer ce post ?';
  } else if (type === 'comment') {
    modalMessage.textContent = 'Êtes-vous sûr de vouloir supprimer ce commentaire ?';
  }
  
  // Focus sur le bouton d'annulation pour l'accessibilité
  setTimeout(() => {
    document.getElementById('cancelDelete').focus();
  }, 100);
}

function closeModal() {
  if (isDeleting) return;
  
  const modal = document.getElementById('confirmationModal');
  modal.classList.add('hidden');
  document.body.classList.remove('modal-open');
  elementIdToDelete = null;
  currentType = null;
  isDeleting = false;
}

function deleteElement() {
  if (isDeleting || !elementIdToDelete || !currentType) return;
  
  isDeleting = true;
  
  // Animation de chargement
  const deleteBtn = document.getElementById('confirmDelete');
  const originalContent = deleteBtn.innerHTML;
  deleteBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Suppression...';
  deleteBtn.disabled = true;
  
  // Désactiver le bouton d'annulation pendant la suppression
  document.getElementById('cancelDelete').disabled = true;
  
  // Déterminer l'URL de suppression selon le type
  let deleteUrl = '';
  if (currentType === 'post') {
    deleteUrl = `../../../../controller/supprimerSujetController.php?id=${elementIdToDelete}&position=back`;
  } else if (currentType === 'comment') {
    deleteUrl = `../../../../controller/supprimerCommentaireController.php?id=${elementIdToDelete}&position=back`;
  }
  
  // Rediriger vers l'URL de suppression
  window.location.href = deleteUrl;
}

// Événements pour les boutons du modal
document.getElementById('confirmDelete').addEventListener('click', deleteElement);
document.getElementById('cancelDelete').addEventListener('click', closeModal);

// Fermer le modal en cliquant en dehors
document.getElementById('confirmationModal').addEventListener('click', function(e) {
  if (e.target === this && !isDeleting) {
    closeModal();
  }
});

// Fermer le modal avec la touche Échap
document.addEventListener('keydown', function(e) {
  if (e.key === 'Escape' && !isDeleting) {
    closeModal();
  }
});

// Gestion du focus pour l'accessibilité
document.addEventListener('keydown', function(e) {
  const modal = document.getElementById('confirmationModal');
  if (!modal.classList.contains('hidden')) {
    const focusableElements = modal.querySelectorAll('button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])');
    const firstElement = focusableElements[0];
    const lastElement = focusableElements[focusableElements.length - 1];
    
    if (e.key === 'Tab') {
      if (e.shiftKey) {
        if (document.activeElement === firstElement) {
          lastElement.focus();
          e.preventDefault();
        }
      } else {
        if (document.activeElement === lastElement) {
          firstElement.focus();
          e.preventDefault();
        }
      }
    }
  }
});

// Animation d'entrée du modal
document.addEventListener('DOMContentLoaded', function() {
  const modal = document.getElementById('confirmationModal');
  const modalContent = modal.querySelector('.modal-container');
  
  // Observer les changements de classe pour déclencher l'animation
  const observer = new MutationObserver(function(mutations) {
    mutations.forEach(function(mutation) {
      if (mutation.attributeName === 'class') {
        if (!modal.classList.contains('hidden')) {
          setTimeout(() => {
            modalContent.classList.add('modal-open');
          }, 10);
        } else {
          modalContent.classList.remove('modal-open');
        }
      }
    });
  });
  
  observer.observe(modal, { attributes: true });
});

// Gestion de l'affichage des commentaires
function toggleComments(sujetId) {
  const commentSection = document.getElementById(`comments-${sujetId}`);
  const toggleButton = document.querySelector(`[onclick="toggleComments(${sujetId})"]`);
  
  if (commentSection.classList.contains('expanded')) {
    commentSection.classList.remove('expanded');
    toggleButton.innerHTML = '<i class="fas fa-chevron-down mr-2"></i>Voir les commentaires';
  } else {
    commentSection.classList.add('expanded');
    toggleButton.innerHTML = '<i class="fas fa-chevron-up mr-2"></i>Masquer les commentaires';
  }
}

// Gestion du modal d'image
function openImageModal(imageSrc) {
  const modal = document.getElementById('imageModal');
  const modalImg = document.getElementById('fullImage');
  modal.style.display = "block";
  modalImg.src = imageSrc;
  document.body.classList.add('modal-open');
}

// Fermer le modal d'image
document.querySelector('.close-modal').addEventListener('click', function() {
  const modal = document.getElementById('imageModal');
  modal.style.display = "none";
  document.body.classList.remove('modal-open');
});

// Fermer le modal d'image en cliquant en dehors
document.getElementById('imageModal').addEventListener('click', function(e) {
  if (e.target === this) {
    this.style.display = "none";
    document.body.classList.remove('modal-open');
  }
});

// Fermer le modal d'image avec la touche Échap
document.addEventListener('keydown', function(e) {
  const imageModal = document.getElementById('imageModal');
  if (e.key === 'Escape' && imageModal.style.display === 'block') {
    imageModal.style.display = "none";
    document.body.classList.remove('modal-open');
  }
});
</script>

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
            <!-- breadcrumb -->
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

              <!-- notifications -->
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
        <!-- table 1 -->
        <div class="flex flex-wrap -mx-3">
          <div class="flex-none w-full max-w-full px-3">
            <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
              <div class="p-6 pb-0 mb-0 border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
                <h6 class="dark:text-white">Posts</h6>
              </div>
               <button onclick="window.location.href='nouveauPost.php'" class="bg-gradient-to-tl from-emerald-500 to-teal-400 px-2.5 text-xs rounded-1.8 py-1.4 inline-block whitespace-nowrap text-center align-baseline font-bold uppercase leading-none text-white">Nouveau post</button>
              <div class="flex-auto px-0 pt-0 pb-2">
                <div class="p-0 overflow-x-auto">
                  <table class="items-center w-full mb-0 align-top border-collapse dark:border-white/40 text-slate-500">
                    <thead class="align-bottom">
                      <tr>
                        <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Auteur</th>
                        <th class="px-6 py-3 pl-2 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Contenu</th>
                        <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Image</th>
                        <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Date</th>
                        <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Commenter</th>
                        <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Modifier</th>
                        <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Supprimer</th>
                      </tr>
                    </thead>
                    <tbody>
                        <?php
                foreach($sujets as $sujet):
                  $sujetId = $sujet["id"];
                  $commentairesDuSujet = isset($commentairesParSujet[$sujetId]) ? $commentairesParSujet[$sujetId] : [];
                  $nombreCommentaires = count($commentairesDuSujet);
                  
                  // Récupérer l'image du post (ajouté dans la colonne 'image' de la table)
                  $imagePost = isset($sujet['imagee']) ? $sujet['imagee'] : null;
                ?>
                      <tr class="post-row">
                        <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                          <div class="flex px-2 py-1">
                            <div>
                              <img src="../assets/img/team-2.jpg" class="inline-flex items-center justify-center mr-4 text-sm text-white transition-all duration-200 ease-in-out h-9 w-9 rounded-xl" alt="user1" />
                            </div>
                            <div class="flex flex-col justify-center">
                              <h6 class="mb-0 text-sm leading-normal dark:text-white">John Michael</h6>
                              <p class="mb-0 text-xs leading-tight dark:text-white dark:opacity-80 text-slate-400">john@creative-tim.com</p>
                            </div>
                          </div>
                        </td>
                        <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                          <div class="post-content-container">
                            <p class="post-content-text"><?=htmlspecialchars($sujet["nom"])?></p>
                          </div>
                        </td>
                        <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent image-preview-cell">
                          <?php if ($imagePost): ?>
                            <div class="post-image-container">
                              <img src="../../../../controller/images/<?=htmlspecialchars($imagePost)?>" 
                                   alt="Image du post" 
                                   class="post-image"
                                   onclick="openImageModal('../../../../controller/images/<?=htmlspecialchars($imagePost)?>')"
                          >
                            </div>
                          <?php else: ?>
                            <div class="no-image">
                              <i class="fas fa-image"></i>
                              <p>Pas d'image</p>
                            </div>
                          <?php endif; ?>
                        </td>
                        <td class="p-2 text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                          <span class="text-xs font-semibold leading-tight dark:text-white dark:opacity-80 text-slate-400"><?=$sujet["date_sujets"]?></span>
                        </td>
                         <td class="p-2 text-sm leading-normal text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                          <button onclick="window.location.href='ajoutCommentaire.php?id=<?=$sujet['id']?>'" class="bg-gradient-to-tl from-emerald-500 to-teal-400 px-2.5 text-xs rounded-1.8 py-1.4 inline-block whitespace-nowrap text-center align-baseline font-bold uppercase leading-none text-white">Commenter</button>
                        </td>
                        <td class="p-2 text-sm leading-normal text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                          <button onclick="window.location.href='modifierPost.php?id=<?=$sujet['id']?>&contenu=<?=urlencode($sujet['nom'])?>&position=back'" class="bg-gradient-to-tl from-emerald-500 to-teal-400 px-2.5 text-xs rounded-1.8 py-1.4 inline-block whitespace-nowrap text-center align-baseline font-bold uppercase leading-none text-white">Modifier</button>
                        </td>
                        <td class="p-2 text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                          <button onclick="confirmDelete(<?=$sujet['id']?>, 'post')" class="bg-gradient-to-tl from-red-600 to-orange-600 px-4 py-2 text-xs rounded-1.8 inline-block whitespace-nowrap text-center align-baseline font-bold uppercase leading-none text-white">Supprimer</button>
                        </td>
                      </tr>
                      <!-- Section des commentaires pour ce post -->
                      <tr>
                        <td colspan="7" class="p-0 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                          <div class="px-6 py-4 bg-gray-50 dark:bg-slate-800/50">
                            <button onclick="toggleComments(<?=$sujetId?>)" class="toggle-comments flex items-center text-sm font-medium text-slate-600 dark:text-slate-300 bg-white dark:bg-slate-700 px-4 py-2 rounded-lg border border-slate-200 dark:border-slate-600 w-full text-left">
                              <i class="fas fa-chevron-down mr-2"></i>Voir les commentaires
                              <?php if ($nombreCommentaires > 0): ?>
                                <span class="comment-badge"><?=$nombreCommentaires?></span>
                              <?php endif; ?>
                            </button>
                            
                            <div id="comments-<?=$sujetId?>" class="comment-section mt-3">
                              <?php if ($nombreCommentaires > 0): ?>
                                <div class="space-y-3">
                                  <h6 class="text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Commentaires (<?=$nombreCommentaires?>)</h6>
                                  <?php foreach($commentairesDuSujet as $commentaire): ?>
                                    <div class="comment-item">
                                      <div class="flex justify-between items-start">
                                        <div class="flex-1">
                                          <div class="flex items-center mb-2">
                                            <img src="../assets/img/team-2.jpg" class="w-6 h-6 rounded-full mr-2" alt="user" />
                                            <span class="text-xs font-medium text-slate-700 dark:text-slate-300">Utilisateur</span>
                                            <span class="text-xs text-slate-500 dark:text-slate-400 ml-2"><?=$commentaire["date_commentaires"]?></span>
                                          </div>
                                          <p class="text-sm text-slate-600 dark:text-slate-300"><?=$commentaire["contenu"]?></p>
                                        </div>
                                        
                                        <!-- Boutons Modifier et Supprimer pour chaque commentaire -->
                                        <div class="flex space-x-2 ml-4">
                                          <!-- Bouton Modifier -->
                                          <a href="modifierCommentaire.php?id=<?=$commentaire['id']?>&contenu=<?=urlencode($commentaire['contenu'])?>&position=back" 
                                             class="bg-gradient-to-tl from-blue-500 to-blue-600 px-3 py-1 text-xs rounded-lg text-white hover:from-blue-600 hover:to-blue-700 transition-all duration-200 flex items-center">
                                            <i class="fas fa-edit mr-1"></i> Modifier
                                          </a>
                                          
                                          <!-- Bouton Supprimer -->
                                          <a href="../../../../controller/supprimerCommentaireController.php?id=<?=$commentaire['id']?>&contenu=<?=urlencode($commentaire['contenu'])?>&position=back" 
                                   class="bg-gradient-to-tl from-blue-500 to-blue-600 px-3 py-1 text-xs rounded-lg text-white hover:from-blue-600 hover:to-blue-700 transition-all duration-200 flex items-center">
                                            <i class="fas fa-edit mr-1"></i> Supprimer
                                             </a>
                                        </div>
                                      </div>
                                    </div>
                                  <?php endforeach; ?>
                                </div>
                              <?php else: ?>
                                <p class="text-sm text-slate-500 dark:text-slate-400 text-center py-4">Aucun commentaire pour ce post</p>
                              <?php endif; ?>
                            </div>
                          </div>
                        </td>
                      </tr>
                      <?php
                      endforeach;
                      ?>
                    </tbody>
                  </table>
                </div>
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

  </body>
  <!-- plugin for scrollbar  -->
  <script src="../assets/js/plugins/perfect-scrollbar.min.js" async></script>
  <!-- main script file  -->
  <script src="../assets/js/argon-dashboard-tailwind.js?v=1.0.1" async></script>
</html>
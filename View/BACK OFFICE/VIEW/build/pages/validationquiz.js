document.addEventListener('DOMContentLoaded', function() {

    var addForm = document.getElementById('addQuizForm');

    if (addForm) {
        addForm.addEventListener('submit', function(event) {
            
            var title = document.getElementById('add_titre').value;
            var category = document.getElementById('add_categorie').value;
            var level = document.getElementById('add_niveau').value;
            var points = document.getElementById('add_points').value;

            var errorTitle = document.getElementById('error_add_titre');
            var errorCat = document.getElementById('error_add_categorie');
            var errorLevel = document.getElementById('error_add_niveau');
            var errorPoints = document.getElementById('error_add_points');

            if (errorTitle) errorTitle.classList.remove('active');
            if (errorCat) errorCat.classList.remove('active');
            if (errorLevel) errorLevel.classList.remove('active');
            if (errorPoints) errorPoints.classList.remove('active');

            var hasError = false;

            if (title.trim() == "") {
                if (errorTitle) {
                    errorTitle.innerText = "Title is required";
                    errorTitle.classList.add('active'); 
                }
                hasError = true;
            } else if (title.length < 3) {
                if (errorTitle) {
                    errorTitle.innerText = "Title too short";
                    errorTitle.classList.add('active');
                }
                hasError = true;
            }


            if (category == "") {
                if (errorCat) {
                    errorCat.innerText = "Choose a category";
                    errorCat.classList.add('active');
                }
                hasError = true;
            }

            if (level == "") {
                if (errorLevel) {
                    errorLevel.innerText = "Choose a level";
                    errorLevel.classList.add('active');
                }
                hasError = true;
            }


            if (points == "") {
                if (errorPoints) {
                    errorPoints.innerText = "Enter points";
                    errorPoints.classList.add('active');
                }
                hasError = true;
            } else if (points <= 0) {
                if (errorPoints) {
                    errorPoints.innerText = "Must be positive";
                    errorPoints.classList.add('active');
                }
                hasError = true;
            }

     
            if (hasError) {
                event.preventDefault();
            }
        });
    }



    var editForm = document.getElementById('editQuizForm');

    if (editForm) {
        editForm.addEventListener('submit', function(event) {
            
            var title = document.getElementById('edit_titre').value;
            var category = document.getElementById('edit_categorie').value;
            var level = document.getElementById('edit_niveau').value;
            var points = document.getElementById('edit_points').value;

            var errorTitle = document.getElementById('error_edit_titre');
            var errorCat = document.getElementById('error_edit_categorie');
            var errorLevel = document.getElementById('error_edit_niveau');
            var errorPoints = document.getElementById('error_edit_points');

 
            if (errorTitle) errorTitle.classList.remove('active');
            if (errorCat) errorCat.classList.remove('active');
            if (errorLevel) errorLevel.classList.remove('active');
            if (errorPoints) errorPoints.classList.remove('active');

            var hasError = false;

            if (title.trim() == "") {
                if (errorTitle) {
                    errorTitle.innerText = "Title is required";
                    errorTitle.classList.add('active');
                }
                hasError = true;
            }

            if (category == "") {
                if (errorCat) {
                    errorCat.innerText = "Choose a category";
                    errorCat.classList.add('active');
                }
                hasError = true;
            }

            if (level == "") {
                if (errorLevel) {
                    errorLevel.innerText = "Choose a level";
                    errorLevel.classList.add('active');
                }
                hasError = true;
            }

            if (points == "") {
                if (errorPoints) {
                    errorPoints.innerText = "Enter points";
                    errorPoints.classList.add('active');
                }
                hasError = true;
            }         else if (points <= 0) {
                if (errorPoints) {
                    errorPoints.innerText = "Must be positive";
                    errorPoints.classList.add('active');
                }
                hasError = true;
            }

            if (hasError) {
                event.preventDefault();
            }
        });
    }

});
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

            errorTitle.classList.remove('active');
            errorCat.classList.remove('active');
            errorLevel.classList.remove('active');
            errorPoints.classList.remove('active');

            var hasError = false;

            if (title == "") {
                errorTitle.innerText = "Title is required";
                errorTitle.classList.add('active'); 
                hasError = true;
            } else if (title.length < 3) {
                errorTitle.innerText = "Title too short";
                errorTitle.classList.add('active');
                hasError = true;
            }


            if (category == "") {
                errorCat.innerText = "Choose a category";
                errorCat.classList.add('active');
                hasError = true;
            }

            if (level == "") {
                errorLevel.innerText = "Choose a level";
                errorLevel.classList.add('active');
                hasError = true;
            }


            if (points == "") {
                errorPoints.innerText = "Enter points";
                errorPoints.classList.add('active');
                hasError = true;
            } else if (points <= 0) {
                errorPoints.innerText = "Must be positive";
                errorPoints.classList.add('active');
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

 
            errorTitle.classList.remove('active');
            errorCat.classList.remove('active');
            errorLevel.classList.remove('active');
            errorPoints.classList.remove('active');

            var hasError = false;

            if (title == "") {
                errorTitle.innerText = "Title is required";
                errorTitle.classList.add('active');
                hasError = true;
            }

            if (category == "") {
                errorCat.innerText = "Choose a category";
                errorCat.classList.add('active');
                hasError = true;
            }

            if (level == "") {
                errorLevel.innerText = "Choose a level";
                errorLevel.classList.add('active');
                hasError = true;
            }

            if (points == "") {
                errorPoints.innerText = "Enter points";
                errorPoints.classList.add('active');
                hasError = true;
            }         else if (points <= 0) {
                errorPoints.innerText = "Must be positive";
                errorPoints.classList.add('active');
                hasError = true;
            }

            if (hasError) {
                event.preventDefault();
            }
        });
    }

});
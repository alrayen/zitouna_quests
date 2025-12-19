document.addEventListener('DOMContentLoaded', function() {
    
    // helper function to clear error messages
    function clearErrors(formId) {
        const errorMsgs = document.querySelectorAll(`#${formId} .error-msg`);
        errorMsgs.forEach(msg => {
            msg.innerText = "";
            msg.classList.remove('active');
        });
    }

    // helper function to show error
    function showError(id, message) {
        const errorElement = document.getElementById(id);
        if (errorElement) {
            errorElement.innerText = message;
            errorElement.classList.add('active');
        }
    }

    // --- ADD CHALLENGE FORM ---
    const addChallengeForm = document.getElementById('addChallengeForm');
    if (addChallengeForm) {
        addChallengeForm.addEventListener('submit', function(e) {
            clearErrors('addChallengeForm');
            let hasError = false;

            const titre = document.getElementById('add_titre').value.trim();
            const description = document.getElementById('add_description').value.trim();
            const points = document.getElementById('add_points').value;
            const time = document.getElementById('add_time').value;
            const place = document.getElementById('add_place').value.trim();

            if (titre === "") {
                showError('error_add_titre', "Title is required.");
                hasError = true;
            } else if (titre.length < 3) {
                showError('error_add_titre', "Title must be at least 3 characters.");
                hasError = true;
            }

            if (description === "") {
                showError('error_add_description', "Description is required.");
                hasError = true;
            }

            if (points === "") {
                showError('error_add_points', "Points are required.");
                hasError = true;
            } else if (parseInt(points) <= 0) {
                showError('error_add_points', "Points must be a positive number.");
                hasError = true;
            }

            if (time === "") {
                showError('error_add_time', "Time is required.");
                hasError = true;
            } else if (parseInt(time) <= 0) {
                showError('error_add_time', "Time must be a positive number.");
                hasError = true;
            }

            if (place === "") {
                showError('error_add_place', "Place is required.");
                hasError = true;
            }

            if (hasError) {
                e.preventDefault();
            }
        });
    }

    // --- EDIT CHALLENGE FORM ---
    const editChallengeForm = document.getElementById('editChallengeForm');
    if (editChallengeForm) {
        editChallengeForm.addEventListener('submit', function(e) {
            clearErrors('editChallengeForm');
            let hasError = false;

            const titre = document.getElementById('edit_titre').value.trim();
            const description = document.getElementById('edit_description').value.trim();
            const points = document.getElementById('edit_points').value;
            const time = document.getElementById('edit_time').value;
            const place = document.getElementById('edit_place').value.trim();

            if (titre === "") {
                showError('error_edit_titre', "Title is required.");
                hasError = true;
            } else if (titre.length < 3) {
                showError('error_edit_titre', "Title must be at least 3 characters.");
                hasError = true;
            }

            if (description === "") {
                showError('error_edit_description', "Description is required.");
                hasError = true;
            }

            if (points === "") {
                showError('error_edit_points', "Points are required.");
                hasError = true;
            } else if (parseInt(points) <= 0) {
                showError('error_edit_points', "Points must be a positive number.");
                hasError = true;
            }

            if (time === "") {
                showError('error_edit_time', "Time is required.");
                hasError = true;
            } else if (parseInt(time) <= 0) {
                showError('error_edit_time', "Time must be a positive number.");
                hasError = true;
            }

            if (place === "") {
                showError('error_edit_place', "Place is required.");
                hasError = true;
            }

            if (hasError) {
                e.preventDefault();
            }
        });
    }

    // --- RESOURCE FORM ---
    const resourceForm = document.getElementById('resourceForm');
    if (resourceForm) {
        resourceForm.addEventListener('submit', function(e) {
            clearErrors('resourceForm');
            let hasError = false;

            const nom = document.getElementById('res_nom').value.trim();
            const description = document.getElementById('res_description').value.trim();
            const type = document.getElementById('res_type').value;
            const ordre = document.getElementById('res_ordre').value;
            const isAddMode = !document.getElementById('action_add_res').disabled;

            if (nom === "") {
                showError('error_res_nom', "Resource name is required.");
                hasError = true;
            } else if (nom.length < 3) {
                showError('error_res_nom', "Name must be at least 3 characters.");
                hasError = true;
            }

            if (description === "") {
                showError('error_res_description', "Description is required.");
                hasError = true;
            } else if (description.length > 500) {
                showError('error_res_description', "Description cannot exceed 500 characters.");
                hasError = true;
            }

            if (ordre === "") {
                showError('error_res_ordre', "Order is required.");
                hasError = true;
            } else if (parseInt(ordre) <= 0) {
                showError('error_res_ordre', "Order must be a positive number.");
                hasError = true;
            }

            if (type === 'PDF') {
                if (isAddMode) {
                    const fileInput = document.querySelector('input[name="file_upload"]');
                    if (!fileInput.files || fileInput.files.length === 0) {
                        showError('error_res_file', "Please upload a PDF file.");
                        hasError = true;
                    }
                }
            } else {
                const url = document.getElementById('res_url_input').value.trim();
                if (url === "") {
                    showError('error_res_url', "URL is required for this resource type.");
                    hasError = true;
                } else {
                    try {
                        // basic URL validation
                        if (!url.startsWith('http://') && !url.startsWith('https://') && !url.startsWith('#')) {
                            showError('error_res_url', "Please enter a valid URL (starting with http:// or https://).");
                            hasError = true;
                        }
                    } catch (_) {
                        showError('error_res_url', "Invalid URL format.");
                        hasError = true;
                    }
                }
            }

            if (hasError) {
                e.preventDefault();
            }
        });
    }
});


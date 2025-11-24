document.addEventListener("DOMContentLoaded", function() {

    var form = document.querySelector(".login-wrapper.registration");

    form.addEventListener("submit", function(e) {
        e.preventDefault();

        var prenom = document.getElementById("prenom").value;
        var nom = document.getElementById("nom").value;
        var dateNaissance = document.getElementById("dateNaissance").value;
        var email = document.getElementById("email").value;
        var password = document.getElementById("password").value;
        var password2 = document.getElementById("password2").value;

        document.getElementById("erreurprenom").innerHTML = "";
        document.getElementById("erreurnom").innerHTML = "";
        document.getElementById("erreurdateNaissance").innerHTML = "";
        document.getElementById("erreuremail").innerHTML = "";
        document.getElementById("erreurpassword").innerHTML = "";
        document.getElementById("erreurpassword2").innerHTML = "";

        var valid = true;


        if (prenom == "") {
            document.getElementById("erreurprenom").innerHTML = "Le prénom est requis";
            valid = false;
        }
        if (nom == "") {
            document.getElementById("erreurnom").innerHTML = "Le nom est requis";
            valid = false;
        }


        if (dateNaissance == "") {
            document.getElementById("erreurdateNaissance").innerHTML = "La date de naissance est requise";
            valid = false;
        } else {
            var today = new Date();
            var birthDate = new Date(dateNaissance);
            var age = today.getFullYear() - birthDate.getFullYear();
            var m = today.getMonth() - birthDate.getMonth();
            if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
                age--;
            }
            if (age < 13) {
                document.getElementById("erreurdateNaissance").innerHTML = "Vous devez avoir au moins 13 ans";
                valid = false;
            }
        }


        if (email == "") {
            document.getElementById("erreuremail").innerHTML = "Email requis";
            valid = false;
        } else if (email.indexOf("@") == -1 || email.indexOf(".") == -1) {
            document.getElementById("erreuremail").innerHTML = "Email invalide";
            valid = false;
            

        }


        if (password == "") {
            document.getElementById("erreurpassword").innerHTML = "Mot de passe requis";
            valid = false;
        } else {
            var maj = /[A-Z]/;
            var min = /[a-z]/;
            var chiffre = /[0-9]/;
            var special = /[!@#$%^&*(),.?":{}|<>]/;
            if (!maj.test(password) || !min.test(password) || !chiffre.test(password) || !special.test(password)) {
                document.getElementById("erreurpassword").innerHTML = "Le mot de passe doit contenir majuscule, minuscule, chiffre et caractère spécial";
                valid = false;
            }
        }

        // Vérification mot de passe 2
        if (password2 == "") {
            document.getElementById("erreurpassword2").innerHTML = "Veuillez confirmer le mot de passe";
            valid = false;
        } else if (password != password2) {
            document.getElementById("erreurpassword2").innerHTML = "Les mots de passe ne correspondent pas";
            valid = false;
        }

        if (valid) {
            form.submit();
        }
    });

});
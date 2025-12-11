<?php 
session_start();

if (!isset($_SESSION['code_verified']) || $_SESSION['code_verified'] !== true) {
    header("Location: login.php"); exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Nouveau mot de passe</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/vendor/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="rt_bg-secondary">
    <div class="container rts-section-gap">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="login-wrapper" style="background: rgba(255,255,255,0.05); padding: 40px; border-radius: 20px;">
                    <h3 style="color:white; text-align:center; margin-bottom: 20px;">Réinitialisation</h3>

                    <form id="resetForm" action="../../../../Controller/update_password.php" method="POST">
                        
                        <div class="mb-4">
                            <label style="color:#fff;">Nouveau mot de passe</label>
                            <input type="password" id="password" name="password" placeholder="Votre nouveau mot de passe" style="width:100%; padding:12px; border-radius: 5px; border: 1px solid #444;">
                            <p id="erreurpassword" style="color:#ff4d4d; font-size:13px; margin-top:5px;"></p>
                        </div>

                        <div class="mb-4">
                            <label style="color:#fff;">Confirmer le mot de passe</label>
                            <input type="password" id="password2" name="confirm_password" placeholder="Répétez le mot de passe" style="width:100%; padding:12px; border-radius: 5px; border: 1px solid #444;">
                            <p id="erreurpassword2" style="color:#ff4d4d; font-size:13px; margin-top:5px;"></p>
                        </div>

                        <?php if (isset($_SESSION['error_message'])): ?>
                            <p style="color: #ff4d4d; font-size: 13px; text-align:center; font-weight: bold;">
                                <?php echo $_SESSION['error_message']; unset($_SESSION['error_message']); ?>
                            </p>
                        <?php endif; ?>

                        <button type="submit" class="rts-btn btn-primary" style="width: 100%;">Valider</button>
                    </form>

                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var form = document.getElementById("resetForm");

            form.addEventListener("submit", function(e) {
         
                var valid = true;

           
                var password = document.getElementById("password").value;
                var passwordConfirm = document.getElementById("password2").value;

              
                document.getElementById("erreurpassword").innerHTML = "";
                document.getElementById("erreurpassword2").innerHTML = "";

             
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

              
                if (passwordConfirm == "") {
                    document.getElementById("erreurpassword2").innerHTML = "Veuillez confirmer le mot de passe";
                    valid = false;
                } else if (password != passwordConfirm) {
                    document.getElementById("erreurpassword2").innerHTML = "Les mots de passe ne correspondent pas";
                    valid = false;
                }

                
                if (!valid) {
                    e.preventDefault();
                }
                
            });
        });
    </script>
</body>
</html>
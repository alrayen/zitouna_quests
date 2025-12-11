<?php session_start(); ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mot de passe oublié - Zitouna Quest</title>
    <link rel="stylesheet" href="assets/css/style.css"> <link rel="stylesheet" href="assets/css/vendor/bootstrap.min.css">
</head>
<body class="rt_bg-secondary">
    <div class="rts-registration-area rts-section-gap">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6">
                    <div class="login-wrapper registration" style="background: rgba(255,255,255,0.05); padding: 40px; border-radius: 20px;">
                        <h3 class="title" style="color:white; text-align:center;">Réinitialisation</h3>
                        
                        <?php if (isset($_SESSION['error_message'])): ?>
                            <div class="alert alert-danger"><?php echo $_SESSION['error_message']; unset($_SESSION['error_message']); ?></div>
                        <?php endif; ?>

                        <form action="../../../../Controller/send_reset_code.php" method="POST">
                            <div class="mb-4">
                                <label style="color:white;">Entrez votre adresse email</label>
                                <input type="email" name="email" required placeholder="ex: mon.email@gmail.com" style="width:100%; padding:10px; border-radius:5px;">
                            </div>
                            <button type="submit" class="rts-btn btn-primary">Envoyer le code</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
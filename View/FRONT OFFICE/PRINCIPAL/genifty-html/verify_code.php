<?php session_start(); 
if (!isset($_SESSION['reset_email'])) { header("Location: login.php"); exit(); }
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Vérification Code</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/vendor/bootstrap.min.css">
</head>
<body class="rt_bg-secondary">
    <div class="container rts-section-gap">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="login-wrapper" style="background: rgba(255,255,255,0.05); padding: 40px; border-radius: 20px;">
                    <h3 style="color:white; text-align:center;">Entrez le code</h3>
                    <p style="color:#ccc; text-align:center;">Un code a été envoyé à <?php echo htmlspecialchars($_SESSION['reset_email']); ?></p>

                    <?php if (isset($_SESSION['error_message'])): ?>
                        <div class="alert alert-danger"><?php echo $_SESSION['error_message']; unset($_SESSION['error_message']); ?></div>
                    <?php endif; ?>

                    <form action="../../../../Controller/check_code.php" method="POST">
                        <input type="text" name="code" placeholder="Code à 6 chiffres" required style="width:100%; padding:10px; margin-bottom:15px;">
                        <button type="submit" class="rts-btn btn-primary">Vérifier</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
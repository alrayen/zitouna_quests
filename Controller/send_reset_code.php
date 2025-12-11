<?php
session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . '/Projet2/config.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/Projet2/vendor/autoload.php'); // Pour PHPMailer

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $pdo = config::getConnexion();

    // 1. Vérifier si l'email existe
    $stmt = $pdo->prepare("SELECT * FROM user WHERE email = :email");
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch();

    if ($user) {
        // 2. Générer un code à 6 chiffres
        $code = rand(100000, 999999);

        // 3. Enregistrer le code dans la BDD
        $update = $pdo->prepare("UPDATE user SET verification_code = :code WHERE email = :email");
        $update->execute(['code' => $code, 'email' => $email]);

        // 4. Envoyer l'email
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->SMTPDebug = 2; // <--- AJOUTE CETTE LIGNE POUR VOIR LES ERREURS
            $mail->Debugoutput = 'html'; // Affiche les erreurs proprement
    
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            // Configuration SMTP (Gmail)
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'Mohamedbenhariz8@gmail.com'; // ⚠️ METS TON EMAIL ICI
            $mail->Password   = 'qfjy qthv minj fnfp'; // ⚠️ METS TON MDP D'APPLICATION ICI
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            $mail->setFrom('no-reply@zitounaquest.com', 'Zitouna Quest Security');
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = 'Code de reinitialisation - Zitouna Quest';
            $mail->Body    = "<h3>Votre code de sécurité</h3><p>Voici votre code pour changer de mot de passe : <b style='font-size:20px; color:#00C49F;'>$code</b></p>";

            $mail->send();

            // On stocke l'email en session pour la page suivante
            $_SESSION['reset_email'] = $email;
            header("Location: ../View/FRONT OFFICE/PRINCIPAL/genifty-html/verify_code.php");
            exit();

        } catch (Exception $e) {
            $_SESSION['error_message'] = "Erreur d'envoi d'email. Réessayez.";
        }
    } else {
        $_SESSION['error_message'] = "Aucun compte trouvé avec cet email.";
    }
    header("Location: ../View/FRONT OFFICE/PRINCIPAL/genifty-html/forgot_password.php");
    exit();
}
?>
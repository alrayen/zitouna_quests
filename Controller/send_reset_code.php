<?php
session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . '/Projet2/config.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/Projet2/vendor/autoload.php'); // Pour PHPMailer

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $pdo = config::getConnexion();


    $stmt = $pdo->prepare("SELECT * FROM user WHERE email = :email");
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch();

    if ($user) {
  
        $code = rand(100000, 999999);


        $update = $pdo->prepare("UPDATE user SET verification_code = :code WHERE email = :email");
        $update->execute(['code' => $code, 'email' => $email]);

        
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->SMTPDebug = 2; 
            $mail->Debugoutput = 'html';
    
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
       
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'Mohamedbenhariz8@gmail.com'; 
            $mail->Password   = 'qfjy qthv minj fnfp'; 
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            $mail->setFrom('no-reply@zitounaquest.com', 'Zitouna Quest Security');
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = 'Code de reinitialisation - Zitouna Quest';
            $mail->Body    = "<h3>Votre code de sécurité</h3><p>Voici votre code pour changer de mot de passe : <b style='font-size:20px; color:#00C49F;'>$code</b></p>";

            $mail->send();


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
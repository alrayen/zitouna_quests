<?php
/**
 * CONFIGURATION EMAIL - Zitouna Quest
 * Créer ce fichier dans : config/EmailConfig.php
 */

class EmailConfig {
    
    // ========== PARAMÈTRES SMTP ==========
    // Pour Gmail, Outlook, ou serveur SMTP personnalisé
    
    const SMTP_HOST = 'smtp.gmail.com';  // Serveur SMTP
    const SMTP_PORT = 587;                // Port (587 pour TLS, 465 pour SSL)
    const SMTP_SECURE = 'tls';            // 'tls' ou 'ssl'
    const SMTP_USERNAME = 'votre-email@gmail.com';  // ⚠️ À CHANGER
    const SMTP_PASSWORD = 'votre-mot-de-passe-app'; // ⚠️ À CHANGER
    
    // ========== INFORMATIONS EXPÉDITEUR ==========
    const FROM_EMAIL = 'noreply@zitounaquest.com';
    const FROM_NAME = 'Zitouna Quest';
    const REPLY_TO_EMAIL = 'contact@zitounaquest.com';
    
    // ========== OPTIONS ==========
    const ENABLE_LOGGING = true;  // Enregistrer historique des emails
    const DEBUG_MODE = false;     // Mode debug (affiche les erreurs)
    
    /**
     * Fonction pour envoyer un email avec PHPMailer
     */
    public static function sendEmail($to, $subject, $body, $isHTML = true) {
        require_once __DIR__ . '/../vendor/autoload.php'; // Si vous utilisez Composer
        
        // Utilisation de PHPMailer
        $mail = new PHPMailer\PHPMailer\PHPMailer(true);
        
        try {
            // Configuration SMTP
            $mail->isSMTP();
            $mail->Host = self::SMTP_HOST;
            $mail->SMTPAuth = true;
            $mail->Username = self::SMTP_USERNAME;
            $mail->Password = self::SMTP_PASSWORD;
            $mail->SMTPSecure = self::SMTP_SECURE;
            $mail->Port = self::SMTP_PORT;
            $mail->CharSet = 'UTF-8';
            
            // Expéditeur et destinataire
            $mail->setFrom(self::FROM_EMAIL, self::FROM_NAME);
            $mail->addReplyTo(self::REPLY_TO_EMAIL, self::FROM_NAME);
            $mail->addAddress($to);
            
            // Contenu
            $mail->isHTML($isHTML);
            $mail->Subject = $subject;
            $mail->Body = $body;
            
            // Envoi
            $mail->send();
            
            // Log si activé
            if (self::ENABLE_LOGGING) {
                self::logEmail($to, $subject, 'success');
            }
            
            return ['success' => true, 'message' => 'Email envoyé avec succès'];
            
        } catch (Exception $e) {
            if (self::ENABLE_LOGGING) {
                self::logEmail($to, $subject, 'failed', $e->getMessage());
            }
            
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
    
    /**
     * Alternative : Envoi avec mail() PHP natif (moins fiable)
     */
    public static function sendSimpleEmail($to, $subject, $body) {
        $headers = "From: " . self::FROM_NAME . " <" . self::FROM_EMAIL . ">\r\n";
        $headers .= "Reply-To: " . self::REPLY_TO_EMAIL . "\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
        
        $success = mail($to, $subject, $body, $headers);
        
        if (self::ENABLE_LOGGING) {
            self::logEmail($to, $subject, $success ? 'success' : 'failed');
        }
        
        return $success;
    }
    
    /**
     * Enregistrer l'historique des emails dans la BDD
     */
    private static function logEmail($to, $subject, $status, $error = null) {
        try {
            $db = Config::getConnexion();
            $sql = "INSERT INTO email_logs (recipient, subject, status, error_message, sent_at) 
                    VALUES (:to, :subject, :status, :error, NOW())";
            $query = $db->prepare($sql);
            $query->execute([
                'to' => $to,
                'subject' => $subject,
                'status' => $status,
                'error' => $error
            ]);
        } catch (Exception $e) {
            // Silencieux si le logging échoue
            if (self::DEBUG_MODE) {
                error_log("Email log failed: " . $e->getMessage());
            }
        }
    }
}
?>
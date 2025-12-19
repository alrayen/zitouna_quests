<?php
/**
 * CONTRÔLEUR EMAIL
 * Créer dans : controller/EmailC.php
 */

require_once __DIR__ . '/../config/Connect.php';
require_once __DIR__ . '/../config/EmailConfig.php';
require_once __DIR__ . '/../model/Email.php';

class EmailC {
    
    /**
     * Envoyer un email de validation de don
     */
    public function sendDonationValidatedEmail($donorName, $donorEmail, $amount, $points) {
        $subject = "✅ Votre don a été validé - Zitouna Quest";
        
        // Charger le template
        $body = $this->loadTemplate('donation_validated', [
            'donor_name' => $donorName,
            'amount' => $amount,
            'points' => $points,
            'date' => date('d/m/Y')
        ]);
        
        return EmailConfig::sendSimpleEmail($donorEmail, $subject, $body);
    }
    
    /**
     * Envoyer un email de bienvenue à un sponsor
     */
    public function sendSponsorWelcomeEmail($sponsorName, $sponsorEmail, $sector, $contribution) {
        $subject = "🤝 Bienvenue parmi nos partenaires - Zitouna Quest";
        
        $body = $this->loadTemplate('sponsor_welcome', [
            'sponsor_name' => $sponsorName,
            'sector' => $sector,
            'contribution' => $contribution
        ]);
        
        return EmailConfig::sendSimpleEmail($sponsorEmail, $subject, $body);
    }
    
    /**
     * Envoyer un email personnalisé
     */
    public function sendCustomEmail($to, $subject, $message) {
        $body = $this->loadTemplate('custom', [
            'message' => nl2br($message)
        ]);
        
        return EmailConfig::sendSimpleEmail($to, $subject, $body);
    }
    
    /**
     * Récupérer l'historique des emails
     */
    public function getEmailHistory($limit = 50) {
        $sql = "SELECT * FROM email_logs ORDER BY sent_at DESC LIMIT :limit";
        $db = Config::getConnexion();
        
        try {
            $query = $db->prepare($sql);
            $query->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
            $query->execute();
            return $query->fetchAll();
        } catch (Exception $e) {
            die('Erreur: ' . $e->getMessage());
        }
    }
    
    /**
     * Charger un template d'email
     */
    private function loadTemplate($templateName, $data = []) {
        $templatePath = __DIR__ . "/../templates/email/{$templateName}.php";
        
        if (!file_exists($templatePath)) {
            return $this->getDefaultTemplate($data);
        }
        
        // Extraire les variables pour le template
        extract($data);
        
        // Capturer le contenu du template
        ob_start();
        include $templatePath;
        return ob_get_clean();
    }
    
    /**
     * Template par défaut si aucun template trouvé
     */
    private function getDefaultTemplate($data) {
        return "
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; background: #f4f7f6; }
                .container { max-width: 600px; margin: 0 auto; background: white; padding: 30px; border-radius: 10px; }
                .header { background: #27ae60; color: white; padding: 20px; text-align: center; border-radius: 10px 10px 0 0; }
                .content { padding: 20px; color: #333; }
                .footer { text-align: center; padding: 20px; color: #777; font-size: 12px; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h1>🌱 Zitouna Quest</h1>
                </div>
                <div class='content'>
                    " . (isset($data['message']) ? $data['message'] : 'Message') . "
                </div>
                <div class='footer'>
                    © " . date('Y') . " Zitouna Quest - Ensemble pour un avenir durable
                </div>
            </div>
        </body>
        </html>
        ";
    }
    
    /**
     * Statistiques des emails
     */
    public function getEmailStats() {
        $sql = "SELECT 
                    COUNT(*) as total,
                    SUM(CASE WHEN status = 'success' THEN 1 ELSE 0 END) as success,
                    SUM(CASE WHEN status = 'failed' THEN 1 ELSE 0 END) as failed
                FROM email_logs";
        $db = Config::getConnexion();
        
        try {
            return $db->query($sql)->fetch();
        } catch (Exception $e) {
            return ['total' => 0, 'success' => 0, 'failed' => 0];
        }
    }
}
?>
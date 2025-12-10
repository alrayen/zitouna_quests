<?php
class config
{   private static $pdo = null;
    public static function getConnexion()
    {
        if (!isset(self::$pdo)) {
            $servername="sql100.infinityfree.com";
            $username="if0_40496450";
            $password ="DfXUbpyUMC8";
            $dbname="if0_40496450_projet";
            try {
                self::$pdo = new PDO("mysql:host=$servername;dbname=$dbname",
                        $username,
                        $password
                   
                );
                self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                self::$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
               
               
            } catch (Exception $e) {
                die('Erreur: ' . $e->getMessage());
            }
        }
        return self::$pdo;
    }
    public static function getGeminiKey(): string {
        // IMPORTANT: Replace 'YOUR_GEMINI_API_KEY_HERE' with your actual key
        // For production, always use getenv() to read from a secure environment variable.
        return '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'; 
    }
}
config::getConnexion();
?>










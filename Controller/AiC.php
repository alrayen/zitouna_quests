<?php
require_once __DIR__ . '/../config.php';

class AiC {

    // 1. Récupérer les données réelles (Mois => Montant Total)
    public function getDataDonations() {
        $db = Config::getConnexion();
        // On groupe les dons par mois pour l'analyse
        $sql = "SELECT DATE_FORMAT(date_don, '%Y-%m') as mois, SUM(montant) as total 
                FROM donation 
                GROUP BY mois 
                ORDER BY mois ASC LIMIT 12"; // Les 12 derniers mois
        try {
            return $db->query($sql)->fetchAll();
        } catch (Exception $e) { die('Erreur: ' . $e->getMessage()); }
    }

    // 2. L'ALGORITHME IA (Régression Linéaire Simple)
    // Entrée : tableau des X (temps) et Y (montants)
    // Sortie : La prédiction pour le mois suivant
    public function predictNextMonth($data) {
        $n = count($data);
        if ($n < 2) return 0; // Pas assez de données pour prédire

        $x = []; // Les mois (1, 2, 3...)
        $y = []; // Les montants
        $i = 1;

        foreach ($data as $d) {
            $x[] = $i;
            $y[] = $d['total'];
            $i++;
        }

        // Calculs statistiques (Sommes)
        $sumX = array_sum($x);
        $sumY = array_sum($y);
        
        $sumXY = 0;
        $sumXX = 0;

        for ($k = 0; $k < $n; $k++) {
            $sumXY += ($x[$k] * $y[$k]);
            $sumXX += ($x[$k] * $x[$k]);
        }

        // Formule de la pente (Slope) 'm' et l'ordonnée à l'origine 'b' (y = mx + b)
        $m = ($n * $sumXY - $sumX * $sumY) / ($n * $sumXX - $sumX * $sumX);
        $b = ($sumY - $m * $sumX) / $n;

        // Prédiction pour le mois suivant (n + 1)
        $nextMonthIndex = $n + 1;
        $prediction = ($m * $nextMonthIndex) + $b;

        return max(0, round($prediction, 2)); // On ne prédit pas de négatif
    }
}
?>
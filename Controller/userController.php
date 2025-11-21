<?php
include (__DIR__ .'/../config.php');
include (__DIR__ .'/../Model/user.php'); 
class UserController {
    public function getAllUsers() {
        try {
            $pdo = config::getConnexion();
            $stmt = $pdo->prepare("SELECT * FROM user");
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (Exception $e) {
            echo "Erreur lors de la récupération des utilisateurs: " . $e->getMessage();
            return [];
        }
    }

    public function deleteUser($id) {
        try {
            $pdo = config::getConnexion();
            $stmt = $pdo->prepare("DELETE FROM user WHERE id_user = :id");
            $stmt->execute(['id' => $id]);
            return true;
        } catch (Exception $e) {
            echo "Erreur lors de la suppression de l'utilisateur: " . $e->getMessage();
            return false;
        }
    }
    public function adminUpdateUser($id, $nom, $prenom, $email, $niveau, $points, $role) 
    {
        try {
            $pdo = config::getConnexion();
            
            
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM user WHERE email = :email AND id_user != :id");
            $stmt->execute(['email' => $email, 'id' => $id]);
            if ($stmt->fetchColumn() > 0) {
                return ['success' => false, 'message' => 'Cet email est déjà utilisé par un autre utilisateur.'];
            }

          
            $sql = "UPDATE user SET 
                        nom = :nom, 
                        Prenom = :prenom, 
                        email = :email, 
                        niveau = :niveau, 
                        points = :points, 
                        role = :role 
                    WHERE id_user = :id";
                    
            $stmt = $pdo->prepare($sql);
            
            $stmt->execute([
                'id' => $id,
                'nom' => $nom,
                'prenom' => $prenom,
                'email' => $email,
                'niveau' => $niveau,
                'points' => $points,
                'role' => $role
            ]);
            
            return ['success' => true];
        } catch (Exception $e) {
            // Handle database error
            return ['success' => false, 'message' => "Database error: " . $e->getMessage()];
        }
    }
    
}
?>
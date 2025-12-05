<?php
require_once ($_SERVER['DOCUMENT_ROOT'] . '/Projet2/Model/user.php'); 
require_once ($_SERVER['DOCUMENT_ROOT'] . '/Projet2/vendor/autoload.php'); 

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class UserController {


    

    public function getAllUsers() {
        try {
            $pdo = config::getConnexion(); 
            $stmt = $pdo->prepare("SELECT * FROM user");
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (Exception $e) {
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
            return false;
        }
    }

    public function banUser($id) {
        try {
            return User::banUser($id);
        } catch (Exception $e) {
            return false;
        }
    }

    public function unbanUser($id) {
        try {
            return User::unbanUser($id);
        } catch (Exception $e) {
            return false;
        }
    }

    public function adminUpdateUser($id, $nom, $prenom, $email, $niveau, $points, $role) {
        try {
            $pdo = config::getConnexion();
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM user WHERE email = :email AND id_user != :id");
            $stmt->execute(['email' => $email, 'id' => $id]);
            if ($stmt->fetchColumn() > 0) {
                return ['success' => false, 'message' => 'Cet email est déjà utilisé.'];
            }
            $sql = "UPDATE user SET nom = :nom, Prenom = :prenom, email = :email, niveau = :niveau, points = :points, role = :role WHERE id_user = :id";     
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['id' => $id, 'nom' => $nom, 'prenom' => $prenom, 'email' => $email, 'niveau' => $niveau, 'points' => $points, 'role' => $role]);
            return ['success' => true];
        } catch (Exception $e) {
            return ['success' => false, 'message' => "Database error: " . $e->getMessage()];
        }
    }
    
    public function resetFaceData($userId) {
        $sql = "UPDATE user SET face_descriptor = NULL WHERE id_user = :id_user";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute(['id_user' => $userId]);
            return ['success' => true];
        } catch (Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    public function getAllFaceDescriptors() {
        $sql = "SELECT id_user, face_descriptor FROM user WHERE face_descriptor IS NOT NULL";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute();
            $users = $query->fetchAll(PDO::FETCH_ASSOC);
            $descriptors = [];
            foreach ($users as $user) {
                $decodedDescriptor = json_decode($user['face_descriptor'], true);
                if (is_array($decodedDescriptor)) {
                    $descriptors[] = ['id_user' => $user['id_user'], 'descriptor' => $decodedDescriptor];
                }
            }
            return $descriptors;
        } catch (Exception $e) {
            return [];
        }
    }

    public function getFaceDescriptorByEmail($email) {
        $sql = "SELECT id_user, face_descriptor FROM user WHERE email = :email AND face_descriptor IS NOT NULL";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute(['email' => $email]);
            $user = $query->fetch(PDO::FETCH_ASSOC);
            if ($user && !empty($user['face_descriptor'])) {
                $user['descriptor'] = json_decode($user['face_descriptor'], true);
                return $user;
            }
            return null;
        } catch (Exception $e) {
            return null;
        }
    }


    public function inscription($nom, $prenom, $email, $password, $date_naissance, $face_descriptor = null) {
        if (User::emailExists($email)) {
            if (session_status() == PHP_SESSION_NONE) { session_start(); }
            $_SESSION['error_message'] = "Cet email est déjà utilisé.";
            
            header("Location: /Projet2/View/FRONT%20OFFICE/PRINCIPAL/genifty-html/registration.php");
            exit();
        }

        $user = new User($nom, $prenom, $date_naissance, $email, $password);
        
        if (!empty($face_descriptor)) {
            $user->setFaceDescriptor($face_descriptor);
        }

        $code = rand(100000, 999999);
        $user->setVerificationCode($code);

        if ($user->register()) {
            $this->sendVerificationEmail($email, $code);
            
            header("Location: /Projet2/View/FRONT%20OFFICE/PRINCIPAL/genifty-html/verification_page.php?email=" . $email);
            exit();
        } else {
            if (session_status() == PHP_SESSION_NONE) { session_start(); }
            $_SESSION['error_message'] = "Erreur lors de l'enregistrement dans la base de données.";
            header("Location: /Projet2/View/FRONT%20OFFICE/PRINCIPAL/genifty-html/registration.php");
            exit();
        }
    }

    private function sendVerificationEmail($email, $code) {
        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com'; 
            $mail->SMTPAuth   = true;
            $mail->Username   = 'Mohamedbenhariz8@gmail.com';  
            $mail->Password   = 'qfjy qthv minj fnfp'; 
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            $mail->setFrom('no-reply@zitounaquest.com', 'Zitouna Quest');
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = 'Votre code de verification Zitouna Quest';
            $mail->Body    = "<h1>Bienvenue !</h1><p>Votre code de validation est : <b>$code</b></p>";
            $mail->AltBody = "Votre code de validation est : $code";

            $mail->send();
        } catch (Exception $e) {
        }
    }

    public function verifierCode($email, $code) {
        if (User::verifyAccount($email, $code)) {
            header("Location: /Projet2/View/FRONT%20OFFICE/PRINCIPAL/genifty-html/login.php?success=verified");
        } else {
            return "Code incorrect.";
        }
    }

    public function loginWithFace($faceDescriptorJSON) {
        $allUsersDescriptors = $this->getAllFaceDescriptors();

        $payload = json_encode([
            'login_descriptor' => json_decode($faceDescriptorJSON),
            'user_descriptors' => $allUsersDescriptors
        ]);

        $ch = curl_init('http://127.0.0.1:5000/find_match');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($payload))
        );

        $result = curl_exec($ch);
        curl_close($ch);

        $data = json_decode($result, true);

        if (isset($data['match_found']) && $data['match_found'] == true) {
            $userId = $data['user_id'];
            $user = User::getUserById($userId);
            
            if ($user) {
                if (session_status() == PHP_SESSION_NONE) { session_start(); }
                $_SESSION['user_id'] = $user['id_user'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['nom'] = $user['nom'];
                return ['success' => true];
            }
        }
        return ['success' => false, 'message' => 'Visage non reconnu'];
    }

} 
?>
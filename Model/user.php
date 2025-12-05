<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/Projet2/config.php');

class User
{
    public int $id;
    private string $nom;
    private string $prenom;
    private string $datenaissance;
    private string $email;
    private string $role;
    private string $password;
    private int $niveau;
    public string $bio;
    public int $pointtotal;
    public string $photo;
    public string $badge;
    public int $etat;
    private ?string $face_descriptor; 
    
    private ?string $verification_code = null;
    private int $is_verified = 0; 

    public string $rank;
    
    public ?string $bio_audio = null;

    public function __construct(string $nom, string $prenom, string $datenaissance, string $email, string $password)
    {
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->datenaissance = $datenaissance;
        $this->email = $email;
        $this->password = $password;
        $this->role = 'user';
        $this->niveau = 1;
        $this->pointtotal = 0;
        $this->photo = 'default.png';
        $this->badge = 'Nouveau';
        $this->rank = 'Débutant';
        $this->bio = '';
        $this->etat = 0;
        $this->face_descriptor = null;
        $this->bio_audio = null;
    }

    public function getNom(){ return $this->nom; }
    public function getPrenom(){ return $this->prenom; }
    public function getDatenaissance(){ return $this->datenaissance; }
    public function getEmail(){ return $this->email; }
    public function getPassword(){ return $this->password; }
    public function getNiveau(){ return $this->niveau; }
    public function getRole(){ return $this->role; }
    
    public function setNom(string $nom){ $this->nom = $nom; }
    public function setPrenom(string $prenom){ $this->prenom = $prenom; }
    public function setDatenaissance(string $datenaissance){ $this->datenaissance = $datenaissance; }
    public function setEmail(string $email){ $this->email = $email; }
    public function setPassword(string $password){ $this->password = $password; }
    public function setNiveau(int $niveau){ $this->niveau = $niveau; }
    public function setRole(string $role){ $this->role = $role; }
    public function setEtat(int $etat){ $this->etat = $etat; }
    public function getEtat(){ return $this->etat; }

    public function setFaceDescriptor($descriptor) {
        $this->face_descriptor = $descriptor;
    }

    public function setPhoto(string $photo) {
        $this->photo = $photo;
    }

    public function setVerificationCode($code) {
        $this->verification_code = $code;
    }


    public function register(){
        try {
            $pdo = config::getConnexion();
            $hash = password_hash($this->password, PASSWORD_DEFAULT);
            
            $stmt = $pdo->prepare("INSERT INTO user (Prenom, nom, email, password, birthdate, role, points, photo, bio, badges, rank, niveau, face_descriptor, verification_code, is_verified, bio_audio)
                                   VALUES (:Prenom, :nom, :email, :password, :birthdate, :role, :points, :photo, :bio, :badges, :rank, :niveau, :face_descriptor, :verification_code, 0, :bio_audio)");
            
            $success = $stmt->execute([
                'Prenom' => $this->prenom,          
                'nom' => $this->nom,
                'email' => $this->email,
                'password' => $hash,                 
                'birthdate' => $this->datenaissance,
                'role' => $this->role,
                'points' => $this->pointtotal,      
                'photo' => $this->photo,
                'bio' => $this->bio ?? null,       
                'badges' => $this->badge,
                'rank' => $this->rank,
                'niveau' => $this->niveau,
                'face_descriptor' => $this->face_descriptor ?? '',
                'verification_code' => $this->verification_code,
                'bio_audio' => $this->bio_audio ?? null 
            ]);

            return $success;
        } catch (Exception $e) {
            echo "Erreur: " . $e->getMessage();
            return false;
        }
    }

    public static function login($email, $password) {
        try {
            $pdo = config::getConnexion();
            $stmt = $pdo->prepare("SELECT * FROM user WHERE email = :email");
            $stmt->execute(['email' => $email]);
            $user = $stmt->fetch();
            
            if ($user && password_verify($password, $user['password'])) {
                if (isset($user['etat']) && $user['etat'] == 1) {
                    return 'banned';
                }
                return $user; 
            }
            return false; 
        } catch (Exception $e) {
            throw new Exception("Erreur lors de la connexion: " . $e->getMessage());
        }
    }

    public static function emailExists($email) {
        try {
            $pdo = config::getConnexion();
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM user WHERE email = :email");
            $stmt->execute(['email' => $email]);
            return $stmt->fetchColumn() > 0;
        } catch (Exception $e) {
            return false;
        }
    }

    public static function getUserById($id) {
        try {
            $pdo = config::getConnexion();
            $stmt = $pdo->prepare("SELECT * FROM user WHERE id_user = :id");
            $stmt->execute(['id' => $id]);
            return $stmt->fetch();
        } catch (Exception $e) {
            return false;
        }
    }

    public static function getUserByEmail($email) {
        try {
            $pdo = config::getConnexion();
            $stmt = $pdo->prepare("SELECT * FROM user WHERE email = :email");
            $stmt->execute(['email' => $email]);
            return $stmt->fetch();
        } catch (Exception $e) {
            return false;
        }
    }

    public static function updateProfile($id, $nom, $prenom, $email, $birthdate, $bio, $photo = null, $password = null, $audio = null) {
        try {
            $pdo = config::getConnexion();
            
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM user WHERE email = :email AND id_user != :id");
            $stmt->execute(['email' => $email, 'id' => $id]);
            
            if ($stmt->fetchColumn() > 0) {
                return ['success' => false, 'message' => 'Cet email est déjà utilisé par un autre utilisateur.'];
            }
            
            $updateFields = [];
            $params = [
                'id' => $id,
                'nom' => $nom,
                'Prenom' => $prenom,
                'email' => $email,
                'birthdate' => $birthdate,
                'bio' => $bio
            ];
            
            $updateFields[] = "nom = :nom";
            $updateFields[] = "Prenom = :Prenom";
            $updateFields[] = "email = :email";
            $updateFields[] = "birthdate = :birthdate";
            $updateFields[] = "bio = :bio";
            
            if ($audio !== null && $audio !== '') {
                $updateFields[] = "bio_audio = :bio_audio";
                $params['bio_audio'] = $audio;
            }
            
            if (!empty($password)) {
                $hash = password_hash($password, PASSWORD_DEFAULT);
                $updateFields[] = "password = :password";
                $params['password'] = $hash;
            }
            
            if ($photo !== null && $photo !== '') {
                $updateFields[] = "photo = :photo";
                $params['photo'] = $photo;
            }
            
            $sql = "UPDATE user SET " . implode(", ", $updateFields) . " WHERE id_user = :id";
            $stmt = $pdo->prepare($sql);
            
            if ($stmt->execute($params)) {
                return ['success' => true, 'message' => 'Profil mis à jour avec succès.'];
            } else {
                return ['success' => false, 'message' => 'Erreur lors de la mise à jour du profil.'];
            }
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Erreur: ' . $e->getMessage()];
        }
    }

    public static function banUser($id) {
        try {
            $pdo = config::getConnexion();
            $sql = "UPDATE user SET etat = 1 WHERE id_user = :id";
            $stmt = $pdo->prepare($sql);
            return $stmt->execute(['id' => $id]);
        } catch (Exception $e) {
            return false;
        }
    }

    public static function unbanUser($id) {
        try {
            $pdo = config::getConnexion();
            $sql = "UPDATE user SET etat = 0 WHERE id_user = :id";
            $stmt = $pdo->prepare($sql);
            return $stmt->execute(['id' => $id]);
        } catch (Exception $e) {
            return false;
        }
    }

    public static function verifyAccount($email, $code) {
        try {
            $pdo = config::getConnexion();
            $stmt = $pdo->prepare("SELECT * FROM user WHERE email = :email AND verification_code = :code");
            $stmt->execute(['email' => $email, 'code' => $code]);
            $user = $stmt->fetch();

            if ($user) {
                $update = $pdo->prepare("UPDATE user SET is_verified = 1, verification_code = NULL WHERE email = :email");
                $update->execute(['email' => $email]);
                return true;
            }
            return false;
        } catch (Exception $e) {
            return false;
        }
    }
}
?>
<?php
require_once(__DIR__ . '/../config.php');
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
    
    public string $rank;
    public function __construct(string $nom, string $prenom, string $datenaissance, string $email, string $password,string $role, int $niveau, int $pointtotal, string $photo, string $badge, string $rank,string $bio)
    {
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->datenaissance = $datenaissance;
        $this->email = $email;
        $this->password = $password;
        $this->niveau = $niveau;
        $this->pointtotal = $pointtotal;
        $this->photo = $photo;
        $this->badge = $badge;
        $this->role = $role;
        $this->rank = $rank;
        $this->bio= $bio;
        
    }
    public function getNom(){
        return $this->nom;
    }
    public function getPrenom(){
        return $this->prenom;
    }
    public function getDatenaissance(){
        return $this->datenaissance;
    }
    public function getEmail(){
        return $this->email;
    }
    public function getPassword(){
        return $this->password;
    }
    public function getNiveau(){
        return $this->niveau;
    }
    public function getRole(){
        return $this->role;
    }
    public function setNom(string $nom){
        $this->nom = $nom;
    }
    public function setPrenom(string $prenom){
        $this->prenom = $prenom;
    }
    public function setDatenaissance(string $datenaissance){
        $this->datenaissance = $datenaissance;
    }
    public function setEmail(string $email){
        $this->email = $email;
    }
    public function setPassword(string $password){
        $this->password = $password;
    }
    public function setNiveau(int $niveau){
        $this->niveau = $niveau;
    }
    public function setRole(string $role){
        $this->role = $role;
    }
    public function show(User $user){
      echo "<table border='1'>";
      echo "<tr>";
        echo "<td>Nom</td>";
        echo "<td>Prenom</td>";
        echo "<td>Date de naissance</td>";
        echo "<td>Email</td>";
        echo "<td>Password</td>";
        echo "<td>Niveau</td>";
        echo "</tr>";
        echo "<tr>";
        echo "<td> ".$user->getNom()." </td>";
        echo "<td> ".$user->getPrenom()." </td>";
        echo "<td> ".$user->getDatenaissance()." </td>";
        echo "<td> ".$user->getEmail()." </td>";
        echo "<td> ".$user->getPassword()." </td>";
        echo "<td> ".$user->getNiveau()." </td>";
        echo "</tr>";
        echo "</table>";  
    }

public function register(){
    try {
        $pdo = config::getConnexion();
        $hash = password_hash($this->password, PASSWORD_DEFAULT);
        
       
        $stmt = $pdo->prepare("INSERT INTO user (Prenom, nom, email, password, birthdate, role, points, photo, bio, badges, rank, niveau)
                               VALUES (:Prenom, :nom, :email, :password, :birthdate, :role, :points, :photo, :bio, :badges, :rank, :niveau)");
        
        return $stmt->execute([
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
            'niveau' => $this->niveau
        ]);
    } catch (Exception $e) {
        echo "Erreur lors de l'inscription: " . $e->getMessage();
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
            return $user; 
        }
        
        return false; // Login échoué
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

public static function updateProfile($id, $nom, $prenom, $email, $birthdate, $bio, $photo = null, $password = null) {
    try {
        $pdo = config::getConnexion();
        
        // Check if email is already used by another user
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM user WHERE email = :email AND id_user != :id");
        $stmt->execute(['email' => $email, 'id' => $id]);
        
        if ($stmt->fetchColumn() > 0) {
            return ['success' => false, 'message' => 'Cet email est déjà utilisé par un autre utilisateur.'];
        }
        
        // Build update query
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
        
        // Update password if provided
        if (!empty($password)) {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $updateFields[] = "password = :password";
            $params['password'] = $hash;
        }
        
        // Update photo if provided
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
}

?>
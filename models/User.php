<?php
class User {
    private $db;
    private $id;
    private $nom;
    private $email;
    private $password;
    private $role;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    // Getters et Setters
    public function getId() { return $this->id; }
    public function getNom() { return $this->nom; }
    public function getEmail() { return $this->email; }
    public function getRole() { return $this->role; }
    
    public function setNom($nom) { $this->nom = $nom; }
    public function setEmail($email) { $this->email = $email; }
    public function setPassword($password) { $this->password = $password; }
    public function setRole($role) { $this->role = $role; }
    
    // Méthodes CRUD
public function findByEmail($email) {
    try {
        // Validate and sanitize email
        $email = filter_var(trim($email), FILTER_VALIDATE_EMAIL);
        if (!$email) {
            throw new Exception("Invalid email format");
        }

        $db = Database::getInstance();
        
        // Case-insensitive search
        $query = "SELECT * FROM users WHERE LOWER(email) = LOWER(:email) LIMIT 1";
        $stmt = $db->prepare($query);
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        
        $user = $stmt->fetch();
        
        if (!$user) {
            error_log("No user found with email: " . $email);
            return false;
        }
        
        return $user;
    } catch(PDOException $e) {
        error_log("Database error in findByEmail: " . $e->getMessage());
        return false;
    } catch(Exception $e) {
        error_log("Error in findByEmail: " . $e->getMessage());
        return false;
    }
}
    
    public function findAll() {
        $query = "SELECT * FROM users";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function findById($id) {
        $query = "SELECT * FROM users WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function update($id, $data) {
        $query = "UPDATE users SET nom = :nom, email = :email, role = :role WHERE id = :id";
        $stmt = $this->db->prepare($query);
        
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':nom', $data['nom']);
        $stmt->bindParam(':email', $data['email']);
        $stmt->bindParam(':role', $data['role']);
        
        return $stmt->execute();
    }
    
    public function delete($id) {
        $query = "DELETE FROM users WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        
        return $stmt->execute();
    }
    
    public function login($email, $password) {
        $user = $this->findByEmail($email);
        
        if($user && password_verify($password, $user['password'])) {
            $this->id = $user['id'];
            $this->nom = $user['nom'];
            $this->email = $user['email'];
            $this->role = $user['role'];
            
            $_SESSION['user'] = [
                'id' => $this->id,
                'nom' => $this->nom,
                'email' => $this->email,
                'role' => $this->role
            ];
            
            return true;
        }
        
        return false;
    }
    
    public function logout() {
        session_destroy();
    }
    public function getAllUsers() {
    try {
        $db = Database::getInstance();
        $query = "SELECT * FROM users";
        $stmt = $db->prepare($query);
        $stmt->execute();
        
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        if(empty($results)) {
            error_log("No users found in database");
            return []; // Return empty array instead of null
        }
        
        return $results;
    } catch(PDOException $e) {
        error_log("Database error in getAllUsers: " . $e->getMessage());
        return []; // Return empty array on error
    }
}
public function create($data) {
    try {
        // Validate role
        $allowedRoles = ['admin', 'preparateur', 'client'];
        if (!in_array($data['role'], $allowedRoles)) {
            throw new Exception("Invalid user role");
        }

        $query = "INSERT INTO users (nom, email, password, role) VALUES (:nom, :email, :password, :role)";
        $stmt = $this->db->prepare($query);
        
        $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);
        
        $stmt->bindParam(':nom', $data['nom']);
        $stmt->bindParam(':email', $data['email']);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->bindParam(':role', $data['role']);
        
        return $stmt->execute();
    } catch(PDOException $e) {
        error_log("Database error in create: ".$e->getMessage());
        return false;
    }
}
}
?>
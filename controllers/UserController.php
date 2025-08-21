<?php
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../config/database.php';
class UserController {
    private $userModel;
    
    public function __construct() {
        $this->userModel = new User();
    }
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
    public function register() {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $errors = [];
            $data = []; // Initialize data array
            
            // Validate inputs
            if(empty($_POST['nom'])) {
                $errors[] = "Le nom est obligatoire";
            }
            
            if(empty($_POST['email']) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                $errors[] = "Email invalide";
            }
            
            if(empty($_POST['password']) || strlen($_POST['password']) < 6) {
                $errors[] = "Le mot de passe doit contenir au moins 6 caractères";
            }
            
            // Check if confirm_password exists in POST
            if(!isset($_POST['confirm_password']) || $_POST['password'] !== $_POST['confirm_password']) {
                $errors[] = "Les mots de passe ne correspondent pas";
            }

            // Only proceed if no errors
            if(empty($errors)) {
                $data = [
                    'nom' => trim($_POST['nom']),
                    'email' => trim($_POST['email']),
                    'password' => trim($_POST['password']),
                    'role' => $_POST['role'] ?? 'client' // Use provided role or default to 'client'
                ];

                // Validate role
                $allowedRoles = ['admin', 'preparateur', 'client'];
                if (!in_array($data['role'], $allowedRoles)) {
                    $errors[] = "Rôle d'inscription invalide";
                    return $errors;
                }

                // Hash password and create user
                $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
                
                if($this->userModel->create($data)) {
                    header('Location: login.php?success=1');
                    exit;
                } else {
                    $errors[] = "Une erreur est survenue lors de l'inscription";
                }
            }
            
            return $errors;
        }
    }
    
public function login($email, $password) {
    try {
        // Debug input
        error_log("Login attempt for: $email");
        
        $user = $this->findByEmail($email);
        
        if(!$user) {
            error_log("User not found: $email");
            return false;
        }
        
        if(!password_verify($password, $user['password'])) {
            error_log("Password mismatch for: $email");
            return false;
        }
        
        // Regenerate session ID for security
        session_regenerate_id(true);
        
        $_SESSION['user'] = [
            'id' => $user['id'],
            'nom' => $user['nom'],
            'email' => $user['email'],
            'role' => $user['role']
        ];
        
        error_log("Session created for: ".$user['email']);
        
            switch($user['role']) {
        case 'admin':
            return '/patisserie/views/user/back/admin/users.php'; // Points to admin users list
        case 'preparateur':
            return '/patisserie/views/user/back/admin/products/create.php'; // Needs to be created
        case 'client':
            return '/patisserie/views/front/index.php';
        default:
            return '/patisserie/index.php';
        }
    } catch(Exception $e) {
        error_log("Login error: ".$e->getMessage());
        return false;
    }
}

public function registerStaff($data) {
    if (!$this->isAdmin()) {
        throw new Exception("Only administrators can register staff");
    }

    // Validate role
    if (!in_array($data['role'], ['admin', 'preparateur'])) {
        throw new Exception("Invalid staff role");
    }

    // Check if email exists
    if ($this->userModel->findByEmail($data['email'])) {
        throw new Exception("Email already registered");
    }

    // Hash password
    $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

    return $this->userModel->create($data);
}
    
    public function getAllUsers() {
        return $this->userModel->findAll();
    }
    
    public function getUserById($id) {
        return $this->userModel->findById($id);
    }
    
    public function updateUser($id, $data) {
        return $this->userModel->update($id, $data);
    }
    
    public function deleteUser($id) {
        return $this->userModel->delete($id);
    }
    
    public function isAdmin() {
        return isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin';
    }
    
    public function isPreparateur() {
        return isset($_SESSION['user']) && $_SESSION['user']['role'] === 'preparateur';
    }
    
    public function isClient() {
        return isset($_SESSION['user']) && $_SESSION['user']['role'] === 'client';
    }
}
?>
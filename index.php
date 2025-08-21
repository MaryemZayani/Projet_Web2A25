<?php
session_start();
require_once 'config/database.php';

// Routeur simple
$page = isset($_GET['page']) ? $_GET['page'] : 'home';

switch($page) {
case 'test-login':
    require 'controllers/UserController.php';
    $controller = new UserController();
    
    // Test with known good credentials
    $redirect = $controller->login('may4@test.com', '123456');
    
    echo "Should redirect to: $redirect";
    echo "<pre>Session: ".print_r($_SESSION, true)."</pre>";
    exit;
        
        require 'views/user/login.php';
        break;
        
    case 'register':
        require 'controllers/UserController.php';
        $userController = new UserController();
        
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $errors = $userController->register();
        }
        
        require 'views/user/register.php';
        break;
        
    case 'logout':
        require 'models/User.php';
        $user = new User();
        $user->logout();
        header('Location: index.php');
        break;
        
    default:
        require 'views/front/home.php';
}
?>
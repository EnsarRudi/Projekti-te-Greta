<?php
// Mos shfaq errors në output, vetëm në JSON
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);

// Sigurohu që nuk ka output para JSON
ob_start();

header('Content-Type: application/json');

try {
    require_once __DIR__ . '/../config.php';
    require_once __DIR__ . '/../classes/User.php';
    
    // Pastro çdo output që mund të ketë mbetur
    ob_clean();
    
    $user = new User();
    $action = $_POST['action'] ?? '';

    switch ($action) {
        case 'register':
            $name = trim($_POST['name'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            
            $result = $user->register($name, $email, $password);
            ob_clean();
            echo json_encode($result);
            exit;
            break;

        case 'login':
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            
            $result = $user->login($email, $password);
            ob_clean();
            echo json_encode($result);
            exit;
            break;

        case 'logout':
            $result = $user->logout();
            ob_clean();
            echo json_encode($result);
            exit;
            break;

        case 'check':
            ob_clean();
            echo json_encode([
                'logged_in' => $user->isLoggedIn(),
                'is_admin' => $user->isAdmin(),
                'user' => $user->getCurrentUser()
            ]);
            exit;
            break;

        default:
            ob_clean();
            echo json_encode(['success' => false, 'message' => 'Aksion i pavlefshëm.']);
            exit;
    }
} catch (PDOException $e) {
    ob_clean();
    http_response_code(500);
    echo json_encode([
        'success' => false, 
        'message' => 'Gabim databaze: ' . $e->getMessage()
    ]);
    exit;
} catch (Exception $e) {
    ob_clean();
    http_response_code(500);
    echo json_encode([
        'success' => false, 
        'message' => 'Gabim serveri: ' . $e->getMessage()
    ]);
    exit;
}


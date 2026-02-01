<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../classes/User.php';
require_once __DIR__ . '/../classes/Contact.php';

header('Content-Type: application/json');

$user = new User();
$contact = new Contact();

if (!$user->isLoggedIn() || !$user->isAdmin()) {
    // Allow contact form submission without login
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'submit') {
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $message = trim($_POST['message'] ?? '');
        
        $result = $contact->submitMessage($name, $email, $message);
        echo json_encode($result);
        exit;
    }
    
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Akses i refuzuar.']);
    exit;
}

$action = $_GET['action'] ?? $_POST['action'] ?? '';

switch ($action) {
    case 'list':
        $unreadOnly = isset($_GET['unread_only']) && $_GET['unread_only'] === 'true';
        $messages = $contact->getAll($unreadOnly);
        echo json_encode(['success' => true, 'data' => $messages]);
        break;

    case 'mark_read':
        $id = (int)($_POST['id'] ?? 0);
        if ($contact->markAsRead($id)) {
            echo json_encode(['success' => true, 'message' => 'Mesazhi u shënuar si i lexuar.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Gabim.']);
        }
        break;

    case 'delete':
        $id = (int)($_POST['id'] ?? 0);
        if ($contact->delete($id)) {
            echo json_encode(['success' => true, 'message' => 'Mesazhi u fshi.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Gabim.']);
        }
        break;

    case 'unread_count':
        $count = $contact->getUnreadCount();
        echo json_encode(['success' => true, 'count' => $count]);
        break;

    default:
        echo json_encode(['success' => false, 'message' => 'Aksion i pavlefshëm.']);
}


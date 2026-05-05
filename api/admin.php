<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../classes/User.php';
require_once __DIR__ . '/../classes/Product.php';
require_once __DIR__ . '/../classes/News.php';
require_once __DIR__ . '/../classes/AboutContent.php';
require_once __DIR__ . '/../classes/Slider.php';
require_once __DIR__ . '/../classes/FileUpload.php';

header('Content-Type: application/json');

$user = new User();
if (!$user->isLoggedIn() || !$user->isAdmin()) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Akses i refuzuar.']);
    exit;
}

$currentUser = $user->getCurrentUser();
$action = $_GET['action'] ?? $_POST['action'] ?? '';
$type = $_GET['type'] ?? $_POST['type'] ?? '';

switch ($action) {
    case 'list':
        switch ($type) {
            case 'products':
                $product = new Product();
                $items = $product->getAll();
                echo json_encode(['success' => true, 'data' => $items]);
                break;
            case 'news':
                $news = new News();
                $items = $news->getAll();
                echo json_encode(['success' => true, 'data' => $items]);
                break;
            case 'about':
                $about = new AboutContent();
                $items = $about->getAll();
                echo json_encode(['success' => true, 'data' => $items]);
                break;
            case 'slider':
                $slider = new Slider();
                $items = $slider->getAll(false);
                echo json_encode(['success' => true, 'data' => $items]);
                break;
            default:
                echo json_encode(['success' => false, 'message' => 'Lloj i pavlefshëm.']);
        }
        break;

    case 'get':
        $id = (int)($_GET['id'] ?? $_POST['id'] ?? 0);
        switch ($type) {
            case 'products':
                $product = new Product();
                $item = $product->getById($id);
                echo json_encode(['success' => true, 'data' => $item]);
                break;
            case 'news':
                $news = new News();
                $item = $news->getById($id);
                echo json_encode(['success' => true, 'data' => $item]);
                break;
            default:
                echo json_encode(['success' => false, 'message' => 'Lloj i pavlefshëm.']);
        }
        break;

    case 'save':
        switch ($type) {
            case 'products':
                $product = new Product();
                $id = (int)($_POST['id'] ?? 0);
                $data = [
                    'name' => trim($_POST['name'] ?? ''),
                    'description' => trim($_POST['description'] ?? ''),
                    'price_per_day' => (float)($_POST['price_per_day'] ?? 0),
                    'category' => trim($_POST['category'] ?? ''),
                    'transmission' => trim($_POST['transmission'] ?? ''),
                    'image_path' => null,
                    'pdf_path' => null
                ];

                // Handle file uploads
                if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                    $upload = FileUpload::uploadImage($_FILES['image'], 'products');
                    if ($upload['success']) {
                        $data['image_path'] = $upload['url'];
                    }
                } elseif ($id) {
                    // Keep existing image if not uploading new one
                    $existing = $product->getById($id);
                    $data['image_path'] = $existing['image_path'] ?? null;
                }

                if (isset($_FILES['pdf']) && $_FILES['pdf']['error'] === UPLOAD_ERR_OK) {
                    $upload = FileUpload::uploadPDF($_FILES['pdf'], 'products');
                    if ($upload['success']) {
                        $data['pdf_path'] = $upload['url'];
                    }
                } elseif ($id) {
                    $existing = $product->getById($id);
                    $data['pdf_path'] = $existing['pdf_path'] ?? null;
                }

                if ($id) {
                    $result = $product->update($id, $data, $currentUser['id']);
                } else {
                    $result = $product->create($data, $currentUser['id']);
                }
                echo json_encode(['success' => $result, 'message' => $result ? 'U ruajt me sukses!' : 'Gabim në ruajtje.']);
                break;

            case 'news':
                $news = new News();
                $id = (int)($_POST['id'] ?? 0);
                $data = [
                    'title' => trim($_POST['title'] ?? ''),
                    'content' => trim($_POST['content'] ?? ''),
                    'image_path' => null,
                    'pdf_path' => null
                ];

                if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                    $upload = FileUpload::uploadImage($_FILES['image'], 'news');
                    if ($upload['success']) {
                        $data['image_path'] = $upload['url'];
                    }
                } elseif ($id) {
                    $existing = $news->getById($id);
                    $data['image_path'] = $existing['image_path'] ?? null;
                }

                if (isset($_FILES['pdf']) && $_FILES['pdf']['error'] === UPLOAD_ERR_OK) {
                    $upload = FileUpload::uploadPDF($_FILES['pdf'], 'news');
                    if ($upload['success']) {
                        $data['pdf_path'] = $upload['url'];
                    }
                } elseif ($id) {
                    $existing = $news->getById($id);
                    $data['pdf_path'] = $existing['pdf_path'] ?? null;
                }

                if ($id) {
                    $result = $news->update($id, $data, $currentUser['id']);
                } else {
                    $result = $news->create($data, $currentUser['id']);
                }
                echo json_encode(['success' => $result, 'message' => $result ? 'U ruajt me sukses!' : 'Gabim në ruajtje.']);
                break;

            default:
                echo json_encode(['success' => false, 'message' => 'Lloj i pavlefshëm.']);
        }
        break;

    case 'delete':
        $id = (int)($_POST['id'] ?? 0);
        switch ($type) {
            case 'products':
                $product = new Product();
                $result = $product->delete($id);
                echo json_encode(['success' => $result, 'message' => $result ? 'U fshi me sukses!' : 'Gabim.']);
                break;
            case 'news':
                $news = new News();
                $result = $news->delete($id);
                echo json_encode(['success' => $result, 'message' => $result ? 'U fshi me sukses!' : 'Gabim.']);
                break;
            case 'about':
                $about = new AboutContent();
                $result = $about->delete($id);
                echo json_encode(['success' => $result, 'message' => $result ? 'U fshi me sukses!' : 'Gabim.']);
                break;
            case 'slider':
                $slider = new Slider();
                $result = $slider->delete($id);
                echo json_encode(['success' => $result, 'message' => $result ? 'U fshi me sukses!' : 'Gabim.']);
                break;
            default:
                echo json_encode(['success' => false, 'message' => 'Lloj i pavlefshëm.']);
        }
        break;

    default:
        echo json_encode(['success' => false, 'message' => 'Aksion i pavlefshëm.']);
}

Aksionet 
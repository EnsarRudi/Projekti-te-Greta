<?php
/**
 * Test file për të kontrolluar çfarë po kthen API
 */
error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: application/json');

try {
    require_once __DIR__ . '/../config.php';
    echo json_encode(['success' => true, 'message' => 'Config u ngarkua me sukses']);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Gabim në config: ' . $e->getMessage()]);
}


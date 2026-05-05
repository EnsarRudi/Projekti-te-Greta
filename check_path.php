<?php
/**
 * File për të kontrolluar path-et
 */
echo "<h2>Path Information</h2>";
echo "<p><strong>Document Root:</strong> " . $_SERVER['DOCUMENT_ROOT'] . "</p>";
echo "<p><strong>Script Name:</strong> " . $_SERVER['SCRIPT_NAME'] . "</p>";
echo "<p><strong>Request URI:</strong> " . $_SERVER['REQUEST_URI'] . "</p>";
echo "<p><strong>Current Directory:</strong> " . __DIR__ . "</p>";
echo "<p><strong>API Path should be:</strong> " . __DIR__ . "/api/auth.php</p>";

echo "<h3>Test API Paths:</h3>";
echo "<ul>";
echo "<li><a href='api/auth.php'>api/auth.php (relative)</a></li>";
echo "<li><a href='/Projekti%20te%20Greta/api/auth.php'>/Projekti%20te%20Greta/api/auth.php (absolute)</a></li>";
echo "<li><a href='./api/auth.php'>./api/auth.php (explicit relative)</a></li>";
echo "</ul>";

echo "<h3>File Exists Check:</h3>";
$apiPath = __DIR__ . '/api/auth.php';
echo "<p>api/auth.php exists: " . (file_exists($apiPath) ? "YES ✓" : "NO ✗") . "</p>";
echo "<p>Full path: " . $apiPath . "</p>";
?>

API Paths
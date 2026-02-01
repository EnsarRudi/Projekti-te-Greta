<?php
/**
 * Skript instalimi për NovaDrive
 * Ky skript krijon databazën dhe tabelat automatikisht
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Konfigurimi i databazës
$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'novadrive';

?>
<!DOCTYPE html>
<html lang="sq">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Instalim - NovaDrive</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background: #f5f5f5;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 { color: #0f62fe; }
        .success { color: green; padding: 10px; background: #d4edda; border-radius: 5px; margin: 10px 0; }
        .error { color: red; padding: 10px; background: #f8d7da; border-radius: 5px; margin: 10px 0; }
        .info { color: #0c5460; padding: 10px; background: #d1ecf1; border-radius: 5px; margin: 10px 0; }
        button {
            background: #0f62fe;
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 20px;
        }
        button:hover { background: #0a3fa6; }
        code { background: #f4f4f4; padding: 2px 6px; border-radius: 3px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🚗 Instalim NovaDrive</h1>
        
        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['install'])) {
            echo "<h2>Duke instaluar...</h2>";
            
            try {
                // Lidhje pa databazë për të krijuar databazën
                $conn = new PDO("mysql:host=$db_host;charset=utf8mb4", $db_user, $db_pass);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                
                // Krijo databazën
                $conn->exec("CREATE DATABASE IF NOT EXISTS `$db_name` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
                echo "<div class='success'>✓ Databaza '$db_name' u krijua me sukses!</div>";
                
                // Përdor databazën
                $conn->exec("USE `$db_name`");
                
                // Lexo dhe ekzekuto SQL
                $sql = file_get_contents(__DIR__ . '/database.sql');
                
                // Ndaj në query të veçanta
                $queries = array_filter(array_map('trim', explode(';', $sql)));
                
                foreach ($queries as $query) {
                    if (!empty($query) && !preg_match('/^(CREATE DATABASE|USE)/i', $query)) {
                        try {
                            $conn->exec($query);
                        } catch (PDOException $e) {
                            // Ignoro gabimet për tabela që ekzistojnë tashmë
                            if (strpos($e->getMessage(), 'already exists') === false) {
                                echo "<div class='error'>⚠ Gabim: " . $e->getMessage() . "</div>";
                            }
                        }
                    }
                }
                
                echo "<div class='success'>✓ Tabelat u krijuan me sukses!</div>";
                echo "<div class='success'>✓ Të dhënat fillestare u shtuan!</div>";
                
                // Testo lidhjen
                require_once __DIR__ . '/config.php';
                require_once __DIR__ . '/classes/Database.php';
                
                $db = new Database();
                $testConn = $db->getConnection();
                $stmt = $testConn->query("SELECT COUNT(*) as count FROM users");
                $result = $stmt->fetch();
                
                echo "<div class='success'>✓ Lidhja me databazën u testua me sukses!</div>";
                echo "<div class='info'>📊 Numri i përdoruesve: " . $result['count'] . "</div>";
                
                echo "<h2>✅ Instalimi u përfundua me sukses!</h2>";
                echo "<div class='info'>";
                echo "<strong>Kredencialet e administratorit:</strong><br>";
                echo "Email: <code>admin@novadrive.com</code><br>";
                echo "Password: <code>admin123</code><br><br>";
                echo "<a href='index.php'><button>Shko te Faqja Kryesore</button></a>";
                echo "</div>";
                
            } catch (PDOException $e) {
                echo "<div class='error'>❌ Gabim: " . $e->getMessage() . "</div>";
                echo "<div class='info'>";
                echo "<strong>Kontrollo:</strong><br>";
                echo "1. MySQL server është duke punuar?<br>";
                echo "2. Username dhe password janë të sakta?<br>";
                echo "3. Ndrysho kredencialet në <code>install.php</code> nëse është e nevojshme.";
                echo "</div>";
            }
        } else {
            ?>
            <div class="info">
                <p>Ky skript do të:</p>
                <ul>
                    <li>Krijojë databazën <code>novadrive</code></li>
                    <li>Krijojë të gjitha tabelat e nevojshme</li>
                    <li>Shtojë të dhënat fillestare (admin user, produkte, lajme, etj.)</li>
                </ul>
            </div>
            
            <div class="info">
                <strong>Kredencialet aktuale të databazës:</strong><br>
                Host: <code><?php echo $db_host; ?></code><br>
                User: <code><?php echo $db_user; ?></code><br>
                Password: <code><?php echo $db_pass ?: '(bosh)'; ?></code><br>
                Database: <code><?php echo $db_name; ?></code>
            </div>
            
            <p><strong>Nëse kredencialet nuk janë të sakta, ndrysho ato në fillim të file-it <code>install.php</code></strong></p>
            
            <form method="POST">
                <button type="submit" name="install">🚀 Fillo Instalimin</button>
            </form>
            <?php
        }
        ?>
    </div>
</body>
</html>


<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../classes/User.php';

$user = new User();
$currentUser = $user->getCurrentUser();
$currentPage = basename($_SERVER['PHP_SELF'], '.php');
?>
<!doctype html>
<html lang="sq">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>NovaDrive | <?php echo ucfirst($currentPage === 'index' ? 'Home' : $currentPage); ?></title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <header>
    <nav class="nav">
      <a class="brand" href="index.php"><span>NovaDrive</span> Rent a Car</a>
      <div class="nav-links">
        <a href="index.php" class="<?php echo $currentPage === 'index' ? 'active' : ''; ?>">Home</a>
        <a href="products.php" class="<?php echo $currentPage === 'products' ? 'active' : ''; ?>">Products</a>
        <a href="news.php" class="<?php echo $currentPage === 'news' ? 'active' : ''; ?>">News</a>
        <a href="about.php" class="<?php echo $currentPage === 'about' ? 'active' : ''; ?>">About</a>
        <a href="contact.php" class="<?php echo $currentPage === 'contact' ? 'active' : ''; ?>">Contact</a>
        <?php if ($user->isAdmin()): ?>
          <a href="admin/dashboard.php" class="<?php echo $currentPage === 'dashboard' ? 'active' : ''; ?>">Dashboard</a>
        <?php endif; ?>
      </div>
      <div class="nav-links">
        <?php if ($currentUser): ?>
          <span id="authWelcome" class="pill">Mirë se erdhe, <?php echo htmlspecialchars($currentUser['name']); ?>!</span>
          <button class="cta outline" id="logoutBtn">Logout</button>
        <?php else: ?>
          <button class="cta outline" id="loginBtn">Login</button>
          <button class="cta" id="signupBtn">Signup</button>
        <?php endif; ?>
      </div>
    </nav>
  </header>
  <!-- avoiding problems -->


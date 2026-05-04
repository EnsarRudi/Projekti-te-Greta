<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../classes/User.php';
require_once __DIR__ . '/../classes/Product.php';
require_once __DIR__ . '/../classes/News.php';
require_once __DIR__ . '/../classes/Contact.php';
require_once __DIR__ . '/../classes/AboutContent.php';
require_once __DIR__ . '/../classes/Slider.php';

$user = new User();
if (!$user->isLoggedIn() || !$user->isAdmin()) {
    header('Location: ../index.php');
    exit;
}

$product = new Product();
$news = new News();
$contact = new Contact();
$aboutContent = new AboutContent();
$slider = new Slider();

$stats = [
    'products' => count($product->getAll()),
    'news' => count($news->getAll()),
    'unread_messages' => $contact->getUnreadCount(),
    'total_messages' => count($contact->getAll())
];
?>
<!doctype html>
<html lang="sq">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>NovaDrive | Admin Dashboard</title>
  <link rel="stylesheet" href="../style.css">
  <style>
    .dashboard { max-width: 1400px; margin: 0 auto; padding: 32px 24px; }
    .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 18px; margin-bottom: 32px; }
    .stat-card { background: var(--card); padding: 24px; border-radius: 18px; border: 1px solid rgba(255,255,255,0.06); }
    .stat-card h3 { margin: 0 0 8px; font-size: 14px; color: var(--muted); }
    .stat-card .number { font-size: 32px; font-weight: 700; color: var(--primary); }
    .tabs { display: flex; gap: 12px; margin-bottom: 24px; flex-wrap: wrap; }
    .tab { padding: 12px 20px; background: var(--card); border: 1px solid rgba(255,255,255,0.06); border-radius: 12px; cursor: pointer; transition: all 0.2s; }
    .tab.active { background: var(--primary); border-color: var(--primary); }
    .tab-content { display: none; }
    .tab-content.active { display: block; }
    .admin-table { width: 100%; border-collapse: collapse; background: var(--card); border-radius: 12px; overflow: hidden; }
    .admin-table th, .admin-table td { padding: 12px; text-align: left; border-bottom: 1px solid rgba(255,255,255,0.06); }
    .admin-table th { background: rgba(15,98,254,0.1); font-weight: 600; }
    .btn-small { padding: 6px 12px; font-size: 13px; }
    .form-modal { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.7); z-index: 100; align-items: center; justify-content: center; padding: 16px; }
    .form-modal.active { display: flex; }
    .form-modal-content { background: var(--card); padding: 24px; border-radius: 16px; max-width: 600px; width: 100%; max-height: 90vh; overflow-y: auto; }
  </style>
</head>
<body>
  <header>
    <nav class="nav">
      <a class="brand" href="../index.php"><span>NovaDrive</span> Admin</a>
      <div class="nav-links">
        <a href="../index.php">Faqja Kryesore</a>
        <a href="dashboard.php" class="active">Dashboard</a>
        <a href="../api/auth.php?action=logout" onclick="event.preventDefault(); logout();">Logout</a>
      </div>
    </nav>
  </header>

  <main class="dashboard">
    <h1>Dashboard Administratori</h1>
    
    <div class="stats-grid">
      <div class="stat-card">
        <h3>Produkte</h3>
        <div class="number"><?php echo $stats['products']; ?></div>
      </div>
      <div class="stat-card">
        <h3>Lajme</h3>
        <div class="number"><?php echo $stats['news']; ?></div>
      </div>
      <div class="stat-card">
        <h3>Mesazhe të palexuara</h3>
        <div class="number"><?php echo $stats['unread_messages']; ?></div>
      </div>
      <div class="stat-card">
        <h3>Total Mesazhe</h3>
        <div class="number"><?php echo $stats['total_messages']; ?></div>
      </div>
    </div>

    <div class="tabs">
      <div class="tab active" data-tab="products">Produkte</div>
      <div class="tab" data-tab="news">Lajme</div>
      <div class="tab" data-tab="messages">Mesazhe</div>
      <div class="tab" data-tab="about">About Content</div>
      <div class="tab" data-tab="slider">Slider</div>
    </div>

    <!-- Products Tab -->
    <div class="tab-content active" id="products">
      <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:18px;">
        <h2>Menaxho Produktet</h2>
        <button class="cta" onclick="openProductForm()">Shto Produkt</button>
      </div>
      <div id="productsList">
        <p>Duke ngarkuar...</p>
      </div>
    </div>

    <!-- News Tab -->
    <div class="tab-content" id="news">
      <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:18px;">
        <h2>Menaxho Lajmet</h2>
        <button class="cta" onclick="openNewsForm()">Shto Lajm</button>
      </div>
      <div id="newsList">
        <p>Duke ngarkuar...</p>
      </div>
    </div>

    <!-- Messages Tab -->
    <div class="tab-content" id="messages">
      <h2>Mesazhet e Kontaktit</h2>
      <div id="messagesList">
        <p>Duke ngarkuar...</p>
      </div>
    </div>

    <!-- About Content Tab -->
    <div class="tab-content" id="about">
      <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:18px;">
        <h2>Menaxho About Content</h2>
        <button class="cta" onclick="openAboutForm()">Shto Seksion</button>
      </div>
      <div id="aboutList">
        <p>Duke ngarkuar...</p>
      </div>
    </div>

    <!-- Slider Tab -->
    <div class="tab-content" id="slider">
      <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:18px;">
        <h2>Menaxho Slider</h2>
        <button class="cta" onclick="openSliderForm()">Shto Slide</button>
      </div>
      <div id="sliderList">
        <p>Duke ngarkuar...</p>
      </div>
    </div>
  </main>

  <!-- Product Form Modal -->
  <div class="form-modal" id="productModal">
    <div class="form-modal-content">
      <h3 id="productModalTitle">Shto Produkt</h3>
      <form id="productForm" enctype="multipart/form-data">
        <input type="hidden" name="id" id="productId">
        <label>Emri</label>
        <input name="name" id="productName" required>
        <label>Përshkrimi</label>
        <textarea name="description" id="productDescription" rows="3"></textarea>
        <label>Çmimi për ditë (€)</label>
        <input type="number" step="0.01" name="price_per_day" id="productPrice" required>
        <label>Kategoria</label>
        <input name="category" id="productCategory">
        <label>Transmission</label>
        <select name="transmission" id="productTransmission">
          <option value="Automatike">Automatike</option>
          <option value="Manuale">Manuale</option>
        </select>
        <label>Imazh</label>
        <input type="file" name="image" accept="image/*">
        <label>PDF (opsionale)</label>
        <input type="file" name="pdf" accept="application/pdf">
        <div style="display:flex; gap:12px; margin-top:18px;">
          <button type="submit" class="cta">Ruaj</button>
          <button type="button" class="cta outline" onclick="closeModal('productModal')">Anulo</button>
        </div>
      </form>
    </div>
  </div>

  <!-- News Form Modal -->
  <div class="form-modal" id="newsModal">
    <div class="form-modal-content">
      <h3 id="newsModalTitle">Shto Lajm</h3>
      <form id="newsForm" enctype="multipart/form-data">
        <input type="hidden" name="id" id="newsId">
        <label>Titulli</label>
        <input name="title" id="newsTitle" required>
        <label>Përmbajtja</label>
        <textarea name="content" id="newsContent" rows="6" required></textarea>
        <label>Imazh</label>
        <input type="file" name="image" accept="image/*">
        <label>PDF (opsionale)</label>
        <input type="file" name="pdf" accept="application/pdf">
        <div style="display:flex; gap:12px; margin-top:18px;">
          <button type="submit" class="cta">Ruaj</button>
          <button type="button" class="cta outline" onclick="closeModal('newsModal')">Anulo</button>
        </div>
      </form>
    </div>
  </div>

  <script src="../script.js"></script>
  <script src="admin.js"></script>
</body>
</html>
<!-- update -->

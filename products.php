<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/classes/Product.php';

$product = new Product();
$products = $product->getAll();
?>
<?php include 'includes/header.php'; ?>

<main>
  <div class="section-title">
    <h2>Zgjidh makinën</h2>
    <div class="badges">
      <span class="badge">Rotacion 3D</span>
      <span class="badge">Ngjyra & kosto</span>
    </div>
  </div>

  <?php if (!empty($products)): ?>
  <div class="card carousel">
    <div class="carousel-track">
      <?php foreach ($products as $prod): ?>
      <article class="card">
        <span class="pill"><?php echo htmlspecialchars($prod['category'] ?? 'N/A'); ?> • <?php echo htmlspecialchars($prod['transmission'] ?? 'N/A'); ?></span>
        <?php if ($prod['image_path']): ?>
        <img class="car-img" src="<?php echo htmlspecialchars($prod['image_path']); ?>" alt="<?php echo htmlspecialchars($prod['name']); ?>">
        <?php endif; ?>
        <h3><?php echo htmlspecialchars($prod['name']); ?></h3>
        <p class="price">€<?php echo number_format($prod['price_per_day'], 2); ?>/ditë</p>
        <p class="muted"><?php echo htmlspecialchars($prod['description'] ?? ''); ?></p>
        <?php if ($prod['pdf_path']): ?>
        <a href="<?php echo htmlspecialchars($prod['pdf_path']); ?>" target="_blank" class="cta outline" style="margin-top:12px; display:inline-flex;">Shiko PDF</a>
        <?php endif; ?>
      </article>
      <?php endforeach; ?>
    </div>
    <div class="carousel-controls">
      <button class="control" id="prevCar">‹</button>
      <button class="control" id="nextCar">›</button>
    </div>
  </div>
  <?php else: ?>
  <div class="card">
    <p class="muted">Nuk ka produkte në dispozicion.</p>
  </div>
  <?php endif; ?>

  <div class="section-title">
    <h2>Kalkulo çmimin</h2>
  </div>
  <div class="card">
    <form class="two-col" id="priceCalculator">
      <div>
        <label>Modeli</label>
        <select id="carSelect" name="car">
          <option value="" data-price="0">Zgjidh makinën</option>
          <?php foreach ($products as $prod): ?>
          <option data-price="<?php echo $prod['price_per_day']; ?>" value="<?php echo htmlspecialchars($prod['name']); ?>">
            <?php echo htmlspecialchars($prod['name']); ?> — €<?php echo number_format($prod['price_per_day'], 2); ?>/ditë
          </option>
          <?php endforeach; ?>
        </select>
      </div>
      <div>
        <label>Ditet</label>
        <input id="rentalDays" type="number" value="4" min="1">
      </div>
    </form>
    <div class="status" id="pricePreview">Totali: €0.00</div>
    <a class="cta" href="contact.php" style="margin-top:12px; display:inline-flex;">Kontakto për rezervim</a>
  </div>
</main>

<?php include 'includes/footer.php'; ?>

// products update
<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/classes/Product.php';
require_once __DIR__ . '/classes/Slider.php';
require_once __DIR__ . '/classes/AboutContent.php';

$product = new Product();
$slider = new Slider();
$aboutContent = new AboutContent();

$products = $product->getAll(3);
$sliderItems = $slider->getAll(true);
$features = $aboutContent->getAll();
?>
<?php include 'includes/header.php'; ?>

<main>
  <!-- Slider Section -->
  <?php if (!empty($sliderItems)): ?>
  <section class="hero-slider">
    <div class="slider-container">
      <div class="slider-track" id="sliderTrack">
        <?php foreach ($sliderItems as $item): ?>
        <div class="slider-slide">
          <img src="<?php echo htmlspecialchars($item['image_path']); ?>" alt="<?php echo htmlspecialchars($item['title']); ?>">
          <div class="slider-content">
            <p class="pill">Premium, i shpejtë, 24/7</p>
            <h1><?php echo htmlspecialchars($item['title']); ?></h1>
            <?php if ($item['description']): ?>
            <p><?php echo htmlspecialchars($item['description']); ?></p>
            <?php endif; ?>
            <div class="badges">
              <span class="badge">Flotë 2023-2024</span>
              <span class="badge">Sigurim i plotë</span>
              <span class="badge">Dorëzim në aeroport</span>
            </div>
            <div style="display:flex; gap:12px; flex-wrap:wrap; margin-top:20px;">
              <a class="cta" href="products.php">Rezervo tani</a>
              <a class="cta outline" href="products.php">Shfleto flotën</a>
            </div>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
      <div class="slider-controls">
        <button class="control" id="prevSlide">‹</button>
        <button class="control" id="nextSlide">›</button>
      </div>
      <div class="slider-dots">
        <?php foreach ($sliderItems as $index => $item): ?>
        <span class="dot <?php echo $index === 0 ? 'active' : ''; ?>" data-slide="<?php echo $index; ?>"></span>
        <?php endforeach; ?>
      </div>
    </div>
  </section>
  <?php else: ?>
  <!-- Fallback hero if no slider items -->
  <section class="hero">
    <div>
      <p class="pill">Premium, i shpejtë, 24/7</p>
      <h1>Gati për udhëtimin tënd të radhës? Merr makinën në pak minuta.</h1>
      <p>Fleksibilitet maksimal, çmime transparente dhe asistencë rrugore kudo në vend. Rezervo online dhe nise aventurën.</p>
      <div class="badges">
        <span class="badge">Flotë 2023-2024</span>
        <span class="badge">Sigurim i plotë</span>
        <span class="badge">Dorëzim në aeroport</span>
      </div>
      <div style="display:flex; gap:12px; flex-wrap:wrap;">
        <a class="cta" href="products.php">Rezervo tani</a>
        <a class="cta outline" href="products.php">Shfleto flotën</a>
      </div>
    </div>
    <div class="hero-visual">
      <img src="IMG/8a9ea33b51a84cd72c0bf814a740a13b.jpg" alt="Range Rover Evoque">
      <div class="floating-card">
        <strong>72% më shpejt</strong>
        <p style="margin:6px 0 0;">Proces i plotë online, dokumentacion i thjeshtë.</p>
      </div>
    </div>
  </section>
  <?php endif; ?>

  <div class="section-title">
    <h2>Flota Premium</h2>
    <a class="cta outline" href="products.php">Shiko të gjitha</a>
  </div>
  <div class="grid">
    <?php if (empty($products)): ?>
      <p class="muted">Nuk ka produkte në dispozicion.</p>
    <?php else: ?>
      <?php foreach ($products as $prod): ?>
      <article class="card">
        <span class="pill"><?php echo htmlspecialchars($prod['category'] ?? 'N/A'); ?> • <?php echo htmlspecialchars($prod['transmission'] ?? 'N/A'); ?></span>
        <?php if ($prod['image_path']): ?>
        <img class="car-img" src="<?php echo htmlspecialchars($prod['image_path']); ?>" alt="<?php echo htmlspecialchars($prod['name']); ?>">
        <?php endif; ?>
        <h3><?php echo htmlspecialchars($prod['name']); ?></h3>
        <p class="price">€<?php echo number_format($prod['price_per_day'], 2); ?>/ditë</p>
        <p class="muted"><?php echo htmlspecialchars($prod['description'] ?? ''); ?></p>
      </article>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>

  <div class="section-title">
    <h2>Pse të zgjedhësh NovaDrive</h2>
  </div>
  <div class="grid">
    <?php if (empty($features)): ?>
      <div class="card">
        <h3>Proces i thjeshtë</h3>
        <p>Rezervim online, verifikim dokumentesh dhe pagesë e sigurt brenda pak minutash.</p>
      </div>
      <div class="card">
        <h3>Mbështetje 24/7</h3>
        <p>Asistencë rrugore dhe suport klienti në çdo qytet ku operojmë.</p>
      </div>
      <div class="card">
        <h3>Çmime transparente</h3>
        <p>Pa tarifa të fshehura. Shfaqim qartë sigurimin, kilometrat dhe depozitën.</p>
      </div>
    <?php else: ?>
      <?php foreach ($features as $feature): ?>
      <div class="card">
        <h3><?php echo htmlspecialchars($feature['section_title']); ?></h3>
        <p><?php echo htmlspecialchars($feature['content']); ?></p>
      </div>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>
</main>

<?php include 'includes/footer.php'; ?>
<!-- homepage update -->
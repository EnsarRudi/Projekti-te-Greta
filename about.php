<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/classes/AboutContent.php';

$aboutContent = new AboutContent();
$sections = $aboutContent->getAll();
?>
<?php include 'includes/header.php'; ?>

<main>
  <section class="hero">
    <div>
      <p class="pill">Rreth nesh</p>
      <h1>NovaDrive, partneri yt i udhëtimeve.</h1>
      <p>Nisi si një ekip i vogël pasionantësh të makinave. Sot ofrojmë flotën më të re në rajon dhe procesin më të shpejtë të marrjes në dorëzim.</p>
    </div>
    <div class="floating-card" style="position:relative; background: rgba(0,0,0,0.45);">
      <strong>Statistika të shpejta</strong>
      <p>2500+ rezervime vjetore</p>
      <p>95% kënaqësi klienti</p>
      <p>12 minuta mesatarisht nga rezervimi në dorëzim</p>
    </div>
  </section>

  <div class="section-title">
    <h2>Çfarë na bën ndryshe</h2>
  </div>
  <div class="grid">
    <?php if (empty($sections)): ?>
      <div class="card">
        <h3>Teknologji</h3>
        <p>Proces i digjitalizuar: firmos online, track-on-time dhe verifikim dokumentesh në sekonda.</p>
      </div>
      <div class="card">
        <h3>Siguri</h3>
        <p>Sigurim i plotë, asistencë rrugore dhe inspektime periodike të çdo mjeti.</p>
      </div>
      <div class="card">
        <h3>Fleksibilitet</h3>
        <p>Paketa ditore, javore ose mujore. Mundësi ndërrimi modeli gjatë kontratës.</p>
      </div>
    <?php else: ?>
      <?php foreach ($sections as $section): ?>
      <div class="card">
        <?php if ($section['image_path']): ?>
        <img class="car-img" src="<?php echo htmlspecialchars($section['image_path']); ?>" alt="<?php echo htmlspecialchars($section['section_title']); ?>">
        <?php endif; ?>
        <h3><?php echo htmlspecialchars($section['section_title']); ?></h3>
        <p><?php echo htmlspecialchars($section['content']); ?></p>
      </div>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>
</main>

<?php include 'includes/footer.php'; ?>
PHP  

<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/classes/News.php';

$news = new News();
$newsItems = $news->getAll();
?>
<?php include 'includes/header.php'; ?>

<main>
  <div class="section-title">
    <h2>Lajmet dhe Njoftimet</h2>
  </div>

  <?php if (empty($newsItems)): ?>
  <div class="card">
    <p class="muted">Nuk ka lajme në dispozicion.</p>
  </div>
  <?php else: ?>
  <div class="grid">
    <?php foreach ($newsItems as $item): ?>
    <article class="card">
      <?php if ($item['image_path']): ?>
      <img class="car-img" src="<?php echo htmlspecialchars($item['image_path']); ?>" alt="<?php echo htmlspecialchars($item['title']); ?>">
      <?php endif; ?>
      <h3><?php echo htmlspecialchars($item['title']); ?></h3>
      <p class="muted" style="font-size:12px; margin-bottom:12px;">
        Publikuar: <?php echo date('d.m.Y', strtotime($item['created_at'])); ?>
        <?php if ($item['created_by_name']): ?>
        | Nga: <?php echo htmlspecialchars($item['created_by_name']); ?>
        <?php endif; ?>
      </p>
      <p><?php echo nl2br(htmlspecialchars(substr($item['content'], 0, 200))); ?><?php echo strlen($item['content']) > 200 ? '...' : ''; ?></p>
      <?php if ($item['pdf_path']): ?>
      <a href="<?php echo htmlspecialchars($item['pdf_path']); ?>" target="_blank" class="cta outline" style="margin-top:12px; display:inline-flex;">Shiko PDF</a>
      <?php endif; ?>
    </article>
    <?php endforeach; ?>
  </div>
  <?php endif; ?>
</main>

<?php include 'includes/footer.php'; ?>

Newest News
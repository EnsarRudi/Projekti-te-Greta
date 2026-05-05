<?php
require_once __DIR__ . '/config.php';
?>
<?php include 'includes/header.php'; ?>

<main>
  <div class="section-title">
    <h2>Na kontakto</h2>
    <span class="pill">Përgjigje brenda 10 minutash</span>
  </div>

  <div class="grid" style="grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));">
    <div class="card">
      <form id="contactForm" method="POST">
        <label for="contactName">Emri</label>
        <input id="contactName" name="name" placeholder="Emri Mbiemri" required>
        <label for="contactEmail">Email</label>
        <input id="contactEmail" name="email" type="email" placeholder="you@example.com" required>
        <label for="contactMessage">Mesazhi</label>
        <textarea id="contactMessage" name="message" rows="4" placeholder="Na tregoni se çfarë ju duhet" required></textarea>
        <div id="contactStatus" class="status" style="display:none;"></div>
        <button class="cta" type="submit">Dërgo</button>
      </form>
    </div>
    <div class="card">
      <h3>Qendra e suportit</h3>
      <p>Telefon: +383 44 123 456</p>
      <p>Email: support@novadrive.com</p>
      <p>Orari: 24/7</p>
      <div class="card" style="margin-top:12px;">
        <h4>Lokacionet kryesore</h4>
        <p>Prishtinë • Tiranë • Shkup</p>
        <p>Aeroport • Qendër qyteti • Dorëzim në adresë</p>
      </div>
    </div>
  </div>
</main>

<?php include 'includes/footer.php'; ?>

Kontakti
  <!-- Login Modal -->
  <div class="modal" id="loginModal">
    <div class="modal-content">
      <div class="modal-header">
        <h3>Hyrje</h3>
        <button class="close" data-close>&times;</button>
      </div>
      <form id="loginForm">
        <label for="loginEmail">Email</label>
        <input id="loginEmail" name="email" type="email" placeholder="you@example.com" required>
        <label for="loginPassword">Fjalëkalimi</label>
        <input id="loginPassword" name="password" type="password" placeholder="••••••••" required>
        <div id="loginStatus" class="status" aria-live="polite"></div>
        <button class="cta" type="submit">Login</button>
      </form>
    </div>
  </div>

  <!-- Signup Modal -->
  <div class="modal" id="signupModal">
    <div class="modal-content">
      <div class="modal-header">
        <h3>Krijo llogari</h3>
        <button class="close" data-close>&times;</button>
      </div>
      <form id="signupForm">
        <label for="signupName">Emri i plotë</label>
        <input id="signupName" name="name" placeholder="Emri Mbiemri" required>
        <label for="signupEmail">Email</label>
        <input id="signupEmail" name="email" type="email" placeholder="you@example.com" required>
        <label for="signupPassword">Fjalëkalimi</label>
        <input id="signupPassword" name="password" type="password" placeholder="Minimumi 8 karaktere" required minlength="8">
        <div id="signupStatus" class="status" aria-live="polite"></div>
        <button class="cta" type="submit">Regjistrohu</button>
      </form>
    </div>
  </div>

  <footer>© 2025 NovaDrive. Gati për çdo destinacion.</footer>

  <script src="script.js"></script>
</body>
</html>


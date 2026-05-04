document.addEventListener('DOMContentLoaded', () => {
  const loginModal = document.getElementById('loginModal');
  const signupModal = document.getElementById('signupModal');
  const loginBtn = document.getElementById('loginBtn');
  const signupBtn = document.getElementById('signupBtn');
  const logoutBtn = document.getElementById('logoutBtn');
  const closeButtons = document.querySelectorAll('[data-close]');
  const authWelcome = document.getElementById('authWelcome');

  function openModal(modal) {
    if (modal) modal.classList.add('active');
  }

  function closeModal(modal) {
    if (modal) modal.classList.remove('active');
  }

  closeButtons.forEach(btn =>
    btn.addEventListener('click', () => {
      closeModal(loginModal);
      closeModal(signupModal);
    })
  );

  if (loginBtn) loginBtn.addEventListener('click', () => openModal(loginModal));
  if (signupBtn) signupBtn.addEventListener('click', () => openModal(signupModal));
  if (logoutBtn)
    logoutBtn.addEventListener('click', () => {
      const basePath = window.location.pathname.substring(0, window.location.pathname.lastIndexOf('/'));
      fetch(basePath + '/api/auth.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: 'action=logout'
      })
      .then(r => r.json())
      .then(data => {
        if (data.success) {
          window.location.reload();
        }
      });
    });

  const loginForm = document.getElementById('loginForm');
  if (loginForm) {
    loginForm.addEventListener('submit', e => {
      e.preventDefault();
      const email = loginForm.email.value.trim();
      const password = loginForm.password.value.trim();
      const status = document.getElementById('loginStatus');
      
      // Front-end validation
      if (!email || !password) {
        status.textContent = 'Ju lutem plotësoni të gjitha fushat.';
        status.className = 'status error';
        return;
      }

      if (!email.includes('@')) {
        status.textContent = 'Email i pavlefshëm.';
        status.className = 'status error';
        return;
      }

      const basePath = window.location.pathname.substring(0, window.location.pathname.lastIndexOf('/'));
      fetch(basePath + '/api/auth.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: `action=login&email=${encodeURIComponent(email)}&password=${encodeURIComponent(password)}`
      })
      .then(r => r.json())
      .then(data => {
        status.textContent = data.message;
        status.className = data.success ? 'status success' : 'status error';
        if (data.success) {
          setTimeout(() => {
            closeModal(loginModal);
            window.location.reload();
          }, 1000);
        }
      })
      .catch(err => {
        status.textContent = 'Gabim në lidhje.';
        status.className = 'status error';
      });
    });
  }

  const signupForm = document.getElementById('signupForm');
  if (signupForm) {
    signupForm.addEventListener('submit', e => {
      e.preventDefault();
      const name = signupForm.name.value.trim();
      const email = signupForm.email.value.trim();
      const password = signupForm.password.value.trim();
      const status = document.getElementById('signupStatus');

      // Front-end validation
      if (!name || !email || !password) {
        status.textContent = 'Ju lutem plotësoni të gjitha fushat.';
        status.className = 'status error';
        return;
      }

      if (!email.includes('@')) {
        status.textContent = 'Email i pavlefshëm.';
        status.className = 'status error';
        return;
      }

      if (password.length < 8) {
        status.textContent = 'Fjalëkalimi duhet të jetë të paktën 8 karaktere.';
        status.className = 'status error';
        return;
      }

      // Kontrollo path-in e API-së - përdor path absolut bazuar në root
      const basePath = window.location.pathname.substring(0, window.location.pathname.lastIndexOf('/'));
      const apiPath = basePath + '/api/auth.php';
      
      console.log('Fetching from:', apiPath);
      
      fetch(apiPath, {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: `action=register&name=${encodeURIComponent(name)}&email=${encodeURIComponent(email)}&password=${encodeURIComponent(password)}`
      })
      .then(r => {
        // Kontrollo nëse response është JSON
        const contentType = r.headers.get('content-type');
        if (!contentType || !contentType.includes('application/json')) {
          return r.text().then(text => {
            throw new Error(`Serveri ktheu: ${text.substring(0, 200)}`);
          });
        }
        
        if (!r.ok) {
          throw new Error(`HTTP error! status: ${r.status}`);
        }
        return r.json();
      })
      .then(data => {
        status.textContent = data.message || 'Gabim i panjohur';
        status.className = data.success ? 'status success' : 'status error';
        if (data.success) {
          setTimeout(() => {
            closeModal(signupModal);
            openModal(loginModal);
          }, 1500);
        }
      })
      .catch(err => {
        console.error('Error details:', err);
        let errorMsg = 'Gabim në lidhje. ';
        
        if (err.message.includes('Failed to fetch') || err.message.includes('NetworkError')) {
          errorMsg += 'API nuk është e arritshme. Kontrollo që server-i PHP është duke punuar dhe path-i është i saktë.';
        } else if (err.message.includes('HTTP error')) {
          errorMsg += 'Gabim HTTP. Kontrollo që file-i api/auth.php ekziston.';
        } else {
          errorMsg += err.message;
        }
        
        status.textContent = errorMsg;
        status.className = 'status error';
      });
    });
  }

  // Rent/documentation form
  const rentForm = document.getElementById('rentForm');
  const rentSummary = document.getElementById('rentSummary');
  const daysInput = document.getElementById('rentalDays');
  const carSelect = document.getElementById('carSelect');
  const colorSelect = document.getElementById('colorSelect');

  function updatePricePreview() {
    if (!daysInput || !carSelect) return;
    const days = Number(daysInput.value || 0);
    const pricePerDay = Number(carSelect.selectedOptions[0]?.dataset.price || 0);
    const total = Math.max(0, days * pricePerDay);
    const preview = document.getElementById('pricePreview');
    if (preview) preview.textContent = total ? `Totali: €${total.toFixed(2)}` : '';
  }

  if (daysInput) daysInput.addEventListener('input', updatePricePreview);
  if (carSelect) carSelect.addEventListener('change', updatePricePreview);

  if (rentForm) {
    rentForm.addEventListener('submit', e => {
      e.preventDefault();
      const payload = {
        name: rentForm.fullName.value.trim(),
        license: rentForm.license.value.trim(),
        car: rentForm.car.value,
        color: rentForm.color.value,
        pickup: rentForm.pickup.value,
        dropoff: rentForm.dropoff.value,
        days: Number(rentForm.days.value || 0),
        price: Number(rentForm.car.selectedOptions[0]?.dataset.price || 0)
      };
      const total = payload.days * payload.price;
      if (rentSummary) {
        rentSummary.innerHTML = `
          <strong>Rezervimi u ruajt!</strong><br/>
          ${payload.name} do të marrë ${payload.car} (${payload.color}) për ${payload.days} ditë.<br/>
          Totali i parashikuar: €${total.toFixed(2)}.
        `;
        rentSummary.className = 'status success';
      }
      rentForm.reset();
      updatePricePreview();
    });
  }

  // Cars carousel + interactions
  const carousel = document.querySelector('.carousel');
  const track = document.querySelector('.carousel-track');
  const prevBtn = document.getElementById('prevCar');
  const nextBtn = document.getElementById('nextCar');
  let index = 0;

  function updateCarousel() {
    if (!track) return;
    const cards = Array.from(track.children);
    const visible = Math.max(1, Math.floor(track.clientWidth / 280));
    const maxIndex = Math.max(0, cards.length - visible);
    index = Math.min(Math.max(index, 0), maxIndex);
    const offset = index * (cards[0]?.clientWidth + 18 || 0);
    track.style.transform = `translateX(-${offset}px) rotateY(${index * 2}deg)`;
  }

  if (prevBtn)
    prevBtn.addEventListener('click', () => {
      index -= 1;
      updateCarousel();
    });
  if (nextBtn)
    nextBtn.addEventListener('click', () => {
      index += 1;
      updateCarousel();
    });

  window.addEventListener('resize', updateCarousel);
  updateCarousel();

  // Color selection per car card
  document.querySelectorAll('.colors').forEach(group => {
    group.addEventListener('click', e => {
      const dot = e.target.closest('.color-dot');
      if (!dot) return;
      group.querySelectorAll('.color-dot').forEach(d => d.classList.remove('active'));
      dot.classList.add('active');
      const img = group.closest('.card')?.querySelector('.car-img');
      if (img) {
        img.style.boxShadow = `0 0 0 4px ${dot.dataset.color}55`;
      }
    });
  });

  updatePricePreview();

  // Slider functionality
  const sliderTrack = document.getElementById('sliderTrack');
  const prevSlide = document.getElementById('prevSlide');
  const nextSlide = document.getElementById('nextSlide');
  const dots = document.querySelectorAll('.dot');
  let currentSlide = 0;

  if (sliderTrack && sliderTrack.children.length > 0) {
    const slides = Array.from(sliderTrack.children);
    const totalSlides = slides.length;

    function showSlide(index) {
      currentSlide = (index + totalSlides) % totalSlides;
      sliderTrack.style.transform = `translateX(-${currentSlide * 100}%)`;
      dots.forEach((dot, i) => {
        dot.classList.toggle('active', i === currentSlide);
      });
    }

    if (prevSlide) {
      prevSlide.addEventListener('click', () => showSlide(currentSlide - 1));
    }
    if (nextSlide) {
      nextSlide.addEventListener('click', () => showSlide(currentSlide + 1));
    }
    dots.forEach((dot, i) => {
      dot.addEventListener('click', () => showSlide(i));
    });

    // Auto-play slider
    setInterval(() => showSlide(currentSlide + 1), 5000);
  }

  // Contact form submission
  const contactForm = document.getElementById('contactForm');
  if (contactForm) {
    contactForm.addEventListener('submit', e => {
      e.preventDefault();
      const name = contactForm.name.value.trim();
      const email = contactForm.email.value.trim();
      const message = contactForm.message.value.trim();
      const status = document.getElementById('contactStatus');

      // Front-end validation
      if (!name || !email || !message) {
        status.textContent = 'Ju lutem plotësoni të gjitha fushat.';
        status.className = 'status error';
        status.style.display = 'block';
        return;
      }

      if (!email.includes('@')) {
        status.textContent = 'Email i pavlefshëm.';
        status.className = 'status error';
        status.style.display = 'block';
        return;
      }

      const basePath = window.location.pathname.substring(0, window.location.pathname.lastIndexOf('/'));
      fetch(basePath + '/api/contact.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: `action=submit&name=${encodeURIComponent(name)}&email=${encodeURIComponent(email)}&message=${encodeURIComponent(message)}`
      })
      .then(r => r.json())
      .then(data => {
        status.textContent = data.message;
        status.className = data.success ? 'status success' : 'status error';
        status.style.display = 'block';
        if (data.success) {
          contactForm.reset();
        }
      })
      .catch(err => {
        status.textContent = 'Gabim në lidhje.';
        status.className = 'status error';
        status.style.display = 'block';
      });
    });
  }
});
// js update


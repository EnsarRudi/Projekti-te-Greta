// Admin Dashboard JavaScript
document.addEventListener('DOMContentLoaded', () => {
  // Tab switching
  document.querySelectorAll('.tab').forEach(tab => {
    tab.addEventListener('click', () => {
      const tabName = tab.dataset.tab;
      document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
      document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
      tab.classList.add('active');
      document.getElementById(tabName).classList.add('active');
      loadTabContent(tabName);
    });
  });

  // Load initial content
  loadTabContent('products');
});

function loadTabContent(tab) {
  switch(tab) {
    case 'products':
      loadProducts();
      break;
    case 'news':
      loadNews();
      break;
    case 'messages':
      loadMessages();
      break;
    case 'about':
      loadAbout();
      break;
    case 'slider':
      loadSlider();
      break;
  }
}

function loadProducts() {
  fetch('../api/admin.php?action=list&type=products')
    .then(r => r.json())
    .then(data => {
      if (data.success) {
        const html = data.data.length ? `
          <table class="admin-table">
            <thead>
              <tr>
                <th>ID</th>
                <th>Emri</th>
                <th>Çmimi</th>
                <th>Kategoria</th>
                <th>Krijuar nga</th>
                <th>Veprime</th>
              </tr>
            </thead>
            <tbody>
              ${data.data.map(p => `
                <tr>
                  <td>${p.id}</td>
                  <td>${p.name}</td>
                  <td>€${parseFloat(p.price_per_day).toFixed(2)}</td>
                  <td>${p.category || 'N/A'}</td>
                  <td>${p.created_by_name || 'N/A'}</td>
                  <td>
                    <button class="cta btn-small" onclick="editProduct(${p.id})">Edit</button>
                    <button class="cta outline btn-small" onclick="deleteProduct(${p.id})">Fshi</button>
                  </td>
                </tr>
              `).join('')}
            </tbody>
          </table>
        ` : '<p>Nuk ka produkte.</p>';
        document.getElementById('productsList').innerHTML = html;
      }
    });
}

function loadNews() {
  fetch('../api/admin.php?action=list&type=news')
    .then(r => r.json())
    .then(data => {
      if (data.success) {
        const html = data.data.length ? `
          <table class="admin-table">
            <thead>
              <tr>
                <th>ID</th>
                <th>Titulli</th>
                <th>Krijuar nga</th>
                <th>Data</th>
                <th>Veprime</th>
              </tr>
            </thead>
            <tbody>
              ${data.data.map(n => `
                <tr>
                  <td>${n.id}</td>
                  <td>${n.title}</td>
                  <td>${n.created_by_name || 'N/A'}</td>
                  <td>${new Date(n.created_at).toLocaleDateString('sq-AL')}</td>
                  <td>
                    <button class="cta btn-small" onclick="editNews(${n.id})">Edit</button>
                    <button class="cta outline btn-small" onclick="deleteNews(${n.id})">Fshi</button>
                  </td>
                </tr>
              `).join('')}
            </tbody>
          </table>
        ` : '<p>Nuk ka lajme.</p>';
        document.getElementById('newsList').innerHTML = html;
      }
    });
}

function loadMessages() {
  fetch('../api/contact.php?action=list')
    .then(r => r.json())
    .then(data => {
      if (data.success) {
        const html = data.data.length ? `
          <table class="admin-table">
            <thead>
              <tr>
                <th>ID</th>
                <th>Emri</th>
                <th>Email</th>
                <th>Mesazhi</th>
                <th>Statusi</th>
                <th>Data</th>
                <th>Veprime</th>
              </tr>
            </thead>
            <tbody>
              ${data.data.map(m => `
                <tr>
                  <td>${m.id}</td>
                  <td>${m.name}</td>
                  <td>${m.email}</td>
                  <td>${m.message.substring(0, 50)}...</td>
                  <td>${m.read_status ? 'Lexuar' : '<strong>E palexuar</strong>'}</td>
                  <td>${new Date(m.created_at).toLocaleDateString('sq-AL')}</td>
                  <td>
                    ${!m.read_status ? `<button class="cta btn-small" onclick="markAsRead(${m.id})">Shëno si lexuar</button>` : ''}
                    <button class="cta outline btn-small" onclick="deleteMessage(${m.id})">Fshi</button>
                  </td>
                </tr>
              `).join('')}
            </tbody>
          </table>
        ` : '<p>Nuk ka mesazhe.</p>';
        document.getElementById('messagesList').innerHTML = html;
      }
    });
}

function loadAbout() {
  fetch('../api/admin.php?action=list&type=about')
    .then(r => r.json())
    .then(data => {
      if (data.success) {
        const html = data.data.length ? `
          <table class="admin-table">
            <thead>
              <tr>
                <th>ID</th>
                <th>Titulli</th>
                <th>Përmbajtja</th>
                <th>Rendi</th>
                <th>Veprime</th>
              </tr>
            </thead>
            <tbody>
              ${data.data.map(a => `
                <tr>
                  <td>${a.id}</td>
                  <td>${a.section_title}</td>
                  <td>${a.content.substring(0, 50)}...</td>
                  <td>${a.display_order}</td>
                  <td>
                    <button class="cta btn-small" onclick="editAbout(${a.id})">Edit</button>
                    <button class="cta outline btn-small" onclick="deleteAbout(${a.id})">Fshi</button>
                  </td>
                </tr>
              `).join('')}
            </tbody>
          </table>
        ` : '<p>Nuk ka përmbajtje.</p>';
        document.getElementById('aboutList').innerHTML = html;
      }
    });
}

function loadSlider() {
  fetch('../api/admin.php?action=list&type=slider')
    .then(r => r.json())
    .then(data => {
      if (data.success) {
        const html = data.data.length ? `
          <table class="admin-table">
            <thead>
              <tr>
                <th>ID</th>
                <th>Titulli</th>
                <th>Rendi</th>
                <th>Statusi</th>
                <th>Veprime</th>
              </tr>
            </thead>
            <tbody>
              ${data.data.map(s => `
                <tr>
                  <td>${s.id}</td>
                  <td>${s.title}</td>
                  <td>${s.display_order}</td>
                  <td>${s.active ? 'Aktiv' : 'Jo aktiv'}</td>
                  <td>
                    <button class="cta btn-small" onclick="editSlider(${s.id})">Edit</button>
                    <button class="cta outline btn-small" onclick="deleteSlider(${s.id})">Fshi</button>
                  </td>
                </tr>
              `).join('')}
            </tbody>
          </table>
        ` : '<p>Nuk ka slide.</p>';
        document.getElementById('sliderList').innerHTML = html;
      }
    });
}

function openProductForm(id = null) {
  document.getElementById('productModalTitle').textContent = id ? 'Modifiko Produkt' : 'Shto Produkt';
  document.getElementById('productForm').reset();
  document.getElementById('productId').value = id || '';
  if (id) {
    fetch(`../api/admin.php?action=get&type=products&id=${id}`)
      .then(r => r.json())
      .then(data => {
        if (data.success) {
          const p = data.data;
          document.getElementById('productName').value = p.name;
          document.getElementById('productDescription').value = p.description || '';
          document.getElementById('productPrice').value = p.price_per_day;
          document.getElementById('productCategory').value = p.category || '';
          document.getElementById('productTransmission').value = p.transmission || '';
        }
      });
  }
  document.getElementById('productModal').classList.add('active');
}

function openNewsForm(id = null) {
  document.getElementById('newsModalTitle').textContent = id ? 'Modifiko Lajm' : 'Shto Lajm';
  document.getElementById('newsForm').reset();
  document.getElementById('newsId').value = id || '';
  if (id) {
    fetch(`../api/admin.php?action=get&type=news&id=${id}`)
      .then(r => r.json())
      .then(data => {
        if (data.success) {
          const n = data.data;
          document.getElementById('newsTitle').value = n.title;
          document.getElementById('newsContent').value = n.content;
        }
      });
  }
  document.getElementById('newsModal').classList.add('active');
}

function openAboutForm(id = null) {
  // Similar implementation for about form
  alert('Funksionaliteti për About form do të shtohet.');
}

function openSliderForm(id = null) {
  // Similar implementation for slider form
  alert('Funksionaliteti për Slider form do të shtohet.');
}

function closeModal(modalId) {
  document.getElementById(modalId).classList.remove('active');
}

function editProduct(id) {
  openProductForm(id);
}

function editNews(id) {
  openNewsForm(id);
}

function deleteProduct(id) {
  if (confirm('Jeni të sigurt që dëshironi të fshini këtë produkt?')) {
    fetch('../api/admin.php', {
      method: 'POST',
      headers: {'Content-Type': 'application/x-www-form-urlencoded'},
      body: `action=delete&type=products&id=${id}`
    })
    .then(r => r.json())
    .then(data => {
      alert(data.message);
      if (data.success) loadProducts();
    });
  }
}

function deleteNews(id) {
  if (confirm('Jeni të sigurt që dëshironi të fshini këtë lajm?')) {
    fetch('../api/admin.php', {
      method: 'POST',
      headers: {'Content-Type': 'application/x-www-form-urlencoded'},
      body: `action=delete&type=news&id=${id}`
    })
    .then(r => r.json())
    .then(data => {
      alert(data.message);
      if (data.success) loadNews();
    });
  }
}

function markAsRead(id) {
  fetch('../api/contact.php', {
    method: 'POST',
    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
    body: `action=mark_read&id=${id}`
  })
  .then(r => r.json())
  .then(data => {
    alert(data.message);
    if (data.success) loadMessages();
  });
}

function deleteMessage(id) {
  if (confirm('Jeni të sigurt që dëshironi të fshini këtë mesazh?')) {
    fetch('../api/contact.php', {
      method: 'POST',
      headers: {'Content-Type': 'application/x-www-form-urlencoded'},
      body: `action=delete&id=${id}`
    })
    .then(r => r.json())
    .then(data => {
      alert(data.message);
      if (data.success) loadMessages();
    });
  }
}

function deleteAbout(id) {
  if (confirm('Jeni të sigurt?')) {
    fetch('../api/admin.php', {
      method: 'POST',
      headers: {'Content-Type': 'application/x-www-form-urlencoded'},
      body: `action=delete&type=about&id=${id}`
    })
    .then(r => r.json())
    .then(data => {
      alert(data.message);
      if (data.success) loadAbout();
    });
  }
}

function deleteSlider(id) {
  if (confirm('Jeni të sigurt?')) {
    fetch('../api/admin.php', {
      method: 'POST',
      headers: {'Content-Type': 'application/x-www-form-urlencoded'},
      body: `action=delete&type=slider&id=${id}`
    })
    .then(r => r.json())
    .then(data => {
      alert(data.message);
      if (data.success) loadSlider();
    });
  }
}

// Form submissions
document.getElementById('productForm')?.addEventListener('submit', e => {
  e.preventDefault();
  const formData = new FormData(e.target);
  formData.append('action', 'save');
  formData.append('type', 'products');
  
  fetch('../api/admin.php', {
    method: 'POST',
    body: formData
  })
  .then(r => r.json())
  .then(data => {
    alert(data.message);
    if (data.success) {
      closeModal('productModal');
      loadProducts();
    }
  });
});

document.getElementById('newsForm')?.addEventListener('submit', e => {
  e.preventDefault();
  const formData = new FormData(e.target);
  formData.append('action', 'save');
  formData.append('type', 'news');
  
  fetch('../api/admin.php', {
    method: 'POST',
    body: formData
  })
  .then(r => r.json())
  .then(data => {
    alert(data.message);
    if (data.success) {
      closeModal('newsModal');
      loadNews();
    }
  });
});

function logout() {
  fetch('../api/auth.php', {
    method: 'POST',
    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
    body: 'action=logout'
  })
  .then(r => r.json())
  .then(data => {
    window.location.href = '../index.php';
  });
}


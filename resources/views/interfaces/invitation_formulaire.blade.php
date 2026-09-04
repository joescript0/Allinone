<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Stone & Divayne</title>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400;1,700&family=Dancing+Script:wght@400;700&family=Great+Vibes&display=swap" rel="stylesheet" />
  <meta name="description" content="Invitation de mariage" />
  <meta property="og:image" content="{{ asset('/photo_font.jpeg') }}" />
  <meta property="og:description" content="Invitation de mariage" />
  <meta property="og:url" content="{{ url('') }}" />
  <meta property="og:title" content="Stone & Divayne" />

  <style>
    /* ===== STYLES (inchangés) ===== */
    * { margin:0; padding:0; box-sizing:border-box; }
    body {
      min-height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      background: linear-gradient(160deg, #9ac2e8 0%, #b5d6f5 35%, #6a9fd6 70%, #8bb9e8 100%);
      font-family: 'Playfair Display', serif;
      padding: 20px;
      position: relative;
    }
    .card {
      width: 100%;
      max-width: 620px;
      background: rgba(255, 255, 255, 0.95);
      border-radius: 40px;
      padding: 40px 30px;
      text-align: center;
      box-shadow: 0 20px 50px rgba(10, 41, 66, 0.3);
      position: relative;
      z-index: 1;
    }
    .title { font-family: 'Great Vibes', cursive; font-size: 55px; color: #1f4a6b; margin-bottom: 5px; }
    .sub { font-family: 'Dancing Script', cursive; font-size: 28px; color: #0a2942; margin-bottom: 30px; }
    .deadline { font-size: 18px; color: #1f4a6b; margin-bottom: 20px; background: #eaf4ff; padding: 10px; border-radius: 50px; }
    .form-group { text-align: left; margin-bottom: 20px; }
    .form-group label { display: block; font-weight: 700; color: #0a2942; font-size: 17px; margin-bottom: 5px; }
    .form-group input[type="text"], .form-group input[type="tel"] {
      width: 100%; padding: 13px 18px; border: 2px solid #d4e9ff; border-radius: 16px;
      font-family: 'Playfair Display', serif; font-size: 16px; outline: none;
    }
    .form-group input:focus { border-color: #6a9fd6; }
    .radio-group { display: flex; flex-wrap: wrap; gap: 10px 20px; }
    .radio-group label { display: flex; align-items: center; gap: 10px; font-weight: 400; color: #1f4a6b; font-size: 16px; }
    .radio-group input[type="radio"] { width: 19px; height: 19px; accent-color: #6a9fd6; }
    .input-other { width: 100%; margin-top: 10px; padding: 10px; border: 2px solid #d4e9ff; border-radius: 12px; display: none; }
    .btn-submit {
      background: linear-gradient(135deg, #6a9fd6, #1f4a6b); color: white; padding: 16px 45px; border: none;
      border-radius: 60px; font-size: 20px; font-weight: bold; cursor: pointer; width: 100%; margin-top: 10px;
      box-shadow: 0 8px 30px rgba(26, 67, 113, 0.35); transition: transform 0.2s, opacity 0.2s;
    }
    .btn-submit:hover { transform: translateY(-3px); }
    .btn-submit:disabled { opacity: 0.6; cursor: not-allowed; transform: none; }
    .back-link { display: block; margin-top: 20px; color: #1f4a6b; text-decoration: underline; }

    .modal-overlay {
      position: fixed; top:0; left:0; width:100%; height:100%; background: rgba(0,0,0,0.5);
      backdrop-filter: blur(6px); display: flex; align-items: center; justify-content: center;
      z-index: 1000; visibility: hidden; opacity: 0; transition: opacity 0.3s ease, visibility 0.3s;
    }
    .modal-overlay.active { visibility: visible; opacity: 1; }
    .modal-box {
      background: white; border-radius: 40px; padding: 40px 30px; max-width: 480px; width: 90%;
      text-align: center; box-shadow: 0 30px 60px rgba(0,0,0,0.3);
      transform: scale(0.9); transition: transform 0.3s ease;
    }
    .modal-overlay.active .modal-box { transform: scale(1); }
    .modal-box h2 { font-family: 'Great Vibes', cursive; font-size: 40px; color: #1f4a6b; margin-bottom: 10px; }
    .modal-box p { font-size: 18px; color: #0a2942; margin: 20px 0; }
    .modal-box .btn-modal {
      background: #1f4a6b; color: white; border: none; padding: 12px 35px; border-radius: 60px;
      font-size: 18px; font-weight: bold; cursor: pointer; transition: background 0.2s; margin: 5px;
    }
    .modal-box .btn-modal:hover { background: #6a9fd6; }
    .modal-box .btn-modal.success { background: #2a7a4a; }
    .modal-box .btn-modal.success:hover { background: #3aa063; }
    .modal-box .btn-modal.danger { background: #b13e3e; }
    .modal-box .btn-modal.danger:hover { background: #d45a5a; }
    .modal-box .btn-modal.secondary { background: #6c757d; }
    .modal-box .btn-modal.secondary:hover { background: #5a6268; }
    .modal-box .btn-modal.download { background: linear-gradient(135deg, #6a9fd6, #1f4a6b); }
    .modal-box .btn-modal.download:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(26, 67, 113, 0.3); }

    .qr-image-container { margin: 20px auto; text-align: center; background: white; padding: 15px; border-radius: 20px; display: inline-block; border: 2px solid #d4e9ff; }
    .qr-image-container img { display: block; max-width: 100%; height: auto; }

    @media (max-width: 480px) {
      .card { padding: 25px 15px; }
      .title { font-size: 40px; }
      .sub { font-size: 22px; }
      .radio-group { flex-direction: column; gap: 5px; }
      .modal-box { padding: 30px 20px; }
      .modal-box h2 { font-size: 32px; }
      .btn-modal { font-size: 16px; padding: 10px 25px; }
    }
  </style>
</head>
<body>

  <div class="card">
    <div class="title">Confirmation</div>
    <div class="sub">Merci de remplir vos coordonnées</div>
    <div class="deadline">📅 Merci de répondre avant le <strong>27 Décembre 2025</strong></div>

    <form id="inviteForm" method="POST" action="{{ route('add_invites') }}">
      @csrf
      <input type="hidden" name="code_unique" id="code_unique" value="" />

      <div class="form-group">
        <label>Présence</label>
        <div class="radio-group">
          <label><input type="radio" name="presence" value="oui" required /> OUI, je serai là ✨</label>
          <label><input type="radio" name="presence" value="non" /> NON, je ne pourrai pas venir</label>
        </div>
      </div>
      <div class="form-group">
        <label for="nom">Nom complet</label>
        <input type="text" id="nom" name="nom" placeholder="Votre nom et prénom" required />
      </div>
      <div class="form-group">
        <label for="tel">Téléphone / WhatsApp</label>
        <input type="tel" id="tel" name="telephone" placeholder="Votre numéro" required />
      </div>
      <div class="form-group">
        <label>Votre relation avec le couple</label>
        <div class="radio-group" style="flex-direction: column; gap: 5px;">
          <label><input type="radio" name="relation" value="famille_marie" required /> Famille du marié</label>
          <label><input type="radio" name="relation" value="famille_mariee" /> Famille de la mariée</label>
          <label><input type="radio" name="relation" value="ami" /> Ami(e)</label>
          <label><input type="radio" name="relation" value="collegue" /> Collègue / connaissance</label>
          <label><input type="radio" name="relation" value="autre" /> Autre (précisez ci‑dessous)</label>
        </div>
        <input type="text" class="input-other" name="relation_autre" placeholder="✏️ Précisez ici..." />
      </div>
      <button type="submit" id="submitBtn" class="btn-submit">ENVOYER MA RÉPONSE</button>
    </form>
    <a href="{{ route('invitation_programme') }}" class="back-link">← Retour au programme</a>
  </div>

  <!-- MODALES -->
  <div id="errorModal" class="modal-overlay">
    <div class="modal-box">
      <h2>😕 Oups !</h2>
      <p id="errorMessage">Veuillez remplir tous les champs obligatoires.</p>
      <button class="btn-modal danger" onclick="closeModal('errorModal')">Fermer</button>
    </div>
  </div>

  <div id="successModal" class="modal-overlay">
    <div class="modal-box">
      <h2 id="modalTitle">🎉 Merci !</h2>
      <p id="modalMessage">Votre présence nous fait chaud au cœur ! Voici votre QR code d'entrée unique pour la salle. Téléchargez-le svp.</p>
      <div id="qrContainer" class="qr-image-container" style="display: none;">
        <img id="qrImage" src="" alt="QR Code" />
      </div>
      <button id="btnDownloadInvite" class="btn-modal download" style="display: none;" onclick="telechargerQR()">
        📥 Télécharger mon invitation
      </button>
      <button class="btn-modal success" onclick="redirectToProgram()">Voir le programme</button>
      <button class="btn-modal secondary" onclick="closeModal('successModal')">Fermer</button>
    </div>
  </div>

  <!-- Canvas pour générer le QR avec logo -->
  <canvas id="qrCanvas" style="display:none;"></canvas>

  <script>
    // ================================================
    // GÉNÉRATION D'UUID
    // ================================================
    function genererUUID() {
      if (window.crypto && window.crypto.randomUUID) {
        return window.crypto.randomUUID();
      }
      return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
        const r = Math.random() * 16 | 0;
        const v = c === 'x' ? r : (r & 0x3 | 0x8);
        return v.toString(16);
      });
    }

    let qrDataURL = '';

    // ================================================
    // GÉNÉRATION DU QR AVEC LOGO (via API externe)
    // ================================================
    function genererEtAfficherQR(qrContent) {
      // Construire l'URL de l'API QR
      const apiUrl = 'https://api.qrserver.com/v1/create-qr-code/?data=' + encodeURIComponent(qrContent) + '&size=400x400&margin=20&format=png';
      
      // Charger l'image du QR depuis l'API
      const qrImage = new Image();
      qrImage.crossOrigin = 'Anonymous';
      qrImage.onload = function() {
        // Créer un canvas pour combiner QR + logo
        const canvas = document.getElementById('qrCanvas');
        const ctx = canvas.getContext('2d');
        canvas.width = 400;
        canvas.height = 400;

        // Dessiner le QR
        ctx.drawImage(qrImage, 0, 0, 400, 400);

        // Charger le logo
        const logoUrl = "{{ asset('photo_font.jpeg') }}";
        const logo = new Image();
        logo.crossOrigin = 'Anonymous';
        logo.onload = function() {
          const size = 80;
          const x = (canvas.width - size) / 2;
          const y = (canvas.height - size) / 2;

          // Effacer une zone circulaire sous le logo
          ctx.save();
          ctx.beginPath();
          ctx.arc(canvas.width / 2, canvas.height / 2, size / 2 + 5, 0, Math.PI * 2);
          ctx.fillStyle = '#ffffff';
          ctx.fill();
          ctx.restore();

          // Dessiner le logo en cercle
          ctx.save();
          ctx.beginPath();
          ctx.arc(canvas.width / 2, canvas.height / 2, size / 2, 0, Math.PI * 2);
          ctx.closePath();
          ctx.clip();
          ctx.drawImage(logo, x, y, size, size);

          // Bordure
          ctx.strokeStyle = '#0a2942';
          ctx.lineWidth = 2;
          ctx.beginPath();
          ctx.arc(canvas.width / 2, canvas.height / 2, size / 2, 0, Math.PI * 2);
          ctx.stroke();
          ctx.restore();

          // Sauvegarder le dataURL
          qrDataURL = canvas.toDataURL('image/png');
          document.getElementById('qrImage').src = qrDataURL;
          document.getElementById('qrContainer').style.display = 'inline-block';
          document.getElementById('btnDownloadInvite').style.display = 'inline-block';
        };

        logo.onerror = function() {
          // Si le logo ne charge pas, on affiche juste le QR
          qrDataURL = canvas.toDataURL('image/png');
          document.getElementById('qrImage').src = qrDataURL;
          document.getElementById('qrContainer').style.display = 'inline-block';
          document.getElementById('btnDownloadInvite').style.display = 'inline-block';
        };

        logo.src = logoUrl;
      };

      qrImage.onerror = function() {
        alert('Impossible de générer le QR code. Vérifiez votre connexion internet.');
      };

      qrImage.src = apiUrl;
    }

    // ================================================
    // TÉLÉCHARGEMENT
    // ================================================
    function telechargerQR() {
      if (!qrDataURL) {
        alert('Aucun QR à télécharger.');
        return;
      }
      const btn = document.getElementById('btnDownloadInvite');
      btn.disabled = true;
      btn.textContent = '⏳ Téléchargement...';

      fetch(qrDataURL)
        .then(response => response.blob())
        .then(blob => {
          const blobURL = URL.createObjectURL(blob);
          const link = document.createElement('a');
          link.href = blobURL;
          link.download = 'Invitation_Stone_Divayne.png';
          document.body.appendChild(link);
          link.click();
          document.body.removeChild(link);
          setTimeout(() => URL.revokeObjectURL(blobURL), 5000);
          btn.disabled = false;
          btn.textContent = '📥 Télécharger mon invitation';
        })
        .catch(error => {
          console.error('Erreur téléchargement:', error);
          alert('Erreur lors du téléchargement. Veuillez réessayer.');
          btn.disabled = false;
          btn.textContent = '📥 Télécharger mon invitation';
        });
    }

    // ================================================
    // GESTION DU FORMULAIRE (sans JSON)
    // ================================================
    document.addEventListener('DOMContentLoaded', function() {
      const form = document.getElementById('inviteForm');
      const submitBtn = document.getElementById('submitBtn');
      const errorModal = document.getElementById('errorModal');
      const errorMsg = document.getElementById('errorMessage');
      const successModal = document.getElementById('successModal');
      const modalTitle = document.getElementById('modalTitle');
      const modalMessage = document.getElementById('modalMessage');
      const btnDownload = document.getElementById('btnDownloadInvite');
      const codeInput = document.getElementById('code_unique');

      document.querySelectorAll('.modal-overlay').forEach(function(overlay) {
        overlay.addEventListener('click', function(e) {
          if (e.target === this) this.classList.remove('active');
        });
      });

      form.addEventListener('submit', function(e) {
        e.preventDefault();

        // Générer le code unique
        const codeUnique = genererUUID();
        codeInput.value = codeUnique;

        // Validation
        let errors = [];
        const presenceRadio = document.querySelector('input[name="presence"]:checked');
        if (!presenceRadio) errors.push('Veuillez indiquer votre présence.');

        const nom = document.getElementById('nom').value.trim();
        if (nom === '') errors.push('Veuillez saisir votre nom complet.');

        const tel = document.getElementById('tel').value.trim();
        if (tel === '') errors.push('Veuillez saisir votre numéro.');

        const relationRadio = document.querySelector('input[name="relation"]:checked');
        if (!relationRadio) {
          errors.push('Veuillez indiquer votre relation.');
        } else if (relationRadio.value === 'autre') {
          const autre = document.querySelector('input[name="relation_autre"]').value.trim();
          if (autre === '') errors.push('Veuillez préciser votre relation.');
        }

        if (errors.length > 0) {
          errorMsg.textContent = errors.join('\n');
          errorModal.classList.add('active');
          return;
        }

        const presenceValue = presenceRadio.value;
        let relationLabel = relationRadio.nextSibling.textContent.trim();
        if (relationRadio.value === 'autre') {
          const autre = document.querySelector('input[name="relation_autre"]').value.trim();
          if (autre) relationLabel = autre;
        }

        submitBtn.disabled = true;
        submitBtn.textContent = 'Envoi en cours...';

        const formData = new FormData(form);

        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
              'X-Requested-With': 'XMLHttpRequest',
              'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
            }
          })
          .then(response => {
            if (response.ok) {
              submitBtn.disabled = false;
              submitBtn.textContent = 'ENVOYER MA RÉPONSE';

              form.reset();
              const autreInput = document.querySelector('input[name="relation_autre"]');
              autreInput.style.display = 'none';
              autreInput.value = '';
              autreInput.required = false;
              document.querySelectorAll('input[type="radio"]').forEach(radio => radio.checked = false);

              const qrUrl = "{{ route('check_qr_code') }}?code=" + encodeURIComponent(codeUnique);

              if (presenceValue === 'oui') {
                modalTitle.textContent = '🎉 Merci infiniment !';
                modalMessage.textContent = 'Votre présence nous fait chaud au cœur ! Voici votre QR code d\'entrée unique pour la salle. Téléchargez-le svp.';
                genererEtAfficherQR(qrUrl);
              } else {
                modalTitle.textContent = '😔 Nous sommes tristes...';
                modalMessage.textContent = 'Nous avons bien pris note de votre absence.';
                document.getElementById('qrContainer').style.display = 'none';
                btnDownload.style.display = 'none';
              }
              successModal.classList.add('active');
            } else {
              return response.text().then(() => {
                throw new Error('Erreur serveur (code ' + response.status + ').');
              });
            }
          })
          .catch(error => {
            submitBtn.disabled = false;
            submitBtn.textContent = 'ENVOYER MA RÉPONSE';
            errorMsg.textContent = error.message || 'Une erreur est survenue.';
            errorModal.classList.add('active');
          });
      });
    });

    // ================================================
    // UTILITAIRES
    // ================================================
    function closeModal(id) {
      document.getElementById(id).classList.remove('active');
    }

    function redirectToProgram() {
      window.location.href = "{{ route('invitation_programme') }}";
    }

    // Gestion du champ "Autre"
    document.addEventListener('DOMContentLoaded', function() {
      const radiosRelation = document.querySelectorAll('input[name="relation"]');
      const autreInput = document.querySelector('input[name="relation_autre"]');

      function toggleAutre() {
        const selected = document.querySelector('input[name="relation"]:checked');
        if (selected && selected.value === 'autre') {
          autreInput.style.display = 'block';
          autreInput.required = true;
        } else {
          autreInput.style.display = 'none';
          autreInput.required = false;
          autreInput.value = '';
        }
      }
      radiosRelation.forEach(radio => radio.addEventListener('change', toggleAutre));
      toggleAutre();
    });

    // Musique
    document.addEventListener('DOMContentLoaded', function() {
      const audio = new Audio("{{ asset('/stone_et_divayne.aac') }}");
      audio.loop = true;
      audio.volume = 0.5;

      function demarrer() { audio.play().catch(() => {}); }
      demarrer();

      let aJoue = false;
      audio.addEventListener('play', () => aJoue = true);

      function demarrerAuGestuel() {
        if (!aJoue) audio.play().catch(() => {});
        document.removeEventListener('touchstart', demarrerAuGestuel);
        document.removeEventListener('click', demarrerAuGestuel);
        document.removeEventListener('scroll', demarrerAuGestuel);
        document.removeEventListener('keydown', demarrerAuGestuel);
      }

      document.addEventListener('touchstart', demarrerAuGestuel, { once: true });
      document.addEventListener('click', demarrerAuGestuel, { once: true });
      document.addEventListener('scroll', demarrerAuGestuel, { once: true });
      document.addEventListener('keydown', demarrerAuGestuel, { once: true });
    });
  </script>
</body>
</html>
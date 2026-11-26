-- MySQL dump 10.13  Distrib 8.4.3, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: kitapeduli
-- ------------------------------------------------------
-- Server version	8.4.3

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache_locks`
--

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `campaign_updates`
--

DROP TABLE IF EXISTS `campaign_updates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `campaign_updates` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `campaign_id` bigint unsigned NOT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `image_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `published_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `campaign_updates_created_by_foreign` (`created_by`),
  KEY `campaign_updates_campaign_id_published_at_index` (`campaign_id`,`published_at`),
  CONSTRAINT `campaign_updates_campaign_id_foreign` FOREIGN KEY (`campaign_id`) REFERENCES `campaigns` (`id`) ON DELETE CASCADE,
  CONSTRAINT `campaign_updates_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `campaign_updates`
--

LOCK TABLES `campaign_updates` WRITE;
/*!40000 ALTER TABLE `campaign_updates` DISABLE KEYS */;
INSERT INTO `campaign_updates` VALUES (1,1,1,'Penyaluran Tahap 1: Paket Nutrisi & Pendampingan Medis','Terima kasih kepada para Orang Baik di KitaPeduli. Tim pengelola telah menyalurkan paket nutrisi khusus tahap pertama kepada 15 anak dampingan serta memfasilitasi kontrol rutin di rumah sakit rujukan.',NULL,'2026-11-22 08:22:06','2026-11-25 08:22:06','2026-11-25 08:22:06'),(2,2,1,'Pengadaan 250 Buku Bacaan Anak & Rak Kayu Baru','Proses perbaikan ruang baca berjalan lancar. Minggu ini sebanyak 250 buku cerita dan ensiklopedia sains anak telah tiba di lokasi perpustakaan pesisir.',NULL,'2026-11-23 08:22:06','2026-11-25 08:22:06','2026-11-25 08:22:06');
/*!40000 ALTER TABLE `campaign_updates` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `campaigns`
--

DROP TABLE IF EXISTS `campaigns`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `campaigns` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `category_id` bigint unsigned NOT NULL,
  `created_by` bigint unsigned NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `short_description` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `story` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `banner_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `target_amount` decimal(15,2) NOT NULL,
  `collected_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `donor_count` int unsigned NOT NULL DEFAULT '0',
  `start_date` date DEFAULT NULL,
  `end_date` date NOT NULL,
  `organizer_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `organizer_type` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `organizer_description` text COLLATE utf8mb4_unicode_ci,
  `is_featured` tinyint(1) NOT NULL DEFAULT '0',
  `is_verified_organizer` tinyint(1) NOT NULL DEFAULT '1',
  `status` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `campaigns_slug_unique` (`slug`),
  KEY `campaigns_category_id_foreign` (`category_id`),
  KEY `campaigns_created_by_foreign` (`created_by`),
  KEY `campaigns_end_date_index` (`end_date`),
  KEY `campaigns_is_featured_index` (`is_featured`),
  KEY `campaigns_status_index` (`status`),
  CONSTRAINT `campaigns_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `campaigns_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `campaigns`
--

LOCK TABLES `campaigns` WRITE;
/*!40000 ALTER TABLE `campaigns` DISABLE KEYS */;
INSERT INTO `campaigns` VALUES (1,1,1,'Bantu Pengobatan & Nutrisi Anak Pejuang Kesehatan (Demo)','bantu-pengobatan-dan-nutrisi-anak-pejuang-kesehatan','Program demonstrasi KitaPeduli untuk mendukung biaya terapi medis dan paket nutrisi anak-anak keluarga prasejahtera.','Kampanye ini merupakan data demonstrasi akademik pada aplikasi KitaPeduli.\n\nBanyak anak dari keluarga prasejahtera membutuhkan pendampingan pengobatan rutin, pemeriksaan laboratorium, serta asupan nutrisi khusus selama masa pemulihan. Keterbatasan biaya transportasi dan tindakan medis sering kali menghambat keberlanjutan terapi mereka.\n\nMelalui penggalangan dana di KitaPeduli, bantuan disalurkan untuk:\n1. Paket nutrisi pemulihan dan susu medis khusus anak.\n2. Subsidi biaya pemeriksaan berkala dan obat-obatan penunjang.\n3. Operasional rumah singgah pasien anak dari luar daerah.','images/campaigns/kesehatan.svg',50000000.00,1500000.00,3,'2026-11-10','2027-01-09','Yayasan Sehat Nusantara (Demo)','Yayasan Sosial Terverifikasi','Lembaga sosial bidang pendampingan pasien anak dan kesehatan masyarakat prasejahtera.',1,1,'active','2026-11-25 08:22:06','2026-11-25 08:22:06'),(2,2,1,'Renovasi Perpustakaan & Beasiswa Anak Pesisir (Demo)','renovasi-perpustakaan-dan-beasiswa-anak-pesisir','Hadirkan ruang baca yang layak, buku pelajaran baru, dan perlengkapan sekolah untuk 150 siswa desa pesisir.','Kampanye demonstrasi KitaPeduli untuk pemerataan akses literasi dan pendidikan.\n\nAnak-anak di kawasan pesisir memiliki semangat belajar yang luar biasa, namun ruang baca komunitas yang mereka gunakan mengalami kerusakan atap dan kekurangan buku referensi.\n\nDana donasi akan dialokasikan untuk:\n1. Perbaikan atap, rak buku, dan meja baca taman literasi.\n2. Pengadaan 600 buku bacaan anak dan ensiklopedia sains.\n3. Paket tas, sepatu, dan alat tulis untuk 150 siswa.','images/campaigns/pendidikan.svg',40000000.00,1000000.00,1,'2026-11-05','2026-12-30','Komunitas Lentera Pendidikan (Demo)','Komunitas Pendidikan','Penggerak taman bacaan masyarakat dan beasiswa pendidikan dasar.',1,1,'active','2026-11-25 08:22:06','2026-11-25 08:22:06'),(3,3,1,'Dapur Umum & Paket Logistik Warga Terdampak Banjir (Demo)','dapur-umum-dan-paket-logistik-warga-terdampak-banjir','Penyaluran makanan siap saji, air bersih, selimut, dan perlengkapan kebersihan untuk posko pengungsian.','Kampanye demonstrasi tanggap darurat bencana alam pada platform KitaPeduli.\n\nCurah hujan tinggi menyebabkan ratusan keluarga harus mengungsi sementara ke posko darurat. Kebutuhan paling mendesak saat ini adalah makanan hangat siap saji, air minum bersih, popok bayi, dan obat-obatan ringan.\n\nSetiap kontribusi Anda disalurkan melalui tim relawan untuk operasional dapur umum selama masa tanggap darurat.','images/campaigns/bencana-alam.svg',75000000.00,600000.00,1,'2026-11-15','2026-12-20','Tim Relawan Tanggap Bencana KitaPeduli (Demo)','Lembaga Kemanusiaan','Unit koordinasi bantuan logistik darurat dan dapur umum bencana.',1,1,'active','2026-11-25 08:22:06','2026-11-25 08:22:06'),(4,4,1,'Paket Sembako & Pemeriksaan Lansia Sebatang Kara (Demo)','paket-sembako-dan-pemeriksaan-lansia-sebatang-kara','Berbagi paket kebutuhan pokok bulanan dan kunjungan pemeriksaan kesehatan rutin bagi lansia prasejahtera.','Kampanye demonstrasi kepedulian sosial kemanusiaan di KitaPeduli.\n\nProgram ini mendampingi para lansia prasejahtera agar tetap memperoleh kebutuhan pangan bergizi seperti beras, telur, minyak goreng, serta pemeriksaan tekanan darah dan vitamin bulanan.','images/campaigns/kemanusiaan.svg',30000000.00,0.00,0,'2026-11-13','2027-01-14','Sahabat Lansia Berdaya (Demo)','Organisasi Sosial','Organisasi pendamping kesejahteraan lansia prasejahtera.',0,1,'active','2026-11-25 08:22:06','2026-11-25 08:22:06'),(5,5,1,'Dukungan Gizi & Asrama Panti Asuhan Harapan Bangsa (Demo)','dukungan-gizi-dan-asrama-panti-asuhan-harapan-bangsa','Bantu pemenuhan kebutuhan pangan harian, kasur asrama, dan perlengkapan belajar untuk 45 anak asuh.','Kampanye demonstrasi bantuan panti asuhan pada aplikasi KitaPeduli.\n\nPanti Asuhan Harapan Bangsa merawat 45 anak yatim dan dhuafa usia sekolah dasar hingga menengah. Donasi yang terkumpul digunakan untuk pengadaan bahan pangan bergizi, perbaikan tempat tidur asrama, serta biaya penunjang sekolah.','images/campaigns/panti-asuhan.svg',35000000.00,0.00,0,'2026-11-07','2027-01-04','Pengurus Panti Asuhan Harapan Bangsa (Demo)','Yayasan Panti Asuhan','Yayasan pengasuhan dan pendidikan anak yatim piatu.',0,1,'active','2026-11-25 08:22:06','2026-11-25 08:22:06'),(6,6,1,'Konservasi 10.000 Bibit Mangrove & Air Bersih Desa (Demo)','konservasi-10000-bibit-mangrove-dan-air-bersih-desa','Penanaman sabuk hijau mangrove penahan abrasi sekaligus instalasi filter air bersih warga pesisir.','Kampanye demonstrasi pelestarian lingkungan hidup pada aplikasi KitaPeduli.\n\nBersama kelompok masyarakat pesisir, program ini melakukan pembibitan dan penanaman 10.000 pohon mangrove untuk menahan abrasi pantai serta membangun instalasi penyaringan air bersih komunal.','images/campaigns/lingkungan.svg',45000000.00,0.00,0,'2026-10-31','2027-01-24','Inisiatif Hijau Lestari (Demo)','Komunitas Lingkungan','Komunitas konservasi ekosistem pesisir dan air bersih desa.',1,1,'active','2026-11-25 08:22:06','2026-11-25 08:22:06');
/*!40000 ALTER TABLE `campaigns` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `categories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `icon` varchar(60) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `categories_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES (1,'Kesehatan','kesehatan','heart-pulse','Bantuan biaya pengobatan medis, alat kesehatan, dan layanan klinik masyarakat.','2026-11-25 08:22:06','2026-11-25 08:22:06'),(2,'Pendidikan','pendidikan','graduation-cap','Beasiswa pelajar, renovasi ruang belajar, dan penyediaan buku bacaan anak.','2026-11-25 08:22:06','2026-11-25 08:22:06'),(3,'Bencana Alam','bencana-alam','shield-alert','Tanggap darurat bencana, dapur umum, dan pemulihan hunian warga terdampak.','2026-11-25 08:22:06','2026-11-25 08:22:06'),(4,'Kemanusiaan','kemanusiaan','users','Paket pangan pokok, bantuan lansia prasejahtera, dan solidaritas sosial.','2026-11-25 08:22:06','2026-11-25 08:22:06'),(5,'Panti Asuhan','panti-asuhan','home','Dukungan gizi, perlengkapan sekolah, dan perbaikan fasilitas panti asuhan.','2026-11-25 08:22:06','2026-11-25 08:22:06'),(6,'Lingkungan','lingkungan','leaf','Penanaman pohon mangrove, sarana air bersih desa, dan konservasi alam.','2026-11-25 08:22:06','2026-11-25 08:22:06');
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `donation_reactions`
--

DROP TABLE IF EXISTS `donation_reactions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `donation_reactions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `donation_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `donation_reactions_donation_id_user_id_unique` (`donation_id`,`user_id`),
  KEY `donation_reactions_user_id_foreign` (`user_id`),
  CONSTRAINT `donation_reactions_donation_id_foreign` FOREIGN KEY (`donation_id`) REFERENCES `donations` (`id`) ON DELETE CASCADE,
  CONSTRAINT `donation_reactions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `donation_reactions`
--

LOCK TABLES `donation_reactions` WRITE;
/*!40000 ALTER TABLE `donation_reactions` DISABLE KEYS */;
INSERT INTO `donation_reactions` VALUES (1,1,2,'2026-11-25 08:22:06','2026-11-25 08:22:06'),(2,1,3,'2026-11-25 08:22:06','2026-11-25 08:22:06'),(3,2,2,'2026-11-25 08:22:06','2026-11-25 08:22:06'),(4,2,3,'2026-11-25 08:22:06','2026-11-25 08:22:06'),(5,3,2,'2026-11-25 08:22:06','2026-11-25 08:22:06'),(6,3,3,'2026-11-25 08:22:06','2026-11-25 08:22:06'),(7,4,2,'2026-11-25 08:22:06','2026-11-25 08:22:06'),(8,4,3,'2026-11-25 08:22:06','2026-11-25 08:22:06'),(9,5,2,'2026-11-25 08:22:06','2026-11-25 08:22:06'),(10,5,3,'2026-11-25 08:22:06','2026-11-25 08:22:06');
/*!40000 ALTER TABLE `donation_reactions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `donations`
--

DROP TABLE IF EXISTS `donations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `donations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `campaign_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `donor_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `donor_email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `donor_phone` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `amount` decimal(15,2) NOT NULL,
  `donor_message` text COLLATE utf8mb4_unicode_ci,
  `is_anonymous` tinyint(1) NOT NULL DEFAULT '0',
  `status` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `amen_count` int unsigned NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `donations_campaign_id_status_index` (`campaign_id`,`status`),
  KEY `donations_user_id_created_at_index` (`user_id`,`created_at`),
  KEY `donations_status_index` (`status`),
  CONSTRAINT `donations_campaign_id_foreign` FOREIGN KEY (`campaign_id`) REFERENCES `campaigns` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `donations_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `donations`
--

LOCK TABLES `donations` WRITE;
/*!40000 ALTER TABLE `donations` DISABLE KEYS */;
INSERT INTO `donations` VALUES (1,1,2,'Andi Pratama','andi@kitapeduli.test',NULL,500000.00,'Semoga adik-adik lekas pulih dan selalu diberi kekuatan serta kesehatan.',0,'success',2,'2026-11-25 08:22:06','2026-11-25 08:22:06'),(2,1,3,'Siti Rahmawati','siti@kitapeduli.test',NULL,750000.00,'Titip doa terbaik untuk kesembuhan anak-anak pejuang kesehatan. Semangat!',1,'success',2,'2026-11-25 08:22:06','2026-11-25 08:22:06'),(3,1,NULL,'Rizky Hidayat (Guest)','rizky.guest@example.com',NULL,250000.00,'Semoga bantuan simulasi ini bermanfaat untuk kebutuhan nutrisi adik-adik.',0,'success',2,'2026-11-25 08:22:06','2026-11-25 08:22:06'),(4,2,4,'Budi Santoso','budi@kitapeduli.test',NULL,1000000.00,'Semoga perpustakaan desa segera selesai direnovasi dan anak-anak makin rajin membaca.',0,'success',2,'2026-11-25 08:22:06','2026-11-25 08:22:06'),(5,3,NULL,'Dewi Lestari','dewi.guest@example.com',NULL,600000.00,'Semoga warga yang terdampak banjir diberikan keselamatan dan kemudahan.',1,'success',2,'2026-11-25 08:22:06','2026-11-25 08:22:06');
/*!40000 ALTER TABLE `donations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fund_disbursement_reports`
--

DROP TABLE IF EXISTS `fund_disbursement_reports`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `fund_disbursement_reports` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `campaign_id` bigint unsigned NOT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `recipient_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `purpose` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `document_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `disbursed_at` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fund_disbursement_reports_created_by_foreign` (`created_by`),
  KEY `fund_disbursement_reports_campaign_id_disbursed_at_index` (`campaign_id`,`disbursed_at`),
  CONSTRAINT `fund_disbursement_reports_campaign_id_foreign` FOREIGN KEY (`campaign_id`) REFERENCES `campaigns` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fund_disbursement_reports_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fund_disbursement_reports`
--

LOCK TABLES `fund_disbursement_reports` WRITE;
/*!40000 ALTER TABLE `fund_disbursement_reports` DISABLE KEYS */;
INSERT INTO `fund_disbursement_reports` VALUES (1,1,1,'Pencairan Dana Tahap 1: Pembelian Paket Nutrisi Medis (Simulasi)',750000.00,'Yayasan Sehat Nusantara (Posko Pendampingan Anak)','Pengadaan 15 paket susu medis dan suplemen pemulihan pasien anak','Catatan transparansi penyaluran dana simulasi untuk pembelian kebutuhan nutrisi medis khusus anak-anak dampingan tahap pertama.',NULL,'2026-11-23','2026-11-25 08:22:06','2026-11-25 08:22:06'),(2,2,1,'Pencairan Dana Tahap 1: Pengadaan Buku dan Material Rak (Simulasi)',500000.00,'Komunitas Lentera Pendidikan Pesisir','Pembelian paket buku literasi dasar dan perlengkapan rak baca','Catatan laporan penyaluran dana simulasi untuk mendukung fasilitas perpustakaan anak pesisir.',NULL,'2026-11-24','2026-11-25 08:22:06','2026-11-25 08:22:06');
/*!40000 ALTER TABLE `fund_disbursement_reports` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_batches`
--

LOCK TABLES `job_batches` WRITE;
/*!40000 ALTER TABLE `job_batches` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_batches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000002_create_jobs_table',1),(4,'0001_01_01_000003_create_personal_access_tokens_table',1),(5,'2026_01_01_000001_create_categories_table',1),(6,'2026_01_01_000002_create_campaigns_table',1),(7,'2026_01_01_000003_create_donations_table',1),(8,'2026_01_01_000004_create_payments_table',1),(9,'2026_01_01_000005_create_campaign_updates_table',1),(10,'2026_01_01_000006_create_fund_disbursement_reports_table',1),(11,'2026_01_01_000007_create_donation_reactions_table',1);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `payments`
--

DROP TABLE IF EXISTS `payments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `payments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `donation_id` bigint unsigned NOT NULL,
  `payment_method` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `reference` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `access_token_hash` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `status` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `instruction_payload` json DEFAULT NULL,
  `paid_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `payments_donation_id_unique` (`donation_id`),
  UNIQUE KEY `payments_reference_unique` (`reference`),
  KEY `payments_payment_method_index` (`payment_method`),
  KEY `payments_status_index` (`status`),
  CONSTRAINT `payments_donation_id_foreign` FOREIGN KEY (`donation_id`) REFERENCES `donations` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payments`
--

LOCK TABLES `payments` WRITE;
/*!40000 ALTER TABLE `payments` DISABLE KEYS */;
INSERT INTO `payments` VALUES (1,1,'qris','KP-DEMO-0001QRIS','b4227facad79c21a6728c38703e8161876c7172e613fcb453361db341316f765',500000.00,'paid','{\"steps\": [\"Halaman ini menggunakan QRIS simulasi demo untuk keperluan demonstrasi akademik PPBO.\", \"Periksa nominal donasi (Rp 500.000) dan kode referensi transaksi (KP-DEMO-0001QRIS).\", \"Anda tidak perlu memindai kode QR menggunakan aplikasi perbankan atau e-wallet sungguhan.\", \"Tekan tombol Simulasikan Pembayaran QRIS Berhasil untuk menyelesaikan pembayaran simulasi.\"], \"amount\": 500000, \"details\": {\"nmid_demo\": \"ID-DEMO-KITAPEDULI-f14201e6\", \"qr_payload\": \"KITAPEDULI-SIMULATION-QRIS|REF:KP-DEMO-0001QRIS|AMT:500000.00|NOTICE:DEMO_ONLY_NOT_REAL_PAYMENT\", \"terminal_id\": \"SIM-QR-01\", \"merchant_name\": \"KitaPeduli Crowdfunding (Simulasi Demo)\", \"supported_apps\": \"Aplikasi e-wallet dan mobile banking (mode simulasi)\"}, \"summary\": \"Periksa detail QRIS demo di bawah ini, lalu tekan tombol Simulasikan Pembayaran QRIS Berhasil.\", \"reference\": \"KP-DEMO-0001QRIS\", \"donor_name\": \"Andi Pratama\", \"method_code\": \"qris\", \"method_label\": \"QRIS (Simulasi Demo)\", \"is_simulation\": true, \"campaign_title\": \"Bantu Pengobatan & Nutrisi Anak Pejuang Kesehatan (Demo)\", \"formatted_amount\": \"Rp 500.000\", \"instruction_title\": \"Instruksi Pembayaran QRIS Simulasi\", \"simulation_notice\": \"SIMULASI / DEMO: Bukan pembayaran nyata\", \"confirm_button_label\": \"Simulasikan Pembayaran QRIS Berhasil\"}','2026-11-25 02:22:06','2026-11-25 08:22:06','2026-11-25 08:22:06'),(2,2,'bank_transfer','KP-DEMO-0002BANK','7b5f700288b237196009600e81354fe2d83e5a4b2fe0aaf01901d546b5d45148',750000.00,'paid','{\"steps\": [\"Nomor rekening di atas adalah rekening simulasi demo, jangan melakukan transfer uang sungguhan.\", \"Periksa kesesuaian nominal transfer (Rp 750.000) dan nomor referensi (KP-DEMO-0002BANK).\", \"Simulasi ini tidak memerlukan unggah bukti transfer maupun verifikasi manual Admin.\", \"Tekan tombol Simulasikan Transfer Bank Berhasil untuk mengonfirmasi pembayaran simulasi.\"], \"amount\": 750000, \"details\": {\"bank_code\": \"999 (Kode Bank Demo)\", \"bank_name\": \"Bank Nasional Simulasi (BNS Demo)\", \"transfer_note\": \"Donasi KP-DEMO-0002BANK\", \"account_holder\": \"Yayasan KitaPeduli Indonesia (Simulasi)\", \"account_number\": \"8800 2243 607545\"}, \"summary\": \"Periksa informasi rekening simulasi di bawah ini, lalu tekan tombol Simulasikan Transfer Bank Berhasil.\", \"reference\": \"KP-DEMO-0002BANK\", \"donor_name\": \"Siti Rahmawati\", \"method_code\": \"bank_transfer\", \"method_label\": \"Transfer Bank / ATM (Simulasi Demo)\", \"is_simulation\": true, \"campaign_title\": \"Bantu Pengobatan & Nutrisi Anak Pejuang Kesehatan (Demo)\", \"formatted_amount\": \"Rp 750.000\", \"instruction_title\": \"Instruksi Transfer Bank / ATM Simulasi\", \"simulation_notice\": \"SIMULASI / DEMO: Bukan pembayaran nyata\", \"confirm_button_label\": \"Simulasikan Transfer Bank Berhasil\"}','2026-11-25 02:22:06','2026-11-25 08:22:06','2026-11-25 08:22:06'),(3,3,'qris','KP-DEMO-0003GUES','9dfef014bd94f37d78e033455f0ad1e37157e2b5c29fab208dbd1f629f72cc6d',250000.00,'paid','{\"steps\": [\"Halaman ini menggunakan QRIS simulasi demo untuk keperluan demonstrasi akademik PPBO.\", \"Periksa nominal donasi (Rp 250.000) dan kode referensi transaksi (KP-DEMO-0003GUES).\", \"Anda tidak perlu memindai kode QR menggunakan aplikasi perbankan atau e-wallet sungguhan.\", \"Tekan tombol Simulasikan Pembayaran QRIS Berhasil untuk menyelesaikan pembayaran simulasi.\"], \"amount\": 250000, \"details\": {\"nmid_demo\": \"ID-DEMO-KITAPEDULI-52d710f0\", \"qr_payload\": \"KITAPEDULI-SIMULATION-QRIS|REF:KP-DEMO-0003GUES|AMT:250000.00|NOTICE:DEMO_ONLY_NOT_REAL_PAYMENT\", \"terminal_id\": \"SIM-QR-01\", \"merchant_name\": \"KitaPeduli Crowdfunding (Simulasi Demo)\", \"supported_apps\": \"Aplikasi e-wallet dan mobile banking (mode simulasi)\"}, \"summary\": \"Periksa detail QRIS demo di bawah ini, lalu tekan tombol Simulasikan Pembayaran QRIS Berhasil.\", \"reference\": \"KP-DEMO-0003GUES\", \"donor_name\": \"Rizky Hidayat (Guest)\", \"method_code\": \"qris\", \"method_label\": \"QRIS (Simulasi Demo)\", \"is_simulation\": true, \"campaign_title\": \"Bantu Pengobatan & Nutrisi Anak Pejuang Kesehatan (Demo)\", \"formatted_amount\": \"Rp 250.000\", \"instruction_title\": \"Instruksi Pembayaran QRIS Simulasi\", \"simulation_notice\": \"SIMULASI / DEMO: Bukan pembayaran nyata\", \"confirm_button_label\": \"Simulasikan Pembayaran QRIS Berhasil\"}','2026-11-25 02:22:06','2026-11-25 08:22:06','2026-11-25 08:22:06'),(4,4,'bank_transfer','KP-DEMO-0004EDUC','003911df95153b83afcfcf780c7fb35ef4dc20028d45d9634e4e2ae3a49fc2b1',1000000.00,'paid','{\"steps\": [\"Nomor rekening di atas adalah rekening simulasi demo, jangan melakukan transfer uang sungguhan.\", \"Periksa kesesuaian nominal transfer (Rp 1.000.000) dan nomor referensi (KP-DEMO-0004EDUC).\", \"Simulasi ini tidak memerlukan unggah bukti transfer maupun verifikasi manual Admin.\", \"Tekan tombol Simulasikan Transfer Bank Berhasil untuk mengonfirmasi pembayaran simulasi.\"], \"amount\": 1000000, \"details\": {\"bank_code\": \"999 (Kode Bank Demo)\", \"bank_name\": \"Bank Nasional Simulasi (BNS Demo)\", \"transfer_note\": \"Donasi KP-DEMO-0004EDUC\", \"account_holder\": \"Yayasan KitaPeduli Indonesia (Simulasi)\", \"account_number\": \"8800 8906 753955\"}, \"summary\": \"Periksa informasi rekening simulasi di bawah ini, lalu tekan tombol Simulasikan Transfer Bank Berhasil.\", \"reference\": \"KP-DEMO-0004EDUC\", \"donor_name\": \"Budi Santoso\", \"method_code\": \"bank_transfer\", \"method_label\": \"Transfer Bank / ATM (Simulasi Demo)\", \"is_simulation\": true, \"campaign_title\": \"Renovasi Perpustakaan & Beasiswa Anak Pesisir (Demo)\", \"formatted_amount\": \"Rp 1.000.000\", \"instruction_title\": \"Instruksi Transfer Bank / ATM Simulasi\", \"simulation_notice\": \"SIMULASI / DEMO: Bukan pembayaran nyata\", \"confirm_button_label\": \"Simulasikan Transfer Bank Berhasil\"}','2026-11-25 02:22:06','2026-11-25 08:22:06','2026-11-25 08:22:06'),(5,5,'qris','KP-DEMO-0005FLOD','9b6d613947db8227ce5b1a1e58f21713eab2912e744a651c066845dac68b8ee8',600000.00,'paid','{\"steps\": [\"Halaman ini menggunakan QRIS simulasi demo untuk keperluan demonstrasi akademik PPBO.\", \"Periksa nominal donasi (Rp 600.000) dan kode referensi transaksi (KP-DEMO-0005FLOD).\", \"Anda tidak perlu memindai kode QR menggunakan aplikasi perbankan atau e-wallet sungguhan.\", \"Tekan tombol Simulasikan Pembayaran QRIS Berhasil untuk menyelesaikan pembayaran simulasi.\"], \"amount\": 600000, \"details\": {\"nmid_demo\": \"ID-DEMO-KITAPEDULI-fec60f94\", \"qr_payload\": \"KITAPEDULI-SIMULATION-QRIS|REF:KP-DEMO-0005FLOD|AMT:600000.00|NOTICE:DEMO_ONLY_NOT_REAL_PAYMENT\", \"terminal_id\": \"SIM-QR-01\", \"merchant_name\": \"KitaPeduli Crowdfunding (Simulasi Demo)\", \"supported_apps\": \"Aplikasi e-wallet dan mobile banking (mode simulasi)\"}, \"summary\": \"Periksa detail QRIS demo di bawah ini, lalu tekan tombol Simulasikan Pembayaran QRIS Berhasil.\", \"reference\": \"KP-DEMO-0005FLOD\", \"donor_name\": \"Dewi Lestari\", \"method_code\": \"qris\", \"method_label\": \"QRIS (Simulasi Demo)\", \"is_simulation\": true, \"campaign_title\": \"Dapur Umum & Paket Logistik Warga Terdampak Banjir (Demo)\", \"formatted_amount\": \"Rp 600.000\", \"instruction_title\": \"Instruksi Pembayaran QRIS Simulasi\", \"simulation_notice\": \"SIMULASI / DEMO: Bukan pembayaran nyata\", \"confirm_button_label\": \"Simulasikan Pembayaran QRIS Berhasil\"}','2026-11-25 02:22:06','2026-11-25 08:22:06','2026-11-25 08:22:06');
/*!40000 ALTER TABLE `payments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `personal_access_tokens`
--

DROP TABLE IF EXISTS `personal_access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`),
  KEY `personal_access_tokens_expires_at_index` (`expires_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `personal_access_tokens`
--

LOCK TABLES `personal_access_tokens` WRITE;
/*!40000 ALTER TABLE `personal_access_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `personal_access_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'donor',
  `status` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `users_role_index` (`role`),
  KEY `users_status_index` (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Admin Utama KitaPeduli','admin@kitapeduli.test','081100001111','2026-11-25 08:22:05','$2y$12$nk1fsS1IwGFAm16YIl0.o.NFM0FowORo1BDm2lChqGwpl4yd9TqnG','admin','active',NULL,'2026-11-25 08:22:05','2026-11-25 08:22:05'),(2,'Andi Pratama','andi@kitapeduli.test','081234567801','2026-11-25 08:22:05','$2y$12$AaEME5cNk6mDE.JpmQtqDuANnXl4T.lu71rNH7KWKSRm60b9ksAk6','donor','active',NULL,'2026-11-25 08:22:06','2026-11-25 08:22:06'),(3,'Siti Rahmawati','siti@kitapeduli.test','081234567802','2026-11-25 08:22:06','$2y$12$ldzUWQNSZtn52xgdNhv49.0EhiiMwx/2KskwfdvrZm7/DPEjoGTba','donor','active',NULL,'2026-11-25 08:22:06','2026-11-25 08:22:06'),(4,'Budi Santoso','budi@kitapeduli.test','081234567803','2026-11-25 08:22:06','$2y$12$ql0Kf1ZscDH/P/S/n/J1p.jsuJkEmnlW4TcRkF07aZvwh5OuxlHJy','donor','active',NULL,'2026-11-25 08:22:06','2026-11-25 08:22:06');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-11-25 22:22:07

/*M!999999\- enable the sandbox mode */ 
-- MariaDB dump 10.19-11.7.2-MariaDB, for Linux (x86_64)
--
-- Host: localhost    Database: DBPendaftaran
-- ------------------------------------------------------
-- Server version	11.7.2-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*M!100616 SET @OLD_NOTE_VERBOSITY=@@NOTE_VERBOSITY, NOTE_VERBOSITY=0 */;

--
-- Table structure for table `MstClub`
--

DROP TABLE IF EXISTS `MstClub`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `MstClub` (
  `KDPROP` varchar(2) DEFAULT NULL,
  `NAMAPROP` varchar(100) DEFAULT NULL,
  `KDJENIS` varchar(1) DEFAULT NULL,
  `JENIS` varchar(5) DEFAULT NULL,
  `KDKOTA` varchar(4) DEFAULT NULL,
  `NAMAKOTA` varchar(100) DEFAULT NULL,
  `KDCLUB` varchar(2) DEFAULT NULL,
  `NAMACLUB` varchar(100) DEFAULT NULL,
  `MSTNIAS` varchar(10) DEFAULT NULL,
  `CPERSON` varchar(30) DEFAULT NULL,
  `KETERANGAN` varchar(50) DEFAULT NULL,
  `IDCLUB` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`IDCLUB`)
) ENGINE=InnoDB AUTO_INCREMENT=129 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `MstClub`
--

LOCK TABLES `MstClub` WRITE;
/*!40000 ALTER TABLE `MstClub` DISABLE KEYS */;
INSERT INTO `MstClub` VALUES
('05','Jawa Timur','1','Kab','3501','Pacitan','01','POSSI KAB.PACITAN','0513501010',NULL,NULL,1),
('05','Jawa Timur','1','Kab','3502','Ponorogo','03','TUNGGUL NAGA DC','0513502030',NULL,NULL,2),
('05','Jawa Timur','1','Kab','3502','Ponorogo','04','PONOROGO DC','0513502040',NULL,NULL,3),
('05','Jawa Timur','1','Kab','3502','Ponorogo','05','WENGKER DC','0513502050',NULL,NULL,4),
('05','Jawa Timur','1','Kab','3502','Ponorogo','01','BAHARI DIVING CLUB','0513502010',NULL,NULL,5),
('05','Jawa Timur','1','Kab','3502','Ponorogo','06','DEWARUCI PONOROGO','0513502060',NULL,NULL,6),
('05','Jawa Timur','1','Kab','3502','Ponorogo','02','SCKOUT DIVING CLUB','0513502020',NULL,NULL,7),
('05','Jawa Timur','1','Kab','3503','Trenggalek','02','LION DC','0513503020',NULL,NULL,8),
('05','Jawa Timur','1','Kab','3503','Trenggalek','01','JWALITA SUB AQUATIC CLUB','0513503010',NULL,NULL,9),
('05','Jawa Timur','1','Kab','3504','Tulungagung','01','BARRACUDA AQUATIC','0513504010',NULL,NULL,10),
('05','Jawa Timur','1','Kab','3504','Tulungagung','03','CROCODILE','0513504030',NULL,NULL,11),
('05','Jawa Timur','1','Kab','3504','Tulungagung','02','STAR LANE INDONESIA','0513504020',NULL,NULL,12),
('05','Jawa Timur','1','Kab','3505','Blitar','02','PRADAH DC','0513505020',NULL,NULL,13),
('05','Jawa Timur','1','Kab','3505','Blitar','01','MARLIN','0513505010',NULL,NULL,14),
('05','Jawa Timur','1','Kab','3505','Blitar','04','TIRTA KHARISMA DC','0513505040',NULL,NULL,15),
('05','Jawa Timur','1','Kab','3505','Blitar','03','SAILFISH DC','0513505030',NULL,NULL,16),
('05','Jawa Timur','1','Kab','3506','Kediri','04','BIMA SAKTI DC','0513506040',NULL,NULL,17),
('05','Jawa Timur','1','Kab','3506','Kediri','01','GARUDA DC','0513506010',NULL,NULL,18),
('05','Jawa Timur','1','Kab','3506','Kediri','02','MARIO DC','0513506020',NULL,NULL,19),
('05','Jawa Timur','1','Kab','3506','Kediri','03','ANUGRAH DC','0513506030',NULL,NULL,20),
('05','Jawa Timur','1','Kab','3506','Kediri','05','HENS DC','0513506050',NULL,NULL,21),
('05','Jawa Timur','1','Kab','3507','Malang','01','CORENA','0513507010',NULL,NULL,22),
('05','Jawa Timur','1','Kab','3507','Malang','02','FBA DC','0513507020',NULL,NULL,23),
('05','Jawa Timur','1','Kab','3507','Malang','03','GLOBE AQUATIC','0513507030',NULL,NULL,24),
('05','Jawa Timur','1','Kab','3507','Malang','04','OSCAR DC','0513507040',NULL,NULL,25),
('05','Jawa Timur','1','Kab','3508','Lumajang','03','PELANGI DC','0513508030',NULL,NULL,26),
('05','Jawa Timur','1','Kab','3508','Lumajang','01','TIRTA MERU','0513508010',NULL,NULL,27),
('05','Jawa Timur','1','Kab','3508','Lumajang','02','SEA STAR','0513508020',NULL,NULL,28),
('05','Jawa Timur','1','Kab','3508','Lumajang','04','TUNAS HARAPAN DC','0513508040',NULL,NULL,29),
('05','Jawa Timur','1','Kab','3509','Jember','01','HQL. ALAM','0513509010',NULL,NULL,30),
('05','Jawa Timur','1','Kab','3509','Jember','02','POSSI JEMBER','0513509020',NULL,NULL,31),
('05','Jawa Timur','1','Kab','3510','Banyuwangi','04','OSING AQUATIC DIVING CLUB','0513510040',NULL,NULL,32),
('05','Jawa Timur','1','Kab','3510','Banyuwangi','05','SMANTA DIVING CLUB','0513510050',NULL,NULL,33),
('05','Jawa Timur','1','Kab','3510','Banyuwangi','01','CHARLIE DIVING CLUB','0513510010',NULL,NULL,34),
('05','Jawa Timur','1','Kab','3510','Banyuwangi','02','DOLPHINE DIVING CLUB','0513510020',NULL,NULL,35),
('05','Jawa Timur','1','Kab','3510','Banyuwangi','03','MUNGSING DIVING CLUB','0513510030',NULL,NULL,36),
('05','Jawa Timur','1','Kab','3511','Bondowoso','01','POSSI KAB. BONDOWOSO','0513511010',NULL,NULL,37),
('05','Jawa Timur','1','Kab','3512','Situbondo','03','POSSI KAB. SITUBONDO','0513512030',NULL,NULL,38),
('05','Jawa Timur','1','Kab','3512','Situbondo','01','BALURAN DIVING CLUB','0513512010',NULL,NULL,39),
('05','Jawa Timur','1','Kab','3512','Situbondo','02','PASIR PUTIH DC','0513512020',NULL,NULL,40),
('05','Jawa Timur','1','Kab','3513','Probolinggo','01','POSSI KAB.PROBOLINGGO','0513513010',NULL,NULL,41),
('05','Jawa Timur','1','Kab','3514','Pasuruan','01','PAS DC','0513514010',NULL,NULL,42),
('05','Jawa Timur','1','Kab','3514','Pasuruan','05','MANDIRI DC','0513514050',NULL,NULL,43),
('05','Jawa Timur','1','Kab','3514','Pasuruan','04','KKGO DC','0513514040',NULL,NULL,44),
('05','Jawa Timur','1','Kab','3514','Pasuruan','03','BANYU BIRU','0513514030',NULL,NULL,45),
('05','Jawa Timur','1','Kab','3514','Pasuruan','02','TIGER','0513514020',NULL,NULL,46),
('05','Jawa Timur','1','Kab','3515','Sidoarjo','02','DELTA DC','0513515020',NULL,NULL,47),
('05','Jawa Timur','1','Kab','3515','Sidoarjo','01','AVIATION','0513515010',NULL,NULL,48),
('05','Jawa Timur','1','Kab','3515','Sidoarjo','03','NEMO DC','0513515030',NULL,NULL,49),
('05','Jawa Timur','1','Kab','3516','Mojokerto','01','ATLANTIC DC','0513516010',NULL,NULL,50),
('05','Jawa Timur','1','Kab','3517','Jombang','02','NAGAPASA DC','0513517020',NULL,NULL,51),
('05','Jawa Timur','1','Kab','3517','Jombang','01','DEWARUCI','0513517010',NULL,NULL,52),
('05','Jawa Timur','1','Kab','3518','Nganjuk','02','TIRTA MULYA','0513518020',NULL,NULL,53),
('05','Jawa Timur','1','Kab','3518','Nganjuk','03','THE LEGEND DC','0513518030',NULL,NULL,54),
('05','Jawa Timur','1','Kab','3518','Nganjuk','01','SENDANG JAYA TIRTA','0513518010',NULL,NULL,55),
('05','Jawa Timur','1','Kab','3519','Madiun','01','POSSI KAB. MADIUN','0513519010',NULL,NULL,56),
('05','Jawa Timur','1','Kab','3519','Madiun','02','PANGERAN TIMOER DC','0513519020',NULL,NULL,57),
('05','Jawa Timur','1','Kab','3520','Magetan','01','LOUHAN MANUNGGAL','0513520010',NULL,NULL,58),
('05','Jawa Timur','1','Kab','3521','Ngawi','01','POSSI KAB. NGAWI','0513521010',NULL,NULL,59),
('05','Jawa Timur','1','Kab','3522','Bojonegoro','01','POSSI KAB. BOJONEGORO','0513522010',NULL,NULL,60),
('05','Jawa Timur','1','Kab','3522','Bojonegoro','02','BOJONEGORO WATER SPORT','0513522020',NULL,NULL,61),
('05','Jawa Timur','1','Kab','3522','Bojonegoro','03','POSEIDON DC','0513522030',NULL,NULL,62),
('05','Jawa Timur','1','Kab','3523','Tuban','01','TIRTA LAWE','0513523010',NULL,NULL,63),
('05','Jawa Timur','1','Kab','3524','Lamongan','01','POSSI KAB. LAMONGAN','0513524010',NULL,NULL,64),
('05','Jawa Timur','1','Kab','3525','Gresik','01','PETROKIMIA DC','0513525010',NULL,NULL,65),
('05','Jawa Timur','1','Kab','3526','Bangkalan','01','POSSI KAB. BANGKALAN','0513526010',NULL,NULL,66),
('05','Jawa Timur','1','Kab','3527','Sampang','01','POSSI KAB.SAMPANG','0513527010',NULL,NULL,67),
('05','Jawa Timur','1','Kab','3528','Pamekasan','01','POSSI KAB.PAMEKASAN','0513528010',NULL,NULL,68),
('05','Jawa Timur','1','Kab','3529','Sumenep','01','POSSI KAB.SUMENEP','0513529010',NULL,NULL,69),
('05','Jawa Timur','0','Kota','3571','Kediri','06','GALAXY AQUATIC DC','0503571060',NULL,NULL,70),
('05','Jawa Timur','0','Kota','3571','Kediri','01','ARWANA','0503571010',NULL,NULL,71),
('05','Jawa Timur','0','Kota','3571','Kediri','02','JOYOBOYO','0503571020',NULL,NULL,72),
('05','Jawa Timur','0','Kota','3571','Kediri','03','SURYA AQUATIC','0503571030',NULL,NULL,73),
('05','Jawa Timur','0','Kota','3571','Kediri','04','DOHO ELS','0503571040',NULL,NULL,74),
('05','Jawa Timur','0','Kota','3571','Kediri','05','DRAGON AQUATIC','0503571050',NULL,NULL,75),
('05','Jawa Timur','0','Kota','3572','Blitar','01','POSSI KOTA BLITAR','0503572010',NULL,NULL,76),
('05','Jawa Timur','0','Kota','3573','Malang','04','ORCA','0503573040',NULL,NULL,77),
('05','Jawa Timur','0','Kota','3573','Malang','03','CLASS','0503573030',NULL,NULL,78),
('05','Jawa Timur','0','Kota','3573','Malang','02','AMARTA','0503573020',NULL,NULL,79),
('05','Jawa Timur','0','Kota','3573','Malang','01','FASTER BETTER DC','0503573010',NULL,NULL,80),
('05','Jawa Timur','0','Kota','3573','Malang','06','TIRTA NIRWANA','0503573060',NULL,NULL,81),
('05','Jawa Timur','0','Kota','3573','Malang','05','TAMAN HARAPAN','0503573050',NULL,NULL,82),
('05','Jawa Timur','0','Kota','3574','Probolinggo','02','BROMO TIRTA','0503574020',NULL,NULL,83),
('05','Jawa Timur','0','Kota','3574','Probolinggo','01','BATRA DC','0503574010',NULL,NULL,84),
('05','Jawa Timur','0','Kota','3575','Pasuruan','03','SATRIA DHARMA AQUATIC','0503575030',NULL,NULL,85),
('05','Jawa Timur','0','Kota','3575','Pasuruan','02','TIMBUL DC','0503575020',NULL,NULL,86),
('05','Jawa Timur','0','Kota','3575','Pasuruan','01','TIRTA SUROPATI','0503575010',NULL,NULL,87),
('05','Jawa Timur','0','Kota','3576','Mojokerto','01','POSSI KOTA MOJOKERTO','0503576010',NULL,NULL,88),
('05','Jawa Timur','0','Kota','3577','Madiun','02','MANTARAY','0503577020',NULL,NULL,89),
('05','Jawa Timur','0','Kota','3577','Madiun','03','MARTINA','0503577030',NULL,NULL,90),
('05','Jawa Timur','0','Kota','3577','Madiun','01','PUSPITA','0503577010',NULL,NULL,91),
('05','Jawa Timur','0','Kota','3578','Surabaya','05','CATALINA SURABAYA DC','0503578050',NULL,NULL,92),
('05','Jawa Timur','0','Kota','3578','Surabaya','01','EAGLE DC','0503578010',NULL,NULL,93),
('05','Jawa Timur','0','Kota','3578','Surabaya','02','HIU','0503578020',NULL,NULL,94),
('05','Jawa Timur','0','Kota','3578','Surabaya','03','POR SURYANAGA','0503578030',NULL,NULL,95),
('05','Jawa Timur','0','Kota','3578','Surabaya','04','SBAC','0503578040',NULL,NULL,96),
('05','Jawa Timur','0','Kota','3578','Surabaya','06','PRIMUS DC','0503578060',NULL,NULL,97),
('05','Jawa Timur','0','Kota','3578','Surabaya','07','PENGUIN DC','0503578070',NULL,NULL,98),
('05','Jawa Timur','0','Kota','3578','Surabaya','08','KARPIL DC','0503578080',NULL,NULL,99),
('05','Jawa Timur','0','Kota','3578','Surabaya','09','CELONIA DC','0503578090',NULL,NULL,100),
('05','Jawa Timur','0','Kota','3579','Batu','01','LOTUS AQUATIC CLUB','0503579010',NULL,NULL,101),
('00','Admin','0','Admin','0000','Admin','00','Admin','0000000000',NULL,NULL,128);
/*!40000 ALTER TABLE `MstClub` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
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
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
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
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
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
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL,
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
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) unsigned NOT NULL,
  `reserved_at` int(10) unsigned DEFAULT NULL,
  `available_at` int(10) unsigned NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
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
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES
(1,'0001_01_01_000000_create_users_table',1),
(2,'0001_01_01_000001_create_cache_table',1),
(3,'0001_01_01_000002_create_jobs_table',1),
(4,'2025_05_28_073601_create_permission_tables',1);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `model_has_permissions`
--

DROP TABLE IF EXISTS `model_has_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `model_has_permissions` (
  `permission_id` bigint(20) unsigned NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `model_has_permissions`
--

LOCK TABLES `model_has_permissions` WRITE;
/*!40000 ALTER TABLE `model_has_permissions` DISABLE KEYS */;
/*!40000 ALTER TABLE `model_has_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `model_has_roles`
--

DROP TABLE IF EXISTS `model_has_roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `model_has_roles` (
  `role_id` bigint(20) unsigned NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `model_has_roles`
--

LOCK TABLES `model_has_roles` WRITE;
/*!40000 ALTER TABLE `model_has_roles` DISABLE KEYS */;
INSERT INTO `model_has_roles` VALUES
(1,'App\\Models\\User',1),
(1,'App\\Models\\User',2),
(3,'App\\Models\\User',7),
(3,'App\\Models\\User',8),
(3,'App\\Models\\User',9);
/*!40000 ALTER TABLE `model_has_roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
INSERT INTO `password_reset_tokens` VALUES
('aadityakusumo@gmail.com','$2y$12$jWwNy28pN2niDKReCuRtau2tSSZeDRqgCHEItyMR/Ffg4agjK1TS2','2025-05-30 05:48:48');
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `permissions`
--

DROP TABLE IF EXISTS `permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `permissions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permissions`
--

LOCK TABLES `permissions` WRITE;
/*!40000 ALTER TABLE `permissions` DISABLE KEYS */;
INSERT INTO `permissions` VALUES
(1,'create users','web','2025-05-28 00:55:26','2025-05-28 00:55:26'),
(2,'edit users','web','2025-05-28 00:55:26','2025-05-28 00:55:26'),
(3,'delete users','web','2025-05-28 00:55:26','2025-05-28 00:55:26'),
(4,'view users','web','2025-05-28 00:55:26','2025-05-28 00:55:26'),
(5,'create posts','web','2025-05-28 00:55:26','2025-05-28 00:55:26'),
(6,'edit posts','web','2025-05-28 00:55:26','2025-05-28 00:55:26'),
(7,'delete posts','web','2025-05-28 00:55:26','2025-05-28 00:55:26'),
(8,'publish posts','web','2025-05-28 00:55:26','2025-05-28 00:55:26'),
(9,'view posts','web','2025-05-28 00:55:26','2025-05-28 00:55:26');
/*!40000 ALTER TABLE `permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `role_has_permissions`
--

DROP TABLE IF EXISTS `role_has_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `role_has_permissions` (
  `permission_id` bigint(20) unsigned NOT NULL,
  `role_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`role_id`),
  KEY `role_has_permissions_role_id_foreign` (`role_id`),
  CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `role_has_permissions`
--

LOCK TABLES `role_has_permissions` WRITE;
/*!40000 ALTER TABLE `role_has_permissions` DISABLE KEYS */;
INSERT INTO `role_has_permissions` VALUES
(1,1),
(2,1),
(3,1),
(4,1),
(5,1),
(6,1),
(7,1),
(8,1),
(9,1),
(5,2),
(6,2),
(8,2),
(9,2),
(9,3);
/*!40000 ALTER TABLE `role_has_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `roles` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES
(1,'admin','web','2025-05-28 00:55:26','2025-05-28 00:55:26'),
(2,'editor','web','2025-05-28 00:55:26','2025-05-28 00:55:26'),
(3,'user','web','2025-05-28 00:55:26','2025-05-28 00:55:26');
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL,
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
INSERT INTO `sessions` VALUES
('5ezCWUn3Idn8ldi3P775IYLXg9jbg393YeZRPtf6',NULL,'127.0.0.1','Mozilla/5.0 (X11; Linux x86_64; rv:138.0) Gecko/20100101 Firefox/138.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiaTZMSXNpQnlGSHNCb2tHSVR2UEVZMDVrV0JFZUc2WWlySTJ2NGVzOCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzA6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9yZWdpc3RlciI7fX0=',1748662432);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `KDPROP` varchar(2) DEFAULT NULL,
  `NAMAPROP` varchar(100) DEFAULT NULL,
  `KDJENIS` varchar(1) DEFAULT NULL,
  `JENIS` varchar(5) DEFAULT NULL,
  `KDKOTA` varchar(4) DEFAULT NULL,
  `NAMAKOTA` varchar(100) DEFAULT NULL,
  `KDCLUB` varchar(2) DEFAULT NULL,
  `NAMACLUB` varchar(100) DEFAULT NULL,
  `IDCLUB` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES
(1,'Test User','test@example.com','2025-05-28 00:55:26','$2y$12$5ms4E59l6E3w3YzTRIU7Ve1C21P1L/NRuRESOpjzQDM2RDTRFTKiO','uXHPSL1CIs','2025-05-28 00:55:26','2025-05-28 00:55:26',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0),
(2,'achmad kusumo','achmadkusumo@mail.com',NULL,'$2y$12$b975tgSk9gGnPbJl1OGy6O8xC9SCC3bUO0XxADA/gbGel7uzcTBmC',NULL,'2025-05-28 01:08:20','2025-05-30 20:15:28','00','Admin','0','Admin','0000','Admin','00','Admin',128),
(7,'lando norris','landonorris@mail.com',NULL,'$2y$12$D3gDSv0Rw2oiC4LO0ADxTOg0Ne6eUOsKIDT4Xas3zke/YJ51agY6K',NULL,'2025-05-29 21:21:25','2025-05-30 19:29:47','05','Jawa Timur','1','Kab','3509','Jember','01','HQL. ALAM',30),
(8,'widad nadia','widadnadia@mail.com',NULL,'$2y$12$hwAQLub5Oi6owT6iHgIh8.QnIr8SKr3zwaWYebVCH7CUQdt203Rvq',NULL,'2025-05-30 03:26:57','2025-05-30 20:06:30','05','Jawa Timur','0','Kota','3573','Malang','02','AMARTA',79),
(9,'adit kusumo','aadityakusumo@gmail.com',NULL,'$2y$12$Xh5rjtBQcu83KfsqPhFP4uOXv03.y6tnOkJQ1WOVLByKhqYCAzaJ6',NULL,'2025-05-30 05:47:33','2025-05-30 05:47:33',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'DBPendaftaran'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*M!100616 SET NOTE_VERBOSITY=@OLD_NOTE_VERBOSITY */;

-- Dump completed on 2025-05-31 10:35:28

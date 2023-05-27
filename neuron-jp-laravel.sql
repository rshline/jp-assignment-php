-- Adminer 4.8.1 MySQL 8.0.33 dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

SET NAMES utf8mb4;

DROP TABLE IF EXISTS `failed_jobs`;
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


DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1,	'2014_10_12_000000_create_users_table',	1),
(2,	'2014_10_12_100000_create_password_reset_tokens_table',	1),
(3,	'2019_08_19_000000_create_failed_jobs_table',	1),
(4,	'2019_12_14_000001_create_personal_access_tokens_table',	1),
(5,	'2023_05_26_135235_create_pegawai_table',	2);

DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `pegawai`;
CREATE TABLE `pegawai` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jabatan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `umur` int NOT NULL,
  `alamat` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `pegawai` (`id`, `nama`, `jabatan`, `umur`, `alamat`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1,	'Ophelia Herzog',	'Opticians',	27,	'67786 Ignacio Meadow Apt. 570\nLake Roselynborough, ME 81056',	NULL,	'2023-05-26 07:49:35',	'2023-05-26 07:49:35'),
(2,	'Neoma Swaniawski DVM',	'Screen Printing Machine Operator',	49,	'32759 Clementina Corners\nLegrosville, SC 05582-9598',	NULL,	'2023-05-26 07:49:35',	'2023-05-26 07:49:35'),
(3,	'Amiya Ullrich',	'Textile Cutting Machine Operator',	49,	'5750 Hettie Drive\nSouth Josianne, SC 51229-6075',	NULL,	'2023-05-26 07:49:35',	'2023-05-26 07:49:35'),
(4,	'Prof. Courtney Klocko',	'Architectural Drafter OR Civil Drafter',	53,	'678 Feeney Row\nGonzaloview, OH 84928',	NULL,	'2023-05-26 07:49:35',	'2023-05-26 07:49:35'),
(5,	'Ms. Jaida Roob II',	'Shoe Machine Operators',	21,	'7517 Boehm Shore Apt. 057\nDaynestad, CT 22898',	NULL,	'2023-05-26 07:49:35',	'2023-05-26 07:49:35'),
(6,	'Mrs. Raina Weissnat',	'Food Science Technician',	25,	'47908 Toy Avenue Suite 855\nWest Tobinville, AZ 05810',	NULL,	'2023-05-26 07:49:35',	'2023-05-26 07:49:35'),
(7,	'Ignatius Hane',	'School Social Worker',	19,	'183 Elise Manors\nHandmouth, MT 43883',	NULL,	'2023-05-26 07:49:35',	'2023-05-26 07:49:35'),
(8,	'Leopoldo King',	'Designer',	35,	'457 Franco Pike Apt. 555\nClaymouth, NE 49827-1708',	NULL,	'2023-05-26 07:49:35',	'2023-05-26 07:49:35'),
(9,	'Hazle Feil Sr.',	'Solderer',	27,	'8195 Simonis Ways\nThompsonton, AR 57670-2745',	NULL,	'2023-05-26 07:49:35',	'2023-05-26 07:49:35'),
(10,	'Emely Gulgowski',	'Cultural Studies Teacher',	30,	'879 Reichert Land\nNew Marcellaburgh, TN 31768-8861',	NULL,	'2023-05-26 07:49:35',	'2023-05-26 07:49:35'),
(11,	'Brenda Dare',	'Pastry Chef',	45,	'6618 Ferry Drive\nWest Luigitown, DE 53776',	NULL,	'2023-05-26 07:49:35',	'2023-05-26 07:49:35'),
(12,	'Alexandria Littel',	'Auxiliary Equipment Operator',	20,	'17093 Justyn Mount\nLake Boris, KS 08492-3931',	NULL,	'2023-05-26 07:49:35',	'2023-05-26 07:49:35'),
(13,	'Palma O\'Hara',	'Software Engineer',	20,	'308 Elizabeth Groves\nKeeblerhaven, OR 12986',	NULL,	'2023-05-26 07:49:35',	'2023-05-26 07:49:35'),
(14,	'Dr. Hulda Howe',	'Travel Guide',	37,	'104 Bernier Walks\nEast Maximusberg, AL 98120-9341',	NULL,	'2023-05-26 07:49:35',	'2023-05-26 07:49:35'),
(15,	'Dave Witting',	'Automotive Master Mechanic',	46,	'474 Thaddeus Highway\nNew Janickbury, HI 00550',	NULL,	'2023-05-26 07:49:35',	'2023-05-26 07:49:35'),
(16,	'Lourdes Funk Jr.',	'Webmaster',	34,	'5293 Hodkiewicz Ways\nHeaneyview, MA 19700-4603',	NULL,	'2023-05-26 07:49:35',	'2023-05-26 07:49:35'),
(17,	'Taya Becker',	'Occupational Therapist Assistant',	52,	'50876 Emanuel Point\nNew Horacio, MD 81897-5241',	NULL,	'2023-05-26 07:49:35',	'2023-05-26 07:49:35'),
(18,	'Prof. Easter Runte Sr.',	'Printing Machine Operator',	55,	'90371 Schumm Shoal\nSkilesbury, NM 27400',	NULL,	'2023-05-26 07:49:35',	'2023-05-26 07:49:35'),
(19,	'Ozella Zulauf',	'Bellhop',	25,	'58190 Goyette Hill\nGarnetshire, VT 91317-4351',	NULL,	'2023-05-26 07:49:35',	'2023-05-26 07:49:35'),
(20,	'Prof. Lavern Wiegand',	'Music Arranger and Orchestrator',	54,	'9983 Clare Falls Apt. 892\nRunolfsdottirville, TX 05033',	NULL,	'2023-05-26 07:49:35',	'2023-05-26 07:49:35'),
(21,	'Dr. Enoch Baumbach',	'Barber',	31,	'4021 Bria Tunnel Suite 516\nNorth Brianaport, LA 85313-4105',	NULL,	'2023-05-26 07:49:35',	'2023-05-26 07:49:35'),
(22,	'Amparo Cummings IV',	'Pipelaying Fitter',	49,	'787 Aubrey Lights\nDovieport, MO 74571',	NULL,	'2023-05-26 07:49:35',	'2023-05-26 07:49:35'),
(23,	'Luisa Ward',	'Sawing Machine Operator',	40,	'11334 Kling Track Apt. 015\nNew Aaliyah, FL 10935-8575',	NULL,	'2023-05-26 07:49:35',	'2023-05-26 07:49:35'),
(24,	'Prof. Dino Powlowski II',	'Fish Hatchery Manager',	49,	'887 Bergstrom Well\nEast Brennan, MT 08736',	NULL,	'2023-05-26 07:49:35',	'2023-05-26 07:49:35'),
(25,	'Lilian Auer',	'Respiratory Therapy Technician',	18,	'28155 Gisselle Isle Apt. 311\nNorth Donavonside, DE 98528',	NULL,	'2023-05-26 07:49:35',	'2023-05-26 07:49:35'),
(26,	'Mr. Hilario Ledner II',	'Maid',	59,	'9811 Hoeger Glens\nNorth Jeffry, CA 36373-1448',	NULL,	'2023-05-26 07:49:35',	'2023-05-26 07:49:35'),
(27,	'Dr. Jett Lowe',	'Short Order Cook',	30,	'50144 Golda Extension Apt. 093\nMcLaughlinville, AK 11294-3514',	NULL,	'2023-05-26 07:49:35',	'2023-05-26 07:49:35'),
(28,	'Janie Kovacek',	'Protective Service Worker',	45,	'418 Jovany Crest Apt. 601\nPort Annabel, CO 95928-3987',	NULL,	'2023-05-26 07:49:35',	'2023-05-26 07:49:35'),
(29,	'Brenna Wisoky II',	'Bailiff',	53,	'565 Walter Terrace Apt. 800\nPort Serenaville, NY 57198-8514',	NULL,	'2023-05-26 07:49:35',	'2023-05-26 07:49:35'),
(30,	'Ariane Kris',	'Gauger',	47,	'2016 Arch Manors Suite 379\nEarnestineshire, NC 75687-9589',	NULL,	'2023-05-26 07:49:35',	'2023-05-26 07:49:35'),
(31,	'Tessie Kreiger V',	'Commercial and Industrial Designer',	19,	'1268 Maci Rue\nLabadieshire, MI 38989',	NULL,	'2023-05-26 07:49:35',	'2023-05-26 07:49:35'),
(32,	'Rosalinda Kunde',	'Director Of Marketing',	52,	'63241 Wyman Stream Suite 528\nJordanhaven, NJ 71889',	NULL,	'2023-05-26 07:49:35',	'2023-05-26 07:49:35'),
(33,	'Joesph Nienow',	'Central Office Operator',	49,	'508 Welch Mission\nAlexanderview, CO 60153-1198',	NULL,	'2023-05-26 07:49:35',	'2023-05-26 07:49:35'),
(34,	'Jaylan Wisoky',	'Credit Analyst',	20,	'8054 Howe Freeway\nFelipaborough, SC 48609-0479',	NULL,	'2023-05-26 07:49:35',	'2023-05-26 07:49:35'),
(35,	'Deborah Walter',	'Optometrist',	48,	'65607 Gottlieb Island\nEmmieberg, NJ 24992',	NULL,	'2023-05-26 07:49:35',	'2023-05-26 07:49:35'),
(36,	'Casey Davis',	'Mathematical Science Teacher',	40,	'674 Torp Burgs\nKyleeberg, NE 82402-5331',	NULL,	'2023-05-26 07:49:35',	'2023-05-26 07:49:35'),
(37,	'Della Pagac PhD',	'Restaurant Cook',	18,	'35380 Bailey Forge Apt. 335\nAlannashire, NV 80767-0005',	NULL,	'2023-05-26 07:49:35',	'2023-05-26 07:49:35'),
(38,	'Dr. Brooklyn Mann',	'Vice President Of Marketing',	59,	'8085 Johnston Ports Suite 292\nBrettton, DC 50662-2852',	NULL,	'2023-05-26 07:49:35',	'2023-05-26 07:49:35'),
(39,	'Nikolas Considine MD',	'Ship Captain',	49,	'41817 Ernestine Pike\nAbernathyhaven, MO 24296',	NULL,	'2023-05-26 07:49:35',	'2023-05-26 07:49:35'),
(40,	'Dr. Eloisa Gutkowski Sr.',	'General Practitioner',	31,	'2270 Alta Crescent Suite 528\nHackettview, TX 83914',	NULL,	'2023-05-26 07:49:35',	'2023-05-26 07:49:35'),
(41,	'Maud Witting',	'Chemical Equipment Tender',	18,	'9071 Timothy Rest\nLake Guillermo, TX 76130-9531',	NULL,	'2023-05-26 07:49:35',	'2023-05-26 07:49:35'),
(42,	'Sigmund Schaden II',	'Management Analyst',	28,	'2186 Mraz Walks\nSouth Jeromy, GA 02225-7533',	NULL,	'2023-05-26 07:49:35',	'2023-05-26 07:49:35'),
(43,	'Alyce Fahey',	'Foreign Language Teacher',	41,	'23114 Schamberger Islands Apt. 408\nWest Altheaberg, MA 34653',	NULL,	'2023-05-26 07:49:35',	'2023-05-26 07:49:35'),
(44,	'Mr. Francesco Schowalter PhD',	'Title Abstractor',	44,	'329 Dooley View\nHeathcoteville, IA 42683',	NULL,	'2023-05-26 07:49:35',	'2023-05-26 07:49:35'),
(45,	'Eleazar Halvorson DDS',	'Ambulance Driver',	22,	'147 Considine Junction\nBoydshire, OK 53620-0748',	NULL,	'2023-05-26 07:49:35',	'2023-05-26 07:49:35'),
(46,	'Marquise Bayer',	'Sculptor',	21,	'112 Adolfo Coves\nLake Gerhard, AR 43181',	NULL,	'2023-05-26 07:49:35',	'2023-05-26 07:49:35'),
(47,	'Fredrick Auer III',	'Plating Operator',	20,	'981 Alessia Shoals Suite 840\nNorth Jackson, GA 46155',	NULL,	'2023-05-26 07:49:35',	'2023-05-26 07:49:35'),
(48,	'Mr. Greyson Graham IV',	'Rotary Drill Operator',	59,	'458 Will Route\nPort Kiaraborough, IA 29445-4652',	NULL,	'2023-05-26 07:49:35',	'2023-05-26 07:49:35'),
(49,	'Mr. Brendan Bayer Sr.',	'Pipe Fitter',	53,	'45465 Waino Rapid\nPort Kianabury, WY 98090',	NULL,	'2023-05-26 07:49:35',	'2023-05-26 07:49:35'),
(50,	'Kamryn Rogahn',	'Visual Designer',	31,	'34352 Dasia Flats Suite 619\nFletabury, SC 11789',	NULL,	'2023-05-26 07:49:35',	'2023-05-26 07:49:35'),
(51,	'tes',	'Junior Programmer',	33,	'swansea st',	NULL,	NULL,	NULL);

DROP TABLE IF EXISTS `personal_access_tokens`;
CREATE TABLE `personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1,	'Rizkyta',	'admin@example.com',	NULL,	'$2y$10$viMDFSQ2K1YN5tlTH8Socub1Fb3Y3os5Mevxee6b3X0GiuCeA3Bm2',	'9JoWsOvgf7M0g9hims5JnFOp5yQOdpyw5fDn7QIZxnSwwUDw0234C5zQxD2u',	'2023-05-26 06:49:51',	'2023-05-26 10:33:06');

-- 2023-05-27 01:58:02

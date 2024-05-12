-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 12, 2024 at 08:12 PM
-- Server version: 10.11.7-MariaDB-cll-lve
-- PHP Version: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `u753579003_app`
--

-- --------------------------------------------------------

--
-- Table structure for table `appcategoryconfigs`
--

CREATE TABLE `appcategoryconfigs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_type` varchar(255) NOT NULL,
  `user_id` bigint(19) NOT NULL,
  `category_id` bigint(19) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `appcategoryconfigs`
--

INSERT INTO `appcategoryconfigs` (`id`, `user_type`, `user_id`, `category_id`, `created_at`, `updated_at`) VALUES
(1, 'Doctor', 8, 1, '2024-05-12 12:11:11', '2024-05-12 12:11:11'),
(2, 'Doctor', 8, 30, '2024-05-12 12:11:11', '2024-05-12 12:11:11'),
(3, 'Doctor', 9, 1, '2024-05-12 12:17:03', '2024-05-12 12:17:03'),
(4, 'Doctor', 9, 30, '2024-05-12 12:17:03', '2024-05-12 12:17:03'),
(5, 'Doctor', 10, 1, '2024-05-12 12:17:31', '2024-05-12 12:17:31'),
(6, 'Doctor', 10, 30, '2024-05-12 12:17:31', '2024-05-12 12:17:31'),
(7, 'Doctor', 11, 1, '2024-05-12 12:18:01', '2024-05-12 12:18:01'),
(8, 'Doctor', 11, 30, '2024-05-12 12:18:01', '2024-05-12 12:18:01'),
(9, 'Doctor', 13, 1, '2024-05-12 16:35:24', '2024-05-12 16:35:24'),
(10, 'Doctor', 13, 30, '2024-05-12 16:35:24', '2024-05-12 16:35:24'),
(11, 'Hospital', 14, 1, '2024-05-12 21:11:22', '2024-05-12 21:11:22'),
(12, 'Hospital', 15, 23, '2024-05-12 23:31:23', '2024-05-12 23:31:23'),
(13, 'Diagnositcs', 16, 26, '2024-05-13 00:35:39', '2024-05-13 00:35:39'),
(14, 'Diagnositcs', 16, 27, '2024-05-13 00:35:39', '2024-05-13 00:35:39'),
(15, 'Diagnositcs', 17, 26, '2024-05-13 00:37:17', '2024-05-13 00:37:17'),
(16, 'Diagnositcs', 17, 27, '2024-05-13 00:37:17', '2024-05-13 00:37:17'),
(17, 'Diagnositcs', 18, 26, '2024-05-13 00:38:14', '2024-05-13 00:38:14'),
(18, 'Diagnositcs', 18, 27, '2024-05-13 00:38:14', '2024-05-13 00:38:14'),
(19, 'Diagnositcs', 19, 26, '2024-05-13 00:39:41', '2024-05-13 00:39:41'),
(20, 'Diagnositcs', 19, 27, '2024-05-13 00:39:41', '2024-05-13 00:39:41'),
(21, 'Diagnositcs', 20, 26, '2024-05-13 00:40:12', '2024-05-13 00:40:12'),
(22, 'Diagnositcs', 20, 27, '2024-05-13 00:40:12', '2024-05-13 00:40:12'),
(23, 'Diagnositcs', 21, 26, '2024-05-13 00:46:36', '2024-05-13 00:46:36'),
(24, 'Diagnositcs', 21, 27, '2024-05-13 00:46:36', '2024-05-13 00:46:36'),
(25, 'Diagnositcs', 22, 26, '2024-05-13 00:47:23', '2024-05-13 00:47:23'),
(26, 'Diagnositcs', 22, 27, '2024-05-13 00:47:23', '2024-05-13 00:47:23'),
(27, 'Diagnositcs', 23, 26, '2024-05-13 00:49:07', '2024-05-13 00:49:07'),
(28, 'Diagnositcs', 23, 27, '2024-05-13 00:49:07', '2024-05-13 00:49:07'),
(29, 'Diagnositcs', 16, 26, '2024-05-13 00:51:42', '2024-05-13 00:51:42'),
(30, 'Diagnositcs', 16, 27, '2024-05-13 00:51:42', '2024-05-13 00:51:42'),
(31, 'Diagnositcs', 17, 26, '2024-05-13 00:53:10', '2024-05-13 00:53:10'),
(32, 'Diagnositcs', 17, 27, '2024-05-13 00:53:10', '2024-05-13 00:53:10'),
(33, 'Diagnositcs', 18, 26, '2024-05-13 00:54:37', '2024-05-13 00:54:37'),
(34, 'Diagnositcs', 18, 27, '2024-05-13 00:54:37', '2024-05-13 00:54:37');

-- --------------------------------------------------------

--
-- Table structure for table `appointments`
--

CREATE TABLE `appointments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `PatientID` bigint(19) NOT NULL,
  `DoctorID` bigint(19) NOT NULL,
  `AppointmentDate` date NOT NULL,
  `AppointmentTime` time NOT NULL,
  `status` enum('Requested','Confirmed','Cancelled','Completed') NOT NULL DEFAULT 'Requested',
  `doctor_type` varchar(255) NOT NULL,
  `Notes` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `appointments`
--

INSERT INTO `appointments` (`id`, `PatientID`, `DoctorID`, `AppointmentDate`, `AppointmentTime`, `status`, `doctor_type`, `Notes`, `created_at`, `updated_at`) VALUES
(1, 1, 1, '2024-05-01', '13:27:00', 'Requested', 'Hospital', '', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `Appointment_history`
--

CREATE TABLE `Appointment_history` (
  `id` int(11) NOT NULL,
  `AppointmentID` int(11) DEFAULT NULL,
  `requested_user_type` varchar(50) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `Appointment_status` enum('Requested','Confirmed','Cancelled') DEFAULT 'Requested',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `availabilities`
--

CREATE TABLE `availabilities` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(19) NOT NULL,
  `day_of_week` varchar(255) NOT NULL,
  `start_time` varchar(25) NOT NULL,
  `end_time` varchar(25) NOT NULL,
  `user_type` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `country`
--

CREATE TABLE `country` (
  `id` int(11) NOT NULL,
  `country_name` varchar(255) DEFAULT NULL,
  `country_code` varchar(30) DEFAULT NULL,
  `dialing_code` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `country`
--

INSERT INTO `country` (`id`, `country_name`, `country_code`, `dialing_code`) VALUES
(2, 'Afghanistan ', 'AF', '+93'),
(3, 'Albania ', 'AL', '+355'),
(4, 'Algeria ', 'DZ', '+213'),
(5, 'American Samoa', 'AS', '+1-684'),
(6, 'Andorra', 'AD', '+376'),
(7, 'Angola', 'AO', '+244'),
(8, 'Anguilla ', 'AI', '+1-264'),
(9, 'Antarctica', 'AQ', '+672'),
(10, 'Antigua and Barbuda', 'AG', '+1-268'),
(11, 'Argentina ', 'AR', '+54'),
(12, 'Armenia', 'AM', '+374'),
(13, 'Aruba', 'AW', '+297'),
(14, 'Australia', 'AU', '+61'),
(15, 'Austria', 'AT', '+43'),
(16, 'Azerbaijan', 'AZ', '+994'),
(18, 'Bahamas', 'BS', '+1-242'),
(19, 'Bahrain', 'BH', '+973'),
(20, 'Bangladesh', 'BD', '+880'),
(21, 'Barbados ', 'BB', '+1-246'),
(22, 'Belarus', 'BY', '+375'),
(23, 'Belgium ', 'BE', '+32'),
(24, 'Belize', 'BZ', '+501'),
(25, 'Benin', 'BJ', '+229'),
(26, 'Bermuda ', 'BM', '+1-441'),
(27, 'Bhutan, Kingdom of', 'BT', '+975'),
(28, 'Bolivia ', 'BO', '+591'),
(29, 'Bosnia and Herzegovina ', 'BA', '+387'),
(30, 'Botswana', 'BW', '+267'),
(32, 'Brazil ', 'BR', '+55'),
(34, 'Brunei ', 'BN', '+673'),
(35, 'Bulgaria ', 'BG', '+359'),
(36, 'Burkina Faso', 'BF', '+226'),
(37, 'Burundi', 'BI', '+257'),
(39, 'Cambodia', 'KH', '+855'),
(40, 'Cameroon', 'CM', '+237'),
(41, 'Canada', 'CA', '+1'),
(42, 'Cape Verde ', 'CV', '+238'),
(43, 'Cayman Islands ', 'KY', '+1-345'),
(44, 'Central African Republic ', 'CF', '+236'),
(45, 'Chad ', 'TD', '+235'),
(46, 'Chile ', 'CL', '+56'),
(47, 'China ', 'CN', '+86'),
(48, 'Christmas Island ', 'CX', '+53'),
(49, 'Cocos (Keeling) Islands ', 'CC', '+61'),
(50, 'Colombia ', 'CO', '+57'),
(51, 'Comoros', 'KM', '+269'),
(52, 'Congo', 'CD', '+243'),
(53, 'Congo', 'CG', '+242'),
(54, 'Cook Islands', 'CK', '+682'),
(55, 'Costa Rica ', 'CR', '+506'),
(56, 'Cote DIvoire', 'CI', '+225'),
(57, 'Croatia (Hrvatska) ', 'HR', '+385'),
(58, 'Cuba ', 'CU', '+53'),
(59, 'Cyprus ', 'CY', '+357'),
(60, 'Czech Republic', 'CZ', '+420'),
(63, 'Denmark ', 'DK', '+45'),
(64, 'Djibouti', 'DJ', '+253'),
(65, 'Dominica ', 'DM', '+1-767'),
(66, 'Dominican Republic ', 'DO', '+1-809 and +1-829  '),
(68, 'East Timor', 'TP', '+670'),
(69, 'Ecuador ', 'EC', '+593 '),
(70, 'Egypt', 'EG', '+20'),
(71, 'El Salvador ', 'SV', '+503'),
(72, 'Equatorial Guinea', 'GQ', '+240'),
(73, 'Eritrea', 'ER', '+291'),
(74, 'Estonia', 'EE', '+372'),
(75, 'Ethiopia', 'ET', '+251'),
(77, 'Falkland Islands (Islas Malvinas) ', 'FK', '+500'),
(78, 'Faroe Islands ', 'FO', '+298'),
(79, 'Fiji ', 'FJ', '+679'),
(80, 'Finland ', 'FI', '+358'),
(81, 'France ', 'FR', '+33'),
(82, 'French Guiana or French Guyana ', 'GF', '+594'),
(83, 'French Polynesia', 'PF', '+689'),
(86, 'Gabon (Gabonese Republic)', 'GA', '+241'),
(87, 'Gambia, The ', 'GM', '+220'),
(88, 'Georgia', 'GE', '+995'),
(89, 'Germany ', 'DE', '+49'),
(90, 'Ghana', 'GH', '+233'),
(91, 'Gibraltar ', 'GI', '+350'),
(93, 'Greece ', 'GR', '+30'),
(94, 'Greenland ', 'GL', '+299'),
(95, 'Grenada ', 'GD', '+1-473'),
(96, 'Guadeloupe', 'GP', '+590'),
(97, 'Guam', 'GU', '+1-671'),
(98, 'Guatemala ', 'GT', '+502'),
(99, 'Guinea', 'GN', '+224'),
(100, 'Guinea-Bissau', 'GW', '+245'),
(101, 'Guyana', 'GY', '+592'),
(103, 'Haiti ', 'HT', '+509'),
(106, 'Honduras ', 'HN', '+504'),
(107, 'Hong Kong ', 'HK', '+852'),
(108, 'Hungary ', 'HU', '+36'),
(110, 'Iceland ', 'IS', '+354'),
(111, 'India ', 'IN', '+91'),
(112, 'Indonesia', 'ID', '+62'),
(113, 'Iran', 'IR', '+98'),
(114, 'Iraq ', 'IQ', '+964'),
(115, 'Ireland ', 'IE', '+353'),
(116, 'Israel ', 'IL', '+972'),
(117, 'Italy ', 'IT', '+39'),
(119, 'Jamaica ', 'JM', '+1-876'),
(120, 'Japan ', 'JP', '+81'),
(121, 'Jordan', 'JO', '+962'),
(123, 'Kazakstan', 'KZ', '+7'),
(124, 'Kenya', 'KE', '+254'),
(125, 'Kiribati', 'KI', '+686'),
(126, 'North Korea', 'KP', '+850'),
(127, 'South Korea', 'KR', '+82'),
(128, 'Kuwait ', 'KW', '+965'),
(129, 'Kyrgyzstan', 'KG', '+996'),
(131, 'Lao People\'s Democratic Republic (Laos)', 'LA', '+856'),
(132, 'Latvia', 'LV', '+371'),
(133, 'Lebanon ', 'LB', '+961'),
(134, 'Lesotho', 'LS', '+266'),
(135, 'Liberia ', 'LR', '+231'),
(136, 'Libya (Libyan Arab Jamahiriya)', 'LY', '+218'),
(137, 'Liechtenstein ', 'LI', '+423'),
(138, 'Lithuania', 'LT', '+370'),
(139, 'Luxembourg ', 'LU', '+352'),
(141, 'Macau ', 'MO', '+853'),
(142, 'Macedonia', 'MK', '+389'),
(143, 'Madagascar', 'MG', '+261'),
(144, 'Malawi', 'MW', '+265'),
(145, 'Malaysia ', 'MY', '+60'),
(146, 'Maldives ', 'MV', '+960'),
(147, 'Mali', 'ML', '+223'),
(148, 'Malta ', 'MT', '+356'),
(149, 'Marshall Islands', 'MH', '+692'),
(150, 'Martinique (French) ', 'MQ', '+596'),
(151, 'Mauritania ', 'MR', '+222'),
(152, 'Mauritius ', 'MU', '+230'),
(153, 'Mayotte (Territorial Collectivity of Mayotte)', 'YT', '+269'),
(154, 'Mexico ', 'MX', '+52'),
(155, 'Micronesia', 'FM', '+691'),
(156, 'Moldova', 'MD', '+373'),
(157, 'Monaco', 'MC', '+377'),
(158, 'Mongolia', 'MN', '+976'),
(159, 'Montserrat ', 'MS', '+1-664'),
(160, 'Morocco ', 'MA', '+212'),
(161, 'Mozambique', 'MZ', '+258'),
(162, 'Myanmar', 'MM', '+95'),
(164, 'Namibia', 'NA', '+264'),
(165, 'Nauru', 'NR', '+674'),
(166, 'Nepal ', 'NP', '+977'),
(167, 'Netherlands ', 'NL', '+31'),
(168, 'Netherlands Antilles', 'AN', '+599'),
(169, 'New Caledonia ', 'NC', '+687'),
(170, 'New Zealand', 'NZ', '+64'),
(171, 'Nicaragua ', 'NI', '+505'),
(172, 'Niger ', 'NE', '+227'),
(173, 'Nigeria ', 'NG', '+234'),
(174, 'Niue', 'NU', '+683'),
(175, 'Norfolk Island ', 'NF', '+672'),
(176, 'Northern Mariana Islands', 'MP', '+1-670'),
(177, 'Norway ', 'NO', '+47'),
(179, 'Oman', 'OM', '+968'),
(181, 'Pakistan', 'PK', '+92'),
(182, 'Palau', 'PW', '+680'),
(183, 'Palestinian State', 'PS', '+970'),
(184, 'Panama ', 'PA', '+507'),
(185, 'Papua New Guinea', 'PG', '+675'),
(186, 'Paraguay ', 'PY', '+595'),
(187, 'Peru ', 'PE', '+51'),
(188, 'Philippines ', 'PH', '+63'),
(190, 'Poland ', 'PL', '+48'),
(191, 'Portugal ', 'PT', '+351'),
(192, 'Puerto Rico ', 'PR', '+1-787'),
(193, 'Puerto Rico', 'PR', '+1-939'),
(194, 'Qatar', 'QA', '+974 '),
(196, 'Reunion (French)', 'RE', '+262'),
(197, 'Romania ', 'RO', '+40'),
(199, 'Russian Federation ', 'RU', '+7'),
(200, 'Rwanda (Rwandese Republic)', 'RW', '+250'),
(202, 'Saint Helena ', 'SH', '+290'),
(203, 'Saint Kitts', 'KN', '+1-869'),
(204, 'Saint Lucia ', 'LC', '+1-758'),
(205, 'Saint Pierre and Miquelon ', 'PM', '+508'),
(206, 'Saint Vincent and the Grenadines ', 'VC', '+1-784'),
(207, 'Samoa', 'WS', '+685'),
(208, 'San Marino ', 'SM', '+378'),
(209, 'Sao Tome and Principe ', 'ST', '+239'),
(210, 'Saudi Arabia ', 'SA', '+966'),
(212, 'Senegal ', 'SN', '+221'),
(213, 'Seychelles ', 'SC', '+248'),
(214, 'Sierra Leone ', 'SL', '+232'),
(215, 'Singapore ', 'SG', '+65'),
(216, 'Slovakia', 'SK', '+421'),
(217, 'Slovenia ', 'SI', '+386'),
(218, 'Solomon Islands', 'SB', '+677'),
(219, 'Somalia', 'SO', '+252'),
(220, 'South Africa', 'ZA', '+27'),
(222, 'Spain ', 'ES', '+34'),
(223, 'Sri Lanka', 'LK', '+94'),
(224, 'Sudan', 'SD', '+249'),
(225, 'Suriname', 'SR', '+597'),
(227, 'Swaziland', 'SZ', '+268'),
(228, 'Sweden ', 'SE', '+46'),
(229, 'Switzerland ', 'CH', '+41'),
(230, 'Syria', 'SY', '+963'),
(232, 'Taiwan', 'TW', '+886'),
(233, 'Tajikistan', 'TJ', '+992'),
(234, 'Tanzania', 'TZ', '+255'),
(235, 'Thailand', 'TH', '+66'),
(237, 'Tokelau ', 'TK', '+690'),
(238, 'Tonga', 'TO', '+676'),
(239, 'Trinidad and Tobago ', 'TT', '+1-868'),
(241, 'Tunisia ', 'TN', '+216'),
(242, 'Turkey ', 'TR', '+90'),
(243, 'Turkmenistan', 'TM', '+993'),
(244, 'Turks and Caicos Islands ', 'TC', '+1-649'),
(245, 'Tuvalu', 'TV', '+688'),
(247, 'Uganda', 'UG', '+256'),
(248, 'Ukraine', 'UA', '+380'),
(249, 'United Arab Emirates (UAE)', 'AE', '+971'),
(250, 'United Kingdom (Great Britain / UK)', 'GB', '+44'),
(251, 'United States ', 'US', '+1'),
(253, 'Uruguay', 'UY', '+598'),
(254, 'Uzbekistan', 'UZ', '+998'),
(256, 'Vanuatu', 'VU', '+678'),
(257, 'Vatican City State (Holy See)', 'VA', '+418'),
(258, 'Venezuela ', 'VE', '+58'),
(259, 'Vietnam ', 'VN', '+84'),
(260, 'Virgin Islands, British ', 'VI', '+1-284'),
(261, 'Virgin Islands, United States', 'VQ', '+1-340'),
(263, 'Wallis and Futuna Islands ', 'WF', '+681'),
(266, 'Yemen ', 'YE', '+967'),
(269, 'Zambia', 'ZM', '+260'),
(270, 'Zimbabwe', 'ZW', '+263');

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `id` bigint(30) UNSIGNED NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `mobile_number` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `profile_photo` varchar(255) NOT NULL DEFAULT '0',
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `gender` varchar(50) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`id`, `first_name`, `last_name`, `email`, `mobile_number`, `email_verified_at`, `password`, `profile_photo`, `remember_token`, `created_at`, `updated_at`, `gender`, `status`) VALUES
(6, 'Chandra Sheker', 'K', 'cskconcepts@gmail.com', '9676129579', NULL, '$2y$10$18mO/XpCMuzusrgdfrPIJOXyDra5kXlfmYjYmfzds5eyIBXeLmYqa', '0', NULL, '2024-04-28 11:37:26', '2024-04-28 11:37:26', 'Male', 1),
(12, 'Yeddula Srikanth', 'Reddy', 'yeddulasrikanthreddy@gmail.com', '9948129720', NULL, '$2y$10$x6F7uBqAXup4GqmPTNEfp.qIWumwXrpqnZrxDm1n/jJ/7rW8Q/Kiu', 'customerptofile/Nse7M0t7TZbPHyh5PB4eSOhXp23k1BdAJDNEzdS5.jpg', NULL, '2024-05-12 16:28:05', '2024-05-12 17:09:18', 'Male', 1);

-- --------------------------------------------------------

--
-- Table structure for table `diagnositcs`
--

CREATE TABLE `diagnositcs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `diagnostics_name` varchar(255) NOT NULL,
  `owner_name` varchar(255) NOT NULL,
  `gender` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `mobile` varchar(255) NOT NULL,
  `Category` varchar(255) NOT NULL,
  `licence_number` varchar(255) NOT NULL,
  `accrediations_NABL` varchar(255) NOT NULL,
  `experience` varchar(255) NOT NULL,
  `profile_description` longtext NOT NULL,
  `logo` varchar(255) NOT NULL,
  `latitude` varchar(255) NOT NULL,
  `longitude` varchar(255) NOT NULL,
  `registered_address` varchar(255) NOT NULL,
  `status` int(11) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `diagnositcs`
--

INSERT INTO `diagnositcs` (`id`, `diagnostics_name`, `owner_name`, `gender`, `email`, `mobile`, `Category`, `licence_number`, `accrediations_NABL`, `experience`, `profile_description`, `logo`, `latitude`, `longitude`, `registered_address`, `status`, `created_at`, `updated_at`) VALUES
(16, 'Life Line Diagnostics', 'Srikanth Reddy', 'Male', 'sr87211+22@gmail.com', '9484888485', '26,27', 'DHMO647474734', 'JCI', '12', '\"1. COMPLETE BLOOD TEST 2.ECG(ELECTRO CARDIOGRAPHY_12 CHANNEL) 3.CTMT(COMPUTERISED TREDMIL TEST) 4.2D ECHO BY CARDIOLOGIST 5.HARMONES _ T3,T4,TSH,FSH,LH,PROLOCTIN,CANCER MARKERS - CA 125 AND PSA-ETC 6.TROPONIN - T CARDIAC MARKER\"', '0', '15.5414129', '78.9491593', '33-25-34, Puspha Hotel Road _ BS.Rao street pushpa hotel to seetharampuram road, Bellapu Sobhanadri St, opp. to Assure hospital, beside SAI BHASKAR HOSPITALS, Moghalrajpuram, Kasturibaipet, Vijayawada, Andhra Pradesh 520010', 1, '2024-05-13 00:51:42', '2024-05-13 00:51:42'),
(17, 'Life Line Diagnostics', 'Srikanth Reddy', 'Male', 'sr87211+23@gmail.com', '9484888485', '26,27', 'DHMO647474734', 'JCI', '12', '\"1. COMPLETE BLOOD TEST 2.ECG(ELECTRO CARDIOGRAPHY_12 CHANNEL) 3.CTMT(COMPUTERISED TREDMIL TEST) 4.2D ECHO BY CARDIOLOGIST 5.HARMONES _ T3,T4,TSH,FSH,LH,PROLOCTIN,CANCER MARKERS - CA 125 AND PSA-ETC 6.TROPONIN - T CARDIAC MARKER\"', '0', '15.5414129', '78.9491593', '33-25-34, Puspha Hotel Road _ BS.Rao street pushpa hotel to seetharampuram road, Bellapu Sobhanadri St, opp. to Assure hospital, beside SAI BHASKAR HOSPITALS, Moghalrajpuram, Kasturibaipet, Vijayawada, Andhra Pradesh 520010', 1, '2024-05-13 00:53:10', '2024-05-13 00:53:10'),
(18, 'Hema Diagnostics', 'Srikanth Reddy', 'Male', 'sr87211+10@gmail.com', '9484888485', '26,27', 'DHMO647474734', 'JCI', '12', '\"1. COMPLETE BLOOD TEST 2.ECG(ELECTRO CARDIOGRAPHY_12 CHANNEL) 3.CTMT(COMPUTERISED TREDMIL TEST) 4.2D ECHO BY CARDIOLOGIST 5.HARMONES _ T3,T4,TSH,FSH,LH,PROLOCTIN,CANCER MARKERS - CA 125 AND PSA-ETC 6.TROPONIN - T CARDIAC MARKER\"', '0', '15.5414129', '78.9491593', '33-25-34, Puspha Hotel Road _ BS.Rao street pushpa hotel to seetharampuram road, Bellapu Sobhanadri St, opp. to Assure hospital, beside SAI BHASKAR HOSPITALS, Moghalrajpuram, Kasturibaipet, Vijayawada, Andhra Pradesh 520010', 1, '2024-05-13 00:54:37', '2024-05-13 00:54:37');

-- --------------------------------------------------------

--
-- Table structure for table `doctor_speciality_name`
--

CREATE TABLE `doctor_speciality_name` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `speciality` varchar(255) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `doctor_speciality_name`
--

INSERT INTO `doctor_speciality_name` (`id`, `speciality`, `status`, `created_at`, `updated_at`) VALUES
(1, 'General Medicine', 1, '2024-04-20 07:21:58', NULL),
(2, 'Pediatrics', 1, '2024-04-20 07:22:06', NULL),
(3, 'Cardiology', 1, '2024-04-20 07:22:08', NULL),
(4, 'Orthopedics', 1, '2024-04-20 07:22:28', NULL),
(5, 'Neurology', 1, '2024-04-20 07:22:11', NULL),
(6, 'Dermatology', 1, '2024-04-20 07:22:13', NULL),
(7, 'Gynecology', 1, '2024-04-20 07:22:14', NULL),
(8, 'Oncology', 1, '2024-04-20 07:22:20', NULL),
(9, 'Ophthalmology', 1, '2024-04-20 07:22:17', NULL),
(10, 'Psychiatry', 1, '2024-04-20 07:22:22', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `favorites`
--

CREATE TABLE `favorites` (
  `id` int(11) NOT NULL,
  `doctor_id` bigint(20) DEFAULT NULL,
  `customer_id` bigint(20) DEFAULT NULL,
  `status` enum('pending','accepted','rejected') DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `favorites`
--

INSERT INTO `favorites` (`id`, `doctor_id`, `customer_id`, `status`, `created_at`, `updated_at`) VALUES
(1, 26, 1, 'pending', '2024-04-27 00:21:32', '2024-04-27 00:21:32'),
(2, 28, 1, 'pending', '2024-04-27 00:21:39', '2024-04-27 00:21:39'),
(8, 30, 1, 'pending', '2024-04-27 13:32:03', '2024-04-27 13:32:03'),
(9, 30, 1, 'pending', '2024-04-27 13:32:09', '2024-04-27 13:32:09'),
(10, 30, 1, 'pending', '2024-04-27 13:34:59', '2024-04-27 13:34:59'),
(18, 31, 2, 'pending', '2024-04-28 10:44:57', '2024-04-28 10:44:57'),
(20, 33, 3, 'pending', '2024-04-28 11:47:53', '2024-04-28 11:47:53'),
(22, 35, 4, 'pending', '2024-04-28 12:19:16', '2024-04-28 12:19:16'),
(23, 36, 2, 'pending', '2024-04-28 15:17:05', '2024-04-28 15:17:05'),
(27, 2, 12, 'pending', '2024-05-03 14:46:59', '2024-05-03 14:46:59'),
(42, 15, 16, 'pending', '2024-05-07 10:38:07', '2024-05-07 10:38:07'),
(44, 3, 16, 'pending', '2024-05-07 12:02:41', '2024-05-07 12:02:41'),
(46, 8, 12, 'pending', '2024-05-12 17:34:20', '2024-05-12 17:34:20');

-- --------------------------------------------------------

--
-- Table structure for table `hospitals`
--

CREATE TABLE `hospitals` (
  `id` bigint(30) UNSIGNED NOT NULL,
  `hospital_name` varchar(255) NOT NULL,
  `director_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `hospital_contact_number` varchar(255) NOT NULL,
  `emergency_number` varchar(255) NOT NULL,
  `category` varchar(255) NOT NULL,
  `dmho_licence_number` varchar(255) NOT NULL,
  `accrediations` varchar(255) NOT NULL,
  `experience` varchar(255) NOT NULL,
  `profile_description` longtext NOT NULL,
  `logo` varchar(255) NOT NULL,
  `latitude` varchar(255) NOT NULL,
  `longitude` varchar(255) NOT NULL,
  `registered_address` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `status` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hospitals`
--

INSERT INTO `hospitals` (`id`, `hospital_name`, `director_name`, `email`, `hospital_contact_number`, `emergency_number`, `category`, `dmho_licence_number`, `accrediations`, `experience`, `profile_description`, `logo`, `latitude`, `longitude`, `registered_address`, `created_at`, `updated_at`, `status`) VALUES
(14, 'PRK Multinational Hospital', 'Ranjitha Reddy', 'zpomail@yail.com', '91 9564646544', '91 9564646544645', '1', 'DMHO-567-123', 'JCI, NABH, NABL, Others Please Specify', '12', 'Every caregiver at PRK Hospitals adheres to the basics of healing by offering care and support while assisting you in understanding the value of working toward a common goal of restoration.↵↵We are dedicated to providing exceptional healthcare services with a focus on patient-centered care, medical innovation, and compassionate treatment. Our state-of-the-art facility is equipped with the latest medical technology and staffed by a team of highly skilled and experienced healthcare professionals.', 'ptofilephoto/tLEy3aNptrWHYqIsM1cPO7A0dauynhbXopZxn0Yv.png', '17.500271', '78.317596', 'NH65, Chanda Nagar, Hyderabad - 500081', '2024-05-12 21:11:22', '2024-05-12 23:32:02', 1),
(15, 'Lakshmi Srinivasa Multi Speciality Hospital', 'Srikanth Reddy', 'yeddulasrikanthreddy+01@gmail.com', '9885568555', '9948223292', '23', 'DHMO12345678', 'JCI, NABH, NABL, Others Please Specify', '15', 'In 2022, the theme of World Health Day is \'Our Planet, Our Health\'. This year, WHO will focus global attention on urgent actions needed to keep humans and the planet healthy and foster a movement to create societies focused on well-being.\nFor medical Information:\nLakshmi Srinivasa Multi Specialty Hospital,\nBeside RTC Bustand. Joyalukas Back Side,\nSundaraiah Bhavan Road, Ongole-523001', '0', '15.5414129', '78.9491593', 'Beside RTC Bustand. Joyalukas Back Side,\nSundaraiah Bhavan Road, Ongole-523001\nContact: +91 98855 68555', '2024-05-12 23:31:23', '2024-05-12 23:31:23', 1);

-- --------------------------------------------------------

--
-- Table structure for table `hvr_doctors`
--

CREATE TABLE `hvr_doctors` (
  `id` bigint(30) UNSIGNED NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `gender` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `specialist` varchar(255) DEFAULT NULL,
  `qualification` varchar(255) NOT NULL,
  `expeirence` varchar(255) NOT NULL,
  `latitude` varchar(255) NOT NULL,
  `longitute` varchar(255) NOT NULL,
  `address` text NOT NULL,
  `profile` longtext NOT NULL,
  `profile_photo` text NOT NULL,
  `profile_status` tinyint(4) NOT NULL DEFAULT 0,
  `password` varchar(255) NOT NULL,
  `NMC_Registration_NO` varchar(150) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hvr_doctors`
--

INSERT INTO `hvr_doctors` (`id`, `first_name`, `last_name`, `gender`, `email`, `phone`, `specialist`, `qualification`, `expeirence`, `latitude`, `longitute`, `address`, `profile`, `profile_photo`, `profile_status`, `password`, `NMC_Registration_NO`, `created_at`, `updated_at`) VALUES
(2, 'Dr harsha', 'Vardhan', 'Male', 'sheker.kasap@gmail.com', '9640031031', '4', 'MBBS', '10 Years', '17.4468038', '78.3551802', 'hyderabad', '10 YEARS EXPEIRENCE ON HEALTHCARE DEPARTMENT', '0', 1, '$2y$10$DyVvFnFDjXxN.XjIpG/ZtOBMoyLhNRmcByqh1sG2hmMY7Wl6RbNni', '', '2024-04-28 12:02:49', '2024-05-03 21:55:30'),
(7, 'Dr Srikanth', 'Reddy', 'Male', 'yeddulasrikanthreddy@gmail.com', '9948129729', '3', 'MBBS', '10', '15.5414129', '78.9491593', 'Atchampeta', 'Dr. Srikanth is committed to providing personalized and compassionate care to her patients. She believes in thorough communication and involving patients in their treatment decisions. She adheres to evidence-based medicine to ensure the best possible outcomes for her patients.                                    Fellow of the American College of Cardiology (FACC)\nMember, American Heart Association (AHA)\nMember, Society for Cardiovascular Angiography and Interventions (SCAI)', 'ptofilephoto/I5WtPRxfvkw1U7CTFJFUhG83MmDuBXYHjCYh0JdB.jpg', 0, '$2y$10$W9RWqjI3TvfPtk3ZW4bmaOwwIs52iCZRAr4avUXujn.FtX48QHbOu', 'NMC1234567890', '2024-05-11 20:39:08', '2024-05-11 21:56:05'),
(8, 'Dr. Azad', 'SMD', 'Male', 'azad@gmail.com', '8801078838', '1,30', 'BDS, MDS - Oral & Maxillofacial Surgery', '26', '15.5414129', '78.9491593', 'Achampetta,Andhra Pradesh 523372', 'Dr. Azad SMD is an experienced dental surgeon and implantologist practicing for 19 years. He began his career from Manipal Institute of Dental Sciences, pursued his Master\'s degree from JSS College, Mysore and he has been a practitioner since 2001. He did his advanced studies in Implantology and secured a Masters Degree in the same from Stony Brook University, Atlanta, USA. At present, he is heading the department of Implantology at the Oxford Dental College and Hospital and also an Associate Dean of academics at the same institute', '0', 0, '$2y$10$pjBLCpptgn/O6ockp1r1f.rzFGcmlCec0n4tDT3n5HFnW1mUBzyXW', 'NMC12345555', '2024-05-12 12:11:11', '2024-05-12 12:11:11'),
(9, 'Dr. Vital', 'G', 'Male', 'vital@gmail.com', '9949752033', '1,30', 'MBBS', '7', '15.5414129', '78.9491593', 'Achampetta,Andhra Pradesh 523372', 'Dr. Vital is an experienced General Physician who practices at the Clinikk Health Hub in Banashankari. He obtained his MBBS degree from Father Muller Medical College, Mangalore, in 2014. With 6 years of experience, Dr. Anil works with a patient-centric approach, developing personalized treatment plans for all his patients. Dr. Anil practices at the Clinikk Health Hub Banashankari, which is located at 100 ft Ring Road, Banashankari 3rd Stage, beside Sri Eshwari Theatre. Clinikk Health Hubs are a chain of 11 premium medical centers by Clinikk across Bengaluru. With modern medical facilities, Clinikk Health Hub provides access to experienced doctors, medicines, and lab tests - all in one place.', '0', 0, '$2y$10$tnZks7J41GvFp7XfeeYyJue4EMlw.rEFvdWKR9SONy6DLd9QEzQNa', 'NMC12345555', '2024-05-12 12:17:03', '2024-05-12 12:17:03'),
(10, 'Dr. Sunitha', 'G', 'FeMale', 'sunitha@gmail.com', '9949752034', '1,30', 'MBBS', '7', '15.5414129', '78.9491593', 'Achampetta,Andhra Pradesh 523372', 'Dr. Vital is an experienced General Physician who practices at the Clinikk Health Hub in Banashankari. He obtained his MBBS degree from Father Muller Medical College, Mangalore, in 2014. With 6 years of experience, Dr. Anil works with a patient-centric approach, developing personalized treatment plans for all his patients. Dr. Anil practices at the Clinikk Health Hub Banashankari, which is located at 100 ft Ring Road, Banashankari 3rd Stage, beside Sri Eshwari Theatre. Clinikk Health Hubs are a chain of 11 premium medical centers by Clinikk across Bengaluru. With modern medical facilities, Clinikk Health Hub provides access to experienced doctors, medicines, and lab tests - all in one place.', '0', 0, '$2y$10$gkrELHTMnRd6yaF1S1aRd.r6nuRQP5Y6nKgm6stShILB9nuHfm5wy', 'NMC12345555', '2024-05-12 12:17:31', '2024-05-12 12:17:31'),
(11, 'Dr. Ravi', 'Kumar', 'FeMale', 'ravikumar@gmail.com', '9949752035', '1,30', 'MBBS', '7', '15.5414129', '78.9491593', 'Achampetta,Andhra Pradesh 523372', 'Dr. Ravikumar is an experienced General Physician who practices at the Clinikk Health Hub in Banashankari. He obtained his MBBS degree from Father Muller Medical College, Mangalore, in 2014. With 6 years of experience, Dr. Anil works with a patient-centric approach, developing personalized treatment plans for all his patients. Dr. Anil practices at the Clinikk Health Hub Banashankari, which is located at 100 ft Ring Road, Banashankari 3rd Stage, beside Sri Eshwari Theatre. Clinikk Health Hubs are a chain of 11 premium medical centers by Clinikk across Bengaluru. With modern medical facilities, Clinikk Health Hub provides access to experienced doctors, medicines, and lab tests - all in one place.', '0', 0, '$2y$10$TYeoZqwBLfQum/wYf38lre3xpmpOh0i7.TR1OHjbk6LdxL5SkyKXy', 'NMC12345555', '2024-05-12 12:18:01', '2024-05-12 21:29:40'),
(13, 'Dr. Srikanth', 'Reddy', 'FeMale', 'sr87211@gmail.com', '9948129720', '1,30', 'MBBS', '7', '15.5414129', '78.9491593', 'Achampetta,Andhra Pradesh 523372', 'Dr. Ravikumar is an experienced General Physician who practices at the Clinikk Health Hub in Banashankari. He obtained his MBBS degree from Father Muller Medical College, Mangalore, in 2014. With 6 years of experience, Dr. Anil works with a patient-centric approach, developing personalized treatment plans for all his patients. Dr. Anil practices at the Clinikk Health Hub Banashankari, which is located at 100 ft Ring Road, Banashankari 3rd Stage, beside Sri Eshwari Theatre. Clinikk Health Hubs are a chain of 11 premium medical centers by Clinikk across Bengaluru. With modern medical facilities, Clinikk Health Hub provides access to experienced doctors, medicines, and lab tests - all in one place.', '0', 1, '$2y$10$vwqp6SC7sPDbvTmYUaE75u8t1IyM8ONU3jvplvU4Nt5TQfVINxEDa', 'NMC12345555', '2024-05-12 16:35:24', '2024-05-12 20:06:06');

-- --------------------------------------------------------

--
-- Table structure for table `insurance_requests`
--

CREATE TABLE `insurance_requests` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `gender` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `mobile` varchar(255) NOT NULL,
  `city` varchar(255) NOT NULL,
  `address` text NOT NULL,
  `pincode` varchar(255) NOT NULL,
  `description` longtext NOT NULL,
  `customer_id` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `internationalpatients`
--

CREATE TABLE `internationalpatients` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `gender` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `country` varchar(255) NOT NULL,
  `mobile_code` varchar(25) NOT NULL,
  `mobile` varchar(255) NOT NULL,
  `service_request` longtext NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `masterdatas`
--

CREATE TABLE `masterdatas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `icon` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `masterdatas`
--

INSERT INTO `masterdatas` (`id`, `name`, `icon`, `created_at`, `updated_at`) VALUES
(1, 'Doctors', 'icons/V6wTFncsNqDsNNl7YObmOJFKfSqvB4m1FcMpZHXz.png', '2024-05-11 11:43:29', '2024-05-11 11:43:29'),
(2, 'Hospitals', 'icons/8xZkDeYkd49ZjOFcQRZkKiLEIP1SmsWhVcDmK8Ck.png', '2024-05-11 11:44:29', '2024-05-11 11:44:29'),
(3, 'Diagnostics', 'icons/zKUtdbMDRE2xkpx5cPxTL7z4opKZAU8SaZx0lySE.png', '2024-05-11 11:45:46', '2024-05-11 11:45:46'),
(4, 'Pharmacy', 'icons/bIc7ln4oFAc7axs1Gw14utGfYCYd0pOFpEKavIcE.png', '2024-05-11 11:46:48', '2024-05-11 11:46:48'),
(5, 'Health Insurance', 'icons/MzCVFe1dLG0dhc0CjNP6t4G9MnD35ppjVZf7Tp8R.png', '2024-05-11 11:47:56', '2024-05-11 11:47:56');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(5, '2024_04_18_103135_create_hvr_doctors_table', 2),
(13, '2024_04_20_070345_create_doctor_speciality_name', 5),
(19, '2024_04_20_074354_specialists', 7),
(21, '2019_12_14_000001_create_personal_access_tokens_table', 8),
(22, '2024_04_20_100021_add_specialist_column_to_doctors_table', 8),
(23, '2014_10_12_000000_create_users_table', 9),
(24, '2014_10_12_100000_create_password_reset_tokens_table', 9),
(25, '2016_06_01_000001_create_oauth_auth_codes_table', 9),
(26, '2016_06_01_000002_create_oauth_access_tokens_table', 9),
(27, '2016_06_01_000003_create_oauth_refresh_tokens_table', 9),
(28, '2016_06_01_000004_create_oauth_clients_table', 9),
(29, '2016_06_01_000005_create_oauth_personal_access_clients_table', 9),
(30, '2019_08_19_000000_create_failed_jobs_table', 9);

-- --------------------------------------------------------

--
-- Table structure for table `oauth_access_tokens`
--

CREATE TABLE `oauth_access_tokens` (
  `id` varchar(100) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `client_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `scopes` text DEFAULT NULL,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `oauth_auth_codes`
--

CREATE TABLE `oauth_auth_codes` (
  `id` varchar(100) NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `client_id` bigint(20) UNSIGNED NOT NULL,
  `scopes` text DEFAULT NULL,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `oauth_clients`
--

CREATE TABLE `oauth_clients` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `secret` varchar(100) DEFAULT NULL,
  `provider` varchar(255) DEFAULT NULL,
  `redirect` text NOT NULL,
  `personal_access_client` tinyint(1) NOT NULL,
  `password_client` tinyint(1) NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `oauth_personal_access_clients`
--

CREATE TABLE `oauth_personal_access_clients` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `client_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `oauth_refresh_tokens`
--

CREATE TABLE `oauth_refresh_tokens` (
  `id` varchar(100) NOT NULL,
  `access_token_id` varchar(100) NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `personal_access_tokens`
--

INSERT INTO `personal_access_tokens` (`id`, `tokenable_type`, `tokenable_id`, `name`, `token`, `abilities`, `last_used_at`, `created_at`, `updated_at`) VALUES
(1, 'App\\Models\\User', 3, 'Token Name', 'bcf99656398f1c1fc3e7c2de8d155817e89fb24e0306a5cbeeac69c792c0d401', '[\"*\"]', NULL, '2024-04-20 14:43:52', '2024-04-20 14:43:52'),
(2, 'App\\Models\\User', 3, 'my-app-token', '113fb1608d06d61e300e67c13afb13bb2859f646d7d2200c57c9cb783a8471d5', '[\"*\"]', NULL, '2024-04-20 14:51:31', '2024-04-20 14:51:31'),
(3, 'App\\Models\\User', 3, 'my-app-token', 'ef8ec151b7bae8a4c9183594b69cc9f3163bba5cf2ed1152942d6c10dfe9fa7b', '[\"*\"]', '2024-04-26 17:05:50', '2024-04-20 14:56:09', '2024-04-26 17:05:50'),
(4, 'App\\Models\\hvr_doctors', 29, 'API TOKEN', '6dc1e696b605856d39aca53e5aa2b4ac7e04ddec6bfbc4b386ea4dc3c247d498', '[\"*\"]', NULL, '2024-04-21 01:30:13', '2024-04-21 01:30:13'),
(5, 'App\\Models\\hvr_doctors', 30, 'API TOKEN', 'a35bd815c58f60dfc7da949639881c9052b28600091803d43cedb5132bcef93c', '[\"*\"]', NULL, '2024-04-21 01:41:03', '2024-04-21 01:41:03'),
(6, 'App\\Models\\User', 3, 'my-app-token', '4bff8730a250535eaeb8eb0acc1871f5a6abdcd57b77cdc26dfe2c43fae8d651', '[\"*\"]', NULL, '2024-04-21 01:59:58', '2024-04-21 01:59:58'),
(7, 'App\\Models\\hvr_doctors', 30, 'my-app-token', '223202acb62b67409448ae7bffa18502b5c9d55a8bcea7cee5904ac5368f08f6', '[\"*\"]', NULL, '2024-04-21 02:06:38', '2024-04-21 02:06:38'),
(8, 'App\\Models\\hvr_doctors', 30, 'my-app-token', 'dcee3b22e3386af2ebeeb6cc4dccc6df3557af55d89ed9d621d03af8bc946527', '[\"*\"]', NULL, '2024-04-21 02:33:58', '2024-04-21 02:33:58'),
(9, 'App\\Models\\hvr_doctors', 30, 'my-app-token', '9e140f4d3d1751c4572203cbf89305704af27f3eb817b50f999a8f764b938108', '[\"*\"]', NULL, '2024-04-21 02:35:47', '2024-04-21 02:35:47'),
(10, 'App\\Models\\hvr_doctors', 30, 'my-app-token', '3a65a3807748de026542818b14351b103aec7e72cc4248b2d3e28b80fdff879c', '[\"*\"]', NULL, '2024-04-21 02:36:07', '2024-04-21 02:36:07'),
(11, 'App\\Models\\hvr_doctors', 30, 'my-app-token', 'efa3ed27a7221c464fdf2f751f6df6a798f084bc695bb5e2209c96c104e527a8', '[\"*\"]', NULL, '2024-04-21 02:36:21', '2024-04-21 02:36:21'),
(12, 'App\\Models\\hvr_doctors', 30, 'my-app-token', '50b453f703f7704eb94c1b8ddabe9b4219a2b21615567b9dbe072246b81d49a7', '[\"*\"]', NULL, '2024-04-21 02:38:04', '2024-04-21 02:38:04'),
(13, 'App\\Models\\User', 1, 'Token Name', 'ec3f9bdeea17ac6654646208edceaa1479f29e061620ab0d388d3a9e9b509956', '[\"*\"]', NULL, '2024-04-21 02:45:39', '2024-04-21 02:45:39'),
(14, 'App\\Models\\User', 1, 'Token Name', 'c053c4126a9df1c58996297fb54d3f89149de14fe485616fb581ef07d74aa5c9', '[\"*\"]', NULL, '2024-04-21 02:47:40', '2024-04-21 02:47:40'),
(15, 'App\\Models\\User', 1, 'Token Name', 'ba518fc794f3c543db70527aa5edb36433999cc991b57ee73e55647bcb2c6ee1', '[\"*\"]', NULL, '2024-04-21 02:47:48', '2024-04-21 02:47:48'),
(16, 'App\\Models\\hvr_doctors', 30, 'my-app-token', '7243be478b94602a2a5ca9c6d2ce589b8eb269bca38529bb35dc78dba6bcef27', '[\"*\"]', NULL, '2024-04-21 02:48:44', '2024-04-21 02:48:44'),
(17, 'App\\Models\\User', 3, 'my-app-token', '4a70fb4866726e7b1b91a3f8169413b0d4812a1edc63293393d3b1f5ed64e8e1', '[\"*\"]', NULL, '2024-04-22 11:44:00', '2024-04-22 11:44:00'),
(18, 'App\\Models\\hvr_doctors', 30, 'my-app-token', 'f9d469e6e59ae553d73594736baac48418da197d8c7afacd04ecb5e13798f0da', '[\"*\"]', NULL, '2024-04-22 11:44:17', '2024-04-22 11:44:17'),
(19, 'App\\Models\\Customers', 1, 'my-app-token', '2ff9fe8c481c53b6486dc28cafaaba19bc9178de27016782030ac410d4e50229', '[\"*\"]', NULL, '2024-04-23 14:20:15', '2024-04-23 14:20:15'),
(20, 'App\\Models\\Customers', 1, 'my-app-token', '20da7098b88494f0e46523bc31c97de39f15188f8056d82913ebae48b3269553', '[\"*\"]', NULL, '2024-04-23 14:23:34', '2024-04-23 14:23:34'),
(21, 'App\\Models\\Customers', 1, 'my-app-token', 'ead2bba309a8910ffaac5d606401a9aa74eed9ba464eca62373f85c9847cfa7f', '[\"*\"]', NULL, '2024-04-23 14:45:21', '2024-04-23 14:45:21'),
(22, 'App\\Models\\Customers', 1, 'my-app-token', '1d4dd4542abf83fb25031f14974a77e2abe13f6ccfc47d0a8701da6bf12eb0cf', '[\"*\"]', NULL, '2024-04-23 14:46:36', '2024-04-23 14:46:36'),
(23, 'App\\Models\\Customers', 1, 'my-app-token', 'e7970e3071fe5b15ad1973de0eb1cace05e13d6123ffe9b629177e6aa6f7a63a', '[\"*\"]', NULL, '2024-04-23 15:46:06', '2024-04-23 15:46:06'),
(24, 'App\\Models\\Customers', 1, 'my-app-token', '3d4fe203d9062b64a19e340fbf6d9b677a199ec3e7ba688b0954b68a8fecb578', '[\"*\"]', NULL, '2024-04-23 15:46:13', '2024-04-23 15:46:13'),
(25, 'App\\Models\\Customers', 1, 'my-app-token', 'd920ec1be14b09930bd778bc70ca70859f1d5643a6105b9c8ef88de4cc48d136', '[\"*\"]', NULL, '2024-04-23 15:46:29', '2024-04-23 15:46:29'),
(26, 'App\\Models\\Customers', 2, 'API TOKEN', 'f4fbd874a9bffdd5ae4e684ee298e464a78038e2980b74e1d97e492f955ff087', '[\"*\"]', NULL, '2024-04-23 16:28:05', '2024-04-23 16:28:05'),
(27, 'App\\Models\\Customers', 2, 'my-app-token', '66e421650a26bd1e78c1029b833d968dbf04416188fea99e3c6ec5c87ad50d0c', '[\"*\"]', NULL, '2024-04-23 16:30:48', '2024-04-23 16:30:48'),
(28, 'App\\Models\\Customers', 2, 'my-app-token', 'a7fffc4eb3179a4fb08fcefb317fcbbd4828e408c5f934bdc3800adf7f615976', '[\"*\"]', NULL, '2024-04-23 16:35:00', '2024-04-23 16:35:00'),
(29, 'App\\Models\\Customers', 1, 'my-app-token', '2d47290015d6b40ac4482352be587ae125e9ee98fc9a64cc7206fe694334179e', '[\"*\"]', NULL, '2024-04-23 16:42:32', '2024-04-23 16:42:32'),
(30, 'App\\Models\\Customers', 2, 'my-app-token', '2e2a4c5ddb6224430678fbb50c2bc9dc8516cd2f545549d074a4c8f1392f45e6', '[\"*\"]', NULL, '2024-04-23 18:49:34', '2024-04-23 18:49:34'),
(31, 'App\\Models\\Customers', 2, 'my-app-token', '487bab2c08f430d20da3bc20473e98e69299bf0871d6450e5f200659c803aa4f', '[\"*\"]', NULL, '2024-04-23 18:53:55', '2024-04-23 18:53:55'),
(32, 'App\\Models\\hvr_doctors', 30, 'my-app-token', '4939de93d0fd4ccac96f4a93bee26a605e34164c6f2b36d6a7a2c08a4fccbccc', '[\"*\"]', NULL, '2024-04-25 16:53:40', '2024-04-25 16:53:40'),
(33, 'App\\Models\\hvr_doctors', 30, 'my-app-token', '10b10833a2efcd8cf6f8711e29da8055ecb71206d920aad5baabca2ca0cb8235', '[\"*\"]', NULL, '2024-04-25 16:54:19', '2024-04-25 16:54:19'),
(34, 'App\\Models\\hvr_doctors', 31, 'API TOKEN', 'af82e580922e9740fe559423dfaad65869828c8b7bdf242ab1dfc75de67343ab', '[\"*\"]', NULL, '2024-04-26 16:13:23', '2024-04-26 16:13:23'),
(35, 'App\\Models\\hvr_doctors', 31, 'my-app-token', '0dcab040cd659527a2ef11011cc319f6276d3a4920d047f8184d6059d664865d', '[\"*\"]', NULL, '2024-04-26 16:13:41', '2024-04-26 16:13:41'),
(36, 'App\\Models\\hvr_doctors', 31, 'my-app-token', '45e43ef888b9a4bb838950f1a1f9e1d3d742bb1236e2646f9dd1a621c14b5871', '[\"*\"]', NULL, '2024-04-26 17:08:52', '2024-04-26 17:08:52'),
(37, 'App\\Models\\hvr_doctors', 31, 'my-app-token', '7d05508663e8461056e20d13766486c9d85fc5832b6cfec755aa11bc8a36add7', '[\"*\"]', NULL, '2024-04-27 20:05:37', '2024-04-27 20:05:37'),
(38, 'App\\Models\\hvr_doctors', 30, 'my-app-token', '5c04bd6da3cc17092e77f1b9cf492dc3c11180737631d6d4980ec1f93847325d', '[\"*\"]', NULL, '2024-04-27 20:40:48', '2024-04-27 20:40:48'),
(39, 'App\\Models\\hvr_doctors', 30, 'my-app-token', 'd7c970fefdc09665e72df90c65134f162a42a8474b607d3fa008f10431ada726', '[\"*\"]', NULL, '2024-04-27 20:43:33', '2024-04-27 20:43:33'),
(40, 'App\\Models\\hvr_doctors', 32, 'API TOKEN', '16c6aa2323ac470cfe91f09a32809dee1b26c23cf31e1466fe9f772c888d5b2f', '[\"*\"]', NULL, '2024-04-27 21:11:04', '2024-04-27 21:11:04'),
(41, 'App\\Models\\Customers', 2, 'my-app-token', 'e8b3541e9c3eae539e1b199e193be6ff143198b7de53c74a37cc430017516642', '[\"*\"]', NULL, '2024-04-28 02:30:50', '2024-04-28 02:30:50'),
(42, 'App\\Models\\Customers', 2, 'my-app-token', '6f27a1075ddcf5f9c7890d336717472c74ead077f4fc04bcf29c7b1c0c337c63', '[\"*\"]', NULL, '2024-04-28 10:44:49', '2024-04-28 10:44:49'),
(43, 'App\\Models\\hvr_doctors', 31, 'my-app-token', 'b20de108d1ec97d1da015afa782f3f9a8fd2c9f3465dbb859adadfd4d936a607', '[\"*\"]', NULL, '2024-04-28 10:45:25', '2024-04-28 10:45:25'),
(44, 'App\\Models\\hvr_doctors', 31, 'my-app-token', 'af03c57ff618c383898c0373c8d2ac64ec6cd298367850b5b5a49fb83578b22c', '[\"*\"]', NULL, '2024-04-28 10:47:07', '2024-04-28 10:47:07'),
(45, 'App\\Models\\Customers', 2, 'my-app-token', '8475ab8d9c23a83bd757dfe8a865ead5eda925bff16178daf93461a956426a88', '[\"*\"]', NULL, '2024-04-28 10:48:06', '2024-04-28 10:48:06'),
(46, 'App\\Models\\Customers', 2, 'my-app-token', '185a36cacb5c26d1c23f7b90f0c998103cbef71b3db3724f1d4162c8a7c5eca5', '[\"*\"]', NULL, '2024-04-28 10:48:11', '2024-04-28 10:48:11'),
(47, 'App\\Models\\Customers', 3, 'API TOKEN', '386626d502a7f373fb50e8ca09fd3a6a94eff9af044a992d1ae4af1f5dad4935', '[\"*\"]', NULL, '2024-04-28 11:27:45', '2024-04-28 11:27:45'),
(48, 'App\\Models\\Customers', 3, 'my-app-token', 'e71fc6a0de3a1a86c8f59cc02bc68a7413ce57d099b318ac9c4382ab306b20fc', '[\"*\"]', NULL, '2024-04-28 11:28:21', '2024-04-28 11:28:21'),
(49, 'App\\Models\\Customers', 4, 'API TOKEN', 'fa8db9a6c0380a6bb1c65f868fc67dbad4b6f717bf16776cdac4bcff3b893cdb', '[\"*\"]', NULL, '2024-04-28 11:37:29', '2024-04-28 11:37:29'),
(50, 'App\\Models\\Customers', 4, 'my-app-token', 'bce2b65e509bfdd327a8ef6d8ed0d704df2f9a81c44340f08149725844965317', '[\"*\"]', NULL, '2024-04-28 11:37:44', '2024-04-28 11:37:44'),
(51, 'App\\Models\\Customers', 4, 'my-app-token', '07e812cec4b5ce5f640006a08f8e2c9f4f2ae8fafe782523cccdb972754b6f63', '[\"*\"]', NULL, '2024-04-28 11:38:41', '2024-04-28 11:38:41'),
(52, 'App\\Models\\hvr_doctors', 33, 'API TOKEN', '0b07bf63abbd87d1c7e1a5f4089fe8668cca8f321b9f08eeec2125607dff5b58', '[\"*\"]', NULL, '2024-04-28 11:44:16', '2024-04-28 11:44:16'),
(53, 'App\\Models\\hvr_doctors', 33, 'my-app-token', 'd4710c9644552521fd721b340b465b65d875b1fe1112c010236f0b2e9770b077', '[\"*\"]', NULL, '2024-04-28 11:45:35', '2024-04-28 11:45:35'),
(54, 'App\\Models\\Customers', 3, 'my-app-token', '3b44f50b6d034ef9f2fb9526980313f670cc250c289f1b62f55357165ba2d8d1', '[\"*\"]', NULL, '2024-04-28 11:47:05', '2024-04-28 11:47:05'),
(55, 'App\\Models\\hvr_doctors', 34, 'API TOKEN', '89c78a3ad567486ad59fbe38a00daf10361815c34718983bcae5d437d3f6b331', '[\"*\"]', NULL, '2024-04-28 12:01:21', '2024-04-28 12:01:21'),
(56, 'App\\Models\\hvr_doctors', 35, 'API TOKEN', '2a065632bed23d3a0bfa161cca76aa6a8ab2bf44d9e06a23cb31594602ff1f6f', '[\"*\"]', NULL, '2024-04-28 12:02:52', '2024-04-28 12:02:52'),
(57, 'App\\Models\\hvr_doctors', 35, 'my-app-token', '090f2c657945ba81483e666702f7b35236cd0c15f7b10c5dc81652aba5c35b39', '[\"*\"]', NULL, '2024-04-28 12:07:50', '2024-04-28 12:07:50'),
(58, 'App\\Models\\hvr_doctors', 35, 'my-app-token', '4d256e092ad5af015fe7562e9c1ce23c89cbbcd6cd335249107ffc8fe44a66d8', '[\"*\"]', NULL, '2024-04-28 12:15:44', '2024-04-28 12:15:44'),
(59, 'App\\Models\\hvr_doctors', 36, 'API TOKEN', '929e6c23772a670f1796cc6b4766c2c53952c7a87bd6ca605103204b885c6490', '[\"*\"]', NULL, '2024-04-28 15:12:47', '2024-04-28 15:12:47'),
(60, 'App\\Models\\hvr_doctors', 36, 'my-app-token', 'f1be9d8810f0de3d7b0cbdb77ab92c063c1d2be786f484963e9cff1a9c257870', '[\"*\"]', NULL, '2024-04-28 15:13:35', '2024-04-28 15:13:35'),
(61, 'App\\Models\\Customers', 2, 'my-app-token', 'b4078c03049508c5d6efdba22faa3d4acb6446cec15cffdc09780283db5be94f', '[\"*\"]', NULL, '2024-04-28 15:15:49', '2024-04-28 15:15:49'),
(62, 'App\\Models\\Customers', 2, 'my-app-token', '3c2b4765783fbadd7a55625828f81223abf8912225941102858a6a7207bd6cff', '[\"*\"]', NULL, '2024-04-28 15:22:10', '2024-04-28 15:22:10'),
(63, 'App\\Models\\hvr_doctors', 32, 'my-app-token', '911d622b41a1ec6cd7782ef9c31e9da6dfc0676742523f3ce4286dc05a3e87db', '[\"*\"]', NULL, '2024-04-28 16:48:32', '2024-04-28 16:48:32'),
(64, 'App\\Models\\User', 1, 'my-app-token', '0cb7320e8fe06f2972cdd073b1d6f3df22a8c826b4078a212f9fcef7444f577c', '[\"*\"]', NULL, '2024-04-28 16:55:08', '2024-04-28 16:55:08'),
(65, 'App\\Models\\User', 1, 'my-app-token', 'f2a9e273ee21bc05474949b360f89f34c834b6b7930b819e56713f24e16afe8e', '[\"*\"]', NULL, '2024-04-28 16:56:45', '2024-04-28 16:56:45'),
(66, 'App\\Models\\User', 1, 'my-app-token', 'b502bdb5f03130cd77779a5862ce9df8b5adebab8ef3ed6a361484766e7a97fc', '[\"*\"]', NULL, '2024-04-28 18:23:29', '2024-04-28 18:23:29'),
(67, 'App\\Models\\User', 1, 'my-app-token', '2adef15f24bca611e0c7f661cb27837462ad08e87e3948423dd420d8ddc4ff4d', '[\"*\"]', NULL, '2024-04-28 18:31:39', '2024-04-28 18:31:39'),
(68, 'App\\Models\\User', 1, 'my-app-token', '5f2389fe36d05b5a07824d4a0e4507d5565c7884596b64f29021333185b7375b', '[\"*\"]', NULL, '2024-04-28 18:44:05', '2024-04-28 18:44:05'),
(69, 'App\\Models\\User', 1, 'my-app-token', '541c7d326c22056d7187cc1631b4867e2f24af418ba0a5697a28d8e0eeecb0cc', '[\"*\"]', NULL, '2024-04-28 18:51:12', '2024-04-28 18:51:12'),
(70, 'App\\Models\\hvr_doctors', 7, 'API TOKEN', '58215ae2bb065fe71a5edd4a29744e11bcf44fb74ba9a3716584151528d52929', '[\"*\"]', NULL, '2024-04-28 22:37:17', '2024-04-28 22:37:17'),
(71, 'App\\Models\\hvr_doctors', 7, 'my-app-token', 'a3b6cc5153b444aa6b87a4a803eafd733e57df6e535a45c58b7ce63ab12d9fa5', '[\"*\"]', NULL, '2024-04-28 22:38:02', '2024-04-28 22:38:02'),
(72, 'App\\Models\\hvr_doctors', 7, 'my-app-token', 'f4a3cefc1d5b17a793b515ca66f823b9cc6306c9470bbf967798791e0b164bc9', '[\"*\"]', '2024-04-28 22:43:13', '2024-04-28 22:42:53', '2024-04-28 22:43:13'),
(73, 'App\\Models\\hvr_doctors', 9, 'API TOKEN', '8976e2410d1e9de81c36483bad1304b558433cad979ce3d6b508834ef0854f09', '[\"*\"]', NULL, '2024-04-29 00:56:51', '2024-04-29 00:56:51'),
(74, 'App\\Models\\hvr_doctors', 9, 'my-app-token', '5ccb7ea3cf1d651bc2e2affc6dc348c7751cf5b29b43afb8e3b84b3832472803', '[\"*\"]', NULL, '2024-04-29 00:57:22', '2024-04-29 00:57:22'),
(75, 'App\\Models\\hvr_doctors', 10, 'API TOKEN', '92805de56dbe6de76734e746b61e2fe5bd4f37110c6b831341dc0eba3876bd17', '[\"*\"]', NULL, '2024-04-29 11:11:15', '2024-04-29 11:11:15'),
(76, 'App\\Models\\hvr_doctors', 11, 'API TOKEN', '4642520ed8e6c3c1489987327e8cf92186bdee40b0dc87713d31c9b651989839', '[\"*\"]', NULL, '2024-04-29 12:24:11', '2024-04-29 12:24:11'),
(77, 'App\\Models\\User', 5, 'my-app-token', 'e7308899fb29945ab7b3be6145ad20b025b5118c98751709f66f6da1590e2278', '[\"*\"]', NULL, '2024-04-29 12:25:21', '2024-04-29 12:25:21'),
(78, 'App\\Models\\User', 5, 'my-app-token', 'df051466baa3eba5d61305775fc03d2d3d095c9eb138d1c85d11498cb3de3ee1', '[\"*\"]', NULL, '2024-04-29 12:27:28', '2024-04-29 12:27:28'),
(79, 'App\\Models\\User', 11, 'my-app-token', '1a750d53128ac0ff42be8928c19d50eb67e3e575845c64ff8cbb9804101b6f1a', '[\"*\"]', NULL, '2024-04-29 12:31:02', '2024-04-29 12:31:02'),
(80, 'App\\Models\\User', 2, 'my-app-token', 'a2501c254efc7336d1b93edb3449aed4bfbfd07fbf31ae84ede1d5b3185167d1', '[\"*\"]', NULL, '2024-04-30 12:26:59', '2024-04-30 12:26:59'),
(81, 'App\\Models\\Customers', 12, 'API TOKEN', '862f935c1bd76591d1780df487d6e86764917040506ea070632082abacdaf5fe', '[\"*\"]', NULL, '2024-05-01 15:10:25', '2024-05-01 15:10:25'),
(82, 'App\\Models\\User', 12, 'my-app-token', '8f1316311d3e1e9c40733dcda07e1539eb94fc094d5c1a7d6f8d89c88d5a5ec7', '[\"*\"]', NULL, '2024-05-01 15:12:53', '2024-05-01 15:12:53'),
(83, 'App\\Models\\User', 5, 'my-app-token', '7764a1f553eb7a54148df88719255073858958f704ac52744ce38818f0946e75', '[\"*\"]', NULL, '2024-05-03 21:34:55', '2024-05-03 21:34:55'),
(84, 'App\\Models\\hvr_doctors', 13, 'API TOKEN', '39cb34f3495f5513bb60be4850f0769bcfade162ca622cc644230690d329bcdd', '[\"*\"]', NULL, '2024-05-03 21:42:28', '2024-05-03 21:42:28'),
(85, 'App\\Models\\hvr_doctors', 14, 'API TOKEN', '371931d8030c03c91b62ada12486d57aa6829814a968beb70f8e290acb96a6ea', '[\"*\"]', NULL, '2024-05-03 21:42:42', '2024-05-03 21:42:42'),
(86, 'App\\Models\\hvr_doctors', 15, 'API TOKEN', '7fd22a35c4b38374f0d91f87c2f90f59985a0b9a05186c45e00e85c7023b88d2', '[\"*\"]', NULL, '2024-05-03 21:42:57', '2024-05-03 21:42:57'),
(87, 'App\\Models\\User', 5, 'my-app-token', '784b608fda27194f7b1a0dbd799605f22635037b1a6e4f82076172c99e814eca', '[\"*\"]', NULL, '2024-05-06 19:53:06', '2024-05-06 19:53:06'),
(88, 'App\\Models\\Customers', 16, 'API TOKEN', '23ab1479b70889b36e5a2f754063649c24dba5f18652c6a4351d6c14a7d91b66', '[\"*\"]', NULL, '2024-05-06 23:18:15', '2024-05-06 23:18:15'),
(89, 'App\\Models\\User', 16, 'my-app-token', '2f0b97214d4d0a53fc5a75f80b024694c7abc26f0b75a49c02c8ac2d09f01bb7', '[\"*\"]', NULL, '2024-05-06 23:18:39', '2024-05-06 23:18:39'),
(90, 'App\\Models\\User', 3, 'my-app-token', 'd2a267e84c2790c5b30efef250335a96ee1c4ae46bfaf4e7a11cd02faf2e039b', '[\"*\"]', NULL, '2024-05-07 15:31:58', '2024-05-07 15:31:58'),
(91, 'App\\Models\\hvr_doctors', 17, 'API TOKEN', 'f204054d13e3b8c6a9a9bbbea935991e8743a4676feee05bcb84383c0f9be0b1', '[\"*\"]', NULL, '2024-05-07 16:23:59', '2024-05-07 16:23:59'),
(92, 'App\\Models\\User', 17, 'my-app-token', '4a05bf03de861db4240bd030843cdff5922a12ad25c19cc335356cdfcc262efe', '[\"*\"]', NULL, '2024-05-07 16:44:11', '2024-05-07 16:44:11'),
(93, 'App\\Models\\User', 16, 'my-app-token', '890795fb2d7e336eb93dc42a1b8743b28b348b623b362df49117e3b95d3a6daf', '[\"*\"]', NULL, '2024-05-07 17:48:22', '2024-05-07 17:48:22'),
(94, 'App\\Models\\User', 18, 'my-app-token', 'e52bb0309171a8a5316147ec5aea4b0331d6bb148b7e0a918723dd9a466a8a78', '[\"*\"]', NULL, '2024-05-09 10:10:19', '2024-05-09 10:10:19'),
(95, 'App\\Models\\User', 1, 'my-app-token', '370b200495c122f2f86144d9960ddae537b34be21386b5a086c49657a4658487', '[\"*\"]', NULL, '2024-05-09 10:59:15', '2024-05-09 10:59:15'),
(96, 'App\\Models\\User', 1, 'my-app-token', 'cb23c02a54da109b3de362105c0af21ba904987b0b4f1b82027cdddce1d63d4b', '[\"*\"]', NULL, '2024-05-09 10:59:45', '2024-05-09 10:59:45'),
(97, 'App\\Models\\User', 5, 'my-app-token', '6e289cd70bdcf6f7afe824e563f5ae4dab4f6b516e30bdea674c0f54416b3dba', '[\"*\"]', NULL, '2024-05-11 15:32:03', '2024-05-11 15:32:03'),
(98, 'App\\Models\\User', 7, 'my-app-token', '7ca66ef01c6e9b726895b321c03c05f31b2b4270298fd86b7402aeae654c8bd6', '[\"*\"]', NULL, '2024-05-11 20:40:50', '2024-05-11 20:40:50'),
(99, 'App\\Models\\User', 7, 'my-app-token', 'e109ffdaa62122e985d399d341fcc23f95434a17de2d64468aa8ea4052533359', '[\"*\"]', NULL, '2024-05-11 20:45:00', '2024-05-11 20:45:00'),
(100, 'App\\Models\\Customers', 12, 'API TOKEN', '7c8ebb5c935ad1da8aac2fccd79f37d9f39081d8018a10720d86046e337b0d6b', '[\"*\"]', NULL, '2024-05-12 16:28:09', '2024-05-12 16:28:09'),
(101, 'App\\Models\\User', 12, 'my-app-token', 'a599064140f243b8510a66212f4ddad60eb05f654f5af08d048cb85ad6965dfc', '[\"*\"]', NULL, '2024-05-12 16:29:11', '2024-05-12 16:29:11'),
(102, 'App\\Models\\User', 12, 'my-app-token', '2bae0e790d2fde32f00c17317e32a556f46a23cd7502fdda9b680b5df665e267', '[\"*\"]', NULL, '2024-05-12 16:51:48', '2024-05-12 16:51:48'),
(103, 'App\\Models\\User', 1, 'my-app-token', '33891bed83729c931a9a64f3df987895f77787c44978d3826507cc794a343499', '[\"*\"]', NULL, '2024-05-12 18:34:28', '2024-05-12 18:34:28'),
(104, 'App\\Models\\User', 12, 'my-app-token', 'b06914dce53d565c5a77417f75c6b5dfa0d76e5977d563be513797607bc7decb', '[\"*\"]', NULL, '2024-05-12 18:35:44', '2024-05-12 18:35:44'),
(105, 'App\\Models\\User', 13, 'my-app-token', '7d662361544f51c4a1c61666c18c7122b6736fc93282a34b3149f894bc39781f', '[\"*\"]', NULL, '2024-05-12 19:21:42', '2024-05-12 19:21:42'),
(106, 'App\\Models\\User', 12, 'my-app-token', '7dede107ff705f5c44da7013bafa2f8be0304420381819457e6e4cb35b52f77c', '[\"*\"]', NULL, '2024-05-12 19:25:31', '2024-05-12 19:25:31'),
(107, 'App\\Models\\User', 12, 'my-app-token', 'bd6705c33d59766eed1b607e01f99eed32c0853be3f8de73a0ab1d0eeb61d4df', '[\"*\"]', NULL, '2024-05-12 19:30:07', '2024-05-12 19:30:07'),
(108, 'App\\Models\\User', 13, 'my-app-token', 'e18655f25c6830eada3c11c5ca2395b21671e6ddf6ce5ad32fd72dcad6625ef2', '[\"*\"]', NULL, '2024-05-12 19:30:21', '2024-05-12 19:30:21'),
(109, 'App\\Models\\User', 1, 'my-app-token', '9f04f749b67ced0379ce8061e148de3942be881c6b1b5264b1040aafe1f368ad', '[\"*\"]', NULL, '2024-05-12 19:33:01', '2024-05-12 19:33:01'),
(110, 'App\\Models\\User', 1, 'my-app-token', '524ceb43f3b2d9bb97b3ffb9fad1ed590cfae7fa713992b8cccf8c415a1f157f', '[\"*\"]', NULL, '2024-05-12 19:35:29', '2024-05-12 19:35:29');

-- --------------------------------------------------------

--
-- Table structure for table `pharmacy`
--

CREATE TABLE `pharmacy` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `pharmacy_name` varchar(255) NOT NULL,
  `pharmacist_name` varchar(255) NOT NULL,
  `gender` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `mobile` varchar(255) NOT NULL,
  `Category` varchar(255) NOT NULL,
  `drug_licence_number` varchar(255) NOT NULL,
  `experience` varchar(255) NOT NULL,
  `profile_description` longtext NOT NULL,
  `logo` varchar(255) NOT NULL,
  `latitude` varchar(255) NOT NULL,
  `longitude` varchar(255) NOT NULL,
  `registered_address` varchar(255) NOT NULL,
  `status` int(11) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `specialists`
--

CREATE TABLE `specialists` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `speciality` varchar(255) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 1,
  `icon` varchar(250) NOT NULL,
  `category` varchar(100) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `specialists`
--

INSERT INTO `specialists` (`id`, `speciality`, `status`, `icon`, `category`, `created_at`, `updated_at`) VALUES
(1, 'General Medicine', 1, 'icons/P5GIYqdLPdXcIwawpo8uNeVQn2psksOsn3Z8mJGA.png', 'Doctors', '2024-04-20 04:00:50', '2024-05-11 16:36:13'),
(2, 'Pediatrics', 1, 'icons/YtaswgDgQqfIY408Q9oLXRfgg4txgBOpY6fgT0TF.png', 'Doctors', '2024-04-20 03:57:20', '2024-05-11 16:37:41'),
(3, 'Cardiology', 1, 'icons/mU4CVRV4qfxpovoyWqFzvrDcx1IPS53SFnI4CCSK.png', 'Doctors', NULL, '2024-05-11 16:42:06'),
(4, 'Orthopedics', 1, 'icons/METq5eZ0YWKFqPdhDf2etzA53UUzaZoK3VfUb62A.png', 'Doctors', NULL, '2024-05-11 16:39:55'),
(5, 'Neurology', 1, 'icons/xqwbs5soq4ULbIHuplkE9giP37Sh6qeil5L8H1Yr.png', 'Doctors', NULL, '2024-05-11 16:41:46'),
(22, 'Super Speciality Hospitals', 1, 'icons/ibFTpKY7uDw4VeYeEppz8OQ7rxVsozZjJREZT2cM.png', 'Hospitals', '2024-05-11 11:56:46', '2024-05-11 16:51:51'),
(23, 'Multi Speciality Hospitals', 1, 'icons/FNtIPep03em8DjWCbSGHuc95Qj7cLGAvt3sUK04v.png', 'Hospitals', '2024-05-11 11:58:19', '2024-05-11 16:52:19'),
(24, 'Nursing Homes', 1, 'icons/S3mTDH5jfkKC2w67MdtAnZonJUEtAgfZ2tDk6ccq.png', 'Hospitals', '2024-05-11 11:59:40', '2024-05-11 16:52:52'),
(25, 'Evening Clinics', 1, 'icons/IfKMGiqAY9QhveiLJwXEoaT05CoWfv8QDqcowHnH.png', 'Hospitals', '2024-05-11 12:01:19', '2024-05-11 16:53:24'),
(26, 'Radiology', 1, 'icons/DShrJbCTzLUkrRZJnEO6dG8OAn3uZZvGWd28hzS3.png', 'Diagnostics', '2024-05-11 16:16:08', '2024-05-11 16:22:46'),
(27, 'Lab', 1, 'icons/zq4ckOEmeNAxZHKKqAYyhDxyGZvZw6ESP5ykWosd.png', 'Diagnostics', '2024-05-11 16:25:50', '2024-05-11 16:25:50'),
(28, 'Pharmacy', 1, 'icons/3ElZujGRoByszCqCRX42evSjbbfC3U19Jo9EQvEe.png', 'Pharmacy', '2024-05-11 17:33:20', '2024-05-11 17:33:20'),
(29, 'Surgicals', 1, 'icons/C8o1lB8xapzolgRaSAQjDyxZyKA5kczu3xQo22n7.png', 'Pharmacy', '2024-05-11 17:34:16', '2024-05-11 17:34:16'),
(30, 'Dentist', 1, 'icons/21U4RcYJUp19gxDnJVH2CEwgHmDBJcd377k2ofpn.png', 'Doctors', '2024-05-12 12:09:04', '2024-05-12 12:12:44'),
(31, 'Gynecologist', 1, 'icons/cgY1yDMyqnUoksKKBkYxKz8KFFbybcgqXjAV7ZgH.png', 'Doctors', '2024-05-12 12:14:30', '2024-05-12 12:14:30');

-- --------------------------------------------------------

--
-- Table structure for table `upload_images_documents`
--

CREATE TABLE `upload_images_documents` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `document_url` varchar(255) NOT NULL,
  `document_type` varchar(255) NOT NULL,
  `uploaded_user_id` varchar(255) NOT NULL,
  `uploaded_user_type` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(30) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(250) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `roles` varchar(50) DEFAULT 'Admin',
  `status` varchar(50) DEFAULT 'Active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `roles`, `status`) VALUES
(1, 'John Doe', 'ramthamail@gmail.com', '2024-04-20 20:19:37', '$2y$10$JChcnsIEdB0568b9eOTCPeSALtps0PME3VgPCFEYKHpH2YHpK3d7a', NULL, NULL, '2024-04-28 16:52:19', 'Admin', 'Active'),
(2, 'Dr Harsha Vardhan', 'sheker.kasap@gmail.com', '2024-04-28 22:26:03', '$2y$10$DyVvFnFDjXxN.XjIpG/ZtOBMoyLhNRmcByqh1sG2hmMY7Wl6RbNni', NULL, NULL, NULL, 'Doctor', 'Active'),
(6, 'Chandra Sheker K', 'cskconcepts@gmail.com', NULL, '$2y$10$18mO/XpCMuzusrgdfrPIJOXyDra5kXlfmYjYmfzds5eyIBXeLmYqa', NULL, NULL, NULL, 'Customer', 'Active'),
(7, 'Dr Srikanth Reddy', 'yeddulasrikanthreddy@gmail.com', NULL, '$2y$10$J43pVNMreQXkZXljuzC1s.xRYI1Nq9r5Lv0tLyh1t3UXtoohnEvtm', NULL, '2024-05-11 20:39:08', '2024-05-11 20:39:08', 'Doctor', 'Active'),
(8, 'Dr. Azad SMD', 'azad@gmail.com', NULL, '$2y$10$Ksz/zPfzNa8XXNrZPpCnDub/qU7mkfTrrVLlDrHqChHNJ3/HY3aJa', NULL, '2024-05-12 12:11:11', '2024-05-12 12:11:11', 'Doctor', 'Active'),
(9, 'Dr. Vital G', 'vital@gmail.com', NULL, '$2y$10$xfuTx2KRw2sQYOMs0sbsBut5JpClnSYg8NAZtTthAY8i7CStS.iXG', NULL, '2024-05-12 12:17:03', '2024-05-12 12:17:03', 'Doctor', 'Active'),
(10, 'Dr. Sunitha G', 'sunitha@gmail.com', NULL, '$2y$10$B.0h.f2lNMoARw2w783n1.hQ.sISqidtFLasNBfOUnB.GpNZxvnYu', NULL, '2024-05-12 12:17:31', '2024-05-12 12:17:31', 'Doctor', 'Active'),
(11, 'Dr. Ravi Kumar', 'ravikumar@gmail.com', NULL, '$2y$10$p1qg4gBpOzYp9nlsQ.r8/uWbmVQdx4ZLc0e4f/5CHMatCqaP57Dz6', NULL, '2024-05-12 12:18:01', '2024-05-12 12:18:01', 'Doctor', 'Active'),
(12, 'Srikanth Reddy', 'yeddulasrikanthreddy@gmail.com', NULL, '$2y$10$VuV8./bqoXmeOq9pXeyd8OjcswnCOb6blZscYX9Lh.0HfV7iIDBpC', NULL, '2024-05-12 16:28:05', '2024-05-12 16:28:05', 'Customer', 'Active'),
(13, 'Dr. Srikanth Reddy', 'sr87211@gmail.com', NULL, '$2y$10$uQNIeS0nlYfaZSB3oVpU1.akgYmnFYR2IYP4UBJQFk2bgm2NkU4ue', NULL, '2024-05-12 16:35:23', '2024-05-12 16:35:23', 'Doctor', 'Active'),
(14, 'PRK Multinational Hospital', 'zpomail@yail.com', NULL, '$2y$10$EgqmRmV.6.9ilNQXhHDfnOyC6M4pHqyjUNog1LHZfgb6S3BEG27hu', NULL, '2024-05-12 21:11:22', '2024-05-12 21:11:22', 'Hospital', 'Active'),
(15, 'Lakshmi Srinivasa Multi Speciality Hospital', 'yeddulasrikanthreddy+01@gmail.com', NULL, '$2y$10$Rf9KZ5zVn88HmrI296KxAedoskyIkz1D.c1URBKG.ZKZ3piThmwRC', NULL, '2024-05-12 23:31:23', '2024-05-12 23:31:23', 'Hospital', 'Active'),
(16, 'Life Line Diagnostics', 'sr87211+22@gmail.com', NULL, '$2y$10$xL.S9Jl6B1GDzEHXUNh9aeSuhtxC7v9cObWRaQtzSt4tXkv4y.Gdq', NULL, '2024-05-13 00:51:42', '2024-05-13 00:51:42', 'Diagnositcs', 'Active'),
(17, 'Life Line Diagnostics', 'sr87211+23@gmail.com', NULL, '$2y$10$9IB27NyCT87lx.vZquigCuatsgMDt0Zs4ontG1pBIEjmljqkEatCu', NULL, '2024-05-13 00:53:10', '2024-05-13 00:53:10', 'Diagnositcs', 'Active'),
(18, 'Hema Diagnostics', 'sr87211+10@gmail.com', NULL, '$2y$10$McLEMXGHAMp0jSjT/PdcreXkG8X.Lus7/vJdet5mK0pRNnu5g/AA2', NULL, '2024-05-13 00:54:37', '2024-05-13 00:54:37', 'Diagnositcs', 'Active');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `appcategoryconfigs`
--
ALTER TABLE `appcategoryconfigs`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indexes for table `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indexes for table `Appointment_history`
--
ALTER TABLE `Appointment_history`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `availabilities`
--
ALTER TABLE `availabilities`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indexes for table `country`
--
ALTER TABLE `country`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `customer_email_unique` (`email`),
  ADD UNIQUE KEY `customer_mobile_number_unique` (`mobile_number`);

--
-- Indexes for table `diagnositcs`
--
ALTER TABLE `diagnositcs`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indexes for table `doctor_speciality_name`
--
ALTER TABLE `doctor_speciality_name`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `favorites`
--
ALTER TABLE `favorites`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hospitals`
--
ALTER TABLE `hospitals`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indexes for table `hvr_doctors`
--
ALTER TABLE `hvr_doctors`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_email` (`email`);

--
-- Indexes for table `insurance_requests`
--
ALTER TABLE `insurance_requests`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indexes for table `internationalpatients`
--
ALTER TABLE `internationalpatients`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indexes for table `masterdatas`
--
ALTER TABLE `masterdatas`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `oauth_access_tokens`
--
ALTER TABLE `oauth_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_access_tokens_user_id_index` (`user_id`);

--
-- Indexes for table `oauth_auth_codes`
--
ALTER TABLE `oauth_auth_codes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_auth_codes_user_id_index` (`user_id`);

--
-- Indexes for table `oauth_clients`
--
ALTER TABLE `oauth_clients`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_clients_user_id_index` (`user_id`);

--
-- Indexes for table `oauth_personal_access_clients`
--
ALTER TABLE `oauth_personal_access_clients`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `oauth_refresh_tokens`
--
ALTER TABLE `oauth_refresh_tokens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_refresh_tokens_access_token_id_index` (`access_token_id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `pharmacy`
--
ALTER TABLE `pharmacy`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indexes for table `specialists`
--
ALTER TABLE `specialists`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `upload_images_documents`
--
ALTER TABLE `upload_images_documents`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`,`roles`),
  ADD UNIQUE KEY `unique_email_role` (`email`,`roles`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `appcategoryconfigs`
--
ALTER TABLE `appcategoryconfigs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `appointments`
--
ALTER TABLE `appointments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `Appointment_history`
--
ALTER TABLE `Appointment_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `availabilities`
--
ALTER TABLE `availabilities`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `country`
--
ALTER TABLE `country`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=271;

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `id` bigint(30) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `diagnositcs`
--
ALTER TABLE `diagnositcs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `doctor_speciality_name`
--
ALTER TABLE `doctor_speciality_name`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `favorites`
--
ALTER TABLE `favorites`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT for table `hospitals`
--
ALTER TABLE `hospitals`
  MODIFY `id` bigint(30) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `hvr_doctors`
--
ALTER TABLE `hvr_doctors`
  MODIFY `id` bigint(30) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `insurance_requests`
--
ALTER TABLE `insurance_requests`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `internationalpatients`
--
ALTER TABLE `internationalpatients`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `masterdatas`
--
ALTER TABLE `masterdatas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `oauth_clients`
--
ALTER TABLE `oauth_clients`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `oauth_personal_access_clients`
--
ALTER TABLE `oauth_personal_access_clients`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=111;

--
-- AUTO_INCREMENT for table `pharmacy`
--
ALTER TABLE `pharmacy`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `specialists`
--
ALTER TABLE `specialists`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `upload_images_documents`
--
ALTER TABLE `upload_images_documents`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(30) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

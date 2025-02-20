-- -------------------------------------------------------------
-- TablePlus 6.2.1(578)
--
-- https://tableplus.com/
--
-- Database: db
-- Generation Time: 2025-02-20 08:42:22.5430
-- -------------------------------------------------------------


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


CREATE TABLE `cms_password_resets` (
  `reset_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `token` varchar(255) DEFAULT NULL,
  `created_date` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`reset_id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `cms_users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `first_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `created_date` datetime DEFAULT current_timestamp(),
  `updated_date` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `pages` (
  `page_id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `uri` varchar(255) DEFAULT NULL,
  `created_date` datetime DEFAULT current_timestamp(),
  `updated_date` datetime DEFAULT NULL,
  PRIMARY KEY (`page_id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `cms_password_resets` (`reset_id`, `user_id`, `token`, `created_date`) VALUES
(1, 1, 'a171d9574129ea8f5c15c5723ae8c27c3c0f065d7e3b659ba9e97beb7dd4d642', '2025-02-16 16:49:48'),
(2, 1, 'ffe7cd4cf722079399980ded08f5201f5811c1fc3dc90653b86b96e7921e3f9d', '2025-02-16 16:51:42'),
(3, 1, '9081cec2d3954046f36bdeb1b1af2842252c0a46ea78da7d9b35283b57dcceae', '2025-02-16 16:52:21'),
(4, 1, '73a0fdc5062a4576a1bc712183ee655b02fad8f466bf43feb76bab4ae85f5baa', '2025-02-16 16:54:05'),
(5, 1, 'e5ba1d6d3a86a2f656afcf8a63a80d5157cc8d9573e06cedc109cfbb8e55652e', '2025-02-16 16:56:56');

INSERT INTO `cms_users` (`user_id`, `email`, `password`, `first_name`, `last_name`, `created_date`, `updated_date`) VALUES
(1, 'mclark49@gmail.com', '$2y$10$OW5xrX2dKtSxxeaCLSS4rudgfRaNpmQDoOpJunNOGh25/RG5gPOzy', 'Michael', 'Clark', '2025-02-06 21:06:43', '2025-02-16 20:44:25');

INSERT INTO `pages` (`page_id`, `parent_id`, `title`, `slug`, `uri`, `created_date`, `updated_date`) VALUES
(1, NULL, 'About Us', 'about-us', '/about-us', '2025-02-08 12:13:05', NULL),
(2, NULL, 'Our Work', 'our-work', '/our-work', '2025-02-08 12:42:47', NULL),
(3, 2, 'Projects', 'projects', '/our-work/projects', '2025-02-08 12:45:25', NULL),
(4, 2, 'Inquire', 'inquire', '/our-work/inquire', '2025-02-08 17:00:57', NULL),
(5, NULL, 'Contact', 'contact', '/contact', '2025-02-08 17:22:56', NULL),
(8, 1, 'Our Team', 'our-team', '/about-us/our-team', '2025-02-19 23:44:38', NULL);



/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
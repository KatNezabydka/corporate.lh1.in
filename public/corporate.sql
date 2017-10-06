-- phpMyAdmin SQL Dump
-- version 4.7.3
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Окт 06 2017 г., 17:35
-- Версия сервера: 5.6.37
-- Версия PHP: 5.5.38

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `corporate`
--

-- --------------------------------------------------------

--
-- Структура таблицы `articles`
--

CREATE TABLE `articles` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `text` text COLLATE utf8_unicode_ci NOT NULL,
  `desc` text COLLATE utf8_unicode_ci NOT NULL,
  `alias` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `img` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_id` int(10) UNSIGNED NOT NULL DEFAULT '1',
  `category_id` int(10) UNSIGNED NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `articles`
--

INSERT INTO `articles` (`id`, `title`, `text`, `desc`, `alias`, `img`, `created_at`, `updated_at`, `user_id`, `category_id`) VALUES
(1, 'This is the title of the first articles. Enjoy it', '<p>Fusce rutrum lectus id nibh ullamcorper aliquet. Pellentesque pretium mauris consectetur aravida</p>\r\nLorem ipsum dolor sit amet, consectetur adipiscing elit. Donec mattis cursus interdum. Cras rhoncus lorem vitae tincidunt consectetur. Proin posuere eleifend turpis, quis elementum orci placerat id. Fusce euismod, tellus ut dapibus tempus, augue leo commodo est, rhoncus sollicitudin augue orci a neque. Nam vulputate ipsum ac ipsum molestie, iaculis gravida odio elementum. Nunc lobortis ipsum sed euismod vestibulum. Integer vel lacinia erat, id cursus diam. Suspendisse laoreet, est in blandit accumsan, libero neque congue nisl, mollis sagittis libero dui vitae urna. Integer nec maximus ipsum, maximus lacinia dolor. Duis scelerisque id risus ac facilisis. Suspendisse vitae est nibh. Cras mollis sapien leo, nec dictum massa suscipit non. Nullam fermentum, leo hendrerit semper vehicula, nibh nunc accumsan tortor, vitae ullamcorper nunc mi non lacus. Nullam porttitor bibendum viverra. Etiam nec accumsan nibh, non hendrerit nulla.\r\n', ' <p>Fusce nec accumsan eros. Aenean ac orci a magna vestibulum posuere quis nec nisi. Maecenas rutrum\r\n', 'article-1', '{\"mini\":\"003-55x55.jpg\",\"max\":\"003-816x282.jpg\",\"path\":\"0081-700x345.jpg\"}', '2017-09-28 21:00:00', NULL, 1, 3),
(2, 'This is the title of the second articles. Enjoy it', '<p>Fusce rutrum lectus id nibh ullamcorper aliquet. Pellentesque pretium mauris consectetur aravida</p>\r\nLorem ipsum dolor sit amet, consectetur adipiscing elit. Donec mattis cursus interdum. Cras rhoncus lorem vitae tincidunt consectetur. Proin posuere eleifend turpis, quis elementum orci placerat id. Fusce euismod, tellus ut dapibus tempus, augue leo commodo est, rhoncus sollicitudin augue orci a neque. Nam vulputate ipsum ac ipsum molestie, iaculis gravida odio elementum. Nunc lobortis ipsum sed euismod vestibulum. Integer vel lacinia erat, id cursus diam. Suspendisse laoreet, est in blandit accumsan, libero neque congue nisl, mollis sagittis libero dui vitae urna. Integer nec maximus ipsum, maximus lacinia dolor. Duis scelerisque id risus ac facilisis. Suspendisse vitae est nibh. Cras mollis sapien leo, nec dictum massa suscipit non. Nullam fermentum, leo hendrerit semper vehicula, nibh nunc accumsan tortor, vitae ullamcorper nunc mi non lacus. Nullam porttitor bibendum viverra. Etiam nec accumsan nibh, non hendrerit nulla.\r\n', '<p>Fusce rutrum lectus id nibh ullamcorper aliquet. Pellentesque pretium mauris consectetur aravida</p>\r\n', 'article-2', '{\"mini\":\"001-55x55.png\",\"max\":\"001-816x282.png\",\"path\":\"001-700x345.png\"}', '2017-09-28 21:00:00', NULL, 1, 2),
(3, 'This is the title of the thirt articles. Enjoy it', '<p>Fusce rutrum lectus id nibh ullamcorper aliquet. Pellentesque pretium mauris consectetur aravida</p>\r\nLorem ipsum dolor sit amet, consectetur adipiscing elit. Donec mattis cursus interdum. Cras rhoncus lorem vitae tincidunt consectetur. Proin posuere eleifend turpis, quis elementum orci placerat id. Fusce euismod, tellus ut dapibus tempus, augue leo commodo est, rhoncus sollicitudin augue orci a neque. Nam vulputate ipsum ac ipsum molestie, iaculis gravida odio elementum. Nunc lobortis ipsum sed euismod vestibulum. Integer vel lacinia erat, id cursus diam. Suspendisse laoreet, est in blandit accumsan, libero neque congue nisl, mollis sagittis libero dui vitae urna. Integer nec maximus ipsum, maximus lacinia dolor. Duis scelerisque id risus ac facilisis. Suspendisse vitae est nibh. Cras mollis sapien leo, nec dictum massa suscipit non. Nullam fermentum, leo hendrerit semper vehicula, nibh nunc accumsan tortor, vitae ullamcorper nunc mi non lacus. Nullam porttitor bibendum viverra. Etiam nec accumsan nibh, non hendrerit nulla.\r\n', '<p>Fusce rutrum lectus id nibh ullamcorper aliquet. Pellentesque pretium mauris consectetur aravida</p>\r\n', 'article-3', '{\"mini\":\"003-55x55.jpg\",\"max\":\"003-816x282.jpg\",\"path\":\"0081-700x345.jpg\"}', '2017-09-28 21:00:00', NULL, 1, 4);

-- --------------------------------------------------------

--
-- Структура таблицы `categories`
--

CREATE TABLE `categories` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(255) CHARACTER SET utf8 NOT NULL,
  `parent_id` int(11) NOT NULL DEFAULT '0',
  `alias` varchar(255) CHARACTER SET utf8 NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `categories`
--

INSERT INTO `categories` (`id`, `title`, `parent_id`, `alias`, `created_at`, `updated_at`) VALUES
(1, 'Блог', 0, 'blog', NULL, NULL),
(2, 'Компьютеры', 1, 'computers', '2017-09-28 21:00:00', NULL),
(3, 'Интересное', 1, 'intresting', '2017-09-28 21:00:00', NULL),
(4, 'Советы', 1, 'soveti', '2017-09-28 21:00:00', NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `comments`
--

CREATE TABLE `comments` (
  `id` int(10) UNSIGNED NOT NULL,
  `text` text COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `site` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `parent_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `article_id` int(10) UNSIGNED NOT NULL DEFAULT '1',
  `user_id` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `comments`
--

INSERT INTO `comments` (`id`, `text`, `name`, `email`, `site`, `parent_id`, `created_at`, `updated_at`, `article_id`, `user_id`) VALUES
(1, 'Hello World!!!', 'name1', 'email@mail.ru', 'http://site.ru', 0, '2017-10-01 21:00:00', NULL, 1, NULL),
(2, 'Hello beautiful World!!!', 'name2', 'email3@mail.ru', 'http://site.ru', 0, '2017-10-01 21:00:00', NULL, 1, 1),
(3, 'Привет Ребята', 'Kat', 'kat@ukr.net', '', 1, '2017-10-02 21:00:00', NULL, 1, 1),
(4, 'И снова здрасьте!', 'kat1', 'kat1@ukr.net', '', 3, '2017-10-02 21:00:00', NULL, 1, 1),
(36, '11111', 'Kat', 'Kat@kat.kat', '111', 0, '2017-10-06 10:48:57', '2017-10-06 10:48:57', 2, NULL),
(37, '22222', 'Kat2', 'Kat2@Kat2.Kat2', '222', 0, '2017-10-06 10:49:28', '2017-10-06 10:49:28', 2, NULL),
(38, '33333', 'Kat3', 'Kat3@Kat3.Kat3', '333', 36, '2017-10-06 10:52:54', '2017-10-06 10:52:54', 2, NULL),
(39, '55555', 'Kat', 'user@mail.ru', '555', 0, '2017-10-06 10:54:07', '2017-10-06 10:54:07', 2, NULL),
(40, 'Kat4', 'Kat4', 'Kat4@Kat4.ru', 'Kat4', 38, '2017-10-06 10:56:38', '2017-10-06 10:56:38', 2, NULL),
(41, 'Kat5', 'Kat5', 'Kat5@Kat5.Kat5', 'Kat5', 37, '2017-10-06 11:03:12', '2017-10-06 11:03:12', 2, NULL),
(42, 'Kat5', 'Kat5', 'Kat7@Kat5.Kat5', 'Kat5', 38, '2017-10-06 11:03:49', '2017-10-06 11:03:49', 2, NULL),
(43, 'Kat5', 'Kat0', 'Kat0@Kat5.Kat5', 'Kat5', 40, '2017-10-06 11:04:55', '2017-10-06 11:04:55', 2, NULL),
(44, 'Kat5', 'Kat8', 'Kat8@Kat5.Kat5', 'Kat5', 38, '2017-10-06 11:05:28', '2017-10-06 11:05:28', 2, NULL),
(45, 'Kat5', 'Kat11', 'Kat11@Kat5.Kat5', 'Kat5', 38, '2017-10-06 11:06:06', '2017-10-06 11:06:06', 2, NULL),
(46, 'Kat5', 'Kat11', 'Kat12@Kat5.Kat5', 'Kat5', 38, '2017-10-06 11:20:31', '2017-10-06 11:20:31', 2, NULL),
(47, 'Kat5', 'Kat11', 'Kat177@Kat5.Kat5', 'Kat5', 40, '2017-10-06 11:22:20', '2017-10-06 11:22:20', 2, NULL),
(48, 'Kat5', 'Kat11', 'Kat1767@Kat5.Kat5', 'Kat5', 36, '2017-10-06 11:27:49', '2017-10-06 11:27:49', 2, NULL),
(49, 'Kat5', 'Kat11', 'Kat177@Kat5.Kat5', 'Kat5', 38, '2017-10-06 11:28:17', '2017-10-06 11:28:17', 2, NULL),
(50, 'Kat5', 'Kat11', 'Kat147@Kat5.Kat5', 'Kat5', 43, '2017-10-06 11:29:58', '2017-10-06 11:29:58', 2, NULL),
(51, 'Kat5', 'Kat11', 'Katt7@Kat5.Kat5', 'Kat5', 38, '2017-10-06 11:34:08', '2017-10-06 11:34:08', 2, NULL),
(52, 'Kat5', 'Kat11', 'Katt787@Kat5.Kat5', 'Kat5', 36, '2017-10-06 11:34:39', '2017-10-06 11:34:39', 2, NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `filters`
--

CREATE TABLE `filters` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `alias` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `filters`
--

INSERT INTO `filters` (`id`, `title`, `alias`, `created_at`, `updated_at`) VALUES
(1, 'Brand Identity', 'brand-identity', '2017-09-28 21:00:00', NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `menus`
--

CREATE TABLE `menus` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `path` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `parent` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `menus`
--

INSERT INTO `menus` (`id`, `title`, `path`, `parent`, `created_at`, `updated_at`) VALUES
(1, 'Главная', 'http://corporate.loc', 0, NULL, NULL),
(2, 'Блог', 'http://corporate.loc/articles', 0, NULL, NULL),
(3, 'Компьютеры', 'http://corporate.loc/articles/cat/computers', 2, NULL, NULL),
(4, 'Интересное ', 'http://corporate.loc/articles/cat/intresting', 2, NULL, NULL),
(5, 'Советы', 'http://corporate.loc/articles/cat/soveti', 2, NULL, NULL),
(6, 'Портфолио ', 'http://corporate.loc/portfolios', 0, NULL, NULL),
(7, 'Контакты', 'http://corporate.loc/contacts', 0, NULL, NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `migrations`
--

CREATE TABLE `migrations` (
  `migration` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `migrations`
--

INSERT INTO `migrations` (`migration`, `batch`) VALUES
('2014_10_12_000000_create_users_table', 1),
('2014_10_12_100000_create_password_resets_table', 1),
('2017_09_29_081940_CreateArticlesTable', 1),
('2017_09_29_082526_CreatePortfoliosTable', 1),
('2017_09_29_083105_CreateFiltersTable', 1),
('2017_09_29_083221_CreateCommentsTable', 1),
('2017_09_29_083741_CreateSlidersTable', 1),
('2017_09_29_083913_CreateMenusTable', 1),
('2017_09_29_084037_CreateCategoriesTable', 1),
('2017_09_29_113801_ChangeArticlesTable', 2),
('2017_09_29_113816_ChangeCommentsTable', 2),
('2017_09_29_114330_ChangePortfoliosTable', 3);

-- --------------------------------------------------------

--
-- Структура таблицы `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `portfolios`
--

CREATE TABLE `portfolios` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `text` text COLLATE utf8_unicode_ci NOT NULL,
  `customer` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `alias` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `img` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `filter_alias` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `portfolios`
--

INSERT INTO `portfolios` (`id`, `title`, `text`, `customer`, `alias`, `img`, `created_at`, `updated_at`, `filter_alias`) VALUES
(1, 'Steep This!', 'Nullam volutpat, mauris scelerisque iaculis semper, justo odio rutrum urna, at cursus urna nisl et ipsum. Donec dapibus lacus nec sapien faucibus eget suscipit lorem mattis.\r\n', 'Steep This!', 'project1', '{\"mini\":\"0061-175x175.jpg\",\"max\":\"0061-770x368.jpg\",\"path\":\"0061.jpg\"}', '2017-09-28 21:00:00', NULL, 'brand-identity'),
(2, 'Kineda', 'Nullam volutpat, mauris scelerisque iaculis semper, justo odio rutrum urna, at cursus urna nisl et ipsum. Donec dapibus lacus nec sapien faucibus eget suscipit lorem mattis.\r\nDonec non mauris ac nulla consectetur pretium sit amet rhoncus neque. Maecenas aliquet, diam sed rhoncus vestibulum, sem lacus ultrices est, eu hendrerit tortor nulla in dui. Suspendisse enim purus, euismod interdum viverra eget, ultricies eu est. Maecenas dignissim mauris id est semper suscipit. Suspendisse venenatis vestibulum quam, quis porttitor arcu vestibulum et.\r\nSed porttitor eros ut purus elementum a consectetur purus vulputate', 'customer', 'project2', '{\"mini\":\"009-175x175.jpg\",\"max\":\"009-770x368.jpg\",\"path\":\"009.jpg\"}', '2017-09-28 21:00:00', NULL, 'brand-identity'),
(3, 'Love', 'Nullam volutpat, mauris scelerisque iaculis semper, justo odio rutrum urna, at cursus urna nisl et ipsum. Donec dapibus lacus nec sapien faucibus eget suscipit lorem mattis.\r\nDonec non mauris ac nulla consectetur pretium sit amet rhoncus neque. Maecenas aliquet, diam sed rhoncus vestibulum, sem lacus ultrices est, eu hendrerit tortor nulla in dui. Suspendisse enim purus, euismod interdum viverra eget, ultricies eu est. Maecenas dignissim mauris id est semper suscipit. Suspendisse venenatis vestibulum quam, quis porttitor arcu vestibulum et.\r\nSed porttitor eros ut purus elementum a consectetur purus vulputate', 'customer', 'project3', '{\"mini\":\"0043-175x175.jpg\",\"max\":\"0043-770x368.jpg\",\"path\":\"0043.jpg\"}', '2017-09-28 21:00:00', NULL, 'brand-identity'),
(4, 'Guanacos', 'Nullam volutpat, mauris scelerisque iaculis semper, justo odio rutrum urna, at cursus urna nisl et ipsum. Donec dapibus lacus nec sapien faucibus eget suscipit lorem mattis.\r\nDonec non mauris ac nulla consectetur pretium sit amet rhoncus neque. Maecenas aliquet, diam sed rhoncus vestibulum, sem lacus ultrices est, eu hendrerit tortor nulla in dui. Suspendisse enim purus, euismod interdum viverra eget, ultricies eu est. Maecenas dignissim mauris id est semper suscipit. Suspendisse venenatis vestibulum quam, quis porttitor arcu vestibulum et.\r\nSed porttitor eros ut purus elementum a consectetur purus vulputate', 'Steep This!', 'project4', '{\"mini\":\"0011-175x175.jpg\",\"max\":\"0011-770x368.jpg\",\"path\":\"0011.jpg\"}', '2017-09-28 21:00:00', NULL, 'brand-identity'),
(5, 'Octopus', 'Nullam volutpat, mauris scelerisque iaculis semper, justo odio rutrum urna, at cursus urna nisl et ipsum. Donec dapibus lacus nec sapien faucibus eget suscipit lorem mattis.\r\nDonec non mauris ac nulla consectetur pretium sit amet rhoncus neque. Maecenas aliquet, diam sed rhoncus vestibulum, sem lacus ultrices est, eu hendrerit tortor nulla in dui. Suspendisse enim purus, euismod interdum viverra eget, ultricies eu est. Maecenas dignissim mauris id est semper suscipit. Suspendisse venenatis vestibulum quam, quis porttitor arcu vestibulum et.\r\nSed porttitor eros ut purus elementum a consectetur purus vulputate', '', 'project9', '{\"mini\":\"0027-175x175.jpg\",\"max\":\"0027-770x368.jpg\",\"path\":\"0027.jpg\"}', '2017-09-28 21:00:00', NULL, 'brand-identity'),
(6, 'Miller Bob', 'Nullam volutpat, mauris scelerisque iaculis semper, justo odio rutrum urna, at cursus urna nisl et ipsum. Donec dapibus lacus nec sapien faucibus eget suscipit lorem mattis.\r\nDonec non mauris ac nulla consectetur pretium sit amet rhoncus neque. Maecenas aliquet, diam sed rhoncus vestibulum, sem lacus ultrices est, eu hendrerit tortor nulla in dui. Suspendisse enim purus, euismod interdum viverra eget, ultricies eu est. Maecenas dignissim mauris id est semper suscipit. Suspendisse venenatis vestibulum quam, quis porttitor arcu vestibulum et.\r\nSed porttitor eros ut purus elementum a consectetur purus vulputate', 'customer', 'project5', '{\"mini\":\"0034-175x175.jpg\",\"max\":\"0034-770x368.jpg\",\"path\":\"0034.jpg\"}', '2017-09-28 21:00:00', NULL, 'brand-identity'),
(7, 'Nili Studios', 'Nullam volutpat, mauris scelerisque iaculis semper, justo odio rutrum urna, at cursus urna nisl et ipsum. Donec dapibus lacus nec sapien faucibus eget suscipit lorem mattis.\r\nDonec non mauris ac nulla consectetur pretium sit amet rhoncus neque. Maecenas aliquet, diam sed rhoncus vestibulum, sem lacus ultrices est, eu hendrerit tortor nulla in dui. Suspendisse enim purus, euismod interdum viverra eget, ultricies eu est. Maecenas dignissim mauris id est semper suscipit. Suspendisse venenatis vestibulum quam, quis porttitor arcu vestibulum et.\r\nSed porttitor eros ut purus elementum a consectetur purus vulputate', '', 'project6', '{\"mini\":\"0052-175x175.jpg\",\"max\":\"0052-770x368.jpg\",\"path\":\"0052.jpg\"}', '2017-09-28 21:00:00', NULL, 'brand-identity'),
(8, 'Vitale Premium', 'Nullam volutpat, mauris scelerisque iaculis semper, justo odio rutrum urna, at cursus urna nisl et ipsum. Donec dapibus lacus nec sapien faucibus eget suscipit lorem mattis.\r\nDonec non mauris ac nulla consectetur pretium sit amet rhoncus neque. Maecenas aliquet, diam sed rhoncus vestibulum, sem lacus ultrices est, eu hendrerit tortor nulla in dui. Suspendisse enim purus, euismod interdum viverra eget, ultricies eu est. Maecenas dignissim mauris id est semper suscipit. Suspendisse venenatis vestibulum quam, quis porttitor arcu vestibulum et.\r\nSed porttitor eros ut purus elementum a consectetur purus vulputate', 'Steep This!', 'project7', '{\"mini\":\"0091-175x175.jpg\",\"max\":\"0091-770x368.jpg\",\"path\":\"0091.jpg\"}', '2017-09-28 21:00:00', NULL, 'brand-identity'),
(9, 'Digitpool Medien', 'Nullam volutpat, mauris scelerisque iaculis semper, justo odio rutrum urna, at cursus urna nisl et ipsum. Donec dapibus lacus nec sapien faucibus eget suscipit lorem mattis.\r\nDonec non mauris ac nulla consectetur pretium sit amet rhoncus neque. Maecenas aliquet, diam sed rhoncus vestibulum, sem lacus ultrices est, eu hendrerit tortor nulla in dui. Suspendisse enim purus, euismod interdum viverra eget, ultricies eu est. Maecenas dignissim mauris id est semper suscipit. Suspendisse venenatis vestibulum quam, quis porttitor arcu vestibulum et.\r\nSed porttitor eros ut purus elementum a consectetur purus vulputate', 'customer', 'project8', '{\"mini\":\"0081-175x175.jpg\",\"max\":\"0081-770x368.jpg\",\"path\":\"0081.jpg\"}', '2017-09-28 21:00:00', NULL, 'brand-identity');

-- --------------------------------------------------------

--
-- Структура таблицы `sliders`
--

CREATE TABLE `sliders` (
  `id` int(10) UNSIGNED NOT NULL,
  `img` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `desc` text COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `sliders`
--

INSERT INTO `sliders` (`id`, `img`, `title`, `desc`, `created_at`, `updated_at`) VALUES
(1, 'xx.jpg', ' <h2 style=\"color:#fff\">CORPORATE, MULTIPURPOSE.. <br /><span>PINK RIO</span></h2>', 'Nam id quam a odio euismod pellentesque. Etiam congue rutrum risus non vestibulum. Quisque a diam at ligula blandit consequat. Mauris ac mi velit, a tempor neque', NULL, NULL),
(2, '00314.jpg', '  <h2 style=\"color:#fff\">PINKRIO. <span>STRONG AND POWERFUL.</span></h2>', 'Nam id quam a odio euismod pellentesque. Etiam congue rutrum risus non vestibulum. Quisque a diam at ligula blandit consequat. Mauris ac mi velit, a tempor neque', NULL, NULL),
(3, 'dd.jpg', '  <h2 style=\"color:#fff\">PINKRIO. <span>STRONG AND POWERFUL.</span></h2>', 'Nam id quam a odio euismod pellentesque. Etiam congue rutrum risus non vestibulum. Quisque a diam at ligula blandit consequat. Mauris ac mi velit, a tempor neque', NULL, NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'User', 'user@mail.ru', '$2y$10$EhUXFKK5VxeObftObH5uZeJhSE.ab7V/0gxxx9o/xyOD/eePbX58.', NULL, '2017-09-29 09:23:12', '2017-09-29 09:23:12'),
(2, 'User', 'user1@mail.ru', '$2y$10$b9hx5zWkd7THJKwPp4UhYuAccV6EbVvq0cDrKF0ptr2rhkzLCbjZK', 'LBhzqmodnqS7rxmuzrtRRRhb5XKehSamQoWAP61Wz8Yp2QFqc4peZs8pNxVZ', '2017-10-06 09:44:21', '2017-10-06 09:45:22'),
(3, 'User', 'user2@mail.ru', '$2y$10$oks5kzQkDwEmEMKRDzs.xuin0FuHFuyl5O3piBXf9UMRfRQAKZq8a', NULL, '2017-10-06 10:38:39', '2017-10-06 10:38:39');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `articles`
--
ALTER TABLE `articles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `articles_alias_unique` (`alias`),
  ADD KEY `articles_user_id_foreign` (`user_id`),
  ADD KEY `articles_category_id_foreign` (`category_id`);

--
-- Индексы таблицы `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `categories_alias_unique` (`alias`);

--
-- Индексы таблицы `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `comments_article_id_foreign` (`article_id`),
  ADD KEY `comments_user_id_foreign` (`user_id`);

--
-- Индексы таблицы `filters`
--
ALTER TABLE `filters`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `filters_alias_unique` (`alias`);

--
-- Индексы таблицы `menus`
--
ALTER TABLE `menus`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`),
  ADD KEY `password_resets_token_index` (`token`);

--
-- Индексы таблицы `portfolios`
--
ALTER TABLE `portfolios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `portfolios_alias_unique` (`alias`),
  ADD KEY `portfolios_filter_alias_foreign` (`filter_alias`);

--
-- Индексы таблицы `sliders`
--
ALTER TABLE `sliders`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `articles`
--
ALTER TABLE `articles`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT для таблицы `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT для таблицы `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;
--
-- AUTO_INCREMENT для таблицы `filters`
--
ALTER TABLE `filters`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT для таблицы `menus`
--
ALTER TABLE `menus`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT для таблицы `portfolios`
--
ALTER TABLE `portfolios`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT для таблицы `sliders`
--
ALTER TABLE `sliders`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `articles`
--
ALTER TABLE `articles`
  ADD CONSTRAINT `articles_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`),
  ADD CONSTRAINT `articles_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Ограничения внешнего ключа таблицы `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_article_id_foreign` FOREIGN KEY (`article_id`) REFERENCES `articles` (`id`),
  ADD CONSTRAINT `comments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Ограничения внешнего ключа таблицы `portfolios`
--
ALTER TABLE `portfolios`
  ADD CONSTRAINT `portfolios_filter_alias_foreign` FOREIGN KEY (`filter_alias`) REFERENCES `filters` (`alias`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

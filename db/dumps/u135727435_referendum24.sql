-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Час створення: Січ 15 2026 р., 15:38
-- Версія сервера: 11.8.3-MariaDB-log
-- Версія PHP: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База даних: `u135727435_referendum24`
--

-- --------------------------------------------------------

--
-- Структура таблиці `auth_assignment`
--

CREATE TABLE `auth_assignment` (
  `item_name` varchar(64) NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `created_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблиці `auth_item`
--

CREATE TABLE `auth_item` (
  `name` varchar(64) NOT NULL,
  `type` smallint(6) NOT NULL,
  `description` text DEFAULT NULL,
  `rule_name` varchar(64) DEFAULT NULL,
  `data` blob DEFAULT NULL,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблиці `auth_item_child`
--

CREATE TABLE `auth_item_child` (
  `parent` varchar(64) NOT NULL,
  `child` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблиці `auth_rule`
--

CREATE TABLE `auth_rule` (
  `name` varchar(64) NOT NULL,
  `data` blob DEFAULT NULL,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблиці `country`
--

CREATE TABLE `country` (
  `id` int(10) UNSIGNED NOT NULL COMMENT 'ID',
  `name` varchar(255) NOT NULL COMMENT 'Name',
  `sorting_uk` smallint(6) NOT NULL DEFAULT 0,
  `sorting_ru` smallint(6) NOT NULL DEFAULT 0,
  `sorting_en` smallint(6) NOT NULL DEFAULT 0,
  `sorting_no` smallint(6) NOT NULL DEFAULT 0,
  `created_by` int(11) UNSIGNED DEFAULT NULL COMMENT 'Created by:',
  `updated_by` int(11) UNSIGNED DEFAULT NULL COMMENT 'Updated by:',
  `created_at` int(11) UNSIGNED DEFAULT NULL COMMENT 'Created at:',
  `updated_at` int(11) UNSIGNED DEFAULT NULL COMMENT 'Updated at:'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблиці `country_region`
--

CREATE TABLE `country_region` (
  `id` int(10) UNSIGNED NOT NULL COMMENT 'ID',
  `country_id` int(11) UNSIGNED NOT NULL COMMENT 'Country',
  `name` varchar(255) NOT NULL COMMENT 'Name',
  `created_by` int(11) UNSIGNED DEFAULT NULL COMMENT 'Created by:',
  `updated_by` int(11) UNSIGNED DEFAULT NULL COMMENT 'Updated by:',
  `created_at` int(11) UNSIGNED DEFAULT NULL COMMENT 'Created at:',
  `updated_at` int(11) UNSIGNED DEFAULT NULL COMMENT 'Updated at:'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблиці `language`
--

CREATE TABLE `language` (
  `id` int(10) UNSIGNED NOT NULL COMMENT 'ID',
  `name` varchar(255) NOT NULL COMMENT 'Name',
  `locale` char(10) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL COMMENT 'Locale',
  `title` varchar(255) NOT NULL COMMENT 'Title',
  `created_by` int(11) UNSIGNED DEFAULT NULL COMMENT 'Created by:',
  `updated_by` int(11) UNSIGNED DEFAULT NULL COMMENT 'Updated by:',
  `created_at` int(11) UNSIGNED DEFAULT NULL COMMENT 'Created at:',
  `updated_at` int(11) UNSIGNED DEFAULT NULL COMMENT 'Updated at:'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблиці `migration`
--

CREATE TABLE `migration` (
  `version` varchar(180) NOT NULL,
  `apply_time` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблиці `option_guest_vote`
--

CREATE TABLE `option_guest_vote` (
  `id` int(10) UNSIGNED NOT NULL COMMENT 'ID',
  `option_id` int(11) UNSIGNED NOT NULL COMMENT 'Option',
  `user_ip` bigint(11) UNSIGNED DEFAULT NULL COMMENT 'User IP',
  `ip_of_user` varchar(67) DEFAULT NULL COMMENT 'User IP',
  `date_add` timestamp NOT NULL DEFAULT current_timestamp() COMMENT 'Date add',
  `created_by` int(11) UNSIGNED DEFAULT NULL COMMENT 'Created by:',
  `updated_by` int(11) UNSIGNED DEFAULT NULL COMMENT 'Updated by:',
  `created_at` int(11) UNSIGNED DEFAULT NULL COMMENT 'Created at:',
  `updated_at` int(11) UNSIGNED DEFAULT NULL COMMENT 'Updated at:'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблиці `option_vote`
--

CREATE TABLE `option_vote` (
  `id` int(10) UNSIGNED NOT NULL COMMENT 'ID',
  `option_id` int(11) UNSIGNED NOT NULL COMMENT 'Option',
  `user_id` int(11) UNSIGNED DEFAULT NULL COMMENT 'User',
  `user_ip` bigint(20) UNSIGNED DEFAULT NULL COMMENT 'User IP',
  `date_add` timestamp NOT NULL DEFAULT current_timestamp() COMMENT 'Date add',
  `created_by` int(11) UNSIGNED DEFAULT NULL COMMENT 'Created by:',
  `updated_by` int(11) UNSIGNED DEFAULT NULL COMMENT 'Updated by:',
  `created_at` int(11) UNSIGNED DEFAULT NULL COMMENT 'Created at:',
  `updated_at` int(11) UNSIGNED DEFAULT NULL COMMENT 'Updated at:'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблиці `page`
--

CREATE TABLE `page` (
  `id` int(10) UNSIGNED NOT NULL COMMENT 'ID',
  `slug` varchar(128) NOT NULL COMMENT 'Slug',
  `language_id` int(11) UNSIGNED NOT NULL DEFAULT 1 COMMENT 'Language',
  `name` varchar(255) NOT NULL COMMENT 'Name',
  `title` varchar(255) NOT NULL COMMENT 'Title',
  `content` text DEFAULT NULL COMMENT 'Content',
  `describe` text DEFAULT NULL COMMENT 'Describe',
  `date_add` timestamp NOT NULL DEFAULT current_timestamp() COMMENT 'Date add',
  `date_update` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Date update',
  `created_by` int(11) UNSIGNED DEFAULT NULL COMMENT 'Created by:',
  `updated_by` int(11) UNSIGNED DEFAULT NULL COMMENT 'Updated by:',
  `created_at` int(11) UNSIGNED DEFAULT NULL COMMENT 'Created at:',
  `updated_at` int(11) UNSIGNED DEFAULT NULL COMMENT 'Updated at:',
  `scripts` text DEFAULT NULL COMMENT 'Scripts'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблиці `poll`
--

CREATE TABLE `poll` (
  `id` int(10) UNSIGNED NOT NULL COMMENT 'ID',
  `title` varchar(255) NOT NULL COMMENT 'Title',
  `describe` text DEFAULT NULL COMMENT 'Describe',
  `user_id` int(11) UNSIGNED NOT NULL COMMENT 'User',
  `rating` int(11) NOT NULL DEFAULT 0 COMMENT 'Rating',
  `status` tinyint(4) NOT NULL COMMENT 'Status',
  `views` int(11) NOT NULL DEFAULT 0 COMMENT 'Views',
  `result_type` tinyint(4) NOT NULL DEFAULT 0 COMMENT 'Result type',
  `poll_language_id` int(11) UNSIGNED NOT NULL DEFAULT 1 COMMENT 'Language',
  `show_for_all_languages` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Show for all languages',
  `poll_sex` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Sex',
  `poll_country_id` int(11) UNSIGNED NOT NULL DEFAULT 1 COMMENT 'Country',
  `poll_region_id` int(11) UNSIGNED NOT NULL DEFAULT 1 COMMENT 'Region',
  `poll_city_id` int(11) UNSIGNED NOT NULL DEFAULT 1 COMMENT 'City',
  `poll_min_age` int(11) NOT NULL DEFAULT 0 COMMENT 'Poll min age',
  `poll_max_age` int(11) NOT NULL DEFAULT 100 COMMENT 'Poll max age',
  `votes_count_close` int(11) NOT NULL DEFAULT 0 COMMENT 'Votes count close',
  `date_add` timestamp NOT NULL DEFAULT current_timestamp() COMMENT 'Date add',
  `date_update` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Date update',
  `show_on_slider` tinyint(4) NOT NULL DEFAULT 0 COMMENT 'Show on slider',
  `created_by` int(11) UNSIGNED DEFAULT NULL COMMENT 'Created by:',
  `updated_by` int(11) UNSIGNED DEFAULT NULL COMMENT 'Updated by:',
  `created_at` int(11) UNSIGNED DEFAULT NULL COMMENT 'Created at:',
  `updated_at` int(11) UNSIGNED DEFAULT NULL COMMENT 'Updated at:'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблиці `poll_answer`
--

CREATE TABLE `poll_answer` (
  `id` int(10) UNSIGNED NOT NULL COMMENT 'ID',
  `poll_id` int(11) UNSIGNED NOT NULL COMMENT 'Poll',
  `user_id` int(11) UNSIGNED NOT NULL COMMENT 'User',
  `content` varchar(255) NOT NULL COMMENT 'Content',
  `status` tinyint(4) NOT NULL COMMENT 'Status',
  `rating` int(11) NOT NULL DEFAULT 0 COMMENT 'Rating',
  `date_add` timestamp NOT NULL DEFAULT current_timestamp() COMMENT 'Date add',
  `created_by` int(11) UNSIGNED DEFAULT NULL COMMENT 'Created by:',
  `updated_by` int(11) UNSIGNED DEFAULT NULL COMMENT 'Updated by:',
  `created_at` int(11) UNSIGNED DEFAULT NULL COMMENT 'Created at:',
  `updated_at` int(11) UNSIGNED DEFAULT NULL COMMENT 'Updated at:'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблиці `poll_comment`
--

CREATE TABLE `poll_comment` (
  `id` int(10) UNSIGNED NOT NULL COMMENT 'ID',
  `parent_id` int(11) UNSIGNED DEFAULT NULL COMMENT 'Parent',
  `poll_id` int(11) UNSIGNED NOT NULL COMMENT 'Poll',
  `user_id` int(11) UNSIGNED NOT NULL COMMENT 'User',
  `content` text NOT NULL COMMENT 'Content',
  `status` tinyint(4) NOT NULL COMMENT 'Status',
  `is_new` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Is new',
  `has_new` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Has_new',
  `read_by_parent` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Read by parent',
  `rating` int(11) NOT NULL DEFAULT 0 COMMENT 'Rating',
  `date_add` timestamp NOT NULL DEFAULT current_timestamp() COMMENT 'Date add',
  `date_update` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Date update',
  `created_by` int(11) UNSIGNED DEFAULT NULL COMMENT 'Created by:',
  `updated_by` int(11) UNSIGNED DEFAULT NULL COMMENT 'Updated by:',
  `created_at` int(11) UNSIGNED DEFAULT NULL COMMENT 'Created at:',
  `updated_at` int(11) UNSIGNED DEFAULT NULL COMMENT 'Updated at:'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблиці `poll_comment_rating`
--

CREATE TABLE `poll_comment_rating` (
  `id` int(10) UNSIGNED NOT NULL COMMENT 'ID',
  `poll_comment_id` int(11) UNSIGNED NOT NULL COMMENT 'Poll comment',
  `user_id` int(11) UNSIGNED NOT NULL COMMENT 'User',
  `rating` int(2) NOT NULL COMMENT 'Rrating',
  `date_add` timestamp NOT NULL DEFAULT current_timestamp() COMMENT 'Date add',
  `created_by` int(11) UNSIGNED DEFAULT NULL COMMENT 'Created by:',
  `updated_by` int(11) UNSIGNED DEFAULT NULL COMMENT 'Updated by:',
  `created_at` int(11) UNSIGNED DEFAULT NULL COMMENT 'Created at:',
  `updated_at` int(11) UNSIGNED DEFAULT NULL COMMENT 'Updated at:'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблиці `poll_option`
--

CREATE TABLE `poll_option` (
  `id` int(10) UNSIGNED NOT NULL COMMENT 'ID',
  `poll_id` int(11) UNSIGNED NOT NULL COMMENT 'Poll',
  `user_id` int(11) UNSIGNED NOT NULL COMMENT 'User',
  `title` varchar(255) NOT NULL COMMENT 'Title',
  `status` tinyint(4) NOT NULL COMMENT 'Status',
  `rating` int(11) NOT NULL DEFAULT 0 COMMENT 'Rating',
  `date_add` timestamp NOT NULL DEFAULT current_timestamp() COMMENT 'Date add',
  `date_update` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Date update',
  `created_by` int(11) UNSIGNED DEFAULT NULL COMMENT 'Created by:',
  `updated_by` int(11) UNSIGNED DEFAULT NULL COMMENT 'Updated by:',
  `created_at` int(11) UNSIGNED DEFAULT NULL COMMENT 'Created at:',
  `updated_at` int(11) UNSIGNED DEFAULT NULL COMMENT 'Updated at:'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблиці `poll_option_rating`
--

CREATE TABLE `poll_option_rating` (
  `id` int(10) UNSIGNED NOT NULL COMMENT 'ID',
  `poll_option_id` int(11) UNSIGNED NOT NULL COMMENT 'Poll option',
  `user_id` int(11) UNSIGNED NOT NULL COMMENT 'User',
  `rating` int(11) NOT NULL COMMENT 'Rating',
  `date_add` timestamp NOT NULL DEFAULT current_timestamp() COMMENT 'Date add',
  `created_by` int(11) UNSIGNED DEFAULT NULL COMMENT 'Created by:',
  `updated_by` int(11) UNSIGNED DEFAULT NULL COMMENT 'Updated by:',
  `created_at` int(11) UNSIGNED DEFAULT NULL COMMENT 'Created at:',
  `updated_at` int(11) UNSIGNED DEFAULT NULL COMMENT 'Updated at:'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблиці `poll_rating_vote`
--

CREATE TABLE `poll_rating_vote` (
  `id` int(10) UNSIGNED NOT NULL COMMENT 'ID',
  `poll_id` int(11) UNSIGNED NOT NULL COMMENT 'Poll',
  `user_id` int(11) UNSIGNED NOT NULL COMMENT 'User',
  `rating` int(2) NOT NULL COMMENT 'Rating',
  `date_add` timestamp NOT NULL DEFAULT current_timestamp() COMMENT 'Date add',
  `created_by` int(11) UNSIGNED DEFAULT NULL COMMENT 'Created by:',
  `updated_by` int(11) UNSIGNED DEFAULT NULL COMMENT 'Updated by:',
  `created_at` int(11) UNSIGNED DEFAULT NULL COMMENT 'Created at:',
  `updated_at` int(11) UNSIGNED DEFAULT NULL COMMENT 'Updated at:'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблиці `poll_static_text`
--

CREATE TABLE `poll_static_text` (
  `id` int(11) NOT NULL,
  `language_id` int(10) UNSIGNED NOT NULL,
  `content` text DEFAULT NULL,
  `heading` varchar(255) DEFAULT NULL,
  `meta_title` varchar(255) DEFAULT NULL,
  `meta_description` text DEFAULT NULL,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблиці `poll_tag`
--

CREATE TABLE `poll_tag` (
  `id` int(10) UNSIGNED NOT NULL COMMENT 'ID',
  `poll_id` int(11) UNSIGNED NOT NULL COMMENT 'Poll',
  `tag_id` int(11) UNSIGNED NOT NULL COMMENT 'Tag'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Дублююча структура для представлення `poll_vote_count`
-- (Див. нижче для фактичного подання)
--
CREATE TABLE `poll_vote_count` (
`poll_id` int(10) unsigned
,`vote_count` bigint(22)
,`guest_vote_count` bigint(21)
,`user_vote_count` bigint(21)
);

-- --------------------------------------------------------

--
-- Дублююча структура для представлення `poll_vote_count_guest`
-- (Див. нижче для фактичного подання)
--
CREATE TABLE `poll_vote_count_guest` (
`poll_id` int(11) unsigned
,`guest_vote_count` bigint(21)
);

-- --------------------------------------------------------

--
-- Дублююча структура для представлення `poll_vote_count_user`
-- (Див. нижче для фактичного подання)
--
CREATE TABLE `poll_vote_count_user` (
`poll_id` int(11) unsigned
,`user_vote_count` bigint(21)
);

-- --------------------------------------------------------

--
-- Структура таблиці `profile`
--

CREATE TABLE `profile` (
  `user_id` int(11) UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `lastname` varchar(128) DEFAULT NULL COMMENT 'Lastname',
  `city_id` int(11) UNSIGNED DEFAULT NULL COMMENT 'City',
  `region_id` int(11) UNSIGNED DEFAULT NULL COMMENT 'Region',
  `country_id` int(11) UNSIGNED DEFAULT NULL COMMENT 'Country',
  `date_birthday` date DEFAULT NULL COMMENT 'Date birthday',
  `public_email` varchar(255) DEFAULT NULL,
  `phone` varchar(128) DEFAULT NULL COMMENT 'Phone',
  `gender` tinyint(3) UNSIGNED DEFAULT NULL COMMENT 'Gender',
  `marital` tinyint(3) UNSIGNED DEFAULT NULL COMMENT 'Marital',
  `preferences` varchar(255) DEFAULT NULL COMMENT 'Preferences',
  `is_active` tinyint(3) DEFAULT 0 COMMENT 'Is active',
  `identity` varchar(255) DEFAULT NULL COMMENT 'Identity',
  `network` varchar(255) DEFAULT NULL COMMENT 'Network',
  `state` tinyint(3) UNSIGNED DEFAULT NULL COMMENT 'State',
  `date_add` datetime DEFAULT NULL COMMENT 'Date add',
  `date_update` datetime DEFAULT NULL COMMENT 'Date update',
  `is_admin` tinyint(3) UNSIGNED DEFAULT 0 COMMENT 'Is admin',
  `gravatar_email` varchar(255) DEFAULT NULL,
  `gravatar_id` varchar(32) DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `website` varchar(255) DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `timezone` varchar(40) DEFAULT NULL,
  `created_by` int(11) UNSIGNED DEFAULT NULL COMMENT 'Created by:',
  `updated_by` int(11) UNSIGNED DEFAULT NULL COMMENT 'Updated by:',
  `created_at` int(11) UNSIGNED DEFAULT NULL COMMENT 'Created at:',
  `updated_at` int(11) UNSIGNED DEFAULT NULL COMMENT 'Updated at:'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблиці `region_city`
--

CREATE TABLE `region_city` (
  `id` int(10) UNSIGNED NOT NULL COMMENT 'ID',
  `region_id` int(11) UNSIGNED NOT NULL COMMENT 'Region',
  `country_id` int(11) UNSIGNED NOT NULL COMMENT 'Country',
  `name` varchar(255) NOT NULL COMMENT 'Name',
  `created_by` int(11) UNSIGNED DEFAULT NULL COMMENT 'Created by:',
  `updated_by` int(11) UNSIGNED DEFAULT NULL COMMENT 'Updated by:',
  `created_at` int(11) UNSIGNED DEFAULT NULL COMMENT 'Created at:',
  `updated_at` int(11) UNSIGNED DEFAULT NULL COMMENT 'Updated at:'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблиці `social_account`
--

CREATE TABLE `social_account` (
  `id` int(11) NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `provider` varchar(255) NOT NULL,
  `client_id` varchar(255) NOT NULL,
  `data` text DEFAULT NULL,
  `code` varchar(32) DEFAULT NULL,
  `created_at` int(11) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `username` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблиці `tag`
--

CREATE TABLE `tag` (
  `id` int(10) UNSIGNED NOT NULL COMMENT 'ID',
  `name` varchar(255) NOT NULL COMMENT 'Name',
  `language_id` int(11) UNSIGNED DEFAULT NULL COMMENT 'Language',
  `description` text DEFAULT NULL COMMENT 'Description',
  `created_by` int(11) UNSIGNED DEFAULT NULL COMMENT 'Created by:',
  `updated_by` int(11) UNSIGNED DEFAULT NULL COMMENT 'Updated by:',
  `created_at` int(11) UNSIGNED DEFAULT NULL COMMENT 'Created at:',
  `updated_at` int(11) UNSIGNED DEFAULT NULL COMMENT 'Updated at:',
  `polls_count` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблиці `tag_static_faq`
--

CREATE TABLE `tag_static_faq` (
  `id` int(10) UNSIGNED NOT NULL,
  `language_id` int(10) UNSIGNED NOT NULL,
  `question` text NOT NULL,
  `answer` text NOT NULL,
  `position` int(11) NOT NULL DEFAULT 0,
  `created_at` int(10) UNSIGNED DEFAULT NULL,
  `updated_at` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

-- --------------------------------------------------------

--
-- Структура таблиці `tag_static_text`
--

CREATE TABLE `tag_static_text` (
  `id` int(10) UNSIGNED NOT NULL,
  `language_id` int(10) UNSIGNED NOT NULL,
  `content` text DEFAULT NULL,
  `heading` varchar(255) DEFAULT NULL,
  `meta_title` varchar(255) DEFAULT NULL,
  `meta_description` text DEFAULT NULL,
  `created_at` int(10) UNSIGNED DEFAULT NULL,
  `updated_at` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

-- --------------------------------------------------------

--
-- Структура таблиці `token`
--

CREATE TABLE `token` (
  `user_id` int(11) UNSIGNED NOT NULL,
  `code` varchar(32) NOT NULL,
  `created_at` int(11) NOT NULL,
  `type` smallint(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблиці `user`
--

CREATE TABLE `user` (
  `id` int(11) UNSIGNED NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `status` smallint(6) NOT NULL DEFAULT 10,
  `password_hash` varchar(60) NOT NULL,
  `auth_key` varchar(32) NOT NULL,
  `confirmed_at` int(11) DEFAULT NULL,
  `unconfirmed_email` varchar(255) DEFAULT NULL,
  `blocked_at` int(11) DEFAULT NULL,
  `registration_ip` varchar(45) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  `flags` int(11) NOT NULL DEFAULT 0,
  `last_login_at` int(11) DEFAULT NULL,
  `verification_token` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблиці `user_career`
--

CREATE TABLE `user_career` (
  `id` int(10) UNSIGNED NOT NULL COMMENT 'ID',
  `user_id` int(11) UNSIGNED NOT NULL COMMENT 'User',
  `country_id` int(11) UNSIGNED DEFAULT NULL COMMENT 'Country',
  `region_id` int(11) UNSIGNED DEFAULT NULL COMMENT 'Region',
  `city_id` int(11) UNSIGNED DEFAULT NULL COMMENT 'City',
  `company` varchar(255) DEFAULT NULL COMMENT 'Company',
  `office` varchar(255) DEFAULT NULL COMMENT 'Office',
  `year_begin` smallint(6) DEFAULT NULL COMMENT 'Year begin',
  `year_end` smallint(6) DEFAULT NULL COMMENT 'Year end',
  `date_add` timestamp NOT NULL DEFAULT current_timestamp() COMMENT 'Date add',
  `date_update` datetime NOT NULL COMMENT 'Date update',
  `created_by` int(11) UNSIGNED DEFAULT NULL COMMENT 'Created by:',
  `updated_by` int(11) UNSIGNED DEFAULT NULL COMMENT 'Updated by:',
  `created_at` int(11) UNSIGNED DEFAULT NULL COMMENT 'Created at:',
  `updated_at` int(11) UNSIGNED DEFAULT NULL COMMENT 'Updated at:'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблиці `user_data`
--

CREATE TABLE `user_data` (
  `id` int(10) UNSIGNED NOT NULL COMMENT 'ID',
  `login` varchar(255) DEFAULT NULL COMMENT 'Login',
  `name` varchar(255) DEFAULT NULL COMMENT 'Name',
  `lastname` varchar(255) DEFAULT NULL COMMENT 'Lastname',
  `password` varchar(255) DEFAULT NULL COMMENT 'Password',
  `city_id` int(11) UNSIGNED DEFAULT NULL COMMENT 'City',
  `region_id` int(11) UNSIGNED DEFAULT NULL COMMENT 'Region',
  `country_id` int(11) UNSIGNED DEFAULT NULL COMMENT 'Country',
  `date_birthday` date DEFAULT NULL COMMENT 'Date birthday',
  `email` varchar(255) NOT NULL COMMENT 'Email',
  `phone` varchar(35) DEFAULT NULL COMMENT 'Phone',
  `sex` tinyint(1) DEFAULT NULL COMMENT 'Sex',
  `marital` tinyint(1) DEFAULT NULL COMMENT 'Marital',
  `preferences` varchar(255) DEFAULT NULL COMMENT 'Preferences',
  `is_active` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Is active',
  `identity` varchar(255) DEFAULT NULL COMMENT 'Identity',
  `network` varchar(255) DEFAULT NULL COMMENT 'Network',
  `state` tinyint(1) DEFAULT NULL COMMENT 'State',
  `date_add` datetime NOT NULL COMMENT 'Date add',
  `date_update` datetime NOT NULL COMMENT 'Date update',
  `is_admin` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Is admin'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблиці `user_high_education`
--

CREATE TABLE `user_high_education` (
  `id` int(10) UNSIGNED NOT NULL COMMENT 'ID',
  `user_id` int(11) UNSIGNED NOT NULL COMMENT 'User',
  `country_id` int(11) UNSIGNED DEFAULT NULL COMMENT 'Country',
  `region_id` int(11) UNSIGNED DEFAULT NULL COMMENT 'Region',
  `city_id` int(11) UNSIGNED DEFAULT NULL COMMENT 'City',
  `university` varchar(255) DEFAULT NULL COMMENT 'University',
  `faculty` varchar(255) DEFAULT NULL COMMENT 'Faculty',
  `speciality` varchar(255) DEFAULT NULL COMMENT 'Speciality',
  `status` varchar(255) DEFAULT NULL COMMENT 'Status',
  `year_begin` smallint(6) DEFAULT NULL COMMENT 'Year begin',
  `year_end` smallint(6) DEFAULT NULL COMMENT 'Year end',
  `date_add` timestamp NOT NULL DEFAULT current_timestamp() COMMENT 'Date add',
  `date_update` datetime NOT NULL COMMENT 'Date update',
  `created_by` int(11) UNSIGNED DEFAULT NULL COMMENT 'Created by:',
  `updated_by` int(11) UNSIGNED DEFAULT NULL COMMENT 'Updated by:',
  `created_at` int(11) UNSIGNED DEFAULT NULL COMMENT 'Created at:',
  `updated_at` int(11) UNSIGNED DEFAULT NULL COMMENT 'Updated at:'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблиці `user_interest`
--

CREATE TABLE `user_interest` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL COMMENT 'User',
  `activity` varchar(255) DEFAULT NULL COMMENT 'Activity',
  `interests` varchar(255) DEFAULT NULL COMMENT 'Interests',
  `music` varchar(255) DEFAULT NULL COMMENT 'Music',
  `films` varchar(255) DEFAULT NULL COMMENT 'Films',
  `shows` varchar(255) DEFAULT NULL COMMENT 'Shows',
  `books` varchar(255) DEFAULT NULL COMMENT 'Books',
  `games` varchar(255) DEFAULT NULL COMMENT 'Games',
  `date_add` timestamp NOT NULL DEFAULT current_timestamp() COMMENT 'Date add',
  `date_update` datetime NOT NULL COMMENT 'Date update',
  `created_by` int(11) UNSIGNED DEFAULT NULL COMMENT 'Created by:',
  `updated_by` int(11) UNSIGNED DEFAULT NULL COMMENT 'Updated by:',
  `created_at` int(11) UNSIGNED DEFAULT NULL COMMENT 'Created at:',
  `updated_at` int(11) UNSIGNED DEFAULT NULL COMMENT 'Updated at:'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблиці `user_language`
--

CREATE TABLE `user_language` (
  `id` int(10) UNSIGNED NOT NULL COMMENT 'ID',
  `user_id` int(11) UNSIGNED NOT NULL COMMENT 'User',
  `language_id` int(11) UNSIGNED NOT NULL COMMENT 'Language'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблиці `user_secondary_education`
--

CREATE TABLE `user_secondary_education` (
  `id` int(10) UNSIGNED NOT NULL COMMENT 'ID',
  `user_id` int(11) UNSIGNED NOT NULL COMMENT 'User',
  `country_id` int(11) UNSIGNED DEFAULT NULL COMMENT 'Country',
  `region_id` int(11) UNSIGNED DEFAULT NULL COMMENT 'Region',
  `city_id` int(11) UNSIGNED DEFAULT NULL COMMENT 'City',
  `school` varchar(255) DEFAULT NULL COMMENT 'School',
  `year_begin` smallint(6) DEFAULT NULL COMMENT 'Year begin',
  `year_end` smallint(6) DEFAULT NULL COMMENT 'Year end',
  `date_add` timestamp NOT NULL DEFAULT current_timestamp() COMMENT 'Date add',
  `date_update` datetime NOT NULL COMMENT 'Date update',
  `created_by` int(11) UNSIGNED DEFAULT NULL COMMENT 'Created by:',
  `updated_by` int(11) UNSIGNED DEFAULT NULL COMMENT 'Updated by:',
  `created_at` int(11) UNSIGNED DEFAULT NULL COMMENT 'Created at:',
  `updated_at` int(11) UNSIGNED DEFAULT NULL COMMENT 'Updated at:'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Індекси збережених таблиць
--

--
-- Індекси таблиці `auth_assignment`
--
ALTER TABLE `auth_assignment`
  ADD PRIMARY KEY (`item_name`,`user_id`),
  ADD KEY `idx-auth_assignment-user_id` (`user_id`);

--
-- Індекси таблиці `auth_item`
--
ALTER TABLE `auth_item`
  ADD PRIMARY KEY (`name`),
  ADD KEY `rule_name` (`rule_name`),
  ADD KEY `idx-auth_item-type` (`type`);

--
-- Індекси таблиці `auth_item_child`
--
ALTER TABLE `auth_item_child`
  ADD PRIMARY KEY (`parent`,`child`),
  ADD KEY `child` (`child`);

--
-- Індекси таблиці `auth_rule`
--
ALTER TABLE `auth_rule`
  ADD PRIMARY KEY (`name`);

--
-- Індекси таблиці `country`
--
ALTER TABLE `country`
  ADD PRIMARY KEY (`id`);

--
-- Індекси таблиці `country_region`
--
ALTER TABLE `country_region`
  ADD PRIMARY KEY (`id`),
  ADD KEY `country_id` (`country_id`);

--
-- Індекси таблиці `language`
--
ALTER TABLE `language`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD UNIQUE KEY `locale` (`locale`);

--
-- Індекси таблиці `migration`
--
ALTER TABLE `migration`
  ADD PRIMARY KEY (`version`);

--
-- Індекси таблиці `option_guest_vote`
--
ALTER TABLE `option_guest_vote`
  ADD PRIMARY KEY (`id`),
  ADD KEY `option_id` (`option_id`);

--
-- Індекси таблиці `option_vote`
--
ALTER TABLE `option_vote`
  ADD PRIMARY KEY (`id`),
  ADD KEY `option_id` (`option_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Індекси таблиці `page`
--
ALTER TABLE `page`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug_2_language` (`slug`,`language_id`),
  ADD KEY `language_id` (`language_id`);

--
-- Індекси таблиці `poll`
--
ALTER TABLE `poll`
  ADD PRIMARY KEY (`id`),
  ADD KEY `poll_language_id` (`poll_language_id`),
  ADD KEY `poll_region_id` (`poll_region_id`),
  ADD KEY `poll_country_id` (`poll_country_id`),
  ADD KEY `poll_city_id` (`poll_city_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Індекси таблиці `poll_answer`
--
ALTER TABLE `poll_answer`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `poll_id` (`poll_id`);

--
-- Індекси таблиці `poll_comment`
--
ALTER TABLE `poll_comment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `poll_id` (`poll_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `parent_id` (`parent_id`);

--
-- Індекси таблиці `poll_comment_rating`
--
ALTER TABLE `poll_comment_rating`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `comment_user` (`poll_comment_id`,`user_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Індекси таблиці `poll_option`
--
ALTER TABLE `poll_option`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `poll_id` (`poll_id`);

--
-- Індекси таблиці `poll_option_rating`
--
ALTER TABLE `poll_option_rating`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `poll_option_id` (`poll_option_id`,`user_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Індекси таблиці `poll_rating_vote`
--
ALTER TABLE `poll_rating_vote`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `poll_user` (`poll_id`,`user_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `poll_id` (`poll_id`);

--
-- Індекси таблиці `poll_static_text`
--
ALTER TABLE `poll_static_text`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `idx-poll_static_text-language_id` (`language_id`);

--
-- Індекси таблиці `poll_tag`
--
ALTER TABLE `poll_tag`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `poll_id` (`poll_id`,`tag_id`),
  ADD KEY `tag_id` (`tag_id`);

--
-- Індекси таблиці `profile`
--
ALTER TABLE `profile`
  ADD PRIMARY KEY (`user_id`),
  ADD KEY `city_id` (`city_id`),
  ADD KEY `region_id` (`region_id`),
  ADD KEY `country_id` (`country_id`);

--
-- Індекси таблиці `region_city`
--
ALTER TABLE `region_city`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_region_city_country1_idx` (`country_id`),
  ADD KEY `fk_region_city_country_region1_idx` (`region_id`);

--
-- Індекси таблиці `social_account`
--
ALTER TABLE `social_account`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `account_unique` (`provider`,`client_id`),
  ADD UNIQUE KEY `account_unique_code` (`code`),
  ADD KEY `fk_user_account` (`user_id`);

--
-- Індекси таблиці `tag`
--
ALTER TABLE `tag`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_tag_language1_idx` (`language_id`);

--
-- Індекси таблиці `tag_static_faq`
--
ALTER TABLE `tag_static_faq`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx-tag_static_faq-language_id-position` (`language_id`,`position`);

--
-- Індекси таблиці `tag_static_text`
--
ALTER TABLE `tag_static_text`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_tag_static_text_language_id` (`language_id`);

--
-- Індекси таблиці `token`
--
ALTER TABLE `token`
  ADD UNIQUE KEY `token_unique` (`user_id`,`code`,`type`);

--
-- Індекси таблиці `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_unique_username` (`username`),
  ADD UNIQUE KEY `user_unique_email` (`email`);

--
-- Індекси таблиці `user_career`
--
ALTER TABLE `user_career`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`),
  ADD KEY `country_id` (`country_id`),
  ADD KEY `city_id` (`city_id`),
  ADD KEY `region_id` (`region_id`);

--
-- Індекси таблиці `user_data`
--
ALTER TABLE `user_data`
  ADD PRIMARY KEY (`id`);

--
-- Індекси таблиці `user_high_education`
--
ALTER TABLE `user_high_education`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`),
  ADD KEY `city_id` (`city_id`),
  ADD KEY `country_id` (`country_id`),
  ADD KEY `region_id` (`region_id`);

--
-- Індекси таблиці `user_interest`
--
ALTER TABLE `user_interest`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`);

--
-- Індекси таблиці `user_language`
--
ALTER TABLE `user_language`
  ADD PRIMARY KEY (`id`),
  ADD KEY `language_id` (`language_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Індекси таблиці `user_secondary_education`
--
ALTER TABLE `user_secondary_education`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`),
  ADD KEY `country_id` (`country_id`),
  ADD KEY `city_id` (`city_id`),
  ADD KEY `region_id` (`region_id`);

--
-- AUTO_INCREMENT для збережених таблиць
--

--
-- AUTO_INCREMENT для таблиці `country`
--
ALTER TABLE `country`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID';

--
-- AUTO_INCREMENT для таблиці `country_region`
--
ALTER TABLE `country_region`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID';

--
-- AUTO_INCREMENT для таблиці `language`
--
ALTER TABLE `language`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID';

--
-- AUTO_INCREMENT для таблиці `option_guest_vote`
--
ALTER TABLE `option_guest_vote`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID';

--
-- AUTO_INCREMENT для таблиці `option_vote`
--
ALTER TABLE `option_vote`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID';

--
-- AUTO_INCREMENT для таблиці `page`
--
ALTER TABLE `page`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID';

--
-- AUTO_INCREMENT для таблиці `poll`
--
ALTER TABLE `poll`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID';

--
-- AUTO_INCREMENT для таблиці `poll_answer`
--
ALTER TABLE `poll_answer`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID';

--
-- AUTO_INCREMENT для таблиці `poll_comment`
--
ALTER TABLE `poll_comment`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID';

--
-- AUTO_INCREMENT для таблиці `poll_comment_rating`
--
ALTER TABLE `poll_comment_rating`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID';

--
-- AUTO_INCREMENT для таблиці `poll_option`
--
ALTER TABLE `poll_option`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID';

--
-- AUTO_INCREMENT для таблиці `poll_option_rating`
--
ALTER TABLE `poll_option_rating`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID';

--
-- AUTO_INCREMENT для таблиці `poll_rating_vote`
--
ALTER TABLE `poll_rating_vote`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID';

--
-- AUTO_INCREMENT для таблиці `poll_static_text`
--
ALTER TABLE `poll_static_text`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблиці `poll_tag`
--
ALTER TABLE `poll_tag`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID';

--
-- AUTO_INCREMENT для таблиці `region_city`
--
ALTER TABLE `region_city`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID';

--
-- AUTO_INCREMENT для таблиці `social_account`
--
ALTER TABLE `social_account`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблиці `tag`
--
ALTER TABLE `tag`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID';

--
-- AUTO_INCREMENT для таблиці `tag_static_faq`
--
ALTER TABLE `tag_static_faq`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблиці `tag_static_text`
--
ALTER TABLE `tag_static_text`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблиці `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблиці `user_career`
--
ALTER TABLE `user_career`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID';

--
-- AUTO_INCREMENT для таблиці `user_data`
--
ALTER TABLE `user_data`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID';

--
-- AUTO_INCREMENT для таблиці `user_high_education`
--
ALTER TABLE `user_high_education`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID';

--
-- AUTO_INCREMENT для таблиці `user_interest`
--
ALTER TABLE `user_interest`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблиці `user_language`
--
ALTER TABLE `user_language`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID';

--
-- AUTO_INCREMENT для таблиці `user_secondary_education`
--
ALTER TABLE `user_secondary_education`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID';

-- --------------------------------------------------------

--
-- Структура для представлення `poll_vote_count`
--
DROP TABLE IF EXISTS `poll_vote_count`;

CREATE ALGORITHM=UNDEFINED DEFINER=`u135727435_referendum24`@`127.0.0.1` SQL SECURITY DEFINER VIEW `poll_vote_count`  AS SELECT `p`.`id` AS `poll_id`, `ogv`.`guest_vote_count`+ `ov`.`user_vote_count` AS `vote_count`, `ogv`.`guest_vote_count` AS `guest_vote_count`, `ov`.`user_vote_count` AS `user_vote_count` FROM ((`poll` `p` left join `poll_vote_count_guest` `ogv` on(`p`.`id` = `ogv`.`poll_id`)) left join `poll_vote_count_user` `ov` on(`p`.`id` = `ov`.`poll_id`)) ;

-- --------------------------------------------------------

--
-- Структура для представлення `poll_vote_count_guest`
--
DROP TABLE IF EXISTS `poll_vote_count_guest`;

CREATE ALGORITHM=UNDEFINED DEFINER=`u135727435_referendum24`@`127.0.0.1` SQL SECURITY DEFINER VIEW `poll_vote_count_guest`  AS SELECT `po`.`poll_id` AS `poll_id`, count(`ogv`.`id`) AS `guest_vote_count` FROM (`poll_option` `po` left join `option_guest_vote` `ogv` on(`po`.`id` = `ogv`.`option_id`)) GROUP BY `po`.`poll_id` ;

-- --------------------------------------------------------

--
-- Структура для представлення `poll_vote_count_user`
--
DROP TABLE IF EXISTS `poll_vote_count_user`;

CREATE ALGORITHM=UNDEFINED DEFINER=`u135727435_referendum24`@`127.0.0.1` SQL SECURITY DEFINER VIEW `poll_vote_count_user`  AS SELECT `po`.`poll_id` AS `poll_id`, count(`ov`.`id`) AS `user_vote_count` FROM (`poll_option` `po` left join `option_vote` `ov` on(`po`.`id` = `ov`.`option_id`)) GROUP BY `po`.`poll_id` ;

--
-- Обмеження зовнішнього ключа збережених таблиць
--

--
-- Обмеження зовнішнього ключа таблиці `auth_assignment`
--
ALTER TABLE `auth_assignment`
  ADD CONSTRAINT `auth_assignment_ibfk_1` FOREIGN KEY (`item_name`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_user_auth_assignment` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE;

--
-- Обмеження зовнішнього ключа таблиці `auth_item`
--
ALTER TABLE `auth_item`
  ADD CONSTRAINT `auth_item_ibfk_1` FOREIGN KEY (`rule_name`) REFERENCES `auth_rule` (`name`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Обмеження зовнішнього ключа таблиці `auth_item_child`
--
ALTER TABLE `auth_item_child`
  ADD CONSTRAINT `auth_item_child_ibfk_1` FOREIGN KEY (`parent`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `auth_item_child_ibfk_2` FOREIGN KEY (`child`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Обмеження зовнішнього ключа таблиці `country_region`
--
ALTER TABLE `country_region`
  ADD CONSTRAINT `country_regions_ibfk_1` FOREIGN KEY (`country_id`) REFERENCES `country` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Обмеження зовнішнього ключа таблиці `option_guest_vote`
--
ALTER TABLE `option_guest_vote`
  ADD CONSTRAINT `fk_option_guest_vote_poll_option1` FOREIGN KEY (`option_id`) REFERENCES `poll_option` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Обмеження зовнішнього ключа таблиці `option_vote`
--
ALTER TABLE `option_vote`
  ADD CONSTRAINT `option_vote_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `option_vote_ibfk_2` FOREIGN KEY (`option_id`) REFERENCES `poll_option` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Обмеження зовнішнього ключа таблиці `page`
--
ALTER TABLE `page`
  ADD CONSTRAINT `pages_language_fk` FOREIGN KEY (`language_id`) REFERENCES `language` (`id`);

--
-- Обмеження зовнішнього ключа таблиці `poll`
--
ALTER TABLE `poll`
  ADD CONSTRAINT `polls_ibfk_2` FOREIGN KEY (`poll_language_id`) REFERENCES `language` (`id`);

--
-- Обмеження зовнішнього ключа таблиці `poll_answer`
--
ALTER TABLE `poll_answer`
  ADD CONSTRAINT `poll_answers_ibfk_1` FOREIGN KEY (`poll_id`) REFERENCES `poll` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `poll_answers_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Обмеження зовнішнього ключа таблиці `poll_comment`
--
ALTER TABLE `poll_comment`
  ADD CONSTRAINT `poll_comment_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `poll_comment_ibfk_2` FOREIGN KEY (`poll_id`) REFERENCES `poll` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `poll_comment_ibfk_3` FOREIGN KEY (`parent_id`) REFERENCES `poll_comment` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Обмеження зовнішнього ключа таблиці `poll_comment_rating`
--
ALTER TABLE `poll_comment_rating`
  ADD CONSTRAINT `poll_comment_ratings_ibfk_1` FOREIGN KEY (`poll_comment_id`) REFERENCES `poll_comment` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `poll_comment_ratings_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Обмеження зовнішнього ключа таблиці `poll_option`
--
ALTER TABLE `poll_option`
  ADD CONSTRAINT `poll_option_ibfk_1` FOREIGN KEY (`poll_id`) REFERENCES `poll` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `poll_option_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Обмеження зовнішнього ключа таблиці `poll_option_rating`
--
ALTER TABLE `poll_option_rating`
  ADD CONSTRAINT `poll_option_ratings_ibfk_1` FOREIGN KEY (`poll_option_id`) REFERENCES `poll_option` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `poll_option_ratings_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Обмеження зовнішнього ключа таблиці `poll_rating_vote`
--
ALTER TABLE `poll_rating_vote`
  ADD CONSTRAINT `poll_rating_votes_ibfk_1` FOREIGN KEY (`poll_id`) REFERENCES `poll` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `poll_rating_votes_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Обмеження зовнішнього ключа таблиці `poll_static_text`
--
ALTER TABLE `poll_static_text`
  ADD CONSTRAINT `fk-poll_static_text-language_id` FOREIGN KEY (`language_id`) REFERENCES `language` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Обмеження зовнішнього ключа таблиці `poll_tag`
--
ALTER TABLE `poll_tag`
  ADD CONSTRAINT `poll_tags_ibfk_1` FOREIGN KEY (`poll_id`) REFERENCES `poll` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `poll_tags_ibfk_2` FOREIGN KEY (`tag_id`) REFERENCES `tag` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Обмеження зовнішнього ключа таблиці `profile`
--
ALTER TABLE `profile`
  ADD CONSTRAINT `fk_user_profile` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `profile_ibfk_2` FOREIGN KEY (`country_id`) REFERENCES `country` (`id`),
  ADD CONSTRAINT `profile_ibfk_3` FOREIGN KEY (`region_id`) REFERENCES `country_region` (`id`),
  ADD CONSTRAINT `profile_ibfk_4` FOREIGN KEY (`city_id`) REFERENCES `region_city` (`id`);

--
-- Обмеження зовнішнього ключа таблиці `region_city`
--
ALTER TABLE `region_city`
  ADD CONSTRAINT `fk_region_city_country1` FOREIGN KEY (`country_id`) REFERENCES `country` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_region_city_country_region1` FOREIGN KEY (`region_id`) REFERENCES `country_region` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Обмеження зовнішнього ключа таблиці `social_account`
--
ALTER TABLE `social_account`
  ADD CONSTRAINT `fk_user_account` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE;

--
-- Обмеження зовнішнього ключа таблиці `tag`
--
ALTER TABLE `tag`
  ADD CONSTRAINT `fk_tag_language1` FOREIGN KEY (`language_id`) REFERENCES `language` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Обмеження зовнішнього ключа таблиці `tag_static_faq`
--
ALTER TABLE `tag_static_faq`
  ADD CONSTRAINT `fk-tag_static_faq-language_id` FOREIGN KEY (`language_id`) REFERENCES `language` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Обмеження зовнішнього ключа таблиці `tag_static_text`
--
ALTER TABLE `tag_static_text`
  ADD CONSTRAINT `fk_tag_static_text_language_id` FOREIGN KEY (`language_id`) REFERENCES `language` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Обмеження зовнішнього ключа таблиці `token`
--
ALTER TABLE `token`
  ADD CONSTRAINT `fk_user_token` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE;

--
-- Обмеження зовнішнього ключа таблиці `user_career`
--
ALTER TABLE `user_career`
  ADD CONSTRAINT `user_career_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `user_career_ibfk_2` FOREIGN KEY (`country_id`) REFERENCES `country` (`id`),
  ADD CONSTRAINT `user_career_ibfk_3` FOREIGN KEY (`region_id`) REFERENCES `country_region` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_career_ibfk_4` FOREIGN KEY (`city_id`) REFERENCES `region_city` (`id`);

--
-- Обмеження зовнішнього ключа таблиці `user_high_education`
--
ALTER TABLE `user_high_education`
  ADD CONSTRAINT `user_high_education_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `user_high_education_ibfk_2` FOREIGN KEY (`country_id`) REFERENCES `country` (`id`),
  ADD CONSTRAINT `user_high_education_ibfk_3` FOREIGN KEY (`region_id`) REFERENCES `country_region` (`id`),
  ADD CONSTRAINT `user_high_education_ibfk_4` FOREIGN KEY (`city_id`) REFERENCES `region_city` (`id`);

--
-- Обмеження зовнішнього ключа таблиці `user_interest`
--
ALTER TABLE `user_interest`
  ADD CONSTRAINT `user_interests_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Обмеження зовнішнього ключа таблиці `user_language`
--
ALTER TABLE `user_language`
  ADD CONSTRAINT `user_languages_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `user_languages_ibfk_2` FOREIGN KEY (`language_id`) REFERENCES `language` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Обмеження зовнішнього ключа таблиці `user_secondary_education`
--
ALTER TABLE `user_secondary_education`
  ADD CONSTRAINT `user_secondary_education_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `user_secondary_education_ibfk_2` FOREIGN KEY (`country_id`) REFERENCES `country` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `user_secondary_education_ibfk_3` FOREIGN KEY (`region_id`) REFERENCES `country_region` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `user_secondary_education_ibfk_4` FOREIGN KEY (`city_id`) REFERENCES `region_city` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

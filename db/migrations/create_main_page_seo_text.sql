-- Створюємо таблицю для SEO-текстів головної сторінки.
CREATE TABLE IF NOT EXISTS `main_page_seo_text` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `domain` VARCHAR(255) NOT NULL,
    `heading` VARCHAR(255) DEFAULT NULL,
    `content` MEDIUMTEXT DEFAULT NULL,
    `meta_title` VARCHAR(255) DEFAULT NULL,
    `meta_description` TEXT DEFAULT NULL,
    `created_at` INT(11) DEFAULT NULL,
    `updated_at` INT(11) DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uniq_main_page_seo_text_domain` (`domain`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

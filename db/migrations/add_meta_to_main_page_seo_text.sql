-- Додаємо мета-теги для головної сторінки по доменах/піддоменах.
ALTER TABLE `main_page_seo_text`
    ADD COLUMN `meta_title` VARCHAR(255) DEFAULT NULL AFTER `content`,
    ADD COLUMN `meta_description` TEXT DEFAULT NULL AFTER `meta_title`;

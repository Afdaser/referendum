-- Не нашкодити: додаємо прив'язку новин до піддомену, не змінюючи наявний контент.
SET @db_name := DATABASE();

SET @has_language_id := (
    SELECT COUNT(*)
    FROM information_schema.COLUMNS
    WHERE TABLE_SCHEMA = @db_name
      AND TABLE_NAME = 'news'
      AND COLUMN_NAME = 'language_id'
);
SET @sql_add_language_id := IF(
    @has_language_id = 0,
    'ALTER TABLE `news` ADD COLUMN `language_id` int unsigned NOT NULL DEFAULT 1 AFTER `slug`',
    'SELECT 1'
);
PREPARE stmt_add_language_id FROM @sql_add_language_id;
EXECUTE stmt_add_language_id;
DEALLOCATE PREPARE stmt_add_language_id;

SET @has_old_slug_index := (
    SELECT COUNT(*)
    FROM information_schema.STATISTICS
    WHERE TABLE_SCHEMA = @db_name
      AND TABLE_NAME = 'news'
      AND INDEX_NAME = 'ux_news_slug'
);
SET @sql_drop_old_slug_index := IF(
    @has_old_slug_index > 0,
    'ALTER TABLE `news` DROP INDEX `ux_news_slug`',
    'SELECT 1'
);
PREPARE stmt_drop_old_slug_index FROM @sql_drop_old_slug_index;
EXECUTE stmt_drop_old_slug_index;
DEALLOCATE PREPARE stmt_drop_old_slug_index;

SET @has_language_index := (
    SELECT COUNT(*)
    FROM information_schema.STATISTICS
    WHERE TABLE_SCHEMA = @db_name
      AND TABLE_NAME = 'news'
      AND INDEX_NAME = 'idx_news_language_id'
);
SET @sql_language_index := IF(
    @has_language_index = 0,
    'ALTER TABLE `news` ADD INDEX `idx_news_language_id` (`language_id`)',
    'SELECT 1'
);
PREPARE stmt_language_index FROM @sql_language_index;
EXECUTE stmt_language_index;
DEALLOCATE PREPARE stmt_language_index;

SET @has_slug_language_index := (
    SELECT COUNT(*)
    FROM information_schema.STATISTICS
    WHERE TABLE_SCHEMA = @db_name
      AND TABLE_NAME = 'news'
      AND INDEX_NAME = 'ux_news_slug_language_id'
);
SET @sql_slug_language_index := IF(
    @has_slug_language_index = 0,
    'ALTER TABLE `news` ADD UNIQUE INDEX `ux_news_slug_language_id` (`slug`, `language_id`)',
    'SELECT 1'
);
PREPARE stmt_slug_language_index FROM @sql_slug_language_index;
EXECUTE stmt_slug_language_index;
DEALLOCATE PREPARE stmt_slug_language_index;

SET @has_language_fk := (
    SELECT COUNT(*)
    FROM information_schema.TABLE_CONSTRAINTS
    WHERE TABLE_SCHEMA = @db_name
      AND TABLE_NAME = 'news'
      AND CONSTRAINT_NAME = 'fk_news_language_id'
      AND CONSTRAINT_TYPE = 'FOREIGN KEY'
);
SET @sql_language_fk := IF(
    @has_language_fk = 0,
    'ALTER TABLE `news` ADD CONSTRAINT `fk_news_language_id` FOREIGN KEY (`language_id`) REFERENCES `language` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT',
    'SELECT 1'
);
PREPARE stmt_language_fk FROM @sql_language_fk;
EXECUTE stmt_language_fk;
DEALLOCATE PREPARE stmt_language_fk;

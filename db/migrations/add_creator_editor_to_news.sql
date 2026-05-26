-- Захист від дублювання: додаємо службові колонки тільки якщо їх ще немає.
SET @db_name := DATABASE();

SET @has_created_by := (
    SELECT COUNT(*)
    FROM information_schema.COLUMNS
    WHERE TABLE_SCHEMA = @db_name
      AND TABLE_NAME = 'news'
      AND COLUMN_NAME = 'created_by'
);
SET @sql_created_by := IF(
    @has_created_by = 0,
    'ALTER TABLE `news` ADD COLUMN `created_by` int DEFAULT NULL AFTER `updated_at`',
    'SELECT 1'
);
PREPARE stmt_created_by FROM @sql_created_by;
EXECUTE stmt_created_by;
DEALLOCATE PREPARE stmt_created_by;

SET @has_updated_by := (
    SELECT COUNT(*)
    FROM information_schema.COLUMNS
    WHERE TABLE_SCHEMA = @db_name
      AND TABLE_NAME = 'news'
      AND COLUMN_NAME = 'updated_by'
);
SET @sql_updated_by := IF(
    @has_updated_by = 0,
    'ALTER TABLE `news` ADD COLUMN `updated_by` int DEFAULT NULL AFTER `created_by`',
    'SELECT 1'
);
PREPARE stmt_updated_by FROM @sql_updated_by;
EXECUTE stmt_updated_by;
DEALLOCATE PREPARE stmt_updated_by;

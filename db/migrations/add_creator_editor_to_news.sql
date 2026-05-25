ALTER TABLE `news`
  ADD COLUMN `created_by` int DEFAULT NULL AFTER `updated_at`,
  ADD COLUMN `updated_by` int DEFAULT NULL AFTER `created_by`;

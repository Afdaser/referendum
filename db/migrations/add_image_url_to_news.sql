ALTER TABLE `news`
  ADD COLUMN `image_url` varchar(1024) DEFAULT NULL AFTER `content`;

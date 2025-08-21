<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

passthru('/usr/bin/php ' . __DIR__ . '/yii sitemap/index 2>&1');

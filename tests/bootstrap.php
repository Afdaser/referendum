<?php
require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../vendor/yiisoft/yii2/Yii.php';

// Реєструємо alias застосунку, щоб кореневі тести могли завантажувати моделі через Yii autoloader.
Yii::setAlias('@common', dirname(__DIR__) . '/common');

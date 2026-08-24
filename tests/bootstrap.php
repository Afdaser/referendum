<?php
require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../vendor/yiisoft/yii2/Yii.php';

// Дозволяємо кореневим PHPUnit-тестам завантажувати класи застосунку через Yii aliases.
Yii::setAlias('@common', dirname(__DIR__) . '/common');

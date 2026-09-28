<?php
require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../vendor/yiisoft/yii2/Yii.php';

// Реєструємо простори застосунку, щоб кореневі тести перевіряли frontend-компоненти.
Yii::setAlias('@frontend', dirname(__DIR__) . '/frontend');

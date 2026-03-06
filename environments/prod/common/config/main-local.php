<?php

return [
    'components' => [
        'db' => [
            'class' => \yii\db\Connection::class,
            'dsn' => getenv('DB_DSN'),
            'username' => getenv('DB_USERNAME'),
            'password' => getenv('DB_PASSWORD'),
            // Використовуємо utf8mb4, щоб мета-дані могли зберігати емодзі без "???".
            'charset' => 'utf8mb4',
        ],
        'mailer' => [
            'class' => \yii\symfonymailer\Mailer::class,
            'viewPath' => '@common/mail',
            // На проді листи мають відправлятися реально, а не писатися у файли.
            'useFileTransport' => false,
            // УВАГА: заповніть SMTP-параметри нижче прямо на сервері (не в публічному репозиторії).
            'transport' => [
                'scheme' => 'smtp',
                'host' => 'smtp.hostinger.com',
                'port' => 465,
                'username' => 'mail@en.referendum.social',
                'password' => 'CHANGE_ME_ON_SERVER',
                // Для Hostinger на 465 зазвичай використовується ssl.
                'encryption' => 'ssl',
                // Якщо використовуєте DSN, заповніть його і приберіть інші поля transport.
                'dsn' => null,
            ],
        ],
    ],
    'params' => [
        // Виносимо адресу/ім'я відправника сюди, щоб вистачало одного файлу main-local.php.
        'supportEmail' => 'mail@en.referendum.social',
        'senderEmail' => 'mail@en.referendum.social',
        'senderName' => 'Referendum mailer',
    ],
];

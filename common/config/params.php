<?php

// this contains the application parameters that can be maintained via GUI

return [
//    'adminEmail' => 'admin@example.com',
    'supportEmail' => 'support@example.com',
    'senderEmail' => 'noreply@example.com',
    'senderName' => 'Example.com mailer',
    'user.passwordResetTokenExpire' => 3600,
    'user.passwordMinLength' => 8,
    // Тимчасово вимикаємо обов'язкову верифікацію email під час реєстрації.
    'user.requireEmailVerification' => false,
    // From Yii1:
    // this is displayed in the header section
    'title' => 'Statistics',
    // this is used in error pages
    //'adminEmail'=>'webmaster@online-statistics.org',
    'adminEmail' => 'webmaster@xpert.net.ua',
    // number of posts displayed per page
    'postsPerPage' => 5,
    // maximum number of comments that can be displayed in recent comments portlet
    'recentCommentCount' => 10,
    // maximum number of pools that can be displayed in sidebar
    'POLLS_LIMIT_SIDEBAR' => 5,
    // maximum number of pools that can be displayed in main page
    'POLLS_LIMIT_MAIN_PAGE' => 10,
    // maximum number of pools that can be displayed in top slider
    'POLLS_LIMIT_TOP_SLIDER' => 10,
    // maximum number of tags that can be displayed in tag cloud portlet
    'tagCloudCount' => 20,
    // whether post comments need to be approved before published
    'commentNeedApproval' => true,
    // the copyright information displayed in the footer section
    'copyrightInfo' => 'Copyright &copy; 2014 by My Company BVB.',
    'locales' => [
        'ua' => 'uk-UA',
        'uk' => 'uk-UA',
        'ru' => 'ru-RU',
        'en' => 'en-US',
        // Нова Зеландія: окрема локаль для піддомену nz.
        'nz' => 'en-NZ',
        'no' => 'nb-NO',
    ],
    'descriptionPrefix' => [
        'uk-UA' => 'Опитування по темі ',
        'ru-RU' => 'Опрос по теме ',
        'en-US' => 'Poll on the topic ',
        // Нова Зеландія: англомовний префікс для локалі en-NZ.
        'en-NZ' => 'Poll on the topic ',
        'nb-NO' => 'Avstemning om emnet ',
    ],
    'descriptionSuffix' => [
        'uk-UA' => '. Натисніть, щоб взяти участь в опитуванні, побачити результати та обговорити його',
        'ru-RU' => '. Нажмите, чтобы принять участие в опросе, увидеть результаты и обсудить их',
        'en-US' => '. Click to particippate in poll, see results and discuss it',
        // Нова Зеландія: англомовний суфікс для локалі en-NZ.
        'en-NZ' => '. Click to particippate in poll, see results and discuss it',
        'nb-NO' => '. Klikk for å delta i avstemningen, se resultater og diskutere det',
    ],
    'descriptionMaxLenth' => 156,
    'langDomains' => [
        1 => 'ua.referendum.social',
        2 => 'ru.referendum.social',
        3 => 'en.referendum.social',
        4 => 'no.referendum.social',
        // NZ — окрема мовна версія.
        5 => 'nz.referendum.social',
    ],
];

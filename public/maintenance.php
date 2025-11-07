<?php declare(strict_types=1);

// the maintenance.php file should be free of any library usage, as it should work without having dependencies installed

// default locale for maintenance translations
\define('DEFAULT_LOCALE', 'en');

// allow access for following ips
$allowedIPs = [
    '127.0.0.1',
];

// translations for maintenance
$translations = [
    'en' => [
        'title' => 'Maintenance',
        'heading' => 'The page is currently down for maintenance',
        'description' => 'Sorry for any inconvenience caused. Please try again shortly.',
    ],
    'de' => [
        'title' => 'Wartungsarbeiten',
        'heading' => 'Die Seite wird derzeit gewartet',
        'description' => 'Wir bitten um Verständnis. Bitte versuche es in Kürze erneut.',
    ],
];

// check if ip is within allowed range
if (\in_array($_SERVER['REMOTE_ADDR'], $allowedIPs, true)) {
    return false;
}

// get language
$lang = \substr($_SERVER['HTTP_ACCEPT_LANGUAGE'] ?? '', 0, 2); // @phpstan-ignore-line argument.type

// chose locale
$locale = \array_key_exists($lang, $translations) ? $lang : DEFAULT_LOCALE;

\http_response_code(503);
\header('Content-Language: ' . $locale);

if (isset($_SERVER['HTTP_ACCEPT'])
    && 1 === \preg_match('#^application/(.+\+)?json$#', $_SERVER['HTTP_ACCEPT']) // @phpstan-ignore-line argument.type
) {
    \header('Content-Type: application/problem+json; charset=utf-8');
    \http_response_code(503);

    echo \json_encode([
        'type' => 'https://tools.ietf.org/html/rfc7807',
        'title' => $translations[$locale]['title'],
        'detail' => $translations[$locale]['heading'] . \PHP_EOL . \PHP_EOL . $translations[$locale]['description'],
    ], \JSON_THROW_ON_ERROR);

    exit;
}

\header('Content-Type: text/html; charset=utf-8');

?><!doctype html>
<html lang="<?php echo $locale; ?>">
<head>
    <title><?php echo $translations[$locale]['title']; ?></title>
    <style type="text/css">
        body {
            background-color: #f1f1f1;
            font-family: Helvetica, Arial, sans-serif;
        }
        #main {
            margin: 50px auto;
            text-align: center;
        }
        .wrapper {
            margin: 0 auto;
            height: 100%;
            color: #666666;
        }
        .description {
            color: #999999;
        }
    </style>
</head>
<body>
    <div id="main">
        <div class="wrapper">
            <div class="text-container">
                <h1><?php echo $translations[$locale]['heading']; ?></h1>
                <div class="description"><?php echo $translations[$locale]['description']; ?></div>
            </div>
        </div>
    </div>
</body>
</html>

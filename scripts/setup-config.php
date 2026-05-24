<?php
/**
 * Rhymix Configuration Generator
 * This script generates files/config/config.php using environment variables.
 */

$config_file = __DIR__ . '/../files/config/config.php';

// Skip if config already exists
if (file_exists($config_file)) {
    echo "Configuration already exists. Skipping.\n";
    exit(0);
}

// Ensure directory exists
if (!is_dir(dirname($config_file))) {
    mkdir(dirname($config_file), 0755, true);
}

// Get DB settings from environment
$db_host = getenv('MYSQL_HOST') ?: 'mysql';
$db_user = getenv('MYSQL_USER');
$db_pass = getenv('MYSQL_PASSWORD');
$db_name = getenv('MYSQL_DATABASE');
$default_url = getenv('DEFAULT_URL') ?: 'http://localhost:8080/';

if (!$db_user || !$db_pass || !$db_name) {
    echo "Error: MYSQL_USER, MYSQL_PASSWORD, and MYSQL_DATABASE environment variables must be set.\n";
    exit(1);
}

// Generate random keys (similar to Rhymix installer)
function getRandomKey($length = 64) {
    $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $key = '';
    $max = strlen($chars) - 1;
    for ($i = 0; $i < $length; $i++) {
        $key .= $chars[random_int(0, $max)];
    }
    return $key;
}

$config = [
    'config_version' => '2.0',
    'db' => [
        'master' => [
            'type' => 'mysql',
            'host' => $db_host,
            'port' => 3306,
            'user' => $db_user,
            'pass' => $db_pass,
            'database' => $db_name,
            'prefix' => 'rx_',
            'charset' => 'utf8mb4',
            'engine' => 'innodb',
        ],
    ],
    'cache' => [
        'type' => 'redis',
        'ttl' => 86400,
        'servers' => [
            'redis://redis:6379'
        ],
        'truncate_method' => 'delete',
        'cache_control' => 'must-revalidate, no-store, no-cache',
    ],
    'crypto' => [
        'encryption_key' => getRandomKey(),
        'authentication_key' => getRandomKey(),
        'session_key' => getRandomKey(),
    ],
    'locale' => [
        'default_lang' => 'ko',
        'enabled_lang' => ['ko'],
        'auto_select_lang' => false,
        'default_timezone' => 'Asia/Seoul',
        'internal_timezone' => 32400,
    ],
    'url' => [
        'default' => $default_url,
        'ssl' => 'none',
        'rewrite' => 1,
    ],
];

$content = "<?php\nreturn " . var_export($config, true) . ";\n";

if (file_exists($config_file)) {
    echo "Configuration already exists.\n";
} else {
    file_put_contents($config_file, $content);
    echo "Successfully generated configuration file!\n";
}

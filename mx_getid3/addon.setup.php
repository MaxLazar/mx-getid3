<?php

$addonJson = json_decode(file_get_contents(__DIR__ . '/addon.json'));


if (!defined('MX_GETID3_NAME')) {
    define('MX_GETID3_NAME', $addonJson->name);
    define('MX_GETID3_VERSION', $addonJson->version);
    define('MX_GETID3_KEY', $addonJson->shortname);
    define('MX_GETID3_DOCS', '');
    define('MX_GETID3_DESCRIPTION', $addonJson->description);
    define('MX_GETID3_AUTHOR', 'Max Lazar');
    define('MX_GETID3_DEBUG', false);
}


return [
    'name' => $addonJson->name,
    'description' => $addonJson->description,
    'version' => $addonJson->version,
    'namespace' => $addonJson->namespace,
    'author' => 'Max Lazar',
    'author_url' => 'https://eecms.dev',
    'settings_exist' => false,
    // Advanced settings
    'services' => [],
];

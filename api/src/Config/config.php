<?php
$config = parse_ini_file(__DIR__ . '/../../../.env');

if ($config === false) {
	throw new RuntimeException('No se encontro el archivo .env');
}

define('HOST', $config['DB_HOST'] ?? '');
define('DATABASE', $config['DB_NAME'] ?? '');
define('USERNAME', $config['DB_USER'] ?? '');
define('PASSWORD', $config['DB_PASSWORD'] ?? '');
define('PORT', $config['DB_PORT'] ?? 3306);
$charset = $config['DB_CHARSET'] ?? 'utf8mb4';
define('CHARSET', str_replace('charset=', '', $charset));
define('SSL_CA', $config['DB_SSL_CA'] ?? '');

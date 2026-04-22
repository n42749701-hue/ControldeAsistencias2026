<?php
$config=parse_ini_file(__DIR__.'/../.env');
define("HOST",$config['DB_HOST']);
define("DATABASE",$config['DB_NAME']);
define("USERNAME",$config['DB_USER']);
define("PASSWORD",$config['DB_PASSWORD']);
define("PORT",$config['DB_PORT']);
define("CHARSET",$config['DB_CHARSET']);

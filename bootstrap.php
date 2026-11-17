<?php

define('ROOTDIR', __DIR__ . DIRECTORY_SEPARATOR);

require_once ROOTDIR . 'vendor/autoload.php';

if (file_exists(ROOTDIR . '.env')) {
	$dotenv = Dotenv\Dotenv::createImmutable(ROOTDIR);
	$dotenv->safeLoad();
}

use Illuminate\Database\Capsule\Manager;

$manager = new Manager();

$dbHost = $_ENV['DB_HOST'] ?? getenv('DB_HOST') ?: '127.0.0.1';
$dbPort = $_ENV['DB_PORT'] ?? getenv('DB_PORT') ?: '3306';
$dbName = $_ENV['DB_NAME'] ?? getenv('DB_NAME') ?: 'bookstore';
$dbUser = $_ENV['DB_USER'] ?? getenv('DB_USER') ?: 'root';
$dbPass = $_ENV['DB_PASS'] ?? getenv('DB_PASS') ?: '';

$manager->addConnection([
	'driver'    => 'mysql',
	'host'      => $dbHost,
	'port'      => $dbPort,
	'database'  => $dbName,
	'username'  => $dbUser,
	'password'  => $dbPass,
	'charset'   => 'utf8mb4',
	'collation' => 'utf8mb4_unicode_ci',
	'prefix'    => '',
]);

$manager->setAsGlobal();
$manager->bootEloquent();

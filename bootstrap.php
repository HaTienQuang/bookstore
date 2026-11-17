<?php

define('ROOTDIR', __DIR__ . DIRECTORY_SEPARATOR);

require_once ROOTDIR . 'vendor/autoload.php';

if (file_exists(ROOTDIR . '.env')) {
	$dotenv = Dotenv\Dotenv::createImmutable(ROOTDIR);
	$dotenv->safeLoad();
}

use Illuminate\Database\Capsule\Manager;

$manager = new Manager();

$dbDriver = $_ENV['DB_DRIVER'] ?? getenv('DB_DRIVER');
$dbHost = $_ENV['DB_HOST'] ?? getenv('DB_HOST');

// On Vercel without an external MySQL host configured, use the embedded SQLite database
$isVercel = !empty(getenv('VERCEL'));
$useSqlite = ($dbDriver === 'sqlite') || ($isVercel && (empty($dbHost) || $dbHost === 'localhost' || $dbHost === '127.0.0.1'));

if ($useSqlite && file_exists(ROOTDIR . 'database.sqlite')) {
	$manager->addConnection([
		'driver'                  => 'sqlite',
		'database'                => ROOTDIR . 'database.sqlite',
		'prefix'                  => '',
		'foreign_key_constraints' => true,
	]);
} else {
	$dbPort = $_ENV['DB_PORT'] ?? getenv('DB_PORT') ?: '3306';
	$dbName = $_ENV['DB_NAME'] ?? getenv('DB_NAME') ?: 'bookstore';
	$dbUser = $_ENV['DB_USER'] ?? getenv('DB_USER') ?: 'root';
	$dbPass = $_ENV['DB_PASS'] ?? getenv('DB_PASS') ?: '';

	$manager->addConnection([
		'driver'    => 'mysql',
		'host'      => $dbHost ?: '127.0.0.1',
		'port'      => $dbPort,
		'database'  => $dbName,
		'username'  => $dbUser,
		'password'  => $dbPass,
		'charset'   => 'utf8mb4',
		'collation' => 'utf8mb4_unicode_ci',
		'prefix'    => '',
	]);
}

$manager->setAsGlobal();
$manager->bootEloquent();

<?php

require 'vendor/autoload.php';

use Envms\FluentPDO\Query;

// Database connection - supports MySQL, PostgreSQL, SQLite
// Get connection details from environment variables
$dsn = $_ENV['DB_DSN'] ?? 'mysql:host=localhost;dbname=multiflexi';
$username = $_ENV['DB_USERNAME'] ?? 'multiflexi';
$password = $_ENV['DB_PASSWORD'] ?? 'password';

$pdo = new PDO($dsn, $username, $password);
$fluent = new Query($pdo);

// Example usage
try {
    // Insert example
    $fluent->insertInto('example_table', [
        'name' => 'Test Name',
        'description' => 'Test Description'
    ])->execute();

    echo "Insert successful!\n";

    // Select example
    $result = $fluent->from('example_table')->fetchAll();
    print_r($result);

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
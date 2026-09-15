<?php

$host     = getenv('DB_HOST');
$port     = getenv('DB_PORT') ?: '5432';
$db       = getenv('DB_NAME');
$user     = getenv('DB_USER');
$password = getenv('DB_PASSWORD');

try {
    $dsn = "pgsql:host={$host};port={$port};dbname={$db};sslmode=require";
    
    $pdo = new PDO($dsn, $user, $password, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_CASE               => PDO::CASE_UPPER
    ]);
} catch (PDOException $e) {
    die("Erro na conexão: " . $e->getMessage());
}
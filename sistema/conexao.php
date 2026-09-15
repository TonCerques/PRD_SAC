<?php

$host     = 'aws-0-sa-east-1.pooler.supabase.com';
$port     = '5432';                              
$db       = 'postgres';                            
$user     = 'postgres.TonCerques';          
$password = 'prd_sac2026';

try {
    $dsn = "pgsql:host=$host;port=$port;dbname=$db;sslmode=require";
    $pdo = new PDO($dsn, $user, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_CASE => PDO::CASE_UPPER
    ]);
} catch (PDOException $e) {
    die("Erro na conexão com Supabase: " . $e->getMessage());
}
?>
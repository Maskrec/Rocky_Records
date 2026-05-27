<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Configuracion de los parametros locales de XAMPP
$host = 'localhost';
$db = 'rockyrecords_db';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
     PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
     PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
     PDO::ATTR_EMULATE_PREPARES => false,
];

try {
     // 1. Creamos la conexion en $pdo (la que pide generos.php y viniles.php)
     $pdo = new PDO($dsn, $user, $pass, $options);

     // 2. Creamos un clon en $conexion (por si tus compañeros usan MySQLi en otras paginas)
     $conexion = $pdo;

} catch (\PDOException $e) {
     die("Error de conexion: " . $e->getMessage());
}
?>
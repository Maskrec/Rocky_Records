<?php
// Configuración de los parámetros locales de XAMPP
$host = 'localhost';
$db   = 'rockyrecords_db'; // El nombre exacto de la base de datos
$user = 'root';          // El usuario por defecto en XAMPP es root
$pass = '';              // Por defecto en XAMPP viene vacío
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
     // 1. Creamos la conexión en $pdo (la que pide generos.php y viniles.php)
     $pdo = new PDO($dsn, $user, $pass, $options);
     
     // 2. Creamos un clon en $conexion (por si tus compañeros usan MySQLi en otras páginas)
     $conexion = $pdo; 
     
} catch (\PDOException $e) {
     die("Error de conexión: " . $e->getMessage());
}
?>
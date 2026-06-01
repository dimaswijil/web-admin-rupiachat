<?php
$host = 'gateway01.ap-southeast-1.prod.alicloud.tidbcloud.com';
$port = '4000';
$db   = 'rupiachat';
$user = '2N5hRCv6oRDvKgq.root';
$pass = 'Hdlfs7R9KgR7MYhB';
$charset = 'utf8mb4';
$dsn = "mysql:host=$host;port=$port;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
    PDO::MYSQL_ATTR_SSL_CA       => '/etc/ssl/cert.pem', // Mac standard path
];
if (!file_exists($options[PDO::MYSQL_ATTR_SSL_CA])) {
    $options[PDO::MYSQL_ATTR_SSL_CA] = '/etc/ssl/certs/ca-certificates.crt'; // Linux standard path
}
try {
     $pdo = new PDO($dsn, $user, $pass, $options);
     echo "Connected successfully to TiDB.\n";
     
     $stmt = $pdo->query('SHOW TABLES');
     $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
     echo "Tables in database: " . implode(', ', $tables) . "\n";
     
     if (in_array('users', $tables)) {
         $stmt = $pdo->query('SELECT COUNT(*) as count FROM users');
         $row = $stmt->fetch();
         echo "Users count: " . $row['count'] . "\n";
     } else {
         echo "Table 'users' does not exist.\n";
     }
} catch (\PDOException $e) {
     echo "Connection failed: " . $e->getMessage() . "\n";
}

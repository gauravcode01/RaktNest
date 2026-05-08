<?php 
if (session_status() === PHP_SESSION_NONE) {
    // Session name set kar rahe hain unique project ke liye
    session_name("raktnest_session");

    // Session lifetime 7 days
    session_set_cookie_params([
        'lifetime' => 60 * 60 * 24 * 7, // 7 days
        'path' => '/',
        'secure' => false, // true if HTTPS
        'httponly' => true,
        'samesite' => 'Lax'
    ]);

    session_start();
}
// DB credentials.
define('DB_HOST','localhost');
define('DB_USER','root');
define('DB_PASS','');
define('DB_NAME','bbdms');
// Establish database connection.
try
{
$dbh = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME,DB_USER, DB_PASS,array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
}
catch (PDOException $e)
{
exit("Error: " . $e->getMessage());
}
?>
<?php
// PDO connection
function sql_connect(){
    global $DB;

    $dbUrl = getenv('DATABASE_URL');

    if ($dbUrl) {
        // Cas Scalingo
        $parts = parse_url($dbUrl);

        $host = $parts['host'] ?? '';
        $port = $parts['port'] ?? 3306;
        $user = $parts['user'] ?? '';
        $pass = $parts['pass'] ?? '';
        $dbname = isset($parts['path']) ? ltrim($parts['path'], '/') : '';

        $dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4";

        $DB = new PDO($dsn, $user, $pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]);
    } else {
        // Cas local
        $dsn = "mysql:host=" . SQL_HOST . ";dbname=" . SQL_DB . ";charset=utf8mb4";

        $DB = new PDO($dsn, SQL_USER, SQL_PWD, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]);
    }
}
?>
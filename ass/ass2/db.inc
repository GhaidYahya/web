<?php 

$host = 'localhost';
$dbname = 'c139_ghaid_2023';
$username = 'root';
$password_db = '';


function db_connect($dbname = '', $username = '', $password = ''){
    global $host, $username, $password_db, $dbname;
    global $MYSQL_ERRNO, $MYSQL_ERROR;

    try {

        if (empty($dbname)) {
            $dbname = $dbname;
        }

        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password_db);
        echo "Connected :)";
        return $pdo;

    } catch (PDOException $e) {
        $MYSQL_ERRNO = 0;
        $MYSQL_ERROR = $e->getMessage();
        echo "Failed :(";
        return 0;
    }
}



?>
<?php
require_once('../vendor/autoload.php');

echo 'тут живет бот, иди куда шел';

//
//$dbopts = parse_url(getenv('DATABASE_URL'));
//
//$driver = 'pgsql';
//$user = $dbopts["user"];
//$pass = $dbopts["pass"];
//$host = $dbopts["host"];
//$port = $dbopts["port"];
//$db = ltrim($dbopts["path"], '/');
//try {
//    $conn = new PDO("pgsql:host=$host dbname=$db", $user, $pass);
//    echo 'АТАС';
//} catch (PDOException $e) {
//    echo $e->getMessage();
//}
//
//
//$sql_get_depts = "SELECT * FROM debts";
//
//$stmt = $conn->query($sql_get_depts);
//
//echo PHP_EOL . var_export($stmt);
//
//while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
//    echo htmlspecialchars($row['username']) . PHP_EOL;
//};

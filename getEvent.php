<?php
header("Content-Type: application/json");

$host = "learn-mysql.cms.waikato.ac.nz";
$dbname = "username"; // 請替換為你的學校數據庫名稱
$username = "username"; // 請替換為你的學校 MySQL 帳號
$password = "pword"; // 請替換為你的學校 MySQL 密碼

try {
    // 建立 PDO 連接
    $con = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 執行 SQL 查詢
    $sql = "SELECT * FROM events";  
    $stmt = $con->prepare($sql);
    $stmt->execute();

    // 獲取結果
    $events = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // 返回 JSON 格式的結果
    echo json_encode($events);

} catch (PDOException $e) {
    echo json_encode(["error" => "Database connection error: " . $e->getMessage()]);
}
?>

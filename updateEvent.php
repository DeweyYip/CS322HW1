<?php
header("Content-Type: application/json");

$host = "learn-mysql.cms.waikato.ac.nz";
$dbname = "username";  // 請替換為你的學校數據庫名稱
$username = "username"; // 請替換為你的學校 MySQL 帳號
$password = "pword";    // 請替換為你的學校 MySQL 密碼

try {
    // 建立 PDO 連接
    $con = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 接收 JSON 格式的請求數據
    $data = json_decode(file_get_contents("php://input"), true);

    // 檢查是否收到所有必要的字段
    if (!isset($data["name"], $data["category"], $data["date"], $data["time"], $data["cost"], $data["location"], $data["note"], $data["id"])) {
        echo json_encode(["error" => "Missing required fields"]);
        exit;
    }

    // 解析日期（假設 date 格式為 "March 25"）
    $dateParts = explode(" ", $data["date"]);
    if (count($dateParts) < 2) {
        echo json_encode(["error" => "Invalid date format"]);
        exit;
    }
    $month = $dateParts[0]; // 月份
    $day = $dateParts[1];   // 日期

    // 構造 SQL 語句
    $sql = "UPDATE events SET name=?, category=?, month=?, day=?, time=?, cost=?, location=?, notes=? WHERE id=?";
    $stmt = $con->prepare($sql);
    
    // 綁定參數
    $stmt->execute([
        $data["name"], 
        $data["category"], 
        $month, 
        $day, 
        $data["time"], 
        $data["cost"], 
        $data["location"], 
        $data["note"], 
        $data["id"]
    ]);

    echo json_encode(["success" => true, "message" => "Event updated"]);

} catch (PDOException $e) {
    echo json_encode(["error" => "Database error: " . $e->getMessage()]);
}
?>

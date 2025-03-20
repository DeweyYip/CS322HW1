<?php
header("Content-Type: application/json");

$servername = "localhost"; 
$username = "root"; 
$password = "yediwei08"; 
$dbname = "Event"; 

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die(json_encode(["error" => "Connection failed: " . $conn->connect_error]));
}

// put the request message into data
$data = json_decode(file_get_contents("php://input"), true);

$sql = "UPDATE events SET name=?, category=?, month=?, day=?, time=?, cost=?, location=?, notes=? WHERE id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssssdssi", 
    $data["name"], 
    $data["category"], 
    explode(" ", $data["date"])[0], 
    explode(" ", $data["date"])[1], 
    $data["time"], 
    $data["cost"], 
    $data["location"], 
    $data["note"], 
    $data["id"]
);

if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "Event updated"]);
} else {
    echo json_encode(["error" => "Update failed"]);
}

$stmt->close();
$conn->close();
?>

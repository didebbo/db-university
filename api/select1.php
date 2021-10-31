<?php
$host = "localhost:3306";
$user = "root";
$password = "";
$database = "db_university";

header("Content-Type: application/json");

$obj = ["status" => 200];

if (!isset($_GET["date"]) || trim($_GET["date"] === "" || !is_numeric($_GET["date"]))) die(json_encode(
    $obj = [
        "status" => 400,
        "error" => "Invalid data request"
    ]
));

$mysqli = new mysqli($host, $user, $password, $database);
if ($mysqli->connect_error) die($mysqli->connect_error);

// Prepare Query and Bind
$stmt = $mysqli->prepare("SELECT * FROM students WHERE date_of_birth LIKE ?");
$stmt->bind_param("s", $date);

$date = $_GET["date"] . "-%";
$stmt->execute();
$result = $stmt->get_result();

$data = [];
while ($row = $result->fetch_assoc()) {
    $col = [];
    foreach ($row as $key => $val) $col[$key] = $val;
    $data[] = $col;
}
$obj["data"] = $data;

echo json_encode($obj);

$stmt->close();
$mysqli->close();


// var_dump($mysqli);

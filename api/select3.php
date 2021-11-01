<?php
require_once "config.php";
header("Content-Type: application/json");

$param = "years";
$obj = ["status" => 200];

if (!isset($_GET[$param]) || trim($_GET[$param] === "" || !is_numeric($_GET[$param]))) die(json_encode(
    $obj = [
        "status" => 400,
        "error" => "Invalid data request"
    ]
));

$mysqli = new mysqli($host, $user, $password, $database);
if ($mysqli->connect_error) die($mysqli->connect_error);

// Prepare Query and Bind
$qry = "SELECT * FROM `students` WHERE TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) > ?;";
$stmt = $mysqli->prepare($qry);
$stmt->bind_param("i", $years);

$years = $_GET[$param];
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

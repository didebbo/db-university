<?php
require_once "config.php";
header("Content-Type: application/json");

$param = ["hour", "date"];
$obj = ["status" => 200];

if (
    !isset($_GET[$param[0]]) || trim($_GET[$param[0]]) === ""
    || !isset($_GET[$param[1]]) || trim($_GET[$param[1]]) === ""
) die(json_encode(
    $obj = [
        "status" => 400,
        "error" => "Invalid data request"
    ]
));

$mysqli = new mysqli($host, $user, $password, $database);
if ($mysqli->connect_error) die($mysqli->connect_error);

// Prepare Query and Bind
$qry = "SELECT * FROM `exams` WHERE hour > ? AND date LIKE ?;";
$stmt = $mysqli->prepare($qry);
$stmt->bind_param("ss", $hour, $date);

$hour = $_GET[$param[0]];
$date = $_GET[$param[1]];
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

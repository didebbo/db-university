<?php
require_once "config.php";
header("Content-Type: application/json");

$param = "cfu";
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
$qry = "SELECT * FROM `courses` WHERE cfu > ?;";
$stmt = $mysqli->prepare($qry);
$stmt->bind_param("i", $cfu);

$cfu = $_GET[$param];
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

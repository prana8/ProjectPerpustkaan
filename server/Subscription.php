<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: PATCH");

include "./Connection.php";

$data = json_decode(file_get_contents("php://input"));

try {

    $path = $_SERVER['PATH_INFO'];
    $id = substr($path, 1);

    $addSecond = $data->addSecond;


    $sql = "UPDATE subscription SET timekeeper = DATE_ADD(timekeeper, INTERVAL :addSecond SECOND) WHERE id_user = :id_user";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':addSecond', $addSecond, PDO::PARAM_INT);
    $stmt->bindParam(':id_user', $id, PDO::PARAM_INT);


    $stmt->execute();

    echo json_encode(array("message" => "Pembaruan berhasil."));
} catch (PDOException $e) {
    echo json_encode(array("message" => "Error: " . $e->getMessage()));
}

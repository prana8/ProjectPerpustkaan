<?php
session_start();

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: PATCH");

include "./Connection.php";

$data = json_decode(file_get_contents("php://input"));

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'PATCH':
        try {
            $sql = "UPDATE subscription SET timekeeper = DATE_SUB(timekeeper, INTERVAL :addSecond SECOND) WHERE id_user = :id_user";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':addSecond', $data->subSecond);
            $stmt->bindParam(':id_user', $data->id);

            $stmt->execute();

            echo json_encode(array("message" => "Pembaruan berhasil.", "id" => $data->id));
        } catch (PDOException $e) {
            echo json_encode(array("message" => "Error: " . $e->getMessage()));
        }
        break;
}

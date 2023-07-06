<?php
session_start();

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: PATCH, GET");

include "./Connection.php";

$data = json_decode(file_get_contents("php://input"));

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        try {
            $id = $_GET['id'];
            $stmt = $conn->prepare("SELECT * FROM subscription WHERE id_user=:id");
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            $subs = $stmt->fetch(PDO::FETCH_ASSOC);
            date_default_timezone_set('Asia/Jakarta');
            $nowDate = date_create()->format('Y-m-d H:i:s');

            if ($nowDate > $subs['tgl_berakhir'] || $subs['tgl_mulai'] > $subs['timekeeper']) {
                $stmt = $conn->prepare("UPDATE user SET status=0 WHERE id=:id");
                $stmt->bindParam(':id', $id);
                $stmt->execute();
            }

            $stmt = $conn->prepare("SELECT status FROM user WHERE id=:id");
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            echo json_encode(array("status" => $user['status']));
        } catch (PDOException $e) {
            echo json_encode(array("message" => "Error: " . $e->getMessage()));
        }
        break;

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

$conn = null;

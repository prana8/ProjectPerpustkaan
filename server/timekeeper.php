<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Content-Type: application/json; charset=UTF-8");

include "./connection.php";

try {

    $id_user = $data->id;
    $addSecond = $data->addSecond;


    $sql = "UPDATE subscription SET timekeeper = DATE_ADD(timekeeper, INTERVAL :addSecond SECOND) WHERE id_user = :id_user";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':addSecond', $addSecond, PDO::PARAM_INT);
    $stmt->bindParam(':id_user', $id_user, PDO::PARAM_INT);


    $stmt->execute();

    echo json_encode(array("message" => "Pembaruan berhasil."));
} catch (PDOException $e) {
    echo json_encode(array("message" => "Error: " . $e->getMessage()));
}

?>

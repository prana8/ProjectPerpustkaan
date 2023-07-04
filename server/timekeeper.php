<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: PATCH");

include "./connection.php";
$data = json_decode(file_get_contents("php://input"));

if ($_SERVER['REQUEST_METHOD'] !== 'PATCH') {
    echo json_encode(array("message" => "Metode permintaan tidak valid."));
    exit;
}

try {
    
    $sql = "UPDATE subscription SET timekeeper=DATE_ADD(timekeeper, INTERVAL :addSecond SECOND) WHERE id_user=:id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':addSecond', $data->addSecond, PDO::PARAM_INT);
    $stmt->bindParam(':id', $data->id, PDO::PARAM_INT);
    $stmt->execute();

    echo "Pembaruan berhasil";
} catch (PDOException $e) {
    echo "Pembaruan gagal: " . $e->getMessage();
}

$conn = null;

?>

<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Content-Type: application/json; charset=UTF-8");

include "./Connection.php";

// Mendapatkan data dari request
$data = json_decode(file_get_contents("php://input"));

// Memasukkan data ke tabel user
try {
    $stmt = $conn->prepare("INSERT INTO user (username, email, no_tlp, password) VALUES (:username, :email, :no_tlp, :password)");
    $stmt->bindParam(":username", $data->username);
    $stmt->bindParam(":email", $data->email);
    $stmt->bindParam(":no_tlp", $data->no_tlp);
    $hashedPassword = password_hash($data->password, PASSWORD_DEFAULT);
    $stmt->bindParam(":password", $hashedPassword);
    $stmt->execute();

    // Mengirim response status success
    $response = array("status" => "success");
    echo json_encode($response);
} catch (PDOException $e) {
    // Jika terjadi error, mengirim response dengan pesan error
    $response = array("status" => "error", "message" => $e->getMessage());
    echo json_encode($response);
}

// Menutup koneksi
$conn = null;

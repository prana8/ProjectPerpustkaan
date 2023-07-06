<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");

include "./Connection.php";

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
 
        $sql = 'SELECT id, judul, pengarang, kategori, tahun_terbit, deskripsi_buku, status  FROM buku';
        $stmt = $conn->prepare($sql);
        $stmt->execute();


        if ($stmt->rowCount() > 0) {
            $buku = $stmt->fetchAll(PDO::FETCH_ASSOC);

            echo json_encode($buku);
        } else {

            http_response_code(404);
            echo json_encode(array('message' => 'No movies found.'));
        }
    } catch (PDOException $e) {
        // Jika terjadi kesalahan dalam query
        http_response_code(500);
        echo json_encode(array('message' => 'Error: ' . $e->getMessage()));
    }
}
<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");

include "./Connection.php";

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
        $id = $_GET['id'];

        $sql = 'SELECT  judul, pengarang, kategori, tahun_terbit, deskripsi_buku FROM buku WHERE id = :id';
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $book = $stmt->fetch(PDO::FETCH_ASSOC);

            echo json_encode($book);
        } else {
            http_response_code(404);
            echo json_encode(array('message' => 'Book not found.'));
        }
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(array('message' => 'Error: ' . $e->getMessage()));
    }
}

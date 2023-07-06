<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");


include "./Connection.php";

$path = $_SERVER['PATH_INFO'];
$id = substr($path, 1);


try {
  $method = $_SERVER['REQUEST_METHOD'];

  switch ($method) {
    case "GET":
      $stmt = $conn->prepare("SELECT book_pdf FROM buku WHERE id = :id");
      $stmt->bindParam(":id", $id);
      $stmt->execute();

      if ($stmt->rowCount() > 0) {
        // Pengguna ditemukan, mengambil data pengguna dari hasil query
        $book = $stmt->fetch(PDO::FETCH_ASSOC);
        $pdfData = $book['book_pdf'];

        header("Content-type: application/pdf");
        header("Content-Disposition: inline; filename=" . $pdfData . ".pdf");

        echo $pdfData;
      } else {
        // Pengguna tidak ditemukan, mengirim response status error
        $response = array("status" => "error", "message" => "Error fetch PDF");
        echo json_encode($response);
      }

      break;
  }
} catch (PDOException $e) {
  echo "Koneksi gagal", $e->getMessage();
}

$conn = null;

<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");


include "./connection.php";



try{
  $method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
  case "GET":
    $sql = "SELECT book_pdf FROM buku WHERE id = 1";
    $result = $conn->query($sql);
    $row = $result->fetch(PDO::FETCH_ASSOC);
    if ($row) {
      $pdfData = $row['book_pdf'];

      // Set the appropriate headers
      header("Content-type: application/pdf");
      header("Content-Disposition: inline; filename=" . $pdfData . ".pdf");

      echo $pdfData;
    } else {
      echo "No PDF found.";
    }

    break;
}
} catch (PDOException $e) {
  echo "Koneksi gagal", $e->getMessage();
}


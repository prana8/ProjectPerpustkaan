<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "peminjaman_buku";

// Create a connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
  case "GET":
    $sql = "SELECT book_pdf FROM buku WHERE id = 1";
    $result = $conn->query($sql);
    if ($result && $result->num_rows > 0) {
      $row = $result->fetch_assoc();
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

$conn->close();

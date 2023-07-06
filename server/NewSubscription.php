<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Content-Type: application/json; charset=UTF-8");

include "./Connection.php";

// Mendapatkan data dari request
$data = json_decode(file_get_contents("php://input"));

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
  case 'POST':
    try {
      $stmt = $conn->prepare("SELECT * FROM user WHERE email = :email");
      $stmt->bindParam(":email", $data->email);
      $stmt->execute();

      if ($stmt->rowCount() > 0) {
        // Pengguna ditemukan, mengambil data pengguna dari hasil query
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Memeriksa kecocokan password
        if (password_verify($data->password, $user["password"])) {
          $kuota = '0 hours';
          $masaBerlaku = '0 days';

          switch ($data->paket) {
            case 'Paket Kebut Semalam':
              $kuota = '20 hours';
              $masaBerlaku = '21 days';
              break;
            case 'Paket Super Pintar':
              $kuota = '68 hours';
              $masaBerlaku = '30 days';
              break;
            case 'Paket Doctor':
              $kuota = '200 hours';
              $masaBerlaku = '60 days';
              break;
          }

          $stmt = $conn->prepare("INSERT INTO subscription (id_user, tgl_berakhir, timekeeper, status) VALUES (:id, :tgl_berakhir, :timekeeper)");
          $stmt->bindParam(":id", $user['id']);
          date_default_timezone_set('Asia/Jakarta');
          $tgl_berakhir = date_add(date_create(date('Y-m-d H:i:s')), date_interval_create_from_date_string($masaBerlaku))->format('Y-m-d H:i:s');
          $timekeeper = date_add(date_create(date('Y-m-d H:i:s')), date_interval_create_from_date_string($kuota))->format('Y-m-d H:i:s');
          $stmt->bindParam(":tgl_berakhir", $tgl_berakhir);
          $stmt->bindParam(":timekeeper", $timekeeper);
          $stmt->execute();

          $stmt = $conn->prepare("UPDATE user SET status = 1 WHERE id=:id");
          $stmt->bindParam(":id", $user['id']);
          $stmt->execute();

          $response = array("status" => "success", "message" => "subscription added");
          echo json_encode($response);
        } else {
          // Password tidak cocok
          // Mengirim response status error
          $response = array("status" => "error", "message" => "Invalid email or password");
          echo json_encode($response);
        }
      } else {
        // Pengguna tidak ditemukan, mengirim response status error
        $response = array("status" => "error", "message" => "Invalid email or password");
        echo json_encode($response);
      }
    } catch (PDOException $e) {
      // Jika terjadi error, mengirim response dengan pesan error
      $response = array("status" => "error", "message" => $e->getMessage());
      echo json_encode($response);
    }
}

$conn = null;

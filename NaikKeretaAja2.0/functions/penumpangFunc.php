<?php
require_once __DIR__ . '/../config/config.php';

function registerPenumpang($nama, $email, $telepon, $alamat, $password) {
  global $conn;
  $query = "INSERT INTO penumpang (nama, email, no_telepon, alamat, password) VALUES ('$nama', '$email', '$telepon', '$alamat', '$password')";
  return mysqli_query($conn, $query);
}

function loginPenumpang($email, $password) {
  global $conn;
  $result = mysqli_query($conn, "SELECT * FROM penumpang WHERE email = '$email'");
  if (mysqli_num_rows($result) === 1) {
    $user = mysqli_fetch_assoc($result);
    if ($user['PASSWORD'] === $password) {
      return $user;
    }
    else {
        return false;
    }
  }
}
function getAllPenumpang() {
    global $conn;
    $sql = "SELECT * FROM view_data_penumpang";
    $result = mysqli_query($conn, $sql);

    $penumpangs = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $penumpangs[] = $row;
    }
    return $penumpangs;
}

function getPenumpangById($id) {
    global $conn;
    $id = mysqli_real_escape_string($conn, $id);
    $sql = "SELECT * FROM penumpang WHERE id_penumpang = '$id'";
    $result = mysqli_query($conn, $sql);
    return mysqli_fetch_assoc($result);
}

function addPenumpang($nama, $email, $password, $no_telepon, $alamat) {
    global $conn;

    $nama        = mysqli_real_escape_string($conn, trim($nama));
    $email       = mysqli_real_escape_string($conn, trim($email));
    $password    = mysqli_real_escape_string($conn, trim($password));
    $no_telepon  = mysqli_real_escape_string($conn, trim($no_telepon));
    $alamat      = mysqli_real_escape_string($conn, trim($alamat));
    $role        = 'penumpang'; 

    // Panggil stored procedure tambah_penumpang
    $sql = "CALL tambah_penumpang('$nama', '$email', '$password', '$no_telepon', '$alamat', '$role')";

    return mysqli_query($conn, $sql);
}



function updatePenumpang($id, $nama, $email, $password, $no_telepon, $alamat) {
    global $conn;

    $id          = mysqli_real_escape_string($conn, $id);
    $nama        = mysqli_real_escape_string($conn, $nama);
    $email       = mysqli_real_escape_string($conn, $email);
    $password    = mysqli_real_escape_string($conn, $password);
    $no_telepon  = mysqli_real_escape_string($conn, $no_telepon);
    $alamat      = mysqli_real_escape_string($conn, $alamat);

    $sql = "UPDATE penumpang SET 
                nama = '$nama', 
                email = '$email', 
                PASSWORD = '$password', 
                no_telepon = '$no_telepon', 
                alamat = '$alamat' 
            WHERE id_penumpang = '$id'";

    return mysqli_query($conn, $sql);
}

function deletePenumpang($id_penumpang) {
    global $conn;

    $id_penumpang = mysqli_real_escape_string($conn, $id_penumpang);
    $sql = "DELETE FROM penumpang WHERE id_penumpang = '$id_penumpang'";
    
    return mysqli_query($conn, $sql);
}

function getPemesananByIdPenumpang($id_penumpang) {
    global $conn;

    $id_penumpang = mysqli_real_escape_string($conn, $id_penumpang);
    $sql = "SELECT * FROM pemesanan WHERE id_penumpang = '$id_penumpang' LIMIT 1";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        return mysqli_fetch_assoc($result);
    }

    return null; // Tidak ada pemesanan
}



<?php
require_once __DIR__ . '/../config/config.php';

function getAllStasiun() {
    global $conn;
    $sql = "SELECT * FROM stasiun ORDER BY id_stasiun DESC";
    $result = mysqli_query($conn, $sql);
    $stasiuns = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $stasiuns[] = $row;
    }
    return $stasiuns;
}

function getStasiunById($id) {
    global $conn;
    $sql = "SELECT * FROM stasiun WHERE id_stasiun = $id";
    $result = mysqli_query($conn, $sql);
    return mysqli_fetch_assoc($result);
}

function addStasiun($nama, $lokasi) {
    global $conn;
    $nama = mysqli_real_escape_string($conn, $nama);
    $lokasi = mysqli_real_escape_string($conn, $lokasi);
    $sql = "INSERT INTO stasiun (nama_stasiun, lokasi) VALUES ('$nama', '$lokasi')";
    return mysqli_query($conn, $sql);
}

function updateStasiun($id, $nama, $lokasi) {
    global $conn;
    $nama = mysqli_real_escape_string($conn, $nama);
    $lokasi = mysqli_real_escape_string($conn, $lokasi);
    $sql = "UPDATE stasiun SET nama_stasiun='$nama', lokasi='$lokasi' WHERE id_stasiun=$id";
    return mysqli_query($conn, $sql);
}

function deleteStasiun($id) {
    global $conn;
    $sql = "DELETE FROM stasiun WHERE id_stasiun=$id";
    return mysqli_query($conn, $sql);
}
?>

<?php
require_once __DIR__ . '/../config/config.php';

function getAllKereta() {
    global $conn;
    $data = [];
    $sql = "SELECT * FROM view_data_kereta";
    $result = mysqli_query($conn, $sql);

    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }

    return $data;
}

function getKeretaById($id) {
    global $conn;
    $id = mysqli_real_escape_string($conn, $id);
    $sql = "SELECT * FROM kereta WHERE id_kereta = '$id'";
    $result = mysqli_query($conn, $sql);
    return mysqli_fetch_assoc($result);
}


function addKereta($nama, $kelas, $kapasitas) {
    global $conn;

    $nama = mysqli_real_escape_string($conn, $nama);
    $kelas = mysqli_real_escape_string($conn, $kelas);
    $kapasitas = (int) $kapasitas;

    $sql = "CALL tambahKeretaLoop('$nama', '$kelas', $kapasitas)";

    return mysqli_query($conn, $sql);
}


// Edit data kereta
function updateKereta($id, $nama, $kelas, $kapasitas) {
    global $conn;

    $id = mysqli_real_escape_string($conn, $id);
    $nama = mysqli_real_escape_string($conn, $nama);
    $kelas = mysqli_real_escape_string($conn, $kelas);
    $kapasitas = (int) $kapasitas;

    $sql = "UPDATE kereta 
            SET nama_kereta = '$nama', kelas_kereta = '$kelas', kapasitas = $kapasitas 
            WHERE id_kereta = '$id'";

    return mysqli_query($conn, $sql);
}

// Hapus data kereta
function deleteKereta($id) {
    global $conn;

    $id = (int) $id;
    $sql = "CALL hapus_kereta($id)";
    return mysqli_query($conn, $sql);
}
?>

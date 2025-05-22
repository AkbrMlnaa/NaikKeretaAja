<?php
require_once __DIR__ . '/../config/config.php';


function getAllJadwal($limit = null) {
    global $conn;
    $query = "
        SELECT * from view_jadwal_kereta
    ";

    if ($limit) {
        $query .= " LIMIT " . intval($limit);
    }

    $result = mysqli_query($conn, $query);
    $data = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }
    return $data;
}
function getAllJadwalAktif($limit = null) {
    global $conn;
    $query = "
        SELECT * from view_jadwal_kereta_aktif
    ";

    if ($limit) {
        $query .= " LIMIT " . intval($limit);
    }

    $result = mysqli_query($conn, $query);
    $data = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }
    return $data;
}
function getAllJadwalById($id_jadwal) {
    global $conn;

    $query = "
        SELECT 
            jadwal.id_jadwal, 
            kereta.nama_kereta, 
            kereta.kelas_kereta,
            sa.nama_stasiun AS stasiun_asal,
            sa.lokasi AS lokasi_asal,
            st.nama_stasiun AS stasiun_tujuan,
            st.lokasi AS lokasi_tujuan,
            jadwal.waktu_berangkat, 
            jadwal.harga
        FROM jadwal
        JOIN kereta ON jadwal.id_kereta = kereta.id_kereta
        JOIN stasiun sa ON jadwal.id_stasiun_asal = sa.id_stasiun
        JOIN stasiun st ON jadwal.id_stasiun_tujuan = st.id_stasiun
        WHERE jadwal.id_jadwal = $id_jadwal
        LIMIT 1
    ";

    $result = mysqli_query($conn, $query);
    return mysqli_fetch_assoc($result); // HANYA 1 baris
}

function getJadwalById($id_jadwal) {
    global $conn;
    $id_jadwal = intval($id_jadwal);
    $query = "SELECT * FROM jadwal WHERE id_jadwal = $id_jadwal";
    $result = mysqli_query($conn, $query);
    return mysqli_fetch_assoc($result);
}

function addJadwal($id_kereta, $id_stasiun_asal, $id_stasiun_tujuan, $waktu_berangkat, $harga) {
    global $conn;
    $query = "INSERT INTO jadwal (id_kereta, id_stasiun_asal, id_stasiun_tujuan, waktu_berangkat, harga)
              VALUES (?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $query);
    if (!$stmt) {
        die('Prepare failed: ' . mysqli_error($conn));  // Debug jika prepare gagal
    }
    // Harga harus integer, waktu_berangkat string, id lain integer
    mysqli_stmt_bind_param($stmt, "iiisi", $id_kereta, $id_stasiun_asal, $id_stasiun_tujuan, $waktu_berangkat, $harga);
    return mysqli_stmt_execute($stmt);
}

function updateJadwal($id_jadwal, $id_kereta, $id_stasiun_asal, $id_stasiun_tujuan, $waktu_berangkat, $harga) {
    global $conn;
    $query = "UPDATE jadwal 
              SET id_kereta = ?, id_stasiun_asal = ?, id_stasiun_tujuan = ?, waktu_berangkat = ?, harga = ?
              WHERE id_jadwal = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "iiisii", $id_kereta, $id_stasiun_asal, $id_stasiun_tujuan, $waktu_berangkat, $harga, $id_jadwal);
    return mysqli_stmt_execute($stmt);
}

function hapusJadwal($id_jadwal) {
    global $conn;
    $query = "DELETE FROM jadwal WHERE id_jadwal = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $id_jadwal);
    return mysqli_stmt_execute($stmt);
}

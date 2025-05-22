<?php
require_once __DIR__ . '/../config/config.php';

function getAllPemesanan()
{
    global $conn;
    $sql = "SELECT * FROM view_data_pemesanan";
    return mysqli_query($conn, $sql);
}

function addPemesanan($id_penumpang, $id_jadwal, $jumlah_tiket, $total_harga)
{
    global $conn;

    $query = "CALL tambah_pemesanan($id_penumpang, $id_jadwal, $jumlah_tiket, $total_harga)";
    $result = mysqli_query($conn, $query);

    return $result ? true : false;
}
function getPemesananByPenumpang($id_penumpang)
{
    global $conn;

    $query = "
        SELECT 
            p.id_pemesanan,
            p.jumlah_tiket,
            p.total_harga,
            p.tanggal_pemesanan,
            j.waktu_berangkat,
            k.nama_kereta,
            k.kelas_kereta,
            s1.nama_stasiun AS stasiun_asal,
            s2.nama_stasiun AS stasiun_tujuan
        FROM pemesanan p
        JOIN jadwal j ON p.id_jadwal = j.id_jadwal
        JOIN kereta k ON j.id_kereta = k.id_kereta
        JOIN stasiun s1 ON j.id_stasiun_asal = s1.id_stasiun
        JOIN stasiun s2 ON j.id_stasiun_tujuan = s2.id_stasiun
        WHERE p.id_penumpang = ?
        ORDER BY p.tanggal_pemesanan DESC
    ";

    $stmt = mysqli_prepare($conn, $query);
    if (!$stmt) {
        die("Query gagal: " . mysqli_error($conn)); // tampilkan error SQL
    }

    mysqli_stmt_bind_param($stmt, 'i', $id_penumpang);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $data = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }

    return $data;
}

<?php

require_once __DIR__ . '/../config/config.php';
function getLaporanBulanIni() {
    global $conn;
    $bulan = date('n');
    $tahun = date('Y');

    $query = "SELECT total_tiket, total_pendapatan 
              FROM laporan_bulanan 
              WHERE bulan_angka = $bulan AND tahun = $tahun";

    $result = mysqli_query($conn, $query);
    return mysqli_fetch_assoc($result);
}

?>

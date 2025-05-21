<?php
require_once '../config/config.php'; // pastikan ini sudah ada $conn sebagai koneksi mysqli procedural

function getAllStasiun() {
    global $conn;

    $sql = "SELECT * FROM view_data_stasiun ";
    $result = mysqli_query($conn, $sql);

    $stasiuns = [];
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $stasiuns[] = $row;
        }
    }
    return $stasiuns;
}

?>
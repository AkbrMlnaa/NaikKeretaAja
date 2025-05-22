<?php
session_start();
require_once '../../../functions/jadwalFunc.php';

if (isset($_GET['id'])) {
    $id_jadwal = $_GET['id'];
    $success = hapusJadwal($id_jadwal);
    $status = $success ? 'success' : 'error';
    $title = $success ? 'Berhasil' : 'Gagal';
    $text = $success ? 'Data Jadwal Berhasil Dihapus!' : 'Gagal Menghapus Data Jadwal!';
    $redirect = 'jadwal.php';
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <title>Hapus Jadwal</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <script>
        Swal.fire({
            icon: '<?= $status ?>',
            title: '<?= $title ?>',
            text: '<?= $text ?>',
            showConfirmButton: false,
            timer: 1800
        }).then(() => {
            window.location.href = '<?= $redirect ?>';
        });
    </script>
</body>

</html>
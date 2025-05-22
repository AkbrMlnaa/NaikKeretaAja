<?php
session_start();
require_once '../../../functions/stasiunFunc.php';

if (!isset($_SESSION['admin'])) {
    header('Location: ../../index.php');
    exit();
}

if (isset($_GET['id'])) {
    $id_stasiun = $_GET['id'];
    $success = deleteStasiun($id_stasiun);
    $status = $success ? 'success' : 'error';
    $title = $success ? 'Berhasil' : 'Gagal';
    $text = $success ? 'Data Stasiun Berhasil Dihapus!' : 'Gagal Menghapus Data Stasiun!';
    $redirect = 'stasiun.php';
} 
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Hapus Stasiun</title>
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

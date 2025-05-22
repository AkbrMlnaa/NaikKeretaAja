<?php
session_start();
require_once '../../../functions/keretaFunc.php';

if (isset($_GET['id'])) {
    $id_kereta = $_GET['id'];
    $success = deleteKereta($id_kereta);
    $status = $success ? 'success' : 'error';
    $title = $success ? 'Berhasil' : 'Gagal';
    $text = $success ? 'Data Kereta Berhasil Dihapus!' : 'Gagal Menghapus Data Kereta!';
    $redirect = 'kereta.php';
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <title>Hapus Kereta</title>
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
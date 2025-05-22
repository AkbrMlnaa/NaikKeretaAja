<?php
session_start();
require_once '../../../functions/penumpangFunc.php';

if (isset($_GET['id'])) {
    $id_penumpang = $_GET['id'];

    // Cek apakah penghapusan berhasil
    $success = deletePenumpang($id_penumpang);
    $status = $success ? 'success' : 'error';
    $title = $success ? 'Berhasil' : 'Gagal';
    $text = $success ? 'Data Penumpang Berhasil Dihapus!' : 'Gagal Menghapus Data Penumpang!';
    $redirect = 'penumpang.php';
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <title>Hapus Penumpang</title>
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
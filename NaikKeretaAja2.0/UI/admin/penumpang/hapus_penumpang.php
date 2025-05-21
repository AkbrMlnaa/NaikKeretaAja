<?php
require_once '../../../functions/penumpangFunc.php';

if (isset($_GET['id'])) {
    $id_penumpang = $_GET['id'];

    deletePenumpang($id_penumpang); // Tidak perlu cek return jika tidak perlu pesan
}

header("Location: penumpang.php");
exit;

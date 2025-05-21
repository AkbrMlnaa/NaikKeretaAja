<?php
require_once '../../../functions/penumpangFunc.php';

if (isset($_POST['submit'])) {
    $nama        = $_POST['nama'];
    $email       = $_POST['email'];
    $password    = $_POST['password'];
    $no_telepon  = $_POST['no_telepon'];
    $alamat      = $_POST['alamat'];

    if (addPenumpang($nama, $email, $password, $no_telepon, $alamat)) {
        header("Location: penumpang.php");
        exit;
    } else {
        $message = "Gagal menambahkan penumpang: " . mysqli_error($conn);
    }
}
?>


<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Penumpang</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center px-4">

    <div class="bg-white shadow-xl rounded-xl p-8 w-full max-w-md">
        <h2 class="text-2xl font-bold mb-6 text-center text-gray-800">Tambah Penumpang</h2>

        <form method="POST" action="" class="space-y-4">
            <div>
                <label class="block text-gray-700 mb-1">Nama</label>
                <input type="text" name="nama" class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400" required>
            </div>

            <div>
                <label class="block text-gray-700 mb-1">Email</label>
                <input type="email" name="email" class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400" required>
            </div>

            <div>
                <label class="block text-gray-700 mb-1">Password</label>
                <input type="text" name="password" class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400" required>
            </div>

            <div>
                <label class="block text-gray-700 mb-1">No Telepon</label>
                <input type="text" name="no_telepon" class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400" required>
            </div>

            <div>
                <label class="block text-gray-700 mb-1">Alamat</label>
                <textarea name="alamat" rows="3" class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400" required></textarea>
            </div>

            <div class="flex justify-between items-center mt-6">
                <a href="penumpang.php" class="text-sm text-blue-600 hover:underline">&larr; Kembali</a>
                <button type="submit" name="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition">Simpan</button>
            </div>
        </form>
    </div>

</body>
</html>

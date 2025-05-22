<?php
session_start();
require_once '../../../functions/penumpangFunc.php';
if (!isset($_SESSION['admin'])) {
    header('Location: ../../index.php');
    exit();
}

if (isset($_POST['submit'])) {
    $nama        = $_POST['nama'];
    $email       = $_POST['email'];
    $password    = $_POST['password'];
    $no_telepon  = $_POST['no_telepon'];
    $alamat      = $_POST['alamat'];

    if (addPenumpang($nama, $email, $password, $no_telepon, $alamat)) {
        echo "
        <script>
          document.addEventListener('DOMContentLoaded', function() {
            showAlert('success', 'Berhasil', 'Berhasil Tambah Data Penumpang!', 'penumpang.php');
          }); 
        </script>";
    } else {
        echo "
        <script>
          document.addEventListener('DOMContentLoaded', function() {
            showAlert('error', 'Gagal', 'Gagal Tambah Data Penumpang!', 'penumpang.php');
          }); 
        </script>";
    }
}
?>


<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Penumpang</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function showAlert(icon, title, text, redirectUrl = null, timer = 1800) {
            Swal.fire({
                icon: icon,
                title: title,
                text: text,
                showConfirmButton: false,
                timer: timer
            }).then(() => {
                if (redirectUrl) {
                    window.location.href = redirectUrl;
                }
            });
        }
    </script>
</head>

<body class="bg-gray-100 min-h-screen flex items-center justify-center px-4">
    <div class="bg-white shadow-xl rounded-xl p-8 w-full max-w-md">
        <h2 class="text-2xl font-bold mb-6 text-center text-gray-800">Tambah Penumpang</h2>
        <form method="POST" action="" class="space-y-4">
            <div>
                <label class="block text-gray-700 mb-1">Nama</label>
                <input type="text" name="nama" class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-400" required>
            </div>
            <div>
                <label class="block text-gray-700 mb-1">Email</label>
                <input type="email" name="email" class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-400" required>
            </div>
            <div>
                <label class="block text-gray-700 mb-1">Password</label>
                <input type="password" name="password" class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-400" required>
            </div>
            <div>
                <label class="block text-gray-700 mb-1">No Telepon</label>
                <input type="text" name="no_telepon" class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-400" required>
            </div>
            <div>
                <label class="block text-gray-700 mb-1">Alamat</label>
                <textarea name="alamat" rows="3" class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-400" required></textarea>
            </div>
            <div class="flex justify-between items-center mt-6">
                <a href="penumpang.php" class="text-sm text-green-600 hover:underline">&larr; Kembali</a>
                <button type="submit" name="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition">Simpan</button>
            </div>
        </form>
    </div>
</body>
</html>
<?php
require_once '../../../functions/keretaFunc.php';
session_start();
if (!isset($_SESSION['admin'])) {
  header('Location: ../../index.php');
  exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $nama = $_POST['nama_kereta'];
  $kelas = $_POST['kelas_kereta'];
  $kapasitas = $_POST['kapasitas'];

  if (addKereta($nama, $kelas, $kapasitas)) {
    echo "
        <script>
          document.addEventListener('DOMContentLoaded', function() {
            showAlert('success', 'Berhasil', 'Berhasil Tambah Data Kereta!', 'kereta.php');
          }); 
        </script>";
  } else {
    echo "
        <script>
          document.addEventListener('DOMContentLoaded', function() {
            showAlert('error', 'Gagal', 'Gagal Tambah Data Kereta!', 'kereta.php');
          }); 
        </script>";
  }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <title>Tambah Kereta - NaikKeretaAja</title>
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

<body class="bg-gray-100 p-6">
  <div class="max-w-xl mx-auto bg-white p-6 rounded shadow">
    <h1 class="text-2xl font-bold mb-4 text-green-700 text-center">Tambah Kereta</h1>
    <form method="POST" class="space-y-4">
      <div>
        <label class="block mb-1 text-sm font-semibold text-gray-700">Nama Kereta</label>
        <input type="text" name="nama_kereta" required class="w-full px-3 py-2 border rounded" />
      </div>
      <div>
        <label class="block mb-1 text-sm font-semibold text-gray-700">Kelas Kereta</label>
        <select name="kelas_kereta" required class="w-full px-3 py-2 border rounded">
          <option value="Eksekutif">Eksekutif</option>
          <option value="Bisnis">Bisnis</option>
          <option value="Ekonomi">Ekonomi</option>
        </select>
      </div>
      <div>
        <label class="block mb-1 text-sm font-semibold text-gray-700">Kapasitas</label>
        <input type="number" name="kapasitas" required class="w-full px-3 py-2 border rounded" />
      </div>
      <div class="flex justify-between">
        <a href="kereta.php" class="text-sm text-gray-600 hover:underline">← Kembali</a>
        <button type="submit" class="bg-green-700 text-white px-4 py-2 rounded hover:bg-green-800">Simpan</button>
      </div>
    </form>
  </div>

</body>

</html>
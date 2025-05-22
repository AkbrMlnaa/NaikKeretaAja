<?php
require_once '../../../functions/keretaFunc.php';
session_start();
if (!isset($_SESSION['admin'])) {
  header('Location: ../../index.php');
  exit();
}

$id = $_GET['id'] ?? null;

if (!$id) {
  header("Location: kereta.php");
  exit;
}

$data = getKeretaById($id);

if (!$data) {
  echo "Data tidak ditemukan.";
  exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $nama = $_POST['nama_kereta'];
  $kelas = $_POST['kelas_kereta'];
  $kapasitas = $_POST['kapasitas'];

  if (updateKereta($id, $nama, $kelas, $kapasitas)) {
    echo "
        <script>
          document.addEventListener('DOMContentLoaded', function() {
            showAlert('success', 'Berhasil', 'Data Kereta Berhasil Diedit!', 'kereta.php');
          }); 
        </script>";
  } else {
    echo "
        <script>
          document.addEventListener('DOMContentLoaded', function() {
            showAlert('erro', 'Gagal', 'Data Kereta Gagal Diedit!', 'kereta.php');
          }); 
        </script>";
  }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <title>Edit Kereta - NaikKeretaAja</title>
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
    <h1 class="text-2xl font-bold mb-4 text-green-700 text-center">Edit Kereta</h1>

    <form method="POST" class="space-y-4">
      <div>
        <label class="block mb-1 text-sm font-semibold text-gray-700">Nama Kereta</label>
        <input type="text" name="nama_kereta" value="<?= htmlspecialchars($data['nama_kereta']) ?>" required class="w-full px-3 py-2 border rounded" />
      </div>

      <div>
        <label class="block mb-1 text-sm font-semibold text-gray-700">Kelas Kereta</label>
        <select name="kelas_kereta" required class="w-full px-3 py-2 border rounded">
          <option value="Eksekutif" <?= $data['kelas_kereta'] === 'Eksekutif' ? 'selected' : '' ?>>Eksekutif</option>
          <option value="Bisnis" <?= $data['kelas_kereta'] === 'Bisnis' ? 'selected' : '' ?>>Bisnis</option>
          <option value="Ekonomi" <?= $data['kelas_kereta'] === 'Ekonomi' ? 'selected' : '' ?>>Ekonomi</option>
        </select>
      </div>

      <div>
        <label class="block mb-1 text-sm font-semibold text-gray-700">Kapasitas</label>
        <input type="number" name="kapasitas" value="<?= htmlspecialchars($data['kapasitas']) ?>" required class="w-full px-3 py-2 border rounded" />
      </div>

      <div class="flex justify-between">
        <a href="kereta.php" class="text-sm text-gray-600 hover:underline">← Kembali</a>
        <button type="submit" class="bg-green-700 text-white px-4 py-2 rounded hover:bg-green-800">Simpan Perubahan</button>
      </div>
    </form>
  </div>

</body>

</html>
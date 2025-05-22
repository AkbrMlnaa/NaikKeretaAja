<?php
require_once '../../../functions/penumpangfunc.php';
session_start();
if (!isset($_SESSION['admin'])) {
  header('Location: ../../index.php');
  exit();
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
  die('ID penumpang tidak valid.');
}

$id = (int) $_GET['id'];
$penumpang = getPenumpangById($id);

if (!$penumpang) {
  die('Data penumpang tidak ditemukan.');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $nama = $_POST['nama'];
  $email = $_POST['email'];
  $password = $_POST['password'];
  $no_telepon = $_POST['no_telepon'];
  $alamat = $_POST['alamat'];

  $hasil = updatePenumpang($id, $nama, $email, $password, $no_telepon, $alamat);
  if ($hasil) {
    echo "
        <script>
          document.addEventListener('DOMContentLoaded', function() {
            showAlert('success', 'Berhasil', 'Data Penumpang Berhasil Diedit!', 'penumpang.php');
          }); 
        </script>";
  } else {
    echo "
        <script>
          document.addEventListener('DOMContentLoaded', function() {
            showAlert('error', 'Gagal', 'Data Penumpang Gagal Diedit!', 'penumpang.php');
          }); 
        </script>";
  }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Penumpang</title>
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

<body class="bg-gray-100 min-h-screen py-10 px-4">
  <div class="max-w-xl mx-auto bg-white p-8 rounded shadow-md">
    <h2 class="text-2xl font-bold text-green-800 mb-6 text-center">Edit Data Penumpang</h2>
    <form action="" method="POST" class="space-y-4">
      <div>
        <label class="block font-medium">Nama</label>
        <input type="text" name="nama" value="<?= htmlspecialchars($penumpang['nama']) ?>" class="w-full border px-3 py-2 rounded" required>
      </div>
      <div>
        <label class="block font-medium">Email</label>
        <input type="email" name="email" value="<?= htmlspecialchars($penumpang['email']) ?>" class="w-full border px-3 py-2 rounded" required>
      </div>
      <div>
        <label class="block font-medium">Password</label>
        <input type="text" name="password" value="<?= htmlspecialchars($penumpang['PASSWORD']) ?>" class="w-full border px-3 py-2 rounded" required>
      </div>
      <div>
        <label class="block font-medium">No Telepon</label>
        <input type="text" name="no_telepon" value="<?= htmlspecialchars($penumpang['no_telepon']) ?>" class="w-full border px-3 py-2 rounded" required>
      </div>
      <div>
        <label class="block font-medium">Alamat</label>
        <textarea name="alamat" class="w-full border px-3 py-2 rounded" rows="3" required><?= htmlspecialchars($penumpang['alamat']) ?></textarea>
      </div>
      <div class="flex justify-between mt-6">
        <a href="penumpang.php" class="px-4 py-2 bg-gray-300 text-gray-800 rounded hover:bg-gray-400">Batal</a>
        <button type="submit" class="px-4 py-2 bg-green-700 text-white rounded hover:bg-green-800">Simpan Perubahan</button>
      </div>
    </form>
  </div>
</body>

</html>
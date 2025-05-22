<?php
require_once '../../../functions/stasiunFunc.php';

if (!isset($_GET['id'])) {
  header('Location: stasiun.php');
  exit;
}

$id = $_GET['id'];
$stasiun = getStasiunById($id);

if (!$stasiun) {
  die('Stasiun tidak ditemukan.');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $nama = $_POST['nama_stasiun'];
  $lokasi = $_POST['lokasi'];

  if (updateStasiun($id, $nama, $lokasi)) {
    echo "
        <script>
          document.addEventListener('DOMContentLoaded', function() {
            showAlert('success', 'Berhasil', 'Data Stasiun Berhasil Diedit!', 'stasiun.php');
          }); 
        </script>";
  } else {
    echo "
        <script>
          document.addEventListener('DOMContentLoaded', function() {
            showAlert('error', 'Gagal', 'Data Stasiun Gagal Diedit!', 'stasiun.php');
          }); 
        </script>";
  }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <title>Edit Stasiun</title>
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

<body class="bg-gray-100 py-10">
  <div class="max-w-xl mx-auto bg-white p-6 rounded shadow">
    <h2 class="text-2xl font-bold text-green-800 mb-6">Edit Stasiun</h2>

    <?php if (isset($error)): ?>
      <p class="text-red-500 mb-4"><?= $error ?></p>
    <?php endif; ?>

    <form method="POST">
      <div class="mb-4">
        <label class="block mb-1">Nama Stasiun</label>
        <input type="text" name="nama_stasiun" value="<?= htmlspecialchars($stasiun['nama_stasiun']) ?>" required class="w-full px-4 py-2 border rounded">
      </div>
      <div class="mb-4">
        <label class="block mb-1">Lokasi</label>
        <input type="text" name="lokasi" value="<?= htmlspecialchars($stasiun['lokasi']) ?>" required class="w-full px-4 py-2 border rounded">
      </div>
      <div class="flex justify-between">
        <a href="stasiun.php" class="bg-gray-300 px-4 py-2 rounded hover:bg-gray-400">← Kembali</a>
        <button type="submit" class="bg-green-700 text-white px-4 py-2 rounded hover:bg-green-800">Update</button>
      </div>
    </form>
  </div>
</body>

</html>
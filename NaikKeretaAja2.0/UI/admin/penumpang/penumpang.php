<?php
require_once '../../../functions/penumpangFunc.php'; 

$penumpangs = getAllPenumpang(); 

if (isset($_GET['id'])) {
    $id_penumpang = $_GET['id'];

    if (deletePenumpang($id_penumpang)) {
        header("Location: penumpang.php?msg=deleted");
        exit;
    } else {
        header("Location: penumpang.php?msg=error");
        exit;
    }
}
?>
<!-- baru setelah itu tampilkan HTML -->


<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>NaikKeretaAja - Data Penumpang</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body class="bg-gray-50 text-gray-800 font-sans pt-16">

  <!-- Main Section -->
  <section class="bg-white pt-10 pb-16 min-h-screen">
    <div class="max-w-7xl mx-auto px-4">

      <!-- Tombol Kembali -->
      <div class="mb-4">
        <a href="../dashboard.php" class="bg-gray-200 text-gray-800 px-4 py-2 rounded hover:bg-gray-300 text-sm inline-block">
          ← Kembali
        </a>
      </div>

      <!-- Judul -->
      <h2 class="text-3xl font-bold text-green-800 mb-8 text-center">Manajemen Data Penumpang</h2>

      <!-- Tombol Tambah -->
      <div class="mb-6 text-right">
        <a href="tambah_penumpang.php" class="bg-green-700 text-white px-4 py-2 rounded hover:bg-green-800 text-sm">
          Tambah Penumpang
        </a>
      </div>

      <!-- Tabel Penumpang -->
      <div class="overflow-x-auto bg-gray-50 p-4 rounded-md shadow-md">
        <table class="w-full text-center rounded-lg text-sm overflow-hidden">
          <thead class="border-b-2 bg-green-700 border-green-700 text-white">
            <tr>
              <th class="px-4 py-2">Nama</th>
              <th class="px-4 py-2">Email</th>
              <th class="px-4 py-2">Password</th>
              <th class="px-4 py-2">No Telpon</th>
              <th class="px-4 py-2">Alamat</th>
              <th class="px-4 py-2">Aksi</th>
            </tr>
          </thead>
          <tbody class="text-gray-700">
            <?php if (empty($penumpangs)): ?>
              <tr><td colspan="6" class="px-4 py-2 text-center">Tidak ada data penumpang</td></tr>
            <?php else: ?>
              <?php foreach ($penumpangs as $p): ?>
                <tr class="border-t">
                  <td class="px-4 py-4"><?= htmlspecialchars($p['nama']) ?></td>
                  <td class="px-4 py-4"><?= htmlspecialchars($p['email']) ?></td>
                  <td class="px-4 py-4"><?= htmlspecialchars($p['password']) ?></td>
                  <td class="px-4 py-4"><?= htmlspecialchars($p['no_telepon']) ?></td>
                  <td class="px-4 py-4"><?= htmlspecialchars($p['alamat']) ?></td>
                  <td class="px-4 py-4 space-x-2">
                    <a href="edit_penumpang.php?id=<?= $p['id_penumpang'] ?>" class="bg-yellow-500 hover:bg-yellow-700 text-white px-3 py-1 rounded">Edit</a>
                    <a href="hapus_penumpang.php?id=<?= $p['id_penumpang'] ?>" class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded">Hapus</a>
                  </td>
                </tr>
              <?php endforeach ?>
            <?php endif ?>
          </tbody>
        </table>
      </div>
    </div>
  </section>

</body>
</html>

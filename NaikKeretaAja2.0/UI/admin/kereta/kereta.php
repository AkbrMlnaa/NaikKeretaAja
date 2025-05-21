<?php
require_once '../../../functions/keretaFunc.php'; 

$daftarKereta = getAllKereta();
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>NaikKeretaAja</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body class="bg-gray-50 text-gray-800 font-sans pt-16">

<section class="bg-white pt-10 pb-16 min-h-screen">
  <div class="max-w-7xl mx-auto px-4">

    <!-- Tombol Kembali -->
    <div class="mb-4">
      <a href="../dashboard.php" class="bg-gray-200 text-gray-800 px-4 py-2 rounded hover:bg-gray-300 text-sm inline-block">
        ← Kembali
      </a>
    </div>

    <!-- Judul -->
    <h2 class="text-3xl font-bold text-green-800 mb-8 text-center">Manajemen Data Kereta</h2>

    <!-- Tombol Tambah -->
    <div class="mb-6 text-right">
      <a href="tambah_kereta.php" class="bg-green-700 text-white px-4 py-2 rounded hover:bg-green-800 text-sm">
        Tambah Kereta
      </a>
    </div>

    <!-- Tabel Data Kereta -->
    <div class="overflow-x-auto bg-gray-50 p-4 rounded-md shadow-md">
      <table class="min-w-full text-center text-sm">
        <thead class="border-b-2 bg-green-700 text-white rounded-lg">
          <tr>
            <th class="px-4 py-2">Nama Kereta</th>
            <th class="px-4 py-2">Kelas</th>
            <th class="px-4 py-2">Kapasitas</th>
            <th class="px-4 py-2">Aksi</th>
          </tr>
        </thead>
        <tbody class="text-gray-700">
          <?php if (count($daftarKereta) > 0): ?>
            <?php foreach ($daftarKereta as $kereta): ?>
              <tr class="border-t">
                <td class="px-4 py-2"><?= htmlspecialchars($kereta['nama_kereta']) ?></td>
                <td class="px-4 py-2"><?= htmlspecialchars($kereta['kelas_kereta']) ?></td>
                <td class="px-4 py-2"><?= htmlspecialchars($kereta['kapasitas']) ?></td>
                <td class="px-4 py-2 space-x-2">
                  <a href="edit_kereta.php?id=<?= $kereta['id_kereta'] ?>" class="bg-yellow-500 hover:bg-yellow-700 text-white px-3 py-1 rounded">Edit</a>
                  <a href="hapus_kereta.php?id=<?= $kereta['id_kereta'] ?>" class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded" onclick="return confirm('Yakin ingin menghapus kereta ini?')">Hapus</a>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <td colspan="4" class="px-4 py-4 text-gray-500">Tidak ada data kereta.</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</section>

</body>
</html>

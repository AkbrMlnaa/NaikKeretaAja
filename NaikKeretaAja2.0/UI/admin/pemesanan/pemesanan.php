<?php
require_once '../../../functions/pemesananFunc.php';

// Ambil data dari database
$result = getAllPemesanan();
$pemesanans = [];

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $pemesanans[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Data Pemesanan - NaikKeretaAja</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body class="bg-gray-50 text-gray-800 font-sans pt-16">

  <section class="bg-white pt-10 pb-10 min-h-screen">
    <div class="max-w-7xl mx-auto px-4">

      <!-- Tombol Kembali -->
      <div class="mb-4">
        <a href="../dashboard.php" class="bg-gray-200 text-gray-800 px-4 py-2 rounded hover:bg-gray-300 text-sm inline-block">
          ← Kembali
        </a>
      </div>

      <!-- Judul -->
      <h2 class="text-3xl font-bold text-green-800 mb-8 text-center">Data Pemesanan Tiket</h2>

      <!-- Tabel Data Pemesanan -->
      <div class="overflow-x-auto bg-gray-50 p-4 rounded-md shadow-md">
        <table class="min-w-full text-center text-sm">
          <thead class="border-b-2 bg-green-700 text-white">
            <tr>
              <th class="px-4 py-2">No</th>
              <th class="px-4 py-2">Nama Penumpang</th>
              <th class="px-4 py-2">Nama Kereta</th>
              <th class="px-4 py-2">Waktu Berangkat</th>
              <th class="px-4 py-2">Jumlah Tiket</th>
              <th class="px-4 py-2">Total Harga</th>
              <th class="px-4 py-2">Tanggal Pemesanan</th>
            </tr>
          </thead>
          <tbody class="text-gray-700">
            <?php if (count($pemesanans) > 0): ?>
              <?php $no = 1; ?>
              <?php foreach ($pemesanans as $p): ?>
                <tr class="border-t">
                  <td class="px-4 py-2"><?= $no++ ?></td>
                  <td class="px-4 py-2"><?= htmlspecialchars($p['nama_penumpang']) ?></td>
                  <td class="px-4 py-2"><?= htmlspecialchars($p['nama_kereta']) ?></td>
                  <td class="px-4 py-2"><?= $p['waktu_berangkat'] ?></td>
                  <td class="px-4 py-2"><?= $p['jumlah_tiket'] ?></td>
                  <td class="px-4 py-2">Rp<?= number_format($p['total_harga'], 0, ',', '.') ?></td>
                  <td class="px-4 py-2"><?= $p['tanggal_pemesanan'] ?></td>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr>
                <td colspan="7" class="px-4 py-4 text-gray-500">Belum ada data pemesanan.</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </section>

</body>
</html>

<?php
session_start();
require_once '../config/config.php';
require_once '../functions/pemesananFunc.php';
if (!isset($_SESSION['penumpang'])) {
  header('Location: index.php');
  exit;
}

$id_penumpang = $_SESSION['penumpang']['id_penumpang'];
$tiket = getPemesananByPenumpang($id_penumpang);
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
  <header class="fixed top-0 left-0 w-full backdrop-blur-md bg-white/80 shadow-md z-50">
    <div class="max-w-7xl mx-auto px-4 py-4 flex justify-between items-center">
      <h1 class="text-2xl font-bold text-green-800">NaikKeretaAja</h1>
      <nav class="space-x-6 text-xl">
        <a href="index.php" class="nav-link text-green-800 font-semibold">Home</a>
        <a href="pencarian.php" class="nav-link text-green-800 font-semibold">Pencarian</a>
        <?php if (isset($_SESSION['admin'])): ?>
          <a href="admin/dashboard.php" class="nav-link text-green-800 font-semibold">Dashboard</a>
        <?php elseif (isset($_SESSION['penumpang'])): ?>
          <a href="tiketku.php" class="nav-link text-green-800 font-semibold">Tiketku</a>
        <?php endif; ?>
      </nav
        <div class="space-x-2 text-lg">
      <?php if (isset($_SESSION['penumpang'])): ?>
        <span class="text-green-800 font-semibold">
          <?= htmlspecialchars($_SESSION['penumpang']['nama']) ?>
        </span>
        <a href="logout.php" class="text-white bg-red-600 px-4 py-1 rounded hover:bg-red-700">Logout</a>
      <?php elseif (isset($_SESSION['admin'])): ?>
        <span class="text-green-800 font-semibold">
          Admin: <?= htmlspecialchars($_SESSION['admin']['nama']) ?>
        </span>
        <a href="logout.php" class="text-white bg-red-600 px-4 py-1 rounded hover:bg-red-700">Logout</a>
      <?php else: ?>
        <button onclick="openForm('loginForm')" class="text-green-800 font-semibold hover:underline">Masuk</button>
        <button onclick="openForm('registerForm')" class="text-white bg-green-800 px-4 py-1 rounded hover:bg-green-700">Daftar</button>
      <?php endif; ?>
    </div>
    </div>
  </header>
  <h1 class="text-3xl font-bold text-center text-green-800 my-6">Tiketku</h1>
  <div class="max-w-6xl mx-auto px-4 grid md:grid-cols-2 gap-8">
    <?php if (empty($tiket)): ?>
      <div class="col-span-full text-center text-gray-600">
        <p>Kamu belum memesan tiket apapun.</p>
        <a href="pencarian.php" class="mt-4 inline-block bg-green-700 text-white px-6 py-2 rounded hover:bg-green-600">Pesan Tiket Sekarang</a>
      </div>
    <?php else: ?>
      <?php foreach ($tiket as $t): ?>
        <div id="tiket-<?= $t['id_pemesanan'] ?>" class="border-2 border-dashed border-green-500 rounded-xl p-4 bg-white shadow">
          <h2 class="text-center text-green-700 font-bold text-xl mb-6">Tiket Kereta</h2>
          <table class="w-full text-sm text-gray-700 mb-4">
            <tr class="border-b">
              <td class="py-2 font-semibold">Nama Penumpang</td>
              <td class="py-2"><?= htmlspecialchars($_SESSION['penumpang']['nama']) ?></td>
            </tr>
            <tr class="border-b">
              <td class="py-2 font-semibold">Kereta</td>
              <td class="py-2"><?= htmlspecialchars($t['nama_kereta']) ?> - <?= htmlspecialchars($t['kelas_kereta']) ?></td>
            </tr>
            <tr class="border-b">
              <td class="py-2 font-semibold">Asal</td>
              <td class="py-2"><?= htmlspecialchars($t['stasiun_asal']) ?></td>
            </tr>
            <tr class="border-b">
              <td class="py-2 font-semibold">Tujuan</td>
              <td class="py-2"><?= htmlspecialchars($t['stasiun_tujuan']) ?></td>
            </tr>
            <tr class="border-b">
              <td class="py-2 font-semibold">Tanggal Berangkat</td>
              <td class="py-2"><?= date('d-m-Y', strtotime($t['waktu_berangkat'])) ?></td>
            </tr>
            <tr class="border-b">
              <td class="py-2 font-semibold">Jam Berangkat</td>
              <td class="py-2"><?= date('H:i', strtotime($t['waktu_berangkat'])) ?></td>
            </tr>
            <tr class="border-b">
              <td class="py-2 font-semibold">Jumlah Tiket</td>
              <td class="py-2"><?= $t['jumlah_tiket'] ?></td>
            </tr>
            <tr>
              <td class="py-2 font-semibold">Harga</td>
              <td class="py-2">Rp <?= number_format($t['total_harga'], 0, ',', '.') ?></td>
            </tr>
          </table>
          <div class="bg-green-100 text-green-800 text-sm p-4 rounded-md mb-4">
            Pastikan hadir di stasiun minimal <span class="font-bold">30 menit sebelum keberangkatan</span>.<br>
            Bawa tiket ini dan kartu identitas saat boarding.
          </div>
          <p class="text-center text-sm text-gray-500 italic mb-4">
            Terima kasih telah menggunakan layanan <span class="font-semibold text-gray-700">NaikKeretaAja</span> 🚆
          </p>
          <div class="text-center">
            <button onclick="printTicket('tiket-<?= $t['id_pemesanan'] ?>')" class="inline-flex items-center gap-2 bg-green-700 hover:bg-green-600 text-white px-6 py-2 rounded-full text-sm font-semibold shadow">
              <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 9v12h12V9m-6 6v6m0 0H6m6 0h6M6 3h12a2 2 0 012 2v4H4V5a2 2 0 012-2z" />
              </svg>
              Cetak Tiket
            </button>
          </div>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>

  <!-- Script Cetak -->
  <script>
    function printTicket(id) {
      const element = document.getElementById(id);
      const newWindow = window.open('', '', 'width=800,height=600');
      newWindow.document.write('<html><head><title>Cetak Tiket</title>');
      newWindow.document.write('<script src="https://cdn.tailwindcss.com"><\/script>');
      newWindow.document.write('</head><body class="p-4 font-sans">' + element.innerHTML + '</body></html>');
      newWindow.document.close();
      newWindow.print();
    }
  </script>

  <footer class="bg-gray-100 text-center text-sm text-gray-600 py-6 mt-20 border-t">
    <div class="max-w-4xl mx-auto px-4">
      <p>Kami menggunakan cookie untuk memastikan bahwa situs web kami bekerja dengan baik dan memberikan pengalaman terbaik. Lihat
        <a href="#" class="text-blue-600 hover:underline">Kebijakan Cookie</a> kami.
      </p>
      <p class="mt-2">© 2025 NaikKeretaAja. Dibuat oleh <strong>Akbar Maulana Husada</strong>.</p>
    </div>
  </footer>
</body>

</html>
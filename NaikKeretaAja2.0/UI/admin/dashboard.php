<?php
session_start();
if (!isset($_SESSION['admin'])) {
  header('Location: ../index.php');
  exit();
}
require_once '../../functions/trigger.php';
$data = getLaporanBulanIni();

$totalTiket = $data ? $data['total_tiket'] : 0;
$totalPendapatan = $data ? $data['total_pendapatan'] : 0;
?>
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>NaikKeretaAja</title>
  <link rel="stylesheet" href="../../assets/css/style.css">
  <script src="https://unpkg.com/lucide@latest"></script>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50 text-gray-800 font-sans pt-16">


  <header class="fixed top-0 left-0 w-full backdrop-blur-md bg-white/80 shadow-md z-50">
    <div class="max-w-7xl mx-auto px-4 py-4 flex justify-between items-center">
      <h1 class="text-2xl font-bold text-green-800">NaikKeretaAja</h1>
      <nav class="space-x-6 text-lg">
        <a href="../index.php" class="nav-link text-green-800 font-semibold">Home</a>
        <a href="../pencarian.php" class="nav-link text-green-800 font-semibold">Pencarian</a>
        <a href="#" class="nav-link text-green-800 font-semibold">Dashboard</a>
      </nav>
      <div class="space-x-2 text-lg">
        <?php if (isset($_SESSION['penumpang'])): ?>
          <span class="text-green-800 font-semibold"><?= htmlspecialchars($_SESSION['penumpang']['nama']) ?></span>
          <a href="logout.php" class="text-white bg-red-600 px-4 py-1 rounded hover:bg-red-700">Logout</a>
        <?php elseif (isset($_SESSION['admin'])): ?>
          <span class="text-green-800 font-semibold">Admin</span>
          <a href="../logout.php" class="text-white bg-red-600 px-4 py-1 rounded hover:bg-red-700">Logout</a>
        <?php else: ?>
          <button onclick="openForm('loginForm')" class="text-green-800 font-semibold hover:underline">Masuk</button>
          <button onclick="openForm('registerForm')" class="text-white bg-green-800 px-4 py-1 rounded hover:bg-green-700">Daftar</button>
        <?php endif; ?>
      </div>
    </div>
  </header>

  <section class="bg-white pt-4 pb-10 min-h-screen">
    <div class="max-w-6xl mx-auto px-4">
      <h2 class="text-3xl font-bold text-green-800 mb-8 text-center animate-fade-in">Dashboard Admin</h2>
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 animate-fade-in-up">
        <!-- Card 1 -->
        <div class="bg-gray-50 border rounded-2xl p-6 shadow-xl hover:shadow-2xl transition-all duration-300 ease-in-out transform hover:-translate-y-1">
          <h3 class="text-2xl font-semibold text-green-700 mb-4 flex items-center gap-2">
            <i data-lucide="user-check" class="w-6 h-6"></i> Manajemen Pengguna
          </h3>
          <ul class="space-y-2 text-sm text-gray-700">
            <li><strong>Total Pengguna:</strong> 245</li>
            <li><strong>Pengguna Aktif Hari Ini:</strong> 38</li>
          </ul>
          <div class="mt-6">
            <a href="penumpang/penumpang.php" class="bg-green-700 text-white px-4 py-2 rounded hover:bg-green-800 text-sm inline-block">
              Kelola Pengguna
            </a>
          </div>
        </div>

        <div class="bg-gray-50 border rounded-2xl p-6 shadow-xl hover:shadow-2xl transition-all duration-300 ease-in-out transform hover:-translate-y-1">
          <h3 class="text-2xl font-semibold text-green-700 mb-4 flex items-center gap-2">
            <i data-lucide="navigation" class="w-6 h-6"></i> Manajemen Stasiun
          </h3>
          <ul class="space-y-2 text-sm text-gray-700">
            <li><strong>Total Stasiun:</strong> 245</li>
            <li><strong>Stasiun Aktif Hari Ini:</strong> 38</li>
          </ul>
          <div class="mt-6">
            <a href="stasiun/stasiun.php" class="bg-green-700 text-white px-4 py-2 rounded hover:bg-green-800 text-sm inline-block">
              Kelola Stasiun
            </a>
          </div>
        </div>

        <div class="bg-gray-50 border rounded-2xl p-6 shadow-xl hover:shadow-2xl transition-all duration-300 ease-in-out transform hover:-translate-y-1">
          <h3 class="text-2xl font-semibold text-green-700 mb-4 flex items-center gap-2">
            <i data-lucide="tram-front" class="w-6 h-6"></i> Manajemen Kereta
          </h3>
          <ul class="space-y-2 text-sm text-gray-700">
            <li><strong>Jumlah Kereta:</strong> 15</li>
            <li><strong>Jadwal Aktif Hari Ini:</strong> 10</li>
          </ul>
          <div class="mt-6">
            <a href="kereta/kereta.php" class="bg-green-700 text-white px-4 py-2 rounded hover:bg-green-800 text-sm inline-block">
              Kelola Kereta
            </a>
          </div>
        </div>

        <div class="sm:col-span-2 lg:col-span-3 flex justify-center gap-6">
          <!-- Card 4 -->
          <div class="bg-gray-50 border rounded-2xl p-6 shadow-xl hover:shadow-2xl transition-all duration-300 ease-in-out transform hover:-translate-y-1 w-full max-w-md">
            <h3 class="text-2xl font-semibold text-green-700 mb-4 flex items-center gap-2">
              <i data-lucide="calendar-clock" class="w-6 h-6"></i> Manajemen Jadwal
            </h3>
            <ul class="space-y-2 text-sm text-gray-700">
              <li><strong>Total Jadwal:</strong> 35</li>
              <li><strong>Jadwal Aktif Hari Ini:</strong> 10</li>
            </ul>
            <div class="mt-6">
              <a href="jadwal/jadwal.php" class="bg-green-700 text-white px-4 py-2 rounded hover:bg-green-800 text-sm inline-block">
                Kelola Jadwal
              </a>
            </div>
          </div>


          <div class="bg-gray-50 border rounded-2xl p-6 shadow-xl hover:shadow-2xl transition-all duration-300 ease-in-out transform hover:-translate-y-1 w-full max-w-md">
            <h3 class="text-2xl font-semibold text-green-700 mb-4 flex items-center gap-2">
              <i data-lucide="ticket" class="w-6 h-6"></i> Manajemen Pemesanan
            </h3>
            <ul class="space-y-2 text-sm text-gray-700">
              <li><strong>Total Pemesanan:</strong> 1.750</li>
              <li><strong>Pemesanan Hari Ini:</strong> 120</li>
            </ul>
            <div class="mt-6">
              <a href="pemesanan/pemesanan.php" class="bg-green-700 text-white px-4 py-2 rounded hover:bg-green-800 text-sm inline-block">
                Kelola Pemesanan
              </a>
            </div>
          </div>
        </div>
      </div>

      <div class="mt-16 animate-fade-in-up">
        <h3 class="text-2xl font-semibold text-green-800 mb-4 text-center">Statistik Pemesanan Tiket</h3>
        <div class="bg-gradient-to-br from-green-50 to-white border rounded-xl shadow-xl p-8 text-center text-sm text-gray-700">
          <p class="text-base"><strong>Total Tiket Terjual Bulan Ini:</strong> <?= number_format($totalTiket) ?></p>
          <p class="mt-3 text-base"><strong>Pendapatan Estimasi:</strong> Rp. <?= number_format($totalPendapatan, 0, ',', '.') ?></p>
        </div>
      </div>
    </div>
  </section>

  <footer class="bg-gray-100 text-center text-sm text-gray-600 py-6 mt-20 border-t">
    <div class="max-w-4xl mx-auto px-4">
      <p>Kami menggunakan cookie untuk memastikan bahwa situs web kami bekerja dengan baik dan memberikan pengalaman terbaik. Lihat
        <a href="#" class="text-blue-600 hover:underline">Kebijakan Cookie</a> kami.
      </p>
      <p class="mt-2">© 2025 NaikKeretaAja. Dibuat oleh <strong>Akbar Maulana Husada</strong>.</p>
    </div>
  </footer>
  <script>
    lucide.createIcons();
  </script>
</body>

</html>
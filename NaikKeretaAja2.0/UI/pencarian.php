<?php
session_start();

require_once '../config/config.php';
require_once '../functions/jadwalFunc.php';
require_once '../functions/stasiunFunc.php';
require_once '../functions/penumpangFunc.php'; 
require_once '../functions/adminFunc.php';     

$jadwals = getAllJadwal(); 
$stasiuns = getAllStasiun(); 
$hasilPencarian = $jadwals; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (isset($_POST['register'])) {
    $nama = $_POST['nama'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $telepon = $_POST['telepon'];
    $alamat = $_POST['alamat'];
    $result = registerPenumpang($nama, $email, $telepon, $alamat, $password);
    if ($result) {
      echo "<script>alert('Registrasi berhasil. Silakan login!');</script>";
    } else {
      echo "<script>alert('Registrasi gagal. Email mungkin sudah digunakan.');</script>";
    }
  }

  if (isset($_POST['login'])) {
    $usernameOrEmail = $_POST['email']; // bisa email (penumpang) atau username (admin)
    $password = $_POST['password'];
    $user = loginPenumpang($usernameOrEmail, $password);

    if ($user) {
      $_SESSION['penumpang'] = $user;
      header('Location: index.php');
      exit;
    } else {
      $admin = loginAdmin($usernameOrEmail, $password);
      if ($admin) {
        $_SESSION['admin'] = $admin;
        header('Location: admin/dashboard.php');
        exit;
      } else {
        $loginError = "Email/Username atau password salah.";
      }
    }
  }
}

// Proses pencarian
if ($_SERVER["REQUEST_METHOD"] === "GET" && (
    isset($_GET['stasiun_awal']) || isset($_GET['stasiun_tujuan']) ||
    isset($_GET['kelas']) || isset($_GET['tanggal'])
)) {
    $awal = strtolower(trim($_GET['stasiun_awal'] ?? ''));
    $tujuan = strtolower(trim($_GET['stasiun_tujuan'] ?? ''));
    $kelas = strtolower(trim($_GET['kelas'] ?? ''));
    $tanggal = $_GET['tanggal'] ?? '';

    $hasilPencarian = array_filter($jadwals, function ($jadwal) use ($awal, $tujuan, $kelas, $tanggal) {
        $match_awal = $awal === '' || strtolower($jadwal['stasiun_asal']) === $awal;
        $match_tujuan = $tujuan === '' || strtolower($jadwal['stasiun_tujuan']) === $tujuan;
        $match_kelas = $kelas === '' || strtolower($jadwal['kelas_kereta']) === $kelas;
        $match_tanggal = $tanggal === '' || date('Y-m-d', strtotime($jadwal['waktu_berangkat'])) === $tanggal;
        return $match_awal && $match_tujuan && $match_kelas && $match_tanggal;
    });
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <title>Pencarian Tiket - NaikKeretaAja</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link rel="stylesheet" href="../assets/css/style.css">
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 pt-20 text-gray-800 font-sans">

<header class="fixed top-0 left-0 w-full backdrop-blur-md bg-white/70 shadow-md z-50">
  <div class="max-w-7xl mx-auto px-4 py-4 flex justify-between items-center">
    <h1 class="text-2xl font-bold text-green-800">NaikKeretaAja</h1>
         <nav class="space-x-4 text-xl">
          <a href="index.php" class="nav-link text-green-800 font-semibold">Home</a>
          <a href="pencarian.php" class="nav-link text-green-800 font-semibold">Pencarian</a>

          <?php if (isset($_SESSION['admin'])): ?>
              <a href="admin/dashboard.php" class="nav-link text-green-800 font-semibold">Dashboard</a>
          <?php elseif (isset($_SESSION['penumpang'])): ?>
              <a href="tiketku.php" class="nav-link text-green-800 font-semibold">Tiketku</a>
          <?php endif; ?>
      </nav>
    <div class="space-x-2 text-lg">
      <?php if (isset($_SESSION['penumpang'])): ?>
        <span class="text-green-800 font-semibold"><?= htmlspecialchars($_SESSION['penumpang']['nama']) ?></span>
        <a href="logout.php" class="text-white bg-red-600 px-4 py-1 rounded hover:bg-red-700">Logout</a>
      <?php elseif (isset($_SESSION['admin'])): ?>
        <span class="text-green-800 font-semibold">Admin</span>
        <a href="logout.php" class="text-white bg-red-600 px-4 py-1 rounded hover:bg-red-700">Logout</a>
      <?php else: ?>
        <button onclick="openForm('loginForm')" class="text-green-800 font-semibold hover:underline">Masuk</button>
        <button onclick="openForm('registerForm')" class="text-white bg-green-800 px-4 py-1 rounded hover:bg-green-700">Daftar</button>
      <?php endif; ?>
    </div>
  </div>
</header>

<main class="bg-white py-10">
  <div class="max-w-4xl mx-auto px-4">
    <h2 class="text-3xl font-bold text-center mb-6 text-green-800">Cari Tiket Kereta</h2>

    <form action="pencarian.php" method="GET" class="grid md:grid-cols-2 gap-6 bg-gray-50 p-6 rounded shadow">
      <div>
        <label for="stasiun_awal" class="block font-medium mb-1">Stasiun Awal</label>
        <select name="stasiun_awal" id="stasiun_awal" class="w-full border rounded px-4 py-2 focus:ring-2 focus:ring-green-700">
          <option value="">Pilih Stasiun Awal</option>
          <?php foreach ($stasiuns as $s): ?>
            <option value="<?= htmlspecialchars($s['nama_stasiun']) ?>" <?= (isset($_GET['stasiun_awal']) && $_GET['stasiun_awal'] === $s['nama_stasiun']) ? 'selected' : '' ?>>
              <?= htmlspecialchars($s['nama_stasiun']) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <div>
        <label for="stasiun_tujuan" class="block font-medium mb-1">Stasiun Tujuan</label>
        <select name="stasiun_tujuan" id="stasiun_tujuan" class="w-full border rounded px-4 py-2 focus:ring-2 focus:ring-green-700">
          <option value="">Pilih Stasiun Tujuan</option>
          <?php foreach ($stasiuns as $s): ?>
            <option value="<?= htmlspecialchars($s['nama_stasiun']) ?>" <?= (isset($_GET['stasiun_tujuan']) && $_GET['stasiun_tujuan'] === $s['nama_stasiun']) ? 'selected' : '' ?>>
              <?= htmlspecialchars($s['nama_stasiun']) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <div>
        <label for="kelas" class="block font-medium mb-1">Kelas Kereta</label>
        <select name="kelas" id="kelas" class="w-full border rounded px-4 py-2 focus:ring-2 focus:ring-green-700">
          <option value="">Pilih Kelas</option>
          <option value="ekonomi" <?= ($_GET['kelas'] ?? '') === 'ekonomi' ? 'selected' : '' ?>>Ekonomi</option>
          <option value="bisnis" <?= ($_GET['kelas'] ?? '') === 'bisnis' ? 'selected' : '' ?>>Bisnis</option>
          <option value="eksekutif" <?= ($_GET['kelas'] ?? '') === 'eksekutif' ? 'selected' : '' ?>>Eksekutif</option>
        </select>
      </div>

      <div>
        <label for="tanggal" class="block font-medium mb-1">Tanggal Keberangkatan</label>
        <input type="date" name="tanggal" id="tanggal" class="w-full border rounded px-4 py-2 focus:ring-2 focus:ring-green-700" value="<?= htmlspecialchars($_GET['tanggal'] ?? '') ?>" />
      </div>

      <div class="md:col-span-2 flex justify-between items-center mt-4">
        <a href="index.php" class="text-green-700 hover:underline text-sm">Kembali ke Home</a>
        <button type="submit" class="bg-green-800 text-white px-6 py-2 rounded hover:bg-green-700">Cari Keberangkatan</button>
      </div>
    </form>
  </div>
</main>

<section class="bg-white py-16">
  <div class="max-w-7xl mx-auto px-4">
    <h1 class="text-3xl font-bold text-center mb-12 text-green-800">Daftar Tiket Kereta</h1>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
      <?php foreach ($hasilPencarian as $jadwal): ?>
        <div class="border shadow-md rounded-md p-6 transition-transform duration-300 hover:scale-[1.02]">
          <h3 class="text-2xl font-semibold mb-1 text-green-800"><?= htmlspecialchars($jadwal['nama_kereta']) ?></h3>
          <p class="text-sm text-gray-500 mb-2"><?= htmlspecialchars($jadwal['kelas_kereta']) ?></p>

          <div class="mt-2">
            <h4 class="text-xl font-semibold">Stasiun Awal :</h4>
            <p class="text-sm text-gray-500"><?= htmlspecialchars($jadwal['stasiun_asal']) ?></p>
          </div>

          <div class="mt-2">
            <h4 class="text-xl font-semibold">Stasiun Tujuan :</h4>
            <p class="text-sm text-gray-500"><?= htmlspecialchars($jadwal['stasiun_tujuan']) ?></p>
          </div>

          <div class="mt-2">
            <h4 class="text-xl font-semibold">Pemberangkatan :</h4>
            <p class="text-sm text-gray-500">
              <?= date('l, d M Y H:i', strtotime($jadwal['waktu_berangkat'])) ?>
            </p>
          </div>

          <div class="mt-4">
            <h3 class="text-2xl font-semibold text-green-700">
              Rp.<?= number_format($jadwal['harga'], 0, ',', '.') ?> 
              <sub class="text-gray-400 text-xs">/Tiket</sub>
            </h3>
         <?php if (isset($_SESSION['penumpang'])): ?>
          <a href="beli.php?id_jadwal=<?= $jadwal['id_jadwal'] ?>" 
            class="w-full mt-4 block text-center px-4 py-2 text-sm font-semibold text-white bg-green-700 hover:bg-green-800 rounded-md">
            Beli tiket
          </a>
        <?php else: ?>
          <button onclick="openForm('loginForm')" 
                  class="w-full mt-4 block text-center px-4 py-2 text-sm font-semibold text-white bg-gray-600 hover:bg-gray-700 rounded-md cursor-pointer">
            Harap Login untuk membeli tiket
          </button>
        <?php endif; ?>

          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>


<!-- Modal Login -->
<div id="loginForm" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
  <div class="bg-white rounded-lg p-8 w-full max-w-md relative">
    <button onclick="closeModal('loginForm')" class="absolute top-4 right-4 text-gray-500 hover:text-gray-700">&times;</button>
    <h2 class="text-2xl font-semibold mb-6 text-green-800">Masuk</h2>
    <?php if (!empty($loginError)): ?>
      <p class="mb-4 text-red-600 font-semibold"><?= htmlspecialchars($loginError) ?></p>
    <?php endif; ?>
    <form action="pencarian.php" method="POST" class="space-y-4">
      <input type="text" name="email" placeholder="Email atau Username" required class="w-full border rounded px-4 py-2" />
      <input type="password" name="password" placeholder="Password" required class="w-full border rounded px-4 py-2" />
      <button type="submit" name="login" class="w-full bg-green-800 text-white py-2 rounded hover:bg-green-700">Masuk</button>
    </form>
  </div>
</div>

<!-- Modal Register -->
<div id="registerForm" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
  <div class="bg-white rounded-lg p-8 w-full max-w-md relative overflow-auto max-h-[90vh]">
    <button onclick="closeModal('registerForm')" class="absolute top-4 right-4 text-gray-500 hover:text-gray-700">&times;</button>
    <h2 class="text-2xl font-semibold mb-6 text-green-800">Daftar</h2>
    <form action="pencarian.php" method="POST" class="space-y-4">
      <input type="text" name="nama" placeholder="Nama Lengkap" required class="w-full border rounded px-4 py-2" />
      <input type="email" name="email" placeholder="Email" required class="w-full border rounded px-4 py-2" />
      <input type="password" name="password" placeholder="Password" required class="w-full border rounded px-4 py-2" />
      <input type="text" name="telepon" placeholder="Telepon" required class="w-full border rounded px-4 py-2" />
      <textarea name="alamat" placeholder="Alamat" required class="w-full border rounded px-4 py-2"></textarea>
      <button type="submit" name="register" class="w-full bg-green-800 text-white py-2 rounded hover:bg-green-700">Daftar</button>
    </form>
  </div>
</div>

<script>
  function openForm(id) {
    document.getElementById(id).classList.remove('hidden');
  }
  function closeModal(id) {
    document.getElementById(id).classList.add('hidden');
  }
</script>

</body>
</html>

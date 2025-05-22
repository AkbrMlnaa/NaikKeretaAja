<?php
session_start();
include '../config/config.php';
include '../functions/jadwalFunc.php';
include '../functions/penumpangFunc.php';
include_once '../functions/adminFunc.php';

$jadwalTerlaris = getAllJadwalAktif(3);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (isset($_POST['register'])) {
    $nama = $_POST['nama'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $telepon = $_POST['telepon'];
    $alamat = $_POST['alamat'];
    $result = registerPenumpang($nama, $email, $telepon, $alamat, $password);
    if ($result) {
      echo "
        <script>
          document.addEventListener('DOMContentLoaded', function() {
            showAlert('success', 'Registrasi Berhasil!', 'Login Untuk Bisa Membeli Tiket', 'index.php');
          }); 
        </script>";
    } else {
      echo "  
        <script>
          document.addEventListener('DOMContentLoaded', function() {
            showAlert('error', 'Registrasi Gagal!', 'Email Mungkin Sudah Digunakan', 'index.php');
          }); 
        </script>";
    }
  }

  if (isset($_POST['login'])) {
    $usernameOrEmail = $_POST['email'];
    $password = $_POST['password'];
    $user = loginPenumpang($usernameOrEmail, $password);

    if ($user) {
      $_SESSION['penumpang'] = $user;
      echo "
        <script>
          document.addEventListener('DOMContentLoaded', function() {
            showAlert('success', 'Login Berhasil!', 'Berhasil Login Sebagai {$_SESSION['penumpang']['nama']}.', 'index.php');
          }); 
        </script>";
    } else {
      $admin = loginAdmin($usernameOrEmail, $password);
      if ($admin) {
        $_SESSION['admin'] = $admin;
        echo "
        <script>
          document.addEventListener('DOMContentLoaded', function() {
            showAlert('success', 'Login Berhasil!', 'Berhasil Login Sebagai {$_SESSION['admin']['nama']}.', 'admin/dashboard.php');
          }); 
        </script>";
      } else {
        echo "
        <script>
          document.addEventListener('DOMContentLoaded', function() {
            showAlert('error', 'Login Gagal!', 'Email/Password Mungkin Salah', 'index.php');
          }); 
        </script>";
      }
    }
  }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>NaikKeretaAja</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="../assets/css/style.css">
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

<body class="bg-white font-sans text-gray-800">
  <header class="fixed top-0 left-0 w-full backdrop-blur-md bg-white/70 shadow-md z-50">
    <div class="max-w-7xl mx-auto px-4 py-4 flex justify-between items-center">
      <h1 class="text-2xl font-bold text-green-800">NaikKeretaAja</h1>
      <nav class="space-x-4 text-xl">
        <a href="#" class="nav-link text-green-800 font-semibold">Home</a>
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

  <!-- Modal Login -->
  <div id="loginForm" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6 relative">
      <button onclick="closeModal('loginForm')" class="absolute top-2 right-2 text-gray-500 hover:text-gray-700">❌</button>
      <h2 class="text-xl font-bold mb-4 text-green-700">Masuk</h2>
      <form method="post">
        <input type="text" name="email" placeholder="Email atau Username" class="w-full mb-3 px-4 py-2 border rounded" required />
        <input type="password" name="password" placeholder="Password" class="w-full mb-3 px-4 py-2 border rounded" required />
        <button type="submit" name="login" class="w-full bg-green-700 text-white py-2 rounded hover:bg-green-800">Masuk</button>
      </form>

      <p class="mt-4 text-sm">Belum punya akun?
        <button onclick="switchModal('loginForm', 'registerForm')" class="text-blue-600 hover:underline">Daftar di sini</button>
      </p>
    </div>
  </div>

  <!-- Modal Register -->
  <div id="registerForm" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6 relative">
      <button onclick="closeModal('registerForm')" class="absolute top-2 right-2 text-gray-500 hover:text-gray-700">❌</button>
      <h2 class="text-xl font-bold mb-4 text-green-700">Daftar</h2>
      <form method="post">
        <input type="text" name="nama" placeholder="Nama Lengkap" class="w-full mb-3 px-4 py-2 border rounded" required />
        <input type="email" name="email" placeholder="Email" class="w-full mb-3 px-4 py-2 border rounded" required />
        <input type="number" name="telepon" placeholder="No Telepon" class="w-full mb-3 px-4 py-2 border rounded" required />
        <input type="text" name="alamat" placeholder="Alamat" class="w-full mb-3 px-4 py-2 border rounded" required />
        <input type="password" name="password" placeholder="Password" class="w-full mb-3 px-4 py-2 border rounded" required />
        <button type="submit" name="register" class="w-full bg-green-700 text-white py-2 rounded hover:bg-green-800">Daftar</button>
      </form>
      <p class="mt-4 text-sm">Sudah punya akun?
        <button onclick="switchModal('registerForm', 'loginForm')" class="text-blue-600 hover:underline">Masuk di sini</button>
      </p>
    </div>
  </div>
  <main class="bg-gray-50 pt-16">
    <div class="flex flex-col md:flex-row items-stretch justify-center w-full h-[90vh]">
      <div class="w-full md:w-1/2 px-6 md:px-12 bg-gray-100 py-8 flex flex-col justify-between h-full">
        <h2 class="text-xl text-green-700 font-semibold">Home</h2>
        <h1 class="text-3xl font-bold mt-2 mb-4">Selamat Datang di Web NaikKeretaAja</h1>
        <p class="mb-4 text-gray-700 text-justify">
          <strong>NaikKeretaAja</strong> adalah platform pemesanan tiket kereta yang dirancang untuk memudahkan Anda merencanakan perjalanan secara cepat, nyaman, dan efisien. Lewat satu platform terintegrasi, Anda dapat mencari dan memesan tiket kereta, melihat jadwal keberangkatan secara real-time, memilih rute terbaik, serta mendapatkan harga tiket yang kompetitif.
          <br><br>
          Dengan antarmuka yang ramah pengguna dan fitur pencarian cerdas, NaikKeretaAja cocok digunakan untuk berbagai keperluan—baik perjalanan bisnis, mudik, maupun liburan bersama keluarga. Kami juga secara berkala menyediakan promo menarik yang membuat perjalanan Anda semakin hemat.
          <br><br>
          Rasakan kemudahan memesan tiket kapan saja dan di mana saja, tanpa antre, tanpa repot. Mulailah petualangan Anda sekarang juga bersama <strong>NaikKeretaAja</strong>—teman setia perjalanan kereta Anda.
        </p>
        <a href="pencarian.php" class="bg-green-800 text-white px-4 py-3 rounded hover:bg-green-700 w-44">Pesan tiket sekarang</a>
      </div>
      <div class="w-full md:w-1/2 flex items-stretch">
        <img src="../assets/images/keretaHome.jpg" alt="Keluarga di pesawat" class="w-full h-full object-cover rounded-sm" />
      </div>
    </div>
  </main>

  <section class="bg-white py-16">
    <div class="max-w-7xl mx-auto px-4">
      <h1 class="text-3xl font-bold text-center mb-12 text-green-800">Daftar Tiket Terlaris</h1>
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
        <?php foreach ($jadwalTerlaris as $jadwal): ?>
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
              <?php elseif (isset($_SESSION['admin'])): ?>
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


  <footer class="bg-gray-100 text-center text-sm text-gray-600 py-6 mt-20 border-t">
    <div class="max-w-4xl mx-auto px-4">
      <p>Kami menggunakan cookie untuk memastikan bahwa situs web kami bekerja dengan baik dan memberikan pengalaman terbaik. Lihat
        <a href="#" class="text-blue-600 hover:underline">Kebijakan Cookie</a> kami.
      </p>
      <p class="mt-2">© 2025 NaikKeretaAja. Dibuat oleh <strong>Akbar Maulana Husada</strong>.</p>
    </div>
  </footer>
  <script src="../assets/js/functions.js"></script>
</body>

</html>
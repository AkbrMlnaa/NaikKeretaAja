<?php
session_start();

$berhasil = isset($_GET['success']) && $_GET['success'] == 1;
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Konfirmasi Pemesanan</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 pt-10 font-sans">

<div class="max-w-md mx-auto bg-white rounded-lg shadow-md p-6 text-center">
    <?php if ($berhasil): ?>
        <svg class="mx-auto w-16 h-16 text-green-600 mb-4" fill="none" stroke="currentColor" stroke-width="2"
             viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round"
                  d="M5 13l4 4L19 7"></path>
        </svg>
        <h1 class="text-2xl font-bold text-green-700 mb-2">Pemesanan Berhasil!</h1>
        <p class="text-gray-700 mb-4">Terima kasih telah memesan tiket. Detail tiket akan segera dikirimkan ke akun Anda.</p>
        <a href="pencarian.php" class="inline-block bg-green-700 hover:bg-green-600 text-white px-4 py-2 rounded transition">
            Kembali ke Beranda
        </a>
    <?php else: ?>
        <h1 class="text-2xl font-bold text-red-600 mb-2">Terjadi Kesalahan</h1>
        <p class="text-gray-700 mb-4">Pemesanan tidak berhasil atau tidak valid.</p>
        <a href="pencarian.php" class="inline-block bg-red-600 hover:bg-red-500 text-white px-4 py-2 rounded transition">
            Coba Lagi
        </a>
    <?php endif; ?>
</div>

</body>
</html>

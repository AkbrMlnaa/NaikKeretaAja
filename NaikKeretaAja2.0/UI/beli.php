<?php
session_start();
require_once '../config/config.php';
require_once '../functions/jadwalFunc.php';
require_once '../functions/pemesananFunc.php';

if (!isset($_GET['id_jadwal']) || empty($_GET['id_jadwal'])) {
    header('Location: index.php');
    exit;
}

$id = $_GET['id_jadwal'];
$jadwal = getAllJadwalById($id);

if (!$jadwal) {
    echo "<h2 class='text-center text-red-600 text-xl font-semibold mt-10'>Jadwal tidak ditemukan.</h2>";
    echo '<p class="text-center mt-2"><a href="index.php" class="text-green-700 hover:underline">Kembali ke pencarian</a></p>';
    exit;
}

$pesan = '';
$jumlah_tiket = 1;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_pembeli = trim($_POST['nama_pembeli'] ?? '');
    $jumlah_tiket = intval($_POST['jumlah_tiket'] ?? 0);

    if ($nama_pembeli === '' || $jumlah_tiket <= 0) {
        $pesan = 'Mohon isi nama dan jumlah tiket dengan benar.';
    } else {
        $total_harga = $jumlah_tiket * $jadwal['harga'];
        $id_penumpang = $_SESSION['penumpang']['id_penumpang']; // pastikan ini sudah login

        if (addPemesanan($id_penumpang, $id, $jumlah_tiket, $total_harga)) {
            header("Location: konfirmasi.php?success=1");
            exit;
        } else {
            $pesan = 'Gagal melakukan pemesanan. Silakan coba lagi.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Beli Tiket - <?= htmlspecialchars($jadwal['nama_kereta']) ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50 font-sans text-gray-800 pt-10">

    <div class="max-w-xl mx-auto bg-white p-8 rounded-xl shadow-md">
        <h1 class="text-3xl font-bold mb-6 text-green-800">Pembelian Tiket</h1>

        <?php if ($pesan): ?>
            <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
                <?= htmlspecialchars($pesan) ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="space-y-4">
            <div>
                <label for="nama_pembeli" class="block font-medium mb-1">Nama Pembeli</label>
                <input type="text" id="nama_pembeli" name="nama_pembeli"
                    value="<?= htmlspecialchars($_SESSION['penumpang']['nama'] ?? '') ?>"
                    readonly
                    class="w-full bg-gray-100 border border-gray-300 rounded px-3 py-2 focus:outline-none" />
            </div>

            <div>
                <label for="jumlah_tiket" class="block font-medium mb-1">Jumlah Tiket</label>
                <input type="number" id="jumlah_tiket" name="jumlah_tiket" min="1"
                    value="<?= htmlspecialchars($jumlah_tiket) ?>"
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-700" />
            </div>

            <div>
                <label class="block font-medium mb-1">Nama Kereta</label>
                <input type="text" value="<?= htmlspecialchars($jadwal['nama_kereta']) ?>" readonly
                    class="w-full bg-gray-100 border border-gray-300 rounded px-3 py-2" />
            </div>

            <div>
                <label class="block font-medium mb-1">Kelas</label>
                <input type="text" value="<?= htmlspecialchars($jadwal['kelas_kereta']) ?>" readonly
                    class="w-full bg-gray-100 border border-gray-300 rounded px-3 py-2" />
            </div>

            <div>
                <label class="block font-medium mb-1">Rute</label>
                <input type="text"
                    value="<?= htmlspecialchars($jadwal['stasiun_asal']) ?>, <?= htmlspecialchars($jadwal['lokasi_asal']) ?> → <?= htmlspecialchars($jadwal['stasiun_tujuan']) ?>, <?= htmlspecialchars($jadwal['lokasi_tujuan']) ?>"
                    readonly
                    class="w-full bg-gray-100 border border-gray-300 rounded px-3 py-2" />
            </div>

            <div>
                <label class="block font-medium mb-1">Keberangkatan</label>
                <input type="text"
                    value="<?= date('l, d F Y H:i', strtotime($jadwal['waktu_berangkat'])) ?>"
                    readonly
                    class="w-full bg-gray-100 border border-gray-300 rounded px-3 py-2" />
            </div>

            <div>
                <label class="block font-medium mb-1">Harga per Tiket</label>
                <input type="text" value="Rp.<?= number_format($jadwal['harga'], 0, ',', '.') ?>"
                    readonly
                    class="w-full bg-gray-100 border border-gray-300 rounded px-3 py-2" />
            </div>

            <div class="flex justify-between items-center">
                <a href="pencarian.php" class="text-green-700 hover:underline">← Kembali</a>
                <button type="submit" class="bg-green-800 text-white px-6 py-2 rounded hover:bg-green-700 transition">Beli Tiket</button>
            </div>
        </form>
    </div>

</body>

</html>
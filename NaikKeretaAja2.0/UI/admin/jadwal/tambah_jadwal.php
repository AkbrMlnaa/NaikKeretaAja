<?php
require_once '../../../functions/jadwalFunc.php';
require_once '../../../config/config.php';

$kereta = mysqli_query($conn, "SELECT id_kereta, nama_kereta FROM kereta");
$stasiun = mysqli_query($conn, "SELECT id_stasiun, nama_stasiun FROM stasiun");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $hasil = addJadwal($_POST['id_kereta'], $_POST['id_stasiun_asal'], $_POST['id_stasiun_tujuan'], $_POST['waktu_berangkat'], $_POST['harga']);
    if ($hasil) {
        header("Location: jadwal.php?msg=added");
        exit;
    } else {
        $error = "Gagal menambahkan jadwal.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <title>Tambah Jadwal Kereta</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center px-4">

    <div class="bg-white shadow-xl rounded-xl p-8 w-full max-w-md">
        <h2 class="text-2xl font-bold mb-6 text-center text-gray-800">Tambah Jadwal Kereta</h2>

        <?php if (isset($error)): ?>
            <div class="mb-4 p-3 bg-red-100 text-red-700 rounded">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="" class="space-y-4">

            <div>
                <label for="id_kereta" class="block text-gray-700 mb-1">Pilih Kereta</label>
                <select name="id_kereta" id="id_kereta" required
                        class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-400">
                    <option value="" disabled selected>Pilih Kereta</option>
                    <?php while($row = mysqli_fetch_assoc($kereta)): ?>
                        <option value="<?= $row['id_kereta'] ?>"><?= htmlspecialchars($row['nama_kereta']) ?></option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div>
                <label for="id_stasiun_asal" class="block text-gray-700 mb-1">Pilih Stasiun Asal</label>
                <select name="id_stasiun_asal" id="id_stasiun_asal" required
                        class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-400">
                    <option value="" disabled selected>Pilih Stasiun Asal</option>
                    <?php mysqli_data_seek($stasiun, 0); while($row = mysqli_fetch_assoc($stasiun)): ?>
                        <option value="<?= $row['id_stasiun'] ?>"><?= htmlspecialchars($row['nama_stasiun']) ?></option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div>
                <label for="id_stasiun_tujuan" class="block text-gray-700 mb-1">Pilih Stasiun Tujuan</label>
                <select name="id_stasiun_tujuan" id="id_stasiun_tujuan" required
                        class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-400">
                    <option value="" disabled selected>Pilih Stasiun Tujuan</option>
                    <?php mysqli_data_seek($stasiun, 0); while($row = mysqli_fetch_assoc($stasiun)): ?>
                        <option value="<?= $row['id_stasiun'] ?>"><?= htmlspecialchars($row['nama_stasiun']) ?></option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div>
                <label for="waktu_berangkat" class="block text-gray-700 mb-1">Waktu Berangkat</label>
                <input type="datetime-local" name="waktu_berangkat" id="waktu_berangkat" required
                       class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-400" />
            </div>

            <div>
                <label for="harga" class="block text-gray-700 mb-1">Harga</label>
                <input type="number" name="harga" id="harga" min="0" required
                       class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-400" />
            </div>

            <div class="flex justify-between items-center mt-6">
                <a href="jadwal.php" class="text-sm text-green-600 hover:underline">&larr; Kembali</a>
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition" name="submit">Simpan</button>
            </div>

        </form>
    </div>

</body>
</html>

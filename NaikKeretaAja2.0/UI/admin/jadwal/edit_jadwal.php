<?php
require_once '../../../functions/jadwalFunc.php';
require_once '../../../config/config.php';

// Validasi ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die('ID jadwal tidak valid.');
}

$id = (int) $_GET['id'];
$jadwal = getJadwalById($id);

if (!$jadwal) {
    die('Data jadwal tidak ditemukan.');
}

// Ambil data dropdown (kereta dan stasiun)
$kereta = mysqli_query($conn, "SELECT id_kereta, nama_kereta FROM kereta");
$stasiun = mysqli_query($conn, "SELECT id_stasiun, nama_stasiun FROM stasiun");

// Proses update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_kereta = $_POST['id_kereta'];
    $id_stasiun_asal = $_POST['id_stasiun_asal'];
    $id_stasiun_tujuan = $_POST['id_stasiun_tujuan'];
    $waktu_berangkat = $_POST['waktu_berangkat'];
    $harga = $_POST['harga'];

    $hasil = updateJadwal($id, $id_kereta, $id_stasiun_asal, $id_stasiun_tujuan, $waktu_berangkat, $harga);
    if ($hasil) {
        header("Location: jadwal.php");
        exit;
    } else {
        $error = "Gagal memperbarui jadwal.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Edit Jadwal</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen py-10 px-4">
  <div class="max-w-xl mx-auto bg-white p-8 rounded shadow-md">
    <h2 class="text-2xl font-bold text-green-800 mb-6 text-center">Edit Jadwal Kereta</h2>

    <?php if (isset($error)): ?>
      <div class="bg-red-100 text-red-700 px-4 py-2 rounded mb-4"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST" class="space-y-4">
      <div>
        <label class="block font-medium">Kereta</label>
        <select name="id_kereta" class="w-full border px-3 py-2 rounded" required>
          <option disabled selected>-- Pilih Kereta --</option>
          <?php while ($row = mysqli_fetch_assoc($kereta)): ?>
            <option value="<?= $row['id_kereta'] ?>" <?= $jadwal['id_kereta'] == $row['id_kereta'] ? 'selected' : '' ?>>
              <?= htmlspecialchars($row['nama_kereta']) ?>
            </option>
          <?php endwhile; ?>
        </select>
      </div>

      <div>
        <label class="block font-medium">Stasiun Asal</label>
        <select name="id_stasiun_asal" class="w-full border px-3 py-2 rounded" required>
          <option disabled selected>-- Pilih Stasiun Asal --</option>
          <?php mysqli_data_seek($stasiun, 0); while ($row = mysqli_fetch_assoc($stasiun)): ?>
            <option value="<?= $row['id_stasiun'] ?>" <?= $jadwal['id_stasiun_asal'] == $row['id_stasiun'] ? 'selected' : '' ?>>
              <?= htmlspecialchars($row['nama_stasiun']) ?>
            </option>
          <?php endwhile; ?>
        </select>
      </div>

      <div>
        <label class="block font-medium">Stasiun Tujuan</label>
        <select name="id_stasiun_tujuan" class="w-full border px-3 py-2 rounded" required>
          <option disabled selected>-- Pilih Stasiun Tujuan --</option>
          <?php mysqli_data_seek($stasiun, 0); while ($row = mysqli_fetch_assoc($stasiun)): ?>
            <option value="<?= $row['id_stasiun'] ?>" <?= $jadwal['id_stasiun_tujuan'] == $row['id_stasiun'] ? 'selected' : '' ?>>
              <?= htmlspecialchars($row['nama_stasiun']) ?>
            </option>
          <?php endwhile; ?>
        </select>
      </div>

      <div>
        <label class="block font-medium">Waktu Berangkat</label>
        <input type="datetime-local" name="waktu_berangkat" value="<?= date('Y-m-d\TH:i', strtotime($jadwal['waktu_berangkat'])) ?>" class="w-full border px-3 py-2 rounded" required>
      </div>

      <div>
        <label class="block font-medium">Harga</label>
        <input type="number" name="harga" value="<?= htmlspecialchars($jadwal['harga']) ?>" class="w-full border px-3 py-2 rounded" required>
      </div>

      <div class="flex justify-between mt-6">
        <a href="jadwal.php" class="px-4 py-2 bg-gray-300 text-gray-800 rounded hover:bg-gray-400">Batal</a>
        <button type="submit" class="px-4 py-2 bg-green-700 text-white rounded hover:bg-green-800">Simpan Perubahan</button>
      </div>
    </form>
  </div>
</body>
</html>

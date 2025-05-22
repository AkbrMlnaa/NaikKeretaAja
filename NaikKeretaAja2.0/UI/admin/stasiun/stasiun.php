<?php
require_once '../../../functions/stasiunFunc.php';
session_start();
if (!isset($_SESSION['admin'])) {
  header('Location: ../../index.php');
  exit();
}
$stasiuns = getAllStasiun();
?>
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>NaikKeretaAja - Data Stasiun</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="../assets/css/style.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
    function confirmDelete(id) {
      Swal.fire({
        title: 'Yakin ingin menghapus?',
        text: "Data Staiun akan dihapus secara permanen!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Hapus',
        cancelButtonText: 'Batal'
      }).then((result) => {
        if (result.isConfirmed) {
          window.location.href = `hapus_stasiun.php?id=${id}`;
        }
      });
    }
  </script>
</head>

<body class="bg-gray-50 text-gray-800 font-sans">
  <section class="bg-white pt-10 pb-16 min-h-screen">
    <div class="max-w-7xl mx-auto px-4">
      <div class="mb-4">
        <a href="../dashboard.php" class="bg-gray-200 text-gray-800 px-4 py-2 rounded hover:bg-gray-300 text-sm inline-block">
          ← Kembali
        </a>
      </div>
      <h2 class="text-3xl font-bold text-green-800 mb-8 text-center">Manajemen Data Stasiun</h2>
      <div class="mb-6 text-right">
        <a href="tambah_stasiun.php" class="bg-green-700 text-white px-4 py-2 rounded hover:bg-green-800 text-sm">
          Tambah Stasiun
        </a>
      </div>
      <div class="overflow-x-auto bg-gray-50 p-4 rounded-md shadow-md">
        <table class="w-full text-center rounded-lg text-sm overflow-hidden">
          <thead class="border-b-2 bg-green-700 border-green-700 text-white">
            <tr>
              <th class="px-4 py-2">Nama Stasiun</th>
              <th class="px-4 py-2">Lokasi</th>
              <th class="px-4 py-2">Aksi</th>
            </tr>
          </thead>
          <tbody class="text-gray-700">
            <?php if (empty($stasiuns)): ?>
              <tr>
                <td colspan="3" class="px-4 py-2 text-center">Tidak ada data stasiun</td>
              </tr>
            <?php else: ?>
              <?php foreach ($stasiuns as $s): ?>
                <tr class="border-t">
                  <td class="px-4 py-4"><?= htmlspecialchars($s['nama_stasiun']) ?></td>
                  <td class="px-4 py-4"><?= htmlspecialchars($s['lokasi']) ?></td>
                  <td class="px-4 py-4 space-x-2">
                    <a href="edit_stasiun.php?id=<?= $s['id_stasiun'] ?>" class="bg-yellow-500 hover:bg-yellow-700 text-white px-3 py-1 rounded">Edit</a>
                    <a href="javascript:void(0);" onclick="confirmDelete(<?= $s['id_stasiun'] ?>)" class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded">Hapus</a>
                  </td>
                </tr>
              <?php endforeach ?>
            <?php endif ?>
          </tbody>
        </table>
      </div>
    </div>
  </section>

</body>

</html>
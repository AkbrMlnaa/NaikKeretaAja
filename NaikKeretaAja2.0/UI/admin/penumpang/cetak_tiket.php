

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $id = $_GET['id_pemesanan'] ?? null;
    if (!$id) {
        die("ID Pemesanan tidak ditemukan. Pastikan URL menggunakan parameter id_pemesanan");
    }

    $stmt = $pdo->prepare("SELECT * FROM view_data_pemesanan WHERE id_pemesanan = ?");
    $stmt->execute([$id]);
    $data = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$data) {
        die("ID Pemesanan tidak ditemukan.");
    }
    
} catch (PDOException $e) {
    die("Error koneksi atau query: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <title>Cetak Tiket - NaikKeretaAja</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f9f9f9;
            padding: 40px 20px;
        }
        .ticket {
            max-width: 600px;
            margin: auto;
            background: white;
            border: 2px dashed #4CAF50;
            padding: 30px 40px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            position: relative;
        }
        h2 {
            text-align: center;
            color: #2E7D32;
            margin-bottom: 30px;
            letter-spacing: 1.2px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        td {
            padding: 12px 8px;
            vertical-align: top;
            border-bottom: 1px solid #eee;
        }
        .label {
            font-weight: 600;
            color: #555;
            width: 40%;
        }
        .value {
            color: #111;
        }
        .footer {
            text-align: center;
            font-size: 14px;
            color: #777;
            margin-top: 40px;
            font-style: italic;
        }
        button.print-btn {
            display: block;
            margin: 40px auto 0;
            background: #4CAF50;
            color: white;
            border: none;
            padding: 12px 25px;
            font-size: 16px;
            border-radius: 25px;
            cursor: pointer;
            transition: background 0.3s ease;
        }
        button.print-btn:hover {
            background: #388E3C;
        }
        .important-info {
            background: #E8F5E9;
            border-left: 6px solid #4CAF50;
            padding: 15px 20px;
            margin-top: 25px;
            font-weight: 600;
            color: #2E7D32;
            font-size: 1.1em;
            border-radius: 5px;
        }
        .barcode {
            text-align: center;
            margin-top: 30px;
        }
        .barcode img {
            width: 180px;
            height: auto;
        }
        @media print {
            button.print-btn {
                display: none;
            }
            body {
                background: white;
                padding: 0;
            }
            .ticket {
                border-style: solid;
                box-shadow: none;
                max-width: 100%;
                padding: 20px;
                border-radius: 0;
            }
        }
    </style>
</head>
<body>
    <div class="ticket">
        <h2>Tiket Kereta</h2>
        <table>
            <tr>
                <td class="label">Nama Penumpang</td>
                <td><?= htmlspecialchars($data['nama_penumpang']) ?></td>
            </tr>
            <tr>
                <td class="label">Kereta</td>
                <td><?= htmlspecialchars($data['nama_kereta']) ?></td>
            </tr>
            <tr>
                <td class="label">Asal</td>
                <td><?= htmlspecialchars($data['stasiun_asal']) ?></td>
            </tr>
            <tr>
                <td class="label">Tujuan</td>
                <td><?= htmlspecialchars($data['stasiun_tujuan']) ?></td>
            </tr>
            <tr>
                <td class="label">Tanggal Berangkat</td>
                <td><?= date('d-m-Y', strtotime($data['waktu_berangkat'])) ?></td>
            </tr>
            <tr>
                <td class="label">Jam Berangkat</td>
                <td><?= date('H:i', strtotime($data['waktu_berangkat'])) ?></td>
            </tr>
            <tr>
                <td class="label">Jumlah Tiket</td>
                <td><?= htmlspecialchars($data['jumlah_tiket']) ?></td>
            </tr>
            <tr>
                <td class="label">Harga</td>
                <td>Rp <?= number_format($data['total_harga'], 0, ',', '.') ?></td>
            </tr>
        </table>

        <div class="important-info">
            Pastikan hadir di stasiun minimal <strong>30 menit sebelum keberangkatan</strong>.<br>
            Bawa tiket ini dan kartu identitas saat boarding.
        </div>
    </div>
    <div class="footer">
        Terima kasih telah menggunakan layanan <strong>NaikKeretaAja</strong> 🚆
    </div>
    <button class="print-btn" onclick="window.print()">🖨️ Cetak Tiket</button>
</body>
</html>


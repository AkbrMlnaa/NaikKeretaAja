CREATE DATABASE NaikKeretaAja;
USE NaikKeretaAja;
DROP DATABASE NaikKeretaAja;
CREATE TABLE kereta (
    id_kereta INT PRIMARY KEY AUTO_INCREMENT,
    nama_kereta VARCHAR(100) NOT NULL,
    kelas_kereta ENUM('Eksekutif', 'Bisnis', 'Ekonomi') NOT NULL,
    kapasitas INT NOT NULL
);

CREATE TABLE stasiun (
    id_stasiun INT PRIMARY KEY AUTO_INCREMENT,
    nama_stasiun VARCHAR(100) NOT NULL,
    lokasi VARCHAR(255) NOT NULL
);

CREATE TABLE penumpang (
    id_penumpang INT PRIMARY KEY AUTO_INCREMENT,
    nama VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    PASSWORD VARCHAR(100) NOT NULL,
    no_telepon VARCHAR(15) NOT NULL,
    alamat TEXT,
    role ENUM('penumpang') DEFAULT 'penumpang'
);

CREATE TABLE jadwal (
    id_jadwal INT PRIMARY KEY AUTO_INCREMENT,
    id_kereta INT NOT NULL,
    id_stasiun_asal INT NOT NULL,
    id_stasiun_tujuan INT NOT NULL,
    waktu_berangkat DATETIME NOT NULL,
    harga INT NOT NULL,
    FOREIGN KEY (id_kereta) REFERENCES kereta(id_kereta),
    FOREIGN KEY (id_stasiun_asal) REFERENCES stasiun(id_stasiun),
    FOREIGN KEY (id_stasiun_tujuan) REFERENCES stasiun(id_stasiun)
);

CREATE TABLE pemesanan (
    id_pemesanan INT PRIMARY KEY AUTO_INCREMENT,
    id_penumpang INT NOT NULL,
    id_jadwal INT NOT NULL,
    jumlah_tiket INT NOT NULL,
    total_harga INT NOT NULL,
    tanggal_pemesanan DATETIME NOT NULL,
    FOREIGN KEY (id_penumpang) REFERENCES penumpang(id_penumpang),
    FOREIGN KEY (id_jadwal) REFERENCES jadwal(id_jadwal)
);

CREATE TABLE ADMIN (
    id_admin INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL UNIQUE,
    nama VARCHAR(100) NOT NULL,
    PASSWORD VARCHAR(255) NOT NULL,
    role ENUM('admin') DEFAULT 'admin'
);


DAFTAR VIEW

CREATE VIEW view_data_penumpang AS
SELECT 
    id_penumpang,
    nama,
    email,
    no_telepon,
    PASSWORD,
    alamat,
    ROLE
FROM penumpang;
SELECT * FROM view_data_penumpang;
DROP VIEW view_data_penumpang;


CREATE VIEW view_data_kereta AS
SELECT 
    id_kereta,
    nama_kereta,
    kelas_kereta,
    kapasitas
FROM kereta;
SELECT * FROM view_data_kereta

CREATE VIEW view_data_stasiun AS
SELECT 
    id_stasiun,
    nama_stasiun,
    lokasi
FROM stasiun;

CREATE VIEW view_jadwal_kereta AS
SELECT 
    j.id_jadwal,
    k.nama_kereta,
    k.kelas_kereta,
    s_asal.nama_stasiun AS stasiun_asal,
    s_tujuan.nama_stasiun AS stasiun_tujuan,
    j.waktu_berangkat,
    j.harga,
    k.kapasitas,
    IF(SUM(p.jumlah_tiket) IS NULL, k.kapasitas, k.kapasitas - SUM(p.jumlah_tiket)) AS sisa_kursi
FROM jadwal j
JOIN kereta k ON j.id_kereta = k.id_kereta
JOIN stasiun s_asal ON j.id_stasiun_asal = s_asal.id_stasiun
JOIN stasiun s_tujuan ON j.id_stasiun_tujuan = s_tujuan.id_stasiun
LEFT JOIN pemesanan p ON j.id_jadwal = p.id_jadwal
GROUP BY j.id_jadwal;


CREATE VIEW view_jadwal_kereta_aktif AS
SELECT 
    j.id_jadwal,
    k.nama_kereta,
    k.kelas_kereta,
    s_asal.nama_stasiun AS stasiun_asal,
    s_tujuan.nama_stasiun AS stasiun_tujuan,
    j.waktu_berangkat,
    j.harga,
    k.kapasitas,
    IF(SUM(p.jumlah_tiket) IS NULL, k.kapasitas, k.kapasitas - SUM(p.jumlah_tiket)) AS sisa_kursi
FROM jadwal j
JOIN kereta k ON j.id_kereta = k.id_kereta
JOIN stasiun s_asal ON j.id_stasiun_asal = s_asal.id_stasiun
JOIN stasiun s_tujuan ON j.id_stasiun_tujuan = s_tujuan.id_stasiun
LEFT JOIN pemesanan p ON j.id_jadwal = p.id_jadwal
WHERE j.waktu_berangkat > NOW() + INTERVAL 30 MINUTE
GROUP BY j.id_jadwal
HAVING sisa_kursi > 0;
DROP VIEW view_jadwal_kereta;
SELECT * FROM view_jadwal_kereta;


CREATE VIEW view_data_pemesanan AS
SELECT 
    p.id_pemesanan,
    pn.nama AS nama_penumpang,
    k.nama_kereta,
    s_asal.nama_stasiun AS stasiun_asal,
    s_tujuan.nama_stasiun AS stasiun_tujuan,
    j.waktu_berangkat,
    p.jumlah_tiket,
    p.total_harga,
    p.tanggal_pemesanan
FROM pemesanan p
JOIN penumpang pn ON p.id_penumpang = pn.id_penumpang
JOIN jadwal j ON p.id_jadwal = j.id_jadwal
JOIN kereta k ON j.id_kereta = k.id_kereta
JOIN stasiun s_asal ON j.id_stasiun_asal = s_asal.id_stasiun
JOIN stasiun s_tujuan ON j.id_stasiun_tujuan = s_tujuan.id_stasiun;
SELECT * FROM view_data_pemesanan;




DELIMITER //
CREATE PROCEDURE tambah_penumpang(
    IN p_nama VARCHAR(100),
    IN p_email VARCHAR(100),
    IN p_password VARCHAR(100),
    IN p_no_telepon VARCHAR(15),
    IN p_alamat TEXT,
    IN p_role ENUM('admin', 'penumpang')
)
BEGIN
    INSERT INTO penumpang (nama, email, PASSWORD, no_telepon, alamat, ROLE)
    VALUES (p_nama, p_email, p_password, p_no_telepon, p_alamat, p_role);
END //
DELIMITER ;
CALL tambah_penumpang(
    'fiki',
    'fiki@example.com',
    'fiki123',
    '08123456789',
    'Sampang, Madura',
    'penumpang'
);

DELIMITER $$
CREATE PROCEDURE tambahKeretaLoop(
    IN p_nama_kereta VARCHAR(100),
    IN p_kelas_kereta ENUM('Eksekutif', 'Bisnis', 'Ekonomi'),
    IN p_kapasitas INT
)
BEGIN
    DECLARE counter INT DEFAULT 1;
    DECLARE max_loop INT DEFAULT 1; -- Loop cuma sekali

    loop_label: LOOP
        IF counter > max_loop THEN
            LEAVE loop_label;
        END IF;

        -- Insert kereta
        INSERT INTO kereta (nama_kereta, kelas_kereta, kapasitas)
        VALUES (p_nama_kereta, p_kelas_kereta, p_kapasitas);

        SET counter = counter + 1;
    END LOOP loop_label;
END $$
DELIMITER ;

DELIMITER //
CREATE PROCEDURE tambah_stasiun(
    IN p_nama_stasiun VARCHAR(100),
    IN p_lokasi VARCHAR(100)
)
BEGIN
    INSERT INTO kereta (nama_stasiun, lokasi)
    VALUES (p_nama_stasiun, p_lokasi);
END //
DELIMITER ;

DELIMITER //

CREATE PROCEDURE tambah_pemesanan (
    IN p_id_penumpang INT,
    IN p_id_jadwal INT,
    IN p_jumlah_tiket INT,
    IN p_total_harga INT
)
BEGIN
    INSERT INTO pemesanan (
        id_penumpang,
        id_jadwal,
        jumlah_tiket,
        total_harga,
        tanggal_pemesanan
    ) VALUES (
        p_id_penumpang,
        p_id_jadwal,
        p_jumlah_tiket,
        p_total_harga, 
        NOW()
    );
END //
DELIMITER ;


DELIMITER $$
CREATE PROCEDURE hapus_kereta(IN p_id_kereta INT)
BEGIN
    DELETE FROM kereta WHERE id_kereta = p_id_kereta;
END $$
DELIMITER ;



TRIGGER
CREATE TABLE laporan_bulanan (
    id_laporan INT PRIMARY KEY AUTO_INCREMENT,
    bulan YEAR,
    bulan_angka INT,
    tahun INT,
    total_tiket INT DEFAULT 0,
    total_pendapatan INT DEFAULT 0
);


DELIMITER $$
CREATE TRIGGER after_pemesanan_insert
AFTER INSERT ON pemesanan
FOR EACH ROW
BEGIN
    DECLARE v_bulan INT;
    DECLARE v_tahun INT;

    SET v_bulan = MONTH(NEW.tanggal_pemesanan);
    SET v_tahun = YEAR(NEW.tanggal_pemesanan);

 
    IF EXISTS (
        SELECT 1 FROM laporan_bulanan
        WHERE bulan_angka = v_bulan AND tahun = v_tahun
    ) THEN

        UPDATE laporan_bulanan
        SET total_tiket = total_tiket + NEW.jumlah_tiket,
            total_pendapatan = total_pendapatan + NEW.total_harga
        WHERE bulan_angka = v_bulan AND tahun = v_tahun;
    ELSE
   
        INSERT INTO laporan_bulanan (bulan, bulan_angka, tahun, total_tiket, total_pendapatan)
        VALUES (NOW(), v_bulan, v_tahun, NEW.jumlah_tiket, NEW.total_harga);
    END IF;
END $$

DELIMITER ;

DELIMITER $$

CREATE TRIGGER after_pemesanan_update
AFTER UPDATE ON pemesanan
FOR EACH ROW
BEGIN
    DECLARE v_bulan INT;
    DECLARE v_tahun INT;

    SET v_bulan = MONTH(NEW.tanggal_pemesanan);
    SET v_tahun = YEAR(NEW.tanggal_pemesanan);

    -- Update laporan: kurangi nilai lama, tambahkan nilai baru
    UPDATE laporan_bulanan
    SET total_tiket = total_tiket - OLD.jumlah_tiket + NEW.jumlah_tiket,
        total_pendapatan = total_pendapatan - OLD.total_harga + NEW.total_harga
    WHERE bulan_angka = v_bulan AND tahun = v_tahun;
END $$

DELIMITER ;

DELIMITER $$

CREATE TRIGGER after_pemesanan_delete
AFTER DELETE ON pemesanan
FOR EACH ROW
BEGIN
    DECLARE v_bulan INT;
    DECLARE v_tahun INT;

    SET v_bulan = MONTH(OLD.tanggal_pemesanan);
    SET v_tahun = YEAR(OLD.tanggal_pemesanan);

    -- Kurangi jumlah tiket & pendapatan saat data dihapus
    UPDATE laporan_bulanan
    SET total_tiket = total_tiket - OLD.jumlah_tiket,
        total_pendapatan = total_pendapatan - OLD.total_harga
    WHERE bulan_angka = v_bulan AND tahun = v_tahun;
END $$

DELIMITER ;

SELECT * FROM laporan_bulanan WHERE bulan_angka = MONTH(NOW()) AND tahun = YEAR(NOW());










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


-- Tambah data ke tabel kereta--
INSERT INTO kereta (nama_kereta, kelas_kereta, kapasitas) VALUES
('Argo Bromo Anggrek', 'Eksekutif', 100),
('Gaya Baru Malam', 'Ekonomi', 150),
('Senja Utama', 'Bisnis', 120);

-- Tambah data ke tabel stasiun
INSERT INTO stasiun (nama_stasiun, lokasi) VALUES
('Stasiun Surabaya Gubeng', 'Surabaya'),
('Stasiun Pasar Senen', 'Jakarta'),
('Stasiun Yogyakarta', 'Yogyakarta');

-- Tambah data ke tabel penumpang
INSERT INTO penumpang (nama, email, PASSWORD, no_telepon, alamat, ROLE) VALUES
('Rina Wijaya', 'rina@gmail.com', '123456', '081234567890', 'Jl. Mawar No. 5', 'penumpang'),
('Budi Santoso', 'budi@gmail.com', 'budi123', '082112345678', 'Jl. Melati No. 8', 'penumpang');

-- Tambah data ke tabel jadwal
INSERT INTO jadwal (id_kereta, id_stasiun_asal, id_stasiun_tujuan, waktu_berangkat, harga) VALUES
(1, 1, 2, '2025-06-01 08:00:00', 300000),
(2, 2, 3, '2025-06-02 20:00:00', 150000),
(3, 3, 1, '2025-06-03 06:30:00', 200000);

-- Tambah data ke tabel pemesanan
INSERT INTO pemesanan (id_penumpang, id_jadwal, jumlah_tiket, total_harga, tanggal_pemesanan) VALUES
(1, 1, 2, 600000, '2025-05-15 10:00:00'),
(2, 2, 1, 150000, '2025-05-14 09:00:00');

-- Tambah data ke tabel ADMIN
INSERT INTO ADMIN (username, nama, PASSWORD, ROLE) VALUES
('admin1', 'Siti Aminah', 'adminpass123', 'admin');

--view menampilkan DATA penumpang--
CREATE VIEW view_data_penumpang AS
SELECT 
    id_penumpang,
    nama,
    email,
    no_telepon,
    alamat,
    ROLE
FROM penumpang;
SELECT * FROM view_data_penumpang;

----menampilkan DATA kereta---
CREATE VIEW view_data_kereta AS
SELECT 
    id_kereta,
    nama_kereta,
    kelas_kereta,
    kapasitas
FROM kereta;
SELECT * FROM view_data_kereta

----menampilkan jadwal kereta JOIN kereta dan stasiun--
CREATE VIEW view_jadwal_kereta AS
SELECT 
    j.id_jadwal,
    k.nama_kereta,
    k.kelas_kereta,
    s_asal.nama_stasiun AS stasiun_asal,
    s_tujuan.nama_stasiun AS stasiun_tujuan,
    j.waktu_berangkat,
    j.harga
FROM jadwal j
JOIN kereta k ON j.id_kereta = k.id_kereta
JOIN stasiun s_asal ON j.id_stasiun_asal = s_asal.id_stasiun
JOIN stasiun s_tujuan ON j.id_stasiun_tujuan = s_tujuan.id_stasiun;
SELECT * FROM view_jadwal_kereta;

---menampilkan DATA pemesanan, JOIN penumpang dan jadwal--
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

--menampilkan jadwal kereta harga tertinggi--
CREATE VIEW view_jadwal_termahal AS
SELECT 
    j.id_jadwal,
    k.nama_kereta,
    s_asal.nama_stasiun AS stasiun_asal,
    s_tujuan.nama_stasiun AS stasiun_tujuan,
    j.waktu_berangkat,
    j.harga
FROM jadwal j
JOIN kereta k ON j.id_kereta = k.id_kereta
JOIN stasiun s_asal ON j.id_stasiun_asal = s_asal.id_stasiun
JOIN stasiun s_tujuan ON j.id_stasiun_tujuan = s_tujuan.id_stasiun
WHERE j.harga = (SELECT MAX(harga) FROM jadwal);
SELECT * FROM view_jadwal_termahal;

--stored orocedure--
---menambah DATA penumpang--
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

--menambah DATA kereta--
DELIMITER //
CREATE PROCEDURE tambah_kereta(
    IN p_nama_kereta VARCHAR(100),
    IN p_kelas_kereta ENUM('Eksekutif', 'Bisnis', 'Ekonomi'),
    IN p_kapasitas INT
)
BEGIN
    INSERT INTO kereta (nama_kereta, kelas_kereta, kapasitas)
    VALUES (p_nama_kereta, p_kelas_kereta, p_kapasitas);
END //
DELIMITER ;
CALL tambah_kereta(
    'Mutiara Selatan',
    'Eksekutif',
    100
);

--update DATA penumpang berdasarkan id--
DELIMITER //
CREATE PROCEDURE update_penumpang(
    IN p_id INT,
    IN p_nama VARCHAR(100),
    IN p_email VARCHAR(100),
    IN p_password VARCHAR(100),
    IN p_no_telepon VARCHAR(15),
    IN p_alamat TEXT,
    IN p_role ENUM('admin', 'penumpang')
)
BEGIN
    UPDATE penumpang
    SET nama = p_nama,
        email = p_email,
        PASSWORD = p_password,
        no_telepon = p_no_telepon,
        alamat = p_alamat,
        ROLE = p_role
    WHERE id_penumpang = p_id;
END //
DELIMITER ;

---UPDATE DATA kreta berdasarkan id--
DELIMITER //
CREATE PROCEDURE update_kereta(
    IN p_id INT,
    IN p_nama_kereta VARCHAR(100),
    IN p_kelas_kereta ENUM('Eksekutif', 'Bisnis', 'Ekonomi'),
    IN p_kapasitas INT
)
BEGIN
    UPDATE kereta
    SET nama_kereta = p_nama_kereta,
        kelas_kereta = p_kelas_kereta,
        kapasitas = p_kapasitas
    WHERE id_kereta = p_id;
END //
DELIMITER ;

--jadwal kreta berdasarkan kls kreta--
DELIMITER //
CREATE PROCEDURE tampil_jadwal_kereta_by_kelas(
    IN p_kelas_kereta ENUM('Eksekutif', 'Bisnis', 'Ekonomi')
)
BEGIN
    SELECT 
        j.id_jadwal,
        k.nama_kereta,
        k.kelas_kereta,
        s_asal.nama_stasiun AS stasiun_asal,
        s_tujuan.nama_stasiun AS stasiun_tujuan,
        j.waktu_berangkat,
        j.harga
    FROM jadwal j
    JOIN kereta k ON j.id_kereta = k.id_kereta
    JOIN stasiun s_asal ON j.id_stasiun_asal = s_asal.id_stasiun
    JOIN stasiun s_tujuan ON j.id_stasiun_tujuan = s_tujuan.id_stasiun
    WHERE k.kelas_kereta = p_kelas_kereta;
END //
DELIMITER ;
CALL tampil_jadwal_kereta_by_kelas('Eksekutif');


--tambah kreta dengan looping--
DELIMITER //
CREATE PROCEDURE tambah_kereta_loop()
BEGIN
    DECLARE i INT DEFAULT 1;

    WHILE i <= 5 DO
        INSERT INTO kereta (nama_kereta, kelas_kereta, kapasitas)
        VALUES (CONCAT('Kereta Loop ', i), 'Ekonomi', 100 + i);
        
        SET i = i + 1;
    END WHILE;
END //
DELIMITER ;
CALL tambah_kereta_loop();
SELECT * FROM kereta;

---triggerr--
DELIMITER //
CREATE TRIGGER hitung_total_harga_sebelum_insert
BEFORE INSERT ON pemesanan
FOR EACH ROW
BEGIN
    DECLARE harga_tiket INT;
    SELECT harga INTO harga_tiket FROM jadwal WHERE id_jadwal = NEW.id_jadwal;
    
    SET NEW.total_harga = harga_tiket * NEW.jumlah_tiket;
END;
//
DELIMITER ;


CREATE TABLE log_pemesanan (
    id_log INT AUTO_INCREMENT PRIMARY KEY,
    aksi ENUM('INSERT', 'DELETE') NOT NULL,
    id_pemesanan INT,
    nama_penumpang VARCHAR(100),
    waktu_log DATETIME DEFAULT CURRENT_TIMESTAMP
);
DELIMITER //
CREATE TRIGGER log_insert_pemesanan
AFTER INSERT ON pemesanan
FOR EACH ROW
BEGIN
    DECLARE namaP VARCHAR(100);
    SELECT nama INTO namaP FROM penumpang WHERE id_penumpang = NEW.id_penumpang;

    INSERT INTO log_pemesanan (aksi, id_pemesanan, nama_penumpang)
    VALUES ('INSERT', NEW.id_pemesanan, namaP);
END;
//
DELIMITER ;
INSERT INTO pemesanan (id_penumpang, id_jadwal, jumlah_tiket, tanggal_pemesanan)
VALUES (1, 1, 3, NOW());
INSERT INTO pemesanan (id_penumpang, id_jadwal, jumlah_tiket, tanggal_pemesanan)
VALUES (1, 1, 2, NOW());
SELECT * FROM log_pemesanan;
SELECT * FROM pemesanan;

DELIMITER //
CREATE TRIGGER log_delete_pemesanan
AFTER DELETE ON pemesanan
FOR EACH ROW
BEGIN
    DECLARE namaP VARCHAR(100);
    SELECT nama INTO namaP FROM penumpang WHERE id_penumpang = OLD.id_penumpang;

    INSERT INTO log_pemesanan (aksi, id_pemesanan, nama_penumpang)
    VALUES ('DELETE', OLD.id_pemesanan, namaP);
END;
//
DELIMITER ;
DELETE FROM pemesanan WHERE id_pemesanan = 3;
SELECT * FROM log_pemesanan

DELIMITER //
CREATE TRIGGER log_update_pemesanan
AFTER UPDATE ON pemesanan
FOR EACH ROW
BEGIN
    DECLARE namaP VARCHAR(100);
    SELECT nama INTO namaP FROM penumpang WHERE id_penumpang = NEW.id_penumpang;

    INSERT INTO log_pemesanan (aksi, id_pemesanan, nama_penumpang)
    VALUES ('UPDATE', NEW.id_pemesanan, namaP);
END;
//
DELIMITER ;
UPDATE pemesanan
SET jumlah_tiket = 5
WHERE id_pemesanan = 1;

ALTER TABLE log_pemesanan
MODIFY COLUMN aksi ENUM('INSERT', 'DELETE', 'UPDATE') NOT NULL;













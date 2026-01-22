-- phpMyAdmin SQL Dump
-- Database: `db_pwd2025`
-- Tabel untuk versi alternatif

-- --------------------------------------------------------

--
-- Struktur tabel `data_pengunjung`
-- Tabel untuk menampung biodata pengunjung
--

CREATE TABLE `data_pengunjung` (
  `pid` int NOT NULL,
  `pnim` varchar(20) DEFAULT NULL,
  `pnama` varchar(100) DEFAULT NULL,
  `ptempat` varchar(100) DEFAULT NULL,
  `ptanggal` varchar(50) DEFAULT NULL,
  `phobi` varchar(100) DEFAULT NULL,
  `ppasangan` varchar(100) DEFAULT NULL,
  `ppekerjaan` varchar(100) DEFAULT NULL,
  `portu` varchar(100) DEFAULT NULL,
  `pkakak` varchar(100) DEFAULT NULL,
  `padik` varchar(100) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Indexes untuk tabel `data_pengunjung`
--
ALTER TABLE `data_pengunjung`
  ADD PRIMARY KEY (`pid`);

--
-- AUTO_INCREMENT untuk tabel `data_pengunjung`
--
ALTER TABLE `data_pengunjung`
  MODIFY `pid` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

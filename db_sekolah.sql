CREATE DATABASE IF NOT EXISTS db_sekolah
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE db_sekolah;

CREATE TABLE IF NOT EXISTS siswa (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    nis VARCHAR(30) NOT NULL,
    nama VARCHAR(100) NOT NULL,
    kelas VARCHAR(30) NOT NULL,
    jurusan VARCHAR(100) NOT NULL,
    alamat TEXT NULL,
    no_hp VARCHAR(20) NULL,
    PRIMARY KEY (id),
    UNIQUE KEY uniq_nis (nis)
) ENGINE=InnoDB;

-- =====================================================
-- Database Schema untuk Aplikasi Berita
-- Tugas 4 - Pemrograman Web 2
-- =====================================================

CREATE DATABASE IF NOT EXISTS db_berita CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE db_berita;

-- =====================================================
-- Tabel berita: menyimpan data utama berita
-- =====================================================
CREATE TABLE IF NOT EXISTS berita (
    id          INT             NOT NULL AUTO_INCREMENT,
    judul       VARCHAR(255)    NOT NULL COMMENT 'Judul berita',
    sinopsis    TEXT            NOT NULL COMMENT 'Sinopsis / ringkasan berita',
    isi         LONGTEXT        NOT NULL COMMENT 'Isi lengkap berita',
    created_at  TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at  TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- Tabel foto_berita: menyimpan URL foto yang terkait
-- dengan satu item berita (relasi one-to-many).
-- File foto tidak disimpan di tabel, hanya URL-nya.
-- =====================================================
CREATE TABLE IF NOT EXISTS foto_berita (
    id          INT             NOT NULL AUTO_INCREMENT,
    berita_id   INT             NOT NULL COMMENT 'FK ke tabel berita',
    url_foto    VARCHAR(500)    NOT NULL COMMENT 'URL / path relatif file foto di server',
    created_at  TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    CONSTRAINT fk_foto_berita FOREIGN KEY (berita_id)
        REFERENCES berita (id) ON DELETE CASCADE ON UPDATE CASCADE,
    INDEX idx_berita_id (berita_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

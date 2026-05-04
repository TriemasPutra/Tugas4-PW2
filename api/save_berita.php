<?php
// =====================================================
// API Endpoint: Simpan Berita + Foto
// Method  : POST
// Response: JSON
// =====================================================

header('Content-Type: application/json; charset=utf-8');
header('X-Content-Type-Options: nosniff');

// Hanya terima request POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['status' => 'GAGAL', 'pesan' => 'Method tidak diizinkan.']);
    exit;
}

require_once __DIR__ . '/../config/database.php';

// ── Helper: kirim JSON dan hentikan eksekusi ──────────────────────────────────
function respond(string $status, string $pesan, array $data = []): void
{
    $payload = ['status' => $status, 'pesan' => $pesan];
    if (!empty($data)) {
        $payload['data'] = $data;
    }
    echo json_encode($payload, JSON_UNESCAPED_UNICODE);
    exit;
}

// ── 1. Validasi input teks ────────────────────────────────────────────────────
$judul    = trim($_POST['judul']    ?? '');
$sinopsis = trim($_POST['sinopsis'] ?? '');
$isi      = trim($_POST['isi']      ?? '');

if ($judul === '' || $sinopsis === '' || $isi === '') {
    respond('GAGAL', 'Judul, Sinopsis, dan Isi wajib diisi.');
}

// Validasi panjang maksimum (sesuai schema database)
if (mb_strlen($judul) > 255) {
    respond('GAGAL', 'Judul tidak boleh lebih dari 255 karakter.');
}

// ── 2. Validasi & upload foto ─────────────────────────────────────────────────
$uploadDir    = __DIR__ . '/../uploads/';
$uploadUrlBase = 'uploads/';           // URL relatif yang disimpan ke DB
$allowedMimes  = ['image/jpeg'];
$maxFileSize   = 5 * 1024 * 1024;     // 5 MB per file
$urlFotoList   = [];

if (!empty($_FILES['foto']['name'][0]) && $_FILES['foto']['name'][0] !== '') {
    $files = $_FILES['foto'];
    $total = count($files['name']);

    for ($i = 0; $i < $total; $i++) {
        if ($files['error'][$i] !== UPLOAD_ERR_OK) {
            respond('GAGAL', 'Terjadi kesalahan saat mengunggah file ke-' . ($i + 1) . '.');
        }

        // Validasi ukuran
        if ($files['size'][$i] > $maxFileSize) {
            respond('GAGAL', 'Ukuran file ke-' . ($i + 1) . ' melebihi batas 5 MB.');
        }

        // Validasi MIME type (JPG)
        $finfo    = new finfo(FILEINFO_MIME_TYPE);
        $mimeType = $finfo->file($files['tmp_name'][$i]);
        if (!in_array($mimeType, $allowedMimes, true)) {
            respond('GAGAL', 'File ke-' . ($i + 1) . ' bukan gambar JPG yang valid.');
        }

        // Buat nama file unik
        $ext      = 'jpg';
        $filename = uniqid('foto_', true) . '.' . $ext;
        $destPath = $uploadDir . $filename;

        if (!move_uploaded_file($files['tmp_name'][$i], $destPath)) {
            respond('GAGAL', 'Gagal menyimpan file ke-' . ($i + 1) . ' ke server.');
        }

        $urlFotoList[] = $uploadUrlBase . $filename;
    }
}

// ── 3. Simpan ke database dalam satu transaksi ───────────────────────────────
try {
    $conn = getConnection();
    $conn->begin_transaction();

    // 3a. Insert data berita
    $stmt = $conn->prepare(
        'INSERT INTO berita (judul, sinopsis, isi) VALUES (?, ?, ?)'
    );
    $stmt->bind_param('sss', $judul, $sinopsis, $isi);
    $stmt->execute();
    $beritaId = (int) $conn->insert_id;
    $stmt->close();

    // 3b. Insert URL foto (jika ada)
    if (!empty($urlFotoList)) {
        $stmtFoto = $conn->prepare(
            'INSERT INTO foto_berita (berita_id, url_foto) VALUES (?, ?)'
        );
        foreach ($urlFotoList as $url) {
            $stmtFoto->bind_param('is', $beritaId, $url);
            $stmtFoto->execute();
        }
        $stmtFoto->close();
    }

    $conn->commit();
    $conn->close();

    respond('SUKSES', 'Berita berhasil disimpan.', [
        'berita_id'  => $beritaId,
        'foto_count' => count($urlFotoList),
    ]);

} catch (Throwable $e) {
    if (isset($conn) && $conn instanceof mysqli) {
        $conn->rollback();
        $conn->close();
    }
    // Hapus file yang sudah terlanjur diupload
    foreach ($urlFotoList as $url) {
        $path = __DIR__ . '/../' . $url;
        if (file_exists($path)) {
            unlink($path);
        }
    }
    respond('GAGAL', 'Terjadi kesalahan server: ' . $e->getMessage());
}

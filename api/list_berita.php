<?php
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../config/database.php';

try {
    $conn = getConnection();

    $sql = "SELECT b.id, b.judul, b.sinopsis, f.url_foto, f.created_at
            FROM berita b
            LEFT JOIN foto_berita f ON f.berita_id = b.id
            ORDER BY b.created_at DESC, b.id DESC, f.id DESC";

    $result = $conn->query($sql);
    
    if (!$result) {
        throw new Exception($conn->error);
    }

    $rows = [];
    $indexById = [];
    while ($row = $result->fetch_assoc()) {
        $id = (int)$row['id'];
        if (!isset($indexById[$id])) {
            $indexById[$id] = count($rows);
            $rows[] = [
                'id' => $id,
                'judul' => $row['judul'],
                'sinopsis' => $row['sinopsis'],
                'fotos' => [],
                'created_at' => $row['created_at']
            ];
        }

        if (!empty($row['url_foto'])) {
            $rows[$indexById[$id]]['fotos'][] = $row['url_foto'];
        }
    }

    echo json_encode(
        [
            'status' => 'SUKSES',
            'data' => $rows
        ],
        JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
    );
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(
        [
            'status' => 'GAGAL',
            'pesan' => 'Gagal memuat data berita.'
        ],
        JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
    );
}

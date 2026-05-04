<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Berita</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

    <!-- jQuery 4 -->
    <script src="https://code.jquery.com/jquery-4.0.0.min.js" integrity="sha256-OaVG6prZf4v69dPg6PhVattBXkcOWQB62pdZ3ORyrao=" crossorigin="anonymous"></script>
    <script defer src="global.js"></script>
    <style>
        body { background-color: #f8f9fa; }

        .hero-banner {
            background: linear-gradient(135deg, #0d6efd 0%, #6610f2 100%);
            color: #fff;
            padding: 2.5rem 1rem;
            border-radius: 0 0 1rem 1rem;
            margin-bottom: 2rem;
        }

        /* Preview thumbnail grid */
        #previewContainer {
            display: flex;
            flex-wrap: wrap;
            gap: .5rem;
            margin-top: .5rem;
        }
        #previewContainer .preview-item {
            position: relative;
            width: 90px;
            height: 90px;
        }
        #previewContainer img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: .375rem;
            border: 2px solid #dee2e6;
        }
        #previewContainer .remove-photo {
            position: absolute;
            top: -6px;
            right: -6px;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background: #dc3545;
            color: #fff;
            border: none;
            font-size: 12px;
            line-height: 20px;
            text-align: center;
            cursor: pointer;
            padding: 0;
        }

        /* Status banner inside modal */
        #statusBanner { display: none; }
    </style>
</head>

<body>

<!-- ── Hero ─────────────────────────────────────────────────────────────────── -->
<div class="hero-banner text-center">
    <h1 class="fw-bold"><i class="bi bi-newspaper me-2"></i>Manajemen Berita</h1>
    <p class="mb-0">Tambah dan kelola artikel berita dengan mudah</p>
</div>

<!-- ── Main container ────────────────────────────────────────────────────────── -->
<div class="container pb-5">

    <!-- Tombol buka modal -->
    <div class="d-flex justify-content-end mb-3">
        <button
            type="button"
            class="btn btn-primary"
            data-bs-toggle="modal"
            data-bs-target="#modalBerita"
        >
            <i class="bi bi-plus-circle me-1"></i>Tambah Berita
        </button>
    </div>

    <!-- ── Tabel daftar berita (placeholder) ─────────────────────────────────── -->
    <div class="card shadow-sm">
        <div class="card-header fw-semibold bg-primary text-white">
            <i class="bi bi-list-ul me-1"></i>Daftar Berita
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0" id="tabelBerita">
                    <thead class="table-light">
                        <tr>
                            <th style="width:50px">#</th>
                            <th>Judul</th>
                            <th>Sinopsis</th>
                            <th style="width:120px">Foto</th>
                            <th style="width:200px"></th>
                            <th style="width:160px">Ditambahkan</th>
                            <th style="width:100px">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                <i class="bi bi-inbox fs-4 d-block mb-1"></i>
                                Belum ada berita. Klik <strong>Tambah Berita</strong> untuk memulai.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- ══════════════════════════════════════════════════════════════════════════════
     Bootstrap 5 Modal – Form Tambah Berita
══════════════════════════════════════════════════════════════════════════════ -->
<div
    class="modal fade"
    id="modalBerita"
    tabindex="-1"
    aria-labelledby="modalBeritaLabel"
    aria-modal="true"
    role="dialog"
>
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">

            <!-- Header -->
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modalBeritaLabel">
                    <i class="bi bi-pencil-square me-2"></i>Form Tambah Berita
                </h5>
                <button
                    type="button"
                    class="btn-close btn-close-white"
                    data-bs-dismiss="modal"
                    aria-label="Tutup"
                ></button>
            </div>

            <!-- Body -->
            <div class="modal-body">

                <!-- Status banner (sukses / gagal) -->
                <div id="statusBanner" class="alert mb-3" role="alert">
                    <i id="statusIcon" class="me-2"></i>
                    <span id="statusMessage"></span>
                </div>

                <!-- Form -->
                <form id="formBerita" novalidate enctype="multipart/form-data">

                    <!-- Judul -->
                    <div class="mb-3">
                        <label for="judul" class="form-label fw-semibold">
                            Judul <span class="text-danger">*</span>
                        </label>
                        <input
                            type="text"
                            class="form-control"
                            id="judul"
                            name="judul"
                            placeholder="Masukkan judul berita"
                            required
                            maxlength="255"
                        >
                        <div class="invalid-feedback">Judul wajib diisi.</div>
                    </div>

                    <!-- Sinopsis -->
                    <div class="mb-3">
                        <label for="sinopsis" class="form-label fw-semibold">
                            Sinopsis <span class="text-danger">*</span>
                        </label>
                        <textarea
                            class="form-control"
                            id="sinopsis"
                            name="sinopsis"
                            rows="3"
                            placeholder="Ringkasan singkat berita"
                            required
                        ></textarea>
                        <div class="invalid-feedback">Sinopsis wajib diisi.</div>
                    </div>

                    <!-- Isi -->
                    <div class="mb-3">
                        <label for="isi" class="form-label fw-semibold">
                            Isi Berita <span class="text-danger">*</span>
                        </label>
                        <textarea
                            class="form-control"
                            id="isi"
                            name="isi"
                            rows="6"
                            placeholder="Tulis isi lengkap berita di sini..."
                            required
                        ></textarea>
                        <div class="invalid-feedback">Isi berita wajib diisi.</div>
                    </div>

                    <!-- Foto (multiple, JPG) -->
                    <div class="mb-3">
                        <label for="foto" class="form-label fw-semibold">
                            Foto <span class="text-muted fw-normal">(JPG, maks. 5 MB per file)</span>
                        </label>
                        <input
                            type="file"
                            class="form-control"
                            id="foto"
                            name="foto[]"
                            accept=".jpg,.jpeg,image/jpeg"
                            multiple
                        >
                        <div class="form-text">
                            Anda dapat memilih lebih dari satu foto untuk satu berita.
                        </div>

                        <!-- Preview thumbnail -->
                        <div id="previewContainer"></div>
                    </div>

                </form>
            </div><!-- /modal-body -->

            <!-- Footer -->
            <div class="modal-footer">
                <button
                    type="button"
                    class="btn btn-secondary"
                    data-bs-dismiss="modal"
                >
                    <i class="bi bi-x-circle me-1"></i>Tutup
                </button>
                <button
                    type="button"
                    class="btn btn-primary"
                    id="btnSubmit"
                >
                    <span
                        id="btnSpinner"
                        class="spinner-border spinner-border-sm me-1 d-none"
                        role="status"
                        aria-hidden="true"
                    ></span>
                    <i class="bi bi-send me-1" id="btnIcon"></i>
                    <span id="btnText">Simpan Berita</span>
                </button>
            </div>

        </div><!-- /modal-content -->
    </div><!-- /modal-dialog -->
</div><!-- /modal -->
</body>
</html>

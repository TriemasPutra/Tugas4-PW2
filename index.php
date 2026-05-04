<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Berita</title>

    <!-- Bootstrap 5 CSS (local) -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <!-- Bootstrap Icons (local) -->
    <link rel="stylesheet" href="assets/css/bootstrap-icons.min.css">

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
                            <th style="width:160px">Ditambahkan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">
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

                <!-- ── Perbandingan JSON vs XML ─────────────────────────────── -->
                <hr>
                <details>
                    <summary class="fw-semibold text-secondary" style="cursor:pointer">
                        <i class="bi bi-info-circle me-1"></i>
                        Perbandingan Format Data: JSON vs XML
                    </summary>
                    <div class="mt-2 p-3 bg-light rounded small">

                        <p class="mb-2">
                            Aplikasi ini menggunakan <strong>JSON</strong> sebagai format pertukaran
                            data antara frontend dan backend melalui AJAX. Berikut perbandingannya
                            dengan <strong>XML</strong>:
                        </p>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="border rounded p-2 h-100">
                                    <p class="fw-semibold text-success mb-1"><i class="bi bi-braces me-1"></i>JSON</p>
                                    <pre class="mb-2 text-success" style="font-size:11px">{
  "status": "SUKSES",
  "pesan": "Berita berhasil disimpan.",
  "data": {
    "berita_id": 1,
    "foto_count": 2
  }
}</pre>
                                    <ul class="mb-0 ps-3">
                                        <li>Lebih ringkas dan mudah dibaca.</li>
                                        <li>Parse langsung ke objek JS (<code>JSON.parse</code>).</li>
                                        <li>Ukuran payload lebih kecil (&plusmn; 30&ndash;40% lebih kecil).</li>
                                        <li>Standar de‑facto untuk REST API modern.</li>
                                        <li>Dukungan native di semua bahasa pemrograman populer.</li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="border rounded p-2 h-100">
                                    <p class="fw-semibold text-danger mb-1"><i class="bi bi-code-slash me-1"></i>XML</p>
                                    <pre class="mb-2 text-danger" style="font-size:11px">&lt;response&gt;
  &lt;status&gt;SUKSES&lt;/status&gt;
  &lt;pesan&gt;Berita berhasil disimpan.&lt;/pesan&gt;
  &lt;data&gt;
    &lt;berita_id&gt;1&lt;/berita_id&gt;
    &lt;foto_count&gt;2&lt;/foto_count&gt;
  &lt;/data&gt;
&lt;/response&gt;</pre>
                                    <ul class="mb-0 ps-3">
                                        <li>Lebih verbose; banyak tag pembuka &amp; penutup.</li>
                                        <li>Butuh XML parser atau <code>DOMParser</code> di browser.</li>
                                        <li>Payload lebih besar → lebih lambat dikirim/terima.</li>
                                        <li>Cocok untuk dokumen terstruktur kompleks (SOAP, dll.).</li>
                                        <li>Mendukung validasi schema (XSD) dan namespace.</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <p class="mt-3 mb-0">
                            <strong>Kesimpulan:</strong> Untuk aplikasi web modern berbasis AJAX,
                            <strong>JSON lebih unggul</strong> karena lebih ringan, lebih cepat
                            diparse, dan lebih mudah diintegrasikan dengan JavaScript. XML lebih
                            relevan pada sistem enterprise lama atau layanan SOAP yang memerlukan
                            validasi skema yang ketat.
                        </p>
                    </div>
                </details>

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


<!-- ══════════════════════════════════════════════════════════════════════════════
     Scripts
══════════════════════════════════════════════════════════════════════════════ -->

<!-- Bootstrap 5 JS bundle (local, includes Popper) -->
<script src="assets/js/bootstrap.bundle.min.js"></script>

<script>
/* ─────────────────────────────────────────────────────────────────────────────
   Variabel global
───────────────────────────────────────────────────────────────────────────── */
// DataTransfer digunakan untuk mengelola kumpulan File yang dipilih user
let selectedFiles = new DataTransfer();

/* ─────────────────────────────────────────────────────────────────────────────
   Preview foto
───────────────────────────────────────────────────────────────────────────── */
const fotoInput        = document.getElementById('foto');
const previewContainer = document.getElementById('previewContainer');

fotoInput.addEventListener('change', function () {
    // Tambahkan file baru ke DataTransfer (hindari duplikat berdasarkan nama+ukuran)
    Array.from(this.files).forEach(file => {
        const isDuplicate = Array.from(selectedFiles.files).some(
            f => f.name === file.name && f.size === file.size
        );
        if (!isDuplicate) {
            selectedFiles.items.add(file);
        }
    });

    renderPreviews();
    // Reset nilai input agar file yang sama bisa dipilih ulang.
    // JANGAN lakukan fotoInput.files = selectedFiles.files sebelum reset ini,
    // karena itu akan membuat keduanya berbagi referensi FileList yang sama
    // sehingga this.value = '' akan mengosongkan selectedFiles juga.
    this.value = '';
});

function renderPreviews() {
    previewContainer.innerHTML = '';
    Array.from(selectedFiles.files).forEach((file, index) => {
        const reader = new FileReader();
        reader.onload = function (e) {
            const item = document.createElement('div');
            item.className = 'preview-item';

            const img = document.createElement('img');
            img.src = e.target.result;
            img.alt = file.name;
            img.title = file.name;

            const btn = document.createElement('button');
            btn.className = 'remove-photo';
            btn.innerHTML = '&times;';
            btn.title = 'Hapus foto ini';
            btn.setAttribute('data-index', index);
            btn.addEventListener('click', removePhoto);

            item.appendChild(img);
            item.appendChild(btn);
            previewContainer.appendChild(item);
        };
        reader.readAsDataURL(file);
    });
}

function removePhoto(e) {
    const idx = parseInt(e.currentTarget.getAttribute('data-index'), 10);

    // Bangun ulang DataTransfer tanpa file yang dihapus
    const newDT = new DataTransfer();
    Array.from(selectedFiles.files).forEach((file, i) => {
        if (i !== idx) newDT.items.add(file);
    });
    selectedFiles = newDT;

    renderPreviews();
}

/* ─────────────────────────────────────────────────────────────────────────────
   Reset modal saat ditutup
───────────────────────────────────────────────────────────────────────────── */
const modalEl = document.getElementById('modalBerita');
modalEl.addEventListener('hidden.bs.modal', function () {
    resetForm();
});

function resetForm() {
    const form = document.getElementById('formBerita');
    form.reset();
    form.classList.remove('was-validated');
    selectedFiles = new DataTransfer();
    previewContainer.innerHTML = '';
    hideStatus();
    setBtnLoading(false);
}

/* ─────────────────────────────────────────────────────────────────────────────
   Status banner (SUKSES / GAGAL)
───────────────────────────────────────────────────────────────────────────── */
function showStatus(type, message) {
    const banner  = document.getElementById('statusBanner');
    const icon    = document.getElementById('statusIcon');
    const msgEl   = document.getElementById('statusMessage');

    banner.className = 'alert mb-3 ' + (type === 'SUKSES' ? 'alert-success' : 'alert-danger');
    icon.className   = 'bi me-2 ' + (type === 'SUKSES' ? 'bi-check-circle-fill' : 'bi-x-circle-fill');
    msgEl.textContent = message;
    banner.style.display = 'block';

    // Scroll ke atas modal-body agar banner terlihat
    banner.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
}

function hideStatus() {
    const banner = document.getElementById('statusBanner');
    banner.style.display = 'none';
}

/* ─────────────────────────────────────────────────────────────────────────────
   Tombol loading state
───────────────────────────────────────────────────────────────────────────── */
function setBtnLoading(loading) {
    const btn     = document.getElementById('btnSubmit');
    const spinner = document.getElementById('btnSpinner');
    const btnIcon = document.getElementById('btnIcon');
    const btnText = document.getElementById('btnText');

    btn.disabled = loading;
    spinner.classList.toggle('d-none', !loading);
    btnIcon.classList.toggle('d-none', loading);
    btnText.textContent = loading ? 'Menyimpan...' : 'Simpan Berita';
}

/* ─────────────────────────────────────────────────────────────────────────────
   Submit via AJAX (JavaScript Fetch API)
───────────────────────────────────────────────────────────────────────────── */
document.getElementById('btnSubmit').addEventListener('click', function () {
    const form = document.getElementById('formBerita');

    // Validasi HTML5
    form.classList.add('was-validated');
    if (!form.checkValidity()) {
        return;
    }

    hideStatus();
    setBtnLoading(true);

    // Catatan: nilai form diambil sebelum AJAX agar konsisten dengan data yang dikirim
    const judulVal    = document.getElementById('judul').value.trim();
    const sinopsisVal = document.getElementById('sinopsis').value.trim();

    // Bangun FormData dari form + file-file yang dipilih
    const formData = new FormData(form);

    // Hapus entri foto[] lama lalu tambahkan ulang dari selectedFiles
    formData.delete('foto[]');
    Array.from(selectedFiles.files).forEach(file => {
        formData.append('foto[]', file);
    });

    // Kirim ke backend
    fetch('api/save_berita.php', {
        method: 'POST',
        body: formData
    })
    .then(function (response) {
        // Periksa HTTP status
        if (!response.ok) {
            return response.json().then(function (data) {
                throw new Error(data.pesan || 'Terjadi kesalahan HTTP ' + response.status);
            });
        }
        return response.json();
    })
    .then(function (data) {
        setBtnLoading(false);

        if (data.status === 'SUKSES') {
            showStatus('SUKSES', data.pesan + (data.data ? ' (ID: ' + data.data.berita_id + ', Foto: ' + data.data.foto_count + ')' : ''));
            // Tambahkan baris baru ke tabel (gunakan nilai yang diambil sebelum reset)
            addRowToTable(data, judulVal, sinopsisVal);
            // Reset form (pertahankan modal terbuka agar user melihat banner)
            const formEl = document.getElementById('formBerita');
            formEl.reset();
            formEl.classList.remove('was-validated');
            selectedFiles = new DataTransfer();
            previewContainer.innerHTML = '';
        } else {
            showStatus('GAGAL', data.pesan || 'Terjadi kesalahan tidak diketahui.');
        }
    })
    .catch(function (err) {
        setBtnLoading(false);
        showStatus('GAGAL', 'Kesalahan jaringan: ' + err.message);
    });
});

/* ─────────────────────────────────────────────────────────────────────────────
   Tambah baris ke tabel daftar berita
───────────────────────────────────────────────────────────────────────────── */
let rowCounter = 0;

function addRowToTable(data, judul, sinopsis) {
    const tbody = document.querySelector('#tabelBerita tbody');
    rowCounter++;

    // Hapus baris placeholder "belum ada berita" jika ada
    const placeholder = tbody.querySelector('td[colspan]');
    if (placeholder) {
        placeholder.closest('tr').remove();
    }

    const fotoCount = data.data ? data.data.foto_count : 0;
    const now = new Date().toLocaleString('id-ID');

    const tr = document.createElement('tr');
    tr.innerHTML =
        '<td>' + rowCounter + '</td>' +
        '<td class="fw-semibold">' + escapeHtml(judul) + '</td>' +
        '<td class="text-muted small">' + escapeHtml(sinopsis.substring(0, 80)) + (sinopsis.length > 80 ? '…' : '') + '</td>' +
        '<td><span class="badge bg-secondary">' + fotoCount + ' foto</span></td>' +
        '<td class="small">' + now + '</td>';
    tbody.appendChild(tr);
}

function escapeHtml(str) {
    return str
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#39;');
}
</script>

</body>
</html>

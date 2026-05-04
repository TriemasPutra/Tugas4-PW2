<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Berita</title>

    <!-- Bootstrap 5 CSS -->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/bootstrap-icons.min.css" rel="stylesheet">
    <script src="assets/js/bootstrap.bundle.min.js"></script>

    <!-- jQuery 4 -->
    <script src="https://code.jquery.com/jquery-4.0.0.min.js" integrity="sha256-OaVG6prZf4v69dPg6PhVattBXkcOWQB62pdZ3ORyrao=" crossorigin="anonymous"></script>

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
        console.log('Response dari server:', data);
        if (data.status === 'SUKSES') {
            showStatus('SUKSES', data.pesan + (data.data ? ' (ID: ' + data.data.berita_id + ', Foto: ' + data.data.foto_count + ')' : ''));
            // Tambahkan baris baru ke tabel (gunakan nilai yang diambil sebelum reset)
            addRowToTable(data.data.fotos, judulVal, sinopsisVal, data.data.created_at, data.data.berita_id);
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

function addRowToTable(foto, judul, sinopsis, dateAdded = null, id) {
    const tbody = document.querySelector('#tabelBerita tbody');
    rowCounter++;

    // Hapus baris placeholder "belum ada berita" jika ada
    const placeholder = tbody.querySelector('td[colspan]');
    if (placeholder) {
        placeholder.closest('tr').remove();
    }
    const imagesHtml = foto.map(url => 
      `<img src="${url}" style="width:40px;height:40px;object-fit:cover;margin-right:4px;">`
    ).join('');

    const fotoCount = foto.length;
    const now = dateAdded ?? new Date().toLocaleString('id-ID');

    const tr = document.createElement('tr');
    tr.innerHTML =
        `<td> ${rowCounter} </td> 
        <td class="fw-semibold"> ${escapeHtml(judul)} </td>
        <td class="text-muted small"> ${escapeHtml(sinopsis.substring(0, 80)) + (sinopsis.length > 80 ? '…' : '')} </td>
        <td><span class="badge bg-secondary"> ${fotoCount} foto</span></td>
        <td>${imagesHtml}</td>
        <td class="small"> ${now} </td>
        <td>
            <a class="btn btn-sm btn-outline-danger" href="api/delete_berita.php?id=${id}" onclick="return confirm('Apakah Anda yakin ingin menghapus berita ini?');">
                <i class="bi bi-trash"></i>
            </a>
        </td>`;
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

function renderTableError(message) {
    $('#tabelBerita tbody').html(
        `<tr><td colspan="4" class="text-center text-danger py-4">${escapeHtml(message)}</td></tr>`
    );
}

function loadBeritaTabel() {
    $.ajax({
        url: 'api/list_berita.php',
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            if (!response || response.status !== 'SUKSES' || !Array.isArray(response.data)) {
                renderTableError('Format data tidak valid.');
                return;
            }
            console.log('Data berita berhasil dimuat:', response.data);
            for (const berita of response.data) {
                addRowToTable(berita.fotos, berita.judul, berita.sinopsis, berita.created_at, berita.id);
            }
        },
        error: function() {
            renderTableError('Gagal memuat data dari database.');
        }
    });
}

(function () {
    loadBeritaTabel();
})();
</script>

</body>
</html>

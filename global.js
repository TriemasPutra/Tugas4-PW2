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
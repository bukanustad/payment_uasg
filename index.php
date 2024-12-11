<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap demo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  </head>
  <body>
    <!-- Table 1 - Bootstrap Brain Component -->
<section class="py-3 py-md-5">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-12 col-lg-9 col-xl-8">
        <div class="card widget-card bg-primary text-white border-light shadow-sm">
          <div class="card-body">
            <h4>Saldo: <small><b id="saldo">Rp. 0,</b></small><button class="btn btn-light text-primary shadow-light" style="float: right;font-size: 15px" data-bs-toggle="modal" data-bs-target="#modalTambah"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-clipboard-plus" viewBox="0 0 16 16">
  <path fill-rule="evenodd" d="M8 7a.5.5 0 0 1 .5.5V9H10a.5.5 0 0 1 0 1H8.5v1.5a.5.5 0 0 1-1 0V10H6a.5.5 0 0 1 0-1h1.5V7.5A.5.5 0 0 1 8 7"/>
  <path d="M4 1.5H3a2 2 0 0 0-2 2V14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V3.5a2 2 0 0 0-2-2h-1v1h1a1 1 0 0 1 1 1V14a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V3.5a1 1 0 0 1 1-1h1z"/>
  <path d="M9.5 1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-3a.5.5 0 0 1-.5-.5v-1a.5.5 0 0 1 .5-.5zm-3-1A1.5 1.5 0 0 0 5 1.5v1A1.5 1.5 0 0 0 6.5 4h3A1.5 1.5 0 0 0 11 2.5v-1A1.5 1.5 0 0 0 9.5 0z"/>
</svg></button></h4> 
          </div>
        </div>
        <div class="card widget-card border-light shadow">
          <div class="card-body p-4">
            <h5 class="card-title widget-card-title mb-4">Pembayaran UAS Ganjil</h5>
            <div class="table-responsive">
              <!-- Form pencarian -->
<div class="mb-3">
    <label for="searchInput" class="form-label">Cari Pembayaran</label>
    <input type="text" class="form-control" id="searchInput" placeholder="Cari berdasarkan nama, kode bayar, atau penerima...">
</div>
              <table id="paymentTable" class="table table-borderless bsb-table-xl text-nowrap align-middle m-0">
                <thead>
                  <tr>
                    <th>No</th>
                    <th>Kode Bayar</th>
                    <th>Nama</th>
                    <th>Nominal</th>
                    <th>Terima</th>
                    <th>Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  <!-- tabel dinamis tampil -->
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<!-- Modal -->
<!-- Modal Form -->
<div class="modal fade" id="modalTambah" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Form Bayar</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <!-- Form -->

        <form id="paymentForm">
          <label>Nama Siswa</label>
          <input type="text" class="form-control" name="nama_siswa" required>
          
          <label>Kelas</label>
          <select class="form-control" name="kelas" required>
            <option selected disabled value="">Pilih...</option>
            <option value="IA">I A</option>
          </select>
          
          <label>Penerima</label>
          <select class="form-control" name="penerima" required>
            <option selected disabled value="">Pilih...</option>
            <option value="1">FAIRUZ</option>
            <option value="2">HAIRI WANTO</option>
            <option value="3">ABDUR ROZAQ</option>
            <option value="4">ABD. RIZAL</option>
            <option value="5">MASRIFAH</option>
            <option value="6">LAINNYA</option>
          </select>
               <label for="nominal">Nominal</label>
                <input type="number" class="form-control" id="nominal" name="nominal" required>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="button" id="submitPayment" class="btn btn-primary">Konfirmasi</button>
      </div>
    </div>
  </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
// Fungsi untuk mengambil data dari server dan menampilkan di tabel
function loadPayments() {
    fetch('get_payments.php') // Endpoint PHP
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json(); // Parsing JSON
        })
        .then(data => {
            console.log(data); // Menampilkan data yang diterima di console
            if (data.success) {
                const tableBody = document.querySelector('#paymentTable tbody');
                tableBody.innerHTML = ''; // Kosongkan tabel sebelum mengisi ulang

                // Menampilkan total nominal di bagian saldo
                const saldoElement = document.querySelector('#saldo');
                saldoElement.innerHTML = `<small><b>Rp. ${data.total_nominal.toLocaleString()},</b></small>`;

                let paymentData = data.data;

                // Fungsi untuk menampilkan data ke dalam tabel
                function renderTable(filteredData) {
                    filteredData.forEach((item, index) => {
                        const row = document.createElement('tr');
                        let penerimaName;
                        switch(item.penerima) {
                            case '1': penerimaName = 'FAIRUZ'; break;
                            case '2': penerimaName = 'HAIRI WANTO'; break;
                            case '3': penerimaName = 'ABDUR ROZAQ'; break;
                            case '4': penerimaName = 'ABD. RIZAL'; break;
                            case '5': penerimaName = 'MASRIFAH'; break;
                            case '6': penerimaName = 'LAINNYA'; break;
                            default: penerimaName = 'Tidak Dikenal'; // Jika ID penerima tidak sesuai
                        }
                        row.innerHTML = `
                            <td>${index + 1}</td>
                            <td><small class="mb-1"><b class="text-primary">${item.kode_bayar}</b></small></td>
                            <td>
                                <p class="mb-1">${item.nama_siswa}</p>
                                <small class="text-secondary fs-7">Kelas ${item.kelas}</small>
                            </td>
                            <td>Rp. <b class="text-primary">${parseInt(item.nominal).toLocaleString()}</b></td>
                            <td>${penerimaName}</td>
                            <td><button class="btn btn-danger deleteBtn" data-id="${item.id}">Hapus</button></td>
                        `;
                        tableBody.appendChild(row); // Tambahkan baris ke tabel
                    });

                    // Menambahkan event listener untuk tombol hapus setelah tabel diperbarui
                    document.querySelectorAll('.deleteBtn').forEach(button => {
                        button.addEventListener('click', function() {
                            const id = this.getAttribute('data-id'); // Ambil ID pembayaran yang akan dihapus
                            if (confirm('Apakah Anda yakin ingin menghapus data ini?')) {
                                deletePayment(id); // Fungsi untuk menghapus data pembayaran
                            }
                        });
                    });
                }

                // Tampilkan seluruh data
                renderTable(paymentData);

                // Fungsi pencarian berdasarkan input
                document.getElementById('searchInput').addEventListener('input', function(e) {
                    const searchTerm = e.target.value.toLowerCase();

                    // Filter data berdasarkan nama, kode bayar, dan penerima
                    const filteredData = paymentData.filter(item => {
                        return item.nama_siswa.toLowerCase().includes(searchTerm) || 
                               item.kode_bayar.toLowerCase().includes(searchTerm) || 
                               item.penerima.toLowerCase().includes(searchTerm);
                    });

                    // Render ulang tabel dengan data yang sudah difilter
                    tableBody.innerHTML = ''; // Kosongkan tabel
                    renderTable(filteredData); // Tampilkan data yang sudah difilter
                });

            } else {
                console.error('Gagal mengambil data:', data.message);
            }
        })
        .catch(error => {
            console.error('Terjadi kesalahan:', error);
        });
}

// Fungsi untuk menghapus data pembayaran
function deletePayment(id) {
  fetch('delete_payment.php', {
    method: 'POST',
    body: new URLSearchParams({ id: id }),  // Pastikan ID dikirim sebagai POST data
    headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
})
.then(response => response.json())  // Parsing JSON response
.then(data => {
    if (data.success) {
        alert('Data berhasil dihapus!');
        loadPayments(); // Memperbarui tabel setelah data dihapus
    } else {
        alert('Gagal menghapus data: ' + data.message); // Menampilkan pesan error
    }
})
.catch(error => {
    console.error('Terjadi kesalahan:', error);
    alert('Terjadi kesalahan saat menghapus data.');
});

}

document.getElementById('submitPayment').addEventListener('click', function () {
    const form = document.getElementById('paymentForm');
    const formData = new FormData(form);  // Ambil data dari form pembayaran

    // Kirim data ke server menggunakan fetch
    fetch('save_payment.php', {
        method: 'POST',
        body: formData,
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();  // Pastikan respons adalah JSON
    })
    .then(data => {
        if (data.success) {
            alert('Data berhasil disimpan!');
            loadPayments();  // Muat ulang data setelah berhasil simpan
            form.reset();  // Reset form setelah berhasil simpan
$('#modalTambah').modal('hide');  // Menutup modal dengan ID modalTambah

// Menghapus backdrop jika modal ditutup
$('.modal-backdrop').remove();
$('body').removeClass('modal-open');

        } else {
            alert(data.message || 'Gagal menyimpan data!');
        }
    })
    .catch(error => {
        console.error('Terjadi kesalahan:', error);
        alert('Terjadi kesalahan saat mengirim data. Periksa browser console untuk detail.');
    });
});


// Panggil fungsi saat halaman dimuat
document.addEventListener('DOMContentLoaded', loadPayments);

</script>
<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  </body>
</html>                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                     
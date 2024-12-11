<?php
header('Content-Type: application/json'); // Set output berupa JSON
include 'Koneksi.php'; // File koneksi database

// Cek koneksi database
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Gagal terhubung ke database']);
    exit;
}

// Ambil data dari tabel `pembayaran` dan hitung total nominal
$sql = "SELECT id, kode_bayar, nama_siswa, nominal, penerima, kelas FROM pembayaran ORDER BY id DESC";
$result = $conn->query($sql);

// Inisialisasi variabel untuk menyimpan data dan total nominal
$data = [];
$totalNominal = 0; // Untuk menghitung total nominal

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Tambahkan nominal ke total
        $totalNominal += $row['nominal'];

        // Simpan data pembayaran
        $data[] = [
            'id' => $row['id'],
            'kode_bayar' => $row['kode_bayar'],
            'nama_siswa' => $row['nama_siswa'],
            'kelas' => $row['kelas'],
            'nominal' => $row['nominal'],
            'penerima' => $row['penerima'],
        ];
    }
}

// Kembalikan data dalam format JSON, termasuk total nominal
echo json_encode([
    'success' => true,
    'data' => $data,
    'total_nominal' => $totalNominal // Total nominal
]);
exit;
?>

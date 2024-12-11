<?php
header('Content-Type: application/json'); // Pastikan output berupa JSON
ob_start(); // Mulai buffer output
ob_clean(); // Bersihkan output sebelumnya

include 'Koneksi.php'; // Pastikan file ini tidak menghasilkan output

// Cek koneksi database
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Gagal terhubung ke database']);
    exit;
}

// Hanya tangani request POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_siswa = $_POST['nama_siswa'] ?? '';
    $kelas = $_POST['kelas'] ?? '';
    $penerima = $_POST['penerima'] ?? '';
    $nominal = $_POST['nominal'] ?? '';

    // Validasi input
    if (empty($nama_siswa) || empty($kelas) || empty($penerima) || empty($nominal)) {
        echo json_encode(['success' => false, 'message' => 'Semua kolom harus diisi.']);
        exit;
    }

    // Pastikan nominal adalah angka dan valid
    if (!is_numeric($nominal)) {
        echo json_encode(['success' => false, 'message' => 'Nominal harus berupa angka yang valid.']);
        exit;
    }

    // Generate kode bayar yang unik
    $kode_bayar = 'PAY' . strtoupper(bin2hex(random_bytes(3)));

    // Simpan data ke database
    $stmt = $conn->prepare("INSERT INTO pembayaran (kode_bayar, nama_siswa, kelas, penerima, nominal) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param('sssii', $kode_bayar, $nama_siswa, $kelas, $penerima, $nominal);

    if ($stmt->execute()) {
        echo json_encode([
            'success' => true,
            'message' => 'Data berhasil disimpan.',
            'data' => [
                'kode_bayar' => $kode_bayar,
                'nama_siswa' => $nama_siswa,
                'kelas' => $kelas,
                'penerima' => $penerima,
                'nominal' => $nominal,
            ]
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Gagal menyimpan data.']);
    }

    $stmt->close();
    $conn->close();
    exit;
} else {
    echo json_encode(['success' => false, 'message' => 'Metode request tidak valid.']);
    exit;
}

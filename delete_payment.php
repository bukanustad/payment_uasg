<?php 
// delete_payment.php
header('Content-Type: application/json');
include('koneksi.php'); // Pastikan koneksi ke DB benar

// Cek apakah ID ada
if (isset($_POST['id'])) {
    $id = $_POST['id'];  // Ambil ID yang dikirim
    $query = "DELETE FROM pembayaran WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $id);

    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Data berhasil dihapus"]);
    } else {
        echo json_encode(["success" => false, "message" => "Gagal menghapus data"]);
    }
} else {
    echo json_encode(["success" => false, "message" => "ID tidak ditemukan"]);
}
?>

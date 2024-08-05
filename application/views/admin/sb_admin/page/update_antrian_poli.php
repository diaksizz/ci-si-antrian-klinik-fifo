<?php
// Koneksi database
$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'db_pasien';

$db = new mysqli($db_host, $db_user, $db_pass, $db_name);

if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

// Cek dan proses data POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_antrian_poli'])) {
    $id_antrian_poli = $_POST['id_antrian_poli'];
    $tgl_selesai = date('Y-m-d H:i:s');

    // Debug: cek apakah data POST diterima
    error_log("id_antrian_poli: " . $id_antrian_poli);

    // Query update
    $query = "UPDATE antrian_poli SET tgl_selesai = ? WHERE id_antrian_poli = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param('si', $tgl_selesai, $id_antrian_poli);

    // Debug: cek apakah statement sudah disiapkan dengan benar
    if ($stmt === false) {
        error_log("Statement prepare failed: " . $db->error);
    }

    if ($stmt->execute()) {
        // Debug: cek apakah eksekusi query berhasil
        error_log("Record updated successfully");
    } else {
        // Debug: cek jika terjadi kesalahan saat eksekusi query
        error_log("Error updating record: " . $stmt->error);
    }

    $stmt->close();
    $db->close();

    // Redirect kembali ke halaman utama
    header("Location: list.php");
    exit;
}
?>

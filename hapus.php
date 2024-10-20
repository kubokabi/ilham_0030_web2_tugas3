<?php
// Koneksi ke database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ilham_0030";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Ambil nim dari query string dan pastikan aman menggunakan prepared statement
if (isset($_POST['nim'])) {
    $nim = $_POST['nim'];

    // Siapkan pernyataan SQL untuk menghapus data
    $stmt = $conn->prepare("DELETE FROM mahasiswa WHERE nim = ?");
    $stmt->bind_param("s", $nim);

    // Eksekusi query
    if ($stmt->execute()) {
        // Redirect dengan notifikasi sukses
        header("Location: index.php?status=success&message=Data%20berhasil%20dihapus");
        exit();
    } else {
        echo "Error: " . $conn->error;
    }

    // Tutup statement
    $stmt->close();
} else {
    echo "NIM tidak ditemukan.";
}

$conn->close();

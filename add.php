<?php
$host = 'localhost';
$dbname = 'ilham_0030';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nim = htmlspecialchars($_POST['nim']);
        $nama = htmlspecialchars($_POST['nama']);
        $alamat = htmlspecialchars($_POST['alamat']);
        $nohp = htmlspecialchars($_POST['nohp']);
        $matkul = $_POST['matkul'];

        // Cek apakah NIM sudah ada
        $cekNim = $pdo->prepare("SELECT nim FROM mahasiswa WHERE nim = :nim");
        $cekNim->bindParam(':nim', $nim);
        $cekNim->execute();

        if ($cekNim->rowCount() > 0) {
            $message = "NIM sudah ada! Silakan gunakan NIM lain.";
            $messageType = "danger"; // Untuk notifikasi error
        } else {
            // Validasi file upload
            $foto = $_FILES['foto'];
            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
            $maxFileSize = 2 * 1024 * 1024; // 2 MB

            if (!in_array($foto['type'], $allowedTypes)) {
                $message = "Hanya file gambar (JPEG, PNG, GIF) yang diizinkan.";
                $messageType = "danger";
            } elseif ($foto['size'] > $maxFileSize) {
                $message = "Ukuran file tidak boleh lebih dari 2 MB.";
                $messageType = "danger";
            } else {
                $fotoData = file_get_contents($foto['tmp_name']);

                $sql = "INSERT INTO mahasiswa (nim, nama, alamat, nohp, matkul, foto) 
                        VALUES (:nim, :nama, :alamat, :nohp, :matkul, :foto)";
                $stmt = $pdo->prepare($sql);

                $stmt->bindParam(':nim', $nim);
                $stmt->bindParam(':nama', $nama);
                $stmt->bindParam(':alamat', $alamat);
                $stmt->bindParam(':nohp', $nohp);
                $stmt->bindParam(':matkul', $matkul);
                $stmt->bindParam(':foto', $fotoData, PDO::PARAM_LOB); // Bind foto sebagai BLOB

                if ($stmt->execute()) {
                    // Redirect ke index.php dengan pesan sukses
                    header("Location: index.php?status=success&message=Data mahasiswa berhasil ditambahkan.");
                    exit();
                } else {
                    $message = "Gagal menyimpan data.";
                    $messageType = "danger"; // Untuk notifikasi error
                }
            }
        }
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Data Mahasiswa</title>
    <link rel="icon" href="https://cdn-icons-png.flaticon.com/128/10433/10433100.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f7f9;
        }

        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #4a5568;
        }

        .btn {
            border-radius: 50px;
        }

        .btn-success {
            background-color: #4caf50;
            border-color: #4caf50;
        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <h1>Tambah Data Mahasiswa</h1>
        <form method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="nim" class="form-label">NIM</label>
                <input type="text" class="form-control" id="nim" name="nim" required placeholder="Contoh: 23.230.0030" maxlength="20">
            </div>
            <div class="mb-3">
                <label for="nama" class="form-label">Nama</label>
                <input type="text" class="form-control" id="nama" name="nama" required placeholder="Nama Lengkap">
            </div>
            <div class="mb-3">
                <label for="alamat" class="form-label">Alamat</label>
                <textarea class="form-control" id="alamat" name="alamat" required placeholder="Alamat Lengkap"></textarea>
            </div>
            <div class="mb-3">
                <label for="nohp" class="form-label">No HP</label>
                <input type="number" class="form-control" id="nohp" name="nohp" required placeholder="Contoh: 085742014272" maxlength="13">
            </div>
            <div class="mb-3">
                <label for="matkul" class="form-label">Mata Kuliah</label>
                <select class="form-control" id="matkul" name="matkul" required>
                    <option value="">-- Pilih Mata Kuliah --</option>
                    <option value="sistem informasi">Sistem Informasi</option>
                    <option value="teknik informatika">Teknik Informatika</option>
                    <option value="komputerisasi akuntansi">Komputerisasi Akuntansi</option>
                    <option value="manajemen informatika">Manajemen Informatika</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="foto" class="form-label">Foto</label>
                <input type="file" class="form-control" id="foto" name="foto" accept="image/*" required>
            </div>

            <!-- Preview Gambar -->
            <div id="preview" class="mb-3" style="display: none;">
                <img id="fotoPreview" src="" alt="Preview" class="img-thumbnail" style="max-width: 200px;">
            </div>

            <button type="submit" class="btn btn-success">Simpan</button>
            <a href="/" class="btn btn-secondary">Kembali</a>
        </form>

        <!-- Bootstrap Toast -->
        <?php if (isset($message)): ?>
            <div class="toast-container position-fixed top-0 end-0 p-3">
                <div class="toast show bg-<?= $messageType ?>" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="toast-header">
                        <strong class="me-auto">Notifikasi</strong>
                        <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                    <div class="toast-body">
                        <?= $message ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Preview image before upload
        document.getElementById('foto').addEventListener('change', function(event) {
            const file = event.target.files[0];
            const reader = new FileReader();
            reader.onload = function(e) {
                // Display the preview image
                const preview = document.getElementById('preview');
                const img = document.getElementById('fotoPreview');
                img.src = e.target.result;
                preview.style.display = 'block'; // Show preview div
            };
            reader.readAsDataURL(file);
        });

        // Hide the toast after 3 seconds
        setTimeout(function() {
            const toast = document.querySelector('.toast');
            if (toast) {
                toast.classList.remove('show');
                toast.classList.add('hide');
            }
        }, 3000);
    </script>
</body>

</html>
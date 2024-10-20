<?php
$host = 'localhost';
$dbname = 'ilham_0030';
$username = 'root';
$password = '';

// Koneksi ke database
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Ambil NIM dari query string
    if (isset($_GET['nim'])) {
        $nim = $_GET['nim'];

        // Ambil data mahasiswa berdasarkan NIM
        $stmt = $pdo->prepare("SELECT * FROM mahasiswa WHERE nim = :nim");
        $stmt->bindParam(':nim', $nim);
        $stmt->execute();
        $mahasiswa = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$mahasiswa) {
            // Jika mahasiswa tidak ditemukan, redirect ke index
            header("Location: index.php?status=error&message=Data mahasiswa tidak ditemukan.");
            exit();
        }
    } else {
        header("Location: index.php?status=error&message=NIM tidak ditemukan.");
        exit();
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
    <title>Detail Mahasiswa</title>
    <link rel="icon" href="https://cdn-icons-png.flaticon.com/128/10433/10433100.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f7f9;
        }

        .container {
            margin-top: 50px;
        }

        .card {
            max-width: 600px;
            margin: 0 auto;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .card img {
            max-height: 300px;
            object-fit: cover;
        }

        .btn {
            border-radius: 50px;
        }

        .btn-primary {
            background-color: #3182ce;
            border-color: #3182ce;
        }

        .card-header {
            background-color: #3182ce;
            color: white;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="card">
            <div class="card-header text-center">
                <h3>Profil Mahasiswa</h3>
            </div>
            <div class="card-body text-center">
                <!-- Foto Mahasiswa -->
                <?php if (!empty($mahasiswa['foto'])): ?>
                    <img src="data:image/jpeg;base64,<?= base64_encode($mahasiswa['foto']) ?>" alt="Foto Profil" class="img-thumbnail mb-3">
                <?php else: ?>
                    <img src="https://via.placeholder.com/150" alt="Foto Profil" class="img-thumbnail mb-3">
                <?php endif; ?>

                <!-- Informasi Mahasiswa -->
                <h4><?= htmlspecialchars($mahasiswa['nama']) ?></h4>
                <p><strong>NIM:</strong> <?= htmlspecialchars($mahasiswa['nim']) ?></p>
                <p><strong>Alamat:</strong> <?= htmlspecialchars($mahasiswa['alamat']) ?></p>
                <p><strong>No HP:</strong> <?= htmlspecialchars($mahasiswa['nohp']) ?></p>
                <p><strong>Mata Kuliah:</strong> <?= htmlspecialchars($mahasiswa['matkul']) ?></p>

                <!-- Tombol Aksi -->
                <a href="edit.php?nim=<?= urlencode($mahasiswa['nim']) ?>" class="btn btn-warning btn-sm">Edit</a>
                <a href="index.php" class="btn btn-secondary btn-sm">Kembali</a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
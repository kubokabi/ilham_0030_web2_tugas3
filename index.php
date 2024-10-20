<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ilham_0030";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

$sql = "SELECT * FROM mahasiswa";
$result = $conn->query($sql);
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Data Mahasiswa</title>
    <link rel="icon" href="https://cdn-icons-png.flaticon.com/128/10433/10433100.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f7f9;
            font-family: 'Arial', sans-serif;
        }

        h1 {
            color: #4a5568;
            margin-bottom: 30px;
        }

        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .table-responsive {
            margin-top: 20px;
        }

        .table th,
        .table td {
            text-align: center;
            vertical-align: middle;
        }

        .table thead {
            background-color: #3182ce;
            color: #fff;
        }

        .btn {
            border-radius: 50px;
        }

        .btn-sm {
            padding: 5px 10px;
            margin: 0 5px;
        }

        .btn-warning {
            background-color: #ff9800;
            border-color: #ff9800;
        }

        .btn-danger {
            background-color: #f44336;
            border-color: #f44336;
        }

        .btn-success {
            background-color: #4caf50;
            border-color: #4caf50;
        }

        .btn-info {
            background-color: #00bcd4;
            border-color: #00bcd4;
        }
    </style>
</head>

<body>
    <div class="container mt-4">
        <h1 class="text-center">Data Mahasiswa</h1>
        <a href="add.php" class="btn btn-info mb-3">Tambah Data</a>

        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>NIM</th>
                        <th>Nama</th>
                        <th>Mata Kuliah</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result->num_rows > 0) {
                        $no = 1;
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $no++ . "</td>";
                            echo "<td>" . $row['nim'] . "</td>";
                            echo "<td>" . $row['nama'] . "</td>";
                            echo "<td>" . $row['matkul'] . "</td>";
                            echo "<td>
                            <a href='edit.php?nim=" . $row['nim'] . "' class='btn btn-warning btn-sm'>Edit</a>
                            <button class='btn btn-danger btn-sm' data-bs-toggle='modal' data-bs-target='#hapusModal' data-nim='" . $row['nim'] . "'>Hapus</button>
                            <a href='detail.php?nim=" . $row['nim'] . "' class='btn btn-success btn-sm'>Detail</a>
                          </td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='5'>Tidak ada data</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <!-- Modal Hapus -->
        <div class="modal fade" id="hapusModal" tabindex="-1" aria-labelledby="hapusModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="hapusModalLabel">Konfirmasi Hapus</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Apakah Anda yakin ingin menghapus data ini?
                    </div>
                    <div class="modal-footer">
                        <form id="formHapus" method="post" action="hapus.php">
                            <input type="hidden" name="nim" id="nimHapus">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-danger">Hapus</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bootstrap Toast for notification -->
        <?php if (isset($_GET['status']) && $_GET['status'] === 'success'): ?>
            <div class="toast-container position-fixed top-0 end-0 p-3">
                <div class="toast show bg-success text-white" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="toast-header">
                        <strong class="me-auto">Notifikasi</strong>
                        <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                    <div class="toast-body">
                        <?= htmlspecialchars($_GET['message']) ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Hilangkan toast setelah 3 detik
        setTimeout(function() {
            var toast = document.querySelector('.toast');
            if (toast) {
                toast.classList.remove('show');
                toast.classList.add('hide');
            }
        }, 3000);

        // Bersihkan query string setelah notifikasi dihilangkan
        setTimeout(function() {
            var url = new URL(window.location);
            url.searchParams.delete('status');
            url.searchParams.delete('message');
            window.history.replaceState({}, document.title, url.toString());
        }, 3100);

        // Tangkap event tombol hapus untuk memunculkan modal
        var hapusModal = document.getElementById('hapusModal');
        hapusModal.addEventListener('show.bs.modal', function(event) {
            var button = event.relatedTarget; // Tombol yang memicu modal
            var nim = button.getAttribute('data-nim'); // Ambil data nim

            var inputNim = document.getElementById('nimHapus');
            inputNim.value = nim; // Isi input hidden dengan NIM
        });
    </script>
</body>

</html>

<?php
$conn->close();
?>
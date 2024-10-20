<?php
class Database
{
    // Properti untuk koneksi database
    public $host = "localhost";
    public $username = "root";
    public $password = "";
    public $database = "db_php";
    public $connect;

    // Koneksi ke database dalam constructor
    function __construct()
    {
        $this->connect = mysqli_connect($this->host, $this->username, $this->password);

        // Cek jika koneksi berhasil
        if (!$this->connect) {
            die("Koneksi database gagal: " . mysqli_connect_error());
        }

        // Pilih database
        mysqli_select_db($this->connect, $this->database);
    }

    // Fungsi untuk menampilkan semua data dari tabel tb_users
    function tampilData()
    {
        $data = mysqli_query($this->connect, "SELECT * FROM tb_users");
        $rows = mysqli_fetch_all($data, MYSQLI_ASSOC);
        return $rows;
    }

    // Fungsi untuk menambah data baru ke tabel tb_users
    function tambahData($nama, $alamat, $nohp, $email, $jenis_kelamin, $foto)
    {
        // Membaca data foto (BLOB)
        $fotoBlob = mysqli_real_escape_string($this->connect, file_get_contents($foto['tmp_name']));

        // Menjalankan query insert
        $query = "INSERT INTO tb_users (nama, alamat, nohp, email, jenis_kelamin, foto) 
                  VALUES ('$nama', '$alamat', '$nohp', '$email', '$jenis_kelamin', '$fotoBlob')";
        
        return mysqli_query($this->connect, $query);
    }
    
    // Fungsi untuk mendapatkan data spesifik berdasarkan ID
    function getDataById($id)
    {
        $query = mysqli_query($this->connect, "SELECT * FROM tb_users WHERE id = $id");
        return mysqli_fetch_assoc($query);
    }

    // Fungsi untuk menghapus data berdasarkan ID
    function hapusData($id)
    {
        return mysqli_query($this->connect, "DELETE FROM tb_users WHERE id = $id");
    }

    // Fungsi untuk mengedit data
    function editData($id, $nama, $alamat, $nohp, $email, $jenis_kelamin, $foto = null)
    {
        if ($foto != null && $foto['tmp_name'] != "") {
            $fotoBlob = mysqli_real_escape_string($this->connect, file_get_contents($foto['tmp_name']));
            $query = "UPDATE tb_users 
                      SET nama = '$nama', alamat = '$alamat', nohp = '$nohp', email = '$email', jenis_kelamin = '$jenis_kelamin', foto = '$fotoBlob' 
                      WHERE id = $id";
        } else {
            $query = "UPDATE tb_users 
                      SET nama = '$nama', alamat = '$alamat', nohp = '$nohp', email = '$email', jenis_kelamin = '$jenis_kelamin'
                      WHERE id = $id";
        }

        return mysqli_query($this->connect, $query);
    }
}
?>

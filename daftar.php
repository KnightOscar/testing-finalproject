<?php
include "inc/koneksi.php";
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Register | SI Perpustakaan</title>
    <link rel="icon" href="dist/img/logo.png">
    <!-- Responsive -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap -->
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
    <!-- SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
</head>

<body class="hold-transition register-page">
    <div class="register-box">
        <div class="register-logo">
            <h3>
                <font color="green"><b>Form Pendaftaran</b></font>
            </h3>
        </div>
        <!-- Form Pendaftaran -->
        <div class="register-box-body">
            <form action="#" method="post">
                <div class="form-group has-feedback">
                    <input type="text" class="form-control" name="nama" placeholder="Nama Lengkap" required>
                    <span class="glyphicon glyphicon-user form-control-feedback"></span>
                </div>
                <div class="form-group">
                    <select class="form-control" name="jekel" required>
                        <option value="" selected disabled>Jenis Kelamin</option>
                        <option value="Laki-laki">Laki-laki</option>
                        <option value="Perempuan">Perempuan</option>
                    </select>
                </div>
                <div class="form-group has-feedback">
                    <input type="text" class="form-control" name="domisili" placeholder="Domisili (Kota)" required>
                    <span class="glyphicon glyphicon-home form-control-feedback"></span>
                </div>
                <div class="form-group has-feedback">
                    <input type="text" class="form-control" name="no_hp" placeholder="Nomor HP" required>
                    <span class="glyphicon glyphicon-phone form-control-feedback"></span>
                </div>
                <div class="form-group has-feedback">
                    <input type="password" class="form-control" name="password" placeholder="Password" required>
                    <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                </div>
                <div class="row">
                    <div class="col-xs-8"></div>
                    <div class="col-xs-4">
                        <button type="submit" class="btn btn-success btn-block btn-flat" name="btnRegister">
                            <b>Daftar</b>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Proses Pendaftaran -->
    <?php
    if (isset($_POST['btnRegister'])) {
        $nama = mysqli_real_escape_string($koneksi, $_POST['nama']);
        $jekel = mysqli_real_escape_string($koneksi, $_POST['jekel']);
        $domisili = mysqli_real_escape_string($koneksi, $_POST['domisili']);
        $no_hp = mysqli_real_escape_string($koneksi, $_POST['no_hp']);
        $password = mysqli_real_escape_string($koneksi, md5($_POST['password']));

        // Generate ID Anggota secara otomatis
        $query_id = "SELECT MAX(id_anggota) AS max_id FROM tb_anggota";
        $result_id = mysqli_query($koneksi, $query_id);
        $data_id = mysqli_fetch_array($result_id);
        $max_id = $data_id['max_id'];

        if ($max_id) {
            // Increment ID terakhir
            $angka_id = (int) substr($max_id, 1) + 1;
            $id_baru = "A" . str_pad($angka_id, 3, "0", STR_PAD_LEFT);
        } else {
            // Jika belum ada data, gunakan ID pertama
            $id_baru = "A001";
        }


        $array_nama = explode(" ", $nama);
        $username = $array_nama[0];

        // Simpan data ke tabel tb_anggota
        $sql_register = "INSERT INTO tb_anggota (id_anggota, nama, jekel, kelas, no_hp) 
                         VALUES ('$id_baru', '$nama', '$jekel', '$domisili', '$no_hp')";
        $query_register = mysqli_query($koneksi, $sql_register);

        // Simpan password ke tabel pengguna
        $sql_user = "INSERT INTO tb_pengguna (id_pengguna, nama_pengguna, username, password, level)
                     VALUES (NULL, '$username', '$id_baru', '$password', 'Peminjam')";
        $query_user = mysqli_query($koneksi, $sql_user);

        if ($query_register && $query_user) {
            echo "<script>
                Swal.fire({
                    title: 'Pendaftaran Berhasil!',
                    text: 'ID Anda adalah $id_baru. Silakan login menggunakan ID dan password Anda.',
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.value) {
                        window.location = 'login.php';
                    }
                });
            </script>";
        } else {
            echo "<script>
                Swal.fire({
                    title: 'Pendaftaran Gagal!',
                    text: 'Terjadi kesalahan, coba lagi.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            </script>";
        }
    }
    ?>
</body>

</html>

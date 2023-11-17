<?php

    include_once 'lib/database.php';

    class Register{
        
        public $db;
        public function __construct()
        {
            $this->db = new Database();
        }

        public function addRegister($data, $file){
            $name = $data['name'];
            $telepon = $data['telepon'];
            $email = $data['email'];
            $alamat = $data['alamat'];

            $permited = array('jpg', 'jpeg', 'img', 'png', 'pdf');
            $file_name = $file['foto']['name'];
            $file_size = $file['foto']['size'];
            $file_temp = $file['foto']['tmp_name'];

            $div = explode('.', $file_name);
            $file_ext = strtolower(end($div));
            $unique_image = substr(md5(time()),0,10).'.'.$file_ext;
            $upload_image = "upload/".$unique_image;

            if (empty($name) || empty($telepon) || empty($email) || empty($alamat) || empty($file_name)){
                $msg = "Form Tidak Boleh Kosong!";
                return $msg;
            }elseif($file_size > 1048567){
                $msg = "Ukuran file tidak boleh lebih dari 1 mb!";
                return $msg;
            }elseif(in_array($file_ext, $permited) == false){
                $msg = "Anda hanya bisa mengupload file berjenis".implode(', ', $permited);
                return $msg;
            }else{
                move_uploaded_file($file_temp, $upload_image);

                $query = "INSERT INTO `tbl_register`(`name`, `telepon`, `email`, `alamat`, `foto`) VALUES ('$name', '$telepon', '$email', '$alamat', '$upload_image')";
            
                $result = $this->db->tambah_data($query);

                if ($result) {
                    $msg = "Registrasi Berhasil!";
                    return $msg;
                }else {
                    $msg = "Maaf, Registrasi Anda Gagal!";
                    return $msg;
                }
            }
        }

        public function allStudent(){
            $query = "SELECT * FROM tbl_register ORDER BY id DESC";
            $result = $this->db->pilih_data($query);
            return $result;
        }

        public function getStdById($id){
            $query = "SELECT * FROM tbl_register WHERE id = '$id'";
            $result = $this->db->pilih_data($query);
            return $result;
        }

        // update
        public function updateStudent($data, $file, $id) {
            $name = $data['name'];
            $telepon = $data['telepon'];
            $email = $data['email'];
            $alamat = $data['alamat'];

            $permited = array('jpg', 'jpeg', 'img', 'png', 'pdf');
            $file_name = $file['foto']['name'];
            $file_size = $file['foto']['size'];
            $file_temp = $file['foto']['tmp_name'];

            $div = explode('.', $file_name);
            $file_ext = strtolower(end($div));
            $unique_image = substr(md5(time()),0,10).'.'.$file_ext;
            $upload_image = "upload/".$unique_image;

            if (empty($name) || empty($telepon) || empty($email) || empty($alamat)) {
                $msg = "Form Tidak Boleh Kosong!";
                return $msg;
            }if (!empty($file_name)) {
                if($file_size > 1048567) {
                    $msg = "Ukuran file tidak boleh lebih dari 1 mb!";
                    return $msg;
                } elseif (in_array($file_ext, $permited) == false) {
                    $msg = "Anda hanya bisa mengupload file berjenis ".implode(', ', $permited);
                    return $msg;    
                } else {
                    $img_query = "SELECT * FROM tbl_register WHERE id = '$id'";
                    $img_res = $this->db->pilih_data($img_query);
                    if ($img_res) {
                        while ($row = mysqli_fetch_assoc($img_res)) {
                            $photo = $row['foto'];
                            unlink($photo);
                        }
                    }

                    move_uploaded_file($file_temp, $upload_image);

                    $query = "UPDATE tbl_register SET name='$name', telepon='$telepon', email='$email', foto='$upload_image', alamat='$alamat' WHERE id = '$id'";

                    $result = $this->db->tambah_data($query);

                    if ($result) {
                        $msg = "Update Data Siswa Berhasil!";
                        return $msg;
                    } else {
                        $msg = "Maaf, Update Data Siswa Gagal!";
                        return $msg;
                    }
                }
            } else {
                $query = "UPDATE tbl_register SET name='$name', telepon='$telepon', email='$email', alamat='$alamat' WHERE id = '$id'";

                $result = $this->db->tambah_data($query);

                if ($result) {
                    $msg = "Update Data Siswa Berhasil!";
                    return $msg;
                } else {
                    $msg = "Maaf, Update Data Siswa Gagal!";
                    return $msg;
                }
            }
        }

        // delete
        public function delStudent($id){
            $img_query = "SELECT * FROM tbl_register WHERE id = '$id'";
            $img_res = $this->db->pilih_data($img_query);
            if ($img_res) {
                while ($row = mysqli_fetch_assoc($img_res)) {
                    $photo = $row['foto'];
                    unlink($photo);
                }
            }

            $delete_query = "DELETE FROM tbl_register WHERE id = '$id'";
            $delete = $this->db->hapus_data($delete_query);
            if ($delete) {
                $msg = "Berhasil Menghapus Data Siswa!";
                return $msg;
            } else {
                $msg = "Gagal Menghapus Data Siswa!";
                return $msg;
            }
        }
    }
?>
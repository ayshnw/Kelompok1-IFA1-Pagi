<?php
session_start();
$connection = mysqli_connect("localhost","root","","adminpanel");

if(isset($_POST['registerbtn']))
{
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $cpassword = $_POST['confirmpassword'];

    if($password === $cpassword)
    {
        $query = "INSERT INTO register (username,email,password) VALUES ('$username','$email','$password')";
        $query_run = mysqli_query($connection, $query);
    
        if($query_run)
        {
            //echo "Saved";
            $_SESSION['success'] = "Admin berhasil ditambahkan";
            header('Location: register.php');
        }
        else
        {
            $_SESSION['status'] = "Admin tidak berhasil ditambahkan";
            header('Location: register.php');
        }
    }
    else
    {
        $_SESSION['status'] = "Password dan Konfirmasi Password Tidak Cocok";
            header('Location: register.php');
    }    
}

?>
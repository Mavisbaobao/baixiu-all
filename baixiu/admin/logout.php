<?php
 require '../functions.php';
 // print_r($_SESSION['user_info']);
 unset($_SESSION['user_info']);
 header('Location:/admin/login.php');
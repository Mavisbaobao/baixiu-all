<?php
//上传图片
require '../functions.php';
  if(!file_exists('../uploads/thums')){
    mkdir('../uploads/thums');
  }
  $filename=time();
  $ext=explode('.', $_FILES['avatara']['name'])[1];

  $path='/uploads/thums/'.$filename.'.'.$ext;
  // $user_id=$_SESSION['user_info']['id'];


  //将二进制的文件从临时目录到
  move_uploaded_file($_FILES['avatara']['tmp_name'], '..'.$path);
  // update('posts',array('avatara'=>$path),$user_id);
  echo $path;
  exit();

<?php
require '../functions.php';
// print_r($_FILLES);
if(!file_exists("../uploads")){
	mkdir('../uploads');
}
$filename=time();

// print_r($_FILLES['avatar']['name']);
//将数组按照 . 分割，，取最后部分
$ext= explode('.', $_FILES['avatar']['name'])[1];

$path='/uploads/'.$filename.'.'.$ext;
$user_id=$_SESSION['user_info']['id'];

//转存到上传文件到指定目录
move_uploaded_file($_FILES['avatar']['tmp_name'], '..'.$path);
update("users",array('avatar'=>$path),$user_id);

//将上传目录返回给浏览器
echo $path;

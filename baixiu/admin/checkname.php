<?php
	include '../functions.php';
  // print_r($_POST);
  // print_r(json_encode($_POST['arr']));
  $a=implode('', $_POST['arr']);
  
  $sql='SELECT * FROM users WHERE email="'.$a. '"';
  // echo $sql;
  header('Content-Type:application/json');

  $list= query($sql);
  // print_r($list[0]);

  if(empty($list[0])){
    $info=array(
      'code'=>666 ,
      'message'=>'邮箱合法'
    );
    echo json_encode($info);
  }else{
     $info=array(
      'code'=>555,
      'message'=>'该用户已注册'
    );
    echo json_encode($info);
  }
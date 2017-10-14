<?php
require '../functions.php';

$message='';

if(!empty($_POST)){
$email=$_POST['email'];
$password=$_POST['password'];

$rows=query('SELECT * FROM users WHERE email="'.$email.'"');
  // print_r(($rows[0]));
  // exit();

   if($rows[0]){
      if($rows[0]['password']==$password){
        
        session_start();

        $_SESSION['user_info']=$rows[0];
  
         header('Location:/admin');
       
        exit();

        }else{
         $message="邮箱或者密码错误！";
        }
    }else{
      $message="用户名错误！";
    }

}

;?>

<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title>Sign in &laquo; Admin</title>
  <link rel="stylesheet" href="../assets/vendors/bootstrap/css/bootstrap.css">
  <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
  <div class="login">
    <form class="login-wrap" method="post" action="./login.php">
      <img class="avatar" src="../assets/img/default.png">
      <!-- 有错误信息时展示 -->
      <?php if(!empty($message)) { ?>
      <div class="alert alert-danger">
        <strong>错误</strong>
        <?php echo $message ;?>
      </div>
      <?php } ?>
      <div class="form-group">
        <label for="email" class="sr-only">邮箱</label>
        <input id="email" name="email" type="email" class="form-control" placeholder="邮箱" autofocus>
      </div>
      <div class="form-group">
        <label for="password" class="sr-only">密码</label>
        <input id="password" name="password" type="password" class="form-control" placeholder="密码">
      </div>
       <input class="btn btn-primary btn-block" type="submit" value="登录">
      <!-- <a class="btn btn-primary btn-block" href="index.html">登 录</a> -->
    </form>
  </div>
</body>
</html>

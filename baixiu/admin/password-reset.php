<?php
 require '../functions.php';
   $active='';
  $active1='';
  $actives=array();
  $message="";
  $prepassword=$_SESSION['user_info']['password'];
  $id=$_SESSION['user_info']['id'];
  if(!empty($_POST)){
    // 先判断旧密码是不是填写正确
    
    $old=$_POST["prepassword"];
    $now=$_POST['newpassword'];
    $confirm=$_POST['confirm'];

    if($prepassword!=$old){
      $message="输入的旧密码错误！"; 
    }else{
      // 在判断两次新密码是不是一样
      if($_POST['newpassword'] != $_POST['confirm']){
        $message="两次新密码不一致！";
      }else{
          // $arr=array();
          $arr=array('password'=>$now);

         $result= update('users',$arr,$id);
        header('Location: /admin/login.php');
      }    
    }
  }

?>


<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title>Password reset &laquo; Admin</title>
  <?php include "./inc/css.php";?>
  <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
  <div class="main">
    <?php include "./inc/nav.php";?>
    <div class="container-fluid">
      <div class="page-title">
        <h1>修改密码</h1>
      </div>
      <!-- 有错误信息时展示 -->
      <?php if(!empty($message)){?>
      <div class="alert alert-danger">
        <strong>错误！</strong><?php echo $message;?>
      </div>
      <?php }?>
      <form class="form-horizontal" method="post" action="./password-reset.php">
        <div class="form-group">
          <label for="old" class="col-sm-3 control-label">旧密码</label>
          <div class="col-sm-7">
            <input id="old" class="form-control" name="prepassword" type="password" placeholder="旧密码">
          </div>
        </div>
        <div class="form-group">
          <label for="password" class="col-sm-3 control-label" >新密码</label>
          <div class="col-sm-7">
            <input id="password" class="form-control" name="newpassword" type="password" placeholder="新密码">
          </div>
        </div>
        <div class="form-group">
          <label for="confirm" class="col-sm-3 control-label">确认新密码</label>
          <div class="col-sm-7">
            <input id="confirm" class="form-control" name="confirm"  type="password" placeholder="确认新密码">
          </div>
        </div>
        <div class="form-group">
          <div class="col-sm-offset-3 col-sm-7">
            <button type="submit" class="btn btn-primary">修改密码</button>
          </div>
        </div>
      </form>
    </div>
  </div>

  <?php include "./inc/aside.php";?>

  <?php include "./inc/script.php";?>
  <script>
      // $(".form-horizontal").on('submit',function(){
      //   $password=$("#old").val();
      //   

      //   // if($("#old").val()==){
      //   //   alert(1);
      //   // }else{}
      //   alert($pre);
      // })
  </script>
</body>
</html>

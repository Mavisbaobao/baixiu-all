<?php
  require '../functions.php';
   $active='';
  $active1='';
  $actives=array();

  checkLogin();
  $user_id=$_SESSION['user_info']['id'];

  $sql='SELECT * FROM users WHERE id= '.$user_id;
  $rows=query('SELECT * FROM users WHERE email="'.$email.'"');
   
  // print_r($sql);
  // exit();
  $rows=query($sql);
  if(!empty($_POST)){
    //以post的方式提交
    unset($_POST['email']);//不允许更新email

    // print_r($_POST);
    // exit();
    $result=update('users',$_POST,$user_id);
    if($result){
     
    $_SESSION['user_info']=$rows[0];
  
    header('Location: /admin/profile.php');
    exit();
    }
    $message="更新失败！";

  }



?>


<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title>Dashboard &laquo; Admin</title>
  <?php include './inc/css.php';?>
  <link rel="stylesheet" href="../assets/css/admin.css">

</head>
<body>


  <div class="main">
    <?php include './inc/nav.php';?>
    <div class="container-fluid">
      <div class="page-title">
        <h1>我的个人资料</h1>
      </div>
      <!-- 有错误信息时展示 -->
      <?php if(isset($message)){?>
      <div class="alert alert-danger">
        <strong>错误！</strong><?php echo $message?>
      </div>
      <?php }?>
      <form class="form-horizontal" action="/admin/profile.php" method="post">
        <div class="form-group">
          <label class="col-sm-3 control-label">头像</label>
          <div class="col-sm-6">
            <label class="form-image">
              <input id="avatar" type="file">

              <?php if($rows[0]['avatar']) {?>
              <img class="preview" src="<?php echo $rows[0]['avatar'] ;?>">
              <?php }else { ?>
              <img src="/assets/img/default.png">
              <?php } ?>



              <i class="mask fa fa-upload"></i>
            </label>
          </div>
        </div>
        <div class="form-group">
          <label for="email" class="col-sm-3 control-label">邮箱</label>
          <div class="col-sm-6">

            <input id="email" class="form-control" name="email" type="type" value="<?php echo $rows[0]['email'];?>" placeholder="邮箱" disabled>

            <p class="help-block">登录邮箱不允许修改</p>
          </div>
        </div>
        <div class="form-group">
          <label for="slug" class="col-sm-3 control-label">别名</label>
          <div class="col-sm-6">
            <input id="slug" class="form-control" name="slug" type="type" value="<?php echo $rows[0]['slug'];?>" placeholder="slug">
            <p class="help-block">https://zce.me/author/<strong>zce</strong></p>
          </div>
        </div>
        <div class="form-group">
          <label for="nickname" class="col-sm-3 control-label">昵称</label>
          <div class="col-sm-6">
            <input id="nickname" class="form-control" name="nickname" type="type" value="<?php echo $rows[0]['nickname'];?>" placeholder="昵称">
            <p class="help-block">限制在 2-16 个字符</p>
          </div>
        </div>
        <div class="form-group">
          <label for="bio"  class="col-sm-3 control-label">简介</label>
          <div class="col-sm-6">
            <textarea id="bio" name="bio" class="form-control" placeholder="Bio" cols="30" rows="6"><?php echo $rows[0]['bio'];?></textarea>
          </div>
        </div>
        <div class="form-group">
          <div class="col-sm-offset-3 col-sm-6">
            <button type="submit" class="btn btn-primary">更新</button>
            <a class="btn btn-link" href="password-reset.php">修改密码</a>
          </div>
        </div>
      </form>
    </div>
  </div>
  <?php include './inc/aside.php';?>
  <?php include './inc/script.php';?>

  

  <script>
    $('#avatar').on('change',function(){
      // alert(999);
      var xhr=new XMLHttpRequest;
      var data=new FormData();//转化成二进制
      data.append('avatar',this.files[0]);
      xhr.open('post','/admin/upfile.php');
      xhr.send(data);
      xhr.onreadystatechange=function(){
        if(xhr.readyState==4 && xhr.status==200){
          // console.log(xhr.responseText);
          $('.preview').attr('src',xhr.responseText);
        }
      }



    })
    
  </script>
  

</body>
</html>

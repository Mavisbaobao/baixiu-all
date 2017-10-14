<?php 

  require '../functions.php';
  checkLogin();
  $active='users';
  $active1='';
  $actives=array();

  
  $message='';
  $action=isset($_GET['action'])?$_GET['action']:'add';

  if(!empty($_POST)){

   /*  $status="unactivated";
    $slug=$_POST['slug'];
    $email=$_POST['email'];
    $nickname=$_POST['nickname'];
    $password=$_POST['password'];
    $mysql='insert into users(id,slug,email,password,status)
            VALUES(null,"'.$slug.'","'.$email.'","'.$password.'","'.$status.'")';
    $result=insert($mysql);*/
    if($action == 'add'){
      $_POST['status']="unactivated";
      $result=insert("users",$_POST);
    
      if($result){
        header('Location:/admin/users.php');

      }else{
        $message="添加用户失败！";
      }

    }
    if($action=='update'){
      //获取用户的id根据用户的id对数据进行修改
      
     
      $id=$_POST['id'];

      //id字段是主键不能修改版
      unset($_POST['id']);
      $result=update('users',$_POST,$id);
      // print_r($result);
      // exit();
      if($result){
        header('Location:/admin/users.php');
        exit();
      }
    }
    if($action=='deleteAll'){
      // print_r(9999);
      // echo json_encode($_POST);
      // print_r($_POST['ids']);
      // exit();
      $sql='DELETE FROM users WHERE id in ('.implode(',', $_POST['ids']).')';
      $result=delete($sql);
      //响应头设置 jquery自定解析json
      header('Content-Type:application/json');
      if($result){
        $info=array('code'=>10000,'message'=>'删除成功');
        echo json_encode($info);
      }else{
        $info=array('code'=>10001,'message'=>'删除失败！');
        echo json_encode($info);
      }
      exit();
    }
  }

 $list=query("SELECT * FROM users");

//编辑/删除操作
    $user_id=isset($_GET['user_id']) ? $_GET['user_id']:'';

    if($action=='edit'){
      //编辑操作
      $action='update';
    
      $rows=query("SELECT * FROM users WHERE id=".$user_id);

    }else if($action=='delete'){
      //执行删除操作
      $result=delete('delete from users where id='.$user_id);
      if ($result) {
        header('Location:/admin/users.php');
        exit();
      }
    }
 

;?>

<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title>Users &laquo; Admin</title>
  <?php include "./inc/css.php" ;?>
  <link rel="stylesheet" href="../assets/css/admin.css">

</head>
<body>
  <div class="main">
   <?php include  './inc/nav.php' ;?>
    <div class="container-fluid">
      <div class="page-title">
        <h1>用户</h1>
      </div>
      <!-- 有错误信息时展示 -->
      <?php if(!empty($message)){  ?>
      <div class="alert alert-danger">
        <strong>错误！</strong>
        <?php echo $message ;?>
      </div>
      <?php }?>
      <div class="row">
        <div class="col-md-4">
          <form method="post" action="/admin/users.php?action=<?php echo $action ;?>" >
            <h2>添加新用户</h2>
            <div class="form-group">
              <label for="email">邮箱</label>
              <?php if($action!='add'){?>
              <input type="hidden" name="id" value="<?php echo $rows[0]['id'] ;?>">
              <?php } ?>


              <input id="email" class="form-control" name="email" type="email" value="<?php echo isset($rows[0]['email'])? $rows[0]['email']:"" ;?>" placeholder="邮箱">
               <span id="warning" style="color: red"></span>
            </div>
            <div class="form-group">
              <label for="slug">别名</label>
              <input id="slug" class="form-control" name="slug" type="text" placeholder="slug" value="<?php echo isset($rows[0]['slug'])? $rows[0]['slug']:"" ;?>">
              <p class="help-block">https://zce.me/author/<strong>slug</strong></p>
            </div>
            <div class="form-group">
              <label for="nickname">昵称</label>
              <input id="nickname" class="form-control" name="nickname" type="text" placeholder="昵称" value="<?php echo isset($rows[0]['nickname'])? $rows[0]['nickname']:"" ;?>">
            </div>
            <div class="form-group">
              <label for="password">密码</label>
              <input id="password" class="form-control" name="password" type="text" placeholder="密码" value="<?php echo isset($rows[0]['password'])? $rows[0]['password']:"" ;?>">
            </div>
            <div class="form-group">
              <button class="btn btn-primary" type="submit">添加</button>
            </div>
          </form>
        </div>
        <div class="col-md-8">
          <div class="page-action">
            <!-- show when multiple checked -->
            <a class="btn btn-danger btn-sm  delete" href="javascript:;" style="display: none" >批量删除</a>
          </div>
          <table class="table table-striped table-bordered table-hover">
            <thead>
               <tr>
                <th class="text-center" width="40">
                <input type="checkbox" id="toggle"></th>
                <th class="text-center" width="80">头像</th>
                <th>邮箱</th>
                <th>别名</th>
                <th>昵称</th>
                <th>状态</th>
                <th class="text-center" width="100">操作</th>
              </tr>
            </thead>
            <tbody>
            <?php foreach($list as $key=>$val){?>
              <tr>
                <td class="text-center"><input type="checkbox" class="chk" value="<?php echo $val['id'] ; ?>"></td>
                <td class="text-center"><img class="avatar" src="<?php echo $val['avatar'];  ?>"></td>
                <td><?php echo $val['email'];?></td>
                <td><?php echo $val['slug']; ;?></td>
                <td><?php echo $val
                ['nickname'] ;?></td>
                <?php if($val['status']=='activated') {?>
                <td>已激活</td>
               <?php } else if($val['status'] == 'unactivated') { ?>
                <td>未激活</td>
                <?php } else if($val['status'] == 'forbidden') { ?>
                <td>已禁用</td>
                <?php } else { ?>
                <td>已删除</td>
                <?php } ?>

                

                <td class="text-center">
                  <a href="/admin/users.php?action=edit&user_id=<?php echo $val['id'] ;?>" class="btn btn-default btn-xs">编辑</a>
                  <a href="/admin/users.php?action=delete&user_id=<?php echo $val['id'] ;?>" class="btn btn-danger btn-xs">删除</a>
                </td>
              </tr>
             <?php } ;?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

 <?php include './inc/aside.php' ;?>

 <?php include './inc/script.php' ;?>
 <script>
   $('#toggle').on('click',function(){
    //这里的this是指的是原生的dom
    // 如果全选，取消全选
    if(this.checked){//全选
      $('.chk').prop('checked',true);
      $('.delete').show();
    }else{//取消全选
      $('.chk').prop('checked',false);
      $('.delete').hide();
    }
   });
   //单个选择
   $('.chk').on('change',function(){
        // 获得当前选中用户的个数
      var size=$('.chk:checked').size();
      // 如果大于0就显示批量按钮
      if(size>0){
        $('.delete').show();
        return;
      }
      //如果小于等于0 则隐藏批量按钮
      $('.delete').hide();

   });
   

   $('.delete').on('click',function(){

        var ids=[];
        $('.chk:checked').each(function(){
          // console.log($(this).val());
          ids.push($(this).val());
        });
         // console.log(ids);

        $.ajax({
          url:'/admin/users.php?action=deleteAll',
          type:'post',
          //将所有的选中的用户的id传给后端
          data:{ids : ids},
          success:function(info){
            // console.log(info);
            alert(info.message);
            if (info.code==10000) {
              location.reload();
            }


          }
        });
   })
  //添加用户邮箱是不是重复
     $('#email').blur(function(){
      var a=$("#email").val();
      var arr=[];
      arr.push(a);
      // console.log(arr);
    $.ajax({
      url:'./checkname.php',
      data:{arr:arr},
      type:"post",
      success:function(info){
        // var message=json_parse(info);
          // console.lo时g(info);
          // alert(message);

           // $('#warning').html(info.message);
           if(info.code==666){
             $('#warning').css({color:'green'});
             $('#warning').html(info.message);
            }else{
                $('#warning').css({color:'red'});
                $('#warning').html(info.message);
            }
      }
      })
    })




 </script>
</body>
</html>

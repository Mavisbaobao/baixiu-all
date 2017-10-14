<?php
  require '../functions.php';
  $active='';
  $active1='setting';
  $actives=array('nav','pic','setting');
  //查询网站的设置信息并显示在页面
  $rows=query("SELECT * FROM options WHERE id<9");

  //头像上传预览功能
  if(!empty($_FILES)){
    print_r($_FILES['logo']);
    exit();

    $path='../uploads/logo/';
    //判断路径是不是存在
    if(!file_exists($path)){
      mkdir($path);
    }else{
      //获取文件的后缀
      $ext=explode('.', $_FILES['logo']['tmp_name'])[1];
      //定义新的文件名
      $filename=time();

      //拼凑成完整的文件名
      $realpath=$filename.'.'.$ext;
      print_r($realpath);
      exit();
      //将文件转移到项目的文件中
      move_uploaded_file($_FILES['logo']['tmp_name'],$realpath);
      echo $realpath;
    }
  }









  // print_r($rows);
  // exit();

?>


<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title>Settings &laquo; Admin</title>
   <?php include "./inc/css.php" ;?>
  <link rel="stylesheet" href="../assets/css/admin.css">
  <script src="../assets/vendors/nprogress/nprogress.js"></script>
</head>
<body>
 

  <div class="main">
     <?php include "./inc/nav.php" ;?>
    <div class="container-fluid">
      <div class="page-title">
        <h1>网站设置</h1>
      </div>
      <!-- 有错误信息时展示 -->
      <!-- <div class="alert alert-danger">
        <strong>错误！</strong>发生XXX错误
      </div> -->
      <form class="form-horizontal">
        <div class="form-group">
          <label for="site_logo" class="col-sm-2 control-label">网站图标</label>
          <div class="col-sm-6">
            <input id="site_logo" name="site_logo" type="hidden">
            <label class="form-image">
              <input id="logo" type="file">
              <img src="<?php echo $rows[1]['value'];?>">
              <i class="mask fa fa-upload"></i>
            </label>
          </div>
        </div>
        <div class="form-group">
          <label for="site_name" class="col-sm-2 control-label">站点名称</label>
          <div class="col-sm-6">
            <input id="site_name" name="site_name" class="form-control" type="type" placeholder="<?php echo $rows[2]['value']?>">
          </div>
        </div>
        <div class="form-group">
          <label for="site_description" class="col-sm-2 control-label">站点描述</label>
          <div class="col-sm-6">
            <textarea id="site_description" name="site_description" class="form-control" placeholder="<?php echo $rows[3]['value']?>" cols="30" rows="6"></textarea>
          </div>
        </div>
        <div class="form-group">
          <label for="site_keywords" class="col-sm-2 control-label">站点关键词</label>
          <div class="col-sm-6">
            <input id="site_keywords" name="site_keywords" class="form-control" type="type" placeholder="<?php echo $rows[4]['value']?>">
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-2 control-label">评论</label>
          <div class="col-sm-6">
            <div class="checkbox">
              <label><input id="comment_status" name="comment_status" type="checkbox" <?php if($rows[6]['value']==1) {?>checked <?php }?>>开启评论功能</label>
            </div>
            <div class="checkbox">
              <label><input id="comment_reviewed" name="comment_reviewed" type="checkbox" <?php if($rows[7]['value']==1) {?>checked <?php }?>>评论必须经人工批准</label>
            </div>
          </div>
        </div>
        <div class="form-group">
          <div class="col-sm-offset-2 col-sm-6">
            <button type="submit" class="btn btn-primary">保存设置</button>
          </div>
        </div>
      </form>
    </div>
  </div>

   <?php include "./inc/aside.php" ;?>

   <?php include "./inc/script.php" ;?>
   <script>
     $("#logo").change(function(){
        var data=new FormData();
        data.append('logo',this.files[0]);
        var xhr=new XMLHttpRequest();
        xhr.open('post','/admin/settings.php');
        xhr.send(data);
        xhr.onreadystatechange=function(){
       
          if(xhr.readyState==4&&xhr.status==200){ 
            console.log(xhr.responseText);
          }
        }

     })
   </script>
</body>
</html>

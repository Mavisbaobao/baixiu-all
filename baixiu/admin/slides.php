<?php
  require '../functions.php';
   $active='';
  $active1='pic';
  $actives=array('nav','pic','setting');
  $message='';
  $action = isset($_GET['action'])?$_GET['action']:''; 

//页面显示
  $sql="SELECT `value` FROM options WHERE  `key`='home_slides'";
  $result=query($sql);
  $json=$result[0]['value'];
  //转化成数组
  $data=json_decode($json,true);
  //将生成的数组中的单元索引不更新的问题问题
  $data=array_values($data);



if(!empty($_FILES)){
  //文件上传
  $path='../uploads/slides/';
  //检测目录是不是存在，不存在就创建目录
  if(!file_exists($path)){
    mkdir($path);
  }
  $ext=explode('.', $_FILES['image']['name'])[1];
  //生成文件名，使用时间戳
  $filename=time();

  //将文件名和后缀名拼凑成完整的文件名
  $realpath=$path.$filename.'.'.$ext;
  //转移上传目录
  move_uploaded_file($_FILES['image']['tmp_name'],$realpath);

  //返回绝对路径
  echo substr($realpath, 2);
  exit();
}

//添加
if(!empty($_POST)){
  $data[]=$_POST;
  //将数组转化成json字符串
  $json=json_encode($data,JSON_UNESCAPED_UNICODE);
  $result=update('options',array('value'=>$json),10);
 if($result){
  header('Location:/admin/slides.php');
  exit();
 }else{
  $message='添加失败！';
 }
}
//删除
if(!empty($action)){
     $index=$_GET['id'];
    
   unset($data[$index]);
    
    $json=json_encode($data,JSON_UNESCAPED_UNICODE);
    
    //执行更新操作
    $result = update('options',array('value'=>$json),10);
    if($result){
      header('Location:/admin/slides.php');

      exit();
    }else{
      $message="删除错误";
    }
}



?>



<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title>Slides &laquo; Admin</title>
  <?php include "./inc/css.php" ;?>
  <link rel="stylesheet" href="../assets/css/admin.css">
  <script src="../assets/vendors/nprogress/nprogress.js"></script>
</head>
<body>
  

  <div class="main">
    <?php include "./inc/nav.php" ;?>
    <div class="container-fluid">
      <div class="page-title">
        <h1>图片轮播</h1>
      </div>
      <!-- 有错误信息时展示 -->
      <!-- <div class="alert alert-danger">
        <strong>错误！</strong>发生XXX错误
      </div> -->
      <div class="row">
        <div class="col-md-4">
          <form action="/admin/slides.php" method="post">
            <h2>添加新轮播内容</h2>
            <div class="form-group">
              <label for="image">图片</label>
              <!-- show when image chose -->
              <img class="help-block thumbnail" style="display: none">
              <input id="image" class="form-control"  type="file">
              <input type="hidden" name="image">
            </div>
            <div class="form-group">
              <label for="text">文本</label>
              <input id="text" class="form-control" name="text" type="text" placeholder="文本">
            </div>
            <div class="form-group">
              <label for="link">链接</label>
              <input id="link" class="form-control" name="link" type="text" placeholder="链接">
            </div>
            <div class="form-group">
              <button class="btn btn-primary" type="submit">添加</button>
            </div>
          </form>
        </div>
        <div class="col-md-8">
          <div class="page-action">
            <!-- show when multiple checked -->
            <a class="btn btn-danger btn-sm" href="javascript:;" style="display: none">批量删除</a>
          </div>
          <table class="table table-striped table-bordered table-hover">
            <thead>
              <tr>
                <th class="text-center" width="80">序号</th>
                <th class="text-center">图片</th>
                <th>文本</th>
                <th>链接</th>
                <th class="text-center" width="100">操作</th>
              </tr>
            </thead>
            <tbody>
            <?php foreach($data as $key=>$val){?>
              <tr>
                <td class="text-center"><?php echo $key+1;?></td>
                <td class="text-center"><img class="slide" src="<?php echo $val['image']?>"></td>
                <td><?php echo $val['text']?></td>
                <td><?php echo $val['link']?></td>
                <td class="text-center">
                  <a href="/admin/slides.php?action=delete&id=<?php echo $key;?>" class="btn btn-danger btn-xs">删除</a>
                </td>
              </tr>
             <?php }?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

    <?php include "./inc/aside.php" ;?>

  
      <?php include "./inc/script.php" ;?>
      <script> 
       $("#image").change(function(){
        // this.files[0]//第一个上传文件的信息
        var data=new FormData();
        data.append('image',this.files[0]);
        var xhr=new XMLHttpRequest();
        xhr.open('post','/admin/slides.php');
        xhr.send(data);
        xhr.onreadystatechange=function(){
          if(xhr.readyState==4 && xhr.status==200){
            console.log(xhr.responseText);
            //预览效果
            $('.thumbnail').attr(
              'src',xhr.responseText,
            ).show();
            $('input[name="image"]').val(xhr.responseText);

        }

        }
        

       })


      </script>

</body>
</html>

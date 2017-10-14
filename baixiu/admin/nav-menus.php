<?php
  require '../functions.php';
  $active='';
  $active1='nav';
  $actives=array('nav','pic','setting');

  $message='';
  //查询所有的导航的菜单
  $sql='SELECT `value` FROM options WHERE `key`="nav_menus"';

  $lists=query($sql); //得到的是json的字符串。
  $json=$lists[0]['value'];
  //第二个 参数是强制转化成数组的形式
  //导航是以json格式存储的，第二个参数为true，强制转化成数组
  $data=json_decode($json,true);
  // print_r($data);
  // exit();
  
 //删除操作
  $action=isset($_GET['action'])?$_GET['action']:'add';
  if($action=='delete'){
    $index=$_GET['key'];
    unset($data[$index]);//从数组中删除
    //将数组转化成json进行重新存储
    //JSON_UNESCAPED_UNICODE设置为汉字不进行编码
    $json=json_encode($data,JSON_UNESCAPED_UNICODE);
    //执行更新操作
    $result = update('options',array('value'=>$json),9);
    if($result){
      header('Location:/admin/nav-menus.php');

      exit();
    }else{
      $message="删除错误";
    }
  }
  //添加操作
  if(!empty($_POST)){
    // $_POST是表单提交上来的数据
    // 将表单提交上来的数据存进数组中
    $data[]=$_POST;
    //因为导航在数据库中是以json的形式存在的所以，转化成json格式
    $jsons=json_encode($data,JSON_UNESCAPED_UNICODE);
    //执行更新操作
    $result=update("options",array('value'=>$jsons),9);
    if($result){
      header("Location:/admin/nav-menus.php");
      exit();
    }else{
      $message="添加失败！";
    }

    // print_r($jsons);
    // exit();
    

  }


?>


<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title>Navigation menus &laquo; Admin</title>
  <?php include "./inc/css.php";?>
  <link rel="stylesheet" href="../assets/css/admin.css">
  <script src="../assets/vendors/nprogress/nprogress.js"></script>
</head>
<body>


  <div class="main">
     <?php include "./inc/nav.php";?>
    <div class="container-fluid">
      <div class="page-title">
        <h1>导航菜单</h1>
      </div>
      <!-- 有错误信息时展示 -->
      <?php if($message!='') {?>
      <div class="alert alert-danger">
        <strong>错误！</strong><?php echo $mesage;?>
      </div>
      <?php } ?>
      <div class="row">
        <div class="col-md-4">
          <form method="post" action="/admin/nav-menus.php" >
            <h2>添加新导航链接</h2>
            <div class="form-group">
              <label for="text">文本</label>
              <input id="text" class="form-control" name="text" type="text" placeholder="文本">
            </div>
            <div class="form-group">
              <label for="title">标题</label>
              <input id="title" class="form-control" name="title" type="text" placeholder="标题">
            </div>
             <div class="form-group">
              <label for="title">图标</label>
              <input id="title" class="form-control" name="icon" type="text" placeholder="图标">
            </div>
            <div class="form-group">
              <label for="href">链接</label>
              <input id="href" class="form-control" name="link" type="text" placeholder="链接">
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
                <th class="text-center" width="40"><input type="checkbox"></th>
                <th>文本</th>
                <th>标题</th>
                <th>链接</th>
                <th class="text-center" width="100">操作</th>
              </tr>
            </thead>
            <tbody>
            <?php foreach($data as $key =>$val){?>
              <tr>
                <td class="text-center"><input type="checkbox"></td>
                <td><i class="<?php echo $val['icon'];?>"></i><?php echo $val['text'] ;?></td>
                <td><?php echo $val['title'] ;?></td>
                <td><?php echo $val['link'] ;?></td>
                <td class="text-center">
                  <a href="/admin/nav-menus.php?action=delete&key=<?php echo $key;?>" class="btn btn-danger btn-xs">删除</a>
                </td>
              </tr>
             <?php }?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

   <?php include "./inc/aside.php";?>

   <?php include "./inc/script.php";?>
 
</body>
</html>

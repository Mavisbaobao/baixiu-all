<?php 

require  '../functions.php';
//检测登录
  checkLogin();
  //定义一个变量
  $active='dashboard';
  $actives=array();

  $active1='';
  //数据库中文章的总量
  $presult=query("SELECT count(*) AS totalPost FROM 
    posts");
  //草稿的数量
  $dresult=query("SELECT count(*) AS totalDrafted FROM posts WHERE status='drafted'");
  //评论的数量
  $aresult=query('SELECT count(*)AS totalAn  FROM comments');
  //分类的数量
  $sresult=query("SELECT count(*) AS totalSort FROM categories");
 
  $totalPost=$presult[0]['totalPost'];
  $totalDrafted=$dresult[0]['totalDrafted'];
  $totalAn=$aresult[0]['totalAn'];
  $totalSort=$sresult[0]['totalSort'];


;?>

<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title>Dashboard &laquo; Admin</title>
  <?php include "./inc/css.php" ;?>
  <link rel="stylesheet" href="../assets/css/admin.css">
 
</head>
<body>


  <div class="main">
    <?php include './inc/nav.php';?>
    <div class="container-fluid">
      <div class="jumbotron text-center">
        <h1>One Belt, One Road</h1>
        <p>Thoughts, stories and ideas.</p>
        <p><a class="btn btn-primary btn-lg" href="post-add.html" role="button">写文章</a></p>
      </div>
      <div class="row">
        <div class="col-md-4">
          <div class="panel panel-default">
            <div class="panel-heading">
              <h3 class="panel-title">站点内容统计：</h3>
            </div>
            <ul class="list-group">
              <li class="list-group-item"><strong><?php echo  $totalPost;?></strong>篇文章（<strong><?php echo  $totalDrafted;?></strong>篇草稿）</li>
              <li class="list-group-item"><strong><?php echo  $totalSort;?></strong>个分类</li>
              <li class="list-group-item"><strong><?php echo  $totalAn;?></strong>条评论（<strong>1</strong>条待审核）</li>
            </ul>
          </div>
        </div>
        <div class="col-md-4"></div>
        <div class="col-md-4"></div>
      </div>
    </div>
  </div>

  <?php include './inc/aside.php' ;?>
  <?php include './inc/script.php' ;?>

</body>
</html>

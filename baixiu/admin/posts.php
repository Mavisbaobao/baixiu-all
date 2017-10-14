<?php 
  require '../functions.php';
  checkLogin();
  $active='posts';
  $active1='';
  $actives=array('category','post','posts');
 
  //显示文章列表
  $sql='SELECT * FROM posts';
  $list=query($sql);



//查找分页显示

//获取数据库中的数据的条数
$countsql='SELECT count(*) AS total FROM posts';
$total=query($countsql)[0]['total'];

//每页显示的数据条数
$pageSize=10;

$pageCount=ceil($total/$pageSize);
//页面显示的个数
$pageLimit=6;


$currentPage=isset($_GET['page'])?$_GET['page']:1;
$prevPage=$currentPage-1;
$nextPage=$currentPage+1;


$start=$currentPage-floor($pageLimit/2);
// 起点的边界
$start=$start>1?$start:1;
$end=$start+$pageLimit-1;

$end=$end>=$pageCount?$pageCount:$end;
// print_r($pageCount);
// print_r($end);
// exit();
$start=$end-$pageLimit+1;
//起点的边界
$start=$start>1?$start:1;
$page=range($start, $end);
$offset=($currentPage-1)*$pageSize;

 $sql = 'SELECT  posts.id, posts.title, posts.created, posts.status, users.nickname, categories.name FROM posts LEFT JOIN users ON posts.user_id=users.id LEFT JOIN categories ON posts.category_id = categories.id LIMIT ' . $offset . ', ' . $pageSize;

  // 结果
  $lists = query($sql);

// 查找分类的种类显示在下拉菜单中
  $sort='SELECT * FROM categories';
  $sorts=query($sort);
  //查询所有的状态显示在页面的下拉菜单中
 
//删除操作
  $action=isset($_GET['action'])?$_GET['action']:'';
  $id=isset($_GET['pid'])?$_GET['pid']:'';
  if($action=='delete'){
    
    $sql='DELETE FROM posts WHERE id='.$id;
    $result=delete($sql);
    if($result){
      header('Location:/admin/posts.php');
      exit();
    }
  }
  


;?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title>Posts &laquo; Admin</title>
  <?php include  './inc/css.php';?>
  <link rel="stylesheet" href="../assets/css/admin.css">
 
</head>
<body>


  <div class="main">
   <?php include  './inc/nav.php';?>
    <div class="container-fluid">
      <div class="page-title">
        <h1>所有文章</h1>
        <a href="post-add.html" class="btn btn-primary btn-xs">写文章</a>
      </div>
      <!-- 有错误信息时展示 -->
      <!-- <div class="alert alert-danger">
        <strong>错误！</strong>发生XXX错误
      </div> -->
      <div class="page-action">
        <!-- show when multiple checked -->
        <a class="btn btn-danger btn-sm" href="javascript:;" style="display: none">批量删除</a>
        <form class="form-inline">
          <select name="" class="form-control input-sm">
            <option value="">所有分类</option>
            <?php foreach($sorts as $key=>$val){?>
              <option value="<?php echo $val['id']?>"><?php echo $val['name'];?></option>
           <?php }?>
          </select>
          <select name="" class="form-control input-sm">
            <option value="">所有状态</option>
            <option value="">草稿</option>
            <option value="">已发布</option>
          </select>
          <button class="btn btn-default btn-sm">筛选</button>
        </form>
        <ul class="pagination pagination-sm pull-right">
        <?php if($currentPage>1) {?>
          <li><a href="/admin/posts.php?page=<?php echo $prevPage?>">上一页</a></li>
        <?php }?>
        <?php foreach($page as $key=>$val) {?>
          <?php if($val==$currentPage){?>
          <li class="active"><a href="/admin/posts.php?page=<?php echo $val;?>"><?php echo $val;?></a></li>
          <?php }else{?>
           <li class=""><a href="/admin/posts.php?page=<?php echo $val;?>"><?php echo $val;?></a></li>
          <?php }?>
          <?php }?>
        <?php if($currentPage<$pageCount) {?>
          <li><a href="/admin/posts.php?page=<?php echo $nextPage?>">下一页</a></li>
        <?php }?>
        </ul>
      </div>
      <table class="table table-striped table-bordered table-hover">
        <thead>
          <tr>
            <th>序号</th>
            <th>标题</th>
            <th>作者</th>
            <th>分类</th>
            <th class="text-center">发表时间</th>
            <th class="text-center">状态</th>
            <th class="text-center" width="100">操作</th>
          </tr>
        </thead>
        <tbody>
        <?php foreach($lists as $key=>$val) {?>
          <tr>
            <td class="text-center"><?php echo $key+1;?></td>
            <td><?php echo $val['title'];?></td>
            <td><?php echo $val['nickname'];?></td>
            <?php if(!empty($val['name'])){?>
                <td><?php echo $val['name'];?></td>
            <?php } else{ ?>
                <td>未分类</td>
            <?php }?>

            <td class="text-center"><?php echo $val['created'];?></td>

            <?php if($val['status']=='published') {?>
            <td class="text-center">已发布</td>
            <?php }else{?>
            <td class="text-center">草稿</td>
            <?php }?>
            <td class="text-center">
              <a href="/admin/post-add.php?action=edit&pid=<?php echo $val['id'];?>" class="btn btn-default btn-xs">编辑</a>
              <a href="/admin/posts.php?action=delete&pid=<?php echo $val['id'];?>" class="btn btn-danger btn-xs">删除</a>
            </td>
          </tr>
         <?php }?>
        </tbody>
      </table>
    </div>
  </div>

 <?php include  './inc/aside.php';?>
  <?php include  './inc/script.php';?>
</body>
</html>

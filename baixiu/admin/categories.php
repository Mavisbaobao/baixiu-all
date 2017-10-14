<?php
  require '../functions.php';
  // print_r($_GET);
  // exit();
  checkLogin();
  $active='category';
  $active1='';
   $actives=array('category','post','posts');

  
  $message='';
   $title="添加新分类目录";
        $btn="添加";
  $cat_id=isset($_GET['cat_id'])?$_GET['cat_id']:0;
  $action=isset($_GET['action'])?$_GET['action']:'add';
 //显示列表
  $search='SELECT * FROM categories';
  $list=query($search);
  //添加操作
  if($action=='add'){
    // if($cat_id!=0){
        unset($_GET['cat_id']);
        unset($_GET['action']);
        $reslut=insert('categories',$_GET);
        // print_r($reslut);
        // exit();
        if($reslut){
          header('Location:/admin/categories.php');
          exit();
        }else{
          // $message='添加分类失败！';
        }
      // }
  }else if($action=='delete'){

      $delete='DELETE FROM categories WHERE id='.$cat_id;
      $reslut=delete($delete);
      if($reslut){
        header('Location:/admin/categories.php');
        exit();
      }else{
        $messag='删除失败！';
      }
  }else if($action=='edit'){
     $action='update';
     $sql='SELECT * FROM categories WHERE id='.$cat_id;
     $result=query($sql);
     $rows=$result[0];

     $title='修改分类目录';
     $btn='修改';
     // print_r($result);
     // exit();

  }else if($action=='update'){
     $cat_id=$_GET['cat_id'];
     unset($_GET['cat_id']);
     unset($_GET['action']);
     $result=update('categories',$_GET,$cat_id);
     if($result){
      // print_r(8888);
      // exit();
      header('Location:/admin/categories.php');
      exit();
     }else{
      $message="更新失败！";
     }

  }else if($action=='deleteAll'){
    // print_r($_GET['ids']);
    $row=$_GET['ids'];
    $str=implode(',', $row);
    // print_r($arr);
    // exit();
    header('Content-Type:application/json');
  
    $sql='DELETE FROM categories WHERE id in'.'('.$str.')';
    $result=delete($sql);
    // print_r($result);
    // exit();
    if($result){
      echo 1;
      exit();
    }else{
      echo 0;
      exit();
    }
    // print_r($sql);
  }
 
?>



<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title>Categories &laquo; Admin</title>hi

 <?php include './inc/css.php';?>

  <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
  <div class="main">
    <?php include './inc/nav.php';?>
    <div class="container-fluid">
      <div class="page-title">
        <h1>分类目录</h1>
      </div>
      <!-- 有错误信息时展示 -->
      <?php if(!empty($message)){?>
      <div class="alert alert-danger">
        <strong>错误！</strong><?php echo $message;?>
      </div>
      <?php } ?>
      <div class="row">
        <div class="col-md-4">
          <form action="/admin/categories.php" method="get" >
            <h2><?php echo $title;?></h2>
            <input type="hidden" name="action" value="<?php echo $action;?>">
            <input type="hidden" name="cat_id" value="<?php echo $cat_id;?>">
            <div class="form-group">
              <label for="name">名称</label>
              <input id="name" class="form-control" name="name" type="text" placeholder="分类名称" value="<?php echo isset($rows['name'])?$rows['name']:''  ;?>">
            </div>
            <div class="form-group">
              <label for="slug">别名</label>
              <input id="slug" class="form-control" name="slug" type="text" placeholder="slug" value="<?php echo isset($rows['slug'])?$rows['slug']:''  ;?>">
              <p class="help-block">https://zce.me/category/<strong>slug</strong></p>
            </div>
            <div class="form-group">
              <button class="btn btn-primary" type="submit"><?php echo $btn;?></button>
            </div>
          </form>
        </div>
        <div class="col-md-8">
          <div class="page-action">
            <!-- show when multiple checked -->
            <a class="btn btn-danger btn-sm deleteAll" href="javascript:;" style="display: none">批量删除</a>
          </div>
          <table class="table table-striped table-bordered table-hover">
            <thead>
              <tr>
                <th class="text-center" width="40">
                <input type="checkbox" id="toggle"></th>
                <th>名称</th>
                <th>Slug</th>
                <th class="text-center" width="100">操作</th>
              </tr>
            </thead>
            <tbody>
            <?php foreach ($list as $key => $val) {  ?>
              <tr>
                <td class="text-center">
                <input type="checkbox" class="chk" value="<?php echo $val['id'];?>"></td>
                <td><?php echo $val['name'];?></td>
                <td><?php echo $val['slug'];?></td>
                <td class="text-center">
                  <a href="/admin/categories.php?action=edit&cat_id=<?php echo $val['id'];?>" class="btn btn-info btn-xs">编辑</a>
                  <a href="/admin/categories.php?action=delete&cat_id=<?php echo $val['id'];?>" class="btn btn-danger btn-xs">删除</a>
                </td>
              </tr>
              <?php    }  ;?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

 <?php include './inc/aside.php';?>

 <?php include './inc/script.php';?>
 <script>
 $('#toggle').on('change',function(){
    if(this.checked){
      $('.chk').prop('checked',true);
      $('.deleteAll').show();
    }else{
      $('.chk').prop('checked',false);
      $('.deleteAll').hide();
    }

 })

 $('.chk').on('change',function(){
  //计算选中的个数
  var size=$('.chk:checked').size();
  // alert(size);
  if(size>0){
    $('.deleteAll').show();
    return;
  }
  $('.deleteAll').hide();

 })
 $('.deleteAll').on('click',function(){
    var ids=[];
    $('.chk:checked').each(function(){
      ids.push($(this).val());
    })
    // console.log(ids);
    //将数组传到后台

    $.ajax({
      url:'/admin/categories.php?action=deleteAll',
      // type:'post',
      data:{ids:ids},
      success:function(info){
        // alert(info);
        if(info==1){
          alert('成功');
          location.reload();
        }else{
          alert('失败！');
        }

      }
    })


 })
   

 </script>
</body>
</html>

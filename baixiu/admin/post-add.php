<?php 
  require '../functions.php';
  checkLogin();
  $active='post';
  $active1='';
  $actives=array('category','post','posts');
 
  $message='';
  $action=isset($_GET['action'])?$_GET['action']:'add';

  if(!empty($_POST)||$action=='upfile'){

   if($action=='add'){
       unset($_POST['id']);
        $result=insert('posts',$_POST);
        // print_r($result);
        // exit;
        if($result){
          header('Location:/admin/posts.php');
          exit();    
        }else{
        $message='添加文章失败！';
      }

   }else if($action=='upfile'){

        if(!file_exists('../uploads/thums')){
          mkdir('../uploads/thums');
        }
        $filename=time();
        $ext=explode('.', $_FILES['avatara']['name'])[1];

        $path='/uploads/thums/'.$filename.'.'.$ext;
        // $user_id=$_SESSION['user_info']['id'];
        //将二进制的文件从临时目录到
        move_uploaded_file($_FILES['avatara']['tmp_name'], '..'.$path);
        // update('posts',array('avatara'=>$path),$user_id);
        echo $path;
        exit();
    }else if($action=='update'){
      $id=$_POST['id'];
      unset($_POST['id']);
      $result=update('posts',$_POST,$id);
      if($result){
        header('Location:/admin/posts.php');
        exit();
      }else{
        $message="更新失败！";
      }
      // print_r($_POST);
      // exit();
  }
  }else if(!empty($_GET)){
    // print_r(99999);
    // exit();
    if($action=='edit'){
      $action='update';
      $pid=$_GET['pid'];
      $sql ='SELECT * FROM posts WHERE id='.$pid;
      $any=query($sql)[0];
     
 // print_r($any['feature']);
 //      exit();
    }
  }


// 查找分类信息
  $sql='SELECT * FROM categories';
  $lists=query($sql);

  //文章的修改

?>

<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title>Add new post &laquo; Admin</title>
  <?php include './inc/css.php';?>
  <link rel="stylesheet" href="../assets/css/admin.css">
 
</head>
<body>


  <div class="main">
      <?php include './inc/nav.php';?>
    <div class="container-fluid">
      <div class="page-title">
        <h1>写文章</h1>
      </div>
      <!-- 有错误信息时展示 -->
      <?php if(!empty($message)){?>
      <div class="alert alert-danger">
        <strong>错误！</strong><?php echo $message;?>
      </div>
      <?php }?>
      <form class="row" action="/admin/post-add.php?action=<?php echo $action?>" method="post">
      <input type="hidden" name="user_id" value="<?php echo $_SESSION['user_info']['id'] ;?>">
      <input type="hidden" name="id" value="<?php echo $pid ;?>">
        <div class="col-md-9">
          <div class="form-group">
            <label for="title">标题</label>
            <input id="title" class="form-control input-lg" name="title" type="text" placeholder="文章标题" value="<?php echo isset($any['title'])?$any['title']:'' ;?>">
          </div>
          <div class="form-group">
            <label for="content">内容</label>
            <textarea id="content" style="height: 300px" name="content" cols="30" rows="10" placeholder="内容" value=""><?php echo isset($any['content'])?$any['content']:'';?></textarea>
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label for="slug">别名</label>
            <input id="slug" class="form-control" name="slug" type="text" placeholder="slug" value="<?php echo isset($any['slug'])?$any['slug']:'';?>">
            <p class="help-block">https://zce.me/post/<strong>slug</strong></p>
          </div>
          <div class="form-group">
            <label for="feature">特色图像</label>
            <!-- show when image chose -->
            <?php if(isset($any['feature'])){?>
               <img class="help-block thumbnail preview" src="<?php echo $any['feature'];?>" style="">
            <?php }else{?>
            <img class="help-block thumbnail preview" src="" style="display: none">
            <?php }?>
            <input id="feature" class="form-control" type="file">
            <input id="thump" type="hidden" name="feature" value="<?php echo isset($any['feature'])?$any['feature']:'';?>">
          </div>
          <div class="form-group">
            <label for="category">所属分类</label>
            <select id="category" class="form-control" name="category_id">
            <?php foreach($lists as $key=>$val){?>
              <option value="<?php echo $val['id']?>"
               <?php if(isset($any['category_id'])?$any['category_id']:1==$val['id']) {?> 
               selected 
               <?php }?>>
               <?php echo $val['name'];?>
               </option>
           <?php }?>
            </select>
          </div>
          <div class="form-group">
            <label for="created">发布时间</label>
            <?php if(isset($any['created'])) {?>
            <input id="created" class="form-control" name="created" type="text"  value="<?php echo isset($any['created'])?$any['created']:'';?>">
            <?php }else{?>
             <input id="created" class="form-control" name="created" type="datetime-local">
            <?php }?>
          </div>
          <div class="form-group">
            <label for="status">状态</label>
            <select id="status" class="form-control" name="status">
              <option value="drafted" <?php if(isset($any['status'])?$any['status']:0=='drafted') {?> selected <?php }?>>草稿</option>
              <option value="published" <?php if(isset($any['status'])?$any['status']:1=='published') {?> selected <?php }?> >已发布</option>
            </select>
          </div>
          <div class="form-group">
            <button class="btn btn-primary" type="submit">保存</button>
          </div>
        </div>
      </form>
    </div>
  </div>

    <?php include './inc/aside.php' ;?>

    <?php include './inc/script.php' ;?>
    <!-- //引入ueditor.js -->
    <script type="text/javascript" src="/assets/vendors/ueditor/ueditor.config.js"></script>>
    <script type="text/javascript" src="/assets/vendors/ueditor/ueditor.all.min.js"></script>>
    <script>
        UE.getEditor('content',{
          autoHeightEnable:true
        });

        $('#feature').on('change',function(){
         
          var xhr=new XMLHttpRequest();
          var data=new FormData();
          data.append('avatara',this.files[0]);//转换成二进制

          xhr.open('post','/admin/post-add.php?action=upfile');
          xhr.send(data);  NProgress.start();
          xhr.onreadystatechange=function(){
            if(xhr.readyState==4&&xhr.status==200){
               NProgress.done();
                // console.log(xhr.responseText);
                $('.preview').attr('src',xhr.responseText).show();
                $('#thump').val(xhr.responseText);

            }
        }
      })
    </script>
</body>
</html>

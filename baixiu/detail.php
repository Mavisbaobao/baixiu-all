<?php
  require './functions.php';
    //查询所有的导航菜单
   $navs= query("SELECT `value` FROM options WHERE `key`='nav_menus'");
    //数据以json的形式导出.需要转化成数组
   $navs=json_decode($navs[0]['value'],true);

   // 按照浏览量最多的查询数据库中的文章 
   $browseSql='SELECT * FROM posts order by posts.views DESC LIMIT 0,5';
   $browse=query($browseSql);
 

  // 按照评论时间排序，显示六条数据
   $commentsql="SELECT * FROM comments order by created DESC LIMIT 0,5";
   $comment=query($commentsql);
  
   $nowtime=date('Y-m-d H:i:s',time());

 // 随机推荐
   //获取数据库中的数据的条数
  $countsql='SELECT count(*) AS total FROM posts';
  $total=query($countsql)[0]['total'];
  $arr=array();
    // 计数器
  $i=1;

  while($i<6){
    $i++; 
    $count=rand(1,$total); 
    
    $arr[]=$count; 
     
  }
  
  $j=0;
  while($j<5){   
    $randsql[]='SELECT * FROM posts WHERE id='.$arr[$j]; 
    $j++;
  }
 
  // 遍历获得数组
   $n=0;
  while($n<5){
    $zrr[]=query($randsql[$n]);
    $n++;
  }
  $zrr=array_values($zrr);
  

 $sql='SELECT  posts.id,posts.content,posts.views, posts.title, posts.created, posts.status, users.nickname, categories.name FROM posts LEFT JOIN users ON posts.user_id=users.id LEFT JOIN categories ON posts.category_id = categories.id WHERE posts.id='.$_GET['id'];

 // 评论的个数
 $sql_1="SELECT count(*) FROM comments";
 $count=query($sql_1)[0];

// 获取文章id
  // 根据 id 查询文章
  $rows = query($sql);
  // print_r($rows);exit;
 


?>
<?php include './inc/head.php';?>
<body>
  <?php include "./inc/nav.php";?>
   <?php include './inc/aside.php';?>
    <div class="content">
    	<?php foreach ($rows as $key => $val) {?>
      <div class="article">
        <div class="breadcrumb">  
          <dl>
            <dt>当前位置：</dt>
            <dd><a href="javascript:;"><?php echo $val['name'];?></a></dd>
            <dd style="margin-left: 12px"><?php echo mb_substr($val['content'],0,20);?></dd>
          </dl>
        </div>
        <h2 class="title">
          <a href="javascript:;"><?php echo $val['title'];?></a>
        </h2>
        <div class="meta">
          <span><?php echo $val['nickname'];?> 发布于 <?php echo $val['created'];?></span>
          <span>分类: <a href="javascript:;"><?php echo $val['name'];?></a></span>
          <span>阅读: (<?php echo $val['views'];?>)</span>
          <span>评论: (<?php echo $count['count(*)'];?>)</span>
        </div>
          <div style="padding-top: 20px;font-size: 14px; color: #666; line-height: 2">
          <?php echo $val['content']; ?>
        </div>
      </div>
      <?php }?>
      <div class="panel hots">
        <h3>热门推荐</h3>
        <ul>
        <?php foreach($browse as $key=>$val) if($key<4) {{?>
          <li>
            <a href="./detail.php?id=<?php echo $val['id'];?>">
              <img src="<?php echo $val['feature'];?>" alt="">
              <span><?php echo $val['title'] ;?></span>
            </a>
          </li>
           <?php }}?>
        </ul>
      </div>
    </div>
    <?php include "./inc/foot.php";?>
  </div>
</body>
</html>

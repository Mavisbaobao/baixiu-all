  <?php 
    require './functions.php';

    //查询所有的导航菜单
   $navs= query("SELECT `value` FROM options WHERE `key`='nav_menus'");
    //数据以json的形式导出.需要转化成数组
  
   $navs=json_decode($navs[0]['value'],true);
   // print_r($navs);exit();

    //查询所有的轮播图
   $slides=query("SELECT `value` FROM options WHERE `key`='home_slides'");
   $slides=json_decode($slides[0]['value'],true);
    
    //取出最新文章
   $postSql='SELECT posts.id, posts.title, posts.created, users.nickname, categories.name, posts.content, posts.feature, posts.likes, posts.views FROM posts LEFT JOIN users ON posts.user_id=users.id LEFT JOIN categories ON posts.category_id = categories.id ORDER BY id DESC LIMIT 0, 10';
   $posts=query($postSql);
    // print_r($posts);
    // exit();

   //
   // 按照浏览量最多的查询数据库中的文章
   $browseSql='SELECT * FROM posts order by posts.views DESC LIMIT 0,5';
   $browse=query($browseSql);
 // print_r($browse);exit;
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
  // print_r($zrr);exit;



  ?>

  <?php include './inc/head.php' ;?>
<body>
  <div class="wrapper">
   
  <?php include './inc/nav.php' ;?>
   <?php include './inc/aside.php' ;?>
    <div class="content">
      <div class="swipe">
        <ul class="swipe-wrapper">
        <?php foreach($slides as $key=>$val) {?>
          <li>
            <a href="<?php echo $val['link'];?>">
              <img src="<?php echo $val['image']?>">
              <span><?php echo $val['text']?></span>
            </a>
          </li>
          <?php }?>
        </ul>
        <p class="cursor">

          <span class="active"></span>
          <span></span>
          <span></span>
          <span></span>
        </p>
        <a href="javascript:;" class="arrow prev"><i class="fa fa-chevron-left"></i></a>
        <a href="javascript:;" class="arrow next"><i class="fa fa-chevron-right"></i></a>
      </div>
      <div class="panel focus">
        <h3>焦点关注</h3>
        <ul>
        <?php foreach($browse as $key=>$val) {?>
          <li class="<?php if($key==0) {?>large<?php }?>">
            <a href="./detail.php?id=<?php echo $val['id'];?>">
              <img src="<?php echo $val['feature'];?>" alt="">
              <span><?php echo $val['title'];?></span>
            </a>
          </li>
         <?php }?>
        </ul>
      </div>
      <div class="panel top">
        <h3>一周热门排行</h3>
        <ol>
        <?php foreach($browse as $key=>$val){?>
          <li>
            <i><?php echo $key+1;?></i>
            <a href="./detail.php?id=<?php echo $val["id"];?>"><?php echo $val['title'] ;?></a>
            <a href="javascript:;" class="like">赞(<?php echo $val['likes'];?>)</a>
            <span>阅读 (<?php echo $val['views'];?>)</span>
          </li>
        <?php }?>
        </ol>
      </div>
      <div class="panel hots">
        <h3>热门推荐</h3>
        <ul>
        <?php foreach($browse as $key=>$val) if($key<4) {{?>
          <li>
            <a href="./detail.php?id=<?php echo $val["id"];?>">
              <img src="<?php echo $val['feature'];?>" alt="">
              <span><?php echo $val['title'] ;?></span>
            </a>
          </li>
           <?php }}?>
        </ul>
      </div>
      <div class="panel new">
        <h3>最新发布</h3>
        <?php foreach($posts as $key=>$val) {?>
        <div class="entry">
          <div class="head">
            <span class="sort"><?php echo $val['name'];?></span>
            <a href="./detail.php?php=<?php echo $val['id'];?>"><?php echo $val['title'];?></a>
          </div>
          <div class="main">
            <p class="info"><?php echo $val['nickname'] ;?>发表于 <?php echo $val['created']?></p>
            <p class="brief"><?php echo mb_substr($val['content'],0,100);?></p>
            <p class="extra">
              <span class="reading">阅读(<?php echo $val['views']?>)</span>
              <span class="comment">评论(0)</span>
              <a href="javascript:;" class="like">
                <i class="fa fa-thumbs-up"></i>
                <span>赞(<?php echo $val['likes'];?>)</span>
              </a>
              <a href="javascript:;" class="tags">
                分类：<span><?php echo $val['name'];?></span>
              </a>
            </p>
            <a href="javascript:;" class="thumb">
              <img src="<?php echo $val['feature']?>" alt="">
            </a>
          </div>
        </div>
        <?php }?>
        
        </div>
      </div>
    </div>
     <?php include './inc/foot.php' ;?>
  </div>
  <script src="assets/vendors/jquery/jquery.js"></script>
  <script src="assets/vendors/swipe/swipe.js"></script>
  <script>
    //
    var swiper = Swipe(document.querySelector('.swipe'), {
      auto: 3000,
      transitionEnd: function (index) {
        // index++;

        $('.cursor span').eq(index).addClass('active').siblings('.active').removeClass('active');
      }
    });

    // 上/下一张
    $('.swipe .arrow').on('click', function () {
      var _this = $(this);

      if(_this.is('.prev')) {
        swiper.prev();
      } else if(_this.is('.next')) {
        swiper.next();
      }
    })
  </script>
</body>
</html>

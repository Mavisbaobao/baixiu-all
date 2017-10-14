
  <div class="aside">
      <div class="widgets">
        <h4>搜索</h4>
        <div class="body search">
          <form>
            <input type="text" class="keys" placeholder="输入关键字">
            <input type="submit" class="btn" value="搜索">
          </form>
        </div>
      </div>
      <div class="widgets">
        <h4>随机推荐</h4>
        <ul class="body random">
          <?php foreach ($zrr as $key => $val) {?>
          <li>
            <a href="./detail.php?id=<?php echo $val[0]["id"];?>">
              <p class="title"><?php echo $val[0]['title'];?></p>
              <p class="reading">阅读(<?php echo $val[0]['views'];?>)</p>
              <div class="pic">
                <img src="<?php echo $val[0]['feature']?>" alt="">
              </div>
            </a>
          </li>
          <?php } ?>
        </ul>
      </div>
      <div class="widgets">
        <h4>最新评论</h4>
        <ul class="body discuz">
          <?php foreach ($comment as $key => $val) {  $thattime=$comment[$key]['created'];   $pre=getMonthNum( $nowtime , $thattime );?>
          <li>
            <a href="javascript:;">
              <div class="avatar">
                <img src="uploads/avatar_<?php echo $key+1;?>.jpg" alt="">
              </div>
              <div class="txt">
                <p>
                  <span><?php echo $val['author'];?></span><?php echo $pre;?>个月前(<?php echo substr($val['created'],0,11);?>)说:
                </p>
                <p><?php echo $val['content'];?></p>
              </div>
            </a>
          </li>
          <?php }?>
        
        </ul>
      </div>
    </div>
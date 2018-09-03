<!DOCTYPE HTML>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1,user-scalable=no">
  <link rel="stylesheet" type="text/css" href="scripts/bootstrap/css/bootstrap.css">
  <link rel="stylesheet" type="text/css" href="resources/font/m/font-glyphs/font-glyphs.css">
  <link rel="stylesheet" type="text/css" href="app/news/bbs/css/bbs.css">
  <script src="scripts/jquery/jquery-2.1.1.min.js"></script>
  <script src="scripts/bootstrap/js/bootstrap.min.js"></script>
  <script type="text/javascript">
    $(function(){
      $("#myReply").click(function(){
        window.parent.mainPanel.closeTab("CNOA_MENU_NEWS_BBS_MYREPLY");
    window.parent.mainPanel.loadClass("index.php?app=news&func=bbs&action=myreply", "CNOA_MENU_NEWS_BBS_MYREPLY", "我的回复", "icon-bbs_myreply");
      });
      $("#myPost").click(function(){
        window.parent.mainPanel.closeTab("CNOA_MENU_NEWS_BBS_MYPOST");
    window.parent.mainPanel.loadClass("index.php?app=news&func=bbs&action=mypost", "CNOA_MENU_NEWS_BBS_MYPOST", "我的帖子", "icon-bbs_mypost");
      });
    })
  </script>
</head>
<body class="background">
  <div style="width:100%; height:80px">
    <div class="search">
      <form class="form-inline" method="post" action="index.php?app=news&func=bbs&action=bbs&task=getIndex&model=search">
        <input class="form-control input-lg search" name="title" type="text" placeholder="搜索" required="required">
        <input class="btn btn-primary btn-lg submit" type="submit" value="搜索">
        今日：<span class="red">{$today}</span>　　昨日：<span class="yellow">{$yesterday}</span>　　帖子：{$allPosts}
      </form>
    </div>
  </div>
  <div class="container pull-left leftDiv">
    <div class="row">
      <div class="col-md-3">
        <div class="white" style="height:294px;">
          <div class="bottom">
            <img class="head" src="{$face}">
            <p class="center">{$truename}</p>
          </div>
          <div class="pull-left marginTop" style="width:50%;">
            <button class="btn btn-primary headButtonL" id="myReply">我的回复</button>
          </div>
          <div class="pull-left marginTop" style="width:50%">
            <button class="btn btn-danger headButtonR" id="myPost">我的帖子</button>
          </div>
        </div>
      </div>
      <div class="col-md-9 white">
        <div class="row" style="height:294px;">
          <div class="top">最新帖子</div>
          <div class="listHeight col-md-12 bottom">
            <div class="row">
              <span class="col-sm-7 center title">标题</span>
              <span class="col-sm-1 left center title">发布人</span>
              <span class="col-sm-2 left center title">所在部门</span>
              <span class="col-sm-1 left center title">回复</span>
              <span class="col-sm-1 left center title">浏览</span>
            </div>
          </div>
          {foreach $newPost(key,value)}
            <div class="listHeight col-md-12 bottom">
            <div class="row">
              <span class="col-sm-7 center">
                <a href="index.php?app=news&func=bbs&action=bbs&task=getIndex&model=browse&fid={@value->fid}&id={@value->id}">  {@value->title}
                </a>
              </span>
              <span class="col-sm-1 center red">{@value->author}</span>
              <span class="col-sm-2 center aaaa">{@value->deptName}</span>
              <span class="col-sm-1 center red">{@value->reply}</span>
              <span class="col-sm-1 center aaaa">{@value->browse}</span>
            </div>
          </div>
          {/foreach}
        </div>
      </div>
      <div class="col-md-3 marginTop">
        <div class="white">
          <div class="top">最新回复</div>
          <div class="listHeight bottom">
            <span class="col-sm-9 center title">帖子</span>
            <span class="col-sm-3 center left title">回复人</span>
          </div>
          {foreach $newReply(key,value)}
          <div class="listHeight bottom">
            <span class="col-sm-9 center">
              <a href="index.php?app=news&func=bbs&action=bbs&task=getIndex&model=browse&fid={@value->fid}&id={@value->id}">
                {@value->title}
              </a>
            </span>
            <span class="col-sm-3 center red">{@value->author}</span>
          </div>
          {/foreach}
        </div>
      </div>
      <div class="col-md-9 white marginTop">
        <div class="row">
          <?php
            foreach ($this->_vars['result'] as $key => $value){
              echo '<div class="top">'.$value['name'].'</div>';
              $str = "result".$value['id'];
              $line = 0;
              foreach ($this->_vars[$str] as $k2 => $v2) {
                if( $k2 % 2 == 0) echo '<div class="bottom clearfix">';
                echo '
                  <div class="borderDiv">
                    <a href="index.php?app=news&func=bbs&action=bbs&task=getIndex&model=getList&fid='.$v2['id'].'">
                      <img src="'.$v2['faceUrl'].'">
                    </a>
                    <div class="title">
                      <a href="index.php?app=news&func=bbs&action=bbs&task=getIndex&model=getList&fid='.$v2['id'].'">
                        '.$v2['name'].'
                      </a>
                    </div>
                    <div class="info">'.$v2['info'].'</div>
                    <div class="rt">主题：'.$v2['topics'].'  回复：'.$v2['reply'].'</div>
                  </div>
                ';
                $line++;
                if( $line % 2 == 0) echo '</div>';
              }
              if( $line % 2 == 1 ) echo '</div>';
              echo '<div class="marginTop"></div>';
            }
          ?>
        </div>
      </div>
    </div>
  </div>
  <div class="pull-left rightDiv">
    <div class="white">
      <div class="top">本周最热</div>
      {foreach $weekHot(key,value)}
      <div class="listHeight2 bottom clearfix">
        <div class="a{@key}">{@key}</div>
        <span class="bbbbbb">
          <a href="index.php?app=news&func=bbs&action=bbs&task=getIndex&model=browse&fid={@value->fid}&id={@value->id}">
            {@value->title}
          </a>
        </span>
      </div>
      {/foreach}
    </div>
    <div class="white">
      <div class="top marginTop">友情链接</div>
      <div class="friendLink">
        {foreach $friendLink(key,value)}
        <a href="{@value->url}" target="_blank">{@value->name}</a>
        {/foreach}
      </div>
    </div>
  </div>
</body>
</html>
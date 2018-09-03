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
      $("#clearHistory").click(function(){
        var url = "index.php?app=news&func=bbs&action=bbs&task=getIndex&model=clearHistory";
        $.ajax({
          type: "GET",
          url: url,
          success: function(){
            $("#afterClearHistory").empty();
          }
        });
      });
    })
  </script>
</head>
<body class="background">
  <div class="container-fluid">
  	<div class="row searchResultTop">
			<div class="col-md-12 search">
        <div class="nav">
          <a href="index.php?app=news&func=bbs&action=bbs&task=getIndex&model=getIndex">返回</a>
        </div>
	      <form class="form-inline" method="post" action="index.php?app=news&func=bbs&action=bbs&task=getIndex&model=search">
	        <input class="form-control input-lg search" name="title" type="text" placeholder="搜索" required="required" value="{$sTitle}">
	        <input class="btn btn-primary btn-lg submit" type="submit" value="搜索">
	      </form>
	    </div>
  	</div>
  </div>
  <div class="container-fluid">
  	<div class="row">
  		<div class="col-md-8">
        {foreach $search(key,value)}
  			<div class="searchResult">
  				<span class="span1">
            <a href="index.php?app=news&func=bbs&action=bbs&task=getIndex&model=browse&fid={@value->fid}&id={@value->id}">
              {@value->title}
            </a>
          </span>
  				<span class="span2">{@value->reply}次回复 - {@value->browse}浏览</span>
  				<span class="span3">{@value->info}</span>
  				<span class="span2">{@value->posttime} - {@value->author} - {@value->forum}</span>
  			</div>
        {/foreach}
        <div class="page" style="float:right;">{$page}</div>
  		</div>
  		<div class="col-md-3 searchHistory">
  			<div>
  				搜索历史　
  				<a href="#" id="clearHistory">清空</a>
  			</div>
        <div id="afterClearHistory">
          {foreach $searchHistory(key,value)}
          <div class="link"><a href="index.php?app=news&func=bbs&action=bbs&task=getIndex&model=search&title={@value->title}">{@value->title}</a></div>
          {/foreach}
        </div>
        
  		</div>
  	</div>
  </div>
</body>
</html>
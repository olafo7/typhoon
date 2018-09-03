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
</head>
<body class="text-overflow">
	<div class="list">
		<div class="clearfix">
      <div class="leftButton">
        <a class="btn btn-lg btn-primary" role="button" 
          href="index.php?app=news&func=bbs&action=bbs&task=getIndex&model=post&fid={$forumId}">
          发表帖子
        </a>
      </div>
      <div class="nav">
        <a href="index.php?app=news&func=bbs&action=bbs&task=getIndex&model=getIndex">论坛</a>
        {if $forumId}
          > {$forumFname}
          > <a class="red" href="index.php?app=news&func=bbs&action=bbs&task=getIndex&model=getList&fid={$forumId}">{$forumName}</a>
        {/if}
      </div>
    </div>
    <div class="listHead">
      <span class="type1">标题</span>
      <span class="type2">发布人</span>
      <span class="type2">部门</span>
      <span class="type2">发布时间</span>
      <span class="type2">回复/查看</span>
      <span class="type2">最后回复</span>
    </div>
    {foreach $postList(key,value)}
      <div class="listInfo">
      	<span class="type1">
          <a class="c{@key}" href="index.php?app=news&func=bbs&action=bbs&task=getIndex&model=browse&fid={@value->fid}&id={@value->id}">
            {@value->title}
          </a>
        </span>
      	<span class="type3 red">
          <img src="{@value->face}">
          {@value->author}
        </span>
      	<span class="type2">{@value->deptName}</span>
      	<span class="type2 red">{@value->posttime}</span>
      	<span class="type2">{@value->reply} / {@value->browse}</span>
        <div>
          <span class="span1">{@value->rAuthor}</span>
          <span class="span2">{@value->rPosttime}</span>
        </div>
      </div>
    {/foreach}
    <div class="page" style="float:right;">{$page}</div>
	</div>
</body>
</html>
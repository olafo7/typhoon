<!DOCTYPE HTML>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1,user-scalable=no">
  <link rel="stylesheet" type="text/css" href="/cnoa/scripts/bootstrap/css/bootstrap.css">
  <link rel="stylesheet" type="text/css" href="/cnoa/resources/font/m/font-glyphs/font-glyphs.css">
  <link rel="stylesheet" type="text/css" href="/cnoa/app/news/bbs/css/bbs.css">
  <script src="/cnoa/scripts/jquery/jquery-2.1.1.min.js"></script>
  <script src="/cnoa/scripts/bootstrap/js/bootstrap.min.js"></script>
  <script type="text/javascript" charset="utf-8" src="/cnoa/scripts/ueditor/ueditor.config.js"></script>
  <script type="text/javascript" charset="utf-8" src="/cnoa/scripts/ueditor/ueditor.js"> </script>
  <script type="text/javascript">
    function reply(){
      var contents = UE.getEditor('editor').hasContents();
      if(contents === false){
        alert('请填写回复内容');
        UE.getEditor('editor').focus();//光标返回编辑器中
        return false;
      }
    }
  </script>
</head>
<body class="background">
	<div class="topic">
    <div class="clearfix">
      {if $showP}
      <div class="leftButton">
        <a class="btn btn-lg btn-primary" role="button" 
          href="index.php?app=news&func=bbs&action=bbs&task=getIndex&model=post&fid={$forumId}">
          发表帖子
        </a>
      </div>
  		<div class="leftButton">
        <a class="btn btn-lg btn-warning" role="button" href="#reply">
          快速回复
        </a>
      </div>
      {/if}
      <div class="nav">
        <a href="index.php?app=news&func=bbs&action=bbs&task=getIndex&model=getIndex">论坛</a>
        {if $forumId}
          > {$forumFname}
          > <a href="index.php?app=news&func=bbs&action=bbs&task=getIndex&model=getList&fid={$forumId}">{$forumName}</a>
        {/if}
        {if $pTitle}
          > <span style="color:red;">{$pTitle}</span>
        {/if}
        {if $newPost2}
          > <span style="color:red;">{$newPost2}</span>
        {/if}
      </div>
    </div>
    {if $showP}
      <div class="title">{$pTitle}</div>
      <div class="clearfix">
        <div class="topicLeft">
          <div class="topicHead">
            <div class="name">{$pAuthor}</div>
            <img src="{$pFace}">
            <div class="userInfo">最后登录  {$pLoginTime}</div>
          </div>
        </div>
        <div class="topicRight">
          <div class="posttime clearfix">
            <span class="glyphicon glyphicon-play"></span> 发表于 {$pPosttime}
            <div class="rt">阅读：<span class="red">{$pBrowse}</span>　|　回复：<span class="red">{$pReply}</span></div>
          </div>
          <div class="info">
            <div>
               {$pContent}
            </div>
          </div>
        </div>
      </div>
    {/if}
	</div>
  {if $reply}
    <div class="clearfix" style="margin-top: 20px; margin-bottom: 20px; padding-left: 15px;">
      <a class="btn btn-lg btn-warning" role="button" href="#reply">
        快速回复
      </a>
      <div class="page" style="float:right;">{$page}</div>
    </div>
    <div class="reply">
  {foreach $reply(key,value)}
      <div class="clearfix">
        <div class="topicLeft">
          <div class="topicHead">
            <div class="name">{@value->author}</div>
            <img src="{@value->face}">
            <div class="userInfo">最后登录  {@value->lastLogintime}</div>
          </div>
        </div>
        <div class="topicRight">
          <div class="posttime"><span class="glyphicon glyphicon-play"></span> 发表于 {@value->posttime}</div>
          <div class="info">
            <div>
               {@value->content}
            </div>
          </div>
        </div>
      </div>
      <div class="line"></div>
  {/foreach}
    </div>
  {/if}
  <div class="clearfix" style="margin-top: 20px; margin-bottom: 20px; padding-left: 15px;">
    <a class="btn btn-lg btn-primary" role="button" 
      href="index.php?app=news&func=bbs&action=bbs&task=getIndex&model=post&fid={$forumId}">
      发表帖子
    </a>
    <div class="page" style="float:right;">{$page}</div>
  </div>
  <div class="myReply clearfix">
    <div class="topicLeft">
      <div class="topicHead">
        <div class="name">{$myTruename}</div>
        <img src="{$myFace}">
        <div class="userInfo">最后登录  {$myLastLogintime}</div>
      </div>
    </div>
    <div class="topicRight">
      <form action="index.php?app=news&func=bbs&action=bbs&task=getIndex&model=reply&fid={$forumId}&id={$pId}" method="post">
        <script id="editor" type="text/plain" style="width:100%;height:300px;" name="reply"></script>
        <script type="text/javascript">
            var toolbars = [
              "undo","redo","bold","italic","underline","strikethrough","superscript","subscript","forecolor","backcolor","removeformat","cleardoc","fontfamily","fontsize","justifyleft","justifycenter","justifyright","link","unlink","emotion","insertimage","date","time","spechars","fullscreen","|","drafts"
          ];
            var ue = UE.getEditor('editor',{
              toolbars: [toolbars]
            });
        </script>
        <input class="send" type="submit" name="send" value="回复" onclick="return reply();">
      </form>
      <a name="reply"></a>
    </div>
  </div>
</body>
<html>
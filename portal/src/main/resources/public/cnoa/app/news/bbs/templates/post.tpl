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
    function setInfo(){
    	var contents = UE.getEditor('editor').hasContents();
    	if(contents === false){
    		alert('请填写内容');
				UE.getEditor('editor').focus();//光标返回编辑器中
				return false;
    	}
      var text = UE.getEditor('editor').getContentTxt();
      var info = text.slice(0,100) + "...";
      $("#info").val(info);
    }
  </script>
</head>
<body>
	<div class="post">
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
		<form method="post" action="index.php?app=news&func=bbs&action=bbs&task=getIndex&model=post&step=2">
			<input type="hidden" name="fid" value="{$fid}" />
			<input type="hidden" name="info" id="info">
			<table class="table table-bordered">
				<tr class="tr1">
					<td class="td1">标题：</td>
					<td class="td2">
						<input type="text" class="form-control" placeholder="主题名" name="title" required="required">
					</td>
				</tr>
				<tr>
					<td class="td3">内容：</td>
					<td class="td3">
						<script id="editor" type="text/plain" style="width:100%;height:500px;" name="editor"></script>
						<script type="text/javascript">
				    	var toolbars = [
				    		"undo","redo","bold","italic","underline","strikethrough","superscript","subscript","forecolor","backcolor","removeformat","cleardoc","fontfamily","fontsize","justifyleft","justifycenter","justifyright","link","unlink","emotion","insertimage","insertvideo","music","date","time","map","spechars","fullscreen","|","preview","drafts"
								];
				    	var ue = UE.getEditor('editor',{
				    		toolbars: [toolbars]
				    	});
						</script>
						<input class="send" type="submit" name="send" value="发表" onclick="return setInfo();">
					</td>
				</tr>
			</table>
		</form>
		
  </div>
	</div>
</body>
</html>
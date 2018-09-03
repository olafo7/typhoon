<a name="gotoTop"></a>
<div id="count">
	<p>
		<a href="index.php?app=news&func=bbs&action=bbs&task=getIndex&model=getIndex">论坛</a>
		{if $forumId}
		 》<span style="color:black;">{$forumFname}</span>
		 》<a href="index.php?app=news&func=bbs&action=bbs&task=getIndex&model=getList&fid={$forumId}">{$forumName}</a>
		{/if}
		{if $pTitle}
		 》<span style="color:red;">{$pTitle}</span>
		{/if}
		{if $newPost2}
		 》<span style="color:red;">{$newPost2}</span>
		{/if}
	</p>
	<span>今日: <span>{$today}</span> | 昨日: <span>{$yesterday}</span> | 帖子: <span>{$allPosts}</span> 
</div>
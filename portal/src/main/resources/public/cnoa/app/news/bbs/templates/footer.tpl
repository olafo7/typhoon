<h1>在线会员 - 总计 {$count} 人在线</h1>
	
	<div id="friendlink">
		{if $friendLink}
		{foreach $friendLink(key,value)}
		<a href="{@value->url}" target="blank">{@value->name}　</a>
		{/foreach}
		{/if}
	</div>
	<hr />
	<span> Build At 2015 By CNOA</span>
	<div id="fly"><a href="#gotoTop" style="color:rgb(241,236,222);"><img src="app/news/bbs/images/fly.png" /></a></div>
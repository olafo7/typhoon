$(function(){

	// 获取我的帖子信息
	function getMyPostData(url){
		$.ajax({
			dataType: "json",
			type: 'post',
			url: url,
			success: function(json){
				$('.my_name').html(json.data.my_name);
				$('.my_post span').html('('+json.data.post_count+')');
				$('.my_reply span').html('('+json.data.reply_count+')');
				$('.my_face img').attr('src',json.data.face);
			}
		})
	}

	getMyPostData('m.php?app=news&func=bbs&action=bbs&task=getMyPostData');

	// 跳转我的发帖
	$(document).on('touchstart','.about_my_posts',function(){
		window.location.href = 'm.php?app=news&func=bbs&action=bbs&task=loadPage&from=myposts';
    	return false;		
	})

	// 跳转我的回帖
	$(document).on('touchstart','.about_my_replys',function(){
		window.location.href = 'm.php?app=news&func=bbs&action=bbs&task=loadPage&from=myreplys';
    	return false;			
	})

	// 跳转我的点赞
	$(document).on('touchstart','.about_my_likes',function(){
		window.location.href = 'm.php?app=news&func=bbs&action=bbs&task=loadPage&from=mylikes';
    	return false;		
	})

	// 跳转我的收藏
	$(document).on('touchstart','.about_my_collects',function(){
		window.location.href = 'm.php?app=news&func=bbs&action=bbs&task=loadPage&from=mycollects';
    	return false;		
	})

	// 返回论坛中心
    $(document).on('touchstart','#btnBack',function(){
    	window.location.href = 'm.php?app=news&func=bbs&action=bbs&task=loadPage&from=main';
    	return false;
    })
})
var myScroll;
var myFns = {
	loaded:function(){
		myScroll = new iScroll('wrapper', {
			hScroll: true,
			vScroll: true,
			hScrollbar: false ,
			vScrollbar: false
			// scrollbarClass: 'myScrollbar',
			// wheelAction: 'scroll',
			// onBeforeScrollStart: function ( e ) {
		 //        if ( this.absDistX > (this.absDistY + 5 ) ) {
		 //            // user is scrolling the x axis, so prevent the browsers' native scrolling
		 //            e.preventDefault();
		 //        }
		 //    }
		});
		document.addEventListener('touchmove', function (e) { e.preventDefault(); }, false);
	},
	getUriString: function(key){
		var reg = new RegExp("(^|&)"+ key +"=([^&]*)(&|$)");
    	var r = window.location.search.substr(1).match(reg);
    	if(r!=null)return  unescape(r[2]); return null;
	},
	//判断当前是否是微信浏览器
	isWeiXin: function(){
		var ua = window.navigator.userAgent.toLowerCase();
		if(ua.match(/MicroMessenger/i) == 'micromessenger'){
		  	return true;
		}else{
		  	return false;
		}
	},
	showView:function(lid){
		$.ajax({
			type: "POST",
			url: "index.php?app=news&func=news&action=view&task=viewNews",
			dataType: "json",
			data: {lid: lid},
			success: function(json){
				var data = json.data;
				var str = '';
				str+= '<img src="'+data.userface+'">';
		        str+= '<span class="info">'+data.user+'&nbsp;&nbsp;'+data.posttime+'</span>';
		        $('#userinfo').append(str);
		        str = '';
		        str+= '<span class="tt">'+data.title+'</span>';
		        $('.title1').append(str);
		        str = '';
		        if(data.type == 'html' || data.type == 'focus'){
		        	$('.newinfo').html(data.content);
		        }else{
		        	var html ='';
		        	var videoStr = data.flv.replace(/%2F/g,"/");
					var videoImage = data.image.replace(/%2F/g,"/");
					html +='<video id="example_video_1" class="video-js vjs-default-skin" controls preload="none" width="100%" height="264" poster="'+videoImage+'" data-setup="{}">';
				    html +='<source src="'+videoStr+'" type="video/mp4" />';
				    html +='<track kind="captions" src="demo.captions.vtt" srclang="en" label="English"></track>';
				    html +='<track kind="subtitles" src="demo.captions.vtt" srclang="en" label="English"></track></video>';
					$('.newinfo').html(html);
		        }
		        $('.com').html('<div class="title2">本文评论</div><div class="comm"></div>');
		        myFns.showComment(lid);
		        myScroll.refresh();
			}
		});	
	},
	showComment:function(lid,isEmpty){
		if(isEmpty){
			$('.comm').empty();
		}
		$.ajax({
			type: "POST",
			url: "index.php?app=news&func=news&action=view&task=viewComment",
			dataType: "json",
			data: {lid: lid},
			success: function(json){
				if(json.data.allowComment === false) return;
				$.each(json.data.list,function(index,array){
					var str = '';	
					str += '<div class="commain"><div class="comimg">';
					str += '<img src="'+array['userface']+'"></div>';
					str += '<div class="cominfo" data-cid="'+array['cid']+'" data-lid="'+array['lid']+'">';
					if(array['uid'] == array['yid']){
						str+= '<div class="comrig"><button type="button" class="btn btn-danger delete">删除</button>';
					}
					str += '<div class="comright"><p class="cc">'+array['content']+'</p>';
					str += '<span class="time">'+array['uname']+'&nbsp;&nbsp;'+array['posttime']+'</span>';
					str += '</div></div></div></div>';
					$('.comm').append(str);
					myScroll.refresh();	
				})
				myScroll.refresh();	
			}
		});
	}
}

$(function(){
	myFns.loaded();
	videojs.options.flash.swf = "scripts/m/swiper/video-js.swf";

	var lid = myFns.getUriString('lid');
	//加载数据
	myFns.showView(lid);
	//评论
	$(document).on('touchstart','.submit',function(){
		var comment = $('#commentinfo').val();
		$.ajax({
			type: "POST",
			url: "index.php?app=news&func=news&action=view&task=postComment",
			dataType: "json",
			data:{lid: lid, content:comment},
			success: function(json){
				if(json.failure == true){
					jNotify(json.msg,{
						autoHide : true,                // 是否自动隐藏提示条 
						clickOverlay : false,            // 是否单击遮罩层才关闭提示条 
						MinWidth : 20,                    // 最小宽度 
						TimeShown : 2000,                 // 显示时间：毫秒 
						ShowTimeEffect : 200,             // 显示到页面上所需时间：毫秒 
						HideTimeEffect : 200,             // 从页面上消失所需时间：毫秒 
						LongTrip : 15,                    // 当提示条显示和隐藏时的位移 
						HorizontalPosition : "center",     // 水平位置:left, center, right 
						VerticalPosition : "center",     // 垂直位置：top, center, bottom 
						ShowOverlay : false,                // 是否显示遮罩层 
						ColorOverlay : "#000",            // 设置遮罩层的颜色 
						OpacityOverlay : 0.3            // 设置遮罩层的透明度 
					});
					return false;
				}else{
					myFns.showComment(lid,true);
				}
			}
		});
	});
	$(document).on('touchstart','#btnBack',function(){
		window.location.href = 'm.php?app=news&func=news&action=read&task=getMIndex';
	});
	//删除评论
	$(document).on('touchstart','.delete',function(){
		var cid = $(this).parents('.cominfo').attr('data-cid');
		var lid = $(this).parents('.cominfo').attr('data-lid');
		$.ajax({
			type: "POST",
			url: "index.php?app=news&func=news&action=view&task=delComment",
			dataType: "json",
			data:{cid: cid, lid:lid},
			success:function(json){
				if(json.success == true){
					myFns.showComment(lid,true);
				}
			}
		});
	})
})
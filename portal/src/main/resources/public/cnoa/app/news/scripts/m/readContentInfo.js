var myScroll;
if(/android/ig.test(navigator.userAgent)){ //安卓
	var userAgent = 'android';
}else{ //ios或者其他
	var userAgent = 'ios';
}
var myFns = {
	loaded:function(){
		myScroll = new iScroll('wrapper', {
			hScroll: true ,
			vScroll: true ,
			zoom:true,
			hScrollbar: false ,
			vScrollbar: false ,
			scrollbarClass: 'myScrollbar',
			wheelAction: 'scroll'
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
	is_images:function(ext){
		var ext = ext.toLowerCase();//转小写
		var arr = ['jpg','jpeg','png','gif','bmp'];//图片格式
		if($.inArray(ext,arr)<0){
			return false;
		}
		return true;
	},
	showView:function(lid){
		showLoading();
		setTimeout(function(){
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
		        var fslist ='';
		        $.each(data.hideattach,function(index,arr){
		        	fslist += '<div class="fsdetail"><span class="fsname">'+arr['name'];
		        	if(myFns.is_images(arr['ext'])){
		        		fslist += '<button type="button" class="btn btn-info brower" data-ext="'+arr['ext']+'" data-name="'+arr['name']+'" data-src="'+arr['url']+'" >浏览</button>';	
		        		ImagesZoom.init({
						    "elem": ".brower"
						});
		        	}else if(userAgent == 'android' && !myFns.is_images(arr['ext'])){
		        		fslist += '<button type="button" class="btn btn-info download" data-ext="'+arr['ext']+'" data-name="'+arr['name']+'" data-src="'+arr['url']+'">下载</button>';
		        	}else if(userAgent == 'ios' && myFns.is_images(arr['ext'])){
		        		fslist += '<button type="button" class="btn btn-info brower" data-ext="'+arr['ext']+'" data-name="'+arr['name']+'" data-src="'+arr['url']+'" >浏览</button>';	
		        		ImagesZoom.init({
						    "elem": ".brower"
						});
		        	}else{
		        		fslist += '<button type="button" class="btn btn-info brower" data-ext="'+arr['ext']+'" data-name="'+arr['name']+'" data-src="'+arr['url']+'" >浏览</button>';
		        	}
		        	fslist += '</span></div>';
		        	$('.fs').html(fslist);
		        });
		        $('.com').html('<div class="title2">本文评论</div><div class="comm"></div>');
		        myFns.showComment(lid);
		        hideLoading();
		        myScroll.refresh();
			}
		});	
		},100);
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
					$('#commentinfo').val('');
					myScroll.refresh();	
				})
				myScroll.refresh();	
			}
		});
	}
}
jQuery.plugin = {
	init: function() {
		this.closeMask();
	}, 
	showWin: function(width, height, title, content) {
		$('#pluginModal').css('display', 'block');
		this.init();
	},
	closeMask: function() {
		
    }
}
$(function(){
	myFns.loaded();
	videojs.options.flash.swf = "scripts/m/swiper/video-js.swf";

	var lid = myFns.getUriString('lid');
	//加载数据
	myFns.showView(lid);

	//附件
	$(document).on('click','.brower',function(){
		var fileSrc  = $(this).attr('data-src'); //文件地址
		var fileName = $(this).attr('data-name'); //文件名带后缀
		var fileExt  = $(this).attr('data-ext'); //文件后缀
		var seat = fileName.lastIndexOf("."); //查找某字符在字符串中最后一次出现的位置
		fileName = fileName.substring(0, seat); //纯文件名
		var host = window.location.host; //当前域名
		var fileAllSrc = host+'/'+fileSrc; //文件绝对路径
		var width = $(document).width(), height = $(document).height();
		if(myFns.isWeiXin()){
			if (userAgent == "android" && !myFns.is_images(fileExt)) {
				popWin.showWin(width, height, fileName, fileSrc);
			}else if(userAgent == "android" && myFns.is_images(fileExt)){
				ImagesZoom.init({
					"elem": ".brower"
				});
			}else if(myFns.is_images(fileExt)) {
				ImagesZoom.init({
					"elem": ".brower"
				});
			}else{
				$('iframe').find('body').attr('background-color','white');
				iospopWin.showWin(width, height, fileName, fileSrc,fileExt.toLocaleLowerCase());
			}
		}else{
			try{
				CNOAApp.viewDocument(fileAllSrc, fileName, fileExt);
			}catch(e){
				if(userAgent == 'android' && myFns.is_images(fileExt)){
					$('.imgzoom_pack').css('display','block');
					$('.imgzoom_img img').attr('src',fileSrc);
				}
				window.location.href = 'js://push_web_view_controller__'+fileAllSrc+'__#'+fileName;
			}
		}
	});
	$(document).on('click','.download',function(){
		var fileSrc  = $(this).attr('data-src'); //文件地址
		window.location.href = fileSrc;
	});

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
var flag =0;
if(/android/ig.test(navigator.userAgent)){ //安卓
	var userAgent = 'android';
}else{ //ios或者其他
	var userAgent = 'ios';
}
var myFns = {
	//初始化iScroll控件
	loaded:function(){
		myScroll = new IScroll('#wrapper', {
			scrollbars: false, //隐藏滚动条
	    	mouseWheel: true,
	    	wheelAction: 'zoom',
			preventDefault: false, //阻止默认事件
			preventDefaultException: { tagName: /^(INPUT|TEXTAREA|BUTTON|SELECT|A)$/ } //这个后面加|A,因为iscroll阻止了A的默认事件
		});
		document.addEventListener('touchmove', function (e) { e.preventDefault(); }, false);
	},
	//是否是图片后缀
	is_images:function(ext){
		var ext = ext.toLowerCase();//转小写
		var arr = ['jpg','jpeg','png','gif','bmp'];//图片格式
		if($.inArray(ext,arr)<0){
			return false;
		}
		return true;
	},
	//判断是否是微信浏览器
	isWeixn: function (){  
	  var ua = navigator.userAgent.toLowerCase();  
	  if(ua.match(/MicroMessenger/i)=="micromessenger") {  
	    return true;  
	  } else {  
	    return false;  
	  }  
	},
	//获取url参数
	getUriString: function(key){
		var reg = new RegExp("(^|&)"+ key +"=([^&]*)(&|$)");
    	var r = window.location.search.substr(1).match(reg);
    	if(r!=null)return  unescape(r[2]); return null;
	},
	showView:function(json){	
		var data = json.data;
		var did = myFns.getUriString('did');
		$('.name').text(data.truename);
		$('.time1').text(data.today);
		$('.user').attr('data_uid',data.uid);
		$('.user').text(data.user);
		$('.time').text(data.today);
		$('#summary').text(data.summary);
		if(!data.summary){
			$('#summary').css('height','34px');
		}else{
			$('#summary').css('height','');
		}
		$('#sum_supplement').text(data.summarycomplement);
		if(!data.summarycomplement){
			$('#sum_supplement').css('height','34px');
		}else{
			$('#sum_supplement').css('height','');
		}
		if(data.attach.length>0){
			var content = '';
			$.each(data.attach,function(index,array){
				var button = '';
				if(myFns.is_images(array['ext'])){
					button = '<button name="'+array['name']+'" ext="'+array['ext']+'" data-src="'+array['url']+'" type="button" class="btn btn-info btn brower">浏览</button>';
				}else if(userAgent == 'android' && !myFns.is_images(array['ext'])){
					button = '<button name="'+array['name']+'" ext="'+array['ext']+'" data-src="'+array['url']+'" type="button" class="btn btn-danger download">下载</button>';
				}else{
					button = '<button name="'+array['name']+'" ext="'+array['ext']+'" data-src="'+array['url']+'" type="button" class="btn btn-info brower">浏览</button>';
				}
				content = (index+1)+'、'+array['name']+button+'<br/>';
				$('#fs').append('<span class="fileView" >'+content);
			});
			ImagesZoom.init({
				"elem": ".brower"
			});
		}else{
			$('#fs').css('height','34px');
		}
		if(data.list.length>0){
			var userContent = '';
			var supContent = '';
			var time = '';
			var aa='';
			$.each(data.list,function(index,arr){
				if(arr.supplement =='0'){
					userContent += (index+1)+'、';
					if(arr.done=='0'){
						userContent+='<img src="resources/images/diary/unfinish.jpg"/>&nbsp;&nbsp;';
					}else{
						userContent+='<img src="resources/images/diary/finish.jpg"/>&nbsp;&nbsp;';
					}
					userContent += arr['content']+'&nbsp;&nbsp;(<span class="time">'+arr['plantime']+'</span>)<br/>';
					time += arr['plantime']+'<br/>';
					//time
					aa = ' <span class="word">'+userContent+'</span>';
				}else{
					flag = flag +1;
					supContent += (index+1)+'、';
					if(arr.done=='0'){
						supContent+='<img src="resources/images/diary/unfinish.jpg"/>&nbsp;&nbsp;';
					}else{
						supContent+='<img src="resources/images/diary/finish.jpg"/>&nbsp;&nbsp;';
					}
					supContent += arr['content']+'<br/>';
				}	
			})
			if(flag<=1){
				$('#dia_supplement').css('height','34px');
			}else{
				$('#dia_supplement').css('height','');
			}
			$('#dia_supplement').html(supContent);
			$('#plancon').html(aa);
		}else{
			$('#plancon').css('height','34px');
			$('#dia_supplement').css('height','34px');
		}
		myScroll.refresh();	
	},
	showCommentView:function(json){
		if(json.success == true && json.data.length>0){
			var content = '';
			$.each(json.data,function(index,array){
				var str = '';	
					str += '<div class="commain"><div class="comimg">';
					str += '<img src="'+array['face']+'">';
					str += '<span class="commname">'+array['truename']+'</div>';
					str += '<div class="cominfo" data-uid="'+array['uid']+'">';
					str+= '<div class="comrig"><button type="button" class="btn btn-danger delete" data-id="'+array['id']+'">删除</button>';
					str += '<div class="comright"><p class="cc">'+array['content']+'</p>';
					str += '<span class="time2">'+array['posttime']+'</span>';
					str += '</div>';
					str += '</div></div></div>';
				$('.comm').append(str);
			});
			myScroll.refresh(); 
		}		
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
	var did = myFns.getUriString('did');
	var opt = myFns.getUriString('opt');
	//加载数据
	getJsonData('m.php?app=monitor&func=mgr&action=diary&task=getTodayData');
	function getJsonData(url){
		$.ajax({
			url: url,
			dataType: 'json', 
			method: 'post',
			data: {did:did},
			success:function(json){
				myFns.showView(json);
			}
		});
	}

	$(document).on('touchstart','#btnBack',function(){
		var uid = $('.user').attr('data_uid');
		if(opt == 'show'){
			window.location.href='m.php?app=monitor&func=mgr&action=diary&task=loadPage&from=reviewForm&did='+did;
		}else{
			window.location.href="m.php?app=monitor&func=mgr&action=diary&task=loadPage&from=reviewIndex&uid="+uid;
		}
	});

	$(document).on('touchstart','.download',function(){
		var link = $(this).attr('data-src');
		window.location.href = link;
	});
	//附件查看
	$(document).on('touchstart','.brower',function(){
		var width = $(window).width(), height = $(window).height();
		var fileName = $(this).attr('name');
		var fileExt  = $(this).attr('ext');
		var fileSrc = $(this).attr('data-src');
		var host = window.location.host; //当前域名
		var fileAllSrc = host+'/'+fileSrc;
		if(myFns.isWeixn()){
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
		} else {
			try {
				CNOAApp.viewDocument(fileAllSrc, fileName, fileExt);
			} catch(e) {
				var file = fileName.split('.');
				window.location.href = 'js://push_web_view_controller__'+fileAllSrc+'__#'+file[0];
			}
		}
	});

	//提交评阅
	$(document).on('touchstart','.submit',function(){
		var review = $('.review').val();
		$.ajax({
			url: 'm.php?app=monitor&func=mgr&action=diary&task=addComment',
			dataType: 'json', 
			method: 'post',
			data: {did:did,comment:review},
			success:function(json){
				if(json.success==true){
					jSuccess(json.msg,{
				     autoHide : true,                // 是否自动隐藏提示条 
				     clickOverlay : false,            // 是否单击遮罩层才关闭提示条 
				     MinWidth : 20,                    // 最小宽度 
				     TimeShown : 1500,                 // 显示时间：毫秒 
				     ShowTimeEffect : 200,             // 显示到页面上所需时间：毫秒 
				     HideTimeEffect : 200,             // 从页面上消失所需时间：毫秒 
				     LongTrip : 15,                    // 当提示条显示和隐藏时的位移 
				     HorizontalPosition : "center",     // 水平位置:left, center, right 
				     VerticalPosition : "center",     // 垂直位置：top, center, bottom 
				     ShowOverlay : false,                // 是否显示遮罩层 
				     ColorOverlay : "#000",            // 设置遮罩层的颜色 
				     OpacityOverlay : 0.3,            // 设置遮罩层的透明度 
				     onClosed:function(){
				     	window.location.href="m.php?app=monitor&func=mgr&action=diary&task=loadPage&from=reviewForm&opt=show&did="+did;
				     }
					});
				}
			}
		});
	});

	//加载评论信息
	if(opt == 'show'){
		$('.reviewInfo').css('display','block');
		$('.review_diary').css('display','none');
		$('.submit').css('display','none');
		$.ajax({
			url: 'm.php?app=monitor&func=mgr&action=diary&task=loadComment',
			dataType: 'json', 
			method: 'post',
			data: {did:did},
			success:function(json){
				myFns.showCommentView(json);
			}
		});
	}else{
		$('.reviewInfo').css('display','none');
	}

	//删除
	$(document).on('touchstart','.delete',function(){
		var id = $(this).attr('data-id');
		$.ajax({
			url: 'm.php?app=monitor&func=mgr&action=diary&task=deleteComment',
			dataType: 'json', 
			method: 'post',
			data: {id:id},
			success:function(json){
				if(json.success == true){
					jSuccess(json.msg,{
				     autoHide : true,                // 是否自动隐藏提示条 
				     clickOverlay : false,            // 是否单击遮罩层才关闭提示条 
				     MinWidth : 20,                    // 最小宽度 
				     TimeShown : 1500,                 // 显示时间：毫秒 
				     ShowTimeEffect : 200,             // 显示到页面上所需时间：毫秒 
				     HideTimeEffect : 200,             // 从页面上消失所需时间：毫秒 
				     LongTrip : 15,                    // 当提示条显示和隐藏时的位移 
				     HorizontalPosition : "center",     // 水平位置:left, center, right 
				     VerticalPosition : "center",     // 垂直位置：top, center, bottom 
				     ShowOverlay : false,                // 是否显示遮罩层 
				     ColorOverlay : "#000",            // 设置遮罩层的颜色 
				     OpacityOverlay : 0.3,            // 设置遮罩层的透明度 
				     onClosed:function(){
						window.location.href="m.php?app=monitor&func=mgr&action=diary&task=loadPage&from=reviewForm&opt=show&did="+did;				     }
					});
				}else{
					jError(json.msg,{
						autoHide : true,                // 是否自动隐藏提示条 
						clickOverlay : false,            // 是否单击遮罩层才关闭提示条 
						MinWidth : 20,                    // 最小宽度 
						TimeShown : 1500,                 // 显示时间：毫秒 
						ShowTimeEffect : 200,             // 显示到页面上所需时间：毫秒 
						HideTimeEffect : 200,             // 从页面上消失所需时间：毫秒 
						LongTrip : 15,                    // 当提示条显示和隐藏时的位移 
						HorizontalPosition : "center",     // 水平位置:left, center, right 
						VerticalPosition : "center",     // 垂直位置：top, center, bottom 
						ShowOverlay : false,                // 是否显示遮罩层 
						ColorOverlay : "#000",            // 设置遮罩层的颜色 
						OpacityOverlay : 0.3            // 设置遮罩层的透明度 
					});
				}
			}
		});
	});
})
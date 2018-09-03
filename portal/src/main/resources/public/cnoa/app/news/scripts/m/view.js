
//判断当前客户端Android和ios
var userAgent = ''; //浏览器类型
if(/android/ig.test(navigator.userAgent)){ //安卓
	var userAgent = 'android';
}else{ //ios或者其他
	var userAgent = 'ios';
}

//全局变量`
var myScroll,myScrolls; //全局对象 

//全局变量`函数
var myFns = {
	//判断当前是否是微信浏览器
	isWeiXin: function(){
		var ua = window.navigator.userAgent.toLowerCase();
		if(ua.match(/MicroMessenger/i) == 'micromessenger'){
		  	return true;
		}else{
		  	return false;
		}
	},
	//判断数组中是否包含某元素
	in_array:function(arr,str){
		var i = arr.length;
		if(i > 0){
			while(i--){
				if(arr[i] === str){
					return true;
				}
			}
		}
		return false;
	},
	/**
	 *获取url参数值
	 *参数：键
	 **/
	getUrlValue:function(key){
	    var reg = new RegExp("(^|&)"+ key +"=([^&]*)(&|$)");
	    var r = window.location.search.substr(1).match(reg);
	    if(r!=null)return  unescape(r[2]); return null;
	},

	//文件图标
	set_file_ico:function(ext){
		var ext = ext.toLocaleLowerCase(); //大写转小写
		var extArray = ["folder","xls","xlsx","doc","docx","ppt","jpg","bmp","png","pdf","gif","rar","xml","html","php","css","zip","txt","swf","wav","mp4","wmv","flv","apk","mp3","rmvb","svg"];
		if(myFns.in_array(extArray,ext)){//定义的class样式存在
			$(".attach .icon:last").addClass("ico-"+ext);
		}else{//未找到已定义的class样式
			$(".attach .icon:last").addClass("ico-file");
		}
	},
	/**
	 *初始化iScroll控件
	**/
	loaded:function(){
			myScroll = new iScroll('wrapper', {
			scrollbarClass: 'myScrollbar',
			wheelAction: 'scroll',
			zoom: true
		});
		document.addEventListener('touchmove', function (e) { e.preventDefault(); }, false);
	},
	//判断是否不是图片，是否支持浏览
	is_image:function(ext){
		var ext = ext.toLowerCase(); //大写转小写
		var view = ['jpg','jpeg','png','gif','bmp']; //在线查看类型
		var i = view.length;
		while(i--){
			if(view[i] === ext){
				return true;
			}
		}
		return false;
	},
	//ios支持在线浏览文件
	allow_view_file: function(ext){
		var ext = ext.toLowerCase(); //大写转小写
		var view = ['xls','xlsx','doc','docx','ppt','pdf','txt']; //在线查看类型
		var i = view.length;
		while(i--){
			if(view[i] === ext){
				return true;
			}
		}
		return false;
	},
	//文件大小转换
	file_size_convert:function(file_size){
		if(file_size < 1024){
			filesSize = file_size.toFixed(0) + 'B';
		}else if(file_size < ( 1024 * 1024 )){
			filesSize = (file_size / 1024).toFixed(0) + 'KB';
		}else if(file_size < ( 1024 * 1024 * 1024)){
			filesSize = (file_size / 1024 / 1024).toFixed(1)  + 'M';
		}else{ 
			filesSize = (file_size / 1024 / 1024 / 1024).toFixed(1) + 'G';
		}
		return filesSize;
	},

	//替换标题样式
	replace: function(){
		$('#noticeCon .list-group-item-name').find('span').each(function(){
   			$(this).css('font-size','15px')
		})
	},
	append_data:function(json,isEmpty){
		if (isEmpty) {
			$('#myTabContent').empty();
		}

		if(json.success && json.data != null){
			var array = json.data;
			var str = "<div class=\"tab-pane fade in active\" id=\"basicInfo\" role=\"tabpanel\" aria-labelledby=\"profile-tab\">";
			str += "<div class=\"panel\"><div class=\"panel-body\"><div class=\"list-group\">";
			str += "<input type='hidden' id=\"hideId\" value="+array['id']+">";
			str += "<div href=\"#\" class=\"list-group-item userInfo\"><span class=\"user\">";
			str += "<span class=\"userDept\">"+array['dept']+'-'+array['user']+"</span>";
			str += "<span class=\"posttime\">"+array['posttime']+"</span>";
			str += "</span>";
			str += "<span class=\"noticeTitle\">"+array['title']+"</span>";
			str += "<img src="+array['face']+" class=\"img-circle face\"></div>";
			str += "<div href=\"#\" class=\"list-group-item\" id=\"noticeCon\"><span class=\"list-group-item-name\">"+array['content']+"</span></div>";
			//附件
			if(array['attach'] != ''){
				str += "<div href=\"#\" class=\"list-group-item item-heading\">附件</div>";
				str += "<div href=\"#\" class=\"list-group-item attach\"></div>";
			}
			str += "</div></div></div></div>";

			$('#myTabContent').append(str);
			if(array['attach'] != ''){ //有附件
				$.each(array['attach'],function(i,array){

					var attachStr = "<div class=\"panel attachs\">";
					attachStr	 += "<header class=\"panel-heading\">";
					attachStr 	 += "<div class=\"title\" data-ext="+array['ext']+" >";
					attachStr 	 += "<div class=\"name-box\">";
					attachStr 	 += "<div class=\"name\"></div>";
					attachStr 	 += "<div class=\"info\">";
					attachStr 	 += "<div class=\"state\">"+array['name']+"</div>";
					attachStr 	 += "</div>";
					attachStr 	 += "</div>";
					attachStr 	 += "<div class=\"glyphicon glyphicon-collapse-down enter\"></div>";
					attachStr 	 += "</div>";
					attachStr 	 += "</header>";
					attachStr 	 += "<div class=\"panel-body attachInfo\" style=\"display: none;\">";
					attachStr 	 += "<button type=\"button\" class=\"btn btn-default btn-md btn-block btnBrowse\" data-src="+array['url']+">在线查看</button>";
					attachStr 	 += "<button type=\"button\" class=\"btn btn-default btn-md btn-block btnFileView\" data-src="+array['url']+">在线浏览</button>";
					attachStr 	 += "<button type=\"button\" class=\"btn btn-default btn-md btn-block downlink\" data-src="+array['url']+array['name']+">下载文件</button>";
					attachStr 	 += "</div>";
					attachStr 	 += "</div>";
					$('#myTabContent .attach').append(attachStr);
					$('#myTabContent .attach .title:last').attr('data-downhref',array['url']);
					$('#myTabContent .attach .title:last').attr('data-ext',array['ext']);
					myFns.set_file_ico(array['ext']);
					if(!myFns.is_image(array['ext'])){ //非图片类型隐藏图片按钮
						$('#myTabContent .attach .btnBrowse:last').css('display','none');
						$('#myTabContent .attach .btnFileView:last').css('display','none');
					}
					if(myFns.is_image(array['ext'])){
						$('#myTabContent .attach .btnFileView:last').css('display','none');
						$('#myTabContent .attach .downlink:last').css('display','none');
					}
					if(userAgent == 'ios'){ //ios客户端
						$("#myTabContent .downlink:last").css('display','none'); //ios不提供下载
						if(myFns.allow_view_file(array['ext'])){ //支持在线浏览文件
							$('#myTabContent .btnFileView:last').show();
						}
					}
				})

				if(array['attach'].length == 1){
				    $('.enter').removeClass("glyphicon-collapse-down").addClass("glyphicon-collapse-up");
				    $('.attachInfo').show();
				    setTimeout(function(){//延迟刷新
						myScroll.refresh();//数据加载完成调用界面更新方法
					},300)
				}
			}
		}

		//初始化图片缩放控件
		ImagesZoom.init({
		  "elem": ".btnBrowse"
		});
		
		//数据加载完成调用界面更新方法
		myFns.replace();
		setTimeout(function(){
			myScroll.refresh();
		},500)
	}

}


$(function(){
	$("#btn_actionsheet").on("touchstart",function(){
		$('#jingle_popup').slideDown(100);
	    $('#jingle_popup_mask').show();
	    return false;
		
	});
	$(document).on('touchstart','#btnAgree',function(){
		//获取id
		var id = myFns.getUrlValue('id'); //公告id
		//跳转到阅读情况
		window.location.href="m.php?app=news&func=notice&action=index&task=loadPage&from=reader&id="+id;
	});
	$(document).on('touchstart','#btn-cancel',function(){
	    $('#jingle_popup').slideUp(100);
	    $('#jingle_popup_mask').hide();
	    return false;
		});
	//页面加载完成调用`初始化页面
	myFns.loaded(); //初始化iScroll控件

	var width = $(document).width();
	$('#noticeCon img').css('max-width',width);
	
	//效果折叠
	$(document).on("touchstart","#myTabContent .attach .enter",function(){
		var el = $(this).parents(".attachs").children(".panel-body");
	    if ($(this).hasClass("glyphicon-collapse-up")) {
	        $(this).removeClass("glyphicon-collapse-up").addClass("glyphicon-collapse-down");
	        el.slideUp(200);
	    } else {
	    	$('#myTabContent .attach .panel-body').slideUp(200);
	    	$('#myTabContent .attach .enter').removeClass("glyphicon-collapse-up").addClass("glyphicon-collapse-down");
	        $(this).removeClass("glyphicon-collapse-down").addClass("glyphicon-collapse-up");
	        el.slideDown(200);
	    }
	    setTimeout(function(){//延迟刷新
			myScroll.refresh();//数据加载完成调用界面更新方法
		},300)
		return false;
	})


	//导航`返回到公告列表
	$(document).on('touchstart','#btnBack',function(){
		window.location.href='m.php?app=news&func=notice&action=index&task=loadPage&from=list';
	})

	//公告内容
	function basicInfoFns(){
		var id = myFns.getUrlValue('id'); //公告id
		$.ajax({
			type: 'get',
			dataType: 'json',
			data: {'id':id,'task':'viewNoticeM'},
			url: 'm.php?app=news&func=notice&action=index',
			beforeSend:function(){
				//请求数据前执行
				showLoading();
			},
			success:function(json){
				//请求成功执行
				myFns.append_data(json,true);
				hideLoading();
			}
		})
	}

	//页面加载完成调用公告
	basicInfoFns();

	//下载文件
	$(document).on('click','.downlink',function(){
		var downhref = $(this).attr('data-src');
		// console.log(downhref);
		var url = 'index.php?action=downFile&from=fs&code=' + downhref;
		window.location.href = url;
	})

	//ios文件在线浏览
	$(document).on('click','.btnFileView',function(){
		var fileSrc  = $(this).attr('data-src'); //文件路径
		var fileName = $(this).closest('.name-box').children('.name').text();
		var	seat = fileName.lastIndexOf('.'); //查找某字符在字符串中最后一次出现的位置
		fileName = fileName.substring(0, seat); //纯文件名
		var fileExt = $(this).parents('.attach').find('.title').attr('data-ext'); //文件后缀
		if (myFns.isWeiXin()) {
		    var height = $(window).height();
		    var width = $(window).width();
			iospopWin.showWin(width,height,'在线浏览',fileSrc,fileExt.toLocaleLowerCase());
			return false;
		};

		var host = window.location.host;
		var fileAllSrc = host+'/'+fileSrc;
		try {
			//新版
			CNOAApp.viewDocument(fileAllSrc, fileName, fileExt);
		}catch (e) {
			//旧版
			window.location.href = 'js://push_web_view_controller__'+fileAllSrc+'__'+fileName;
		}
	});
})
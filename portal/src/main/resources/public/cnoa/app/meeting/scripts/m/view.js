//判断当前客户端Android和ios
var userAgent = ''; //浏览器类型
if(/android/ig.test(navigator.userAgent)){ //安卓
	var userAgent = 'android';
}else{ //ios或者其他
	var userAgent = 'ios';
}

// 全局函数
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
		var view = ['xls','xlsx','doc','docx','ppt','txt']; //在线查看类型
		var i = view.length;
		while(i--){
			if(view[i] === ext){
				return true;
			}
		}
		return false;
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
	/*
	 *ajax成功返回数据
	 *json：ajax请求成功返回的data
	 *当前会议阅读的详细信息
	 */
	assignment_data: function(json){
		if (json.data != null) {
			$('.name').html(json.data.name);
			$('.type').html(json.data.type);
			$('.plan').html(json.data.plan);
			$('.title').html(json.data.title);
			$('.stime').html(json.data.stime);
			$('.etime').html(json.data.etime);
			$('.mgrman').html(json.data.mgrman);
			$('.attend').html(json.data.attend);
			$('.viewer').html(json.data.viewer);
			$('.join').html(json.data.attendee);
			$('.njoin').html(json.data.notattendee);
			$('.contain').html(json.data.contain);
			$('.address').html(json.data.address);
			$('.opinion').html(json.data.opinion);
			$('.markname').html(json.data.markname);
			$('.equipment').html(json.data.equipment);
			$('.descripts').html(json.data.descripts);
			$('.markdetail').html(json.data.markdetail);
			$('.otherattend').html(json.data.otherattend);
			$('.meetingroomName').html(json.data.meetingroomName);
			if (json.data.attach == "") {
				$('.attachT').css('display',"none");
			} else {
				$.each(json.data.attach,function(i,array){
					var attachStr = "<div class=\"panel attachs\">";
					attachStr 	 += "<header class=\"panel-heading\"><span class=\"attachTitle\">";
					attachStr 	 += "<span class=\"name-box\"><span class=\"attachN\"></span>";
					attachStr 	 += "<div class=\"info\"><span class=\"state\">"+array['name']+"</span></div></span>";
					attachStr 	 += "<span class=\"glyphicon glyphicon-collapse-down enter\"></span></span></header>";
					attachStr 	 += "<div class=\"panel-body attachInfo\" style=\"display: none;\">";
					attachStr 	 += "<button type=\"button\" class=\"btn btn-default btn-md btn-block btnBrowse\" data-src="+array['url']+">在线查看</button>";
					attachStr 	 += "<button type=\"button\" class=\"btn btn-default btn-md btn-block btnFileView\" data-src="+array['url']+">在线浏览</button>";
					attachStr 	 += "<button type=\"button\" class=\"btn btn-default btn-md btn-block downlink\" data-src="+array['url']+">下载文件</button>";
					attachStr 	 += "</div></div>";
					$('#myAttach').append(attachStr);
					$('#myAttach .title:last').attr('data-downhref',array['url']);
					$('#myAttach .title:last').attr('data-ext',array['ext']);
					myFns.set_file_ico(array['ext']);
					// if(!myFns.is_image(array['ext'])){ //非图片类型隐藏图片按钮
					// 	$('#myAttach .attachInfo .btnBrowse:last').css('display','none');
					// }
					if(userAgent == 'android' && !myFns.is_image(array['ext'])){
						$('#myAttach .attachInfo .btnFileView:last').css('display','none');
						$('#myAttach .attachInfo .btnBrowse:last').css('display','none');
						$('#myAttach .attachInfo .downlink:last').css('display','');
					}else if(myFns.is_image(array['ext'])){
						$('#myAttach .attachInfo .downlink:last').css('display','none');
						$('#myAttach .attachInfo .btnFileView:last').css('display','none');
						$('#myAttach .attachInfo .btnBrowse:last').css('display','');
					}

					if(userAgent == 'ios'){ //ios客户端
						$("#myAttach .attachInfo .downlink:last").css('display','none'); //ios不提供下载
						if(myFns.allow_view_file(array['ext'])){ //支持在线浏览文件
							$('#myAttach .attachInfo .btnFileView:last').show();
							$('#myAttach .attachInfo .btnBrowse:last').hide();
						} else {
							$('#myAttach .attachInfo .btnFileView:last').hide();
						}
					}
				})
			}

			if (json.data.markattach == "") {
				$('.markAttachT').css('display',"none");
			} else {
				$.each(json.data.markattach,function(i,array){
					var markAttachStr = "<div class=\"panel attachs\">";
					markAttachStr 	 += "<header class=\"panel-heading\"><span class=\"attachTitle\">";
					markAttachStr 	 += "<span class=\"name-box\"><span class=\"attachN\"></span>";
					markAttachStr 	 += "<div class=\"info\"><span class=\"state\">"+array['name']+"</span></div></span>";
					markAttachStr 	 += "<span class=\"glyphicon glyphicon-collapse-down enter\"></span></span></header>";
					markAttachStr 	 += "<div class=\"panel-body markAttachInfo\" style=\"display: none;\">";
					markAttachStr 	 += "<button type=\"button\" class=\"btn btn-default btn-md btn-block btnBrowse\" data-src="+array['url']+">在线查看</button>";
					markAttachStr 	 += "<button type=\"button\" class=\"btn btn-default btn-md btn-block btnFileView\" data-src="+array['url']+">在线浏览</button>";
					markAttachStr 	 += "<button type=\"button\" class=\"btn btn-default btn-md btn-block downlink\" data-src="+array['url']+">下载文件</button>";
					markAttachStr 	 += "</div></div>";
					$('#markAttach').append(markAttachStr);
					$('#markAttach .title:last').attr('data-downhref',array['url']);
					$('#markAttach .title:last').attr('data-ext',array['ext']);
					myFns.set_file_ico(array['ext']);
					// if(!myFns.is_image(array['ext'])){ //非图片类型隐藏图片按钮
					// 	$('#markAttach .markAttachInfo .btnBrowse:last').css('display','none');
					// }
					if(userAgent == 'android' && !myFns.is_image(array['ext'])){
						$('#markAttach .markAttachInfo .btnFileView:last').css('display','none');
						$('#markAttach .markAttachInfo .btnBrowse:last').css('display','none');
						$('#markAttach .markAttachInfo .downlink:last').css('display','');
					}else if(myFns.is_image(array['ext'])){
						$('#markAttach .markAttachInfo .downlink:last').css('display','none');
						$('#markAttach .markAttachInfo .btnFileView:last').css('display','none');
						$('#markAttach .markAttachInfo .btnBrowse:last').css('display','');
					}

					if(userAgent == 'ios'){ //ios客户端
						$("#markAttach .markAttachInfo .downlink:last").css('display','none'); //ios不提供下载
						if(myFns.allow_view_file(array['ext'])){ //支持在线浏览文件
							$('#markAttach .markAttachInfo .btnFileView:last').show();
							$('#markAttach .markAttachInfo .btnBrowse:last').hide();
						} else {
							$('#markAttach .markAttachInfo .btnFileView:last').hide();
						}
					}

					if (myFns.isWeiXin()) {
						$('#markAttach .markAttachInfo .downlink').hide();
					}
				})
			}
			//初始化图片缩放控件
			ImagesZoom.init({
			  "elem": ".btnBrowse"
			});
		}
	},

	/*
	 *ajax成功返回数据
	 *json：ajax请求成功返回的data
	 *isEmpty：是否清空数据区 true：清空 false：不清空数据区
	 *当前会议阅读的阅读人员的列表
	 */
	 readerList_data: function(json,isEmpty){
		$("#con_box").empty();
		if(json.success && json.data.length > 0){
			$.each(json.data, function(index,array){
				var str = "<tr>";
					str += "<td>"+(index+1)+"</td>";
					str += "<td>"+array['deptname']+"</td>";
					str += "<td>"+array['name']+"</td>";
					str += "<td>"+array['readtime']+"</td>";
					str += "</tr>";
				$('#con_box').append(str);
			})
		}
	 },
	 /*
	  *返回不参加理由
	  */
	notJoinReason: function(json){
		if(json.success && json.data.length > 0){
			$('.reason').text(json.data);
		}
	},
	/**
	 *	方法: myFns.is_type(type)
	 *	功能: 判断该类型是否在数组中
	 *	返回: <boolean> 返回true false
	 */
	 is_type: function(type){
	 	var value = ['join','needMe','waiting','pass'];
	 	if($.inArray(type,value) < 0){
			return false;
		}
		return true;
	 }
}

$(function(){
	var rid = myFns.getUrlValue('rid');
	var aid = myFns.getUrlValue('aid');
	var type = myFns.getUrlValue('type');
	// if (myFns.is_type(type)) {
	// 	$('.reader').hide();
	// 	$('#wrapper').css('top','0px');
	// }
	function loadData(url){
		$.ajax({
			dataType: "json",
			type: "post",
			url: url,
			data:{
				"rid":rid
			},
			success: function(json){
				if (json.success) {
					get_data('m.php?app=meeting&func=mgr&action=meeting&task=viewMeetingRoomApplyDetails&aid='+aid);
				}
			},
			complete: function(XMLHttpRequest, textStatus){
				$(".ui-ios-overlay").removeClass("ios-overlay-show").addClass("ios-overlay-hide");
			}
		})
	}


	function get_data(url){
		$.ajax({
			dataType: "json",
			type: "post",
			url: url,
			success: function(json){
				myFns.assignment_data(json);
			},
			complete: function(XMLHttpRequest, textStatus){
				$(".ui-ios-overlay").removeClass("ios-overlay-show").addClass("ios-overlay-hide");
			}
		})
	}

	loadData('m.php?app=meeting&func=mgr&action=meeting&task=readed');

	// 返回到会议阅读列表
	$(document).on('touchstart','#btnBack',function(){
		if (type == 'join') {
			window.location.href = 'm.php?app=meeting&func=mgr&action=join&task=loadPage';
		} else if (type == 'waiting') {
			window.location.href = 'm.php?app=meeting&func=mgr&action=join&task=loadPage&from=waiting';
		} else if (type == 'pass') {
			window.location.href = 'm.php?app=meeting&func=mgr&action=join&task=loadPage&from=pass';
		} else if (type == 'needMe') {
			window.location.href = 'm.php?app=meeting&func=mgr&action=meeting&task=loadPage&from=list';
		} else if (type == 'approval'){
			window.location.href = 'm.php?app=meeting&func=mgr&action=join&&task=loadPage&from=meetingPassList';
		} else {
			window.location.href = 'm.php?app=meeting&func=mgr&action=meeting&task=loadPage&from=meetReading';
		}
		return false;
	})


	//效果折叠
	$(document).on("touchstart","#myAttach .enter",function(){
		var el = $(this).parents(".attachs").children(".panel-body");
	    if ($(this).hasClass("glyphicon-collapse-up")) {
	        $(this).removeClass("glyphicon-collapse-up").addClass("glyphicon-collapse-down");
	        el.slideUp(200);
	    } else {
	    	$('#myAttach .panel-body').slideUp(200);
	    	$('#myAttach .enter').removeClass("glyphicon-collapse-up").addClass("glyphicon-collapse-down");
	        $(this).removeClass("glyphicon-collapse-down").addClass("glyphicon-collapse-up");
	        el.slideDown(200);
	    }
	})

	//效果折叠
	$(document).on("touchstart","#markAttach .enter",function(){
		var el = $(this).parents(".attachs").children(".panel-body");
	    if ($(this).hasClass("glyphicon-collapse-up")) {
	        $(this).removeClass("glyphicon-collapse-up").addClass("glyphicon-collapse-down");
	        el.slideUp(200);
	    } else {
	    	$('#markAttach .panel-body').slideUp(200);
	    	$('#markAttach .enter').removeClass("glyphicon-collapse-up").addClass("glyphicon-collapse-down");
	        $(this).removeClass("glyphicon-collapse-down").addClass("glyphicon-collapse-up");
	        el.slideDown(200);
	    }
	})

	//下载文件
	$(document).on('click','.downlink',function(){
		var downhref = $(this).attr('data-src');
		var url = 'index.php?action=downFile&from=fs&code=' + downhref;
		window.location.href = url;
	})

	//ios文件在线浏览
	$(document).on('click','.btnFileView',function(){
		// $('#header').hide();
		var fileSrc  = $(this).attr('data-src'); //文件路径
		if (myFns.isWeiXin()) {
		    var height = $(window).height();
		    var width = $(window).width();
			iospopWin.showWin(width,height,'在线浏览',fileSrc);
			return false;
		};
		var fileName = $(this).closest('.name-box').children('.name').text();
		var	seat = fileName.lastIndexOf('.'); //查找某字符在字符串中最后一次出现的位置
		fileName = fileName.substring(0, seat); //纯文件名
		var fileExt = $(this).closest('.title').attr('data-ext'); //文件后缀
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

	$(document).on('touchstart','.reader',function(){
		get_readList('m.php?app=meeting&func=mgr&action=meeting&task=getReaderList&aid='+aid);
	})
	var uid = myFns.getUrlValue('uid');
	if(uid){
		$.ajax({
			dataType: "json",
			type: "get",
			url: 'm.php?app=meeting&func=mgr&action=meeting&task=notJoinReason&aid='+aid+'&uid='+uid,
			success: function(json){
				$('#wrapper').css("overflow",'hidden');
				$('.readerContent').show();
				$('.bordered').hide();
				$('.readHeader .readTit').text('不参加原因');
				myFns.notJoinReason(json);
			},
			complete: function(XMLHttpRequest, textStatus){
				$(".ui-ios-overlay").removeClass("ios-overlay-show").addClass("ios-overlay-hide");
			}
		})
	}
	function get_readList(url){
		$.ajax({
			dataType: "json",
			type: "post",
			url: url,
			success: function(json){
				$('#wrapper').css("overflow",'hidden');
				$('.readerContent').show();
				$('.readHeader .readTit').text('阅读情况');
				myFns.readerList_data(json,true);
			},
			complete: function(XMLHttpRequest, textStatus){
				$(".ui-ios-overlay").removeClass("ios-overlay-show").addClass("ios-overlay-hide");
			}
		})
	}

	$(document).on('touchstart','#btnCancel',function(){
		$('#wrapper').css("overflow",'auto');
		if(uid){
			window.location.href="m.php?app=meeting&func=mgr&action=meeting&&task=loadPage&t=show&from=view&aid="+aid+"&&type=join";	
		}else{
			$('.readerContent').hide();
		}
		
	})
})
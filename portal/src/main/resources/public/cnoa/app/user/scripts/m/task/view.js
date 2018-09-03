var sendeeObj = {}; //接收人全局对象
var myScroll,userDataScroll,userScroll;  
//myScroll.refresh();//数据加载完成后调用界面更新方法
var sendeeArr = [];//全局对象和数组 
//判断当前客户端Android和ios
var userAgent = ''; //浏览器类型
if(/android/ig.test(navigator.userAgent)){ //安卓
	var userAgent = 'android';
}else{ //ios或者其他
	var userAgent = 'ios';
}
var etimes = '',status;


//全局变量`函数
var myFns = {
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
	// //加载等待效果
	// showLoading:function(text){
	// 	var opts = {
	// 		lines: 13, // 画线数
	// 		length: 11, // 每条线的长度
	// 		width: 5, // 线厚度
	// 		radius: 17, // 内圆半径
	// 		corners: 1, // 角圆度(0....1)
	// 		rotate: 0, // 旋转偏移
	// 		color: '#FFF', // 颜色 例：#rgb 和 #rrggbb
	// 		speed: 1, // 每秒轮
	// 		trail: 60, // 余辉百分率
	// 		shadow: false, // 是否渲染一个阴影
	// 		hwaccel: false, // 是否使用硬件加速
	// 		className: 'spinner', // CSS类分配给纺织
	// 		zIndex: 2e9, // z-index（默认为2000000000）
	// 		top: 'auto', // 在像素中相对于父的顶部位置
	// 		left: 'auto' // 左位置相对于父在像素
	// 	};
	// 	var target = document.createElement("div");
	// 	document.body.appendChild(target);
	// 	var spinner = new Spinner(opts).spin(target);
	// 	iosOverlay({
	// 		text: text,
	// 		spinner: spinner
	// 	});
	// 	return false;
	// },
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
	loaded:function(element){
		myScroll = new iScroll(element, {
			scrollbarClass: 'myScrollbar',
			wheelAction: 'scroll'
		});
		setTimeout(function () { document.getElementById(element).style.left = '0'; }, 800);
		document.addEventListener('touchmove', function (e) { e.preventDefault(); }, false);
	},
	// loadeds: function(element){
	// 		//初始化绑定iScroll控件
	// 		myScrolls = new iScroll(element, {
	// 		scrollbarClass: 'myScrollbar',
	// 		wheelAction: 'scroll'
	// 	});
	// 	setTimeout(function () { document.getElementById(element).style.left = '0'; }, 800);
	// 	document.addEventListener('touchmove', function (e) { e.preventDefault(); }, false);
	// },

	/**
	 *	方法: myFns.userDataLoaded()
	 *	功能: 初初始化人员选择器用户部门iScroll控件
	 */
	userDataLoaded: function(element){
		userDataScroll = new iScroll(element, {
			scrollbarClass: 'myScrollbar',
			wheelAction: 'scroll'
		});
		setTimeout(function () { document.getElementById(element).style.left = '0'; }, 800);
		document.addEventListener('touchmove', function (e) { e.preventDefault(); }, false);
	},
	/**
	 *	方法: myFns.userLoaded()
	 *	功能: 初始化人员选择器删除区域iScroll控件
	 */
	userLoaded: function(element){
		userScroll = new iScroll(element, { 
			scrollbarClass: 'myScrollbar',
			wheelAction: 'scroll'
		});
		setTimeout(function () { document.getElementById(element).style.left = '0'; }, 800);
		document.addEventListener('touchmove', function (e) { e.preventDefault(); }, false);
	},
	isWeixn: function (){  
	  var ua = navigator.userAgent.toLowerCase();  
	  if(ua.match(/MicroMessenger/i)=="micromessenger") {  
	    return true;  
	  } else {  
	    return false;  
	  }  
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
			filesSize = file_size + 'B';
		}else if(file_size < ( 1024 * 1024 )){
			filesSize = (file_size / 1024).toFixed(0) + 'KB';
		}else if(file_size < ( 1024 * 1024 * 1024)){
			filesSize = (file_size / 1024 / 1024).toFixed(1)  + 'M';
		}else{ 
			filesSize = (file_size / 1024 / 1024 / 1024).toFixed(1) + 'G';
		}
		return filesSize;
	}

}


$(function(){
	//底部添加计划
	$(document).on('touchstart','#btn_actionsheet',function(){
    $('#jingle_popup').slideDown(100);
    $('#jingle_popup_mask').show();
    return false;
	});
	$(document).on('touchstart','#btn-cancel',function(){
    $('#jingle_popup').slideUp(100);
    $('#jingle_popup_mask').hide();
    return false;
	});

	//关闭弹出窗口 
	$(document).on('touchstart','#jingle_popup_mask',function(event){
		if( $(event.target).is(this)) { 
     $(this).hide();
     $('#jingle_popup').slideUp(100);
    }
    return false;
	})

	//页面加载完成调用`初始化页面
	// myFns.loaded(); //初始化iScroll控件
	// myFns.loadeds('modal-body-wrapper');
	//页面加载完成调用`初始化页面
	function loadedInit(){
		myFns.loaded('wrapper');
		myFns.userDataLoaded('userDataWrapper'); //人员和部门区域
		var userDataWrapperH = $(window).height()-151;
		$('.userDataWrapper').css('height', userDataWrapperH); //人员和部门区域高度
		myFns.userLoaded('userWrapper'); //人员删除操作区域
		if(myFns.isWeixn() && userAgent == 'android'){
			var userWrapperTop = $(window).height()-142; //上线后改为107  147
		} else {
			var userWrapperTop = $(window).height()-142; //上线后改为107  147
		}
		$('.userWrapper').css('top', userWrapperTop); //人员删除操作区域高度
		var userWrapperW = $(window).width() - 84; //userWrapper宽度
		$('#userWrapper').css('width', userWrapperW);
		var userScrollerW = $('#userGroup li').length * $('#userGroup li').width(); //userScroller宽度
		$('#userScroller').css('width', userScrollerW);
		var optsBoxH = $(window).height() - 54;
		$('#userDataModal .opts-box').css('top', optsBoxH);
		$('#userDataModal .opts-box').css('width', $(window).width());

		//动态改变modal-body的高度 注:减去的为头部+搜索框的高度
		var modalBodyH = $(document).height()-111;
		$('#modal-body-wrapper').css('height',modalBodyH);

		
	}
	loadedInit();

	//效果折叠
	$(document).on("touchstart","#taskActive .enter",function(){
		var el = $(this).parents(".panel").children(".panel-body");
	    if ($(this).hasClass("glyphicon-menu-up")) {
	        $(this).removeClass("glyphicon-menu-up").addClass("glyphicon-menu-down");
	        el.slideUp(200);
	    } else {
	    	$('.panel-body').slideUp(200);
	    	$('.enter').removeClass("glyphicon-menu-up").addClass("glyphicon-menu-down");;
	        $(this).removeClass("glyphicon-menu-down").addClass("glyphicon-menu-up");
	        el.slideDown(200);
	    }
	    setTimeout(function(){//延迟刷新
			myScroll.refresh();//数据加载完成调用界面更新方法
		},300)
	})


	//导航`返回到任务列表
	$(document).on('touchstart','#btnBack',function(){
		window.location.href='m.php?app=user&func=task&action=default&task=loadPage&from=list';
	})

	//基本资料方法
	function basicInfoFns(){
		var tid = myFns.getUrlValue('tid'); //任务id
		$.ajax({
			type: 'get',
			dataType: 'json',
			data: {'tid':tid,'task':'viewTask'},
			url: 'm.php?app=user&func=task&action=default',
			beforeSend:function(){
				//请求数据前执行
				// myFns.showLoading('正在加载，请稍等...');
				showLoading();
			},
			success:function(json){
				//请求成功执行
				hideLoading();
				// $(".ui-ios-overlay").removeClass("ios-overlay-show").addClass("ios-overlay-hide");
				$('#myTabContent').empty();
				if(json.success && json.data != null){
					var array = json.data; 
					etimes = array['etime'];
					status = array['status'];
					var str = "<div class=\"tab-pane fade in active\" id=\"basicInfo\" role=\"tabpanel\" aria-labelledby=\"profile-tab\">";
					str += "<div class=\"panel\"><div class=\"panel-body\"><div class=\"list-group\">";
					str += "<a href=\"#\" class=\"list-group-item\"><span class=\"list-group-item-lable\">任务名称</span><span class=\"list-group-item-name taskName\">"+array['title']+"</span></a>";
					str += "<a href=\"#\" class=\"list-group-item\"><span class=\"list-group-item-lable\">当前状态</span><span class=\"list-group-item-name\">"+array['statusText']+"</span></a>";
					str += "<a href=\"#\" class=\"list-group-item\"><span class=\"list-group-item-lable\">任务布置时间</span><span class=\"list-group-item-name\">"+array['posttime']+"</span></a>";
					str += "<a href=\"#\" class=\"list-group-item\"><span class=\"list-group-item-lable\">布置开始时间</span><span class=\"list-group-item-name\">"+array['stime']+"</span></a>";
					str += "<a href=\"#\" class=\"list-group-item\"><span class=\"list-group-item-lable \">布置结束时间</span><span class=\"list-group-item-name etime\">"+array['etime']+"</span></a>";
					str += "<a href=\"#\" class=\"list-group-item\"><span class=\"list-group-item-lable\">实际开始时间</span><span class=\"list-group-item-name\">"+array['sttime']+"</span></a>";
					str += "<a href=\"#\" class=\"list-group-item\"><span class=\"list-group-item-lable\">实际完成时间</span><span class=\"list-group-item-name\">"+array['entime']+"</span></a>";
					str += "<a href=\"#\" class=\"list-group-item\"><span class=\"list-group-item-lable\">任务进度</span><span class=\"list-group-item-name\">"+array['progress']+"%</span></a>";
					str += "<a href=\"#\" class=\"list-group-item\"><span class=\"list-group-item-lable\">布置人</span><span class=\"list-group-item-name\">"+array['postter']+"</span></a>";
					str += "<a href=\"#\" class=\"list-group-item\"><span class=\"list-group-item-lable\">负责人</span><span class=\"list-group-item-name\">"+array['execman']+"</span></a>";
					str += "<a href=\"#\" class=\"list-group-item\"><span class=\"list-group-item-lable\">审批人</span><span class=\"list-group-item-name\">"+array['examapp']+"</span></a>";
					str += "<a href=\"#\" class=\"list-group-item\"><span class=\"list-group-item-lable\">评估工时</span><span class=\"list-group-item-name\">"+array['worktime1']+"</span></a>";
					str += "<a href=\"#\" class=\"list-group-item\"><span class=\"list-group-item-lable\">确认工时</span><span class=\"list-group-item-name\">"+array['worktime2']+"</span></a>";
					if(array['participant'] != ''){
						str += "<a href=\"#\" class=\"list-group-item item-heading\">参与人</a>";
						str += "<a href=\"#\" class=\"list-group-item\"><span class=\"list-group-item-names\">"+array['participant']+"</span></a>";
					}
					if(array['content']){
						str += "<a href=\"#\" class=\"list-group-item item-heading\">任务内容</a>";
						str += "<a href=\"#\" class=\"list-group-item\"><span class=\"list-group-item-names\">"+array['content']+"</span></a>";
					}
					if(array['attach'] != ''){
						str += "<a href=\"#\" class=\"list-group-item item-heading\">任务附件</a>";
						str += "<a href=\"#\" class=\"list-group-item attach\"></a>";
					}
					if(array['prizepunish']){
						str += "<a href=\"#\" class=\"list-group-item item-heading\">奖罚标准</a>";
						str += "<a href=\"#\" class=\"list-group-item\"><span class=\"list-group-item-names\">"+array['prizepunish']+"</span></a>";
					}
					if(array['leadersay']){
						str += "<a href=\"#\" class=\"list-group-item item-heading\">领导批示</a>";
						str += "<a href=\"#\" class=\"list-group-item\"><span class=\"list-group-item-names\">"+array['leadersay']+"</span></a>";	
					}
					str += "</div></div></div></div>";
					$('#myTabContent').append(str);

					if(array['attach'] != ''){ //有附件
						$.each(array['attach'],function(i,array){
							var attachStr = "<span class=\"title\"><span class=\"icon\"></span><span class=\"name-box\"><span class=\"name\">"+array['name']+"("+myFns.file_size_convert(array['size'])+")</span><div class=\"info\"><botton type=\"botton\" class=\"btn btn-info btn-xs btnBrowse\" data-src="+array['url']+">浏览</botton><botton data-src=\""+array['url']+"\" type=\"botton\" class=\"btn btn-success btn-xs downlink\">下载</botton><botton type=\"botton\" class=\"btn btn-info btn-xs btnFileView\" data-src="+array['url']+">查看</botton></div></span></span>";
							$('#myTabContent .attach').append(attachStr);
							$('#myTabContent .attach .title:last').attr('data-downhref',array['url']);
							$('#myTabContent .attach .title:last').attr('data-ext',array['ext']);
							// $('#myTabContent .attach .btnFileView:last').css('display','none');
							myFns.set_file_ico(array['ext']);
							if(!myFns.is_image(array['ext'])){ //非图片类型隐藏图片按钮
								$('#myTabContent .attach .btnBrowse:last').css('display','none');
							}
							if(userAgent == 'ios'){ //ios客户端
								$("#myTabContent .downlink:last").css('display','none'); //ios不提供下载
								if(myFns.allow_view_file(array['ext'])){ //支持在线浏览文件
									$('#myTabContent .btnFileView:last').show();
								}
							}
						})
					}
					
					if((array['showTip'] == true) && (array['needexamapp'] != '0') && array['tipJob'] == "examapp"){ //负责人 同意`不同意
						$('#btnAgree').css('display','block');
						$('#btnDisagree').css('display','block');
					}
					if((array['showTip'] == true) && (array['needexamapp']) == '0' && array['tipJob'] == "receive"){ //审批人 同意接收和不同意接收
						$('#btnReceive').css('display','block');
						$('#btnRefuse').css('display','block');
					}
					if(array['fenable'] == null){ //负责人按钮和失败按钮
						$('#btnEditPrincipal').css('display','none');
						$('#btnFail').css('display','none');
					}
					if(array['eenable'] == null){ //修改按钮
						$('#btnMod').css('display','none');
					}
					if(array['denable'] == null){ //删除按钮
						$('#btnDel').css('display','none');
					}
					if(array['renable'] == null){ //撤销按钮
						$('#btnRepeal').css('display','none');
					}
					if(array['aenable'] == null){ //汇报按钮
						$('#btnReport').css('display','none');
					}
					if(array['benable'] == null){ //提交审核按钮(完成任务)
						$('#btnComplete').css('display','none');
					}
					if(array['uenable'] == null){ //督办按钮和延期
						$('#btnUrge').css('display','none');
						$('#btnDelay').css('display','none');
					}
					if(array['showTip'] == true && array['tipJob'] == "check") {//同意审核
						$('#btnFinish').css('display','block'); 
						$('#btnNofinish').css('display','block');
					}
				}

				//初始化图片缩放控件
				ImagesZoom.init({
				  "elem": ".btnBrowse"
				});
				
				//数据加载完成调用界面更新方法
				setTimeout(function(){
					myScroll.refresh();
				},200);
					
			}
		})
	}

	//页面加载完成调用任务基本信息
	basicInfoFns();

	$(document).on('click','#btnBasicInfo',basicInfoFns);

	//下载文件
	$(document).on('click','.downlink',function(){
		var downhref = $(this).attr('data-src');
		var url = 'index.php?action=downFile&from=fs&code=' + downhref;
		window.location.href = url;
	})

	//ios文件在线浏览
	$(document).on('click','.btnFileView',function(){
		var fileSrc  = $(this).attr('data-src'); //文件路径
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

	//任务事件方法
	function taskActive(){
		var tid = myFns.getUrlValue('tid'); //任务id
		$.ajax({
			type: 'get',
			dataType: 'json',
			url: 'm.php?app=user&func=task&action=default&tid='+tid+'&status='+status+'&task=getEventList',
			beforeSend:function(){
				//请求数据前执行
				// myFns.showLoading('正在加载，请稍等...');
				showLoading();
			},
			success:function(json){
				hideLoading();
				// $(".ui-ios-overlay").removeClass("ios-overlay-show").addClass("ios-overlay-hide");
				$('#myTabContent').empty();
				if(json.success && json.data != null){
					$.each(json.data,function(index,array){
						var str = "<div class=\"tab-pane fade in active\" id=\"taskActive\" role=\"tabpanel\" aria-labelledby=\"profile-tab\">";
						str += "<div class=\"panel\"><header class=\"panel-heading\"><span class=\"title\"><span class=\"name\">"+array['title']+"</span>";
						str += "<span class=\"glyphicon glyphicon-menu-down enter\"></span></span></header>";
						str += "<div class=\"panel-body\"><a href=\"#\" class=\"list-group-item\"><span class=\"list-group-item-lable\">操作类型</span><span class=\"list-group-item-name\">"+array['typename']+"</span></a>";
						str += "<a href=\"#\" class=\"list-group-item\"><span class=\"list-group-item-lable\">事件标题/内容</span><span class=\"list-group-item-name\">"+array['content']+"</span></a>";
						str += "<a href=\"#\" class=\"list-group-item\"><h5 class=\"list-group-item-line\">操作人/时间</h5><span class=\"list-group-item-names\">"+array['truename']+"</span><span class=\"optTime\">"+array['posttime']+"</span></a>";
						str += "</div></div>";
						$('#myTabContent').append(str);
					})
					myScroll.refresh();
				}
			}
		})
	}

	//调用任务事件方法
	$(document).on('click','#btnTaskActive',taskActive);

	/*
 	 *撤销任务事件
	 *参数：撤销理由,任务id,任务状态
	 */
	function repealFns(reason,tid,status){
		var content = reason; //撤销理由
		var tid = tid; //任务id
		var url = 'm.php?app=user&func=task&action=default&task=operateTask';
		$.post(url,{'tid':tid,'job':'repeal','status':status,'content':content},function(json){
			if(json.success){
			  //   iosOverlay({
					// 	text: json.msg,
					// 	duration: 2e3,
					// 	icon: "../../../../../../resources/images/m/artDialog/check.png"
					// })
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
				     OpacityOverlay : 0.3            // 设置遮罩层的透明度 
				});
			}else{
		  //   	iosOverlay({
				// 	text: json.msg,
				// 	duration: 2e3,
				// 	icon: "../../../../../../resources/images/m/artDialog/cross.png"
				// })
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
		    setTimeout(function(){ //2秒后跳转到任务列表
		    	// hideLoading();
		    	//$(".ui-ios-overlay").removeClass("ios-overlay-show").addClass("ios-overlay-hide");
	   			window.location.href='m.php?app=user&func=task&action=default&task=loadPage&from=list';
		    },2000)
		},'json')
	}

	//触发撤销任务事件
	$(document).on('click','#btnRepeal',function(){
		$('#jingle_popup').hide();
	  $('#jingle_popup_mask').hide();
		var tid = myFns.getUrlValue('tid'); //任务id
		swal({   
			title: "撤销任务",   
			confirmButtonText: "确认撤销",   
			type: "input",   
			showCancelButton: true,   
			closeOnConfirm: false,   
			animation: "slide-from-top",   
			inputPlaceholder: "撤销理由" 
		}, function(inputValue){ 
			if (inputValue === false){
				return false;      
			}
			if (inputValue === "") {     
				swal.showInputError("请输入撤销理由!");     
				return false;   
			}
			$('.sweet-overlay').css('display','none'); //隐藏输入框
			$('.sweet-alert').css('display','none');
			repealFns(inputValue,tid,status); //执行撤销任务
		});
	})

	/*
	 *督办事件
	 *参数：督办内容,任务id,任务状态
	 */
	function urgeFns(content,tid,status){
		var content = content; //失败原因
		var tid = tid; //任务id
		var url = 'm.php?app=user&func=task&action=default&task=operateTask';
		$.post(url,{'tid':tid,'job':'urge','status':status,'content':content},function(json){
			if(json.success){
			  //   iosOverlay({
					// 	text: json.msg,
					// 	duration: 2e3,
					// 	icon: "../../../../../../resources/images/m/artDialog/check.png"
					// })
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
				     OpacityOverlay : 0.3            // 设置遮罩层的透明度 
				});
			}else{
		  //   	iosOverlay({
				// 	text: json.msg,
				// 	duration: 2e3,
				// 	icon: "../../../../../../resources/images/m/artDialog/cross.png"
				// })
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
		    setTimeout(function(){ //2秒后跳转到任务列表
		    	// $(".ui-ios-overlay").removeClass("ios-overlay-show").addClass("ios-overlay-hide");
		    	// hideLoading();
	   			window.location.href='m.php?app=user&func=task&action=default&task=loadPage&from=list';
		    },2000)
		},'json')
	}

	//触发督办事件
	$(document).on('click','#btnUrge',function(){
		$('#jingle_popup').hide();
	    $('#jingle_popup_mask').hide();
		var tid = myFns.getUrlValue('tid'); //任务id
		swal({   
			title: "督办任务",   
			//text: "这里可以输入并确认:",
			confirmButtonText: "确认督办",   
			type: "input",   
			showCancelButton: true,   
			closeOnConfirm: false,   
			animation: "slide-from-top",   
			inputPlaceholder: "督办内容" 
		}, function(inputValue){ 
			if (inputValue === false){
				return false;      
			}
			if (inputValue === "") {     
				swal.showInputError("请输入督办内容!");     
				return false;   
			}
			$('.sweet-overlay').css('display','none'); //隐藏输入框
			$('.sweet-alert').css('display','none');

			urgeFns(inputValue,tid,status); //执行督办事件
		});
	})

	/*
	 *延期事件
	 *参数：新日期,任务id,任务状态
	 */
	function delayFns(newTime,tid,status){
		var url = 'm.php?app=user&func=task&action=default&task=operateTask';
		$.post(url, {'tid':tid,'job':'delay','status':status,'newtime':newTime}, function(json){
		    if(json.success){
			  //   	iosOverlay({
					// 	text: json.msg,
					// 	duration: 2e3,
					// 	icon: "../../../../../../resources/images/m/artDialog/check.png"
					// })
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
				     OpacityOverlay : 0.3            // 设置遮罩层的透明度 
				});
		    }else{
		   //  	iosOverlay({
					// 	text: json.msg,
					// 	duration: 2e3,
					// 	icon: "../../../../../../resources/images/m/artDialog/cross.png"
					// })
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
		    setTimeout(function(){ //2秒后跳转到任务列表
		    	$(".ui-ios-overlay").removeClass("ios-overlay-show").addClass("ios-overlay-hide");
	   			window.location.href='m.php?app=user&func=task&action=default&task=loadPage&from=list';
		    },2000)
		},'json')
	}

	//触发延期事件
	$(document).on('click','#btnDelay',function(){
		$('#jingle_popup').hide();
	    $('#jingle_popup_mask').hide();
		var tid = myFns.getUrlValue('tid'); //任务id
		var etime = $('#basicInfo .etime').html(); //任务结束日期
		if(etime == undefined) etime = etimes;
		setTimeout(function(){
			//初始化时间控件
			$('#newTime').date();
		},200)
		swal({   
			title: "延期任务",   
			text: "<div class=\"form-inline\"><div class=\"form-group etime\"><label for=\"etimeTitle\">原结束时间：</label><label for=\"etime\">"+etime+"</label></div></div><div class=\"form-group datepick-control\"><span class=\"date-span\">修改为：</span><input type=\"text\" class=\"datetimepick-input kbtn\" id=\"newTime\"></div>",
			confirmButtonText: "确认延期",   
			showCancelButton: true,   
			closeOnConfirm: false,   
			html: true,
			animation: "slide-from-top"   
		}, function(){ //确认按钮会进这里
			var newTime = $('#newTime').val(); //修改的时间
			if(newTime == ''){
				// iosOverlay({
				// 	text: '请输入修改时间',
				// 	duration: 2e3,
				// 	icon: "../../../../../../resources/images/m/artDialog/cross.png"
				// })
				jNotify('请输入修改时间',{
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
				$('#newTime').focus();
				return false;
			}
			delayFns(newTime,tid,status); //调用延期任务事件
		});
	})

	/**
	 *任务失败事件
	 *参数：失败原因,任务id,任务状态
	 */
	function failFns(reason,tid,status){
		var content = reason; //失败原因
		var tid = tid; //任务id
		var url = 'm.php?app=user&func=task&action=default&task=operateTask';
		$.post(url,{'tid':tid,'job':'fail','status':status,'content':content},function(json){
			if(json.success){
			  //   iosOverlay({
					// 	text: json.msg,
					// 	duration: 2e3,
					// 	icon: "../../../../../../resources/images/m/artDialog/check.png"
					// })
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
				     OpacityOverlay : 0.3            // 设置遮罩层的透明度 
				});
			}else{
		  //   	iosOverlay({
				// 	text: json.msg,
				// 	duration: 2e3,
				// 	icon: "../../../../../../resources/images/m/artDialog/cross.png"
				// })
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
		    setTimeout(function(){ //2秒后跳转到任务列表
		    	$(".ui-ios-overlay").removeClass("ios-overlay-show").addClass("ios-overlay-hide");
	   			window.location.href='m.php?app=user&func=task&action=default&task=loadPage&from=list';
		    },2000)
		},'json')
	}

	//触发失败任务事件
	$(document).on('click','#btnFail',function(){
		$('#jingle_popup').hide();
	    $('#jingle_popup_mask').hide();
		var tid = myFns.getUrlValue('tid'); //任务id
		swal({   
			title: "失败原因",   
			confirmButtonText: "确认操作",   
			type: "input",   
			showCancelButton: true,   
			closeOnConfirm: false,   
			animation: "slide-from-top",   
			inputPlaceholder: "失败原因" 
		}, function(inputValue){ 
			if (inputValue === false){
				return false;      
			}
			if (inputValue === "") {     
				swal.showInputError("请输入失败原因!");     
				return false;   
			}
			$('.sweet-overlay').css('display','none'); //隐藏输入框
			$('.sweet-alert').css('display','none');
			failFns(inputValue,tid,status); //执行任务失败操作
		});
	})

	//删除任务事件
	function deleteFns(tid,status){
		var url = 'm.php?app=user&func=task&action=default&task=operateTask&tid='+tid;
		$.post(url, {'tid':tid,'job':'delete','status':status}, function(json){
		    if(json.success){
		  //   	iosOverlay({
				// 	text: json.msg,
				// 	duration: 2e3,
				// 	icon: "../../../../../../resources/images/m/artDialog/check.png"
				// })
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
				     OpacityOverlay : 0.3            // 设置遮罩层的透明度 
				});
		    }else{
		  //   	iosOverlay({
				// 	text: json.msg,
				// 	duration: 2e3,
				// 	icon: "../../../../../../resources/images/m/artDialog/cross.png"
				// })
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
		    setTimeout(function(){ //2秒后调用刷新界面方法
		    	$(".ui-ios-overlay").removeClass("ios-overlay-show").addClass("ios-overlay-hide");
	   			window.location.href='m.php?app=user&func=task&action=default&task=loadPage&from=list';
		    },2000)
		},'json')
	}

	//触发删除事件
	$(document).on('click','#btnDel',function(){
		$('#jingle_popup').hide();
	    $('#jingle_popup_mask').hide();
		var tid = myFns.getUrlValue('tid'); //任务id
		swal({   
			title: "温馨提示",   
			text: "<p class='info'>删除不可恢复，确定要删除吗？</p>",
			confirmButtonText: "确认操作",   
			showCancelButton: true,   
			closeOnConfirm: false,   
			html: true,
			animation: "slide-from-top"  
		}, function(){ //确认按钮会进这里
			$('.sweet-overlay').css('display','none'); //隐藏输入框
			$('.sweet-alert').css('display','none');
			deleteFns(tid,status);
		});
	})

	//触发修改任务`跳转
	$(document).on('click','#btnMod',function(){
		$('#jingle_popup').hide();
	    $('#jingle_popup_mask').hide();
		var tid = myFns.getUrlValue('tid'); //任务id
		window.location.href='m.php?app=user&func=task&action=default&task=loadPage&from=addedit&job=edit&tid='+tid; //请求修改页面地址
	})

	/**
	 *汇报进度事件
  	 *参数：任务进度,进度说明,任务id,任务状态
  	 */
	function reportFns(progress,content,tid,status){
		var url = 'm.php?app=user&func=task&action=default&tid='+tid+'&status='+status+'&task=operateTask';
		$.post(url,{'tid':tid,'progress':progress,'job':'report','status':status,'content':content},function(json){
			if(json.success){
			  //   iosOverlay({
					// 	text: json.msg,
					// 	duration: 2e3,
					// 	icon: "../../../../../../resources/images/m/artDialog/check.png"
					// })
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
				     OpacityOverlay : 0.3            // 设置遮罩层的透明度 
				});
			}else{
		  //   	iosOverlay({
				// 	text: json.msg,
				// 	duration: 2e3,
				// 	icon: "../../../../../../resources/images/m/artDialog/cross.png"
				// })
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
		    setTimeout(function(){ //2秒后刷新
		    	window.location.href='m.php?app=user&func=task&action=default&task=loadPage&from=list';
		    },2000)
		},'json')
	}

	//触发汇报进度事件
	$(document).on('click','#btnReport',function(){
		$('#jingle_popup').hide();
	    $('#jingle_popup_mask').hide();
		var tid = myFns.getUrlValue('tid'); //任务id
		setTimeout(function(){
			$('#progress').focus();
		},300)

		swal({   
			title: "汇报任务进度",   
			text: "<input type=\"text\" class=\"form-control input-m etime\" id=\"progress\" placeholder=\"任务进度(请输入整数1~100)\"><textarea class=\"form-control contents\" rows=\"2\" id=\"content\" placeholder=\"进度说明\"></textarea>",
			confirmButtonText: "确认汇报",   
			showCancelButton: true,   
			closeOnConfirm: false,   
			html: true,
			animation: "slide-from-top"   
		}, function(){ //确认按钮会进这里
			var progress = $('#progress').val(); //任务进度
			var content = $('#content').val(); //进度说明
			if(progress == ''){
				$('#progress').focus();
				return false;
			}else if(content == ''){
				$('#content').focus();
				return false;
			}
			reportFns(progress,content,tid,status); //调用汇报进度事件
		});
	})


	/**
	 *完成任务事件
  	 *参数：任务总结,任务id,任务状态
  	 */
	function completeFns(content,tid,status){
		var url = 'm.php?app=user&func=task&action=default&tid='+tid+'&status='+status+'&task=operateTask';
		$.post(url,{'tid':tid,'job':'complete','status':status,'content':content},function(json){
			if(json.success){
			  //   iosOverlay({
					// 	text: json.msg,
					// 	duration: 2e3,
					// 	icon: "../../../../../../resources/images/m/artDialog/check.png"
					// })
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
				     OpacityOverlay : 0.3            // 设置遮罩层的透明度 
				});
			}else{
		  //   	iosOverlay({
				// 	text: json.msg,
				// 	duration: 2e3,
				// 	icon: "../../../../../../resources/images/m/artDialog/cross.png"
				// })
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
		    setTimeout(function(){ //2秒后刷新
		    	window.location.href='m.php?app=user&func=task&action=default&task=loadPage&from=list';
		    },2000)
		},'json')
	}

	//触发完成任务
	$(document).on('click','#btnComplete',function(){
		$('#jingle_popup').hide();
	    $('#jingle_popup_mask').hide();
		var tid = myFns.getUrlValue('tid'); //任务id
		setTimeout(function(){
			$('#content').focus();
		},300)

		swal({   
			title: "确认完成任务",   
			text: "<textarea class=\"form-control content\" rows=\"2\" id=\"content\" placeholder=\"任务总结\"></textarea>",
			confirmButtonText: "确认完成",   
			showCancelButton: true,   
			closeOnConfirm: false,   
			html: true,
			animation: "slide-from-top"   
		}, function(){ //确认按钮会进这里
			var content = $('#content').val(); //完成任务说明
			if(content == ''){
				$('#content').focus();
				return false;
			}
			completeFns(content,tid,status); //调用完成任务事件
		});
	})


	/**
	 * 同意审核任务事件
  	 *参数：任务总结,任务id,任务状态
  	 */
	function finishFns(tid,status,content,point){
  		var url = 'm.php?app=user&func=task&action=default&tid='+tid+'&status='+status+'&task=operateTask';
		$.post(url,{'tid':tid,'job':'finish','status':status,'content':content,'point':point},function(json){
			if(json.success){
			 //    iosOverlay({
				// 	text: json.msg,
				// 	duration: 2e3,
				// 	icon: "../../../../../../resources/images/m/artDialog/check.png"
				// })
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
				     OpacityOverlay : 0.3            // 设置遮罩层的透明度 
				});
			}else{
		  //   	iosOverlay({
				// 	text: json.msg,
				// 	duration: 2e3,
				// 	icon: "../../../../../../resources/images/m/artDialog/cross.png"
				// })
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
		    setTimeout(function(){ //2秒后刷新
		    	window.location.href='m.php?app=user&func=task&action=default&task=loadPage&from=list';
		    },2000)
		},'json')
  	}

	//触发完成任务
	$(document).on('click','#btnFinish',function(){
		$('#jingle_popup').hide();
	    $('#jingle_popup_mask').hide();
		var tid = myFns.getUrlValue('tid'); //任务id
		var finish = 'finish'; //类型

		swal({   
			title: "同意审核任务",   
			text: "<p><textarea class=\"form-control content\" rows=\"2\" id=\"content\" placeholder=\"任务意见\"></textarea></p><p><textarea class=\"form-control content\" rows=\"1\" id=\"point\" placeholder=\"任务评分(请输入整数1-100)\"></textarea></p>",
			confirmButtonText: "确认操作",   
			showCancelButton: true,   
			closeOnConfirm: false,   
			html: true,
			animation: "slide-from-top"  
		}, function(){ //确认按钮会进这里
			
			var content = $('#content').val(); //完成任务说明
			if(content == ''){
				$('#content').focus();
				return false;
			}
			var point = $('#point').val(); //完成任务说明
			var re = /^[1-9]\d{0,2}$/;
			var result = re.test(point);
			var inSize = true;
			if(result){
				if(parseInt(point)>100){
					var inSize = false; 
				}
			}
			if(point == '' || !result || !inSize){
				$('#point').focus();
				return false;
			}
			$('.sweet-overlay').css('display','none'); //隐藏输入框
			$('.sweet-alert').css('display','none');
			finishFns(tid,status,content,point); //调用完成任务事件
		});
	})
	/**
	 * 不同意审核方法
  	 *参数：任务id,任务状态,任务类型
  	 */
  	function noFinishFns(content,tid,status){
  		var url = 'm.php?app=user&func=task&action=default&tid='+tid+'&status='+status+'&task=operateTask';
		$.post(url,{'tid':tid,'job':'nofinish','status':status,'content':content},function(json){
			if(json.success){
			 //    iosOverlay({
				// 	text: json.msg,
				// 	duration: 2e3,
				// 	icon: "../../../../../../resources/images/m/artDialog/check.png"
				// })
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
				     OpacityOverlay : 0.3            // 设置遮罩层的透明度 
				});
			}else{
		  //   	iosOverlay({
				// 	text: json.msg,
				// 	duration: 2e3,
				// 	icon: "../../../../../../resources/images/m/artDialog/cross.png"
				// })
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
		    setTimeout(function(){ //2秒后刷新
		    	window.location.href='m.php?app=user&func=task&action=default&task=loadPage&from=list';
		    },2000)
		},'json')
  	}

	//触发不同意审核任务
	$(document).on('click','#btnNofinish',function(){
		$('#jingle_popup').hide();
	    $('#jingle_popup_mask').hide();
		var tid = myFns.getUrlValue('tid'); //任务id
		setTimeout(function(){
			$('#content').focus();
		},300)

		swal({   
			title: "不同意完成任务",   
			text: "<textarea class=\"form-control content\" rows=\"2\" id=\"content\" placeholder=\"不同意理由\"></textarea>",
			confirmButtonText: "确认完成",   
			showCancelButton: true,   
			closeOnConfirm: false,   
			html: true,
			animation: "slide-from-top"   
		}, function(){ //确认按钮会进这里
			var content = $('#content').val(); //完成任务说明
			if(content == ''){
				$('#content').focus();
				return false;
			}
			noFinishFns(content,tid,status); //调用完成任务事件
		});
	})

	/**
	 *修改负责人事件
  	 *参数：用户id,任务id
  	 */
	function editPrincipalFns(uid,tid){
		var url = 'm.php?app=user&func=task&action=default&tid='+tid+'&status='+status+'&task=editPrincipal';
		$.post(url,{'uid':uid,'tid':tid},function(json){
			if(json.success){
			  //   iosOverlay({
					// 	text: json.msg,
					// 	duration: 2e3,
					// 	icon: "../../../../../../resources/images/m/artDialog/check.png"
					// })
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
				     OpacityOverlay : 0.3            // 设置遮罩层的透明度 
				});
			}else{
		  //   	iosOverlay({
				// 	text: json.msg,
				// 	duration: 2e3,
				// 	icon: "../../../../../../resources/images/m/artDialog/cross.png"
				// })
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
		    setTimeout(function(){ //2秒后刷新
		    	window.location.href='m.php?app=user&func=task&action=default&task=loadPage&from=list';
		    },2000)
		},'json')
	}

	//触发修改负责人事件
	$(document).on('click','#btnEditPrincipal',function(){
		$('#jingle_popup').hide();
	  $('#jingle_popup_mask').hide();
		var tid = myFns.getUrlValue('tid'); //任务id
		var text = '<botton type="botton" class="btn btn-default modSendee" id="modSendee" data-checktype="false" data-toggle="modal" data-source="modSendee" readonly><span class="name-box">请选择负责人</span></botton>';
		swal({   
			title: "修改负责人",   
			confirmButtonText: "确认操作",   
			html: true,
			//text: "<div class=\"modSendee\" id=\"modSendee\" data-checktype=\"false\" data-toggle=\"modal\" data-target=\"#getUser\" readonly><span class=\"title\">选择负责人：</span><span class=\"name-box\"></span></div>",
			text: text,
			showCancelButton: true,   
			closeOnConfirm: false,   
			animation: "slide-from-top"
		}, function(){ 
			var uid = $('#modSendee .name').attr('data-uid');
			if(uid == '' || uid == undefined){
				// iosOverlay({
				// 	text: '请选择负责人',
				// 	duration: 2e3,
				// 	icon: "../../../../../../resources/images/m/artDialog/cross.png"
				// })
				jNotify('请选择负责人',{
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
				return false;
			}
			$('.sweet-overlay').css('display','none'); //隐藏输入框
			$('.sweet-alert').css('display','none');
			editPrincipalFns(uid,tid); //执行任务失败操作
		});
	})

	// //触发人员选择器
	// $(document).on('click','#modSendee',function(){
	// 	$('#getUser').attr('data-source','modSendee');
	//     //请求人员数据
	//     $('#search').val("");
	//     $('#userDataModal').show();
	// 	getSelectorData();
	// })

	$(document).on('click','#modSendee',function(){
		var source = $(this).attr('data-source') == undefined ? '': $(this).attr('data-source');
		// $('#userDataModal').attr('data-belong','freeFlowStepDeal');
		$('#userDataModal').attr('data-source', source);
		var checktype = 'false';
		$('#userDataModal').attr('data-checktype', checktype);
		$('#search').val("");
		$('#modSendee .name-box .name').each(function(){
			var uid  = $(this).attr('data-uid');
			var name = $(this).text();
			var face = $(this).attr('data-face');
			if(face == undefined){ //默认头像
				face = 'file/common/face/default-face.jpg';
			}
			if(uid != undefined){
				var userObj = {'uid':uid, "name":name, "face":face};
				sendeeArr.push(userObj);
			};
		});
		$('#userDataModal').show();
    	//请求人员数据
		getSelectorData();
	});

	/**
	 *同意任务执行方法
  	 *参数：确认工时,意见建议,用户id,任务id
  	 */
	function agreeFns(worktime2,content,tid,status){
		var url = 'm.php?app=user&func=task&action=default&task=operateTask';
		$.post(url,{'tid':tid,'job':'agree','status':status,'content':content,'worktime2':worktime2},function(json){
			if(json.success){
			 //    iosOverlay({
				// 	text: json.msg,
				// 	duration: 2e3,
				// 	icon: "../../../../../../resources/images/m/artDialog/check.png"
				// })
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
				     OpacityOverlay : 0.3            // 设置遮罩层的透明度 
				});
			}else{
		  //   	iosOverlay({
				// 	text: json.msg,
				// 	duration: 2e3,
				// 	icon: "../../../../../../resources/images/m/artDialog/cross.png"
				// })
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
		    setTimeout(function(){ //2秒后刷新
		    	window.location.href='m.php?app=user&func=task&action=default&task=loadPage&from=list';
		    },2000)
		},'json')
	}

	//触发同意任务执行事件
	$(document).on('click','#btnAgree',function(){
		$('#jingle_popup').hide();
	    $('#jingle_popup_mask').hide();
	    var tid = myFns.getUrlValue('tid'); //任务id
		setTimeout(function(){
			$('#worktime2').focus();
		},300)

		swal({   
			title: "同意任务执行",   
			text: "<input type=\"text\" class=\"form-control input-m etime\" id=\"worktime2\" placeholder=\"确认工时\"><textarea class=\"form-control contents\" rows=\"2\" id=\"content\" placeholder=\"意见建议\"></textarea>",
			confirmButtonText: "确认操作",   
			showCancelButton: true,   
			closeOnConfirm: false,   
			html: true,
			animation: "slide-from-top"  
		}, function(){ //确认按钮会进这里
			var worktime2 = $('#worktime2').val(); //确认工时
			var content = $('#content').val(); //意见建议
			if(worktime2 == ''){
				$('#worktime2').focus();
				return false;
			}else if(content == ''){
				$('#content').focus();
				return false;
			}
			agreeFns(worktime2,content,tid,status); //调用确认同意方法
		});
	})

	/**
	 *审批不同意任务方法
  	 *参数：不同意理由,用户id,任务id
  	 */
  	function disagreeFns(content,tid,status){
  		var url = 'm.php?app=user&func=task&action=default&task=operateTask';
		$.post(url,{'tid':tid,'job':'disagree','status':status,'content':content},function(json){
			if(json.success){
			 //    iosOverlay({
				// 	text: json.msg,
				// 	duration: 2e3,
				// 	icon: "../../../../../../resources/images/m/artDialog/check.png"
				// })
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
				     OpacityOverlay : 0.3            // 设置遮罩层的透明度 
				});
			}else{
		  //   	iosOverlay({
				// 	text: json.msg,
				// 	duration: 2e3,
				// 	icon: "../../../../../../resources/images/m/artDialog/cross.png"
				// })
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
		    setTimeout(function(){ //2秒后刷新
		    	window.location.href='m.php?app=user&func=task&action=default&task=loadPage&from=list';
		    },2000)
		},'json')
  	}

	//触发审批任务·不同意事件
	$(document).on('click','#btnDisagree',function(){
		$('#jingle_popup').hide();
	    $('#jingle_popup_mask').hide();
	    var tid = myFns.getUrlValue('tid'); //任务id
		setTimeout(function(){
			$('#content').focus();
		},300);

		swal({   
			title: "审批任务不同意",   
			text: "<textarea class=\"form-control content\" rows=\"2\" id=\"content\" placeholder=\"不同意理由\"></textarea>",
			confirmButtonText: "确认操作",   
			showCancelButton: true,   
			closeOnConfirm: false,   
			html: true,
			animation: "slide-from-top"   
		}, function(){ //确认按钮会进这里
			var content = $('#content').val(); //不同意理由
			if(content == ''){
				// iosOverlay({
				// 	text: '请输入理由',
				// 	duration: 2e3,
				// 	icon: "../../../../../../resources/images/m/artDialog/cross.png"
				// })
				jNotify('请输入理由',{
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
				$('#content').focus();
				return false;
			}
			disagreeFns(content,tid,status); //调用确认同意方法
		});
	})

	/**
	 *接收任务方法
  	 *参数：任务id,任务状态,任务类型
  	 */
  	function acceptFns(tid,status,accept){
  		var url = 'm.php?app=user&func=task&action=default&task=operateTask';
		$.post(url,{'tid':tid,'job':'accept','status':status},function(json){
			if(json.success){
			 //    iosOverlay({
				// 	text: json.msg,
				// 	duration: 2e3,
				// 	icon: "../../../../../../resources/images/m/artDialog/check.png"
				// })
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
				     OpacityOverlay : 0.3            // 设置遮罩层的透明度 
				});
			}else{
		  //   	iosOverlay({
				// 	text: json.msg,
				// 	duration: 2e3,
				// 	icon: "../../../../../../resources/images/m/artDialog/cross.png"
				// })
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
		    setTimeout(function(){ //2秒后刷新
		    	window.location.href='m.php?app=user&func=task&action=default&task=loadPage&from=list';
		    },2000)
		},'json')
  	}

	//触发接收任务
	$(document).on('click','#btnReceive',function(){
		$('#jingle_popup').hide();
	    $('#jingle_popup_mask').hide();
	    var tid = myFns.getUrlValue('tid'); //任务id
		var accept = 'accept'; //类型
		swal({   
			title: "温馨提示",   
			text: "<p class='info'>确定接收任务吗？</p>",
			confirmButtonText: "确认操作",   
			showCancelButton: true,   
			closeOnConfirm: false,   
			html: true,
			animation: "slide-from-top"  
		}, function(){ //确认按钮会进这里
			$('.sweet-overlay').css('display','none'); //隐藏输入框
			$('.sweet-alert').css('display','none');
			acceptFns(tid,status,accept);
		});
	})

	/**
	 *拒绝接收任务方法
  	 *参数：任务id,任务状态,任务类型
  	 */
  	function rejectFns(tid,status,content,reject){
  		var url = 'm.php?app=user&func=task&action=default&task=operateTask';
		$.post(url,{'tid':tid,'job':'reject','status':status,'content':content},function(json){
			if(json.success){
			 //    iosOverlay({
				// 	text: json.msg,
				// 	duration: 2e3,
				// 	icon: "../../../../../../resources/images/m/artDialog/check.png"
				// })
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
				     OpacityOverlay : 0.3            // 设置遮罩层的透明度 
				});
			}else{
		  //   	iosOverlay({
				// 	text: json.msg,
				// 	duration: 2e3,
				// 	icon: "../../../../../../resources/images/m/artDialog/cross.png"
				// })
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
		    setTimeout(function(){ //2秒后刷新
		    	window.location.href='m.php?app=user&func=task&action=default&task=loadPage&from=list';
		    },2000)
		},'json')
  	}

	//触发拒绝接收任务
	$(document).on('click','#btnRefuse',function(){
		$('#jingle_popup').hide();
	    $('#jingle_popup_mask').hide();
	    var tid = myFns.getUrlValue('tid'); //任务id
		var reject = 'reject'; //类型
		setTimeout(function(){
			$('#content').focus();
		},300);

		swal({   
			title: "拒绝接收任务",   
			text: "<textarea class=\"form-control content\" rows=\"2\" id=\"content\" placeholder=\"拒绝理由\"></textarea>",
			confirmButtonText: "确认操作",   
			showCancelButton: true,   
			closeOnConfirm: false,   
			html: true,
			animation: "slide-from-top"   
		}, function(){ //确认按钮会进这里
			var content = $('#content').val(); //不同意理由
			if(content == ''){
				// iosOverlay({
				// 	text: '请输入拒绝理由',
				// 	duration: 2e3,
				// 	icon: "../../../../../../resources/images/m/artDialog/cross.png"
				// })
				jNotify('请输入拒绝理由',{
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
				$('#content').focus();
				return false;
			}
			rejectFns(tid,status,content,reject); //调用拒绝接收方法
		});
	})

	//请求人员选择器数据
	function getSelectorData(){
		$.ajax({
			dataType: "json",
			type: "get",
			url: "m.php?action=commonJob&task=getSelectorData&target=getUserDatamm",
			beforeSend: function(){
				//提交表单前验证
				// myFns.showLoading('正在加载，请稍等...');
				showLoading();
			},
			success: function(json){
				hideLoading();
				if(json.success){ //请求成功
					if(json.paterId == 0){ //上级部门id
						$('#sback').css('display','none');
					}else{
						$('#sback').css('display','block');
					}

					var data  	= json.data;
					var users 	= data.users; //人员数据
					var structs = data.structs; //部门数据

					if(users.length == 0 && structs.length == 0){
						jNotify('没有检索到数据!',{
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
						return false;
					} 

					$('#sback').attr('data-fid',json.paterId); //有数据才改变上级部门id
					$('#userDataGroup').empty();//清空数据区
					if(users.length > 0){ //处理人员数据
						$.each(users, function(i,arr){
							var userStr  = '<li class="list-group-item userList" id="uid-'+arr['uid']+'" data-uid="'+arr['uid']+'" data-name="'+arr['truename']+'" data-face="'+arr['face']+'">';
								userStr += '<span class="tools pull-left">';
								userStr += '<input type="checkbox" class="ipt-hide">';
								userStr += '<label class="checkbox"></label>';
								userStr += '</span>';
								userStr += '<div class="name-box">';
								userStr += '<img src="'+arr['face']+'" class="img-circle face">';
								userStr += '<span class="truename">'+arr['truename']+'</span>';
								userStr += '</div>';
								userStr += '<span class="job">'+arr['jobName']+'</span>';
								userStr += '</li>';
							$('#userDataGroup').append(userStr);
						}) 
						//自动勾选
						setChecked('userList', sendeeArr, 'user');            
					}
					if(structs.length > 0){ //处理部门数据
						$.each(structs, function(index,array){
							var structStr  = '<li class="list-group-item structList" data-sid="'+array['structId']+'">';
								structStr += '<span class="fs1 struct-ico-left" aria-hidden="true" data-icon="&#xe109;"></span>';
								structStr += '<div class="name-box">';
								structStr += '<span class="struct">'+array['structName']+'</span></div>';
								structStr += '<div class="fs1 struct-ico-right" aria-hidden="true" data-icon="5"></div>';
								structStr += '<div class="userTotal">'+array['userTotal']+'</div>';
								structStr += '</li>';
							$('#userDataGroup').append(structStr); 
							//重新调整页面
							userDataScroll.refresh();  	
						})
					}
				}
				//已选人员区域操作
				setUserOpts();

				//重新调整页面
				userDataScroll.refresh(); 
			}
		})
	}
	/*                             
	 *  方法:viewUserData(structId)      
	 *  功能:加载用户和部门数据.         
	 *  参数:部门ID.     
	 */
	function viewUserData(structId){
		$.ajax({
			dataType: "json",
			type: "post",
			url: "m.php?action=commonJob&task=getSelectorData&target=getUserDatamm",
			data: {'structId':structId},
			beforeSend: function(){
				//提交表单前验证
				// myFns.showLoading('正在加载，请稍等...');
				showLoading();
			},
			success: function(json){
				hideLoading();
				if(json.success){ //请求成功
					if(json.paterId == 0){ //上级部门id
						$('#sback').css('display','none');
					}else{
						$('#sback').css('display','block');
					}
					if(json.msg != 0){
						$('#userDataModal').attr('data-sid', json.msg);
					}else{
						if(json.paterId == 1){
							$('#sback').css('display','none');
						};
					}
					var data  	= json.data;
					var users 	= data.users; //人员数据
					var structs = data.structs; //部门数据
					if(users.length == 0 && structs.length == 0){
						jNotify('没有检索到数据!',{
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
						return false;
					} 
					$('#sback').attr('data-fid',json.paterId); //有数据才改变上级部门id
					$('#userDataGroup').empty();//清空数据区
					if(users.length > 0){ //处理人员数据
						$.each(users, function(i,arr){
							var userStr  = '<li class="list-group-item userList" id="uid-'+arr['uid']+'" data-uid="'+arr['uid']+'" data-name="'+arr['truename']+'" data-face="'+arr['face']+'">';
								userStr += '<span class="tools pull-left">';
								userStr += '<input type="checkbox" class="ipt-hide">';
								userStr += '<label class="checkbox"></label>';
								userStr += '</span>';
								userStr += '<div class="name-box">';
								userStr += '<img src="'+arr['face']+'" class="img-circle face">';
								userStr += '<span class="truename">'+arr['truename']+'</span>';
								userStr += '</div>';
								userStr += '<span class="job">'+arr['jobName']+'</span>';
								userStr += '</li>';
							$('#userDataGroup').append(userStr);
						}) 
						//自动勾选
						setChecked('userList', sendeeArr, 'user');            
					}
					if(structs.length > 0){ //处理部门数据
						$.each(structs, function(index,array){
							var structStr  = '<li class="list-group-item structList" data-sid="'+array['structId']+'">';
							structStr += '<span class="fs1 struct-ico-left" aria-hidden="true" data-icon="&#xe109;"></span>';
							structStr += '<div class="name-box">';
							structStr += '<span class="struct">'+array['structName']+'</span></div>';
							structStr += '<div class="fs1 struct-ico-right" aria-hidden="true" data-icon="5"></div>';
							structStr += '<div class="userTotal">'+array['userTotal']+'</div>';
			              	structStr += '</li>';
							$('#userDataGroup').append(structStr);  	
							})
						}	
					}
				//已选人员区域操作
				setUserOpts();

				//重新调整页面
				userDataScroll.refresh(); 
			}
		})
	}

	//搜索功能
	function getSelectorDataVal(){
		var search = $('#search').val();
		if(search != ''){ 
			var httpUrl = 'm.php?action=commonJob&task=getSelectorData&target=getUserDatamm&search='+search;
		}else{
			var structId = $('#userDataModal').attr('data-sid');
			var httpUrl = 'm.php?action=commonJob&task=getSelectorData&target=getUserDatamm&structId='+structId;
		}
		$.ajax({
			dataType: "json",
			type: "post",
			url: httpUrl,
			beforeSend: function(){
				//提交表单前验证
				// myFns.showLoading('正在加载，请稍等...');
				showLoading();
			},
			success: function(json){
				hideLoading();
				$('#userDataGroup').empty();//清空数据区
				if(json.success){
					var data  = json.data;
					var users = data.users; //人员数据
					if(data != ''){
						if(users.length > 0){
							$.each(users,function(i, arr){
								var userStr  = '<li class="list-group-item userList" id="uid-'+arr['uid']+'" data-uid="'+arr['uid']+'" data-name="'+arr['truename']+'" data-face="'+arr['face']+'">';
									userStr += '<span class="tools pull-left">';
									userStr += '<input type="checkbox" class="ipt-hide">';
									userStr += '<label class="checkbox"></label>';
									userStr += '</span>';
									userStr += '<div class="name-box">';
									userStr += '<img src="'+arr['face']+'" class="img-circle face">';
									userStr += '<span class="truename">'+arr['truename']+'</span>';
									userStr += '</div>';
									userStr += '<span class="job">'+arr['jobName']+'</span>';
									userStr += '</li>';
								$('#userDataGroup').append(userStr);
							})
							//自动扣选
							setChecked('userList', sendeeArr, 'user');
						}
					}

					if(data.hasOwnProperty('structs')){
						var structs = data.structs; //部门数据
						if(structs.length > 0){ //处理部门数据
							$.each(structs, function(index,array){
								var structStr  = '<li class="list-group-item structList" data-sid="'+array['structId']+'">';
									structStr += '<span class="fs1 struct-ico-left" aria-hidden="true" data-icon="&#xe109;"></span>';
									structStr += '<div class="name-box">';
									structStr += '<span class="struct">'+array['structName']+'</span></div>';
									structStr += '<div class="fs1 struct-ico-right" aria-hidden="true" data-icon="5"></div>';
									structStr += '<div class="userTotal">'+array['userTotal']+'</div>';
									structStr += '</li>';
									$('#userDataGroup').append(structStr);  	
							})
						}
					};
				}

				//已选人员区域操作
				setUserOpts();

				//重新调整页面
				userDataScroll.refresh(); 
			}
		})
	}
	//进入下一级部门
	$(document).on('click','.structList',function(){
		var structId = $(this).attr('data-sid');
		viewUserData(structId);
	});
	
	//返回上一级部门
	$(document).on('touchstart','#sback',function(){
		$('#search').val(''); //清空搜索框
		var structId = $(this).attr('data-fid'); //部门id
		if(structId == 0){
			return false;
		}
		viewUserData(structId);
	});
	//关闭人员选择器
	$(document).on('touchstart','#sClose',function(){
		sendeeArr = []; //切记`关闭选择器时需重置全局数组
		$('#userDataModal').hide();
	});

	//完成人员选择
	$(document).on('click','#sFinish',function(){
		recordData(); //其他通用方式
		sendeeArr = []; //切记`关闭选择器时需重置全局数组
		$('#userDataModal').hide();
	});
	//完成人员选择`把数据记录到指定位置
	function recordData(){ 
		$('#modSendee .name-box').empty(); //先清空数据区再遍历数据
		var uids  = ''; //用户id
		var names = ''; //用户名
		var faces = ''; //用户头像
		//把存放在全局数组中的人员数据遍历到指定位置
		var userStr = '';
		for (var i = 0; i < sendeeArr.length; i++) {
			uids  = sendeeArr[i].uid;
			names = sendeeArr[i].name;
			faces = sendeeArr[i].face;
			if(i!=sendeeArr.length-1){
				userStr += '<span class="name" data-uid="'+uids+'" data-face="'+faces+'">'+names+'</span>,';
			}else{
				userStr += '<span class="name" data-uid="'+uids+'" data-face="'+faces+'">'+names+'</span>';
			}
		}
		$('#modSendee .name-box').append(userStr);
		
		
	}

	//按钮被松开时触发搜索人员事件
	$(document).on('keyup','#search',getSelectorDataVal);

	//checkbox扣选
	$(document).on('click','#userGroup .user-list',function(){
		//触发事件只能单选
		var source = $("#getUser").attr("data-source"); //请求来源
		var checktype = $("#"+source).attr('data-checktype'); //checkbox类型：false单选 、true多选
		if(checktype == "false"){ //checktype：false`单选  true`多选
			checkOnly(this);
		}

        if($(this).find('.ipt-hide').attr('checked')){
            $(this).find('.checkbox').removeClass('cur');
            $(this).find('.ipt-hide').removeAttr('checked')
        }else{
            $(this).find('.checkbox').addClass('cur');
            $(this).find('.ipt-hide').attr('checked','checked')
        }
    })

	
	/**
	 *	方法: setChecked(element)
	 *	功能: 自动扣选
	 *	参数1: <string> element 需要遍历的元素 
	 *	参数2: <array> 存放已扣选的数据`数组 
	 *	参数3: <string> choiceType 选择器类型
	 */
	function setChecked(element,dataArr,choiceType){
		if(choiceType == 'user'){ //人员选择器
			$('.'+element+' :checkbox').each(function(){
				var uid = $(this).closest('.'+element).attr("data-uid"); //选择框当前行的员工id
				var _this = $(this);
				if(dataArr.length > 0){
					for (var i = 0; i < dataArr.length; i++) {
						if (dataArr[i].uid == uid) {
							$(_this).prop("checked", true);
							$(_this).siblings('.checkbox').addClass('cur');
							break;
						}
					}
				}
			})
		}else if(choiceType == 'job'){ //职位选择器
			$('.'+element+' :checkbox').each(function(){
				var jid = $(this).closest('.'+element).attr("data-jid"); //选择框当前行的员工id
				var _this = $(this);
				if(dataArr.length > 0){
					for (var i = 0; i < dataArr.length; i++) {
						if (dataArr[i] == jid) {
							$(_this).prop("checked", true);
							$(_this).siblings('.checkbox').addClass('cur');
							break;
						}
					}
				}
			})
		}else if(choiceType == 'station'){ //岗位选择器
			$('.'+element+' :checkbox').each(function(){
				var sid = $(this).closest('.'+element).attr("data-sid"); //选择框当前行的员工id
				var _this = $(this);
				if(dataArr.length > 0){
					for (var i = 0; i < dataArr.length; i++) {
						if (dataArr[i] == sid) {
							$(_this).prop("checked", true);
							$(_this).siblings('.checkbox').addClass('cur');
							break;
						}
					}
				}
			})
		}else if(choiceType == 'dept'){ //部门选择器
			$('.'+element+' :checkbox').each(function(){
				var did = $(this).closest('.'+element).attr("data-did"); //选择框当前行的员工id
				var _this = $(this);
				if(dataArr.length > 0){
					for (var i = 0; i < dataArr.length; i++) {
						if (dataArr[i] == did) {
							$(_this).prop("checked", true);
							$(_this).siblings('.checkbox').addClass('cur');
							break;
						}
					}
				}
			})
		}
	}

	//删除人员操作区域
	function setUserOpts(){
		$('#userGroup').empty();
		var sendeeArrLength = sendeeArr.length;
		if (sendeeArrLength > 0) {
			for (var i = 0; i < sendeeArrLength; i++) {
				var faceStr = '<li class="delFace" data-uid="'+sendeeArr[i].uid+'" data-name="'+sendeeArr[i].name+'"><img src="'+sendeeArr[i].face+'" class="img-circle face"></li>';
				$('#userGroup').append(faceStr);
			};
			$('#sFinish').prop('disabled', false);
			$('#sFinish').html('完成(' + sendeeArrLength + ')');
		}else { //是否满足完成操作
			$('#sFinish').prop('disabled', true);
			$('#sFinish').html('完成');
		}
		var userScrollerW = $('#userGroup li').length * $('#userGroup li').width(); //userScroller宽度
		$('#userScroller').css('width', userScrollerW);
		userScroll.refresh();
	}

	//checkbox扣选
	$(document).on('click','.userList',function(){
		//触发事件只能单选
		var source = $("#userDataModal").attr("data-source"); //请求来源 请求来源可以是通过ID或者class来获得出处
		var checktype = $("#"+source).attr('data-checktype'); //checkbox类型：false单选 、true多选 
		if(checktype == "false" || !checktype){ //checktype：false`单选  true`多选
			checkOnly(this, 'userList');
		}
    if($(this).find('.ipt-hide').prop('checked')){
      $(this).find('.checkbox').removeClass('cur');
      $(this).find('.ipt-hide').prop('checked', false)
    }else{
      $(this).find('.checkbox').addClass('cur');
      $(this).find('.ipt-hide').prop('checked', true)
    }
    //改变存放人员的数组
    changeSendeeArr(this);
    //删除人员区域设置
    setUserOpts();
  })

  //点击删除人员区域头像
  $(document).on('click','.delFace',function(){
  	var uid = $(this).attr('data-uid');
  	var userListId = 'uid-' + uid;
  	$('#' + userListId).find('.ipt-hide').prop('checked', false);
  	$('#' + userListId).find('.checkbox').removeClass('cur');
  	$(this).remove(); //删除当前用户头像
  	for (var i = 0; i < sendeeArr.length; i++) {
			if (sendeeArr[i].uid == uid) {
				sendeeArr.splice(i, 1); //删除全局数组中存放着的用户
			}
		}
		if(sendeeArr.length > 0){
			$('#sFinish').prop('disabled', false);
			$('#sFinish').html('完成(' + sendeeArr.length + ')');
		}else{
			$('#sFinish').prop('disabled', true);
			$('#sFinish').html('完成');
		}
  	var userScrollerW = $('#userGroup li').length * $('#userGroup li').width(); //userScroller宽度
		$('#userScroller').css('width', userScrollerW);
		userScroll.refresh();
  })

	/**
	 *	方法: checkOnly(obj,element)
	 *	功能: 限制只能单选
	 *	参数: <Object> obj 当前鼠标点击的HTML元素对象 <string> element 需要遍历的元素
	 */
	function checkOnly(obj, element){
    $('.'+element).each(function(){
      if (this != obj){
        $(this).find('.checkbox').removeClass('cur');
        $(this).find('.ipt-hide').prop('checked', false)
      }else{
        if($(this).find('.ipt-hide').prop('checked')){
          $(this).find('.ipt-hide').prop('checked', true)
          $(this).find('.checkbox').addClass('cur');
        }else{
          $(this).find('.checkbox').removeClass('cur');
      		$(this).find('.ipt-hide').prop('checked',false)
        }
      }
    })
	}

	//改变存放人员的全局变量对象
	function changeSendeeArr(userList){
		var uid  = $(userList).attr('data-uid');
		var name = $(userList).attr('data-name');
		var face = $(userList).attr('data-face');
		var userObj = {'uid':uid, 'name':name, 'face':face};
		var chk  = $(userList).find('.ipt-hide');
		var chkStu = $(chk).prop('checked'); //状态
		if($('#userDataModal').attr('data-checktype') == 'true'){ //允许多选
			if(chkStu){ //扣选
				var flag = false;
				for (var i = 0; i < sendeeArr.length; i++) {
					if (sendeeArr[i].uid == uid) {
						flag = true;
						break;
					}
				}
				if(!flag){ //不存在
					sendeeArr.push(userObj);
				}
			}else{ //未扣选
				for (var i = 0; i < sendeeArr.length; i++) {
					if (sendeeArr[i].uid == uid) {
						sendeeArr.splice(i, 1);
					}
				}
			}
		}else{ //只允许单选
			if(chkStu){ //扣选
				sendeeArr = [];
				sendeeArr.push(userObj);
			}else{ 
				sendeeArr = [];
			}
		}
	}
})
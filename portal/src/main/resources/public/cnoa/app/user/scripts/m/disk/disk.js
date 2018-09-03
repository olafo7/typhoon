
//判断当前客户端Android和ios
var userAgent = ''; //浏览器类型
if(/android/ig.test(navigator.userAgent)){ //安卓
	var userAgent = 'android';
}else{ //ios或者其他
	var userAgent = 'ios';
}

/**
 *全局变量`函数
 */
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
	//文件图标
	set_file_ico:function(ext){
		var ext = ext || "folder";//文件后缀ext为空就取folder为值
		ext = ext.toLowerCase(); //大写转小写
		var extArray = new Array("folder","xls","xlsx","doc","docx","ppt","jpg","bmp","png","pdf","gif","rar","xml","html","php","css","zip","txt","swf","wav","mp4","wmv","flv","apk","mp3","rmvb","svg");
		if(~extArray.indexOf(ext)){//定义的class样式存在
			$("#diskFileContent .panel:last .icon").addClass("ico-"+ext);
		}else{//未找到已定义的class样式
			$("#diskFileContent .panel:last .icon").addClass("ico-file");
		}
		if(ext == "folder"){ //文件夹不提供下载功能
			//$("#diskFileContent .btnDownload:last").addClass("disabled");//按钮呈禁用状态
			$("#diskFileContent .btnDownload:last").css("display","none");
		}
	},
	//路径导航
	nav_path:function(path){
		var strs = new Array(); //定义一数组 
		strs = path.split("-"); //字符分割
		for (i=0;i<strs.length;i++) { 
			strs[i];
		}
		if(strs[strs.length-2] == undefined){
			return strs[0];
		}else{
			return strs[strs.length-2];
		}
	},
	//下拉刷新
	pull_down_refresh:function(){
		var pid = $("#nowPage").val();//当前目录id
		var post_url = "m.php?app=user&func=disk&action=index&task=getList";
		$.post(post_url, {pid:pid}, function(json){
			myFns.append_connect(json);
		},"json");
	},
	/**
	*判断一个值是否在数组中 图片
	*@param <string> ext 文件后缀 
	*@return <boolean> 返回true false
	**/
	in_array:function(ext){
		var ext = ext.toLowerCase(); //大写转小写
		var view = ['jpg','jpeg','png','gif','bmp']; //ios支持在线查看类型
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
	//ajax成功数据`追加内容`初始化·查询·刷新
	append_connect:function(json){
		if(json.success){//响应成功
			$("#diskFileContent").empty();//清空数据区
			$("#backPage").val(json.paterId);//设置当前目录的上级id
			if(json.data != null){
				var fileSum = 0;//统计文件总数
				var folderSum = 0;//统计文件夹总数
				$.each(json.data, function(index,array){
					if(array['type'] == "d"){//文件夹
						folderSum += 1; 
					}else{//文件
						fileSum += 1;
					}
					var str = "<div class=\"panel\" data-fid=\""+array['fid']+"\" data-type=\""+array['type']+"\" data-disView=\""+array['disView']+"\" data-disDownload=\""+array['disDownload']+"\" data-name=\""+array['name']+"\" data-size=\""+array['size']+"\" data-posttime=\""+array['posttime']+"\" data-ext=\""+array['ext']+"\" data-downpath=\""+array['downpath']+"\">";
					str += "<header class=\"panel-heading\">";
					str += "<span class=\"title\" data-fid="+array['fid']+"><span class=\"icon \"></span>";
					str += "<span class=\"name-box dir-box\"><span class=\"name\"><span>"+array['name']+"</span></span></span>";
					str += "<span class=\"glyphicon glyphicon-collapse-down enter\"></span></span></header>";
					str += "<div class=\"panel-body\">";
					str += "<botton type=\"button\" class=\"btn btn-xs btn-info btnBrowse\" data-src=\""+array['downpath']+"\">浏览</botton>";
					str += "<button type=\"button\" class=\"btn btn-xs btn-info btnFileView\" data-src=\""+array['downpath']+"\">查看</button>";
					str += "<botton type=\"button\" class=\"btn btn-xs btn-success btnDownload\">下载</botton>";
					str += "<button type=\"button\" class=\"btn btn-xs btn-danger btnDel\">删除</button>";
					str += "<button type=\"button\" class=\"btn btn-xs btn-default btnRenameModal\" data-toggle=\"modal\" data-target=\"#rename\">重命名</button>";
					str += "</div></div>";
					$("#diskFileContent").append(str);
					myFns.set_file_ico(array['ext']); //设置文件图标
					if(!myFns.in_array(array['ext'])){ //不支持在线浏览
						$("#diskFileContent .btnBrowse:last").css("display","none");//隐藏浏览按钮
					}
					if(array['type'] == "f"){ //非共享文件
						$("<span>"+"."+array['ext']+"</span>").appendTo(".name-box .name:last");//追加文件后缀
						$("#diskFileContent .name-box:last").removeClass("dir-box");
						var infoStr = "<div class=\"info\"><span>"+array['size']+"</span><span>"+array['posttime']+"</span></div>";
						$("#diskFileContent .name-box:last").append(infoStr);//追加文件大小、更新时间信息
						if(array['downpath'] != '' && userAgent == 'android'){ //提供下载链接
							$("#diskFileContent .btnDownload:last").attr("data-href",array['downpath']); //下载链接
						}
					}
					if(array['type'] == 'sf'){ //共享文件
						$("<span>"+"."+array['ext']+"</span>").appendTo(".name-box .name:last");//追加文件后缀
						$("#diskFileContent .name-box:last").removeClass("dir-box");
						var infoStr = "<div class=\"info\"><span>"+array['size']+"</span><span>"+array['posttime']+"</span></div>";
						$("#diskFileContent .name-box:last").append(infoStr);//追加文件大小、更新时间信息
						if(array['download'] == '0' || userAgent == 'ios'){ //是否支持下载
							$("#diskFileContent .btnDownload:last").css('display','none'); //不提供下载
						}else if(array['download'] == '1'){
							$("#diskFileContent .btnBrowse:last").attr('data-src',array['downpath']); //支持浏览
							$("#diskFileContent .btnDownload:last").attr("data-href",array['downpath']); //下载链接
						}
					}
					if(array['shareFrom'] != '0'){ //共享人
						$('.name-box .name:last').append("<span class='sharefrom'>[" + array['shareFrom'] + "]");
					}
					if(userAgent == 'ios'){ //ios客户端不可以下载
						$("#diskFileContent .btnDownload:last").css('display','none');
					};
					if(myFns.allow_view_file(array['ext'])){ //支持office文件在线浏览
						$('#diskFileContent .btnFileView:last').show();
					}
				});
				//统计文件及文件夹总数
				var count = "<div class=\"count\"><span class=\"file-sum\">"+fileSum+"个文件</span><span class=\"folder-sum\">"+folderSum+"个文件夹</span></div>";
				$("#diskFileContent").append(count);
			}

			//初始化图片缩放控件
			ImagesZoom.init({
		    "elem": ".btnBrowse"
			});

			myScroll.refresh();//数据加载完成后，调用界面更新方法
		}
	}
}

/**
 *我的硬盘函数
 */
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
	$(document).on('touchstart','#user_disk',function(){
	    $('#jingle_popup').slideUp(100);
	    $('#jingle_popup_mask').hide();
	    return false;
	});
	$(document).on('touchstart','#mgrpub',function(){
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

	//我的硬盘$.ajax
	function user_disk(pid){
		$("#nowPage").val(pid);//标记当前所在目录id
		$("#pullUp").css("display","none");//默认隐藏
		$.ajax({
			type: "POST",
			url: "m.php?app=user&func=disk&action=index&task=getList",
			dataType: "json",
			data: {"pid":pid}, 
			beforeSend: function(){ 
				showLoading();//显示loading
      },
      success: function(json){//追加内容
      	myFns.append_connect(json);
      },
      complete: function(){//隐藏loading
      	hideLoading();
      }
		})
	}

	//我的硬盘$.post
	function user_disk_post(pid){
		$("#nowPage").val(pid);//标记当前所在目录id
		$("#pullUp").css("display","none");//默认隐藏
		var post_url = "m.php?app=user&func=disk&action=index&task=getList";
		$.post(post_url, {"pid":pid}, function(json){
			myFns.append_connect(json);
		},"json")
	}

	//新建文件夹
	function build_folder(){
		var pid = $("#nowPage").val();//当前目录id
		var type = "add";//操作类型
		var name = $("#folderName").val();//新建文件夹名
		if(name == ""){
			jError('文件夹名不能为空',{
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
		var post_url = "m.php?app=user&func=disk&action=index&task=rename";//请求地址
		var post_data = {pid:pid, type:type, name:name};
		$.post(post_url, post_data, function(json){
			if(json.success){//创建文件夹成功
				jSuccess("操作成功",{
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
				user_disk_post(pid);
			}else{//创建失败
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
		},"json");
	}

	//初始化新建文件夹输入框
	$(document).on("click", "#newFolderModal", function(){
		$("#folderName").val('');//清空输入框数据
		setTimeout(function(){
			$("#folderName").focus();
		},500)
	})

	//触发新建文件夹事件
	$(document).on("click", "#btnBuild", function(){
		build_folder();
	});

	//查询文件及文件夹ajax
	function search_file_folder(){
		var word = $("#fileName").val();//搜索内容
		var pid = $("#nowPage").val();//当前目录id
		$.ajax({
			type: "POST",
			url: "m.php?app=user&func=disk&action=index&task=getList",
			dataType: "json",
			data: {word:word, pid:pid}, 
			beforeSend: function(){ 
				showLoading();//显示loading效果
      },
      success: function(json){
      	myFns.append_connect(json);
      },
      complete: function(){
      	hideLoading();
      }
		})
	}

	//初始化查询输入框
	$(document).on("click", "#searchModal", function(){
		$("#fileName").val("");//清空搜索框
		setTimeout(function(){
			$("#fileName").focus();
		},500)
	})

	//触发查询文件及文件夹事件
	$(document).on("click", "#btnSearch", function(){
		search_file_folder();
	})

	//上传文件
	$(document).on("click", "#uploadModal", function(){//点击uploadModal初始化上传表单
		var post_url = "m.php?app=user&func=disk&action=index&task=upload";
		var myUpload = $("#myUpload");
		$("#Filedata").val("");//清空file控件数据
		if(!myUpload.length){//判断表单form是否存在
			$("#Filedata").wrap("<form id='myUpload' action='' method='post' enctype='multipart/form-data'></form>"); 
			$("#myUpload").attr("action",post_url);
		}
	});

	$(document).on("click", "#btnUpload", function(){//点击上传文件
		var pid = $("#nowPage").val();//当前目录的id
		$("#myUpload").ajaxSubmit({
			dataType:"json", //数据格式为json
	        data:{pid:pid},
	        beforeSend:function(){//开始上传
				showLoading();
				return false;
	        },
	        success:function(json){//上传成功
	        	hideLoading();
				if(json.success){
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
				}else if(json.failure){
					jError('上传失败,请检查硬盘空间',{
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
	        	user_disk_post(pid);
	        }
		});
	});	

	//初始化我的网盘数据
	user_disk(0);

	//我的硬盘
	$(document).on('click', "#user_disk", function(){
		user_disk(0);
	})

	//点击文件夹进入下一层级目录
	$(document).on("click","#diskFileContent .name-box",function(){
		var fid = $(this).closest(".panel").attr("data-fid");//获取文件或文件夹id
		var type = $(this).closest(".panel").attr("data-type");//文件类型
		if(type == "d"){//如果是文件夹点击则进入下一层
			user_disk(fid);
		}
	})

	//路径导航
	$(document).on("touchstart","#btnBack",function(){
		if($('.imgzoom_pack').css("display") == "block"){
			$('.imgzoom_pack').css("display","none");
		}else{
			var nowPage = $("#nowPage").val();//当前目录id
			var path_id = $("#backPage").val();//父级目录id
			if(nowPage == "0"){
				if(/android/ig.test(navigator.userAgent)){
					window.javaInterface.returnToMain();
				}else{
					window.location.href = 'js://pop_view_controller';
				}
			}else{
				user_disk(path_id);//把文件夹id传过去
			}
			return false;
		}
	})

	//跳转到公共硬盘
	$(document).on('touchstart','#disk_public',function(){
		window.location.href = 'm.php?app=user&func=disk&action=mgrpub&task=loadPage';
	});

	//效果折叠
	$(document).on("touchstart",".enter",function(){
		var el = $(this).parents(".panel").children(".panel-body");
	    if ($(this).hasClass("glyphicon-collapse-up")) {
	        $(this).removeClass("glyphicon-collapse-up").addClass("glyphicon-collapse-down");
	        el.slideUp(200);
	    }else {
	    	$('.panel-body').slideUp(200); //隐藏所有的.panel-body
	    	$('.enter').removeClass("glyphicon-collapse-up").addClass("glyphicon-collapse-down"); //所有.enter改成折叠向下图标
	        $(this).removeClass("glyphicon-collapse-down").addClass("glyphicon-collapse-up");
	        el.slideDown(200);
	    }
	    setTimeout(function(){//延迟刷新
			myScroll.refresh();//数据加载完成调用界面更新方法
		},300)
		return false;
	});

	//触发删除文件事件
	$(document).on("click",".btnDel",function(){
		var fid = $(this).closest(".panel").attr("data-fid");//获取文件id
		var ids = [];
		ids.push(fid);//传object数据然后转json数据
		del_file_folder(ids);
	})

	//删除文件
	function del_file_folder(ids){
		$.confirm({
		    title: '温馨提示',
		    content: '删除不可恢复，确定要删除吗？',
		    animation: "top",
		    confirm: function(){
		        var post_url = "m.php?app=user&func=disk&action=index&task=delete";
				$.post(post_url, {ids:JSON.stringify(ids)}, function(json){
				    if(json.success){
						jSuccess("删除成功",{
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
						user_disk_post($("#nowPage").val());
				    }else{
						jError('删除失败',{
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
				},"json");
		    }
		});
	}

	//重命名表单初始化
	$(document).on("click", ".btnRenameModal", function(){
		var fid = $(this).closest(".panel").attr("data-fid");//获取文件id
		var name = $(this).closest(".panel").attr("data-name");//获取文件名
		$("#fid").val(fid);//赋值文件id
		$("#newName").val(name);//赋值文件名
		setTimeout(function(){
			$("#newName").focus();//获取焦点
		},500)
	})

	//触发重命名文件事件
	$(document).on("click", "#btnRename", function(){
		rename();
	})

	//重命名文件和文件夹
	function rename(){
		var fid = $("#fid").val();//文件或文件夹id
		var rename = "rename";//操作类型
		var name = $("#newName").val();//新的命名
		var post_data = {fid:fid, rename:rename, name:name};
		var post_url = "m.php?app=user&func=disk&action=index&task=rename";
		$.post(post_url, post_data, function(json){
			if(json.msg == fid){
				jSuccess("重命名成功",{
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
				user_disk_post($("#nowPage").val());
			}else{
				jError('重命名失败',{
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
		},"json");
	}

	//浏览次数`是否还支持浏览
	$(document).on('click','#diskFileContent .btnBrowse',function(){
		var disView = $(this).closest('.panel').attr('data-disView'); //可浏览次数
		var fid = $(this).closest('.panel').attr('data-fid'); //文件id
		if(disView != 0){ //设有浏览次数限制
			var url = 'm.php?app=user&func=disk&action=index&task=viewTimes&fid=' + fid;
			$.post(url,function(json){
				if(json.reflash){ //浏览数量上限
					user_disk(0); //刷新
				}
			},'json')
		}
	})

	//下载次数`是否还支持下载
	$(document).on('click','#diskFileContent .btnDownload',function(){
		var disdownload = $(this).closest('.panel').attr('data-disdownload'); //可下载次数
		var fid = $(this).closest('.panel').attr('data-fid'); //文件id
		if(disdownload != 0){ //设有下载次数限制
			var url = 'm.php?app=user&func=disk&action=index&task=downLoadTimes&fid=' + fid;
			$.post(url,function(json){
				if(json.reflash){ //下载数量上限
					user_disk(0); //刷新
				}
			},'json')
		};
	})

	//下载文件
	$(document).on('click','.btnDownload',function(){
		var downhref = $(this).attr('data-href');
		var url = 'index.php?action=downFile&from=other&code=' + downhref;
		window.location.href = url;
	})

	//ios文件浏览
	$(document).on('click','.btnFileView',function(){
		var filePath = $(this).attr('data-src'), fileExt = $(this).closest('.panel').attr('data-ext');
		if (myFns.isWeiXin()) {
			$('iframe').find('body').attr('background-color','white');
		    var width = $(document).width(); 
		    var height = $(document).height();
			iospopWin.showWin(width,height,'在线浏览',filePath, fileExt.toLocaleLowerCase());
			return false;
		}
		var fileExt  = $(this).closest('.panel').attr('data-ext');
		var fileName = $(this).closest('.panel').attr('data-name');
		var host = window.location.host;
		var fileAllPath = host+'/'+filePath;
		//window.location.href = 'js://push_web_view_controller__'+fileAllPath+'__文件浏览';
		try {
			//新版
			CNOAApp.viewDocument(fileAllPath, fileName, fileExt);
		}catch (e) {
		 	//旧版
			window.location.href = 'js://push_web_view_controller__'+fileAllPath+'__'+fileName;
		}
	})

})
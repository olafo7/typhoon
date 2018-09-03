
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
		ext = ext || "folder";//文件后缀ext为空就取folder为值
		var extArray = new Array("folder","xls","xlsx","doc","docx","ppt","jpg","bmp","png","pdf","gif","rar","xml","html","php","css","zip","txt","swf","wav","mp4","wmv","flv","apk","mp3","rmvb","svg");
		if(~extArray.indexOf(ext)){//定义的class样式存在
			$("#diskFileContent .panel:last .icon").addClass("ico-"+ext);
		}else{//未找到已定义的class样式
			$("#diskFileContent .panel:last .icon").addClass("ico-file");
		}
		if(ext == "folder"){//文件夹不提供下载功能
			//$("#diskFileContent .btnDownload:last").addClass("disabled");//按钮呈禁用状态
			$("#diskFileContent .btnDownload:last").css("display","none");
		}
	},
	//下拉刷新
	pull_down_refresh:function(){
		var pid = $("#nowPage").val();//当前目录id
		var post_url = "m.php?app=user&func=disk&action=mgrpub&task=getList&viewMod=list";
		$.post(post_url,{pid:pid},function(json){
			//alert(JSON.parse(json).data.length);//不指定类型
			myFns.append_connect(json);
		},"json")
	},
	/**
	*判断一个值是否在数组中
	*@param <string> ext 文件后缀 
	*@param <string> userAgent 浏览器类型
	*@return <boolean> 返回true false
	**/
	in_array:function(ext){
		var ext = ext.toLowerCase(); //大写转小写
		var view = ['jpg','jpeg','png','gif','bmp']; //安卓支持在线查看类型
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
						folderSum += 1; //统计
						var strDir = "<div class=\"panel\" data-fid=\""+array['fid']+"\" data-type=\""+array['type']+"\" data-name=\""+array['name']+"\" data-uid=\""+array['uid']+"\" data-ext=\""+array['ext']+"\">";
						strDir += "<header class=\"panel-heading\">";
						strDir += "<span class=\"title\" data-fid="+array['fid']+"><span class=\"icon \"></span>";
						strDir += "<span class=\"name-box\"><span class=\"name\">"+array['name']+"</span>";
						strDir += "<div class=\"info\"><span>"+array['postname']+"</span><span>"+array['posttime']+"</span></div></span>";
						strDir += "<span class=\"glyphicon glyphicon-collapse-down enter\"></span></span></header>";
						strDir += "<div class=\"panel-body\">";
						strDir += "<button type=\"button\" class=\"btn btn-xs btn-danger btnDel\">删除</button>";
						strDir += "<button type=\"button\" class=\"btn btn-xs btn-default btnRenameModal\" data-toggle=\"modal\" data-target=\"#rename\">重命名</button>";
						strDir += "</div></div>";
						$("#diskFileContent").append(strDir);
					}else{//文件
						fileSum += 1;//统计
						var strFile = "<div class=\"panel\" data-fid=\""+array['fileid']+"\" data-type=\""+array['type']+"\" data-name=\""+array['name']+"\" data-size=\""+array['size']+"\"  data-uid=\""+array['uid']+"\" data-posttime=\""+array['posttime']+"\" data-postname=\""+array['postname']+"\" data-ext=\""+array['ext']+"\" data-downhref=\""+array['downhref']+"\">";
						strFile += "<header class=\"panel-heading\">";
						strFile += "<span class=\"title\" data-fid="+array['fileid']+"><span class=\"icon \"></span>";
						strFile += "<span class=\"name-box\"><span class=\"name\">"+array['name']+"."+array['ext']+"</span>";
						strFile += "<div class=\"info\"><span>"+array['size']+"</span><span>"+array['posttime']+"</span></div></span>";
						strFile += "<span class=\"glyphicon glyphicon-collapse-down enter\"></span></span></header>";
						strFile += "<div class=\"panel-body\">";
						strFile += "<botton type=\"button\" class=\"btn btn-xs btn-info btnBrowse\" data-src=\""+array['downhref']+"\">浏览</botton>";
						strFile += "<botton type=\"button\" class=\"btn btn-xs btn-info btnFileView\" data-src=\""+array['downhref']+"\">查看</botton>";
						strFile += "<botton type=\"button\" class=\"btn btn-xs btn-success btnDownload\" data-href=\""+array['downhref']+"\">下载</botton>";
						strFile += "<button type=\"button\" class=\"btn btn-xs btn-danger btnDel\">删除</button>";
						strFile += "<button type=\"button\" class=\"btn btn-xs btn-default btnRenameModal\" data-toggle=\"modal\" data-target=\"#rename\">重命名</button>";
						strFile += "</div></div>";
						$("#diskFileContent").append(strFile);
						if(!myFns.in_array(array['ext'])){//不支持在线浏览
							$("#diskFileContent .btnBrowse:last").css("display","none");//隐藏浏览按钮
						}
					}
					myFns.set_file_ico(array['ext']);//设置文件图标
					if(userAgent == 'ios'){ //ios客户端不可以下载
						$("#diskFileContent .btnDownload:last").css('display','none');
					};
					if(myFns.allow_view_file(array['ext'])){
						$("#diskFileContent .btnFileView:last").show();
					}
				})
				//操作权限
				if(json.dl == 0){ //下载权限
					$('#diskFileContent .btnDownload').css('display','none');
				}
				if(json.dt == 0){ //删除权限
					$('#diskFileContent .btnDel').css('display','none');
				}
				if(json.vi == 0){ //在线浏览权限
					$('#diskFileContent .btnBrowse').css('display','none');
					$('#diskFileContent .btnFileView').css('display','none');
				}
				if(json.up == 0){ //上传权限
					$('#uploadModal').prop('disabled',true);
				}else{
					$('#uploadModal').prop('disabled',false);
				}
				
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
 *公共网盘管理`函数
 */
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
	    $('#jingle_popup').slideDown(100);
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

	//管理公共硬盘ajax
	function mgrpub(pid){
		$("#nowPage").val(pid);//标记当前所在目录id
		$("#pullUp").css("display","none");//默认隐藏
		$.ajax({
			type: "POST",
			url: "m.php?app=user&func=disk&action=mgrpub&task=getList&viewMod=list",
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

	//管理公共硬盘post
	function mgrpub_post(pid){
		$("#nowPage").val(pid);//标记当前所在目录id
		$("#pullUp").css("display","none");//默认隐藏
		var post_url = "m.php?app=user&func=disk&action=mgrpub&task=getList&viewMod=list";
		$.post(post_url,{pid:pid},function(json){
			myFns.append_connect(json);
		},"json")
	}

	//初始化我的公共硬盘管理
	mgrpub(0);

	//我的硬盘
	$(document).on('click', "#mgrpub", function(){
		mgrpub(0);
	})

	//点击文件夹进入下一层级目录
	$(document).on("click","#diskFileContent .name-box",function(){
		var fid = $(this).closest(".panel").attr("data-fid");//获取文件或文件夹id
		var type = $(this).closest(".panel").attr("data-type");//文件类型
		if(type == "d"){//如果是文件夹点击则进入下一层
			mgrpub(fid);//调用公共硬盘更新数据
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
				mgrpub(path_id);//把文件夹id传过去
			}
		}
	})

	//跳转到我的硬盘
	$(document).on('touchstart','#user_disk',function(){
		window.location.href = 'm.php?app=user&func=disk&action=index';
	});

	//效果折叠
	$(document).on("touchstart",".enter",function(){
		var el = $(this).parents(".panel").children(".panel-body");
	    if ($(this).hasClass("glyphicon-collapse-up")) {
	        $(this).removeClass("glyphicon-collapse-up").addClass("glyphicon-collapse-down");
	        el.slideUp(200);
	    } else {
	    	$('.panel-body').slideUp(200); //隐藏所有的.panel-body
	    	$('.enter').removeClass("glyphicon-collapse-up").addClass("glyphicon-collapse-down"); //所有.enter改成折叠向下图标
	        $(this).removeClass("glyphicon-collapse-down").addClass("glyphicon-collapse-up");
	        el.slideDown(200);
	    }
	    setTimeout(function(){//延迟刷新
			myScroll.refresh();//数据加载完成调用界面更新方法
		},300)
	})

	//新建文件夹
	function build_folder(){
		var pid = $("#nowPage").val();//当前目录id
		var type = "add";//操作类型
		var name = $("#folderName").val();//新建文件夹名
		if(name == ""){
			jError("文件夹名不能为空",{
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
		var post_url = "m.php?app=user&func=disk&action=mgrpub&task=rename";//请求地址
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
				mgrpub_post(pid);
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
		var name = $("#folderName").val();//清空输入框数据
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
			url: "m.php?app=user&func=disk&action=mgrpub&task=getList&viewMod=list",
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
		$("#fileName").val("");//清空输入框数据
		setTimeout(function(){
			$("#fileName").focus();
		},500)
	})

	//触发查询文件及文件夹事件
	$(document).on("click", "#btnSearch", function(){
		search_file_folder();
	})

	//初始化上传文件表单
	$(document).on("click", "#uploadModal", function(){//点击uploadModal初始化上传表单
		var post_url = "m.php?app=user&func=disk&action=mgrpub&task=upload";
		var myUpload = $("#myUpload");
		$("#Filedata").val("");//清空file控件数据
		if(!myUpload.length){//判断表单form是否存在
			$("#Filedata").wrap("<form id='myUpload' action='' method='post' enctype='multipart/form-data'></form>"); 
			$("#myUpload").attr("action",post_url);
		}
	});

	//上传文件
	$(document).on("click", "#btnUpload", function(){//点击上传文件
		var pid = $("#nowPage").val();//当前目录的id
		$("#myUpload").ajaxSubmit({
			dataType:"json", //数据格式为json
	        data:{pid:pid},
	        beforeSend:function(){//开始上传
	    		showLoading();
	        },
	        success:function(json){//上传成功
				hideLoading();
				jSuccess("上传成功",{
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
				getThumb(Number(json.msg)); //缩略图
	        	mgrpub_post(pid);
	        },
	        error:function(xhr){//上传失败
	        	hideLoading();
				jError('上传失败',{
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
		})
	})

	/*
	 *生成缩略图
	 *参数：自动增长的文件id
	 */
	function getThumb(fileid){
		var url = 'm.php?app=user&func=disk&action=mgrpub&task=getThumb&fileid=' + fileid;
		$.getJSON(url)
	}

	//触发删除文件事件
	$(document).on("click",".btnDel",function(){
		var fileid = $(this).closest(".panel").attr("data-fid");//获取文件id
		var type = $(this).closest(".panel").attr("data-type");//获取文件类型
		var ids = [{"id":fileid,"type":type}];
		del_file_folder(ids);
	})

	//删除文件
	function del_file_folder(ids){
		$.confirm({
		    title: '温馨提示',
		    content: '删除不可恢复，确定要删除吗？',
		    animation: "top",
		    confirm: function(){
		        var post_url = "m.php?app=user&func=disk&action=mgrpub&task=delete";
				$.post(post_url, {ids:JSON.stringify(ids)}, function(json){
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
				    hideLoading();
				    mgrpub_post($("#nowPage").val());
				},"json");
		    }
		})
	}

	//重命名表单初始化
	$(document).on("click", ".btnRenameModal", function(){
		var fid = $(this).closest(".panel").attr("data-fid");//获取文件id
		var name = $(this).closest(".panel").attr("data-name");//获取文件名
		var ftype = $(this).closest(".panel").attr("data-type");//获取文件类型
		$("#fid").val(fid);//赋值文件id
		$("#newName").val(name);//赋值文件名
		$("#ftype").val(ftype);//复制文件类型
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
		var type = "rename";//操作类型
		var ftype = $("#ftype").val();//文件类型
		var name = $("#newName").val();//新的命名
		if(name == ""){
			jError("名字不能为空",{
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
		var post_data = {fid:fid,type:type,ftype:ftype,name:name};
		var post_url = "m.php?app=user&func=disk&action=mgrpub&task=rename";
		$.post(post_url, post_data, function(json){
			if(json.success){
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
			}else{
				jError("重命名失败",{
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
			mgrpub_post($("#nowPage").val());
		},"json")
	}

	//下载文件
	$(document).on('click','.btnDownload',function(){
		var downhref = $(this).attr('data-href');
		var url = 'index.php?action=downFile&from=other&code=' + downhref;
		window.location.href = url;
	})

	//ios文件浏览
	$(document).on('click','.btnFileView',function(){
		// if(userAgent == 'ios'){
		var filePath = $(this).attr('data-src'), fileExt = $(this).closest('.panel').attr("data-ext");
		if (myFns.isWeiXin()) {
		    var height = $(document).height();
		    var width = $(document).width();
			iospopWin.showWin(width,height,'在线浏览',filePath, fileExt.toLocaleLowerCase());
			return false;
		};
		var fileName = $(this).closest('.panel').attr('data-name');
		var fileExt  = $(this).closest('.panel').attr('data-ext');
		var host = window.location.host;
		var fileAllPath = host+'/'+filePath;
		//window.location.href = 'js://push_web_view_controller__'+fileAllPath+'__文件浏览';
		try {
			//新版
			CNOAApp.viewDocument(fileAllPath, fileName, fileExt);
		}catch (e) {
		 	//旧版
		 	window.location.href = 'js://push_web_view_controller__'+fileAllPath+'__#'+fileName;
		}
		// }
	})

})
/**
 *代码结束
 */

var pageNow = 1;
var myFns = {
	//加载等待效果
	showLoading:function(text){
		var opts = {
			lines: 13, // 画线数
			length: 11, // 每条线的长度
			width: 5, // 线厚度
			radius: 17, // 内圆半径
			corners: 1, // 角圆度(0....1)
			rotate: 0, // 旋转偏移
			color: '#FFF', // 颜色 例：#rgb 和 #rrggbb
			speed: 1, // 每秒轮
			trail: 60, // 余辉百分率
			shadow: false, // 是否渲染一个阴影
			hwaccel: false, // 是否使用硬件加速
			className: 'spinner', // CSS类分配给纺织
			zIndex: 2e9, // z-index（默认为2000000000）
			top: 'auto', // 在像素中相对于父的顶部位置
			left: 'auto' // 左位置相对于父在像素
		};
		var target = document.createElement("div");
		document.body.appendChild(target);
		var spinner = new Spinner(opts).spin(target);
		iosOverlay({
			text: text,
			spinner: spinner
		});
		return false;
	},
	// 下拉刷新
	pull_down_refresh:function(){
		myFns.refreshFns(); //刷新界面
		pageNow = 1; //重置当前页为1
	},
	//上拉加载
	pullUpAction: function(){
		var limit = 15; //每次取15条
		var start = (pageNow-1) * limit;//设置开始取数据
		var name = $('#search').val(); //会议标题
		var url = $('#wrapper').attr('data-url'); //请求数据地址
		var data = {'start':start, 'limit':limit,'name':name,'storeType':'waiting'};
		$.post(url, data, function(json){
			myFns.append_data(json,false);
		},'json');
	},
	//刷新界面
	refreshFns: function(){
		var url = $('#wrapper').attr('data-url'); //请求数据地址
		var data = {'storeType':'waiting'};
		$.post(url,data,function(json){
			myFns.append_data(json,true);
		},'json')
	},
	/*
	 *ajax成功返回数据
	 *json：ajax请求成功返回的data
	 *isEmpty：是否清空数据区 true：清空 false：不清空数据区
	 *追加内容
	 */
	append_data : function(json,isEmpty){
		if (isEmpty) {
			$('#meet_list').empty();
		};
		if(json.success && json.data.length > 0){
			if(json.data.length < 15){ //有超过一页的数据再显示滚动加载提示
				$("#pullUp").css("display","none");
	      		$("#scroller .stop-label").html("已加载完成，不要再滚动了");
	      		$("#scroller .stop-label").css("display","block");
			}else{
				$("#pullUp").css("display","block");
				$("#scroller .stop-label").css("display","none");
			}
			$.each(json.data, function(index,array){
				var str = "<div class=\"panel btnView\">";
				str += "<header class=\"panel-heading\"><span class=\"title\">";
				str += "<span class=\"name-box\">";
				str += "<span class=\"guide\">>></span><span class=\"title\">"+array['name']+"</span>";
				str += "<div class=\"info\"><span class=\"username\">纪要员："+array['markname']+"</span></div>";
				str += "<div class=\"info2\">状态："+array['statusname']+"<span class=\"posttime\">"+array['stime']+"</span></div>";
				str += "</span>";
				str += "<button type'button' class='btn btn-xs opt btn-success' data-aid="+array['aid']+">操作</button>";
				str += "</div>";
				$('#meet_list').append(str);
			})
			pageNow += 1; //当前页+1
		}else{
	    	$("#pullUp").css("display","none");
	    	$("#scroller .stop-label").css("display","block");
		}
		myScroll.refresh();
	}
}

$(function(){

	get_data("m.php?app=meeting&func=mgr&action=join&task=getJiyaoJsonData");

	// 获取信息
	function get_data(url){
		$.ajax({
			type: 'post',
			data: {
				'storeType': 'waiting'
			},
			dataType: 'json',
			url: url,
			beforeSend:function(){
				//请求数据前执行
				myFns.showLoading('正在加载，请稍等...');
			},
			success:function(json){
				myFns.append_data(json,true);
			},
			complete: function(XMLHttpRequest, textStatus){
				$(".ui-ios-overlay").removeClass("ios-overlay-show").addClass("ios-overlay-hide");
			}
		})
	}

	// 点击查看会议信息
	$(document).on('touchstart','#meet_view',function(){
		var aid = $(this).attr('data-aid');
		window.location.href = 'm.php?app=meeting&func=mgr&action=meeting&&task=loadPage&from=view&aid='+aid+'&type=waiting';
	})

	// 跳转添加/修改纪要内容
	$(document).on('touchstart',"#meet_add_edit",function(){
		var aid = $(this).attr('data-aid');
		window.location.href = 'm.php?app=meeting&func=mgr&action=join&task=loadPage&from=listContent&aid='+aid+"&type=waiting";
		return false;
	})

	// 需要我纪要的会议
	$(document).on('touchstart',"#need_me",function(){
		window.location.href = 'm.php?app=meeting&func=mgr&action=meeting&task=loadPage&from=list';
		return false;
	})

	// 需要审核的纪要
	$(document).on('touchstart',"#waiting",function(){
		window.location.href = 'm.php?app=meeting&func=mgr&action=meeting&task=loadPage&from=waiting';
		return false;
	})

	// 审批通过的纪要
	$(document).on('touchstart',"#pass_approval",function(){
		window.location.href = 'm.php?app=meeting&func=mgr&action=join&task=loadPage&from=pass';
		return false;
	})	

	// 需要重写的纪要
	$(document).on('touchstart',"#need_write",function(){
		window.location.href = 'm.php?app=meeting&func=mgr&action=join&task=loadPage&from=returnback';
		return false;
	})

	$(document).on("touchstart","#btnBack",function(){
		// if(/android/ig.test(navigator.userAgent)){
		// 	window.javaInterface.returnToMain();
		// }else{
		// 	window.location.href = 'js://pop_view_controller';
		// }
		// return false;
		window.location.href = 'm.php?app=meeting&func=mgr&action=join&task=loadPage&from=meetingManage';
	})


	//底部添加计划
	$(document).on('touchstart','#listNav',function(){
	    $('#jingle_popup').slideDown(100);
	    $('#jingle_popup_mask').show();
	    return false;
	});

	// 取消按钮
	$(document).on('touchstart','.btn-cancel',function(){
	    $('#jingle_popup').slideUp(100);
	    $('#jingle_popup2').slideUp(100);
	    $('#jingle_popup_mask').hide();
	    return false;
	});

	//关闭弹出窗口 
	$(document).on('touchstart','#jingle_popup_mask',function(event){
		if( $(event.target).is(this)) { 
	       $(this).hide();
	       $('#jingle_popup').slideUp(100);
	       $('#jingle_popup2').slideUp(100);
	    }
	    return false;
	})

	// 选择操作
	$(document).on('touchstart','.opt',function(){
		var aid = $(this).attr('data-aid');
		$('#pass').attr('data-aid',aid);
		$('#unpass').attr('data-aid',aid);
		$('#meet_view').attr('data-aid',aid);
		$('#meet_add_edit').attr('data-aid',aid);
		$('#jingle_popup2').slideDown(100);
	    $('#jingle_popup_mask').show();
	    return false;
	})

	// 搜索关键词
	$(document).on('search','#search',function(){
		var name = $('#search').val(); //纪要标题
		var url = $('#wrapper').attr('data-url'); //请求数据地址
		$.ajax({
			dataType: "json",
			type: "post",
			url: url,
			data:{
				"name":name,
			 	'storeType':'waiting'
			},
			beforeSend: function(){
				//提交表单前验证
				myFns.showLoading("搜索中...");
			},
			success: function(json){
				//提交成功后调用
				myFns.append_data(json,true);
			},
			complete: function(XMLHttpRequest, textStatus){
				$(".ui-ios-overlay").removeClass("ios-overlay-show").addClass("ios-overlay-hide");
			}
		})
		pageNow = 1; //重置当前页为1
	})

	//审批通过,不通过index.php?app=meeting&func=mgr&action=join&task=loadpage&from=pass
	// index.php?app=meeting&func=mgr&action=join&task=loadpage&from=pass
	$(document).on('click','#pass,#unpass',function(){
		var aid = $(this).attr('data-aid');
		var flag = $(this).attr('id');
		var word = '',type = '';
		if(flag == 'pass'){	
			type = 'pass';
			word = '通过';
		}else{
			type = 'unpass';
			word = '不通过';
		}
		$.confirm({
			title: '提示',
			content: '您确定要'+word+'该会议吗?',
			animation: "top",
			cancelButtonClass: 'btn-danger',
			confirmButton: '确定',
			cancelButton: '取消',
			confirm: function(){
				$.ajax({
					type:'post',
					dataType:'json',
					url: 'index.php?app=meeting&func=mgr&action=jiyao&task=check',
					data: {'ids':aid,'type':type},
					success:function(json){
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
							     OpacityOverlay : 0.3,            // 设置遮罩层的透明度 
							     onClosed:function(){
							     	if(type == 'pass'){
							     		window.location.href = 'm.php?app=meeting&func=mgr&action=join&task=loadPage&from=pass';
							     	}else{
							     		window.location.href = 'm.php?app=meeting&func=mgr&action=join&task=loadPage&from=returnback';
							     	}
							     }
							});
						}else{
							jError('操作失败',{
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
				})
			}
		});
	});
})
//全局变量`
var pageNow = 1;
var aid = '';
var myFns = {
	//加载等待效果
	showLoading:function(){
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
			spinner: spinner
		});
		return false;
	},
	/*
	 *ajax成功返回数据
	 *json：ajax请求成功返回的data
	 *isEmpty：是否清空数据区 true：清空 false：不清空数据区
	 *追加会议阅读情况到表格
	 */
	append_data : function(json,isEmpty){
		if (isEmpty) {
			$('#con_box').empty();
		}
		if (json.success && json.data.length != null ) {
			if(json.data.length < 15){//有超过一页的数据再显示滚动加载提示
				$("#pullUp").css("display","none");
		      	$("#scroller .stop-label").html("已加载完成，不要再滚动了");
		      	$("#scroller .stop-label").css("display","block");
			}else{
				$("#pullUp").css("display","block");
				$("#scroller .stop-label").css("display","none");
			}
			$.each(json.data, function(index,array){
				var str = '<tr>';
					str += "<td class='td1'><a href='javascript:void(0)' data-aid="+array['aid']+" class='meetName'>"+array['name']+"</a></td>";
					str += "<td class='td2'><a href='javascript:void(0)' class='markdetail'>"+'开始:'+array['stime']+'<br/>结束:'+array['etime']+"</a></td>";
					// str += "<td style='display:none'>"+array['etime']+'-'+array['stime']+"</td>";
					str += "<td class='td3'><button type='button' class='btn btn-success task'>讨论</button>";
					str += "<br>";
					if(array['joinStatu'] == '1'){
						str += "<button type='button' class='btn btn-info isJoin' data-toggle='modal' data-target='#myModal'>是否参加</button>";
					}else{
						str += "";
					}
					str += '</td></tr>';
				$('#con_box').append(str);
			})
			pageNow += 1; //当前页+1
		}else{
	    	$("#pullUp").css("display","none");
	    	$("#scroller .stop-label").css("display","block");
		}
		myScroll.refresh();
	},

	pull_down_refresh:function(){
		myFns.refreshFns(); //刷新界面
		pageNow = 1; //重置当前页为1
	},

	//上拉加载
	pullUpAction: function(){
		var limit = 15; //每次取15条
		var start = (pageNow-1) * limit;//设置开始取数据
		var name = $('#search').val(); //会议标题
		var url = $('#f_control').attr('data-url'); //请求数据地址
		var data = {'start':start, 'name':name,"storeType":'all'};
		$.post(url, data, function(json){
			myFns.append_data(json,false);
		},'json');
	},

	//刷新界面
	refreshFns: function(){
		var name = $('#search').val(); //会议标题
		var url = $('#f_control').attr('data-url'); //请求数据地址
		$.post(url,{'name':name, "storeType":'all'}, function(json){
			myFns.append_data(json,true);
		},'json')
	}
}
 
$(function(){

	// 获取我的会议信息
	function get_my_meeting(url){
		$.ajax({
			dataType: "json",
			type: "post",
			url: url,
			beforeSend: function(){
				//提交表单前验证
				myFns.showLoading();
			},
			success: function(json){
				myFns.append_data(json,true);
			},
			complete: function(XMLHttpRequest, textStatus){
				$(".ui-ios-overlay").removeClass("ios-overlay-show").addClass("ios-overlay-hide");
			}
		})

		pageNow = 1; //重置当前页为1
	}

	// 获取我的会议
	get_my_meeting('m.php?app=meeting&func=mgr&action=join&task=getJsonData');

	// 查看纪要内容
	// $(document).on('touchstart','.markdetail',function(){
	// 	var msg = $(this).parent().next('td').html();
	// 	$.confirm({
	// 	    title: '',
	// 	    content: msg,
	// 	    animation: "top",
	// 	    cancelButtonClass: 'btn-danger',
	// 	    confirmButton: '确定',
	// 		cancelButton: false
	//     })
	//     return false;
	// })

	// 根据会议名称搜索
	$(document).on('search','#search',function(){
		var name = $('#search').val(); //公告标题
		var url = $('#f_control').attr('data-url'); //请求数据地址
		$.ajax({
			dataType: "json",
			type: "post",
			url: url,
			data:{
				"storeType":'all',
				'name' : name
			},
			beforeSend: function(){
				//提交表单前验证
				myFns.showLoading();
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

	// 查看会议信息
	$(document).on('touchstart','.meetName',function(){
		var aid = $(this).attr('data-aid');
		window.location.href = 'm.php?app=meeting&func=mgr&action=meeting&&task=loadPage&from=view&aid='+aid+'&type=join';
		return false;
	})

	// 点击讨论
	$(document).on('touchstart','.task',function(){
		var aid = $(this).parents('tr').children().find('.meetName').attr('data-aid');
		window.location.href = 'm.php?app=meeting&func=mgr&action=join&&task=loadPage&from=viewdiscusslist&aid='+aid;
		return false;
	})

	//是否参加
	$(document).on('click','.isJoin',function(){
		aid = $(this).parents('.td3').parents().find('.meetName').attr('data-aid');
	});
	$('input[type=radio]').change(function(){
		var sel = $('input[type=radio]:checked').val();
		if(sel == '2'){
			$('#reason').show();
		}else{
			$('#reason').hide();
		}
	});
	$(document).on('touchstart',".submit",function(){
		var sel = $('input[type=radio]:checked').val();
		var reason = $('textarea').val();
		$.ajax({
			dataType: "json",
			type: "post",
			url: 'index.php?app=meeting&func=mgr&action=join&task=updateStatus',
			data:{
				aid:aid,
				reason:reason,
				status:sel
			},
			success: function(json){
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
					$('#myModal').modal('hide');
					return false;
				}else{
					jNotify(json.msg,{
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

	$(document).on("touchstart","#btnBack",function(){
		// if(/android/ig.test(navigator.userAgent)){
		// 	window.javaInterface.returnToMain();
		// }else{
		// 	window.location.href = 'js://pop_view_controller';
		// }
		// return false;
		window.location.href = 'm.php?app=meeting&func=mgr&action=join&task=loadPage&from=meetingManage';
	})
})
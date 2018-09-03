//全局变量`
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
			$("#pullUp").css("display","none");
	      	$("#scroller .stop-label").html("已加载完成，不要再滚动了");
	      	$("#scroller .stop-label").css("display","block");
			$.each(json.data, function(index,array){
				var str = "<div class='post'>";
					str += "<div><a href='javascript:void(0)' data-did="+array['did']+" data-aid="+array['aid']+" class='title'>"+array['title']+"</a></div>";
					str += "<div class='author'>作者:"+array['postname']+"</div>";
					str += "<div class='posttime'>发表时间: "+array['posttime']+"  最后回复时间: "+array['lasttime']+"</div>";
					str += "<div class='posttime'></div>";
					str += '</div>';
				$('#con_box').append(str);
			})
		}
		myScroll.refresh();
	},

	pull_down_refresh:function(){
		myFns.refreshFns(); //刷新界面
		// pageNow = 1; //重置当前页为1
	},

	//上拉加载
	pullUpAction: function(){
		var title = $('#search').val(); //会议标题
		var aid = myFns.getUrlValue('aid');
		var url = $('#f_control').attr('data-url')+'&aid='+aid; //请求数据地址
		var data = {'title':title};
		$.post(url, data, function(json){
			myFns.append_data(json,true);
		},'json');
	},

	//刷新界面
	refreshFns: function(){
		var title = $('#search').val(); //会议标题
		var aid = myFns.getUrlValue('aid');
		var url = $('#f_control').attr('data-url')+'&aid='+aid; //请求数据地址
		$.post(url,{'title':title}, function(json){
			myFns.append_data(json,true);
		},'json')
	},
	/**
	 *获取url参数值
	 *参数：键
	 **/
	getUrlValue:function(key){
	    var reg = new RegExp("(^|&)"+ key +"=([^&]*)(&|$)");
	    var r = window.location.search.substr(1).match(reg);
	    if(r!=null)return  unescape(r[2]); return null;
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
	}

	var aid = myFns.getUrlValue('aid');
	// 获取我的发帖列表
	get_my_meeting('m.php?app=meeting&func=mgr&action=join&task=viewdiscusslist&aid='+aid);

	// 根据名称搜索帖子
	$(document).on('search','#search',function(){
		var title = $('#search').val(); //公告标题
		var url = $('#f_control').attr('data-url')+'&aid='+aid; //请求数据地址
		$.ajax({
			dataType: "json",
			type: "post",
			url: url,
			data:{
				'title' : title
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
	})

	// 根据名称搜索帖子
	$(document).on('touchstart','#sub',function(){
		var title = $("#recipient-name").val();
		var message = $("#message-text").val();
		if (!title || !message) {
			var msg = title ? '内容' : '标题';
			$.confirm({
			    title: '',
			    content: msg+'不能为空',
			    animation: "top",
			    cancelButtonClass: 'btn-danger',
			    confirmButton: '确定',
				cancelButton: false
		    })
		    return false;
		};
		var url = 'm.php?app=meeting&func=mgr&action=join&task=discussprojadd';
		$.ajax({
			dataType: "json",
			type: "post",
			url: url,
			data: {
				'aid': aid,
				'title' : title,
				'content': message
			},
			beforeSend: function(){
				//提交表单前验证
				myFns.showLoading();
				$('#myModal').modal('hide')
			},
			success: function(json){
				myFns.refreshFns();
			},
			complete: function(XMLHttpRequest, textStatus){
				$(".ui-ios-overlay").removeClass("ios-overlay-show").addClass("ios-overlay-hide");
			}
		})
	})

	// 进入讨论页面
	$(document).on('touchstart','#btnBack',function(){
		window.location.href = 'm.php?app=meeting&func=mgr&action=join&&task=loadPage';
	})


	// 点击进去查看讨论列表
	$(document).on('touchstart','.title',function(){
		var did = $(this).attr('data-did');
		window.location.href = 'm.php?app=meeting&func=mgr&action=join&&task=loadPage&from=tasklist&did='+did;
	})
})
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
		};
		$('.taskTitle').html(json.data.title);
		$('.postname').html(json.data.postname);
		$('.posttime').html(json.data.posttime);
		$('#taskTBox').attr('data-aid',json.data.aid);
		var cstr = "<div class='panel'>";
			cstr += "<header class='panel-heading'>";
			cstr += "<span class='title'>";
			cstr += "<img src="+json.data.face+" class='img-circle face'>";
			cstr += "<span class='truename'>"+json.data.postname+"</span>";
			cstr += "<span class='name-box'>";
			cstr += "<span class='name'>"+json.data.content+"</span>";
			cstr += "<div class='info'>";
			cstr += "<span class='tate'>"+json.data.posttime+"</span>";
			cstr += "</div>";
			cstr += "</span>";
			cstr += "</span>";
			cstr += "</header>";
			cstr += '</div>';
		$('#con_box').append(cstr);
		if (json.success && json.data.list.length != null ) {
			// if(json.data.length < 15){//有超过一页的数据再显示滚动加载提示
				$("#pullUp").css("display","none");
		      	$("#scroller .stop-label").html("已加载完成，不要再滚动了");
		      	$("#scroller .stop-label").css("display","block");
			// }else{
				// $("#pullUp").css("display","block");
				// $("#scroller .stop-label").css("display","none");
			// }
			$.each(json.data.list, function(index,array){
				var str = "<div class='panel'>";
					str += "<header class='panel-heading'>";
					str += "<span class='title'>";
					str += "<img src="+array['face']+" class='img-circle face'>";
					str += "<span class='truename'>"+array['postname']+"</span>";
					str += "<span class='name-box'>";
					str += "<span class='name'>"+array['content']+"</span>";
					str += "<div class='info'>";
					str += "<span class='tate'>"+array['posttime']+"</span>";
					str += "<span>"+(index+1)+"楼</span>";
					str += "</div>";
					str += "</span>";
					str += "<button type='button' data-rid="+array['rid']+" class='btn btn-xs status btn-success reply' data-toggle='modal' data-target='#myModal' data-whatever='@mdo'>回复</button>";
					str += "</span>";
					str += "</header>";
					str += '</div>';
				$('#con_box').append(str);
			})
			pageNow += 1; //当前页+1
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
		var did = myFns.getUrlValue('did');
		var url = $('#taskTBox').attr('data-url')+'&did='+did; //请求数据地址
		$.post(url, function(json){
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

	var did = myFns.getUrlValue('did');
	// 获取我的发帖列表
	get_my_meeting('m.php?app=meeting&func=mgr&action=join&task=getDiscussInfo&did='+did);

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

	// 提交回复内容
	$(document).on('touchstart','#sub',function(){
		var content = $("#message-text").val();
		var rid = $("#myModal").attr('data-rid');
		if (!content) {
			$.confirm({
			    title: '',
			    content: '回复内容不能为空',
			    animation: "top",
			    cancelButtonClass: 'btn-danger',
			    confirmButton: '确定',
				cancelButton: false
		    })
		    return false;
		};
		var url = 'm.php?app=meeting&func=mgr&action=join&task=discussprojreply';
		$.ajax({
			dataType: "json",
			type: "post",
			url: url,
			data: {
				'did': did,
				'rid' : rid,
				'content': content
			},
			beforeSend: function(){
				//提交表单前验证
				myFns.showLoading();
			},
			success: function(json){
				$('#myModal').modal('hide');
				$("#message-text").val('')
				myFns.refreshFns();
			},
			complete: function(XMLHttpRequest, textStatus){
				$(".ui-ios-overlay").removeClass("ios-overlay-show").addClass("ios-overlay-hide");
			}
		})
	})

	// 返回帖子列表
	$(document).on('touchstart','#btnBack',function(){
		var aid = $('#taskTBox').attr('data-aid');
		window.location.href = 'm.php?app=meeting&func=mgr&action=join&&task=loadPage&from=viewdiscusslist&aid='+aid;
	})


	$(document).on('touchstart','.reply',function(){
		var	rid = $(this).attr('data-rid');
		$('#myModal').attr('data-rid',rid);
	})

	// 点击进去查看讨论列表
	// $(document).on('touchstart','.title',function(){
	// 	var did = $(this).attr('data-did');
	// 	window.location.href = 'm.php?app=meeting&func=mgr&action=join&&task=loadPage&from=tasklist&did='+did;
	// })
})
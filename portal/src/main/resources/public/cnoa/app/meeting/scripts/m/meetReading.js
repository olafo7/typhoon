//全局变量`
var pageNow = 1;

//全局变量`函数
var myFns = {
	//下拉刷新
	pull_down_refresh: function(){		
		myFns.refreshFns(); //刷新界面
		pageNow = 1; //重置当前页为1
	},
	//上拉加载
	pullUpAction: function(){
		var rows = 15; //每次取15条
		var start = (pageNow-1) * rows;//设置开始取数据
		var name = $('#search').val(); //会议标题
		var url = $('#myTabs').attr('data-url'); //请求数据地址
		var storeType = $('.active a').attr('data-storeType');
		var data = {'start':start, 'storeType': storeType, 'name':name};
		$.post(url, data, function(json){
			var myTabs = Number($('#myTabs').attr('data-tab'));
			myFns.isRead_meeting(json,false);
		},'json');
	},

	/*
	 *ajax成功返回数据`追加内容`初始化·查询·刷新
	 *json：ajax请求成功返回的data
	 *isEmpty：是否清空数据区 true：清空 false：不清空数据区
	 *全部会议阅读
	 */
	isRead_meeting: function(json,isEmpty){
		if(isEmpty){ //不是上拉加载就清空数据区
			$("#meetingList").empty();
		}
		if(json.success && json.data.length != null ){
			if(json.data.length < 15){//有超过一页的数据再显示滚动加载提示
				$("#pullUp").css("display","none");
	      		$("#scroller .stop-label").html("已加载完成，不要再滚动了");
	      		$("#scroller .stop-label").css("display","block");
			}else{
				$("#pullUp").css("display","block");
				$("#scroller .stop-label").css("display","none");
			}
			$.each(json.data, function(index,array){
				var str = "<div class=\"panel btnView\" data-rid="+array['rid']+" data-aid="+array['aid']+">";
				str += "<header class=\"panel-heading\"><span class=\"name\">";
				str += "<span class=\"name-box\">";
				str += "<span id=\"guide\">>></span><span class=\"title\">"+array['name']+"</span>";
				str += "<div class=\"info2\">"+array['title']+"</div>";
				str += "<div class=\"info\"><span class=\"username\">开始时间："+array['stime']+"</span>";
				str += "<span class=\"posttime\">结束时间"+array['etime']+"</span></div>";
				str += "</span>";
				str += "</div>";
				$('#meetingList').append(str);
			})
			pageNow += 1; //当前页+1
		}else{
	    	$("#pullUp").css("display","none");
	    	$("#scroller .stop-label").css("display","block");
		}
		myScroll.refresh();
	},

	//刷新界面
	refreshFns: function(){
		var name = $('#search').val(); //会议标题
		var url = $('#myTabs').attr('data-url'); //请求数据地址
		var storeType = $('.active a').attr('data-storeType');
		$.post(url,{'name':name, 'storeType': storeType}, function(json){
			var myTabs = Number($('#myTabs').attr('data-tab'));
			myFns.isRead_meeting(json,true);
		},'json')
	}
}


$(function(){
	//会议阅读已读、未读数据
	function meetingList(url,storeType){
		var name = $('#search').val(); //会议标题
		$.ajax({
			dataType: "json",
			type: "post",
			url: url,
			data:{
				"name":name,
				'storeType': storeType
			},
			success: function(json){
				//提交成功后调用
				myFns.isRead_meeting(json, true);
			},
			complete: function(XMLHttpRequest, textStatus){
				$(".ui-ios-overlay").removeClass("ios-overlay-show").addClass("ios-overlay-hide");
			}
		})
	}

	//默认加载未读的会议信息
	meetingList('m.php?app=meeting&func=mgr&action=meeting&task=getJsonData','waiting');

	// 获取阅读状态
	$(document).on('click','.readType',function(){
		pageNow = 1; //重置当前页为1
		var storeType = $(this).attr('data-storeType');
		meetingList('m.php?app=meeting&func=mgr&action=meeting&task=getJsonData', storeType);
	})

	$(document).on("touchstart","#btnBack",function(){
		window.location.href = 'm.php?app=meeting&func=mgr&action=join&task=loadPage&from=meetingManage';
	})

	//搜索公告
	$(document).on('search','#search',function(){
		var name = $('#search').val(); //公告标题
		var url = $('#myTabs').attr('data-url'); //请求数据地址
		$.ajax({
			dataType: "json",
			type: "post",
			url: url,
			data:{
				"name":name
			},
			success: function(json){
				//提交成功后调用
				myFns.isRead_meeting(json,true);
			},
			complete: function(XMLHttpRequest, textStatus){
				$(".ui-ios-overlay").removeClass("ios-overlay-show").addClass("ios-overlay-hide");
			}
		})
		pageNow = 1; //重置当前页为1
	})

	//触发查看事件
	$(document).on('click','.btnView',function(){
		var rid = $(this).closest('.panel').attr('data-rid'); //阅读id
		var aid = $(this).closest('.panel').attr('data-aid'); //会议id
		window.location.href = 'm.php?app=meeting&func=mgr&action=meeting&&task=loadPage&from=view&rid='+rid+'&aid='+aid;
	});

	$(document).on('touchstart','#listNav',function(){
	    $('#jingle_popup').slideToggle('fast');
	    $('#jingle_popup_mask').show();
	    return false;
	});
	// 需要我纪要的会议
	$(document).on('touchstart',"#need_me",function(){
		window.location.href = 'm.php?app=meeting&func=mgr&action=meeting&task=loadPage&from=list';
		return false;
	})

	// 需要审核的纪要
	$(document).on('touchstart',"#waiting",function(){
		window.location.href = 'm.php?app=meeting&func=mgr&action=join&task=loadPage&from=waiting';
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
	//我参与的所有会议
	$(document).on('touchstart',"#join_me",function(){
		window.location.href = 'm.php?app=meeting&func=mgr&action=join&&task=loadPage';
		return false;
	})
	//会议审批
	$(document).on('touchstart','#pass_meet',function(){
		window.location.href = 'm.php?app=meeting&func=mgr&action=join&&task=loadPage&from=meetingPassList';
		return false;
	})
	$(document).on('touchstart','.btn-cancel',function(){
	    $('#jingle_popup').slideUp(100);
	    $('#jingle_popup2').slideUp(100);
	    $('#jingle_popup_mask').hide();
	    return false;
	});
})
//全局变量`
var pageNow = 1;

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
	//下拉刷新
	pull_down_refresh:function(){
		myFns.refreshFns(); //刷新界面
		pageNow = 1; //重置当前页为1
	},
	
	//上拉加载
	pullUpAction: function(){
		var limit = 15; //每次取15条
		var start = (pageNow-1) * limit;//设置开始取数据
		var title = $('#search').val(); //公告标题
		var url = $('#myTabs').attr('data-url'); //请求数据地址
		var data = {'start':start, 'limit':limit, 'title':title};
		$.post(url, data, function(json){
			var myTabs = Number($('#myTabs').attr('data-tab'));
			myFns.isRead_notice(json,true);
		},'json');
	},

	/*
	 *ajax成功返回数据`追加内容`初始化·查询·刷新
	 *json：ajax请求成功返回的data
	 *isEmpty：是否清空数据区 true：清空 false：不清空数据区
	 *全部工作流程
	 */
	isRead_notice: function(json,isEmpty){
		if(isEmpty){ //不是上拉加载就清空数据区
			$("#noticeList").empty();
		}
		if(json.success && json.data.length > 0){
			if(json.count == json.data.length){ //有超过一页的数据再显示滚动加载提示
				$("#pullUp").css("display","none");
	      		$("#scroller .stop-label").html("已加载完成，不要再滚动了");
	      		$("#scroller .stop-label").css("display","block");
			}else{
				$("#pullUp").css("display","block");
				$("#scroller .stop-label").css("display","none");
			}
			$.each(json.data, function(index,array){
				var str = "<div class=\"panel btnView\" data-id="+array['id']+">";
				str += "<header class=\"panel-heading\"><span class=\"title\">";
				str += "<span class=\"name-box\">";
				str += "<span id=\"guide\">>></span><span class=\"title\">"+array['title']+"</span>";
				str += "<div class=\"info\"><span class=\"username\">发表人："+array['poster']+"</span>";
				str += "<span class=\"posttime\">"+array['posttime']+"</span></div>";
				str += "<div class=\"info2\">"+array['content']+"</div>";
				str += "</span>";
				str += "</div>";
				$('#noticeList').append(str);
				myFns.replace();
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
		var title = $('#search').val(); //公告标题
		var url = $('#myTabs').attr('data-url'); //请求数据地址
		$.post(url,{'title':title}, function(json){
			var myTabs = Number($('#myTabs').attr('data-tab'));
			myFns.isRead_notice(json,true);
		},'json')
	},

	//替换标题样式
	replace: function(){
		$('.name-box .name').find('span').css('color','#221814');
	}
}


$(function(){

	//关闭弹出窗口 
	$(document).on('touchstart','#jingle_popup_mask',function(event){
		if( $(event.target).is(this)) { 
       $(this).hide();
       $('#jingle_popup').slideUp(100);
    }
    return false;
	})
	//路径导航
	$(document).on("touchstart","#btnBack",function(){
		if(/android/ig.test(navigator.userAgent)){
			window.javaInterface.returnToMain();
		}else{
			window.location.href = 'js://pop_view_controller';
		}
		return false;
	})
	//通知公告已读、未读数据
	function noticeList(url){
		var title = $('#search').val(); //公告标题
		$.ajax({
			dataType: "json",
			type: "post",
			url: url,
			data:{
				"title":title
			},
			beforeSend: function(){
				//提交表单前验证
				showLoading();
			},
			success: function(json){
				//提交成功后调用
				var myTabs = Number($('#myTabs').attr('data-tab'));
				myFns.isRead_notice(json, true);
				
			},
			complete: function(XMLHttpRequest, textStatus){
				hideLoading();
			}
		})
	}

	//默认加载未读公告
	noticeList('m.php?app=news&func=notice&action=index&task=getNoticeListM&isRead=0');

	//已读公告
	$(document).on('click','#isRead',function(){
		pageNow = 1; //重置当前页为1
		var url = 'm.php?app=news&func=notice&action=index&task=getNoticeListM&isRead=1'; //请求数据地址
		$('#myTabs').attr('data-tab','0'); 
		$('#myTabs').attr('data-url',url); //设置请求数据地址
		noticeList(url);
	});

	//未读公告
	$(document).on('click','#noRead',function(){
		pageNow = 1; //重置当前页为1
		var url = 'm.php?app=news&func=notice&action=index&task=getNoticeListM&isRead=0'; //请求数据地址
		$('#myTabs').attr('data-tab','1'); 
		$('#myTabs').attr('data-url',url); //设置请求数据地址
		noticeList(url);
	});

	//搜索公告
	$(document).on('search','#search',function(){
		var title = $('#search').val(); //公告标题
		var url = $('#myTabs').attr('data-url'); //请求数据地址
		$.ajax({
			dataType: "json",
			type: "post",
			url: url,
			data:{
				"title":title
			},
			beforeSend: function(){
				//提交表单前验证
				showLoading();
			},
			success: function(json){
				//提交成功后调用
				var myTabs = Number($('#myTabs').attr('data-tab'));
				myFns.isRead_notice(json,true);
			},
			complete: function(XMLHttpRequest, textStatus){
				hideLoading();
			}
		})
		pageNow = 1; //重置当前页为1
	})

	//触发查看事件
	$(document).on('click','.btnView',function(){
		var id = $(this).closest('.panel').attr('data-id'); //公告id
		window.location.href = 'm.php?app=news&func=notice&action=index&task=loadPage&from=view&id='+id;
	});

})

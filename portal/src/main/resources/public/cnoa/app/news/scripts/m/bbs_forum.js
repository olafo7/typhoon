//全局变量`函数
var pageNow = 1;
var myFns = {
	// 下拉刷新
	pull_down_refresh:function(){
		myFns.refreshFns(); //刷新界面
		pageNow = 1; //重置当前页为1
	},
	//上拉加载
	pullUpAction: function(){
		var limit = 15; //每次取15条
		var start = (pageNow-1) * limit;//设置开始取数据
		var url = $('#wrapper').attr('data-url');
		var data = {'start':start};
		$.post(url, data, function(json){
			myFns.append_data(json,false);
		},'json');
	},
	//刷新界面
	refreshFns: function(){
		var url = $('#wrapper').attr('data-url'); //请求数据地址
		$.post(url,function(json){
			myFns.append_data(json,true);
		},'json')
	},
	/*
	 *	方法: myFns.append_data(json,isEmpty);
	 *	功能: 追加数据到前台显示
	 *  参数: json  JSON数据   isEmpty 是否要清空数据
	 */
	append_data: function(json,isEmpty){
		if (isEmpty) {
			$('.forum_list').empty();
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
				var str = '<li data-id='+array['id']+'>';
					str +=''+array['name']+'<span>></span>';
					str +='</li>';
				$('.forum_list').append(str);
			})
		}else{
	    	$("#pullUp").css("display","none");
	    	$("#scroller .stop-label").css("display","block");
		}
		pageNow += 1;
	    myScroll.refresh();
	}

}

$(function(){

	// 获取帖子分类
	function getPostForum(url){
		$.ajax({
			dataType: "json",
			type: 'post',
			url: url,
			success: function(json){
				myFns.append_data(json,true);
			}
		})
	}

	// 初始化数据
	getPostForum('m.php?app=news&func=bbs&action=bbs&task=getPostForum');

	// 点击跳转页面
	$(document).on('click','.forum_list li',function(){
		var id = $(this).attr('data-id');
		window.location.href = 'm.php?app=news&func=bbs&action=bbs&task=loadPage&from=forumlist&id='+id;
    	return false;
	})

    // 跳转至上一步
    $(document).on('touchstart','#btnBack',function(){
    	window.location.href = 'm.php?app=news&func=bbs&action=bbs&task=loadPage&from=main';
    	return false;
    })
})
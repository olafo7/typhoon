var pageNow = 1;
//全局变量`函数
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
		var title = $('#search').val(); //帖子标题
		var url = $('#wrapper').attr('data-url');
		var data = {'start':start,'title':title};
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
			$('.bbs_content').empty();
		};
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
				var str = '<div class="post_info center-block" data-pid='+array['id']+'>';
					str +='<div class="post_author">'+array['author']+'</div>';
					if (index<3) {
						str += '<div class="hot_order order_'+(index+1)+'"><span>'+(index+1)+'</span></div>';
					}
					str += '<div class="collect"></div>';
					var cname = array['type'] == 1 ? '取消收藏' : '收藏帖子';
					str += '<div class="collect_this" data-type='+array['type']+' data-pid='+array['id']+' data-status="false">'+cname+'</div>';
					str +='<div class="post_face">';
					str +='<img src='+array['face']+'>';
					str +='</div>';
					str +='<div class="post_title">'+array['title']+'</div>';
					str +='<div class="post_time">'+array['posttime']+'</div>';
					str +='</div>';
				$('.bbs_content').append(str);
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

	// 获取后台传回前台的数据
    function getJsonData(url){
    	$.ajax({
			dataType: "json",
			type: 'post',
			url: url,
			success: function(json){
				myFns.append_data(json,true);
			}
		})
    }

    getJsonData('m.php?app=news&func=bbs&action=bbs&task=getHotPosts');

    //搜索帖子
	$(document).on('search','#search',function(){
		var title = $('#search').val(); //帖子标题
		var url = $('#wrapper').attr('data-url');
		$.ajax({
			dataType: "json",
			type: "post",
			url: url,
			data:{
				"title":title
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

	// 跳转至论坛中心
    $(document).on('touchstart','#btnBack',function(){
    	window.location.href = 'm.php?app=news&func=bbs&action=bbs&task=loadPage&from=main';
    	return false;
    })

    // 显示收藏帖子
    $(document).on('click','.collect',function(){
    	var collect;
    	collect = $(this).next().attr('data-status');
    	if(collect == 'true'){
    		$(this).next().hide();
    		$(this).next().attr('data-status',false);
    	} else {
    		$(this).next().show();
    		$(this).next().attr('data-status',true);
    	}
    	return false;
    })

    // 收藏帖子
    $(document).on('click','.collect_this',function(){
    	var pid = $(this).attr('data-pid');
    	var type = $(this).attr('data-type');
    	var collect = $(this);
    	$.ajax({
			dataType: "json",
			type: "post",
			data: {
				'pid': pid,
				'type': type
			},
			url: 'm.php?app=news&func=bbs&action=bbs&task=collectPost',
			success: function(json){
				console.log(json);
				if(json.success) {
					collect.attr('data-type',json.type);
					if(json.type == '1') {
						collect.html('取消收藏');
					} else {
						collect.html('收藏帖子');
					}
					return false;
				}
			},
			complete: function(XMLHttpRequest, textStatus){
				$(".ui-ios-overlay").removeClass("ios-overlay-show").addClass("ios-overlay-hide");
			}
		})
    })


    // 跳转帖子正文
    $(document).on('click','.post_info .post_title',function(){
    	var pid = $(this).parents('.post_info').attr('data-pid');
    	window.location.href = 'm.php?app=news&func=bbs&action=bbs&task=loadPage&from=postinfo&pid='+pid+'&type=hot';
    	return false;
    })
})
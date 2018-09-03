var pageNow = 1;
var froms = '';
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
		froms = myFns.getUrlData();
		url = url+'&from='+froms;
		var data = {'start':start,'title':title};
		$.post(url, data, function(json){
			myFns.append_data(json,false);
		},'json');
	},
	//刷新界面
	refreshFns: function(){
		var url = $('#wrapper').attr('data-url'); //请求数据地址
		froms = myFns.getUrlData();
		url = url+'&from='+froms;
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
		if (json.fname) {
			$('.bbs_title').html(json.fname);
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
				var str = '<div class="post_info center-block" data-pid='+array['id']+'>';
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
	},
	/**
	 *	方法: myFns.getUriString(key)
	 *	功能: 获取url参数值
	 *	参数: <string> key URL键
	 *	返回: <string> URL 对应的键值
	 */
	getUriString: function(key){
		var reg = new RegExp("(^|&)"+ key +"=([^&]*)(&|$)");
	    var r = window.location.search.substr(1).match(reg);
	    if(r!=null)return  unescape(r[2]); return null;
	},
	/**
	 *	方法: myFns.getUrlData()
	 *	功能: 获取来自什么页面
	 *	返回: <string> URL 对应的键值
	 */
	getUrlData: function(){
		var from = myFns.getUriString('from');
    	var id = myFns.getUriString('id');

	    if (from == 'myposts') {
	    	from = 1;
	    	$('.bbs_title').html('我的发帖');
	    } else if(from == 'myreplys'){
	    	from = 2;
	    	$('.bbs_title').html('我的回帖');
	    } else if(from == 'mylikes'){
	    	from = 3;
	    	$('.bbs_title').html('我的点赞');
	    } else if(from == 'mycollects'){
	    	from = 4;
	    	$('.bbs_title').html('我的收藏');
	    } else if(from == 'forumlist'){
	    	from = 'forumlist'+'&id='+id;
	    }
	    return from;
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

    froms = myFns.getUrlData();
    getJsonData('m.php?app=news&func=bbs&action=bbs&task=getMyPosts&from='+froms);

    //搜索帖子
	$(document).on('search','#search',function(){
		var title = $('#search').val(); //帖子标题
		var url = $('#wrapper').attr('data-url');
		$.ajax({
			dataType: "json",
			type: "post",
			url: url,
			data:{
				"title":title,
				"from": from,
				'id':id
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

	// 跳转至我的发帖信息页面
    $(document).on('touchstart','#btnBack',function(){
    	if (isNaN(froms)) {
			window.location.href = 'm.php?app=news&func=bbs&action=bbs&task=loadPage&from=forum';
    	} else {
    		window.location.href = 'm.php?app=news&func=bbs&action=bbs&task=loadPage&from=my';
    	}
    	return false;
    })

    // 跳转帖子正文
    $(document).on('click','.post_info',function(){
    	var pid = $(this).attr('data-pid');
    	window.location.href = 'm.php?app=news&func=bbs&action=bbs&task=loadPage&from=postinfo&pid='+pid+'&type='+froms;
    	return false;
    })
})
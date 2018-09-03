//全局变量
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
		var limit = 12; //每次取12条
		var start = (pageNow-1) * limit;//设置开始取数据
		var search = $('#search').val(); //流程标题`编号
		var url = "m.php?app=communication&func=message&action=index&task=getMailFolder";
		var data = {'start':start, 'limit':limit, 'search':search};
		$.post(url, data, function(json){
			myFns.all_flow(json,false);
		},'json');
	},
	//刷新界面
	refreshFns: function(){
		var search = $('#search').val(); //流程标题`编号
		var limit = 12; //每次取15条
		var start = 0;//设置开始取数据
		var url = "m.php?app=communication&func=message&action=index&task=getMailFolder";
		$.post(url,{'start':start,'limit':limit,'search':search}, function(json){
			myFns.all_flow(json,true);
		},'json')
	},
	all_flow: function(json,isEmpty){
		if(isEmpty){ //不是上拉加载就清空数据区
			$("#row").empty();
		}
		if(json.success && json.data.length > 0){
			if(json.data.length < 12){ //有超过一页的数据再显示滚动加载提示
				$("#pullUp").css("display","none");
	      		$("#scroller .stop-label").html("已加载完成，不要再滚动了");
	      		$("#scroller .stop-label").css("display","none");
			}else{
				$("#pullUp").css("display","block");
				$("#scroller .stop-label").css("display","none");
			}
			$.each(json.data,function(index,array){
				var str = '<li class="list-group-item">';
					str+= '<input type="hidden" value="'+array['fid']+'">';
					str+= '<span class="left">'+array['name']+'</span>';
					str+= '</li>';
				$("#row").append(str);
			})
			pageNow += 1;
		}else{
	    	$("#pullUp").css("display","none");
	    	$("#scroller .stop-label").css("display","block");
		}
		myScroll.refresh();
	}
}

$(function(){
	function getJsonData(url){
		search = $("#search").val();
		var limit = 12; //每次取12条
		var start = 0;//设置开始取数据
		$.ajax({
			url: url,
			dataType: 'json',
			method: 'post',
			data: {'start':start,'limit':limit,"search": search},
			success:function(json){
				myFns.all_flow(json,false);
			}
		})
	}
	getJsonData("m.php?app=communication&func=message&action=index&task=getMailFolder");

	$(document).on('touchstart','#btnBack',function(){
		history.go(-1);
	});

	//点击文件夹
	$(document).on('touchstart','.left',function(){
		var folderId = $(this).prev().val();
		var foldName = $(this).text();
		var fids = $('#fid').val();
		$.ajax({
			url: "m.php?app=communication&func=message&action=index&task=removeFolder",
			dataType: 'json',
			method: 'post',
			data: {folderId:folderId,fids:fids},
			success:function(){
				// swal("操作成功!", '已将邮件移到"'+foldName+'"中' , "success"); 
				jSuccess("操作成功!已将邮件移到'"+foldName+"'中",{
				    autoHide: true,                // 是否自动隐藏提示条 
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
				    	window.location.href="m.php?app=communication&func=message&action=index&task=loadPage&from=acceptEmail";
				    }
				});
			}
		})
	})
})

var pageNow = 1;
//全局变量`函数
var myFns = {
	loaded:function(element){
		myScroll = new iScroll(element, {
			scrollbarClass: 'myScrollbar',
			wheelAction: 'scroll'
		});
		setTimeout(function () { document.getElementById(element).style.left = '0'; }, 800);
		document.addEventListener('touchmove', function (e) { e.preventDefault(); }, false);
	},
	//刷新界面
	refreshFns: function(){
		var lid = myFns.getUriString('id');
		var url = "m.php?app=news&func=notice&action=index&task=getReaderData";
		$.post(url,{'start':0, 'lid':lid}, function(json){
			myFns.showView(json,true);
		},'json')
	},
	showView: function(json,isEmpty){
		if(isEmpty){ //不是上拉加载就清空数据区
			$("#main").empty();
		}
		if(json.success && json.data.length > 0){
			if(json.data.length < 15){ //有超过一页的数据再显示滚动加载提示
				$("#pullUp").css("display","none");
	      		$("#scroller .stop-label").html("已加载完成，不要再滚动了");
	      		$("#scroller .stop-label").css("display","block");
			}else{
				$("#pullUp").css("display","block");
				$("#scroller .stop-label").css("display","none");
			}
			pageNow += 1;
			var html = "",length = 0;
			length = json.data.length;
			for(var i = 0; i < length; i++){
				html += '<div class="reader_list" style="width:100%; border-top:1px solid #dbdcdc; border-bottom:1px solid #dbdcdc;padding:10px 20px;margin-top:-1px;float:left;>';
				html += '<div class="reader_box" style="width:80%; margin:0 auto;">';
				html += '<div class="reader_left" style="float:left; width:60%;">';
				html += '<div class="reader_name" style="font-size:16px;">'+json.data[i].name+'</div>';
				html += '<div class="reader_dept" style="color:#B5B2B2;font-size:14px;">'+json.data[i].jid+'</div>';
				html += '</div>';
				html += '<div class="reader_time" style="float:left; width:40%;">'+json.data[i].viewtime+'</div>';
				html += '</div>';
				html += '<div class="clear" style="clear:both;"></div>';
				html += '</div>';
			}
			$('#main').append(html);
		}
		myScroll.refresh();
	},
	getUriString: function(key){
		var reg = new RegExp("(^|&)"+ key +"=([^&]*)(&|$)");
    	var r = window.location.search.substr(1).match(reg);
    	if(r!=null)return  unescape(r[2]); return null;
	},
	//下拉刷新
	pull_down_refresh: function(){
		myFns.refreshFns(); //刷新界面
		pageNow = 1; //重置当前页为1
	},
	//上拉加载
	pullUpAction: function(){
		var lid = myFns.getUriString('id');
		var limit = 15; //每次取15条
		var start = (pageNow-1) * limit;//设置开始取数据
		var url = "m.php?app=news&func=notice&action=index&task=getReaderData";
		var data = {'start':start, 'lid':lid};
		$.post(url, data, function(json){
			myFns.showView(json,false);
		},'json');
	}
}

$(function(){
	myFns.loaded('wrapper');
	var lid = myFns.getUriString('id');
	//初始化加载数据
	function getJsonData(url){
		var start = pageNow - 1;//设置开始取数
		$.ajax({
			url: url,
			dataType: 'json',
			method: 'post',
			data:{start:start,lid:lid},
			success:function(json){
				myFns.showView(json,true);
			}
		})
	}
	getJsonData("m.php?app=news&func=notice&action=index&task=getReaderData");

	


	//返回上一页
	$(document).on('touchstart','#btnBack',function(){
		//获取id
		var id = myFns.getUriString('id');
		window.location.href ='m.php?app=news&func=notice&action=index&task=loadPage&from=view&id='+id;
	});
})
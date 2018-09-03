
//全局变量
var pageNow = 1;
var sid =0;
var start = 0;
var arr = [];//存储top 3
var myScroll,myScroll1;
String.prototype.replaceAll = function(reallyDo, replaceWith, ignoreCase) {  
   if (!RegExp.prototype.isPrototypeOf(reallyDo)) {  
       return this.replace(new RegExp(reallyDo, (ignoreCase ? "gi": "g")), replaceWith);  
   } else {  
       return this.replace(reallyDo, replaceWith);  
   }  
}
//全局变量`函数
var myFns = {
	loaded:function(){
		myScroll = new iScroll('wrapper', {
			hScroll: true,
			vScroll: false,
			hScrollbar: false ,
			vScrollbar: false
		});
		document.addEventListener('touchmove', function (e) { e.preventDefault(); }, false);
	},
	loaded1:function(){
		myScroll1 = new iScroll('Vwrapper', {
			hScroll: false,
			vScroll: true,
			hScrollbar: false ,
			vScrollbar: false
		});	
		document.addEventListener('touchmove', function (e) { e.preventDefault(); }, false);
	},
	//下拉刷新
	pull_down_refresh: function(){		
		myFns.refreshFns(); //刷新界面
		pageNow = 1; //重置当前页为1
	},
	//刷新界面
	refreshFns: function(){
		var search = $('#search').val(); //流程标题`编号
		var start = 0;//设置开始取数据
		var url = "m.php?app=news&func=news&action=read&task=getJsonData";
		$.post(url,{'start':start,sort:sid,'title':search}, function(json){
			myFns.showView(json,true);
		},'json')
	},
	//上拉加载
	pullUpAction:function(){
		var start = pageNow-1;//设置开始取数据
		var search = $('#search').val(); //流程标题`编号
		var url = "m.php?app=news&func=news&action=read&task=getJsonData";
		var data = {'start':start,sort:sid,'title':search};
		$.post(url, data, function(json){
			myFns.showView(json,false);
		},'json');
	},
	showView:function(json,isEmpty){
		if(isEmpty){
			$("#content").empty();
		}
		if(json.success && json.data.length > 0){
			if(json.data.length < 15){ //有超过一页的数据再显示滚动加载提示
				$("#pullUp").css("display","none");
	      		$("#Vscroller .stop-label").css("display","none");
			}else{
				$("#pullUp").css("display","block");
				$("#Vscroller .stop-label").css("display","none");
			}
			$.each(json.data,function(index,array){
				str = '';
				str+= '<div class="main" data-lid ='+array['lid']+'>';
				str+= '<div class="showimg">';
				if(array['image']!=''){
					str+= "<img src='"+array['image']+"'/></div>";	
				}else{
					str+= '<img src="resources/images/m/news/news.jpg"/></div>';            					
				}          
				str+= '<div class="showcon">';          
				str+= '<p class="title">'+array['title']+'</p>';            
				str+= '<span class="time">'+array['posttime']+'</span></div></div>';            
				$("#content").append(str);
			})
			pageNow +=1;
		}else{
	    	$("#pullUp").css("display","none");
	    	$("#Vscroller .stop-label").css("display","block");
		}
		myScroll1.refresh();
		myScroll.refresh();
	},
	showFocus:function(json){
		var data = json.data;
		if(json.success == true && data.length>0){
			$.each(data,function(index,array){
				var str = '';
				str+= '<div class="swiper-slide" data-lid="'+array['lid']+'"><img src="'+array['image']+'"></div>';
				$('.swiper-wrapper').append(str);
				arr.push(array['title']);
			});
		}
	}
}

$(function(){
	myFns.loaded();
	myFns.loaded1();
	//滑动slide
	var mySwiper = new Swiper ('.swiper-container', {
	    direction: 'horizontal',
	    loop: true,
	    autoplay : 1500,
	    a11y: false,
	    autoplayDisableOnInteraction : false,
	    // 如果需要分页器
	    pagination: '.swiper-pagination',
	    onSlideChangeEnd:function(swiper){
	    	var index = swiper.activeIndex;
	    	if(index == 4){$('.swiper-title').text(arr[0]);}
	    	if(index == 2){$('.swiper-title').text(arr[1]);}
	    	if(index == 3){$('.swiper-title').text(arr[2]);}
	    }
	});        
	//初始化加载栏目分类
	$.ajax({
		type: "POST",
		url: "index.php?app=news&func=news&action=read&task=getSortList&type=tree",
		dataType: "json",
		success: function(json){
			var html='<li class="active"><a class="noRadius" data-toggle="tab" data-sid="0">首页</a></li>';
			for(var i = 0; i < json.length; i++){
				html += '<li><a class="noRadius" data-toggle="tab" data-sid="'+json[i].sid+'">' + json[i].name + '</a></li>';
			}
			$(".nav").append(html);
			var width = 0;
			$('.nav li').each(function(){
				width += $(this).width();
			});
			$("#scroller").width(width+5);
			setTimeout(function(){
				myScroll.refresh();
			},200);
			getFocus();
			getList(0,0);
		}
	});
	function getFocus(){
		$.ajax({
			type:'post',
			dataType:'json',
			url:'m.php?app=news&func=news&action=read&task=getHitNews',
			data:{sort:sid,start:start},
			success:function(json){
				myFns.showFocus(json);
				mySwiper.reLoop();
				mySwiper.updatePagination();
				mySwiper.onResize();
			}
		});
	}
	function getList(sid,start){
		$.ajax({
			type:'post',
			dataType:'json',
			url:'m.php?app=news&func=news&action=read&task=getJsonData',
			data:{sort:sid,start:start},
			success:function(json){
				myFns.showView(json,true);
			}
		});
	}

	//点击切换
	$(document).on('click','.noRadius',function(){
		sid = $(this).attr('data-sid');
		getList(sid,0);
	});
	//点击跳到新闻详情页面
	$(document).on('click','.main',function(){
		var lid = $(this).attr('data-lid');
		$.ajax({
			type:'post',
			dataType:'json',
			url:'m.php?app=news&func=news&action=read&task=change',
			data:{lid:lid},
			success:function(json){
				if(json.success){
					window.location.href="m.php?app=news&func=news&action=read&task=getReadContent&lid="+lid;
				}
			}
		});
		// window.location.href="m.php?app=news&func=news&action=read&task=getReadContent&lid="+lid;
	});
	//搜索
	$(document).on('touchstart','#btnAdd',function(){
		$('#myModal').modal('show');
		return false;
	});
	$(document).on('keyup','#search',function(){
		myScroll1.refresh();
		showLoading();
		var title = $(this).val();
		$.ajax({
			type:'post',
			dataType:'json',
			url:'m.php?app=news&func=news&action=read&task=getJsonData',
			data:{sort:sid,start:start,title:title},
			success:function(json){
				hideLoading();
				myFns.showView(json,true);
				$('#myModal').modal('hide');
			}
		});
	});
	//点击焦点图
	$(document).on('click','.swiper-slide',function(){
		var lid = $(this).attr('data-lid');
		window.location.href="m.php?app=news&func=news&action=read&task=getReadContent&lid="+lid;
	});

	//返回
	$(document).on('touchstart','#btnBack',function(){
		if(/android/ig.test(navigator.userAgent)){
			window.javaInterface.returnToMain();
		}else{
			window.location.href = 'js://pop_view_controller';
		}
		return false;
	});
})
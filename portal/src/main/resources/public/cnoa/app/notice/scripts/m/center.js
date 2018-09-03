var pageNow = 1;
var toggle = 1;
//全局变量`函数
var myFns = {
	//下拉刷新
	pull_down_refresh: function(){
		if(toggle == 2){
			$('#pullDown').css('display','none');
			return false;
		}		
		myFns.refreshFns(); //刷新界面
		pageNow = 1; //重置当前页为1
	},
	//上拉加载
	pullUpAction: function(){
		var limit = 15; //每次取15条
		var start = (pageNow-1) * limit;//设置开始取数据
		var search = $('#search').val(); //流程标题`编号
		if(toggle == 2){
			$('#pullDown').css('display','none');
			return false;
		}
		var url = "m.php?app=notice&func=notice&action=todo&task=getData";
		var data = {'start':start, 'search':search};
		$.post(url, data, function(json){
			myFns.showView(json,false);
		},'json');
	},
	//刷新界面
	refreshFns: function(){
		var search = $('#search').val(); //流程标题`编号
		var start = 0;//设置开始取数据
		var url = "m.php?app=notice&func=notice&action=todo&task=getData";
		$.post(url,{'start':start,'search':search}, function(json){
			myFns.showView(json,true);
		},'json')
	},
	showView: function(json,isEmpty){
		if(isEmpty){ //不是上拉加载就清空数据区
			$("#main").empty();
		}
		if(json.success && json.data.length > 0){
			if(json.data.length < 12){ //有超过一页的数据再显示滚动加载提示
				$("#pullUp").css("display","none");
	      		// $("#scroller .stop-label").html("已加载完成，不要再滚动了");
	      		$("#scroller .stop-label").css("display","none");
			}else{
				$("#pullUp").css("display","block");
				$("#scroller .stop-label").css("display","none");
			}
			var mid = myFns.getUriString('mid');
			$.each(json.data,function(index,array){
				if(!array['href']){
					array['href'] = "";
				}
				var str = '';
					if(toggle == 1){
						if(array['href'] !=''){
							str+= '<div class="contain"><div id="tt">';
							str+= '<div id="pic"><div id="img"><img src="'+array['picUrl']+'"></div></div></div>';
							str+= '<div class="right" type="right">';
							str+= '<div class="cont"><input type="hidden" link="'+array['href']+'" value="'+array['id']+'"><span class="title1">';
							str+= array['content'];
							str+= '</span>';
							str+= '<p class="time">'+array['noticetime']+'</p></div>';
							str+= '<div class="opt" style="display:none;">';
							str+= '<span class="checkinfo" type="check">查看</span>';
							str+= '<span class="delete">删除</span>';
							str+= '</div>'
							str+= '</div><div class="line1"></div></div></div>';
						}else{
							str+= '';
						}
					}else{
						str+= '<div class="contain"><div id="tt">';
						str+= '<div id="pic"><div id="img"><img src="'+array['picUrl']+'"></div></div></div>';
						str+= '<div id="rightD">';
						str+= '<div class="cont"><input type="hidden" mid="'+array['mid']+'" link="'+array['href']+'" value="'+array['mid']+'"><span class="title2">';
						str+= array['content'];
						str+= '</span>';
						str+= '<p class="time">'+array['noticetime']+'</p></div>'
						str+= '<span class="glyphicon glyphicon-chevron-right rightT"></span></div>';
						str+= '<div class="line1"></div></div></div>';
					}
					
				$("#main").append(str);
				var sort_list = $('#main').find('.right').last();
				sort_list.swipe({
			        swipe: function(event, direction, distance, duration, fingerCount){   //参数:事件、方向、距离、持续事件、手指总数
			        	if (direction == 'right') {							//右滑动
			        		$(this).find('.cont').css('display','block');	
			        		$(this).find('.opt').css('display','none');			
			        	} else if (direction == 'left') {					//左滑动
			        		$('.right .cont').css('display','block');	
			        		$('.right .opt').css('display','none');			
			        		$(this).find('.cont').css('display','none');	
			        		$(this).find('.opt').css('display','block');		
			        	}
			        }
			    })
			})
			if(toggle == 2){
				$('img').css('border-radius','0');
				$('.cont').css('margin-top','5px');
				//内容
			}else{
				$('img').css('border-radius','50%');
				$('.cont').css('margin-top','10px');
			}
			pageNow += 1;
		}else{
	    	$("#pullUp").css("display","none");
	    	$("#scroller .stop-label").css("display","block");
		}
		myScroll.refresh();
	},
	getUri:function(href , key){
		var reg = new RegExp("(^|&)"+ key +"=([^&]*)(&|$)");
		href = href.split('?');
    	var r = href[1].match(reg);
    	if(r!=null)return  unescape(r[2]); return null;
	},
	getUriString: function(key){
		var reg = new RegExp("(^|&)"+ key +"=([^&]*)(&|$)");
	    var r = window.location.search.substr(1).match(reg);
	    if(r!=null)return  unescape(r[2]); return null;
	}
}

$(function(){

	$(".opt").attr('disabled',true);

	//默认选中最新状态
	$('.btn_not_email').css({
		'background-color':'white',
		'color': '#4A78BB'
	});
	var test ='';
	function getJsonData(url){
		var search = $("#search").val();
		var start = 0;//设置开始取数
		toggle =1;
		$.ajax({
			url: url,
			dataType: 'json',
			method: 'post',
			data:{start:start},
			success:function(json){
				myFns.showView(json,true);
			}
		})
	}
	getJsonData("m.php?app=notice&func=notice&action=todo&task=getData");

	//待办
	$(document).on('click','.btn_have_email',function(){
		$('.btn_not_email').removeAttr('style','');
		$('.btn_have_email').css({
			'background-color':'white',
			'color': '#4A78BB'
		});
		toggle =2;
		search = $("#search").val();
		var start = 0;//设置开始取数据
		isread = 1;
		$.ajax({
			url: "m.php?app=notice&func=notice&action=todo&task=getMenuList",
			dataType: 'json',
			method: 'post',
			data: {'start':start,"search": search},
			success:function(json){
				myFns.showView(json,true);
			}
		});
		test ='have';
	});
	//最新
	$(document).on('click','.btn_not_email',function(){
		$('.btn_have_email').removeAttr('style','');
		$('.btn_not_email').css({
			'background-color':'white',
			'color': '#4A78BB'
		});
		toggle = 1;
		test = 'not';
		getJsonData("m.php?app=notice&func=notice&action=todo&task=getData");
	});
	//点击跳转链接--判断权限
	$(document).on('click','.title1,.checkinfo',function(){
		var type = $(this).attr('type');
		var href = '';
		var id = '';
		if(type == 'check'){
			href = $(this).parents('.opt').prev().find('input[type="hidden"]').attr('link');
			id = $(this).parents('.opt').prev().find('input[type="hidden"]').val();
		}else{
			href = $(this).prev().attr('link');
			id = $(this).prev().val();
		}
		var app = myFns.getUri(href,'app');
		if(app == 'odoc'){
			app ='meeting';
		}
		var func= myFns.getUri(href,'func');
		if(func == 'read' && app =='meeting'){
			func ='mgr';
		}
		var action= myFns.getUri(href,'action');
		//可能需要改成像system-menu/permit
		$.ajax({
			url: 'm.php?app=notice&func=notice&action=todo&task=checkPermit',
			dataType: 'json',
			method: 'post',
			data:{id:id,device:'weixin',app:app,func:func,action:action},
			success:function(json){
				if(json.failure == true){
					jNotify(json.msg,{
						autoHide : true,                // 是否自动隐藏提示条 
						clickOverlay : false,            // 是否单击遮罩层才关闭提示条 
						MinWidth : 20,                    // 最小宽度 
						TimeShown : 2500,                 // 显示时间：毫秒 
						ShowTimeEffect : 200,             // 显示到页面上所需时间：毫秒 
						HideTimeEffect : 200,             // 从页面上消失所需时间：毫秒 
						LongTrip : 15,                    // 当提示条显示和隐藏时的位移 
						HorizontalPosition : "center",     // 水平位置:left, center, right 
						VerticalPosition : "center",     // 垂直位置：top, center, bottom 
						ShowOverlay : false,                // 是否显示遮罩层 
						ColorOverlay : "#000",            // 设置遮罩层的颜色 
						OpacityOverlay : 0.3            // 设置遮罩层的透明度 
					});
				}else{
					$.ajax({
						url: 'm.php?app=notice&func=notice&action=todo&task=changeState',
						dataType: 'json',
						method: 'post',
						data:{id:id},
						success:function(json){
							window.location.href = href;																									
						}
					});
					
				}																		
			}
		});
	});
	// 点击待办中菜单
	$(document).on('click','#rightD',function(){
		var href = $(this).children('.cont').find('input[type="hidden"]').attr('link');
		var app = myFns.getUri(href,'app');
		var func= myFns.getUri(href,'func');
		var action= myFns.getUri(href,'action');
		var mid = $(this).find('.cont').find('input[type="hidden"]').val();	
		$.ajax({
			url: 'm.php?app=notice&func=notice&action=todo&task=checkPermit',
			dataType: 'json',
			method: 'post',
			data:{device:'weixin',app:app,func:func,action:action},
			success:function(json){
				if(json.failure == true){
					jNotify(json.msg,{
						autoHide : true,                // 是否自动隐藏提示条 
						clickOverlay : false,            // 是否单击遮罩层才关闭提示条 
						MinWidth : 20,                    // 最小宽度 
						TimeShown : 2500,                 // 显示时间：毫秒 
						ShowTimeEffect : 200,             // 显示到页面上所需时间：毫秒 
						HideTimeEffect : 200,             // 从页面上消失所需时间：毫秒 
						LongTrip : 15,                    // 当提示条显示和隐藏时的位移 
						HorizontalPosition : "center",     // 水平位置:left, center, right 
						VerticalPosition : "center",     // 垂直位置：top, center, bottom 
						ShowOverlay : false,                // 是否显示遮罩层 
						ColorOverlay : "#000",            // 设置遮罩层的颜色 
						OpacityOverlay : 0.3            // 设置遮罩层的透明度 
					});
				}else{
					if(mid == '7'){
						window.location.href = 'm.php?app=news&func=notice&action=index&task=loadPage&from=list';
					}else if(mid == '27'){
						window.location.href = 'm.php?app=communication&func=message&action=index&task=loadPage&from=acceptEmail';
					}else{
						window.location.href = 'm.php?app=notice&func=notice&action=todo&task=loadPage&from=check&mid='+mid;
					}
				}																		
			}
		});
	});
	//删除
	$(document).on('touchstart','.delete',function(){
		var id = $(this).parents('.opt').prev().find('input[type="hidden"]').val();
		$.confirm({
			title: '提示',
			content: '是否要删除此提醒？',
			animation: "top",
			cancelButtonClass: 'btn-danger',
			confirmButton: '确定',
			cancelButton: '取消',
			confirm: function(){
				$.ajax({
					url: 'm.php?app=notice&func=notice&action=todo&task=deleteNotice',
					dataType: 'json',
					method: 'post',
					data:{id:id},
					success:function(json){
						window.location.href="m.php?app=notice&func=notice&action=todo&task=loadPage";
					}
				});
			}
		});
	});
	//搜索
	$(document).on('search','#search',function(){
		if(toggle == 2){
			return false;
		}
		var search = $('#search').val();
		start = 0;
		$.ajax({
			url: 'm.php?app=notice&func=notice&action=todo&task=getData',
			dataType: 'json',
			method: 'post',
			data:{start:start,search:search},
			success:function(json){
				myFns.showView(json,true);
			}
		})
	});
	//返回上一页
	$(document).on('touchstart','#btnBack',function(){
		if(/android/ig.test(navigator.userAgent)){
			window.javaInterface.returnToMain();
		}else{
			window.location.href = 'js://pop_view_controller';
		}
		return false;
	});
})
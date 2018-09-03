var pageNow = 1;
var isread = 0; //已读/未读
//全局变量`函数
var myFns = {
	//下拉刷新
	pull_down_refresh: function(){		
		myFns.refreshFns(); //刷新界面
		pageNow = 1; //重置当前页为1
	},
	//上拉加载
	pullUpAction: function(){
		var limit = 12; //每次取15条
		var start = (pageNow-1) * limit;//设置开始取数据
		var search = $('#search').val(); //流程标题`编号
		var url = "m.php?app=communication&func=message&action=index&task=getMail";
		var data = {'start':start, 'limit':limit, 'search':search,'isread':isread};
		$.post(url, data, function(json){
			myFns.showView(json,false);
		},'json');
	},
	//刷新界面
	refreshFns: function(){
		var search = $('#search').val(); //流程标题`编号
		var limit = 12; //每次取15条
		var start = 0;//设置开始取数据
		var url = "m.php?app=communication&func=message&action=index&task=getMail";
		$.post(url,{'start':start,'limit':limit,'search':search,'isread':isread}, function(json){
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
			$.each(json.data,function(index,array){
				var str = '<div class="contain"><div id="tt"><div class="check" id="check"><input type="checkbox" id="one"/><input type="hidden" value="'+array['id']+'"></div>';
					str+= '<div id="pic"><div id="img"><img src="'+array['face']+'"></div><span class="name">'+array['truename']+'</span></div></div>';
					str+= '<div id="right"><span class="shaft"></span>';
					
					str+= '<span class="glyphicon glyphicon-chevron-right btnGO" aria-hidden="true"></span>';
					str+= '<div class="cont"><input type="hidden" value="'+array['id']+'"><span class="title">';
					str+= array['title'];
					//判断是否有附件
					if(array['attach']!=0){
						// str+='<span class="glyphicon glyphicon-paperclip fs"></span>';
						str+='<img class="attach" src="resources/images/email_icon/038.png">';
					}else{
						str+='<span></span>';
					}
					str+='</span>';
					str+='<p class="time">'+array['posttime']+'&nbsp;&nbsp;'+array['truename']+'</p></div>';
					str+= '</div><div class="line1"></div></div></div>';
				$("#main").append(str);
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

	$(".opt").attr('disabled',true);

	$(document).on('touchstart','#btnAdd',function(){
		window.location.href='m.php?app=communication&func=message&action=index&task=loadPage&from=writeEmail';
	});

	//默认选中未读邮件
	$('.btn_not_email').css({
		'background-color':'white',
		'color': '#4A78BB'
	});

	function getJsonData(url){
		search = $("#search").val();
		var limit = 12; //每次取12条
		var start = 0;//设置开始取数据
		isread = 0;
		$.ajax({
			url: url,
			dataType: 'json',
			method: 'post',
			data: {'start':start,'limit':limit,"search": search,"isread":isread},
			beforeSend: function(){
				//提交表单前验证
				showLoading("正在加载，请稍等...");
			},
			success:function(json){
				hideLoading();
				myFns.showView(json,true);
			}
		})
	}
	getJsonData("m.php?app=communication&func=message&action=index&task=getMail");

	//已读
	$(document).on('click','.btn_have_email',function(){
		$('.btn_not_email').removeAttr('style','');
		$('.btn_have_email').css({
			'background-color':'white',
			'color': '#4A78BB'
		});
		search = $("#search").val();
		var limit = 12; //每次取12条
		var start = 0;//设置开始取数据
		isread = 1;
		$.ajax({
			url: "m.php?app=communication&func=message&action=index&task=getMail",
			dataType: 'json',
			method: 'post',
			data: {'start':start,'limit':limit,"search": search,"isread":isread},
			beforeSend: function(){
				//提交表单前验证
				showLoading("正在加载，请稍等...");
			},
			success:function(json){
				hideLoading();
				myFns.showView(json,true);
			}
		})
	});
	//未读
	$(document).on('click','.btn_not_email',function(){
		$('.btn_have_email').removeAttr('style','');
		$('.btn_not_email').css({
			'background-color':'white',
			'color': '#4A78BB'
		});
		getJsonData("m.php?app=communication&func=message&action=index&task=getMail");
	});

	//标为已读
	$(document).on('click','.hasread',function(){
		
		var fids=[];
		var check = $("input[type='checkbox']:checked").length;
		for(var i = 0;i < check;i++){
			fids.push($("input[type='checkbox']:checked:eq("+i+")").parent().children("input[type='hidden']").val());
		}
		$.ajax({
			url: "m.php?app=communication&func=message&action=index&task=markRead",
			dataType: 'json',
			method: 'post',
			data: {'fids':fids},
			success:function(json){
				jSuccess('已成功标为已读',{
				     autoHide : true,                // 是否自动隐藏提示条 
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
				     	window.location.href = 'm.php?app=communication&func=message&action=index&task=loadPage&from=acceptEmail';
				     }
				});	
			}
		})
	});

	//删除
	$(document).on('click','.delete',function(){
		if($('#main').find('.title').length == 0){
			jNotify('没有选中任何项!',{
			     autoHide : true,                // 是否自动隐藏提示条 
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
			     OpacityOverlay : 0.3            // 设置遮罩层的透明度 
			});
			return false;
		}
		var fids=[];
		var check = $("input[type='checkbox']:checked").length;
		for(var i = 0;i < check;i++){
			fids.push($("input[type='checkbox']:checked:eq("+i+")").parent().children("input[type='hidden']").val());
		}
		swal({ 
		    title: "您确定要删除吗？", 
		    type: "warning", 
		    showCancelButton: true, 
		    closeOnConfirm: false, 
		    confirmButtonText: "删除", 
		    confirmButtonColor: "#ec6c62" 
		},function(){
			$.ajax({
				url: "m.php?app=communication&func=message&action=index&task=delteMail",
				dataType: 'json',
				method: 'post',
				data: {'fids':fids},
				success:function(json){
					window.location.href = 'm.php?app=communication&func=message&action=index&task=loadPage&from=acceptEmail';
				}
			})
		});
	});

	//移动
	$(document).on('click','.move',function(){
		var fids=[];
		var check = $("input[type='checkbox']:checked").length;
		for(var i = 0;i < check;i++){
			fids.push($("input[type='checkbox']:checked:eq("+i+")").parent().children("input[type='hidden']").val());
		}
		window.location.href="m.php?app=communication&func=message&action=index&task=loadPage&from=remove&fids="+fids;
	});

	//跳到查看邮件页面
	$(document).on('click','#right',function(){
		var fids = [];
		var fid = $(this).children('.cont').find("input[type='hidden']").val();
		fids.push(fid);
		if(isread ==0){
			$.ajax({
				dataType: "json",
				type: "post",
				url: 'm.php?app=communication&func=message&action=index&task=markRead',
				data:{
					"fids":fids
				},
				success:function(json){
					window.location.href="m.php?app=communication&func=message&action=index&task=loadPage&from=checkEmail&fids="+fid+'&type=accept';
				}
			});
		}else{
			window.location.href="m.php?app=communication&func=message&action=index&task=loadPage&from=checkEmail&fids="+fid+'&type=accept';
		}
		
	});

	$(document).on('click','#all',function(){
		if(this.checked){
			$("input[type='checkbox']").each(function(){this.checked=true;});
			$(".opt").removeClass('btn btn-default');
			$(".opt").addClass('btn btn-primary');
			$(".opt").attr('disabled',false);
			if(isread == '1'){
				$(".hasread").removeClass('btn btn-primary');
				$(".hasread").addClass('btn btn-default');
				$(".hasread").attr('disabled',true);
			}
		}else{
			$("input[type='checkbox']").each(function(){this.checked=false;});
			$(".opt").removeClass('btn btn-primary');
			$(".opt").addClass('btn btn-default');
			$(".opt").attr('disabled',true);			
		}
	});
	//点击checkbox
	$(document).on('click',"input[type='checkbox']",function(){
		if(this.checked){
			$(".opt").removeClass('btn btn-default');
			$(".opt").addClass('btn btn-primary');
			$(".opt").attr('disabled',false);
			if(isread == '1'){
				$(".hasread").removeClass('btn btn-primary');
				$(".hasread").addClass('btn btn-default');
				$(".hasread").attr('disabled',true);
			}
		}else{
			if(!$("input[type='checkbox']").is(':checked')){

				$(".opt").removeClass('btn btn-primary');
				$(".opt").addClass('btn btn-default');
				$(".opt").attr('disabled',true);
			}
		}
	});
	$(document).on('touchstart','.check',function(){
		if($(this).find("input[type='checkbox']").is(':checked')){
			$(this).find("input[type='checkbox']").prop('checked',false);
			if(!$("input[type='checkbox']").is(':checked')){
				$(".opt").removeClass('btn btn-primary');
				$(".opt").addClass('btn btn-default');
				$(".opt").attr('disabled',true);
			}
			return false;
		}else{
			$(this).find("input[type='checkbox']").prop('checked',true);
			$(".opt").removeClass('btn btn-default');
			$(".opt").addClass('btn btn-primary');
			$(".opt").attr('disabled',false);
			if(isread == '1'){
				$(".hasread").removeClass('btn btn-primary');
				$(".hasread").addClass('btn btn-default');
				$(".hasread").attr('disabled',true);
			}
			return false;
		}
	});

	//搜索
	$(document).on('search','#search',function(){
		var search = $('#search').val(); //流程标题`编号
		var limit = 12;
		var start =0;
		var url = "m.php?app=communication&func=message&action=index&task=getMail";
		$.ajax({
			dataType: "json",
			type: "post",
			url: url,
			data:{
				"search":search,
				"start":start,
				"limit":limit,
				"isread":isread
			},
			success:function(json){
				if(json.data.length==0){
					jNotify("未检索到数据!",{
						autoHide : true,                // 是否自动隐藏提示条 
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
						OpacityOverlay : 0.3            // 设置遮罩层的透明度 
					});
					hideLoading();
					return false;
				}
				myFns.showView(json,true);
			},

			complete: function(XMLHttpRequest, textStatus){
				$(".ui-ios-overlay").removeClass("ios-overlay-show").addClass("ios-overlay-hide");
			}
		})
		pageNow = 1; //重置当前页为1
	});

	//返回上一页
	$(document).on('touchstart','#btnBack',function(){
		window.location.href='m.php?app=communication&func=message&action=index&task=loadPage&from=emailIndex';
	});
})
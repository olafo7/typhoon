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
		var limit = 12; //每次取15条
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
	      		// $("#scroller .stop-label").html("已加载完成，不要再滚动了");
	      		$("#scroller .stop-label").css("display","none");
			}else{
				$("#pullUp").css("display","block");
				$("#scroller .stop-label").css("display","none");
			}
			$.each(json.data,function(index,array){
				var str = '<li class="list-group-item">';
					str+= '<span class="aa"><input type="checkbox"/></span>';
					str+= '<input type="hidden" value="'+array['fid']+'">';
					str+= '<span class="left" data-id="'+array['fid']+'">'+array['name']+'</span>';
					str+= '<span class="glyphicon glyphicon-chevron-right btnGO" aria-hidden="true"></span>';
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

	$(document).on('touchstart','.aa',function(){
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
			return false;
		}
	});

    $(".opt").attr('disabled',true);

	function getJsonData(url){
		search = $("#search").val();
		var limit = 12; //每次取12条
		var start = 0;//设置开始取数据
		$.ajax({
			url: url,
			dataType: 'json',
			method: 'post',
			data: {'start':start,'limit':limit,"search": search},

			beforeSend: function(){
				showLoading("正在加载，请稍等...");
			},
			success:function(json){
				hideLoading();
				myFns.all_flow(json,false);
			}
		})
	}
	getJsonData("m.php?app=communication&func=message&action=index&task=getMailFolder");

	$(document).on('click','#all',function(){
		if(this.checked){
			$("input[type='checkbox']").each(function(){this.checked=true;});
			$(".opt").removeClass('btn btn-default');
			$(".opt").addClass('btn btn-primary');
			$(".opt").attr('disabled',false);
		}else{
			$("input[type='checkbox']").each(function(){this.checked=false;});
			$(".opt").removeClass('btn btn-primary');
			$(".opt").addClass('btn btn-default');
			$(".opt").attr('disabled',true);			
		}
		if($('.left').text().length==0){
			$(".opt").removeClass('btn btn-primary');
			$(".opt").addClass('btn btn-default');
			$(".opt").attr('disabled',true);
		}
	});
	
	$(document).on('click','.left',function(){
		var name = $(this).text();
		var fid = $(this).attr('data-id');
		window.location.href=encodeURI(encodeURI('m.php?app=communication&func=message&action=index&task=loadPage&from=checkFolder&name='+name+'&fid='+fid));
	});
	//搜索????
	$(document).on('search','#search',function(){
		var search = $('#search').val(); //流程标题`编号
		var url = "m.php?app=communication&func=message&action=index&task=getMailFolder";
		$.ajax({
			dataType: "json",
			type: "post",
			url: url,
			data:{
				"search":search
			},
			success: function(json){
				if(!json.data){
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
					return false;
				}
				myFns.all_flow(json,true);
			},

			complete: function(XMLHttpRequest, textStatus){
				$(".ui-ios-overlay").removeClass("ios-overlay-show").addClass("ios-overlay-hide");
			}
		})
		pageNow = 1; //重置当前页为1
	});

	//点击删除按钮
	$(document).on('click','.delete',function(){
		var fids=[];
		var check = $("input[type='checkbox']:checked").length;
		for(var i = 0;i < check;i++){
			fids.push($("input[type='checkbox']:checked:eq("+i+")").parent().parent().children("input[type='hidden']").val());
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
				url: 'm.php?app=communication&func=message&action=index&task=deleteMailFolder',
				method: 'post',
				dataType: 'json',
				data: {fids:fids},
				success: function(json){
					if(json.success==true){
						window.location.href="m.php?app=communication&func=message&action=index&task=loadPage&from=mailfolder";
					}
				}
			})
		});
	});
	//点击修改按钮
	$(document).on('click','.update',function(){
		if($("input[type='checkbox']:checked").length>1){
			jNotify('只能选择一个文件夹!',{
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
		var text = $("input[type='checkbox']:checked").parent().parent().find('.left').text();
     	var hiddentext = $("input[type='checkbox']:checked").parent().next().val();
		$('#myModal').modal('show');
        $('#foldername').val(text);
        $('#fid').val(hiddentext);
	});
	//点击确认后修改文件夹
	$(document).on('click','.sure',function(){
		
		var name = $("#foldername").val();
		if(name.length==0){
			jNotify('文件夹名称不能为空!',{
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
		var fid = $("#fid").val();
		var type = '';
		if(fid.length>0){
			type ='update';
		}else{
			type ='add';
		}
		$.ajax({
			url: 'm.php?app=communication&func=message&action=index&task=addMailFolder',
			method: 'post',
			dataType: 'json',
			data: {name : name,type:type,fid:fid},
			success: function(json){
				var show ='';
				if(json.success==true){
					if(fid.length>0){
						show = '修改';
					}else{
						show = '添加';
					}
					jSuccess(show+'成功',{
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
				     	window.location.href="m.php?app=communication&func=message&action=index&task=loadPage&from=mailfolder";
				     }
					});
				}
			}
		})
	});


	$(document).on('touchstart','.cancel',function(){
		$('#myModal').modal('hide')
	});
	$(document).on('touchstart','#btnBack',function(){
		window.location.href='m.php?app=communication&func=message&action=index&task=loadPage&from=emailIndex';
	});
	//点击添加添加数据
	$(document).on('touchstart','#btnAdd',function(){
		$('#myModal').modal('show');
		return false;
	});
})

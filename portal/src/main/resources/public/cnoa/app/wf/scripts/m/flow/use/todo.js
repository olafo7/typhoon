//全局变量`
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
		var limit = 15; //每次取15条
		var start = (pageNow-1) * limit;//设置开始取数据
		var search = $('#search').val(); //流程标题`编号
		var url = $('#myTabs').attr('data-url'); //请求数据地址
		var data = {'start':start, 'limit':limit, 'search':search};
		$.post(url, data, function(json){
			var myTabs = Number($('#myTabs').attr('data-tab'));
			switch(myTabs){
				//所有流程
				case 0:
				  myFns.all_flow(json,false);
				  break;
				//待办流程
				case 1:
				  myFns.append_data(json,false);
				  break;
				//已办流程
				case 2:
				  myFns.done_data(json,false);
				  break;
				//我申请的
				case 3:
				  myFns.myFlowData(json,false);
				  break;
			}
		},'json');
	},
	/*
	 *ajax成功返回数据`追加内容`初始化·查询·刷新
	 *json：ajax请求成功返回的data
	 *isEmpty：是否清空数据区 true：清空 false：不清空数据区
	 *追加待办流程
	 */
	append_data: function(json,isEmpty){
		if(isEmpty){ //不是上拉加载就清空数据区
			$("#todoList").empty();
		}
		if(json.success && json.data != null){
			if(json.data.length < 15){//有超过一页的数据再显示滚动加载提示
				$("#pullUp").css("display","none");
	      	$("#scroller .stop-label").html("已加载完成，不要再滚动了");
	      	$("#scroller .stop-label").css("display","block");
			}else{
				$("#pullUp").css("display","block");
				$("#scroller .stop-label").css("display","none");
			}
			$.each(json.data, function(index,array){
				var str  = '<div class="panel" data-uflowid='+array['uFlowId']+' data-flowid='+array['flowId']+' data-step='+array['uStepId']+' data-flowType="'+array['flowType']+'" data-tplSort="'+array['tplSort']+'" data-tohq='+array['toHQ']+' data-tofenfa='+array['toFenfa']+'>';
						str += '<header class="panel-heading"><span class="title">';
						str += '<img src="'+array['face']+'" class="img-circle face">';
						str += '<span class="truename">'+array['truename']+'</span>';
						str += '<span class="name-box"><span class="flowName">'+array['flowName']+'</span>';
						str += '<span class="name">'+array['flowNumber']+'</span>';
						str += '<div class="info"><span class="stime">'+array['level']+'</span>';
						str += '<span class="state">'+array['posttime']+'</span></div></span>';
						str += '<button type="button" class="btn btn-xs status">'+(array['toHQ']==1?'会签中':array['fstatus'])+'</button></span></header>';
						str += '</div>';
				$('#todoList').append(str);
				if(array['fstatus'] === '未发布'){
					$('.status:last').addClass('btn-primary');
				}else if(array['fstatus'] === '办理中'){
					if(array['toHQ'] == 1){
						$('.status:last').addClass('btn-warning');
					}else{
						$('.status:last').addClass('btn-success');
					}
				}else if(array['fstatus'] === '待阅中'){
          $('.status:last').addClass('btn-warning');
        }else if(array['fstatus'] === '已办理'){
					$('.status:last').addClass('btn-info');
				}else if(array['fstatus'] === '已退件'){
					$('.status:last').addClass('btn-warning');
				}else if(array['fstatus'] === '已撤销'){
					$('.status:last').addClass('btn-danger');
				}else{
					$('.status:last').addClass('btn-default');
				}
			})
			pageNow += 1; //当前页+1
		}else{
    	$("#pullUp").css("display","none");
    	$("#scroller .stop-label").css("display","block");
		}
		myScroll.refresh();
	},
	/*
	 *ajax成功返回数据`追加内容`初始化·查询·刷新
	 *json：ajax请求成功返回的data
	 *isEmpty：是否清空数据区 true：清空 false：不清空数据区
	 *追加已办流程数据
	 */
	done_data: function(json,isEmpty){
		if(isEmpty){ //不是上拉加载就清空数据区
			$("#todoList").empty();
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
			$.each(json.data, function(index,array){
				var str  = '<div class="panel" data-uflowid='+array['uFlowId']+' data-flowid='+array['flowId']+' data-step='+array['uStepId']+' data-flowType="'+array['flowType']+'" data-tplSort="'+array['tplSort']+'" data-callback="'+array['allowCallback']+'" data-cancel="'+array['allowCancel']+'" data-fenfa="'+array['allowFenFa']+'">';
						str += '<header class="panel-heading"><span class="title">';
						str += '<img src="'+array['face']+'" class="img-circle face">';
						str += '<span class="truename">'+array['truename']+'</span>';
						str += '<span class="name-box"><span class="flowName">'+array['flowName']+'</span>';
						str += '<span class="name">'+array['flowNumber']+'</span>';
						str += '<div class="info"><span class="stime">'+array['level']+'</span>';
						str += '<span class="state">'+array['posttime']+'</span></div></span>';
						str += '<button type="button" class="btn btn-xs status">'+array['status']+'</button></span></header>';
						str += '</div>';
				$('#todoList').append(str);
				if(array['status'] === '未发布'){
					$('.status:last').addClass('btn-primary');
				}else if(array['status'] === '办理中'){
					$('.status:last').addClass('btn-success');
				}else if(array['status'] === '已办理'){
					$('.status:last').addClass('btn-info');
				}else if(array['status'] === '已退件'){
					$('.status:last').addClass('btn-warning');
				}else if(array['status'] === '已撤销'){
					$('.status:last').addClass('btn-danger');
				}else{
					$('.status:last').addClass('btn-default');
				}
			})
			pageNow += 1; //当前页+1
		}else{
    	$("#pullUp").css("display","none");
    	$("#scroller .stop-label").css("display","block");
		}
		myScroll.refresh();
	},
	/*
	 *ajax成功返回数据`追加内容`初始化·查询·刷新
	 *json：ajax请求成功返回的data
	 *isEmpty：是否清空数据区 true：清空 false：不清空数据区
	 *全部工作流程
	 */
	all_flow: function(json,isEmpty){
		if(isEmpty){ //不是上拉加载就清空数据区
			$("#todoList").empty();
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
			$.each(json.data, function(index,array){
				var str  = '<div class="panel" data-uflowid="'+array['uFlowId']+'" data-flowid="'+array['flowId']+'" data-step="'+array['uStepId']+'" data-flowType="'+array['flowType']+'" data-tplSort="'+array['tplSort']+'" data-tohq='+array['toHQ']+' data-tofenfa='+array['toFenfa']+' data-callback="'+array['allowCallback']+'" data-cancel="'+array['allowCancel']+'" data-fenfa="'+array['allowFenFa']+'" data-category="'+array['category']+'">';
						str += '<header class="panel-heading"><span class="title">';
						str += '<img src="'+array['face']+'" class="img-circle face">';
						str += '<span class="truename">'+array['truename']+'</span>';
						str += '<span class="name-box"><span class="flowName">'+array['flowName']+'</span>';
						str += '<span class="name">'+array['flowNumber']+'</span>';
						str += '<div class="info"><span class="stime">'+array['level']+'</span>';
						str += '<span class="state">'+array['posttime']+'</span></div></span>';
						str += '<button type="button" class="btn btn-xs status">'+(array['toHQ']==1?'会签中':array['fstatus'])+'</button></span></header>';
						str += '</div>';
				$('#todoList').append(str);
				if(array['fstatus'] === '未发布'){
					$('.status:last').addClass('btn-primary');
				}else if(array['fstatus'] === '办理中'){
          if(array['toHQ'] == 1){
            $('.status:last').addClass('btn-warning');
          }else{
            $('.status:last').addClass('btn-success');
          }
				}else if(array['fstatus'] === '待阅中'){
          $('.status:last').addClass('btn-warning');
        }else if(array['fstatus'] === '已办理'){
					$('.status:last').addClass('btn-info');
				}else if(array['fstatus'] === '已退件'){
					$('.status:last').addClass('btn-warning');
				}else if(array['fstatus'] === '已撤销'){
					$('.status:last').addClass('btn-danger');
				}else{
					$('.status:last').addClass('btn-default');
				}
			})
			pageNow += 1; //当前页+1
		}else{
    	$("#pullUp").css("display","none");
    	$("#scroller .stop-label").css("display","block");
		}
		myScroll.refresh();
	},
	/*
	 *ajax成功返回数据`追加内容`初始化·查询·刷新
	 *json：ajax请求成功返回的data
	 *isEmpty：是否清空数据区 true：清空 false：不清空数据区
	 *我发起的工作流程
	 */
	myFlowData: function(json,isEmpty){
		if(isEmpty){ //不是上拉加载就清空数据区
			$("#todoList").empty();
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
			$.each(json.data, function(index,array){
				var str  = '<div class="panel" data-uflowid="'+array['uFlowId']+'" data-flowid="'+array['flowId']+'" data-step="'+array['uStepId']+'" data-flowType="'+array['flowType']+'" data-tplSort="'+array['tplSort']+'" data-callback="'+array['allowCallback']+'" data-cancel="'+array['allowCancel']+'" data-fenfa="'+array['allowFenFa']+'" data-category="'+array['category']+'" >';
						str += '<header class="panel-heading"><span class="title">';
						str += '<img src="'+array['face']+'" class="img-circle face">';
						str += '<span class="truename">'+array['truename']+'</span>';
						str += '<span class="name-box"><span class="flowName">'+array['flowName']+'</span>';
						str += '<span class="name">'+array['flowNumber']+'</span>';
						str += '<div class="info"><span class="stime">'+array['level']+'</span>';
						str += '<span class="state">'+array['posttime']+'</span></div></span>';
						str += '<button type="button" class="btn btn-xs status">'+array['fstatus']+'</button></span></header>';
						str += '</div>';
				$('#todoList').append(str);
				if(array['fstatus'] === '未发布'){
					$('.status:last').addClass('btn-primary');
				}else if(array['fstatus'] === '办理中'){
					$('.status:last').addClass('btn-success');
				}else if(array['fstatus'] === '已办理'){
					$('.status:last').addClass('btn-info');
				}else if(array['fstatus'] === '已退件'){
					$('.status:last').addClass('btn-warning');
				}else if(array['fstatus'] === '已撤销'){
					$('.status:last').addClass('btn-danger');
				}else{
					$('.status:last').addClass('btn-default');
				}
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
		var search = $('#search').val(); //流程标题`编号
		var url = $('#myTabs').attr('data-url'); //请求数据地址
		$.post(url,{'search':search}, function(json){
			var myTabs = Number($('#myTabs').attr('data-tab'));
			switch(myTabs){
				//所有流程
				case 0:
				  myFns.all_flow(json,true);
				  break;
				//待办流程
				case 1:
				  myFns.append_data(json,true);
				  break;
				//已办流程
				case 2:
				  myFns.done_data(json,true);
				  break;
				//我申请的
				case 3:
				  myFns.myFlowData(json,true);
				  break;
			}
		},'json')
	}
}

$(function(){

	//底部添加计划
	$(document).on('touchstart','#btn_actionsheet',function(){
    $('#jingle_popup').slideDown(100);
    $('#jingle_popup_mask').show();
    return false;
	});
	$(document).on('touchstart','#btn-cancel',function(){
    $('#jingle_popup').slideUp(100);
    $('#jingle_popup_mask').hide();
    return false;
	});

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
		try {
		  CNOAApp.popPage();
		} catch (e) {
		  if(/android/ig.test(navigator.userAgent)){
				window.javaInterface.returnToMain();
			}else{
				window.location.href = 'js://pop_view_controller';
			} 
		}
		return false;
	})

	//效果折叠
	$(document).on("touchstart","#todoList .enter",function(){
		var el = $(this).parents(".panel").children(".panel-body");
	    if ($(this).hasClass("glyphicon-collapse-up")) {
        $(this).removeClass("glyphicon-collapse-up").addClass("glyphicon-collapse-down");
        el.slideUp(200);
	    } else {
	    	$('#todoList .panel-body').slideUp(200);
	    	$('#todoList .enter').removeClass("glyphicon-collapse-up").addClass("glyphicon-collapse-down");
        $(this).removeClass("glyphicon-collapse-down").addClass("glyphicon-collapse-up");
        el.slideDown(200);
	    }
	  setTimeout(function(){//延迟刷新
			myScroll.refresh();//数据加载完成调用界面更新方法
		},300)
	})

	//工作流程`待办、已办数据
	function getJsonDataNew(url){
		var search = $('#search').val(); //流程标题`编号
		$.ajax({
			dataType: "json",
			type: "post",
			url: url,
			data:{
				"search":search
			},
			beforeSend: function(){
				//提交表单前验证
				showLoading();
			},
			success: function(json){
				hideLoading();
				//提交成功后调用
				var myTabs = Number($('#myTabs').attr('data-tab'));
				switch(myTabs){
					//所有流程
					case 0:
					  myFns.all_flow(json,true);
					  break;
					//待办流程
					case 1:
					  myFns.append_data(json, true);
					  break;
					//已办流程
					case 2:
					  myFns.done_data(json,true);
					  break;
					//我申请的
					case 3:
					  myFns.myFlowData(json,true);
					  break;
				}
			}
		})
	}

	//默认加载所有待办流程
	getJsonDataNew('m.php?app=wf&func=flow&action=use&modul=done&task=getAllFlowlist&version=mm');

	//待办所有流程
	$(document).on('click','#btnAllFlow',function(){
		pageNow = 1; //重置当前页为1
		var url = 'm.php?app=wf&func=flow&action=use&modul=done&task=getAllFlowlist&version=mm'; //请求数据地址
		$('#myTabs').attr('data-tab','0'); 
		$('#myTabs').attr('data-url',url); //设置请求数据地址
		getJsonDataNew(url);
	});

	//获取待办流程
	$(document).on('click','#btnTodo',function(){
		pageNow = 1; //重置当前页为1
		var url = 'm.php?app=wf&func=flow&action=use&modul=todo&task=getJsonData&version=mm'; //请求数据地址
		$('#myTabs').attr('data-tab','1');
		$('#myTabs').attr('data-url',url); //设置请求数据地址
		getJsonDataNew(url);
	});

	//获取已办流程
	$(document).on('click','#btnDone',function(){
		pageNow = 1; //重置当前页为1
		var url = 'm.php?app=wf&func=flow&action=use&modul=done&task=getWfList&version=mm'; //请求数据地址
		$('#myTabs').attr('data-tab','2');
		$('#myTabs').attr('data-url',url); //设置请求数据地址
		getJsonDataNew(url);
	})

	//我发起的
	$(document).on('click','#btnMyFlow',function(){
		pageNow = 1; //重置当前页为1
		var url = 'm.php?app=wf&func=flow&action=use&modul=done&task=getMyFlowlist&version=mm'; //请求数据地址
		$('#myTabs').attr('data-tab','3');
		$('#myTabs').attr('data-url',url); //设置请求数据地址
		getJsonDataNew(url);
	})

	//搜索
	$(document).on('search','#search',function(){
		var search = $('#search').val(); //流程标题`编号
		var url = $('#myTabs').attr('data-url'); //请求数据地址
		$.ajax({
			dataType: "json",
			type: "post",
			url: url,
			data:{
				"search":search
			},
			beforeSend: function(){
				//提交表单前验证
				showLoading();
			},
			success: function(json){
				hideLoading();
				//提交成功后调用
				var myTabs = Number($('#myTabs').attr('data-tab'));
				switch(myTabs){
					//所有流程
					case 0:
					  myFns.all_flow(json,true);
					  break;
					//待办流程
					case 1:
					  myFns.append_data(json, true);
					  break;
					//已办流程
					case 2:
					  myFns.done_data(json,true);
					  break;
					//我申请的
					case 3:
					  myFns.myFlowData(json,true);
					  break;
				}
			}
		})
		pageNow = 1; //重置当前页为1
	})

	$(document).on('click','#todoList .panel',function(){
		//uStepId   flowId  uFlowId  tohq tofenfa 
		var uFlowId = $(this).attr('data-uflowid'), flowId  = $(this).attr('data-flowid'),
				flowType = $(this).attr('data-flowtype'), tplSort = $(this).attr('data-tplsort'),
				step = $(this).attr('data-step'),category = $(this).attr('data-category'), //流程分类 
				tabs = $('#myTabs').attr('data-tab');//当前页面：所有流程、待办流程、已办流程、我发起的
		var url  = 'm.php?app=wf&func=flow&action=use&modul=todo&task=loadPage&type=show&from=showflow&version=mm';
				//url += '&uFlowId='+uFlowId+'&flowId='+flowId+'&step='+step+'&tabs='+tabs;
		if(category == undefined){
			if(tabs == 1){ //待办
				var tohq    = $(this).attr('data-tohq');
				var tofenfa = $(this).attr('data-tofenfa');
				url += '&tohq='+tohq+'&tofenfa='+tofenfa+'&tabs='+tabs+'&flowType='+flowType+'&tplSort='+tplSort;
			}else if(tabs == 2){ //已办
				var callback = $(this).attr('data-callback');
				var cancel   = $(this).attr('data-cancel');
				var fenfa   = $(this).attr('data-fenfa');
				url += '&callback='+callback+'&cancel='+cancel+'&fenfa='+fenfa+'&tabs='+tabs+'&flowType='+flowType+'&tplSort='+tplSort; 
			};	
		}else{
			tabs = category;
			if(tabs == 1){ //待办
				var tohq    = $(this).attr('data-tohq');
				var tofenfa = $(this).attr('data-tofenfa');
				url += '&tohq='+tohq+'&tofenfa='+tofenfa+'&tabs='+tabs+'&flowType='+flowType+'&tplSort='+tplSort;
			}else if(tabs == 2){ //已办
				var callback = $(this).attr('data-callback');
				var cancel   = $(this).attr('data-cancel');
				var fenfa   = $(this).attr('data-fenfa');
				url += '&callback='+callback+'&cancel='+cancel+'&fenfa='+fenfa+'&tabs='+tabs+'&flowType='+flowType+'&tplSort='+tplSort; 
			};
		}
		url += '&uFlowId='+uFlowId+'&flowId='+flowId+'&step='+step;

		try {
		  CNOAApp.pushOAPage(url, true);
		} catch (e) {
		  window.location.href = url; 
		}
	})

	//发起流程
	$(document).on('touchstart','#btnNewWf',function(){
		window.location.href = 'm.php?app=wf&func=flow&action=use&modul=new&task=loadPage&from=newflow&version=mm';
		return false;
	})

})
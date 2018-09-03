//全局变量`
var pageNow = 1;

//全局变量`函数
var myFns = {
	/**
	*	方法: myFns.getFlowSortList()
	*	功能: 获取流程类别数据
	*/
	getFlowSortList: function(){
		$.ajax({
			dataType: "json",
			type: "post",
			url: "m.php?app=wf&func=flow&action=use&modul=new&task=getSortJsonData&version=mm",
			beforeSend: function(){
				//提交表单前验证
				showLoading();
			},
			success: function(json){
				hideLoading();
				if(json.success){
					$("#pullUp").css("display","none");
					$('.stop-label').css("display","none");
					$('#flowList').empty();
					$.each(json.data, function(index,array){
						var sortStr  = '<div class="panel '+(index == 0 ? 'childFlow':'sort')+'" data-sortid="'+array['sortId']+'" data-sortname="'+array['sortName']+'">';
								sortStr += '<header class="panel-heading">';
                sortStr += '<span class="title"><span class="name-box">';    
                sortStr += '<span class="sortName">'+array['sortName']+'</span></span>'; 
                sortStr += '<span class="glyphicon glyphicon-menu-right chevron" aria-hidden="true"></span>'; 
                sortStr += '</span></header></div>';
            $('#flowList').append(sortStr);
					})
					myScroll.refresh();
				}
			}
		})
	},
	/**
	*	方法: myFns.getFlowList(sortId, start, limit, isEmpty)
	*	功能: 获取流程列表数据
	*	参数: <int> sortId 类别id <int> start 第几条开始取数据 <int> limit 取多少条 <bool> isEmpty：是否清空数据区 true：清空 false：不清空数据区
	*/
	getFlowList: function(sortId, start, limit, isEmpty){
		$.ajax({
			dataType: "json",
			type: "post",
			url: "m.php?app=wf&func=flow&action=use&modul=new&task=getJsonData&version=mm",
			data:{
				"start": start, "limit": limit, "sortId": sortId
			},
			beforeSend: function(){
				//提交表单前验证
				showLoading();
			},
			success: function(json){
				hideLoading();
				if(json.success && json.data != null){
					if(json.data.length < 15){//有超过一页的数据再显示滚动加载提示
						$("#pullUp").css("display","none");
			      	$("#scroller .stop-label").html("已加载完成，不要再滚动了");
			      	$("#scroller .stop-label").css("display","block");
					}else{
						$("#pullUp").css("display","block");
						$("#scroller .stop-label").css("display","none");
					}
					if(isEmpty){ //不是上拉加载就清空数据区
						$("#flowList").empty();
					}
					$.each(json.data, function(index,array){
						var flowStr  = '<div class="panel flow" flowId="'+array['flowId']+'" flowType="'+array['flowType']+'" tplSort="'+array['tplSort']+'">';
								flowStr += '<header class="panel-heading">';
								flowStr += '<span class="title"><span class="name-box">';
						  	flowStr += '<span class="flowName">'+array['flowName']+'</span>';
						    flowStr += '</span><span class="glyphicon glyphicon-menu-right chevron" aria-hidden="true"></span>';
						    flowStr += '</span></header></div>';  
						$('#flowList').append(flowStr);
					})
					pageNow += 1; //当前页+1
				}else{
		    	$("#pullUp").css("display","none");
		    	$("#scroller .stop-label").css("display","block");
				}
				myScroll.refresh();
			}
		})
	},
	//我发起的子流程
	getChildFlowList: function(isEmpty){
		$.ajax({
			dataType: "json",
			type: "post",
			url: "m.php?app=wf&func=flow&action=use&modul=new&task=getJsonData&from=need&version=mm",
			beforeSend: function(){
				//提交表单前验证
				showLoading();
			},
			success: function(json){
				hideLoading();
				if(json.success && json.data != null){
					if(json.data.length < 15){//有超过一页的数据再显示滚动加载提示
						$("#pullUp").css("display","none");
			      	$("#scroller .stop-label").html("已加载完成，不要再滚动了");
			      	$("#scroller .stop-label").css("display","block");
					}else{
						$("#pullUp").css("display","block");
						$("#scroller .stop-label").css("display","none");
					}
					if(isEmpty){ //不是上拉加载就清空数据区
						$("#flowList").empty();
					}
					$.each(json.data, function(index, array){
						$('#pullUp').css('display','none');
						var childFlowStr  = '<div class="panel" data-flowId='+array['flowId']+' data-nameRuleId="'+array['nameRuleId']+'" data-childId="'+array['childId']+'" data-puFlowId="'+array['puFlowId']+'" data-tplSort="'+array['tplSort']+'" data-flowType="'+array['flowType']+'">';
								childFlowStr += '<header class="panel-child-heading"><span class="title">';
								childFlowStr += '<img src="'+array['face']+'" class="img-circle face">';
								childFlowStr += '<span class="truename">'+array['truename']+'</span>';
								childFlowStr += '<span class="name-box"><span class="name">'+array['flowName']+'</span>';                  
                childFlowStr += '<span class="flowName">'+array['puFlowName']+'</span>';   
                childFlowStr += '<div class="info"><span class="label label-danger sname">'+array['sname']+'</span>'; 
                childFlowStr += '<span class="flowNumber">【'+array['flowNumber']+'】</span></div></span>'; 
                childFlowStr += '<button type="button" class="btn btn-xs newFlow btn-primary">';        
                childFlowStr += '<span class="glyphicon glyphicon-plus newFlowIcon" aria-hidden="true"></span></button>';        
                childFlowStr += '</span></header></div>';
            $('#flowList').append(childFlowStr);                         
					})
				}else{
		    	$("#pullUp").css("display","none");
		    	$("#scroller .stop-label").css("display","block");
				}
				myScroll.refresh();
			}
		})
	},
	//下拉刷新
	pull_down_refresh: function(){
		var refreshType = $('#flowList').attr('data-type');		
		if(refreshType == 'sortList'){
			myFns.sortRefreshFns(); //流程类别列表
		}else if(refreshType == 'childFlow'){ //子流程列表
			myFns.getChildFlowList(true);
		} else {
			myFns.flowRefreshFns(); //流程列表
		}
	},
	//刷新类别界面
	sortRefreshFns: function(){
		myFns.getFlowSortList();
	},
	//刷新流程列表界面
	flowRefreshFns: function(){
		var sortId = $('#flowList').attr('data-sortid');
		myFns.getFlowList(sortId, 0, 15, true);
		pageNow = 1; //重置当前页为1
	},
	//上拉加载
	pullUpAction: function(){
		if($('#flowList').attr('data-type') == 'flowList'){ //流程列表
			var limit = 15; //每次取15条
			var start = (pageNow-1) * limit;//设置开始取数据
			var sortId = $('#flowList').attr('data-sortid');
			myFns.getFlowList(sortId, start, limit, false);
		} else if($('#flowList').attr('data-type') == 'childFlow'){ //子流程列表
			myFns.getChildFlowList(true);
		}
	}
}


$(function(){

	//路径导航
	$(document).on("touchstart", "#btnBack", function(){
		if($('#flowList').attr('data-type') == 'sortList'){
			window.location.href = 'm.php?app=wf&func=flow&action=use&modul=todo&task=loadPage&type=show&from=list&version=mm';
		}else{
			$('#flowList').attr('data-sortid', '');
			$('#header .headerName').html('流程分类');
			myFns.getFlowSortList();
		}
		$('#flowList').attr('data-type','sortList');
		return false;
	})

	//流程类别列表
	myFns.getFlowSortList();

	//点击获取当前类别的流程数据
	$(document).on('click', '#flowList .sort', function(){
			var sortId = $(this).attr('data-sortid'), sort = $(this).attr('data-sortname');
			$('#header .headerName').html(sort);
			$('#flowList').attr('data-type','flowList'); //标记数据据是流程类别数据还是流程数据
			if (sortId == '-1') { //发起子流程
				pageNow = 1;
				myFns.getFlowList(sortId, 0, 15, true);
			} else {
				if(sortId != ''){
					$('#flowList').attr('data-sortid', sortId); 
					pageNow = 1;
					myFns.getFlowList(sortId, 0, 15, true);
				}
			}
	})

	//常用子流程
	$(document).on('click', '#flowList .childFlow', function(){
		$('#header .headerName').html('发起子流程');
		$('#flowList').attr('data-type','childFlow'); //标记数据据是流程类别数据还是流程数据
		myFns.getChildFlowList(true);
	})

	//发起子流程
	$(document).on('click', '.panel-child-heading .newFlow',function(){
		var panel = $(this).closest('.panel'), flowId = panel.attr('data-flowId'), childId = panel.attr('data-childId'),
				nameRuleId = panel.attr('data-nameRuleId'), puFlowId = panel.attr('data-puFlowId'),
				tplSort = panel.attr('data-tplSort'), flowType = panel.attr('data-flowType'); 
		var url  = 'm.php?app=wf&func=flow&action=use&modul=form&task=loadPage&flowId='+flowId+'';
				url += '&childId='+childId+'&nameRuleId='+nameRuleId+'&puFlowId='+puFlowId+'';
				url += '&tplSort='+tplSort+'&flowType='+flowType+'&version=mm';
		window.location.href = url;
	})

	//加载发起流程表单
	$(document).on('touchend', '#flowList .flow', function(){
		var flowId = $(this).attr('flowId'), flowType = $(this).attr('flowType'), tplSort = $(this).attr('tplSort');
		window.location.href = 'm.php?app=wf&func=flow&action=use&modul=form&task=loadPage&flowId='+flowId+'&flowType='+flowType+'&tplSort='+tplSort+'&version=mm';
	})

})
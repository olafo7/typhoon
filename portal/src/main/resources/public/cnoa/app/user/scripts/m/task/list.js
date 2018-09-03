
$(function(){
	var status = "",
		pullDownEl = document.getElementById('pullDown'),
	    pullDownOffset = pullDownEl.offsetHeight,
	    pullUpEl = document.getElementById('pullUp'), 
	    pullUpOffset = pullUpEl.offsetHeight,
	    minScrollY = -50,
		scroller = $("#scroller"),
		task_list = $("#task_list"),
		pageSize = 15;
	//初始化绑定iScroll控件
	document.addEventListener('touchmove', function (e) { e.preventDefault(); }, false);
	//内容滚动
	var hScroll = new IScroll('#wrapper', {
		probeType: 3,
		click: true,
		scrollY: true,
		scrollX: false
	});
	//状态栏目滚动
	var wScroll = new IScroll('#taskstatus', {
		click: true,
		scrollX: true,
		scrollY: false
	});
	function dataInit(){
		//初始化数据
		status = 0;
		getStatus();
		var width = 0;
		var li_width = $(window).width()/4;
		$('.nav li').each(function(){
			// width += $(this).width()*2.4;
			width += li_width;
		});
		$("#taskitem").width(width);
		wScroll.refresh();
		if(status != 0){
			$(".noRadius").each(function(){
				if($(this).attr('data-sid') == status){
					$(this).click();
				}
			});
			getList(status, 0, '', 'init');
		}else{
			getList('', 0, '', 'init');
		}
		hScroll.scrollTo(0,minScrollY);
	}
	dataInit();

	//获取跳转时任务状态
	function getStatus(){
		var getData = window.location.href.split("?");  //取得Get参数
		if(getData.length > 1)
		{
			var getOne = getData[1].split("&");
			for(var i=0, iLoop = getOne.length; i<iLoop; i++)
			{
				var kv = getOne[i].split("=");  //分离key与Value
				if(kv[0] == "ts"){
					status = kv[1];
				}
			}
		}
	}
	
	//获取数据
	function getList(sid, start, title, type){
		var isEmpty,isLoading;
		switch(type){
			case "init":
			{
				isEmpty = true;
				isLoading = true;
				break;
			}
			case "pullDown":
			{
				isEmpty = true;
				isLoading = true;
				break;
			}
			case "pullUp":
			{
				isEmpty = false;
				isLoading = false;
				break;
			}
			case "status":
			{
				isEmpty = true;
				isLoading = true;
				break;
			}
			case "search":
			{
				isEmpty = true;
				isLoading = false;
				break;
			}
		}
		$.ajax({
			type: "POST",
			url: "m.php?app=user&func=task&action=default&task=getJsonData&goto=indexTask",
			dataType: "json",
			beforeSend: function(){
				if(isLoading){
					//提交表单前验证
					showLoading();
				}
			},
			data: {status:sid, start:start, title:title, limit:15},
			success: function(json){
				hideLoading();
				var data = json.data;
				var html = '';
				if(isEmpty){
					task_list.empty();
				}
				if(data.length<15){
					$("#pullUp").css("display","none");
			      	$("#scroller .stop-label").html("已加载完成，不要再滚动了");
			      	$("#scroller .stop-label").css("display","block");
				}else{
					$("#pullUp").css("display","block");
					$("#scroller .stop-label").css("display","none");
				}
				for(var i = 0; i < data.length; i++){
					html += '<div class="task_box" data-tid="'+data[i].tid+'" data-status="'+data[i].status+'">';
					html += '<div class="task_tit">'+data[i].title+'</div>';
					html += '<div class="task_info"><span class="task_time">'+data[i].stime+'</span><span class="task_status">状态：'+data[i].statusText+'</span></div></div>';
				}
				task_list.append(html);
				var height = task_list.height();
				if(($(window).height()-146) > height) $("#wrapper").height(height);
				else $("#wrapper").height('');
				hScroll.refresh();
				wScroll.refresh();				
			}
		});
	}
	//滚动功能
	setTimeout(function(){
		//滚动时  
	    hScroll.on('scroll', function(){  
	    	//下拉刷新
	    	if (this.y >= 0 && !pullDownEl.className.match('flip')){
	    		pullDownEl.className = 'flip';
				pullDownEl.querySelector('.pullDownLabel').innerHTML = '松手开始刷新...';
	    	}else if (this.y < 0 && this.y > minScrollY && pullDownEl.className.match('flip')) {
				pullDownEl.className = '';
				pullDownEl.querySelector('.pullDownLabel').innerHTML = '下拉刷新...';
			}
			//上拉加载
	    	if (this.y < this.maxScrollY && !pullUpEl.className.match('flip')) {
				pullUpEl.className = 'flip';
				pullUpEl.querySelector('.pullUpLabel').innerHTML = '松手开始更新...';
			} else if (this.y > this.maxScrollY && pullUpEl.className.match('flip')) {
				pullUpEl.className = '';
				pullUpEl.querySelector('.pullUpLabel').innerHTML = '上拉加载更多...';
			}
	    }); 
		hScroll.on("scrollEnd",function(){
			//下拉刷新
			if(pullDownEl.className.match('flip')){
				pullDownEl.className = 'loading';
				pullDownEl.querySelector('.pullDownLabel').innerHTML = '加载中...';
				task_list.attr("data-page",0);
				var title = $("#search").val();
				getList(status,0,title,'pullDown');
				this.scrollTo(0,minScrollY,1000,IScroll.utils.ease.bounce);
			}else if( this.y > minScrollY && this.y<0){
				this.scrollTo(0,minScrollY,1000,IScroll.utils.ease.bounce);
			}
			//上拉加载
			if(pullUpEl.className.match('flip')){
				pullUpEl.className = 'loading';
				pullUpEl.querySelector('.pullUpLabel').innerHTML = '加载中...';
				var page = parseInt(task_list.attr("data-page")) + 1,
					title = $("#search").val();
				if(page > 0){
					task_list.attr("data-page",page);
					getList(status,page * pageSize,title,'pullUp');
				}
			} else if( (this.y - this.maxScrollY) < pullUpOffset){
				this.scrollTo(0,this.maxScrollY + pullUpOffset,1000,IScroll.utils.ease.bounce);
			}
		});
	},1000);

	//根据状态获取任务
	$(document).on("click",".nav li a",function(){
		var title = $("#search").val();
		status = $(this).attr("data-sid");
		task_list.attr("data-page",0);
		getList(status, 0, title, 'status');
		hScroll.scrollTo(0,minScrollY);
	});
	//跳转
	$(document).on("click",".task_box",function(){
		var tid = $(this).attr("data-tid");
		status = $(this).attr("data-status");
		window.location.href="m.php?app=user&func=task&action=default&task=loadPage&from=view&tid="+tid;
	});
	//返回按钮
	$(document).on("touchstart","#btnBack",function(){
		if(/android/ig.test(navigator.userAgent)){
			window.javaInterface.returnToMain();
		}else{
			window.location.href = 'js://pop_view_controller';
		}
	});
	//布置任务
	$(document).on("touchstart","#listNav",function(){
		window.location.href="m.php?app=user&func=task&action=default&task=loadPage&from=addedit";
	});
	// 搜索关键词
	$(document).on('keyup','#search',function(){
		var title = $("#search").val();
		task_list.attr("data-page",0);
		getList(status,0,title,'search');
	})

});
$(function(){

	//初始化日记列表select控件
	function month_ini(){
		var str = "";
	    var months = ["一月","二月","三月","四月","五月","六月","七月","八月","九月","十月","十一月","十二月"];
	    for(var i = 0;i<months.length;i++){
		  str += "<option value=" + (i+1) +">" + months[i] + "</option>";
	    }
	    $("#select_month").append(str);
	    $("#select_month").val(new Date().getMonth()+1);//默认选择当前月
	}

	// 初始化内容
	function initialize_content(){
		var i = 1;//设置当前页数 
		var page_size = 15;//设置每页的数据
		var start = (i-1)*page_size;//设置开始取数据
		$.getJSON("m.php?app=user&func=diary&action=index&task=getAllDiary&page="+i+"&start="+start+"&limit="+page_size, function(json){
		    if(json.success && json.data != null){
		      //alert(JSON.stringify(json)); //打印json
		      if(json.data.length < page_size){//有超过一页的数据再显示滚动加载提示
		      	$("#pullUp").css("display","none");
		      	$("#scroller .stop-label").html("已加载完成，不要再滚动了");
		      	$("#scroller .stop-label").css("display","block");
		      }else{
		      	$("#pullUp").css("display","block");
	      		$("#scroller .stop-label").css("display","none");
		      }
		      $.each(json['data'],function(index,array){ //遍历
		      	//拼接获取的JSON数据
		      	var str = "<li><a href=\"m.php?app=user&func=diary&action=index&task=loadPageViewDiary&date="+array['date']+"\"><p class=\"content\">"+array['content']+"</p>";
		      	str += "<p class=\"showdate\">"+array['showDate']+"</p></a></li>"
		      	$("#diary_list").append(str); //追加
		      	if(array['supplement']==1){ //标记补充日记
		      		$("<span>(补充日记)</span>").appendTo("#wrapper > #scroller .content:last").css({"color":"#F93","font-size":"114%;"});
		      	} 
		      })
		    }else{
		    	$("#pullUp").css("display","none");
		    	$("#scroller .stop-label").css("display","block");
		    	$("#scroller .stop-label").html("没有检索到相关数据");
		    }
		    myScroll.refresh();//数据加载完成后，调用界面更新方法
		})
	}

	//按月查询和点击查询调用此方法
	function month_search(){
		var page_num =  1;//设置当前页数 
		var page_size = 15;//设置每页的数据
		var start = (page_num-1)*page_size;//设置开始取数据
		var content = $("#diary_title").val();
		var month = $("#select_month").val();
		$.getJSON("m.php?app=user&func=diary&action=index&task=getAllDiary&page="+page_num+"&start="+start+"&month="+month+"&content="+content+"&limit="+page_size, function(json){
		    $("#diary_list").empty();//清空数据区 
		    if(json.success && json.data != null){
		      //alert(JSON.stringify(json)); //打印json
		      if(json.data.length < page_size){//有超过一页的数据再显示滚动加载提示
		      	$("#pullUp").css("display","none");
		      	$("#scroller .stop-label").html("已加载完成，不要再滚动了");
		      	$("#scroller .stop-label").css("display","block");
		      }else{
		      	$("#pullUp").css("display","block");
		      	$("#scroller .stop-label").css("display","none");
		      }
		      $.each(json.data,function(index,array){ //遍历
		      	//拼接获取的JSON数据
		      	var str = "<li><a href=\"m.php?app=user&func=diary&action=index&task=loadPageViewDiary&date="+array['date']+"\"><p class=\"content\">"+array['content']+"</p>";
		      	str += "<p class=\"showdate\">"+array['showDate']+"</p></a></li>"
		      	$("#wrapper > #scroller > ul").append(str); //追加
		      	if(array['supplement']==1){ //标记补充日记
		      		$("<span>(补充日记)</span>").appendTo("#wrapper > #scroller .content:last").css({"color":"#F93","font-size":"116%;"});
		      	}
		      })
		      i = 2;//把全部变量页码i置为2
		    }else{
		    	$("#pullUp").css("display","none");
		    	$("#scroller .stop-label").html("没有检索到相关数据");
		    	$("#scroller .stop-label").css("display","block");
		    }
		    myScroll.refresh();//数据加载完成后，调用界面更新方法
		})
	}

    //底部添加计划
	$('#btn_actionsheet').click(function(){
	    $('#jingle_popup').show(70);
	    $('#jingle_popup_mask').show();
	  });
	$('#btn-cancel').click(function(){
	    $('#jingle_popup').hide(70);
	    $('#jingle_popup_mask').hide();
	  });

	//按月查询出触发
	$(document).on("change","#select_month",function(){
		month_search();
	});
	
	//点击搜索按钮查询
	$(document).on("click","#btn_search",function(){
		month_search();
	});

	//初始化select控件
	month_ini();

	//初始化内容数据
	initialize_content();
})
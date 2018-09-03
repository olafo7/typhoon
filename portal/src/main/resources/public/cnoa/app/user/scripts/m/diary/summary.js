$(function(){
	//获取url参数值
	function GetQueryString(key){
	    var reg = new RegExp("(^|&)"+ key +"=([^&]*)(&|$)");
	    var r = window.location.search.substr(1).match(reg);
	    if(r!=null)return  unescape(r[2]); return null;
	}
	
	var date = GetQueryString('date');//计划的日期
		date = date.replace('/','');
		date = date.replace('/','');
		
	//附加参数和跳转地址
	$(document).on('touchstart','#btnBack',function(){
		window.location.href="m.php?app=user&func=diary&action=index&task=loadPageViewDiary&date="+date;;
	});


	//添加修改总结
	$(document).on("click", "#btn_addsummary", function(){
		var summary = $("#summary").val();//总结内容
		if(summary==""){
			jNotify('内容不能为空!',{
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
			$("#summary").focus();
			return false;
		}
		var post_url = "m.php?app=user&func=diary&action=index&task=addSummary";//请求地址
		$.post(post_url, { "summary":summary, "date":date},function(json){
			//alert(JSON.stringify(json));
			if(json.success){
				jSuccess('保存成功',{
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
				     	window.location.href="m.php?app=user&func=diary&action=index&task=loadPageViewDiary&date="+date;
				     }
				});
			}else{
				jNotify('保存失败!',{
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
			}
	   }, "json");
	})
})
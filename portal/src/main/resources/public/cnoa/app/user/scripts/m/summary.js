$(function(){
	//获取url参数值
	function GetQueryString(key){
	    var reg = new RegExp("(^|&)"+ key +"=([^&]*)(&|$)");
	    var r = window.location.search.substr(1).match(reg);
	    if(r!=null)return  unescape(r[2]); return null;
	}
	
	//附加参数和跳转地址
	$("#btnBack").attr("href","m.php?app=user&func=diary&action=index&task=loadPageViewDiary&date="+GetQueryString('date'));

	//添加修改总结
	$(document).on("click", "#btn_addsummary", function(){
		var summary = $("#summary").val();//总结内容
		var date = GetQueryString('date');//计划的日期
		if(summary==""){
			alert("总结内容不能为空!");
			$("#summary").focus();
			return false;
		}
		var post_url = "m.php?app=user&func=diary&action=index&task=addSummary";//请求地址
		$.post(post_url, { "summary":summary, "date":date},function(json){
			//alert(JSON.stringify(json));
			if(json.success){
				alert(json.msg);
				window.location.href="m.php?app=user&func=diary&action=index&task=loadPageViewDiary&date="+date; 
			}else{
				alert(json.msg);
			}
	   }, "json");
	})
})
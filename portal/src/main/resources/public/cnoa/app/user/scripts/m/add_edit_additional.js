$(function(){

	//请求验收人员
	function getSelectorDataFor(){
		$.getJSON("m.php?action=commonJob&task=getSelectorDataForM&target=user&itemType=checkboxfield",function(json){
			//alert(JSON.stringify(json)); //打印json
			if(json.length > 0){
				var str = "";
				$.each(json,function(index,array){ //遍历
					str += "<option value=" + array['value'] +">" + array['label'] + "</option>";
				})
				$("#signUid").append(str);
			}
		});
	}

	//获取url参数值
	function GetQueryString(key){
	    var reg = new RegExp("(^|&)"+ key +"=([^&]*)(&|$)");
	    var r = window.location.search.substr(1).match(reg);
	    if(r!=null)return  unescape(r[2]); return null;
	}

	//转换日期 格式yyyymmdd转yyyy-mm-dd
	function getFormatDate(dateString){
		var pattern = /(\d{4})(\d{2})(\d{2})/;
		var formatedDate = dateString.replace(pattern, '$1-$2-$3');
		return formatedDate;
	}

	//将日期yyyy-mm-ddTH:m:s转成yyyy-mm-dd和H:m:s
	function getSplitString(str){
		var strs= new Array(); //定义一数组 
		strs=str.split("T"); //字符分割
		for (i=0;i<strs.length ;i++ ){ 
			strs[i] //分割后的字符输出 
		} 
		return strs;
	}

	//提交数据
	$(document).on("click","#btn-additional",function(){
		var content = $("#content").val();//内容
		var plan = $("#plan").val();//计划时间
		var planArr = getSplitString(plan);
		var plandate = planArr[0];//日期
		var plantime = planArr[1];//时间
		var signUid = $("#signUid").val();//验收人ID
		var date = getFormatDate(GetQueryString("date"));//日记日期 yyyy-mm-dd

		if(content == ""){
			alert("内容不能为空!");
			$("#content").focus();
			return false;
		}else if(plan == ""){
			alert("执行时间不能为空!");
			$("#plan").focus();
			return false;
		}

		var post_url = "index.php?app=user&func=diary&action=index&task=addEditAdditional";//请求地址
		var post_data = {date:date, content:content, plandate:plandate, plantime:plantime, signUid:signUid};
		$.post(post_url, post_data, function(json){
			//alert(JSON.stringify(json));
			if(json.success){
				alert(json.msg);
				window.location.href="m.php?app=user&func=diary&action=index&task=loadPage"; 
			}else{
				alert(json.msg);
			}
		},"json");

	})

	//调用请求验收人数据
	getSelectorDataFor();

})
$(function(){

	//选中执行fn1，取消选中执行fn2
	$.fn.extend({
	    toggleCheckbox:function(fn1,fn2){  
	        var that = this;
	        that.on("change",function(){
	            if(that.prop("checked")){
	                fn1(); 
	            }else{
	                fn2();
	            }
	        });
	    }
	});

	/*调用自定义函数*/
	$("#checkbox").toggleCheckbox(function(){
	    $("#remind").prop("disabled",false)
	    $("#checkbox").val(1);//给提醒开关赋值
	},function(){
	    $("#remind").prop("disabled",true)
	    $("#checkbox").val(0);//给提醒开关赋值
	})

	//请求验收人员
	function getSelectorDataFor(){
		$.getJSON("m.php?action=commonJob&task=getSelectorDataForM&target=user&itemType=checkboxfield",function(json){
			//alert(JSON.stringify(json)); //打印json
			if(json.length > 0){
				var str = "";
				$.each(json,function(index,array){ //遍历
					str += "<option value=" + array['value'] +">" + array['label'] + "</option>";
				})
				$("#yanshou").append(str);
			}
		});
	}

	//调用请求验收人数据
	getSelectorDataFor();

	//初始化时间控件
	$('#planetime').date();
	$('#remind').date({theme:"datetime"});
	$('#plantime').date({theme:"datetime"});

	$(document).on('touchstart','#btnBack',function(){
		window.location.href = 'm.php?app=user&func=diary&action=index&task=loadPage';
		return false;
	});

	$(document).on('touchstart','#btnHome',function(){
		window.location.href = 'm.php?app=user&func=diary&action=index&task=loadPage';
		return false;
	});

	//提交表单数据 
	$(document).on('click','#btn-add',function(){
		var content = $("#content").val();//计划内容
		var plantime = $("#plantime").val().replace('T'," ");//执行时间
		var planetime = $("#planetime").val();//完成时间
		var yanshou = $("#yanshou").val();//验收人员id
		var checkbox = $("#checkbox").val();//提醒开关
		var remind  = $("#remind").val().replace('T'," ");//提醒时间
		if(content == ""){
			iosOverlay({
				text: "计划内容不能为空",
				duration: 2e3,
				icon: "resources/images/artDialog/cross.png"
			});
			$("#content").focus();
			return false;
		}
		if(plantime == ""){
			iosOverlay({
				text: "执行时间不能为空",
				duration: 2e3,
				icon: "resources/images/artDialog/cross.png"
			});
			$('#plantime').focus();
			return false;
		}
		if(planetime == ""){
			iosOverlay({
				text: "完成时间不能为空",
				duration: 2e3,
				icon: "resources/images/artDialog/cross.png"
			});
			$('#planetime').focus();
			return false;
		}
		var post_url = "m.php?app=user&func=diary&action=index&task=addDiary";//请求的url
		var form_data = {content:content,plantime:plantime,planetime:planetime,signUid:yanshou,alarm:checkbox,alarmtime:remind};
		$.post(post_url, form_data, function(json){
			//alert(JSON.stringify(json));
			if(json['success']){
				iosOverlay({
					text: json['msg'],
					duration: 2e3,
					icon: "resources/images/artDialog/check.png"
				});
				window.location.href="m.php?app=user&func=diary&action=index&task=loadPage"; 
			}else{
				iosOverlay({
					text: json['msg'],
					duration: 2e3,
					icon: "resources/images/artDialog/cross.png"
				});
			}
		   }, "json");
	})
})


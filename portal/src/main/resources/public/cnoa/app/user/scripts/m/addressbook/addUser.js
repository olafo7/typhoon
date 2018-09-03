//全局变量`函数
var myFns = {
	/*
	 *获取分组列表
	 *ajax成功返回数据`追加内容`初始化
	 *json：ajax请求成功返回的data
	 */
	get_group_list: function(json,isEmpty){
		if(isEmpty){ //不是上拉加载就清空数据区
			$("#user_group").empty();
		}
		var def = "<option value=\"0\" selected=\"selected\">储存分组</option>";
		$('#user_group').append(def);
		if(json.success && json.data.length > 0){
			$.each(json.data, function(index,array){
				var str = "<option value="+array['id']+">"+array['name']+"</option>";
				$('#user_group').append(str);
			})
		}
	},
	//判断当前是否是微信浏览器
	isWeiXin: function(){
		var ua = window.navigator.userAgent.toLowerCase();
		if(ua.match(/MicroMessenger/i) == 'micromessenger'){
		  	return true;
		}else{
		  	return false;
		}
	}
}

$(function(){

	//获取URL参数
	function getQueryString(name) { 
		var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)", "i"); 
		var r = window.location.search.substr(1).match(reg); 
		if (r != null) return unescape(r[2]); return null; 
	} 

	function decode_url(){
		// 姓名、手机号码、公司名称转码
		var url_username = decodeURI(getQueryString('username'));
		var url_mobile 	 = decodeURI(getQueryString('mobile'));
		var url_org		 = decodeURI(getQueryString('org'));

		//避免扫描为空的情况发生
		if (url_username !== 'null' && url_username !== 'undefined') {
			$('#user_name').val(url_username);
		}

		if (url_mobile !== 'null'  && url_username !== 'undefined') {
			$('#user_phone').val(url_mobile);
		}

		if (url_org !== 'null' && url_username !== 'undefined') {
			$('#user_company').val(url_org);
		}
	}
	decode_url();

	//获取分组列表
	function group_list(url){
		$.ajax({
			dataType: "json",
			type: "post",
			url: url,
			success: function(json){
				//提交成功后调用
				myFns.get_group_list(json, true);
			},
			complete: function(XMLHttpRequest, textStatus){
				$(".ui-ios-overlay").removeClass("ios-overlay-show").addClass("ios-overlay-hide");
			}
		})
	}

	group_list('m.php?app=user&func=addressbook&action=default&task=loadPage&from=list&task=getGroupList');

	//如果是微信浏览器隐藏同步手机通讯录
	if (myFns.isWeiXin()) {
		$('#syn_notes').hide();
	} else {
		$('#syn_notes').show();
	}
	
	//点击保存
    $(document).on('touchstart','#save',function(){
    	var user_name 	 = $("#user_name").val(),
    		user_phone 	 = $("#user_phone").val(),
    		user_company = $("#user_company").val(),
    		user_groupid   = $("#user_group").find("option:selected").val();
		if (!user_name) {
			alert('用户名不能为空');
			return false;
		};
		if (user_phone){
			var reg = /(1[3-9]\d{9}$)/;
	        if (!reg.test(user_phone)){
	            alert("请输入正确格式的手机号码！");
	            return false;
	        }
		} else {
			alert('手机号码不能为空'); 
			return false;
		}
		
		//同步到手机通讯录
		var type = $("input[type='checkbox']").is(':checked');

		if (type && !myFns.isWeiXin()) {
			try{
				alert(111);
				CNOAApp.createContact(user_name, user_phone);
			}catch(e){}
		}

		$.ajax({
			dataType: "json",
			type: "post",
			data: {
				'groupid': user_groupid,
				'name'	 : user_name,
				'company': user_company,
				'mobile' : user_phone
			},
			url: "m.php?app=user&func=addressbook&action=default&task=loadPage&from=list&task=addNotes",
			success: function(json){
				//提交成功后调用
				if(json.success){
					//添加成功跳转我的通讯录界面
					window.location.href = 'm.php?app=user&func=addressbook&action=default&task=loadPage&from=my';
				} else {
					alert(json.msg);
				}
			}
		})
    })

	// 点击取消返回上一页
	$(document).on('touchstart','#cancel',function(){
		window.location.href = 'm.php?app=user&func=addressbook&action=default&task=loadPage&from=moreOpt';
	})

	// 点击返回上一页
	$(document).on('touchstart','#btnBack',function(){
		window.location.href = 'm.php?app=user&func=addressbook&action=default&task=loadPage&from=moreOpt';
	})
})

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
	}
}

$(function(){

	//获取URL参数
	function getQueryString(name) { 
		var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)", "i"); 
		var r = window.location.search.substr(1).match(reg); 
		if (r != null) return unescape(r[2]); return null; 
	} 

	// 返回上一页
	$(document).on('touchstart','#btnBack',function(){
		window.location.href = 'm.php?app=user&func=addressbook&action=default&task=loadPage&from=my';
	})

	// 点击取消返回上一页
	$(document).on('touchstart','#cancel',function(){
		window.location.href = 'm.php?app=user&func=addressbook&action=default&task=loadPage&from=my';
	})

	// 获取分组列表
	group_list('m.php?app=user&func=addressbook&action=default&task=loadPage&from=list&task=getGroupList');

	//获取用户信息
	function user_info(url){
		// 获取用户ID
		var user_id = decodeURI(getQueryString('id'));
		$.ajax({
			dataType: "json",
			type: "post",
			url: url,
			data: {
				user_id: user_id
			},
			success: function(json){
				//提交成功后调用
				// myFns.user_list(json);
				$('#user_name').val(json.data.name);
				$('#user_phone').val(json.data.mobile);
				$('#user_company').val(json.data.company);
				$('#user_group').val(json.data.groupid);
			},
			complete: function(XMLHttpRequest, textStatus){
				$(".ui-ios-overlay").removeClass("ios-overlay-show").addClass("ios-overlay-hide");
			}
		})
	}


	//获取分组列表
	function group_list(url){
		$.ajax({
			dataType: "json",
			type: "post",
			url: url,
			success: function(json){
				//提交成功后调用
				myFns.get_group_list(json, true);
				// 获取当前用户信息
				user_info('m.php?app=user&func=addressbook&action=default&task=loadPage&from=list&task=getOneUserInfo');
			},
			complete: function(XMLHttpRequest, textStatus){
				$(".ui-ios-overlay").removeClass("ios-overlay-show").addClass("ios-overlay-hide");
			}
		})
	}

	//修改保存
    $(document).on('touchstart','#save',function(){
    	var user_name 	 = $("#user_name").val(),
    		user_phone 	 = $("#user_phone").val(),
    		user_company = $("#user_company").val(),
    		user_groupid   = $("#user_group").find("option:selected").val(),
    		user_id = decodeURI(getQueryString('id'));
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
		
		$.ajax({
			dataType: "json",
			type: "post",
			data: {
				'groupid': user_groupid,
				'name'	 : user_name,
				'company': user_company,
				'mobile' : user_phone,
				'user_id': user_id
			},
			url: "m.php?app=user&func=addressbook&action=default&task=loadPage&from=list&task=editNotes",
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

})

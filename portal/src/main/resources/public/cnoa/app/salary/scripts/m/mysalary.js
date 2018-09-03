$(function(){

	/**
	 *	方法: getUriString(key)
	 *	功能: 获取url参数值
	 *	参数: <string> key URL键
	 *	返回: <string> URL 对应的键值
	 */
	function getUriString(key){
		var reg = new RegExp("(^|&)"+ key +"=([^&]*)(&|$)");
	    var r = window.location.search.substr(1).match(reg);
	    if(r!=null)return  unescape(r[2]); return null;
	}
	var type = getUriString('type');
	if (!type) {
		$('#check').modal({
			show: true,
			backdrop: 'static', 
			keyboard: false
		});
	}
	//验证用户密码
	function code_password(url){
		var password = $('#password').val();
		$.ajax({
			dataType: "json",
			type: "post",
			url: url,
			data: {
				password: password
			},
			success: function(json){
				//提交成功后调用
				if (json.success) {
					$('#check').modal('hide')
				} else {
					$.confirm({
					    title: '',
					    content: json.msg,
					    animation: "top",
					    cancelButtonClass: 'btn-danger',
					    confirmButton: '确定',
						cancelButton: false
				    })
				    return false;
				}
			}
		})
	}

	$(document).on('click','#sub',function(){
		code_password('m.php?app=salary&func=manage&action=mysalary&task=checkPassword');
		return false;
	})

	$(document).on('click','#salary',function(){
		window.location.href = 'm.php?app=salary&func=manage&action=mysalary&task=loadPage&from=viewSalary';
		return false;
	})

	$(document).on('click','#welfare',function(){
		window.location.href = 'm.php?app=salary&func=manage&action=mysalary&task=loadPage&from=mywelfare';
		return false;
	})

	$(document).on("touchstart","#btnBack",function(){
		if(/android/ig.test(navigator.userAgent)){
			window.javaInterface.returnToMain();
		}else{
			window.location.href = 'js://pop_view_controller';
		}
		return false;
	})

	$(document).on("click",".close",function(){
		if(/android/ig.test(navigator.userAgent)){
			window.javaInterface.returnToMain();
		}else{
			window.location.href = 'js://pop_view_controller';
		}
		return false;
	})
})
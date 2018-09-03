$(function(){
	//获取当前用户的薪酬
	function get_user_welfare(url){
		$.ajax({
			dataType: "json",
			type: "post",
			url: url,
			success: function(json){
				//提交成功后调用
				$("#con_box").empty();
				if(json.success && json.data.length > 0){
					$.each(json.data, function(index,array){
						var str = "<tr>";
							str += "<td>"+array['tuid']+"</td>";
							str += "<td>"+array['title']+"</td>";
							str += "<td>"+array['content']+"</td>";
							str += "</tr>";
						$('#con_box').append(str);
					})
				}
			}
		})
	}

	get_user_welfare('m.php?app=salary&func=manage&action=mysalary&task=getMyWealData');
	// 返回上一步
	$(document).on('touchstart','#btnBack',function(){
		window.location.href = 'm.php?app=salary&func=manage&action=mysalary&task=loadPage&from=mysalary&type=1';
	})
})
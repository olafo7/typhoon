$(function(){
	//获取当前用户的薪酬
	function get_user_salary(url){
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
							str += "<td>"+(index+1)+"</td>";
							str += "<td>"+array['truename']+"</td>";
							str += "<td>"+array['dept']+"</td>";
							str += "<td class='date'>"+array['date']+"</td>";
							str += "<td>"+array['shouldPay']+"</td>";
							str += "<td>"+array['actualPay']+"</td>";
							str += "<td>"+ sureType(array['isInSure']) +"</td>";
							str += "<td style='display:none'>"+ array['reason'] +"</td>";
							str += "<td class='enter'><span class='id' style='display:none'>"+array['id']+"</span>";
							str += "<span class=\"glyphicon glyphicon-collapse-down\"></span></td>";
							str += "<td class='isInSure' style='display:none'>"+array['isInSure']+"</td>";
							str += "</tr>";
						$('#con_box').append(str);
					})
				}
			}
		})
	}

	// 获取确认状态
	function sureType(isInSure){
		if (isInSure == 1) {
			return '已确认';
		} else if (isInSure == 2){
			return "<a href='javascript:void(0)' class='reason'>不同意</a>";
		} else {
			return '未确认';
		}
	}

	// 查看原因
	$(document).on('touchstart','.reason',function(){
		var reason = $(this).parent().next('td').html();
		$.confirm({
		    title: '',
		    content: reason,
		    animation: "top",
		    cancelButtonClass: 'btn-danger',
		    confirmButton: '确定',
			cancelButton: false
	    })
	    return false;
	})

	// 获取用户的薪酬
	get_user_salary('m.php?app=salary&func=manage&action=mysalary&task=getMysalaryJsonData');

    //底部添加计划
	$(document).on('touchstart','.enter',function(){
		var id 	 = $(this).children('.id').html();
		var date = $(this).parents('tr').find('.date').html();
		var isInSure = $(this).next('.isInSure').html();
		if (isInSure == 1 || isInSure == 2) {
			$('#noConfirm').css('display','none');
			$('#confirm').css('display','none');
		} else {
			$('#noConfirm').css('display','block');
			$('#confirm').css('display','block');
		}
		$('#confirm').attr('data-id',id);
		$('#noConfirm').attr('data-id',id);
		$('#details').attr('data-id',id);
		$('#details').attr('data-date',date);
	    $('#jingle_popup').slideDown(100);
	    $('#jingle_popup_mask').show();
	    return false;
	});

	// 取消确认
	$(document).on('touchstart','#btn-cancel',function(){
	    $('#jingle_popup').slideUp(100);
	    $('#jingle_popup_mask').hide();
	    return false;
	});

	// 是否确认
	function is_sure(id){
		$.ajax({
			dataType: 'json',
			data: {
				'id' : id
			},
			type: 'post',
			url: 'm.php?app=salary&func=manage&action=mysalary&task=optIsInSure',
			success: function(json){
				if (json.success) {
					get_user_salary('m.php?app=salary&func=manage&action=mysalary&task=getMysalaryJsonData');
					$('#jingle_popup').slideUp(100);
					$('#jingle_popup_mask').hide();
					return false;
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

	// 确认
	$(document).on('touchstart','#confirm',function(){
		var id = $(this).attr('data-id');
		is_sure(id);
	})

	// 不确认
	$(document).on('touchstart','#noConfirm',function(){
		var id = $(this).attr('data-id');
		$('#noConfirmModal').modal({
			show: true
		})
		$('#explain').val('');
		$('#jingle_popup_mask2').show();
		return false;
	})

	// 取消说明原因
	$('#noConfirmModal').on('hidden.bs.modal', function (e) {
	 	$('#jingle_popup_mask2').hide();
	})

	// 提交说明原因
	$(document).on('touchstart','#sub',function(){
		var id = $('#details').attr('data-id');
		var explain = $('#explain').val();
		addReason(explain,id);
	})

	// 提交原因到数据库
	function addReason(explain,id){
		$.ajax({
			dataType: 'json',
			data: {
				'id' : id,
				'explain' : explain
			},
			type: 'post',
			url: 'm.php?app=salary&func=manage&action=mysalary&task=addReason',
			success: function(json){
				if (json.success) {
					get_user_salary('m.php?app=salary&func=manage&action=mysalary&task=getMysalaryJsonData');
					$('#jingle_popup').slideUp(100);
					$('#jingle_popup_mask2').hide();
				    $('#jingle_popup_mask').hide();
				    $('#noConfirmModal').modal('hide')
				    return false;
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

	// 详细信息
	$(document).on('touchstart','#details',function(){
		var date = $(this).attr('data-date');
		window.location.href = 'm.php?app=salary&func=manage&action=mysalary&task=getOneMysalary&date='+date;
	})

	// 返回上一步
	$(document).on('touchstart','#btnBack',function(){
		window.location.href = 'm.php?app=salary&func=manage&action=mysalary&task=loadPage&from=mysalary&type=1';
	})
})
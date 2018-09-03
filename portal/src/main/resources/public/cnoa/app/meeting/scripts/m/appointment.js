// 全局函数
var myFns = {

	/*
	 *ajax成功返回数据
	 *json：ajax请求成功返回的data
	 *isEmpty：是否清空数据区 true：清空 false：不清空数据区
	 *追加会议的预约情况到表格
	 */
	append_data : function(json,isEmpty){
		if (isEmpty) {
			$('#timeBody').empty();
		}
		if (json.success && json.data.length != null ) {
			var color,value;
			$('.date').html(json.date);
			$('.meetT').empty();
			var meetStr = '<li>会议室(小时)</li>';
				meetStr += '<li class="meet2"></li>';
			$('.meetT').append(meetStr);
			if(json.data.length == 0) {
				$('.meet2').css('height','11px');
			}
			$.each(json.data, function(index,array){
				var str	 = "<tr class='cnoa_tr"+(index+1)+"'>";
					for (var i = 0; i < 48; i++) {
						value = array['dt']['t'+i] ? array['dt']['t'+i] : '';
						if (value == 4) {
							color = 'cnoa_car_a';
						} else if (value == 2) {
							color = 'cnoa_car_b';
						} else if (value == 3) {
							color = 'cnoa_car_c';
						} else {
							color = 'cnoa_car_d';
						}
						str+= "<td class="+color+"></td>";
					};
					str+= "</tr>"
				$('#timeBody').append(str);

				meetStr2 = '<li>'+array['name']+'</li>';
				$('.meetT').append(meetStr2);
			})

			var w = 0;
			$('.tableHeader th').each(function(i){
				if (i < 19) {
				 	w += $(this).width();
				};
			});
			w -= 8;
			$('#main .table-responsive').scrollLeft(w);
		}
	}
}


$(function(){
	var url = $('.days').attr('data-url');

	// 一小时循环
	for (var i = 0; i < 24; i++) {
		str= "<th colspan='2'>"+i+"</th>";
		$('.tableHeader').append(str);
	}

	// 按半小时循环
	for (var i = 0; i < 48; i++) {
		str= "<th></th>";
		$('.half').append(str);
	}

	// 获取会议室预约的情况
	function get_data(day){
		$.ajax({
			dataType: "json",
			type: "post",
			url: url,
			data:{
				'day' : day
			},
			success: function(json){
				myFns.append_data(json,true);
			},
			complete: function(XMLHttpRequest, textStatus){
				$(".ui-ios-overlay").removeClass("ios-overlay-show").addClass("ios-overlay-hide");
			}
		})
	}

	// 加载页面获取数据
	get_data(0);

	// 点击上一天
	$(document).on('touchstart','#yesterday',function(){
		var day = $('#tomorrow').attr('data-days');
		if (day == 0) {
			day = $(this).attr('data-days');
		}
		day --;
		$(this).attr('data-days',day);
		$('#tomorrow').attr('data-days',day);
		get_data(day);
	})

	// 点击今天
	$(document).on('touchstart','#today',function(){
		$('#yesterday').attr('data-days',0);
		$('#tomorrow').attr('data-days',0);
		get_data(0);
	})

	// 点击下一天
	$(document).on('touchstart','#tomorrow',function(){
		var day = $(this).attr('data-days');
			day ++;
		$(this).attr('data-days',day);
		get_data(day);
	})

	$(document).on("touchstart","#btnBack",function(){
		if(/android/ig.test(navigator.userAgent)){
			window.javaInterface.returnToMain();
		}else{
			window.location.href = 'js://pop_view_controller';
		}
		return false;
	})
})
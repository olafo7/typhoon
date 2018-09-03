//全局变量`
var myScroll;

//全局变量`函数
var myFns = { 
	//加载等待效果
	
	/**
	 *登记类型
	 *@param <Object> 当前记录对象
	 *@return <string> 登记类型
	 **/
	checkType : function(record) {
		var data = record;
		var item = data.workType == 0 ? '上班' : '下班';
		return item;
	},
	/**
	 *登记时间
	 *@param <Object> 当前记录对象
	 *@return <string> 登记时间
	 **/
	checkTime: function(record){
		var data = record,
		nDate = data.date,
		recTime = Date.parse(nDate + ' ' + data.recTime), //将时间转换成毫秒 进行比较
		time = Date.parse(nDate + ' ' + data.time),
		stime = Date.parse(nDate + ' ' + data.stime),
		etime = Date.parse(nDate + ' ' + data.etime),
		item = '';
		if(data.recTime == ''){ //未登记
			item = '未登记';
			return item;
		}

		// if (data.workType == 0){
		// 	if (recTime > time && recTime <= etime) {
		// 		item = data.recTime + '<font color=red>(' + '迟到' + ')</font>';
		// 		return item;
		// 	} else if (recTime <= time ) {
		// 		item = data.recTime;
		// 		return item;
		// 	}
		// } else {
		// 	if (recTime >= stime && recTime <= etime) {
		// 		item = data.recTime;
		// 		return item;
		// 	} else if(recTime < stime){
		// 		item = data.recTime + '<font color=red>(' + '早退' + ')</font>';	
		// 		return item;
		// 	}
		// }
		if (data.type == 1) {
			item = data.recTime + '<font color=red>(' + lang('late') + ')</font>';
			return item;
		} else if (data.type == 2) {
			item = data.recTime + '<font color=red>(' + lang('leaveEarly') + ')</font>';	
			return item;
		}
		item = data.recTime;
		return item;
	},
	/**
	 *操作
	 *@param <Object> 当前记录对象
	 *@return <string> 操作类型事件按钮
	 **/
	operation:function(record){
		var data 	= record,
			item 	= '',
			nDate	= data.date,
			nTime	= Date.parse(nDate + ' ' + data.nowTime),
			time 	= Date.parse(nDate + ' ' + data.time),
			recTime = Date.parse(nDate + ' ' + data.recTime),
			stime 	= Date.parse(nDate + ' ' + data.stime),
			etime 	= Date.parse(nDate + ' ' + data.etime),
			explain = data.explain,
			reason	= '<button type="button" class="btn btn-info btn-xs btnDoExplain" data-num="'+data.num+'" data-classes="'+data.classes+'">说明情况</button>';
			
		if (data.option) {
			myFns.checkAlert('');
			item = '<span class="haveExplainSituation" data-explain="'+explain+'">'+data.option+'</span>';
			$('#wfRegister').attr('data-option',data.option);
		}else{

			if (data.recTime == '') {
				if (nTime >= stime && nTime <= etime){

					if(explain != ''){ //已说明情况
						item = '<span class="haveExplainSituation" data-explain="'+explain+'">已说明情况</span>';
					} else { //登记

						item = '<button type="button" class="btn btn-info btn-xs btnDoRecord" data-num="'+data.num+'">去登记</button>';
						//提醒可以打卡
						myFns.checkAlert(data);

					}
				} else if (nTime > etime){

						
					if(explain != ''){ //已说明情况

						item = '<span class="haveExplainSituation" data-explain="'+explain+'">已说明情况</span>';
					} else { //说明情况

						item = reason;
					}
				} else if (nTime < stime){

					if(explain != ''){ //已说明情况

						item = '<span class="haveExplainSituation" data-explain="'+explain+'">已说明情况</span>';
					} else { //不在登记时间

						item = '不在登记时间';
					}
				}
				return item;
			} else{
				if(nTime>=stime && nTime<=etime){
					$('#wfRegister').attr('data-rct','true');
				}
			}
			
			if (data.workType == 0){
				if (recTime > time) {
					item = reason;
				} else if (recTime >= stime && recTime <= time) {
					item = '';
				}
			} else {
				if(recTime < stime){
					item = reason;	
				} else if (recTime >= time && recTime <= etime){
					item = '';
				}
			}

			if(explain != ''){ //已说明情况
				item = '<span class="haveExplainSituation" data-explain="'+explain+'">已说明情况</span>';
			}
			return item;

		}
		return item;	
	},
	/**
	 *记录说明情况
	 *@param <int> cnum 当前处于第几次打卡
	 *@param <string> classes 班次
	 *@param <string> explain 说明情况内容
	 **/
	doExplain:function(cnum, classes ,explain){
		$.ajax({
			type: 'post',
			dataType: 'json',
			data: {'cnum':cnum,'classes':classes,'explain':explain},
			url: 'm.php?app=att&func=person&action=register&task=updateExplain',
			beforeSend: function(){
				//提交表单前验证
				showLoading();
			},
			success: function(json){
				hideLoading();
				if(json.success){ //登记成功
					// iosOverlay({
					// 	text: json.msg,
					// 	duration: 2e3,
					// 	icon: "../../../../../../resources/images/m/artDialog/check.png"
					// })
					jSuccess(json.msg,{
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
					$('#newTime').focus();
					return false;
				}else if(json.failure){
					hideLoading();
					// iosOverlay({
					// 	text: json.msg,
					// 	duration: 2e3,
					// 	icon: "../../../../../../resources/images/m/artDialog/cross.png"
					// })
					jError(json.msg,{
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
				setTimeout(function(){
					myFns.checkAlert('');
					myFns.getRegisterTimeFns(false); //调用上下班记录
				},1000)
			}
		})
	},
	/**
	 *登记
	 *@param <Object> obj 当前当卡班次对象
	 *return <string> 
	 **/
	doRecord:function(obj){
		$.ajax({
			type: 'post',
			dataType: 'json',
			data: {'num':obj.num, 'classes':obj.classs, 'workType':obj.workType,
				'time':obj.times, 'stime':obj.stime, 'etime':obj.etime
			},
			url: 'm.php?app=att&func=person&action=register&task=addChecktime',
			beforeSend:function(){
				//请求数据前执行
				showLoading();
			},
			success: function(json){
				if(json.success){
					hideLoading();
					// iosOverlay({
					// 	text: json.msg,
					// 	duration: 2e3,
					// 	icon: "../../../../../../resources/images/m/artDialog/check.png"
					// })
					jSuccess(json.msg,{
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
				}else if(json.failure){
					hideLoading();
					// iosOverlay({
					// 	text: json.msg,
					// 	duration: 2e3,
					// 	icon: "../../../../../../resources/images/m/artDialog/cross.png"
					// })
					jError(json.msg,{
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
				setTimeout(function(){
					myFns.getRegisterTimeFns(false); //调用上下班记录
				},1000)
			}
		})
	},
	/**上下班登记记录
		*@param <bool> loading 是否启用loading效果 
	**/
	getRegisterTimeFns:function(loading){
		$.ajax({
			type: 'get',
			dataType: 'json',
			url: 'm.php?app=att&func=person&action=register&task=getRegisterTimeAll',
			beforeSend:function(){
				//请求数据前执行
				if(loading !== false){
					showLoading();
				}
			},
			success: function(json){
				$('#myTabContent').empty();
				if(json.success){
					if(json.data.length == 6){
						$("#myTabContent").css('bottom',-100);
						// $("#wrapper").css('overflow','visible');
					}
					$.each(json.data,function(index,array){
						var str = '<div class="panel"><header class="panel-heading"><span class="title">';
						str += '<span class="No"><button class="btn btn-info" type="button">'+(index+1)+'</button></span>';
						str += '<span class="name-box"><span class="name">'+array['classes']+'</span>';
						str += '<div class="infos"><span>登记类型</span><span>'+ myFns.checkType(this) +'</span></div></span>';
						str += '<span class="glyphicon glyphicon-collapse-down enter"></span></span></header>';
						str += '<div class="panel-body"><table id="table'+(index+1)+'" class="table" data-classes="'+array['classes']+'" data-workType="'+array['workType']+'" data-time="'+array['time']+'" data-stime="'+array['stime']+'" data-etime="'+array['etime']+'"><tbody>';
						str += '<tr><th align="left">规定时间:</th><td colspan="3" align="left" id="aTime">'+array['time']+'</td></tr>';
						str += '<tr><th align="left">开始登记时间:</th><td colspan="3" align="left" id="asTime">'+array['stime']+'</td></tr>';
						str += '<tr><th align="left">结束登记时间:</th><td colspan="3" align="left" id="aeTime">'+array['etime']+'</td></tr>';
						str += '<tr><th align="left">登记时间:</th><td colspan="3" align="left" id="checkTime">'+ myFns.checkTime(this) +'</td></tr>';
						str += '<tr><th align="left">操作:</th><td colspan="3" align="left" id="operation">'+ myFns.operation(this) +'</td></tr>';
						str += '</tbody></table></div></div>';
						$('#myTabContent').append(str);
					});
					hideLoading();
					if(json.data == ''){
						// iosOverlay({
						// 	text: '没有检索到数据',
						// 	duration: 2e3,
						// 	icon: "../../../../../../resources/images/m/artDialog/cross.png"
						// })
						jNotify('没有检索到数据',{
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
				}else if(json.failure){
					hideLoading();
					// iosOverlay({
					// 	text: json.msg,
					// 	duration: 2e3,
					// 	icon: "../../../../../../resources/images/m/artDialog/cross.png"
					// })
					jError(json.msg,{
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
					setTimeout(function(){
						window.history.back();
					},2000)
				}
				// myScroll.refresh();
			}
		})
	},
	//检测
	/** 修改操作记录
	*@param <bool> loading 是否启用loading效果 
	**/
	changeOptionFns:function(loading){
		$.ajax({
			type: 'get',
			dataType: 'json',
			url: 'm.php?app=att&func=person&action=register&task=getRegisterTimeAll',
			beforeSend:function(){
				//请求数据前执行
				if(loading !== false){
					showLoading();
				}
			},
			success: function(json){
				if(json.success){
					$.each(json.data,function(index,array){
						$('.name-box .name').html(array['classes']);
						$('#table'+(index+1)+" #aTime").html(array['time']);
						$('#table'+(index+1)+" #asTime").html(array['astime']);
						$('#table'+(index+1)+" #aeTime").html(array['aetime']);
						$('#table'+(index+1)+" #checkTime").html(myFns.checkTime(this));
						$('#table'+(index+1)+" #operation").html(myFns.operation(this));
						// operation
					
					});
					hideLoading();
					if(json.data == ''){
						// iosOverlay({
						// 	text: '没有检索到数据',
						// 	duration: 2e3,
						// 	icon: "../../../../../../resources/images/m/artDialog/cross.png"
						// })
						jNotify('没有检索到数据',{
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
				}else if(json.failure){
					hideLoading();
					// iosOverlay({
					// 	text: json.msg,
					// 	duration: 2e3,
					// 	icon: "../../../../../../resources/images/m/artDialog/cross.png"
					// })
					jError(json.msg,{
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
					setTimeout(function(){
						window.history.back();
					},2000)
				}
				// myScroll.refresh();
			}
		})
	},
	//@return <string> H:m:s 
	current:function(){ 
		var d = new Date(),str=''; 
		//str += d.getFullYear(); //获取当前年份 
		//str += d.getMonth()+1; //获取当前月份（0——11） 
		//str += d.getDate(); //日
		str = myFns.extra(d.getHours()) + ':'; //时
		str += myFns.extra(d.getMinutes()) + ':'; //分
		str += myFns.extra(d.getSeconds()); //秒
		// return str; 
		// alert(str);
		$("#now_time").html(str);
		setTimeout("myFns.current()",1000);
	},
	extra:function(x)  
    {  
        //如果传入数字小于10，数字前补一位0。  
        if(x < 10)  
        {  
            return "0" + x;  
        }  
        else  
        {  
            return x;  
        }  
    },
    //提醒打卡
    checkAlert:function(data){
		if(data){
			$('#wfRegister').attr('data-num',data.num);
			$('#wfRegister').attr('data-classes',data.classes);
			$('#wfRegister').attr('data-workType',data.workType);
			$('#wfRegister').attr('data-time',data.time);
			$('#wfRegister').attr('data-stime',data.stime);
			$('#wfRegister').attr('data-etime',data.etime);
			var explain = "现在是登记时间"; //说明详细信息
			jNotify(explain,{
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
	    }else{
	    	$('#wfRegister').attr('data-num','');
			$('#wfRegister').attr('data-classes','');
			$('#wfRegister').attr('data-workType','');
			$('#wfRegister').attr('data-time','');
			$('#wfRegister').attr('data-stime','');
			$('#wfRegister').attr('data-etime','');
			$('#wfRegister').attr('data-option','');
			$('#wfRegister').attr('data-rct','');
	    }
    }
 	

}

$(function(){
	//获取各个图标的位置
	var c_width = $(window).width()/2;
	$('.tab-overtime').css('left',c_width-52.5);
	$('.tab-leave').css('left',c_width-136.5);
	$('.tab-evection').css('left',c_width-169.5);
	$('.tab-egression').css('left',c_width+31.5);
	$('.tab-again').css('left',c_width+83.5);
	$('.tab-register').css('left',c_width-75.5);

	//获取系统时间
	myFns.current();
	//效果折叠
	$(document).on("touchstart",".enter",function(){
		var el = $(this).parents(".panel").children(".panel-body");
    if ($(this).hasClass("glyphicon-collapse-up")) {
        $(this).removeClass("glyphicon-collapse-up").addClass("glyphicon-collapse-down");
        el.slideUp(200);
    } else {
    	$('.panel-body').slideUp(200); //隐藏所有的.panel-body
    	$('.enter').removeClass("glyphicon-collapse-up").addClass("glyphicon-collapse-down"); //所有.enter改成折叠向下图标
        $(this).removeClass("glyphicon-collapse-down").addClass("glyphicon-collapse-up");
        el.slideDown(200);
    }
    });
	myFns.getRegisterTimeFns(true);
    var intv = setInterval(function(){
		myFns.checkAlert('');
		myFns.changeOptionFns(false);
	}, 1000 * 20);
    
	//绑定流程、表单flowId
	function getBindFlowFns(){
		var url = 'index.php?action=commonJob&act=getBindFlow';
		$.post(url,{'code':'attEvection'},function(json){
			if(json.success){
				$('#wfEvection').attr('flowid', json.msg['flowId']);
			}
		},'json');
		$.post(url,{'code':'attOvertime'},function(json){
			if(json.success){
				$('#wfOvertime').attr('flowid',json.msg['flowId']);
			}
		},'json');
		$.post(url,{'code':'attEgression'},function(json){
			if(json.success){
				$('#wfEgression').attr('flowid',json.msg['flowId']);
			}
		},'json');
		$.post(url,{'code':'attLeave'},function(json){
			if(json.success){
				$('#wfLeave').attr('flowid', json.msg['flowId']);
			}else{
				$.post(url,{'code':'attLeaveInHours'},function(json){
					if(json.success){
						$('#wfLeave').attr('flowid', json.msg['flowId']);
					}
				},'json');
			}
		},'json');
		$.post(url,{'code':'attAgain'},function(json){
			if(json.success){
				$('#wfAgain').attr('flowid', json.msg['flowId']);
			}
		},'json');
	}
	getBindFlowFns();
	//页面加载完成调用`初始化页面
	// myFns.loaded(); 

	//调用上下班登记记录数据
	

	//定位上下班登记
	$(document).on('click','.btnDoRecord',function(){
		var obj = {};
		obj.num = Number($(this).attr('data-num'));
		obj.classs = $(this).closest('.table').attr('data-classes');
		obj.workType = $(this).closest('.table').attr('data-workType');
		obj.times = $(this).closest('.table').attr('data-time');
		obj.stime = $(this).closest('.table').attr('data-stime');
		obj.etime = $(this).closest('.table').attr('data-etime');
		var params = '&num=' + obj.num + '&classs=' + obj.classs + '&worktype=' + obj.workType + '&time=' + obj.times + '&stime=' + obj.stime + '&etime=' + obj.etime;
		var url = 'm.php?app=att&func=person&action=register&task=registrationto' + params;	
		window.location.href = url;
	})

	//说明情况
	$(document).on('click','.btnDoExplain',function(){
		var num = $(this).attr('data-num'); //当前处于第几次打卡
		var classes = $(this).attr('data-classes'); //班次
		swal({   
			title: "说明情况",   
			//text: "这里可以输入并确认:",
			confirmButtonText: "确认操作",   
			type: "input",   
			showCancelButton: true,   
			closeOnConfirm: false,   
			animation: "slide-from-top",   
			inputPlaceholder: "请说明有关情况(如迟到或早退原因)" 
		}, function(inputValue){ 
			if (inputValue === false){
				return false;      
			}
			if (inputValue === "") {     
				swal.showInputError("请输入失败原因!");     
				return false;   
			}
			$('.sweet-overlay').css('display','none'); //隐藏输入框
			$('.sweet-alert').css('display','none');
			myFns.doExplain(num,classes,inputValue); //说明情况
		});

	})

	//查看查看已说明情况详细信息
	$(document).on('click','.haveExplainSituation',function(){
		var explain = $(this).attr('data-explain'); //说明详细信息
		var content = '<span class="explain">'+explain+'</span>';
		$.confirm({
		    title: '详细信息',
		    content: content,
		    animation: "top",
		    cancelButtonClass: 'btn-danger',
		    confirmButton: false
		});
	})
	
	//发起出差流程
	$(document).on('touchstart','#wfEvection',function(){
		var flowId = $(this).attr('flowid');
		if (flowId == undefined) {
			alert('未绑定业务引擎,请联系管理员进行绑定。');
			return false;
		};
		window.location.href = 'm.php?app=wf&func=flow&action=use&modul=form&task=loadPage&flowId='+flowId+'&version=mm';
		return false;
	})
	//发起请假流程
	$(document).on('touchstart','#wfLeave',function(){
		var flowId = $(this).attr('flowid');
		if (flowId == undefined) {
			alert('未绑定业务引擎,请联系管理员进行绑定。');
			return false;
		};
		window.location.href = 'm.php?app=wf&func=flow&action=use&modul=form&task=loadPage&flowId='+flowId+'&version=mm';
		return false;
	})
	//发起加班流程
	$(document).on('touchstart','#wfOvertime',function(){
		var flowId = $(this).attr('flowid');
		if (flowId == undefined) {
			alert('未绑定业务引擎,请联系管理员进行绑定。');
			return false;
		};
		window.location.href = 'm.php?app=wf&func=flow&action=use&modul=form&task=loadPage&flowId='+flowId+'&version=mm';
		return false;
	})
	//发起外出流程
	$(document).on('touchstart','#wfEgression',function(){
		var flowId = $(this).attr('flowid');
		if (flowId == undefined) {
			alert('未绑定业务引擎,请联系管理员进行绑定。');
			return false;
		};
		window.location.href = 'm.php?app=wf&func=flow&action=use&modul=form&task=loadPage&flowId='+flowId+'&version=mm';
		return false;
	})
	//发起补卡流程
	$(document).on('touchstart','#wfAgain',function(){
		var flowId = $(this).attr('flowid');
		if (flowId == undefined) {
			alert('未绑定业务引擎,请联系管理员进行绑定。');
			return false;
		};
		window.location.href = 'm.php?app=wf&func=flow&action=use&modul=form&task=loadPage&flowId='+flowId+'&version=mm';
		return false;
	})
	//发起打卡
	$(document).on('touchstart','#wfRegister',function(){
		//获取打卡状态
		var obj = {};
		obj.num = $(this).attr('data-num');
		obj.classs = $(this).attr('data-classes');
		obj.workType = $(this).attr('data-workType');
		obj.times = $(this).attr('data-time');
		obj.stime = $(this).attr('data-stime');
		obj.etime = $(this).attr('data-etime');
		obj.option = $(this).attr('data-option');
		obj.rct = $(this).attr('data-rct');
		//免签 或 休假
		if( obj.option != undefined ){
			if(obj.option !== ''){
				explain = "当前为"+obj.option+"时间";
				setTimeout(function(){
					jError(explain,{
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
				},200);
				return false;
			}
		}
		
		//已打卡时间
		if( obj.rct != undefined){
			if(obj.rct !== ''){
				explain = "已登记";
				setTimeout(function(){
					jError(explain,{
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
				},200);
				return false;
			}
		}
		//数据缺少
		if(( obj.num == undefined )||( obj.classs == undefined )||( obj.workType == undefined )||( obj.times == undefined )||( obj.stime == undefined )||( obj.etime == undefined )){
			explain = "不在登记时间！"; //说明详细信息
			setTimeout(function(){
				jError(explain,{
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
			},200);
			return false;
		}else if(( obj.num === '')||( obj.classs === '')||( obj.workType === '')||( obj.times === '')||( obj.stime === '' )||( obj.etime === '')){
			explain = "不在登记时间！"; //说明详细信息
			setTimeout(function(){
				jError(explain,{
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
			},200);
			return false;
		}else{
			var params = '&num=' + obj.num + '&classs=' + obj.classs + '&worktype=' + obj.workType + '&time=' + obj.times + '&stime=' + obj.stime + '&etime=' + obj.etime;
			var url = 'm.php?app=att&func=person&action=register&task=registrationto' + params;	

			window.location.href = url;
			
			return false;
		}
	})

	//路径导航
	$(document).on("touchstart","#btnBack",function(){
		if(/android/ig.test(navigator.userAgent)){
			window.javaInterface.returnToMain();
		}else{
			window.location.href = 'js://pop_view_controller';
		}
		return false;
	})
	
})
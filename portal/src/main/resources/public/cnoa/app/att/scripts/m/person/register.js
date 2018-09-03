//全局变量`
var myScroll;

//全局变量`函数
var myFns = { 
	//初始化iScroll控件
	loaded:function(){
			myScroll = new iScroll('wrapper', {
			hScroll: true ,
			vScroll: true ,
			hScrollbar: false ,
			vScrollbar: false ,
			scrollbarClass: 'myScrollbar',
			wheelAction: 'scroll'
		});
		document.addEventListener('touchmove', function (e) { e.preventDefault(); }, false);
	},
	//加载等待效果
	showLoading:function(text){
		var opts = {
			lines: 13, // 画线数
			length: 11, // 每条线的长度
			width: 5, // 线厚度
			radius: 17, // 内圆半径
			corners: 1, // 角圆度(0....1)
			rotate: 0, // 旋转偏移
			color: '#FFF', // 颜色 例：#rgb 和 #rrggbb
			speed: 1, // 每秒轮
			trail: 60, // 余辉百分率
			shadow: false, // 是否渲染一个阴影
			hwaccel: false, // 是否使用硬件加速
			className: 'spinner', // CSS类分配给纺织
			zIndex: 2e9, // z-index（默认为2000000000）
			top: 'auto', // 在像素中相对于父的顶部位置
			left: 'auto' // 左位置相对于父在像素
		};
		var target = document.createElement("div");
		document.body.appendChild(target);
		var spinner = new Spinner(opts).spin(target);
		iosOverlay({
			text: text,
			spinner: spinner
		});
		return false;
	},
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

		if (data.recTime == '') {
			if (nTime >= stime && nTime <= etime){
				if(explain != ''){ //已说明情况
					item = '<span class="haveExplainSituation" data-explain="'+explain+'">已说明情况</span>';
				} else { //登记
					item = '<button type="button" class="btn btn-info btn-xs btnDoRecord" data-num="'+data.num+'">去登记</button>';
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
				myFns.showLoading("正在加载,请稍等...");
			},
			success: function(json){
				$(".ui-ios-overlay").removeClass("ios-overlay-show").addClass("ios-overlay-hide");
				if(json.success){ //登记成功
					iosOverlay({
						text: json.msg,
						duration: 2e3,
						icon: "../../../../../../resources/images/m/artDialog/check.png"
					})
				}else if(json.failure){
					$(".ui-ios-overlay").removeClass("ios-overlay-show").addClass("ios-overlay-hide");
					iosOverlay({
						text: json.msg,
						duration: 2e3,
						icon: "../../../../../../resources/images/m/artDialog/cross.png"
					})
				}
				setTimeout(function(){
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
				myFns.showLoading('正在加载，请稍等...');
			},
			success: function(json){
				if(json.success){
					$(".ui-ios-overlay").removeClass("ios-overlay-show").addClass("ios-overlay-hide");
					iosOverlay({
						text: json.msg,
						duration: 2e3,
						icon: "../../../../../../resources/images/m/artDialog/check.png"
					})
				}else if(json.failure){
					$(".ui-ios-overlay").removeClass("ios-overlay-show").addClass("ios-overlay-hide");
					iosOverlay({
						text: json.msg,
						duration: 2e3,
						icon: "../../../../../../resources/images/m/artDialog/cross.png"
					})
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
			url: 'm.php?app=att&func=person&action=register&task=getRegisterTime',
			beforeSend:function(){
				//请求数据前执行
				if(loading !== false){
					myFns.showLoading('正在加载，请稍等...');
				}
			},
			success: function(json){
				$('#myTabContent').empty();
				if(json.success){
					$.each(json.data,function(index,array){
						var str = '<div class="panel"><header class="panel-heading"><span class="title">';
						str += '<span class="No"><button class="btn btn-info" type="button">'+(index+1)+'</button></span>';
						str += '<span class="name-box"><span class="name">'+array['classes']+'</span>';
						str += '<div class="infos"><span>登记类型</span><span>'+ myFns.checkType(this) +'</span></div></span>';
						str += '<span class="glyphicon glyphicon-collapse-down enter"></span></span></header>';
						str += '<div class="panel-body"><table class="table" data-classes="'+array['classes']+'" data-workType="'+array['workType']+'" data-time="'+array['time']+'" data-stime="'+array['stime']+'" data-etime="'+array['etime']+'"><tbody>';
						str += '<tr><th align="left">规定时间:</th><td colspan="3" align="left">'+array['time']+'</td></tr>';
						str += '<tr><th align="left">开始登记时间:</th><td colspan="3" align="left">'+array['stime']+'</td></tr>';
						str += '<tr><th align="left">结束登记时间:</th><td colspan="3" align="left">'+array['etime']+'</td></tr>';
						str += '<tr><th align="left">登记时间:</th><td colspan="3" align="left">'+ myFns.checkTime(this) +'</td></tr>';
						str += '<tr><th align="left">操作:</th><td colspan="3" align="left">'+ myFns.operation(this) +'</td></tr>';
						str += '</tbody></table></div></div>';
						$('#myTabContent').append(str);
					})
					$(".ui-ios-overlay").removeClass("ios-overlay-show").addClass("ios-overlay-hide");
					if(json.data == ''){
						iosOverlay({
							text: '没有检索到数据',
							duration: 2e3,
							icon: "../../../../../../resources/images/m/artDialog/cross.png"
						})
					}
				}else if(json.failure){
					$(".ui-ios-overlay").removeClass("ios-overlay-show").addClass("ios-overlay-hide");
					iosOverlay({
						text: json.msg,
						duration: 2e3,
						icon: "../../../../../../resources/images/m/artDialog/cross.png"
					})
					setTimeout(function(){
						window.history.back();
					},2000)
				}
				myScroll.refresh();
			}
		})
	},
	//@return <string> H:m:s 
	current:function(){ 
		var d = new Date(),str=''; 
		//str += d.getFullYear(); //获取当前年份 
		//str += d.getMonth()+1; //获取当前月份（0——11） 
		//str += d.getDate(); //日
		str = d.getHours() + ':'; //时
		str += d.getMinutes() + ':'; //分
		str += d.getSeconds(); //秒
		return str; 
	}
}

$(function(){

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
    setTimeout(function(){//延迟刷新
			myScroll.refresh();//数据加载完成调用界面更新方法
		},300)
		return false;
	});

	//页面加载完成调用`初始化页面
	myFns.loaded(); 

	//调用上下班登记记录数据
	myFns.getRegisterTimeFns();

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

	//跳转`我的考勤管理
	$(document).on('touchstart','#btnClasses',function(){
		window.location.href = 'm.php?app=att&func=person&action=classes&task=loadPage';
		return false;
	})

	//跳转`加班登记
	$(document).on('touchstart','#btnOvertime',function(){
		window.location.href = 'm.php?app=att&func=person&action=overtime&task=loadPage';
		return false;
	})

	//跳转`出差登记
	$(document).on('touchstart','#btnEvection',function(){
		window.location.href = 'm.php?app=att&func=person&action=evection&task=loadPage';
		return false;
	})

	//跳转`请假登记
	$(document).on('touchstart','#btnLeave',function(){
		window.location.href = 'm.php?app=att&func=person&action=leave&task=loadPage';
		return false;
	})

	//跳转`外出登记
	$(document).on('touchstart','#btnEgression',function(){
		window.location.href = 'm.php?app=att&func=person&action=egression&task=loadPage';
		return false;
	})

	//跳转`补卡登记
	$(document).on('touchstart','#btnAgain',function(){
		window.location.href = 'm.php?app=att&func=person&action=again&task=loadPage';
		return false;
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
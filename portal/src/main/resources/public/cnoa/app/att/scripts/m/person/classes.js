
//全局变量`
var myScroll;

//全局变量`函数
var myFns = { 
	//初始化iScroll控件
	loaded:function(){0
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
	 *@record <Object> 遍历对象(看代码理解)
	 *@value <string> 单元格数据
	 *@colIndex <string> 单元格位置
	 *@return <string> 单元格实际要显示的内容
	**/
	changeFont:function(record,value,colIndex){
		var data = record,
			date = new Date(),
			nDate = date.getDate();
			nMonth = date.getMonth()+1;

		if (nDate < data.day && nMonth == data.month){
			value = '------';
		}else if (value == '未登记' ) {
			value = "<span style='color: red'>" + '未登记' + "</span>";
		} else if (value == '休假') {
			value = "<span style='color: green'>" + '休假' + "</span>";
		} else if (value == '请假') {
			value = "<span style='color: mediumblue'>" + '请假' + "</span>";
		} else if (value == '正常') {
			switch (colIndex){
				case 0:
					value = data.oneTime;
					break;
				case 1:
					value = data.twoTime;
					break;
				case 2:
					value = data.threeTime;
					break;
				case 3:
					value = data.fourTime;
					break;
			}
		} else if (value == '未登记1') {
			var explain = '';
			switch (colIndex){
				case 0:
					if (data.oneExplain) {
						explain = '<span>' + data.oneExplain + '</span>';
						value = "<span class='explain' data-explain='" + explain + "'>" + "<span style='color: red'>" + '未登记' +"</span>"
							+ "</span>";
						break;
					}
					value = "<span style='color: red'>" + '未登记' + "</span>"
						  + explain;
					break;
				case 1:
					if (data.twoExplain) {
						explain = '<span>' + data.twoExplain + '</span>';
						value = "<span class='explain' data-explain='" + explain + "'>" + "<span style='color: red'>" + '未登记' +"</span>"
							+ "</span>";
						break;
					}
					value = "<span style='color: red'>" + '未登记' + "</span>"
						  + explain;
					break;
				case 2:
					if (data.threeExplain) {
						explain = '<span>' + data.threeExplain + '</span>';
						value = "<span class='explain' data-explain='" + explain + "'>" + "<span style='color: red'>" + '未登记' +"</span>"
							+ "</span>";
						break;
					}
					value = "<span style='color: red'>" + '未登记' + "</span>"
						  + explain;
					break;
				case 3:
					if (data.fourExplain) {
						explain = '<span>' + data.fourExplain + '</span>';
						value = "<span class='explain' data-explain='" + explain + "'>" + "<span style='color: red'>" + '未登记' +"</span>"
							+ "</span>";
						break;
					}
					value = "<span style='color: red'>" + '未登记' + "</span>"
						  + explain;
					break;
			}
		} else if (value == '出差') {
			value = "<span style='color: indigo'>" + '出差' + "</span>";
		} else if (value == '外出') {
			value = "<span style='color: #008B8B'>" + '外出' + "</span>";
		} else if (value == '(出差)'){
			switch (colIndex){
				case 0:
					value = data.oneTime + "<span style='color: red'>(" + '出差' + ")</span>"
					break;
				case 1:
					value = data.twoTime + "<span style='color: red'>(" + '出差' + ")</span>"
					break;
				case 2:
					value = data.threeTime + "<span style='color: red'>(" + '出差' + ")</span>"
					break;
				case 3:
					value = data.fourTime + "<span style='color: red'>(" + '出差' + ")</span>"
					break;
			}
		} else if (value == '(外出)'){
			switch (colIndex){
				case 0:
					value = data.oneTime + "<span style='color: red'>(" + '外出' + ")</span>"
					break;
				case 1:
					value = data.twoTime + "<span style='color: red'>(" + '外出' + ")</span>"
					break;
				case 3:
					value = data.threeTime + "<span style='color: red'>(" + '外出' + ")</span>"
					break;
				case 4:
					value = data.fourTime + "<span style='color: red'>(" + '外出' + ")</span>"
					break;
			}
		} else if (value == '迟到'){
			var explain = '';
			switch (colIndex){
				case 0:
					if (data.oneExplain) {
						explain = '<span>' + data.oneExplain + '</span>';
						value = "<span class='explain' data-explain='" + explain + "'>" + data.oneTime + "<span style='color: red'>(" + '迟到' +")</span>"
							+ "</span>";
						break;
					}
					value = data.oneTime + "<span style='color: red'>(" + '迟到' +")</span>"
						  + explain;
					break;
				case 1:
					if (data.twoExplain) {
						explain = '<span>' + data.twoExplain + '</span>';
						value = "<span class='explain' data-explain='" + explain + "'>" + data.twoTime + "<span style='color: red'>(" + '迟到' +")</span>"
							+ "</span>";
						break;
					}
					value = data.twoTime + "<span style='color: red'>(" + '迟到' +")</span>"
						  + explain;
					break;
				case 2:
					if (data.threeExplain) {
						explain = '<span>' + data.threeExplain + '</span>';
						value = "<span class='explain' data-explain='" + explain + "'>" + data.threeTime + "<span style='color: red'>(" + '迟到' +")</span>"
							+ "</span>";
						break;
					}
					value = data.threeTime + "<span style='color: red'>(" + '迟到' +")</span>"
						  + explain;
					break;
				case 3:
					if (data.fourExplain) {
						explain = '<span>' + data.fourExplain + '</span>';
						value = "<span class='explain' data-explain='" + explain + "'>" + data.fourTime + "<span style='color: red'>(" + '迟到' +")</span>"
							+ "</span>";
						break;
					}
					value = data.fourTime + "<span style='color: red'>(" + '迟到' +")</span>"
						  + explain;
					break;
			}
		} else if (value == '早退'){
			var explain = '';
			switch (colIndex){
				case 0:
					if (data.oneExplain) {
						explain = '<span>' + data.oneExplain + '</span>';
						value = "<span class='explain' data-explain='" + explain + "'>" + data.oneTime + "<span style='color: red'>(" + '早退' +")</span>"
							+ "</span>";
						break;
					}
					value = data.oneTime + "<span style='color: red'>(" + '早退' + ")</span>"
						  + explain;
					break;
				case 1:
					if (data.twoExplain) {
						explain = '<span>' + data.twoExplain + '</span>';
						value = "<span class='explain' data-explain='" + explain + "'>" + data.twoTime + "<span style='color: red'>(" + '早退' +")</span>"
							+ "</span>";
						break;
					}
					value = data.twoTime + "<span style='color: red'>(" + '早退' + ")</span>"
						  + explain;
					break;
				case 2:
					if (data.threeExplain) {
						explain = '<span>' + data.threeExplain + '</span>';
						value = "<span class='explain' data-explain='" + explain + "'>" + data.threeTime + "<span style='color: red'>(" + '早退' +")</span>"
							+ "</span>";
						break;
					}
					value = data.threeTime + "<span style='color: red'>(" + '早退' + ")</span>"
						  + explain;
					break;
				case 3:
					if (data.fourExplain) {
						explain = '<span>' + data.fourExplain + '</span>';
						value = "<span class='explain' data-explain='" + explain + "'>" + data.fourTime + "<span style='color: red'>(" + '早退' +")</span>"
							+ "</span>";
						break;
					}
					value = data.fourTime + "<span style='color: red'>(" + '早退' + ")</span>"
						  + explain;
					break;
			}
		} else if (value == '未登记(休假)') {
			switch (colIndex){
				case 0:
					value = "<span style='color: red'>未登记</span><span style='color: green'>(" + '休假' + ")</span>";
					break;
				case 1:
					value = "<span style='color: red'>未登记</span><span style='color: green'>(" + '休假' + ")</span>";
					break;
				case 2:
					value = "<span style='color: red'>未登记</span><span style='color: green'>(" + '休假' + ")</span>";
					break;
				case 3:
					value = "<span style='color: red'>未登记</span><span style='color: green'>(" + '休假' + ")</span>";
					break;
			}
		} else if (value == '免签') {
			value = "<span style='color:saddlebrown'>" + '免签' + "</span>";
		}

		return value;
	},
	//备注
	leaveHoursRender: function(value){
		if(value=='' || value==undefined || value==null){
			value = "";
		}else{
			value = "请假<span style='color:blue; font-weight:bold'>" + value  + "</span>小时";
		}
		return value;
	}
}


$(function(){

	//页面加载完成调用`初始化页面
	myFns.loaded(); 

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
	});

	function getPersonClassesFns(){
		$.ajax({
			type: 'get',
			dataType: 'json',
			url: 'm.php?app=att&func=person&action=classes&task=getPersonClasses',
			beforeSend:function(){
				//请求数据前执行
				myFns.showLoading('正在加载，请稍等...');
			},
			success:function(json){
				$('#myTabContent').empty();
				var strH = '<div id="personClasses" class="tab-pane fade in active" role="tabpanel" aria-labelledby="profile-tab"></div>';
				$('#myTabContent').append(strH);
				if(json.success){
					$.each(json.data,function(index,array){
						var str = '<div class="panel"><header class="panel-heading"><span class="title">';
						str += '<span class="No"><button class="btn btn-info" type="button">'+(index+1)+'</button></span>';
						str += '<span class="name-box"><span class="name">'+array['date']+'</span>';
						str += '<div class="infos"><span>'+array['truename']+'</span><span class="arrange '+((array['classes']==4) ? 'restTab' : '')+'">'+((array['classes']==4) ? '休假' : array['classes'])+'</span></div></span>';
						str += '<span class="glyphicon glyphicon-collapse-down enter"></span></span></header>';
						str += '<div class="panel-body"><table class="table">';
						str += '<thead><tr class="info"><th>上班</th><th>下班</th><th>上班</th><th>下班</th></tr></thead>';
						str += '<tbody><tr><td>'+(array['oneStime']==4 ? '休假' : array['oneStime'])+'</td><td>'+(array['oneEtime']==4 ? '休假' : array['oneEtime'])+'</td><td>'+(array['twoStime']==4 ? '休假' : array['twoStime'])+'</td><td>'+(array['twoEtime']==4 ? '休假' : array['twoEtime'])+'</td></tr></tbody>';						
						str += '</table></div></div>';
						$('#personClasses').append(str);
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
						duration: 3000,
						icon: "../../../../../../resources/images/m/artDialog/cross.png"
					})
				}
				myScroll.refresh();
			}
		})
	}

	//页面加载完成调用我的排班表
	getPersonClassesFns();

	//调用我的排班表
	$(document).on('touchstart','#btnPersonClasses',getPersonClassesFns)

	//我的假期
	function getPersonRestFns(){
		$.ajax({
			type: 'get',
			dataType: 'json',
			url: 'm.php?app=att&func=person&action=classes&task=getPersonRest',
			beforeSend:function(){
				//请求数据前执行
				myFns.showLoading('正在加载，请稍等...');
			},
			success:function(json){
				$('#myTabContent').empty();
				var strH = '<div id="personRest" class="tab-pane fade in active" role="tabpanel" aria-labelledby="profile-tab"></div>';
				$('#myTabContent').append(strH);
				if(json.success){
					$.each(json.data,function(index,array){
						var str = '<div class="panel"><header class="panel-heading"><span class="title">';
						str += '<span class="No"><button class="btn btn-info" type="button">'+(index+1)+'</button></span>';
						str += '<span class="name-box"><span class="nameR">'+array['truename']+'</span></span>';
						str += '<span class="glyphicon glyphicon-collapse-down enter"></span></span></header>';
						str += '<div class="panel-body"><table class="table"><thead><tr class="info">';
						str += '<th>年假</th><th>已休</th><th>未休</th><th>剩余调休天数</th></tr></thead>';
        		str += '<tbody><tr><td>'+array['annualLeave']+'</td><td>'+array['useLeave']+'</td><td>'+array['unUseLeave']+'</td><td>'+array['takeRest']+'</td></tr>';
        		str += '</tbody></table></div></div>';
        		$('#personRest').append(str);
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
						duration: 3000,
						icon: "../../../../../../resources/images/m/artDialog/cross.png"
					})
				}
				myScroll.refresh();
			}
		})
	}

	//调用我的假期
	$(document).on('touchstart','#btnPersonRest',getPersonRestFns);

	//我的考勤
	function getPersonRecordFns(){
		$.ajax({
			type: 'post',
			dataType: 'json',
			//data: {'stime':'2015-09'},
			url: 'm.php?app=att&func=person&action=classes&task=getPersonRecord',
			beforeSend:function(){
				//请求数据前执行
				myFns.showLoading('正在加载，请稍等...');
			},
			success:function(json){
				$('#myTabContent').empty();
				var strH = '<div id="personRecord" class="tab-pane fade in active" role="tabpanel" aria-labelledby="profile-tab"></div>';
				$('#myTabContent').append(strH);
				if(json.success){
					$.each(json.data,function(index,array){
						var str = '<div class="panel"><header class="panel-heading"><span class="title">';
						str += '<span class="No"><button class="btn btn-info" type="button">'+(index+1)+'</button></span>';
						str += '<span class="name-box"><span class="nameR">'+array['date']+'</span></span>';
						str += '<span class="glyphicon glyphicon-collapse-down enter"></span></span></header><div class="panel-body"><table class="table">';
						str += '<thead><tr class="info"><th>上班</th><th>下班</th><th>上班</th><th>下班</th></tr></thead>';
						str += '<tbody><tr><td class="oneStime"></td><td class="oneEtime"></td><td class="twoStime"></td><td class="twoEtime"></td></tr>';
						if(array['leaveHours']){ //备注
							str += '<tr><td colspan="5">'+myFns.leaveHoursRender(array['leaveHours'])+'</td></tr>';
						}
						str += '</tbody></table></div></div>';
						$('#personRecord').append(str);

						var cellArr = [array['oneStime'],array['oneEtime'],array['twoStime'],array['twoEtime']];
						var obj = this; 
						for(i=0; i < cellArr.length; i++){
							var value = myFns.changeFont(obj,cellArr[i],i);
							switch(i){
								case 0:
									$('.oneStime:last').html(value);
								break;
								case 1:
									$('.oneEtime:last').html(value);
								break;
								case 2:
									$('.twoStime:last').html(value);
								break;
								case 3:
									$('.twoEtime:last').html(value);
								break;
							}
						}
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
						duration: 3000,
						icon: "../../../../../../resources/images/m/artDialog/cross.png"
					})
				}
				myScroll.refresh();
			}
		})
	}

	//详细说明
	$(document).on('click','.explain',function(e){
		var content = $(this).attr('data-explain');
		$.confirm({
		    title: '详细信息',
		    content: content,
		    animation: "top",
		    cancelButtonClass: 'btn-danger',
		    confirmButton: false
		});
	})

	//调用我的考勤
	$(document).on('touchstart','#btnPersonRecord',getPersonRecordFns);

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

	//跳转`上下班登记
	$(document).on('touchstart','#btnRegister',function(){
		window.location.href = 'm.php?app=att&func=person&action=register&task=loadPage';
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
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
	}
}


$(function(){

	//底部添加计划
	$(document).on('touchstart','#btn_actionsheet',function(){
    $('#jingle_popup').slideDown(100);
    $('#jingle_popup_mask').show();
    return false;
	});
	$(document).on('touchstart','#btn-cancel',function(){
    $('#jingle_popup').slideUp(100);
    $('#jingle_popup_mask').hide();
    return false;
	});

	//关闭弹出窗口 
	$(document).on('touchstart','#jingle_popup_mask',function(event){
		if( $(event.target).is(this)) { 
       $(this).hide();
       $('#jingle_popup').slideUp(100);
    }
    return false;
	})

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

	//页面加载完成调用`初始化页面
	myFns.loaded(); 

	//绑定流程、表单flowId
	function getBindFlowFns(){
		var url = 'index.php?action=commonJob&act=getBindFlow';
		$.post(url,{'code':'attOvertime'},function(json){
			if(json.success){
				$('#btn_overtime').attr('flowid',json.msg['flowId']);
			}
		},'json')
	}

	//加班登记记录
	function getOvertimeListFns(){
		getBindFlowFns(); 
		$.ajax({
			type: 'get',
			dataType: 'json',
			url: 'm.php?app=att&func=person&action=overtime&task=getOvertimeList',
			beforeSend:function(){
				//请求数据前执行
				myFns.showLoading('正在加载，请稍等...');
			},
			success: function(json){
				$('#myTabContent').empty();
				if(json.success){
					$.each(json.data,function(index,array){
						var str = '<div class="panel"><header class="panel-heading"><span class="title">';
						str += '<span class="No"><button class="btn btn-info" type="button">'+(index+1)+'</button></span>';
						str += '<span class="name-box"><span class="name">'+array['truename']+'</span>';
						str += '<div class="infos"><span>申请时间</span><span>'+array['posttime']+'</span></div></span>';
						str += '<span class="glyphicon glyphicon-collapse-down enter"></span></span></header>';
						str += '<div class="panel-body"><table class="table"><tbody>';
						str += '<tr><th>申请时间:</th><td colspan="3" align="left">'+array['posttime']+'</td></tr>';
						str += '<tr><th>开始时间:</th><td colspan="3" align="left">'+array['stime']+'</td></tr>';
						str += '<tr><th>结束时间:</th><td colspan="3" align="left">'+array['etime']+'</td></tr>';
						str += '<tr><th>加班时长:</th><td colspan="3" align="left">'+array['hour']+' 小时</td></tr>';
						if(array['reason']){
							str += '<tr><th>加班原因:</th><td colspan="3" align="left">'+array['reason']+'</td></tr>';
						}
						str += '</tbody></table></div></div>';
						$('#myTabContent ').append(str);
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
		}})
	}
	
	//调用加班登记记录数据
	getOvertimeListFns();

	//跳转到发起该流程页面
	$(document).on('touchstart','#btn_overtime',function(){
		var flowId = $(this).attr('flowid');
		if (flowId == undefined) {
			alert('未绑定业务引擎,请联系管理员进行绑定。');
			return false;
		};
		window.location.href = 'm.php?app=wf&func=flow&action=use&modul=form&task=loadPage&flowId='+flowId+'&version=mm';
		return false;
	});

	//跳转`我的考勤管理
	$(document).on('touchstart','#btnClasses',function(){
		window.location.href = 'm.php?app=att&func=person&action=classes&task=loadPage';
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
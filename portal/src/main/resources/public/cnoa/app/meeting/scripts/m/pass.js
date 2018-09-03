var pageNow = 1;

//判断当前客户端Android和ios
if(/android/ig.test(navigator.userAgent)){ //安卓
	var userAgent = 'android';
}else{ //ios或者其他
	var userAgent = 'ios';
} 
var myFns = {
	/**
	 *	方法: myFns.userDataLoaded()
	 *	功能: 初始化人员选择器用户部门iScroll控件
	 */
	userDataLoaded: function(){
		userDataScroll = new IScroll('#userDataWrapper', {
			scrollbars: false,
			click: true
		});
		document.addEventListener('touchmove', function (e) { e.preventDefault(); }, false);
	},
	/**
	 *	方法: myFns.userLoaded()
	 *	功能: 初始化人员选择器删除区域iScroll控件
	 */
	userLoaded: function(){
		userScroll = new IScroll('#userWrapper', { 
			scrollbars: false,
			freeScroll: true,
			scrollX: true,
			scrollY: true,
			click: true
		});
		document.addEventListener('touchmove', function (e) { e.preventDefault(); }, false);
	},
	/**
	 *	方法: myFns.isWeixn(key)
	 *	功能: 判断当前浏览器是否微信浏览器
	 *	返回: <bool> TRUE FALSE
	 */
	isWeixn: function (){  
	  var ua = navigator.userAgent.toLowerCase();  
	  if(ua.match(/MicroMessenger/i)=="micromessenger") {  
	    return true;  
	  } else {  
	    return false;  
	  }  
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
	// 下拉刷新
	pull_down_refresh:function(){
		myFns.refreshFns(); //刷新界面
		pageNow = 1; //重置当前页为1
	},
	//上拉加载
	pullUpAction: function(){
		var limit = 15; //每次取15条
		var start = (pageNow-1) * limit;//设置开始取数据
		var name = $('#searchs').val(); //会议标题
		var url = $('#wrapper').attr('data-url'); //请求数据地址
		var data = {'start':start, 'limit':limit,'name':name,'storeType':'pass'};
		$.post(url, data, function(json){
			myFns.append_data(json,false);
		},'json');
	},
	//刷新界面
	refreshFns: function(){
		var url = $('#wrapper').attr('data-url'); //请求数据地址
		var data = {'storeType':'pass'};
		$.post(url,data,function(json){
			myFns.append_data(json,true);
		},'json')
	},
	/*
	 *ajax成功返回数据
	 *json：ajax请求成功返回的data
	 *isEmpty：是否清空数据区 true：清空 false：不清空数据区
	 *追加内容
	 */
	append_data : function(json,isEmpty){
		if (isEmpty) {
			$('#meet_list').empty();
		};
		if(json.success && json.data.length > 0){
			if(json.data.length < 15){ //有超过一页的数据再显示滚动加载提示
				$("#pullUp").css("display","none");
	      		$("#scroller .stop-label").html("已加载完成，不要再滚动了");
	      		$("#scroller .stop-label").css("display","block");
			}else{
				$("#pullUp").css("display","block");
				$("#scroller .stop-label").css("display","none");
			}
			$.each(json.data, function(index,array){
				var str = "<div class=\"panel btnView\">";
				str += "<header class=\"panel-heading\"><span class=\"title\">";
				str += "<span class=\"name-box\">";
				str += "<span class=\"guide\">>></span><span class=\"title\">"+array['name']+"</span>";
				str += "<div class=\"info\"><span class=\"username\">纪要员："+array['markname']+"</span></div>";
				str += "<div class=\"info2\">状态："+array['statusname']+"<span class=\"posttime\">"+array['stime']+"</span></div>";
				str += "</span>";
				str += "<button type'button' class='btn btn-xs opt btn-success' data-aid="+array['aid']+">操作</button>";
				str += "</div>";
				$('#meet_list').append(str);
			})
			pageNow += 1; //当前页+1
		}else{
	    	$("#pullUp").css("display","none");
	    	$("#scroller .stop-label").css("display","block");
		}
		myScroll.refresh();
	}
}

$(function(){

	get_data("m.php?app=meeting&func=mgr&action=join&task=getJiyaoJsonData");

	// 获取信息
	function get_data(url){
		$.ajax({
			type: 'post',
			data: {
				'storeType': 'pass'
			},
			dataType: 'json',
			url: url,
			beforeSend:function(){
				//请求数据前执行
				myFns.showLoading('正在加载，请稍等...');
			},
			success:function(json){
				myFns.append_data(json,true);
			},
			complete: function(XMLHttpRequest, textStatus){
				$(".ui-ios-overlay").removeClass("ios-overlay-show").addClass("ios-overlay-hide");
			}
		})
	}

	// 点击查看会议信息
	$(document).on('touchstart','#meet_view',function(){
		var aid = $(this).attr('data-aid');
		window.location.href = 'm.php?app=meeting&func=mgr&action=meeting&&task=loadPage&from=view&aid='+aid+'&type=pass';
		return false;
	})

	// 跳转添加/修改纪要内容
	$(document).on('touchstart',"#meet_add_edit",function(){
		var aid = $(this).attr('data-aid');
		window.location.href = 'm.php?app=meeting&func=mgr&action=join&task=loadPage&from=listContent&aid='+aid+"&type=pass";
		return false;
	})

	// 需要我纪要的会议
	$(document).on('touchstart',"#need_me",function(){
		window.location.href = 'm.php?app=meeting&func=mgr&action=meeting&task=loadPage&from=list';
		return false;
	})

	// 需要审核的纪要
	$(document).on('touchstart',"#waiting",function(){
		window.location.href = 'm.php?app=meeting&func=mgr&action=join&task=loadPage&from=waiting';
		return false;
	})

	// 审批通过的纪要
	$(document).on('touchstart',"#pass_approval",function(){
		window.location.href = 'm.php?app=meeting&func=mgr&action=join&task=loadPage&from=pass';
		return false;
	})	

	// 需要重写的纪要
	$(document).on('touchstart',"#need_write",function(){
		window.location.href = 'm.php?app=meeting&func=mgr&action=join&task=loadPage&from=returnback';
		return false;
	})

	// 需要重写的纪要
	$(document).on('touchstart',"#fenfaSub",function(){
		var aid = $('#fenfa').attr('data-aid');
		var attendUids = $('#postValue').val();
		var message = $('#message').val();
		if (!attendUids) {
			jNotify('分发人员不能为空!',{
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
			return false;
		}

		$.ajax({
			type: 'post',
			data: {
				'aid': aid,
				'attendUids': attendUids,
				'opinion': message
			},
			dataType: 'json',
			url: 'm.php?app=meeting&func=mgr&action=join&task=fenfa',
			success:function(json){
				$('#fenfaModal').modal('hide');
				$('#jingle_popup').slideUp(100);
			    $('#jingle_popup2').slideUp(100);
			    $('#jingle_popup_mask').hide();
			    get_data("m.php?app=meeting&func=mgr&action=join&task=getJiyaoJsonData");
			    return false;
			},
			complete: function(XMLHttpRequest, textStatus){
				$(".ui-ios-overlay").removeClass("ios-overlay-show").addClass("ios-overlay-hide");
			}
		})
	})

	//底部添加计划
	$(document).on('touchstart','#listNav',function(){
	    $('#jingle_popup').slideDown(100);
	    $('#jingle_popup_mask').show();
	    return false;
	});

	// 取消按钮
	$(document).on('touchstart','.btn-cancel',function(){
	    $('#jingle_popup').slideUp(100);
	    $('#jingle_popup2').slideUp(100);
	    $('#jingle_popup_mask').hide();
	    return false;
	});

	//关闭弹出窗口 
	$(document).on('touchstart','#jingle_popup_mask',function(event){
		if( $(event.target).is(this)) { 
	       $(this).hide();
	       $('#jingle_popup').slideUp(100);
	       $('#jingle_popup2').slideUp(100);
	    }
	    return false;
	})

	// 选择操作
	$(document).on('touchstart','.opt',function(){
		var aid = $(this).attr('data-aid');
		$('#meet_view').attr('data-aid',aid);
		$('#fenfa').attr('data-aid',aid);
		$('#meet_add_edit').attr('data-aid',aid);
		$('#jingle_popup2').slideDown(100);
	    $('#jingle_popup_mask').show();
	    return false;
	})

	// 搜索关键词
	$(document).on('search','#searchs',function(){
		var name = $('#searchs').val(); //纪要标题
		var url = $('#wrapper').attr('data-url'); //请求数据地址
		$.ajax({
			dataType: "json",
			type: "post",
			url: url,
			data:{
				"name":name,
			 	'storeType':'pass'
			},
			beforeSend: function(){
				//提交表单前验证
				myFns.showLoading("搜索中...");
			},
			success: function(json){
				//提交成功后调用
				myFns.append_data(json,true);
			},
			complete: function(XMLHttpRequest, textStatus){
				$(".ui-ios-overlay").removeClass("ios-overlay-show").addClass("ios-overlay-hide");
			}
		})
		pageNow = 1; //重置当前页为1
	})

	//网页初始化信息
	function loadedInit(){
		//页面加载完成调用`初始化页面
		// myFns.loaded(); 
		myFns.userDataLoaded(); //人员和部门区域
		var userDataWrapperH = $(window).height()-151;
		$('.userDataWrapper').css('height', userDataWrapperH); //人员和部门区域高度
		myFns.userLoaded(); //人员删除操作区域
		if(myFns.isWeixn() && userAgent == 'android'){
			var userWrapperTop = $(window).height()-106; //上线后改为107  147
		} else {
			var userWrapperTop = $(window).height()-146; //上线后改为107  147
		}
		$('.userWrapper').css('top', userWrapperTop); //人员删除操作区域高度
		var userWrapperW = $(window).width() - 84; //userWrapper宽度
		$('#userWrapper').css('width', userWrapperW);
		var userScrollerW = $('#userGroup li').length * $('#userGroup li').width(); //userScroller宽度
		$('#userScroller').css('width', userScrollerW);
		var optsBoxH = $(window).height() - 54;
		$('#userDataModal .opts-box').css('top', optsBoxH);
		$('#userDataModal .opts-box').css('width', $(window).width());
	}

	//初始化信息
	loadedInit();

	//触发人员选择器
	$(document).on('click','.sendee',function(){
		if (userAgent == 'ios') { //ios需要在弹出的层加绝对定位才能滚动
			$('.userDataWrapper,.userDataScroller').css('position','absolute');
		} else {
			$('.userDataWrapper,.userDataScroller').css('position','');
		}
		if($(this).attr('data-readonly') == 'true'){ //TRUE 只读 FALSE 可选择
			return false;
		}	
		var source = $(this).attr('data-name');
		$('#userDataModal').attr('data-source',source);
		var checktype = $(this).attr('data-checktype'); //判断当前控件为单选还是多选
		$('#userDataModal').attr('data-checktype',checktype); //类型标记在人员选择器userDataModal之上
		var uids  = $(this).find('.name').attr('data-uid') == undefined ? '' : $(this).find('.name').attr('data-uid');
		var names = $(this).find('.name').text() == undefined ? '' : $(this).find('.name').text();
		var faces = $(this).find('.name').attr('data-face') == undefined ? '' : $(this).find('.name').attr('data-face');
		if(uids == ''){ //没有已选的人员
    	sendeeArr = [];
	    }else{
	    	var uidArr  = uids.split(',').reverse();
	    	var nameArr = names.split(',');
	    }
	   	if(faces == ''){
	   		var faceArr = [];
	   	}else{
	   		var faceArr = faces.split(',');
	   	}
	   	if(uids != ''){
	   		for (var i = 0; i < uidArr.length; i++) {
		   		if(faceArr[i] == '' || faceArr[i] == undefined){
		   			faceArr[i] = 'file/common/face/default-face.jpg';
		   		}
		   		var userObj = {'uid':uidArr[i], 'name':nameArr[i], 'face':faceArr[i]};
		   		sendeeArr.push(userObj);
		   	};
	   	}
   	 	//请求人员数据
		getSelectorData();
	})
	//关闭人员选择器
	$(document).on('touchstart','#sClose',function(){
		sendeeArr = []; //切记`关闭选择器时需重置全局数组
	});
	//请求人员选择器数据
	function getSelectorData(){
		$.ajax({
			dataType: "json",
			type: "get",
			url: "m.php?action=commonJob&task=getSelectorData&target=getUserDatamm",
			success: function(json){
				if(json.success){ //请求成功
					if(json.paterId == 0){ //上级部门id
						$('#sback').css('display','none');
					}else{
						$('#sback').css('display','block');
					}
					var data  	= json.data;
					var users 	= data.users; //人员数据
					var structs = data.structs; //部门数据
					if(users.length == 0 && structs.length == 0){
						jNotify('没有检索到数据!',{
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
						return false;
					} 
					$('#sback').attr('data-fid',json.paterId); //有数据才改变上级部门id
					$('#userDataGroup').empty();//清空数据区
					if(users.length > 0){ //处理人员数据
						$.each(users, function(i,arr){
							var userStr  = '<li class="list-group-item userList" id="uid-'+arr['uid']+'" data-uid="'+arr['uid']+'" data-name="'+arr['truename']+'" data-face="'+arr['face']+'">';
								userStr += '<span class="tools pull-left">';
								userStr += '<input type="checkbox" class="ipt-hide">';
								userStr += '<label class="checkbox"></label>';
								userStr += '</span>';
								userStr += '<div class="name-box">';
								userStr += '<img src="'+arr['face']+'" class="img-circle face">';
								userStr += '<span class="truename">'+arr['truename']+'</span>';
								userStr += '</div>';
								userStr += '<span class="job">'+arr['jobName']+'</span>';
								userStr += '</li>';
							$('#userDataGroup').append(userStr);
						}) 
						//自动勾选
						setChecked('userList', sendeeArr, 'user');            
					}
					if(structs.length > 0){ //处理部门数据
						$.each(structs, function(index,array){
							var structStr  = '<li class="list-group-item structList" data-sid="'+array['structId']+'">';
								structStr += '<span class="fs1 struct-ico-left" aria-hidden="true" data-icon="&#xe109;"></span>';
								structStr += '<div class="name-box">';
								structStr += '<span class="struct">'+array['structName']+'</span></div>';
				              	structStr += '<div class="fs1 struct-ico-right" aria-hidden="true" data-icon="5"></div>';
				              	structStr += '<div class="userTotal">'+array['userTotal']+'</div>';
				              	structStr += '</li>';
	            			$('#userDataGroup').append(structStr);  	
						})
					}
				}
				//已选人员区域操作
				setUserOpts();

				//重新调整页面
				userDataScroll.refresh(); 
			}
		})
	}

	/**
	 *	方法: setChecked(element)
	 *	功能: 自动扣选
	 *	参数1: <string> element 需要遍历的元素 
	 *	参数2: <array> 存放已扣选的数据`数组 
	 *	参数3: <string> choiceType 选择器类型
	 */
	function setChecked(element,dataArr,choiceType){
		if(choiceType == 'user'){ //人员选择器
			$('.'+element+' :checkbox').each(function(){
				var uid = $(this).closest('.'+element).attr("data-uid"); //选择框当前行的员工id
				var _this = $(this);
				if(dataArr.length > 0){
					for (var i = 0; i < dataArr.length; i++) {
						if (dataArr[i].uid == uid) {
							$(_this).prop("checked", true);
							$(_this).siblings('.checkbox').addClass('cur');
							break;
						}
					}
				}
			})
		}
	}

	//删除人员操作区域
	function setUserOpts(){
		$('#userGroup').empty();
		var sendeeArrLength = sendeeArr.length;
		if (sendeeArrLength > 0) {
			for (var i = 0; i < sendeeArrLength; i++) {
				var faceStr = '<li class="delFace" data-uid="'+sendeeArr[i].uid+'" data-name="'+sendeeArr[i].name+'"><img src="'+sendeeArr[i].face+'" class="img-circle face"></li>';
				$('#userGroup').append(faceStr);
			};
			$('#sFinish').prop('disabled', false);
			$('#sFinish').html('完成(' + sendeeArrLength + ')');
		}else { //是否满足完成操作
			$('#sFinish').prop('disabled', true);
			$('#sFinish').html('完成');
		}
		var userScrollerW = $('#userGroup li').length * $('#userGroup li').width(); //userScroller宽度
		$('#userScroller').css('width', userScrollerW);
		userScroll.refresh();
	}

	//完成人员选择
	$(document).on('touchstart','#sFinish',function(){
		recordData(); //其他通用方式
		sendeeArr = []; //切记`关闭选择器时需重置全局数组
	})

	//完成人员选择`把数据记录到指定位置
	function recordData(){ 
		var source = $('#userDataModal').attr('data-source'); //要把数据到的位置
		$('#' + source).empty(); //先清空数据区再遍历数据
		var uids  = ''; //用户id
		var names = ''; //用户名
		var faces = ''; //用户头像
		//把存放在全局数组中的人员数据遍历到指定位置
		for (var i = 0; i < sendeeArr.length; i++) {
			if (i == 0) {
				uids  += sendeeArr[i].uid;
				names += sendeeArr[i].name;
				faces += sendeeArr[i].face;
			}else {
				uids  += ',' + sendeeArr[i].uid;
				names += ',' + sendeeArr[i].name;
				faces += ',' + sendeeArr[i].face;
			}
		};
		var userStr = '<span class="name" data-uid="'+uids+'" data-face="'+faces+'">'+names+'</span>';
		$('#' + source).append(userStr);
		$('#'+source).next().val(uids); //隐藏input赋值
	}

	//checkbox扣选
	$(document).on('click','.userList',function(){
	    if($(this).find('.ipt-hide').prop('checked')){
	      $(this).find('.checkbox').removeClass('cur');
	      $(this).find('.ipt-hide').prop('checked', false)
	    }else{
	      $(this).find('.checkbox').addClass('cur');
	      $(this).find('.ipt-hide').prop('checked', true)
	    }
	    //改变存放人员的数组
    	changeSendeeArr(this);
   	 	//删除人员区域设置
    	setUserOpts();
	})

    //改变存放人员的全局变量对象
	function changeSendeeArr(userList){
		var uid  = $(userList).attr('data-uid');
		var name = $(userList).attr('data-name');
		var face = $(userList).attr('data-face');
		var userObj = {'uid':uid, 'name':name, 'face':face};
		var chk  = $(userList).find('.ipt-hide');
		var chkStu = $(chk).prop('checked'); //状态
		if($('#userDataModal').attr('data-checktype') == 'true'){ //允许多选
			if(chkStu){ //扣选
				var flag = false;
				for (var i = 0; i < sendeeArr.length; i++) {
					if (sendeeArr[i].uid == uid) {
						flag = true;
						break;
					}
				}
				if(!flag){ //不存在
					sendeeArr.push(userObj);
				}
			}else{ //未扣选
				for (var i = 0; i < sendeeArr.length; i++) {
					if (sendeeArr[i].uid == uid) {
						sendeeArr.remove(i);
					}
				}
			}
		}
	}

	//点击删除人员区域头像
  	$(document).on('click','.delFace',function(){
	  	var uid = $(this).attr('data-uid');
	  	var userListId = 'uid-' + uid;
	  	$('#' + userListId).find('.ipt-hide').prop('checked', false);
	  	$('#' + userListId).find('.checkbox').removeClass('cur');
	  	$(this).remove(); //删除当前用户头像
	  	for (var i = 0; i < sendeeArr.length; i++) {
				if (sendeeArr[i].uid == uid) {
					sendeeArr.remove(i); //删除全局数组中存放着的用户
				}
			}
			if(sendeeArr.length > 0){
				$('#sFinish').prop('disabled', false);
				$('#sFinish').html('完成(' + sendeeArr.length + ')');
			}else{
				$('#sFinish').prop('disabled', true);
				$('#sFinish').html('完成');
			}
	  	var userScrollerW = $('#userGroup li').length * $('#userGroup li').width(); //userScroller宽度
			$('#userScroller').css('width', userScrollerW);
			userScroll.refresh();
	})


  	//进入下一级部门
	$(document).on('click','.structList',function(){
		var structId = $(this).attr('data-sid');
		viewUserData(structId);
	})

	//返回上一级部门
	$(document).on('click','#sback',function(){
		$('#search').val(''); //清空搜索框
		var structId = $(this).attr('data-fid'); //部门id
		if(structId == 0){
			return false;
		}
		viewUserData(structId);
	})

	/*                             
	 *  方法:viewUserData(structId)      
	 *  功能:加载用户和部门数据.         
	 *  参数:部门ID.     
	 */
	function viewUserData(structId){
		$.ajax({
			dataType: "json",
			type: "post",
			url: "m.php?action=commonJob&task=getSelectorData&target=getUserDatamm",
			data: {'structId':structId},
			success: function(json){
				if(json.success){ //请求成功
					if(json.paterId == 0){ //上级部门id
						$('#sback').css('display','none');
					}else{
						$('#sback').css('display','block');
					}
					if(json.msg != 0){
						$('#userDataModal').attr('data-sid', json.msg);
					}else{
						if(json.paterId == 1){
							$('#sback').css('display','none');
						};
					}
					var data  	= json.data;
					var users 	= data.users; //人员数据
					var structs = data.structs; //部门数据
					if(users.length == 0 && structs.length == 0){
						jNotify('没有检索到数据!',{
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
						return false;
					} 
					$('#sback').attr('data-fid',json.paterId); //有数据才改变上级部门id
					$('#userDataGroup').empty();//清空数据区
					if(users.length > 0){ //处理人员数据
						$.each(users, function(i,arr){
							var userStr  = '<li class="list-group-item userList" id="uid-'+arr['uid']+'" data-uid="'+arr['uid']+'" data-name="'+arr['truename']+'" data-face="'+arr['face']+'">';
								userStr += '<span class="tools pull-left">';
								userStr += '<input type="checkbox" class="ipt-hide">';
								userStr += '<label class="checkbox"></label>';
								userStr += '</span>';
								userStr += '<div class="name-box">';
								userStr += '<img src="'+arr['face']+'" class="img-circle face">';
								userStr += '<span class="truename">'+arr['truename']+'</span>';
								userStr += '</div>';
								userStr += '<span class="job">'+arr['jobName']+'</span>';
								userStr += '</li>';
							$('#userDataGroup').append(userStr);
						}) 
						//自动勾选
						setChecked('userList', sendeeArr, 'user');            
					}
					if(structs.length > 0){ //处理部门数据
						$.each(structs, function(index,array){
							var structStr  = '<li class="list-group-item structList" data-sid="'+array['structId']+'">';
								structStr += '<span class="fs1 struct-ico-left" aria-hidden="true" data-icon="&#xe109;"></span>';
								structStr += '<div class="name-box">';
								structStr += '<span class="struct">'+array['structName']+'</span></div>';
				              	structStr += '<div class="fs1 struct-ico-right" aria-hidden="true" data-icon="5"></div>';
				              	structStr += '<div class="userTotal">'+array['userTotal']+'</div>';
				              	structStr += '</li>';
				            $('#userDataGroup').append(structStr);  	
						})
					}	
				}
				//已选人员区域操作
				setUserOpts();

				//重新调整页面
				userDataScroll.refresh(); 
			}
		})
	}

	//按钮被松开时触发搜索人员事件
	$(document).on('keyup','#search',getSearchUserData);

	function getSearchUserData(){
		var search = $('#search').val();
		if(search != ''){ 
			var httpUrl = 'm.php?action=commonJob&task=getSelectorData&target=getUserDatamm&search='+search;
		}else{
			var structId = $('#userDataModal').attr('data-sid');
			var httpUrl = 'm.php?action=commonJob&task=getSelectorData&target=getUserDatamm&structId='+structId;
		}
		$.ajax({
			dataType: "json",
			type: "post",
			url: httpUrl,
			beforeSend: function(){
				//提交表单前验证
				myFns.showLoading('正在加载，请稍等...');
			},
			success: function(json){
				$('#userDataGroup').empty();//清空数据区
				if(json.success){
					var data  = json.data;
					var users = data.users; //人员数据
					if(users.length > 0){
						$.each(users,function(i, arr){
							var userStr  = '<li class="list-group-item userList" id="uid-'+arr['uid']+'" data-uid="'+arr['uid']+'" data-name="'+arr['truename']+'" data-face="'+arr['face']+'">';
								userStr += '<span class="tools pull-left">';
								userStr += '<input type="checkbox" class="ipt-hide">';
								userStr += '<label class="checkbox"></label>';
								userStr += '</span>';
								userStr += '<div class="name-box">';
								userStr += '<img src="'+arr['face']+'" class="img-circle face">';
								userStr += '<span class="truename">'+arr['truename']+'</span>';
								userStr += '</div>';
								userStr += '<span class="job">'+arr['jobName']+'</span>';
								userStr += '</li>';
							$('#userDataGroup').append(userStr);
						})
						//自动扣选
						setChecked('userList', sendeeArr, 'user');
					}

					if(data.hasOwnProperty('structs')){
						var structs = data.structs; //部门数据
						if(structs.length > 0){ //处理部门数据
							$.each(structs, function(index,array){
								var structStr  = '<li class="list-group-item structList" data-sid="'+array['structId']+'">';
									structStr += '<span class="fs1 struct-ico-left" aria-hidden="true" data-icon="&#xe109;"></span>';
									structStr += '<div class="name-box">';
									structStr += '<span class="struct">'+array['structName']+'</span></div>';
					              	structStr += '<div class="fs1 struct-ico-right" aria-hidden="true" data-icon="5"></div>';
					              	structStr += '<div class="userTotal">'+array['userTotal']+'</div>';
					              	structStr += '</li>';
					            $('#userDataGroup').append(structStr);  	
							})
						}
					};
				}

				//已选人员区域操作
				setUserOpts();

				//重新调整页面
				userDataScroll.refresh(); 
			}
		})
	}

	$(document).on("touchstart","#btnBack",function(){
		// if(/android/ig.test(navigator.userAgent)){
		// 	window.javaInterface.returnToMain();
		// }else{
		// 	window.location.href = 'js://pop_view_controller';
		// }
		// return false;
		window.location.href = 'm.php?app=meeting&func=mgr&action=join&task=loadPage&from=meetingManage';
	})
})
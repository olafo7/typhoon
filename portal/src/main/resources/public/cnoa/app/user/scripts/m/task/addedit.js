var sendeeObj = {}; //接收人全局对象
var myScroll,userDataScroll,userScroll;  
//myScroll.refresh();//数据加载完成后调用界面更新方法
var sendeeArr = [];//全局对象和数组 
//判断当前客户端Android和ios
if(/android/ig.test(navigator.userAgent)){ //安卓
	var userAgent = 'android';
}else{ //ios或者其他
	var userAgent = 'ios';
}

//全局变量`函数
var myFns = {
	//判断数组中是否包含某元素
	in_array:function(arr,str){
		var i = arr.length;
		if(i > 0){
			while(i--){
				if(arr[i] === str){
					return true;
				}
			}
		}
		return false;
	},
	//加载等待效果
	// showLoading:function(text){
	// 	var opts = {
	// 		lines: 13, // 画线数
	// 		length: 11, // 每条线的长度
	// 		width: 5, // 线厚度
	// 		radius: 17, // 内圆半径
	// 		corners: 1, // 角圆度(0....1)
	// 		rotate: 0, // 旋转偏移
	// 		color: '#FFF', // 颜色 例：#rgb 和 #rrggbb
	// 		speed: 1, // 每秒轮
	// 		trail: 60, // 余辉百分率
	// 		shadow: false, // 是否渲染一个阴影
	// 		hwaccel: false, // 是否使用硬件加速
	// 		className: 'spinner', // CSS类分配给纺织
	// 		zIndex: 2e9, // z-index（默认为2000000000）
	// 		top: 'auto', // 在像素中相对于父的顶部位置
	// 		left: 'auto' // 左位置相对于父在像素
	// 	};
	// 	var target = document.createElement("div");
	// 	document.body.appendChild(target);
	// 	var spinner = new Spinner(opts).spin(target);
	// 	iosOverlay({
	// 		text: text,
	// 		spinner: spinner
	// 	});
	// 	return false;
	// },
	/**
	 *获取url参数值
	 *参数：键
	 **/
	getUrlValue:function(key){
	    var reg = new RegExp("(^|&)"+ key +"=([^&]*)(&|$)");
	    var r = window.location.search.substr(1).match(reg);
	    if(r!=null)return  unescape(r[2]); return null;
	},
	/**
	 *初始化iScroll控件
	 *参数：参数为滚动的区域元素
	 **/
	loaded:function(element){ //参数为滚动的区域元素
			//初始化绑定iScroll控件
			myScroll = new iScroll(element, {
			scrollbarClass: 'myScrollbar',
			wheelAction: 'scroll',
			preventDefault: false,
			preventDefaultException: { tagName: /^(INPUT|TEXTAREA|BUTTON|SELECT|A)$/ }
		});
		setTimeout(function () { document.getElementById(element).style.left = '0'; }, 800);
		document.addEventListener('touchmove', function (e) { e.preventDefault(); }, false);
	},
	/**
	 *	方法: myFns.userDataLoaded()
	 *	功能: 初初始化人员选择器用户部门iScroll控件
	 */
	userDataLoaded: function(element){
		userDataScroll = new iScroll(element, {
			scrollbarClass: 'myScrollbar',
			wheelAction: 'scroll'
		});
		setTimeout(function () { document.getElementById(element).style.left = '0'; }, 800);
		document.addEventListener('touchmove', function (e) { e.preventDefault(); }, false);
	},
	/**
	 *	方法: myFns.userLoaded()
	 *	功能: 初始化人员选择器删除区域iScroll控件
	 */
	userLoaded: function(element){
		userScroll = new iScroll(element, { 
			scrollbarClass: 'myScrollbar',
			wheelAction: 'scroll'
		});
		setTimeout(function () { document.getElementById(element).style.left = '0'; }, 800);
		document.addEventListener('touchmove', function (e) { e.preventDefault(); }, false);
	},
	isWeixn: function (){  
	  var ua = navigator.userAgent.toLowerCase();  
	  if(ua.match(/MicroMessenger/i)=="micromessenger") {  
	    return true;  
	  } else {  
	    return false;  
	  }  
	}
}

$(function(){

	//获取任务来源类型
	$(document).on('click','#fromid',function(){
	$('#jingle_popup').slideDown(100);
	$('#jingle_popup_mask').show();
	return false;
	});
	//关闭弹出窗口 
	$(document).on('touchstart','#jingle_popup_mask',function(event){
		if( $(event.target).is(this)) { 
	     $(this).hide();
	     $('#jingle_popup').slideUp(100);
	  }
	  return false;
	});
	window.onresize = function(){
		myScroll.scrollTo(0, -50);
	}
	//初始化时间控件
	$('#stime').date();
	$('#etime').date();

	//页面加载完成调用`初始化页面
	function loadedInit(){
		myFns.loaded('wrapper');
		myFns.userDataLoaded('userDataWrapper'); //人员和部门区域
		var userDataWrapperH = $(window).height()-151;
		$('.userDataWrapper').css('height', userDataWrapperH); //人员和部门区域高度
		myFns.userLoaded('userWrapper'); //人员删除操作区域
		if(myFns.isWeixn() && userAgent == 'android'){
			var userWrapperTop = $(window).height()-142; //上线后改为107  147
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

		//动态改变modal-body的高度 注:减去的为头部+搜索框的高度
		var modalBodyH = $(document).height()-111;
		$('#modal-body-wrapper').css('height',modalBodyH);

		
	}
	loadedInit();

	//部门计划数据
	function planFns(cid){
		var url = "m.php?app=user&func=task&action=default&task=loadFromListForPlan&tid=0";
		$.getJSON(url,function(json){
			if(json.data != null){
				$("#task_child").empty();
				var strDep = "";
				$.each(json.data,function(index,array){
					if(cid == array['value']){
						strDep += "<option value=" + array['value'] +" selected>" + array['fromid'] + "</option>";
					}else{
						strDep += "<option value=" + array['value'] +">" + array['fromid'] + "</option>";
					}
				})
				$("#task_child").append(strDep);
				myScroll.refresh();
			}
		});
	}

	//母任务
	function mothertaskFns(cid){
		
		var url = "m.php?app=user&func=task&action=default&task=loadFromListForMotherTask&tid=0";
		$.getJSON(url,function(json){
			if(json.data != null){
				$("#task_child").empty();
				var strMot = "";
				$.each(json.data,function(index,array){
					if(cid == array['value']){
						strMot += "<option value=" + array['value'] +" selected>" + array['fromid'] + "</option>";
					}else{
						strMot += "<option value=" + array['value'] +">" + array['fromid'] + "</option>";
					}
				})
				$("#task_child").append(strMot);
				myScroll.refresh();
			}
		});
	}



	//任务来源-监听事件
	$(document).on("touchend","#task",function(){
	 	taskFromData(this);
	});
	$(document).on("touchend","#plan",function(){
		taskFromData(this);
	});
	$(document).on("touchend","#mothertask",function(){
		taskFromData(this);
	});
	//根据状态修改任务来源
	function taskFromData(obj){
		//修改任务来源
		var fromid = $(obj).html();
		$("#fromid").attr("value",fromid);
		var taskid = $(obj).attr("id");
		switch(taskid){
			case 'plan': //部门计划
				$(".task-child-lable").html(fromid);
				$("#task_child_form").show();
				planFns();
			break;
			case 'mothertask': //母任务
				$(".task-child-lable").html(fromid);
				$("#task_child_form").show();
				mothertaskFns();
			break;
			default: //自定义
				$("#task_child_form").hide();
		}
		$(".btn_task").removeClass("active");
		$(obj).addClass("active");
		
		setTimeout(function(){
			$("#jingle_popup_mask").hide();
		},500);
	    $('#jingle_popup').slideUp(100);
	}

	//判断是添加还是修改表单
	var tid = myFns.getUrlValue('tid');
	if(tid){ //编辑表单
		$('#pageName').html('修改任务'); //更改网页名
		$('#addedit_f').attr('data-form','edit'); //设置为修改任务表单
		var url = 'm.php?app=user&func=task&action=default'; //请求当前任务的表单数据
		$.post(url,{'tid':tid,'task':'loadFormData'},function(json){
			if(json.data != null){ //有数据存在
				var data = json.data; //当前任务编辑数据

				if(data['title']){ //任务标题
					$('#title').val(data['title']);
				}

				var task_str = $("#"+data['from']).html();
				$('#fromid').attr("value",task_str);
				$('.task-child-lable').html(task_str);
				if(data['from'] == 'plan' || data['from'] == 'mothertask'){ //如果有子任务
					$("#task_child_form").show();
					$(".btn_task").removeClass("active");
					$("#"+data['from']).addClass("active");

					var task_source = data['from']; //任务来源
					if(task_source === 'plan'){ //判断当前任务来源
						planFns(data['fromid']);
					}else if(task_source === 'mothertask'){
						mothertaskFns(data['fromid']);
					}
					myScroll.refresh();
				}

				if(data['worktime1']){ //评估工时
					$('#worktime1').val(data['worktime1']);
				}

				if(data['stime']){ //布置时间
					$('#stime').val(data['stime']);
				}

				if(data['etime']){ //完成时间
					$('#etime').val(data['etime']);
				}
				if(data['approveName'] || data['examapp']){ //负责人`只能单选，不需要遍历
					var apprName = (data['approveName'] != "")?data['approveName']:data['examapp'];
					var approveUid = (data['approveUid'] !="") ?data['approveUid']:data['examappUid'];
					var approveUidFace = data['approveUidFace'];
					var examappUidStr = "<span class=\"name\" data-uid="+approveUid+" data-face="+approveUidFace+">"+apprName+"</span>";
					$('#examappUid').empty(); //清空负责人数据区
					$('#examappUid').append(examappUidStr);
				}

				if(data['execmanUid'] && data['execman']){ //负责人`只能单选，不需要遍历
					var presideStr = "<span class=\"name\" data-uid="+data['execmanUid']+" data-face="+data['execmanUidFace']+">"+data['execman']+"</span>";
					$('#preside').empty(); //清空负责人数据区
					$('#preside').append(presideStr);
				}

				if(data['participantUids'] && data['participant']){ //参与人`遍历存到数组或对象
					var participantUidsArr = data['participantUids'].split(','); //参与人id
					var participantArr = data['participant'].split(','); //参与人姓名
					var participantImgArr = data['participantFace'].split(','); //参与人头像
					$('#participant').empty(); //清空参与人数据区
					for (var f = 0; f < participantUidsArr.length; f++) {
						if(f == 0){
							var participantStr = "<span class=\"name\" data-uid="+participantUidsArr[f]+" data-face="+participantImgArr[f]+">"+participantArr[f]+"</span>";
						}else{
							var participantStr = ",<span class=\"name\" data-uid="+participantUidsArr[f]+" data-face="+participantImgArr[f]+">"+participantArr[f]+"</span>";
						}
						$('#participant').append(participantStr);
					};
				}

				if(data['content']){ //任务内容
					$('#content').val(data['content']);
				}

				if(data['files']){ //附件编辑处理
					var filesDiv = $(".files");
					var files = data['files']; //多附件
					var filesInfo = []; //文件信息
					for(var s = 0; s < files.length; s++){
						filesInfo[s] = files[s].split("||")
						var filesSize = ''; //文件大小
						if(filesInfo[s][3] < 1024){
							filesSize = filesInfo[s][3].toFixed(0) + 'B';
						}else if(filesInfo[s][3] < ( 1024 * 1024 )){
							filesSize = (filesInfo[s][3] / 1024).toFixed(0) + 'KB';
						}else if(filesInfo[s][3] < ( 1024 * 1024 * 1024)){
							filesSize = (filesInfo[s][3] / 1024 / 1024).toFixed(1)  + 'M';
						}else{ 
							filesSize = (filesInfo[s][3] / 1024 / 1024 / 1024).toFixed(1) + 'G';
						}//("+filesSize+")
						filesDiv.append("<div class=\"fileuploads\"><div class=\"file-lname\">"+filesInfo[s][1]+"</div> <div class=\"delimg file-del glyphicon glyphicon-remove-sign\" rel=\"\"></div> <input type=\"hidden\" name=\"filesUpload[]\" value=\""+files[s]+"\"></div>");
					}
					myScroll.refresh();
				}

				if(data['prizepunish']){ //奖罚标准
					$('#prizepunish').val(data['prizepunish']);
				}

				if(data['leadersay']){ //领导批示
					$('#leadersay').val(data['leadersay']);
				}
			}
		},'json')
	}

	//路径导航
	$(document).on("touchstart","#btnBack",function(){
		window.location.href='m.php?app=user&func=task&action=default&task=loadPage&from=list';
		return false;
	});
	
	//触发人员选择器
	$(document).on('click','#preside,#participant',function(){
		var source = $(this).attr('data-source') == undefined ? '': $(this).attr('data-source');
		$('#userDataModal').attr('data-belong','freeFlowStepDeal');
		$('#userDataModal').attr('data-source', source);
		var checktype = $(this).attr('data-checktype');
		$('#userDataModal').attr('data-checktype', checktype);
		$('#search').val("");
		$(this).children('.name').each(function(){
			var uid  = $(this).attr('data-uid');
			var name = $(this).text();
			var face = $(this).attr('data-face');
			if(face == undefined){ //默认头像
				face = 'file/common/face/default-face.jpg';
			}
			if(uid != undefined){
				var userObj = {'uid':uid, "name":name, "face":face};
				sendeeArr.push(userObj);
			};
		});
		$('#userDataModal').show();
    	//请求人员数据
		getSelectorData();
	});

	$(document).on('click','#examappUid',function(){
		var source = $(this).attr('data-source') == undefined ? '': $(this).attr('data-source');
		$('#userDataModal').attr('data-belong','freeFlowStepDeal');
		$('#userDataModal').attr('data-source', source);
		var checktype = $(this).attr('data-checktype');
		$('#userDataModal').attr('data-checktype', checktype);
		$('#search').val("");
		$(this).children('.name').each(function(){
			var uid  = $(this).attr('data-uid');
			var name = $(this).text();
			var face = $(this).attr('data-face');
			if(face == undefined){ //默认头像
				face = 'file/common/face/default-face.jpg';
			}
			if(uid != undefined){
				var userObj = {'uid':uid, "name":name, "face":face};
				sendeeArr.push(userObj);
			};
		});

		$('#userDataModal').show();
    	//请求人员数据
		getSelectorData2();
	});


	//请求人员选择器数据
	function getSelectorData(){
		$.ajax({
			dataType: "json",
			type: "get",
			url: "m.php?action=commonJob&task=getSelectorData&target=getUserDatamm",
			beforeSend: function(){
				//提交表单前验证
				// myFns.showLoading();
				showLoading();
			},
			success: function(json){
				hideLoading();
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
							//重新调整页面
							userDataScroll.refresh();  	
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
	//请求人员选择器数据
	function getSelectorData2(){
		$.ajax({
			dataType: "json",
			type: "get",
			url: "m.php?app=user&func=task&action=default&task=getApprovalorFromJson",
			beforeSend: function(){
				//提交表单前验证
				// myFns.showLoading();
				showLoading();
			},
			success: function(json){
				hideLoading();
				if(json.success){ //请求成功
					$('#sback').css('display','none');
					

					var data  	= json.data;
					if(json.status == 0){
						jNotify('不需要审批!',{
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
						$('#userDataModal').hide();
						return false;
					}
					var users 	= data.users; //人员数据
					if(!users){
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
				}
				//已选人员区域操作
				setUserOpts();
				//重新调整页面
				userDataScroll.refresh(); 
			}
		})
	}

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
			beforeSend: function(){
				//提交表单前验证
				showLoading();
			},
			success: function(json){
				hideLoading();
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

	//进入下一级部门
	$(document).on('click','.structList',function(){
		var structId = $(this).attr('data-sid');
		viewUserData(structId);
	})
	
	//返回上一级部门
	$(document).on('touchstart','#sback',function(){
		$('#search').val(''); //清空搜索框
		var structId = $(this).attr('data-fid'); //部门id
		if(structId == 0){
			return false;
		}
		viewUserData(structId);
	})

	//关闭人员选择器
	$(document).on('touchstart','#sClose',function(){
		sendeeArr = []; //切记`关闭选择器时需重置全局数组
		$('#userDataModal').hide();
	});

	//完成人员选择
	$(document).on('click','#sFinish',function(){
		recordData(); //其他通用方式
		sendeeArr = []; //切记`关闭选择器时需重置全局数组
		$('#userDataModal').hide();
	});

	//完成人员选择`把数据记录到指定位置
	function recordData(){ 
		var source = $('#userDataModal').attr('data-source'); //要把数据到的位置
		$('#' + source).empty(); //先清空数据区再遍历数据
		var uids  = ''; //用户id
		var names = ''; //用户名
		var faces = ''; //用户头像
		//把存放在全局数组中的人员数据遍历到指定位置
		var userStr = '';
		for (var i = 0; i < sendeeArr.length; i++) {
			uids  = sendeeArr[i].uid;
			names = sendeeArr[i].name;
			faces = sendeeArr[i].face;
			if(i!=sendeeArr.length-1){
				userStr += '<span class="name" data-uid="'+uids+'" data-face="'+faces+'">'+names+'</span>,';
			}else{
				userStr += '<span class="name" data-uid="'+uids+'" data-face="'+faces+'">'+names+'</span>';
			}
		}
		$('#' + source).append(userStr);
		$('#'+source).next().val(uids); //隐藏input赋值
		myScroll.refresh();
	}

	//按钮被松开时触发搜索人员事件
	$(document).on('keyup','#search',getSearchUserData);

	function getSearchUserData(){
		var search = $('#search').val();
		var isApprovalor = $('#userDataModal').attr('data-source');
		if(search != ''){ 
			if(isApprovalor != 'examappUid'){
				var httpUrl = 'm.php?action=commonJob&task=getSelectorData&target=getUserDatamm&search='+search;
			}else{
				var httpUrl = 'm.php?app=user&func=task&action=default&task=getApprovalorFromJson&search='+search;
			}
		}else{
			if(isApprovalor != 'examappUid'){
				var structId = $('#userDataModal').attr('data-sid');
				var httpUrl = 'm.php?action=commonJob&task=getSelectorData&target=getUserDatamm&structId='+structId;
			}else{
				var httpUrl = 'm.php?app=user&func=task&action=default&task=getApprovalorFromJson';
			}
		}
		$.ajax({
			dataType: "json",
			type: "post",
			url: httpUrl,
			beforeSend: function(){
				//提交表单前验证
				showLoading();
			},
			success: function(json){
				hideLoading();
				$('#userDataGroup').empty();//清空数据区
				if(json.success){
					var data  = json.data;
					var users = data.users; //人员数据
					if(data != ''){
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
		}else if(choiceType == 'job'){ //职位选择器
			$('.'+element+' :checkbox').each(function(){
				var jid = $(this).closest('.'+element).attr("data-jid"); //选择框当前行的员工id
				var _this = $(this);
				if(dataArr.length > 0){
					for (var i = 0; i < dataArr.length; i++) {
						if (dataArr[i] == jid) {
							$(_this).prop("checked", true);
							$(_this).siblings('.checkbox').addClass('cur');
							break;
						}
					}
				}
			})
		}else if(choiceType == 'station'){ //岗位选择器
			$('.'+element+' :checkbox').each(function(){
				var sid = $(this).closest('.'+element).attr("data-sid"); //选择框当前行的员工id
				var _this = $(this);
				if(dataArr.length > 0){
					for (var i = 0; i < dataArr.length; i++) {
						if (dataArr[i] == sid) {
							$(_this).prop("checked", true);
							$(_this).siblings('.checkbox').addClass('cur');
							break;
						}
					}
				}
			})
		}else if(choiceType == 'dept'){ //部门选择器
			$('.'+element+' :checkbox').each(function(){
				var did = $(this).closest('.'+element).attr("data-did"); //选择框当前行的员工id
				var _this = $(this);
				if(dataArr.length > 0){
					for (var i = 0; i < dataArr.length; i++) {
						if (dataArr[i] == did) {
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

	//checkbox扣选
	$(document).on('click','.userList',function(){
		//触发事件只能单选
		var source = $("#userDataModal").attr("data-source"); //请求来源 请求来源可以是通过ID或者class来获得出处
		var checktype = $("#"+source).attr('data-checktype'); //checkbox类型：false单选 、true多选 
		if(checktype == "false" || !checktype){ //checktype：false`单选  true`多选
			checkOnly(this, 'userList');
		}
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

  //点击删除人员区域头像
  $(document).on('click','.delFace',function(){
  	var uid = $(this).attr('data-uid');
  	var userListId = 'uid-' + uid;
  	$('#' + userListId).find('.ipt-hide').prop('checked', false);
  	$('#' + userListId).find('.checkbox').removeClass('cur');
  	$(this).remove(); //删除当前用户头像
  	for (var i = 0; i < sendeeArr.length; i++) {
			if (sendeeArr[i].uid == uid) {
				sendeeArr.splice(i, 1); //删除全局数组中存放着的用户
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

	/**
	 *	方法: checkOnly(obj,element)
	 *	功能: 限制只能单选
	 *	参数: <Object> obj 当前鼠标点击的HTML元素对象 <string> element 需要遍历的元素
	 */
	function checkOnly(obj, element){
    $('.'+element).each(function(){
      if (this != obj){
        $(this).find('.checkbox').removeClass('cur');
        $(this).find('.ipt-hide').prop('checked', false)
      }else{
        if($(this).find('.ipt-hide').prop('checked')){
          $(this).find('.ipt-hide').prop('checked', true)
          $(this).find('.checkbox').addClass('cur');
        }else{
          $(this).find('.checkbox').removeClass('cur');
      		$(this).find('.ipt-hide').prop('checked',false)
        }
      }
    })
	}

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
						sendeeArr.splice(i, 1);
					}
				}
			}
		}else{ //只允许单选
			if(chkStu){ //扣选
				sendeeArr = [];
				sendeeArr.push(userObj);
			}else{ 
				sendeeArr = [];
			}
		}
	}
	//添加任务
	function addTask(filesUpload){
		var execmanUid = $('#preside .name').attr('data-uid'); //负责人id
		var participantUids = '';
		$('#participant .name').each(function(){ //参与人id
			participantUids += $(this).attr('data-uid') + ',';
		})
		var filesUpload = filesUpload; //文件上传信息
		var title = $('#title').val(); //标题
		var from = $('.btn_task.active').attr("id"); //任务来源
		var fromid = $('#task_child').val(); //子部门和子计划
		var worktime1 = $('#worktime1').val(); //评估工时
		var stime = $('#stime').val(); //布置日期
		var etime = $('#etime').val(); //结束日期
		var examappUid = $('#examappUid .name').attr('data-uid'); //审批人
		var approveUid = examappUid; //审批人
		if(examappUid != undefined){ //是否需要审批
			var approveUid = examappUid; //审批人
			var needexamapp = 'on'; //需要审批
		}
		var content = $('#content').val(); //布置内容
		var prizepunish = $('#prizepunish').val(); //奖罚标准
		var leadersay = $('#leadersay').val(); //领导批示
		var task = 'add'; //添加类型
		$.ajax({
			dataType: "json",
			type: "post",
			url: "m.php?app=user&func=task&action=default",
			data:{
				'execmanUid':execmanUid, //负责人id
				'participantUids':participantUids, //参与人id
				'title':title, //标题
				'from':from, //任务来源
				'fromid':fromid, //子部门和子计划
				'worktime1':worktime1, //评估工时
				'stime':stime, //布置日期
				'etime':etime, //结束日期
				'examappUid':examappUid, //审批人
				'approveUid':approveUid, //审批人
				'needexamapp':needexamapp, //是否需要审批
				'content':content, //布置内容
				'prizepunish':prizepunish, //奖罚标准
				'leadersay':leadersay, //领导批示
				'task':task, //添加类型
				'filesUpload':filesUpload //文件上传信息
			},
			beforeSend: function(){
				//提交表单前验证
				// myFns.showLoading("正在提交，请稍等...");
				showLoading();
			},
			success: function(json){
				// $(".ui-ios-overlay").removeClass("ios-overlay-show").addClass("ios-overlay-hide");
				hideLoading();
				//提交成功后调用
				if(json.success){
					// iosOverlay({
					// 	text: json.msg,
					// 	duration: 2e3,
					// 	icon: "../../../../../../resources/images/m/artDialog/check.png"
					// });
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
					// iosOverlay({
					// 	text: json.msg,
					// 	duration: 2e3,
					// 	icon: "../../../../../../resources/images/m/artDialog/cross.png"
					// });
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
				if(json.msg == "操作成功"){
					setTimeout(function(){//请求完成延迟刷新
						window.location.href='m.php?app=user&func=task&action=default&task=loadPage&from=list&ts=1'; //请求任务列表
					},200);
				}
				myScroll.refresh();
			}
			// complete: function(){
				//请求完成之后调用
				// setTimeout(function(){//请求完成延迟刷新
				// 	window.location.reload();
				// },2000)
			// }
		})
	}

	//修改任务
	function editTask(filesUpload){
		var tid = myFns.getUrlValue('tid'); //任务id
		var execmanUid = $('#preside .name').attr('data-uid'); //负责人id
		var participantUids = '';
		$('#participant .name').each(function(){ //参与人id
			participantUids += $(this).attr('data-uid') + ',';
		})
		var filesUpload = filesUpload; //文件上传信息
		var title = $('#title').val(); //标题
		var from = $('.btn_task.active').attr("id"); //任务来源
		var fromid = $('#task_child').val(); //子部门和子计划
		var worktime1 = $('#worktime1').val(); //评估工时
		var stime = $('#stime').val(); //布置日期
		var etime = $('#etime').val(); //结束日期
		var examappUid =  $('#examappUid .name').attr('data-uid'); //审批人
		

		if(examappUid != undefined){ //是否需要审批
			var approveUid = examappUid; //审批人
			var needexamapp = 'on'; //需要审批
		}
		var content = $('#content').val(); //布置内容
		var prizepunish = $('#prizepunish').val(); //奖罚标准
		var leadersay = $('#leadersay').val(); //领导批示
		var task = 'edit'; //添加类型

		$.ajax({
			dataType: 'json',
			type: 'post',
			url: 'm.php?app=user&func=task&action=default',
			data: {
				'tid':tid, //任务id
				'execmanUid':execmanUid, //负责人id
				'participantUids':participantUids, //参与人id
				'title':title, //标题
				'from':from, //任务来源
				'fromid':fromid, //子部门和子计划
				'worktime1':worktime1, //评估工时
				'stime':stime, //布置日期
				'etime':etime, //结束日期 
				'examappUid':examappUid, //审批人
				'approveUid':approveUid, //审批人
				'needexamapp':needexamapp, //是否需要审批
				'content':content, //布置内容
				'prizepunish':prizepunish, //奖罚标准
				'leadersay':leadersay, //领导批示
				'task':task, //添加类型
				'filesUpload':filesUpload //文件上传信息
			}, //参数
			beforeSend: function(){
				//提交表单前验证
				// myFns.showLoading("正在提交，请稍等...");
				showLoading();
			},
			success: function(json){
				// $(".ui-ios-overlay").removeClass("ios-overlay-show").addClass("ios-overlay-hide");
				hideLoading();
				//提交成功后调用
				if(json.success){
					// iosOverlay({
					// 	text: json.msg,
					// 	duration: 2e3,
					// 	icon: "../../../../../../resources/images/m/artDialog/check.png"
					// });
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
					// iosOverlay({
					// 	text: json.msg,
					// 	duration: 2e3,
					// 	icon: "../../../../../../resources/images/m/artDialog/cross.png"
					// });
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
				if(json.msg == "操作成功"){
					setTimeout(function(){//请求完成延迟刷新
						window.location.href='m.php?app=user&func=task&action=default&task=loadPage&from=list'; //请求任务列表
					},200);
				}
				myScroll.refresh();
			}
			// complete: function(){
				//请求完成之后调用
				// setTimeout(function(){//请求完成延迟刷新
				// 	window.location.href='m.php?app=user&func=task&action=default&task=loadPage&from=tasklist'; //请求任务列表
				// },2000)
			// }
		})
	}

	//布置任务事件
	$(document).on("click","#btnSave",function(){
		var filesUpload = [];
		$("input[name='filesUpload[]']").each(function(i){
			filesUpload[i] = $(this).val();
		})
		var form = $('#addedit_f').attr('data-form'); //表单类型
		if(form == 'edit'){ //编辑任务
			editTask(filesUpload);
		}else{ //添加任务
			addTask(filesUpload);
		}
	})

	//初始化上传附件表单
	$(document).on('touchstart','#fileupload',function(){
		var myUpload = $("#myUpload");
		var url = 'm.php?app=user&func=task&action=default&task=fileUpload';
		if(!myUpload.length){//判断表单form是否存在
			$("#fileupload").wrap("<form id='myUpload' action='' method='post' enctype='multipart/form-data'></form>"); 
			$("#myUpload").attr("action",url);
		}
	})

	//file改变上传文件
	$(document).on('change','#fileupload',function(){
		if($('.fileuploads').length >= 5){
			// iosOverlay({
			// 	text: '不能超过5个文件',
			// 	duration: 2e3,
			// 	icon: "../../../../../../resources/images/m/artDialog/cross.png"
			// });
			jNotify('不能超过5个文件',{
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
		var filePath = $('#fileupload').val();
		if(!filePath){
			return false;
		}
		var filename = filePath.replace(/.*(\/|\\)/, ""); //文件名带后缀
		var fileSplit = (/[.]/.exec(filename)) ? /[^.]+$/.exec(filename.toLowerCase()) : ''; //文件后缀fileExt[0]
		var fileArr = ['jpg','jpeg','gif','png','bmp','rar','zip','doc','wps','wpt','ppt','xls','txt','csv','et','ett','pdf'];
		var fileExt = fileSplit[0].toLowerCase(); //文件后缀名转小写
		if(!myFns.in_array(fileArr,fileExt)){
			// iosOverlay({
			// 	text: '不支持此文件类型',
			// 	duration: 2e3,
			// 	icon: "../../../../../../resources/images/m/artDialog/cross.png"
			// });
			jNotify('不支持此文件类型',{
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
		var files = $(".files");
    	var btnUpload = $(".btnUpload span");
    	var progress = $(".progress");
    	var bar = $(".bar");
    	var percent = $(".percent");
    	$("#myUpload").ajaxSubmit({
            dataType:  'json',
            beforeSend: function() {
                progress.show(); //显示进度条 
                var percentVal = '0%'; //开始进度为0% 
                bar.width(percentVal); //进度条的宽度 
                percent.html(percentVal); //显示进度为0% 
                btnUpload.html("上传中...");
                $('#fileupload').attr('disabled',"true"); //添加disabled属性 
            },
            uploadProgress: function(event, position, total, percentComplete) { 
                var percentVal = percentComplete + '%'; //获得进度 
                bar.width(percentVal); //上传进度条宽度变宽 
                percent.html(percentVal); //显示上传进度百分比 
            },
            success: function(data){
            	if(data == 2){ //不支持文件类型
            		$('#fileupload').removeAttr("disabled"); //移除disabled属性 
	                bar.width('0'); 
	                progress.css('display','none');
	                btnUpload.html("添加附件");
     //        		iosOverlay({
					// 	text: '不支持此文件类型',
					// 	duration: 2e3,
					// 	icon: "../../../../../../resources/images/m/artDialog/cross.png"
					// });
					jNotify('不支持此文件类型',{
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
            	var filesUploadData = '||'+data.name+'||'+data.tmp_name+'||'+data.size;
            	var filesSize = ''; //文件大小
				if(data.size < 1024){
					filesSize = data.size.toFixed(0) + 'B';
				}else if(data.size < ( 1024 * 1024 )){
					filesSize = (data.size / 1024).toFixed(0) + 'KB';
				}else if(data.size < ( 1024 * 1024 * 1024)){
					filesSize = (data.size / 1024 / 1024).toFixed(1)  + 'M';
				}else{ 
					filesSize = (data.size / 1024 / 1024 / 1024).toFixed(1) + 'G';//("+filesSize+")
				}
                files.append("<div class=\"fileuploads\"><div class=\"file-lname\">"+data.name+"</div> <div class=\"delimg file-del glyphicon glyphicon-remove-sign\" rel=\""+data.tmp_name+"\"></div> <input type=\"hidden\" name=\"filesUpload[]\" value=\""+filesUploadData+"\"></div>");                
                btnUpload.html("添加附件");
                $('#fileupload').removeAttr("disabled"); //移除disabled属性 
            	myScroll.refresh();
            },
            error:function(xhr){
                btnUpload.html("上传失败");
                $('#fileupload').removeAttr("disabled"); //移除disabled属性 
                bar.width('0'); 
                progress.css('display','none');
    //             iosOverlay({
				// 	text: xhr.responseText,
				// 	duration: 2e3,
				// 	icon: "../../../../../../resources/images/m/artDialog/cross.png"
				// });
				jError(xhr.responseText,{
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
        });
	})

	//删除不想上传的附件
	$(document).on('click','.delimg',function(){
		var tmp_name = $(this).attr("rel"); //当前服务器上临时文件
		var delimg = $(this);
		$.post("m.php?app=user&func=task&action=default&task=fileUpload&act=delFile",{tmp_name:tmp_name},function(msg){
            delimg.closest('.fileuploads').remove();
            $(".progress").css('display','none');
            myScroll.refresh();
        });
	});

})

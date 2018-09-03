
//判断当前客户端Android和ios
if(/android/ig.test(navigator.userAgent)){ //安卓
	var userAgent = 'android';
}else{ //ios或者其他
	var userAgent = 'ios';
}

//全局变量`
var userDataScroll, stationDataScroll; //全局对象和数组
	//myScroll.refresh(); //数据加载完成后调用界面更新方法

//全局变量`函数
var myFns = {
	/*
	 *	方法: myFns.loaded()
	 *	功能: 初始化iScroll控件
	 */
	loaded:function(){
		myScroll = new IScroll('#wrapper', {
			scrollbars: false, //隐藏滚动条
			zoom: true, //缩放功能
	    mouseWheel: true,
	    wheelAction: 'zoom',
			preventDefault: false, //阻止默认事件
			preventDefaultException: { tagName: /^(INPUT|TEXTAREA|BUTTON|SELECT|A)$/ } //这个后面加|A,因为iscroll阻止了A的默认事件
		});
		document.addEventListener('touchmove', function (e) { e.preventDefault(); }, false);
	},
	/**
	 *	方法: myFns.userDataLoaded()
	 *	功能: 初初始化人员选择器用户部门iScroll控件
	 */
	userDataLoaded: function(){
		userDataScroll = new IScroll('#userDataWrapper', {
			scrollbars: false,
			click: true
		});
		document.addEventListener('touchmove', function (e) { e.preventDefault(); }, false);
	},
	/**
	 *	方法: myFns.stationLoaded()
	 *	功能: 初始化岗位选择器iScroll控件
	 */
	stationLoaded: function(){
		stationDataScroll = new IScroll('#stationDataWrapper', {
			scrollbars: false,
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
	}
}

$(function(){

	//跳转更多操作
	$(document).on("touchstart","#addUser",function(){
		window.location.href = 'm.php?app=user&func=addressbook&action=default&task=loadPage&from=moreOpt';
	})

    //跳转到我的通讯录
	$(document).on('click','#my',function(){
		window.location.href = 'm.php?app=user&func=addressbook&action=default&task=loadPage&from=my';
	}); 

   //跳转到内部通讯录
	$(document).on('click','#dept',function(){
		window.location.href = 'm.php?app=user&func=addressbook&action=default&task=loadPage&from=dept';
	})

	//跳转到公共通讯录
	$(document).on('click','#common',function(){
		window.location.href = 'm.php?app=user&func=addressbook&action=default&task=loadPage&from=common';
	});

	//跳转到组织架构
	$(document).on('click','#organization',function(){
		window.location.href = 'm.php?app=user&func=addressbook&action=default&task=loadPage&from=organization';
	});

	function loadSelectorData(){
		var source = $(this).attr('data-name'); //把数据追加到哪里
		$("#userDataModal").attr("data-source",source); //请求来源
		var checktype = $(this).attr('data-checktype'); //chk类型
		$('#userDataModal').attr('data-checktype', checktype); //记录chk类型
		$('#'+source+' .name').each(function(){
			var uid = $(this).attr("data-uid") //员工id
			var name = $(this).html(); //员工名字
			if(uid != ''){
				sendeeObj[uid] = name; //存放在一个全局对象中
			}
		})
		//请求人员数据
		getSelectorData();
	}

	loadSelectorData();

	//功能: 请求人员选择器数据
	function getSelectorData(){
		$.ajax({
			dataType: "json",
			type: "get",
			url: "m.php?action=commonJob&task=getSelectorData&target=getUserDatamm",
			success: function(json){
				hideLoading();
				if(json.success){ //请求成功
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
						})
						return false;
					} 
					$('#btnBack').attr('data-fid',json.paterId); //有数据才改变上级部门id
					$('#userDataGroup').empty();//清空数据区
					if(users.length > 0){ //处理人员数据
						$.each(users, function(i,arr){
							var userStr  = '<li class="list-group-item userList" id="uid-'+arr['uid']+'" data-uid="'+arr['uid']+'" data-name="'+arr['truename']+'" data-face="'+arr['face']+'">';
								userStr += '<div class="name-box">';
								userStr += '<img src="'+arr['face']+'" class="img-circle face">';
								userStr += '<span class="truename">'+arr['truename']+'</span>';
								userStr += '<span class="structName">'+arr['structName']+'</span>';
								userStr += '</div>';
								userStr += '<input type="hidden" class="mobile" value="'+arr['mobile']+'">';
								userStr += '<input type="hidden" class="email" value="'+arr['email']+'">';
								userStr += '<input type="hidden" class="workphone" value="'+arr['workphone']+'">';
								userStr += '<input type="hidden" class="address" value="'+arr['address']+'">';
								userStr += '<input type="hidden" class="personSign" value="'+arr['personSign']+'">';
								userStr += '<input type="hidden" class="jobName" value="'+arr['jobName']+'">';
								userStr += '<div class="fs1 struct-ico-right" aria-hidden="true" data-icon="5"></div>';
								userStr += '</li>';
							$('#userDataGroup').append(userStr);
						}) 
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

				//重新调整页面
				userDataScroll.refresh(); 
			}
		})
	}
	
	//网页初始化信息
	function loadedInit(){
		//页面加载完成调用`初始化页面
		myFns.loaded(); 
		//动态改变HTML元素属性值
		myFns.userDataLoaded(); //人员和部门区域
		var userDataWrapperH = $(window).height()-97;
		$('.userDataWrapper').css('height', userDataWrapperH); //人员和部门区域高度
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
		//岗位选择器
		myFns.stationLoaded();
		$('#stationDataWrapper').css('height', $(window).height()-52);
		//其他弹窗
		$('#other-wrapper').css('height', $(window).height()-52);
	}

	//初始化信息
	loadedInit();

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
				showLoading();
			},
			success: function(json){
				hideLoading();
				$('#userDataGroup').empty();//清空数据区
				if(json.success){
					var data  = json.data;
					var users = data.users; //人员数据
					if(users.length > 0){
						$.each(users,function(i, arr){
							var userStr  = '<li class="list-group-item userList" id="uid-'+arr['uid']+'" data-uid="'+arr['uid']+'" data-name="'+arr['truename']+'" data-face="'+arr['face']+'">';
								userStr += '<div class="name-box">';
								userStr += '<img src="'+arr['face']+'" class="img-circle face">';
								userStr += '<span class="truename">'+arr['truename']+'</span>';
								userStr += '<span class="structName">'+arr['structName']+'</span>';
								userStr += '</div>';
								userStr += '<input type="hidden" class="mobile" value="'+arr['mobile']+'">';
								userStr += '<input type="hidden" class="email" value="'+arr['email']+'">';
								userStr += '<input type="hidden" class="workphone" value="'+arr['workphone']+'">';
								userStr += '<input type="hidden" class="address" value="'+arr['address']+'">';
								userStr += '<input type="hidden" class="personSign" value="'+arr['personSign']+'">';
								userStr += '<input type="hidden" class="jobName" value="'+arr['jobName']+'">';
								userStr += '<div class="fs1 struct-ico-right" aria-hidden="true" data-icon="5"></div>';
								userStr += '</li>';
							$('#userDataGroup').append(userStr);
						})
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
				// setUserOpts();

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
					if(json.msg != 0){
						$('#userDataModal').attr('data-sid', json.msg);
					}else{
						if(json.paterId == 1){
							$('#btnBack').css('display','block');
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
					$('#btnBack').attr('data-fid',json.paterId); //有数据才改变上级部门id
					$('#userDataGroup').empty();//清空数据区
					if(users.length > 0){ //处理人员数据
						$.each(users, function(i,arr){
							var userStr  = '<li class="list-group-item userList" id="uid-'+arr['uid']+'" data-uid="'+arr['uid']+'" data-name="'+arr['truename']+'" data-face="'+arr['face']+'">';
								userStr += '<div class="name-box">';
								userStr += '<img src="'+arr['face']+'" class="img-circle face">';
								userStr += '<span class="truename">'+arr['truename']+'</span>';
								userStr += '<span class="structName">'+arr['structName']+'</span>';
								userStr += '</div>';
								userStr += '<input type="hidden" class="mobile" value="'+arr['mobile']+'">';
								userStr += '<input type="hidden" class="email" value="'+arr['email']+'">';
								userStr += '<input type="hidden" class="workphone" value="'+arr['workphone']+'">';
								userStr += '<input type="hidden" class="address" value="'+arr['address']+'">';
								userStr += '<input type="hidden" class="personSign" value="'+arr['personSign']+'">';
								userStr += '<input type="hidden" class="jobName" value="'+arr['jobName']+'">';
								userStr += '<div class="fs1 struct-ico-right" aria-hidden="true" data-icon="5"></div>';
								userStr += '</li>';
							$('#userDataGroup').append(userStr);
						}) 
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
	$(document).on('touchstart','#btnBack',function(){
		$('#search').val(''); //清空搜索框
		var structId = $(this).attr('data-fid'); //部门id
		if(structId == 0){
			window.location.href = 'm.php?app=user&func=addressbook&action=default&task=loadPage&from=list';
			return false;
		}
		viewUserData(structId);
	})

	//点击用户
	$(document).on('click','.userList',function(){
		//获取手机、邮箱、工作电话、地址、个性签名、职位、头像、姓名
		var mobile 		= $(this).find('.mobile').val(),
			email 		= $(this).find('.email').val(),	
			workphone 	= $(this).find('.workphone').val(),	
			address 	= $(this).find('.address').val(),	
			personSign 	= $(this).find('.personSign').val(),
			jobName 	= $(this).find('.jobName').val(),
			user_name 	= $(this).find('.truename').html(),
			user_face	= $(this).find('img').attr('src');
			personSign = personSign ? personSign : '未添加签名';
			$('.user_name').html(user_name);								
			$('.user_grouping').html(jobName);
			$('.about_user_comphone .about_value').html(workphone);
			$('.about_user_company .about_value').html(address);
			$('.about_user_mobile .about_value').html(mobile);
			$('.about_user_email .about_value').html(email);
			$('.user_personSign').html(personSign);
			$('.user_face img').attr('src',user_face);

		//用户详情列表
		$('#cbp-spmenu').addClass('cbp-spmenu-open');	//展开隐藏详情列表
		$('.masking').css('display','block');			//显示遮罩层
		$('#wrapper').css('overflow-y','hidden');		//内容滚动条禁用
	})
	
	//点击快速拨号
	$(document).on('touchstart','.relat_call', function(){
		var userInfo   	= [];
			userInfo[0] = $(this).parent().prev().find('.about_user_mobile .about_value').html(),
    		userInfo[1]	= $(this).parents('#cbp-spmenu').find('.user_name').html(),
    		userInfo[2]	= $(this).parents('#cbp-spmenu').find('.user_grouping').html(),
    		str 		= $(this).parents('#cbp-spmenu').find('.user_face img').attr('src'),
    		n 			= str.lastIndexOf("/"),
    		userInfo[3] = str.substring(n+1);

		if (userInfo[0]) {
			$(this).children().attr('href',"tel:"+userInfo[0]);
			var url = 'm.php?app=user&func=addressbook&action=default&task=loadPage&from=list&task=addCall';
			$.post(url,{'userInfo':userInfo},function(json){
				return false;
			},'json')
		} else {
			alert('号码不存在')
		}
	})

	//点击发信息
	$(document).on('touchstart','.relat_note', function(){
		var user_class = $(this).parent().prev(),
			mobile = user_class.find('.about_user_mobile .about_value').html();
		if (mobile) {
			$(this).children().attr('href',"sms:"+mobile);
		} else {
			alert('号码不存在')
		}
	})

	//点击返回关闭遮罩层隐藏用户详情
	$(document).on('touchend','.btn_back',function(){
		$('#cbp-spmenu').removeClass('cbp-spmenu-open');
		$('.masking').css('display','none');
		$('#wrapper').css('overflow-y','scroll');
	})

	//点击遮罩层隐藏用户详情
	$(document).on('touchstart','.masking',function(){
		$('#cbp-spmenu').removeClass('cbp-spmenu-open');
		$('.masking').css('display','none');
		$('#wrapper').css('overflow-y','scroll');
		return false;
	})

	// 点击用户详情的手机号码
	$(document).on('touchstart','.about_user_mobile',function(){
		var userInfo   	= [];
			userInfo[0] = $(this).find('.about_value').html(),
    		userInfo[1]	= $(this).parent().prev().find('.user_name').html(),
    		userInfo[2]	= $(this).parent().prev().find('.user_grouping').html(),
    		str 		= $(this).parent().prev().find('.user_face img').attr('src'),
    		n 			= str.lastIndexOf("/"),
    		userInfo[3] = str.substring(n+1);
    	if (userInfo[0]) {
			var url = 'm.php?app=user&func=addressbook&action=default&task=loadPage&from=list&task=addCall';
			$.post(url,{'userInfo':userInfo},function(json){
				return false;
			},'json')
			$(this).children('.about_value').attr('href',"tel:"+userInfo[0]);
    	}
	})

	// 点击用户详情的单位号码
	$(document).on('touchstart','.about_user_comphone',function(){
		var userInfo   	= [];
			userInfo[0] = $(this).find('.about_value').html(),
    		userInfo[1]	= $(this).parent().prev().find('.user_name').html(),
    		userInfo[2]	= $(this).parent().prev().find('.user_grouping').html(),
    		str 		= $(this).parent().prev().find('.user_face img').attr('src'),
    		n 			= str.lastIndexOf("/"),
    		userInfo[3] = str.substring(n+1);
    	if (userInfo[0]) {
			var url = 'm.php?app=user&func=addressbook&action=default&task=loadPage&from=list&task=addCall';
			$.post(url,{'userInfo':userInfo},function(json){
				return false;
			},'json')
			$(this).children('.about_value').attr('href',"tel:"+userInfo[0]);
    	}
	})

	// 点击用户详情的邮箱
	$(document).on('touchstart','.about_user_email',function(){
		var email = $(this).find('.about_value').html();
		$(this).children('.about_value').attr('href',"mailto:"+email);
	})

})
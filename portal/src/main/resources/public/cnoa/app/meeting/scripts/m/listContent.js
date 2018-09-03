
//判断当前客户端Android和ios
if(/android/ig.test(navigator.userAgent)){ //安卓
	var userAgent = 'android';
}else{ //ios或者其他
	var userAgent = 'ios';
} 

var userScroll,sendeeArr=[];

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
	 *	方法: myFns.is_images(ext)
	 *	功能: 判断一个值是否在数组中 图片
	 *	参数: <string> ext 文件后缀 
	 *	返回: <boolean> 返回true false
	 */
	is_images:function(ext){
		var ext = ext.toLowerCase(); //大写转小写
		var view = ['jpg','jpeg','png','gif','bmp']; //ios支持在线查看类型
		if($.inArray(ext,view) < 0){
			return false;
		}
		return true;
	},

	/**
	 *	方法: myFns.is_type(type)
	 *	功能: 判断该类型是否在数组中
	 *	返回: <boolean> 返回true false
	 */
	 is_type: function(type){
	 	var value = ['waiting','pass'];
	 	if($.inArray(type,value) < 0){
			return false;
		}
		return true;
	 }
}

$(function(){

	var aid = myFns.getUrlValue('aid');
	var type = myFns.getUrlValue('type'); //类型

	// 如果是待审核类型
	if (myFns.is_type(type)) {
		$('#listNav').hide();
		$('#markdetail').attr('disabled','true');
		$('#username').attr('data-target','');
	}

	load_data("m.php?app=meeting&func=mgr&action=join&task=loadMarkFormData");

	// 获取信息
	function load_data(url){
		$.ajax({
			dataType: "json",
			type: "post",
			url: url,
			data:{
				"aid":aid
			},
			success: function(json){
				if (json.success) {
					$('#markdetail').val(json.data.markdetail);
					$('.name').attr('data-uid',json.data.jiyaouid);
					$('.name').html(json.data.jiyaoman);
					if (type == 'returnback') {
						$('#opinion').val(json.data.opinion);
					} else {
						$('.opinion').hide();
					}
					//附件信息
					if(json.data.markattach.length > 0 ){
						if (!$('#attachGroup').length) {
							var attachGroupStr = '<div id="attachGroup"><div class="row formInfoTitle"><span>附件信息</span></div></div>';
							$('#scroller').append(attachGroupStr);
						};
						$.each(json.data.markattach,function(index,array){
							var strAttach  = '<div class="panel panel-default panel-attach-box" data-attachid="'+array.attachid+'">';
								strAttach += '<div class="panel-heading" data-name="'+array['name']+'" data-size="'+array['size']+'" data-ext="'+array['ext']+'">';
								strAttach += '<span class="fileName">'+array.name+'</span>';
								strAttach += '<div class="panel-attach">';
								strAttach += '<botton type="botton" class="btn btn-info btn-xs btnBrowse" data-src="'+array['url']+'">浏览</botton>';
								strAttach += '<botton type="botton" class="btn btn-info btn-xs btnFileView" data-src="'+array['url']+'">查看</botton>';
								strAttach += '<botton type="botton" class="btn btn-success btn-xs btnDownload" data-src="'+array['url']+'">下载</botton>';
								if (!myFns.is_type(type)) {
									strAttach += '<botton type="botton" class="btn btn-danger btn-xs btnDel" data-src="'+array['url']+'">删除</botton>';
								}
								strAttach += '<input type="hidden" name="'+'attachid_'+array.attachid+'" value="1">'
								strAttach += '</div></div></div>';
							$('#attachGroup').append(strAttach);
							if(userAgent == 'android' && !myFns.is_images(array['ext'])){
								$('.btnBrowse:last').hide();
								$('.btnFileView:last').hide();
								$('.btnDownload:last').show();
							}if(myFns.is_images(array['ext'])){
								$('.btnBrowse:last').hide();
								$('.btnFileView:last').show();
								$('.btnDownload:last').hide();
							}
							if(userAgent === 'ios'){ //IOS端不提供下载
								$('.btnDownload:last').hide();
							}
							var fileExt = ['doc','docx','ppt','pptx','xls','xlsx','txt','pdf'];
							if(userAgent == 'ios'){
								if($.inArray(array['ext'].toLocaleLowerCase(), fileExt) == -1){
									$('.btnFileView:last').hide();
									$('.btnBrowse:last').show();
									$('.btnDownload:last').hide();
								}else{
									$('.btnFileView:last').show();
									$('.btnDownload:last').hide();
									$('.btnBrowse:last').hide();
								}
							}
						})
					}

					//初始化图片缩放控件
					ImagesZoom.init({
				    	"elem": ".btnBrowse"
					});
				}
			},
			complete: function(XMLHttpRequest, textStatus){
				$(".ui-ios-overlay").removeClass("ios-overlay-show").addClass("ios-overlay-hide");
			}
		})
	}


	//网页初始化信息
	function loadedInit(){
		//页面加载完成调用`初始化页面
		myFns.loaded(); 

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

	// 点击暂存纪要信息
	$(document).on('touchstart','#temporary',function(){
		// 纪要员ID
		var jiyaouid = $('.name').attr('data-uid');
		// 纪要内容
		var markdetail = $('#markdetail').val();
		if (!markdetail || !jiyaouid) {
			var msg =  markdetail ? '会议纪要员' : '纪要内容';
			jError(msg+'不能为空！',{
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
		};
		//附件信息
		var upload_attach = '';
		$("input[name='filesUpload[]']").each(function(i){
			if(i==0){
				upload_attach += '0' + $(this).val();
			}else{
				upload_attach += ',' + '0' + $(this).val();
			}
		})

		// 提交表单
		$("#myTableFrom").ajaxSubmit({
			dataType: "json",
			type: "post",
			data: {
				aid: aid,
				jiyaouid: jiyaouid,
				markdetail: markdetail,
				filesUpload: upload_attach
			},
			url: "m.php?app=meeting&func=mgr&action=join&task=onceMark",
			beforeSend: function(){
				//提交表单前验证
				showLoading();
			},
			success: function(json){
				hideLoading();
				if(json.success){
					jSuccess('操作成功!',{
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
					$('#jingle_popup').css('display','none');
					$('#jingle_popup_mask').css('display','none');
					setTimeout(function(){
						if (type) {
							window.location.href = 'm.php?app=meeting&func=mgr&action=join&task=loadPage&from='+type;
						} else {
						window.location.href = 'm.php?app=meeting&func=mgr&action=meeting&task=loadPage&from=list';
						}
					},1400)
				}else{
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
			}
		})
	})


	//请求人员选择器数据
	function getSelectorData(){
		$.ajax({
			dataType: "json",
			type: "get",
			url: "m.php?action=commonJob&task=getSelectorData&target=getUserDatamm",
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
		//触发事件只能单选
		var checktype = false;
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
		if(chkStu){ //扣选
			sendeeArr = [];
			sendeeArr.push(userObj);
		}else{ 
			sendeeArr = [];
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

	//底部添加计划
	$(document).on('touchstart','#listNav',function(){
	    $('#jingle_popup').slideDown(100);
	    $('#jingle_popup_mask').show();
	    return false;
	});

	// 取消按钮
	$(document).on('touchstart','.btn-cancel',function(){
	    $('#jingle_popup').slideUp(100);
	    $('#jingle_popup_mask').hide();
	    return false;
	});

	// 返回上一步
	$(document).on('touchstart','#btnBack',function(){
		if (type) {
			window.location.href = 'm.php?app=meeting&func=mgr&action=join&task=loadPage&from='+type;
		} else {
	    	window.location.href = 'm.php?app=meeting&func=mgr&action=meeting&task=loadPage&from=list';
		}
	    return false;
	});

	//添加附件`初始化上传附件表单
	$(document).on('click','#fileupload',function(){
		$('#jingle_popup').css('display','none');
   		$('#jingle_popup_mask').css('display','none');
		var myUpload = $("#myUpload");
		var url = 'm.php?action=commonJob&task=upFile';
		if(!myUpload.length){//判断表单form是否存在
			$("#fileupload").wrap("<form id='myUpload' action='' method='post' enctype='multipart/form-data'></form>"); 
			$("#myUpload").attr("action",url);
		}
	});

	//file改变上传文件`附件
	$(document).on('change','#fileupload',function(){
		var filePath = $('#fileupload').val();
		if(!filePath){
			return false;
		}
		var filename  = filePath.replace(/.*(\/|\\)/, ""); //文件名带后缀
		var fileSplit = (/[.]/.exec(filename)) ? /[^.]+$/.exec(filename.toLowerCase()) : ''; //文件后缀fileExt[0]
		var fileArr 	= ['jpg','jpeg','gif','png','bmp','rar','zip','doc','wps','wpt','ppt','xls','txt','csv','et','ett','pdf'];
		var fileExt 	= fileSplit[0].toLowerCase(); //文件后缀名转小写
		if($.inArray(fileExt,fileArr) < 0){
			jError('不支持上传此类型文件!',{
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

		$("#myUpload").ajaxSubmit({
		    dataType:  'json',
		    beforeSend: function() {
		      	showLoading();
		        $('#fileupload').attr('disabled',"true"); //添加disabled属性 
		    },
		    success: function(json){
		      	hideLoading();
		      	$('#fileupload').removeAttr("disabled"); //移除disabled属性 
		      	if(json.success){ 
					if(!$('#attachGroup').length){
						var attachGroupStr = '<div id="attachGroup"><div class="row formInfoTitle"><span>附件信息</span></div></div>';
						$('#scroller').append(attachGroupStr);
					}
					var attachGroup = $('#attachGroup');
		      		var data = json.msg;
		      		var filesUploadData = '||'+data.oldname+'||'+data.name+'||'+data.size;
		      		var fileBox  = '<div class="panel panel-default panel-attach-box">';
		      			fileBox += '<div class="panel-heading">';
		      			fileBox += ''+data.oldname+'';
  						fileBox += '<div class="panel-attach">';
  						fileBox += '<botton type="botton" class="btn btn-danger btn-xs btnDel">删除</botton>';
    					fileBox += '<input type="hidden" name="filesUpload[]" value="'+filesUploadData+'" disabled>';
    					fileBox += '</div></div></div>';
		    		$(attachGroup).append(fileBox);		
		      	}else{ //上传失败
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
					return false;
		      	}
		      	myScroll.refresh();
		    },
	      	error:function(xhr){
	        $('#fileupload').removeAttr("disabled"); //移除disabled属性 
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

	//删除文件
	$(document).on('click','.btnDel',function(){
		var delFile = $(this);
		$.confirm({
	    	title: '提示',
	    	content: '确定删除此附件吗？',
	    	animation: "top",
	    	cancelButtonClass: 'btn-danger',
	    	confirmButton: '确定',
			cancelButton: '取消',
		    confirm: function(){
		    	$('.buttons .btn').closest('.jconfirm').hide();
			  	var wfAttachId = delFile.closest('.panel-attach-box').attr('data-attachid'); //流程附件对应的id
			  	var wfRandomId = delFile.closest('.panel-attach-box').attr('data-random'); //上传文件的删除按钮
			  	delFile.closest('.panel-attach-box').remove();
			  	if (wfAttachId != undefined) { //存在流程附件id就删除对应文件
			  		$('#scroller .panel-attach-box[data-attachid='+wfAttachId+']').remove();
			  	};
			  	if (wfRandomId != undefined) { //上传文件后返回的随机数，提供删除对应文件
			  		$('#scroller .panel-attach-box[data-random='+wfRandomId+']').remove();
			  	};
			  	var attachGroupChildren = $('#attachGroup').find('.panel-attach-box'); //有多少附件存在
			  	if (attachGroupChildren.length < 1) {
			  		$('#attachGroup').remove();
			  	};
			  	myScroll.refresh();
		    }
		});
	})

	//ios文件在线浏览
	$(document).on('click','.btnFileView',function(){
		var fileSrc  = $(this).attr('data-src'); //文件地址
		var fileName = $(this).closest('.panel-heading').attr('data-name'); //文件名带后缀
		var fileExt  = $(this).closest('.panel-heading').attr('data-ext'); //文件后缀
		var seat = fileName.lastIndexOf("."); //查找某字符在字符串中最后一次出现的位置
		fileName = fileName.substring(0, seat); //纯文件名
		var host = window.location.host; //当前域名
		var fileAllSrc = host+'/'+fileSrc; //文件绝对路径
		var width = $(document).width(), height = $(window).height();
		if (myFns.isWeixn()) {
			if (userAgent == "android") {
				iospopWin.showWin(width, height, fileName, fileSrc, fileExt.toLocaleLowerCase());
			} else {
				iospopWin.showWin(width, height, fileName, fileSrc, fileExt.toLocaleLowerCase());
			}
		} else {
			try {
				CNOAApp.viewDocument(fileAllSrc, fileName, fileExt);
			} catch(e) {
				window.location.href = 'js://push_web_view_controller__'+fileAllSrc+'__'+fileName;
			}
		}
	})

	//下载文件
	$(document).on('click','.btnDownload',function(){
		var url = $(this).attr('data-src');
		window.location.href = url;
	})

	$(document).on('touchstart','#submit',function(){
		// 纪要员ID
		var jiyaouid = $('.name').attr('data-uid');
		// 纪要内容
		var markdetail = $('#markdetail').val();
		if (!markdetail || !jiyaouid) {
			var msg =  markdetail ? '会议纪要员' : '纪要内容';
			jError(msg+'不能为空！',{
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
		};
		//附件信息
		var upload_attach = '';
		$("input[name='filesUpload[]']").each(function(i){
			if(i==0){
				upload_attach += '0' + $(this).val();
			}else{
				upload_attach += ',' + '0' + $(this).val();
			}
		})

		$.confirm({
	    	title: '提示',
	    	content: '您确定你要提交审批吗?',
	    	animation: "top",
	    	cancelButtonClass: 'btn-danger',
	    	confirmButton: '确定',
			cancelButton: '取消',
		    confirm: function(){
		    	$("#myTableFrom").ajaxSubmit({
					dataType: 'json',
					type: 'post', 
					url: 'm.php?app=meeting&func=mgr&action=join&task=submitMark',
					data: { 
						"jiyaouid":jiyaouid, 
						"markdetail":markdetail, 
						"aid":aid,
						"filesUpload":upload_attach
					},
					success: function(json){
						if(json.success){
							jSuccess('操作成功!',{
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
							$('#jingle_popup').css('display','none');
   							$('#jingle_popup_mask').css('display','none');
   							setTimeout(function(){
   								if (type) {
   									window.location.href = 'm.php?app=meeting&func=mgr&action=join&task=loadPage&from='+type;
   								} else {
									window.location.href = 'm.php?app=meeting&func=mgr&action=meeting&task=loadPage&from=list';
   								}
							},1400)
						}else{
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
					}
				})
		    }
		});
		return false;
	})

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
})
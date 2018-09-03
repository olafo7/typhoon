var userAgent;
var sendeeArr = [];
/*                             
 *  方法:Array.remove(obj)      
 *  功能:删除数组元素.         
 *  参数:要删除的对象.     
 *  返回:在原数组上修改数组    
 */  
Array.prototype.remove=function(obj){  
  for(var i =0;i <this.length;i++){  
    var temp = this[i];  
    if(!isNaN(obj)){  
      temp=i;  
    }  
    if(temp == obj){  
      for(var j = i;j <this.length;j++){  
        this[j]=this[j+1];  
      }  
      this.length = this.length-1;  
    }     
  }  
}
String.prototype.replaceAll = function(reallyDo, replaceWith, ignoreCase) {  
   if (!RegExp.prototype.isPrototypeOf(reallyDo)) {  
       return this.replace(new RegExp(reallyDo, (ignoreCase ? "gi": "g")), replaceWith);  
   } else {  
       return this.replace(reallyDo, replaceWith);  
   }  
}
//判断当前客户端Android和ios
if(/android/ig.test(navigator.userAgent)){ //安卓
	var userAgent = 'android';
}else{ //ios或者其他
	var userAgent = 'ios';
}
var myFns = {
	//初始化iScroll控件
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
	getUriString: function(key){
		var reg = new RegExp("(^|&)"+ key +"=([^&]*)(&|$)");
    	var r = window.location.search.substr(1).match(reg);
    	if(r!=null)return  unescape(r[2]); return null;
	}
}
$(function(){
	var date = new Date();
	// myScroll.refresh();
	setTimeout(function(){
		myFns.loaded();
	},100);
		
	myFns.userDataLoaded(); //人员和部门区域
	myFns.userLoaded();
	//时间默认为当前时间
	var m = date.getMonth()+1;
	var d = date.getDate();
	if(m<10){
		m = '0'+m;
	}
	var tm = date.getFullYear()+'-'	+m+'-'+d;
	$('#plantime').val(tm+' '+date.getHours()+':'+date.getMinutes());
	if(myFns.getUriString('date')){
		tm = myFns.getUriString('date');
		$('#plantime').val(tm+' 09:00');
	}	
	$('#planetime').val(tm);
	var userDataWrapperH = $(window).height()-151;
	$('.userDataWrapper').css('height', userDataWrapperH); //人员和部门区域高度
	var userWrapperTop = $(window).height()-106;
	$('.userWrapper').css('top', userWrapperTop); //人员删除操作区域高度
	var userWrapperW = $(window).width() - 84; //userWrapper宽度
	$('#userWrapper').css('width', userWrapperW);
	var userScrollerW = $('#userGroup li').length * $('#userGroup li').width(); //userScroller宽度
	$('#userScroller').css('width', userScrollerW);
	var optsBoxH = $(window).height() - 54;
	$('#userDataModal .opts-box').css('top', optsBoxH);
	$('#userDataModal .opts-box').css('width', $(window).width());

	$(document).on('touchstart','#btnBack',function(){
		window.location.href="m.php?app=user&func=diary&action=index&task=loadPage";
	});

	//修改,载入数据
	var id = myFns.getUriString('id');
	if(id>0){
		$.ajax({
			dataType:'json',
    		method:'post',
    		url:'m.php?app=user&func=diary&action=index&task=loadPlanFormData',
    		data:{'id':id},
    		success:function(json){
    			var data = json.data;
    			$('.myEditor').val(data.content);
    			$('#plantime').val(data.plandate+" "+data.plantime);
    			$('#planetime').val(data.planetime);
    			if(data.signName!=''){
    				$('#modSend').append('<span class="name" data-uid="'+data.signUid+'">'+data.signName+'</span>');
    			}
    			if(data.alarm=="off"){
    				$('.switch-animate').removeClass('switch-on').addClass('switch-off');
    			}
    			$('#noticetime').val(data.alarmtime);
    		}
		});
	}

	// 提交表单
	$(document).on('click','.btn-add',function(){
		var content = $('.myEditor').val();
		var signUid = $('.name').attr('data-uid');
		var plantime= $('#plantime').val();
		var planetime = $('#planetime').val();
		var date = new Date();
		var str = plantime.substr(0,10);
		str = str.replaceAll('-','');
		var str1 = planetime.substr(0,10);
		var d = planetime.substr(-2,2);
		if(d.indexOf('-')>-1){
			d='0'+d.substr(-1,1);
		}
		str1 = str1.replaceAll('-','');
		str1 = str1.substr(0,6);
		str1 = str1+d;	
		var cha1= str1-str;

		var show ='';
		if(content == '' || plantime =='' || planetime=='' || cha1<0){
			if(content == '') {show = '计划内容不能为空';}
			if(plantime == ''){show = '执行时间不能为空';}
			if(planetime == ''){show = '完成时间不能为空';}
			if(cha1<0){show = '完成时间不能早于执行时间';}
			jNotify(show+'!',{
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
		//提醒
		var alarm,alarmtime;
		if($('.switch-animate').hasClass('switch-on')){
			alarm = 1;
			alarmtime=$('#noticetime').val();
		}else{
			alarm = 0;
		}
		var opt='';//是修改还是添加
		if(id>0){
			opt='update';
		}else{
			opt='add';
		}
		
		var data={'opt':opt,'id':id,'content':content,'plantime':plantime,'planetime':planetime,'alarm':alarm,'alarmtime':alarmtime,'signUid':signUid};
		$.ajax({
			dataType: "json",
			type: "post",
			url: 'm.php?app=user&func=diary&action=index&task=addDiary',
			data:data,
			success:function(json){
				if(json.success){
					if(opt=='update'){ show='修改';}else{
						show='添加';
					}

					jSuccess(show+'计划成功',{
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
					     OpacityOverlay : 0.3,            // 设置遮罩层的透明度 
					     onClosed:function(){
					     	window.location.href="m.php?app=user&func=diary&action=index&task=loadPage";
					     }
					});
				}
			}
		})
	});

	//初始化时间控件
	$('#planetime').date({curdate:true,endday:30});
	$('#noticetime').date({theme:"datetime"});
	$('#plantime').date({theme:"datetime"});
	//是否要提醒
	$('.switch').on('switch-change', function (e, data) {
		value = data.value;
	    if(!value){
	    	$('.nn').css('display','none');
	    }else{
	    	$('.nn').css('display','block');
	    }
	});

	// 人员选择
	//触发人员选择器
	$(document).on('click','#modSend',function(){
		if (userAgent == 'ios') { //ios需要在弹出的层加绝对定位才能滚动
			$('.userDataWrapper,.userDataScroller').css('position','absolute');
		} else {
			$('.userDataWrapper,.userDataScroller').css('position','');
		}
		if($(this).attr('data-readonly') == 'true'){ //TRUE 只读 FALSE 可选择
			return false;
		}

		//清除头像区的数据
		sendeeArr = [];
		$('#userGroup').empty();
		var modId = $(this).attr('id');

		//清除搜索框的数据
		$('#search').val('');
		$('.dif').html('选择人员');
		$('#btnAll').removeClass('glyphicon-check');
		$('#btnAll').addClass('glyphicon-chevron-left');
		$('#btnAll').attr('id','sback');

		var source = $(this).attr('data-name') == undefined ? '': $(this).attr('data-name');
		$('#userDataModal').attr('data-belong','freeFlowStepDeal');
		if (source != '') {
			$('#userDataModal').attr('data-source', source);
		} else {
			$('#userDataModal').attr('data-source','modSend');
		}
		
		var checktype = $(this).attr('data-checktype');
		$('#userDataModal').attr('data-checktype', checktype);
		var uid  = $(this).children('.name').attr('data-uid');
		var name = $(this).children('.name').text();
		var face = $(this).children('.name').attr('data-face');
		if(face == undefined){ //默认头像
			face = 'file/common/face/default-face.jpg';
		}
		if(uid != undefined){
			var userObj = {'uid':uid, "name":name, "face":face};
			sendeeArr.push(userObj);
		};
    	//请求人员数据
		getSelectorData();
	});
	//请求人员选择器数据
	function getSelectorData(){
		url = "m.php?action=commonJob&task=getSelectorData&target=getUserDatamm";
		$.ajax({
			dataType: "json",
			type: "get",
			url: url,
			success: function(json){
				if(json.success){ //请求成功
					if(json.paterId == 0){ //上级部门id
						$('#sback').css('display','none');
					}else{
						$('#sback').css('display','block');
					}
					
					var data = json.data;
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
							var userStr  = '<li class="list-group-item userList" data-email="'+arr['email']+'" id="uid-'+arr['uid']+'" data-uid="'+arr['uid']+'" data-name="'+arr['truename']+'" data-face="'+arr['face']+'">';
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
				// hideLoading();
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
	$(document).on('click','#sback',function(){
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
	});

	//完成人员选择
	$(document).on('touchstart','#sFinish',function(){
		recordData(); //其他通用方式
		sendeeArr = []; //切记`关闭选择器时需重置全局数组
	});

	//完成人员选择`把数据记录到指定位置
	function recordData(){ 
		var source = $('#userDataModal').attr('data-source'); //要把数据到的位置
		$('#' + source).empty(); //先清空数据区再遍历数据
		var uids  = ''; //用户id
		var names = ''; //用户名
		var faces = ''; //用户头像
		var email = ''; //用户邮箱
		//把存放在全局数组中的人员数据遍历到指定位置
		for (var i = 0; i < sendeeArr.length; i++) {
			if (i == 0) {
				uids  += sendeeArr[i].uid;
				names += sendeeArr[i].name;
				faces += sendeeArr[i].face;
				email += sendeeArr[i].email;
			}else {
				uids  += ',' + sendeeArr[i].uid;
				names += ',' + sendeeArr[i].name;
				faces += ',' + sendeeArr[i].face;
				email += ';' + sendeeArr[i].email;
			}
		};
		
		var userStr = '<span class="name" data-uid="'+uids+'" data-face="'+faces+'">'+names+'</span>';
		$('.modSend').html(userStr);
		$('#'+source).next().val(uids); //隐藏input赋值
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
			success: function(json){
				// hideLoading();
				$('#userDataGroup').empty();//清空数据区
				if(json.success){
					var data  = json.data;
					var users = data.users; //人员数据
					if(users.length > 0){
						$.each(users,function(i, arr){
							var userStr  = '<li class="list-group-item userList" data-email="'+arr['email']+'" id="uid-'+arr['uid']+'" data-uid="'+arr['uid']+'" data-name="'+arr['truename']+'" data-face="'+arr['face']+'">';
									userStr += '<span class="tools pull-left">';
									userStr += '<input type="checkbox" class="ipt-hide">';
									userStr += '<label class="checkbox"></label>';
									userStr += '</span>';
									userStr += '<div class="name-box">';
									userStr += '<img src="'+arr['face']+'" class="img-circle face">';
									userStr += '<span class="truename">'+arr['truename']+'</span>';
									if(source == 'modSendee4'){
										$('.truename').css('top','0px');
										$('.cur').css('top','9px');
										$('.checkbox').css('top','9px');
										$('.list-group-item').css('padding','10px 15px');
										userStr += '<span class="structname" style="position:absolute;left:48px;top:26px">'+arr['structName']+'</span>';
									}
									userStr += '</div>';
									if(source != 'modSendee4'){
										userStr += '<span class="job">'+arr['jobName']+'</span>';
									}
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

	/**
	 *	方法: setChecked(element)
	 *	功能: 自动扣选
	 *	参数1: <string> element 需要遍历的元素 userlist
	 *	参数2: <array> 存放已扣选的数据`数组 
	 *	参数3: <string> choiceType 选择器类型
	 */
	function setChecked(element,dataArr,choiceType){
		if(choiceType == 'user'){ //人员选择器
			$('.'+element+':checkbox').each(function(){
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
			//
			$('#sFinish').prop('disabled', false);
			$('#sFinish').html('完成');
		}
		var userScrollerW = $('#userGroup li').length * $('#userGroup li').width(); //userScroller宽度
		$('#userScroller').css('width', userScrollerW);
		userScroll.refresh();
		myScroll.refresh();
	}

	//checkbox扣选
	$(document).on('touchstart','.userList',function(e){
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
		var email= $(userList).attr('data-email');
		var modId = $('#modSendee4').attr('id');
		var userObj = {'uid':uid, 'name':name, 'face':face, 'email':email};
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
		}else{ //只允许单选
			if(chkStu){ //扣选
				sendeeArr = [];
				sendeeArr.push(userObj);
			}else{ 
				sendeeArr = [];
			}
		}
	}
})
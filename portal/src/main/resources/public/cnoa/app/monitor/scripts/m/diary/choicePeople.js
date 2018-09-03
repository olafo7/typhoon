
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
//判断当前客户端Android和ios
if(/android/ig.test(navigator.userAgent)){ //安卓
	var userAgent = 'android';
}else{ //ios或者其他
	var userAgent = 'ios';
}
var myFns = {
	/**
	 *	方法: myFns.userDataLoaded()
	 *	功能: 初初始化人员选择器用户部门iScroll控件
	 */
	userDataLoaded: function(){
		userDataScroll = new IScroll('#userDataWrapper', {
			scrollbars: false,
			zoom: true, //缩放功能
		    mouseWheel: true,
		    wheelAction: 'zoom',
			preventDefault: false, 
			click: true
		});
		document.addEventListener('touchmove', function (e) { e.preventDefault(); }, false);
	}
}
$(function(){

	//加载完成显示人员选择模态框
	$('#userDataModal').modal('show');
	$('.modal-backdrop').remove();

	// myFns.loaded();	
	myFns.userDataLoaded(); //人员和部门区域
	$('.userDataWrapper').css('top','96px');
	var userDataWrapperH = $(window).height()-100;
	$('.userDataWrapper').css('height', userDataWrapperH); //人员和部门区域高度
	var userScrollerW = $('#userGroup li').length * $('#userGroup li').width(); //userScroller宽度
	$('#userScroller').css('width', userScrollerW);
	var optsBoxH = $(window).height() - 54;
	$('#userDataModal .opts-box').css('top', optsBoxH);
	$('#userDataModal .opts-box').css('width', $(window).width());

	//触发人员选择器
	getSelectorData();
	
	//请求人员选择器数据
	function getSelectorData(){
		url = "m.php?app=monitor&func=mgr&action=diary&task=getChoicePeopleList";
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
					$('#btnBack').attr('data-fid',0); //有数据才改变上级部门id
					$('#userDataGroup').empty();//清空数据区
					if(users.length > 0){ //处理人员数据
						$.each(users, function(i,arr){
							var userStr  = '<li class="list-group-item userList" data-email="'+arr['email']+'" id="uid-'+arr['uid']+'" data-uid="'+arr['uid']+'" data-name="'+arr['truename']+'" data-face="'+arr['face']+'">';
									userStr += '<div class="name-box">';
									userStr += '<img src="'+arr['face']+'" class="img-circle face">';
									userStr += '<span class="truename">'+arr['truename']+'</span>';
									userStr += '</div>';
									userStr += '<span class="job">'+arr['jobName']+'</span>';
									userStr += '</li>';
							$('#userDataGroup').append(userStr);
						})           
					}
					if(structs.length > 0){ //处理部门数据
						$.each(structs, function(index,array){
							var structStr  = '<li class="list-group-item structList" data-sid="'+array['structId']+'">';
									structStr += '<span class="fs1 struct-ico-left" aria-hidden="true" data-icon="&#xe109;"></span>';
									structStr += '<div class="name-box1">';
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

	/*                             
	 *  方法:viewUserData(structId)      
	 *  功能:加载用户和部门数据.         
	 *  参数:部门ID.     
	 */
	function viewUserData(structId){
		$.ajax({
			dataType: "json",
			type: "post",
			url: "m.php?app=monitor&func=mgr&action=diary&task=getChoicePeopleList",
			data: {'structId':structId},
			// beforeSend: function(){
			// 	//提交表单前验证
			// 	showLoading();
			// },
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
					if(users.length == 0 && structs==false){
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
					if(json.data.users.length>0 || json.data.structs.length>0){
						$('#btnBack').attr('data-fid',1); //有数据才改变上级部门id
					}
					if(structId == 1){
						$('#btnBack').attr('data-fid',0);
					}
					$('#userDataGroup').empty();//清空数据区
					if(users.length > 0){ //处理人员数据
						$.each(users, function(i,arr){
							var userStr  = '<li class="list-group-item userList" id="uid-'+arr['uid']+'" data-uid="'+arr['uid']+'" data-name="'+arr['truename']+'" data-face="'+arr['face']+'">';
									userStr += '<div class="name-box">';
									userStr += '<img src="'+arr['face']+'" class="img-circle face">';
									userStr += '<span class="truename">'+arr['truename']+'</span>';
									userStr += '</div>';
									userStr += '<span class="job">'+arr['jobName']+'</span>';
									userStr += '</li>';
							$('#userDataGroup').append(userStr);
						}) 
						//自动勾选
						// setChecked('userList', sendeeArr, 'user');            
					}
					if(structs.length > 0){ //处理部门数据
						$.each(structs, function(index,array){
							var structStr  = '<li class="list-group-item structList" data-sid="'+array['structId']+'">';
									structStr += '<span class="fs1 struct-ico-left" aria-hidden="true" data-icon="&#xe109;"></span>';
									structStr += '<div class="name-box1">';
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
		setTimeout(function(){
			viewUserData(structId);
		},300);
		return false;
	})
	
	//返回上一级部门
	$(document).on('touchstart','#btnBack',function(){
		$('#search').val(''); //清空搜索框
		var structId = $(this).attr('data-fid'); //部门id
		
		if(structId == 0){
			if(/android/ig.test(navigator.userAgent)){
				window.javaInterface.returnToMain();
			}else{
				window.location.href = 'js://pop_view_controller';
			}
			return false;
		}
		// console.log(structId);
		viewUserData(structId);
	})
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
		userDataScroll.refresh();
	}

	//按钮被松开时触发搜索人员事件
	$(document).on('keyup','#search',getSearchUserData);

	function getSearchUserData(){
		var search = $('#search').val();
		if(search != ''){ 
			var httpUrl = 'm.php?app=monitor&func=mgr&action=diary&task=getChoicePeopleList&search='+search;
		}else{
			var structId = $('#userDataModal').attr('data-sid');
			var httpUrl = 'm.php?app=monitor&func=mgr&action=diary&task=getChoicePeopleList';
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
									userStr += '</div>';
									userStr += '<span class="job">'+arr['jobName']+'</span>';
									userStr += '</li>';
							$('#userDataGroup').append(userStr);
						})
						//自动扣选
						// setChecked('userList', sendeeArr, 'user');
					}
					if(data.hasOwnProperty('structs')){
						var structs = data.structs; //部门数据
						if(structs.length > 0){ //处理部门数据
							$.each(structs, function(index,array){
		            			var structStr  = '<li class="list-group-item structList" data-sid="'+array['structId']+'">';
									structStr += '<span class="fs1 struct-ico-left" aria-hidden="true" data-icon="&#xe109;"></span>';
									structStr += '<div class="name-box1">';
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
	//跳到评阅日记
	$(document).on('click','.userList',function(){
		var uid = $(this).attr('data-uid');
		window.location.href="m.php?app=monitor&func=mgr&action=diary&task=loadPage&from=reviewIndex&uid="+uid;
	});
	//test
	$(document).on('touchstart','.truename',function(){
		var uid = $(this).parents().parents('.userList').attr('data-uid');
		window.location.href="m.php?app=monitor&func=mgr&action=diary&task=loadPage&from=reviewIndex&uid="+uid;
	});
})
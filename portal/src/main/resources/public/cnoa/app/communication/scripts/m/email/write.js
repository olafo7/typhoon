//全局变量`
var dsPageNow = 1, jxcPageNow = 1,userAgent=''; //数据源分页, 进销存分页
var sendeeArr = [], stationArr = [], jobArr = [], deptArr = []; //全局对象和数组
var emailInfo = ''; 
var myScroll;
//myScroll.refresh(); //数据加载完成后调用界面更新方法
var ue; //百度编辑器
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
	//获取url参数
	getUriString: function(key){
		var reg = new RegExp("(^|&)"+ key +"=([^&]*)(&|$)");
    	var r = window.location.search.substr(1).match(reg);
    	if(r!=null)return  unescape(r[2]); return null;
	}
}

$(function(){
	
	//一开始加载时如果是Android就显示编辑器
	if(userAgent == 'android'){
		UM.getEditor('myEditor').focus();
	}
	window.onresize = function(){
		myScroll.scrollTo(0, -150);
	}
	//获取参数
	var opt = myFns.getUriString('opt');
	var fid = myFns.getUriString('fid');
	var emailType = myFns.getUriString('emailType');
	if(opt == 'reply'){//回复
		$('#btnBack').prev().text('回复邮件');
		$.ajax({
			dataType:'json',
			url:'m.php?app=communication&func=message&action=index&task=getData',
			data:{opt:opt,fid:fid,emailType:emailType},
			type:'post',
			success:function(json){
				// console.log(json);
				$.each(json.data.attach,function(index,array){
					$('.fslist').append('<p><span class="fn">'+array['name']+'<input type="hidden" class="" name="filesUpload[]" value="'+json.data.hideattach[index]+'"></span><button type="button" class="btn btn-danger delete">删除</button></p>');
				});
				var userStr = '<span class="glyphicon glyphicon-search search-icon people"></span><span class="name" data-uid="'+json.data.uid+'">'+json.data.name+'</span>';
				$('#modSendee').html(userStr);
				$('.tit').val(json.data.title);
				emailInfo = json.data.content;
			}
		})
	}else if(opt == 'forward'){//转发
		$('#btnBack').prev().text('转发邮件');
		$.ajax({
			dataType:'json',
			url:'m.php?app=communication&func=message&action=index&task=getData',
			data:{opt:opt,fid:fid,emailType:emailType},
			type:'post',
			success:function(json){
				$.each(json.data.attach,function(index,array){
					$('.fslist').append('<p><span class="fn">'+array['name']+'<input type="hidden" class="" name="filesUpload[]" value="'+json.data.hideattach[index]+'"></span><button type="button" class="btn btn-danger delete">删除</button></p>');
				});
				$('.tit').val(json.data.title);
				$('.myEditor').append(json.data.content);
			}
		})
	}else if(opt == 'draft'){
		$('#btnBack').prev().text('存草稿');
		$('#btnAdd').text('保存');
	}else if(emailType == 'draft'){
		$('#btnBack').prev().text('草稿箱');
		$.ajax({
			dataType:'json',
			url:'m.php?app=communication&func=message&action=index&task=getData',
			data:{opt:opt,fid:fid,emailType:emailType},
			type:'post',
			success:function(json){
				//接收人
				var userStr = '<span class="glyphicon glyphicon-search search-icon people"></span><span class="name" data-uid="'+json.data.to_uids+'">'+json.data.to_unames+'</span>';
				$('#modSendee').html(userStr);
				//抄送
				var userStr = '<span class="glyphicon glyphicon-search search-icon people"></span><span class="name" data-uid="'+json.data.cc_uids+'">'+json.data.cc_names+'</span>';
				$('#modSendee2').html(userStr);
				//密送
				var userStr = '<span class="glyphicon glyphicon-search search-icon people"></span><span class="name" data-uid="'+json.data.bccuids+'">'+json.data.bccNames+'</span>';
				$('#modSendee3').html(userStr);
				//附件
				$.each(json.data.attach,function(index,array){
					$('.fslist').append('<p><span class="fn">'+array['name']+'</span><button type="button" class="btn btn-danger delete">删除</button></p>');
				});
				//外部发信账号
				$('#modSendee4').append('<span class="name" data-uid="'+json.data.fromEmail+'">'+json.data.toEmails+'</span>');
				//标题和内容
				$('.tit').val(json.data.title);
				$('textarea').append(json.data.content);
			}
		})
	}

	//上传附件
	$(document).on('click','.uploaded',function(){
		var myUpload = $("#myUpload");
		var url = 'm.php?action=commonJob&task=upFile';
		if(!myUpload.length){//判断表单form是否存在
			$(".file").wrap("<form id='myUpload' action='' method='post' enctype='multipart/form-data'></form>"); 
			$("#myUpload").attr("action",url);
		}
	});
	$('.uploaded').change(function(){
		var lujin = $(this).val();
		var mime = lujin.toLowerCase().substr(lujin.lastIndexOf("."));
		var size = $('.uploaded')[0].files[0].size;
		var msize= parseInt(size/1024/1024);
		//判断文件大小
		if(msize>8){
			jNotify('文件大小不能超过8M!',{
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
			$('#myModal').modal('hide');
			return false;
		}
		var pos  = lujin.lastIndexOf("\\");
		var filename = lujin.substring(pos+1);
		showLoading();
		$("#myUpload").ajaxSubmit({
        	dataType:  'json',
        	success:function(json){
        		hideLoading();
        		if(json.success){
        			var data = json.msg;
	      			var filesUploadData = '||'+data.oldname+'||'+data.name+'||'+data.size;
	        		$('.fslist').append('<p><span class="fn">'+filename+'</span><button type="button" class="btn btn-danger delete">删除</button></p>');
					$('.fn').append('<input type="hidden" class="" name="filesUpload[]" class="data" value="'+filesUploadData+'">');
					$('#myModal').modal('hide');
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
					return false;
        		}
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
		
	});
	//删除附件
	$(document).on('click','.delete',function(){
		$(this).parent('p').remove();
	});

	myFns.loaded();	
	myFns.userDataLoaded(); //人员和部门区域
	myFns.userLoaded();
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

	//发送邮件
	$(document).on('touchstart','#btnAdd',function(){
		//判断
		var text = $('#btnAdd').text();
		var url = '';
		var title = $('.tit').val();
		var receiverNames = $('.name:eq(0)').text();//接收人
		var receiverUids  = $('.name:eq(0)').attr('data-uid');
		var content;
		if(userAgent == 'android'){
			content = $('.myEditor').text();
		}else{
			content = $('.myEditor').val();
		}
		content += emailInfo+"<br/>";
		if(text == '保存'){
			url = 'm.php?app=communication&func=message&action=index&task=save';
		}else{
			if(title=='' || receiverNames=='' || content==''){
				var show ='';
				if(title == ''){show='标题';}
				if(receiverNames == '' || receiverNames =='选择接收人'){show='接收人';}
				if(content == ''){show='内容';}
				jNotify(show+'不能为空!',{
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
			url = 'm.php?app=communication&func=message&action=index&task=send';
		}
		//上传附件
		var f = $('.uploaded').val();
		var arr = new Array();
		var r = Math.random().toString(36).substr(2);
		for(var i=0;i<$('.fn').length;i++){
			arr.push($('.fn:eq('+i+')').find('input[type="hidden"]').val());
		}
		var ccNames = $('.name:eq(1)').text();//抄送
		var ccUids  = $('.name:eq(1)').attr('data-uid');
		var bccNames = $('.name:eq(2)').text();//密送
		var bccuids  = $('.name:eq(2)').attr('data-uid');
		var toEmails = $('#modSendee4').find('.name').text();//外部发信
		var fromEmail  = $('#modSendee4').find('.name').attr('data-uid');//?????
		var data = {title:title,receiverNames:receiverNames,receiverUids:receiverUids,content:content,ccNames:ccNames,
				ccUids:ccUids,bccNames:bccNames,bccuids:bccuids,toEmails:toEmails,fromEmail:fromEmail,filesUpload:arr};
		// console.log(data);
		$.ajax({
			dataType:'json',
			type:'post',
			url:url,
			data: data,
			beforeSend: function(){
				showLoading();
			},
			success:function(json){
				hideLoading();
				var show = '';
				if(text == '保存'){
					show = '保存';
				}else{
					show = '发送';
				}
				jSuccess(show+'成功',{
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
				     	window.location.href="m.php?app=communication&func=message&action=index&task=loadPage&from=emailIndex";
				     }
				});
				// 
			}
		})
	});

	//我的通讯录
	$(document).on('touchstart','.drop1',function(){
		var text = $(this).text();
		$('.head').text(text);
		var dropId = $(this).attr('id');
		$('.head').attr('id',dropId);
		if(dropId != 'inside'){//不是内部通讯录的话
			$('#userDataGroup').empty();//清空数据区
			if(dropId == 'my'){
				getSelectorData('modSendee4','my');
			}else if(dropId == 'share'){
				getSelectorData('modSendee4','share');
			}else if(dropId == 'custom'){
				getSelectorData('modSendee4','custom');
			}
		}else{
			getSelectorData('modSendee4','inside');
		}
	});

	//触发人员选择器
	$(document).on('click','.modSendee',function(){
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

		//外部发信账号
		if(modId == 'modSendee4'){
			$('#sback').removeClass('glyphicon-chevron-left');
			$('#sback').addClass('glyphicon-check');
			$('#sback').attr('id','btnAll');
			$('.dif').html('<div class="dropdown clearfix center-block">'+
      						'<button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">'
    						 +'<span class="head" id="inside">内部通讯录</span>'
    							+'<span class="caret"></span></button>'
						      +'<ul class="dropdown-menu dropdown" id="dropdown" aria-labelledby="dropdownMenu1">'
						       +' <li><a class="drop1" id="inside" href="javascript:void(0);">内部通讯录</a></li>'
						        +'<li role="separator" class="divider"></li>'
						        +'<li><a class="drop1" id="my" href="javascript:void(0);">我的通讯录</a></li>'
						        +'<li role="separator" class="divider"></li>'
						        +'<li><a class="drop1" id="share" href="javascript:void(0);">共享通讯录</a></li>'
						        +'<li role="separator" class="divider"></li>'
						        +'<li><a class="drop1" id="custom" href="javascript:void(0);">我的客户</a></li></ul></div>'
						);
		}else{
			$('.dif').html('选择人员');
			$('#btnAll').removeClass('glyphicon-check');
			$('#btnAll').addClass('glyphicon-chevron-left');
			$('#btnAll').attr('id','sback');
		}

		var source = $(this).attr('data-name') == undefined ? '': $(this).attr('data-name');
		if (source != '') {
			$('#userDataModal').attr('data-source', source);
		} else {
			$('#userDataModal').attr('data-source',modId);//三种类型
		}
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
    	//请求人员数据
    	if(modId == 'modSendee4'){
    		getSelectorData(modId,'inside');
    	}else{
    		getSelectorData(modId);
    	}	
	})

	//请求人员选择器数据
	function getSelectorData(modId,type){
		var url = '';
		if(modId == 'modSendee4' && type == 'inside'){
			url = "m.php?app=communication&func=message&action=index&task=getDeptAddressBook";
		}else if(modId == 'modSendee4' && type == 'my'){
			url = "m.php?app=communication&func=message&action=index&task=getAddressbook&type=my";
		}else if(modId == 'modSendee4' && type == 'share'){
			url = "m.php?app=communication&func=message&action=index&task=getAddressbook&type=share";
		}else if(modId == 'modSendee4' && type == 'custom'){
			url = "m.php?app=communication&func=message&action=index&task=getCustomer";
		}else{
			url = "m.php?action=commonJob&task=getSelectorData&target=getUserDatamm";
		}
		$.ajax({
			dataType: "json",
			type: "get",
			url: url,
			success: function(json){
				// console.log(json.data);
				if(json.success){ //请求成功
					if(modId != 'modSendee4'){
						if(json.paterId == 0){ //上级部门id
							$('#sback').css('display','none');
						}else{
							$('#sback').css('display','block');
						}
					}else{
						$('#btnAll').css('display','block');	
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
									if(modId == 'modSendee4'){
										$('.truename').css('top','0px');
										$('.cur').css('top','9px');
										$('.checkbox').css('top','9px');
										$('.list-group-item').css('padding','10px 15px');
										userStr += '<span class="structname" style="position:absolute;left:48px;top:26px">'+arr['structName']+'</span>';
									}
									userStr += '</div>';
									if(modId != 'modSendee4'){
										userStr += '<span class="job">'+arr['jobName']+'</span>';
									}
									userStr += '</li>';
							$('#userDataGroup').append(userStr);
						}) 
						if(modId == 'modSendee4'){
							initials();
						}  
						//自动勾选
						setChecked('userList', sendeeArr, 'user');            
					}
					if(modId != 'modSendee4'){
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

	//全选
	$(document).on('click','#btnAll',function(){		
		if($('.checkbox').hasClass('cur')){
			$('#userDataGroup .checkbox').siblings('.checkbox').removeClass('cur');
			$('#userDataGroup .checkbox').removeClass('cur');
			sendeeArr = [];
		}else{
			$('#userDataGroup .checkbox').addClass('cur');
			$('.userDataGroup .userList').each(function(){
				var uid  = $(this).attr('data-uid');
				var name = $(this).attr('data-name');
				var face = $(this).attr('data-face');
				var email= $(this).attr('data-email');
				var userObj = {'uid':uid, 'name':name, 'face':face, 'email':email};
				sendeeArr.push(userObj);
			});
		}
		
		setUserOpts();
		userScroll.refresh();
		myScroll.refresh();
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
		if(source == 'modSendee4'){
			if(email.indexOf('@')<0 || email==''){
				jNotify('你选择的人员没有外部账号!',{
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
			var userStr = '<span class="name" data-uid="'+uids+'">'+email+'</span>';
		}else{
			var userStr = '<span class="glyphicon glyphicon-search search-icon people"></span><span class="name" data-uid="'+uids+'" data-face="'+faces+'">'+names+'</span>';
		}
		$('#' + source).append(userStr);
		$('#'+source).next().val(uids); //隐藏input赋值
	}

	//按钮被松开时触发搜索人员事件
	$(document).on('keyup','#search',getSearchUserData);

	function getSearchUserData(){
		var search = $('#search').val();
		var type   = $('.head').attr('id');
		var source = $('#userDataModal').attr('data-source');
		if(source == 'modSendee4' && type =='inside'){
			var httpUrl = 'm.php?app=communication&func=message&action=index&task=getDeptAddressBook&search='+search;
		}else if(source == 'modSendee4' && type =='my'){
			var httpUrl = 'm.php?app=communication&func=message&action=index&task=getAddressbook&type=my&search='+search;
		}else if(source == 'modSendee4' && type =='share'){
			var httpUrl = 'm.php?app=communication&func=message&action=index&task=getAddressbook&type=share&search='+search;
		}else if(source == 'modSendee4' && type =='custom'){
			var httpUrl = 'm.php?app=communication&func=message&action=index&task=getCustomer&search='+search;
		}else if(source!='modSendee4'){
			if(search != ''){ 
				var httpUrl = 'm.php?action=commonJob&task=getSelectorData&target=getUserDatamm&search='+search;
			}else{
				var structId = $('#userDataModal').attr('data-sid');
				var httpUrl = 'm.php?action=commonJob&task=getSelectorData&target=getUserDatamm&structId='+structId;
			}
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
						if(source == 'modSendee4'){
							initials();
						}
						//自动扣选
						setChecked('userList', sendeeArr, 'user');
					}
					if(source != 'modSendee4'){
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
	$(document).on('click','.userList',function(){
		//触发事件只能单选
		var source = $("#userDataModal").attr("data-source"); //请求来源 请求来源可以是通过ID或者class来获得出处
		//     !$("#"+source).attr('data-checktype') && $("."+source).attr('data-checktype');
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
					if(modId != 'modSendee4'){
						if (sendeeArr[i].uid == uid) {
							flag = true;
							break;
						}
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


	//百度编辑器
	if(userAgent == 'android'){
		$(document).on('touchstart','#myEditor', function(){
			UM.getEditor('myEditor').focus();
		});
	}

	//返回上一页
	$(document).on('touchstart','#btnBack',function(){
		history.go(-1);
	});

})
//全局变量`函数
var myFns = {
	/*
	 *ajax成功返回数据`追加内容`初始化·查询·刷新
	 *json：ajax请求成功返回的data
	 *isEmpty：是否清空数据区 true：清空 false：不清空数据区
	 *
	 */
	addressbook_list: function(json,isEmpty){
		if(isEmpty){ //不是上拉加载就清空数据区
			$("#sort_box").empty();
		}
		if(json.success && json.data.length > 0){
			$('.noBody').hide();
			$.each(json.data, function(index,array){
				var str = "<div class=\"sort_list\" data-id="+array['id']+">";
				str += "<div class=\"num_logo num_"+array['id']+"\"><img src="+array['face']+" /></div>";
				str += "<div class=\"num_name\">";
				str += "<span class=\"name\">"+array['name']+"</span>";
				str += "<span class=\"grouping\">"+array['gname']+"</span>";
				str += "<span class=\"hide user_mobile\">"+array['mobile']+"</span>";
				str += "<span class=\"hide user_fax\">"+array['fax']+"</span>";
				str += "<span class=\"hide user_email\">"+array['email']+"</span>";
				str += "<span class=\"hide user_company\">"+array['company']+"</span>";
				str += "<span class=\"hide user_comphone\">"+array['comphone']+"</span>";
				str += "<span class=\"hide user_id\">"+array['id']+"</span>";
				str += "<span class=\"more\">></span>";
				str += "</div>";
				str += "<div class=\"opt\">";
				str += "<a class=\"call\" href=\"javascript:void(0);\">拨号</a>";
				str += "<span class=\"delete\">删除</span>";
				str += "</div>";
				str += "</div>";
				$('#sort_box').append(str);
				var sort_list = $('#sort_box').find('.sort_list').last();
				sort_list.swipe({
			        swipe: function(event, direction, distance, duration, fingerCount){   //参数:事件、方向、距离、持续事件、手指总数
			        	if (direction == 'right') {								//右滑动
			        		$(this).find('.num_name').css('display','block');	//用户名称显示
			        		$(this).find('.opt').css('display','none');			//拨打和删除按钮隐藏
			        	} else if (direction == 'left') {						//左滑动
			        		$('.sort_list .num_name').css('display','block');	//所有用户的用户名显示
			        		$('.sort_list .opt').css('display','none');			//所有用户的拨打和删除按钮隐藏
			        		$(this).find('.num_name').css('display','none');	//当前的用户的用户名隐藏
			        		$(this).find('.opt').css('display','block');		//拨打和删除按钮显示
			        	}
			        }
			    })
			})

			initials();  //字母排序方法  文件位于script/m/addressbook/sort.js
		} else {
			$('.noBody').show();
		}
	},
	 
	//判断当前是否是微信浏览器
	isWeiXin: function(){
		var ua = window.navigator.userAgent.toLowerCase();
		if(ua.match(/MicroMessenger/i) == 'micromessenger'){
		  	return true;
		}else{
		  	return false;
		}
	},
	// 图片
	photos: function(src){
		var options =
        {
            imageBox: '.imageBox',
            thumbBox: '.thumbBox',
            spinner: '.spinner',
            imgSrc: src
        };
		// var cropper = new cropbox(options);  //实例化画布
		var cropper = $('.imageBox').cropbox(options);
		$(document).on('click','#croped',function(){
			var imgUrl = cropper.getDataURL();								//获取截取的URL数据
			var postUrl = 'm.php?action=commonJob&task=uploadUserHeadPic'; 	//上传到本地服务器
			var user_id = $('.hide_user_id').html();
			$.post(postUrl, {"imgUrl":imgUrl,"user_id": user_id}, function(data){
				$('.user_face img').attr('src',data);
				$(".num_"+user_id+" img").attr('src',data);
				$('.containers').hide();
			},'json');
		})

		$(document).on('click','#btnZoomOut',function(){
			cropper.zoomOut();
		})

		$(document).on('click','#btnZoomIn',function(){
			cropper.zoomIn();
		})
	},

	stopPropagation:function(e) {  
	    e = e || window.event;  
	    if(e.stopPropagation) { //W3C阻止冒泡方法  
	        e.stopPropagation();  
	    } else {  
	        e.cancelBubble = true; //IE阻止冒泡方法  
	    }  
	} 
}


$(function(){
	//点击更换头像
	$(document).on("touchstart",".user_face",function(){
		$('#btn-mask').show();
		if(myFns.isWeiXin()){ 
			$('.photo-file').hide();
			$('#imagefile').hide();
			$('.photo-album').show();
			$('.take-photos').show();
		} else {
			$('.photo-file').show();
			$('#imagefile').show();
			$('.photo-album').hide();
			$('.take-photos').hide();
		}
	})

	/*
	*	url 用于文件上传的服务器端请求地址
	*	secureuri 是否需要安全协议，一般设置为false
	*	fileElementId 文件上传域的ID
	*	dataType 返回值类型 一般设置为json
	*	data, status 服务器成功响应处理函数
	*/
	$(document).on('change','#imagefile',function () {
        $.ajaxFileUpload({
	        url: 'm.php?action=commonJob&task=uploadNoWechaUserHeadPic',
	        secureuri: false,
	        fileElementId: 'imagefile',
	        dataType: 'json',
	        success: function (data, status){
	        	$('#btn-mask').hide();
			    $('.containers').show();
	        	myFns.photos(data);
	        }
		})
    })

	//点击取消拍照/相册
	$(document).on("touchstart",".photo-cancel",function(){
		$('#btn-mask').hide();
	})

	//点击拍照
	$(document).on("touchstart",".take-photos",function(){
		if(myFns.isWeiXin()){ 
			$.getJSON('m.php?app=att&func=person&action=register&task=getSignPackage',function(json){
			  if(json.success){
			    var configData = json.data;
			    setJsdkConfig(configData,'camera');
			  }
			})
		}
	})

	//点击相册
	$(document).on("touchstart",".photo-album",function(){
		if(myFns.isWeiXin()){ 
			$.getJSON('m.php?app=att&func=person&action=register&task=getSignPackage',function(json){
			  if(json.success){
			    var configData = json.data;
			    setJsdkConfig(configData,'album');
			  }
			})
		}
	})

	/**
   	*微信JS-SDK配置及函数处理
   	*/
  	function setJsdkConfig(configData,type){
	    //微信JS-SDK权限验证配置
	    wx.config({
	      debug: false,
	      appId: configData['appId'],
	      timestamp: configData['timestamp'],
	      nonceStr: configData['nonceStr'],
	      signature: configData['signature'],
	      jsApiList: [
	        'chooseImage',//使用拍照/相册上传
	        'uploadImage'//上传图片接口
	        // 'downloadImage'//下载图片接口
	      ]
	    });

	    //通过config接口注入权限验证配置
	    wx.ready(function(){
		    wx.chooseImage({
			    count: 1, // 默认9
			    sizeType: ['compressed'], // 可以指定是原图还是压缩图，默认压缩图
			    sourceType: [type], // 可以指定来源是相册还是相机
			    success: function (res) {
			        var localIds = res.localIds; // 返回选定照片的本地ID列表，localId可以作为img标签的src属性显示图片
			        $('#btn-mask').hide();
			    	$('.containers').show();
			    	function upload() {
                        wx.uploadImage({
						    localId: localIds.toString(), // 需要上传的图片的本地ID，由chooseImage接口获得
						    isShowProgressTips: 1, // 默认为1，显示进度提示
						    success: function (res) {
						        var serverId = res.serverId; // 返回图片的服务器端ID
						        var postUrl = 'index.php?app=wechat&func=setting&action=setting&task=uploadWXPic';
								$.post(postUrl, {"serverId":serverId}, function(data){
									myFns.photos(data);
								},'json');
						    }, 
						    fail: function (res) {
                                alert(JSON.stringify(res));
                            }
						});
            		}
            		upload();
			    }
			});
		})
	}

	//路径导航
	$(document).on("touchstart","#btnBack",function(){
		window.location.href = 'm.php?app=user&func=addressbook&action=default&task=loadPage&from=list';
	})

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

	//判断手机高度低于526像素显示下拉
	var winHeight = $(window).height();
	if (winHeight <= 526) {
		$('.user_info').css('height','135px');
	}


	//获取我的通讯录列表
	function addressbookList(url){
		var keyword = $('#search').val(); //获取搜索关键词
		$.ajax({
			dataType: "json",
			type: "post",
			url: url,
			data:{
				"keyword":keyword
			},
			success: function(json){
				//提交成功后调用
				myFns.addressbook_list(json, true);
			},
			complete: function(XMLHttpRequest, textStatus){
				$(".ui-ios-overlay").removeClass("ios-overlay-show").addClass("ios-overlay-hide");
			}
		})
	}

	//加载我的通讯录
	addressbookList('m.php?app=user&func=addressbook&action=default&task=loadPage&from=list&task=getMyAddressBook');

	//搜索联系人
	$(document).on('keyup','#search',function(){
		var keyword = $('#search').val(); //搜索关键词
		var url = $('#header').attr('data-url'); //请求数据地址
		$.ajax({
			dataType: "json",
			type: "post",
			url: url,
			data:{
				"keyword":keyword
			},
			success: function(json){
				//提交成功后调用
				myFns.addressbook_list(json, true);
			},
			complete: function(XMLHttpRequest, textStatus){
				$(".ui-ios-overlay").removeClass("ios-overlay-show").addClass("ios-overlay-hide");
			}
		})
	})

  	//点击拨打
    $(document).on('touchstart','.call',function(e){
    	var userInfo   	= [];
    		sort_list 	= $(this).closest('.sort_list'),
			userInfo[0] = sort_list.find('.user_mobile').html(),
    		userInfo[1]	= sort_list.find('.name').html(),
    		userInfo[2]	= sort_list.find('.grouping').html(),
    		str 		= sort_list.find('.num_logo img').attr('src'),
    		n 			= str.lastIndexOf("/"),
    		userInfo[3] = str.substring(n+1);

		if (userInfo[0]) {
			$(this).attr('href',"tel:"+userInfo[0]);
			var url = 'm.php?app=user&func=addressbook&action=default&task=loadPage&from=list&task=addCall';
			$.post(url,{'userInfo':userInfo},function(json){
				return false;
			},'json')
		} else {
			alert('号码不存在')
		}
  		myFns.stopPropagation(e);
    })

    //点击删除
    $(document).on('touchstart','.delete',function(){
    	var sort_list 	= $(this).closest('.sort_list');
        var username 	= sort_list.find('.name').html()
        var text  	 	= '是否删除 "<span style="color:red">'+username+'</span>" 联系人?';
        var id 	 		= $(this).closest('.sort_list').attr('data-id');
		$.confirm({
		    title: '',
		    content: text,
		    animation: "top",
		    cancelButtonClass: 'btn-danger',
		    confirmButton: '确定',
			cancelButton: '取消',
		    confirm: function(){
		    	//删除跳转地址
		    	var url = 'm.php?app=user&func=addressbook&action=default&task=loadPage&from=list&task=delAddressBookInfo'
		    	$.post(url,{'id':id},function(json){
					if(json.success){
						//删除成功重新加载数据
						addressbookList('m.php?app=user&func=addressbook&action=default&task=loadPage&from=list&task=getMyAddressBook');
					}
				},'json')
		    }
	    })
	    return false;
    })

    //跳转到我的通讯录
	$(document).on('click','#my',function(){
		addressbookList('m.php?app=user&func=addressbook&action=default&task=loadPage&from=list&task=getMyAddressBook');
	});

	//滑动/点击我的通讯录
	//避免滑动的时候触发点击事件,SO 添加了flag来判断
	var flag = false;
	$(document).on('touchstart touchmove touchend','.sort_list',function(event){
		switch(event.type) {
	        case 'touchstart':
	            falg = false;
	            break;
	        case 'touchmove':
	            falg = true;
	             break;
	        case 'touchend':
	            if( !falg ) {
	            	//获取用户数据
	            // event.preventDefault()
	                var user_mobile  	= $(this).find('.user_mobile').html(),		//获取用户手机号码
						user_email 	 	= $(this).find('.user_email').html(),		//获取用户邮箱
						user_fax 	 	= $(this).find('.user_fax').html(),			//获取用户传真号码
						user_company 	= $(this).find('.user_company').html(),		//获取用户公司地址
						user_comphone 	= $(this).find('.user_comphone').html(),	//获取用户单位号码
						user_id 		= $(this).find('.user_id').html(),			//获取用户ID
						user_name 	 	= $(this).find('.name').html(),				//获取用户姓名
						user_grouping 	= $(this).find('.grouping').html(),			//获取用户所在分组
						user_face	 	= $(this).find('img').attr('src');			//获取用户头像地址

					//赋值用户数据到详情列表
					$('.user_name').html(user_name);								
					$('.user_grouping').html(user_grouping);
					$('.about_user_comphone .about_value').html(user_comphone);
					$('.about_user_company .about_value').html(user_company);
					$('.about_user_fax .about_value').html(user_fax);
					$('.about_user_mobile .about_value').html(user_mobile);
					$('.about_user_email .about_value').html(user_email);
					$('.user_face img').attr('src',user_face);
					$('.hide_user_id').html(user_id);
					//用户详情列表
					$('#cbp-spmenu').addClass('cbp-spmenu-open');	//展开隐藏详情列表
					$('.masking').css('display','block');			//显示遮罩层
					$('#wrapper').css('overflow-y','hidden');		//内容滚动条禁用
	            }
	            break;
	    }
		
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
    		userInfo[1]	= $(this).closest('.user_info').prev().find('.user_name').html(),
    		userInfo[2]	= $(this).closest('.user_info').prev().find('.user_grouping').html(),
    		str 		= $(this).closest('.user_info').prev().find('.user_face img').attr('src'),
    		n 			= str.lastIndexOf("/"),
    		userInfo[3] = str.substring(n+1);

		if (userInfo[0]) {
			$(this).children('.about_value').attr('href',"tel:"+userInfo[0]);
			var url = 'm.php?app=user&func=addressbook&action=default&task=loadPage&from=list&task=addCall';
			$.post(url,{'userInfo':userInfo},function(json){
				return false;
			},'json')
		}
	})

	// 点击用户详情的单位号码
	$(document).on('touchstart','.about_user_comphone',function(){
		var userInfo   	= [];
			userInfo[0] = $(this).find('.about_value').html(),
    		userInfo[1]	= $(this).closest('.user_info').prev().find('.user_name').html(),
    		userInfo[2]	= $(this).closest('.user_info').prev().find('.user_grouping').html(),
    		str 		= $(this).closest('.user_info').prev().find('.user_face img').attr('src'),
    		n 			= str.lastIndexOf("/"),
    		userInfo[3] = str.substring(n+1);

		if (userInfo[0]) {
			$(this).children('.about_value').attr('href',"tel:"+userInfo[0]);
			var url = 'm.php?app=user&func=addressbook&action=default&task=loadPage&from=list&task=addCall';
			$.post(url,{'userInfo':userInfo},function(json){
				return false;
			},'json')
		}
	})

	// 点击用户详情的邮箱
	$(document).on('touchstart','.about_user_email',function(){
		var email = $(this).find('.about_value').html();
		$(this).children('.about_value').attr('href',"mailto:"+email);
	})

	// 点击修改用户信息
	$(document).on('touchstart','.btn_update',function(){
		var user_id = $(this).parent().find('.hide_user_id').html();
		window.location.href = encodeURI(encodeURI('m.php?app=user&func=addressbook&action=default&task=loadPage&from=editUser&id='+user_id));
	})

	// 点击取消
	$(document).on('touchstart','#take_cancel',function(){
		$('.containers').hide();
	})
})

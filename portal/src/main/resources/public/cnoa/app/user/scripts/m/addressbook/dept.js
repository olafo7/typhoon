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
			$.each(json.data, function(index,array){
				var str = "<div class=\"sort_list\" data-id="+array['id']+">";
				str += "<div class=\"num_logo num_"+array['id']+"\"><img src="+array['face']+" /></div>";
				str += "<div class=\"num_name\">";
				str += "<span class=\"name\">"+array['name']+"</span>";
				str += "<span class=\"grouping\">"+array['dept']+"</span>";
				str += "<span class=\"hide user_mobile\">"+array['mobile']+"</span>";
				str += "<span class=\"hide user_email\">"+array['email']+"</span>";
				str += "<span class=\"hide user_company\">"+array['address']+"</span>";
				str += "<span class=\"hide user_comphone\">"+array['workphone']+"</span>";
				str += "<span class=\"hide user_id\">"+array['id']+"</span>";
				str += "<span class=\"more\">></span>";
				str += "</div>";
				str += "</div>";
				$('#sort_box').append(str);
			})

			initials();  //字母排序方法  文件位于script/m/addressbook/sort.js
		}
	}
}


$(function(){

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
	addressbookList('m.php?app=user&func=addressbook&action=default&task=loadPage&from=list&task=getDeptAddressBook');

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
    	var sort_list 	= $(this).closest('.sort_list'),
  			mobile		= sort_list.find('.user_mobile').html();
  			$(this).attr('href',"tel:"+mobile);
  			myFns.stopPropagation(e);
    })

    //路径导航
	$(document).on("touchstart","#btnBack",function(){
		window.location.href = 'm.php?app=user&func=addressbook&action=default&task=loadPage&from=list';
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

	// 点击取消
	$(document).on('touchstart','#take_cancel',function(){
		$('.containers').hide();
	})
})

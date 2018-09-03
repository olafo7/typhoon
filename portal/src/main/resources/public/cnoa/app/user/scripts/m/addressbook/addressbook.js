//全局变量`
var pageNow = 1;

//全局变量`函数
var myFns = {
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
	/*
	 *ajax成功返回数据`追加内容`初始化·查询·刷新
	 *json：ajax请求成功返回的data
	 *isEmpty：是否清空数据区 true：清空 false：不清空数据区
	 *全部用户信息
	 */
	addressbook_list: function(json,isEmpty){
		if(isEmpty){ //不是上拉加载就清空数据区
			$("#addressbookList").empty();
		}
		if(json.success && json.data.length > 0){
			$.each(json.data, function(index,array){
				var str = "<div class=\"panel btnView\" data-class=\"btnView\" data-cid="+array['cid']+">";
				str += "<header class=\"panel-heading\"><span class=\"title\">";
				str += "<span class=\"name-box\">";
				str += "<span class=\"face\"><img src="+array['face']+"></span>";
				str += "<div class=\"user_info\">";
				str += "<span class=\"username\">";
				str += "<span class=\"name\">"+array['name']+"</span>";
				str += "<span class=\"mobile\">"+array['mobile']+"</span>";
				str += "</span>";
				str += "<div class=\"baseInfo\">";
				str += "<span class=\"profession\">"+array['profession']+"</span>";
				str += "<span class=\"calltime\">"+array['calltime']+"</span>";
				str += "</div>";
				str += "<a href=\"javascript:void(0)\" class=\"telIcon\"></a>";
				str += "</div>";
				str += "<a class=\"dial\">拨打</a>";
				str += "<div class=\"delete\">删除</div>";
				str += "</span>";
				str += "</span></header>";
				str += "</div>";
				$('#addressbookList').append(str);
				myFns.replace();
			})
			pageNow += 1; //当前页+1
		}
	},

	//刷新界面
	refreshFns: function(){
		var keyword = $('#search').val(); //公告标题
		var url = $('#myTabs').attr('data-url'); //请求数据地址
		$.post(url,{'keyword':keyword}, function(json){
			var myTabs = Number($('#myTabs').attr('data-tab'));
			myFns.isRead_notice(json,true);
		},'json')
	},

	//替换标题样式
	replace: function(){
		$('.name-box .name').find('span').css('color','#221814');
	}
}


$(function(){

	$('#search').attr('type','search');

	//路径导航
	$(document).on("touchstart","#btnBack",function(){
		if(/android/ig.test(navigator.userAgent)){
			window.javaInterface.returnToMain();
		}else{
			window.location.href = 'js://pop_view_controller';
		}
		return false;
	})

	//获取经常呼叫的联系人
	function addressbookList(url){
		var keyword = $('#search').val(); //公告标题
		$.ajax({
			dataType: "json",
			type: "post",
			url: url,
			data:{
				"keyword":keyword
			},
			success: function(json){
				//提交成功后调用
				var myTabs = Number($('#myTabs').attr('data-tab'));
				myFns.addressbook_list(json, true);
			},
			complete: function(XMLHttpRequest, textStatus){
				$(".ui-ios-overlay").removeClass("ios-overlay-show").addClass("ios-overlay-hide");
			}
		})
	}

	//添加经常联系人
	function addCall(userInfo,url,type){
		$.ajax({
			dataType: "json",
			type: "post",
			url: url,
			data:{
				"type": type,
				"userInfo": userInfo
			},
			success: function(json){
				//提交成功后调用
				var myTabs = Number($('#myTabs').attr('data-tab'));
				addressbookList('m.php?app=user&func=addressbook&action=default&task=loadPage&from=list&task=getOftenCall');
				
			},
			complete: function(XMLHttpRequest, textStatus){
				$(".ui-ios-overlay").removeClass("ios-overlay-show").addClass("ios-overlay-hide");
			}
		})
	}

	//删除最近联系人
	function deleteCall(url,cid){
		$.ajax({
			dataType: "json",
			type: "post",
			url: url,
			data:{
				cid : cid
			},
			beforeSend: function(){
				//提交表单前验证
				myFns.showLoading("加载中...");
			},
			success: function(json){
				//提交成功后调用
				var myTabs = Number($('#myTabs').attr('data-tab'));
				// myFns.addressbook_list(json, true);
				addressbookList('m.php?app=user&func=addressbook&action=default&task=loadPage&from=list&task=getOftenCall');
				
			},
			complete: function(XMLHttpRequest, textStatus){
				$(".ui-ios-overlay").removeClass("ios-overlay-show").addClass("ios-overlay-hide");
			}
		})
	}



	//默认加载已读公告
	addressbookList('m.php?app=user&func=addressbook&action=default&task=loadPage&from=list&task=getOftenCall');

	//已读公告
	$(document).on('click','#isRead',function(){
		pageNow = 1; //重置当前页为1
		var url = 'm.php?app=user&func=addressbook&action=default&task=getNoticeListM&isRead=1'; //请求数据地址
		$('#myTabs').attr('data-tab','0'); 
		$('#myTabs').attr('data-url',url); //设置请求数据地址
		addressbookList(url);
	});

	//已读公告
	$(document).on('click','#noRead',function(){
		pageNow = 1; //重置当前页为1
		var url = 'm.php?app=user&func=addressbook&action=default&task=getNoticeListM&isRead=0'; //请求数据地址
		$('#myTabs').attr('data-tab','1'); 
		$('#myTabs').attr('data-url',url); //设置请求数据地址
		addressbookList(url);
	});

	//搜索联系人
	$(document).on('keyup','#search',function(){
		var keyword = $('#search').val(); //搜索关键词
		var url = $('#myTabs').attr('data-url'); //请求数据地址
		$.ajax({
			dataType: "json",
			type: "post",
			url: url,
			data:{
				"keyword":keyword
			},
			success: function(json){
				//提交成功后调用
				var myTabs = Number($('#myTabs').attr('data-tab'));
				myFns.addressbook_list(json, true);
			},
			complete: function(XMLHttpRequest, textStatus){
				$(".ui-ios-overlay").removeClass("ios-overlay-show").addClass("ios-overlay-hide");
			}
		})
		pageNow = 1; //重置当前页为1
	})

	//触发点击导航事件
	$(document).on('click','#myTabs li',function(){
		var active = $('.active').attr('data-id');
		$('#myTabs li').each(function(){
			var id = $(this).children('a').attr('id');
		    $(this).children('a').css('background-image',"url('../../../../../../resources/images/m/addressbook/"+id+"_default.png')");
		 });
		$('#'+active).css('background-image',"url('../../../../../../resources/images/m/addressbook/"+active+"_active.png')");
	});

	//左滑动
	$(document).on("swipeleft",'.btnView',function(){
        $(this).find('.user_info').css('display','none');
        $(this).siblings().find('.user_info').css('display','block');
        $(this).siblings().find('.dial').css('display','none');
        $(this).siblings().find('.delete').css('display','none');
        $(this).find('.dial').css('display','block');
        $(this).find('.delete').css('display','block');
    })

	//右滑动
    $(document).on("swiperight",'.btnView',function(){
        $(this).find('.user_info').css('display','block');
        $(this).find('.dial').css('display','none');
        $(this).find('.delete').css('display','none');
    })

    //点击电话图标拨打
    $(document).on('click','.telIcon',function(){
    	var userInfo   	= [];
    		userInfo[0] = $(this).parent().find('.mobile').html(),
    		userInfo[1] = $(this).parent().find('.name').html(),
    		userInfo[2] = $(this).parent().find('.profession').html(),
    		str 		= $(this).closest('.name-box').find('.face img').attr('src');
    		n 			= str.lastIndexOf("/"),
    		userInfo[3] = str.substring(n+1);
    	$(this).attr('href',"tel:"+userInfo[0]);
    	addCall(userInfo,'m.php?app=user&func=addressbook&action=default&task=loadPage&from=list&task=addCall');
    })

    //点击拨打
    $(document).on('touchend','.dial',function(){
    	var userInfo   	= [];
    	 	userInfo[0]	= $(this).prev().find('.mobile').html(),
    		userInfo[1]	= $(this).prev().find('.name').html(),
    		userInfo[2]	= $(this).prev().find('.profession').html(),
    		str 		= $(this).parent().find('.face img').attr('src'),
    		n 			= str.lastIndexOf("/"),
    		userInfo[3] = str.substring(n+1);
    	$(this).attr('href',"tel:"+userInfo[0]);

		var url = 'm.php?app=user&func=addressbook&action=default&task=loadPage&from=list&task=addCall';
		$.post(url,{'userInfo':userInfo},function(json){
			if(json.success){
				var myTabs = Number($('#myTabs').attr('data-tab'));
				addressbookList('m.php?app=user&func=addressbook&action=default&task=loadPage&from=list&task=getOftenCall');
			}
			return false;
		},'json')

    })

    //点击删除
    $(document).on('touchstart','.delete',function(){
        var username = $(this).parent().find('.name').html()
        var text  	 = '是否删除 "<span style="color:red">'+username+'</span>" 联系人?';
        var cid = $(this).closest('.btnView').attr('data-cid');
		$.confirm({
		    title: '',
		    content: text,
		    animation: "top",
		    cancelButtonClass: 'btn-danger',
		    confirmButton: '确定',
			cancelButton: '取消',
		    confirm: function(){
		    	var url = 'm.php?app=user&func=addressbook&action=default&task=loadPage&from=list&task=deleteCall'
		    	$.post(url,{'cid':cid},function(json){
					if(json.success){
						var myTabs = Number($('#myTabs').attr('data-tab'));
						addressbookList('m.php?app=user&func=addressbook&action=default&task=loadPage&from=list&task=getOftenCall');
					}
				},'json')
		    }
	    })
	    return false;
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
	$(document).on('click','#outside',function(){
		window.location.href = 'm.php?app=user&func=addressbook&action=default&task=loadPage&from=common';
	});

	//跳转到组织架构
	$(document).on('click','#struct',function(){
		window.location.href = 'm.php?app=user&func=addressbook&action=default&task=loadPage&from=organization';
	});
})

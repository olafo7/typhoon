var nums = 0;

var myFns = {
	/*
	 *获取分组列表
	 *ajax成功返回数据`追加内容`初始化
	 *json：ajax请求成功返回的data
	 */
	get_select_list: function(json,isEmpty){
		if(isEmpty){ //不是上拉加载就清空数据区
			$("#select_c").empty();
		}
		if(json.success && json.data.length > 0){
			$.each(json.data, function(index,array){
				var str = "<option value="+array['id']+">"+array['name']+"</option>";
				$('#select_c').append(str);
			})
		}
	},
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
	}
}

$(function(){
	// 初始化表情
    $('.post_emotion').qqFace({
        id : 'facebox', //表情盒子的ID
        assign:'post_content', //给那个控件赋值
        path:'resources/images/m/qqface/face/', //表情存放的路径
        type:'new_post', //表情存放的路径
        click:'post_emotion' //表情存放的路径
    });

    
    $(document).on('touchstart','.post_emotion',function(){
    	myScroll.refresh();
    })

    // 初始化内容
    myFns.loaded();

    // 初始化帖子分类
    get_select('m.php?app=news&func=bbs&action=bbs&task=getSelect');

    // 获取帖子分类
    function get_select(url){
    	$.ajax({
			dataType: "json",
			type: "post",
			url: url,
			success: function(json){
				//提交成功后调用
				myFns.get_select_list(json, true);
			},
			complete: function(XMLHttpRequest, textStatus){
				$(".ui-ios-overlay").removeClass("ios-overlay-show").addClass("ios-overlay-hide");
			}
		})
    }

    // 显示位置菜单
    $(document).on('touchstart','.address',function(){
		$('#jingle_popup').slideDown(100);
	    $('#jingle_popup_mask').show();
	    return false;
    })

    // 获取我的位置
    $(document).on('touchstart','#chose_address',function(){
		var winH= $(window).height();
    	$('#mapPage').attr('src','http://apis.map.qq.com/tools/locpicker?policy=1&type=1&key=OB4BZ-D4W3U-B7VVO-4PJWW-6TKDJ-WPB77&referer=myapp');
    	$('#mapPage').css('height',winH+'px');
    	$('#mapPage').show();
    	window.addEventListener('message', function(event) {
	        // 接收位置信息，用户选择确认位置点后选点组件会触发该事件，回传用户的位置信息
	        var loc = event.data;
	        if (loc && loc.module == 'locationPicker') {//防止其他应用也会向该页面post信息，需判断module是否为'locationPicker'
	        	if (loc.poiname == '我的位置') {
	        		loc.poiname = loc.poiaddress;
	        	}
	            $('.address').html(loc.cityname+loc.poiname);
	            $('#mapPage').attr('src','');
	            $('#mapPage').hide();
	            $('#jingle_popup').slideUp(100);
   				$('#jingle_popup_mask').hide();
	            nums = 1;
	        }                                
	    }, false);
    })

    // 取消按钮
	$(document).on('touchstart','.btn-cancel',function(){
	    $('#jingle_popup').slideUp(100);
	    $('#jingle_popup_mask').hide();
	    return false;
	});

	//关闭弹出窗口 
	$(document).on('touchstart','#jingle_popup_mask',function(event){
		if( $(event.target).is(this)) { 
	       $(this).hide();
	       $('#jingle_popup').slideUp(100);
	    }
	    return false;
	})

   	// 输入判断文字
	$('.post_content').bind('input propertychange', function() {
    	var content = $('.post_content').val();
		var count = content.length;
		var num   = Math.abs(200-count);
		$('.post_c_nums span').text(num);
		if (count > 200) {
			$('.post_c_nums label').text('字数超出');
			$('.post_c_nums span').css('color','red');
		} else {
			$('.post_c_nums label').text('还可以输入');
			$('.post_c_nums span').css('color','#C9C9CA');
		}
	});

    // 帖子提交
    $(document).on('touchstart','#post_sub',function(){
    	var title = $('.post_t').val();
    	var content = $('.post_content').val();
    	var fid = $("#select_c").find("option:selected").val();
    	var address = $('.address').html();
    	if (address == '所在位置') {
    		address = '';
    	}
    	if (!title || !content) {
    		title = title ? '内容' : '标题';
			jError( title+'不能为空！',{
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

    	//附件信息
		var upload_attach = '';
		$("input[name='filesUpload[]']").each(function(i){
			if(i==0){
				upload_attach += '0' + $(this).val();
			}else{
				upload_attach += ',' + '0' + $(this).val();
			}
		})

    	$("#myTableFrom").ajaxSubmit({
			dataType: "json",
			type: "post",
			data: {
				'title': title,
				'filesUpload': upload_attach,
				'address': address,
				'fid': fid
			},
			url: "m.php?app=news&func=bbs&action=bbs&task=newPost",
			beforeSend: function(){
				//提交表单前验证
				showLoading();
			},
			success: function(json){
				hideLoading();
				//提交成功后调用
				if(json.success){
					//添加成功跳转论坛中心
					setTimeout(function(){
						window.location.href = 'm.php?app=news&func=bbs&action=bbs&task=loadPage&from=main';
					},1400)
				} else {
					$.confirm({
					    title: '提示',
					    content: json.msg,
					    animation: "top",
					    cancelButtonClass: 'btn-danger',
					    confirmButton: '确定',
						cancelButton: ''
					})
					return false;
				}
			}
		})
    })
    //添加附件`初始化上传附件表单
	$(document).on('click','#fileupload',function(){
		var myUpload = $("#myUpload");
		var url = 'm.php?action=commonJob&task=upFile';
		if(!myUpload.length){//判断表单form是否存在
			$("#fileupload").wrap("<form id='myUpload' action='' method='post' enctype='multipart/form-data'></form>"); 
			$("#myUpload").attr("action",url);
		}
	});

    // 选择后上传附件
    $(document).on('change','#fileupload',function(){
    	var filePath = $('#fileupload').val();
		if(!filePath){
			return false;
		}
		var filename  = filePath.replace(/.*(\/|\\)/, ""); //文件名带后缀
		var fileSplit = (/[.]/.exec(filename)) ? /[^.]+$/.exec(filename.toLowerCase()) : ''; //文件后缀fileExt[0]
		var fileArr 	= ['jpg','jpeg','gif','png','bmp','rar','zip','doc','docx','wps','wpt','ppt','xls','txt','csv','et','ett','pdf'];
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
						$('.post_opt').after(attachGroupStr);
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

	// 返回论坛中心
    $(document).on('touchstart','#btnBack',function(){
    	window.location.href = 'm.php?app=news&func=bbs&action=bbs&task=loadPage&from=main';
    	return false;
    })
})
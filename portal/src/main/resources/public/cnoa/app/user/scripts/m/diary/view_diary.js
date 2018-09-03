
var arr =[];
var sarr =[];
var carr = [];
if(/android/ig.test(navigator.userAgent)){ //安卓
	var userAgent = 'android';
}else{ //ios或者其他
	var userAgent = 'ios';
}
String.prototype.replaceAll = function(reallyDo, replaceWith, ignoreCase) {  
   if (!RegExp.prototype.isPrototypeOf(reallyDo)) {  
       return this.replace(new RegExp(reallyDo, (ignoreCase ? "gi": "g")), replaceWith);  
   } else {  
       return this.replace(reallyDo, replaceWith);  
   }  
}
var myFns = {
	//初始化iScroll控件
	loaded:function(){
		myScroll = new iScroll('wrapper', {
			vScrollbar:false, //隐藏滚动条
	    	mouseWheel: true,
	    	wheelAction: 'zoom',
			preventDefault: false, //阻止默认事件
			preventDefaultException: { tagName: /^(INPUT|TEXTAREA|BUTTON|SELECT|A)$/ } //这个后面加|A,因为iscroll阻止了A的默认事件
		});
		document.addEventListener('touchmove', function (e) { e.preventDefault(); }, false);
	},
	//获取url参数
	getUriString: function(key){
		var reg = new RegExp("(^|&)"+ key +"=([^&]*)(&|$)");
    	var r = window.location.search.substr(1).match(reg);
    	if(r!=null)return  unescape(r[2]); return null;
	},
	//判断后缀是不是图片类型
	is_images:function(ext){
		var ext = ext.toLowerCase();//转小写
		var arr = ['jpg','jpeg','png','gif','bmp'];//图片格式
		if($.inArray(ext,arr)<0){
			return false;
		}
		return true;
	},
	isWeixn: function (){  
	  var ua = navigator.userAgent.toLowerCase();  
	  if(ua.match(/MicroMessenger/i)=="micromessenger") {  
	    return true;  
	  } else {  
	    return false;  
	  }  
	},
	showView: function(json){
		if(json.data.additional.length<=0){
			$('.supplement').css('display','none');
		}
		if(json.data.comment.length<=0){
			$('.comment').css('display','none');
		}
		$.each(json.data.additional,function(index,array){
			sarr.push(array['content']+','+array['done']);
		});
		$.each(json.data.plan,function(index,array){
			if(array['supplement']=='0'){
				arr.push(array['content']+','+array['done']);
			}
		});
		$.each(json.data.comment,function(index,array){
			carr.push(array['content']+','+array['truename']);
		});
		
		$.each(json.data.attach,function(index,array){
			var fileSrc = array['url'];
			var filename = array['name'];
			var ext = array['ext'];
			var str = '';
				str+= '<li class="list-group-item todayplan fs"><span class="attachIndex">'+filename;
				str+= '</span><span class="fsopt">';
				if(userAgent =='android' && !myFns.is_images(ext)){
					str+= '<button data-src="'+fileSrc+'" type="button" class="btn btn-info download">下载</button>';
				}else{
					str+= '<button data-src="'+fileSrc+'" type="button" ext="'+ext+'" class="btn btn-info routine">浏览</button>';
					ImagesZoom.init({
					"elem": ".routine"
					});
				}
				str+= '<button type="button" class="btn btn-danger del">删除</button></span>';
				str+= '</li>';
			$('.fslist').append(str);
			// $('.fslist').find('a').removeAttr('href');
		});
		// 载入今日计划
		var str ='',state='';
		for(var i=0;i<arr.length;i++){
			var cont = arr[i].split(',');
			if(cont[1]=='1'){
				state = '<button type="button" class="btn btn-xs status btn-info">已完成</button>';
			}else{
				state = '<button type="button" class="btn btn-xs status btn-success">未完成</button>';
			}
			str+='<li class="list-group-item todayplan"><span class="plancon">'+(i+1)+'、&nbsp;'+cont[0]+'</span>'+state+'</li>';
		}
		$('.planlist').append(str);

		var str='',str1='',str2='';

		var data = json.data.plan;
		$('.num').text('('+json.data.planTotal+')');
		$('.num1').text('('+sarr.length+')');
		if(json.data.planTotal==0){
			$('.enter').removeClass('glyphicon-collapse-down');
		}
		$('.enter').attr('data-id',json.data.plan[0].did);
		$('.count').append(json.data.commentTotal);
		$('.summary').append(json.data.summary);
		myScroll.refresh();
	}
}
jQuery.plugin = {
	init: function() {
		this.closeMask();
	}, 
	showWin: function(width, height, title, content) {
		$('#pluginModal').css('display', 'block');
		this.init();
	},
	closeMask: function() {
		
    }
}
$(function(){

	myFns.loaded();
	var date = decodeURI(myFns.getUriString('date'));
	var mydate = new Date();
	if(date.indexOf('年')<0){
		var y = date.substr(0,4);
		var m = date.substr(4,2);
		var d = date.substr(6,2);
		mydate.setFullYear(y,m,d);
	}else{
		date = date.substr(0,11);
		date = date.replace('年','/');
		date = date.replace('月','/');
		date = date.replace('日','');
		var darr = date.split('/');
		mydate.setFullYear(darr[0],darr[1]-1,darr[2]);
	}
	var nowdate = new Date();
	if(mydate>=nowdate){
		$('.diarysu').css('display','none');
		$('.add').css('display','block');
		$('#fileupload').css('display','');
	}else{
		$('.diarysu').css('display','block');
		$('.add').css('display','none');
		$('#fileupload').css('display','none');
	}

	$(document).on('touchstart','#btnAdd',function(){
		$('#menu').modal('show');
		return false;
	});
    function getJsonData(url){
    	$.ajax({
    		dataType:'json',
    		method:'post',
    		url:url,
    		data:{'date':date},
    		success:function(json){
    			myFns.showView(json);    		
    		}
    	})
    }
    getJsonData('m.php?app=user&func=diary&action=index&task=viewDiary');


	$(document).on('touchstart','#btnBack',function(){
		window.location.href="m.php?app=user&func=diary&action=index&task=loadPage";
	});

	$(document).on('touchstart','.au',function(){
		window.location.href="m.php?app=user&func=diary&action=index&task=loadPageAddSummary&date="+date;
	});

	//附件上传
	$(document).on('click','#fileupload',function(){
		var myUpload = $("#myUpload");
		var url = 'm.php?action=commonJob&task=upFile';
		if(!myUpload.length){//判断表单form是否存在
			$("#fileupload").wrap("<form id='myUpload' action='' method='post' enctype='multipart/form-data'></form>"); 
			$("#myUpload").attr("action",url);
		}
	});
	$('#fileupload').change(function(){
		var lujin = $(this).val();
		var size = $('#fileupload')[0].files[0].size;
		var msize= parseInt(size/1024/1024);
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
		}
		var pos  = lujin.lastIndexOf("\\");
		var filename = lujin.substring(pos+1);
		$('#myUpload').ajaxSubmit({
			dataType:  'json',
        	success:function(json){
        		showLoading();
        		var data = json.msg;
        		if(json.success){
        			var date = new Date();
					var dd = date.getFullYear()+'-'+(date.getMonth()+1)+'-'+date.getDate();
			  		var did  = $('.enter:eq(0)').attr('data-id');
        			var filesUploadData = '||'+data.oldname+'||'+data.name+'||'+data.size;
        			$.ajax({
			    		dataType:'json',
			    		method:'post',
			    		url:'m.php?app=user&func=diary&action=index&task=addAttach',
			    		data:{did:did,filesUpload:filesUploadData,date:dd,job:'add'},
			    		success:function(json){
			    			hideLoading();
			    			if(json.success){
			    				var str = '';
			        				str+= '<li class="list-group-item todayplan fs"><span class="attachIndex">'+data.oldname;
			        				str+= '</span><span class="fsopt"><button type="button" class="btn btn-info routine">浏览</button>';
			        				str+= '<button type="button" class="btn btn-danger del">删除</button></span>';
			        				str+= '<input type="hidden" name="filesUpload[]" class="data" value="'+filesUploadData+'">';
			        			$('.fslist').append(str);
			        			$('#menu').modal('hide');
			    			}   		
			    		}
			    	})
        			
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
        	}
		});
	});
	//下载
	$(document).on('touchstart','.download',function(){
		var href = $(this).attr('data-src');
		window.location.href = href;
	});
	//浏览
	$(document).on('touchstart','.routine',function(){
		var width = $(window).width(), height = $(window).height();
		var fileExt = $(this).attr('ext');
		var fileSrc = $(this).attr('data-src');
		var fileName= $(this).parents('.fsopt').parents('.fs').find('.attachIndex').text();
		// console.log(fileName);	
		var host = window.location.host; //当前域名
		var fileAllSrc = host+'/'+fileSrc;
		if(myFns.isWeixn()) {
			if (userAgent == "android" && !myFns.is_images(fileExt)) {
				popWin.showWin(width, height, fileName, fileSrc);
			}else if(userAgent == "android" && myFns.is_images(fileExt)){
				ImagesZoom.init({
					"elem": ".routine"
				});
			}else if(myFns.is_images(fileExt)) {
				ImagesZoom.init({
					"elem": ".routine"
				});
			}else{
				$('iframe').find('body').attr('background-color','white');
				iospopWin.showWin(width, height, fileName, fileSrc,fileExt.toLocaleLowerCase());
			}
		} else {
			try {
				CNOAApp.viewDocument(fileAllSrc, fileName, fileExt);
			} catch(e) {
				if(userAgent == 'android' && myFns.is_images(fileExt)){
					$('.imgzoom_pack').css('display','block');
					$('.imgzoom_img img').attr('src',fileSrc);
				}
				window.location.href = 'js://push_web_view_controller__'+fileAllSrc+'__#'+fileName;
			}
		}
	});
	//删除
	$(document).on('touchstart','.del',function(){
		var date = new Date();
		var dd = date.getFullYear()+'-'+(date.getMonth()+1)+'-'+date.getDate();
  		var did  = $('.enter:eq(0)').attr('data-id');
		var oldname = $(this).parents().prev('.attachIndex').text();
		$.ajax({
    		dataType:'json',
    		method:'post',
    		url:'m.php?app=user&func=diary&action=index&task=addAttach',
    		data:{did:did,oldname:oldname,date:dd,job:'del'},
    		success:function(json){
    			if(json.success){
    				jSuccess('删除成功！',{
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
					     	window.location.reload();
					     }
					});
    			}
    		}
    	});
	});
	$(document).on('touchstart','.cancel',function(){
		$('#menu').modal('hide');
	})
	// 折叠
	// planlist
	$(document).on('touchstart','.enter',function(){
		if($(this).hasClass('glyphicon-collapse-up')){
			$(this).removeClass('glyphicon-collapse-up').addClass('glyphicon-collapse-down');
			$('.planlist').empty();
		}else{
			$(this).removeClass('glyphicon-collapse-down').addClass('glyphicon-collapse-up');
			var str ='',state='';
			for(var i=0;i<arr.length;i++){
				var cont = arr[i].split(',');
				if(cont[1]=='1'){
					state = '<button type="button" class="btn btn-xs status btn-info">已完成</button>';
				}else{
					state = '<button type="button" class="btn btn-xs status btn-success">未完成</button>';
				}
				str+='<li class="list-group-item todayplan"><span class="plancon">'+(i+1)+'、&nbsp;'+cont[0]+'</span>'+state+'</li>';
			}
			$('.planlist').append(str);
			myScroll.refresh();
		}
	});
	// supplementlist
	$(document).on('touchstart','.supplement',function(){
		if($(this).hasClass('glyphicon-collapse-down')){
			$(this).removeClass('glyphicon-collapse-down').addClass('glyphicon-collapse-up');
			var str1='';
			for(var i=0;i<sarr.length;i++){
				var cont = sarr[i].split(',');
				if(cont[1]=='1'){
					state = '<button type="button" class="btn btn-xs status btn-info">已完成</button>';
				}else{
					state = '<button type="button" class="btn btn-xs status btn-success">未完成</button>';
				}
				str1+='<li class="list-group-item todayplan"><span class="plancon">'+(i+1)+'、&nbsp;'+cont[0]+'</span>'+state+'</li>';
			}
			$('.supplist').append(str1);
			myScroll.refresh();
		}else{
			$(this).removeClass('glyphicon-collapse-up').addClass('glyphicon-collapse-down');
			$('.supplist').empty();
		}
	});
	//comment diary
	$(document).on('touchstart','.comment',function(){
		if($(this).hasClass('glyphicon-collapse-down')){
			$(this).removeClass('glyphicon-collapse-down').addClass('glyphicon-collapse-up');
			var str1='';
			for(var i=0;i<carr.length;i++){
				var cont = carr[i].split(',');
				state = '<span class="state">'+cont[1]+'</span>';
				str1+='<li class="list-group-item todayplan"><span class="plancon">'+(i+1)+'、&nbsp;'+cont[0]+'</span>'+state+'</li>';
			}
			$('.commentlist').append(str1);
			myScroll.refresh();
		}else{
			$(this).removeClass('glyphicon-collapse-up').addClass('glyphicon-collapse-down');
			$('.commentlist').empty();
			myScroll.refresh();
		}
	});
	//supplement diary
	$(document).on('touchstart','.diarysu',function(){
		window.location.href="m.php?app=user&func=diary&action=index&task=addEditAdditional&date="+date;
	})
})
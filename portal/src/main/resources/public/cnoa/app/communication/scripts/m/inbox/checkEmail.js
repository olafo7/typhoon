var myScroll, fenfaScroll; //全局对象 


if(/android/ig.test(navigator.userAgent)){ //安卓
	var userAgent = 'android';
}else{ //ios或者其他
	var userAgent = 'ios';
}
var myFns = {
	//初始化iScroll控件
	loaded:function(){
		myScroll = new iScroll('wrapper', {
			hScroll: true ,
			vScroll: true ,
			zoom:true,
			hScrollbar: false ,
			vScrollbar: false ,
			scrollbarClass: 'myScrollbar',
			wheelAction: 'scroll'
		});
		document.addEventListener('touchmove', function (e) { e.preventDefault(); }, false);
	},
	loadeds:function(element){
			//初始化绑定iScroll控件
			myScrolls = new iScroll(element, {
			scrollbarClass: 'myScrollbar',
			wheelAction: 'scroll'
		});
		setTimeout(function () { document.getElementById(element).style.left = '0'; }, 800);
		document.addEventListener('touchmove', function (e) { e.preventDefault(); }, false);
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
	//获取url参数
	getUriString: function(key){
		var reg = new RegExp("(^|&)"+ key +"=([^&]*)(&|$)");
    	var r = window.location.search.substr(1).match(reg);
    	if(r!=null)return  unescape(r[2]); return null;
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

	$(document).on('touchstart','#btnAdd',function(){
		$('#menu').modal('show');
		return false;
	});

	var type = myFns.getUriString('type');
	if(type =='hassend'){
		$('.menu_ada:lt(4)').remove();
		$('.modal-body').append('<span class="menu_ada"><button type="button" class="btn btn-default btn-lg ab forward">转发邮件</button></span><br><br>');
		$('.modal-body').append('<span class="menu_ada"><button type="button" class="btn btn-default btn-lg ab delete">删除邮件</button></span><br><br>');
		$('.modal-body').append('<span class="menu_ada"><button type="button" class="btn btn-default btn-lg ab cancel" data-dismiss="modal">取消</button></span>');
	}

	function getJsonData(url){
		setTimeout(function(){
		var fids=$("#fid").val();
		$.ajax({
			url: url,
			dataType: 'json', 
			method: 'post',
			data: {fids:fids,type:type},
			success:function(json){
				if(json.success == true){
					$('.title').text(json.data.title);
					$('.time1').text(json.data.posttime);
					$('.time:eq(0)').text(json.data.sendname);
					$('.time:eq(1)').text(json.data.receivename);
					$('.time:eq(2)').text(json.data.cc_names);
					$('.cont').append(json.data.content);
					//判断是否未读
					if(json.data.isread == '0'){
						$.ajax({
							url: "m.php?app=communication&func=message&action=index&task=markRead",
							dataType: 'json',
							method: 'post',
							data: {fids:fids},
							success:function(json){
								$('.title').attr('ir','1');
							}
						})
					}

					var length = json.data.attach.length;
					if(length > 1){
						for(var i=0;i<json.data.attach.length;i++){
							$(".fs").append('<div class="collapse"><a href="javascript:void(0);">'+json.data.attach[i]['name']+'</a><span class="glyphicon glyphicon-collapse-down collapse_down"></span>');
							$(".fs").append('<div class="ada"><button type="button" class="btn btn-primary btn-lg aa btnBrowse" data-ext="'+json.data.attach[i]['ext']+'" data-name="'+json.data.attach[i]['name']+'" data-src="'+json.data.attach[i]['url']+'">在线预览</button><button type="button" class="btn btn-primary btn-lg aa btnDownload"  data-src="'+json.data.attach[i]['url']+'">下载文件</button></div>');
							$(".ada").hide();
							// 如果是Android/图片，显示在线预览
							if(userAgent=='android' && myFns.is_images(json.data.attach[i]['ext'])){
								$(".collapse_down").parents('.collapse').next().find(".btnBrowse").eq(i).show();
								$(".collapse_down").parents('.collapse').find(".collapse_down").eq(i).show();
								ImagesZoom.init({
						    		"elem": ".btnBrowse"
								});
							}else if(userAgent=='android' && !myFns.is_images(json.data.attach[i]['ext'])){
								$(".collapse_down").parents('.collapse').next().find(".btnBrowse").eq(i).hide();//hide
								$(".ada").eq(i).css('height','55px');
								$(".btnDownload").eq(i).css('margin-top','-1px');
							}else{//ios
								$(".collapse_down").parents('.collapse').next().find(".btnBrowse").eq(i).show();
								$(".collapse_down").parents('.collapse').next().find(".btnDownload").eq(i).hide();
								$(".ada").eq(i).css('height','55px');
								$(".collapse_down").parents('.collapse').find(".collapse_down").eq(i).show();
							}
						}
					}else if(length ==1){
						$(".fs").append('<div class="collapse"><a href="javascript:void(0);">'+json.data.attach[0]['name']+'</a><span class="glyphicon glyphicon-collapse-up collapse_down"></span>');
						$(".fs").append('<div class="ada"><button type="button" class="btn btn-primary btn-lg aa btnBrowse" data-ext="'+json.data.attach[0]['ext']+'" data-name="'+json.data.attach[0]['name']+'" data-src="'+json.data.attach[0]['url']+'">在线预览</button><button type="button" class="btn btn-primary btn-lg aa btnDownload"  data-src="'+json.data.attach[0]['url']+'">下载文件</button></div>');
						// 如果是Android/图片，显示在线预览
						if(userAgent=='android' && myFns.is_images(json.data.attach[0]['ext'])){
							$(".collapse_down").parents('.collapse').next().find(".btnBrowse").show();
							$(".collapse_down").parents('.collapse').find(".collapse_down").show();
							ImagesZoom.init({
					    		"elem": ".btnBrowse"
							});
						}else if(userAgent=='android' && !myFns.is_images(json.data.attach[0]['ext'])){
							$(".collapse_down").parents('.collapse').next().find(".btnBrowse").hide();//hide
							$(".ada").css('height','55px');
							$(".btnDownload").css('margin-top','-1px');
						}else{//ios
							$(".collapse_down").parents('.collapse').next().find(".btnBrowse").show();
							$(".collapse_down").parents('.collapse').next().find(".btnDownload").hide();
							$(".ada").css('height','55px');
							$(".collapse_down").parents('.collapse').find(".collapse_down").show();
						}
						
					}
					
			        setTimeout(function(){
						myScroll.refresh();
					},100);
				}
			}
		});
		},0)
	}
	getJsonData("m.php?app=communication&func=message&action=index&task=getMailInfo");
	
	//在线预览文件
	$(document).on('click','.btnBrowse',function(){
		var fileSrc  = $(this).attr('data-src'); //文件地址
		var fileName = $(this).attr('data-name'); //文件名带后缀
		var fileExt  = $(this).attr('data-ext'); //文件后缀
		var seat = fileName.lastIndexOf("."); //查找某字符在字符串中最后一次出现的位置
		fileName = fileName.substring(0, seat); //纯文件名
		var host = window.location.host; //当前域名
		var fileAllSrc = host+'/'+fileSrc; //文件绝对路径
		var width = $(document).width(), height = $(document).height();

		if(myFns.isWeixn()) {
			if (userAgent == "android" && !myFns.is_images(fileExt)) {
				popWin.showWin(width, height, fileName, fileSrc);
			}else if(userAgent == "android" && myFns.is_images(fileExt)){
				ImagesZoom.init({
					"elem": ".btnBrowse"
				});
			}else if(myFns.is_images(fileExt)) {
				ImagesZoom.init({
					"elem": ".btnBrowse"
				});
			}else{
				$('iframe').find('body').attr('background-color','white');
				iospopWin.showWin(width, height, fileName, fileSrc,fileExt.toLocaleLowerCase());
			}
		} else {
			try {
				CNOAApp.viewDocument(fileAllSrc, fileName, fileExt);
			} catch(e) {
				window.location.href = 'js://push_web_view_controller__'+fileAllSrc+'__#'+fileName;
			}
		}
	});

	//回复邮件
	$(document).on('click','.reply',function(){
		var fid = $('#fid').val();
		window.location.href='m.php?app=communication&func=message&action=index&task=loadPage&from=writeEmail&opt=reply&fid='+fid;
	});
	//转发邮件forward
	$(document).on('click','.forward',function(){
		var fid = $('#fid').val();
		if(type =='hassend'){
			window.location.href='m.php?app=communication&func=message&action=index&task=loadPage&from=writeEmail&opt=forward&emailType=outbox&fid='+fid;
		}else{
			window.location.href='m.php?app=communication&func=message&action=index&task=loadPage&from=writeEmail&opt=forward&fid='+fid;
		}
		
	});
	//删除
	$(document).on('click','.delete',function(){
		// console.log(12);
		var fids = [];
		fids.push(myFns.getUriString('fids'));
		swal({ 
		    title: "您确定要删除吗？", 
		    type: "warning", 
		    showCancelButton: true, 
		    closeOnConfirm: false, 
		    confirmButtonText: "删除", 
		    confirmButtonColor: "#ec6c62" 
		},function(){
			$.ajax({
				dataType:'json',
				data:{'fids':fids},
				method:'post',
				url:'m.php?app=communication&func=message&action=index&task=delHassend',
				success:function(json){
					window.location.href="m.php?app=communication&func=message&action=index&task=loadPage&from=hassend";
				}
			})
		});
	});

	myFns.loaded();

	//折叠
	$(document).on('touchstart','.collapse_down',function(){
		if($(this).hasClass("glyphicon-collapse-down")){
			$(this).parent().next().show("fast",function(){
				$(this).prev().children('span').removeClass('glyphicon glyphicon-collapse-down').addClass('glyphicon glyphicon-collapse-up');
			});
		}else{
			$(this).parent().next().hide("fast",function(){
				$(this).prev().children('span').removeClass('glyphicon glyphicon-collapse-up').addClass('glyphicon glyphicon-collapse-down');
			});
		}
		setTimeout(function(){
			myScroll.refresh();
		},200);
	});

	//下载文件
	$(document).on('touchstart','.btnDownload',function(){
		var url = $(this).attr('data-src');
		window.location.href=url;
	});

	//移动到
	$(document).on('touchstart','.move',function(){
		var fid = $("#fid").val();
		// console.log(escape(fid));
		window.location.href = 'm.php?app=communication&func=message&action=index&task=loadPage&from=remove&fids='+fid;
	});

	//返回上一页
	$(document).on('touchstart','#btnBack',function(){
		var type = myFns.getUriString('type');
		var name = decodeURI(myFns.getUriString('name'));
		var id = myFns.getUriString('id');
		if(type == 'accept'){
			window.location.href='m.php?app=communication&func=message&action=index&task=loadPage&from=acceptEmail';
		}else if(type == 'hassend'){
			window.location.href='m.php?app=communication&func=message&action=index&task=loadPage&from=hassend';
		}else if(type == 'checkfolder'){
			window.location.href = encodeURI(encodeURI('m.php?app=communication&func=message&action=index&task=loadPage&from=checkFolder&name='+name+'&fid='+id));
		}
	});

	$(document).on('touchstart','.cancel',function(){
		$('#menu').modal('hide');
	})
})
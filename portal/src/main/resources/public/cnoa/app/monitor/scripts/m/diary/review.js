var type='now';//默认为今年
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
	//获取url参数
	getUriString: function(key){
		var reg = new RegExp("(^|&)"+ key +"=([^&]*)(&|$)");
    	var r = window.location.search.substr(1).match(reg);
    	if(r!=null)return  unescape(r[2]); return null;
	},
	showView:function(json,flag){
		var str = '';
		var temp = [];
		if(flag){
			$('.dateContent').empty();
		}
		// console.log(json);
		if(json.failure == true){
			jNotify(json.msg,{
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
		if(json.success == true){
			$.each(json.data,function(index,array){
				temp.push(index);
				temp=temp.sort(function(a,b){
					return b-a;
				});
			});		
			for(var i=0;i<temp.length;i++){
				str = '<div class="contain"><div class="showMonth"><span class="month"><span class="glyphicon glyphicon-minus open"></span>'+temp[i]+'月份'+'('+json.data[temp[i]].length+'条)</span></div>';
				$.each(json.data[temp[i]],function(index,arr){
						str+= '<div class="showdate"><div class="left">';
						str+= '<div class="title" id="'+arr['id']+'" data-id="'+arr['did']+'">'+arr['plantime']+'</div>';
						str+= '<div class="user">'+arr['user']+'</div>';
						str+= '<div class="time">'+arr['content']+'</div>';
						str+= '</div><div class="right">';
						if(arr['isSign'] == '1'){
							str+= '<button type="button" class="btn btn-success sign">签收</button>';
						}else{
							str+= '';
						}
						str+= '<button type="button" class="btn btn-primary review">评阅</button></div>';
						str+= '</div>';
						// str+='<br>';
				});
				str+='</div>';
				$('.dateContent').append(str);
			}
			myScroll.refresh();
		}
	}
}
$(function(){
	myFns.loaded();
	var uid = myFns.getUriString('uid');
	var date = new Date();
	var year = date.getFullYear();
	var uid  = myFns.getUriString('uid');
	//加载数据
	getJsonData('m.php?app=monitor&func=mgr&action=diary&task=getDiaryData&from=reviewIndex');
	function getJsonData(url){
		$.ajax({
			url: url,
			dataType: 'json', 
			method: 'post',
			data: {uid:uid,type:type,year:year},
			success:function(json){
				myFns.showView(json);
			}
		});
	}

	//last year
	$(document).on('touchstart','.prev',function(){
		year = year-1;
		type = 'prev';
		$.ajax({
			url: 'm.php?app=monitor&func=mgr&action=diary&task=getDiaryData&from=reviewIndex',
			dataType: 'json', 
			method: 'post',
			data: {uid:uid,type:type,year:year},
			success:function(json){
				myFns.showView(json,true);
			}
		});
	});
	//next year
	$(document).on('touchstart','.next',function(){
		year = year+1;
		type = 'next';
		$.ajax({
			url: 'm.php?app=monitor&func=mgr&action=diary&task=getDiaryData&from=reviewIndex',
			dataType: 'json', 
			method: 'post',
			data: {uid:uid,type:type,year:year},
			success:function(json){
				myFns.showView(json,true);
			}
		});
	});
	//now year
	$(document).on('touchstart','.now',function(){
		year = date.getFullYear();
		type = 'now';
		$.ajax({
			url: 'm.php?app=monitor&func=mgr&action=diary&task=getDiaryData&from=reviewIndex',
			dataType: 'json', 
			method: 'post',
			data: {uid:uid,type:type,year:year},
			success:function(json){
				myFns.showView(json,true);
			}
		});
	});
	//搜索
	$(document).on('keyup','#search',getSearchData);
	function getSearchData(){
		var search = $('#search').val();
		//null
		$.ajax({
			url: 'm.php?app=monitor&func=mgr&action=diary&task=getDiaryData&from=reviewIndex',
			dataType: 'json', 
			method: 'post',
			data: {uid:uid,type:type,year:year,query:search},
			success:function(json){
				myFns.showView(json,true);
			}
		});
	}
	$(document).on('touchstart','.open',function(){
		var showdate = $(this).parents('.contain').find('.showdate');
		showdate.css('display','none');
		$(this).removeClass('glyphicon glyphicon-minus').addClass('glyphicon glyphicon-plus');
		$(this).addClass('clo').removeClass('open');
	});
	$(document).on('touchstart','.clo',function(){
		var showdate = $(this).parents('.contain').find('.showdate');
		showdate.css('display','block');
		$(this).removeClass('glyphicon glyphicon-plus').addClass('glyphicon glyphicon-minus');
		$(this).addClass('open').removeClass('clo');
	});
	
	//jump to review view
	$(document).on('click','.review',function(){
		var did = $(this).parents('.showdate').find('.title').attr('data-id');
		window.location.href="m.php?app=monitor&func=mgr&action=diary&task=loadPage&from=reviewForm&did="+did;
	});

	//签收
	$(document).on('touchstart','.sign',function(){
		var id = $(this).parents('.right').prev('.left').find('.title').attr('id');
		setTimeout(function(){
			swal({   
			title: "签收意见",   
			confirmButtonText: "确认",   
			type: "input",   
			showCancelButton: true,   
			closeOnConfirm: false,   
			//animation: "slide-from-top",   
			inputPlaceholder: "请输入您的签收意见" 
		}, function(inputValue){
			if (inputValue === false){ //取消按钮
				return false;      
			}
			if (inputValue === "") {     
				swal.showInputError("请输入签收意见!");     
				return false;   
			}
			$('.sweet-overlay').css('display','none'); //隐藏输入框
			$('.sweet-alert').css('display','none');
			Sign(inputValue,id);
		});	
		},500);
	});
	function Sign(text,id){
		$.ajax({
			url: 'index.php?app=monitor&func=mgr&action=diary&task=signfor',
			dataType: 'json', 
			method: 'post',
			data: {signContent:text,id:id},
			success:function(json){
				if(json.success == true){
					jSuccess(json.msg,{
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
				     		window.location.href="m.php?app=monitor&func=mgr&action=diary&task=loadPage&from=reviewIndex&uid="+uid;
				     	}
					});
				}
			}
		});
	}	

	//back to last page
	$(document).on('touchstart','#btnBack',function(){
		window.location.href="m.php?app=monitor&func=mgr&action=diary&task=loadPage&from=reviewDiaryIndex";
	})
})
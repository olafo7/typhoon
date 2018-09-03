var pageNow = 1;
var monthData = [];
var newSwip=1;
var flag = '0';
var action = '';
var testid='';
var sdate = '';
var date = new Date();
String.prototype.replaceAll = function(reallyDo, replaceWith, ignoreCase) {  
   if (!RegExp.prototype.isPrototypeOf(reallyDo)) {  
       return this.replace(new RegExp(reallyDo, (ignoreCase ? "gi": "g")), replaceWith);  
   } else {  
       return this.replace(reallyDo, replaceWith);  
   }  
}
//全局变量`函数
var myFns = {
	//下拉刷新
	pull_down_refresh: function(){	
		$('#wrapper').css('top','419px');
		$('tbody tr').css('display','');
		$('.to').find('img').attr('src','resources/images/diary/up1.png');
		myFns.refreshFns(); //刷新界面
		pageNow = 1; //重置当前页为1
	},
	//刷新界面
	refreshFns: function(){
		var search = $('#search').val(); 
		var start = 0;
		var url = "m.php?app=user&func=diary&action=index&task=getAllDiary";
		$.post(url,{'start':start,'content':search,'date':sdate}, function(json){
			myFns.showView(json,true);
		},'json')
	},
	//上拉加载
	pullUpAction:function(){
		var start = (pageNow-1) * 15;
		var search = $('#search').val(); 
		var url = "m.php?app=user&func=diary&action=index&task=getAllDiary";
		var data = {'start':start,'content':search};
		$.post(url, data, function(json){
			myFns.showView(json,false);
		},'json');
	},
	//显示
	showView:function(json,isEmpty){
		var search = $('#search').val();
		var data; //显示数据
		if(isEmpty){
			$("#main").empty();
		}
		data = json.data;
		// 没有判断是否成功
		if(json.success || json.data.length > 0){
			if(data.length < 15){ //有超过一页的数据再显示滚动加载提示
				$("#pullUp").css("display","none");
	      		$("#scroller .stop-label").css("display","none");
			}else{
				$("#pullUp").css("display","block");
				$("#scroller .stop-label").css("display","none");
			}
			$.each(data,function(index,array){
				var coll = '';
				var str = '';
				var state = '';
				    str+=    '<div class="diary">';
				    if(array['done']=='1'){
				    	str+= '<span class="isread" style="background-color:white"></span>';
				    	state = '<span class="state" style="color:red;">已完成</span>';
				    }else{
				    	str+= '<span class="isread" style="background-color:#69b82d"></span>';
				    	coll+= '<div class="collbody">';
				    	coll+= '<button type="button" class="btn btn-success complete">设置完成</button>';
				    	coll+= '<button type="button" class="btn btn-warning update">修改</button>';
				    	coll+= '<button type="button" class="btn btn-danger delete">删除</button>';
				    	coll+= '<button type="button" class="btn btn-info dely" data-toggle="modal" data-target="#myModal">延期到</button>';
				    	coll+= '</div>';
				    	state = '<span class="state">未完成</span>';
				    }
				    str+=  '<div class="con"><br/>';
				    str+=  '<p class="title" data-mid="'+array['id']+'" data-id="'+array['did']+'">'+array['content']+'</p>';
				    str+=  '<p class="time">'+array['showDate'];
				    str+=  '</p>';
				    str+=  '</div>';
				    //完成状态
				    str+= state;
				    if(array['done']=='0'){
				    	str+='<span class="glyphicon enter glyphicon-collapse-down"></span>';
				    }
				    str+= '</div>';
				$('#main').append(str);
				$('#main').append(coll);
			})
			pageNow +=1;
		}else{
	    	$("#pullUp").css("display","none");
	    	$("#scroller .stop-label").css("display","block");
		}
		myScroll.refresh();
	},
	getNowDate:function(){
		//获取当天日期
		var year = date.getFullYear();
		var month= date.getMonth()+1;
		var day  = date.getDate();
		if(month<10){
			month = '0'+month;
		}
		if(day<10){
			day = '0'+day;
		}
		var nowDate = year+','+month+','+day;
		sdate = nowDate.replaceAll(',','');
		return nowDate;
	},
	getMonthData:function(y,m){
		var yearmonth = y+'-'+m;
		var defer = $.Deferred();
		$.ajax({
			dataType:'json',
			method:'post',
			url:'m.php?app=user&func=diary&action=index&task=loadMonthData',
			data:{yearmonth:yearmonth},
			success:function(json){
				 for(var i=0;i<json.data.length;i++){
                    monthData.push(json.data[i]['date']);  
                }
                defer.resolve(monthData);
			}
		})
		return defer.promise();	
	},
	//闰年
	isLeap :function(y) {
        return (y % 100 !== 0 && y % 4 === 0) || (y % 400 === 0);
    },
	//月份天数
	getDaysNum : function(y, m){
        var num = 31;
        switch (m) {
            case '02':
                num = myFns.isLeap(y) ? 29 : 28;
                break;
            case '04':
            case '06':
            case '09':
            case 11:
                num = 30;
                break;
        }
        return num;
    },
    //获取url参数
	getUriString: function(key){
		var reg = new RegExp("(^|&)"+ key +"=([^&]*)(&|$)");
    	var r = window.location.search.substr(1).match(reg);
    	if(r!=null)return  unescape(r[2]); return null;
	}
}
$(function(){

	var res  = myFns.getNowDate();
	var vd = myFns.getUriString('date');
	if(vd ==null){
		vd = '';
	}
	res = res.split(',');
	var year = res[0];//切换时会变
	var month= res[1];//切换时会变
	$.when(myFns.getMonthData(year,month)).done(function(data){	

		
		//加载日历控件
		$('#delytime').date({theme:"date",curdate:true});

		$.datepicker.regional["zh-CN"] = {showOn: "both", closeText: "关闭", prevText: "&#x3c;", nextText: "&#x3e;", currentText: "今天", monthNames: ["1月", "2月", "3月", "4月", "5月", "6月", "7月", "8月", "9月", "10月", "11月", "12月"], monthNamesShort: ["一", "二", "三", "四", "五", "六", "七", "八", "九", "十", "十一", "十二"], dayNames: ["星期日", "星期一", "星期二", "星期三", "星期四", "星期五", "星期六"], dayNamesShort: ["周日", "周一", "周二", "周三", "周四", "周五", "周六"], dayNamesMin: ["日", "一", "二", "三", "四", "五", "六"], weekHeader: "周", dateFormat: "yy-mm-dd", firstDay: 1, isRTL: !1, showMonthAfterYear: !0, yearSuffix: "年" }
	    $.datepicker.setDefaults($.datepicker.regional["zh-CN"]);
	    $("#demo").datepicker({data:data,flag:flag});
	    var width = document.body.clientWidth;
		var twidth= width/7+'px';
		var dwidth= width/7+'px';
		$('th').css('width',twidth);
		$('td').css('width',dwidth);
		// alert($('td').width());
	    $('.to').find('img').attr('src','resources/images/diary/down1.png');
	    //开始时折叠(当月--从当天这周、其他-从1号开始)	   
	    if(year==date.getFullYear() && month==(date.getMonth()+1)){
	    	var index = $('.ui-state-highlight').parents('tr').index();
	    	$('tbody tr:gt('+index+')').css('display','none');
	    	$('tbody tr:lt('+index+')').css('display','none');
	    	newSwip = index;
	    }
	    //页面载入时加载数据
	    function getJsonData(url){
	    	var search = $('#search').val();
	    	var start = 0;
	    	var tmp = myFns.getNowDate().replaceAll(',','');
	    	if(vd.length>0){
	    		tmp = vd.replaceAll('-','');
	    	}else{
	    		tmp = myFns.getNowDate().replaceAll(',','')
	    	}
	    	$.ajax({
	    		dataType:'json',
	    		method:'post',
	    		url:url,
	    		data:{'start':start,'content':search,'date':tmp},
	    		success:function(json){
	    			myFns.showView(json,true);    		
	    		}
	    	})
	    }
	    getJsonData('m.php?app=user&func=diary&action=index&task=getAllDiary');

	    //点击日子是显示日记
    $(document).on('touchstart','tbody tr td',function(){

	    	pageNow = 1;
	    	var d = $(this).find('.ui-state-default').text();
	    	if(d<10){
	    		d = '0'+d;
	    	}
	    	var newDate = String(year)+String(month)+d;

	    	var search = $('#search').val();
	    	var start = 0;
	    	var tm = parseInt($(this).attr('data-month'))+1;
	    	var tmm= parseInt(date.getMonth())+1;
	    	if(tm<10 || tmm <10){
	    		tm = '0'+tm;
	    		tmm= '0'+tmm;
	    	}
	    	var td = $(this).find('a').text();
	    	if(parseInt(td)<10){
	    		td = '0'+td;
	    	}
	    	var aa = $(this).attr('data-year')+'-'+tm+'-'+td;
	    	var tdate = $(this).attr('data-year')+tm+td;
	    	sdate = newDate; 
	    	$.ajax({
	    		dataType:'json',	
	    		method:'post',
	    		url:'m.php?app=user&func=diary&action=index&task=getAllDiary',
	    		data:{'/start':start,'content':search,'date':newDate},
	    		success:function(json){
	    			if(json.data.length>0){
	    				myFns.showView(json,true);
	    			}else{
	    				//补充日记
	    				var title = '';
	    				if(search.length>0){
	    					title = '标题为'+search+'的';
	    				}
	    				var d = date.getDate();
	    				if(d<10){
	    					d='0'+d;
	    				}
	    				var str = '';
	    				if(parseInt(date.getFullYear()+tmm+d)<=parseInt(tdate)){
	    					str = '';
	    				}else{
	    					str = '补充';
	    				}

	    				$.confirm({
							title: '提示',
							content: '该日期下没有'+title+'日记,是否要添加'+str+'日记?',
							animation: "top",
							cancelButtonClass: 'btn-danger',
							confirmButton: '确定',
							cancelButton: '取消',
							confirm: function(){
								window.location.href='m.php?app=user&func=diary&action=index&task=loadPageAddDiary&date='+aa;
							}
						});
	    			}    		
	    		}
	    	})
	    });

	    //test滑动
	    touch.on('tbody', 'touchstart', function(ev){
			ev.preventDefault();
		});
		//向右滑动
		touch.on('tbody', 'swipeleft', function(ev){
	    	var trlength = $('tr').length-1;
	    	if(year==date.getFullYear() && month==(date.getMonth()+1)){
	    		var index = $('.ui-state-highlight').parents('tr').index();
	    	}else{
	    		var index = 0;
	    	}
			if((newSwip+1) == trlength){
				month = parseInt(month)+1;
				if(month <10){
					month = '0'+month;
				}
				if(month>12){
					month = '01';
					year = parseInt(year)+1;
				}
				var aa = year+'-'+month+'-'+'01';
				aabb =new Date(aa);
				$('#demo').datepicker('setDate', new Date(aa));	
				$(".ui-datepicker-trigger").remove();
				newSwip = 0;
			}
			$('tbody tr:eq('+(newSwip+1)+')').css('display','');
			$('tbody tr:gt('+(newSwip+1)+')').css('display','none');
			$('tbody tr:lt('+(newSwip+1)+')').css('display','none');	
			if(month != (date.getMonth()+1)){
				$('tbody tr:eq('+(newSwip)+')').css('display','');
				$('tbody tr:gt('+(newSwip)+')').css('display','none');
				$('tbody tr:lt('+(newSwip)+')').css('display','none');
			}
			newSwip+=1;
			flag = '1';
			action='toleft';
		});
		touch.on('tbody', 'swiperight', function(ev){
	    	var trlength = $('tr').length-1;
	    	$('tbody tr:eq('+(newSwip-1)+')').css('display','');
			$('tbody tr:gt('+(newSwip-1)+')').css('display','none');
			$('tbody tr:lt('+(newSwip-1)+')').css('display','none');
			
			if(newSwip==0){	
				month = parseInt(month)-1;
				if(month <10){
					month = '0'+month;
				}
				if(month==0){
					month='12';
					year = parseInt(year)-1;
				}
				var aa = year+'-'+month+'-'+myFns.getDaysNum(year,month);
				aabb = new Date(aa);
				$('#demo').datepicker('setDate', new Date(aa));
				newSwip = $('tbody tr').length;
			}
			$('tbody tr:eq('+(newSwip-1)+')').css('display','');
			$('tbody tr:gt('+(newSwip-1)+')').css('display','none');
			$('tbody tr:lt('+(newSwip-1)+')').css('display','none');
			newSwip = newSwip-1;
			flag = '1';
			action='toright';
		});
		//test 上滑、下滑
		//收缩
		touch.on('#main','swipeup',function(ev){
			$('#wrapper').css('top','228px');
			$('.to').find('img').attr('src','resources/images/diary/down1.png');
			var len = $('tr').length-1;
	    		//当前月或其他月份
	    		if(year==date.getFullYear() && month==(date.getMonth()+1)){
	    			var index = $('.ui-state-highlight').parents('tr').index();
			    	if(flag = '1'){
	    				$('tbody tr:gt('+newSwip+')').css('display','none');
			    		$('tbody tr:lt('+newSwip+')').css('display','none');
	    			}else{
			    		$('tbody tr:gt('+index+')').css('display','none');
			    		$('tbody tr:lt('+index+')').css('display','none');
			    	}
	    		}else{//其他月份
	    			if(flag = '1' && action=='toright'){
	    				$('tbody tr:gt('+newSwip+')').css('display','none');
			    		$('tbody tr:lt('+newSwip+')').css('display','none');
	    			}else if(flag = '1' && action=='toleft'){
	    				$('tbody tr:gt('+(newSwip-1)+')').css('display','none');
			    		$('tbody tr:lt('+(newSwip-1)+')').css('display','none');
	    			}else{
		    			$('tbody tr:gt(0)').css('display','none');
				    	$('tbody tr:lt(0)').css('display','none');
				    }
	    		}
		});
		//展开
		touch.on('#main','swipedown',function(ev){
			
		});
	    //to折叠
	    $(document).on('touchstart','.to',function(){ 	
	    	var height = $('.ui-datepicker-calendar').height();
	    	if(height<100){
	    		$('#wrapper').css('top','419px');
	    		$(this).parents('.ui-datepicker-header').next('table').find('tbody tr').css('display','');
	    		$(this).find('img').attr('src','resources/images/diary/up1.png');
	    	}else{
	    		$('#wrapper').css('top','228px');
	    		$(this).find('img').attr('src','resources/images/diary/down1.png');
	    		var len = $('tr').length-1;
	    		//当前月或其他月份
	    		if(year==date.getFullYear() && month==(date.getMonth()+1)){
	    			var index = $('.ui-state-highlight').parents('tr').index();
			    	if(flag = '1'){
	    				$('tbody tr:gt('+newSwip+')').css('display','none');
			    		$('tbody tr:lt('+newSwip+')').css('display','none');
	    			}else{
			    		$('tbody tr:gt('+index+')').css('display','none');
			    		$('tbody tr:lt('+index+')').css('display','none');
			    	}
	    		}else{//其他月份
	    			if(flag = '1' && action=='toright'){
	    				$('tbody tr:gt('+newSwip+')').css('display','none');
			    		$('tbody tr:lt('+newSwip+')').css('display','none');
	    			}else if(flag = '1' && action=='toleft'){
	    				$('tbody tr:gt('+(newSwip-1)+')').css('display','none');
			    		$('tbody tr:lt('+(newSwip-1)+')').css('display','none');
	    			}else{
		    			$('tbody tr:gt(0)').css('display','none');
				    	$('tbody tr:lt(0)').css('display','none');
				    }
	    		}
	    	}
	    });
	    //点击next
	    $(document).on('touchstart','.ui-datepicker-next',function(){
	    	$('#wrapper').css('top','419px');
	    	month = parseInt(month)+1;
	    	if(month<10){
	    		month = '0'+month;
	    	}
	    	if(month>12){
	    		month = '01';
	    		year = parseInt(year)+1;
	    	}
	    	var aa = year+'-'+month+'-'+'01';
			aabb = new Date(aa);
			$('#demo').datepicker('setDate', new Date(aa));
	    })
	    $(document).on('touchstart','.ui-datepicker-prev',function(){
	    	$('#wrapper').css('top','419px');
	    	month = parseInt(month)-1;
	    	if(month<10){
	    		month = '0'+month;
	    	}
	    	if(month==0){
	    		month = 12;
	    		year = year-1;
	    	}
	    	var aa = year+'-'+month+'-'+myFns.getDaysNum(year,month);
			aabb = new Date(aa);
			$('#demo').datepicker('setDate', new Date(aa));
	    })

	    //搜索
	    $(document).on('search','#search',function(){
			var search = $('#search').val(); 
			var start = 0;
			$.ajax({
	    		dataType:'json',
	    		method:'post',
	    		url:'m.php?app=user&func=diary&action=index&task=getAllDiary',
	    		data:{'start':start,'content':search},
	    		success:function(json){
	    			pageNow=1;
	    			myFns.showView(json,true);    		
	    		}
	    	})
		});

		$(document).on('touchstart','#btnBack',function(){
			if(/android/ig.test(navigator.userAgent)){
				window.javaInterface.returnToMain();
			}else{
				window.location.href = 'js://pop_view_controller';
			}
			return false;
		});
		$(document).on('click','.con',function(){
			var date = encodeURI(encodeURI($(this).find('.time').text()));
			window.location.href="m.php?app=user&func=diary&action=index&task=loadPageViewDiary&date="+date;
		});
	    //调到添加计划
		$(document).on('touchstart','#btnAdd',function(){
			window.location.href="m.php?app=user&func=diary&action=index&task=loadPageAddDiary";
		});

		//折叠
		$(document).on('touchstart','.enter',function(){
			if($(this).hasClass('glyphicon-collapse-down')){
				$(this).removeClass('glyphicon-collapse-down').addClass('glyphicon-collapse-up');
				$(this).parent('.diary').next('.collbody').css('display','block');
				testid = $(this).prev().prev().find('.title').attr('data-mid');
			}else{
				$(this).removeClass('glyphicon-collapse-up').addClass('glyphicon-collapse-down');
				$(this).parent('.diary').next('.collbody').css('display','none');
				// id = $(this).prev().find('.title').attr('data-mid');
				
			}
		});

		//设置完成
		$(document).on('click','.complete',function(){
			var id = $(this).parent().prev().find('.title').attr('data-mid');
			$.ajax({
				dataType:'json',
	    		method:'post',
	    		url:'m.php?app=user&func=diary&action=index&task=planDoneDoing',
	    		data:{'id':id},
	    		success:function(json){
	    			if(json.success){
	    				jSuccess('设置完成成功',{
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
					     	if(vd.length>0){
					    		window.location.href="m.php?app=user&func=diary&action=index&task=loadPage&date="+vd;
					    	}else{
					    		window.location.href="m.php?app=user&func=diary&action=index&task=loadPage";
					    	}
					     	
					     }
						});
	    			}else{
	    				jNotify('设置失败!',{
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
			})
		});
		//删除
		$(document).on('click','.delete',function(){
			var id = $(this).parent().prev().find('.title').attr('data-mid');
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
				method:'post',
				url:'m.php?app=user&func=diary&action=index&task=deletePlan',
				data:{'id':id},
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
					     	window.location.href="m.php?app=user&func=diary&action=index&task=loadPage";
					     }
						});
					}else{
						jNotify('删除失败!',{
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
			})
			});
		});
		//修改
		$(document).on('click','.update',function(){
			var id = $(this).parent().prev().find('.con').find('.title').attr('data-mid');
			window.location.href="m.php?app=user&func=diary&action=index&task=loadPageAddDiary&id="+id;
		})
		//延期
		$(document).on('click','.sure',function(){
			// var id = $(this).parent().prev().find('.con').find('.title').attr('data-mid');
			var delytime = $('#delytime').val();
			var radio = $('input[type="radio"]:checked').val();
			if(radio == 'option1'){
				radio = '1';
			}else{
				radio = '2';
			}
			// console.log(id);
			var delytime = delytime.replaceAll('-0','');
			var aa = String(date.getFullYear())+'-'+String(date.getMonth()+1)+'-'+String(date.getDate());
			if(aa.replaceAll('-','') > delytime.replaceAll('-','')){
				jNotify('延期时间不能小于当前时间!',{
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
			$.ajax({
				dataType:'json',
	    		method:'post',
	    		url:'m.php?app=user&func=diary&action=index&task=delayPlan',
	    		data:{'id':testid,'delaytype':radio,'date':aa,'delaytime':delytime},
	    		success:function(json){
	    			// console.log(json);
	    			if(json.success){
	    				jSuccess('操作成功！',{
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
	    			}else{
	    				jNotify('操作失败!',{
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
		//取消
		$(document).on('click','.cancel',function(){
			$('#myModal').modal('hide');
		});
	});
})
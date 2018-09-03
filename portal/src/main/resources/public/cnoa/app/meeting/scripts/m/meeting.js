var myScroll, pullDownEl, pullDownL, pullUpEl, pullUpL, loadingStep=0;
var pageNow = 1;
var storeType = 'waiting';
myFns = {
	init: function() {
		myFns.loaded();
		$("#wrapper").height($(window).height()-150);
		myFns.getReportList("GET", "json", {}, true, false, true);
	},
	loaded: function() {
		pullDownEl = $('#pullDown');  
		pullDownL = pullDownEl.find('.pullDownLabel');  
		pullDownEl['class'] = pullDownEl.attr('class');  
		pullDownEl.attr('class','').hide();  

		pullUpEl = $('#pullUp');  
		pullUpL = pullUpEl.find('.pullUpLabel');  
		pullUpEl['class'] = pullUpEl.attr('class');  
		pullUpEl.attr('class','').hide();  
		  
		myScroll = new IScroll('#wrapper', {  
			probeType: 2, 
			useTransition: false,
			scrollbars: false,
			scrollX: true, 
			scrollY: true, 
			click: false , 
			preventDefault: false, 
			preventDefaultException: { tagName: /^(INPUT|TEXTAREA|BUTTON|SELECT|A)$/ } 
		});  

		myScroll.on('scroll', function(){  
			if(loadingStep == 0 && !pullDownEl.attr('class').match('flip|loading') && !pullUpEl.attr('class').match('flip|loading')){  
				if (this.y > 50) {  
					pullDownEl.attr('class',pullUpEl['class'])  
					pullDownEl.show();  
					myScroll.refresh();  
					pullDownEl.addClass('flip');  
					pullDownL.html('松手开始刷新...');  
					loadingStep = 1;  
				}else if (this.y < (this.maxScrollY - 50)) {  
					pullUpEl.attr('class',pullUpEl['class'])  
					pullUpEl.show();  
					myScroll.refresh();  
					pullUpEl.addClass('flip');  
					pullUpL.html('松手开始更新...');  
					loadingStep = 1;  
				}  
			}  
		});

		myScroll.on('scrollEnd',function(){  
			if(loadingStep == 1){  
				if (pullUpEl.attr('class').match('flip|loading')) {  
					pullUpEl.removeClass('flip').addClass('loading');  
					pullUpL.html('Loading...');  
					loadingStep = 2;  
					myFns.pullUpAction();  
				}else if(pullDownEl.attr('class').match('flip|loading')){  
					pullDownEl.removeClass('flip').addClass('loading');  
					pullDownL.html('Loading...');  
					loadingStep = 2;  
					myFns.pullDownAction();  
				}  
			}  
		});
		document.addEventListener('touchmove', function (e) { e.preventDefault(); }, false);    
	},
	pullDownAction: function() {
		window.setTimeout(function() {
			pullDownEl.removeClass('loading');  
			pullDownL.html('下拉显示更多...');  
			pullDownEl['class'] = pullDownEl.attr('class');  
			pullDownEl.attr('class','').hide();  
			myScroll.refresh();  
			loadingStep = 0;  
			myFns.reportPullDownAction();
		}, 500)
	},
	pullUpAction: function() {
		window.setTimeout(function() {
			pullUpEl.removeClass('loading');  
	          pullUpL.html('上拉显示更多...');  
	          pullUpEl['class'] = pullUpEl.attr('class');  
	          pullUpEl.attr('class','').hide();  
	          myScroll.refresh();  
	          loadingStep = 0;
	          myFns.reportPullUpAction();
		}, 500)
	},
	reportPullDownAction: function() {
		pageNow = 1;
		var name = $("#iptSearch").val(), objData = {"name": name,'storeType':storeType,'start':0};
		myFns.getReportList("POST", "json", objData, false, true, true);
	},
	reportPullUpAction: function() {
		var limit = 15, start = (pageNow-1) * limit, name = $("#iptSearch").val(), objData = {"name": name, "start": start,'storeType':storeType};
		myFns.getReportList("POST", "json", objData, false, false, true);
	},
	getReportList: function(type, dataType, data, emptyContainer, emptyTbody, mask) {
		$.ajax({
			url: 'm.php?app=meeting&func=mgr&action=join&task=getPassData',
			type: type,
			dataType: dataType,
			data: data,
			beforeSend: function() {
				if (mask) {
					showLoading();
				};
			},
			success: function(json) {
				if (mask) {
					hideLoading();
				};
				var viewWfContainerEl = $("#viewWfContainer");
				if (json.success) {
					if (emptyContainer) {
						viewWfContainerEl.empty();
						var theadHtml  = '<table class="table table-bordered"><thead><tr>';
					 	 	 theadHtml += '<th>No</th><th>操作</th><th>会议名称</th><th>开始时间</th><th>结束时间</th><th>申请人</th><th>申请时间</th></tr></thead><tbody></tbody></table>';
			     		viewWfContainerEl.append(theadHtml);
					}
					
					if (emptyTbody) {
						viewWfContainerEl.children('.table').children('tbody').empty();
					};
			     	var data = json.data, dataLen = data.length, tbodyHtmlArr = [], bTrLen = $("#viewWfContainer > .table > tbody > tr").length;
			     	for (var i = 0; i < dataLen; i++) {
			     		var tbodyHtml   = '<tr><td>'+(bTrLen<1 ? (i+1) : (bTrLen+i+1))+'</td><td>';
			     			 if(storeType == 'waiting'){
				     			tbodyHtml += '<button type="button" id="vReport" class="btn btn-success btn-xs vReport" tid='+data[i].aid+'>同意</button>';
				     			tbodyHtml += '<button type="button" id="nReport" class="btn btn-danger btn-xs nReport" tid='+data[i].aid+'>不同意</button>';
					     	 }else if(storeType == 'pass'){
					     	 	if(data[i].isrecall == '1'){
					     	 		tbodyHtml += '<button type="button" id="recell" class="btn btn-success btn-xs vReport" tid='+data[i].aid+'>撤销</button>';
					     	 	}else{
					     	 		tbodyHtml += '';
					     	 	}
					     	 }else{
					     	 	tbodyHtml += '';
					     	 }
					     	 if (data[i].history == 1) {
					     	 	tbodyHtml += '<button type="button" class="btn btn-success btn-xs vhistoryReport" condition='+data[i].condition+'>历史报表</button>'; 
					     	 };
			     			 tbodyHtml += '</td>';
			     			 tbodyHtml += '<td class="detail" style="color:#428bca" aid="'+data[i].aid+'">'+data[i].name+'</td><td>'+myFns.convertEmptyData(data[i].stime)+'</td>';  
					     	 tbodyHtml += '<td>'+myFns.convertEmptyData(data[i].etime)+'</td><td>'+data[i].appman+'</td><td>'+data[i].apptime+'</td>';
					     	 tbodyHtml += '</tr>';
			     		tbodyHtmlArr.push(tbodyHtml);
			     	};
				     viewWfContainerEl.children('.table').children('tbody').append(tbodyHtmlArr.join(""));
				} else {
					alert(json.msg);
				}
				pageNow += 1;
				var tableWidth = $("#viewWfContainer > .table").width(), viewportWidth = $(document).width();
				if (tableWidth > viewportWidth) {
					$('#scroller').css('width', tableWidth);
				} else {
					$('#scroller').css('width', "100%");
				}
				myScroll.refresh();
			}
		})
	},
	convertEmptyData: function(str) {
		if (str == null || str == undefined) {
			str = '';
		};
		return str;
	},
	isWeixn: function() {  
	    var ua = navigator.userAgent.toLowerCase();  
	    if(ua.match(/MicroMessenger/i)=="micromessenger") {  
	      return true;  
	    } else {  
	      return false;  
	    }  
	}
}

historyReport = {
	init: function() {
		this.touchClose();
		this.hReportLoaded();
		this.initView();
	},
	showWin: function(width, height, title) {
		var innerHtml   = '';
			 innerHtml += '<div id="hReportMask" style="width:'+width+'px;height:'+height+'px;"><div id="header"><span class="glyphicon glyphicon-chevron-left btn-back-ico" aria-hidden="true" id="hReportBackBtn"></span>';
			 innerHtml += '<span>'+title+'</span></div>';
			 innerHtml += '<div id="hReportWraper"><div id="hReportScroller">';
			 innerHtml += '<div class="reportListBox" id="reportListBox">';
			 innerHtml += '<table class="table table-bordered">';
			 innerHtml += '<thead><tr><th>No</th><th>报表开始时间</th><th>报表结束时间</th><th>操作</th></tr></thead>';
			 innerHtml += '<tbody id="reportList"></tbody>';
			 innerHtml += '</table></div></div></div></div>'; 
		$("body").append(innerHtml);
		this.init();
	},
	hReportLoaded:function() {
		hReportScroll = new IScroll('#hReportWraper', {
			scrollbars: false, 
			preventDefault: false, 
			preventDefaultException: { tagName: /^(INPUT|TEXTAREA|BUTTON|SELECT|A)$/ }
		});
		document.addEventListener('touchmove', function (e) { e.preventDefault(); }, false);
	},
	initView: function() {
		$("#hReportWraper").css('height', $(window).height()-52);
	},
	touchClose: function(){
		$("#hReportBackBtn").on('click', function() {
			$("#hReportMask").fadeOut("slow", function() {
				$(this).remove();
			})
        });
	},
	getHistoryReportList: function(cid) {
		$.ajax({
			url: 'm.php?app=report&func=form&action=view&modul=wf&task=getHistoryJsonData&cid='+cid+'',
			type: 'GET',
			dataType: 'json',
			beforeSend: function() {
				showLoading();
			},
			success: function(json) {
				hideLoading();
				var reportListEl = $("#reportList");
				reportListEl.empty();
				if (json.success) {
					var data = json.data, dataLen = data.length, innerHtml = '';
					for (var i = 0; i < dataLen; i++) {
						innerHtml += '<tr><td>'+(i+1)+'</td><td>'+data[i].stime+'</td><td>'+data[i].etime+'</td>';
						innerHtml += '<td><button type="button" class="btn btn-primary btn-xs hvReport" tid="'+data[i].cid+'" hid="'+data[i].hid+'">查看</button></td></tr>';
					};
					reportListEl.html(innerHtml);
					var tableWidth = $("#reportListBox > .table").width(), viewportWidth = $(window).width();
					if (tableWidth > viewportWidth) {
						$('#hReportScroller').css('width', tableWidth);
					} else {
						$('#hReportScroller').css('width', "100%");
					}
					hReportScroll.refresh();
				};
			}
		})
	}
}

$(function(){

	$(document).on('touchstart','#viewWfBlack',function(){
		window.location.href = 'm.php?app=meeting&func=mgr&action=join&task=loadPage&from=meetingManage';
	});
	$("#iptSearch").on('keyup', function() {
		pageNow = 1;
		var start = (pageNow-1) * 15;
		var name = $(this).val(), objData = {"name": name, "start": start ,'storeType':storeType};
		myFns.getReportList("POST", "json", objData, false, true, true);
	})

	$(document).on('touchstart','.detail',function(){
		var aid = $(this).attr('aid');
		window.location.href ='m.php?app=meeting&func=mgr&action=meeting&&task=loadPage&from=view&aid='+aid+'&type=approval'; 
	});
	// 导航菜单
	$(document).on('touchstart','#listNav',function(){
	    $('#jingle_popup').slideToggle('fast');
	    $('#jingle_popup_mask').show();
	    return false;
	});
	$(document).on('touchstart','.btn-cancel',function(){
	    $('#jingle_popup').slideUp(100);
	    $('#jingle_popup2').slideUp(100);
	    $('#jingle_popup_mask').hide();
	    return false;
	});
	//路径导航
	$(document).on('touchstart','#pending,#pass,#npass,#cancel',function(){
		var flag = $(this).attr('id');
		if(flag == 'pending'){
			storeType = 'waiting';
		}else if(flag == 'pass'){
			storeType = 'pass';
		}else if(flag == 'npass'){
			storeType = 'unpass';
		}else{
			storeType = 'cancel';
		}
		var name = $('#iptSearch').val();
		var objData = {'start':0,'storeType':storeType,'name':name};
		myFns.getReportList("POST", "json", objData, false, true, true);
		$('#jingle_popup').slideUp(100);
		pageNow = 1;	
		return false;
	});

	//操作(同意，不同意，撤销)
	$(document).on('click','#vReport,#nReport,#recell',function(){
		var flag = $(this).attr('id');
		var type = '',word = '';
		if(flag == 'vReport'){
			type = 'agree';
			word = '通过';
		}else if(flag == 'nReport'){
			type = 'disagree';
			word = '不通过';
		}else{
			type = 'recell';
			word = '撤销';
		}
		var aid = $(this).attr('tid');
		$.confirm({
			title: '提示',
			content: '您确定要'+word+'该会议吗?',
			animation: "top",
			cancelButtonClass: 'btn-danger',
			confirmButton: '确定',
			cancelButton: '取消',
			confirm: function(){
				$.ajax({
					type:'post',
					dataType:'json',
					url: 'index.php?app=meeting&func=mgr&action=list&task=comfirmAppMeetingroom',
					data: {'aid':aid,'type':type},
					beforeSend: function() {
						showLoading();
					},
					success:function(json){
						hideLoading();
						if(json.success){
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
							     	window.location.reload();
							     }
							});
						}else{
							jError('操作失败',{
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
					}
				})
			}
		});
	});

	myFns.init();
})
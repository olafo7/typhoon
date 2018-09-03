var myScroll, pullDownEl, pullDownL, pullUpEl, pullUpL, loadingStep=0;
var pageNow = 1;
myFns = {
	init: function() {
		myFns.loaded();
		$("#wrapper").height($(window).height()-110);
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
		var name = $("#iptSearch").val(), objData = {"name": name};
		myFns.getReportList("GET", "json", objData, false, true, true);
	},
	reportPullUpAction: function() {
		var limit = 15, start = (pageNow-1) * limit, name = $("#iptSearch").val(), objData = {"name": name, "start": start};
		myFns.getReportList("POST", "json", objData, false, false, true);
	},

	//获取用户报表查看
	getReportList: function(type, dataType, data, emptyContainer, emptyTbody, mask) {
		$.ajax({
			url: 'm.php?app=report&func=user&action=view&task=getReportList',
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
					 	 	 theadHtml += '<th>No</th><th>操作</th><th>报表名称</th></tr></thead><tbody></tbody></table>';
			     		viewWfContainerEl.append(theadHtml);
					}
					//重新定义table宽度
					viewWfContainerEl.children('.table').width('0');
					if (emptyTbody) {
						viewWfContainerEl.children('.table').children('tbody').empty();
					};
			     	var data = json.data, dataLen = data.length, tbodyHtmlArr = [], bTrLen = $("#viewWfContainer > .table > tbody > tr").length;
			     	for (var i = 0; i < dataLen; i++) {
			     		var tbodyHtml   = '<tr><td>'+(bTrLen<1 ? (i+1) 	: (bTrLen+i+1))+'</td><td>';
			     			 tbodyHtml += '<button type="button" class="btn btn-primary btn-xs vReport" tid='+data[i].tid+' id='+data[i].fromid+'>查看</button>';
			     			 tbodyHtml += '</td>';
			     			 tbodyHtml += '<td>'+data[i].name+'</td>';  
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
					$('.table').css('width','100%');
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

// historyReport = {
// 	init: function() {
// 		this.touchClose();
// 		// this.hReportLoaded();
// 		this.initView();
// 	},
	// showWin: function(width, height, title) {
	// 	var innerHtml   = '';
	// 		 innerHtml += '<div id="hReportMask" style="width:'+width+'px;height:'+height+'px;"><div id="header"><span class="glyphicon glyphicon-chevron-left btn-back-ico" aria-hidden="true" id="hReportBackBtn"></span>';
	// 		 innerHtml += '<span>'+title+'</span></div>';
	// 		 innerHtml += '<div id="hReportWraper"><div id="hReportScroller">';
	// 		 innerHtml += '<div class="reportListBox" id="reportListBox">';
	// 		 innerHtml += '<table class="table table-bordered">';
	// 		 innerHtml += '<thead><tr><th>No</th><th>报表开始时间</th><th>报表结束时间</th><th>操作</th></tr></thead>';
	// 		 innerHtml += '<tbody id="reportList"></tbody>';
	// 		 innerHtml += '</table></div></div></div></div>'; 
	// 	$("body").append(innerHtml);
	// 	this.init();
	// },
	// hReportLoaded:function() {
	// 	hReportScroll = new IScroll('#hReportWraper', {
	// 		scrollbars: false, 
	// 		preventDefault: false, 
	// 		preventDefaultException: { tagName: /^(INPUT|TEXTAREA|BUTTON|SELECT|A)$/ }
	// 	});
	// 	document.addEventListener('touchmove', function (e) { e.preventDefault(); }, false);
	// },
	// initView: function() {
	// 	$("#hReportWraper").css('height', $(window).height()-52);
	// },
	// touchClose: function(){
	// 	$("#hReportBackBtn").on('click', function() {
	// 		$("#hReportMask").fadeOut("slow", function() {
	// 			$(this).remove();
	// 		})
 //        });
	// },
	// getHistoryReportList: function(cid) {
	// 	$.ajax({
	// 		url: 'm.php?app=report&func=user&action=view&task=getHistoryJsonData&cid='+cid+'',
	// 		type: 'GET',
	// 		dataType: 'json',
	// 		beforeSend: function() {
	// 			showLoading();
	// 		},
	// 		success: function(json) {
	// 			hideLoading();
	// 			var reportListEl = $("#reportList");
	// 			reportListEl.empty();
	// 			if (json.success) {
	// 				var data = json.data, dataLen = data.length, innerHtml = '';
	// 				for (var i = 0; i < dataLen; i++) {
	// 					innerHtml += '<tr><td>'+(i+1)+'</td><td>'+data[i].stime+'</td><td>'+data[i].etime+'</td>';
	// 					innerHtml += '<td><button type="button" class="btn btn-primary btn-xs hvReport" tid="'+data[i].cid+'" hid="'+data[i].hid+'">查看</button></td></tr>';
	// 				};
	// 				reportListEl.html(innerHtml);
	// 				var tableWidth = $("#reportListBox > .table").width(), viewportWidth = $(window).width();
	// 				if (tableWidth > viewportWidth) {
	// 					$('#hReportScroller').css('width', tableWidth);
	// 				} else {
	// 					$('#hReportScroller').css('width', "100%");
	// 				}
	// 				hReportScroll.refresh();
	// 			};
	// 		}
	// 	})
	// }
// }

jQuery.browser = {
  versions: function() {
    var u = navigator.userAgent, app = navigator.appVersion;
    return {
      trident: u.indexOf('Trident') > -1, 
      presto: u.indexOf('Presto') > -1, 
      webKit: u.indexOf('AppleWebKit') > -1, 
      gecko: u.indexOf('Gecko') > -1 && u.indexOf('KHTML') == -1, 
      mobile: !!u.match(/AppleWebKit.*Mobile.*/) || !!u.match(/AppleWebKit/), 
      ios: !!u.match(/\(i[^;]+;( U;)? CPU.+Mac OS X/), 
      android: u.indexOf('Android') > -1 || u.indexOf('Linux') > -1, 
      iPhone: u.indexOf('iPhone') > -1 || u.indexOf('Mac') > -1, 
      iPad: u.indexOf('iPad') > -1, 
      webApp: u.indexOf('Safari') == -1 
    };
  }(),
  language: (navigator.browserLanguage || navigator.language).toLowerCase()
}

$(function(){

	$("#viewWfBlack").on("touchstart", function() {
//		if (myFns.isWeixn()) {
//			WeixinJSBridge.call('closeWindow');
//		} else {
//			try {
//			  CNOAApp.popPage();
//			} catch (e) {
//			  if(/android/ig.test(navigator.userAgent)){
//					window.javaInterface.returnToMain();
//				}else{
//					window.location.href = 'js://pop_view_controller';
//				} 
//			}
//			return false;
//		}
		window.location.href = 'm.php?app=report&func=form&action=view&modul=wf&task=loadPage';
	})
	//查找
	$("#iptSearch").on('keyup', function() {
		pageNow = 1;
		var start = (pageNow-1) * 15;
		var name = $(this).val(), objData = {"name": name, "start": start};
		myFns.getReportList("POST", "json", objData, false, true, true);
	})
	
	//点击查看
	$(document).on("click", "button.vReport", function(e) {
		var el = $(e.target), hid = $(this).attr('hid'), tid = $(this).attr('tid'), id = $(this).attr('id');
		if (el.is('.vReport')) {
			window.location.href = 'm.php?app=report&func=user&action=view&task=loadPage&model=view&id='+id+'&tid='+tid;
		}
		// else if (el.is('.hvReport')) {
		// 	window.location.href = 'm.php?app=report&func=form&action=view&modul=wf&task=loadPage&from=detail&tid='+tid+'&hid='+hid+'';
		// }
	})

	// $(document).on("click", "button.vhistoryReport", function() {
	// 	var condition = $(this).attr("condition"), width = $(window).width(), height = $(window).height(), cid = $(this).attr("condition");
	// 	historyReport.showWin(width, height, "历史报表列表");
	// 	historyReport.getHistoryReportList(cid);
	// })


	myFns.init();
})
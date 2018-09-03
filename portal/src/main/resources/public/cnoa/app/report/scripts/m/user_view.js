var myScroll, pullDownEl, pullDownL, pullUpEl, pullUpL, loadingStep=0;
var pageNow = 1;
myFns = {
  getUriString: function(key) {
    var reg = new RegExp("(^|&)"+ key +"=([^&]*)(&|$)");
    var r = window.location.search.substr(1).match(reg);
    if(r!=null)return  unescape(r[2]); return null;
  },
  client: function() {
    if (/android/ig.test(navigator.userAgent)) {
      var ret = "android";
    } else {
      var ret = "ios";
    }
    return ret;
  },
  isWeixn: function() {  
    var ua = navigator.userAgent.toLowerCase();  
    if(ua.match(/MicroMessenger/i)=="micromessenger") {
      return true;
    } else {
      return false;
    }
  },
  convertEmptyData: function(str) {
    if (str == null || str == undefined || str == '') {
      str = '';
    };
    return str;
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
      myFns.wfDetailPullDownAction();
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
            myFns.wfDetailPullUpAction();
    }, 500)
  },
  wfDetailPullDownAction: function() {
    pageNow = 1;
    var fieldsName = $("#fieldsName").val(), keyword = $("#iptSearch").val(), condition = "contain", 
          params = {"fieldsName": fieldsName, "keyword": keyword, "condition": condition, "search": "now"};
    this.getTempleDataList(true, params);
  },
  wfDetailPullUpAction: function() {
    var fieldsName = $("#fieldsName").val(), keyword = $("#iptSearch").val(), condition = "contain", 
          params = {"fieldsName": fieldsName, "keyword": keyword, "condition": condition, "search": "now"};
    this.getTempleDataList(false, params);
  },
  //获取内容列表
  getTempleDataList: function(isEmpty, params) {
    var isEmpty = isEmpty ? true : false;
    var limit = 30, start = (pageNow-1) * limit;
    var tid = myFns.getUriString("tid"), id = myFns.getUriString("id") != null ? myFns.getUriString("id") : 0;
    if (!params) {
      var data = {"start": start, "limit": limit, "tid":tid, "id":id};
    } else {
      var data = {"start": start, "limit": limit, "kong1": params.fieldsName, "value1": params.keyword, "condition1": params.condition, "search": params.search, "tid":tid, "id":id};
    }
    $.ajax({
      url: 'm.php?app=report&func=user&action=view&task=getTempleDataList',
      type: 'POST',
      dataType: 'json',
      data: data,
      beforeSend: function() {
        showLoading();
      },
      success: function(json) {
        hideLoading();
        if (json.success) {
          if (isEmpty) {
            $("#wfDetailTbody").empty();
          };
          var data = json.data, dataLen = data.length, tdHtml = '', bTrLen = $("#wfDetailTbody").children('tr').length, 
          ths = $("#wfDetailThead > tr > th"), thLen = ths.length, thClassNames = [];
          for (var t = 0; t < thLen; t++) {
            thClassNames.push(ths.eq(t).attr('class'));
          };
          var thClassNamesLen = thClassNames.length;
          for (var i = 0; i < dataLen; i++) {
            tdHtml += '<tr uid="'+data[i].uid2+'">';
            for (var j = 0; j < thClassNamesLen; j++) {
              var thClassName = thClassNames[j], isExist = false;
              $.each(data[i], function(key) {
                  if (key == "operateA" || key == "operateD" || key == "operateV") {
                    return true;
                  };
                  if (thClassName == key) {
                    tdHtml += '<td class="'+key+'">'+myFns.convertEmptyData(data[i][key])+'</td>';
                    isExist = true;
                    return false;
                  }
              });
              if ( ! isExist) {
                if (thClassName == "No") {
                  tdHtml += '<td class="'+thClassName+'">'+(bTrLen<1 ? (i+1) : (bTrLen+i+1))+'</td>';
                } else if (thClassName == "operate") {
                    if(data[i].operateV || data[i].operateD){
                      if (data[i].operateV) {
                        tdHtml += '<td class="'+thClassName+'"><button type="button" class="btn btn-primary btn-xs vWfReport">查看</button>';
                      }
                      if (data[i].operateD) {
                        tdHtml += '<button type="button" class="btn btn-success btn-xs vReportFileList">附件</button></td>';
                      };
                    }else{
                      tdHtml += '<td class="'+thClassName+'"></td>';
                    }
                } else {
                  tdHtml += '<td class="'+thClassName+'"></td>';
                }
              };
            };
            tdHtml += '</tr>';
          };  
          pageNow += 1;
          $("#wfDetailTbody").append(tdHtml);
          var viewportWidth = $(window).width();
          var width = 0;
          $('#wfDetailThead tr th').each(function(){
        	  width += $(this).width()+18;
          })
          width = width+2;
          if (width > viewportWidth) {
            $('#scroller').css('width', width);
          } else {
            $('#scroller').css('width', "100%");
          };
          myScroll.refresh();
        } else {
          alert(json.msg);
        }
      }
    }) 
  }
}
//上传文件
//jQuery.reportFiles = {
//  param: {},
//  init: function() {
//    this.touchClose();
//    this.hReportLoaded();
//    this.initView();
//    this.getAttachList();
//  },
//  showWin: function(width, height, title, paramObj) {
//    var innerHtml   = '';
//    innerHtml += '<div id="reportFileMask" style="width:'+width+'px;height:'+height+'px;"><div id="header"><span class="glyphicon glyphicon-chevron-left btn-back-ico" aria-hidden="true" id="reportFileBack"></span>';
//    innerHtml += '<span>'+title+'</span></div>';
//    innerHtml += '<div id="reportFileWraper"><div id="reportFileScroller">';
//    innerHtml += '<div class="fileListBox">';
//    innerHtml += '<table class="table table-bordered">';
//    innerHtml += '<thead><tr><th>No</th><th>附件名称</th><th>上传者</th><th>上传日期</th><th>操作</th></tr></thead>';
//    innerHtml += '<tbody id="fileList"></tbody>';
//    innerHtml += '</table></div></div></div></div>'; 
//    $("body").append(innerHtml);
//    this.param = paramObj;
//    this.init();
//  },
//  hReportLoaded:function() {
//    reportFileScroll = new IScroll('#reportFileWraper', {
//    scrollbars: false, 
//    preventDefault: false, 
//    scrollX: true, 
//    scrollY: true, 
//    preventDefaultException: { tagName: /^(INPUT|TEXTAREA|BUTTON|SELECT|A)$/ }
//    });
//    document.addEventListener('touchmove', function (e) { e.preventDefault(); }, false);
//  },
//  initView: function() {
//    $("#reportFileWraper").css('height', $(window).height()-52);
//  },
//  touchClose: function() {
//    $("#reportFileBack").on('click', function() {
//      $("#reportFileMask").fadeOut("slow", function() {
//        $(this).remove();
//      })
//    });
//  }
//  ,
//	//附件
//  getAttachList: function() {
//    $.ajax({
//      url: 'm.php?app=report&func=user&action=view&modul=wf&tid='+this.param.tid+'&hid='+this.param.hid+'&task=getAttachList&uFlowId='+this.param.uFlowId+'&type='+this.param.type+'',
//      type: 'GET',
//      dataType: 'json',
//      beforeSend: function() {
//        showLoading();
//      },  
//      success: function(json) {
//        hideLoading();
//        if (json.success) {
//          var data = json.data, dataLen = data.length, innerHtml = '', picExts = ['jpg', 'jpeg', 'png', 'gif', 'bmp'];
//          for (var i = 0; i < dataLen; i++) {
//              innerHtml += '<tr fileName='+data[i].filename+' fileExt='+data[i].ext+'><td>'+(i+1)+'</td>';
//              innerHtml += '<td>'+data[i].filename+'</td>';
//              innerHtml += '<td>'+data[i].uname+'</td>';
//              innerHtml += '<td>'+data[i].date+'</td>';
//              innerHtml += '<td class="operate">';
//              if ( ! $.browser.versions.android) {
//                innerHtml += '<button type="button" class="btn btn-primary btn-xs '+(~$.inArray(data[i].ext, picExts) ? 'btnBrowse' : 'btnFileView')+'" data-src="'+data[i].filepath+'">查看</button>';
//              } else {
//                if (~$.inArray(data[i].ext, picExts)) {
//                  innerHtml += '<button type="button" class="btn btn-primary btn-xs btnBrowse" data-src="'+data[i].filepath+'">查看</button>';
//                };
//                innerHtml += '<button type="button" class="btn btn-success btn-xs btnDownload" data-src="'+data[i].filepath+'">下载</button>';
//              }
//              innerHtml += '</td></tr>';
//          };
//          $("#fileList").html(innerHtml);
//          ImagesZoom.init({
//            "elem": ".btnBrowse"
//          });
//
//          var tableWidth = $("#reportFileScroller > .fileListBox > .table").width(), viewportWidth = $(window).width();
//          if (tableWidth > viewportWidth) {
//            $('#reportFileScroller').css('width', tableWidth);
//          } else {
//            $('#reportFileScroller').css('width', "100%");
//          };
//          reportFileScroll.refresh();
//        };
//      }
//    })
//  }
//}

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

$(function() {
	//退回
  $("#wfDetailBlack").on("touchstart", function() {
    window.location.href = "m.php?app=report&func=user&action=view&task=loadPage";
    return false;
  })
  // 获取列表头
  function loadColumn(callbackFunc) {
    var tid = myFns.getUriString("tid"), id = myFns.getUriString("id") != null ? myFns.getUriString("id") : 0;
    $.ajax({
      url: 'm.php?app=report&func=user&action=view&task=loadColumn',
      type: 'POST',
      data: {'tid':tid,'id':id},
      dataType: 'json',
      beforeSend: function() {
        showLoading();
      }, 
      success: function(json) {
        hideLoading();
        if (json.success) {
          var data = json.data.data, dataLen = data.length, fields = data.fields, group = data.group, search = data.search, wfDetailTheadHtml = '<tr><th class="No">No</th>';
          if (data[dataLen-1].dataIndex == "operate") data.unshift(data.pop());
          for (var i = 0; i < dataLen; i++) {
            if (data[i].hidden) continue;
            wfDetailTheadHtml += '<th class="'+data[i].dataIndex+'">'+data[i].header+'</th>';
          };
          wfDetailTheadHtml += '</tr>';
          $("#wfDetailThead").html(wfDetailTheadHtml);
          var tableWidth = $("#wfDetailContainer > .table").width(), viewportWidth = $(window).width();
          if (tableWidth > viewportWidth) {
            $('#scroller').css('width', tableWidth);
          } else {
            $('#scroller').css('width', "100%");
          };
          myScroll.refresh();
        } else {
          alert(json.msg);
        }
        callbackFunc(true);
      }
    })
  }

//  function getWordList() {
//    $.ajax({
//      url: 'm.php?app=report&func=form&action=view&modul=wf&tid=43&hid=0&task=getWordList&from=normal',
//      type: 'GET',
//      dataType: 'json',
//      beforeSend: function() {
//        showLoading();
//      },
//      success: function(json) {
//        hideLoading();
//        if (json.success) {
//          var data = json.data, dataLen = data.length, innerHtml = '';
//          for (var i = 0; i < dataLen; i++) {
//            if (data[i].fieldid == "operate") continue;
//            innerHtml += '<option value="'+data[i].fieldid+'">'+data[i].title+'</option>';
//          };
//          $("#fieldsName").append(innerHtml);
//        };
//      }
//    })
//  }

  $(document).on("click", "button.vWfReport", function() {
      var uFlowId = $(this).closest('tr').attr('uFlowId'), tid = myFns.getUriString('tid'),
          hid = myFns.getUriString("hid") != null ? myFns.getUriString("hid") : 0;
          $.ajax({
            url: 'm.php?app=report&func=form&action=view&modul=wf&tid='+tid+'&hid='+hid+'&task=loadPage&from=viewflow&uFlowId='+uFlowId+'',
            type: 'GET',
            dataType: 'json',
            beforeSend: function() {
              showLoading();
            },
            success: function(json) {
              hideLoading();
              if (json.success) {
                var url   = 'm.php?app=wf&func=flow&action=use&modul=todo&task=loadPage&type=show&from=showflow&version=mm', data = json.data;
                      url += '&callback='+data['allowCallback']+'&cancel='+data['allowCancel']+'&fenfa='+data['allowFenfa']+'&tabs=2&flowType='+data['flowType']+'';
                      url += '&tplSort='+data['tplSort']+'&uFlowId='+data['uFlowId']+'&flowId='+data['flowId']+'&step='+data['step']+'';
                try {
                  CNOAApp.pushOAPage(url, true);
                } catch(e) {
                  window.location.href = url;
                }
              } else {
                alert(json.msg);
              }
            }
          })
          
  })

//  $(document).on("click", "button.vReportFileList", function() {
//      var uFlowId = $(this).closest('tr').attr('uFlowId'), tid = myFns.getUriString('tid'),
//          hid = myFns.getUriString("hid") != null ? myFns.getUriString("hid") : 0;
//      var width = $(document).width(), height = $(document).height();
//      var paramObj = {"uFlowId": uFlowId, "hid": hid, "tid": tid, "type": "d"};
//          $.reportFiles.showWin(width, height, "附件列表", paramObj);
//  })

//  $(document).on("click", "button.btnFileView", function() {
//    var filepath = $(this).attr("data-src");
//    if (myFns.isWeixn()) {
//      var width = $(window).width(), height = $(window).height();
//      iospopWin.showWin(width, height, "附件浏览", filepath);
//    } else {
//      var host = window.location.host, absolutePath = host+'/'+filepath, fileName = $(this).closest('tr').attr('fileName'),
//            fileExt = $(this).closest('tr').attr('fileExt');
//      try {
//        CNOAApp.viewDocument(absolutePath, fileName, fileExt);
//      } catch(e) {
//        window.location.href = 'js://push_web_view_controller__'+absolutePath+'__'+fileName;
//      }
//    }
//  })

//  $(document).on("click", "button.btnDownload", function() {
//    var filePath = $(this).attr("data-src"), fileExt = $(this).attr("fileExt");
//    window.location.href = filePath;
//  })

  $(document).on("click", "#btnSearch", function() {
    pageNow = 1;
    var btnEl = $(this), fieldsName = $("#fieldsName").val(), keyword = $("#iptSearch").val(), condition = "contain", 
          params = {"fieldsName": fieldsName, "keyword": keyword, "condition": condition, "search": "now"};
    myFns.getTempleDataList(true, params);
  })

  function init() {
    loadColumn(myFns.getTempleDataList);
    myFns.loaded();
//    getWordList();
  }

  init();

})
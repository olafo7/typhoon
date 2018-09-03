//IOS使用定位
var latitudeIOS,longitudeIOS;

//全局变量`定义
var myFns = {
	/**
	 *加载等待
	 *@param <string> text 文字
	 */
	showLoading: function(text){
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
	//获取url参数值
	getUriString: function(key){
		var reg = new RegExp("(^|&)"+ key +"=([^&]*)(&|$)");
	    var r = window.location.search.substr(1).match(reg);
	    if(r!=null)return  unescape(r[2]); return null;
	},
	/**
	 *登记
	 *@param <Object> obj 当前当卡班次对象
	 *@param <double> longitude 经度
	 *@param <double> latitude 纬度 
	 *@param <string> address 地址 
   	 *@param <int> distance 距离
	 **/
	doRecord:function(obj,longitude,latitude,address,distance){
		$.ajax({
			type: 'post',
			dataType: 'json',
			data: {'num':obj.num, 'classes':obj.classs, 'workType':obj.workType,
				'time':obj.times, 'stime':obj.stime, 'etime':obj.etime, 'longitude':longitude,
				'latitude':latitude, 'address':address, 'distance':distance
			},
			url: 'm.php?app=att&func=person&action=register&task=addChecktime',
			beforeSend:function(){
				//请求数据前执行
				myFns.showLoading('正在签到,请稍等...');
			},
			success: function(json){
				if(json.success){
					$(".ui-ios-overlay").removeClass("ios-overlay-show").addClass("ios-overlay-hide");
					iosOverlay({
						text: json.msg,
						duration: 2e3,
						icon: "../../../../../../resources/images/m/artDialog/check.png"
					})
				}else if(json.failure){
					$(".ui-ios-overlay").removeClass("ios-overlay-show").addClass("ios-overlay-hide");
					iosOverlay({
						text: json.msg,
						duration: 2e3,
						icon: "../../../../../../resources/images/m/artDialog/cross.png"
					})
				}
				setTimeout(function(){
					window.history.back();
					return false;
				},2000)
			}
		})
	},
  //判断当前是否是微信浏览器
  isWeiXin: function(){
    var ua = window.navigator.userAgent.toLowerCase();
    if(ua.match(/MicroMessenger/i) == 'micromessenger'){
      return true;
    }else{
      return false;
    }
  }
}


var _this = this;

$(function(){
if(/(iPhone|iPad|iPod|iOS)/i.test(navigator.userAgent)){
	//js、ios交互()
	function setupWebViewJavascriptBridge(callback) {
		
		if (window.WebViewJavascriptBridge) { return callback(WebViewJavascriptBridge); }
		if (window.WVJBCallbacks) { return window.WVJBCallbacks.push(callback); }
		window.WVJBCallbacks = [callback];
		var WVJBIframe = document.createElement('iframe');
		WVJBIframe.style.display = 'none';
		WVJBIframe.src = 'wvjbscheme://__BRIDGE_LOADED__';
		document.documentElement.appendChild(WVJBIframe);
		setTimeout(function() { document.documentElement.removeChild(WVJBIframe) }, 0)
	}
	
}
	//底部添加计划
	$(document).on('touchstart','#btn_actionsheet',function(){
	$('#jingle_popup').slideDown(100);
	$('#jingle_popup_mask').show();
	return false;
	});

	$(document).on('touchstart','#btn-cancel',function(){
	$('#jingle_popup').slideUp(100);
	$('#jingle_popup_mask').hide();
	return false;
	});

	//关闭弹出窗口 
	$(document).on('touchstart','#jingle_popup_mask',function(event){
		if($(event.target).is(this)) { 
	    $(this).hide();
	    $('#jingle_popup').slideUp(100);
    }
    return false;
	})

	//路径导航
	$(document).on("touchstart","#btnBack",function(){
		window.history.back();
		return false;
	})

	var allmapHeight = $(document).height()-104;
	$('#allmap').css('height',allmapHeight);

	//接收参数
	var obj = {};
	obj.num = myFns.getUriString('num'); 
	obj.classs = myFns.getUriString('classs');
	obj.workType = myFns.getUriString('worktype');
	obj.times = myFns.getUriString('time');
	obj.stime = myFns.getUriString('stime');
	obj.etime = myFns.getUriString('etime');

  //前台全局变量
  var map; //地图实例
  var targets = []; //多个打卡坐标点
  var targetMarkers = []; //多个创建标注
  var targetCircles = []; //多个圆形范围区域 
  var targetTotal = 0; //打卡地点数量
  var radius = []; //后台设定用户里打卡地点的距离
  var distance; //定位后用户距离打卡地点距离
  var startLon; //经度
  var startLat; //纬度
  var stratAddress = ''; //用户地址

  //请求后台获取该用户的打卡地点
  $.getJSON('m.php?app=att&func=person&action=register&task=getAddress',function(json){
    if(json.success){
      // 百度地图API功能
      map = new BMap.Map("allmap"); //创建地图实例  
      map.addControl(new BMap.NavigationControl()); //缩略地图控件
      map.addControl(new BMap.ScaleControl()); //地图比例控件
      $('#allmap').append('<div id="allmap"><button type="button" class="btn refresh" id="refresh"><span class="glyphicon glyphicon-refresh" aria-hidden="true"></span></button></div>');
      var data = json.data; //后台返回的打卡地点
      targetTotal = json.total; //打卡地点数量
      for(var i=0; i<data.length; i++){
        radius.push(data[i]['radius']); //打卡区域范围
        targets[i] = new BMap.Point(data[i]['longitude'], data[i]['latitude']); //创建点坐标  经度,纬度
        targetMarkers[i] = new BMap.Marker(targets[i]); //创建标注 
        map.addOverlay(targetMarkers[i]); //标注表示地图上的点，可自定义标注的图标。
        targetMarkers[i].setAnimation(BMAP_ANIMATION_BOUNCE); //跳动的动画
        targetCircles[i] = new BMap.Circle(targets[i], data[i]['radius'], {fillColor:"#000", strokeWeight: 1 ,fillOpacity: 0.1, strokeOpacity: 0.1}); //圆形范围
        map.addOverlay(targetCircles[i]); 
        var content = '地址：' + data[i]['address'];
        addClickHandler(content, targetMarkers[i]); //打卡地点信息窗口
        if(i == data.length-1){ //设定最后一个标注的中心点坐标和地图级别
          map.centerAndZoom(targets[i], 18); //初始化地图，设置中心点坐标和地图级别
        }
      }
      if(myFns.isWeiXin()){ //微信中使用考勤功能
        $.getJSON('m.php?app=att&func=person&action=register&task=getSignPackage',function(json){
          if(json.success){
            var configData = json.data;
            setJsdkConfig(configData);
          }else{
            iosOverlay({
              text: json.msg,
              duration: 2e3,
              icon: "../../../../../../resources/images/m/artDialog/cross.png"
            })
          }
        })
      }else{ //非微信则使用HTML5进行定位
        //判断当前客户端Android和ios
        if(/android/ig.test(navigator.userAgent)){ //安卓
        	initdata();
        }else if(/(iPhone|iPad|iPod|iOS)/i.test(navigator.userAgent)){
        	//获取经纬度
			setupWebViewJavascriptBridge(function(bridge) {
				//js调用ios方法获取经纬度(ios定位)
				bridge.callHandler('testObjcCallback', {'foo': 'bar'}, function(response) {
					//获取IOS经纬度
					latitudeIOS = response.latitude;
					longitudeIOS = response.longitude;
					// 获取经纬度
					if(!latitudeIOS || !longitudeIOS){
						iosOverlay({
							text: '定位失败,位置信息不可用.',
							duration: 2e3,
							icon: "../../../../../../resources/images/m/artDialog/cross.png"
						})
						return false;
					}
					var data = {
						status: 0,
						result: [{
							x: longitudeIOS,
							y: latitudeIOS
						}]
					};
					setTimeout(function(){
						getMap(data);
					},600);
					return false;
				
				})
			})

			setTimeout(oldVersionFromIOS,2000);
			
        }else{
        	initdata(); //GPS定位
        }
      }
    }else if(json.failure){
      iosOverlay({
        text: json.msg,
        duration: 2e3,
        icon: "../../../../../../resources/images/m/artDialog/cross.png"
      })
      setTimeout(function(){
        window.history.back(); 
      },2000)
    }
  })
  /**
   * IOS 旧版本提示升级
   */
  function oldVersionFromIOS(){
	if(!latitudeIOS || !longitudeIOS){
		iosOverlay({
			text: '请升级手机最新版本',
			duration: 2e3,
			icon: "../../../../../../resources/images/m/artDialog/cross.png"
		})
		setTimeout(function(){
			window.history.back(); 
		},2000)
		return false;
	}
  }
  /**
   *微信JS-SDK配置及函数处理
   */
  function setJsdkConfig(configData){
    //微信JS-SDK权限验证配置
    wx.config({
      debug: false,
      appId: configData['appId'],
      timestamp: configData['timestamp'],
      nonceStr: configData['nonceStr'],
      signature: configData['signature'],
      jsApiList: [
        'openLocation', //使用微信内置地图查看位置接口
        'getLocation', //获取地理位置接口
        'getNetworkType' //获取网络状态接口
      ]
    });

    //通过config接口注入权限验证配置
    wx.ready(function(){
      wx.getLocation({
        type: 'wgs84', // 默认为wgs84的gps坐标，如果要返回直接给openLocation用的火星坐标，可传入'gcj02'
        success: function (res) {
          var sLat = res.latitude; // 纬度，浮点数，范围为90 ~ -90
          var sLon = res.longitude ; // 经度，浮点数，范围为180 ~ -180。
          var speed = res.speed; // 速度，以米/每秒计
          var accuracy = res.accuracy; // 位置精度
          //微信GPS地理坐标转换百度地图坐标
          convertPosition(sLat, sLon);
        },
        cancel: function (res) {
          iosOverlay({
            text: '用户拒绝授权获取地理位置',
            duration: 2e3,
            icon: "../../../../../../resources/images/m/artDialog/cross.png"
          })
          return false;
        }
      });
    });
  }
  
  var opts = {
    title : "信息窗口", // 信息窗口标题
    enableMessage:true //设置允许信息窗发送短息
  };
  
  //信息窗口标示
  function addClickHandler(content,marker){
    marker.addEventListener("click", function(e){
      openInfo(content,e)}
    );
  }

  //点击之后触发信息窗口
  function openInfo(content,e){
    var p = e.target;
    var point = new BMap.Point(p.getPosition().lng, p.getPosition().lat);
    var infoWindow = new BMap.InfoWindow(content,opts); // 创建信息窗口对象 
    map.openInfoWindow(infoWindow,point); //开启信息窗口
  }

  function initdata(){ //页面初始化
    if (navigator.geolocation){
      navigator.geolocation.getCurrentPosition(showPosition,showError);//HTML5获取GPS设备地理位置信息
    }else{
      iosOverlay({
        text: '您的浏览器不支持定位',
        duration: 2e3,
        icon: "../../../../../../resources/images/m/artDialog/cross.png"
      })
      return false;
    }
  }

  function showPosition(position){
    var x = position.coords.latitude;//获取纬度
    var y = position.coords.longitude;//获取经度
    
    //GPS坐标转百度地图坐标
    convertPosition(x, y);
  }

  /**
   *转为百度地图坐标
   *注意点：1、coords的经度、纬度顺序（可多组坐标转换，以；（分号）隔开）。2、from与to的准确性。3、callback为回调函数
   *@param <double> latitude 纬度
   *@param <double> longitude 经度
   */
  function convertPosition(latitude, longitude){
    var positionUrl = "http://api.map.baidu.com/geoconv/v1/?coords="+longitude+","+latitude+"&from=1&to=5&ak=1iBBkQeaR4wdkKsIjhpX1nQExhwBKwMz&output=json";
    $.ajax({ 
      type: "GET", 
      dataType: "jsonp", 
      url: positionUrl,
      success: function (json) { 
        if(json.status==0){
          getMap(json);
        }
      },
      error: function (XMLHttpRequest, textStatus, errorThrown) { 
        iosOverlay({
          text: '地图坐标转换出错',
          duration: 2e3,
          icon: "../../../../../../resources/images/m/artDialog/cross.png"
        })
        return false;
      }
    });
  }

  //HTML5获取地理位置信息错误处理
  function showError(error){
    switch(error.code){
      case error.PERMISSION_DENIED:
        iosOverlay({
          text: '定位失败,用户拒绝请求地理定位.',
          duration: 2e3,
          icon: "../../../../../../resources/images/m/artDialog/cross.png"
        })
        break;
      case error.POSITION_UNAVAILABLE:
        iosOverlay({
          text: '定位失败,位置信息不可用.',
          duration: 2e3,
          icon: "../../../../../../resources/images/m/artDialog/cross.png"
        })
        break;
      case error.TIMEOUT:
        iosOverlay({
          text: '定位失败,请求获取用户位置超时.',
          duration: 2e3,
          icon: "../../../../../../resources/images/m/artDialog/cross.png"
        })
        break;
      case error.UNKNOWN_ERROR:
        iosOverlay({
          text: '定位失败,定位系统失效.',
          duration: 2e3,
          icon: "../../../../../../resources/images/m/artDialog/cross.png"
        })
        break;
    }
  }
  
  //安卓调用方法获取经纬度函数
  _this.getMapFromAndroid = function(x, y){
//    var data = {
//      status: 0,
//      result: [{
//        x: x,
//        y: y
//      }]
//    };
//    getMap(data);
	iosOverlay({
		text: '请升级手机最新版本',
		duration: 2e3,
		icon: "../../../../../../resources/images/m/artDialog/cross.png"
	})
	setTimeout(function(){
		window.history.back(); 
	},2000)
	
  }

  

  /**
   *HTML5 GPS定位坐标转换为百度地图坐标
   *@param <object> data GPS坐标转换为百度坐标的对象
   */
  function getMap(data){
    if(!myFns.isWeiXin()){ //OA客户端中使用
      //返回的状态码，0为正常；1为内部错误；21为from非法；22为to非法；24为coords格式非法；25为coords个数非法，超过限制 
      if(data.status != 0){
        iosOverlay({
          text: '地图坐标转换出错',
          duration: 2e3,
          icon: "../../../../../../resources/images/m/artDialog/cross.png"
        })
        return false;
      }
      //result为数组
      var result = data.result;
      startLon = result[0].x; //经度
      startLat = result[0].y; //纬度
    }else{ //微信中使用定位打卡
      //返回的状态码，0为正常
      if(data.status != 0){
        iosOverlay({
          text: '地图坐标转换出错',
          duration: 2e3,
          icon: "../../../../../../resources/images/m/artDialog/cross.png"
        })
        return false;
      }
      //result为数组
      var result = data.result;
      startLon = result[0].x; //经度
      startLat = result[0].y; //纬度
      
    }
    var startPoint = new BMap.Point(startLon, startLat); // 创建点坐标  经度,纬度
    map.centerAndZoom(startPoint, 18); // 初始化地图，设置中心点坐标和地图级别

    //覆盖物
    var myIcon = new BMap.Icon("../../../../../../resources/images/m/ico/myPostionMarker.png", new BMap.Size(14,32));
    var startMarker = new BMap.Marker(startPoint, {icon:myIcon}); //创建标注    
    map.addOverlay(startMarker); //标注表示地图上的点，可自定义标注的图标。

    //逆地址转换
    var positionUrl = "http://api.map.baidu.com/geocoder/v2/?ak=1iBBkQeaR4wdkKsIjhpX1nQExhwBKwMz&callback=getAddress&location="+startLat+","+startLon+"&output=json&pois=0";
    $.ajax({ 
      type: "GET", 
      dataType: "jsonp", 
      url: positionUrl,
      success: function (json) { 
        if(json.status == 0){
          getAddress(json);
          addClickHandler(stratAddress, startMarker); //用户位置信息窗口
        }
      },
      error: function (XMLHttpRequest, textStatus, errorThrown) { 
        iosOverlay({
          text: '地址转换出错',
          duration: 2e3,
          icon: "../../../../../../resources/images/m/artDialog/cross.png"
        })
        return false;
      }
    });

    //获取两两坐标之间距离
    var res = false; //是否满足打卡 标记
    for(var i=0; i<targetTotal; i++){
      var pointRadius = Math.floor(map.getDistance(startPoint,targets[i]));
      if(pointRadius <= radius[i]) { //满足打卡
        distance = pointRadius;
        iosOverlay({
          text: '已经进入打卡区域',
          duration: 2e3,
          icon: "../../../../../../resources/images/m/artDialog/check.png"
        })
        $('#btnRegister').css('display', 'block'); 
        return false; //满足一个地点打卡即跳出函数体
      }
      res = true; //不满足打卡标记
    }
    
    if(res){
      iosOverlay({
        text: '您不在打卡范围内',
        duration: 2e3,
        icon: "../../../../../../resources/images/m/artDialog/cross.png"
      })
      return false;
    }
  }

  //定位所在地址
  function getAddress(data){
    //注意点：0 正常;1 服务器内部错误;2 请求参数非法;3 权限校验失败;4 配额校验失败;5   ak不存在或者非法;101 服务禁用;102 不通过白名单或者安全码不对;2xx 无权限;3xx 配额错误
    if(data.status != 0){
      iosOverlay({
        text: '地址转换出错',
        duration: 2e3,
        icon: "../../../../../../resources/images/m/artDialog/cross.png"
      })
      return false;
    }
    //经纬度转换地址成功
    stratAddress = data.result['formatted_address'];
  }
  
  //上下班签到
  $(document).on('click','#btnRegister',function(){
  	myFns.doRecord(obj, startLon, startLat, stratAddress, distance);
  })

  //刷新
  $(document).on('click','#refresh',function(){
    window.location.reload();
  })

})

var myFns = {
	//判断当前是否是微信浏览器
	isWeiXin: function(){
		var ua = window.navigator.userAgent.toLowerCase();
		if(ua.match(/MicroMessenger/i) == 'micromessenger'){
		  	return true;
		}else{
			alert('请先安装微信');
		  	return false;
		}
	}
}

$(function(){
	// 微信扫一扫
	$(document).on('touchstart','.scan',function(){
		if(myFns.isWeiXin()){ //微信中扫一扫
			$.getJSON('m.php?app=att&func=person&action=register&task=getSignPackage',function(json){
			  if(json.success){
			    var configData = json.data;
			    setJsdkConfig(configData);
			  }
			})
		}
	})

	//跳转添加页面
	$(document).on('touchstart','.addMobile',function(){
		window.location.href = 'm.php?app=user&func=addressbook&action=default&task=loadPage&from=addUser';
	})

	// 返回到我的通讯录
	$(document).on('touchstart','#btnBack',function(){
		window.location.href = 'm.php?app=user&func=addressbook&action=default&task=loadPage&from=my';
	})

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
	        'scanQRCode'//使用微信扫一扫接口
	      ]
	    });

	    //通过config接口注入权限验证配置
	    wx.ready(function(){
			wx.scanQRCode({
			    desc: 'scanQRCode desc',
			    needResult: 1, // 默认为0，扫描结果由微信处理，1则直接返回扫描结果，
			    scanType: ["qrCode","barCode"], // 可以指定扫二维码还是一维码，默认二者都有
			    success: function (res) {
			    	var result = res.resultStr; // 当needResult 为 1 时，扫码返回的结果
						if (result.indexOf('MECARD') >= 0) {
							var qrcode = result.replace('MECARD:',''),
								qrcode = qrcode.replace('http://:',''),
								qrarr = qrcode.split(";"),
								obj = {};
								for (var i = 0; i < qrarr.length; i++) {
									var temp = qrarr[i].split(":");
									if (temp != '') {
										obj[temp[0]] = temp[1];
									}
								};
								obj.URL = 'http://' + obj.URL;
						}
					window.location.href = encodeURI(encodeURI('m.php?app=user&func=addressbook&action=default&task=loadPage&from=addUser&username='+obj.N+"&mobile="+obj.TEL+"&org="+obj.ORG));
				}
			})
		})
	}
})

function doResize(){
	var a = $("#table");
	a.css('left', (document.body.clientWidth/2-a.width()/2)+"px");
	a.css('top', (document.body.scrollTop+document.body.clientHeight/2-a.height()/2)+"px");
	a.show();
}

function tooltip(){xOffset=-20;yOffset=10;$("a,input").hover(function(e){if(this.title!=''&&this.title!=undefined){this.t=this.title;this.title="";$("body").append("<p id='tooltip'>"+this.t+"</p>");$("#tooltip").css("top",(e.pageY-xOffset)+"px").css("left",(e.pageX+yOffset)+"px").fadeIn("fast");}},function(){if(this.t!=''&&this.t!=undefined){this.title=this.t;$("#tooltip").remove();}});$("a,input").mousemove(function(e){$("#tooltip").css("top",(e.pageY-xOffset)+"px").css("left",(e.pageX+yOffset)+"px");});$("img").hover(function(e){if(this.alt!=''&&this.alt!=undefined){this.t=this.alt;this.alt="";$("body").append("<p id='tooltip'>"+this.t+"</p>");$("#tooltip").css("top",(e.pageY-xOffset)+"px").css("left",(e.pageX+yOffset)+"px").fadeIn("fast");}},function(){if(this.t!=''&&this.t!=undefined){this.alt=this.t;$("#tooltip").remove();}});$("img").mousemove(function(e){$("#tooltip").css("top",(e.pageY-xOffset)+"px").css("left",(e.pageX+yOffset)+"px");});};

function setInput(){
	$("input[type=text],input[type=password]").focus(function(){
		$(this).addClass("focus");//.removeClass("error");
	}).blur(function(){
		$(this).removeClass("focus");
	}).bind('keydown',function(event) {    
		clearError("");
	});;
}
function focusInput(){
	setTimeout(function(){
		$("#userName").select();
		$("#userName").focus();
	}, 100);
}
function bindEnterKey(){
	$(document).bind('keyup',function(event) {    
		if(event.keyCode==13){    
			submit.submit();
		}    
	});
}
function makeAuthUrl(){
	return 'index.php?app=main&func=common&action=commonJob&act=authcode&r=_'+Math.random();
}
function refreshCode(){
	obj = $("#authCodeImg");
	obj.attr("src", makeAuthUrl());
	$(loginForm.authcode).val("");
	$(loginForm.authcode).focus();
}
function error(msg){
	$("#notice").html(msg);
}
function clearError(){
	$("#notice").html("");
}
/*
function createActiveX(){
	var activex = document.getElementById("CNOA_ACTIVEX");

	if(activex == null){
		var el = document.createElement('div');

		if($.browser.msie){
			el.innerHTML = '<object id="CNOA_ACTIVEX" style="LEFT: -10px; TOP: -10px" height="1" width="1"  classid="clsid:'+CNOA_ACTIVEX_CONFIG.Clsid+'"><param name="_ExtentX" value="1"><param name="_ExtentY" value="1"></object>';
		}else{
			el.innerHTML = '<object id="CNOA_ACTIVEX" style="LEFT: -10px; TOP: -10px" TYPE="application/x-itst-activex"  clsid="{'+CNOA_ACTIVEX_CONFIG.Clsid+'}" progid="'+CNOA_ACTIVEX_CONFIG.Progid+'"  height="1" width="1"><param name="_ExtentX" value="1"><param name="_ExtentY" value="1"></object>';
		}
		
		document.body.appendChild(el);
		activex = document.getElementById("CNOA_ACTIVEX");
	}

	return activex;
}

function CNOA_ActiveX_Check(){
	if(window.navigator.platform && window.navigator.platform == "Win64"){
		alert("您正在使用64位版本浏览器，请使用32位版本浏览器访问本系统！");
		return false;
	}

	try{		
		var ActiveX = createActiveX();
		var v = ActiveX.Version;

		if(!v){
			alert("您尚未安装OA系统Web组件或者组件已经失效，请点击下方地址下载安装！(安装完毕后请重启浏览器)");
			return false;
		}

		if(v < CNOA_ACTIVEX_CONFIG.Version){
			alert("您安装的OA系统Web组件有更新的版本，请点击下方地址下载安装！(安装完毕后请重启浏览器)");
			return false;
		}
	}catch(e){
		alert(e);
	}
	return true;
}
*/
function submit(){
	var _this = this;
	this.userName = null;
	this.passWord = null;
	this.authcode = null;

	this.checkForm = function(){
		this.userName = $("#userName");
		this.passWord = $("#passWord");
		this.authcode = $("#authcode");

		if(this.userName.val() == ""){
			error('请填写帐户名');
			this.userName.focus();
			return false;
		}
		if(this.passWord.val() == ""){
			error('请填写密码');
			this.passWord.focus();
			return false;
		}
		if((CNOA_enableLoginAuth == 1) && (this.authcode.val() == '')){
			error('请填写验证码');
			this.authcode.focus();
			return false;
		}
		return true;
	}
	this.submit = function(){
		//if(!CNOA_ActiveX_Check()){
		//	return false;
		//}

		if(this.checkForm()){
			error("登录中 请稍等...");
			this.doLogin();
		}
	}
	this.doLogin = function(){
		$.ajax({
			type: "post",
			url: "index.php?app=main&func=passport&action=login",
			data: "userName=" + this.userName.val() + "&passWord=" + this.passWord.val() + "&authcode=" + this.authcode.val() + "&loginSubmit=1",
			success: function(msg){
				try{
					var dataObj=eval("("+msg+")");
					if(dataObj.success === true){
						window.location.href = 'index.php';
					}else{
						if(dataObj.msg == 301){
							refreshCode();
							error("验证码不正确");
						}else{
							error(dataObj.msg);
							_this.passWord.focus();
						}
					}
				}catch(e){
					
				}
			}
		});
	}
}

submit = new submit();

$(window).resize(function() {
	doResize();
});
$(document).ready(function(){
	tooltip();
	focusInput();
	setInput();
	bindEnterKey();
	
	$("#submitbtn").click(function(){
		submit.submit();
	});
	
	setTimeout(function(){
		$(document.body).css("background", bodyBg);
	}, 60);
});



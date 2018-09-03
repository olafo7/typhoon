function tooltip() {
	xOffset = -20;
	yOffset = 10;
	$("a,input").hover(function(e) {
		if (this.title != '' && this.title != undefined) {
			this.t = this.title;
			this.title = "";
			$("body").append("<p id='tooltip'>" + this.t + "</p>");
			if(this.id == 'iOST' || this.id == 'androidT'){
				$("#tooltip").empty();
				$("#tooltip").addClass("qrcode").html("<img src='index.php?app=main&func=common&action=commonJob&act=qrcode&url="+encodeURIComponent(this.href)+"' width='120' height='120' />");
			}
			$("#tooltip").css("top", (e.pageY - xOffset) + "px").css("left", (e.pageX + yOffset) + "px").fadeIn("fast");
		}
	}, function() {
		if (this.t != '' && this.t != undefined) {
			this.title = this.t;
			$("#tooltip").remove();
		}
	});
	$("a,input").mousemove(function(e) {
		if(this.id == 'iOST' || this.id == 'androidT'){
			$("#tooltip").css("top", (e.pageY - 140) + "px").css("left", (e.pageX - 65) + "px");
		}else{
			$("#tooltip").css("top", (e.pageY - xOffset) + "px").css("left", (e.pageX + yOffset) + "px");
		}
	});
	$("img").hover(function(e) {
		if (this.alt != '' && this.alt != undefined) {
			this.t = this.alt;
			this.alt = "";
			$("body").append("<p id='tooltip'>" + this.t + "</p>");
			$("#tooltip").css("top", (e.pageY - xOffset) + "px").css("left", (e.pageX + yOffset) + "px").fadeIn("fast");
		}
	},
	function() {
		if (this.t != '' && this.t != undefined) {
			this.alt = this.t;
			$("#tooltip").remove();
		}
	});
	$("img").mousemove(function(e) {
		$("#tooltip").css("top", (e.pageY - xOffset) + "px").css("left", (e.pageX + yOffset) + "px");
	});
};
function checkMobile(){
	var rt = false;
	try{
		if($.browser.webkit){
			rt = true;
		}
	}catch(e){}
	if(!rt){
		alert("请使用手机浏览器或是谷歌(Webkit内核)的浏览器进入手机版！");
	}
	return rt;
}
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
		if($("#userName").val() == ''){
			$("#userName").select();
			$("#userName").focus();
		}else{
			$("#passWord").select();
			$("#passWord").focus();
		}
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
	var src = makeAuthUrl();
	obj = $("#authCodeImg");
	obj.attr("src", src);
	$("#authcode").val("");
	$("#authcode").focus();
}
function error(msg){
	$("#notice").html(msg);
}
function clearError(){
	$("#notice").html(defaultNoticeInfo);
}
function submit(){
	var _this = this;
	this.userName = null;
	this.passWord = null;
	this.domainId = null;
	this.authcode = null;
	this.checkForm = function(){
		this.userName = $("#userName");
		this.passWord = $("#passWord");
		this.domainId = $("#domainId");
		this.authcode = $("#authcode");
		this.language = $("#language");

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
		if( (CNOA_enableDomainID == 1) && (this.domainId.val() == "") && CNOA_enableGroup == 0 ){
			error('请填写企业ID');
			this.domainId.focus();
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
		if(this.checkForm()){
			error("登录中 请稍等...");
			this.doLogin();
		}
	}
	this.doLogin = function(){
		$.ajax({
			type: "post",
			url: "index.php?app=main&func=passport&action=login",
			data: "language=" + this.language.val() + "&userName=" + this.userName.val() + "&passWord=" + this.passWord.val() + "&domainId=" + this.domainId.val() + "&authcode=" + this.authcode.val() + "&loginSubmit=1",
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

function ajaxLang(l){
	$.ajax({
		type: "post",
		url: "index.php?app=main&func=passport&action=login",
		data: "language=" + l + "&changeLang=1",
		success: function(msg){
			
		}
	});
}
var cookie = {
	path  : '/',
	domain: '',
	get: function(n){
		var oc = document.cookie;
		var v = "";
		var s = n + "=";
		var os= -1;
		var end;
		if (oc.length > 0) {
			os = oc.indexOf(s);
			if (os != -1) {
				os += s.length;
				end = oc.indexOf(";", os);
				
				if (end == -1){
					end = oc.length;
				}
				v = unescape(decodeURI(oc.substring(os,end)));
			}
		}
		return (v===''||v===null)?null:v;
	}
}
var language = cookie.get("language");
if(language){
	setTimeout(function(){
		$("#changeLanguage").val(language);
	}, 50);
}

submit = new submit();

$(window).resize(function() {
	doResize();
});
$(document).ready(function(){
	doResize();
	tooltip();
	focusInput();
	setInput();
	bindEnterKey();
	
	$("#submitbtn").click(function(){
		submit.submit();
	});

	setTimeout(function(){
		$("#userName").val(CNOA_LOGIN_USERNAME);
		$("#passWord").val("");
		$("#userName").css("backgroundColor", "#FFF");
	}, 50);
});



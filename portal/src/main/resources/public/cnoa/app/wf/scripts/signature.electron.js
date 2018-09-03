/**
 * 电子签章
 */
var CNOA_signature_electronClass, CNOA_wf_signature_electron;
CNOA_signature_electronClass = CNOA.Class.create();
CNOA_signature_electronClass.prototype = {
	init : function(){
		var _this = this;
		this.obj = []; //储存所有签章对象
		
		//加载插件
		var obj = $("#DWebSignSeal");
		if(obj.length == 0){
			var tag= "<object id=DWebSignSeal classid='CLSID:77709A87-71F9-41AE-904F-886976F99E3E' style='position:absolute;width:0px;height:0px;left:0px;top:0px;' codebase='resources/webseal/WebSign.cab#version=4,1,0,0' ></OBJECT>";

			//var tag = CNOA.webObject.getWebSign('position:absolute;width:0px;height:0px;left:0px;top:0px;');
			$("body").children().first().before(tag);
		}
		
		//添加定位元素
		if($("#wf_signature_position").length == 0){
			var posTag = '<div style="height:1px; width:1px; display:inline;" id="wf_signature_position" value=""></div>';
			$("div:[signature_position=true]").children().first().before(posTag); 
		}
		
		//获取控件
		this.electron = $("#DWebSignSeal").get(0);	
	},
	
	show : function(me){
		if(!Ext.isIE){
			CNOA.msg.alert(lang('useIeBrowser'));
			return 0;
		}

		this.fieldId = me.attr('id');
		var oname = me.attr('oname');
		
		var lockfield = this.getLockFields(me.attr('lockfield')); //获取锁定字段
		var opt = me.attr('opt');
		if (opt == 'seal') {
			this.seal(oname, lockfield);
		}else if(opt == 'hand'){
			this.handWrite(oname, lockfield);
		}
	},
	
	/**
	 * 添加印章签章
	 * vElectronName:签章名称
	 * vPosition:签章绑定的位置
	 * vSignData：签章绑定的数据
	 */
	seal : function(obj, lockfield){
		var offsetXY = $("#wf_signature_position").offset();
		var position = $("input:[oname='"+obj+"']:first").offset();
		var X = position.left - offsetXY.left;
		var Y = position.top - offsetXY.top;
		
		obj = 'wf_signature_' + obj;
		var vElectronName = 'seal_' + obj;
		var vPosition = "wf_signature_position";
		var vSignData = lockfield;
		try{
			var strObjectName;
			strObjectName = this.electron.FindSeal("", 0);
			//判断签章是否已存在
			while(strObjectName != ''){
				console.log(vElectronName);
				if(vElectronName == strObjectName){
					CNOA.msg.alert(lang('sealExistPleaseDel'));
					return false;
				}
				strObjectName = this.electron.FindSeal(strObjectName,0);
			}
			//设置签章各参数
			
			this.electron.SetSignData("+LIST:" + lockfield);
			this.electron.SetCurrUser(CNOA_USER_TRUENAME);
			this.electron.SetPosition(X-50, Y-50, vPosition);
			vElectronName = this.electron.AddSeal("", vElectronName); //添加签章

			if(vElectronName == ""){
				 CNOA.msg.alert(lang('sealFailure'));
				 return false;
			}
			
			if(lockfield == ''){
				this.electron.SetDocAutoVerify(vElectronName, 0); //取消检验
			}
			
			this.obj.push(vElectronName);
			//去除遮挡弹出框
			$("#DD"+vElectronName).css('z-index', 5000);
		}catch(e){
			CNOA.msg.alert(lang('controlNotInstall') +e);
		}
	},
	
	/**
	 * 手写电子签章
	 * vElectronName:签章名称
	 * vPosition:签章绑定的位置
	 * vSignData：签章绑定的数据
	 */
	handWrite : function(obj, lockfield){
		var offsetXY = $("#wf_signature_position").offset();
		var position = $("input:[oname='"+obj+"']:first").offset();
		var X = position.left - offsetXY.left;
		var Y = position.top - offsetXY.top;
		
		obj = 'wf_signature_' + obj;
		var vElectronName = 'hand_' + obj;
		var vPosition = "wf_signature_position";
		var vSignData = obj;
		try{
			var strObjectName;
			strObjectName = this.electron.FindSeal("", 0);
			//判断签章是否已存在
			while(strObjectName != ''){
				if(vElectronName == strObjectName){
					CNOA.msg.alert(lang('signedPleaseDel'));
					return false;
				}
				strObjectName = this.electron.FindSeal(strObjectName,0);
			}
			//设置签章各参数
			this.electron.SetSignData("+LIST:" + lockfield);
			this.electron.SetCurrUser(CNOA_USER_TRUENAME);
			this.electron.SetPosition(X-50, Y-50, vPosition);
			vElectronName = this.electron.HandWrite(0, 255, vElectronName);
			if(vElectronName == ""){
				CNOA.msg.alert(lang('dullScreenSignature'));
				return false;
			}
			if(lockfield == ''){
				this.electron.SetDocAutoVerify(vElectronName, 0); //取消检验
			}
			
			this.obj.push(vElectronName);
			//去除遮挡弹出框
			$("#DD"+vElectronName).css('z-index', 5000);
		}catch(e){
			CNOA.msg.alert(lang('controlNotInstall') +e);
		}
	},
	
	/**
	 * 绑定的数据
	 */
	checkData : function(){
		try{
			var strObjName = this.electron.FindSeal("", 0);
			while(strObjName != ""){
				this.electron.VerifyDoc(strObjName);
				strObjName = this.electron.FindSeal(strObjName, 0);
			}
		}catch(e){}
	},
	
	/**
	 * 获取签章数据 base64
	 */
	GetSealValue :  function(){
		var _this = this;
		var id = '';
		var oname = '';
		var data = '';
		var me = '';
		var val = '';
		
		var sealData = $("[id^=seal_wf_signature]");
		sealData.each(function(){
			id = $(this).attr('id');
			oname = id.substring(id.lastIndexOf("_")+1);
			me = $("input[type='hidden'][oname='" + oname + "']");
			val = me.val();
			data = _this.electron.GetStoreDataEx(id);
			if(data != ''){
				me.val(val + 'seal:' + data + ';');
			}
		});
		
		var handData = $("[id^=hand_wf_signature]");
		handData.each(function(){
			id = $(this).attr('id');
			oname = id.substring(id.lastIndexOf("_")+1);
			me = $("input[type='hidden'][oname='" + oname + "']");
			val = me.val();
			data = _this.electron.GetStoreDataEx(id);
			if(data != ''){
				me.val(val + 'handwrite:' + data + ';');
			}
		});
	},
	
	/**
	 * 设置数据
	 * @param {Object} data
	 */
	SetSealValue : function(data){
		try {
			var _this = this;
			data.each(function(){
				var val = $(this).val();
				var oname = $(this).attr('oname');
				var id = $(this).attr('id');
				
				_this.electron.SetStoreData(val);
				_this.electron.ShowWebSeals();

				//判断是否取消右击菜单
				if ($("input[type='button'][oname='" + oname + "']").length == 0) {
					_this.electron.SetMenuItem(id, 0);
				}
				var lockfield = $("input[type='button'][oname='" + oname + "']").attr('lockfield');
				if (lockfield == '' || typeof(lockfield) == 'undefined') {
					_this.electron.SetDocAutoVerify("seal_wf_signature_"+oname, 0); //取消检验
					if (typeof(lockfield) == 'undefined') {
						Y = _this.electron.GetSealPosY("seal_wf_signature_"+oname);
						X = _this.electron.GetSealPosX("seal_wf_signature_"+oname); 
						_this.electron.MoveSealPosition("seal_wf_signature_"+oname, X, Y, "seal_wf_signature_"+oname+'position');
					}
				} 
			});
			//去除遮挡弹出框
			$("div[id^='DD']").each(function(){
				$(this).css('z-index', 5000);
			});
		}catch(e){}
	},
	
	/**
	 * 销毁签章
	 */
	destroy : function(){
		for(var i=0; i<this.obj.length; ++i){
			//删除签章
			this.electron.DelSeal(this.obj[i]);
			//删除自动添加的标签<div>和<object>
			$('#'+this.obj[i]).remove(); 
			$('#D'+this.obj[i]).remove();
			$('#DWebSignSeal').remove();
		}
	},
	
	/**
	 * 销毁查看时的签章
	 */
	showDestroy : function(){
		var div = $("[id^=wf_signature][id$=position]");
		div.each(function(){
			$(this).remove();
			$('#DWebSignSeal').remove();
		});
	},
	
	/**
	 * 获取锁定字段
	 * @param {string} lockfield 所有锁定字段的oname属性值
	 */
	getLockFields : function(lockfield){
		if (lockfield != '') {
			var onames = lockfield.split(',');
			var fields = '';
			for (var i = 0; i < onames.length; ++i) {
				fields += $("[type!='hidden'][oname='" + onames[i] + "']").attr('id') + ';';
			}
			return fields;
		}
		return '';
	}
};

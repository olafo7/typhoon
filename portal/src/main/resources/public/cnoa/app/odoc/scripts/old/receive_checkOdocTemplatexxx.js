/**
 * 全局变量
 */

/*
=============================================
显示服务器时间
*/
//用来存放差值
var differentMillisec = 0;

function initServerTime() {
    //取时间差值
	getServerDate();
}

//取得显示时间
function showtime(){
	now = new Date();
	now.setTime(differentMillisec + now.getTime());
	str = now.format('Y年m月d日 H:i:s');
	
	CNOA_odoc_send_apply_edit_dealTime = now.format('Y.m.d H:i');
	var today = new Array(lang('sunday'), lang('monday'), lang('tuesday'), lang('wednesday'), lang('thursday'), lang('friday'), lang('saturday'));
	str += " [" + today[now.getDay()] + "]";
	try{Ext.getCmp("CNOA_SERVERTIME_BAR").getEl().update(str);}catch(e){}
	ctroltime=setTimeout("showtime()", 1000);
}

function getServerTime(tarea_id, tarea_va){
	tarea_va = tarea_va.replace("{TIME}", CNOA_odoc_send_apply_edit_dealTime);
	jQuery("textarea[name^='"+tarea_id+"']").attr('value', tarea_va);
}

//从服务器取时间,用的buffalo取
function getServerDate() {
	begin = new Date();
	millisecbeg = begin.getTime();

	Ext.Ajax.request({
		url: 'index.php?action=commonJob&act=getServerTime13',
		method: "POST",
		success: function(r, opts) {
			var serverMillisec = r.responseText;
			end = new Date();
			millisecend = end.getTime();
			differentMillisec = serverMillisec - new Date() + (millisecend - millisecbeg)/2;
			//取得显示时间
			showtime();
		},
		failure: function(response, opts) {
			CNOA.msg.alert(result.msg);
		}
	});
}
getServerDate();
/*
显示服务器时间
=============================================
*/

maxsizeWindow();

var CNOA_odoc_receive_check_edit, CNOA_odoc_receive_check_editClass;
CNOA_odoc_receive_check_editClass = CNOA.Class.create();
CNOA_odoc_receive_check_editClass.prototype = {
	init : function(){
		var _this = this;
		
		this.ID_attachCt = Ext.id();
		this.id		= CNOA.odoc.id;
		this.stepId = CNOA.odoc.stepId;
		
		this.type	= CNOA.odoc.type;
		
		this.baseUrl = "index.php?app=odoc&func=receive&action=check";
		this.baseURI = location.href.substr(0, location.href.lastIndexOf("/")+1);
		
		/*
		this.webofficeHtml  = '<object id="CNOA_WEBOFFICE" height="768" width="100%" style="left: 0px; TOP: 0px" classid="clsid:E77E049B-23FC-4DB8-B756-60529A35FAD5" codebase="resources/weboffice/v6.0.5.0.cab#version=6,0,5,0">';
		this.webofficeHtml += '<param name="_ExtentX" value="6350">';
		this.webofficeHtml += '<param name="_ExtentY" value="6350">';
		this.webofficeHtml += '要显示公文正文内容，请切换到IE浏览器下查看';
		this.webofficeHtml += '</object>';
		*/

		this.webofficeHtml = CNOA.webObject.getWebOffice("100%", 768, 'left: 0px; TOP: 0px');
		
		this.attachTpl = new Ext.XTemplate(
			'		{attach}'
		);
		
		this.baseField = [
			{
				xtype: "fieldset",
				title: "收文单",
				//anchor: "97%",
				html: "{FAWENFORM}"
			},
			{
				xtype: "fieldset",
				title: "审批进度",
				layout: 'fit',
				height: 300,
				items: _this.checkProgress()
			},
			{
				xtype: "fieldset",
				title: lang('attach'),
				autoHeight: true,
				id: this.ID_attachCt
			},
			{
				xtype: "fieldset",
				title: "公文正文",
				items: [
					{
						xtype: 'panel',
						html: this.webofficeHtml
					}
				],
				listeners: {
					afterrender : function(){
						jQuery("input[name^='id_radio_']").each(function(){
							_this.makeHuiQianFunc(jQuery(this));
						});
					}
				}
			}
		];
		
		this.form = new Ext.form.FormPanel({
			labelWidth: 120,
			labelAlign: 'right',
			region : "center",
			waitMsgTarget: true,
			layout: "border",
			items:[
				{
					xtype: "panel",
					border: false,
					bodyStyle: "padding:10px;",
					layout: "form",
					region: "center",
					autoScroll : true,
					items: this.baseField
				}
			],
			tbar: [
				{
					text : "转下一步",
					iconCls: 'icon-fileview-column3',
					style: "margin-left:5px",
					cls: "x-btn-over",
					listeners: {
						"mouseout" : function(btn){
							btn.addClass("x-btn-over");
						}
					},
					handler : function(){
						_this.submitFormData(true);
					//	if(_this.type == "apply"){
							//var CNOA_odoc_receive_apply_selectFlow = new CNOA_odoc_receive_apply_selectFlowClass(_this.id);
					//	}else if(_this.type == "check"){
					//		var CNOA_odoc_receive_check_flow = new CNOA_odoc_receive_check_flowClass(_this.id);
					//	}
					}.createDelegate(this)
				},/*"-",
				{
					text: "暂存",
					iconCls : "icon-btn-save",
					cls: "x-btn-over",
					listeners: {
						"mouseout" : function(btn){
							btn.addClass("x-btn-over");
						}
					},
					handler : function(){
						_this.submitFormData(false);
					}
				},*/
				"->",
				new Ext.BoxComponent({
					id: "CNOA_SERVERTIME_BAR",
					autoEl: {
						tag: 'div',
						html: ''
					}
				}),
				{
					text : lang('close'),
					iconCls: 'icon-dialog-cancel',
					style: "margin-left:5px",
					cls: "x-btn-over",
					listeners: {
						"mouseout" : function(btn){
							btn.addClass("x-btn-over");
						}
					},
					handler : function(){
						window.close();
					}
				}
			]
		});
		
		this.mainPanel = new Ext.Viewport({
			layout: 'border',
			style: 'background-color:#fff;border-width: 1px;',
			autoScroll: true,
			items: [this.form]
		});
		
		this.loadFormData();
	},
	
	checkProgress : function(_this){
		var _this = this;
		
		var fields = [
			{name : "id"},
			{name: "stepname"},
			{name: "from"},
			{name: "say"},
			{name: "etime"},
			{name: "status"}
		];
		
		var store = new Ext.data.Store({
			proxy:new Ext.data.HttpProxy({url: _this.baseUrl+"&task=getStepList", disableCaching: true}),   
			reader:new Ext.data.JsonReader({totalProperty:"total",root:"data", fields: fields}),
			listeners:{
				exception : function(th, type, action, options, response, arg){
					var result = Ext.decode(response.responseText);
					if(result.failure){
						CNOA.msg.alert(result.msg);
					}
				},
				"load" : function(th, rd, op){
					
				}
			}
		});
		
		store.setBaseParam('fid', _this.id);
		store.setBaseParam('status', 1);
		store.load();
		
		var sm = new Ext.grid.CheckboxSelectionModel({
			singleSelect: false
		});
			
		var colModel = new Ext.grid.ColumnModel([
			new Ext.grid.RowNumberer(),
			//sm,
			{header: "id", dataIndex: 'id', hidden: true},			
			{header: '步骤名称', dataIndex: 'stepname', width: 150, sortable: true, menuDisabled :true},
			{header: '状态', dataIndex: 'status', width: 100, sortable: true, menuDisabled :true},
			{header: '所在部门／经办人', dataIndex: 'from', width: 200, sortable: true, menuDisabled :true},
			{header: '意见', dataIndex: 'say', width: 180, sortable: true, menuDisabled :true},
			{header: '审批时间', dataIndex: 'etime', width: 123, sortable: true, menuDisabled :true},
			{header: "", dataIndex: 'noIndex', width: 1, menuDisabled: true,resizable: false, hidden: true}
		]);
		
		var grid = new Ext.grid.GridPanel({
			stripeRows : true,
			store:store,
			loadMask : {msg: lang('waiting')},
			cm: colModel,
			sm: sm,
			//hideBorders: true,
			listeners:{
				"rowdblclick":function(button, event){
					
				}
			}
		});
		
		return grid;
	},
	
	submitFormData : function(gonext){
		var _this = this;
		
		this.setOfficeAcitveXDisplay(false);
		
		//this.form.getForm().findField('content').syncValue(); 
		
		var saveDocFile = function(){
			try{
				var webObj = document.getElementById("CNOA_WEBOFFICE");
				var returnValue;
				webObj.HttpInit();
				webObj.HttpAddPostString("id", _this.id);
				webObj.HttpAddPostCurrFile("msOffice", "");
				returnValue = webObj.HttpPost(_this.baseURI + _this.baseUrl + "&task=submitFileData");
				if("1" == returnValue){
					if(gonext != true){
						CNOA.msg.alert("已保存", function(){
							_this.setOfficeAcitveXDisplay(true);
						});
					}else{
						//转下一步
						_this.sendNext();
					}
					
					//try{
					//	//opener.CNOA_odoc_setting_editTemplate.reloadDocTpl();
					//}catch(e){}
					//window.close();
				}else if("0" == returnValue)
					alert("操作失败");
			}catch(e){
				alert("异常\r\nError:"+e+"\r\nError Code:"+e.number+"\r\nError Des:"+e.description);
			}
		};

		if (this.form.getForm().isValid()) {
			this.form.getForm().submit({
				url: _this.baseUrl + "&task=submitFormData",
				waitMsg: lang('waiting'),
				params: {id : _this.id},
				method: 'POST',	
				success: function(form, action) {
					saveDocFile();
				}.createDelegate(this),
				failure: function(form, action) {
					CNOA.msg.alert(action.result.msg);
				}.createDelegate(this)
			});
		}
	},

	makeHuiQianFunc : function(radio){
		var tarea_id = radio.attr('name').replace("id_radio_", "id_");
		var tarea_va = radio.attr('cname') + " ("+CNOA.odoc.truename+" {TIME})";

		radio.click(function(){
			getServerTime(tarea_id, tarea_va);
		});
	},
		
	sendNext : function(){
		var _this =this;
		CNOA_odoc_receive_check_flow = new CNOA_odoc_receive_check_flowClass(_this.stepId);
	},
	
	setOfficeAcitveXDisplay : function(showHide){
		if(showHide){
			jQuery('#CNOA_WEBOFFICE').show();
		}else{
			jQuery('#CNOA_WEBOFFICE').hide();
		}
	},
	
	loadFormData : function(){
		var _this = this;
		
		this.form.getForm().load({
			url: _this.baseUrl + "&task=loadFormData",
			method:'POST',
			params : {id : _this.id},
			waitTitle: lang('notice'),
			success: function(form, action){
				var rd = action.result.data;
				_this.attachTpl.overwrite(Ext.getCmp(_this.ID_attachCt).body, rd);
			},
			failure: function(form, action) {
				CNOA.msg.alert(action.result.msg, function(){
					
				});
			}
		})
	}
}

CNOA_odoc_receive_check_flowClass = CNOA.Class.create();
CNOA_odoc_receive_check_flowClass.prototype = {
	init : function(id){
		var _this = this;
		
		var ID_TEXT_SAY = Ext.id();
		
		this.baseUrl = "index.php?app=odoc&func=receive&action=check";
		
		this.baseField = [
			{
				xtype : "textarea",
				fieldLabel : "意见",
				width : 500,
				height : 260,
				name : "say",
				value : "同意",
				id : ID_TEXT_SAY
			},
			{
				xtype: 'radiogroup',
				labelWidth: 1,
				width: 200,
				style : "margin-left : 360px",
				hideLabel: true,
				items:[
					{
						boxLabel: '已阅',
						name: 'send_check_say',
						inputValue: 1
					},{
						boxLabel: '同意',
						name: 'send_check_say',
						inputValue: 2,
						checked : true
					},{
						boxLabel: '不同意',
						name: 'send_check_say',
						inputValue: 3
					}
				],
				listeners : {
					"change" : function(th, checked){
						if(checked.inputValue=="1"){
							Ext.getCmp(ID_TEXT_SAY).setValue("已阅");
						}else if(checked.inputValue=="2"){
							Ext.getCmp(ID_TEXT_SAY).setValue("同意");
						}else if(checked.inputValue=="3"){
							Ext.getCmp(ID_TEXT_SAY).setValue("不同意");
						}
					}
				}
			}
		];
		
		this.form = new Ext.form.FormPanel({
			labelWidth: 40,
			labelAlign: 'right',
			region : "center",
			bodyStyle : "padding : 10px",
			border : false,
			autoScroll : true,
			waitMsgTarget: true,
			items:[
				{
					xtype: "panel",
					border: false,
					layout: "form",
					region: "center",
					items: this.baseField
				}
			]
		});
		
		this.mainPanel = new Ext.Window({
			title : "转下一步办理",
			layout : "border",
			resizable : false,
			autoScroll: false,
			width : 620,
			height : makeWindowHeight(420),
			modal : true,
			layout : "card",
			activeItem: 0,
			items : [this.form],
			buttons : [
				{
					text : "转下一步",
					iconCls: 'icon-dialog-apply2',
					handler : function(){
						_this.submit(id);
					}
				},
				{
					text : lang('close'),
					iconCls: 'icon-dialog-cancel',
					handler : function(){
						_this.mainPanel.close();
					}
				}
			],
			listeners: {
				show : function(){
					//CNOA_odoc_receive_apply_edit.setOfficeAcitveXDisplay(false);
				},
				close : function(){
					//CNOA_odoc_receive_apply_edit.setOfficeAcitveXDisplay(true);
				}
			}
		}).show();
	},
	
	submit : function(id){
		var _this = this;
		
		var say = _this.form.getForm().findField("say").getValue();

		Ext.Ajax.request({
			url: _this.baseUrl + '&task=nextstep',
			params: {id : id, say : say},
			method: "POST",
			success: function(r, opts) {
				var result = Ext.decode(r.responseText);
				if(result.success === true){
					CNOA.msg.notice(result.msg, '公文管理');
					try{
						opener.CNOA_odoc_receive_check.store.reload();
					}catch(e){
						
					}
					window.close();
				}else{
					
				}
			},
			failure: function(response, opts) {
				CNOA.msg.alert(result.msg);
			}
		});
	}
}

Ext.onReady(function() {
	if(!Ext.isIE){
		CNOA.msg.alert("此功能因使用Office控件，需要使用IE浏览器，请使用IE浏览器重新进入本功能。", function(){
			window.close();
		});
	}else{
		CNOA_odoc_receive_check_edit = new CNOA_odoc_receive_check_editClass();
		CNOA_odoc_receive_check_edit.mainPanel.doLayout();
	}
});
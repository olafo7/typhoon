/**
 * 全局变量
 */
var CNOA_odoc_send_apply_edit, CNOA_odoc_send_apply_editClass;
var CNOA_odoc_send_apply_edit_dealTime = "";

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
	var today = new Array("星期日", "星期一", "星期二", "星期三", "星期四", "星期五", "星期六");
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

CNOA_odoc_send_apply_editClass = CNOA.Class.create();
CNOA_odoc_send_apply_editClass.prototype = {
	init : function(){
		var _this = this;
		
		this.id				= CNOA.odoc.id;
		this.ID_attachCt	= Ext.id();
		this.ID_printArea	= Ext.id();
		this.ID_baseInfo	= Ext.id();
		this.func			= CNOA.odoc.func;
		this.action			= CNOA.odoc.action;
		this.fromType		= CNOA.odoc.fromType;
		this.title			= "发文";

		if(CNOA.odoc.fromType == "send"){
			this.title = "发文档案";
			this.showSR = true;
			this.showProgress = true;
		}else if(CNOA.odoc.fromType == "receive"){
			this.title = "收文档案";
			this.showSR = true;
			this.showProgress = true;
		}else{
			this.title = "档案";
			this.showSR = false;
			this.showProgress = false;
		}
		
		if(this.func == "read"){
			this.showProgress = false;
		}

		
		this.baseUrl = "index.php?app=odoc&func=" + this.func + "&action="+this.action + "&fromType=" + this.fromType;
		this.baseURI = location.href.substr(0, location.href.lastIndexOf("/")+1);
		
		/*
		this.webofficeHtml  = '<object id="CNOA_WEBOFFICE" height="100%" width="100%" style="left: 0px; TOP: 0px" classid="clsid:E77E049B-23FC-4DB8-B756-60529A35FAD5" codebase="resources/weboffice/v6.0.5.0.cab#version=6,0,5,0">';
		this.webofficeHtml += '<param name="_ExtentX" value="6350">';
		this.webofficeHtml += '<param name="_ExtentY" value="6350">';
		this.webofficeHtml += '要显示公文正文内容，请切换到IE浏览器下查看';
		this.webofficeHtml += '</object>';
		*/

		this.webofficeHtml = CNOA.webObject.getWebOffice("100%", 768, 'left: 0px; TOP: 0px');
		
		this.attachTpl = new Ext.XTemplate(
			'{attach}'
		);
		
		
		this.fields = [
			{name : "id"},
			{name: "stepname"},
			{name: "from"},
			{name: "say"},
			{name: "stime"},
			{name: "etime"},
			{name: "status"}
		];
		
		if(this.showProgress){
			this.store = new Ext.data.Store({
				proxy:new Ext.data.HttpProxy({url: _this.baseUrl+"&task=view&act=getStepList", disableCaching: true}),   
				reader:new Ext.data.JsonReader({totalProperty:"total",root:"data", fields: this.fields}),
				listeners:{
					exception : function(th, type, action, options, response, arg){
						var result = Ext.decode(response.responseText);
						if(result.failure){
							CNOA.msg.alert(result.msg);
						}
					}
				}
			});
			this.store.setBaseParam('id', _this.id);
			this.store.setBaseParam('fid', _this.id);
			this.store.setBaseParam('status', 1);
			this.store.load();
			
			this.preogresssm = new Ext.grid.CheckboxSelectionModel({
				singleSelect: false
			});
			
			this.preogressColModel = new Ext.grid.ColumnModel([
				new Ext.grid.RowNumberer(),
				//sm,
				{header: "id", dataIndex: 'id', hidden: true},			
				{header: '步骤名称', dataIndex: 'stepname', width: 150, sortable: true, menuDisabled :true},
				{header: '状态', dataIndex: 'status', width: 100, sortable: true, menuDisabled :true},
				{header: '所在部门／经办人', dataIndex: 'from', width: 200, sortable: true, menuDisabled :true},
				{header: '意见', dataIndex: 'say', width: 180, sortable: true, menuDisabled :true},
				{header: '开始时间', dataIndex: 'stime', width: 123, sortable: true, menuDisabled :true},
				{header: '结束时间', dataIndex: 'etime', width: 123, sortable: true, menuDisabled :true},
				{header: "", dataIndex: 'noIndex', width: 1, menuDisabled: true,resizable: false, hidden: true}
			]);
			
			this.preogressGrid = new Ext.grid.GridPanel({
				stripeRows : true,
				store: this.store,
				loadMask : {msg: CNOA.lang.msgLoadMask},
				cm: this.preogressColModel,
				sm: this.preogresssm,
				autoHeight: true,
				listeners:{
					"rowdblclick":function(button, event){
						
					}
				}
			});
		}
		
		this.formHtml = {
			xtype: "fieldset",
			title: _this.title,
			html: "<div id='"+this.ID_printArea+"'>载入中...</div>",
			listeners: {
				afterrender : function(){
					_this.getFormHtml();
				}
			}
		};

		this.baseField = [
			{
				xtype: "fieldset",
				title: "基本信息",
				id: this.ID_baseInfo
			},
			{
				xtype:'button',
				text:'查看流程',
				handler:function(btn){
					opener.mainPanel.closeTab("CNOA_MENU_WF_USE_OPENFLOW");
					opener.mainPanel.loadClass(_this.baseUrl+"&task=loadpage&from=viewflow&uFlowId="+CNOA.odoc.uFlowId, "CNOA_MENU_WF_USE_OPENFLOW", "查看工作流程", "icon-flow");
				}
			},
			//(this.showSR ? this.formHtml : {xtype: "box"}),
			{
				xtype: "fieldset",
				title: "附件",
				autoHeight: true,
				id: this.ID_attachCt,
				listeners: {
					render : function(p){
						_this.getAttachList();
					}
				}
			},
			(this.showProgress ? this.preogressGrid : {xtype: "box"})
		];
		
		this.zwPanel = new Ext.Panel({
			title: "公文正文",
			layout: "border",
			bodyStyle: 'border-left-width:1px;',
			items: [
				{
					xtype: 'panel',
					region : "center",
					border: false,
					html: this.webofficeHtml
				}
			],
			listeners: {
				afterrender : function(th){
					
				}
			},
			tbar: [
				{
					text: "显示工具栏",
					iconCls: "icon-ui-combo-box-calendar",
					enableToggle: true,
					cls: "x-btn-over",listeners: {"mouseout" : function(btn){btn.addClass("x-btn-over");}},
					toggleHandler: function(btn, state){
						_this.setOfficeToolsDisplay(state);
						if(state){
							btn.setText("隐藏工具栏");
						}else{
							btn.setText("显示工具栏");
						}
					}
				},
				{
					text: "隐藏修订",
					iconCls: "icon-utils-s-edit",
					enableToggle: true,
					cls: "x-btn-over",style: "margin-left:2px;",listeners: {"mouseout" : function(btn){btn.addClass("x-btn-over");}},
					toggleHandler: function(btn, state){
						_this.setOfficeXiuDinDisplay(state);
						if(state){
							btn.setText("显示修订");
						}else{
							btn.setText("隐藏修订");
						}
					}
				},
				{
					text: "打印",
					cls: "x-btn-over",listeners: {"mouseout" : function(btn){btn.addClass("x-btn-over");}},
					iconCls: "icon-print",
					style: "margin-left:2px;",
					handler: function(){
						_this.printDoc();
					}
				}
			]
		});
		
		this.form = new Ext.form.FormPanel({
			title: _this.title,
			labelWidth: 120,
			labelAlign: 'right',
			region : "center",
			waitMsgTarget: true,
			layout: "border",
			bodyStyle: 'border-left-width:1px;',
			items:[
				{
					xtype: "panel",
					bodyStyle: "padding:10px;",
					border: false,
					//layout: "form",
					autoScroll : true,
					region: "center",
					items: this.baseField
				}
			],
			tbar: new Ext.Toolbar({
				hidden: this.showSR ? false : true,
				items: [
					{
						text: "打印"+_this.title,
						cls: "x-btn-over",listeners: {"mouseout" : function(btn){btn.addClass("x-btn-over");}},
						iconCls: "icon-print",
						style: "margin-left:2px;",
						handler: function(){
							printArea(_this.ID_printArea, "打印");
						}
					}
				]
			})
		});
		
		this.centerPanel = new Ext.ux.VerticalTabPanel({
			region: "center",
			border: false,
			tabPosition: "left",
			tabWidth: 100,
			activeItem: 0,
			//layout: "fit",
			items: this.showSR ? [this.form, this.zwPanel] : [this.form]
		});
		
		this.mainPanelCt = new Ext.Panel({
			border: false,
			layout: 'border',
			items: [this.centerPanel],
			tbar: [
				"查看档案","->",
				new Ext.BoxComponent({
					id: "CNOA_SERVERTIME_BAR",
					autoEl: {
						tag: 'div',
						html: ''
					}
				}),
				{
					text:"重新加载",
					iconCls:'icon-system-refresh',
					cls: "x-btn-over",
					listeners: {
						"mouseout" : function(btn){
							btn.addClass("x-btn-over");
						}
					},
					handler:function(button, event){
						location.reload();
					}
				},"-",
				{
					text : "关闭",
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
			layout: 'fit',
			style: 'background-color:#fff;',
			autoScroll: true,
			items: [this.mainPanelCt]
		});
		
		this.loadBaseInfo();
	},
	
	loadBaseInfo : function(){
		var _this = this;
		
		Ext.Ajax.request({  
			url: _this.baseUrl + "&task=view&act=loadBaseInfo",
			params: {id: _this.id},
			method: 'POST',
			success: function(r) {
				var result = Ext.decode(r.responseText);
				
				//_this.cid = result.cid;
				makeListView2(result, Ext.getCmp(_this.ID_baseInfo).body);
			},
			failure : function(r){
				var result = Ext.decode(r.responseText);
			}
		});
	},
	
	
	getFormHtml : function(){
		var _this = this;
		
		Ext.Ajax.request({  
			url: _this.baseUrl + "&task=view&act=getFormHtml",
			method: 'POST',
			params : {id : _this.id},
			success: function(r) {
				Ext.fly(_this.ID_printArea).update(r.responseText);
			}
		});
	},
	
	getAttachList : function(){
		var _this = this;
		
		//_this.form.getEl().mask(CNOA.lang.msgLoadMask);
		
		Ext.Ajax.request({  
			url: _this.baseUrl + "&task=view&act=getAttachList",
			method: 'POST',
			params : {id : _this.id},
			success: function(r) {
				var result = Ext.decode(r.responseText);
				if(result.success === true){
					var rd = result.data;

					_this.attachTpl.overwrite(Ext.getCmp(_this.ID_attachCt).body, rd);
				}else{
					CNOA.msg.alert(result.msg);
				}
				//_this.form.getEl().unmask();
			}
		});
	},
	
	setOfficeAcitveXDisplay : function(showHide){
		if(showHide){
			jQuery('#CNOA_WEBOFFICE').show();
		}else{
			jQuery('#CNOA_WEBOFFICE').hide();
		}
	},

	setOfficeToolsDisplay : function(showHide) {
		var obj = document.getElementById('CNOA_WEBOFFICE');
		if(!showHide){
			try{
				obj.hideMenuArea("hideall"," "," "," ");
			}catch(e){}
		}else{
			try{
				obj.hideMenuArea("showmenu"," "," "," ");
			}catch(e){}
		}
	},

	setOfficeXiuDinDisplay : function(showHide) {
		var obj = document.getElementById('CNOA_WEBOFFICE');
		if(showHide){
			try{
				obj.ShowRevisions(0);
			}catch(e){}
		}else{
			try{
				obj.ShowRevisions(1);
			}catch(e){}
		}
	},
	
	printDoc : function(){
		var obj = document.getElementById('CNOA_WEBOFFICE');
		try{
			obj.PrintDoc(1);
		}catch(e){}
	}
}

Ext.onReady(function() {
	//if(!Ext.isIE){
	//	CNOA.msg.alert("此功能因使用Office控件，需要使用IE浏览器，请使用IE浏览器重新进入本功能。", function(){
	//	window.close();
	//	});
	//}else{
		CNOA_odoc_send_apply_edit = new CNOA_odoc_send_apply_editClass();
		CNOA_odoc_send_apply_edit.mainPanel.doLayout();
	//}
});
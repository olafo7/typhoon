/**
 * 全局变量
 */
var CNOA_odoc_send_apply_edit, CNOA_odoc_send_apply_editClass;
var CNOA_odoc_send_apply_selectFlow, CNOA_odoc_send_apply_selectFlowClass;
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

CNOA_odoc_send_apply_selectFlowClass = CNOA.Class.create();
CNOA_odoc_send_apply_selectFlowClass.prototype = {
	init: function(id){
		var _this = this;
		
		var ID_SET_UID		= Ext.id();
		var ID_CHOICE_VALUE	= Ext.id();
		
		this.from = "fixed";
		
		this.baseUrl = "index.php?app=odoc&func=send&action=apply";
		
		this.flowListStore = new Ext.data.Store({
			autoLoad: true,
			proxy: new Ext.data.HttpProxy({
				url: _this.baseUrl+"&task=getFlowList"
			}),
			reader: new Ext.data.JsonReader({
				root:'data',
				fields: [
					{name : "flowid", mapping: 'id'},
					{name : "flowname", mapping: 'name'}
				]
			})
		});
		
		
		//绑定流程开始
		this.fixedFields = [
			{name : "id"},
			{name : "stepName"},
			{name : "uname"}
		];
		
		this.fixedstore = new Ext.data.Store({
			proxy:new Ext.data.HttpProxy({url: this.baseUrl+"&task=getFixedJsonData", disableCaching: true}),   
			reader:new Ext.data.JsonReader({totalProperty:"total",root:"data", fields: this.fixedFields}),
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
		
		this.sm = new Ext.grid.CheckboxSelectionModel({
			singleSelect: false
		});
		
		this.fixedcolModel = new Ext.grid.ColumnModel([
			new Ext.grid.RowNumberer(),
			{header: "id", dataIndex: 'id', hidden: true},
			{header: '步骤名称', dataIndex: 'stepName', width: 350, sortable: true, menuDisabled :true},
			{header: '经办人', dataIndex: 'uname', width: 100, sortable: true, menuDisabled :true}
		]);
		
		this.fixedGrid = new Ext.grid.GridPanel({
			stripeRows : true,
			region : "center", 
			border:true, 
			width : 480, 
			height : 200, 
			bodyStyle: 'border-left-width:1px;',
			store:this.fixedstore, 
			loadMask : {msg: lang('waiting')},
			cm: this.fixedcolModel, 
			sm: this.sm, 
			hideBorders: true, 
			listeners:{
				"rowdblclick":function(button, event){
					
				}
			}
		});
		
				
		this.fixedBaseField = [
			{
				xtype: "fieldset",
				title: "选择流程",
				autoHeight: true,
				defaults : {
					border : false,
					style : "margin-bottom : 5px"
				},
				items: [
					new Ext.form.ComboBox({
						allowBlank : false,
						fieldLabel : "流转流程",
						name: 'flowid',
						store: this.flowListStore,
						width: 480,
						hiddenName: 'flowid',
						valueField: 'flowid',
						displayField: 'flowname',
						blankText: "请选择要绑定的流转流程",
						mode: 'local',
						triggerAction: 'all',
						forceSelection: true,
						editable: false,
						id : ID_CHOICE_VALUE,
						listeners: {
							change : function(th, newValue, oldValue){
								//
							},
							select : function(combo, record, index){
								_this.fixedstore.load({params : {id : record.id }});
							}
						}
					}),
					{
						xtype : "panel",
						fieldLabel : "步骤信息",
						items : [this.fixedGrid]
					}
				]
			}
		];
		
		this.fixed = new Ext.form.FormPanel({
			border: false,
			labelWidth: 80,
			labelAlign: 'right',
			region : "center",
			autoScroll: true,
			waitMsgTarget: true,
			items : [
				{
					xtype: "panel",
					border: false,
					layout: "form",
					bodyStyle: "padding:10px;",
					items: this.fixedBaseField
				}
			]
		});
		//绑定流程结束
		
		
		
		//自定义流程开始
		
		this.defineddsc = Ext.data.Record.create([
		{
			name: 'stepname',
			type: 'string'
		}]);
		
		this.definedFields = [
			{name : "id"},
			{name : "uid"},
			{name : "stepname"},
			{name : "uname"}
		];
		
		this.definedstore = new Ext.data.GroupingStore({
			proxy:new Ext.data.HttpProxy({url: this.baseUrl+"&task=getDefinedJsonData", disableCaching: true}),   
			reader:new Ext.data.JsonReader({totalProperty:"total",root:"data", fields: this.definedFields}),
			listeners:{
				exception : function(th, type, action, options, response, arg){
					var result = Ext.decode(response.responseText);
					if(result.failure){
						CNOA.msg.alert(result.msg);
					}
				},
				'update': function(thiz, record, operation) {
					var user	= record.data;
					user.fid	= id;
					user.uid	= Ext.getCmp(ID_SET_UID).getValue();
					if (operation == Ext.data.Record.EDIT) {
						//判断update时间的操作类型是否为 edit 该事件还有其他操作类型比如 commit,reject 
						_this.submit(user);
					}
				}
			}
		});
		
		this.definedstore.load({params : {fid : id}});
		
		this.editor = new Ext.ux.grid.RowEditor({
			cancelText: lang('cancel'),
			saveText: lang('update'),
			errorSummary: false
		});
		
		this.sm = new Ext.grid.CheckboxSelectionModel({
			singleSelect: false
		});
		
		this.definedcolModel = new Ext.grid.ColumnModel([
			new Ext.grid.RowNumberer(),
			this.sm,
			{header: "id", dataIndex: 'id', hidden: true},
			{header: "uid", dataIndex: 'uid', hidden: true, editor: {
				xtype : "hidden",
				id : ID_SET_UID
			}},
			{header: '步骤名称', dataIndex: 'stepname', width: 350, sortable: true, menuDisabled :true, editor: {
				xtype: 'textfield',
				allowBlank: false
			}},
			{header: '经办人', dataIndex: 'uname', width: 100, sortable: true, menuDisabled :true, editor: {
				xtype: "triggerForPeople",
				fieldLabel:"审批人员",
				allowBlank: false,
				name:"uname",
				dataUrl: _this.baseUrl+"&task=getAllUserListsInPermitDeptTree",
				width: 230,
				listeners: {
					"selected": function(th, uid, uname){
						Ext.getCmp(ID_SET_UID).setValue(uid);
					}
				}
			}}
		]);
		
		this.definedGrid = new Ext.grid.GridPanel({
			stripeRows : true,
			region : "center",
			border:true,
			width : 500,
			height : 200,
			bodyStyle: 'border-left-width:1px;',
			store:this.definedstore,
			plugins: [this.editor],
			view: new Ext.grid.GroupingView({
				markDirty: false
			}),
			loadMask : {msg: lang('waiting')},
			cm: this.definedcolModel,
			sm: this.sm,
			hideBorders: true,
			listeners:{
				cellclick:function(th, rowNum, columnNum, e){
					if(columnNum == 1){
						return false;
					}
				}
			},
			tbar : [
				{
					handler : function(button, event) {
						_this.definedstore.reload();
					}.createDelegate(this),
					iconCls: 'icon-system-refresh',
					text : lang('refresh')
				},'-',
				{
					text : lang('add'),
					iconCls:'icon-utils-s-add',
					handler : function(){
						var e = new _this.defineddsc({
   							name: ''
   						});
   						_this.editor.stopEditing();
   						_this.definedstore.insert(0, e);
   						_this.definedGrid.getView().refresh();
   						_this.definedGrid.getSelectionModel().selectRow(0);
   						_this.editor.startEditing(0);
					}
				},"-",
				{
					text : lang('del'),
					iconCls: 'icon-utils-s-delete',
					handler : function(button){
						var rows = _this.definedGrid.getSelectionModel().getSelections(); // 返回值为 Record 数组 
						if (rows.length == 0) {
							CNOA.miniMsg.alertShowAt(button, lang('mustSelectOneRow'));
						} else {
							CNOA.miniMsg.cfShowAt(button, "确定要删该信息吗?", function(){
								if (rows) {
									var ids = "";
									for (var i = 0; i < rows.length; i++) {
										ids += rows[i].get("id") + ",";
									}
									
									Ext.Ajax.request({
										url: _this.baseUrl + '&task=deleteDefinedData',
										params: {ids : ids},
										method: "POST",
										success: function(r, opts) {
											var result = Ext.decode(r.responseText);
											if(result.success === true){
												_this.store.reload();
											}else{
												CNOA.msg.alert(result.msg, function(){
													_this.store.reload();
												});
											}
										},
										failure: function(response, opts) {
											CNOA.msg.alert(result.msg, function(){
												_this.store.reload();
											});
										}
									});
								}
							});
						}
					}
				}
			]
		});
				
		this.definedBaseField = [
			{
				xtype: "fieldset",
				title: "选择流程",
				autoHeight: true,
				defaults : {
					border : false,
					style : "margin-bottom : 5px"
				},
				items: [
					{
						xtype : "panel",
						fieldLabel : "步骤信息",
						items : [this.definedGrid]
					}
				]
			}
		];
		
		this.defined = new Ext.form.FormPanel({
			border: false,
			labelWidth: 80,
			labelAlign: 'right',
			region : "center",
			autoScroll: true,
			waitMsgTarget: true,
			items : [
				{
					xtype: "panel",
					border: false,
					layout: "form",
					bodyStyle: "padding:10px;",
					items: this.definedBaseField
				}
			]
		});
		//自定义流程结束
		
		this.mainPanel = new Ext.Window({
			title : "转下一步办理",
			layout : "border",
			resizable : false,
			autoScroll: false,
			width : 660,
			height : 400,
			modal : true,
			layout : "card",
			activeItem: 0,
			items : [this.fixed, this.defined],
			tbar : [
				{
					handler : function(button, event) {
						this.from = "fixed";
						this.mainPanel.getLayout().setActiveItem(0);
					}.createDelegate(this),
					iconCls: 'icon-roduction',
					enableToggle: true,
					pressed: true,
					allowDepress: false,
					toggleGroup: "meeting_room_check",
					tooltip: "显示绑定流程界面",
					text : '绑定已有流程'
				},"-",
				{
					handler : function(button, event) {
						this.from = "defined";
						this.mainPanel.getLayout().setActiveItem(1);
					}.createDelegate(this),
					iconCls: 'icon-roduction',
					enableToggle: true,
					allowDepress: false,
					toggleGroup: "meeting_room_check",
					tooltip: "显示自定义流程界面",
					text : '自定义流程'
				}
			],
			buttons : [
				{
					text : "提交",
					iconCls: 'icon-dialog-apply2',
					handler : function(){
						if(_this.from == "fixed"){
							var choice = Ext.getCmp(ID_CHOICE_VALUE).getValue();
							if(choice != ""){
								_this.move(id, _this.from, choice);
							}else{
								CNOA.msg.alert("您还有选择固定流程,请选择");
							}
						}else if(_this.from == "defined"){
							_this.move(id, _this.from, choice);
						}
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
					CNOA_odoc_send_apply_edit.setOfficeAcitveXDisplay(false);
				},
				close : function(){
					CNOA_odoc_send_apply_edit.setOfficeAcitveXDisplay(true);
				}
			}
		}).show();
	},
	
	move : function(id, from, choice){
		var _this = this;
		
		Ext.Ajax.request({
			url: _this.baseUrl + '&task=moveDefinedData',
			params: {fid : id, from : from, choice : choice},
			method: "POST",
			success: function(r, opts) {
				var result = Ext.decode(r.responseText);
				if(result.success === true){
					CNOA.msg.notice(lang('successopt'), "公文管理");
					try{
						opener.CNOA_odoc_send_apply.store.reload();
					}catch(e){
						
					}
					window.close();
				}
			},
			failure: function(response, opts) {
				CNOA.msg.alert(result.msg);
			}
		});
	},
	
	submit : function(user){
		var _this = this;
		
		Ext.Ajax.request({
			url: _this.baseUrl + '&task=addDefinedFlow',
			params: user,
			method: "POST",
			success: function(r, opts) {
				var result = Ext.decode(r.responseText);
				if(result.success === true){
					_this.definedstore.reload();
				}else{
					
				}
			},
			failure: function(response, opts) {
				CNOA.msg.alert(result.msg);
			}
		});
	},
	
	getStepInfo : function(flowid){
		var _this = this;
		
		Ext.Ajax.request({
			url: _this.baseUrl + '&task=getStepInfo',
			params: {flowid : flowid},
			method: "POST",
			success: function(r, opts) {
				var result = Ext.decode(r.responseText);
				if(result.success === true){
					_this._makeStepList(result.data.stepList);
				}else{
					
				}
			},
			failure: function(response, opts) {
				CNOA.msg.alert(result.msg);
			}
		});
	},

	_makeStepList : function(list){
		this.stepListCt.removeAll();
		this.stepListCt.layoutConfig = {columns: list.length+1};
		for(var i=0;i<list.length;i++){
			this.stepListCt.add({
				xtype: "button",
				enableToggle: true,
				allowDepress: false,
				toggleGroup: "odoc_apply_send_select_step",
				style: "margin-right:3px;",
				text: (i+1) + "." + list[i].stepName
			});
		}
		this.stepListCt.doLayout();
	}
};


CNOA_odoc_send_apply_editClass = CNOA.Class.create();
CNOA_odoc_send_apply_editClass.prototype = {
	init : function(){
		var _this = this;
		
		this.id	= CNOA.odoc.id;
		
		this.baseUrl = "index.php?app=odoc&func=send&action=apply";
		this.baseURI = location.href.substr(0, location.href.lastIndexOf("/")+1);
		
		/*
		this.webofficeHtml  = '<object id="CNOA_WEBOFFICE" height="768" width="100%" style="left: 0px; TOP: 0px" classid="clsid:E77E049B-23FC-4DB8-B756-60529A35FAD5" codebase="resources/weboffice/v6.0.5.0.cab#version=6,0,5,0">';
		this.webofficeHtml += '<param name="_ExtentX" value="6350">';
		this.webofficeHtml += '<param name="_ExtentY" value="6350">';
		this.webofficeHtml += '要显示公文正文内容，请切换到IE浏览器下查看';
		this.webofficeHtml += '</object>';
		*/

		this.webofficeHtml = CNOA.webObject.getWebOffice("100%", 768, 'left: 0px; TOP: 0px');
		
		this.hurryListStore = new Ext.data.Store({
			proxy: new Ext.data.HttpProxy({
				url: _this.baseUrl+"&task=getHurryList"
			}),
			reader: new Ext.data.JsonReader({
				root:'data',
				fields: [
					{name : "tid"},
					{name : "title"}
				]
			})
		});
		
		this.hurryListStore.load();
		
		
		this.baseField = [
			/*{
				xtype: "fieldset",
				title: "发文单",
				anchor: "97%",
				autoHeight: true,
				items: [
					{
						xtype : "textfield",
						width : 500,
						fieldLabel : "公文标题",
						allowBlank : false,
						name : "title",
						value : CNOA.odoc.title
					},
					{
						xtype : "textfield",
						fieldLabel : "文件字号",
						allowBlank : false,
						name : "number",
						width : 188
					},
					{
						xtype: "panel",
						hideBorders: true,
						border: false,
						layout: "table",
						autoWidth: true,
						layoutConfig: {
							columns: 2
						},
						items : [
							{
								xtype : "panel",
								layout : "form",
								items : [
									{
										xtype : "textfield",
										width : 188,
										fieldLabel : "拟稿人",
										readOnly : true,
										name : "createpeople"
									}
								]
							},
							{
								xtype : "panel",
								layout : "form",
								items : [
									{
										xtype : "textfield",
										width : 188,
										fieldLabel : "拟稿单位",
										readOnly : true,
										name : "createdept"
									}
								]
							}
						]
					}
				]
			},*/
			{
				xtype: "fieldset",
				title: "发文单",
				//anchor: "97%",
				html: "{FAWENFORM}"
			},
			{
				xtype: "fieldset",
				title: lang('attach'),
				//anchor: "97%",
				autoHeight: true,
				items: [
					{
						xtype: "flashfile",
						fieldLabel: "添加附件",
						style: "margin-top:5px;",
						inputPre: "filesUpload",
						name : "attach"
					}
				]
			},
			{
				xtype: "fieldset",
				title: "公文正文",
				//anchor: "97%",
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
					bodyStyle: "padding:10px;",
					border: false,
					//layout: "form",
					autoScroll : true,
					region: "center",
					items: this.baseField
				}
			],
			tbar: [
				"发文拟稿： ",
				{
					text: lang('save'),
					iconCls : "icon-btn-save",
					cls: "x-btn-over",
					listeners: {
						"mouseout" : function(btn){
							btn.addClass("x-btn-over");
						}
					},
					handler : function(){
						_this.submitFormData();
					}
				},
				{
					text: "转下一步",
					iconCls : "icon-btn-save",
					cls: "x-btn-over",style: 'margin-left:3px;',
					listeners: {
						"mouseout" : function(btn){
							btn.addClass("x-btn-over");
						}
					},
					handler : function(){
						_this.submitFormData(true);
						//
					}
				},"->",
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
			style: 'background-color:#fff;',
			autoScroll: true,
			items: [this.form]
		});
		
		this.loadFormData();
	},

	loadFormData : function(){
		var _this = this;
		
		this.form.getForm().load({
			url: _this.baseUrl + "&task=loadFormData",
			method:'POST',
			params : {id : _this.id},
			waitTitle: lang('notice'),
			success: function(form, action){

			},
			failure: function(form, action) {
				CNOA.msg.alert(action.result.msg, function(){
					
				});
			}
		});
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
				params: {id : this.id},
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
		CNOA_odoc_send_apply_selectFlow = new CNOA_odoc_send_apply_selectFlowClass(_this.id);
	},
	
	setOfficeAcitveXDisplay : function(showHide){
		if(showHide){
			jQuery('#CNOA_WEBOFFICE').show();
		}else{
			jQuery('#CNOA_WEBOFFICE').hide();
		}
	}
}

Ext.onReady(function() {
	if(!Ext.isIE){
		CNOA.msg.alert("此功能因使用Office控件，需要使用IE浏览器，请使用IE浏览器重新进入本功能。", function(){
		window.close();
		});
	}else{
		CNOA_odoc_send_apply_edit = new CNOA_odoc_send_apply_editClass();
		CNOA_odoc_send_apply_edit.mainPanel.doLayout();
	}
});
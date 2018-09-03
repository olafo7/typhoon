/**
 * 全局变量
 */

var CNOA_odoc_send_list, CNOA_odoc_send_listClass;

CNOA_odoc_send_listClass = CNOA.Class.create();
CNOA_odoc_send_listClass.prototype = {
	init : function(){
		var _this = this;
		
		var ID_SEARCH_TITLE		= Ext.id();
		var ID_SEARCH_NUMBER	= Ext.id();
		var ID_SEARCH_TYPE		= Ext.id();
		var ID_SEARCH_LEVEL		= Ext.id();
		var ID_SEARCH_HURRY		= Ext.id();
		
		
		this.ID_recvUids	= Ext.id();
		
		this.ID_btnSign		= Ext.id();
		this.ID_btnIssue	= Ext.id();
		
		this.baseUrl = "index.php?app=odoc&func=send&action=list";
		
		this.storeBar = {
			storeType : "waiting",
			title : "",
			number : "",
			type : "",
			level : "",
			hurry : "",
			status: 1
		};
		
		this.fields = [
			{name : "id"},
			{name: "title"},
			{name: "number"},
			{name: "type"},
			{name: "level"},
			{name: "hurry"},
			{name: "status"},
			{name: "createname"},
			{name: "createdept"},
			{name: "sign"},
			{name: "regdate"},
			{name: "senddate"},
			{name: 'many'}
		];
		
		this.typeListStore = new Ext.data.Store({
			proxy: new Ext.data.HttpProxy({
				url: _this.baseUrl+"&task=getTypeList"
			}),
			reader: new Ext.data.JsonReader({
				root:'data',
				fields: [
					{name : "tid"},
					{name : "title"}
				]
			})
		});
		
		this.typeListStore.load();
		
		this.levelListStore = new Ext.data.Store({
			proxy: new Ext.data.HttpProxy({
				url: _this.baseUrl+"&task=getLevelList"
			}),
			reader: new Ext.data.JsonReader({
				root:'data',
				fields: [
					{name : "tid"},
					{name : "title"}
				]
			})
		});
		
		this.levelListStore.load();
		
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
		
		this.store = new Ext.data.Store({
			autoLoad : true,
			proxy:new Ext.data.HttpProxy({url: this.baseUrl+"&task=getJsonData", disableCaching: true}),   
			reader:new Ext.data.JsonReader({totalProperty:"total",root:"data", fields: this.fields}),
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
		
		this.pagingBar = new Ext.PagingToolbar({
			displayInfo:true,   
			store: this.store,
			pageSize:15,
			listeners:{
				"beforechange" : function(th, params){
					Ext.apply(params, _this.storeBar);
				}
			}
		});
		
		this.colModel = new Ext.grid.ColumnModel([
			new Ext.grid.RowNumberer(),
			this.sm,
			{header: "id", dataIndex: 'id', hidden: true},
			{header: lang('opt'), dataIndex: 'id', width: 120, sortable: true, menuDisabled :true, renderer:this.makeOperate.createDelegate(this)},
			{header: lang('title'), dataIndex: 'title', width: 150, sortable: true, menuDisabled :true},
			{header: '文号', dataIndex: 'number', width: 100, sortable: true, menuDisabled :true},
			{header: '类型', dataIndex: 'type', width: 100, sortable: true, menuDisabled :true},
			{header: '密级', dataIndex: 'level', width: 80, sortable: true, menuDisabled :true},
			{header: '缓急', dataIndex: 'hurry', width: 80, sortable: true, menuDisabled :true},			
			{header: '份数', dataIndex: 'many', width: 80, sortable: true, menuDisabled :true},
			{header: '拟稿人', dataIndex: 'createname', width: 80, sortable: true, menuDisabled :true},
			{header: '拟稿部门', dataIndex: 'createdept', width: 80, sortable: true, menuDisabled :true},
			//{header: '签发人', dataIndex: 'sign', width: 80, sortable: true, menuDisabled :true},
			//{header: '登记时间', dataIndex: 'regdate', width: 80, sortable: true, menuDisabled :true},
			{header: '发出时间', dataIndex: 'senddate', width: 120, sortable: true, menuDisabled :true},
			{header: '流程状态', dataIndex: 'status', width: 80, sortable: true, menuDisabled :true},
			//{header: '状态', dataIndex: 'status', width: 80, sortable: true, menuDisabled :true},
			{header: "", dataIndex: 'noIndex', width: 1, menuDisabled: true,resizable: false}
		]);
		
		this.grid = new Ext.grid.GridPanel({
			stripeRows : true,
			region : "center",
			border:false,
			store:this.store,
			loadMask : {msg: lang('waiting')},
			cm: this.colModel,
			sm: this.sm,
			hideBorders: true,
			listeners:{
				"rowdblclick":function(button, event){
					
				}
			},
			tbar:[
				{
					text:lang('refresh'),
					iconCls:'icon-system-refresh',
					handler:function(button, event){
						_this.store.reload();
					}
				},"-",
				{
					handler : function(button, event) {
						
						
						//Ext.getCmp(_this.ID_btnIssue).setVisible(true);
						//Ext.getCmp(_this.ID_btnSign).setVisible(true);

						_this.storeBar.status = 1;
						_this.storeBar.storeType = "waiting";
						_this.store.load({params: _this.storeBar});
					}.createDelegate(this),
					iconCls: 'icon-roduction',
					enableToggle: true,
					pressed: true,
					allowDepress: false,
					toggleGroup: "meeting_room_check",
					tooltip: "显示待发送的公文列表",
					text : '待发送'
				},"-",
				{
					handler : function(button, event) {
						
						
						//Ext.getCmp(_this.ID_btnIssue).setVisible(false);
						//Ext.getCmp(_this.ID_btnSign).setVisible(false);
						
						_this.storeBar.status		= 2;
						_this.storeBar.storeType	= "pass";
						_this.store.load({params: _this.storeBar});
						
					}.createDelegate(this),
					iconCls: 'icon-roduction',
					enableToggle: true,
					allowDepress: false,
					toggleGroup: "meeting_room_check",
					tooltip: "显示已发送的公文列表",
					text : '已发送'
				},
				'-',
				{
					text: '分发', //所有人
					handler: function(){
						_this.issueSend();
					}.createDelegate(this),
					iconCls: 'icon-applications-stack',
					cls: 'x-btn-over',
					listeners: {
						'mouseout': function(btn){
							btn.addClass('x-btn-over');
						}
					},
					id: _this.ID_btnIssue
				},
				'-',
				{
					text: '签发', //下级部门 － 阅读
					handler: function(){
						_this.signSend();
						
					}.createDelegate(this),
					iconCls: 'icon-arrow_down',
					cls: 'x-btn-over',
					listeners: {
						'mouseout': function(btn){
							btn.addClass('x-btn-over');
						}
					},
					id: _this.ID_btnSign			
				},
				
				//"<span class='cnoa_color_gray'>双击可修改任务信息，按住ctrl或shift键可选择多个</span>",
				"->",{xtype: 'cnoa_helpBtn', helpid: 4002}
			],
			bbar: _this.pagingBar
		});
		
		this.mainPanel = new Ext.Panel({
			collapsible: false,
			hideBorders: true,
			border: false,
			layout: 'border',
			autoScroll: false,
			items : [this.grid],
			tbar : [
				(lang('title')+':'),
				{
					xtype : "textfield",
					width : 100,
					id : ID_SEARCH_TITLE
				},
				"文号: ",
				{
					xtype : "textfield",
					width : 100,
					id : ID_SEARCH_NUMBER
				},"类型: ",
				new Ext.form.ComboBox({
					name: 'title',
					store: _this.typeListStore,
					width: 100,
					id : ID_SEARCH_TYPE,
					hiddenName: 'tid',
					valueField: 'tid',
					displayField: 'title',
					mode: 'local',
					triggerAction: 'all',
					forceSelection: true,
					editable: false
				}),
				"密级: ",
				new Ext.form.ComboBox({
					name: 'title',
					store: _this.levelListStore,
					width: 100,
					id : ID_SEARCH_LEVEL,
					hiddenName: 'tid',
					valueField: 'tid',
					displayField: 'title',
					mode: 'local',
					triggerAction: 'all',
					forceSelection: true,
					editable: false
				}),
				"缓急: ",
				new Ext.form.ComboBox({
					name: 'title',
					store: _this.hurryListStore,
					width: 100,
					id : ID_SEARCH_HURRY,
					hiddenName: 'tid',
					valueField: 'tid',
					displayField: 'title',
					mode: 'local',
					triggerAction: 'all',
					forceSelection: true,
					editable: false
				}),
				{
					xtype: "button",
					text: lang('search'),
					style: "margin-left:5px",
					cls: "x-btn-over",
					listeners: {
						"mouseout" : function(btn){
							btn.addClass("x-btn-over");
						}
					},
					handler: function(){
						_this.storeBar.title 		= Ext.getCmp(ID_SEARCH_TITLE).getValue();
						_this.storeBar.number 		= Ext.getCmp(ID_SEARCH_NUMBER).getValue();
						_this.storeBar.type 		= Ext.getCmp(ID_SEARCH_TYPE).getValue();
						_this.storeBar.level 		= Ext.getCmp(ID_SEARCH_LEVEL).getValue();
						_this.storeBar.hurry 		= Ext.getCmp(ID_SEARCH_HURRY).getValue();
						
						_this.store.load({params:_this.storeBar});
					}
				},
				{
					xtype:"button",
					text:lang('clear'),
					handler:function(){
						Ext.getCmp(ID_SEARCH_TITLE).setValue("");
						Ext.getCmp(ID_SEARCH_NUMBER).setValue("");
						Ext.getCmp(ID_SEARCH_TYPE).setValue("");
						Ext.getCmp(ID_SEARCH_LEVEL).setValue("");
						Ext.getCmp(ID_SEARCH_HURRY).setValue("");
						
						_this.storeBar.title 		= "";
						_this.storeBar.number 		= "";
						_this.storeBar.type 		= "";
						_this.storeBar.level 		= "";
						_this.storeBar.hurry 		= "";
						
						_this.store.load({params:_this.storeBar});
					}
				}
			]
		});
	},
	
	
	/**
	 * 签发
	 */
	signSend : function(){
		var _this = this;
		
		
		var rows = _this.grid.getSelectionModel().getSelections();
		//cdump(rows);return;
		if(rows.length <= 0){
			CNOA.msg.alert('您好，请至少选择一个发文进行操作.');
			return;
		}
		var slid = rows[0].json.id;
		
		
		var frm = new Ext.form.FormPanel({
			border: false,
			labelWidth: 80,
			labelAlign: 'right',
			bodyStyle:'padding: 10px 0 0 15px',
			defaults:{
				style: 'margin-bottom: 10px'
			},
			items: [
				{
					xtype: 'textfield',
					fieldLabel: '来文单位',
					name: 'dept',
					width: 200,
					allowBlank: false
				},
				{
					xtype: 'textfield',
					fieldLabel: lang('title'),
					name: 'title',
					width: 200,
					allowBlank: false
				},/*
				{
					xtype: 'textfield',
					fieldLabel: '收文类型',
					name: 'type',
					width: 200,
					allowBlank: false
				},*/
				{
					xtype: 'textfield',
					fieldLabel: '打印分数',
					name: 'print',
					width: 200,
					allowBlank: false
				},
				
				
				
				
				{
					xtype: "hidden",
					name: "uids"
				},{
					xtype: 'textarea',
					width: 300,
					height: 160,
					name: 'names',
					allowBlank: false,
					readOnly:true,
					fieldLabel: '接收人员'
				},
				{
					xtype: "btnForPoepleSelector",
					//fieldLabel:lang('selectPeople'),
					text: lang('selectPeople'),
					allowBlank: false,
					name:"asdfasdf",
					style: 'margin:0 0 0 85px',
					dataUrl: _this.baseUrl+"&task=getAllUserListsInPermitDeptTree",
					width: 80,
					listeners: {
						"selected": function(th, data){
							//cdump(data);
							var names = new Array();
							var uids = new Array();
							
							if (data.length>0){
								for (var i=0;i <= data.length - 1; i++){
									names.push(data[i].uname);
									uids.push(data[i].uid);
								}
							}
							var sNames	= names.join(",");
							var sUids	= uids.join(",");
							
							frm.getForm().findField("uids").setValue(sUids);
							frm.getForm().findField("names").setValue(sNames);
						}
					}
				}
				
			]
		})
		
		var loadFromData = function(_this){			
			frm.getForm().load({
				url: _this.baseUrl + "&task=getSignData",
				params: {id: slid},
				method:'POST',
				//waitMsg: lang('waiting'),
				success: function(form, action){
					//cdump(action);
				},
				failure: function(form, action) {
					CNOA.msg.alert(action.result.msg, function(){
					});
				}
			});
		};
		
		var submit = function(_this){						
			var f = frm.getForm();
			if (f.isValid()) {	
				f.submit({
					url: _this.baseUrl + '&task=submitSign',
					method: 'POST',
					params: {id: slid},
					success: function(form, action) {
						CNOA.msg.notice(action.result.msg, "公文管理");
						_this.store.reload();
						win.close();
					},
					failure: function(form, action) {
						CNOA.msg.alert(action.result.msg);
					}
				});
			}else{
				CNOA.msg.alert(lang('formValid'));
			}
		};
		
		var win = new Ext.Window({
			title: '签发',
			width: 460,
			height: 400,
			layout: 'fit',
			modal: true,
			resizable: false,
			maximizable: false,
			items: [frm],
			buttons:[
				{
					text: '确定签发',
					handler: function(){
						submit(_this);
					}
				},{
					text: lang('close'),
					handler: function (){						
						win.close();
					}
				}
			]
		}).show();
		
		loadFromData(_this);
		
		
		return win;
	},
	
	/**
	 * 分发
	 */
	issueSend : function(){
		var _this = this;
		
		
		var rows = _this.grid.getSelectionModel().getSelections();
		//cdump(rows);return;
		if(rows.length <= 0){
			CNOA.msg.alert('您好，请至少选择一个发文进行操作.');
			return;
		}
		var slid = rows[0].json.id;
			
			
		
		var frm = new Ext.form.FormPanel({
			border: false,
			labelWidth: 50,
			labelAlign: 'right',
			bodyStyle:'padding: 10px 0 0 15px',
			defaults:{
				style: 'margin-bottom: 10px'
			},
			items: [
				{
					xtype: 'textarea',
					fieldLabel: '收文人',
					name: 'recvMan',
					width: 300,
					height: 150,
					allowBlank: false
				},
				{
					xtype: "hidden",
					name: "recvUids",
					id: _this.ID_recvUids
				},
				{
					xtype: "panel",
					hidden: false,
					layout: "form",
					border: false,
					items: [
						{
							xtype: "btnForPoepleSelector",
							fieldLabel:"",
							text: lang('select'),
							allowBlank: false,
							name:"checkName",
							dataUrl: _this.baseUrl+"&task=getAllUserListsInPermitDeptTree",
							width: 70,
							style: 'margin: 0 0 0 55px',
							listeners: {
								"selected": function(th, data){
									//cdump(data); return;
									var names = new Array();
									var uids = new Array();
									
									if (data.length>0){
										for (var i=0;i <= data.length - 1; i++){
											names.push(data[i].uname);
											uids.push(data[i].uid);
										}
									}
									var sNames	= names.join(",");
									var sUids	= uids.join(",");
									
									Ext.getCmp(_this.ID_recvUids).setValue(sUids);
									frm.getForm().findField("recvMan").setValue(sNames);
								}
							}
						}
					]
				}
				
			]
		})
		
		
		submit = function(_this){						
			var f = frm.getForm();
			if (f.isValid()) {	
				f.submit({
					url: _this.baseUrl + '&task=submitIssue',
					//waitTitle: lang('notice'),
					method: 'POST',
					//waitMsg: lang('waiting'),
					params: {id: slid},
					success: function(form, action) {
						CNOA.msg.notice(action.result.msg, "公文管理");
						_this.store.reload();
						win.close();
					},
					failure: function(form, action) {
						CNOA.msg.alert(action.result.msg);
					}
				});
			}else{
				CNOA.msg.alert(lang('formValid'));
				//CNOA.miniMsg.alertShowAt(ID_btn_save, lang('formValid'));
			}
		};
		
		var win = new Ext.Window({
			title: '分发',
			width: 400,
			height: 300,
			layout: 'fit',
			modal: true,
			resizable: false,
			maximizable: false,
			items: [frm],
			buttons:[
				{
					text: '确定分发',
					handler: function(){
						submit(_this);
					}
				},{
					text: lang('close'),
					handler: function (){						
						win.close();
					}
				}
			]
		}).show();
		
		
		return win;
	},
	
	
	
	readProgress: function(id){
		var _this = this;
		
		
		var fields = [
			{name : "id"},
			{name: "dept"},
			{name: "name"},
			{name: "time"}
		];
		
		var store = new Ext.data.Store({
			proxy:new Ext.data.HttpProxy({url: _this.baseUrl+"&task=getSendReadList", disableCaching: true}),   
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
		
		store.setBaseParam('id', id);
		store.load();
		
		var sm = new Ext.grid.CheckboxSelectionModel({
			singleSelect: false
		});
		
		
		
		var colModel = new Ext.grid.ColumnModel([
			new Ext.grid.RowNumberer(),
			//sm,
			{header: "id", dataIndex: 'id', hidden: true},
			{header: lang('inDepartment'), dataIndex: 'dept', width: 200, sortable: true, menuDisabled :true},
			{header: lang('truename'), dataIndex: 'name', width: 111, sortable: true, menuDisabled :true},
			{header: '阅读时间', dataIndex: 'time', width: 130, sortable: true, menuDisabled :true},
			{header: "", dataIndex: 'noIndex', width: 1, menuDisabled: true,resizable: false, hidden: true}
		]);
		
		var grid = new Ext.grid.GridPanel({
			stripeRows : true,
			border:false,
			store:store,
			loadMask : {msg: lang('waiting')},
			cm: colModel,
			sm: sm,
			listeners:{
				"rowdblclick":function(button, event){
					
				}
			}/*,
			tbar: new Ext.Toolbar({
				items:[
					{
						xtype: 'button',
						iconCls:'icon-system-refresh',
						text: lang('refresh'),
						handler: function(){
							store.reload();
						}
					}
				]
			})*/
		});
		
		
		var win = new Ext.Window({
			width: 600,
			height: 350,
			modal: true,
			layout: 'fit',
			items: grid,
			buttons:[
				{
					iconCls:'icon-system-refresh',
						text: lang('refresh'),
						handler: function(){
							store.reload();
						}
				},
				{
					text: lang('close'),
					handler: function(){
						win.close();
					}
				}
			]
		}).show();
		
		
		return win;
	},
	
	
	makeOperate : function(value, cellmeta, record, rowIndex, columnIndex, color_store){
		var s = '';
		var _this = this;
		
				
		s = "<a href='" + _this.baseUrl + "&task=view&act=getHtml&id=" + value + "' target='CNOA_ODOC_SEND_CHEDK' style=''>查看公文</a>";
		
		if(parseInt(_this.storeBar.status) == 2){
			s += " / ";
			s += "<a href='javascript:void(0)' onclick='CNOA_odoc_send_list.readProgress(" + value + ");' target='CNOA_ODOC_SEND_CHEDK'>阅读情况</a>";
		}
		
		
		return s;
	},
	
	viewOdoc : function(id){
		var _this = this;
		
		x = (screen.availWidth - 850) / 2;
		y = (screen.availHeight - 600) / 2;
		window.open(_this.baseUrl + '&task=view&act=getHtml&id=' + id, 'editOdoc', 'width=890,height=500,left=' + x +',top=' + y + ',scrollbars=yes,resizable=yes,status=no');
	}
}

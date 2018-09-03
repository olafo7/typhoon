/**
 * 全局变量
 */

var CNOA_odoc_receive_list, CNOA_odoc_receive_listClass;

CNOA_odoc_receive_listClass = CNOA.Class.create();
CNOA_odoc_receive_listClass.prototype = {
	init : function(){
		var _this = this;
		
		var ID_SEARCH_TITLE		= Ext.id();
		var ID_SEARCH_NUMBER	= Ext.id();
		var ID_SEARCH_TYPE		= Ext.id();
		var ID_SEARCH_LEVEL		= Ext.id();
		var ID_SEARCH_HURRY		= Ext.id();
		var ID_BTN_SPLIT		= Ext.id();
		this.ID_BTN_SAVE		= Ext.id();
		
		this.baseUrl = "index.php?app=odoc&func=receive&action=list";
		
		this.storeBar = {
			storeType : "waiting",
			title : "",
			number : "",
			type : "",
			level : "",
			hurry : ""
		};
		
		this.fields = [
			{name : "id"},
			{name : "title"},
			{name : "number"},
			{name : "type"},
			{name : "level"},
			{name : "hurry"},
			{name : "status"},
			{name : "many"},
			{name : "page"},
			{name : "fromdept"},
			{name : "createname"},
			{name : "createtime"},
			{name : "receivedate"}
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
			singleSelect: true
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
			{header: lang('opt'), dataIndex: 'id', width: 120, sortable: true, menuDisabled :true, renderer : this.operate.createDelegate(this)},
			{header: lang('title'), dataIndex: 'title', width: 150, sortable: true, menuDisabled :true},
			{header: '文号', dataIndex: 'number', width: 100, sortable: true, menuDisabled :true},
			{header: '类型', dataIndex: 'type', width: 100, sortable: true, menuDisabled :true},
			{header: '密级', dataIndex: 'level', width: 80, sortable: true, menuDisabled :true},
			{header: '缓急', dataIndex: 'hurry', width: 80, sortable: true, menuDisabled :true},
			{header: '份数', dataIndex: 'many', width: 80, sortable: true, menuDisabled :true},
			{header: '页数', dataIndex: 'page', width: 80, sortable: true, menuDisabled :true},
			{header: '发文单位', dataIndex: 'fromdept', width: 80, sortable: true, menuDisabled :true},
			{header: '收文人', dataIndex: 'createname', width: 80, sortable: true, menuDisabled :true},
			{header: '登记时间', dataIndex: 'createtime', width: 120, sortable: true, menuDisabled :true},
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
						_this.storeBar.storeType = "all";
						_this.store.load({params: _this.storeBar});
						Ext.getCmp(ID_BTN_SPLIT).show();
					}.createDelegate(this),
					iconCls: 'icon-roduction',
					enableToggle: true,
					pressed: true,
					allowDepress: false,
					toggleGroup: "meeting_room_check",
					tooltip: "显示所有的收文公文列表",
					text : '收文列表'
				},"-",
				{
					handler : function(button, event) {
						_this.storeBar.storeType = "history";
						_this.store.load({params: _this.storeBar});
						Ext.getCmp(ID_BTN_SPLIT).hide();
					}.createDelegate(this),
					iconCls: 'icon-roduction',
					enableToggle: true,
					allowDepress: false,
					toggleGroup: "meeting_room_check",
					tooltip: "显示已分发记录的列表",
					text : '已分发记录'
				},"-",
				{
					handler : function(button, event) {
						var rows = _this.grid.getSelectionModel().getSelections(); // 返回值为 Record 数组 
						if (rows.length == 0) {
							CNOA.miniMsg.alertShowAt(button, "您还没有选择要分发的文件");
						} else {
							CNOA.miniMsg.cfShowAt(button, "确定要分发该文件吗?", function(){						
								_this.newSplit(rows[0].get("id"));
							});
						}
					}.createDelegate(this),
					id : ID_BTN_SPLIT,
					iconCls: 'icon-applications-stack',
					tooltip: "分发文件",
					text : '分发'
				},
				"->",{xtype: 'cnoa_helpBtn', helpid: 4002}
			],
			bbar: this.pagingBar
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
	
	operate : function(value, cellmeta, record, rowIndex, columnIndex, color_store){
		var _this = this;
		
		if(_this.storeBar.storeType == "history"){
			return "<a href='index.php?app=odoc&func=receive&action=list&task=view&act=getHtml&id="+value+"' target='viewodoc'>查看公文</a> / <a href='javascript:void(0)' onclick='CNOA_odoc_receive_list.read("+value+", \"view\")'>阅读情况</a>";
		}else{
			return "<a href='index.php?app=odoc&func=receive&action=list&task=view&act=getHtml&id="+value+"' target='viewodoc'>查看公文</a>";
		}
	},
	
	view : function(value){
		
	},
	
	read : function(id){
		var _this = this;
		
		var fields = [
			{name:"deptname"},
			{name:"name"},
			{name:"readtime"}
		];
		
		var store = new Ext.data.Store({
			proxy:new Ext.data.HttpProxy({url: _this.baseUrl+"&task=getReaderList&id="+id, disableCaching: true}),
			reader:new Ext.data.JsonReader({totalProperty:"total",root:"data", fields: fields})
		});
		
		store.load();
		
		var sm = new Ext.grid.CheckboxSelectionModel({
			singleSelect:false
		});
		
		var colModel = new Ext.grid.ColumnModel([
			new Ext.grid.RowNumberer(),
			{header: "所属部门", dataIndex: 'deptname', width: 100, sortable: true, menuDisabled :true},
			{header: "名字", dataIndex: 'name', width: 100, sortable: false,menuDisabled :true},
			{header: "阅读日期", dataIndex: 'readtime', width: 150, sortable: true, menuDisabled :true},
			{header: "", dataIndex: 'noIndex', width: 1, menuDisabled: true,resizable: false}
		]);
		
		var Grid = new Ext.grid.GridPanel({
			stripeRows : true,
			store : store,
			region : "center",
			layout : "fit",
			loadMask : {msg: lang('waiting')},
			cm : colModel,
			sm : sm,
			hideBorders : true,
			border : false,
			viewConfig : {
				forceFit : true
			}
		});
		
		var win = new Ext.Window({
			title : "阅读情况",
			layout : "border",
			width : 600,
			height : makeWindowHeight(500),
			resizable : false,
			modal : true,
			items : [Grid],
			buttons : [
				{
					text : lang('close'),
					handler : function(){
						win.close();
					}
				}
			]
		}).show();
	},
	
	newSplit : function(ids, from){
		var _this = this;
		
		var loadFormData = function(ids){
			form.getForm().load({
				url: _this.baseUrl + "&task=loadFormData",
				method:'POST',
				params : {id : ids},
				waitTitle: lang('notice'),
				success: function(form, action){
					
				},
				failure: function(form, action) {
					CNOA.msg.alert(action.result.msg, function(){
						
					});
				}
			})
		};
		
		var submit = function(ids){
			if (form.getForm().isValid()) {
				form.getForm().submit({
					url: _this.baseUrl + "&task=sendFile",
					waitTitle: lang('notice'),
					params : {id : ids},
					method: 'POST',
					waitMsg: lang('waiting'),
					success: function(form, action) {
						CNOA.msg.notice(action.result.msg, "公文管理");
						win.close();
					},
					failure: function(form, action) {
						CNOA.msg.alert(action.result.msg, function(){
							
						});
					}.createDelegate(this)
				});
			}
		};
		
		var baseField = [
			{
				xtype: "fieldset",
				title: "分发文件",
				autoHeight: true,
				defaults : {
					border : false,
					style : "margin-bottom : 5px"
				},
				items: [
					{
						xtype : "panel",
						layout : "table",
						defaults : {
							border : false
						},
						colunm : 2,
						items : [
							{
								xtype : "panel",
								layout : "form",
								items : [
									{
										xtype : "datefield",
										name : "stime",
										fieldLabel : lang('startTime'),
										width : 150,
										format : "Y-m-d",
										allowBlank : false
									}
								]
							},
							{
								xtype : "panel",
								layout : "form",
								items : [
									{
										xtype : "datefield",
										name : "etime",
										fieldLabel : lang('endTime'),
										width : 150,
										format : "Y-m-d"
									}
								]
							}
						]
					},
					new Ext.BoxComponent({
						autoEl: {
							tag: 'div',
							style: "margin-left:80px;margin-top:5px;margin-bottom:5px;color:#676767;",
							html: '注意:如果不填写结束时间则表示阅读该文件没有时间限制'
						}
					}),
					{
						xtype: "textarea",
						readOnly: true,
						width:365,
						height: 80,
						readOnly: true,
						fieldLabel: '分发给',
						name: "split",
						allowBlank : false
					},
					{
						xtype: "hidden",
						name: "splituid"
					},
					{
						xtype: "btnForPoepleSelector",
						text: "选择",
						dataUrl: _this.baseUrl + "&task=getAllUserListsInPermitDeptTree",
						style: "margin-left:65px; margin-bottom:5px",
						listeners: {
							"selected" : function(th, data){
								var names = new Array();
								var uids = new Array();
								if (data.length>0){
									for (var i=0;i<data.length;i++){
										names.push(data[i].uname);
										uids.push(data[i].uid);
									}
								}
								form.getForm().findField("split").setValue(names.join(", "));
								form.getForm().findField("splituid").setValue(uids.join(","));
							},
							"onrender" : function(th){
								th.setSelectedUids(form.getForm().findField("splituid").getValue().split(","));
							}
						}
					}
				]
			}
		];
		
		var form = new Ext.form.FormPanel({
			border: false,
			labelWidth: 60,
			labelAlign: 'right',
			region : "center",
			layout : "fit",
			waitMsgTarget: true,
			items:[
				{
					xtype: "panel",
					border: false,
					bodyStyle: "padding:10px",
					layout: "form",
					region: "center",
					autoScroll: true,
					items: baseField
				}
			]
		});
		
		var win = new Ext.Window({
			title : "分发窗口",
			layout : "border",
			width : 520,
			height : 300,
			resizable : false,
			modal : true,
			items : [form],
			buttons : [
				{
					text : lang('save'),
					iconCls : "icon-btn-save",
					id : _this.ID_BTN_SAVE,
					handler : function(){
						submit(ids);
					}
				},
				{
					text : lang('close'),
					handler : function(){
						win.close();
					}
				}
			]
		}).show();
		
		loadFormData(ids);
	}
}


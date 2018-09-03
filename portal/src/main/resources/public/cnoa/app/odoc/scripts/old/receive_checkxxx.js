/**
 * 全局变量
 */

var CNOA_odoc_receive_check, CNOA_odoc_receive_checkClass;

CNOA_odoc_receive_checkClass = CNOA.Class.create();
CNOA_odoc_receive_checkClass.prototype = {
	init : function(){
		var _this = this;
		
		var ID_SEARCH_TITLE		= Ext.id();
		var ID_SEARCH_NUMBER	= Ext.id();
		var ID_SEARCH_TYPE		= Ext.id();
		var ID_SEARCH_LEVEL		= Ext.id();
		var ID_SEARCH_HURRY		= Ext.id();
		var ID_BTN_AGREE		= Ext.id();
		var ID_BTN_DISAGREE		= Ext.id();
		
		this.baseUrl = "index.php?app=odoc&func=receive&action=check";
		
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
			{name : "fromId"},
			{name : "stepname"},
			{name : "createtime"}
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
			{header: lang('opt'), dataIndex: 'id', width: 120, sortable: true, menuDisabled :true, renderer : this.operate.createDelegate(this)},
			{header: lang('title'), dataIndex: 'title', width: 150, sortable: true, menuDisabled :true},
			{header: '文号', dataIndex: 'number', width: 100, sortable: true, menuDisabled :true},
			{header: '类型', dataIndex: 'type', width: 100, sortable: true, menuDisabled :true},
			{header: '密级', dataIndex: 'level', width: 80, sortable: true, menuDisabled :true},
			{header: '缓急', dataIndex: 'hurry', width: 80, sortable: true, menuDisabled :true},
			{header: '创建时间', dataIndex: 'createtime', width: 120, sortable: true, menuDisabled :true},
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
						_this.storeBar.storeType = "waiting";
						_this.store.load({params: _this.storeBar});
					}.createDelegate(this),
					iconCls: 'icon-roduction',
					enableToggle: true,
					pressed: true,
					allowDepress: false,
					toggleGroup: "meeting_room_check",
					tooltip: "显示待审批的公文列表",
					text : '待审批'
				},"-",
				{
					handler : function(button, event) {
						_this.storeBar.storeType = "check";
						_this.store.load({params: _this.storeBar});
					}.createDelegate(this),
					iconCls: 'icon-roduction',
					enableToggle: true,
					allowDepress: false,
					toggleGroup: "meeting_room_check",
					tooltip: "显示已审批的公文列表",
					text : '已审批'
				},/*"-",
				{
					handler : function(button, event) {
						var rows = _this.grid.getSelectionModel().getSelections(); // 返回值为 Record 数组 
						if (rows.length == 0) {
							CNOA.miniMsg.alertShowAt(button, "您还没有选择信息");
						} else {
							CNOA.miniMsg.cfShowAt(button, "确定要同意该收文审批吗?", function(){
								if (rows) {
									var ids = "";
									for (var i = 0; i < rows.length; i++) {
										ids += rows[i].get("id") + ",";
									}
									_this.submit(ids, "agree");
								}
							});
						}
					}.createDelegate(this),
					id : ID_BTN_AGREE,
					iconCls: 'icon-dialog-ok-apply',
					text : "同意"
				},"-",
				{
					handler : function(button, event) {
						var rows = _this.grid.getSelectionModel().getSelections(); // 返回值为 Record 数组 
						if (rows.length == 0) {
							CNOA.miniMsg.alertShowAt(button, "您还没有选择信息");
						} else {
							CNOA.miniMsg.cfShowAt(button, "确定要不同意该收文审批吗?", function(){
								if (rows) {
									var ids = "";
									for (var i = 0; i < rows.length; i++) {
										ids += rows[i].get("id") + ",";
									}
									_this.submit(ids, "disagree");
								}
							});
						}
					}.createDelegate(this),
					id : ID_BTN_DISAGREE,
					iconCls: 'icon-order-s-close',
					text : "不同意"
				},*/
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
	
	operate : function(value, c, record){
		var _this = this;
		var rd = record.data;
		if(_this.storeBar.storeType == "waiting"){
			return "<a href='javascript:void(0)' onclick='CNOA_odoc_receive_check.manage("+value+")'><b class='cnoa_color_red'>"+rd.stepname+"</b></a>";
		}else if(_this.storeBar.storeType = "check"){
			return "<a href='index.php?app=odoc&func=receive&action=list&task=view&act=getHtml&id="+rd.fromId+"' target='viewodoc'><b >查看</b></a>";
		}
	},
	
	manage : function(id){
		var _this = this;
		
		x = (screen.availWidth - 850) / 2;
		y = (screen.availHeight - 600) / 2;
		window.open(_this.baseUrl+"&task=checkOdocTemplate&id="+id,'checkOdoc','width=890,height=600,left='+x+',top='+y+',scrollbars=yes,resizable=yes,status=no');
	},
	
	submit : function(ids, type){
		var _this = this;
		
		Ext.Ajax.request({
			url: _this.baseUrl + '&task=submit',
			params: {ids : ids, from : type},
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
}



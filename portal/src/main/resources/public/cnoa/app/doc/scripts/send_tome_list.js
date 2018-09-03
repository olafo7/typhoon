//定义全局变量：
var CNOA_doc_send_tome_listClass, CNOA_doc_send_tome_list;


/**
* 主面板-列表
*
*/
CNOA_doc_send_tome_listClass = CNOA.Class.create();
CNOA_doc_send_tome_listClass.prototype = {
	init : function(){
		var _this = this;
		
		this.baseUrl = "index.php?app=doc&func=send&action=tome";

		this.ID_btn_edit = Ext.id();
		this.ID_btn_delete = Ext.id();
		
		this.searchParams = {
			isread: 0
		};
		
		this.fields = [
			{name:"lid"},
			{name:"ulid"},
			{name:"name"},
			{name:"title"},
			{name:"level"},
			{name:"uid"},
			{name:"uname"},
			{name:"step"},
			{name:"posttime"},
			{name:"allowOperate"},
			{name:"status"}
		];
		this.store = new Ext.data.Store({
			proxy:new Ext.data.HttpProxy({url: this.baseUrl+"&task=getList", disableCaching: true}),   
			reader:new Ext.data.JsonReader({totalProperty:"total",root:"data", fields: this.fields})		
		});
		this.sm = new Ext.grid.CheckboxSelectionModel({
			singleSelect:false
		});
		this.store.load();
		this.colModel = new Ext.grid.ColumnModel([
			new Ext.grid.RowNumberer(),
			{header: "ulid", dataIndex: 'ulid', hidden: true},
			{header: lang('flowNumber'), dataIndex: 'name', width: 160, sortable: true, menuDisabled :true},
			{header: lang('title'), dataIndex: 'title', width: 200, sortable: true, menuDisabled :true},
			{header: lang('initiator'), dataIndex: 'uname', width: 120, sortable: true, menuDisabled :true},
			{header: lang('importantGrade'), dataIndex: 'level', width: 60, sortable: false,menuDisabled :true},
			{header: lang('currentStep'), dataIndex: 'step', width: 70, sortable: false,menuDisabled :true},
			{header: lang('status'), dataIndex: 'status', width: 50, sortable: false,menuDisabled :true, renderer:this.makeStatus.createDelegate(this)},
			{header: lang('launchingDate'), dataIndex: 'posttime', width: 110, sortable: false,menuDisabled :true},
			{header: lang('opt'), dataIndex: 'ulid', width: 160, sortable: false,menuDisabled :true, renderer:this.makeOperate.createDelegate(this)},
			{header: "", dataIndex: 'noIndex', width: 1, menuDisabled: true,resizable: false}
		]);
		this.pagingBar = new Ext.PagingToolbar({
			displayInfo:true,
			store: this.store,
			pageSize:15,
			listeners: {
				"beforechange" : function(th, params){					
					//查询参数
					params.isread = _this.searchParams.isread;
				}
			}
		});
		this.grid = new Ext.grid.GridPanel({
			store: this.store,
			loadMask : {msg: lang('waiting')},
			cm: this.colModel,
			hideBorders: true,
			border: false,
			region: "center",
			tbar : new Ext.Toolbar({
				items: [
					{
						handler : function(button, event) {
							_this.store.reload();
						}.createDelegate(this),
						iconCls: 'icon-system-refresh',
						text : lang('refresh')
					},
					{
						text: lang('noReading'),
						enableToggle: true,
						allowDepress: false,
						pressed: true,
						toggleGroup: "doc_send_tome_list",
						handler:function(){
							_this.searchParams.isread = 0;
							_this.store.load({params: {isread: 0}});
						}
					},
					{
						text: lang('flowReaded'),
						enableToggle: true,
						allowDepress: false,
						toggleGroup: "doc_send_tome_list",
						handler:function(){
							_this.searchParams.isread = 1;
							_this.store.load({params: {isread: 1}});
						}
					},"->",{xtype: 'cnoa_helpBtn', helpid: 57}
				]
			}),
			bbar: this.pagingBar
		});

		this.centerPanel = new Ext.Panel({
			region: "center",
			layout: "card",
			activeItem: 0,
			items: [this.grid]
		});

		this.mainPanel = new Ext.Panel({
			collapsible:false,
			hideBorders: true,
			border: false,
			layout:'border',
			autoScroll: false,
			items: [this.centerPanel]//, this.bottomPanel
		});
	},
	
	makeOperate : function(value, cellmeta, record, rowIndex, columnIndex, color_store){
		var rd = record.data;
		var rs = rd.status;

		var h  = "";
		
			h += "<a href='javascript:void(0);' onclick='CNOA_doc_api_flow.showFlow("+value+", 1);' ext:qtip='" + lang('viewDetail') + "'>" + lang('view') + "</a>";
		return h;
	},
	
	makeStatus : function(v){
		var h = "";
			 if(v == 0){h = "<span class='cnoa_color_gray'>" + lang('unsent') + "</span>";}
		else if(v == 1){h = "<span class='cnoa_color_orange'>" + lang('checkIn') + "</span>";}
		else if(v == 2){h = "<span class='cnoa_color_green'>" + lang('yibanli') + "</span>";}
		else if(v == 3){h = "<span class='cnoa_color_red'>" + lang('retirePieces') + "</span> ";}
		else if(v == 4){h = "<span class='cnoa_color_gray'>" + lang('undone') + "</span>";}
		return h;
	}
}

Ext.onReady(function() {
	CNOA_doc_send_tome_list = new CNOA_doc_send_tome_listClass();
	Ext.getCmp(CNOA.doc.send.tome_list.parentID).add(CNOA_doc_send_tome_list.mainPanel);
	Ext.getCmp(CNOA.doc.send.tome_list.parentID).doLayout();
});
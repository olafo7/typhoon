var CNOA_doc_send_apply_newFlowListClass, CNOA_doc_send_apply_newFlowList;

/**
* 主面板-列表
*
*/
CNOA_doc_send_apply_newFlowListClass = CNOA.Class.create();
CNOA_doc_send_apply_newFlowListClass.prototype = {
	init : function(){
		var _this = this;
		
		this.baseUrl = "index.php?app=doc&func=send&action=apply";

		//ID-ID_saveStatusBar
		//this.ID_saveStatusBar = Ext.id();
		this.ID_btn_edit = Ext.id();
		this.ID_btn_delete = Ext.id();
		this.ID_tree_treeRoot = Ext.id();

		this.fields = [
			{name:"lid"},
			{name:"name"},
			{name:"sname"},
			{name:"formid"}
		];
		this.store = new Ext.data.Store({
			proxy:new Ext.data.HttpProxy({url: this.baseUrl+"&task=getNewFlowList", disableCaching: true}),   
			reader:new Ext.data.JsonReader({totalProperty:"total",root:"data", fields: this.fields})		
		});
		this.sm = new Ext.grid.CheckboxSelectionModel({
			singleSelect:false
		});
		this.store.load();
		this.colModel = new Ext.grid.ColumnModel([
			new Ext.grid.RowNumberer(),
			{header: "lid", dataIndex: 'lid', hidden: true},
			{header: lang('flowName'), dataIndex: 'name', width: 140, sortable: true, menuDisabled :true},
			{header: lang('sort2'), dataIndex: 'sname', width: 70, sortable: false,menuDisabled :true},
			{header: lang('opt'), dataIndex: 'lid', width: 70, sortable: false,menuDisabled :true, renderer:this.makeOperate.createDelegate(this)},
			{header: "", dataIndex: 'noIndex', width: 1, menuDisabled: true,resizable: false}
		]);
		this.pagingBar = new Ext.PagingToolbar({
			displayInfo:true,
			store: this.store,
			pageSize:15,
			listeners: {

			}
		});
		this.grid = new Ext.grid.GridPanel({
			store: this.store,
			loadMask : {msg: lang('waiting')},
			cm: this.colModel,
			hideBorders: true,
			border: false,
			region: "center",
			viewConfig: {
				forceFit: true
			},
			tbar : new Ext.Toolbar({
				items: [
					{
						iconCls: 'icon-system-refresh',
						text : lang('refresh'),
						handler : function(button, event) {
							_this.store.reload();
						}.createDelegate(this)
					},lang('useOfficialDocument'),
					"->",{xtype: 'cnoa_helpBtn', helpid: 16}
				]
			}),
			bbar: this.pagingBar
		});

		this.mainPanel = new Ext.Panel({
			collapsible:false,
			hideBorders: true,
			border: false,
			layout:'border',
			autoScroll: false,
			items: [this.grid]//, this.bottomPanel
		});
	},
	
	makeOperate : function(value, cellmeta, record, rowIndex, columnIndex, color_store){
		var rd = record.data;
		
		var src = "./resources/images/icons/";
		var h  = "<div style='margin-top:2px;'><a href='javascript:void(0);' onclick='CNOA_doc_send_apply_newFlowList.newFlow("+value+");'><img src='"+src+"page_addedit.png' style='margin:0 2px 4px 0' align='absmiddle' />"+lang('new')+"</a>";
			h += "</div>";
		return h;
	},
	
	//新建流程
	newFlow : function(lid){
		mainPanel.closeTab("CNOA_MENU_FLOW_USER_NEWFLOW");
		mainPanel.loadClass("index.php?app=flow&func=flow&action=user&task=loadPage&from=newflow&ac=add&lid="+lid+"&target=doc", "CNOA_MENU_FLOW_USER_NEWFLOW", lang('newWorkFlow'), "icon-flow-new");
	}
}

Ext.onReady(function() {
	CNOA_doc_send_apply_newFlowList = new CNOA_doc_send_apply_newFlowListClass();
	Ext.getCmp(CNOA.doc.send.apply_newFlowList.parentID).add(CNOA_doc_send_apply_newFlowList.mainPanel);
	Ext.getCmp(CNOA.doc.send.apply_newFlowList.parentID).doLayout();
});
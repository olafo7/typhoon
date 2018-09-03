var DESKTOPAPP_CLASS = CNOA.Class.create();
DESKTOPAPP_CLASS.prototype = {
	init : function(portalsID){
		var _this = this;

		this.baseUrl = "index.php?app=wf&func=flow&action=use&modul=todo&task=getDeskTopJsonData";
		
		this.id = "CNOA_MAIN_DESKTOP_WF_SORT_{SORTID}";
		if(portalsID) this.id += portalsID;
		var fieldsInbox = [
			{name:"sortId"},
			{name:"flowNumber"},

			{name:"uFlowId"},
			{name:"flowId"},
			{name:"flowName"},
			{name:"tplSort"},
			{name:"flowType"},
			{name:"uStepId"}
		];
		this.store = new Ext.data.Store({
			proxy:new Ext.data.HttpProxy({url: this.baseUrl + "&sortId={SORTID}", disableCaching: true}),   
			reader:new Ext.data.JsonReader({totalProperty:"total",root:"data", fields: fieldsInbox}),
			listeners:{
				exception : function(th, type, action, opt, response, arg){
					try{_this.checkPermit(opt.reader.jsonData.noPermit);}catch(e){}
				},
				load : function(){
					_this.mainPanel.show();
				}
			}
		});
		this.store.load();
		
		colModel = new Ext.grid.ColumnModel([
			{header: "sortId", dataIndex: 'sortId', hidden: true},
			{header: lang('flowNumber'), dataIndex: 'flowNumber', id: "flowNumber", width: 75, sortable: false,menuDisabled :true}
		]);
		
		this.grid = new Ext.grid.GridPanel({
			bodyStyle: 'border-left-width:1px;',
			store: this.store,
			loadMask : {msg: lang('waiting')},
			cm: colModel,
			hideBorders: true,
			border: false,
			autoWidth: true,
			autoExpandColumn: 'flowNumber',
			listeners:{  
				//单击  
				"rowclick" : function(grid, rowIndex, columnIndex){
					var record = grid.getStore().getAt(rowIndex);
					_this.view(record);
				},
				"render" : function(grid){
					//grid.getEl().child(".x-grid3").setStyle("backgroundColor", "#F6F6F6");
				}
			}
		});

		var tools = [], draggable = false;
		tools.push({
			id:'refresh',
			handler: function(){
				_this.store.reload();
			}
		});
		if((CNOA_LOCK_INDEX_LAYOUT == 0) || (CNOA_USER_JOBTYPE == "superAdmin")){
			draggable = true;
			tools.push({
				id:'close',
				handler: function(e, target, panel){
					panel.ownerCt.remove(panel, true);

					_this.closeAction();
				}
			});
		};

		this.mainPanel = new Ext.ux.Portlet({
			id: this.id,
			title: lang('toDo')+ ": {SORTNAME}",
			layout: 'fit',
			height: 250,
			draggable: draggable,
			tools: tools,
			items: [
				this.grid
			]
		});
	},

	checkPermit : function(noPermit){
		var p = this.mainPanel;
		if(noPermit == true){
			p.ownerCt.remove(p, true);
		}else{
			p.show();
		}
	},

	view : function(record){
		var rd = record.data;

		mainPanel.closeTab("CNOA_MENU_WF_USE_OPENFLOW");
		mainPanel.loadClass("index.php?app=wf&func=flow&action=use&modul=todo&task=loadPage&from=dealflow&uFlowId="+rd.uFlowId+"&flowId="+rd.flowId+"&step="+rd.uStepId+"&flowType="+rd.flowType+"&tplSort="+rd.tplSort, "CNOA_MENU_WF_USE_OPENFLOW", "办理工作流程", "icon-flow");
	},

	closeAction : function(){
		var _this = this;
		CNOA_main_common_index.closeDesktopApp(_this.id);
	}
}

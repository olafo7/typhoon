var DESKTOPAPP_CLASS = CNOA.Class.create();
DESKTOPAPP_CLASS.prototype = {
	init : function(portalsID){
		var _this = this;

		this.baseUrl = "index.php?app=wf&func=flow&action=use&modul=view";
		
		this.id = "CNOA_MAIN_DESKTOP_WF_READ";
		if(portalsID) this.id += portalsID;
		var fieldsInbox = [
			{name: "uFlowId"},
			{name: "flowId"},
			{name: "flowNumber"},
			{name: "flowName"},
			{name: "flowType"},
			{name: "tplSort"},
			{name: "step"}
		];
		this.store = new Ext.data.Store({
			proxy:new Ext.data.HttpProxy({url: this.baseUrl + "&task=getJsonData&from=desktop", disableCaching: true}),   
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
		this.store.load({params : {storeType: "all"}});
		
		colModel = new Ext.grid.ColumnModel([
			{header: "sortId", dataIndex: 'sortId', hidden: true},
			{header: lang('flowNumber') + "/" + lang('name'), dataIndex: 'flowNumber', id: "flowNumber", width: 75, sortable: false,menuDisabled :true, renderer: this.formatTitle.createDelegate(this)}
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
					var rd = grid.getStore().getAt(rowIndex).data;
					_this.viewFlow(rd.uFlowId, rd.flowId, rd.step, rd.flowType, rd.tplSort);
				},
				"render" : function(grid){
					//grid.getEl().child(".x-grid3").setStyle("backgroundColor", "#F6F6F6");
				}
			}
		});

		var tools = [], draggable = false;
		tools.push({
			id:'right',
			qtip: lang('clickReadMoreFlow'),
			handler: function(e, target, panel){
				_this.goToView();
			}
		});
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
			title: lang('readFlow'),
			layout: 'fit',
			height: 250,
			draggable: draggable,
			tools: tools,
			items: [
				this.grid
			]
		});
	},

	formatTitle : function(value, cellmeta, record, rowIndex, columnIndex, color_store){
		return "<span >"+value+"</span><br /><span class=cnoa_color_gray>"+record.data.flowName+"</span>";
	},

	checkPermit : function(noPermit){
		var p = this.mainPanel;
		if(noPermit == true){
			p.ownerCt.remove(p, true);
		}else{
			p.show();
		}
	},

	closeAction : function(){
		var _this = this;
		CNOA_main_common_index.closeDesktopApp(_this.id);
	},

	//查看流程(操作)
	viewFlow : function(uFlowId, flowId, stepId, flowType, tplSort){
		mainPanel.closeTab("CNOA_MENU_WF_USE_OPENFLOW");
		mainPanel.loadClass("index.php?app=wf&func=flow&action=use&modul=view&task=loadPage&from=viewflow&uFlowId="+uFlowId+"&flowId="+flowId+"&stepId="+stepId+"&flowType="+flowType+"&tplSort="+tplSort, "CNOA_MENU_WF_USE_OPENFLOW", "查看工作流程", "icon-flow");	
	},

	goToView : function(){
		mainPanel.closeTab("CNOA_MENU_WF_USE_READ");
		mainPanel.loadClass("index.php?app=wf&func=flow&action=use&modul=view&task=loadPage&from=list", "CNOA_MENU_WF_USE_READ", lang('flowRead'), "icon-flow");
	}
}

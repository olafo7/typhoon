//CNOA_MAIN_DESKTOP_BIRTHDAY
var DESKTOPAPP_CLASS = CNOA.Class.create();
DESKTOPAPP_CLASS.prototype = {
	init : function(portalsID){
		var _this = this;

		this.baseUrl = "index.php?app=wf&func=flow&action=use&modul=new";
		
		this.id = "CNOA_MAIN_DESKTOP_WF";
		if(portalsID) this.id += portalsID;
		this.fieldsInbox = [
			{name:"flowId"},
			{name:"flowName"},
			{name:"sname"},
			{name:"about"},
			{name:"nameRuleId"},
			{name:"tplSort"},
			{name:"flowType"},
			{name:"childId"}
		];
		
		this.store = new Ext.data.Store({
			proxy:new Ext.data.HttpProxy({url:this.baseUrl + '&task=getCollectFlow', disableCaching:true}),   
			reader:new Ext.data.JsonReader({totalProperty:"total",root:"data", fields:this.fieldsInbox}),
			listeners:{
				exception : function(th, type, action, opt, response, arg){
					_this.checkPermit(opt.reader.jsonData.noPermit);
				},
				load : function(){
					_this.mainPanel.show();
				}
			}
		});
		this.store.load({params:{sort:'wffav'}});

		this.colModel = new Ext.grid.ColumnModel([
			{header: "flowId", dataIndex: 'flowId', hidden: true},
			{header: lang('flowName'), dataIndex: 'flowName',id: 'flowName', width: 140, sortable: true, menuDisabled :true,renderer:function(v,c,record){
				return '<a href="javascript:void(0)" >'+v+'</a>';
			}},
			{header: lang('sort2'), dataIndex: 'sname', width: 140, sortable: false,menuDisabled :true},
			{header: lang('flowDescription'), dataIndex: 'about', width: 100, sortable: false,menuDisabled :true},
			{header: "", dataIndex: 'noIndex', width: 1, menuDisabled: true,resizable: false}
		]);
		this.grid = new Ext.grid.GridPanel({
			stripeRows:true,
			bodyStyle:'border-left-width:1px;',
			store:this.store,
			loadMask:{msg: lang('waiting')},
			cm:this.colModel,
			hideBorders:true,
			border:false,
			autoWidth:true,
			viewConfig:{
				forceFit:true
			},
			listeners:{
				//单击  
				"cellclick" : function(grid, rowIndex, columnIndex, e){
					if(columnIndex == 1){
						var record = _this.store.getAt(rowIndex);
						var rd = record.data;
						var childId = rd.childId;
						if(childId == undefined || childId == ''){
							childId = 0;
						}
						_this.newFlow(rd.flowId,rd.nameRuleId, rd.tplSort, rd.flowType, childId);
					};
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
			xtype:"portlet",
			id:this.id,
			title: lang('newFlow'),		
			layout:'fit',
			height:250,
			tools: tools,
			items:[this.grid]
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

	newFlow : function(flowId, nameRuleId, tplSort, flowType, childId){
		var _this = this;

		if(flowType == 0){
			mainPanel.closeTab("CNOA_MENU_WF_USE_OPENFLOW");
			mainPanel.loadClass(_this.baseUrl+"&task=loadPage&from=newflow&flowId="+flowId+"&nameRuleId="+nameRuleId+"&flowType="+flowType+"&tplSort="+tplSort+"&childId="+childId, "CNOA_MENU_WF_USE_OPENFLOW", "发起新的固定流程", "icon-flow-new");
		}else{
			mainPanel.closeTab("CNOA_USE_FLOW_NEWFREE_FLOWDESIGN");
			mainPanel.loadClass("index.php?app=wf&func=flow&action=use&modul=newfree&task=loadPage&from=flowdesign&flowId="+flowId+"&flowType="+flowType+"&tplSort="+tplSort+"&childId="+childId, "CNOA_USE_FLOW_NEWFREE_FLOWDESIGN", "设计流程", "icon-flow-new");
		}
	},

	closeAction : function(){
		var _this = this;
		CNOA_main_common_index.closeDesktopApp(_this.id);
	}
}

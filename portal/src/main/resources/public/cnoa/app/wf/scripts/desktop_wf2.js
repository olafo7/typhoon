//CNOA_MAIN_DESKTOP_BIRTHDAY
var DESKTOPAPP_CLASS = CNOA.Class.create();
DESKTOPAPP_CLASS.prototype = {
	init : function(portalsID){
		var _this = this;

		this.baseUrl = "index.php?app=wf&func=flow&action=timeout";
		
		this.id = "CNOA_MAIN_DESKTOP_WF2";
		if(portalsID) this.id += portalsID;
		this.fieldsInbox = [
			{name:"user"},
			{name:"dept"},
			{name:"job"},
			{name:"count"},
			{name:'time'},
			{name:'flowType'}
		];
		
		this.store = new Ext.data.Store({
			proxy:new Ext.data.HttpProxy({url:this.baseUrl + '&task=getRanking', disableCaching:true}),   
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
		this.store.load();

		this.colModel = new Ext.grid.ColumnModel([
			new Ext.grid.RowNumberer(),
			{header: lang('truename'), dataIndex: 'user', width: 100, sortable: false, menuDisabled :true},
			{header: lang('department'), dataIndex: 'dept', width: 100, sortable: false, menuDisabled :true},
			{header: lang('job'), dataIndex: 'job',  width: 100, sortable: false, menuDisabled :true},
			{header: lang('quantity'), dataIndex: 'count',width: 100, sortable: true, menuDisabled :true},
			{header: lang('takeAverLength'), dataIndex: 'time',width: 140, sortable: true, menuDisabled :true},
			{header: "", dataIndex: 'noIndex', width: 1, menuDisabled:true, resizable: false}
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
		tools.push({
			id:'maximize',
			handler: function(){
				mainPanel.closeTab('CNOA_MENU_WF_MANAGER_TIMEOUT');
				mainPanel.loadClass(_this.baseUrl+'&task=loadPage', 'CNOA_MENU_WF_MANAGER_TIMEOUT', lang('dealManagement'), 'icon-page-list');
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
			title: lang('flowRank'),		
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

	closeAction : function(){
		var _this = this;
		CNOA_main_common_index.closeDesktopApp(_this.id);
	}
}

var DESKTOPAPP_CLASS = CNOA.Class.create();
DESKTOPAPP_CLASS.prototype = {
	init : function(portalsID){
		var _this = this;

		this.baseUrl = "index.php?app=news&func=bbs&action=bbs&task=getIndex";
		
		this.id = "CNOA_MAIN_DESKTOP_NEWS_BBS_{BBSTYPE}";
		if(portalsID) this.id += portalsID;
		var fieldsInbox = [
			{name: 'id'},
			{name: 'title'},
			{name: 'posttime'},
			{name: 'author'}
		];
		this.store = new Ext.data.Store({
			proxy:new Ext.data.HttpProxy({url: this.baseUrl + "&model={BBSFUNC}", disableCaching: true}),   
			reader:new Ext.data.JsonReader({root:"data", fields: fieldsInbox}),
			listeners:{
				exception : function(th, type, action, opt, response, arg){
					_this.checkPermit(opt.reader.jsonData.noPermit);
				},
				load : function(th){
					_this.mainPanel.show();
				}
			}
		});
		this.store.load();
		
		colModel = new Ext.grid.ColumnModel([
			{header: "id", dataIndex: 'id', hidden: true},
			{header: lang('title'), dataIndex: 'title', width: 80, sortable: false,menuDisabled:true, id:'title'},
			{header: lang('date'), dataIndex: 'posttime', width: 100, sortable: false,menuDisabled:true},
			{header: "作者", dataIndex: 'author',  width: 90, sortable: false,menuDisabled:true}
		]);
		
		this.grid = new Ext.grid.GridPanel({
			bodyStyle: 'border-left-width:1px;',
			store: this.store,
			loadMask : {msg: lang('waiting')},
			cm: colModel,
			hideBorders: true,
			border: false,
			autoWidth: true,
			autoExpandColumn: 'title',
			listeners:{  
				//单击  
				"rowclick" : function(grid, rowIndex, columnIndex){
					var record = grid.getStore().getAt(rowIndex);
					_this.view(record.data.id);
				},
				"render" : function(grid){
					grid.getEl().child(".x-grid3").setStyle("backgroundColor", "#F6F6F6");
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
			title:"{BBSTITLE}",
			layout: 'fit',
			height: 250,
			draggable: draggable,
			tools: tools,
			items: [
				this.grid
			]
		});
	},

	view : function(pid){
		mainPanel.closeTab("CNOA_MENU_NEWS_BBS_BBS");
		mainPanel.loadClass("index.php?app=news&func=bbs&action=bbs&pid="+pid, "CNOA_MENU_NEWS_BBS_BBS", "站内论坛", "icon-system-notice");
	},

	closeAction : function(){
		var _this = this;
		CNOA_main_common_index.closeDesktopApp(_this.id);
	},

	checkPermit : function(noPermit){
		var p = this.mainPanel;
		if(noPermit == true){
			p.ownerCt.remove(p, true);
		}else{
			p.show();
		}
	}

}

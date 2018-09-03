var DESKTOPAPP_CLASS = CNOA.Class.create();
DESKTOPAPP_CLASS.prototype = {
	init : function(portalsID){
		var _this = this;

		this.baseUrl = "index.php?app=news&func=news&action=view";
		
		this.id = "CNOA_MAIN_DESKTOP_NEWS_NEWS_SORT_{NEWSSORTID}";
		if(portalsID) this.id += portalsID;
		var fieldsInbox = [
			{name: 'lid'},
			{name: 'allowcomment'},
			{name: 'isread'},
			{name: 'title'},
			{name: 'posttime'},
			{name: 'count'}
		];
		this.store = new Ext.data.Store({
			proxy:new Ext.data.HttpProxy({url: this.baseUrl + "&task=desktoplist&sid={NEWSSORTID}", disableCaching: true}),   
			reader:new Ext.data.JsonReader({totalProperty:"total",root:"data", fields: fieldsInbox}),
			listeners:{
				exception : function(th, type, action, opt, response, arg){
					_this.checkPermit(opt.reader.jsonData.noPermit);
				},
				load : function(th){
					var data = th.reader.jsonData;
					_this.isHide(data.allowcomment);
					_this.mainPanel.show();
				}
			}
		});
		this.store.load({params:{start:0, rows: '{NEWSSORTROWS}'}});
		
		var renderTitle = function(value, meta, record){
			if(!record.get('isread')){
				data = '<span style="font-weight: bold" title="' + value + '">' + value + '</span>';
			} else {
				data = '<span title="' + value + '">' + value + '</span>';
			}
			
			return data;
		},
		
		statusCheck = function(value){
			if (value==1){
				return '<img src="./resources/images/icons/sms-readed.gif" width="16" height="16" />';
			}else{
				return '<img src="./resources/images/icons/sms-unread.gif" width="16" height="16" />';
			}
		},
		
		colModel = new Ext.grid.ColumnModel([
			{header: "lid", dataIndex: 'lid', hidden: true},
			{header: '<img src="./resources/images/icons/sms-readed.gif" width="16" height="16" />', dataIndex: 'isread', width: 34, sortable: false, menuDisabled :true,renderer: statusCheck},
			{header: lang('title'), dataIndex: 'title', width: 120, sortable: false, menuDisabled: true, renderer: renderTitle, id:'title'},
			{header: lang('date'), dataIndex: 'posttime', width: 78, sortable: false, menuDisabled: true},
			{header: lang('numOfComment'), dataIndex: 'count', id: 'conment', width: 50, sortable: false, menuDisabled: true, renderer: _this.allowcomment}
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
					_this.view(record.data.lid);
					record.set('isread', 1);
					record.set('title', record.get('title'));
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
			title:"{NEWSSORTNAME}",
			layout: 'fit',
			height: 250,
			draggable: draggable,
			tools: tools,
			items: [
				this.grid
			]
		});
	},

	allowcomment: function(value, mateData, record){
		var data = record.data;
		return "<span style='color:red'>" + data.count + "</span>";
	},

	checkPermit : function(noPermit){
		var p = this.mainPanel;
		if(noPermit == true){
			p.ownerCt.remove(p, true);
		}else{
			p.show();
		}
	},

	view : function(lid){
		mainPanel.closeTab("CNOA_MENU_NEWS_VIEW");
		mainPanel.loadClass(this.baseUrl + "&task=view&lid="+lid, "CNOA_MENU_NEWS_VIEW", lang('viewMsg'), "icon-page-view");
	},

	closeAction : function(){
		var _this = this;
		CNOA_main_common_index.closeDesktopApp(_this.id);
	},

	isHide: function(allowcomment){
		var me = this;
		if (allowcomment != 1) {
			me.grid.colModel.setHidden(4, true);
		} else{
			me.grid.colModel.setHidden(4, false);
		}
	}
}

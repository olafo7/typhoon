///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
var DESKTOPAPP_CLASS = CNOA.Class.create();
DESKTOPAPP_CLASS.prototype = {
	init : function(portalsID){
		var _this = this;
		
		var ID_UNREAD_BUTTON = Ext.id();

		this.baseUrl = "index.php?app=news&func=notice&action=index&task=desktoplist";
		
		this.id = "CNOA_MAIN_DESKTOP_NEWS_NOTICE";
		if(portalsID) this.id += portalsID;
		this.fieldsInbox = [
			{name:"id"},
			{name:"title"},
			{name:"isread"}
		];
		this.store = new Ext.data.Store({
			proxy:new Ext.data.HttpProxy({url: this.baseUrl, disableCaching: true}),   
			reader:new Ext.data.JsonReader({totalProperty:"total",root:"data", fields: this.fieldsInbox}),
			listeners: {
				exception : function(th, type, action, opt, response, arg){
					_this.checkPermit(opt.reader.jsonData.noPermit);
				},
				'load' : function(th, record, e){
					_this.mainPanel.show();
					var unread = 0;
					unread = "<span class='cnoa_color_red'>"+th.reader.jsonData.unread+"</span>";
					Ext.getCmp(ID_UNREAD_BUTTON).setText(lang('unread') + "("+unread+")");
				}
			}
				
		});
		
		this.store.load({params:{start:0, rows: 50, unread:0}});
		this.colModel = new Ext.grid.ColumnModel([
			{header: "id", dataIndex: 'id', hidden: true},
			{header: '<img src="./resources/images/icons/sms-readed.gif" width="16" height="16" />', dataIndex: 'isread', width: 34, sortable: false, menuDisabled :true,renderer: this.statusCheck},
			{header: lang('title'), dataIndex: 'title', width: 370, sortable: false, menuDisabled :true, renderer: this.addTitleTip}
		])
		this.grid = new Ext.grid.GridPanel({
			bodyStyle: 'border-left-width:1px;',
			store: this.store,
			loadMask : {msg: lang('waiting')},
			cm: this.colModel,
			hideBorders: true,
			border: false,
			autoWidth: true,
			viewConfig: {
				forceFit: true
			},
			listeners:{
				//单击  
				"cellclick" : function(grid, rowIndex, columnIndex, e){
					var record = grid.getStore().getAt(rowIndex);
					_this.view(record.data.id);
				},
				"render" : function(grid){
					//grid.getEl().child(".x-grid3").setStyle("backgroundColor", "#F6F6F6");
				}
			},
			tbar: new Ext.Toolbar({
				items: [
					{
						text: lang('unread'),
						id: ID_UNREAD_BUTTON,
						iconCls: 'icon-system-notice',
						tooltip: lang('showNotRead'),
						enableToggle: true,
						pressed: false,
						toggleHandler:function(btn, state){
							if(state){
								_this.store.load({params:{unread:1}});
							}else{
								_this.store.load({params:{unread:0}});
							}
						}
					}
				]
			})
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
				mainPanel.closeTab('CNOA_MENU_NEWS_NOTICE');
				mainPanel.loadClass('index.php?app=news&func=notice&action=index', 'CNOA_MENU_NEWS_NOTICE', lang('notice2'), 'icon-system-notice');
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
			xtype: "portlet",
			id: this.id,
			height: 250,
			title: lang('notice2'),
			layout: 'fit',
			draggable: draggable,
			tools: tools,
			items: [
				this.grid
			]
		});
	},

	checkPermit: function(noPermit){
		var p = this.mainPanel;
		if(noPermit == true){
			p.ownerCt.remove(p, true);
		}else{
			p.show();
		}
	},

	view : function(id){
		mainPanel.closeTab("CNOA_MENU_NEWS_NOTICE_VIEW");
		mainPanel.loadClass("index.php?app=news&func=notice&action=index&task=loadPage&from=view&id="+id, "CNOA_MENU_NEWS_NOTICE_VIEW", lang('noticeAnnoun'), "icon-system-notice");
	},

	closeAction : function(){
		var _this = this;
		CNOA_main_common_index.closeDesktopApp(_this.id);
	},
	
	statusCheck : function(value, p, record){
		if (value==1){
			return '<img src="./resources/images/icons/sms-readed.gif" width="16" height="16" />';
		}else{
			return '<img src="./resources/images/icons/sms-unread.gif" width="16" height="16" gridid="'+this.actionFolder+'_'+record.data.id+'" />';
		}
	}
}

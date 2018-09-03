var DESKTOPAPP_CLASS = CNOA.Class.create();
DESKTOPAPP_CLASS.prototype = {
	init : function(portalsID){
		var _this = this;

		this.baseUrl = "index.php?app=odoc&func=read&action=receive";
		
		this.id = "CNOA_MAIN_DESKTOP_COMM_ODOC_READ_RECEIVE";
		if(portalsID) this.id += portalsID;

		var fieldsInbox = [
			{name: 'id'},
			{name: 'flowNumber'},
			{name: 'uFlowId'},
			{name: 'flowName'}
		];
		this.store = new Ext.data.Store({
			proxy:new Ext.data.HttpProxy({url: this.baseUrl + "&task=getJsonList&from=desktop", disableCaching: true}),   
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
			{header: lang('flowNumber'), dataIndex: 'flowNumber', id: "flowNumber", width: 75, sortable: false,menuDisabled :true},
			{header: lang('flowName'), dataIndex: 'flowName', id: "flowName", width: 250, sortable: false,menuDisabled :true}
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
					_this.viewOdoc(record.data.id);
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
			title: lang('receiveFileRead'),
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
	
	viewOdoc : function(id){
		var _this = this;
		
		var ID_fieldSet_Word = Ext.id();
		var ID_fieldSet_Attach = Ext.id();
		var ID_fieldSet_Form = Ext.id();
		
		var loadAttachList = function(){
			Ext.Ajax.request({
				url: _this.baseUrl + "&task=loadAttachList",
				method: 'POST',
				params:{id:id},
				success: function(r) {
					var result = Ext.decode(r.responseText);
					if(result.success === true){
						if(result.data.word){
							Ext.getCmp(ID_fieldSet_Word).show();
							Ext.getCmp(ID_fieldSet_Word).body.update(result.data.word);
						}
						if(result.data.attach){
							Ext.getCmp(ID_fieldSet_Attach).show();
							Ext.getCmp(ID_fieldSet_Attach).body.update(result.data.attach);
						}
						if(result.data.form){
							Ext.getCmp(ID_fieldSet_Form).show();
							Ext.getCmp(ID_fieldSet_Form).body.update(result.data.form);
						}
					}
				}
			});
		};
		
		var win = new Ext.Window({
			title: lang('lookAddressee'),
			width: 800,
			height: makeWindowHeight(Ext.getBody().getBox().height-40),
			modal: true,
			bodyStyle: 'background-color:#FFF;padding:10px;',
			autoScroll: true,
			maximizable: true,
			border: false,
			items: [
				{
					xtype: 'fieldset',
					title: lang('fileView'),
					id: ID_fieldSet_Word
				},
				{
					xtype: 'fieldset',
					title: lang('formView'),
					id: ID_fieldSet_Form
				},
				{
					xtype: 'fieldset',
					title: lang('attachmentView'),
					id: ID_fieldSet_Attach
				}
			],
			listeners: {
				afterrender : function(){
					loadAttachList();
				}
			},
			buttons:[
				{
					text:lang('close'),
					handler:function(btn){
						win.close();
					}
				}
			]
		}).show();
	},

	closeAction : function(){
		var _this = this;
		CNOA_main_common_index.closeDesktopApp(_this.id);
	}
}

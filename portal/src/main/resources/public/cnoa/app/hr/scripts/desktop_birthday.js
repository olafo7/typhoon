//CNOA_MAIN_DESKTOP_BIRTHDAY
var DESKTOPAPP_CLASS = CNOA.Class.create();
DESKTOPAPP_CLASS.prototype = {
	init : function(portalsID){
		var _this = this;

		this.baseUrl = "index.php?app=hr&func=person&action=waiting";
		
		this.id = "CNOA_MAIN_DESKTOP_BIRTHDAY";
		if(portalsID) this.id += portalsID;

		this.fieldsInbox = [
			{name : "pid"},
			{name: "truename"},
			{name: "deptment"},
			{name: "birthday"},
			{name: "age"},
			{name: "gender"},
			{name: "workStatus"},
			{name: "cellPhone"},
			{name: "operate"}
		];
		
		this.store = new Ext.data.Store({
			proxy:new Ext.data.HttpProxy({url: this.baseUrl + '&task=getJsonData', disableCaching: true}),   
			reader:new Ext.data.JsonReader({totalProperty:"total",root:"data", fields: this.fieldsInbox}),
			listeners:{
				exception : function(th, type, action, opt, response, arg){
					_this.checkPermit(opt.reader.jsonData.noPermit);
				},
				load : function(){
					_this.mainPanel.show();
				}
			}
		});

		this.store.load({params:{start:0, rows: 5}});

		this.colModel = new Ext.grid.ColumnModel([
			{header: "pid", dataIndex: 'pid', hidden: true},
			{header: lang('truename'), dataIndex: 'truename', width: 100, sortable: true, menuDisabled :true},
			{header: lang('department'), dataIndex: 'deptment', width: 100, sortable: true, menuDisabled :true},
			{header: lang('birthdayTime'), dataIndex: 'birthday', width: 100, sortable: true, menuDisabled :true},
			{header: lang('age'), dataIndex: 'age', width: 40, sortable: true, menuDisabled :true},
			{header: lang('sex'), dataIndex: 'gender', width: 60, sortable: true, menuDisabled :true},
			{header: lang('status'), dataIndex: 'workStatus', width: 70, sortable: true,menuDisabled :true },
			{header: "", dataIndex: 'noIndex', width: 1, menuDisabled: true,resizable: false}
		]);

		this.grid = new Ext.grid.GridPanel({
			stripeRows : true,
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
			xtype: "portlet",
			id: this.id,
			title: lang('hrBirthday'),		
			layout: 'fit',
			height: 250,
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

	makeLink : function(value, cellmeta, record, rowIndex, columnIndex, color_store){
		var rd = record.data;

		return "<a href='"+rd.link+"' target='_blank'><div>"+value+"</div></a>";
	},

	closeAction : function(){
		var _this = this;
		CNOA_main_common_index.closeDesktopApp(_this.id);
	}
}

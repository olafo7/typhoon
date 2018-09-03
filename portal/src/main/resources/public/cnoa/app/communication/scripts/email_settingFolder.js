var CNOA_communication_email_settingFolderClass, CNOA_communication_email_settingFolder;

/**
* 主面板-列表
*
*/
CNOA_communication_email_settingFolderClass = CNOA.Class.create();
CNOA_communication_email_settingFolderClass.prototype = {
	init : function(){
		var _this = this;

		this.baseUrl = "index.php?app=communication&func=email&action=index";

		/**
		 * Tab收件箱
		 */
		this.fields = [
			{name:"fid"},
			{name:"name"},
			{name:"rule"}
		];
		this.store = new Ext.data.Store({
			proxy:new Ext.data.HttpProxy({url: this.baseUrl+"&task=getFolderList", disableCaching: true}),   
			reader:new Ext.data.JsonReader({totalProperty:"total",root:"data", fields: this.fields})		
		});
		this.store.load();
		this.sm = new Ext.grid.CheckboxSelectionModel({
			singleSelect: true
		});
		//this.sm.handleMouseDown = Ext.emptyFn;
		this.colModel = new Ext.grid.ColumnModel([
			//new Ext.grid.RowNumberer(),
			this.sm,
			{header: "id", dataIndex: 'fid', hidden: true},
			{header: lang('name'), dataIndex: 'name', width: 260, sortable: true, menuDisabled :true},
			{header: "", dataIndex: 'noIndex', width: 1, menuDisabled: true,resizable: false}
		]);

		this.grid = new Ext.grid.GridPanel({
			stripeRows : true,
			store: this.store,
			loadMask : {msg: lang('waiting')},
			cm: this.colModel,
			sm: this.sm,
			hideBorders: true,
			border: false,
			listeners:{  
				//双击  
				"rowdblclick" : function(grid, row){
					_this.showAddEditWindow('edit');
				},  
				//单击  
				"cellclick" : function(grid, rowIndex, columnIndex, e){
					
				}
			},
			tbar : new Ext.Toolbar({
				items: [
					{
						handler : function(button, event) {
							this.store.reload();
						}.createDelegate(this),
						iconCls: 'icon-system-refresh',
						tooltip: lang('refresh'),
						text : lang('refresh')
					},
					{
						handler : function(button, event) {
							_this.showAddEditWindow('add');
						}.createDelegate(this),
						iconCls: 'icon-utils-s-add',
						text : lang('add')
					},{
						handler : function(button, event) {
							_this.showAddEditWindow('edit', button);
						}.createDelegate(this),
						iconCls: 'icon-utils-s-edit',
						text : lang('modify')
					},{
						text : lang('del'),
						iconCls: 'icon-utils-s-delete',
						handler : function(button, event) {
							_this.deleteList(button);
						}.createDelegate(this)
					},
					"->",{xtype: 'cnoa_helpBtn', helpid: 125}
				]
			})
		});

		this.centerPanel = new Ext.Panel({
			border: false,
			//layout:'fit',
			region: "center",
			layout: "fit",
			items: [this.grid]
		});

		this.mainPanel = new Ext.Panel({
			collapsible:false,
			hideBorders: true,
			border: false,
			layout:'border',
			autoScroll: false,
			items: [this.centerPanel]
		});
	},
	
	deleteList : function(btn){
		var _this = this;
		
		var rows = this.grid.getSelectionModel().getSelections();
		if (rows.length == 0) {
			CNOA.miniMsg.alertShowAt(btn, lang('mustSelectOneRow'));
		} else {
			CNOA.msg.cf(lang('deleteFolder'), function(btn){
				if (btn == 'yes') {
					if (rows) {
						var ids = "";
						//取消多删除
						for (var i = 0; i < rows.length; i++) {
							ids += rows[i].get("fid") + ",";
						}
						Ext.Ajax.request({
							url: _this.baseUrl + '&task=deleteFolder',
							method: 'POST',
							params: {
								ids: ids,
								btn: btn
							},
							success: function(r){
								var result = Ext.decode(r.responseText);
								if (result.success === true) {
									CNOA.msg.notice2(result.msg);
									_this.store.reload();
								}
								else {
									CNOA.msg.alert(result.msg);
								}
							}
						});
					}
				}
			});
		} 
	},
	
	showAddEditWindow : function(type, btn){
		var _this = this;
		var fid = 0, name = "";
		
		if(type == 'edit'){
			var rows = this.grid.getSelectionModel().getSelections();
			if (rows.length == 0) {
				CNOA.miniMsg.alertShowAt(btn, lang('mustSelectOneRow'));
				return;
			}
			fid = rows[0].get('fid');
			name = rows[0].get('name');
		}
		
		this.baseField = [
			{
            	xtype:'fieldset',
				defaults:{
				    border: false
				},
				items:[
					{
						xtype: 'textfield',
						width: 238,
						name: "name",
						allowBlank: false,
						fieldLabel: lang('folderName'),
						value: type == 'edit' ? name : ""
					}
				]
        	}
		];
		
		this.formPanel = new Ext.form.FormPanel({
			border: false,
			monitorResize: true,
			labelWidth: 60,
			labelAlign: 'right',
			waitMsgTarget: true,
			items: [
				{xtype: "panel",border: false, bodyStyle: 'padding:10px;', items: this.baseField}
			]
		});
		
		var submitForm = function(){
			if (_this.formPanel.getForm().isValid()) {				
				_this.formPanel.getForm().submit({
					url: _this.baseUrl + "&task=saveSettingFolder",
					waitTitle: lang('notice'),
					method: 'POST',
					waitMsg: lang('waiting'),
					params: {fid : fid, type : type},
					success: function(form, action) {
						_this.store.reload();
						_this.addEditWindow.close();
					},
					failure: function(form, action) {
						CNOA.msg.alert(action.result.msg, function(){
							
						});
					}
				});
			}
		};

		this.addEditWindow = new Ext.Window({
			title: lang('addOrEditFolder'),
			width: 360,
			height: 156,
			resizable: false,
			autoDistory: true,
			modal: true,
			maskDisabled : true,
			items: [this.formPanel],
			plain: true,
			layout: 'fit',
			buttonAlign: "right",
			buttons: [
				//保存
				{
					text: lang('save'),
					iconCls: 'icon-btn-save',
					handler: function() {
						submitForm();
					}.createDelegate(this)
				},
				//关闭
				{
					text: lang('close'),
					iconCls: 'icon-dialog-cancel',
					handler: function() {
						_this.addEditWindow.close();
					}
				}
			]
		});
		
		this.addEditWindow.show();
	}
}

Ext.onReady(function() {
	
	CNOA_communication_email_settingFolder = new CNOA_communication_email_settingFolderClass();
	Ext.getCmp(CNOA.communication.email.settingFolder.parentID).add(CNOA_communication_email_settingFolder.mainPanel);
	Ext.getCmp(CNOA.communication.email.settingFolder.parentID).doLayout();

});
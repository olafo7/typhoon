//定义全局变量：
var CNOA_att_arrange_settingsClass, CNOA_att_arrange_settings;
CNOA_att_arrange_settingsClass = CNOA.Class.create();
CNOA_att_arrange_settingsClass.prototype = {
	init: function(){
		this.baseUrl = "index.php?app=att&func=arrange&action=settings";
		this.TYPE_CLASSES	= 0;
		this.TYPE_SHIFT		= 1;
		
		var fields =[
			{name: 'cid'},
			{name: 'name'},
			{name: 'stime'},
			{name: 'etime'},
			{name: 'inStime'},
			{name: 'inEtime'},
			{name: 'outStime'},
			{name: 'outEtime'},
			{name: 'status'}
		];
		this.classesStore = new Ext.data.Store({
			autoLoad: true,
			proxy: new Ext.data.HttpProxy({url: this.baseUrl + "&task=getClasses", disableCaching: true}),   
			reader: new Ext.data.JsonReader({totalProperty:"total", root:"data", fields: fields})
		});
		
		this.classesPanel = this.getClassesPanel();
		this.shiftPanel = this.getShiftPanel();
		
		this.mainPanel = new Ext.ux.VerticalTabPanel({
			region: "center",
			border: false,
			tabPosition: "left",
			tabWidth: 100,
			activeItem: 0,
			deferredRender: true,
			layoutOnTabChange: true,
			items: [this.classesPanel, this.shiftPanel]
		});
	},
	
	//班次面板
	getClassesPanel: function(){
		var me = this;
		
		var sm = new Ext.grid.CheckboxSelectionModel();
		var getCheckbox = function(value){
			if(value=="") return '';
			checked = value==1 ? 'checked' : '';
			return '<input type="checkbox" '+checked+' />';
		};
		
		var colModel = new Ext.grid.ColumnModel({
			defaults:{sortable: true, menuDisabled: true},
			columns: [
				new Ext.grid.RowNumberer(),
				sm,
				{header:'名称', dataIndex: 'name', editor: new Ext.form.TextField()},
				{header:'上班时间', dataIndex: 'stime', editor: new Ext.form.TimeField({format: 'H:i'})},
				{header:'下班时间', dataIndex: 'etime', editor: new Ext.form.TimeField({format: 'H:i'})},
				{header:'签到开始时间', dataIndex: 'inStime', editor: new Ext.form.TimeField({format: 'H:i'})},
				{header:'签到结束时间', dataIndex: 'inEtime', editor: new Ext.form.TimeField({format: 'H:i'})},
				{header:'签退开始时间', dataIndex: 'outStime', editor: new Ext.form.TimeField({format: 'H:i'})},
				{header:'签退结束时间', dataIndex: 'outEtime', editor: new Ext.form.TimeField({format: 'H:i'})},
				{header:'是否生效', dataIndex: 'status', renderer: getCheckbox}
			]
		});
		
		var grid = new Ext.grid.EditorGridPanel({
			title: '班次',
			border: false,
			loadMask: {msg: lang('loading')},
			store: this.classesStore,
			cm: colModel,
			sm: sm,
			tbar: new Ext.Toolbar({
				items: [
					{
						text:lang('refresh'),
						iconCls:'icon-system-refresh',
						handler:function(){
							me.classesStore.reload();
						}
					}, '-', {
						text: lang('add'),
						iconCls: 'icon-utils-s-add',
						handler: function(){
							me.addClasses();
						}
					}, '-', {
						text: lang('del'),
						iconCls: 'icon-utils-s-delete',
						handler: function(btn){
							var rows = grid.getSelectionModel().getSelections();
							if (rows.length == 0){
								CNOA.miniMsg.alertShowAt(btn, lang('mustSelectOneRow'));
								
							} else {
								CNOA.miniMsg.cfShowAt(btn, "确定删除吗?", function(){
									var ids = [];
									Ext.each(rows, function(item, i){
										ids.push(item.json.cid);
									});
									me.delClasses(ids.join(','));
								});
							}
						}
					}
				]
			}),
			listeners: {
				'cellclick': function(grid, rowIndex, columnIndex){
					var field = grid.getColumnModel().getDataIndex(columnIndex);
					if(field == 'status'){
						var record = grid.getStore().getAt(rowIndex),
							id = record.get('cid');
						value = $(grid.getView().getCell(rowIndex, columnIndex)).find('input').attr('checked');
						if (value == undefined) return;
						value = value ? 1 : 0;
						me.updateClasses(id, field, value);
					}
				},
				'afteredit': function(e){
					var field = e.field,
						id = e.record.get('cid'),
						value = e.value;
						me.updateClasses(id, field, value);
				}
			}
		});
		
		return grid;
	},
	
	//班制
	getShiftPanel: function(){
		var me = this;
		
		var fields = [
			{name: 'sid'},
			{name: 'name'},
			{name: 'sun'},
			{name: 'mon'},
			{name: 'tue'},
			{name: 'wed'},
			{name: 'thurs'},
			{name: 'fri'},
			{name: 'sat'}
		];
		
		var shiftStore = new Ext.data.Store({
			autoLoad: true,
			proxy: new Ext.data.HttpProxy({url: me.baseUrl + "&task=getShift", disableCaching: true}),   
			reader: new Ext.data.JsonReader({totalProperty:"total",root:"data", fields: fields})
		});
		
		var weebCombo = new Ext.form.ComboBox({
			typeAhead: true,
		    triggerAction: 'all',
		    lazyRender:true,
		    mode: 'local',
		    store: this.classesStore,
		    valueField: 'cid',
		    displayField: 'name'
		});
		var renCombo = function(combo){
			return function (value){
				var record = combo.findRecord(combo.valueField, value);
				return record ? record.get('name') : combo.valueNotFoundText;
			}
		};
		
		var sm = new Ext.grid.CheckboxSelectionModel();
		var colModel = new Ext.grid.ColumnModel({
			defaults:{sortable: true, menuDisabled: true},
			columns: [
				new Ext.grid.RowNumberer(),
				sm,
				{header:'名称', dataIndex: 'name', editor: new Ext.form.TextField({allowBlank: false})},
				{header:'星期日', dataIndex: 'sun', editor: weebCombo, renderer: renCombo(weebCombo)},
				{header:'星期一', dataIndex: 'mon', editor: weebCombo, renderer: renCombo(weebCombo)},
				{header:'星期二', dataIndex: 'tue', editor: weebCombo, renderer: renCombo(weebCombo)},
				{header:'星期三', dataIndex: 'wed', editor: weebCombo, renderer: renCombo(weebCombo)},
				{header:'星期四', dataIndex: 'thurs', editor: weebCombo, renderer: renCombo(weebCombo)},
				{header:'星期五', dataIndex: 'fri', editor: weebCombo, renderer: renCombo(weebCombo)},
				{header:'星期六', dataIndex: 'sat', editor: weebCombo, renderer: renCombo(weebCombo)}
			]
		});
		
		var grid = new Ext.grid.EditorGridPanel({
			title: '班制',
			border: false,
			loadMask: {msg: lang('loading')},
			store: shiftStore,
			cm: colModel,
			sm: sm,
			tbar: new Ext.Toolbar({
				items: [
					{
						text:lang('refresh'),
						iconCls:'icon-system-refresh',
						handler:function(){
							me.shiftPanel.store.reload();
						}
					}, '-', {
						text: lang('add'),
						iconCls: 'icon-utils-s-add',
						handler: function(){
							me.addShift();
						}
					}, '-', {
						text: lang('del'),
						iconCls: 'icon-utils-s-delete',
						handler: function(btn){
							var rows = grid.getSelectionModel().getSelections();
							if (rows.length == 0){
								CNOA.miniMsg.alertShowAt(btn, lang('mustSelectOneRow'));
								
							} else {
								CNOA.miniMsg.cfShowAt(btn, "确定删除吗?", function(){
									var ids = [];
									Ext.each(rows, function(item, i){
										ids.push(item.json.sid);
									});
									me.delShift(ids.join(','));
								});
							}
						}
					}
				]
			}),
			listeners: {
				'afteredit': function(e){
					var field = e.field,
						id = e.record.get('sid'),
						value = e.value;
						e.record.set('')
						me.updateShift(id, field, value);
				}
			}
		});
		
		return grid;
	},
	
	//添加班次
	addClasses: function(){
		var me = this;
		
		var formPanel = new Ext.form.FormPanel({
			border: false,
			hideBorders: true,
			labelAlign: 'right',
			labelWidth: 80,
			padding: 10,
			defaults:{
				width: 180,
				xtype:'timefield'
			},
			items: [
				{
					xtype: 'textfield',
					fieldLabel: '名称',
					name: 'name',
					allowBlank: false
				},{
					fieldLabel: '上班时间',
					format: 'H:i',
					name: 'stime'
				},{
					fieldLabel: '下班时间',
					format: 'H:i',
					name: 'etime'
				},{
					fieldLabel: '签到开始时间',
					format: 'H:i',
					name: 'inStime'
				},{
					fieldLabel: '签到结束时间',
					format: 'H:i',
					name: 'inEtime'
				},{
					fieldLabel: '签退开始时间',
					format: 'H:i',
					name: 'outStime'
				},{
					fieldLabel: '签退结束时间',
					format: 'H:i',
					name: 'outEtime'
				},{
					xtype: 'radiogroup',
					fieldLabel: '是否生效',
					items: [
						{boxLabel: '是', name: 'status', inputValue: 1, checked: true},
                		{boxLabel: '否', name: 'status', inputValue: 0}
					]
				}
			]
		});
		
		var submit = function(){
			if (formPanel.getForm().isValid()) {
				formPanel.getForm().submit({
					url: me.baseUrl + "&task=addClasses",
					waitTitle: lang('notice'),
					waitMsg: lang('notice'),
					method: 'POST',
					success: function(form, action) {
						CNOA.msg.notice(action.result.msg, "考勤设置");
						me.classesPanel.store.reload();
						win.close();
					},
					failure: function(form, action) {
						CNOA.msg.alert(action.result.msg);
					}
				});
			}
		};
		
		var win = new Ext.Window({
			title: '添加班次',
			width: 330,
			height: 350,
			layout: "fit",
			resizable: false,
			autoDistory: true,
			modal: true,
			maskDisabled : true,
			items: [formPanel],
			plain: true,
			buttonAlign: "right",
			buttons: [
				{
					text: lang('save'),
					iconCls: 'icon-btn-save',
					handler: function() {
						submit();
					}
				},{
					text: lang('close'),
					iconCls: 'icon-dialog-cancel',
					handler: function() {
						win.close();
					}
				}
			]
		});
		
		win.show();
	},
	
	//删除班次
	delClasses: function(ids, type){
		var me = this;
		Ext.Ajax.request({
			url: me.baseUrl + "&task=" + task,
			params:{ids: ids},
			success: function(response){
				var responseText = Ext.decode(response.responseText);
				CNOA.msg.notice2(responseText.msg);
				me.classesPanel.store.reload();
			}
		});
	},
	
	//修改班次
	updateClasses: function(id, field, value){
		var me = this;
		Ext.Ajax.request({
			url: me.baseUrl + "&task=updateClasses",
			params:{id: id, field: field, value: value},
			success: function(response){
				var responseText = Ext.decode(response.responseText);
				CNOA.msg.notice2(responseText.msg);
			}
		});
	},
	
	//添加班制
	addShift: function(){
		var me = this;

		var formPanel = new Ext.form.FormPanel({
			border: false,
			hideBorders: true,
			labelAlign: 'right',
			labelWidth: 80,
			padding: 10,
			defaults:{
				width: 180,
				xtype:'combo',
				typeAhead: true,
			    triggerAction: 'all',
			    lazyRender:true,
			    mode: 'local',
			    store: this.classesStore,
			    valueField: 'cid',
			    displayField: 'name'
			},
			items: [
				{
					xtype: 'textfield',
					fieldLabel: '名称',
					name: 'name',
					allowBlank: false
				},{
					fieldLabel: '星期日',
					hiddenName: 'sun'
				},{
					fieldLabel: '星期一',
					hiddenName: 'mon'
				},{
					fieldLabel: '星期二',
					hiddenName: 'tue'
				},{
					fieldLabel: '星期三',
					hiddenName: 'wed'
				},{
					fieldLabel: '星期四',
					hiddenName: 'thurs'
				},{
					fieldLabel: '星期五',
					hiddenName: 'fri'
				},{
					fieldLabel: '星期六',
					hiddenName: 'sat'
				}
			]
		});
		
		var submit = function(){
			if (formPanel.getForm().isValid()) {
				formPanel.getForm().submit({
					url: me.baseUrl + "&task=addShift",
					waitTitle: lang('notice'),
					waitMsg: lang('notice'),
					method: 'POST',
					success: function(form, action) {
						CNOA.msg.notice(action.result.msg, "考勤设置");
						me.shiftPanel.store.reload();
						win.close();
					},
					failure: function(form, action) {
						CNOA.msg.alert(action.result.msg);
					}
				});
			}
		};
		
		var win = new Ext.Window({
			title: '添加班制',
			width: 330,
			height: 350,
			layout: "fit",
			resizable: false,
			autoDistory: true,
			modal: true,
			maskDisabled : true,
			items: [formPanel],
			plain: true,
			buttonAlign: "right",
			buttons: [
				{
					text: lang('save'),
					iconCls: 'icon-btn-save',
					handler: function() {
						submit();
					}
				},{
					text: lang('close'),
					iconCls: 'icon-dialog-cancel',
					handler: function() {
						win.close();
					}
				}
			]
		});
		
		win.show();
	},
	
	//删除班制
	delShift: function(ids){
		var me = this;
		Ext.Ajax.request({
			url: me.baseUrl + "&task=delShift",
			params:{ids: ids},
			success: function(response){
				var responseText = Ext.decode(response.responseText);
				CNOA.msg.notice2(responseText.msg);
				me.shiftPanel.store.reload();
			}
		});
	},
	
	//修改班制
	updateShift: function(id, field, value){
		var me = this;
		Ext.Ajax.request({
			url: me.baseUrl + "&task=updateShift",
			params:{id: id, field: field, value: value},
			success: function(response){
				var responseText = Ext.decode(response.responseText);
				CNOA.msg.notice2(responseText.msg);
			}
		});
	}
};

CNOA_att_arrange_settings = new CNOA_att_arrange_settingsClass();
Ext.getCmp(CNOA.att.arrange.settings.parentID).add(CNOA_att_arrange_settings.mainPanel);
Ext.getCmp(CNOA.att.arrange.settings.parentID).doLayout();

var CNOA_user_customers_myDelayRecordClass, CNOA_user_customers_myDelayRecord;

CNOA_user_customers_myDelayRecordClass = CNOA.Class.create();
CNOA_user_customers_myDelayRecordClass.prototype = {
	init : function(cid){
		var _this = this;
		this.baseUrl = "index.php?app=user&func=customers&action=index&module=delay&cid="+cid;
		this.cid = cid;

		var fields = [
			{name: "did"},
			{name: "max"},
			{name: "uname"},
			{name: "version"},
			{name: "endtime"},
			{name: "posttime"},
			{name: "dlnum"},
			{name: "expire"},
			{name: "other"}
		],
		
		store = new Ext.data.Store({
			autoLoad : true,
			proxy : new Ext.data.HttpProxy({url: this.baseUrl+"&task=getJsonData", disableCaching: true}),   
			reader : new Ext.data.JsonReader({totalProperty:"total",root:"data", fields: fields})
		}),
		
		sm = new Ext.grid.CheckboxSelectionModel({
			singleSelect: false
		}),
		
		operation = function(value, cellmeta, record){
			var rd = record.data;
			var expire = rd.expire;
			var dlnum = rd.dlnum;
			var a = "";
			if(expire == '0'){
				a = "onclick='CNOA_user_customers_myDelayRecord.dlsq("+rd.did+");'";
				return "<a href='javascript:void(0);' "+a+">" + lang('extensionFile') + "("+dlnum+")</a>";
			}else{
				return lang('extensionFile') + "("+dlnum+")";
			}
		},
		
		mkOther = function(value){
			return "<span ext:qtip='"+value+"'>"+value+"</span>";
		},
		
		colModel = new Ext.grid.ColumnModel({
			defaults: {
				width: 120,
				sortable: true,
				menuDisabled :true
			},
			columns:[
				new Ext.grid.RowNumberer(),
				sm,
				{header: "did", dataIndex: 'did', hidden: true},
				{header: lang('userNumber'), dataIndex: 'max', width: 50},
				{header: lang('version'), dataIndex: 'version'},
				{header: lang('delayTo'), dataIndex: 'endtime', width: 85},
				{header: lang('delayPeople'), dataIndex: 'uname'},
				{header: lang('optTime'), dataIndex: 'posttime'},
				{header: lang('download'), dataIndex: 'did', renderer:operation, width: 78},
				{header: lang('remark'), dataIndex: 'other', width: 380, renderer:mkOther},
				{header: "", dataIndex: 'noIndex', width: 1}
			]
		}),
		
		list = new Ext.grid.GridPanel({
			title: lang('trialDelayRecord'),
			store: store,
			cm: colModel,
			sm: sm,
			border:false,
			hideBorders: true,
			loadMask : {msg: lang('loading')},
			tbar:[
				{
					text:lang('refresh'),
					iconCls:'icon-system-refresh',
					handler:function(button, event){
						_this.mainPanel.store.reload()
					}
				},
				{
					text: '+ ' + lang('delay'),
					handler:function(button, event){
						_this.delayWindow();
					}
				}
			]
		});
		
		this.mainPanel = list;
		this.store = store;
	},

	delayWindow : function(){
		var _this = this, win, formPanel;
		
		var ID_btn_empty = Ext.id();
		
		var postUrl = _this.baseUrl + "&task=addDelayRecord";
				
		var submit = function(config){
			var f = formPanel.getForm();
			if(f.isValid()){
				f.submit({
					url: postUrl,
					method: 'POST',
					params: {cid: _this.cid},
					success: function(form, action){
						CNOA.msg.notice2(action.result.msg);
						_this.store.reload();
						win.close();
					},
					failure: function(form, action){
						CNOA.msg.alert(action.result.msg);
					}
				});
			}else{
				CNOA.miniMsg.alertShowAt(ID_btn_empty, lang('formValid'));
			}
		},
		
		loadData = function(){
			formPanel.getForm().loadRecord(record);
		},
		
		formPanel = new Ext.form.FormPanel({
			hideBorders: true,
			border: false,
			labelWidth: 60,
			labelAlign: 'right',
			bodyStyle: 'padding: 10px',
			defaults: {width: 170, format:'Y-m-d'},
			items:[
				{
					xtype: 'numberfield',
					name: 'max',
					allowBlank: false,
					fieldLabel: lang('userNumber')
				},
				new Ext.form.ComboBox({
					fieldLabel: lang('version'),
					allowBlank:false,
					name: 'version',
					store:  new Ext.data.Store({
						autoLoad: true,
						proxy: new Ext.data.HttpProxy({
							url: this.baseUrl + "&task=getVersionTypeList"
						}),
						reader: new Ext.data.JsonReader({
							root:'data',
							fields: [
								{name:"version"},
								{name:"versionName"}
							]
						})
					}),
					hiddenName: 'version',
					valueField: 'version',
					displayField:'versionName',
					mode: 'local',
					triggerAction:'all',
					forceSelection: true,
					editable: false
				}),
				{
					xtype: 'datefield',
					name: 'endtime',
					allowBlank: false,
					minValue: new Date(),
					fieldLabel: lang('endTime')
				},
				{
					xtype: 'textarea',
					name: 'other',
					height: 100,
					fieldLabel: lang('remark'),
					listeners : {
						afterrender : function(){
							
						}
					}
				}
			]
		});
		
		win = new Ext.Window({
			width: 270,
			height: 268,
			title: lang('applyExtenFile'),
			layout: 'fit',
			autoDestroy: true,
			closeAction: 'close',
			resizable: false,
			items: [formPanel],
			modal: true,
			buttons: [
				{
					text: lang('confirm'),
					id: ID_btn_empty,
					handler: function(){
						submit();
					}.createDelegate(this)
				},
				{
					text: lang('cancel'),
					iconCls: 'icon-dialog-cancel',
					handler: function(){
						win.close();
					}
				}
			]
		});
		win.show();
	},

	dlsq : function(did){
		var _this = this;
		
		ajaxDownload(_this.baseUrl + "&task=downSQ&did="+did);
		
		setTimeout(function(){
			_this.store.reload();
		}, 2000);
	}
}
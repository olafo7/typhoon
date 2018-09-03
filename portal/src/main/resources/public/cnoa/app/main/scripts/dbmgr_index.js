var CNOA_main_dbmgr_indexClass, CNOA_main_dbmgr_index;

/**
* 主面板-列表
*
*/
CNOA_main_dbmgr_indexClass = CNOA.Class.create();
CNOA_main_dbmgr_indexClass.prototype = {
	init : function(from){
		var _this = this;

		this.baseUrl = "index.php?app=main&func=dbmgr&action=index";
		this.from	 = from;
		
		this.ID_btn_edit = Ext.id();
		this.ID_btn_delete = Ext.id();
		this.ID_tree_treeRoot = Ext.id();

		this.fields = [
			{name:"id"},
			{name:"backname"},
			{name:"date"},
			{name:"type"},
			{name:"size"},
			{name:"file"}
		];
		this.store = new Ext.data.Store({
			proxy:new Ext.data.HttpProxy({url: this.baseUrl+"&task=getJsonList", disableCaching: true}),   
			reader:new Ext.data.JsonReader({totalProperty:"total",root:"data", fields: this.fields, unread: "unread"})
		});

		this.sm = new Ext.grid.CheckboxSelectionModel({
			singleSelect:false
		});

		this.store.load({params:{from: _this.from}});

		this.colModel = new Ext.grid.ColumnModel([
			new Ext.grid.RowNumberer(),
			{header: "id", dataIndex: 'id', hidden: true},
			{header: lang('backupFileName'), dataIndex: 'backname', width: 250, sortable: true, menuDisabled :true},
			{header: lang('backupTime'), dataIndex: 'date', width: 190, sortable: false, menuDisabled :true},
			{header: lang('backupType'), dataIndex: 'type', width: 110, sortable: false,menuDisabled :true},
			{header: lang('size'), dataIndex: 'size', width: 100, sortable: false,menuDisabled :true},
			{header: lang('opt'), dataIndex: 'id', width: 150, sortable: false,menuDisabled :true, renderer:this.makeOperator.createDelegate(this)},
			{header: "", dataIndex: 'noIndex', width: 1, menuDisabled: true,resizable: false}
		]);

		this.grid = new Ext.grid.GridPanel({
			stripeRows : true,
			store: this.store,
			loadMask : {msg: lang('waiting')},
			cm: this.colModel,
			hideBorders: true,
			border: false,
			region: "center",
			tbar : new Ext.Toolbar({
				items: [
					{
						iconCls: 'icon-system-refresh',
						text : lang('refresh'),
						handler : function(button, event) {
							_this.store.reload();
						}.createDelegate(this)
					},"-",
					{
						iconCls: 'icon-system-refresh',
						text: lang('backupData'),
						handler: function(button, event) {
							_this.showExport();
						}.createDelegate(this)
					},"-",
					{
						iconCls: 'icon-banshou',
						text: lang('repairDatabase'),
						handler : function(button, event) {
							_this.repairDb(button);
						}.createDelegate(this)
					},"-",
					{
						iconCls: 'icon-system-refresh',
						text : lang('import2'),
						handler : function(button, event) {
							_this.showUpload();
						}.createDelegate(this)
					},
					"->",{xtype: 'cnoa_helpBtn', helpid: 23}
				]
			})
		});

		this.mainPanel = new Ext.Panel({
			collapsible:false,
			hideBorders: true,
			border: false,
			layout:'border',
			autoScroll: false,
			items: [this.grid]//, this.bottomPanel
		});
	},
	
	makeOperator : function(value, cellmeta, record, rowIndex, columnIndex, color_store){
		var rd = record.data;
		
		var h  = "<div>";
			h += "<a href='javascript:void(0);' onclick='CNOA_main_dbmgr_index.deleteBackup("+value+");'>" + lang('del') + "</a>";
			h += "&nbsp;&nbsp;";
			h += "<a href='javascript:void(0);' onclick='CNOA_main_dbmgr_index.restore("+value+");' ext:qtip='" + lang('thisTimeToRestore') + "<br>" + lang('pleaseJSCZ') + "'>" + lang('restore') + "</a>";
			h += "&nbsp;&nbsp;";
			h += rd.file;
			h += "</div>";
		return h;
	},
	
	showExport : function(){
		var _this = this;
		
		var ID_isBackSQL  = Ext.id();
		var ID_isBackFILE = Ext.id();
		
		var win = new Ext.Window({
			width: 340,
			height: 185,
			title: lang('backupSiteData'),
			resizable: false,
			modal: true,
			layout: "fit",
			items: [
				{
					xtype: "panel",
					border: false,
					bodyStyle: "padding:10px",
					items: [
						{
							xtype: "checkbox",
							id: ID_isBackSQL,
							boxLabel: lang('backupDatabase'),
							checked: true
						},
						{
							xtype: "checkbox",
							id: ID_isBackFILE,
							boxLabel: lang('backupOtherData')
						},
						{
							xtype: "displayfield",
							value: "<span class='cnoa_color_gray'>" + lang('recommend1') + "</span>"
						},
						{
							xtype: "displayfield",
							value: "<span class='cnoa_color_gray'>" + lang('recommend2') + "</span>"
						}
					]
				}
			],
			buttons: [
				{
					text: lang('startBackup'),
					handler: function(btn){
						var isBackSQL  = Ext.getCmp(ID_isBackSQL).getValue();
						var isBackFILE = Ext.getCmp(ID_isBackFILE).getValue();
						if(!isBackSQL && !isBackFILE){
							CNOA.miniMsg.alertShowAt(btn, lang('pleaseSelectBackup'));
						}else{
							_this.exports({sql: isBackSQL, file:isBackFILE});
							win.close();
						}
					}
				},{
					text: lang('cancel'),
					handler: function(){
						win.close();
					}
				}
			]
		}).show();
	},
	
	showUpload : function(){
		var _this = this;
		
		var formPanel = new Ext.FormPanel({
			fileUpload: true,
			autoScroll: false,
			waitMsgTarget: true,
			hideBorders: true,
			border: false,
			items: [
				new Ext.Panel({
					border: false,
					bodyStyle: "padding: 5px;",
					items: [
						{
							xtype: 'fileuploadfield',
							name: 'file',
							allowBlank: false,
							blankText: lang('uploadFiles'),
							regex : /^[0-9]{14,14}[1-3]{1,1}[0-9]{15,15}\.zip$/,
							regexText: lang('fileNameBuFuHe'),
							buttonCfg: {
								text: lang('browseFiles')
							},
							hideLabel : true,
							width: 370
						},
						{
							xtype: "displayfield",
							value: "<span class='cnoa_color_gray'>" + lang('attention') + "<br>" + lang('uploadFilesMust') + "<br>&nbsp;&nbsp;&nbsp;&nbsp;" + lang('file') + "。<br>" + lang('fileNameFormat') + ".zip<br>&nbsp;&nbsp;&nbsp;&nbsp;" + lang('eg') + ":201008081201143874951293445.zip</span>"
						}
					]
				})
			]
		});
		
		var win = new Ext.Window({
			width: 400,
			height: 185,
			title: lang('importBackupFile'),
			resizable: false,
			modal: true,
			layout: "fit",
			items: [formPanel],
			buttons: [
				{
					text: lang('startUpload'),
					handler: function(btn){
						if (formPanel.getForm().isValid()) {
							formPanel.getForm().submit({
								url: _this.baseUrl + "&task=upBackupFile",
								waitMsg: lang('waiting'),
								params: {},
								success: function(form, action) {
									_this.store.reload();
									win.close();
								},
								failure: function(form, action) {
									CNOA.msg.alert(action.result.msg, function(){
										win.close();
									});
								}
							});
						}
					}
				},{
					text: lang('cancel'),
					handler: function(){
						win.close();
					}
				}
			]
		}).show();
	},

	exports : function(config){
		var _this = this;

		//CNOA.msg.cf("确定要备份当前的数据库吗？", function(btn){
		//	if(btn == "yes"){
		Ext.MessageBox.show({
			msg: lang('beingBackUP') + '...',
			title: lang('waiting'),
			width: 300,
			wait: true,
			waitConfig: {interval:200}
		});
		Ext.Ajax.request({  
			url: _this.baseUrl + "&task=export",
			params: config,
			method: 'POST',
			timeout: 9999999,
			success: function(r) {						
				var result = Ext.decode(r.responseText);
				Ext.MessageBox.hide();
				CNOA.msg.alert(result.msg, function(){
					_this.store.reload();
				});
			}.createDelegate(this),
			failure : function(r){
				Ext.MessageBox.hide();
				CNOA.msg.alert(lang('optFail'));
			}
		});
		//	}
		//});
	},
	
	deleteBackup : function(id){
		var _this = this;
		
		CNOA.msg.cf(lang('sureDelData'), function(btn){
			if(btn == "yes"){
				Ext.Ajax.request({  
					url: _this.baseUrl + "&task=delete",
					params: {id: id},
					method: 'POST',
					success: function(r) {
						var result = Ext.decode(r.responseText);
						CNOA.msg.alert(result.msg, function(){
							_this.store.reload();
						});
					}.createDelegate(this),
					failure : function(r){
						var result = Ext.decode(r.responseText);
						CNOA.msg.alert(result.msg);
					}
				});
			}
		});
	},
	
	download : function(id){
		var f = document.createElement("iframe");
		f.style.width = "0px";
		f.style.height = "0px";
		f.style.display = "none";
		f.src = this.baseUrl + "&task=download&id="+id;
		document.body.appendChild(f);
		//window.open(this.baseUrl + "&task=download&id="+id);
	},
	
	restore : function(id){
		var _this = this;

		CNOA.msg.cf(lang('sureRestore') + "<br>" + lang('qJSCZBQueBao'), function(btn){
			if(btn == "yes"){
				Ext.MessageBox.show({
					msg: lang('recoverData') + '...',
					title: lang('waiting'),
					width: 300,
					wait: true,
					waitConfig: {interval:200}
				});
				Ext.Ajax.request({  
					url: _this.baseUrl + "&task=restore",
					params: {id: id},
					method: 'POST',
					timeout: 9999999,
					success: function(r) {
						Ext.MessageBox.hide();
						var result = Ext.decode(r.responseText);
						if(result.failure){
							CNOA.msg.alert(result.msg);
						}
						if(result.success){
							CNOA.msg.alert(result.msg + "<br />" + lang('allUserWillExit'), function(){
								location.href = "index.php?app=main&func=passport&action=logout";
							});
						}
					}.createDelegate(this),
					failure : function(r){
						Ext.MessageBox.hide();
						var result = Ext.decode(r.responseText);
						CNOA.msg.alert(result.msg);
					}
				});
			}
		});
	},
	
	repairDb : function(btn){
		var _this = this;
		
		CNOA.miniMsg.cfShowAt(btn, lang('suerRepairDb'), function(){
			Ext.Ajax.request({  
				url: _this.baseUrl + "&task=repairDb",
				params: {id: id},
				method: 'POST',
				timeout: 9999999,
				success: function(r) {
					Ext.MessageBox.hide();
					var result = Ext.decode(r.responseText);
					if(result.failure){
						CNOA.msg.alert(result.msg);
					}
					if(result.success){
						CNOA.msg.alert(result.msg);
					}
				}.createDelegate(this),
				failure : function(r){
					Ext.MessageBox.hide();
					var result = Ext.decode(r.responseText);
					CNOA.msg.alert(result.msg);
				}
			});
		});
	}
}

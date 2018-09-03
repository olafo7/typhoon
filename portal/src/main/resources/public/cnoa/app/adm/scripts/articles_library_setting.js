/**
 * 全局变量
 */
var CNOA_adm_articles_librarySettingIndexClass, CNOA_adm_articles_librarySettingIndex;

CNOA_adm_articles_librarySettingIndexClass = CNOA.Class.create();
CNOA_adm_articles_librarySettingIndexClass.prototype = {
	init:function(){
		var _this = this;
		this.edit_id = 0;
		this.type = "add";
		
		_this.baseUrl = "index.php?app=adm&func=articles&action=library";
		
		this.fields = [
			{name: "id"},
			{name: "name"},
			{name: "typeName"},
			{name: "deptName"},
			{name: "adminNames"},
			{name: "transportNames"}
		];
		
		this.store = new Ext.data.Store({
			proxy:new Ext.data.HttpProxy({url: this.baseUrl+"&task=list", disableCaching: true}),   
			reader:new Ext.data.JsonReader({totalProperty:"total",root:"data", fields: this.fields})
		});
		
		this.sm = new Ext.grid.CheckboxSelectionModel({
			singleSelect: false
		});
		
		this.store.load();
		
		this.colModel = new Ext.grid.ColumnModel([
			new Ext.grid.RowNumberer(),
			this.sm,
			{header: "id", dataIndex: 'id', hidden: true},
			{header: lang('offSupLi'), dataIndex: 'name', width: 100, sortable: true, menuDisabled :true},
			{header: lang('officeSupType'), dataIndex: 'typeName', width: 300, sortable: true, menuDisabled :true},
			{header: lang('belongDept'), dataIndex: 'deptName', width: 100, sortable: true, menuDisabled :true},
			{header: lang('warehouseMgr'), dataIndex: 'adminNames', width: 140, sortable: true, menuDisabled :true},
			{header: lang('itemDispatcher'), dataIndex: 'transportNames', width: 140, sortable: true, menuDisabled :true},
			{header: "", dataIndex: 'noIndex', width: 1, menuDisabled: true,resizable: false}
		]);
		
		this.pagingBar = new Ext.PagingToolbar({
			displayInfo:true,emptyMsg: lang('pagingToolbarEmptyMsg'),displayMsg:lang('showDataTotal2'),   
			store: this.store,
			pageSize:15
		})
		
		this.list = new Ext.grid.GridPanel({
			border:false,
			store:this.store,
       		stripeRows : true,
			loadMask : {msg: lang('waiting')},
			cm: this.colModel,
			sm: this.sm,
			hideBorders: true,
			listeners:{
				"rowdblclick":function(button, event){
					var rows = _this.list.getSelectionModel().getSelections();
					_this.type = "edit";
					_this.edit_id = rows[0].get("id");
					_this.newLibrary();
				}
			},
			tbar:[
				{
					text:lang('refresh'),
					iconCls:'icon-system-refresh',
					handler:function(button, event){
						_this.store.reload()
					}
				},"-",
				{
					text: lang('addNewOffSupLib'),
					iconCls: 'icon-utils-s-add',
					handler:function(){
						_this.type = "add";
						_this.newLibrary();
					}
				},"-",
				{
					text: lang('editLibrary'),
					iconCls: 'icon-utils-s-edit',
					handler:function(button, event){
						var rows = _this.list.getSelectionModel().getSelections();
						if (rows.length == 0) {
							CNOA.miniMsg.alertShowAt(button, lang('mustSelectOneRow'));
						} else {
							_this.type = "edit";
							_this.edit_id = rows[0].get("id");
							_this.newLibrary();
						}
					}.createDelegate(this)
				},"-",
				{
					text: lang('del'),
					iconCls: 'icon-utils-s-delete',
					handler:function(button){
						_this.deleteList(button);
					}
				}
				,"<span class='cnoa_color_gray'>" + lang('ctrlShiftSelect') + "</span> <span class='cnoa_color_red'>" + lang('addOfficeSupType') + " ></span>",
				"->",{xtype: 'cnoa_helpBtn', helpid: 130}
			],
			bbar: this.pagingBar
		})
		
		this.center = new Ext.Panel({
			region: 'center',
			layout: "fit",
			items: [this.list]
		})
		
		this.mainPanel = new Ext.Panel({
			collapsible:false,
			hideBorders: true,
			border: false,
			layout:'border', 
			autoScroll: true,
			items:[this.center]
		})
	},
	
	newLibrary:function(){
		var _this = this;
		
		var ADMINOAUID = Ext.id();
		var OAUIDTRF = Ext.id();
		this.ID_textarea_deptNames = Ext.id();
		this.ID_button_deptNames   = Ext.id();
		
		this.baseField = [
			{
				xtype: "fieldset",
				title: lang('addNewLibSup'),
				defaults: {
					style: {
		                marginBottom: '0px'
		            },
					border:false
				},
				items:[
					{
						xtype:"panel",
						layout:"form",
						width:550,
						defaults: {
							style: {
				                marginBottom: '10px'
				            },
							border:false
						},
						items:[
							{
								xtype:"cnoa_textfield",
								fieldLabel: lang('houseName'),
								allowBlank:false,
								name: "name",
								width: 450
							},
							
							{
								xtype: "hidden",
								name: "deptID"
							},
							{
								xtype: "panel",
								layout: "form",
								border: false,
								items: [
									{
										xtype: "triggerForDept",
										name: "deptName",
										allowBlank:false,
										deptListUrl: _this.baseUrl + "&task=getStructTree",
										fieldLabel: lang('belongDept'),
										width: 450,
										listeners:{
											"selected":function(th, node){
												if(node.attributes.selfid != ""){
													this.formPanel.getForm().findField("deptID").setValue(node.attributes.selfid);
												}
											}.createDelegate(this)
										}
									}
								]
							},/*
							{
								xtype: 'hidden',
								name: "deptIds"
							},
							{
								xtype: 'textarea',
								width:450,
								height: 50,
								fieldLabel : "所属部门",
								id: this.ID_textarea_deptNames,
								name: "deptNames",
								readOnly: true
							},
							{
								xtype: "deptMultipleSelector",
								style: "margin-left:85px; margin-bottom:4px; margin-top:-10px;",
								autoWidth: true,
								id: this.ID_button_deptNames,
								deptListUrl : _this.baseUrl + "&task=getStructTree",
								//loader: CNOA_main_user_list.treeLoader,
								listeners:{
									"selected" : function(th, textString, idString){
										_this.formPanel.getForm().findField("deptNames").setValue(textString);
										_this.formPanel.getForm().findField("deptIds").setValue(idString);
									},
									"load" : function(th){
										th.setSelectedIds(_this.formPanel.getForm().findField("deptIds").getValue());
									}
								}
							},
							new Ext.BoxComponent({
								autoEl: {
									tag: 'div',
									style: "margin-left:80px;margin-top:5px;margin-bottom:5px;color:#676767;",
									html: '注意:所属部门为空时，表示所有部门均可以申请该用品库的用品'
								}
							}),*/
							{
								xtype: "textarea",
								width: 450,
								readOnly: true,
								fieldLabel: lang('warehouseMgr'),
								allowBlank: false,
								blankText: lang('pleaseSelectKuAdmin'),
								name: "adminNames"
							},
							{
								xtype: "hidden",
								name: "adminIDs"
							},
							{
								xtype: "btnForPoepleSelector",
								autoWidth: true,
								anchor: "-10",
								dataUrl: _this.baseUrl+"&task=getAllUserListsInPermitDeptTree",
								style: "margin-left:85px;margin-bottom:4px;margin-top:-10px;",
								text: lang('select'),
								listeners: {
									"selected" : function(th, data){
										var names = new Array();
										var uids = new Array();
										if (data.length>0){
											for (var i=0;i<data.length;i++){
												names.push(data[i].uname);
												uids.push(data[i].uid);
											}
										}
										_this.formPanel.getForm().findField("adminNames").setValue(names.join(","));
										_this.formPanel.getForm().findField("adminIDs").setValue(uids.join(","));
									},
			
									"onrender" : function(th){
										th.setSelectedUids(_this.formPanel.getForm().findField("adminIDs").getValue().split(","));
									}
								}
							},
							new Ext.BoxComponent({
								autoEl: {
									tag: 'div',
									style: "margin-left:80px;margin-top:5px;margin-bottom:5px;color:#676767;",
									html: lang('noteSuchAs')
								}
							}),
							{
								xtype: "textarea",
								height: 45,
								width : 450,
								readOnly: true,
								fieldLabel: lang('itemDispatcher'),
								allowBlank: false,
								blankText: lang('selectItemDispatcher'),
								name: "transportNames"
							},
							{
								xtype: "hidden",
								name: "transportIDs"
							},
							{
								xtype: "btnForPoepleSelector",
								autoWidth: true,
								anchor: "-10",
								dataUrl: _this.baseUrl+"&task=getAllUserListsInPermitDeptTree",
								style: "margin-left:85px;margin-bottom:4px;margin-top:-10px;",
								text: lang('select'),
								listeners: {
									"selected" : function(th, data){
										var names = new Array();
										var uids = new Array();
										if (data.length>0){
											for (var i=0;i<data.length;i++){
												names.push(data[i].uname);
												uids.push(data[i].uid);
											}
										}
										_this.formPanel.getForm().findField("transportNames").setValue(names.join(","));
										_this.formPanel.getForm().findField("transportIDs").setValue(uids.join(","));
									},
			
									"onrender" : function(th){
										th.setSelectedUids(_this.formPanel.getForm().findField("transportIDs").getValue().split(","));
									}
								}
							},
							new Ext.BoxComponent({
								autoEl: {
									tag: 'div',
									style: "margin-left:80px;margin-top:5px;margin-bottom:5px;color:#676767;",
									html: lang('noteSuppliesSelectDispatcher')
								}
							})
						]
					}
				]
			}
		];
		
		this.formPanel = new Ext.form.FormPanel({
			hideBorders: true,
			border: false,
			waitMsgTarget: true,
			layout: "border",
			labelWidth: 80,
			labelAlign: 'right',
			items:[
				{
					xtype: "panel",
					border: false,
					bodyStyle: "padding:10px",
					layout: "form",
					region: "center",
					autoScroll: true,
					items: [this.baseField]
				}
			]
		});
		
		this.addWindow = new Ext.Window({
			title: lang('addNewOffSupLib'),
			width: 620,
			height: makeWindowHeight(480),
			modal:true,
			layout:"fit",
			resizable:false,
			border:false,
			items:[this.formPanel],
			buttons:[
				{
					text: lang('save'),
					iconCls: 'icon-btn-save',
					handler:function(){
						_this.submitForm()
					}
				},
				{
					text:lang('close'),
					iconCls: 'icon-dialog-cancel',
					handler : function() {
						_this.addWindow.close()
					}
				}
			]
		}).show();
		
		if(this.type == "edit"){
			_this.loadFormData();
		}
	},	
	
	loadFormData : function(){
		var _this = this;
		
		this.formPanel.getForm().load({
			url: _this.baseUrl+"&task=list&edit=edit",
			params: {id: _this.edit_id},
			method:'POST',
			waitMsg: lang('waiting'),
			success: function(form, action){		
			},
			failure: function(form, action){ 
				CNOA.msg.alert(action.result.msg, function(){
					_this.addWindow.close();
				});
			}.createDelegate(this)
		});
	},
	
	submitForm : function(){
		var _this = this;

		if (_this.formPanel.getForm().isValid()) {
			_this.formPanel.getForm().submit({
				url: _this.baseUrl+"&task="+_this.type+"&id="+_this.edit_id,
				waitMsg: lang('waiting'),
				method: 'POST',	
				success: function(form, action) {
					CNOA.msg.notice(action.result.msg, lang('admSuppMgr'));
					_this.addWindow.close()
					_this.store.load()
				}.createDelegate(this),
				failure: function(form, action) {
					CNOA.msg.alert(action.result.msg);
				}.createDelegate(this)
			});
		}
	},
	
	deleteList:function(button){
		var _this = this;				
		var rows = this.list.getSelectionModel().getSelections(); // 返回值为 Record 数组 
		if (rows.length == 0) {
			CNOA.miniMsg.alertShowAt(button, lang('mustSelectOneRow'));

		} else {
			CNOA.msg.cf(lang('delAlsoDelFollow'), function(btn) {
				if (btn == 'yes') {
					if (rows) {
						var ids = "";
						for (var i = 0; i < rows.length; i++) {
							ids += rows[i].get("id") + ",";
						}
						_this.deleteData(ids);
					}
				}
			});
		}
	},
	
	deleteData:function(ids){
	    var	_this = this;
		Ext.Ajax.request({
			url: this.baseUrl + "&task=delete",
			method: 'POST',
			params: { ids: ids },
			success: function(r) {
				var result = Ext.decode(r.responseText);
				if(result.success === true){
					CNOA.msg.notice(result.msg, lang('admSuppMgr'));
					_this.store.reload();
				}else{
					CNOA.msg.alert(result.msg);
				}
			}
		});
	}	
}
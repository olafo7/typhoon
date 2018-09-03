//定义全局变量：
var CNOA_adm_articles_editTypeIndexClass, CNOA_adm_articles_editTypeIndex;

/**
 * 主面板-列表
 *
 */
CNOA_adm_articles_editTypeIndexClass = CNOA.Class.create();
CNOA_adm_articles_editTypeIndexClass.prototype = {
	init: function(){
		var _this = this;
		
		this.baseUrl = "index.php?app=adm&func=articles&action=info";
		
		this.libraryComboBoxDataStore = new Ext.data.Store({
			proxy: new Ext.data.HttpProxy({
				url: _this.baseUrl+"&task=getArticlesLibraryList"
			}),
			reader: new Ext.data.JsonReader({
				root:'data',
				fields: [
					{name:"libraryID", mapping: 'id'},
					{name:"name"}
				]
			})
		});
		
		this.libraryComboBoxDataStore.load();
		
		this.sortStore = new Ext.data.Store({
			proxy: new Ext.data.HttpProxy({
				url: this.baseUrl + "&task=getArticlesLibraryList",
				method: 'POST'
			}),
			reader: new Ext.data.JsonReader({
				root: 'data',
				fields: [{
					name: 'libraryID',
					mapping: 'id'
				}, {
					name: 'name'
				}]
			})
		});
		
		this.sortStore.load();
		
		this.sortCombo = new Ext.form.ComboBox({
			autoLoad: true,
			triggerAction: 'all',
			selectOnFocus: true,
			editable: false,
			store: this.sortStore,
			valueField: 'libraryID',
			hiddenName: 'libraryID',
			displayField: 'name',
			allowBlank: false,
			mode: 'local',
			forceSelection: true
		});
		
		this.fields = [
			{name: "id"},
			{name: "libraryID"},
			{name: "libraryName"},
			{name: "name"},
			{name : "truename"}
		];
		
		this.store = new Ext.data.GroupingStore({
			autoLoad: true,
			proxy: new Ext.data.HttpProxy({
				url: this.baseUrl + '&task=typeList'
			}),
			reader: new Ext.data.JsonReader({
				totalProperty: "total",
				root: "data",
				fields: this.fields
			}),
			listeners: {
				'update': function(thiz, record, operation) {
					var typeData = record.data;
					if (operation == Ext.data.Record.EDIT) {//判断update时间的操作类型是否为 edit 该事件还有其他操作类型比如 commit,reject   
						_this.submit(typeData);
					}
				}
			},
			sortInfo: {
			    field: 'id',
			    direction: 'DESC'
			}
		});
		
		this.pagingBar = new Ext.PagingToolbar({
			displayInfo:true,emptyMsg:lang('pagingToolbarEmptyMsg'),displayMsg:lang('showDataTotal2'),   
			store: this.store,
			pageSize:15
		})
		
		this.editor = new Ext.ux.grid.RowEditor({
			cancelText: lang('cancel'),
			saveText: lang('update'),
			errorSummary: false
		});
		
		this.sm = new Ext.grid.CheckboxSelectionModel({
			//singleSelect:false
		});
		
		this.cm = [new Ext.grid.RowNumberer(), this.sm,
		{
			header: '',
			dataIndex: 'id',
			hidden: true
		},
		{
			header: '',
			dataIndex: 'libraryID',
			hidden: true
		}, 
		{
			header: lang('sortName'),
			dataIndex: 'name',
			width: 200,
			sortable: true,
			editor: {
				xtype: 'textfield',
				allowBlank: false
			}
		},
		{
			id: 'libraryName',
			header: lang('belongLibraryName'),
			dataIndex: 'libraryName',
			width: 250,
			sortable: true,
			editor: this.sortCombo,
            renderer: function(v){
                var recor;
                _this.sortStore.each(function(r){
                    if(r.get('libraryID')==v){
                        recor=r;
                        return;
                    }
                });
                return recor==null?v:recor.get("name");
            }
		}];
		
		this.grid = new Ext.grid.GridPanel({
			store: this.store,
			width: 600,
			plain: false,
			loadMask : {msg: lang('waiting')},
			region: "center",
			border: false,
			autoScroll: true,
       		stripeRows : true,
			plugins: [this.editor],
			columns: this.cm,
			view: new Ext.grid.GroupingView({
				markDirty: false
			}),
			tbar: [
				{
					handler : function(button, event) {
						_this.store.reload();
					}.createDelegate(this),
					iconCls: 'icon-system-refresh',
					text : lang('refresh')
				},
				{
					text:lang('addSuppliesType'),
					iconCls: 'icon-utils-s-add',
					handler:function(){
						_this.libraryComboBoxDataStore.load();
						_this.newType();
					}
				},
				{
					iconCls: 'icon-utils-s-delete',
					text: lang('del'),
					tooltip: lang('delRecord'),
					handler: function(btn){
						_this.editor.stopEditing();
						
						var rows = _this.grid.getSelectionModel().getSelections(); // 返回值为 Record 数组 
						if (rows.length == 0) {
							CNOA.miniMsg.alertShowAt(btn, lang('mustSelectOneRow'));
						} else {
							CNOA.msg.cf(lang('sureWantDelType'), function(btn) {
								if (btn == 'yes') {
									if (rows) {
										var ids = "";
										for (var i = 0; i < rows.length; i++) {
											ids += rows[i].get("id") + ",";
											
											var r = rows[i]
											_this.store.remove(r);
										}
										_this.deleteList(ids);
									}
								}
							});
						}
					}
				},
				"<span class='cnoa_color_gray'>" + lang('doubleClickEdit') + "</span>",
				'->',{xtype: 'cnoa_helpBtn', helpid: 129}
			],
			bbar: this.pagingBar
		});
		
		this.mainPanel = new Ext.Panel({
			collapsible: false,
			hideBorders: true,
			border: false,
			layout: 'border',
			autoScroll: false,
			items: [this.grid]
		});
	},
	
	newType: function(){
		var _this = this;

		this.baseField = [
			{
				xtype: "fieldset",
				title: lang('addNewCategories'),
				defaults: {
					style: {
		                marginBottom: '-10px'
		            },
					border:false
				},
				items:[
					{
						xtype:"panel",
						layout:"form",
						width:350,
						defaults: {
							style: {
				                marginBottom: '10px'
				            },
							border:false
						},
						items:[
							{
								xtype: "panel",
								layout: "form",
								border: false,
								items: [
									new Ext.form.ComboBox({
										fieldLabel: lang('offSupLi'),
										name: 'libraryID',
										allowBlank:false,
										store: _this.libraryComboBoxDataStore,
										hiddenName: 'libraryID',
										valueField: 'libraryID',
										displayField:'name',
										mode: 'local',
										width: 230,
										triggerAction:'all',
										forceSelection: true,
										editable: false,
										listeners:{
											select : function(th, record, index){
												
											}.createDelegate(this),
											change : function(){
												
											}.createDelegate(this)
										}
									})
								]
							},
							{
								xtype:"cnoa_textfield",
								fieldLabel: lang('cetegoryName'),
								allowBlank:false,
								name: "name",
								width: 230
							}
						]
					}
				]
			}
		]
		
		this.typeFormPanel = new Ext.form.FormPanel({
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
		
		this.newTypeWindow = new Ext.Window({
			title: lang('addNewOfficeSupplies'),
			width: 420,
			height: 200,
			modal:true,
			layout:"fit",
			resizable:false,
			border:false,
			items:[this.typeFormPanel],
			buttons:[
				{
					text: lang('save'),
					iconCls: 'icon-btn-save',
					handler:function(){
						_this.typeSubmitForm()
					}
				},
				{
					text:lang('close'),
					iconCls: 'icon-dialog-cancel',
					handler : function() {
						_this.newTypeWindow.close()
					}
				}
			]
		}).show();
	},	
	
	typeSubmitForm:function(){
		var _this = this;

		if (_this.typeFormPanel.getForm().isValid()) {
			_this.typeFormPanel.getForm().submit({
				url: _this.baseUrl+"&task=typeAdd",
				waitMsg: lang('waiting'),
				method: 'POST',	
				success: function(form, action) {
					CNOA.msg.notice(action.result.msg, lang('admSuppliesType'));
					_this.store.reload();
					this.newTypeWindow.close();
				}.createDelegate(this),
				failure: function(form, action) {
					CNOA.msg.alert(action.result.msg);
				}.createDelegate(this)
			});
		}
	},
	
	submit : function(typeData){
		var _this = this;
		Ext.Ajax.request({
			url: this.baseUrl + '&task=editTypeData',
			params: typeData,
			method: "POST",
			success: function(r, opts) {
				var result = Ext.decode(r.responseText);
				if(result.success === true){
					CNOA.msg.notice(result.msg, lang('admSuppliesType'));
				}else{
					CNOA.msg.alert(result.msg, function(){
						_this.store.reload();
					});
				}
			},
			failure: function(response, opts) {
				CNOA.msg.alert(result.msg, function(){
					_this.store.reload();
				});
			}
		});
	},
	
	deleteList : function(ids){
		var _this = this;
		
		Ext.Ajax.request({  
			url: this.baseUrl + "&task=deleteType",
			method: 'POST',
			params: { ids: ids },
			success: function(r) {
				var result = Ext.decode(r.responseText);
				if(result.success === true){
					CNOA.msg.alert(result.msg, function(){
						_this.store.reload();
					});
				}else{
					CNOA.msg.alert(result.msg);
				}
			}
		});
	}
}


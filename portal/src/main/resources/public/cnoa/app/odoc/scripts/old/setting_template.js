/**
 * 全局变量
 */


var CNOA_odoc_setting_templateClass, CNOA_odoc_setting_template;
CNOA_odoc_setting_templateClass = CNOA.Class.create();
CNOA_odoc_setting_templateClass.prototype = {
	init : function(){
		var _this = this;
		
		this.baseUrl = "index.php?app=odoc&func=setting&action=template";
		
		this.storeBar = {
			title : "",
			type : 0
		};
		
		this.dsc = Ext.data.Record.create([
		{
			name: 'title',
			type: 'string'
		}]);
		
		this.fields = [
			{name : "id"},
			{name : "title"},
			{name : "type"},
			{name : "postname"},
			{name : "order"},
			{name : "fromType"}
		];
		
		this.typeListStore = new Ext.data.Store({
			proxy: new Ext.data.HttpProxy({
				url: _this.baseUrl+"&task=getTypeList"
			}),
			reader: new Ext.data.JsonReader({
				root:'data',
				fields: [
					{name : "tid"},
					{name : "title"}
				]
			})
		});
		
		this.typeListStore.load();
		
		this.typeCombo = new Ext.form.ComboBox({
			allowBlank : false,
			autoLoad: true,
			triggerAction: 'all',
			selectOnFocus: true,
			editable: false,
			store: this.typeListStore,
			valueField: 'tid',
			displayField: 'title',
			mode: 'local',
			forceSelection: true
		});
		
		this.store = new Ext.data.GroupingStore({
			autoLoad: true,
			proxy: new Ext.data.HttpProxy({
				url: this.baseUrl + '&task=getJsonData'
			}),
			reader: new Ext.data.JsonReader({
				totalProperty: "total",
				root: "data",
				fields: this.fields
			}),
			listeners: {
				'update': function(thiz, record, operation) {
					var user = record.data;
					if (operation == Ext.data.Record.EDIT) {//判断update时间的操作类型是否为 edit 该事件还有其他操作类型比如 commit,reject   
						_this.submit(user);
					}
				}
			}
		});
		
		this.pagingBar = new Ext.PagingToolbar({
			displayInfo:true,   
			store: this.store,
			pageSize:15,
			listeners:{
				"beforechange" : function(th, params){
					Ext.apply(params, _this.storeBar);
				}
			}
		});
		
		this.editor = new Ext.ux.grid.RowEditor({
			cancelText: lang('cancel'),
			saveText: lang('update'),
			errorSummary: false
		});
		
		this.sm = new Ext.grid.CheckboxSelectionModel({
			
		});
		
		this.cm = [new Ext.grid.RowNumberer(), this.sm,
		{
			header: '',
			dataIndex: 'id',
			hidden: true
		}, {
			header: '模板名称',
			dataIndex: 'title',
			width: 300,
			sortable: false,
			editor: {
				xtype: 'textfield',
				allowBlank: false
			}
		}, {
			header: '所属类别',
			dataIndex: 'fromType',
			width: 60,
			sortable: false,
			editor: new Ext.form.ComboBox({
				name: 'fromType',
				store: new Ext.data.SimpleStore({
					fields: ['value', 'fromType'],
					data: [['1', "发文"], ['2', "收文"]]
				}),
				hiddenName: 'fromType',
				valueField: 'value',
				displayField: 'fromType',
				mode: 'local',
				allowBlank: false,
				triggerAction: 'all',
				forceSelection: true,
				editable: false
			}),
			renderer: function(v){
				if(v == '1'){
					var h = "发文";
				}
				if(v == '2'){
					var h = "收文";
				}
				return h;
			}
		}, {
			header: '类型',
			dataIndex: 'type',
			width: 200,
			sortable: false,
			editor: this.typeCombo,
            renderer: function(v){
                var recor;
                _this.typeListStore.each(function(r){
                    if(r.get('tid')==v){
                        recor=r;
                        return;
                    };
                });
                return recor==null?"":recor.get("title");
            }
		}, {
			header: '发布人',
			dataIndex: 'postname',
			width: 80,
			sortable: false
		}, {
			header : lang('order'),
			dataIndex : 'order',
			width : 100,
			sortable : false,
			editor: {
				xtype : 'textfield',
				allowBlank : true,
				regex: /^\+?[1-9][0-9]*$/i,
				regexText: '必须设置为正整数'
			}
		},
		{
			header : lang('opt'),
			dataIndex : 'id',
			width : 100,
			sortable : false,
			renderer:this.add.createDelegate(this)
		}];
		
		this.grid = new Ext.grid.GridPanel({
			stripeRows : true,
			store: this.store,
			plain: false,
			loadMask : {msg: lang('waiting')},
			region: "center",
			border: false,
			autoScroll: true,
			plugins: [this.editor],
			columns: this.cm,
			view: new Ext.grid.GroupingView({
				markDirty: false
			}),
			listeners:{
				cellclick:function(th, rowNum, columnNum, e){
					if(columnNum == 7){
						return false;
					}
				}
			},
			tbar: [
				{
					handler : function(button, event) {
						_this.store.reload();
					}.createDelegate(this),
					iconCls: 'icon-system-refresh',
					text : lang('refresh')
				},'-',
				{
					handler : function(button, event) {
						var e = new _this.dsc({
   							name: ''
   						});
   						_this.editor.stopEditing();
   						_this.store.insert(0, e);
   						_this.grid.getView().refresh();
   						_this.grid.getSelectionModel().selectRow(0);
   						_this.editor.startEditing(0);
					}.createDelegate(this),
					iconCls: 'icon-system-refresh',
					text : lang('add')
				},'-',
				{
					text : lang('del'),
					iconCls: 'icon-utils-s-delete',
					handler : function(button, event) {
						var rows = _this.grid.getSelectionModel().getSelections(); // 返回值为 Record 数组 
						if (rows.length == 0) {
							CNOA.miniMsg.alertShowAt(button, lang('mustSelectOneRow'));
						} else {
							CNOA.miniMsg.cfShowAt(button, lang('confirmToDelete'), function(){
								if (rows) {
									var ids = "";
									for (var i = 0; i < rows.length; i++) {
										ids += rows[i].get("id") + ",";
									}
									_this.deleteData(ids);
								}
							});
						}
					}
				},
				"<span class='cnoa_color_gray'>双击可修改记录,按住Ctrl或Shift键可以一次选择多条记录</span>",
				'->',{xtype: 'cnoa_helpBtn', helpid:5002}
			],
			bbar: this.pagingBar
		});
		
		var ID_SEARCH_TITLE = Ext.id();
		var ID_SEARCH_TYPE	= Ext.id();
		
		this.mainPanel = new Ext.Panel({
			collapsible: false,
			hideBorders: true,
			border: false,
			layout: 'border',
			autoScroll: false,
			items: [this.grid],
			tbar : [
				"模板名称: ",
				{
					xtype : "textfield",
					width : 100,
					id : ID_SEARCH_TITLE
				},
				"发文类型: ",
				new Ext.form.ComboBox({
					name: 'title',
					store: _this.typeListStore,
					width: 150,
					id : ID_SEARCH_TYPE,
					hiddenName: 'tid',
					valueField: 'tid',
					displayField: 'title',
					mode: 'local',
					triggerAction: 'all',
					forceSelection: true,
					editable: false
				}),
				{
					xtype: "button",
					text: lang('search'),
					style: "margin-left:5px",
					cls: "x-btn-over",
					listeners: {
						"mouseout" : function(btn){
							btn.addClass("x-btn-over");
						}
					},
					handler: function(){
						_this.storeBar.title 		= Ext.getCmp(ID_SEARCH_TITLE).getValue();
						_this.storeBar.type 		= Ext.getCmp(ID_SEARCH_TYPE).getValue();
						
						_this.store.load({params:_this.storeBar});
					}
				},
				{
					xtype:"button",
					text:lang('clear'),
					handler:function(){
						Ext.getCmp(ID_SEARCH_TITLE).setValue("");
						Ext.getCmp(ID_SEARCH_TYPE).setValue("");
						
						_this.storeBar.title 		= "";
						_this.storeBar.type 		= "";
						
						_this.store.load({params:_this.storeBar});
					}
				}
			]
		});
	},
	
	submit : function(user){
		var _this = this;
		
		Ext.Ajax.request({
			url: _this.baseUrl + '&task=add',
			params: user,
			method: "POST",
			success: function(r, opts) {
				var result = Ext.decode(r.responseText);
				if(result.success === true){
					_this.store.reload();
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
	
	deleteData : function(ids){
		var _this = this;
		
		Ext.Ajax.request({
			url: _this.baseUrl + '&task=deleteData',
			params: {ids : ids},
			method: "POST",
			success: function(r, opts) {
				var result = Ext.decode(r.responseText);
				if(result.success === true){
					_this.store.reload();
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
	
	
	add : function(value, cellmeta, record, rowIndex, columnIndex, color_store){
		
		return "<a href='index.php?app=odoc&func=setting&action=template&task=editTemplate&id="+value+"' target='odoc_tpl_edit'>编辑模板</a>";
	}
	/*
	add : function(value, cellmeta, record, rowIndex, columnIndex, color_store){
		return "<a href='javascript:void(0)' onclick='CNOA_odoc_setting_template.addTemplate("+value+")'>编辑模板</a>";
	},
	
	addTemplate : function(value){
		var _this = this;
		
		var baseField = [
			{
				xtype: "fieldset",
				title: "基本设置",
				anchor: "99%",
				autoHeight: true,
				items: [
					{
						xtype : "textfield",
						name : "",
						width : 300,
						fieldLabel : "公文类型名称"
					}
				]
			}
		];
		
		var form = new Ext.form.FormPanel({
			border: false,
			labelWidth: 120,
			labelAlign: 'right',
			region : "center",
			layout : "fit",
			autoScroll : true,
			waitMsgTarget: true,
			bodyStyle: "padding:5px 10px 10px 10px;",
			items:[
				{
					xtype: "panel",
					border: false,
					bodyStyle: "padding:10px",
					layout: "form",
					region: "center",
					autoScroll: true,
					items: [baseField]
				}
			]
		});
		
		var win = new Ext.Window({
			title : "编辑模板",
			modal : true,
			width : 700,
			height : makeWindowHeight(800),
			layout : "border",
			items : form,
			buttons : [
				{
					text : lang('close'),
					handler : function(){
						win.close();
						_this.typeListStore.load();
					}
				}
			]
		}).show();
	}*/
}


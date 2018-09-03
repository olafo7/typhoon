/**
 * 全局变量
 */
var CNOA_odoc_setting_wordClass, CNOA_odoc_setting_word;
CNOA_odoc_setting_wordClass = CNOA.Class.create();
CNOA_odoc_setting_wordClass.prototype = {
	init : function(){
		var _this = this;
		
		var ID_BTN_SETTING_TYPE = Ext.id();
		
		this.baseUrl = "index.php?app=odoc&func=setting&action=word";
		
		this.dsc = Ext.data.Record.create([
		{
			name: 'title',
			type: 'string'
		}]);
		
		this.fields = [
			{name : "tid"},
			{name : "title"},
			{name : "postname"},
			{name : "order"}
		];
		
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
						user.from = _this.from;  
						_this.submit(user);
					}
				}
			}
		});
		
		this.pagingBar = new Ext.PagingToolbar({
			displayInfo:true,emptyMsg:"没有数据显示",displayMsg:"显示第{0}--{1}条数据，共{2}条",   
			store: this.store,
			pageSize:15,
			listeners:{
				"beforechange" : function(th, params){
					Ext.apply(params, _this.storeBar);
				}
			}
		});
		
		this.editor = new Ext.ux.grid.RowEditor({
			cancelText: '取消',
			saveText: '更新',
			errorSummary: false
		});
		
		this.sm = new Ext.grid.CheckboxSelectionModel({
			
		});
		
		this.cm = [new Ext.grid.RowNumberer(), this.sm,
		{
			header: '',
			dataIndex: 'tid',
			hidden: true
		}, {
			header: '分类名称',
			dataIndex: 'title',
			width: 300,
			sortable: false,
			editor: {
				xtype: 'textfield',
				allowBlank: false
			}
		}, {
			header : '排序',
			dataIndex : 'order',
			width : 100,
			sortable : false,
			editor: {
				xtype : 'textfield',
				allowBlank : true,
				regex: /^\+?[1-9][0-9]*$/i,
				regexText: '必须设置为正整数'
			}
		}, {
			header: '发布人',
			dataIndex: 'postname',
			width: 80,
			sortable: false
		}];
		
		this.grid = new Ext.grid.GridPanel({
			stripeRows : true,
			store: this.store,
			plain: false,
			loadMask : {msg: CNOA.lang.msgLoadMask},
			region: "center",
			border: false,
			autoScroll: true,
			plugins: [this.editor],
			columns: this.cm,
			sm : this.sm,
			view: new Ext.grid.GroupingView({
				markDirty: false
			}),
			listeners:{
				cellclick:function(th, rowNum, columnNum, e){
					if(columnNum == 1){
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
					text : CNOA.lang.mainJob.refreshList
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
					text : "添加"
				},'-',
			/*	{
					text : "类别权限设置",
					iconCls: 'icon-system-s-userSelf-help',
					id : ID_BTN_SETTING_TYPE,
					handler : function(button){
						var rows = _this.grid.getSelectionModel().getSelections();
						if (rows.length == 0) {
							CNOA.miniMsg.alertShowAt(button, "选择一个信息进行设置!");
						} else {
							_this.settingType(rows[0].get("tid"));
						}
					}
				},'-',*/
				{
					text : "删除",
					iconCls: 'icon-utils-s-delete',
					handler : function(button, event) {
						var rows = _this.grid.getSelectionModel().getSelections(); // 返回值为 Record 数组 
						if (rows.length == 0) {
							CNOA.miniMsg.alertShowAt(button, CNOA.lang.mainJob.oneMoreDataToDel);
						} else {
							CNOA.miniMsg.cfShowAt(button, "确定要删该记录吗?", function(){
								if (rows) {
									var ids = "";
									for (var i = 0; i < rows.length; i++) {
										ids += rows[i].get("tid") + ",";
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
		
		this.mainPanel = new Ext.Panel({
			collapsible: false,
			hideBorders: true,
			border: false,
			layout: 'border',
			autoScroll: false,
			items : [this.grid],
			tbar : [
				{
					text : "公文类型",
					iconCls: 'icon-ui-combo-boxes',
					enableToggle: true,
					toggleGroup: "user_customers_index_share",
					pressed:true,
					allowDepress:false,
					handler : function(){
						_this.from = "type";
						_this.grid.getColumnModel().setColumnHeader(3, "分类名称");
						_this.grid.getColumnModel().setColumnHeader(4, "排序");
						_this.store.load({params : {from : "type"}});
						//Ext.getCmp(ID_BTN_SETTING_TYPE).show();
					}
				},'-',
				{
					text : "秘密等级",
					iconCls: 'icon-ui-combo-box',
					enableToggle: true,
					toggleGroup: "user_customers_index_share",
					allowDepress:false,
					handler : function(){
						_this.from = "level";
						_this.grid.getColumnModel().setColumnHeader(3, "等级名称");
						_this.grid.getColumnModel().setColumnHeader(4, "排序");
						_this.store.load({params : {from : "level"}});
						//Ext.getCmp(ID_BTN_SETTING_TYPE).hide();
					}
				},'-',
				{
					text : "缓急情况",
					iconCls: 'icon-ui-combo-box-blue',
					enableToggle: true,
					toggleGroup: "user_customers_index_share",
					allowDepress:false,
					handler : function(){
						_this.from = "hurry";
						_this.grid.getColumnModel().setColumnHeader(3, "缓急情况名称");
						_this.grid.getColumnModel().setColumnHeader(4, "排序");
						_this.store.load({params : {from : "hurry"}});
						//Ext.getCmp(ID_BTN_SETTING_TYPE).hide();
					}
				},'-',
				{
					text : "保密期限",
					iconCls: 'icon-ui-combo-box-blue',
					enableToggle: true,
					toggleGroup: "user_customers_index_share",
					allowDepress:false,
					handler : function(){
						_this.from = "secret";
						_this.grid.getColumnModel().setColumnHeader(3, "保密期限名称");
						_this.grid.getColumnModel().setColumnHeader(4, "保密天数");
						_this.store.load({params : {from : "secret"}});
						//Ext.getCmp(ID_BTN_SETTING_TYPE).hide();
					}
				},"<span class='cnoa_color_red'>&nbsp;&nbsp;注意 : 【保密期限】的保密时间如果填为31天则表示一个月，365则表示一年</span>"
			]
		})
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
					CNOA.msg.notice(result.msg, "公文管理");
					_this.store.reload();
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
			params: {ids : ids, from : _this.from},
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
	
	settingType : function(id){
		var _this = this;
		
		var loadFormData = function(){
			form.getForm().load({
				url: _this.baseUrl + "&task=loadFormData",
				method:'POST',
				params : {tid : id},
				waitTitle: CNOA.lang.msgLoginWaitTitle,
				success: function(form, action){
				},
				failure: function(form, action) {
					CNOA.msg.alert(action.result.msg, function(){
						
					});
				}
			})
		};
		
		var submit = function(){
			if (form.getForm().isValid()) {
				form.getForm().submit({
					url: _this.baseUrl + "&task=submitTypePermit",
					waitTitle: CNOA.lang.msgLoginWaitTitle,
					method: 'POST',
					params : {tid : id},
					waitMsg: CNOA.lang.msgLoadMask,
					success: function(form, action) {
						CNOA.msg.notice(action.result.msg, "公文管理");
						win.close();
					},
					failure: function(form, action) {
						CNOA.msg.alert(action.result.msg, function(){
							//this.mainPanel.enable();
						});
					}.createDelegate(this)
				});
			}
		};
		
		var baseField = [
			{
				xtype: "fieldset",
				title: "权限设置",
				anchor: "99%",
				autoHeight: true,
				items: [
					{
						xtype : "displayfield",
						value : "",
						fieldLabel : "注意"
					},
					{
						xtype: "textarea",
						readOnly: true,
						width:425,
						height: 100,
						readOnly: true,
						fieldLabel: '查看权限',
						name: "view"	
					},
					{
						xtype: "hidden",
						name: "viewuid"
					},
					{
						xtype: "btnForPoepleSelector",
						text: "选择",
						dataUrl: _this.baseUrl + "&task=getAllUserListsInPermitDeptTree",
						style: "margin-left:65px; margin-bottom:5px",
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
								form.getForm().findField("view").setValue(names.join(", "));
								form.getForm().findField("viewuid").setValue(uids.join(","));
							},
							"onrender" : function(th){
								th.setSelectedUids(form.getForm().findField("viewuid").getValue().split(","));
							}
						}
					},
					{
						xtype: "textarea",
						readOnly: true,
						width:425,
						height: 100,
						readOnly: true,
						fieldLabel: '删除权限',
						name: "delete"	
					},
					{
						xtype: "hidden",
						name: "deleteuid"
					},
					{
						xtype: "btnForPoepleSelector",
						text: "选择",
						dataUrl: _this.baseUrl + "&task=getAllUserListsInPermitDeptTree",
						style: "margin-left:65px;",
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
								form.getForm().findField("delete").setValue(names.join(", "));
								form.getForm().findField("deleteuid").setValue(uids.join(","));
							},
							"onrender" : function(th){
								th.setSelectedUids(form.getForm().findField("deleteuid").getValue().split(","));
							}
						}
					}
				]
			}
		];
		
		var form = new Ext.form.FormPanel({
			border: false,
			labelWidth: 60,
			labelAlign: 'right',
			region : "center",
			layout : "fit",
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
					items: baseField
				}
			]
		});
		
		var win = new Ext.Window({
			title : "类别权限",
			layout : "border",
			resizable : true,
			width : 590,
			height : makeWindowHeight(450),
			modal : true,
			items : [form],
			buttons : [
				{
					text : "提交",
					iconCls: 'icon-dialog-apply2',
					handler : function(){
						submit();
					}
				},
				{
					text : "关闭",
					iconCls: 'icon-dialog-cancel',
					handler : function(){
						win.close();
					}
				}
			]
		}).show();
		
		loadFormData();
	}
}


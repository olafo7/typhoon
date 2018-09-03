//主面板
var CNOA_adm_ammanage_set, CNOA_adm_ammanage_setClass;
    CNOA_adm_ammanage_setClass = CNOA.Class.create();
    CNOA_adm_ammanage_setClass.prototype = {
    		init: function(){
    			var _this = this;
    			this.type = "add";
    			this.baseUrl = "index.php?app=adm&func=ammanage&action=set";
    			
    			this.dsc = Ext.data.Record.create([
    			{
    				name: 'name',
    				type: 'string'
    			}]);
    			
    			this.fields = [
    				{name : "id"}, 
    				{name : "name"},
    				{name : "order"}
    			];
    			
    			this.store = new Ext.data.GroupingStore({
    				autoLoad: true,
    				proxy: new Ext.data.HttpProxy({
    					url: this.baseUrl + '&task=list'
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
    			
    			this.editor = new Ext.ux.grid.RowEditor({
    				cancelText: lang('cancel'),
    				saveText: lang('update'),
    				errorSummary: false
    			});
    			
    			this.sm = new Ext.grid.CheckboxSelectionModel({
					singleSelect: false
    				
    			});
    			
    			this.col = new Ext.grid.Column({

    					header: lang('cetegoryName'),
    					dataIndex: 'name',
    					width: 400,
    					sortable: true,
    					editor: {
    						xtype: 'textfield',
    						allowBlank: false
    					}

    			});
    			
    			this.cm = [
    				new Ext.grid.RowNumberer(), 
    				this.sm,
    				{
    					header: '',
    					dataIndex: 'id',
    					hidden: true
    				}, 
    				{
    					header: lang('zhejiuMethod'),
    					dataIndex: 'name',
    					width: 400,
    					sortable: true,
    					editor: {
    						xtype: 'textfield',
    						allowBlank: false
    					}
    				},{
    					header: lang('order'),
    					dataIndex: 'order',
    					width: 80,
    					sortable: true,
    					editor: {
    						xtype : 'textfield',
    						allowBlank : true,
    						regex: /^\+?[0-9][0-9]*$/i,
    						regexText: lang('mustBeInt')
    					}
    				}
    			];

    			this.grid = new Ext.grid.GridPanel({
    				store: this.store,
    				width: 600,
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
    				tbar: [
    					{
    						handler : function(button, event) {
    							_this.store.reload();
    						}.createDelegate(this),
    						iconCls: 'icon-system-refresh',
    						text : lang('refresh')
    					},
    					{
    						iconCls: 'icon-utils-s-add',
    						text: lang('add'),
    						handler: function(){
    							var e = new _this.dsc({
    								name: ''
    							});
    							_this.editor.stopEditing();
    							_this.store.insert(0, e);
    							_this.grid.getView().refresh();
    							_this.grid.getSelectionModel().selectRow(0);
    							_this.editor.startEditing(0);
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
    								CNOA.msg.cf(lang('wantDelRecord'), function(btn) {
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
    					{
    						text : lang('zengjianCLset'),
    						iconCls: 'icon-ui-combo-boxes',
    						enableToggle: true,
							pressed:true,
    						toggleGroup: "user_customers_index_share",
    						allowDepress:false,
    						handler : function(){
    							_this.type = "add";
    							_this.grid.getColumnModel().setColumnHeader(3, lang('zengjianCJway'));
    							_this.store.load({params : {type : "add"}});
    						}
    					},
    					{
    						text : lang('assetClassSet'),
    						iconCls: 'icon-ui-combo-box',
    						enableToggle: true,
    						toggleGroup: "user_customers_index_share",
    						allowDepress:false,
    						handler : function(){
    							_this.type = "type";
    							_this.grid.getColumnModel().setColumnHeader(3, lang('assetClass'));
    							_this.store.load({params : {type : "type"}});
    						}
    					},
    					{
    						text : lang('useDeptSet'),
    						iconCls: 'icon-ui-combo-box-blue',
    						enableToggle: true,
    						toggleGroup: "user_customers_index_share",
    						allowDepress:false,
    						handler : function(){
    							_this.type = "user";
    							_this.grid.getColumnModel().setColumnHeader(3, lang('useDept'));
    							_this.store.load({params : {type : "user"}});
    						}
    					},{
							text : lang('zhejiuMethodSet'),
							iconCls:'icon-arrow-block',
							style: "margin-left:5px",
							cls: "x-btn-over",
							listeners: {
								"mouseout" : function(btn){
									btn.addClass("x-btn-over");
								}
							},
							handler : function(button, event) {
								_this.depwindow();
							}.createDelegate(this)
						},
    					"<span class='cnoa_color_gray'>" + lang('dblclickToEdit') + "</span>",
    					'->',{xtype: 'cnoa_helpBtn', helpid: 127}
    				]
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
    		
    		submit : function(user){
    			var _this = this;
    			user.type = _this.type;
    			Ext.Ajax.request({
    				url: this.baseUrl + '&task=update',
    				params: user,
    				method: "POST",
    				success: function(r, opts) {
    					var result = Ext.decode(r.responseText);
    					if(result.success == true){
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
    		
    		deleteList : function(ids){
    			var _this = this;
    			Ext.Ajax.request({
    				url: this.baseUrl + "&task=delete",
    				method: 'POST',
    				params: { ids: ids, type : _this.type},
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
    			})
    		},
		depwindow: function(){
			var _this = this;
			var loadFormData = function(){
				formPanel.getForm().load({
					url: _this.baseUrl+"&task=loadDep",						
					method:'POST',
					waitMsg: lang('waiting'),			
					success: function(form, action){
						
					}.createDelegate(this),
					failure: function(form, action) {
				
					}.createDelegate(this)
				});
			};
			var submitDep = function(){
				if (formPanel.getForm().isValid()) {
					formPanel.getForm().submit({
						url: _this.baseUrl+"&task=submitDep",
						waitMsg: lang('waiting'),
						method: 'POST',	
						success: function(form, action) {
								CNOA.msg.alert(action.result.msg);
								   window.close();
								_this.store.load()
						}.createDelegate(this),
						failure: function(form, action) {
							CNOA.msg.alert(action.result.msg);
						}.createDelegate(this)
					});
				}
			};
			var depStore = new Ext.data.SimpleStore({  
		        fields:['name','id'],  
		        data:[[lang('month'),'01'],[lang('quarter'),'02'],[lang('year'),'03']]  
		    });  
			var valueStore = new Ext.data.SimpleStore({  
		        fields:['name2','id2'],  
		        data:[[lang('residual'),'01'],[lang('residualRate'),'02']]  
		    });  
			var depField =new Ext.Panel({
				border: false,
				layout: 'column',
				bodyStyle: "padding: 10px;",
				defaults: {
					width: 230
				},
				items: [
					{
						xtype: "fieldset",
						title: lang('set2'),
						width: 350,
						layout: "table",
						defaults: {
							border: false,
							width: 400,
							labelWidth : 100,
							xtype: "textfield"
						},
						items: [
							{
								xtype: "panel",
								layout: "form",
								items: [
									{
										xtype: 'combo',
										name: "Depreciation_way",
										fieldLabel: lang('JTzhejiuMethod'),
										width: 180,
				                        hiddenName:'Depreciation_way',  
				                        store:depStore,  
				                        displayField:'name',  
				                        valueField:'id',  
				                        forceSelection:true,  
				                        selectOnFocus:true,  
				                        editable:false,  
				                        triggerAction:'all',  
				                        mode:'local' 
									},{
										xtype: 'combo',
										name: "Salvage_value",
										fieldLabel: lang('residualHandWay'),
										width: 180,
				                        hiddenName:'Salvage_value',  
				                        store:valueStore,  
				                        displayField:'name2',  
				                        valueField:'id2',  
				                        forceSelection:true,  
				                        selectOnFocus:true,  
				                        editable:false,  
				                        triggerAction:'all',  
				                        mode:'local' 
									}
								]
							}
						]
					}
					
				]
			});
			var formPanel = new Ext.form.FormPanel({
				hideBorders: true,
				border: false,
				waitMsgTarget: true,
				layout: "border",
				labelAlign: 'right',
				items:[
					{
						xtype: "panel",
						border: false,
						bodyStyle: "padding:10px",
						layout: "form",
						region: "center",
						autoScroll: true,
						items: depField
					}
				]
			});
			
			var window = new Ext.Window({
				title : lang('zhejiuMethodSet'),
				width : 420,
				height : 200,
				modal : true,
				layout : "fit",
				resizable : false,
				border : false,
				items : formPanel,
				buttons:[
					{
						text:"保存",
						iconCls: 'icon-btn-save',
						handler:function(){
							submitDep();
							
						}
					},
					{
						text:lang('close'),		
						iconCls: 'icon-dialog-cancel',
						handler : function() {
							window.close();
						}
					}
				]
			}).show();
		loadFormData();
		}
				
	}

Ext.onReady(function() {
	CNOA_adm_ammanage_set = new CNOA_adm_ammanage_setClass();
	Ext.getCmp(CNOA.adm.ammanage.set.parentID).add(CNOA_adm_ammanage_set.mainPanel);
	Ext.getCmp(CNOA.adm.ammanage.set.parentID).doLayout();
});

    
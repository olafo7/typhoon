
var CNOA_adm_ammanage_dep, CNOA_adm_ammanage_depClass;
    CNOA_adm_ammanage_depClass = CNOA.Class.create();
    CNOA_adm_ammanage_depClass.prototype = {
    		init: function(){
    			var _this = this;
    			this.from = "type";
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
    					url: this.baseUrl + '&task=loist'
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
    				//new Ext.grid.RowNumberer(), 
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
					},{
						text: lang('increaseAsset'),
						iconCls: 'icon-utils-s-add',
						handler:function(){
							_this.add(0, "add");
						}   					
    				},lang('truename') + ':',{
    				   xtype: "textfield",    				   
    				 
    				   readOnly:false,   				  
    				   name:'name'
    				}
					]
    			});

    			this.mainPanel = new Ext.Panel({
    				collapsible: false,
    				hideBorders: true,
    				border: false,
    				layout: 'border',
    				autoScroll: false,
    				items: [this.grid],    			
    				tbar:new Ext.Toolbar({
    					height:30 				
    				}),   			
    				bbar:new Ext.Toolbar({
    					style:'border-left-width:1px',
    					items:[{
    							xtype:"button",
    					        text: "保存",
    					        onpress: true
    					      //  margins-left: 223
    					}]
    				})
    			});
    		}   
  	}
    Ext.onReady(function() {
    	CNOA_adm_ammanage_dep = new CNOA_adm_ammanage_depClass();
    	Ext.getCmp(CNOA.adm.ammanage.dep.parentID).add(CNOA_adm_ammanage_dep.mainPanel);
    	Ext.getCmp(CNOA.adm.ammanage.dep.parentID).doLayout();
    });

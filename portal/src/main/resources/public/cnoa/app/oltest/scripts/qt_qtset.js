var CNOA_test_qt_qtset, CNOA_test_qt_qtsetClass;

CNOA_test_qt_qtsetClass = CNOA.Class.create();
CNOA_test_qt_qtsetClass.prototype = {
	init: function(){

		var me = this;
		this.baseUrl = 'index.php?app=test&func=qt&action=qtset';

		this.TYPE_UNIT	      		= 0;    
		this.TYPE_SOURCE	 		= 2;	
		this.TYPE_WAY		  		= 3;	
		this.TYPE_PLACE		 		= 4;	
		this.TYPE_MANUFACTUER 		= 6;
		this.TYPE_SUPPLIER    		= 7;
		this.TYPE_RESIDUALS    		= 9;
		
		this.unitmeasure  = this.getDropdownPanel(this.TYPE_UNIT, lang('uniteMeasure'));
		this.assetssource = this.getDropdownPanel(this.TYPE_SOURCE, lang('assetsSources'));
		this.reduceway    = this.getDropdownPanel(this.TYPE_WAY, lang('reduceway'));
		this.storageplace = this.getDropdownPanel(this.TYPE_PLACE, lang('storagePlace'));
		this.manufactuer = this.getDropdownPanel(this.TYPE_MANUFACTUER, lang('manufacturer'));
		this.supplier = this.getDropdownPanel(this.TYPE_SUPPLIER, lang('supplier'));
		this.residuals = this.getDropdownPanel(this.TYPE_RESIDUALS, lang('residualRate'));

		this.mainPanel = new Ext.ux.VerticalTabPanel({
			region: "center",
			border: false,
			tabPosition: "left",
			tabWidth: 150,
			activeItem: 0,
			deferredRender: true,
			layoutOnTabChange: true,
			items: [this.unitmeasure,  this.assetssource, this.reduceway, this.storageplace,
					 this.manufactuer, this.supplier,  this.residuals

			]
		});
	},

	getDropdownPanel: function(type, title){
		var me = this;
		var fields = [
			{name: "id"},
			{name: "value"}
		];

		var store = new Ext.data.Store({
			autoLoad: true,
			proxy: new Ext.data.HttpProxy({url: this.baseUrl + '&task=getdrop&type=' + type}),   
		 	reader: new Ext.data.JsonReader({totalProperty:"total",root:"data", fields: fields}),
		 	listeners: {
				update: function(store, record, opt){
					if (opt == Ext.data.Record.EDIT){
						Ext.Ajax.request({
							url: me.baseUrl+'&task=add',
							params: {type: type, value:record.get('value'),id:record.get('id')},
							success: function(response){
								var responseText = Ext.decode(response.responseText);
								if (responseText.success == true) {
									CNOA.msg.notice2(responseText.msg);
								}else if(responseText.failure == true){
									CNOA.msg.alert(responseText.msg);
								}
								switch(type){
									case 0:
										me.unitmeasure.store.reload();
										break;
									case 2:
										me.assetssource.store.reload();
										break;
									case 3:
										me.reduceway.store.reload();
										break;
									case 4:
										me.storageplace.store.reload();
										break;
									case 6:
										me.manufactuer.store.reload();
										break;
									case 7:
										me.supplier.store.reload();
										break;
									case 9:
										me.residuals.store.reload();
										break;
								}
							}
						});
					}
				}
			}
		 });

		var sm = new Ext.grid.CheckboxSelectionModel({
			singleSelect: true
		});
		var tField = new Ext.form.TextField({allowBlank:false});
		var nField = new Ext.form.NumberField({
			allowBlank:false,
			regex: /^0\.1$|^0\.0\d$/,
			regexText: lang('enterBetween')
		});
		var cm = new Ext.grid.ColumnModel({
			columns: [
				new Ext.grid.RowNumberer(),
				sm,
				{header: 'id', dataIndex: 'id', hidden: true},
				{header: lang('name'), dataIndex: 'value', width: 180, editor: type == 9? nField: tField}
			]
		});

		var editor = new Ext.ux.grid.RowEditor({
	        saveText: lang('ok'),
	        cancelText: lang('cancel')
	    });

		var grid = new Ext.grid.GridPanel({
			title: title,
			stripeRows : true,
			store : store,
			sm: sm,
			cm : cm,
			plugins: [editor],
			tbar: new Ext.Toolbar({
				items: [{
					text: lang('refresh'),
					iconCls: 'icon-system-refresh',
					handler: function(){
						store.reload()
					}
				},
				'-',{
					text: lang('add'),
					iconCls: 'icon-utils-s-add',
					handler: add
				},
				'-',{
					text: lang('del'),
					iconCls: 'icon-utils-s-delete',
					handler: del
				},
				'-',("<span style='color:#999'>"+lang('dblclickToEdit')+"</span>")
				]
			})


		});
		//添加/修改
		function add(){
			var u = new store.recordType({
				id :'',
	            value: ''
	        });
	        editor.stopEditing();
	        grid.store.insert(0, u);
	        editor.startEditing(0);
		};

		//删除
		function del(){
			var record = grid.getSelectionModel().getSelected();
	        if (!record) return false;
	        CNOA.msg.cf(lang('areYouDelete'), function(btn){
	        	if (btn == 'yes') {
	        		Ext.Ajax.request({
						url: me.baseUrl+'&task=delete',
						params: {id: record.get('id')},
						success: function(response){
							store.remove(record);
							store.reload();
							var responseText = Ext.decode(response.responseText);
							if (responseText.success == true) {
								CNOA.msg.notice2(responseText.msg);
							} else{
								CNOA.msg.alert(responseText.msg);
							};
							
						}
					});
	        	};
	        })
	        
		};

		return grid;
	}

	

}

CNOA_test_qt_qtset = new CNOA_test_qt_qtsetClass();
Ext.getCmp(CNOA.test.qt.qtset.parentID).add(CNOA_test_qt_qtset.mainPanel);
Ext.getCmp(CNOA.test.qt.qtset.parentID).doLayout();
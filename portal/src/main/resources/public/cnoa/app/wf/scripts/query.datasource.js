Ext.ns('CNOA.wf.datasource');

CNOA.wf.datasource = Ext.extend(Ext.Window, {
	constructor : function(fieldId, datasource, maps, sqlDetail){
		this.fieldId = fieldId;
		this.datasource = datasource;
		this.maps = maps;
		this.sqlDetail = sqlDetail;
		CNOA.wf.datasource.superclass.constructor.call(this);
	},
	initComponent : function(){
		this.baseUrl = "index.php?app=wf&func=flow&action=use&modul=new";
		
		if (this.datasource == 'customerInfo') {
			this.selectPanel = this.getCustomerInfoPanel();
		} else if (this.datasource == 'sqlDetail') {
			this.selectPanel = this.getSqlDetailPanel();
		} else {
			this.selectPanel = this.getSelectPanel();
		}
		
		var box = Ext.getBody().getBox();
		var w	= box.width - 20;
		var h	= box.height - 20;
		
		Ext.apply(this, {
			modal: true,
			width: w,
			height: h,
			maximizable: true,
			title: lang('queryPanel'),
			layout: 'fit',
			items: this.selectPanel,
			buttons: [
				{
					text: lang('determineSelect'),
					scope: this,
					handler: this.onOk
				},{
					text: lang('cancel'),
					scope: this,
					handler: function(){
						this.close();
					}
				}
			]
		});
		
		CNOA.wf.datasource.superclass.initComponent.call(this);
	},

	getSelectPanel: function(){
		var grid = new Ext.grid.CustomGridPanel({
			page: true,
			pageSize: 15,
			singleSelect: true,
			customFiledUrl: this.baseUrl+"&task=getDsFields&dsTag="+this.datasource,
			storeUrl: this.baseUrl+"&task=getDsData&dsTag="+this.datasource
		});
		return grid;
	},
	
	getCustomerInfoPanel: function(){
		var customerName = new Ext.form.TextField({width: 150});
		
		var grid = new Ext.grid.CustomGridPanel({
			page: true,
			pageSize: 15,
			singleSelect: true,
			customFiledUrl: this.baseUrl+"&task=getDsFields&dsTag="+this.datasource,
			storeUrl: this.baseUrl+"&task=getDsData&dsTag="+this.datasource,
			tbar: [
				lang('clientName') + '：',
				customerName,
				{
					text: lang('search'),
					handler : function(){
						var customerStore = grid.getStore(),
							name = customerName.getValue();
							
							customerStore.load({params: {name: name}});
					}
				},{
					text: lang('clear'),
					handler : function(){
						var customerStore = grid.getStore();
						
						customerName.setValue('');

						customerStore.load();
					}
				}
			]
		});
		return grid;
	},

	getSqlDetailPanel: function(){
		var baseUrl = "index.php?app=abutment&func=abutment&action=sqldetail";
		var me = this;
		var list = new Ext.grid.CustomGridPanel({
			border: false,
			region: 'center',
			pageSize: 100,
			singleSelect: true,
			customFiledUrl: baseUrl+"&task=getCustomerCustomField&id="+this.sqlDetail,
			storeUrl: baseUrl+"&task=getCustomerList&id="+this.sqlDetail,
			loadMask : {msg: lang('waiting')},
			listeners:{
				afterrender: function(th){
					setTimeout(function(){
						th.store.reload()
					},200)
				}
			}
		});

		this.sqlDetailGrid = list;

		var searchPanel = new Ext.form.FormPanel({
			border: false,
			height: 150,
			hidden: true,
			region: 'north',
			labelAlign: 'right',
			padding: 5,
			autoScroll: true,
			split: true,
			defaults: {
				xtype: 'textfield'
			}
		});

		var tbar = new Ext.Toolbar({
			items: [
				{
					text: lang('expandSearch'),
					handler: function(th){
						if(searchPanel.isVisible()){
							searchPanel.hide();
						}else{
							th.setText(lang('foldSearch'));
							searchPanel.show();
						}
						panel.doLayout();
					}
				},{
					text:lang('search'),
					handler: function(th){
						me.searchParams = searchPanel.getForm().getValues();
						list.store.reload({params:me.searchParams});
						list.store.searchStore = me.searchParams;
					}
				},{
					text: lang('clear'),
					style: "margin-left:5px",
					handler: function(th){
						me.searchParams = {};
						Ext.each(searchPanel.getForm().items.items, function(item, index){
							var xtype = item.getXType();
							if(xtype=='compositefield' || xtype=='checkboxgroup'){
								Ext.each(item.items.items, function(i){
									i.setValue('');
								});
							}else{
								item.setValue('');
							}
						});
						list.store.reload({params:me.searchParams});
						list.searchStore = me.searchParams;
					}
				}
			]
		});
		
		//获取查询条件生成条件按钮
		Ext.Ajax.request({
			url: baseUrl + "&task=getCustomerSearchFields&id="+this.sqlDetail,
			method: 'POST',
			success: function(r) {
				var condition = Ext.decode(r.responseText), item;
				for(var i = 0; i < condition.msg.length; i++){
					item = new Ext.form.TextField({
						fieldLabel: condition.msg[i].mapName,
						name: 'search|' + condition.msg[i].id
					})
					searchPanel.add(item);
				}
				searchPanel.doLayout();
			}
		});

		var panel = new Ext.Panel({
			collapsible:false,
			hideBorders: true,
			border: false,
			layout:'border',
			autoScroll: false,
			items: [searchPanel,list],
			tbar: tbar
		});
		return panel;
	},
	
	onOk: function(){
		if(this.datasource == 'sqlDetail') {
			var row = this.sqlDetailGrid.getSelectionModel().getSelected();
		} else {
			var row = this.selectPanel.getSelectionModel().getSelected();
		}
		if(row){
			var maps = Ext.decode(this.maps) || {},
				value;
			$('input[name=wf_field_'+this.fieldId+']')[0].value = row.json.datasourceId;
			//兼容旧数据格式(内容为对象)
			if(Ext.isObject(maps)){
				for(var m in maps){
					if(!Ext.isEmpty(row.get(m))){
						$('#'+maps[m]).val(row.get(m));
						$('#'+maps[m]).attr("readOnly", "readOnly");
					}
				}
			}
			//处理新数据格式(内容为数组)
			if(Ext.isArray(maps)){
				Ext.each(maps, function(v){
					$('#'+v.des).val('');
					if(!Ext.isEmpty(row.get(v.src))){
						$('#'+v.des).val(row.get(v.src));
						if(v.editable != '1'){
							$('#'+v.des).attr("readOnly", "readOnly");
						}
					}
				});
			}
			
			this.close();
		}else{
			CNOA.msg.alert(lang('selectOneData'));
		}
	}
});



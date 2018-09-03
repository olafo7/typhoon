var CNOA_domain_domain_setting, CNOA_domain_domain_settingClass;

CNOA_domain_domain_settingClass = new CNOA.Class.create();
CNOA_domain_domain_settingClass.prototype = {
	init: function() {
		var me = this;
		this.baseUrl = "index.php?app=domain&func=domain&action=setting";

		var fields = [
			{name: 'id'},
			{name: 'domain'},
			{name: 'name'},
			{name: 'isuse'}
		];

		var cm = new Ext.grid.ColumnModel({
			defaults:{align: 'center', menuDisabled :true},
			columns: [
				new Ext.grid.RowNumberer(),
				{header: 'id', dataIndex: 'id', hidden:true},
				{header: 'isuse', dataIndex: 'isuse', hidden:true},
				{header: '子公司名称', dataIndex: 'name', width: 100, align: 'left', editor: new Ext.form.TextField({allowBlank:false})},
				{header: '子公司地址', dataIndex: 'domain', width: 250},
				{header: lang('opt'), width: 100, renderer: this.opt}
			]
		});

		var agentStore = new Ext.data.Store({
			autoLoad: true,
			proxy: new Ext.data.HttpProxy({url: me.baseUrl + '&task=getAgentList', disableCaching: true}),   
			reader: new Ext.data.JsonReader({root: 'data', fields: fields})
		});

		this.store = agentStore;

		var grid = new Ext.grid.EditorGridPanel({
			store: agentStore,
			border: false,
			region: 'center',
			layout: 'fit',
			cm: cm,
			tbar: new Ext.Toolbar({
				items: [
					{
						text:"生成子公司",
						iconCls:'icon-utils-s-add',
						cls: 'btn-blue4',
						handler:function(){
							var myMask = new Ext.LoadMask(Ext.getBody(), {    
					            msg: '正在生成子公司，请稍后...',    
					            removeMask: true //完成后移除    
					        });    
					        myMask.show();
							Ext.Ajax.request({
								url: me.baseUrl+"&task=createSubsidiary",
								success: function(response){
									myMask.hide();
									var json = Ext.decode(response.responseText);
									if(json.failure){
										CNOA.msg.alert(json.msg);
									}else{
										CNOA.msg.notice2(json.msg);
										agentStore.reload();
									}
								}
							});
						}
					}
				]
			}),
			listeners: {
				'afteredit': function(e){
					var field = e.field,
						id = e.record.get('id'),
						value = e.value;
					me.updateAgent(id, field, value,agentStore);
				}
			}
		});

		this.mainPanel = new Ext.Panel({
			border: false,
			layout: 'fit',
			items: [grid]
		});
	},

	updateAgent: function(id, field, value, store){
		var me = this;
		Ext.Ajax.request({
			url: me.baseUrl+"&task=updateSubsidiary",
			params:{id:id, field:field, value:value},
			success: function(response){
				var result = Ext.decode(response.responseText);
				if (result.failure === true){
					CNOA.msg.alert(result.msg);
					store.reload();
				}else{
					CNOA.msg.notice2(result.msg);
					store.reload();
				}
			}
		});
	},

	opt: function(value, cellmeta, record){
		var id = record.json.id,
			isuse = record.json.isuse;
		var value = '<a href="javascript:void(0)" class="gridview" onclick="CNOA_domain_domain_setting.changeUse('+id+','+isuse+')">'+(isuse == 1 ? "停用" : "启用")+'</a>';
		value += ' <a href="javascript:void(0)" class="gridview4" onclick="CNOA_domain_domain_setting.deleteDoamin('+id+')">删除</a>';
		return value;
	},

	changeUse: function(id, isuse){
		var me = this;
		Ext.Ajax.request({
			url: me.baseUrl+"&task=changeUse",
			params: {"id": id, "isuse": isuse},
			success: function(response){
				var json = Ext.decode(response.responseText);
				if(json.failure){
					CNOA.msg.alert(json.msg);
				}else{
					CNOA.msg.notice2(json.msg);
					me.store.reload();
				}
			}
		});
	},

	deleteDoamin: function(id){
		var me = this;
		CNOA.msg.cf("删除后不能恢复，确定删除？",function(btn){
			if(btn == "yes"){
				var myMask = new Ext.LoadMask(Ext.getBody(), {    
		            msg: '正在删除子公司，请稍后...',    
		            removeMask: true //完成后移除    
		        });    
		        myMask.show();
				Ext.Ajax.request({
					url: me.baseUrl+"&task=deleteDoamin",
					params: {"id": id},
					success: function(response){
						myMask.hide();
						var json = Ext.decode(response.responseText);
						if(json.failure){
							CNOA.msg.alert(json.msg);
						}else{
							CNOA.msg.notice2(json.msg);
							me.store.reload();
						}
					}
				});
			}
		});
	}

}

var sm_domain_domain = 1;
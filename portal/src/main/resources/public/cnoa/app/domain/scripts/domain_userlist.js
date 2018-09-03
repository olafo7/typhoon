var CNOA_domain_domain_userlist, CNOA_domain_domain_userlistClass;

CNOA_domain_domain_userlistClass = new CNOA.Class.create();
CNOA_domain_domain_userlistClass.prototype = {
	init: function() {
		var me = this;
		// this.baseUrl = "index.php?app=wechat&func=setting&action=setting";
		this.baseUrl = "index.php?app=domain&func=domain&action=userlist";

		var fields = [
			{name: 'domain'},
			{name: 'name'}
		];

		var cm = new Ext.grid.ColumnModel({
			defaults:{align: 'center', menuDisabled :true},
			columns: [
				new Ext.grid.RowNumberer(),
				{header: '用户名称', dataIndex: 'name', width: 100, align: 'left'},
				{header: '所在集团', dataIndex: 'domain', width: 250}
			]
		});

		var agentStore = new Ext.data.Store({
			autoLoad: true,
			proxy: new Ext.data.HttpProxy({url: me.baseUrl + '&task=getUserlist', disableCaching: true}),   
			reader: new Ext.data.JsonReader({root: 'data', fields: fields})
		});

		var grid = new Ext.grid.GridPanel({
			store: agentStore,
			border: false,
			region: 'center',
			layout: 'fit',
			cm: cm,
			tbar: new Ext.Toolbar({
				items: [
					lang("name"),{
						xtype: "textfield",
						width: 100,
						id: "name"
					},"-",{
						text: lang('search'),
						cls: 'btn-blue3',
						handler:function(){
							var name = Ext.getCmp("name").getValue();
							if(name){
								agentStore.reload({
									params: {"name": name}
								})
							}
						}
					},"-",{
						text: lang('clear'),
						handler:function(){
							Ext.getCmp("name").setValue("");
							agentStore.load();
						}
					}
				]
			})
		});

		this.mainPanel = new Ext.Panel({
			border: false,
			layout: 'fit',
			items: [grid]
		});

	}

}

var sm_domain_domain3 = 1;
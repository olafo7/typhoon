/**
 * 外出记录
 * @type Ext.grid.PageGridPanel
 */
var CNOA_user_customers_outingClass = CNOA.Class.create();
CNOA_user_customers_outingClass.prototype = {

    init: function() {
        this.baseUrl = "index.php?app=user&func=customers&action=outing";

        this.mainPanel = this.createOutingView();
    },

    createOutingView: function() {
        var fields = [
            {name: 'id'},
            {name: 'uname'},
            {name: 'customer'},
            {name: 'linkman'},
            {name: 'posttime'},
            {name: 'type'},
            {name: 'address'},
            {name: 'content'}
        ];

        var recordStore = new Ext.data.Store({
            autoLoad: true,
            proxy:new Ext.data.HttpProxy({url: this.baseUrl + "&task=getOutingRecord", disableCaching: true}),   
            reader:new Ext.data.JsonReader({totalProperty: "total",root: "data", fields: fields})
        });

        var colModel = new Ext.grid.ColumnModel({
            defaults: {
                sortable: false,
                menuDisabled: true
            },
            columns: [
                new Ext.grid.RowNumberer(),
                {header: 'id', dataIndex: 'id', hidden: true},
                {header: '用户', dataIndex: 'uname'},
                {header: '客户', dataIndex: 'customer'},
                {header: '联系人', dataIndex: 'linkman'},
                {header: '类型', dataIndex: 'type'},
                {header: '地址', dataIndex: 'address', width: 250},
                {header: '跟踪内容', dataIndex: 'content', width: 300}
            ]
        });

        var grid = new Ext.grid.PageGridPanel({
            cm: colModel,
            store: recordStore,
            tbar: new Ext.Toolbar({
                items: [
                    {
                        text: lang('refresh'),
                        iconCls: 'icon-system-refresh',
                        handler: function(){
                            recordStore.reload()
                        }
                    }
                ]
            })
        });

        return grid;
    }
}


var CNOA_user_customers_outing = new CNOA_user_customers_outingClass();
Ext.getCmp(CNOA.user.customers.outing.parentID).add(CNOA_user_customers_outing.mainPanel);
Ext.getCmp(CNOA.user.customers.outing.parentID).doLayout();
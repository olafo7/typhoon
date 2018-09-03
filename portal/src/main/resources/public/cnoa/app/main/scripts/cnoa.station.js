var CNOA_main_station_indexClass, CNOA_main_station_index;
CNOA_main_station_indexClass = CNOA.Class.create();
CNOA_main_station_indexClass.prototype = {
    init: function () {
        var a = this;
        this.baseUrl = "main/station";
        this.edit_id = 0;
        this.ID_btn_add = Ext.id();
        this.ID_btn_edit = Ext.id();
        this.ID_btn_addedit_save = Ext.id();
        this.fields = [{name: "sid"}, {name: "name"}, {name: "about"}, {name: "sort"}];
        this.store = new Ext.data.Store({
            proxy: new Ext.data.HttpProxy({type: 'ajax',url: this.baseUrl+"/getJsonData"}),
            reader: new Ext.data.JsonReader({totalProperty: "total", root: "data", fields: this.fields})
        });
        this.store.load({params: {start: 0}});
        this.sm = new Ext.grid.CheckboxSelectionModel({singleSelect: true});
        this.colModel = new Ext.grid.ColumnModel([new Ext.grid.RowNumberer(), this.sm, {
            header: "sid",
            dataIndex: "sid",
            width: 20,
            sortable: true,
            hidden: true
        }, {header: lang("stationName"), dataIndex: "name", width: 80, sortable: true}, {
            header: lang("remark"),
            dataIndex: "about",
            width: 120,
            sortable: true
        }, {
            header: lang("order"),
            dataIndex: "sort",
            width: 120,
            sortable: true,
            editor: new Ext.form.TextField()
        }, {header: "", dataIndex: "noIndex", width: 1, menuDisabled: true, resizable: false}]);
        this.grid = new Ext.grid.EditorGridPanel({
            stripeRows: true,
            store: this.store,
            loadMask: {msg: lang("waiting")},
            cm: this.colModel,
            sm: this.sm,
            hideBorders: true,
            autoWidth: true,
            hideBorders: true,
            border: false,
            viewConfig: {forceFit: true},
            listeners: {
                celldblclick: function (c, e, d) {
                    var b = c.getStore().getAt(e).get("sid");
                    if (d == "3") {
                        a.editPanel(b)
                    }
                }, afteredit: function (f) {
                    var d = f.field, b = f.record.get("sid"), c = f.value;
                    a.updateSort(b, d, c)
                }
            }
        });
        this.centerPanel = new Ext.Panel({
            title: lang("station") + lang("list"),
            region: "center",
            layout: "fit",
            autoScroll: true,
            items: [this.grid],
            tbar: new Ext.Toolbar({
                items: [{
                    id: this.ID_btn_refreshList,
                    handler: function (b, c) {
                        CNOA_main_station_index.store.reload()
                    }.createDelegate(this),
                    iconCls: "icon-system-refresh",
                    tooltip: lang("refresh"),
                    text: lang("refresh")
                }, {
                    id: this.ID_btn_add, handler: function (b, c) {
                        a.showAddEditWindow("add")
                    }, iconCls: "icon-utils-s-add", text: lang("add")
                }, {
                    text: lang("modify"), iconCls: "icon-utils-s-edit", handler: function (c, d) {
                        var e = a.grid.getSelectionModel().getSelections();
                        if (e.length == 0) {
                            var b = c.getEl().getBox();
                            b = [b.x + 12, b.y + 26];
                            CNOA.miniMsg.alert(lang("mustSelectOneRow"), b)
                        } else {
                            a.editPanel(e[0].get("sid"))
                        }
                    }.createDelegate(this)
                }, {
                    text: lang("del"), iconCls: "icon-utils-s-delete", handler: function (c, d) {
                        var e = this.grid.getSelectionModel().getSelections();
                        if (e.length == 0) {
                            var b = c.getEl().getBox();
                            b = [b.x + 12, b.y + 26];
                            CNOA.miniMsg.alert(lang("mustSelectOneRow"), b)
                        } else {
                            CNOA.msg.cf(lang("confirmToDelete"), function (f) {
                                if (f == "yes") {
                                    if (e) {
                                        var g = "";
                                        g += e[0].get("sid");
                                        a.deleteRecord(g)
                                    }
                                }
                            })
                        }
                    }.createDelegate(this)
                }, "<span style='color:#999'>" + lang("dblclickToEdit") + "</span>", "->", {
                    xtype: "cnoa_helpBtn",
                    helpid: 34
                }]
            })
        });
        this.mainPanel = new Ext.Panel({
            collapsible: false,
            hideBorders: true,
            border: false,
            layout: "border",
            autoScroll: true,
            items: [this.centerPanel]
        })
    }, updateSort: function (a, d, c) {
        var b = this;
        if (c < 0) {
            CNOA.msg.alert(lang("enterNumNotLess0"));
            return
        }
        Ext.Ajax.request({
            url: b.baseUrl + "/updateSort",
            params: {sid: a, field: d, value: c},
            success: function (e) {
                var f = Ext.decode(e.responseText);
                b.store.reload();
                CNOA.msg.notice2(f.msg)
            }
        })
    }, editPanel: function (a) {
        var b = this;
        b.edit_id = a;
        this.showAddEditWindow("edit")
    }, showAddEditWindow: function (b) {
        var g = this;
        var e = b == "edit" ? lang("remark") : lang("addStation");
        var f = function () {
            var h = a.getForm();
            h.load({
                url: g.baseUrl + "/loadFormData",
                params: {sid: g.edit_id},
                method: "POST",
                waitMsg: lang("waiting"),
                success: function (i, j) {
                }.createDelegate(this),
                failure: function (i, j) {
                    d.close();
                    CNOA.msg.alert(j.result.msg, function () {
                    })
                }.createDelegate(this)
            })
        };
        var a = new Ext.form.FormPanel({
            labelAlign: "right",
            labelWidth: 70,
            waitMsgTarget: true,
            border: false,
            bodyStyle: "padding: 10px;",
            items: [{
                xtype: "textfield",
                width: 200,
                fieldLabel: lang("stationName"),
                allowBlank: false,
                name: "name"
            }, {xtype: "textarea", fieldLabel: lang("remark"), name: "about", width: 200, height: 100}],
            listeners: {
                afterrender: function (h) {
                    if (b == "edit") {
                        f()
                    }
                }
            }
        });
        var d = new Ext.Window({
            title: e,
            modal: true,
            layout: "fit",
            width: 350,
            height: 240,
            resizable: false,
            items: [a],
            buttons: [{
                text: lang("save"),
                id: g.ID_btn_addedit_save,
                iconCls: "icon-btn-save",
                cls: "btn-blue4",
                handler: function () {
                    c()
                }
            }, {
                text: lang("close"), cls: "btn-red1", iconCls: "icon-dialog-cancel", handler: function () {
                    d.close()
                }
            }]
        }).show();
        var c = function () {
            var i = a.getForm();
            if (i.isValid()) {
                i.submit({
                    url: g.baseUrl + "/" + b,
                    waitTitle: lang("notice"),
                    method: "POST",
                    waitMsg: lang("waiting"),
                    params: {sid: g.edit_id},
                    success: function (j, k) {
                        g.store.reload();
                        CNOA.msg.alert(k.result.msg, function () {
                            d.close()
                        }.createDelegate(this))
                    }.createDelegate(this),
                    failure: function (j, k) {
                        CNOA.msg.alert(k.result.msg, function () {
                        }.createDelegate(this))
                    }.createDelegate(this)
                })
            } else {
                var h = Ext.getCmp(g.ID_btn_addedit_save).getEl().getBox();
                h = [h.x + 35, h.y + 26];
                CNOA.miniMsg.alert(lang("formValid"), h)
            }
        }
    }, deleteRecord: function (a) {
        var b = this;
        Ext.Ajax.request({
            url: b.baseUrl + "/delete", method: "POST", params: {sid: a}, success: function (d) {
                var c = Ext.decode(d.responseText);
                if (c.success === true) {
                    CNOA.msg.alert(c.msg, function () {
                        b.store.reload()
                    })
                } else {
                    CNOA.msg.alert(c.msg)
                }
            }
        })
    }
};
var sm_main_station = 1;
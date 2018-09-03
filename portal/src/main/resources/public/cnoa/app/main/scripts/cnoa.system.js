var CNOA_main_system_index, CNOA_main_system_indexClass;
CNOA_main_system_indexClass = CNOA.Class.create();
CNOA_main_system_indexClass.prototype = {
    init: function() {
        _this = this;
        this.baseUrl = "main/system";
        this.tabs = new Ext.TabPanel({
            activeTab: 4,
            region: "center",
            minTabWidth: 120,
            tabWidth: 120,
            enableTabScroll: true,
            listeners: {
                afterrender: function(a) {
                    if (CNOA_USER_JOBTYPE == "superAdmin") {
                        a.add({
                            xtype: "panel",
                            id: "CNOA_MAIN_SYSTEM_INDEX_TABPANEL_DESKTOP",
                            title: lang("desktopApp"),
                            layout: "fit",
                            autoLoad: {
                                url: _this.baseUrl + "desktop",
                                scripts: true,
                                scope: this.tabs,
                                nocache: true
                            }
                        });
                        a.doLayout();
                        a.activate(7)
                    }
                }
            },
            items: [new Ext.Panel({
                id: "CNOA_MAIN_SYSTEM_INDEX_TABPANEL_CORE",
                title: lang("systemCore"),
                layout: "fit",
                disabled: false,
                autoLoad: {
                    url: this.baseUrl + "core",
                    scripts: true,
                    scope: this.tabs,
                    nocache: true
                }
            }), new Ext.Panel({
                id: "CNOA_MAIN_SYSTEM_INDEX_TABPANEL_USERINFO",
                title: lang("extendField"),
                disabled: !CNOA.permitController.main_system.userinfo,
                layout: "fit",
                autoLoad: {
                    url: this.baseUrl + "userinfo",
                    scripts: true,
                    scope: this.tabs,
                    nocache: true
                }
            }), new Ext.Panel({
                id: "CNOA_MAIN_SYSTEM_INDEX_TABPANEL_DEVELOPER",
                title: lang("developer"),
                layout: "fit",
                disabled: true,
                autoLoad: {
                    url: this.baseUrl + "developer",
                    scripts: true,
                    scope: this.tabs,
                    nocache: true
                }
            }), new Ext.Panel({
                id: "CNOA_MAIN_SYSTEM_INDEX_TABPANEL_PERMIT",
                title: lang("premitSet"),
                layout: "fit",
                disabled: true,
                autoLoad: {
                    url: this.baseUrl + "permit",
                    scripts: true,
                    scope: this.tabs,
                    nocache: true
                }
            })]
        });
        this.Viewport = new Ext.Panel({
            id: "CNOA_main_system_index",
            collapsible: false,
            hideBorders: true,
            layout: "border",
            border: false,
            items: [this.tabs]
        })
    },
    mainPanel: function() {
        return this.Viewport
    }
};
var CNOA_main_system_core, CNOA_main_system_coreClass;
CNOA_main_system_coreClass = CNOA.Class.create();
CNOA_main_system_coreClass.prototype = {
    init: function() {
        _this = this;
        this.baseUrl = "";
        this.ID_btn_save = Ext.id();
        this.baseField = [{
            xtype: "fieldset",
            title: lang("coreSet"),
            defaults: {
                xtype: "textfield",
                width: 470
            },
            items: [{
                name: "name",
                fieldLabel: lang("systemName"),
                inputType: "text",
                allowBlank: false,
                maxLength: 20,
                maxLengthText: lang("noMore20"),
                validationEvent: "click"
            },
                {
                    xtype: "radiogroup",
                    fieldLabel: lang("enablePasswordFun"),
                    allowBlank: false,
                    style: "margin-top:15px",
                    name: "operatortypeGroup",
                    items: [{
                        boxLabel: lang("anyOneAgrees"),
                        name: "operatortype",
                        inputValue: "1"
                    },
                        {
                            boxLabel: lang("everyoneAgree"),
                            name: "operatortype",
                            inputValue: "2",
                            checked: true
                        }]
                }]
        }];
        this.formPanel = new Ext.form.FormPanel({
            border: false,
            labelWidth: 80,
            labelAlign: "right",
            waitMsgTarget: true,
            region: "center",
            layout: "fit",
            items: [{
                xtype: "panel",
                border: false,
                bodyStyle: "padding:10px",
                layout: "form",
                items: [this.baseField]
            }]
        });
        this.mainPanel = new Ext.Panel({
            collapsible: false,
            hideBorders: true,
            border: false,
            autoScroll: true,
            layout: "border",
            items: [this.formPanel],
            tbar: [{
                iconCls: "icon-system-refresh",
                tooltip: lang("refresh"),
                text: lang("refresh")
            },
                {
                    text: lang("reset"),
                    iconCls: "icon-order-s"
                },
                {
                    text: lang("save"),
                    iconCls: "icon-btn-save",
                    id: this.ID_btn_save,
                    handler: function() {
                        _this.submitForm()
                    }.createDelegate(this)
                }]
        })
    },
    submitForm: function(b) {
        var d = this;
        var c = this.formPanel.getForm();
        if (c.isValid()) {
            c.submit({
                url: this.baseUrl,
                waitTitle: lang("notice"),
                method: "POST",
                waitMsg: lang("waiting"),
                params: {},
                success: function(e, f) {
                    CNOA.msg.alert(f.result.msg,
                        function() {})
                }.createDelegate(this),
                failure: function(e, f) {
                    CNOA.msg.alert(f.result.msg)
                }.createDelegate(this)
            })
        } else {
            var a = Ext.getCmp(this.ID_btn_save).getEl().getBox();
            a = [a.x + 35, a.y + 26];
            CNOA.miniMsg.alert(lang("formValid"), a)
        }
    }
};
var CNOA_main_system_desktop, CNOA_main_system_desktop_Class;
var CNOA_main_system_desktop_edit, CNOA_main_system_desktop_edit_Class;
CNOA_main_system_desktop_Class = CNOA.Class.create();
CNOA_main_system_desktop_Class.prototype = {
    init: function() {
        this.fields = [{
            name: "id",
            mapping: "id"
        },
            {
                name: "name",
                mapping: "name"
            },
            {
                name: "app",
                mapping: "app"
            },
            {
                name: "about",
                mapping: "about"
            },
            {
                name: "status",
                mapping: "status"
            }];
        this.store = new Ext.data.Store({
            proxy: new Ext.data.HttpProxy({
                url: "index.php?app=main&func=system&action=desktop&task=getJsonData"
            }),
            reader: new Ext.data.JsonReader({
                totalProperty: "total",
                root: "data",
                fields: this.fields
            })
        });
        this.store.load({
            params: {
                start: 0
            }
        });
        this.sm = new Ext.grid.CheckboxSelectionModel();
        this.colModel = new Ext.grid.ColumnModel([new Ext.grid.RowNumberer(), this.sm, {
            header: "id",
            dataIndex: "id",
            width: 20,
            sortable: false,
            hidden: true
        },
            {
                header: lang("name"),
                dataIndex: "name",
                width: 30,
                sortable: false
            },
            {
                header: lang("belongApp"),
                dataIndex: "app",
                width: 30,
                sortable: false
            },
            {
                header: lang("enable"),
                dataIndex: "status",
                width: 30,
                sortable: false,
                renderer: this.statusCheck
            },
            {
                header: lang("description"),
                dataIndex: "about",
                width: 120,
                sortable: false
            }]);
        this.grid = new Ext.grid.GridPanel({
            stripeRows: true,
            store: this.store,
            region: "center",
            loadMask: {
                msg: lang("waiting")
            },
            cm: this.colModel,
            sm: this.sm,
            hideBorders: true,
            viewConfig: {
                forceFit: true
            },
            listeners: {
                rowdblclick: function(a, b) {
                    CNOA_main_system_desktop.editPanel()
                }
            },
            autoWidth: true
        });
        this.pagingBar = new Ext.PagingToolbar({
            displayInfo: true,
            emptyMsg: lang("pagingToolbarEmptyMsg"),
            displayMsg: lang("pagingToolbarDisplayMsg"),
            store: this.store,
            pageSize: 15
        });
        this.tbar = new Ext.Toolbar([{
            handler: function(a, b) {
                CNOA_main_system_desktop_edit = new CNOA_main_system_desktop_edit_Class("add");
                CNOA_main_system_desktop_edit.show()
            }.createDelegate(this),
            iconCls: "icon-utils-s-add",
            tooltip: lang("add"),
            text: lang("add")
        },
            {
                handler: function(a, b) {
                    this.editPanel()
                }.createDelegate(this),
                iconCls: "icon-utils-s-edit",
                tooltip: lang("modify"),
                text: lang("modify")
            },
            {
                handler: function(a, b) {
                    this.store.reload()
                }.createDelegate(this),
                iconCls: "icon-system-refresh",
                tooltip: lang("refresh"),
                text: lang("refresh")
            },
            {
                text: lang("del"),
                tooltip: lang("del"),
                iconCls: "icon-utils-s-delete",
                handler: function(a, b) {
                    var c = this.grid.getSelectionModel().getSelections();
                    if (c.length == 0) {
                        CNOA.msg.alert(lang("mustSelectOneRow"))
                    } else {
                        CNOA.msg.cf(lang("userInfoConfirmToDelete"),
                            function(e) {
                                if (e == "yes") {
                                    if (c) {
                                        var f = "";
                                        for (var d = 0; d < c.length; d++) {
                                            f += c[d].get("id") + ","
                                        }
                                        CNOA_main_system_desktop.deleteRecord(f)
                                    }
                                }
                            })
                    }
                }.createDelegate(this)
            },
            "<span style='color:#999'>" + lang("dblclickToEdit") + "</span>", "->", {
                xtype: "cnoa_helpBtn",
                helpid: 72
            }]);
        this.mainPanel = new Ext.Panel({
            collapsible: false,
            hideBorders: true,
            border: false,
            layout: "border",
            autoScroll: true,
            items: [this.grid],
            tbar: this.tbar
        })
    },
    editPanel: function() {
        var a = this.grid.getSelectionModel().getSelections();
        if (a.length == 0) {
            CNOA.msg.alert(lang("mustSelectOneRow"))
        } else {
            CNOA_main_system_desktop_edit = new CNOA_main_system_desktop_edit_Class("edit");
            CNOA_main_system_desktop_edit.show();
            CNOA_main_system_desktop_edit.loadFormData(a[0].get("id"))
        }
    },
    deleteRecord: function(a) {
        Ext.Ajax.request({
            url: "index.php?app=main&func=system&action=userinfo&task=delete",
            method: "POST",
            params: {
                ids: a
            },
            success: function(c) {
                var b = Ext.decode(c.responseText);
                if (b.success === true) {
                    CNOA.msg.alert(b.msg,
                        function() {
                            CNOA_main_system_desktop.store.reload()
                        })
                } else {
                    CNOA.msg.alert(b.msg)
                }
            }
        })
    },
    statusCheck: function(a) {
        return "<span style='color:" + (a == 1 ? "red;'>" + lang("enable") : "green;'>" + lang("noUse")) + "</span>"
    }
};
CNOA_main_system_desktop_edit_Class = CNOA.Class.create();
CNOA_main_system_desktop_edit_Class.prototype = {
    init: function(a) {
        this.tp = a;
        this.baseUrl = "index.php?app=main&func=system&action=desktop&task=";
        this.title = a == "edit" ? lang("editDesktopApp") : lang("addDesktopApp");
        this.action = a == "edit" ? "edit": "add";
        this.edit_id = 0;
        this.appListStore = new Ext.data.Store({
            proxy: new Ext.data.MemoryProxy(CNOA.main.system.desktop.DestTopAppList),
            reader: new Ext.data.JsonReader({
                    root: "data"
                },
                [{
                    name: "value"
                },
                    {
                        name: "app"
                    }])
        });
        this.appListStore.load();
        this.formPanel = new Ext.form.FormPanel({
            autoWidth: true,
            border: false,
            height: 166,
            labelWidth: 70,
            labelAlign: "right",
            bodyStyle: "padding:10px;",
            hideBorders: true,
            waitMsgTarget: true,
            defaults: {
                width: 270,
                xtype: "textfield"
            },
            items: [{
                name: "name",
                fieldLabel: lang("name"),
                inputType: "text",
                allowBlank: false,
                maxLength: 40,
                maxLengthText: lang("maxLengthText") + 40,
                validationEvent: "click"
            },
                new Ext.form.ComboBox({
                    fieldLabel: lang("belongApp"),
                    name: "app",
                    store: this.appListStore,
                    hiddenName: "app",
                    allowBlank: false,
                    valueField: "value",
                    displayField: "app",
                    mode: "local",
                    triggerAction: "all",
                    forceSelection: true,
                    editable: false,
                    value: ""
                }), new Ext.form.ComboBox({
                    fieldLabel: lang("belongApp"),
                    name: "column",
                    store: new Ext.data.SimpleStore({
                        fields: ["value", "column"],
                        data: [["0", lang("column1")], ["1", lang("column2")], ["2", lang("column3")]]
                    }),
                    hiddenName: "column",
                    valueField: "value",
                    displayField: "column",
                    mode: "local",
                    allowBlank: false,
                    triggerAction: "all",
                    forceSelection: true,
                    editable: false,
                    value: ""
                }), {
                    xtype: "radiogroup",
                    fieldLabel: lang("isEnable"),
                    allowBlank: false,
                    items: [{
                        boxLabel: lang("enable"),
                        name: "status",
                        inputValue: 1,
                        checked: true
                    },
                        {
                            boxLabel: lang("noUse"),
                            name: "status",
                            inputValue: 0
                        }]
                },
                {
                    name: "code",
                    fieldLabel: lang("onlyCode"),
                    inputType: "text",
                    allowBlank: false,
                    maxLength: 40,
                    width: 410,
                    maxLengthText: lang("maxLengthText") + 40,
                    validationEvent: "click"
                },
                {
                    name: "icon",
                    fieldLabel: lang("icon"),
                    inputType: "text",
                    allowBlank: false,
                    width: 410,
                    maxLength: 40,
                    maxLengthText: lang("maxLengthText") + 40,
                    validationEvent: "click"
                },
                {
                    xtype: "textarea",
                    name: "about",
                    fieldLabel: lang("description"),
                    inputType: "text",
                    maxLength: 255,
                    height: 110,
                    width: 410,
                    maxLengthText: lang("maxLengthText") + 255,
                    validationEvent: "click"
                }]
        });
        this.mainPanel = new Ext.Window({
            width: 530,
            height: 400,
            layout: "fit",
            resizable: false,
            autoDistory: true,
            modal: true,
            maskDisabled: true,
            items: [this.formPanel],
            plain: true,
            title: this.title,
            buttonAlign: "right",
            buttons: [{
                text: lang("save"),
                iconCls: "icon-btn-save",
                handler: function() {
                    this.submitForm({
                        close: true
                    })
                }.createDelegate(this)
            },
                {
                    text: lang("apply"),
                    hidden: this.action == "edit" ? false: true,
                    iconCls: "icon-btn-save",
                    handler: function() {
                        this.submitForm({
                            close: false
                        })
                    }.createDelegate(this)
                },
                {
                    text: lang("close"),
                    iconCls: "icon-dialog-cancel",
                    handler: function() {
                        this.close()
                    }.createDelegate(this)
                }]
        })
    },
    show: function() {
        this.mainPanel.show()
    },
    close: function() {
        this.mainPanel.close()
    },
    submitForm: function(a) {
        if (this.formPanel.getForm().isValid()) {
            this.formPanel.getForm().submit({
                url: this.baseUrl + this.tp,
                waitTitle: lang("notice"),
                method: "POST",
                waitMsg: lang("waiting"),
                params: {
                    id: this.edit_id,
                    job: "edit"
                },
                success: function(b, c) {
                    CNOA.msg.alert(c.result.msg,
                        function() {
                            CNOA_main_system_desktop.store.reload();
                            if (a.close) {
                                CNOA_main_system_desktop_edit.close()
                            }
                        })
                }.createDelegate(this),
                failure: function(b, c) {
                    CNOA.msg.alert(c.result.msg)
                }.createDelegate(this)
            })
        }
    },
    loadFormData: function(b) {
        var a = this;
        this.formPanel.getForm().load({
            url: "index.php?app=main&func=system&action=desktop&task=edit",
            params: {
                id: b,
                job: "loadData"
            },
            method: "POST",
            waitMsg: lang("waiting"),
            success: function(c, d) {
                a.formPanel.getForm().findRadioCheckBoxGroupField("status").setValue(d.result.data.status);
                a.edit_id = b
            },
            failure: function(c, d) {
                CNOA.msg.alert(d.result.msg,
                    function() {
                        a.close()
                    })
            }
        })
    }
};
var CNOA_main_system_permit, CNOA_main_system_permit_Class;
var CNOA_main_system_permit_edit, CNOA_main_system_permit_edit_Class;
CNOA_main_system_permit_Class = CNOA.Class.create();
CNOA_main_system_permit_Class.prototype = {
    init: function() {
        _this = this;
        this.fields = [{
            name: "id",
            mapping: "id"
        },
            {
                name: "name",
                mapping: "name"
            },
            {
                name: "code",
                mapping: "code"
            },
            {
                name: "about",
                mapping: "about"
            },
            {
                name: "type",
                mapping: "type"
            }];
        this.store = new Ext.data.Store({
            proxy: new Ext.data.HttpProxy({
                url: null
            }),
            reader: new Ext.data.JsonReader({
                totalProperty: "total",
                root: "data",
                fields: this.fields
            })
        });
        this.sm = new Ext.grid.CheckboxSelectionModel();
        this.colModel = new Ext.grid.ColumnModel([new Ext.grid.RowNumberer(), this.sm, {
            header: "id",
            dataIndex: "id",
            width: 20,
            sortable: true,
            hidden: true
        },
            {
                header: lang("fieldName"),
                dataIndex: "name",
                width: 30,
                sortable: true
            },
            {
                header: lang("fieldCode"),
                dataIndex: "code",
                width: 30,
                sortable: true
            },
            {
                header: lang("fieldType"),
                dataIndex: "type",
                width: 30,
                sortable: true
            },
            {
                header: lang("fieldDescription"),
                dataIndex: "about",
                width: 120,
                sortable: true
            }]);
        this.grid = new Ext.grid.GridPanel({
            stripeRows: true,
            store: this.store,
            region: "center",
            loadMask: {
                msg: lang("waiting")
            },
            cm: this.colModel,
            sm: this.sm,
            hideBorders: true,
            viewConfig: {
                forceFit: true
            },
            listeners: {
                rowdblclick: function(a, b) {
                    CNOA_main_system_permit.editPanel()
                }
            },
            autoWidth: true
        });
        this.pagingBar = new Ext.PagingToolbar({
            displayInfo: true,
            emptyMsg: lang("pagingToolbarEmptyMsg"),
            displayMsg: lang("pagingToolbarDisplayMsg"),
            store: this.store,
            pageSize: 15
        });
        this.tbar = new Ext.Toolbar([{
            handler: function(a, b) {
                CNOA_main_system_permit_edit = new CNOA_main_system_permit_edit_Class("add");
                CNOA_main_system_permit_edit.show()
            }.createDelegate(this),
            iconCls: "icon-utils-s-add",
            tooltip: lang("add"),
            text: lang("add")
        },
            {
                handler: function(a, b) {
                    this.editPanel()
                }.createDelegate(this),
                iconCls: "icon-utils-s-edit",
                tooltip: lang("modify"),
                text: lang("modify")
            },
            {
                handler: function(a, b) {
                    this.store.reload()
                }.createDelegate(this),
                iconCls: "icon-system-refresh",
                tooltip: lang("refresh"),
                text: lang("refresh")
            },
            {
                text: lang("del"),
                tooltip: lang("del"),
                iconCls: "icon-utils-s-delete",
                handler: function(a, b) {
                    var c = this.grid.getSelectionModel().getSelections();
                    if (c.length == 0) {
                        CNOA.msg.alert(lang("mustSelectOneRow"))
                    } else {
                        CNOA.msg.cf(lang("userInfoConfirmToDelete"),
                            function(e) {
                                if (e == "yes") {
                                    if (c) {
                                        var f = "";
                                        for (var d = 0; d < c.length; d++) {
                                            f += c[d].get("id") + ","
                                        }
                                        CNOA_main_system_permit.deleteRecord(f)
                                    }
                                }
                            })
                    }
                }.createDelegate(this)
            },
            "<span style='color:#999'>" + lang("dblclickToEdit") + "</span>", "->", {
                xtype: "cnoa_helpBtn",
                helpid: 73
            }]);
        this.mainPanel = new Ext.Panel({
            collapsible: false,
            hideBorders: true,
            border: false,
            layout: "border",
            autoScroll: true,
            items: [this.grid],
            tbar: this.tbar
        })
    },
    editPanel: function() {
        var a = this.grid.getSelectionModel().getSelections();
        if (a.length == 0) {
            CNOA.msg.alert(lang("mustSelectOneRow"))
        } else {
            CNOA_main_system_permit_edit = new CNOA_main_system_permit_edit_Class("edit");
            CNOA_main_system_permit_edit.show();
            CNOA_main_system_permit_edit.loadFormData(a[0].get("id"))
        }
    },
    deleteRecord: function(a) {
        Ext.Ajax.request({
            url: "index.php?app=main&func=system&action=userinfo&task=delete",
            method: "POST",
            params: {
                ids: a
            },
            success: function(c) {
                var b = Ext.decode(c.responseText);
                if (b.success === true) {
                    CNOA.msg.alert(b.msg,
                        function() {
                            CNOA_main_system_permit.store.reload()
                        })
                } else {
                    CNOA.msg.alert(b.msg)
                }
            }
        })
    }
};
CNOA_main_system_permit_edit_Class = CNOA.Class.create();
CNOA_main_system_permit_edit_Class.prototype = {
    init: function(a) {
        _this = this;
        _this.tp = a;
        _this.baseUrl = "index.php?app=main&func=system&action=userinfo&task=";
        _this.title = a == "edit" ? lang("editPermissions") : lang("newPermissions");
        _this.action = a == "edit" ? "edit": "add";
        _this.edit_id = 0;
        this.formPanel = new Ext.form.FormPanel({
            autoWidth: true,
            border: false,
            height: 166,
            labelWidth: 70,
            labelAlign: "right",
            bodyStyle: "padding:10px;",
            hideBorders: true,
            waitMsgTarget: true,
            defaults: {
                width: 270,
                xtype: "textfield"
            },
            items: [{
                name: "name",
                fieldLabel: lang("fieldName"),
                inputType: "text",
                allowBlank: false,
                maxLength: 40,
                maxLengthText: lang("maxLengthText") + 40,
                validationEvent: "click"
            },
                {
                    name: "code",
                    fieldLabel: lang("fieldCode"),
                    inputType: "text",
                    allowBlank: false,
                    maxLength: 40,
                    maxLengthText: lang("maxLengthText") + 40,
                    validationEvent: "click"
                },
                {
                    xtype: "radiogroup",
                    fieldLabel: lang("fieldType"),
                    allowBlank: false,
                    items: [{
                        boxLabel: lang("singleLine"),
                        name: "type",
                        inputValue: 1,
                        checked: true
                    },
                        {
                            boxLabel: lang("multiLine"),
                            name: "type",
                            inputValue: 2
                        }]
                },
                {
                    xtype: "textarea",
                    name: "about",
                    fieldLabel: lang("fieldDescription"),
                    inputType: "text",
                    maxLength: 255,
                    height: 60,
                    maxLengthText: lang("maxLengthText") + 255,
                    validationEvent: "click"
                }]
        });
        this.mainPanel = new Ext.Window({
            width: 400,
            height: 240,
            resizable: false,
            autoDistory: true,
            modal: true,
            maskDisabled: true,
            items: [this.formPanel],
            plain: true,
            title: _this.title,
            buttonAlign: "center",
            buttons: [{
                text: lang("save"),
                iconCls: "icon-order-s-accept",
                handler: function() {
                    if (_this.formPanel.getForm().isValid()) {
                        this.formPanel.getForm().submit({
                            url: this.baseUrl + this.tp,
                            waitTitle: lang("notice"),
                            method: "POST",
                            waitMsg: lang("waiting"),
                            params: {
                                id: this.edit_id,
                                job: "edit"
                            },
                            success: function(b, c) {
                                CNOA.msg.alert(c.result.msg,
                                    function() {
                                        CNOA_main_system_permit.store.reload();
                                        CNOA_main_system_permit_edit.close()
                                    })
                            }.createDelegate(this),
                            failure: function(b, c) {
                                CNOA.msg.alert(c.result.msg)
                            }.createDelegate(this)
                        })
                    }
                }.createDelegate(this)
            },
                {
                    text: lang("close"),
                    iconCls: "icon-dialog-cancel",
                    handler: function() {
                        this.close()
                    }.createDelegate(this)
                }]
        })
    },
    show: function() {
        this.mainPanel.show()
    },
    close: function() {
        this.mainPanel.close()
    },
    loadFormData: function(a) {
        _this.formPanel.getForm().load({
            url: "index.php?app=main&func=system&action=userinfo&task=edit",
            params: {
                id: a,
                job: "loadData"
            },
            method: "POST",
            waitMsg: lang("waiting"),
            success: function(b, c) {
                _this.formPanel.getForm().findRadioCheckBoxGroupField("type").setValue(c.result.data.type);
                _this.edit_id = a
            },
            failure: function(b, c) {
                CNOA.msg.alert(c.result.msg,
                    function() {
                        _this.close()
                    })
            }
        })
    }
};
var CNOA_main_system_userinfo, CNOA_main_system_userinfo_Class;
var CNOA_main_system_userinfo_edit, CNOA_main_system_userinfo_edit_Class;
CNOA_main_system_userinfo_Class = CNOA.Class.create();
CNOA_main_system_userinfo_Class.prototype = {
    init: function() {
        this.fields = [{
            name: "id",
            mapping: "id"
        },
            {
                name: "name",
                mapping: "name"
            },
            {
                name: "code",
                mapping: "code"
            },
            {
                name: "about",
                mapping: "about"
            },
            {
                name: "type",
                mapping: "type"
            }];
        this.store = new Ext.data.Store({
            proxy: new Ext.data.HttpProxy({
                url: "index.php?app=main&func=system&action=userinfo&task=getJsonData"
            }),
            reader: new Ext.data.JsonReader({
                totalProperty: "total",
                root: "data",
                fields: this.fields
            })
        });
        this.store.load({
            params: {
                start: 0
            }
        });
        this.sm = new Ext.grid.CheckboxSelectionModel();
        this.colModel = new Ext.grid.ColumnModel([new Ext.grid.RowNumberer(), this.sm, {
            header: "id",
            dataIndex: "id",
            width: 20,
            sortable: true,
            hidden: true
        },
            {
                header: lang("fieldName"),
                dataIndex: "name",
                width: 30,
                sortable: true
            },
            {
                header: lang("fieldCode"),
                dataIndex: "code",
                width: 30,
                sortable: true
            },
            {
                header: lang("fieldType"),
                dataIndex: "type",
                width: 30,
                sortable: true
            },
            {
                header: lang("fieldDescription"),
                dataIndex: "about",
                width: 120,
                sortable: true
            }]);
        this.grid = new Ext.grid.GridPanel({
            stripeRows: true,
            store: this.store,
            region: "center",
            loadMask: {
                msg: lang("waiting")
            },
            cm: this.colModel,
            sm: this.sm,
            hideBorders: true,
            viewConfig: {
                forceFit: true
            },
            listeners: {
                rowdblclick: function(a, b) {
                    CNOA_main_system_userinfo.editPanel()
                }
            },
            autoWidth: true
        });
        this.pagingBar = new Ext.PagingToolbar({
            displayInfo: true,
            emptyMsg: lang("pagingToolbarEmptyMsg"),
            displayMsg: lang("pagingToolbarDisplayMsg"),
            store: this.store,
            pageSize: 15
        });
        this.tbar = new Ext.Toolbar([{
            handler: function(a, b) {
                CNOA_main_system_userinfo_edit = new CNOA_main_system_userinfo_edit_Class("add");
                CNOA_main_system_userinfo_edit.show()
            }.createDelegate(this),
            iconCls: "icon-utils-s-add",
            tooltip: lang("add"),
            text: lang("add")
        },
            "-", {
                handler: function(a, b) {
                    this.editPanel()
                }.createDelegate(this),
                iconCls: "icon-utils-s-edit",
                tooltip: lang("modify"),
                text: lang("modify")
            },
            "-", {
                handler: function(a, b) {
                    this.store.reload()
                }.createDelegate(this),
                iconCls: "icon-system-refresh",
                tooltip: lang("refresh"),
                text: lang("refresh")
            },
            "-", {
                text: lang("del"),
                tooltip: lang("del"),
                iconCls: "icon-utils-s-delete",
                handler: function(a, b) {
                    var c = this.grid.getSelectionModel().getSelections();
                    if (c.length == 0) {
                        CNOA.msg.alert(lang("mustSelectOneRow"))
                    } else {
                        CNOA.msg.cf(lang("userInfoConfirmToDelete"),
                            function(e) {
                                if (e == "yes") {
                                    if (c) {
                                        var f = "";
                                        for (var d = 0; d < c.length; d++) {
                                            f += c[d].get("id") + ","
                                        }
                                        CNOA_main_system_userinfo.deleteRecord(f)
                                    }
                                }
                            })
                    }
                }.createDelegate(this)
            },
            "-", "<span style='color:#999'>" + lang("dblclickToEdit") + "</span>", "->", {
                xtype: "cnoa_helpBtn",
                helpid: 74
            }]);
        this.mainPanel = new Ext.Panel({
            collapsible: false,
            hideBorders: true,
            border: false,
            layout: "border",
            autoScroll: true,
            items: [this.grid],
            tbar: this.tbar
        })
    },
    editPanel: function() {
        var a = this.grid.getSelectionModel().getSelections();
        if (a.length == 0) {
            CNOA.msg.alert(lang("mustSelectOneRow"))
        } else {
            CNOA_main_system_userinfo_edit = new CNOA_main_system_userinfo_edit_Class("edit");
            CNOA_main_system_userinfo_edit.show();
            CNOA_main_system_userinfo_edit.loadFormData(a[0].get("id"))
        }
    },
    deleteRecord: function(a) {
        Ext.Ajax.request({
            url: "index.php?app=main&func=system&action=userinfo&task=delete",
            method: "POST",
            params: {
                ids: a
            },
            success: function(c) {
                var b = Ext.decode(c.responseText);
                if (b.success === true) {
                    CNOA.msg.alert(b.msg,
                        function() {
                            CNOA_main_system_userinfo.store.reload()
                        })
                } else {
                    CNOA.msg.alert(b.msg)
                }
            }
        })
    }
};
CNOA_main_system_userinfo_edit_Class = CNOA.Class.create();
CNOA_main_system_userinfo_edit_Class.prototype = {
    init: function(a) {
        this.tp = a;
        this.baseUrl = "index.php?app=main&func=system&action=userinfo&task=";
        this.title = a == "edit" ? lang("modify") + lang("field") : lang("add") + lang("field");
        this.action = a == "edit" ? "edit": "add";
        this.edit_id = 0;
        this.formPanel = new Ext.form.FormPanel({
            autoWidth: true,
            border: false,
            height: 166,
            labelWidth: 70,
            labelAlign: "right",
            bodyStyle: "padding:10px;",
            hideBorders: true,
            waitMsgTarget: true,
            defaults: {
                width: 270,
                xtype: "textfield"
            },
            items: [{
                name: "name",
                fieldLabel: lang("fieldName"),
                inputType: "text",
                allowBlank: false,
                maxLength: 40,
                maxLengthText: lang("maxLengthText") + 40,
                validationEvent: "click"
            },
                {
                    name: "code",
                    fieldLabel: lang("fieldCode"),
                    inputType: "text",
                    allowBlank: false,
                    maxLength: 40,
                    maxLengthText: lang("maxLengthText") + 40,
                    validationEvent: "click"
                },
                {
                    xtype: "radiogroup",
                    fieldLabel: lang("fieldType"),
                    allowBlank: false,
                    items: [{
                        boxLabel: "单行文本框",
                        name: "type",
                        inputValue: 1,
                        checked: true
                    },
                        {
                            boxLabel: "多行文本框",
                            name: "type",
                            inputValue: 2
                        }]
                },
                {
                    xtype: "textarea",
                    name: "about",
                    fieldLabel: lang("fieldDescription"),
                    inputType: "text",
                    maxLength: 255,
                    height: 60,
                    maxLengthText: lang("maxLengthText") + 255,
                    validationEvent: "click"
                }]
        });
        this.mainPanel = new Ext.Window({
            width: 400,
            height: 240,
            resizable: false,
            autoDistory: true,
            modal: true,
            maskDisabled: true,
            items: [this.formPanel],
            plain: true,
            title: this.title,
            buttonAlign: "right",
            buttons: [{
                text: lang("save"),
                iconCls: "icon-btn-save",
                handler: function() {
                    this.submitForm({
                        close: true
                    })
                }.createDelegate(this)
            },
                {
                    text: lang("apply"),
                    hidden: this.action == "edit" ? false: true,
                    iconCls: "icon-btn-save",
                    handler: function() {
                        this.submitForm({
                            close: false
                        })
                    }.createDelegate(this)
                },
                {
                    text: lang("close"),
                    iconCls: "icon-dialog-cancel",
                    handler: function() {
                        this.close()
                    }.createDelegate(this)
                }]
        })
    },
    show: function() {
        this.mainPanel.show()
    },
    close: function() {
        this.mainPanel.close()
    },
    submitForm: function(a) {
        if (this.formPanel.getForm().isValid()) {
            this.formPanel.getForm().submit({
                url: this.baseUrl + this.tp,
                waitTitle: lang("notice"),
                method: "POST",
                waitMsg: lang("waiting"),
                params: {
                    id: this.edit_id,
                    job: "edit"
                },
                success: function(b, c) {
                    CNOA.msg.alert(c.result.msg,
                        function() {
                            CNOA_main_system_userinfo.store.reload();
                            if (a.close) {
                                CNOA_main_system_userinfo_edit.close()
                            }
                        })
                }.createDelegate(this),
                failure: function(b, c) {
                    CNOA.msg.alert(c.result.msg)
                }.createDelegate(this)
            })
        }
    },
    loadFormData: function(b) {
        var a = this;
        this.formPanel.getForm().load({
            url: "index.php?app=main&func=system&action=userinfo&task=edit",
            params: {
                id: b,
                job: "loadData"
            },
            method: "POST",
            waitMsg: lang("waiting"),
            success: function(c, d) {
                a.formPanel.getForm().findRadioCheckBoxGroupField("type").setValue(d.result.data.type);
                a.edit_id = b
            },
            failure: function(c, d) {
                CNOA.msg.alert(d.result.msg,
                    function() {
                        a.close()
                    })
            }
        })
    }
};
var CNOA_main_system_registClass, CNOA_main_system_regist;
CNOA_main_system_registClass = CNOA.Class.create();
CNOA_main_system_registClass.prototype = {
    init: function() {
        var b = this;
        try {
            CNOA.config.scblg == 1 ? null: null
        } catch(a) {
            Ext.ns("CNOA.config.scblg");
            CNOA.config.scblg = {}
        }
        this.actionUrl = "index.php?app=main&func=system&action=regist";
        this.ID_btn_save = Ext.id();
        this.ID_btn_apply = Ext.id();
        this.ID_btn_close = Ext.id();
        this.formPanel = new Ext.form.FormPanel({
            fileUpload: true,
            hideBorders: true,
            border: false,
            waitMsgTarget: true,
            labelWidth: 60,
            items: [{
                xtype: "panel",
                border: false,
                layout: "form",
                bodyStyle: "padding: 10px",
                items: [{
                    xtype: "displayfield",
                    hideLabel: true,
                    value: lang("completeAuthor")
                },
                    {
                        xtype: "textfield",
                        name: "mkey",
                        style: "height:22px",
                        readOnly: true,
                        value: "",
                        fieldLabel: "机器ID"
                    },
                    {
                        xtype: "fileuploadfield",
                        name: "keyfile",
                        allowBlank: false,
                        style: "height:22px",
                        width: 340,
                        blankText: lang("authorizationFile"),
                        buttonCfg: {
                            text: lang("browse")
                        },
                        fieldLabel: "授权文件"
                    },
                    {
                        xtype: "hidden",
                        name: "op"
                    },
                    {
                        xtype: "displayfield",
                        hideLabel: true,
                        hidden: CNOA.config.scblg.sclo == 1 ? false: true,
                        value: "<font size='3'>" + lang("ifVersionExpires") + "<br>" + lang("clickHere") + "[<a href='http://www.cnoa.cn/' style='color:red;' target='_blank'>" + lang("appToExtend") + "</a>]</font>"
                    }]
            }]
        });
        this.mainPanel = new Ext.Window({
            width: 440,
            height: 220,
            modal: CNOA.main.system.regist.modal == 1 ? true: false,
            autoDestroy: true,
            closeAction: "close",
            resizable: false,
            closable: CNOA.main.system.regist.modal == 1 ? false: true,
            title: lang("register") + (CNOA.config.scblg.sclo == 1 ? lang("xiezhongOA") : ""),
            layout: "fit",
            items: [this.formPanel],
            listeners: {
                close: function() {
                    Ext.destroy(CNOA_main_system_regist);
                    CNOA_main_system_regist = null;
                    b.closeTab();
                    try {
                        b.closeTab()
                    } catch(c) {}
                },
                afterrender: function() {
                    b.loadForm()
                }
            },
            buttons: [{
                text: lang("save"),
                id: this.ID_btn_save,
                iconCls: "icon-btn-save",
                handler: function() {
                    this.submitForm({
                        close: true
                    })
                }.createDelegate(this)
            },
                {
                    text: lang("cancel"),
                    id: this.ID_btn_close,
                    hidden: CNOA.main.system.regist.modal == 1 ? true: false,
                    iconCls: "icon-dialog-cancel",
                    handler: function() {
                        b.close()
                    }
                }]
        })
    },
    show: function() {
        this.mainPanel.show()
    },
    close: function() {
        this.mainPanel.close()
    },
    closeTab: function() {
        mainPanel.closeTab(CNOA.main.system.regist.parentID.replace("docs-", ""))
    },
    submitForm: function(b) {
        var d = this;
        var c = this.formPanel.getForm();
        if (c.isValid()) {
            c.submit({
                url: this.actionUrl + "&task=registByKeyFile",
                waitTitle: lang("notice"),
                method: "POST",
                waitMsg: lang("waiting"),
                params: {},
                success: function(e, f) {
                    CNOA.msg.alert(f.result.msg,
                        function() {
                            location.reload()
                        })
                }.createDelegate(this),
                failure: function(e, f) {
                    if (f.result.msg == "needop") {
                        d.showopwin(c)
                    } else {
                        if (f.result.msg == "wrongop") {
                            CNOA.msg.alert("验证码不正确",
                                function() {
                                    d.showopwin(c)
                                })
                        } else {
                            CNOA.msg.alert(f.result.msg)
                        }
                    }
                }.createDelegate(this)
            })
        } else {
            var a = Ext.getCmp(this.ID_btn_save).getEl().getBox();
            a = [a.x + 35, a.y + 26];
            CNOA.miniMsg.alert(lang("formValid"), a)
        }
    },
    showopwin: function(b) {
        var c = this;
        var a = new Ext.MessageBox.promptWindow({
            title: "请输入验证码",
            multiline: false,
            border: false,
            height: 100,
            txCfg: {
                value: (function() {}).createDelegate(this)()
            },
            listeners: {
                submit: function(d, e) {
                    b.findField("op").setValue(e);
                    d.close();
                    c.submitForm()
                }.createDelegate(this)
            }
        }).show()
    },
    loadForm: function(a) {
        var c = this;
        var b = this.formPanel.getForm();
        b.load({
            url: this.actionUrl + "&task=getMkey",
            waitTitle: lang("notice"),
            method: "POST",
            waitMsg: lang("waiting"),
            params: {},
            success: function(d, e) {}.createDelegate(this),
            failure: function(d, e) {
                CNOA.msg.alert(e.result.msg)
            }.createDelegate(this)
        })
    }
};
var CNOA_main_system_funcSettingClass;
CNOA_main_system_funcSettingClass = CNOA.Class.create();
CNOA_main_system_funcSettingClass.prototype = {
    init: function() {
        var a = this;
        this.newNameList = {};
        this.advMode = CNOA.advMode;
        this.baseUrl = "main/system";
        this.menuTree = new Ext.tree.ColumnTree({
            border: false,
            animate: false,
            rootVisible: false,
            autoScroll: true,
            enableDD: true,
            width: 670,
            region: "center",
            itemqtip: lang("renameMenuNotice"),
            checkModel: "cascade",
            onlyLeafCheckable: false,
            loader: new Ext.tree.TreeLoader({
                preloadChildren: true,
                clearOnLoad: false,
                dataUrl: this.baseUrl + "/getMenuTreeList" + (this.advMode ? "?blong=1": ""),
                uiProviders: {
                    col: Ext.ux.ColumnTreeCheckNodeUI
                },
                listeners: {
                    load: function(d, c, b) {
                        a.menuTree.root.expand(true);
                        a.initTree(c);
                        a.menuTree.root.collapse(true)
                    }
                }
            }),
            root: new Ext.tree.AsyncTreeNode({
                id: "0"
            }),
            columns: [{
                header: lang("menuName"),
                width: 300,
                dataIndex: "text"
            },
                {
                    header: lang("customMenuName"),
                    width: 210,
                    dataIndex: "newText"
                },
                {
                    header: lang("description"),
                    width: 350,
                    dataIndex: "about"
                }],
            tbar: ["<span class='cnoa_color_gray'>" + lang("orderMenuNotice") + "</span>"],
            contextMenu: this.contextMenu,
            listeners: {
                contextmenu: function(f, g) {
                    a.contextMenu.removeAll();
                    var d = {
                            id: "edit",
                            text: lang("modify")
                        },
                        b = {
                            id: "del",
                            text: lang("del")
                        },
                        j = {
                            id: "update",
                            text: lang("editCustomMenuName")
                        },
                        i = {
                            id: "advMode",
                            text: lang("developmentPattern")
                        };
                    if (f.attributes.isCustom) {
                        a.contextMenu.add(d);
                        if (f.childNodes.length == 0) {
                            a.contextMenu.add(b)
                        }
                    } else {
                        a.contextMenu.add(j);
                        if (a.advMode) {
                            a.contextMenu.add(i)
                        }
                    }
                    f.select();
                    var h = a.contextMenu;
                    h.contextNode = f;
                    h.showAt(g.getXY())
                },
                beforemovenode: function(f, e, c, d, b) {
                    if (c.id != d.id) {
                        return false
                    }
                }
            }
        });
        this.contextMenu = new Ext.menu.Menu({
            items: [{
                id: "update",
                text: lang("editCustomMenuName")
            }],
            listeners: {
                itemclick: function(d) {
                    switch (d.id) {
                        case "advMode":
                            a.setAdvMode(d.parentMenu.contextNode.id);
                            break;
                        case "update":
                            Ext.Msg.prompt(lang("fillMenuName"), lang("menuName"),
                                function(f, g) {
                                    if (f == "ok") {
                                        var h = d.parentMenu.contextNode.id;
                                        a.updateNewText(h, g)
                                    }
                                },
                                this, false, a.getNewText(d));
                            break;
                        case "edit":
                            var b = d.parentMenu.contextNode.attributes,
                                e = b.menuId.split("_").length,
                                c = "";
                            if (e == 3) {
                                c = "first"
                            } else {
                                if (e == 4) {
                                    c = "second"
                                } else {
                                    if (e == 5) {
                                        c = "third"
                                    }
                                }
                            }
                            a.addCustomMenu(c, b.id);
                            break;
                        case "del":
                            CNOA.msg.cf(lang("wantDelMenu"),
                                function(f) {
                                    if (f == "yes") {
                                        Ext.Ajax.request({
                                            url: a.baseUrl + "&task=delCustomMenu",
                                            params: {
                                                mid: d.parentMenu.contextNode.id
                                            },
                                            success: function(i) {
                                                var h = Ext.decode(i.responseText);
                                                if (h.success) {
                                                    var g = d.parentMenu.contextNode;
                                                    g.parentNode.removeChild(g, true);
                                                    CNOA.msg.notice2(h.msg)
                                                } else {
                                                    CNOA.msg.alert(h.msg)
                                                }
                                            }
                                        })
                                    }
                                });
                            break
                    }
                }
            }
        });
        this.mainPanel = new Ext.Panel({
            title: lang("funcMenuSet"),
            hideBorders: true,
            border: false,
            autoScroll: true,
            layout: "border",
            items: [this.menuTree],
            tbar: new Ext.Toolbar({
                items: [{
                    text: lang("save"),
                    iconCls: "icon-btn-save",
                    cls: "btn-blue4",
                    handler: function() {
                        a.doSave()
                    }.createDelegate(this)
                },
                    {
                        text: lang("reload"),
                        cls: "btn-green1",
                        iconCls: "icon-system-refresh",
                        handler: function() {
                            a.doReload()
                        }.createDelegate(this)
                    },
                    {
                        text: lang("addMenu"),
                        cls: "btn-blue4",
                        iconCls: "icon-utils-s-add",
                        menu: {
                            items: [{
                                text: lang("fistLevelMenu"),
                                handler: function() {
                                    a.addCustomMenu("first")
                                }
                            },
                                {
                                    text: lang("secondLevelMenu"),
                                    handler: function() {
                                        a.addCustomMenu("second")
                                    }
                                },
                                {
                                    text: lang("menu"),
                                    handler: function() {
                                        a.addCustomMenu("third")
                                    }
                                }]
                        }
                    },
                    {
                        text: lang("expand"),
                        tooltip: lang("expandMenuTip"),
                        enableToggle: true,
                        toggleHandler: function(b, c) {
                            if (c) {
                                b.setText(lang("collapse"));
                                b.setTooltip(lang("collapse"));
                                a.menuTree.root.expand(true)
                            } else {
                                b.setText(lang("expand"));
                                b.setTooltip(lang("expand"));
                                a.menuTree.root.collapse(true)
                            }
                        }
                    },
                    "->", {
                        xtype: "cnoa_helpBtn",
                        helpid: 84
                    }]
            })
        })
    },
    initTree: function(a) {
        var b = this;
        a.eachChild(function(c) {
            if (c.attributes.system == 1) {
                c.ui.checkbox.disabled = true
            }
            b.initTree(c)
        })
    },
    updateNewText: function(c, a) {
        var b = this;
        $($($("div[ext-tree-node-id=" + c + "]")).children()[1]).children().html(a);
        this.newNameList["id_" + c] = [c, a]
    },
    setAdvMode: function(h) {
        var c = this;
        var b, a, g;
        var e = function() {
            var i = a.getForm();
            if (i.isValid()) {
                i.submit({
                    url: c.baseUrl + "&task=saveBlongInfo",
                    method: "POST",
                    params: {
                        id: h
                    },
                    waitTitle: lang("notice"),
                    waitMsg: lang("waiting"),
                    success: function(f, j) {
                        CNOA.msg.notice2(j.result.msg);
                        g.close()
                    },
                    failure: function(f, j) {
                        CNOA.msg.alert(j.result.msg)
                    }
                })
            }
        };
        b = [{
            xtype: "displayfield",
            fieldLabel: lang("name"),
            name: "text"
        },
            {
                xtype: "displayfield",
                fieldLabel: "ID",
                name: "id2"
            },
            {
                xtype: "hidden",
                name: "id"
            },
            {
                xtype: "checkboxgroup",
                fieldLabel: lang("belong"),
                name: "blong",
                items: [{
                    boxLabel: lang("standardVersion") + "1",
                    inputValue: 1,
                    name: "blong[1]"
                },
                    {
                        boxLabel: lang("enterpriseEdition") + "2",
                        inputValue: 1,
                        name: "blong[2]"
                    },
                    {
                        boxLabel: lang("enhancedVersion") + "3",
                        inputValue: 1,
                        name: "blong[3]"
                    },
                    {
                        boxLabel: lang("govermentVersion") + "4",
                        inputValue: 1,
                        name: "blong[4]"
                    },
                    {
                        boxLabel: lang("ultimateEdition") + "5",
                        inputValue: 1,
                        name: "blong[5]"
                    }]
            }];
        a = new Ext.form.FormPanel({
            hideBorders: true,
            border: false,
            waitMsgTarget: true,
            labelWidth: 40,
            labelAlign: "right",
            bodyStyle: "padding: 10px",
            items: b
        });
        g = new Ext.Window({
            width: 500,
            height: 180,
            modal: true,
            resizable: false,
            title: lang("set2"),
            layout: "fit",
            items: a,
            buttons: [{
                text: lang("save"),
                iconCls: "icon-btn-save",
                cls: "btn-blue4",
                handler: function() {
                    e()
                }
            },
                {
                    text: lang("cancel"),
                    cls: "btn-red1",
                    iconCls: "icon-dialog-cancel",
                    handler: function() {
                        g.close()
                    }
                }]
        }).show();
        var d = a.getForm();
        d.load({
            url: c.baseUrl + "&task=loadBlongInfo",
            params: {
                id: h
            },
            method: "POST",
            waitMsg: lang("waiting"),
            success: function(f, i) {},
            failure: function(f, i) {
                CNOA.msg.alert(i.result.msg);
                g.close()
            }
        })
    },
    getNewText: function(a) {
        var c = a.parentMenu.contextNode.id;
        var b = $($($("div[ext-tree-node-id=" + c + "]")).children()[1]).children().html();
        b = b.substr(0, b.length - 6);
        return b
    },
    doSave: function() {
        var b = this;
        this.allTreeNodes = [];
        this.getChildNodes(this.menuTree.root);
        this.mainPanel.getEl().mask(lang("waiting"));
        var a = [];
        Ext.each(this.newNameList,
            function(c, d) {
                for (var d in c) {
                    a.push(c[d])
                }
            });
        Ext.Ajax.request({
            url: this.baseUrl + "&task=saveMenuList",
            method: "POST",
            params: {
                allnodes: Ext.encode(this.allTreeNodes),
                names: Ext.encode(a)
            },
            success: function(d) {
                b.mainPanel.getEl().unmask();
                var c = Ext.decode(d.responseText);
                if (c.success === true) {
                    CNOA.msg.alert("保存成功",
                        function() {
                            menuPanel.getMenuList()
                        })
                } else {
                    CNOA.msg.alert(c.msg)
                }
            }
        })
    },
    getChildNodes: function(a) {
        var b = this;
        this.allTreeNodes.push({
            id: a.id,
            checked: a.ui.isChecked()
        });
        if (a.childNodes.length > 0) {
            $.each(a.childNodes,
                function(c, d) {
                    b.getChildNodes(d)
                })
        }
    },
    doReload: function() {
        this.menuTree.root.reload()
    },
    addCustomMenu: function(n, b) {
        var m = this,
            a = [],
            h = Ext.id(),
            e = Ext.id();
        var g = function() {
            var o = new Ext.FormPanel({
                fileUpload: true,
                autoHeight: true,
                autoScroll: false,
                waitMsgTarget: true,
                hideBorders: true,
                border: false,
                bodyStyle: "padding: 5px;",
                buttonAlign: "right",
                items: [{
                    xtype: "fileuploadfield",
                    name: "icon",
                    allowBlank: false,
                    blankText: lang("updateFaceBlankText"),
                    buttonCfg: {
                        text: lang("browse")
                    },
                    hideLabel: true,
                    width: 370
                }],
                buttons: [{
                    text: lang("save"),
                    handler: function() {
                        if (o.getForm().isValid()) {
                            o.getForm().submit({
                                url: m.baseUrl + "&task=upIcon",
                                waitMsg: lang("waiting"),
                                params: {
                                    type: n
                                },
                                success: function(p, q) {
                                    loadCss(q.result.cssPath);
                                    $("#" + h).attr("class", "").addClass(q.result.msg);
                                    $("#" + e).val(q.result.msg);
                                    f.close()
                                },
                                failure: function(p, q) {
                                    CNOA.msg.alert(q.result.msg)
                                }
                            })
                        }
                    }.createDelegate(this)
                },
                    {
                        text: lang("close"),
                        handler: function() {
                            f.close()
                        }
                    }]
            });
            var f = new Ext.Window({
                width: 398,
                height: 123,
                autoScroll: false,
                modal: true,
                resizable: false,
                title: lang("uploadPic"),
                items: [o]
            }).show()
        };
        if (n != "first") {
            var d = new Ext.data.Store({
                autoLoad: true,
                proxy: new Ext.data.HttpProxy({
                    url: this.baseUrl + "&task=getMenus",
                    disableCaching: true
                }),
                reader: new Ext.data.JsonReader({
                    totalProperty: "total",
                    root: "data",
                    fields: [{
                        name: "id"
                    },
                        {
                            name: "text"
                        }]
                })
            });
            a = [{
                xtype: "combo",
                fieldLabel: lang("fistLevelMenu"),
                allowBlank: false,
                editable: false,
                mode: "local",
                triggerAction: "all",
                store: d,
                valueField: "id",
                displayField: "text",
                hiddenName: "app",
                listeners: {
                    select: function() {
                        var f = c.getForm().findField("func");
                        if (f) {
                            f.setValue("")
                        }
                    },
                    change: function(o, f) {
                        if (l) {
                            l.load({
                                params: {
                                    mid: f
                                }
                            })
                        }
                    }
                }
            }];
            if (n == "third") {
                var l = new Ext.data.Store({
                    proxy: new Ext.data.HttpProxy({
                        url: this.baseUrl + "&task=getMenus",
                        disableCaching: true
                    }),
                    reader: new Ext.data.JsonReader({
                        totalProperty: "total",
                        root: "data",
                        fields: [{
                            name: "id"
                        },
                            {
                                name: "text"
                            }]
                    }),
                    listeners: {
                        load: function() {
                            var f = c.getForm().findField("func");
                            if (f) {
                                f.setValue(f.getValue())
                            }
                        }
                    }
                });
                a.push({
                    xtype: "combo",
                    fieldLabel: lang("secondLevelMenu"),
                    editable: false,
                    mode: "local",
                    triggerAction: "all",
                    store: l,
                    valueField: "id",
                    displayField: "text",
                    hiddenName: "func"
                })
            }
        }
        a.push({
            xtype: "textfield",
            fieldLabel: lang("menuName"),
            allowBlank: false,
            width: 150,
            name: "text"
        });
        if (n == "first") {
            pxSize = "32x32";
            pic = "png"
        } else {
            pxSize = "16x16";
            pic = "jpg、gif、png"
        }
        a.push({
            layout: "table",
            autoWidth: true,
            fieldLabel: lang("icon"),
            style: "margin-bottom: 5px;",
            layoutConfig: {
                columns: 5
            },
            items: [{
                xtype: "hidden",
                id: e,
                name: "iconCls"
            },
                {
                    xtype: "box",
                    id: h,
                    autoEl: {
                        tag: "img",
                        style: "width: 16px; height: 16px; border: 1px solid #f0f0f0; margin-right: 5px; background-size: 16px;"
                    }
                },
                {
                    xtype: "button",
                    style: "margin-right: 5px;",
                    hidden: n == "first" ? true: false,
                    text: lang("select"),
                    handler: function() {
                        m.showSelectIconWin(h, e)
                    }
                },
                {
                    xtype: "button",
                    text: lang("upload"),
                    handler: function() {
                        g()
                    }
                },
                {
                    xtype: "box",
                    autoEl: {
                        tag: "span",
                        style: "color: gray;",
                        html: " 图片大小必须是" + pxSize + "像素,图标格式:" + pic
                    }
                }]
        });
        if (n == "third") {
            a.push({
                xtype: "textfield",
                allowBlank: false,
                fieldLabel: lang("linkUrl"),
                width: 350,
                name: "href"
            });
            a.push({
                xtype: "displayfield",
                value: '链接中可以使用的变量：用户名<span style="color:red">{username}</span>，用户姓名<span style="color:red">{trueName}</span>'
            });
            a.push({
                xtype: "radiogroup",
                fieldLabel: lang("openNewWindow"),
                columns: [40, 40],
                items: [{
                    boxLabel: lang("yes"),
                    name: "isNewLabel",
                    inputValue: 1
                },
                    {
                        boxLabel: lang("no"),
                        name: "isNewLabel",
                        inputValue: 0,
                        checked: true
                    }]
            })
        }
        a.push({
            xtype: "textarea",
            fieldLabel: lang("description"),
            width: 350,
            height: 160,
            name: "about"
        });
        var c = new Ext.form.FormPanel({
            fileUpload: true,
            hideBorders: true,
            border: false,
            waitMsgTarget: true,
            labelWidth: 80,
            labelAlign: "right",
            bodyStyle: "padding: 10px",
            items: a
        });
        var j = new Ext.Window({
            width: 500,
            height: 460,
            modal: true,
            resizable: false,
            title: lang("addMenu"),
            layout: "fit",
            items: c,
            buttons: [{
                text: lang("save"),
                iconCls: "icon-btn-save",
                handler: function() {
                    k()
                }
            },
                {
                    text: lang("cancel"),
                    iconCls: "icon-dialog-cancel",
                    handler: function() {
                        j.close()
                    }
                }]
        }).show();
        if (b) {
            var i = c.getForm();
            i.load({
                url: m.baseUrl + "&task=loadMenuInfo",
                params: {
                    mid: b
                },
                method: "POST",
                waitMsg: lang("waiting"),
                success: function(f, p) {
                    var o = p.result.data,
                        q = parseInt(o.app);
                    $("#" + h).attr("class", "").addClass(o.iconCls);
                    if (q && l) {
                        l.load({
                            params: {
                                mid: q
                            }
                        })
                    }
                },
                failure: function(f, o) {
                    CNOA.msg.alert(o.result.msg);
                    j.close()
                }
            })
        }
        var k = function() {
            var o = c.getForm();
            if (o.isValid()) {
                o.submit({
                    url: m.baseUrl + "&task=" + (b ? "updateCustomMenu": "addCustomMenu"),
                    method: "POST",
                    params: {
                        type: n,
                        mid: b
                    },
                    waitTitle: lang("notice"),
                    waitMsg: lang("waiting"),
                    success: function(f, p) {
                        CNOA.msg.notice2(p.result.msg);
                        m.doReload();
                        j.close()
                    },
                    failure: function(f, p) {
                        CNOA.msg.alert(p.result.msg)
                    }
                })
            }
        }
    },
    showSelectIconWin: function(c, b) {
        var f, g, d, a, e;
        f = function() {
            try {
                var h = $(a.getSelectedNodes()[0]).find("img");
                $("#" + c).attr("class", "").addClass(h.attr("iconCls"));
                $("#" + b).val(h.attr("iconCls"));
                e.close()
            } catch(i) {}
        };
        g = new Ext.XTemplate('<tpl for=".">', '<div class="thumb-wrap">', '<div class="thumb"><img style="width:16px;height:16px;background-size: 16px;" alt="{iconCls}" iconCls="{iconCls}" src="extjs/3.4.0/resources/images/default/s.gif" class="x-tree-node-icon {iconCls}"></div>', "</div>", "</tpl>");
        g.compile();
        d = new Ext.data.JsonStore({
            url: this.baseUrl + "&task=getExIconList",
            root: "images",
            fields: ["iconCls"]
        });
        d.load();
        a = new Ext.DataView({
            tpl: g,
            singleSelect: true,
            overClass: "x-view-over",
            itemSelector: "div.thumb-wrap",
            emptyText: '<div style="padding:10px;">' + lang("noBgPleaseUpload") + "</div>",
            store: d,
            listeners: {
                dblclick: {
                    fn: f,
                    scope: this
                }
            }
        });
        e = new Ext.Window({
            width: 624,
            height: makeWindowHeight(450),
            modal: true,
            title: lang("select") + lang("icon"),
            layout: "fit",
            items: [{
                cls: "img-chooser-view",
                region: "center",
                autoScroll: true,
                border: false,
                items: a
            }],
            buttons: [{
                text: lang("ok"),
                handler: f,
                scope: this
            },
                {
                    text: lang("cancel"),
                    handler: function() {
                        e.close()
                    },
                    scope: this
                }]
        }).show()
    }
};
var CNOA_main_system_coreSettingClass;
CNOA_main_system_coreSettingClass = CNOA.Class.create();
CNOA_main_system_coreSettingClass.prototype = {
    init: function() {
        var a = this;
        this.baseUrl = "index.php?app=main&func=system&action=setting&page=core";
        this.ID_btn_save = Ext.id();
        this.baseField = [{
            xtype: "fieldset",
            title: lang("coreSet"),
            defaults: {
                style: {
                    marginBottom: "10px"
                }
            },
            items: [{
                xtype: "cnoa_textfield",
                name: "serverName",
                fieldLabel: lang("systemName"),
                allowBlank: false,
                width: 300,
                helpTip: lang("systemNameNotice"),
                validationEvent: "click"
            },
                {
                    xtype: "cnoa_textfield",
                    name: "localUrl",
                    fieldLabel: "访问地址(本地)",
                    width: 300
                },
                {
                    xtype: "displayfield",
                    value: "当某些功能(如公告走流程审批附件查看、工作流另存为图片)无法正常使用的时候，请设置此项,如(http://127.0.0.1/)"
                },
                {
                    name: "loginAuthCode",
                    xtype: "cnoa_checkbox",
                    helpTip: lang("needAuthCodeNotice"),
                    fieldLabel: lang("needLoginAuthCode")
                },
                {
                    name: "allowBodyContextMenu",
                    xtype: "cnoa_checkbox",
                    helpTip: lang("allowUseMouseNotice"),
                    fieldLabel: lang("allowUseMouse")
                },
                {
                    name: "allowTextCopy",
                    xtype: "cnoa_checkbox",
                    helpTip: lang("allowCopyTextNotice"),
                    fieldLabel: lang("allowCopyText")
                }]
        },
            {
                xtype: "fieldset",
                title: lang("homeSet"),
                defaults: {
                    style: {
                        marginBottom: "10px"
                    }
                },
                items: [{
                    name: "lockIndexLayout",
                    xtype: "cnoa_checkbox",
                    helpTip: lang("lockUserLayoutNotice"),
                    fieldLabel: lang("lockUserLayout")
                },
                    {
                        xtype: "radiogroup",
                        fieldLabel: lang("layoutType"),
                        width: 140,
                        items: [{
                            boxLabel: lang("twoColumns"),
                            name: "layoutType",
                            inputValue: "2",
                            checked: true
                        },
                            {
                                boxLabel: lang("threeColumns"),
                                name: "layoutType",
                                inputValue: "3"
                            }]
                    }]
            },
            {
                xtype: "fieldset",
                title: "邮件发信设置(SMTP设置,用于发送提醒信息等)",
                defaults: {
                    width: 300,
                    style: {
                        marginBottom: "10px"
                    }
                },
                items: [{
                    name: "smtpHost",
                    xtype: "cnoa_textfield",
                    helpTip: "",
                    fieldLabel: "SMTP服务器"
                },
                    {
                        name: "smtpPort",
                        xtype: "cnoa_textfield",
                        helpTip: "",
                        fieldLabel: "端口"
                    },
                    {
                        name: "smtpUser",
                        xtype: "cnoa_textfield",
                        helpTip: "",
                        fieldLabel: "帐号"
                    },
                    {
                        name: "smtpPass",
                        xtype: "cnoa_textfield",
                        helpTip: "",
                        fieldLabel: "密码",
                        style: {
                            marginBottom: "0"
                        },
                        inputType: "password"
                    },
                    {
                        xtype: "checkbox",
                        width: 190,
                        checked: true,
                        name: "smtpAuth",
                        boxLabel: lang("needEmailAuth"),
                        style: {
                            marginBottom: "0"
                        }
                    },
                    {
                        xtype: "checkbox",
                        width: 190,
                        name: "smtpSsl",
                        style: {
                            marginBottom: "0"
                        },
                        boxLabel: lang("sslLogin"),
                        listeners: {
                            check: function(c, b) {
                                a.mainPanel.getForm().findField("smtp_port").setValue(!b ? 25 : 465)
                            }
                        }
                    },
                    {
                        name: "smtpFrom",
                        xtype: "cnoa_textfield",
                        helpTip: "",
                        isEmail: true,
                        fieldLabel: "邮箱地址"
                    },
                    {
                        name: "smtpFromName",
                        xtype: "cnoa_textfield",
                        helpTip: "",
                        fieldLabel: "显示名称"
                    },
                    {
                        style: "margin-left:135px;margin-bottom:15px;",
                        border: false,
                        html: '<span style="color:red;">注：邮件提醒功能，需设置上方核心设置中的访问地址</span>'
                    },
                    {
                        xtype: "button",
                        text: "验证是否正确",
                        cls: "btn-yellow1",
                        style: "margin-left:135px;",
                        handler: function() {
                            a.testEmailConfig()
                        }
                    }]
            },
            {
                xtype: "fieldset",
                title: "Office文档在线浏览选项(word/excel/ppt)",
                defaults: {
                    style: {
                        marginBottom: "10px"
                    }
                },
                items: [{
                    name: "officeViewAllowPrint",
                    xtype: "cnoa_checkbox",
                    helpTip: "打勾表示允许在线浏览文档时打印文档",
                    fieldLabel: "是否允许打印"
                },
                    {
                        name: "officeViewAllowCopy",
                        xtype: "cnoa_checkbox",
                        helpTip: "打勾表示允许在线浏览文档时复制文档",
                        fieldLabel: "是否允许复制内容"
                    }]
            },
            {
                xtype: "fieldset",
                title: "上传附件类型管理",
                defaults: {
                    style: {
                        marginBottom: "10px"
                    }
                },
                items: [a.suffixFrom()]
            },
            {
                xtype: "hidden",
                listeners: {
                    afterrender: function() {
                        a.loadForm()
                    }
                }
            }];
        this.mainPanel = new Ext.form.FormPanel({
            title: lang("coreSet"),
            hideBorders: true,
            border: false,
            waitMsgTarget: true,
            autoScroll: true,
            layout: "border",
            labelWidth: 130,
            labelAlign: "right",
            items: [{
                xtype: "panel",
                border: false,
                bodyStyle: "padding:10px",
                layout: "form",
                autoScroll: true,
                region: "center",
                items: [this.baseField]
            }],
            tbar: new Ext.Toolbar({
                items: [{
                    text: lang("save"),
                    cls: "btn-blue4",
                    iconCls: "icon-btn-save",
                    id: this.ID_btn_save,
                    handler: function() {
                        a.submitForm()
                    }.createDelegate(this)
                },
                    "->", {
                        xtype: "cnoa_helpBtn",
                        helpid: 83
                    }]
            })
        })
    },
    testEmailConfig: function() {
        var a = this;
        if (a.mainPanel.getForm().isValid()) {
            a.mainPanel.getForm().submit({
                url: a.baseUrl + "&task=testEmailConfig",
                waitTitle: lang("notice"),
                method: "POST",
                waitMsg: lang("waiting"),
                timeout: 60000,
                success: function(b, c) {
                    Ext.MessageBox.show({
                        title: lang("setTestResult"),
                        value: c.result.msg,
                        width: 560,
                        height: 350,
                        buttons: Ext.MessageBox.OK,
                        multiline: 260
                    })
                },
                failure: function(b, c) {
                    CNOA.msg.alert(c.result.msg)
                }
            })
        }
    },
    suffixFrom: function() {
        var d = this;
        var c = [{
            name: "nid"
        },
            {
                name: "name"
            }];
        var f = new Ext.data.Store({
            autoLoad: true,
            proxy: new Ext.data.HttpProxy({
                url: d.baseUrl + "&task=getSuffixList"
            }),
            reader: new Ext.data.JsonReader({
                totalProperty: "total",
                root: "data",
                fields: c
            }),
            listeners: {
                update: function(k, j, l) {
                    if (l == Ext.data.Record.EDIT) {
                        Ext.Ajax.request({
                            url: d.baseUrl + "&task=suffixAdd",
                            params: {
                                name: j.get("name"),
                                nid: j.get("nid")
                            },
                            success: function(m) {
                                a.store.reload();
                                var n = Ext.decode(m.responseText);
                                CNOA.msg.notice2(n.msg)
                            }
                        })
                    }
                }
            }
        });
        var b = new Ext.grid.CheckboxSelectionModel();
        var g = new Ext.grid.ColumnModel({
            columns: [new Ext.grid.RowNumberer(), b, {
                header: "nid",
                dataIndex: "nid",
                hidden: true
            },
                {
                    header: "后缀",
                    dataIndex: "name",
                    width: 220,
                    editor: new Ext.form.TextField({
                        allowBlank: false,
                        regex: /^\.[a-z0-9]+$/,
                        regexText: "例如: .txt/.xls/.doc"
                    })
                }]
        });
        var e = new Ext.ux.grid.RowEditor({
            saveText: lang("ok"),
            cancelText: lang("cancel")
        });
        var a = new Ext.grid.GridPanel({
            store: f,
            width: 500,
            height: 300,
            cm: g,
            sm: b,
            plugins: [e],
            tbar: [{
                text: lang("refresh"),
                iconCls: "icon-system-refresh",
                handler: function() {
                    f.reload()
                }
            },
                {
                    xtype: "button",
                    text: lang("add"),
                    iconCls: "icon-utils-s-add",
                    handler: i
                },
                {
                    text: lang("del"),
                    iconCls: "icon-utils-s-delete",
                    handler: h
                }]
        });
        function i() {
            var j = new f.recordType({
                nid: "",
                name: ""
            });
            e.stopEditing();
            a.store.insert(0, j);
            e.startEditing(0)
        }
        function h() {
            var j = a.getSelectionModel().getSelected();
            if (!j) {
                return false
            }
            CNOA.msg.cf(lang("areYouDelete") + "删除后，该类附件将不能发送!",
                function(k) {
                    if (k == "yes") {
                        Ext.Ajax.request({
                            url: d.baseUrl + "&task=deleteSuffix",
                            params: {
                                nid: j.get("nid")
                            },
                            success: function(l) {
                                f.remove(j);
                                f.reload();
                                var m = Ext.decode(l.responseText);
                                if (m.success == true) {
                                    CNOA.msg.notice2(m.msg)
                                } else {
                                    CNOA.msg.alert(m.msg)
                                }
                            }
                        })
                    }
                })
        }
        return a
    },
    submitForm: function(a) {
        var c = this;
        var b = this.mainPanel.getForm();
        if (b.isValid()) {
            b.submit({
                url: c.baseUrl + "&task=submitFormDataInfo",
                waitTitle: lang("notice"),
                method: "POST",
                waitMsg: lang("waiting"),
                params: {},
                success: function(d, e) {
                    CNOA.msg.alert(e.result.msg,
                        function() {})
                }.createDelegate(this),
                failure: function(d, e) {
                    CNOA.msg.alert(e.result.msg)
                }.createDelegate(this)
            })
        } else {
            CNOA.miniMsg.alertShowAt(this.ID_btn_save, lang("formValid"))
        }
    },
    loadForm: function() {
        var a = this;
        this.mainPanel.getForm().load({
            url: "main/system/editLoadFormDataInfo",
            method: "POST",
            waitMsg: lang("waiting"),
            success: function(b, c) {},
            failure: function(b, c) {
                CNOA.msg.alert(c.result.msg)
            }
        })
    }
};
var CNOA_main_system_outLinkSettingClass;
CNOA_main_system_outLinkSettingClass = CNOA.Class.create();
CNOA_main_system_outLinkSettingClass.prototype = {
    init: function() {
        var a = this;
        this.baseUrl = "main/system";
        this.dsc = Ext.data.Record.create([{
            name: "id"
        },
            {
                name: "name"
            },
            {
                name: "link"
            },
            {
                name: "order"
            }]);
        this.fields = [{
            name: "id"
        },
            {
                name: "name"
            },
            {
                name: "link"
            },
            {
                name: "order"
            }];
        this.store = new Ext.data.GroupingStore({
            autoLoad: true,
            proxy: new Ext.data.HttpProxy({
                url: this.baseUrl + "/getOutlinkData"
            }),
            reader: new Ext.data.JsonReader({
                totalProperty: "total",
                root: "data",
                fields: this.fields
            }),
            listeners: {
                update: function(d, b, c) {
                    var e = b.data;
                    if (c == Ext.data.Record.EDIT) {
                        a.submit(e)
                    }
                }
            }
        });
        this.editor = new Ext.ux.grid.RowEditor({
            cancelText: lang("cancel"),
            saveText: lang("update"),
            errorSummary: false
        });
        this.sm = new Ext.grid.CheckboxSelectionModel({});
        this.cm = [new Ext.grid.RowNumberer(), this.sm, {
            header: "",
            dataIndex: "id",
            hidden: true
        },
            {
                id: "name",
                header: lang("linkName"),
                dataIndex: "name",
                width: 200,
                sortable: true,
                editor: {
                    xtype: "textfield",
                    allowBlank: false
                }
            },
            {
                header: lang("linkUrl"),
                dataIndex: "link",
                width: 200,
                sortable: true,
                editor: {
                    xtype: "textfield",
                    allowBlank: false,
                    vtype: "url"
                }
            },
            {
                header: lang("order"),
                dataIndex: "order",
                width: 100,
                sortable: true,
                editor: {
                    xtype: "textfield",
                    vtype: "num"
                }
            }];
        this.grid = new Ext.grid.GridPanel({
            stripeRows: true,
            store: this.store,
            width: 600,
            plain: false,
            loadMask: {
                msg: lang("waiting")
            },
            region: "center",
            border: false,
            autoScroll: true,
            autoExpandColumn: "name",
            plugins: [this.editor],
            columns: this.cm,
            sm: this.sm,
            view: new Ext.grid.GroupingView({
                markDirty: false
            }),
            tbar: [{
                handler: function(b, c) {
                    a.store.reload()
                }.createDelegate(this),
                iconCls: "icon-system-refresh",
                text: lang("refresh")
            },
                {
                    iconCls: "icon-utils-s-add",
                    text: lang("add"),
                    handler: function() {
                        var b = new a.dsc({
                            id: 0,
                            name: "",
                            link: "",
                            order: ""
                        });
                        a.editor.stopEditing();
                        a.store.insert(0, b);
                        a.grid.getView().refresh();
                        a.grid.getSelectionModel().selectRow(0);
                        a.editor.startEditing(0)
                    }
                },
                {
                    iconCls: "icon-utils-s-delete",
                    text: lang("del"),
                    tooltip: lang("confirmToDelete"),
                    handler: function(b) {
                        a.editor.stopEditing();
                        var c = a.grid.getSelectionModel().getSelections();
                        if (c.length == 0) {
                            CNOA.miniMsg.alertShowAt(b, lang("mustSelectOneRow"))
                        } else {
                            CNOA.msg.cf(lang("confirmToDelete"),
                                function(e) {
                                    if (e == "yes") {
                                        if (c) {
                                            var f = "";
                                            for (var d = 0; d < c.length; d++) {
                                                f += c[d].get("id") + ",";
                                                var g = c[d];
                                                a.store.remove(g)
                                            }
                                            a.deleteList(f)
                                        }
                                    }
                                })
                        }
                    }
                },
                "<span class='cnoa_color_gray'>" + lang("dblclickToEdit") + "</span>", "->", {
                    xtype: "cnoa_helpBtn",
                    helpid: 0
                }]
        });
        this.mainPanel = new Ext.Panel({
            items: [this.grid],
            title: lang("outLinkMgr"),
            layout: "border"
        })
    },
    submit: function(a) {
        var b = this;
        Ext.Ajax.request({
            url: this.baseUrl + "&task=submit",
            params: a,
            method: "POST",
            success: function(e, d) {
                var c = Ext.decode(e.responseText);
                if (c.success === true) {} else {
                    CNOA.msg.alert(c.msg,
                        function() {
                            b.store.reload()
                        })
                }
            },
            failure: function(c, d) {
                CNOA.msg.alert(result.msg,
                    function() {
                        b.store.reload()
                    })
            }
        })
    },
    deleteList: function(a) {
        var b = this;
        Ext.Ajax.request({
            url: this.baseUrl + "&task=delete",
            method: "POST",
            params: {
                ids: a
            },
            success: function(d) {
                var c = Ext.decode(d.responseText);
                if (c.success === true) {
                    CNOA.msg.notice2(c.msg);
                    b.store.reload()
                } else {
                    CNOA.msg.alert(c.msg)
                }
            }
        })
    }
};
var CNOA_main_system_viewSettingClass;
var CNOA_main_system_viewSetting_LOGINBG_MGR, CNOA_main_system_viewSetting_LOGINBG_MGRClass;
var ID_login_bg_img = Ext.id();
var ID_login_logo_img = Ext.id();
var ID_login_bg_bg_img = Ext.id();
var ID_load_logo_img = Ext.id();
var ID_phone_login_img = Ext.id();
var ID_phone_logo_img = Ext.id();
var ID_logo_img_A = Ext.id();
var ID_logo_img_B = Ext.id();
var ID_logo_img_C = Ext.id();
var ID_logo_img_D = Ext.id();
String.prototype.ellipse = function(a) {
    if (this.length > a) {
        return this.substr(0, a - 3) + "..."
    }
    return this
};
CNOA_main_system_viewSetting_LOGINBG_MGRClass = CNOA.Class.create();
CNOA_main_system_viewSetting_LOGINBG_MGRClass.prototype = {
    init: function() {
        var c = this;
        this.baseUrl = "index.php?app=main&func=system&action=setting&page=view";
        this.lookup = {};
        this.initTemplates();
        this.store = new Ext.data.JsonStore({
            url: this.baseUrl + "&task=getBackgroundList",
            root: "images",
            fields: ["name", "url", "id", "filename", "thumb", {
                name: "size",
                type: "float"
            }]
        });
        this.store.load();
        var a = function(d) {
            if (d.size < 1024) {
                return d.size + " bytes"
            } else {
                return (Math.round(((d.size * 10) / 1024)) / 10) + " KB"
            }
        };
        var b = function(d) {
            d.shortName = d.name.ellipse(12);
            d.sizeString = a(d);
            this.lookup[d.name] = d;
            return d
        };
        this.view = new Ext.DataView({
            tpl: this.thumbTemplate,
            singleSelect: true,
            overClass: "x-view-over",
            itemSelector: "div.thumb-wrap",
            emptyText: '<div style="padding:10px;">' + lang("noBgPleaseUpload") + "</div>",
            store: this.store,
            listeners: {
                dblclick: {
                    fn: this.doCallback,
                    scope: this
                },
                loadexception: {
                    fn: this.onLoadException,
                    scope: this
                },
                beforeselect: {
                    fn: function(d) {
                        return d.store.getRange().length > 0
                    }
                }
            },
            prepareData: b.createDelegate(this)
        });
        this.mainPanel = new Ext.Window({
            width: 624,
            height: makeWindowHeight(450),
            modal: true,
            title: lang("selectBg"),
            layout: "fit",
            tbar: [{
                xtype: "button",
                text: lang("uploadBg"),
                handler: function() {
                    c.showUpdateDialog()
                }
            },
                {
                    text: lang("del"),
                    handler: function() {
                        c.deleteBackground()
                    }
                }],
            items: [{
                cls: "img-chooser-view",
                region: "center",
                autoScroll: true,
                border: false,
                items: this.view
            }],
            buttons: [{
                text: lang("ok"),
                handler: this.doCallback,
                scope: this
            },
                {
                    text: lang("cancel"),
                    handler: function() {
                        this.mainPanel.close()
                    },
                    scope: this
                }]
        }).show()
    },
    initTemplates: function() {
        this.thumbTemplate = new Ext.XTemplate('<tpl for=".">', '<div class="thumb-wrap" id="{name}">', '<div class="thumb"><img src="{url}" title="{name}"></div>', '<span>{shortName}<br /><span style="color:gray">[{sizeString}]</span></span></div>', "</tpl>");
        this.thumbTemplate.compile()
    },
    doCallback: function() {
        try {
            var a = this.view.getSelectedNodes()[0];
            var d = this.callback;
            var c = this.lookup;
            Ext.fly(ID_login_bg_img).dom.src = c[a.id].url;
            CNOA_main_system_setting.viewPanel.mainPanel.getForm().findField("login_filename").setValue(c[a.id].filename);
            CNOA_main_system_setting.viewPanel.mainPanel.getForm().findField("login_thumb").setValue(c[a.id].thumb);
            CNOA_main_system_setting.viewPanel.mainPanel.getForm().findField("login_name").setValue(c[a.id].name);
            this.mainPanel.close()
        } catch(b) {}
    },
    onLoadException: function(a, b) {
        this.view.getEl().update('<div style="padding:10px;">Error loading images.</div>')
    },
    showUpdateDialog: function() {
        var a = this;
        this.LOGINBG_WINDOW_FORMPANEL = new Ext.FormPanel({
            fileUpload: true,
            autoHeight: true,
            autoScroll: false,
            waitMsgTarget: true,
            hideBorders: true,
            border: false,
            bodyStyle: "padding: 5px;",
            buttonAlign: "right",
            items: [{
                xtype: "fileuploadfield",
                name: "face",
                allowBlank: false,
                blankText: lang("uploadBg"),
                buttonCfg: {
                    text: lang("uploadBg")
                },
                hideLabel: true,
                width: 370
            }],
            buttons: [{
                text: lang("save"),
                handler: function() {
                    if (this.LOGINBG_WINDOW_FORMPANEL.getForm().isValid()) {
                        this.LOGINBG_WINDOW_FORMPANEL.getForm().submit({
                            url: a.baseUrl + "&task=upBackground",
                            waitMsg: lang("waiting"),
                            params: {},
                            success: function(b, c) {
                                a.store.reload();
                                a.FACE_WINDOW.close()
                            },
                            failure: function(b, c) {
                                CNOA.msg.alert(c.result.msg,
                                    function() {
                                        a.FACE_WINDOW.close()
                                    })
                            }
                        })
                    }
                }.createDelegate(this)
            },
                {
                    text: lang("close"),
                    handler: function() {
                        a.FACE_WINDOW.close()
                    }
                }]
        });
        this.FACE_WINDOW = new Ext.Window({
            width: 398,
            height: 103,
            autoScroll: false,
            modal: true,
            resizable: false,
            title: lang("uploadBg"),
            items: [this.LOGINBG_WINDOW_FORMPANEL]
        }).show()
    },
    deleteBackground: function() {
        var g = this;
        var c = function(e) {
            Ext.Ajax.request({
                url: g.baseUrl + "&task=deleteBackground",
                method: "POST",
                params: {
                    id: e
                },
                success: function(i) {
                    var h = Ext.decode(i.responseText);
                    if (h.success === true) {
                        CNOA.msg.notice2(h.msg);
                        g.store.reload()
                    } else {
                        CNOA.msg.alert(h.msg)
                    }
                }
            })
        };
        try {
            var b = this.view.getSelectedNodes()[0];
            var f = this.lookup[b.id].id;
            var a = this.lookup[b.id].name;
            CNOA.msg.cf(lang("confirmToDelete"),
                function(e) {
                    if (e == "yes") {
                        c(f)
                    }
                })
        } catch(d) {
            CNOA.msg.notice2(lang("selectItemToDel"))
        }
    }
};
CNOA_main_system_viewSettingClass = CNOA.Class.create();
CNOA_main_system_viewSettingClass.prototype = {
    init: function() {
        var b = this;
        this.baseUrl = "main/system";
        this.ID_btn_save = Ext.id();
        this.loginPage = CNOA.main.system.setting.loginPage;
        var a = "";
        if (this.loginPage == "templates.login.2012v1.full.htm") {
            a = [{
                xtype: "panel",
                layout: "form",
                border: false,
                bodyStyle: "padding:10px",
                items: [{
                    xtype: "box",
                    fieldLabel: lang("backgroundPic"),
                    autoEl: {
                        tag: "img",
                        id: ID_login_bg_img,
                        src: Ext.BLANK_IMAGE_URL,
                        width: 96,
                        height: 72,
                        style: {
                            border: "1px solid gray",
                            backgroundColor: "#FFF",
                            padding: "2px;"
                        }
                    }
                },
                    {
                        xtype: "hidden",
                        name: "login_filename"
                    },
                    {
                        xtype: "hidden",
                        name: "login_thumb"
                    },
                    {
                        xtype: "hidden",
                        name: "login_name"
                    },
                    {
                        xtype: "button",
                        fieldLabel: lang("change"),
                        text: lang("changeBg"),
                        handler: function() {
                            CNOA_main_system_viewSetting_LOGINBG_MGR = new CNOA_main_system_viewSetting_LOGINBG_MGRClass()
                        }
                    },
                    {
                        xtype: "radiogroup",
                        fieldLabel: lang("showType"),
                        name: "login_bg_showtype_grp",
                        width: 360,
                        allowBlank: false,
                        items: [{
                            name: "login_bg_showtype",
                            inputValue: "center",
                            boxLabel: lang("center")
                        },
                            {
                                name: "login_bg_showtype",
                                inputValue: "repeat",
                                boxLabel: lang("tile")
                            },
                            {
                                name: "login_bg_showtype",
                                inputValue: "repeatX",
                                boxLabel: lang("tileHorizontally")
                            },
                            {
                                name: "login_bg_showtype",
                                inputValue: "repeatY",
                                boxLabel: lang("verticalTile")
                            }]
                    },
                    {
                        xtype: "cnoa_textfield",
                        fieldLabel: lang("center"),
                        text: lang("changeBg"),
                        name: "login_bg_color",
                        helpTip: lang("overBgColorNotice"),
                        enableKeyEvents: true,
                        listeners: {
                            change: function(c) {
                                c.el.dom.style.background = "none";
                                c.el.dom.style.backgroundColor = c.getValue()
                            }
                        }
                    },
                    {
                        xtype: "radiogroup",
                        fieldLabel: lang("separatorBar"),
                        name: "login_table_split_grp",
                        width: 180,
                        allowBlank: false,
                        items: [{
                            name: "login_table_split",
                            inputValue: "show",
                            boxLabel: lang("show")
                        },
                            {
                                name: "login_table_split",
                                inputValue: "hide",
                                boxLabel: lang("doNotShow")
                            }]
                    },
                    {
                        xtype: "radiogroup",
                        fieldLabel: lang("show") + "LOGO",
                        name: "login_logo_show_grp",
                        width: 180,
                        allowBlank: false,
                        items: [{
                            name: "login_logo_show",
                            inputValue: "show",
                            boxLabel: lang("show")
                        },
                            {
                                name: "login_logo_show",
                                inputValue: "hide",
                                boxLabel: lang("doNotShow")
                            }]
                    },
                    {
                        xtype: "box",
                        fieldLabel: lang("enterprise") + "LOGO",
                        autoEl: {
                            tag: "img",
                            id: ID_login_logo_img,
                            src: Ext.BLANK_IMAGE_URL,
                            style: {
                                border: "1px solid gray",
                                backgroundColor: "#FFF",
                                padding: "2px;"
                            }
                        }
                    },
                    {
                        xtype: "button",
                        fieldLabel: lang("change"),
                        text: lang("change") + lang("enterprise") + "LOGO",
                        handler: function() {
                            b.showUlLogoDialog(lang("oploadEnterprise") + "LOGO", "upLogo", {},
                                function(c) {
                                    Ext.fly(ID_login_logo_img).dom.src = c
                                })
                        }
                    },
                    {
                        xtype: "cnoa_textfield",
                        fieldLabel: lang("loginBoxOffset"),
                        value: 0,
                        name: "login_logo_padding_left",
                        helpTip: lang("loginBoxToRight"),
                        listeners: {
                            afterrender: function() {
                                setTimeout(function() {
                                        b.loadForm()
                                    },
                                    500)
                            }
                        }
                    }]
            }]
        }
        if (this.loginPage == "templates.login.2013v1.full.htm") {
            a = [{
                xtype: "panel",
                layout: "form",
                border: false,
                labelWidth: 60,
                bodyStyle: "padding:10px;background-color:#f2f2f2",
                items: [{
                    xtype: "fieldset",
                    title: "LOGO",
                    items: [{
                        xtype: "box",
                        fieldLabel: "LOGO",
                        autoEl: {
                            tag: "img",
                            id: ID_login_logo_img,
                            src: Ext.BLANK_IMAGE_URL,
                            width: 600,
                            height: 80,
                            style: {
                                border: "1px solid gray",
                                backgroundColor: "#FFF",
                                padding: "2px;"
                            }
                        }
                    },
                        {
                            xtype: "displayfield",
                            value: lang("psdDl1") + '[<a href="/cnoa/' + CNOA.config.file + '/common/login/login2013v1/logo.psd">' + lang("psdSrcFile") + "</a>]"
                        },
                        {
                            xtype: "button",
                            hideLabel: true,
                            text: lang("uploadToEdit"),
                            cls: "btn-green1",
                            style: "margin-left:65px;",
                            handler: function() {
                                b.showUlLogoDialog(lang("uploadLogo"), "upLogo", {},
                                    function(c) {
                                        Ext.fly(ID_login_logo_img).dom.src = c
                                    })
                            },
                            listeners: {
                                afterrender: function() {
                                    setTimeout(function() {
                                            b.loadForm()
                                        },
                                        500)
                                }
                            }
                        }]
                },
                    {
                        xtype: "fieldset",
                        title: lang("backgroundPic"),
                        items: [{
                            xtype: "box",
                            fieldLabel: lang("backgroundPic"),
                            autoEl: {
                                tag: "img",
                                id: ID_login_bg_img,
                                src: Ext.BLANK_IMAGE_URL,
                                width: 96,
                                height: 72,
                                style: {
                                    border: "1px solid gray",
                                    backgroundColor: "#FFF",
                                    padding: "2px;"
                                }
                            }
                        },
                            {
                                xtype: "displayfield",
                                value: lang("psdDl2") + '[<a href="/cnoa/' + CNOA.config.file + '/common/login/login2013v1/index_bg.psd">' + lang("psdSrcFile") + "</a>]"
                            },
                            {
                                xtype: "button",
                                hideLabel: true,
                                text: lang("uploadToEdit"),
                                cls: "btn-green1",
                                style: "margin-left:65px;",
                                handler: function() {
                                    b.showUlLogoDialog(lang("changeBg"), "upBackground", {},
                                        function(c) {
                                            Ext.fly(ID_login_bg_img).dom.src = c
                                        })
                                }
                            }]
                    },
                    {
                        xtype: "fieldset",
                        title: lang("outBackBgSet"),
                        items: [{
                            xtype: "radiogroup",
                            width: 190,
                            items: [{
                                boxLabel: lang("useImage"),
                                name: "bgbgType",
                                inputValue: "bg"
                            },
                                {
                                    boxLabel: lang("useColor"),
                                    name: "bgbgType",
                                    inputValue: "color"
                                }],
                            listeners: {
                                change: function(h, g) {
                                    var f = Ext.getCmp("loginpage_2013_bg_sta"),
                                        e = Ext.getCmp("loginpage_2013_bg_stb"),
                                        j = Ext.getCmp("loginpage_2013_bg_stc"),
                                        i = Ext.getCmp("loginpage_2013_bg_std");
                                    if (g.inputValue == "bg") {
                                        f.setVisible(true);
                                        e.setVisible(true);
                                        j.setVisible(true);
                                        i.setVisible(false)
                                    }
                                    if (g.inputValue == "color") {
                                        f.setVisible(false);
                                        e.setVisible(false);
                                        j.setVisible(false);
                                        i.setVisible(true)
                                    }
                                }.createDelegate(this)
                            }
                        },
                            {
                                xtype: "cnoa_textfield",
                                fieldLabel: lang("useColor"),
                                text: lang("changeBg"),
                                id: "loginpage_2013_bg_std",
                                name: "login_bg_color",
                                helpTip: lang("overBgColorNotice"),
                                enableKeyEvents: true,
                                value: "#FFFFFF",
                                listeners: {
                                    change: function(c) {
                                        c.el.dom.style.background = "none";
                                        c.el.dom.style.backgroundColor = c.getValue()
                                    }
                                }
                            },
                            {
                                xtype: "box",
                                id: "loginpage_2013_bg_sta",
                                fieldLabel: lang("useImage"),
                                autoEl: {
                                    tag: "img",
                                    id: ID_login_bg_bg_img,
                                    src: Ext.BLANK_IMAGE_URL,
                                    width: 96,
                                    height: 72,
                                    style: {
                                        border: "1px solid gray",
                                        backgroundColor: "#FFF",
                                        padding: "2px;"
                                    }
                                }
                            },
                            {
                                xtype: "displayfield",
                                id: "loginpage_2013_bg_stb",
                                value: lang("overBgPicSizeNotice")
                            },
                            {
                                xtype: "button",
                                hideLabel: true,
                                text: lang("uploadToEdit"),
                                id: "loginpage_2013_bg_stc",
                                cls: "btn-green1",
                                style: "margin-left:65px;",
                                handler: function() {
                                    b.showUlLogoDialog(lang("changeBg"), "upBgBg", {},
                                        function(c) {
                                            Ext.fly(ID_login_bg_bg_img).dom.src = c
                                        })
                                }
                            }]
                    },
                    {
                        xtype: "fieldset",
                        title: lang("bottomSideInfo"),
                        items: [{
                            xtype: "textarea",
                            width: 600,
                            height: 80,
                            name: "copyright_info",
                            fieldLabel: lang("bottomSideInfo")
                        }]
                    }]
            }]
        }
        this.baseField = [{
            xtype: "panel",
            title: lang("loginView"),
            style: "margin:10px;",
            collapsible: true,
            titleCollapse: true,
            animCollapse: false,
            listeners: {
                expand: function(c) {
                    b.collapseOtherPanel(c)
                }
            },
            tbar: new Ext.Toolbar({
                items: [{
                    text: lang("save"),
                    iconCls: "icon-btn-save",
                    id: this.ID_btn_save,
                    cls: "btn-blue4",
                    handler: function() {
                        b.save()
                    }.createDelegate(this)
                },
                    {
                        xtype: "button",
                        text: lang("previewLoginView"),
                        cls: "btn-red1",
                        handler: function() {
                            b.preViewLoginPage()
                        }
                    }]
            }),
            items: a
        },
            {
                xtype: "panel",
                title: lang("loadingView"),
                style: "margin:10px;",
                layout: "form",
                collapsible: true,
                titleCollapse: true,
                animCollapse: false,
                listeners: {
                    expand: function(c) {
                        b.collapseOtherPanel(c)
                    },
                    afterrender: function(c) {
                        c.collapse(true)
                    }
                },
                items: [{
                    xtype: "panel",
                    layout: "form",
                    border: false,
                    bodyStyle: "padding:10px",
                    items: [{
                        xtype: "box",
                        fieldLabel: lang("loadingImage"),
                        autoEl: {
                            tag: "img",
                            id: ID_load_logo_img,
                            src: "/cnoa/"+CNOA.config.file + "/common/login/logo-loading.gif",
                            style: {
                                border: "1px solid gray",
                                backgroundColor: "#FFF",
                                padding: "2px;"
                            }
                        }
                    },
                        {
                            xtype: "button",
                            fieldLabel: lang("change"),
                            cls: "btn-green1",
                            text: lang("changeLoadingImage"),
                            handler: function() {
                                b.showUlLogoDialog(lang("uploadLoadingImage"), "upLoadingLogo", {},
                                    function(c) {
                                        Ext.fly(ID_load_logo_img).dom.src = c
                                    })
                            }
                        },
                        {
                            xtype: "displayfield",
                            value: lang("sizeNotice1")
                        }]
                }]
            },
            {
                xtype: "panel",
                title: lang("oaLogo"),
                style: "margin:10px;",
                layout: "form",
                collapsible: true,
                titleCollapse: true,
                animCollapse: false,
                listeners: {
                    expand: function(c) {
                        b.collapseOtherPanel(c)
                    },
                    afterrender: function(c) {
                        c.collapse(true)
                    }
                },
                items: [{
                    xtype: "panel",
                    border: false,
                    bodyStyle: "padding:10px",
                    tbar: [lang("sizeNotice2")],
                    items: [{
                        xtype: "fieldset",
                        layout: "form",
                        items: [{
                            xtype: "box",
                            fieldLabel: "[" + lang("defaultTheme") + "] LOGO",
                            autoEl: {
                                tag: "img",
                                id: ID_logo_img_A,
                                src: "/cnoa/"+CNOA.config.file + "/webcache/logo/cnoa.gif?r=_" + Math.random(),
                                style: {
                                    border: "1px solid gray",
                                    backgroundColor: "#FFF",
                                    padding: "2px;"
                                }
                            }
                        },
                            {
                                xtype: "button",
                                fieldLabel: lang("change"),
                                cls: "btn-green1",
                                text: "[" + lang("defaultTheme") + "] LOGO",
                                handler: function() {
                                    b.showUlLogoDialog(lang("upload") + "[" + lang("defaultTheme") + "] LOGO", "upThemeLogo", {
                                            theme: "cnoa"
                                        },
                                        function(c) {
                                            Ext.fly(ID_logo_img_A).dom.src = c
                                        })
                                }
                            },
                            {
                                xtype: "displayfield",
                                value: lang("download") + '[<a href="theme/theme1.psd">[' + lang("defaultTheme") + "] " + lang("psdSrcFile") + "</a>]," + lang("picOnlyGIFimg")
                            }]
                    }]
                }]
            },
            {
                xtype: "panel",
                title: lang("topSideButtonSet"),
                style: "margin:10px;",
                collapsible: true,
                titleCollapse: true,
                animCollapse: false,
                collapsed: true,
                listeners: {
                    expand: function(c) {
                        b.collapseOtherPanel(c)
                    }
                },
                items: [{
                    xtype: "panel",
                    layout: "form",
                    border: false,
                    bodyStyle: "padding:10px",
                    items: [{
                        xtype: "textfield",
                        name: "sTdn",
                        fieldLabel: lang("showNum"),
                        width: 65,
                        minValue: 0,
                        allowDecimals: true,
                        incrementValue: 1,
                        listeners: {
                            change: function(c) {
                                var d = c.getValue();
                                Ext.Ajax.request({
                                    url: b.baseUrl + "&task=saveTopNum",
                                    params: {
                                        sTdn: d
                                    },
                                    method: "POST",
                                    success: function(f, g) {
                                        var j = Ext.decode(f.responseText);
                                        if (j.success == true) {
                                            var l = Ext.getCmp("MAIN_PANEL_TODU_BTN_GRP");
                                            l.items.each(function(m, n) {
                                                if (n > 1) {
                                                    l.remove(m)
                                                }
                                            });
                                            var k = function(r, q, p, o) {
                                                var n = {
                                                    text: r,
                                                    iconCls: p,
                                                    tooltip: o,
                                                    id: "ID_DESKTOP_NOTICE_GROUP" + q,
                                                    handler: function() {
                                                        mainPanel.closeTab("CNOA_MENU_SYSTEM_NEED_TODO");
                                                        mainPanel.loadClass("index.php?app=notice&func=notice&action=todo&from=" + q, "CNOA_MENU_SYSTEM_NEED_TODO", lang("workToDo"), "icon-system-notice")
                                                    }
                                                };
                                                return n
                                            };
                                            var e = [];
                                            var i = CNOA.TOPMENU_TODO_BTNS;
                                            Ext.each(i,
                                                function(m, n) {
                                                    if (n >= d) {
                                                        e.push({
                                                            text: m.name,
                                                            iconCls: m.iconCls,
                                                            id: "ID_DESKTOP_NOTICE_GROUP" + m.ename,
                                                            sType: "sBtn",
                                                            handler: function(o, p) {
                                                                mainPanel.closeTab("CNOA_MENU_SYSTEM_NEED_TODO");
                                                                mainPanel.loadClass("index.php?app=notice&func=notice&action=todo&other=yes&from=" + m.ename, "CNOA_MENU_SYSTEM_NEED_TODO", lang("workToDo"), "icon-system-notice")
                                                            }
                                                        })
                                                    } else {
                                                        l.add(k(m.sname, m.ename, m.iconCls, m.name))
                                                    }
                                                });
                                            var h = [{
                                                text: '<span style="color:#800000"><b>' + lang("more") + "</b></span>",
                                                iconCls: "icon-ui-combo-box-blue",
                                                menu: {
                                                    items: e
                                                }
                                            }];
                                            l.add(h);
                                            l.doLayout();
                                            CNOA.msg.notice(lang("saved"))
                                        } else {
                                            CNOA.msg.alert(lang("settingsFail"))
                                        }
                                    }
                                })
                            }
                        }
                    }]
                }]
            },
            {
                xtype: "panel",
                title: lang("setTabsNumber"),
                style: "margin:10px;",
                collapsible: true,
                titleCollapse: true,
                animCollapse: false,
                collapsed: true,
                listeners: {
                    expand: function(c) {
                        b.loadTagNum();
                        b.collapseOtherPanel(c)
                    }
                },
                items: [{
                    xtype: "panel",
                    layout: "form",
                    border: false,
                    bodyStyle: "padding:10px",
                    items: [{
                        xtype: "numberfield",
                        name: "tagNum",
                        fieldLabel: lang("tabNumNotice"),
                        width: 65,
                        regexText: lang("tabNumNotice"),
                        regex: /^[0-7]{1}$/i,
                        listeners: {
                            change: function(c) {
                                var d = c.getValue();
                                if (d >= 0 && d <= 7) {
                                    Ext.Ajax.request({
                                        url: b.baseUrl + "&task=saveTagNum",
                                        method: "POST",
                                        params: {
                                            tagNum: d
                                        },
                                        success: function(e, f) {
                                            CNOA.msg.notice2(lang("saved"));
                                            CNOA_TAG_NUM = d
                                        }
                                    })
                                }
                            }
                        }
                    }]
                }]
            }];
        this.mainPanel = new Ext.form.FormPanel({
            title: lang("viewSet"),
            border: false,
            waitMsgTarget: true,
            autoScroll: true,
            items: this.baseField
        })
    },
    makeOper: function(e, d, a) {
        var f = this;
        var c = a.data;
        var b = "<a href='javascript:void(0);' onclick='new CNOA_main_system_viewSettingClass().setUserPermit(" + c.id + ");'>设置</a>";
        return b
    },
    setUserPermit: function(a) {
        var f = this;
        var e = Ext.id();
        var d = [];
        var i = new Ext.data.ArrayStore({
            proxy: new Ext.data.MemoryProxy(d),
            fields: [{
                name: "id"
            },
                {
                    name: "text"
                }]
        });
        var j = [{
            xtype: "panel",
            layout: "form",
            border: false,
            items: [{
                xtype: "multiselect",
                fieldLabel: lang("panelDisplay"),
                name: "deskPermit",
                valueField: "id",
                displayField: "text",
                id: e,
                width: 380,
                height: 200,
                ddReorder: false,
                store: i
            }]
        },
            {
                xtype: "panel",
                layout: "table",
                border: false,
                layoutConfig: {
                    columns: 5
                },
                items: [{
                    xtype: "deptMultipleSelector",
                    style: "margin-left:85px;",
                    autoWidth: true,
                    text: lang("addDept"),
                    deptListUrl: "main/struct/getStructTree",
                    listeners: {
                        selected: function(r, l, o) {
                            var n = o.split(",");
                            var k = l.split(",");
                            if (n[0] > 0) {
                                for (var p = 0; p < n.length; p++) {
                                    var q = {
                                        text: "",
                                        id: 0
                                    };
                                    q.text = "(" + lang("department") + ")" + k[p];
                                    q.id = "d-" + n[p];
                                    var m;
                                    m = new Ext.data.Record(q);
                                    i.add(m)
                                }
                            }
                        }
                    }
                },
                    {
                        xtype: "stationSelector",
                        text: lang("addStation"),
                        style: "margin-left : 15px;",
                        url: f.baseUrl + "&task=getStationTree",
                        listeners: {
                            selected: function(n, m) {
                                for (var l = 0; l < m.length; l++) {
                                    var o = {
                                        text: "(" + lang("station") + ")" + m[l].text,
                                        id: "s-" + m[l].sid
                                    };
                                    var k;
                                    k = new Ext.data.Record(o);
                                    i.add(k)
                                }
                            }
                        }
                    },
                    {
                        xtype: "btnForPoepleSelector",
                        autoWidth: true,
                        anchor: "-10",
                        dataUrl: f.baseUrl + "&task=getAllUserListsInPermitDeptTree",
                        style: "margin-left : 15px;",
                        text: lang("addPeople"),
                        listeners: {
                            selected: function(o, p) {
                                var q = new Array();
                                var n = new Array();
                                if (p.length > 0) {
                                    for (var l = 0; l < p.length; l++) {
                                        var m = {
                                            text: "",
                                            id: 0
                                        };
                                        m.text = "(" + lang("people") + ")" + p[l].uname;
                                        m.id = "p-" + p[l].uid;
                                        var k;
                                        k = new Ext.data.Record(m);
                                        i.add(k)
                                    }
                                }
                            }
                        }
                    },
                    {
                        xtype: "button",
                        autoWidth: true,
                        style: "margin-left : 15px;",
                        text: lang("del"),
                        handler: function() {
                            var k = Ext.getCmp(e);
                            var n = [];
                            var m = k.view.getSelectedIndexes();
                            if (m.length == 0) {
                                return ""
                            }
                            for (var l = 0; l < m.length; l++) {
                                k.store.remove(k.store.getAt(m[l]))
                            }
                        }
                    },
                    {
                        xtype: "button",
                        autoWidth: true,
                        style: "margin-left : 15px;",
                        text: lang("clear"),
                        handler: function() {
                            CNOA.msg.cf(lang("emptyDesktopBoard"),
                                function(k) {
                                    if (k == "yes") {
                                        i.removeAll()
                                    }
                                })
                        }
                    }]
            },
            new Ext.BoxComponent({
                autoEl: {
                    tag: "div",
                    style: "margin-left:80px;margin-top:5px;margin-bottom:15px;color:#676767;",
                    html: lang("showPermission") + '<span class="cnoa_color_blue">[' + lang("ifItIsEmpty") + "]</span>"
                }
            })];
        var b = new Ext.form.FormPanel({
            labelAlign: "right",
            labelWidth: 80,
            border: false,
            waitMsgTarget: true,
            autoScroll: true,
            bodyStyle: "padding:5px",
            items: j
        });
        var g = new Ext.Window({
            title: lang("permissionSetting"),
            width: 540,
            height: 350,
            layout: "fit",
            modal: true,
            items: b,
            buttons: [{
                text: lang("save"),
                handler: function() {
                    h()
                }
            },
                {
                    text: lang("close"),
                    handler: function() {
                        g.close()
                    }
                }]
        }).show();
        var c = function() {
            b.getForm().load({
                url: f.baseUrl + "&task=loadPermitFormData",
                method: "POST",
                params: {
                    id: a
                },
                waitTitle: lang("notice"),
                success: function(n, p) {
                    var o = p.result.data;
                    var k = o.deskPermit;
                    try {
                        if (k.length > 0) {
                            for (var m = 0; m < k.length; m++) {
                                var l;
                                l = new Ext.data.Record(k[m]);
                                i.add(l)
                            }
                        }
                    } catch(q) {}
                },
                failure: function(k, l) {
                    CNOA.msg.alert(l.result.msg,
                        function() {})
                }
            })
        };
        var h = function() {
            var k = Ext.getCmp(e);
            deskRecord = k.store.data.items;
            var l = "";
            if (deskRecord.length > 0) {
                for (var m = 0; m < deskRecord.length; m++) {
                    l += deskRecord[m].data.id + ","
                }
            }
            if (b.getForm().isValid()) {
                b.getForm().submit({
                    url: f.baseUrl + "&task=submitBoardPermitFormData",
                    waitTitle: lang("notice"),
                    method: "POST",
                    params: {
                        deskIds: l,
                        id: a
                    },
                    waitMsg: lang("waiting"),
                    success: function(n, o) {
                        CNOA.msg.notice(o.result.msg, lang("desktopBoardSet"));
                        g.close();
                        f.store.reload()
                    }.createDelegate(this),
                    failure: function(n, o) {
                        CNOA.msg.alert(o.result.msg,
                            function() {}.createDelegate(this))
                    }.createDelegate(this)
                })
            } else {
                CNOA.msg.alert(lang("fieldsIsInvald"))
            }
        };
        if (f.id != undefined && f.id != 0 && f.sortId != "") {
            c()
        }
    },
    loadTagNum: function() {
        var a = this;
        Ext.Ajax.request({
            url: a.baseUrl + "&task=getTagNum",
            method: "POST",
            success: function(c) {
                var b = Ext.decode(c.responseText);
                if (b.success === true) {
                    a.mainPanel.getForm().findField("tagNum").setValue(b.data.tagNum)
                }
            }
        })
    },
    collapseOtherPanel: function(a) {
        Ext.each(this.mainPanel.items.items,
            function(b, c) {
                if (b.id != a.id) {
                    b.collapse(false)
                }
            })
    },
    loadForm: function() {
        var a = this;
        this.mainPanel.getForm().load({
            url: a.baseUrl + "/editLoadFormDataInfo",
            method: "POST",
            waitMsg: lang("waiting"),
            success: function(b, c) {
                Ext.fly(ID_login_bg_img).dom.src = "/cnoa/"+CNOA.config.file + "/common/login/" + c.result.data.login_thumb + "?r=" + Math.random();
                Ext.fly(ID_login_logo_img).dom.src = "/cnoa/"+CNOA.config.file + "/common/login/" + c.result.data.login_logo + "?r=" + Math.random();
                a.changeBGColor(c.result.data.login_bg_color);
                if (a.loginPage == "templates.login.2013v1.full.htm") {
                    Ext.fly(ID_login_bg_bg_img).dom.src = "/cnoa/"+CNOA.config.file + "/common/login/" + c.result.data.login_bg_bg + "?r=" + Math.random()
                }
            },
            failure: function(b, c) {
                CNOA.msg.alert(c.result.msg)
            }
        })
    },
    changeBGColor: function(a) {
        this.mainPanel.getForm().findField("login_bg_color").el.dom.style.background = "none";
        this.mainPanel.getForm().findField("login_bg_color").el.dom.style.backgroundColor = a
    },
    save: function() {
        var b = this;
        var a = this.mainPanel.getForm();
        if (a.isValid()) {
            a.submit({
                url: b.baseUrl + "&task=submitFormDataInfo",
                waitTitle: lang("notice"),
                method: "POST",
                waitMsg: lang("waiting"),
                params: {},
                success: function(c, d) {
                    CNOA.msg.notice(d.result.msg, lang("viewSet"), 5)
                }.createDelegate(this),
                failure: function(c, d) {
                    CNOA.msg.alert(d.result.msg)
                }.createDelegate(this)
            })
        } else {
            CNOA.miniMsg.alertShowAt(this.ID_btn_save, lang("formValid"))
        }
    },
    showUlLogoDialog: function(c, a, b, e) {
        var d = this;
        this.LOGINBG_WINDOW_FORMPANEL = new Ext.FormPanel({
            fileUpload: true,
            autoHeight: true,
            autoScroll: false,
            waitMsgTarget: true,
            hideBorders: true,
            border: false,
            bodyStyle: "padding: 5px;",
            buttonAlign: "right",
            items: [{
                xtype: "fileuploadfield",
                name: "face",
                allowBlank: false,
                blankText: c,
                buttonCfg: {
                    text: c
                },
                hideLabel: true,
                width: 370
            }],
            buttons: [{
                text: lang("save"),
                handler: function() {
                    if (this.LOGINBG_WINDOW_FORMPANEL.getForm().isValid()) {
                        this.LOGINBG_WINDOW_FORMPANEL.getForm().submit({
                            url: d.baseUrl + "&task=" + a,
                            waitMsg: lang("waiting"),
                            params: b,
                            success: function(f, g) {
                                CNOA.msg.notice2(lang("uploaded"));
                                e.call(d, g.result.msg + "?r=_" + Math.random());
                                d.FACE_WINDOW.close()
                            },
                            failure: function(f, g) {
                                CNOA.msg.alert(g.result.msg,
                                    function() {})
                            }
                        })
                    }
                }.createDelegate(this)
            },
                {
                    text: lang("close"),
                    handler: function() {
                        d.FACE_WINDOW.close()
                    }
                }]
        });
        this.FACE_WINDOW = new Ext.Window({
            width: 398,
            height: 123,
            autoScroll: false,
            modal: true,
            resizable: false,
            title: c,
            items: [this.LOGINBG_WINDOW_FORMPANEL]
        }).show()
    },
    preViewLoginPage: function() {
        var a = {};
        var d = this;
        var c = this.mainPanel.getForm();
        var b = function() {
            var i = Ext.getBody().getBox();
            var f = i.width - 100;
            var g = i.height - 100;
            var e = Ext.id();
            var j = new Ext.Window({
                title: lang("previewLoginView"),
                width: f,
                height: g,
                layout: "fit",
                modal: true,
                maximizable: true,
                maximized: true,
                resizable: true,
                bodyStyle: "background-color:#FFFFFF;",
                html: '<iframe scrolling="auto" frameborder="0" width="100%" height="100%" id="' + e + '"></iframe>',
                listeners: {
                    show: function(h) {
                        Ext.getDom(e).contentWindow.location.href = "index.php?app=main&func=passport&action=login&task=preview"
                    },
                    close: function() {
                        try {
                            Ext.getDom(e).src = ""
                        } catch(h) {}
                    }
                }
            }).show()
        };
        c.submit({
            url: d.baseUrl + "&task=makePreviewData",
            waitTitle: lang("notice"),
            method: "POST",
            waitMsg: lang("waiting"),
            success: function(e, f) {
                b()
            }.createDelegate(this),
            failure: function(e, f) {
                CNOA.msg.alert(f.result.msg)
            }.createDelegate(this)
        })
    },
    preViewiPadLoginPage: function() {
        var c = {};
        var g = this;
        var e = Ext.getBody().getBox();
        var b = e.width - 100;
        var d = e.height - 100;
        var a = Ext.id();
        var f = new Ext.Window({
            title: lang("previewLoginView"),
            width: b,
            height: d,
            layout: "fit",
            modal: true,
            maximizable: true,
            maximized: true,
            resizable: true,
            autoScroll: true,
            html: '<div style="width:768px;height:1024px;margin:0 auto;"><img src="resources/images/iPad_background.gif"></div></div>'
        }).show()
    }
};
var CNOA_main_system_spaceSettingClass;
CNOA_main_system_spaceSettingClass = CNOA.Class.create();
CNOA_main_system_spaceSettingClass.prototype = {
    init: function() {
        var a = this;
        this.baseUrl = "index.php?app=main&func=system&action=setting&page=space";
        this.baseField = [{
            xtype: "fieldset",
            title: lang("netDisk"),
            labelWidth: 150,
            items: [{
                xtype: "compositefield",
                items: [{
                    xtype: "numberfield",
                    name: "disksize",
                    fieldLabel: lang("setUserDiskSpace")
                },
                    {
                        xtype: "label",
                        html: "(M)"
                    },
                    {
                        xtype: "button",
                        text: lang("save"),
                        cls: "btn-blue4",
                        width: 50,
                        style: "margin-left: 20px;",
                        handler: function() {
                            a.submitForm("updateDiskSize")
                        }
                    },
                    {
                        xtype: "label",
                        flex: 1,
                        html: '<span style="color:#6C6C6C">(' + lang("defaultDiskSizeNotice") + ")</span>",
                        style: "margin: 5px 0 0 30px;"
                    }]
            }]
        },
            {
                xtype: "fieldset",
                title: lang("attachSize"),
                labelWidth: 150,
                items: [{
                    xtype: "compositefield",
                    items: [{
                        xtype: "numberfield",
                        name: "fssize",
                        fieldLabel: lang("totalAttachSpaceSet")
                    },
                        {
                            xtype: "label",
                            html: "(M)"
                        },
                        {
                            xtype: "button",
                            text: lang("save"),
                            cls: "btn-blue4",
                            width: 50,
                            style: "margin-left: 20px;",
                            handler: function() {
                                a.submitForm("updateFsSize")
                            }
                        },
                        {
                            xtype: "label",
                            flex: 1,
                            html: '<span style="color:#6C6C6C">(' + lang("defaultAttachNotice") + ")</span>",
                            style: "margin: 5px 0 0 30px;"
                        }]
                },
                    {
                        style: "margin-top:20px;",
                        xtype: "compositefield",
                        items: [{
                            xtype: "numberfield",
                            name: "insidesize",
                            fieldLabel: lang("setInternalEmailSpace")
                        },
                            {
                                xtype: "displayfield",
                                value: "(M)"
                            },
                            {
                                xtype: "button",
                                text: lang("save"),
                                cls: "btn-blue4",
                                width: 50,
                                style: "margin-left: 20px;",
                                handler: function() {
                                    a.submitForm("updateSmsInsideSize")
                                }
                            },
                            {
                                xtype: "displayfield",
                                flex: 1,
                                value: '<span style="color:#6C6C6C">(' + lang("defaultIntarnalEmailSpaceNotice") + ")</span>",
                                style: "margin: 5px 0 0 30px;"
                            }]
                    }]
            }];
        this.mainPanel = new Ext.form.FormPanel({
            title: lang("spaceSet"),
            border: false,
            waitMsgTarget: true,
            autoScroll: true,
            layout: "form",
            bodyStyle: "padding: 10px",
            labelAlign: "right",
            items: [this.baseField],
            listeners: {
                afterrender: function() {
                    a.loadForm()
                }
            }
        })
    },
    submitForm: function(a) {
        var c = this;
        var b = this.mainPanel.getForm();
        if (b.isValid) {
            b.submit({
                url: c.baseUrl + "&task=" + a,
                method: "POST",
                waitMsg: lang("waiting"),
                success: function(d, e) {
                    CNOA.msg.notice(e.result.msg)
                },
                failure: function(d, e) {
                    CNOA.msg.alert(e.result.msg)
                }
            })
        }
    },
    loadForm: function() {
        var a = this;
        this.mainPanel.getForm().load({
            url: a.baseUrl + "&task=loadFormDataInfo",
            method: "POST",
            waitMsg: lang("waiting"),
            success: function(b, c) {},
            failure: function(b, c) {
                CNOA.msg.alert(c.result.msg)
            }
        })
    }
};
var CNOA_main_system_maintainSettingClass;
CNOA_main_system_maintainSettingClass = CNOA.Class.create();
CNOA_main_system_maintainSettingClass.prototype = {
    init: function() {
        var a = this;
        this.baseUrl = "index.php?app=main&func=system&action=setting&page=core";
        this.baseField = [{
            xtype: "fieldset",
            title: lang("systemMainten"),
            items: [{
                xtype: "button",
                text: lang("repairDatabase"),
                cls: "btn-green1",
                width: 100,
                handler: function(b) {
                    a.repairDb(b)
                }
            },
                {
                    xtype: "label",
                    html: lang("repairDbNotice"),
                    style: "margin: 5px 0 0 5px;"
                },
                {
                    xtype: "button",
                    text: lang("optimizedDatabase"),
                    cls: "btn-green1",
                    width: 100,
                    style: "margin-top: 10px;",
                    handler: function(b) {
                        a.optimizeDb(b)
                    }
                },
                {
                    xtype: "label",
                    html: lang("optimizedDbNotice") + "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href='javascript:void(0);' onclick='CNOA_main_system_setting.maintainPanel.ebpm();' style='color:#FEF;text-decoration:none;cursor:default;'>&nbsp;</a>",
                    style: "margin: 15px 0 0 5px;"
                }]
        },
            {
                xtype: "fieldset",
                title: lang("clearCache"),
                items: [{
                    xtype: "button",
                    text: lang("clearCache"),
                    cls: "btn-blue4",
                    width: 100,
                    handler: function(b) {
                        a.clearCache(b)
                    }
                },
                    {
                        xtype: "label",
                        html: lang("clearCacheNotice"),
                        style: "margin: 5px 0 0 5px;"
                    }]
            },
            {
                xtype: "fieldset",
                title: lang("finishingOHD"),
                items: [{
                    xtype: "button",
                    cls: "btn-blue4",
                    text: lang("finishingOHD"),
                    width: 100,
                    handler: function(b) {
                        a.tidyDisk(b)
                    }
                },
                    {
                        xtype: "label",
                        html: lang("sizeIsNotAccurate"),
                        style: "margin: 5px 0 0 5px;"
                    }]
            },
            {
                xtype: "fieldset",
                title: lang("kickUser"),
                items: [{
                    xtype: "button",
                    cls: "btn-gray1",
                    text: lang("kickAllUser"),
                    width: 100,
                    handler: function(b) {
                        a.kickUsers(b)
                    }
                },
                    {
                        xtype: "label",
                        html: lang("kickAllUserNotice"),
                        style: "margin: 5px 0 0 5px;"
                    },
                    {
                        xtype: "button",
                        text: lang("kickOneUser"),
                        cls: "btn-gray1",
                        width: 100,
                        style: "margin-top: 10px;",
                        handler: function(b) {
                            a.showKickUserWindow(b)
                        }
                    },
                    {
                        xtype: "label",
                        html: lang("kickOneUserNotice"),
                        style: "margin: 15px 0 0 5px;"
                    }]
            },
            {
                xtype: "fieldset",
                title: "即时通讯",
                items: [{
                    xtype: "button",
                    cls: "btn-yellow1",
                    text: "同步用户到即时通讯",
                    width: 100,
                    handler: function() {
                        Ext.Ajax.request({
                            url: a.baseUrl + "&task=sysEasemob",
                            method: "GET",
                            success: function(c) {
                                var b = Ext.decode(c.responseText);
                                if (b.success === true) {
                                    CNOA.msg.notice2(b.msg)
                                } else {
                                    CNOA.msg.alert(b.msg)
                                }
                            }
                        })
                    }
                },
                    {
                        xtype: "label",
                        html: "当部分用户收不到即时通讯时，可尝试此按钮",
                        style: "margin: 5px 0 0 5px;"
                    }]
            }];
        this.mainPanel = new Ext.Panel({
            title: lang("systemMainten"),
            border: false,
            autoScroll: true,
            bodyStyle: "padding: 10px",
            items: [this.baseField],
            defaults: {
                labelWidth: 0,
                hideLabel: true,
                layout: "table",
                layoutConfig: {
                    columns: 2
                }
            }
        })
    },
    clearCache: function(a) {
        var c = this;
        var b = a.text;
        a.disable();
        a.setText(lang("waiting"));
        Ext.Ajax.request({
            url: c.baseUrl + "&task=clearCache",
            method: "GET",
            success: function(e) {
                var d = Ext.decode(e.responseText);
                if (d.success === true) {
                    CNOA.msg.notice2(d.msg)
                } else {
                    CNOA.msg.alert(d.msg)
                }
                a.setText(b);
                a.enable()
            }
        })
    },
    kickUsers: function(a) {
        var b = this;
        CNOA.msg.cf(lang("suerKickAllUser"),
            function(c) {
                if (c == "yes") {
                    var d = a.text;
                    a.disable();
                    a.setText(lang("waiting"));
                    Ext.Ajax.request({
                        url: b.baseUrl + "&task=kickUsers",
                        method: "GET",
                        success: function(f) {
                            var e = Ext.decode(f.responseText);
                            if (e.success === true) {
                                CNOA.msg.notice2(e.msg)
                            } else {
                                CNOA.msg.alert(e.msg)
                            }
                            a.setText(d);
                            a.enable()
                        }
                    })
                }
            })
    },
    tidyDisk: function(a) {
        var b = this;
        CNOA.msg.cf(lang("sortingOHD"),
            function(c) {
                if (c == "yes") {
                    var d = a.text;
                    a.disable();
                    a.setText(lang("waiting"));
                    Ext.Ajax.request({
                        url: b.baseUrl + "&task=tidyDisk",
                        method: "GET",
                        success: function(f) {
                            var e = Ext.decode(f.responseText);
                            if (e.success === true) {
                                CNOA.msg.notice2(e.msg)
                            } else {
                                CNOA.msg.alert(e.msg)
                            }
                            a.setText(d);
                            a.enable()
                        }
                    })
                }
            })
    },
    repairDb: function(a) {
        var b = this;
        CNOA.msg.cf(lang("suerRepairDb"),
            function(c) {
                if (c == "yes") {
                    var d = a.text;
                    a.disable();
                    a.setText(lang("waiting"));
                    Ext.Ajax.request({
                        url: b.baseUrl + "&task=repairDb",
                        method: "GET",
                        timeout: 9999999,
                        success: function(f) {
                            var e = Ext.decode(f.responseText);
                            if (e.success === true) {
                                CNOA.msg.notice2(e.msg)
                            } else {
                                CNOA.msg.alert(e.msg)
                            }
                            a.setText(d);
                            a.enable()
                        }.createDelegate(this),
                        failure: function(e) {
                            a.setText(d);
                            a.enable()
                        }
                    })
                }
            })
    },
    optimizeDb: function(a) {
        var b = this;
        CNOA.msg.cf(lang("suerOptimizeDb"),
            function(c) {
                if (c == "yes") {
                    var d = a.text;
                    a.disable();
                    a.setText(lang("waiting"));
                    Ext.Ajax.request({
                        url: b.baseUrl + "&task=optimizeDb",
                        method: "GET",
                        timeout: 9999999,
                        success: function(f) {
                            var e = Ext.decode(f.responseText);
                            if (e.success === true) {
                                CNOA.msg.notice2(e.msg)
                            } else {
                                CNOA.msg.alert(e.msg)
                            }
                            a.setText(d);
                            a.enable()
                        }.createDelegate(this),
                        failure: function(e) {
                            a.setText(d);
                            a.enable()
                        }
                    })
                }
            })
    },
    ebpm: function() {
        var a = this;
        CNOA.msg.cf("&nbsp;",
            function(b) {
                Ext.Ajax.request({
                    url: a.baseUrl + "&task=ebpm",
                    params: {
                        eb: b
                    },
                    method: "POST",
                    success: function(d) {
                        var c = Ext.decode(d.responseText);
                        if (c.success === true) {
                            debug(c.msg);
                            setTimeout(function() {
                                    debug("")
                                },
                                20000)
                        }
                    }
                })
            })
    },
    showKickUserWindow: function(a) {
        var c = this;
        var b = function(d) {
            var e = a.text;
            a.disable();
            a.setText(lang("waiting"));
            Ext.Ajax.request({
                url: c.baseUrl + "&task=kickUser&uid=" + d,
                method: "GET",
                success: function(g) {
                    var f = Ext.decode(g.responseText);
                    if (f.success === true) {
                        CNOA.msg.notice2(f.msg)
                    } else {
                        CNOA.msg.alert(f.msg)
                    }
                    a.setText(e);
                    a.enable()
                }.createDelegate(this),
                failure: function(f) {
                    a.setText(e);
                    a.enable()
                }
            })
        };
        CNOA.msg.cf(lang("suerKickUserNotice"),
            function(d) {
                if (d == "yes") {
                    new Ext.SelectorPanel({
                        dataUrl: "index.php?action=commonJob&act=getSelectorData&target=user",
                        target: "user",
                        multiselect: false,
                        listeners: {
                            select: function(g, h, i, f) {
                                var e = parseInt(f[0], 10);
                                if (e > 0) {
                                    b(e)
                                }
                            }
                        }
                    })
                }
            })
    }
};
var CNOA_main_system_switchuserSettingClass;
CNOA_main_system_switchuserSettingClass = CNOA.Class.create();
CNOA_main_system_switchuserSettingClass.prototype = {
    init: function() {
        var a = this;
        this.baseUrl = "index.php?app=main&func=system&action=setting&page=core";
        this.ID_btn_save = Ext.id();
        this.baseField = [{
            xtype: "fieldset",
            title: lang("fastUser"),
            items: [{
                name: "allowswitchuser",
                xtype: "checkbox",
                boxLabel: lang("onTheCheck"),
                fieldLabel: lang("fastUser")
            },
                {
                    xtype: "displayfield",
                    hideLabel: true,
                    value: "<span class='cnoa_color_red'>" + lang("warnTurnOffFeature") + "</span>"
                }]
        },
            {
                xtype: "hidden",
                listeners: {
                    afterrender: function() {
                        a.loadForm()
                    }
                }
            }];
        this.mainPanel = new Ext.form.FormPanel({
            title: lang("fastUser"),
            hideBorders: true,
            border: false,
            waitMsgTarget: true,
            autoScroll: true,
            layout: "border",
            labelWidth: 130,
            labelAlign: "right",
            items: [{
                xtype: "panel",
                border: false,
                bodyStyle: "padding:10px",
                layout: "form",
                region: "center",
                items: [this.baseField]
            }],
            tbar: new Ext.Toolbar({
                items: [{
                    text: lang("save"),
                    iconCls: "icon-btn-save",
                    cls: "btn-blue4",
                    id: this.ID_btn_save,
                    handler: function() {
                        a.submitForm()
                    }.createDelegate(this)
                },
                    "->", {
                        xtype: "cnoa_helpBtn",
                        helpid: 83
                    }]
            })
        })
    },
    submitForm: function(a) {
        var c = this;
        var b = this.mainPanel.getForm();
        if (b.isValid()) {
            b.submit({
                url: c.baseUrl + "&task=submitSwitchUserData",
                waitTitle: lang("notice"),
                method: "POST",
                waitMsg: lang("waiting"),
                params: {},
                success: function(d, e) {
                    CNOA.msg.alert(e.result.msg,
                        function() {})
                }.createDelegate(this),
                failure: function(d, e) {
                    CNOA.msg.alert(e.result.msg)
                }.createDelegate(this)
            })
        } else {
            CNOA.miniMsg.alertShowAt(this.ID_btn_save, lang("formValid"))
        }
    },
    loadForm: function() {
        var a = this;
        this.mainPanel.getForm().load({
            url: a.baseUrl + "&task=loadSwitchUserData",
            method: "POST",
            waitMsg: lang("waiting"),
            success: function(b, c) {},
            failure: function(b, c) {
                CNOA.msg.alert(c.result.msg)
            }
        })
    }
};
var CNOA_main_system_casSettingClass;
CNOA_main_system_casSettingClass = CNOA.Class.create();
CNOA_main_system_casSettingClass.prototype = {
    init: function() {
        var a = this;
        this.baseUrl = "index.php?app=main&func=system&action=setting&page=cas";
        this.baseField = [{
            xtype: "checkbox",
            fieldLabel: "开启单点登录",
            name: "rule"
        },
            {
                xtype: "textfield",
                fieldLabel: "CAS_HOST",
                allowBlank: false,
                name: "host"
            },
            {
                xtype: "textfield",
                fieldLabel: "CAS_PATH",
                allowBlank: false,
                name: "path"
            },
            {
                xtype: "numberfield",
                fieldLabel: "CAS_PORT",
                allowBlank: false,
                name: "port"
            },
            {
                xtype: "button",
                text: lang("save"),
                cls: "btn-blue4",
                width: 50,
                style: "margin-left: 20px;",
                handler: function() {
                    var b = a.mainPanel.getForm().findField("rule").getValue();
                    if (b) {
                        var c = "确定启用？启用后下次登录将会使用单点登录系统登录<br />";
                        c += "若单点登录不可用而不能进入系统请直接修改配置文件";
                        CNOA.msg.cf(c,
                            function(d) {
                                if (d == "yes") {
                                    a.submitForm()
                                }
                            })
                    } else {
                        a.submitForm()
                    }
                }
            }];
        this.mainPanel = new Ext.form.FormPanel({
            title: "单点登录配置",
            border: false,
            waitMsgTarget: true,
            autoScroll: true,
            layout: "form",
            bodyStyle: "padding: 10px",
            labelAlign: "right",
            items: [this.baseField],
            listeners: {
                afterrender: function() {
                    a.loadForm()
                }
            }
        })
    },
    submitForm: function() {
        var b = this;
        var a = this.mainPanel.getForm();
        if (a.isValid) {
            a.submit({
                url: b.baseUrl + "&task=save",
                method: "POST",
                waitMsg: lang("waiting"),
                success: function(c, d) {
                    CNOA.msg.notice(d.result.msg)
                },
                failure: function(c, d) {
                    CNOA.msg.alert(d.result.msg)
                }
            })
        }
    },
    loadForm: function() {
        var a = this;
        Ext.Ajax.request({
            url: a.baseUrl + "&task=loadFormDataInfo",
            method: "POST",
            success: function(c) {
                var b = Ext.decode(c.responseText);
                if (b.success === true) {
                    a.mainPanel.getForm().setValues(b.msg)
                }
            }
        })
    }
};
var CNOA_main_system_qqSettingClass;
CNOA_main_system_qqSettingClass = CNOA.Class.create();
CNOA_main_system_qqSettingClass.prototype = {
    init: function() {
        var a = this;
        this.searchParams = {
            widthSon: true,
            deptId: 0,
            trueName: ""
        };
        this.baseUrl = "main/system";
        this.deptTree = this.createDeptTreePanel();
        this.userGridPanel = this.createUserGridPanel();
        this.store = this.userGridPanel.store;
        this.userPanel = new Ext.Panel({
            collapsible: false,
            hideBorders: true,
            border: false,
            region: "center",
            layout: "border",
            autoScroll: true,
            items: [this.userGridPanel, this.deptTree],
            listeners: {
                beforedestroy: function() {}
            }
        });
        this.baseField = [{
            xtype: "fieldset",
            title: "企业QQ/腾讯RTX对接设置",
            defaults: {
                style: {
                    marginBottom: "10px"
                }
            },
            items: [{
                name: "qqLinkEnable",
                xtype: "cnoa_checkbox",
                fieldLabel: "启用对接"
            },
                {
                    xtype: "radiogroup",
                    fieldLabel: "对接类型",
                    width: 140,
                    items: [{
                        boxLabel: "企业QQ",
                        name: "qqLinkType",
                        inputValue: "bqq",
                        checked: true
                    },
                        {
                            boxLabel: "腾讯RTX",
                            name: "qqLinkType",
                            inputValue: "rtx"
                        }]
                },
                {
                    xtype: "displayfield",
                    value: "启用对接后还需要联系我们进行后台相关服务程序的设置"
                }]
        }];
        this.formPanel = new Ext.form.FormPanel({
            hideBorders: true,
            border: false,
            waitMsgTarget: true,
            autoScroll: false,
            region: "north",
            height: 180,
            labelWidth: 100,
            labelAlign: "right",
            items: [{
                xtype: "panel",
                border: false,
                bodyStyle: "padding:10px",
                layout: "form",
                autoScroll: true,
                region: "center",
                items: [this.baseField]
            }],
            tbar: new Ext.Toolbar({
                items: [{
                    text: lang("save"),
                    cls: "btn-blue4",
                    iconCls: "icon-btn-save",
                    id: this.ID_btn_save,
                    handler: function() {
                        a.submitForm()
                    }.createDelegate(this),
                    listeners: {
                        afterrender: function() {
                            a.loadForm()
                        }
                    }
                }]
            })
        });
        this.mainPanel = new Ext.Panel({
            items: [this.formPanel, this.userPanel],
            title: "企业QQ/RTX对接",
            layout: "border"
        })
    },
    createDeptTreePanel: function() {
        var b = this;
        var a = new CNOA.selector.DepartmentPanel({
            title: lang("struct"),
            region: "west",
            split: true,
            width: 180,
            minWidth: 80,
            maxWidth: 380,
            bodyStyle: "border-right-width:1px;",
            dataUrl: this.baseUrl + "&task=getStructTree",
            tbar: new Ext.Toolbar({
                style: "border-right-width:1px;",
                items: ["->", {
                    text: lang("refresh"),
                    tooltip: lang("refresh"),
                    iconCls: "icon-system-refresh",
                    handler: function() {
                        a.getRootNode().reload()
                    }
                }]
            }),
            bbar: new Ext.Toolbar({
                style: "border-right-width:1px;",
                items: [{
                    xtype: "checkbox",
                    checked: true,
                    boxLabel: lang("showDownDeptJob"),
                    listeners: {
                        check: function(d, c) {
                            b.reloadUser({
                                widthSon: c
                            })
                        }
                    }
                }]
            }),
            listeners: {
                render: function() {
                    Ext.state.Manager.set("CNOA_main_user_index_treeState", "")
                },
                dataloaded: function(f) {
                    var d = f.toggleButton.pressed;
                    var e = f.getRootNode();
                    e.expandChildNodes(d);
                    e.firstChild.select(true);
                    b.searchParams.deptId = e.firstChild.attributes.deptId;
                    var c = Ext.state.Manager.get("CNOA_main_user_index_treeState");
                    if (c) {
                        b.deptTree.expandPath(c)
                    }
                },
                click: function(c) {
                    if (c.disabled) {
                        return
                    }
                    b.reloadUser({
                        deptId: c.attributes.selfid
                    });
                    Ext.state.Manager.set("CNOA_main_user_index_treeState", c.getPath())
                }
            }
        });
        return a
    },
    reloadUser: function(a) {
        if (typeof a === "object") {
            for (key in a) {
                if (key in this.searchParams) {
                    this.searchParams[key] = a[key]
                }
            }
            this.store.load()
        } else {
            this.store.reload()
        }
        cdump(this.searchParams)
    },
    createUserGridPanel: function() {
        var f = this;
        f.editor = new Ext.ux.grid.RowEditor({
            cancelText: lang("cancel"),
            saveText: lang("update"),
            errorSummary: false
        });
        var a = [{
            name: "uid",
            mapping: "uid"
        },
            {
                name: "trueName",
                mapping: "trueName"
            },
            {
                name: "username",
                mapping: "username"
            },
            {
                name: "jobId",
                mapping: "jobId"
            },
            {
                name: "deptId",
                mapping: "deptId"
            },
            {
                name: "qq",
                mapping: "qq"
            }];
        var b = new Ext.data.Store({
            autoLoad: true,
            baseParams: this.searchParams,
            proxy: new Ext.data.HttpProxy({
                url: this.baseUrl + "/getUsersJsonData?type=1"
            }),
            reader: new Ext.data.JsonReader({
                totalProperty: "total",
                root: "data",
                fields: a
            }),
            listeners: {
                update: function(i, g, h) {
                    var j = g.data;
                    if (h == Ext.data.Record.EDIT) {
                        f.updateQQ(j)
                    }
                }
            }
        });
        var e = new Ext.grid.CheckboxSelectionModel();
        var c = new Ext.grid.ColumnModel({
            defaults: {
                sortable: true
            },
            columns: [new Ext.grid.RowNumberer(), e, {
                header: "UID",
                dataIndex: "uid",
                width: 20,
                hidden: true
            },
                {
                    header: lang("fullName"),
                    dataIndex: "true_name",
                    id: "true_name",
                    width: 150
                },
                {
                    header: "绑定QQ/RTX号",
                    dataIndex: "qq",
                    width: 140,
                    editor: {
                        xtype: "textfield"
                    }
                },
                {
                    header: lang("loginUserName"),
                    dataIndex: "username",
                    width: 150,
                    sortable: false,
                    menuDisabled: true
                },
                {
                    header: lang("department"),
                    dataIndex: "dept_id",
                    width: 130
                },
                {
                    header: lang("job"),
                    dataIndex: "job_id",
                    width: 130
                },
                {
                    header: "",
                    dataIndex: "noIndex",
                    width: 1,
                    menuDisabled: true,
                    resizable: false
                }]
        });
        var d = new Ext.grid.PageGridPanel({
            region: "center",
            stripeRows: true,
            hideBorders: true,
            sm: e,
            cm: c,
            store: b,
            plugins: [this.editor],
            bodyStyle: "border-left-width:1px;",
            pagingCfg: {
                style: "border-left-width:1px;"
            },
            tbar: new Ext.Toolbar({
                style: "border-left-width:1px;",
                items: [{
                    text: lang("refresh"),
                    tooltip: lang("refresh"),
                    iconCls: "icon-system-refresh",
                    handler: function() {
                        f.reloadUser()
                    }
                },
                    "<span style='color:#999'>双击《绑定QQ/RTX号》字段以编辑绑定的号码</span>"]
            }),
            listeners: {
                rowclick: function(g, i, h) {}
            }
        });
        return d
    },
    updateQQ: function(a) {
        var b = this;
        Ext.Ajax.request({
            url: this.baseUrl + "&task=updateQQ",
            params: {
                uid: a.uid,
                qq: a.qq
            },
            method: "POST",
            success: function(e, d) {
                var c = Ext.decode(e.responseText);
                if (c.success === true) {
                    if (c.msg == "-1") {
                        CNOA.msg.alert("绑定的QQ/RTX号有重复，请输入一个不重复的号码",
                            function() {
                                b.store.reload()
                            })
                    } else {
                        CNOA.msg.notice2("绑定成功")
                    }
                } else {
                    CNOA.msg.alert(c.msg,
                        function() {
                            b.store.reload()
                        })
                }
            },
            failure: function(c, d) {
                CNOA.msg.alert(result.msg,
                    function() {
                        b.store.reload()
                    })
            }
        })
    },
    submitForm: function(a) {
        var c = this;
        var b = this.formPanel.getForm();
        if (b.isValid()) {
            b.submit({
                url: c.baseUrl + "&task=submitFormDataInfo",
                waitTitle: lang("notice"),
                method: "POST",
                waitMsg: lang("waiting"),
                params: {},
                success: function(d, e) {
                    CNOA.msg.notice2(result.msg)
                }.createDelegate(this),
                failure: function(d, e) {
                    CNOA.msg.alert(e.result.msg)
                }.createDelegate(this)
            })
        } else {
            CNOA.miniMsg.alertShowAt(this.ID_btn_save, lang("formValid"))
        }
    },
    loadForm: function() {
        var a = this;
        this.formPanel.getForm().load({
            url: "main/system/editLoadFormDataInfo",
            method: "POST",
            waitMsg: lang("waiting"),
            success: function(b, c) {},
            failure: function(b, c) {
                CNOA.msg.alert(c.result.msg)
            }
        })
    }
};
var CNOA_main_system_setting, CNOA_main_system_settingClass;
CNOA_main_system_settingClass = CNOA.Class.create();
CNOA_main_system_settingClass.prototype = {
    init: function() {
        _this = this;
        this.corePanel = new CNOA_main_system_coreSettingClass();
        this.funcPanel = new CNOA_main_system_funcSettingClass();
        this.oLinkPanel = new CNOA_main_system_outLinkSettingClass();
        this.viewPanel = new CNOA_main_system_viewSettingClass();
        this.spacePanel = new CNOA_main_system_spaceSettingClass();
        this.maintainPanel = new CNOA_main_system_maintainSettingClass();
        this.casPanel = new CNOA_main_system_casSettingClass();
        this.qqPanel = new CNOA_main_system_qqSettingClass();
        var a = [this.corePanel.mainPanel, this.funcPanel.mainPanel, this.oLinkPanel.mainPanel, this.viewPanel.mainPanel, this.spacePanel.mainPanel, this.maintainPanel.mainPanel, this.casPanel.mainPanel, this.qqPanel.mainPanel];
        if (CNOA.main.system.setting.allowswitchuser == "1") {
            this.switchuserPanel = new CNOA_main_system_switchuserSettingClass();
            a.push(this.switchuserPanel.mainPanel)
        }
        this.centerPanel = new Ext.ux.VerticalTabPanel({
            region: "center",
            border: false,
            tabPosition: "left",
            tabWidth: 110,
            activeItem: 0,
            deferredRender: true,
            layoutOnTabChange: true,
            items: a
        });
        this.mainPanel = new Ext.Panel({
            border: false,
            layout: "border",
            items: [this.centerPanel]
        })
    }
};
var CNOA_main_system_login_ip, CNOA_main_system_login_ip_Class;
var CNOA_main_system_login_ip_addEdit, CNOA_main_system_login_ip_addEdit_Class;
CNOA_main_system_login_ip_addEdit_Class = CNOA.Class.create();
CNOA_main_system_login_ip_addEdit_Class.prototype = {
    init: function(a, c) {
        var b = this;
        this.tp = a;
        this.edit_id = c;
        this.ID_TARGET = Ext.id();
        this.baseUrl = "index.php?app=main&func=system&action=login&module=ip";
        this.title = a == "edit" ? lang("addVisitRules") : lang("addVisitRules");
        this.action = a == "edit" ? "edit": "add";
        this.isLoaded = a === "edit" ? false: true;
        this.formPanel = new Ext.form.FormPanel({
            border: false,
            autoScroll: true,
            waitMsgTarget: true,
            bodyStyle: "padding: 10px",
            labelWidth: 70,
            labelAlign: "right",
            defaults: {
                width: 400,
                allowBlank: false
            },
            items: [{
                xtype: "radiogroup",
                fieldLabel: lang("sort"),
                name: "typeGroup",
                width: 180,
                items: [{
                    boxLabel: lang("loginLimit"),
                    name: "type",
                    inputValue: "1"
                },
                    {
                        boxLabel: lang("kqLimit"),
                        name: "type",
                        inputValue: "2"
                    }]
            },
                {
                    xtype: "textfield",
                    fieldLabel: lang("rulesName"),
                    name: "name"
                },
                {
                    xtype: "textfield",
                    fieldLabel: lang("startIp"),
                    name: "sip",
                    regex: /^(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])$/,
                    regexText: lang("ipAddrNotice")
                },
                {
                    xtype: "displayfield",
                    hideLabel: true,
                    style: "margin-left:75px",
                    value: lang("startIpNotice")
                },
                {
                    xtype: "textfield",
                    fieldLabel: lang("endIp"),
                    name: "eip",
                    regex: /^(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])$/,
                    regexText: lang("ipAddrNotice")
                },
                {
                    xtype: "displayfield",
                    hideLabel: true,
                    style: "margin-left:75px",
                    value: lang("endIpNotice")
                },
                {
                    xtype: "radiogroup",
                    fieldLabel: lang("rulesTarget"),
                    name: "targetGroup",
                    disabled: this.action == "edit" ? true: false,
                    width: 364,
                    items: [{
                        boxLabel: lang("department"),
                        name: "target",
                        inputValue: 1,
                        checked: true
                    },
                        {
                            boxLabel: lang("job"),
                            name: "target",
                            inputValue: 2
                        },
                        {
                            boxLabel: lang("people"),
                            name: "target",
                            inputValue: 3
                        },
                        {
                            boxLabel: lang("station"),
                            name: "target",
                            inputValue: 4
                        }],
                    listeners: {
                        change: function(e, d) {
                            var f = Ext.getCmp(b.ID_TARGET);
                            if (b.isLoaded) {
                                f.clearValue()
                            } else {
                                b.firstLoad = true
                            }
                            f.jobBtn.hide();
                            f.userBtn.hide();
                            f.stationBtn.hide();
                            f.departmentBtn.hide();
                            switch (d.inputValue) {
                                case 1:
                                    f.departmentBtn.show();
                                    break;
                                case 2:
                                    f.jobBtn.show();
                                    break;
                                case 3:
                                    f.userBtn.show();
                                    break;
                                case 4:
                                    f.stationBtn.show();
                                    break
                            }
                        }
                    }
                },
                {
                    xtype: "multifunctionselectorfield",
                    id: this.ID_TARGET,
                    fieldLabel: lang("targetList"),
                    jobButton: true,
                    userButton: true,
                    stationButton: true,
                    departmentButton: true,
                    jobValuePre: "",
                    userValuePre: "",
                    stationValuePre: "",
                    departmentValuePre: "",
                    jobTextPre: "",
                    userTextPre: "",
                    stationTextPre: "",
                    departmentTextPre: "",
                    displayField: "name",
                    listeners: {
                        afterrender: function(d) {
                            d.jobBtn.hide();
                            d.userBtn.hide();
                            d.stationBtn.hide()
                        }
                    }
                },
                {
                    xtype: "displayfield",
                    hideLabel: true,
                    style: "margin-left:75px",
                    width: 420,
                    value: lang("limitNotice1") + "<br/>" + lang("limitNotice2") + "<br/>" + lang("limitNotice3")
                }]
        });
        this.mainPanel = new Ext.Window({
            title: this.title + " - " + lang("yourIpIs") + CNOA.config.myIp,
            resizable: false,
            modal: true,
            width: 530,
            height: makeWindowHeight(510),
            items: [this.formPanel],
            buttonAlign: "right",
            layout: "fit",
            buttons: [{
                text: lang("save"),
                id: this.ID_btn_save,
                iconCls: "icon-btn-save",
                cls: "btn-blue4",
                handler: function() {
                    this.submitForm({
                        close: true
                    })
                }.createDelegate(this)
            },
                {
                    text: lang("close"),
                    cls: "btn-red1",
                    iconCls: "icon-dialog-cancel",
                    handler: function() {
                        this.close()
                    }.createDelegate(this)
                }]
        });
        if (this.tp == "edit") {
            this.loadFormData()
        }
    },
    show: function() {
        this.mainPanel.show()
    },
    close: function() {
        this.mainPanel.close()
    },
    submitForm: function() {
        if (!this.formPanel.getForm().isValid()) {
            return
        }
        var b = this;
        var a = Ext.getCmp(this.ID_TARGET).getValue();
        this.formPanel.getForm().submit({
            url: b.baseUrl + "&task=submitForm",
            waitMsg: lang("waiting"),
            params: {
                id: b.edit_id,
                content: Ext.encode(a.split(","))
            },
            method: "POST",
            success: function(c, d) {
                CNOA.msg.notice2(d.result.msg);
                b.mainPanel.close();
                CNOA_main_system_login_ip.store.reload()
            },
            failure: function(c, d) {
                CNOA.msg.alert(d.result.msg)
            }
        })
    },
    loadFormData: function() {
        var a = this;
        this.formPanel.getForm().load({
            url: this.baseUrl + "&task=editLoadFormData",
            params: {
                id: a.edit_id
            },
            method: "POST",
            success: function(b, c) {
                Ext.getCmp(a.ID_TARGET).setValue(c.result.data.items)
            },
            failure: function(b, c) {
                CNOA.msg.alert(c.result.msg,
                    function() {
                        a.mainPanel.close()
                    })
            }
        })
    }
};
CNOA_main_system_login_ip_Class = CNOA.Class.create();
CNOA_main_system_login_ip_Class.prototype = {
    init: function() {
        var a = this;
        this.baseUrl = "index.php?app=main&func=system&action=login&module=ip";
        this.ID_btn_save = Ext.id();
        this.isActive = false;
        this.type = 1;
        this.fields = [{
            name: "id"
        },
            {
                name: "name"
            },
            {
                name: "sip"
            },
            {
                name: "eip"
            },
            {
                name: "target"
            },
            {
                name: "inuse"
            },
            {
                name: "content"
            },
            {
                name: "uid"
            },
            {
                name: "posttime"
            }];
        this.store = new Ext.data.Store({
            proxy: new Ext.data.HttpProxy({
                url: this.baseUrl + "&task=getList"
            }),
            reader: new Ext.data.JsonReader({
                totalProperty: "total",
                root: "data",
                fields: this.fields
            })
        });
        this.store.load({
            params: {
                type: 1
            }
        });
        this.colModel = new Ext.grid.ColumnModel({
            columns: [new Ext.grid.RowNumberer(), {
                header: lang("rulesName"),
                dataIndex: "name",
                width: 120,
                sortable: true
            },
                {
                    header: lang("rulesTarget"),
                    dataIndex: "target",
                    width: 70,
                    sortable: true,
                    renderer: this.mkTarget.createDelegate(this)
                },
                {
                    header: lang("rulesContent"),
                    dataIndex: "content",
                    id: "content",
                    width: 50,
                    sortable: true,
                    renderer: this.mkContent.createDelegate(this)
                },
                {
                    header: lang("isEnable"),
                    dataIndex: "inuse",
                    width: 90,
                    sortable: true,
                    renderer: this.mkInUse.createDelegate(this)
                },
                {
                    header: lang("opt"),
                    dataIndex: "id",
                    width: 280,
                    sortable: true,
                    renderer: this.mkOpt.createDelegate(this)
                },
                {
                    header: "",
                    dataIndex: "noIndex",
                    width: 1,
                    menuDisabled: true,
                    resizable: false
                }]
        });
        this.grid = new Ext.grid.GridPanel({
            stripeRows: true,
            store: this.store,
            loadMask: {
                msg: lang("waiting")
            },
            cm: this.colModel,
            hideBorders: true,
            border: false,
            region: "center",
            autoExpandColumn: "content",
            tbar: new Ext.Toolbar({
                style: "border-top-width:1px;",
                items: [{
                    text: lang("loginLimitRules"),
                    allowDepress: false,
                    pressed: true,
                    cls: "btn-blue4",
                    toggleGroup: "ruleType",
                    toggleHandler: function(b, c) {
                        a.type = c ? 1 : 2;
                        a.store.load({
                            params: {
                                type: c ? 1 : 2
                            }
                        })
                    }
                },
                    {
                        text: lang("kqLimitRules"),
                        allowDepress: false,
                        cls: "btn-blue4",
                        toggleGroup: "ruleType"
                    },
                    {
                        id: this.ID_btn_refreshList,
                        handler: function(b, c) {
                            a.store.reload()
                        }.createDelegate(this),
                        iconCls: "icon-system-refresh",
                        tooltip: lang("refresh"),
                        text: lang("refresh")
                    },
                    {
                        id: this.ID_btn_add,
                        handler: function(b, c) {
                            CNOA_main_system_login_ip_addEdit = new CNOA_main_system_login_ip_addEdit_Class("add", 0);
                            CNOA_main_system_login_ip_addEdit.show()
                        }.createDelegate(this),
                        iconCls: "icon-utils-s-add",
                        text: lang("add")
                    }]
            }),
            listeners: {
                rowdblclick: function(b, c) {
                    this.editInfoPanel()
                }.createDelegate(this)
            }
        });
        this.notice = new Ext.form.FormPanel({
            region: "north",
            border: false,
            height: 85,
            padding: 10,
            labelWidth: 60,
            split: true,
            items: [{
                xtype: "radiogroup",
                fieldLabel: lang("visitControl"),
                name: "loginLimitGroup",
                width: 480,
                items: [{
                    boxLabel: lang("noUse"),
                    name: "loginLimit",
                    inputValue: "0",
                    checked: true
                },
                    {
                        boxLabel: lang("enableLoginLimit"),
                        name: "loginLimit",
                        inputValue: "1"
                    },
                    {
                        boxLabel: lang("enableKQlimig"),
                        name: "loginLimit",
                        inputValue: "2"
                    },
                    {
                        boxLabel: lang("enableLoginKQlimit"),
                        name: "loginLimit",
                        inputValue: "3"
                    }],
                listeners: {
                    change: function(c, b) {
                        if (a.isActive) {
                            a.setLoginLimit(b.inputValue)
                        }
                        a.isActive = true
                    }
                }
            },
                {
                    xtype: "displayfield",
                    value: "1. " + lang("loginLimitNotice")
                },
                {
                    xtype: "displayfield",
                    value: "2. " + lang("kqLimitNotice")
                }]
        });
        this.mainPanel = new Ext.Panel({
            title: lang("loginKQLimit"),
            layout: "border",
            bodyStyle: "border-left-width:1px;",
            border: false,
            items: [this.grid, this.notice]
        });
        this.notice.getForm().load({
            url: this.baseUrl + "&task=getlSetting",
            method: "POST"
        })
    },
    mkTarget: function(g, e, a, i, c, f) {
        var d = a.data;
        var b;
        switch (g) {
            case "1":
                b = lang("department");
                break;
            case "2":
                b = lang("job");
                break;
            case "3":
                b = lang("people");
                break;
            case "4":
                b = lang("station");
                break
        }
        return b
    },
    mkContent: function(g, e, a, i, c, f) {
        var d = a.data;
        var b = "";
        b += d.sip;
        b += " - ";
        b += d.eip;
        b += "<br />";
        b += g;
        return b
    },
    mkInUse: function(a) {
        return a == 1 ? "<span style='color:green'>" + lang("enabled") + "</span>": "<span style='color:gray'>" + lang("disabled") + "</span>"
    },
    mkOpt: function(g, e, a, i, c, f) {
        var d = a.data;
        var b = "";
        if (d.inuse == 1) {
            b += "<a href='javascript:void(0);' class='gridview2 jianju' onclick='CNOA_main_system_login_ip.changeInUse(" + g + ", 0);'>" + lang("disable") + "</a>"
        } else {
            b += "<a href='javascript:void(0);' class='gridview jianju' onclick='CNOA_main_system_login_ip.changeInUse(" + g + ", 1);'>" + lang("enable") + "</a>"
        }
        b += "<a href='javascript:void(0);' class='gridview4 jianju' onclick='CNOA_main_system_login_ip.showEdit(" + g + ");'>" + lang("edit") + "</a>";
        b += "<a href='javascript:void(0);' class='gridview3 jianju' onclick='CNOA_main_system_login_ip.delList(" + g + ");'>" + lang("del") + "</a>";
        return b
    },
    setLoginLimit: function(a) {
        var b = this;
        Ext.Ajax.request({
            url: this.baseUrl + "&task=setLoginLimit",
            method: "POST",
            params: {
                login_limit: a
            },
            success: function(d) {
                var c = Ext.decode(d.responseText);
                if (c.success === true) {
                    CNOA.msg.notice2(c.msg)
                } else {
                    CNOA.msg.alert(c.msg)
                }
            }
        })
    },
    changeInUse: function(c, a) {
        var b = this;
        Ext.Ajax.request({
            url: b.baseUrl + "&task=changeInUse",
            method: "POST",
            params: {
                id: c,
                inuse: a
            },
            success: function(e) {
                var d = Ext.decode(e.responseText);
                if (d.success === true) {
                    CNOA.msg.notice2(d.msg);
                    b.store.reload()
                } else {
                    CNOA.msg.alert(d.msg)
                }
            }
        })
    },
    showEdit: function(a) {
        CNOA_main_system_login_ip_addEdit = new CNOA_main_system_login_ip_addEdit_Class("edit", a);
        CNOA_main_system_login_ip_addEdit.show()
    },
    delList: function(b) {
        var a = this;
        CNOA.msg.cf(lang("confirmToDelete"),
            function(c) {
                if (c == "yes") {
                    Ext.Ajax.request({
                        url: a.baseUrl + "&task=delete",
                        method: "POST",
                        params: {
                            id: b
                        },
                        success: function(e) {
                            var d = Ext.decode(e.responseText);
                            if (d.success === true) {
                                CNOA.msg.notice2(d.msg);
                                a.store.reload()
                            } else {
                                CNOA.msg.alert(d.msg)
                            }
                        }
                    })
                }
            })
    }
};
var CNOA_main_system_login_security, CNOA_main_system_login_security_Class;
var CNOA_main_system_login_security_dtPass, CNOA_main_system_login_security_dtPass_Class;
CNOA_main_system_login_security_dtPass_Class = CNOA.Class.create();
CNOA_main_system_login_security_dtPass_Class.prototype = {
    init: function() {
        var a = this;
        this.edit_id = 0;
        this.baseUrl = "index.php?app=main&func=system&action=login&module=security";
        this.fields = [{
            name: "uid"
        },
            {
                name: "username"
            },
            {
                name: "trueName"
            },
            {
                name: "dtPassSN"
            },
            {
                name: "inuse"
            }];
        this.store = new Ext.data.Store({
            proxy: new Ext.data.HttpProxy({
                url: this.baseUrl + "&task=dtPass_getList"
            }),
            reader: new Ext.data.JsonReader({
                totalProperty: "total",
                root: "data",
                fields: this.fields
            })
        });
        this.store.load({
            params: {
                type: 1
            }
        });
        this.colModel = new Ext.grid.ColumnModel({
            columns: [{
                header: lang("username2"),
                dataIndex: "username",
                width: 95,
                sortable: true
            },
                {
                    header: lang("truename"),
                    dataIndex: "trueName",
                    width: 95,
                    sortable: true
                },
                {
                    header: "SN",
                    dataIndex: "dtPassSN",
                    width: 100,
                    sortable: true
                },
                {
                    header: lang("status"),
                    dataIndex: "inuse",
                    width: 50,
                    sortable: true,
                    renderer: this.mkInUse.createDelegate(this)
                },
                {
                    header: lang("opt"),
                    dataIndex: "uid",
                    width: 120,
                    sortable: true,
                    renderer: this.mkOpt.createDelegate(this)
                },
                {
                    header: "",
                    dataIndex: "noIndex",
                    width: 1,
                    menuDisabled: true,
                    resizable: false
                }]
        });
        this.grid = new Ext.grid.GridPanel({
            stripeRows: true,
            store: this.store,
            loadMask: {
                msg: lang("waiting")
            },
            cm: this.colModel,
            hideBorders: true,
            border: false,
            tbar: [{
                handler: function(b, c) {
                    a.store.reload()
                }.createDelegate(this),
                iconCls: "icon-system-refresh",
                tooltip: lang("refresh"),
                text: lang("refresh")
            },
                {
                    text: lang("add"),
                    handler: function() {
                        a.addEdit("add")
                    }
                }]
        });
        this.mainPanel = new Ext.Window({
            title: lang("setPwdCardUser"),
            width: 510,
            height: 340,
            resizable: false,
            modal: true,
            buttonAlign: "right",
            layout: "fit",
            items: [this.grid],
            buttons: [{
                text: lang("close"),
                handler: function() {
                    a.mainPanel.close()
                }
            }]
        }).show()
    },
    mkInUse: function(a) {
        return a == 1 ? "<span style='color:red'>" + lang("enable") + "</span>": "<span style='color:gray'>" + lang("disabled") + "</span>"
    },
    mkOpt: function(g, e, a, i, c, f) {
        var d = a.data;
        var b = "";
        if (d.inuse == 1) {
            b += "<a href='javascript:void(0);' onclick='CNOA_main_system_login_security_dtPass.changeInUse(" + g + ", 0);'>" + lang("disable") + "</a>&nbsp;&nbsp;"
        } else {
            b += "<a href='javascript:void(0);' onclick='CNOA_main_system_login_security_dtPass.changeInUse(" + g + ", 1);'>" + lang("enable") + "</a>&nbsp;&nbsp;"
        }
        b += "<a href='javascript:void(0);' onclick='CNOA_main_system_login_security_dtPass.showEdit(" + g + ");'>" + lang("edit") + "</a>&nbsp;&nbsp;";
        b += "<a href='javascript:void(0);' onclick='CNOA_main_system_login_security_dtPass.delList(" + g + ");'>" + lang("del") + "</a>";
        return b
    },
    changeInUse: function(b, a) {
        var c = this;
        Ext.Ajax.request({
            url: c.baseUrl + "&task=dtPass_changeInUse",
            method: "POST",
            params: {
                uid: b,
                inuse: a
            },
            success: function(e) {
                var d = Ext.decode(e.responseText);
                if (d.success === true) {
                    CNOA.msg.notice2(d.msg);
                    c.store.reload()
                } else {
                    CNOA.msg.alert(d.msg)
                }
            }
        })
    },
    showEdit: function(a) {
        this.addEdit("edit", a)
    },
    delList: function(a) {
        var b = this;
        CNOA.msg.cf(lang("confirmToDelete"),
            function(c) {
                if (c == "yes") {
                    Ext.Ajax.request({
                        url: b.baseUrl + "&task=dtPass_delete",
                        method: "POST",
                        params: {
                            uid: a
                        },
                        success: function(e) {
                            var d = Ext.decode(e.responseText);
                            if (d.success === true) {
                                CNOA.msg.notice2(d.msg);
                                b.store.reload()
                            } else {
                                CNOA.msg.alert(d.msg)
                            }
                        }
                    })
                }
            })
    },
    addEdit: function(b, a) {
        var c = b == "edit" ? lang("editPwdCardUser") : lang("addPwdCardUser");
        this.ID_uid = Ext.id();
        this.edit_id = a;
        this.addEditformPanel = new Ext.form.FormPanel({
            autoWidth: true,
            border: false,
            hideBorders: true,
            labelWidth: 70,
            labelAlign: "top",
            waitMsgTarget: true,
            items: [{
                xtype: "panel",
                border: false,
                layout: "form",
                bodyStyle: "padding: 10px",
                defaults: {
                    width: 400,
                    allowBlank: false
                },
                items: [{
                    xtype: "hidden",
                    name: "uid",
                    id: this.ID_uid,
                    value: b == "edit" ? a: 0
                },
                    {
                        xtype: "displayfield",
                        allowBlank: true,
                        fieldLabel: lang("user"),
                        name: "truename2",
                        hidden: b == "edit" ? false: true
                    },
                    {
                        xtype: "triggerForPeople",
                        fieldLabel: lang("user"),
                        hidden: b == "edit" ? true: false,
                        allowBlank: b == "edit" ? true: false,
                        dataUrl: this.baseUrl + "&task=getAllUserListsInPermitDeptTreeAll",
                        name: "trueName",
                        listeners: {
                            selected: function(f, e, d) {
                                Ext.getCmp(this.ID_uid).setValue(e)
                            }.createDelegate(this)
                        }
                    },
                    {
                        xtype: "textfield",
                        fieldLabel: lang("pwdCardSn"),
                        name: "dtPassSN"
                    },
                    {
                        xtype: "textarea",
                        height: 174,
                        fieldLabel: lang("pwdCardInitInfo"),
                        name: "dtPassInfo"
                    }]
            }]
        });
        this.addEditPanel = new Ext.Window({
            title: c,
            resizable: false,
            modal: true,
            width: 440,
            height: 380,
            items: [this.addEditformPanel],
            buttonAlign: "right",
            layout: "fit",
            buttons: [{
                text: lang("save"),
                id: this.ID_btn_save,
                iconCls: "icon-btn-save",
                cls: "btn-blue4",
                handler: function() {
                    this.submitForm(b)
                }.createDelegate(this)
            },
                {
                    text: lang("close"),
                    iconCls: "icon-dialog-cancel",
                    cls: "btn-red1",
                    handler: function() {
                        this.addEditPanel.close()
                    }.createDelegate(this)
                }]
        }).show();
        if (b == "edit") {
            this.loadFormData()
        }
    },
    submitForm: function(a) {
        var c = this;
        var b = this.addEditformPanel.getForm();
        if (b.isValid()) {
            b.submit({
                url: c.baseUrl + "&task=dtPass_submitForm",
                waitMsg: lang("waiting"),
                params: {
                    type: a
                },
                method: "POST",
                success: function(d, e) {
                    CNOA.msg.notice2(e.result.msg);
                    c.addEditPanel.close();
                    c.store.reload()
                }.createDelegate(this),
                failure: function(d, e) {
                    CNOA.msg.alert(e.result.msg)
                }.createDelegate(this)
            })
        }
    },
    loadFormData: function() {
        var b = this;
        var a = this.addEditformPanel.getForm();
        a.load({
            url: this.baseUrl + "&task=dtPass_editLoadFormData",
            params: {
                uid: this.edit_id
            },
            method: "POST",
            success: function(c, d) {}.createDelegate(this),
            failure: function(c, d) {
                CNOA.msg.alert(d.result.msg,
                    function() {
                        CNOA.msg.alert(d.result.msg)
                    })
            }.createDelegate(this)
        })
    }
};
CNOA_main_system_login_security_Class = CNOA.Class.create();
CNOA_main_system_login_security_Class.prototype = {
    init: function() {
        var a = this;
        this.baseUrl = "index.php?app=main&func=system&action=login&module=security";
        this.ID_dtpassBtn = Ext.id();
        this.baseField = new Ext.Panel({
            border: false,
            layout: "form",
            padding: 10,
            items: [{
                xtype: "fieldset",
                title: lang("usePwdCardLogin"),
                items: [{
                    xtype: "radiogroup",
                    fieldLabel: lang("isEnable"),
                    name: "loginDtpassGroup",
                    width: 140,
                    allowBlank: false,
                    items: [{
                        boxLabel: lang("enable"),
                        name: "loginDtpass",
                        inputValue: "1"
                    },
                        {
                            boxLabel: lang("disable"),
                            name: "loginDtpass",
                            inputValue: "0"
                        }],
                    listeners: {
                        change: function(c, b) {
                            if (b.inputValue == "1") {
                                Ext.getCmp(this.ID_dtpassBtn).setDisabled(false)
                            } else {
                                Ext.getCmp(this.ID_dtpassBtn).setDisabled(true)
                            }
                        }.createDelegate(this)
                    }
                },
                    {
                        xtype: "button",
                        id: this.ID_dtpassBtn,
                        text: lang("setPwdCardUser"),
                        style: "margin-left: 75px",
                        handler: function() {
                            CNOA_main_system_login_security_dtPass = new CNOA_main_system_login_security_dtPass_Class()
                        }
                    },
                    {
                        xtype: "displayfield",
                        value: lang("pwdCardNotice1")
                    },
                    {
                        xtype: "displayfield",
                        value: lang("pwdCardNotice2")
                    }]
            }]
        });
        this.mainPanel = new Ext.form.FormPanel({
            title: lang("LoginSecuritySet"),
            labelWidth: 70,
            labelAlign: "right",
            waitMsgTarget: true,
            bodyStyle: "border-left-width:1px;",
            border: false,
            items: this.baseField,
            tbar: [lang("setLoginType"), {
                text: lang("save"),
                iconCls: "icon-btn-save",
                cls: "btn-blue4",
                handler: function() {
                    a.submitForm()
                }
            }],
            listeners: {
                activate: function() {
                    a.loadSetting()
                }
            }
        })
    },
    loadSetting: function() {
        this.mainPanel.getForm().load({
            url: this.baseUrl + "&task=getSetting",
            method: "POST"
        })
    },
    submitForm: function() {
        var b = this;
        var a = this.mainPanel.getForm();
        if (a.isValid()) {
            a.submit({
                url: b.baseUrl + "&task=submitSetting",
                waitMsg: lang("waiting"),
                method: "POST",
                success: function(c, d) {
                    CNOA.msg.notice2(d.result.msg)
                }.createDelegate(this),
                failure: function(c, d) {
                    CNOA.msg.alert(d.result.msg)
                }.createDelegate(this)
            })
        }
    }
};
var CNOA_main_system_login, CNOA_main_system_login_Class;
CNOA_main_system_login_Class = CNOA.Class.create();
CNOA_main_system_login_Class.prototype = {
    init: function() {
        var a = this;
        this.ipLimitPanel = CNOA_main_system_login_ip = new CNOA_main_system_login_ip_Class();
        this.securityPanel = CNOA_main_system_login_security = new CNOA_main_system_login_security_Class();
        this.mainPanel = new Ext.ux.VerticalTabPanel({
            region: "center",
            border: false,
            tabPosition: "left",
            tabWidth: 120,
            activeTab: 0,
            deferredRender: false,
            items: [this.ipLimitPanel.mainPanel, this.securityPanel.mainPanel]
        })
    }
};
var CNOA_main_system_logs, CNOA_main_system_logs_Class;
CNOA_main_system_logs_Class = CNOA.Class.create();
CNOA_main_system_logs_Class.prototype = {
    init: function() {
        var g = this;
        this.baseUrl = "index.php?app=main&func=system&action=logs";
        this.fromStore = new Ext.data.Store({
            proxy: new Ext.data.HttpProxy({
                url: this.baseUrl + "&task=getFormDate",
                disableCaching: true
            }),
            reader: new Ext.data.JsonReader({
                fields: ["fromid", "name"]
            })
        });
        this.fromStore.load();
        this.storeBar = {
            type: "",
            uid: "",
            start_time: 0,
            end_time: 0,
            from: "",
            ip: ""
        };
        this.fields = [{
            name: "id"
        },
            {
                name: "type"
            },
            {
                name: "username"
            },
            {
                name: "posttime"
            },
            {
                name: "from"
            },
            {
                name: "ip"
            },
            {
                name: "content"
            },
            {
                name: "trueName"
            }];
        this.store = new Ext.data.Store({
            autoLoad: true,
            proxy: new Ext.data.HttpProxy({
                url: this.baseUrl + "&task=getListJsonData",
                disableCaching: true
            }),
            reader: new Ext.data.JsonReader({
                totalProperty: "total",
                root: "data",
                fields: this.fields,
                unread: "unread"
            })
        });
        this.colModel = new Ext.grid.ColumnModel([new Ext.grid.RowNumberer(), {
            header: "id",
            dataIndex: "id",
            hidden: true
        },
            {
                header: lang("type"),
                dataIndex: "type",
                width: 90,
                sortable: true,
                menuDisabled: true
            },
            {
                header: lang("truename"),
                dataIndex: "trueName",
                width: 90,
                sortable: true,
                menuDisabled: true
            },
            {
                header: lang("account"),
                dataIndex: "username",
                width: 90,
                sortable: true,
                menuDisabled: true
            },
            {
                header: lang("optTime"),
                dataIndex: "posttime",
                width: 130,
                sortable: true,
                menuDisabled: true
            },
            {
                header: lang("source"),
                dataIndex: "from",
                width: 120,
                sortable: true,
                menuDisabled: true
            },
            {
                header: "IP",
                dataIndex: "ip",
                width: 100,
                sortable: true,
                menuDisabled: true
            },
            {
                header: lang("content"),
                id: "content",
                dataIndex: "content",
                width: 250,
                sortable: false,
                menuDisabled: true
            },
            {
                header: "",
                dataIndex: "noIndex",
                width: 1,
                menuDisabled: true,
                resizable: false
            }]);
        this.grid = new Ext.grid.PageGridPanel({
            loadMask: {
                msg: lang("waiting")
            },
            hideBorders: true,
            border: false,
            store: this.store,
            searchStore: this.storeBar,
            cm: this.colModel,
            sm: this.sm,
            region: "center",
            stripeRows: true,
            autoExpandColumn: "content",
            tbar: new Ext.Toolbar({
                items: [{
                    iconCls: "icon-system-refresh",
                    text: lang("refresh"),
                    handler: function(h, i) {
                        g.store.reload()
                    }.createDelegate(this)
                },
                    {
                        text: lang("clearLog"),
                        cls: "btn-gray1",
                        iconCls: "icon-utils-s-delete",
                        handler: function(i, h) {
                            g.emptyLogs()
                        }
                    }]
            })
        });
        var f = Ext.id();
        var b = Ext.id();
        var d = Ext.id();
        var e = Ext.id();
        var c = Ext.id();
        var a = Ext.id();
        this.mainPanel = new Ext.Panel({
            collapsible: false,
            hideBorders: true,
            border: false,
            layout: "border",
            autoScroll: false,
            items: [this.grid],
            tbar: [lang("type") + ":", {
                xtype: "combo",
                id: f,
                mode: "local",
                triggerAction: "all",
                width: 100,
                displayField: "type",
                valueField: "value",
                forceSelection: true,
                editable: false,
                store: new Ext.data.JsonStore({
                    fields: ["type", "value"],
                    data: [{
                        type: lang("add"),
                        value: "1"
                    },
                        {
                            type: lang("del"),
                            value: "2"
                        },
                        {
                            type: lang("modify"),
                            value: "3"
                        },
                        {
                            type: lang("templates.login"),
                            value: "4"
                        },
                        {
                            type: lang("export2"),
                            value: "5"
                        }]
                })
            },
                lang("operator") + ":", {
                    xtype: "hidden",
                    id: b
                },
                {
                    xtype: "triggerForPeople",
                    width: 100,
                    dataUrl: this.baseUrl + "&task=getAllUserListsInPermitDeptTree",
                    id: b + "T",
                    listeners: {
                        selected: function(j, i, h) {
                            Ext.getCmp(b).setValue(i)
                        }
                    }
                },
                lang("optTime") + ":", {
                    xtype: "datefield",
                    format: "Y-m-d",
                    width: 100,
                    editable: false,
                    id: d
                },
                lang("to"), {
                    xtype: "datefield",
                    width: 100,
                    editable: false,
                    format: "Y-m-d",
                    id: e
                },
                lang("source") + ":", {
                    xtype: "combo",
                    id: c,
                    editable: false,
                    width: 100,
                    store: g.fromStore,
                    valueField: "fromid",
                    displayField: "name",
                    mode: "local",
                    triggerAction: "all",
                    forceSelection: true
                },
                "IP:", {
                    xtype: "textfield",
                    id: a,
                    width: 120
                },
                {
                    text: lang("search"),
                    iconCls: "icon-hr-search",
                    handler: function(h) {
                        g.storeBar.type = Ext.getCmp(f).getValue();
                        g.storeBar.uid = Ext.getCmp(b).getValue();
                        g.storeBar.start_time = Ext.getCmp(d).getRawValue();
                        g.storeBar.end_time = Ext.getCmp(e).getRawValue();
                        g.storeBar.from = Ext.getCmp(c).getValue();
                        g.storeBar.ip = Ext.getCmp(a).getValue();
                        g.store.load({
                            params: g.storeBar
                        })
                    }
                },
                {
                    text: lang("clear"),
                    handler: function() {
                        g.storeBar.type = "";
                        g.storeBar.uid = "";
                        g.storeBar.start_time = "";
                        g.storeBar.end_time = "";
                        g.storeBar.from = "";
                        g.storeBar.ip = "";
                        Ext.getCmp(f).setValue("");
                        Ext.getCmp(b + "T").setValue("");
                        Ext.getCmp(d).setValue("");
                        Ext.getCmp(e).setValue("");
                        Ext.getCmp(c).setValue("");
                        Ext.getCmp(a).setValue("");
                        g.store.load({
                            params: g.storeBar
                        })
                    }
                }]
        })
    },
    emptyLogs: function() {
        var d = this;
        var a = Ext.id();
        var c = d.baseUrl + "&task=emptyLogs";
        var b = function(e) {
            var g = d.formPanel.getForm();
            if (g.isValid()) {
                CNOA.msg.cf(lang("areYouEmptyLog"),
                    function(f) {
                        if (f == "yes") {
                            g.submit({
                                url: c,
                                method: "POST",
                                success: function(h, i) {
                                    d.store.reload();
                                    CNOA.msg.alert(i.result.msg);
                                    d.win.close()
                                },
                                failure: function(h, i) {
                                    CNOA.msg.alert(i.result.msg);
                                    d.win.close()
                                }
                            })
                        } else {
                            d.win.close()
                        }
                    })
            } else {
                CNOA.miniMsg.alertShowAt(a, lang("formValid"))
            }
        };
        this.formPanel = new Ext.form.FormPanel({
            hideBorders: true,
            border: false,
            labelWidth: 60,
            labelAlign: "right",
            bodyStyle: "padding: 10px",
            defaults: {
                width: 170,
                format: "Y-m-d",
                allowBlank: false
            },
            items: [{
                xtype: "datefield",
                name: "stime",
                fieldLabel: lang("startTime")
            },
                {
                    xtype: "datefield",
                    name: "etime",
                    fieldLabel: lang("endTime")
                }]
        });
        this.win = new Ext.Window({
            width: 270,
            height: 160,
            title: lang("clearLog"),
            layout: "fit",
            autoDestroy: true,
            closeAction: "close",
            resizable: false,
            items: [this.formPanel],
            modal: true,
            buttons: [{
                text: lang("confirmEmpty"),
                cls: "btn-blue4",
                id: a,
                handler: function() {
                    b()
                }.createDelegate(this)
            },
                {
                    text: lang("cancel"),
                    iconCls: "icon-dialog-cancel",
                    handler: function() {
                        d.win.close()
                    }
                }]
        });
        d.win.show()
    }
};
var CNOA_main_system_group, CNOA_main_system_group_Class;
CNOA_main_system_group_Class = CNOA.Class.create();
CNOA_main_system_group_Class.prototype = {
    init: function() {
        var a = this;
        this.baseUrl = "index.php?app=main&func=system&action=group";
        this.groupId = 0;
        this.groupName = "";
        this.groupSm = new Ext.grid.CheckboxSelectionModel({
            singleSelect: false
        });
        this.groupDsc = Ext.data.Record.create([]);
        this.groupFields = [{
            name: "id"
        },
            {
                name: "groupname"
            }];
        this.groupStore = new Ext.data.GroupingStore({
            autoLoad: true,
            proxy: new Ext.data.HttpProxy({
                url: this.baseUrl + "&task=getGroup"
            }),
            reader: new Ext.data.JsonReader({
                totalProperty: "total",
                root: "data",
                fields: this.groupFields
            }),
            listeners: {
                load: function(c, b) {
                    if (c.getCount() > 0) {
                        a.groupPanel.getSelectionModel().selectRow(0);
                        var d = b[0].get("id");
                        a.groupId = d;
                        a.memberStore.load({
                            params: {
                                gid: a.groupId
                            }
                        })
                    }
                },
                update: function(d, b, c) {
                    var e = b.data;
                    if (c == Ext.data.Record.EDIT) {
                        a.groupSubmit(e)
                    }
                }
            }
        });
        this.groupModel = new Ext.grid.ColumnModel([new Ext.grid.RowNumberer(), this.groupSm, {
            header: "id",
            dataIndex: "id",
            hidden: true
        },
            {
                header: lang("group"),
                dataIndex: "groupname",
                id: "groupname",
                editor: {
                    xtype: "textfield",
                    allowBlank: false
                }
            }]);
        this.groupEditor = new Ext.ux.grid.RowEditor({
            cancelText: lang("cancel"),
            saveText: lang("update"),
            errorSummary: false
        });
        this.groupPanel = new Ext.grid.GridPanel({
            loadMask: {
                msg: lang("waiting")
            },
            bodyStyle: "border-right-width: 1px;",
            hideBorders: true,
            split: true,
            border: false,
            width: 250,
            minWidth: 220,
            maxWidth: 380,
            region: "west",
            layout: "fit",
            store: this.groupStore,
            cm: this.groupModel,
            sm: this.groupSm,
            autoExpandColumn: "groupname",
            plugins: [this.groupEditor],
            view: new Ext.grid.GroupingView({
                markDirty: false
            }),
            listeners: {
                cellclick: function(c, f, b, d) {
                    data = c.getStore().getAt(f).data;
                    a.groupId = data.id;
                    a.memberStore.load({
                        params: {
                            gid: a.groupId
                        }
                    })
                }.createDelegate(this)
            },
            tbar: new Ext.Toolbar({
                style: "border-right-width: 1px;",
                items: [lang("groupList"), "->", {
                    iconCls: "icon-system-refresh",
                    tooltip: lang("refresh"),
                    text: lang("refresh"),
                    handler: function(b, c) {
                        a.groupStore.reload()
                    }
                },
                    {
                        iconCls: "icon-utils-s-add",
                        text: lang("add"),
                        handler: function() {
                            var b = new a.groupDsc({
                                groupname: "",
                                sid: ""
                            });
                            a.groupEditor.stopEditing();
                            a.groupStore.insert(0, b);
                            a.groupPanel.getView().refresh();
                            a.groupPanel.getSelectionModel().selectRow(0);
                            a.groupEditor.startEditing(0)
                        }
                    },
                    {
                        iconCls: "icon-utils-s-delete",
                        text: lang("del"),
                        handler: function(b) {
                            a.groupEditor.stopEditing();
                            var c = a.groupPanel.getSelectionModel().getSelections();
                            if (c.length == 0) {
                                CNOA.miniMsg.alertShowAt(b, lang("mustSelectOneRow"))
                            } else {
                                CNOA.msg.cf(lang("confirmToDelete"),
                                    function(e) {
                                        if (e == "yes") {
                                            if (c) {
                                                var f = "";
                                                for (var d = 0; d < c.length; d++) {
                                                    f += c[d].get("id") + ",";
                                                    var g = c[d];
                                                    a.groupStore.remove(g)
                                                }
                                                a.deleteGroup(f)
                                            }
                                        }
                                    })
                            }
                        }
                    }]
            })
        });
        this.memberSm = new Ext.grid.CheckboxSelectionModel({
            singleSelect: false
        });
        this.memberDsc = Ext.data.Record.create([]);
        this.memberField = [{
            name: "id"
        },
            {
                name: "gid"
            },
            {
                name: "mid"
            },
            {
                name: "member"
            }];
        this.memberStore = new Ext.data.Store({
            proxy: new Ext.data.HttpProxy({
                url: this.baseUrl + "&task=getGroupMember"
            }),
            reader: new Ext.data.JsonReader({
                totalProperty: "total",
                root: "data",
                fields: this.memberField
            })
        });
        this.memberModel = new Ext.grid.ColumnModel({
            columns: [new Ext.grid.RowNumberer(), this.memberSm, {
                header: "id",
                dataIndex: "id",
                sortable: true,
                hidden: true
            },
                {
                    header: "gid",
                    dataIndex: "id",
                    hidden: true
                },
                {
                    header: "mid",
                    dataIndex: "mid",
                    hidden: true
                },
                {
                    header: lang("member"),
                    dataIndex: "member",
                    id: "member"
                }]
        });
        this.centerPanel = new Ext.grid.GridPanel({
            region: "center",
            bodyStyle: "border-left-width: 1px; overflow: auto;",
            layout: "fit",
            border: false,
            hideBorders: true,
            store: this.memberStore,
            cm: this.memberModel,
            sm: this.memberSm,
            autoExpandColumn: "member",
            loadMask: {
                msg: lang("waiting")
            },
            listeners: {
                click: function() {
                    a.groupEditor.stopEditing()
                }
            },
            tbar: new Ext.Toolbar({
                style: "border-left-width: 1px;",
                items: [lang("member"), {
                    iconCls: "icon-system-refresh",
                    tooltip: lang("refresh"),
                    text: lang("refresh"),
                    style: "margin-left: 20px;",
                    handler: function(b, c) {
                        a.memberStore.reload()
                    }
                },
                    {
                        xtype: "btnForPoepleSelector",
                        iconCls: "icon-utils-s-add",
                        text: lang("add"),
                        showGroup: false,
                        dataUrl: a.baseUrl + "&task=getAllUserListsInPermitDeptTree",
                        listeners: {
                            selected: function(c, d) {
                                var e = [];
                                if (d.length > 0) {
                                    for (var b = 0; b < d.length; b++) {
                                        e.push(d[b].uid)
                                    }
                                    a.memberSubmit(e, a.groupId)
                                }
                            }
                        }
                    },
                    {
                        iconCls: "icon-utils-s-delete",
                        text: lang("del"),
                        tooltip: lang("del"),
                        handler: function(b) {
                            var c = a.centerPanel.getSelectionModel().getSelections();
                            if (c.length == 0) {
                                CNOA.miniMsg.alertShowAt(b, lang("mustSelectOneRow"))
                            } else {
                                CNOA.msg.cf(lang("confirmToDelete"),
                                    function(e) {
                                        if (e == "yes") {
                                            if (c) {
                                                var f = [];
                                                for (var d = 0; d < c.length; d++) {
                                                    f.push(c[d].get("id"))
                                                }
                                                a.deleteMember(f)
                                            }
                                        }
                                    })
                            }
                        }
                    }]
            })
        });
        this.mainPanel = new Ext.Panel({
            collapsible: false,
            hideBorders: true,
            border: false,
            layout: "border",
            autoScroll: false,
            items: [this.groupPanel, this.centerPanel]
        })
    },
    groupSubmit: function(a) {
        var b = this;
        Ext.Ajax.request({
            url: this.baseUrl + "&task=groupSubmit",
            params: a,
            method: "POST",
            success: function(e, d) {
                var c = Ext.decode(e.responseText);
                if (c.success === true) {
                    b.groupStore.reload()
                } else {
                    CNOA.msg.alert(c.msg,
                        function() {
                            b.groupStore.reload()
                        })
                }
            },
            failure: function(c, d) {
                CNOA.msg.alert(result.msg,
                    function() {
                        b.groupStore.reload()
                    })
            }
        })
    },
    deleteGroup: function(a) {
        var b = this;
        Ext.Ajax.request({
            url: this.baseUrl + "&task=deleteGroup",
            params: {
                ids: a
            },
            method: "POST",
            success: function(e, d) {
                var c = Ext.decode(e.responseText);
                if (c.success === true) {
                    b.groupStore.reload();
                    b.memberStore.reload()
                } else {
                    CNOA.msg.alert(c.msg)
                }
            }
        })
    },
    memberSubmit: function(b, a) {
        var c = this;
        Ext.Ajax.request({
            url: this.baseUrl + "&task=memberSubmit",
            method: "POST",
            params: {
                mids: Ext.encode(b),
                gid: a
            },
            success: function(f, e) {
                var d = Ext.decode(f.responseText);
                if (d.success === true) {
                    c.memberStore.reload()
                } else {
                    CNOA.msg.alert(d.msg,
                        function() {
                            c.memberStore.reload()
                        })
                }
            },
            failure: function(d, e) {
                CNOA.msg.alert(result.msg,
                    function() {
                        c.memberGroup.reload()
                    })
            }
        })
    },
    deleteMember: function(a) {
        var b = this;
        Ext.Ajax.request({
            url: this.baseUrl + "&task=deleteMember",
            params: {
                ids: Ext.encode(a),
                gid: this.groupId
            },
            method: "POST",
            success: function(e, d) {
                var c = Ext.decode(e.responseText);
                if (c.success === true) {
                    b.memberStore.reload()
                } else {
                    CNOA.msg.alert(c.msg)
                }
            }
        })
    }
};
var CNOA_main_system_updateClass, CNOA_main_system_update;
var CNOA_main_system_progressClass, CNOA_main_system_progress;
CNOA_main_system_progressClass = CNOA.Class.create();
CNOA_main_system_progressClass.prototype = {
    init: function() {
        var b = this;
        this.baseUrl = "index.php?app=main&func=system&action=setting&modul=update";
        var a = new Ext.XTemplate('<div id="upgrade">', '<b class="system">' + lang("systemBeUpdate") + "<span>...</span></b>", '<span class="tip">' + lang("updateDoNotClose") + "</span>", '<div class="upgrade_div">', '<div id="progress"></div>', "<b>" + lang("updateProgress") + '：<span id="percent">0</span>%</b>', "</div>", '<div id="cnoa_upgrade_logpanel"></div>', '<iframe id="cnoa_upgrade_iframe" src="' + this.baseUrl + "&task=upgrade&r=" + Math.random() + '"></iframe>', "</div>");
        this.mainPanel = new Ext.Window({
            width: 700,
            height: makeWindowHeight(500),
            modal: true,
            title: lang("onlineUpdate"),
            layout: "fit",
            closable: false,
            bodyStyle: "padding:10px;",
            listeners: {
                afterrender: function(c) {
                    a.overwrite(c.body)
                }
            }
        })
    },
    close: function() {
        this.mainPanel.close()
    },
    show: function() {
        this.mainPanel.show()
    },
    error: function(a) {
        var b = this;
        CNOA.msg.alert(a,
            function(c) {
                b.mainPanel.close()
            })
    },
    msg: function(a, b) {
        var c = this;
        if (b === true) {
            Ext.MessageBox.show({
                title: lang("systemPrompts"),
                msg: a,
                buttons: Ext.MessageBox.OK,
                icon: Ext.MessageBox.QUESTION,
                fn: function(d) {
                    if (d == "ok") {
                        c.mainPanel.close();
                        CNOA_main_system_update.getSystemInfo();
                        CNOA_main_system_update.patchStore.reload()
                    }
                }
            })
        } else {
            Ext.MessageBox.confirm(lang("systemPrompts"), lang("systemUpdateFails") + "<br />" + lang("followError") + "：" + a,
                function(d) {
                    if (d == "yes") {
                        $("#progress").width(0);
                        $("#cnoa_upgrade_logpanel").html("");
                        $("#cnoa_upgrade_iframe").get(0).contentWindow.location.reload()
                    } else {
                        c.mainPanel.close()
                    }
                })
        }
    }
};
CNOA_main_system_updateClass = CNOA.Class.create();
CNOA_main_system_updateClass.prototype = {
    init: function() {
        var a = this;
        this.baseUrl = "index.php?app=main&func=system&action=setting&modul=update";
        this.ID_td_cnoa = Ext.id();
        this.ID_btn_update = Ext.id();
        this.systemInfoTpl = new Ext.XTemplate('<table width="100%" border="0" cellpadding="0" cellspacing="1" class="CNOA_main_system_update_tbg">', '<tr><td align="center" height="28" colspan="2" valign="middle" bgcolor="#FFFFFF" class="cnoa_f_18 cnoa_f_b" style="background-color:#E9E9E9">' + lang("systemOverview") + '</td></tr><tr class="cnoa_f_14">', '<td width="40%" height="28" valign="middle" bgcolor="#F4F4F4">&nbsp;' + lang("systemName") + '</td><td width="80%" valign="middle" bgcolor="#FFFFFF">&nbsp;{cnoaname} [{versionType}' + lang("version") + "]</td></tr>", '<tr class="cnoa_f_14"><td height="28" valign="middle" bgcolor="#F4F4F4">&nbsp;' + lang("currentVersion") + '</td><td valign="middle" bgcolor="#FFFFFF">&nbsp;{version1}</td></tr>', '<tr class="cnoa_f_14"><td height="28" valign="middle" bgcolor="#F4F4F4">&nbsp;' + lang("lastUpdateTime") + '</td><td valign="middle" bgcolor="#FFFFFF">&nbsp;{lastUpdateTime}</td></tr>', '<tr><td height="50" colspan="2" align="center" valign="middle" bgcolor="#FFFFFF"><div id="' + this.ID_btn_update + '"></div></td></tr></table>');
        this.systemInfoPanel = new Ext.Panel({
            region: "west",
            width: 260,
            border: false,
            bodyStyle: "border-bottom-width:1px",
            listeners: {
                render: function() {
                    a.getSystemInfo()
                }
            },
            tbar: new Ext.Toolbar({
                items: [{
                    iconCls: "icon-system-refresh",
                    text: lang("refresh"),
                    handler: function(b, c) {
                        a.getSystemInfo()
                    }.createDelegate(this)
                }]
            })
        });
        this.patchFields = [{
            name: "patch"
        },
            {
                name: "size"
            },
            {
                name: "about"
            },
            {
                name: "version"
            },
            {
                name: "posttime"
            }];
        this.patchStore = new Ext.data.Store({
            proxy: new Ext.data.HttpProxy({
                url: this.baseUrl + "&task=getJsonData"
            }),
            reader: new Ext.data.JsonReader({
                totalProperty: "total",
                root: "data",
                fields: this.patchFields
            }),
            listeners: {
                exception: function(g, f, h, e, d, c) {
                    var b = Ext.decode(d.responseText);
                    if (b.failure) {
                        if (b.msg == "notconnecttoserver") {
                            CNOA.msg.alert("不能连接到升级服务器，请确认OA服务器是否已经连接上互联网再重试！<br><span style='color:gray'>点击[<a href='javascript:void(0);' onclick='CNOA_main_system_update.checkNet()'>这里</a>]查看调试信息</span>")
                        }
                    }
                },
                load: function(d, b, c) {
                    if (d.getCount() > 0) {
                        a.patchPanel.getSelectionModel().selectFirstRow();
                        a._detail(0)
                    } else {
                        a.detailsTpl.overwrite(a.detailsPanel.body, {
                            text: "无新升级包"
                        })
                    }
                }
            }
        });
        this.patchModel = new Ext.grid.ColumnModel({
            defaults: {
                width: 120,
                sortable: true,
                menuDisabled: true,
                align: "center"
            },
            columns: [new Ext.grid.RowNumberer(), {
                header: "更新包",
                dataIndex: "patch",
                id: "patch",
                align: "left"
            },
                {
                    header: lang("versionNumber"),
                    dataIndex: "version"
                },
                {
                    header: lang("size"),
                    dataIndex: "size",
                    width: 110
                },
                {
                    header: lang("releaseDate"),
                    dataIndex: "posttime",
                    width: 150
                },
                {
                    header: lang("opt"),
                    renderer: this._opt.createDelegate(this),
                    width: 100
                }]
        });
        this.patchPanel = new Ext.grid.GridPanel({
            region: "center",
            store: this.patchStore,
            cm: this.patchModel,
            border: false,
            bodyStyle: "border-width:0 0 1px 1px",
            autoExpandColumn: "patch",
            loadMask: {
                msg: lang("waiting")
            },
            listeners: {
                afterrender: function() {
                    setTimeout(function() {
                            a.patchStore.load()
                        },
                        500)
                }
            },
            tbar: new Ext.Toolbar({
                items: [{
                    iconCls: "icon-system-refresh",
                    cls: "btn-green1",
                    text: lang("checkUpdate"),
                    handler: function(b, c) {
                        a.patchStore.reload()
                    }.createDelegate(this)
                }]
            })
        });
        this.topPanel = new Ext.Panel({
            region: "north",
            layout: "border",
            height: 200,
            split: true,
            border: false,
            items: [this.systemInfoPanel, this.patchPanel]
        });
        this.detailsTpl = new Ext.XTemplate('<div style="padding:10px;">{text}<div>');
        this.detailsPanel = new Ext.Panel({
            autoScroll: true,
            id: "updateDetailsPanel",
            title: lang("updateDetails")
        });
        this.historyPanel = new Ext.Panel({
            autoScroll: true,
            title: "OA更新日志",
            html: '<iframe scrolling=auto frameborder=0 width=100% height=100% id="cnoa_upgrade_history_iframe" src="http://service.cnoa.cn/update/update.php?task=history"></iframe>'
        });
        this.centerPanel = new Ext.TabPanel({
            region: "center",
            activeTab: 0,
            border: false,
            deferredRender: true,
            items: [this.detailsPanel, this.historyPanel]
        });
        this.mainPanel = new Ext.Panel({
            collapsible: false,
            layout: "border",
            autoScroll: false,
            border: false,
            items: [this.topPanel, this.centerPanel]
        })
    },
    _grade: function(a) {
        switch (a) {
            case "1":
                a = '<img src="resources/images/update/icon_green.gif" />';
                break;
            case "2":
                a = '<img src="resources/images/update/icon_red.gif" />';
                break
        }
        return a
    },
    _opt: function(f, d, a, g, b, e) {
        var c = a.data;
        return '<a style="cursor:pointer;" onclick="CNOA_main_system_update._detail(' + g + ');">' + lang("updateLog") + '</a>&nbsp;&nbsp;<a style="cursor:pointer;" onclick="CNOA_main_system_update._update(' + g + ');">' + lang("upgrade") + "</a>"
    },
    _update: function(i) {
        var b = this.patchStore.getAt(i).get("version");
        var e = Ext.getBody().getBox(),
            a = e.width - 100,
            d = e.height - 100;
        var c = Ext.id(),
            g = "index.php?app=main&func=system&action=setting&modul=update&task=getAgree";
        var f = new Ext.Window({
            width: 760,
            height: Ext.getBody().getBox().height - 100,
            modal: true,
            title: "OA升级更新协议",
            html: '<div style="width:100%;height:100%;"><iframe scrolling=auto frameborder=0 width=100% height=100% id="' + c + '"></iframe></div>',
            buttons: [{
                text: "同意本协议开始升级",
                cls: "btn-green1",
                handler: function() {
                    f.close();
                    CNOA_main_system_progress = new CNOA_main_system_progressClass();
                    CNOA_main_system_progress.show()
                }
            },
                {
                    text: "取消",
                    cls: "btn-gray1",
                    handler: function() {
                        f.close()
                    }
                }],
            listeners: {
                afterrender: function() {
                    Ext.getDom(c).contentWindow.location.href = g
                }
            }
        }).show();
        return;
        CNOA.msg.cf("<b>" + lang("sureUpgrade") + "[v " + b + "]？</b><br>" + lang("clickButton") + "<b>[" + lang("yes") + "]</b>" + lang("agreeUpgredeXieYi") + "<br>" + lang("clickButton") + "<b>[" + lang("no") + "]</b>" + lang("doNotAgree") + "<br>" + lang("clickHereView") + "：[<a style='color:red' href='http://www.cnoa.cn/about/c/updateagree' target='_blank'>" + lang("upgradeAgree") + "</a>]",
            function(h) {
                if (h == "yes") {
                    CNOA_main_system_progress = new CNOA_main_system_progressClass();
                    CNOA_main_system_progress.show()
                }
            })
    },
    _detail: function(b) {
        var a = this.patchStore.getAt(b).get("about");
        this.detailsTpl.overwrite(this.detailsPanel.body, {
            text: a
        })
    },
    checkNet: function() {
        var b = this;
        var a = new Ext.Window({
            width: 500,
            height: 400,
            title: "查看调试信息",
            maximizable: true,
            resizable: false,
            modal: true,
            layout: "fit",
            padding: 10,
            autoScroll: true,
            html: "请稍等,发送调试信息中(可能需要花几分钟的时间)...",
            buttons: [{
                xtype: "displayfield",
                value: "<span style='color:#6f6f6f'>请把上面显示的调试信息复制出来发给我们，以便更快判断问题所在</span>"
            },
                {
                    text: "关闭",
                    handler: function() {
                        a.close()
                    }
                }],
            listeners: {
                afterrender: function(c) {
                    Ext.Ajax.request({
                        url: b.baseUrl + "&task=checkNet",
                        method: "GET",
                        timeout: 9999999,
                        success: function(e) {
                            var d = Ext.decode(e.responseText);
                            c.body.update(e.responseText)
                        }.createDelegate(this),
                        failure: function(d) {}
                    })
                }
            }
        }).show()
    },
    getSystemInfo: function() {
        var a = this;
        this.systemInfoPanel.getEl().mask(lang("waiting"));
        Ext.Ajax.request({
            url: a.baseUrl + "&task=getSystemInfo",
            method: "GET",
            success: function(c) {
                a.systemInfoPanel.getEl().unmask();
                var b = Ext.decode(c.responseText);
                a.systemInfoTpl.overwrite(a.systemInfoPanel.body, b.data)
            }.createDelegate(this),
            failure: function(c) {
                a.systemInfoPanel.getEl().unmask();
                var b = Ext.decode(c.responseText);
                CNOA.msg.alert(b.msg)
            }
        })
    }
};
var CNOA_main_system_languageClass, CNOA_main_system_language;
CNOA_main_system_languageListClass = CNOA.Class.create();
CNOA_main_system_languageListClass.prototype = {
    init: function(c) {
        var d = this;
        this.baseUrl = "index.php?app=main&func=system&action=language";
        this.tp = c;
        this.title = "语言设置";
        this.action = c == "edit" ? "edit": "add";
        this.edit_id = 0;
        this.ID_btn_delete = Ext.id();
        this.ID_btn_edit = Ext.id();
        this.ID_groupComboBox = Ext.id();
        var b = function(h, g, e) {
            var f = e.data;
            var i = h;
            if (f.status == "1") {
                i += "&nbsp;<span class='cnoa_color_green'>[" + lang("enabled") + "]</span>"
            } else {
                i += "&nbsp;<span class='cnoa_color_red'>[" + lang("disabled") + "]</span>"
            }
            return i
        };
        var a = function(h, g, e) {
            var f = e.data;
            var i = "";
            if (f.status == "1") {
                i += "<a href='javascript:void(0);' onclick='CNOA_main_system_languageList.setStatus(0, " + f.id + ");'>" + lang("disable") + "</a>"
            } else {
                i += "<a href='javascript:void(0);' onclick='CNOA_main_system_languageList.setStatus(1, " + f.id + ");'>" + lang("enable") + "</a>"
            }
            return i
        };
        this.fields = [{
            name: "id"
        },
            {
                name: "lang"
            },
            {
                name: "name"
            },
            {
                name: "status"
            }];
        this.store = new Ext.data.Store({
            autoLoad: true,
            proxy: new Ext.data.HttpProxy({
                url: this.baseUrl + "&task=loadLanguageList"
            }),
            reader: new Ext.data.JsonReader({
                totalProperty: "total",
                root: "data",
                fields: this.fields
            })
        });
        this.sm = new Ext.grid.CheckboxSelectionModel({
            singleSelect: true
        });
        this.colum = new Ext.grid.ColumnModel([new Ext.grid.RowNumberer(), this.sm, {
            header: "id",
            dataIndex: "id",
            hidden: true
        },
            {
                header: lang("languageCode"),
                width: 100,
                dataIndex: "lang"
            },
            {
                header: lang("languageName"),
                width: 234,
                dataIndex: "name",
                renderer: b.createDelegate(this)
            },
            {
                header: lang("status"),
                width: 76,
                dataIndex: "status",
                renderer: a.createDelegate(this)
            }]);
        this.grid = new Ext.grid.GridPanel({
            store: this.store,
            sm: this.sm,
            hideBorders: true,
            border: false,
            colModel: this.colum
        });
        this.mainPanel = new Ext.Window({
            width: 500,
            height: 360,
            resizable: false,
            layout: "fit",
            modal: true,
            maskDisabled: true,
            items: [this.grid],
            plain: true,
            title: this.title,
            listeners: {
                close: function(e, f) {
                    CNOA_main_system_language.loadList()
                }
            },
            tbar: [{
                iconCls: "icon-utils-s-add",
                text: lang("add") + lang("language"),
                cls: "btn-blue4",
                handler: function(e, f) {
                    this.addEdit()
                }.createDelegate(this)
            },
                {
                    text: lang("modify") + lang("language"),
                    cls: "btn-red1",
                    iconCls: "icon-utils-s-edit",
                    handler: function(e) {
                        var f = this.grid.getSelectionModel().getSelections();
                        if (f.length == 0) {
                            CNOA.miniMsg.alertShowAt(e, lang("mustSelectOneRow"))
                        } else {
                            this.addEdit("edit", f[0].get("id"))
                        }
                    }.createDelegate(this)
                },
                {
                    text: lang("del"),
                    iconCls: "icon-utils-s-delete",
                    id: this.ID_btn_delete,
                    handler: function(e, f) {
                        var g = this.grid.getSelectionModel().getSelections();
                        if (g.length == 0) {
                            CNOA.miniMsg.alertShowAt(e, lang("mustSelectOneRow"))
                        } else {
                            CNOA.msg.cf(lang("confirmToDelete"),
                                function(h) {
                                    if (h == "yes") {
                                        d.deleteList(g[0].get("id"))
                                    }
                                })
                        }
                    }.createDelegate(this)
                }],
            buttons: [{
                text: lang("close"),
                cls: "btn-red1",
                iconCls: "icon-dialog-cancel",
                handler: function() {
                    d.close();
                    d.store.reload()
                }
            }]
        }).show()
    },
    setStatus: function(a, d) {
        var c = this;
        var b = function() {
            Ext.Ajax.request({
                url: c.baseUrl + "&task=changeStatus",
                method: "POST",
                params: {
                    id: d,
                    status: a
                },
                success: function(f) {
                    var e = Ext.decode(f.responseText);
                    if (e.success === true) {
                        CNOA.msg.notice2(e.msg);
                        c.store.reload()
                    } else {
                        CNOA.msg.alert(e.msg)
                    }
                }
            })
        };
        if (a === 1) {
            b()
        } else {
            CNOA.msg.cf(lang("disableIt"),
                function(e) {
                    if (e == "yes") {
                        b()
                    }
                })
        }
    },
    addEdit: function(b, f) {
        var e = this;
        var c = function() {
            if (a.getForm().isValid()) {
                a.getForm().submit({
                    url: e.baseUrl + (b == "edit" ? "&task=editLanguage": "&task=addLanguage"),
                    waitMsg: lang("waiting"),
                    params: b == "edit" ? {
                        id: f
                    }: {},
                    method: "POST",
                    success: function(g, h) {
                        CNOA.msg.notice2(h.result.msg);
                        e.store.reload();
                        d.close()
                    },
                    failure: function(g, h) {
                        Ext.Msg.alert(lang("notice"), h.result.msg,
                            function() {})
                    }
                })
            }
        };
        var a = new Ext.form.FormPanel({
            border: false,
            waitMsgTarget: true,
            labelWidth: 75,
            autoScroll: true,
            bodyStyle: "padding: 10px;",
            border: false,
            defaults: {
                width: 254,
                xtype: "textfield",
                border: false
            },
            items: [{
                fieldLabel: lang("languageCode"),
                name: "lang",
                disabled: b == "edit" ? true: false,
                allowBlank: false,
                value: ""
            },
                {
                    xtype: "displayfield",
                    hideLabel: true,
                    style: "margin-left:80px",
                    value: lang("countryCode")
                },
                {
                    fieldLabel: lang("languageName"),
                    name: "name",
                    allowBlank: false,
                    value: ""
                },
                {
                    xtype: "checkbox",
                    fieldLabel: lang("status"),
                    boxLabel: lang("isEnable"),
                    name: "status",
                    value: ""
                }]
        });
        var d = new Ext.Window({
            width: 376,
            height: 230,
            title: (b == "edit" ? lang("modify") : lang("added")) + lang("language"),
            modal: true,
            layout: "fit",
            items: [a],
            buttons: [{
                text: b == "edit" ? lang("modify") : lang("added"),
                cls: "btn-blue4",
                handler: function() {
                    c()
                }
            },
                {
                    text: lang("cancel"),
                    handler: function() {
                        d.close()
                    }
                }]
        }).show();
        if (b == "edit") {
            a.getForm().load({
                url: e.baseUrl + "&task=loadLanguage",
                params: {
                    id: f
                },
                method: "POST",
                waitMsg: "loading...",
                failure: function(g, h) {
                    CNOA.msg.alert(h.result.msg)
                }.createDelegate(this)
            })
        }
    },
    show: function(b) {
        var a = this;
        a.mainPanel.show()
    },
    close: function() {
        this.mainPanel.close()
    },
    deleteList: function(b) {
        var a = this;
        Ext.Ajax.request({
            url: this.baseUrl + "&task=deleteLanguage",
            method: "POST",
            params: {
                id: b
            },
            success: function(d) {
                var c = Ext.decode(d.responseText);
                if (c.success === true) {
                    CNOA.msg.notice2(c.msg);
                    a.store.reload()
                } else {
                    CNOA.msg.alert(c.msg)
                }
            }
        })
    }
};
CNOA_main_system_languageClass = CNOA.Class.create();
CNOA_main_system_languageClass.prototype = {
    init: function() {
        var a = this;
        this.baseUrl = "index.php?app=main&func=system&action=language";
        this.loadList()
    },
    loadList: function() {
        var a = this;
        Ext.Ajax.request({
            url: this.baseUrl + "&task=loadList",
            method: "POST",
            success: function(c) {
                var b = Ext.decode(c.responseText);
                if (b.success === true) {
                    a.makeView(b.data)
                } else {
                    CNOA.msg.alert(b.msg)
                }
            }
        })
    },
    makeView: function(d) {
        var f = this;
        var b = [{
                name: "code"
            }],
            a = [new Ext.grid.RowNumberer(), {
                header: "Code",
                dataIndex: "code",
                width: 130
            }];
        Ext.each(d,
            function(h, g) {
                b.push({
                    name: h.lang
                });
                if (g > 0) {
                    a.push({
                        header: (h.status == "0" ? "<span class='cnoa_color_red'>[" + lang("disabled") + "]</span> ": "") + h.name + "(" + h.lang + ")",
                        dataIndex: h.lang,
                        editor: new Ext.form.TextField()
                    })
                } else {
                    a.push({
                        header: h.name + "(" + h.lang + ")",
                        dataIndex: h.lang
                    })
                }
            });
        var e = {
            name: ""
        };
        this.fields = b;
        this.store = new Ext.data.Store({
            autoLoad: true,
            baseParams: e,
            proxy: new Ext.data.HttpProxy({
                url: this.baseUrl + "&task=getJsonData"
            }),
            reader: new Ext.data.JsonReader({
                totalProperty: "total",
                root: "data",
                fields: this.fields
            })
        });
        this.model = new Ext.grid.ColumnModel({
            defaults: {
                width: 300,
                sortable: false,
                menuDisabled: true
            },
            columns: a
        });
        this.pagingBar = new Ext.PagingToolbar({
            displayInfo: true,
            emptyMsg: lang("pagingToolbarEmptyMsg"),
            displayMsg: lang("pagingToolbarDisplayMsg"),
            store: this.store,
            pageSize: 25
        });
        var c = new Ext.form.TextField({
            width: 220
        });
        this.gridPanel = new Ext.grid.EditorGridPanel({
            region: "center",
            store: this.store,
            cm: this.model,
            border: false,
            stripeRows: true,
            loadMask: {
                msg: lang("waiting")
            },
            tbar: new Ext.Toolbar({
                items: [{
                    iconCls: "icon-system-refresh",
                    text: lang("refresh"),
                    handler: function(g, h) {
                        f.store.reload()
                    }.createDelegate(this)
                },
                    {
                        iconCls: "icon-system-setup",
                        text: lang("manage") + lang("language"),
                        cls: "btn-blue4",
                        handler: function(g, h) {
                            CNOA_main_system_languageList = new CNOA_main_system_languageListClass()
                        }.createDelegate(this)
                    },
                    {
                        iconCls: "icon-excel",
                        cls: "btn-blue3",
                        text: "导出模板",
                        handler: function(g, h) {
                            this.exportTpl()
                        }.createDelegate(this)
                    },
                    {
                        iconCls: "document-excel-import",
                        cls: "btn-blue3",
                        text: "导入语言",
                        handler: function(g, h) {
                            this.importLang()
                        }.createDelegate(this)
                    },
                    "查询内容：", c, {
                        text: lang("search"),
                        handler: function() {
                            e.name = c.getValue();
                            f.store.load()
                        }
                    },
                    {
                        text: lang("clear"),
                        handler: function() {
                            c.setValue("");
                            e.name = "";
                            f.store.load()
                        }
                    },
                    ('<span class="cnoa_color_gray">' + lang("dblclickToEdit") + "</span>")]
            }),
            bbar: this.pagingBar,
            listeners: {
                afteredit: function(h) {
                    var i = h.record;
                    var g = h.field;
                    var k = i.get("id");
                    var j = i.data;
                    j.id = i.id;
                    Ext.Ajax.request({
                        url: this.baseUrl + "&task=edit",
                        method: "POST",
                        params: i.data,
                        success: function(m) {
                            var l = Ext.decode(m.responseText);
                            if (l.success === true) {
                                CNOA.msg.notice2(l.msg)
                            } else {
                                CNOA.msg.alert(l.msg,
                                    function() {})
                            }
                        }
                    })
                }.createDelegate(this)
            }
        });
        this.mainPanel = new Ext.Panel({
            collapsible: false,
            layout: "border",
            autoScroll: false,
            border: false,
            items: [this.gridPanel]
        });
        Ext.ns("CNOA.system.language");
        Ext.getCmp(CNOA.system.language.parentID).removeAll();
        Ext.getCmp(CNOA.system.language.parentID).add(this.mainPanel);
        Ext.getCmp(CNOA.system.language.parentID).doLayout()
    },
    exportTpl: function() {
        ajaxDownload(this.baseUrl + "&task=exportTpl")
    },
    importLang: function() {
        var c = this;
        var a = new Ext.data.Store({
            proxy: new Ext.data.HttpProxy({
                url: this.baseUrl + "&task=getLangList"
            }),
            reader: new Ext.data.JsonReader({
                root: "data",
                fields: [{
                    name: "lang"
                },
                    {
                        name: "value"
                    }]
            })
        });
        a.load();
        var b = new Ext.form.ComboBox({
            fieldLabel: lang("importWhichLanguage"),
            name: "lang",
            store: a,
            hiddenName: "lang",
            valueField: "value",
            displayField: "lang",
            mode: "local",
            allowBlank: false,
            triggerAction: "all",
            forceSelection: true,
            editable: false,
            width: 194
        });
        UPLOAD_WINDOW_FORMPANEL = new Ext.FormPanel({
            fileUpload: true,
            waitMsgTarget: true,
            hideBorders: true,
            border: false,
            bodyStyle: "padding: 5px;",
            items: [b, {
                xtype: "fileuploadfield",
                name: "file",
                fieldLabel: lang("uploadLanguage"),
                allowBlank: false,
                buttonCfg: {
                    text: lang("browseLanguage")
                },
                width: 264,
                listeners: {
                    fileselected: function(e, d) {}
                }
            },
                {
                    xtype: "displayfield",
                    hideLabel: true,
                    value: lang("pleaseImportLanguage")
                }]
        });
        UPLOAD_WINDOW = new Ext.Window({
            width: 398,
            height: 230,
            layout: "fit",
            modal: true,
            resizable: false,
            title: lang("browseLanguage"),
            items: [UPLOAD_WINDOW_FORMPANEL],
            buttons: [{
                text: lang("import2"),
                handler: function() {
                    if (UPLOAD_WINDOW_FORMPANEL.getForm().isValid()) {
                        UPLOAD_WINDOW_FORMPANEL.getForm().submit({
                            url: c.baseUrl + "&task=import",
                            waitMsg: lang("waiting"),
                            params: {},
                            success: function(d, e) {
                                CNOA.msg.alert(e.result.msg,
                                    function() {
                                        c.store.reload();
                                        UPLOAD_WINDOW.close()
                                    })
                            },
                            failure: function(d, e) {
                                CNOA.msg.alert(e.result.msg,
                                    function() {})
                            }
                        })
                    }
                }.createDelegate(this)
            },
                {
                    text: lang("close"),
                    handler: function() {
                        UPLOAD_WINDOW.close()
                    }
                }]
        }).show()
    }
};
var CNOA_main_system_setofbooks, CNOA_main_system_setofbooksClass;
CNOA_main_system_setofbooksClass = new CNOA.Class.create();
CNOA_main_system_setofbooksClass.prototype = {
    init: function() {
        this.baseUrl = "index.php?app=main&func=system&action=setofbooks";
        var a = this.getsetOfBooksPanel();
        this.mainPanel = new Ext.ux.VerticalTabPanel({
            region: "center",
            border: false,
            tabPosition: "left",
            tabWidth: 100,
            activeItem: 0,
            deferredRender: true,
            layoutOnTabChange: true,
            items: [a]
        });
        this.getPanel()
    },
    getsetOfBooksPanel: function() {
        var e = this;
        var b = [{
            name: "id"
        },
            {
                name: "name"
            }];
        var a = new Ext.grid.ColumnModel({
            defaults: {
                align: "center",
                menuDisabled: true
            },
            columns: [new Ext.grid.RowNumberer(), {
                header: "id",
                dataIndex: "id",
                hidden: true
            },
                {
                    header: "账套名称",
                    dataIndex: "name",
                    width: 100,
                    align: "left"
                },
                {
                    header: lang("opt"),
                    dataIndex: "",
                    width: 250,
                    renderer: e.makeOperate
                }]
        });
        this.setOfBooksStore = new Ext.data.Store({
            autoLoad: true,
            proxy: new Ext.data.HttpProxy({
                url: e.baseUrl + "&task=getList",
                disableCaching: true
            }),
            reader: new Ext.data.JsonReader({
                root: "data",
                fields: b
            })
        });
        var d = new Ext.grid.GridPanel({
            region: "center",
            store: e.setOfBooksStore,
            cm: a,
            tbar: new Ext.Toolbar({
                items: [{
                    text: "刷新",
                    cls: "btn-blue4",
                    handler: function() {
                        e.setOfBooksStore.reload()
                    }
                },
                    {
                        text: "重置系统",
                        cls: "btn-yellow1",
                        handler: function() {
                            e.resetSystem()
                        }
                    },
                    {
                        text: "增加账套",
                        handler: function() {
                            e.importWin()
                        }
                    }]
            })
        });
        var c = new Ext.Panel({
            collapsible: false,
            hideBorders: true,
            border: false,
            layout: "border",
            autoScroll: false,
            region: "center",
            title: "账套列表",
            items: [d]
        });
        return c
    },
    getPanel: function() {
        var a = this;
        Ext.Ajax.request({
            url: a.baseUrl + "&task=getPermitFields",
            success: function(c) {
                var b = Ext.decode(c.responseText).data;
                a.fields = b.fields;
                a.areaFields = b.area;
                a.count = b.count;
                a.createPanel(b)
            }
        })
    },
    createPanel: function(m) {
        var g = this;
        var f = [{
            name: "uid"
        },
            {
                name: "trueName"
            },
            {
                name: "deptName"
            },
            {
                name: "mobile"
            },
            {
                name: "stationName"
            },
            {
                name: "jobName"
            },
            {
                name: "username"
            },
            {
                name: "customPermit"
            }];
        var k = new Ext.data.Store({
            proxy: new Ext.data.HttpProxy({
                url: g.baseUrl + "&task=getJobList"
            }),
            reader: new Ext.data.JsonReader({
                totalProperty: "total",
                root: "data",
                fields: ["id", "name"]
            })
        });
        var c = [{
            header: "名字",
            dataIndex: "trueName",
            width: 80,
            align: "left",
            locked: true,
            editor: new Ext.form.TextField({
                allowBlank: false
            })
        },
            {
                header: "登录名",
                dataIndex: "username",
                width: 80,
                align: "left",
                editor: new Ext.form.TextField({
                    allowBlank: false
                })
            },
            {
                header: "用户ID",
                dataIndex: "uid",
                hidden: true
            },
            {
                header: "部门名称",
                dataIndex: "deptName",
                width: 120,
                editor: new Ext.form.ComboBox({
                    store: new Ext.data.Store({
                        proxy: new Ext.data.HttpProxy({
                            url: g.baseUrl + "&task=getDeptList"
                        }),
                        reader: new Ext.data.JsonReader({
                            totalProperty: "total",
                            root: "data",
                            fields: ["id", "name"]
                        })
                    }),
                    width: 120,
                    displayField: "name",
                    valueField: "id",
                    triggerAction: "all",
                    allowBlank: false,
                    listeners: {
                        select: function(i) {
                            g.deptId = i.value;
                            i.value = i.lastSelectionText
                        }
                    }
                })
            },
            {
                header: "岗位名称",
                dataIndex: "stationName",
                width: 120,
                editor: new Ext.form.ComboBox({
                    store: new Ext.data.Store({
                        proxy: new Ext.data.HttpProxy({
                            url: g.baseUrl + "&task=getStationList"
                        }),
                        reader: new Ext.data.JsonReader({
                            totalProperty: "total",
                            root: "data",
                            fields: ["sid", "name"]
                        })
                    }),
                    width: 120,
                    displayField: "name",
                    valueField: "sid",
                    triggerAction: "all",
                    allowBlank: false,
                    listeners: {
                        select: function(i) {
                            g.stationid = i.value;
                            i.value = i.lastSelectionText
                        }
                    }
                })
            },
            {
                header: "职位名称",
                dataIndex: "jobName",
                width: 120,
                editor: new Ext.form.ComboBox({
                    store: k,
                    width: 120,
                    displayField: "name",
                    valueField: "id",
                    triggerAction: "all",
                    allowBlank: false,
                    listeners: {
                        select: function(i) {
                            g.jobId = i.value;
                            i.value = i.lastSelectionText
                        }
                    }
                })
            },
            {
                header: "手机",
                dataIndex: "mobile",
                width: 100,
                align: "left",
                editor: new Ext.form.NumberField({
                    nanText: "只能是数字",
                    allowBlank: false,
                    allowNegative: false,
                    regex: /^1\d{10}$/,
                    regexText: "请输入11位数字",
                    allowDecimals: false
                })
            },
            {
                header: "自定义权限",
                dataIndex: "customPermit",
                width: 80,
                align: "center",
                renderer: g.makeUserPermit
            }];
        for (var e = 0; e < m.fields.length; e++) {
            var l = {
                    name: m.fields[e].permitField
                },
                b = {
                    header: m.fields[e].name,
                    dataIndex: m.fields[e].permitField,
                    width: 100,
                    align: "center",
                    renderer: g.makeCheck
                };
            f.push(l);
            c.push(b)
        }
        for (var e = 0; e < m.area.length; e++) {
            var l = {
                    name: m.area[e].permitField
                },
                b = {
                    header: m.area[e].name,
                    dataIndex: m.area[e].permitField,
                    width: 100,
                    align: "center",
                    renderer: g.makeCheck2
                };
            f.push(l);
            c.push(b)
        }
        var j = new Ext.ux.grid.LockingColumnModel({
            columns: c
        });
        var h = new Ext.data.Store({
            autoLoad: true,
            proxy: new Ext.data.HttpProxy({
                url: g.baseUrl + "&task=getJsonData",
                disableCaching: true
            }),
            reader: new Ext.data.JsonReader({
                root: "data",
                fields: f
            })
        });
        g.permitStore = h;
        var d = new Ext.PagingToolbar({
            plugins: [new Ext.grid.plugins.ComboPageSize()],
            displayInfo: true,
            store: h,
            pageSize: 2
        });
        var a = new Ext.grid.EditorGridPanel({
            title: "设置账套",
            region: "center",
            view: new Ext.ux.grid.LockingGridView(),
            store: h,
            stripeRows: true,
            width: 500,
            cm: j,
            tbar: new Ext.Toolbar({
                items: [{
                    text: "刷新",
                    cls: "btn-blue4",
                    handler: function() {
                        h.reload()
                    }
                },
                    {
                        text: "导出",
                        handler: function() {
                            ajaxDownload(g.baseUrl + "&task=exportSetOfBooks")
                        }
                    },
                    {
                        text: "添加用户",
                        handler: function() {
                            loadScripts("sm_main_user", "app/main/scripts/cnoa.user.js",
                                function() {
                                    CNOA_main_user_info = new CNOA_main_user_infoClass(g, "add");
                                    CNOA_main_user_info.show()
                                })
                        }
                    }]
            }),
            bbar: d,
            listeners: {
                afteredit: function(p) {
                    var o = p.field,
                        i = p.record.get("uid"),
                        n = p.value;
                    g.updateGrid(i, o, n, h)
                },
                beforeedit: function(i) {
                    if (i.field == "jobName") {
                        k.load({
                            params: {
                                deptId: i.record.json.deptId
                            }
                        })
                    }
                }
            }
        });
        g.mainPanel.add(a);
        g.mainPanel.doLayout()
    },
    updateGrid: function(b, e, d, a) {
        var c = this;
        if (e == "deptName") {
            e = "deptId";
            d = c.deptId
        }
        if (e == "stationName") {
            e = "stationid";
            d = c.stationid
        }
        if (e == "jobName") {
            e = "jobId";
            d = c.jobId
        }
        Ext.Ajax.request({
            url: c.baseUrl + "&task=update",
            params: {
                uid: b,
                field: e,
                value: d
            },
            success: function(g) {
                var f = Ext.decode(g.responseText);
                if (f.failure === true) {
                    CNOA.msg.alert(f.msg);
                    a.reload()
                } else {
                    if (e == "jobId" || e == "deptId") {
                        a.reload()
                    }
                    CNOA.msg.notice2(f.msg)
                }
            }
        })
    },
    makeCheck: function(h, f, c, g, e, i) {
        var d = c.json.uid,
            a = e - 8,
            b = CNOA_main_system_setofbooks.fields[a].id;
        return "<input type='checkbox' data-type='userPermit' data-uid='" + d + "' data-permitId='" + b + "' " + (h == 1 ? "checked": "") + " onclick=CNOA_main_system_setofbooks.permitOnClick(this)></input>"
    },
    makeCheck2: function(h, f, c, g, e, i) {
        var d = c.json.uid,
            a = e - 8 - CNOA_main_system_setofbooks.count,
            b = CNOA_main_system_setofbooks.areaFields[a].id;
        return "<input type='button' style='background-color:#3A85C6;color:#FFF;cursor:pointer;border-radius:2px;border:1px solid #286EA9;' value='范围' data-type='userPermit' data-uid='" + d + "' data-permitId='" + b + "'  onclick=CNOA_main_system_setofbooks.permitOnClick2(this)></input>"
    },
    makeUserPermit: function(f, e, a, g, d, b) {
        var c = a.json.uid;
        return "<input type='checkbox' data-uid='" + c + "' " + (f == 1 ? "checked": "") + " onclick=CNOA_main_system_setofbooks.openUserPermit(this)></input>"
    },
    permitOnClick: function(f) {
        var c = this;
        var a = $(f).attr("data-uid"),
            d = $(f).attr("data-permitId"),
            b = $(f).attr("checked");
        Ext.Ajax.request({
            url: c.baseUrl + "&task=checkUserPermit",
            params: {
                uid: a
            },
            success: function(g) {
                var e = Ext.decode(g.responseText);
                if (e.failure) {
                    $(f).attr("checked", !b);
                    CNOA.msg.alert(e.msg)
                } else {
                    if (e.success) {
                        Ext.Ajax.request({
                            url: c.baseUrl + "&task=saveUserPermit",
                            params: {
                                uid: a,
                                permitId: d,
                                checked: b
                            },
                            success: function(i) {
                                var h = Ext.decode(i.responseText);
                                CNOA.msg.notice2(h.msg)
                            }
                        })
                    }
                }
            }
        })
    },
    permitOnClick2: function(f) {
        var c = this;
        var a = $(f).attr("data-uid"),
            d = $(f).attr("data-permitId"),
            b = $(f).attr("checked");
        Ext.Ajax.request({
            url: c.baseUrl + "&task=checkUserPermit",
            params: {
                uid: a
            },
            success: function(g) {
                var e = Ext.decode(g.responseText);
                if (e.failure) {
                    CNOA.msg.alert(e.msg)
                } else {
                    if (e.success) {
                        c.permitWin(a, d)
                    }
                }
            }
        })
    },
    openUserPermit: function(d) {
        var c = this,
            a = $(d).attr("data-uid"),
            b = $(d).attr("checked"),
            f = "";
        $(":checkbox[data-uid='" + a + "'][data-type='userPermit'][checked='true']").each(function() {
            f += $(this).attr("data-permitId") + ","
        });
        Ext.Ajax.request({
            url: c.baseUrl + "&task=openUserPermit",
            params: {
                uid: a,
                permitIds: f,
                checked: b
            },
            success: function(g) {
                var e = Ext.decode(g.responseText);
                CNOA.msg.notice2(e.msg);
                c.permitStore.reload()
            }
        })
    },
    makeOperate: function(e, b, a, g, d, c) {
        var f = a.json.id;
        e = "<a href='javascript:void(0);'  class='gridview' onclick='CNOA_main_system_setofbooks.importSetOfBooks(" + f + ");'>导入账套</a>";
        e += "　<a href='javascript:void(0);'  class='gridview4' onclick='CNOA_main_system_setofbooks.deleteSetOfBooks(" + f + ");'>删除账套</a>";
        return e
    },
    importWin: function() {
        var b = this;
        var a = new Ext.form.FormPanel({
            border: false,
            fileUpload: true,
            bodyStyle: "padding:10px;",
            waitMsgTarget: true,
            labelWidth: 110,
            items: [{
                xtype: "textfield",
                width: 300,
                fieldLabel: lang("name"),
                allowBlank: false,
                name: "name"
            },
                {
                    xtype: "fileuploadfield",
                    name: "data",
                    allowBlank: false,
                    fieldLabel: "选择文件",
                    buttonCfg: {
                        text: lang("browse")
                    },
                    width: 300
                }]
        });
        var c = function() {
            if (a.getForm().isValid()) {
                a.getForm().submit({
                    url: b.baseUrl + "&task=importSetOfbooks",
                    waitMsg: lang("waiting"),
                    params: {},
                    success: function(e, f) {
                        CNOA.msg.alert(f.result.msg,
                            function() {
                                d.close();
                                b.setOfBooksStore.reload()
                            })
                    },
                    failure: function(e, f) {
                        CNOA.msg.alert(f.result.msg,
                            function() {})
                    }
                })
            }
        };
        var d = new Ext.Window({
            width: 460,
            height: 180,
            title: "导入套账",
            resizable: false,
            modal: true,
            layout: "fit",
            items: a,
            buttons: [{
                text: lang("import2"),
                cls: "btn-blue3",
                handler: function() {
                    c()
                }.createDelegate(this)
            },
                {
                    text: lang("cancel"),
                    handler: function() {
                        d.close()
                    }.createDelegate(this)
                }]
        }).show()
    },
    deleteSetOfBooks: function(b) {
        var a = this;
        CNOA.msg.cf("确定删除？",
            function(c) {
                if (c == "yes") {
                    Ext.Ajax.request({
                        url: a.baseUrl + "&task=deleteSetOfBooks&id=" + b,
                        success: function(d) {
                            var e = Ext.decode(d.responseText);
                            CNOA.msg.notice2(e.msg);
                            a.setOfBooksStore.reload()
                        }
                    })
                }
            })
    },
    importSetOfBooks: function(c) {
        var a = this,
            b = "确定导入吗？导入会删除原来的组织机构、职位、岗位、用户及流程相关信息<br />建议先导出原来的数据后再导入，不然是无法恢复数据的";
        CNOA.msg.cf(b,
            function(e) {
                if (e == "yes") {
                    var d = new Ext.LoadMask(Ext.getBody(), {
                        msg: "正在重置系统，请稍后...",
                        removeMask: true
                    });
                    d.show();
                    Ext.Ajax.request({
                        url: a.baseUrl + "&task=resetSystem&id=" + c,
                        success: function(f) {
                            d.hide();
                            a.importStation(c)
                        }
                    })
                }
            })
    },
    importStation: function(c) {
        var b = this;
        var a = new Ext.LoadMask(Ext.getBody(), {
            msg: "正在导入岗位，请稍后...",
            removeMask: true
        });
        a.show();
        Ext.Ajax.request({
            url: b.baseUrl + "&task=importStation&id=" + c,
            success: function(d) {
                a.hide();
                b.importStruct(c)
            }
        })
    },
    importStruct: function(c) {
        var b = this;
        var a = new Ext.LoadMask(Ext.getBody(), {
            msg: "正在导入部门，请稍后...",
            removeMask: true
        });
        a.show();
        Ext.Ajax.request({
            url: b.baseUrl + "&task=importStruct&id=" + c,
            success: function(d) {
                a.hide();
                b.importJob(c)
            }
        })
    },
    importJob: function(c) {
        var b = this;
        var a = new Ext.LoadMask(Ext.getBody(), {
            msg: "正在导入职位，请稍后...",
            removeMask: true
        });
        a.show();
        Ext.Ajax.request({
            url: b.baseUrl + "&task=importJob&id=" + c,
            success: function(d) {
                a.hide();
                b.importUser(c)
            }
        })
    },
    importUser: function(c) {
        var b = this;
        var a = new Ext.LoadMask(Ext.getBody(), {
            msg: "正在导入用户，请稍后...",
            removeMask: true
        });
        a.show();
        Ext.Ajax.request({
            url: b.baseUrl + "&task=importUser&id=" + c,
            success: function(d) {
                a.hide();
                b.importJobPermit(c)
            }
        })
    },
    importJobPermit: function(c) {
        var b = this;
        var a = new Ext.LoadMask(Ext.getBody(), {
            msg: "正在导入职位权限，请稍后...",
            removeMask: true
        });
        a.show();
        Ext.Ajax.request({
            url: b.baseUrl + "&task=importJobPermit&id=" + c,
            success: function(d) {
                a.hide();
                b.importUserPermit(c)
            }
        })
    },
    importUserPermit: function(c) {
        var b = this;
        var a = new Ext.LoadMask(Ext.getBody(), {
            msg: "正在导入用户权限，请稍后...",
            removeMask: true
        });
        a.show();
        Ext.Ajax.request({
            url: b.baseUrl + "&task=importUserPermit&id=" + c,
            success: function(d) {
                a.hide();
                b.importFlow(c)
            }
        })
    },
    importFlow: function(c) {
        var b = this;
        var a = new Ext.LoadMask(Ext.getBody(), {
            msg: "正在导入流程，请稍后...",
            removeMask: true
        });
        a.show();
        Ext.Ajax.request({
            url: b.baseUrl + "&task=importFlow&id=" + c,
            success: function(d) {
                a.hide();
                var e = Ext.decode(d.responseText);
                CNOA.msg.notice2(e.msg)
            }
        })
    },
    resetSystem: function() {
        var a = this,
            b = "确定重置吗？重置会删除组织机构、职位、岗位、用户及流程相关信息<br />建议先导出原来的数据后再重置，不然是无法恢复数据的";
        CNOA.msg.cf(b,
            function(d) {
                if (d == "yes") {
                    var c = new Ext.LoadMask(Ext.getBody(), {
                        msg: "正在重置系统，请稍后...",
                        removeMask: true
                    });
                    c.show();
                    Ext.Ajax.request({
                        url: a.baseUrl + "&task=resetSystem",
                        success: function(e) {
                            c.hide();
                            var f = Ext.decode(e.responseText);
                            CNOA.msg.notice2(f.msg)
                        }
                    })
                }
            })
    },
    reloadUser: function() {
        this.permitStore.reload()
    },
    permitWin: function(b, f) {
        var c = this;
        var a = new Ext.form.FormPanel({
            border: false,
            fileUpload: true,
            bodyStyle: "padding:10px;",
            labelWidth: "80",
            waitMsgTarget: true,
            labelWidth: 110,
            items: [{
                xtype: "hidden",
                name: "deptIds"
            },
                {
                    xtype: "textarea",
                    width: 270,
                    height: 60,
                    fieldLabel: lang("selectDept"),
                    name: "dept",
                    readOnly: true,
                    listeners: {
                        afterrender: function(g) {
                            g.mon(g.el, "click",
                                function() {
                                    var h = a.getForm();
                                    var i = h.findField("deptIds").getValue();
                                    new_selector("dept", h.findField("dept"), h.findField("deptIds"), true, "index.php?action=commonJob&act=getSelectorData&target=dept", i)
                                })
                        }
                    }
                }]
        });
        var d = function() {
            if (a.getForm().isValid()) {
                a.getForm().submit({
                    url: c.baseUrl + "&task=saveUserAreaPermit",
                    waitMsg: lang("waiting"),
                    params: {
                        uid: b,
                        permitId: f
                    },
                    success: function(g, h) {
                        CNOA.msg.alert(h.result.msg,
                            function() {
                                e.close()
                            })
                    },
                    failure: function(g, h) {
                        CNOA.msg.alert(h.result.msg,
                            function() {})
                    }
                })
            }
        };
        var e = new Ext.Window({
            width: 460,
            height: 180,
            title: "设置权限范围",
            resizable: false,
            modal: true,
            layout: "fit",
            items: a,
            buttons: [{
                text: lang("save"),
                iconCls: "icon-btn-save",
                handler: d
            },
                {
                    text: lang("cancel"),
                    handler: function() {
                        e.close()
                    }.createDelegate(this)
                }]
        }).show();
        Ext.Ajax.request({
            url: c.baseUrl + "&task=getUserAreaPermit",
            params: {
                uid: b,
                permitId: f
            },
            success: function(h) {
                var g = Ext.decode(h.responseText);
                a.getForm().setValues(g.msg)
            }
        })
    }
};
var sm_main_system = 1;
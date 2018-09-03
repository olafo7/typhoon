var CNOA_my_infoClass, CNOA_my_info;
CNOA_my_infoClass = CNOA.Class.create();
CNOA_my_infoClass.prototype = {
    init: function() {
        var b = this;
        this.baseUrl = "welcome";
        this.ID_btn_save = Ext.id();
        this.ID_btn_apply = Ext.id();
        this.ID_btn_close = Ext.id();
        var a = Ext.id();
        this.baseField = new Ext.Panel({
            border: false,
            layout: "column",
            bodyStyle: "padding: 10px;",
            defaults: {
                width: 230
            },
            items: [{
                columnWidth: 0.79,
                border: false,
                items: [{
                    xtype: "fieldset",
                    title: lang("baseInfo"),
                    width: 470,
                    layout: "table",
                    defaults: {
                        border: false,
                        width: 226,
                        xtype: "textfield"
                    },
                    layoutConfig: {
                        columns: 2
                    },
                    items: [{
                        xtype: "panel",
                        layout: "form",
                        items: [{
                            xtype: "textfield",
                            name: "truename",
                            allowBlank: false,
                            fieldLabel: lang("truename"),
                            width: 150
                        }]
                    },
                        {
                            xtype: "panel",
                            layout: "form",
                            items: [new Ext.form.ComboBox({
                                fieldLabel: lang("sex"),
                                name: "sex",
                                store: new Ext.data.SimpleStore({
                                    fields: ["value", "sex"],
                                    data: [["1", lang("male")], ["2", lang("female")]]
                                }),
                                hiddenName: "sex",
                                valueField: "value",
                                displayField: "sex",
                                mode: "local",
                                width: 149,
                                allowBlank: false,
                                triggerAction: "all",
                                forceSelection: true,
                                editable: false,
                                value: lang("male")
                            })]
                        },
                        {
                            xtype: "panel",
                            layout: "form",
                            items: [{
                                xtype: "datefield",
                                name: "birthday",
                                width: 150,
                                fieldLabel: lang("birthday"),
                                format: "Y-m-d",
                                maxLength: 10,
                                editable: false,
                                allowBlank: false,
                                maxLengthText: lang("maxLengthText") + 10
                            }]
                        }]
                },
                    {
                        xtype: "fieldset",
                        title: lang("linkInfo"),
                        width: 470,
                        defaults: {
                            border: false
                        },
                        items: [{
                            xtype: "panel",
                            layout: "table",
                            border: false,
                            defaults: {
                                border: false,
                                width: 226,
                                xtype: "textfield"
                            },
                            layoutConfig: {
                                columns: 2
                            },
                            items: [{
                                xtype: "panel",
                                layout: "form",
                                items: [{
                                    xtype: "textfield",
                                    name: "mobile",
                                    fieldLabel: lang("mobile"),
                                    width: 150
                                }]
                            },
                                {
                                    xtype: "panel",
                                    layout: "form",
                                    items: [{
                                        xtype: "textfield",
                                        name: "workphone",
                                        fieldLabel: lang("workPhone"),
                                        width: 150
                                    }]
                                }]
                        },
                            {
                                xtype: "panel",
                                layout: "table",
                                border: false,
                                defaults: {
                                    border: false,
                                    width: 226
                                },
                                layoutConfig: {
                                    columns: 2
                                },
                                items: [{
                                    layout: "form",
                                    items: [{
                                        xtype: "textfield",
                                        name: "qq",
                                        fieldLabel: "QQ",
                                        width: 150
                                    }]
                                },
                                    {
                                        layout: "form",
                                        items: [{
                                            xtype: "textfield",
                                            vtype: "email",
                                            name: "email",
                                            fieldLabel: lang("email"),
                                            width: 150
                                        }]
                                    }]
                            },
                            {
                                xtype: "panel",
                                layout: "form",
                                width: 450,
                                items: [{
                                    xtype: "textfield",
                                    name: "address",
                                    fieldLabel: lang("nowAddress"),
                                    width: 376
                                }]
                            }]
                    }]
            },
                {
                    columnWidth: 0.21,
                    bodyStyle: "text-align:center;margin-top:7px;",
                    hideBorders: true,
                    border: false,
                    items: [{
                        html: '<div style="border:1px solid #CCC;width:134px;height:134px;padding:1px;display: table-cell;vertical-align: middle;"><img style="border: 0px;overflow:hidden;max-width: 130px; max-height: 130px;" src="./resources/images/default.jpg" id="CNOA_MY_INDEX_INFO_FACEBOX"></div>'
                    },
                        {
                            xtype: "button",
                            width: 128,
                            style: "margin-top: 20px;",
                            text: lang("editFace"),
                            handler: function() {
                                new Ext.EditpicWindow({
                                    width: 130,
                                    height: 130,
                                    orderAttr: "orderAttr",
                                    listeners: {
                                        comfirmpic: function(d, c) {
                                            $("#CNOA_MY_INDEX_INFO_FACEBOX").attr("src", c);
                                            Ext.getCmp(a).setValue(c)
                                        }
                                    }
                                })
                            }
                        },
                        {
                            xtype: "hidden",
                            name: "faceUrl",
                            id: a
                        }]
                }]
        });
        this.formPanel = new Ext.form.FormPanel({
            hideBorders: true,
            border: false,
            waitMsgTarget: true,
            labelWidth: 60,
            labelAlign: "right",
            items: [this.baseField]
        });
        this.mainPanel = new Ext.Window({
            width: 650,
            height: 330,
            autoDestroy: true,
            closeAction: "close",
            resizable: false,
            title: lang("editMyInfo"),
            layout: "fit",
            items: [this.formPanel],
            listeners: {
                close: function() {
                    Ext.destroy(CNOA_my_info);
                    CNOA_my_info = null;
                    try {
                        b.closeTab()
                    } catch(c) {}
                }
            },
            buttons: [{
                xtype: "cnoa_helpBtn",
                cls: "btn-transparent",
                helpid: 86
            },
                {
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
                    text: lang("cancel"),
                    id: this.ID_btn_close,
                    iconCls: "icon-dialog-cancel",
                    cls: "btn-red1",
                    handler: function() {
                        b.close()
                    }
                }]
        })
    },
    show: function() {
        this.mainPanel.show();
        this.loadForm()
    },
    close: function() {
        this.mainPanel.close()
    },
    closeTab: function() {
        mainPanel.closeTab(CNOA.my.index.info.parentID.replace("docs-", ""))
    },
    loadForm: function() {
        var a = this;
        this.formPanel.getForm().load({
            url: a.baseUrl + "/editLoadFormDataInfo",
            method: "POST",
            waitMsg: lang("waiting"),
            success: function(b, c) {
                if (c.result.data.face != "") {
                    Ext.fly("CNOA_MY_INDEX_INFO_FACEBOX").dom.src = c.result.data.face
                }
            }.createDelegate(this),
            failure: function(b, c) {
                CNOA.msg.alert(c.result.msg,
                    function() {
                        a.close()
                    })
            }.createDelegate(this)
        })
    },
    submitForm: function() {
        var a = this;
        if (this.formPanel.getForm().isValid()) {
            this.formPanel.getForm().submit({
                url: this.baseUrl + "&task=submitFormDataInfo",
                waitTitle: lang("notice"),
                method: "POST",
                waitMsg: lang("waiting"),
                params: {},
                success: function(b, c) {
                    CNOA.msg.alert(c.result.msg,
                        function() {
                            a.close()
                        })
                }.createDelegate(this),
                failure: function(b, c) {
                    CNOA.msg.alert(c.result.msg,
                        function() {})
                }.createDelegate(this)
            })
        } else {}
    },
    showUpdateFaceDialog: function() {
        var a = this;
        this.FACE_WINDOW_FORMPANEL = new Ext.FormPanel({
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
                blankText: lang("updateFaceBlankText"),
                buttonCfg: {
                    text: lang("browse")
                },
                hideLabel: true,
                width: 370,
                listeners: {
                    fileselected: function(c, b) {}
                }
            }],
            buttons: [{
                text: lang("save"),
                cls: "btn-blue4",
                handler: function() {
                    if (this.FACE_WINDOW_FORMPANEL.getForm().isValid()) {
                        this.FACE_WINDOW_FORMPANEL.getForm().submit({
                            url: a.baseUrl + "&task=upFace",
                            waitMsg: lang("waiting"),
                            params: {},
                            success: function(b, c) {
                                a.formPanel.getForm().findField("faceUrl").setValue(c.result.msg);
                                Ext.fly("CNOA_MY_INDEX_INFO_FACEBOX").dom.src = c.result.msg;
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
                    cls: "btn-red1",
                    handler: function() {
                        a.FACE_WINDOW.close()
                    }
                }]
        });
        this.FACE_WINDOW = new Ext.Window({
            width: 398,
            height: 123,
            autoScroll: false,
            modal: true,
            resizable: false,
            title: lang("upload") + lang("photo"),
            items: [this.FACE_WINDOW_FORMPANEL]
        }).show()
    }
};
var CNOA_my_setClass, CNOA_my_set;
CNOA_my_setClass = CNOA.Class.create();
CNOA_my_setClass.prototype = {
    init: function() {
        var d = this;
        this.baseUrl = "index.php?app=my&func=info&action=index";
        this.langStore = new Ext.data.Store({
            proxy: new Ext.data.HttpProxy({
                url: this.baseUrl + "&task=getLanguageList&type=combo",
                disableCaching: true
            }),
            reader: new Ext.data.JsonReader({
                totalProperty: "total",
                root: "data",
                fields: [{
                    name: "lang"
                },
                    {
                        name: "name"
                    }]
            }),
            listeners: {
                load: function(g, e, f) {
                    a()
                }
            }
        });
        this.langStore.load();
        this.langSelector = new Ext.form.ComboBox({
            store: this.langStore,
            valueField: "lang",
            displayField: "name",
            hiddenName: "language",
            mode: "local",
            allowBlank: false,
            width: 180,
            triggerAction: "all",
            forceSelection: true,
            editable: false,
            fieldLabel: lang("oaLanguage"),
            listeners: {
                select: function(g, e, f) {}
            }
        });
        var c = new Ext.form.FormPanel({
            bodyStyle: "padding:10px 10px 0",
            border: false,
            items: [{
                xtype: "fieldset",
                autoHeight: true,
                title: lang("mySetting"),
                labelWidth: 150,
                labelAlign: "right",
                bodyStyle: "padding:10px 10px 0",
                items: [{
                    xtype: "radiogroup",
                    fieldLabel: lang("logoutConfirm"),
                    width: 120,
                    items: [{
                        boxLabel: lang("yes"),
                        name: "exitalert",
                        inputValue: 1
                    },
                        {
                            boxLabel: lang("no"),
                            name: "exitalert",
                            inputValue: 0
                        }]
                },
                    {
                        text: lang("save"),
                        cls: "btn-blue4",
                        style: "margin-left:155px;",
                        xtype: "button",
                        iconCls: "icon-btn-save",
                        handler: function() {
                            b("exitalert")
                        }
                    }]
            },
                {
                    xtype: "fieldset",
                    autoHeight: true,
                    title: "个人首页上半布局设置",
                    labelWidth: 150,
                    labelAlign: "right",
                    bodyStyle: "padding:10px 10px 0",
                    items: [{
                        xtype: "radiogroup",
                        fieldLabel: "是否显示首页上半布局",
                        width: 120,
                        items: [{
                            boxLabel: lang("yes"),
                            name: "indexLayout",
                            inputValue: 1
                        },
                            {
                                boxLabel: lang("no"),
                                name: "indexLayout",
                                inputValue: 0
                            }]
                    },
                        {
                            text: lang("save"),
                            cls: "btn-blue4",
                            style: "margin-left:155px;",
                            xtype: "button",
                            iconCls: "icon-btn-save",
                            handler: function() {
                                b("indexLayout")
                            }
                        }]
                },
                {
                    xtype: "fieldset",
                    autoHeight: true,
                    title: lang("languageSet"),
                    labelWidth: 150,
                    labelAlign: "right",
                    bodyStyle: "padding:10px 10px 0",
                    items: [this.langSelector, {
                        xtype: "displayfield",
                        value: lang("refreshAfterSetLang")
                    },
                        {
                            text: lang("change"),
                            cls: "btn-blue4",
                            style: "margin-left:155px;",
                            xtype: "button",
                            iconCls: "icon-btn-save",
                            handler: function() {
                                b("lang")
                            }
                        }]
                },
                {
                    xtype: "fieldset",
                    autoHeight: true,
                    title: "工作流程快速审批设置",
                    labelWidth: 150,
                    labelAlign: "right",
                    bodyStyle: "padding:10px 10px 0",
                    items: [{
                        xtype: "radiogroup",
                        fieldLabel: "是否开启快速审批",
                        width: 120,
                        items: [{
                            boxLabel: lang("yes"),
                            name: "wfAutoNext",
                            inputValue: 1
                        },
                            {
                                boxLabel: lang("no"),
                                name: "wfAutoNext",
                                inputValue: 0,
                                checked: true
                            }]
                    },
                        {
                            xtype: "displayfield",
                            value: "开启快速审批功能后，工作流待办列表中的流程在办理过程中会自动打开下一步办理界面"
                        },
                        {
                            xtype: "radiogroup",
                            fieldLabel: "新窗口中打开",
                            width: 120,
                            items: [{
                                boxLabel: lang("yes"),
                                name: "wfNewWindow",
                                inputValue: 1
                            },
                                {
                                    boxLabel: lang("no"),
                                    name: "wfNewWindow",
                                    inputValue: 0,
                                    checked: true
                                }]
                        },
                        {
                            xtype: "displayfield",
                            value: "开启新窗口中打开能后，工作流待办列表中的流程在办理时会在新窗口中打开而不是固定在现有的标签页中(开启本功能后快速审批功能则无效)"
                        },
                        {
                            text: lang("save"),
                            cls: "btn-blue4",
                            style: "margin-left:155px;",
                            xtype: "button",
                            iconCls: "icon-btn-save",
                            handler: function() {
                                b("wfSet")
                            }
                        }]
                }]
        });
        this.mainPanel = new Ext.Panel({
            border: false,
            items: c,
            tbar: [lang("mySetting")]
        });
        var b = function(e) {
            var g = c.getForm();
            if (g.isValid()) {
                g.submit({
                    url: d.baseUrl + "&task=submitSetData",
                    waitTitle: lang("notice"),
                    method: "POST",
                    params: {
                        save: e
                    },
                    waitMsg: lang("waiting"),
                    success: function(f, h) {
                        if (e == "lang") {
                            CNOA.msg.cf(h.result.msg + " " + lang("whetherRefresh"),
                                function(i) {
                                    if (i == "yes") {
                                        location.reload()
                                    }
                                })
                        } else {
                            if (e == "wfSet") {
                                if (g.getValues().wfNewWindow == 1) {
                                    window.CNOA_USER_WFINNEWWINDOW = 1
                                } else {
                                    window.CNOA_USER_WFINNEWWINDOW = 0
                                }
                            } else {
                                CNOA.msg.notice2(h.result.msg)
                            }
                        }
                    }.createDelegate(this),
                    failure: function(f, h) {
                        CNOA.msg.alert(h.result.msg)
                    }.createDelegate(this)
                })
            } else {
                CNOA.miniMsg.alertShowAt(this.ID_btn_save, lang("formValid"))
            }
        };
        var a = function() {
            c.getForm().load({
                url: d.baseUrl + "&task=loadSetData",
                method: "POST",
                waitMsg: lang("waiting"),
                failure: function(e, f) {
                    CNOA.msg.alert(f.result.msg)
                }
            })
        }
    }
};
var CNOA_my_group, CNOA_my_groupClass;
CNOA_my_groupClass = CNOA.Class.create();
CNOA_my_groupClass.prototype = {
    init: function() {
        var a = this;
        this.baseUrl = "index.php?app=my&func=info&action=index";
        this.groupId = 0;
        this.groupName = "";
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
        this.groupModel = new Ext.grid.ColumnModel([new Ext.grid.RowNumberer(), {
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
var sm_my_myInfo = 1;
var CNOA_main_exsms_sendClass, CNOA_main_exsms_send;
CNOA_main_exsms_sendClass = CNOA.Class.create();
CNOA_main_exsms_sendClass.prototype = {
    init: function() {
        var a = this;
        this.baseUrl = "index.php?app=main&func=exsms&action=send";
        ID_exsms_length_status = Ext.id();
        this.treeRoot = new Ext.tree.AsyncTreeNode({
            text: lang("phoneAddBook"),
            expanded: true
        });
        this.treeLoader = new Ext.tree.TreeLoader({
            dataUrl: this.baseUrl + "&task=getLinkmanTree",
            preloadChildren: true,
            clearOnLoad: false,
            baseAttrs: {
                uiProvider: Ext.ux.TreeCheckNodeUI
            },
            listeners: {
                load: function(b) {
                    this.deptTree.expandAll();
                    this.deptTree.getEl().unmask()
                }.createDelegate(this)
            }
        });
        this.deptTree = new Ext.tree.TreePanel({
            hideBorders: true,
            border: false,
            rootVisible: true,
            lines: true,
            animCollapse: false,
            animate: false,
            loader: this.treeLoader,
            root: this.treeRoot,
            autoScroll: true,
            listeners: {
                check: function(c, b) {
                    a.makeLinkmanList()
                }
            }
        });
        this.deptPanel = new Ext.Panel({
            region: "east",
            layout: "fit",
            split: true,
            width: 320,
            minWidth: 80,
            maxWidth: 380,
            bodyStyle: "border-left-width:1px;",
            items: [this.deptTree],
            tbar: new Ext.Toolbar({
                style: "border-left-width:1px;",
                items: [{
                    handler: function(b, c) {
                        a.treeRoot.reload()
                    }.createDelegate(this),
                    iconCls: "icon-system-refresh",
                    tooltip: lang("refresh"),
                    text: lang("refresh")
                },
                    {
                        text: lang("expand"),
                        id: this.ID_btn_collapseExpand,
                        tooltip: lang("expandMenuTip"),
                        enableToggle: true,
                        toggleHandler: function(b, c) {
                            if (c) {
                                b.setText(lang("collapse"));
                                b.setTooltip(lang("collapseMenuTip"));
                                a.treeRoot.expand(true)
                            } else {
                                b.setText(lang("expand"));
                                b.setTooltip(lang("expandMenuTip"));
                                a.treeRoot.collapse(true);
                                a.treeRoot.firstChild.expand()
                            }
                        }
                    },
                    "->", {
                        xtype: "checkbox",
                        boxLabel: lang("selectAll"),
                        listeners: {
                            check: function(c, b) {
                                a.treeRoot.eachChild(function(d) {
                                    d.checked = b;
                                    d.getUI().checkbox.checked = b;
                                    d.eachChild(function(e) {
                                        e.checked = b;
                                        e.getUI().checkbox.checked = b
                                    })
                                });
                                a.makeLinkmanList()
                            }.createDelegate(this)
                        }
                    },
                    {
                        text: lang("mgrPhoneBook"),
                        cls: "btn-blue2",
                        handler: function() {
                            a.gotoLinkman()
                        }
                    }]
            })
        });
        this.centerPanel = new Ext.form.FormPanel({
            plain: false,
            region: "center",
            border: false,
            autoScroll: true,
            bodyStyle: "background-color:#F8F8F8;",
            labelWidth: 70,
            labelAlign: "right",
            bodyStyle: "border-right-width:1px;",
            waitMsgTarget: true,
            items: [{
                xtype: "panel",
                border: false,
                bodyStyle: "padding:10px",
                layout: "form",
                items: [{
                    xtype: "hidden",
                    name: "from",
                    value: "hand"
                },
                    {
                        xtype: "textarea",
                        height: 35,
                        anchor: "-20",
                        readOnly: true,
                        fieldLabel: lang("interRecipient"),
                        blankText: lang("pleaseSelectRecipient"),
                        name: "insideNames"
                    },
                    {
                        xtype: "hidden",
                        name: "insideUids"
                    },
                    {
                        xtype: "btnForPoepleSelector",
                        autoWidth: true,
                        anchor: "-20",
                        dataUrl: this.baseUrl + "&task=getAllUserListsInPermitDeptTree",
                        style: "margin-left: 75px; margin-bottom: 4px;",
                        text: lang("selectReceiveMan"),
                        listeners: {
                            selected: function(d, e) {
                                var f = new Array();
                                var c = new Array();
                                if (e.length > 0) {
                                    for (var b = 0; b < e.length; b++) {
                                        f.push(e[b].uname);
                                        c.push(e[b].uid)
                                    }
                                }
                                a.centerPanel.getForm().findField("insideNames").setValue(f.join(","));
                                a.centerPanel.getForm().findField("insideUids").setValue(c.join(","))
                            },
                            onrender: function(b) {
                                b.setSelectedUids(a.centerPanel.getForm().findField("insideUids").getValue().split(","))
                            }
                        }
                    },
                    {
                        xtype: "textarea",
                        anchor: "-20",
                        readOnly: true,
                        fieldLabel: lang("receiveMan"),
                        grow: true,
                        growMax: 400,
                        growMin: 35,
                        blankText: lang("pleaseSelectRecipient"),
                        name: "receiverNames"
                    },
                    {
                        xtype: "hidden",
                        name: "receiverLids"
                    },
                    {
                        xtype: "datetimefield",
                        width: 177,
                        minValue: new Date(),
                        fieldLabel: lang("posttime"),
                        name: "time"
                    },
                    {
                        xtype: "displayfield",
                        value: lang("emptyTimeIsSend")
                    },
                    {
                        xtype: "textarea",
                        anchor: "-20",
                        height: 160,
                        allowBlank: false,
                        enableKeyEvents: true,
                        fieldLabel: lang("smsContent"),
                        name: "text",
                        listeners: {
                            keyup: function(b, c) {
                                a.makeCount(b)
                            },
                            change: function(b, c) {
                                a.makeCount(b)
                            }
                        }
                    },
                    {
                        xtype: "displayfield",
                        id: ID_exsms_length_status,
                        value: lang("current0")
                    }]
            }],
            tbar: new Ext.Toolbar({
                style: "border-right-width:1px;",
                items: [{
                    handler: function(b, c) {
                        this.submit()
                    }.createDelegate(this),
                    iconCls: "icon-send-sms",
                    cls: "btn-green1",
                    text: lang("send")
                },
                    {
                        handler: function(b, c) {
                            this.centerPanel.getForm().reset()
                        }.createDelegate(this),
                        iconCls: "icon-system-reset",
                        cls: "btn-red1",
                        text: lang("reset")
                    },
                    {
                        text: lang("close"),
                        cls: "btn-gray1",
                        iconCls: "icon-bulk-delete",
                        handler: function() {
                            a.closeTab()
                        }.createDelegate(this)
                    },
                    "->", {
                        xtype: "cnoa_helpBtn",
                        helpid: 98
                    }]
            })
        });
        this.mainPanel = new Ext.Panel({
            collapsible: false,
            hideBorders: true,
            border: false,
            layout: "border",
            autoScroll: false,
            items: [this.centerPanel, this.deptPanel]
        })
    },
    makeCount: function(b) {
        var a = b.getValue().length;
        var c = b.getValue().Tlength();
        var e = Math.ceil(c / 140);
        Ext.getCmp(ID_exsms_length_status).setValue(lang("nowInput") + "<span class='cnoa_color_red'>" + a + "</span>" + lang("word"))
    },
    submit: function() {
        var d = this;
        var b = this.centerPanel.getForm();
        var a = b.findField("insideUids").getValue();
        var c = b.findField("receiverLids").getValue();
        if (Ext.isEmpty(a) && Ext.isEmpty(c)) {
            CNOA.msg.alert(lang("selectSelectToSend"));
            return
        }
        if (b.isValid()) {
            d.mainPanel.getEl().mask(lang("SMSsending"));
            Ext.Ajax.request({
                url: this.baseUrl + "&task=send",
                params: b.getValues(),
                method: "POST",
                timeout: 15000,
                success: function(g) {
                    try {
                        var f = Ext.decode(g.responseText);
                        if (f.success) {
                            CNOA.msg.alert(f.msg,
                                function() {
                                    b.reset();
                                    d.treeRoot.eachChild(function(e) {
                                        e.checked = false;
                                        e.getUI().checkbox.checked = false;
                                        e.eachChild(function(j) {
                                            j.checked = false;
                                            j.getUI().checkbox.checked = false
                                        })
                                    })
                                });
                            try {
                                CNOA_main_exsms_outboxList.store.reload()
                            } catch(i) {}
                        } else {
                            CNOA.msg.alert(lang("exceptionOccur") + f.msg)
                        }
                    } catch(i) {
                        CNOA.msg.alert(lang("requestException") + "：<br>" + f.msg)
                    }
                    d.mainPanel.getEl().unmask()
                }.createDelegate(this),
                failure: function(e, f) {
                    if (e.isTimeout) {
                        CNOA.msg.alert(lang("requestTimeOut"))
                    } else {
                        CNOA.msg.alert(lang("requsetException"))
                    }
                    d.mainPanel.getEl().unmask()
                }
            })
        }
    },
    closeTab: function() {
        mainPanel.closeTab(CNOA.main.exsms.send.parentID.replace("docs-", ""))
    },
    makeLinkmanList: function() {
        var c = this;
        var b = "";
        var a = "";
        c.treeRoot.eachChild(function(d) {
            d.eachChild(function(e) {
                if (e.getUI().checkbox.checked) {
                    b += e.attributes.lid + ",";
                    a += e.text + ", "
                }
            })
        });
        c.centerPanel.getForm().findField("receiverNames").setValue(a);
        c.centerPanel.getForm().findField("receiverLids").setValue(b)
    },
    gotoLinkman: function() {
        mainPanel.closeTab("CNOA_MENU_SYSTEM_EXSMS_LINKMAN");
        mainPanel.loadClass("index.php?app=main&func=exsms&action=send&task=loadPage&from=linkman", "CNOA_MENU_SYSTEM_EXSMS_LINKMAN", lang("phoneAddBook"), "icon-kontact-contacts")
    }
};
var CNOA_main_exsms_outboxListClass, CNOA_main_exsms_outboxList;
CNOA_main_exsms_outboxListClass = CNOA.Class.create();
CNOA_main_exsms_outboxListClass.prototype = {
    init: function() {
        var a = this;
        this.baseUrl = "index.php?app=main&func=exsms&action=smsmgr";
        this.search = {
            stime: 0,
            etime: 0
        };
        this.ID_Search_stime = Ext.id();
        this.ID_Search_etime = Ext.id();
        this.searchBar = new Ext.Toolbar({
            items: [(lang("posttime") + ":"), {
                xtype: "datefield",
                format: "Y-m-d",
                id: this.ID_Search_stime,
                width: 100
            },
                lang("to"), {
                    xtype: "datefield",
                    id: this.ID_Search_etime,
                    format: "Y-m-d",
                    width: 100
                },
                {
                    xtype: "button",
                    text: lang("search"),
                    style: "margin-left:5px",
                    handler: function() {
                        a.doSearch()
                    }
                },
                {
                    xtype: "button",
                    text: lang("clear"),
                    style: "margin-left:5px",
                    handler: function() {
                        a.resetSearch()
                    }
                }]
        });
        this.fields = [{
            name: "id"
        },
            {
                name: "text"
            },
            {
                name: "to"
            },
            {
                name: "sendtime"
            },
            {
                name: "posttime"
            },
            {
                name: "fromname"
            },
            {
                name: "statusText"
            }];
        this.store = new Ext.data.Store({
            proxy: new Ext.data.HttpProxy({
                url: this.baseUrl + "&task=getJsonDataOutbox"
            }),
            reader: new Ext.data.JsonReader({
                totalProperty: "total",
                root: "data",
                fields: this.fields
            }),
            listeners: {
                exception: function(g, f, i, e, d, c) {
                    var b = Ext.decode(d.responseText);
                    if (b.failure) {
                        CNOA.msg.alert(b.msg)
                    }
                }
            }
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
                header: lang("smsContent"),
                dataIndex: "text",
                id: "text",
                width: 300,
                sortable: true
            },
            {
                header: lang("receiveMan"),
                dataIndex: "to",
                width: 200,
                sortable: true
            },
            {
                header: lang("posttime"),
                dataIndex: "sendtime",
                width: 138,
                sortable: true
            },
            {
                header: lang("addTime"),
                dataIndex: "posttime",
                width: 138,
                sortable: true
            },
            {
                header: lang("sender"),
                dataIndex: "fromname",
                width: 98,
                menuDisabled: true
            },
            {
                header: lang("returnStatus"),
                dataIndex: "statusText",
                width: 100,
                menuDisabled: true
            },
            {
                header: lang("opt"),
                dataIndex: "",
                width: 60,
                renderer: this.makeOperate.createDelegate(this)
            }]);
        this.grid = new Ext.grid.GridPanel({
            stripeRows: true,
            store: this.store,
            loadMask: {
                msg: lang("waiting")
            },
            cm: this.colModel,
            sm: this.sm,
            autoExpandColumn: "text",
            hideBorders: true,
            autoWidth: true,
            hideBorders: true,
            border: false,
            viewConfig: {
                forceFit: true
            }
        });
        this.pagingBar = new Ext.PagingToolbar({
            displayInfo: true,
            store: this.store,
            pageSize: 15,
            listeners: {
                beforechange: function(b, c) {
                    if (a.search.stime != "") {
                        c.stime = a.search.stime
                    }
                    if (a.search.etime != "") {
                        c.etime = a.search.etime
                    }
                }
            }
        });
        this.centerPanel = new Ext.Panel({
            region: "center",
            layout: "fit",
            border: false,
            autoScroll: true,
            items: [this.grid],
            tbar: new Ext.Toolbar({
                items: [{
                    id: this.ID_btn_refreshList,
                    handler: function(b, c) {
                        a.store.reload()
                    }.createDelegate(this),
                    iconCls: "icon-system-refresh",
                    text: lang("refresh")
                },
                    {
                        id: this.ID_btn_delete,
                        text: lang("del"),
                        iconCls: "icon-utils-s-delete",
                        handler: function(b, c) {
                            var d = this.grid.getSelectionModel().getSelections();
                            if (d.length == 0) {
                                CNOA.miniMsg.alertShowAt(b, lang("mustSelectOneRow"))
                            } else {
                                CNOA.miniMsg.cfShowAt(b, lang("confirmToDelete"),
                                    function() {
                                        if (d) {
                                            var f = "";
                                            for (var e = 0; e < d.length; e++) {
                                                f += d[e].get("id") + ","
                                            }
                                            a.deleteList(f)
                                        }
                                    })
                            }
                        }.createDelegate(this)
                    },
                    "->", {
                        xtype: "cnoa_helpBtn",
                        helpid: 24
                    }]
            }),
            bbar: this.pagingBar
        });
        this.mainPanel = new Ext.Panel({
            collapsible: false,
            border: false,
            layout: "border",
            autoScroll: true,
            items: [this.centerPanel],
            tbar: this.searchBar
        })
    },
    makeOperate: function(f, d, a, g, b, e) {
        var c = a.data;
        h = "<a href='javascript:void(0);' class='gridview' onclick='CNOA_main_exsms_outboxList.view(" + c.id + ', "out");\'>' + lang("view") + "</a>";
        return h
    },
    doSearch: function() {
        this.search.stime = Ext.getCmp(this.ID_Search_stime).getRawValue();
        this.search.etime = Ext.getCmp(this.ID_Search_etime).getRawValue();
        this.store.load({
            params: {
                stime: this.search.stime,
                etime: this.search.etime
            }
        })
    },
    resetSearch: function() {
        Ext.getCmp(this.ID_Search_stime).setValue("");
        Ext.getCmp(this.ID_Search_etime).setValue("");
        this.search = {
            stime: 0,
            etime: 0
        };
        this.store.load()
    },
    deleteList: function(a) {
        var b = this;
        Ext.Ajax.request({
            url: this.baseUrl + "&task=deleteOutbox",
            method: "POST",
            params: {
                ids: a
            },
            success: function(d) {
                var c = Ext.decode(d.responseText);
                if (c.success === true) {
                    CNOA.msg.alert(c.msg,
                        function() {
                            b.store.reload()
                        })
                } else {
                    CNOA.msg.alert(c.msg)
                }
            }
        })
    },
    view: function(b, a) {
        mainPanel.closeTab("CNOA_MENU_MAIN_exsms_VIEW");
        mainPanel.loadClass(this.baseUrl + "&task=loadPage&from=smsview&id=" + b, "CNOA_MENU_MAIN_exsms_VIEW", lang("viewSMS"), "icon-page-view")
    }
};
var CNOA_main_exsms_settingClass, CNOA_main_exsms_setting;
CNOA_main_exsms_settingClass = CNOA.Class.create();
CNOA_main_exsms_settingClass.prototype = {
    init: function() {
        var a = this;
        this.baseUrl = "index.php?app=main&func=exsms&action=setting";
        this.fields = [{
            name: "id",
            mapping: "id"
        },
            {
                name: "code",
                mapping: "code"
            },
            {
                name: "name",
                mapping: "name"
            }];
        this.store = new Ext.data.Store({
            autoLoad: true,
            proxy: new Ext.data.HttpProxy({
                url: this.baseUrl + "&task=getStatusList"
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
            sortable: true,
            hidden: true
        },
            {
                header: lang("returnCode"),
                width: 100,
                editor: new Ext.form.TextField({
                    allowBlank: false
                }),
                sortable: true,
                dataIndex: "code"
            },
            {
                header: lang("codeDescription"),
                width: 440,
                editor: new Ext.form.TextField({
                    allowBlank: false
                }),
                sortable: true,
                dataIndex: "name"
            }]);
        this.grid = new Ext.grid.EditorGridPanel({
            store: this.store,
            sm: this.sm,
            loadMask: {
                msg: lang("waiting")
            },
            height: 258,
            collapsible: true,
            animCollapse: true,
            allowDomMove: true,
            colModel: this.colum,
            viewConfig: {
                forceFit: true
            },
            listeners: {
                afteredit: function(d) {
                    var f = d.record;
                    var b = d.field;
                    var g = f.get("id");
                    var e = f.get("code");
                    var c = f.get("name");
                    Ext.Ajax.request({
                        url: this.baseUrl + "&task=updateStatus",
                        params: "id=" + g + "&code=" + e + "&name=" + encodeURIComponent(c),
                        success: function(j) {
                            var i = Ext.decode(j.responseText);
                            if (i.success === true) {
                                a.store.reload()
                            } else {
                                CNOA.msg.alert(i.msg)
                            }
                        }
                    })
                }.createDelegate(this)
            },
            tbar: new Ext.Toolbar({
                items: [{
                    id: this.ID_btn_refreshList,
                    handler: function(b, c) {
                        this.store.reload()
                    }.createDelegate(this),
                    iconCls: "icon-system-refresh",
                    tooltip: lang("refresh"),
                    text: lang("refresh")
                },
                    {
                        text: lang("addStatus"),
                        iconCls: "icon-utils-s-add",
                        cls: "btn-blue4",
                        handler: function() {
                            var b = this.grid.getStore().recordType;
                            var c = new b({
                                name: ""
                            });
                            this.grid.stopEditing();
                            this.store.insert(0, c);
                            this.grid.startEditing(0, 3)
                        }.createDelegate(this)
                    },
                    {
                        text: lang("del"),
                        iconCls: "icon-utils-s-delete",
                        handler: function(b, c) {
                            var d = this.grid.getSelectionModel().getSelections();
                            if (d.length == 0) {
                                CNOA.miniMsg.alertShowAt(b, lang("mustSelectOneRow"))
                            } else {
                                CNOA.msg.cf(lang("confirmToDelete"),
                                    function(e) {
                                        if (e == "yes") {
                                            if (d) {
                                                var f = "";
                                                f += d[0].get("id");
                                                a.deleteStatusList(f)
                                            }
                                        }
                                    })
                            }
                        }.createDelegate(this)
                    },
                    lang("makeSureThat")]
            })
        });
        this.formPanel = new Ext.form.FormPanel({
            border: false,
            waitMsgTarget: true,
            labelWidth: 90,
            labelAlign: "right",
            autoScroll: true,
            items: [{
                xtype: "panel",
                border: false,
                layout: "form",
                bodyStyle: "padding:10px",
                items: [{
                    xtype: "fieldset",
                    title: lang("SMSinformation"),
                    items: [{
                        xtype: "displayfield",
                        name: "msgoutcount",
                        fieldLabel: lang("beenTextingNum")
                    }]
                },
                    {
                        xtype: "fieldset",
                        title: lang("jieKouSet"),
                        items: [{
                            xtype: "textarea",
                            name: "api_send_url",
                            fieldLabel: lang("SMSinterface"),
                            anchor: "-20"
                        },
                            {
                                xtype: "displayfield",
                                style: "margin-bottom:13px",
                                value: lang("interfaceDescription") + "：<br>1. " + lang("createTime") + ": @@MKTIME@@<br>2. " + lang("posttime") + ": @@SENDTIME@@<br>3. " + lang("numList") + ": @@MOBILES@@<br>4. " + lang("smsContent") + ": @@TEXT@@"
                            },
                            {
                                xtype: "textarea",
                                name: "api_balance_url",
                                fieldLabel: lang("yuErInterface"),
                                anchor: "-20"
                            },
                            {
                                xtype: "textfield",
                                name: "balanceregex",
                                fieldLabel: lang("interfaceRules"),
                                anchor: "-20"
                            },
                            {
                                xtype: "textfield",
                                name: "timeformat",
                                fieldLabel: lang("timeFormat"),
                                anchor: "-20"
                            },
                            {
                                xtype: "displayfield",
                                style: "margin-bottom:13px",
                                value: lang("referPHPTime")
                            },
                            {
                                xtype: "textfield",
                                name: "mobilesplite",
                                fieldLabel: lang("numDelimiter"),
                                anchor: "-20"
                            },
                            {
                                xtype: "textfield",
                                name: "charset",
                                fieldLabel: lang("charEncoding"),
                                anchor: "-20"
                            },
                            {
                                xtype: "textfield",
                                name: "callbackregex",
                                fieldLabel: lang("returnStatusRules"),
                                anchor: "-20"
                            },
                            {
                                xtype: "displayfield",
                                value: "<span class='cnoa_color_red'>" + lang("doNotEditSet") + "</span>"
                            }]
                    }]
            }],
            listeners: {
                render: function() {
                    a.getSettingInfo()
                }
            }
        });
        this.panel = new Ext.Panel({
            border: false,
            autoScroll: true,
            items: [this.formPanel, new Ext.Panel({
                border: false,
                bodyStyle: "padding:10px",
                items: [{
                    xtype: "fieldset",
                    title: lang("returnStatusSet"),
                    items: [this.grid]
                }]
            })]
        });
        this.mainPanel = new Ext.Panel({
            collapsible: false,
            hideBorders: true,
            border: false,
            layout: "fit",
            autoScroll: false,
            items: [this.panel],
            tbar: new Ext.Toolbar({
                items: [{
                    iconCls: "icon-btn-save",
                    cls: "btn-blue4",
                    text: lang("save"),
                    handler: function(b, c) {
                        a.submit()
                    }.createDelegate(this)
                },
                    {
                        iconCls: "icon-balanceInquiry",
                        cls: "btn-yellow1",
                        text: lang("balanceInquiry"),
                        handler: function(b, c) {
                            a.getBalance()
                        }.createDelegate(this)
                    },
                    "->", {
                        xtype: "cnoa_helpBtn",
                        helpid: 25
                    }]
            })
        })
    },
    getSettingInfo: function() {
        var a = this;
        this.formPanel.getForm().load({
            url: a.baseUrl + "&task=getSetting",
            params: {},
            method: "POST",
            waitMsg: lang("waiting"),
            success: function(b, c) {}.createDelegate(this),
            failure: function(b, c) {
                CNOA.msg.alert(c.result.msg,
                    function() {})
            }.createDelegate(this)
        })
    },
    submit: function() {
        var a = this;
        if (this.formPanel.getForm().isValid()) {
            this.formPanel.getForm().submit({
                url: this.baseUrl + "&task=submit",
                waitTitle: lang("notice"),
                method: "POST",
                waitMsg: lang("waiting"),
                params: {},
                success: function(b, c) {
                    CNOA.msg.alert(c.result.msg,
                        function() {})
                }.createDelegate(this),
                failure: function(b, c) {
                    CNOA.msg.alert(c.result.msg)
                }.createDelegate(this)
            })
        }
    },
    getBalance: function() {
        var a = this;
        if (this.formPanel.getForm().isValid()) {
            this.formPanel.getForm().submit({
                url: this.baseUrl + "&task=getBalance",
                waitTitle: lang("notice"),
                method: "POST",
                waitMsg: lang("waiting"),
                params: {},
                success: function(b, c) {
                    CNOA.msg.alert(c.result.msg,
                        function() {})
                }.createDelegate(this),
                failure: function(b, c) {
                    CNOA.msg.alert(c.result.msg)
                }.createDelegate(this)
            })
        }
    },
    deleteStatusList: function(a) {
        var b = this;
        Ext.Ajax.request({
            url: this.baseUrl + "&task=deleteStatus",
            method: "POST",
            params: {
                ids: a
            },
            success: function(d) {
                var c = Ext.decode(d.responseText);
                if (c.success === true) {
                    CNOA.msg.alert(c.msg,
                        function() {
                            b.store.reload()
                        })
                } else {
                    CNOA.msg.alert(c.msg)
                }
            }
        })
    }
};
var CNOA_main_exsms_viewClass, CNOA_main_exsms_view;
CNOA_main_exsms_viewClass = CNOA.Class.create();
CNOA_main_exsms_viewClass.prototype = {
    init: function() {
        var a = this;
        this.type = CNOA.main.exsms.view.type;
        this.id = CNOA.main.exsms.view.id;
        this.baseUrl = "index.php?app=main&func=exsms&action=smsmgr";
        this.Panel = new Ext.Panel({
            border: false,
            bodyStyle: "padding:10px",
            labelAlign: "right",
            labelWidth: "100",
            autoScroll: true,
            listeners: {
                render: function() {
                    a.getviewInfo()
                }
            }
        });
        this.mainPanel = new Ext.Panel({
            collapsible: false,
            hideBorders: true,
            border: false,
            layout: "fit",
            autoScroll: false,
            items: [this.Panel],
            tbar: new Ext.Toolbar({
                items: [{
                    id: this.ID_btn_refreshList,
                    handler: function(b, c) {
                        a.getviewInfo()
                    }.createDelegate(this),
                    iconCls: "icon-system-refresh",
                    text: lang("refresh")
                },
                    {
                        text: lang("close"),
                        iconCls: "icon-dialog-cancel",
                        handler: function() {
                            a.closeTab()
                        }.createDelegate(this)
                    },
                    "->", {
                        xtype: "cnoa_helpBtn",
                        helpid: 26
                    }]
            })
        })
    },
    closeTab: function() {
        mainPanel.closeTab(CNOA.main.exsms.view.parentID.replace("docs-", ""))
    },
    getviewInfo: function() {
        var a = this;
        a.mainPanel.getEl().mask(lang("waiting"));
        Ext.Ajax.request({
            url: this.baseUrl + "&task=view",
            method: "POST",
            disableCaching: true,
            params: {
                type: this.type,
                id: this.id
            },
            success: function(c) {
                a.mainPanel.getEl().unmask();
                var b = Ext.decode(c.responseText);
                if (b.success === true) {
                    a.makeContents(b.data)
                } else {
                    CNOA.msg.alert(b.msg)
                }
            }
        })
    },
    makeContents: function(b) {
        var c = this;
        var a = new Array();
        a.push(c.makeOutView(b));
        this.Panel.removeAll();
        this.Panel.add(a);
        this.Panel.doLayout()
    },
    makeOutView: function(a) {
        var c = this;
        var b = new Ext.Panel({
            title: lang("msgType"),
            style: "margin-bottom:10px",
            bodyStyle: "padding:10px",
            frame: true,
            layout: "form",
            items: [{
                xtype: "displayfield",
                fieldLabel: lang("sender"),
                value: a.fromname
            },
                {
                    xtype: "displayfield",
                    fieldLabel: lang("receiveMan"),
                    value: a.to
                },
                {
                    xtype: "displayfield",
                    fieldLabel: lang("posttime"),
                    value: a.sendtime
                },
                {
                    xtype: "displayfield",
                    fieldLabel: lang("addTime"),
                    value: a.posttime
                },
                {
                    xtype: "displayfield",
                    fieldLabel: lang("smsContent"),
                    value: a.text
                }]
        });
        return b
    }
};
CNOA_main_exsms_linkmanSortClass = CNOA.Class.create();
CNOA_main_exsms_linkmanSortClass.prototype = {
    init: function(a) {
        var b = this;
        this.baseUrl = "index.php?app=main&func=exsms&action=send";
        this.tp = a;
        this.title = lang("sortSet");
        this.action = a == "edit" ? "edit": "add";
        this.edit_id = 0;
        this.ID_btn_delete = Ext.id();
        this.ID_btn_edit = Ext.id();
        this.ID_groupComboBox = Ext.id();
        this.fields = [{
            name: "id",
            mapping: "id"
        },
            {
                name: "name",
                mapping: "name"
            }];
        this.store = new Ext.data.Store({
            autoLoad: true,
            proxy: new Ext.data.HttpProxy({
                url: this.baseUrl + "&task=getGroupList"
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
            sortable: true,
            hidden: true
        },
            {
                header: lang("sortName"),
                width: 440,
                editor: new Ext.form.TextField({
                    allowBlank: false
                }),
                sortable: true,
                dataIndex: "name"
            }]);
        this.grid = new Ext.grid.EditorGridPanel({
            store: this.store,
            sm: this.sm,
            hideBorders: true,
            border: false,
            height: 258,
            collapsible: true,
            animCollapse: true,
            allowDomMove: true,
            colModel: this.colum,
            viewConfig: {
                forceFit: true
            },
            clicksToEdit: 1,
            listeners: {
                afteredit: function(e) {
                    var f = e.record;
                    var c = e.field;
                    var g = f.get("id");
                    var d = f.get("name");
                    Ext.Ajax.request({
                        url: this.baseUrl + "&task=updateGroup",
                        params: "id=" + g + "&name=" + encodeURIComponent(d),
                        success: function(j) {
                            var i = Ext.decode(j.responseText);
                            if (i.success === true) {} else {
                                CNOA.msg.alert(i.msg,
                                    function() {
                                        b.store.reload()
                                    })
                            }
                        }
                    })
                }.createDelegate(this)
            }
        });
        this.mainPanel = new Ext.Window({
            width: 500,
            height: 360,
            resizable: false,
            autoDistory: true,
            modal: true,
            maskDisabled: true,
            items: [this.grid],
            plain: true,
            title: this.title,
            buttonAlign: "right",
            autoScroll: true,
            tbar: [{
                text: lang("addSort"),
                iconCls: "icon-utils-s-add",
                handler: function() {
                    var c = this.grid.getStore().recordType;
                    var d = new c({
                        name: ""
                    });
                    this.grid.stopEditing();
                    this.store.insert(0, d);
                    this.grid.startEditing(0, 3)
                }.createDelegate(this)
            },
                {
                    text: lang("del"),
                    iconCls: "icon-utils-s-delete",
                    id: this.ID_btn_delete,
                    handler: function(c, d) {
                        var e = this.grid.getSelectionModel().getSelections();
                        if (e.length == 0) {
                            CNOA.miniMsg.alertShowAt(c, lang("mustSelectOneRow"))
                        } else {
                            CNOA.msg.cf(lang("delLinkSortSure"),
                                function(f) {
                                    if (f == "yes") {
                                        if (e) {
                                            var g = "";
                                            g += e[0].get("id");
                                            b.deleteList(g)
                                        }
                                    }
                                })
                        }
                    }.createDelegate(this)
                }],
            buttons: [{
                text: lang("close"),
                iconCls: "icon-dialog-cancel",
                handler: function() {
                    b.close();
                    b.store.reload();
                    CNOA_main_exsms_linkman.sortStore.reload();
                    CNOA_main_exsms_linkman.store.reload()
                }
            }]
        })
    },
    show: function(b) {
        var a = this;
        a.mainPanel.show()
    },
    close: function() {
        this.mainPanel.close()
    },
    deleteList: function(a) {
        var b = this;
        Ext.Ajax.request({
            url: this.baseUrl + "&task=deleteGroup",
            method: "POST",
            params: {
                ids: a
            },
            success: function(d) {
                var c = Ext.decode(d.responseText);
                if (c.success === true) {
                    b.store.reload()
                } else {
                    CNOA.msg.alert(c.msg)
                }
            }
        })
    }
};
CNOA_main_exsms_linkmanClass = CNOA.Class.create();
CNOA_main_exsms_linkmanClass.prototype = {
    init: function() {
        var d = this;
        var c = Ext.id();
        var b = Ext.id();
        var a = Ext.id();
        this.baseUrl = "index.php?app=main&func=exsms&action=send";
        this.storeBar = {
            name: "",
            type: 0,
            mobile: 0
        };
        this.sortStore = new Ext.data.Store({
            proxy: new Ext.data.HttpProxy({
                url: this.baseUrl + "&task=getSortList",
                method: "POST"
            }),
            reader: new Ext.data.JsonReader({
                root: "data",
                fields: [{
                    name: "id",
                    mapping: "id"
                },
                    {
                        name: "name",
                        mapping: "name"
                    }]
            })
        });
        this.sortStore.load();
        this.sortCombo = new Ext.form.ComboBox({
            autoLoad: true,
            triggerAction: "all",
            selectOnFocus: true,
            editable: false,
            store: this.sortStore,
            valueField: "id",
            displayField: "name",
            allowBlank: false,
            mode: "local",
            forceSelection: true
        });
        this.dsc = Ext.data.Record.create([{
            name: "mobile",
            type: "string"
        },
            {
                name: "name",
                type: "string"
            },
            {
                name: "sid",
                type: "string"
            }]);
        this.fields = [{
            name: "id",
            mapping: "id"
        },
            {
                name: "sid",
                mapping: "sid"
            },
            {
                name: "mobile",
                mapping: "mobile"
            },
            {
                name: "name",
                mapping: "name"
            }];
        this.store = new Ext.data.GroupingStore({
            autoLoad: true,
            proxy: new Ext.data.HttpProxy({
                url: this.baseUrl + "&task=getLinkmanList"
            }),
            reader: new Ext.data.JsonReader({
                totalProperty: "total",
                root: "data",
                fields: this.fields
            }),
            listeners: {
                update: function(i, e, g) {
                    var f = e.data;
                    if (g == Ext.data.Record.EDIT) {
                        d.submit(f)
                    }
                }
            },
            sortInfo: {
                field: "sid",
                direction: "DESC"
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
                header: "",
                dataIndex: "sid",
                hidden: true
            },
            {
                id: "mobile",
                header: lang("phoneNumber"),
                dataIndex: "mobile",
                width: 200,
                sortable: true,
                editor: {
                    xtype: "textfield",
                    vtype: "mobile",
                    vtypeText: lang("wrongMobile"),
                    allowBlank: false
                }
            },
            {
                header: lang("truename"),
                dataIndex: "name",
                width: 200,
                sortable: true,
                editor: {
                    xtype: "textfield",
                    allowBlank: false
                }
            },
            {
                id: "sid",
                header: lang("sort2"),
                dataIndex: "sid",
                width: 200,
                sortable: true,
                editor: this.sortCombo,
                renderer: function(e) {
                    var f;
                    d.sortStore.each(function(g) {
                        if (g.get("id") == e) {
                            f = g;
                            return
                        }
                    });
                    return f == null ? e: f.get("name")
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
            autoExpandColumn: "sid",
            plugins: [this.editor],
            columns: this.cm,
            view: new Ext.grid.GroupingView({
                markDirty: false
            }),
            tbar: [{
                handler: function(e, f) {
                    d.store.reload()
                }.createDelegate(this),
                iconCls: "icon-system-refresh",
                text: lang("refresh")
            },
                {
                    iconCls: "icon-utils-s-add",
                    text: lang("add"),
                    handler: function() {
                        var f = new d.dsc({
                            mobile: "",
                            sid: "",
                            name: ""
                        });
                        d.editor.stopEditing();
                        d.store.insert(0, f);
                        d.grid.getView().refresh();
                        d.grid.getSelectionModel().selectRow(0);
                        d.editor.startEditing(0)
                    }
                },
                {
                    iconCls: "icon-utils-s-delete",
                    text: lang("del"),
                    handler: function(e) {
                        d.editor.stopEditing();
                        var f = d.grid.getSelectionModel().getSelections();
                        if (f.length == 0) {
                            CNOA.miniMsg.alertShowAt(e, lang("mustSelectOneRow"))
                        } else {
                            CNOA.msg.cf(lang("confirmToDelete"),
                                function(j) {
                                    if (j == "yes") {
                                        if (f) {
                                            var k = "";
                                            for (var g = 0; g < f.length; g++) {
                                                k += f[g].get("id") + ",";
                                                var l = f[g];
                                                d.store.remove(l)
                                            }
                                            d.deleteList(k)
                                        }
                                    }
                                })
                        }
                    }
                },
                {
                    handler: function(e, f) {
                        d.showImportWindow()
                    }.createDelegate(this),
                    iconCls: "document-excel-import",
                    text: lang("importLinkman")
                },
                ("<span style='color:#676767'>" + lang("sortSet") + "：</span>"), {
                    text: lang("sortMgr"),
                    iconCls: "icon-cnoa-communication",
                    handler: function(e, f) {
                        CNOA_main_exsms_linkmanSort = new CNOA_main_exsms_linkmanSortClass();
                        CNOA_main_exsms_linkmanSort.show()
                    }.createDelegate(this)
                },
                (lang("sort") + ":"), {
                    xtype: "panel",
                    border: false,
                    items: [new CNOA.form.ComboBox({
                        autoLoad: true,
                        triggerAction: "all",
                        selectOnFocus: true,
                        editable: false,
                        width: 100,
                        store: d.sortStore,
                        id: c,
                        valueField: "id",
                        displayField: "name",
                        mode: "local",
                        forceSelection: true
                    })]
                },
                lang("truename"), {
                    xtype: "cnoa_textfield",
                    width: 100,
                    id: b
                },
                lang("phoneNumber"), {
                    xtype: "cnoa_textfield",
                    width: 100,
                    id: a
                },
                {
                    text: lang("search"),
                    iconCls: "icon-hr-search",
                    handler: function() {
                        d.storeBar.type = Ext.getCmp(c).getValue();
                        d.storeBar.name = Ext.getCmp(b).getValue();
                        d.storeBar.mobile = Ext.getCmp(a).getValue();
                        d.store.load({
                            params: d.storeBar
                        })
                    }
                },
                {
                    text: lang("clear"),
                    handler: function() {
                        Ext.getCmp(c).setValue("");
                        Ext.getCmp(b).setValue("");
                        Ext.getCmp(a).setValue("");
                        d.storeBar.name = "";
                        d.storeBar.mobile = "";
                        d.storeBar.type = 0;
                        d.store.load({
                            params: d.storeBar
                        })
                    }
                },
                ("<span class='cnoa_color_gray'>" + lang("dblclickToEdit") + "</span>"), "->", {
                    xtype: "cnoa_helpBtn",
                    helpid: 99
                }]
        });
        this.mainPanel = new Ext.Panel({
            collapsible: false,
            hideBorders: true,
            border: false,
            layout: "border",
            autoScroll: false,
            items: [this.grid]
        })
    },
    submit: function(a) {
        var b = this;
        Ext.Ajax.request({
            url: this.baseUrl + "&task=submitLinkman",
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
            url: this.baseUrl + "&task=deleteLinkman",
            method: "POST",
            params: {
                ids: a
            },
            success: function(d) {
                var c = Ext.decode(d.responseText);
                if (c.success === true) {
                    b.store.reload()
                } else {
                    CNOA.msg.alert(c.msg)
                }
            }
        })
    },
    showImportWindow: function() {
        var a = this;
        this.UPLOAD_WINDOW_FORMPANEL = new Ext.FormPanel({
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
                buttonCfg: {
                    text: lang("browserLinkmanExcel")
                },
                hideLabel: true,
                width: 370,
                listeners: {
                    fileselected: function(c, b) {}
                }
            },
                {
                    xtype: "displayfield",
                    hideLabel: true,
                    value: lang("must0307Excel")
                }],
            buttons: [{
                text: lang("import2"),
                iconCls: "document-excel-import",
                handler: function() {
                    if (this.UPLOAD_WINDOW_FORMPANEL.getForm().isValid()) {
                        this.UPLOAD_WINDOW_FORMPANEL.getForm().submit({
                            url: a.baseUrl + "&task=import_linkman_upload",
                            waitMsg: lang("waiting"),
                            params: {},
                            success: function(b, c) {
                                a.showImportToDbWindow(c.result.msg);
                                a.UPLOAD_WINDOW.close()
                            },
                            failure: function(b, c) {
                                CNOA.msg.alert(c.result.msg,
                                    function() {})
                            }
                        })
                    }
                }.createDelegate(this)
            },
                {
                    text: lang("close"),
                    handler: function() {
                        a.UPLOAD_WINDOW.close()
                    }
                }]
        });
        this.UPLOAD_WINDOW = new Ext.Window({
            width: 398,
            height: 163,
            autoScroll: false,
            modal: true,
            resizable: false,
            title: lang("importLinkman"),
            items: [this.UPLOAD_WINDOW_FORMPANEL]
        }).show()
    },
    showImportToDbWindow: function(e) {
        var f = this;
        var d = Ext.getBody().getBox();
        var b = d.width - 100;
        var c = d.height - 100;
        var a = Ext.id();
        f.ImportToDbWindow = new Ext.Window({
            title: lang("setLinkImportAttr"),
            width: b,
            height: c,
            layout: "fit",
            modal: true,
            maximizable: true,
            resizable: true,
            html: '<iframe scrolling="auto" frameborder="0" id="' + a + '" width="100%" height="100%" src="' + f.baseUrl + "&task=import_linkman_todb&from=" + e + '"></iframe>',
            buttons: [{
                text: lang("save"),
                iconCls: "icon-order-s-accept",
                handler: function() {
                    Ext.getDom(a).contentWindow.submit()
                }
            },
                {
                    text: lang("cancel"),
                    iconCls: "icon-dialog-cancel",
                    handler: function() {
                        f.ImportToDbWindow.close()
                    }
                }],
            listeners: {
                close: function() {
                    try {
                        Ext.getDom(a).src = ""
                    } catch(g) {}
                }
            }
        }).show()
    },
    closeImportToDbWindow: function() {
        this.ImportToDbWindow.close()
    }
};
var sm_main_exsms = 1;
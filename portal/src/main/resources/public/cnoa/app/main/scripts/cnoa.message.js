var CNOA_main_message_listClass, CNOA_main_message_list;
CNOA_main_message_listClass = CNOA.Class.create();
CNOA_main_message_listClass.prototype = {
    init: function() {
        var a = this;
        this.edit_id = 0;
        this.action = "";
        this.baseUrl = "main/message";
        this.ID_btn_add = Ext.id();
        this.ID_btn_edit = Ext.id();
        this.ID_btn_refreshList = Ext.id();
        this.ID_btn_delete = Ext.id();
        this.tip_noPermission = lang("noPermitToUseFeature");
        this.fields = [{
            name: "id"
        },
            {
                name: "attach"
            },
            {
                name: "title"
            },
            {
                name: "content"
            },
            {
                name: "inUse"
            },
            {
                name: "isexpire"
            },
            {
                name: "start"
            },
            {
                name: "end"
            },
            {
                name: "author"
            }];
        this.store = new Ext.data.Store({
            proxy: new Ext.data.HttpProxy({
                url: this.baseUrl+"/getJsonData"
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
        this.sm = new Ext.grid.CheckboxSelectionModel({
            singleSelect: false
        });
        this.colModel = new Ext.grid.ColumnModel([new Ext.grid.RowNumberer(), this.sm, {
            header: "id",
            dataIndex: "id",
            hidden: true
        },
            {
                header: '<img src="/cnoa/resources/images/icons/attach.gif" width="13" height="13" />',
                dataIndex: "attach",
                width: 34,
                sortable: true,
                menuDisabled: true,
                renderer: this.attachCheck
            },
            {
                header: lang("posters"),
                dataIndex: "author",
                width: 80,
                sortable: false,
                menuDisabled: true
            },
            {
                header: lang("title"),
                dataIndex: "title",
                width: 140,
                sortable: false,
                menuDisabled: true
            },
            {
                header: lang("content"),
                dataIndex: "content",
                width: 320,
                sortable: false,
                menuDisabled: true
            },
            {
                header: lang("enable"),
                dataIndex: "inUse",
                width: 40,
                sortable: false,
                menuDisabled: true,
                renderer: this.inUseChecker.createDelegate(this)
            },
            {
                header: lang("expired"),
                dataIndex: "isexpire",
                width: 40,
                sortable: false,
                menuDisabled: true
            },
            {
                header: lang("startTime"),
                dataIndex: "start",
                width: 110,
                sortable: false,
                menuDisabled: true
            },
            {
                header: lang("endTime"),
                dataIndex: "end",
                width: 110,
                sortable: false,
                menuDisabled: true
            }]);
        this.grid = new Ext.grid.GridPanel({
            stripeRows: true,
            store: this.store,
            loadMask: {
                msg: lang("waiting")
            },
            cm: this.colModel,
            sm: this.sm,
            hideBorders: true,
            autoWidth: true,
            border: false,
            listeners: {
                cellclick: function(c, g, d, f) {
                    if ((d != 0) && (d != 1)) {
                        var b = c.getStore().getAt(g);
                        a.centerPanel.getLayout().setActiveItem(1);
                        a.previewMessage(b.data.id)
                    }
                }
            }
        });
        this.pagingBar = new Ext.PagingToolbar({
            displayInfo: true,
            store: this.store,
            pageSize: 15
        });
        this.smsListPanel = new Ext.Panel({
            title: lang("systemNotice"),
            layout: "fit",
            autoScroll: true,
            items: [this.grid],
            tbar: new Ext.Toolbar({
                items: [{
                    handler: function(b, c) {
                        a.store.reload()
                    }.createDelegate(this),
                    iconCls: "icon-system-refresh",
                    tooltip: lang("refresh"),
                    text: lang("refresh")
                },
                    {
                        iconCls: "icon-utils-s-add",
                        text: lang("add"),
                        handler: function(b, c) {
                            a.edit_id = 0;
                            a.action = "add";
                            a.centerPanel.getLayout().setActiveItem(2)
                        }.createDelegate(this)
                    },
                    {
                        id: this.ID_btn_edit,
                        handler: function(c, d) {
                            var e = this.grid.getSelectionModel().getSelections();
                            if (e.length == 0) {
                                var b = c.getEl().getBox();
                                b = [b.x + 12, b.y + 26];
                                CNOA.miniMsg.alert(lang("mustSelectOneRow"), b)
                            } else {
                                a.centerPanel.getLayout().setActiveItem(2);
                                a.loadMessage()
                            }
                        }.createDelegate(this),
                        iconCls: "icon-utils-s-edit",
                        text: lang("modify")
                    },
                    {
                        id: this.ID_btn_delete,
                        text: lang("del"),
                        iconCls: "icon-utils-s-delete",
                        handler: function(c, d) {
                            var e = this.grid.getSelectionModel().getSelections();
                            if (e.length == 0) {
                                var b = Ext.getCmp(this.ID_btn_delete).getEl().getBox();
                                b = [b.x + 12, b.y + 26];
                                CNOA.miniMsg.alert(lang("mustSelectOneRow"), b)
                            } else {
                                CNOA.msg.cf(lang("confirmToDelete"),
                                    function(g) {
                                        if (g == "yes") {
                                            if (e) {
                                                var h = "";
                                                for (var f = 0; f < e.length; f++) {
                                                    h += e[f].get("id") + ","
                                                }
                                                CNOA_main_message_list.deleteRecord(h)
                                            }
                                        }
                                    })
                            }
                        }.createDelegate(this)
                    },
                    "<span style='color:#999'>" + lang("dblclickToEdit") + "</span>", "->", {
                        xtype: "cnoa_helpBtn",
                        helpid: 28
                    }]
            }),
            bbar: this.pagingBar
        });
        this.smsPreviewPanel = new Ext.Panel({
            title: lang("viewMessage"),
            layout: "fit",
            autoScroll: true,
            tbar: new Ext.Toolbar({
                items: [{
                    handler: function(b, c) {
                        a.centerPanel.getLayout().setActiveItem(0)
                    }.createDelegate(this),
                    iconCls: "icon-tab-inbox",
                    text: "<<" + lang("backToList")
                },
                    {
                        id: a.ID_btn_reply,
                        handler: function(b, c) {
                            a.centerPanel.getLayout().setActiveItem(2);
                            a.loadMessage()
                        }.createDelegate(this),
                        iconCls: "icon-btn-sms-reply",
                        text: lang("modify")
                    }]
            })
        });
        this.smsEditPanel = new Ext.form.FormPanel({
            autoScroll: true,
            bodyStyle: "padding: 10px;background-color:#F8F8F8;",
            labelWidth: 60,
            labelAlign: "right",
            waitMsgTarget: true,
            items: [{
                xtype: "textfield",
                fieldLabel: lang("title"),
                anchor: "-10",
                allowBlank: false,
                name: "title"
            },
                {
                    xtype: "compositefield",
                    hideBorders: true,
                    border: false,
                    fieldLabel: lang("startTime"),
                    bodyStyle: "background-color:#F8F8F8;",
                    defaults: {
                        border: false,
                        bodyStyle: "background-color:#F8F8F8;"
                    },
                    items: [{
                        xtype: "datefield",
                        format: "Y-m-d",
                        name: "sdate",
                        allowBlank: false,
                        fieldLabel: lang("startTime"),
                        width: 100
                    },
                        {
                            xtype: "timefield",
                            name: "stime",
                            allowBlank: false,
                            format: "H:i:s",
                            width: 60,
                            listWidth: 77,
                            value: "00:00:00"
                        },
                        {
                            xtype: "displayfield",
                            value: lang("endTime") + ":"
                        },
                        {
                            xtype: "datefield",
                            format: "Y-m-d",
                            name: "edate",
                            allowBlank: false,
                            width: 100
                        },
                        {
                            xtype: "timefield",
                            name: "etime",
                            allowBlank: false,
                            format: "H:i:s",
                            width: 60,
                            listWidth: 77,
                            value: "00:00:00"
                        }]
                },
                {
                    xtype: "checkbox",
                    fieldLabel: lang("isEnable"),
                    checked: true,
                    name: "inUse"
                },
                {
                    xtype: "flashfile",
                    fieldLabel: lang("attach"),
                    name: "files",
                    inputPre: "filesUpload"
                },
                {
                    xtype: "htmleditor",
                    anchor: "-10 -126",
                    fieldLabel: lang("content"),
                    allowBlank: false,
                    name: "content",
                    plugins: [new Ext.ux.form.HtmlEditor.Divider(), new Ext.ux.form.HtmlEditor.Picture()]
                }],
            tbar: new Ext.Toolbar({
                items: [{
                    handler: function(b, c) {
                        a.centerPanel.getLayout().setActiveItem(0)
                    }.createDelegate(this),
                    iconCls: "icon-tab-inbox",
                    text: "<<" + lang("backToList")
                },
                    {
                        handler: function(b, c) {
                            a.saveMessage()
                        },
                        iconCls: "icon-tab-drafts",
                        text: lang("save")
                    }]
            }),
            bbar: new Ext.Toolbar({
                items: [{
                    handler: function(b, c) {
                        a.centerPanel.getLayout().setActiveItem(0)
                    }.createDelegate(this),
                    iconCls: "icon-tab-inbox",
                    text: "<<" + lang("backToList")
                },
                    {
                        handler: function(b, c) {
                            a.saveMessage()
                        },
                        iconCls: "icon-tab-drafts",
                        text: lang("save")
                    }]
            })
        });
        this.centerPanel = new Ext.Panel({
            region: "center",
            layout: "card",
            activeItem: 0,
            hideBorders: true,
            border: false,
            items: [this.smsListPanel, this.smsPreviewPanel, this.smsEditPanel]
        });
        this.mainPanel = new Ext.Panel({
            collapsible: false,
            hideBorders: true,
            border: false,
            layout: "border",
            autoScroll: true,
            items: [this.centerPanel]
        })
    },
    editPanel: function() {
        var b = this.grid.getSelectionModel().getSelections();
        if (b.length == 0) {
            var a = Ext.getCmp(this.ID_btn_edit).getEl().getBox();
            a = [a.x + 12, a.y + 26];
            CNOA.miniMsg.alert(lang("mustSelectOneRow"), a)
        } else {
            CNOA_main_job_edit = new CNOA_main_job_addClass("edit");
            CNOA_main_job_edit.show(b[0].get("id"))
        }
    },
    deleteRecord: function(a) {
        Ext.Ajax.request({
            url: "index.php?app=main&func=message&action=index&task=delete",
            method: "POST",
            params: {
                ids: a
            },
            success: function(c) {
                var b = Ext.decode(c.responseText);
                if (b.success === true) {
                    CNOA.msg.alert(b.msg,
                        function() {
                            CNOA_main_message_list.store.reload()
                        })
                } else {
                    CNOA.msg.alert(b.msg)
                }
            }
        })
    },
    previewMessage: function(c) {
        var b = this;
        previewPanel = b.smsPreviewPanel;
        var a = new Ext.XTemplate('<div style="margin: 5px;">', '	<div style="background-color:#F5F5F5;padding:3px 10px;border-bottom:1px solid #D9D9D9;">', '		<div style="font-size:14px;font-weight:bold;line-height:22px;">{title}</div>', '		<div style="font-size:12px;line-height:18px;color:#919191;">' + lang("sender2") + "：{sender}</div>", '		<div style="font-size:12px;line-height:18px;color:#919191;">' + lang("sender2") + "：{postTime}</div>", '		<div style="font-size:12px;line-height:18px;color:#919191;">' + lang("startTime") + "：{start}</div>", '		<div style="font-size:12px;line-height:18px;color:#919191;">' + lang("endTime") + "：{end}</div>", "	</div>", '	<div class="content" style="font-size:12px;margin:5px 0 0 15px;">{content}</div>', '	<tpl if="attachCount &gt; 0">', '	<div style="border:2px solid #E6E6E6;height:auto;margin-top:100px;">', '		<div style="height:32px;background-color:#E6E6E6;line-height:32px;">', '			<img src="/cnoa/resources/images/icons/attach.gif">', '			<span style="font-size:14px;font-weight:bold;">' + lang("attach") + "</span>({attachCount}个)", "		</div>", "		{attach}", "	</div>", "	</tpl>", "</div>");
        previewPanel.getEl().mask(lang("waiting"));
        previewPanel.body.update("");
        Ext.Ajax.request({
            url: "index.php?app=main&func=message&action=index",
            method: "POST",
            params: {
                task: "preview",
                id: c
            },
            success: function(e) {
                var d = Ext.decode(e.responseText);
                a.overwrite(previewPanel.body, d.data);
                if (b.actionFolder == "inbox") {
                    if (d.data.smstype == "sys") {
                        Ext.getCmp(b.ID_btn_reply).disable()
                    } else {
                        Ext.getCmp(b.ID_btn_reply).enable()
                    }
                }
                previewPanel.getEl().unmask()
            }
        })
    },
    saveMessage: function() {
        var d = this;
        var c = d.smsEditPanel.getForm();
        if (c.isValid()) {
            var a = parseInt(c.findField("sdate").getEl().dom.value.split("-").join("") + c.findField("stime").getValue().split(":").join(""));
            var b = parseInt(c.findField("edate").getEl().dom.value.split("-").join("") + c.findField("etime").getValue().split(":").join(""));
            if (a >= b) {
                CNOA.msg.alert(lang("stimeAndEtime"));
                return false
            }
            c.submit({
                url: "index.php?app=main&func=message&action=index",
                method: "POST",
                waitMsg: lang("waiting"),
                params: {
                    task: d.action,
                    edit_id: d.edit_id
                },
                success: function(e, f) {
                    Ext.MessageBox.show({
                        title: d.action == "add" ? lang("msgSended") : lang("messageHasEdit"),
                        msg: d.action == "add" ? lang("sendSuccessSave") : lang("editSuccessSave"),
                        buttons: {
                            yes: "<<" + lang("gotoList"),
                            no: lang("toWrite") + ">>"
                        },
                        fn: function(h, g, i) {
                            c.reset();
                            if (h == "yes") {
                                d.centerPanel.getLayout().setActiveItem(0);
                                d.store.reload()
                            } else {
                                d.edit_id = 0;
                                d.action = "add"
                            }
                        },
                        icon: Ext.MessageBox.QUESTION
                    })
                },
                failure: function(e, f) {
                    CNOA.msg.alert(f.result.msg,
                        function() {}.createDelegate(this))
                }
            })
        } else {}
    },
    loadMessage: function() {
        var d = this;
        d.action = "edit";
        var b = d.grid.getSelectionModel().getSelections();
        var c = b[0].get("id");
        var a = d.smsEditPanel.getForm();
        a.load({
            url: "index.php?app=main&func=message&action=index",
            params: {
                edit_id: c,
                task: "loadForm"
            },
            method: "POST",
            waitTitle: lang("notice"),
            success: function(e, f) {
                d.edit_id = c;
                a.findField("content").focus()
            },
            failure: function(e, f) {
                CNOA.msg.alert(f.result.msg,
                    function() {})
            }
        })
    },
    inUseChecker: function(a) {
        if (a == "1") {
            return "<span style='color:red;'>" + lang("yes") + "</span>"
        } else {
            return "<span style='color:#999999;'>" + lang("no") + "</span>"
        }
    },
    attachCheck: function(a) {
        if (a == 1) {
            return '<img src="/cnoa/resources/images/icons/attach2.gif" width="16" height="16" />'
        } else {
            return ""
        }
    }
};
var sm_main_message = 1;
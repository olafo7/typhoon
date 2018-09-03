var CNOA_know_know_mapClass, CNOA_know_know_map;
CNOA_know_know_mapClass = CNOA.Class.create();
CNOA_know_know_mapClass.prototype = {
    init: function() {
        var e = this;
        this.pageInit = false;
        this.nowfid = 0;
        this.nowpid = 0;
        this.nowpath = "";
        this.ID_tree_treeRoot = Ext.id();
        this.baseUrl = "index.php?app=know&func=know&action=map";
        this.treeRoot = new Ext.tree.AsyncTreeNode({
            text: lang("knowType"),
            id: "0",
            pid: "0",
            fid: "0",
            iconCls: "icon-tree-root-cnoa",
            cls: "feeds-node",
            expanded: true
        });
        this.treeLoader = new Ext.tree.TreeLoader({
            dataUrl: this.baseUrl + "&task=getCate&type=tree",
            preloadChildren: false,
            clearOnLoad: false,
            listeners: {
                load: function(f) {
                    e.showFullPath()
                }.createDelegate(this),
                beforeload: function(g, f, i) {
                    try {
                        g.baseParams.pid = f.attributes.fid;
                        g.baseParams.path = f.attributes.path
                    } catch(h) {}
                }
            }
        });
        this.CATE_BTN_ADD = Ext.id();
        this.CATE_BTN_EDIT = Ext.id();
        this.CATE_BTN_DEL = Ext.id();
        this.KNOW_BTN_SHARE = Ext.id();
        this.KNOW_BTN_MOVE = Ext.id();
        this.KNOW_BTN_DEL = Ext.id();
        this.displayBtn(0);
        this.dirTree = new Ext.tree.TreePanel({
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
                click: function(f) {
                    e.nowpid = f.attributes.pid;
                    e.nowfid = f.attributes.fid;
                    e.storeBar.pid = e.nowfid;
                    e.store.load({
                        params: {
                            pid: e.storeBar.pid
                        }
                    });
                    f.expand();
                    e.nowpath = f.getPath();
                    e.showFullPath();
                    e.displayBtn(e.storeBar.pid)
                }.createDelegate(this),
                render: function() {}
            }
        });
        var a = Ext.id();
        var c = Ext.id();
        var b = Ext.id();
        this.dirPanel = new Ext.Panel({
            region: "west",
            layout: "fit",
            split: true,
            border: false,
            width: 240,
            minWidth: 30,
            maxWidth: 300,
            bodyStyle: "border-right-width:1px;",
            items: [this.dirTree],
            tbar: new Ext.Toolbar({
                style: "border-right-width:1px;",
                items: [{
                    handler: function(f, g) {
                        e.refreshTree();
                        e.store.reload()
                    }.createDelegate(this),
                    iconCls: "icon-system-refresh",
                    tooltip: lang("refresh"),
                    text: lang("refresh")
                },
                    {
                        text: lang("add"),
                        iconCls: "icon-utils-s-add",
                        id: e.CATE_BTN_ADD,
                        handler: function() {
                            var f = new Ext.Window({
                                title: lang("addStyle"),
                                resizable: false,
                                modal: true,
                                width: 320,
                                height: 150,
                                items: [{
                                    xtype: "form",
                                    bodyStyle: "padding:10px;",
                                    border: false,
                                    labelWidth: 80,
                                    id: a,
                                    items: [{
                                        xtype: "textfield",
                                        fieldLabel: lang("typeName"),
                                        width: 200,
                                        id: c
                                    },
                                        {
                                            xtype: "checkbox",
                                            fieldLabel: lang("ApprovalOfknowledge"),
                                            id: b
                                        }]
                                }],
                                buttons: [{
                                    text: lang("add"),
                                    iconCls: "icon-btn-save",
                                    handler: function() {
                                        var i = Ext.getCmp(a).getForm();
                                        var h = i.findField(c).getValue();
                                        var j = e.nowfid;
                                        var g = i.findField(b).getValue() ? 1 : 0;
                                        Ext.Ajax.request({
                                            url: e.baseUrl + "&task=addCate",
                                            method: "POST",
                                            params: {
                                                name: h,
                                                pid: j,
                                                isSp: g
                                            },
                                            success: function(l) {
                                                var k = Ext.decode(l.responseText);
                                                if (k.success === true) {
                                                    f.close();
                                                    e.treeRoot.reload();
                                                    CNOA.msg.notice2(k.msg)
                                                } else {
                                                    CNOA.msg.alert(k.msg)
                                                }
                                            }
                                        })
                                    }
                                },
                                    {
                                        text: lang("cancel"),
                                        iconCls: "icon-dialog-cancel",
                                        handler: function() {
                                            f.close()
                                        }
                                    }]
                            }).show()
                        },
                        hidden: true
                    },
                    {
                        text: lang("edit"),
                        iconCls: "icon-utils-s-edit",
                        id: e.CATE_BTN_EDIT,
                        handler: function(g, h) {
                            if (e.nowfid == 0) {
                                var f = this.getEl().getBox();
                                f = [f.x + 15, f.y + 26];
                                CNOA.miniMsg.alert(lang("pleaseSelectType"), f)
                            } else {
                                e.doPermit()
                            }
                        }
                    },
                    {
                        text: lang("del"),
                        iconCls: "icon-utils-s-delete",
                        id: e.CATE_BTN_DEL,
                        handler: function(g, h) {
                            if (e.nowfid == 0) {
                                var f = this.getEl().getBox();
                                f = [f.x + 15, f.y + 26];
                                CNOA.miniMsg.alert(lang("pleaseSelectType"), f)
                            } else {
                                CNOA.msg.cf(lang("sureToDelete") + "<br>" + lang("deleteAllKnow") + "！",
                                    function(i) {
                                        if (i == "yes") {
                                            Ext.Ajax.request({
                                                url: e.baseUrl + "&task=deleteCate",
                                                method: "POST",
                                                params: {
                                                    cid: e.nowfid
                                                },
                                                success: function(k) {
                                                    var j = Ext.decode(k.responseText);
                                                    if (j.success === true) {
                                                        e.treeRoot.reload();
                                                        e.nowpid = "0";
                                                        e.nowfid = "0";
                                                        e.storeBar.pid = e.nowpid;
                                                        e.store.load({
                                                            params: {
                                                                pid: e.storeBar.pid
                                                            }
                                                        });
                                                        CNOA.msg.notice2(j.msg)
                                                    } else {
                                                        CNOA.msg.alert(j.msg)
                                                    }
                                                }
                                            })
                                        }
                                    })
                            }
                        },
                        hidden: true
                    }]
            })
        });
        this.storeBar = {
            pid: 0,
            stime: "",
            etime: "",
            auth: "",
            title: "",
            dept: ""
        };
        this.fields = [{
            name: "kid"
        },
            {
                name: "title"
            },
            {
                name: "auth"
            },
            {
                name: "poster"
            },
            {
                name: "posttime"
            },
            {
                name: "endtime"
            },
            {
                name: "cate"
            },
            {
                name: "keyword"
            },
            {
                name: "opt"
            },
            {
                name: "see"
            },
            {
                name: "download"
            },
            {
                name: "edit"
            }];
        this.store = new Ext.data.Store({
            autoLoad: true,
            baseParams: e.storeBar,
            proxy: new Ext.data.HttpProxy({
                url: this.baseUrl + "&task=getList"
            }),
            reader: new Ext.data.JsonReader({
                idProperty: "",
                totalProperty: "total",
                root: "data",
                fields: this.fields
            }),
            listeners: {
                exception: function(k, j, l, i, h, g) {
                    var f = Ext.decode(h.responseText);
                    if (f.failure) {
                        CNOA.msg.alert(f.msg)
                    }
                }
            }
        });
        this.sm = new Ext.grid.CheckboxSelectionModel({
            singleSelect: false
        });
        this.colModel = new Ext.grid.ColumnModel([this.sm, {
            header: "id",
            dataIndex: "kid",
            width: 1,
            sortable: true,
            hidden: true
        },
            {
                header: lang("title"),
                dataIndex: "title",
                width: 150,
                sortable: true,
                id: "title"
            },
            {
                header: lang("author"),
                dataIndex: "auth",
                width: 120,
                sortable: true
            },
            {
                header: lang("keyword2"),
                dataIndex: "keyword",
                width: 120,
                sortable: true
            },
            {
                header: lang("knowType"),
                dataIndex: "cate",
                width: 120,
                sortable: true
            },
            {
                header: lang("posters"),
                dataIndex: "poster",
                width: 120,
                sortable: true
            },
            {
                header: lang("publish"),
                dataIndex: "posttime",
                width: 180,
                sortable: true
            },
            {
                header: lang("opt"),
                dataIndex: "opt",
                width: 180,
                sortable: true,
                renderer: e.getOpt
            }]);
        stime = new Ext.form.DateField({
            format: "Y-m-d",
            width: 90
        });
        etime = new Ext.form.DateField({
            format: "Y-m-d",
            width: 90
        });
        cate = new Ext.form.TriggerField({
            width: 90
        });
        auth = new Ext.form.TextField({
            width: 90
        });
        title = new Ext.form.TextField({
            width: 90
        });
        dept = new Ext.form.SelectorForm({
            singline: true,
            style: "margin-top:2px;padding:0;line-height:25px;",
            selectorType: "dept",
            hiddenName: "deptId",
            width: 90
        });
        this.grid = new Ext.grid.GridPanel({
            store: this.store,
            loadMask: {
                msg: lang("waiting")
            },
            cm: this.colModel,
            sm: this.sm,
            hideBorders: true,
            border: false,
            stripeRows: true,
            layout: "fit",
            listeners: {
                rowdblclick: function(g, h) {
                    var f = g.getStore().getAt(h);
                    e.view(f.data.kid)
                }.createDelegate(this)
            }
        });
        var d = new Ext.PagingToolbar({
            displayInfo: true,
            emptyMsg: lang("pagingToolbarEmptyMsg"),
            displayMsg: lang("showDataTotal2"),
            store: this.store,
            pageSize: 50,
            plugins: [new Ext.grid.plugins.ComboPageSize()]
        });
        this.gridPanel = new Ext.Panel({
            region: "center",
            layout: "fit",
            border: false,
            items: [this.grid],
            bbar: d,
            tbar: new Ext.Toolbar({
                items: [{
                    handler: function(f, g) {
                        e.store.reload()
                    }.createDelegate(this),
                    iconCls: "icon-system-refresh",
                    tooltip: lang("refresh"),
                    style: "margin-left:0px;",
                    text: lang("refresh")
                },
                    {
                        handler: function(g, h) {
                            var i = e.grid.getSelectionModel().getSelections();
                            if (i.length != 1) {
                                var f = this.getEl().getBox();
                                f = [f.x + 12, f.y + 26];
                                CNOA.miniMsg.alert(lang("selectOneItem"), f)
                            } else {
                                CNOA_know_know_map.shareKnow(i[0].data.kid)
                            }
                        },
                        iconCls: "icon-user-share",
                        cls: "btn-blue3",
                        text: lang("share"),
                        hidden: true,
                        id: e.KNOW_BTN_SHARE
                    },
                    {
                        handler: function(g, h) {
                            var i = CNOA_know_know_map.grid.getSelectionModel().getSelections();
                            if (i.length != 1) {
                                var f = this.getEl().getBox();
                                f = [f.x + 12, f.y + 26];
                                CNOA.miniMsg.alert(lang("selectOneItem"), f)
                            } else {
                                CNOA_know_know_map.moveToCate(i[0].data.kid)
                            }
                        },
                        text: lang("transfer1"),
                        iconCls: "icon-customes-change",
                        cls: "btn-red1",
                        hidden: true,
                        id: e.KNOW_BTN_MOVE
                    },
                    {
                        text: lang("del"),
                        iconCls: "icon-utils-s-delete",
                        handler: function(g, h) {
                            var i = e.grid.getSelectionModel().getSelections();
                            if (i.length == 0) {
                                var f = this.getEl().getBox();
                                f = [f.x + 12, f.y + 26];
                                CNOA.miniMsg.alert(lang("mustSelectOneRow"), f)
                            } else {
                                CNOA.msg.cf(lang("confirmToDelete"),
                                    function(k) {
                                        if (k == "yes") {
                                            if (i) {
                                                var l = "";
                                                for (var j = 0; j < i.length; j++) {
                                                    l += i[j].get("kid") + ","
                                                }
                                            }
                                            CNOA_know_know_map.deleteList(l)
                                        }
                                    })
                            }
                        },
                        hidden: true,
                        id: e.KNOW_BTN_DEL
                    },
                    "->", "&nbsp;" + lang("publish") + ":&nbsp;", stime, lang("to"), etime, "&nbsp;" + lang("author") + ":&nbsp;", auth, "&nbsp;" + lang("title") + ":&nbsp;", title, "&nbsp;" + lang("department") + ":&nbsp;", dept, {
                        xtype: "button",
                        text: lang("search"),
                        handler: function(f) {
                            e.doSearch()
                        }
                    },
                    {
                        text: lang("clear"),
                        handler: function() {
                            stime.setValue("");
                            etime.setValue("");
                            auth.setValue("");
                            title.setValue("");
                            dept.setValue("");
                            dept.clearValue();
                            e.storeBar.pid = e.nowfid;
                            e.storeBar.stime = "";
                            e.storeBar.etime = "";
                            e.storeBar.auth = "";
                            e.storeBar.title = "";
                            e.storeBar.dept = "";
                            e.store.load({
                                params: {
                                    pid: e.storeBar.pid
                                }
                            })
                        }
                    }]
            })
        });
        this.addressBar = new Ext.BoxComponent({
            autoEl: {
                tag: "span",
                html: lang("knowType")
            }
        });
        this.centerPanel = new Ext.Panel({
            region: "center",
            bodyStyle: "border-left-width:1px;overflow:auto;",
            layout: "fit",
            border: false,
            items: [this.gridPanel],
            tbar: new Ext.Toolbar({
                style: "border-left-width:1px;",
                items: [lang("path") + ": ", this.addressBar, "->", ("<span style='color:#999'>" + lang("dbClickKnowDetail") + "</span>")]
            })
        });
        this.mainPanel = new Ext.Panel({
            collapsible: false,
            hideBorders: true,
            border: false,
            layout: "border",
            autoScroll: false,
            items: [this.dirPanel, this.centerPanel]
        })
    },
    shareKnow: function(c) {
        var d = this;
        var b = new Ext.form.FormPanel({
            border: false,
            bodyStyle: "padding:10px;",
            waitMsgTarget: true,
            labelWidth: 100,
            items: [{
                xtype: "selectorform",
                selectorType: "dept",
                multiselect: true,
                hiddenName: "shareDept",
                name: "chooseDept",
                width: 300,
                fieldLabel: lang("chooseShareDept")
            },
                {
                    xtype: "displayfield",
                    value: "<span style='color:#676767;'>" + lang("emptyDefaultAll") + "</span>"
                },
                {
                    xtype: "selectorform",
                    selectorType: "user",
                    multiselect: true,
                    hiddenName: "shareUser",
                    name: "chooseUser",
                    width: 300,
                    fieldLabel: lang("chooseShareUser")
                },
                {
                    xtype: "displayfield",
                    value: "<span style='color:#676767;'>" + lang("emptyDefaultAll") + "</span>"
                },
                {
                    xtype: "checkboxgroup",
                    fieldLabel: lang("permitShare"),
                    columns: 3,
                    width: 200,
                    items: [{
                        boxLabel: lang("view"),
                        name: "view",
                        checked: true,
                        disabled: true
                    },
                        {
                            boxLabel: lang("download"),
                            name: "download"
                        },
                        {
                            boxLabel: lang("edit"),
                            name: "edit"
                        }]
                },
                {
                    xtype: "checkbox",
                    fieldLabel: lang("notice1"),
                    name: "isNotice",
                    inputValue: "1",
                    checked: true,
                    boxLabel: lang("sendNoticeToSUser")
                }]
        });
        var a = new Ext.Window({
            title: lang("knowShare"),
            width: 460,
            height: 335,
            modal: true,
            resizable: false,
            items: [b],
            buttons: [{
                text: lang("share"),
                iconCls: "icon-btn-save",
                cls: "btn-blue4",
                handler: function() {
                    CNOA.msg.cf(lang("suerToShare"),
                        function(i) {
                            if (i == "yes") {
                                var k = b.getForm();
                                if (k.isValid()) {
                                    var l = k.findField("shareDept").getValue();
                                    var g = k.findField("shareUser").getValue();
                                    var f = k.findField("view").getValue() ? 1 : 0;
                                    var e = k.findField("download").getValue() ? 1 : 0;
                                    var j = k.findField("edit").getValue() ? 1 : 0;
                                    var h = k.findField("isNotice").getValue() ? 1 : 0;
                                    Ext.Ajax.request({
                                        url: d.baseUrl + "&task=shareKnow",
                                        method: "POST",
                                        params: {
                                            kid: c,
                                            sid: l,
                                            uid: g,
                                            view: f,
                                            download: e,
                                            edit: j,
                                            isNotice: h
                                        },
                                        success: function(n) {
                                            var m = Ext.decode(n.responseText);
                                            if (m.success === true) {
                                                a.close();
                                                d.store.reload();
                                                CNOA.msg.notice2(m.msg)
                                            } else {
                                                CNOA.msg.alert(m.msg)
                                            }
                                        }
                                    })
                                }
                            }
                        })
                }
            },
                {
                    text: lang("cancel"),
                    iconCls: "icon-dialog-cancel",
                    handler: function() {
                        a.close()
                    }
                }]
        }).show()
    },
    moveToCate: function(f) {
        var e = this;
        var b = new Ext.tree.AsyncTreeNode({
            text: lang("knowType"),
            id: "0",
            pid: "0",
            fid: "0",
            iconCls: "icon-tree-root-cnoa",
            cls: "feeds-node",
            expanded: true
        });
        var c = new Ext.tree.TreeLoader({
            dataUrl: e.baseUrl + "&task=getCate&type=combo",
            preloadChildren: false,
            clearOnLoad: false,
            listeners: {
                load: function(g) {
                    b.expand()
                },
                beforeload: function(h, g, j) {
                    try {
                        h.baseParams.pid = g.attributes.fid;
                        h.baseParams.path = g.attributes.path
                    } catch(i) {}
                }
            }
        });
        var a = new Ext.tree.TreePanel({
            hideBorders: true,
            border: false,
            rootVisible: true,
            lines: true,
            containerScroll: true,
            animCollapse: false,
            checkModel: "single",
            animate: false,
            loader: c,
            root: b,
            autoScroll: true,
            listeners: {
                checkchange: function(k, j) {
                    if (j) {
                        var g = a.getChecked();
                        if (g && g.length) {
                            for (var h = 0; h < g.length; h++) {
                                if (g[h].id != k.id) {
                                    g[h].getUI().toggleCheck(false);
                                    g[h].attributes.checked = false
                                }
                            }
                        }
                        e.nodeId = k.id
                    }
                }.createDelegate(this)
            }
        });
        var d = new Ext.Window({
            border: false,
            width: 320,
            height: 300,
            autoScroll: true,
            modal: true,
            layout: "fit",
            title: lang("knowTransfer"),
            buttons: [{
                text: lang("save"),
                handler: function() {
                    if (e.nodeId) {
                        Ext.Ajax.request({
                            url: e.baseUrl + "&task=moveToCate",
                            method: "POST",
                            params: {
                                fileid: f,
                                pid: e.nodeId
                            },
                            success: function(h) {
                                var g = Ext.decode(h.responseText);
                                if (g.success === true) {
                                    d.close();
                                    e.store.reload();
                                    CNOA.msg.notice2(g.msg)
                                } else {
                                    CNOA.msg.alert(g.msg)
                                }
                            }
                        })
                    }
                }
            },
                {
                    text: lang("cancel"),
                    handler: function() {
                        d.close()
                    }
                }],
            items: [a]
        });
        d.show()
    },
    displayBtn: function(a) {
        Ext.Ajax.request({
            url: this.baseUrl + "&task=displayBtn",
            method: "POST",
            params: {
                pid: a
            },
            success: function(c) {
                var b = Ext.decode(c.responseText);
                if (b.cateCz == 1) {
                    Ext.getCmp(CNOA_know_know_map.CATE_BTN_ADD).show();
                    Ext.getCmp(CNOA_know_know_map.CATE_BTN_DEL).show()
                }
                if (b.knowShare == 1) {
                    Ext.getCmp(CNOA_know_know_map.KNOW_BTN_SHARE).show()
                } else {
                    Ext.getCmp(CNOA_know_know_map.KNOW_BTN_SHARE).hide()
                }
                if (b.knowMove == 1) {
                    Ext.getCmp(CNOA_know_know_map.KNOW_BTN_MOVE).show()
                } else {
                    Ext.getCmp(CNOA_know_know_map.KNOW_BTN_MOVE).hide()
                }
                if (b.knowDelete == 1) {
                    Ext.getCmp(CNOA_know_know_map.KNOW_BTN_DEL).show()
                } else {
                    Ext.getCmp(CNOA_know_know_map.KNOW_BTN_DEL).hide()
                }
            }
        })
    },
    getOpt: function(e, c, b) {
        var d = b.data;
        var a = "";
        if (d.see == 1) {
            a += '<a href="javascript:void(0);" class="gridview" onclick="CNOA_know_know_map.view(' + d.kid + ')">' + lang("view") + "</a>"
        }
        if (d.download == 1) {
            a += "<a href=\"javascript:ajaxDownload('index.php?app=know&func=know&action=view&task=download&kid=" + d.kid + '\');" class="gridview3 jianju">' + lang("download") + "</a>"
        }
        if (d.edit == 1) {
            a += '<a href="javascript:void(0);" class="gridview4 jianju" onclick="CNOA_know_know_map.editKnow(\'edit\',' + d.kid + ')">' + lang("edit") + "</a>"
        }
        return a
    },
    deleteList: function(a) {
        Ext.Ajax.request({
            url: this.baseUrl + "&task=deleteKnow",
            method: "POST",
            params: {
                ids: a
            },
            success: function(c) {
                var b = Ext.decode(c.responseText);
                if (b.success === true) {
                    CNOA_know_know_map.store.reload();
                    CNOA.msg.alert(b.msg)
                } else {
                    CNOA.msg.alert(b.msg)
                }
            }
        })
    },
    view: function(a) {
        mainPanel.closeTab("CNOA_KNOW_KNOW_VIEW");
        mainPanel.loadClass("index.php?app=know&func=know&action=view&from=map&kid=" + a, "CNOA_KNOW_KNOW_VIEW", lang("knowView"), "icon-page-view")
    },
    editKnow: function(a, c) {
        var b = this;
        var c = c || 0;
        Ext.Ajax.request({
            url: "index.php?app=know&func=know&action=edit&task=getUeditorContent",
            method: "POST",
            params: {
                id: c
            },
            success: function(e) {
                var d = Ext.decode(e.responseText).content;
                var f = new CNOA_know_know_editComponentClass(this, c, d, b.store);
                f.show()
            }
        })
    },
    doSearch: function() {
        var a = this;
        a.storeBar.stime = stime.getRawValue();
        a.storeBar.etime = etime.getRawValue();
        a.storeBar.auth = auth.getValue();
        a.storeBar.title = title.getValue();
        a.storeBar.dept = dept.getValue();
        a.store.load({
            params: {
                pid: a.storeBar.pid
            }
        })
    },
    refreshTree: function() {
        this.treeRoot.reload()
    },
    reload: function() {
        this.refreshTree();
        this.store.reload()
    },
    shareFileTo: function(e) {
        var d = this;
        var b = new Ext.Window({
            title: lang("setShare"),
            width: 500,
            height: 140,
            modal: true,
            resizable: false,
            layout: "fit",
            items: [{
                xtype: "form",
                id: "OUTSIDE_FORM_ID",
                border: false,
                items: [{
                    xtype: "textarea",
                    hideLabel: true,
                    name: "outsideurl",
                    width: 480
                },
                    {
                        xtype: "button",
                        text: lang("copyLink"),
                        cls: "btn-blue3",
                        handler: function(f) {
                            var g = Ext.getCmp("OUTSIDE_FORM_ID").getForm().findField("outsideurl").getValue();
                            try {
                                window.clipboardData.setData("text", g)
                            } catch(h) {
                                alert(lang("noSupportCopy"))
                            }
                        }
                    }]
            }]
        });
        var a = new Ext.form.FormPanel({
            bodyStyle: "padding:10px;",
            border: false,
            waitMsgTarget: true,
            items: [{
                xtype: "hidden",
                name: "peopleUid"
            },
                {
                    xtype: "textarea",
                    width: 325,
                    height: 50,
                    fieldLabel: lang("selectPeople"),
                    name: "people",
                    readOnly: true,
                    listeners: {
                        afterrender: function(f) {
                            f.mon(f.el, "click",
                                function() {
                                    var g = a.getForm().findField("peopleUid").getValue();
                                    new_selector("user", a.getForm().findField("people"), a.getForm().findField("peopleUid"), true, d.baseUrl + "&task=selector&target=user", g)
                                })
                        }
                    }
                },
                {
                    xtype: "hidden",
                    name: "deptIds"
                },
                {
                    xtype: "textarea",
                    width: 325,
                    height: 50,
                    fieldLabel: lang("selectDept"),
                    name: "dept",
                    readOnly: true,
                    listeners: {
                        afterrender: function(f) {
                            f.mon(f.el, "click",
                                function() {
                                    var g = a.getForm().findField("deptIds").getValue();
                                    new_selector("dept", a.getForm().findField("dept"), a.getForm().findField("deptIds"), true, d.baseUrl + "&task=selector&target=dept", g)
                                })
                        }
                    }
                },
                {
                    xtype: "compositefield",
                    fieldLabel: lang("expireTime"),
                    combineErrors: false,
                    items: [{
                        xtype: "displayfield",
                        value: lang("visitorOver")
                    },
                        {
                            name: "disTime",
                            xtype: "datetimefield",
                            format: "Y-m-d H:i",
                            width: 150
                        },
                        {
                            xtype: "displayfield",
                            value: lang("whenNoView")
                        }]
                },
                {
                    xtype: "compositefield",
                    fieldLabel: lang("expireTimes"),
                    combineErrors: false,
                    items: [{
                        xtype: "displayfield",
                        value: lang("wtbo")
                    },
                        {
                            name: "disView",
                            xtype: "numberfield",
                            width: 100
                        },
                        {
                            xtype: "displayfield",
                            value: lang("whenNoView2")
                        }]
                },
                {
                    xtype: "compositefield",
                    fieldLabel: lang("expireTimes"),
                    combineErrors: false,
                    items: [{
                        xtype: "displayfield",
                        value: lang("wtdo")
                    },
                        {
                            name: "disDownload",
                            xtype: "numberfield",
                            width: 100
                        },
                        {
                            xtype: "displayfield",
                            value: lang("whenNoView2")
                        }]
                },
                {
                    xtype: "checkboxgroup",
                    fieldLabel: lang("permitShare"),
                    columns: 3,
                    width: 200,
                    items: [{
                        boxLabel: lang("download"),
                        name: "download"
                    },
                        {
                            boxLabel: lang("edit"),
                            name: "edit"
                        },
                        {
                            boxLabel: lang("newEmail"),
                            name: "email"
                        }]
                },
                {
                    xtype: "checkbox",
                    boxLabel: lang("makeLinkNotice"),
                    name: "outsideLink",
                    listeners: {}
                },
                {
                    xtype: "displayfield",
                    value: "(" + lang("makeLinkBZ") + ")"
                }],
            listeners: {
                afterrender: function(f) {
                    Ext.Ajax.request({
                        url: d.baseUrl + "&task=sharedpeople",
                        params: {
                            fileid: e
                        },
                        success: function(g) {
                            var h = Ext.decode(g.responseText);
                            a.getForm().findField("peopleUid").setValue(h.data[0]);
                            a.getForm().findField("people").setValue(h.data[1]);
                            a.getForm().findField("deptIds").setValue(h.data[2]);
                            a.getForm().findField("dept").setValue(h.data[3]);
                            d.store.reload()
                        }
                    })
                }
            }
        });
        var c = new Ext.Window({
            title: lang("setShare"),
            width: 700,
            height: 400,
            modal: true,
            resizable: false,
            items: a,
            layout: "fit",
            buttons: [{
                text: lang("share"),
                iconCls: "icon-btn-save",
                cls: "btn-blue4",
                handler: function() {
                    CNOA.msg.cf(lang("suerToShare"),
                        function(f) {
                            if (f == "yes") {
                                a.getForm().submit({
                                    url: d.baseUrl + "&task=submitShare",
                                    method: "POST",
                                    waitMsg: lang("waiting"),
                                    params: {
                                        fileid: e
                                    },
                                    success: function(g, h) {
                                        if (h.result.success == true) {
                                            if (h.result.rand == true) {
                                                Ext.getCmp("OUTSIDE_FORM_ID").getForm().findField("outsideurl").setValue(h.result.randomUrl);
                                                b.show();
                                                c.close()
                                            } else {
                                                CNOA.msg.alert(h.result.msg,
                                                    function() {
                                                        c.close()
                                                    })
                                            }
                                        }
                                    },
                                    failure: function(g, h) {
                                        CNOA.msg.alert(h.result.msg,
                                            function() {})
                                    }
                                })
                            }
                        })
                }
            },
                {
                    text: lang("cancel"),
                    iconCls: "icon-dialog-cancel",
                    handler: function() {
                        c.close()
                    }
                }]
        }).show()
    },
    showFullPath: function() {
        var d = this;
        try {
            var c = d.dirTree.getNodeById(d.nowfid);
            var b = c.getPath("text");
            b = b.substr(1, b.length);
            d.addressBar.getEl().update(b.replace(/\//g, "<span style='color:#AAAAAA;margin:0 3px;'>/</span>"))
        } catch(a) {}
    },
    doPermit: function() {
        var e = this;
        CNOA_know_know_mapPermitMember = new CNOA_know_know_mapPermitMemberClass(e.nowfid);
        CNOA_know_know_mapPermitDeptment = new CNOA_know_know_mapPermitDeptmentClass(e.nowfid);
        var d = CNOA_know_know_mapPermitMember.mainPanel;
        var c = CNOA_know_know_mapPermitDeptment.mainPanel;
        var b = new Ext.Panel({
            border: false,
            region: "center",
            layout: "card",
            activeItem: 0,
            items: [d, c],
            tbar: [{
                handler: function(f, g) {
                    b.getLayout().setActiveItem(0)
                }.createDelegate(this),
                iconCls: "icon-roduction",
                enableToggle: true,
                pressed: true,
                allowDepress: false,
                toggleGroup: "MAP_BTN_GROUP_PERMIT",
                cls: "btn-blue4",
                text: lang("userPermitSet")
            },
                {
                    handler: function(f, g) {
                        b.getLayout().setActiveItem(1)
                    }.createDelegate(this),
                    enableToggle: true,
                    allowDepress: false,
                    toggleGroup: "MAP_BTN_GROUP_PERMIT",
                    iconCls: "icon-roduction",
                    cls: "btn-blue4",
                    text: lang("deptPermitSet")
                },
                "->", {
                    xtype: "displayfield",
                    hidden: e.nowpid == 0 ? true: false,
                    value: lang("extendQx")
                },
                {
                    xtype: "checkbox",
                    hidden: e.nowpid == 0 ? true: false,
                    id: "knowExtendCombobox",
                    listeners: {
                        check: function(g, f) {
                            if (!f) {
                                CNOA.msg.cf(lang("whetherInterrupt"),
                                    function(h) {
                                        if (h == "yes") {
                                            CNOA.msg.cf(lang("jJZDSFFZ"),
                                                function(i) {
                                                    if (i == "yes") {
                                                        e.doExtend(e.nowfid, 0, 1)
                                                    } else {
                                                        e.doExtend(e.nowfid, 0, 0)
                                                    }
                                                })
                                        } else {
                                            g.suspendEvents();
                                            g.setValue(true);
                                            g.resumeEvents()
                                        }
                                    })
                            } else {
                                CNOA.msg.cf(lang("sFJCSJML"),
                                    function(h) {
                                        if (h == "yes") {
                                            e.doExtend(e.nowfid, 1)
                                        } else {
                                            g.suspendEvents();
                                            g.setValue(false);
                                            g.resumeEvents()
                                        }
                                    })
                            }
                        }
                    }
                }]
        });
        var a = new Ext.Window({
            title: lang("userPermit"),
            width: 1050,
            layout: "border",
            modal: true,
            height: 400,
            resizable: false,
            scroll: true,
            border: false,
            items: [b],
            listeners: {
                close: function() {
                    Ext.Ajax.request({
                        url: e.baseUrl + "&task=deleteEmptyPermit",
                        method: "POST",
                        params: {
                            fid: e.nowfid
                        },
                        success: function(g) {
                            var f = Ext.decode(g.responseText);
                            if (f.success === true) {} else {
                                CNOA.msg.alert(f.msg,
                                    function() {})
                            }
                        }
                    })
                }
            }
        }).show()
    },
    doExtend: function(a, d, c) {
        var b = this;
        Ext.Ajax.request({
            url: b.baseUrl + "&task=doExtend",
            method: "POST",
            params: {
                fid: a,
                extend: d,
                copy: c
            },
            success: function(f) {
                var e = Ext.decode(f.responseText);
                if (e.success === true) {
                    CNOA.msg.notice2(e.msg);
                    CNOA_know_know_mapPermitMember.store.reload();
                    CNOA_know_know_mapPermitDeptment.store.reload()
                } else {
                    CNOA.msg.alert(e.msg,
                        function() {})
                }
            }
        })
    }
};
CNOA_know_know_mapPermitMemberClass = CNOA.Class.create();
CNOA_know_know_mapPermitMemberClass.prototype = {
    init: function(b) {
        var c = this;
        var a = Ext.id();
        this.fid = b;
        this.submitBtnID = Ext.id();
        this.baseUrl = "index.php?app=know&func=know&action=map";
        this.fields = [{
            name: "pid"
        },
            {
                name: "uid"
            },
            {
                name: "name"
            }];
        this.store = new Ext.data.GroupingStore({
            proxy: new Ext.data.HttpProxy({
                url: this.baseUrl + "&task=permitListByM&fid=" + b
            }),
            reader: new Ext.data.JsonReader({
                totalProperty: "total",
                root: "data",
                fields: this.fields
            }),
            listeners: {
                load: function(g, d, e) {
                    Ext.fly("CNOA_USER_DISK_SELLECTALL_VIEW").on("click",
                        function() {
                            var j = this.dom.checked;
                            Ext.select("input[id^=CNOA_USER_DISK_MGRPUBPERMITMEMBER_VIEW]").each(function() {
                                if (!$(this.dom).attr("disabled")) {
                                    this.dom.checked = j
                                }
                            })
                        });
                    Ext.fly("CNOA_USER_DISK_SELLECTALL_EDIT").on("click",
                        function() {
                            var j = this.dom.checked;
                            Ext.select("input[id^=CNOA_USER_DISK_MGRPUBPERMITMEMBER_EDIT]").each(function() {
                                if (!$(this.dom).attr("disabled")) {
                                    this.dom.checked = j
                                }
                            })
                        });
                    Ext.fly("CNOA_USER_DISK_SELLECTALL_MOVE").on("click",
                        function() {
                            var j = this.dom.checked;
                            Ext.select("input[id^=CNOA_USER_DISK_MGRPUBPERMITMEMBER_MOVE]").each(function() {
                                if (!$(this.dom).attr("disabled")) {
                                    this.dom.checked = j
                                }
                            })
                        });
                    Ext.fly("CNOA_USER_DISK_SELLECTALL_DOWNLOAD").on("click",
                        function() {
                            var j = this.dom.checked;
                            Ext.select("input[id^=CNOA_USER_DISK_MGRPUBPERMITMEMBER_DOWNLOAD]").each(function() {
                                if (!$(this.dom).attr("disabled")) {
                                    this.dom.checked = j
                                }
                            })
                        });
                    Ext.fly("CNOA_USER_DISK_SELLECTALL_SHARE").on("click",
                        function() {
                            var j = this.dom.checked;
                            Ext.select("input[id^=CNOA_USER_DISK_MGRPUBPERMITMEMBER_SHARE]").each(function() {
                                if (!$(this.dom).attr("disabled")) {
                                    this.dom.checked = j
                                }
                            })
                        });
                    Ext.fly("CNOA_USER_DISK_SELLALL_UPLOAD").on("click",
                        function() {
                            var j = this.dom.checked;
                            Ext.select("input[id^=CNOA_USER_DISK_MGRPUBPERMITMEMBER_UPLOAD]").each(function() {
                                if (!$(this.dom).attr("disabled")) {
                                    this.dom.checked = j
                                }
                            })
                        });
                    Ext.fly("CNOA_USER_DISK_SELLALL_DELETE").on("click",
                        function() {
                            var j = this.dom.checked;
                            Ext.select("input[id^=CNOA_USER_DISK_MGRPUBPERMITMEMBER_DELETE]").each(function() {
                                if (!$(this.dom).attr("disabled")) {
                                    this.dom.checked = j
                                }
                            })
                        });
                    var f = Ext.fly("CNOA_USER_DISK_SELLALL_MGR").on("click",
                        function() {
                            var j = this.dom.checked;
                            Ext.select("input[id^=CNOA_USER_DISK_MGRPUBPERMITMEMBER_VIEW]").each(function() {
                                if (!$(this.dom).attr("disabled")) {
                                    this.dom.checked = j
                                }
                            });
                            Ext.select("input[id^=CNOA_USER_DISK_MGRPUBPERMITMEMBER_EDIT]").each(function() {
                                if (!$(this.dom).attr("disabled")) {
                                    this.dom.checked = j
                                }
                            });
                            Ext.select("input[id^=CNOA_USER_DISK_MGRPUBPERMITMEMBER_MOVE]").each(function() {
                                if (!$(this.dom).attr("disabled")) {
                                    this.dom.checked = j
                                }
                            });
                            Ext.select("input[id^=CNOA_USER_DISK_MGRPUBPERMITMEMBER_DOWNLOAD]").each(function() {
                                if (!$(this.dom).attr("disabled")) {
                                    this.dom.checked = j
                                }
                            });
                            Ext.select("input[id^=CNOA_USER_DISK_MGRPUBPERMITMEMBER_SHARE]").each(function() {
                                if (!$(this.dom).attr("disabled")) {
                                    this.dom.checked = j
                                }
                            });
                            Ext.select("input[id^=CNOA_USER_DISK_MGRPUBPERMITMEMBER_UPLOAD]").each(function() {
                                if (!$(this.dom).attr("disabled")) {
                                    this.dom.checked = j
                                }
                            });
                            Ext.select("input[id^=CNOA_USER_DISK_MGRPUBPERMITMEMBER_DELETE]").each(function() {
                                if (!$(this.dom).attr("disabled")) {
                                    this.dom.checked = j
                                }
                            });
                            Ext.select("input[id^=CNOA_USER_DISK_MGRPUBPERMITMEMBER_ALL]").each(function() {
                                if (!$(this.dom).attr("disabled")) {
                                    this.dom.checked = j
                                }
                            })
                        });
                    var i = Ext.getCmp("knowExtendCombobox");
                    i.suspendEvents();
                    i.setValue(g.reader.jsonData.extend);
                    i.resumeEvents();
                    var h = 0;
                    Ext.each(d,
                        function(j) {
                            if (j.json.extend == "1") {
                                h++
                            }
                        });
                    if (g.getCount() > h) {
                        Ext.getCmp(c.submitBtnID).enable()
                    }
                }
            }
        });
        this.sm = new Ext.grid.CheckboxSelectionModel({
            singleSelect: true
        });
        this.store.load();
        this.cm = [new Ext.grid.RowNumberer(), {
            header: "pid",
            dataIndex: "pid",
            hidden: true
        },
            {
                header: lang("name2"),
                dataIndex: "name",
                width: 100,
                sortable: true
            },
            {
                header: "<input type='checkbox' id='CNOA_USER_DISK_SELLECTALL_VIEW'>" + lang("permit4View"),
                dataIndex: "pid",
                width: 100,
                sortable: false,
                menuDisabled: true,
                renderer: this.viewfile.createDelegate(this)
            },
            {
                header: "<input type='checkbox' id='CNOA_USER_DISK_SELLECTALL_EDIT'>" + lang("permit4Edit"),
                dataIndex: "pid",
                width: 100,
                sortable: false,
                menuDisabled: true,
                renderer: this.editfile.createDelegate(this)
            },
            {
                header: "<input type='checkbox' id='CNOA_USER_DISK_SELLECTALL_MOVE'>" + lang("knowTransQx"),
                dataIndex: "pid",
                width: 100,
                sortable: false,
                menuDisabled: true,
                renderer: this.movefile.createDelegate(this)
            },
            {
                header: "<input type='checkbox' id='CNOA_USER_DISK_SELLECTALL_DOWNLOAD'>" + lang("permit4Down"),
                dataIndex: "pid",
                width: 80,
                sortable: false,
                menuDisabled: true,
                renderer: this.downloadfile.createDelegate(this)
            },
            {
                header: "<input type='checkbox' id='CNOA_USER_DISK_SELLECTALL_SHARE'>" + lang("permit4Share"),
                dataIndex: "pid",
                width: 80,
                sortable: false,
                menuDisabled: true,
                renderer: this.sharefile.createDelegate(this)
            },
            {
                header: "<input type='checkbox' id='CNOA_USER_DISK_SELLALL_UPLOAD'>" + lang("knowFbQx"),
                dataIndex: "pid",
                width: 100,
                sortable: false,
                menuDisabled: true,
                renderer: this.uploadfile.createDelegate(this)
            },
            {
                header: "<input type='checkbox' id='CNOA_USER_DISK_SELLALL_DELETE'>" + lang("permit4Del"),
                dataIndex: "pid",
                width: 80,
                sortable: false,
                menuDisabled: true,
                renderer: this.deletefile.createDelegate(this)
            },
            {
                header: "<input type='checkbox' id='CNOA_USER_DISK_SELLALL_MGR'>" + lang("permit4Mgr"),
                dataIndex: "pid",
                width: 80,
                sortable: false,
                menuDisabled: true,
                renderer: this.selectall.createDelegate(this)
            },
            {
                header: lang("opt"),
                dataIndex: "pid",
                width: 80,
                sortable: true,
                menuDisabled: true,
                renderer: this.operate.createDelegate(this)
            },
            {
                header: "",
                dataIndex: "",
                width: 1,
                menuDisabled: true,
                resizable: false
            }];
        this.grid = new Ext.grid.GridPanel({
            border: false,
            layout: "fit",
            autoScroll: true,
            store: this.store,
            loadMask: {
                msg: lang("waiting")
            },
            columns: this.cm,
            sm: this.sm,
            stripeRows: true,
            hideBorders: true,
            tbar: [{
                text: lang("refresh"),
                iconCls: "icon-system-refresh",
                handler: function(d) {
                    c.store.reload()
                }
            },
                {
                    text: lang("addUser"),
                    iconCls: "icon-utils-s-add",
                    cls: "btn-blue4",
                    handler: function() {
                        c.add(b)
                    }
                },
                {
                    text: "&nbsp;&nbsp;" + lang("submit") + "&nbsp;&nbsp;",
                    iconCls: "icon-pass",
                    style: "margin-left:5px",
                    cls: "btn-blue4",
                    id: this.submitBtnID,
                    disabled: true,
                    handler: function() {
                        c.submit(b)
                    }
                }]
        });
        this.mainPanel = new Ext.form.FormPanel({
            collapsible: false,
            hideBorders: true,
            border: false,
            layout: "fit",
            autoScroll: false,
            items: [this.grid]
        })
    },
    viewfile: function(i, g, d, h, e, b) {
        var j = d.json,
            f = j.extend == "1" ? "extend='1'": "extend='0'",
            a = j.vi == "1" ? " checked='checked' ": "",
            c = j.extend == "1" ? "disabled='true'": "";
        return "<label><input " + f + " row='CNOA_USER_DISK_MGRPUBPERMITMEMBER_ROW" + h + "' name='mem[" + i + "][vi]' type='checkbox' value='1' id='CNOA_USER_DISK_MGRPUBPERMITMEMBER_VIEW" + i + "' " + a + " " + c + " /></label>"
    },
    editfile: function(i, g, d, h, e, b) {
        var j = d.json,
            f = j.extend == "1" ? "extend='1'": "extend='0'",
            a = j.ed == "1" ? " checked='checked' ": "",
            c = j.extend == "1" ? "disabled='true'": "";
        return "<label><input " + f + " row='CNOA_USER_DISK_MGRPUBPERMITMEMBER_ROW" + h + "' name='mem[" + i + "][ed]' type='checkbox' value='1' id='CNOA_USER_DISK_MGRPUBPERMITMEMBER_EDIT" + i + "' " + a + " " + c + " /></label>"
    },
    movefile: function(i, g, d, h, e, b) {
        var j = d.json,
            f = j.extend == "1" ? "extend='1'": "extend='0'",
            a = j.mv == "1" ? " checked='checked' ": "",
            c = j.extend == "1" ? "disabled='true'": "";
        return "<label><input " + f + " row='CNOA_USER_DISK_MGRPUBPERMITMEMBER_ROW" + h + "' name='mem[" + i + "][mv]' type='checkbox' value='1' id='CNOA_USER_DISK_MGRPUBPERMITMEMBER_MOVE" + i + "' " + a + " " + c + " /></label>"
    },
    downloadfile: function(i, g, d, h, e, b) {
        var j = d.json,
            f = j.extend == "1" ? "extend='1'": "extend='0'",
            a = j.dl == "1" ? " checked='checked' ": "",
            c = j.extend == "1" ? "disabled='true'": "";
        return "<label><input " + f + " row='CNOA_USER_DISK_MGRPUBPERMITMEMBER_ROW" + h + "' name='mem[" + i + "][dl]' type='checkbox' value='1' id='CNOA_USER_DISK_MGRPUBPERMITMEMBER_DOWNLOAD" + i + "' " + a + " " + c + " /></label>"
    },
    sharefile: function(i, g, d, h, e, b) {
        var j = d.json,
            f = j.extend == "1" ? "extend='1'": "extend='0'",
            a = j.sh == "1" ? " checked='checked' ": "",
            c = j.extend == "1" ? "disabled='true'": "";
        return "<label><input " + f + " row='CNOA_USER_DISK_MGRPUBPERMITMEMBER_ROW" + h + "' name='mem[" + i + "][sh]' type='checkbox' value='1' id='CNOA_USER_DISK_MGRPUBPERMITMEMBER_SHARE" + i + "' " + a + " " + c + " /></label>"
    },
    uploadfile: function(i, g, d, h, e, b) {
        var j = d.json,
            f = j.extend == "1" ? "extend='1'": "extend='0'",
            a = j.up == "1" ? " checked='checked' ": "",
            c = j.extend == "1" ? "disabled='true'": "";
        return "<label><input " + f + " row='CNOA_USER_DISK_MGRPUBPERMITMEMBER_ROW" + h + "' name='mem[" + i + "][up]' type='checkbox' value='1' id='CNOA_USER_DISK_MGRPUBPERMITMEMBER_UPLOAD" + i + "' " + a + " " + c + " /></label>"
    },
    deletefile: function(i, g, d, h, e, b) {
        var j = d.json,
            f = j.extend == "1" ? "extend='1'": "extend='0'",
            a = j.dt == "1" ? " checked='checked' ": "",
            c = j.extend == "1" ? "disabled='true'": "";
        return "<label><input " + f + " row='CNOA_USER_DISK_MGRPUBPERMITMEMBER_ROW" + h + "' name='mem[" + i + "][dt]' type='checkbox' value='1' id='CNOA_USER_DISK_MGRPUBPERMITMEMBER_DELETE" + i + "' " + a + " " + c + " /></label>"
    },
    selectall: function(i, g, d, h, e, b) {
        var j = d.json,
            f = j.extend == "1" ? "extend='1'": "extend='0'",
            a = j.mgr == "1" ? " checked='checked' ": "",
            c = j.extend == "1" ? "disabled='true'": "";
        return "<label><input type='checkbox' onclick='CNOA_know_know_mapPermitMember.selectall2(" + h + ",this)' name='mem[" + i + "][mgr]' value='1' id='CNOA_USER_DISK_MGRPUBPERMITMEMBER_ALL" + i + "' " + a + " " + c + " /></label>"
    },
    selectall2: function(c, b) {
        var a = Ext.query("input[row=CNOA_USER_DISK_MGRPUBPERMITMEMBER_ROW" + c + "]");
        Ext.each(a,
            function(d, e) {
                d.checked = b.checked
            })
    },
    operate: function(d, f, a, e) {
        var b = a.json;
        if (b.extend != "1") {
            return "<a href='javascript:void(0)' onclick='CNOA_know_know_mapPermitMember.deleteData(" + d + ", " + e + ")'><span class='cnoa_color_red'>" + lang("del") + "</span></a>"
        }
    },
    deleteData: function(a, c) {
        var b = this;
        CNOA.msg.cf(lang("confirmToDelete"),
            function(d) {
                if (d == "yes") {
                    Ext.Ajax.request({
                        url: b.baseUrl + "&task=deletePermitM&pid=" + a,
                        method: "POST",
                        params: {
                            fid: b.fid
                        },
                        success: function(g, f) {
                            var e = Ext.decode(g.responseText);
                            if (e.success === true) {
                                b.store.reload()
                            } else {
                                CNOA.msg.alert(e.msg,
                                    function() {})
                            }
                        },
                        failure: function(e, f) {
                            CNOA.msg.alert(result.msg,
                                function() {
                                    b.coststore.reload()
                                })
                        }
                    })
                }
            })
    },
    submit: function(a) {
        var b = this;
        b.mainPanel.getForm().submit({
            url: b.baseUrl + "&task=addPermitDataM",
            method: "POST",
            params: {
                fid: a
            },
            success: function(c, d) {
                CNOA.msg.notice(d.result.msg, lang("knowMgr"));
                b.store.reload()
            }.createDelegate(this),
            failure: function(c, d) {
                CNOA.msg.alert(d.result.msg)
            }.createDelegate(this)
        })
    },
    add: function(e) {
        var f = this;
        var b = Ext.id();
        var a = Ext.id();
        var c = new Ext.form.FormPanel({
            bodyStyle: "padding:10px;",
            border: false,
            waitMsgTarget: true,
            labelWidth: 50,
            labelAlign: "right",
            items: [{
                xtype: "hidden",
                id: b
            },
                {
                    xtype: "textarea",
                    fieldLabel: lang("setPeople"),
                    height: 80,
                    width: 350,
                    readOnly: true,
                    id: a
                },
                {
                    xtype: "btnForPoepleSelector",
                    text: lang("select"),
                    dataUrl: f.baseUrl + "&task=getAllUserListsInPermitDeptTree",
                    style: "margin-left:50px; margin-top:10px",
                    listeners: {
                        selected: function(j, k) {
                            var l = new Array();
                            var h = new Array();
                            if (k.length > 0) {
                                for (var g = 0; g < k.length; g++) {
                                    l.push(k[g].uname);
                                    h.push(k[g].uid)
                                }
                            }
                            Ext.getCmp(a).setValue(l.join(","));
                            Ext.getCmp(b).setValue(h.join(","))
                        },
                        onrender: function(g) {}
                    }
                }]
        });
        var d = new Ext.Window({
            title: lang("addUser"),
            width: 500,
            height: 250,
            modal: true,
            resizable: false,
            items: c,
            layout: "fit",
            buttons: [{
                text: lang("add"),
                iconCls: "icon-btn-save",
                handler: function() {
                    var g = Ext.getCmp(b).getValue();
                    Ext.Ajax.request({
                        url: f.baseUrl + "&task=addPermitM",
                        method: "POST",
                        params: {
                            fid: e,
                            uid: g
                        },
                        success: function(i) {
                            var h = Ext.decode(i.responseText);
                            if (h.success === true) {
                                d.close();
                                f.store.reload()
                            } else {
                                CNOA.msg.alert(h.msg,
                                    function() {})
                            }
                        }
                    })
                }.createDelegate(this)
            },
                {
                    text: lang("cancel"),
                    iconCls: "icon-dialog-cancel",
                    handler: function() {
                        d.close()
                    }
                }]
        }).show()
    }
};
CNOA_know_know_mapPermitDeptmentClass = CNOA.Class.create();
CNOA_know_know_mapPermitDeptmentClass.prototype = {
    init: function(b) {
        var c = this;
        var a = Ext.id();
        this.fid = b;
        this.submitBtnID = Ext.id();
        this.baseUrl = "index.php?app=know&func=know&action=map";
        this.fields = [{
            name: "pid"
        },
            {
                name: "uid"
            },
            {
                name: "name"
            }];
        this.store = new Ext.data.GroupingStore({
            proxy: new Ext.data.HttpProxy({
                url: this.baseUrl + "&task=permitListByS&fid=" + b
            }),
            reader: new Ext.data.JsonReader({
                totalProperty: "total",
                root: "data",
                fields: this.fields
            }),
            listeners: {
                load: function(g, d, e) {
                    $.each(["VIEW", "EDIT", "MOVE", "DOWNLOAD", "SHARE", "UPLOAD", "DELETE"],
                        function(i, j) {
                            Ext.fly("CNOA_DEPT_DISK_SELLECTALL_" + j).on("click",
                                function() {
                                    var k = this.dom.checked;
                                    Ext.select("input[id^=CNOA_USER_DISK_MGRPUBPERMITDEPT_" + j + "]").each(function() {
                                        if (!$(this.dom).attr("disabled")) {
                                            this.dom.checked = k
                                        }
                                    })
                                })
                        });
                    var f = Ext.fly("CNOA_DEPT_DISK_SELLECTALL_MGR").on("click",
                        function() {
                            var i = this.dom.checked;
                            Ext.select("input[id^=CNOA_USER_DISK_MGRPUBPERMITDEPT_VIEW]").each(function() {
                                if (!$(this.dom).attr("disabled")) {
                                    this.dom.checked = i
                                }
                            });
                            Ext.select("input[id^=CNOA_USER_DISK_MGRPUBPERMITDEPT_EDIT]").each(function() {
                                if (!$(this.dom).attr("disabled")) {
                                    this.dom.checked = i
                                }
                            });
                            Ext.select("input[id^=CNOA_USER_DISK_MGRPUBPERMITDEPT_MOVE]").each(function() {
                                if (!$(this.dom).attr("disabled")) {
                                    this.dom.checked = i
                                }
                            });
                            Ext.select("input[id^=CNOA_USER_DISK_MGRPUBPERMITDEPT_DOWNLOAD]").each(function() {
                                if (!$(this.dom).attr("disabled")) {
                                    this.dom.checked = i
                                }
                            });
                            Ext.select("input[id^=CNOA_USER_DISK_MGRPUBPERMITDEPT_SHARE]").each(function() {
                                if (!$(this.dom).attr("disabled")) {
                                    this.dom.checked = i
                                }
                            });
                            Ext.select("input[id^=CNOA_USER_DISK_MGRPUBPERMITDEPT_UPLOAD]").each(function() {
                                if (!$(this.dom).attr("disabled")) {
                                    this.dom.checked = i
                                }
                            });
                            Ext.select("input[id^=CNOA_USER_DISK_MGRPUBPERMITDEPT_DELETE]").each(function() {
                                if (!$(this.dom).attr("disabled")) {
                                    this.dom.checked = i
                                }
                            });
                            Ext.select("input[id^=CNOA_USER_DISK_MGRPUBPERMITDEPT_ALL]").each(function() {
                                if (!$(this.dom).attr("disabled")) {
                                    this.dom.checked = i
                                }
                            })
                        });
                    var h = 0;
                    Ext.each(d,
                        function(i) {
                            if (i.json.extend == "1") {
                                h++
                            }
                        });
                    if (g.getCount() > h) {
                        Ext.getCmp(c.submitBtnID).enable()
                    }
                }
            }
        });
        this.sm = new Ext.grid.CheckboxSelectionModel({
            singleSelect: true
        });
        this.store.load();
        this.cm = [new Ext.grid.RowNumberer(), {
            header: "sid",
            dataIndex: "pid",
            hidden: true
        },
            {
                header: lang("deptName"),
                dataIndex: "name",
                width: 100,
                sortable: true
            },
            {
                header: "<input type='checkbox' id='CNOA_DEPT_DISK_SELLECTALL_VIEW'>" + lang("permit4View"),
                dataIndex: "pid",
                width: 100,
                sortable: false,
                menuDisabled: true,
                renderer: this.viewfile.createDelegate(this)
            },
            {
                header: "<input type='checkbox' id='CNOA_DEPT_DISK_SELLECTALL_EDIT'>" + lang("permit4Edit"),
                dataIndex: "pid",
                width: 100,
                sortable: false,
                menuDisabled: true,
                renderer: this.editfile.createDelegate(this)
            },
            {
                header: "<input type='checkbox' id='CNOA_DEPT_DISK_SELLECTALL_MOVE'>" + lang("knowTransQx"),
                dataIndex: "pid",
                width: 100,
                sortable: false,
                menuDisabled: true,
                renderer: this.movefile.createDelegate(this)
            },
            {
                header: "<input type='checkbox' id='CNOA_DEPT_DISK_SELLECTALL_DOWNLOAD'>" + lang("permit4Down"),
                dataIndex: "pid",
                width: 80,
                sortable: false,
                menuDisabled: true,
                renderer: this.downloadfile.createDelegate(this)
            },
            {
                header: "<input type='checkbox' id='CNOA_DEPT_DISK_SELLECTALL_SHARE'>" + lang("permit4Share"),
                dataIndex: "pid",
                width: 80,
                sortable: false,
                menuDisabled: true,
                renderer: this.sharefile.createDelegate(this)
            },
            {
                header: "<input type='checkbox' id='CNOA_DEPT_DISK_SELLECTALL_UPLOAD'>" + lang("knowFbQx"),
                dataIndex: "pid",
                width: 100,
                sortable: false,
                menuDisabled: true,
                renderer: this.uploadfile.createDelegate(this)
            },
            {
                header: "<input type='checkbox' id='CNOA_DEPT_DISK_SELLECTALL_DELETE'>" + lang("permit4Del"),
                dataIndex: "pid",
                width: 80,
                sortable: false,
                menuDisabled: true,
                renderer: this.deletefile.createDelegate(this)
            },
            {
                header: "<input type='checkbox' id='CNOA_DEPT_DISK_SELLECTALL_MGR'>" + lang("permit4Mgr"),
                dataIndex: "pid",
                width: 80,
                sortable: false,
                menuDisabled: true,
                renderer: this.selectall.createDelegate(this)
            },
            {
                header: lang("opt"),
                dataIndex: "pid",
                width: 160,
                sortable: true,
                menuDisabled: true,
                renderer: this.operate.createDelegate(this)
            },
            {
                header: "",
                dataIndex: "",
                width: 1,
                menuDisabled: true,
                resizable: false
            }];
        this.grid = new Ext.grid.GridPanel({
            border: false,
            layout: "fit",
            autoScroll: true,
            store: this.store,
            loadMask: {
                msg: lang("waiting")
            },
            columns: this.cm,
            sm: this.sm,
            stripeRows: true,
            hideBorders: true,
            listeners: {
                cellclick: function(d, h, g, f) {}
            },
            tbar: [{
                text: lang("refresh"),
                iconCls: "icon-system-refresh",
                handler: function(d) {
                    c.store.reload()
                }
            },
                {
                    text: lang("addDept"),
                    iconCls: "icon-utils-s-add",
                    cls: "btn-blue4",
                    handler: function() {
                        c.add(b)
                    }
                },
                {
                    text: "&nbsp;&nbsp;" + lang("submit") + "&nbsp;&nbsp;",
                    iconCls: "icon-pass",
                    style: "margin-left:5px",
                    cls: "btn-blue4",
                    id: this.submitBtnID,
                    disabled: true,
                    handler: function() {
                        c.submit(b)
                    }
                }]
        });
        this.mainPanel = new Ext.form.FormPanel({
            collapsible: false,
            hideBorders: true,
            border: false,
            layout: "fit",
            autoScroll: false,
            items: [this.grid]
        })
    },
    viewfile: function(i, g, d, h, e, b) {
        var j = d.json,
            f = j.extend == "1" ? "extend='1'": "extend='0'",
            a = j.vi == "1" ? " checked='checked' ": "",
            c = j.extend == "1" ? "disabled='true'": "";
        return "<label><input " + f + " row='CNOA_USER_DISK_MGRPUBPERMITDEPT_ROW" + h + "' name='dept[" + i + "][vi]' type='checkbox' value='1' id='CNOA_USER_DISK_MGRPUBPERMITDEPT_VIEW" + i + "' " + a + " " + c + " /></label>"
    },
    editfile: function(i, g, d, h, e, b) {
        var j = d.json,
            f = j.extend == "1" ? "extend='1'": "extend='0'",
            a = j.ed == "1" ? " checked='checked' ": "",
            c = j.extend == "1" ? "disabled='true'": "";
        return "<label><input " + f + " row='CNOA_USER_DISK_MGRPUBPERMITDEPT_ROW" + h + "' name='dept[" + i + "][ed]' type='checkbox' value='1' id='CNOA_USER_DISK_MGRPUBPERMITDEPT_EDIT" + i + "' " + a + " " + c + " /></label>"
    },
    movefile: function(i, g, d, h, e, b) {
        var j = d.json,
            f = j.extend == "1" ? "extend='1'": "extend='0'",
            a = j.mv == "1" ? " checked='checked' ": "",
            c = j.extend == "1" ? "disabled='true'": "";
        return "<label><input " + f + " row='CNOA_USER_DISK_MGRPUBPERMITDEPT_ROW" + h + "' name='dept[" + i + "][mv]' type='checkbox' value='1' id='CNOA_USER_DISK_MGRPUBPERMITDEPT_MOVE" + i + "' " + a + " " + c + " /></label>"
    },
    downloadfile: function(i, g, d, h, e, b) {
        var j = d.json,
            f = j.extend == "1" ? "extend='1'": "extend='0'",
            a = j.dl == "1" ? " checked='checked' ": "",
            c = j.extend == "1" ? "disabled='true'": "";
        return "<label><input " + f + " row='CNOA_USER_DISK_MGRPUBPERMITDEPT_ROW" + h + "' name='dept[" + i + "][dl]' type='checkbox' value='1' id='CNOA_USER_DISK_MGRPUBPERMITDEPT_DOWNLOAD" + i + "' " + a + " " + c + " /></label>"
    },
    sharefile: function(i, g, d, h, e, b) {
        var j = d.json,
            f = j.extend == "1" ? "extend='1'": "extend='0'",
            a = j.sh == "1" ? " checked='checked' ": "",
            c = j.extend == "1" ? "disabled='true'": "";
        return "<label><input " + f + " row='CNOA_USER_DISK_MGRPUBPERMITDEPT_ROW" + h + "' name='dept[" + i + "][sh]' type='checkbox' value='1' id='CNOA_USER_DISK_MGRPUBPERMITDEPT_SHARE" + i + "' " + a + " " + c + " /></label>"
    },
    uploadfile: function(i, g, d, h, e, b) {
        var j = d.json,
            f = j.extend == "1" ? "extend='1'": "extend='0'",
            a = j.up == "1" ? " checked='checked' ": "",
            c = j.extend == "1" ? "disabled='true'": "";
        return "<label><input " + f + " row='CNOA_USER_DISK_MGRPUBPERMITDEPT_ROW" + h + "' name='dept[" + i + "][up]' type='checkbox' value='1' id='CNOA_USER_DISK_MGRPUBPERMITDEPT_UPLOAD" + i + "' " + a + " " + c + " /></label>"
    },
    deletefile: function(i, g, d, h, e, b) {
        var j = d.json,
            f = j.extend == "1" ? "extend='1'": "extend='0'",
            a = j.dt == "1" ? " checked='checked' ": "",
            c = j.extend == "1" ? "disabled='true'": "";
        return "<label><input " + f + " row='CNOA_USER_DISK_MGRPUBPERMITDEPT_ROW" + h + "' name='dept[" + i + "][dt]' type='checkbox' value='1' id='CNOA_USER_DISK_MGRPUBPERMITDEPT_DELETE" + i + "' " + a + " " + c + " /></label>"
    },
    selectall: function(i, g, d, h, e, b) {
        var j = d.json,
            f = j.extend == "1" ? "extend='1'": "extend='0'",
            a = j.mgr == "1" ? " checked='checked' ": "",
            c = j.extend == "1" ? "disabled='true'": "";
        return "<label><input " + f + " type='checkbox' onclick='CNOA_know_know_mapPermitDeptment.selectall2(" + h + ",this)' name='dept[" + i + "][mgr]' value='1' id='CNOA_USER_DISK_MGRPUBPERMITDEPT_ALL" + i + "' " + a + " " + c + "/></label>"
    },
    selectall2: function(c, b) {
        var a = Ext.query("input[row^=CNOA_USER_DISK_MGRPUBPERMITDEPT_ROW" + c + "]");
        Ext.each(a,
            function(d, e) {
                d.checked = b.checked
            })
    },
    operate: function(d, f, a) {
        var b = a.json;
        var e = this.fid;
        if (b.extend != "1") {
            return "<a href='javascript:void(0)' onclick='CNOA_know_know_mapPermitDeptment.deleteData(" + d + "," + e + ")'><span class='cnoa_color_red'>" + lang("del") + "</span></a>"
        }
    },
    deleteData: function(a, b) {
        var c = this;
        CNOA.msg.cf(lang("confirmToDelete"),
            function(d) {
                if (d == "yes") {
                    Ext.Ajax.request({
                        url: c.baseUrl + "&task=deletePermitS&sid=" + a,
                        method: "POST",
                        params: {
                            fid: b
                        },
                        success: function(g, f) {
                            var e = Ext.decode(g.responseText);
                            if (e.success === true) {
                                c.store.reload()
                            } else {
                                CNOA.msg.alert(e.msg,
                                    function() {})
                            }
                        },
                        failure: function(e, f) {
                            CNOA.msg.alert(result.msg,
                                function() {
                                    c.coststore.reload()
                                })
                        }
                    })
                }
            })
    },
    submit: function(a) {
        var b = this;
        b.mainPanel.getForm().submit({
            url: b.baseUrl + "&task=addPermitDataS",
            method: "POST",
            params: {
                fid: a
            },
            success: function(c, d) {
                CNOA.msg.notice(d.result.msg, lang("knowMgr"));
                b.store.reload()
            }.createDelegate(this),
            failure: function(c, d) {
                CNOA.msg.alert(d.result.msg)
            }.createDelegate(this)
        })
    },
    add: function(d) {
        var e = this;
        var a = Ext.id();
        var b = new Ext.form.FormPanel({
            bodyStyle: "padding:10px;",
            border: false,
            waitMsgTarget: true,
            labelWidth: 80,
            labelAllign: "right",
            items: [{
                xtype: "hidden",
                name: "deptIds",
                id: a
            },
                {
                    xtype: "textarea",
                    width: 300,
                    height: 50,
                    fieldLabel: lang("inDepartment"),
                    name: "deptNames",
                    readOnly: true
                },
                {
                    xtype: "deptMultipleSelector",
                    style: "margin-left:85px; margin-bottom:4px; margin-top:5px;",
                    deptListUrl: e.baseUrl + "&task=getStructTree",
                    listeners: {
                        selected: function(h, f, g) {
                            b.getForm().findField("deptNames").setValue(f);
                            b.getForm().findField("deptIds").setValue(g)
                        },
                        load: function(f) {}
                    }
                }]
        });
        var c = new Ext.Window({
            title: lang("addDept"),
            width: 430,
            height: 200,
            modal: true,
            resizable: false,
            items: b,
            layout: "fit",
            buttons: [{
                text: lang("add"),
                iconCls: "icon-btn-save",
                handler: function() {
                    var f = Ext.getCmp(a).getValue();
                    Ext.Ajax.request({
                        url: e.baseUrl + "&task=addPermitS",
                        method: "POST",
                        params: {
                            fid: d,
                            did: f
                        },
                        success: function(h) {
                            var g = Ext.decode(h.responseText);
                            if (g.success === true) {
                                c.close();
                                e.store.reload()
                            } else {
                                CNOA.msg.alert(g.msg,
                                    function() {})
                            }
                        }
                    })
                }.createDelegate(this)
            },
                {
                    text: lang("cancel"),
                    iconCls: "icon-dialog-cancel",
                    handler: function() {
                        c.close()
                    }
                }]
        }).show()
    }
};
var CNOA_know_know_dingyeClass, CNOA_know_know_dingye;
CNOA_know_know_dingyeClass = CNOA.Class.create();
CNOA_know_know_dingyeClass.prototype = {
    init: function() {
        var a = this;
        this.baseUrl = "index.php?app=know&func=know&action=dy";
        this.starttime = Ext.id();
        this.endtime = Ext.id();
        this.title = Ext.id();
        this.auth = Ext.id();
        this.cate = Ext.id();
        this.cateStore = new Ext.data.Store({
            proxy: new Ext.data.HttpProxy({
                url: a.baseUrl + "&task=getCateCombo",
                disableCaching: true
            }),
            reader: new Ext.data.JsonReader({
                fields: ["cid", "name"]
            })
        });
        this.cateStore.load();
        this.dingyueList = this.dingyueList();
        this.readedList = this.readedList();
        this.dySet = this.dySet();
        this.mainPanel = new Ext.ux.VerticalTabPanel({
            region: "center",
            border: false,
            tabPosition: "left",
            tabWidth: 100,
            activeItem: 0,
            deferredRender: true,
            layoutOnTabChange: true,
            items: [this.dingyueList, this.readedList, this.dySet],
            listeners: {}
        })
    },
    dingyueList: function() {
        var g = this;
        this.wfields = [{
            name: "kid"
        },
            {
                name: "title"
            },
            {
                name: "cate"
            },
            {
                name: "auth"
            },
            {
                name: "poster"
            },
            {
                name: "posttime"
            },
            {
                name: "keyword"
            }];
        this.storeBar = {
            stime: "",
            etime: "",
            auth: "",
            title: "",
            cate: "",
            type: "noReaded"
        };
        this.wstore = new Ext.data.Store({
            autoLoad: true,
            baseParams: g.storeBar,
            proxy: new Ext.data.HttpProxy({
                url: g.baseUrl + "&task=getList"
            }),
            reader: new Ext.data.JsonReader({
                idProperty: "",
                totalProperty: "total",
                root: "data",
                fields: g.wfields
            }),
            listeners: {
                exception: function(m, l, n, k, j, i) {
                    var h = Ext.decode(j.responseText);
                    if (h.failure) {
                        CNOA.msg.alert(h.msg)
                    }
                }
            }
        });
        var b = Ext.id();
        var f = new Ext.grid.CheckboxSelectionModel({
            singleSelect: false
        });
        var a = new Ext.grid.ColumnModel([f, {
            header: "kid",
            dataIndex: "kid",
            width: 1,
            sortable: true,
            hidden: true
        },
            {
                header: lang("title"),
                dataIndex: "title",
                sortable: true,
                id: b
            },
            {
                header: lang("knowType"),
                dataIndex: "cate",
                width: 150,
                sortable: true
            },
            {
                header: lang("author"),
                dataIndex: "auth",
                width: 120,
                sortable: true
            },
            {
                header: lang("posters"),
                dataIndex: "poster",
                width: 150,
                sortable: true
            },
            {
                header: lang("publish"),
                dataIndex: "posttime",
                width: 150,
                sortable: true
            },
            {
                header: lang("keyword2"),
                dataIndex: "keyword",
                width: 150,
                sortable: true
            },
            {
                header: lang("opt"),
                dataIndex: "noIndex",
                width: 60,
                sortable: true,
                renderer: g.makeOpt
            }]);
        var d = new Ext.grid.GridPanel({
            store: g.wstore,
            loadMask: {
                msg: lang("waiting")
            },
            cm: a,
            sm: f,
            hideBorders: true,
            border: false,
            stripeRows: true,
            layout: "fit",
            autoExpandColumn: b,
            listeners: {
                rowdblclick: function(i, j) {
                    var h = i.getStore().getAt(j).get("kid");
                    g.view(h)
                }.createDelegate(this)
            },
            tbar: new Ext.Toolbar({
                items: [{
                    text: lang("refresh"),
                    tooltip: lang("refresh"),
                    iconCls: "icon-system-refresh",
                    handler: function() {
                        g.wstore.load()
                    }
                },
                    {
                        text: lang("readed"),
                        tooltip: lang("setReaded"),
                        iconCls: "icon-dialog-apply2",
                        handler: function(k, m) {
                            var n = d.getSelectionModel().getSelections();
                            if (n.length == 0) {
                                var h = this.getEl().getBox();
                                h = [h.x + 12, h.y + 26];
                                CNOA.miniMsg.alert(lang("mustSelectOneRow"), h)
                            } else {
                                if (n) {
                                    var l = "";
                                    for (var j = 0; j < n.length; j++) {
                                        l += n[j].get("kid") + ","
                                    }
                                }
                                Ext.Ajax.request({
                                    url: g.baseUrl + "&task=setReaded",
                                    method: "POST",
                                    params: {
                                        ids: l
                                    },
                                    success: function(o) {
                                        var i = Ext.decode(o.responseText);
                                        if (i.success === true) {
                                            g.wstore.load();
                                            g.ystore.load()
                                        }
                                    }
                                })
                            }
                        }
                    },
                    ("<span style='color:#999'>" + lang("dbClickKnowDetail") + "</span>")]
            })
        });
        var e = new Ext.PagingToolbar({
            displayInfo: true,
            emptyMsg: lang("pagingToolbarEmptyMsg"),
            displayMsg: lang("showDataTotal2"),
            store: g.wstore,
            pageSize: 50,
            plugins: [new Ext.grid.plugins.ComboPageSize()]
        });
        var c = new Ext.Panel({
            title: lang("notReadKnow"),
            layout: "fit",
            bbar: e,
            tbar: new Ext.Toolbar({
                items: [lang("publish") + ":", {
                    xtype: "datefield",
                    width: 100,
                    format: "Y-m-d",
                    id: g.starttime
                },
                    "&nbsp;" + lang("to") + "&nbsp;", {
                        xtype: "datefield",
                        width: 100,
                        format: "Y-m-d",
                        id: g.endtime
                    },
                    "&nbsp;&nbsp;" + lang("author") + ":", {
                        xtype: "textfield",
                        width: 100,
                        id: g.auth
                    },
                    "&nbsp;&nbsp;" + lang("title") + ":", {
                        xtype: "textfield",
                        width: 100,
                        id: g.title
                    },
                    "&nbsp;&nbsp;" + lang("type") + ":", {
                        xtype: "combo",
                        id: g.cate,
                        editable: false,
                        width: 100,
                        store: g.cateStore,
                        valueField: "cid",
                        displayField: "name",
                        mode: "local",
                        triggerAction: "all",
                        forceSelection: true
                    },
                    {
                        xtype: "button",
                        text: lang("search"),
                        handler: function(h) {
                            g.doSearch()
                        }
                    },
                    {
                        xtype: "button",
                        text: lang("clear"),
                        handler: function(h) {
                            g.emptySearch()
                        }
                    }]
            }),
            items: [d]
        });
        return c
    },
    readedList: function() {
        var g = this;
        this.yfields = [{
            name: "kid"
        },
            {
                name: "title"
            },
            {
                name: "cate"
            },
            {
                name: "auth"
            },
            {
                name: "poster"
            },
            {
                name: "posttime"
            },
            {
                name: "keyword"
            },
            {
                name: "readedTime"
            }];
        this.ystore = new Ext.data.Store({
            autoLoad: true,
            baseParams: {
                type: "readed"
            },
            proxy: new Ext.data.HttpProxy({
                url: g.baseUrl + "&task=getList"
            }),
            reader: new Ext.data.JsonReader({
                idProperty: "",
                totalProperty: "total",
                root: "data",
                fields: g.yfields
            }),
            listeners: {
                exception: function(m, l, n, k, j, i) {
                    var h = Ext.decode(j.responseText);
                    if (h.failure) {
                        CNOA.msg.alert(h.msg)
                    }
                }
            }
        });
        var b = Ext.id();
        var f = new Ext.grid.CheckboxSelectionModel({
            singleSelect: false
        });
        var a = new Ext.grid.ColumnModel([f, {
            header: "kid",
            dataIndex: "kid",
            width: 1,
            sortable: true,
            hidden: true
        },
            {
                header: lang("title"),
                dataIndex: "title",
                sortable: true,
                id: b
            },
            {
                header: lang("knowType"),
                dataIndex: "cate",
                width: 150,
                sortable: true
            },
            {
                header: lang("author"),
                dataIndex: "auth",
                width: 120,
                sortable: true
            },
            {
                header: lang("posters"),
                dataIndex: "poster",
                width: 150,
                sortable: true
            },
            {
                header: lang("publish"),
                dataIndex: "posttime",
                width: 150,
                sortable: true
            },
            {
                header: lang("keyword2"),
                dataIndex: "keyword",
                width: 150,
                sortable: true
            },
            {
                header: lang("readedTime"),
                dataIndex: "readedTime",
                width: 150,
                sortable: true
            },
            {
                header: lang("opt"),
                dataIndex: "noIndex",
                width: 60,
                sortable: true,
                renderer: g.makeOpt2
            }]);
        var d = new Ext.grid.GridPanel({
            store: g.ystore,
            loadMask: {
                msg: lang("waiting")
            },
            cm: a,
            sm: f,
            hideBorders: true,
            border: false,
            stripeRows: true,
            layout: "fit",
            autoExpandColumn: b,
            listeners: {
                rowdblclick: function(i, j) {
                    var h = i.getStore().getAt(j).get("kid");
                    g.view(h)
                }.createDelegate(this)
            }
        });
        var e = new Ext.PagingToolbar({
            displayInfo: true,
            emptyMsg: lang("pagingToolbarEmptyMsg"),
            displayMsg: lang("showDataTotal2"),
            store: g.ystore,
            pageSize: 50,
            plugins: [new Ext.grid.plugins.ComboPageSize()]
        });
        var c = new Ext.Panel({
            title: lang("readedKnow"),
            layout: "fit",
            bbar: e,
            tbar: new Ext.Toolbar({
                items: [{
                    text: lang("refresh"),
                    tooltip: lang("refresh"),
                    iconCls: "icon-system-refresh",
                    handler: function() {
                        g.ystore.reload()
                    }
                },
                    {
                        text: lang("del"),
                        iconCls: "icon-utils-s-delete",
                        tooltip: lang("del"),
                        handler: function(i, j) {
                            var k = d.getSelectionModel().getSelections();
                            if (k.length == 0) {
                                var h = this.getEl().getBox();
                                h = [h.x + 12, h.y + 26];
                                CNOA.miniMsg.alert(lang("mustSelectOneRow"), h)
                            } else {
                                CNOA.msg.cf(lang("confirmToDelete"),
                                    function(m) {
                                        if (m == "yes") {
                                            if (k) {
                                                var n = "";
                                                for (var l = 0; l < k.length; l++) {
                                                    n += k[l].get("kid") + ","
                                                }
                                            }
                                            Ext.Ajax.request({
                                                url: g.baseUrl + "&task=deleteReaded",
                                                method: "POST",
                                                params: {
                                                    ids: n
                                                },
                                                success: function(p) {
                                                    var o = Ext.decode(p.responseText);
                                                    if (o.success === true) {
                                                        CNOA.msg.alert(o.msg,
                                                            function() {
                                                                g.ystore.reload()
                                                            })
                                                    } else {
                                                        CNOA.msg.alert(o.msg)
                                                    }
                                                }
                                            })
                                        }
                                    })
                            }
                        }
                    },
                    "->", ("<span style='color:#999'>" + lang("dbClickKnowDetail") + "</span>")]
            }),
            items: [d]
        });
        return c
    },
    dySet: function() {
        var c = this;
        this.ID_btn_save = Ext.id();
        var b = new Ext.form.FormPanel({
            border: false,
            items: [{
                xtype: "fieldset",
                title: lang("readedConf"),
                buttonAlign: "left",
                items: [{
                    xtype: "textarea",
                    width: 500,
                    fieldLabel: lang("SubType"),
                    name: "dyCate",
                    id: "dyCate",
                    onFocus: function() {
                        c.chooseDyCate()
                    }
                },
                    {
                        xtype: "hidden",
                        name: "dyCateField",
                        id: "dyCateField"
                    },
                    {
                        xtype: "checkbox",
                        fieldLabel: lang("sendNotice"),
                        name: "isNotice",
                        id: "isNotice",
                        inputValue: "1",
                        checked: true,
                        boxLabel: "<span style='color:#676767'>" + lang("newKnowNotify") + "</span>"
                    },
                    {
                        xtype: "checkbox",
                        fieldLabel: lang("autoReaded"),
                        name: "autoReaded",
                        id: "autoReaded",
                        checked: false,
                        boxLabel: "<span style='color:#676767'>" + lang("notReadAutoReaded") + "</span>"
                    }]
            }]
        });
        var a = new Ext.Panel({
            title: lang("readedConf"),
            items: [b],
            tbar: new Ext.Toolbar({
                items: [{
                    text: lang("save"),
                    iconCls: "icon-btn-save",
                    cls: "btn-blue3",
                    handler: function() {
                        Ext.Ajax.request({
                            url: c.baseUrl + "&task=readSet",
                            method: "POST",
                            params: {
                                cateId: Ext.getCmp("dyCateField").getValue(),
                                isNotice: Ext.getCmp("isNotice").getValue() ? 1 : 0,
                                autoReaded: Ext.getCmp("autoReaded").getValue() ? 1 : 0
                            },
                            success: function(e) {
                                var d = Ext.decode(e.responseText);
                                CNOA.msg.alert(d.msg);
                                c.storeBar.cate = "";
                                Ext.getCmp(c.cate).setValue("");
                                c.cateStore.load();
                                c.wstore.load()
                            }
                        })
                    },
                    id: c.ID_btn_save
                }]
            })
        });
        c.showDyCate();
        return a
    },
    showDyCate: function() {
        var a = this;
        Ext.Ajax.request({
            url: a.baseUrl + "&task=showDyCate",
            method: "POST",
            success: function(c) {
                var b = Ext.decode(c.responseText);
                if (b.success) {
                    Ext.getCmp("dyCate").setValue(b.msg[1]);
                    Ext.getCmp("dyCateField").setValue(b.msg[0]);
                    if (b.msg[2] == 1) {
                        Ext.getCmp("isNotice").setValue(true)
                    } else {
                        Ext.getCmp("isNotice").setValue(false)
                    }
                    if (b.msg[3] == 1) {
                        Ext.getCmp("autoReaded").setValue(true)
                    } else {
                        Ext.getCmp("autoReaded").setValue(false)
                    }
                }
            }
        })
    },
    chooseDyCate: function() {
        var e = this;
        var b = new Ext.tree.AsyncTreeNode({
            text: lang("knowType"),
            id: "0",
            pid: "0",
            fid: "0",
            iconCls: "icon-tree-root-cnoa",
            cls: "feeds-node",
            expanded: true
        });
        var c = new Ext.tree.TreeLoader({
            dataUrl: "index.php?app=know&func=know&action=map&task=getCate&type=combo",
            preloadChildren: false,
            clearOnLoad: false,
            listeners: {
                load: function(f) {
                    b.expand()
                },
                beforeload: function(g, f, i) {
                    try {
                        g.baseParams.pid = f.attributes.fid;
                        g.baseParams.path = f.attributes.path
                    } catch(h) {}
                }
            }
        });
        var a = new Ext.tree.TreePanel({
            hideBorders: true,
            border: false,
            rootVisible: true,
            lines: true,
            containerScroll: true,
            animCollapse: false,
            animate: false,
            loader: c,
            root: b,
            autoScroll: true,
            listeners: {
                load: function() {
                    Ext.Ajax.request({
                        url: e.baseUrl + "&task=getDyItems",
                        success: function(j) {
                            var g = Ext.decode(j.responseText);
                            var h = g.split(",");
                            for (var f = 0; f < h.length; f++) {
                                var i = a.getNodeById(h[f]);
                                i.getUI().toggleCheck(true);
                                i.attributes.checked = true
                            }
                        }
                    })
                }
            }
        });
        var d = new Ext.Window({
            border: false,
            width: 320,
            height: 300,
            autoScroll: true,
            modal: true,
            layout: "fit",
            title: lang("SubKnow"),
            buttons: [{
                text: lang("save"),
                handler: function() {
                    var g = a.getChecked("id");
                    var f = a.getChecked("text");
                    Ext.getCmp("dyCate").setValue(f);
                    Ext.getCmp("dyCateField").setValue(g);
                    d.close()
                }
            },
                {
                    text: lang("cancel"),
                    handler: function() {
                        d.close()
                    }
                }],
            items: [a]
        });
        d.show()
    },
    doSearch: function() {
        var f = this;
        var d = Ext.getCmp(f.starttime).getRawValue();
        var a = Ext.getCmp(f.endtime).getRawValue();
        var c = Ext.getCmp(f.auth).getValue();
        var e = Ext.getCmp(f.title).getValue();
        var b = Ext.getCmp(f.cate).getValue();
        f.storeBar.stime = d;
        f.storeBar.etime = a;
        f.storeBar.auth = c;
        f.storeBar.title = e;
        f.storeBar.cate = b;
        f.wstore.load()
    },
    emptySearch: function() {
        var a = this;
        Ext.getCmp(a.starttime).setValue();
        Ext.getCmp(a.endtime).setValue();
        Ext.getCmp(a.auth).setValue();
        Ext.getCmp(a.title).setValue();
        Ext.getCmp(a.cate).setValue();
        a.storeBar.stime = "";
        a.storeBar.etime = "";
        a.storeBar.auth = "";
        a.storeBar.title = "";
        a.storeBar.cate = "";
        a.wstore.load()
    },
    makeOpt: function(d, b, a) {
        var c = a.data;
        return '<a href="javascript:void(0);" class="gridview" onclick="CNOA_know_know_dingye.view(' + c.kid + ')">' + lang("view") + "</a>"
    },
    view: function(a) {
        var b = this;
        Ext.Ajax.request({
            url: b.baseUrl + "&task=isAutoReaded",
            method: "POST",
            params: {
                kid: a
            },
            success: function(d) {
                var c = Ext.decode(d.responseText);
                if (c.success) {
                    b.wstore.load();
                    b.ystore.load();
                    b.view2(a)
                }
            }
        })
    },
    makeOpt2: function(d, b, a) {
        var c = a.data;
        return '<a href="javascript:void(0);" class="gridview" onclick="CNOA_know_know_dingye.view2(' + c.kid + ')">' + lang("view") + "</a>"
    },
    view2: function(a) {
        mainPanel.closeTab("CNOA_KNOW_KNOW_VIEW");
        mainPanel.loadClass("index.php?app=know&func=know&action=view&from=dy&kid=" + a, "CNOA_KNOW_KNOW_VIEW", lang("knowView"), "icon-page-view")
    }
};
var CNOA_know_know_myClass, CNOA_know_know_my;
CNOA_know_know_myClass = CNOA.Class.create();
CNOA_know_know_myClass.prototype = {
    init: function() {
        var g = this;
        this.baseUrl = "index.php?app=know&func=know&action=my";
        this.fields = [{
            name: "kid"
        },
            {
                name: "title"
            },
            {
                name: "cate"
            },
            {
                name: "posttime"
            },
            {
                name: "hits"
            },
            {
                name: "tuijian"
            },
            {
                name: "readed"
            },
            {
                name: "keyword"
            }];
        this.storeBar = {
            title: ""
        };
        this.store = new Ext.data.Store({
            autoLoad: true,
            baseParams: g.storeBar,
            proxy: new Ext.data.HttpProxy({
                url: g.baseUrl + "&task=getList"
            }),
            reader: new Ext.data.JsonReader({
                idProperty: "",
                totalProperty: "total",
                root: "data",
                fields: g.fields
            }),
            listeners: {
                exception: function(m, l, n, k, j, i) {
                    var h = Ext.decode(j.responseText);
                    if (h.failure) {
                        CNOA.msg.alert(h.msg)
                    }
                }
            }
        });
        var c = Ext.id();
        var f = new Ext.grid.CheckboxSelectionModel({
            singleSelect: false
        });
        var b = new Ext.grid.ColumnModel([f, {
            header: "kid",
            dataIndex: "kid",
            width: 1,
            sortable: true,
            hidden: true
        },
            {
                header: lang("title"),
                dataIndex: "title",
                sortable: true,
                id: c
            },
            {
                header: lang("knowType"),
                dataIndex: "cate",
                width: 150,
                sortable: true
            },
            {
                header: lang("publish"),
                dataIndex: "posttime",
                width: 150,
                sortable: true
            },
            {
                header: lang("knowClicks"),
                dataIndex: "hits",
                width: 150,
                sortable: true
            },
            {
                header: lang("knowTj"),
                dataIndex: "tuijian",
                width: 150,
                sortable: true
            },
            {
                header: lang("knowHumans"),
                dataIndex: "readed",
                width: 150,
                sortable: true
            },
            {
                header: lang("keyword2"),
                dataIndex: "keyword",
                width: 150,
                sortable: true
            },
            {
                header: lang("opt"),
                dataIndex: "noIndex",
                width: 80,
                sortable: true,
                renderer: g.makeOpt
            }]);
        this.gridPanel = new Ext.grid.GridPanel({
            store: g.store,
            loadMask: {
                msg: lang("waiting")
            },
            cm: b,
            sm: f,
            hideBorders: true,
            border: false,
            stripeRows: true,
            layout: "fit",
            autoExpandColumn: c,
            listeners: {
                rowdblclick: function(i, j) {
                    var h = i.getStore().getAt(j)
                }.createDelegate(this)
            }
        });
        var e = new Ext.PagingToolbar({
            displayInfo: true,
            emptyMsg: lang("pagingToolbarEmptyMsg"),
            displayMsg: lang("showDataTotal2"),
            store: g.store,
            pageSize: 15,
            plugins: [new Ext.grid.plugins.ComboPageSize()]
        });
        var d = Ext.id();
        var a = Ext.id();
        this.mainPanel = new Ext.Panel({
            hideBorders: true,
            border: false,
            items: [g.gridPanel],
            bbar: e,
            layout: "fit",
            tbar: new Ext.Toolbar({
                items: [{
                    text: lang("refresh"),
                    tooltip: lang("refresh"),
                    iconCls: "icon-system-refresh",
                    handler: function() {
                        g.store.load()
                    }
                },
                    {
                        text: lang("knowpost"),
                        iconCls: "icon-utils-s-add",
                        tooltip: lang("knowPublic"),
                        cls: "btn-blue3",
                        handler: function() {
                            g.editKnow("add")
                        },
                        id: d
                    },
                    {
                        text: lang("tuijianKnow"),
                        iconCls: "icon-utils-s-tuijian",
                        cls: "btn-blue4",
                        handler: function() {
                            var k = g.gridPanel.getSelectionModel().getSelections();
                            if (k.length == 0) {
                                var i = this.getEl().getBox();
                                i = [i.x + 12, i.y + 26];
                                CNOA.miniMsg.alert(lang("mustSelectOneRow"), i)
                            } else {
                                var j = "";
                                for (var h = 0; h < k.length; h++) {
                                    j += k[h].get("kid") + ","
                                }
                                g.tuijianKnow(j)
                            }
                        }
                    },
                    "->", lang("knowName") + ":", {
                        xtype: "textfield",
                        width: 120,
                        id: a
                    },
                    {
                        text: lang("search"),
                        handler: function() {
                            g.storeBar.title = Ext.getCmp(a).getValue();
                            g.store.load()
                        }
                    },
                    {
                        text: lang("clear"),
                        handler: function() {
                            g.storeBar.title = "";
                            Ext.getCmp(a).setValue();
                            g.store.load()
                        }
                    }]
            })
        })
    },
    tuijianKnow: function(b) {
        var d = this;
        var a = new Ext.form.FormPanel({
            border: false,
            bodyStyle: "padding:10px;",
            waitMsgTarget: true,
            labelWidth: 60,
            labelAlign: "left",
            items: [{
                xtype: "selectorform",
                selectorType: "dept",
                multiselect: true,
                hiddenName: "deptId",
                name: "chooseDept",
                width: 300,
                fieldLabel: lang("tuijianDept")
            },
                {
                    xtype: "displayfield",
                    value: "<span style='color:#676767;'>" + lang("emptySelectAllDept") + "</span>"
                },
                {
                    xtype: "selectorform",
                    selectorType: "user",
                    multiselect: true,
                    hiddenName: "userId",
                    name: "chooseUser",
                    width: 300,
                    fieldLabel: lang("tuijianUser")
                },
                {
                    xtype: "displayfield",
                    value: "<span style='color:#676767;'>" + lang("emptyDefaultAll") + "</span>"
                },
                {
                    xtype: "textarea",
                    fieldLabel: lang("tuijianSeason"),
                    name: "tjreason",
                    width: 300
                },
                {
                    xtype: "checkbox",
                    fieldLabel: lang("notice1"),
                    name: "isNotice",
                    inputValue: "1",
                    checked: true,
                    boxLabel: lang("tuijianNotice")
                }]
        });
        var c = new Ext.Window({
            title: lang("knowTuijian"),
            width: 400,
            height: 390,
            modal: true,
            resizable: false,
            autoScroll: true,
            items: [a],
            buttons: [{
                text: lang("tuijianKnow"),
                iconCls: "icon-btn-save",
                cls: "btn-blue4",
                handler: function() {
                    CNOA.msg.cf(lang("confirmTuijian") + "?",
                        function(g) {
                            if (g == "yes") {
                                var i = a.getForm();
                                if (i.isValid()) {
                                    var h = i.findField("deptId").getValue();
                                    var f = i.findField("userId").getValue();
                                    var j = i.findField("tjreason").getValue();
                                    var e = i.findField("isNotice").getValue() ? 1 : 0;
                                    Ext.Ajax.request({
                                        url: d.baseUrl + "&task=tuijian",
                                        method: "POST",
                                        params: {
                                            userId: f,
                                            deptId: h,
                                            isNotice: e,
                                            reason: j,
                                            ids: b
                                        },
                                        success: function(l) {
                                            var k = Ext.decode(l.responseText);
                                            if (k.success === true) {
                                                c.close();
                                                d.store.reload();
                                                CNOA.msg.alert(k.msg)
                                            } else {
                                                CNOA.msg.alert(k.msg)
                                            }
                                        }
                                    })
                                }
                            }
                        })
                }
            },
                {
                    text: lang("cancel"),
                    iconCls: "icon-dialog-cancel",
                    handler: function() {
                        c.close()
                    }
                }]
        }).show()
    },
    makeOpt: function(e, b, a) {
        var d = a.data;
        var c = '<a href="javascript:void(0);" class="gridview" onclick="CNOA_know_know_my.view(' + d.kid + ')">' + lang("view") + "</a>";
        c += '<a href="javascript:void(0);" class="gridview4 jianju" onclick="CNOA_know_know_my.editKnow(\'edit\',' + d.kid + ')">' + lang("edit") + "</a>";
        return c
    },
    view: function(a) {
        mainPanel.closeTab("CNOA_KNOW_KNOW_VIEW");
        mainPanel.loadClass("index.php?app=know&func=know&action=view&from=my&kid=" + a, "CNOA_KNOW_KNOW_VIEW", lang("knowView"), "icon-page-view")
    },
    editKnow: function(a, c) {
        var b = this;
        var c = c || 0;
        Ext.Ajax.request({
            url: "index.php?app=know&func=know&action=edit&task=getUeditorContent",
            method: "POST",
            params: {
                id: c
            },
            success: function(e) {
                var d = Ext.decode(e.responseText).content;
                var f = new CNOA_know_know_editComponentClass(this, c, d, b.store);
                f.show()
            }
        })
    }
};
var CNOA_know_know_sortClass, CNOA_know_know_sort;
CNOA_know_know_sortClass = CNOA.Class.create();
CNOA_know_know_sortClass.prototype = {
    init: function() {
        var f = this;
        this.baseUrl = "index.php?app=know&func=know&action=sort";
        this.orderStore = new Ext.data.Store({
            proxy: new Ext.data.HttpProxy({
                url: f.baseUrl + "&task=getOrder",
                disableCaching: true
            }),
            reader: new Ext.data.JsonReader({
                fields: ["order", "name"]
            })
        });
        this.orderStore.load();
        this.fields = [{
            name: "kid"
        },
            {
                name: "title"
            },
            {
                name: "keyword"
            },
            {
                name: "cate"
            },
            {
                name: "auth"
            },
            {
                name: "poster"
            },
            {
                name: "hits"
            },
            {
                name: "tuijian"
            },
            {
                name: "score"
            },
            {
                name: "posttime"
            },
            {
                name: "readed"
            }];
        this.storeBar = {
            order: 1
        };
        this.store = new Ext.data.Store({
            autoLoad: true,
            baseParams: f.storeBar,
            proxy: new Ext.data.HttpProxy({
                url: f.baseUrl + "&task=getList"
            }),
            reader: new Ext.data.JsonReader({
                idProperty: "",
                totalProperty: "total",
                root: "data",
                fields: f.fields
            }),
            listeners: {
                exception: function(l, k, m, j, i, h) {
                    var g = Ext.decode(i.responseText);
                    if (g.failure) {
                        CNOA.msg.alert(g.msg)
                    }
                }
            }
        });
        var b = Ext.id();
        var e = new Ext.grid.CheckboxSelectionModel({
            singleSelect: false
        });
        var a = new Ext.grid.ColumnModel([new Ext.grid.RowNumberer(), {
            header: "kid",
            dataIndex: "kid",
            width: 1,
            sortable: false,
            hidden: true
        },
            {
                header: lang("title"),
                dataIndex: "title",
                sortable: false,
                id: b
            },
            {
                header: lang("keyword2"),
                dataIndex: "keyword",
                width: 150,
                sortable: false
            },
            {
                header: lang("type"),
                dataIndex: "cate",
                width: 150,
                sortable: false
            },
            {
                header: lang("author"),
                dataIndex: "auth",
                width: 120,
                sortable: false
            },
            {
                header: lang("posters"),
                dataIndex: "poster",
                width: 120,
                sortable: false
            },
            {
                header: lang("knowScore"),
                dataIndex: "score",
                width: 150,
                sortable: false
            },
            {
                header: lang("knowClicks"),
                dataIndex: "hits",
                width: 150,
                sortable: false
            },
            {
                header: lang("knowTj"),
                dataIndex: "tuijian",
                width: 150,
                sortable: false
            },
            {
                header: lang("knowHumans"),
                dataIndex: "readed",
                width: 150,
                sortable: false
            },
            {
                header: lang("publish"),
                dataIndex: "posttime",
                width: 150,
                sortable: false
            }]);
        this.gridPanel = new Ext.grid.GridPanel({
            store: f.store,
            loadMask: {
                msg: lang("waiting")
            },
            cm: a,
            sm: e,
            hideBorders: true,
            border: false,
            stripeRows: true,
            layout: "fit",
            autoExpandColumn: b,
            listeners: {
                rowdblclick: function(h, i) {
                    var g = h.getStore().getAt(i).get("kid");
                    f.view(g)
                }.createDelegate(this)
            }
        });
        var d = new Ext.PagingToolbar({
            displayInfo: true,
            emptyMsg: lang("pagingToolbarEmptyMsg"),
            displayMsg: lang("showDataTotal2"),
            store: f.store,
            pageSize: 15,
            plugins: [new Ext.grid.plugins.ComboPageSize()]
        });
        var c = Ext.id();
        this.mainPanel = new Ext.Panel({
            hideBorders: true,
            border: false,
            items: [f.gridPanel],
            bbar: d,
            layout: "fit",
            tbar: new Ext.Toolbar({
                items: [{
                    text: lang("refresh"),
                    tooltip: lang("refresh"),
                    iconCls: "icon-system-refresh",
                    handler: function() {
                        f.store.load()
                    }
                },
                    "<span style='margin-left:10px;'>" + lang("according") + "</span>", {
                        xtype: "combo",
                        id: c,
                        editable: false,
                        width: 100,
                        store: f.orderStore,
                        valueField: "order",
                        displayField: "name",
                        mode: "local",
                        triggerAction: "all",
                        forceSelection: true,
                        listeners: {
                            select: function() {
                                f.storeBar.order = this.getValue();
                                f.store.load()
                            }
                        }
                    },
                    lang("order"), "<span style='color:#999;margin-left:10px;'>" + lang("dbClickKnowDetail") + "</span>"]
            })
        });
        Ext.getCmp(c).setValue(lang("latestKnow"))
    },
    view: function(a) {
        mainPanel.closeTab("CNOA_KNOW_KNOW_VIEW");
        mainPanel.loadClass("index.php?app=know&func=know&action=view&from=sort&kid=" + a, "CNOA_KNOW_KNOW_VIEW", lang("knowView"), "icon-page-view")
    }
};
var CNOA_know_know_searchClass, CNOA_know_know_search;
CNOA_know_know_searchClass = CNOA.Class.create();
CNOA_know_know_searchClass.prototype = {
    init: function() {
        var d = this;
        this.baseUrl = "index.php?app=know&func=know&action=search";
        this.fields = [{
            name: "kid"
        },
            {
                name: "title"
            },
            {
                name: "cate"
            },
            {
                name: "poster"
            },
            {
                name: "department"
            },
            {
                name: "posttime"
            }];
        this.storeBar = {
            search: ""
        };
        this.store = new Ext.data.Store({
            autoLoad: true,
            baseParams: d.storeBar,
            proxy: new Ext.data.HttpProxy({
                url: d.baseUrl + "&task=getList"
            }),
            reader: new Ext.data.JsonReader({
                idProperty: "",
                totalProperty: "total",
                root: "data",
                fields: d.fields
            }),
            listeners: {
                exception: function(j, i, k, h, g, f) {
                    var e = Ext.decode(g.responseText);
                    if (e.failure) {
                        CNOA.msg.alert(e.msg)
                    }
                }
            }
        });
        var c = new Ext.grid.CheckboxSelectionModel({
            singleSelect: false
        });
        var a = new Ext.grid.ColumnModel([new Ext.grid.RowNumberer(), {
            header: "kid",
            dataIndex: "kid",
            width: 1,
            sortable: true,
            hidden: true
        },
            {
                header: lang("title"),
                dataIndex: "title",
                sortable: true,
                width: 150
            },
            {
                header: lang("knowType"),
                dataIndex: "cate",
                width: 150,
                sortable: true
            },
            {
                header: lang("posters"),
                dataIndex: "poster",
                width: 150,
                sortable: true
            },
            {
                header: lang("department"),
                dataIndex: "department",
                width: 150,
                sortable: true
            },
            {
                header: lang("publish"),
                dataIndex: "posttime",
                width: 150,
                sortable: true
            }]);
        this.gridPanel = new Ext.grid.GridPanel({
            store: d.store,
            loadMask: {
                msg: lang("waiting")
            },
            cm: a,
            sm: c,
            hideBorders: true,
            border: false,
            stripeRows: true,
            layout: "fit",
            listeners: {
                rowdblclick: function(f, g) {
                    var e = f.getStore().getAt(g).get("kid");
                    d.view(e)
                }.createDelegate(this)
            }
        });
        var b = new Ext.PagingToolbar({
            displayInfo: true,
            emptyMsg: lang("pagingToolbarEmptyMsg"),
            displayMsg: lang("showDataTotal2"),
            store: d.store,
            pageSize: 50,
            plugins: [new Ext.grid.plugins.ComboPageSize()]
        });
        ID_text_search = Ext.id();
        this.mainPanel = new Ext.Panel({
            hideBorders: true,
            border: false,
            items: [d.gridPanel],
            bbar: b,
            layout: "fit",
            tbar: new Ext.Toolbar({
                items: [lang("FullSearch") + ": ", {
                    xtype: "textfield",
                    width: 300,
                    id: ID_text_search
                },
                    {
                        text: lang("search"),
                        cls: "btn-blue3",
                        iconCls: "icon-utils-s-big",
                        handler: function() {
                            d.fullSearch()
                        }
                    },
                    {
                        text: lang("refresh"),
                        tooltip: lang("refresh"),
                        iconCls: "icon-system-refresh",
                        handler: function() {
                            d.store.load()
                        }
                    },
                    "<span style='color:#999;margin-left:10px;'>" + lang("dbClickKnowDetail") + "</span>"]
            })
        })
    },
    fullSearch: function() {
        var b = this;
        var a = Ext.getCmp(ID_text_search).getValue();
        b.storeBar.search = a;
        b.store.load()
    },
    view: function(a) {
        mainPanel.closeTab("CNOA_KNOW_KNOW_VIEW");
        mainPanel.loadClass("index.php?app=know&func=know&action=view&from=search&kid=" + a, "CNOA_KNOW_KNOW_VIEW", lang("knowView"), "icon-page-view")
    }
};
var CNOA_know_know_mgrClass, CNOA_know_know_mgr;
CNOA_know_know_mgrClass = CNOA.Class.create();
CNOA_know_know_mgrClass.prototype = {
    init: function() {
        var a = this;
        this.baseUrl = "index.php?app=know&func=know&action=mgr";
        this.spPanel = this.spList();
        this.sharePanel = this.shareList();
        this.tuijianPanel = this.tuijianList();
        this.readedPanel = this.readedList();
        this.mainPanel = new Ext.ux.VerticalTabPanel({
            region: "center",
            border: false,
            tabPosition: "left",
            tabWidth: 100,
            activeItem: 0,
            deferredRender: true,
            layoutOnTabChange: true,
            items: [this.spPanel, this.sharePanel, this.tuijianPanel, this.readedPanel],
            listeners: {}
        })
    },
    spList: function() {
        var d = this;
        this.cateStore = new Ext.data.Store({
            proxy: new Ext.data.HttpProxy({
                url: d.baseUrl + "&task=getCate2",
                disableCaching: true
            }),
            reader: new Ext.data.JsonReader({
                fields: ["cid", "name"]
            })
        });
        this.cateStore.load();
        this.spFields = [{
            name: "kid"
        },
            {
                name: "title"
            },
            {
                name: "cate"
            },
            {
                name: "btime"
            },
            {
                name: "poster"
            },
            {
                name: "status"
            },
            {
                name: "shCode"
            }];
        this.spStoreBar = {
            cate: ""
        };
        this.spStore = new Ext.data.Store({
            autoLoad: true,
            baseParams: d.spStoreBar,
            proxy: new Ext.data.HttpProxy({
                url: d.baseUrl + "&task=getSpList"
            }),
            reader: new Ext.data.JsonReader({
                idProperty: "",
                totalProperty: "total",
                root: "data",
                fields: d.spFields
            }),
            listeners: {
                exception: function(j, i, k, h, g, f) {
                    var e = Ext.decode(g.responseText);
                    if (e.failure) {
                        CNOA.msg.alert(e.msg)
                    }
                }
            }
        });
        var c = new Ext.grid.CheckboxSelectionModel({
            singleSelect: false
        });
        var a = new Ext.grid.ColumnModel([new Ext.grid.RowNumberer(), {
            header: "kid",
            dataIndex: "kid",
            width: 1,
            sortable: false,
            hidden: true
        },
            {
                header: lang("title"),
                dataIndex: "title",
                width: 150,
                sortable: false
            },
            {
                header: lang("type"),
                dataIndex: "cate",
                width: 150,
                sortable: false
            },
            {
                header: lang("createTime"),
                dataIndex: "btime",
                width: 150,
                sortable: false
            },
            {
                header: lang("postter"),
                dataIndex: "poster",
                width: 150,
                sortable: false
            },
            {
                header: lang("opt"),
                dataIndex: "noIndex",
                width: 60,
                sortable: false,
                renderer: d.viewOpt
            },
            {
                header: lang("status"),
                dataIndex: "status",
                width: 80,
                sortable: false,
                renderer: d.getStatus
            },
            {
                header: lang("approval"),
                dataIndex: "shCode",
                width: 100,
                sortable: false,
                renderer: d.shStatus
            }]);
        this.spGridPanel = new Ext.grid.GridPanel({
            store: d.spStore,
            loadMask: {
                msg: lang("waiting")
            },
            cm: a,
            sm: c,
            hideBorders: true,
            border: false,
            stripeRows: true,
            layout: "fit",
            listeners: {
                rowdblclick: function(f, g) {
                    var e = f.getStore().getAt(g)
                }.createDelegate(this)
            }
        });
        var b = new Ext.PagingToolbar({
            displayInfo: true,
            emptyMsg: lang("pagingToolbarEmptyMsg"),
            displayMsg: lang("showDataTotal2"),
            store: d.spStore,
            pageSize: 50,
            plugins: [new Ext.grid.plugins.ComboPageSize()]
        });
        this.mySpPanel = new Ext.Panel({
            title: lang("apprKnow"),
            hideBorders: true,
            border: false,
            items: [d.spGridPanel],
            bbar: b,
            layout: "fit",
            tbar: new Ext.Toolbar({
                items: [{
                    text: lang("refresh"),
                    tooltip: lang("refresh"),
                    iconCls: "icon-system-refresh",
                    handler: function() {
                        d.spStore.load()
                    }
                },
                    "<span style='margin-left:10px;'>" + lang("apprType") + ":&nbsp;</span>", {
                        xtype: "combo",
                        editable: false,
                        width: 120,
                        store: d.cateStore,
                        valueField: "cid",
                        displayField: "name",
                        mode: "local",
                        triggerAction: "all",
                        forceSelection: true,
                        id: "sp_cate_combo",
                        listeners: {
                            select: function() {
                                d.spStoreBar.cate = this.getValue();
                                d.spStore.load()
                            }
                        }
                    }]
            })
        });
        Ext.getCmp("sp_cate_combo").setValue("---" + lang("allApprType") + "---");
        return this.mySpPanel
    },
    shareList: function() {
        var d = this;
        this.shareFields = [{
            name: "sid"
        },
            {
                name: "user"
            },
            {
                name: "kid"
            },
            {
                name: "title"
            },
            {
                name: "cate"
            },
            {
                name: "toUser"
            },
            {
                name: "toDept"
            },
            {
                name: "isNotice"
            },
            {
                name: "canView"
            },
            {
                name: "canDownload"
            },
            {
                name: "canEdit"
            },
            {
                name: "shareTime"
            }];
        this.shareStoreBar = {
            type: 1
        };
        this.shareStore = new Ext.data.Store({
            autoLoad: true,
            baseParams: d.shareStoreBar,
            proxy: new Ext.data.HttpProxy({
                url: d.baseUrl + "&task=getShareList"
            }),
            reader: new Ext.data.JsonReader({
                idProperty: "",
                totalProperty: "total",
                root: "data",
                fields: d.shareFields
            }),
            listeners: {
                exception: function(j, i, k, h, g, f) {
                    var e = Ext.decode(g.responseText);
                    if (e.failure) {
                        CNOA.msg.alert(e.msg)
                    }
                }
            }
        });
        var c = new Ext.grid.CheckboxSelectionModel({
            singleSelect: false
        });
        var a = new Ext.grid.ColumnModel([new Ext.grid.RowNumberer(), {
            header: "sid",
            dataIndex: "sid",
            width: 0,
            sortable: false,
            hidden: true
        },
            {
                header: "kid",
                dataIndex: "kid,",
                width: 0,
                sortable: false,
                hidden: true
            },
            {
                header: lang("shareKnow"),
                dataIndex: "title",
                width: 400,
                sortable: false
            },
            {
                header: lang("sharePerson"),
                dataIndex: "user",
                width: 120,
                sortable: false
            },
            {
                header: lang("knowType"),
                dataIndex: "cate",
                width: 150,
                sortable: false
            },
            {
                header: lang("shareTime"),
                dataIndex: "shareTime",
                width: 150,
                sortable: false
            },
            {
                header: lang("isNotice"),
                dataIndex: "isNotice",
                width: 70,
                sortable: false
            },
            {
                header: lang("opt"),
                dataIndex: "noIndex",
                width: 150,
                sortable: false,
                renderer: d.cancleShareAndSee
            },
            {
                header: lang("opt"),
                dataIndex: "noIndex2",
                width: 150,
                sortable: false,
                renderer: d.makeOption
            }]);
        this.shareGridPanel = new Ext.grid.GridPanel({
            store: d.shareStore,
            loadMask: {
                msg: lang("waiting")
            },
            cm: a,
            sm: c,
            hideBorders: true,
            border: false,
            stripeRows: true,
            layout: "fit",
            listeners: {
                rowdblclick: function(f, g) {
                    var e = f.getStore().getAt(g)
                }.createDelegate(this),
                beforerender: function(e) {
                    if (d.shareStoreBar.type == 1) {
                        d.shareGridPanel.getColumnModel().setHidden(4, true);
                        d.shareGridPanel.getColumnModel().setHidden(9, true);
                        d.shareGridPanel.getColumnModel().setHidden(8, false)
                    }
                    if (d.shareStoreBar.type == 2) {
                        d.shareGridPanel.getColumnModel().setHidden(8, true);
                        d.shareGridPanel.getColumnModel().setHidden(4, false);
                        d.shareGridPanel.getColumnModel().setHidden(9, false)
                    }
                }
            }
        });
        var b = new Ext.PagingToolbar({
            displayInfo: true,
            emptyMsg: lang("pagingToolbarEmptyMsg"),
            displayMsg: lang("showDataTotal2"),
            store: d.shareStore,
            pageSize: 50,
            plugins: [new Ext.grid.plugins.ComboPageSize()]
        });
        this.mySharePanel = new Ext.Panel({
            title: lang("shareKnow"),
            hideBorders: true,
            border: false,
            items: [d.shareGridPanel],
            bbar: b,
            layout: "fit",
            tbar: new Ext.Toolbar({
                items: [{
                    text: lang("refresh"),
                    tooltip: lang("refresh"),
                    iconCls: "icon-system-refresh",
                    handler: function() {
                        d.shareStore.load()
                    }
                },
                    {
                        text: lang("myShare"),
                        tooltip: lang("myShareKnow"),
                        cls: "btn-blue3",
                        handler: function() {
                            d.shareStoreBar.type = 1;
                            d.shareGridPanel.getColumnModel().setHidden(4, true);
                            d.shareGridPanel.getColumnModel().setHidden(9, true);
                            d.shareGridPanel.getColumnModel().setHidden(8, false);
                            d.shareStore.load()
                        }
                    },
                    {
                        text: lang("shareToMe"),
                        cls: "btn-red1",
                        handler: function() {
                            d.shareStoreBar.type = 2;
                            d.shareGridPanel.getColumnModel().setHidden(8, true);
                            d.shareGridPanel.getColumnModel().setHidden(4, false);
                            d.shareGridPanel.getColumnModel().setHidden(9, false);
                            d.shareStore.load()
                        }
                    }]
            })
        });
        return this.mySharePanel
    },
    tuijianList: function() {
        var d = this;
        this.tuijianFields = [{
            name: "tid"
        },
            {
                name: "kid"
            },
            {
                name: "user"
            },
            {
                name: "title"
            },
            {
                name: "cate"
            },
            {
                name: "userId"
            },
            {
                name: "deptId"
            },
            {
                name: "isNotice"
            },
            {
                name: "reason"
            },
            {
                name: "tuijianTime"
            },
            {
                name: "see"
            },
            {
                name: "download"
            },
            {
                name: "edit"
            }];
        this.tuijianStoreBar = {
            type: 1
        };
        this.tuijianStore = new Ext.data.Store({
            autoLoad: true,
            baseParams: d.tuijianStoreBar,
            proxy: new Ext.data.HttpProxy({
                url: d.baseUrl + "&task=tuijianList"
            }),
            reader: new Ext.data.JsonReader({
                idProperty: "",
                totalProperty: "total",
                root: "data",
                fields: d.tuijianFields
            }),
            listeners: {
                exception: function(j, i, k, h, g, f) {
                    var e = Ext.decode(g.responseText);
                    if (e.failure) {
                        CNOA.msg.alert(e.msg)
                    }
                }
            }
        });
        var c = new Ext.grid.CheckboxSelectionModel({
            singleSelect: false
        });
        var a = new Ext.grid.ColumnModel([new Ext.grid.RowNumberer(), {
            header: "tid",
            dataIndex: "tid",
            width: 0,
            sortable: false,
            hidden: true
        },
            {
                header: "kid",
                dataIndex: "kid,",
                width: 0,
                sortable: false,
                hidden: true
            },
            {
                header: lang("tuijianKnow2"),
                dataIndex: "title",
                width: 400,
                sortable: false
            },
            {
                header: lang("referer"),
                dataIndex: "user",
                width: 120,
                sortable: false
            },
            {
                header: lang("knowType"),
                dataIndex: "cate",
                width: 150,
                sortable: false
            },
            {
                header: lang("tuijianTime"),
                dataIndex: "tuijianTime",
                width: 150,
                sortable: false
            },
            {
                header: lang("isNotice"),
                dataIndex: "isNotice",
                width: 70,
                sortable: false
            },
            {
                header: lang("opt"),
                dataIndex: "noIndex",
                width: 100,
                sortable: false,
                renderer: d.tuijianDetail
            },
            {
                header: lang("opt"),
                dataIndex: "noIndex",
                width: 120,
                sortable: false,
                renderer: d.tuijianOption
            }]);
        this.tuijianGridPanel = new Ext.grid.GridPanel({
            store: d.tuijianStore,
            loadMask: {
                msg: lang("waiting")
            },
            cm: a,
            sm: c,
            hideBorders: true,
            border: false,
            stripeRows: true,
            layout: "fit",
            listeners: {
                rowdblclick: function(f, g) {
                    var e = f.getStore().getAt(g)
                }.createDelegate(this),
                beforerender: function(e) {
                    if (d.tuijianStoreBar.type == 1) {
                        d.tuijianGridPanel.getColumnModel().setHidden(4, true);
                        d.tuijianGridPanel.getColumnModel().setHidden(9, true)
                    }
                    if (d.tuijianStoreBar.type == 2) {
                        d.tuijianGridPanel.getColumnModel().setHidden(8, true);
                        d.tuijianGridPanel.getColumnModel().setHidden(4, false);
                        d.tuijianGridPanel.getColumnModel().setHidden(9, false)
                    }
                }
            }
        });
        var b = new Ext.PagingToolbar({
            displayInfo: true,
            emptyMsg: lang("pagingToolbarEmptyMsg"),
            displayMsg: lang("showDataTotal2"),
            store: d.tuijianStore,
            pageSize: 50,
            plugins: [new Ext.grid.plugins.ComboPageSize()]
        });
        this.mytuijianPanel = new Ext.Panel({
            title: lang("tuijianKnow2"),
            hideBorders: true,
            border: false,
            items: [d.tuijianGridPanel],
            bbar: b,
            layout: "fit",
            tbar: new Ext.Toolbar({
                items: [{
                    text: lang("refresh"),
                    tooltip: lang("refresh"),
                    iconCls: "icon-system-refresh",
                    handler: function() {
                        d.tuijianStore.load()
                    }
                },
                    {
                        text: lang("myRecommend"),
                        tooltip: lang("myTjKnow"),
                        cls: "btn-blue3",
                        handler: function() {
                            d.tuijianStoreBar.type = 1;
                            d.tuijianStore.load();
                            d.tuijianGridPanel.getColumnModel().setHidden(4, true);
                            d.tuijianGridPanel.getColumnModel().setHidden(9, true);
                            d.tuijianGridPanel.getColumnModel().setHidden(8, false)
                        }
                    },
                    {
                        text: lang("recommendTome"),
                        cls: "btn-red1",
                        handler: function() {
                            d.tuijianStoreBar.type = 2;
                            d.tuijianStore.load();
                            d.tuijianGridPanel.getColumnModel().setHidden(8, true);
                            d.tuijianGridPanel.getColumnModel().setHidden(4, false);
                            d.tuijianGridPanel.getColumnModel().setHidden(9, false)
                        }
                    }]
            })
        });
        return this.mytuijianPanel
    },
    readedList: function() {
        var d = this;
        this.readedFields = [{
            name: "know"
        },
            {
                name: "cate"
            },
            {
                name: "user"
            },
            {
                name: "stime"
            }];
        this.readedStore = new Ext.data.Store({
            autoLoad: true,
            proxy: new Ext.data.HttpProxy({
                url: d.baseUrl + "&task=readedRecord"
            }),
            reader: new Ext.data.JsonReader({
                idProperty: "",
                totalProperty: "total",
                root: "data",
                fields: d.readedFields
            }),
            listeners: {
                exception: function(j, i, k, h, g, f) {
                    var e = Ext.decode(g.responseText);
                    if (e.failure) {
                        CNOA.msg.alert(e.msg)
                    }
                }
            }
        });
        var c = new Ext.grid.CheckboxSelectionModel({
            singleSelect: false
        });
        var a = new Ext.grid.ColumnModel([new Ext.grid.RowNumberer(), {
            header: lang("viewKnow"),
            dataIndex: "know",
            width: 400,
            sortable: false
        },
            {
                header: lang("knowType"),
                dataIndex: "cate",
                width: 150,
                sortable: false
            },
            {
                header: lang("peopleToView"),
                dataIndex: "user",
                width: 120,
                sortable: false
            },
            {
                header: lang("viewtime"),
                dataIndex: "stime",
                width: 150,
                sortable: false
            }]);
        this.readedGridPanel = new Ext.grid.GridPanel({
            store: d.readedStore,
            loadMask: {
                msg: lang("waiting")
            },
            cm: a,
            sm: c,
            hideBorders: true,
            border: false,
            stripeRows: true,
            layout: "fit"
        });
        var b = new Ext.PagingToolbar({
            displayInfo: true,
            emptyMsg: lang("pagingToolbarEmptyMsg"),
            displayMsg: lang("showDataTotal2"),
            store: d.readedStore,
            pageSize: 50,
            plugins: [new Ext.grid.plugins.ComboPageSize()]
        });
        this.myReadedPanel = new Ext.Panel({
            title: lang("viewLog"),
            hideBorders: true,
            border: false,
            items: [d.readedGridPanel],
            bbar: b,
            layout: "fit",
            tbar: new Ext.Toolbar({
                items: [{
                    text: lang("refresh"),
                    tooltip: lang("refresh"),
                    iconCls: "icon-system-refresh",
                    handler: function() {
                        d.readedStore.load()
                    }
                },
                    "<span style='margin-left:10px; color:#676767;'>" + lang("logUserRecord") + "</span>"]
            })
        });
        return this.myReadedPanel
    },
    viewOpt: function(e, c, a, f, b, d) {
        var e = a.data.kid;
        return "<a href='javascript:void(0);' class='gridview' onclick='CNOA_know_know_mgr.spview(" + e + ");'>" + lang("view") + "</a>"
    },
    spview: function(a) {
        mainPanel.closeTab("CNOA_KNOW_KNOW_VIEW");
        mainPanel.loadClass("index.php?app=know&func=know&action=view&from=sp&kid=" + a, "CNOA_KNOW_KNOW_VIEW", lang("knowView"), "icon-page-view")
    },
    shStatus: function(f, c, a, h, b, e) {
        var d = a.data;
        var f = a.data.kid;
        var g;
        switch (d.shCode) {
            case "0":
                g = "<a class='gridview3' href='javascript:void(0);' onclick='CNOA_know_know_mgr.setpass(" + f + ",1);'>" + lang("pass") + "</a>";
                break;
            case "1":
                g = "<a class='gridview2' href='javascript:void(0);' onclick='CNOA_know_know_mgr.setpass(" + f + ",0);'>" + lang("unPass") + "</a>";
                break;
            case "2":
                g = "<a class='gridview3' href='javascript:void(0);' onclick='CNOA_know_know_mgr.setpass(" + f + ",1);'>" + lang("pass") + "</a>";
                g += "<a class='gridview2 jianju' href='javascript:void(0);' onclick='CNOA_know_know_mgr.setpass(" + f + ",0);'>" + lang("unPass") + "</a>";
                break
        }
        return g
    },
    setpass: function(b, a) {
        var c = this;
        Ext.Ajax.request({
            url: this.baseUrl + "&task=setPass",
            method: "POST",
            params: {
                kid: b,
                type: a
            },
            success: function(e) {
                var d = Ext.decode(e.responseText);
                if (d.success === true) {
                    CNOA.msg.alert;
                    c.spStore.load();
                    CNOA.msg.notice2(d.msg)
                } else {
                    CNOA.msg.alert(d.msg)
                }
            }
        })
    },
    getStatus: function(e, c, a, f, b, d) {
        var e = a.data.shCode;
        if (e == 1) {
            return "<font color='#2EB800'>" + lang("pass") + "</font>"
        } else {
            if (e == 0) {
                return "<font color='#ff0000'>" + lang("unPass") + "</font>"
            } else {
                if (e == 2) {
                    return lang("pendingTrial")
                }
            }
        }
    },
    cancleShareAndSee: function(g, d, b, h, c, f) {
        var e = b.data;
        var a = '<a href="javascript:void(0);" class="gridview4 jianju" onclick="CNOA_know_know_mgr.doCancleShare(' + e.sid + ')">' + lang("unshared") + "</a>";
        a += '<a href="javascript:void(0)" class="gridview jianju" onclick="CNOA_know_know_mgr.viewDetail(' + e.sid + ')">' + lang("viewDetail2") + "</a>";
        return a
    },
    doCancleShare: function(a) {
        var b = this;
        CNOA.msg.cf(lang("cancleShare") + "?",
            function(c) {
                if (c == "yes") {
                    Ext.Ajax.request({
                        url: b.baseUrl + "&task=cancleShare",
                        method: "POST",
                        params: {
                            sid: a
                        },
                        success: function(e) {
                            var d = Ext.decode(e.responseText);
                            if (d.success == true) {
                                b.shareStore.load()
                            }
                        }
                    })
                }
            })
    },
    viewDetail: function(a) {
        var c = this;
        var b = Ext.id();
        Ext.Ajax.request({
            url: c.baseUrl + "&task=getShareDetail",
            method: "POST",
            params: {
                sid: a
            },
            success: function(e) {
                var d = Ext.decode(e.responseText);
                new Ext.Window({
                    width: 600,
                    height: makeWindowHeight(300),
                    modal: true,
                    autoScroll: true,
                    maximizable: true,
                    title: lang("shareInfo"),
                    html: '<table style="width:100%;" cellpadding="0" cellspacing="0">							<tr>								<td colspan="2" class="shareInfoHead">' + lang("detailInfo") + '</td>							</tr>							<tr>								<td class="shareInfoLeft">' + lang("shareKnow") + '</td>								<td class="shareInfoRight">' + d.know + '</td>							</tr>							<tr>								<td class="shareInfoLeft">' + lang("shareUser") + '</td>								<td class="shareInfoRight">' + d.shareUser + '</td>							</tr><tr>								<td class="shareInfoLeft">' + lang("shareDept") + '</td>								<td class="shareInfoRight">' + d.shareDept + '</td>							</tr>							<tr>								<td class="shareInfoLeft">' + lang("permit4Share") + '</td>								<td class="shareInfoRight">' + d.permit + "</td>							</tr>						</table>"
                }).show()
            }
        })
    },
    makeOption: function(g, d, b, h, c, f) {
        var e = b.data;
        var a = "";
        if (e.canView == 1) {
            a += '<a href="javascript:void(0);" class="gridview" onclick="CNOA_know_know_mgr.shareview(' + e.kid + ')">' + lang("view") + "</a>"
        }
        if (e.canDownload == 1) {
            a += "<a href=\"javascript:ajaxDownload('index.php?app=know&func=know&action=view&task=download&kid=" + e.kid + '\');" class="gridview3 jianju">' + lang("download") + "</a>"
        }
        if (e.canEdit == 1) {
            a += '<a href="javascript:void(0);" class="gridview4 jianju" onclick="CNOA_know_know_mgr.editKnow(\'edit\',' + e.kid + ',1)">' + lang("edit") + "</a>"
        }
        return a
    },
    shareview: function(a) {
        mainPanel.closeTab("CNOA_KNOW_KNOW_VIEW");
        mainPanel.loadClass("index.php?app=know&func=know&action=view&from=share&kid=" + a, "CNOA_KNOW_KNOW_VIEW", lang("knowView"), "icon-page-view")
    },
    tuijianDetail: function(f, c, a, g, b, e) {
        var d = a.data;
        return '<a href="javascript:void(0)" class="gridview jianju" onclick="CNOA_know_know_mgr.tuijianViewDetail(' + d.tid + ')">' + lang("viewDetail2") + "</a>"
    },
    tuijianViewDetail: function(b) {
        var c = this;
        var a = Ext.id();
        Ext.Ajax.request({
            url: c.baseUrl + "&task=TuijianDetail",
            method: "POST",
            params: {
                tid: b
            },
            success: function(e) {
                var d = Ext.decode(e.responseText);
                new Ext.Window({
                    width: 600,
                    height: makeWindowHeight(300),
                    modal: true,
                    autoScroll: true,
                    maximizable: true,
                    title: lang("recommendDetail"),
                    html: '<table style="width:100%;" cellpadding="0" cellspacing="0">							<tr>								<td colspan="2" class="shareInfoHead">' + lang("detailInfo") + '</td>							</tr>							<tr>								<td class="shareInfoLeft">' + lang("tuijianKnow2") + '</td>								<td class="shareInfoRight">' + d.know + '</td>							</tr>							<tr>								<td class="shareInfoLeft">' + lang("tuijianUser") + '</td>								<td class="shareInfoRight">' + d.tuijianUser + '</td>							</tr><tr>								<td class="shareInfoLeft">' + lang("tuijianDept") + '</td>								<td class="shareInfoRight">' + d.tuijianDept + '</td>							</tr>							<tr>								<td class="shareInfoLeft">' + lang("tuijianSeason") + '</td>								<td class="shareInfoRight">' + d.reason + "</td>							</tr>						</table>"
                }).show()
            }
        })
    },
    tuijianOption: function(g, d, b, h, c, f) {
        var e = b.data;
        var a = "";
        if (e.see == 1) {
            a += '<a href="javascript:void(0);" class="gridview" onclick="CNOA_know_know_mgr.tuijianView(' + e.kid + ')">' + lang("view") + "</a>"
        }
        if (e.download == 1) {
            a += "<a href=\"javascript:ajaxDownload('index.php?app=know&func=know&action=view&task=download&kid=" + e.kid + '\');" class="gridview3 jianju">' + lang("download") + "</a>"
        }
        if (e.edit == 1) {
            a += '<a href="javascript:void(0);" class="gridview4 jianju" onclick="CNOA_know_know_mgr.editKnow(\'edit\',' + e.kid + ',2)">' + lang("edit") + "</a>"
        }
        if (a == "") {
            a = lang("noPermit")
        }
        return a
    },
    tuijianView: function(a) {
        mainPanel.closeTab("CNOA_KNOW_KNOW_VIEW");
        mainPanel.loadClass("index.php?app=know&func=know&action=view&from=tuijian&kid=" + a, "CNOA_KNOW_KNOW_VIEW", lang("knowView"), "icon-page-view")
    },
    editKnow: function(b, d, a) {
        var c = this;
        var d = d || 0;
        Ext.Ajax.request({
            url: "index.php?app=know&func=know&action=edit&task=getUeditorContent",
            method: "POST",
            params: {
                id: d
            },
            success: function(f) {
                var e = Ext.decode(f.responseText).content;
                var g = "";
                if (a == 1) {
                    g = new CNOA_know_know_editComponentClass(this, d, e, c.shareStore)
                } else {
                    g = new CNOA_know_know_editComponentClass(this, d, e, c.tuijianStore)
                }
                g.show()
            }
        })
    }
};
var CNOA_know_know_viewClass, CNOA_know_know_view;
CNOA_know_know_viewClass = CNOA.Class.create();
CNOA_know_know_viewClass.prototype = {
    init: function() {
        var a = this;
        this.baseUrl = "index.php?app=know&func=know&action=view";
        this.kid = CNOA.know.know.view.kid;
        this.from = CNOA.know.know.view.from;
        this.historyPanel = a.historyVersion();
        this.page = 1;
        this.tabPanel = new Ext.TabPanel({
            activeTab: 0,
            id: "know_menu_tabPanel",
            items: [{
                title: lang("knowContent"),
                region: "center",
                width: "100%",
                id: "know_preview_panel",
                html: '<div id="know_view_wrap">							<div id="know_view_container">								<div class="pdfshow" id="pdf" style="height:101%;overflow:scroll;"></div>							</div>							<div id="know_previous"> < </div>							<div id="know_next"> > </div>						</div>',
                border: false
            },
                {
                    title: lang("knowReview"),
                    id: "know_comment_panel",
                    style: "overflow:scroll",
                    html: '<div id="know_all_comment_panel">						<div class="no_user_comment" id="know_no_comment_info" style="display:none;">' + lang("noComment") + '</div>						<div class="no_user_comment" id="know_yes_comment_info">' + lang("total") + ' <span id="know_comment_count"></span> ' + lang("comments") + '</div>						<div class="know_know_wrap"></div>						<div class="know_fbAndPage">							<div class="know_comment_page" id="know_comment_page"></div>						</div>						<div class="know_user_comment">							<div class="know_container" style="border-bottom:none;">								<div class="know_userInfo">									<div class="know_user_name">										<p id="new_know_user"></p>									</div>									<div class="know_user_face"></div>								</div>								<div class="know_commentInfo">									<div class="know_score_up">										<ul>											<li id="know_comment_score_text">' + lang("score3") + '</li>											<li id="know_comment_score">												<select id="know_comment_score_select">													<option value="0"></option>													<option value="1">1</option>													<option value="2">2</option>													<option value="3">3</option>													<option value="4">4</option>													<option value="5">5</option>												</select>											</li>										</ul>									</div>									<div class="know_info_down">										<ul>											<li id="know_comment_score_text">' + lang("comment") + '</li>											<li id="know_comment_score" style="margin-bottom:10px;">												<textarea id="know_comment_textarea"></textarea>											</li>										</ul>										<div class="know_sendComment" style="margin-left:5%;">											<button id="know_sendComment_btn">' + lang("addComment") + '</button>											<button id="know_comment_reset_btn">' + lang("reset") + "</button>										</div>									</div>								</div>							</div>						</div></div>					",
                    listeners: {
                        afterrender: function() {
                            var b = Ext.getCmp("know_detail_panel").getHeight() + 40;
                            Ext.getCmp("know_comment_panel").setHeight(b);
                            a.viewComment(1);
                            $("#know_comment_reset_btn").click(function() {
                                $("#know_comment_textarea").val("")
                            });
                            $("#know_sendComment_btn").click(function() {
                                var d = parseInt($("#know_comment_score_select").val());
                                var c = $("#know_comment_textarea").val();
                                Ext.Ajax.request({
                                    url: a.baseUrl + "&task=addComment",
                                    method: "POST",
                                    params: {
                                        score: d,
                                        comment: c,
                                        kid: a.kid
                                    },
                                    success: function(h) {
                                        var e = Ext.decode(h.responseText);
                                        if (e.success !== true) {
                                            CNOA.msg.alert(e.msg);
                                            return
                                        }
                                        CNOA.msg.notice2(e.msg);
                                        $("#know_comment_textarea").val("");
                                        $("#know_comment_score_select").val("");
                                        var g = parseInt($("#know_comment").html()) + 1;
                                        $("#know_comment").html(g);
                                        var f = parseInt($("#know_score").html()) + d;
                                        $("#know_score").html(f);
                                        a.viewComment(0);
                                        $("#know_all_comment_panel").animate({
                                                scrollTop: 0
                                            },
                                            "slow")
                                    }
                                })
                            })
                        }
                    }
                },
                a.historyPanel]
        });
        this.mainPanel = new Ext.Panel({
            layout: "border",
            border: false,
            hideBorders: true,
            items: [{
                width: "75%",
                region: "center",
                items: [a.tabPanel],
                border: false,
                hideBorders: true
            },
                {
                    region: "east",
                    width: "25%",
                    id: "know_detail_panel",
                    border: true,
                    hideBorders: true,
                    tbar: new Ext.Toolbar({
                        style: "background-color:#ffffff;border:none;",
                        height: 26,
                        items: [{
                            xtype: "displayfield",
                            value: "<span style='margin-left:15px;'>" + lang("click") + "	                    		<strong id='know_hits'></strong>" + lang("bout") + "</span>"
                        },
                            {
                                xtype: "displayfield",
                                value: "<span style='margin-left:15px;'>" + lang("read") + "			                    	<strong id='know_readed'></strong>" + lang("peop") + "</span>"
                            },
                            {
                                xtype: "displayfield",
                                value: "<span style='margin-left:15px;'>" + lang("recommended") + "			                    	<strong id='know_tuijian'></strong>" + lang("bout") + "</span>"
                            },
                            {
                                xtype: "displayfield",
                                value: "<span style='margin-left:15px;'>" + lang("comment") + "									<strong id='know_comment'></strong>" + lang("bout") + "</span>"
                            },
                            {
                                xtype: "displayfield",
                                value: "<span style='margin-left:15px;margin-right:10px;'>" + lang("knowledgeScores") + "<strong id='know_score'></strong></span>"
                            }]
                    }),
                    html: '<div id="detailInfo" style="height:100%;background-color:#f0f0f0;overflow:scroll;">							<table class="detailInfo_table">								<tr>									<td class="detailInfo_left">' + lang("knowTitle") + ':</td>									<td id="know_title" class="detailInfo_right"></td>								</tr>								<tr>									<td class="detailInfo_left">' + lang("knowType") + ':</td>									<td id="know_cate" class="detailInfo_right"></td>								</tr>								<tr>									<td class="detailInfo_left">' + lang("knowAuth") + ':</td>									<td id="know_auth" class="detailInfo_right"></td>								</tr>								<tr>									<td class="detailInfo_left">' + lang("publish") + ':</td>									<td id="know_posttime" class="detailInfo_right"></td>								</tr>								<tr>									<td class="detailInfo_left">' + lang("knowAttach") + ':</td>								</tr>							</table>							<div id="know_attach" style="padding-left:15px;"></div>							<div id="know_tip" style="padding-left:20px;">' + lang("Prompt") + ':</div>							<div id="know_tip_cont" style="padding-left:20px;font-size:14px;color:red;">' + lang("convertSupport") + '</div>							<div id="know_container">								<div id="downloadDocument">								<img src="resources/images/icons/download.png">&nbsp;&nbsp;' + lang("downloadDocument") + '								</div>								<div id="TJDocument">									<img src="resources/images/icons/tuijian.png">&nbsp;&nbsp;' + lang("tuijianKnow") + "								</div>							</div>					</div>",
                    listeners: {
                        afterrender: function() {
                            Ext.Ajax.request({
                                url: a.baseUrl + "&task=getInfo",
                                method: "POST",
                                params: {
                                    kid: a.kid,
                                    from: a.from
                                },
                                success: function(i) {
                                    var d = Ext.decode(i.responseText);
                                    $("#downloadDocument").click(function() {
                                        ajaxDownload(a.baseUrl + "&task=download&kid=" + a.kid)
                                    });
                                    $("#TJDocument").click(function() {
                                        a.knowTuijian(a.kid)
                                    });
                                    if (d.success == true) {
                                        $("#know_hits").html(d.hits);
                                        $("#know_readed").html(d.readed);
                                        $("#know_tuijian").html(d.tuijian);
                                        $("#know_comment").html(d.comment);
                                        $("#know_score").html(d.score);
                                        $("#know_title").html(d.title);
                                        $("#know_cate").html(d.cate);
                                        $("#know_auth").html(d.auth);
                                        $("#know_posttime").html(d.posttime);
                                        var f = {
                                            pdfOpenParams: {
                                                navpanes: 0,
                                                toolbar: 0,
                                                statusbar: 0,
                                                view: "FitV",
                                                pagemode: "thumbs"
                                            }
                                        };
                                        if (d.type != 2) {
                                            $("#pdf").html(d.content)
                                        } else {
                                            PDFObject.embed(d.content, "#pdf", f)
                                        }
                                        for (var c = 0; c < d.officeCount; c++) {
                                            $("#know_view_container").append('<div class="pdfshow" style="height:101%;overflow:scroll;" id="pdf_' + c + '"></div>');
                                            PDFObject.embed(d.office2pdf[c], "#pdf_" + c, f)
                                        }
                                        var e = 0;
                                        var h = $("#know_view_container").children().length;
                                        var g = $("#know_view_wrap").width();
                                        $(".pdfshow").css("width", g);
                                        $("#know_previous").click(function() {
                                            if (e > 0) {
                                                e--;
                                                left = -e * 100;
                                                left += "%";
                                                $("#know_view_container").stop(false, true).animate({
                                                        left: left
                                                    },
                                                    "fast",
                                                    function() {
                                                        $("#know_view_container").css("left", left)
                                                    })
                                            }
                                        });
                                        $("#know_next").click(function() {
                                            if (e < h - 1) {
                                                e++;
                                                left = -e * 100;
                                                left += "%";
                                                $("#know_view_container").stop(false, true).animate({
                                                        left: left
                                                    },
                                                    "fast",
                                                    function() {
                                                        $("#know_view_container").css("left", left)
                                                    })
                                            }
                                        });
                                        $("#know_view_wrap").stop(false, true).mouseenter(function() {
                                            $("#know_previous").fadeIn("slow");
                                            $("#know_next").fadeIn("slow")
                                        });
                                        $("#know_view_wrap").stop(false, true).mouseleave(function() {
                                            $("#know_previous").fadeOut("slow");
                                            $("#know_next").fadeOut("slow")
                                        });
                                        if (d.canDownload == true) {
                                            $("#downloadDocument").show()
                                        } else {
                                            $("#downloadDocument").hide()
                                        }
                                        $("#know_previous").show();
                                        $("#know_next").show();
                                        $("#know_attach").html(d.attach);
                                        if (d.notTuijian) {
                                            $("#TJDocument").hide()
                                        }
                                        var b = Ext.getCmp("know_detail_panel").getHeight();
                                        Ext.getCmp("know_menu_tabPanel").setHeight(b)
                                    } else {
                                        CNOA.msg.cf(d.msg,
                                            function() {
                                                a.closeTab()
                                            })
                                    }
                                },
                                failure: function() {
                                    alert(lang("requestError") + "!")
                                }
                            })
                        }
                    }
                }]
        })
    },
    knowTuijian: function(b) {
        var d = this;
        var a = new Ext.form.FormPanel({
            border: false,
            bodyStyle: "padding:10px;",
            waitMsgTarget: true,
            labelWidth: 60,
            labelAlign: "left",
            items: [{
                xtype: "selectorform",
                selectorType: "dept",
                multiselect: true,
                hiddenName: "deptId",
                name: "chooseDept",
                width: 300,
                fieldLabel: lang("tuijianDept")
            },
                {
                    xtype: "displayfield",
                    value: "<span style='color:#676767;'>" + lang("emptySelectAllDept") + "</span>"
                },
                {
                    xtype: "selectorform",
                    selectorType: "user",
                    multiselect: true,
                    hiddenName: "userId",
                    name: "chooseUser",
                    width: 300,
                    fieldLabel: lang("tuijianUser")
                },
                {
                    xtype: "displayfield",
                    value: "<span style='color:#676767;'>" + lang("emptyDefaultAll") + "</span>"
                },
                {
                    xtype: "textarea",
                    fieldLabel: lang("tuijianSeason"),
                    name: "tjreason",
                    width: 300
                },
                {
                    xtype: "checkbox",
                    fieldLabel: lang("notice1"),
                    name: "isNotice",
                    inputValue: "1",
                    checked: true,
                    boxLabel: lang("tuijianNotice")
                }]
        });
        var c = new Ext.Window({
            title: lang("knowTuijian"),
            width: 400,
            height: 390,
            modal: true,
            resizable: false,
            autoScroll: true,
            items: [a],
            buttons: [{
                text: lang("tuijianKnow"),
                iconCls: "icon-btn-save",
                cls: "btn-blue4",
                handler: function() {
                    CNOA.msg.cf(lang("confirmTuijian") + "?",
                        function(g) {
                            if (g == "yes") {
                                var i = a.getForm();
                                if (i.isValid()) {
                                    var h = i.findField("deptId").getValue();
                                    var f = i.findField("userId").getValue();
                                    var j = i.findField("tjreason").getValue();
                                    var e = i.findField("isNotice").getValue() ? 1 : 0;
                                    Ext.Ajax.request({
                                        url: "index.php?app=know&func=know&action=my&task=tuijian",
                                        method: "POST",
                                        params: {
                                            userId: f,
                                            deptId: h,
                                            isNotice: e,
                                            reason: j,
                                            ids: b + ","
                                        },
                                        success: function(l) {
                                            var k = Ext.decode(l.responseText);
                                            if (k.success === true) {
                                                c.close();
                                                CNOA.msg.alert(k.msg);
                                                var m = parseInt($("#know_tuijian").html()) + 1;
                                                $("#know_tuijian").html(m)
                                            } else {
                                                CNOA.msg.alert(k.msg)
                                            }
                                        }
                                    })
                                }
                            }
                        })
                }
            },
                {
                    text: lang("cancel"),
                    iconCls: "icon-dialog-cancel",
                    handler: function() {
                        c.close()
                    }
                }]
        }).show()
    },
    historyVersion: function() {
        var h = this;
        var c = [{
            name: "kid"
        },
            {
                name: "title"
            },
            {
                name: "btime"
            },
            {
                name: "ctime"
            }];
        var d = new Ext.data.Store({
            autoLoad: true,
            baseParams: {
                kid: h.kid
            },
            proxy: new Ext.data.HttpProxy({
                url: h.baseUrl + "&task=getHistoryVersion"
            }),
            reader: new Ext.data.JsonReader({
                idProperty: "",
                totalProperty: "total",
                root: "data",
                fields: c
            })
        });
        var b = Ext.id();
        var g = new Ext.grid.CheckboxSelectionModel({
            singleSelect: false
        });
        var a = new Ext.grid.ColumnModel([new Ext.grid.RowNumberer(), {
            header: lang("title"),
            dataIndex: "title",
            sortable: false,
            width: 200,
            id: b
        },
            {
                header: lang("createTime"),
                dataIndex: "btime",
                width: 200,
                sortable: false
            },
            {
                header: lang("publish"),
                dataIndex: "ctime",
                width: 200,
                sortable: false
            },
            {
                header: lang("opt"),
                dataIndex: "noIndex",
                width: 60,
                renderer: h.setVersion,
                sortable: false
            }]);
        var f = new Ext.PagingToolbar({
            displayInfo: true,
            emptyMsg: lang("pagingToolbarEmptyMsg"),
            displayMsg: lang("showDataTotal2"),
            store: d,
            pageSize: 50
        });
        var e = new Ext.grid.GridPanel({
            title: lang("historyVer"),
            store: d,
            loadMask: {
                msg: lang("waiting")
            },
            cm: a,
            sm: g,
            hideBorders: true,
            border: false,
            stripeRows: true,
            layout: "fit",
            autoExpandColumn: b,
            bbar: f,
            id: "know_history_panel"
        });
        return e
    },
    closeTab: function() {
        parent.mainPanel.closeTab("CNOA_MENU_KNOW_KNOW_VIEW")
    },
    setVersion: function(g, d, b, h, c, f) {
        var e = b.data.kid;
        var a = '<a href="javascript:void(0);" class="gridview jianju" onclick="CNOA_know_know_view.view(' + e + ')">' + lang("view") + "</a>";
        return a
    },
    view: function(a) {
        mainPanel.closeTab("CNOA_KNOW_KNOW_VIEW");
        mainPanel.loadClass("index.php?app=know&func=know&action=view&from=history&kid=" + a, "CNOA_KNOW_KNOW_VIEW", lang("knowView"), "icon-page-view")
    },
    viewComment: function(a) {
        var b = this;
        Ext.Ajax.request({
            url: b.baseUrl + "&task=viewComment&page=" + b.page,
            method: "POST",
            params: {
                kid: b.kid
            },
            success: function(e) {
                var d = Ext.decode(e.responseText);
                if (d.total == 0) {
                    $("#know_no_comment_info").show();
                    $("#know_yes_comment_info").hide();
                    $("#know_comment_page").hide()
                } else {
                    $("#know_no_comment_info").hide();
                    $("#know_yes_comment_info").show();
                    $("#know_comment_page").show();
                    $("#know_comment_count").html(d.total)
                }
                if (a) {
                    $(".know_user_comment .know_user_face").append('<img id="know_fb_user_face" src="' + d.myFace + '">');
                    $("#new_know_user").html(d.myName)
                }
                $("#know_comment_page").empty().append(d.page);
                var f = d.data;
                $(".know_know_wrap").empty();
                for (var c = 0; c < f.length; c++) {
                    $(".know_know_wrap").append('<div class="know_container">						<div class="know_userInfo">							<div class="know_user_name">								<p>' + f[c]["name"] + '</p>							</div>							<div class="know_user_face">								<!--<img src="resources/images/default.jpg">-->								<img src="' + f[c]["face"] + '">							</div>						</div>						<div class="know_commentInfo">							<div class="know_commentInfo_info">' + f[c]["content"] + '</div>							<div class="know_commentInfo_time">								<p> >> ' + lang("commentIn") + " " + f[c]["ctime"] + "&nbsp;&nbsp;<span>" + f[c]["score"] + lang("point") + '</span><span onclick="CNOA_know_know_view.delComment(' + f[c]["cid"] + ')" style="float:right;cursor:pointer;">' + lang("del") + '</span><img src="/resources/images/icons/delete2.png" style="float:right;color:#ccc;"></p>							</div>						</div>					</div>')
                }
            }
        })
    },
    fpage: function(a) {
        var b = this;
        Ext.Ajax.request({
            url: b.baseUrl + "&task=viewComment&page=" + a,
            method: "POST",
            params: {
                kid: b.kid
            },
            success: function(e) {
                b.page = a;
                var d = Ext.decode(e.responseText);
                $("#know_comment_page").empty().append(d.page);
                var f = d.data;
                $(".know_know_wrap").empty();
                for (var c = 0; c < f.length; c++) {
                    $(".know_know_wrap").append('<div class="know_container">						<div class="know_userInfo">							<div class="know_user_name">								<p>' + f[c]["name"] + '</p>							</div>							<div class="know_user_face">								<!--<img src="resources/images/default.jpg">-->								<img src="' + f[c]["face"] + '">							</div>						</div>						<div class="know_commentInfo">							<div class="know_commentInfo_info">' + f[c]["content"] + '</div>							<div class="know_commentInfo_time">								<p> >> ' + lang("commentIn") + " " + f[c]["ctime"] + "&nbsp;&nbsp;<span>" + f[c]["score"] + lang("point") + '</span><span onclick="CNOA_know_know_view.delComment(' + f[c]["cid"] + ')" style="float:right;cursor:pointer;">' + lang("del") + '</span><img src="/resources/images/icons/delete2.png" style="float:right;color:#ccc;"></p>							</div>						</div>					</div>')
                }
            }
        })
    },
    delComment: function(b) {
        var a = this;
        CNOA.msg.cf(lang("confirmDeletePL") + "?",
            function(c) {
                if (c == "yes") {
                    Ext.Ajax.request({
                        url: a.baseUrl + "&task=delComment",
                        method: "POST",
                        params: {
                            cid: b
                        },
                        success: function(f) {
                            var d = Ext.decode(f.responseText);
                            if (d.success === true) {
                                var e = parseInt($("#know_comment").html()) - 1;
                                $("#know_comment").html(e);
                                $("#know_score").html(d.score);
                                CNOA.msg.notice2(d.msg);
                                a.viewComment(0)
                            }
                        }
                    })
                }
            })
    }
};
var CNOA_know_know_editComponentClass = CNOA.Class.create();
CNOA_know_know_editComponentClass.prototype = {
    init: function(e, a, b, d) {
        var g = this;
        this.editObj = e;
        this.ueditorContent = b;
        this.listStore = d;
        this.baseUrl = "index.php?app=know&func=know&action=edit";
        this.editId = parseInt(a) || 0;
        this.action = (this.editId === 0) ? "add": "edit";
        this.knowType = 1;
        this.cateStore = new Ext.data.Store({
            proxy: new Ext.data.HttpProxy({
                url: g.baseUrl + "&task=upCate",
                disableCaching: true
            }),
            reader: new Ext.data.JsonReader({
                fields: ["cateId", "name"]
            })
        });
        this.cateStore.load();
        this.formPanel = this.createEditView();
        var c = this;
        var f = this.action == "add" ? lang("KnowledgeFb") : lang("knowledgeEdit");
        this.mainPanel = new Ext.Window({
            width: 800,
            height: makeWindowHeight(600),
            title: f,
            modal: true,
            layout: "fit",
            items: [c.formPanel],
            buttons: [{
                text: lang("save"),
                iconCls: "icon-btn-save",
                handler: function() {
                    c.submitForm()
                }
            },
                {
                    text: lang("close"),
                    iconCls: "icon-dialog-cancel",
                    handler: function() {
                        c.close()
                    }
                }]
        })
    },
    createEditView: function() {
        this.ID_up_cate = Ext.id();
        this.ID_know_content = Ext.id();
        var a = this;
        return new Ext.form.FormPanel({
            autoScroll: true,
            border: false,
            labelWidth: 70,
            labelAlign: "right",
            waitMsgTarget: true,
            bodyStyle: "padding:10px;",
            items: [{
                xtype: "fieldset",
                title: lang("knowInfo"),
                items: [{
                    xtype: "container",
                    layout: "column",
                    items: [{
                        xtype: "container",
                        layout: "form",
                        items: [{
                            xtype: "textfield",
                            name: "title",
                            width: 250,
                            fieldLabel: lang("knowTitle"),
                            allowBlank: false
                        }]
                    },
                        {
                            xtype: "container",
                            layout: "form",
                            items: [{
                                xtype: "textfield",
                                name: "keyword",
                                width: 120,
                                fieldLabel: lang("keyword2"),
                                allowBlank: false
                            }]
                        },
                        {
                            xtype: "container",
                            layout: "form",
                            items: [{
                                xtype: "textfield",
                                name: "auth",
                                width: 120,
                                fieldLabel: lang("author"),
                                allowBlank: false
                            }]
                        },
                        {
                            xtype: "container",
                            layout: "form",
                            items: [{
                                xtype: "combo",
                                id: a.ID_up_cate,
                                editable: false,
                                width: 150,
                                store: a.cateStore,
                                valueField: "cateId",
                                displayField: "name",
                                mode: "local",
                                triggerAction: "all",
                                forceSelection: true,
                                fieldLabel: lang("knowType"),
                                allowBlank: false,
                                name: "knowCate",
                                listeners: {
                                    select: function(b) {
                                        var c = b.getValue();
                                        Ext.Ajax.request({
                                            url: a.baseUrl + "&task=isSp",
                                            method: "POST",
                                            params: {
                                                cateId: c
                                            },
                                            success: function(e) {
                                                var d = Ext.decode(e.responseText);
                                                if (d.isSp == 1) {
                                                    Ext.getCmp("sp_notice").show()
                                                } else {
                                                    Ext.getCmp("sp_notice").hide()
                                                }
                                            }
                                        })
                                    }
                                }
                            }]
                        }]
                },
                    {
                        xtype: "flashfile",
                        fieldLabel: lang("upPDF"),
                        name: "filesPDF",
                        inputPre: "pdf_content",
                        id: "pdf_content",
                        hidden: a.knowType != 2 ? true: false
                    },
                    {
                        xtype: "displayfield",
                        value: '<span style="color:red;">*' + lang("pdfNeeded") + "</span>",
                        hidden: a.knowType != 2 ? true: false,
                        id: "pdf_display_info",
                        style: "margin-bottom:5px;"
                    },
                    {
                        xtype: "ueditor",
                        toolType: "mini",
                        name: "content",
                        fieldLabel: lang("knowContent") + "&nbsp;",
                        width: 640,
                        height: 200,
                        id: a.ID_know_content
                    },
                    {
                        xtype: "radiogroup",
                        columns: 2,
                        fieldLabel: lang("ContentFormats") + "&nbsp;",
                        width: 140,
                        id: "know_know_group",
                        items: [{
                            xtype: "radio",
                            name: "know_type",
                            inputValue: "1",
                            boxLabel: "HTML",
                            checked: true
                        },
                            {
                                xtype: "radio",
                                name: "know_type",
                                boxLabel: "PDF",
                                inputValue: "2"
                            }],
                        listeners: {
                            afterrender: function() {
                                this.on("change",
                                    function(b, d) {
                                        var c = d.getRawValue();
                                        if (c == 1) {
                                            a.knowType = 1;
                                            Ext.getCmp("pdf_content").hide();
                                            Ext.getCmp("pdf_display_info").hide();
                                            Ext.getCmp(a.ID_know_content).show()
                                        } else {
                                            if (c == 2) {
                                                a.knowType = 2;
                                                Ext.getCmp("pdf_content").show();
                                                Ext.getCmp("pdf_display_info").show();
                                                Ext.getCmp(a.ID_know_content).hide()
                                            }
                                        }
                                    })
                            }
                        }
                    },
                    {
                        xtype: "checkboxgroup",
                        fieldLabel: lang("isNotice") + "&nbsp;",
                        columns: 4,
                        items: [{
                            boxLabel: lang("NotifyReader"),
                            name: "post_notice"
                        },
                            {
                                boxLabel: lang("pLsendNotice"),
                                name: "comment_notice",
                                checked: true,
                                inputValue: 1
                            }]
                    },
                    {
                        xtype: "checkbox",
                        fieldLabel: lang("appRemind") + "&nbsp;",
                        boxLabel: lang("sendApprPerson"),
                        name: "sp_notice",
                        id: "sp_notice",
                        hidden: true
                    },
                    {
                        xtype: "checkbox",
                        fieldLabel: lang("versionUpdate") + "&nbsp;",
                        name: "version_update",
                        boxLabel: lang("newVersionSaved"),
                        hidden: a.action == "add" ? true: false
                    },
                    {
                        xtype: "flashfile",
                        fieldLabel: lang("attach") + "&nbsp;",
                        name: "files",
                        width: 640,
                        inputPre: "filesUpload"
                    }]
            },
                {
                    xtype: "fieldset",
                    title: lang("knowPermit"),
                    items: [{
                        xtype: "selectorform",
                        selectorType: "user",
                        multiselect: true,
                        hiddenName: "canSeeUser",
                        name: "chooseUser",
                        width: 660,
                        fieldLabel: lang("canReader")
                    },
                        {
                            xtype: "displayfield",
                            value: "<span style='color:#676767;'>" + lang("emptyDefaultAll") + "</span>"
                        },
                        {
                            xtype: "selectorform",
                            selectorType: "dept",
                            multiselect: true,
                            hiddenName: "canSeeDept",
                            name: "chooseDept",
                            width: 660,
                            fieldLabel: lang("canReadDept")
                        },
                        {
                            xtype: "displayfield",
                            value: "<span style='color:#676767;'>" + lang("emptySelectAllDept") + "</span>"
                        },
                        {
                            xtype: "selectorform",
                            selectorType: "user",
                            multiselect: true,
                            hiddenName: "canEditUser",
                            name: "chooseEditUser",
                            width: 660,
                            fieldLabel: lang("ModifiableStaff")
                        },
                        {
                            xtype: "displayfield",
                            value: "<span style='color:#676767;'>" + lang("emptyDefaultAll") + "</span>"
                        },
                        {
                            xtype: "selectorform",
                            selectorType: "dept",
                            multiselect: true,
                            hiddenName: "canEditDept",
                            name: "chooseEditDept",
                            width: 660,
                            fieldLabel: lang("canEditDept")
                        },
                        {
                            xtype: "displayfield",
                            value: "<span style='color:#676767;'>" + lang("emptySelectAllDept") + "</span>"
                        },
                        {
                            xtype: "selectorform",
                            selectorType: "user",
                            multiselect: true,
                            hiddenName: "canDownloadUser",
                            name: "chooseDownloadUser",
                            width: 660,
                            fieldLabel: lang("downloadableUser")
                        },
                        {
                            xtype: "displayfield",
                            value: "<span style='color:#676767;'>" + lang("emptyDefaultAll") + "</span>"
                        },
                        {
                            xtype: "selectorform",
                            selectorType: "dept",
                            multiselect: true,
                            hiddenName: "canDownloadDept",
                            name: "chooseDownloadDept",
                            width: 660,
                            fieldLabel: lang("DownloadableDept")
                        },
                        {
                            xtype: "displayfield",
                            value: "<span style='color:#676767;'>" + lang("emptySelectAllDept") + "</span>"
                        }]
                }],
            listeners: {
                afterrender: function() {
                    Ext.getCmp(a.ID_know_content).setValue(a.ueditorContent)
                }
            }
        })
    },
    loadFormData: function() {
        var b = this;
        var a = this.formPanel.getForm();
        Ext.Ajax.request({
            url: b.baseUrl + "&task=getKnow",
            params: {
                kid: b.editId
            },
            method: "POST",
            waitMsg: lang("waiting"),
            success: function(d) {
                var c = Ext.decode(d.responseText);
                var e = c.data;
                if (c.success == true) {
                    a.findField("title").setValue(e.title);
                    a.findField("keyword").setValue(e.keyword);
                    a.findField("auth").setValue(e.auth);
                    a.findField("knowCate").setValue(e.cateId);
                    a.findField("post_notice").setValue(e.sendNotice);
                    a.findField("comment_notice").setValue(e.isNotice);
                    a.findField("sp_notice").setValue(e.spNotice);
                    a.findField("canSeeUser").setValue(e.canSeeUser);
                    a.findField("canSeeDept").setValue(e.canSeeDept);
                    a.findField("chooseUser").setValue(e.chooseUser);
                    a.findField("chooseDept").setValue(e.chooseDept);
                    a.findField("canEditUser").setValue(e.canEditUser);
                    a.findField("canEditDept").setValue(e.canEditDept);
                    a.findField("chooseEditUser").setValue(e.chooseEditUser);
                    a.findField("chooseEditDept").setValue(e.chooseEditDept);
                    a.findField("canDownloadUser").setValue(e.canDownloadUser);
                    a.findField("canDownloadDept").setValue(e.canDownloadDept);
                    a.findField("chooseDownloadUser").setValue(e.chooseDownloadUser);
                    a.findField("chooseDownloadDept").setValue(e.chooseDownloadDept);
                    if (e.type == 2) {
                        a.findField("content").hide();
                        a.findField("filesPDF").show();
                        a.findField("pdf_display_info").show();
                        a.findField("filesPDF").setValue(e.filesPDF);
                        b.knowType = 2;
                        Ext.getCmp("know_know_group").setValue(2)
                    }
                    a.findField("files").setValue(e.files)
                } else {
                    CNOA.msg.alert(c.msg)
                }
            },
            failure: function(d) {
                var c = Ext.decode(d.responseText);
                CNOA.msg.alert(c.msg)
            }
        })
    },
    submitForm: function() {
        var b = this;
        var a = this.formPanel.getForm();
        if (this.formPanel.getForm().isValid()) {
            this.formPanel.getForm().submit({
                url: this.baseUrl + "&task=" + b.action,
                waitMsg: lang("waiting"),
                params: {
                    kid: b.editId,
                    knowType: b.knowType,
                    sendNotice: a.findField("post_notice").getValue() ? 1 : 0,
                    isNotice: a.findField("comment_notice").getValue() ? 1 : 0,
                    spNotice: a.findField("sp_notice").getValue() ? 1 : 0,
                    isHistory: a.findField("version_update").getValue() ? 1 : 0,
                    cateId: a.findField("knowCate").getValue(),
                    realContent: a.findField("content").getContentText()
                },
                method: "POST",
                success: function(d, e) {
                    var c = e.result;
                    if (c.success) {
                        b.close();
                        b.listStore.reload();
                        CNOA.msg.notice2(c.msg)
                    } else {
                        CNOA.msg.alert(c.msg)
                    }
                },
                failure: function(c, d) {
                    CNOA.msg.alert(d.result.msg)
                }
            })
        }
    },
    show: function() {
        this.mainPanel.show();
        if (this.action == "edit") {
            this.loadFormData()
        }
    },
    close: function() {
        this.mainPanel.close()
    }
};
var sm_know_know = 1;
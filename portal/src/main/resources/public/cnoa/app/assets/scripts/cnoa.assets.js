var CNOA_assets_assets_assetsmanagementClass, CNOA_assets_assets_assetsmanagement;
CNOA_assets_assets_assetsmanagementClass = new CNOA.Class.create();
CNOA_assets_assets_assetsmanagementClass.prototype = {
    init: function() {
        var a = this;
        this.baseUrl = "index.php?app=assets&func=assets&action=assetsmanagement";
        this.TYPE_MEASURE = 0;
        this.TYPE_SOURCE = 2;
        this.TYPE_WAY = 3;
        this.TYPE_STORAGE = 4;
        this.TYPE_MANUFACTUER = 6;
        this.TYPE_SUPPLIER = 7;
        this.inputStatus = "";
        this.addNumber = "";
        this.assets = this.assetsmanagement();
        this.searchPanel = this.getSearchPanel();
        this.searchSort = new Ext.form.TextField({
            width: 130,
            id: this.ID_Sort,
            listeners: {
                focus: function() {
                    a.Sort()
                }
            }
        });
        this.searchDept = new CNOA.selector.form.DepartmentSelectorField({
            width: 130,
            hiddenName: "searchDept"
        });
        this.searchName = new Ext.form.TextField({
            width: 130
        });
        this.mainPanel = new Ext.Panel({
            border: false,
            layout: "border",
            tbar: new Ext.Toolbar({
                height: 32,
                style: "padding-top: 5px",
                items: [{
                    xtype: "hidden",
                    id: this.ID_searchSort
                },
                    lang("sort") + ":", this.searchSort, lang("department") + ":", this.searchDept, lang("stdname") + ":", this.searchName, {
                        xtype: "button",
                        text: lang("search"),
                        listeners: {
                            click: function(b) {
                                a.searchParams = a.searchPanel.getForm().getValues();
                                a.searchParams.searchName = a.searchName.getValue();
                                a.searchParams.searchSort = Ext.getCmp(a.ID_searchSort).getValue();
                                a.searchParams.searchDept = a.searchDept.getValue();
                                a.grid.store.reload({
                                    params: a.searchParams
                                });
                                a.grid.searchStore = a.searchParams
                            }
                        }
                    },
                    {
                        xtype: "button",
                        text: lang("expandSearch"),
                        handler: function(d) {
                            if (a.searchPanel.isVisible()) {
                                d.setText(lang("expandSearch"));
                                a.searchPanel.hide()
                            } else {
                                d.setText(lang("foldSearch"));
                                if (a.searchPanel.items.length <= 0) {
                                    var b = Ext.CustomSearchPanel.getCustomColumns(a.grid.customColumns, a.baseUrl, a.searchPanel);
                                    for (var c = 0; c < b.length; c++) {
                                        if (b[c].name != "typeName") {
                                            a.searchPanel.add(b[c])
                                        }
                                    }
                                }
                                a.searchPanel.show()
                            }
                            a.mainPanel.doLayout()
                        }
                    },
                    {
                        xtype: "button",
                        text: lang("clear2"),
                        handler: function() {
                            a.searchParams = {};
                            a.searchName.setValue("");
                            a.searchSort.setValue("");
                            Ext.getCmp(a.ID_searchSort).setValue("");
                            a.searchDept.setValue("");
                            Ext.each(a.searchPanel.getForm().items.items,
                                function(c, b) {
                                    var d = c.getXType();
                                    if (d == "compositefield" || d == "checkboxgroup") {
                                        Ext.each(c.items.items,
                                            function(e) {
                                                e.setValue("")
                                            })
                                    } else {
                                        c.setValue("")
                                    }
                                });
                            a.grid.store.reload({
                                params: a.searchParams
                            });
                            a.grid.searchStore = a.searchParams
                        }
                    }]
            }),
            items: [this.searchPanel, this.assets]
        })
    },
    Sort: function() {
        var c = this;
        this.ID_btn_collapseExpand2 = Ext.id();
        sortRoot = new Ext.tree.AsyncTreeNode({
            expanded: true
        });
        var b = new Ext.tree.TreeLoader({
            dataUrl: c.baseUrl + "&task=getSortList&type=combo",
            preloadChildren: true,
            clearOnLoad: false,
            listeners: {
                load: function(d) {
                    sortRoot.firstChild.disable();
                    if (Ext.getCmp(c.ID_btn_collapseExpand2).pressed) {
                        sortRoot.collapse(true);
                        sortRoot.expand(false)
                    } else {
                        sortRoot.expand(true)
                    }
                }
            }
        });
        var a = new Ext.tree.TreePanel({
            border: false,
            animate: false,
            containerScroll: true,
            autoScroll: true,
            rootVisible: false,
            loader: b,
            root: sortRoot,
            checkModel: "single",
            bodyStyle: "background-color: #FFF",
            tbar: new Ext.Toolbar({
                style: "border-right-width:1px;",
                items: [{
                    text: lang("expand"),
                    iconCls: "icon-expand-all",
                    id: this.ID_btn_collapseExpand2,
                    tooltip: lang("expandMenuTip"),
                    enableToggle: true,
                    toggleHandler: function(d, e) {
                        if (e) {
                            d.setIconClass("icon-expand-all");
                            d.setText(lang("expand"));
                            d.setTooltip(lang("expandMenuTip"));
                            sortRoot.collapse(true);
                            sortRoot.firstChild.expand()
                        } else {
                            d.setIconClass("icon-collapse-all");
                            d.setText(lang("collapse"));
                            d.setTooltip(lang("collapseMenuTip"));
                            sortRoot.expand(true)
                        }
                    }
                }]
            }),
            listeners: {
                checkchange: function(g, f) {
                    if (f) {
                        var d = a.getChecked();
                        if (d && d.length) {
                            for (var e = 0; e < d.length; e++) {
                                if (d[e].id != g.id) {
                                    d[e].getUI().toggleCheck(false);
                                    d[e].attributes.checked = false
                                }
                            }
                        }
                        c.nodeId = g.id;
                        c.nodeText = g.text
                    } else {
                        c.nodeId = "";
                        c.nodeText = ""
                    }
                }
            }
        });
        this.editwin = new Ext.Window({
            border: false,
            width: 400,
            height: 500,
            closeAction: "hide",
            layout: "fit",
            modal: true,
            buttons: [{
                text: lang("ok"),
                handler: function() {
                    var d = a.getChecked();
                    if (c.nodeId) {
                        if (d != "") {
                            Ext.getCmp(c.ID_searchSort).setValue(c.nodeId);
                            Ext.getCmp(c.ID_Sort).setValue(c.nodeText)
                        } else {
                            Ext.getCmp(c.ID_searchSort).setValue("");
                            Ext.getCmp(c.ID_Sort).setValue("")
                        }
                    }
                    sortRoot.reload();
                    c.editwin.hide()
                }
            },
                {
                    text: lang("cancel"),
                    handler: function() {
                        sortRoot.reload();
                        c.editwin.hide()
                    }
                }],
            items: [a]
        }).show()
    },
    assetsmanagement: function() {
        var a = this;
        this.ID_searchSort = Ext.id();
        this.ID_Sort = Ext.id();
        this.searchParams = {
            searchName: "",
            searchSort: "",
            searchDept: ""
        };
        var b = new Ext.PagingToolbar({
            plugin: [new Ext.grid.plugins.ComboPageSize()],
            style: "border-left-width:1px;",
            displayInfo: true,
            emptyMsg: lang("pagingToolbarEmptyMsg"),
            displayMsg: lang("showDataTotal2"),
            store: a.store,
            pageSize: 15
        });
        makeOperate = function(e, d, c) {
            var f = c.json.id;
            return "<a href='javascript:void(0);' class='gridview' onclick='CNOA_assets_assets_assetsmanagement.viewCustomer(" + f + ");'>" + lang("baseInfo2") + "</a><a href='javascript:void(0);' class='gridview2 jianju' onclick='CNOA_assets_assets_assetsmanagement.photoWindow(" + f + ");'>" + lang("picture") + "</a><a href='javascript:void(0);' class='gridview4 jianju' onclick='CNOA_assets_assets_assetsmanagement.assetFacility(" + f + ");'>" + lang("accessories") + "</a>"
        };
        this.grid = new Ext.grid.CustomGridPanel({
            searchStore: a.searchParams,
            border: false,
            region: "center",
            bodyStyle: "border-left-width:1px;",
            bbar: b,
            customFiledUrl: a.baseUrl + "&task=getCustomField",
            storeUrl: a.baseUrl + "&task=getCustomList",
            otherField: [{
                header: lang("opt"),
                dataIndex: "",
                width: 230,
                renderer: makeOperate
            }],
            tbar: new Ext.Toolbar({
                items: [{
                    xtype: "button",
                    text: lang("refresh"),
                    iconCls: "icon-system-refresh",
                    handler: function() {
                        a.grid.store.load()
                    }
                },
                    {
                        text: lang("add"),
                        iconCls: "icon-utils-s-add",
                        handler: function() {
                            a.getStatus()
                        }
                    },
                    {
                        text: lang("modify"),
                        iconCls: "icon-utils-s-edit",
                        handler: function(c) {
                            var d = a.grid.getSelectionModel().getSelections();
                            if (d.length == 0) {
                                CNOA.miniMsg.alertShowAt(c, lang("mustSelectOneRow"));
                                return false
                            } else {
                                if (d.length != 1) {
                                    CNOA.miniMsg.alertShowAt(c, lang("selectItemOnly"))
                                } else {
                                    a.addWin(lang("modify"), d[0].json)
                                }
                            }
                        }
                    },
                    {
                        xtype: "importButton",
                        cls: "btn-blue3",
                        browseTitle: "导入资产",
                        url: a.baseUrl + "&task=import_assets"
                    },
                    {
                        text: lang("export2"),
                        cls: "btn-blue3",
                        iconCls: "icon-excel",
                        handler: function(c, e) {
                            rows = a.grid.getSelectionModel().getSelections();
                            var d = [];
                            Ext.each(rows,
                                function(g, f) {
                                    d.push(g.json.id)
                                });
                            a.openExportExcelWindow(d.join(","))
                        }
                    },
                    {
                        text: lang("del"),
                        iconCls: "icon-utils-s-delete",
                        handler: function(c) {
                            var d = a.grid.getSelectionModel().getSelections();
                            if (d.length == 0) {
                                CNOA.miniMsg.alertShowAt(c, lang("mustSelectOneRow"))
                            } else {
                                CNOA.msg.cf(lang("confirmToDelete"),
                                    function(e) {
                                        if (e == "yes") {
                                            var f = [];
                                            Ext.each(d,
                                                function(h, g) {
                                                    f.push(h.json.id)
                                                });
                                            a.delAssets(f.join(","))
                                        }
                                    })
                            }
                        }
                    }]
            }),
            listeners: {
                dblclick: function() {
                    var c = a.grid.getSelectionModel().getSelections();
                    if (c.length == 0) {
                        CNOA.msg.alert(lang("pleaseSelectOne1"));
                        return false
                    }
                    a.addWin(lang("modify"), c[0].json)
                }
            }
        });
        return this.grid
    },
    getStatus: function() {
        var a = this;
        Ext.Ajax.request({
            url: a.baseUrl + "&task=getStatus",
            success: function(b) {
                var c = Ext.decode(b.responseText);
                a.inputStatus = c.status;
                a.addNumber = c.cnum;
                a.addWin(lang("add"), 0)
            }
        })
    },
    getSearchPanel: function() {
        return new Ext.form.FormPanel({
            border: false,
            height: 150,
            hidden: true,
            region: "north",
            labelAlign: "right",
            padding: 5,
            autoScroll: true,
            split: true,
            defaults: {
                xtype: "textfield"
            }
        })
    },
    addWin: function(e, a) {
        var b = this;
        var c = function(f) {
            var g = f.getForm();
            if (g.isValid()) {
                f.getForm().submit({
                    url: b.baseUrl + "&task=addAssets",
                    params: {
                        id: a.id
                    },
                    method: "POST",
                    success: function(h, i) {
                        CNOA.msg.notice2(lang("successopt"));
                        b.grid.store.reload();
                        d.close()
                    },
                    failure: function(h, i) {
                        CNOA.msg.alert(i.result.msg)
                    }
                })
            }
        };
        var d = new Ext.AssetsCustomWindow({
            title: e,
            width: 600,
            height: 600,
            baseUrl: b.baseUrl,
            customColumns: b.grid.customColumns,
            submit: c
        }).show();
        if (a) {
            d.get(0).getForm().findField("cnum").setReadOnly(true);
            d.get(0).getForm().setValues(a)
        } else {
            if (b.inputStatus == 1) {
                d.get(0).getForm().findField("cnum").setReadOnly(true);
                d.get(0).getForm().findField("cnum").setValue(b.addNumber)
            }
        }
    },
    delAssets: function(b) {
        var a = this;
        Ext.Ajax.request({
            url: a.baseUrl + "&task=delAssets",
            params: {
                id: b
            },
            success: function(e, d) {
                var c = Ext.decode(e.responseText);
                if (c.success == true) {
                    a.grid.store.reload();
                    CNOA.msg.notice2(lang("successopt"))
                } else {
                    if (c.failure == true) {
                        CNOA.msg.alert(c.msg)
                    }
                }
            }
        })
    },
    viewCustomer: function(b) {
        var a = this;
        window.open(a.baseUrl + "&task=viewAssets&id=" + b, "viewAssets", "width=700, height=" + (screen.availHeight - 430) + ",left=" + ((screen.availWidth - 700) / 2) + ",top=60,scrollbars=yes,resizable=yes,status=no")
    },
    photoWindow: function(f) {
        var e = this;
        var a = [{
            name: "id"
        },
            {
                name: "value"
            },
            {
                name: "fileTime"
            },
            {
                name: "uname"
            }];
        this.photoStore = new Ext.data.Store({
            autoLoad: true,
            proxy: new Ext.data.HttpProxy({
                url: e.baseUrl + "&task=getPhotoList&id=" + f
            }),
            reader: new Ext.data.JsonReader({
                totalProperty: "total",
                root: "data",
                fields: a
            })
        });
        var b = new Ext.grid.CheckboxSelectionModel();
        var c = new Ext.grid.ColumnModel({
            columns: [new Ext.grid.RowNumberer(), b, {
                header: "id",
                dataIndex: "id",
                hidden: true
            },
                {
                    header: lang("picture"),
                    dataIndex: "value",
                    width: 150
                },
                {
                    header: lang("filingDate"),
                    dataIndex: "fileTime",
                    width: 140
                },
                {
                    header: lang("operator"),
                    dataIndex: "uname",
                    width: 80
                }]
        });
        this.photoGrid = new Ext.grid.GridPanel({
            columnWidth: 0.6,
            height: 300,
            border: false,
            store: this.photoStore,
            cm: c,
            sm: b,
            tbar: [{
                text: lang("refresh"),
                iconCls: "icon-system-refresh",
                handler: function() {
                    e.photoGrid.store.reload()
                }
            },
                {
                    text: lang("add"),
                    iconCls: "icon-utils-s-add",
                    handler: function() {
                        e.updatePhoto(f)
                    }
                },
                {
                    text: lang("del"),
                    iconCls: "icon-utils-s-delete",
                    handler: function(g) {
                        var h = e.photoGrid.getSelectionModel().getSelections();
                        if (h.length == 0) {
                            CNOA.miniMsg.alertShowAt(g, lang("mustSelectOneRow"))
                        } else {
                            CNOA.msg.cf(lang("confirmToDelete"),
                                function(i) {
                                    if (i == "yes") {
                                        var j = [];
                                        Ext.each(h,
                                            function(l, k) {
                                                j.push(l.json.id)
                                            });
                                        e.delPhoto(j.join(","))
                                    }
                                })
                        }
                    }
                }],
            listeners: {
                rowclick: function(h, k, j) {
                    var i = h.getSelectionModel().getSelected();
                    var g = i.id;
                    e.getOnePhoto(g)
                }
            }
        });
        var d = new Ext.Window({
            title: lang("picture"),
            width: 720,
            height: 400,
            layout: "fit",
            border: false,
            modal: true,
            items: [new Ext.Panel({
                layout: "column",
                items: [this.photoGrid, new Ext.Panel({
                    border: false,
                    height: 400,
                    bodyStyle: "padding: 50px 0px 0px 0px;border-left: 1px solid #99BBE8",
                    columnWidth: 0.4,
                    items: [{
                        border: false,
                        html: '<div style="width:273px;height:206px;padding:3px;display: table-cell;vertical-align: middle;text-align: center;"><img style="max-width: 267px; max-height: 200px; border: 0px;overflow:hidden;" src="" id="CNOA_ASSETS_INFO_PHOTO"></div>'
                    }]
                })]
            })]
        }).show()
    },
    updatePhoto: function(b) {
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
                name: "photo",
                allowBlank: false,
                blankText: lang("updateFaceBlankText"),
                buttonCfg: {
                    text: lang("browse")
                },
                hideLabel: true,
                width: 370,
                listeners: {
                    fileselected: function(d, c) {}
                }
            }],
            buttons: [{
                text: lang("save"),
                handler: function() {
                    if (this.FACE_WINDOW_FORMPANEL.getForm().isValid()) {
                        this.FACE_WINDOW_FORMPANEL.getForm().submit({
                            url: a.baseUrl + "&task=upPhoto",
                            waitMsg: lang("waiting"),
                            params: {
                                id: b
                            },
                            success: function(c, d) {
                                Ext.fly("CNOA_ASSETS_INFO_PHOTO").dom.src = d.result.msg;
                                a.photoStore.reload();
                                a.FACE_WINDOW.close()
                            },
                            failure: function(c, d) {
                                CNOA.msg.alert(d.result.msg,
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
            height: 123,
            autoScroll: false,
            modal: true,
            resizable: false,
            title: lang("upload") + lang("photo"),
            items: [this.FACE_WINDOW_FORMPANEL]
        }).show()
    },
    getOnePhoto: function(a) {
        var b = this;
        if (!a) {
            return
        }
        Ext.Ajax.request({
            url: b.baseUrl + "&task=getOnePhoto",
            params: {
                id: a
            },
            method: "POST",
            success: function(c) {
                var d = Ext.decode(c.responseText);
                Ext.fly("CNOA_ASSETS_INFO_PHOTO").dom.src = d.msg
            }
        })
    },
    delPhoto: function(a) {
        var b = this;
        Ext.Ajax.request({
            url: b.baseUrl + "&task=delPhoto",
            params: {
                ids: a
            },
            method: "POST",
            success: function(c) {
                var d = Ext.decode(c.responseText);
                if (d.success == true) {
                    CNOA.msg.alert(d.msg);
                    b.photoGrid.store.reload()
                }
            }
        })
    },
    assetFacility: function(h) {
        var f = this;
        var a = [{
            name: "faid"
        },
            {
                name: "facilitynum"
            },
            {
                name: "facilityname"
            },
            {
                name: "measure"
            },
            {
                name: "supplier"
            },
            {
                name: "regtime"
            },
            {
                name: "remark"
            }];
        this.facilityStore = new Ext.data.Store({
            autoLoad: true,
            proxy: new Ext.data.HttpProxy({
                url: f.baseUrl + "&task=getFacilityList&id=" + h
            }),
            reader: new Ext.data.JsonReader({
                totalProperty: "total",
                root: "data",
                fields: a
            })
        });
        var g = new Ext.PagingToolbar({
            displayInfo: true,
            emptyMsg: lang("pagingToolbarEmptyMsg"),
            displayMsg: lang("showDataTotal2"),
            store: this.facilityStore,
            pageSize: 15
        });
        var c = new Ext.grid.CheckboxSelectionModel();
        var d = new Ext.grid.ColumnModel({
            columns: [new Ext.grid.RowNumberer(), c, {
                header: "id",
                dataIndex: "faid",
                hidden: true
            },
                {
                    header: lang("deviceNum"),
                    dataIndex: "facilitynum",
                    width: 130
                },
                {
                    header: lang("deviceName"),
                    dataIndex: "facilityname",
                    width: 130
                },
                {
                    header: lang("uniteMeasure"),
                    dataIndex: "measure",
                    width: 80
                },
                {
                    header: lang("supplier"),
                    dataIndex: "supplier",
                    width: 80
                },
                {
                    header: lang("registerTime"),
                    dataIndex: "regtime",
                    width: 80
                },
                {
                    header: lang("remark"),
                    dataIndex: "remark",
                    width: 80
                }]
        });
        var e = new Ext.grid.GridPanel({
            height: 300,
            border: false,
            store: this.facilityStore,
            cm: d,
            sm: c,
            bbar: g,
            tbar: [{
                text: lang("refresh"),
                iconCls: "icon-system-refresh",
                handler: function() {
                    f.facilityStore.reload()
                }
            },
                {
                    text: lang("add"),
                    iconCls: "icon-utils-s-add",
                    handler: function() {
                        f.addFacility("add", h, 0)
                    }
                },
                {
                    text: lang("modify"),
                    iconCls: "icon-utils-s-edit",
                    handler: function(i) {
                        var k = e.getSelectionModel().getSelections();
                        if (k.length == 0) {
                            CNOA.miniMsg.alertShowAt(i, lang("mustSelectOneRow"))
                        } else {
                            var j = e.getSelectionModel().getSelected().json;
                            faid = j.faid;
                            f.addFacility("editFacility", h, faid)
                        }
                    }
                },
                {
                    text: lang("del"),
                    iconCls: "icon-utils-s-delete",
                    handler: function(i) {
                        var j = e.getSelectionModel().getSelections();
                        if (j.length == 0) {
                            CNOA.miniMsg.alertShowAt(i, lang("mustSelectOneRow"))
                        } else {
                            CNOA.msg.cf(lang("confirmToDelete"),
                                function(k) {
                                    if (k == "yes") {
                                        var l = [];
                                        Ext.each(j,
                                            function(n, m) {
                                                l.push(n.json.faid)
                                            });
                                        f.delFacility(l.join(","))
                                    }
                                })
                        }
                    }
                }],
            listeners: {
                celldblclick: function(i, l, k) {
                    var j = e.getSelectionModel().getSelected().json;
                    faid = j.faid;
                    if (j.length == 0) {
                        CNOA.msg.alert(lang("mustSelectOneRow"));
                        return false
                    }
                    f.addFacility("editFacility", h, faid)
                }
            }
        });
        var b = new Ext.Window({
            title: lang("accessories"),
            autoWidth: true,
            height: 500,
            layout: "fit",
            border: false,
            modal: true,
            items: [e]
        }).show()
    },
    getComboStore: function(a) {
        var b = this;
        var c = new Ext.data.Store({
            autoLoad: true,
            proxy: new Ext.data.HttpProxy({
                url: b.baseUrl + "&task=comboStore&type=" + a,
                disableCaching: true
            }),
            reader: new Ext.data.JsonReader({
                root: "data",
                fields: [{
                    name: "id"
                },
                    {
                        name: "value"
                    }]
            })
        });
        return c
    },
    addFacility: function(b, f, i) {
        var k = this;
        var d = function() {
            var m = g.getValue();
            var n = e.getValue();
            var o = n * m;
            h.setValue(o)
        };
        var g = new Ext.form.TextField({
            fieldLabel: lang("unitPrice"),
            name: "price",
            enableKeyEvents: true,
            listeners: {
                keyup: function(m, n) {
                    d()
                },
                change: function(m, n) {
                    d()
                }
            }
        });
        var e = new Ext.form.TextField({
            fieldLabel: lang("quantity"),
            name: "num",
            enableKeyEvents: true,
            listeners: {
                keyup: function(m, n) {
                    d()
                },
                change: function(m, n) {
                    d()
                }
            }
        });
        var h = new Ext.form.TextField({
            fieldLabel: lang("amount"),
            readOnly: true,
            width: 80,
            name: "sum"
        });
        var a = new Ext.form.FormPanel({
            border: false,
            hideBorders: true,
            labelAlign: "right",
            labelWidth: 70,
            padding: 5,
            layout: "column",
            autoScroll: true,
            defaults: {
                columnWidth: 0.5,
                layout: "form",
                defaults: {
                    width: 120,
                    xtype: "textfield"
                }
            },
            items: [{
                items: [{
                    fieldLabel: lang("deviceNum"),
                    name: "facilitynum",
                    allowBlank: false
                },
                    {
                        fieldLabel: lang("deviceName"),
                        name: "facilityname"
                    },
                    {
                        fieldLabel: lang("registerTime"),
                        xtype: "datefield",
                        format: "Y-m-d",
                        allowBlank: false,
                        value: new Date(),
                        editable: false,
                        name: "regtime"
                    }]
            },
                {
                    items: [{
                        xtype: "combo",
                        fieldLabel: lang("uniteMeasure"),
                        name: "measure",
                        editable: false,
                        triggerAction: "all",
                        typeAhead: true,
                        hiddenName: "measure",
                        mode: "local",
                        valueField: "id",
                        displayField: "value",
                        store: k.getComboStore("measure")
                    },
                        {
                            xtype: "combo",
                            fieldLabel: lang("supplier"),
                            name: "supplier",
                            editable: false,
                            triggerAction: "all",
                            typeAhead: true,
                            hiddenName: "supplier",
                            mode: "local",
                            valueField: "id",
                            displayField: "value",
                            store: k.getComboStore("supplier")
                        },
                        {
                            fieldLabel: lang("remark"),
                            name: "remark"
                        }]
                }]
        });
        var l = b == "editFacility" ? lang("modify") : lang("add");
        var c = new Ext.Window({
            width: 500,
            autoHeight: true,
            modal: true,
            title: l + lang("accessories"),
            items: [a],
            buttons: [{
                text: lang("ok"),
                iconCls: "icon-btn-save",
                handler: function() {
                    j(b, f, i)
                }
            },
                {
                    text: lang("cancel"),
                    iconCls: "icon-dialog-cancel",
                    handler: function() {
                        c.close()
                    }
                }]
        }).show();
        var j = function(n, m, o) {
            if (a.getForm().isValid()) {
                a.getForm().submit({
                    url: k.baseUrl + "&task=addFacility",
                    params: {
                        faid: o,
                        task: n,
                        pid: m
                    },
                    method: "POST",
                    success: function(p, q) {
                        CNOA.msg.notice2(lang("successopt"));
                        k.facilityStore.reload();
                        c.close()
                    },
                    failure: function(p, q) {
                        CNOA.msg.alert(q.result.msg)
                    }
                })
            }
        };
        if (b == "editFacility") {
            a.getForm().load({
                url: k.baseUrl + "&task=" + b,
                params: {
                    faid: i
                },
                method: "POST"
            })
        }
    },
    delFacility: function(a) {
        var b = this;
        Ext.Ajax.request({
            url: b.baseUrl + "&task=delFacility",
            params: {
                ids: a
            },
            method: "POST",
            success: function(c) {
                var d = Ext.decode(c.responseText);
                if (d.success == true) {
                    CNOA.msg.notice2(lang("successopt"));
                    b.facilityStore.reload()
                }
            }
        })
    },
    openExportExcelWindow: function(b) {
        var c = this;
        var d = function(h, g) {
            if (a.getForm().isValid()) {
                a.getForm().submit({
                    url: c.baseUrl + "&task=exportExcel&step=1",
                    waitMsg: lang("waiting"),
                    params: {
                        ids: h,
                        deptID: g
                    },
                    method: "POST",
                    success: function(i, j) {
                        ajaxDownload(c.baseUrl + "&task=exportExcel&file=" + j.result.msg + "&step=2")
                    },
                    failure: function(i, j) {
                        CNOA.msg.alert(j.result.msg)
                    }
                })
            }
        };
        var f = new Ext.form.SelectorForm({
            multiselect: true,
            selectorType: "dept",
            hiddenName: "deptID",
            fieldLabel: lang("inDepartment")
        });
        var a = new Ext.form.FormPanel({
            border: false,
            bodyStyle: "padding:10px;",
            waitMsgTarget: true,
            labelWidth: 110,
            items: [f, {
                xtype: "displayfield",
                hideLabel: true,
                value: "<span style='color: red'>说明:导出所在部门的所有人事信息</span>"
            },
                {
                    xtype: "checkbox",
                    fieldLabel: "导出部分数据",
                    name: "check"
                },
                {
                    xtype: "displayfield",
                    hideLabel: true,
                    value: "<span style='color: red'>说明:请先选择需要导出的部分数据,导出部分数据请不要选择所在部门</span>"
                }]
        });
        var e = new Ext.Window({
            width: 320,
            height: 250,
            title: lang("exportPerson"),
            resizable: false,
            modal: true,
            layout: "fit",
            items: a,
            buttons: [{
                text: lang("export2"),
                iconCls: "icon-excel",
                cls: "btn-blue3",
                handler: function() {
                    if (f.getValue() == " " && a.getForm().findField("check").checked == " ") {
                        CNOA.msg.alert("请确认需要导出的数据")
                    } else {
                        var g = f.getValue();
                        if (a.getForm().findField("check").checked == " ") {
                            d("", g)
                        } else {
                            d(b, g)
                        }
                    }
                }.createDelegate(this)
            },
                {
                    text: lang("cancel"),
                    iconCls: "icon-dialog-cancel",
                    handler: function() {
                        e.close()
                    }.createDelegate(this)
                }]
        }).show()
    },
    openDownloadExcelWindow: function(a) {
        var b = new Ext.Window({
            width: 320,
            height: 150,
            title: lang("downExcel"),
            resizable: false,
            modal: true,
            layout: "fit",
            bodyStyle: "padding:10px;background-color: #FFF;",
            html: lang("clickToDownload") + "：<br/>" + a,
            buttons: [{
                text: lang("close"),
                iconCls: "icon-dialog-cancel",
                handler: function() {
                    b.close()
                }.createDelegate(this)
            }]
        }).show()
    }
};
var CNOA_assets_assets_secondmentClass, CNOA_assets_assets_secondment;
CNOA_assets_assets_secondmentClass = new CNOA.Class.create();
CNOA_assets_assets_secondmentClass.prototype = {
    init: function() {
        var a = this;
        this.searchParams = {
            searchNumber: "",
            searchName: "",
            searchAssetsName: "",
            searchBorrowDate: "",
            searchEndBorrowDate: "",
            searchReturnDate: "",
            searchEndReturnDate: "",
            searchDept: "",
            searchReceiver: "",
            searchReturnStatus: ""
        };
        this.ID_receiver = Ext.id();
        this.baseUrl = "assets/secondment";
        this.second = this.secondment();
        this.searchPanel = this.getSearchPanel();
        this.searchName = new Ext.form.TextField({
            width: 80,
            fieldLabel: lang("secondmentsNumber")
        });
        this.searchNumber = new Ext.form.ComboBox({
            name: "cnum",
            width: 115,
            editable: false,
            triggerAction: "all",
            typeAhead: true,
            hiddenName: "cnum",
            mode: "local",
            valueField: "id",
            displayField: "cnum",
            store: a.getComboStore("cnum")
        });
        this.searchBorrowDate = new Ext.form.DateField({
            width: 90,
            editable: false,
            format: "Y-m-d"
        });
        this.searchEndBorrowDate = new Ext.form.DateField({
            width: 90,
            editable: false,
            format: "Y-m-d"
        });
        this.searchReturnDate = new Ext.form.DateField({
            width: 90,
            editable: false,
            format: "Y-m-d"
        });
        this.searchEndReturnDate = new Ext.form.DateField({
            width: 90,
            editable: false,
            format: "Y-m-d"
        });
        this.mainPanel = new Ext.Panel({
            border: false,
            layout: "border",
            tbar: new Ext.Toolbar({
                height: 32,
                style: "padding-top: 5px",
                xtype: "form",
                items: [lang("asetNum") + ":", a.searchNumber, lang("secondmentsNumber") + ":", a.searchName, lang("lendTime") + ":", a.searchBorrowDate, lang("to"), a.searchEndBorrowDate, lang("returnTime") + ":", a.searchReturnDate, lang("to"), a.searchEndReturnDate, {
                    xtype: "button",
                    text: lang("search"),
                    click: function(b) {
                        a.searchParams.searchNumber = a.searchNumber.getValue();
                        a.searchParams.searchName = a.searchName.getValue();
                        a.searchParams.searchAssetsName = a.searchAssetsName.getValue();
                        a.searchParams.searchReturnDate = a.searchReturnDate.getRawValue();
                        a.searchParams.searchEndReturnDate = a.searchEndReturnDate.getRawValue();
                        a.searchParams.searchBorrowDate = a.searchBorrowDate.getRawValue();
                        a.searchParams.searchEndBorrowDate = a.searchEndBorrowDate.getRawValue();
                        a.searchParams.searchDept = a.searchDept.getValue();
                        a.searchParams.searchReceiver = a.ID_search_name_uid.getValue();
                        a.searchParams.searchReturnStatus = a.searchReturnStatus.getValue();
                        a.grid.store.reload({
                            params: a.searchParams
                        })
                    }
                },
                    {
                        xtype: "button",
                        text: lang("expandSearch"),
                        handler: function(b) {
                            if (a.searchPanel.isVisible()) {
                                b.setText(lang("expandSearch"));
                                a.searchPanel.hide()
                            } else {
                                b.setText(lang("foldSearch"));
                                a.searchPanel.show()
                            }
                            a.mainPanel.doLayout()
                        }
                    },
                    {
                        xtype: "button",
                        text: lang("clear2"),
                        handler: function() {
                            a.searchNumber.setValue("");
                            a.searchName.setValue("");
                            a.searchAssetsName.setValue("");
                            a.searchReturnDate.setValue("");
                            a.searchEndReturnDate.setValue("");
                            a.searchBorrowDate.setValue("");
                            a.searchEndBorrowDate.setValue("");
                            a.searchDept.setValue("");
                            a.ID_search_name_uid.setValue("");
                            a.searchReceiver.setValue("");
                            a.searchReturnStatus.setValue("");
                            a.searchParams.searchNumber = "";
                            a.searchParams.searchName = "";
                            a.searchParams.searchAssetsName = "";
                            a.searchParams.searchReturnDate = "";
                            a.searchParams.searchEndReturnDate = "";
                            a.searchParams.searchBorrowDate = "";
                            a.searchParams.searchEndBorrowDate = "";
                            a.searchParams.searchDept = "";
                            a.searchParams.searchReceiver = "";
                            a.searchParams.searchReturnStatus = "";
                            a.grid.store.reload({
                                params: a.searchParams
                            })
                        }
                    }]
            }),
            items: [this.searchPanel, this.second]
        })
    },
    secondment: function() {
        var e = this;
        var c = [{
            name: "borrowNumber"
        },
            {
                name: "assetsName"
            },
            {
                name: "borrowDate"
            },
            {
                name: "acceptDpt"
            },
            {
                name: "receiver"
            },
            {
                name: "returnDate"
            },
            {
                name: "status"
            },
            {
                name: "remark"
            }];
        var g = new Ext.grid.CheckboxSelectionModel();
        var b = function(j, i, h) {
            var k = h.json.id;
            if (h.json.status == "1" && h.json.returnDate == " ") {
                return "<a href='javascript:void(0);' class='gridview' onclick='CNOA_assets_assets_secondment.changeEdit(" + k + ");'>未使用</a>"
            } else {
                if (h.json.status == "1") {
                    return "<a href='javascript:void(0);' class='gridview' onclick='CNOA_assets_assets_secondment.changeEdit(" + k + ");'>已归还</a>"
                } else {
                    if (h.json.status == "5") {
                        return "<a href='javascript:void(0);' class='gridview' onclick='CNOA_assets_assets_secondment.changeEdit(" + k + ");'>已丢失</a>"
                    } else {
                        return "<a href='javascript:void(0);' class='gridview' onclick='CNOA_assets_assets_secondment.changeStatus(" + k + ");'>" + lang("restitution") + "</a>"
                    }
                }
            }
        };
        var d = function(h) {
            if (h == "1") {
                h = "待用"
            } else {
                if (h == "2") {
                    h = "在用"
                } else {
                    if (h == "3") {
                        h = "外借"
                    } else {
                        if (h == "4") {
                            h = "维修"
                        } else {
                            if (h == "5") {
                                h = "已丢失"
                            }
                        }
                    }
                }
            }
            return h
        };
        var a = new Ext.grid.ColumnModel({
            border: false,
            defaults: {
                sortable: true,
                menuDisabled: true,
                width: 130
            },
            columns: [new Ext.grid.RowNumberer(), g, {
                header: "id",
                dataIndex: "id",
                hidden: true,
                width: 80
            },
                {
                    header: lang("asetNum") + "+" + lang("secondmentsNumber"),
                    dataIndex: "borrowNumber",
                    width: 170
                },
                {
                    header: lang("stdname"),
                    dataIndex: "assetsName"
                },
                {
                    header: lang("lendTime"),
                    dataIndex: "borrowDate"
                },
                {
                    header: lang("useDept"),
                    dataIndex: "acceptDpt"
                },
                {
                    header: lang("useHuman"),
                    dataIndex: "receiver"
                },
                {
                    header: lang("returnTime"),
                    dataIndex: "returnDate"
                },
                {
                    header: lang("assetStatus"),
                    dataIndex: "status",
                    renderer: d
                },
                {
                    header: lang("remark"),
                    dataIndex: "remark"
                },
                {
                    header: lang("opt"),
                    dataIndex: "",
                    renderer: b
                }]
        });
        this.store = new Ext.data.Store({
            autoLoad: true,
            baseParams: e.searchParams,
            proxy: new Ext.data.HttpProxy({
                url: e.baseUrl + "&task=getList",
                disableCaching: true
            }),
            reader: new Ext.data.JsonReader({
                totalProperty: "total",
                root: "data",
                fields: c
            }),
            listeners: {
                exception: function(m, l, n, k, i) {
                    var j = i.responseText;
                    if (j != "") {
                        var h = Ext.decode(j);
                        if (h.failure) {
                            CNOA.msg.alert(h.msg)
                        }
                    }
                }
            }
        });
        var f = new Ext.PagingToolbar({
            plugins: [new Ext.grid.plugins.ComboPageSize()],
            style: "border-left-width:1px;",
            displayInfo: true,
            emptyMsg: lang("pagingToolbarEmptyMsg"),
            displayMsg: lang("showDataTotal2"),
            store: this.store,
            pageSize: 15
        });
        this.grid = new Ext.grid.GridPanel({
            border: false,
            searchStore: e.searchParams,
            store: this.store,
            region: "center",
            sm: g,
            cm: a,
            bbar: f,
            tbar: [{
                text: lang("refresh"),
                iconCls: "icon-system-refresh",
                handler: function() {
                    e.store.reload()
                }
            },
                {
                    text: lang("add"),
                    iconCls: "icon-utils-s-add",
                    handler: function() {
                        e.secondWin(0, "addSecondment")
                    }
                },
                {
                    text: lang("secondChange"),
                    iconCls: "icon-utils-s-edit",
                    cls: "btn-red1",
                    handler: function(i) {
                        var j = e.grid.getSelectionModel().getSelections();
                        if (j.length == 0) {
                            CNOA.miniMsg.alertShowAt(i, lang("mustSelectOneRow"))
                        } else {
                            var k = e.grid.getSelectionModel().getSelections();
                            if (k.length > 1) {
                                CNOA.miniMsg.alertShowAt(i, lang("doNotSBatchM"));
                                return false
                            }
                            var h = e.grid.getSelectionModel().getSelected().id;
                            e.secondWin(h, "editSecondment");
                            e.cnum.setReadOnly(true);
                            e.assetsName.setReadOnly(true);
                            e.borrowNumber.setReadOnly(true)
                        }
                    }
                },
                {
                    text: lang("export2"),
                    iconCls: "icon-excel",
                    cls: "btn-blue3",
                    handler: function() {
                        Ext.Ajax.request({
                            url: e.baseUrl + "&task=exportExcel&step=1",
                            method: "POST",
                            success: function(h, i) {
                                var j = Ext.decode(h.responseText);
                                ajaxDownload(e.baseUrl + "&task=exportExcel&file=" + j.msg + "&step=2")
                            }
                        })
                    }
                },
                {
                    text: lang("del"),
                    iconCls: "icon-utils-s-delete",
                    handler: function(h) {
                        var i = e.grid.getSelectionModel().getSelections();
                        if (i.length == 0) {
                            CNOA.miniMsg.alertShowAt(h, lang("mustSelectOneRow"))
                        } else {
                            CNOA.msg.cf(lang("confirmToDelete"),
                                function(j) {
                                    if (j == "yes") {
                                        var k = [];
                                        Ext.each(i,
                                            function(m, l) {
                                                k.push(m.id)
                                            });
                                        e.delSecondment(k.join(","))
                                    }
                                })
                        }
                    }
                }],
            listeners: {
                celldblclick: function(i, k, j) {
                    var h = e.grid.getSelectionModel().getSelected().id;
                    if (h.length == 0) {
                        CNOA.msg.alert(lang("mustSelectOneRow"));
                        return false
                    }
                    e.secondWin(h, "editSecondment");
                    e.cnum.setReadOnly(true);
                    e.assetsName.setReadOnly(true);
                    e.borrowNumber.setReadOnly(true)
                }
            }
        });
        return this.grid
    },
    getSearchPanel: function() {
        var b = this;
        this.searchAssetsName = new Ext.form.ComboBox({
            name: "assetsName",
            fieldLabel: lang("stdname"),
            width: 115,
            editable: false,
            triggerAction: "all",
            typeAhead: true,
            hiddenName: "assetsName",
            mode: "local",
            valueField: "id",
            displayField: "assetsName",
            store: b.getComboStore("assetsName")
        });
        this.ID_search_truename = Ext.id();
        this.ID_search_name_uid = new Ext.form.Hidden({
            id: b.ID_search_truename
        });
        this.searchReceiver = new Ext.form.TextField({
            width: 60,
            fieldLabel: lang("useHuman"),
            readOnly: true,
            listeners: {
                render: function(c) {
                    c.to = b.ID_search_truename
                },
                focus: function(c) {
                    people_selector("user", c, false, false)
                }
            }
        });
        this.searchReturnStatus = new Ext.form.ComboBox({
            width: 60,
            typeAhead: true,
            fieldLabel: lang("assetStatus"),
            triggerAction: "all",
            lazyRender: true,
            editable: false,
            mode: "local",
            store: new Ext.data.SimpleStore({
                fields: ["id", "value"],
                data: [["1", lang("standby")], ["2", lang("Use")], ["3", lang("forCirculation")], ["4", lang("maintain")], ["5", lang("lost")]]
            }),
            valueField: "id",
            displayField: "value",
            hiddenName: "value"
        });
        this.searchDept = new CNOA.selector.form.DepartmentSelectorField({
            width: 60,
            fieldLabel: lang("useDept")
        });
        var a = new Ext.form.FormPanel({
            border: false,
            hidden: true,
            region: "north",
            labelAlign: "right",
            padding: 5,
            autoScroll: true,
            split: true,
            defaults: {
                xtype: "textfield",
                width: 120
            },
            items: [this.searchAssetsName, b.searchDept, b.searchReturnStatus, b.searchReceiver]
        });
        return a
    },
    getComboStore: function(b) {
        var c = this;
        var a = new Ext.data.Store({
            autoLoad: true,
            proxy: new Ext.data.HttpProxy({
                url: c.baseUrl + "&task=cnumstore",
                disableCaching: true
            }),
            reader: new Ext.data.JsonReader({
                root: "data",
                fields: [{
                    name: "id"
                },
                    {
                        name: b
                    }]
            })
        });
        return a
    },
    getStatusStore: function() {
        var a = this;
        var b = new Ext.data.Store({
            autoLoad: true,
            proxy: new Ext.data.HttpProxy({
                url: a.baseUrl + "&task=getStatusStore",
                disableCaching: true
            }),
            reader: new Ext.data.JsonReader({
                root: "data",
                fields: [{
                    name: "id"
                },
                    {
                        name: "value"
                    }]
            })
        });
        return b
    },
    secondWin: function(a, b) {
        var h = this;
        var d = [{
            name: "standard"
        },
            {
                name: "deptId"
            },
            {
                name: "assetsName"
            },
            {
                name: "purchase"
            },
            {
                name: "custody"
            },
            {
                name: "assetsNum"
            },
            {
                name: "residueNum"
            }];
        this.ID_temp = Ext.id();
        this.ID_setReceiver = Ext.id();
        this.cnum = new Ext.form.ComboBox({
            fieldLabel: lang("asetNum"),
            name: "cnum",
            allowBlank: false,
            editable: false,
            triggerAction: "all",
            typeAhead: true,
            hiddenName: "cnum",
            mode: "local",
            valueField: "id",
            displayField: "cnum",
            store: h.getComboStore("cnum"),
            listeners: {
                select: function(l) {
                    var m = l.getValue();
                    c.getForm().load({
                        url: h.baseUrl + "&task=cnumnews",
                        params: {
                            id: m
                        }
                    })
                }
            }
        });
        this.assetsName = new Ext.form.ComboBox({
            fieldLabel: lang("stdname"),
            name: "assetsName",
            allowBlank: false,
            editable: false,
            triggerAction: "all",
            typeAhead: true,
            hiddenName: "assetsName",
            mode: "local",
            valueField: "id",
            displayField: "assetsName",
            store: h.getComboStore("assetsName"),
            listeners: {
                select: function(l) {
                    var m = l.getValue();
                    c.getForm().load({
                        url: h.baseUrl + "&task=cnumnews",
                        params: {
                            id: m
                        }
                    })
                }
            }
        });
        this.borrowNumber = new Ext.form.TextField({
            fieldLabel: lang("secondmentsNumber"),
            name: "borrowNumber",
            allowBlank: false
        });
        var i = new CNOA.selector.form.DepartmentSelectorField({
            fieldLabel: lang("useDept"),
            hiddenName: "acceptDpt",
            name: "acceptdptName"
        });
        var j = new Ext.form.TextField({
            fieldLabel: lang("returnTime"),
            name: "returnDate",
            readOnly: true
        });
        var f = new Ext.ux.form.DateTimeField({
            fieldLabel: lang("lendTime"),
            name: "borrowDate",
            allowBlank: false,
            value: new Date(),
            editable: false
        });
        this.baseField = [{
            xtype: "fieldset",
            title: lang("assetBasicXX"),
            defaults: {
                width: 300,
                border: false
            },
            layout: "table",
            columns: 2,
            items: [{
                layout: "form",
                defaults: {
                    xtype: "textfield",
                    style: {
                        marginBottom: "10px"
                    },
                    width: 150
                },
                items: [this.cnum, {
                    fieldLabel: lang("specifications"),
                    name: "standard",
                    readOnly: true
                },
                    {
                        fieldLabel: lang("department"),
                        name: "deptId",
                        readOnly: true
                    },
                    {
                        fieldLabel: lang("quantity"),
                        name: "assetsNum",
                        readOnly: true
                    }]
            },
                {
                    layout: "form",
                    defaults: {
                        xtype: "textfield",
                        style: {
                            marginBottom: "10px"
                        },
                        width: 150
                    },
                    items: [this.assetsName, {
                        fieldLabel: lang("purchaseDate"),
                        name: "purchase",
                        format: "Y-m-d",
                        readOnly: true
                    },
                        {
                            fieldLabel: lang("storeMan"),
                            name: "custody",
                            readOnly: true
                        },
                        {
                            fieldLabel: lang("canSecondment"),
                            name: "residueNum",
                            readOnly: true
                        }]
                }]
        },
            {
                xtype: "fieldset",
                title: lang("assetsLoanInformation"),
                defaults: {
                    border: false,
                    width: 300
                },
                layout: "table",
                columns: 2,
                items: [{
                    layout: "form",
                    defaults: {
                        xtype: "textfield",
                        style: {
                            marginBottom: "10px"
                        },
                        width: 150
                    },
                    items: [this.borrowNumber, {
                        xtype: "hidden",
                        id: h.ID_receiver,
                        name: "receiverUids"
                    },
                        {
                            fieldLabel: lang("useHuman"),
                            name: "receiver",
                            id: h.ID_setReceiver,
                            listeners: {
                                render: function(l) {
                                    l.to = h.ID_receiver
                                },
                                focus: function(l) {
                                    people_selector("user", l, false, false)
                                }
                            }
                        }]
                },
                    {
                        layout: "form",
                        defaults: {
                            xtype: "textfield",
                            style: {
                                marginBottom: "10px"
                            },
                            width: 150
                        },
                        items: [{
                            fieldLabel: lang("assetStatus"),
                            xtype: "combo",
                            mode: "local",
                            name: "value",
                            valueField: "statusId",
                            triggerAction: "all",
                            hiddenName: "statusId",
                            displayField: "value",
                            editable: false,
                            allowBlank: false,
                            store: new Ext.data.SimpleStore({
                                fields: ["statusId", "value"],
                                data: [["1", lang("standby")], ["2", lang("Use")], ["3", lang("forCirculation")], ["4", lang("maintain")], ["5", lang("lost")]]
                            }),
                            listeners: {
                                select: function(o) {
                                    var l = o.getValue();
                                    if (l != 1) {
                                        var r = new Date();
                                        f.setValue(r);
                                        Ext.getCmp(h.ID_setReceiver).allowBlank = false;
                                        var n = j.setValue("");
                                        n = "";
                                        var m = i.setValue("");
                                        m = "";
                                        var q = Ext.getCmp(h.ID_setReceiver).setValue("");
                                        q = "";
                                        var p = Ext.getCmp(h.ID_receiver).setValue("");
                                        p = ""
                                    }
                                    if (l == 1) {
                                        Ext.getCmp(h.ID_setReceiver).allowBlank = true
                                    }
                                }
                            }
                        },
                            i, {
                                xtype: "hidden",
                                id: this.ID_temp,
                                name: "cid"
                            }]
                    }]
            },
            {
                xtype: "fieldset",
                defaults: {
                    border: false,
                    width: 300
                },
                layout: "table",
                columns: 2,
                items: [{
                    layout: "form",
                    defaults: {
                        xtype: "textfield",
                        width: 150
                    },
                    items: [f]
                },
                    {
                        layout: "form",
                        defaults: {
                            xtype: "textfield",
                            width: 150
                        },
                        items: [j]
                    }]
            },
            {
                xtype: "fieldset",
                items: [{
                    xtype: "textarea",
                    name: "remark",
                    fieldLabel: lang("remark"),
                    width: 450,
                    height: 80
                }]
            }];
        var c = new Ext.form.FormPanel({
            border: false,
            labelAlign: "right",
            labelWidth: 80,
            padding: 5,
            autoScroll: true,
            items: this.baseField
        });
        var k = b == "editSecondment" ? lang("secondChange") : lang("add");
        var e = new Ext.Window({
            width: 600,
            autoHeight: true,
            modal: true,
            title: k + lang("assetsLoanInformation"),
            items: [c],
            buttons: [{
                text: lang("ok"),
                iconCls: "icon-btn-save",
                handler: function() {
                    g(a, b)
                }
            },
                {
                    text: lang("cancel"),
                    iconCls: "icon-dialog-cancel",
                    handler: function() {
                        e.close()
                    }
                }]
        }).show();
        var g = function(l, m) {
            if (c.getForm().isValid()) {
                c.getForm().submit({
                    url: h.baseUrl + "&task=addSecondment",
                    params: {
                        sid: l,
                        task: m
                    },
                    method: "POST",
                    success: function(n, o) {
                        CNOA.msg.notice2(lang("successopt"));
                        h.store.reload();
                        e.close()
                    },
                    failure: function(n, o) {
                        CNOA.msg.alert(o.result.msg)
                    }
                })
            }
        };
        if (b == "editSecondment") {
            c.getForm().load({
                url: h.baseUrl + "&task=" + b,
                params: {
                    sid: a
                },
                method: "POST"
            })
        }
    },
    changeEdit: function(b) {
        var a = this;
        a.secondWin(b, "editSecondment");
        a.cnum.setReadOnly(true);
        a.assetsName.setReadOnly(true);
        a.borrowNumber.setReadOnly(true)
    },
    changeStatus: function(b) {
        var a = this;
        CNOA.msg.cf("确定变更为归还？",
            function(c) {
                if (c == "yes") {
                    Ext.Ajax.request({
                        url: a.baseUrl + "&task=changeStatus",
                        params: {
                            id: b
                        },
                        method: "POST",
                        success: function(d) {
                            var e = Ext.decode(d.responseText);
                            if (e.success == true) {
                                CNOA.msg.notice2(lang("successopt"));
                                a.store.reload()
                            }
                        }
                    })
                }
            })
    },
    delSecondment: function(a) {
        var b = this;
        Ext.Ajax.request({
            url: b.baseUrl + "&task=delSecondment",
            params: {
                ids: a
            },
            method: "POST",
            success: function(c) {
                var d = Ext.decode(c.responseText);
                if (d.success == true) {
                    CNOA.msg.notice2(lang("successopt"));
                    b.store.reload()
                }
            }
        })
    }
};
var CNOA_assets_assets_historicalClass, CNOA_assets_assets_historical;
CNOA_assets_assets_historicalClass = new CNOA.Class.create();
CNOA_assets_assets_historicalClass.prototype = {
    init: function() {
        var a = this;
        this.baseUrl = "assets/historical";
        this.searchParams = {
            searchOperator: "",
            searchOperation: "",
            searchAssetsName: "",
            searchAssetsNum: "",
            searchReturnStatus: "",
            searchNumber: "",
            searchsturnover: "",
            searcheturnover: ""
        };
        ID_search_operator = Ext.id();
        ID_search_name_uid = new Ext.form.Hidden({
            id: ID_search_operator
        });
        searchOperator = new Ext.form.TextField({
            width: 80,
            readOnly: true,
            listeners: {
                render: function(c) {
                    c.to = ID_search_operator
                },
                focus: function(c) {
                    people_selector("user", c, false, false)
                }
            }
        });
        searchAssetsName = new Ext.form.TextField({
            width: 80
        });
        searchAssetsNum = new Ext.form.ComboBox({
            name: "cnum",
            width: 115,
            editable: false,
            triggerAction: "all",
            typeAhead: true,
            hiddenName: "id",
            mode: "local",
            valueField: "cnum",
            displayField: "cnum",
            store: a.getComboStore("cnum")
        });
        searchOperation = new Ext.form.ComboBox({
            store: new Ext.data.SimpleStore({
                fields: ["id", "value"],
                data: [["1", lang("add")], ["2", lang("secondChange")], ["3", lang("del")], ["4", lang("restitution")]]
            }),
            mode: "local",
            width: 60,
            triggerAction: "all",
            hideTrigger: false,
            hiddenField: "id",
            editable: false,
            valueField: "id",
            displayField: "value"
        });
        searchReturnStatus = new Ext.form.ComboBox({
            width: 60,
            typeAhead: true,
            fieldLabel: lang("assetStatus"),
            triggerAction: "all",
            lazyRender: true,
            editable: false,
            mode: "local",
            store: new Ext.data.SimpleStore({
                fields: ["id", "value"],
                data: [["1", lang("standby")], ["2", lang("Use")], ["3", lang("forCirculation")], ["4", lang("maintain")], ["5", lang("lost")]]
            }),
            valueField: "id",
            displayField: "value",
            hiddenName: "value"
        });
        searchNumber = new Ext.form.TextField({
            width: 80
        });
        searchsturnover = new Ext.form.DateField({
            width: 90,
            editable: false,
            format: "Y-m-d"
        });
        searcheturnover = new Ext.form.DateField({
            width: 90,
            editable: false,
            format: "Y-m-d"
        });
        var b = new Ext.Toolbar({
            height: 32,
            style: "padding-top: 5px",
            xtype: "form",
            items: [lang("operator") + ":", searchOperator, "", lang("type") + ":", searchOperation, "", lang("optTime") + ":", searchsturnover, lang("to"), searcheturnover, "", lang("stdname") + ":", searchAssetsName, "", lang("asetNum") + ":", searchAssetsNum, "", lang("secondmentsNumber") + ":", searchNumber, "", lang("assetStatus") + ":", searchReturnStatus, "", {
                xtype: "button",
                text: lang("search"),
                listeners: {
                    click: function(c) {
                        a.searchParams.searchOperation = searchOperation.getValue();
                        a.searchParams.searchOperator = ID_search_name_uid.getValue();
                        a.searchParams.searchNumber = searchNumber.getValue();
                        a.searchParams.searchAssetsName = searchAssetsName.getValue();
                        a.searchParams.searchReturnStatus = searchReturnStatus.getValue();
                        a.searchParams.searchAssetsNum = searchAssetsNum.getValue();
                        a.searchParams.searchsturnover = searchsturnover.getRawValue();
                        a.searchParams.searcheturnover = searcheturnover.getRawValue();
                        a.grid.store.reload({
                            params: a.searchParams
                        })
                    }
                }
            },
                {
                    xtype: "button",
                    text: lang("clear2"),
                    handler: function() {
                        searchOperator.setValue("");
                        searchOperation.setValue("");
                        searchNumber.setValue("");
                        searchAssetsName.setValue("");
                        searchReturnStatus.setValue("");
                        searchAssetsNum.setValue("");
                        ID_search_name_uid.setValue("");
                        searchsturnover.setValue("");
                        searcheturnover.setValue("");
                        a.searchParams.searchOperator = "";
                        a.searchParams.searchOperation = "";
                        a.searchParams.searchNumber = "";
                        a.searchParams.searchAssetsName = "";
                        a.searchParams.searchReturnStatus = "";
                        a.searchParams.searchAssetsNum = "";
                        a.searchParams.searchsturnover = "";
                        a.searchParams.searcheturnover = "";
                        a.grid.store.reload({
                            params: a.searchParams
                        })
                    }
                }]
        });
        this.historical = this.historical();
        this.mainPanel = new Ext.Panel({
            border: false,
            layout: "border",
            tbar: b,
            items: [this.historical]
        })
    },
    historical: function() {
        var e = this;
        var b = [{
            name: "operation"
        },
            {
                name: "turnover"
            },
            {
                name: "operator"
            },
            {
                name: "assetsName"
            },
            {
                name: "borrowNumber"
            },
            {
                name: "borrowDate"
            },
            {
                name: "acceptDpt"
            },
            {
                name: "receiver"
            },
            {
                name: "returnDate"
            },
            {
                name: "status"
            },
            {
                name: "remark"
            }];
        var g = new Ext.grid.CheckboxSelectionModel();
        var c = function(h) {
            if (h == "1") {
                h = "待用"
            } else {
                if (h == "2") {
                    h = "在用"
                } else {
                    if (h == "3") {
                        h = "外借"
                    } else {
                        if (h == "4") {
                            h = "维修"
                        } else {
                            if (h == "5") {
                                h = "已丢失"
                            }
                        }
                    }
                }
            }
            return h
        };
        var a = new Ext.grid.ColumnModel({
            border: false,
            defaults: {
                sortable: true,
                menuDisabled: true,
                width: 130
            },
            columns: [new Ext.grid.RowNumberer(), {
                header: "id",
                dataIndex: "hid",
                hidden: true,
                width: 80
            },
                {
                    header: lang("operator"),
                    dataIndex: "operator",
                    width: 80
                },
                {
                    header: lang("type"),
                    dataIndex: "operation",
                    width: 50
                },
                {
                    header: lang("updateTime"),
                    dataIndex: "turnover"
                },
                {
                    header: lang("stdname"),
                    dataIndex: "assetsName"
                },
                {
                    header: lang("asetNum") + "+" + lang("secondmentsNumber"),
                    dataIndex: "borrowNumber",
                    width: 170
                },
                {
                    header: lang("lendTime"),
                    dataIndex: "borrowDate"
                },
                {
                    header: lang("useDept"),
                    dataIndex: "acceptDpt"
                },
                {
                    header: lang("useHuman"),
                    dataIndex: "receiver"
                },
                {
                    header: lang("returnTime"),
                    dataIndex: "returnDate"
                },
                {
                    header: lang("assetStatus"),
                    dataIndex: "status",
                    renderer: c
                },
                {
                    header: lang("remark"),
                    dataIndex: "remark"
                }]
        });
        var d = new Ext.data.Store({
            autoLoad: true,
            baseParams: e.searchParams,
            proxy: new Ext.data.HttpProxy({
                url: e.baseUrl + "/getHistoricalList",
                disableCaching: true
            }),
            reader: new Ext.data.JsonReader({
                totalProperty: "total",
                root: "data",
                fields: b
            }),
            listeners: {
                exception: function(m, l, n, k, i) {
                    var j = i.responseText;
                    if (j != "") {
                        var h = Ext.decode(j);
                        if (h.failure) {
                            CNOA.msg.alert(h.msg)
                        }
                    }
                }
            }
        });
        var f = new Ext.PagingToolbar({
            plugins: [new Ext.grid.plugins.ComboPageSize()],
            style: "border-left-width:1px;",
            displayInfo: true,
            emptyMsg: lang("pagingToolbarEmptyMsg"),
            displayMsg: lang("showDataTotal2"),
            store: d,
            pageSize: 15
        });
        this.grid = new Ext.grid.GridPanel({
            border: false,
            store: d,
            region: "center",
            cm: a,
            bbar: f,
            tbar: [{
                text: lang("refresh"),
                iconCls: "icon-system-refresh",
                handler: function() {
                    d.reload()
                }
            }]
        });
        return e.grid
    },
    getComboStore: function(b) {
        var c = this;
        var a = new Ext.data.Store({
            autoLoad: true,
            proxy: new Ext.data.HttpProxy({
                url: c.baseUrl + "/cnumstore",
                disableCaching: true
            }),
            reader: new Ext.data.JsonReader({
                root: "data",
                fields: [{
                    name: "id"
                },
                    {
                        name: b
                    }]
            })
        });
        return a
    }
};
var CNOA_assets_base_numbersetClass, CNOA_assets_base_numberset;
CNOA_assets_base_numbersetClass = new CNOA.Class.create();
CNOA_assets_base_numbersetClass.prototype = {
    init: function() {
        var h = this;
        this.baseUrl = "assets/numberSet";
        var e = function() {
            var u = a.getValue();
            var p = i.getValue();
            var q = b.getValue();
            var n = g.getValue();
            var s = n.length;
            var k = q.length;
            var o = q.substring(0, k - s);
            var m = o + n;
            var r = Ext.getCmp(j).checked;
            var l = Ext.getCmp(d).checked;
            if (r == true && l == false) {
                var t = u + m
            } else {
                if (r == false && l == true) {
                    var t = p + m
                } else {
                    if (r == false && l == false) {
                        var t = m
                    } else {
                        var t = u + p + m
                    }
                }
            }
            f.setValue(t)
        };
        var j = Ext.id();
        var d = Ext.id();
        var a = new Ext.form.TextField({
            width: 120,
            enableKeyEvents: true,
            maxLength: 20,
            regex: /^[A-Za-z]+$/,
            name: "zimu",
            readOnly: true,
            regexText: lang("onlyEnterEnglish20"),
            listeners: {
                keyup: function(k, l) {
                    e()
                },
                change: function(k, l) {
                    e()
                }
            }
        });
        var i = new Ext.form.TextField({
            width: 120,
            enableKeyEvents: true,
            maxLength: 6,
            name: "fuhao",
            readOnly: true,
            listeners: {
                keyup: function(k, l) {
                    e()
                },
                change: function(k, l) {
                    e()
                }
            }
        });
        var b = new Ext.form.TextField({
            width: 120,
            maxLength: 20,
            name: "num",
            readOnly: true,
            regex: /^0+$/,
            regexText: lang("waterRoughlyOnlyTo0"),
            enableKeyEvents: true,
            listeners: {
                keyup: function(k, l) {
                    e()
                },
                change: function(k, l) {
                    e()
                }
            }
        });
        var g = new Ext.form.TextField({
            fieldLabel: lang("currentNumber"),
            width: 200,
            name: "nowNum",
            readOnly: true,
            enableKeyEvents: true,
            listeners: {
                keyup: function(k, l) {
                    e()
                },
                change: function(k, l) {
                    e()
                }
            }
        });
        var f = new Ext.form.TextField({
            fieldLabel: lang("numberSample"),
            readOnly: true,
            width: 200,
            name: "numShow"
        });
        var c = Ext.id();
        this.mainPanel = new Ext.form.FormPanel({
            border: false,
            padding: 10,
            items: [{
                xtype: "fieldset",
                width: 800,
                height: 300,
                items: [{
                    width: 300,
                    hideLabel: true,
                    xtype: "radiogroup",
                    items: [{
                        boxLabel: lang("accordRuleNumber"),
                        name: "status",
                        inputValue: 1
                    },
                        {
                            boxLabel: lang("manualNumber"),
                            name: "status",
                            inputValue: 0,
                            checked: true
                        }],
                    listeners: {
                        change: function(l, k) {
                            if (k.inputValue == 0) {
                                a.setReadOnly(true);
                                i.setReadOnly(true);
                                b.setReadOnly(true);
                                f.setReadOnly(true);
                                g.setReadOnly(true);
                                Ext.getCmp(j).setDisabled(true);
                                Ext.getCmp(d).setDisabled(true)
                            } else {
                                a.setReadOnly(false);
                                i.setReadOnly(false);
                                b.setReadOnly(false);
                                f.setReadOnly(false);
                                g.setReadOnly(false);
                                Ext.getCmp(j).setDisabled(false);
                                Ext.getCmp(d).setDisabled(false);
                                if (Ext.getCmp(j).checked == false) {
                                    Ext.getCmp(j).setValue(false)
                                }
                                if (Ext.getCmp(d).checked == false) {
                                    Ext.getCmp(d).setValue(false)
                                }
                            }
                        }
                    }
                },
                    {
                        xtype: "hidden",
                        listeners: {
                            afterrender: function() {
                                h.formLoad()
                            }
                        }
                    },
                    {
                        xtype: "fieldset",
                        title: lang("numRules"),
                        height: 200,
                        id: c,
                        items: [{
                            border: false,
                            layout: "table",
                            defaults: {
                                border: false
                            },
                            items: [a, {
                                html: "+"
                            },
                                i, {
                                    html: "+"
                                },
                                b]
                        },
                            {
                                border: false,
                                layout: "table",
                                height: 25,
                                items: [{
                                    xtype: "checkbox",
                                    boxLabel: lang("English"),
                                    checked: true,
                                    name: "zimuCheck",
                                    disabled: true,
                                    id: j,
                                    width: 128,
                                    listeners: {
                                        check: function(k, u) {
                                            if (u) {
                                                e()
                                            } else {
                                                var q = i.getValue();
                                                var r = b.getValue();
                                                var o = g.getValue();
                                                var t = o.length;
                                                var l = r.length;
                                                var p = r.substring(0, l - t);
                                                var n = p + o;
                                                var s = Ext.getCmp(j).checked;
                                                var m = Ext.getCmp(d).checked;
                                                if (m == true) {
                                                    var v = q + n
                                                }
                                                if (m == false) {
                                                    var v = n
                                                }
                                                f.setValue(v)
                                            }
                                        }
                                    }
                                },
                                    {
                                        html: ""
                                    },
                                    {
                                        xtype: "checkbox",
                                        boxLabel: lang("symbol"),
                                        id: d,
                                        name: "fuhaoCheck",
                                        disabled: true,
                                        width: 128,
                                        checked: true,
                                        listeners: {
                                            check: function(k, t) {
                                                if (t) {
                                                    e()
                                                } else {
                                                    var v = a.getValue();
                                                    var q = b.getValue();
                                                    var o = g.getValue();
                                                    var s = o.length;
                                                    var l = q.length;
                                                    var p = q.substring(0, l - s);
                                                    var n = p + o;
                                                    var r = Ext.getCmp(j).checked;
                                                    var m = Ext.getCmp(d).checked;
                                                    if (r == true) {
                                                        var u = v + n
                                                    }
                                                    if (r == false) {
                                                        var u = n
                                                    }
                                                    f.setValue(u)
                                                }
                                            }
                                        }
                                    },
                                    {
                                        html: ""
                                    },
                                    {
                                        border: false,
                                        html: lang("waterNumber"),
                                        style: "padding-top: 3px;"
                                    }]
                            },
                            g, f]
                    }]
            }],
            tbar: new Ext.Toolbar({
                items: [{
                    xtype: "button",
                    text: lang("refresh"),
                    iconCls: "icon-system-refresh",
                    handler: function() {
                        h.formLoad()
                    }
                },
                    {
                        text: lang("save"),
                        iconCls: "icon-win-save",
                        cls: "btn-blue4",
                        handler: function(k) {
                            h.edit()
                        }
                    }]
            })
        })
    },
    edit: function() {
        var b = this,
            a = b.mainPanel.getForm();
        if (a.isValid()) {
            a.submit({
                url: b.baseUrl + "/saveEdit",
                method: "POST",
                success: function(c, d) {
                    CNOA.msg.notice2(lang("successopt"))
                },
                failure: function(c, e) {
                    var d = e.response.responseText;
                    d = JSON.parse(d);
                    CNOA.msg.alert(d.msg)
                }
            })
        }
    },
    formLoad: function() {
        var b = this,
            a = b.mainPanel.getForm();
        a.load({
            url: b.baseUrl + "/getSortList",
            method: "POST"
        })
    }
};
var CNOA_assets_base_sortSetting, CNOA_assets_base_sortSettingClass;
CNOA_assets_base_sortSettingClass = CNOA.Class.create();
CNOA_assets_base_sortSettingClass.prototype = {
    init: function() {
        var a = this;
        this.baseUrl = "assets/sortSet";
        this.treePanel = this.treePanel();
        this.formPanel = this.formPanel();
        this.mainPanel = new Ext.Panel({
            title: lang("sortSet"),
            layout: "border",
            border: false,
            items: [this.treePanel, this.formPanel]
        })
    },
    treePanel: function() {
        var b = this;
        this.ID_btn_collapseExpand = Ext.id();
        this.treeRoot = new Ext.tree.AsyncTreeNode({
            expanded: true
        });
        this.treeLoader = new Ext.tree.TreeLoader({
            dataUrl: b.baseUrl + "/getSortList?type=tree",
            preloadChildren: true,
            clearOnLoad: false,
            listeners: {
                load: function(c) {
                    b.treeRoot.expand(true)
                }
            }
        });
        var a = new Ext.tree.TreePanel({
            region: "west",
            split: true,
            width: 235,
            rootVisible: false,
            lines: true,
            animate: false,
            autoScroll: true,
            bodyStyle: "border-right-width:1px;",
            loader: this.treeLoader,
            root: this.treeRoot,
            tbar: new Ext.Toolbar({
                style: "border-right-width:1px;",
                items: [{
                    text: lang("expand"),
                    id: this.ID_btn_collapseExpand,
                    tooltip: lang("expandMenuTip"),
                    enableToggle: true,
                    toggleHandler: function(c, d) {
                        if (d) {
                            c.setText(lang("expand"));
                            c.setTooltip(lang("expandMenuTip"));
                            b.treeRoot.collapse(true);
                            b.treeRoot.firstChild.expand()
                        } else {
                            c.setText(lang("collapse"));
                            c.setTooltip(lang("collapseMenuTip"));
                            b.treeRoot.expand(true)
                        }
                    }
                },
                    "->", {
                        text: lang("add"),
                        iconCls: "icon-utils-s-add",
                        handler: function() {
                            b.addSort()
                        }
                    },
                    {
                        text: lang("del"),
                        iconCls: "icon-utils-s-delete",
                        handler: function() {
                            b.deleteSort()
                        }
                    },
                    {
                        handler: function(c, d) {
                            b.treeRoot.reload();
                            if (b.sort_id) {
                                b.sort_id = ""
                            }
                            Ext.getCmp(b.ID_text_sort).setValue("");
                            Ext.getCmp(b.ID_text_name).setValue("");
                            Ext.getCmp(b.ID_text_fid).setValue("");
                            Ext.getCmp(b.ID_text_order).setValue("");
                            Ext.getCmp(b.ID_text_fname).setValue("");
                            Ext.getCmp(b.ID_text_about).setValue("")
                        },
                        iconCls: "icon-system-refresh",
                        tooltip: lang("refresh"),
                        text: lang("refresh")
                    }]
            }),
            listeners: {
                click: function(c) {
                    Ext.getCmp(b.ID_text_sort).setValue("");
                    Ext.getCmp(b.ID_text_name).setValue("");
                    Ext.getCmp(b.ID_text_fid).setValue("");
                    Ext.getCmp(b.ID_text_order).setValue("");
                    Ext.getCmp(b.ID_text_about).setValue("");
                    Ext.getCmp(b.ID_text_fname).setValue("");
                    b.sort_id = c.attributes.id;
                    b.sort = c.attributes.fid;
                    b.name = c.attributes.text;
                    b.order = c.attributes.order;
                    b.about = c.attributes.about;
                    Ext.getCmp(b.ID_text_sort).setValue(b.sort_id);
                    Ext.getCmp(b.ID_text_name).setValue(b.name);
                    Ext.getCmp(b.ID_text_fid).setValue(b.sort);
                    Ext.getCmp(b.ID_text_order).setValue(b.order);
                    Ext.getCmp(b.ID_text_about).setValue(b.about);
                    Ext.getCmp(b.ID_text_fname).setDisabled(true);
                    b.loadFname()
                }
            }
        });
        return a
    },
    loadFname: function() {
        var a = this;
        this.formPanel.getForm().load({
            url: a.baseUrl + "/loadFname",
            params: {
                id: a.sort
            },
            method: "POST"
        })
    },
    formPanel: function() {
        var d = this;
        this.ID_text_name = Ext.id();
        this.ID_text_fid = Ext.id();
        this.ID_text_fname = Ext.id();
        this.ID_text_order = Ext.id();
        this.ID_text_about = Ext.id();
        this.ID_btn_collapseExpand2 = Ext.id();
        this.ID_text_sort = Ext.id();
        this.root = new Ext.tree.AsyncTreeNode({
            expanded: true
        });
        var b = new Ext.tree.TreeLoader({
            dataUrl: d.baseUrl + "/getSortList?type=combo",
            preloadChildren: true,
            clearOnLoad: false,
            listeners: {
                load: function(e) {
                    d.root.expand(true)
                }
            }
        });
        var a = new Ext.tree.TreePanel({
            border: false,
            animate: false,
            autoScroll: true,
            containerScroll: true,
            rootVisible: false,
            lines: true,
            checkModel: "single",
            loader: b,
            root: this.root,
            bodyStyle: "background-color: #FFF;",
            tbar: new Ext.Toolbar({
                style: "border-right-width:1px;",
                items: [{
                    text: lang("expand"),
                    iconCls: "icon-expand-all",
                    id: this.ID_btn_collapseExpand2,
                    tooltip: lang("expandMenuTip"),
                    enableToggle: true,
                    toggleHandler: function(e, f) {
                        if (f) {
                            e.setIconClass("icon-expand-all");
                            e.setText(lang("expand"));
                            e.setTooltip(lang("expandMenuTip"));
                            d.root.collapse(true);
                            d.root.firstChild.expand()
                        } else {
                            e.setIconClass("icon-collapse-all");
                            e.setText(lang("collapse"));
                            e.setTooltip(lang("collapseMenuTip"));
                            d.root.expand(true)
                        }
                    }
                }]
            }),
            listeners: {
                checkchange: function(h, g) {
                    if (g) {
                        var e = a.getChecked();
                        if (e && e.length) {
                            for (var f = 0; f < e.length; f++) {
                                if (e[f].id != h.id) {
                                    e[f].getUI().toggleCheck(false);
                                    e[f].attributes.checked = false
                                }
                            }
                        }
                        d.nodeId = h.id;
                        d.nodeText = h.text
                    } else {
                        d.nodeId = "";
                        d.nodeText = ""
                    }
                }
            }
        });
        this.win = new Ext.Window({
            border: false,
            width: 400,
            height: 500,
            closeAction: "hide",
            layout: "fit",
            modal: true,
            buttonAlign: "right",
            buttons: [{
                text: lang("ok"),
                handler: function() {
                    if (d.nodeId) {
                        Ext.getCmp(d.ID_text_fid).setValue(d.nodeId);
                        Ext.getCmp(d.ID_text_fname).setValue(d.nodeText);
                        d.root.reload();
                        d.win.hide()
                    }
                }
            },
                {
                    text: lang("cancel"),
                    handler: function() {
                        d.root.reload();
                        d.win.hide()
                    }
                }],
            items: [a]
        });
        var c = new Ext.FormPanel({
            region: "center",
            title: lang("addOrEdit"),
            padding: 10,
            buttonAlign: "left",
            items: [{
                xtype: "fieldset",
                title: lang("addOrEdit"),
                autoWidth: true,
                items: [{
                    xtype: "hidden",
                    id: this.ID_text_sort
                },
                    {
                        xtype: "textfield",
                        fieldLabel: lang("sortName"),
                        name: "name",
                        id: this.ID_text_name,
                        allowBlank: false
                    },
                    {
                        xtype: "hidden",
                        name: "fid",
                        id: this.ID_text_fid
                    },
                    {
                        xtype: "textfield",
                        fieldLabel: lang("sort2"),
                        name: "fname",
                        editable: false,
                        allowBlank: false,
                        id: this.ID_text_fname,
                        listeners: {
                            focus: function(f, e) {
                                d.win.show()
                            }
                        }
                    },
                    {
                        xtype: "numberfield",
                        fieldLabel: lang("order"),
                        name: "order",
                        id: this.ID_text_order
                    },
                    {
                        xtype: "textarea",
                        fieldLabel: lang("description"),
                        name: "about",
                        id: this.ID_text_about,
                        width: 300
                    }]
            }],
            buttons: [{
                text: lang("save"),
                iconCls: "icon-order-s-accept",
                handler: function() {
                    d.submitForm()
                }
            }],
            listeners: {
                beforerender: function() {
                    Ext.Ajax.request({
                        url: d.baseUrl + "&task=loadForm",
                        success: function(e) {
                            var f = Ext.decode(e.responseText);
                            if (f == 0) {
                                Ext.getCmp(d.ID_text_fname).setDisabled(true);
                                Ext.getCmp(d.ID_text_fname).allowBlank = true;
                                Ext.getCmp(d.ID_text_fname).hide()
                            }
                        }
                    })
                }
            }
        });
        return c
    },
    addSort: function() {
        var a = this;
        Ext.getCmp(a.ID_text_fname).setDisabled(false);
        Ext.getCmp(a.ID_text_sort).setValue("");
        Ext.getCmp(a.ID_text_name).setValue("");
        Ext.getCmp(a.ID_text_fid).setValue("");
        Ext.getCmp(a.ID_text_order).setValue("");
        Ext.getCmp(a.ID_text_fname).setValue("");
        Ext.getCmp(a.ID_text_about).setValue("");
        if (a.sort_id && a.name) {
            Ext.getCmp(a.ID_text_fid).setValue(a.sort_id);
            Ext.getCmp(a.ID_text_fname).setValue(a.name)
        }
        a.sort_id = ""
    },
    submitForm: function() {
        if (!this.formPanel.getForm().isValid()) {
            return
        }
        var a = this;
        this.formPanel.getForm().submit({
            waitMsg: lang("waiting"),
            url: this.baseUrl + "/saveOrUpdate",
            method: "POST",
            params: {
                id: a.sort_id
            },
            success: function(b, c) {
                Ext.getCmp(a.ID_text_fname).show();
                a.treeRoot.reload();
                a.root.load();
                CNOA.msg.notice2(c.result.msg)
            },
            failure: function(b, c) {
                CNOA.msg.alert(c.result.msg)
            }
        })
    },
    deleteSort: function() {
        var a = this;
        if (!a.sort_id) {
            CNOA.msg.alert(lang("selectItemToDel"));
            return false
        }
        CNOA.msg.cf(lang("confirmToDelete"),
            function(b) {
                if (b == "yes") {
                    Ext.Ajax.request({
                        url: a.baseUrl + "/deleteSort",
                        method: "POST",
                        params: {
                            id: a.sort_id
                        },
                        success: function(d) {
                            var c = Ext.decode(d.responseText);
                            if (c.success === true) {
                                a.treeRoot.reload();
                                a.root.reload();
                                a.formPanel.getForm().reset();
                                CNOA.msg.notice2(c.msg)
                            } else {
                                CNOA.msg.alert(c.msg)
                            }
                        }
                    })
                }
            })
    }
};
var CNOA_assets_base_customClass, CNOA_assets_base_custom;
CNOA_assets_base_customClass = new CNOA.Class.create();
CNOA_assets_base_customClass.prototype = {
    init: function() {
        var a = this;
        this.baseUrl = "index.php?app=assets&func=base&action=custom";
        this.custom = this.custom();
        this.mainPanel = new Ext.Panel({
            border: false,
            layout: "fit",
            items: [this.custom]
        })
    },
    custom: function() {
        var f = this;
        var b = [{
            name: "fieldid"
        },
            {
                name: "fieldname"
            },
            {
                name: "fieldtype"
            },
            {
                name: "items"
            },
            {
                name: "view"
            },
            {
                name: "add"
            },
            {
                name: "show"
            },
            {
                name: "must"
            },
            {
                name: "order"
            }];
        var c = Ext.id();
        var d = function(i) {
            if (i == " ") {
                return ""
            }
            checked = i == 1 ? "checked": "";
            return '<input type="checkbox" ' + checked + "  />"
        };
        var h = new Ext.grid.CheckboxSelectionModel({
            singleSelect: true
        });
        var a = new Ext.grid.ColumnModel({
            border: false,
            defaults: {
                sortable: true,
                menuDisabled: true,
                width: 130
            },
            columns: [new Ext.grid.RowNumberer(), h, {
                header: "fieldid",
                dataIndex: "fieldid",
                hidden: true,
                width: 80
            },
                {
                    header: lang("fieldName"),
                    dataIndex: "fieldname"
                },
                {
                    header: lang("type"),
                    dataIndex: "fieldtype"
                },
                {
                    header: lang("optionSet"),
                    dataIndex: "items",
                    editor: new Ext.form.TextArea({
                        allowBlank: false
                    }),
                    renderer: function(j, i) {
                        i.attr = 'style="white-space:normal;"';
                        return j
                    }
                },
                {
                    header: lang("areListDisplay"),
                    dataIndex: "show",
                    renderer: d
                },
                {
                    header: lang("areEditDisplay"),
                    dataIndex: "add",
                    renderer: d
                },
                {
                    header: lang("ifRequired"),
                    dataIndex: "must",
                    renderer: d
                },
                {
                    header: lang("order"),
                    dataIndex: "order",
                    editor: true
                }]
        });
        this.store = new Ext.data.Store({
            autoLoad: true,
            proxy: new Ext.data.HttpProxy({
                url: f.baseUrl + "&task=getCustom",
                disableCaching: true
            }),
            reader: new Ext.data.JsonReader({
                totalProperty: "total",
                root: "data",
                fields: b
            }),
            listeners: {
                exception: function(n, m, o, l, j) {
                    var k = j.responseText;
                    if (k != "") {
                        var i = Ext.decode(k);
                        if (i.failure) {
                            CNOA.msg.alert(i.msg)
                        }
                    }
                }
            }
        });
        var g = new Ext.PagingToolbar({
            style: "border-left-width:1px;",
            displayInfo: true,
            store: f.store,
            pageSize: 15
        });
        var e = new Ext.grid.EditorGridPanel({
            border: false,
            region: "center",
            store: f.store,
            bodyStyle: "border-left-width:1px;",
            cm: a,
            sm: h,
            bbar: g,
            tbar: new Ext.Toolbar({
                items: [{
                    xtype: "button",
                    text: lang("refresh"),
                    iconCls: "icon-system-refresh",
                    handler: function() {
                        f.store.reload()
                    }
                },
                    {
                        text: lang("add"),
                        iconCls: "icon-utils-s-add",
                        handler: function() {
                            f.addCustom()
                        }
                    },
                    {
                        text: lang("del"),
                        iconCls: "icon-utils-s-delete",
                        handler: function(j) {
                            var k = e.getSelectionModel().getSelected().data;
                            var i = k.fieldid;
                            f.delCustom(i)
                        }
                    }]
            }),
            listeners: {
                afteredit: function(l) {
                    var j = l.field,
                        i = l.record.get("fieldid"),
                        k = l.value;
                    if (j != "order") {
                        l.record.set("");
                        f.editCustom(i, j, k)
                    }
                    if (j == "order") {
                        Ext.Ajax.request({
                            url: f.baseUrl + "&task=order",
                            method: "POST",
                            params: {
                                fieldid: i,
                                value: k
                            },
                            success: function(m) {
                                var n = Ext.decode(m.responseText);
                                if (n.success) {
                                    CNOA.msg.notice2(n.msg)
                                } else {
                                    CNOA.msg.alert(n.msg)
                                }
                                f.store.reload()
                            }
                        })
                    }
                },
                cellclick: function(k, n, l) {
                    var m = k.getColumnModel().getDataIndex(l);
                    if (m == "add" || "show" || "must") {
                        var j = k.getStore().getAt(n),
                            i = j.get("fieldid");
                        oldValue = j.get(m);
                        value = $(k.getView().getCell(n, l)).find("input").attr("checked");
                        if (value == undefined || value == oldValue) {
                            return
                        }
                        value = value ? 1 : 0;
                        f.editCustom(i, m, value)
                    }
                },
                beforeedit: function(j) {
                    var i = j.record.json.type;
                    if (j.field == "items" && i != 6 && i != 8) {
                        return false
                    }
                }
            }
        });
        return e
    },
    editCustom: function(a, d, c) {
        var b = this;
        Ext.Ajax.request({
            url: b.baseUrl + "&task=editCustom",
            method: "POST",
            params: {
                fieldid: a,
                fieldname: d,
                value: c
            },
            success: function(e) {
                var f = Ext.decode(e.responseText);
                if (f.success) {
                    CNOA.msg.notice2(f.msg)
                } else {
                    CNOA.msg.alert(f.msg)
                }
                b.store.reload()
            }
        })
    },
    addCustom: function() {
        var b = this;
        var e = Ext.id();
        var a = new Ext.FormPanel({
            width: 340,
            border: false,
            height: 260,
            items: [{
                xtype: "fieldset",
                width: 300,
                height: 250,
                border: false,
                labelWidth: 120,
                labelAlign: "right",
                defaults: {
                    width: 155
                },
                items: [{
                    xtype: "textfield",
                    allowBlank: false,
                    fieldLabel: lang("fieldName"),
                    name: "fieldname",
                    id: e
                },
                    {
                        xtype: "combo",
                        fieldLabel: lang("fieldType"),
                        hiddenName: "fieldtype",
                        typeAhead: true,
                        triggerAction: "all",
                        editable: false,
                        mode: "local",
                        allowBlank: false,
                        valueField: "fieldtype",
                        displayField: "fieldtypeName",
                        store: new Ext.data.Store({
                            autoLoad: true,
                            proxy: new Ext.data.HttpProxy({
                                url: b.baseUrl + "&task=getFieldType"
                            }),
                            reader: new Ext.data.JsonReader({
                                totalProperty: "total",
                                root: "data",
                                fields: [{
                                    name: "fieldtype"
                                },
                                    {
                                        name: "fieldtypeName"
                                    }]
                            })
                        }),
                        listeners: {
                            select: function(i, f, h) {
                                var g = i.nextSibling(),
                                    j = parseInt(f.get("fieldtype"));
                                if (j == 6 || j == 8) {
                                    g.show();
                                    g.setDisabled(false)
                                } else {
                                    g.hide();
                                    g.setDisabled(true)
                                }
                            }
                        }
                    },
                    {
                        xtype: "compositefield",
                        fieldLabel: lang("optionSet"),
                        hidden: true,
                        items: [{
                            xtype: "textarea",
                            name: "items",
                            allowBlank: false,
                            width: 120
                        },
                            {
                                xtype: "label",
                                html: '<span style="color:red;">' + lang("optionSeparated") + "</span>"
                            }]
                    },
                    {
                        xtype: "textfield",
                        emptyText: 100,
                        fieldLabel: lang("order"),
                        name: "order"
                    },
                    {
                        fieldLabel: lang("areListDisplay"),
                        xtype: "radiogroup",
                        items: [{
                            boxLabel: lang("yes"),
                            name: "show",
                            inputValue: 1,
                            checked: true
                        },
                            {
                                boxLabel: lang("no"),
                                name: "show",
                                inputValue: 0
                            }]
                    },
                    {
                        fieldLabel: lang("areEditDisplay"),
                        xtype: "radiogroup",
                        items: [{
                            boxLabel: lang("yes"),
                            name: "add",
                            inputValue: 1,
                            checked: true
                        },
                            {
                                boxLabel: lang("no"),
                                name: "add",
                                inputValue: 0
                            }]
                    },
                    {
                        fieldLabel: lang("ifRequired"),
                        xtype: "radiogroup",
                        items: [{
                            boxLabel: lang("yes"),
                            name: "must",
                            inputValue: 1,
                            checked: true
                        },
                            {
                                boxLabel: lang("no"),
                                name: "must",
                                inputValue: 0
                            }]
                    }]
            }]
        });
        var c = function() {
            if (a.getForm().isValid()) {
                a.getForm().submit({
                    url: b.baseUrl + "&task=addCustom",
                    method: "POST",
                    success: function(g, i) {
                        var h = i.response.responseText,
                            f = JSON.parse(h);
                        CNOA.msg.notice2(f.msg);
                        b.store.load();
                        d.close()
                    },
                    failure: function(f, g) {
                        CNOA.msg.alert(g.result.msg)
                    }
                })
            }
        };
        var d = new Ext.Window({
            title: lang("addCustomField"),
            width: 350,
            height: 320,
            modal: true,
            items: [a],
            buttons: [{
                text: lang("submit"),
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
        });
        d.show()
    },
    delCustom: function(a) {
        var b = this;
        CNOA.msg.cf(lang("confirmToDelete"),
            function(c) {
                if (c == "yes") {
                    Ext.Ajax.request({
                        url: b.baseUrl + "&task=delCustom",
                        params: {
                            fieldid: a
                        },
                        success: function(d) {
                            b.store.reload()
                        }
                    })
                }
            })
    }
};
var CNOA_assets_base_fixClass, CNOA_assets_base_fix;
CNOA_assets_base_fixClass = new CNOA.Class.create();
CNOA_assets_base_fixClass.prototype = {
    init: function() {
        var a = this;
        this.baseUrl = "index.php?app=assets&func=base&action=fix";
        this.fix = this.fix();
        this.mainPanel = new Ext.Panel({
            border: false,
            layout: "fit",
            items: [this.fix]
        })
    },
    fix: function() {
        var f = this;
        var b = [{
            name: "fixid"
        },
            {
                name: "fixname"
            },
            {
                name: "add"
            },
            {
                name: "show"
            },
            {
                name: "must"
            },
            {
                name: "order"
            }];
        var c = Ext.id();
        var d = function(h) {
            if (h == " ") {
                return ""
            }
            checked = h == 1 ? "checked": "";
            return '<input type="checkbox" ' + checked + "  />"
        };
        var a = new Ext.grid.ColumnModel({
            border: false,
            defaults: {
                sortable: true,
                menuDisabled: true,
                width: 130
            },
            columns: [new Ext.grid.RowNumberer(), {
                header: "fixid",
                dataIndex: "fixid",
                hidden: true,
                width: 80
            },
                {
                    header: lang("fieldName"),
                    dataIndex: "fixname"
                },
                {
                    header: lang("areListDisplay"),
                    dataIndex: "show",
                    renderer: d
                },
                {
                    header: lang("areEditDisplay"),
                    dataIndex: "add",
                    renderer: d
                },
                {
                    header: lang("ifRequired"),
                    dataIndex: "must",
                    renderer: d
                },
                {
                    header: lang("order"),
                    dataIndex: "order",
                    editor: true
                }]
        });
        this.store = new Ext.data.Store({
            autoLoad: true,
            proxy: new Ext.data.HttpProxy({
                url: f.baseUrl + "&task=getFix",
                disableCaching: true
            }),
            reader: new Ext.data.JsonReader({
                totalProperty: "total",
                root: "data",
                fields: b
            }),
            listeners: {
                exception: function(m, l, n, k, i) {
                    var j = i.responseText;
                    if (j != "") {
                        var h = Ext.decode(j);
                        if (h.failure) {
                            CNOA.msg.alert(h.msg)
                        }
                    }
                }
            }
        });
        var g = new Ext.PagingToolbar({
            displayInfo: true,
            store: f.store,
            pageSize: 20
        });
        var e = new Ext.grid.EditorGridPanel({
            border: false,
            region: "center",
            store: f.store,
            cm: a,
            bbar: g,
            tbar: new Ext.Toolbar({
                items: [{
                    xtype: "button",
                    text: lang("refresh"),
                    iconCls: "icon-system-refresh",
                    handler: function() {
                        f.store.reload()
                    }
                }]
            }),
            listeners: {
                cellclick: function(j, m, k) {
                    var l = j.getColumnModel().getDataIndex(k);
                    if (l == "add" || "show" || "must") {
                        var i = j.getStore().getAt(m),
                            h = i.get("fixid");
                        oldValue = i.get(l);
                        value = $(j.getView().getCell(m, k)).find("input").attr("checked");
                        if (value == undefined || value == oldValue) {
                            return
                        }
                        value = value ? 1 : 0;
                        f.editFix(h, l, value)
                    }
                },
                afteredit: function(i) {
                    var j = i.record.get("fixid"),
                        h = i.record.get("order");
                    Ext.Ajax.request({
                        url: f.baseUrl + "&task=order",
                        method: "POST",
                        params: {
                            fixid: j,
                            value: h
                        },
                        success: function(k) {
                            var l = Ext.decode(k.responseText);
                            if (l.success) {
                                CNOA.msg.notice2(l.msg)
                            } else {
                                CNOA.msg.alert(l.msg)
                            }
                            f.store.reload()
                        }
                    })
                }
            }
        });
        return e
    },
    editFix: function(a, d, c) {
        var b = this;
        Ext.Ajax.request({
            url: b.baseUrl + "&task=editFix",
            method: "POST",
            params: {
                fixid: a,
                fixname: d,
                value: c
            },
            success: function(e) {
                var f = Ext.decode(e.responseText);
                if (f.success) {
                    CNOA.msg.notice2(f.msg)
                } else {
                    CNOA.msg.alert(f.msg)
                }
                b.store.reload()
            }
        })
    }
};
var CNOA_assets_base_baseSet, CNOA_assets_base_baseSetClass;
CNOA_assets_base_baseSetClass = CNOA.Class.create();
CNOA_assets_base_baseSetClass.prototype = {
    init: function() {
        var a = this;
        this.baseUrl = "assets/baseSet";
        this.TYPE_UNIT = 0;
        this.TYPE_SOURCE = 2;
        this.TYPE_WAY = 3;
        this.TYPE_PLACE = 4;
        this.TYPE_MANUFACTUER = 6;
        this.TYPE_SUPPLIER = 7;
        this.TYPE_RESIDUALS = 9;
        this.unitmeasure = this.getDropdownPanel(this.TYPE_UNIT, lang("uniteMeasure"));
        this.assetssource = this.getDropdownPanel(this.TYPE_SOURCE, lang("assetsSources"));
        this.reduceway = this.getDropdownPanel(this.TYPE_WAY, lang("reduceway"));
        this.storageplace = this.getDropdownPanel(this.TYPE_PLACE, lang("storagePlace"));
        this.manufactuer = this.getDropdownPanel(this.TYPE_MANUFACTUER, lang("manufacturer"));
        this.supplier = this.getDropdownPanel(this.TYPE_SUPPLIER, lang("supplier"));
        this.residuals = this.getDropdownPanel(this.TYPE_RESIDUALS, lang("residualRate"));
        this.mainPanel = new Ext.ux.VerticalTabPanel({
            region: "center",
            border: false,
            tabPosition: "left",
            tabWidth: 150,
            activeItem: 0,
            deferredRender: true,
            layoutOnTabChange: true,
            items: [this.unitmeasure, this.assetssource, this.reduceway, this.storageplace, this.manufactuer, this.supplier, this.residuals]
        })
    },
    getDropdownPanel: function(h, i) {
        var g = this;
        var e = [{
            name: "id"
        },
            {
                name: "value"
            }];
        var k = new Ext.data.Store({
            autoLoad: true,
            proxy: new Ext.data.HttpProxy({
                url: this.baseUrl + "/getDropdown?type=" + h
            }),
            reader: new Ext.data.JsonReader({
                totalProperty: "total",
                root: "data",
                fields: e
            }),
            listeners: {
                update: function(o, n, p) {
                    if (p == Ext.data.Record.EDIT) {
                        Ext.Ajax.request({
                            url: g.baseUrl + "/saveOrUpdate",
                            params: {
                                type: h,
                                value: n.get("value"),
                                id: n.get("id")
                            },
                            success: function(q) {
                                var r = Ext.decode(q.responseText);
                                if (r.success == true) {
                                    CNOA.msg.notice2(r.msg)
                                } else {
                                    if (r.failure == true) {
                                        CNOA.msg.alert(r.msg)
                                    }
                                }
                                switch (h) {
                                    case 0:
                                        g.unitmeasure.store.reload();
                                        break;
                                    case 2:
                                        g.assetssource.store.reload();
                                        break;
                                    case 3:
                                        g.reduceway.store.reload();
                                        break;
                                    case 4:
                                        g.storageplace.store.reload();
                                        break;
                                    case 6:
                                        g.manufactuer.store.reload();
                                        break;
                                    case 7:
                                        g.supplier.store.reload();
                                        break;
                                    case 9:
                                        g.residuals.store.reload();
                                        break
                                }
                            }
                        })
                    }
                }
            }
        });
        var c = new Ext.grid.CheckboxSelectionModel({
            singleSelect: true
        });
        var b = new Ext.form.TextField({
            allowBlank: false
        });
        var d = new Ext.form.NumberField({
            allowBlank: false,
            regex: /^0\.1$|^0\.0\d$/,
            regexText: lang("enterBetween")
        });
        var j = new Ext.grid.ColumnModel({
            columns: [new Ext.grid.RowNumberer(), c, {
                header: "id",
                dataIndex: "id",
                hidden: true
            },
                {
                    header: lang("name"),
                    dataIndex: "value",
                    width: 180,
                    editor: h == 9 ? d: b
                }]
        });
        var f = new Ext.ux.grid.RowEditor({
            saveText: lang("ok"),
            cancelText: lang("cancel")
        });
        var a = new Ext.grid.GridPanel({
            title: i,
            stripeRows: true,
            store: k,
            sm: c,
            cm: j,
            plugins: [f],
            tbar: new Ext.Toolbar({
                items: [{
                    text: lang("refresh"),
                    iconCls: "icon-system-refresh",
                    handler: function() {
                        k.reload()
                    }
                },
                    {
                        text: lang("add"),
                        iconCls: "icon-utils-s-add",
                        handler: m
                    },
                    {
                        text: lang("del"),
                        iconCls: "icon-utils-s-delete",
                        handler: l
                    },
                    ("<span style='color:#999'>" + lang("dblclickToEdit") + "</span>")]
            })
        });
        function m() {
            var n = new k.recordType({
                id: "",
                value: ""
            });
            f.stopEditing();
            a.store.insert(0, n);
            f.startEditing(0)
        }
        function l() {
            var n = a.getSelectionModel().getSelected();
            if (!n) {
                return false
            }
            CNOA.msg.cf(lang("areYouDelete"),
                function(o) {
                    if (o == "yes") {
                        Ext.Ajax.request({
                            url: g.baseUrl + "/delete",
                            params: {
                                id: n.get("id")
                            },
                            success: function(p) {
                                k.remove(n);
                                k.reload();
                                var q = Ext.decode(p.responseText);
                                if (q.success == true) {
                                    CNOA.msg.notice2(q.msg)
                                } else {
                                    CNOA.msg.alert(q.msg)
                                }
                            }
                        })
                    }
                })
        }
        return a
    }
};
var sm_assets_assets = 1;
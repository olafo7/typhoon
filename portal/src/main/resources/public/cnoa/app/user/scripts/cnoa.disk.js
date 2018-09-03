var CNOA_user_disk_common = {
    getFileExt: function(d, a) {
        var b, c;
        switch (d) {
            case "jpg":
            case "bmp":
            case "png":
                b = "jpg.gif";
                c = lang("picFile") + "(" + d + ")";
                break;
            case "rar":
            case "zip":
            case "7z":
                b = "rar.gif";
                c = lang("zipFile") + "(" + d + ")";
                break;
            case "txt":
                b = "txt.gif";
                c = lang("textFile") + "(" + d + ")";
                break;
            case "xls":
            case "xlsx":
                b = "excel.gif";
                c = lang("excelTable") + "(" + d + ")";
                break;
            case "doc":
            case "docx":
                b = "word.gif";
                c = lang("wordFile") + "(" + d + ")";
                break;
            case "ppt":
            case "pptx":
                b = "word.gif";
                c = lang("pptFile") + "(" + d + ")";
                break;
            default:
                b = "file.gif";
                c = d + lang("file");
                break
        }
        if (a == 1) {
            return "resources/images/icons_file/" + b
        } else {
            return c
        }
    }
};
function makeplanAttacBtn(g, e, d, f) {
    var c = function(j, a) {
        var i, l = "none";
        var k = j.substring(j.lastIndexOf(".") + 1).toLowerCase();
        switch (k) {
            case "jpg":
            case "bmp":
            case "png":
                i = "jpg.gif";
                l = "pic";
                break;
            case "rar":
            case "zip":
            case "7z":
                i = "rar.gif";
                break;
            case "txt":
                i = "txt.gif";
                l = "txt";
                break;
            case "xls":
            case "xlsx":
                i = "excel.gif";
                l = "xls";
                break;
            case "doc":
            case "docx":
                i = "word.gif";
                l = "doc";
                break;
            default:
                i = "file.gif";
                break
        }
        if (a == true) {
            return l
        } else {
            return '<img src="./resources/images/icons_file/' + i + '" width=16 height=16 align="absmiddle" >' + j + " (" + f + ")"
        }
    };
    var b = Ext.fly(g);
    b.dom.innerHTML = c(d);
    var h = new Ext.menu.Menu({
        id: "menu_" + g,
        items: [{
            text: d,
            disabled: true
        },
            {
                xtype: "swfdownloadmenuitem",
                text: lang("download"),
                iconCls: "icon-file-down",
                dlurl: e,
                dlname: d
            },
            {
                text: lang("viewPreview"),
                hidden: (c(d, true) == "pic" || c(d, true) == "txt" || c(d, true) == "xls") ? false: true,
                handler: function() {
                    window.open("./resources/preview.php?url=" + encodeURIComponent(e))
                }
            },
            {
                text: lang("edit"),
                pressed: false,
                hidden: true,
                handler: function() {
                    window.open("./index.php?action=commonJob&act=viewDocForActivex&url=" + encodeURIComponent(e))
                }
            }]
    });
    b.dom.onmouseover = function(a) {
        try {
            Ext.getCmp(cnoa_plan_user_plan_attach_btn).hide();
            Ext.fly(cnoa_plan_user_plan_attach_btn.replace("menu_", "")).dom.style.backgroundColor = "#F2E6E6"
        } catch(j) {}
        Ext.fly(g).dom.style.backgroundColor = "#CEC1FF";
        if (Ext.isIE) {
            i = [window.event.clientX, window.event.clientY]
        } else {
            var i = [a.clientX, a.clientY]
        }
        h.showAt(i);
        cnoa_plan_user_plan_attach_btn = "menu_" + g
    }
}
var CNOA_user_disk_indexClass, CNOA_user_disk_index;
CNOA_user_disk_indexClass = CNOA.Class.create();
CNOA_user_disk_indexClass.prototype = {
    init: function() {
        var a = this;
        this.date = new Date();
        this.todayIsMarkup = false;
        this.pageInit = false;
        this.nowfid = 0;
        this.nowpid = 0;
        this.nowpath = "";
        this.ID_tree_treeRoot = Ext.id();
        this.ID_uppath = Ext.id();
        this.ID_diskStuts = Ext.id();
        this.ID_find_edtWord = Ext.id();
        this.baseUrl = "user/disk";
        this.treeRoot = new Ext.tree.AsyncTreeNode({
            text: lang("myDisk"),
            id: "0",
            pid: "0",
            fid: "0",
            cls: "folder",
            iconcls: "folder",
            expanded: true
        });
        this.treeLoader = new Ext.tree.TreeLoader({
            dataUrl: this.baseUrl + "/getDir",
            preloadChildren: false,
            clearOnLoad: false,
            listeners: {
                load: function(b) {}.createDelegate(this),
                beforeload: function(c, b, f) {
                    try {
                        c.baseParams.pid = b.attributes.fid
                    } catch(d) {}
                }
            }
        });
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
                click: function(b) {
                    a.nowpid = b.attributes.pid;
                    a.nowfid = b.attributes.fid;
                    a.store.load({
                        params: {
                            pid: a.nowfid
                        }
                    });
                    b.expand();
                    a.nowpath = b.getPath();
                    a.checkUpPathBtn();
                    a.showFullPath()
                }.createDelegate(this),
                render: function() {}
            }
        });
        this.fields = [{
            name: "fid"
        },
            {
                name: "pid"
            },
            {
                name: "ext"
            },
            {
                name: "name"
            },
            {
                name: "size"
            },
            {
                name: "type"
            },
            {
                name: "shareFrom"
            },
            {
                name: "downpath"
            },
            {
                name: "postTime"
            },
            {
                name: "email"
            }];
        this.store = new Ext.data.Store({
            proxy: new Ext.data.HttpProxy({
                url: this.baseUrl + "/getList"
            }),
            reader: new Ext.data.JsonReader({
                totalProperty: "total",
                root: "data",
                fields: this.fields
            }),
            listeners: {
                load: function() {
                    try {
                        a.dirTree.expandPath(a.nowpath);
                        a.dirTree.selectPath(a.nowpath);
                        a.refreshDiskStuts()
                    } catch(b) {}
                }
            }
        });
        this.store.load({
            params: {
                pid: 0
            }
        });
        this.sm = new Ext.grid.CheckboxSelectionModel();
        this.colModel = new Ext.grid.ColumnModel([this.sm, {
            header: "fid",
            dataIndex: "fid",
            width: 1,
            sortable: true,
            hidden: true
        },
            {
                header: lang("opt"),
                dataIndex: "opt",
                width: 160,
                sortable: false,
                renderer: this.makeOpt.createDelegate(this)
            },
            {
                header: lang("fileName"),
                dataIndex: "name",
                width: 120,
                sortable: true,
                id: "name",
                renderer: this.makeTitle.createDelegate(this)
            },
            {
                header: lang("size"),
                dataIndex: "size",
                width: 100,
                sortable: true
            },
            {
                header: lang("type"),
                dataIndex: "type",
                width: 100,
                sortable: true,
                renderer: this.makeType.createDelegate(this)
            },
            {
                header: lang("addTime"),
                dataIndex: "postTime",
                width: 130,
                sortable: true
            },
            {
                header: "",
                dataIndex: "noIndex",
                width: 1,
                menuDisabled: true,
                resizable: false
            }]);
        this.grid = new Ext.grid.GridPanel({
            store: this.store,
            loadMask: {
                msg: lang("waiting")
            },
            cm: this.colModel,
            stripeRows: true,
            sm: this.sm,
            hideBorders: true,
            hideBorders: true,
            border: false,
            autoExpandColumn: "name",
            listeners: {
                rowdblclick: function(c, f) {
                    var e = c.store.getAt(f).get("fid");
                    var b = c.store.getAt(f).get("pid");
                    var d = c.store.getAt(f).get("type");
                    if (d == "f") {
                        return
                    }
                    a.nowpid = b;
                    a.nowfid = e;
                    var g = a.dirTree.getNodeById(e);
                    a.nowpath = g.getPath();
                    a.dirTree.selectPath(g.getPath());
                    g.expand();
                    a.store.load({
                        params: {
                            pid: e
                        }
                    });
                    a.checkUpPathBtn();
                    a.showFullPath()
                }.createDelegate(this),
                rowclick: function(b, c) {},
                rowcontextmenu: function(b, i, f) {
                    f.preventDefault();
                    f.stopEvent();
                    this.grid.getSelectionModel().selectRow(i);
                    var d = this.grid.getSelectionModel().getSelected();
                    var h = false;
                    try {
                        if (CNOA_IN_AIR == 1) {
                            h = true
                        }
                    } catch(g) {
                        if (d.get("type") == "f") {
                            h = false
                        } else {
                            h = true
                        }
                    }
                    var c = new Ext.menu.Menu({
                        items: [{
                            text: lang("rename"),
                            handler: function() {
                                a.showRenameWindow("rename", d.get("name"), d.get("fid"), d.get("type"))
                            },
                            iconCls: "icon-order-ss-edit",
                            scope: this
                        },
                            {
                                text: lang("del"),
                                handler: function() {
                                    a.doDelete([d.get("fid")])
                                },
                                iconCls: "icon-attac-delete",
                                scope: this
                            }]
                    });
                    c.showAt(f.getXY())
                }.createDelegate(this)
            }
        });
        this.dirPanel = new Ext.Panel({
            region: "west",
            layout: "fit",
            split: true,
            border: false,
            width: 176,
            minWidth: 30,
            maxWidth: 300,
            bodyStyle: "border-right-width:1px;",
            items: [this.dirTree],
            tbar: new Ext.Toolbar({
                style: "border-right-width:1px;",
                items: [{
                    handler: function(b, c) {
                        a.refreshTree();
                        a.store.reload()
                    }.createDelegate(this),
                    iconCls: "icon-system-refresh",
                    tooltip: lang("refresh"),
                    text: lang("refresh")
                },
                    ("<span style='color:#999'>" + lang("clickShowDownFolder") + "</span>")]
            })
        });
        this.gridPanel = new Ext.Panel({
            region: "center",
            layout: "fit",
            border: false,
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
                        handler: function(b, c) {
                            a.goUpPath()
                        }.createDelegate(this),
                        disabled: true,
                        id: this.ID_uppath,
                        iconCls: "arrow-up-double",
                        cls: "btn-blue4",
                        text: lang("up")
                    },
                    {
                        handler: function(b, c) {
                            a.showRenameWindow("add")
                        }.createDelegate(this),
                        iconCls: "icon-disk-folder-add",
                        cls: "btn-red1",
                        text: lang("newFolder")
                    },
                    {
                        text: lang("del"),
                        cls: "btn-gray1",
                        handler: function(b) {
                            var c = a.grid.getSelectionModel().getSelections();
                            if (c.length == 0) {
                                CNOA.miniMsg.alertShowAt(b, lang("mustSelectOneRow"))
                            } else {
                                CNOA.msg.cf(lang("confirmToDelete"),
                                    function(d) {
                                        if (d == "yes") {
                                            var e = [];
                                            $.each(c,
                                                function(g, f) {
                                                    e.push(f.get("fid"))
                                                });
                                            a.doDelete(e)
                                        }
                                    })
                            }
                        },
                        iconCls: "icon-utils-s-delete",
                        scope: this
                    },
                    {
                        handler: function(b, c) {
                            var d = new Ext.Window({
                                width: 500,
                                height: 300,
                                layout: "fit",
                                modal: true,
                                title: lang("upFile"),
                                resizable: false,
                                items: [],
                                listeners: {
                                    render: function(e) {
                                        Ext.Ajax.request({
                                            url: a.baseUrl + "&task=checksize",
                                            method: "GET",
                                            success: function(g) {
                                                var f = Ext.decode(g.responseText);
                                                if (f.success === true) {
                                                    e.add(new Ext.ux.SwfUploadPanel({
                                                        border: false,
                                                        upload_url: "../" + a.baseUrl + "&task=upload&CNOAOASESSID=" + CNOA.cookie.get("CNOAOASESSID"),
                                                        post_params: {
                                                            pid: a.nowfid
                                                        },
                                                        flash_url: "resources/swfupload.swf",
                                                        single_file_select: false,
                                                        confirm_delete: false,
                                                        remove_completed: false,
                                                        file_types: "*.*",
                                                        file_types_description: lang("allType"),
                                                        listeners: {
                                                            fileUploadComplete: function() {
                                                                a.fileUploadComplete()
                                                            },
                                                            uploadError: function(i, h, j) {}
                                                        }
                                                    }));
                                                    e.doLayout()
                                                } else {
                                                    e.close();
                                                    CNOA.msg.alert(f.msg,
                                                        function() {})
                                                }
                                            }
                                        })
                                    },
                                    close: function() {}
                                }
                            }).show()
                        }.createDelegate(this),
                        iconCls: "icon-file-up",
                        cls: "btn-yellow1",
                        text: lang("upFile")
                    },
                    {
                        text: lang("packEmail"),
                        iconCls: "icon-pack-Email",
                        cls: "btn-blue3",
                        handler: function(c) {
                            var e = a.grid.getSelectionModel().getSelections();
                            if (e.length == 0) {
                                CNOA.miniMsg.alertShowAt(c, lang("noFileToSend"))
                            } else {
                                if (e) {
                                    var d = "";
                                    for (var b = 0; b < e.length; b++) {
                                        if (e[b].get("email") == 1) {
                                            alert(lang("shareFileNotAllowSendEmail", e[b].get("name")))
                                        } else {
                                            d += e[b].get("fid") + ","
                                        }
                                    }
                                    if (d != "") {
                                        a.emailpackage(d)
                                    }
                                }
                            }
                        }
                    },
                    {
                        text: "导出目录",
                        cls: "btn-blue3",
                        handler: function() {
                            ajaxDownload(a.baseUrl + "&task=exportCatalog&fid=" + a.nowfid)
                        }
                    },
                    ("<span style='color:#999'>" + lang("dblToEnterNotice") + "</span>"), "->", {
                        xtype: "cnoa_helpBtn",
                        helpid: 80
                    }]
            })
        });
        this.addressBar = new Ext.BoxComponent({
            autoEl: {
                tag: "span",
                html: lang("pathMyDisk")
            }
        });
        this.findFilePanel = new Ext.Panel({
            layout: "fit",
            border: false,
            items: [this.gridPanel],
            tbar: [lang("fileName") + ":", {
                xtype: "textfield",
                id: this.ID_find_edtWord,
                width: 280
            },
                {
                    xtype: "button",
                    text: lang("search"),
                    handler: function() {
                        var b = Ext.getCmp(a.ID_find_edtWord).getValue();
                        a.store.load({
                            params: {
                                pid: a.nowfid,
                                word: b
                            }
                        })
                    }
                },
                {
                    text: lang("clear"),
                    handler: function() {
                        Ext.getCmp(a.ID_find_edtWord).setValue("");
                        a.store.load({
                            params: {
                                pid: a.nowfid
                            }
                        })
                    }
                }]
        });
        this.centerPanel = new Ext.Panel({
            region: "center",
            bodyStyle: "border-left-width:1px;overflow:auto;",
            layout: "fit",
            border: false,
            items: [this.findFilePanel],
            tbar: new Ext.Toolbar({
                style: "border-left-width:1px;",
                items: [this.addressBar]
            }),
            bbar: new Ext.Toolbar({
                items: [new Ext.BoxComponent({
                    id: this.ID_diskStuts,
                    autoEl: {
                        tag: "span"
                    }
                })]
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
    refreshTree: function() {
        this.treeRoot.reload()
    },
    makeTitle: function(f, d, a, g, b, e) {
        var c = a.data;
        if (c.type == "d") {
            return '<img src="' + CNOA_EXTJS_PATH + '/resources/images/default/tree/folder.gif" align="absmiddle">&nbsp;' + f
        } else {
            if (c.type == "sf") {
                return '<img src="' + CNOA_user_disk_common.getFileExt(c.ext, 1) + '" align="absmiddle">&nbsp;' + f + "." + c.ext + " [" + lang("shareFrom", c.shareFrom) + "]"
            } else {
                return '<img src="' + CNOA_user_disk_common.getFileExt(c.ext, 1) + '" align="absmiddle">&nbsp;' + f + "." + c.ext
            }
        }
    },
    makeType: function(f, d, a, g, b, e) {
        var c = a.data;
        if (f == "d") {
            return lang("folder")
        } else {
            return CNOA_user_disk_common.getFileExt(c.ext, 2)
        }
    },
    makeOpt: function(f, d, a, g, b, e) {
        var c = a.data;
        if (c.type == "d") {
            return ""
        } else {
            return c.downpath
        }
    },
    showRenameWindow: function(j, a, c, i) {
        var g = this;
        var c = c == undefined ? "0": c;
        var e = Ext.id();
        var h = function() {
            var l = b.getForm();
            var k = l.findField("name");
            if (k.getValue() == a) {
                CNOA.miniMsg.alertShowAt(Ext.getCmp(e), lang("noChange"));
                return
            }
            if (k.getValue().indexOf("/") !== -1) {
                CNOA.miniMsg.alertShowAt(Ext.getCmp(e), lang("fileNameNotAllow"));
                return
            }
            if (l.isValid()) {
                l.submit({
                    url: g.baseUrl + "&task=rename",
                    waitTitle: lang("notice"),
                    method: "POST",
                    waitMsg: lang("waiting"),
                    params: d,
                    success: function(m, n) {
                        f.close();
                        g.store.reload();
                        if ((i == "d") || (j == "add")) {
                            g.refreshTree()
                        }
                    }.createDelegate(this),
                    failure: function(m, n) {
                        CNOA.msg.alert(n.result.msg,
                            function() {})
                    }.createDelegate(this)
                })
            }
        };
        var b = new Ext.form.FormPanel({
            bodyStyle: "padding:10px;",
            border: false,
            waitMsgTarget: true,
            items: [{
                xtype: "textfield",
                width: 325,
                hideLabel: true,
                allowBlank: false,
                name: "name",
                value: a,
                listeners: {
                    render: function(k) {
                        k.focus.defer(100, k)
                    }
                }
            }]
        });
        var f = new Ext.Window({
            title: j == "add" ? lang("newFolder") : lang("rename"),
            width: 360,
            height: 130,
            modal: true,
            resizable: false,
            items: b,
            layout: "fit",
            buttons: [{
                text: lang("ok"),
                id: e,
                iconCls: "icon-btn-save",
                handler: function() {
                    h()
                }.createDelegate(this)
            },
                {
                    text: lang("cancel"),
                    iconCls: "icon-dialog-cancel",
                    handler: function() {
                        f.close()
                    }
                }],
            keys: [{
                key: Ext.EventObject.ENTER,
                fn: h,
                scope: this
            }]
        }).show();
        var d;
        if (j == "add") {
            d = {
                pid: g.nowfid,
                type: j
            }
        } else {
            d = {
                fid: c,
                type: j
            }
        }
    },
    checkUpPathBtn: function() {
        var a = this;
        if (a.nowfid == "0" && a.nowpid == "0") {
            Ext.getCmp(this.ID_uppath).disable()
        } else {
            Ext.getCmp(this.ID_uppath).enable()
        }
    },
    goUpPath: function() {
        var c = this;
        if (c.nowfid == "0" && c.nowpid == "0") {
            c.checkUpPathBtn();
            c.showFullPath();
            return
        }
        try {
            if (c.nowpid == "0") {
                c.treeRoot.select();
                c.nowpath = c.dirTree.getNodeById("0")
            } else {
                var b = c.dirTree.getNodeById(c.nowpid);
                c.nowpath = b.getPath();
                c.dirTree.selectPath(c.nowpath);
                b.expand()
            }
            c.store.load({
                params: {
                    pid: c.nowpid
                }
            });
            c.nowfid = c.nowpid;
            c.nowpid = c.dirTree.getNodeById(c.nowfid).attributes.pid
        } catch(a) {}
        c.checkUpPathBtn();
        c.showFullPath()
    },
    showFullPath: function() {
        var c = this;
        var b = c.dirTree.getNodeById(c.nowfid);
        var a = b.getPath("text");
        a = a.substr(1, a.length);
        c.addressBar.getEl().update(lang("path") + "：&nbsp;" + a.replace(/\//g, "<span style='color:#AAAAAA;margin:0 3px;'>/</span>"))
    },
    doDelete: function(a) {
        var b = this;
        Ext.Ajax.request({
            url: b.baseUrl + "&task=delete",
            method: "POST",
            params: {
                ids: Ext.encode(a)
            },
            success: function(d) {
                var c = Ext.decode(d.responseText);
                if (c.success === true) {
                    b.store.reload();
                    b.refreshTree()
                } else {
                    CNOA.msg.alert(c.msg,
                        function() {})
                }
            }
        })
    },
    fileUploadComplete: function() {
        this.store.reload()
    },
    refreshDiskStuts: function() {
        var a = this;
        Ext.Ajax.request({
            url: a.baseUrl + "&task=getUsedInfo",
            method: "GET",
            success: function(c) {
                var b = Ext.decode(c.responseText);
                if (b.success === true) {
                    Ext.getCmp(a.ID_diskStuts).el.update(lang("myDiskInfo") + ":" + b.msg)
                }
            }
        })
    },
    downloadTimes: function(a) {
        var b = this;
        Ext.Ajax.request({
            url: b.baseUrl + "&task=downLoadTimes&fid=" + a,
            method: "POST",
            success: function(d) {
                var c = Ext.decode(d.responseText);
                if (c.success === true) {
                    if (c.reflash == true) {
                        b.store.reload()
                    }
                } else {
                    CNOA.msg.alert(c.msg,
                        function() {})
                }
            }
        })
    },
    viewTimes: function(a) {
        var b = this;
        Ext.Ajax.request({
            url: b.baseUrl + "&task=viewTimes&fid=" + a,
            method: "POST",
            success: function(d) {
                var c = Ext.decode(d.responseText);
                if (c.success === true) {
                    if (c.reflash == true) {
                        b.store.reload()
                    }
                } else {
                    CNOA.msg.alert(c.msg,
                        function() {})
                }
            }
        })
    },
    emailpackage: function(a) {
        var b = this;
        Ext.MessageBox.prompt(lang("packFolder"), lang("packFileName"),
            function(c, d) {
                if (c == "ok") {
                    Ext.Ajax.request({
                        url: b.baseUrl + "&task=packagefile",
                        method: "POST",
                        params: {
                            ids: a,
                            packname: d
                        },
                        success: function(f) {
                            var e = Ext.decode(f.responseText);
                            if (e.success === true) {
                                new Ext.SendEmail({
                                    filesPackage: true,
                                    filesPackageValue: e.msg
                                })
                            } else {
                                CNOA.msg.alert(e.msg,
                                    function() {})
                            }
                        }
                    })
                }
            })
    }
};
var CNOA_user_disk_publicClass, CNOA_user_disk_public;
CNOA_user_disk_publicClass = CNOA.Class.create();
CNOA_user_disk_publicClass.prototype = {
    init: function() {
        var a = this;
        this.date = new Date();
        this.todayIsMarkup = false;
        this.pageInit = false;
        this.nowfid = 0;
        this.nowpid = 0;
        this.nowpath = "";
        this.ID_tree_treeRoot = Ext.id();
        this.ID_uppath = Ext.id();
        this.ID_find_edtWord = Ext.id();
        this.baseUrl = "index.php?app=user&func=disk&action=public";
        this.treeRoot = new Ext.tree.AsyncTreeNode({
            text: lang("pubDisk"),
            id: "0",
            pid: "0",
            fid: "0",
            cls: "folder",
            iconcls: "folder",
            expanded: true
        });
        this.treeLoader = new Ext.tree.TreeLoader({
            dataUrl: this.baseUrl + "&task=getDir",
            preloadChildren: false,
            clearOnLoad: false,
            listeners: {
                load: function(b) {}.createDelegate(this),
                beforeload: function(c, b, f) {
                    try {
                        c.baseParams.pid = b.attributes.fid
                    } catch(d) {}
                }
            }
        });
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
                click: function(b) {
                    a.nowpid = b.attributes.pid;
                    a.nowfid = b.attributes.fid;
                    a.store.load({
                        params: {
                            pid: a.nowfid
                        }
                    });
                    b.expand();
                    a.nowpath = b.getPath();
                    a.checkUpPathBtn();
                    a.showFullPath()
                }.createDelegate(this),
                render: function() {}
            }
        });
        this.fields = [{
            name: "fid",
            mapping: "fid"
        },
            {
                name: "pid",
                mapping: "pid"
            },
            {
                name: "ext",
                mapping: "ext"
            },
            {
                name: "name",
                mapping: "name"
            },
            {
                name: "size",
                mapping: "size"
            },
            {
                name: "type",
                mapping: "type"
            },
            {
                name: "downpath",
                mapping: "downpath"
            },
            {
                name: "postTime",
                mapping: "postTime"
            }];
        this.store = new Ext.data.Store({
            proxy: new Ext.data.HttpProxy({
                url: this.baseUrl + "&task=getList"
            }),
            reader: new Ext.data.JsonReader({
                totalProperty: "total",
                root: "data",
                fields: this.fields
            }),
            listeners: {
                load: function() {
                    try {
                        a.dirTree.expandPath(a.nowpath);
                        a.dirTree.selectPath(a.nowpath)
                    } catch(b) {}
                }
            }
        });
        this.store.load({
            params: {
                pid: 0
            }
        });
        this.colModel = new Ext.grid.ColumnModel([{
            header: "fid",
            dataIndex: "fid",
            width: 1,
            sortable: true,
            hidden: true
        },
            {
                header: lang("download"),
                dataIndex: "opt",
                width: 50,
                sortable: false,
                renderer: this.makeOpt.createDelegate(this)
            },
            {
                header: lang("fileName"),
                dataIndex: "name",
                width: 120,
                sortable: true,
                id: "name",
                renderer: this.makeTitle.createDelegate(this)
            },
            {
                header: lang("size"),
                dataIndex: "size",
                width: 100,
                sortable: true
            },
            {
                header: lang("type"),
                dataIndex: "type",
                width: 100,
                sortable: true,
                renderer: this.makeType.createDelegate(this)
            },
            {
                header: lang("addtime"),
                dataIndex: "postTime",
                width: 130,
                sortable: true
            },
            {
                header: "",
                dataIndex: "noIndex",
                width: 1,
                menuDisabled: true,
                resizable: false
            }]);
        this.grid = new Ext.grid.GridPanel({
            stripeRows: true,
            store: this.store,
            loadMask: {
                msg: lang("waiting")
            },
            cm: this.colModel,
            hideBorders: true,
            hideBorders: true,
            border: false,
            autoExpandColumn: "name",
            listeners: {
                rowdblclick: function(c, f) {
                    var e = c.store.getAt(f).get("fid");
                    var b = c.store.getAt(f).get("pid");
                    var d = c.store.getAt(f).get("type");
                    if (d == "f") {
                        return
                    }
                    a.nowpid = b;
                    a.nowfid = e;
                    var g = a.dirTree.getNodeById(e);
                    a.nowpath = g.getPath();
                    a.dirTree.selectPath(g.getPath());
                    g.expand();
                    a.store.load({
                        params: {
                            pid: e
                        }
                    });
                    a.checkUpPathBtn();
                    a.showFullPath()
                }.createDelegate(this),
                rowclick: function(b, c) {},
                rowcontextmenu: function(b, h, d) {
                    d.preventDefault();
                    d.stopEvent();
                    this.grid.getSelectionModel().selectRow(h);
                    var c = this.grid.getSelectionModel().getSelected();
                    var g = false;
                    try {
                        if (CNOA_IN_AIR == 1) {
                            g = true
                        }
                    } catch(f) {
                        if (c.get("type") == "f") {
                            g = false
                        } else {
                            g = true
                        }
                    }
                    if (c.get("type") == "f") {}
                }.createDelegate(this)
            }
        });
        this.dirPanel = new Ext.Panel({
            region: "west",
            layout: "fit",
            split: true,
            border: false,
            width: 176,
            minWidth: 30,
            maxWidth: 300,
            bodyStyle: "border-right-width:1px;",
            items: [this.dirTree],
            tbar: new Ext.Toolbar({
                style: "border-right-width:1px;",
                items: [{
                    handler: function(b, c) {
                        a.refreshTree();
                        a.store.reload()
                    }.createDelegate(this),
                    iconCls: "icon-system-refresh",
                    tooltip: lang("refresh"),
                    text: lang("refresh")
                },
                    ("<span style='color:#999'>" + lang("clickShowDownFolder") + "</span>")]
            })
        });
        this.gridPanel = new Ext.Panel({
            region: "center",
            layout: "fit",
            border: false,
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
                        handler: function(b, c) {
                            a.goUpPath()
                        }.createDelegate(this),
                        disabled: true,
                        id: this.ID_uppath,
                        iconCls: "arrow-up-double",
                        text: lang("up")
                    },
                    ("<span style='color:#999'>" + lang("dblToEnterNotice") + "</span>"), "->", {
                        xtype: "cnoa_helpBtn",
                        helpid: 82
                    }]
            })
        });
        this.addressBar = new Ext.BoxComponent({
            autoEl: {
                tag: "span",
                html: lang("pathPubDisk")
            }
        });
        this.findPanel = new Ext.Panel({
            layout: "fit",
            border: false,
            items: [this.gridPanel],
            tbar: [(lang("fileName") + ":"), {
                xtype: "textfield",
                id: this.ID_find_edtWord,
                width: 260
            },
                {
                    xtype: "button",
                    text: lang("search"),
                    handler: function(c) {
                        var b = Ext.getCmp(a.ID_find_edtWord).getValue();
                        a.store.load({
                            params: {
                                pid: a.nowfid,
                                word: b
                            }
                        })
                    }
                },
                {
                    text: lang("clear"),
                    handler: function() {
                        Ext.getCmp(a.ID_find_edtWord).setValue("");
                        a.store.load({
                            params: {
                                pid: a.nowfid
                            }
                        })
                    }
                }]
        });
        this.centerPanel = this.centerPanel = new Ext.Panel({
            region: "center",
            bodyStyle: "border-left-width:1px;overflow:auto;",
            layout: "fit",
            border: false,
            items: [this.findPanel],
            tbar: new Ext.Toolbar({
                style: "border-left-width:1px;",
                items: [this.addressBar]
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
    refreshTree: function() {
        this.treeRoot.reload()
    },
    makeTitle: function(f, d, a, g, b, e) {
        var c = a.data;
        if (c.type == "d") {
            return '<img src="' + CNOA_EXTJS_PATH + '/resources/images/default/tree/folder.gif" align="absmiddle">&nbsp;' + f
        } else {
            return '<img src="' + CNOA_user_disk_common.getFileExt(c.ext, 1) + '" align="absmiddle">&nbsp;' + f
        }
    },
    makeType: function(f, d, a, g, b, e) {
        var c = a.data;
        if (f == "d") {
            return "文件夹"
        } else {
            return CNOA_user_disk_common.getFileExt(c.ext, 2)
        }
    },
    makeOpt: function(f, d, a, g, b, e) {
        var c = a.data;
        if (c.type == "d") {
            return ""
        } else {
            return c.downpath
        }
    },
    checkUpPathBtn: function() {
        var a = this;
        if (a.nowfid == "0" && a.nowpid == "0") {
            Ext.getCmp(this.ID_uppath).disable()
        } else {
            Ext.getCmp(this.ID_uppath).enable()
        }
    },
    goUpPath: function() {
        var c = this;
        if (c.nowfid == "0" && c.nowpid == "0") {
            c.checkUpPathBtn();
            c.showFullPath();
            return
        }
        try {
            if (c.nowpid == "0") {
                c.treeRoot.select();
                c.nowpath = c.dirTree.getNodeById("0")
            } else {
                var b = c.dirTree.getNodeById(c.nowpid);
                c.nowpath = b.getPath();
                c.dirTree.selectPath(c.nowpath);
                b.expand()
            }
            c.store.load({
                params: {
                    pid: c.nowpid
                }
            });
            c.nowfid = c.nowpid;
            c.nowpid = c.dirTree.getNodeById(c.nowfid).attributes.pid
        } catch(a) {}
        c.checkUpPathBtn();
        c.showFullPath()
    },
    showFullPath: function() {
        var c = this;
        var b = c.dirTree.getNodeById(c.nowfid);
        var a = b.getPath("text");
        a = a.substr(1, a.length);
        c.addressBar.getEl().update(lang("path") + "：&nbsp;" + a.replace(/\//g, "<span style='color:#AAAAAA;margin:0 3px;'>/</span>"))
    }
};
var CNOA_user_disk_mgrnetClass, CNOA_user_disk_mgrnet;
CNOA_user_disk_mgrnetClass = CNOA.Class.create();
CNOA_user_disk_mgrnetClass.prototype = {
    init: function() {
        var a = this;
        this.ID_btn_save = Ext.id();
        this.baseUrl = "index.php?app=user&func=disk&action=mgrnet";
        this.netPanel = new Ext.form.FormPanel({
            style: "padding: 20px",
            border: false,
            items: [{
                xtype: "fieldset",
                title: lang("userDiskSizeSet"),
                width: 506,
                defaults: {
                    border: false
                },
                items: [{
                    xtype: "panel",
                    width: 506,
                    layout: "table",
                    items: [{
                        xtype: "label",
                        text: lang("setUserDiskSpace") + ":"
                    },
                        {
                            xtype: "textfield",
                            name: "maxsizeperuser"
                        },
                        {
                            xtype: "label",
                            text: "(M)"
                        }]
                }]
            }]
        });
        this.mainPanel = new Ext.Panel({
            title: lang("userDiskSizeSet"),
            hideBorders: true,
            border: false,
            waitMsgTarget: true,
            labelWidth: 130,
            labelAlign: "right",
            items: [this.netPanel],
            tbar: new Ext.Toolbar({
                items: [{
                    text: lang("save"),
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
        });
        a.loadFormData()
    },
    submitForm: function() {
        var a = this;
        if (this.netPanel.getForm().isValid()) {
            this.netPanel.getForm().submit({
                url: a.baseUrl + "&task=submitForm",
                waitTitle: lang("notice"),
                method: "POST",
                waitMsg: lang("waiting"),
                success: function(b, c) {
                    CNOA.msg.notice2(c.result.msg)
                },
                failure: function(b, c) {
                    CNOA.msg.alert(c.result.msg,
                        function() {})
                }.createDelegate(this)
            })
        }
    },
    loadFormData: function() {
        var a = this;
        a.netPanel.getForm().load({
            url: a.baseUrl + "&task=loadFormData",
            method: "POST",
            success: function(b, c) {},
            failure: function(b, c) {}
        })
    }
};
var CNOA_user_disk_mgrpubClass, CNOA_user_disk_mgrpub;
CNOA_user_disk_mgrpubClass = CNOA.Class.create();
CNOA_user_disk_mgrpubClass.prototype = {
    init: function() {
        var _this = this;
        this.date = new Date();
        this.todayIsMarkup = false;
        this.pageInit = false;
        this.nowfid = 0;
        this.nowpid = 0;
        this.viewMod = CNOA.user.disk.mgrpub.viewMod;
        this.nowpath = "";
        this.storeBar = {
            word: "",
            pid: 0
        };
        this.ID_tree_treeRoot = Ext.id();
        this.ID_uppath = Ext.id();
        var ID_BTN_NEWDIR = Ext.id();
        var ID_BTN_UPLOAD = Ext.id();
        var ID_BTN_DELETE = Ext.id();
        var ID_BTN_IMPORT = Ext.id();
        this.ID_find_edtWord = Ext.id();
        this.baseUrl = "index.php?app=user&func=disk&action=mgrpub";
        this.treeRoot = new Ext.tree.AsyncTreeNode({
            text: lang("pubDisk"),
            id: "0",
            pid: "0",
            fid: "0",
            cls: "folder",
            iconcls: "folder",
            expanded: true
        });
        this.treeLoader = new Ext.tree.TreeLoader({
            dataUrl: this.baseUrl + "&task=getDir&type=tree",
            preloadChildren: false,
            clearOnLoad: false,
            listeners: {
                load: function(node) {
                    _this.showFullPath()
                }.createDelegate(this),
                beforeload: function(th, node, callback) {
                    try {
                        th.baseParams.pid = node.attributes.fid;
                        th.baseParams.path = node.attributes.path
                    } catch(e) {}
                }
            }
        });
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
                click: function(node) {
                    _this.nowpid = node.attributes.pid;
                    _this.nowfid = node.attributes.fid;
                    _this.storeBar.pid = _this.nowfid;
                    _this.store.reload({
                        params: {
                            pid: _this.storeBar.pid
                        }
                    });
                    node.expand();
                    _this.nowpath = node.getPath();
                    _this.checkUpPathBtn();
                    _this.showFullPath()
                }.createDelegate(this),
                render: function() {}
            }
        });
        this.fields = [{
            name: "id",
            mapping: "id"
        },
            {
                name: "pid",
                mapping: "pid"
            },
            {
                name: "ext",
                mapping: "ext"
            },
            {
                name: "name",
                mapping: "name"
            },
            {
                name: "size",
                mapping: "size"
            },
            {
                name: "type",
                mapping: "type"
            },
            {
                name: "downpath",
                mapping: "downpath"
            },
            {
                name: "downhref"
            },
            {
                name: "viewhref"
            },
            {
                name: "edithref"
            },
            {
                name: "postTime",
                mapping: "postTime"
            },
            {
                name: "postname"
            },
            {
                name: "vi"
            },
            {
                name: "mv"
            },
            {
                name: "dl"
            },
            {
                name: "sh"
            },
            {
                name: "ed"
            },
            {
                name: "pr"
            },
            {
                name: "fileid"
            },
            {
                name: ""
            }];
        this.store = new Ext.data.Store({
            autoLoad: true,
            baseParams: _this.storeBar,
            proxy: new Ext.data.HttpProxy({
                url: this.baseUrl + "&task=getList&viewMod=" + this.viewMod
            }),
            reader: new Ext.data.JsonReader({
                idProperty: "",
                totalProperty: "total",
                root: "data",
                fields: this.fields
            }),
            listeners: {
                load: function(th, record, e) {
                    var dl = th.reader.jsonData.dl;
                    var dt = th.reader.jsonData.dt;
                    var up = th.reader.jsonData.up;
                    var mgr = th.reader.jsonData.mgr;
                    var pr = th.reader.jsonData.pr;
                    if (pr) {
                        CNOA.viewOfficeAllowPrint = 1
                    } else {
                        CNOA.viewOfficeAllowPrint = 0
                    }
                    if (mgr) {
                        _this.mgr = true;
                        Ext.getCmp(ID_BTN_NEWDIR).show();
                        if (_this.nowfid != undefined && _this.nowfid != 0) {
                            Ext.getCmp(ID_BTN_UPLOAD).show();
                            if (CNOA_USER_JOBTYPE == "superAdmin") {
                                Ext.getCmp(ID_BTN_IMPORT).show()
                            }
                        } else {
                            Ext.getCmp(ID_BTN_UPLOAD).hide();
                            Ext.getCmp(ID_BTN_IMPORT).hide()
                        }
                        Ext.getCmp(ID_BTN_DELETE).show();
                        _this.dl = true
                    } else {
                        _this.mgr = false;
                        Ext.getCmp(ID_BTN_NEWDIR).hide();
                        if (dl) {
                            _this.dl = true
                        } else {
                            _this.dl = false
                        }
                        if (dt) {
                            Ext.getCmp(ID_BTN_DELETE).show()
                        } else {
                            Ext.getCmp(ID_BTN_DELETE).hide()
                        }
                        if (up) {
                            Ext.getCmp(ID_BTN_UPLOAD).show();
                            if (CNOA_USER_JOBTYPE == "superAdmin") {
                                Ext.getCmp(ID_BTN_IMPORT).show()
                            }
                        } else {
                            Ext.getCmp(ID_BTN_UPLOAD).hide();
                            Ext.getCmp(ID_BTN_IMPORT).hide()
                        }
                    }
                    try {
                        _this.dirTree.expandPath(th.reader.jsonData.nowpath);
                        _this.dirTree.selectPath(th.reader.jsonData.nowpath)
                    } catch(e) {}
                    _this.initPreviewImage()
                }
            }
        });
        this.sm = new Ext.grid.CheckboxSelectionModel({
            singleSelect: false
        });
        this.colModel = new Ext.grid.ColumnModel([this.sm, {
            header: "id",
            dataIndex: "id",
            width: 1,
            sortable: true,
            hidden: true
        },
            {
                header: lang("opt"),
                dataIndex: "opt",
                width: 160,
                sortable: false,
                renderer: this.makeOpt.createDelegate(this)
            },
            {
                header: lang("fileName"),
                dataIndex: "name",
                width: 120,
                sortable: true,
                id: "name",
                renderer: this.makeTitle.createDelegate(this)
            },
            {
                header: lang("size"),
                dataIndex: "size",
                width: 100,
                sortable: true,
                renderer: function(v, c, record) {
                    var rd = record.data;
                    if (rd.type == "d") {
                        return ""
                    } else {
                        return v
                    }
                }
            },
            {
                header: lang("type"),
                dataIndex: "type",
                width: 100,
                sortable: true,
                renderer: this.makeType.createDelegate(this)
            },
            {
                header: lang("addTime"),
                dataIndex: "postTime",
                width: 130,
                sortable: true
            },
            {
                header: lang("creater"),
                dataIndex: "postname",
                width: 130,
                sortable: true
            },
            {
                header: "",
                dataIndex: "noIndex",
                width: 1,
                menuDisabled: true,
                resizable: false
            }]);
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
            autoExpandColumn: "name",
            listeners: {
                rowdblclick: function(grid, row) {
                    var fid = grid.store.getAt(row).get("id");
                    var pid = grid.store.getAt(row).get("pid");
                    var type = grid.store.getAt(row).get("type");
                    if (type == "f") {
                        return
                    }
                    _this.storeBar.pid = fid;
                    _this.nowpid = pid;
                    _this.nowfid = fid;
                    var selectednode = _this.dirTree.getNodeById(fid);
                    try {
                        _this.nowpath = selectednode.getPath();
                        _this.dirTree.selectPath(selectednode.getPath());
                        selectednode.expand()
                    } catch(e) {}
                    _this.store.reload({
                        params: {
                            pid: _this.nowfid
                        }
                    });
                    Ext.getCmp(_this.ID_find_edtWord).setValue("");
                    _this.storeBar.word = "";
                    _this.checkUpPathBtn();
                    _this.showFullPath()
                }.createDelegate(this),
                rowcontextmenu: function(client, rowIndex, e) {
                    e.preventDefault();
                    e.stopEvent();
                    this.grid.getSelectionModel().selectRow(rowIndex);
                    var rd = this.grid.getSelectionModel().getSelected();
                    var contextMenu = new Ext.menu.Menu({
                        items: [{
                            text: lang("rename"),
                            handler: function() {
                                _this.showRenameWindow("rename", rd.get("name"), rd.get("id"), rd.get("type"))
                            },
                            iconCls: "icon-order-ss-edit",
                            scope: this
                        },
                            {
                                text: lang("historyLog"),
                                handler: function() {
                                    _this.logWin(rd.data)
                                },
                                iconCls: "icon-application-list",
                                scope: this
                            },
                            {
                                text: lang("historyVer"),
                                hidden: rd.get("type") == "f" ? false: true,
                                handler: function() {
                                    _this.versions(rd.data, rd.store.reader.jsonData.dt)
                                },
                                iconCls: "icon-page-view",
                                scope: this
                            },
                            {
                                text: lang("userPermitSet"),
                                hidden: rd.get("type") == "d" ? false: true,
                                handler: function() {
                                    _this.doPermit(rd.data.id, rd.data.pid)
                                },
                                iconCls: "folder-share",
                                scope: this
                            },
                            {
                                text: lang("moveTO"),
                                hidden: rd.get("type") == "f" ? false: true,
                                handler: function() {
                                    Ext.Ajax.request({
                                        url: _this.baseUrl + "&task=move",
                                        method: "POST",
                                        params: {
                                            fileid: rd.data.id
                                        },
                                        success: function(response, action) {
                                            var res = Ext.decode(response.responseText);
                                            if (res.success == true) {
                                                moveToDir(rd.data.id)
                                            } else {
                                                CNOA.msg.alert(res.msg)
                                            }
                                        }
                                    })
                                },
                                scope: this
                            }]
                    });
                    contextMenu.showAt(e.getXY())
                }.createDelegate(this)
            }
        });
        var moveToDir = function(id) {
            var dirRoot = new Ext.tree.AsyncTreeNode({
                text: lang("pubDisk"),
                id: "0",
                pid: "0",
                fid: "0",
                cls: "folder",
                iconcls: "folder",
                expanded: true
            });
            var dirLoader = new Ext.tree.TreeLoader({
                dataUrl: _this.baseUrl + "&task=getDir&type=combo",
                preloadChildren: false,
                clearOnLoad: false,
                listeners: {
                    load: function(node) {
                        _this.dirRoot.expand(true)
                    },
                    beforeload: function(th, node, callback) {
                        try {
                            th.baseParams.pid = node.attributes.fid;
                            th.baseParams.path = node.attributes.path
                        } catch(e) {}
                    }
                }
            });
            var dirTree = new Ext.tree.TreePanel({
                hideBorders: true,
                border: false,
                rootVisible: true,
                lines: true,
                containerScroll: true,
                animCollapse: false,
                checkModel: "single",
                animate: false,
                loader: dirLoader,
                root: dirRoot,
                autoScroll: true,
                listeners: {
                    checkchange: function(node, checked) {
                        if (checked) {
                            var nodes = dirTree.getChecked();
                            if (nodes && nodes.length) {
                                for (var i = 0; i < nodes.length; i++) {
                                    if (nodes[i].id != node.id) {
                                        nodes[i].getUI().toggleCheck(false);
                                        nodes[i].attributes.checked = false
                                    }
                                }
                            }
                            _this.nodeId = node.id
                        }
                    }.createDelegate(this)
                }
            });
            var dirWin = new Ext.Window({
                border: false,
                width: 320,
                height: 400,
                autoScroll: true,
                modal: true,
                layout: "fit",
                buttons: [{
                    text: lang("save"),
                    handler: function() {
                        if (_this.nodeId) {
                            Ext.Ajax.request({
                                url: _this.baseUrl + "&task=moveToDir",
                                method: "POST",
                                params: {
                                    fileid: id,
                                    pid: _this.nodeId
                                },
                                success: function(r) {
                                    var result = Ext.decode(r.responseText);
                                    if (result.success === true) {
                                        dirWin.close();
                                        _this.store.reload();
                                        CNOA.msg.notice2(result.msg)
                                    } else {
                                        CNOA.msg.alert(result.msg)
                                    }
                                }
                            })
                        }
                    }
                },
                    {
                        text: lang("cancel"),
                        handler: function() {
                            dirWin.close()
                        }
                    }],
                items: [dirTree]
            });
            dirWin.show()
        };
        this.dirPanel = new Ext.Panel({
            region: "west",
            layout: "fit",
            split: true,
            border: false,
            width: 176,
            minWidth: 30,
            maxWidth: 300,
            bodyStyle: "border-right-width:1px;",
            items: [this.dirTree],
            tbar: new Ext.Toolbar({
                style: "border-right-width:1px;",
                items: [{
                    handler: function(button, event) {
                        _this.refreshTree();
                        _this.store.reload()
                    }.createDelegate(this),
                    iconCls: "icon-system-refresh",
                    tooltip: lang("refresh"),
                    text: lang("refresh")
                },
                    ("<span style='color:#999'>" + lang("clickShowDownFolder") + "</span>")]
            })
        });
        this.gridPanel = new Ext.Panel({
            region: "center",
            layout: "fit",
            border: false,
            items: [this.grid]
        });
        this.addressBar = new Ext.BoxComponent({
            autoEl: {
                tag: "span",
                html: lang("pubDisk")
            }
        });
        this.thumbView = new Ext.DataView({
            store: this.store,
            style: "overflow:auto",
            overClass: "x-view-over",
            tpl: new Ext.XTemplate('<tpl for=".">', '<div class="thumb-wrap" title="{altName}">', '<table width="96"><tr><td align="center" valign="middle" height="92" style="border: 1px solid #ECE9D8;background-color: #FFF;">', '<div class="thumb">', '<tpl if="isImg==1">', '<a rel="isdiskimg" href="{thumb}&target=big" title="{altName}"><img src="{thumb}" fileid="{fileid}" href="{thumb}" onerror="this.src=\'resources/images/icons_file/undefined-48.gif\'" /></a>', "</tpl>", '<tpl if="isImg!=1">', '<img src="{thumb}" fileid="{fileid}" href="{thumb}" onerror="this.src=\'resources/images/icons_file/undefined-48.gif\'" />', "</tpl>", "</div>", "</td></tr></table>", '<span class="x-editable name" style="white-space:normal;word-break :break-all">{name}', "<tpl if=\"type!='d'\">", ".{ext}", "</tpl>", "</span></div>", "</tpl>", '<div class="x-clear"></div>'),
            multiSelect: true,
            itemSelector: "div.thumb-wrap",
            emptyText: "",
            picexts: ["gif", "jpg", "jpeg", "png"],
            mimetypes: ["image/gif", "image/jpeg", "image/jpeg", "image/png"],
            prepareData: function(data) {
                if (data.ext == null) {
                    data.ext = "null"
                }
                var index = this.picexts.indexOf(data.ext.toLowerCase());
                if (index == -1) {
                    data.thumb = "resources/images/icons_file/" + data.ext + "-48.gif";
                    data.isImg = "0"
                } else {
                    data.thumb = "index.php?app=user&func=disk&action=mgrpub&task=getThumb&fileid=" + data.fileid;
                    data.isImg = "1"
                }
                data.altName = data.name.replace(/<\/?[^>]*>/g, "");
                return data
            }
        });
        this.thumbView.on("dblclick",
            function(dataview, index, node, e) {
                var rec = dataview.getRecord(node);
                var type = rec.get("type");
                var pid = rec.get("pid");
                var fid = rec.get("id");
                if (type == "d") {
                    _this.nowpid = pid;
                    _this.nowfid = fid;
                    var selectednode = _this.dirTree.getNodeById(fid);
                    try {
                        _this.nowpath = selectednode.getPath();
                        _this.dirTree.selectPath(selectednode.getPath());
                        selectednode.expand()
                    } catch(e) {}
                    _this.storeBar.pid = fid;
                    _this.store.reload({
                        params: {
                            pid: _this.storeBar.pid
                        }
                    });
                    Ext.getCmp(_this.ID_find_edtWord).setValue("");
                    _this.storeBar.word = "";
                    _this.checkUpPathBtn();
                    _this.showFullPath()
                }
            },
            this);
        this.thumbView.on("contextmenu",
            function(dataview, index, node, e) {
                var rd = dataview.getRecord(node),
                    type = rd.get("type"),
                    fileid = rd.get("fileid"),
                    share = rd.get("sh"),
                    name = rd.get("name"),
                    id = rd.get("id"),
                    pid = rd.get("pid");
                e.stopEvent();
                dataview.select(node);
                var items = [];
                if (type == "f") {
                    var dhref = rd.get("downhref"),
                        vhref = rd.get("viewhref"),
                        ehref = rd.get("edithref");
                    if (!Ext.isEmpty(dhref)) {
                        items.push({
                            text: lang("download"),
                            handler: function() {
                                try {
                                    eval(dhref)
                                } catch(e) {}
                            },
                            scope: this
                        })
                    }
                    if (!Ext.isEmpty(vhref)) {
                        items.push({
                            text: lang("browse"),
                            handler: function() {
                                var isImg = rd.get("isImg");
                                try {
                                    if (isImg == 1) {
                                        window.open(vhref)
                                    } else {
                                        eval(vhref)
                                    }
                                } catch(e) {}
                            },
                            scope: this
                        })
                    }
                    if (!Ext.isEmpty(ehref)) {
                        items.push({
                            text: lang("edit"),
                            handler: function() {
                                try {
                                    eval(ehref)
                                } catch(e) {}
                            },
                            scope: this
                        })
                    }
                    if (share) {
                        items.push({
                            text: lang("share"),
                            handler: function() {
                                CNOA_user_disk_mgrpub.shareFileTo(fileid)
                            },
                            scope: this
                        })
                    }
                }
                items.push({
                    text: lang("rename"),
                    handler: function() {
                        _this.showRenameWindow("rename", name, id, type)
                    },
                    iconCls: "icon-utils-s-edit",
                    scope: this
                });
                items.push("-");
                items.push({
                    text: lang("historyLog"),
                    handler: function() {
                        _this.logWin(rd.data)
                    },
                    iconCls: "icon-application-list",
                    scope: this
                });
                items.push("-");
                if (type == "d") {
                    items.push({
                        text: lang("userPermitSet"),
                        handler: function() {
                            _this.doPermit(id, pid)
                        },
                        iconCls: "folder-share",
                        scope: this
                    })
                } else {
                    items.push({
                        text: lang("historyVer"),
                        handler: function() {
                            _this.versions(rd.data, rd.store.reader.jsonData.dt)
                        },
                        iconCls: "icon-applications-stack",
                        scope: this
                    })
                }
                items.push({
                    text: lang("moveTO"),
                    hidden: rd.get("type") == "f" ? false: true,
                    handler: function() {
                        Ext.Ajax.request({
                            url: _this.baseUrl + "&task=move",
                            method: "POST",
                            params: {
                                fileid: rd.data.id
                            },
                            success: function(response, action) {
                                var res = Ext.decode(response.responseText);
                                if (res.success == true) {
                                    moveToDir(rd.data.id)
                                } else {
                                    CNOA.msg.alert(res.msg)
                                }
                            }
                        })
                    },
                    scope: this
                });
                var contextMenu = new Ext.menu.Menu({
                    items: items
                });
                contextMenu.showAt(e.getXY())
            },
            this);
        var changeViewMod = function(viewMod) {
            Ext.Ajax.request({
                url: _this.baseUrl + "&task=changeViewMod",
                method: "POST",
                params: {
                    viewMod: viewMod
                },
                success: function(r) {}
            })
        };
        var pagingBar = new Ext.PagingToolbar({
            displayInfo: true,
            emptyMsg: lang("pagingToolbarEmptyMsg"),
            displayMsg: lang("showDataTotal2"),
            store: this.store,
            pageSize: 50
        });
        this.ctPanel = new Ext.Panel({
            border: false,
            cls: "oa-disk",
            bbar: pagingBar,
            items: [this.thumbView, this.gridPanel],
            layout: "card",
            activeItem: (function() {
                if (_this.viewMod == "thumb") {
                    return 0
                }
                if (_this.viewMod == "list") {
                    return 1
                }
            })(),
            tbar: new Ext.Toolbar({
                items: [{
                    handler: function(button, event) {
                        _this.store.reload()
                    }.createDelegate(this),
                    iconCls: "icon-system-refresh",
                    tooltip: lang("refresh"),
                    text: lang("refresh")
                },
                    {
                        handler: function(button, event) {
                            _this.goUpPath()
                        }.createDelegate(this),
                        disabled: true,
                        id: this.ID_uppath,
                        iconCls: "arrow-up-double",
                        cls: "btn-blue4",
                        text: lang("up")
                    },
                    {
                        handler: function(button, event) {
                            _this.showRenameWindow("add")
                        }.createDelegate(this),
                        id: ID_BTN_NEWDIR,
                        hidden: true,
                        cls: "btn-red1",
                        iconCls: "icon-disk-folder-add",
                        text: lang("newFolder")
                    },
                    {
                        handler: function(button, event) {
                            _this.uploadWin()
                        }.createDelegate(this),
                        id: ID_BTN_UPLOAD,
                        hidden: true,
                        cls: "btn-yellow1",
                        iconCls: "icon-file-up",
                        text: lang("upFile")
                    },
                    {
                        handler: function(button, event) {
                            _this.importWin(this.nowfid, this.nowpid)
                        }.createDelegate(this),
                        id: ID_BTN_IMPORT,
                        hidden: true,
                        cls: "btn-blue3",
                        iconCls: "document-excel-import",
                        text: lang("import2")
                    },
                    {
                        text: "导出目录",
                        cls: "btn-blue3",
                        handler: function() {
                            ajaxDownload(_this.baseUrl + "&task=exportCatalog&fid=" + _this.nowfid)
                        }
                    },
                    {
                        text: lang("del"),
                        id: ID_BTN_DELETE,
                        hidden: true,
                        handler: function() {
                            _this.del()
                        },
                        iconCls: "icon-utils-s-delete",
                        cls: "btn-gray1",
                        scope: this
                    },
                    ("<span style='color:#999'>" + lang("dblToEnterNotice") + "</span>"), "->", new Ext.Button({
                        text: lang("view1"),
                        iconCls: "application_view_list",
                        tooltip: lang("fileView1"),
                        scope: this,
                        menu: [this.thumbviewItem = new Ext.menu.CheckItem({
                            text: lang("thumbnail"),
                            handler: function() {
                                this.ctPanel.getLayout().setActiveItem(0);
                                this.viewMod = "thumb";
                                this.store.proxy = new Ext.data.HttpProxy({
                                    url: this.baseUrl + "&task=getList&viewMod=" + this.viewMod
                                });
                                changeViewMod(this.viewMod);
                                this.initPreviewImage()
                            },
                            checked: this.viewMod == "thumb" ? true: false,
                            group: "toolview",
                            scope: this
                        }), this.iconviewItem = new Ext.menu.CheckItem({
                            text: lang("icon"),
                            hidden: true,
                            checked: false,
                            group: "toolview",
                            handler: function() {
                                this.ctPanel.getLayout().setActiveItem(2);
                                this.store.proxy = new Ext.data.HttpProxy({
                                    url: this.baseUrl + "&task=getList&viewMod=" + this.viewMod
                                });
                                this.initPreviewImage()
                            },
                            scope: this
                        }), this.listviewItem = new Ext.menu.CheckItem({
                            text: lang("detailXX"),
                            checked: this.viewMod == "list" ? true: false,
                            group: "toolview",
                            handler: function() {
                                this.ctPanel.getLayout().setActiveItem(1);
                                this.store.proxy = new Ext.data.HttpProxy({
                                    url: this.baseUrl + "&task=getList&viewMod=" + this.viewMod
                                });
                                this.viewMod = "list";
                                changeViewMod(this.viewMod);
                                this.initPreviewImage()
                            },
                            scope: this
                        })]
                    }), {
                        xtype: "cnoa_helpBtn",
                        helpid: 81
                    }]
            })
        });
        this.centerPanel = new Ext.Panel({
            region: "center",
            bodyStyle: "border-left-width:1px;overflow:auto;",
            layout: "fit",
            border: false,
            items: [this.ctPanel],
            tbar: new Ext.Toolbar({
                style: "border-left-width:1px;",
                items: [lang("path") + ": ", this.addressBar, "->", lang("fileName") + ":", {
                    xtype: "textfield",
                    id: this.ID_find_edtWord,
                    width: 120
                },
                    {
                        xtype: "button",
                        text: lang("search"),
                        handler: function(btn) {
                            var sWord = Ext.getCmp(_this.ID_find_edtWord).getValue();
                            _this.storeBar.pid = _this.nowfid;
                            _this.storeBar.word = sWord;
                            _this.store.load({
                                params: {
                                    word: _this.storeBar.word
                                }
                            })
                        }
                    },
                    {
                        text: lang("clear"),
                        handler: function() {
                            Ext.getCmp(_this.ID_find_edtWord).setValue("");
                            _this.storeBar.word = "";
                            _this.storeBar.pid = _this.nowfid;
                            _this.store.load({
                                params: {
                                    word: _this.storeBar.word
                                }
                            })
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
            items: [this.dirPanel, this.centerPanel]
        })
    },
    loadFancyBox: function() {
        var a = this;
        loadCss("scripts/jquery/fancybox/jquery.fancybox-1.3.4.css");
        loadJs("scripts/jquery/fancybox/jquery.fancybox-1.3.4.pack.js", true,
            function() {
                setTimeout(function() {
                        a.initPreviewImage()
                    },
                    500)
            })
    },
    initPreviewImage: function() {
        if (!$.fancybox) {
            this.loadFancyBox()
        } else {
            $("a[rel=isdiskimg]").fancybox({
                transitionIn: "none",
                transitionOut: "none",
                titlePosition: "over",
                type: "image",
                titleFormat: function(d, c, a, b) {
                    return '<span id="fancybox-title-over">' + (a + 1) + " / " + c.length + (d.length ? " &nbsp; " + d: "") + "</span>"
                }
            })
        }
    },
    del: function() {
        var b = this,
            a;
        if (this.viewMod == "list") {
            a = b.grid.getSelectionModel().getSelections()
        } else {
            if (this.viewMod == "thumb") {
                a = b.thumbView.getSelectedRecords()
            }
        }
        if (a.length == 0) {
            CNOA.miniMsg.alertShowAt(button, lang("mustSelectOneRow"))
        } else {
            CNOA.msg.cf(lang("confirmToDelete"),
                function(c) {
                    if (c == "yes") {
                        var d = [];
                        type = a[0].get("type");
                        $.each(a,
                            function(f, e) {
                                if (e.get("type") == "f") {
                                    d.push({
                                        id: e.get("fileid"),
                                        type: "f"
                                    })
                                } else {
                                    d.push({
                                        id: e.get("id"),
                                        type: "d"
                                    })
                                }
                            });
                        b.doDelete(d)
                    }
                })
        }
    },
    uploadWin: function() {
        var e = this;
        var c = Ext.id();
        var b = new Ext.Panel({
            width: 150,
            layout: "fit",
            region: "east",
            autoScroll: true,
            tbar: [{
                xtype: "box",
                autoEl: {
                    tag: "div",
                    html: lang("versionBZ"),
                    style: "height : 22px; line-height : 22px"
                }
            }],
            border: false,
            hideBorders: true,
            items: [{
                xtype: "textarea",
                border: false,
                id: c
            }]
        });
        var a = new Ext.ux.SwfUploadPanel({
            region: "center",
            hasFolder: true,
            border: false,
            upload_url: "../" + e.baseUrl + "&task=upload&CNOAOASESSID=" + CNOA.cookie.get("CNOAOASESSID"),
            post_params: {
                pid: e.nowfid
            },
            flash_url: "resources/swfupload.swf",
            single_file_select: false,
            confirm_delete: false,
            remove_completed: false,
            file_types: "*.*",
            file_types_description: lang("allType"),
            listeners: {
                fileUploadComplete: function() {
                    e.fileUploadComplete();
                    d.close()
                },
                folderUploadStart: function() {
                    d.el.mask(lang("refreshFolderNotice"))
                },
                folderUploadComplete: function() {
                    d.el.unmask()
                },
                uploadError: function(g, f, h) {},
                startUpload: function(g) {
                    d.el.mask(lang("waiting"));
                    var f = Ext.getCmp(c).getValue();
                    g.addPostParam("note", f)
                }
            }
        });
        var d = new Ext.Window({
            width: 600,
            height: makeWindowHeight(500),
            layout: "border",
            modal: true,
            title: lang("upFile"),
            resizable: false,
            items: [a, b]
        }).show()
    },
    importWin: function(f, a) {
        var h = this,
            e, g, c;
        var b = Ext.id();
        var d = null;
        g = function(i) {
            i.disable();
            e.body.mask("请稍等，导入中...");
            d = Ext.getCmp(b).getValue();
            Ext.Ajax.request({
                url: h.baseUrl + "&task=import",
                method: "POST",
                params: {
                    pid: f,
                    folder: d
                },
                success: function(k) {
                    var j = Ext.decode(k.responseText);
                    if (j.success === true) {
                        CNOA.msg.alert(j.msg,
                            function() {
                                h.reload();
                                e.close()
                            })
                    } else {
                        CNOA.msg.alert(j.msg,
                            function() {
                                i.enable();
                                e.body.unmask()
                            })
                    }
                }
            })
        };
        c = new Ext.Panel({
            width: 150,
            layout: "form",
            bodyStyle: "padding: 10px;",
            labelWidth: 70,
            tbar: [{
                xtype: "box",
                autoEl: {
                    tag: "div",
                    html: "导入<span style='color:red;'>服务器</span>上的某个文件夹到OA系统中，请使用完全路径，如：D:\\公司人事档案",
                    style: "height : 22px; line-height : 22px"
                }
            }],
            border: false,
            hideBorders: true,
            items: [{
                xtype: "textfield",
                fieldLabel: "文件夹路径",
                width: 486,
                border: false,
                id: b
            }]
        });
        e = new Ext.Window({
            width: 600,
            height: 150,
            layout: "fit",
            modal: true,
            title: "导入文件夹",
            resizable: false,
            items: [c],
            buttons: [{
                text: "开始导入",
                handler: function(i) {
                    g(i)
                }
            },
                {
                    text: "取消",
                    handler: function() {
                        e.close()
                    }
                }]
        }).show()
    },
    refreshTree: function() {
        this.treeRoot.reload()
    },
    reload: function() {
        this.refreshTree();
        this.store.reload()
    },
    makeTitle: function(k, i, f, j, h, d) {
        var l = f.data,
            c, e;
        if (l.type == "d") {
            return '<img src="' + CNOA_EXTJS_PATH + '/resources/images/default/tree/folder.gif" align="absmiddle">&nbsp;' + k
        } else {
            var g = ["jpg", "png", "gif", "jpeg"].indexOf(l.ext.toLowerCase());
            if (g == -1) {
                c = '<img width="22" height="22" src="resources/images/icons_file/' + l.ext + '-22.gif" onerror="this.src=\'resources/images/icons_file/undefined-22.gif\'" align="absmiddle">&nbsp;' + k + "." + l.ext;
                e = "0"
            } else {
                var a = "index.php?app=user&func=disk&action=mgrpub&task=getThumb&fileid=" + l.fileid;
                var b = l.name.replace(/<\/?[^>]*>/g, "");
                c = '<a rel="isdiskimg" href="' + a + '&target=big" title="' + b + "\"><img onmouseover=\"$(this).attr('ext:qtip', '<img src=\\'" + a + '&target=middle\\\' />\')" ext:qtip="" width="22" height="22" src="' + a + '" align="absmiddle"></a>&nbsp;' + k + "." + l.ext;
                e = "1"
            }
            return c
        }
    },
    makeType: function(f, d, a, g, b, e) {
        var c = a.data;
        if (f == "d") {
            return lang("folder")
        } else {
            return CNOA_user_disk_common.getFileExt(c.ext, 2)
        }
    },
    makeOpt: function(h, f, c, g, e, a) {
        var d = this;
        var i = c.data;
        if (i.type == "d") {
            return ""
        } else {
            if (i.dl || i.vi || i.ed || i.sh) {
                var b = i.downpath;
                if (i.sh) {
                    b += '&nbsp;&nbsp;<a href="javascript:void(0);" onclick="CNOA_user_disk_mgrpub.shareFileTo(' + i.fileid + ')">' + lang("share") + "</a>"
                }
                return b
            } else {
                return lang("noPermit")
            }
        }
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
    showRenameWindow: function(j, a, c, i) {
        var g = this;
        if (!a) {
            a = ""
        }
        a = a.replace(/<span\/?[^>]*>.*<\/span>/g, "");
        var c = c == undefined ? "0": c;
        var e = Ext.id();
        var h = function() {
            var l = b.getForm();
            var k = l.findField("name");
            if (k.getValue() == a) {
                CNOA.miniMsg.alertShowAt(Ext.getCmp(e), lang("noChange"));
                return
            }
            if (k.getValue().indexOf("/") !== -1) {
                CNOA.miniMsg.alertShowAt(Ext.getCmp(e), lang("fileNameNotAllow"));
                return
            }
            if (l.isValid()) {
                l.submit({
                    url: g.baseUrl + "&task=rename",
                    waitTitle: lang("notice"),
                    method: "POST",
                    waitMsg: lang("waiting"),
                    params: d,
                    success: function(m, n) {
                        f.close();
                        g.store.reload();
                        if ((i == "d") || (j == "add")) {
                            g.refreshTree()
                        }
                    }.createDelegate(this),
                    failure: function(m, n) {
                        CNOA.msg.alert(n.result.msg,
                            function() {})
                    }.createDelegate(this)
                })
            }
        };
        var b = new Ext.form.FormPanel({
            bodyStyle: "padding:10px;",
            border: false,
            waitMsgTarget: true,
            items: [{
                xtype: "textfield",
                width: 325,
                hideLabel: true,
                allowBlank: false,
                name: "name",
                value: a,
                listeners: {
                    render: function(k) {
                        k.focus.defer(100, k)
                    }
                }
            }]
        });
        var f = new Ext.Window({
            title: j == "add" ? lang("newFolder") : lang("rename"),
            width: 360,
            height: 130,
            modal: true,
            resizable: false,
            items: b,
            layout: "fit",
            buttons: [{
                text: lang("ok"),
                id: e,
                iconCls: "icon-btn-save",
                handler: function() {
                    h()
                }.createDelegate(this)
            },
                {
                    text: lang("cancel"),
                    iconCls: "icon-dialog-cancel",
                    handler: function() {
                        f.close()
                    }
                }],
            keys: [{
                key: Ext.EventObject.ENTER,
                fn: h,
                scope: this
            }]
        }).show();
        var d;
        if (j == "add") {
            d = {
                pid: g.nowfid,
                type: j
            }
        } else {
            d = {
                fid: c,
                type: j,
                ftype: i
            }
        }
    },
    checkUpPathBtn: function() {
        var a = this;
        if (a.nowfid == "0" && a.nowpid == "0") {
            Ext.getCmp(this.ID_uppath).disable()
        } else {
            Ext.getCmp(this.ID_uppath).enable()
        }
    },
    goUpPath: function() {
        var c = this;
        if (c.nowfid == "0" && c.nowpid == "0") {
            c.checkUpPathBtn();
            c.showFullPath();
            return
        }
        try {
            if (c.nowpid == "0") {
                c.treeRoot.select();
                c.nowpath = c.dirTree.getNodeById("0")
            } else {
                var b = c.dirTree.getNodeById(c.nowpid);
                c.nowpath = b.getPath();
                c.dirTree.selectPath(c.nowpath);
                b.expand()
            }
            c.storeBar.pid = c.nowpid;
            c.store.load({
                params: {
                    pid: c.nowpid
                }
            });
            Ext.getCmp(c.ID_find_edtWord).setValue("");
            c.storeBar.word = "";
            c.nowfid = c.nowpid;
            c.nowpid = c.dirTree.getNodeById(c.nowfid).attributes.pid
        } catch(a) {
            c.storeBar.pid = c.nowpid;
            c.store.load()
        }
        c.checkUpPathBtn();
        c.showFullPath()
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
    doDelete: function(a) {
        var b = this;
        Ext.Ajax.request({
            url: b.baseUrl + "&task=delete",
            method: "POST",
            params: {
                ids: Ext.encode(a)
            },
            success: function(d) {
                var c = Ext.decode(d.responseText);
                if (c.success === true) {
                    b.store.reload();
                    if (type == "d") {
                        b.refreshTree()
                    }
                } else {
                    CNOA.msg.alert(c.msg,
                        function() {})
                }
            }
        })
    },
    logWin: function(c) {
        var f = this;
        var a = c.id;
        var h = c.type;
        var d = [{
            name: "id"
        },
            {
                name: "log"
            },
            {
                name: "truename"
            },
            {
                name: "postTime"
            }];
        var j = new Ext.data.Store({
            autoLoad: true,
            proxy: new Ext.data.HttpProxy({
                url: f.baseUrl + "&task=getLogList&id=" + a + "&type=" + h
            }),
            reader: new Ext.data.JsonReader({
                totalProperty: "total",
                root: "data",
                fields: d
            }),
            listeners: {}
        });
        var b = new Ext.grid.CheckboxSelectionModel({
            singleSelect: true
        });
        var i = new Ext.grid.ColumnModel([new Ext.grid.RowNumberer(), {
            header: "id",
            dataIndex: "id",
            width: 1,
            sortable: true,
            hidden: true
        },
            {
                header: lang("optType"),
                dataIndex: "log",
                width: 280,
                sortable: false,
                resizable: false,
                menuDisabled: true
            },
            {
                header: lang("operator"),
                dataIndex: "truename",
                width: 80,
                sortable: true,
                resizable: false,
                menuDisabled: true
            },
            {
                header: lang("optTime"),
                dataIndex: "postTime",
                width: 140,
                sortable: true,
                resizable: false,
                menuDisabled: true
            }]);
        var g = new Ext.grid.GridPanel({
            autoScroll: true,
            store: j,
            loadMask: {
                msg: lang("waiting")
            },
            cm: i,
            hideBorders: true,
            border: false,
            stripeRows: true
        });
        var e = new Ext.Window({
            title: lang("historyLog"),
            width: 560,
            layout: "fit",
            modal: true,
            height: 400,
            resizable: false,
            border: false,
            items: [g],
            buttons: [{
                text: lang("close"),
                iconCls: "icon-dialog-cancel",
                handler: function() {
                    e.close()
                }
            }]
        }).show()
    },
    versions: function(c, b) {
        var f = this;
        var a = c.id;
        var d = [{
            name: "id"
        },
            {
                name: "note"
            },
            {
                name: "num"
            },
            {
                name: "truename"
            },
            {
                name: "postTime"
            },
            {
                name: "down"
            },
            {
                name: "view"
            },
            {
                name: "edit"
            }];
        var i = new Ext.data.Store({
            autoLoad: true,
            proxy: new Ext.data.HttpProxy({
                url: f.baseUrl + "&task=getVersionsList&fileid=" + a
            }),
            reader: new Ext.data.JsonReader({
                totalProperty: "total",
                root: "data",
                fields: d
            }),
            listeners: {}
        });
        this.versionsStore = i;
        var h = new Ext.grid.ColumnModel([{
            header: "id",
            dataIndex: "id",
            width: 1,
            sortable: true,
            hidden: true
        },
            {
                header: lang("version"),
                dataIndex: "num",
                width: 80,
                sortable: false,
                resizable: false,
                menuDisabled: true
            },
            {
                header: lang("creater"),
                dataIndex: "truename",
                width: 80,
                sortable: true,
                resizable: false,
                menuDisabled: true
            },
            {
                header: lang("addTime"),
                dataIndex: "postTime",
                width: 140,
                sortable: true,
                resizable: false,
                menuDisabled: true
            },
            {
                header: lang("opt"),
                dataIndex: "id",
                width: 180,
                sortable: true,
                resizable: false,
                menuDisabled: true,
                renderer: function(o, n, k) {
                    var m = k.data;
                    var j = "";
                    if (c.dl) {
                        j += m.down + " "
                    }
                    if (c.vi) {
                        j += m.view
                    }
                    if (c.ed) {
                        j += m.edit
                    }
                    if (b) {
                        j += '&nbsp&nbsp<a href="javascript:void(0)" onclick="CNOA_user_disk_mgrpub.deleteVersions(' + k.id + ')">删除</a>'
                    }
                    return j
                }
            },
            {
                header: lang("remark"),
                dataIndex: "note",
                width: 300,
                sortable: true,
                resizable: true,
                menuDisabled: true,
                editor: new Ext.form.TextField({
                    allowBlank: false
                })
            }]);
        var g = new Ext.grid.EditorGridPanel({
            autoScroll: true,
            store: i,
            loadMask: {
                msg: lang("waiting")
            },
            cm: h,
            hideBorders: true,
            border: false,
            stripeRows: true,
            listeners: {
                afteredit: function(l) {
                    var k = l.field,
                        m = l.record.get("id"),
                        j = l.value;
                    f.updataVersions(m, k, j, i)
                }
            }
        });
        var e = new Ext.Window({
            title: lang("historyVer"),
            width: 600,
            layout: "fit",
            modal: true,
            height: 400,
            resizable: false,
            border: false,
            items: [g],
            buttons: [{
                text: lang("close"),
                iconCls: "icon-dialog-cancel",
                handler: function() {
                    e.close()
                }
            }]
        }).show()
    },
    deleteVersions: function(b) {
        var a = this;
        CNOA.msg.cf(lang("areYouDelete"),
            function(c) {
                if (c == "yes") {
                    Ext.Ajax.request({
                        url: a.baseUrl + "&task=deleteVersions",
                        method: "POST",
                        params: {
                            id: b
                        },
                        success: function(e) {
                            var d = Ext.decode(e.responseText);
                            if (d.success === true) {
                                CNOA.msg.notice2(d.msg);
                                a.versionsStore.reload()
                            } else {
                                CNOA.msg.alert(d.msg,
                                    function() {})
                            }
                        }
                    })
                }
            })
    },
    updataVersions: function(e, d, c, a) {
        var b = this;
        Ext.Ajax.request({
            url: b.baseUrl + "&task=updataVersions",
            params: {
                id: e,
                field: d,
                value: c
            },
            success: function(g) {
                var f = Ext.decode(g.responseText);
                if (f.failure === true) {
                    CNOA.msg.alert(f.msg);
                    a.reload()
                } else {
                    CNOA.msg.notice2(f.msg);
                    a.reload()
                }
            }
        })
    },
    doPermit: function(e, b) {
        var g = this;
        CNOA_user_disk_mgrpubPermitMember = new CNOA_user_disk_mgrpubPermitMemberClass(e);
        CNOA_user_disk_mgrpubPermitDeptment = new CNOA_user_disk_mgrpubPermitDeptmentClass(e);
        var f = CNOA_user_disk_mgrpubPermitMember.mainPanel;
        var d = CNOA_user_disk_mgrpubPermitDeptment.mainPanel;
        var c = new Ext.Panel({
            border: false,
            region: "center",
            layout: "card",
            activeItem: 0,
            items: [f, d],
            tbar: [{
                handler: function(h, i) {
                    c.getLayout().setActiveItem(0)
                }.createDelegate(this),
                iconCls: "icon-roduction",
                enableToggle: true,
                pressed: true,
                allowDepress: false,
                toggleGroup: "DISK_BTN_GROUP_PERMIT",
                cls: "btn-blue4",
                text: lang("userPermitSet")
            },
                {
                    handler: function(h, i) {
                        c.getLayout().setActiveItem(1)
                    }.createDelegate(this),
                    enableToggle: true,
                    allowDepress: false,
                    toggleGroup: "DISK_BTN_GROUP_PERMIT",
                    iconCls: "icon-roduction",
                    cls: "btn-blue4",
                    text: lang("deptPermitSet")
                },
                "->", {
                    xtype: "displayfield",
                    hidden: b == 0 ? true: false,
                    value: lang("inheritParent") + ":"
                },
                {
                    xtype: "checkbox",
                    hidden: b == 0 ? true: false,
                    id: "pubDiskExtendCombobox",
                    listeners: {
                        check: function(i, h) {
                            if (!h) {
                                CNOA.msg.cf(lang("whetherInterrupt"),
                                    function(j) {
                                        if (j == "yes") {
                                            CNOA.msg.cf(lang("jJZDSFFZ"),
                                                function(k) {
                                                    if (k == "yes") {
                                                        g.doExtend(e, 0, 1)
                                                    } else {
                                                        g.doExtend(e, 0, 0)
                                                    }
                                                })
                                        } else {
                                            i.suspendEvents();
                                            i.setValue(true);
                                            i.resumeEvents()
                                        }
                                    })
                            } else {
                                CNOA.msg.cf(lang("sFJCSJML"),
                                    function(j) {
                                        if (j == "yes") {
                                            g.doExtend(e, 1)
                                        } else {
                                            i.suspendEvents();
                                            i.setValue(false);
                                            i.resumeEvents()
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
            items: [c],
            listeners: {
                close: function() {
                    Ext.Ajax.request({
                        url: g.baseUrl + "&task=deleteEmptyPermit",
                        method: "POST",
                        params: {
                            fid: e
                        },
                        success: function(i) {
                            var h = Ext.decode(i.responseText);
                            if (h.success === true) {} else {
                                CNOA.msg.alert(h.msg,
                                    function() {})
                            }
                        }
                    })
                }
            }
        }).show()
    },
    doExtend: function(a, c, b) {
        Ext.Ajax.request({
            url: this.baseUrl + "&task=doExtend",
            method: "POST",
            params: {
                fid: a,
                extend: c,
                copy: b
            },
            success: function(e) {
                var d = Ext.decode(e.responseText);
                if (d.success === true) {
                    CNOA.msg.notice2(d.msg);
                    CNOA_user_disk_mgrpubPermitMember.store.reload();
                    CNOA_user_disk_mgrpubPermitDeptment.store.reload()
                } else {
                    CNOA.msg.alert(d.msg,
                        function() {})
                }
            }
        })
    },
    submit: function(a) {
        var b = this;
        if (b.docFormPanel.getForm().isValid()) {
            b.docFormPanel.getForm().submit({
                url: b.baseUrl + "&task=directorypermit",
                waitMsg: lang("waiting"),
                params: {
                    fid: a.data.fid
                },
                method: "POST",
                success: function(c, d) {
                    CNOA.msg.notice(d.result.msg, lang("netDisk"));
                    b.directoryPermitWindow.close()
                }.createDelegate(this),
                failure: function(c, d) {
                    CNOA.msg.alert(d.result.msg)
                }.createDelegate(this)
            })
        }
    },
    setfid: function(a) {
        var b = this;
        b.fid = a.data.fid
    },
    doShare: function(m) {
        var h = this;
        var i;
        var d = loaded_s = false;
        var f = function(o, n) {
            o.expand();
            o.attributes.checked = n;
            o.eachChild(function(p) {
                p.ui.toggleCheck(n);
                p.attributes.checked = n;
                p.fireEvent("checkchange", p, n)
            })
        };
        var j = new Ext.tree.AsyncTreeNode({
            expanded: true,
            rootVisible: false,
            checked: false,
            draggable: false,
            listeners: {
                load: function() {
                    loaded_s = true
                }
            }
        });
        var b = new Ext.tree.TreeLoader({
            dataUrl: h.baseUrl + "&task=getStructTree",
            preloadChildren: true,
            clearOnLoad: false,
            baseAttrs: {
                uiProvider: Ext.ux.TreeCheckNodeUI
            },
            listeners: {
                load: function(p, o, n) {
                    c.expandAll()
                }
            }
        });
        var c = new Ext.tree.TreePanel({
            animate: false,
            enableDD: false,
            hideBorders: true,
            border: false,
            containerScroll: true,
            checkModel: "multiple",
            loader: b,
            root: j,
            rootVisible: false,
            listeners: {
                checkchange: f
            }
        });
        var k = new Ext.Panel({
            width: 240,
            height: 326,
            layout: "anchor",
            autoScroll: true,
            tbar: [(lang("tdcv") + "&nbsp;&nbsp;")],
            items: [c]
        });
        var a = [];
        var e = new Ext.data.ArrayStore({
            proxy: new Ext.data.MemoryProxy(a),
            fields: [{
                name: "uid",
                mapping: "uid"
            },
                {
                    name: "uname",
                    mapping: "uname"
                }]
        });
        e.load();
        var l = new Ext.ux.form.MultiSelect({
            name: "multiselect",
            width: 240,
            height: 326,
            valueField: "uid",
            displayField: "uname",
            hiddenName: "uid",
            store: e,
            tbar: [(lang("setPeople") + "&nbsp;&nbsp;"), "->", {
                xtype: "btnForPoepleSelector",
                text: lang("select"),
                dataUrl: h.baseUrl + "&task=getAllUserListsInPermitDeptTree",
                iconCls: "icon-order-s-spAdd",
                listeners: {
                    selected: function(p, q) {
                        if (q.length > 0) {
                            e.removeAll();
                            for (var o = 0; o < q.length; o++) {
                                var n = new Ext.data.Record(q[o]);
                                l.store.add(n)
                            }
                        }
                    },
                    onrender: function(n) {}
                }
            },
                "-", {
                    text: lang("clear"),
                    iconCls: "icon-clear",
                    handler: function() {
                        e.removeAll()
                    }
                }],
            ddReorder: true,
            listeners: {
                render: function() {
                    d = true
                }
            }
        });
        var g = new Ext.Panel({
            border: false,
            items: [{
                margins: "5 5 5 5",
                layout: "column",
                autoScroll: true,
                border: false,
                items: [{
                    columnWidth: 0.5,
                    baseCls: "x-plain",
                    bodyStyle: "padding:5px 0 5px 5px",
                    layout: "fit",
                    items: [l]
                },
                    {
                        columnWidth: 0.5,
                        baseCls: "x-plain",
                        bodyStyle: "padding:5px",
                        items: [k]
                    }]
            }],
            tbar: [lang("setPermitViewNotice")]
        });
        i = new Ext.Window({
            title: lang("setShareFolder") + " - " + m.get("name"),
            width: 514,
            height: makeWindowHeight(469),
            modal: true,
            resizable: false,
            items: g,
            layout: "fit",
            buttons: [{
                text: lang("ok"),
                iconCls: "icon-btn-save",
                handler: function() {
                    var n = new Array();
                    var o = l.store.data.items;
                    Ext.each(o,
                        function(p, q) {
                            n.push({
                                uid: p.data.uid,
                                uname: p.data.uname
                            })
                        });
                    Ext.Ajax.request({
                        url: h.baseUrl + "&task=doShare",
                        method: "POST",
                        params: {
                            data_p: Ext.encode(n),
                            data_s: Ext.encode(c.getChecked("deptId")),
                            fid: m.get("fid")
                        },
                        success: function(q) {
                            var p = Ext.decode(q.responseText);
                            if (p.success === true) {
                                CNOA.msg.notice(p.msg, lang("netDisk"));
                                i.close()
                            } else {
                                CNOA.msg.alert(p.msg,
                                    function() {})
                            }
                        }
                    })
                }
            },
                {
                    text: lang("cancel"),
                    iconCls: "icon-dialog-cancel",
                    handler: function() {
                        i.close()
                    }
                }],
            listeners: {
                show: function(n) {
                    n.getEl().mask(lang("waiting"));
                    Ext.Ajax.request({
                        url: h.baseUrl + "&task=getShareData",
                        method: "POST",
                        params: {
                            fid: m.get("fid")
                        },
                        success: function(s) {
                            var o = Ext.decode(s.responseText);
                            if (o.success === true) {
                                var q = o.data;
                                var p = setInterval(function() {
                                        if (d && d) {
                                            clearInterval(p);
                                            try {
                                                for (var t = 0; t < q.data_p.data.length; t++) {
                                                    var r = new Ext.data.Record(q.data_p.data[t]);
                                                    l.store.add(r)
                                                }
                                            } catch(u) {}
                                            try {
                                                for (var t = 0; t < q.data_s.length; t++) {
                                                    if (q.data_s[t] == "0") {
                                                        j.ui.toggleCheck(true)
                                                    } else {
                                                        c.getNodeById("CNOA_main_struct_list_tree_node_" + q.data_s[t]).ui.toggleCheck(true)
                                                    }
                                                }
                                            } catch(u) {}
                                        }
                                    },
                                    200)
                            } else {
                                CNOA.msg.alert(o.msg,
                                    function() {})
                            }
                            n.getEl().unmask()
                        }
                    })
                }
            }
        }).show()
    },
    fileUploadComplete: function() {
        this.store.reload();
        this.treeRoot.reload()
    }
};
CNOA_user_disk_mgrpubPermitMemberClass = CNOA.Class.create();
CNOA_user_disk_mgrpubPermitMemberClass.prototype = {
    init: function(b) {
        var c = this;
        var a = Ext.id();
        this.fid = b;
        this.submitBtnID = Ext.id();
        this.baseUrl = "index.php?app=user&func=disk&action=mgrpub";
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
                    Ext.fly("CNOA_USER_DISK_SELLECTALL_PRINT").on("click",
                        function() {
                            var j = this.dom.checked;
                            Ext.select("input[id^=CNOA_USER_DISK_MGRPUBPERMITMEMBER_PRINT]").each(function() {
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
                            Ext.select("input[id^=CNOA_USER_DISK_MGRPUBPERMITMEMBER_PRINT]").each(function() {
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
                    var h = Ext.getCmp("pubDiskExtendCombobox");
                    h.suspendEvents();
                    h.setValue(g.reader.jsonData.extend);
                    h.resumeEvents();
                    var i = 0;
                    Ext.each(d,
                        function(j) {
                            if (j.json.extend == "1") {
                                i++
                            }
                        });
                    if (g.getCount() > i) {
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
                header: "<input type='checkbox' id='CNOA_USER_DISK_SELLECTALL_MOVE'>文件转移权限",
                dataIndex: "pid",
                width: 100,
                sortable: false,
                menuDisabled: true,
                renderer: this.movefile.createDelegate(this)
            },
            {
                header: "<input type='checkbox' id='CNOA_USER_DISK_SELLECTALL_PRINT'>打印权限",
                dataIndex: "pid",
                width: 100,
                sortable: false,
                menuDisabled: true,
                renderer: this.printfile.createDelegate(this)
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
                header: "<input type='checkbox' id='CNOA_USER_DISK_SELLALL_UPLOAD'>" + lang("permit4Up"),
                dataIndex: "pid",
                width: 80,
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
    printfile: function(i, g, d, h, e, b) {
        var j = d.json,
            f = j.extend == "1" ? "extend='1'": "extend='0'",
            a = j.pr == "1" ? " checked='checked' ": "",
            c = j.extend == "1" ? "disabled='true'": "";
        return "<label><input " + f + " row='CNOA_USER_DISK_MGRPUBPERMITMEMBER_ROW" + h + "' name='mem[" + i + "][pr]' type='checkbox' value='1' id='CNOA_USER_DISK_MGRPUBPERMITMEMBER_PRINT" + i + "' " + a + " " + c + " /></label>"
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
        return "<label><input type='checkbox' onclick='CNOA_user_disk_mgrpubPermitMember.selectall2(" + h + ",this)' name='mem[" + i + "][mgr]' value='1' id='CNOA_USER_DISK_MGRPUBPERMITMEMBER_ALL" + i + "' " + a + " " + c + " /></label>"
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
            return "<a href='javascript:void(0)' onclick='CNOA_user_disk_mgrpubPermitMember.deleteData(" + d + ", " + e + ")'><span class='cnoa_color_red'>" + lang("del") + "</span></a>"
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
                CNOA.msg.notice(d.result.msg, lang("netDisk"));
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
CNOA_user_disk_mgrpubPermitDeptmentClass = CNOA.Class.create();
CNOA_user_disk_mgrpubPermitDeptmentClass.prototype = {
    init: function(b) {
        var c = this;
        var a = Ext.id();
        this.fid = b;
        this.submitBtnID = Ext.id();
        this.baseUrl = "index.php?app=user&func=disk&action=mgrpub";
        this.fields = [{
            name: "sid"
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
                    $.each(["VIEW", "EDIT", "MOVE", "PRINT", "DOWNLOAD", "SHARE", "UPLOAD", "DELETE"],
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
                            Ext.select("input[id^=CNOA_USER_DISK_MGRPUBPERMITDEPT_PRINT]").each(function() {
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
            dataIndex: "sid",
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
                dataIndex: "sid",
                width: 100,
                sortable: false,
                menuDisabled: true,
                renderer: this.viewfile.createDelegate(this)
            },
            {
                header: "<input type='checkbox' id='CNOA_DEPT_DISK_SELLECTALL_EDIT'>" + lang("permit4Edit"),
                dataIndex: "sid",
                width: 100,
                sortable: false,
                menuDisabled: true,
                renderer: this.editfile.createDelegate(this)
            },
            {
                header: "<input type='checkbox' id='CNOA_DEPT_DISK_SELLECTALL_MOVE'>文件转移权限",
                dataIndex: "sid",
                width: 100,
                sortable: false,
                menuDisabled: true,
                renderer: this.movefile.createDelegate(this)
            },
            {
                header: "<input type='checkbox' id='CNOA_DEPT_DISK_SELLECTALL_PRINT'>打印权限",
                dataIndex: "sid",
                width: 100,
                sortable: false,
                menuDisabled: true,
                renderer: this.printfile.createDelegate(this)
            },
            {
                header: "<input type='checkbox' id='CNOA_DEPT_DISK_SELLECTALL_DOWNLOAD'>" + lang("permit4Down"),
                dataIndex: "sid",
                width: 80,
                sortable: false,
                menuDisabled: true,
                renderer: this.downloadfile.createDelegate(this)
            },
            {
                header: "<input type='checkbox' id='CNOA_DEPT_DISK_SELLECTALL_SHARE'>" + lang("permit4Share"),
                dataIndex: "sid",
                width: 80,
                sortable: false,
                menuDisabled: true,
                renderer: this.sharefile.createDelegate(this)
            },
            {
                header: "<input type='checkbox' id='CNOA_DEPT_DISK_SELLECTALL_UPLOAD'>" + lang("permit4Up"),
                dataIndex: "sid",
                width: 80,
                sortable: false,
                menuDisabled: true,
                renderer: this.uploadfile.createDelegate(this)
            },
            {
                header: "<input type='checkbox' id='CNOA_DEPT_DISK_SELLECTALL_DELETE'>" + lang("permit4Del"),
                dataIndex: "sid",
                width: 80,
                sortable: false,
                menuDisabled: true,
                renderer: this.deletefile.createDelegate(this)
            },
            {
                header: "<input type='checkbox' id='CNOA_DEPT_DISK_SELLECTALL_MGR'>" + lang("permit4Mgr"),
                dataIndex: "sid",
                width: 80,
                sortable: false,
                menuDisabled: true,
                renderer: this.selectall.createDelegate(this)
            },
            {
                header: lang("opt"),
                dataIndex: "sid",
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
    printfile: function(i, g, d, h, e, b) {
        var j = d.json,
            f = j.extend == "1" ? "extend='1'": "extend='0'",
            a = j.pr == "1" ? " checked='checked' ": "",
            c = j.extend == "1" ? "disabled='true'": "";
        return "<label><input " + f + " row='CNOA_USER_DISK_MGRPUBPERMITDEPT_ROW" + h + "' name='dept[" + i + "][pr]' type='checkbox' value='1' id='CNOA_USER_DISK_MGRPUBPERMITDEPT_PRINT" + i + "' " + a + " " + c + " /></label>"
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
        return "<label><input " + f + " type='checkbox' onclick='CNOA_user_disk_mgrpubPermitDeptment.selectall2(" + h + ",this)' name='dept[" + i + "][mgr]' value='1' id='CNOA_USER_DISK_MGRPUBPERMITDEPT_ALL" + i + "' " + a + " " + c + "/></label>"
    },
    selectall2: function(c, b) {
        var a = Ext.query("input[row^=CNOA_USER_DISK_MGRPUBPERMITDEPT_ROW" + c + "]");
        Ext.each(a,
            function(d, e) {
                d.checked = b.checked
            })
    },
    operate: function(d, f, a) {
        var b = a.json,
            e = b.fid;
        if (b.extend != "1") {
            return "<a href='javascript:void(0)' onclick='CNOA_user_disk_mgrpubPermitDeptment.deleteData(" + d + "," + e + ")'><span class='cnoa_color_red'>" + lang("del") + "</span></a>"
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
                CNOA.msg.notice(d.result.msg, lang("netDisk"));
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
var sm_user_disk = 1;
var CNOA_main_dbmgr_indexClass, CNOA_main_dbmgr_index;
CNOA_main_dbmgr_indexClass = CNOA.Class.create();
CNOA_main_dbmgr_indexClass.prototype = {
    init: function(b) {
        var a = this;
        this.baseUrl = "index.php?app=main&func=dbmgr&action=index";
        this.from = b;
        this.ID_btn_edit = Ext.id();
        this.ID_btn_delete = Ext.id();
        this.ID_tree_treeRoot = Ext.id();
        this.fields = [{
            name: "id"
        },
            {
                name: "backname"
            },
            {
                name: "date"
            },
            {
                name: "type"
            },
            {
                name: "size"
            },
            {
                name: "file"
            }];
        this.store = new Ext.data.Store({
            proxy: new Ext.data.HttpProxy({
                url: this.baseUrl + "&task=getJsonList",
                disableCaching: true
            }),
            reader: new Ext.data.JsonReader({
                totalProperty: "total",
                root: "data",
                fields: this.fields,
                unread: "unread"
            }),
            listeners: {
                load: function(d, c, e) {}
            }
        });
        this.sm = new Ext.grid.CheckboxSelectionModel({
            singleSelect: false
        });
        this.store.load({
            params: {
                from: a.from
            }
        });
        this.colModel = new Ext.grid.ColumnModel([new Ext.grid.RowNumberer(), {
            header: "id",
            dataIndex: "id",
            hidden: true
        },
            {
                header: "备份文件名",
                dataIndex: "backname",
                width: 250,
                sortable: true,
                menuDisabled: true
            },
            {
                header: "备份时间",
                dataIndex: "date",
                width: 190,
                sortable: false,
                menuDisabled: true
            },
            {
                header: "备份类型",
                dataIndex: "type",
                width: 110,
                sortable: false,
                menuDisabled: true
            },
            {
                header: "大小",
                dataIndex: "size",
                width: 100,
                sortable: false,
                menuDisabled: true
            },
            {
                header: "操作",
                dataIndex: "id",
                width: 150,
                sortable: false,
                menuDisabled: true,
                renderer: this.makeOperator.createDelegate(this)
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
                msg: CNOA.lang.msgLoadMask
            },
            cm: this.colModel,
            hideBorders: true,
            border: false,
            region: "center",
            viewConfig: {},
            listeners: {
                rowdblclick: function(c, d) {},
                rowclick: function(c, g, d, f) {},
                cellclick: function(c, g, d, f) {}
            },
            tbar: new Ext.Toolbar({
                items: [{
                    iconCls: "icon-system-refresh",
                    text: "刷新",
                    handler: function(c, d) {
                        a.store.reload()
                    }.createDelegate(this)
                },
                    "-", {
                        iconCls: "icon-system-refresh",
                        text: "备份数据",
                        handler: function(c, d) {
                            a.showExport()
                        }.createDelegate(this)
                    },
                    "-", {
                        iconCls: "icon-banshou",
                        text: "修复数据库",
                        handler: function(c, d) {
                            a.repairDb(c)
                        }.createDelegate(this)
                    },
                    "-", {
                        iconCls: "icon-system-refresh",
                        text: "导入",
                        handler: function(c, d) {
                            a.showUpload()
                        }.createDelegate(this)
                    },
                    "->", {
                        xtype: "cnoa_helpBtn",
                        helpid: 23
                    }]
            })
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
    makeOperator: function(g, e, a, i, c, f) {
        var d = a.data;
        var b = "<div>";
        b += "<a href='javascript:void(0);' onclick='CNOA_main_dbmgr_index.deleteBackup(" + g + ");'>删除</a>";
        b += "&nbsp;&nbsp;";
        b += "<a href='javascript:void(0);' onclick='CNOA_main_dbmgr_index.restore(" + g + ");' ext:qtip='还原数据库到此时间<br>请谨慎操作'>还原</a>";
        b += "&nbsp;&nbsp;";
        b += d.file;
        b += "</div>";
        return b
    },
    showExport: function() {
        var d = this;
        var a = Ext.id();
        var b = Ext.id();
        var c = new Ext.Window({
            width: 340,
            height: 185,
            title: "备份网站数据",
            resizable: false,
            modal: true,
            layout: "fit",
            items: [{
                xtype: "panel",
                border: false,
                bodyStyle: "padding:10px",
                items: [{
                    xtype: "checkbox",
                    id: a,
                    boxLabel: "备份数据库",
                    checked: true
                },
                    {
                        xtype: "checkbox",
                        id: b,
                        boxLabel: "备份网站其它数据(所有用户上传的文件)"
                    },
                    {
                        xtype: "displayfield",
                        value: "<span class='cnoa_color_gray'>1. 建议在无人员使用本系统时备份(夜间)。</span>"
                    },
                    {
                        xtype: "displayfield",
                        value: "<span class='cnoa_color_gray'>2. 备份网站其它数据会比较占用服务器空间，请定期备份并下载到电脑上。</span>"
                    }]
            }],
            buttons: [{
                text: "开始备份",
                handler: function(f) {
                    var e = Ext.getCmp(a).getValue();
                    var g = Ext.getCmp(b).getValue();
                    if (!e && !g) {
                        CNOA.miniMsg.alertShowAt(f, "请选择要备份的项目")
                    } else {
                        d.exports({
                            sql: e,
                            file: g
                        });
                        c.close()
                    }
                }
            },
                {
                    text: "取消",
                    handler: function() {
                        c.close()
                    }
                }]
        }).show()
    },
    showUpload: function() {
        var c = this;
        var a = new Ext.FormPanel({
            fileUpload: true,
            autoScroll: false,
            waitMsgTarget: true,
            hideBorders: true,
            border: false,
            items: [new Ext.Panel({
                border: false,
                bodyStyle: "padding: 5px;",
                items: [{
                    xtype: "fileuploadfield",
                    name: "file",
                    allowBlank: false,
                    blankText: "请选择要上传的文件",
                    regex: /^[0-9]{14,14}[1-3]{1,1}[0-9]{15,15}\.zip$/,
                    regexText: "文件名不符合格式，请查看下方说明",
                    buttonCfg: {
                        text: "浏览文件"
                    },
                    hideLabel: true,
                    width: 370,
                    listeners: {
                        fileselected: function(e, d) {}
                    }
                },
                    {
                        xtype: "displayfield",
                        value: "<span class='cnoa_color_gray'>注意: <br>1. 所上传的文件必须是以前下载的备份文件，文件格式为zip压缩包<br>&nbsp;&nbsp;&nbsp;&nbsp;文件。<br>2. 文件名格式必须是原始备份的名字：30位数字.zip<br>&nbsp;&nbsp;&nbsp;&nbsp;如:201008081201143874951293445.zip</span>"
                    }]
            })]
        });
        var b = new Ext.Window({
            width: 400,
            height: 185,
            title: "导入备份文件",
            resizable: false,
            modal: true,
            layout: "fit",
            items: [a],
            buttons: [{
                text: "开始上传",
                handler: function(d) {
                    if (a.getForm().isValid()) {
                        a.getForm().submit({
                            url: c.baseUrl + "&task=upBackupFile",
                            waitMsg: CNOA.lang.msgLoadMask,
                            params: {},
                            success: function(e, f) {
                                c.store.reload();
                                b.close()
                            },
                            failure: function(e, f) {
                                CNOA.msg.alert(f.result.msg,
                                    function() {
                                        b.close()
                                    })
                            }
                        })
                    }
                }
            },
                {
                    text: "取消",
                    handler: function() {
                        b.close()
                    }
                }]
        }).show()
    },
    exports: function(a) {
        var b = this;
        Ext.MessageBox.show({
            msg: "正在备份数据...",
            title: "请稍等",
            width: 300,
            wait: true,
            waitConfig: {
                interval: 200
            }
        });
        Ext.Ajax.request({
            url: b.baseUrl + "&task=export",
            params: a,
            method: "POST",
            timeout: 9999999,
            success: function(d) {
                var c = Ext.decode(d.responseText);
                Ext.MessageBox.hide();
                CNOA.msg.alert(c.msg,
                    function() {
                        b.store.reload()
                    })
            }.createDelegate(this),
            failure: function(c) {
                Ext.MessageBox.hide();
                CNOA.msg.alert("操作失败")
            }
        })
    },
    deleteBackup: function(b) {
        var a = this;
        CNOA.msg.cf("确定删除此备份数据吗？",
            function(c) {
                if (c == "yes") {
                    Ext.Ajax.request({
                        url: a.baseUrl + "&task=delete",
                        params: {
                            id: b
                        },
                        method: "POST",
                        success: function(e) {
                            var d = Ext.decode(e.responseText);
                            CNOA.msg.alert(d.msg,
                                function() {
                                    a.store.reload()
                                })
                        }.createDelegate(this),
                        failure: function(e) {
                            var d = Ext.decode(e.responseText);
                            CNOA.msg.alert(d.msg)
                        }
                    })
                }
            })
    },
    download: function(b) {
        var a = document.createElement("iframe");
        a.style.width = "0px";
        a.style.height = "0px";
        a.style.display = "none";
        a.src = this.baseUrl + "&task=download&id=" + b;
        document.body.appendChild(a)
    },
    restore: function(b) {
        var a = this;
        CNOA.msg.cf("确定恢复此数据到现有系统吗？恢复系统后需要强制所有在线用户重新登录一次本系统。<br>请谨慎操作，并确保恢复前你是否需要先做一次备份!!!",
            function(c) {
                if (c == "yes") {
                    Ext.MessageBox.show({
                        msg: "正在恢复数据...",
                        title: "请稍等",
                        width: 300,
                        wait: true,
                        waitConfig: {
                            interval: 200
                        }
                    });
                    Ext.Ajax.request({
                        url: a.baseUrl + "&task=restore",
                        params: {
                            id: b
                        },
                        method: "POST",
                        timeout: 9999999,
                        success: function(e) {
                            Ext.MessageBox.hide();
                            var d = Ext.decode(e.responseText);
                            if (d.failure) {
                                CNOA.msg.alert(d.msg)
                            }
                            if (d.success) {
                                CNOA.msg.alert(d.msg + "<br />现在将强制所有用户退出系统，请重新登录！",
                                    function() {
                                        location.href = "index.php?app=main&func=passport&action=logout"
                                    })
                            }
                        }.createDelegate(this),
                        failure: function(e) {
                            Ext.MessageBox.hide();
                            var d = Ext.decode(e.responseText);
                            CNOA.msg.alert(d.msg)
                        }
                    })
                }
            })
    },
    repairDb: function(a) {
        var b = this;
        CNOA.miniMsg.cfShowAt(a, "确实要修复数据库吗?",
            function() {
                Ext.Ajax.request({
                    url: b.baseUrl + "&task=repairDb",
                    params: {
                        id: id
                    },
                    method: "POST",
                    timeout: 9999999,
                    success: function(d) {
                        Ext.MessageBox.hide();
                        var c = Ext.decode(d.responseText);
                        if (c.failure) {
                            CNOA.msg.alert(c.msg)
                        }
                        if (c.success) {
                            CNOA.msg.alert(c.msg)
                        }
                    }.createDelegate(this),
                    failure: function(d) {
                        Ext.MessageBox.hide();
                        var c = Ext.decode(d.responseText);
                        CNOA.msg.alert(c.msg)
                    }
                })
            })
    }
};
var sm_main_backUpdate = 1;
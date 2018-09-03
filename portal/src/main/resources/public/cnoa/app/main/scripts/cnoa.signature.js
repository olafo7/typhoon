var CNOA_main_signature, CNOA_main_signatureClass;
CNOA_main_signatureClass = CNOA.Class.create();
CNOA_main_signatureClass.prototype = {
    init: function() {
        _this = this;
        this.graphPanel = new CNOA_main_signature_graphClass();
        this.centerPanel = new Ext.ux.VerticalTabPanel({
            region: "center",
            border: false,
            tabWidth: 100,
            activeItem: 0,
            tabPosition: "left",
            deferredRender: false,
            items: [this.graphPanel.mainPanel]
        });
        this.mainPanel = new Ext.Panel({
            border: false,
            layout: "border",
            items: [this.centerPanel]
        })
    }
};
var CNOA_main_signature_graphClass;
CNOA_main_signature_graphClass = CNOA.Class.create();
CNOA_main_signature_graphClass.prototype = {
    init: function() {
        var g = this;
        var f = {
            uid: 0,
            sname: ""
        };
        this.baseUrl = "main/signature";
        this.circleUrl = "index.php?app=main&func=signature&action=index&model=circle";
        this.graphFields = [{
            name: "sid"
        },
            {
                name: "username"
            },
            {
                name: "signature"
            },
            {
                name: "url"
            },
            {
                name: "isUsePwd"
            }];
        this.graphStore = new Ext.data.Store({
            autoLoad: true,
            baseParams: f,
            proxy: new Ext.data.HttpProxy({
                url: this.baseUrl + "/getJsonDatas"
            }),
            reader: new Ext.data.JsonReader({
                totalProperty: "total",
                root: "data",
                fields: this.graphFields
            })
        });
        var b = new Ext.grid.CheckboxSelectionModel({
            singleSelect: false
        });
        var c = function(h) {
            if (h == "") {
                return ""
            }
            checked = h == 1 ? lang("yes") : lang("no");
            return checked
        };
        var e = new Ext.grid.ColumnModel([new Ext.grid.RowNumberer(), b, {
            header: "sid",
            dataIndex: "sid",
            hidden: true
        },
            {
                header: lang("username2"),
                dataIndex: "username",
                width: 120
            },
            {
                header: lang("signatureName"),
                dataIndex: "signature",
                width: 130
            },
            {
                header: lang("isNeedUsePWD"),
                dataIndex: "isUsePwd",
                width: 120,
                renderer: c
            },
            {
                header: lang("signature"),
                dataIndex: "url",
                id: "signature",
                renderer: this.makePic.createDelegate(this)
            }]);
        this.graphGrid = new Ext.grid.PageGridPanel({
            cm: e,
            sm: b,
            store: this.graphStore,
            border: false,
            waitMsgTarget: true,
            autoExpandColumn: "signature",
            tbar: new Ext.Toolbar({
                items: [{
                    text: lang("refresh"),
                    iconCls: "icon-system-refresh",
                    handler: function() {
                        g.graphStore.reload()
                    }
                },
                    {
                        text: lang("makeSignature"),
                        iconCls: "icon-form-signature",
                        cls: "btn-yellow1",
                        handler: function() {
                            g.makeSeal()
                        }
                    },
                    {
                        text: lang("add"),
                        iconCls: "icon-utils-s-add",
                        handler: function() {
                            g.addEditWindow("add", "")
                        }
                    },
                    {
                        text: lang("modify"),
                        iconCls: "icon-utils-s-edit",
                        handler: function(i, k) {
                            var j = g.graphGrid.getSelectionModel().getSelections();
                            if (j.length == 0) {
                                var h = i.getEl().getBox();
                                h = [h.x + 12, h.y + 26];
                                CNOA.miniMsg.alert(lang("mustSelectOneRow"), h)
                            } else {
                                if (j.length > 1) {
                                    var h = i.getEl().getBox();
                                    h = [h.x + 12, h.y + 26];
                                    CNOA.miniMsg.alert(lang("selectItemOnly"), h)
                                } else {
                                    var l = j[j.length - 1].id;
                                    g.addEditWindow("edit", l);
                                    g.loadForm(l)
                                }
                            }
                        }
                    },
                    {
                        text: lang("del"),
                        iconCls: "icon-utils-s-delete",
                        tooltip: lang("del"),
                        handler: function(i, k) {
                            var j = g.graphGrid.getSelectionModel().getSelections();
                            if (j.length == 0) {
                                var h = i.getEl().getBox();
                                h = [h.x + 12, h.y + 26];
                                CNOA.miniMsg.alert(lang("mustSelectOneRow"), h)
                            } else {
                                CNOA.msg.cf(lang("confirmToDelete"),
                                    function(n) {
                                        if (n == "yes") {
                                            if (j) {
                                                var m = new Array();
                                                for (var l = 0; l < j.length; l++) {
                                                    m[l] = j[l].id
                                                }
                                                g.delSignature(m.join(","))
                                            }
                                        }
                                    })
                            }
                        }
                    }]
            })
        });
        var a = Ext.id(),
            d = new Ext.form.Hidden({
                id: a
            });
        search_uname = new Ext.form.TextField({
            width: 110,
            readOnly: true,
            listeners: {
                render: function(h) {
                    h.to = a
                },
                focus: function(h) {
                    people_selector("user", h, false, false)
                }
            }
        }),
            search_sname = new Ext.form.TextField({});
        this.mainPanel = new Ext.Panel({
            title: lang("picSignature"),
            border: false,
            waitMsgTarget: true,
            autoScroll: true,
            layout: "fit",
            labelWidth: 130,
            items: [this.graphGrid],
            tbar: new Ext.Toolbar({
                items: [lang("username2"), search_uname, "&nbsp;&nbsp;" + lang("signatureName"), search_sname, {
                    text: lang("search"),
                    style: "margin-left:5px",
                    handler: function() {
                        f.uid = d.getValue();
                        f.sname = search_sname.getValue();
                        g.graphStore.reload()
                    }
                },
                    {
                        text: lang("clear"),
                        style: "margin-left:5px",
                        handler: function() {
                            d.setValue("");
                            search_sname.setValue("");
                            search_uname.setValue("");
                            f.uid = 0;
                            f.sname = "";
                            g.graphStore.reload()
                        }
                    }]
            })
        })
    },
    makePic: function(e, c, a, f, b, d) {
        return "<img src='" + e + "' onload='resizeImage(this, 90, 70)' />"
    },
    addEditWindow: function(a, e) {
        var d = this;
        var c = a == "edit" ? lang("editSignature") : lang("addSignature");
        this.form = new Ext.form.FormPanel({
            labelAlign: "right",
            labelWidth: 100,
            border: false,
            fileUpload: true,
            autoHeight: true,
            bodyStyle: "padding: 10px;",
            defaults: {
                style: "margin-bottom: 10px;",
                width: 220
            },
            items: [{
                xtype: "userselectorfield",
                fieldLabel: lang("username2"),
                allowBlank: false,
                name: "uid",
                multiSelect: false
            },
                {
                    xtype: "textfield",
                    fieldLabel: lang("signatureName"),
                    allowBlank: false,
                    name: "signature",
                    style: "margin-bottom: 5px;"
                },
                {
                    xtype: "fileuploadfield",
                    name: "image",
                    fieldLabel: lang("signature"),
                    buttonCfg: {
                        text: lang("uploadPic")
                    }
                },
                {
                    xtype: "radiogroup",
                    fieldLabel: lang("isNeedUsePWD"),
                    items: [{
                        boxLabel: lang("yes"),
                        name: "isUsePwd",
                        inputValue: 1,
                        checked: true
                    },
                        {
                            boxLabel: lang("no"),
                            name: "isUsePwd",
                            inputValue: 0
                        }]
                }]
        });
        this.win = new Ext.Window({
            title: c,
            modal: true,
            layout: "fit",
            width: 380,
            resizable: false,
            items: [this.form],
            buttons: [{
                text: lang("save"),
                iconCls: "icon-order-s-accept",
                handler: function() {
                    b()
                }
            },
                {
                    text: lang("close"),
                    iconCls: "icon-dialog-cancel",
                    handler: function() {
                        d.win.close()
                    }
                }]
        }).show();
        var b = function() {
            var g = d.form.getForm();
            if (g.isValid()) {
                g.submit({
                    url: d.baseUrl + "&task=addEditSignature",
                    waitTitle: lang("notice"),
                    method: "POST",
                    params: {
                        id: e
                    },
                    waitMsg: lang("waiting"),
                    success: function(f, h) {
                        d.win.close();
                        d.graphStore.reload()
                    },
                    failure: function(f, h) {
                        CNOA.msg.alert(h.result.msg)
                    }
                })
            }
        }
    },
    makeSeal: function() {
        var d = this;
        var h = new Ext.Panel({
            width: 400,
            height: 500,
            region: "west",
            border: false,
            html: '<img id="sealImg" src="' + this.circleUrl + '&task=sealImg" style="margin: 100px;" />',
            listeners: {
                afterlayout: function() {
                    var l = $("img:#sealImg");
                    var n = l.parent();
                    var m = (n.width() - 42 * 3.78) / 2;
                    var k = (n.height() - 42 * 3.78) / 2;
                    l.css({
                        "margin-left": m,
                        "margin-top": k
                    })
                }
            }
        });
        var a = new Ext.data.JsonStore({
            url: this.circleUrl + "&task=loadFont",
            root: "data",
            fields: [{
                name: "font"
            },
                {
                    name: "url"
                }],
            listeners: {
                load: function(m) {
                    var n = m.getRange()[0].data;
                    var k = n.font;
                    var l = n.url;
                    Ext.getCmp("upCombo").setValue(k);
                    Ext.getCmp("upFont").setValue(l);
                    Ext.getCmp("croCombo").setValue(k);
                    Ext.getCmp("croFont").setValue(l)
                }
            }
        });
        a.load();
        var b = new Ext.data.JsonStore({
            url: this.circleUrl + "&task=loadImg",
            root: "data",
            fields: [{
                name: "url"
            },
                {
                    name: "name"
                }],
            listeners: {
                load: function(m) {
                    var n = m.getRange()[0].data;
                    var l = n.url;
                    var k = n.name;
                    Ext.getCmp("inImg").setValue(k + ".png")
                }
            }
        });
        b.load();
        var e = new Ext.XTemplate('<tpl for=".">', '<div class="thumb-wrap" id="{name}">', '<div class="thumb"><img src="{url}" title="{name}"></div>', '<span class="x-editable">{shortName}</span></div>', "</tpl>", '<div class="x-clear"></div>');
        var i = new Ext.TabPanel({
            activeTab: 0,
            height: 180,
            padding: 10,
            defaults: {
                layout: "form",
                defaultType: "textfield",
                labelAlign: "right",
                labelWidth: 60
            },
            items: [{
                title: lang("sxw"),
                autoScroll: true,
                defaults: {
                    width: 230
                },
                items: [{
                    fieldLabel: lang("content"),
                    name: "upText",
                    value: lang("sealTest"),
                    listeners: {
                        change: function() {
                            j()
                        }
                    }
                },
                    {
                        xtype: "combo",
                        id: "upCombo",
                        editable: false,
                        fieldLabel: lang("fontName"),
                        mode: "local",
                        triggerAction: "all",
                        store: a,
                        displayField: "font",
                        valueField: "url",
                        listeners: {
                            select: function(k) {
                                Ext.getCmp("upFont").setValue(k.getValue());
                                j()
                            }
                        }
                    },
                    {
                        xtype: "hidden",
                        id: "upFont",
                        name: "upFont"
                    },
                    {
                        xtype: "panel",
                        layout: "table",
                        columns: 5,
                        fieldLabel: lang("fontSize"),
                        items: [{
                            xtype: "spinnerfield",
                            name: "upFontHeight",
                            width: 65,
                            minValue: 0,
                            allowDecimals: true,
                            decimalPrecision: 2,
                            incrementValue: 0.1,
                            value: "4.2"
                        },
                            {
                                xtype: "displayfield",
                                value: lang("millimeter"),
                                style: "margin-left: 5px; margin-right: 10px;"
                            },
                            {
                                xtype: "displayfield",
                                value: lang("ingression") + ":"
                            },
                            {
                                xtype: "spinnerfield",
                                name: "inMove",
                                width: 65,
                                minValue: 0,
                                allowDecimals: true,
                                decimalPrecision: 2,
                                incrementValue: 0.1,
                                value: "1"
                            },
                            {
                                xtype: "displayfield",
                                value: lang("millimeter"),
                                style: "margin-left: 5px;"
                            }]
                    },
                    {
                        xtype: "panel",
                        layout: "table",
                        columns: 2,
                        fieldLabel: lang("zyjd"),
                        items: [{
                            xtype: "spinnerfield",
                            name: "uAngle",
                            width: 65,
                            minValue: 0,
                            allowDecimals: true,
                            decimalPrecision: 2,
                            incrementValue: 1,
                            value: "210"
                        },
                            {
                                xtype: "displayfield",
                                value: lang("angle"),
                                style: "margin-left: 5px; margin-right: 10px;"
                            }]
                    }]
            },
                {
                    title: lang("hxw"),
                    autoScroll: true,
                    defaults: {
                        width: 230
                    },
                    items: [{
                        fieldLabel: lang("content"),
                        name: "croText",
                        value: lang("demosChapter"),
                        listeners: {
                            change: function() {
                                j()
                            }
                        }
                    },
                        {
                            xtype: "combo",
                            id: "croCombo",
                            editable: false,
                            fieldLabel: lang("fontName"),
                            mode: "local",
                            triggerAction: "all",
                            store: a,
                            displayField: "font",
                            valueField: "url",
                            listeners: {
                                select: function(k) {
                                    Ext.getCmp("croFont").setValue(k.getValue());
                                    j()
                                }
                            }
                        },
                        {
                            xtype: "hidden",
                            id: "croFont",
                            name: "croFont"
                        },
                        {
                            xtype: "panel",
                            layout: "table",
                            columns: 5,
                            fieldLabel: lang("fontSize"),
                            items: [{
                                xtype: "spinnerfield",
                                name: "croFontHeight",
                                width: 65,
                                minValue: 0,
                                allowDecimals: true,
                                decimalPrecision: 2,
                                incrementValue: 0.1,
                                value: "4.2"
                            },
                                {
                                    xtype: "displayfield",
                                    value: lang("millimeter"),
                                    style: "margin-left: 5px; margin-right: 10px;"
                                },
                                {
                                    xtype: "displayfield",
                                    value: (lang("moveDown") + ":")
                                },
                                {
                                    xtype: "spinnerfield",
                                    name: "downMove",
                                    width: 65,
                                    minValue: 0,
                                    allowDecimals: true,
                                    decimalPrecision: 2,
                                    incrementValue: 0.1,
                                    value: "3"
                                },
                                {
                                    xtype: "displayfield",
                                    value: lang("millimeter"),
                                    style: "margin-left: 5px;"
                                }]
                        },
                        {
                            xtype: "panel",
                            layout: "table",
                            columns: 5,
                            fieldLabel: lang("fontMargin"),
                            items: [{
                                xtype: "spinnerfield",
                                name: "spacing",
                                width: 65,
                                minValue: 0,
                                allowDecimals: true,
                                decimalPrecision: 2,
                                incrementValue: 0.1,
                                value: "1"
                            },
                                {
                                    xtype: "displayfield",
                                    value: lang("millimeter"),
                                    style: "margin-left: 5px; margin-right: 10px;"
                                }]
                        }]
                },
                {
                    title: lang("groupPhoto"),
                    autoScroll: true,
                    cls: "img-chooser-view",
                    items: [new Ext.DataView({
                        singleSelect: true,
                        overClass: "x-view-over",
                        itemSelector: "div.thumb-wrap",
                        store: b,
                        tpl: e,
                        autoScroll: true,
                        height: 80,
                        listeners: {
                            click: function(m, k, l, n) {
                                Ext.getCmp("inImg").setValue($(l).attr("id") + ".png");
                                j()
                            }
                        }
                    }), {
                        xtype: "hidden",
                        id: "inImg",
                        name: "inImg"
                    },
                        {
                            xtype: "panel",
                            layout: "table",
                            columns: 5,
                            fieldLabel: lang("inImgWidth"),
                            style: "margin-top: 8px;",
                            items: [{
                                xtype: "spinnerfield",
                                name: "inImgWidth",
                                width: 65,
                                minValue: 0,
                                allowDecimals: true,
                                decimalPrecision: 2,
                                incrementValue: 0.1,
                                value: "14"
                            },
                                {
                                    xtype: "displayfield",
                                    value: lang("millimeter"),
                                    style: "margin-left: 5px; margin-right: 10px;"
                                },
                                {
                                    xtype: "displayfield",
                                    value: (lang("moveDown") + ":")
                                },
                                {
                                    xtype: "spinnerfield",
                                    name: "inImgDown",
                                    width: 65,
                                    allowDecimals: true,
                                    decimalPrecision: 2,
                                    incrementValue: 0.1,
                                    value: "0"
                                },
                                {
                                    xtype: "displayfield",
                                    value: lang("millimeter"),
                                    style: "margin-left: 5px;"
                                }]
                        }]
                }],
            listeners: {
                afterrender: function(k) {
                    k.setActiveTab(1);
                    k.setActiveTab(2);
                    k.setActiveTab(0)
                }
            }
        });
        var f = new Ext.form.FormPanel({
            height: 550,
            region: "center",
            padding: 10,
            frame: true,
            border: false,
            items: [{
                xtype: "fieldset",
                title: lang("sealGG"),
                defaults: {
                    hideLabel: true
                },
                items: [{
                    xtype: "combo",
                    style: {
                        marginLeft: "25px"
                    },
                    width: 300,
                    editable: false,
                    typeAhead: true,
                    triggerAction: "all",
                    mode: "local",
                    value: 1,
                    store: new Ext.data.ArrayStore({
                        fields: ["rid", "rule"],
                        data: [[1, "42" + lang("millimeter") + " × 42" + lang("millimeter")], [2, "45" + lang("millimeter") + " × 45" + lang("millimeter")], [3, lang("cusGG")]]
                    }),
                    displayField: "rule",
                    valueField: "rid",
                    listeners: {
                        change: function(m, l, k) {
                            if (l == 3) {
                                m.nextSibling().setReadOnly(false)
                            } else {
                                m.nextSibling().setReadOnly(true)
                            }
                        },
                        select: function(n, k, l) {
                            var m = k.json[0];
                            if (m == 1) {
                                Ext.getCmp("width").setValue(42);
                                Ext.getCmp("height").setValue(42)
                            } else {
                                if (m == 2) {
                                    Ext.getCmp("width").setValue(45);
                                    Ext.getCmp("height").setValue(45)
                                }
                            }
                            j()
                        }
                    }
                },
                    {
                        xtype: "compositefield",
                        defaults: {
                            width: 40,
                            readOnly: true
                        },
                        style: {
                            marginLeft: "25px"
                        },
                        items: [{
                            xtype: "displayfield",
                            value: lang("width") + "："
                        },
                            {
                                xtype: "spinnerfield",
                                name: "width",
                                id: "width",
                                width: 65,
                                minValue: 1,
                                allowDecimals: true,
                                decimalPrecision: 2,
                                incrementValue: 0.1,
                                value: 42
                            },
                            {
                                xtype: "displayfield",
                                value: lang("millimeter")
                            },
                            {
                                xtype: "displayfield",
                                value: lang("height") + "："
                            },
                            {
                                xtype: "spinnerfield",
                                name: "height",
                                id: "height",
                                width: 65,
                                minValue: 1,
                                allowDecimals: true,
                                decimalPrecision: 2,
                                incrementValue: 0.1,
                                value: 42
                            },
                            {
                                xtype: "displayfield",
                                value: lang("millimeter")
                            }]
                    },
                    {
                        xtype: "compositefield",
                        style: {
                            marginLeft: "5px"
                        },
                        items: [{
                            xtype: "displayfield",
                            value: lang("borderWidth") + "：",
                            widht: 60
                        },
                            {
                                xtype: "spinnerfield",
                                name: "rim",
                                width: 65,
                                minValue: 0,
                                allowDecimals: true,
                                decimalPrecision: 2,
                                incrementValue: 0.1,
                                value: 1.4
                            },
                            {
                                xtype: "displayfield",
                                value: lang("millimeter")
                            }]
                    }]
            },
                i, {
                    xtype: "fieldset",
                    title: lang("signatureInfo"),
                    items: [{
                        xtype: "userselectorfield",
                        fieldLabel: lang("username2"),
                        allowBlank: false,
                        name: "userId",
                        multiSelect: false
                    },
                        {
                            xtype: "textfield",
                            fieldLabel: lang("signatureName"),
                            name: "sealName"
                        },
                        {
                            xtype: "radiogroup",
                            fieldLabel: lang("isNeedUsePWD"),
                            items: [{
                                boxLabel: lang("yes"),
                                name: "isUsePwd",
                                inputValue: 1,
                                checked: true
                            },
                                {
                                    boxLabel: lang("no"),
                                    name: "isUsePwd",
                                    inputValue: 0
                                }],
                            style: "margin-bottom: 5px;"
                        }]
                }],
            buttons: [{
                text: lang("preview"),
                handler: function() {
                    j()
                }
            }]
        });
        var c = new Ext.Window({
            title: lang("makeSignature"),
            width: 800,
            height: makeWindowHeight(600),
            modal: true,
            resizable: false,
            layout: "border",
            plain: true,
            items: [h, f],
            buttons: [{
                text: lang("save"),
                iconCls: "icon-order-s-accept",
                handler: function() {
                    var k = f.getForm();
                    if (k.isValid()) {
                        k.submit({
                            url: d.circleUrl + "&task=saveCircle",
                            waitTitle: lang("notice"),
                            method: "POST",
                            waitMsg: lang("waiting"),
                            success: function(l, m) {
                                c.close();
                                d.graphStore.reload()
                            },
                            failure: function(l, m) {
                                CNOA.msg.alert(m.result.msg)
                            }
                        })
                    }
                }
            },
                {
                    text: lang("close"),
                    iconCls: "icon-dialog-cancel",
                    handler: function() {
                        c.close()
                    }
                }]
        }).show();
        var j = function() {
            var k = f.getForm();
            if (k.isValid()) {
                k.submit({
                    url: d.circleUrl + "&task=preView",
                    method: "POST",
                    success: function(m, o) {
                        var l = $("img:#sealImg");
                        var n = o.result.msg;
                        l.attr("src", d.circleUrl + "&task=sealImg&data=" + n);
                        setTimeout(function() {
                                g()
                            },
                            500)
                    },
                    failure: function(l, m) {}
                })
            }
        };
        var g = function() {
            var l = $("img:#sealImg");
            var n = l.parent();
            var m = (n.width() - l.width()) / 2;
            var k = (n.height() - l.height()) / 2;
            l.css({
                "margin-left": m,
                "margin-top": k
            })
        }
    },
    loadForm: function(b) {
        var a = this;
        this.form.getForm().load({
            url: a.baseUrl + "&task=editLoadFormDataInfo",
            method: "POST",
            params: {
                id: b
            },
            waitMsg: lang("waiting")
        })
    },
    delSignature: function(a) {
        var b = this;
        Ext.Ajax.request({
            url: this.baseUrl + "&task=delSignature",
            method: "POST",
            params: {
                ids: a
            },
            success: function(d) {
                var c = Ext.decode(d.responseText);
                if (c.success === true) {
                    CNOA.msg.alert(c.msg);
                    b.graphStore.reload()
                } else {
                    CNOA.msg.alert(c.msg)
                }
            }
        })
    }
};
var sm_main_signature = 1;
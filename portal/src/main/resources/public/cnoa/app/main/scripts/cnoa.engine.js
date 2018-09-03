var CNOA_main_engine, CNOA_main_engine_Class;
CNOA_main_engine_Class = CNOA.Class.create();
CNOA_main_engine_Class.prototype = {
    init: function() {
        var f = this;
        this.baseUrl = "index.php?app=main&func=system&action=engine";
        var c = [{
            name: "code"
        },
            {
                name: "module"
            },
            {
                name: "descript"
            },
            {
                name: "flowname"
            },
            {
                name: "sMapVal"
            },
            {
                name: "stepsMap"
            },
            {
                name: "fMapVal"
            },
            {
                name: "fieldsMap"
            },
            {
                name: "open"
            }];
        this.engineStore = new Ext.data.Store({
            autoLoad: true,
            proxy: new Ext.data.HttpProxy({
                url: this.baseUrl + "&task=getEngineList"
            }),
            reader: new Ext.data.JsonReader({
                totalProperty: "total",
                root: "data",
                fields: c
            })
        });
        var a = new Ext.grid.CheckboxSelectionModel({
            singleSelect: true
        });
        var d = function(h, g) {
            g.attr = 'style="white-space:pre-wrap;"';
            return h
        };
        var b = function(i, g, h, j) {
            return '<a class="gridview" onclick="CNOA_main_engine.setEngineStatus(\'' + h.get("code") + "'," + i + ',this)">' + (i ? lang("yes") : lang("no")) + "</a>"
        };
        var e = function(i, g, h, j) {
            return '<a class="gridview" onclick="CNOA_main_engine.releaseFlow(\'' + h.get("code") + "')\">释放流程</a>"
        };
        this.engineModel = new Ext.grid.ColumnModel([new Ext.grid.RowNumberer(), a, {
            header: "code",
            dataIndex: "code",
            hidden: true
        },
            {
                header: lang("optModule"),
                dataIndex: "module",
                width: 120
            },
            {
                header: lang("description"),
                dataIndex: "descript",
                width: 300
            },
            {
                header: lang("flowName"),
                dataIndex: "flowname",
                width: 150
            },
            {
                header: "sMapVal",
                dataIndex: "sMapVal",
                hidden: true
            },
            {
                header: lang("stepMapping"),
                dataIndex: "stepsMap",
                width: 300,
                renderer: d
            },
            {
                header: "fMapVal",
                dataIndex: "fMapVal",
                hidden: true
            },
            {
                header: lang("dataMapping"),
                dataIndex: "fieldsMap",
                width: 300,
                renderer: d
            },
            {
                header: lang("enable"),
                dataIndex: "open",
                width: 50,
                renderer: b
            },
            {
                header: lang("opt"),
                dataIndex: "",
                renderer: e
            }]);
        this.mainPanel = new Ext.grid.GridPanel({
            title: lang("businessEngine"),
            hideBorders: true,
            border: false,
            autoScroll: false,
            store: this.engineStore,
            cm: this.engineModel,
            sm: a,
            loadMask: {
                msg: lang("waiting")
            },
            tbar: new Ext.Toolbar({
                items: [{
                    iconCls: "icon-system-refresh",
                    tooltip: lang("refresh"),
                    text: lang("refresh"),
                    handler: function(g, h) {
                        f.engineStore.reload()
                    }
                },
                    {
                        xtype: "button",
                        iconCls: "icon-sys-settings",
                        cls: "btn-blue4",
                        text: lang("set2"),
                        handler: function(g, h) {
                            f.bindflow(f.mainPanel.getSelectionModel().lastActive)
                        }
                    }]
            }),
            listeners: {
                rowdblclick: function(g, h) {
                    f.bindflow(h)
                }
            }
        })
    },
    setEngineStatus: function(b, c, a) {
        var d = this;
        $.ajax({
            url: d.baseUrl + "&task=openEngine",
            type: "POST",
            data: "code=" + b + "&status=" + c,
            success: function(e) {
                e = Ext.decode(e);
                if (e.success) {
                    a.innerText = e.msg;
                    a.onclick = function() {
                        CNOA_main_engine.setEngineStatus(b, c ? 0 : 1, this)
                    }
                }
            }
        })
    },
    bindflow: function(j) {
        var m = this;
        var l = this.mainPanel.getStore().getAt(j).data;
        var t = new Ext.data.Store({
            proxy: new Ext.data.HttpProxy({
                url: m.baseUrl + "&task=getFlows&code=" + l.code
            }),
            reader: new Ext.data.JsonReader({
                root: "data",
                fields: [{
                    name: "flowId"
                },
                    {
                        name: "name"
                    }]
            })
        });
        t.load();
        var a = new Ext.data.ArrayStore({
            fields: ["stepId", "stepName"]
        });
        var q = new Ext.data.ArrayStore({
            fields: ["id", "name"]
        });
        var d = Ext.id();
        var e = Ext.id();
        var g = Ext.id();
        var s = Ext.id();
        var n = Ext.id();
        var c = Ext.id();
        var i = Ext.id();
        var b = Ext.id();
        var h = Ext.id();
        var f = new Ext.form.FormPanel({
            bodyStyle: "padding:10px",
            autoScroll: true,
            labelAlign: "right",
            items: [{
                xtype: "combo",
                id: d,
                name: "flowId",
                fieldLabel: lang("flow"),
                store: t,
                displayField: "name",
                valueField: "flowId",
                mode: "local",
                triggerAction: "all",
                editable: false,
                listeners: {
                    select: function(u) {
                        r(u.getValue(), true)
                    }
                }
            },
                {
                    xtype: "compositefield",
                    fieldLabel: lang("stepMapping"),
                    items: [{
                        xtype: "multiselect",
                        id: e,
                        name: "engine_steps",
                        height: 150,
                        width: 120,
                        valueField: "sid",
                        displayField: "step",
                        mode: "local",
                        typeAhead: true,
                        triggerAction: "all",
                        store: new Ext.data.ArrayStore({
                            fields: ["sid", "step"]
                        }),
                        tbar: [{
                            text: lang("optModule")
                        }]
                    },
                        {
                            xtype: "multiselect",
                            id: s,
                            height: 150,
                            width: 120,
                            valueField: "stepId",
                            displayField: "stepName",
                            mode: "local",
                            typeAhead: true,
                            triggerAction: "all",
                            store: a,
                            tbar: [{
                                text: lang("flowStep")
                            }]
                        },
                        {
                            xtype: "button",
                            text: lang("add"),
                            style: {
                                marginTop: "125px"
                            },
                            handler: function() {
                                var x = Ext.getCmp(e).getRawValue();
                                var v = Ext.getCmp(s).getRawValue();
                                if (x.length == 0 || v.length == 0) {
                                    CNOA.msg.alert(lang("noStepSelect"))
                                } else {
                                    if (x.length > 1 || v.length > 1) {
                                        CNOA.msg.alert(lang("noStepMore"))
                                    } else {
                                        var w = true;
                                        $("input[name=stepMap]").each(function(A, C) {
                                            var B = $(C).val().split("|");
                                            if (x == B[1] || v == B[0]) {
                                                w = false;
                                                CNOA.msg.alert(lang("noBindStepMore"))
                                            }
                                        });
                                        if (w) {
                                            var z = $("#" + e + " dl.ux-mselect-selected").text();
                                            var y = $("#" + s + " dl.ux-mselect-selected").text();
                                            var u = o("step", y + "=>" + z, v + "|" + x);
                                            $("#" + c).append(u)
                                        }
                                    }
                                }
                            }
                        }]
                },
                {
                    xtype: "displayfield",
                    fieldLabel: lang("stepMappingGX"),
                    id: c,
                    style: {
                        marginBottom: "10px"
                    }
                },
                {
                    xtype: "compositefield",
                    fieldLabel: lang("dataMapping"),
                    items: [{
                        xtype: "multiselect",
                        id: g,
                        name: "engine_fields",
                        height: 180,
                        width: 120,
                        valueField: "fid",
                        displayField: "field",
                        mode: "local",
                        typeAhead: true,
                        triggerAction: "all",
                        store: new Ext.data.ArrayStore({
                            fields: ["fid", "field"]
                        }),
                        tbar: [{
                            text: lang("optModule")
                        }]
                    },
                        {
                            xtype: "multiselect",
                            id: n,
                            height: 180,
                            width: 120,
                            valueField: "id",
                            displayField: "name",
                            mode: "local",
                            typeAhead: true,
                            triggerAction: "all",
                            store: q,
                            tbar: [{
                                text: lang("flowField")
                            }]
                        },
                        {
                            xtype: "button",
                            text: lang("add"),
                            style: {
                                marginTop: "155px"
                            },
                            handler: function() {
                                var v = Ext.getCmp(g).getRawValue();
                                var u = Ext.getCmp(n).getRawValue();
                                if (v.length == 0 || u.length == 0) {
                                    CNOA.msg.alert(lang("noFieldSelect"))
                                } else {
                                    if (v.length > 1 || u.length > 1) {
                                        CNOA.msg.alert(lang("noFieldMore"))
                                    } else {
                                        var x = true;
                                        $("input[name=fieldMap]").each(function(A, C) {
                                            var B = $(C).val().split("|");
                                            if (v == B[1] || u == B[0]) {
                                                x = false;
                                                CNOA.msg.alert(lang("noFieldBindDC"))
                                            }
                                        });
                                        if (x) {
                                            var z = $("#" + g + " dl.ux-mselect-selected").text();
                                            var y = $("#" + n + " dl.ux-mselect-selected").text();
                                            var w = o("field", y + "=>" + z, u + "|" + v);
                                            $("#" + i).append(w)
                                        }
                                    }
                                }
                            }
                        }]
                },
                {
                    xtype: "displayfield",
                    fieldLabel: lang("dataMappingGX"),
                    id: i,
                    style: {
                        marginBottom: "10px"
                    }
                },
                {
                    xtype: "compositefield",
                    fieldLabel: lang("shyj"),
                    items: [{
                        xtype: "displayfield",
                        value: lang("pass") + ":"
                    },
                        {
                            xtype: "textfield",
                            id: b,
                            name: "pass",
                            width: 70,
                            value: lang("agree"),
                            allowBlank: false
                        },
                        {
                            xtype: "displayfield",
                            value: lang("unPass") + ":"
                        },
                        {
                            xtype: "textfield",
                            id: h,
                            name: "unpass",
                            width: 70,
                            value: lang("disagree"),
                            allowBlank: false
                        }]
                }]
        });
        f.getForm().load({
            url: this.baseUrl + "&task=loadData",
            params: {
                code: l.code
            },
            method: "POST",
            waitMsg: lang("waiting"),
            success: function(u, x) {
                r(Ext.getCmp(d).getValue(), false);
                var z = x.result.data;
                var E = [],
                    v = [];
                var I = $.parseJSON(z.engineSteps);
                for (var J in I) {
                    E.push([J, I[J]])
                }
                var B = $.parseJSON(z.engineFields);
                for (var J in B) {
                    v.push([J, B[J]])
                }
                Ext.getCmp(e).store.loadData(E);
                Ext.getCmp(g).store.loadData(v);
                var D = l.sMapVal.split(",");
                var H = l.fMapVal.split(",");
                var G = l.stepsMap.split(",");
                var F = l.fieldsMap.split(",");
                var K = [],
                    w = [];
                for (var y = 0,
                         C = G.length; y < C; y++) {
                    if (G[y] == "") {
                        continue
                    }
                    var A = o("step", G[y], D[y]);
                    K.push(A)
                }
                $("#" + c).append(K.join(""));
                for (var y = 0,
                         C = F.length; y < C; y++) {
                    if (F[y] == "") {
                        continue
                    }
                    var A = o("field", F[y], H[y]);
                    w.push(A)
                }
                $("#" + i).append(w.join(""))
            }
        });
        var p = new Ext.Window({
            title: lang("flowBind"),
            width: 550,
            height: makeWindowHeight(550),
            layout: "fit",
            hideBorders: true,
            items: f,
            modal: true,
            buttons: [{
                text: lang("ok"),
                handler: function() {
                    k()
                }
            },
                {
                    text: lang("cancel"),
                    handler: function() {
                        p.close()
                    }
                }]
        }).show();
        var r = function(u, v) {
            $.ajax({
                type: "POST",
                url: m.baseUrl + "&task=getFlowInfo",
                data: "flowId=" + u,
                success: function(B) {
                    var B = $.parseJSON(B);
                    var y = [],
                        x = [],
                        A = null,
                        z = 0,
                        w;
                    for (w = B.steps.length; z < w; ++z) {
                        A = B.steps[z];
                        y[z] = [A.stepId, A.stepName]
                    }
                    for (z = 0, w = B.fields.length; z < w; ++z) {
                        A = B.fields[z];
                        x[z] = [A.id, A.name]
                    }
                    if (v) {
                        $("#" + c).html("");
                        $("#" + i).html("")
                    }
                    a.loadData(y);
                    q.loadData(x)
                }
            })
        };
        var o = function(v, x, w) {
            var u = ["<span>", x, '<a style="margin-left:5px;" onclick="$(this).parent().remove();">' + lang("del") + '</a><input type="hidden" name="', v, 'Map" value="', w, '"/><br/></span>'];
            return u.join("")
        };
        var k = function() {
            var w = [],
                v = [];
            $("input[name$=Map]").each(function(y, z) {
                var x = $(z).attr("name").slice(0, -3);
                switch (x) {
                    case "step":
                        w.push($(z).val());
                        break;
                    case "field":
                        v.push($(z).val());
                        break
                }
            });
            var u = Ext.getCmp(b).getValue() + "|" + Ext.getCmp(h).getValue();
            $.ajax({
                type: "POST",
                url: m.baseUrl + "&task=saveMap",
                data: "code=" + l.code + "&flowId=" + Ext.getCmp(d).getValue() + "&check=" + u + "&stepsMap=" + w.join(",") + "&fieldsMap=" + v.join(","),
                success: function(x) {
                    if (x.slice(0, 4) == "true") {
                        CNOA.msg.notice2(lang("bindSucess"));
                        p.close();
                        m.engineStore.reload()
                    } else {
                        CNOA.msg.alert(x)
                    }
                }
            })
        }
    },
    releaseFlow: function(a) {
        var b = this;
        CNOA.msg.cf("是否要释放绑定的流程?",
            function(c) {
                if (c == "yes") {
                    Ext.Ajax.request({
                        url: b.baseUrl + "&task=releaseFlow",
                        params: {
                            code: a
                        },
                        success: function(d) {
                            var d = Ext.decode(d.responseText);
                            if (d.success) {
                                b.engineStore.reload();
                                CNOA.msg.notice2(d.msg)
                            } else {
                                CNOA.msg.alert(d.msg)
                            }
                        }
                    })
                }
            })
    }
};
var sm_main_engine = 1;
var CNOA_wf_advSearchWinClass, CNOA_wf_advSearchWin;
CNOA_wf_advSearchWinClass = CNOA.Class.create();
CNOA_wf_advSearchWinClass.prototype = {
    init: function(b) {
        var a = this;
        this.dataStore = b.store;
        this.baseUrl = b.baseUrl;
        this.flowId = 0;
        this.flowNameComp = new Ext.form.DisplayField();
        this.form = new Ext.form.FormPanel({
            border: false,
            items: [{
                xtype: "fieldset",
                title: "请选择需要查询的流程",
                style: "margin:10px",
                items: [{
                    xtype: "compositefield",
                    labelWidth: 80,
                    fieldLabel: "查询的流程",
                    items: [this.flowNameComp, {
                        xtype: "button",
                        text: "选择流程",
                        handler: function() {
                            a.showSelectFlowWindow()
                        }
                    }]
                }]
            }]
        });
        this.mainPanel = new Ext.Window({
            title: "高级查询",
            width: 600,
            height: 500,
            bodyStyle: "background-color:#FFFFFF",
            maximizable: false,
            resizable: false,
            autoScroll: true,
            closeAction: "hide",
            items: [this.form],
            buttons: [{
                text: lang("search"),
                iconCls: "icon-search",
                cls: "btn-blue4",
                handler: function(c) {
                    var e = a.form.getForm().getValues(),
                        f = a.dataStore.baseParams;
                    for (var d in e) {
                        if (d.search(/T_/) == 0) {
                            f[d] = e[d]
                        }
                    }
                    f.flowId = a.flowId;
                    a.dataStore.reload()
                }
            },
                {
                    text: lang("clear") + " ",
                    cls: "btn-gray1",
                    handler: function() {
                        a.clearFieldParams()
                    }
                },
                {
                    text: lang("close"),
                    cls: "btn-red1",
                    iconCls: "icon-dialog-cancel",
                    handler: function() {
                        a.mainPanel.hide()
                    }
                }]
        })
    },
    clearFieldParams: function() {
        this.form.getForm().reset();
        var b = this.dataStore.baseParams;
        delete(b.flowId);
        for (var a in b) {
            if (a.search(/T_/) != -1) {
                delete(b[a])
            }
        }
        this.dataStore.reload()
    },
    show: function() {
        this.mainPanel.show()
    },
    initSearchFields: function(b) {
        var c = [],
            a = b.length;
        if (a > 0) {
            for (var d = 0; d < a; d++) {
                c.push(this.getField(b[d]))
            }
        } else {
            c.push({
                xtype: "displayfield",
                value: "无可查询的条件"
            })
        }
        this.form.remove(1);
        this.form.add({
            xtype: "fieldset",
            title: "查询条件",
            style: "margin:10px",
            items: c
        });
        this.form.doLayout()
    },
    getField: function(b) {
        var a = {};
        switch (b.otype) {
            case "choice":
                a = this.getChoice(b);
                break;
            case "macro":
                a = this.getMacro(b);
                break;
            default:
                a.xtype = "textfield";
                a.name = "T_" + b.id;
                break
        }
        a.width = 420;
        a.fieldLabel = b.name;
        return a
    },
    getMacro: function(b) {
        var a = {
            xtype: "selectorform",
            multiselect: false,
            name: "T_" + b.id
        };
        switch (b.dataType) {
            case "creatername":
                a.selectorType = "user";
                break;
            case "loginname":
                a.selectorType = "user";
                a.hiddenName = a.name;
                delete(a.name);
                break;
            case "createrdept":
            case "logindept":
                a.selectorType = "dept";
                break;
            case "createrjob":
            case "loginjob":
                a.selectorType = "job";
                break;
            case "createrstation":
            case "loginstation":
                a.selectorType = "station";
                break;
            default:
                a.xtype = "textfield";
                break
        }
        return a
    },
    getChoice: function(b) {
        var a = {
            xtype: "selectorform",
            hiddenName: "T_" + b.id
        };
        switch (b.dataType) {
            case "users_sel":
                a.selectorType = "user";
                a.multiselect = true;
                break;
            case "user_sel":
                a.selectorType = "user";
                a.multiselect = false;
                break;
            case "stations_sel":
                a.selectorType = "station";
                a.multiselect = true;
                break;
            case "station_sel":
                a.selectorType = "station";
                a.multiselect = false;
                break;
            case "jobs_sel":
                a.selectorType = "job";
                a.multiselect = true;
                break;
            case "job_sel":
                a.selectorType = "job";
                a.multiselect = false;
                break;
            case "depts_sel":
                a.selectorType = "dept";
                a.multiselect = true;
                break;
            case "dept_sel":
                a.selectorType = "dept";
                a.multiselect = false;
                break;
            default:
                a.xtype = "textfield";
                a.name = a.hiddenName;
                delete(a.hiddenName)
        }
        return a
    },
    getFlowFields: function(a) {
        var b = this;
        this.mainPanel.getEl().mask("请稍等...");
        Ext.Ajax.request({
            url: this.baseUrl + "&task=getFlowFieldsList",
            method: "POST",
            params: {
                flowId: a
            },
            success: function(d) {
                var c = Ext.decode(d.responseText);
                if (c.success === true) {
                    b.initSearchFields(c.data)
                } else {
                    CNOA.msg.alert(c.msg)
                }
                b.mainPanel.getEl().unmask()
            }
        })
    },
    showSelectFlowWindow: function() {
        var f = this;
        var g = {
            sortId: 0
        };
        var d = [{
            name: "flowId"
        },
            {
                name: "name"
            }];
        var k = new Ext.data.Store({
            proxy: new Ext.data.HttpProxy({
                url: f.baseUrl + "&task=getFlowListInSort"
            }),
            reader: new Ext.data.JsonReader({
                totalProperty: "total",
                root: "data",
                fields: d
            })
        });
        var b = new Ext.grid.CheckboxSelectionModel({
            singleSelect: true
        });
        var j = new Ext.grid.ColumnModel([b, {
            header: "flowId",
            dataIndex: "flowId",
            hidden: true
        },
            {
                header: "流程名称",
                dataIndex: "name",
                id: "name"
            }]);
        var a = new Ext.grid.GridPanel({
            bodyStyle: "border-left-width:1px;",
            border: false,
            store: k,
            loadMask: {
                msg: lang("waiting")
            },
            cm: j,
            sm: b,
            region: "center",
            stripeRows: true,
            hideBorders: true,
            autoExpandColumn: "name"
        });
        var c = new Ext.tree.AsyncTreeNode({
            text: "所有分类",
            sortId: "0",
            iconCls: "icon-tree-root-cnoa",
            cls: "feeds-node",
            expanded: true
        });
        var h = new Ext.tree.TreeLoader({
            dataUrl: this.baseUrl + "&task=getSortTree",
            preloadChildren: true,
            clearOnLoad: false
        });
        var m = new Ext.tree.TreePanel({
            region: "west",
            width: 150,
            minWidth: 80,
            maxWidth: 380,
            hideBorders: true,
            border: false,
            rootVisible: true,
            split: true,
            bodyStyle: "border-right-width:1px;",
            lines: true,
            animCollapse: false,
            animate: false,
            loader: h,
            root: c,
            autoScroll: true,
            listeners: {
                click: function(n) {
                    g.sortId = n.attributes.sortId;
                    k.load({
                        params: g
                    })
                }
            }
        });
        var e = new Ext.Window({
            title: "选择流程",
            width: 500,
            height: 400,
            layout: "border",
            modal: true,
            autoScroll: true,
            maximizable: false,
            resizable: false,
            items: [a, m],
            buttons: [{
                text: lang("ok"),
                iconCls: "icon-order-s-accept",
                handler: function(n) {
                    var p = a.getSelectionModel().getSelections();
                    if (p.length == 0) {
                        CNOA.miniMsg.alertShowAt(n, lang("mustSelectOneRow"))
                    } else {
                        var o = p[0];
                        f.flowNameComp.setValue(o.get("name"));
                        f.flowId = o.get("flowId");
                        f.getFlowFields(f.flowId);
                        e.close()
                    }
                }
            },
                {
                    text: lang("cancel"),
                    iconCls: "icon-dialog-cancel",
                    handler: function() {
                        e.close()
                    }
                }]
        }).show()
    }
};
function setPageSet(c, k) {
    var k = k;
    var e = k.pageSize;
    var a = 794;
    var j = 1123;
    switch (e) {
        case "a1page":
            if (k.pageDir == "lengthways") {
                a = 2245;
                j = 3174
            } else {
                a = 3174;
                j = 2245
            }
            break;
        case "a2page":
            if (k.pageDir == "lengthways") {
                a = 1587;
                j = 2245
            } else {
                a = 2245;
                j = 1587
            }
            break;
        case "a3page":
            if (k.pageDir == "lengthways") {
                a = 1123;
                j = 1587
            } else {
                a = 1587;
                j = 1123
            }
            break;
        case "a4page":
            if (k.pageDir == "lengthways") {
                a = 794;
                j = 1123
            } else {
                a = 1123;
                j = 794
            }
            break;
        case "a5page":
            if (k.pageDir == "lengthways") {
                a = 559;
                j = 794
            } else {
                a = 794;
                j = 559
            }
            break
    }
    var d = Math.ceil(k.pageUp * 3.4);
    var h = Math.ceil(k.pageDown * 3.4);
    var b = Math.ceil(k.pageLeft * 3.4);
    var g = Math.ceil(k.pageRight * 3.4);
    var f = d + "px " + g + "px " + h + "px " + b + "px";
    $(c).parent().css("padding", f);
    $(c).width(a - b - g)
}
Ext.ns("CNOA.wf.dealWindow");
CNOA.wf.dealWindow = Ext.extend(Ext.Window, {
    listeners: {
        beforerender: function(a) {
            try {
                CNOA_wf_use_dealflow.bottomPanel.bottomToolbar.disable()
            } catch(b) {}
        },
        close: function(b) {
            try {
                CNOA_wf_use_dealflow.bottomPanel.bottomToolbar.enable()
            } catch(a) {}
        }
    }
});
function wfViewForm(b) {
    var f = this;
    var c = Ext.getBody().getBox();
    var j = c.width - 20;
    var d = c.height - 20;
    var g = "workflow";
    var k = function() {
        Ext.Ajax.request({
            url: g + "/showFormInfo",
            method: "POST",
            params: {
                flowId: b
            },
            success: function(m) {
                var h = Ext.decode(m.responseText);
                if (h.success === true) {
                    a.getEl().update("<center>" + h.data.formHtml + "</center>")
                } else {
                    CNOA.msg.alert(h.msg,
                        function() {})
                }
                e.getEl().unmask()
            }
        })
    };
    var a = new Ext.Panel({
        border: false,
        html: "工作表单载入中...",
        listeners: {
            afterrender: function(h) {
                k()
            }
        }
    });
    var e = new Ext.Window({
        title: lang("viewJobForm"),
        width: j,
        height: d,
        layout: "fit",
        bodyStyle: "background-color:#FFFFFF",
        modal: true,
        autoScroll: true,
        maximizable: false,
        resizable: false,
        items: [a],
        buttons: [{
            text: lang("close"),
            iconCls: "icon-dialog-cancel",
            handler: function() {
                e.close()
            }
        }],
        listeners: {
            show: function(h) {
                h.getEl().mask(lang("waiting"))
            }
        }
    }).show()
}
var CNOA_wf_use_goNextStepWinClass, CNOA_wf_use_goNextStepWin;
CNOA_wf_use_goNextStepWinClass = CNOA.Class.create();
CNOA_wf_use_goNextStepWinClass.prototype = {
    init: function(c, m, h, g) {
        var f = this;
        this.selectedStepId = 0;
        this.baseUrl = "index.php?app=wf&func=flow&action=use&modul=new";
        this.from = m;
        this.tplSort = g;
        this.type = h;
        var j = {};
        selectedStepType = null;
        var n = Ext.id();
        var d = Ext.id();
        var k = Ext.id();
        var e = '<div class="cnoa-formhtml-layout" style="padding:5px;">';
        e += '<table width="100%" border="0" cellspacing="1" cellpadding="0">';
        e += "  <tr>";
        e += '    <td width="40%" valign="middle" class="lable" style="padding:10px;">' + lang("gotoStep") + "</td>";
        e += '    <td width="60%" valign="middle" class="lable" style="padding:10px;" id="' + n + '">' + lang("acceptOfficer") + "</td>";
        e += "  </tr>";
        e += "  <tr>";
        e += '    <td class="field" valign="top" style="padding:0;">';
        e += '	  <div style="height:283px;overflow:auto;padding:5px 5px 0 5px;" id="wf-next-win-steplist">';
        e += '		<!-- <a class="wf-next-win-a step">AAA</a> -->';
        e += "	  </div>";
        e += "	</td>";
        e += '    <td class="field" valign="top" style="padding:0;">';
        e += '	  <div style="height:283px;overflow:auto;padding:5px 5px 0 5px;" id="wf-next-win-optorlist">';
        e += '		<!-- <a class="wf-next-win-a optor">AAA</a> -->';
        e += "	  </div>";
        e += "	</td>";
        e += "  </tr>";
        e += "</table>";
        e += "</div>";
        var b = function(q) {
            selectedStepType = q.stepType;
            var s = [];
            var p = q.isEnd;
            if (p) {
                $("#" + n).html("");
                s.push('<a class="wf-next-win-a optor selected" uid="0">' + lang("endFlow") + "</a>");
                $("#wf-next-win-optorlist").html(s.join(""));
                return
            } else {
                $("#" + n).html(lang("acceptOfficer"))
            }
            var t = q.child;
            var r = q.dealWay;
            if (t.length > 0) {
                Ext.each(t,
                    function(v, u) {
                        if (v.operator.length == 0) {
                            checked = 'disabled="disabled"'
                        } else {
                            checked = 'checked="true"'
                        }
                        s.push('<span class="wf-next-win-span bfstep" stepId="' + v.stepId + '"><input type="checkbox" ' + checked + ' stepId="' + v.stepId + '"/><b style="margin-left:10px;font-size:15px;">' + v.stepName + '</b></span><div style="margin-bottom: 25px;">');
                        Ext.each(v.operator,
                            function(x, y) {
                                var w = "";
                                if (y == 0) {
                                    w = " selected"
                                }
                                s.push('<a class="wf-next-win-a optor' + w + '" stepId="' + v.stepId + '" uid="' + x.uid + '">' + x.name + " [" + x.sname + "]</a>")
                            });
                        s.push("</div>")
                    });
                $("#wf-next-win-optorlist").html(s.join(""))
            } else {
                if (r == 1 && q.operator.length > 0) {
                    checked = 'checked="true"';
                    Ext.each(q.operator,
                        function(u, w) {
                            classs = " selected";
                            s.push('<span class="wf-next-win-span bfstep" stepId="' + u.uid + '"><input type="checkbox" ' + checked + ' stepId="' + u.uid + '"/><b style="margin-left:10px;font-size:15px;">' + q.stepName + '</b></span><div style="margin-bottom: 25px;">');
                            s.push('<a class="wf-next-win-a optor' + classs + '" stepId="' + u.uid + '" uid="' + u.uid + '">' + u.name + " [" + u.sname + "]</a>");
                            s.push("</div>")
                        });
                    $("#wf-next-win-optorlist").html(s.join(""))
                } else {
                    if (q.operator.length > 0 && r != 1) {
                        Ext.each(q.operator,
                            function(w, x) {
                                var u = "";
                                if (x == 0) {
                                    u = " selected"
                                }
                                s.push('<a class="wf-next-win-a optor' + u + '" uid="' + w.uid + '">' + w.name + " [" + w.sname + "]</a>")
                            });
                        $("#wf-next-win-optorlist").html(s.join(""))
                    } else {
                        $("#wf-next-win-optorlist").html('<a class="cnoa_color_red">' + lang("thisStepNotDeal") + "</a>")
                    }
                }
            }
            if ($(".wf-next-win-a.optor.selected").length <= 0) {
                Ext.getCmp(d).disable();
                Ext.getCmp(k).disable()
            } else {
                Ext.getCmp(d).enable();
                Ext.getCmp(k).enable()
            }
            $(".wf-next-win-span.bfstep input").each(function() {
                $(this).click(function() {
                    var u = $(this);
                    var v = u.attr("stepid");
                    if (u[0].checked) {
                        $(".wf-next-win-a.optor[stepid=" + v + "]").eq(0).addClass("selected")
                    } else {
                        $(".wf-next-win-a.optor[stepid=" + v + "]").removeClass("selected")
                    }
                    if ($(".wf-next-win-a.optor.selected").length <= 0) {
                        Ext.getCmp(d).disable();
                        Ext.getCmp(k).disable();
                        $(".wf-next-win-a.optor").attr("selectStatus", "1")
                    } else {
                        Ext.getCmp(d).enable();
                        Ext.getCmp(k).enable();
                        $(".wf-next-win-a.optor").attr("selectStatus", "0")
                    }
                })
            });
            var o = $(".wf-next-win-span.bfstep");
            if (o.length > 0) {
                o.each(function() {
                    var v = $(this).attr("stepId");
                    var u = $(".wf-next-win-a.optor[stepId=" + v + "]");
                    u.each(function() {
                        $(this).click(function() {
                            $(".wf-next-win-span.bfstep[stepId=" + v + "]").find("input").attr("checked", true);
                            $(this).addClass("selected").siblings().removeClass("selected");
                            Ext.getCmp(d).enable();
                            Ext.getCmp(k).enable();
                            $(".wf-next-win-a.optor").attr("selectStatus", "0")
                        })
                    })
                })
            } else {
                $(".wf-next-win-a.optor").each(function() {
                    $(this).click(function() {
                        $(this).addClass("selected").siblings().removeClass("selected")
                    })
                })
            }
        };
        var a = function() {
            var o = [];
            if (c.length > 0) {
                Ext.each(c,
                    function(q, r) {
                        j["step_" + q.stepId] = q;
                        var p = "";
                        if (r == 0) {
                            p = " selected";
                            f.selectedStepId = q.stepId
                        }
                        o.push('<a class="wf-next-win-a step' + p + '" stepid="' + q.stepId + '">' + q.stepName + "</a>")
                    });
                $("#wf-next-win-steplist").html(o.join(""));
                b(c[0])
            } else {
                $("#wf-next-win-steplist").html('<a class="cnoa_color_red">' + lang("noEligibleStep") + "</a>");
                $("#wf-next-win-optorlist").html('<a class="cnoa_color_red">' + lang("thisStepNotDeal") + "</a>")
            }
            if (selectedStepType != 4) {
                Ext.getCmp("selectAll_btn").disable()
            } else {
                Ext.getCmp("selectAll_btn").enable()
            }
            $(".wf-next-win-a.step").each(function() {
                $(this).click(function() {
                    var p = $(this);
                    if (f.selectedStepId != p.attr("stepId")) {
                        f.selectedStepId = p.attr("stepId");
                        p.addClass("selected").siblings().removeClass("selected");
                        b(j["step_" + p.attr("stepId")]);
                        if ($(".wf-next-win-a.optor.selected").length <= 0) {
                            Ext.getCmp(d).disable();
                            Ext.getCmp(k).disable()
                        } else {
                            Ext.getCmp(d).enable();
                            Ext.getCmp(k).enable()
                        }
                    }
                    if (selectedStepType != 4) {
                        Ext.getCmp("selectAll_btn").disable()
                    } else {
                        Ext.getCmp("selectAll_btn").enable()
                    }
                })
            });
            $(".wf-next-win-a.optor.selected").each(function() {
                var p = $(this);
                p.click(function() {
                    optorStepId = p.attr("stepid");
                    $(".wf-next-win-span.bfstep[stepid=" + optorStepId + "]").find("input").attr("checked", true);
                    if (p.length <= 0) {
                        Ext.getCmp(d).disable();
                        Ext.getCmp(k).disable();
                        $(".wf-next-win-a.optor").attr("selectStatus", "1")
                    } else {
                        Ext.getCmp(d).enable();
                        Ext.getCmp(k).enable();
                        $(".wf-next-win-a.optor").attr("selectStatus", "0")
                    }
                })
            })
        };
        this.formPanel = new Ext.Panel({
            border: false,
            hideBorders: true,
            html: e,
            autoScroll: false,
            listeners: {
                afterrender: a
            }
        });
        this.mainPanel = new CNOA.wf.dealWindow({
            title: lang("turnNextSelectPeople"),
            width: 540,
            height: 450,
            layout: "fit",
            modal: true,
            maximizable: false,
            resizable: false,
            items: this.formPanel,
            tbar: new Ext.Toolbar({
                items: ["->", {
                    xtype: "button",
                    id: "selectAll_btn",
                    text: lang("selectAll") + "/" + lang("cancel"),
                    handler: function() {
                        var o = $(".wf-next-win-a.optor");
                        o.each(function() {
                            optorStepId = $(this).attr("stepid");
                            optorStatus = $(this).attr("selectStatus");
                            if (selectedStepType == 4) {
                                if (optorStatus != 1) {
                                    $(this).removeClass("selected");
                                    $(this).attr("selectStatus", "1");
                                    $(".wf-next-win-span.bfstep[stepid=" + optorStepId + "]").find("input").attr("checked", false);
                                    Ext.getCmp(d).disable();
                                    Ext.getCmp(k).disable()
                                } else {
                                    $(".wf-next-win-a.optor[stepid=" + optorStepId + "]").eq(0).addClass("selected");
                                    $(".wf-next-win-span.bfstep[stepid=" + optorStepId + "]").find("input").attr("checked", true);
                                    $(this).attr("selectStatus", "0");
                                    Ext.getCmp(d).enable();
                                    Ext.getCmp(k).enable()
                                }
                            }
                        })
                    }
                },
                    (lang("name") + ":"), {
                        xtype: "search",
                        key: "$('#wf-next-win-optorlist a')",
                        searchIcon: false
                    }]
            }),
            buttons: [{
                text: lang("determineConcurrentSMS"),
                iconCls: "icon-sms-send",
                id: d,
                handler: function() {
                    if (selectedStepType == 4) {
                        var o = $(".wf-next-win-a.step.selected").index();
                        f.showConvergenceUname("phone", c[o].convergenenUname)
                    } else {
                        f.submitForm("phone")
                    }
                }.createDelegate(this)
            },
                {
                    text: lang("ok"),
                    iconCls: "icon-order-s-accept",
                    id: k,
                    handler: function() {
                        if (selectedStepType == 4) {
                            var o = $(".wf-next-win-a.step.selected").index();
                            f.showConvergenceUname("", c[o].convergenenUname)
                        } else {
                            f.submitForm("")
                        }
                    }.createDelegate(this)
                },
                {
                    text: lang("cancel"),
                    iconCls: "icon-dialog-cancel",
                    handler: function() {
                        f.mainPanel.close();
                        $("div[id^='DD']").each(function() {
                            $(this).css("z-index", 20000)
                        })
                    }
                }],
            listeners: {
                close: function() {
                    try {
                        if (f.from == "new") {
                            CNOA_wf_use_new.formPanel.body.dom.setAttribute("enctype", "");
                            CNOA_wf_use_new.formPanel.body.dom.enctype = ""
                        }
                        if (f.from == "deal") {
                            CNOA_wf_use_dealflow.formPanel.body.dom.setAttribute("enctype", "");
                            CNOA_wf_use_dealflow.formPanel.body.dom.enctype = ""
                        }
                    } catch(o) {}
                }
            }
        }).show()
    },
    showConvergenceUname: function(h, b) {
        var f = this;
        var a = b;
        var k = [];
        this.convergence = Ext.id();
        Ext.each(a,
            function(e, m) {
                k.push({
                    boxLabel: e.name + "[" + e.sname + "]",
                    inputValue: e.uid == 0 ? e.oid: e.uid,
                    name: "convergenenUname"
                })
            });
        try {
            k[0].checked = "true"
        } catch(g) {}
        var c = false;
        if (k.length > 0) {
            k = [{
                xtype: "radiogroup",
                items: k,
                columns: 1,
                id: this.convergence
            }];
            c = true
        } else {
            k = {
                xtype: "displayfield",
                value: lang("huiJuNoPerson")
            }
        }
        var d = new Ext.form.FormPanel({
            labelAlign: "right",
            autoScroll: true,
            labelWidth: 20,
            waitMsgTarget: true,
            border: false,
            items: k
        });
        var j = new Ext.Window({
            title: lang("chooseHuiJuPerson"),
            width: 300,
            height: 200,
            layout: "fit",
            modal: true,
            maximizable: false,
            resizable: false,
            items: d,
            buttons: [{
                text: lang("ok"),
                disabled: c ? false: true,
                iconCls: "icon-order-s-accept",
                handler: function() {
                    var e = d.getForm().getValues().convergenenUname;
                    j.close();
                    f.submitForm(h, e)
                }
            },
                {
                    text: lang("cancel"),
                    iconCls: "icon-dialog-cancel",
                    handler: function() {
                        j.close()
                    }
                }]
        }).show()
    },
    submitForm: function(g, a) {
        var d = this;
        var b = $(".wf-next-win-a.step.selected");
        var c = b.attr("stepid"),
            f = b.text();
        var e = [],
            h = [];
        var j = $(".wf-next-win-a.optor.selected");
        j.each(function(k) {
            var m = true;
            if ($(this).attr("stepid") != undefined) {
                m = $(".wf-next-win-span.bfstep input[stepid=" + $(this).attr("stepid") + "]").get(0).checked;
                if (m) {
                    e.push($(this).attr("stepId"))
                }
            }
            if (m) {
                e.push($(this).attr("uid"));
                h.push($(this).text())
            }
        });
        if (a == undefined) {
            a = ""
        }
        if ((c != undefined) && (e != undefined)) {
            d.mainPanel.getEl().mask(lang("submitPleaseWait"));
            if (d.from == "new") {
                CNOA_wf_use_new.sendNextStep({
                        stepId: c,
                        stepname: f,
                        uid: e.toString(),
                        uname: h.toString(),
                        phone: g,
                        convergenenUid: a
                    },
                    function() {
                        d.mainPanel.getEl().unmask();
                        d.mainPanel.close()
                    })
            }
            if (d.from == "deal") {
                CNOA_wf_use_dealflow.sendNextStep({
                        stepId: c,
                        stepname: f,
                        uid: e.toString(),
                        uname: h.toString(),
                        type: d.type,
                        phone: g,
                        convergenenUid: a
                    },
                    function() {
                        d.mainPanel.getEl().unmask();
                        d.mainPanel.close()
                    })
            }
        } else {
            CNOA.msg.notice(lang("stepConduction"), lang("workFlowRemind"))
        }
    }
};
var CNOA_wf_use_showFlowStepClass = CNOA.Class.create();
CNOA_wf_use_showFlowStepClass.prototype = {
    init: function(c, b, a, e, d, f) {
        var g = this;
        this.flowType = d;
        this.tplSort = f;
        this.baseUrl = "index.php?app=wf&func=flow&action=use&modul=todo";
        this.uFlowId = c;
        this.flowId = e;
        this.autoHeight = b == undefined ? true: false;
        this.autoGroup = a == undefined ? true: false;
        this.renderStepStatusText = function(r, o, m, p, n, j) {
            var s = m.data.status;
            var q = "#00000";
            if (s == 1) {
                q = "#FF0000"
            } else {
                if (s == 2) {
                    q = "#008000"
                } else {
                    if (s == 3) {
                        q = "#FF00FF"
                    } else {
                        if (s == 4) {
                            q = "#008000"
                        }
                    }
                }
            }
            var k = "<span style='color:" + q + ";'>" + r + "</span>";
            return k
        };
        this.renderNodenameText = function(n, k, h, o, j, m) {
            return "<span ext:qtip='" + n + "'>" + n + "</span>"
        };
        this.renderStepOperatText = function(q, o, j, r, m, p) {
            var n = j.data;
            var k = q + "<br /><span style='color:#808080;'>" + n.formatStime + "</span>";
            return k
        };
        this.renderCuiban = function(q, o, j, r, m, p) {
            var n = j.data;
            var k = n.status == 1 ? "<span style='color:#808080;'><a href='javascript:void(0);' onclick='CNOA_wf_use_showFlowStep.cuiban(" + this.uFlowId + "," + n.uStepId + ',"' + n.cuibanName + "\");'>" + lang("reminder") + "</a></span>": "";
            return k
        };
        this.fields = [{
            name: "uStepId"
        },
            {
                name: "status"
            },
            {
                name: "statusText"
            },
            {
                name: "stepid"
            },
            {
                name: "uname"
            },
            {
                name: "stime"
            },
            {
                name: "formatStime"
            },
            {
                name: "utime"
            },
            {
                name: "say"
            },
            {
                name: "nodename"
            },
            {
                name: "cuibanName"
            }];
        if (this.autoGroup) {
            this.store = new Ext.data.GroupingStore({
                proxy: new Ext.data.HttpProxy({
                    url: this.baseUrl + "&task=getStepList&uFlowId=" + this.uFlowId + "&flowId=" + this.flowId + "&flowType=" + this.flowType
                }),
                reader: new Ext.data.JsonReader({
                    totalProperty: "totalPorperty",
                    root: "data",
                    fields: this.fields
                }),
                sortInfo: {
                    field: "uStepId",
                    direction: "ASC"
                },
                groupOnSort: false,
                groupField: "nodename",
                sortData: function() {}
            })
        } else {
            this.store = new Ext.data.GroupingStore({
                proxy: new Ext.data.HttpProxy({
                    url: this.baseUrl + "&task=getStepList&uFlowId=" + this.uFlowId + "&flowId=" + this.flowId + "&flowType=" + this.flowType
                }),
                reader: new Ext.data.JsonReader({
                    totalProperty: "totalPorperty",
                    root: "data",
                    fields: this.fields
                })
            })
        }
        this.store.load({
            params: {
                start: 0,
                limit: 15
            }
        });
        this.grid = new Ext.grid.GridPanel({
            stripeRows: true,
            store: this.store,
            autoScroll: true,
            hideBorders: true,
            border: false,
            columns: [new Ext.grid.RowNumberer(), {
                header: "uStepId",
                width: 10,
                sortable: true,
                dataIndex: "uStepId",
                hidden: true
            },
                {
                    header: lang("stepName"),
                    width: 120,
                    sortable: true,
                    dataIndex: "nodename",
                    renderer: this.renderNodenameText
                },
                {
                    header: lang("status"),
                    width: 80,
                    sortable: true,
                    dataIndex: "statusText",
                    renderer: this.renderStepStatusText
                },
                {
                    header: lang("attn") + " / " + lang("startTime"),
                    width: 130,
                    sortable: true,
                    dataIndex: "uname",
                    renderer: this.renderStepOperatText
                },
                {
                    header: lang("timeDuration"),
                    width: 60,
                    sortable: true,
                    dataIndex: "utime"
                },
                {
                    header: lang("forReason"),
                    width: 150,
                    sortable: true,
                    dataIndex: "say",
                    id: "say"
                },
                {
                    header: lang("reminder"),
                    width: 40,
                    sortable: true,
                    dataIndex: "uStepId",
                    renderer: this.renderCuiban.createDelegate(this)
                }],
            autoHeight: this.autoHeight,
            listeners: {
                cellclick: function(k, n, j) {
                    if (j < 7) {
                        var h = k.getStore().getAt(n);
                        var m = new Ext.Window({
                            title: lang("viewPricessStep"),
                            layout: "fit",
                            width: 400,
                            height: 260,
                            modal: true,
                            maximizable: true,
                            items: [{
                                xtype: "panel",
                                border: false,
                                autoScroll: true,
                                bodyStyle: "padding:10px;",
                                html: h.data.say.replace(/\r\n/ig, "<br />").replace(/\n/ig, "<br />")
                            }],
                            buttons: [{
                                text: lang("close"),
                                iconCls: "icon-dialog-cancel",
                                handler: function() {
                                    m.close()
                                }.createDelegate(this)
                            }]
                        }).show()
                    }
                }
            }
        });
        this.win = new Ext.Window({
            title: lang("checkProcessStep"),
            layout: "fit",
            width: 650,
            height: 300,
            modal: true,
            maximizable: true,
            items: [this.grid],
            buttons: [{
                text: lang("close"),
                iconCls: "icon-dialog-cancel",
                handler: function() {
                    g.win.close()
                }.createDelegate(this)
            }]
        }).show()
    },
    cuiban: function(b, f, a) {
        var h = this;
        var g = Ext.id();
        var c = Ext.id();
        var d = function() {
            content = Ext.getCmp(g);
            if (Ext.isEmpty(content.getValue())) {
                CNOA.msg.notice(lang("notice"), lang("pleasefillReminder"));
                content.focus(100, this)
            } else {
                var j = Ext.getCmp(c).getValue();
                Ext.Ajax.request({
                    url: h.baseUrl + "&task=cuiban",
                    method: "POST",
                    params: {
                        uFlowId: b,
                        uStepId: f,
                        content: content.getValue(),
                        sms: j
                    },
                    success: function(k) {
                        CNOA.msg.notice(lang("successSentReminder"), lang("notice"));
                        e.close()
                    }
                })
            }
        };
        var e = new Ext.Window({
            title: lang("reminder") + ": " + lang("urge") + "[" + a + "]" + lang("deal"),
            layout: "fit",
            width: 400,
            height: 260,
            modal: true,
            maximizable: true,
            bodyStyle: "padding:10px;",
            items: [{
                xtype: "textarea",
                id: g,
                emptyText: lang("pleasefillReminder")
            }],
            buttons: [{
                xtype: "checkbox",
                id: c,
                boxLabel: lang("SMSnotification")
            },
                {
                    text: lang("reminder"),
                    handler: function() {
                        d()
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
    }
};
var CNOA_wf_use_showFlowEventClass = CNOA.Class.create();
CNOA_wf_use_showFlowEventClass.prototype = {
    init: function(b, a, d, c, e) {
        var f = this;
        this.baseUrl = "index.php?app=wf&func=flow&action=use&modul=todo";
        this.uFlowId = b;
        this.flowId = d;
        this.autoHeight = a == undefined ? true: false;
        this.flowType = c;
        this.tplSort = e;
        this.renderEventType = function(q, n, k, o, m, g) {
            var r = k.data;
            var p = "#00000";
            if (r.type == 1) {
                p = "#FF0000"
            } else {
                if (r.type == 2) {
                    p = "#008000"
                } else {
                    if (r.type == 3) {
                        p = "#FF8040"
                    } else {
                        if (r.type == 4) {
                            p = "#FF00FF"
                        } else {
                            if (r.type == 5) {
                                p = "#448CBB"
                            } else {
                                if (r.type == 6) {
                                    p = "#408080"
                                } else {
                                    if (r.type == 7) {
                                        p = "#0080FF"
                                    } else {
                                        if (r.type == 8) {
                                            p = "#720E65"
                                        } else {
                                            if (r.type == 9) {
                                                p = "#8D3CC4"
                                            } else {
                                                if (r.type == 11) {
                                                    p = "#666666"
                                                } else {
                                                    p = "#44A3BB"
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
            var j = "<span style='color:#FFF;display:block;height:20px;background-color:" + p + ";text-indent:3px;'>" + r.typename + "</span>";
            return j
        };
        this.renderStepnameText = function(m, j, g, n, h, k) {
            return "<span ext:qtip='" + m + "'>" + m + "</span>"
        };
        this.renderEventOther = function(n, m, g) {
            var k = g.data;
            var j = k.uname + "<br />";
            j += "<span style='color:#8C8C8C'>" + k.postTime + "</span>";
            return j
        };
        this.fields = [{
            name: "id"
        },
            {
                name: "type"
            },
            {
                name: "typename"
            },
            {
                name: "title"
            },
            {
                name: "say"
            },
            {
                name: "uname"
            },
            {
                name: "stepname"
            },
            {
                name: "postTime"
            }];
        this.store = new Ext.data.GroupingStore({
            proxy: new Ext.data.HttpProxy({
                url: this.baseUrl + "&task=getEventList&uFlowId=" + this.uFlowId + "&flowId=" + this.flowId + "&flowType=" + this.flowType
            }),
            reader: new Ext.data.JsonReader({
                totalProperty: "total",
                root: "data",
                fields: this.fields
            })
        });
        this.store.load();
        this.colModel = new Ext.grid.ColumnModel([new Ext.grid.RowNumberer(), {
            header: "id",
            dataIndex: "id",
            width: 20,
            sortable: true,
            menuDisabled: true,
            hidden: true
        },
            {
                header: lang("type"),
                dataIndex: "typename",
                width: 80,
                menuDisabled: true,
                sortable: true,
                renderer: this.renderEventType.createDelegate(this)
            },
            {
                header: lang("step"),
                dataIndex: "stepname",
                width: 100,
                menuDisabled: true,
                sortable: true,
                renderer: this.renderStepnameText.createDelegate(this)
            },
            {
                header: lang("attn") + " / " + lang("time"),
                dataIndex: "other",
                width: 150,
                sortable: false,
                resizable: false,
                menuDisabled: true,
                renderer: this.renderEventOther.createDelegate(this)
            },
            {
                header: lang("forReason") + "(" + lang("clickToView") + ")",
                dataIndex: "say",
                id: "say",
                width: 260,
                menuDisabled: true,
                sortable: false
            }]);
        this.grid = new Ext.grid.GridPanel({
            stripeRows: true,
            ds: this.store,
            autoScroll: true,
            loadMask: {
                msg: lang("waiting")
            },
            cm: this.colModel,
            hideBorders: true,
            border: false,
            autoHeight: this.autoHeight,
            autoExpandColumn: "say",
            listeners: {
                cellclick: function(g, n, h, m) {
                    var j = g.getStore().getAt(n).data;
                    if (j.say != "") {
                        var k = new Ext.Window({
                            title: lang("viewFlowEventDealt"),
                            layout: "fit",
                            width: 400,
                            height: 260,
                            modal: true,
                            maximizable: true,
                            items: [{
                                xtype: "panel",
                                border: false,
                                autoScroll: true,
                                bodyStyle: "padding:10px;",
                                html: j.say.replace(/\r\n/ig, "<br />").replace(/\n/ig, "<br />")
                            }],
                            buttons: [{
                                text: lang("close"),
                                iconCls: "icon-dialog-cancel",
                                handler: function(o, p) {
                                    k.close()
                                }
                            }]
                        }).show()
                    }
                }
            }
        });
        this.win = new Ext.Window({
            title: lang("seeFlowEvent"),
            layout: "fit",
            width: 650,
            height: 300,
            modal: true,
            maximizable: true,
            items: [this.grid],
            buttons: [{
                text: lang("close"),
                iconCls: "icon-dialog-cancel",
                handler: function() {
                    f.win.close()
                }.createDelegate(this)
            }]
        }).show()
    }
};
CNOA_wf_use_newfree_goNextStepWinClass = CNOA.Class.create();
CNOA_wf_use_newfree_goNextStepWinClass.prototype = {
    init: function(a, f, k, j, b, h, g, c) {
        var d = this;
        this.ID_say_text = Ext.id();
        this.flowId = a;
        this.tplSort = f;
        this.flowType = k;
        this.from = j;
        this.step = b;
        this.uFlowId = h;
        this.type = g;
        this.editAction = c;
        if (this.from == "newfree") {
            this.baseUrl = "index.php?app=wf&func=flow&action=use&modul=newfree&flowId=" + this.flowId + "&tplSort=" + this.tplSort + "&flowType=" + this.flowType
        } else {
            this.baseUrl = "index.php?app=wf&func=flow&action=use&modul=todo&flowId=" + this.flowId + "&step=" + this.step + "&uFlowId=" + this.uFlowId + "&tplSort=" + this.tplSort + "&flowType=" + this.flowType
        }
        this.select_user = new Ext.form.SelectorForm({
            fieldLabel: lang("selectPeople"),
            allowBlank: false,
            width: 275,
            hiddenName: "dealuid",
            name: "dealname",
            multiselect: false,
            selectorType: "user",
            listeners: {
                select: function(o, p) {
                    var n = p[0].name;
                    d.select_stepname.setValue(n + "(" + lang("deal") + ")")
                }
            }
        });
        this.select_stepname = new Ext.form.TextField({
            width: 275,
            fieldLabel: lang("flowStep"),
            allowBlank: false,
            name: "stepname"
        });
        this.formPanel = new Ext.form.FormPanel({
            border: false,
            hideBorders: true,
            labelWidth: 60,
            items: {
                xtype: "panel",
                border: false,
                bodyStyle: "padding: 10px",
                layout: "form",
                items: [this.select_user, this.select_stepname, {
                    xtype: "textarea",
                    id: d.ID_say_text,
                    height: 80,
                    width: 275,
                    fieldLabel: lang("forReason"),
                    name: "say",
                    emptyText: lang("pleaseFillReason")
                }]
            }
        });
        var e = Ext.id();
        this.mainPanel = new Ext.Window({
            title: lang("selectNextStep"),
            layout: "fit",
            border: false,
            autoScroll: true,
            width: 390,
            height: 240,
            modal: true,
            items: [this.formPanel],
            buttons: [{
                xtype: "checkbox",
                id: e,
                boxLabel: lang("SMSnotification")
            },
                {
                    text: lang("save"),
                    iconCls: "icon-order-s-accept",
                    handler: function(o, q) {
                        var p = Ext.getCmp(e).getValue();
                        var n = d.select_stepname.getValue();
                        if (n) {
                            d.freeFlowSendNextStep(p)
                        } else {
                            CNOA.msg.alert("人员不能为空！");
                            return
                        }
                    }.createDelegate(this)
                },
                {
                    text: lang("close"),
                    iconCls: "icon-dialog-cancel",
                    handler: function(n, o) {
                        d.mainPanel.close()
                    }.createDelegate(this)
                }]
        });
        var m = "";
        if (d.from == "deal") {
            m = Ext.getCmp(CNOA_wf_use_dealflow.ID_checkAbout).getValue()
        }
        Ext.getCmp(d.ID_say_text).setValue(m)
    },
    freeFlowSendNextStep: function(a) {
        var h = this;
        var b = h.select_user.getValue();
        if (b == "") {
            CNOA.msg.alert(lang("selectNextStep"));
            return
        }
        var c = function(f) {
            var p = h.baseUrl + "&task=ms_submitMsOfficeData&tplSort=" + h.tplSort + "&uFlowId=" + f;
            CNOA.WOWF.saveOffice(p);
            if (h.from == "newfree") {
                if (h.editAction == "add") {
                    mainPanel.closeTab("CNOA_USE_FLOW_NEWFREE_FLOWDESIGN")
                } else {
                    mainPanel.closeTab("CNOA_MENU_WF_USE_OPENFLOW")
                }
            } else {
                mainPanel.closeTab("CNOA_MENU_WF_USE_OPENFLOW")
            }
            try {
                CNOA_wf_use_draft.store.reload()
            } catch(q) {}
        };
        var n = "";
        if (h.from == "newfree") {
            var m = CNOA_wf_use_newfree_flowdesign.formPanel;
            var j = m.getForm();
            try {
                m.body.dom.setAttribute("enctype", "multipart/form-data");
                m.body.dom.enctype = "multipart/form-data"
            } catch(k) {}
            if (h.tplSort == 0) {
                n = CNOA_wf_use_newfree_flowdesign.editor.getContent()
            }
        } else {
            var m = CNOA_wf_use_dealflow.formPanel;
            var j = m.getForm();
            try {
                m.body.dom.setAttribute("enctype", "multipart/form-data");
                m.body.dom.enctype = "multipart/form-data"
            } catch(k) {}
            if (h.tplSort == 0) {
                n = CNOA_wf_use_dealflow.editor.getContent()
            }
        }
        var b = h.select_user.getValue();
        var g = h.select_stepname.getValue();
        var o = h.formPanel.getForm().findField("say").getValue();
        var d = h.select_user.value;
        if (j.isValid()) {
            CNOA.msg.cf(lang("confirmNextStep"),
                function(e) {
                    if (e == "yes") {
                        j.submit({
                            url: h.baseUrl + "&task=freeFlowSendNextStep",
                            params: {
                                dealuid: b,
                                stepname: g,
                                say: o,
                                type: h.type,
                                uFlowId: h.uFlowId,
                                flowType: h.flowType,
                                tplSort: h.tplSort,
                                htmlFormContent: n,
                                sms: a
                            },
                            method: "POST",
                            success: function(r, p) {
                                CNOA.msg.notice(lang("flowIntoStep") + "：" + g + "<br />" + lang("acceptOfficer") + "：" + d, lang("workFlow"), 6);
                                var f = p.result.data.uFlowId;
                                if (h.tplSort != 0) {
                                    if (h.from == "newfree") {
                                        c(f)
                                    } else {
                                        c(h.uFlowId)
                                    }
                                } else {
                                    if (h.from == "newfree") {
                                        if (h.editAction == "add") {
                                            mainPanel.closeTab("CNOA_USE_FLOW_NEWFREE_FLOWDESIGN")
                                        } else {
                                            mainPanel.closeTab("CNOA_MENU_WF_USE_OPENFLOW");
                                            try {
                                                CNOA_wf_use_draft.store.reload()
                                            } catch(q) {}
                                        }
                                    } else {
                                        mainPanel.closeTab("CNOA_MENU_WF_USE_OPENFLOW")
                                    }
                                }
                                h.mainPanel.close();
                                try {
                                    CNOA_wf_use_todo.store.reload()
                                } catch(q) {}
                            }
                        })
                    } else {}
                })
        }
    },
    show: function() {
        var a = this;
        a.mainPanel.show()
    }
};
function tplFile(c) {
    var b = c.url;
    var a = c.uFlowId;
    var d = c.flowId;
    var e = c.tplSort;
    this.saveTemplateFile = function() {
        var f = b + "&task=ms_submitMsOfficeData&tplSort=" + e + "&uFlowId=" + a;
        try {
            CNOA.WOWF.saveOffice(f)
        } catch(g) {}
        mainPanel.closeTab("CNOA_MENU_WF_USE_OPENFLOW")
    }
}
CNOA_wf_use_flowpreviewClass = CNOA.Class.create();
CNOA_wf_use_flowpreviewClass.prototype = {
    init: function(d, c) {
        var g = this;
        this.baseUrl = "index.php?app=wf&func=flow&action=use&modul=new";
        this.designer = null;
        this.flowId = d;
        this.uFlowId = c;
        this.stepWindow = null;
        this.data = {
            flowXml: "",
            steps: {}
        };
        this.flowFields = null;
        var f = Ext.getBody().getBox();
        var b = f.width - 60;
        var e = f.height - 60;
        var a = Ext.id();
        this.centerPanel = new Ext.Panel({
            border: false,
            region: "center",
            autoScroll: false,
            html: '<iframe scrolling="auto" frameborder="0" width="100%" height="100%" id="' + a + '"></iframe>'
        });
        this.mainPanel = new Ext.Window({
            title: lang("viewProcess"),
            width: b,
            height: e,
            layout: "border",
            modal: true,
            maximizable: true,
            items: [this.centerPanel],
            buttons: [{
                text: lang("close"),
                handler: function() {
                    g.mainPanel.close()
                }
            }],
            listeners: {
                show: function(j) {
                    var h = Ext.getDom(a).contentWindow;
                    h.location.href = "cnoa/scripts/wfDesigner/view.php?r=" + Math.random()
                },
                close: function() {
                    try {
                        Ext.getDom(a).src = ""
                    } catch(h) {}
                }
            }
        }).show();
        this.loadFlowDesignData()
    },
    close: function() {
        this.mainPanel.close()
    },
    loadFlowDesignData: function() {
        var a = this;
        Ext.Ajax.request({
            url: this.baseUrl + "&task=loadFlowDesignData",
            method: "POST",
            params: {
                flowId: this.flowId,
                uFlowId: this.uFlowId
            },
            success: function(c) {
                var b = Ext.decode(c.responseText);
                if (b.success === true) {
                    a.setFlowXml(b.data.flowXml)
                } else {
                    CNOA.msg.alert(lang("failedLoad"),
                        function() {
                            a.close()
                        })
                }
            }
        })
    },
    setFlowXml: function(a) {
        var c = this;
        var b = setInterval(function() {
                try {
                    c.designer.setXml(a);
                    clearInterval(b)
                } catch(d) {}
            },
            50)
    }
};
var CNOA_wf_form_checker = new(function() {
    var detail_list = {},
        ckme = this;
    var formPanelId = "";
    var elIsSelected = function(el) {
        vl = el.val();
        el = el.get(0);
        var selection, version = $.browser.version;
        if (!$.browser.msie) {
            if (el.selectionStart != undefined) {
                selection = el.value.substr(el.selectionStart, el.selectionEnd - el.selectionStart)
            }
        } else {
            if (parseInt(version.substr(0, version.indexOf(".")), 10) > 8) {
                selection = el.value.substr(el.selectionStart, el.selectionEnd - el.selectionStart)
            } else {
                if (window.getSelection) {
                    selection = window.getSelection()
                } else {
                    if (document.getSelection) {
                        selection = document.getSelection()
                    } else {
                        if (document.selection) {
                            selection = document.selection.createRange().text
                        }
                    }
                }
            }
        }
        if (selection.length < 1) {
            return false
        }
        if (selection.length <= vl.length) {
            return true
        }
    };
    this.eventList = {
        keyup: {},
        change: {},
        blur: {}
    };
    var addEvent = function(event, etargetid, key, func) {
        if (event.indexOf(" ") != -1) {
            event = event.split(" ");
            for (var i = 0; i < event.length; i++) {
                ckme.eventList[event[i]][key] = {
                    tid: etargetid,
                    func: func
                }
            }
        } else {
            ckme.eventList[event][key] = {
                tid: etargetid,
                func: func
            }
        }
    };
    var removeEvent = function(event, key, func) {
        delete ckme.eventList[event][key]
    };
    var checkMust = function() {
        var pass = false;
        var selector = ".wf_form_content input:text[must=true],textarea[must=true],input:button[must=true],select[must=true],input:hidden[must=true][mustfor=radio],input:hidden[must=true][mustfor=signature],input:hidden[must=true][mustfor=button],input:[must=true][mustfor=choice],input:hidden[must=true][mustfor=checkbox]";
        var els = $(selector);
        for (var i = 0; i < els.length; i++) {
            var nowEl = $(els[i]);
            if (nowEl.parents("span[class=group][showhide=hide]").length <= 0) {
                if (nowEl.attr("type") == "hidden" && nowEl.attr("mustfor") == "radio") {
                    var radio = $(".wf_form_content input:radio[name=" + nowEl.attr("musttarget") + "]:checked");
                    if (radio.length <= 0) {
                        CNOA.msg.alert(lang("radioControls") + ": [" + nowEl.attr("foroname") + "]" + lang("pleaseSelectOne1"));
                        return false
                    }
                } else {
                    if (nowEl.attr("type") == "button" && nowEl.attr("must") == "true") {
                        var attachArr = nowEl.closest("tbody"),
                            dataAttach = nowEl.attr("data-attach"),
                            tra = attachArr.find("tr"),
                            len = 0;
                        $(tra).each(function() {
                            var dataAttach2 = $(this).attr("data-attach");
                            if (dataAttach2 == dataAttach) {
                                len++
                            }
                        });
                        if (len < 1) {
                            CNOA.msg.alert(lang("controls") + ": [" + nowEl.val() + "]" + lang("isRequired"),
                                function() {
                                    nowEl.focus()
                                });
                            return false
                        }
                    } else {
                        if (nowEl.attr("type") == "hidden" && nowEl.attr("mustfor") == "checkbox") {
                            var checkbox = $(".wf_form_content input:checkbox[gid=" + nowEl.attr("musttarget") + "]:checked");
                            if (checkbox.length <= 0) {
                                CNOA.msg.alert(lang("multipleChoice") + ": [" + nowEl.attr("foroname") + "]" + lang("pleaseSelectOne1"));
                                return false
                            }
                        } else {
                            if (Ext.isEmpty(nowEl.val())) {
                                CNOA.msg.alert(lang("controls") + ": [" + nowEl.attr("oname") + "]" + lang("isRequired"),
                                    function() {
                                        nowEl.focus()
                                    });
                                return false
                            }
                        }
                    }
                }
            }
        }
        var selector = ".wf_form_content input[isvalid=false],textarea[isvalid=false],select[isvalid=false]";
        var els = $(selector);
        for (var i = 0; i < els.length; i++) {
            var nowEl = $(els[i]);
            if (nowEl.attr("otype") == "calculate") {
                CNOA.msg.alert(lang("controls") + ": [" + nowEl.attr("oname") + "]" + lang("calculationNotCorrect"),
                    function() {
                        nowEl.focus()
                    })
            } else {
                CNOA.msg.alert(lang("controls") + ": [" + nowEl.attr("oname") + "]" + lang("fillIncorrect"),
                    function() {
                        nowEl.focus()
                    })
            }
            return false
        }
        return true
    };
    var bindMoneyConvert = function() {
        var converters = $(".wf_form_content input:hidden:[moneyconvert=true]");
        converters.each(function() {
            var th = this;
            var from = $("#" + $(this).attr("from"));
            var to = $("#" + $(this).attr("to"));
            var fromcount = $(this).attr("fromcount");
            to.attr("value", AmountInWords(cleanSymbol(to.val()), fromcount)).change();
            var events = "moneyconvert_" + $(this).attr("from") + "__" + $(this).attr("to");
            to.attr("value", AmountInWords(cleanSymbol(from.val()), fromcount));
            addEvent("keyup change", from.attr("id"), events,
                function() {
                    var newVla = AmountInWords(cleanSymbol(from.val()), fromcount);
                    if (typeof(newVla) == "object") {
                        newVla = newVla.msg;
                        from.attr("isvalid", false)
                    } else {
                        from.attr("isvalid", true)
                    }
                    to.attr("value", newVla).change()
                })
        })
    };
    var changeAutoFormat = function(el, change, isReturn) {
        var format = el.attr("autoformat");
        var val = el.val();
        var value = "";
        switch (format) {
            case "#":
                value = formatNumber(val, false, 0, false, false);
                break;
            case "#,###":
                value = formatNumber(val, true, 0, false, false);
                break;
            case "#.#":
                value = formatNumber(val, false, 1, false, false);
                break;
            case "#.##":
                value = formatNumber(val, false, 2, false, false);
                break;
            case "#.###":
                value = formatNumber(val, false, 3, false, false);
                break;
            case "#,###.#":
                value = formatNumber(val, true, 1, false, false);
                break;
            case "#,###.##":
                value = formatNumber(val, true, 2, false, false);
                break;
            case "#,###.###":
                value = formatNumber(val, true, 3, false, false);
                break;
            case "#.0":
                value = formatNumber(val, false, 1, true, false);
                break;
            case "#.00":
                value = formatNumber(val, false, 2, true, false);
                break;
            case "#.000":
                value = formatNumber(val, false, 3, true, false);
                break;
            case "#,###.0":
                value = formatNumber(val, true, 1, true, false);
                break;
            case "#,###.00":
                value = formatNumber(val, true, 2, true, false);
                break;
            case "#,###.000":
                value = formatNumber(val, true, 3, true, false);
                break;
            case "#.####":
                value = formatNumber(val, false, 4, false, false);
                break;
            default:
                value = validateDateTime(val, format);
                break
        }
        if (isReturn) {
            return value
        }
        el.attr("isvalid", true);
        if (value === false) {
            el.attr("isvalid", false);
            if (el.attr("otype") != "calculate") {}
        } else {
            if (value != 0) {
                el.attr("isvalid", true);
                el.val(value);
                if (change) {
                    el.change()
                }
            }
        }
    };
    var formatAutoFormat = function() {
        return;
        var fields = $(".wf_form_content input:[autoformat]");
        fields.each(function() {
            changeAutoFormat($(this), true)
        })
    };
    var bindAutoFormat = function() {
        return;
        var fields = $(".wf_form_content input:[autoformat]");
        fields.each(function() {
            var field = $(this, true);
            changeAutoFormat(field);
            field.unbind("blur.b keyup.b").bind("blur.b keyup.b",
                function() {
                    changeAutoFormat($(this), true)
                })
        })
    };
    var bindMinMaxNumber = function() {
        var fields = $(".wf_form_content input[maxnum]");
        fields.each(function() {
            var field = $(this, true);
            field.unbind("blur.c keyup.i").bind("blur.c keyup.i",
                function() {
                    var maxnum = field.attr("maxnum"),
                        maxnumtext = field.attr("maxnumtext"),
                        value = $(this).val().replace(/,/ig, "");
                    if (parseInt(value, 10) > parseInt(maxnum, 10)) {
                        CNOA.msg.alert(lang("controls") + ": [" + field.attr("oname") + "]" + lang("valueCanNotDaYu") + (maxnumtext ? ("[" + maxnumtext + "]") : "") + lang("value") + "：" + maxnum + "，" + lang("pleaseCheck"),
                            function() {
                                field.focus()
                            })
                    }
                })
        })
    };
    var cleanSymbol = function(val) {
        val = val + "";
        try {
            val = val.replace(/,/g, "")
        } catch(e) {
            val = 0
        }
        return Number(val)
    };
    var removeRow = function(tr) {
        tr.children("td").children("input").each(function() {
            $(this).val(0).change()
        });
        tr.remove()
    };
    var bindDetailtable = function() {
        var detail_trs = $(".wf_form_content tr[class=detail-line]");
        detail_trs.parents("table").css("border", "none");
        var dtel = [];
        detail_trs.each(function() {
            dtel[$(this).attr("detail")] = dtel[$(this).attr("detail")] ? dtel[$(this).attr("detail")] + 1 : 1
        });
        detail_trs.each(function() {
            detail_list["d_" + $(this).attr("detail")] = {
                id: $(this).attr("detail"),
                linemax: dtel[$(this).attr("detail")],
                rowmax: dtel[$(this).attr("detail")]
            }
        });
        detail_trs.each(function() {
            var th = $(this),
                detailid = th.attr("detail"),
                trHtml = th.attr("outerHTML");
            th.find("img[class=wf_row_jian]").unbind("click").bind("click",
                function() {
                    var parent = th.parent();
                    removeRow(th);
                    var j = 1;
                    var rows = parent.children("tr[detail]").each(function() {
                        var rw = $(this);
                        var snumber1 = rw.find("input[snumber]");
                        try {
                            snumber1.attr("value", snumber1.attr("snumber").replace("{x}", j))
                        } catch(e) {}
                        detail_list["d_" + detailid].linemax = j;
                        j++
                    });
                    bindRowCalculate()
                });
            th.find("img[class=wf_row_jia]").bind("click",
                function() {
                    var data = [];
                    th.find("input,textarea,select").each(function() {
                        var ipt = $(this);
                        data.push({
                            id: ipt.attr("id"),
                            checked: ipt.attr("checked"),
                            value: ipt.attr("value")
                        })
                    });
                    detail_list["d_" + detailid].linemax = detail_list["d_" + detailid].linemax + 1;
                    detail_list["d_" + detailid].rowmax = detail_list["d_" + detailid].rowmax + 1;
                    var tr1 = $(trHtml);
                    tr1.attr("rownum", detail_list["d_" + detailid].rowmax);
                    tr1.insertAfter(th.parent().children("tr[class=detail-line][detail=" + detailid + "]:last"));
                    var snumber = tr1.find("input[snumber]");
                    try {
                        snumber.attr("value", snumber.attr("snumber").replace("{x}", detail_list["d_" + detailid].linemax))
                    } catch(e) {}
                    for (var i = 0; i < data.length; i++) {
                        try {
                            th.find("#" + data[i].id).attr("value", data[i].value);
                            if (data[i].checked) {
                                th.find("#" + data[i].id).attr("checked", data[i].checked)
                            }
                        } catch(e) {}
                    }
                    rmax = detail_list["d_" + detailid].rowmax;
                    tr1.find("input,textarea,select").each(function() {
                        var input = $(this),
                            inputname = input.attr("name").replace("wf_detail_1_", "wf_detail_" + rmax + "_").replace("wf_detailC_1_", "wf_detailC_" + rmax + "_").replace("wf_detailJ_1_", "wf_detailJ_" + rmax + "_").replace("wf_detailbid_1", "wf_detailbid_" + rmax),
                            inputid = input.attr("id").replace("wf_detail_1", "wf_detail_" + rmax).replace("wf_detailbid_1", "wf_detailbid_" + rmax);
                        input.attr("name", inputname);
                        input.attr("id", inputid);
                        try {
                            inputtoid = input.attr("to").replace("wf_detail_1", "wf_detail_" + rmax).replace("wf_detailbid_1", "wf_detailbid_" + rmax);
                            input.attr("to", inputtoid)
                        } catch(e) {}
                        if (input.attr("gid")) {
                            var inputgid = input.attr("gid").replace("wf_detail_1", "wf_detail_" + rmax);
                            input.attr("gid", inputgid)
                        }
                        if (input.attr("detailbindid")) {
                            input.val("")
                        }
                        if (input.parent("label")) {
                            input.parent("label").attr("for", input.attr("id").replace("wf_detail_1", "wf_detail_" + rmax))
                        }
                        var tag = $(this).attr("tagName");
                        var type = $(this).attr("type");
                        var dvalue = $(this).attr("dvalue");
                        if (tag == "INPUT" && type == "hidden") {
                            if ($(this).attr("moneyconvert") == "true") {
                                input.attr("from", input.attr("from").replace("wf_detail_1", "wf_detail_" + rmax));
                                input.attr("to", input.attr("to").replace("wf_detail_1", "wf_detail_" + rmax));
                                bindMoneyConvert()
                            }
                        }
                        if ((tag == "INPUT" && type == "text") || tag == "TEXTAREA") {
                            if (dvalue != undefined) {
                                $(this).val(dvalue)
                            } else {
                                if ($(this).attr("otype") !== "macro") {
                                    $(this).val("")
                                }
                            }
                        }
                        if (tag == "INPUT" && (type == "checkbox" || type == "radio")) {
                            if (dvalue == "true") {
                                $(this).attr("checked", true)
                            } else {
                                $(this).attr("checked", false)
                            }
                        }
                        if (tag == "SELECT") {
                            $(this).find("option").each(function() {
                                if ($(this).attr("dvalue") == "true") {
                                    $(this).attr("selected", "selected")
                                } else {
                                    $(this).removeAttr("selected")
                                }
                            })
                        }
                    });
                    tr1.find("img[class=wf_row_jia]").attr("src", "resources/images/cnoa/wf-dt-jian.gif").unbind("click").bind("click",
                        function() {
                            removeRow(tr1);
                            var j = 1;
                            var rows = th.parent().children("tr[class=detail-line][detail=" + detailid + "]").each(function() {
                                var rw = $(this);
                                var snumber1 = rw.find("input[snumber]");
                                try {
                                    snumber1.attr("value", snumber1.attr("snumber").replace("{x}", j))
                                } catch(e) {}
                                detail_list["d_" + detailid].linemax = j;
                                j++
                            })
                        }).attr("ext:qtip", lang("delThisLink")).show();
                    bindOneRowCalculate(tr1, rmax)
                })
        })
    };
    var bindElementSelectEvent = function() {
        $("#" + formPanelId).bind("click.selectall",
            function(e) {
                try {
                    if (e.target.tagName == "INPUT" || e.target.tagName == "TEXTAREA") {
                        var el = $(e.target);
                        if (el.attr("isnum") == "true") {
                            if (!elIsSelected(el)) {
                                el.select()
                            }
                        }
                    }
                } catch(e) {}
            })
    };
    var initRadioCheckboxShowHide = function() {
        var els = $(".wf_form_content input:hidden:[showhide=true]");
        var flag = true;
        els.each(function() {
            var shows, hides, stmp, htmp;
            shows = stmp = $(this).attr("display").split(",");
            hides = htmp = $(this).attr("undisplay").split(",");
            if ($(this).attr("fromtype") == "checkbox" && $(this).attr("checkboxck") == "1") {
                flag = false;
                try {
                    for (var i = 0; i < shows.length; i++) {
                        $(".wf_form_content span[class=group][oname=" + shows[i] + "]").attr("showhide", "show").show()
                    }
                } catch(e) {}
                try {
                    for (var i = 0; i < hides.length; i++) {
                        $(".wf_form_content span[class=group][oname=" + hides[i] + "]").attr("showhide", "hide").hide()
                    }
                } catch(e) {}
            }
        });
        if (flag) {
            $(".wf_form_content span[class=group]").each(function() {
                $(this).attr("showhide", "hide").hide()
            })
        }
    };
    var to1bits = function(flt) {
        if (parseFloat(flt) == flt) {
            return Math.round(flt * 1000000) / 1000000
        }
    };
    var roundResult = function(result, roundtype, baoliu) {
        if (roundtype == "round") {
            return result.toFixed(baoliu)
        } else {
            if (roundtype == "add") {
                var diejiaNum = 1;
                for (var i = 0; i < baoliu; i++) {
                    diejiaNum = diejiaNum * 10
                }
                result = Math.ceil(result * diejiaNum) / diejiaNum;
                return result
            } else {
                return result
            }
        }
    };
    var bindCalculate = function() {
        var calculates = $(".wf_form_content input:hidden:[calculate=true][detail=false]");
        var getValue = function(gongshi, roundType) {
            var string = "";
            var isDateCal = true;
            for (var i = 0; i < gongshi.length; i++) {
                if (gongshi[i].indexOf("wf|") != -1) {
                    var val = $("#wf_field_" + gongshi[i].replace("wf|", "")).val();
                    if (/^[0-9]{4,4}-[0-9]{2,2}-[0-9]{2,2} [0-9]{2,2}:[0-9]{2,2}$/ig.test(val)) {
                        val = strtotime(val)
                    } else {
                        isDateCal = false
                    }
                    if (val != undefined) {
                        val = cleanSymbol(val);
                        string += (val == "" ? 0 : val)
                    } else {
                        string += 0
                    }
                } else {
                    if (gongshi[i].indexOf("wfd|") != -1) {
                        var val = $("#wf_detail_column_" + gongshi[i].replace("wfd|", "")).val();
                        if (val != undefined) {
                            val = cleanSymbol(val);
                            string += (val == "" ? 0 : val)
                        } else {
                            string += 0
                        }
                    } else {
                        string += gongshi[i]
                    }
                }
            }
            try {
                eval("var rt = " + string + ";");
                rt = to1bits(rt);
                if (isDateCal) {
                    if (roundType == "hour") {
                        rt = secondtohour(rt)
                    } else {
                        if (roundType == "day") {
                            rt = secondtoday(rt)
                        } else {
                            if (roundType == "dayhour") {
                                rt = secondtodayhour(rt)
                            }
                        }
                    }
                }
            } catch(e) {
                var rt = lang("calculationError")
            }
            return rt
        };
        calculates.each(function() {
            var to = $("#" + $(this).attr("to"));
            var gongshi = Ext.decode($(this).attr("gongshi"));
            var fields = [];
            var detailFields = [];
            var string = "";
            var roundtype = $(this).attr("roundtype");
            var baoliu = $(this).attr("baoliu");
            for (var i = 0; i < gongshi.length; i++) {
                if (gongshi[i].indexOf("wf|") != -1) {
                    fields.push(gongshi[i].replace("wf|", ""))
                } else {
                    if (gongshi[i].indexOf("wfd|") != -1) {
                        detailFields.push(gongshi[i].replace("wfd|", ""))
                    }
                }
            }
            try {
                $("#wf_field_" + fields[0]).change()
            } catch(e) {}
            try {
                $("#wf_detail_column_" + detailFields[0]).change()
            } catch(e) {}
            for (var i = 0; i < fields.length; i++) {
                var field = $("#wf_field_" + fields[i]);
                var events = "keyup.c." + fields[i] + "." + to.attr("id") + " change.c." + fields[i] + "." + to.attr("id");
                field.unbind(events).bind(events,
                    function() {
                        try {
                            var result = roundResult(getValue(gongshi, roundtype), roundtype, baoliu);
                            if (roundtype == 2) {
                                if (!Ext.isEmpty($(to).attr("autoformat"))) {
                                    $(to).val(result);
                                    result = changeAutoFormat($(to), false, true)
                                }
                            }
                            to.attr("value", result).change()
                        } catch(e) {}
                    });
                field.change()
            }
            for (var i = 0; i < detailFields.length; i++) {
                var field = $("#wf_detail_column_" + detailFields[i]);
                var events = "keyup.d." + detailFields[i] + "." + to.attr("id") + " change.d." + detailFields[i] + "." + to.attr("id");
                field.unbind(events).bind(events,
                    function() {
                        try {
                            var result = roundResult(getValue(gongshi), roundtype, baoliu);
                            if (roundtype == 2) {
                                if (!Ext.isEmpty($(to).attr("autoformat"))) {
                                    $(to).val(result);
                                    result = changeAutoFormat($(to), false, true)
                                }
                            }
                            to.attr("value", result).change()
                        } catch(e) {}
                    })
            }
        })
    };
    var bindOneRowCalculate = function(tr, row) {
        var cals = tr.find("input[otype=calculate]");
        var getValue = function(gongshi, cu) {
            var string = "";
            for (var i = 0; i < gongshi.length; i++) {
                if (gongshi[i].indexOf("wf|") != -1) {
                    var val = $("#wf_field_" + gongshi[i].replace("wf|", "")).val();
                    if (val != undefined) {
                        val = cleanSymbol(val);
                        string += (val == "" ? 0 : val)
                    } else {
                        string += 0
                    }
                } else {
                    if (gongshi[i].indexOf("wfd|") != -1) {
                        var val = $("#wf_detail_" + row + "_" + gongshi[i].replace("wfd|", "")).val();
                        if (val != undefined) {
                            val = cleanSymbol(val);
                            string += (val == "" ? 0 : val)
                        } else {
                            string += 0
                        }
                    } else {
                        if (gongshi[i].indexOf("wfo|") != -1) {
                            var val = $("#wf_detail_column_" + gongshi[i].replace("wfo|", "")).val();
                            if (val != undefined) {
                                val = cleanSymbol(val);
                                string += (val == "" ? 0 : val)
                            } else {
                                string += 0
                            }
                        } else {
                            string += gongshi[i]
                        }
                    }
                }
            }
            try {
                eval("var rt = " + string + ";");
                rt = to1bits(rt)
            } catch(e) {
                var rt = lang("calculationError")
            }
            if (isNaN(rt)) {
                rt = 0
            }
            if (rt == Infinity) {
                rt = 0
            }
            return rt
        };
        cals.each(function() {
            var cu = $(this);
            var gongshi = Ext.decode(cu.attr("gongshi"));
            var roundtype = cu.attr("roundtype");
            var baoliu = cu.attr("baoliu");
            outFields = [];
            inFields = [];
            otherFields = [];
            for (var i = 0; i < gongshi.length; i++) {
                if (gongshi[i].indexOf("wf|") != -1) {
                    outFields.push(gongshi[i].replace("wf|", ""))
                } else {
                    if (gongshi[i].indexOf("wfd|") != -1) {
                        inFields.push(gongshi[i].replace("wfd|", ""))
                    } else {
                        if (gongshi[i].indexOf("wfo|") != -1) {
                            otherFields.push(gongshi[i].replace("wfo|", ""))
                        }
                    }
                }
            }
            cu.attr("value", getValue(gongshi, cu, tr)).change();
            for (var k = 0; k < inFields.length; k++) {
                var field = $("#wf_detail_" + row + "_" + inFields[k]);
                var events = "keyup.e." + cu.attr("id") + "_" + inFields[k] + " change.e." + cu.attr("id") + "_" + inFields[k];
                field.unbind(events).bind(events,
                    function() {
                        var result = roundResult(getValue(gongshi, cu, tr), roundtype, baoliu);
                        cu.attr("value", result).change()
                    })
            }
            for (var j = 0; j < outFields.length; j++) {
                var field = $("#wf_field_" + outFields[j]);
                var events = "keyup.f." + outFields[j] + "." + cu.attr("id") + " change.f." + outFields[j] + "." + cu.attr("id");
                field.unbind(events).bind(events,
                    function() {
                        var result = roundResult(getValue(gongshi, cu, tr), roundtype, baoliu);
                        cu.attr("value", result).change()
                    })
            }
            for (var l = 0; l < otherFields.length; l++) {
                var field = $("#wf_detail_column_" + otherFields[l]);
                var events = "keyup.g." + otherFields[l] + "." + cu.attr("id") + " change.g." + otherFields[l] + "." + cu.attr("id");
                field.unbind(events).bind(events,
                    function() {
                        var result = roundResult(getValue(gongshi, cu, tr), roundtype, baoliu);
                        cu.attr("value", result).change()
                    })
            }
        })
    };
    var bindRowCalculate = function() {
        var detail_trs = $(".wf_form_content tr[class=detail-line]");
        var getValue = function(gongshi, to, tr) {
            var row = tr.attr("rownum");
            var string = "";
            for (var i = 0; i < gongshi.length; i++) {
                if (gongshi[i].indexOf("wf|") != -1) {
                    var val = $("#wf_field_" + gongshi[i].replace("wf|", "")).val();
                    if (val != undefined) {
                        val = cleanSymbol(val);
                        string += (val == "" ? 0 : val)
                    } else {
                        string += 0
                    }
                } else {
                    if (gongshi[i].indexOf("wfd|") != -1) {
                        var val = $("#wf_detail_" + row + "_" + gongshi[i].replace("wfd|", "")).val();
                        if (val != undefined) {
                            val = cleanSymbol(val);
                            string += (val == "" ? 0 : val)
                        } else {
                            string += 0
                        }
                    } else {
                        if (gongshi[i].indexOf("wfo|") != -1) {
                            var val = $("#wf_detail_column_" + gongshi[i].replace("wfo|", "")).val();
                            if (val != undefined) {
                                val = cleanSymbol(val);
                                string += (val == "" ? 0 : val)
                            } else {
                                string += 0
                            }
                        } else {
                            string += gongshi[i]
                        }
                    }
                }
            }
            try {
                eval("var rt = " + string + ";");
                rt = to1bits(rt)
            } catch(e) {
                var rt = lang("calculationError")
            }
            if (isNaN(rt)) {
                rt = 0
            }
            if (rt == Infinity) {
                rt = 0
            }
            return rt
        };
        detail_trs.each(function() {
            var tr = $(this);
            var cuAll = tr.find("input[otype=calculate]");
            var rn = tr.attr("rownum");
            cuAll.each(function() {
                var cu = $(this);
                var gongshi = Ext.decode(cu.attr("gongshi"));
                var roundtype = cu.attr("roundtype");
                var baoliu = cu.attr("baoliu");
                outFields = [];
                inFields = [];
                otherFields = [];
                for (var i = 0; i < gongshi.length; i++) {
                    if (gongshi[i].indexOf("wf|") != -1) {
                        outFields.push(gongshi[i].replace("wf|", ""))
                    } else {
                        if (gongshi[i].indexOf("wfd|") != -1) {
                            inFields.push(gongshi[i].replace("wfd|", ""))
                        } else {
                            if (gongshi[i].indexOf("wfo|") != -1) {
                                otherFields.push(gongshi[i].replace("wfo|", ""))
                            }
                        }
                    }
                }
                for (var k = 0; k < inFields.length; k++) {
                    var field = $("#wf_detail_" + rn + "_" + inFields[k]);
                    var events = "keyup.e." + cu.attr("id") + "_" + inFields[k] + " change.e." + cu.attr("id") + "_" + inFields[k];
                    field.unbind(events).bind(events,
                        function() {
                            var result = roundResult(getValue(gongshi, cu, tr), roundtype, baoliu);
                            cu.attr("value", result).change()
                        })
                }
                for (var j = 0; j < outFields.length; j++) {
                    var field = $("#wf_field_" + outFields[j]);
                    var events = "keyup.f." + outFields[j] + "." + cu.attr("id") + " change.f." + outFields[j] + "." + cu.attr("id");
                    field.unbind(events).bind(events,
                        function() {
                            var result = roundResult(getValue(gongshi, cu, tr), roundtype, baoliu);
                            cu.attr("value", result).change()
                        })
                }
                for (var l = 0; l < otherFields.length; l++) {
                    var field = $("#wf_detail_column_" + otherFields[l]);
                    var events = "keyup.g." + otherFields[l] + "." + cu.attr("id") + " change.g." + otherFields[l] + "." + cu.attr("id");
                    field.unbind(events).bind(events,
                        function() {
                            var result = roundResult(getValue(gongshi, cu, tr), roundtype, baoliu);
                            cu.attr("value", result).change()
                        })
                }
            })
        })
    };
    var initColumnSum = function() {
        var columns = $(".wf_form_content input:[columnsum=true]");
        columns.each(function() {
            var hidden = $(this);
            var columnid = hidden.attr("columnid");
            var detailid = hidden.attr("detailid");
            var trs = $(".wf_form_content tr:[class=detail-line][detail=" + detailid + "]");
            trs.find("input:[name^=wf_detail_][name$=_" + columnid + "],input:[name^=wf_detailJ_][name$=_" + columnid + "]").each(function() {
                var string = "0";
                var val = $(this).val();
                string += "+";
                string += val ? cleanSymbol(val) : 0;
                try {
                    eval("var rt = " + string + ";");
                    rt = to1bits(rt)
                } catch(e) {
                    var rt = lang("calculationError")
                }
                hidden.attr("value", rt)
            })
        })
    };
    var bindColumnSum = function(event) {
        var nowel = $(event.target),
            fieldid = nowel.attr("field"),
            tr = nowel.parents("tr:[class=detail-line]"),
            detailtableid = tr.attr("detail"),
            hidden = $("input[detailid=" + detailtableid + "][columnsum=true][columnid=" + fieldid + "]");
        if (hidden.length > 0) {
            var string = "0";
            var els = $("tr[detail]").find("input[field=" + fieldid + "]").each(function() {
                var val = $(this).val();
                string += "+";
                string += val ? cleanSymbol(val) : 0
            });
            try {
                eval("var rt = " + string + ";");
                rt = to1bits(rt)
            } catch(e) {
                var rt = lang("calculationError")
            }
            hidden.attr("value", rt).change()
        }
    };
    var makeColumnSumChange = function() {
        var columns = $(".wf_form_content input:[columnsum=true]");
        columns.each(function() {
            var hidden = $(this);
            var columnid = hidden.attr("columnid");
            var detailid = hidden.attr("detailid");
            var trs = $(".wf_form_content tr:[class=detail-line][detail=" + detailid + "]");
            setTimeout(function() {
                    trs.find("[name^=wf_detail_1_" + columnid + "]").change();
                    trs.find("[name^=wf_detailJ_1_" + columnid + "]").change()
                },
                50)
        })
    };
    var bindQueryButton = function(uFlowId, flowId) {
        var button = $(".wf_form_content img:[class=wf_list_query][query=true]");
        var bindfuncs = [];
        button.each(function() {
            bindfuncs.push($(this).attr("querykey"));
            $(this).click(function() {
                var wfquerywindow = new CNOA.wf.query($(this).attr("detail"), $(this).attr("bindfunc"));
                wfquerywindow.show()
            })
        });
        for (var i = 0,
                 len = $.unique(bindfuncs).length; i < len; ++i) {
            loadJs("cnoa/app/wf/scripts/query." + bindfuncs[i] + ".js", true)
        }
        var bindfuncs = $(".wf_form_content a[otype=bindfunc]");
        bindfuncs.each(function() {
            var val = $(this).attr("value");
            if (val == "salaryApprove") {
                var salaryApproveId = 0;
                salaryApproveId = $(".wf_form_content a[otype=salaryApproveId]").attr("value");
                loadJs("cnoa/app/wf/scripts/query.businessData.js", true,
                    function() {
                        eval("queryBusinessData('" + val + "','" + salaryApproveId + "','" + uFlowId + "');")
                    })
            } else {
                if (val == "userReadlySubmit" || val == "userSubmit") {
                    var userCid = 0;
                    userCid = $(".wf_form_content a[otype=userCid]").attr("value");
                    loadJs("cnoa/app/wf/scripts/query.businessData.js", true,
                        function() {
                            eval("queryBusinessData('" + val + "','" + userCid + "','" + uFlowId + "');")
                        })
                } else {
                    loadJs("cnoa/app/wf/scripts/query.businessData.js", true,
                        function() {
                            eval("queryBusinessData('" + val + "','','" + uFlowId + "','" + flowId + "');")
                        })
                }
            }
        })
    };
    this.bindMinMaxNumber = bindMinMaxNumber;
    var bindSignature = function() {
        var img = $("img[signaturetype=graph]");
        var btnSignature = $(".wf_form_content input:[type=button][otype=signature]");
        var hasgraph = false;
        var haselectron = false;
        btnSignature.each(function() {
            var signaturetype = $(this).attr("signaturetype");
            if (signaturetype == "graph") {
                hasgraph = true
            }
            if (signaturetype == "electron") {
                haselectron = true
            }
        });
        if (hasgraph) {
            loadJs("cnoa/app/wf/scripts/signature.graph.js", true)
        }
        if (haselectron) {
            loadJs("cnoa/app/wf/scripts/signature.electron.js", true,
                function() {
                    CNOA_wf_signature_electron = new CNOA_signature_electronClass()
                })
        }
        btnSignature.each(function() {
            var me = $(this);
            var signaturetype = $(this).attr("signaturetype");
            me.unbind("click").bind("click",
                function() {
                    if (signaturetype == "graph") {
                        CNOA_wf_signature_graph = new CNOA_signature_graphClass(me);
                        CNOA_wf_signature_graph.show()
                    } else {
                        if (signaturetype == "electron") {
                            CNOA_wf_signature_electron.show(me)
                        }
                    }
                })
        });
        img.each(function() {
            var me = $(this);
            var signaturetype = $(this).attr("signaturetype");
            loadJs("cnoa/app/wf/scripts/signature." + signaturetype + ".js", true,
                function() {
                    CNOA_wf_signature_graph = new CNOA_signature_graphClass(me);
                    CNOA_wf_signature_graph.bindImg(me)
                })
        })
    };
    var bindDatasource = function() {
        var ds = $("input[otype=datasource]");
        if (ds.length > 0) {
            loadJs("cnoa/app/wf/scripts/query.datasource.js", true,
                function() {
                    ds.each(function() {
                        $(this).click(function() {
                            var wfquerywindow = new CNOA.wf.datasource($(this).attr("fieldId"), $(this).attr("datasource"), $(this).attr("maps"));
                            wfquerywindow.show()
                        });
                        var maps = Ext.decode($(this).attr("maps")) || {};
                        if (Ext.isObject(maps)) {
                            for (var m in maps) {
                                $("#" + maps[m]).attr("readOnly", "readOnly")
                            }
                        }
                        if (Ext.isArray(maps)) {
                            Ext.each(maps,
                                function(v) {
                                    if (v.editable != "1") {
                                        $("#" + v.des).attr("readOnly", "readOnly")
                                    }
                                })
                        }
                    })
                })
        }
    };
    var bindSignData = function() {
        var sealData = $(".wf_form_content input[sealstoredata=true]");
        if (sealData.length > 0) {
            loadJs("cnoa/app/wf/scripts/signature.electron.js", true,
                function() {
                    CNOA_wf_signature_electron = new CNOA_signature_electronClass();
                    CNOA_wf_signature_electron.SetSealValue(sealData)
                })
        }
    };
    var bindBudgetdept = function() {
        var budgetDept = $(".wf_form_content input[budgetDept=true]");
        if (budgetDept.length > 0) {
            budgetDept.click(function() {
                var me = this,
                    th = $(me),
                    id = th.attr("to"),
                    sum = th.val(),
                    deptName = th.attr("deptname"),
                    deptId = $("[name=wf_budgetDept_" + id + "]").val(),
                    dept_ID = Ext.id(),
                    budget_ID = Ext.id(),
                    balance_ID = Ext.id(),
                    balance;
                var getBalance = function(deptId) {
                        Ext.Ajax.request({
                            url: "index.php?app=wf&func=flow&action=use&modul=getBalanceByDeptId",
                            params: {
                                deptId: deptId
                            },
                            success: function(response) {
                                balance = Ext.decode(response.responseText) || 0;
                                Ext.getCmp(budget_ID).setDisabled(false);
                                Ext.getCmp(balance_ID).setText("<b style='color:#ff0000'>" + lang("availableAmount") + "：" + balance + "</b>", false)
                            }
                        })
                    },
                    getFieldsId = function(name) {
                        Ext.Ajax.request({
                            url: "index.php?app=wf&func=flow&action=use&modul=getFieldsId",
                            method: "post",
                            params: {
                                fieldsId: id
                            },
                            success: function(response) {
                                var response = Ext.decode(response.responseText);
                                if (response.failure) {
                                    CNOA.msg.alert(response.msg)
                                } else {
                                    var fieldId = response.data.fieldId;
                                    $("#wf_field_" + fieldId).val(name)
                                }
                            }
                        })
                    },
                    form = new Ext.form.FormPanel({
                        padding: 10,
                        labelAlign: "right",
                        labelWidth: 70,
                        cls: "cnoa_form",
                        items: [{
                            xtype: "textfield",
                            readOnly: true,
                            allowBlank: false,
                            fieldLabel: lang("department"),
                            emptyText: lang("pleaseSelectDept"),
                            name: "deptName",
                            listeners: {
                                render: function(th) {
                                    th.to = dept_ID
                                },
                                focus: function(th) {
                                    people_selector("dept", th, false, getBalance)
                                }
                            }
                        },
                            {
                                xtype: "hidden",
                                name: "deptId",
                                id: dept_ID
                            },
                            {
                                xtype: "compositefield",
                                id: budget_ID,
                                fieldLabel: lang("amountRequest"),
                                allowBlank: false,
                                disabled: true,
                                items: [{
                                    xtype: "textfield",
                                    allowBlank: false,
                                    regex: /^\d+$|^\d+.\d{0,4}$/,
                                    name: "sum"
                                },
                                    {
                                        xtype: "label",
                                        id: balance_ID
                                    }]
                            }]
                    }),
                    win = new Ext.Window({
                        title: lang("deptBudget"),
                        border: false,
                        width: 380,
                        height: 180,
                        layout: "fit",
                        modal: true,
                        items: form,
                        buttons: [{
                            text: lang("ok"),
                            handler: function() {
                                var f = form.getForm(),
                                    values = f.getValues(),
                                    sum = values.sum,
                                    deptName = values.deptName;
                                if (sum == "") {
                                    CNOA.msg.alert(lang("appAmountEmpty"));
                                    return
                                }
                                if (parseFloat(sum) > parseFloat(balance)) {
                                    CNOA.msg.alert(lang("appMoneyNotExceed"));
                                    return
                                }
                                th.val(sum).change();
                                th.attr("ext:qtip", lang("deptBudget") + "<br/>" + lang("department") + ":" + deptName + "<br/>" + lang("amount") + ":" + sum);
                                th.attr("deptname", deptName);
                                $("input[name=wf_budgetDept_" + id + "][budgetDeptId=true]").val(f.findField("deptId").getValue());
                                getFieldsId(deptName);
                                win.close()
                            }
                        },
                            {
                                text: lang("cancel"),
                                handler: function() {
                                    win.close()
                                }
                            }]
                    }).show();
                form.getForm().setValues({
                    sum: sum,
                    deptId: deptId,
                    deptName: deptName
                });
                if (deptId) {
                    getBalance(deptId)
                }
            })
        }
    };
    var bindBudgetproj = function() {
        var budgetProj = $(".wf_form_content input[budgetProj=true]");
        if (budgetProj.length > 0) {
            budgetProj.click(function() {
                var me = this,
                    th = $(me),
                    id = th.attr("to"),
                    proj_sum = th.val(),
                    projName = th.attr("projname"),
                    projId = $("[name=wf_budgetProj_" + id + "]").val(),
                    deptId = $("[name=wf_budgetProj_dept_" + id + "]").val(),
                    proj_ID = Ext.id(),
                    budget_proj_ID = Ext.id(),
                    balance_proj_ID = Ext.id(),
                    balance_proj;
                var getProjBalance = function(projId) {
                        Ext.Ajax.request({
                            url: "index.php?app=wf&func=flow&action=use&modul=getBalanceByProjId",
                            params: {
                                projId: projId
                            },
                            success: function(response) {
                                balance_proj = Ext.decode(response.responseText) || 0;
                                Ext.getCmp(budget_proj_ID).setDisabled(false);
                                Ext.getCmp(balance_proj_ID).setText("<b style='color:#ff0000'>" + lang("availableAmount") + "：" + balance_proj + "</b>", false)
                            }
                        })
                    },
                    getFieldsId = function(name) {
                        Ext.Ajax.request({
                            url: "index.php?app=wf&func=flow&action=use&modul=getFieldsId",
                            method: "post",
                            params: {
                                fieldsId: id
                            },
                            success: function(response) {
                                var response = Ext.decode(response.responseText);
                                if (response.failure) {
                                    CNOA.msg.alert(response.msg)
                                } else {
                                    var fieldId = response.data.fieldId;
                                    $("#wf_field_" + fieldId).val(name)
                                }
                            }
                        })
                    },
                    projform = new Ext.form.FormPanel({
                        padding: 10,
                        labelAlign: "right",
                        labelWidth: 70,
                        cls: "cnoa_form",
                        items: [{
                            xtype: "combo",
                            editable: false,
                            allowBlank: false,
                            fieldLabel: "项目",
                            typeAhead: true,
                            triggerAction: "all",
                            lazyRender: true,
                            mode: "local",
                            hiddenName: "projId",
                            valueField: "projId",
                            displayField: "name",
                            id: proj_ID,
                            store: new Ext.data.Store({
                                autoLoad: true,
                                baseParams: {
                                    deptId: deptId
                                },
                                proxy: new Ext.data.HttpProxy({
                                    url: "index.php?app=wf&func=flow&action=use&modul=getProjCombo",
                                    disableCaching: true
                                }),
                                reader: new Ext.data.JsonReader({
                                    totalProperty: "total",
                                    root: "data",
                                    fields: ["projId", "name"]
                                })
                            }),
                            listeners: {
                                select: function(combo, record, index) {
                                    projId = record.json.projId;
                                    projform.getForm().setValues({
                                        proj_sum: proj_sum,
                                        projId: projId,
                                        projName: projName
                                    });
                                    if (projId) {
                                        getProjBalance(projId)
                                    }
                                }
                            }
                        },
                            {
                                xtype: "compositefield",
                                id: budget_proj_ID,
                                fieldLabel: lang("amountRequest"),
                                allowBlank: false,
                                disabled: true,
                                items: [{
                                    xtype: "textfield",
                                    allowBlank: false,
                                    regex: /^\d+$|^\d+.\d{0,4}$/,
                                    name: "proj_sum"
                                },
                                    {
                                        xtype: "label",
                                        id: balance_proj_ID
                                    }]
                            }]
                    }),
                    proj_win = new Ext.Window({
                        title: "项目预算",
                        border: false,
                        width: 380,
                        height: 150,
                        layout: "fit",
                        modal: true,
                        items: projform,
                        buttons: [{
                            text: lang("ok"),
                            handler: function() {
                                var f = projform.getForm(),
                                    values = f.getValues(),
                                    proj_sum = values.proj_sum,
                                    proj_Id = values.projId;
                                projName = Ext.getCmp(proj_ID).getRawValue();
                                if (proj_sum == "") {
                                    CNOA.msg.alert(lang("appAmountEmpty"));
                                    return
                                }
                                if (parseFloat(proj_sum) > parseFloat(balance_proj)) {
                                    CNOA.msg.alert(lang("appMoneyNotExceed"));
                                    return
                                }
                                th.val(proj_sum).change();
                                th.attr("ext:qtip", "项目预算:<br/>项目:" + projName + "<br/>金额:" + proj_sum);
                                th.attr("projname", projName);
                                $("input[name=wf_budgetProj_" + id + "][budgetProjId=true]").val(proj_Id);
                                getFieldsId(projName);
                                proj_win.close()
                            }
                        },
                            {
                                text: lang("cancel"),
                                handler: function() {
                                    proj_win.close()
                                }
                            }]
                    }).show();
                projform.getForm().setValues({
                    proj_sum: proj_sum,
                    projId: projId
                });
                Ext.getCmp(proj_ID).setRawValue(projName);
                if (projId) {
                    getProjBalance(projId)
                }
            })
        }
    };
    var bindAttLeave = function() {
        var attLeave = $(".wf_form_content input[attLeave=true]");
        if (attLeave.length > 0) {
            attLeave.click(function() {
                var me = this,
                    th = $(me),
                    id = th.attr("to"),
                    sum = th.val(),
                    leaveId = $("[name=wf_attLeave_" + id + "]").val(),
                    leave_ID = Ext.id(),
                    leave_ID = Ext.id();
                var getBalance = function(stime, etime) {
                        Ext.Ajax.request({
                            url: "index.php?app=wf&func=flow&action=use&modul=getAttLeaveDays",
                            method: "post",
                            params: {
                                stime: stime,
                                etime: etime,
                                fieldsId: id
                            },
                            success: function(response) {
                                var response = Ext.decode(response.responseText);
                                if (response.failure) {
                                    CNOA.msg.alert(response.msg)
                                } else {
                                    var data = response.data,
                                        stime = data.stime,
                                        etime = data.etime,
                                        days = data.days,
                                        fieldId = data.fieldId,
                                        value = stime + " " + lang("to") + " " + etime;
                                    th.val(value);
                                    $("input[name=wf_attLeave_" + id + "][attLeave=true]").val(value);
                                    $("#wf_field_" + fieldId).val(days);
                                    win.close()
                                }
                            }
                        })
                    },
                    workStarTime = new Ext.ux.form.DateTimeField({
                        fieldLabel: lang("startTime"),
                        format: "Y-m-d H:i",
                        value: new Date()
                    }),
                    workEndTime = new Ext.ux.form.DateTimeField({
                        fieldLabel: lang("endTime"),
                        format: "Y-m-d H:i",
                        value: new Date()
                    }),
                    form = new Ext.form.FormPanel({
                        padding: 10,
                        labelAlign: "right",
                        labelWidth: 70,
                        cls: "cnoa_form",
                        items: [workStarTime, workEndTime]
                    }),
                    win = new Ext.Window({
                        title: lang("leaveTime"),
                        border: false,
                        width: 380,
                        height: 150,
                        layout: "fit",
                        modal: true,
                        items: form,
                        buttons: [{
                            text: lang("ok"),
                            handler: function() {
                                var stime = workStarTime.getValue(),
                                    etime = workEndTime.getValue();
                                getBalance(stime, etime)
                            }
                        },
                            {
                                text: lang("cancel"),
                                handler: function() {
                                    win.close()
                                }
                            }]
                    }).show()
            })
        }
    };
    var bindAttEvectionAllDays = function() {
        var attEvection = $(".wf_form_content input[attEvection=true]");
        if (attEvection.length > 0) {
            attEvection.click(function() {
                var me = this,
                    th = $(me),
                    id = th.attr("to"),
                    sum = th.val(),
                    leaveId = $("[name=wf_attEvection_" + id + "]").val(),
                    leave_ID = Ext.id(),
                    leave_ID = Ext.id();
                var getBalance = function(stime, etime) {
                        Ext.Ajax.request({
                            url: "index.php?app=wf&func=flow&action=use&modul=getAttEvectionDays",
                            method: "post",
                            params: {
                                stime: stime,
                                etime: etime,
                                fieldsId: id
                            },
                            success: function(response) {
                                var response = Ext.decode(response.responseText);
                                if (response.failure) {
                                    CNOA.msg.alert(response.msg)
                                } else {
                                    var data = response.data,
                                        stime = data.stime,
                                        etime = data.etime,
                                        days = data.days,
                                        fieldId = data.fieldId,
                                        value = stime + " " + lang("to") + " " + etime;
                                    th.val(value);
                                    $("input[name=wf_attEvection_" + id + "][attEvection=true]").val(value);
                                    $("#wf_field_" + fieldId).val(days);
                                    win.close()
                                }
                            }
                        })
                    },
                    workStarTime = new Ext.ux.form.DateTimeField({
                        fieldLabel: lang("startTime"),
                        format: "Y-m-d H:i",
                        value: new Date()
                    }),
                    workEndTime = new Ext.ux.form.DateTimeField({
                        fieldLabel: lang("endTime"),
                        format: "Y-m-d H:i",
                        value: new Date()
                    }),
                    form = new Ext.form.FormPanel({
                        padding: 10,
                        labelAlign: "right",
                        labelWidth: 70,
                        cls: "cnoa_form",
                        items: [workStarTime, workEndTime]
                    }),
                    win = new Ext.Window({
                        title: lang("evection"),
                        border: false,
                        width: 380,
                        height: 150,
                        layout: "fit",
                        modal: true,
                        items: form,
                        buttons: [{
                            text: lang("ok"),
                            handler: function() {
                                var stime = workStarTime.getValue(),
                                    etime = workEndTime.getValue();
                                getBalance(stime, etime)
                            }
                        },
                            {
                                text: lang("cancel"),
                                handler: function() {
                                    win.close()
                                }
                            }]
                    }).show()
            })
        }
    };
    var bindAttTime = function() {
        var attTime = $(".wf_form_content input[attTime=true]");
        if (attTime.length > 0) {
            attTime.click(function() {
                var me = this,
                    th = $(me),
                    id = th.attr("to"),
                    sum = th.val(),
                    leaveId = $("[name=wf_attTime_" + id + "]").val(),
                    leave_ID = Ext.id(),
                    leave_ID = Ext.id();
                var getBalance = function(stime, etime) {
                        Ext.Ajax.request({
                            url: "index.php?app=wf&func=flow&action=use&modul=getAttTime",
                            method: "post",
                            params: {
                                stime: stime,
                                etime: etime,
                                fieldsId: id
                            },
                            success: function(response) {
                                var response = Ext.decode(response.responseText);
                                if (response.failure) {
                                    CNOA.msg.alert(response.msg)
                                } else {
                                    var data = response.data,
                                        stime = data.stime,
                                        etime = data.etime,
                                        days = data.days,
                                        fieldId = data.fieldId,
                                        value = stime + " " + lang("to") + " " + etime;
                                    hour = data.hour,
                                        fieldId2 = data.fieldId2,
                                        th.val(value);
                                    $("input[name=wf_attTime_" + id + "][attTime=true]").val(value);
                                    $("#wf_field_" + fieldId).val(days);
                                    $("#wf_field_" + fieldId2).val(hour);
                                    win.close()
                                }
                            }
                        })
                    },
                    workStarTime = new Ext.ux.form.DateTimeField({
                        fieldLabel: lang("startTime"),
                        format: "Y-m-d H:i",
                        value: new Date()
                    }),
                    workEndTime = new Ext.ux.form.DateTimeField({
                        fieldLabel: lang("endTime"),
                        format: "Y-m-d H:i",
                        value: new Date()
                    }),
                    form = new Ext.form.FormPanel({
                        padding: 10,
                        labelAlign: "right",
                        labelWidth: 70,
                        cls: "cnoa_form",
                        items: [workStarTime, workEndTime]
                    }),
                    win = new Ext.Window({
                        title: "考勤时间",
                        border: false,
                        width: 380,
                        height: 150,
                        layout: "fit",
                        modal: true,
                        items: form,
                        buttons: [{
                            text: lang("ok"),
                            handler: function() {
                                var stime = workStarTime.getValue(),
                                    etime = workEndTime.getValue();
                                getBalance(stime, etime)
                            }
                        },
                            {
                                text: lang("cancel"),
                                handler: function() {
                                    win.close()
                                }
                            }]
                    }).show()
            })
        }
    };
    var initRichText = function() {
        var els = $(".wf_form_content input:hidden:[richtext=true]");
        if (els.length > 0) {
            loadJs("cnoa/scripts/editor/nicedit.min.js", true,
                function() {
                    els.each(function() {
                        var from = $(this).attr("from");
                        var fromel = $("#" + from);
                        new nicEditor({
                            maxHeight: fromel.height() + 10
                        }).panelInstance(from)
                    })
                })
        }
    };
    this.formInitForView = function() {
        bindSignData();
        initRadioCheckboxShowHide();
        initRichText()
    };
    this.formInit = function(id, uFlowId, flowId) {
        formPanelId = id;
        bindDetailtable();
        bindMoneyConvert();
        bindRowCalculate();
        initColumnSum();
        bindCalculate();
        bindQueryButton(uFlowId, flowId);
        bindMinMaxNumber();
        bindSignature();
        bindBudgetdept();
        bindBudgetproj();
        bindAttLeave();
        bindAttEvectionAllDays();
        bindAttTime();
        bindDatasource();
        bindElementSelectEvent();
        initRadioCheckboxShowHide();
        initRichText();
        makeColumnSumChange();
        $("#" + formPanelId).bind("keyup",
            function(event) {
                try {
                    for (var i in ckme.eventList.keyup) {
                        if (event.target.id == ckme.eventList.keyup[i].tid) {
                            ckme.eventList.keyup[i].func.call(this)
                        }
                    }
                } catch(e) {}
                try {
                    bindColumnSum(event)
                } catch(e) {}
                var el = event.target;
                with(el) {
                    if (tagName == "INPUT" && type == "text") {
                        if (!Ext.isEmpty($(el).attr("autoformat"))) {
                            changeAutoFormat($(el), true)
                        }
                    }
                }
            });
        $("#" + formPanelId).bind("change",
            function(event) {
                try {
                    for (var i in ckme.eventList.change) {
                        if (event.target.id == ckme.eventList.change[i].tid) {
                            ckme.eventList.change[i].func.call(this)
                        }
                    }
                } catch(e) {}
                try {
                    bindColumnSum(event)
                } catch(e) {}
                var el = event.target;
                if ($(el).attr("otype") == "calculate") {
                    if (!Ext.isEmpty($(el).attr("autoformat"))) {
                        changeAutoFormat($(el), false)
                    }
                }
            })
    };
    this.rsyncCheckbox = function(checkbox) {
        var source = $(checkbox);
        var target = $("#" + $(checkbox).attr("checkboxid"));
        if (checkbox.checked) {
            target.val(source.val())
        } else {
            target.val("")
        }
    };
    this.showHide = function(shows, hides, objcfg) {
        var stmp, htmp;
        if (shows) {
            stmp = shows = shows.split(",")
        }
        if (hides) {
            htmp = hides = hides.split(",")
        }
        if (objcfg.checkbox) {
            if (objcfg.checkbox.checked == false) {
                shows = htmp;
                hides = stmp
            }
        }
        if (objcfg.checkboxid) {
            checkbox = $("#" + objcfg.checkboxid).get(0);
            if (checkbox.checked == false) {
                shows = htmp;
                hides = stmp
            }
        }
        try {
            for (var i = 0; i < shows.length; i++) {
                $(".wf_form_content span[class=group][oname=" + shows[i] + "]").attr("showhide", "show").show()
            }
        } catch(e) {}
        try {
            for (var i = 0; i < hides.length; i++) {
                $(".wf_form_content span[class=group][oname=" + hides[i] + "]").attr("showhide", "hide").hide()
            }
        } catch(e) {}
    };
    this.check = function() {
        try {
            CNOA_wf_signature_electron.GetSealValue()
        } catch(e) {}
        var pass = false;
        var must = checkMust();
        var a;
        if (must) {
            pass = true
        }
        return must
    };
    this.formatAll = function() {}
})();
function validateDateTime(c, a) {
    if (c == "") {
        return ""
    }
    var b = Date.parseDate(c, a);
    if (b == undefined) {
        return false
    } else {
        return c
    }
}
function formatNumber(g, k, a, e, b) {
    var h = "";
    if (g.indexOf("-") === 0) {
        h = "-";
        g = g.substring(1, g.length)
    }
    if (g != "") {
        if (!/^-?[\d|,]+(\.\d+)?$/.test(g)) {
            return false
        }
    }
    var m = g + "";
    var f = "";
    if (m == null) {
        if (e) {
            for (var d = 0; d < a; d++) {
                f += "0"
            }
            return "0." + f
        } else {
            return "0"
        }
    }
    m = m.replace(/^\s*|\s*$/g, "");
    if (m == "") {
        if (e) {
            for (var d = 0; d < a; d++) {
                f += "0"
            }
            return "0." + f
        } else {
            return "0"
        }
    }
    m = m.replace(/,/g, "");
    if (b) {
        var n = "0.";
        if (e) {
            for (var d = 0; d < a; d++) {
                n += "0"
            }
        }
        n += "5";
        m = Number(m) + Number(n);
        m += ""
    }
    var j = m.split(".");
    if (k) {
        if (j[0].length > 3) {
            while (j[0].length > 3) {
                f = "," + j[0].substring(j[0].length - 3, j[0].length) + f;
                j[0] = j[0].substring(0, j[0].length - 3)
            }
        }
    }
    f = j[0] + f;
    if (j.length == 2 && a != 0) {
        j[1] = j[1].substring(0, (j[1].length <= a) ? j[1].length: a);
        var c = j[1];
        if (j[1].length < a) {
            if (e) {
                for (var d = 0; d < a - j[1].length; d++) {
                    c += "0"
                }
            }
        }
        f += "." + c
    } else {
        if (j.length == 1 && a != 0) {
            if (e) {
                f += ".";
                for (var d = 0; d < a; d++) {
                    f += "0"
                }
            }
        }
    }
    return h + f
}
var CNOA_wf_use_newClass, CNOA_wf_use_new;
CNOA_wf_use_newClass = CNOA.Class.create();
CNOA_wf_use_newClass.prototype = {
    init: function() {
        var f = this;
        this.ID_btn_save = Ext.id();
        this.ID_flowName = Ext.id();
        this.flowId = CNOA.wf.use_new.flowId;
        this.againId = CNOA.wf.use_new.againId;
        this.flowType = CNOA.wf.use_new.flowType;
        this.tplSort = CNOA.wf.use_new.tplSort;
        this.editAction = CNOA.wf.use_new.action;
        this.uFlowId = CNOA.wf.use_new.uFlowId;
        this.puFlowId = CNOA.wf.use_new.puFlowId;
        this.cid = CNOA.wf.use_new.cid;
        this.pid = CNOA.wf.use_new.pid;
        var d = CNOA.wf.use_new.salaryApproveId,
            b = CNOA.wf.use_new.userCid,
            c = CNOA.wf.use_new.noticeLid,
            a = "";
        if (d != 0) {
            a = "&salaryApproveId=" + d + "&salaryOldUflowId=" + this.uFlowId
        }
        if (b != 0) {
            a = "&userCid=" + b
        }
        if (c != 0) {
            a = "&noticeLid=" + c + "&noticeOldUflowId=" + this.uFlowId
        }
        this.attachmentCt = Ext.id();
        this.ID_attachTable = Ext.id();
        this.ID_smsTable = Ext.id();
        this.ID_displayAt = Ext.id();
        this.ID_displaySms = Ext.id();
        this.ID_BTN_FJ = Ext.id();
        this.ID_BTN_SMS = Ext.id();
        window.wfAutoSaveIDV = null;
        this.attachUploading = false;
        this.nameDisallowBlank = true;
        this.ID_CNOA_wf_use_flowPanel_ct = Ext.id();
        this.ID_CNOA_wf_use_formPanel_ct = Ext.id();
        this.ID_CNOA_wf_use_attachPanel_ct = Ext.id();
        this.ID_CNOA_wf_use_smsPanel_ct = Ext.id();
        if (this.pid || this.cid) {
            this.baseUrl = "index.php?app=wf&func=flow&action=use&modul=new&flowId=" + this.flowId + "&flowType=" + this.flowType + "&tplSort=" + this.tplSort + "&edit=" + this.editAction + "&childId=" + CNOA.wf.use_new.childId + "&otherApp=" + CNOA.wf.use_new.otherApp + "&" + getSessionStr() + a + "&cid=" + this.cid + "&pid=" + this.pid
        } else {
            this.baseUrl = "index.php?app=wf&func=flow&action=use&modul=new&flowId=" + this.flowId + "&flowType=" + this.flowType + "&tplSort=" + this.tplSort + "&edit=" + this.editAction + "&childId=" + CNOA.wf.use_new.childId + "&otherApp=" + CNOA.wf.use_new.otherApp + "&" + getSessionStr() + a
        }
        var e = "<br />{F}: " + lang("biaoShiCurrentFlow") + "<br>{U}: " + lang("biaoshiCurrentFQ") + "<br>{Y}: " + lang("biaoshiCurrentTime") + "<br>{M}: " + lang("biaoshiCurrentMonth") + "<br>{D}: " + lang("biaoshiCurrentDays") + "<br>{H}: " + lang("biaoshiCurrentHour") + "。<br>{I}: " + lang("biaoshiCurrentMinutes") + "。<br>{S}: " + lang("biaoshiCurrentSecond") + "。<br>{N}: " + lang("biaoshiSystemZDNum") + "<br>{N}" + lang("oneFlowNum") + "1。<br>{NNNNN}" + lang("saidFiveFlowNum") + "。<br><br><b>" + lang("eg") + "</b>: " + lang("eg") + "：{F}{Y}{M}{D}-{NNNN}，" + lang("willBeConver") + "：" + lang("flowName") + "20080808-0008。";
        this.formPanel = new Ext.form.FormPanel({
            fileUpload: false,
            hideBorders: true,
            border: false,
            region: "center",
            autoScroll: true,
            layout: "htmlform",
            layoutConfig: {
                cache: false,
                template: this.tplSort == 3 ? "app/wf/tpl/default/flow/use/form_new_tplSort3.tpl.html": "app/wf/tpl/default/flow/use/form_new.tpl.html",
                templateData: {
                    str_flowInfo: lang("flowInformation"),
                    str_flowNumber: lang("flowNumber"),
                    str_flowLevel: lang("importantGrade"),
                    str_flowFreeTitle: lang("customFlowTitle"),
                    str_flowReason: lang("appReason"),
                    str_flowFormInfo: lang("formInfo"),
                    str_flowAttach: lang("attachmentInfo"),
                    str_flowAttachName: lang("attName"),
                    str_flowUploadBy: lang("uploadBy"),
                    str_flowUploadTime: lang("uploadTime"),
                    str_flowSMS: lang("sms"),
                    str_flowStep: lang("flowStep"),
                    str_flowMobileNum: lang("mobileNumName"),
                    str_flowContent: lang("content"),
                    str_flowPosttime: lang("posttime"),
                    str_flowSender: lang("sender"),
                    str_flowOpt: lang("opt"),
                    str_flowTextInfo: lang("textInfo"),
                    id_flowPanel: this.ID_CNOA_wf_use_flowPanel_ct,
                    id_formPanel: this.ID_CNOA_wf_use_formPanel_ct,
                    id_displayAt: this.ID_displayAt,
                    id_displaySms: this.ID_displaySms,
                    id_attachPanel: this.ID_CNOA_wf_use_attachPanel_ct,
                    id_attachTable: this.ID_attachTable,
                    id_smsPanel: this.ID_CNOA_wf_use_smsPanel_ct,
                    id_smsTable: this.ID_smsTable,
                    id_flowName: this.ID_flowName
                }
            },
            items: [{
                xtype: "cnoa_textfield",
                name: "flowNumber",
                width: 250,
                readOnly: true,
                editable: false,
                allowBlank: false,
                tipTitle: lang("numRuleHelp") + ":",
                tipText: e
            },
                {
                    xtype: "cnoa_textfield",
                    name: "flowName",
                    width: 250
                },
                {
                    xtype: "hidden",
                    name: "uFlowId",
                    value: "0"
                },
                {
                    xtype: "combo",
                    name: "level",
                    store: new Ext.data.SimpleStore({
                        fields: ["value", "level"],
                        data: [["0", lang("general")], ["1", lang("importance")], ["2", lang("veryImportant")]]
                    }),
                    hiddenName: "level",
                    valueField: "value",
                    displayField: "level",
                    mode: "local",
                    width: 250,
                    allowBlank: false,
                    triggerAction: "all",
                    forceSelection: true,
                    editable: false,
                    value: "0"
                },
                {
                    xtype: "textarea",
                    name: "reason",
                    width: 250,
                    cls: "wf-reason"
                }],
            listeners: {
                afterrender: function() {
                    setTimeout(function() {
                            if ($(".wf_form_content").width() < 540) {}
                            f.loadFormPanel()
                        },
                        200)
                }
            },
            bbar: new Ext.Toolbar({
                items: [{
                    text: lang("turnNextStep"),
                    iconCls: "icon-fileview-column3",
                    cls: "btn-blue4",
                    style: "magin-left: 10px",
                    handler: function(g) {
                        cdump(f.ID_flowName);
                        var h = $("#" + f.ID_flowName).find("input").val();
                        if (h == "" && f.nameDisallowBlank) {
                            CNOA.msg.alert(lang("notFillYourFlow"),
                                function() {
                                    f.formPanel.getForm().findField("flowName").focus()
                                })
                        } else {
                            f.getSendNextData(g)
                        }
                    }
                },
                    "-", {
                        xtype: "panel",
                        border: false,
                        id: this.ID_BTN_FJ,
                        bodyCfg: {
                            id: this.attachmentCt,
                            cls: "x-panel-body x-panel-body-noheader x-panel-body-noborde btn-blue3",
                            style: "background:none"
                        },
                        items: [{
                            xtype: "fileuploadfield",
                            buttonOnly: true,
                            buttonText: lang("addAttach"),
                            buttonCfg: {
                                text: lang("addAttach"),
                                iconCls: "icon-folder-plus3"
                            },
                            listeners: {
                                fileselected: function(h, g) {
                                    f.addAttach(h, g)
                                }
                            }
                        }]
                    },
                    {
                        text: "添加短信",
                        id: this.ID_BTN_SMS,
                        hidden: true,
                        cls: "btn-blue4",
                        iconCls: "icon-sms-sound",
                        handler: function() {
                            newSmsWindow(function(j, k, g, h) {
                                f.insertSms(j, k, g, h)
                            })
                        }
                    },
                    {
                        text: "打开Excel",
                        iconCls: "document-excel-import",
                        cls: "btn-blue4",
                        hidden: true,
                        handler: function() {
                            var g = document.getElementById("CNOA_WEBOFFICE");
                            g.LoadOriginalFile("open", "xls")
                        }
                    },
                    {
                        text: "存草稿箱",
                        id: this.ID_btn_save,
                        iconCls: "icon-save-draft",
                        cls: "btn-blue4",
                        handler: function() {
                            f.saveFlow()
                        }
                    },
                    {
                        text: lang("cancel"),
                        handler: function() {
                            f.closeTab()
                        }
                    },
                    "->", {
                        xtype: "cnoa_helpBtn",
                        helpid: 69
                    }]
            })
        });
        this.mainPanel = new Ext.Panel({
            collapsible: false,
            hideBorders: true,
            border: false,
            layout: "border",
            items: [this.formPanel],
            listeners: {
                beforedestroy: function(g) {
                    if (d != 0 || c != 0) {
                        Ext.Ajax.request({
                            url: "index.php?app=wf&func=flow&action=use&modul=new&task=bindingProcess",
                            params: {
                                uFlowId: f.uFlowId,
                                noticeLid: c,
                                salaryApproveId: d
                            }
                        })
                    }
                    try {
                        CNOA_wf_signature_electron.destroy()
                    } catch(h) {}
                }
            }
        })
    },
    insertSms: function(c, h, a, f) {
        var e = this;
        var b = new Date();
        var j = b.format("Y-m-d H:i:s");
        Ext.fly(e.ID_displaySms).dom.style.display = "block";
        var k = jQuery("#" + this.ID_smsTable + " tr").length;
        var d = "<input type='hidden' name='sms[]' value='" + Ext.encode({
            mobiles: a,
            names: h,
            content: f
        }) + "' />";
        var g = '<tr height=24 align="left" bgColor="#FFFFFF"><td bgColor="#FFFFFF">&nbsp;当前步骤</td><td bgColor="#FFFFFF">&nbsp;' + a + "(" + h + ") " + d + '</td><td bgColor="#FFFFFF">&nbsp;' + f + '</td><td bgColor="#FFFFFF">&nbsp;' + j + '</td><td bgColor="#FFFFFF">&nbsp;' + CNOA_USER_TRUENAME + '</td><td bgColor="#FFFFFF">&nbsp;<a href="javascript:void(0)" onclick="CNOA_wf_use_new.removeSms(this);">删除</a></td>';
        "</tr>";
        jQuery(g).insertAfter(jQuery("#" + this.ID_smsTable + " tr:eq(" + (k - 1) + ")"));
        Ext.fly(this.ID_smsTable).dom.scrollIntoView()
    },
    removeSms: function(a) {
        var b = this;
        CNOA.msg.cf("确定删除此短信吗？",
            function(c) {
                if (c == "yes") {
                    jQuery(a).parent().parent("tr").remove();
                    var d = jQuery("#" + b.ID_smsTable + " tr").length;
                    if (d == 1) {
                        Ext.fly(b.ID_displaySms).dom.style.display = "none"
                    }
                }
            })
    },
    getSendNextData: function(c) {
        var e = this;
        CNOA_wf_form_checker.formatAll();
        var d = e.formPanel.getForm();
        d.fileUpload = false;
        if (!d.isValid()) {
            CNOA.msg.alert(lang("formValid"));
            return false
        } else {
            if (!CNOA_wf_form_checker.check()) {
                return false
            }
            var a = $('.wf_form_content input[dataType="user_sel"]');
            var b = "";
            a.each(function() {
                b += $(this).attr("id") + "=";
                b += $(this).val() + "|"
            });
            d.submit({
                url: e.baseUrl + "&task=getSendNextData",
                params: {
                    flowId: this.flowId,
                    tplSort: this.tplSort,
                    user_sel: b
                },
                method: "POST",
                success: function(g, f) {
                    e.showSendNextStepWin(f.result.data)
                },
                failure: function(g, f) {}
            })
        }
    },
    sendNextStep: function(b, d) {
        var j = this;
        CNOA_wf_form_checker.formatAll();
        var a = j.formPanel,
            c = a.getForm();
        try {
            a.body.dom.setAttribute("enctype", "multipart/form-data");
            a.body.dom.enctype = "multipart/form-data"
        } catch(h) {}
        var g = function(f) {
            var k = j.baseUrl + "&task=ms_submitMsOfficeData&uFlowId=" + f;
            try {
                CNOA.WOWF.saveOffice(k)
            } catch(m) {}
            mainPanel.closeTab("CNOA_MENU_WF_USE_OPENFLOW")
        };
        c.submit({
            url: j.baseUrl + "&task=sendNextStep",
            params: {
                flowId: this.flowId,
                nextStepId: b.stepId,
                nextUid: b.uid,
                phone: b.phone,
                convergenenUid: b.convergenenUid
            },
            method: "POST",
            success: function(m, f) {
                CNOA.msg.notice(lang("flowIntoStep1") + b.stepname + "<br />" + lang("acceptOfficer") + "：" + b.uname, lang("workFlow"), 6);
                try {
                    CNOA_wf_use_list.store.reload()
                } catch(k) {}
                try {
                    CNOA_wf_use_draft.store.reload()
                } catch(k) {}
                try {
                    CNOA_notice_notice_todo.reload()
                } catch(k) {}
                try {
                    CNOA_wf_use_todo.store.reload()
                } catch(k) {}
                if (j.tplSort != 0) {
                    g(f.result.data.uFlowId)
                }
                d.call(j);
                if (j.tplSort == 0 || j.tplSort == 3) {
                    j.closeTab()
                }
            },
            failure: function(f, e) {
                CNOA.msg.alert(e.result.msg);
                d.call(j)
            }
        })
    },
    addAttach: function(b, h) {
        var d = this;
        var f = h.lastIndexOf("/");
        if (f == -1) {
            f = h.lastIndexOf("\\")
        }
        var a = h.substr(f + 1);
        a += "<div style='display:none;' id='" + b.id + "-file-ct'></div>";
        var g = false;
        jQuery("#" + this.ID_attachTable + " input[type=file]").each(function() {
            if (jQuery(this).val() == h) {
                g = true
            }
        });
        if (g) {
            CNOA.msg.notice2(lang("file") + ": " + a + lang("alreadyUpload"));
            return
        }
        var c = new Date();
        var j = c.format("Y-m-d H:i:s");
        var k = jQuery("#" + this.ID_attachTable);
        Ext.fly(d.ID_displayAt).dom.style.display = "block";
        var m = jQuery("#" + this.ID_attachTable + " tr").length;
        var e = '<tr height=24 align="left" bgColor="#FFFFFF"><td bgColor="#FFFFFF">&nbsp;' + a + '</td><td bgColor="#FFFFFF">&nbsp;' + CNOA_USER_TRUENAME + '</td><td bgColor="#FFFFFF">&nbsp;' + j + '</td><td bgColor="#FFFFFF">&nbsp;<a href="javascript:void(0)" onclick="CNOA_wf_use_new.removefile(this);">' + lang("del") + "</a></td>";
        "</tr>";
        jQuery(e).insertAfter(jQuery("#" + this.ID_attachTable + " tr:eq(" + (m - 1) + ")"));
        jQuery("#" + b.id + "-file-ct").append(jQuery("#" + b.id + "-file"));
        Ext.fly(this.ID_attachTable).dom.scrollIntoView();
        jQuery("#" + this.attachmentCt).html("");
        new Ext.ux.form.FileUploadField({
            renderTo: this.attachmentCt,
            buttonOnly: true,
            buttonText: lang("addAttach"),
            buttonCfg: {
                text: lang("addAttach"),
                iconCls: "icon-folder--plus"
            },
            listeners: {
                fileselected: function(o, n) {
                    d.addAttach(o, n)
                }
            }
        })
    },
    removefile: function(b) {
        var c = this;
        var a = $(b).closest("tr").attr("class");
        CNOA.msg.cf(lang("sureDelAttchment"),
            function(d) {
                if (d == "yes") {
                    if (a) {
                        $("." + a).remove()
                    }
                    jQuery(b).parent().parent("tr").remove();
                    var e = jQuery("#" + c.ID_attachTable + " tr").length;
                    if (e == 1) {
                        Ext.fly(c.ID_displayAt).dom.style.display = "none"
                    }
                }
            })
    },
    loadFormPanel: function(c) {
        var b = this;
        var a = function() {
            var e = b.baseUrl + "&task=ms_loadTemplateFile&editAction=" + b.editAction + "&uFlowId=" + b.uFlowId;
            var f = b.baseUrl + "&task=ms_submitMsOfficeData&tplSort=" + b.tplSort + "&uFlowId=" + b.uFlowId + "&editAction=" + b.editAction;
            if (b.tplSort == 1 || b.tplSort == 3) {
                var g = "doc"
            } else {
                var g = "xls"
            }
            var d = "a=b&c=d&flowId=" + b.flowId;
            $("#wf_new_formct").html("").css("border", "none");
            openOfficeForEdit_WF("wf_new_formct", e, g, "自由流程表单", 0, f, d, true)
        };
        if (b.tplSort == 0 || b.tplSort == 3) {
            Ext.Ajax.request({
                url: b.baseUrl + "&task=loadFormHtml",
                method: "POST",
                params: {
                    uFlowId: b.uFlowId,
                    puFlowId: b.puFlowId,
                    againId: b.againId
                },
                success: function(e) {
                    var d = Ext.decode(e.responseText);
                    if (d.success === true) {
                        Ext.fly(b.ID_CNOA_wf_use_formPanel_ct).update(d.data.formHtml + '<div style="clear:both;"></div>');
                        CNOA_wf_form_checker.formInit(b.ID_CNOA_wf_use_formPanel_ct, b.uFlowId, b.flowId);
                        b.loadUflowInfo(c);
                        setPageSet($("#" + b.ID_CNOA_wf_use_formPanel_ct).get(0), Ext.decode(d.pageSet));
                        if (b.tplSort == 3) {
                            a()
                        }
                    } else {
                        CNOA.msg.alert(d.msg,
                            function() {})
                    }
                }
            })
        } else {
            a();
            b.loadUflowInfo(c)
        }
        if (b.flowType == 0 && (b.tplSort == 0 || b.tplSort == 3)) {
            $("#wf_form_new_tushi").show();
            setTimeout(function() {},
                5)
        } else {
            $("#wf_form_new_tushi").hide()
        }
    },
    showSendNextStepWin: function(a) {
        var b = this;
        CNOA_wf_use_goNextStepWin = new CNOA_wf_use_goNextStepWinClass(a, "new", "", b.tplSort)
    },
    startAutoSave: function() {
        var a = this;
        window.wfAutoSaveIDV = setInterval(function() {
                if (!a.attachUploading) {
                    a.saveFlow()
                }
            },
            60000)
    },
    saveFlow: function() {
        var h = this;
        h.attachUploading = true;
        CNOA_wf_form_checker.formatAll();
        var a = h.formPanel,
            c = a.getForm();
        try {
            a.body.dom.setAttribute("enctype", "multipart/form-data");
            a.body.dom.enctype = "multipart/form-data"
        } catch(g) {}
        var d = function(e) {
            var f = h.baseUrl + "&task=ms_submitMsOfficeData&tplSort=" + h.tplSort + "&uFlowId=" + e + "&editAction=" + h.editAction;
            CNOA.WOWF.saveOffice(f)
        };
        var b = "";
        if (h.tplSort == 0 || h.tplSort == 3) {
            b = "saveFlow"
        } else {
            b = "savefreeFlow"
        }
        c.submit({
            url: h.baseUrl + "&task=" + b,
            params: {
                flowId: this.flowId,
                tplSort: this.tplSort
            },
            method: "POST",
            success: function(m, j) {
                var f = j.result.data.uFlowId;
                h.uFlowId = f;
                h.editAction = "edit";
                c.findField("uFlowId").setValue(f);
                h.createAttachList(j.result.attach);
                if (h.tplSort != 0) {
                    d(f)
                }
                try {
                    CNOA_wf_use_draft.store.reload()
                } catch(k) {}
                CNOA.msg.notice(lang("saved") + "[" + j.result.data.saveTime + "]", lang("workFlow"));
                h.attachUploading = false
            },
            failure: function(f, e) {}
        })
    },
    createAttachList: function(d) {
        var e = this;
        if (!d) {
            return
        }
        var c = jQuery("#" + this.ID_attachTable + " tr");
        c.each(function(f) {
            var g = $(this);
            if ((f > 0) && g.attr("upd") != "true") {
                g.remove()
            }
        });
        for (var a = 0; a < d.length; a++) {
            var b = '<tr class="attach_' + d[a].attachid + '" upd="true" height=24 align="left" bgColor="#FFFFFF"><td bgColor="#FFFFFF">&nbsp;' + d[a].filename + ' <span style="color:green;">[' + lang("uploaded") + ']</span><input type="hidden" name="wf_attach_' + d[a].attachid + '" value="1" /></td><td bgColor="#FFFFFF">&nbsp;' + d[a].uname + '</td><td bgColor="#FFFFFF">&nbsp;' + d[a].date + '</td><td bgColor="#FFFFFF">&nbsp;' + d[a].optStr + "</td>";
            "</tr>";
            jQuery(b).appendTo(jQuery("#" + e.ID_attachTable))
        }
    },
    closeTab: function() {
        var a = CNOA.wf.use_new.parentID.replace("docs-", "");
        mainPanel.closeTab(a)
    },
    loadUflowInfo: function(noattach) {
        var _this = this;
        var fp = _this.formPanel,
            f = fp.getForm();
        f.load({
            url: _this.baseUrl + "&task=loadUflowInfo",
            method: "POST",
            params: {
                flowId: _this.flowId,
                uFlowId: _this.uFlowId,
                editAction: _this.editAction,
                flowType: _this.flowType,
                tplSort: _this.tplSort
            },
            success: function(form, action) {
                if (action.result.attach && noattach !== "noattach") {
                    if (action.result.attach.length > 0) {
                        Ext.fly(_this.ID_displayAt).dom.style.display = "block";
                        _this.createAttachList(action.result.attach)
                    }
                }
                if (action.result.wf_detail_field_data) {
                    var wfdt = action.result.wf_detail_field_data;
                    if (wfdt.length > 0) {
                        for (var i = 0; i < wfdt.length; i++) {
                            for (var j = 0; j < wfdt[i].length; j++) {
                                $("#wf_detail_" + wfdt[i][j].id).val(wfdt[i][j].data)
                            }
                        }
                    }
                }
                if (action.result.config) {
                    var config = action.result.config;
                    var flowNumber = _this.formPanel.getForm().findField("flowNumber");
                    with(config) {
                        try {
                            allowAttachAdd == "1" ? Ext.getCmp(_this.ID_BTN_FJ).show() : Ext.getCmp(_this.ID_BTN_FJ).hide()
                        } catch(e) {}
                        try {
                            allowSms == "1" ? Ext.getCmp(_this.ID_BTN_SMS).show() : Ext.getCmp(_this.ID_BTN_SMS).hide()
                        } catch(e) {}
                        allowEditFlowNumber == "1" ? flowNumber.setReadOnly(false) : flowNumber.setReadOnly(true);
                        _this.nameDisallowBlank = nameDisallowBlank == "1" ? true: false
                    }
                }
                setTimeout(function() {
                        var flowview = $("[mark=flowview]");
                        flowview.each(function(i) {
                            var data = Ext.decode($(this).attr("data"));
                            $(this).bind("click",
                                function() {
                                    mainPanel.closeTab("CNOA_MENU_WF_USE_JUMPVIEW");
                                    if (data.status == 1) {
                                        mainPanel.loadClass("index.php?app=wf&func=flow&action=use&modul=todo&task=loadPage&type=show&from=showflow&uFlowId=" + data.uFlowId + "&flowId=" + data.flowId + "&step=" + data.stepId + "&flowType=" + data.flowType + "&tplSort=" + data.tplSort, "CNOA_MENU_WF_USE_JUMPVIEW", lang("seeRelateFlow"), "icon-flow")
                                    } else {
                                        if (data.status == 2) {
                                            mainPanel.loadClass("index.php?app=wf&func=flow&action=use&modul=done&task=loadPage&from=showflow&uFlowId=" + data.uFlowId + "&flowId=" + data.flowId + "&step=" + data.stepId + "&flowType=" + data.flowType + "&tplSort=" + data.tplSort + "&owner=" + data.owner, "CNOA_MENU_WF_USE_JUMPVIEW", lang("seeRelateFlow"), "icon-flow")
                                        }
                                    }
                                })
                        })
                    },
                    100)
            },
            failure: function(form, action) {
                CNOA.msg.alert(action.result.msg,
                    function() {})
            }.createDelegate(this)
        })
    }
};
var CNOA_wf_use_newfree_flowdesignClass, CNOA_wf_use_newfree_flowdesign;
var CNOA_wf_use_newfree_setFlowStepWinClass, CNOA_wf_use_newfree_setFlowStepWin;
CNOA_wf_use_newfree_setFlowStepWinClass = CNOA.Class.create();
CNOA_wf_use_newfree_setFlowStepWinClass.prototype = {
    init: function(a, c) {
        var d = this;
        this.flowType = a;
        this.tplSort = c;
        this.baseUrl = "index.php?app=wf&func=flow&action=use&modul=newfree";
        this.fields = [{
            name: "stepId",
            mapping: "stepId"
        },
            {
                name: "uname",
                mapping: "uname"
            },
            {
                name: "stepname",
                mapping: "stepname"
            }];
        this.stepStore = new Ext.data.Store({
            proxy: new Ext.data.HttpProxy({
                url: d.baseUrl + "&task=ms_loadStepDealList"
            }),
            reader: new Ext.data.JsonReader({
                totalProperty: "total",
                root: "data",
                fields: d.fields
            })
        });
        this.colModel = new Ext.grid.ColumnModel([new Ext.grid.RowNumberer(), {
            header: "stepId",
            dataIndex: "stepId",
            width: 20,
            sortable: false,
            hidden: true
        },
            {
                header: lang("acceptOfficer"),
                dataIndex: "uname",
                width: 100,
                sortable: true
            },
            {
                header: lang("stepName"),
                dataIndex: "stepname",
                width: 150,
                sortable: true,
                editor: new Ext.form.TextField({
                    allowBlank: false
                })
            },
            {
                header: lang("opt"),
                dataIndex: "stepId",
                width: 100,
                sortable: true,
                renderer: this.makeOperate.createDelegate(this)
            },
            {
                header: "",
                dataIndex: "noIndex",
                width: 1,
                menuDisabled: true,
                resizable: false
            }]);
        var b = new Ext.ux.grid.RowEditor({
            cancelText: lang("cancel"),
            saveText: lang("update"),
            errorSummary: false
        });
        this.grid = new Ext.grid.GridPanel({
            stripeRows: true,
            loadMask: {
                msg: lang("waiting")
            },
            cm: d.colModel,
            store: d.stepStore,
            hideBorders: true,
            border: false,
            enableDragDrop: true,
            plugins: [b],
            dropConfig: {
                appendOnly: true
            },
            ddGroup: "GridDD",
            listeners: {
                afterrender: function(f) {
                    var e = new Ext.dd.DropTarget(f.getEl(), {
                        ddGroup: "GridDD",
                        copy: false,
                        notifyDrop: function(g, n, m) {
                            var k = m.selections;
                            var h = g.getDragData(n).rowIndex;
                            if (typeof(h) == "undefined") {
                                return
                            }
                            for (i = 0; i < k.length; i++) {
                                var j = k[i];
                                if (!this.copy) {
                                    d.stepStore.remove(j)
                                }
                                if (h == 0) {
                                    j.data.orderNum -= 1
                                } else {
                                    if (h == d.stepStore.data.items.length) {
                                        j.data.id = d.stepStore.data.items[h - 1].data.id + 1
                                    } else {
                                        j.data.id = (d.stepStore.data.items[h - 1].data.id + d.stepStore.data.items[h].data.id) / 2
                                    }
                                }
                                d.stepStore.insert(h, j)
                            }
                        }
                    })
                },
                cellclick: function(f, j, g, h) {
                    if (g == 2 || g == 4) {
                        return false
                    }
                }
            },
            tbar: new Ext.Toolbar({
                items: [{
                    xtype: "hidden",
                    name: "uid",
                    id: CNOA_wf_use_newfree_flowdesign.ID_dealUid
                },
                    {
                        xtype: "btnForPoepleSelector",
                        iconCls: "icon-utils-s-add",
                        autoWidth: true,
                        dataUrl: d.baseUrl + "&task=getAllUserListsInPermitDeptTree",
                        text: lang("acceptOfficer"),
                        listeners: {
                            selected: function(g, j) {
                                var k = new Array();
                                var f = new Array();
                                if (j.length > 0) {
                                    for (var e = 0; e < j.length; e++) {
                                        k.push(j[e].uname);
                                        f.push(j[e].uid);
                                        var h = new Ext.data.Record({
                                            uid: j[e].uid,
                                            uname: j[e].uname,
                                            stepname: j[e].uname + "(" + lang("deal") + ")"
                                        });
                                        d.stepStore.add(h)
                                    }
                                }
                            }
                        }
                    },
                    new Ext.BoxComponent({
                        autoEl: {
                            tag: "div",
                            html: "<span class=cnoa_color_gray>" + lang("dragcolumnPerson") + "</span>"
                        }
                    })]
            })
        });
        this.win = new Ext.Window({
            title: lang("setProcessStep"),
            width: 410,
            height: 300,
            border: false,
            modal: true,
            layout: "fit",
            items: this.grid,
            maximizable: true,
            buttons: [{
                text: lang("ok"),
                iconCls: "icon-btn-save",
                handler: function() {
                    d.saveDealStepInfo();
                    if (d.tplSort == 0) {
                        Ext.getCmp(CNOA_wf_use_newfree_flowdesign.ID_HtmlEditor).show()
                    }
                }
            },
                {
                    text: lang("close"),
                    iconCls: "icon-dialog-cancel",
                    handler: function() {
                        d.win.close();
                        if (d.tplSort == 0) {
                            Ext.getCmp(CNOA_wf_use_newfree_flowdesign.ID_HtmlEditor).show()
                        }
                    }
                }],
            listeners: {
                close: function() {
                    if (d.tplSort == 0) {
                        Ext.getCmp(CNOA_wf_use_newfree_flowdesign.ID_HtmlEditor).show()
                    }
                },
                show: function() {
                    if (d.tplSort == 0) {
                        Ext.getCmp(CNOA_wf_use_newfree_flowdesign.ID_HtmlEditor).show()
                    }
                }
            }
        }).show();
        d.loadStepDealer()
    },
    loadStepDealer: function() {
        var f = this;
        var b = Ext.getCmp(CNOA_wf_use_newfree_flowdesign.ID_dealStepUid).getValue();
        var e = Ext.getCmp(CNOA_wf_use_newfree_flowdesign.ID_getDealName).getValue();
        var a = Ext.getCmp(CNOA_wf_use_newfree_flowdesign.ID_getStepName).getValue();
        if (e != "") {
            e = e.split(",");
            b = b.split(",");
            a = a.split(",");
            if (b.length > 0) {
                for (var c = 0; c < b.length; c++) {
                    var d = new Ext.data.Record({
                        stepId: 0,
                        uid: b[c],
                        uname: e[c],
                        stepname: a[c]
                    });
                    f.stepStore.add(d);
                    f.grid.getView().refresh()
                }
            }
        }
    },
    saveDealStepInfo: function() {
        var g = this;
        for (var c = 0; c < g.stepStore.getCount(); c++) {
            var a = g.stepStore.getAt(c);
            if (a.get("stepname") === undefined) {
                CNOA.msg.alert(lang("stepNameNotEmptyFill"));
                return false
            }
        }
        var d = new Array();
        var b = new Array();
        var f = new Array();
        var e = new Array();
        Ext.each(g.stepStore.data.items,
            function(h, j) {
                d.push(h.data.uid);
                b.push("[" + h.data.uname + "]");
                f.push(h.data.uname);
                e.push(h.data.stepname)
            });
        if (b != "") {
            Ext.getCmp(CNOA_wf_use_newfree_flowdesign.ID_dealStep).setValue(lang("startPeople") + b.join("->") + lang("endpeople"));
            Ext.getCmp(CNOA_wf_use_newfree_flowdesign.ID_getDealName).setValue(f.join(","));
            Ext.getCmp(CNOA_wf_use_newfree_flowdesign.ID_dealStepUid).setValue(d.join(","));
            Ext.getCmp(CNOA_wf_use_newfree_flowdesign.ID_getStepName).setValue(e.join(","));
            g.win.close()
        } else {
            CNOA.msg.alert(lang("pleaseAddFlowDeal"))
        }
        g.win.close()
    },
    makeOperate: function(e, c, a, f, b, d) {
        return "<a href='javascript:void(0);' onclick='CNOA_wf_use_newfree_setFlowStepWin.ms_deleteDealStep(" + f + ");'>" + lang("del") + "</a>"
    },
    ms_deleteDealStep: function(a) {
        this.grid.getStore().removeAt(a);
        this.grid.getView().refresh()
    }
};
CNOA_wf_use_newfree_flowdesignClass = CNOA.Class.create();
CNOA_wf_use_newfree_flowdesignClass.prototype = {
    init: function() {
        var b = this;
        this.flowId = CNOA.wf.use_newfree_flowdesign.flowId;
        this.title = this.flowType == 1 ? "自由顺序流": "自由流程";
        this.editAction = CNOA.wf.use_newfree_flowdesign.action;
        this.uFlowId = CNOA.wf.use_newfree_flowdesign.uFlowId;
        this.flowType = CNOA.wf.use_newfree_flowdesign.flowType;
        this.tplSort = CNOA.wf.use_newfree_flowdesign.tplSort;
        this.ID_stepDisplayAt = Ext.id();
        this.ID_save = Ext.id();
        this.attachmentCt = Ext.id();
        this.ID_attachTable = Ext.id();
        this.ID_displayAt = Ext.id();
        this.ID_BTN_FJ = Ext.id();
        this.ID_CNOA_wf_use_flowPanel_ct = Ext.id();
        this.ID_CNOA_wf_use_formPanel_ct = Ext.id();
        this.ID_CNOA_wf_use_attachPanel_ct = Ext.id();
        this.ID_CNOA_wf_use_stepPanel_ct = Ext.id();
        this.ID_CNOA_wf_use_formPanel_ct2 = Ext.id();
        this.ID_CNOA_wf_use_freeFlow_formPanel_ct = Ext.id();
        this.baseUrl = "index.php?app=wf&func=flow&action=use&modul=newfree&flowId=" + b.flowId + "&flowType=" + b.flowType + "&tplSort=" + b.tplSort + "&" + getSessionStr();
        this.baseURI = location.href.substr(0, location.href.lastIndexOf("/") + 1);
        this.ID_officeHtml = Ext.id();
        this.ID_dealStep = Ext.id();
        this.ID_dealStepUid = Ext.id();
        this.ID_getDealName = Ext.id();
        this.ID_getStepName = Ext.id();
        this.ID_HtmlEditor = Ext.id();
        this.editor = null;
        var a = "<br />{F}: " + lang("biaoShiCurrentFlow") + "<br>{U}: " + lang("biaoshiCurrentFQ") + "<br>{Y}: " + lang("biaoshiCurrentTime") + "<br>{M}: " + lang("biaoshiCurrentMonth") + "<br>{D}: " + lang("biaoshiCurrentDays") + "<br>{H}: " + lang("biaoshiCurrentHour") + "。<br>{I}: " + lang("biaoshiCurrentMinutes") + "。<br>{S}: " + lang("biaoshiCurrentSecond") + "。<br>{N}: " + lang("biaoshiSystemZDNum") + "<br>{N}" + lang("oneFlowNum") + "1。<br>{NNNNN}" + lang("saidFiveFlowNum") + "。<br><br><b>" + lang("eg") + "</b>: " + lang("eg") + "：{F}{Y}{M}{D}-{NNNN}，" + lang("willBeConver") + "：" + lang("flowName") + "20080808-0008。";
        this.formPanel = new Ext.form.FormPanel({
            fileUpload: true,
            hideBorders: true,
            border: false,
            region: "center",
            autoScroll: true,
            layout: "htmlform",
            layoutConfig: {
                cache: false,
                template: "app/wf/tpl/default/flow/use/form_newfree.tpl.html",
                templateData: {
                    str_flowInfo: lang("flowInformation"),
                    str_flowNumber: lang("flowNumber"),
                    str_flowLevel: lang("importantGrade"),
                    str_flowFreeTitle: lang("customFlowTitle"),
                    str_flowReason: lang("appReason"),
                    str_flowFormInfo: lang("formInfo"),
                    str_flowAttach: lang("attachmentInfo"),
                    str_flowAttachName: lang("attName"),
                    str_flowUploadBy: lang("uploadBy"),
                    str_flowUploadTime: lang("uploadTime"),
                    str_flowOpt: lang("opt"),
                    str_flowStep: lang("flowStep"),
                    id_flowPanel: this.ID_CNOA_wf_use_flowPanel_ct,
                    id_formPanel: this.ID_CNOA_wf_use_formPanel_ct,
                    id_stepPanel: this.ID_CNOA_wf_use_stepPanel_ct,
                    id_displayAt: this.ID_displayAt,
                    id_attachPanel: this.ID_CNOA_wf_use_attachPanel_ct,
                    id_attachTable: this.ID_attachTable,
                    id_stepDisplayAt: this.ID_stepDisplayAt,
                    id_freeFlow_formPanel: this.ID_CNOA_wf_use_freeFlow_formPanel_ct
                }
            },
            items: [{
                xtype: "cnoa_textfield",
                name: "flowNumber",
                width: 250,
                readOnly: true,
                editable: false,
                allowBlank: false,
                tipTitle: lang("numRuleHelp") + ":",
                tipText: a
            },
                {
                    xtype: "cnoa_textfield",
                    name: "flowName",
                    width: 250
                },
                {
                    xtype: "hidden",
                    name: "uFlowId",
                    value: "0"
                },
                {
                    xtype: "combo",
                    name: "level",
                    store: new Ext.data.SimpleStore({
                        fields: ["value", "level"],
                        data: [["0", lang("general")], ["1", lang("importance")], ["2", lang("veryImportant")]]
                    }),
                    hiddenName: "level",
                    valueField: "value",
                    displayField: "level",
                    mode: "local",
                    width: 250,
                    allowBlank: false,
                    triggerAction: "all",
                    forceSelection: true,
                    editable: false,
                    value: "0"
                },
                {
                    xtype: "textarea",
                    name: "reason",
                    width: 250,
                    cls: "wf-reason"
                }],
            listeners: {
                afterrender: function() {
                    setTimeout(function() {
                            if ($(".wf_form_content").width() < 540) {}
                            if (b.flowType == 1) {
                                b.loadStepPanel()
                            }
                            b.loadFormPanel();
                            if (b.tplSort == 0 && $.inArray(b.flowType), [1, 2] != -1) {
                                $("#CNOA_FLOW_NEWFREE_FLOW_FORM_DESIGN").children().css("z-index", "999")
                            }
                        },
                        200)
                }
            },
            bbar: new Ext.Toolbar({
                items: [this.title + "：", {
                    text: lang("turnNextStep"),
                    iconCls: "icon-fileview-column3",
                    style: "magin-left: 10px",
                    id: b.ID_save,
                    handler: function(c) {
                        b.sendNextStep()
                    }
                },
                    {
                        text: lang("saveToDrafts"),
                        id: this.ID_btn_save,
                        iconCls: "icon-btn-save",
                        handler: function() {
                            b.saveFlow()
                        }
                    },
                    {
                        xtype: "panel",
                        border: false,
                        id: this.ID_BTN_FJ,
                        bodyCfg: {
                            id: this.attachmentCt,
                            cls: "x-panel-body x-panel-body-noheader x-panel-body-noborde",
                            style: "background:none"
                        },
                        items: [{
                            xtype: "fileuploadfield",
                            buttonOnly: true,
                            buttonText: lang("addAttach"),
                            buttonCfg: {
                                text: lang("addAttach"),
                                iconCls: "icon-folder--plus"
                            },
                            listeners: {
                                fileselected: function(d, c) {
                                    b.addAttach(d, c)
                                }
                            }
                        }]
                    },
                    {
                        text: lang("cancel"),
                        iconCls: "icon-dialog-cancel",
                        handler: function() {
                            if (b.editAction == "add") {
                                b.closeTab()
                            } else {
                                mainPanel.closeTab("CNOA_MENU_WF_USE_OPENFLOW")
                            }
                            if (b.tplSort != 0) {
                                try {
                                    var c = document.getElementById("CNOA_WEBOFFICE");
                                    c.SetSecurity(4 + 32768)
                                } catch(d) {}
                            }
                        }
                    },
                    "->", {
                        xtype: "cnoa_helpBtn",
                        helpid: 69
                    }]
            })
        });
        this.mainPanel = new Ext.Panel({
            collapsible: false,
            hideBorders: true,
            border: false,
            layout: "border",
            items: [this.formPanel]
        })
    },
    saveFlow: function() {
        var h = this;
        var b = h.formPanel,
            c = b.getForm();
        try {
            b.body.dom.setAttribute("enctype", "multipart/form-data");
            b.body.dom.enctype = "multipart/form-data"
        } catch(g) {}
        var a = "";
        if (h.tplSort == 0) {
            a = h.editor.getContent()
        }
        var d = function(e) {
            var f = h.baseUrl + "&task=ms_submitMsOfficeData&tplSort=" + h.tplSort + "&uFlowId=" + e;
            CNOA.WOWF.saveOffice(f)
        };
        c.submit({
            url: h.baseUrl + "&task=saveFlow",
            params: {
                flowId: this.flowId,
                tplSort: this.tplSort,
                htmlFormContent: a
            },
            method: "POST",
            success: function(k, f) {
                c.findField("uFlowId").setValue(f.result.data.uFlowId);
                h.createAttachList(f.result.data.attach);
                h.uFlowId = f.result.data.uFlowId;
                if (h.tplSort != 0) {
                    d(h.uFlowId)
                }
                try {
                    CNOA_wf_use_draft.store.reload()
                } catch(j) {}
                CNOA.msg.notice(lang("saved") + "[" + f.result.data.saveTime + "]", lang("workFlow"))
            },
            failure: function(f, e) {}
        })
    },
    createAttachList: function(d) {
        var e = this;
        var c = jQuery("#" + this.ID_attachTable + " tr");
        c.each(function(f) {
            var g = $(this);
            if ((f > 0) && g.attr("upd") != "true") {
                g.remove()
            }
        });
        for (var a = 0; a < d.length; a++) {
            var b = '<tr upd="true" height=24 align="left" bgColor="#FFFFFF"><td bgColor="#FFFFFF">&nbsp;' + d[a].filename + "[" + lang("uploaded") + ']<input type="hidden" name="wf_attach_' + d[a].attachid + '" value="1" /></td><td bgColor="#FFFFFF">&nbsp;' + d[a].uname + '</td><td bgColor="#FFFFFF">&nbsp;' + d[a].date + '</td><td bgColor="#FFFFFF">&nbsp;<a href="javascript:void(0)" onclick="CNOA_wf_use_newfree_flowdesign.removefile(this);" style="text-decoration:underline">' + lang("del") + "</a></td>";
            "</tr>";
            jQuery(b).appendTo(jQuery("#" + e.ID_attachTable))
        }
    },
    addAttach: function(b, h) {
        var d = this;
        var f = h.lastIndexOf("/");
        if (f == -1) {
            f = h.lastIndexOf("\\")
        }
        var a = h.substr(f + 1);
        a += "<div style='display:none;' id='" + b.id + "-file-ct'></div>";
        var g = false;
        jQuery("#" + this.ID_attachTable + " input[type=file]").each(function() {
            if (jQuery(this).val() == h) {
                g = true
            }
        });
        if (g) {
            CNOA.msg.notice2(lang("file") + ": " + a + lang("alreadyUpload"));
            return
        }
        var c = new Date();
        var j = c.format("Y-m-d H:i:s");
        var k = jQuery("#" + this.ID_attachTable);
        Ext.fly(d.ID_displayAt).dom.style.display = "block";
        var m = jQuery("#" + this.ID_attachTable + " tr").length;
        var e = '<tr height=24 align="left" bgColor="#FFFFFF"><td bgColor="#FFFFFF">&nbsp;' + a + '</td><td bgColor="#FFFFFF">&nbsp;' + CNOA_USER_TRUENAME + '</td><td bgColor="#FFFFFF">&nbsp;' + j + '</td><td bgColor="#FFFFFF">&nbsp;<a href="javascript:void(0)" onclick="CNOA_wf_use_newfree_flowdesign.removefile(this);">' + lang("del") + "</a></td>";
        "</tr>";
        jQuery(e).insertAfter(jQuery("#" + this.ID_attachTable + " tr:eq(" + (m - 1) + ")"));
        jQuery("#" + b.id + "-file-ct").append(jQuery("#" + b.id + "-file"));
        Ext.fly(this.ID_attachTable).dom.scrollIntoView();
        jQuery("#" + this.attachmentCt).html("");
        new Ext.ux.form.FileUploadField({
            renderTo: this.attachmentCt,
            buttonOnly: true,
            buttonText: lang("addAttach"),
            buttonCfg: {
                text: lang("addAttach"),
                iconCls: "icon-folder--plus"
            },
            listeners: {
                fileselected: function(o, n) {
                    d.addAttach(o, n)
                }
            }
        })
    },
    removefile: function(a) {
        var b = this;
        CNOA.msg.cf(lang("sureDelAttchment"),
            function(c) {
                if (c == "yes") {
                    jQuery(a).parent().parent("tr").remove();
                    var d = jQuery("#" + b.ID_attachTable + " tr").length;
                    if (d == 1) {
                        Ext.fly(b.ID_displayAt).dom.style.display = "none"
                    }
                }
            })
    },
    loadStepPanel: function() {
        var c = this;
        var a = c.tplSort;
        var b = c.flowType;
        Ext.fly(c.ID_stepDisplayAt).dom.style.display = "block";
        new Ext.form.FieldSet({
            renderTo: c.ID_CNOA_wf_use_stepPanel_ct,
            border: false,
            hideBorders: false,
            labelWidth: 30,
            autoHeight: true,
            style: "margin-bottom:0",
            items: [{
                xtype: "textarea",
                height: 35,
                anchor: "-20",
                style: "margin-top:4px;",
                id: c.ID_dealStep,
                readOnly: true,
                editable: false,
                fieldLabel: lang("step"),
                name: "uname"
            },
                {
                    xtype: "hidden",
                    id: c.ID_getDealName,
                    name: "getname"
                },
                {
                    xtype: "hidden",
                    id: c.ID_dealStepUid,
                    name: "uid"
                },
                {
                    xtype: "hidden",
                    id: c.ID_getStepName,
                    name: "stepname"
                },
                {
                    xtype: "compositefield",
                    items: [{
                        xtype: "button",
                        text: lang("setFlowStep"),
                        handler: function(d, e) {
                            c.setFlowStepWindow()
                        }.createDelegate(this)
                    },
                        {
                            xtype: "displayfield",
                            value: lang("hostAccordingOther")
                        }]
                },
                {
                    xtype: "compositefield",
                    items: [{
                        xtype: "checkbox",
                        name: "changeFlowInfo",
                        checken: true
                    },
                        {
                            xtype: "displayfield",
                            value: "是否禁止流程步骤修改表单信息"
                        }]
                }]
        });
        if (this.editAction == "edit") {
            c.loadStepFormData()
        }
    },
    loadStepFormData: function() {
        var a = this;
        a.formPanel.getForm().load({
            url: a.baseUrl + "&task=loadStepFormData&uFlowId=" + a.uFlowId,
            method: "POST",
            success: function(b, c) {
                Ext.getCmp(a.ID_dealStep).setValue(c.result.data.uname);
                Ext.getCmp(a.ID_dealStepUid).setValue(c.result.data.uid);
                Ext.getCmp(a.ID_getDealName).setValue(c.result.data.getname);
                Ext.getCmp(a.ID_getStepName).setValue(c.result.data.stepname)
            },
            failure: function(b, c) {
                CNOA.msg.alert(c.result.msg,
                    function() {})
            }.createDelegate(this)
        })
    },
    loadFormPanel: function() {
        var e = this;
        if (e.tplSort == 0) {
            new Ext.Panel({
                renderTo: e.ID_CNOA_wf_use_freeFlow_formPanel_ct,
                autoHeight: true,
                layout: "fit",
                id: e.ID_HtmlEditor,
                name: "htmlFormContent",
                bodyCfg: {
                    id: "CNOA_FLOW_NEWFREE_FLOW_FORM_DESIGN"
                },
                listeners: {
                    afterrender: function(f) {
                        e.editor = UE.getEditor("CNOA_FLOW_NEWFREE_FLOW_FORM_DESIGN", {
                            imageUrl: "../../php/imageUp.php",
                            catcherUrl: "../../php/getRemoteImage.php",
                            toolbars: [["fullscreen", "source", "|", "justifyleft", "justifycenter", "justifyright", "justifyjustify", "|", "bold", "italic", "underline", "fontborder", "strikethrough", "superscript", "subscript", "forecolor", "backcolor", "pasteplain", "|", "fontfamily", "fontsize", "|", "link", "unlink", "|", "insertimage", "wordimage", "imagenone", "imageleft", "imageright", "imagecenter", "|", "inserttable", "deletetable", "insertparagraphbeforetable", "insertrow", "deleterow", "insertcol", "deletecol", "mergecells", "mergeright", "mergedown", "splittocells", "splittorows", "splittocols"]],
                            autoClearinitialContent: true,
                            wordCount: false,
                            elementPathEnabled: false,
                            initialFrameHeight: 420,
                            initialFrameWidth: f.getWidth() - 20
                        })
                    }
                }
            })
        } else {
            var b = e.baseUrl + "&task=ms_loadTemplateFile&editAction=" + e.editAction + "&uFlowId=" + e.uFlowId;
            var c = "index.php";
            if (e.tplSort == 1) {
                var d = "doc"
            } else {
                var d = "xls"
            }
            var a = "a=b&c=d&flowId=" + e.flowId;
            $("#wf_newfree_formct").html("").css("border", "none");
            openOfficeForEdit_WF("wf_newfree_formct", b, d, "自由流程表单", 0, c, a, true)
        }
        e.loadUflowInfo();
        setTimeout(function() {
                $("#CNOA_WEBOFFICE").height(769)
            },
            200);
        if (e.flowType == 0 && e.tplSort == 0) {
            $("#wf_form_newfree_tushi").children().show()
        } else {
            $("#wf_form_newfree_tushi").children().hide()
        }
    },
    loadUflowInfo: function() {
        var _this = this;
        var fp = _this.formPanel,
            f = fp.getForm();
        f.load({
            url: _this.baseUrl + "&task=loadUflowInfo",
            method: "POST",
            params: {
                flowId: _this.flowId,
                uFlowId: _this.uFlowId,
                editAction: _this.editAction,
                flowType: _this.flowType,
                tplSort: _this.tplSort
            },
            success: function(form, action) {
                if (_this.flowType != 0 && _this.tplSort == 0) {
                    if (_this.editAction == "edit") {
                        _this.editor.setContent(action.result.data.htmlFormContent)
                    }
                }
                if (action.result.attach) {
                    if (action.result.attach.length > 0) {
                        Ext.fly(_this.ID_displayAt).dom.style.display = "block";
                        _this.createAttachList(action.result.attach)
                    }
                }
                if (action.result.config) {
                    var config = action.result.config;
                    var flowNumber = _this.formPanel.getForm().findField("flowNumber");
                    with(config) {
                        allowEditFlowNumber == "1" ? flowNumber.setReadOnly(false) : flowNumber.setReadOnly(true)
                    }
                }
            }
        })
    },
    sendNextStep: function() {
        var e = this;
        var a = "";
        if (e.tplSort == 0) {
            a = e.editor.getContent()
        }
        if (e.flowType == 1) {
            var c = function(f) {
                var g = e.baseUrl + "&task=ms_submitMsOfficeData&uFlowId=" + f;
                CNOA.WOWF.saveOffice(g);
                mainPanel.closeTab("CNOA_MENU_WF_USE_OPENFLOW");
                CNOA.msg.notice(lang("flowIntoStep"), lang("workFlow"))
            };
            var d = e.formPanel.getForm();
            var b = Ext.getCmp(e.ID_dealStepUid).getValue();
            if (d.isValid()) {
                if (Ext.isEmpty(b)) {
                    CNOA.miniMsg.alertShowAt(Ext.getCmp(e.ID_save), lang("flowDealStepEmpty"));
                    return false
                }
                CNOA.msg.cf(lang("confirmNextStep"),
                    function(f) {
                        if (f == "yes") {
                            d.submit({
                                url: e.baseUrl + "&task=seqFlowSendNextStep",
                                method: "POST",
                                params: {
                                    uFlowId: e.uFlowId,
                                    flowId: e.flowId,
                                    htmlFormContent: a
                                },
                                success: function(h, j) {
                                    var g = j.result.data.uFlowId;
                                    if (e.tplSort != 0) {
                                        c(g)
                                    } else {
                                        CNOA.msg.notice(lang("flowIntoStep"), lang("workFlow"))
                                    }
                                    if (e.editAction == "add") {
                                        e.closeTab()
                                    } else {
                                        mainPanel.closeTab("CNOA_MENU_WF_USE_OPENFLOW");
                                        try {
                                            CNOA_wf_use_draft.store.reload()
                                        } catch(k) {}
                                    }
                                },
                                failure: function(g, h) {
                                    CNOA.msg.alert(h.result.msgInfo.msg,
                                        function() {})
                                }
                            })
                        }
                    })
            }
        } else {
            CNOA_wf_use_newfree_goNextStepWin = new CNOA_wf_use_newfree_goNextStepWinClass(e.flowId, e.tplSort, e.flowType, "newfree", 0, e.uFlowId, "", e.editAction);
            CNOA_wf_use_newfree_goNextStepWin.show()
        }
    },
    setFlowStepWindow: function() {
        var a = this;
        CNOA_wf_use_newfree_setFlowStepWin = new CNOA_wf_use_newfree_setFlowStepWinClass(a.flowType, a.tplSort);
        if (a.flowType == 1 && a.tplSort == 0) {
            Ext.getCmp(a.ID_HtmlEditor).hide()
        }
    },
    closeTab: function() {
        mainPanel.closeTab("CNOA_USE_FLOW_NEWFREE_FLOWDESIGN")
    }
};
var CNOA_wf_use_viewClass, CNOA_wf_use_view;
CNOA_wf_use_viewClass = CNOA.Class.create();
CNOA_wf_use_viewClass.prototype = {
    init: function() {
        var d = this;
        var c = Ext.id();
        var b = Ext.id();
        this.baseUrl = "index.php?app=wf&func=flow&action=use&modul=view";
        this.storeBar = {
            sname: "",
            flowName: ""
        };
        this.fields = [{
            name: "uFlowId"
        },
            {
                name: "flowId"
            },
            {
                name: "flowNumber"
            },
            {
                name: "sname"
            },
            {
                name: "uname"
            },
            {
                name: "flowName"
            },
            {
                name: "flowSetName"
            },
            {
                name: "flowType"
            },
            {
                name: "tplSort"
            },
            {
                name: "level"
            },
            {
                name: "step"
            },
            {
                name: "postTime"
            },
            {
                name: "isread"
            },
            {
                name: "disposeType"
            },
            {
                name: "storeType"
            }];
        this.store = new Ext.data.Store({
            proxy: new Ext.data.HttpProxy({
                url: this.baseUrl + "&task=getJsonData",
                disableCaching: true
            }),
            reader: new Ext.data.JsonReader({
                totalProperty: "total",
                root: "data",
                fields: this.fields
            }),
            remoteSort: true
        });
        this.flowTypeStore = new Ext.data.Store({
            proxy: new Ext.data.HttpProxy({
                url: d.baseUrl + "&task=getFlowTypeData"
            }),
            reader: new Ext.data.JsonReader({
                root: "data",
                fields: [{
                    name: "sortId"
                },
                    {
                        name: "sname"
                    }]
            })
        });
        this.flowTypeStore.load();
        var a = function(e) {
            if (e == "5") {
                e = "待办加急"
            } else {
                if (e == "4") {
                    e = "待办"
                } else {
                    if (e == "3") {
                        e = "暂停"
                    } else {
                        if (e == "2") {
                            e = "完成"
                        } else {
                            if (e == "1") {
                                e = "取消"
                            }
                        }
                    }
                }
            }
            return e
        };
        this.sm = new Ext.grid.CheckboxSelectionModel({
            singleSelect: false
        });
        this.store.load();
        this.colModel = new Ext.grid.ColumnModel([new Ext.grid.RowNumberer(), {
            header: "uFlowId",
            dataIndex: "uFlowId",
            hidden: true
        },
            {
                header: lang("flowNumber") + " / " + lang("customTitle"),
                dataIndex: "flowNumber",
                width: 200,
                id: "flowNumber",
                sortable: true,
                menuDisabled: true,
                renderer: this.formatNumber.createDelegate(this)
            },
            {
                header: lang("initiator") + " / " + lang("initTime"),
                dataIndex: "uname",
                width: 110,
                sortable: true,
                menuDisabled: true,
                renderer: this.formatUser.createDelegate(this)
            },
            {
                header: lang("sort2") + " / " + lang("belongFlow"),
                dataIndex: "sname",
                id: "sname",
                width: 150,
                sortable: true,
                menuDisabled: true,
                renderer: this.formatSort.createDelegate(this)
            },
            {
                header: lang("importantGrade"),
                dataIndex: "level",
                width: 60,
                sortable: false,
                menuDisabled: true
            },
            {
                header: lang("status"),
                dataIndex: "isread",
                width: 70,
                sortable: false,
                menuDisabled: true
            },
            {
                header: "处理状态",
                dataIndex: "disposeType",
                width: 70,
                menuDisabled: true,
                renderer: a,
                sortable: true
            },
            {
                header: lang("opt"),
                dataIndex: "uFlowId",
                width: 100,
                sortable: false,
                menuDisabled: true,
                renderer: this.makeOperate.createDelegate(this)
            },
            {
                header: "",
                dataIndex: "noIndex",
                width: 20,
                menuDisabled: true,
                resizable: false
            }]);
        this.grid = new Ext.grid.PageGridPanel({
            stripeRows: true,
            store: this.store,
            searchStore: this.storeBar,
            loadMask: {
                msg: lang("waiting")
            },
            cm: this.colModel,
            hideBorders: true,
            border: false,
            region: "center",
            layout: "fit",
            scriptRows: true,
            autoExpandColumn: "flowNumber",
            tbar: new Ext.Toolbar({
                items: [{
                    iconCls: "icon-system-refresh",
                    text: lang("refresh"),
                    handler: function(e, f) {
                        d.store.reload()
                    }.createDelegate(this)
                },
                    {
                        text: lang("flowToView"),
                        iconCls: "icon-flow-to-view",
                        enableToggle: true,
                        cls: "btn-blue4",
                        allowDepress: false,
                        pressed: true,
                        toggleGroup: "flow_use_todo_type",
                        handler: function(e, f) {
                            d.store.setBaseParam("storeType", "waiting");
                            d.grid.getColumnModel().setHidden(7, true);
                            d.store.load()
                        }.createDelegate(this)
                    },
                    {
                        text: lang("flowReaded"),
                        iconCls: "icon-flow-readed",
                        cls: "btn-red1",
                        enableToggle: true,
                        allowDepress: false,
                        toggleGroup: "flow_use_todo_type",
                        handler: function(e, f) {
                            d.store.setBaseParam("storeType", "finish");
                            d.grid.getColumnModel().setHidden(7, false);
                            d.store.load()
                        }.createDelegate(this)
                    },
                    "->", lang("flowName") + lang("or") + lang("bianHao") + ":", {
                        xtype: "textfield",
                        width: 200,
                        id: c,
                        listeners: {
                            specialkey: function(g, f) {
                                if (f.getKey() == f.ENTER) {
                                    d.storeBar.flowName = Ext.getCmp(c).getValue();
                                    d.storeBar.sname = Ext.getCmp(b).getValue();
                                    d.store.load({
                                        params: d.storeBar
                                    })
                                }
                            }
                        }
                    },
                    lang("processType"), {
                        xtype: "combo",
                        name: "sname",
                        id: b,
                        hiddenName: "sname",
                        displayField: "sname",
                        valueField: "sortId",
                        triggerAction: "all",
                        forceSelection: true,
                        mode: "local",
                        editable: false,
                        store: this.flowTypeStore
                    },
                    {
                        xtype: "button",
                        text: lang("search"),
                        handler: function() {
                            d.storeBar.flowName = Ext.getCmp(c).getValue();
                            d.storeBar.sname = Ext.getCmp(b).getValue();
                            d.store.load({
                                params: d.storeBar
                            })
                        }
                    },
                    {
                        xtype: "button",
                        text: lang("clear"),
                        handler: function() {
                            Ext.getCmp(c).setValue("");
                            Ext.getCmp(b).setValue("");
                            d.storeBar.flowName = "";
                            d.storeBar.sname = "";
                            d.store.load({
                                params: d.storeBar
                            })
                        }
                    }]
            }),
            listeners: {
                celldblclick: function(h, k, j) {
                    var g = h.getSelectionModel().getSelected().data.uFlowId;
                    var f = h.getSelectionModel().getSelected().data.isread;
                    if (f == "已阅") {
                        d.updateDisposeType(g)
                    }
                },
                beforerender: function() {
                    d.grid.getColumnModel().setHidden(7, true)
                }
            }
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
    updateDisposeType: function(a) {
        var g = this;
        var d = Ext.id();
        var f = new Ext.data.Store({
            autoLoad: true,
            baseParams: {
                uFlowId: a
            },
            proxy: new Ext.data.HttpProxy({
                url: g.baseUrl + "&task=getDisposeType",
                disableCaching: true
            }),
            reader: new Ext.data.JsonReader({
                totalProperty: "total",
                root: "data",
                fields: this.fields
            }),
            listeners: {
                load: function(m, k, o, j, h) {
                    var n = m.reader.jsonData.data.disposeType;
                    Ext.getCmp(d).setValue(n)
                }
            }
        });
        var b = new Ext.form.FormPanel({
            border: false,
            labelAlign: "right",
            labelWidth: 80,
            padding: 5,
            autoScroll: true,
            items: [{
                xtype: "combo",
                displayField: "name",
                valueField: "id",
                hiddenName: "disposeType",
                id: d,
                triggerAction: "all",
                width: 186,
                fieldLabel: "处理状态",
                editable: false,
                mode: "local",
                value: 4,
                store: new Ext.data.ArrayStore({
                    fields: ["id", "name"],
                    data: [["5", "待办加急"], ["4", "待办"], ["3", "暂停"], ["2", "完成"], ["1", "取消"]]
                })
            }]
        });
        var e = new Ext.Window({
            width: 300,
            autoHeight: true,
            modal: true,
            title: "修改处理状态",
            items: [b],
            buttons: [{
                text: lang("ok"),
                iconCls: "icon-btn-save",
                handler: function() {
                    c(a)
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
        var c = function(h) {
            if (b.getForm().isValid()) {
                b.getForm().submit({
                    url: g.baseUrl + "&task=updateDisposeType",
                    params: {
                        uFlowId: h
                    },
                    method: "POST",
                    success: function(j, k) {
                        CNOA.msg.notice2(lang("successopt"));
                        g.store.load();
                        e.close()
                    },
                    failure: function(j, k) {
                        CNOA.msg.alert(k.result.msg)
                    }
                })
            }
        }
    },
    formatUser: function(e, c, a, f, b, d) {
        return e + "<br>" + a.data.postTime
    },
    formatNumber: function(e, c, a, f, b, d) {
        return "<span >" + e + "</span><br /><span class=cnoa_color_gray>" + a.data.flowName + "</span>"
    },
    formatSort: function(e, c, a, f, b, d) {
        return "<span >" + e + "</span><br /><span class=cnoa_color_gray>" + a.data.flowSetName + "</span>"
    },
    makeOperate: function(g, e, a, j, c, f) {
        var d = a.data;
        var b = "";
        b += "<a class='gridview' href='javascript:void(0);' onclick='CNOA_wf_use_view.viewFlow(" + g + ", " + d.flowId + ", " + d.step + ", " + d.flowType + ", " + d.tplSort + ");' ext:qtip='查看详情'>" + lang("view") + "</a>";
        return b
    },
    viewFlow: function(a, c, e, b, d) {
        mainPanel.closeTab("CNOA_MENU_WF_USE_OPENFLOW");
        mainPanel.loadClass("index.php?app=wf&func=flow&action=use&modul=view&task=loadPage&from=viewflow&uFlowId=" + a + "&flowId=" + c + "&stepId=" + e + "&flowType=" + b + "&tplSort=" + d, "CNOA_MENU_WF_USE_OPENFLOW", lang("viewFlow"), "icon-flow")
    }
};
var CNOA_wf_use_doneClass, CNOA_wf_use_done;
CNOA_wf_use_doneClass = CNOA.Class.create();
CNOA_wf_use_doneClass.prototype = {
    init: function() {
        this.baseUrl = "index.php?app=wf&func=flow&action=use&modul=done";
        this.storeBar = {
            storeType: "running",
            flowType: 0,
            flowTitle: "",
            faqiId: 0,
            stime: "",
            etime: ""
        };
        var a = [{
            name: "uFlowId"
        },
            {
                name: "flowId"
            },
            {
                name: "flowNumber"
            },
            {
                name: "sname"
            },
            {
                name: "flowName"
            },
            {
                name: "flowSetName"
            },
            {
                name: "tplSort"
            },
            {
                name: "flowType"
            },
            {
                name: "step"
            },
            {
                name: "level"
            },
            {
                name: "postTime"
            },
            {
                name: "endtime"
            },
            {
                name: "status"
            },
            {
                name: "statusText"
            },
            {
                name: "owner"
            },
            {
                name: "allowCallback"
            },
            {
                name: "allowCancel"
            },
            {
                name: "proxyText"
            },
            {
                name: "curUname"
            }];
        this.store = new Ext.data.Store({
            autoLoad: true,
            baseParams: this.storeBar,
            proxy: new Ext.data.HttpProxy({
                url: this.baseUrl + "&task=getJsonData",
                disableCaching: true
            }),
            reader: new Ext.data.JsonReader({
                totalProperty: "total",
                root: "data",
                fields: a
            })
        });
        this.flowTypeStore = new Ext.data.Store({
            autoLoad: true,
            proxy: new Ext.data.HttpProxy({
                url: this.baseUrl + "&task=getFlowTypeData"
            }),
            reader: new Ext.data.JsonReader({
                root: "data",
                fields: [{
                    name: "sortId"
                },
                    {
                        name: "sname"
                    }]
            })
        });
        this.searchWin;
        this.mainPanel = this.getListPanel()
    },
    getListPanel: function() {
        var d = this;
        var g = new Ext.grid.ColumnModel({
            defaults: {
                sortable: false,
                menuDisabled: true
            },
            columns: [new Ext.grid.RowNumberer(), {
                header: "uFlowId",
                dataIndex: "uFlowId",
                hidden: true
            },
                {
                    header: lang("flowNumber") + " / " + lang("customTitle"),
                    dataIndex: "flowNumber",
                    id: "flowNumber",
                    width: 200,
                    renderer: this.formatNumber
                },
                {
                    header: lang("sort2") + " / " + lang("belongFlow"),
                    dataIndex: "sname",
                    id: "sname",
                    width: 150,
                    renderer: this.formatSort
                },
                {
                    header: lang("launch") + " / " + lang("endTime"),
                    dataIndex: "postTime",
                    width: 114,
                    renderer: function(o, p, n) {
                        return o + "<br />" + n.data.endtime
                    }
                },
                {
                    header: lang("importantGrade"),
                    dataIndex: "level",
                    width: 64
                },
                {
                    header: lang("currentDealPeople"),
                    dataIndex: "curUname",
                    width: 100,
                    renderer: function(o, p, n) {
                        p.attr = 'style="white-space:pre-wrap;"';
                        return o
                    }
                },
                {
                    header: lang("status"),
                    dataIndex: "statusText",
                    width: 64,
                    renderer: this.makeStatus
                },
                {
                    header: lang("opt"),
                    dataIndex: "uFlowId",
                    width: 170,
                    renderer: this.makeOperate.createDelegate(this)
                },
                {
                    header: "",
                    dataIndex: "noIndex",
                    width: 20,
                    resizable: false
                }]
        });
        var b = Ext.id();
        var f = new Ext.form.TextField({
                width: 100
            }),
            c = new Ext.form.DateField({
                editable: false,
                format: "Y-m-d",
                width: 100
            }),
            j = new Ext.form.DateField({
                editable: false,
                format: "Y-m-d",
                width: 100
            }),
            m = new Ext.form.Hidden({
                id: b
            }),
            h = new Ext.form.TextField({
                width: 110,
                readOnly: true,
                listeners: {
                    render: function(n) {
                        n.to = b
                    },
                    focus: function(n) {
                        if (d.storeBar.storeType == "mypublish") {
                            CNOA.msg.alert("当前查询的发起人为自己",
                                function() {});
                            this.setValue("")
                        } else {
                            people_selector("user", n, true, false)
                        }
                    }
                }
            }),
            e = new Ext.form.ComboBox({
                editable: false,
                store: this.flowTypeStore,
                mode: "local",
                displayField: "sname",
                valueField: "sortId",
                triggerAction: "all",
                focusSelection: true,
                width: 100
            });
        var k = new Ext.Toolbar({
                items: [{
                    iconCls: "icon-system-refresh",
                    text: lang("refresh"),
                    handler: function(n, o) {
                        d.store.reload()
                    }
                },
                    {
                        text: lang("inFlow"),
                        iconCls: "icon-inFlow",
                        enableToggle: true,
                        cls: "btn-blue4",
                        allowDepress: false,
                        pressed: true,
                        toggleGroup: "flow_use_done_type",
                        handler: function(n, o) {
                            d.storeBar.storeType = "running";
                            d.store.load({
                                params: d.storeBar
                            })
                        }
                    },
                    {
                        text: lang("alreadyOver"),
                        iconCls: "icon-already-over",
                        cls: "btn-gray1",
                        enableToggle: true,
                        allowDepress: false,
                        toggleGroup: "flow_use_done_type",
                        handler: function(n, o) {
                            d.storeBar.storeType = "done";
                            d.store.load({
                                params: d.storeBar
                            })
                        }
                    },
                    {
                        text: lang("All"),
                        iconCls: "icon-all",
                        cls: "btn-red1",
                        enableToggle: true,
                        allowDepress: false,
                        toggleGroup: "flow_use_done_type",
                        handler: function(n, o) {
                            d.storeBar.storeType = "all";
                            d.store.load({
                                params: d.storeBar
                            })
                        }
                    },
                    {
                        text: lang("returned"),
                        iconCls: "icon-roduction",
                        cls: "btn-gray1",
                        enableToggle: true,
                        allowDepress: false,
                        toggleGroup: "flow_use_done_type",
                        handler: function(n, o) {
                            d.storeBar.storeType = "returned";
                            d.store.load({
                                params: d.storeBar
                            })
                        }
                    },
                    {
                        text: "我发起的流程",
                        iconCls: "icon-roduction",
                        cls: "btn-blue4",
                        enableToggle: true,
                        allowDepress: false,
                        toggleGroup: "flow_use_done_type",
                        handler: function(n, o) {
                            h.setValue("");
                            m.setValue("");
                            d.storeBar.flowTitle = "";
                            d.storeBar.storeType = "mypublish";
                            d.store.load({
                                params: d.storeBar
                            })
                        }
                    },
                    {
                        text: "我办理的流程",
                        iconCls: "icon-roduction",
                        cls: "btn-blue4",
                        enableToggle: true,
                        allowDepress: false,
                        toggleGroup: "flow_use_done_type",
                        handler: function(n, o) {
                            d.storeBar.storeType = "mymanagement";
                            d.store.load({
                                params: d.storeBar
                            })
                        }
                    },
                    lang("flowNumber") + "/" + lang("customTitle") + ":", f, (lang("sort2") + " / " + lang("belongFlow")), e, lang("initiator") + ":", h]
            }),
            a = new Ext.Toolbar({
                items: [lang("initTime") + ":", c, lang("to"), j, {
                    xtype: "button",
                    text: lang("search"),
                    handler: function() {
                        d.storeBar.flowType = e.getValue();
                        d.storeBar.flowTitle = f.getValue();
                        d.storeBar.faqiId = m.getValue();
                        d.storeBar.stime = c.getRawValue();
                        d.storeBar.etime = j.getRawValue();
                        d.store.reload()
                    }
                },
                    {
                        xtype: "button",
                        text: lang("advanceQuery"),
                        cls: "btn-blue4",
                        iconCls: "icon-search",
                        style: "margin-left:5px",
                        handler: function() {
                            if (!d.searchWin) {
                                d.searchWin = new CNOA_wf_advSearchWinClass(d)
                            }
                            d.searchWin.show()
                        }
                    },
                    {
                        xtype: "button",
                        text: lang("clear"),
                        pressed: true,
                        handler: function() {
                            e.setValue("");
                            f.setValue("");
                            h.setValue("");
                            m.setValue("");
                            c.setValue("");
                            j.setValue("");
                            d.storeBar.flowType = 0;
                            d.storeBar.flowTitle = "";
                            d.storeBar.faqiId = 0;
                            d.storeBar.stime = "";
                            d.storeBar.etime = "";
                            d.store.reload()
                        }
                    }]
            });
        return new Ext.grid.PageGridPanel({
            store: this.store,
            cm: g,
            searchStore: this.storeBar,
            stripeRows: true,
            autoExpandColumn: "flowNumber",
            tbar: new Ext.Container({
                items: [k, a]
            })
        })
    },
    formatNumber: function(d, c, a) {
        var b = a.data;
        if (!Ext.isEmpty(b.proxyText)) {
            return d + "[<span class=cnoa_color_red>" + b.proxyText + "</span>]<br /><span class=cnoa_color_gray>" + b.flowName + "</span>"
        } else {
            return d + b.proxyText + "<br /><span class=cnoa_color_gray>" + b.flowName + "</span>"
        }
    },
    formatSort: function(c, b, a) {
        return "<span >" + c + "</span><br /><span class='cnoa_color_gray' ext:qtip='" + a.data.flowSetName + "'>" + a.data.flowSetName + "</span>"
    },
    makeStatus: function(d, c, a) {
        var b = a.data.status;
        var e = "#00000";
        if (b == 2) {
            e = "#008000"
        } else {
            if (b == 3) {
                e = "#FF00FF"
            } else {
                if (b == 4) {
                    e = "#333333"
                } else {
                    if (b == 6) {
                        e = "#9900FF"
                    }
                }
            }
        }
        return "<span style='color:" + e + ";'>" + d + "</span>"
    },
    makeOperate: function(f, d, a) {
        var c = a.data,
            e = "";
        var b = "<a class='gridview' href='javascript:void(0);' onclick='CNOA_wf_use_done.showFlow(" + f + ", " + c.flowId + ", " + c.step + ", " + c.flowType + ", " + c.tplSort + ", " + c.owner + ");' ext:qtip='" + lang("viewDetail") + "'>" + lang("view") + "</a>";
        if (this.storeBar.storeType == "running" || this.storeBar.storeType == "all" || this.storeBar.storeType == "mypublish" || this.storeBar.storeType == "mymanagement") {
            if (c.owner == 1) {
                if (c.allowCallback == 1) {
                    b += "<a href='javascript:void(0);' class='gridview3 jianju' onclick='CNOA_wf_use_done.callback(" + f + ", " + c.flowId + ", " + c.step + ");' ext:qtip='" + lang("recallProcess") + "'>" + lang("recall") + "</a>"
                }
                if (c.allowCancel == 1) {
                    var e = "";
                    if (c.allowCallback == 1) {
                        e = "jianju"
                    }
                    b += "<a href='javascript:void(0);' class='gridview2 " + e + "'  onclick='CNOA_wf_use_done.cancelflow(" + f + ", " + c.flowId + ", " + c.step + ");' ext:qtip='" + lang("revocationProcess") + "'>" + lang("recell") + "</a>"
                }
            }
        }
        if (c.flowType == "0" && c.tplSort == "0" && c.uid == CNOA_USER_UID && c.againStatus == 1) {
            l += "<a href='javascript:void(0)' class='gridview4 jianju' onclick='CNOA_wf_mgr_list.again(" + f + ", " + c.flowId + ", " + c.flowType + " ," + c.tplSort + ")'>" + lang("againNewFlow") + "</a>"
        }
        return b
    },
    showFlow: function(b, d, e, c, f, a) {
        var g = this;
        mainPanel.closeTab("CNOA_MENU_WF_USE_OPENFLOW");
        mainPanel.loadClass("index.php?app=wf&func=flow&action=use&modul=done&task=loadPage&from=showflow&uFlowId=" + b + "&flowId=" + d + "&step=" + e + "&flowType=" + c + "&tplSort=" + f + "&owner=" + a + "&type=" + g.storeBar.storeType, "CNOA_MENU_WF_USE_OPENFLOW", lang("viewFlow"), "icon-flow")
    },
    callback: function(a, b, c) {
        var d = this;
        CNOA.msg.cf(lang("wantToRecall"),
            function(e) {
                if (e == "yes") {
                    Ext.Ajax.request({
                        url: d.baseUrl + "&task=callback",
                        method: "POST",
                        params: {
                            uFlowId: a,
                            flowId: b,
                            stepId: c
                        },
                        success: function(g) {
                            var f = Ext.decode(g.responseText);
                            if (f.success === true) {
                                CNOA.msg.alert(lang("recallSuccess"),
                                    function() {
                                        d.store.reload()
                                    })
                            } else {
                                CNOA.msg.alert(f.msg,
                                    function() {})
                            }
                        }
                    })
                }
            })
    },
    cancelflow: function(a, b, c) {
        var d = this;
        CNOA.msg.cf(lang("sureWantCancelFlow"),
            function(e) {
                if (e == "yes") {
                    Ext.Ajax.request({
                        url: d.baseUrl + "&task=cancelflow",
                        method: "POST",
                        params: {
                            uFlowId: a,
                            flowId: b,
                            stepId: c
                        },
                        success: function(g) {
                            var f = Ext.decode(g.responseText);
                            if (f.success === true) {
                                CNOA.msg.notice(f.msg, lang("flowRevoked"));
                                d.store.reload()
                            } else {
                                CNOA.msg.alert(f.msg,
                                    function() {})
                            }
                        }
                    })
                }
            })
    }
};
var CNOA_wf_use_draftClass, CNOA_wf_use_draft;
CNOA_wf_use_draftClass = CNOA.Class.create();
CNOA_wf_use_draftClass.prototype = {
    init: function() {
        var a = this;
        this.ID_SEARCH_TEXT_NAME = Ext.id();
        this.baseUrl = "index.php?app=wf&func=flow&action=use&modul=draft";
        this.storeBar = {
            flowname: ""
        };
        this.fields = [{
            name: "flowId"
        },
            {
                name: "uFlowId"
            },
            {
                name: "flowNumber"
            },
            {
                name: "flowName"
            },
            {
                name: "flowSetName"
            },
            {
                name: "sname"
            },
            {
                name: "edittime"
            },
            {
                name: "flowType"
            },
            {
                name: "tplSort"
            }];
        this.store = new Ext.data.Store({
            proxy: new Ext.data.HttpProxy({
                url: this.baseUrl + "&task=getJsonData",
                disableCaching: true
            }),
            reader: new Ext.data.JsonReader({
                totalProperty: "total",
                root: "data",
                fields: this.fields
            })
        });
        this.sm = new Ext.grid.CheckboxSelectionModel({
            singleSelect: false
        });
        this.store.load();
        this.colModel = new Ext.grid.ColumnModel([this.sm, new Ext.grid.RowNumberer(), {
            header: "uFlowId",
            dataIndex: "uFlowId",
            hidden: true
        },
            {
                header: lang("flowNumber") + " / " + lang("customTitle"),
                dataIndex: "flowNumber",
                id: "flowNumber",
                width: 180,
                sortable: true,
                menuDisabled: true,
                renderer: this.formatNumber.createDelegate(this)
            },
            {
                header: lang("sort2") + " / " + lang("belongFlow"),
                dataIndex: "sname",
                width: 150,
                sortable: false,
                menuDisabled: true,
                renderer: this.formatName.createDelegate(this)
            },
            {
                header: lang("modifiedDate"),
                dataIndex: "edittime",
                width: 130,
                sortable: false,
                menuDisabled: true
            },
            {
                header: lang("opt"),
                dataIndex: "uFlowId",
                width: 110,
                sortable: false,
                menuDisabled: true,
                renderer: this.makeOperate.createDelegate(this)
            },
            {
                header: "",
                dataIndex: "noIndex",
                width: 1,
                menuDisabled: true,
                resizable: false
            }]);
        this.grid = new Ext.grid.PageGridPanel({
            stripeRows: true,
            store: this.store,
            searchStore: this.storeBar,
            loadMask: {
                msg: lang("waiting")
            },
            cm: this.colModel,
            sm: this.sm,
            hideBorders: true,
            border: false,
            region: "center",
            autoExpandColumn: "flowNumber",
            tbar: new Ext.Toolbar({
                items: [{
                    iconCls: "icon-bulk-delete",
                    text: lang("bulkDel"),
                    cls: "btn-red1",
                    handler: function(b, c) {
                        a.remove()
                    }.createDelegate(this)
                },
                    {
                        iconCls: "icon-system-refresh",
                        text: lang("refresh"),
                        handler: function(b, c) {
                            a.store.reload()
                        }.createDelegate(this)
                    },
                    "->", (lang("flowName") + ":"), {
                        xtype: "textfield",
                        width: 200,
                        id: this.ID_SEARCH_TEXT_NAME,
                        listeners: {
                            specialkey: function(c, b) {
                                if (b.getKey() == b.ENTER) {
                                    a.storeBar.flowname = c.getValue();
                                    a.store.load({
                                        params: a.storeBar
                                    })
                                }
                            }
                        }
                    },
                    {
                        xtype: "button",
                        text: lang("search"),
                        handler: function() {
                            a.storeBar.flowname = Ext.getCmp(a.ID_SEARCH_TEXT_NAME).getValue();
                            a.store.load({
                                params: a.storeBar
                            })
                        }
                    },
                    {
                        xtype: "button",
                        text: lang("clear"),
                        handler: function() {
                            Ext.getCmp(a.ID_SEARCH_TEXT_NAME).setValue("");
                            a.storeBar.flowname = "";
                            a.store.load({
                                params: a.storeBar
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
            items: [this.grid]
        })
    },
    formatNumber: function(f, d, a, h, c, e) {
        var b = a.data.status;
        var g = "#00000";
        if (b == 1) {
            g = "#FF0000"
        } else {
            if (b == 2) {
                g = "#008000"
            } else {
                if (b == 3) {
                    g = "#FF00FF"
                } else {
                    if (b == 4) {
                        g = "#808080"
                    } else {
                        g = "#9900FF"
                    }
                }
            }
        }
        return "<span >" + f + "</span><br /><span class='cnoa_color_gray'>" + a.data.flowName + "&nbsp;</span>"
    },
    formatName: function(e, c, a, f, b, d) {
        return "<span >" + e + "</span><br /><span class='cnoa_color_gray' ext:qtip='" + a.data.flowSetName + "'>" + a.data.flowSetName + "</span>"
    },
    makeOperate: function(j, f, d, g, e, b) {
        var k = d.data;
        var a = "./resources/images/icons/";
        var c = "<a href='javascript:void(0);' class='gridview4' onclick='CNOA_wf_use_draft.editPanel(" + j + ", " + k.flowType + ", " + k.tplSort + ");'>" + lang("edit") + "</a>";
        c += "<a href='javascript:void(0);' class='gridview2 jianju' onclick='CNOA_wf_use_draft.deleteFlow(" + k.uFlowId + ");'>" + lang("del") + "</a>";
        c += "</div>";
        return c
    },
    editPanel: function(a, b, c) {
        var d = this;
        mainPanel.closeTab("CNOA_MENU_WF_USE_OPENFLOW");
        mainPanel.loadClass("index.php?app=wf&func=flow&action=use&modul=new&task=loadPage&from=editflow&uFlowId=" + a + "&flowType=" + b + "&tplSort=" + c, "CNOA_MENU_WF_USE_OPENFLOW", "编辑流程", "icon-flow-new")
    },
    deleteFlow: function(a) {
        var b = this;
        CNOA.msg.cf(lang("sureDelFlowDraft"),
            function(c) {
                if (c == "yes") {
                    Ext.Ajax.request({
                        url: b.baseUrl + "&task=deleteFlowList",
                        method: "POST",
                        params: {
                            uFlowId: a
                        },
                        success: function(e) {
                            var d = Ext.decode(e.responseText);
                            if (d.success === true) {
                                CNOA.msg.notice(d.msg, lang("dealFlow"));
                                b.store.reload()
                            } else {
                                CNOA.msg.alert(d.msg)
                            }
                        }
                    })
                }
            })
    },
    remove: function() {
        var e = this;
        var d = this.grid.getSelectionModel().getSelections();
        if (d.length == 0) {
            var a = Ext.getCmp(this.ID_btn_delete).getEl().getBox();
            a = [a.x + 12, a.y + 26];
            CNOA.miniMsg.alert(lang("mustSelectOneRow"), a)
        } else {
            if (d) {
                var c = [];
                for (var b = 0; b < d.length; b++) {
                    c.push(d[b].get("uFlowId"))
                }
                e.deleteFlow(c.join(","))
            }
        }
    }
};
var CNOA_wf_use_listClass, CNOA_wf_use_list;
var CNOA_wf_use_needListClass, CNOA_wf_use_needList;
CNOA_wf_use_needListClass = CNOA.Class.create();
CNOA_wf_use_needListClass.prototype = {
    init: function() {
        var a = this;
        this.ID_FAVNOTICE = Ext.id();
        this.baseUrl = "index.php?app=wf&func=flow&action=use&modul=new";
        this.fields = [{
            name: "flowId"
        },
            {
                name: "flowName"
            },
            {
                name: "sname"
            },
            {
                name: "about"
            },
            {
                name: "abouttip"
            },
            {
                name: "nameRuleId"
            },
            {
                name: "tplSort"
            },
            {
                name: "flowType"
            },
            {
                name: "childId"
            },
            {
                name: "flowNumber"
            },
            {
                name: "puFlowName"
            },
            {
                name: "puFlowId"
            }];
        this.store = new Ext.data.Store({
            proxy: new Ext.data.HttpProxy({
                url: this.baseUrl + "&task=getJsonData&from=need",
                disableCaching: true
            }),
            reader: new Ext.data.JsonReader({
                totalProperty: "total",
                root: "data",
                fields: this.fields
            })
        });
        this.sm = new Ext.grid.CheckboxSelectionModel({
            singleSelect: false
        });
        this.store.load({
            params: {
                from: "need"
            }
        });
        this.colModel = new Ext.grid.ColumnModel([new Ext.grid.RowNumberer(), {
            header: "flowId",
            dataIndex: "flowId",
            hidden: true
        },
            {
                header: lang("flowName"),
                dataIndex: "flowName",
                id: "flowName",
                width: 140,
                sortable: true,
                menuDisabled: true
            },
            {
                header: lang("puFlowNum"),
                dataIndex: "flowNumber",
                width: 120,
                sortable: false,
                menuDisabled: true
            },
            {
                header: lang("puFlowName"),
                dataIndex: "puFlowName",
                width: 160,
                sortable: false,
                menuDisabled: true
            },
            {
                header: lang("sort2"),
                dataIndex: "sname",
                width: 140,
                sortable: false,
                menuDisabled: true
            },
            {
                header: lang("opt"),
                dataIndex: "flowId",
                width: 60,
                sortable: false,
                menuDisabled: true,
                renderer: this.makeOperate.createDelegate(this)
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
            hideBorders: true,
            border: false,
            region: "center",
            autoExpandColumn: "flowName",
            stripeRows: true
        });
        this.mainPanel = new Ext.Window({
            title: lang("launchZLC"),
            width: 780,
            height: 500,
            layout: "fit",
            modal: true,
            maximizable: true,
            items: [this.grid]
        }).show()
    },
    makeOperate: function(k, g, e, j, f, b) {
        var m = e.data;
        var a = "./resources/images/icons/";
        var c = m.childId;
        if (c == undefined || c == "") {
            c = 0
        }
        var d = "<div style='margin-top:2px;'><a href='javascript:void(0);' onclick='CNOA_wf_use_list.newFlow(" + k + "," + m.nameRuleId + ", " + m.tplSort + ", " + m.flowType + ", " + c + ", " + m.puFlowId + ");CNOA_wf_use_needList.close();'><img src='" + a + "flow-new.png' style='margin:2px 2px 4px 2px' align='absmiddle' />" + lang("launch") + "</a>";
        d += "</div>";
        return d
    },
    close: function() {
        this.mainPanel.close()
    }
};
CNOA_wf_use_listClass = CNOA.Class.create();
CNOA_wf_use_listClass.prototype = {
    init: function() {
        var a = this;
        this.ID_FAVNOTICE = Ext.id();
        this.baseUrl = "workflow";
        a.ID_column0 = Ext.id();
        a.ID_column1 = Ext.id();
        a.ID_column2 = Ext.id();
        $("#wf_new_list_portal a").live("mouseover",
            function() {
                $(this).children("img[class=favicon],img[class=flowicon],img[class=formicon]").show()
            }).live("mouseout",
            function() {
                $(this).children("img[class=favicon],img[class=flowicon],img[class=formicon]").hide()
            });
        this.mainPanel = new Ext.ux.Portal({
            hideBorders: true,
            border: false,
            id: "wf_new_list_portal",
            items: [{
                columnWidth: 0.33,
                id: a.ID_column0,
                style: "padding:10px 0 10px 10px",
                items: []
            },
                {
                    columnWidth: 0.33,
                    id: a.ID_column1,
                    style: "padding:10px 0 10px 10px",
                    items: []
                },
                {
                    columnWidth: 0.33,
                    id: a.ID_column2,
                    style: "padding:10px",
                    items: [],
                    listeners: {
                        afterrender: function() {
                            a.getSortList()
                        }
                    }
                }],
            tbar: new Ext.Toolbar({
                items: [{
                    iconCls: "icon-system-refresh",
                    text: lang("refresh"),
                    handler: function(b, c) {
                        a.reloadAllList();
                        a.getSortList()
                    }.createDelegate(this)
                },
                    {
                        xtype: "button",
                        text: lang("needIlaunch"),
                        iconCls: "icon-utils-s-edit",
                        id: "ID_BTN_WF_NEED_FLOW_CHILD",
                        cls: "btn-red1",
                        handler: function(c, b) {
                            CNOA_wf_use_needList = new CNOA_wf_use_needListClass()
                        }
                    },
                    {
                        xtype: "box",
                        id: this.ID_FAVNOTICE,
                        autoEl: {
                            tag: "div",
                            html: "",
                            style: "margin-left:20px;color:#333333"
                        }
                    },
                    "->", (lang("flowName") + ":"), {
                        xtype: "search",
                        key: "$('#wf_new_list_portal .flowColumnItemBody a span')",
                        searchIcon: false
                    }]
            }),
            listeners: {
                drop: function(b) {
                    a.savePosition(b.panel.id, b.columnIndex, b.position)
                }
            }
        })
    },
    savePosition: function(id, column, position) {
        var data = new Array();
        var items = this.mainPanel.items;
        for (var i = 0; i < 3; i++) {
            data[i] = new Array();
            eval("data[i] = Ext.getCmp(this.ID_column" + i + ").items.keys;")
        }
        var _this = this;
        Ext.Ajax.request({
            url: _this.baseUrl + "&task=savePosition",
            method: "POST",
            params: {
                data: Ext.encode(data)
            },
            success: function(r) {}
        })
    },
    reloadAllList: function() {
        Ext.getCmp(this.ID_column0).removeAll();
        Ext.getCmp(this.ID_column1).removeAll();
        Ext.getCmp(this.ID_column2).removeAll()
    },
    getSortList: function() {
        var a = this;
        Ext.Ajax.request({
            url: a.baseUrl + "&task=getSortList",
            method: "POST",
            params: {},
            success: function(c) {
                var b = Ext.decode(c.responseText);
                if (b.success === true) {
                    a.createSortList(b.data);
                    Ext.getCmp("ID_BTN_WF_NEED_FLOW_CHILD").setText(lang("needIlaunch") + "(" + b.childTotal + ")")
                } else {}
            }
        })
    },
    createSortList: function(dt) {
        var _this = this;
        _this.reloadAllList();
        var html = "<div style='padding: 10px;'><img src='resources/images/wait.gif' /></div>";
        var getTool = function(sortId) {
            return [{
                id: "refresh",
                handler: function(th) {
                    Ext.getCmp("flowColumnItem" + sortId).body.update(html);
                    _this.getItemFlowList(sortId)
                }
            }]
        };
        for (var i = 0; i < dt.length; i++) {
            var item = dt[i];
            var cnumber = item.column;
            if (item.column == -1) {
                cnumber = i % 3
            }
            eval("var column = Ext.getCmp(_this.ID_column" + cnumber + ")");
            column.add({
                title: item.text,
                id: "flowColumnItem" + item.sortId,
                bodyCfg: {
                    cls: "flowColumnItemBody"
                },
                html: html,
                tools: getTool(item.sortId)
            });
            column.doLayout();
            _this.getItemFlowList(item.sortId)
        }
    },
    getItemFlowList: function(a) {
        var b = this;
        Ext.Ajax.request({
            url: b.baseUrl + "&task=getJsonData",
            method: "POST",
            params: {
                sortId: a
            },
            success: function(d) {
                var c = Ext.decode(d.responseText);
                if (c.success === true) {
                    b.createItemFlowList(c.data, a)
                } else {}
            }
        })
    },
    createItemFlowList: function(c, e) {
        var h = this;
        var g = "";
        for (var f = 0; f < c.length; f++) {
            var b = "document-text.png";
            var j = lang("baseOnWebForm");
            if (c[f].tplSort == "1") {
                b = "document-word.png";
                j = lang("baseOnWordDoc")
            } else {
                if (c[f].tplSort == "2") {
                    b = "document-excel.png";
                    j = lang("baseOnExcelDoc1")
                } else {
                    if (c[f].tplSort == "3") {
                        b = "document-html-word.png";
                        j = lang("baseOnFormWordDoc")
                    }
                }
            }
            var d = "";
            var a = "./resources/images/icons/";
            if (c[f].flowType == 0) {
                d += "<img class='flowicon' src='" + a + "icon-flow.png' align='texttop' style='float:right;display:none;margin-right:5px;' ext:qtip='" + lang("SeeFlowChart") + "' onclick='CNOA_wf_use_list.viewFlow(" + c[f].flowId + ', "' + c[f].name + "\");' />";
                if (c[f].tplSort == 0 || c[f].tplSort == 3) {
                    d += "<img class='formicon' src='" + a + "application-document.png' align='texttop' style='float:right;display:none;margin-right:5px;' ext:qtip='" + lang("viewForm") + "' onclick='CNOA_wf_use_list.viewForm(" + c[f].flowId + ");' />"
                }
            }
            d += "<img class='flowicon' src='" + a + "clock.png' align='texttop' style='float:right;display:none;margin-right:5px;' ext:qtip='添加日程提醒' onclick='CNOA_wf_use_list.clock(" + c[f].flowId + ");' />";
            g += ["<a href='javascript:void(0);' onclick='CNOA_wf_use_list.newFlow(" + c[f].flowId + "," + c[f].nameRuleId + ", " + c[f].tplSort + ", " + c[f].flowType + ", " + c[f].childId + ");'>", "<img src='./resources/images/icons/", b, "' align='texttop' ext:qtip='", j, "' /> ", "<span ext:qtip='", c[f].about, "'>", c[f].name, "</span>", (c[f].fav == 1 ? "&nbsp;<img src='" + a + "page_fav2.png' class='fav' align='texttop' />": ""), "<img class='favicon' src='" + a + "page_fav.png' align='texttop' style='float:right;display:none;' ext:qtip='" + lang("starCancelStar") + "' onclick='CNOA_wf_use_list.addFavFlow(", c[f].flowId, ", this)' /> ", d, "</a>"].join("")
        }
        if (Ext.isEmpty(g)) {
            g = "<span style='color:gray'>" + lang("typeCanNotUseWork") + "</span>"
        }
        Ext.getCmp("flowColumnItem" + e).body.update(g)
    },
    newFlow: function(b, e, f, a, d, c) {
        var g = this;
        if (Ext.isEmpty(d)) {
            d = 0
        }
        if (a == 0) {
            mainPanel.closeTab("CNOA_MENU_WF_USE_OPENFLOW");
            mainPanel.loadClass(g.baseUrl + "&task=loadPage&from=newflow&flowId=" + b + "&nameRuleId=" + e + "&flowType=" + a + "&tplSort=" + f + "&childId=" + d + "&puFlowId=" + c, "CNOA_MENU_WF_USE_OPENFLOW", lang("FqNewFixedFlow"), "icon-flow-new")
        } else {
            mainPanel.closeTab("CNOA_USE_FLOW_NEWFREE_FLOWDESIGN");
            mainPanel.loadClass("index.php?app=wf&func=flow&action=use&modul=newfree&task=loadPage&from=flowdesign&flowId=" + b + "&flowType=" + a + "&tplSort=" + f + "&childId=" + d + "&puFlowId=" + c, "CNOA_USE_FLOW_NEWFREE_FLOWDESIGN", lang("designFlow1"), "icon-flow-new")
        }
    },
    viewFlow: function(a) {
        var b = this;
        stopEventBubble();
        CNOA_wf_use_flowpreview = new CNOA_wf_use_flowpreviewClass(a)
    },
    clock: function(a) {
        var b = this;
        stopEventBubble();
        mainPanel.closeTab("CNOA_MENU_SYSTEM_NEED_CALENDAR");
        mainPanel.loadClass("index.php?app=notice&func=notice&action=calendar&flowId=" + a, "CNOA_MENU_SYSTEM_NEED_CALENDAR", "我的日程", "icon-my-schedule")
    },
    viewForm: function(c) {
        var j = this;
        stopEventBubble();
        var e = Ext.getBody().getBox();
        var b = e.width - 100;
        var d = e.height - 100;
        var f = function() {
            Ext.Ajax.request({
                url: j.baseUrl + "/showFormInfo",
                method: "POST",
                params: {
                    flowId: c
                },
                success: function(k) {
                    var h = Ext.decode(k.responseText);
                    if (h.success === true) {
                        a.getEl().update("<center>" + h.data.formHtml + "</center>")
                    } else {
                        CNOA.msg.alert(h.msg,
                            function() {})
                    }
                    g.getEl().unmask()
                }
            })
        };
        var a = new Ext.Panel({
            border: false,
            html: lang("workFormLoad") + "...",
            listeners: {
                afterrender: function(h) {
                    f()
                }
            }
        });
        var g = new Ext.Window({
            title: lang("viewJobForm"),
            width: b,
            height: d,
            layout: "fit",
            bodyStyle: "background-color:#FFFFFF",
            modal: true,
            autoScroll: true,
            maximizable: false,
            resizable: false,
            items: [a],
            buttons: [{
                text: lang("close"),
                iconCls: "icon-dialog-cancel",
                handler: function() {
                    g.close()
                }
            }],
            listeners: {
                show: function(h) {
                    h.getEl().mask(lang("waiting"))
                }
            }
        }).show()
    },
    addFavFlow: function(b, a) {
        var c = this;
        Ext.Ajax.request({
            url: this.baseUrl + "&task=addFavFlow",
            method: "POST",
            params: {
                flowId: b
            },
            success: function(g) {
                var d = Ext.decode(g.responseText);
                CNOA.msg.notice(d.msg, lang("collectionFlow"));
                if (d.success === true) {
                    var j = $(a).parent();
                    var e = j.parent();
                    var f = j.children("img[class=fav]");
                    if (f.length < 1) {
                        $("&nbsp;<img src='./resources/images/icons/page_fav2.png' class='fav' align='texttop' />").insertBefore($(a));
                        var h = e.find("img[class=fav]");
                        var k = h[h.length - 2];
                        if (!k) {
                            e.prepend(j)
                        } else {
                            j.insertAfter($(k).parent())
                        }
                    } else {
                        f.remove()
                    }
                }
            }
        });
        stopEventBubble()
    }
};
var CNOA_wf_use_todoClass, CNOA_wf_use_todo;
CNOA_wf_use_todoClass = CNOA.Class.create();
CNOA_wf_use_todoClass.prototype = {
    init: function() {
        this.baseUrl = "index.php?app=wf&func=flow&action=use&modul=todo";
        this.levels = CNOA.wf.use_todo.levels;
        this.storeBar = {
            flowType: 0,
            flowTitle: "",
            faqiId: 0,
            stime: "",
            etime: ""
        };
        var a = [{
            name: "uFlowId"
        },
            {
                name: "flowId"
            },
            {
                name: "flowNumber"
            },
            {
                name: "flowName"
            },
            {
                name: "flowSetName"
            },
            {
                name: "tplSort"
            },
            {
                name: "flowType"
            },
            {
                name: "uid"
            },
            {
                name: "uname"
            },
            {
                name: "level"
            },
            {
                name: "uStepId"
            },
            {
                name: "sname"
            },
            {
                name: "statusText"
            },
            {
                name: "status"
            },
            {
                name: "eventType"
            },
            {
                name: "postTime"
            },
            {
                name: "childDone"
            },
            {
                name: "childAll"
            },
            {
                name: "changeFlowInfo"
            }];
        this.store = new Ext.data.Store({
            autoLoad: true,
            baseParams: this.storeBar,
            proxy: new Ext.data.HttpProxy({
                url: this.baseUrl + "&task=getJsonData&levels=" + this.levels,
                disableCaching: true
            }),
            reader: new Ext.data.JsonReader({
                totalProperty: "total",
                root: "data",
                fields: a
            })
        });
        this.flowTypeStore = new Ext.data.Store({
            autoLoad: true,
            proxy: new Ext.data.HttpProxy({
                url: this.baseUrl + "&task=getFlowTypeData"
            }),
            reader: new Ext.data.JsonReader({
                root: "data",
                fields: [{
                    name: "sortId"
                },
                    {
                        name: "sname"
                    }]
            })
        });
        this.searchWin;
        this.mainPanel = this.getListPanel()
    },
    getListPanel: function() {
        var e = this;
        var a = function(q, p, o) {
            return q + '<br/><span class="cnoa_color_gray">' + o.get("eventType") + "</span>"
        };
        var j = new Ext.grid.ColumnModel({
            defaults: {
                sortable: false,
                menuDisabled: true
            },
            columns: [new Ext.grid.RowNumberer(), {
                header: "uFlowId",
                dataIndex: "uFlowId",
                hidden: true
            },
                {
                    header: lang("flowNumber") + " / " + lang("customTitle"),
                    dataIndex: "flowNumber",
                    id: "flowNumber",
                    width: 130,
                    renderer: this.formatNumber
                },
                {
                    header: lang("initiator") + " / " + lang("initTime"),
                    dataIndex: "uname",
                    width: 130,
                    renderer: this.formatUser
                },
                {
                    header: lang("sort2") + " / " + lang("belongFlow"),
                    dataIndex: "sname",
                    id: "sname",
                    width: 140,
                    renderer: this.formatSort
                },
                {
                    header: lang("importantGrade"),
                    dataIndex: "level",
                    width: 70
                },
                {
                    header: lang("status"),
                    dataIndex: "statusText",
                    width: 70,
                    renderer: a
                },
                {
                    header: lang("opt"),
                    dataIndex: "status",
                    width: 110,
                    renderer: this.makeOperate
                },
                {
                    header: "",
                    dataIndex: "noIndex",
                    width: 20,
                    resizable: false
                }]
        });
        var c = Ext.id();
        var h = new Ext.form.TextField({
                width: 100
            }),
            d = new Ext.form.DateField({
                editable: false,
                format: "Y-m-d",
                width: 100
            }),
            k = new Ext.form.DateField({
                editable: false,
                format: "Y-m-d",
                width: 100
            }),
            n = new Ext.form.Hidden({
                id: c
            }),
            g = new Ext.form.TextField({
                width: 110,
                readOnly: true,
                listeners: {
                    render: function(o) {
                        o.to = c
                    },
                    focus: function(o) {
                        people_selector("user", o, false, false)
                    }
                }
            }),
            f = new Ext.form.ComboBox({
                editable: false,
                store: this.flowTypeStore,
                mode: "local",
                displayField: "sname",
                valueField: "sortId",
                triggerAction: "all",
                focusSelection: true,
                width: 100
            });
        var m = new Ext.Toolbar({
                items: [{
                    iconCls: "icon-system-refresh",
                    text: lang("refresh"),
                    handler: function(o, p) {
                        e.store.reload()
                    }
                },
                    lang("flowNumber") + "/:" + lang("customTitle"), h, (lang("sort2") + " / " + lang("belongFlow")), f, lang("initTime") + ":", d, lang("to"), k]
            }),
            b = new Ext.Toolbar({
                items: [lang("initiator") + ":", g, {
                    xtype: "button",
                    text: lang("search"),
                    handler: function() {
                        e.storeBar.flowType = f.getValue();
                        e.storeBar.flowTitle = h.getValue();
                        e.storeBar.faqiId = n.getValue();
                        e.storeBar.stime = d.getRawValue();
                        e.storeBar.etime = k.getRawValue();
                        e.store.load({
                            params: {
                                flowType: e.storeBar.flowType,
                                flowTitle: e.storeBar.flowTitle,
                                faqiId: e.storeBar.faqiId,
                                stime: e.storeBar.stime,
                                etime: e.storeBar.etime
                            }
                        })
                    }
                },
                    {
                        xtype: "button",
                        text: lang("advanceQuery"),
                        cls: "btn-blue4",
                        iconCls: "icon-search",
                        style: "margin-left:5px",
                        handler: function() {
                            if (!e.searchWin) {
                                e.searchWin = new CNOA_wf_advSearchWinClass(e)
                            }
                            e.searchWin.show()
                        }
                    },
                    {
                        xtype: "button",
                        text: lang("clear"),
                        pressed: true,
                        handler: function() {
                            f.setValue("");
                            h.setValue("");
                            g.setValue("");
                            n.setValue("");
                            d.setValue("");
                            k.setValue("");
                            e.storeBar.flowType = 0;
                            e.storeBar.flowTitle = "";
                            e.storeBar.faqiId = 0;
                            e.storeBar.stime = "";
                            e.storeBar.etime = "";
                            e.store.load({
                                params: {
                                    flowType: e.storeBar.flowType,
                                    flowTitle: e.storeBar.flowTitle,
                                    faqiId: e.storeBar.faqiId,
                                    stime: e.storeBar.stime,
                                    etime: e.storeBar.etime
                                }
                            })
                        }
                    }]
            });
        return new Ext.grid.PageGridPanel({
            stripeRows: true,
            store: this.store,
            searchStore: this.storeBar,
            cm: j,
            autoExpandColumn: "flowNumber",
            tbar: new Ext.Container({
                items: [m, b]
            })
        })
    },
    formatNumber: function(d, c, a) {
        var b = "";
        if (a.data.childAll > 0) {
            b += "&nbsp;<span style='color:red;' ext:qtip='已办子流程/所有子流程数量'>(" + a.data.childDone + "/" + a.data.childAll + ")</span>"
        }
        return "<span >" + d + b + "</span><br /><span class='cnoa_color_gray'>" + a.data.flowName + "</span>"
    },
    formatSort: function(c, b, a) {
        if (!Ext.isEmpty(a.data.flowSetName)) {
            return "<span >" + c + '</span><br /><span class="cnoa_color_gray">' + a.data.flowSetName + "</span>"
        } else {
            return "<span >" + c + "</span>"
        }
    },
    formatUser: function(c, b, a) {
        return c + "<br>" + a.data.postTime
    },
    makeOperate: function(e, d, b) {
        var c = b.data;
        var a = "";
        if (e == "toHQ") {
            a += '<a class="gridview2" href="javascript:void(0)" onclick="CNOA_wf_use_todo.huiqianFlow(' + c.uFlowId + ", " + c.flowId + ", " + c.uStepId + ", " + c.flowType + ", " + c.tplSort + ')">' + lang("countersigned") + "</a>"
        } else {
            a += '<a class="gridview" href="javascript:void(0)" onclick="CNOA_wf_use_todo.showFlow(' + c.uFlowId + ", " + c.flowId + ", " + c.uStepId + ", " + c.flowType + ", " + c.tplSort + ')">' + lang("view") + "</a>";
            a += '<a  class="gridview3 jianju" href="javascript:void(0)" onclick="CNOA_wf_use_todo.dealFlow(' + c.uFlowId + ", " + c.flowId + ", " + c.uStepId + ", " + c.flowType + ", " + c.tplSort + ", " + c.changeFlowInfo + ')">' + lang("deal") + "</a>"
        }
        return a
    },
    showFlow: function(a, c, e, b, d) {
        mainPanel.closeTab("CNOA_MENU_WF_USE_OPENFLOW");
        mainPanel.loadClass("index.php?app=wf&func=flow&action=use&modul=todo&task=loadPage&type=show&from=showflow&uFlowId=" + a + "&flowId=" + c + "&step=" + e + "&flowType=" + b + "&tplSort=" + d, "CNOA_MENU_WF_USE_OPENFLOW", lang("viewFlow"), "icon-flow")
    },
    dealFlow: function(b, d, f, c, e, a) {
        mainPanel.closeTab("CNOA_MENU_WF_USE_OPENFLOW");
        mainPanel.loadClass("index.php?app=wf&func=flow&action=use&modul=todo&task=loadPage&from=dealflow&uFlowId=" + b + "&flowId=" + d + "&step=" + f + "&flowType=" + c + "&tplSort=" + e + "&changeFlowInfo=" + a, "CNOA_MENU_WF_USE_OPENFLOW", lang("dealWorkFlow"), "icon-flow")
    },
    huiqianFlow: function(a, c, e, b, d) {
        mainPanel.closeTab("CNOA_MENU_WF_USE_OPENFLOW");
        mainPanel.loadClass("index.php?app=wf&func=flow&action=use&modul=todo&task=loadPage&type=huiqian&from=showflow&uFlowId=" + a + "&flowId=" + c + "&step=" + e + "&flowType=" + b + "&tplSort=" + d, "CNOA_MENU_WF_USE_OPENFLOW", lang("workProcessSign"), "icon-flow")
    }
};
var CNOA_wf_use_proxyClass, CNOA_wf_use_proxy;
var CNOA_wf_use_my_proxyClass, CNOA_wf_use_my_proxy;
var CNOA_wf_use_my_proxyRecordClass, CNOA_wf_use_my_proxyRecord;
var CNOA_wf_use_to_proxyRecordClass, CNOA_wf_use_to_proxyRecord;
var CNOA_wf_use_proxyAddEditClass, CNOA_wf_use_proxyAddEdit;
CNOA_wf_use_proxyAddEditClass = CNOA.Class.create();
CNOA_wf_use_proxyAddEditClass.prototype = {
    init: function(e, b) {
        var f = this;
        this.ID_save = Ext.id();
        this.ID_rightPanelCt = Ext.id();
        this.edit_id = b;
        this.type = e == "edit" ? "edit": "add";
        this.title = e == "edit" ? "修改工作委托": "新建工作委托";
        this.baseUrl = "index.php?app=wf&func=flow&action=use&modul=proxy";
        this.leftPanel = new Ext.Panel({
            width: 200,
            border: false,
            region: "west",
            bodyStyle: "border-right-width:3px;padding: 10px",
            layout: "form",
            items: [{
                xtype: "textfield",
                name: "fromName",
                readOnly: true,
                fieldLabel: lang("principal1"),
                style: "margin-top:3px;",
                width: 175,
                value: CNOA_USER_TRUENAME
            },
                {
                    xtype: "userselectorfield",
                    fieldLabel: lang("mandatary"),
                    width: 175,
                    allowBlank: false,
                    name: "touid",
                    multiSelect: false
                },
                {
                    xtype: "datetimefield",
                    name: "stime",
                    editable: false,
                    fieldLabel: lang("startTime"),
                    minValue: new Date(),
                    allowBlank: false,
                    width: 175
                },
                {
                    xtype: "compositefield",
                    items: [{
                        xtype: "datetimefield",
                        fieldLabel: lang("endTime"),
                        name: "etime",
                        editable: false,
                        minValue: new Date(),
                        width: 140
                    },
                        {
                            xtype: "button",
                            text: lang("clear2"),
                            hideLabel: true,
                            handler: function() {
                                f.formPanel.getForm().findField("etime").setValue("")
                            }
                        }]
                },
                {
                    xtype: "compositefield",
                    items: [{
                        xtype: "textarea",
                        fieldLabel: lang("principalReason"),
                        name: "say",
                        width: 175,
                        height: 40
                    }]
                },
                {
                    xtype: "displayfield",
                    hideLabel: true,
                    value: '<span class="cnoa_color_red">' + lang("noteWhenEtimeEmpty") + "</span>"
                }]
        });
        this.rightPanel = new Ext.Panel({
            region: "center",
            border: false,
            hideBorders: true,
            autoScroll: true,
            id: this.ID_rightPanelCt,
            tbar: new Ext.Toolbar({
                items: [new Ext.BoxComponent({
                    autoEl: {
                        tag: "div",
                        style: "margin-left: 15px;margin-top:8px;font-weight:800;font-size:12px;",
                        html: '<label><input type=checkBox id="wf_proxy_selectAll" style="margin-right:3px;">' + lang("selectAll") + "</label></input>"
                    }
                }), f.listLength = new Ext.BoxComponent({
                    autoEl: {
                        tag: "div",
                        style: "margin-left: 15px;margin-top:8px;font-weight:800;font-size:12px;",
                        html: ""
                    }
                })]
            })
        });
        this.formPanel = new Ext.form.FormPanel({
            layout: "border",
            border: false,
            labelAlign: "top",
            items: [this.leftPanel, this.rightPanel]
        });
        var d = Ext.getBody().getBox();
        var a = d.width - 70;
        var c = d.height - 70;
        this.mainPanel = new Ext.Window({
            title: this.title,
            layout: "fit",
            width: a,
            height: c,
            modal: true,
            maximizable: true,
            resizable: false,
            items: [this.formPanel],
            buttons: [{
                text: lang("save"),
                id: f.ID_save,
                iconCls: "icon-order-s-accept",
                handler: function() {
                    f.submit()
                }
            },
                {
                    text: lang("cancel"),
                    iconCls: "icon-dialog-cancel",
                    handler: function() {
                        this.mainPanel.close()
                    }.createDelegate(this)
                }]
        }).show();
        f.loadFormData()
    },
    makeProxyFlowField: function(d) {
        var e = this;
        var b = 0;
        var a = 0;
        var c = '<div id="wf_proxy_set" class="cnoa-formhtml-layout" style="padding:5px;" >';
        Ext.each(d,
            function(h, f) {
                var g = Ext.id();
                c += '<table width="100%" border="0" cellspacing="1" cellpadding="0" style="margin-bottom:5px;" id="wf_proxy_set_ct_' + h.sortId + '">';
                c += '    <td width="40%" valign="middle" class="lable" style="padding:10px;">';
                c += '	 <label for="wf_proxy_set_ck_' + g + '"><input sortId="' + h.sortId + '" id="wf_proxy_set_ck_' + g + '" type="checkbox"> ' + lang("sortName") + ": " + h.sname + "</label></input>";
                c += "	 </td>";
                c += "  </tr>";
                c += "  <tr>";
                c += '    <td class="field" valign="top" style="padding:0;">';
                c += '	  <div style="overflow:auto;padding:5px 5px 0 5px;" id="wf-proxy-flowList">';
                Ext.each(h.items,
                    function(n, k) {
                        var m = n.enable ? " enable": " disable";
                        var j = n.checked ? " selecteds": "";
                        n.enable ? b++:a++;
                        c += '<a class="wf-proxy-list-a flow' + m + j + '" flowId="' + n.flowId + '">' + n.name + "</a>"
                    });
                c += "	  </div>";
                c += "	</td>";
                c += "  </tr>";
                c += "</table>"
            });
        c += "</div>";
        e.rightPanel.body.update(c);
        e.listLength.update(lang("total") + '<span class="cnoa_color_red">' + b + "</span>" + lang("theAlterFlow") + '，<span class="cnoa_color_red">' + a + "</span>" + lang("canNotChooseFlow"));
        $("input[id=wf_proxy_selectAll]").click(function() {
            var f = $(this).attr("checked");
            $("#wf_proxy_set .wf-proxy-list-a.enable").each(function() {
                f ? $(this).addClass("selecteds") : $(this).removeClass("selecteds")
            });
            $("input[id^=wf_proxy_set_ck_]").each(function() {
                $(this).attr("checked", f)
            })
        });
        $("input[id^=wf_proxy_set_ck_]").each(function() {
            $(this).click(function() {
                var f = $(this).attr("sortId");
                var g = $(this).attr("checked");
                $("#wf_proxy_set_ct_" + f + " .wf-proxy-list-a.enable").each(function() {
                    g ? $(this).addClass("selecteds") : $(this).removeClass("selecteds")
                })
            })
        });
        $(".wf-proxy-list-a.enable").each(function() {
            $(this).click(function() {
                $(this).toggleClass("selecteds")
            })
        })
    },
    submit: function() {
        var e = this;
        var d = [];
        $(".wf-proxy-list-a.flow.selecteds").each(function() {
            d.push(parseInt($(this).attr("flowid"), 10))
        });
        var b = e.formPanel.getForm();
        if (b.isValid()) {
            var a = b.findField("stime");
            var c = b.findField("etime");
            if (!Ext.isEmpty(c.getValue())) {
                if (a.getValue() > c.getValue()) {
                    CNOA.miniMsg.alertShowAt(Ext.getCmp(e.ID_save), lang("StimeGreaterThanEtime"));
                    return false
                }
            }
            if (d.length < 1) {
                CNOA.miniMsg.alertShowAt(Ext.getCmp(e.ID_save), lang("notChosenEntrustFlow"));
                return false
            }
            b.submit({
                url: e.baseUrl + "&task=" + e.type + "&id=" + e.edit_id,
                method: "POST",
                params: {
                    flowId: Ext.encode(d)
                },
                success: function(f, g) {
                    CNOA.msg.notice(g.result.msg, lang("flowEntrust"));
                    this.mainPanel.close();
                    CNOA_wf_use_my_proxy.store.reload()
                }.createDelegate(this),
                failure: function(f, g) {
                    CNOA.msg.alert(g.result.msg)
                }.createDelegate(this)
            })
        } else {
            CNOA.miniMsg.alertShowAt(Ext.getCmp(e.ID_save), lang("partOfFormCheck"))
        }
    },
    loadFormData: function() {
        var a = this;
        a.formPanel.getForm().load({
            url: a.baseUrl + "&task=loadFormData",
            params: {
                id: a.edit_id
            },
            method: "POST",
            success: function(b, c) {
                a.makeProxyFlowField(c.result.flow)
            },
            failure: function(b, c) {
                CNOA.msg.alert(c.result.msg,
                    function() {
                        a.mainPanel.close()
                    })
            }.createDelegate(this)
        })
    }
};
CNOA_wf_use_my_proxyClass = CNOA.Class.create();
CNOA_wf_use_my_proxyClass.prototype = {
    init: function() {
        var a = this;
        this.baseUrl = "index.php?app=wf&func=flow&action=use&modul=proxy";
        this.storeBar = {
            storeType: "my_proxy"
        };
        this.fields = [{
            name: "id"
        },
            {
                name: "status"
            },
            {
                name: "type"
            },
            {
                name: "fromName"
            },
            {
                name: "toName"
            },
            {
                name: "count"
            },
            {
                name: "stime"
            },
            {
                name: "etime"
            },
            {
                name: "proxyStatus"
            }];
        this.store = new Ext.data.Store({
            proxy: new Ext.data.HttpProxy({
                url: this.baseUrl + "&task=getJsonData",
                disableCaching: true
            }),
            reader: new Ext.data.JsonReader({
                totalProperty: "total",
                root: "data",
                fields: this.fields
            })
        });
        this.sm = new Ext.grid.CheckboxSelectionModel({
            singleSelect: false
        });
        this.store.load();
        this.colModel = new Ext.grid.ColumnModel([new Ext.grid.RowNumberer(), this.sm, {
            header: "id",
            dataIndex: "id",
            hidden: true
        },
            {
                header: lang("status"),
                dataIndex: "status",
                width: 50,
                sortable: false,
                menuDisabled: true,
                renderer: this.statusCheck.createDelegate(this)
            },
            {
                header: lang("principal1"),
                dataIndex: "fromName",
                id: "fromName",
                width: 100,
                sortable: false,
                menuDisabled: true
            },
            {
                header: lang("mandatary"),
                dataIndex: "toName",
                width: 100,
                sortable: false,
                menuDisabled: true
            },
            {
                header: lang("startTime"),
                dataIndex: "stime",
                width: 120,
                sortable: false,
                menuDisabled: true
            },
            {
                header: lang("endTime"),
                dataIndex: "etime",
                width: 120,
                sortable: false,
                menuDisabled: true
            },
            {
                header: lang("quantity"),
                dataIndex: "count",
                width: 100,
                sortable: false,
                menuDisabled: true
            },
            {
                header: lang("commissionState"),
                dataIndex: "proxyStatus",
                width: 100,
                sortable: false,
                menuDisabled: true
            },
            {
                header: lang("opt"),
                dataIndex: "status",
                width: 230,
                sortable: false,
                menuDisabled: true,
                renderer: this.makOprate.createDelegate(this)
            },
            {
                header: "",
                dataIndex: "noIndex",
                width: 20,
                menuDisabled: true,
                resizable: false
            }]);
        this.pagingBar = new Ext.PagingToolbar({
            displayInfo: true,
            store: this.store,
            pageSize: 15,
            listeners: {
                beforechange: function(b, c) {
                    Ext.apply(c, a.storeBar)
                }
            }
        });
        this.grid = new Ext.grid.GridPanel({
            sm: this.sm,
            stripeRows: true,
            store: this.store,
            loadMask: {
                msg: lang("waiting")
            },
            cm: this.colModel,
            hideBorders: true,
            border: false,
            region: "center",
            layout: "fit",
            scriptRows: true,
            autoExpandColumn: "fromName",
            bbar: this.pagingBar,
            listeners: {
                cellclick: function(c, g, d, f) {
                    var b = c.getStore().getAt(g);
                    if (d == 8) {
                        a.getProxyFlow(b.data.id)
                    }
                }
            }
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
                    CNOA.msg.notice(c.msg, lang("flowEntrust"));
                    b.store.reload()
                } else {
                    CNOA.msg.alert(c.msg)
                }
            }
        })
    },
    statusCheck: function(b, c, a) {
        if (b == 1) {
            return '<img src="./resources/images/icons/accept.png" width="16" height="16" />'
        } else {
            return '<img src="./resources/images/icons/dialog-close.png" width="16" height="16" />'
        }
    },
    setProxyStatus: function(b) {
        var a = this;
        Ext.Ajax.request({
            url: a.baseUrl + "&task=setProxyStatus",
            method: "POST",
            params: {
                id: b
            },
            success: function(d) {
                var c = Ext.decode(d.responseText);
                if (c.success === true) {
                    a.store.reload()
                } else {
                    CNOA.msg.alert(c.msg)
                }
            }
        })
    },
    getProxyFlow: function(a) {
        mainPanel.closeTab("CNOA_MENU_WF_USE_FLOW_PROXYVIEWFLOW");
        mainPanel.loadClass("index.php?app=wf&func=flow&action=use&modul=proxy&task=loadPage&from=proxyView_flow&id=" + a, "CNOA_MENU_WF_USE_FLOW_PROXYVIEWFLOW", lang("viewCommissionPricess"), "icon-flow-new")
    },
    makOprate: function(k, g, d, j, f, b) {
        var e = this;
        var m = d.data;
        var a = Ext.id();
        var c = "";
        if (k == "1") {
            c = "<a href='javascript:void(0)' class='gridview3' onclick='CNOA_wf_use_my_proxy.setProxyStatus(" + m.id + ")'>" + lang("disable") + "</a>"
        } else {
            c = "<a href='javascript:void(0)' class='gridview' onclick='CNOA_wf_use_my_proxy.setProxyStatus(" + m.id + ")'>" + lang("enable") + "</a>"
        }
        c += '<a href=\'javascript:void(0)\' class=\'gridview2 jianju\' onclick=\'var ck=$(this).siblings("label").children("input").attr("checked");CNOA_wf_use_my_proxy.takeBackFlow(' + m.id + ", ck);' id='wf_use_takeFlow_" + m.id + "'>" + lang("callIn") + "</a>";
        c += "<label for=wf_use_runflow_" + m.id + "><input selectId='" + m.id + "' type='checkbox' style='margin-left:6px;' id='wf_use_runflow_" + m.id + "'><span class='cnoa_color_green'>" + lang("recoverRunFlow") + "</span></input></label>";
        return c
    },
    takeBackFlow: function(c, a) {
        var b = this;
        CNOA.msg.cf(lang("determineRecoverDelegate"),
            function(d) {
                if (d == "yes") {
                    Ext.Ajax.request({
                        url: b.baseUrl + "&task=takeBackAllProxyFlow",
                        method: "POST",
                        params: {
                            id: c,
                            ck: a
                        },
                        success: function(f) {
                            var e = Ext.decode(f.responseText);
                            if (e.success === true) {
                                CNOA.msg.notice(e.msg, lang("dealFlow"));
                                b.store.reload()
                            } else {
                                CNOA.msg.alert(e.msg)
                            }
                        }
                    })
                }
            })
    }
};
CNOA_wf_use_my_proxyRecordClass = CNOA.Class.create();
CNOA_wf_use_my_proxyRecordClass.prototype = {
    init: function() {
        var a = this;
        this.baseUrl = "index.php?app=wf&func=flow&action=use&modul=proxy";
        this.storeBar = {
            storeType: "my_proxyRecord"
        };
        this.fields = [{
            name: "id"
        },
            {
                name: "uFlowId"
            },
            {
                name: "flowId"
            },
            {
                name: "step"
            },
            {
                name: "status"
            },
            {
                name: "statusText"
            },
            {
                name: "flowNumber"
            },
            {
                name: "flowName"
            },
            {
                name: "fromName"
            },
            {
                name: "toName"
            },
            {
                name: "postTime"
            }];
        this.store = new Ext.data.Store({
            proxy: new Ext.data.HttpProxy({
                url: this.baseUrl + "&task=getMyProxyRecordList",
                disableCaching: true
            }),
            reader: new Ext.data.JsonReader({
                totalProperty: "total",
                root: "data",
                fields: this.fields
            })
        });
        this.store.load();
        this.sm = new Ext.grid.CheckboxSelectionModel({
            singleSelect: false
        });
        this.colModel = new Ext.grid.ColumnModel([new Ext.grid.RowNumberer(), this.sm, {
            header: "id",
            dataIndex: "id",
            hidden: true
        },
            {
                header: lang("flowNumber") + "/" + lang("name"),
                dataIndex: "flowNumber",
                id: "flowNumber",
                width: 150,
                sortable: true,
                menuDisabled: true,
                renderer: this.formatNumber.createDelegate(this)
            },
            {
                header: lang("status"),
                dataIndex: "statusText",
                width: 120,
                sortable: false,
                menuDisabled: true
            },
            {
                header: lang("principal1"),
                dataIndex: "fromName",
                width: 120,
                sortable: false,
                menuDisabled: true
            },
            {
                header: lang("entrustTime"),
                dataIndex: "postTime",
                width: 120,
                sortable: false,
                menuDisabled: true
            },
            {
                header: lang("opt"),
                dataIndex: "uFlowId",
                width: 60,
                sortable: false,
                menuDisabled: true,
                renderer: this.makeOper.createDelegate(this)
            },
            {
                header: "",
                dataIndex: "noIndex",
                width: 20,
                menuDisabled: true,
                resizable: false
            }]);
        this.pagingBar = new Ext.PagingToolbar({
            displayInfo: true,
            store: this.store,
            pageSize: 15,
            listeners: {
                beforechange: function(b, c) {
                    Ext.apply(c, a.storeBar)
                }
            }
        });
        this.grid = new Ext.grid.GridPanel({
            sm: this.sm,
            stripeRows: true,
            store: this.store,
            loadMask: {
                msg: lang("waiting")
            },
            cm: this.colModel,
            hideBorders: true,
            border: false,
            region: "center",
            layout: "fit",
            scriptRows: true,
            autoExpandColumn: "flowNumber",
            bbar: this.pagingBar
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
    formatNumber: function(e, c, a, f, b, d) {
        if (a.data.flowName == "") {
            return "<span >" + e + "</span><br /><span>&nbsp;</span>"
        } else {
            return "<span >" + e + "</span><br /><span class='cnoa_color_gray'>" + a.data.flowName + "</span>"
        }
    },
    makeOper: function(g, e, b, h, c, f) {
        var d = b.data;
        var a = "";
        a += '<a href="javascript:void(0)" class="gridview" onclick="CNOA_wf_use_to_proxyRecord.showFlow(' + g + ", " + d.flowId + ", " + d.step + ')">' + lang("view") + "</a>";
        return a
    },
    showFlow: function(a, b, c) {
        mainPanel.closeTab("CNOA_MENU_WF_USE_OPENFLOW");
        mainPanel.loadClass("index.php?app=wf&func=flow&action=use&modul=todo&task=loadPage&type=show&from=showflow&uFlowId=" + a + "&flowId=" + b + "&step=" + c, "CNOA_MENU_WF_USE_OPENFLOW", lang("viewFlow"), "icon-flow")
    }
};
CNOA_wf_use_to_proxyRecordClass = CNOA.Class.create();
CNOA_wf_use_to_proxyRecordClass.prototype = {
    init: function() {
        var a = this;
        this.baseUrl = "index.php?app=wf&func=flow&action=use&modul=proxy";
        this.storeBar = {
            storeType: "to_proxyRecord"
        };
        this.fields = [{
            name: "id"
        },
            {
                name: "uFlowId"
            },
            {
                name: "flowId"
            },
            {
                name: "step"
            },
            {
                name: "status"
            },
            {
                name: "statusText"
            },
            {
                name: "flowNumber"
            },
            {
                name: "flowName"
            },
            {
                name: "fromName"
            },
            {
                name: "toName"
            },
            {
                name: "postTime"
            },
            {
                name: "flowType"
            },
            {
                name: "tplSort"
            }];
        this.store = new Ext.data.Store({
            proxy: new Ext.data.HttpProxy({
                url: this.baseUrl + "&task=getTrustedRecordList",
                disableCaching: true
            }),
            reader: new Ext.data.JsonReader({
                totalProperty: "total",
                root: "data",
                fields: this.fields
            })
        });
        this.store.load();
        this.sm = new Ext.grid.CheckboxSelectionModel({
            singleSelect: false
        });
        this.colModel = new Ext.grid.ColumnModel([new Ext.grid.RowNumberer(), this.sm, {
            header: "id",
            dataIndex: "id",
            hidden: true
        },
            {
                header: lang("flowNumber") + "/" + lang("name"),
                dataIndex: "flowNumber",
                id: "flowNumber",
                width: 150,
                sortable: true,
                menuDisabled: true,
                renderer: this.formatNumber.createDelegate(this)
            },
            {
                header: lang("status"),
                dataIndex: "statusText",
                width: 120,
                sortable: false,
                menuDisabled: true
            },
            {
                header: lang("principal1"),
                dataIndex: "fromName",
                width: 120,
                sortable: false,
                menuDisabled: true
            },
            {
                header: lang("entrustTime"),
                dataIndex: "postTime",
                width: 120,
                sortable: false,
                menuDisabled: true
            },
            {
                header: lang("opt"),
                dataIndex: "uFlowId",
                width: 60,
                sortable: false,
                menuDisabled: true,
                renderer: this.makeOper.createDelegate(this)
            },
            {
                header: "",
                dataIndex: "noIndex",
                width: 20,
                menuDisabled: true,
                resizable: false
            }]);
        this.pagingBar = new Ext.PagingToolbar({
            displayInfo: true,
            store: this.store,
            pageSize: 15,
            listeners: {
                beforechange: function(b, c) {
                    Ext.apply(c, a.storeBar)
                }
            }
        });
        this.grid = new Ext.grid.GridPanel({
            sm: this.sm,
            stripeRows: true,
            store: this.store,
            loadMask: {
                msg: lang("waiting")
            },
            cm: this.colModel,
            hideBorders: true,
            border: false,
            region: "center",
            layout: "fit",
            scriptRows: true,
            autoExpandColumn: "flowNumber",
            bbar: this.pagingBar
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
    formatNumber: function(e, c, a, f, b, d) {
        if (a.data.flowName == "") {
            return "<span >" + e + "</span><br /><span>&nbsp;</span>"
        } else {
            return "<span >" + e + "</span><br /><span class='cnoa_color_gray'>" + a.data.flowName + "</span>"
        }
    },
    makeOper: function(g, e, b, h, c, f) {
        var d = b.data;
        var a = "";
        a += '<a href="javascript:void(0)" class="gridview" onclick="CNOA_wf_use_to_proxyRecord.showFlow(' + g + ", " + d.flowId + ", " + d.step + ", " + d.flowType + ", " + d.tplSort + ')">' + lang("view") + "</a>";
        return a
    },
    showFlow: function(a, c, e, b, d) {
        mainPanel.closeTab("CNOA_MENU_WF_USE_OPENFLOW");
        mainPanel.loadClass("index.php?app=wf&func=flow&action=use&modul=todo&task=loadPage&type=show&from=showflow&uFlowId=" + a + "&flowId=" + c + "&step=" + e + "&flowType=" + b + "&tplSort=" + d, "CNOA_MENU_WF_USE_OPENFLOW", lang("viewFlow"), "icon-flow")
    }
};
CNOA_wf_use_proxyClass = CNOA.Class.create();
CNOA_wf_use_proxyClass.prototype = {
    init: function() {
        var d = this;
        this.edit_id = 0;
        this.type = "add";
        this.ID_proxy_add = Ext.id();
        this.ID_proxy_edit = Ext.id();
        this.ID_proxy_delete = Ext.id();
        this.baseUrl = "index.php?app=wf&func=flow&action=use&modul=proxy";
        this.proxyType = "my_proxy";
        CNOA_wf_use_my_proxy = new CNOA_wf_use_my_proxyClass();
        var a = CNOA_wf_use_my_proxy.mainPanel;
        CNOA_wf_use_my_proxyRecord = new CNOA_wf_use_my_proxyRecordClass();
        var b = CNOA_wf_use_my_proxyRecord.mainPanel;
        CNOA_wf_use_to_proxyRecord = new CNOA_wf_use_to_proxyRecordClass();
        var c = CNOA_wf_use_to_proxyRecord.mainPanel;
        this.center = new Ext.Panel({
            border: false,
            hideBorders: true,
            layout: "card",
            activeItem: 0,
            items: [a, b, c]
        });
        this.mainPanel = new Ext.Panel({
            collapsible: false,
            hideBorders: true,
            border: false,
            layout: "fit",
            autoScroll: false,
            items: [this.center],
            tbar: new Ext.Toolbar({
                style: "border-left-width:1px;",
                items: [{
                    text: lang("myEntrustRule"),
                    iconCls: "icon-roduction",
                    enableToggle: true,
                    allowDepress: false,
                    pressed: true,
                    cls: "btn-blue4",
                    toggleGroup: "flow_use_proty_type",
                    handler: function(e, f) {
                        d.proxyType = "my_proxy";
                        CNOA_wf_use_my_proxy.storeBar.storeType = "my_proxy";
                        CNOA_wf_use_my_proxy.store.setBaseParam("storeType", "my_proxy");
                        CNOA_wf_use_my_proxy.store.load();
                        Ext.getCmp(d.ID_proxy_add).show();
                        Ext.getCmp(d.ID_proxy_edit).show();
                        Ext.getCmp(d.ID_proxy_delete).show();
                        d.center.getLayout().setActiveItem(0)
                    }.createDelegate(this)
                },
                    {
                        text: lang("myEntruSteRecord"),
                        iconCls: "icon-roduction",
                        enableToggle: true,
                        allowDepress: false,
                        toggleGroup: "flow_use_proty_type",
                        cls: "btn-blue4",
                        handler: function(e, f) {
                            d.proxyType = "my_proxyRecord";
                            CNOA_wf_use_my_proxyRecord.storeBar.storeType = "my_proxyRecord";
                            CNOA_wf_use_my_proxyRecord.store.setBaseParam("storeType", "my_proxyRecord");
                            CNOA_wf_use_my_proxyRecord.store.load();
                            Ext.getCmp(d.ID_proxy_add).hide();
                            Ext.getCmp(d.ID_proxy_edit).hide();
                            Ext.getCmp(d.ID_proxy_delete).hide();
                            d.center.getLayout().setActiveItem(1)
                        }.createDelegate(this)
                    },
                    {
                        text: lang("beWeiTuoRecord"),
                        iconCls: "icon-roduction",
                        cls: "btn-blue4",
                        enableToggle: true,
                        allowDepress: false,
                        toggleGroup: "flow_use_proty_type",
                        handler: function(e, f) {
                            d.proxyType = "to_proxyRecord";
                            CNOA_wf_use_to_proxyRecord.storeBar.storeType = "to_proxyRecord";
                            CNOA_wf_use_to_proxyRecord.store.setBaseParam("storeType", "to_proxyRecord");
                            CNOA_wf_use_to_proxyRecord.store.load();
                            Ext.getCmp(d.ID_proxy_add).hide();
                            Ext.getCmp(d.ID_proxy_edit).hide();
                            Ext.getCmp(d.ID_proxy_delete).hide();
                            d.center.getLayout().setActiveItem(2)
                        }.createDelegate(this)
                    },
                    {
                        iconCls: "icon-system-refresh",
                        text: lang("refresh"),
                        handler: function(e, f) {
                            if (d.proxyType == "my_proxy") {
                                CNOA_wf_use_my_proxy.store.reload()
                            } else {
                                if (d.proxyType == "my_proxyRecord") {
                                    CNOA_wf_use_my_proxyRecord.store.reload()
                                } else {
                                    if (d.proxyType == "to_proxyRecord") {
                                        CNOA_wf_use_to_proxyRecord.store.reload()
                                    }
                                }
                            }
                        }.createDelegate(this)
                    },
                    {
                        text: lang("new"),
                        iconCls: "icon-utils-s-add",
                        cls: "btn-blue4",
                        id: d.ID_proxy_add,
                        handler: function(e, f) {
                            CNOA_wf_use_proxyAddEdit = new CNOA_wf_use_proxyAddEditClass("add")
                        }.createDelegate(this)
                    },
                    {
                        text: lang("modify"),
                        id: d.ID_proxy_edit,
                        iconCls: "icon-utils-s-edit",
                        handler: function(e, f) {
                            var g = CNOA_wf_use_my_proxy.grid.getSelectionModel().getSelections();
                            if (g.length == 0) {
                                CNOA.miniMsg.alertShowAt(e, lang("youDidNotChooseEditData"))
                            } else {
                                CNOA_wf_use_my_proxy.edit_id = g[0].get("id");
                                CNOA_wf_use_proxyAddEdit = new CNOA_wf_use_proxyAddEditClass("edit", CNOA_wf_use_my_proxy.edit_id)
                            }
                        }.createDelegate(this)
                    },
                    {
                        text: lang("del"),
                        id: d.ID_proxy_delete,
                        iconCls: "icon-utils-s-delete",
                        handler: function(e, f) {
                            var g = CNOA_wf_use_my_proxy.grid.getSelectionModel().getSelections();
                            if (g.length == 0) {
                                CNOA.miniMsg.alertShowAt(e, lang("haveNotChooseDel"))
                            } else {
                                CNOA.miniMsg.cfShowAt(e, lang("confirmToDelete"),
                                    function() {
                                        if (g) {
                                            var j = "";
                                            for (var h = 0; h < g.length; h++) {
                                                j += g[h].get("id") + ","
                                            }
                                            CNOA_wf_use_my_proxy.deleteList(j)
                                        }
                                    })
                            }
                        }
                    }]
            })
        })
    }
};
var CNOA_wf_use_recallClass, CNOA_wf_use_recall;
CNOA_wf_use_recallClass = CNOA.Class.create();
CNOA_wf_use_recallClass.prototype = {
    init: function() {
        var b = this;
        this.baseUrl = "index.php?app=wf&func=flow&action=use&modul=view";
        this.searchParams = {
            flowName: "",
            suid: "",
            stime: "",
            etime: ""
        };
        var a = [{
            name: "flowNumber"
        },
            {
                name: "flowName"
            },
            {
                name: "truename"
            },
            {
                name: "event"
            },
            {
                name: "postTime"
            }];
        this.store = new Ext.data.Store({
            autoLoad: true,
            baseParams: this.searchParams,
            proxy: new Ext.data.HttpProxy({
                url: b.baseUrl + "&task=recallList",
                disableCaching: true
            }),
            reader: new Ext.data.JsonReader({
                totalProperty: "total",
                root: "data",
                fields: a
            })
        });
        this.mainPanel = this.getListPanel()
    },
    getListPanel: function() {
        var f = this;
        var e = Ext.id();
        var g = Ext.id();
        var h = Ext.id();
        var b = Ext.id();
        var d = Ext.id();
        var c = function(k, j) {
            j.attr = 'style="white-space:pre-wrap;"';
            return k
        };
        var a = new Ext.grid.ColumnModel({
            defaults: {
                sortable: false,
                menuDisabled: true
            },
            columns: [new Ext.grid.RowNumberer(), {
                header: lang("flowNumber"),
                dataIndex: "flowNumber",
                width: 150
            },
                {
                    header: lang("flowName"),
                    dataIndex: "flowName",
                    width: 320
                },
                {
                    header: lang("recaller"),
                    dataIndex: "truename",
                    width: 100
                },
                {
                    header: lang("event"),
                    dataIndex: "event",
                    renderer: c,
                    width: 400
                },
                {
                    header: lang("recallTime"),
                    dataIndex: "postTime",
                    width: 150
                }]
        });
        return new Ext.grid.PageGridPanel({
            store: this.store,
            cm: a,
            pageSize: 15,
            stripeRows: true,
            tbar: new Ext.Toolbar({
                items: [lang("flowName") + ":", {
                    xtype: "textfield",
                    id: e
                },
                    {
                        xtype: "hidden",
                        id: g
                    },
                    lang("recaller") + ":", {
                        xtype: "textfield",
                        id: d,
                        listeners: {
                            render: function(j) {
                                j.to = g
                            },
                            focus: function(j) {
                                people_selector("user", j, false, false)
                            }
                        }
                    },
                    lang("time") + ":", {
                        xtype: "datefield",
                        format: "Y-m-d",
                        id: h
                    },
                    lang("to"), {
                        xtype: "datefield",
                        format: "Y-m-d",
                        id: b
                    },
                    {
                        text: lang("search"),
                        listeners: {
                            click: function() {
                                f.searchParams.flowName = Ext.getCmp(e).getValue();
                                f.searchParams.suid = Ext.getCmp(g).getValue();
                                f.searchParams.stime = Ext.getCmp(h).getRawValue();
                                f.searchParams.etime = Ext.getCmp(b).getRawValue();
                                f.store.load({
                                    params: f.searchParams
                                })
                            }
                        }
                    },
                    {
                        text: lang("clear"),
                        handler: function() {
                            Ext.getCmp(e).setValue("");
                            Ext.getCmp(g).setValue("");
                            Ext.getCmp(d).setValue("");
                            Ext.getCmp(h).setRawValue("");
                            Ext.getCmp(b).setRawValue("");
                            f.searchParams.flowName = "";
                            f.searchParams.suid = "";
                            f.searchParams.stime = "";
                            f.searchParams.etime = "";
                            f.store.reload()
                        }
                    }]
            })
        })
    }
};
var CNOA_wf_use_proxyView_flowClass, CNOA_wf_use_proxyView_flow;
CNOA_wf_use_proxyView_flowClassClass = CNOA.Class.create();
CNOA_wf_use_proxyView_flowClassClass.prototype = {
    init: function() {
        var a = this;
        this.id = CNOA.wf.use_proxyView_flow.id;
        this.baseUrl = "index.php?app=wf&func=flow&action=use&modul=proxy&id=" + this.id;
        this.fields = [{
            name: "id"
        },
            {
                name: "flowId"
            },
            {
                name: "flowName"
            },
            {
                name: "fromName"
            },
            {
                name: "toName"
            },
            {
                name: "count"
            },
            {
                name: "stime"
            },
            {
                name: "etime"
            },
            {
                name: "fromuid"
            },
            {
                name: "touid"
            }];
        this.store = new Ext.data.Store({
            proxy: new Ext.data.HttpProxy({
                url: this.baseUrl + "&task=getProxyFlowList",
                disableCaching: true
            }),
            reader: new Ext.data.JsonReader({
                totalProperty: "total",
                root: "data",
                fields: this.fields
            })
        });
        this.sm = new Ext.grid.CheckboxSelectionModel({
            singleSelect: false
        });
        this.store.load();
        this.colModel = new Ext.grid.ColumnModel([new Ext.grid.RowNumberer(), {
            header: "id",
            dataIndex: "id",
            hidden: true
        },
            {
                header: lang("flowName"),
                dataIndex: "flowName",
                id: "flowName",
                width: 120,
                sortable: true,
                menuDisabled: true
            },
            {
                header: lang("principal1"),
                dataIndex: "fromName",
                width: 100,
                sortable: false,
                menuDisabled: true
            },
            {
                header: lang("mandatary"),
                dataIndex: "toName",
                width: 100,
                sortable: false,
                menuDisabled: true
            },
            {
                header: lang("startTime"),
                dataIndex: "stime",
                width: 120,
                sortable: false,
                menuDisabled: true
            },
            {
                header: lang("endTime"),
                dataIndex: "etime",
                width: 120,
                sortable: false,
                menuDisabled: true
            },
            {
                header: lang("quantity"),
                dataIndex: "count",
                width: 100,
                sortable: false,
                menuDisabled: true
            },
            {
                header: lang("opt"),
                dataIndex: "status",
                width: 160,
                sortable: false,
                menuDisabled: true,
                renderer: this.makOprate.createDelegate(this)
            },
            {
                header: "",
                dataIndex: "noIndex",
                width: 20,
                menuDisabled: true,
                resizable: false
            }]);
        this.grid = new Ext.grid.PageGridPanel({
            stripeRows: true,
            store: this.store,
            loadMask: {
                msg: lang("waiting")
            },
            cm: this.colModel,
            hideBorders: true,
            border: false,
            region: "center",
            layout: "fit",
            scriptRows: true,
            autoExpandColumn: "flowName",
            listeners: {
                cellclick: function(c, g, d, f) {
                    var b = c.getStore().getAt(g);
                    if (d == 7) {
                        a.getProxyFlow(b.data.flowId, b.data.fromuid, b.data.touid)
                    }
                }
            }
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
    getProxyFlow: function(c, a, b) {
        mainPanel.closeTab("CNOA_MENU_WF_USE_FLOW_PROXYVIEWUFLOW");
        mainPanel.loadClass("index.php?app=wf&func=flow&action=use&modul=proxy&task=loadPage&from=proxyView_uflow&flowId=" + c + "&fromuid=" + a + "&touid=" + b, "CNOA_MENU_WF_USE_FLOW_PROXYVIEWUFLOW", lang("viewCommissionPricess"), "icon-flow-new")
    },
    makOprate: function(k, g, d, j, f, b) {
        var e = this;
        var a = Ext.id();
        var m = d.data;
        var c = '<a href=\'javascript:void(0)\' onclick=\'var ck=$(this).siblings("label").children("input").attr("checked");CNOA_wf_use_proxyView_flow.takeBackAllUflow(' + m.id + ", ck);' style=margin-left:6px; id='wf_use_takeUflow_" + m.id + "'>" + lang("callIn") + "</a>";
        c += "<label for=wf_use_runflow_" + m.id + "><input selectId='" + m.id + "' type='checkbox' style='margin-left:6px;' id='wf_use_runflow_" + m.id + "'><span class='cnoa_color_green'>" + lang("recoverRunFlow") + "</span></input></label>";
        return c
    },
    takeBackAllUflow: function(c, a) {
        var b = this;
        CNOA.msg.cf(lang("determineRecoverDelegate"),
            function(d) {
                if (d == "yes") {
                    Ext.Ajax.request({
                        url: b.baseUrl + "&task=takeBackAllUflow",
                        method: "POST",
                        params: {
                            id: c,
                            ck: a
                        },
                        success: function(g) {
                            var f = Ext.decode(g.responseText);
                            if (f.success === true) {
                                CNOA.msg.notice(f.msg, lang("dealFlow"));
                                b.store.reload();
                                try {
                                    CNOA_wf_use_my_proxy.store.reload()
                                } catch(h) {}
                            } else {
                                CNOA.msg.alert(f.msg)
                            }
                        }
                    })
                }
            })
    }
};
var CNOA_wf_use_proxyView_uflowClass, CNOA_wf_use_proxyView_uflow;
CNOA_wf_use_proxyView_uflowClass = CNOA.Class.create();
CNOA_wf_use_proxyView_uflowClass.prototype = {
    init: function() {
        var a = this;
        this.flowId = CNOA.wf.use_proxyView_uflow.flowId;
        this.fromuid = CNOA.wf.use_proxyView_uflow.fromuid;
        this.touid = CNOA.wf.use_proxyView_uflow.touid;
        this.baseUrl = "index.php?app=wf&func=flow&action=use&modul=proxy&flowId=" + this.flowId + "&fromuid=" + this.fromuid + "&touid=" + this.touid;
        this.fields = [{
            name: "id"
        },
            {
                name: "flowNumber"
            },
            {
                name: "flowName"
            },
            {
                name: "fromName"
            },
            {
                name: "toName"
            },
            {
                name: "status"
            },
            {
                name: "statusText"
            },
            {
                name: "postTime"
            }];
        this.store = new Ext.data.Store({
            proxy: new Ext.data.HttpProxy({
                url: this.baseUrl + "&task=getProxyUflowList",
                disableCaching: true
            }),
            reader: new Ext.data.JsonReader({
                totalProperty: "total",
                root: "data",
                fields: this.fields
            })
        });
        this.sm = new Ext.grid.CheckboxSelectionModel({
            singleSelect: false
        });
        this.store.load();
        this.colModel = new Ext.grid.ColumnModel([new Ext.grid.RowNumberer(), {
            header: "id",
            dataIndex: "id",
            hidden: true
        },
            {
                header: lang("flowNumber") + "/" + lang("name"),
                dataIndex: "flowNumber",
                id: "flowNumber",
                width: 150,
                sortable: true,
                menuDisabled: true,
                renderer: this.formatNumber.createDelegate(this)
            },
            {
                header: lang("principal1"),
                dataIndex: "fromName",
                width: 120,
                sortable: false,
                menuDisabled: true
            },
            {
                header: lang("mandatary"),
                dataIndex: "toName",
                width: 120,
                sortable: false,
                menuDisabled: true
            },
            {
                header: lang("commissionState"),
                dataIndex: "statusText",
                width: 120,
                sortable: false,
                menuDisabled: true
            },
            {
                header: lang("entrustTime"),
                dataIndex: "postTime",
                width: 120,
                sortable: false,
                menuDisabled: true
            },
            {
                header: lang("opt"),
                dataIndex: "status",
                width: 100,
                sortable: false,
                menuDisabled: true,
                renderer: this.makOprate.createDelegate(this)
            },
            {
                header: "",
                dataIndex: "noIndex",
                width: 20,
                menuDisabled: true,
                resizable: false
            }]);
        this.grid = new Ext.grid.PageGridPanel({
            stripeRows: true,
            store: this.store,
            loadMask: {
                msg: lang("waiting")
            },
            cm: this.colModel,
            hideBorders: true,
            border: false,
            region: "center",
            layout: "fit",
            scriptRows: true,
            autoExpandColumn: "flowNumber"
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
    makOprate: function(j, f, c, g, e, a) {
        var d = this;
        var k = c.data;
        var b = "<a href='javascript:void(0)' onclick='CNOA_wf_use_proxyView_uflow.takeBackUflow(" + k.id + ")'>" + lang("callIn") + "</a>";
        return b
    },
    takeBackUflow: function(b) {
        var a = this;
        CNOA.msg.cf(lang("determineRecoverDelegate"),
            function(c) {
                if (c == "yes") {
                    Ext.Ajax.request({
                        url: a.baseUrl + "&task=takeBackUflow",
                        method: "POST",
                        params: {
                            id: b
                        },
                        success: function(e) {
                            var d = Ext.decode(e.responseText);
                            if (d.success === true) {
                                CNOA.msg.notice(d.msg, lang("dealFlow"));
                                a.store.reload();
                                CNOA_wf_use_proxyView_flow.store.reload()
                            } else {
                                CNOA.msg.alert(d.msg)
                            }
                        }
                    })
                }
            })
    },
    formatNumber: function(e, c, a, f, b, d) {
        if (Ext.isEmpty(a.data.flowName)) {
            return "<span >" + e + "</span><br /><span>&nbsp;</span>"
        } else {
            return "<span >" + e + "</span><br /><span class='cnoa_color_gray'>" + a.data.flowName + "</span>"
        }
    }
};
var CNOA_wf_use_showflowClass, CNOA_wf_use_showflow;
var CNOA_wf_use_showflow_fenfaClass, CNOA_wf_use_showflow_fenfa;
var CNOA_wf_use_dealflow, CNOA_wf_use_dealflowClass;
CNOA_wf_use_showflow_fenfaClass = CNOA.Class.create();
CNOA_wf_use_showflow_fenfaClass.prototype = {
    init: function(a, b, c) {
        var d = this;
        this.flowType = b;
        this.tplSort = c;
        this.baseUrl = "index.php?app=wf&func=flow&action=use&modul=done";
        this.formPanel = new Ext.form.FormPanel({
            autoWidth: true,
            border: false,
            hideBorders: true,
            labelWidth: 70,
            labelAlign: "right",
            waitMsgTarget: true,
            bodyStyle: "padding:10px;",
            items: [{
                xtype: "textarea",
                style: "margin: 0; padding: 0;",
                height: 70,
                width: 310,
                readOnly: true,
                allowBlank: false,
                fieldLabel: lang("distributedtoWho"),
                name: "toName"
            },
                {
                    xtype: "hidden",
                    name: "touid"
                },
                {
                    xtype: "btnForPoepleSelector",
                    style: "margin: 0; padding: 0",
                    autoWidth: true,
                    dataUrl: d.baseUrl + "&task=getAllUserListsInPermitDeptTree",
                    style: "margin-left:75px;",
                    text: lang("selectPeople"),
                    listeners: {
                        selected: function(g, h) {
                            var j = new Array();
                            var f = new Array();
                            if (h.length > 0) {
                                for (var e = 0; e < h.length; e++) {
                                    j.push(h[e].uname);
                                    f.push(h[e].uid)
                                }
                            }
                            d.formPanel.getForm().findField("toName").setValue(j.join(","));
                            d.formPanel.getForm().findField("touid").setValue(f.join(","))
                        },
                        onrender: function(e) {
                            e.setSelectedUids(d.formPanel.getForm().findField("touid").getValue().split(","))
                        }
                    }
                }]
        });
        this.win = new Ext.Window({
            title: lang("distributedWorkflow"),
            width: 475,
            height: 205,
            layout: "fit",
            modal: true,
            maximizable: false,
            resizable: false,
            items: this.formPanel,
            buttons: [{
                text: lang("distribute"),
                cls: "btn-blue3",
                handler: function(e, f) {
                    d.submit(a)
                }
            },
                {
                    text: lang("close"),
                    handler: function() {
                        d.win.close()
                    }
                }]
        }).show();
        d.loadFenfaFormData(a)
    },
    loadFenfaFormData: function(a) {
        var b = this;
        b.formPanel.getForm().load({
            url: b.baseUrl + "&task=loadFenfaFormData",
            method: "POST",
            params: {
                uFlowId: a
            },
            waitTitle: lang("notice"),
            success: function(c, d) {},
            failure: function(c, d) {
                CNOA.msg.alert(d.result.msg,
                    function() {})
            }
        })
    },
    submit: function(a) {
        var c = this;
        var b = c.formPanel.getForm();
        if (b.isValid()) {
            b.submit({
                url: c.baseUrl + "&task=fenFaFlow",
                waitTitle: lang("notice"),
                method: "POST",
                waitMsg: lang("waiting"),
                params: {
                    uFlowId: a,
                    flowType: c.flowType,
                    tplSort: c.tplSort
                },
                success: function(d, f) {
                    CNOA.msg.notice(f.result.msg, lang("dealFlow"));
                    c.win.close();
                    try {
                        mainPanel.closeTab("CNOA_MENU_WF_USE_OPENFLOW")
                    } catch(g) {}
                }.createDelegate(this),
                failure: function(d, e) {
                    CNOA.msg.alert(e.result.msg)
                }.createDelegate(this)
            })
        } else {
            CNOA.miniMsg.alertShowAt(btn, lang("didNotChoose"), 30)
        }
    }
};
CNOA_wf_use_showflowClass = CNOA.Class.create();
CNOA_wf_use_showflowClass.prototype = {
    init: function() {
        var k = this;
        this.delAtt = "";
        this.ID_flowTitle = Ext.id();
        this.attachmentCt = Ext.id();
        this.ID_attachTable = Ext.id();
        this.ID_displayAt = Ext.id();
        this.ID_readTable = Ext.id();
        this.ID_displayRd = Ext.id();
        this.ID_CNOA_wf_use_flowPanel_ct = Ext.id();
        this.ID_CNOA_wf_use_formPanel_ct = Ext.id();
        this.ID_CNOA_wf_use_attachPanel_ct = Ext.id();
        this.ID_CNOA_wf_use_readerPanel_ct = Ext.id();
        this.ID_CNOA_wf_use_stepPanel_ct = Ext.id();
        this.FLOW_STATUS_TODO = 1;
        this.FLOW_STATUS_DONE = 2;
        this.FLOW_STATUS_BACK = 3;
        this.FLOW_STATUS_BACKOUT = 4;
        this.FLOW_STATUS_DELETE = 5;
        this.FLOW_STATUS_STOP = 6;
        this.uFlowId = CNOA.wf.use_showflow.uFlowId;
        this.flowId = CNOA.wf.use_showflow.flowId;
        this.step = CNOA.wf.use_showflow.step;
        this.status = CNOA.wf.use_showflow.status;
        this.flowNumber = CNOA.wf.use_showflow.flowNumber;
        this.type = CNOA.wf.use_showflow.type;
        this.allowCallback = CNOA.wf.use_showflow.allowCallback;
        this.allowCancel = CNOA.wf.use_showflow.allowCancel;
        this.allowPrint = CNOA.wf.use_showflow.allowPrint;
        this.allowViewRelate = CNOA.wf.use_showflow.relevanceUFlowInfo;
        this.isInitiator = CNOA.wf.use_showflow.isInitiator;
        this.childSeeParent = CNOA.wf.use_showflow.childSeeParent;
        this.puStepId = CNOA.wf.use_showflow.puStepId;
        this.owner = CNOA.wf.use_showflow.owner;
        this.tplSort = CNOA.wf.use_showflow.tplSort;
        this.flowType = CNOA.wf.use_showflow.flowType;
        this.baseUrl = "index.php?app=wf&func=flow&action=use&modul=todo&uFlowId=" + this.uFlowId + "&flowId=" + this.flowId + "&stepId=" + this.step + "&" + getSessionStr();
        this.baseURI = location.href.substr(0, location.href.lastIndexOf("/") + 1);
        this.addAttachPermit = CNOA.wf.use_showflow.allowHqAttachAdd;
        this.editAttachPermit = CNOA.wf.use_showflow.allowHqAttachEdit;
        this.deleteAttachPermit = CNOA.wf.use_showflow.allowHqAttachDelete;
        this.downAttachPermit = CNOA.wf.use_showflow.allowHqAttachDown;
        this.viewAttachPermit = CNOA.wf.use_showflow.allowHqAttachView;
        this.htmlCon = "";
        this.sourcefile = "";
        this.filen = "";
        var g = {
                text: lang("print") + " / " + lang("export2"),
                iconCls: "icon-print",
                cls: "btn-green1",
                handler: function() {
                    if (k.flowType == 0 && k.tplSort == 0) {
                        k.printFlow()
                    } else {
                        k.printFreeFlow()
                    }
                }
            },
            a = {
                text: lang("flowStep"),
                iconCls: "icon-application-task",
                cls: "btn-blue3",
                handler: function() {
                    k.showFlowStep()
                }
            },
            m = {
                text: lang("flowEvent"),
                cls: "btn-blue3",
                iconCls: "icon-event",
                handler: function() {
                    k.showFlowEvent()
                }
            },
            n = {
                text: lang("flowChart"),
                cls: "btn-blue3",
                iconCls: "icon-flow",
                handler: function() {
                    CNOA_wf_use_flowpreview = new CNOA_wf_use_flowpreviewClass(k.flowId, k.uFlowId)
                }
            },
            c = {
                text: lang("seeRelateFlow"),
                tooltip: lang("seeRelateFlow"),
                iconCls: "icon-collapse-all",
                cls: "btn-blue3",
                menu: [],
                listeners: {
                    beforerender: function(q) {
                        if (CNOA.wf.use_showflow.relevanceUFlowInfo != 0) {
                            var o = Ext.decode(CNOA.wf.use_showflow.relevanceUFlowInfo);
                            var r = [];
                            var p = "&childSeeParent=childSeeParent";
                            var s = "";
                            if (CNOA.wf.use_showflow.puStepId) {
                                s = "&puStepId=" + CNOA.wf.use_showflow.puStepId
                            }
                            Ext.each(o,
                                function(t, u) {
                                    var w = {
                                        text: t.flowNumber,
                                        handler: function() {
                                            mainPanel.closeTab("CNOA_MENU_WF_USE_JUMPVIEW");
                                            mainPanel.loadClass("index.php?app=wf&func=flow&action=use&modul=todo&task=loadPage&type=show&from=showflow&uFlowId=" + t.uFlowId + "&flowId=" + t.flowId + "&step=" + t.step + "&flowType=" + t.flowType + "&tplSort=" + t.tplSort + p + s, "CNOA_MENU_WF_USE_JUMPVIEW", lang("seeRelateFlow"), "icon-flow")
                                        }
                                    };
                                    r[u] = w
                                });
                            q.menu.addItem(r)
                        }
                    }
                }
            },
            e = {
                text: lang("distribute"),
                iconCls: "icon-flow-dispense",
                cls: "btn-blue3",
                handler: function(o, p) {
                    CNOA_wf_use_showflow_fenfa = new CNOA_wf_use_showflow_fenfaClass(k.uFlowId, k.flowType, k.tplSort)
                }
            },
            d = {
                iconCls: "icon-dialog-cancel",
                text: lang("close"),
                handler: function() {
                    k.closeTab()
                }
            };
        var h = "";
        if (k.addAttachPermit == "1") {
            h = {
                xtype: "panel",
                id: this.ID_BTN_FJ,
                border: false,
                bodyCfg: {
                    id: this.attachmentCt,
                    cls: "x-panel-body x-panel-body-noheader x-panel-body-noborde btn-blue3",
                    style: "background:none"
                },
                items: [{
                    xtype: "fileuploadfield",
                    id: this.attachID,
                    buttonOnly: true,
                    buttonText: lang("addAttach"),
                    buttonCfg: {
                        text: lang("addAttach"),
                        iconCls: "icon-folder-plus3"
                    },
                    listeners: {
                        fileselected: function(p, o) {
                            k.addAttach(p, o)
                        }
                    }
                }]
            }
        }
        var j = [];
        if (this.type == "show" && (this.status == this.FLOW_STATUS_TODO || this.status == this.FLOW_STATUS_DONE)) {
            j = [a, m];
            if (this.allowPrint == 1) {
                j.push(g)
            }
            if (this.flowType == 0) {
                j.push(n)
            }
            if (this.allowViewRelate != 0) {
                j.push(c)
            }
            j.push("-", d)
        } else {
            if (this.type == "done") {
                if (this.status == this.FLOW_STATUS_TODO) {
                    if (this.allowPrint == 1) {
                        j.push(g)
                    }
                    if ($.inArray(g, j) == -1 && this.isInitiator) {
                        j.push(g)
                    }
                    if (this.owner == 1 && this.allowCallback == 1) {
                        var f = {
                                text: lang("recall"),
                                tooltip: lang("recallFlow"),
                                cls: "btn-gray1",
                                iconCls: "icon-arrow-continue-180-top",
                                handler: function(o, p) {
                                    k.callback(k.uFlowId, k.flowId, k.step)
                                }
                            },
                            b = {
                                text: lang("recell"),
                                ooltip: lang("recellFlow"),
                                cls: "btn-red1",
                                iconCls: "icon-attac-delete",
                                handler: function() {
                                    k.cancelflow(k.uFlowId, k.flowId, k.step)
                                }
                            };
                        j.push(f, b)
                    }
                    j.push("-", a, m)
                } else {
                    j = [g, "-", a, m];
                    if (this.status == this.FLOW_STATUS_DONE) {
                        j.push(e)
                    }
                }
                if (this.flowType == 0) {
                    j.push(n)
                }
                if (this.allowViewRelate != 0) {
                    j.push(c)
                }
                j.push("-", d)
            } else {
                if (this.type == "huiqian") {
                    if (this.status == 1) {
                        j = [{
                            text: lang("signOpinion"),
                            tooltip: lang("fillSignStepDeal"),
                            iconCls: "icon-flow-entrust",
                            handler: function() {
                                k.huiqianWin()
                            }
                        },
                            h, a, m]
                    } else {
                        j = [a, m]
                    }
                    if (this.flowType == 0) {
                        j.push(n)
                    }
                    j.push("-", d)
                }
            }
        }
        this.formPanel = new Ext.form.FormPanel({
            fileUpload: false,
            hideBorders: true,
            border: false,
            region: "center",
            autoScroll: true,
            layout: "htmlform",
            defaults: {
                xtype: "displayfield",
                width: 300
            },
            layoutConfig: {
                cache: false,
                template: this.tplSort == 3 ? "app/wf/tpl/default/flow/use/form_show_tplSort3.tpl.html": "app/wf/tpl/default/flow/use/form_show.tpl.html",
                templateData: {
                    str_flowInfo: lang("flowInformation"),
                    str_flowNumber: lang("flowNumber"),
                    str_flowLevel: lang("importantGrade"),
                    str_flowFreeTitle: lang("customFlowTitle"),
                    str_flowReason: lang("appReason"),
                    str_flowFormInfo: lang("formInfo"),
                    str_flowAttach: lang("attachmentInfo"),
                    str_flowAttachName: lang("attName"),
                    str_flowUploadBy: lang("uploadBy"),
                    str_flowUploadTime: lang("uploadTime"),
                    str_flowOpt: lang("opt"),
                    str_flowTextInfo: lang("textInfo"),
                    str_flowShowDetail: lang("showDetail"),
                    str_flowStatus: lang("processState"),
                    str_flowInitiator: lang("initiator"),
                    str_flowInitTime: lang("initTime"),
                    str_flowReadInfo: lang("reviewInformation"),
                    str_flowFFpeople: lang("distributePeople"),
                    str_flowPyPeople: lang("pyMan"),
                    str_flowBelongDept: lang("belongDept"),
                    str_flowPyContent: lang("pyContent"),
                    str_flowPyDate: lang("pyDate"),
                    str_flowShowHide: lang("cllickShowHide"),
                    id_flowPanel: this.ID_CNOA_wf_use_flowPanel_ct,
                    id_formPanel: this.ID_CNOA_wf_use_formPanel_ct,
                    id_displayAt: this.ID_displayAt,
                    id_attachPanel: this.ID_CNOA_wf_use_attachPanel_ct,
                    id_attachTable: this.ID_attachTable,
                    id_flowTitle: this.ID_flowTitle,
                    id_displayRd: this.ID_displayRd,
                    id_readTable: this.ID_readTable,
                    id_readerPanel: this.ID_CNOA_wf_use_readerPanel_ct
                }
            },
            items: [{
                name: "flowNumber"
            },
                {
                    name: "flowName"
                },
                {
                    name: "status"
                },
                {
                    name: "uname"
                },
                {
                    name: "postTime"
                },
                {
                    name: "reason",
                    cls: "wf-reason"
                },
                {
                    name: "level"
                }],
            listeners: {
                afterrender: function() {
                    setTimeout(function() {
                        k.loadFlowPanel()
                    })
                }
            },
            bbar: new Ext.Toolbar({
                items: j
            })
        });
        this.mainPanel = new Ext.Panel({
            collapsible: false,
            hideBorders: true,
            border: false,
            layout: "border",
            items: [this.formPanel],
            listeners: {
                beforedestroy: function() {
                    try {
                        CNOA_wf_signature_electron.showDestroy()
                    } catch(o) {}
                }
            }
        })
    },
    loadFlowStepData: function() {
        var a = this;
        Ext.Ajax.request({
            url: a.baseUrl + "&task=loadFlowStepData",
            method: "POST",
            success: function(c) {
                var b = Ext.decode(c.responseText);
                if (b.success === true) {} else {
                    CNOA.msg.alert(b.msg,
                        function() {})
                }
            }
        })
    },
    displayFlowInfo: function() {
        var c = this;
        var a = $("#" + this.ID_flowTitle);
        var b = $("#" + this.ID_CNOA_wf_use_flowPanel_ct);
        b.slideToggle("fast",
            function() {
                if ($(this).css("display") == "block") {
                    a.children("a").text(lang("hideDetails"));
                    a.children("span").text(lang("flowInformation"));
                    a.parent().css("border-bottom-width", "1px")
                } else {
                    a.children("a").text(lang("showDetail"));
                    a.children("span").text(c.flowTitle);
                    a.parent().css("border-bottom-width", "0")
                }
            })
    },
    loadFlowPanel: function() {
        var b = this;
        var a = function() {
            var c = b.baseUrl + "&task=ms_loadTemplateFile&tplSort=" + b.tplSort;
            if (b.tplSort == 1 || b.tplSort == 3) {
                var d = "doc"
            } else {
                var d = "xls"
            }
            $("#wf_show_formct").html("").css("border", "none");
            openOfficeForView_WF("wf_show_formct", c, d, "自由流程表单")
        };
        if (b.flowType == 0) {
            if (b.tplSort == 0 || b.tplSort == 3) {
                Ext.Ajax.request({
                    url: b.baseUrl + "&task=loadFormHtmlView",
                    method: "POST",
                    params: {
                        type: this.type,
                        childSeeParent: b.childSeeParent,
                        puStepId: b.step
                    },
                    success: function(d) {
                        var c = Ext.decode(d.responseText);
                        if (c.success === true) {
                            Ext.fly(b.ID_CNOA_wf_use_formPanel_ct).update(c.data.formHtml + '<div style="clear:both;"></div>');
                            CNOA_wf_form_checker.formInitForView();
                            setPageSet($("#" + b.ID_CNOA_wf_use_formPanel_ct).get(0), Ext.decode(c.pageSet))
                        } else {
                            CNOA.msg.alert(c.msg,
                                function() {})
                        }
                    }
                });
                if (b.tplSort == 3) {
                    a()
                }
            } else {
                a()
            }
        } else {
            if (b.tplSort != 0) {
                a()
            }
        }
        b.loadUflowInfo();
        setTimeout(function() {
                $("#CNOA_WEBOFFICE").height(769)
            },
            200);
        if (b.flowType == 0 && b.tplSort == 0) {
            $("#wf_form_newfree_tushi").children().show()
        } else {
            $("#wf_form_newfree_tushi").children().hide()
        }
    },
    loadUflowInfo: function() {
        var c = this;
        var a = c.formPanel,
            b = a.getForm();
        b.load({
            url: c.baseUrl + "&task=loadUflowInfo",
            method: "POST",
            params: {
                flowId: this.flowId,
                uFlowId: this.uFlowId,
                step: this.step,
                type: this.type,
                flowType: this.flowType,
                tplSort: this.tplSort
            },
            success: function(d, e) {
                if (c.flowType != 0) {
                    if (c.tplSort == 0) {
                        Ext.fly(c.ID_CNOA_wf_use_formPanel_ct).update(e.result.data.htmlFormContent)
                    }
                }
                c.initView(e);
                setTimeout(function() {
                        var f = $("[mark=flowview]");
                        f.each(function(g) {
                            var h = Ext.decode($(this).attr("data"));
                            $(this).bind("click",
                                function() {
                                    mainPanel.closeTab("CNOA_MENU_WF_USE_JUMPVIEW");
                                    if (h.status == 1) {
                                        mainPanel.loadClass("index.php?app=wf&func=flow&action=use&modul=todo&task=loadPage&type=show&from=showflow&uFlowId=" + h.uFlowId + "&flowId=" + h.flowId + "&step=" + h.stepId + "&flowType=" + h.flowType + "&tplSort=" + h.tplSort, "CNOA_MENU_WF_USE_JUMPVIEW", lang("seeRelateFlow"), "icon-flow")
                                    } else {
                                        if (h.status == 2) {
                                            mainPanel.loadClass("index.php?app=wf&func=flow&action=use&modul=done&task=loadPage&from=showflow&uFlowId=" + h.uFlowId + "&flowId=" + h.flowId + "&step=" + h.stepId + "&flowType=" + h.flowType + "&tplSort=" + h.tplSort + "&owner=" + h.owner, "CNOA_MENU_WF_USE_JUMPVIEW", lang("seeRelateFlow"), "icon-flow")
                                        }
                                    }
                                })
                        })
                    },
                    100)
            },
            failure: function(d, e) {
                CNOA.msg.alert(e.result.msg,
                    function() {})
            }.createDelegate(this)
        })
    },
    initView: function(b) {
        var d = this;
        var c = $("#" + d.ID_flowTitle);
        d.flowTitle = lang("flow") + ": " + b.result.data.flowNumber + "(" + b.result.data.level + ")  ";
        d.flowTitle += "[" + b.result.data.uname + "]";
        d.flowTitle += "[" + b.result.data.postTime + "]";
        c.children("span").text(d.flowTitle);
        c.children("span").css("font-weight", "600");
        if (b.result.attach.length > 0) {
            Ext.fly(d.ID_displayAt).dom.style.display = "block";
            d.createAttachList(b.result.attach)
        }
        if (b.result.readInfo.length > 0) {
            Ext.fly(d.ID_displayRd).dom.style.display = "block";
            d.createReadList(b.result.readInfo)
        }
        this.changeWidth();
        var a = this.mainPanel.getId();
        this.mainPanel.on("resize",
            function() {
                d.changeWidth(a)
            })
    },
    changeWidth: function(b) {
        var c = $("#" + b);
        var d = c.find(".wf_div_cttb").width();
        var a = c.width() - 40;
        if (a < d) {
            c.find(".cnoa-formhtml-layout").width(d + 40)
        } else {
            c.find(".cnoa-formhtml-layout").css("width", "")
        }
    },
    createAttachList: function(e, a) {
        var f = this;
        var d = jQuery("#" + this.ID_attachTable + " tr");
        d.each(function(g) {
            var h = $(this);
            if ((g > 0) && h.attr("upd") != "true") {
                h.remove()
            }
        });
        for (var b = 0; b < e.length; b++) {
            var c = '<tr upd="true" height=24 align="left" bgColor="#FFFFFF"><td bgColor="#FFFFFF">&nbsp;' + e[b].filename + "[" + lang("uploaded") + ']<input type="hidden" name="wf_attach_' + e[b].attachid + '" value="1" /></td><td bgColor="#FFFFFF">&nbsp;' + e[b].uname + '</td><td bgColor="#FFFFFF">&nbsp;' + e[b].date + '</td><td bgColor="#FFFFFF" id="att">&nbsp;' + e[b].optStr.replace("CNOA_wf_use_dealflow", "CNOA_wf_use_showflow") + "</td>";
            "</tr>";
            jQuery(c).appendTo(jQuery("#" + f.ID_attachTable))
        }
    },
    createReadList: function(b) {
        var e = this;
        var d = jQuery("#" + this.ID_readTable + " tr");
        for (var a = 0; a < b.length; a++) {
            var c = '<tr upd="true" height=24 align="left" bgColor="#FFFFFF"><td bgColor="#FFFFFF">&nbsp;' + b[a].fenfaName + '</td><td bgColor="#FFFFFF">&nbsp;' + b[a].uname + '</td><td bgColor="#FFFFFF">&nbsp;' + b[a].deptment + '</td><td bgColor="#FFFFFF">&nbsp;' + b[a].say + '</td><td bgColor="#FFFFFF" id="att">&nbsp;' + b[a].sayDate + "</td>";
            "</tr>";
            jQuery(c).appendTo(jQuery("#" + e.ID_readTable))
        }
    },
    removefile: function(a) {
        var b = this;
        CNOA.msg.cf(lang("sureDelAttchment"),
            function(c) {
                if (c == "yes") {
                    jQuery(a).parent().parent("tr").remove();
                    var d = jQuery("#" + b.ID_attachTable + " tr").length;
                    if (d == 1) {
                        Ext.fly(b.ID_displayAt).dom.style.display = "none"
                    }
                }
            })
    },
    printFlow: function() {
        window.open(this.baseUrl + "&task=exportFlow&flowId=" + this.flowId + "&uFlowId=" + this.uFlowId + "&step=" + this.step, "wfexport", "width=740,height=" + (screen.availHeight - 120) + ",left=" + ((screen.availWidth - 740) / 2) + ",top=60,scrollbars=yes,resizable=yes,status=no")
    },
    printFreeFlow: function() {
        window.open(this.baseUrl + "&task=exportFreeFlow&flowId=" + this.flowId + "&uFlowId=" + this.uFlowId + "&step=" + this.step + "&flowType=" + this.flowType + "&tplSort=" + this.tplSort, "wfexport", "width=740,height=" + (screen.availHeight - 120) + ",left=" + ((screen.availWidth - 740) / 2) + ",top=60,scrollbars=yes,resizable=yes,status=no")
    },
    huiqianWin: function() {
        var d = this;
        var c = function() {
            a.getForm().load({
                url: d.baseUrl + "&task=loadHuiqianMsg",
                method: "POST",
                params: {
                    uFlowId: d.uFlowId,
                    stepId: d.step
                },
                waitTitle: lang("notice"),
                success: function(e, f) {},
                failure: function(e, f) {
                    CNOA.msg.alert(f.result.msg)
                }
            })
        };
        var a = new Ext.form.FormPanel({
            autoWidth: true,
            border: false,
            hideBorders: true,
            labelWidth: 60,
            labelAlign: "top",
            waitMsgTarget: true,
            items: [{
                xtype: "textarea",
                fieldLabel: "签发意见",
                name: "qfmessage",
                readOnly: "true",
                width: 400,
                height: 120
            },
                {
                    xtype: "textarea",
                    fieldLabel: "会签意见",
                    name: "message",
                    allowBlank: false,
                    width: 400,
                    height: 120
                }]
        });
        var b = new Ext.Window({
            title: lang("signOpinion"),
            width: 415,
            height: 380,
            layout: "fit",
            modal: true,
            maximizable: false,
            resizable: false,
            items: [a],
            buttons: [{
                text: lang("submit"),
                handler: function() {
                    if (a.getForm().isValid()) {
                        CNOA_wf_form_checker.formatAll();
                        var g = d.formPanel;
                        var j = g.getForm();
                        try {
                            g.body.dom.setAttribute("enctype", "multipart/form-data");
                            g.body.dom.enctype = "multipart/form-data"
                        } catch(n) {}
                        var m = function(f) {
                            var o = d.baseUrl + "&task=ms_submitMsOfficeData&uFlowId=" + f;
                            try {
                                CNOA.WOWF.saveOffice(o)
                            } catch(p) {}
                            mainPanel.closeTab("CNOA_MENU_WF_USE_OPENFLOW")
                        };
                        var k = a.getForm().getValues();
                        var h = k = k.message;
                        g.getForm().submit({
                            url: d.baseUrl + "&task=huiqianMsg",
                            waitTitle: lang("notice"),
                            method: "POST",
                            params: {
                                message: h,
                                uFlowId: d.uFlowId,
                                stepId: d.step,
                                flowType: d.flowType,
                                tplSort: d.tplSort,
                                delAtt: d.delAtt
                            },
                            waitMsg: lang("waiting"),
                            success: function(f, o) {
                                CNOA.msg.notice(lang("proceSignSubSuccess"), lang("workProcessSign"));
                                b.close();
                                try {
                                    CNOA_notice_notice_todo.reload()
                                } catch(p) {}
                                d.closeTab()
                            }.createDelegate(this),
                            failure: function(e, f) {
                                CNOA.msg.alert(f.result.msg,
                                    function() {}.createDelegate(this))
                            }.createDelegate(this)
                        })
                    } else {
                        CNOA.msg.alert(lang("haveNotFillSign"))
                    }
                }
            },
                {
                    text: lang("close"),
                    iconCls: "icon-dialog-cancel",
                    handler: function() {
                        b.close()
                    }
                }]
        }).show();
        c()
    },
    callback: function(a, b, c) {
        var d = this;
        CNOA.msg.cf(lang("wantToRecall"),
            function(e) {
                if (e == "yes") {
                    Ext.Ajax.request({
                        url: "index.php?app=wf&func=flow&action=use&modul=done&task=callback",
                        method: "POST",
                        params: {
                            uFlowId: a,
                            flowId: b,
                            stepId: c
                        },
                        success: function(g) {
                            var f = Ext.decode(g.responseText);
                            if (f.success === true) {
                                CNOA.msg.alert(lang("recallSuccess"),
                                    function() {
                                        try {
                                            CNOA_wf_use_done.store.reload()
                                        } catch(h) {}
                                        mainPanel.closeTab("CNOA_MENU_WF_USE_OPENFLOW");
                                        try {
                                            CNOA_wf_use_draft.store.reload()
                                        } catch(h) {}
                                    })
                            } else {
                                CNOA.msg.alert(f.msg,
                                    function() {})
                            }
                        }
                    })
                }
            })
    },
    cancelflow: function(a, b, c) {
        var d = this;
        CNOA.msg.cf(lang("sureWantCancelFlow"),
            function(e) {
                if (e == "yes") {
                    Ext.Ajax.request({
                        url: "index.php?app=wf&func=flow&action=use&modul=done&task=cancelflow",
                        method: "POST",
                        params: {
                            uFlowId: a,
                            flowId: b,
                            stepId: c
                        },
                        success: function(g) {
                            var f = Ext.decode(g.responseText);
                            if (f.success === true) {
                                CNOA.msg.notice(f.msg, lang("flowRevoked"));
                                try {
                                    CNOA_wf_use_done.store.reload()
                                } catch(h) {}
                                mainPanel.closeTab("CNOA_MENU_WF_USE_OPENFLOW")
                            } else {
                                CNOA.msg.alert(f.msg,
                                    function() {})
                            }
                        }
                    })
                }
            })
    },
    showFlowStep: function() {
        CNOA_wf_use_showFlowStep = new CNOA_wf_use_showFlowStepClass(this.uFlowId, true, false, this.flowId, this.flowType, this.tplSort)
    },
    showFlowEvent: function() {
        CNOA_wf_use_showFlowEvent = new CNOA_wf_use_showFlowEventClass(this.uFlowId, true, this.flowId, this.flowType, this.tplSort)
    },
    closeTab: function() {
        try {
            if (_this.tplSort != 0) {
                var a = document.getElementById("CNOA_WEBOFFICE");
                a.SetSecurity(4 + 32768)
            }
        } catch(b) {}
        mainPanel.closeTab("CNOA_MENU_WF_USE_OPENFLOW")
    },
    addAttach: function(b, h) {
        var d = this;
        d.sourcefile = h;
        var f = h.lastIndexOf("/");
        if (f == -1) {
            f = h.lastIndexOf("\\")
        }
        var a = h.substr(f + 1);
        d.filen = a;
        a += "<div style='display:none;' id='" + b.id + "-file-ct'></div>";
        var g = false;
        jQuery("#" + this.ID_attachTable + " input[type=file]").each(function() {
            if (jQuery(this).val() == h) {
                g = true
            }
        });
        if (g) {
            CNOA.msg.notice2(lang("file") + ": " + a + lang("alreadyUpload"));
            return
        }
        var c = new Date();
        var j = c.format("Y-m-d H:i:s");
        var k = jQuery("#" + this.ID_attachTable);
        Ext.fly(d.ID_displayAt).dom.style.display = "block";
        var m = jQuery("#" + this.ID_attachTable + " tr").length;
        var e = '<tr height=24 align=left bgColor="#FFFFFF"><td bgColor="#FFFFFF">&nbsp;' + a + '</td><td bgColor="#FFFFFF">&nbsp;' + CNOA_USER_TRUENAME + '</td><td bgColor="#FFFFFF">&nbsp;' + j + '</td><td bgColor="#FFFFFF">&nbsp;<a href="javascript:void(0)" onclick="CNOA_wf_use_showflow.removefile(this);">删除</a></td>';
        "</tr>";
        jQuery(e).insertAfter(jQuery("#" + this.ID_attachTable + " tr:eq(" + (m - 1) + ")"));
        jQuery("#" + b.id + "-file-ct").append(jQuery("#" + b.id + "-file"));
        Ext.fly(this.ID_attachTable).dom.scrollIntoView();
        jQuery("#" + this.attachmentCt).html("");
        new Ext.ux.form.FileUploadField({
            renderTo: this.attachmentCt,
            id: this.attachID,
            buttonOnly: true,
            buttonText: lang("addAttach"),
            buttonCfg: {
                text: lang("addAttach"),
                iconCls: "icon-folder--plus"
            },
            listeners: {
                fileselected: function(o, n) {
                    d.addAttach(o, n)
                }
            }
        })
    }
};
var CNOA_wf_use_dealflowClass, CNOA_wf_use_dealflow;
var CNOA_wf_use_dealflow_huiqianClass, CNOA_wf_use_dealflow_huiqian;
var CNOA_wf_use_dealflow_entrustClass, CNOA_wf_use_dealflow_entrust;
var CNOA_wf_use_dealflow_fenfaClass, CNOA_wf_use_dealflow_fenfa;
var CNOA_wf_use_dealflow_gotoPrevStepClass, CNOA_wf_use_dealflow_gotoPrevStep;
CNOA_wf_use_dealflow_huiqianClass = CNOA.Class.create();
CNOA_wf_use_dealflow_huiqianClass.prototype = {
    init: function(c, g) {
        var e = this;
        this.baseUrl = "index.php?app=wf&func=flow&action=use&modul=todo";
        var a = [{
            name: "id"
        },
            {
                name: "stepId"
            },
            {
                name: "stepName"
            },
            {
                name: "truename"
            },
            {
                name: "writetime"
            },
            {
                name: "message"
            }];
        this.huiqianStore = new Ext.data.Store({
            autoLoad: true,
            proxy: new Ext.data.HttpProxy({
                url: e.baseUrl + "&task=getHuiqianJsonData&uFlowId=" + c + "&stepId=" + g
            }),
            reader: new Ext.data.JsonReader({
                totalProperty: "total",
                root: "data",
                fields: a
            })
        });
        var d = function(k, j, h) {
            if (h.get("stepId") == g) {
                return '<a onclick="CNOA_wf_use_dealflow_huiqian.delHuiqian(' + k + "," + h.get("stepId") + ')">' + lang("del") + "</a>"
            }
            return ""
        };
        var b = new Ext.grid.GridPanel({
            border: false,
            region: "center",
            store: this.huiqianStore,
            trackMouseOver: false,
            disableSelection: true,
            loadMask: true,
            autoScroll: true,
            columns: [{
                header: lang("signStep"),
                dataIndex: "stepName",
                width: 100,
                sortable: true
            },
                {
                    header: lang("signPeople"),
                    dataIndex: "truename",
                    width: 100,
                    sortable: true
                },
                {
                    header: lang("commentSumbitTime"),
                    dataIndex: "writetime",
                    width: 120,
                    sortable: true
                },
                {
                    header: lang("signOpinion") + "(" + lang("clickToView") + ")",
                    dataIndex: "message",
                    width: 210,
                    sortable: true
                },
                {
                    header: lang("opt"),
                    dataIndex: "id",
                    width: 80,
                    renderer: d
                }],
            tbar: [{
                text: lang("refresh"),
                iconCls: "icon-system-refresh",
                handler: function() {
                    e.huiqianStore.reload()
                }
            },
                {
                    text: lang("addPeople"),
                    cls: "btn-blue4",
                    iconCls: "icon-utils-s-add",
                    handler: function() {
                        e.addHuiqian(c, g)
                    }
                }],
            listeners: {
                cellclick: function(h, p, j, o) {
                    var k = h.getStore().getAt(p).data;
                    var n = h.getColumnModel().getDataIndex(j);
                    if (k.message != "" && n == "message") {
                        var m = new Ext.Window({
                            title: lang("signOpinion"),
                            layout: "fit",
                            width: 400,
                            height: 260,
                            modal: true,
                            maximizable: true,
                            items: [{
                                xtype: "panel",
                                border: false,
                                autoScroll: true,
                                bodyStyle: "padding:10px;",
                                html: k.message.replace(/\r\n/ig, "<br />").replace(/\n/ig, "<br />")
                            }],
                            buttons: [{
                                text: lang("close"),
                                iconCls: "icon-dialog-cancel",
                                handler: function(q, r) {
                                    m.close()
                                }
                            }]
                        }).show()
                    }
                }
            }
        });
        var f = new CNOA.wf.dealWindow({
            width: 650,
            height: 400,
            title: lang("countersigned"),
            layout: "border",
            items: [b, {
                xtype: "textarea",
                region: "south",
                name: "checkAbout",
                id: "qfmessage",
                height: 120,
                emptyText: "签发意见:"
            }],
            buttons: [{
                text: lang("submit"),
                handler: function() {
                    var h = {
                        uFlowId: c,
                        stepId: g,
                        qfmessage: Ext.getCmp("qfmessage").getValue(),
                    };
                    Ext.Ajax.request({
                        url: e.baseUrl + "&task=sendHuiQianInfo",
                        method: "POST",
                        params: h,
                        success: function(k) {
                            var j = Ext.decode(k.responseText);
                            if (j.success === true) {
                                CNOA.msg.notice(j.msg, lang("dealFlow"));
                                e.huiqianStore.reload()
                            } else {
                                CNOA.msg.alert(j.msg)
                            }
                        }
                    });
                    f.close()
                }
            }]
        }).show()
    },
    addHuiqian: function(a, c) {
        var b = this;
        new Ext.SelectorPanel({
            target: "user",
            multiselect: true,
            dataUrl: this.baseUrl + "&task=getSelectorUser&type=huiqian&uFlowId=" + a + "&stepId=" + c,
            listeners: {
                select: function(e, f, g, d) {
                    var h = {
                        uFlowId: a,
                        stepId: c,
                        huiqianUids: d.join(","),
                        huiqianNames: g.join(",")
                    };
                    Ext.Ajax.request({
                        url: b.baseUrl + "&task=submitHuiQianInfo",
                        method: "POST",
                        params: h,
                        success: function(k) {
                            var j = Ext.decode(k.responseText);
                            if (j.success === true) {
                                CNOA.msg.notice(j.msg, lang("dealFlow"));
                                b.huiqianStore.reload()
                            } else {
                                CNOA.msg.alert(j.msg)
                            }
                        }
                    })
                }
            }
        })
    },
    delHuiqian: function(c, b) {
        var a = this;
        CNOA.msg.cf(lang("sureWantDelHQ"),
            function(d) {
                if (d == "yes") {
                    Ext.Ajax.request({
                        url: a.baseUrl + "&task=deleteHuiqian",
                        method: "POST",
                        params: {
                            id: c,
                            stepId: b
                        },
                        success: function(f) {
                            var e = Ext.decode(f.responseText);
                            if (e.success === true) {
                                CNOA.msg.notice(e.msg, lang("delProSign"));
                                a.huiqianStore.reload()
                            } else {
                                CNOA.msg.alert(e.msg)
                            }
                        }
                    })
                }
            })
    }
};
CNOA_wf_use_dealflow_fenfaClass = CNOA.Class.create();
CNOA_wf_use_dealflow_fenfaClass.prototype = {
    init: function(c, g) {
        var e = this;
        this.baseUrl = "index.php?app=wf&func=flow&action=use&modul=todo";
        var e = this;
        this.baseUrl = "index.php?app=wf&func=flow&action=use&modul=todo";
        var a = [{
            name: "id"
        },
            {
                name: "fenfaUname"
            },
            {
                name: "viewUname"
            },
            {
                name: "viewtime"
            },
            {
                name: "say"
            },
            {
                name: "isread"
            },
            {
                name: "stepId"
            }];
        this.fenfaStore = new Ext.data.Store({
            autoLoad: true,
            proxy: new Ext.data.HttpProxy({
                url: e.baseUrl + "&task=getFenfaList&uFlowId=" + c
            }),
            reader: new Ext.data.JsonReader({
                totalProperty: "total",
                root: "data",
                fields: a
            })
        });
        var d = function(j, h) {
            if (h.value == g) {
                return '<a onclick="CNOA_wf_use_dealflow_fenfa.delFenfa(' + j + ", " + g + ')">' + lang("del") + "</a>"
            } else {
                return ""
            }
        };
        var b = new Ext.grid.GridPanel({
            border: false,
            store: this.fenfaStore,
            trackMouseOver: false,
            disableSelection: true,
            loadMask: true,
            autoScroll: true,
            columns: [{
                header: lang("distributePeople"),
                dataIndex: "fenfaUname"
            },
                {
                    header: lang("pyMan"),
                    dataIndex: "viewUname"
                },
                {
                    header: lang("pyTime"),
                    dataIndex: "viewtime",
                    width: 120
                },
                {
                    header: lang("pyContent"),
                    dataIndex: "say",
                    width: 180
                },
                {
                    header: lang("status"),
                    dataIndex: "isread",
                    width: 50
                },
                {
                    header: lang("flowStep"),
                    dataIndex: "stepId",
                    hidden: true
                },
                {
                    header: lang("opt"),
                    dataIndex: "id",
                    width: 80,
                    renderer: d
                }],
            tbar: [{
                text: lang("refresh"),
                iconCls: "icon-system-refresh",
                handler: function() {
                    e.fenfaStore.reload()
                }
            },
                {
                    text: lang("addPeople"),
                    iconCls: "icon-utils-s-add",
                    handler: function() {
                        e.addFenfa(c, g)
                    }
                }]
        });
        var f = new CNOA.wf.dealWindow({
            width: 650,
            height: 400,
            title: lang("distribute"),
            resizable: false,
            modal: true,
            layout: "fit",
            items: b,
            buttons: [{
                text: lang("close"),
                handler: function() {
                    f.close()
                }
            }]
        }).show()
    },
    addFenfa: function(a, c) {
        var b = this;
        new Ext.SelectorPanel({
            target: "user",
            multiselect: true,
            dataUrl: this.baseUrl + "&task=getSelectorUser&type=fenfa&uFlowId=" + a + "&stepId=" + c,
            listeners: {
                select: function(e, f, g, d) {
                    var h = {
                        uFlowId: a,
                        stepId: c,
                        toUids: d.join(",")
                    };
                    Ext.Ajax.request({
                        url: b.baseUrl + "&task=addFenfa",
                        method: "POST",
                        params: h,
                        success: function(k) {
                            var j = Ext.decode(k.responseText);
                            if (j.success === true) {
                                CNOA.msg.notice(j.msg, lang("dealFlow"));
                                b.fenfaStore.reload()
                            } else {
                                CNOA.msg.alert(j.msg)
                            }
                        }
                    })
                }
            }
        })
    },
    delFenfa: function(c, b) {
        var a = this;
        CNOA.msg.cf(lang("sureWantDelFF"),
            function(d) {
                if (d == "yes") {
                    Ext.Ajax.request({
                        url: a.baseUrl + "&task=delFenfa",
                        method: "POST",
                        params: {
                            id: c,
                            stepId: b
                        },
                        success: function(f) {
                            var e = Ext.decode(f.responseText);
                            if (e.success === true) {
                                CNOA.msg.notice(e.msg, lang("delFlowDistribution"));
                                a.fenfaStore.reload()
                            } else {
                                CNOA.msg.alert(e.msg)
                            }
                        }
                    })
                }
            })
    }
};
CNOA_wf_use_dealflow_gotoPrevStepClass = CNOA.Class.create();
CNOA_wf_use_dealflow_gotoPrevStepClass.prototype = {
    init: function(a, c, b, d) {
        var e = this;
        this.flowType = b;
        this.tplSort = d;
        this.uFlowId = a;
        this.stepId = c;
        this.baseUrl = "index.php?app=wf&func=flow&action=use&modul=todo";
        Ext.Ajax.request({
            url: e.baseUrl + "&task=loadPrevstepData",
            method: "POST",
            params: {
                uFlowId: a,
                step: c
            },
            success: function(h) {
                var f = Ext.decode(h.responseText);
                if (f.success === true) {
                    var g = Ext.decode(f.msg);
                    e.showWindow(g)
                }
            }
        })
    },
    showWindow: function(a) {
        var d = this,
            b = [],
            c;
        Ext.each(a,
            function(e, f) {
                c = "bf_step_" + e.inputValue;
                b.push({
                    xtype: "radio",
                    id: c,
                    checked: e.checked,
                    boxLabel: e.boxLabel,
                    name: "uStepId",
                    inputValue: e.inputValue,
                    listeners: {
                        check: function(h) {
                            var g = h.getValue(),
                                j = Ext.query("[id^=" + h.id + "_]");
                            Ext.each(j,
                                function(m) {
                                    var k = Ext.getCmp(m.id);
                                    if (k) {
                                        if (!g) {
                                            m.checked = g
                                        }
                                        k.setDisabled(!g)
                                    }
                                })
                        }
                    }
                });
                if (e.bingfaChild != undefined) {
                    Ext.each(e.bingfaChild,
                        function(g) {
                            b.push({
                                xtype: "radiogroup",
                                style: "padding: 5px 0 5px 20px;border-bottom:1px #ddd dotted",
                                disabled: !e.checked,
                                from: c,
                                columns: 1,
                                items: g
                            })
                        })
                }
            });
        this.formPanel = new Ext.form.FormPanel({
            labelAlign: "right",
            autoScroll: true,
            labelWidth: 30,
            border: false,
            bodyStyle: "padding:10px;",
            waitMsgTarget: true,
            items: b
        });
        this.mainPanel = new CNOA.wf.dealWindow({
            width: 300,
            height: 300,
            title: lang("sendBack"),
            resizable: false,
            modal: true,
            layout: "fit",
            items: this.formPanel,
            buttons: [{
                text: lang("submit"),
                iconCls: "icon-order-s-accept",
                handler: function() {
                    d.submitPrevstepData()
                }
            },
                {
                    text: lang("close"),
                    iconCls: "icon-dialog-cancel",
                    handler: function() {
                        d.mainPanel.close()
                    }
                }]
        }).show()
    },
    submitPrevstepData: function() {
        var c = this;
        var a = c.baseUrl + "&task=submitPrevstepData";
        say = Ext.getCmp(CNOA_wf_use_dealflow.ID_checkAbout).getValue();
        var b = c.formPanel.getForm();
        if (c.flowType == 1) {
            a = c.baseUrl + "&task=submitFreeSendBackData"
        }
        b.submit({
            url: a,
            waitTitle: lang("notice"),
            method: "POST",
            waitMsg: lang("waiting"),
            params: {
                uFlowId: c.uFlowId,
                stepId: c.stepId,
                say: say,
                flowType: c.flowType,
                tplSort: c.tplSort
            },
            success: function(d, f) {
                if (f.result.success === true) {
                    CNOA.msg.notice(f.result.msg, lang("flowBackDeal"));
                    c.mainPanel.close();
                    mainPanel.closeTab("CNOA_MENU_WF_USE_OPENFLOW");
                    try {
                        CNOA_notice_notice_todo.reload()
                    } catch(g) {}
                    try {
                        CNOA_wf_use_todo.store.reload()
                    } catch(g) {}
                    try {
                        CNOA_wf_use_deal_flow.closeTab()
                    } catch(g) {}
                } else {
                    CNOA.msg.alert(f.result.msg)
                }
            },
            failure: function(d, e) {
                CNOA.msg.alert(e.result.msg)
            }
        })
    }
};
CNOA_wf_use_dealflow_entrustClass = CNOA.Class.create();
CNOA_wf_use_dealflow_entrustClass.prototype = {
    init: function(a, c, d, b, e) {
        var f = this;
        this.baseUrl = "index.php?app=wf&func=flow&action=use&modul=todo";
        this.flowType = b;
        this.tplSort = e;
        this.ID_btn_save = Ext.id();
        this.formPanel = new Ext.form.FormPanel({
            border: false,
            labelWidth: 60,
            waitMsgTarget: true,
            bodyStyle: "padding: 10px",
            items: [{
                xtype: "userselectorfield",
                fieldLabel: lang("selectPeople"),
                width: 275,
                allowBlank: false,
                name: "touid",
                multiSelect: false
            },
                {
                    xtype: "textarea",
                    height: 126,
                    width: 275,
                    fieldLabel: lang("principalReason"),
                    name: "say"
                }]
        });
        this.mainPanel = new CNOA.wf.dealWindow({
            width: 380,
            height: 240,
            title: lang("delegateEntrusted"),
            resizable: false,
            modal: true,
            layout: "fit",
            items: this.formPanel,
            buttons: [{
                text: lang("ok"),
                iconCls: "icon-order-s-accept",
                handler: function() {
                    f.submitEntrustFormData(a, c, d)
                }.createDelegate(this)
            },
                {
                    text: lang("cancel"),
                    iconCls: "icon-dialog-cancel",
                    handler: function() {
                        f.mainPanel.close()
                    }.createDelegate(this)
                }]
        }).show();
        f.loadEntrustForm(a)
    },
    loadEntrustForm: function(a) {
        var b = this;
        b.formPanel.getForm().load({
            url: b.baseUrl + "&task=loadEntrustForm",
            method: "POST",
            params: {
                uFlowId: a
            },
            waitTitle: lang("notice"),
            success: function(c, d) {},
            failure: function(c, d) {
                CNOA.msg.alert(d.result.msg,
                    function() {})
            }
        })
    },
    submitEntrustFormData: function(a, b, e) {
        var g = this;
        var d = g.formPanel.getForm();
        var c = d.findField("touid").getRawValue();
        if (d.isValid()) {
            d.submit({
                url: g.baseUrl + "&task=submitEntrustFormData",
                waitTitle: lang("notice"),
                method: "POST",
                waitMsg: lang("waiting"),
                params: {
                    uFlowId: a,
                    flowId: b,
                    uStepId: e,
                    flowType: g.flowType,
                    tplSort: g.tplSort
                },
                success: function(h, j) {
                    CNOA.msg.notice(j.msg, lang("hasBeenEntruste") + ": " + c + "");
                    g.mainPanel.close();
                    if (g.tplSort == 0) {
                        mainPanel.closeTab("CNOA_MENU_WF_USE_OPENFLOW")
                    } else {
                        CNOA_wf_use_dealflow.tplFile.saveTemplateFile();
                        var f = document.getElementById("CNOA_WEBOFFICE");
                        f.SetSecurity(4 + 32768)
                    }
                    try {
                        CNOA_wf_use_todo.store.reload()
                    } catch(k) {}
                }.createDelegate(this),
                failure: function(f, h) {
                    CNOA.msg.alert(h.result.msg)
                }.createDelegate(this)
            })
        } else {
            CNOA.miniMsg.alertShowAt(g.ID_btn_save, lang("didNotChooseDelegate"), 30)
        }
    }
};
CNOA_wf_use_dealflowClass = CNOA.Class.create();
CNOA_wf_use_dealflowClass.prototype = {
    init: function() {
        var a = this;
        this.flowInfo = "";
        this.ID_checkAbout = Ext.id();
        this.ID_stepTable = Ext.id();
        this.ID_flowTitle = Ext.id();
        this.attachmentCt = Ext.id();
        this.ID_attachTable = Ext.id();
        this.ID_smsTable = Ext.id();
        this.ID_displayAt = Ext.id();
        this.ID_displaySms = Ext.id();
        this.ID_CNOA_wf_use_flowPanel_ct = Ext.id();
        this.ID_CNOA_wf_use_formPanel_ct = Ext.id();
        this.ID_CNOA_wf_use_attachPanel_ct = Ext.id();
        this.ID_CNOA_wf_use_stepPanel_ct = Ext.id();
        this.ID_CNOA_wf_use_freeFlow_formPanel_ct = Ext.id();
        this.ID_HtmlEditor = Ext.id();
        this.ID_BTN_TY = Ext.id();
        this.ID_BTN_JJ = Ext.id();
        this.ID_BTN_HQ = Ext.id();
        this.ID_BTN_FF = Ext.id();
        this.ID_BTN_TH = Ext.id();
        this.ID_BTN_WT = Ext.id();
        this.ID_BTN_FJ = Ext.id();
        this.ID_BTN_YJ = Ext.id();
        this.ID_BTN_SMS = Ext.id();
        this.uFlowId = CNOA.wf.use_dealflow.uFlowId;
        this.flowId = CNOA.wf.use_dealflow.flowId;
        this.step = CNOA.wf.use_dealflow.step;
        this.stepType = CNOA.wf.use_dealflow.stepType;
        this.isDeals = CNOA.wf.use_dealflow.isDeal;
        this.huiqiannum = CNOA.wf.use_dealflow.huiqiannum;
        this.puStepId = CNOA.wf.use_dealflow.puStepId;
        this.flowType = CNOA.wf.use_dealflow.flowType;
        this.tplSort = CNOA.wf.use_dealflow.tplSort;
        this.childFlow = CNOA.wf.use_dealflow.childFlow;
        this.changeFlowInfo = CNOA.wf.use_dealflow.changeFlowInfo;
        this.allowWordEdit = CNOA.wf.use_dealflow.allowWordEdit;
        this.baseUrl = "index.php?app=wf&func=flow&action=use&modul=todo&uFlowId=" + a.uFlowId + "&flowId=" + this.flowId + "&stepId=" + this.step + "&" + getSessionStr();
        this.editor = null;
        this.myconfig = {
            url: a.baseUrl,
            uFlowId: a.uFlowId,
            flowId: a.flowId,
            tplSort: a.tplSort
        };
        this.tplFile = new tplFile(this.myconfig);
        this.formPanel = new Ext.form.FormPanel({
            fileUpload: false,
            hideBorders: true,
            border: false,
            region: "center",
            autoScroll: true,
            layout: "htmlform",
            defaults: {
                xtype: "displayfield",
                width: 300
            },
            layoutConfig: {
                cache: false,
                template: this.tplSort == 3 ? "app/wf/tpl/default/flow/use/form_deal_tplSort3.tpl.html": "app/wf/tpl/default/flow/use/form_deal.tpl.html",
                templateData: {
                    str_flowInfo: lang("flowInformation"),
                    str_flowInfo1: lang("flowInformation"),
                    str_flowNumber: lang("flowNumber"),
                    str_flowLevel: lang("importantGrade"),
                    str_flowFreeTitle: lang("customFlowTitle"),
                    str_flowReason: lang("appReason"),
                    str_flowFormInfo: lang("formInfo"),
                    str_flowAttach: lang("attachmentInfo"),
                    str_flowAttachName: lang("attName"),
                    str_flowUploadBy: lang("uploadBy"),
                    str_flowUploadTime: lang("uploadTime"),
                    str_flowSMS: lang("sms"),
                    str_flowStep: lang("flowStep"),
                    str_flowMobileNum: lang("mobileNumName"),
                    str_flowContent: lang("content"),
                    str_flowPosttime: lang("posttime"),
                    str_flowSender: lang("sender"),
                    str_flowOpt: lang("opt"),
                    str_flowTextInfo: lang("textInfo"),
                    str_flowShowDetail: lang("showDetail"),
                    str_flowStatus: lang("processState"),
                    str_flowInitiator: lang("initiator"),
                    str_flowInitTime: lang("initTime"),
                    str_flowShowHide: lang("cllickShowHide"),
                    id_flowPanel: this.ID_CNOA_wf_use_flowPanel_ct,
                    id_formPanel: this.ID_CNOA_wf_use_formPanel_ct,
                    id_displayAt: this.ID_displayAt,
                    id_displaySms: this.ID_displaySms,
                    id_stepPanel: this.ID_CNOA_wf_use_stepPanel_ct,
                    id_attachPanel: this.ID_CNOA_wf_use_attachPanel_ct,
                    id_attachTable: this.ID_attachTable,
                    id_flowTitle: this.ID_flowTitle,
                    id_freeFlow_formPanel: this.ID_CNOA_wf_use_freeFlow_formPanel_ct,
                    id_smsPanel: this.ID_CNOA_wf_use_smsPanel_ct,
                    id_smsTable: this.ID_smsTable
                }
            },
            items: [{
                name: "flowNumber"
            },
                {
                    name: "flowName"
                },
                {
                    name: "status"
                },
                {
                    name: "uname"
                },
                {
                    name: "postTime"
                },
                {
                    name: "reason",
                    cls: "wf-reason"
                },
                {
                    name: "level"
                }],
            listeners: {
                afterrender: function() {
                    setTimeout(function() {
                        a.loadFormPanel()
                    })
                }
            }
        });
        this.bottomPanel = new Ext.Panel({
            hideBorders: true,
            border: false,
            region: "south",
            height: 69,
            layout: "fit",
            items: [{
                xtype: "textarea",
                region: "buttom",
                name: "checkAbout",
                id: a.ID_checkAbout,
                height: 40,
                style: "border-bottom-width:0;border-left-width:0;border-right-width:0;background-image: url('resources/images/wf-comment-bg.gif');border-color: #ff0000;color:#ff0000;font-size:16px;font-weight:bold;",
                emptyText: lang("pleaseFillYourReason") + ":"
            }],
            bbar: new Ext.Toolbar({
                items: [{
                    handler: function(b, c) {
                        var d = this;
                        Ext.Ajax.request({
                            url: d.baseUrl + "&task=checkHuiqian",
                            params: {
                                uFlowId: d.uFlowId,
                                step: d.step
                            },
                            method: "POST",
                            success: function(f, e) {
                                result = Ext.decode(f.responseText);
                                if (result.success === true) {
                                    CNOA.msg.cf(result.msg,
                                        function(g) {
                                            if (g == "yes") {
                                                if (d.flowType == 0) {
                                                    d.getSendNextData(g, "agree")
                                                } else {
                                                    if (d.flowType == 1) {
                                                        d.seqFlowSendNextStep("agree")
                                                    } else {
                                                        d.sendfreeFlowNextStepWin("agree")
                                                    }
                                                }
                                            }
                                        })
                                } else {
                                    if (d.flowType == 0) {
                                        d.getSendNextData(b, "agree")
                                    } else {
                                        if (d.flowType == 1) {
                                            d.seqFlowSendNextStep("agree")
                                        } else {
                                            d.sendfreeFlowNextStepWin("agree")
                                        }
                                    }
                                }
                            }
                        })
                    }.createDelegate(this),
                    tooltip: lang("agree"),
                    cls: "btn-green1",
                    id: this.ID_BTN_TY,
                    style: "margin-left: 4px",
                    iconCls: "icon-flow-deal",
                    text: lang("agree")
                },
                    {
                        handler: function(b, c) {
                            if (this.flowType == 0) {
                                a.getSendNextData(b, "keep")
                            } else {
                                if (this.flowType == 1) {
                                    a.seqFlowSendNextStep("keep")
                                } else {
                                    a.sendfreeFlowNextStepWin("keep")
                                }
                            }
                        }.createDelegate(this),
                        tooltip: lang("reservationOpinion"),
                        iconCls: "icon-page-addedit",
                        cls: "btn-red1",
                        id: this.ID_BTN_YJ,
                        text: lang("reservationOpinion")
                    },
                    {
                        handler: function(b, c) {
                            a.finishFlow()
                        }.createDelegate(this),
                        tooltip: lang("end"),
                        iconCls: "icon-fileview-column3",
                        cls: "btn-gray1",
                        hidden: a.flowType == 2 ? false: true,
                        text: lang("end")
                    },
                    {
                        xtype: "splitbutton",
                        text: "暂存",
                        hidden: a.flowType == 0 && a.tplSort == 0 ? false: true,
                        iconCls: "icon-save-draft",
                        cls: "btn-blue4",
                        handler: function() {
                            CNOA.msg.cf("数据更新以后，只能自己查看，是否继续操作？",
                                function(b) {
                                    if (b == "yes") {
                                        a.saveFileDraft(1)
                                    }
                                })
                        },
                        menu: [{
                            xtype: "button",
                            text: "保存并更新",
                            cls: "btn-blue4",
                            width: 81,
                            handler: function() {
                                CNOA.msg.cf("数据更新以后，其他用户也会同时更新，是否继续操作？",
                                    function(b) {
                                        if (b == "yes") {
                                            a.saveFileDraft(2)
                                        }
                                    })
                            }
                        },
                            {
                                xtype: "button",
                                text: "暂存",
                                cls: "btn-blue4",
                                width: 81,
                                handler: function() {
                                    CNOA.msg.cf("数据更新以后，只能自己查看，是否继续操作？",
                                        function(b) {
                                            if (b == "yes") {
                                                a.saveFileDraft(1)
                                            }
                                        })
                                }
                            }]
                    },
                    {
                        xtype: "panel",
                        id: this.ID_BTN_FJ,
                        border: false,
                        bodyCfg: {
                            id: this.attachmentCt,
                            cls: "x-panel-body x-panel-body-noheader x-panel-body-noborde btn-blue3",
                            style: "background:none"
                        },
                        items: [{
                            xtype: "fileuploadfield",
                            buttonOnly: true,
                            buttonText: lang("addAttach"),
                            buttonCfg: {
                                text: lang("addAttach"),
                                iconCls: "icon-folder-plus3"
                            },
                            listeners: {
                                fileselected: function(c, b) {
                                    a.addAttach(c, b)
                                }
                            }
                        }]
                    },
                    {
                        text: lang("addSMS"),
                        id: this.ID_BTN_SMS,
                        hidden: true,
                        cls: "btn-blue3",
                        iconCls: "phone-sound",
                        handler: function() {
                            newSmsWindow(function(d, e, b, c) {
                                a.insertSms(d, e, b, c)
                            })
                        }
                    },
                    {
                        tooltip: lang("launchZLC"),
                        text: lang("subprocesses") + "(" + CNOA.wf.use_dealflow.childNum + ")",
                        iconCls: "icon-collapse-all",
                        cls: "btn-blue3",
                        hidden: a.childFlow == 0 ? true: false,
                        handler: function() {
                            a.childFlowWin()
                        }
                    },
                    {
                        tooltip: lang("seeRelateFlow"),
                        text: lang("seeRelateFlow"),
                        iconCls: "icon-collapse-all",
                        cls: "btn-blue3",
                        hidden: CNOA.wf.use_dealflow.relevanceUFlowInfo == 0 ? true: false,
                        menu: [],
                        listeners: {
                            beforerender: function(d) {
                                if (CNOA.wf.use_dealflow.relevanceUFlowInfo != 0) {
                                    var b = Ext.decode(CNOA.wf.use_dealflow.relevanceUFlowInfo);
                                    var e = [];
                                    var c = "",
                                        f = "";
                                    if (!a.childFlow) {
                                        c = "&childSeeParent=childSeeParent"
                                    }
                                    if (a.puStepId) {
                                        f = "&puStepId=" + a.puStepId
                                    }
                                    Ext.each(b,
                                        function(g, h) {
                                            var j = {
                                                text: g.flowNumber,
                                                handler: function() {
                                                    mainPanel.closeTab("CNOA_MENU_WF_USE_JUMPVIEW");
                                                    mainPanel.loadClass("index.php?app=wf&func=flow&action=use&modul=todo&task=loadPage&type=show&from=showflow&uFlowId=" + g.uFlowId + "&flowId=" + g.flowId + "&step=" + g.step + "&flowType=" + g.flowType + "&tplSort=" + g.tplSort + c + f, "CNOA_MENU_WF_USE_JUMPVIEW", lang("seeRelateFlow"), "icon-flow")
                                                }
                                            };
                                            e[h] = j
                                        });
                                    d.menu.addItem(e)
                                }
                            }
                        }
                    },
                    {
                        handler: function(b, d) {
                            var c = Ext.getCmp(a.ID_checkAbout).getValue();
                            if (c == "") {
                                CNOA.msg.alert(lang("fillOutReason"),
                                    function() {
                                        Ext.getCmp(a.ID_checkAbout).focus()
                                    })
                            } else {
                                CNOA.msg.cf(lang("wantRefuseStep"),
                                    function(e) {
                                        if (e == "yes") {
                                            a.rejectReason()
                                        }
                                    })
                            }
                        }.createDelegate(this),
                        tooltip: lang("reject"),
                        id: this.ID_BTN_JJ,
                        cls: "btn-blue3",
                        iconCls: "icon-refuse",
                        text: lang("reject")
                    },
                    {
                        text: lang("countersigned") + '( <b class="cnoa_color_white">' + this.huiqiannum + "</b> )",
                        tooltip: lang("countersigned"),
                        cls: "btn-blue3",
                        id: this.ID_BTN_HQ,
                        iconCls: "icon-flow-entrust",
                        handler: function(b, c) {
                            a.huiqianWin()
                        }
                    },
                    {
                        text: lang("distribute"),
                        tooltip: lang("distribute"),
                        cls: "btn-blue3",
                        iconCls: "icon-flow-dispense",
                        id: this.ID_BTN_FF,
                        handler: function() {
                            a.fenfaWin()
                        }
                    },
                    {
                        handler: function(b, d) {
                            var c = Ext.getCmp(a.ID_checkAbout).getValue();
                            if (c == "") {
                                CNOA.msg.alert(lang("fillOutReasonReturn"),
                                    function() {
                                        Ext.getCmp(a.ID_checkAbout).focus()
                                    })
                            } else {
                                a.goto_prevstepWin()
                            }
                        }.createDelegate(this),
                        tooltip: lang("sendBack"),
                        cls: "btn-blue3",
                        id: this.ID_BTN_TH,
                        iconCls: "icon-flow-goto-prevstep",
                        text: lang("sendBack")
                    },
                    {
                        handler: function(b, c) {
                            a.entrustWin()
                        }.createDelegate(this),
                        cls: "btn-blue3",
                        tooltip: lang("entrust"),
                        id: this.ID_BTN_WT,
                        iconCls: "icon-flow-entrust",
                        text: lang("entrust")
                    },
                    {
                        handler: function(b, c) {
                            a.join_calender()
                        }.createDelegate(this),
                        tooltip: lang("joinSchedule"),
                        iconCls: "icon-schedule",
                        cls: "btn-blue3",
                        text: lang("joinSchedule")
                    },
                    {
                        handler: function(b, c) {
                            a.showFlowStep()
                        }.createDelegate(this),
                        tooltip: lang("flowStep"),
                        iconCls: "icon-application-task",
                        cls: "btn-blue3",
                        text: lang("flowStep")
                    },
                    {
                        handler: function(b, c) {
                            a.showFlowEvent()
                        }.createDelegate(this),
                        text: lang("flowEvent"),
                        cls: "btn-blue3",
                        iconCls: "icon-event",
                        tooltip: lang("flowEvent")
                    },
                    {
                        handler: function(b, c) {
                            CNOA_wf_use_flowpreview = new CNOA_wf_use_flowpreviewClass(a.flowId, a.uFlowId)
                        }.createDelegate(this),
                        text: lang("flowChart"),
                        hidden: this.flowType != 0 ? true: false,
                        iconCls: "icon-flow",
                        cls: "btn-blue3",
                        tooltip: lang("flowChart")
                    },
                    {
                        handler: function(b, c) {
                            a.closeTab()
                        }.createDelegate(this),
                        tooltip: lang("cancel"),
                        text: lang("cancel")
                    }]
            })
        });
        this.mainPanel = new Ext.Panel({
            collapsible: false,
            hideBorders: true,
            border: false,
            region: "center",
            layout: "border",
            items: [this.formPanel, this.bottomPanel],
            listeners: {
                beforedestroy: function() {
                    try {
                        CNOA_wf_signature_electron.showDestroy()
                    } catch(b) {}
                }
            }
        })
    },
    childFlowWin: function() {
        var c = this;
        var a = [{
            name: "name"
        },
            {
                name: "id"
            },
            {
                name: "faqiname"
            },
            {
                name: "flowId"
            },
            {
                name: "status"
            },
            {
                name: "faqiFlow"
            },
            {
                name: "operate"
            },
            {
                name: "operatename"
            },
            {
                name: "operateuid"
            },
            {
                name: "uFlowId"
            },
            {
                name: "stepname"
            },
            {
                name: "stepuid"
            },
            {
                name: "original"
            }];
        this.childStore = new Ext.data.Store({
            autoLoad: true,
            proxy: new Ext.data.HttpProxy({
                url: c.baseUrl + "&task=getChildFlowJsonData"
            }),
            reader: new Ext.data.JsonReader({
                totalProperty: "total",
                root: "data",
                fields: a
            })
        });
        var b = new Ext.grid.GridPanel({
            layout: "fit",
            border: false,
            store: this.childStore,
            disableSelection: true,
            loadMask: true,
            autoScroll: true,
            columns: [{
                header: lang("flowName"),
                dataIndex: "name",
                width: 300,
                sortable: false
            },
                {
                    header: lang("status"),
                    dataIndex: "status",
                    width: 80,
                    sortable: false,
                    renderer: function(f, g, e) {
                        var d = "";
                        if (f == 3) {
                            d = '<span class="cnoa_color_red">' + lang("alreadyOver") + "</span>"
                        } else {
                            if (f == 2) {
                                d = '<span class="cnoa_color_blue">' + lang("haveLaunched") + "</span>"
                            } else {
                                if (f == 1) {
                                    d = '<span class="cnoa_color_green">' + lang("isSet") + "</span>"
                                } else {
                                    d = lang("notSet")
                                }
                            }
                        }
                        return d
                    }
                },
                {
                    header: lang("initiator"),
                    dataIndex: "faqiname",
                    width: 80,
                    sortable: false,
                    renderer: function(e, g, d) {
                        var f = d.data;
                        if (f.faqiFlow == "myself") {
                            return lang("currentDealPeople")
                        }
                        if (f.operate == 2) {
                            return f.operatename
                        } else {
                            return e
                        }
                    }
                },
                {
                    header: lang("step"),
                    dataIndex: "stepname",
                    width: 80,
                    sortable: false
                },
                {
                    header: lang("acceptOfficer"),
                    dataIndex: "stepuid",
                    width: 80,
                    sortable: false
                },
                {
                    header: lang("opt"),
                    dataIndex: "id",
                    width: 140,
                    sortable: false,
                    renderer: function(f, h, e) {
                        var g = e.data;
                        var d = "";
                        if (g.status == "0") {
                            if (g.operate == "2") {
                                return '<a href="javascript:void(0);" onclick="CNOA_wf_use_dealflow.childFaqiUserSelect(' + g.operateuid + ", " + f + ", '" + g.operatename + "')\">" + lang("new2") + "</a>"
                            } else {
                                return '<a href="javascript:void(0);" onclick="CNOA_wf_use_dealflow.childFaqi(' + f + "," + g.flowId + ", '" + g.faqiFlow + "')\">" + lang("new2") + "</a>"
                            }
                        } else {
                            if (g.status == "1") {
                                d += '<a href="javascript:void(0);" onclick="CNOA_wf_use_dealflow.cancelChildFaqi(' + f + "," + g.flowId + ", '" + g.faqiFlow + "', " + g.status + ')"><span class="cnoa_color_red">' + lang("recell") + "</span></a>"
                            } else {
                                d += '<a href="javascript:void(0);" onclick="CNOA_wf_use_dealflow.viewChildFlow(' + g.uFlowId + ')">' + lang("view") + "</a>"
                            }
                        }
                        if (g.original == 0) {
                            d += ' / <a href="javascript:void(0);" onclick="CNOA_wf_use_dealflow.addChildFlow(' + f + ')">' + lang("addSubprocess") + "</a>"
                        } else {
                            d += ' / <a href="javascript:void(0);" onclick="CNOA_wf_use_dealflow.deleteChildFlow(' + f + ')">' + lang("del") + "</a>"
                        }
                        return d
                    }
                }]
        });
        this.childFlowWindow = new Ext.Window({
            width: 750,
            height: 400,
            title: lang("showSubprocessList"),
            resizable: false,
            modal: true,
            layout: "fit",
            items: b,
            buttons: [{
                text: lang("close"),
                handler: function() {
                    c.childFlowWindow.close()
                }
            }]
        }).show()
    },
    addChildFlow: function(b) {
        var a = this;
        CNOA.msg.cf(lang("sureWantAdd"),
            function(c) {
                if (c == "yes") {
                    Ext.Ajax.request({
                        url: a.baseUrl + "&task=addChildFlow",
                        method: "POST",
                        params: {
                            id: b
                        },
                        success: function(e) {
                            var d = Ext.decode(e.responseText);
                            if (d.success === true) {
                                a.childStore.reload()
                            } else {
                                CNOA.msg.alert(d.msg,
                                    function() {})
                            }
                        }
                    })
                }
            })
    },
    deleteChildFlow: function(b) {
        var a = this;
        CNOA.msg.cf(lang("areYouDelete"),
            function(c) {
                if (c == "yes") {
                    Ext.Ajax.request({
                        url: a.baseUrl + "&task=deleteChildFlow",
                        method: "POST",
                        params: {
                            id: b
                        },
                        success: function(e) {
                            var d = Ext.decode(e.responseText);
                            if (d.success === true) {
                                a.childStore.reload()
                            } else {
                                CNOA.msg.alert(d.msg,
                                    function() {})
                            }
                        }
                    })
                }
            })
    },
    viewFlow: function(a) {
        this.childFlowWindow.close();
        mainPanel.closeTab("CNOA_MENU_WF_USE_OPENFLOW");
        mainPanel.loadClass(this.baseUrl + "&task=loadPage&from=viewflow&uFlowId=" + a, "CNOA_MENU_WF_USE_OPENFLOW", lang("viewFlow"), "icon-flow")
    },
    viewChildFlow: function(a) {
        this.childFlowWindow.close();
        mainPanel.closeTab("CNOA_MENU_WF_USE_JUMPVIEW");
        mainPanel.loadClass(this.baseUrl + "&task=loadPage&from=viewflow&uFlowId=" + a, "CNOA_MENU_WF_USE_JUMPVIEW", lang("viewFlow"), "icon-flow")
    },
    cancelChildFaqi: function(d, a) {
        var c = this;
        var b = "yes";
        if (a == 2) {
            CNOA.msg.cf(lang("undoProcessMove"),
                function(e) {
                    b = e
                })
        }
        if (b == "no") {
            return
        }
        Ext.Ajax.request({
            url: c.baseUrl + "&task=cancelChildFaqi",
            method: "POST",
            params: {
                id: d
            },
            success: function(f) {
                var e = Ext.decode(f.responseText);
                if (e.success === true) {
                    c.childStore.reload()
                } else {
                    CNOA.msg.alert(e.msg,
                        function() {})
                }
            }
        })
    },
    childFaqi: function(d, a, b) {
        var c = this;
        if (b == "banlichoice") {
            c.childFaqiUser(d, a)
        } else {
            Ext.Ajax.request({
                url: c.baseUrl + "&task=childFaqi",
                method: "POST",
                params: {
                    id: d
                },
                success: function(f) {
                    var e = Ext.decode(f.responseText);
                    if (e.success === true) {
                        c.childStore.reload()
                    } else {
                        CNOA.msg.alert(e.msg,
                            function() {})
                    }
                }
            })
        }
    },
    childFaqiUser: function(e, c) {
        var d = this;
        var a = new Ext.data.Store({
            proxy: new Ext.data.HttpProxy({
                url: d.baseUrl + "&task=getChildFlowUserJsonData"
            }),
            reader: new Ext.data.JsonReader({
                totalProperty: "total",
                root: "data",
                fields: [{
                    name: "truename"
                },
                    {
                        name: "uid"
                    }]
            })
        });
        a.load({
            params: {
                cflowId: c
            }
        });
        var b = new Ext.grid.GridPanel({
            layout: "fit",
            border: false,
            store: a,
            disableSelection: true,
            loadMask: true,
            autoScroll: true,
            columns: [{
                header: lang("initiator"),
                dataIndex: "truename",
                width: 200,
                sortable: false
            },
                {
                    header: lang("opt"),
                    dataIndex: "uid",
                    width: 100,
                    sortable: false,
                    renderer: function(g, j, f) {
                        var h = f.data;
                        return '<a href="javascript:void(0);" onclick="CNOA_wf_use_dealflow.childFaqiUserSelect(' + g + "," + e + ",'" + h.truename + "')\">" + lang("ok") + "</a>"
                    }
                }]
        });
        this.childFaqiUserWin = new Ext.Window({
            width: 400,
            height: 400,
            title: lang("showSubprocessUser"),
            resizable: false,
            modal: true,
            layout: "fit",
            items: b,
            buttons: [{
                text: lang("close"),
                handler: function() {
                    d.childFaqiUserWin.close()
                }
            }]
        }).show()
    },
    childFaqiUserSelect: function(b, d, a) {
        var c = this;
        Ext.Ajax.request({
            url: c.baseUrl + "&task=childFaqi",
            method: "POST",
            params: {
                uid: b,
                id: d
            },
            success: function(g) {
                var f = Ext.decode(g.responseText);
                if (f.success === true) {
                    try {
                        c.childFaqiUserWin.close()
                    } catch(h) {}
                    c.childStore.reload()
                } else {
                    CNOA.msg.alert(f.msg,
                        function() {})
                }
            }
        })
    },
    displayFlowInfo: function() {
        var c = this;
        var a = $("#" + this.ID_flowTitle);
        var b = $("#" + this.ID_CNOA_wf_use_flowPanel_ct);
        b.slideToggle("fast",
            function() {
                if ($(this).css("display") == "block") {
                    a.children("a").text(lang("hideDetails"));
                    a.children("span").text(lang("flowInformation"));
                    a.parent().css("border-bottom-width", "1px")
                } else {
                    a.children("a").text(lang("showDetail"));
                    a.children("span").text(c.flowTitle);
                    a.parent().css("border-bottom-width", "0")
                }
            })
    },
    loadFormPanel: function() {
        var b = this;
        var a = function() {
            var d = b.baseUrl + "&task=ms_loadTemplateFile&tplSort=" + b.tplSort;
            var e = "index.php";
            if (b.tplSort == 1 || b.tplSort == 3) {
                var f = "doc"
            } else {
                var f = "xls"
            }
            var c = "a=b&c=d&flowId=" + b.flowId;
            $("#wf_deal_formct").html("").css("border", "none");
            openOfficeForEdit_WF("wf_deal_formct", d, f, "自由流程表单", 1, e, c, true)
        };
        if (b.flowType == 0) {
            $("#wf_deal_free_formct").hide();
            if (b.tplSort == 0 || b.tplSort == 3) {
                Ext.Ajax.request({
                    url: b.baseUrl + "&task=loadFormHtml",
                    method: "POST",
                    success: function(d) {
                        var c = Ext.decode(d.responseText);
                        if (c.success === true) {
                            Ext.fly(b.ID_CNOA_wf_use_formPanel_ct).update(c.data.formHtml + '<div style="clear:both;"></div>');
                            CNOA_wf_form_checker.formInit(b.ID_CNOA_wf_use_formPanel_ct, b.uFlowId, b.flowId);
                            CNOA_wf_form_checker.formInitForView();
                            setPageSet($("#" + b.ID_CNOA_wf_use_formPanel_ct).get(0), Ext.decode(c.pageSet))
                        } else {
                            CNOA.msg.alert(c.msg,
                                function() {})
                        }
                    }
                });
                if (b.tplSort == 3) {
                    a()
                }
            } else {
                a()
            }
        } else {
            if (b.tplSort == 0) {
                $("#wf_deal_formct").hide();
                new Ext.Panel({
                    renderTo: b.ID_CNOA_wf_use_freeFlow_formPanel_ct,
                    autoHeight: true,
                    layout: "fit",
                    id: b.ID_HtmlEditor,
                    name: "htmlFormContent",
                    bodyCfg: {
                        id: "CNOA_FLOW_NEWFREE_FLOW_FORM_DESIGN"
                    },
                    listeners: {
                        afterrender: function(c) {
                            b.editor = UE.getEditor("CNOA_FLOW_NEWFREE_FLOW_FORM_DESIGN", {
                                imageUrl: "../../php/imageUp.php",
                                catcherUrl: "../../php/getRemoteImage.php",
                                toolbars: [["fullscreen", "source", "|", "justifyleft", "justifycenter", "justifyright", "justifyjustify", "|", "bold", "italic", "underline", "fontborder", "strikethrough", "superscript", "subscript", "forecolor", "backcolor", "pasteplain", "|", "fontfamily", "fontsize", "|", "link", "unlink", "|", "insertimage", "wordimage", "imagenone", "imageleft", "imageright", "imagecenter", "|", "inserttable", "deletetable", "insertparagraphbeforetable", "insertrow", "deleterow", "insertcol", "deletecol", "mergecells", "mergeright", "mergedown", "splittocells", "splittorows", "splittocols"]],
                                autoClearinitialContent: true,
                                wordCount: false,
                                readonly: b.changeFlowInfo ? true: false,
                                elementPathEnabled: false,
                                initialFrameHeight: 420,
                                initialFrameWidth: c.getWidth() - 20
                            })
                        }
                    }
                })
            } else {
                $("#wf_deal_free_formct").hide();
                a()
            }
        }
        b.loadUflowInfo();
        setTimeout(function() {
                $("#CNOA_WEBOFFICE").height(769)
            },
            200);
        if (b.flowType == 0 && b.tplSort == 0) {
            $("#wf_form_newfree_tushi").children().show()
        } else {
            $("#wf_form_newfree_tushi").children().hide()
        }
    },
    addAttach: function(b, h) {
        var d = this;
        var f = h.lastIndexOf("/");
        if (f == -1) {
            f = h.lastIndexOf("\\")
        }
        var a = h.substr(f + 1);
        a += "<div style='display:none;' id='" + b.id + "-file-ct'></div>";
        var g = false;
        jQuery("#" + this.ID_attachTable + " input[type=file]").each(function() {
            if (jQuery(this).val() == h) {
                g = true
            }
        });
        if (g) {
            CNOA.msg.notice2(lang("file") + ": " + a + lang("alreadyUpload"));
            return
        }
        var c = new Date();
        var j = c.format("Y-m-d H:i:s");
        var k = jQuery("#" + this.ID_attachTable);
        Ext.fly(d.ID_displayAt).dom.style.display = "block";
        var m = jQuery("#" + this.ID_attachTable + " tr").length;
        var e = '<tr height=24 align=left bgColor="#FFFFFF"><td bgColor="#FFFFFF">&nbsp;' + a + '</td><td bgColor="#FFFFFF">&nbsp;' + CNOA_USER_TRUENAME + '</td><td bgColor="#FFFFFF">&nbsp;' + j + '</td><td bgColor="#FFFFFF">&nbsp;<a href="javascript:void(0)" onclick="CNOA_wf_use_dealflow.removefile(this);">删除</a></td>';
        "</tr>";
        jQuery(e).insertAfter(jQuery("#" + this.ID_attachTable + " tr:eq(" + (m - 1) + ")"));
        jQuery("#" + b.id + "-file-ct").append(jQuery("#" + b.id + "-file"));
        Ext.fly(this.ID_attachTable).dom.scrollIntoView();
        jQuery("#" + this.attachmentCt).html("");
        new Ext.ux.form.FileUploadField({
            renderTo: this.attachmentCt,
            buttonOnly: true,
            buttonText: lang("addAttach"),
            buttonCfg: {
                text: lang("addAttach"),
                iconCls: "icon-folder--plus"
            },
            listeners: {
                fileselected: function(o, n) {
                    d.addAttach(o, n)
                }
            }
        })
    },
    seqFlowSendNextStep: function(c) {
        var k = this;
        var a = "";
        if (k.tplSort != 0) {} else {
            a = k.editor.getContent()
        }
        var b = k.formPanel;
        var g = b.getForm();
        try {
            b.body.dom.setAttribute("enctype", "multipart/form-data");
            b.body.dom.enctype = "multipart/form-data"
        } catch(j) {}
        var h = Ext.getCmp(this.ID_checkAbout).getValue();
        var d = function() {
            g.submit({
                url: k.baseUrl + "&task=seqFlowSendNextStep",
                params: {
                    flowType: k.flowType,
                    tplSort: k.tplSort,
                    flowId: k.flowId,
                    uFlowId: k.uFlowId,
                    step: k.step,
                    say: h,
                    type: c,
                    htmlFormContent: a
                },
                method: "POST",
                success: function(n, f) {
                    if (f.result.data.stepType == "done") {
                        CNOA.msg.notice(lang("endFlow"), lang("workFlow"))
                    } else {
                        CNOA.msg.notice(lang("flowIntoStep"), lang("workFlow"))
                    }
                    if (k.tplSort == 0) {
                        k.closeTab()
                    } else {
                        k.tplFile.saveTemplateFile()
                    }
                    try {
                        CNOA_wf_use_todo.store.reload()
                    } catch(m) {}
                },
                failure: function(f, e) {}
            })
        };
        CNOA.msg.cf(lang("confirmNextStep"),
            function(e) {
                if (e == "yes") {
                    d()
                }
            })
    },
    sendfreeFlowNextStepWin: function(a) {
        var b = this;
        CNOA_wf_use_newfree_goNextStepWin = new CNOA_wf_use_newfree_goNextStepWinClass(b.flowId, b.tplSort, b.flowType, "deal", this.step, this.uFlowId, a);
        CNOA_wf_use_newfree_goNextStepWin.show()
    },
    getSendNextData: function(b, c) {
        var h = this;
        var g = h.formPanel.getForm();
        g.fileUpload = false;
        var a = Ext.getCmp(this.ID_checkAbout),
            e = true;
        var d = function() {
            if (!g.isValid()) {
                CNOA.msg.alert(lang("formValid"));
                return false
            } else {
                if (!CNOA_wf_form_checker.check()) {
                    return false
                }
                var f = $('.wf_form_content input[dataType="user_sel"]');
                var j = "";
                f.each(function() {
                    j += $(this).attr("id") + "=";
                    j += $(this).val() + "|"
                });
                g.submit({
                    url: h.baseUrl + "&task=getSendNextData",
                    params: {
                        flowId: h.flowId,
                        uFlowId: h.uFlowId,
                        step: h.step,
                        tplSort: h.tplSort,
                        user_sel: j
                    },
                    method: "POST",
                    success: function(m, k) {
                        if (k.result.data.length == 1 && c == "keep" && k.result.data[0].isEnd != true) {
                            if (k.result.data[0].operator && k.result.data[0].operator.length < 2 && !k.result.data[0].child) {
                                h.keepNextStep(k.result.data[0], c)
                            } else {
                                h.showSendNextStepWin(k.result.data, c)
                            }
                        } else {
                            h.showSendNextStepWin(k.result.data, c)
                        }
                    },
                    failure: function(m, k) {
                        CNOA.msg.alert(k.result.msg)
                    }
                })
            }
        };
        d()
    },
    showSendNextStepWin: function(b, a) {
        var c = this;
        CNOA_wf_use_goNextStepWin = new CNOA_wf_use_goNextStepWinClass(b, "deal", a, c.tplSort)
    },
    sendNextStep: function(b, d) {
        var j = this;
        var a = j.formPanel,
            c = a.getForm(),
            h = Ext.getCmp(this.ID_checkAbout).getValue();
        try {
            a.body.dom.setAttribute("enctype", "multipart/form-data");
            a.body.dom.enctype = "multipart/form-data"
        } catch(g) {}
        c.submit({
            url: j.baseUrl + "&task=sendNextStep",
            params: {
                flowType: j.flowType,
                tplSort: j.tplSort,
                flowId: j.flowId,
                uFlowId: j.uFlowId,
                step: j.step,
                nextStepId: b.stepId,
                nextUid: b.uid,
                say: h,
                type: b.type,
                phone: b.phone,
                convergenenUid: b.convergenenUid
            },
            method: "POST",
            success: function(n, f) {
                CNOA.msg.notice(lang("flowIntoStep") + "：" + b.stepname + "<br />" + lang("acceptOfficer") + "：" + b.uname, lang("workFlow"), 6);
                if (j.flowType == 0) {
                    if (j.tplSort != 0) {
                        j.tplFile.saveTemplateFile()
                    }
                }
                try {
                    CNOA_notice_notice_todo.reload()
                } catch(k) {}
                try {
                    CNOA_wf_use_todo.store.reload()
                } catch(k) {}
                d.call(j);
                var m = f.result.autoNextWfInfo;
                if (m && window.wfDealInNewWindow != true) {
                    CNOA.msg.cf("流程办理完毕，是否继续下一个流程办理？",
                        function(e) {
                            if (e == "yes") {
                                j.closeTab();
                                mainPanel.loadClass("index.php?app=wf&func=flow&action=use&modul=todo&task=loadPage&from=dealflow&uFlowId=" + m.uFlowId + "&flowId=" + m.flowId + "&step=" + m.uStepId + "&flowType=" + m.flowType + "&tplSort=" + m.tplSort, "CNOA_MENU_WF_USE_OPENFLOW", lang("dealWorkFlow"), "icon-flow")
                            } else {
                                j.closeTab()
                            }
                        })
                } else {
                    j.closeTab()
                }
            },
            failure: function(f, e) {
                CNOA.msg.alert(e.result.msg);
                d.call(j)
            }
        })
    },
    keepNextStep: function(d, b) {
        var j = this;
        var a = j.formPanel,
            c = a.getForm(),
            h = Ext.getCmp(this.ID_checkAbout).getValue();
        try {
            a.body.dom.setAttribute("enctype", "multipart/form-data");
            a.body.dom.enctype = "multipart/form-data"
        } catch(g) {}
        c.submit({
            url: j.baseUrl + "&task=sendNextStep",
            params: {
                flowType: j.flowType,
                tplSort: j.tplSort,
                flowId: j.flowId,
                uFlowId: j.uFlowId,
                step: j.step,
                nextStepId: d.stepId,
                nextUid: d.operator[0].uid,
                say: h,
                type: b
            },
            method: "POST",
            success: function(m, f) {
                CNOA.msg.notice(lang("flowIntoStep") + "：" + d.stepName + "<br />" + lang("acceptOfficer") + "：" + d.operator[0].name, lang("workFlow"), 6);
                try {
                    CNOA_wf_use_todo.store.reload()
                } catch(k) {}
                j.closeTab()
            },
            failure: function(f, e) {}
        })
    },
    rejectReason: function() {
        var b = this;
        var a = Ext.getCmp(b.ID_checkAbout).getValue();
        Ext.Ajax.request({
            url: b.baseUrl + "&task=submitRejectAbout",
            method: "POST",
            params: {
                flowType: b.flowType,
                tplSort: b.tplSort,
                flowId: b.flowId,
                uFlowId: b.uFlowId,
                step: b.step,
                say: a
            },
            success: function(d) {
                var c = Ext.decode(d.responseText);
                if (c.success === true) {
                    CNOA.msg.notice(c.msg, lang("refuseFlowDeal"));
                    try {
                        CNOA_notice_notice_todo.reload()
                    } catch(f) {}
                    try {
                        CNOA_wf_use_todo.store.reload()
                    } catch(f) {}
                    try {
                        b.closeTab()
                    } catch(f) {}
                } else {
                    CNOA.msg.alert(c.msg)
                }
            }
        })
    },
    huiqianWin: function() {
        CNOA_wf_use_dealflow_huiqian = new CNOA_wf_use_dealflow_huiqianClass(this.uFlowId, this.step)
    },
    fenfaWin: function() {
        CNOA_wf_use_dealflow_fenfa = new CNOA_wf_use_dealflow_fenfaClass(this.uFlowId, this.step)
    },
    goto_prevstepWin: function() {
        var a = this;
        CNOA_wf_use_dealflow_gotoPrevStep = new CNOA_wf_use_dealflow_gotoPrevStepClass(this.uFlowId, this.step, this.flowType, this.tplSort)
    },
    entrustWin: function() {
        var a = this;
        CNOA_wf_use_dealflow_entrust = new CNOA_wf_use_dealflow_entrustClass(this.uFlowId, this.flowId, this.step, this.flowType, this.tplSort)
    },
    join_calender: function() {
        var b = this;
        var a = lang("flowNumber") + ":" + b.flowInfo.flowNumber + "\n";
        a += lang("importantGrade") + ":" + b.flowInfo.level + "\n";
        a += lang("initiator") + ":" + b.flowInfo.uname + "\n";
        a += lang("initTime") + ":" + b.flowInfo.postTime + "\n";
        CalendarWin(lang("flow") + "[" + b.flowInfo.flowName + "]" + lang("needYouApproval"), a)
    },
    finishFlow: function() {
        var g = this;
        say = Ext.getCmp(g.ID_checkAbout).getValue();
        var a = "";
        if (g.flowType != 0) {
            if (g.tplSort != 0) {} else {
                a = g.editor.getContent()
            }
        }
        var b = g.formPanel;
        var c = b.getForm();
        try {
            b.body.dom.setAttribute("enctype", "multipart/form-data");
            b.body.dom.enctype = "multipart/form-data"
        } catch(d) {}
        if (c.isValid()) {
            CNOA.msg.cf(lang("sureWantEndProcess"),
                function(e) {
                    if (e == "yes") {
                        c.submit({
                            url: g.baseUrl + "&task=finishFlow",
                            params: {
                                flowType: g.flowType,
                                tplSort: g.tplSort,
                                htmlFormContent: a,
                                say: say
                            },
                            method: "POST",
                            success: function(j, f) {
                                CNOA.msg.notice(lang("processIsOver") + "<br />" + lang("acceptOfficer") + "：" + CNOA_USER_TRUENAME, lang("workFlow"), 6);
                                if (g.tplSort == 0) {
                                    g.closeTab()
                                } else {
                                    g.tplFile.saveTemplateFile()
                                }
                                try {
                                    CNOA_wf_use_todo.store.reload()
                                } catch(h) {}
                            },
                            failure: function(h, f) {
                                CNOA.msg.alert(f.result.msg,
                                    function() {})
                            }
                        })
                    }
                })
        }
    },
    showFlowStep: function() {
        var a = this;
        CNOA_wf_use_showFlowStep = new CNOA_wf_use_showFlowStepClass(this.uFlowId, true, false, this.flowId, this.flowType, this.tplSort)
    },
    showFlowEvent: function() {
        var b = this;
        var a = new CNOA_wf_use_showFlowEventClass(this.uFlowId, true, this.flowId, this.flowType, this.tplSort)
    },
    closeTab: function() {
        var a = this;
        mainPanel.closeTab("CNOA_MENU_WF_USE_OPENFLOW")
    },
    loadUflowInfo: function() {
        var c = this;
        var a = c.formPanel,
            b = a.getForm();
        b.load({
            url: c.baseUrl + "&task=loadUflowInfo",
            method: "POST",
            params: {
                flowId: this.flowId,
                uFlowId: this.uFlowId,
                step: this.step,
                flowType: this.flowType,
                tplSort: this.tplSort
            },
            success: function(d, e) {
                c.initView(e);
                c.flowInfo = e.result.data;
                if (c.flowType != 0) {
                    if (c.tplSort == 0) {
                        c.editor.setContent(e.result.data.htmlFormContent)
                    }
                }
                if (CNOA.wf.use_dealflow.showChildAlert == 1) {
                    CNOA.msg.cf(lang("needFQzlc"),
                        function(f) {
                            if (f == "yes") {
                                c.childFlowWin()
                            }
                        })
                }
                if (window.wfDealInNewWindow) {
                    document.title = "办理流程：《" + c.flowInfo.flowNumber + " - " + c.flowInfo.flowName + "》"
                }
                setTimeout(function() {
                        var f = $("[mark=flowview]");
                        f.each(function(g) {
                            var h = Ext.decode($(this).attr("data"));
                            $(this).bind("click",
                                function() {
                                    mainPanel.closeTab("CNOA_MENU_WF_USE_JUMPVIEW");
                                    if (h.status == 1) {
                                        mainPanel.loadClass("index.php?app=wf&func=flow&action=use&modul=todo&task=loadPage&type=show&from=showflow&uFlowId=" + h.uFlowId + "&flowId=" + h.flowId + "&step=" + h.stepId + "&flowType=" + h.flowType + "&tplSort=" + h.tplSort, "CNOA_MENU_WF_USE_JUMPVIEW", lang("seeRelateFlow"), "icon-flow")
                                    } else {
                                        if (h.status == 2) {
                                            mainPanel.loadClass("index.php?app=wf&func=flow&action=use&modul=done&task=loadPage&from=showflow&uFlowId=" + h.uFlowId + "&flowId=" + h.flowId + "&step=" + h.stepId + "&flowType=" + h.flowType + "&tplSort=" + h.tplSort + "&owner=" + h.owner, "CNOA_MENU_WF_USE_JUMPVIEW", lang("seeRelateFlow"), "icon-flow")
                                        }
                                    }
                                })
                        })
                    },
                    100)
            },
            failure: function(d, e) {
                CNOA.msg.alert(e.result.msg,
                    function() {})
            }.createDelegate(this)
        })
    },
    initView: function(action) {
        var _this = this;
        var flowTitleCt = $("#" + _this.ID_flowTitle);
        _this.flowTitle = lang("flow") + ": " + action.result.data.flowNumber + "(" + action.result.data.level + ")  ";
        _this.flowTitle += "[" + action.result.data.uname + "]";
        _this.flowTitle += "[" + action.result.data.postTime + "]";
        flowTitleCt.children("span").text(_this.flowTitle);
        flowTitleCt.children("span").css("font-weight", "600");
        var config = action.result.config;
        if (_this.flowType == 0) {
            with(config) {
                try {
                    btnText = !btnText ? lang("turnNextStep") : btnText;
                    Ext.getCmp(_this.ID_BTN_TY).setText(btnText)
                } catch(e) {}
                try {
                    if (this.stepType != "5" && this.stepType != "6" && allowReject == "1" && _this.isDeals == "0") {
                        Ext.getCmp(this.ID_BTN_JJ).show()
                    } else {
                        Ext.getCmp(this.ID_BTN_JJ).hide()
                    }
                } catch(e) {}
                try {
                    allowHuiqian == "1" ? Ext.getCmp(this.ID_BTN_HQ).show() : Ext.getCmp(this.ID_BTN_HQ).hide()
                } catch(e) {}
                try {
                    allowFenfa == "1" ? Ext.getCmp(this.ID_BTN_FF).show() : Ext.getCmp(this.ID_BTN_FF).hide()
                } catch(e) {}
                try {
                    allowTuihui == "1" ? Ext.getCmp(this.ID_BTN_TH).show() : Ext.getCmp(this.ID_BTN_TH).hide()
                } catch(e) {}
                try {
                    allowAttachAdd == 1 ? Ext.getCmp(this.ID_BTN_FJ).show() : Ext.getCmp(this.ID_BTN_FJ).hide()
                } catch(e) {}
                try {
                    allowErust == 0 ? Ext.getCmp(this.ID_BTN_WT).hide() : Ext.getCmp(this.ID_BTN_WT).show()
                } catch(e) {}
                try {
                    allowSms == "1" ? Ext.getCmp(_this.ID_BTN_SMS).show() : Ext.getCmp(_this.ID_BTN_SMS).hide()
                } catch(e) {}
                try {
                    allowYijian == "1" ? Ext.getCmp(this.ID_BTN_YJ).show() : Ext.getCmp(this.ID_BTN_YJ).hide()
                } catch(e) {}
            }
        } else {
            with(config) {
                try {
                    allowErust == 0 ? Ext.getCmp(this.ID_BTN_WT).hide() : Ext.getCmp(this.ID_BTN_WT).show()
                } catch(e) {}
            }
        }
        if (action.result.attach.length > 0) {
            Ext.fly(_this.ID_displayAt).dom.style.display = "block";
            _this.createAttachList(action.result.attach)
        }
        this.changeWidth();
        var mainPanelId = this.mainPanel.getId();
        this.mainPanel.on("resize",
            function() {
                _this.changeWidth(mainPanelId)
            })
    },
    insertSms: function(c, h, a, f) {
        var e = this;
        var b = new Date();
        var j = b.format("Y-m-d H:i:s");
        Ext.fly(e.ID_displaySms).dom.style.display = "block";
        var k = jQuery("#" + this.ID_smsTable + " tr").length;
        var d = "<input type='hidden' name='sms[]' value='" + Ext.encode({
            mobiles: a,
            names: h,
            content: f
        }) + "' />";
        var g = '<tr height=24 align="left" bgColor="#FFFFFF"><td bgColor="#FFFFFF">&nbsp;' + lang("currentStep") + '</td><td bgColor="#FFFFFF">&nbsp;' + a + "(" + h + ") " + d + '</td><td bgColor="#FFFFFF">&nbsp;' + f + '</td><td bgColor="#FFFFFF">&nbsp;' + j + '</td><td bgColor="#FFFFFF">&nbsp;' + CNOA_USER_TRUENAME + '</td><td bgColor="#FFFFFF">&nbsp;<a href="javascript:void(0)" onclick="CNOA_wf_use_dealflow.removeSms(this);">' + lang("del") + "</a></td>";
        "</tr>";
        jQuery(g).insertAfter(jQuery("#" + this.ID_smsTable + " tr:eq(" + (k - 1) + ")"));
        Ext.fly(this.ID_smsTable).dom.scrollIntoView()
    },
    removeSms: function(a) {
        var b = this;
        CNOA.msg.cf(lang("sureDel"),
            function(c) {
                if (c == "yes") {
                    jQuery(a).parent().parent("tr").remove();
                    var d = jQuery("#" + b.ID_smsTable + " tr").length;
                    if (d == 1) {
                        Ext.fly(b.ID_displaySms).dom.style.display = "none"
                    }
                }
            })
    },
    changeWidth: function(b) {
        var c = $("#" + b);
        var d = c.find(".wf_div_cttb").width();
        var a = c.width() - 40;
        if (a < d) {
            c.find(".cnoa-formhtml-layout").width(d + 40)
        } else {
            c.find(".cnoa-formhtml-layout").css("width", "")
        }
    },
    createAttachList: function(d) {
        var e = this;
        var c = jQuery("#" + this.ID_attachTable + " tr");
        c.each(function(f) {
            var g = $(this);
            if ((f > 0) && g.attr("upd") != "true") {
                g.remove()
            }
        });
        for (var a = 0; a < d.length; a++) {
            var b = '<tr class="attach_' + d[a].attachid + '" upd="true" height=24 align="left" bgColor="#FFFFFF"><td bgColor="#FFFFFF">&nbsp;' + d[a].filename + '<input type="hidden" name="wf_attach_' + d[a].attachid + '" value="1" /></td><td bgColor="#FFFFFF">&nbsp;' + d[a].uname + '</td><td bgColor="#FFFFFF">&nbsp;' + d[a].date + '</td><td bgColor="#FFFFFF">&nbsp;' + d[a].optStr + "</td>";
            "</tr>";
            jQuery(b).appendTo(jQuery("#" + e.ID_attachTable))
        }
    },
    removefile: function(b) {
        var c = this;
        var a = $(b).closest("tr").attr("class");
        CNOA.msg.cf(lang("sureDelAttchment"),
            function(d) {
                if (d == "yes") {
                    if (a) {
                        $("." + a).remove()
                    }
                    jQuery(b).parent().parent("tr").remove();
                    var e = jQuery("#" + c.ID_attachTable + " tr").length;
                    if (e == 1) {
                        Ext.fly(c.ID_displayAt).dom.style.display = "none"
                    }
                }
            })
    },
    saveFileDraft: function(b) {
        var g = this;
        var a = g.formPanel,
            c = a.getForm();
        try {
            a.body.dom.setAttribute("enctype", "multipart/form-data");
            a.body.dom.enctype = "multipart/form-data"
        } catch(d) {}
        say = Ext.getCmp(CNOA_wf_use_dealflow.ID_checkAbout).getValue();
        if (say == "" && b == 2) {
            CNOA.msg.alert("请填写办理理由")
        } else {
            c.submit({
                url: g.baseUrl + "&task=saveFieldDraft",
                params: {
                    flowId: g.flowId,
                    uFlowId: g.uFlowId,
                    step: g.step,
                    tplSort: g.tplSort,
                    type: b,
                    say: say
                },
                method: "POST",
                success: function(h, f) {
                    var e = f.result;
                    CNOA.msg.alert(e.msg)
                }
            })
        }
    }
};
var CNOA_wf_use_viewflowClass, CNOA_wf_use_viewflow;
var CNOA_wf_use_viewflow_readerClass, CNOA_wf_use_viewflow_reader;
var CNOA_wf_use_viewflow_readerClass = CNOA.Class.create();
CNOA_wf_use_viewflow_readerClass.prototype = {
    init: function(a, d, b, c) {
        var e = this;
        this.stepId = d;
        this.flowType = b;
        this.tplSort = c;
        this.baseUrl = "index.php?app=wf&func=flow&action=use&modul=view";
        this.formPanel = new Ext.form.FormPanel({
            border: false,
            hideBorders: true,
            labelWidth: 70,
            labelAlign: "right",
            waitMsgTarget: true,
            autoScroll: true,
            bodyStyle: "padding-top:10px;",
            listeners: {
                render: function(f) {
                    f.load({
                        url: e.baseUrl + "&task=loadReaderSay",
                        params: {
                            uFlowId: a
                        },
                        method: "POST",
                        waitMsg: lang("waiting"),
                        success: function(g, h) {},
                        failure: function(g, h) {
                            CNOA.msg.alert(h.result.msg,
                                function() {})
                        }
                    })
                }
            },
            items: [{
                xtype: "textarea",
                anchor: "-25 -25",
                name: "say",
                allowBlank: false,
                fieldLabel: lang("pyOpinion"),
                value: lang("agree")
            }]
        });
        this.win = new Ext.Window({
            width: 440,
            height: 310,
            title: lang("postReviewComment"),
            resizable: true,
            modal: true,
            layout: "fit",
            items: [this.formPanel],
            tbar: new Ext.Toolbar({
                items: lang("pubishOpinionReplace")
            }),
            buttons: [{
                text: lang("ok"),
                iconCls: "icon-order-s-accept",
                handler: function() {
                    e.submitReaderSay(a)
                }.createDelegate(this)
            },
                {
                    text: lang("cancel"),
                    iconCls: "icon-dialog-cancel",
                    handler: function() {
                        e.win.close()
                    }.createDelegate(this)
                }]
        }).show()
    },
    submitReaderSay: function(b) {
        var e = this;
        var a = e.formPanel.getForm().findField("say").getValue();
        var c = new Date();
        var d = c.format("Y-m-d H:i");
        if (e.formPanel.getForm().isValid()) {
            e.formPanel.getForm().submit({
                url: e.baseUrl + "&task=addReaderSay",
                waitTitle: lang("notice"),
                method: "POST",
                waitMsg: lang("waiting"),
                params: {
                    uFlowId: b,
                    stepId: e.stepId,
                    flowType: e.flowType,
                    tplSort: e.tplSort
                },
                success: function(g, j) {
                    CNOA.msg.notice(j.result.msg, lang("flowRead"));
                    var f = jQuery("#" + CNOA_wf_use_viewflow.ID_readTable);
                    Ext.fly(CNOA_wf_use_viewflow.ID_displayRd).dom.style.display = "block";
                    var m = jQuery("#" + CNOA_wf_use_viewflow.ID_readTable + " tr").length;
                    var h = '<tr height=24 align=left bgColor="#FFFFFF"><td bgColor="#FFFFFF">&nbsp;' + j.result.fenfaName + '</td><td bgColor="#FFFFFF">&nbsp;' + CNOA_USER_TRUENAME + '</td><td bgColor="#FFFFFF">&nbsp;' + CNOA_USER_DEPTMENT + '</td><td bgColor="#FFFFFF">&nbsp;' + a + '</td><td bgColor="#FFFFFF">&nbsp;' + d + "</td></tr>";
                    jQuery(h).insertAfter(jQuery("#" + CNOA_wf_use_viewflow.ID_readTable + " tr:eq(" + (m - 1) + ")"));
                    if (m == 2) {
                        $("#" + CNOA_wf_use_viewflow.ID_readTable).children().children("tr").has("td").eq(1).remove()
                    }
                    e.win.close();
                    try {
                        mainPanel.closeTab("CNOA_MENU_WF_USE_OPENFLOW")
                    } catch(k) {}
                }.createDelegate(this),
                failure: function(f, g) {
                    CNOA.msg.alert(g.result.msg,
                        function() {
                            e.win.close()
                        }.createDelegate(this))
                }.createDelegate(this)
            })
        }
    }
};
CNOA_wf_use_viewflowClass = CNOA.Class.create();
CNOA_wf_use_viewflowClass.prototype = {
    init: function() {
        var a = this;
        if (CNOA.wf.use_viewflow.allowPrint == "false") {
            allowPrint = false
        } else {
            allowPrint = true
        }
        this.ID_readTable = Ext.id();
        this.ID_displayRd = Ext.id();
        this.ID_displayAt = Ext.id();
        this.ID_CNOA_wf_use_flowPanel_ct = Ext.id();
        this.ID_CNOA_wf_use_formPanel_ct = Ext.id();
        this.ID_CNOA_wf_use_attachPanel_ct = Ext.id();
        this.ID_CNOA_wf_use_readerPanel_ct = Ext.id();
        this.ID_HtmlEditor = Ext.id();
        this.flowId = CNOA.wf.use_viewflow.flowId;
        this.uFlowId = CNOA.wf.use_viewflow.uFlowId;
        this.readId = CNOA.wf.use_viewflow.readId;
        this.step = CNOA.wf.use_viewflow.step;
        this.flowType = CNOA.wf.use_viewflow.flowType;
        this.tplSort = CNOA.wf.use_viewflow.tplSort;
        this.baseUrl = "index.php?app=wf&func=flow&action=use&modul=view&uFlowId=" + a.uFlowId + "&flowId=" + this.flowId + "&stepId=" + this.step + "&" + getSessionStr();
        this.baseURI = location.href.substr(0, location.href.lastIndexOf("/") + 1);
        this.formPanel = new Ext.form.FormPanel({
            fileUpload: false,
            hideBorders: true,
            border: false,
            region: "center",
            autoScroll: true,
            layout: "htmlform",
            defaults: {
                xtype: "displayfield",
                width: 300
            },
            layoutConfig: {
                cache: false,
                template: (this.tplSort == 3 || this.tplSort == 1) ? "app/wf/tpl/default/flow/use/form_view_tplSort3.tpl.html": "app/wf/tpl/default/flow/use/form_view.tpl.html",
                templateData: {
                    str_flowInfo: lang("flowInformation"),
                    str_flowNumber: lang("flowNumber"),
                    str_flowLevel: lang("importantGrade"),
                    str_flowFreeTitle: lang("customFlowTitle"),
                    str_flowReason: lang("appReason"),
                    str_flowFormInfo: lang("formInfo"),
                    str_flowAttach: lang("attachmentInfo"),
                    str_flowAttachName: lang("attName"),
                    str_flowUploadBy: lang("uploadBy"),
                    str_flowUploadTime: lang("uploadTime"),
                    str_flowOpt: lang("opt"),
                    str_flowTextInfo: lang("textInfo"),
                    str_flowShowDetail: lang("showDetail"),
                    str_flowStatus: lang("processState"),
                    str_flowInitiator: lang("initiator"),
                    str_flowInitTime: lang("initTime"),
                    str_flowReadInfo: lang("reviewInformation"),
                    str_flowFFpeople: lang("distributePeople"),
                    str_flowPyPeople: lang("pyMan"),
                    str_flowBelongDept: lang("belongDept"),
                    str_flowPyContent: lang("pyContent"),
                    str_flowPyDate: lang("pyDate"),
                    str_flowShowHide: lang("cllickShowHide"),
                    id_flowPanel: this.ID_CNOA_wf_use_flowPanel_ct,
                    id_formPanel: this.ID_CNOA_wf_use_formPanel_ct,
                    id_displayAt: this.ID_displayAt,
                    id_displayRd: this.ID_displayRd,
                    id_readTable: this.ID_readTable,
                    id_readerPanel: this.ID_CNOA_wf_use_readerPanel_ct,
                    id_attachPanel: this.ID_CNOA_wf_use_attachPanel_ct,
                    id_attachTable: this.ID_attachTable
                }
            },
            items: [{
                name: "flowNumber"
            },
                {
                    name: "flowName"
                },
                {
                    name: "status"
                },
                {
                    name: "uname"
                },
                {
                    name: "postTime"
                },
                {
                    name: "reason"
                },
                {
                    name: "level",
                    cls: "wf-reason"
                }],
            listeners: {
                afterrender: function() {
                    setTimeout(function() {
                        if ($(".wf_form_content").width() < 540) {}
                        a.loadFormPanel()
                    })
                }
            },
            bbar: new Ext.Toolbar({
                items: [{
                    handler: function(b, c) {
                        if (a.flowType == 0 && a.tplSort == 0) {
                            a.printFlow()
                        } else {
                            a.printFreeFlow()
                        }
                    }.createDelegate(this),
                    hidden: allowPrint ? false: true,
                    iconCls: "icon-print",
                    text: lang("print") + " / " + lang("export2")
                },
                    {
                        handler: function(b, c) {
                            CNOA_wf_use_viewflow_reader = new CNOA_wf_use_viewflow_readerClass(this.uFlowId, this.stepId, this.flowType, this.tplSort)
                        }.createDelegate(this),
                        iconCls: "icon-view-form-png",
                        text: lang("pyOpinion")
                    },
                    {
                        text: lang("flowStep"),
                        iconCls: "icon-application-task",
                        tooltip: lang("flowStep"),
                        handler: function() {
                            a.showFlowStep()
                        }.createDelegate(this)
                    },
                    {
                        text: lang("flowEvent"),
                        tooltip: lang("flowEvent"),
                        iconCls: "icon-event",
                        handler: function() {
                            a.showFlowEvent()
                        }.createDelegate(this)
                    },
                    {
                        iconCls: "icon-dialog-cancel",
                        text: lang("close"),
                        handler: function(b, c) {
                            a.closeTab();
                            try {
                                CNOA_wf_use_view.store.reload()
                            } catch(d) {}
                        }.createDelegate(this)
                    }]
            })
        });
        this.mainPanel = new Ext.Panel({
            collapsible: false,
            hideBorders: true,
            border: false,
            layout: "border",
            items: [this.formPanel],
            listeners: {
                beforedestroy: function() {
                    try {
                        CNOA_wf_signature_electron.showDestroy()
                    } catch(b) {}
                }
            }
        })
    },
    loadFormPanel: function() {
        var b = this;
        var a = function() {
            var c = b.baseUrl + "&task=ms_loadTemplateFile&tplSort=" + b.tplSort;
            if (b.tplSort == 1 || b.tplSort == 3) {
                var d = "doc"
            } else {
                var d = "xls"
            }
            if (b.tplSort == 1) {
                $(".wf_div_ct:eq(1)").css("display", "none")
            }
            $("#wf_show_formct").html("").css("border", "none");
            openOfficeForView_WF("wf_show_formct", c, d, "自由流程表单")
        };
        if (b.flowType == 0) {
            if (b.tplSort == 0 || b.tplSort == 3) {
                Ext.Ajax.request({
                    url: b.baseUrl + "&task=loadFormHtml",
                    method: "POST",
                    success: function(d) {
                        var c = Ext.decode(d.responseText);
                        if (c.success === true) {
                            Ext.fly(b.ID_CNOA_wf_use_formPanel_ct).update(c.data.formHtml + '<div style="clear:both;"></div>');
                            var e = $(".wf_form_content input[sealstoredata=true]");
                            if (e.length > 0) {
                                loadJs("cnoa/app/wf/scripts/signature.electron.js", true,
                                    function() {
                                        CNOA_wf_signature_electron = new CNOA_signature_electronClass();
                                        CNOA_wf_signature_electron.SetSealValue(e)
                                    })
                            }
                            setPageSet($("#" + b.ID_CNOA_wf_use_formPanel_ct).get(0), Ext.decode(c.pageSet))
                        } else {
                            CNOA.msg.alert(c.msg,
                                function() {})
                        }
                    }
                });
                if (b.tplSort == 3) {
                    a()
                }
            } else {
                a()
            }
        } else {
            if (b.tplSort == 0) {} else {
                a()
            }
        }
        b.loadUflowInfo();
        setTimeout(function() {
                $("#CNOA_WEBOFFICE").height(769)
            },
            200);
        if (b.flowType == 0 && b.tplSort == 0) {
            $("#wf_form_newfree_tushi").children().show()
        } else {
            $("#wf_form_newfree_tushi").children().hide()
        }
    },
    loadUflowInfo: function() {
        var c = this;
        var a = c.formPanel,
            b = a.getForm();
        b.load({
            url: c.baseUrl + "&task=loadUflowInfo",
            method: "POST",
            params: {
                flowId: this.flowId,
                uFlowId: this.uFlowId,
                step: this.step,
                flowType: this.flowType,
                tplSort: this.tplSort
            },
            success: function(d, e) {
                if (c.flowType != 0) {
                    if (c.tplSort == 0 || c.tplSort == 3) {
                        Ext.fly(c.ID_CNOA_wf_use_formPanel_ct).update(e.result.data.htmlFormContent)
                    }
                }
                c.initView(e)
            },
            failure: function(d, e) {
                CNOA.msg.alert(e.result.msg,
                    function() {})
            }.createDelegate(this)
        })
    },
    initView: function(e) {
        var f = this;
        if (f.flowType == 0 && (f.tplSort == 0 || f.tplSort == 3)) {
            if (e.result.wf_detail_field_data) {
                var a = e.result.wf_detail_field_data;
                if (a.length > 0) {
                    for (var d = 0; d < a.length; d++) {
                        for (var c = 0; c < a[d].length; c++) {
                            $("#wf_detail_" + a[d][c].id).val(a[d][c].data)
                        }
                    }
                }
            }
        }
        if (e.result.attach.length > 0) {
            Ext.fly(f.ID_displayAt).dom.style.display = "block";
            f.createAttachList(e.result.attach)
        }
        if (e.result.readInfo.length > 0) {
            if (e.result.readInfo != "") {
                Ext.fly(f.ID_displayRd).dom.style.display = "block";
                f.createReadList(e.result.readInfo)
            }
        }
        this.changeWidth();
        var b = this.mainPanel.getId();
        this.mainPanel.on("resize",
            function() {
                f.changeWidth(b)
            })
    },
    changeWidth: function(b) {
        var c = $("#" + b);
        var d = c.find(".wf_div_cttb").width();
        var a = c.width() - 40;
        if (a < d) {
            c.find(".cnoa-formhtml-layout").width(d + 40)
        } else {
            c.find(".cnoa-formhtml-layout").css("width", "")
        }
    },
    createAttachList: function(e, a) {
        var f = this;
        var d = jQuery("#" + this.ID_attachTable + " tr");
        d.each(function(g) {
            var h = $(this);
            if ((g > 0) && h.attr("upd") != "true") {
                h.remove()
            }
        });
        for (var b = 0; b < e.length; b++) {
            var c = '<tr upd="true" height=24 align="left" bgColor="#FFFFFF"><td bgColor="#FFFFFF">&nbsp;' + e[b].filename + "[" + lang("uploaded") + ']<input type="hidden" name="wf_attach_' + e[b].attachid + '" value="1" /></td><td bgColor="#FFFFFF">&nbsp;' + e[b].uname + '</td><td bgColor="#FFFFFF">&nbsp;' + e[b].date + '</td><td bgColor="#FFFFFF" id="att">&nbsp;' + e[b].optStr + "</td>";
            "</tr>";
            jQuery(c).appendTo(jQuery("#" + f.ID_attachTable))
        }
    },
    createReadList: function(b) {
        var e = this;
        var d = jQuery("#" + this.ID_readTable + " tr");
        for (var a = 0; a < b.length; a++) {
            var c = '<tr upd="true" height=24 align="left" bgColor="#FFFFFF"><td bgColor="#FFFFFF">&nbsp;' + b[a].fenfaName + '</td><td bgColor="#FFFFFF">&nbsp;' + b[a].uname + '</td><td bgColor="#FFFFFF">&nbsp;' + b[a].deptment + '</td><td bgColor="#FFFFFF">&nbsp;' + b[a].say + '</td><td bgColor="#FFFFFF" id="att">&nbsp;' + b[a].sayDate + "</td>";
            "</tr>";
            jQuery(c).appendTo(jQuery("#" + e.ID_readTable))
        }
    },
    removefile: function(a) {
        var b = this;
        CNOA.msg.cf(lang("sureDelAttchment"),
            function(c) {
                if (c == "yes") {
                    jQuery(a).parent().parent("tr").remove();
                    var d = jQuery("#" + b.ID_attachTable + " tr").length;
                    if (d == 1) {
                        Ext.fly(b.ID_displayAt).dom.style.display = "none"
                    }
                }
            })
    },
    printFlow: function() {
        window.open("index.php?app=wf&func=flow&action=use&modul=todo&task=exportFlow&target=printer&flowId=" + this.flowId + "&uFlowId=" + this.uFlowId + "&step=" + this.step, "wfexport", "width=740,height=" + (screen.availHeight - 120) + ",left=" + ((screen.availWidth - 740) / 2) + ",top=60,scrollbars=yes,resizable=yes,status=no")
    },
    printFreeFlow: function() {
        window.open("index.php?app=wf&func=flow&action=use&modul=todo&task=exportFreeFlow&flowId=" + this.flowId + "&uFlowId=" + this.uFlowId + "&step=" + this.step + "&flowType=" + this.flowType + "&tplSort=" + this.tplSort, "wfexport", "width=740,height=" + (screen.availHeight - 120) + ",left=" + ((screen.availWidth - 740) / 2) + ",top=60,scrollbars=yes,resizable=yes,status=no")
    },
    showFlowStep: function() {
        var a = this;
        CNOA_wf_use_showFlowStep = new CNOA_wf_use_showFlowStepClass(this.uFlowId, true, false, this.flowId, this.flowType, this.tplSort)
    },
    showFlowEvent: function() {
        var a = this;
        CNOA_wf_use_showFlowEvent = new CNOA_wf_use_showFlowEventClass(this.uFlowId, true, this.flowId, this.flowType, this.tplSort)
    },
    closeTab: function() {
        mainPanel.closeTab("CNOA_MENU_WF_USE_OPENFLOW")
    }
};
var CNOA_wf_set_sortClass, CNOA_wf_set_sort;
CNOA_wf_set_sortClass = CNOA.Class.create();
CNOA_wf_set_sortClass.prototype = {
    init: function() {
        this.baseUrl = "workFlow";
        this.searchStore = null;
        this.searchToolbar = this.createSearchToolbar();
        this.mainPanel = this.createMainPanel();
        this.sortStore = this.mainPanel.store
    },
    createMainPanel: function() {
        var g = this;
        var f = [{
            name: "sortId"
        },
            {
                name: "name"
            },
            {
                name: "note"
            },
            {
                name: "type"
            },
            {
                name: "order"
            }];
        var j = new Ext.data.Store({
            autoLoad: true,
            proxy: new Ext.data.HttpProxy({
                url: this.baseUrl + "/getSortJsonData"
            }),
            reader: new Ext.data.JsonReader({
                totalProperty: "total",
                root: "data",
                fields: f
            }),
            listeners: {
                update: function(n, k, m) {
                    var o = k.data;
                    if (m == Ext.data.Record.EDIT) {
                        g.submit(o)
                    }
                }
            }
        });
        var d = new Ext.grid.CheckboxSelectionModel({
            singleSelect: true
        });
        var b = function(k) {
            return '<a class="gridview4" action="edit">' + lang("modify") + "</a>"
        };
        var h = new Ext.grid.ColumnModel({
            defaults: {
                sortable: true,
                menuDisabled: true
            },
            columns: [new Ext.grid.RowNumberer(), d, {
                header: "sortId",
                dataIndex: "sortId",
                hidden: true
            },
                {
                    header: lang("opt"),
                    dataIndex: "sortId",
                    width: 60,
                    sortable: false,
                    renderer: b
                },
                {
                    header: lang("sortName"),
                    dataIndex: "name",
                    width: 250
                },
                {
                    header: lang("description"),
                    dataIndex: "note",
                    width: 500
                },
                {
                    header: lang("order"),
                    dataIndex: "order",
                    width: 100,
                    editor: {
                        xtype: "textfield",
                        allowBlank: true
                    }
                },
                {
                    header: "",
                    dataIndex: "noIndex",
                    width: 1,
                    resizable: false
                }]
        });
        var c = new Ext.ux.grid.RowEditor({
            cancelText: lang("cancel"),
            saveText: lang("update"),
            errorSummary: false
        });
        var e = new Ext.PagingToolbar({
            displayInfo: true,
            style: "border-left-width: 0px;",
            store: j,
            pageSize: 15,
            listeners: {
                beforechange: function(k, m) {
                    if (g.searchStore != null) {
                        m.sname = g.searchStore.sname
                    }
                }
            }
        });
        var a = new Ext.grid.GridPanel({
            cm: h,
            sm: d,
            store: j,
            stripeRows: true,
            bodyStyle: "border-width: 0px; border-bottom-width: 1px;",
            plugins: [c],
            bbar: e,
            tbar: new Ext.Toolbar({
                style: "border-width: 0px",
                items: [{
                    text: lang("refresh"),
                    iconCls: "icon-system-refresh",
                    handler: function() {
                        j.reload()
                    }
                },
                    {
                        text: lang("add"),
                        iconCls: "icon-utils-s-add",
                        handler: function() {
                            g.showEditWin()
                        }
                    },
                    {
                        text: lang("del"),
                        iconCls: "icon-utils-s-delete",
                        handler: function() {
                            var k = a.getSelectionModel().getSelections();
                            if (k.length == 0) {
                                CNOA.miniMsg.alertShowAt(btn, lang("mustSelectOneRow"))
                            } else {
                                CNOA.msg.cf(lang("areYouDelete"),
                                    function(n) {
                                        if (n == "yes") {
                                            var o = [];
                                            for (var m = 0; m < k.length; m++) {
                                                o.push(k[m].get("sortId"))
                                            }
                                            g.deleteSort(o)
                                        }
                                    })
                            }
                        }
                    },
                    "<span class='cnoa_color_gray'>" + lang("doubleClickRecordEdit") + "</span>"]
            }),
            listeners: {
                render: function(k) {
                    g.searchToolbar.render(k.tbar, 0)
                },
                cellclick: function(m, o, k, n) {
                    if (n.target.getAttribute("action") === "edit") {
                        g.showEditWin(g.getStore().getAt(o).get("sortId"))
                    }
                    return k > 3
                }
            }
        });
        return a
    },
    createSearchToolbar: function() {
        var b = this;
        var a = new CNOA.toolbar.SearchToolbar({
            style: "border: 0px;",
            items: [lang("sortName"), {
                xtype: "textfield",
                width: 200,
                name: "sname",
                listeners: {
                    specialkey: function(d, c) {
                        if (c.getKey() == c.ENTER) {
                            b.sortStore.load({
                                params: a.getValue()
                            })
                        }
                    }
                }
            }],
            listeners: {
                search: function(d, c) {
                    b.searchStore = c;
                    b.sortStore.load({
                        params: c
                    })
                },
                clearSearch: function(c) {
                    b.searchStore = null;
                    b.sortStore.load()
                }
            }
        });
        return a
    },
    showEditWin: function(e) {
        var g = this;
        var f = new CNOA.selector.UserSelector({
            closeAction: "hide",
            multiSelect: true
        });
        var b = new CNOA.selector.StationSelector({
            closeAction: "hide",
            multiSelect: true
        });
        var d = new CNOA.selector.DepartmentSelector({
            closeAction: "hide",
            multiSelect: true
        });
        var k = [{
            xtype: "textfield",
            fieldLabel: lang("sortName"),
            name: "name",
            allowBlank: false
        },
            {
                xtype: "textarea",
                fieldLabel: lang("remark"),
                height: 50,
                name: "note"
            },
            {
                xtype: "multifunctionselectorfield",
                fieldLabel: lang("launchPeimiss"),
                height: 100,
                name: "faqiPermit",
                userButton: true,
                stationButton: true,
                departmentButton: true,
                userSelector: f,
                stationSelector: b,
                departmentSelector: d
            },
            {
                xtype: "box",
                autoEl: {
                    tag: "div",
                    style: "margin-left:60px;margin-top:5px;margin-bottom:15px;color:#676767;",
                    html: lang("launchPermitLong")
                }
            },
            {
                xtype: "multifunctionselectorfield",
                fieldLabel: lang("forbidPermit"),
                height: 100,
                name: "forbidFaqi",
                userButton: true,
                stationButton: true,
                departmentButton: true,
                userSelector: f,
                stationSelector: b,
                departmentSelector: d
            },
            {
                xtype: "box",
                autoEl: {
                    tag: "div",
                    style: "margin-left:60px;margin-top:5px;margin-bottom:15px;color:#676767;",
                    html: lang("forbidPermitLong")
                }
            },
            {
                xtype: "multifunctionselectorfield",
                fieldLabel: lang("accessPermit"),
                height: 100,
                name: "chayuePermit",
                userButton: true,
                stationButton: true,
                departmentButton: true,
                userSelector: f,
                stationSelector: b,
                departmentSelector: d
            },
            {
                xtype: "box",
                autoEl: {
                    tag: "div",
                    style: "margin-left:60px;margin-top:5px;margin-bottom:15px;color:#676767;",
                    html: lang("accessPermitLong")
                }
            },
            {
                xtype: "multifunctionselectorfield",
                fieldLabel: lang("permit4Mgr"),
                height: 100,
                name: "guanliPermit",
                userButton: true,
                stationButton: true,
                departmentButton: true,
                userSelector: f,
                stationSelector: b,
                departmentSelector: d
            },
            {
                xtype: "box",
                autoEl: {
                    tag: "div",
                    style: "margin-left:60px;margin-top:5px;margin-bottom:5px;color:#676767;",
                    html: lang("mgrPermitLong")
                }
            }];
        var a = new Ext.form.FormPanel({
            labelAlign: "right",
            labelWidth: 60,
            border: false,
            waitMsgTarget: true,
            autoScroll: true,
            bodyStyle: "padding:5px",
            defaults: {
                width: 380
            },
            items: k
        });
        var h = new Ext.Window({
            title: lang("addPermitType"),
            width: 500,
            height: makeWindowHeight(560),
            layout: "fit",
            modal: true,
            items: a,
            buttons: [{
                text: lang("save"),
                handler: function() {
                    j("save")
                }
            },
                {
                    text: lang("saveContinueAdd"),
                    cls: "btn-blue3",
                    handler: function() {
                        j("reset")
                    }
                },
                {
                    text: lang("close"),
                    handler: function() {
                        h.close()
                    }
                }]
        }).show();
        var j = function(m) {
            if (!a.getForm().isValid()) {
                CNOA.msg.alert(lang("fieldsIsInvald"));
                return
            }
            a.getForm().submit({
                url: g.baseUrl + "&task=submit",
                method: "POST",
                params: {
                    sortId: e
                },
                waitTitle: lang("notice"),
                waitMsg: lang("waiting"),
                success: function(n, o) {
                    if (o.result.success) {
                        CNOA.msg.notice(o.result.msg, lang("flowMgr"));
                        if (m == "save") {
                            h.close()
                        } else {
                            n.reset();
                            e = 0
                        }
                        g.getStore().reload()
                    } else {
                        CNOA.msg.alert(o.result.msg)
                    }
                },
                failure: function(n, o) {
                    CNOA.msg.alert(o.result.msg)
                }
            })
        };
        var c = function() {
            a.getForm().load({
                url: g.baseUrl + "/loadSortFormData",
                method: "POST",
                params: {
                    sortId: e
                },
                waitTitle: lang("notice"),
                failure: function(m, n) {
                    CNOA.msg.alert(n.result.msg)
                }
            })
        };
        if (e) {
            c()
        }
    },
    submit: function(a) {
        var b = this;
        Ext.Ajax.request({
            url: b.baseUrl + "/submitSortOrder",
            method: "POST",
            params: a,
            success: function(d) {
                var c = Ext.decode(d.responseText);
                if (c.success === true) {
                    CNOA.msg.notice(c.msg, lang("flowMgr"));
                    b.sortStore.reload()
                } else {
                    CNOA.msg.alert(c.msg)
                }
            }
        })
    },
    deleteSort: function(a) {
        var b = this;
        Ext.Ajax.request({
            url: this.baseUrl + "/deleteSort",
            method: "POST",
            params: {
                ids: a.join(",")
            },
            success: function(d) {
                var c = Ext.decode(d.responseText);
                if (c.success === true) {
                    CNOA.msg.notice(c.msg, lang("flowMgr"));
                    b.sortStore.reload()
                } else {
                    CNOA.msg.alert(c.msg)
                }
            }
        })
    },
    getStore: function() {
        return this.sortStore
    }
};
var CNOA_wf_set_desktopClass, CNOA_wf_set_desktop;
CNOA_wf_set_desktopClass = CNOA.Class.create();
CNOA_wf_set_desktopClass.prototype = {
    init: function() {
        var b = this;
        var a = Ext.id();
        this.baseUrl = "index.php?app=wf&func=flow&action=setdesktop";
        this.storeBar = {
            sname: ""
        };
        this.sortId = 0;
        this.fields = [{
            name: "sortId"
        },
            {
                name: "text"
            },
            {
                name: "show"
            }];
        this.store = new Ext.data.GroupingStore({
            autoLoad: true,
            proxy: new Ext.data.HttpProxy({
                url: b.baseUrl + "&task=getJsonData"
            }),
            reader: new Ext.data.JsonReader({
                totalProperty: "total",
                root: "data",
                fields: this.fields
            }),
            listeners: {
                update: function(e, c, d) {
                    var f = c.data;
                    if (d == Ext.data.Record.EDIT) {
                        b.submit(f)
                    }
                }
            }
        });
        this.pagingBar = new Ext.PagingToolbar({
            displayInfo: true,
            store: this.store,
            pageSize: 15,
            listeners: {
                beforechange: function(c, d) {
                    Ext.apply(d, b.storeBar)
                }
            }
        });
        this.sm = new Ext.grid.CheckboxSelectionModel({
            singleSelect: true
        });
        this.colModel = new Ext.grid.ColumnModel([new Ext.grid.RowNumberer(), this.sm, {
            header: "sortId",
            dataIndex: "sortId",
            hidden: true
        },
            {
                header: lang("sortName"),
                dataIndex: "text",
                width: 250,
                sortable: true,
                menuDisabled: true
            },
            {
                header: lang("whetherDisplayDtop"),
                dataIndex: "show",
                width: 100,
                sortable: true,
                menuDisabled: true,
                renderer: function(e, f, c) {
                    var d = lang("no");
                    if (e == "1") {
                        d = "<span class='cnoa_color_red'>是</span>"
                    }
                    return d
                }
            },
            {
                header: lang("opt"),
                dataIndex: "sortId",
                width: 60,
                sortable: true,
                menuDisabled: true,
                renderer: function(d, e, c) {
                    var f = c.data.text;
                    return "<a href='javascript:void(0)' class='gridview4' onclick='CNOA_wf_set_desktop.edit(" + d + ")'>" + lang("modify") + "</a>"
                }
            },
            {
                header: "",
                dataIndex: "noIndex",
                width: 1,
                menuDisabled: true,
                resizable: false
            }]);
        this.grid = new Ext.grid.GridPanel({
            region: "center",
            layout: "fit",
            border: false,
            store: b.store,
            loadMask: {
                msg: lang("waiting")
            },
            cm: this.colModel,
            sm: this.sm,
            stripeRows: true,
            hideBorders: true,
            bbar: this.pagingBar,
            view: new Ext.grid.GroupingView({
                markDirty: false
            }),
            tbar: [{
                text: lang("refresh"),
                iconCls: "icon-system-refresh",
                handler: function() {
                    b.store.reload()
                }
            },
                lang("setWhetherPengding")],
            listeners: {
                cellclick: function(d, g, c, f) {
                    if (c == 3 || c == 1 || c == 0) {
                        return false
                    }
                }
            }
        });
        this.mainPanel = new Ext.Panel({
            collapsible: false,
            hideBorders: true,
            border: false,
            layout: "border",
            autoScroll: false,
            items: this.grid
        })
    },
    edit: function(a) {
        var b = this;
        CNOA.msg.cf(lang("wherherAmendState"),
            function(c) {
                if (c == "yes") {
                    Ext.Ajax.request({
                        url: b.baseUrl + "&task=editShow",
                        method: "POST",
                        params: {
                            sortId: a
                        },
                        success: function(e) {
                            var d = Ext.decode(e.responseText);
                            if (d.success === true) {
                                CNOA.msg.notice2(d.msg);
                                b.store.reload()
                            } else {
                                CNOA.msg.alert(d.msg,
                                    function() {})
                            }
                        }
                    })
                }
            })
    }
};
var CNOA_wf_set_flow_addEditClass, CNOA_wf_set_flow_addEdit;
CNOA_wf_set_flow_addEditClass = CNOA.Class.create();
CNOA_wf_set_flow_addEditClass.prototype = {
    init: function(k, b, n, j) {
        var g = this;
        var c = Ext.id();
        this.flowId = b;
        this.flowType = n;
        this.tplSort = j;
        //this.baseUrl = "index.php?app=wf&func=flow&action=set&modul=flow";
        this.baseUrl = "workFlow";
        this.title = k == "edit" ? "修改流程": k == "add" ? "新建流程": "复制流程";
        var h = Ext.id();
        this.sortStore = new Ext.data.Store({
            autoLoad: true,
            proxy: new Ext.data.HttpProxy({
                url: this.baseUrl + "/getFlowClassify",
                disableCaching: true
            }),
            reader: new Ext.data.JsonReader({
                totalProperty: "total",
                root: "data",
                fields: [{
                    name: "sortId"
                },
                    {
                        name: "name"
                    }]
            })
        });
        this.noticePsh = true;
        this.baseInfoCmp = [{
            xtype: "panel",
            border: false,
            layout: "form",
            items: [{
                xtype: "textfield",
                fieldLabel: lang("flowName"),
                width: 310,
                allowBlank: false,
                name: "name"
            },
                {
                    xtype: "combo",
                    width: 310,
                    fieldLabel: lang("sort2"),
                    allowBlank: false,
                    name: "sortId",
                    store: this.sortStore,
                    hiddenName: "sortId",
                    valueField: "sortId",
                    displayField: "name",
                    mode: "local",
                    triggerAction: "all",
                    forceSelection: true,
                    editable: false
                },
                {
                    xtype: "compositefield",
                    border: false,
                    fieldLabel: lang("numRules") + '<span class="cnoa_color_red">*</span>',
                    items: [{
                        xtype: "textfield",
                        width: 251,
                        allowBlank: false,
                        name: "nameRule"
                    },
                        {
                            xtype: "button",
                            text: lang("ruleDescription"),
                            pressed: true,
                            enableToggle: true,
                            handler: function() {
                                g.showNotice()
                            }
                        }]
                },
                {
                    xtype: "radiogroup",
                    fieldLabel: lang("allowYouEditNum"),
                    width: 100,
                    items: [{
                        boxLabel: lang("yes"),
                        name: "nameRuleAllowEdit",
                        inputValue: "1"
                    },
                        {
                            boxLabel: lang("no"),
                            name: "nameRuleAllowEdit",
                            inputValue: "0",
                            checked: true
                        }]
                },
                {
                    xtype: "radiogroup",
                    fieldLabel: lang("customTile"),
                    width: 100,
                    items: [{
                        boxLabel: lang("yes"),
                        name: "nameDisallowBlank",
                        inputValue: "1",
                        checked: true
                    },
                        {
                            boxLabel: lang("no"),
                            name: "nameDisallowBlank",
                            inputValue: "0"
                        }]
                }]
        }];
        this.serialNumCmp = [{
            xtype: "panel",
            border: false,
            layout: "form",
            items: [{
                xtype: "compositefield",
                fieldLabel: lang("serualNumRule"),
                border: false,
                items: [{
                    xtype: "textfield",
                    width: 251,
                    allowBlank: false,
                    name: "serialNum"
                },
                    {
                        xtype: "button",
                        text: lang("ruleDescription"),
                        handler: function() {
                            g.showNotice()
                        }
                    }]
            },
                {
                    xtype: "displayfield",
                    fieldLabel: lang("currentNum")
                }]
        }];
        this.noticeInfoCmp = [{
            xtype: "panel",
            border: false,
            hideBorders: true,
            items: [{
                xtype: "panel",
                border: false,
                layout: "form",
                items: [{
                    xtype: "radiogroup",
                    fieldLabel: lang("recallNotification"),
                    columns: 2,
                    style: "background-color: #DFE8F6",
                    items: [{
                        boxLabel: lang("recallStepSponser"),
                        name: "noticeCallback",
                        inputValue: "1",
                        checked: true
                    },
                        {
                            boxLabel: lang("fQmanAndThMan"),
                            name: "noticeCallback",
                            inputValue: "2"
                        },
                        {
                            boxLabel: lang("allDoStepPeople"),
                            name: "noticeCallback",
                            inputValue: "3"
                        },
                        {
                            boxLabel: lang("notNotice"),
                            name: "noticeCallback",
                            inputValue: "4"
                        }]
                },
                    {
                        xtype: "radiogroup",
                        fieldLabel: lang("whenCeXNotice"),
                        columns: 2,
                        items: [{
                            boxLabel: lang("revokeStepOfHost"),
                            name: "noticeCancel",
                            inputValue: "1",
                            checked: true
                        },
                            {
                                boxLabel: lang("fQManAndHost"),
                                name: "noticeCancel",
                                inputValue: "2"
                            },
                            {
                                boxLabel: lang("allDoStepPeople"),
                                name: "noticeCancel",
                                inputValue: "3"
                            },
                            {
                                boxLabel: lang("notNotice"),
                                name: "noticeCancel",
                                inputValue: "4"
                            }]
                    },
                    {
                        xtype: "radiogroup",
                        fieldLabel: lang("returnWhenNotice"),
                        columns: 2,
                        style: "background-color : #DFE8F6",
                        items: [{
                            boxLabel: lang("theStepRrturnMan"),
                            name: "noticeAtGoBack",
                            inputValue: "1",
                            checked: true
                        },
                            {
                                boxLabel: lang("fQmanAndThMan"),
                                name: "noticeAtGoBack",
                                inputValue: "2"
                            },
                            {
                                boxLabel: lang("allDoStepPeople"),
                                name: "noticeAtGoBack",
                                inputValue: "3"
                            },
                            {
                                boxLabel: lang("notNotice"),
                                name: "noticeAtGoBack",
                                inputValue: "4"
                            }]
                    },
                    {
                        xtype: "radiogroup",
                        fieldLabel: lang("refusingToInform"),
                        columns: 2,
                        items: [{
                            boxLabel: lang("theStepRrturnMan"),
                            name: "noticeAtReject",
                            inputValue: "1",
                            checked: true
                        },
                            {
                                boxLabel: lang("fQmanAndThMan"),
                                name: "noticeAtReject",
                                inputValue: "2"
                            },
                            {
                                boxLabel: lang("allDoStepPeople"),
                                name: "noticeAtReject",
                                inputValue: "3"
                            },
                            {
                                boxLabel: lang("notNotice"),
                                name: "noticeAtReject",
                                inputValue: "4"
                            }]
                    },
                    {
                        xtype: "radiogroup",
                        fieldLabel: lang("noticeTermination"),
                        columns: 2,
                        style: "background-color : #DFE8F6",
                        items: [{
                            boxLabel: lang("initiator"),
                            name: "noticeAtInterrupt",
                            inputValue: "1",
                            checked: true
                        },
                            {
                                boxLabel: lang("fQManAndHost"),
                                name: "noticeAtInterrupt",
                                inputValue: "2"
                            },
                            {
                                boxLabel: lang("allDoStepPeople"),
                                name: "noticeAtInterrupt",
                                inputValue: "3"
                            },
                            {
                                boxLabel: lang("notNotice"),
                                name: "noticeAtInterrupt",
                                inputValue: "4"
                            }]
                    },
                    {
                        xtype: "radiogroup",
                        fieldLabel: lang("endOfNotification"),
                        columns: 2,
                        items: [{
                            boxLabel: lang("initiator"),
                            name: "noticeAtFinish",
                            inputValue: "1",
                            checked: true
                        },
                            {
                                boxLabel: lang("allDoStepPeople"),
                                name: "noticeAtFinish",
                                inputValue: "2"
                            },
                            {
                                boxLabel: lang("notNotice"),
                                name: "noticeAtFinish",
                                inputValue: "3"
                            }]
                    }]
            }]
        }];
        var a = Ext.id();
        var p = Ext.id();
        var o = Ext.id();
        var m = Ext.id();
        var f = Ext.id();
        var e = Ext.id();
        var d = Ext.id();
        this.baseField = [{
            border: false,
            bodyStyle: "padding: 10px;",
            items: [{
                xtype: "fieldset",
                title: lang("baseInfo"),
                width: 420,
                items: [this.baseInfoCmp]
            },
                {
                    xtype: "fieldset",
                    title: lang("template"),
                    width: 420,
                    items: [{
                        xtype: "radiogroup",
                        fieldLabel: lang("textTemp"),
                        allowBlank: false,
                        columns: 3,
                        items: [{
                            xtype: "radio",
                            id: a,
                            boxLabel: lang("form") + '<img src="/cnoa/resources/images/cnoa/ie.16x16.gif" width="18" height="18" align="texttop" />',
                            name: "tplSort",
                            checked: k == "add" ? true: false,
                            inputValue: 0
                        },
                            {
                                xtype: "radio",
                                id: p,
                                boxLabel: 'WORD<img src="/cnoa/resources/images/icons/document-word.png" width="18" height="18" align="texttop" />',
                                name: "tplSort",
                                inputValue: 1
                            },
                            {
                                xtype: "radio",
                                id: o,
                                boxLabel: 'EXCEL<img src="/cnoa/resources/images/icons/document-excel.png" width="18" height="18" align="texttop" />',
                                name: "tplSort",
                                inputValue: 2
                            },
                            {
                                xtype: "radio",
                                id: m,
                                boxLabel: lang("docClass") + '<img src="/cnoa/resources/images/cnoa/ie.16x16.gif" width="18" height="18" align="texttop" />+<img src="/cnoa/resources/images/icons/document-word.png" width="18" height="18" align="texttop" />',
                                name: "tplSort",
                                inputValue: 3
                            }],
                        listeners: {
                            change: function(s, r) {
                                var q = r.inputValue;
                                if (k == "edit" || k == "copy") {
                                    if (q == 0) {
                                        Ext.getCmp(a).setDisabled(false);
                                        Ext.getCmp(p).setDisabled(true);
                                        Ext.getCmp(o).setDisabled(true);
                                        Ext.getCmp(m).setDisabled(true)
                                    } else {
                                        if (q == 1) {
                                            Ext.getCmp(a).setDisabled(true);
                                            Ext.getCmp(p).setDisabled(false);
                                            Ext.getCmp(o).setDisabled(true);
                                            Ext.getCmp(m).setDisabled(true)
                                        } else {
                                            if (q == 2) {
                                                Ext.getCmp(a).setDisabled(true);
                                                Ext.getCmp(p).setDisabled(true);
                                                Ext.getCmp(o).setDisabled(false);
                                                Ext.getCmp(m).setDisabled(true)
                                            } else {
                                                Ext.getCmp(a).setDisabled(true);
                                                Ext.getCmp(p).setDisabled(true);
                                                Ext.getCmp(o).setDisabled(true);
                                                Ext.getCmp(m).setDisabled(false)
                                            }
                                        }
                                    }
                                }
                                if (q == 3) {
                                    Ext.getCmp(f).setDisabled(false);
                                    Ext.getCmp(e).setDisabled(true);
                                    Ext.getCmp(d).setDisabled(true)
                                } else {
                                    Ext.getCmp(f).setDisabled(false);
                                    Ext.getCmp(e).setDisabled(false);
                                    Ext.getCmp(d).setDisabled(false)
                                }
                            }
                        }
                    }]
                },
                {
                    xtype: "fieldset",
                    title: lang("type"),
                    width: 420,
                    items: [{
                        xtype: "radiogroup",
                        fieldLabel: lang("processType"),
                        allowBlank: false,
                        columns: 3,
                        items: [{
                            xtype: "radio",
                            id: f,
                            boxLabel: lang("fixedFlow"),
                            name: "flowType",
                            checked: k == "add" ? true: false,
                            inputValue: 0
                        },
                            {
                                xtype: "radio",
                                id: e,
                                boxLabel: lang("freeOrderFlow"),
                                name: "flowType",
                                inputValue: 1
                            },
                            {
                                xtype: "radio",
                                id: d,
                                boxLabel: lang("freeProcess"),
                                name: "flowType",
                                inputValue: 2
                            }],
                        listeners: {
                            change: function(s, r) {
                                if (k == "edit" || k == "copy") {
                                    var q = r.inputValue;
                                    if (q == 0) {
                                        Ext.getCmp(f).setDisabled(false);
                                        Ext.getCmp(e).setDisabled(true);
                                        Ext.getCmp(d).setDisabled(true)
                                    } else {
                                        if (q == 1) {
                                            Ext.getCmp(f).setDisabled(true);
                                            Ext.getCmp(e).setDisabled(false);
                                            Ext.getCmp(d).setDisabled(true)
                                        } else {
                                            Ext.getCmp(f).setDisabled(true);
                                            Ext.getCmp(e).setDisabled(true);
                                            Ext.getCmp(d).setDisabled(false)
                                        }
                                    }
                                }
                            }
                        }
                    }]
                },
                {
                    xtype: "fieldset",
                    title: lang("notice1"),
                    width: 420,
                    items: [this.noticeInfoCmp]
                },
                {
                    xtype: "fieldset",
                    width: 420,
                    title: lang("remark"),
                    items: [{
                        xtype: "panel",
                        autoHeight: true,
                        layout: "form",
                        border: false,
                        items: [{
                            xtype: "textarea",
                            fieldLabel: lang("remark"),
                            name: "about",
                            width: 310,
                            height: 80
                        }]
                    }]
                }]
        }];
        this.formPanel = new Ext.form.FormPanel({
            labelAlign: "right",
            labelWidth: 80,
            region: "center",
            border: false,
            waitMsgTarget: true,
            autoScroll: true,
            items: this.baseField
        });
        this.noticePanel = new Ext.Panel({
            width: 290,
            style: "border-left:1px solid #dddddd",
            border: false,
            tbar: [lang("flowNumberRule"), "->", {
                xtype: "button",
                text: lang("hide"),
                handler: function() {
                    g.noticePsh = true;
                    g.showNotice()
                }
            }],
            region: "east",
            layout: "form",
            labelWidth: 20,
            bodyStyle: "padding: 10px;",
            defaults: {
                xtype: "displayfield",
                style: "cursor: pointer;"
            },
            items: [{
                fieldLabel: "{F}",
                value: lang("biaoShiCurrentFlow"),
                listeners: {
                    render: function(q) {
                        g.initField(q, "{F}")
                    }
                }
            },
                {
                    fieldLabel: "{U}",
                    value: lang("biaoshiCurrentFQ"),
                    listeners: {
                        render: function(q) {
                            g.initField(q, "{U}")
                        }
                    }
                },
                {
                    fieldLabel: "{Y}",
                    value: lang("biaoshiCurrentTime"),
                    listeners: {
                        render: function(q) {
                            g.initField(q, "{Y}")
                        }
                    }
                },
                {
                    fieldLabel: "{M}",
                    value: lang("biaoshiCurrentMonth"),
                    listeners: {
                        render: function(q) {
                            g.initField(q, "{M}")
                        }
                    }
                },
                {
                    fieldLabel: "{D}",
                    value: lang("biaoshiCurrentDays"),
                    listeners: {
                        render: function(q) {
                            g.initField(q, "{D}")
                        }
                    }
                },
                {
                    fieldLabel: "{H}",
                    value: lang("biaoshiCurrentHour"),
                    listeners: {
                        render: function(q) {
                            g.initField(q, "{H}")
                        }
                    }
                },
                {
                    fieldLabel: "{I}",
                    value: lang("biaoshiCurrentMinutes"),
                    listeners: {
                        render: function(q) {
                            g.initField(q, "{I}")
                        }
                    }
                },
                {
                    fieldLabel: "{S}",
                    value: lang("biaoshiCurrentSecond"),
                    listeners: {
                        render: function(q) {
                            g.initField(q, "{S}")
                        }
                    }
                },
                {
                    fieldLabel: "{N}",
                    value: lang("biaoshiSystemZDNum") + "<br>{N}" + lang("oneFlowNum") + "1。<br>{NNNNN}" + lang("saidFiveFlowNum"),
                    listeners: {
                        render: function(q) {
                            g.initField(q, "{N}")
                        }
                    }
                },
                {
                    fieldLabel: lang("eg"),
                    value: lang("eg") + "：{F}{Y}{M}{D}-{NNNN}，" + lang("willBeConver") + "：<br><span class='cnoa_color_blue'>" + lang("flowName") + "20080808-0008</span>",
                    style: "cursor: normal;",
                    hideLabel: true
                },
                {
                    fieldLabel: "",
                    value: "<span class='cnoa_color_red'>" + lang("cilckFillMoreClick") + "</span>",
                    style: "cursor: normal;",
                    hideLabel: true
                }]
        });
        this.mainPanel = new Ext.Window({
            title: this.title,
            width: 760,
            height: Ext.getBody().getHeight() - 50,
            layout: "border",
            modal: true,
            resizable: false,
            items: [this.formPanel, this.noticePanel],
            buttons: [{
                text: lang("save"),
                handler: function() {
                    g.submit("save", k)
                }
            },
                {
                    text: lang("saveAndAddnew"),
                    cls: "btn-blue4",
                    hidden: k == "copy" ? true: false,
                    handler: function() {
                        g.submit("reset", k)
                    }
                },
                {
                    text: lang("close"),
                    handler: function() {
                        g.mainPanel.close()
                    }
                }]
        }).show();
        if (k == "edit" || k == "copy") {
            this.loadForm(k)
        }
    },
    initField: function(a, b) {
        var c = this;
        a.itemCt.setStyle("backgroundColor", "#f0f0f0");
        a.getEl().on("click",
            function() {
                c.insert(b)
            });
        a.getEl().on("mouseover",
            function() {
                a.itemCt.setStyle("backgroundColor", "#ffa87d")
            });
        a.getEl().on("mouseout",
            function() {
                a.itemCt.setStyle("backgroundColor", "#f0f0f0")
            })
    },
    insert: function(c) {
        var d = this.formPanel.getForm().findField("nameRule");
        var b = d.getValue();
        if (/\{N+\}/.test(b) && c == "{N}") {
            var a = b.replace(/(.*)({N+})(.*)/, "$2");
            b = b.replace(a, a.replace("{N", "{NN"));
            d.setValue(b);
            return
        }
        d.setValue(d.getValue() + "" + c)
    },
    showNotice: function() {
        if (this.noticePsh) {
            this.noticePsh = false;
            this.mainPanel.setWidth(470);
            this.noticePanel.hide()
        } else {
            this.noticePsh = true;
            this.mainPanel.setWidth(760);
            this.noticePanel.show()
        }
    },
    submit: function(a, d) {
        var h = this;
        var c = h.formPanel.getForm(),
            g = c.findField("nameRule").getValue(),
            e = true;
        var b = function() {
            var f = h.baseUrl + "/submit";
            if (d == "add") {
                flowId = 0
            } else {
                if (d == "edit") {} else {
                    if (d == "copy") {
                        if (h.flowType == 0 && h.tplSort == 0) {
                            f = h.baseUrl + "&task=clone"
                        } else {
                            f = h.baseUrl + "&task=cloneFreeFlow"
                        }
                    } else {
                        return
                    }
                }
            }
            if (c.isValid()) {
                c.submit({
                    url: f,
                    waitTitle: lang("notice"),
                    method: "POST",
                    params: {
                        flowId: h.flowId,
                        type: d
                        //flowType: h.flowType,
                        //tplSort: h.tplSort
                    },
                    waitMsg: lang("waiting"),
                    success: function(j, k) {
                        CNOA.msg.notice(k.result.msg, lang("flowMgr"));
                        if (a == "save") {
                            h.mainPanel.close()
                        } else {
                            if (a == "reset") {
                                c.reset();
                                h.flowId = 0
                            }
                        }
                        CNOA_wf_set_flow.store.reload()
                    }.createDelegate(this),
                    failure: function(j, k) {
                        CNOA.msg.alert(k.result.msg,
                            function() {}.createDelegate(this))
                    }.createDelegate(this)
                })
            } else {
                CNOA.msg.alert(lang("notFinish"));
                return
            }
        };
        if (/\{[N]{1,2}\}/g.test(g)) {
            CNOA.msg.cf(lang("flowNumLessThan2"),
                function(f) {
                    if (f == "yes") {
                        b()
                    }
                })
        } else {
            b()
        }
    },
    loadForm: function(a) {
        var b = this;
        this.formPanel.getForm().load({
            url: b.baseUrl + "/loadFlowData",
            method: "POST",
            params: {
                flowId: b.flowId
            },
            waitTitle: lang("notice"),
            success: function(c, e) {
                var d = e.result.data;
                if (a == "copy") {
                    b.formPanel.getForm().findField("name").setValue(d.name + "--副本")
                }
            },
            failure: function(c, d) {
                CNOA.msg.alert(d.result.msg,
                    function() {})
            }
        })
    }
};
var CNOA_wf_set_flowClass, CNOA_wf_set_flow;
CNOA_wf_set_flowClass = CNOA.Class.create();
CNOA_wf_set_flowClass.prototype = {
    init: function() {
        var b = this;
        var a = Ext.id();
        this.baseUrl = "workFlow";
        this.storeBar = {
            sname: "",
            sortId: 0
        };
        this.fields = [{
            name: "flowId"
        },
            {
                name: "name"
            },
            {
                name: "sort"
            },
            {
                name: "status"
            },
            {
                name: "flowType"
            },
            {
                name: "tplSort"
            },
            {
                name: "pageSet"
            }];
        this.store = new Ext.data.Store({
            autoLoad: true,
            proxy: new Ext.data.HttpProxy({
                url: b.baseUrl + "/getFlowData"
            }),
            reader: new Ext.data.JsonReader({
                totalProperty: "total",
                root: "data",
                fields: this.fields
            }),
            listeners: {}
        });
        this.pagingBar = new Ext.PagingToolbar({
            displayInfo: true,
            store: this.store,
            pageSize: 15,
            listeners: {
                beforechange: function(c, d) {
                    Ext.apply(d, b.storeBar)
                }
            }
        });
        this.sm = new Ext.grid.CheckboxSelectionModel({
            singleSelect: false
        });
        this.colModel = new Ext.grid.ColumnModel([new Ext.grid.RowNumberer(), {
            header: "flowId",
            dataIndex: "flowId",
            hidden: true
        },
            {
                header: lang("type"),
                dataIndex: "tplSort",
                width: 35,
                sortable: true,
                menuDisabled: true,
                renderer: this.tplContnetShow.createDelegate(this)
            },
            {
                header: lang("opt"),
                dataIndex: "flowId",
                width: 385,
                sortable: true,
                menuDisabled: true,
                renderer: this.operate.createDelegate(this)
            },
            {
                header: lang("flowName") + " / " + lang("sort2"),
                dataIndex: "name",
                id: "name",
                width: 300,
                sortable: true,
                menuDisabled: true,
                renderer: this.formatName.createDelegate(this)
            },
            {
                header: lang("status"),
                dataIndex: "status",
                width: 50,
                sortable: true,
                menuDisabled: true,
                renderer: this.statusCheck.createDelegate(this)
            },
            {
                header: "",
                dataIndex: "noIndex",
                width: 1,
                menuDisabled: true,
                resizable: false
            }]);
        this.grid = new Ext.grid.GridPanel({
            bodyStyle: "border-left-width:1px;",
            region: "center",
            layout: "fit",
            border: false,
            autoExpandColumn: "name",
            store: b.store,
            loadMask: {
                msg: lang("waiting")
            },
            cm: this.colModel,
            sm: this.sm,
            stripeRows: true,
            hideBorders: true,
            bbar: this.pagingBar,
            tbar: [{
                text: lang("refresh"),
                iconCls: "icon-system-refresh",
                handler: function() {
                    b.store.reload()
                }
            },
                {
                    text: lang("new"),
                    iconCls: "icon-utils-s-add",
                    cls: "btn-blue4",
                    handler: function() {
                        var c = "add";
                        b.addWin()
                    }
                },
                {
                    text: lang("import2"),
                    cls: "btn-blue3",
                    iconCls: "document-excel-import",
                    handler: function() {
                        b.importWin()
                    }
                },
                {
                    text: lang("order"),
                    tooltip: lang("order"),
                    cls: "btn-yellow1",
                    iconCls: "icon-order",
                    handler: function(c, d) {
                        b.showOrderWin()
                    }
                },
                {
                    text: "检查流程",
                    cls: "btn-blue4",
                    handler: function() {
                        b.checkStepCondition()
                    }
                },
                "->", (lang("flowName") + ":"), {
                    xtype: "textfield",
                    width: 200,
                    id: a,
                    listeners: {
                        specialkey: function(d, c) {
                            if (c.getKey() == c.ENTER) {
                                b.storeBar.sname = Ext.getCmp(a).getValue();
                                b.store.load({
                                    params: b.storeBar
                                })
                            }
                        }
                    }
                },
                {
                    xtype: "button",
                    text: lang("search"),
                    handler: function() {
                        b.storeBar.sname = Ext.getCmp(a).getValue();
                        b.store.load({
                            params: b.storeBar
                        })
                    }
                },
                {
                    xtype: "button",
                    text: lang("clear"),
                    handler: function() {
                        Ext.getCmp(a).setValue("");
                        b.storeBar.sname = "";
                        b.store.load({
                            params: b.storeBar
                        })
                    }
                }]
        });
        this.treeRoot = new Ext.tree.AsyncTreeNode({
            text: lang("allSort"),
            sortId: "0",
            iconCls: "icon-tree-root-cnoa",
            cls: "feeds-node",
            expanded: true
        });
        this.treeLoader = new Ext.tree.TreeLoader({
            dataUrl: this.baseUrl + "/getSortTree",
            preloadChildren: true,
            clearOnLoad: false
        });
        this.tree = new Ext.tree.TreePanel({
            region: "west",
            layout: "fit",
            width: 180,
            minWidth: 80,
            maxWidth: 380,
            hideBorders: true,
            border: false,
            rootVisible: true,
            split: true,
            bodyStyle: "border-right-width:1px;",
            lines: true,
            animCollapse: false,
            animate: false,
            loader: this.treeLoader,
            root: this.treeRoot,
            autoScroll: true,
            listeners: {
                click: function(c) {
                    b.storeBar.sortId = c.attributes.sortId;
                    b.store.load({
                        params: b.storeBar
                    })
                }
            },
            tbar: [{
                text: lang("refresh"),
                iconCls: "icon-system-refresh",
                handler: function() {
                    b.treeRoot.reload()
                }
            },
                "->", lang("flowCategoryList") + ": &nbsp;"]
        });
        this.mainPanel = new Ext.Panel({
            collapsible: false,
            hideBorders: true,
            border: false,
            layout: "border",
            autoScroll: false,
            items: [this.tree, this.grid]
        })
    },
    showOrderWin: function() {
        var a = this.tree.getSelectionModel().getSelectedNode();
        if (!this.orderWindow) {
            this.orderWindow = this.createOrderWindow()
        }
        this.orderWindow.getStore().load({
            params: {
                sortId: a.attributes.sortId,
                widthSon: "false"
            }
        });
        this.orderWindow.setTitle(lang("userOrder") + " - " + lang("department") + ": " + a.text);
        this.orderWindow.show()
    },
    createOrderWindow: function() {
        var g = this;
        var a = [{
            name: "flowId"
        },
            {
                name: "name"
            },
            {
                name: "sort"
            },
            {
                name: "status"
            },
            {
                name: "flowType"
            },
            {
                name: "tplSort"
            },
            {
                name: "pageSet"
            }];
        var c = new Ext.data.Store({
            proxy: new Ext.data.HttpProxy({
                url: g.baseUrl + "&task=getOrderList"
            }),
            reader: new Ext.data.JsonReader({
                totalProperty: "total",
                root: "data",
                fields: a
            })
        });
        var b = new Ext.grid.ColumnModel({
            defaults: {
                sortable: false
            },
            columns: [new Ext.grid.RowNumberer(), {
                header: "flowId",
                dataIndex: "flowId",
                hidden: true
            },
                {
                    header: lang("type"),
                    dataIndex: "tplSort",
                    width: 35,
                    sortable: true,
                    menuDisabled: true,
                    renderer: this.tplContnetShow.createDelegate(this)
                },
                {
                    header: lang("opt"),
                    dataIndex: "flowId",
                    width: 315,
                    sortable: true,
                    menuDisabled: true,
                    renderer: this.operate.createDelegate(this)
                },
                {
                    header: lang("flowName") + " / " + lang("sort2"),
                    dataIndex: "name",
                    id: "name",
                    width: 300,
                    sortable: true,
                    menuDisabled: true,
                    renderer: this.formatName.createDelegate(this)
                },
                {
                    header: lang("status"),
                    dataIndex: "status",
                    width: 50,
                    sortable: true,
                    menuDisabled: true,
                    renderer: this.statusCheck.createDelegate(this)
                },
                {
                    header: "",
                    dataIndex: "noIndex",
                    width: 1,
                    menuDisabled: true,
                    resizable: false
                }]
        });
        var d = new Ext.grid.GridPanel({
            stripeRows: true,
            loadMask: {
                msg: lang("waiting")
            },
            cm: b,
            store: c,
            hideBorders: true,
            border: false,
            enableDragDrop: true,
            dropConfig: {
                appendOnly: true
            },
            ddGroup: "GridDD",
            listeners: {
                afterrender: function(j) {
                    var h = new Ext.dd.DropTarget(j.getEl(), {
                        ddGroup: "GridDD",
                        copy: false,
                        notifyDrop: function(k, q, p) {
                            var o = p.selections;
                            var m = k.getDragData(q).rowIndex;
                            if (typeof(m) == "undefined") {
                                return
                            }
                            for (i = 0; i < o.length; i++) {
                                var n = o[i];
                                if (!this.copy) {
                                    c.remove(n)
                                }
                                if (m == 0) {
                                    n.data.orderNum -= 1
                                } else {
                                    if (m == c.data.items.length) {
                                        n.data.id = c.data.items[m - 1].data.id + 1
                                    } else {
                                        n.data.id = (c.data.items[m - 1].data.id + c.data.items[m].data.id) / 2
                                    }
                                }
                                c.insert(m, n)
                            }
                        }
                    })
                }
            },
            tbar: new Ext.Toolbar({
                items: [{
                    text: lang("refresh"),
                    tooltip: lang("refresh"),
                    iconCls: "icon-system-refresh",
                    handler: function(h, j) {
                        c.reload()
                    }
                }]
            })
        });
        var e = function() {
            var h = new Array();
            Ext.each(c.data.items,
                function(j) {
                    h.push(j.data.flowId)
                });
            Ext.Ajax.request({
                method: "POST",
                url: g.baseUrl + "&task=getOrderForm",
                params: {
                    order: h.join(",")
                },
                success: function(m, k) {
                    var j = Ext.decode(m.responseText);
                    if (j.success === true) {
                        CNOA.msg.notice2(j.msg);
                        g.reloadUser();
                        f.hide()
                    } else {
                        CNOA.msg.alert(j.msg)
                    }
                }
            })
        };
        var f = new Ext.Window({
            width: 780,
            height: makeWindowHeight(500),
            border: false,
            modal: true,
            layout: "fit",
            maximizable: true,
            closeAction: "hide",
            items: d,
            buttons: [{
                text: lang("save"),
                iconCls: "icon-btn-save",
                handler: function() {
                    e()
                }
            },
                {
                    text: lang("close"),
                    iconCls: "icon-dialog-cancel",
                    handler: function() {
                        f.hide()
                    }
                }]
        });
        f.getStore = function() {
            return c
        };
        return f
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
    },
    formatName: function(b, c, a) {
        if (a.data.sort == "") {
            return "<span >" + b + "</span><br /><span>&nbsp;</span>"
        } else {
            return "<span >" + b + "</span><br /><span class='cnoa_color_gray'>" + a.data.sort + "</span>"
        }
    },
    operate: function(h, f, e) {
        var m = e.data,
            d = m.status,
            j = m.flowType,
            g = m.tplSort,
            a = m.name;
        if (j == 0) {
            var k = Ext.decode(m.pageSet);
            var b = "<a href='javascript:void(0)' class='gridview' onclick='CNOA_wf_set_flow.makeForm(" + m.flowId + ", " + g + ', "' + a + "\")'>" + lang("formDesign") + "</a>";
            b += "<a href='javascript:void(0)' class='gridview3 jianju' onclick='CNOA_wf_set_flow.design(" + h + ", " + g + ', "' + a + "\")'>" + lang("designFlow") + "</a>"
        } else {
            b = ""
        }
        if (d == 1) {
            b += "<a href='javascript:void(0)' class='gridview2 jianju' onclick='CNOA_wf_set_flow.status(\"stop\", " + h + ", " + g + ", " + j + ")'>" + lang("disable") + "</a>"
        } else {
            b += "<a href='javascript:void(0)' class='gridview jianju' onclick='CNOA_wf_set_flow.status(\"use\", " + h + ", " + g + ", " + j + ")'>" + lang("enable") + "</a>"
        }
        b += "<a href='javascript:void(0)' class='gridview4 jianju' onclick='CNOA_wf_set_flow.edit(" + m.flowId + ")'>" + lang("modify") + "</a>";
        b += "<a href='javascript:void(0)' class='gridview3 jianju' onclick='CNOA_wf_set_flow.copy(" + m.flowId + ", " + j + ", " + g + ")'>" + lang("copy") + "</a>";
        b += "<a href='javascript:void(0)' class='gridview jianju' onclick='CNOA_wf_set_flow.exportFlow(" + h + ")'>" + lang("export2") + "</a>";
        b += "<a href='javascript:void(0)' class='gridview2 jianju' onclick='CNOA_wf_set_flow.note(" + h + ", " + j + ", " + g + ")'>" + lang("del") + "</a>";
        return b
    },
    statusCheck: function(b, c, a) {
        if (b == 1) {
            return "<span class=cnoa_color_green>" + lang("enable") + "</span>"
        } else {
            return "<span class=cnoa_color_red>" + lang("disable") + "</span>"
        }
    },
    tplContnetShow: function(d, f, a) {
        var e = this;
        var b = a.data;
        if (b.tplSort == 0) {
            return '<img src="/cnoa/resources/images/icons/document-html.png" width="16" height="16" ext:qtip="' + lang("appFlowWebForm") + '" />'
        } else {
            if (b.tplSort == 1) {
                return '<img src="/cnoa/resources/images/icons/document-word.png" width="16" height="16" ext:qtip="' + lang("baseWordDoc") + '" />'
            } else {
                if (b.tplSort == 2) {
                    return '<img src="/cnoa/resources/images/icons/document-excel.png" width="16" height="16" ext:qtip="' + lang("baseOnExcelDoc") + '" />'
                } else {
                    return '<img src="/cnoa/resources/images/icons/document-html-word.png" width="16" height="16" ext:qtip="' + lang("jieheWordForm") + '" />'
                }
            }
        }
    },
    makeForm: function(c, g, b) {
        if (g == 0) {
            CNOA_wf_set_flow_makeForm = new CNOA_wf_set_flow_makeFormClass(c, b);
            CNOA_wf_set_flow_makeForm.mainPanel
        } else {
            if (g == 1 || g == 2) {
                var f = g == 1 ? "Word表单设计": "Excel表单设计";
                var a = "index.php?app=wf&func=flow&action=set&modul=flow&flowId=" + c + "&tplSort=" + g + "&" + getSessionStr();
                var e = a + "&task=ms_loadTemplateFile&flowId=" + c;
                var h = a + "&task=ms_submitMsOfficeData&type=" + g;
                if (g == 1) {
                    openOfficeForEdit_Attach(e, "doc", f, 0, h, "flowId=" + c + "&c=d", false)
                }
                if (g == 2) {
                    openOfficeForEdit_Attach(e, "xls", f, 0, h, "flowId=" + c + "&c=d", false)
                }
            } else {
                var d = new Ext.Window({
                    width: 320,
                    height: 135,
                    maximizable: false,
                    resizable: false,
                    modal: true,
                    bodyStyle: "background-color:#FFF;padding:5px;",
                    title: lang("designFormEditContent"),
                    layout: "column",
                    defaults: {
                        scale: "medium",
                        xtype: "button"
                    },
                    items: [{
                        text: lang("editAppSingle"),
                        iconCls: "document-html",
                        style: "width: auto;",
                        handler: function() {
                            CNOA_wf_set_flow_makeForm = new CNOA_wf_set_flow_makeFormClass(c, b);
                            CNOA_wf_set_flow_makeForm.mainPanel
                        }
                    },
                        {
                            text: lang("editWordTextTemp"),
                            iconCls: "document-word",
                            style: "width: auto; margin-left:5px;",
                            handler: function() {
                                var m = lang("wordFormDesign");
                                var j = "index.php?app=wf&func=flow&action=set&modul=flow&flowId=" + c + "&tplSort=" + g + "&" + getSessionStr();
                                var k = j + "&task=ms_loadTemplateFile&flowId=" + c;
                                var n = j + "&task=ms_submitMsOfficeData&type=" + g;
                                openOfficeForEdit_Attach(k, "doc", m, 0, n, "flowId=" + c + "&c=d", true)
                            }
                        }],
                    buttons: [{
                        text: lang("close"),
                        handler: function() {
                            d.close()
                        }
                    }]
                }).show()
            }
        }
    },
    design: function(b, c, a) {
        CNOA_wf_set_flow_design_main = new CNOA_wf_set_flow_design_mainClass(b, c, a)
    },
    status: function(c, b, e, a) {
        var f = this;
        if (c == "stop") {
            var d = lang("disable")
        } else {
            var d = lang("enable")
        }
        CNOA.msg.cf(lang("sureWantEditTo") + d,
            function(g) {
                if (g == "yes") {
                    Ext.Ajax.request({
                        url: f.baseUrl + "/changeStatus",
                        method: "POST",
                        params: {
                            flowId: b,
                            type: c,
                            tplSort: e,
                            flowType: a
                        },
                        success: function(j) {
                            var h = Ext.decode(j.responseText);
                            if (h.success === true) {
                                f.store.reload()
                            } else {
                                CNOA.msg.alert(h.msg,
                                    function() {})
                            }
                        }
                    })
                }
            })
    },
    edit: function(a) {
        CNOA_wf_set_flow_addEdit = new CNOA_wf_set_flow_addEditClass("edit", a)
    },
    addWin: function() {
        CNOA_wf_set_flow_addEdit = new CNOA_wf_set_flow_addEditClass("add")
    },
    copy: function(b, a, c) {
        CNOA_wf_set_flow_addEdit = new CNOA_wf_set_flow_addEditClass("copy", b, a, c)
    },
    note: function(a, m, j) {
        var g = this;
        var e = Ext.id();
        var h = Ext.id();
        var b = Ext.id();
        var f = function(n) {
            CNOA.msg.cf(lang("wantDelThisFlow"),
                function(o) {
                    if (o == "yes") {
                        Ext.Ajax.request({
                            url: g.baseUrl + "&task=delete",
                            method: "POST",
                            params: {
                                flowId: a,
                                checked: n,
                                flowType: m,
                                tplSort: j
                            },
                            success: function(q) {
                                var p = Ext.decode(q.responseText);
                                if (p.success === true) {
                                    CNOA.msg.notice(p.msg, lang("flowDel"));
                                    g.store.reload();
                                    c.close()
                                } else {
                                    CNOA.msg.alert(p.msg)
                                }
                            }
                        })
                    }
                })
        };
        var k = function() {
            Ext.Ajax.request({
                url: g.baseUrl + "&task=loadFlowTotal",
                method: "POST",
                params: {
                    flowId: a
                },
                success: function(o) {
                    var n = Ext.decode(o.responseText);
                    if (n.success == true) {
                        Ext.fly(e).update("--- 办理中的流程(<span style='color:red'>" + n.doing + "</span>)");
                        Ext.fly(h).update("--- 已办理的流程(<span style='color:red'>" + n.done + "</span>)")
                    } else {
                        CNOA.msg.alert(n.msg,
                            function() {})
                    }
                }
            })
        };
        var d = new Ext.form.FormPanel({
            border: false,
            waitMsgTarget: true,
            labelWidth: 60,
            bodyStyle: "padding: 15px;",
            listeners: {
                afterrender: function() {
                    k()
                }
            },
            items: [{
                xtype: "fieldset",
                title: lang("flowStatistics"),
                width: 360,
                layout: "form",
                items: [{
                    xtype: "displayfield",
                    hideLabel: true,
                    id: e,
                    style: "margin-left:10px;",
                    value: ""
                },
                    {
                        xtype: "displayfield",
                        hideLabel: true,
                        id: h,
                        style: "margin-left:10px;",
                        value: ""
                    }]
            },
                {
                    xtype: "panel",
                    border: false,
                    items: [{
                        xtype: "checkbox",
                        id: b,
                        name: "checked",
                        boxLabel: lang("delAllRunFlow")
                    }]
                },
                {
                    xtype: "displayfield",
                    hideLabel: true,
                    value: '<span class="cnoa_color_red">' + lang("selectionThisFlow") + "</span>"
                }]
        });
        var c = new Ext.Window({
            layout: "fit",
            resizable: false,
            modal: true,
            height: 260,
            width: 400,
            title: lang("flowDel"),
            items: d,
            buttons: [{
                text: lang("ok"),
                iconCls: "icon-dialog-apply",
                handler: function() {
                    var n = Ext.getCmp(b).getValue();
                    f(n)
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
    },
    importWin: function() {
        var e = this;
        var c = new Ext.data.Store({
            autoLoad: true,
            proxy: new Ext.data.HttpProxy({
                url: this.baseUrl + "&task=getSortStore",
                disableCaching: true
            }),
            reader: new Ext.data.JsonReader({
                totalProperty: "total",
                root: "data",
                fields: [{
                    name: "sortId"
                },
                    {
                        name: "name"
                    }]
            })
        });
        var a = new Ext.form.FormPanel({
            border: false,
            fileUpload: true,
            bodyStyle: "padding:10px;",
            waitMsgTarget: true,
            labelWidth: 110,
            items: [{
                xtype: "combo",
                width: 300,
                fieldLabel: lang("sort2"),
                allowBlank: false,
                name: "sortId",
                store: c,
                hiddenName: "sortId",
                valueField: "sortId",
                displayField: "name",
                mode: "local",
                triggerAction: "all",
                forceSelection: true,
                editable: false
            },
                {
                    xtype: "fileuploadfield",
                    name: "data",
                    allowBlank: false,
                    fieldLabel: lang("pleaseSelectFlowData"),
                    buttonCfg: {
                        text: lang("browse")
                    },
                    width: 300
                },
                {
                    xtype: "displayfield",
                    hideLabel: true,
                    value: lang("notePleaseThisSystem")
                }]
        });
        var b = function() {
            if (a.getForm().isValid()) {
                a.getForm().submit({
                    url: e.baseUrl + "&task=importFlow",
                    waitMsg: lang("waiting"),
                    params: {},
                    success: function(f, g) {
                        CNOA.msg.alert(g.result.msg,
                            function() {
                                d.close();
                                e.store.reload()
                            })
                    },
                    failure: function(f, g) {
                        CNOA.msg.alert(g.result.msg,
                            function() {})
                    }
                })
            }
        };
        var d = new Ext.Window({
            width: 460,
            height: 180,
            title: lang("importProcess"),
            resizable: false,
            modal: true,
            layout: "fit",
            items: a,
            buttons: [{
                text: lang("import2"),
                cls: "btn-blue3",
                handler: function() {
                    b()
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
    exportFlow: function(a) {
        var c = this;
        var b = function() {
            Ext.Ajax.request({
                url: c.baseUrl + "&task=exportFlow",
                method: "POST",
                params: {
                    flowId: a
                },
                success: function(e) {
                    var d = Ext.decode(e.responseText);
                    if (d.success == true) {
                        ajaxDownload(c.baseUrl + "&task=exportFlowDownload")
                    } else {
                        CNOA.msg.alert(d.msg,
                            function() {})
                    }
                }
            })
        };
        b()
    },
    checkStepCondition: function() {
        var a = this;
        Ext.Ajax.request({
            method: "POST",
            url: a.baseUrl + "&task=checkStepCondition",
            success: function(d, c) {
                var b = Ext.decode(d.responseText);
                if (b.success === true) {
                    CNOA.msg.notice2(b.msg)
                } else {
                    CNOA.msg.alert(b.msg)
                }
            }
        })
    }
};
var CNOA_wf_set_flow_makeFormClass, CNOA_wf_set_flow_makeForm;
CNOA_wf_set_flow_makeFormClass = CNOA.Class.create();
CNOA_wf_set_flow_makeFormClass.prototype = {
    init: function(e, c) {
        var j = this;
        this.flowId = e;
        this.baseUrl = "workFlow";
        var d = this.baseUrl + "/formDesigner?flowId=" + this.flowId;
        var g = Ext.getBody().getBox();
        var b = g.width - 10;
        var f = g.height - 10;
        this.ID_iframe = Ext.id();
        this.ID_saveForm = Ext.id();
        this.ID_closeForm = Ext.id();
        this.ID_closeWin = Ext.id();
        this.editorLoaded = function() {
            this.mainPanel.getEl().unmask();
            for (var h = 0; h < this.mainPanel.buttons.length; h++) {
                this.mainPanel.buttons[h].enable()
            }
        };
        var a = false;
        this.mainPanel = new Ext.Window({
            title: lang("smartDesigner") + " - 《" + c + "》",
            width: b,
            height: f,
            layout: "fit",
            modal: true,
            maximizable: true,
            shadow: false,
            resizable: true,
            html: '<iframe scrolling="auto" frameborder="0" width="100%" height="100%" id="' + j.ID_iframe + '"></iframe>',
            buttons: [{
                text: lang("save"),
                iconCls: "icon-btn-save",
                disabled: true,
                cls: "btn-blue4",
                id: j.ID_saveForm,
                handler: function() {
                    j.submit({
                        close: false
                    })
                }
            },
                {
                    text: lang("saveAndClose"),
                    iconCls: "icon-btn-save",
                    cls: "btn-blue4",
                    disabled: true,
                    id: j.ID_closeForm,
                    handler: function() {
                        a = true;
                        j.submit({
                            close: true
                        })
                    }
                },
                {
                    text: lang("close"),
                    iconCls: "icon-dialog-cancel",
                    cls: "btn-red1",
                    id: j.ID_closeWin,
                    handler: function() {
                        j.mainPanel.close()
                    }
                }],
            listeners: {
                show: function(h) {
                    Ext.getDom(j.ID_iframe).contentWindow.location.href = d + "&r=" + Math.random();
                    h.getEl().mask(lang("waiting"))
                },
                beforeclose: function() {
                    if (a) {
                        return
                    }
                    CNOA.msg.cf(lang("sureWantClose"),
                        function(h) {
                            if (h == "yes") {
                                a = true;
                                j.mainPanel.close()
                            }
                        });
                    return false
                },
                close: function() {
                    try {
                        Ext.getDom(j.ID_iframe).src = ""
                    } catch(h) {}
                }
            }
        }).show()
    },
    submit: function(a) {
        var e = this;
        var c = Ext.getDom(e.ID_iframe).contentWindow.editor.getContent();
        var b = Ext.getDom(e.ID_iframe).contentWindow.editor.getContentTxt();
        if (!c) {
            CNOA.msg.alert(lang("formWithoutAny"));
            return
        }
        e.mainPanel.getEl().mask(lang("waiting"));
        var d = Ext.encode(Ext.getDom(e.ID_iframe).contentWindow.editorConfig.pageset);
        Ext.Ajax.request({
            url: e.baseUrl + "&task=saveFormDesignData",
            method: "POST",
            params: {
                formHtml: c,
                flowId: e.flowId,
                pageset: d
            },
            success: function(g) {
                var f = Ext.decode(g.responseText);
                if (f.success === true) {
                    CNOA.msg.notice(f.msg, lang("flowMgr"));
                    if (a.close) {
                        e.mainPanel.close()
                    }
                } else {
                    CNOA.msg.alert(f.msg,
                        function() {})
                }
                e.mainPanel.getEl().unmask()
            }
        })
    },
    makeOrderMobileList: function() {
        var n = this;
        this.delOrderStore = function(u) {
            var h = d.getAt(u);
            $("#wfFormHtmlForOrder input[fieldname=" + h.get("fieldname") + "]").val(h.get("fieldname")).removeClass("formatFormItemForOrder2").removeClass("formatFormItemForOrder3");
            var t = [];
            d.removeAt(u);
            d.each(function(w, x) {
                t.push({
                    fieldid: w.get("fieldid"),
                    fieldname: w.get("fieldname")
                })
            });
            d.removeAll();
            $.each(t,
                function(x, w) {
                    d.add(new e({
                        fieldid: w.fieldid,
                        fieldname: w.fieldname
                    }))
                });
            j()
        };
        var e = Ext.data.Record.create([{
            name: "fieldid",
            type: "string"
        },
            {
                name: "fieldname",
                type: "string"
            }]);
        var c = function(h) {
            Ext.Ajax.request({
                url: n.baseUrl + "&task=loadFormHtmlForOrder",
                method: "POST",
                params: {
                    flowId: n.flowId,
                    parseForOrder: 1
                },
                success: function(u) {
                    var t = Ext.decode(u.responseText);
                    if (t.success === true) {
                        h.body.update(t.data.formHtml);
                        $("#wfFormHtmlForOrder").bind("mouseover",
                            function() {
                                $("#wfFormHtmlForOrder input[fieldname]").removeClass("formatFormItemForOrder2")
                            });
                        $("#wfFormHtmlForOrder input[fieldname]").each(function(x, w) {
                            Ext.dd.Registry.register(w)
                        });
                        new Ext.dd.DragZone("wfFormHtmlForOrder", {
                            ddGroup: "GridDD"
                        })
                    } else {
                        CNOA.msg.alert(t.msg,
                            function() {})
                    }
                }
            })
        };
        var k = [{
            name: "fieldid",
            mapping: "fieldid"
        },
            {
                name: "fieldname",
                mapping: "fieldname"
            }];
        var d = new Ext.data.Store({
            proxy: new Ext.data.HttpProxy({
                url: n.baseUrl + "&task=loadFormItemsForOrder"
            }),
            reader: new Ext.data.JsonReader({
                totalProperty: "total",
                root: "data",
                fields: k
            })
        });
        var j = function() {
            d.each(function(h, w) {
                var u = h.get("fieldname");
                var t = $("#wfFormHtmlForOrder input[fieldname=" + u + "]");
                t.val("(" + (w + 1) + ")" + u);
                t.addClass("formatFormItemForOrder3")
            })
        };
        var b = function(u, t, h, v) {
            return '<img src="resources/images/icons/cross.gif" onclick="CNOA_wf_set_flow_makeForm.delOrderStore(' + v + ')" />'
        };
        var s = function(h) {
            $("#wfFormHtmlForOrder input[fieldname]").removeClass("formatFormItemForOrder2");
            $("#wfFormHtmlForOrder input[fieldname=" + h + "]").addClass("formatFormItemForOrder2")
        };
        var q = new Ext.grid.ColumnModel({
            defaults: {
                sortable: false
            },
            columns: [new Ext.grid.RowNumberer(), {
                header: "fieldid",
                dataIndex: "fieldid",
                width: 20,
                hidden: true
            },
                {
                    header: "",
                    dataIndex: "opt",
                    width: 30,
                    renderer: b
                },
                {
                    header: lang("controlName"),
                    dataIndex: "fieldname",
                    width: 120
                },
                {
                    header: "",
                    dataIndex: "noIndex",
                    width: 1,
                    resizable: false
                }]
        });
        var a = new Ext.grid.GridPanel({
            stripeRows: true,
            region: "west",
            width: 200,
            loadMask: {
                msg: lang("waiting")
            },
            cm: q,
            store: d,
            hideBorders: true,
            border: false,
            enableDragDrop: true,
            dropConfig: {
                appendOnly: true
            },
            ddGroup: "GridDD",
            listeners: {
                mouseover: function(v) {
                    var u = v.getTarget();
                    var h = a.getView().findRowIndex(u);
                    try {
                        s(d.getAt(h).get("fieldname"))
                    } catch(v) {}
                },
                afterrender: function(t) {
                    var h = new Ext.dd.DropTarget(t.getEl(), {
                        ddGroup: "GridDD",
                        copy: false,
                        notifyDrop: function(u, x, w) {
                            if (u.id == "wfFormHtmlForOrder") {
                                var y = $(w.ddel);
                                var v = false;
                                d.each(function(z, A) {
                                    if (z.get("fieldid") == y.attr("fieldid")) {
                                        v = true
                                    }
                                });
                                if (v) {
                                    CNOA.msg.notice2(lang("beenAddedIfReorderNeedEmpty"));
                                    return false
                                }
                                d.add(new e({
                                    fieldid: y.attr("fieldid"),
                                    fieldname: y.attr("fieldname")
                                }));
                                j();
                                return true
                            }
                        }
                    })
                }
            },
            tbar: new Ext.Toolbar({
                items: [{
                    text: lang("reset"),
                    tooltip: lang("emptyListToOrderAgain"),
                    iconCls: "icon-system-refresh",
                    handler: function(h, t) {
                        d.removeAll();
                        $("#wfFormHtmlForOrder input[fieldname]").each(function(w, u) {
                            var x = $(this);
                            x.val("(" + x.attr("order") + ")" + x.attr("fieldname")).removeClass("formatFormItemForOrder2").removeClass("formatFormItemForOrder3")
                        })
                    }
                }]
            })
        });
        var p = new Ext.Panel({
            border: false,
            region: "center",
            autoScroll: true,
            bodyStyle: "border-left-width: 1px;background-color:#ECECEC",
            listeners: {
                afterrender: function(h) {
                    c(h)
                }
            },
            tbar: new Ext.Toolbar({
                style: "border-left-width: 1px",
                items: [{
                    text: "&nbsp;",
                    disabled: true
                },
                    lang("pcVersionForm")]
            })
        });
        var o = function() {
            var h = new Array();
            Ext.each(d.data.items,
                function(t) {
                    h.push(t.data.fieldid)
                });
            if (h.length <= 0) {
                CNOA.msg.alert(lang("leftListEmpty"));
                return
            }
            Ext.Ajax.request({
                method: "POST",
                url: n.baseUrl + "&task=saveFormItemsOrder",
                params: {
                    order: Ext.encode(h),
                    flowId: n.flowId
                },
                success: function(v, u) {
                    var t = Ext.decode(v.responseText);
                    if (t.success === true) {
                        CNOA.msg.alert(t.msg,
                            function() {
                                m.close()
                            })
                    } else {
                        CNOA.msg.alert(t.msg)
                    }
                }
            })
        };
        var g = Ext.getBody().getBox();
        var r = g.width - 20;
        var f = g.height - 20;
        var m = new Ext.Window({
            title: lang("wfFlowFieldMobileOrder") + " - " + lang("wfHowToUseMobileOrder"),
            width: r,
            height: f,
            border: false,
            modal: true,
            layout: "border",
            maximizable: true,
            items: [a, p],
            buttons: [{
                text: lang("save"),
                iconCls: "icon-btn-save",
                handler: function() {
                    o()
                }
            },
                {
                    text: lang("close"),
                    iconCls: "icon-dialog-cancel",
                    handler: function() {
                        m.hide()
                    }
                }]
        });
        m.show()
    },
    setBtnEnable: function() {
        Ext.getCmp(this.ID_saveForm).enable();
        Ext.getCmp(this.ID_closeForm).enable();
        Ext.getCmp(this.ID_closeWin).enable()
    },
    setBtnDisable: function() {
        Ext.getCmp(this.ID_saveForm).disable();
        Ext.getCmp(this.ID_closeForm).disable();
        Ext.getCmp(this.ID_closeWin).disable()
    },
    setEditorPage: function() {
        var c = this;
        try {
            var b = $($($(Ext.getDom(c.ID_iframe).contentWindow.document.body)).find("iframe").get(0).contentWindow.document.body);
            Ext.Ajax.request({
                url: c.baseUrl + "/getPrintPage?flowId=" + c.flowId,
                method: "POST",
                success: function(d) {
                    var v = Ext.decode(d.responseText);
                    if (v.success === true) {
                        var u = Ext.decode(v.pageset);
                        if (!u) {
                            return
                        }
                        try {
                            Ext.getDom(c.ID_iframe).contentWindow.editorConfig.pageset = u
                        } catch(m) {}
                        b.width(790);
                        var n = u.pageSize;
                        var g = 794;
                        var t = 1123;
                        switch (n) {
                            case "a1page":
                                if (u.pageDir == "lengthways") {
                                    g = 2245;
                                    t = 3174
                                } else {
                                    g = 3174;
                                    t = 2245
                                }
                                break;
                            case "a2page":
                                if (u.pageDir == "lengthways") {
                                    g = 1587;
                                    t = 2245
                                } else {
                                    g = 2245;
                                    t = 1587
                                }
                                break;
                            case "a3page":
                                if (u.pageDir == "lengthways") {
                                    g = 1123;
                                    t = 1587
                                } else {
                                    g = 1587;
                                    t = 1123
                                }
                                break;
                            case "a4page":
                                if (u.pageDir == "lengthways") {
                                    g = 794;
                                    t = 1123
                                } else {
                                    g = 1123;
                                    t = 794
                                }
                                break;
                            case "a5page":
                                if (u.pageDir == "lengthways") {
                                    g = 559;
                                    t = 794
                                } else {
                                    g = 794;
                                    t = 559
                                }
                                break
                        }
                        var k = Math.ceil(u.pageUp * 3.4);
                        var q = Math.ceil(u.pageDown * 3.4);
                        var h = Math.ceil(u.pageLeft * 3.4);
                        var p = Math.ceil(u.pageRight * 3.4);
                        var o = k + "px " + p + "px " + q + "px " + h + "px";
                        b.css("padding", o);
                        b.width(g - h - p);
                        if (Ext.isIE) {
                            var j = b.width() - g - 17;
                            var s = parseInt(b.css("paddingRight")) - 2;
                            b.css({
                                marginRight: j + 66,
                                paddingRight: s
                            })
                        }
                        var f = "index.php?app=wf&func=flow&action=set&modul=form&task=ueditorbackground&width=" + g + "&height=" + t + "&left=" + h + "&right=" + p + "&top=" + k + "&down=" + q + "&r=" + Math.random();
                        f = location.href.substr(0, location.href.lastIndexOf("/") + 1) + f;
                        b.css({
                            //backgroundImage: "url(" + f + ")",
                            backgroundImage: "url('/cnoa/scripts/ueditor/themes/default/images/ueditor_bgpic.gif')",
                            backgroundRepeat: "repeat-y",
                            backgroundColor: "#c9c9c9"
                        })
                    } else {
                        CNOA.msg.alert(v.msg,
                            function() {})
                    }
                }
            })
        } catch(a) {
            cdump(a)
        }
    }
};
var CNOA_wf_set_flow_step_permitClass;
CNOA_wf_set_flow_step_permitClass = CNOA.Class.create();
CNOA_wf_set_flow_step_permitClass.prototype = {
    init: function() {
        var a = this;
        this.baseUrl = "index.php?app=wf&func=flow&action=set&modul=flow";
        this.selectorUrl = "common/getSelectorData?target="
    },
    getHuiqianPermitPanel: function(a) {
        var b = new Ext.form.FieldSet({
            title: lang("signCompetence"),
            style: "margin:5px",
            layout: "column",
            defaults: {
                xtype: "container",
                border: false,
                layout: "form",
                labelAlign: "top",
                columnWidth: 0.3
            },
            items: [{
                items: this.getSelectWidget("人员", "user", a.user)
            },
                {
                    items: this.getSelectWidget("部门", "dept", a.dept)
                },
                {
                    items: this.getRoleRuleWidget(a.rule)
                }]
        });
        b.getValue = function() {
            var c = {
                user: [],
                dept: [],
                rule: []
            };
            Ext.each(this.get(0).get(0).store.data.items,
                function(d) {
                    c.user.push([d.get("id"), d.get("text")])
                });
            Ext.each(this.get(1).get(0).store.data.items,
                function(d) {
                    c.dept.push([d.get("id"), d.get("text")])
                });
            Ext.each(this.get(2).get(0).store.data.items,
                function(d) {
                    c.rule.push([d.get("id"), d.get("text")])
                });
            return c
        };
        return b
    },
    getFenfaPermitPanel: function(b) {
        var c = Ext.id();
        var a = new Ext.form.FieldSet({
            title: lang("distributionOfQX"),
            style: "margin:5px",
            layout: "column",
            defaults: {
                xtype: "container",
                border: false,
                layout: "form",
                labelAlign: "top",
                columnWidth: 0.3
            },
            items: [{
                items: this.getSelectWidget("人员", "user", b.user)
            },
                {
                    items: this.getSelectWidget("部门", "dept", b.dept)
                },
                {
                    items: this.getRoleRuleWidget(b.rule)
                },
                {
                    columnWidth: 0.9,
                    style: "padding-top: 10px",
                    items: this.getSelectWidget("自动分发人员(当前步骤结束时，自动分发给选中人员)", "user", b.autoFenfa)
                }]
        });
        a.getValue = function() {
            var d = {
                user: [],
                dept: [],
                rule: [],
                autoFenfa: []
            };
            Ext.each(this.get(0).get(0).store.data.items,
                function(e) {
                    d.user.push([e.get("id"), e.get("text")])
                });
            Ext.each(this.get(1).get(0).store.data.items,
                function(e) {
                    d.dept.push([e.get("id"), e.get("text")])
                });
            Ext.each(this.get(2).get(0).store.data.items,
                function(e) {
                    d.rule.push([e.get("id"), e.get("text")])
                });
            Ext.each(this.get(3).get(0).store.data.items,
                function(e) {
                    d.autoFenfa.push([e.get("id"), e.get("text")])
                });
            return d
        };
        return a
    },
    getSelectWidget: function(f, d, c) {
        var b = this;
        var e = Ext.id();
        var a = new Ext.data.ArrayStore({
            data: c || [],
            fields: [{
                name: "id"
            },
                {
                    name: "text"
                }]
        });
        return [{
            xtype: "multiselect",
            id: e,
            fieldLabel: f,
            valueField: "id",
            displayField: "text",
            width: 200,
            height: 100,
            ddReorder: false,
            store: a
        },
            {
                xtype: "container",
                layout: "table",
                border: false,
                layoutConfig: {
                    columns: 2
                },
                items: [{
                    xtype: "button",
                    text: lang("add"),
                    handler: function() {
                        new Ext.SelectorPanel({
                            dataUrl: b.selectorUrl + d,
                            target: d,
                            multiselect: true,
                            listeners: {
                                select: function(g, h) {
                                    a.removeAll();
                                    Ext.each(h,
                                        function(k) {
                                            var j = {
                                                id: k.id,
                                                text: k.name
                                            };
                                            a.add(new Ext.data.Record(j))
                                        })
                                },
                                dataLoaded: function(h, g) {
                                    var j = [];
                                    Ext.each(a.data.items,
                                        function(k) {
                                            j.push(k.get("id"))
                                        });
                                    g.call(h, j.join(","))
                                }
                            }
                        })
                    }
                },
                    {
                        xtype: "button",
                        style: "margin-left:15px;",
                        text: lang("del"),
                        handler: function() {
                            var g = Ext.getCmp(e);
                            var h = g.view.getSelectedRecords();
                            if (h.length != 0) {
                                a.remove(h)
                            }
                        }
                    }]
            }]
    },
    getRoleRuleWidget: function(c) {
        var b = this;
        var d = Ext.id();
        var a = new Ext.data.ArrayStore({
            data: c || [],
            fields: [{
                name: "id"
            },
                {
                    name: "text"
                }]
        });
        return [{
            xtype: "multiselect",
            id: d,
            fieldLabel: lang("role"),
            valueField: "id",
            displayField: "text",
            width: 210,
            height: 100,
            ddReorder: false,
            store: a
        },
            {
                xtype: "container",
                layout: "table",
                border: false,
                layoutConfig: {
                    columns: 2
                },
                items: [{
                    xtype: "button",
                    text: lang("add"),
                    handler: function() {
                        b.addRule(a)
                    }
                },
                    {
                        xtype: "button",
                        style: "margin-left:15px;",
                        text: lang("del"),
                        handler: function() {
                            var e = Ext.getCmp(d);
                            var f = e.view.getSelectedRecords();
                            if (f.length != 0) {
                                a.remove(f)
                            }
                        }
                    }]
            }]
    },
    addRule: function(c) {
        var b = new Ext.form.ComboBox({
                fieldLabel: lang("people"),
                width: 100,
                value: "faqi",
                valueField: "value",
                displayField: "name",
                mode: "local",
                triggerAction: "all",
                forceSelection: true,
                editable: false,
                store: new Ext.data.SimpleStore({
                    fields: ["value", "name"],
                    data: [["faqi", lang("initiator")], ["zhuban", lang("sponsor")]]
                })
            }),
            e = new Ext.form.ComboBox({
                fieldLabel: lang("department"),
                width: 200,
                value: "myDept",
                valueField: "value",
                displayField: "name",
                mode: "local",
                triggerAction: "all",
                forceSelection: true,
                editable: false,
                store: new Ext.data.SimpleStore({
                    fields: ["value", "name"],
                    data: [["myDept", lang("belongDept")], ["upDept", lang("upperDepartment")], ["myUpDept", lang("deptAndUpDept")], ["allDept", lang("deptAndAllUpDept")]]
                })
            }),
            a = new Ext.form.SelectorForm({
                fieldLabel: lang("station"),
                width: 200,
                height: 150,
                multiselect: true,
                hiddenName: "station",
                selectorType: "station"
            });
        var d = new Ext.Window({
            title: lang("roleRule"),
            border: false,
            width: 350,
            height: 250,
            modal: true,
            layout: "form",
            bodyStyle: "padding:10px;",
            labelWidth: 70,
            labelAlign: "right",
            items: [b, e, a],
            buttons: [{
                text: lang("save"),
                handler: function() {
                    var g = a.getValue();
                    if (g.length != 0) {
                        var g = g.split(","),
                            r = a.getRawValue().split(","),
                            f = b.getValue(),
                            m = e.getValue(),
                            s = b.getRawValue(),
                            k = e.getRawValue(),
                            p = c.data.items;
                        for (var n = 0; n < g.length; n++) {
                            var t = false;
                            for (var o = 0; o < p.length; o++) {
                                var q = p[o].get("id").split("|");
                                if (q[0] == f && q[1] == m && q[2] == g[n]) {
                                    t = true;
                                    break
                                }
                            }
                            if (!t) {
                                var h = {
                                    id: f + "|" + m + "|" + g[n],
                                    text: "[" + s + "][" + k + "][" + r[n] + "]"
                                };
                                c.add(new Ext.data.Record(h))
                            }
                        }
                    }
                    d.close()
                }
            },
                {
                    text: lang("close"),
                    handler: function() {
                        d.close()
                    }
                }]
        }).show()
    },
    getWordPermitPanel: function() {
        return new Ext.form.FieldSet({
            style: "margin:5px",
            title: lang("allowEditWord"),
            items: [{
                xtype: "checkbox",
                fieldLabel: lang("allowRevise"),
                boxLabel: lang("whetherAllowDisplay"),
                name: "allowWordEdit",
                checked: false
            }]
        })
    }
};
var CNOA_wf_set_flow_step_baseClass, CNOA_wf_set_flow_step_base;
CNOA_wf_set_flow_step_baseClass = CNOA.Class.create();
CNOA_wf_set_flow_step_baseClass.prototype = {
    init: function(j, c, a, h) {
        var e = this;
        this.baseUrl = "index.php?app=wf&func=flow&action=set&modul=flow";
        this.parent = j;
        this.stepType = c.nodeType;
        this.stepId = c.id;
        this.data = j.stepData.base;
        this.nextStep = [];
        this.allNextStep = [];
        otherNodes = [],
            linksTo = [],
            sourceNodeIds = [];
        this.nextstepDisplay = false;
        this.basestepDisplay = false;
        if (c.nodeType == "childNode") {
            this.nextstepDisplay = true;
            this.basestepDisplay = true
        }
        for (var d = 0; d < a.length; d++) {
            sourceNodeIds.push(a[d].id)
        }
        Ext.each(h.lines,
            function(p, o) {
                if (p.source.id == c.id) {
                    linksTo[p.target.id] = true;
                    var n = {};
                    n.stepId = p.target.id;
                    n.stepName = p.target.value;
                    e.nextStep.push(n)
                }
            });
        Ext.each(h.nodes,
            function(n, o) {
                if ((c.id != n.id) && !CNOA.common.in_array(n.id, sourceNodeIds)) {
                    var p = false;
                    e.allNextStep.push({
                        stepId: n.id,
                        stepName: n.value
                    });
                    otherNodes.push({
                        hidden: n.nodeType == "childNode" ? true: false,
                        xtype: "checkbox",
                        boxLabel: n.value,
                        checked: linksTo[n.id] == true ? true: false,
                        nodeId: n.id,
                        nodeNum: o,
                        nodeType: n.nodeType,
                        name: "next_node_id_" + n.id,
                        id: "next_node_step_id_" + n.id,
                        listeners: {
                            afterrender: function(q) {
                                q.wrap.setStyle("marginRight", "10px")
                            },
                            check: function(r, q) {
                                e.parent.condition.mainPanel.removeAll()
                            }
                        }
                    })
                }
            });
        e.allNextStep.push({
            stepId: h.endNode.id,
            stepName: h.endNode.value
        });
        otherNodes.push({
            xtype: "checkbox",
            boxLabel: h.endNode.value,
            checked: linksTo[h.endNode.id] == true ? true: false,
            nodeId: h.endNode.id,
            nodeType: h.endNode.nodeType,
            id: "next_node_step_id_" + h.endNode.id,
            name: "next_node_id_" + h.endNode.id,
            listeners: {
                check: function(o, n) {
                    e.parent.condition.mainPanel.removeAll()
                }
            }
        });
        var k = [["1", lang("agree")], ["2", lang("approval")], ["3", lang("approval2")], ["4", lang("confirm")], ["5", lang("opt")], ["6", lang("archive")], ["7", lang("readed")]];
        var b = [["1", lang("initiator")], ["2", lang("sponsor")], ["3", lang("initiator") + "/" + lang("sponsor")], ["4", lang("notNotice")]];
        this.ID_TEXTFIELD_BINGFLOWNAMES = Ext.id();
        this.ID_TEXTFIELD_BINGFLOWIDS = Ext.id();
        this.setBaseField = [{
            xtype: "fieldset",
            style: "margin:5px",
            title: lang("stepProper"),
            items: [{
                xtype: "textfield",
                fieldLabel: lang("stepName"),
                width: 400,
                name: "stepName",
                allowBlank: false,
                value: c.value
            },
                {
                    xtype: "container",
                    border: false,
                    layout: "form",
                    hidden: this.basestepDisplay,
                    items: [{
                        xtype: "cnoa_combo",
                        fieldLabel: lang("handleType"),
                        hidden: this.stepType == "startNode" ? true: false,
                        store: new Ext.data.SimpleStore({
                            fields: ["value", "gender"],
                            data: k
                        }),
                        value: 1,
                        allowBlank: false,
                        tipTitle: lang("help"),
                        tipText: lang("thisStepHandled"),
                        width: 150,
                        valueField: "value",
                        displayField: "gender",
                        name: "doBtnText",
                        hiddenName: "doBtnText",
                        mode: "local",
                        triggerAction: "all",
                        forceSelection: true,
                        editable: false
                    },
                        {
                            xtype: "compositefield",
                            fieldLabel: lang("allowPoerator"),
                            defaults: {
                                xtype: "cnoa_checkbox",
                                width: 90
                            },
                            items: [{
                                boxLabel: lang("reject"),
                                name: "allowReject",
                                tipTitle: lang("help"),
                                tipText: lang("allowStepRefuse"),
                                hidden: this.stepType == "startNode" ? true: false,
                                checked: this.stepType == "startNode" ? false: true
                            },
                                {
                                    boxLabel: lang("countersigned"),
                                    name: "allowHuiqian",
                                    tipTitle: lang("help"),
                                    tipText: lang("allowStepOther"),
                                    hidden: this.stepType == "startNode" ? true: false,
                                    checked: this.stepType == "startNode" ? false: true,
                                    listeners: {
                                        check: function(n, o) {
                                            if (e.huiqianPermitPanel) {
                                                o ? e.huiqianPermitPanel.show() : e.huiqianPermitPanel.hide()
                                            }
                                        }
                                    }
                                },
                                {
                                    boxLabel: lang("distribute"),
                                    name: "allowFenfa",
                                    tipTitle: lang("help"),
                                    tipText: lang("allowSteoDistribution"),
                                    hidden: this.stepType == "startNode" ? true: false,
                                    checked: this.stepType == "startNode" ? false: true,
                                    listeners: {
                                        check: function(n, o) {
                                            if (e.fenfaPermitPanel) {
                                                o ? e.fenfaPermitPanel.show() : e.fenfaPermitPanel.hide()
                                            }
                                        }
                                    }
                                },
                                {
                                    boxLabel: lang("sendBack"),
                                    name: "allowTuihui",
                                    tipTitle: lang("help"),
                                    tipText: lang("allowStepBackUp"),
                                    hidden: this.stepType == "startNode" ? true: false,
                                    checked: this.stepType == "startNode" ? false: true
                                },
                                {
                                    boxLabel: lang("recall"),
                                    name: "allowCallback",
                                    tipTitle: lang("help"),
                                    tipText: lang("allowStepRecall"),
                                    hidden: this.stepType == "startNode" ? false: true,
                                    checked: this.stepType == "startNode" ? true: false
                                },
                                {
                                    boxLabel: lang("recell"),
                                    name: "allowCancel",
                                    tipTitle: lang("help"),
                                    tipText: lang("allowStepCancel"),
                                    hidden: this.stepType == "startNode" ? false: true,
                                    checked: this.stepType == "startNode" ? true: false
                                },
                                {
                                    boxLabel: lang("print"),
                                    name: "allowPrint",
                                    tipTitle: lang("help"),
                                    tipText: lang("allowStepPrint"),
                                    hidden: this.stepType == "startNode" ? true: false
                                },
                                {
                                    boxLabel: lang("sendSms"),
                                    name: "allowSms",
                                    tipTitle: lang("help"),
                                    tipText: lang("allowStepSendSMS")
                                },
                                {
                                    boxLabel: lang("reservationOpinion"),
                                    name: "allowYijian",
                                    tipTitle: lang("help"),
                                    tipText: "允许此步骤保留意见",
                                    hidden: this.stepType == "startNode" ? true: false,
                                    checked: this.stepType == "startNode" ? false: true
                                }]
                        },
                        {
                            xtype: "compositefield",
                            fieldLabel: lang("attachPermiss"),
                            defaults: {
                                xtype: "cnoa_checkbox",
                                width: 90
                            },
                            items: [{
                                boxLabel: lang("addAttach"),
                                name: "allowAttachAdd",
                                tipTitle: lang("help"),
                                tipText: lang("allowStepAddAttach"),
                                checked: true
                            },
                                {
                                    boxLabel: lang("viewAttch"),
                                    name: "allowAttachView",
                                    tipTitle: lang("help"),
                                    tipText: lang("allowStepOnlineCheck"),
                                    checked: true
                                },
                                {
                                    boxLabel: lang("editAttach"),
                                    name: "allowAttachEdit",
                                    tipTitle: lang("help"),
                                    tipText: lang("allowStepOnlineEdit"),
                                    checked: true
                                },
                                {
                                    boxLabel: lang("removeAttachment"),
                                    name: "allowAttachDelete",
                                    tipTitle: lang("help"),
                                    tipText: lang("allowStepDelAttch"),
                                    checked: true
                                },
                                {
                                    boxLabel: lang("downloadAttachment"),
                                    name: "allowAttachDown",
                                    tipTitle: lang("help"),
                                    tipText: lang("allowStepDownload"),
                                    checked: true
                                }]
                        },
                        {
                            xtype: "compositefield",
                            fieldLabel: lang("attachHqPermiss"),
                            defaults: {
                                xtype: "cnoa_checkbox",
                                width: 90
                            },
                            items: [{
                                boxLabel: lang("addAttach"),
                                name: "allowHqAttachAdd",
                                tipTitle: lang("help"),
                                tipText: lang("allowHqStepAddAttach"),
                                checked: true
                            },
                                {
                                    boxLabel: lang("viewAttch"),
                                    name: "allowHqAttachView",
                                    tipTitle: lang("help"),
                                    tipText: lang("allowHqStepOnlineCheck"),
                                    checked: true
                                },
                                {
                                    boxLabel: lang("editAttach"),
                                    name: "allowHqAttachEdit",
                                    tipTitle: lang("help"),
                                    tipText: lang("allowHqStepOnlineEdit"),
                                    checked: true
                                },
                                {
                                    boxLabel: lang("removeAttachment"),
                                    name: "allowHqAttachDelete",
                                    tipTitle: lang("help"),
                                    tipText: lang("allowHqStepDelAttch"),
                                    checked: true
                                },
                                {
                                    boxLabel: lang("downloadAttachment"),
                                    name: "allowHqAttachDown",
                                    tipTitle: lang("help"),
                                    tipText: lang("allowHqStepDownload"),
                                    checked: true
                                }]
                        },
                        {
                            xtype: "compositefield",
                            fieldLabel: lang("processTime"),
                            hidden: this.stepType == "startNode" ? true: false,
                            items: [{
                                xtype: "cnoa_textfield",
                                name: "stepTime",
                                width: 80,
                                value: "0"
                            },
                                {
                                    xtype: "displayfield",
                                    value: lang("hoursAllowDecimal")
                                }]
                        },
                        {
                            xtype: "compositefield",
                            hidden: this.stepType == "startNode" ? true: false,
                            fieldLabel: lang("remiderAdvance"),
                            items: [{
                                xtype: "textfield",
                                name: "urgeBefore",
                                width: 80,
                                value: "0"
                            },
                                {
                                    xtype: "displayfield",
                                    value: lang("minutesCloseTimeLimit")
                                }]
                        },
                        {
                            xtype: "combo",
                            fieldLabel: lang("urgingHanRemind"),
                            hidden: this.stepType == "startNode" ? true: false,
                            store: new Ext.data.SimpleStore({
                                fields: ["value", "gender"],
                                data: b
                            }),
                            width: 150,
                            valueField: "value",
                            displayField: "gender",
                            name: "urgeTarget",
                            hiddenName: "urgeTarget",
                            mode: "local",
                            triggerAction: "all",
                            forceSelection: true,
                            editable: false,
                            listeners: {
                                select: function(p, n, o) {}
                            }
                        }]
                }]
        },
            {
                xtype: "fieldset",
                style: "margin:5px",
                title: lang("subproProperties"),
                hidden: c.nodeType == "childNode" ? false: true,
                items: [{
                    xtype: "textarea",
                    width: 398,
                    heigh: 100,
                    fieldLabel: lang("bindFlow"),
                    readOnly: true,
                    name: "bingNames",
                    id: this.ID_TEXTFIELD_BINGFLOWNAMES,
                    listeners: {
                        afterrender: function(n) {
                            n.mon(n.el, "click",
                                function() {
                                    e.bingFlow()
                                })
                        }
                    }
                },
                    {
                        xtype: "hidden",
                        name: "bingIds",
                        id: this.ID_TEXTFIELD_BINGFLOWIDS
                    },
                    {
                        xtype: "button",
                        text: lang("clear"),
                        style: "margin-left:90px; margin-bottom:10px",
                        handler: function() {
                            Ext.getCmp(e.ID_TEXTFIELD_BINGFLOWNAMES).setValue("");
                            Ext.getCmp(e.ID_TEXTFIELD_BINGFLOWIDS).setValue("")
                        }
                    },
                    {
                        xtype: "combo",
                        fieldLabel: lang("initiator"),
                        store: new Ext.data.SimpleStore({
                            fields: ["value", "name"],
                            data: [["myself", lang("currentStepDealt")], ["banlichoice", lang("selectByTransactor")]]
                        }),
                        width: 150,
                        valueField: "value",
                        displayField: "name",
                        name: "faqiFlow",
                        hiddenName: "faqiFlow",
                        mode: "local",
                        triggerAction: "all",
                        forceSelection: true,
                        editable: false,
                        value: "myself"
                    },
                    {
                        xtype: "combo",
                        fieldLabel: lang("stepRequire"),
                        store: new Ext.data.SimpleStore({
                            fields: ["value", "name"],
                            data: [["myself", lang("transactorDecision")], ["nextstep", lang("afterChilAllTurn")]]
                        }),
                        width: 300,
                        valueField: "value",
                        displayField: "name",
                        name: "endFlow",
                        hiddenName: "endFlow",
                        mode: "local",
                        triggerAction: "all",
                        forceSelection: true,
                        editable: false,
                        value: "myself"
                    },
                    {
                        xtype: "checkbox",
                        fieldLabel: lang("whetherShareAttch"),
                        name: "shareFile"
                    }]
            }];
        if (this.stepType != "childNode" && this.stepType != "startNode") {
            var f = new CNOA_wf_set_flow_step_permitClass();
            var m = {
                    user: [],
                    dept: []
                },
                g = {
                    user: [],
                    dept: []
                };
            if (this.data) {
                if (this.data.huiqianPermit) {
                    m = this.data.huiqianPermit
                }
                if (this.data.fenfaPermit) {
                    g = this.data.fenfaPermit
                }
            }
            this.huiqianPermitPanel = f.getHuiqianPermitPanel(m);
            this.fenfaPermitPanel = f.getFenfaPermitPanel(g);
            this.setBaseField.push(this.huiqianPermitPanel);
            this.setBaseField.push(this.fenfaPermitPanel);
            if (this.parent.tplSort == 3) {
                this.wordPermitPanel = f.getWordPermitPanel();
                this.setBaseField.push(this.wordPermitPanel)
            }
        }
        this.setBaseField.push({
            xtype: "fieldset",
            style: "margin:5px",
            title: lang("theNextStep"),
            hidden: this.nextstepDisplay,
            items: [{
                xtype: "panel",
                layout: "column",
                style: "margin-bottom:5px;",
                id: "CNOA_WF_FLOW_DESIGNER_COLUMN_CT",
                hideLabel: true,
                defaults: {
                    autoWidth: true
                },
                border: false,
                items: otherNodes
            }]
        });
        this.mainPanel = new Ext.form.FormPanel({
            title: lang("setStep"),
            labelAlign: "right",
            labelWidth: 80,
            border: false,
            waitMsgTarget: true,
            autoScroll: true,
            items: this.setBaseField
        });
        this.mainPanel.getForm().loadRecord(new Ext.data.Record(this.data))
    },
    bingFlow: function() {
        var g = this;
        var e = [{
            name: "flowId"
        },
            {
                name: "flowId"
            },
            {
                name: "name"
            },
            {
                name: "sort"
            },
            {
                name: "status"
            },
            {
                name: "tplSort"
            },
            {
                name: "flowType"
            }];
        var k = new Ext.data.Store({
            proxy: new Ext.data.HttpProxy({
                url: this.baseUrl + "&task=getJsonData",
                disableCaching: true
            }),
            reader: new Ext.data.JsonReader({
                totalProperty: "total",
                root: "data",
                fields: e
            })
        });
        var b = new Ext.grid.CheckboxSelectionModel({
            singleSelect: false
        });
        k.load();
        var j = new Ext.grid.ColumnModel([new Ext.grid.RowNumberer(), {
            header: "flowId",
            dataIndex: "flowId",
            hidden: true
        },
            {
                header: lang("flowName"),
                dataIndex: "name",
                id: "flowName",
                width: 140,
                sortable: true,
                menuDisabled: true
            },
            {
                header: lang("sort2"),
                dataIndex: "sort",
                width: 140,
                sortable: false,
                menuDisabled: true
            },
            {
                header: lang("processState"),
                dataIndex: "status",
                width: 100,
                sortable: false,
                menuDisabled: true,
                renderer: function(p, s, o) {
                    var q = this;
                    if (p == 1) {
                        return "<span class=cnoa_color_green>" + lang("enable") + "</span>"
                    } else {
                        return "<span class=cnoa_color_red>" + lang("disable") + "</span>"
                    }
                }
            },
            {
                header: "",
                dataIndex: "noIndex",
                width: 1,
                menuDisabled: true,
                resizable: false
            }]);
        var d = new Ext.PagingToolbar({
            displayInfo: true,
            store: k,
            style: "border-left-width:1px;",
            pageSize: 15,
            listeners: {
                beforechange: function(o, p) {
                    Ext.apply(p, g.storeBar)
                }
            }
        });
        this.ID_SEARCH_TEXT_NAME = Ext.id();
        g.storeBar = {
            sname: "",
            sortId: 0
        };
        var m = new Ext.grid.GridPanel({
            store: k,
            bodyStyle: "border-left-width:1px;",
            loadMask: {
                msg: lang("waiting")
            },
            cm: j,
            hideBorders: true,
            border: false,
            region: "center",
            layout: "fit",
            autoExpandColumn: "flowName",
            stripeRows: true,
            bbar: d,
            tbar: ["->", (lang("flowName") + ":"), {
                xtype: "textfield",
                width: 200,
                id: this.ID_SEARCH_TEXT_NAME,
                listeners: {
                    specialkey: function(p, o) {
                        if (o.getKey() == o.ENTER) {
                            g.storeBar.sname = Ext.getCmp(g.ID_SEARCH_TEXT_NAME).getValue();
                            g.store.load({
                                params: g.storeBar
                            })
                        }
                    }
                }
            },
                {
                    xtype: "button",
                    text: lang("search"),
                    style: "margin-left:5px",
                    iconCls: "icon-search",
                    handler: function() {
                        g.storeBar.sname = Ext.getCmp(g.ID_SEARCH_TEXT_NAME).getValue();
                        k.load({
                            params: g.storeBar
                        })
                    }
                },
                "-", {
                    xtype: "button",
                    text: lang("clear"),
                    handler: function() {
                        Ext.getCmp(g.ID_SEARCH_TEXT_NAME).setValue("");
                        g.storeBar.sname = "";
                        k.load({
                            params: g.storeBar
                        })
                    }
                }]
        });
        var c = new Ext.tree.AsyncTreeNode({
            text: lang("allSort"),
            sortId: "0",
            iconCls: "icon-tree-root-cnoa",
            cls: "feeds-node",
            expanded: true
        });
        var h = new Ext.tree.TreeLoader({
            dataUrl: this.baseUrl + "&task=getSortTree",
            preloadChildren: true,
            clearOnLoad: false
        });
        var n = new Ext.tree.TreePanel({
            hideBorders: true,
            border: false,
            rootVisible: false,
            lines: true,
            animCollapse: false,
            animate: false,
            loader: h,
            root: c,
            autoScroll: true,
            listeners: {
                click: function(o) {
                    g.storeBar.sortId = o.attributes.sortId;
                    k.load({
                        params: g.storeBar
                    })
                }
            }
        });
        var a = new Ext.Panel({
            region: "west",
            layout: "fit",
            split: true,
            width: 180,
            minWidth: 80,
            maxWidth: 380,
            border: false,
            bodyStyle: "border-right-width:1px;",
            items: [n],
            tbar: new Ext.Toolbar({
                style: "border-right-width:1px;",
                items: [lang("launchFlow"), "->", {
                    handler: function(o, p) {
                        g.treeRoot.reload()
                    }.createDelegate(this),
                    iconCls: "icon-system-refresh",
                    tooltip: lang("refresh"),
                    text: lang("refresh")
                }]
            })
        });
        var f = new Ext.Window({
            width: 800,
            height: makeWindowHeight(550),
            modal: true,
            title: lang("chooseBindingFlow"),
            layout: "border",
            items: [a, m],
            buttons: [{
                text: lang("add"),
                handler: function(p) {
                    var r = m.getSelectionModel().getSelections();
                    if (r.length == 0) {
                        CNOA.miniMsg.alertShowAt(p, lang("haveNotChosen"))
                    } else {
                        if (r) {
                            var q = Ext.getCmp(g.ID_TEXTFIELD_BINGFLOWIDS).getValue();
                            var s = Ext.getCmp(g.ID_TEXTFIELD_BINGFLOWNAMES).getValue();
                            for (var o = 0; o < r.length; o++) {
                                q += r[o].get("flowId") + ",";
                                s += r[o].get("name") + ","
                            }
                            Ext.getCmp(g.ID_TEXTFIELD_BINGFLOWNAMES).setValue(s);
                            Ext.getCmp(g.ID_TEXTFIELD_BINGFLOWIDS).setValue(q)
                        }
                        CNOA.msg.alert(lang("successopt"));
                        f.close()
                    }
                }
            },
                {
                    text: lang("close"),
                    handler: function() {
                        f.close()
                    }
                }]
        }).show()
    },
    getData: function() {
        var g = this.mainPanel.getForm();
        var e = g.getValues();
        var a = [];
        for (var b in e) {
            if ((b.indexOf("next_node_id_") != -1) && (e[b] == "on")) {
                a.push(b.replace("next_node_id_", ""))
            }
        }
        if (this.stepType == "startNode") {
            g.findField("doBtnText").disable()
        }
        var d = {
            user: [],
            dept: [],
            rule: []
        };
        var c = {
            user: [],
            dept: [],
            rule: []
        };
        if (this.stepType != "childNode") {
            if (e.allowHuiqian) {
                d = this.huiqianPermitPanel.getValue()
            }
            if (e.allowFenfa) {
                c = this.fenfaPermitPanel.getValue()
            }
            if (!g.isValid()) {
                CNOA.msg.alert(lang("setupStepBasic"));
                return false
            }
        } else {
            if (e.bingIds == "") {
                CNOA.msg.alert(lang("notBindingProcess"));
                return
            }
            if (e.stepName == "") {
                CNOA.msg.alert(lang("notFillChildFlow"));
                return
            }
        }
        this.data = {
            stepType: this.stepType,
            stepId: this.stepId,
            bingIds: e.bingIds,
            bingNames: e.bingNames,
            faqiFlow: e.faqiFlow,
            endFlow: e.endFlow,
            shareFile: e.shareFile,
            stepName: e.stepName,
            nextStep: a,
            doBtnText: e.doBtnText,
            allowReject: e.allowReject,
            allowHuiqian: e.allowHuiqian,
            allowFenfa: e.allowFenfa,
            allowTuihui: e.allowTuihui,
            allowCallback: e.allowCallback,
            allowPrint: e.allowPrint,
            allowYijian: e.allowYijian,
            allowCancel: e.allowCancel,
            allowAttachAdd: e.allowAttachAdd,
            allowAttachView: e.allowAttachView,
            allowAttachEdit: e.allowAttachEdit,
            allowAttachDelete: e.allowAttachDelete,
            allowAttachDown: e.allowAttachDown,
            allowHqAttachAdd: e.allowHqAttachAdd,
            allowHqAttachView: e.allowHqAttachView,
            allowHqAttachEdit: e.allowHqAttachEdit,
            allowHqAttachDelete: e.allowHqAttachDelete,
            allowHqAttachDown: e.allowHqAttachDown,
            allowWordEdit: e.allowWordEdit,
            allowAttachWordEdit: e.allowAttachWordEdit,
            allowSms: e.allowSms,
            stepTime: e.stepTime,
            urgeBefore: e.urgeBefore,
            urgeTarget: e.urgeTarget,
            fenfaPermit: c,
            huiqianPermit: d
        };
        return this.data
    }
};
var CNOA_wf_set_flow_step_fieldsClass, CNOA_wf_set_flow_step_fields;
CNOA_wf_set_flow_step_fieldsClass = CNOA.Class.create();
CNOA_wf_set_flow_step_fieldsClass.prototype = {
    init: function(c) {
        var e = this;
        this.parent = c;
        this.flowFields = c.flowFields;
        this.ID_GRID = Ext.id();
        var d = {
            coders: this.flowFields
        };
        this.formData = this.parent.stepData.fields;
        var a = "#" + this.ID_GRID + " input[type=checkbox][id=CNOA_WF_STEP";
        var b = "#" + this.ID_GRID + " input[type=radio][name=CNOA_WF_STEP_SHOWHIDE";
        this.store = new Ext.data.GroupingStore({
            proxy: new Ext.data.MemoryProxy(d),
            reader: new Ext.data.JsonReader({
                    root: "coders"
                },
                [{
                    name: "id"
                },
                    {
                        name: "name"
                    },
                    {
                        name: "gname"
                    },
                    {
                        name: "type"
                    },
                    {
                        name: "otype"
                    },
                    {
                        name: "from"
                    },
                    {
                        name: "table"
                    },
                    {
                        name: "tableid"
                    },
                    {
                        name: "dataType"
                    }]),
            groupField: "gname"
        });
        this.store.load();
        this.sm = new Ext.grid.CheckboxSelectionModel({
            singleSelect: false
        });
        this.cm = new Ext.grid.ColumnModel([this.sm, {
            header: lang("controlName"),
            dataIndex: "name",
            id: "name",
            width: 130,
            sortable: true,
            menuDisabled: true
        },
            {
                header: lang("controlType"),
                dataIndex: "id",
                width: 200,
                sortable: true,
                menuDisabled: true,
                renderer: this.mkTypename.createDelegate(this)
            },
            {
                header: lang("controlVisibleWay"),
                dataIndex: "id",
                width: 108,
                sortable: true,
                menuDisabled: true,
                renderer: this.mkShowHide.createDelegate(this)
            },
            {
                header: lang("controlSet"),
                dataIndex: "id",
                width: 108,
                sortable: true,
                menuDisabled: true,
                renderer: this.mkOpt.createDelegate(this)
            },
            {
                header: "",
                dataIndex: "gname",
                width: 1,
                sortable: true,
                menuDisabled: true
            }]);
        this.mainPanel = new Ext.grid.GridPanel({
            title: lang("formControl"),
            id: this.ID_GRID,
            border: false,
            store: this.store,
            autoExpandColumn: "name",
            loadMask: {
                msg: lang("waiting")
            },
            cm: this.cm,
            sm: this.sm,
            stripeRows: true,
            hideBorders: true,
            view: new Ext.grid.GroupingView({
                forceFit: true,
                groupTextTpl: "{text} ({[values.rs.length]} 项)"
            }),
            listeners: {
                afterrender: function() {
                    setTimeout(function() {
                            e.loadData()
                        },
                        100)
                },
                activate: function() {
                    e.parent.fieldClick = true
                }
            },
            tbar: new Ext.Toolbar({
                defaults: {
                    style: "margin-right:2px;"
                },
                items: [lang("controlTool") + "： ", "-", {
                    text: lang("visible"),
                    handler: function(g) {
                        var j = e.mainPanel.getSelectionModel().getSelections();
                        if (j.length == 0) {
                            CNOA.miniMsg.alertShowAt(g, lang("notChoseControlTip"))
                        } else {
                            var h = "";
                            var k = [];
                            for (var f = 0; f < j.length; f++) {
                                var m = j[f].get("id");
                                $(b + m + "][value=show]").attr("checked", true);
                                $(a + "_WRITE" + m + "]").attr("disabled", false);
                                $(a + "_MUST" + m + "]").attr("disabled", false);
                                $(b + m + "]").attr("disabled", false);
                                if (j[f].get("otype") == "detailtable") {
                                    k.push(m)
                                }
                            }
                            for (var f = 0; f < k.length; f++) {
                                $("div[tableid=" + k[f] + "] input").attr("disabled", false)
                            }
                        }
                    }
                },
                    {
                        text: lang("secret"),
                        handler: function(g) {
                            var j = e.mainPanel.getSelectionModel().getSelections();
                            if (j.length == 0) {
                                CNOA.miniMsg.alertShowAt(g, lang("notChoseControlTip"))
                            } else {
                                var h = "";
                                var k = [];
                                for (var f = 0; f < j.length; f++) {
                                    var m = j[f].get("id");
                                    $(b + m + "][value=hide]").attr("checked", true);
                                    $(a + "_WRITE" + m + "]").attr("checked", false);
                                    $(a + "_MUST" + m + "]").attr("checked", false);
                                    $(a + "_WRITE" + m + "]").attr("disabled", true);
                                    $(a + "_MUST" + m + "]").attr("disabled", true);
                                    if (j[f].get("otype") == "detailtable") {
                                        k.push(m)
                                    }
                                }
                                for (var f = 0; f < k.length; f++) {
                                    $("div[tableid=" + k[f] + "] input").attr("disabled", true)
                                }
                            }
                        }
                    },
                    "-", {
                        text: lang("canFill"),
                        handler: function(m) {
                            var o = e.mainPanel.getSelectionModel().getSelections();
                            if (o.length == 0) {
                                CNOA.miniMsg.alertShowAt(m, lang("notChoseControlTip"))
                            } else {
                                var f = "";
                                for (var k = 0; k < o.length; k++) {
                                    var j = o[k].get("id"),
                                        g = o[k].get("otype");
                                    if (CNOA.common.in_array(g, ["textfield", "textarea", "select", "radio", "choice", "checkbox"])) {
                                        var p = $(a + "_WRITE" + j + "]");
                                        var n = $(a + "_MUST" + j + "]");
                                        var h = p.attr("tableid");
                                        if (!Ext.isEmpty(p.attr("tableid"))) {
                                            if ($("#CNOA_WF_STEP_HIDE" + h).attr("checked")) {
                                                continue
                                            }
                                        }
                                        $(b + j + "][value=show]").attr("checked", true);
                                        p.attr("disabled", false);
                                        n.attr("disabled", false);
                                        p.attr("checked", true)
                                    }
                                }
                            }
                        }
                    },
                    {
                        text: lang("cancelCanfill"),
                        handler: function(g) {
                            var k = e.mainPanel.getSelectionModel().getSelections();
                            if (k.length == 0) {
                                CNOA.miniMsg.alertShowAt(g, lang("notChoseControlTip"))
                            } else {
                                var h = "";
                                for (var f = 0; f < k.length; f++) {
                                    var m = k[f].get("id");
                                    var j = $(a + "_WRITE" + m + "]");
                                    if (j.attr("checked")) {
                                        j.attr("checked", false);
                                        j.attr("disabled", false);
                                        $(a + "_MUST" + m + "]").attr("checked", false)
                                    }
                                }
                            }
                        }
                    },
                    "-", {
                        text: lang("isRequire"),
                        handler: function() {
                            var n = e.mainPanel.getSelectionModel().getSelections();
                            if (n.length == 0) {
                                CNOA.miniMsg.alertShowAt(button, lang("notChoseControlTip"))
                            } else {
                                var m = "";
                                for (var j = 0; j < n.length; j++) {
                                    var o = n[j].get("id"),
                                        g = n[j].get("otype");
                                    if (CNOA.common.in_array(g, ["textfield", "textarea", "select", "radio", "choice", "attach", "abutment"])) {
                                        $(b + o + "][value=show]").attr("checked", true);
                                        var k = $(a + "_WRITE" + o + "]");
                                        var f = $(a + "_MUST" + o + "]");
                                        var h = k.attr("tableid");
                                        if (!Ext.isEmpty(k.attr("tableid"))) {
                                            if ($("#CNOA_WF_STEP_HIDE" + h).attr("checked")) {
                                                continue
                                            }
                                        }
                                        k.attr("disabled", false);
                                        f.attr("disabled", false);
                                        k.attr("checked", true);
                                        f.attr("checked", true)
                                    }
                                }
                            }
                        }
                    },
                    {
                        text: lang("cancelMandatory"),
                        handler: function(g) {
                            var k = e.mainPanel.getSelectionModel().getSelections();
                            if (k.length == 0) {
                                CNOA.miniMsg.alertShowAt(g, lang("notChoseControlTip"))
                            } else {
                                var h = "";
                                for (var f = 0; f < k.length; f++) {
                                    var m = k[f].get("id");
                                    var j = $(a + "_MUST" + m + "]");
                                    if (j.attr("checked")) {
                                        $(a + "_MUST" + m + "]").attr("checked", false)
                                    }
                                }
                            }
                        }
                    },
                    "->", '<span class="cnoa_color_red">' + lang("holdDownCtrlShiftDuo") + "</span>"]
            })
        })
    },
    loadData: function() {
        var c = this;
        var a = "#" + this.ID_GRID + " input[type=checkbox][id=CNOA_WF_STEP";
        var b = "#" + this.ID_GRID + " input[type=radio][name=CNOA_WF_STEP_SHOWHIDE";
        Ext.each(this.formData,
            function(d, e) {
                if (d.show == 1) {
                    $(b + d.id + "][value=show]").attr("checked", true);
                    $(a + "_WRITE" + d.id + "]").attr("disabled", false);
                    $(a + "_MUST" + d.id + "]").attr("disabled", false)
                } else {
                    $(b + d.id + "][value=hide]").attr("checked", true);
                    $(a + "_WRITE" + d.id + "]").attr("disabled", true);
                    $(a + "_MUST" + d.id + "]").attr("disabled", true)
                }
                if (d.write == 1) {
                    $(a + "_WRITE" + d.id + "]").attr("checked", true)
                }
                if (d.must == 1) {
                    $(a + "_WRITE" + d.id + "]").attr("checked", true);
                    $(a + "_MUST" + d.id + "]").attr("checked", true)
                }
                if ((d.otype == "detailtable") && (d.hide == "1")) {
                    $("div[tableid=" + d.id + "] input").attr("disabled", true)
                }
            })
    },
    mkShowHide: function(d, h, b) {
        var g = b.data;
        var a = g.otype;
        var f = g.tableid;
        var e = '<div id="WF_FLOWDESIGN_STEP_FIELD_CT1_' + d + '" tableid="' + f + '">';
        e += '<input type="radio" otype="' + a + '" checked="checked" value="show" name="CNOA_WF_STEP_SHOWHIDE' + d + '" onclick="CNOA_wf_set_flow_step.fields.checkboxClick(this, \'' + d + "', 'show')\" id=\"CNOA_WF_STEP_SHOW" + d + '" /><label for="CNOA_WF_STEP_SHOW' + d + '">' + lang("visible") + "</label>";
        e += "&nbsp;&nbsp;&nbsp;";
        e += '<input type="radio" otype="' + a + '" value="hide" name="CNOA_WF_STEP_SHOWHIDE' + d + '" onclick="CNOA_wf_set_flow_step.fields.checkboxClick(this, \'' + d + "', 'hide')\" id=\"CNOA_WF_STEP_HIDE" + d + '" /><label for="CNOA_WF_STEP_HIDE' + d + '">保密</label>';
        e += "</div>";
        return e
    },
    mkOpt: function(d, j, b) {
        var g = b.data,
            a = g.otype,
            f = g.tableid,
            h = "";
        var e = '<div id="WF_FLOWDESIGN_STEP_FIELD_CT2_' + d + '" tableid="' + f + '">';
        switch (a) {
            case "textfield":
            case "textarea":
            case "signature":
                h = lang("canFill");
                break;
            case "radio":
            case "select":
            case "checkbox":
            case "choice":
            case "datasource":
                h = lang("available");
                break;
            case "attach":
                h = lang("available");
                break;
            case "abutment":
                h = lang("available");
                break;
            case "macro":
                h = lang("binding");
                break;
            case "detailtable":
                h = "[" + lang("add") + " / " + lang("removeLine") + "] / [" + lang("available") + "]";
                break
        }
        if (CNOA.common.in_array(a, ["textfield", "textarea", "radio", "select", "checkbox", "choice", "macro", "detailtable", "signature", "datasource", "attach", "abutment"])) {
            if (g.dataType != "huiqian") {
                e += '<input type="checkbox" tableid="' + f + '" onclick="CNOA_wf_set_flow_step.fields.checkboxClick(this, \'' + d + "', 'write')\" id=\"CNOA_WF_STEP_WRITE" + d + '" /><label for="CNOA_WF_STEP_WRITE' + d + '">' + h + "</label>";
                e += "&nbsp;&nbsp;&nbsp;"
            }
        }
        if (CNOA.common.in_array(a, ["textfield", "textarea", "radio", "select", "choice", "checkbox", "signature", "attach", "abutment"])) {
            e += '<input type="checkbox" tableid="' + f + '" onclick="CNOA_wf_set_flow_step.fields.checkboxClick(this, \'' + d + "', 'must')\" id=\"CNOA_WF_STEP_MUST" + d + '" /><label for="CNOA_WF_STEP_MUST' + d + '">' + lang("isRequire") + "</label>"
        }
        e += "</div>";
        return e
    },
    mkTypename: function(g, j, b) {
        var h = this;
        var f = b.data,
            a = f.otype,
            e = f.table;
        var d = "";
        d = (Ext.isEmpty(e) ? "": "[" + lang("form") + ":<span style='color:red'>" + e + "</span>] ");
        switch (a) {
            case "textfield":
                d += "<span style='color:#422D55'>" + lang("singleLine") + "</span>";
                break;
            case "textarea":
                d += "<span style='color:#660000'>" + lang("multiLine") + "</span>";
                break;
            case "radio":
                d += "<span style='color:green'>" + lang("radioBox") + "</span>";
                break;
            case "checkbox":
                d += "<span style='color:#C46200'>" + lang("checkBox") + "</span>";
                break;
            case "macro":
                d += "<span style='color:#0080C0'>" + lang("macroControl") + "</span>";
                break;
            case "calculate":
                d += "<span style='color:#FF00FF'>" + lang("calculateControl") + "</span>";
                break;
            case "detailtable":
                d += "<span style='color:#FF0000'>" + lang("detailForm") + "</span>";
                break;
            case "select":
                d += "<span style='color:#FF9900'>" + lang("dropDownList") + "</span>";
                break;
            case "choice":
                d += "<span style='color:#0000FF'>" + lang("selector") + "</span>";
                break;
            case "signature":
                d += "<span style='color:#AD5A5A'>" + lang("signature1") + "</span>";
                break;
            case "datasource":
                d += "<span style='color:#AD5A5A'>" + lang("dateSource") + "</span>";
                break;
            case "attach":
                d += "<span style='color:#AD5A5A'>附件上传</span>";
                break;
            case "abutment":
                d += "<span style='color:#AD5A5A'>SQL选择器</span>";
                break;
            default:
                d += "<span style='color:gray'>----</span>";
                break
        }
        return d
    },
    checkboxClick: function(d, e, c) {
        var a = "#" + this.ID_GRID + " input[type=checkbox][id=CNOA_WF_STEP";
        var b = "#" + this.ID_GRID + " input[type=radio][name=CNOA_WF_STEP_SHOWHIDE";
        switch (c) {
            case "show":
                $(a + "_WRITE" + e + "]").attr("disabled", false);
                $(a + "_MUST" + e + "]").attr("disabled", false);
                if ($(d).attr("otype") == "detailtable") {
                    $("div[tableid=" + e + "] input").attr("disabled", false)
                }
                break;
            case "hide":
                $(a + "_WRITE" + e + "]").attr("checked", false);
                $(a + "_MUST" + e + "]").attr("checked", false);
                $(a + "_WRITE" + e + "]").attr("disabled", true);
                $(a + "_MUST" + e + "]").attr("disabled", true);
                if ($(d).attr("otype") == "detailtable") {
                    $("div[tableid=" + e + "] input").attr("disabled", true)
                }
                break;
            case "write":
                if (!d.checked) {
                    $(a + "_MUST" + e + "]").attr("checked", false)
                }
                break;
            case "must":
                if (d.checked) {
                    $(a + "_WRITE" + e + "]").attr("checked", true)
                }
                break
        }
    },
    getData: function() {
        var f = clone(this.flowFields);
        var a = "#" + this.ID_GRID + " input[type=checkbox][id=CNOA_WF_STEP";
        for (var d = 0; d < f.length; d++) {
            var c = f[d];
            var b = $("#" + this.ID_GRID + " input:radio[name=CNOA_WF_STEP_SHOWHIDE" + c.id + "]:checked").val();
            f[d].show = b == "show" ? "1": "0";
            f[d].hide = b == "hide" ? "1": "0";
            var g = $(a + "_WRITE" + c.id + "]");
            f[d].write = g.attr("checked") ? "1": "0";
            var e = $(a + "_MUST" + c.id + "]");
            f[d].must = e.attr("checked") ? "1": "0"
        }
        return f
    }
};
var CNOA_wf_set_flow_step_userClass, CNOA_wf_set_flow_step_user;
CNOA_wf_set_flow_step_userClass = CNOA.Class.create();
CNOA_wf_set_flow_step_userClass.prototype = {
    init: function(j) {
        var e = this;
        this.baseUrl = "index.php?app=wf&func=flow&action=set&modul=flow";
        this.parent = j;
        this.dealWay = this.parent.stepData.dealWay;
        this.formData = this.parent.stepData.user;
        this.initRuleFields();
        this.ID_DEAL = Ext.id();
        var b = {
            xtype: "container",
            border: false,
            layout: "table",
            layoutConfig: {
                columns: 3
            },
            items: [{
                xtype: "container",
                layout: "form",
                border: false,
                items: this.userRule
            },
                {
                    xtype: "container",
                    layout: "form",
                    style: "margin-left: 15px;",
                    border: false,
                    items: this.stationRule
                },
                {
                    xtype: "container",
                    layout: "form",
                    style: "margin-left: 15px;",
                    border: false,
                    items: this.departmentRule
                }]
        };
        var g = {
            xtype: "container",
            border: false,
            layout: "table",
            style: "margin-top:10px",
            layoutConfig: {
                columns: 2
            },
            items: [{
                xtype: "container",
                layout: "form",
                border: false,
                items: this.roleRule
            },
                {
                    xtype: "container",
                    layout: "form",
                    style: "margin-left: 15px;",
                    border: false,
                    items: this.kongRule
                }]
        };
        var h = {
            xtype: "container",
            border: false,
            layout: "table",
            style: "margin-top:10px",
            layoutConfig: {
                columns: 1
            },
            items: [{
                xtype: "container",
                layout: "form",
                border: false,
                items: this.deptStationRule
            }]
        };
        var f = {
            xtype: "container",
            border: false,
            layout: "table",
            style: "margin-top:10px",
            layoutConfig: {
                columns: 1
            },
            items: [{
                xtype: "container",
                layout: "form",
                border: false,
                items: this.excludeRule
            }]
        };
        var c = {
            xtype: "fieldset",
            title: "办理方式",
            style: "margin:0 5px 5px 5px",
            items: [{
                xtype: "checkbox",
                boxLabel: "多人办理<span style='color: red;'>(注:其中一个人办理就可以转下一步)</span>",
                id: this.ID_DEAL,
                inputValue: 1,
                name: "deal"
            }]
        };
        var a;
        if (this.parent.base.stepType == "startNode") {
            var d = {
                xtype: "displayfield",
                value: '<span class="cnoa_color_red">' + lang("setRoleOnlyRoles") + "</span>"
            };
            a = [b, h, f, d];
            var c = {}
        } else {
            a = [b, g, f, h]
        }
        this.mainPanel = new Ext.form.FormPanel({
            title: lang("attnRole"),
            labelAlign: "top",
            labelWidth: 60,
            border: false,
            waitMsgTarget: true,
            autoScroll: true,
            items: [{
                xtype: "fieldset",
                title: lang("characterSetup"),
                style: "margin:5px",
                items: a
            },
                c],
            listeners: {
                afterrender: function() {
                    e.loadData()
                },
                activate: function() {
                    e.parent.userClick = true
                }
            }
        })
    },
    initRuleFields: function() {
        var a = this;
        this.userRule = new CNOA.selector.form.MultiFunctionSelectorField({
            fieldLabel: lang("people"),
            width: 200,
            height: 100,
            userButton: true,
            userValuePre: "",
            userTextPre: ""
        });
        this.stationRule = new CNOA.selector.form.MultiFunctionSelectorField({
            fieldLabel: lang("station"),
            width: 200,
            height: 100,
            stationButton: true,
            stationValuePre: "",
            stationTextPre: ""
        });
        this.departmentRule = new CNOA.selector.form.MultiFunctionSelectorField({
            xtype: "multifunctionselectorfield",
            fieldLabel: lang("department"),
            width: 200,
            height: 100,
            departmentButton: true,
            departmentValuePre: "",
            departmentTextPre: ""
        });
        this.roleRule = new CNOA.form.ListViewSelectField({
            fieldLabel: lang("roleRule"),
            width: 415,
            height: 100,
            buttons: [{
                text: lang("addRule"),
                cls: "btn-blue4",
                handler: function() {
                    a.showRoleSelector()
                }
            }]
        });
        this.kongRule = new CNOA.form.ListViewSelectField({
            fieldLabel: lang("personSelectNameMacro"),
            width: 200,
            height: 100,
            buttons: [{
                text: "添加控件",
                cls: "btn-blue4",
                handler: function() {
                    a.showKongListSeletor()
                }
            }]
        });
        this.deptStationRule = new CNOA.form.ListViewSelectField({
            fieldLabel: lang("department") + "-" + lang("station"),
            width: 415,
            height: 100,
            buttons: [{
                text: lang("addRule"),
                cls: "btn-blue4",
                handler: function() {
                    a.showDeptStationSelector()
                }
            }]
        });
        this.excludeRule = new CNOA.selector.form.MultiFunctionSelectorField({
            fieldLabel: "排除人员",
            width: 200,
            height: 100,
            userButton: true,
            userValuePre: "",
            userTextPre: ""
        })
    },
    showRoleSelector: function() {
        var h = this;
        var e = function(m) {
            var k = h.roleRule.getRecordData();
            for (var j = 0; j < k.length; j++) {
                if (k[j].people != m.people) {
                    continue
                }
                if (m.people == "faqiself" || m.people == "beforepeop" || (k[j].dept == m.dept && k[j].id == m.id)) {
                    return true
                }
            }
            return false
        };
        var d = new CNOA.selector.form.MultiFunctionSelectorField({
            fieldLabel: lang("station"),
            width: 312,
            height: 100,
            stationButton: true,
            stationValuePre: ""
        });
        var g = new Ext.form.ComboBox({
            fieldLabel: lang("people"),
            width: 100,
            mode: "local",
            triggerAction: "all",
            forceSelection: true,
            editable: false,
            valueField: "value",
            displayField: "name",
            store: new Ext.data.SimpleStore({
                fields: ["value", "name"],
                data: [["faqi", "发起人"], ["zhuban", "主办人"], ["faqiself", "发起人自己"], ["beforepeop", "所有已办理人"]]
            }),
            value: "faqi",
            listeners: {
                select: function(k, j) {
                    if (j.get("value") == "faqiself" || j.get("value") == "beforepeop") {
                        a.disable();
                        d.disable()
                    } else {
                        a.enable();
                        d.enable()
                    }
                }
            }
        });
        var a = new Ext.form.ComboBox({
            fieldLabel: lang("department"),
            width: 200,
            mode: "local",
            triggerAction: "all",
            forceSelection: true,
            editable: false,
            valueField: "value",
            displayField: "name",
            store: new Ext.data.SimpleStore({
                fields: ["value", "name"],
                data: [["myDept", lang("belongDept")], ["upDept", lang("upperDepartment")], ["myUpDept", lang("deptAndUpDept")], ["allDept", lang("deptAndAllUpDept")]]
            }),
            value: "myDept"
        });
        var b = [{
            xtype: "container",
            layout: "table",
            padding: 5,
            border: false,
            layoutConfig: {
                columns: 2
            },
            defaults: {
                xtype: "container",
                layout: "form",
                border: false
            },
            items: [{
                items: g
            },
                {
                    style: "margin-left:10px",
                    items: a
                }]
        },
            d];
        var c = new Ext.form.FormPanel({
            padding: 5,
            labelAlign: "top",
            labelWidth: 60,
            border: false,
            waitMsgTarget: true,
            autoScroll: true,
            items: b
        });
        var f = new Ext.Window({
            title: lang("roleRule"),
            border: false,
            width: 350,
            height: 300,
            modal: true,
            layout: "fit",
            items: c,
            buttons: [{
                text: lang("save"),
                handler: function() {
                    var j = g.getValue(),
                        s = g.getRawValue();
                    var m = [];
                    if (j == "faqiself" || j == "beforepeop") {
                        var p = {
                            text: "[" + s + "] ",
                            people: j
                        };
                        if (e(p)) {
                            CNOA.msg.alert(p.text + " " + lang("haveRoleOther"));
                            return
                        } else {
                            m.push(new Ext.data.Record(p))
                        }
                    } else {
                        var k = a.getValue(),
                            q = a.getRawValue();
                        var r = d.getRecordData();
                        if (q == "" || r.length == 0) {
                            CNOA.msg.alert(lang("formValid"));
                            return
                        }
                        for (var n = 0,
                                 o = r.length; n < o; n++) {
                            var p = {
                                text: "[" + s + "]  [" + q + "] [" + r[n].text + "]",
                                id: r[n].id,
                                dept: k,
                                people: j
                            };
                            if (e(p)) {
                                CNOA.msg.alert(p.text + " " + lang("haveRoleOther"));
                                return
                            } else {
                                m.push(new Ext.data.Record(p))
                            }
                        }
                    }
                    h.roleRule.store.add(m);
                    f.close()
                }
            },
                {
                    text: lang("close"),
                    handler: function() {
                        f.close()
                    }
                }]
        }).show()
    },
    showKongListSeletor: function() {
        var f = this;
        var d = [],
            a = [],
            g = this.kongRule.getValue().split(",");
        Ext.each(this.parent.fields.flowFields,
            function(m, n) {
                if (m.dataType == "user_sel" || m.dataType == "loginname") {
                    var k = new Ext.data.Record({
                        id: m.id,
                        name: m.name
                    });
                    d.push(k);
                    if (g.indexOf(m.id) !== -1) {
                        a.push(k)
                    }
                }
            });
        var h = new Ext.data.ArrayStore({
            proxy: new Ext.data.MemoryProxy(),
            fields: [{
                name: "id"
            },
                {
                    name: "name"
                }]
        });
        h.add(d);
        var c = new Ext.grid.CheckboxSelectionModel();
        var j = new Ext.grid.ColumnModel([c, {
            header: lang("controlName"),
            dataIndex: "name",
            width: 200,
            sortable: false,
            menuDisabled: true
        }]);
        var b = new Ext.grid.GridPanel({
            border: false,
            stripeRows: true,
            loadMask: {
                msg: lang("waiting")
            },
            cm: j,
            sm: c,
            store: h,
            listeners: {
                viewready: function(k) {
                    k.selModel.selectRecords(a)
                }
            }
        });
        var e = new Ext.Window({
            title: lang("controlList"),
            width: 350,
            height: 300,
            modal: true,
            layout: "fit",
            items: b,
            buttons: [{
                text: lang("add"),
                handler: function(n) {
                    var o = b.getSelectionModel().getSelections();
                    if (o.length == 0) {
                        CNOA.miniMsg.alertShowAt(n, lang("haveNotChosenControl"))
                    } else {
                        f.kongRule.clearValue();
                        var k = [];
                        for (var m = 0; m < o.length; m++) {
                            k.push({
                                id: o[m].get("id"),
                                text: o[m].get("name")
                            })
                        }
                        f.kongRule.setValue(k);
                        e.close()
                    }
                }
            },
                {
                    text: lang("close"),
                    handler: function() {
                        e.close()
                    }
                }]
        }).show()
    },
    showDeptStationSelector: function() {
        var d = this;
        var a = new CNOA.selector.form.DepartmentSelectorField({
            fieldLabel: lang("department"),
            width: 325,
            multiselect: false,
            allowBlank: false
        });
        var b = new CNOA.selector.form.StationSelectorField({
            fieldLabel: lang("station"),
            width: 325,
            multiselect: false,
            allowBlank: false
        });
        var c = new Ext.Window({
            title: lang("department") + "-" + lang("station"),
            width: 350,
            height: 200,
            padding: 5,
            modal: true,
            layout: "form",
            labelAlign: "top",
            labelWidth: 60,
            bodyStyle: "background-color:#fff",
            items: [a, b],
            buttons: [{
                text: lang("save"),
                handler: function(g) {
                    if (a.getValue() == "" || b.getValue() == "") {
                        CNOA.miniMsg.alertShowAt(g, lang("formValid"));
                        return
                    }
                    var k = a.getValue() + "," + b.getValue(),
                        j = "[" + a.getRawValue() + "] [" + b.getRawValue() + "]",
                        e = d.deptStationRule.getRecordData();
                    for (var f = 0; f < e.length; f++) {
                        if (e[f].id == k) {
                            CNOA.msg.alert(lang("haveRoleOther"));
                            return
                        }
                    }
                    var h = {
                        id: k,
                        text: j
                    };
                    d.deptStationRule.store.add(new Ext.data.Record(h));
                    c.close()
                }
            },
                {
                    text: lang("close"),
                    handler: function() {
                        c.close()
                    }
                }]
        }).show()
    },
    loadData: function() {
        var a = this;
        if (this.parent.stepData.user == undefined) {
            return
        }
        this.userRule.setValue(this.formData.people);
        this.excludeRule.setValue(this.formData.exclude);
        this.stationRule.setValue(this.formData.station);
        this.departmentRule.setValue(this.formData.dept);
        this.roleRule.setValue(this.formData.rule);
        this.kongRule.setValue(this.formData.kong);
        this.deptStationRule.setValue(this.formData.deptStation);
        if (Ext.getCmp(a.ID_DEAL) && this.dealWay) {
            Ext.getCmp(a.ID_DEAL).setValue(this.dealWay.deal)
        }
    },
    getData: function() {
        var a = {
            people: this.userRule.getRecordData(),
            exclude: this.excludeRule.getRecordData(),
            station: this.stationRule.getRecordData(),
            dept: this.departmentRule.getRecordData(),
            rule: this.roleRule.getRecordData(),
            kong: this.kongRule.getRecordData(),
            deptStation: this.deptStationRule.getRecordData()
        };
        return a
    },
    getDealWay: function() {
        var c = this;
        if (Ext.getCmp(c.ID_DEAL)) {
            var b = Ext.getCmp(c.ID_DEAL).getValue();
            var a = {
                deal: b
            }
        } else {
            var a = {}
        }
        return a
    }
};
var CNOA_wf_set_flow_step_conditionClass, CNOA_wf_set_flow_step_condition;
CNOA_wf_set_flow_step_conditionClass = CNOA.Class.create();
CNOA_wf_set_flow_step_conditionClass.prototype = {
    init: function(h) {
        var f = this;
        this.parent = h;
        this.flowFields = h.flowFields;
        this.nextStep = h.base.allNextStep;
        this.formData = this.parent.stepData.condition;
        var c = [];
        this.store = new Ext.data.ArrayStore({
            proxy: new Ext.data.MemoryProxy(c),
            fields: [{
                name: "fieldType"
            },
                {
                    name: "id"
                },
                {
                    name: "text"
                },
                {
                    name: "dataType"
                }]
        });
        var g = new Ext.data.SimpleStore({
            proxy: new Ext.data.MemoryProxy(c),
            fields: [{
                name: "id"
            },
                {
                    name: "text"
                }],
            data: [[1, lang("equal")], [2, lang("notEqual")], [3, lang("greaterThan")], [4, lang("lessThan")], [5, lang("greaterOrEqual")], [6, lang("lessOrEqual")], [7, lang("contains")], [8, lang("notInclude")]]
        });
        this.conditionStore = new Ext.data.ArrayStore({
            proxy: new Ext.data.MemoryProxy(c),
            fields: [{
                name: "id"
            },
                {
                    name: "text"
                }]
        });
        var d = [{
            dataType: "system",
            id: "s|n_n",
            text: "[发起人姓名]"
        },
            {
                dataType: "system",
                id: "s|n_s",
                text: "[发起人岗位]"
            },
            {
                dataType: "system",
                id: "s|n_d",
                text: "[发起人所在部门]"
            },
            {
                dataType: "system",
                id: "s|d_n",
                text: "[主办人姓名]"
            },
            {
                dataType: "system",
                id: "s|d_s",
                text: "[主办人岗位]"
            },
            {
                dataType: "system",
                id: "s|d_d",
                text: "[主办人所在部门]"
            }];
        for (var e = 0; e < d.length; e++) {
            var b;
            b = new Ext.data.Record(d[e]);
            this.store.add(b)
        }
        if (this.flowFields.length > 0) {
            for (var e = 0; e < this.flowFields.length; e++) {
                var a = {
                    text: "",
                    id: 0,
                    dataType: ""
                };
                if (this.flowFields[e].from == "normal" && this.flowFields[e].otype != "detailtable") {
                    a.text = this.flowFields[e].name;
                    a.id = this.flowFields[e].id;
                    a.dataType = this.flowFields[e].dataType;
                    var b;
                    b = new Ext.data.Record(a);
                    this.store.add(b)
                }
            }
        }
        this.conBaseField = [];
        this.field = function(m) {
            var k = {
                xtype: "fieldset",
                id: "SET_FLOW_DESIGN_STEP_CONDITION_" + m.stepId,
                style: "margin:5px",
                title: lang("toStep") + "：" + m.stepName,
                items: [{
                    xtype: "panel",
                    layout: "table",
                    border: false,
                    layoutConfig: {
                        columns: 6
                    },
                    defaults: {
                        border: false
                    },
                    items: [{
                        xtype: "panel",
                        layout: "table",
                        layoutConfig: {
                            columns: 2
                        },
                        items: [{
                            xtype: "displayfield",
                            value: "&nbsp;&nbsp;" + lang("controlList") + ":&nbsp;"
                        },
                            {
                                xtype: "combo",
                                store: f.store,
                                width: 135,
                                valueField: "id",
                                displayField: "text",
                                hiddenName: "kongjian" + m.stepId,
                                name: "kongjian" + m.stepId,
                                mode: "local",
                                triggerAction: "all",
                                forceSelection: true,
                                editable: false,
                                listeners: {
                                    select: function(r, n, o) {
                                        var q = f.mainPanel.getForm();
                                        var s = q.findField("ovalue" + m.stepId);
                                        s.setReadOnly(false);
                                        if (n.data.dataType == "int" || n.data.dataType == "float") {
                                            var p = [[1, lang("equal")], [2, lang("notEqual")], [3, lang("greaterThan")], [4, lang("lessThan")], [5, lang("greaterOrEqual")], [6, lang("lessOrEqual")], [7, lang("contains")], [8, lang("notInclude")]];
                                            g.loadData(p)
                                        } else {
                                            var p = [[1, lang("equal")], [2, lang("notEqual")], [7, lang("contains")], [8, lang("notInclude")]];
                                            g.loadData(p);
                                            if (n.data.dataType == "system") {
                                                s.setReadOnly(true);
                                                s.setValue("")
                                            }
                                        }
                                    }
                                }
                            }]
                    },
                        {
                            xtype: "panel",
                            layout: "table",
                            layoutConfig: {
                                columns: 2
                            },
                            items: [{
                                xtype: "displayfield",
                                value: "&nbsp;&nbsp;" + lang("condition") + ": "
                            },
                                {
                                    xtype: "combo",
                                    store: g,
                                    width: 80,
                                    valueField: "id",
                                    displayField: "text",
                                    mode: "local",
                                    triggerAction: "all",
                                    forceSelection: true,
                                    hiddenName: "rule" + m.stepId,
                                    name: "rule" + m.stepId,
                                    editable: false,
                                    value: 1,
                                    listeners: {
                                        select: function(p, n, o) {}
                                    }
                                }]
                        },
                        {
                            xtype: "panel",
                            layout: "table",
                            layoutConfig: {
                                columns: 2
                            },
                            items: [{
                                xtype: "displayfield",
                                value: "&nbsp;&nbsp;&nbsp;" + lang("value") + ": "
                            },
                                {
                                    xtype: "textfield",
                                    width: 80,
                                    name: "ovalue" + m.stepId,
                                    listeners: {
                                        afterrender: function(n) {
                                            n.mon(n.el, "click",
                                                function() {
                                                    var q = f.mainPanel.getForm();
                                                    var r = q.findField("kongjian" + m.stepId);
                                                    var p = "";
                                                    var o = "index.php?app=wf&func=flow&action=set&modul=flow&task=selector&target=";
                                                    switch (r.value) {
                                                        case "s|n_n":
                                                            p = "user";
                                                            break;
                                                        case "s|n_s":
                                                            p = "station";
                                                            break;
                                                        case "s|n_d":
                                                            p = "dept";
                                                            break;
                                                        case "s|d_n":
                                                            p = "user";
                                                            break;
                                                        case "s|d_s":
                                                            p = "station";
                                                            break;
                                                        case "s|d_d":
                                                            p = "dept";
                                                            break;
                                                        default:
                                                            return
                                                    }
                                                    new_selector(p, n, false, false, o + p)
                                                },
                                                this)
                                        }
                                    }
                                }]
                        },
                        {
                            xtype: "panel",
                            layout: "table",
                            layoutConfig: {
                                columns: 2
                            },
                            items: [{
                                xtype: "displayfield",
                                value: "&nbsp;&nbsp;&nbsp;" + lang("correlativity") + ": "
                            },
                                {
                                    xtype: "combo",
                                    store: new Ext.data.SimpleStore({
                                        fields: ["value", "name"],
                                        data: [["or", lang("or")], ["and", lang("and")]]
                                    }),
                                    width: 60,
                                    value: "or",
                                    valueField: "value",
                                    displayField: "name",
                                    mode: "local",
                                    hiddenName: "orAnd" + m.stepId,
                                    name: "orAnd",
                                    triggerAction: "all",
                                    forceSelection: true,
                                    editable: false,
                                    listeners: {
                                        select: function(p, n, o) {}
                                    }
                                }]
                        },
                        {
                            xtype: "panel",
                            layout: "table",
                            layoutConfig: {
                                columns: 2
                            },
                            items: [{
                                xtype: "button",
                                text: "&nbsp;&nbsp;(&nbsp;&nbsp;",
                                name: "left",
                                incid: m.stepId,
                                style: "margin-left:10px",
                                handler: function(q) {
                                    var p = {
                                        text: "",
                                        name: "",
                                        items: [],
                                        left: "",
                                        right: "",
                                        orAnd: ""
                                    };
                                    var o = f.mainPanel.getForm();
                                    var u = o.findField("condition" + q.incid);
                                    var s = u.store.data.items;
                                    var t = u.store.data.items.length;
                                    if (t == 0) {
                                        p.text = "(";
                                        p.left = true
                                    } else {
                                        var n = o.findField("orAnd" + q.incid).getRawValue();
                                        var v = o.findField("orAnd" + q.incid).getValue();
                                        dataLast = s[t - 1].data;
                                        if (dataLast.left == "") {
                                            p.text = n + "(";
                                            p.left = true;
                                            p.orAnd = v
                                        } else {
                                            if (dataLast.right == "" || dataLast.right == undefined) {
                                                CNOA.msg.alert(lang("haveNotAddRight"));
                                                return
                                            } else {
                                                p.text = n + "(";
                                                p.left = true;
                                                p.orAnd = v
                                            }
                                        }
                                    }
                                    var r;
                                    r = new Ext.data.Record(p);
                                    u.store.add(r)
                                }
                            },
                                {
                                    xtype: "button",
                                    text: "&nbsp;&nbsp;)&nbsp;&nbsp;",
                                    name: "right",
                                    incid: m.stepId,
                                    style: "margin-left:10px",
                                    handler: function(p) {
                                        var r = {
                                            text: "",
                                            items: [],
                                            left: "",
                                            right: "",
                                            orAnd: ""
                                        };
                                        var q = f.mainPanel.getForm();
                                        var t = q.findField("condition" + p.incid);
                                        var s = t.store.data.items;
                                        var o = t.store.data.items.length;
                                        if (o == 0) {
                                            CNOA.msg.alert(lang("notAllowAddRight"));
                                            return
                                        } else {
                                            dataLast = s[o - 1].data;
                                            if (dataLast.left == "" || dataLast.left == undefined) {
                                                CNOA.msg.alert(lang("notAllowAddLeft"));
                                                return
                                            } else {
                                                if (dataLast.items.length == 0) {
                                                    CNOA.msg.alert(lang("haveNotAddAny"));
                                                    return
                                                } else {
                                                    if (dataLast.right) {
                                                        CNOA.msg.alert(lang("yJaddRightKuo"));
                                                        return
                                                    } else {
                                                        r.text = dataLast.text + ")";
                                                        r.items = dataLast.items;
                                                        r.right = true;
                                                        r.left = true;
                                                        r.orAnd = dataLast.orAnd;
                                                        var n;
                                                        n = new Ext.data.Record(r);
                                                        t.store.remove(t.store.getAt(o - 1));
                                                        t.store.add(n)
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }]
                        },
                        {
                            xtype: "button",
                            text: lang("add"),
                            style: "margin-left : 10px",
                            incid: m.stepId,
                            handler: function(s) {
                                var r = {
                                    text: "",
                                    items: [],
                                    left: "",
                                    right: "",
                                    orAnd: ""
                                };
                                var q = f.mainPanel.getForm();
                                var n = q.findField("kongjian" + s.incid).getValue();
                                var o = q.findField("kongjian" + s.incid).getRawValue();
                                var z = q.findField("rule" + s.incid).getValue();
                                var y = q.findField("rule" + s.incid).getRawValue();
                                var B = q.findField("ovalue" + s.incid).getValue();
                                var A = q.findField("orAnd" + s.incid).getValue();
                                var p = q.findField("orAnd" + s.incid).getRawValue();
                                if (o == "" || B == "" || y == "") {
                                    CNOA.msg.alert(lang("pleaseSelectName") + "/" + lang("conditionFillValue"));
                                    return
                                }
                                o = "<span class='cnoa_color_blue'>" + o + "</span>";
                                y = "<span class='cnoa_color_green'>" + y + "</span>";
                                var u = q.findField("condition" + s.incid);
                                var w = u.store.data.items.length;
                                if (w != 0) {
                                    var v = u.store.data.items[w - 1].data
                                }
                                B = B;
                                var C = o + y + '<span class="cnoa_color_red">' + B + "</span>";
                                var x = {};
                                x.name = n;
                                x.rule = z;
                                x.ovalue = B;
                                x.orAnd = A;
                                if (w == 0) {
                                    r.text = C;
                                    r.items.push(x);
                                    var t;
                                    t = new Ext.data.Record(r);
                                    u.store.add(t)
                                } else {
                                    if (v.right) {
                                        r.text = p + C;
                                        r.orAnd = v.orAnd;
                                        r.items.push(x);
                                        var t;
                                        t = new Ext.data.Record(r);
                                        u.store.add(t)
                                    } else {
                                        if (v.left) {
                                            if (v.items.length == 0) {
                                                r.text = v.text + C;
                                                r.left = v.left;
                                                r.orAnd = v.orAnd;
                                                v.items.push(x);
                                                r.items = v.items
                                            } else {
                                                r.text = v.text + p + C;
                                                r.left = v.left;
                                                r.orAnd = v.orAnd;
                                                v.items.push(x);
                                                r.items = v.items
                                            }
                                            var t;
                                            t = new Ext.data.Record(r);
                                            u.store.remove(u.store.getAt(w - 1));
                                            u.store.add(t)
                                        } else {
                                            r.text = p + C;
                                            r.orAnd = v.orAnd;
                                            r.items.push(x);
                                            var t;
                                            t = new Ext.data.Record(r);
                                            u.store.add(t)
                                        }
                                    }
                                }
                                q.findField("kongjian" + s.incid).setValue("");
                                q.findField("ovalue" + s.incid).setValue("");
                                q.findField("rule" + s.incid).setValue("");
                                var D = [];
                                g.loadData(D)
                            }
                        }]
                },
                    {
                        xtype: "panel",
                        layout: "table",
                        border: false,
                        layoutConfig: {
                            columns: 2
                        },
                        items: [{
                            xtype: "panel",
                            layout: "form",
                            labelWidth: 60,
                            style: "margin-top : 10px;",
                            border: false,
                            items: [{
                                xtype: "multiselect",
                                fieldLabel: lang("conditionalFormula"),
                                name: "condition" + m.stepId,
                                valueField: "id",
                                displayField: "text",
                                width: 525,
                                height: 100,
                                ddReorder: false,
                                store: new Ext.data.ArrayStore({
                                    proxy: new Ext.data.MemoryProxy(c),
                                    fields: [{
                                        name: "id"
                                    },
                                        {
                                            name: "text"
                                        }]
                                })
                            }]
                        },
                            {
                                xtype: "panel",
                                border: false,
                                style: "margin-left: 8px",
                                items: [{
                                    xtype: "button",
                                    text: lang("delRow"),
                                    incid: m.stepId,
                                    style: "margin-bottom: 10px",
                                    handler: function(p) {
                                        var u = f.mainPanel.getForm().findField("condition" + p.incid);
                                        var v = [];
                                        var t = u.view.getSelectedIndexes();
                                        if (t.length == 0) {
                                            return ""
                                        }
                                        for (var r = 0; r < t.length; r++) {
                                            u.store.remove(u.store.getAt(t[r]))
                                        }
                                        if (u.store.data.items[0] != undefined || u.store.data.items[0] != "") {
                                            try {
                                                var w = u.store.data.items[0].data.text.charAt(0)
                                            } catch(s) {}
                                            if (w == "或" || w == "与") {
                                                var n = u.store.data.items;
                                                u.store.removeAll();
                                                for (var r = 0; r < n.length; r++) {
                                                    if (r == 0) {
                                                        var x = n[r].data.text.substr(1)
                                                    } else {
                                                        var x = n[r].data.text
                                                    }
                                                    var o = {
                                                        text: x,
                                                        items: n[r].data.items,
                                                        left: n[r].data.left,
                                                        right: n[r].data.right,
                                                        orAnd: n[r].data.orAnd
                                                    };
                                                    var q;
                                                    q = new Ext.data.Record(o);
                                                    u.store.add(q)
                                                }
                                            }
                                        }
                                    }
                                },
                                    {
                                        xtype: "button",
                                        text: lang("clear"),
                                        incid: m.stepId,
                                        handler: function(o) {
                                            var n = f.mainPanel.getForm().findField("condition" + o.incid);
                                            CNOA.msg.cf(lang("sureWantClearTJ"),
                                                function(p) {
                                                    if (p == "yes") {
                                                        n.store.removeAll()
                                                    }
                                                })
                                        }
                                    }]
                            }]
                    }]
            };
            return k
        };
        var j = true;
        this.mainPanel = new Ext.form.FormPanel({
            title: lang("setCondition"),
            labelAlign: "right",
            hideLabel: true,
            border: false,
            waitMsgTarget: true,
            autoScroll: true,
            tbar: [lang("reminContrilWeek")],
            listeners: {
                activate: function(n) {
                    if (j) {
                        for (var m = 0; m < f.nextStep.length; m++) {
                            var k = Ext.getCmp("next_node_step_id_" + f.nextStep[m].stepId);
                            if (k.checked && k.nodeType != "childNode") {
                                if (f.mainPanel.getForm().findField("condition" + f.nextStep[m].stepId) == undefined) {
                                    f.mainPanel.add(f.field(f.nextStep[m]))
                                }
                            } else {}
                        }
                        f.mainPanel.doLayout();
                        f.loadData();
                        j = false
                    }
                    f.parent.conditionClick = true
                },
                deactivate: function(k) {
                    j = true
                }
            }
        })
    },
    loadData: function() {
        var k = this;
        var d = this.formData;
        if (d == undefined) {
            return
        }
        for (var h = 0; h < d.length; h++) {
            var a = d[h];
            var b = a.items;
            try {
                var n = k.mainPanel.getForm().findField("condition" + a.id);
                n.store.removeAll();
                for (var f = 0; f < b.length; f++) {
                    if (b[f].text == "" || b[f].text == undefined) {
                        continue
                    }
                    var g = {};
                    g.text = b[f].text;
                    g.left = b[f].left;
                    g.right = b[f].right;
                    g.orAnd = b[f].orAnd;
                    g.items = b[f].items;
                    var c;
                    c = new Ext.data.Record(g);
                    n.store.add(c)
                }
            } catch(m) {}
        }
    },
    getData: function() {
        var f = this;
        var k = [];
        for (var d = 0; d < this.nextStep.length; d++) {
            var c = {
                id: "",
                items: []
            };
            var a = this.nextStep[d];
            try {
                var m = f.mainPanel.getForm().findField("condition" + a.stepId).store.data.items
            } catch(g) {
                continue
            }
            var h = [];
            for (var b = 0; b < m.length; b++) {
                h.push(m[b].data)
            }
            c.id = this.nextStep[d].stepId;
            c.items = h;
            k.push(c)
        }
        return k
    }
};
var CNOA_wf_set_flow_stepClass, CNOA_wf_set_flow_step;
CNOA_wf_set_flow_stepClass = CNOA.Class.create();
CNOA_wf_set_flow_stepClass.prototype = {
    init: function(b, a, k, j) {
        var e = this;
        this.stepData = CNOA_wf_set_flow_design_main.data.steps["step_" + b.id];
        this.stepData = this.stepData || {};
        this.nowNode = b;
        this.tplSort = j;
        this.flowFields = CNOA_wf_set_flow_design_main.flowFields;
        var f = [];
        this.base = new CNOA_wf_set_flow_step_baseClass(this, b, a, k);
        f.push(this.base.mainPanel);
        if (b.nodeType != "bNode" && b.nodeType != "childNode") {
            if (this.tplSort == 0 || this.tplSort == 3) {
                this.fields = new CNOA_wf_set_flow_step_fieldsClass(this);
                f.push(this.fields.mainPanel)
            }
            this.user = new CNOA_wf_set_flow_step_userClass(this);
            f.push(this.user.mainPanel);
            this.condition = new CNOA_wf_set_flow_step_conditionClass(this);
            f.push(this.condition.mainPanel)
        }
        if (b.nodeType == "cNode") {
            this.convergence = new CNOA_wf_set_flow_convergenceClass(this, b, a);
            f.push(this.convergence.mainPanel)
        }
        if (b.nodeType == "childNode") {
            this.child = new CNOA_wf_set_flow_childClass(this, b, a, k);
            this.child_permit = new CNOA_wf_set_flow_child_permitClass(this, b, a, k);
            f.push(this.child.mainPanel);
            f.push(this.child_permit.mainPanel)
        }
        this.fieldClick = false;
        this.userClick = false;
        this.conditionClick = false;
        this.convergenceClick = false;
        this.childClick = false;
        this.tabPanel = new Ext.TabPanel({
            region: "center",
            border: false,
            activeTab: 0,
            deferredRender: false,
            items: f
        });
        var d = Ext.getBody().getBox();
        var m = d.width - 60;
        var c = d.height - 70;
        var g = [{
            text: lang("ok"),
            handler: function() {
                e.submit()
            }
        }];
        if (this.tplSort == 0 || this.tplSort == 3) {
            g.push({
                text: lang("viewForm"),
                cls: "btn-blue4",
                iconCls: "document-html",
                hidden: (this.tplSort == 0 || this.tplSort == 3) ? false: true,
                handler: function(h) {
                    CNOA_wf_set_flow_design_main.viewForm(CNOA_wf_set_flow_design_main.flowId)
                }
            })
        }
        g.push({
            text: lang("close"),
            handler: function() {
                e.mainPanel.close()
            }
        });
        this.mainPanel = new Ext.Window({
            title: lang("setStep"),
            width: 800,
            height: c,
            layout: "border",
            modal: true,
            items: [this.tabPanel],
            listeners: {
                afterrender: function() {}
            },
            buttons: g
        }).show()
    },
    close: function() {
        this.mainPanel.close()
    },
    submit: function() {
        this.stepData.base = this.base.getData();
        if (this.user) {
            this.dealWay = this.user.getDealWay()
        }
        if (this.child_permit) {
            if (!this.fieldClick) {
                var a = new CNOA_wf_set_flow_step_fieldsClass(this);
                this.stepData.fields = a.getData()
            }
            this.childPermit = this.child_permit.getData()
        }
        if (this.stepData.base === false) {
            return false
        }
        if (this.fieldClick) {
            this.stepData.fields = this.fields.getData()
        }
        if (this.userClick) {
            this.stepData.user = this.user.getData()
        }
        if (this.conditionClick) {
            this.stepData.condition = this.condition.getData()
        }
        if (this.convergenceClick) {
            this.stepData.convergence = this.convergence.getData()
        }
        if (this.childClick) {
            this.stepData.child = this.child.getData()
        }
        if (this.dealWay) {
            this.stepData.dealWay = this.dealWay
        }
        if (this.childPermit) {
            this.stepData.childPermit = this.childPermit
        }
        CNOA_wf_set_flow_design_main.setNode(this.stepData);
        CNOA_wf_set_flow_design_main.nodeClick(this.nowNode);
        this.close()
    }
};
var CNOA_wf_set_flow_design_mainClass, CNOA_wf_set_flow_design_main;
CNOA_wf_set_flow_design_mainClass = CNOA.Class.create();
CNOA_wf_set_flow_design_mainClass.prototype = {
    init: function(a, j, b) {
        var f = this;
        this.baseUrl = "workFlow";
        this.designer = null;
        this.flowId = a;
        this.tplSort = j;
        this.stepWindow = null;
        this.data = {
            flowXml: "",
            steps: {}
        };
        this.flowFields = null;
        var d = Ext.getBody().getBox();
        var k = d.width - 10;
        var e = d.height - 10;
        var c = Ext.id();
        this.treeRoot = new Ext.tree.TreeNode({
            expanded: true
        });
        this.treePanel = new Ext.tree.TreePanel({
            region: "east",
            width: 180,
            minWidth: 180,
            maxWidth: 380,
            split: true,
            title: lang("stepOverview"),
            root: this.treeRoot,
            border: false,
            bodyStyle: "border-left-width: 1px;",
            id: "flow_flow_design_summary",
            rootVisible: false,
            autoScroll: true
        });
        this.centerPanel = new Ext.Panel({
            border: false,
            region: "center",
            autoScroll: false,
            html: '<iframe scrolling="auto" frameborder="0" width="100%" height="100%" id="' + c + '"></iframe>'
        });
        var g = [{
            text: lang("save"),
            handler: function() {
                f.submit({
                    close: false
                })
            }
        },
            {
                text: lang("saveAndClose"),
                cls: "btn-blue4",
                handler: function() {
                    f.submit({
                        close: true
                    })
                }
            }];
        if (this.tplSort == 0 || this.tplSort == 3) {
            g.push({
                text: lang("viewForm"),
                cls: "btn-blue4",
                iconCls: "document-html",
                hidden: (this.tplSort == 0 || this.tplSort == 3) ? false: true,
                handler: function(h) {
                    CNOA_wf_set_flow_design_main.viewForm(CNOA_wf_set_flow_design_main.flowId)
                }
            })
        }
        g.push({
            text: lang("close"),
            handler: function() {
                f.mainPanel.close()
            }
        });
        this.mainPanel = new Ext.Window({
            title: lang("designFlow1") + " - 《" + b + "》",
            width: k,
            height: e,
            shadow: false,
            layout: "border",
            modal: true,
            maximizable: true,
            items: [this.centerPanel, this.treePanel],
            buttons: g,
            listeners: {
                show: function(m) {
                    var h = Ext.getDom(c).contentWindow;
                    //h.location.href = "cnoa/scripts/wfDesigner?r=" + Math.random()
                    h.location.href = "workFlow/flowDesigner"
                },
                close: function() {
                    try {
                        Ext.getDom(c).src = ""
                    } catch(h) {}
                }
            }
        }).show();
        this.loadFlowDesignData()
    },
    close: function() {
        this.mainPanel.close()
    },
    submit: function(a) {
        var c = this;
        var b = this.designer.checkFlow();
        if (b !== true) {
            CNOA.msg.alert(b,
                function() {});
            return
        }
        this.data.flowXml = this.getFlowXml();
        this.data.flowHtml5 = "";
        Ext.Ajax.request({
            url: this.baseUrl + "&task=submitFlowDesignData",
            method: "POST",
            params: {
                data: Ext.encode(this.data),
                flowId: this.flowId
            },
            success: function(e) {
                var d = Ext.decode(e.responseText);
                if (d.success === true) {
                    CNOA.msg.notice2(d.msg);
                    if (a.close) {
                        c.close()
                    }
                } else {
                    CNOA.msg.alert(d.msg,
                        function() {})
                }
            }
        })
    },
    loadFlowDesignData: function() {
        var a = this;
        Ext.Ajax.request({
            url: this.baseUrl + "/loadFlowDesignData",
            method: "POST",
            params: {
                flowId: this.flowId
            },
            success: function(c) {
                var b = Ext.decode(c.responseText);
                if (b.success === true) {
                    a.flowFields = b.data.fields;
                    a.setFlowXml(b.data.flowXml);
                    Ext.each(b.data.steps,
                        function(d, e) {
                            a.data.steps["step_" + d.stepId] = {};
                            a.data.steps["step_" + d.stepId].base = d;
                            if (a.tplSort == 0 || a.tplSort == 3) {
                                a.data.steps["step_" + d.stepId].fields = d.fields
                            }
                            a.data.steps["step_" + d.stepId].user = d.user;
                            a.data.steps["step_" + d.stepId].condition = d.condition;
                            a.data.steps["step_" + d.stepId].child = d.child;
                            a.data.steps["step_" + d.stepId].dealWay = d.dealWay.dealWay[0];
                            a.data.steps["step_" + d.stepId].childPermit = d.childPermit
                        })
                } else {
                    CNOA.msg.alert(lang("failedLoad"),
                        function() {
                            a.close()
                        })
                }
            }
        })
    },
    getFlowXml: function() {
        return this.designer.getXml()
    },
    setFlowXml: function(a) {
        var c = this;
        var b = setInterval(function() {
                try {
                    c.designer.setXml(a);
                    clearInterval(b)
                } catch(d) {}
            },
            50)
    },
    openSetNodeWindow: function(b, c, a) {
        this.stepWindow = CNOA_wf_set_flow_step = new CNOA_wf_set_flow_stepClass(b, c, a, this.tplSort)
    },
    setNode: function(a) {
        this.data.steps["step_" + a.base.stepId] = a;
        this.setNodeToDesigner(a.base)
    },
    setNodeToDesigner: function(b) {
        var a = {};
        a.nodeName = b.stepName;
        a.linkTo = b.nextStep;
        this.designer.setNodeCallBack(a)
    },
    nodeClick: function(g) {
        var h = this;
        var f = function(E, e) {
            return new Ext.tree.TreeNode({
                iconCls: e ? "icon-none": "icon-fileview-column2",
                text: E,
                expanded: true
            })
        };
        var d = this.data.steps["step_" + g.id];
        if (!d) {
            return
        }
        var B = f("<b>" + lang("stepName") + "</b>", false);
        var y = f("<span style='color:#616161'>" + d.base.stepName + "</span>", true);
        var c = d.base;
        if (g.nodeType == "node") {
            var t = f("<b>" + lang("handleType") + "</b>", false);
            var q = f("<b>" + lang("allowPoerator") + "</b>", false)
        }
        if (g.nodeType != "endNode") {
            var o = f("<b>" + lang("attachPermiss") + "</b>", false)
        }
        if (g.nodeType == "node") {
            var n = f("<b>" + lang("processTime") + "</b>", false);
            var k = f("<b>" + lang("remiderAdvance") + "</b>", false);
            var j = f("<b>" + lang("urgingHanRemind") + "</b>", false)
        }
        if (g.nodeType == "node") {
            var b = lang("undefinition");
            if (c.doBtnText == "1") {
                b = lang("agree")
            } else {
                if (c.doBtnText == "2") {
                    b = lang("approval")
                } else {
                    if (c.doBtnText == "3") {
                        b = lang("approval2")
                    } else {
                        if (c.doBtnText == "4") {
                            b = lang("confirm")
                        } else {
                            if (c.doBtnText == "5") {
                                b = lang("opt")
                            } else {
                                if (c.doBtnText == "6") {
                                    b = lang("archive")
                                } else {
                                    if (c.doBtnText == "7") {
                                        b = lang("readed")
                                    }
                                }
                            }
                        }
                    }
                }
            }
            t.appendChild(f("<span style='color:#616161'>" + b + "</span>", true))
        }
        if (g.nodeType == "node") {
            if (c.allowReject == "on") {
                q.appendChild(f("<span style='color:#616161'>" + lang("reject") + "</span>", true))
            }
            if (c.allowHuiqian == "on") {
                q.appendChild(f("<span style='color:#616161'>" + lang("countersigned") + "</span>", true))
            }
            if (c.allowTuihui == "on") {
                q.appendChild(f("<span style='color:#616161'>" + lang("sendBack") + "</span>", true))
            }
        }
        if (g.nodeType != "endNode") {
            if (c.allowAttachAdd == "on") {
                o.appendChild(f("<span style='color:#616161'>" + lang("addAttach") + "</span>", true))
            }
        }
        if (g.nodeType == "node") {
            if (c.allowAttachView == "on") {
                o.appendChild(f("<span style='color:#616161'>" + lang("viewAttch") + "</span>", true))
            }
            if (c.allowAttachEdit == "on") {
                o.appendChild(f("<span style='color:#616161'>" + lang("editAttach") + "</span>", true))
            }
            if (c.allowAttachDelete == "on") {
                o.appendChild(f("<span style='color:#616161'>" + lang("removeAttachment") + "</span>", true))
            }
            if (c.allowAttachDown == "on") {
                o.appendChild(f("<span style='color:#616161'>" + lang("downloadAttachment") + "</span>", true))
            }
        }
        if (g.nodeType == "node") {
            n.appendChild(f("<span style='color:#616161'>" + c.stepTime + lang("hour") + "</span>", true));
            k.appendChild(f("<span style='color:#616161'>" + c.urgeBefore + lang("minute") + "</span>", true));
            if (c.urgeTarget == "1") {
                j.appendChild(f("<span style='color:#616161'>" + lang("initiator") + "</span>", true))
            } else {
                if (c.urgeTarget == "2") {
                    j.appendChild(f("<span style='color:#616161'>" + lang("sponsor") + "</span>", true))
                } else {
                    if (c.urgeTarget == "3") {
                        j.appendChild(f("<span style='color:#616161'>" + lang("initiator") + "/" + lang("sponsor") + "</span>", true))
                    } else {
                        if (c.urgeTarget == "4") {
                            j.appendChild(f("<span style='color:#616161'>" + lang("notNotice") + "</span>", true))
                        }
                    }
                }
            }
        }
        B.appendChild(y);
        if (g.nodeType == "node") {
            B.appendChild(t);
            B.appendChild(q)
        }
        if (g.nodeType != "endNode") {
            B.appendChild(o)
        }
        if (g.nodeType == "node") {
            B.appendChild(n);
            B.appendChild(k);
            B.appendChild(j)
        }
        if (h.tplSort == 0 || this.tplSort == 3) {
            var a = f("<b>" + lang("formFields") + "</b>", false);
            var D = f("<b>" + lang("noCustomField") + "</b>", false);
            var A = f("<b>" + lang("visible") + "</b>", false);
            var x = f("<b>" + lang("secret") + "</b>", false);
            var v = f("<b>" + lang("canFill") + "</b>", false);
            var s = f("<b>" + lang("canBeEdit") + "</b>", false);
            var p = f("<b>" + lang("isRequire") + "</b>", false);
            if (!d.fields) {
                Ext.each(d.fields,
                    function(e, E) {
                        D.appendChild(f("<span style='color:#616161'>" + e.name + "</span>", true))
                    })
            } else {
                Ext.each(d.fields,
                    function(e, E) {
                        if (e.must == 1) {
                            p.appendChild(f("<span style='color:#616161'>" + e.name + "</span>", true))
                        } else {
                            if (e.write == 1) {
                                v.appendChild(f("<span style='color:#616161'>" + e.name + "</span>", true))
                            } else {
                                if (e.edit == 1) {
                                    s.appendChild(f("<span style='color:#616161'>" + e.name + "</span>", true))
                                } else {
                                    if (e.show == 1) {
                                        A.appendChild(f("<span style='color:#616161'>" + e.name + "</span>", true))
                                    } else {
                                        if (e.hide == 1) {
                                            x.appendChild(f("<span style='color:#616161'>" + e.name + "</span>", true))
                                        } else {
                                            D.appendChild(f("<span style='color:#616161'>" + e.name + "</span>", true))
                                        }
                                    }
                                }
                            }
                        }
                    })
            }
            a.appendChild(A);
            a.appendChild(x);
            a.appendChild(v);
            a.appendChild(s);
            a.appendChild(p);
            a.appendChild(D)
        }
        var C = f("<b>" + lang("attnRole") + "</b>", false);
        var z = f("<b>" + lang("roleRule") + "</b>", false);
        var w = f("<b>" + lang("people") + "</b>", false);
        var u = f("<b>" + lang("station") + "</b>", false);
        var r = f("<b>" + lang("department") + "</b>", false);
        if (d.user != undefined) {
            Ext.each(d.user.dept,
                function(e, E) {
                    r.appendChild(f("<span style='color:#616161'>" + e.text + "</span>", true))
                });
            Ext.each(d.user.people,
                function(e, E) {
                    w.appendChild(f("<span style='color:#616161'>" + e.text + "</span>", true))
                });
            Ext.each(d.user.rule,
                function(e, E) {
                    z.appendChild(f("<span style='color:#616161'>" + e.text + "</span>", true))
                });
            Ext.each(d.user.station,
                function(e, E) {
                    u.appendChild(f("<span style='color:#616161'>" + e.text + "</span>", true))
                })
        }
        C.appendChild(z);
        C.appendChild(w);
        C.appendChild(u);
        C.appendChild(r);
        if (g.nodeType == "startNode") {
            try {
                if ((d.user.people.length == 0) && (d.user.dept.length == 0) && (d.user.station.length == 0)) {
                    C.childNodes = [];
                    C.appendChild(f("<span style='color:#616161'>" + lang("anyPerson") + "</span>", true))
                }
            } catch(m) {}
        }
        h.treeRoot = new Ext.tree.TreeNode({
            expanded: true
        });
        if (g.nodeType == "endNode") {
            h.treeRoot.appendChild([B])
        } else {
            if (h.tplSort == 0 || this.tplSort == 3) {
                h.treeRoot.appendChild([B, C, a])
            } else {
                h.treeRoot.appendChild([B, C])
            }
        }
        h.treePanel.setRootNode(h.treeRoot)
    },
    removeNode: function(a) {},
    viewForm: function(a) {
        wfViewForm(a)
    }
};
var CNOA_wf_set_flow_convergenceClass, CNOA_wf_set_flow_convergence;
CNOA_wf_set_flow_convergenceClass = CNOA.Class.create();
CNOA_wf_set_flow_convergenceClass.prototype = {
    init: function(q, d, b) {
        var j = this;
        this.parent = q;
        this.historyRecord = q.stepData.convergence;
        this.baseUrl = "index.php?app=wf&func=flow&action=set&modul=flow";
        var c = CNOA_wf_set_flow_design_main.flowId;
        var r = [];
        var k = b.length;
        for (var g = 0; g < k; g++) {
            var f = [];
            if (b[g].id != d.id) {
                f.push(b[g].id);
                f.push(b[g].value);
                r.push(f)
            }
        }
        var o = new Ext.data.Record.create([{
            name: "id"
        },
            {
                name: "text"
            },
            {
                name: "condition"
            }]);
        var h = new Ext.data.ArrayStore({
            fields: [{
                name: "id"
            },
                {
                    name: "node"
                }]
        });
        h.loadData(r);
        var m = new Ext.grid.CheckboxSelectionModel({
            singleSelect: false
        });
        var s = new Ext.grid.ColumnModel([new Ext.grid.RowNumberer(), m, {
            header: "id",
            dataIndex: "id",
            hidden: true
        },
            {
                header: lang("optionalStep"),
                dataIndex: "node",
                id: "node",
                menuDisabled: true
            },
            {
                header: "",
                dataIndex: "noIndex",
                width: 20,
                menuDisabled: true,
                resizable: false
            }]);
        this.allNodeGrid = new Ext.grid.GridPanel({
            region: "west",
            width: 200,
            autoScroll: true,
            store: h,
            cm: s,
            sm: m,
            autoExpandColumn: "node",
            tbar: new Ext.Toolbar({
                items: ["->", {
                    text: lang("add"),
                    iconCls: "icon-utils-s-add",
                    handler: function(t) {
                        var C = j.allNodeGrid.getSelectionModel().getSelections();
                        var z = C.length;
                        if (z == 0) {
                            var y = t.getEl().getBox();
                            y = [y.x + 12, y.y + 26];
                            CNOA.miniMsg.alert(lang("notSelectStep"), y)
                        } else {
                            var x = new o({
                                id: "0",
                                text: "",
                                condition: []
                            });
                            for (var w = 0; w < z; w++) {
                                var v = C[w].data;
                                x.data.text += v.node + "+";
                                x.data.condition.push(v.id)
                            }
                            var B = true;
                            var A = j.conditionGrid.getStore();
                            A.each(function(E, D) {
                                if (Ext.decode(E.data.condition) == x.data.condition.sort(function F(H, G) {
                                    return H - G
                                }).toString()) {
                                    B = false
                                }
                            });
                            if (B) {
                                x.data.condition = Ext.encode(x.data.condition.sort(function u(E, D) {
                                    return E - D
                                }));
                                x.data.text = x.data.text.substr(0, x.data.text.length - 1);
                                n.add(x)
                            } else {
                                CNOA.msg.alert(lang("conditionAlreadyExists"))
                            }
                        }
                    }
                }]
            })
        });
        var p = [{
            name: "id"
        },
            {
                name: "text"
            },
            {
                name: "condition"
            }];
        var n = new Ext.data.Store({
            proxy: new Ext.data.HttpProxy({
                url: this.baseUrl + "&task=getFlowConvergence&flowId=" + c + "&stepId=" + d.id,
                disableCaching: true
            }),
            reader: new Ext.data.JsonReader({
                totalProperty: "total",
                root: "data",
                fields: p
            })
        });
        if (this.historyRecord == undefined) {
            n.load()
        }
        var a = new Ext.grid.CheckboxSelectionModel({
            singleSelect: false
        });
        var e = new Ext.grid.ColumnModel([a, {
            header: "id",
            dataIndex: "id",
            hidden: true
        },
            {
                header: lang("gatherCondition"),
                dataIndex: "text",
                id: "text",
                menuDisabled: true
            },
            {
                header: "condition",
                dataIndex: "condition",
                hidden: true
            },
            {
                header: "",
                dataIndex: "noIndex",
                width: 20,
                menuDisabled: true,
                resizable: false
            }]);
        this.conditionGrid = new Ext.grid.GridPanel({
            region: "center",
            autoScroll: true,
            store: n,
            cm: e,
            sm: a,
            autoExpandColumn: "text",
            loadMask: {
                msg: lang("waiting")
            },
            tbar: new Ext.Toolbar({
                items: [{
                    text: lang("del"),
                    iconCls: "icon-utils-s-delete",
                    handler: function(v) {
                        var w = j.conditionGrid.getSelectionModel().getSelections();
                        var u = w.length;
                        if (u == 0) {
                            var t = v.getEl().getBox();
                            t = [t.x + 12, t.y + 26];
                            CNOA.miniMsg.alert(lang("mustSelectOneRow"), t)
                        } else {
                            CNOA.msg.cf(lang("sureDelCondition"),
                                function(y) {
                                    if (y == "yes") {
                                        if (w) {
                                            for (var x = 0; x < u; x++) {
                                                n.remove(w[x])
                                            }
                                        }
                                    }
                                })
                        }
                    }
                },
                    {
                        xtype: "label",
                        html: '<div style="margin-left:15px;color:#8E8E8E;">( ' + lang("defaultAfterStep") + ")</div>"
                    }]
            })
        });
        this.mainPanel = new Ext.Panel({
            title: lang("convergenceCondition"),
            waitMsgTarget: true,
            layout: "border",
            items: [this.allNodeGrid, this.conditionGrid],
            listeners: {
                activate: function() {
                    j.parent.convergenceClick = true
                },
                afterrender: function() {
                    if (j.historyRecord != undefined) {
                        Ext.each(j.historyRecord,
                            function(v, u) {
                                var t = new o({
                                    id: "0",
                                    text: "",
                                    condition: []
                                });
                                t.data = v;
                                n.add(t)
                            })
                    }
                }
            }
        })
    },
    getData: function() {
        var c = this;
        var a = this.conditionGrid.store;
        var b = [];
        a.each(function(e, d) {
            b.push(e.data)
        });
        return b
    }
};
var CNOA_wf_set_flow_childClass, CNOA_wf_set_flow_child;
CNOA_wf_set_flow_childClass = CNOA.Class.create();
CNOA_wf_set_flow_childClass.prototype = {
    init: function(m, e, a) {
        var h = this;
        this.stepId = e.edges[0].source.id;
        this.childFlowId = null;
        this.parent = m;
        this.baseUrl = "index.php?app=wf&func=flow&action=set&modul=flow";
        var c = CNOA_wf_set_flow_design_main.flowId;
        var k = [{
            name: "id"
        },
            {
                name: "text"
            },
            {
                name: "condition"
            },
            {
                name: "arrow"
            },
            {
                name: "bangdingFlowName"
            },
            {
                name: "bangdingFlow"
            },
            {
                name: "childKongjianName"
            },
            {
                name: "childKongjian"
            },
            {
                name: "childType"
            },
            {
                name: "parentKongjianName"
            },
            {
                name: "parentKongjian"
            },
            {
                name: "parentType"
            },
            {
                name: "stepId"
            }];
        this.dataFlowStore = new Ext.data.Store({
            proxy: new Ext.data.HttpProxy({
                url: h.baseUrl
            }),
            reader: new Ext.data.JsonReader({
                root: "data",
                fields: [{
                    name: "name"
                },
                    {
                        name: "id"
                    }]
            })
        });
        this.dataFlowKongjianStore = new Ext.data.Store({
            proxy: new Ext.data.HttpProxy({
                url: h.baseUrl + "&task=flowKongjianData"
            }),
            reader: new Ext.data.JsonReader({
                root: "data",
                fields: [{
                    name: "name"
                },
                    {
                        name: "childType"
                    },
                    {
                        name: "id"
                    }]
            })
        });
        this.dataParentKongjianStore = new Ext.data.Store({
            proxy: new Ext.data.HttpProxy({
                url: h.baseUrl
            }),
            reader: new Ext.data.JsonReader({
                root: "data",
                fields: [{
                    name: "name"
                },
                    {
                        name: "parentType"
                    },
                    {
                        name: "id"
                    }]
            })
        });
        this.conditionStore = new Ext.data.Store({
            proxy: new Ext.data.HttpProxy({
                url: this.baseUrl + "&task=getChildFlowDataExchange&flowId=" + c,
                disableCaching: true
            }),
            reader: new Ext.data.JsonReader({
                totalProperty: "total",
                root: "data",
                fields: k
            })
        });
        this.editor = new Ext.ux.grid.RowEditor({
            cancelText: lang("cancel"),
            saveText: lang("update"),
            errorSummary: false
        });
        var b = new Ext.grid.CheckboxSelectionModel({
            singleSelect: false
        });
        var f = new Ext.grid.ColumnModel([b, {
            header: "id",
            dataIndex: "id",
            hidden: true
        },
            {
                header: lang("bindingflow"),
                dataIndex: "bangdingFlowName",
                width: 180,
                menuDisabled: true
            },
            {
                header: "childType",
                dataIndex: "childType",
                hidden: true
            },
            {
                header: lang("controlName"),
                dataIndex: "childKongjianName",
                width: 120,
                menuDisabled: true
            },
            {
                header: lang("bindingDirection"),
                dataIndex: "arrow",
                width: 80,
                menuDisabled: true,
                renderer: function(o, p, n) {
                    if (o == "right") {
                        return lang("bindingToRight")
                    } else {
                        if (o == "left") {
                            return lang("bindingToLeft")
                        }
                    }
                }
            },
            {
                header: "parentType",
                dataIndex: "parentType",
                hidden: true
            },
            {
                header: lang("parentProcessControl"),
                dataIndex: "parentKongjianName",
                width: 180,
                menuDisabled: true
            },
            {
                header: "",
                dataIndex: "noIndex",
                width: 20,
                menuDisabled: true,
                resizable: false
            }]);
        this.dsc = Ext.data.Record.create([{
            name: "name",
            type: "string"
        }]);
        this.conditionGrid = new Ext.grid.GridPanel({
            region: "center",
            autoScroll: true,
            store: this.conditionStore,
            cm: f,
            sm: b,
            border: false,
            loadMask: {
                msg: lang("waiting")
            },
            tbar: new Ext.Toolbar({
                items: [{
                    text: lang("add"),
                    iconCls: "icon-utils-s-add",
                    handler: function(n) {
                        h.addDataWin()
                    }
                },
                    "-", {
                        text: lang("del"),
                        iconCls: "icon-utils-s-delete",
                        handler: function(p) {
                            var q = h.conditionGrid.getSelectionModel().getSelections();
                            var o = q.length;
                            if (o == 0) {
                                var n = p.getEl().getBox();
                                n = [n.x + 12, n.y + 26];
                                CNOA.miniMsg.alert(lang("mustSelectOneRow"), n)
                            } else {
                                CNOA.msg.cf(lang("sureDelCondition"),
                                    function(s) {
                                        if (s == "yes") {
                                            if (q) {
                                                for (var r = 0; r < o; r++) {
                                                    h.conditionStore.remove(q[r])
                                                }
                                            }
                                        }
                                    })
                            }
                        }
                    },
                    {
                        xtype: "label",
                        html: '<div style="margin-left:15px;color:#8E8E8E;">（' + lang("defaultAfterStep") + "）</div>"
                    }]
            })
        });
        var j = 0;
        this.conditionRecord = new Ext.data.Record.create([{
            name: "id"
        },
            {
                name: "bangdingFlowName"
            },
            {
                name: "bangdingFlow"
            },
            {
                name: "childKongjianName"
            },
            {
                name: "childKongjian"
            },
            {
                name: "childType"
            },
            {
                name: "parentKongjianName"
            },
            {
                name: "parentKongjian"
            },
            {
                name: "parentType"
            },
            {
                name: "arrow"
            },
            {
                name: "stepId"
            }]);
        var g = new Ext.data.Record.create([{
            name: "id"
        },
            {
                name: "name"
            },
            {
                name: "data"
            }]);
        var d = new Ext.data.Record.create([{
            name: "id"
        },
            {
                name: "name"
            },
            {
                name: "parentType"
            },
            {
                name: "data"
            }]);
        this.mainPanel = new Ext.Panel({
            title: lang("setFormConnection"),
            waitMsgTarget: true,
            layout: "border",
            border: false,
            items: [this.conditionGrid],
            listeners: {
                activate: function() {
                    h.parent.childClick = true;
                    j++;
                    if (j % 2 == 1) {
                        h.dataFlowStore.removeAll();
                        var n = Ext.getCmp(m.base.ID_TEXTFIELD_BINGFLOWNAMES).getValue();
                        n = n.split(",");
                        var o = Ext.getCmp(m.base.ID_TEXTFIELD_BINGFLOWIDS).getValue();
                        o = o.split(",");
                        Ext.each(o,
                            function(r, q) {
                                var p = new g({
                                    data: {
                                        id: 0,
                                        name: ""
                                    }
                                });
                                if (r != "") {
                                    p.data.id = r;
                                    p.data.name = n[q];
                                    h.dataFlowStore.add(p)
                                }
                            });
                        h.dataParentKongjianStore.removeAll();
                        Ext.each(m.flowFields,
                            function(r, q) {
                                var p = new d({
                                    data: {
                                        id: 0,
                                        name: "",
                                        parentType: ""
                                    }
                                });
                                if (r != "") {
                                    if (r.from == "normal") {
                                        p.data.id = "T_" + r.id;
                                        p.data.name = r.name;
                                        p.data.parentType = r.dataType;
                                        h.dataParentKongjianStore.add(p)
                                    }
                                }
                            })
                    }
                },
                afterrender: function() {
                    h.loadData()
                }
            }
        })
    },
    loadData: function() {
        var a = this;
        if (this.parent.stepData.child == undefined) {
            return
        }
        Ext.each(this.parent.stepData.child,
            function(d, c) {
                var b = new a.conditionRecord({
                    data: {
                        bangdingFlowName: "",
                        bangdingFlow: "",
                        childKongjianName: "",
                        childKongjian: "",
                        childType: "",
                        parentKongjianName: "",
                        parentKongjian: "",
                        parentType: "",
                        stepId: "",
                        arrow: ""
                    }
                });
                b.data.bangdingFlowName = d.bangdingFlowName;
                b.data.bangdingFlow = d.bangdingFlow;
                b.data.childKongjianName = d.childKongjianName;
                b.data.childKongjian = d.childKongjian;
                b.data.childType = d.childType;
                b.data.parentKongjianName = d.parentKongjianName;
                b.data.parentKongjian = d.parentKongjian;
                b.data.parentType = d.parentType;
                b.data.arrow = d.arrow;
                b.data.stepId = a.stepId;
                a.conditionStore.add(b)
            })
    },
    getData: function() {
        var c = this;
        var a = this.conditionGrid.store;
        var b = [];
        a.each(function(e, d) {
            b.push(e.data)
        });
        return b
    },
    addDataWin: function() {
        var e = this;
        var b = [{
            xtype: "fieldset",
            title: lang("bindingSet"),
            style: "margin:5px",
            items: [{
                xtype: "panel",
                layout: "form",
                border: false,
                items: [{
                    xtype: "hidden",
                    name: "bangdingFlowName"
                },
                    {
                        xtype: "combo",
                        allowBlank: false,
                        store: e.dataFlowStore,
                        style: "margin-bottom:10px;",
                        width: 150,
                        valueField: "id",
                        displayField: "name",
                        id: "childFlowId",
                        fieldLabel: lang("bindingOfSubprocess"),
                        mode: "local",
                        triggerAction: "all",
                        forceSelection: true,
                        editable: false,
                        hiddenName: "bangdingFlow",
                        name: "bangdingFlow",
                        listeners: {
                            change: function(h, g, f) {},
                            select: function(h, f, g) {
                                c.getForm().findField("bangdingFlowName").setValue(f.data.name);
                                e.dataFlowKongjianStore.load({
                                    params: {
                                        flowId: h.value
                                    }
                                });
                                e.childFlowId = h.value
                            }
                        }
                    }]
            },
                {
                    xtype: "panel",
                    layout: "table",
                    border: false,
                    layoutConfig: {
                        columns: 4
                    },
                    items: [{
                        xtype: "hidden",
                        name: "childType"
                    },
                        {
                            xtype: "panel",
                            border: false,
                            style: "margin-left:10px;",
                            items: [{
                                xtype: "hidden",
                                name: "childKongjianName"
                            },
                                {
                                    xtype: "multiselect",
                                    height: 150,
                                    width: 120,
                                    valueField: "id",
                                    displayField: "name",
                                    hiddenName: "childKongjian",
                                    name: "childKongjian",
                                    mode: "local",
                                    typeAhead: true,
                                    triggerAction: "all",
                                    store: e.dataFlowKongjianStore,
                                    tbar: [{
                                        text: lang("controls")
                                    }],
                                    listeners: {
                                        click: function(f, h) {
                                            f.value = f.getRawValue();
                                            var g = f.store.data.items[h].data;
                                            c.getForm().findField("childKongjianName").setValue(g.name);
                                            c.getForm().findField("childType").setValue(g.parentType)
                                        }
                                    }
                                }]
                        },
                        {
                            xtype: "panel",
                            layout: "form",
                            border: false,
                            style: "margin-top:10px;margin-left:10px;",
                            items: [{
                                xtype: "hidden",
                                name: "arrow",
                                value: "right"
                            },
                                {
                                    xtype: "button",
                                    text: "&nbsp;&nbsp;→&nbsp;&nbsp;",
                                    handler: function(f) {
                                        var g = c.getForm().findField("arrow");
                                        var h = g.getValue();
                                        if (h == "right") {
                                            f.setText("&nbsp;&nbsp;←&nbsp;&nbsp;");
                                            g.setValue("left")
                                        } else {
                                            f.setText("&nbsp;&nbsp;→&nbsp;&nbsp;");
                                            g.setValue("right")
                                        }
                                    }
                                }]
                        },
                        {
                            xtype: "panel",
                            border: false,
                            style: "margin-left:10px;",
                            items: [{
                                xtype: "hidden",
                                name: "parentType"
                            },
                                {
                                    xtype: "hidden",
                                    name: "parentKongjianName"
                                },
                                {
                                    xtype: "multiselect",
                                    id: "ID_detailfields",
                                    height: 150,
                                    width: 120,
                                    valueField: "id",
                                    displayField: "name",
                                    hiddenName: "parentKongjian",
                                    name: "parentKongjian",
                                    mode: "local",
                                    typeAhead: true,
                                    triggerAction: "all",
                                    store: e.dataParentKongjianStore,
                                    tbar: [{
                                        text: lang("parentProcessControl")
                                    }],
                                    listeners: {
                                        click: function(f, h) {
                                            f.value = f.getRawValue();
                                            var g = f.store.data.items[h].data;
                                            c.getForm().findField("parentKongjianName").setValue(g.name);
                                            c.getForm().findField("parentType").setValue(g.parentType)
                                        }
                                    }
                                }]
                        }]
                }]
        }];
        var c = new Ext.form.FormPanel({
            padding: 5,
            region: "center",
            labelAlign: "right",
            labelWidth: 110,
            border: false,
            waitMsgTarget: true,
            autoScroll: true,
            items: b
        });
        var d = new Ext.Window({
            title: lang("addBindingWay"),
            border: false,
            width: 380,
            height: 330,
            modal: true,
            layout: "border",
            items: c,
            buttons: [{
                text: lang("add"),
                handler: function() {
                    a()
                }
            },
                {
                    text: lang("close"),
                    handler: function(f) {
                        d.close()
                    }
                }],
            listeners: {
                show: function() {
                    if (e.childFlowId) {
                        Ext.getCmp("childFlowId").setValue(e.childFlowId);
                        c.getForm().findField("bangdingFlowName").setValue(Ext.getCmp("childFlowId").lastSelectionText)
                    }
                }
            }
        }).show();
        var a = function(k) {
            var g = c.getForm().getValues();
            var j = g.childKongjian.substr(0, 1) == "T" ? "normal": "detail";
            var h = g.parentKongjian.substr(0, 1) == "T" ? "normal": "detail";
            if (g.arrow == "right") {
                if (h == "normal" && j == "detail") {
                    CNOA.msg.alert(lang("childFlowNotDetail"));
                    return
                }
            } else {
                if (j == "normal" && h == "detail") {
                    CNOA.msg.alert(lang("parentDetailNotChild"));
                    return
                }
            }
            var f = new e.conditionRecord({
                data: {
                    bangdingFlowName: "",
                    bangdingFlow: "",
                    childKongjianName: "",
                    childKongjian: "",
                    childType: "",
                    parentKongjianName: "",
                    parentKongjian: "",
                    parentType: "",
                    stepId: "",
                    arrow: ""
                }
            });
            f.data.bangdingFlowName = g.bangdingFlowName;
            f.data.bangdingFlow = g.bangdingFlow;
            f.data.childKongjianName = g.childKongjianName;
            f.data.childKongjian = g.childKongjian;
            f.data.childType = g.childType;
            f.data.parentKongjianName = g.parentKongjianName;
            f.data.parentKongjian = g.parentKongjian;
            f.data.parentType = g.parentType;
            f.data.arrow = g.arrow;
            f.data.stepId = e.stepId;
            e.conditionStore.add(f);
            if (k == "close") {
                d.close()
            } else {}
        }
    }
};
var CNOA_wf_set_flow_child_permitClass, CNOA_wf_set_flow_child_permit;
CNOA_wf_set_flow_child_permitClass = CNOA.Class.create();
CNOA_wf_set_flow_child_permitClass.prototype = {
    init: function(j, d, b) {
        var g = this;
        this.stepId = d.edges[0].source.id;
        this.ID_GRID_PERMIT = Ext.id();
        this.parent = j;
        this.flowFields = j.flowFields;
        this.baseUrl = "index.php?app=wf&func=flow&action=set&modul=flow";
        var c = CNOA_wf_set_flow_design_main.flowId;
        var e = {
            coders: this.flowFields
        };
        this.formData = this.parent.stepData.childPermit;
        this.permitStore = new Ext.data.GroupingStore({
            proxy: new Ext.data.MemoryProxy(e),
            reader: new Ext.data.JsonReader({
                    root: "coders"
                },
                [{
                    name: "id"
                },
                    {
                        name: "name"
                    },
                    {
                        name: "otype"
                    },
                    {
                        name: "tableid"
                    },
                    {
                        name: "gname"
                    }]),
            groupField: "gname"
        });
        this.permitStore.load();
        var f = new Ext.grid.CheckboxSelectionModel({
            singleSelect: false
        });
        var a = new Ext.grid.ColumnModel([f, {
            header: lang("controlName"),
            dataIndex: "name",
            id: "name",
            width: 300,
            sortable: true,
            menuDisabled: true
        },
            {
                header: lang("opt"),
                dataIndex: "id",
                menuDisabled: true,
                width: 130,
                renderer: this.mkShowHide.createDelegate(this)
            },
            {
                header: "",
                dataIndex: "gname",
                width: 1,
                sortable: true,
                menuDisabled: true
            }]);
        this.permitGrid = new Ext.grid.GridPanel({
            region: "center",
            autoScroll: true,
            store: this.permitStore,
            id: this.ID_GRID_PERMIT,
            cm: a,
            sm: f,
            border: false,
            autoExpandColumn: "name",
            view: new Ext.grid.GroupingView({
                forceFit: true,
                groupTextTpl: "{text} ({[values.rs.length]} 项)"
            }),
            loadMask: {
                msg: lang("waiting")
            },
            tbar: new Ext.Toolbar({
                items: [{
                    xtype: "label",
                    html: '<div style="margin-left:15px;color:#8E8E8E;">设置子流程查看父流程时,父流程表单哪些内容可见</div>'
                }]
            }),
            listeners: {
                afterrender: function() {
                    setTimeout(function() {
                            g.loadData()
                        },
                        100)
                }
            }
        });
        var h = 0;
        this.mainPanel = new Ext.Panel({
            title: "子流程权限",
            waitMsgTarget: true,
            layout: "border",
            border: false,
            items: [this.permitGrid]
        })
    },
    mkShowHide: function(d, h, b) {
        var g = b.data;
        var a = g.otype;
        var f = g.tableid;
        var e = '<div id="WF_FLOWDESIGN_STEP_FIELD_CT3_' + d + '" tableid="' + f + '">';
        e += '<input type="radio" otype="' + a + '" checked="checked" value="show" name="CNOA_WF_CHILD_PERMIT' + d + '" id="CNOA_WF_STEP_SHOW' + d + '" /><label for="CNOA_WF_STEP_SHOW' + d + '">' + lang("visible") + "</label>";
        e += "&nbsp;&nbsp;&nbsp;";
        e += '<input type="radio" otype="' + a + '" value="hide" name="CNOA_WF_CHILD_PERMIT' + d + '" id="CNOA_WF_STEP_HIDE' + d + '" /><label for="CNOA_WF_STEP_HIDE' + d + '">保密</label>';
        e += "</div>";
        return e
    },
    loadData: function() {
        var b = this;
        var a = "#" + this.ID_GRID_PERMIT + " input[type=radio][name=CNOA_WF_CHILD_PERMIT";
        Ext.each(this.formData,
            function(c, d) {
                if (c.status == 1) {
                    $(a + c.id + "][value=show]").attr("checked", true)
                } else {
                    $(a + c.id + "][value=hide]").attr("checked", true)
                }
                if ((c.otype == "detailtable") && (c.hide == "1")) {
                    $("div[tableid=" + c.id + "] input").attr("disabled", true)
                }
            })
    },
    getData: function() {
        var d = clone(this.flowFields);
        for (var c = 0; c < d.length; c++) {
            var b = d[c];
            var a = $("#" + this.ID_GRID_PERMIT + " input:radio[name=CNOA_WF_CHILD_PERMIT" + b.id + "]:checked").val();
            d[c].status = a == "show" ? "1": "0";
            d[c].stepId = this.parent.child.stepId
        }
        return d
    }
};
var CNOA_wf_set_taohongClass, CNOA_wf_set_taohong;
CNOA_wf_set_taohongClass = CNOA.Class.create();
CNOA_wf_set_taohongClass.prototype = {
    init: function() {
        var a = this;
        this.baseUrl = "index.php?app=wf&func=flow&action=set&modul=flow&task=taoHong";
        this.list = this.tplList();
        this.mainPanel = new Ext.Panel({
            border: false,
            region: "center",
            layout: "fit",
            items: [this.list]
        })
    },
    tplList: function() {
        var g = this;
        var c = [{
            name: "id"
        },
            {
                name: "oldname"
            }];
        this.store = new Ext.data.Store({
            autoLoad: true,
            proxy: new Ext.data.HttpProxy({
                url: g.baseUrl + "&type=tplList"
            }),
            reader: new Ext.data.JsonReader({
                totalProperty: "total",
                root: "data",
                fields: c
            })
        });
        var f = new Ext.grid.CheckboxSelectionModel();
        var b = function(n, m, j) {
            var k = j.json.urlView,
                h = j.json.urlEdit;
            return k + h
        };
        var a = new Ext.grid.ColumnModel({
            columns: [new Ext.grid.RowNumberer(), f, {
                header: "id",
                dataIndex: "id",
                hidden: true
            },
                {
                    header: lang("fileName"),
                    dataIndex: "oldname",
                    width: 300
                },
                {
                    header: lang("opt"),
                    dataIndex: "",
                    width: 110,
                    renderer: b
                }]
        });
        var e = new Ext.PagingToolbar({
            store: this.store,
            displayInfo: true,
            pageSize: 15
        });
        var d = new Ext.grid.GridPanel({
            border: false,
            store: this.store,
            cm: a,
            sm: f,
            bbar: e,
            tbar: [{
                text: lang("refresh"),
                iconCls: "icon-system-refresh",
                handler: function() {
                    g.store.reload()
                }
            },
                {
                    text: lang("upFile"),
                    iconCls: "icon-file-up",
                    cls: "btn-yellow1",
                    handler: function() {
                        g.uploadWin()
                    }
                },
                {
                    text: lang("del"),
                    iconCls: "icon-utils-s-delete",
                    handler: function(h) {
                        var j = d.getSelectionModel().getSelections();
                        if (j.length == 0) {
                            CNOA.miniMsg.alertShowAt(h, lang("mustSelectOneRow"))
                        } else {
                            CNOA.msg.cf(lang("confirmToDelete"),
                                function(k) {
                                    if (k == "yes") {
                                        var m = [];
                                        Ext.each(j,
                                            function(o, n) {
                                                m.push(o.json.id)
                                            });
                                        g.delTpl(m.join(","))
                                    }
                                })
                        }
                    }
                }]
        });
        return d
    },
    uploadWin: function() {
        var c = this;
        var a = new Ext.ux.SwfUploadPanel({
            region: "center",
            hasFolder: true,
            border: false,
            upload_url: "../" + c.baseUrl + "&type=upload&CNOAOASESSID=" + CNOA.cookie.get("CNOAOASESSID"),
            flash_url: "resources/swfupload.swf",
            single_file_select: false,
            confirm_delete: false,
            remove_completed: false,
            file_types: "*.*",
            file_types_description: lang("allType"),
            listeners: {
                fileUploadComplete: function() {
                    c.store.reload();
                    b.close()
                }
            }
        });
        var b = new Ext.Window({
            width: 600,
            height: makeWindowHeight(500),
            layout: "border",
            modal: true,
            title: "<span style='color: red'>目前只支持word文档套红<span>",
            resizable: false,
            items: [a]
        }).show()
    },
    delTpl: function(a) {
        var b = this;
        Ext.Ajax.request({
            url: b.baseUrl + "&type=delTpl",
            method: "POST",
            params: {
                ids: a
            },
            success: function(c) {
                var d = Ext.decode(c.responseText);
                if (d.success == true) {
                    CNOA.msg.notice2(d.msg);
                    b.store.reload()
                } else {
                    CNOA.msg.alert(d.msg)
                }
            }
        })
    }
};
var CNOA_wf_mgr_listClass, CNOA_wf_mgr_list;
var CNOA_wf_mgr_list_entrustClass, CNOA_wf_mgr_list_entrust;
var CNOA_wf_mgr_listModifyClass, CNOA_wf_mgr_listModify;
CNOA_wf_mgr_listModifyClass = CNOA.Class.create();
CNOA_wf_mgr_listModifyClass.prototype = {
    init: function(k, a, e, g, n, h) {
        var f = this;
        this.baseUrl = "index.php?app=wf&func=flow&action=mgr&modul=list";
        var d = Ext.id();
        this.flowType = n;
        this.tplSort = h;
        var j = function() {
            c.getForm().load({
                url: f.baseUrl + "&task=loadModifyForm",
                method: "POST",
                params: {
                    uFlowId: k,
                    stepId: e
                },
                waitTitle: lang("notice"),
                success: function(o, p) {},
                failure: function(o, p) {
                    CNOA.msg.alert(p.result.msg)
                }
            })
        };
        var m = function() {
            var p = c.getForm();
            var o = p.findField("uid").getRawValue();
            if (p.isValid()) {
                p.submit({
                    url: f.baseUrl + "&task=modify",
                    waitTitle: lang("notice"),
                    method: "POST",
                    waitMsg: lang("waiting"),
                    params: {
                        uFlowId: k,
                        flowId: a,
                        stepId: e,
                        stepUid: g,
                        flowType: f.flowType,
                        tplSort: f.tplSort
                    },
                    success: function(q, r) {
                        CNOA.msg.notice(r.msg, lang("toCorrectAgent") + "：" + o);
                        CNOA_wf_mgr_list.store.reload();
                        b.close()
                    }.createDelegate(this),
                    failure: function(q, r) {
                        CNOA.msg.alert(r.result.msg)
                    }.createDelegate(this)
                })
            } else {
                CNOA.miniMsg.alertShowAt(d, lang("notChooseCorrectHand"), 30)
            }
        };
        var c = new Ext.form.FormPanel({
            border: false,
            hideBorders: true,
            labelWidth: 80,
            style: "padding:8px;",
            waitMsgTarget: true,
            listeners: {
                afterrender: function() {
                    j()
                }
            },
            items: [{
                xtype: "userselectorfield",
                fieldLabel: lang("correctionAttn"),
                width: 250,
                allowBlank: false,
                name: "uid",
                multiSelect: false
            }]
        });
        var b = new Ext.Window({
            width: 400,
            height: 200,
            title: lang("correctStepDealPeople"),
            resizable: false,
            modal: true,
            layout: "fit",
            items: c,
            buttons: [{
                text: lang("ok"),
                iconCls: "icon-order-s-accept",
                handler: function() {
                    m()
                }
            },
                {
                    text: lang("cancel"),
                    iconCls: "icon-dialog-cancel",
                    handler: function() {
                        b.close()
                    }
                }]
        }).show()
    }
};
CNOA_wf_mgr_list_entrustClass = CNOA.Class.create();
CNOA_wf_mgr_list_entrustClass.prototype = {
    init: function(a, e, d, b, c) {
        var f = this;
        this.flowType = b;
        this.tplSort = c;
        this.ID_btn_save = Ext.id();
        this.baseUrl = "index.php?app=wf&func=flow&action=mgr&modul=list";
        this.formPanel = new Ext.form.FormPanel({
            border: false,
            labelWidth: 60,
            waitMsgTarget: true,
            bodyStyle: "padding: 10px",
            items: [{
                xtype: "userselectorfield",
                fieldLabel: lang("selectPeople"),
                width: 275,
                allowBlank: false,
                name: "touid",
                multiSelect: false
            },
                {
                    xtype: "textarea",
                    height: 126,
                    width: 275,
                    fieldLabel: lang("principalReason"),
                    name: "say"
                }]
        });
        this.mainPanel = new CNOA.wf.dealWindow({
            width: 380,
            height: 240,
            title: lang("delegateEntrusted"),
            resizable: false,
            modal: true,
            layout: "fit",
            items: this.formPanel,
            buttons: [{
                text: lang("ok"),
                iconCls: "icon-order-s-accept",
                handler: function() {
                    f.submitEntrustFormData(a, e, d)
                }.createDelegate(this)
            },
                {
                    text: lang("cancel"),
                    iconCls: "icon-dialog-cancel",
                    handler: function() {
                        f.mainPanel.close()
                    }.createDelegate(this)
                }]
        }).show();
        f.loadEntrustForm(a, e)
    },
    loadEntrustForm: function(a, b) {
        var c = this;
        c.formPanel.getForm().load({
            url: c.baseUrl + "&task=loadEntrustForm",
            method: "POST",
            params: {
                uFlowId: a,
                stepId: b
            },
            waitTitle: lang("notice"),
            success: function(d, e) {},
            failure: function(d, e) {
                CNOA.msg.alert(e.result.msg,
                    function() {})
            }
        })
    },
    submitEntrustFormData: function(a, e, d) {
        var g = this;
        var c = g.formPanel.getForm();
        var b = c.findField("touid").getRawValue();
        if (c.isValid()) {
            c.submit({
                url: g.baseUrl + "&task=submitEntrustFormData",
                waitTitle: lang("notice"),
                method: "POST",
                waitMsg: lang("waiting"),
                params: {
                    uFlowId: a,
                    stepId: e,
                    stepUid: d,
                    flowType: g.flowType,
                    tplSort: g.tplSort
                },
                success: function(f, h) {
                    CNOA.msg.notice(h.msg, lang("hasBeenEntruste") + ": " + b + "");
                    g.mainPanel.close();
                    try {
                        CNOA_wf_mgr_list.store.reload()
                    } catch(j) {}
                }.createDelegate(this),
                failure: function(f, h) {
                    CNOA.msg.alert(h.result.msg)
                }.createDelegate(this)
            })
        } else {
            CNOA.miniMsg.alertShowAt(g.ID_btn_save, lang("didNotChooseDelegate"), 30)
        }
    }
};
CNOA_wf_mgr_listClass = CNOA.Class.create();
CNOA_wf_mgr_listClass.prototype = {
    init: function() {
        var b = this;
        var a = Ext.id();
        this.baseUrl = "index.php?app=wf&func=flow&action=mgr&modul=list";
        this.storeBar = {
            from: "normal",
            flowName: "",
            overtime: "",
            status: "",
            hour: CNOA.wf.mgr.list.hour,
            stime: CNOA.wf.mgr.list.stime,
            etime: CNOA.wf.mgr.list.etime
        };
        this.fields = [{
            name: "againStatus"
        },
            {
                name: "uFlowId"
            },
            {
                name: "flowId"
            },
            {
                name: "flowNumber"
            },
            {
                name: "flowName"
            },
            {
                name: "flowSetName"
            },
            {
                name: "flowType"
            },
            {
                name: "tplSort"
            },
            {
                name: "uid"
            },
            {
                name: "level"
            },
            {
                name: "step"
            },
            {
                name: "sname"
            },
            {
                name: "sortId"
            },
            {
                name: "uname"
            },
            {
                name: "stepUid"
            },
            {
                name: "stepUname"
            },
            {
                name: "stepTrustUname"
            },
            {
                name: "isread"
            },
            {
                name: "statusText"
            },
            {
                name: "status"
            },
            {
                name: "postTime"
            },
            {
                name: "usetime"
            },
            {
                name: "overtime"
            },
            {
                name: "permit"
            },
            {
                name: "proxyStatus"
            },
            {
                name: "stepname"
            },
            {
                name: "stime"
            },
            {
                name: "etime"
            }];
        this.store = new Ext.data.Store({
            autoLoad: true,
            baseParams: this.storeBar,
            proxy: new Ext.data.HttpProxy({
                url: b.baseUrl + "&task=getJsonData"
            }),
            reader: new Ext.data.JsonReader({
                totalProperty: "total",
                root: "data",
                fields: this.fields
            })
        });
        this.sm = new Ext.grid.CheckboxSelectionModel({
            singleSelect: false
        });
        this.colModel = new Ext.grid.ColumnModel([new Ext.grid.RowNumberer(), this.sm, {
            header: "uFlowId",
            dataIndex: "uFlowId",
            hidden: true
        },
            {
                header: lang("flowNumber") + " / " + lang("customTitle"),
                dataIndex: "flowNumber",
                id: "flowNumber",
                width: 150,
                sortable: true,
                menuDisabled: true,
                renderer: this.formatNumber.createDelegate(this)
            },
            {
                header: lang("sort2") + " / " + lang("belongFlow"),
                dataIndex: "sname",
                width: 130,
                sortable: true,
                menuDisabled: true,
                renderer: this.formatName.createDelegate(this)
            },
            {
                header: lang("initiator") + " / " + lang("initTime"),
                dataIndex: "uname",
                width: 110,
                sortable: true,
                menuDisabled: true,
                renderer: this.formatUser.createDelegate(this)
            },
            {
                header: lang("attn") + " / " + lang("whenHandLong"),
                dataIndex: "stepUname",
                width: 130,
                sortable: true,
                menuDisabled: true,
                renderer: this.formatStepUser.createDelegate(this)
            },
            {
                header: lang("importantGrade"),
                dataIndex: "level",
                width: 60,
                sortable: true,
                menuDisabled: true
            },
            {
                header: lang("opt"),
                dataIndex: "uFlowId",
                width: 400,
                sortable: true,
                menuDisabled: true,
                renderer: this.operate.createDelegate(this)
            },
            {
                header: "",
                dataIndex: "noIndex",
                width: 1,
                menuDisabled: true,
                resizable: false
            }]);
        this.grid = new Ext.grid.PageGridPanel({
            bodyStyle: "border-left-width:1px;",
            border: false,
            store: b.store,
            searchStore: this.storeBar,
            loadMask: {
                msg: lang("waiting")
            },
            cm: this.colModel,
            sm: this.sm,
            stripeRows: true,
            hideBorders: true,
            autoExpandColumn: "flowNumber",
            tbar: [{
                handler: function(c, d) {
                    b.storeBar.status = "doing";
                    b.store.load({
                        params: b.storeBar
                    })
                }.createDelegate(this),
                iconCls: "icon-waiting",
                enableToggle: true,
                pressed: true,
                allowDepress: false,
                toggleGroup: "GROUP_BTN_FLOW",
                cls: "btn-yellow1",
                text: lang("checkIn")
            },
                {
                    handler: function(c, d) {
                        b.storeBar.status = "done";
                        b.store.load({
                            params: b.storeBar
                        })
                    }.createDelegate(this),
                    iconCls: "icon-pass",
                    enableToggle: true,
                    cls: "btn-blue3",
                    allowDepress: false,
                    toggleGroup: "GROUP_BTN_FLOW",
                    text: lang("yibanli")
                },
                {
                    handler: function(c, d) {
                        b.storeBar.status = "all";
                        b.store.load({
                            params: b.storeBar
                        })
                    }.createDelegate(this),
                    enableToggle: true,
                    allowDepress: false,
                    cls: "btn-red1",
                    toggleGroup: "GROUP_BTN_FLOW",
                    iconCls: "icon-wenType",
                    text: lang("allFlow")
                }]
        });
        this.treeRoot = new Ext.tree.AsyncTreeNode({
            text: lang("allSort"),
            sortId: "0",
            iconCls: "icon-tree-root-cnoa",
            cls: "feeds-node",
            expanded: true
        });
        this.treeLoader = new Ext.tree.TreeLoader({
            dataUrl: this.baseUrl + "&task=getSortTree",
            preloadChildren: true,
            clearOnLoad: false
        });
        this.tree = new Ext.tree.TreePanel({
            region: "west",
            layout: "fit",
            width: 150,
            minWidth: 80,
            maxWidth: 380,
            hideBorders: true,
            border: false,
            rootVisible: true,
            split: true,
            bodyStyle: "border-right-width:1px;",
            lines: true,
            animCollapse: false,
            animate: false,
            loader: this.treeLoader,
            root: this.treeRoot,
            autoScroll: true,
            listeners: {
                click: function(c) {
                    b.storeBar.sortId = c.attributes.sortId;
                    b.store.load({
                        params: b.storeBar
                    })
                }
            },
            tbar: [{
                text: lang("refresh"),
                iconCls: "icon-system-refresh",
                handler: function() {
                    b.treeRoot.reload()
                }
            },
                "->", lang("processCategoryList") + ": &nbsp;"]
        });
        this.overSm = new Ext.grid.CheckboxSelectionModel({
            singleSelect: false
        });
        this.overColModel = new Ext.grid.ColumnModel([new Ext.grid.RowNumberer(), this.overSm, {
            header: "id",
            dataIndex: "id",
            hidden: true
        },
            {
                header: lang("stepName"),
                dataIndex: "stepname",
                width: 160,
                sortable: true,
                menuDisabled: true,
                renderer: function(d, f, e) {
                    return "<span>" + d + "</span><br /><span>" + e.data.stime + "~" + e.data.etime + "</span>"
                }
            },
            {
                header: lang("attn") + " / " + lang("whenHandLong"),
                dataIndex: "stepUname",
                width: 130,
                sortable: true,
                menuDisabled: true,
                renderer: function(e, g, d) {
                    var f = e + "<br />" + d.data.usetime;
                    if (d.get("overtime")) {
                        f += '<span style="color:#ff0000">[ ' + lang("timeOut") + " ]<span>"
                    }
                    return f
                }
            },
            {
                header: lang("flowNumber") + " / " + lang("customTitle"),
                dataIndex: "flowNumber",
                id: "flowNumber",
                width: 150,
                sortable: true,
                menuDisabled: true,
                renderer: function(e, f, d) {
                    if (d.data.flowName == "") {
                        d.data.flowName = "&nbsp;"
                    }
                    return "<span >" + e + "</span><br /><span class='cnoa_color_gray'>" + d.data.flowName + "</span>"
                }
            },
            {
                header: lang("sort2") + " / " + lang("belongFlow"),
                dataIndex: "sname",
                width: 130,
                sortable: true,
                menuDisabled: true,
                renderer: this.formatName.createDelegate(this)
            },
            {
                header: lang("importantGrade"),
                dataIndex: "level",
                width: 60,
                sortable: true,
                menuDisabled: true
            },
            {
                header: lang("opt"),
                dataIndex: "uFlowId",
                width: 130,
                sortable: true,
                menuDisabled: true,
                renderer: function(f, g, e) {
                    var d = e.data;
                    l = "";
                    if (d.status == "1") {
                        l = " <a href='javascript:void(0)' class='gridview4 jianju' onclick='CNOA_wf_mgr_list.warn(" + f + ", " + d.flowId + ", " + d.step + ", " + d.stepUid + ", " + d.flowType + ", " + d.tplSort + ")'>督办</a> "
                    }
                    return l
                }
            },
            {
                header: "",
                dataIndex: "noIndex",
                width: 1,
                menuDisabled: true,
                resizable: false
            }]);
        this.viewOverTime = new Ext.grid.PageGridPanel({
            bodyStyle: "border-left-width:1px;",
            border: false,
            store: b.store,
            searchStore: this.storeBar,
            loadMask: {
                msg: lang("waiting")
            },
            cm: this.overColModel,
            sm: this.overSm,
            stripeRows: true,
            hideBorders: true,
            autoExpandColumn: "flowNumber",
            tbar: [{
                handler: function(c, d) {
                    b.storeBar.status = "doing";
                    b.store.load({
                        params: b.storeBar
                    })
                }.createDelegate(this),
                iconCls: "icon-roduction",
                enableToggle: true,
                pressed: true,
                allowDepress: false,
                toggleGroup: "GROUP_BTN_STEP",
                cls: "btn-blue4",
                text: lang("handSteps")
            },
                {
                    handler: function(c, d) {
                        b.storeBar.status = "done";
                        b.store.load({
                            params: b.storeBar
                        })
                    }.createDelegate(this),
                    enableToggle: true,
                    allowDepress: false,
                    toggleGroup: "GROUP_BTN_STEP",
                    cls: "btn-red1",
                    iconCls: "icon-roduction",
                    text: lang("allStep")
                },
                {
                    xtype: "datefield",
                    id: "ID_FIELD_DATE_START",
                    format: "Y-m-d",
                    value: CNOA.wf.mgr.list.stime
                },
                "~", {
                    xtype: "datefield",
                    id: "ID_FIELD_DATE_END",
                    format: "Y-m-d",
                    value: CNOA.wf.mgr.list.etime
                },
                lang("greaterThan"), {
                    xtype: "textfield",
                    id: "ID_FIELD_HOUR",
                    width: 80,
                    value: CNOA.wf.mgr.list.hour
                },
                lang("hour"), {
                    text: lang("filter"),
                    cls: "btn-red1",
                    id: "ID_FIELD_DATE_SEARCH_BTN",
                    handler: function() {
                        b.storeBar.stime = Ext.getCmp("ID_FIELD_DATE_START").getRawValue();
                        b.storeBar.etime = Ext.getCmp("ID_FIELD_DATE_END").getRawValue();
                        b.storeBar.hour = Ext.getCmp("ID_FIELD_HOUR").getValue();
                        b.store.load({
                            params: b.storeBar
                        })
                    }
                }]
        });
        this.ID_BTN_RETURN = Ext.id();
        this.ID_BTN_VIEWOVER = Ext.id();
        this.searchWin;
        this.panel = new Ext.Panel({
            collapsible: false,
            hideBorders: true,
            border: false,
            layout: "card",
            activeItem: 0,
            region: "center",
            items: [this.grid, this.viewOverTime],
            tbar: [{
                text: lang("refresh"),
                iconCls: "icon-system-refresh",
                handler: function() {
                    b.store.reload()
                }
            },
                {
                    text: lang("goBack"),
                    iconCls: "icon-btn-arrow-left",
                    id: this.ID_BTN_RETURN,
                    hidden: true,
                    handler: function() {
                        b.storeBar.from = "normal";
                        Ext.getCmp(b.ID_BTN_VIEWOVER).show();
                        this.hide();
                        b.panel.getLayout().setActiveItem(0);
                        b.store.load({
                            params: b.storeBar
                        })
                    }
                },
                {
                    text: lang("managementFlowLong"),
                    iconCls: "icon-myEntruSteRecord",
                    cls: "btn-blue4",
                    id: this.ID_BTN_VIEWOVER,
                    handler: function() {
                        b.storeBar.from = "overtime";
                        Ext.getCmp(b.ID_BTN_RETURN).show();
                        this.hide();
                        b.panel.getLayout().setActiveItem(1);
                        b.store.load({
                            params: b.storeBar
                        })
                    }
                },
                "->", lang("processNumName") + ":", {
                    xtype: "textfield",
                    width: 200,
                    id: a,
                    listeners: {
                        specialkey: function(d, c) {
                            if (c.getKey() == c.ENTER) {
                                b.storeBar.flowName = Ext.getCmp(a).getValue();
                                b.store.load({
                                    params: b.storeBar
                                })
                            }
                        }
                    }
                },
                {
                    xtype: "button",
                    text: lang("search"),
                    style: "margin-left:5px",
                    handler: function() {
                        b.storeBar.flowName = Ext.getCmp(a).getValue();
                        b.store.load({
                            params: b.storeBar
                        })
                    }
                },
                {
                    xtype: "button",
                    text: lang("advanceQuery"),
                    cls: "btn-blue4",
                    iconCls: "icon-search",
                    style: "margin-left:5px",
                    handler: function() {
                        if (!b.searchWin) {
                            b.searchWin = new CNOA_wf_advSearchWinClass(b)
                        }
                        b.searchWin.show()
                    }
                },
                {
                    xtype: "button",
                    text: lang("clear"),
                    handler: function() {
                        Ext.getCmp(a).setValue("");
                        b.storeBar.flowName = "";
                        b.store.load({
                            params: b.storeBar
                        })
                    }
                }]
        });
        this.mainPanel = new Ext.Panel({
            collapsible: false,
            hideBorders: true,
            border: false,
            layout: "border",
            autoScroll: false,
            items: [this.tree, this.panel]
        })
    },
    formatUser: function(e, c, a, f, b, d) {
        return e + "<br>" + a.data.postTime
    },
    formatNumber: function(h, e, c, f, d, a) {
        var j = c.data.status;
        var g = "#00000";
        if (j == 1) {
            g = "#FF0000"
        } else {
            if (j == 2) {
                g = "#008000"
            } else {
                if (j == 3) {
                    g = "#FF00FF"
                } else {
                    if (j == 4) {
                        g = "#808080"
                    } else {
                        g = "#9900FF"
                    }
                }
            }
        }
        var b = "<span style='color:" + g + ";'>[" + c.data.statusText + "]</span> ";
        return "<span >" + h + "</span><br />" + b + "<span class='cnoa_color_gray'>" + c.data.flowName + "</span>"
    },
    formatName: function(e, c, a, f, b, d) {
        return "<span >" + e + "</span><br /><span class='cnoa_color_gray' ext:qtip='" + a.data.flowSetName + "'>" + a.data.flowSetName + "</span>"
    },
    formatStepUser: function(f, d, a, g, b, e) {
        var c = "";
        if (!Ext.isEmpty(a.data.usetime)) {
            c = " [<span class='cnoa_color_red'>" + a.data.usetime + "</span>]"
        }
        if (Ext.isEmpty(a.data.stepTrustUname)) {
            return "<span >" + f + "</span>" + c
        } else {
            return "<span >" + f + "</span> " + c + "<br /><span class='cnoa_color_gray'>-->" + a.data.stepTrustUname + "</span>"
        }
    },
    makeStatus: function(j, e, c, f, d, a) {
        var k = c.data.status;
        var g = "#00000";
        if (k == 1) {
            g = "#FF0000"
        } else {
            if (k == 2) {
                g = "#008000"
            } else {
                if (k == 3) {
                    g = "#FF00FF"
                } else {
                    if (k == 4) {
                        g = "#808080"
                    } else {
                        g = "#9900FF"
                    }
                }
            }
        }
        var b = "<span style='color:" + g + ";'>" + j + "</span>";
        b += "<br>" + c.data.level;
        return b
    },
    operate: function(f, h, b) {
        var g = this;
        var e = b.data;
        var a = "";
        var d = "<a href='javascript:void(0)' class='gridview' onclick='CNOA_wf_mgr_list.makeForm(" + f + ", " + e.flowId + ", " + e.step + ", " + e.stepUid + ", " + e.status + ", " + e.flowType + ", " + e.tplSort + ")'>" + lang("view") + "</a>";
        if (e.permit == "c") {
            a = d
        } else {
            if (e.permit == "g") {
                a = d;
                if ((e.status != 2) && (e.status != 4) && (e.status != 6)) {
                    if ((Ext.isEmpty(e.stepTrustUname)) && e.proxyStatus != 1) {
                        a += "<a href='javascript:void(0)' class='gridview3 jianju' onclick='CNOA_wf_mgr_list.selectStep(" + f + ", " + e.flowId + ", " + e.step + ", " + e.stepUid + ", " + e.flowType + ", " + e.tplSort + ', "trustWin")\'>' + lang("entrust") + "</a>"
                    } else {
                        a += ""
                    }
                    a += "<a href='javascript:void(0)' class='gridview4 jianju' onclick='CNOA_wf_mgr_list.selectStep(" + f + ", " + e.flowId + ", " + e.step + ", " + e.stepUid + ", " + e.flowType + ", " + e.tplSort + ', "warn")\'>' + lang("supervision") + "</a>";
                    a += "<a href='javascript:void(0)' class='gridview2 jianju' onclick='CNOA_wf_mgr_list.selectStep(" + f + ", " + e.flowId + ", " + e.step + ", " + e.stepUid + ", " + e.flowType + ", " + e.tplSort + ', "modify")\'>' + lang("correct") + "</a>";
                    a += "<a href='javascript:void(0)' class='gridview3 jianju' onclick='CNOA_wf_mgr_list.stop(" + f + ", " + e.flowId + ", " + e.step + ", " + e.flowType + ", " + e.tplSort + ")'>" + lang("abort") + "</a>"
                }
                a += "<a href='javascript:void(0)' class='gridview2 jianju' onclick='CNOA_wf_mgr_list.note(" + f + ", " + e.flowType + ", " + e.tplSort + ")'>" + lang("del") + "</a>";
                a += "<a href='javascript:void(0)' class='gridview jianju' onclick='CNOA_wf_mgr_list.printFlow(" + f + ", " + e.flowId + ", " + e.step + ")'>" + lang("archive") + "</a>"
            }
        }
        if (e.flowId == "0" || e.sortId == "0") {
            a += " <a href='javascript:void(0)' class='gridview2 jianju' onclick='CNOA_wf_mgr_list.note(" + f + ", " + e.flowType + ", " + e.tplSort + ")'>" + lang("del") + "</a> "
        }
        return a
    },
    printFlow: function(b, c, d) {
        var a = "index.php?app=wf&func=flow&action=mgr&modul=list&uFlowId=" + b + "&flowId=" + c + "&step=" + d + "&" + getSessionStr();
        window.open(a + "&task=exportFlow&target=printer&flowId=" + c + "&uFlowId=" + b + "&step=" + d, "wfexport", "width=740,height=" + (screen.availHeight - 120) + ",left=" + ((screen.availWidth - 740) / 2) + ",top=60,scrollbars=yes,resizable=yes,status=no")
    },
    stop: function(a, c, e, b, d) {
        var f = this;
        CNOA.msg.cf(lang("areYouSureAbort"),
            function(g) {
                if (g == "yes") {
                    Ext.Ajax.request({
                        url: f.baseUrl + "&task=stopFlow",
                        method: "POST",
                        params: {
                            uFlowId: a,
                            flowId: c,
                            stepId: e,
                            flowType: f.flowType,
                            tplSort: f.tplSort
                        },
                        success: function(j) {
                            var h = Ext.decode(j.responseText);
                            if (h.success === true) {
                                f.store.reload();
                                CNOA.msg.notice(h.msg, lang("FlowHasBeenTerminated"));
                                try {
                                    CNOA_notice_notice_todo.reload()
                                } catch(k) {}
                            } else {
                                CNOA.msg.alert(h.msg)
                            }
                        }
                    })
                }
            })
    },
    makeForm: function(b, d, g, f, a, c, e) {
        mainPanel.closeTab("CNOA_MENU_WF_USE_OPENFLOW");
        mainPanel.loadClass("index.php?app=wf&func=flow&action=mgr&modul=list&task=loadPage&from=viewMgrflow&uFlowId=" + b + "&flowId=" + d + "&step=" + g + "&stepUid=" + f + "&status=" + a + "&flowType=" + c + "&tplSort=" + e, "CNOA_MENU_WF_USE_OPENFLOW", lang("viewFlow"), "icon-flow")
    },
    trustWin: function(a, c, f, e, b, d) {
        var g = this;
        CNOA_wf_mgr_list_entrust = new CNOA_wf_mgr_list_entrustClass(a, f, e, b, d)
    },
    warn: function(m, b, e, h, n, j, k, a) {
        var f = this;
        var g = function() {
            var o = d.getForm().findField("say").getValue();
            Ext.Ajax.request({
                url: f.baseUrl + "&task=warnFlow",
                method: "POST",
                params: {
                    uFlowId: m,
                    flowId: b,
                    stepId: e,
                    stepUid: h,
                    say: o,
                    flowType: n,
                    tplSort: j,
                    sms: a
                },
                success: function(q) {
                    var p = Ext.decode(q.responseText);
                    if (p.success === true) {
                        c.close();
                        CNOA.msg.notice(p.msg, lang("hasNotifiedCurrent"))
                    } else {
                        CNOA.msg.alert(p.msg)
                    }
                }
            })
        };
        var d = new Ext.form.FormPanel({
            border: false,
            hideBorders: false,
            layout: "fit",
            items: [{
                xtype: "textarea",
                name: "say",
                emptyText: lang("pleaseFillSupervisory")
            }]
        });
        var c = new Ext.Window({
            title: lang("overseeReason"),
            border: false,
            width: 300,
            height: 150,
            layout: "fit",
            modal: true,
            resizable: false,
            items: d,
            buttons: [{
                text: lang("supervision"),
                handler: function(o, p) {
                    g()
                }
            },
                {
                    text: lang("close"),
                    handler: function() {
                        c.close()
                    }
                }]
        }).show()
    },
    modify: function(a, c, f, e, b, d) {
        var g = this;
        CNOA_wf_mgr_list_modify = new CNOA_wf_mgr_listModifyClass(a, c, f, e, b, d)
    },
    cancelflow: function(a, c, d, b, e) {
        var f = this;
        CNOA.msg.cf(lang("sureWantCancelFlow"),
            function(g) {
                if (g == "yes") {
                    Ext.Ajax.request({
                        url: f.baseUrl + "&task=cancelFlow",
                        method: "POST",
                        params: {
                            uFlowId: a,
                            flowId: c,
                            stepId: d,
                            flowType: b,
                            tplSort: e
                        },
                        success: function(j) {
                            var h = Ext.decode(j.responseText);
                            if (h.success === true) {
                                CNOA.msg.notice(h.msg, lang("flowRevoked"));
                                f.store.reload()
                            } else {
                                CNOA.msg.alert(h.msg,
                                    function() {})
                            }
                        }
                    })
                }
            })
    },
    note: function(a, b, c) {
        var d = this;
        CNOA.msg.cf(lang("wantDelThisFlow"),
            function(e) {
                if (e == "yes") {
                    Ext.Ajax.request({
                        url: d.baseUrl + "&task=delete",
                        method: "POST",
                        params: {
                            uFlowId: a,
                            flowType: b,
                            tplSort: c
                        },
                        success: function(g) {
                            var f = Ext.decode(g.responseText);
                            if (f.success === true) {
                                CNOA.msg.notice(f.msg, lang("flowMgr"));
                                d.store.reload()
                            } else {
                                CNOA.msg.alert(f.msg)
                            }
                        }
                    })
                }
            })
    },
    selectStep: function(uFlowId, flowId, stepId, stepUid, flowType, tplSort, type) {
        var _this = this;
        var items = {
            xtype: "radiogroup",
            columns: 1,
            items: []
        };
        var stepStore = new Ext.data.Store({
            proxy: new Ext.data.HttpProxy({
                url: _this.baseUrl + "&task=getManageStep"
            }),
            reader: new Ext.data.JsonReader({
                totalProperty: "total",
                root: "data",
                fields: this.fields
            }),
            listeners: {
                load: function(th, record) {
                    Ext.each(record,
                        function(ch, index) {
                            items.items.push({
                                boxLabel: ch.json.stepname,
                                inputValue: ch.json.id,
                                name: "stepId"
                            })
                        });
                    items.items[0].checked = "true";
                    stepPanel.add(items);
                    stepPanel.doLayout()
                }
            }
        });
        stepStore.load({
            params: {
                uFlowId: uFlowId
            }
        });
        var stepPanel = new Ext.form.FormPanel({
            labelAlign: "right",
            autoScroll: true,
            labelWidth: 20,
            waitMsgTarget: true,
            border: false,
            autoScroll: true
        });
        var smsCheckbox = Ext.id();
        var win = new Ext.Window({
            title: lang("pleaseSelectStep"),
            width: 300,
            height: 200,
            layout: "fit",
            modal: true,
            items: stepPanel,
            buttons: [{
                xtype: "checkbox",
                id: smsCheckbox,
                hidden: type == "warn" ? false: true,
                boxLabel: lang("SMSnotification")
            },
                {
                    text: lang("ok"),
                    iconCls: "icon-order-s-accept",
                    handler: function() {
                        var sms = Ext.getCmp(smsCheckbox).getValue();
                        if (type != "" && type != undefined) {
                            stepId = stepPanel.getForm().findField("stepId").getValue();
                            eval("_this." + type + "(uFlowId, flowId, stepId, stepUid, flowType, tplSort, type, sms);")
                        }
                        win.close()
                    }.createDelegate(this)
                },
                {
                    text: lang("cancel"),
                    iconCls: "icon-dialog-cancel",
                    handler: function() {
                        win.close()
                    }.createDelegate(this)
                }]
        }).show()
    },
    again: function(a, c, b, d) {
        var e = this;
        Ext.Ajax.request({
            url: e.baseUrl + "&task=again",
            method: "POST",
            params: {
                flowId: c
            },
            success: function(g) {
                var f = Ext.decode(g.responseText);
                var h = f.nameRuleId;
                mainPanel.closeTab("CNOA_MENU_WF_USE_OPENFLOW");
                mainPanel.loadClass("index.php?app=wf&func=flow&action=use&modul=new&task=loadPage&from=newflow&flowId=" + c + "&nameRuleId=" + h + "&flowType=" + b + "&tplSort=" + d + "&childId=0&puFlowId=0&againId=" + a, "CNOA_MENU_WF_USE_OPENFLOW", lang("FqNewFixedFlow"), "icon-flow-new")
            }
        })
    }
};
var CNOA_wf_mgr_listViewClass, CNOA_wf_mgr_listView;
var CNOA_wf_mgr_listView_fenfaClass, CNOA_wf_mgr_listView_fenfa;
CNOA_wf_mgr_listView_fenfaClass = CNOA.Class.create();
CNOA_wf_mgr_listView_fenfaClass.prototype = {
    init: function(c, g) {
        var e = this;
        this.baseUrl = "index.php?app=wf&func=flow&action=mgr&modul=list";
        var a = [{
            name: "id"
        },
            {
                name: "fenfaUname"
            },
            {
                name: "viewUname"
            },
            {
                name: "viewtime"
            },
            {
                name: "say"
            },
            {
                name: "isread"
            }];
        this.fenfaStore = new Ext.data.Store({
            autoLoad: true,
            proxy: new Ext.data.HttpProxy({
                url: e.baseUrl + "&task=getFenfaList&uFlowId=" + c
            }),
            reader: new Ext.data.JsonReader({
                totalProperty: "total",
                root: "data",
                fields: a
            })
        });
        var d = function(h) {
            return '<a onclick="CNOA_wf_mgr_listView_fenfa.delFenfa(' + h + ')">' + lang("del") + "</a>"
        };
        var b = new Ext.grid.GridPanel({
            border: false,
            store: this.fenfaStore,
            trackMouseOver: false,
            disableSelection: true,
            loadMask: true,
            autoScroll: true,
            columns: [{
                header: lang("distributePeople"),
                dataIndex: "fenfaUname"
            },
                {
                    header: lang("pyMan"),
                    dataIndex: "viewUname"
                },
                {
                    header: lang("pyTime"),
                    dataIndex: "viewtime",
                    width: 120
                },
                {
                    header: lang("pyContent"),
                    dataIndex: "say",
                    width: 180
                },
                {
                    header: lang("status"),
                    dataIndex: "isread",
                    width: 50
                },
                {
                    header: lang("opt"),
                    dataIndex: "id",
                    width: 80,
                    renderer: d
                }],
            tbar: [{
                text: lang("refresh"),
                iconCls: "icon-system-refresh",
                handler: function() {
                    e.fenfaStore.reload()
                }
            },
                "-", {
                    text: lang("addPeople"),
                    iconCls: "icon-utils-s-add",
                    handler: function() {
                        e.addFenfa(c, g)
                    }
                }]
        });
        var f = new CNOA.wf.dealWindow({
            width: 650,
            height: 400,
            title: lang("distribute"),
            resizable: false,
            modal: true,
            layout: "fit",
            items: b,
            buttons: [{
                text: lang("close"),
                handler: function() {
                    f.close()
                }
            }]
        }).show()
    },
    addFenfa: function(a, c) {
        var b = this;
        new Ext.SelectorPanel({
            target: "user",
            multiselect: true,
            dataUrl: "index.php?action=commonJob&act=getSelectorData&target=user",
            listeners: {
                select: function(e, f, g, d) {
                    var h = {
                        uFlowId: a,
                        stepId: c,
                        toUids: d.join(",")
                    };
                    Ext.Ajax.request({
                        url: b.baseUrl + "&task=addFenfa",
                        method: "POST",
                        params: h,
                        success: function(k) {
                            var j = Ext.decode(k.responseText);
                            if (j.success === true) {
                                CNOA.msg.notice(j.msg, lang("dealFlow"));
                                b.fenfaStore.reload()
                            } else {
                                CNOA.msg.alert(j.msg)
                            }
                        }
                    })
                }
            }
        })
    },
    delFenfa: function(b) {
        var a = this;
        CNOA.msg.cf(lang("sureWantDelHQ"),
            function(c) {
                if (c == "yes") {
                    Ext.Ajax.request({
                        url: a.baseUrl + "&task=delFenfa",
                        method: "POST",
                        params: {
                            id: b
                        },
                        success: function(e) {
                            var d = Ext.decode(e.responseText);
                            if (d.success === true) {
                                CNOA.msg.notice(d.msg, lang("delFlowDistribution"));
                                a.fenfaStore.reload()
                            } else {
                                CNOA.msg.alert(d.msg)
                            }
                        }
                    })
                }
            })
    }
};
CNOA_wf_mgr_listViewClass = CNOA.Class.create();
CNOA_wf_mgr_listViewClass.prototype = {
    init: function() {
        var a = this;
        this.ID_stepTable = Ext.id();
        this.ID_flowTitle = Ext.id();
        this.attachmentCt = Ext.id();
        this.ID_attachTable = Ext.id();
        this.ID_displayAt = Ext.id();
        this.ID_readTable = Ext.id();
        this.ID_displayRd = Ext.id();
        this.ID_CNOA_wf_use_flowPanel_ct = Ext.id();
        this.ID_CNOA_wf_use_formPanel_ct = Ext.id();
        this.ID_CNOA_wf_use_attachPanel_ct = Ext.id();
        this.ID_CNOA_wf_use_readerPanel_ct = Ext.id();
        this.ID_CNOA_wf_use_stepPanel_ct = Ext.id();
        this.ID_HtmlEditor = Ext.id();
        this.uFlowId = CNOA.wf.mgr_listView.uFlowId;
        this.flowId = CNOA.wf.mgr_listView.flowId;
        this.step = CNOA.wf.mgr_listView.step;
        this.stepUid = CNOA.wf.mgr_listView.stepUid;
        this.status = CNOA.wf.mgr_listView.status;
        this.flowNumber = CNOA.wf.mgr_listView.flowNumber;
        this.flowType = CNOA.wf.mgr_listView.flowType;
        this.tplSort = CNOA.wf.mgr_listView.tplSort;
        this.baseUrl = "index.php?app=wf&func=flow&action=mgr&modul=list&uFlowId=" + a.uFlowId + "&flowId=" + this.flowId + "&step=" + this.step + "&" + getSessionStr();
        this.baseURI = location.href.substr(0, location.href.lastIndexOf("/") + 1);
        this.formPanel = new Ext.form.FormPanel({
            fileUpload: false,
            hideBorders: true,
            border: false,
            region: "center",
            autoScroll: true,
            layout: "htmlform",
            defaults: {
                xtype: "displayfield",
                width: 300
            },
            layoutConfig: {
                cache: false,
                template: this.tplSort == 3 ? "app/wf/tpl/default/flow/mgr/listView_tplSort3.tpl.html": "app/wf/tpl/default/flow/mgr/listView.tpl.html",
                templateData: {
                    str_flowInfo: lang("flowInformation"),
                    str_flowNumber: lang("flowNumber"),
                    str_flowLevel: lang("importantGrade"),
                    str_flowName: lang("flowName"),
                    str_flowReason: lang("appReason"),
                    str_flowFormInfo: lang("formInfo"),
                    str_flowAttach: lang("attachmentInfo"),
                    str_flowAttachName: lang("attName"),
                    str_flowUploadBy: lang("uploadBy"),
                    str_flowUploadTime: lang("uploadTime"),
                    str_flowOpt: lang("opt"),
                    str_flowTextInfo: lang("textInfo"),
                    str_flowShowDetail: lang("showDetail"),
                    str_flowStatus: lang("processState"),
                    str_flowInitiator: lang("initiator"),
                    str_flowInitTime: lang("initTime"),
                    str_flowReadInfo: lang("reviewInformation"),
                    str_flowFFpeople: lang("distributePeople"),
                    str_flowPyPeople: lang("pyMan"),
                    str_flowBelongDept: lang("belongDept"),
                    str_flowPyContent: lang("pyContent"),
                    str_flowPyDate: lang("pyDate"),
                    str_flowShowHide: lang("cllickShowHide"),
                    id_flowPanel: this.ID_CNOA_wf_use_flowPanel_ct,
                    id_formPanel: this.ID_CNOA_wf_use_formPanel_ct,
                    id_displayAt: this.ID_displayAt,
                    id_attachPanel: this.ID_CNOA_wf_use_attachPanel_ct,
                    id_attachTable: this.ID_attachTable,
                    id_flowTitle: this.ID_flowTitle,
                    id_displayRd: this.ID_displayRd,
                    id_readTable: this.ID_readTable,
                    id_readerPanel: this.ID_CNOA_wf_use_readerPanel_ct
                }
            },
            items: [{
                name: "flowNumber"
            },
                {
                    name: "flowName"
                },
                {
                    name: "status"
                },
                {
                    name: "uname"
                },
                {
                    name: "postTime"
                },
                {
                    name: "reason",
                    cls: "wf-reason"
                },
                {
                    name: "level"
                }],
            listeners: {
                afterrender: function() {
                    setTimeout(function() {
                        a.loadFlowPanel()
                    })
                }
            },
            bbar: new Ext.Toolbar({
                items: [{
                    handler: function(b, c) {
                        if (a.flowType == 0 && a.tplSort == 0) {
                            a.printFlow()
                        } else {
                            a.printFreeFlow()
                        }
                    }.createDelegate(this),
                    hidden: Ext.isAir ? true: false,
                    iconCls: "icon-print",
                    text: lang("print") + " / " + lang("export2")
                },
                    "-", {
                        handler: function(b, c) {
                            a.warnFlow(this.uFlowId, this.flowId, this.step, this.stepUid)
                        }.createDelegate(this),
                        iconCls: "icon-application--pencil",
                        hidden: (this.status == 2 || this.status == 4) ? true: false,
                        text: lang("supervision")
                    },
                    {
                        iconCls: "icon-flow-dispense",
                        tooltip: lang("distribute"),
                        text: lang("distribute"),
                        hidden: this.status == 2 ? false: true,
                        handler: function(b, c) {
                            CNOA_wf_mgr_listView_fenfa = new CNOA_wf_mgr_listView_fenfaClass(a.uFlowId, a.step, a.flowType, a.tplSort)
                        }
                    },
                    {
                        text: lang("flowStep"),
                        tooltip: lang("flowStep"),
                        iconCls: "icon-application-task",
                        handler: function() {
                            a.showFlowStep()
                        }.createDelegate(this)
                    },
                    {
                        text: lang("flowEvent"),
                        tooltip: lang("flowEvent"),
                        iconCls: "icon-event",
                        handler: function() {
                            a.showFlowEvent()
                        }.createDelegate(this)
                    },
                    {
                        text: lang("flowChart"),
                        tooltip: lang("flowChart"),
                        iconCls: "icon-flow",
                        hidden: this.flowType != 0 ? true: false,
                        handler: function(b, c) {
                            CNOA_wf_use_flowpreview = new CNOA_wf_use_flowpreviewClass(a.flowId, a.uFlowId)
                        }.createDelegate(this)
                    },
                    {
                        tooltip: lang("seeRelateFlow"),
                        text: lang("seeRelateFlow"),
                        iconCls: "icon-collapse-all",
                        hidden: CNOA.wf.mgr_listView.relevanceUFlowInfo == 0 ? true: false,
                        menu: [],
                        listeners: {
                            beforerender: function(c) {
                                if (CNOA.wf.mgr_listView.relevanceUFlowInfo != 0) {
                                    var b = Ext.decode(CNOA.wf.mgr_listView.relevanceUFlowInfo);
                                    var d = [];
                                    Ext.each(b,
                                        function(e, f) {
                                            var g = {
                                                text: e.flowNumber,
                                                handler: function() {
                                                    mainPanel.closeTab("CNOA_MENU_WF_USE_JUMPVIEW");
                                                    mainPanel.loadClass("index.php?app=wf&func=flow&action=use&modul=todo&task=loadPage&type=show&from=showflow&uFlowId=" + e.uFlowId + "&flowId=" + e.flowId + "&step=" + e.step + "&flowType=" + e.flowType + "&tplSort=" + e.tplSort, "CNOA_MENU_WF_USE_JUMPVIEW", lang("seeRelateFlow"), "icon-flow")
                                                }
                                            };
                                            d[f] = g
                                        });
                                    c.menu.addItem(d)
                                }
                            }
                        }
                    },
                    {
                        iconCls: "icon-dialog-cancel",
                        text: lang("close"),
                        handler: function(b, c) {
                            a.closeTab()
                        }.createDelegate(this)
                    }]
            })
        });
        this.mainPanel = new Ext.Panel({
            collapsible: false,
            hideBorders: true,
            border: false,
            layout: "border",
            items: [this.formPanel]
        })
    },
    warnFlow: function(b, c, g, f) {
        var h = this;
        var e = function() {
            var j = a.getForm().findField("say").getValue();
            Ext.Ajax.request({
                url: h.baseUrl + "&task=warnFlow",
                method: "POST",
                params: {
                    uFlowId: b,
                    flowId: c,
                    stepId: g,
                    stepUid: f,
                    say: j,
                    flowType: h.flowType,
                    tplSort: h.tplSort
                },
                success: function(m) {
                    var k = Ext.decode(m.responseText);
                    if (k.success === true) {
                        d.close();
                        CNOA.msg.notice(k.msg, lang("hasNotifiedCurrent"))
                    } else {
                        CNOA.msg.alert(k.msg)
                    }
                }
            })
        };
        var a = new Ext.form.FormPanel({
            border: false,
            hideBorders: false,
            layout: "fit",
            items: [{
                xtype: "textarea",
                name: "say",
                emptyText: lang("pleaseFillSupervisory")
            }]
        });
        var d = new Ext.Window({
            title: lang("overseeReason"),
            border: false,
            width: 300,
            height: 150,
            layout: "fit",
            modal: true,
            resizable: false,
            items: a,
            buttons: [{
                text: lang("supervision"),
                handler: function(j, k) {
                    e()
                }
            },
                {
                    text: lang("close"),
                    handler: function() {
                        d.close()
                    }
                }]
        }).show()
    },
    loadFlowStepData: function() {
        var a = this;
        Ext.Ajax.request({
            url: a.baseUrl + "&task=loadFlowStepData",
            method: "POST",
            success: function(c) {
                var b = Ext.decode(c.responseText);
                if (b.success === true) {} else {
                    CNOA.msg.alert(b.msg,
                        function() {})
                }
            }
        })
    },
    displayFlowInfo: function() {
        var c = this;
        var a = $("#" + this.ID_flowTitle);
        var b = $("#" + this.ID_CNOA_wf_use_flowPanel_ct);
        b.slideToggle("fast",
            function() {
                if ($(this).css("display") == "block") {
                    a.children("a").text(lang("hideDetails"));
                    a.children("span").text(lang("flowInformation"));
                    a.parent().css("border-bottom-width", "1px")
                } else {
                    a.children("a").text(lang("showDetail"));
                    a.children("span").text(c.flowTitle);
                    a.parent().css("border-bottom-width", "0")
                }
            })
    },
    loadFlowPanel: function() {
        var b = this;
        var a = function() {
            var c = b.baseUrl + "&task=ms_loadTemplateFile&tplSort=" + b.tplSort;
            if (b.tplSort == 1 || b.tplSort == 3) {
                var d = "doc"
            } else {
                var d = "xls"
            }
            $("#wf_view_formct").html("").css("border", "none");
            openOfficeForView_WF("wf_view_formct", c, d, "自由流程表单")
        };
        if (b.flowType == 0) {
            if (b.tplSort == 0 || b.tplSort == 3) {
                Ext.Ajax.request({
                    url: b.baseUrl + "&task=loadFormHtml",
                    method: "POST",
                    success: function(d) {
                        var c = Ext.decode(d.responseText);
                        if (c.success === true) {
                            Ext.fly(b.ID_CNOA_wf_use_formPanel_ct).update(c.data.formHtml + '<div style="clear:both;"></div>');
                            CNOA_wf_form_checker.formInitForView();
                            setPageSet($("#" + b.ID_CNOA_wf_use_formPanel_ct).get(0), Ext.decode(c.pageset))
                        } else {
                            CNOA.msg.alert(c.msg,
                                function() {})
                        }
                    }
                });
                if (b.tplSort == 3) {
                    a()
                }
            } else {
                a()
            }
        } else {
            if (b.tplSort == 0) {} else {
                a()
            }
        }
        b.loadUflowInfo();
        if (b.flowType == 0 && b.tplSort == 0) {
            $("#wf_form_newfree_tushi").children().show()
        } else {
            $("#wf_form_newfree_tushi").children().hide()
        }
    },
    loadUflowInfo: function() {
        var c = this;
        var a = c.formPanel,
            b = a.getForm();
        b.load({
            url: c.baseUrl + "&task=loadUflowInfo",
            method: "POST",
            params: {
                flowId: this.flowId,
                uFlowId: this.uFlowId,
                step: this.step,
                flowType: this.flowType,
                tplSort: this.tplSort
            },
            success: function(d, e) {
                if (c.flowType != 0) {
                    if (c.tplSort == 0 || c.tplSort == 3) {
                        Ext.fly(c.ID_CNOA_wf_use_formPanel_ct).update(e.result.data.htmlFormContent)
                    }
                }
                c.initView(e);
                setTimeout(function() {
                        var f = $("[mark=flowview]");
                        f.each(function(g) {
                            var h = Ext.decode($(this).attr("data"));
                            $(this).bind("click",
                                function() {
                                    mainPanel.closeTab("CNOA_MENU_WF_USE_JUMPVIEW");
                                    if (h.status == 1) {
                                        mainPanel.loadClass("index.php?app=wf&func=flow&action=use&modul=todo&task=loadPage&type=show&from=showflow&uFlowId=" + h.uFlowId + "&flowId=" + h.flowId + "&step=" + h.stepId + "&flowType=" + h.flowType + "&tplSort=" + h.tplSort, "CNOA_MENU_WF_USE_JUMPVIEW", lang("seeRelateFlow"), "icon-flow")
                                    } else {
                                        if (h.status == 2) {
                                            mainPanel.loadClass("index.php?app=wf&func=flow&action=use&modul=done&task=loadPage&from=showflow&uFlowId=" + h.uFlowId + "&flowId=" + h.flowId + "&step=" + h.stepId + "&flowType=" + h.flowType + "&tplSort=" + h.tplSort + "&owner=" + h.owner, "CNOA_MENU_WF_USE_JUMPVIEW", lang("seeRelateFlow"), "icon-flow")
                                        }
                                    }
                                })
                        })
                    },
                    100)
            },
            failure: function(d, e) {
                CNOA.msg.alert(e.result.msg,
                    function() {})
            }.createDelegate(this)
        })
    },
    initView: function(b) {
        var d = this;
        var c = $("#" + d.ID_flowTitle);
        d.flowTitle = lang("flow") + ": " + b.result.data.flowNumber + "(" + b.result.data.level + ")  ";
        d.flowTitle += "[" + b.result.data.uname + "]";
        d.flowTitle += "[" + b.result.data.postTime + "]";
        c.children("span").text(d.flowTitle);
        c.children("span").css("font-weight", "600");
        if (b.result.attach.length > 0) {
            Ext.fly(d.ID_displayAt).dom.style.display = "block";
            d.createAttachList(b.result.attach)
        }
        if (b.result.readInfo.length > 0) {
            Ext.fly(d.ID_displayRd).dom.style.display = "block";
            d.createReadList(b.result.readInfo)
        }
        this.changeWidth();
        var a = this.mainPanel.getId();
        this.mainPanel.on("resize",
            function() {
                d.changeWidth(a)
            })
    },
    changeWidth: function(b) {
        var c = $("#" + b);
        var d = c.find(".wf_div_cttb").width();
        var a = c.width() - 40;
        if (a < d) {
            c.find(".cnoa-formhtml-layout").width(d + 40)
        } else {
            c.find(".cnoa-formhtml-layout").css("width", "")
        }
    },
    createAttachList: function(e, a) {
        var f = this;
        var d = jQuery("#" + this.ID_attachTable + " tr");
        d.each(function(g) {
            var h = $(this);
            if ((g > 0) && h.attr("upd") != "true") {
                h.remove()
            }
        });
        for (var b = 0; b < e.length; b++) {
            var c = '<tr upd="true" height=24 align="left" bgColor="#FFFFFF"><td bgColor="#FFFFFF">&nbsp;' + e[b].filename + "[" + lang("uploaded") + ']<input type="hidden" name="wf_attach_' + e[b].attachid + '" value="1" /></td><td bgColor="#FFFFFF">&nbsp;' + e[b].uname + '</td><td bgColor="#FFFFFF">&nbsp;' + e[b].date + '</td><td bgColor="#FFFFFF" id="att">&nbsp;' + e[b].optStr + "</td>";
            "</tr>";
            jQuery(c).appendTo(jQuery("#" + f.ID_attachTable))
        }
    },
    createReadList: function(b) {
        var e = this;
        var d = jQuery("#" + this.ID_readTable + " tr");
        for (var a = 0; a < b.length; a++) {
            var c = '<tr upd="true" height=24 align="left" bgColor="#FFFFFF"><td bgColor="#FFFFFF">&nbsp;' + b[a].fenfaName + '</td><td bgColor="#FFFFFF">&nbsp;' + b[a].uname + '</td><td bgColor="#FFFFFF">&nbsp;' + b[a].deptment + '</td><td bgColor="#FFFFFF">&nbsp;' + b[a].say + '</td><td bgColor="#FFFFFF" id="att">&nbsp;' + b[a].sayDate + "</td>";
            "</tr>";
            jQuery(c).appendTo(jQuery("#" + e.ID_readTable))
        }
    },
    removefile: function(a) {
        var b = this;
        CNOA.msg.cf(lang("sureDelAttchment"),
            function(c) {
                if (c == "yes") {
                    jQuery(a).parent().parent("tr").remove();
                    var d = jQuery("#" + b.ID_attachTable + " tr").length;
                    if (d == 1) {
                        Ext.fly(b.ID_displayAt).dom.style.display = "none"
                    }
                }
            })
    },
    printFlow: function() {
        window.open(this.baseUrl + "&task=exportFlow&target=printer&flowId=" + this.flowId + "&uFlowId=" + this.uFlowId + "&step=" + this.step, "wfexport", "width=740,height=" + (screen.availHeight - 120) + ",left=" + ((screen.availWidth - 740) / 2) + ",top=60,scrollbars=yes,resizable=yes,status=no")
    },
    printFreeFlow: function() {
        window.open("index.php?app=wf&func=flow&action=use&modul=todo&task=exportFreeFlow&flowId=" + this.flowId + "&uFlowId=" + this.uFlowId + "&step=" + this.step + "&flowType=" + this.flowType + "&tplSort=" + this.tplSort, "wfexport", "width=740,height=" + (screen.availHeight - 120) + ",left=" + ((screen.availWidth - 740) / 2) + ",top=60,scrollbars=yes,resizable=yes,status=no")
    },
    exportFlow: function(e) {
        var f = this;
        var a = Ext.id();
        var d = function() {
            b.getForm().submit({
                url: f.baseUrl + "&task=exportFlow&target=" + e,
                params: {
                    uFlowId: f.uFlowId
                },
                method: "POST",
                waitMsg: lang("waiting"),
                success: function(g, h) {
                    var j = Ext.getCmp(a);
                    j.getEl().update(lang("clickToDownload") + "：<br/>" + makeDownLoadIcon2(h.result.msg, "img"))
                }.createDelegate(this),
                failure: function(g, h) {
                    c.close();
                    CNOA.msg.alert(h.result.msg,
                        function() {})
                }.createDelegate(this)
            })
        };
        var b = new Ext.form.FormPanel({
            autoWidth: true,
            border: false,
            hideBorders: true,
            labelWidth: 70,
            labelAlign: "right",
            waitMsgTarget: true,
            bodyStyle: "padding:10px;",
            listeners: {
                afterrender: function(g) {
                    d()
                }
            },
            items: [{
                xtype: "displayfield",
                name: "name",
                fieldLabel: lang("flowName"),
                value: lang("flowUseName")
            },
                new Ext.BoxComponent({
                    id: a,
                    autoEl: {
                        tag: "div",
                        style: "padding-left:16px",
                        html: ""
                    }
                })]
        });
        var c = new Ext.Window({
            title: lang("exportWorkFlow"),
            width: 320,
            height: 150,
            layout: "fit",
            modal: true,
            maximizable: false,
            resizable: false,
            items: [b],
            buttons: [{
                text: lang("close"),
                iconCls: "icon-dialog-cancel",
                handler: function() {
                    c.close()
                }
            }]
        }).show()
    },
    showFlowStep: function() {
        var a = this;
        CNOA_wf_use_showFlowStep = new CNOA_wf_use_showFlowStepClass(this.uFlowId, true, false, this.flowId, this.flowType, this.tplSort)
    },
    showFlowEvent: function() {
        var a = this;
        CNOA_wf_use_showFlowEvent = new CNOA_wf_use_showFlowEventClass(this.uFlowId, true, this.flowId, this.flowType, this.tplSort)
    },
    closeTab: function() {
        mainPanel.closeTab("CNOA_MENU_WF_USE_OPENFLOW")
    }
};
var CNOA_wf_manager_trustClass, CNOA_wf_manager_trust;
var CNOA_wf_manager_trustAddEditClass, CNOA_wf_manager_trustAddEdit;
CNOA_wf_manager_trustAddEditClass = CNOA.Class.create();
CNOA_wf_manager_trustAddEditClass.prototype = {
    init: function(e, b) {
        var f = this;
        this.ID_save = Ext.id();
        this.ID_rightPanelCt = Ext.id();
        this.ID_fromuid_select = Ext.id();
        this.ID_touid_select = Ext.id();
        this.edit_id = b;
        this.type = e == "edit" ? "edit": "add";
        this.title = e == "edit" ? "修改委托规则": "新建委托规则";
        this.baseUrl = "index.php?app=wf&func=flow&action=manager&modul=trust";
        this.leftPanel = new Ext.Panel({
            width: 200,
            border: false,
            region: "west",
            bodyStyle: "border-right-width:3px;padding: 10px",
            layout: "form",
            items: [{
                xtype: "userselectorfield",
                id: this.ID_fromuid_select,
                fieldLabel: lang("principal1"),
                width: 175,
                name: "fromuid",
                allowBlank: false,
                multiSelect: false,
                listeners: {
                    confirm: function(g) {
                        f.loadAddFormData(g.getValue())
                    }
                }
            },
                {
                    xtype: "userselectorfield",
                    id: this.ID_touid_select,
                    fieldLabel: lang("mandatary"),
                    width: 175,
                    allowBlank: false,
                    name: "touid",
                    multiSelect: false
                },
                {
                    xtype: "datetimefield",
                    name: "stime",
                    editable: false,
                    fieldLabel: lang("startTime"),
                    minValue: new Date(),
                    allowBlank: false,
                    width: 175
                },
                {
                    xtype: "compositefield",
                    items: [{
                        xtype: "datetimefield",
                        fieldLabel: lang("endTime"),
                        name: "etime",
                        editable: false,
                        minValue: new Date(),
                        width: 140
                    },
                        {
                            xtype: "button",
                            text: lang("clear2"),
                            hideLabel: true,
                            handler: function() {
                                f.formPanel.getForm().findField("etime").setValue("")
                            }
                        }]
                },
                {
                    xtype: "compositefield",
                    fieldLabel: lang("principalReason"),
                    items: [{
                        xtype: "textarea",
                        width: 175,
                        height: 40,
                        name: "say"
                    }]
                },
                {
                    xtype: "displayfield",
                    hideLabel: true,
                    value: '<span class="cnoa_color_red">' + lang("noteWhenEtimeEmpty") + "</span>"
                }]
        });
        this.rightPanel = new Ext.Panel({
            region: "center",
            border: false,
            hideBorders: true,
            id: this.ID_rightPanelCt,
            html: lang("pleaseSelectPrincipal"),
            autoScroll: true,
            tbar: new Ext.Toolbar({
                items: [new Ext.BoxComponent({
                    autoEl: {
                        tag: "div",
                        style: "margin-left: 15px;margin-top:8px;font-weight:800;font-size:12px;",
                        html: '<label><input type=checkBox id="wf_proxy_selectAll" style="margin-right:3px;">' + lang("selectAll") + "</label></input>"
                    }
                }), f.listLength = new Ext.BoxComponent({
                    autoEl: {
                        tag: "div",
                        style: "margin-left: 15px;margin-top:8px;font-weight:800;font-size:12px;",
                        html: ""
                    }
                })]
            })
        });
        this.formPanel = new Ext.form.FormPanel({
            layout: "border",
            border: false,
            labelAlign: "top",
            items: [this.leftPanel, this.rightPanel]
        });
        var d = Ext.getBody().getBox();
        var a = d.width - 70;
        var c = d.height - 70;
        this.mainPanel = new Ext.Window({
            title: this.title,
            layout: "fit",
            width: a,
            height: c,
            modal: true,
            maximizable: true,
            resizable: false,
            items: [this.formPanel],
            buttons: [{
                text: lang("save"),
                id: f.ID_save,
                cls: "btn-blue4",
                iconCls: "icon-btn-save",
                handler: function() {
                    f.submit()
                }
            },
                {
                    text: lang("cancel"),
                    cls: "btn-red1",
                    iconCls: "icon-dialog-cancel",
                    handler: function() {
                        f.mainPanel.close()
                    }.createDelegate(this)
                }]
        }).show();
        if (f.type == "edit") {
            Ext.getCmp(f.ID_fromuid_select).disable();
            Ext.getCmp(f.ID_touid_select).disable();
            f.loadEditFormData()
        }
    },
    makeProxyFlowField: function(d) {
        var e = this;
        var b = 0;
        var a = 0;
        var c = '<div id="wf_proxy_set" class="cnoa-formhtml-layout" style="padding:5px;" >';
        Ext.each(d,
            function(h, f) {
                var g = Ext.id();
                c += '<table width="100%" border="0" cellspacing="1" cellpadding="0" style="margin-bottom:5px;" id="wf_proxy_set_ct_' + h.sortId + '">';
                c += '    <td width="40%" valign="middle" class="lable" style="padding:10px;">';
                c += '	 <label for="wf_proxy_set_ck_' + g + '"><input sortId="' + h.sortId + '" id="wf_proxy_set_ck_' + g + '" type="checkbox"> ' + lang("sortName") + ": " + h.sname + "</label></input>";
                c += "	 </td>";
                c += "  </tr>";
                c += "  <tr>";
                c += '    <td class="field" valign="top" style="padding:0;">';
                c += '	  <div style="overflow:auto;padding:5px 5px 0 5px;" id="wf-proxy-flowList">';
                Ext.each(h.items,
                    function(n, k) {
                        var m = n.enable ? " enable": " disable";
                        var j = n.checked ? " selecteds": "";
                        n.enable ? b++:a++;
                        c += '<a class="wf-proxy-list-a flow' + m + j + '" flowId="' + n.flowId + '">' + n.name + "</a>"
                    });
                c += "	  </div>";
                c += "	</td>";
                c += "  </tr>";
                c += "</table>"
            });
        c += "</div>";
        e.rightPanel.body.update(c);
        e.listLength.update(lang("total") + '<span class="cnoa_color_red">' + b + '</span>条可选流程，<span class="cnoa_color_red">' + a + "</span>条不可选流程。");
        $("input[id=wf_proxy_selectAll]").click(function() {
            var f = $(this).attr("checked");
            $("#wf_proxy_set .wf-proxy-list-a.enable").each(function() {
                f ? $(this).addClass("selecteds") : $(this).removeClass("selecteds")
            });
            $("input[id^=wf_proxy_set_ck_]").each(function() {
                $(this).attr("checked", f)
            })
        });
        $("input[id^=wf_proxy_set_ck_]").each(function() {
            $(this).click(function() {
                var f = $(this).attr("sortId");
                var g = $(this).attr("checked");
                $("#wf_proxy_set_ct_" + f + " .wf-proxy-list-a.enable").each(function() {
                    g ? $(this).addClass("selecteds") : $(this).removeClass("selecteds")
                })
            })
        });
        $(".wf-proxy-list-a.enable").each(function() {
            $(this).click(function() {
                $(this).toggleClass("selecteds")
            })
        })
    },
    submit: function() {
        var e = this;
        var d = [];
        $(".wf-proxy-list-a.flow.selecteds").each(function() {
            d.push(parseInt($(this).attr("flowid"), 10))
        });
        var b = e.formPanel.getForm();
        if (b.isValid()) {
            var a = b.findField("stime");
            var c = b.findField("etime");
            if (!Ext.isEmpty(c.getValue())) {
                if (a.getValue() > c.getValue()) {
                    CNOA.miniMsg.alertShowAt(Ext.getCmp(e.ID_save), lang("StimeGreaterThanEtime"));
                    return false
                }
            }
            if (d.length < 1) {
                CNOA.miniMsg.alertShowAt(Ext.getCmp(e.ID_save), lang("notChosenEntrustFlow"));
                return false
            }
            b.submit({
                waitTitle: lang("notice"),
                url: e.baseUrl + "&task=" + e.type + "&id=" + e.edit_id,
                method: "POST",
                params: {
                    flowId: Ext.encode(d)
                },
                success: function(f, g) {
                    CNOA.msg.notice(g.result.msg, lang("flowEntrust"));
                    e.mainPanel.close();
                    CNOA_wf_manager_trust.store.reload()
                }.createDelegate(this),
                failure: function(f, g) {
                    CNOA.msg.alert(g.result.msg)
                }.createDelegate(this)
            })
        } else {
            CNOA.miniMsg.alertShowAt(Ext.getCmp(e.ID_save), lang("partOfFormCheck"))
        }
    },
    loadEditFormData: function() {
        var a = this;
        a.formPanel.getForm().load({
            url: a.baseUrl + "&task=loadEditFormData",
            params: {
                id: a.edit_id
            },
            method: "POST",
            success: function(b, c) {
                a.makeProxyFlowField(c.result.flow)
            },
            failure: function(b, c) {
                CNOA.msg.alert(c.result.msg,
                    function() {
                        a.mainPanel.close()
                    })
            }.createDelegate(this)
        })
    },
    loadAddFormData: function(a) {
        var b = this;
        b.formPanel.getForm().load({
            url: b.baseUrl + "&task=loadAddFormData",
            params: {
                fromuid: a
            },
            method: "POST",
            waitMsg: lang("waiting"),
            success: function(c, d) {
                b.makeProxyFlowField(d.result.flow)
            },
            failure: function(c, d) {
                CNOA.msg.alert(d.result.msg,
                    function() {
                        b.mainPanel.close()
                    })
            }.createDelegate(this)
        })
    }
};
CNOA_wf_manager_trustClass = CNOA.Class.create();
CNOA_wf_manager_trustClass.prototype = {
    init: function() {
        var b = this;
        var a = Ext.id();
        this.baseUrl = "index.php?app=wf&func=flow&action=manager&modul=trust";
        this.fields = [{
            name: "id"
        },
            {
                name: "status"
            },
            {
                name: "type"
            },
            {
                name: "fromName"
            },
            {
                name: "toName"
            },
            {
                name: "count"
            },
            {
                name: "stime"
            },
            {
                name: "etime"
            },
            {
                name: "proxyStatus"
            }];
        this.store = new Ext.data.Store({
            autoLoad: true,
            proxy: new Ext.data.HttpProxy({
                url: b.baseUrl + "&task=getJsonData"
            }),
            reader: new Ext.data.JsonReader({
                totalProperty: "total",
                root: "data",
                fields: this.fields
            })
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
                header: lang("status"),
                dataIndex: "status",
                width: 50,
                sortable: false,
                menuDisabled: true,
                renderer: this.statusCheck.createDelegate(this)
            },
            {
                header: lang("principal1"),
                dataIndex: "fromName",
                id: "fromName",
                width: 100,
                sortable: false,
                menuDisabled: true
            },
            {
                header: lang("mandatary"),
                dataIndex: "toName",
                width: 100,
                sortable: false,
                menuDisabled: true
            },
            {
                header: lang("startTime"),
                dataIndex: "stime",
                width: 120,
                sortable: false,
                menuDisabled: true
            },
            {
                header: lang("endTime"),
                dataIndex: "etime",
                width: 120,
                sortable: false,
                menuDisabled: true
            },
            {
                header: lang("quantity"),
                dataIndex: "count",
                width: 100,
                sortable: false,
                menuDisabled: true
            },
            {
                header: lang("commissionState"),
                dataIndex: "proxyStatus",
                width: 100,
                sortable: false,
                menuDisabled: true
            },
            {
                header: lang("opt"),
                dataIndex: "status",
                width: 280,
                sortable: false,
                menuDisabled: true,
                renderer: this.makOprate.createDelegate(this)
            },
            {
                header: "",
                dataIndex: "noIndex",
                width: 20,
                menuDisabled: true,
                resizable: false
            }]);
        this.pagingBar = new Ext.PagingToolbar({
            displayInfo: true,
            store: this.store,
            pageSize: 15
        });
        this.grid = new Ext.grid.GridPanel({
            region: "center",
            layout: "fit",
            border: false,
            store: b.store,
            loadMask: {
                msg: lang("waiting")
            },
            cm: this.colModel,
            sm: this.sm,
            stripeRows: true,
            hideBorders: true,
            autoExpandColumn: "fromName",
            listeners: {
                cellclick: function(d, h, f, g) {
                    var c = d.getStore().getAt(h);
                    if (f == 8) {
                        b.getTrustFlow(c.data.id)
                    }
                }
            },
            tbar: [{
                text: lang("commissionRuleList"),
                iconCls: "icon-wenType",
                enableToggle: true,
                allowDepress: false,
                cls: "btn-yellow1",
                pressed: true
            },
                {
                    text: lang("refresh"),
                    iconCls: "icon-system-refresh",
                    handler: function() {
                        b.store.reload()
                    }
                },
                {
                    text: lang("newCommissionRule"),
                    cls: "btn-blue4",
                    iconCls: "icon-utils-s-add",
                    handler: function() {
                        CNOA_wf_manager_trustAddEdit = new CNOA_wf_manager_trustAddEditClass("add")
                    }
                },
                {
                    text: lang("modify"),
                    id: b.ID_proxy_edit,
                    iconCls: "icon-utils-s-edit",
                    handler: function(d, e) {
                        var f = b.grid.getSelectionModel().getSelections();
                        if (f.length == 0) {
                            CNOA.miniMsg.alertShowAt(d, lang("youDidNotChooseEditData"))
                        } else {
                            var c = f[0].get("id");
                            CNOA_wf_manager_trustAddEdit = new CNOA_wf_manager_trustAddEditClass("edit", c)
                        }
                    }.createDelegate(this)
                },
                {
                    text: lang("del"),
                    id: b.ID_proxy_delete,
                    iconCls: "icon-utils-s-delete",
                    handler: function(c, d) {
                        var e = b.grid.getSelectionModel().getSelections();
                        if (e.length == 0) {
                            CNOA.miniMsg.alertShowAt(c, lang("noChoiceNeed"))
                        } else {
                            CNOA.miniMsg.cfShowAt(c, lang("confirmToDelete"),
                                function() {
                                    if (e) {
                                        var g = "";
                                        for (var f = 0; f < e.length; f++) {
                                            g += e[f].get("id") + ","
                                        }
                                        b.deleteList(g)
                                    }
                                })
                        }
                    }
                },
                "<span class='cnoa_color_gray'>" + lang("holdDownSelectMore") + "</span>"],
            bbar: b.pagingBar
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
    statusCheck: function(b, c, a) {
        if (b == 1) {
            return '<img src="/cnoa/resources/images/icons/accept.png" width="16" height="16" />'
        } else {
            return '<img src="/cnoa/resources/images/icons/dialog-close.png" width="16" height="16" />'
        }
    },
    setTrustStatus: function(b) {
        var a = this;
        Ext.Ajax.request({
            url: a.baseUrl + "&task=setTrustStatus",
            method: "POST",
            params: {
                id: b
            },
            success: function(d) {
                var c = Ext.decode(d.responseText);
                if (c.success === true) {
                    a.store.reload()
                } else {
                    CNOA.msg.alert(c.msg)
                }
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
                    CNOA.msg.notice(c.msg, lang("flowEntrust"));
                    b.store.reload()
                } else {
                    CNOA.msg.alert(c.msg)
                }
            }
        })
    },
    getTrustFlow: function(a) {
        mainPanel.closeTab("CNOA_MENU_WF_MANAGER_FLOW_TRUSTVIEWFLOW");
        mainPanel.loadClass("index.php?app=wf&func=flow&action=manager&modul=trust&task=loadPage&from=trustView_flow&id=" + a, "CNOA_MENU_WF_MANAGER_FLOW_TRUSTVIEWFLOW", lang("viewCommissionPricess"), "icon-flow-new")
    },
    makOprate: function(k, g, d, j, f, b) {
        var e = this;
        var m = d.data;
        var c = "";
        var a = Ext.id();
        if (k == "1") {
            c = "<a href='javascript:void(0)' class='gridview3' onclick='CNOA_wf_manager_trust.setTrustStatus(" + m.id + ")'>" + lang("disable") + "</a>"
        } else {
            c = "<a href='javascript:void(0)' class='gridview' onclick='CNOA_wf_manager_trust.setTrustStatus(" + m.id + ")'>" + lang("enable") + "</a>"
        }
        c += '<a href=\'javascript:void(0)\' class=\'gridview2 jianju\' onclick=\'var ck=$(this).siblings("label").children("input").attr("checked");CNOA_wf_manager_trust.takeBackFlow(' + m.id + ", ck);' id='wf_manager_takeFlow_" + m.id + "'>" + lang("callIn") + "</a>";
        c += "<label for=wf_manager_runflow_" + m.id + "><input selectId='" + m.id + "' type='checkbox' style='margin-left:6px;' id='wf_manager_runflow_" + m.id + "'><span class='cnoa_color_green'>" + lang("recoverRunFlow") + "</span></input></label>";
        return c
    },
    takeBackFlow: function(c, a) {
        var b = this;
        CNOA.msg.cf(lang("determineRecoverDelegate"),
            function(d) {
                if (d == "yes") {
                    Ext.Ajax.request({
                        url: b.baseUrl + "&task=takeBackAllTrustFlow",
                        method: "POST",
                        params: {
                            id: c,
                            ck: a
                        },
                        success: function(f) {
                            var e = Ext.decode(f.responseText);
                            if (e.success === true) {
                                CNOA.msg.notice(e.msg, lang("dealFlow"));
                                b.store.reload()
                            } else {
                                CNOA.msg.alert(e.msg)
                            }
                        }
                    })
                }
            })
    }
};
var CNOA_wf_manager_trustView_flowClass, CNOA_wf_manager_trustView_flow;
CNOA_wf_manager_trustView_flowClassClass = CNOA.Class.create();
CNOA_wf_manager_trustView_flowClassClass.prototype = {
    init: function() {
        var a = this;
        this.id = CNOA.wf.manager_trustView_flow.id;
        this.baseUrl = "index.php?app=wf&func=flow&action=manager&modul=trust&id=" + this.id;
        this.fields = [{
            name: "id"
        },
            {
                name: "flowId"
            },
            {
                name: "flowName"
            },
            {
                name: "fromName"
            },
            {
                name: "toName"
            },
            {
                name: "count"
            },
            {
                name: "stime"
            },
            {
                name: "etime"
            },
            {
                name: "fromuid"
            },
            {
                name: "touid"
            }];
        this.store = new Ext.data.Store({
            proxy: new Ext.data.HttpProxy({
                url: this.baseUrl + "&task=getTrustFlowList",
                disableCaching: true
            }),
            reader: new Ext.data.JsonReader({
                totalProperty: "total",
                root: "data",
                fields: this.fields
            })
        });
        this.sm = new Ext.grid.CheckboxSelectionModel({
            singleSelect: false
        });
        this.store.load();
        this.colModel = new Ext.grid.ColumnModel([new Ext.grid.RowNumberer(), {
            header: "id",
            dataIndex: "id",
            hidden: true
        },
            {
                header: lang("flowName"),
                dataIndex: "flowName",
                id: "flowName",
                width: 120,
                sortable: true,
                menuDisabled: true
            },
            {
                header: lang("principal1"),
                dataIndex: "fromName",
                width: 100,
                sortable: false,
                menuDisabled: true
            },
            {
                header: lang("mandatary"),
                dataIndex: "toName",
                width: 100,
                sortable: false,
                menuDisabled: true
            },
            {
                header: lang("startTime"),
                dataIndex: "stime",
                width: 120,
                sortable: false,
                menuDisabled: true
            },
            {
                header: lang("endTime"),
                dataIndex: "etime",
                width: 120,
                sortable: false,
                menuDisabled: true
            },
            {
                header: lang("quantity"),
                dataIndex: "count",
                width: 100,
                sortable: false,
                menuDisabled: true
            },
            {
                header: lang("opt"),
                dataIndex: "status",
                width: 160,
                sortable: false,
                menuDisabled: true,
                renderer: this.makOprate.createDelegate(this)
            },
            {
                header: "",
                dataIndex: "noIndex",
                width: 20,
                menuDisabled: true,
                resizable: false
            }]);
        this.grid = new Ext.grid.PageGridPanel({
            stripeRows: true,
            store: this.store,
            loadMask: {
                msg: lang("waiting")
            },
            cm: this.colModel,
            hideBorders: true,
            border: false,
            region: "center",
            layout: "fit",
            scriptRows: true,
            autoExpandColumn: "flowName",
            listeners: {
                cellclick: function(c, g, d, f) {
                    var b = c.getStore().getAt(g);
                    if (d == 7) {
                        a.getProxyFlow(b.data.flowId, b.data.fromuid, b.data.touid)
                    }
                }
            }
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
    getProxyFlow: function(c, a, b) {
        mainPanel.closeTab("CNOA_MENU_WF_MANAGER_FLOW_TRUSTVIEWUFLOW");
        mainPanel.loadClass("index.php?app=wf&func=flow&action=manager&modul=trust&task=loadPage&from=trustView_uflow&flowId=" + c + "&fromuid=" + a + "&touid=" + b, "CNOA_MENU_WF_MANAGER_FLOW_TRUSTVIEWUFLOW", lang("viewCommissionPricess"), "icon-flow-new")
    },
    makOprate: function(k, g, d, j, f, b) {
        var e = this;
        var m = d.data;
        var a = Ext.id();
        var c = '<a href=\'javascript:void(0)\' onclick=\'var ck=$(this).siblings("label").children("input").attr("checked");CNOA_wf_manager_trustView_flow.takeBackAllUflow(' + m.id + ", ck);' style=margin-left:6px; id='wf_manager_takeFlow_" + m.id + "'>" + lang("callIn") + "</a>";
        c += "<label for=wf_manager_runflow_" + m.id + "><input selectId='" + m.id + "' type='checkbox' style='margin-left:6px;' id='wf_manager_runflow_" + m.id + "'><span class='cnoa_color_green'>" + lang("recoverRunFlow") + "</span></input></label>";
        return c
    },
    takeBackAllUflow: function(c, a) {
        var b = this;
        CNOA.msg.cf(lang("determineRecoverDelegate"),
            function(d) {
                if (d == "yes") {
                    Ext.Ajax.request({
                        url: b.baseUrl + "&task=takeBackAllUflow",
                        method: "POST",
                        params: {
                            id: c,
                            ck: a
                        },
                        success: function(g) {
                            var f = Ext.decode(g.responseText);
                            if (f.success === true) {
                                CNOA.msg.notice(f.msg, lang("dealFlow"));
                                b.store.reload();
                                try {
                                    CNOA_wf_use_my_proxy.store.reload()
                                } catch(h) {}
                            } else {
                                CNOA.msg.alert(f.msg)
                            }
                        }
                    })
                }
            })
    }
};
var CNOA_wf_flow_timeout, CNOA_wf_flow_timeoutClass;
CNOA_wf_flow_timeoutClass = CNOA.Class.create();
CNOA_wf_flow_timeoutClass.prototype = {
    init: function(a) {
        this.baseUrl = "index.php?app=wf&func=flow&action=timeout",
            this.timeoutPanel = this.getTimeoutPanel();
        this.statisticsPanel = this.getStatisticsPanel();
        this.rankingPanel = this.getRankingPanel();
        this.mainPanel = new Ext.ux.VerticalTabPanel({
            region: "center",
            border: false,
            tabPosition: "left",
            tabWidth: 100,
            activeItem: 0,
            deferredRender: true,
            layoutOnTabChange: true,
            items: [this.timeoutPanel, this.statisticsPanel, this.rankingPanel],
            listeners: {
                afterrender: function(b) {
                    b.setActiveTab(2)
                }
            }
        })
    },
    getTimeoutPanel: function() {
        var f = this;
        var c = [{
                name: "flowNumber"
            },
                {
                    name: "flowName"
                },
                {
                    name: "stepName"
                },
                {
                    name: "status"
                },
                {
                    name: "user"
                },
                {
                    name: "timelimit"
                },
                {
                    name: "timeout"
                }],
            h = new Ext.data.Store({
                autoLoad: true,
                proxy: new Ext.data.HttpProxy({
                    url: this.baseUrl + "&task=getTimeoutStep",
                    disableCaching: true
                }),
                reader: new Ext.data.JsonReader({
                    totalProperty: "total",
                    root: "data",
                    fields: c
                })
            }),
            d = function(o, n, m) {
                return o + '<br/><span class="cnoa_color_gray">' + m.get("flowName") + "</span>"
            },
            g = new Ext.grid.ColumnModel({
                defaults: {
                    sortable: false,
                    menuDisabled: true
                },
                columns: [new Ext.grid.RowNumberer(), {
                    header: lang("flowNumber") + "/" + lang("customTitle"),
                    dataIndex: "flowNumber",
                    width: 300,
                    renderer: d
                },
                    {
                        header: lang("stepName"),
                        dataIndex: "stepName"
                    },
                    {
                        header: lang("stepStatus"),
                        dataIndex: "status"
                    },
                    {
                        header: lang("acceptOfficer"),
                        dataIndex: "user"
                    },
                    {
                        header: lang("processTime"),
                        dataIndex: "timelimit"
                    },
                    {
                        header: lang("timeOut1"),
                        dataIndex: "timeout"
                    }]
            });
        var j = new Ext.form.ComboBox({
                width: 80,
                mode: "local",
                triggerAction: "all",
                store: new Ext.data.ArrayStore({
                    fields: ["status", "text"],
                    data: [[1, lang("checkIn")], [2, lang("haveBeenProcess")]]
                }),
                valueField: "status",
                displayField: "text"
            }),
            e = new Ext.form.TextField({
                width: 50
            }),
            b = new Ext.form.TextField({
                width: 50
            }),
            k = {};
        var a = new Ext.grid.PageGridPanel({
            title: lang("timeOutStep"),
            store: h,
            cm: g,
            searchStore: k,
            pageSize: 3,
            tbar: new Ext.Toolbar({
                items: [{
                    text: lang("refresh"),
                    iconCls: "icon-system-refresh",
                    handler: function() {
                        h.reload()
                    }
                },
                    lang("processTime") + " >", e, {
                        xtype: "label",
                        text: lang("hour"),
                        style: "margin-right:10px;"
                    },
                    lang("timeOut1") + " >", b, {
                        xtype: "label",
                        text: lang("hour"),
                        style: "margin-right:10px;"
                    },
                    lang("stepStatus"), j, {
                        text: lang("search"),
                        style: "margin-left:5px",
                        handler: function() {
                            k.status = j.getValue();
                            k.timelimit = e.getValue();
                            k.timeout = b.getValue();
                            h.reload({
                                params: k
                            })
                        }
                    },
                    {
                        text: lang("clear"),
                        style: "margin-left:5px",
                        handler: function() {
                            j.setValue("");
                            e.setValue("");
                            b.setValue("");
                            k.status = j.getValue();
                            k.timelimit = e.getValue();
                            k.timeout = b.getValue();
                            h.load()
                        }
                    }]
            })
        });
        return a
    },
    getStatisticsPanel: function() {
        var h = this;
        var f = [{
                name: "user"
            },
                {
                    name: "stepTotal"
                },
                {
                    name: "todo"
                },
                {
                    name: "done"
                },
                {
                    name: "timeoutTotal"
                }],
            k = new Ext.data.Store({
                proxy: new Ext.data.HttpProxy({
                    url: this.baseUrl + "&task=getStatistics",
                    disableCaching: true
                }),
                reader: new Ext.data.JsonReader({
                    totalProperty: "total",
                    root: "data",
                    fields: f
                })
            }),
            j = new Ext.grid.ColumnModel({
                defaults: {
                    sortable: false,
                    menuDisabled: true
                },
                columns: [new Ext.grid.RowNumberer(), {
                    header: lang("acceptOfficer"),
                    dataIndex: "user"
                },
                    {
                        header: lang("handledTOtality"),
                        dataIndex: "stepTotal"
                    },
                    {
                        header: lang("toDealTimeOutNum"),
                        dataIndex: "todo",
                        width: 150
                    },
                    {
                        header: lang("timeOutNumAlready"),
                        dataIndex: "done",
                        width: 150
                    },
                    {
                        header: lang("totalOvertime"),
                        dataIndex: "timeoutTotal"
                    }]
            });
        var g = new Ext.data.Store({
                autoLoad: true,
                proxy: new Ext.data.HttpProxy({
                    url: this.baseUrl + "&task=getFlowSort",
                    disableCaching: true
                }),
                reader: new Ext.data.JsonReader({
                    totalProperty: "total",
                    root: "data",
                    fields: [{
                        name: "sortId"
                    },
                        {
                            name: "name"
                        }]
                })
            }),
            c = new Ext.data.Store({
                proxy: new Ext.data.HttpProxy({
                    url: this.baseUrl + "&task=getFlows",
                    disableCaching: true
                }),
                reader: new Ext.data.JsonReader({
                    totalProperty: "total",
                    root: "data",
                    fields: [{
                        name: "flowId"
                    },
                        {
                            name: "name"
                        }]
                })
            });
        var b = new Ext.form.ComboBox({
                width: 120,
                mode: "local",
                triggerAction: "all",
                store: c,
                valueField: "flowId",
                displayField: "name"
            }),
            e = new Ext.form.ComboBox({
                width: 120,
                mode: "local",
                triggerAction: "all",
                store: g,
                valueField: "sortId",
                displayField: "name",
                listeners: {
                    change: function(p, o, n) {
                        c.load({
                            params: {
                                sid: o
                            }
                        })
                    },
                    select: function() {
                        b.setValue("")
                    }
                }
            }),
            d = new Ext.TimeInterval({
                showMode: "month"
            }),
            m = {};
        var a = new Ext.grid.GridPanel({
            title: lang("timeoutStatistics"),
            store: k,
            cm: j,
            loadMask: {
                msg: lang("waiting")
            },
            tbar: new Ext.Toolbar({
                items: [{
                    text: lang("refresh"),
                    iconCls: "icon-system-refresh",
                    handler: function() {
                        k.reload()
                    }
                },
                    lang("processType"), e, lang("flow"), b, d, {
                        text: lang("search"),
                        style: "margin-left:5px",
                        handler: function() {
                            var n = b.getValue();
                            if (!n) {
                                CNOA.msg.alert(lang("selectNeedStatisticalFlow"));
                                return
                            }
                            m.flowId = n;
                            m.stime = d.getSTime();
                            m.etime = d.getETime();
                            k.load({
                                params: m
                            })
                        }
                    },
                    {
                        text: lang("clear"),
                        style: "margin-left:5px",
                        handler: function() {
                            b.setValue("");
                            d.clearValue();
                            m.flowId = "";
                            m.stime = "";
                            m.etime = "";
                            k.load()
                        }
                    }]
            })
        });
        return a
    },
    getRankingPanel: function() {
        var g = this;
        var a = [{
                name: "user"
            },
                {
                    name: "dept"
                },
                {
                    name: "job"
                },
                {
                    name: "count"
                },
                {
                    name: "time"
                },
                {
                    name: "flowType"
                }],
            b = new Ext.data.Store({
                autoLoad: true,
                proxy: new Ext.data.HttpProxy({
                    url: this.baseUrl + "&task=getRanking",
                    disableCaching: true
                }),
                reader: new Ext.data.JsonReader({
                    totalProperty: "total",
                    root: "data",
                    fields: a
                })
            }),
            c = new Ext.grid.ColumnModel({
                defauls: {
                    sortable: false,
                    menuDisabled: true
                },
                columns: [new Ext.grid.RowNumberer(), {
                    header: lang("truename"),
                    dataIndex: "user"
                },
                    {
                        header: lang("department"),
                        dataIndex: "dept",
                        width: 140
                    },
                    {
                        header: lang("job"),
                        dataIndex: "job"
                    },
                    {
                        header: lang("quantity"),
                        dataIndex: "count",
                        sortable: true
                    },
                    {
                        header: lang("takeAverLength"),
                        dataIndex: "time",
                        width: 140
                    }]
            });
        var f = new Ext.TimeInterval({
                showMode: "month"
            }),
            e = {};
        var d = new Ext.grid.GridPanel({
            title: lang("flowRanl1"),
            loadMask: {
                msg: lang("waiting")
            },
            store: b,
            cm: c,
            tbar: new Ext.Toolbar({
                items: [{
                    text: lang("refresh"),
                    iconCls: "icon-system-refresh",
                    handler: function() {
                        b.reload()
                    }
                },
                    f, {
                        text: lang("search"),
                        style: "margin-left:5px",
                        handler: function() {
                            e.stime = f.getSTime();
                            e.etime = f.getETime();
                            b.load({
                                params: e
                            })
                        }
                    },
                    {
                        text: lang("clear"),
                        style: "margin-left:5px",
                        handler: function() {
                            f.clearValue();
                            e.stime = "";
                            e.etime = "";
                            b.load()
                        }
                    }]
            })
        });
        return d
    }
};
var sm_wf_flow = 1;
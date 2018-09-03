try {
    document.getElementById("load-status").innerHTML = "载入 主框架 ..."
} catch (e) {
}
var mainPanel = null;
var timeShowWin;
var menuPanelLoaded = false;
var ID_CNOA_main_mainPanel_btn_updateOnline = Ext.id();
var ID_CNOA_main_mainPanel_btn_im = Ext.id();
var ID_CNOA_main_menu_tbar = "CNOA_main_menu_tbar";
var ID_CNOA_main_menu_CT = "CNOA_main_menu_CT";
var ID_CNOA_main_menu_main_CT = "CNOA_main_menu_main_CT";
var ID_CNOA_main_menu_text_CT = "CNOA_main_menu_text_CT";
try {
    CNOA.config.scblg == 1 ? null : null
} catch (e) {
    Ext.ns("CNOA.config.scblg");
    CNOA.config.scblg = {}
}
var CNOA_MAIN_MENU_LOADED = false;
var CNOA_MY_MENU_LOADED = false;
var CNOA_MY_MENU_DATA = new Array();

function setting() {
    var g = this;
    var i = "";
    var d = new Ext.data.Store({
        proxy: new Ext.data.HttpProxy({url: "index.php?app=main&func=common&action=commonJob&act=getWeatherCityList&task=area"}),
        reader: new Ext.data.JsonReader({root: "data", fields: [{name: "number"}, {name: "name"}]}),
        listeners: {
            load: function (l, k) {
                j.setValue(l.getAt(0).data.number)
            }
        }
    });
    var j = new Ext.form.ComboBox({
        triggerAction: "all",
        mode: "local",
        editable: false,
        width: 225,
        fieldLabel: lang("area"),
        allowBlank: false,
        name: "area",
        hiddenName: "area",
        store: d,
        valueField: "number",
        displayField: "name"
    });
    var h = new Ext.data.Store({
        proxy: new Ext.data.HttpProxy({url: "index.php?app=main&func=common&action=commonJob&act=getWeatherCityList&task=city"}),
        reader: new Ext.data.JsonReader({root: "data", fields: [{name: "number"}, {name: "name"}]}),
        listeners: {
            load: function (l, k) {
                a.setValue(l.getAt(0).data.number)
            }, datachanged: function (k) {
                d.load({params: {prov: i, city: k.getAt(0).data.number}})
            }
        }
    });
    var a = new Ext.form.ComboBox({
        triggerAction: "all",
        mode: "local",
        editable: false,
        width: 225,
        fieldLabel: lang("city"),
        allowBlank: false,
        name: "city",
        hiddenName: "city",
        store: h,
        valueField: "number",
        displayField: "name",
        listeners: {
            select: function (l, k) {
                j.setValue("");
                d.load({params: {prov: i, city: k.data.number}})
            }
        }
    });
    var c = new Ext.form.ComboBox({
        triggerAction: "all",
        mode: "local",
        width: 225,
        editable: false,
        fieldLabel: lang("province"),
        allowBlank: false,
        store: new Ext.data.SimpleStore({
            fields: ["number", "name"],
            data: [[10101, "北京"], [10102, "上海"], [10103, "天津"], [10104, "重庆"], [10105, "黑龙江"], [10106, "吉林"], [10107, "辽宁"], [10108, "内蒙古"], [10109, "河北"], [10110, "山西"], [10111, "陕西"], [10112, "山东"], [10113, "新疆"], [10114, "西藏"], [10115, "青海"], [10116, "甘肃"], [10117, "宁夏"], [10118, "河南"], [10119, "江苏"], [10120, "湖北"], [10121, "浙江"], [10122, "安徽"], [10123, "福建"], [10124, "江西"], [10125, "湖南"], [10126, "贵州"], [10127, "四川"], [10128, "广东"], [10129, "云南"], [10130, "广西"], [10131, "海南"], [10132, "香港"], [10133, "澳门"], [10134, "台湾"]]
        }),
        valueField: "number",
        displayField: "name",
        name: "prov",
        hiddenName: "prov",
        listeners: {
            select: function (m, k, l) {
                a.setValue("");
                j.setValue("");
                i = k.data.number;
                h.load({params: {prov: i}})
            }
        }
    });
    var b = new Ext.form.FormPanel({border: false, labelWidth: 60, bodyStyle: "padding: 10px", items: [c, a, j]});
    var f = new Ext.Window({
        title: lang("setMyWeather"),
        width: 330,
        height: 200,
        modal: true,
        layout: "fit",
        items: b,
        resizable: true,
        buttons: [{
            text: lang("ok"), iconCls: "icon-btn-save", handler: function () {
                g.submit(f, b)
            }.createDelegate(this)
        }, {
            text: lang("cancel"), iconCls: "icon-dialog-cancel", handler: function () {
                f.close()
            }.createDelegate(this)
        }]
    }).show()
}

function submit(c, b) {
    var d = this;
    if (b.getForm().isValid()) {
        var a = b.getForm().getValues();
        Ext.Ajax.request({
            url: "index.php?app=main&func=common&action=commonJob&act=setWeather",
            method: "POST",
            params: {city: a.city, prov: a.prov, area: a.area},
            success: function (g) {
                c.close();
                var f = Ext.decode(g.responseText);
                $(".lhc_city").text(f.data.city);
                $(".lhc_temp").text(f.data.temp1);
                $(".lhc_weat").text(f.data.weat1 + " " + f.data.wind1);
                $(".lhc_weather_img img").attr("src", f.data.pict1)
            }
        })
    }
}

function resizeMenu() {
    $("a.cnoa_ex_menu_area").remove();
    $(document).unbind("mousemove.menuex");
    var c = Math.floor(($("#CNOA_main_menu_main_CT").parent().height() - 360 - 8) / 37);
    var b = $(Ext.getCmp(ID_CNOA_main_menu_CT).body.dom);
    CNOA_MAIN_CONTEXTMENU = null;
    var g = [];
    var d = [];
    var a = null;
    $("#CNOA_main_menu_CT").unbind("mouseenter mouseleave");
    $("#CnoaMiniMenuCt").unbind("mouseenter mouseleave");
    if (CNOA_USER_MENUCED == 2) {
        $.each(menuPanel.data, function (k, j) {
            j.iid = i;
            g.push(j);
            i++
        })
    } else {
        if (c < 2) {
            c = 2
        }
        var i = 0;
        if (c > menuPanel.data.length) {
            $.each(menuPanel.data, function (k, j) {
                j.iid = i;
                g.push(j);
                i++
            })
        } else {
            $.each(menuPanel.data, function (k, j) {
                j.iid = i;
                if ((k + 1) < c) {
                    g.push(j)
                } else {
                    d.push(j)
                }
                i++
            })
        }
    }
    var f = new Array();
    f.push('<div id="CNOA_MAIN_MENU_BOTTOM_CT">');
    Ext.each(g, function (j, k, m) {
        if (k == 0 && !CNOA_MAIN_MENU_LOADED) {
            menuPanel.makeMenu(j);
            CNOA_MAIN_MENU_LOADED = true
        }
        var l = "<span  class='" + j.iconCls + "'></span>";
        f.push(["<a href='javascript:void(0);' id='" + Ext.id() + "' " + (CNOA_USER_MENUCED == 2 ? "" : "onclick='menuPanel.makeMenu2(" + k + ", this);' ") + (CNOA_USER_MENUCED == 2 ? "onmouseover='menuPanel.makeMenu3(" + k + ", this);' " : "") + "class='item" + (k == 0 ? " " : "") + "'>", l, "<li clsss='text' ext:qtip='" + j.text + "'>" + j.text + "</li>", "</a>"].join(""))
    });
    if (d.length > 0) {
        a = Ext.id();
        f.push("<a href='javascript:void(0);' id='" + a + "' class='item more cnoa_ex_menu_area' style='height:43px !important;text-indent: 40px !important;'>" + lang("moreMenu") + "</a>")
    }
    f.push("</div>");
    Ext.getCmp(ID_CNOA_main_menu_CT).body.update('<div class="splitbarH" unselectable="on" style="-webkit-user-select: none;"></div>' + f.join(""));
    Ext.getCmp(ID_CNOA_main_menu_CT).doLayout();
    setTimeout(function () {
        Ext.getCmp(ID_CNOA_main_menu_main_CT).setHeight($("#CNOA_main_menu_main_CT").parent().height() - $("#CNOA_MAIN_MENU_BOTTOM_CT").height() - 3);
        if (CNOA_USER_MENUCED == 2) {
            Ext.getCmp(ID_CNOA_main_menu_CT).setHeight($("#CNOA_ROOT_TREE>div>.x-panel-body").height());
            makeScrollable("#CNOA_main_menu_CT .x-panel-body", "#CNOA_MAIN_MENU_BOTTOM_CT");
            $("#CNOA_main_menu_CT").bind("mouseenter", function () {
                window.inMiniMenuCt1 = true;
                showMiniMenuCt()
            }).bind("mouseleave", function () {
                window.inMiniMenuCt1 = false;
                hideMiniMenuCt()
            });
            $("#CnoaMiniMenuCt").bind("mouseenter", function () {
                window.inMiniMenuCt2 = true;
                showMiniMenuCt()
            }).bind("mouseleave", function () {
                window.inMiniMenuCt2 = false;
                hideMiniMenuCt()
            })
        } else {
            Ext.getCmp(ID_CNOA_main_menu_CT).setHeight($("#CNOA_MAIN_MENU_BOTTOM_CT").height());
            var j = [];
            if (d.length > 0) {
                $.each(d, function (m, l) {
                    j.push({
                        href: "javascript:void(0);",
                        text: l.text,
                        newWindow: false,
                        click: "menuPanel.makeMenu2(" + l.iid + ", {id:null,className:''})",
                        cls: "menu-" + l.iconCls
                    })
                });
                $("#" + Ext.getCmp(ID_CNOA_main_menu_CT).body.id).unbind("mouseenter").unbind("mouseleave");
                var k = null;
                if ((d.length * 43) > ($(window).height() - 44)) {
                    k = $(window).height() - 44
                }
                $(".cnoa_ex_menu_area").powerFloat({
                    width: 156,
                    target: j,
                    targetMode: "list",
                    position: "2-1",
                    cls: "CNOA_MAIN_EX_MENU",
                    hideDelay: 300,
                    height: k,
                    showCall: function () {
                        if (k) {
                            makeScrollable("#floatBox_list", ".float_list_ul");
                            var l = $(".cnoa_ex_menu_area").offset().top + $(".cnoa_ex_menu_area").height() - $("#floatBox_list").height() - 4;
                            $("#floatBox_list").css("top", l + "px");
                            $("#floatBox_list").scrollTop(9999)
                        }
                        $("#floatBox_list").removeClass("MY_MENU")
                    },
                    hideCall: function () {
                        $(".CNOA_MAIN_EX_MENU").css("height", "auto")
                    }
                })
            }
        }
        $("#CNOA_MAIN_MENU_BOTTOM_CT").parent().css("position", "absolute").css("bottom", "0px");
        $("#CNOA_main_menu_CT").css("visibility", "visible")
    });
    $("#CNOA_MAIN_MENU_BOTTOM_CT a.item").each(function (l, k) {
        var j = $(this).children("span").attr("class");
        $(this).children("span").addClass("menu-" + j)
    });
    if (CNOA_USER_MENUCED == 2) {
        $("#CNOA_MAIN_MENU_BOTTOM_CT a.item").each(function (l, k) {
            var j = $(this).children("span").attr("class");
            if (j) {
                cls2 = j.split(" ")[0];
                $(this).children("span").addClass("menu-" + cls2 + "-wz")
            }
        })
    }
    $("#CNOA_MAIN_PANEL_HEADER li").live("mouseover", function () {
        $(this).find(".x-tab-text-mark").addClass("x-tab-text-mark-over")
    }).live("mouseout", function () {
        $(this).find(".x-tab-text-mark").removeClass("x-tab-text-mark-over")
    });
    try {
        Ext.getCmp("CNOA_MAIN_NOTICE_RIGHT_PANEL").setHeight($(window).height());
        Ext.getCmp("CNOA_MAIN_NOTICE_RIGHT_PANEL").setWidth($("#CnoaMiniMenuCtRight").width())
    } catch (h) {
    }
}

$(window).resize(function () {
    setTimeout(resizeMenu, 500);
    var b = $(window).height(), a = b * 555 / 949;
    if (a >= 555) {
        $("#lhc_main").css("overflow-y", "hidden")
    } else {
        $("#lhc_main").css("overflow-y", "auto")
    }
    $("#lhc_main").css("height", a + "px")
});

function showTryWin(b) {
    var a = new Ext.Window({
        width: 365,
        height: 200,
        title: lang("about"),
        autoSroll: true,
        bodyStyle: "padding:10px;font-size: 14px;",
        html: $(b).html(),
        modal: true,
        buttons: [{
            text: lang("close"), handler: function () {
                a.close()
            }
        }]
    }).show()
}

function loadMyMenu(a) {
    Ext.Ajax.request({
        url: "index.php?action=commonJob&act=myMenu",
        method: "POST",
        params: {task: "getMyMenu"},
        success: function (c) {
            CNOA_MY_MENU_LOADED = true;
            var b = Ext.decode(c.responseText);
            CNOA_MY_MENU_DATA = b;
            if (a) {
                a.call(this)
            }
        }
    })
}

function clickMyMenu(index) {
    $.powerFloat.hide();
    if (index == -1) {
        mainPanel.closeTab("CNOA_MENU_MAIN_SETMYMENU");
        mainPanel.loadClass("index.php?action=commonJob&act=myMenu&task=loadPage", "CNOA_MENU_MAIN_SETMYMENU", lang("myMenu"), "icon-application-icon");
        return
    }
    $.each(CNOA_MY_MENU_DATA, function (i, v) {
        if (index == i) {
            if ((v.clickEvent != undefined) && (v.clickEvent != "")) {
                eval(v.clickEvent + "('" + v.autoLoadUrl + "')")
            } else {
                mainPanel.closeTab(v.id);
                mainPanel.loadClass(v.autoLoadUrl, v.id, v.name, v.iconCls)
            }
        }
    })
}

function cnoaCollapseMenu() {
    if (CNOA_USER_MENUCED == 2) {
        CNOA_USER_MENUCED = 1;
        $("#CNOA_ROOT_TREE").css("width", "180px");
        OAViewport.doLayout();
        $("#CNOA_ROOT_TREE").removeClass("menu-collapsed")
    } else {
        CNOA_USER_MENUCED = 2;
        $("#CNOA_ROOT_TREE").css("width", "50px");
        OAViewport.doLayout();
        $("#CNOA_ROOT_TREE").addClass("menu-collapsed")
    }
    menuPanel.setMenuCp(CNOA_USER_MENUCED);
    resizeMenu()
}

function makeScrollable(d, c) {
    var a = c;
    var d = $(d), c = $(c);
    setTimeout(function () {
        b()
    }, 600);

    function b() {
        var g = 99;
        var f = d.width();
        var i = d.height();
        var h = c.outerHeight() + 2 * g;
        d.mousemove(function (l) {
            if (a == "#CNOA_MAIN_MENU_BOTTOM_CT" && CNOA_USER_MENUCED != 2) {
                return
            }
            i = d.height();
            var k = d.offset();
            var j = (l.pageY - k.top) * (h - i) / i - g;
            if (j < 0) {
                j = 0
            }
            d.scrollTop(j)
        })
    }
}

MenuPanel = function () {
    MenuPanel.superclass.constructor.call(this, {
        id: "CNOA_ROOT_TREE",
        region: "west",
        width: CNOA_USER_MENUCED == 2 ? 50 : 180,
        cls: CNOA_USER_MENUCED == 2 ? "menu-collapsed CNOA_ROOT_TREE" : "CNOA_ROOT_TREE",
        collapsible: true,
        lines: true,
        bodyStyle: "border-bottom-width:0",
        header: false,
        data: [],
        listeners: {
            resize: function (b, f, c, a, d) {
                $(".CNOA_MAIN_EX_MENU2").height(c - 38)
            }
        },
        tbar: new Ext.Toolbar({
            items: [new Ext.BoxComponent({
                id: ID_CNOA_main_menu_text_CT,
                autoEl: {tag: "div", style: "padding:1px 0 2px 0", html: ""}
            }), "->", new Ext.BoxComponent({
                autoEl: {
                    tag: "a",
                    cls: "CNOA_main_menu_cuttle_button",
                    href: "javascript:void(0);",
                    onclick: "cnoaCollapseMenu();"
                }
            })]
        }),
        defaults: {border: false},
        items: [{
            xtype: "panel",
            header: false,
            autoScroll: false,
            id: ID_CNOA_main_menu_main_CT,
            layout: "fit",
            listeners: {
                afterrender: function (b) {
                    var a = function () {
                        var f = b.getBox();
                        return [f.x, f.y, f.x + f.width, f.y + f.height]
                    };
                    var d = false;
                    var c = 0;
                    b.body.on("mouseover", function (l) {
                        var i = a(), g = i[0], o = i[1], f = i[2], n = i[3];
                        var p = l.xy[0], m = l.xy[1];
                        if (p > g && p < f && m > o && m < n) {
                            if (!d) {
                                d = true;
                                var k = $(b.body.dom);
                                c = k.height();
                                var j = 0;
                                if ($("a.cnoa_ex_menu_area").length > 0) {
                                    j = $("#CNOA_main_menu_main_CT").parent().height() - 43
                                } else {
                                    j = $("#CNOA_main_menu_main_CT").parent().height()
                                }
                                k.css("zIndex", "99999").height(j);
                                Ext.getCmp("CNOA_main_menu_main_CT").items.items[0].setHeight(j);
                                $("#" + ID_CNOA_main_menu_main_CT).css("zIndex", 2);
                                $("#" + ID_CNOA_main_menu_CT).css("zIndex", 1);
                                l.stopPropagation()
                            }
                        }
                    });
                    b.body.on("mouseout", function (j) {
                        var h = a(), g = h[0], m = h[1], f = h[2], l = h[3];
                        var n = j.xy[0], k = j.xy[1];
                        if (n > g && n < f && k > m && k < l) {
                        } else {
                            if (d) {
                                d = false;
                                var i = $(b.body.dom);
                                i.css("zIndex", "").height(c);
                                $("#" + ID_CNOA_main_menu_main_CT).css("zIndex", "");
                                $("#" + ID_CNOA_main_menu_CT).css("zIndex", "");
                                Ext.getCmp("CNOA_main_menu_main_CT").items.items[0].setHeight(c);
                                j.stopPropagation()
                            }
                        }
                    })
                }
            }
        }, {
            xtype: "panel",
            style: "border-top-width:1px;",
            header: false,
            bodyStyle: "width: 100%;",
            id: ID_CNOA_main_menu_CT,
            split: true,
            html: '<div class="splitbarH" unselectable="on" style="-webkit-user-select: none;"></div>',
            listeners: {
                resize: function (a) {
                    $("#CnoaMiniMenuCt").height($("#CNOA_ROOT_TREE").height() - 38)
                }
            }
        }]
    })
};

function showMiniMenuCt() {
    $("#CnoaMiniMenuCt").show()
}

function hideMiniMenuCt() {
    $("#CnoaMiniMenuCt").hide()
}

Ext.extend(MenuPanel, Ext.Panel, {
    makeMenu3: function (index, nowItem) {
        var me = this;
        if ($(nowItem).text() == "") {
            return
        }
        $(nowItem).addClass("hover").siblings().removeClass("hover");
        $("#CnoaMiniMenuCt .title").html($(nowItem).text());
        $("#CnoaMiniMenuCt2").empty();
        var makeTree = function (id) {
            var treeRoot = new Ext.tree.TreeNode({expanded: true});
            return new Ext.tree.TreePanel({
                root: treeRoot,
                autoScroll: true,
                border: false,
                rootVisible: false,
                useArrows: false,
                layout: "fit",
                applyTo: "CnoaMiniMenuCt2",
                listeners: {
                    click: function (node, e) {
                        if (node.isLeaf()) {
                            e.stopEvent();
                            var attr = node.attributes;
                            if (attr.clickEvent && attr.isNewLabel) {
                                eval(attr.clickEvent + "('" + attr.href + "')")
                            } else {
                                mainPanel.loadClass(attr.autoLoadUrl, node.id, node.text, attr.iconCls, attr.forceRefresh)
                            }
                            hideMiniMenuCt()
                        }
                    }, afterrender: function (th) {
                    }
                }
            })
        };
        var tree = makeTree(me.data[index].id);
        tree.getRootNode().appendChild(me.data[index].children);
        tree.getSelectionModel().on("beforeselect", function (sm, node) {
            return node.isLeaf()
        })
    }, makeMenu2: function (a, b) {
        try {
            this.makeMenu(this.data[a])
        } catch (c) {
            cdump(c)
        }
    }, makeMenu: function (v) {
        Ext.getCmp(ID_CNOA_main_menu_text_CT).getEl().update("<p>" + v.text + "</p>");
        var makeTree = function (id) {
            var treeRoot = new Ext.tree.TreeNode({expanded: true});
            return new Ext.tree.TreePanel({
                id: id,
                root: treeRoot,
                autoScroll: true,
                border: false,
                rootVisible: false,
                useArrows: false,
                layout: "fit",
                listeners: {
                    click: function (node, e) {
                        if (node.isLeaf()) {
                            e.stopEvent();
                            var attr = node.attributes;
                            if (attr.clickEvent && attr.isNewLabel) {
                                eval(attr.clickEvent + "('" + attr.href + "')")
                            } else {
                                mainPanel.loadClass(attr.autoLoadUrl, node.id, node.text, attr.iconCls, attr.forceRefresh)
                            }
                        }
                    }
                }
            })
        };
        var ct = Ext.getCmp(ID_CNOA_main_menu_main_CT);
        var tree = makeTree(v.id);
        tree.getRootNode().appendChild(v.children);
        tree.getSelectionModel().on("beforeselect", function (sm, node) {
            return node.isLeaf()
        });
        ct.removeAll();
        ct.add(tree);
        ct.doLayout()
    }, getMenuList: function () {
        var a = this;
        Ext.Ajax.request({
            url: "welcome/getMainPanelTreeData2",
            method: "GET",
            success: function (c) {
                var b = Ext.decode(c.responseText);
                menuPanelLoaded = true;
                a.data = b;
                resizeMenu();
                a.doLayout()
            }
        })
    }, onRender: function (b, a) {
        this.getMenuList();
        MenuPanel.superclass.onRender.call(this, b, a)
    }, selectClass: function (a) {
        Ext.each(this.items.items, function (c, d) {
            if (a) {
                try {
                    var b = c.items.items[0];
                    b.getSelectionModel().clearSelections();
                    var f = b.getNodeById(a);
                    if (f) {
                        c.expand(true);
                        var h = f.getPath();
                        b.selectPath(h)
                    }
                } catch (g) {
                }
            }
        })
    }, setMenuCp: function (a) {
        Ext.Ajax.request({
            url: "index.php?action=commonJob&act=setMenuCp",
            method: "POST",
            params: {collapsed: a},
            success: function (b) {
            }
        })
    }
});
DocPanel = Ext.extend(Ext.Panel, {
    closable: true,
    autoScroll: true,
    autoShow: true,
    border: false,
    bodyStyle: "padding: 5px 0 0 5px;",
    scrollToMember: function (c) {
        var a = Ext.fly(this.cclass + "-" + c);
        if (a) {
            var b = (a.getOffsetsTo(this.body)[1]) + this.body.dom.scrollTop;
            this.body.scrollTo("top", b - 25, {duration: 0.75, callback: this.hlMember.createDelegate(this, [c])})
        }
    },
    scrollToSection: function (c) {
        var a = Ext.getDom(c);
        if (a) {
            var b = (Ext.fly(a).getOffsetsTo(this.body)[1]) + this.body.dom.scrollTop;
            this.body.scrollTo("top", b - 25, {
                duration: 0.5, callback: function () {
                    Ext.fly(a).next("h2").pause(0.2).highlight("#8DB2E3", {attr: "color"})
                }
            })
        }
    },
    hlMember: function (b) {
        var a = Ext.fly(this.cclass + "-" + b);
        if (a) {
            a.up("tr").highlight("#cadaf9")
        }
    }
});
MainPanel = function () {
    MainPanel.superclass.constructor.call(this, {
        id: "CNOA_MAIN_PANEL",
        region: "center",
        resizeTabs: true,
        minTabWidth: 90,
        tabWidth: 120,
        bodyStyle: "border-bottom-width:0; border-right-width:0",
        listeners: {
            afterrender: function (a) {
                Ext.Ajax.request({
                    url: "/welcome/myPortals",
                    method: "GET",
                    success: function (c) {
                        try {
                            var b = Ext.decode(c.responseText);
                            if (b.noPermit == true) {
                                $("#CNOA_MAIN_PANEL__docs-CNOA_MENU_PORTALS_MY").css("display", "none")
                            }
                        } catch (d) {
                        }
                    }
                });
                $("#CNOA_MAIN_PANEL").prepend("<div class='cnoa_main_menu_shadow'></div>");
                $("#CNOA_MAIN_PANEL .x-tab-edge .x-tab-strip-text").html(lang("commonlyUsed")).powerFloat({
                    target: [],
                    position: "4-1",
                    width: 180,
                    targetMode: "list",
                    cls: "CNOA_MAIN_EX_MENU MY_MENU",
                    hideDelay: 300,
                    showDelay: 300,
                    emptyText: lang("loading"),
                    showCall: function () {
                        var b = function () {
                            $("#floatBox_list").addClass("MY_MENU");
                            $(".float_list_ul").empty();
                            $.each(CNOA_MY_MENU_DATA, function (f, c) {
                                var d = [], g = "";
                                if (f == 0) {
                                    d.push("float_list_li_first")
                                }
                                if (CNOA_MY_MENU_DATA.length - f == 1) {
                                    d.push("float_list_li_last")
                                }
                                if (d.length > 0) {
                                    g = " class='" + d.join(" ") + "'"
                                }
                                $("<li" + g + '><a href="javascript:void(0);" onclick="clickMyMenu(' + f + ')" class="float_list_a ' + c.iconCls + '">' + (c.newName == "" ? c.name : c.newName) + "</a></li>").appendTo($(".float_list_ul"))
                            });
                            $('<li class="float_list_li_last powerFloat-mymenu"><a href="javascript:void(0);" onclick="clickMyMenu(-1)" class="float_list_a icon-form-combobox">' + lang("set2") + lang("myMenu") + "</a></li>").appendTo($(".float_list_ul"));
                            if ((CNOA_MY_MENU_DATA.length * 43) > ($(window).height() - 90)) {
                                $("#floatBox_list").height($(window).height() - 90);
                                makeScrollable("#floatBox_list", ".float_list_ul")
                            }
                        };
                        if (!CNOA_MY_MENU_LOADED) {
                            loadMyMenu(b)
                        } else {
                            b()
                        }
                    },
                    hideCall: function () {
                        $("#floatBox_list").css("height", "auto")
                    }
                })
            }, resize: function (b, f, c, a, d) {
                $(".cnoa_main_menu_shadow").height(c - 38)
            }
        },
        headerCfg: {cls: "x-tab-panel-header cnoa_main_tab_header cnoa_border", id: "CNOA_MAIN_PANEL_HEADER"},
        enableTabScroll: true,
        activeTab: 0,
        itemTpl: new Ext.XTemplate('<li class="{cls}" id="{id}"><a class="x-tab-strip-close"></a>', '<span class="cnoa_tab_item_left"></span><span class="cnoa_tab_item_right"></span>', '<a class="x-tab-right" href="#"><div class="x-tab-text-mark"></div><em class="x-tab-left">', '<span class="x-tab-strip-inner"><span class="x-tab-strip-text {iconCls}">{text}</span></span>', "</em></a></li>"),
        items: [{
            id: "docs-CNOA_WELCOM_PANEL",
            title: lang("myIndexPage"),
            iconCls: "icon-cnoa",
            layout: "fit",
            tabCls: "CNOA_TAB_HOMEPAGE",
            autoScroll: false,
            bodyStyle: "padding: 5px 0 0 5px;",
            autoLoad: {
                url: "/welcome/welcomePanel",
                scripts: true,
                scope: this,
                nocache: true
            }
        }, {
            id: "docs-CNOA_MENU_PORTALS_MY",
            title: lang("myportals"),
            iconCls: "x-tab-with-icon",
            deferredRender: false,
            layout: "fit",
            autoScroll: false,
            bodyStyle: "padding: 5px 0 0 5px;",
            autoLoad: {
                url: "index.php?app=portals&func=portals&action=my&ajax=1&parentid=docs-CNOA_MENU_PORTALS_MY",
                scripts: true,
                scope: this,
                nocache: true
            }
        }],
        initEvents: function () {
            Ext.TabPanel.superclass.initEvents.call(this);
            this.on("remove", this.onRemove, this, {target: this});
            this.mon(this.strip, "mousedown", this.onStripMouseDown, this);
            this.mon(this.strip, "contextmenu", this.onStripContextMenu, this);
            if (this.enableTabScroll) {
                this.mon(this.strip, "mousewheel", this.onWheel, this)
            }
            this.mon(this.strip, "dblclick", this.onTitleDbClick, this)
        },
        onTitleDbClick: function (c, b, d) {
            try {
                var a = this.findTargets(c);
                if (a.item.id != "docs-CNOA_WELCOM_PANEL" && a.item.id != "docs-CNOA_MENU_PORTALS_MY") {
                    if (a.item.fireEvent("beforeclose", a.item) !== false) {
                        a.item.fireEvent("close", a.item);
                        this.remove(a.item)
                    }
                }
            } catch (c) {
            }
        }
    })
};
Ext.extend(MainPanel, Ext.TabPanel, {
    initEvents: function () {
        MainPanel.superclass.initEvents.call(this);
        this.body.on("click", this.onClick, this)
    }, onClick: function (d, c) {
        if (c = d.getTarget("a:not(.exi)", 3)) {
            var a = Ext.fly(c).getAttributeNS("ext", "cls");
            d.stopEvent();
            if (a) {
                var f = Ext.fly(c).getAttributeNS("ext", "member");
                this.loadClass(c.href, a, c, f, c.iconCls)
            } else {
                if (c.className == "inner-link") {
                    this.getActiveTab().scrollToSection(c.href.split("#")[1])
                } else {
                }
            }
        } else {
            if (c = d.getTarget(".micon", 2)) {
                d.stopEvent();
                var b = Ext.fly(c.parentNode);
                if (b.hasClass("expandable")) {
                    b.toggleClass("expanded")
                }
            }
        }
    }, loadClass: function (a, j, i, h, k) {
        if (this.isWfInNewWindow(a)) {
            return
        }
        var g = this;
        var c = "docs-" + j;
        if (j == "CNOA_MENU_COMMUNICATION_MESSAGE_NEW") {
            if ($("#docs-CNOA_MENU_COMMUNICATION_MESSAGE_NEW").length > 0) {
                $("#docs-CNOA_MENU_COMMUNICATION_MESSAGE_NEW").remove()
            }
        }
        if (j == "CNOA_MENU_COMMUNICATION_EMAIL_NEW") {
            if ($("#docs-CNOA_MENU_COMMUNICATION_EMAIL_NEW").length > 0) {
                $("#docs-CNOA_MENU_COMMUNICATION_EMAIL_NEW").remove()
            }
        }
        this.maxTabNumber = CNOA_TAG_NUM;
        if (k) {
            this.remove(c)
        }
        var d = this.getItem(c);
        if (d == null || d == undefined) {
            if (this.items.getCount() > this.maxTabNumber) {
                this.remove(this.items.items[1].id)
            }
            h = h == undefined ? "icon-order-s-new" : h;
            var f = new DocPanel({
                id: c,
                title: i.replace(/\(.*\)/g, ""),
                cclass: j,
                layout: "fit",
                autoScroll: false,
                iconCls: h,
                tabTip: i.replace(/\(.*\)/g, ""),
                autoLoad: {url: a + "?ajax=1&parentid=" + c, scripts: true, scope: this, nocache: true},
                listeners: {
                    afterrender: function (l) {
                        l.body.getUpdater().on("update", function (m, o) {
                            if (o.responseText.indexOf('"noPermit":true') != -1) {
                                var n = Ext.decode(o.responseText);
                                if (n.noPermit) {
                                    l.body.update("");
                                    CNOA.msg.alert(n.msg, function () {
                                        g.remove(c)
                                    })
                                }
                            }
                        })
                    }
                }
            });
            var b = this.add(f);
            this.setActiveTab(b)
        } else {
            this.setActiveTab(d)
        }
    }, closeTab: function (a) {
        var b = "docs-" + a;
        this.remove(b)
    }, isWfInNewWindow: function (b) {
        var a = parseURL(b);
        if (CNOA_USER_WFINNEWWINDOW && a.params.app == "wf" && a.params.func == "flow" && a.params.action == "use" && a.params.task == "loadPage" && a.params.from == "dealflow") {
            b = b.replace("from=dealflow", "from=dealflow&openInNewWin=1");
            window.open(b);
            return true
        }
        return false
    }, setMenu: function () {
        loadMyMenu()
    }
});

function main_init_desktop() {
    Ext.QuickTips.init();
    mainPanel = new MainPanel();
    menuPanel = new MenuPanel();
    mainPanel.on("tabchange", function (C, B) {
        menuPanel.selectClass(B.cclass);
        $("#CNOA_MAIN_PANEL .x-tab-strip-close").css("right", "-2px");
        $(B.tabEl).prev(".x-tab-with-icon").find(".x-tab-strip-close").css("right", "4px");
        var A = 99;
        $(B.tabEl).css("zIndex", "99").siblings(".x-tab-with-icon").each(function (E, D) {
            A--;
            $(D).css("zIndex", A)
        });
        var y = $(B.tabEl).find(".x-tab-strip-text");
        var z = y.css("backgroundImage").replace(/(\.gray)+/ig, "");
        y.css("cssText", "background-image:" + z + " !important");
        if (B.tabCls == "CNOA_TAB_HOMEPAGE") {
            $(".CNOA_TAB_HOMEPAGE .x-tab-right").css("cssText", "background-position: 1px -288px !important;")
        } else {
            $(".CNOA_TAB_HOMEPAGE .x-tab-right").css("cssText", "background-position: 1px -320px !important;")
        }
    });

    function p(y) {
        Ext.Ajax.request({
            url: "index.php?app=main&func=user&action=changeTheme",
            method: "POST",
            params: {theme: y},
            success: function (A) {
                var z = Ext.decode(A.responseText);
                if (z.success === true) {
                    CNOA.RefreshWindow()
                } else {
                    CNOA.msg.alert(z.msg)
                }
            }
        })
    }

    var j = new Array();
    Ext.each(CNOA.config.themeList, function (y, z, A) {
        j.push({
            text: y.name,
            checked: CNOA_USER_THEME == y.folder ? true : false,
            group: "theme",
            handler: function () {
                Ext.util.CSS.swapStyleSheet("theme", "theme/" + y.folder + "/style.css");
                p(y.folder)
            }
        })
    });
    var t = "";
    if (CNOA.systemTitleShowType == 0) {
        t = "<a href='javascript:void(0);' style='background-image: url(/cnoa/" + CNOA.config.file + "/webcache/logo/" + CNOA_USER_THEME + ".png);' id='CNOA_LOGO' onclick='CNOA.RefreshWindow();' ext:qtip='" + lang("clickToRefresh") + "'></a>"
    } else {
        t = "<a href='javascript:void(0);' onclick='CNOA.RefreshWindow();' ext:qtip='" + lang("clickToRefresh") + "'><span id='CNOA_SYSTEMTITLE'>" + CNOA.config.name + "</span></a>"
    }
    if (Ext.isIE6 || Ext.isIE7) {
        var d = ["当前使用的浏览器版本较低，可能会导致部分界面或功能不正常，建议升级浏览器或<br>", "使用速度更快/性能更优的<span style='color:red'>" + lang("chrome") + "</span>" + lang("browser") + "，本系统将不再做IE8以下版本浏览器的兼容。<br>", "点击下载安装：&nbsp;<a href='http://yun.baidu.com/share/link?shareid=736509129&uk=2902785718' target='_blank'><img src='./resources/images/cnoa/chrome.16x16.gif'>谷歌浏览器</a>&nbsp;&nbsp;", "<a href='http://www.baidu.com/s?ie=utf-8&f=8&rsv_bp=1&tn=baidu&wd=IE%E4%B8%8B%E8%BD%BD' target='_blank'><img src='./resources/images/cnoa/ie.16x16.gif'>IE8/IE10</a>", "<br><span style='color:gray'>当前浏览器版本为：" + (Ext.isIE6 ? "IE6" : "IE7") + "<br>浏览器内置信息为：</span><input style='width:333px;color:gray' value='" + navigator.userAgent + "' readonly=true>"].join("");
        var x = new Ext.Window({
            width: 464,
            height: 180,
            cls: "btn-red1",
            title: "温馨提醒",
            border: false,
            draggable: false,
            modal: true,
            html: d,
            buttonAlign: "center",
            buttons: [{
                text: "我已知晓", handler: function () {
                    x.close()
                }
            }]
        }).show()
    }
    var n = [];
    n.push({
        text: lang("oaClient"),
        iconCls: "icon-client-white",
        height: 25,
        hidden: CNOA.config.scblg.smeb == 1 ? false : true,
        tooltip: lang("download") + " " + lang("oaClient"),
        handler: function () {
            window.open(CNOA.config.scblg.smeu)
        }
    });
    var o = [{name: "id"}, {name: "title"}, {name: "noticetime"}, {name: "href"}, {name: "from"}, {name: "content"}, {name: "opentype"}];
    var g = new Ext.data.Store({
        proxy: new Ext.data.HttpProxy({url: "index.php?app=notice&func=notice&action=list&task=getMiniNoticeList"}),
        reader: new Ext.data.JsonReader({totalProperty: "total", root: "data", fields: o}),
        listeners: {
            load: function (y) {
                Ext.getCmp("CNOA_DESKTOP_NOTICE_BTN").setText(lang("remind") + "<span class='b1'><span>" + y.reader.jsonData.total + "</span><i></i></span>")
            }
        }
    });
    var i = new Ext.grid.CheckboxSelectionModel({singleSelect: false});
    this.viewNotice = function (D, C, y, z) {
        var B = this;
        $("#CnoaMiniMenuCtRight").animate({right: "-" + ($("#CnoaMiniMenuCtRight").width() + 50) + "px"}, 150);
        var A = new CNOA.noticeWindow(D, C, {title: "", content: "", href: y, opentype: z}).goView();
        Ext.Ajax.request({
            url: "index.php?app=notice&func=notice&action=list&task=updateIsread",
            params: {id: D},
            method: "POST",
            success: function (E) {
                g.reload()
            }.createDelegate(this)
        })
    };
    var w = function (z, C, B) {
        var A = B.data;
        var y = A.href;
        if (y != undefined && y != "") {
            return "<a href='javascript:void(0)' onclick='viewNotice(" + A.id + ", " + A.from + ', "' + y + '",' + A.opentype + ")' ext:qtip='" + z + "'>" + z + "</a>"
        }
        return "<span ext:qtip='" + z + "'>" + z + "</span>"
    };
    var k = new Ext.grid.ColumnModel([i, {
        header: "id",
        dataIndex: "id",
        width: 20,
        sortable: true,
        hidden: true
    }, {
        header: lang("noticeContent"),
        dataIndex: "content",
        width: 310,
        sortable: false,
        menuDisabled: true,
        renderer: w
    }, {header: lang("noticeTime"), dataIndex: "noticetime", width: 120, menuDisabled: true, resizable: false}]);
    var r = new Ext.PagingToolbar({style: "border-left-width:1px;", displayInfo: true, store: g, pageSize: 15});
    var a;

    function h() {
        a = new Ext.grid.GridPanel({
            id: "CNOA_MAIN_NOTICE_RIGHT_PANEL",
            height: $(window).height(),
            renderTo: "CnoaMiniMenuCtRight",
            store: g,
            loadMask: {msg: lang("waiting")},
            cm: k,
            sm: i,
            hideBorders: true,
            border: false,
            autoScroll: false,
            stripeRows: true,
            bodyStyle: "border-left-width:1px;",
            listeners: {
                cellclick: function (A, C, z, B) {
                    var y = g.getAt(C).id + ",";
                    Ext.Ajax.request({
                        url: "index.php?app=notice&func=notice&action=list&task=signAllRead",
                        method: "POST",
                        params: {ids: y, type: "read"},
                        success: function (E) {
                            var D = Ext.decode(E.responseText);
                            if (D.success === true) {
                                CNOA.msg.notice2(D.msg);
                                g.reload()
                            } else {
                                CNOA.msg.alert(D.msg, function () {
                                })
                            }
                        }
                    })
                }
            },
            tbar: new Ext.Toolbar({
                style: "border-left-width:1px;",
                items: [lang("MyNotices"), "->", {
                    text: lang("markedAsRead"),
                    iconCls: "icon-sms-readed btn-filter-dark",
                    handler: function (z) {
                        var C = a.getSelectionModel().getSelections();
                        if (C.length == 0) {
                            CNOA.miniMsg.alertShowAt(z, lang("mustSelectOneRow"))
                        } else {
                            if (C) {
                                var A = "";
                                for (var y = 0; y < C.length; y++) {
                                    A += C[y].get("id") + ",";
                                    var B = C[y]
                                }
                                Ext.Ajax.request({
                                    url: "index.php?app=notice&func=notice&action=list&task=signAllRead",
                                    method: "POST",
                                    params: {ids: A, type: "read"},
                                    success: function (E) {
                                        var D = Ext.decode(E.responseText);
                                        if (D.success === true) {
                                            CNOA.msg.notice2(D.msg);
                                            g.reload()
                                        } else {
                                            CNOA.msg.alert(D.msg, function () {
                                            })
                                        }
                                    }
                                })
                            }
                        }
                    }
                }]
            }),
            bbar: r
        })
    }

    var b = function (C, B, A, z) {
        var y = {
            text: C, iconCls: A, tooltip: z, id: "ID_DESKTOP_NOTICE_GROUP" + B, handler: function () {
                mainPanel.closeTab("CNOA_MENU_SYSTEM_NEED_TODO");
                mainPanel.loadClass("index.php?app=notice&func=notice&action=todo&from=" + B, "CNOA_MENU_SYSTEM_NEED_TODO", lang("workToDo"), "icon-system-notice")
            }
        };
        return y
    };
    var q = function () {
        Ext.Ajax.request({
            url: "welcome/getAllMenuList",
            method: "POST",
            success: function (z) {
                var y = Ext.decode(z.responseText);
                if (y.success === true) {
                } else {
                    f.doLayout()
                }
                setTimeout(function () {
                    f.doLayout()
                }, 500)
            }
        })
    };
    var l = function () {
        $("#CnoaMiniMenuCtRight").css("display", "block");
        var z = $("#CnoaMiniMenuCtRight");
        if (z.length <= 0) {
            var y = '<div id="CnoaMiniMenuCtRight" style="height: ' + $(window).height() + 'px;"></div>';
            $(document.body).append(y);
            z = $("#CnoaMiniMenuCtRight");
            z.animate({right: "0px"}, 150);
            h()
        }
        z.animate({right: "0px"}, 150)
    };
    var m = function () {
        $("#CnoaMiniMenuCtRight").animate({right: "-" + ($("#CnoaMiniMenuCtRight").width() + 50) + "px"}, 150);
        $("#CnoaMiniMenuCtRight").css("display", "none");
        $("#CNOA_MAIN_REMIND_PANEL").remove()
    };
    window.closeRemindPanel = m;

    function u(y) {
        new CNOA_main_common_indexSettingWinClass(y)
    }

    var c = new Ext.Panel({
        region: "center",
        border: false,
        hideBorders: true,
        tbar: new Ext.Toolbar({
            style: "padding-top: 5px;",
            height: 40,
            id: "CNOA_MAIN_TOP_RIGHT_BUTTONSCT",
            items: ["->", "<img id='CNOA_main_framework_ajax_status' src='/cnoa/resources/images/wait.gif' style='margin-right:15px;display:none;'>", {
                text: lang("setDesktop"),
                iconCls: "icon-desktop",
                height: 30,
                hidden: ((CNOA_LOCK_INDEX_LAYOUT == 1) && (CNOA_USER_JOBTYPE != "superAdmin")) ? true : false,
                handler: function (y) {
                    u(y)
                }
            }, {
                text: "切换用户",
                height: 25,
                hidden: window.CNOA_ALLOW_SWITCH_USER != 1,
                iconCls: "icon-switchuser-white",
                tooltip: "不需要退出重新登陆即可切换用户，方便试用用户快速体验OA系统，省去很多繁琐步骤(本功能可由超级管理在后台选择是否关闭，正式版本无此功能)！",
                handler: function (y) {
                    new window.CNOA_SWITCHUSER().show()
                }
            }, {
                text: lang("remind"),
                id: "CNOA_DESKTOP_NOTICE_BTN",
                iconCls: "icon-notice-white",
                height: 25,
                handler: function (y) {
                    l();
                    g.load();
                    $(document.body).append('<div class="ext-el-mask" id="CNOA_MAIN_REMIND_PANEL" style="opacity: 0;filter: alpha(opacity=0);z-index:1" onclick="closeRemindPanel();"></div>')
                }
            }, {
                text: lang("workToDo"),
                id: "CNOA_DESKTOP_NOTICE_ALLWAIT_BTN",
                iconCls: "icon-todo-white",
                height: 25,
                handler: function () {
                    mainPanel.closeTab("CNOA_MENU_SYSTEM_NEED_TODO");
                    mainPanel.loadClass("index.php?app=notice&func=notice&action=todo", "CNOA_MENU_SYSTEM_NEED_TODO", lang("workToDo"), "icon-system-notice")
                }
            }, {
                height: 25,
                id: "CNOA_DESKTOP_NOTICE_OVERTIME_BTN",
                iconCls: "icon-flow-overtime-green",
                tooltip: lang("cswblqck"),
                hidden: true,
                handler: function () {
                    mainPanel.closeTab("CNOA_MENU_WF_USE_NOTDONE");
                    mainPanel.loadClass("index.php?app=wf&func=flow&action=use&modul=todo&task=loadPage&from=list", "CNOA_MENU_WF_USE_NOTDONE", lang("flowToDo"), "icon-system-notice")
                }
            }, n, {
                text: lang("help"),
                iconCls: "icon-help-white",
                height: 25,
                menu: [{
                    text: lang("theme"),
                    iconCls: "icon-system-theme-setup",
                    hideOnClick: false,
                    menu: {items: j}
                }, {
                    text: lang("DownloadAuxiliaryControls"), handler: function () {
                        window.open("./file/webactivex.zip")
                    }
                }, {
                    text: lang("help"),
                    hidden: CNOA.config.scblg.shmh == 1 ? false : true,
                    iconCls: "icon-system-help",
                    handler: function () {
                        window.open("http://faq.cnoa.cn/")
                    }
                }, {
                    text: lang("onlineCount"), onlinenum: 0, myonlinenum: 0, handler: function (y) {
                        CNOA_ONLINENUM_REFRESH_FUNCTION(true);
                        CNOA_SHOW_ONLINE_LIST_WINDOW()
                    }, listeners: {
                        mouseover: function (y) {
                            var z = lang("currentOnlineCount", y.onlinenum);
                            z += "<br/>";
                            z += lang("iloginAtCount", y.myonlinenum);
                            CNOA.miniMsg.alertShowAt(y, z, 60, -70, "bottom")
                        }, mouseout: function () {
                            CNOA.miniMsg.hide()
                        }
                    }
                }, "-", {
                    text: lang("register"),
                    hidden: CNOA_USER_JOBTYPE == "superAdmin" ? false : true,
                    handler: function () {
                        mainPanel.loadClass("index.php?app=main&func=system&action=regist", "CNOA_MENU_SYSTEM_SETUP_REGIST", lang("register"), "icon-key")
                    }
                }, {
                    text: lang("about"), hidden: CNOA.config.scblg.shma == 1 ? false : true, handler: function () {
                        if (CNOA_WINDOW_ABOUTUS == null) {
                            CNOA_WINDOW_ABOUTUS = new CNOA_WINDOW_ABOUTUS_CLASS();
                            CNOA_WINDOW_ABOUTUS.show()
                        }
                    }
                }]
            }, {
                text: CNOA_USER_TRUENAME, iconCls: "icon-person-color", height: 25, handler: function (y) {
                    createMyInfoPanel();
                    var z = $("#lhc_menu").hasClass("in");
                    if (z) {
                        $("#lhc_menu").addClass("out");
                        $("#lhc_menu").removeClass("in")
                    } else {
                        $("#lhc_menu").addClass("in");
                        $("#lhc_menu").removeClass("out")
                    }
                    $(document.body).append('<div class="ext-el-mask" id="CNOA_MAIN_MYINFO_PANEL" style="opacity: 0;filter: alpha(opacity=0);z-index:1" onclick="closeMyInfoPanel();"></div>')
                }
            }]
        })
    });
    q();
    var v = new Ext.Panel({
        width: 400,
        region: "west",
        border: false,
        hideBorders: true,
        tbar: new Ext.Toolbar({height: 40, items: [t]})
    });
    var f = new Ext.Panel({
        id: "ID_CNOA_main_header",
        border: false,
        layout: "border",
        region: "north",
        clas: "docs-header",
        border: false,
        hideBorders: true,
        height: 40,
        items: [v, c]
    });
    var s = new Ext.Viewport({layout: "border", items: [f, menuPanel, mainPanel]});
    s.doLayout();
    window.OAViewport = s;
    setTimeout(function () {
        try {
            Ext.get("loading").remove()
        } catch (y) {
        }
        try {
            Ext.get("loading-mask").remove()
        } catch (y) {
        }
    }, 250)
}

function createMyInfoPanel() {
    if ($("#lhc_menu").length > 0) {
        return
    }



    var a = '<div id="lhc_menu">';
    a += '<div class="lhc_img"><img src=""/></div>';
    a += '<div class="lhc_back"></div>';
    a += '<div class="lhc_name_dept">'+CNOA_USER_TRUENAME+'</br><span class="cnoa_color_gray">'+CNOA_USER_DEPTMENT+'</span></div>';
    a += '<div class="lhc_email">'+CNOA_USER_EMAIL+'</div>';
    a += '<div id="lhc_main">';
    a += '<div class="lhc_drop_down">';
    a += '<div class="lhc_person">' + lang("personalProfile") + "</div>";
    a += '<div class="lhc_person_info">';
    a += '<div class="lhc_edit_info lhc_common_info"><a href="javascript:void(0)">' + lang("editMyInfo") + "</a></div>";
    a += '<div class="lhc_edit_password lhc_common_info"><a href="javascript:void(0)">' + lang("pwdSetting") + "</a></div>";
    a += '<div class="lhc_edit_group lhc_common_info"><a href="javascript:void(0)">' + lang("customizedGroups") + "</a></div>";
    a += "</div>";
    if (CNOA.config.scblg.shmh == 1) {
        a += '<div class="lhc_help lhc_common_about"><a href="javascript:void(0)">' + lang("helpCenter") + "</a></div>"
    }
    a += '<div class="lhc_about lhc_common_about">';
    if (CNOA.config.scblg.shma == 1) {
        a += '<a href="javascript:void(0)">' + lang("aboutSoftware") + "</a>"
    }
    a += '<span class="lhc_new_version" style="display:none;"></span></div>';
    a += '<div class="lhc_weather">';
    a += '<div class="lhc_city"></div>';
    a += '<div class="lhc_weather_img"><img src="cnoa/resources/images/weather/b/01.png" width="60" height="60" style="background-color:#4E97FF;" /></div>';
    a += '<div class="lhc_temp"></div>';
    a += '<div class="lhc_weat"></div>';
    a += '<div class="lhc_setting"><a href="javascript:void(0)">' + lang("weatherSettings") + "</a></div>";
    a += "</div>";
    a += "</div>";
    a += "</div>";
    a += '<div class="lhc_footer">';
    a += '<div class="lhc_logout lhc_common_footer"><a href="javascript:void(0)">' + lang("logout") + "</a></div>";
    a += "</div>";
    a += "</div>";
    $("body").append(a);
    $(".lhc_back").click(function () {
        $("#lhc_menu").addClass("out");
        $("#lhc_menu").removeClass("in")
    });
    $(".lhc_logout a").click(function () {
        CNOA.Logout("index.php?app=main&func=passport&action=logout")
    });
    $(".lhc_setting a").click(function () {
        setting()
    });
    $(".lhc_help a").click(function () {
        window.open("http://faq.cnoa.cn/")
    });
    $(".lhc_edit_info a").click(function () {
        mainPanel.loadClass("welcome/myInfo", "CNOA_MENU_MY_INFO", lang("myInfo"), "icon-cnoa-my-info")
    });
    $(".lhc_edit_password a").click(function () {
        mainPanel.loadClass("index.php?app=my&func=info&action=index&task=loadPage&from=passwd", "CNOA_MENU_MY_PASSWD", lang("pwdSetting"), "icon-cnoa-my-info")
    });
    $(".lhc_edit_group a").click(function () {
        mainPanel.loadClass("index.php?app=my&func=info&action=index&task=loadPage&from=group", "CNOA_MENU_MY_GROUP", lang("customGroup"), "icon-cnoa-my-info")
    });
    $(".lhc_about a").click(function () {
        if (CNOA_WINDOW_ABOUTUS == null) {
            CNOA_WINDOW_ABOUTUS = new CNOA_WINDOW_ABOUTUS_CLASS();
            CNOA_WINDOW_ABOUTUS.show()
        }
    });
    Ext.Ajax.request({
        url: "index.php?app=main&func=common&action=commonJob&act=getWeather",
        method: "GET",
        success: function (c) {
            var b = Ext.decode(c.responseText);
            $(".lhc_city").text(b.data.city + " (" + b.data.wind1 + ")");
            $(".lhc_temp").text(b.data.temp1);
            $(".lhc_weat").text(b.data.weat1);
            $(".lhc_weather_img img").attr("src", b.data.pict1)
        }
    });
    getMyInfo();
    checkUpdate()
}

function closeMyInfoPanel() {
    $("#lhc_menu").addClass("out");
    $("#lhc_menu").removeClass("in");
    $("#CNOA_MAIN_MYINFO_PANEL").remove()
}

function getMyInfo() {
    var b = $(window).height(), a = b * 555 / 949;
    if (a >= 555) {
        $("#lhc_main").css("overflow-y", "hidden")
    } else {
        $("#lhc_main").css("overflow-y", "auto")
    }
    $("#lhc_main").css("height", a + "px");
    Ext.Ajax.request({
        url: "index.php?app=my&func=info&action=index&task=editLoadFormDataInfo",
        method: "POST",
        success: function (c) {
            var d = Ext.decode(c.responseText);
            $(".lhc_name_dept").html(d.data.truename + "<br /><span class='cnoa_color_gray'>" + d.data.deptname + "</span>");
            if (d.data.face) {
                $(".lhc_img img").attr("src", d.data.face)
            } else {
                $(".lhc_img img").attr("src", "./resources/images/default.jpg")
            }
            $(".lhc_email").text(d.data.email)
        }
    })
}

function checkUpdate() {
    Ext.Ajax.request({
        url: "index.php?act=commonJob&task=checkUpdate", method: "GET", success: function (b) {
            var a = Ext.decode(b.responseText);
            if (a) {
                if (a.data != "") {
                    $(".lhc_new_version").show();
                    $(".lhc_new_version").attr("ext:qtip", lang("OASystemHasUpgrade") + "v" + a.data[0].version)
                } else {
                    $(".lhc_new_version").hide()
                }
            } else {
                $(".lhc_new_version").hide()
            }
        }
    })
}

function addNewTab(d, c, a, b) {
    if (mainPanel) {
        mainPanel.loadClass(d, b, c, a)
    }
}

function removeTabByIdd(b) {
    if (mainPanel) {
        try {
            var d = "TABPANEL__" + b;
            var a = mainPanel.getItem(d);
            if (a) {
                mainPanel.remove(a, true)
            }
        } catch (c) {
        }
    }
}

var CNOA_WINDOW_ABOUTUS;
var CNOA_WINDOW_ABOUTUS_CLASS = CNOA.Class.create();
CNOA_WINDOW_ABOUTUS_CLASS.prototype = {
    init: function () {
        _this = this;
        this.CT = new Ext.Panel({
            bodyStyle: "padding:15px;", border: false, listeners: {
                render: function (a) {
                    Ext.Ajax.request({
                        url: "index.php?app=main&func=system&action=about",
                        method: "GET",
                        success: function (g) {
                            var b = Ext.decode(g.responseText);
                            var h = "", c = "Copyright &copy; 广州协众软件科技有限公司版权所有.", d = "www.cnoa.cn";
                            if (b.oem == true) {
                                h = "background-image: url('data:image/jpg;base64," + b.oem_logo + "');";
                                c = b.oem_company;
                                d = b.oem_site
                            }
                            var f = '<div class="cnoa_main_about_us_ct">';
                            f += '<div class="cnoa_main_about_us_ct_logo" style="' + h + '"></div>';
                            f += '<div style="line-height:20px;"><div>' + c + "</div>";
                            f += '<div>官方网站: <a href="http://' + d + '/" target="_blank">' + d + "</a></div>";
                            if (b.oem == true) {
                                f += "<br />"
                            } else {
                                f += '<div>服务支持: <a href="http://www.cnoa.cn/" target="_blank">协众软件</a></div>'
                            }
                            f += '<hr style="border-width:0;border-bottom:1px solid #15428B;"></hr>';
                            f += "<div>注 册 到: " + b.compny + "</div>";
                            f += "<div>序 列 号: " + b.key + "</div>";
                            f += "<div>版本信息: " + b.versionname + "</div></div>";
                            f += "</div>";
                            a.body.update(f)
                        }
                    })
                }
            }
        });
        this.mainPanel = new Ext.Window({
            width: 400,
            height: 306,
            title: lang("about"),
            layout: "fit",
            items: [this.CT],
            buttonAlign: "right",
            buttons: [{
                text: lang("ok"), handler: function () {
                    this.mainPanel.close()
                }.createDelegate(this)
            }],
            listeners: {
                close: function (a) {
                    Ext.destroy(a);
                    Ext.destroy(CNOA_WINDOW_ABOUTUS);
                    CNOA_WINDOW_ABOUTUS = null
                }
            }
        })
    }, show: function () {
        _this.mainPanel.show()
    }
};
var CNOA_my_info_mainPanel = null;

function showMyInfoPanel(a) {
    CNOA_my_info_mainPanel = new Ext.Panel({
        width: 0,
        height: 0,
        hidden: true,
        autoLoad: {url: a + "&ajax=1", scripts: true, scope: this, nocache: true},
        renderTo: document.body
    })
}

function main_init_load_tab() {
    try {
        if (CNOA_CLIENT_OPENTAB_YES === true) {
            if (CNOA_CLIENT_OPENTAB_OPENTYPE == 1) {
                mainPanel.closeTab(CNOA_CLIENT_OPENTAB_ID);
                mainPanel.loadClass(CNOA_CLIENT_OPENTAB_HREF, CNOA_CLIENT_OPENTAB_ID, CNOA_CLIENT_OPENTAB_TITLE, CNOA_CLIENT_OPENTAB_ICON)
            }
            if (CNOA_CLIENT_OPENTAB_OPENTYPE == 2) {
                new Ext.Window({
                    width: 700,
                    height: makeWindowHeight(550),
                    maximizable: true,
                    title: CNOA_CLIENT_OPENTAB_TITLE,
                    html: '<div style="width:100%;height:100%;"><iframe scrolling=auto frameborder=0 width=100% height=100% src="' + CNOA_CLIENT_OPENTAB_HREF + '"></iframe></div>'
                }).show()
            }
            if (CNOA_CLIENT_OPENTAB_OPENTYPE == 3) {
                window.open(CNOA_CLIENT_OPENTAB_HREF)
            }
        }
    } catch (a) {
    }
}

function debug(a) {
    Ext.getCmp("CNOA_DEBUG_BAR").getEl().update(a)
}

function main_init_load_task() {
    var b = document.getElementsByTagName("HEAD").item(0);
    var a = document.createElement("script");
    a.type = "text/javascript";
    a.src = "/cnoa/"+CNOA.config.file + "/webcache/task.js";
    b.appendChild(a)
}

function show_expire_window() {
    var a = "";
    if (CNOA_VERSION_INFO.keyOutdate) {
        a = lang("sqsbcxsq")
    }
    if (CNOA_VERSION_INFO.expire) {
        a = lang("expiredRegNotice")
    }
    CNOA.msg.alert(a, function () {
        if (CNOA_USER_JOBTYPE == "superAdmin") {
            mainPanel.loadClass("index.php?app=main&func=system&action=regist&modal=1", "CNOA_MENU_SYSTEM_SETUP_REGIST", lang("register"), "icon-key")
        } else {
            CNOA.Logout("index.php?app=main&func=passport&action=logout")
        }
    })
}

function CNOA_SHOW_ONLINE_LIST_WINDOW() {
    window.CNOA_ONLINEDLOGINNOTICED = true;
    window.CNOA_KICKSELFFUNC = function (j, l, k) {
        CNOA.msg.cf(lang("sureToLogout") + "<br>" + lang("account") + "：" + j + "<br>" + lang("ip2") + "：" + l, function (m) {
            if (m == "yes") {
                Ext.Ajax.request({
                    url: "index.php?task=getonlinecount&job=kick",
                    method: "POST",
                    params: {session: k},
                    success: function (p, o) {
                        var n = Ext.decode(p.responseText);
                        if (n.failure) {
                            CNOA.msg.alert(n.msg)
                        } else {
                            CNOA.msg.alert(n.msg, function () {
                                b.reload()
                            })
                        }
                    },
                    failure: function (n, o) {
                        CNOA.msg.alert(result.msg)
                    }
                })
            }
        })
    };
    var c = function (p, n, j, q, l, o) {
        var m = j.data, k = "";
        if (m.self1 == 1) {
            k = ' <span class="cnoa_color_red">[' + lang("localPc") + "]</span>"
        }
        return ' <a href="http://ip138.com/ips138.asp?ip=' + p + '&action=2" target="_blank">' + p + "</a>" + k
    };
    var h = function (p, n, j, q, l, o) {
        var m = j.data, k = "";
        if (m.self2 == 1) {
            k = '<a href="javascript:void(0);" onclick="CNOA_KICKSELFFUNC(\'' + m.truename + "','" + m.ip + "','" + m.session + "')\">" + lang("logout2") + "</a>"
        }
        return k
    };
    var g = function () {
        Ext.Ajax.request({
            url: "index.php?task=getonlinecount", method: "GET", success: function (j) {
                var k = Ext.decode(j.responseText);
                $("#onlinePeople").html(lang("onlineCount") + ":[" + k.oluids.length + "]+[" + k.mlnums + "]")
            }
        })
    };
    g();
    var a = [{name: "uid"}, {name: "truename"}, {name: "dept"}, {name: "ip"}, {name: "from"}, {name: "session"}, {name: "self1"}, {name: "self2"}];
    var b = new Ext.data.Store({
        proxy: new Ext.data.HttpProxy({
            url: "index.php?task=getonlinecount&job=getonlinelist",
            disableCaching: true
        }), reader: new Ext.data.JsonReader({totalProperty: "total", root: "data", fields: a})
    });
    b.load();
    var d = new Ext.grid.ColumnModel([{
        header: lang("truename"),
        dataIndex: "truename",
        width: 130,
        sortable: true
    }, {header: lang("inDepartment"), dataIndex: "dept", width: 233, sortable: true}, {
        header: lang("ip2"),
        dataIndex: "ip",
        width: 135,
        sortable: true,
        renderer: c.createDelegate(this)
    }, {header: lang("comeFrom"), dataIndex: "from", width: 76, sortable: true}, {
        header: lang("opt"),
        dataIndex: "noIndex",
        width: 60,
        sortable: false,
        menuDisabled: true,
        renderer: h.createDelegate(this)
    }, {header: "", dataIndex: "noIndex", width: 1}]);
    var f = new Ext.grid.GridPanel({
        stripeRows: true,
        store: b,
        loadMask: {msg: lang("loading")},
        cm: d,
        hideBorders: true,
        border: false,
        tbar: new Ext.Toolbar({
            items: [{
                handler: function (j, k) {
                    g();
                    b.reload()
                }.createDelegate(this), text: lang("refresh")
            }, {
                text: lang("onlineList"),
                allowDepress: false,
                toggleGroup: "onlineviewbtngp",
                pressed: true,
                toggleHandler: function (j, k) {
                    if (k) {
                        b.load({params: {from: "all"}})
                    } else {
                        b.load({params: {from: "self"}})
                    }
                }.createDelegate(this)
            }, {
                text: lang("myOnlineStatus"),
                allowDepress: false,
                toggleGroup: "onlineviewbtngp"
            }, ("<span style='color:#999' id='onlinePeople'></span>")]
        })
    });
    var i = new Ext.Window({
        width: 700,
        height: makeWindowHeight(480),
        modal: true,
        title: lang("onlineList"),
        layout: "fit",
        resizable: false,
        items: [f],
        buttonAlign: "right",
        buttons: [{
            text: lang("close"), handler: function () {
                i.close()
            }.createDelegate(this)
        }]
    }).show()
}

var differentMillisec = 0;

function initServerTime() {
    getServerDate()
}

function showtime() {
    now = new Date();
    now.setTime(differentMillisec + now.getTime());
    str = now.format(lang("ymd") + " H:i:s");
    var a = new Array(lang("sunday"), lang("monday"), lang("tuesday"), lang("wednesday"), lang("thursday"), lang("friday"), lang("saturday"));
    str += " [" + a[now.getDay()] + "]";
    ctroltime = setTimeout("showtime()", 1000)
}

function getServerDate() {
    begin = new Date();
    millisecbeg = begin.getTime();
    Ext.Ajax.request({
        url: "welcome/getServerTime13", method: "POST", success: function (c, b) {
            var a = c.responseText;
            end = new Date();
            millisecend = end.getTime();
            differentMillisec = a - new Date() + (millisecend - millisecbeg) / 2;
            showtime()
        }, failure: function (a, b) {
            CNOA.msg.alert(result.msg)
        }
    })
}

function initMiniMenuCt() {
    var a = ['<div id="CnoaMiniMenuCt" class="CNOA_MAIN_EX_MENU2">', "	<div>", '		<div class="title"></div>', '		<div id="CnoaMiniMenuCt2" class="CNOA_ROOT_TREE"></div>', "	</div>", "</div>"];
    var b = $("#CnoaMiniMenuCt");
    if (b.length == 0) {
        $(document.body).append(a.join(""))
    }
}

function documentClick() {
    $(document).click(function (a) {
    })
}

// function checkAttackUpdate() {
//     Ext.Ajax.request({
//         url: "index.php?action=commonJob&act=checkAttackUpdate", method: "GET", success: function (b, a) {
//         }, failure: function (a, b) {
//         }
//     })
// }

Ext.onReady(function () {
    var b = false;
    var c = false;
    var d = false;
    var a = false;
    var f = setInterval(function () {
        try {
            if (CNOA_SCRIPTS_LOADED_MARK_CORE == 1) {
                b = true
            }
        } catch (g) {
        }
        try {
            if (CNOA_SCRIPTS_LOADED_MARK_COMMON == 1) {
                c = true
            }
        } catch (g) {
        }
        try {
            if (CNOA_SCRIPTS_LOADED_MARK_EXTRA == 1) {
                d = true
            }
        } catch (g) {
        }
        if (b && c && d) {
            clearInterval(f);
            main_init_desktop();
            main_init_load_task();
            initServerTime();
            initMiniMenuCt();
            documentClick();
            // setTimeout(function () {
            //     checkAttackUpdate()
            // }, 10000)
        }
    }, 100);
    if (CNOA_VERSION_INFO.wrongmkey) {
        CNOA.msg.alert(lang("sqsbcxsq"), function () {
            if (CNOA_USER_JOBTYPE == "superAdmin") {
                mainPanel.loadClass("index.php?app=main&func=system&action=regist&modal=1", "CNOA_MENU_SYSTEM_SETUP_REGIST", lang("register"), "icon-key")
            } else {
                CNOA.Logout("index.php?app=main&func=passport&action=logout")
            }
        })
    }
    if (CNOA_VERSION_INFO.expire || CNOA_VERSION_INFO.keyOutdate) {
        //show_expire_window()
    }
    $("a").live("click", function (h) {
        try {
            var g = h.currentTarget;
            if (!Ext.isEmpty(g.href) && (/^http:|https:/.test(g.href))) {
                $(g).attr("target", "_blank")
            }
        } catch (i) {
        }
    })
});
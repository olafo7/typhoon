var CNOA_main_job_addClass, CNOA_main_job_add;
var CNOA_main_job_add_edit_ifCascade = true;
CNOA_main_job_addClass = CNOA.Class.create();
CNOA_main_job_addClass.prototype = {
    init: function(a, b) {
        var c = this;
        this.parentPanel = a;
        this.tp = b;
        this.actionUrl = b == "edit" ? "main/job/edit": "main.job/add";
        this.title = b == "edit" ? lang("editJob") : lang("addJob");
        this.action = b == "edit" ? "edit": "add";
        this.edit_id = 0;
        this.tabPanelLoaded = false;
        this.permitNowId = null;
        this.permitList = new Array();
        this.ID_tabBaseInfo = Ext.id();
        this.ID_btn_save = Ext.id();
        this.ID_btn_apply = Ext.id();
        this.ID_tree_structAreaTreeRoot = Ext.id();
        this.ID_radiogroup_jobTypeGroup = Ext.id();
        this.ID_triggerForDept_deptName = Ext.id();
        this.jobType = "user";
        this.jobTypeChangeTime = 0;
        this.jobTypeAdminChangeTime = 0;
        this.baseField = [{
            xtype: "textfield",
            name: "name",
            width: 350,
            fieldLabel: lang("jobName"),
            inputType: "text",
            allowBlank: false,
            maxLength: 40,
            labelStyle: "margin-top:20px;",
            style: "margin-top:20px;",
            maxLengthText: lang("maxLengthText") + 40,
            validationEvent: "click"
        },
            {
                xtype: "radiogroup",
                fieldLabel: lang("jobType"),
                allowBlank: false,
                hidden: true,
                name: "jobTypeGroup",
                width: 200,
                id: this.ID_radiogroup_jobTypeGroup,
                items: [{
                    boxLabel: lang("normalJob"),
                    name: "jobType",
                    inputValue: "user",
                    checked: true
                },
                    {
                        boxLabel: "系统管理职位",
                        name: "jobType",
                        inputValue: "admin",
                        hidden: (!CNOA.permitController.main_job.adminRadio) || (CNOA_USER_JOBTYPE == "user")
                    }],
                listeners: {
                    change: function(e, d) {
                        if ((this.jobTypeChangeTime == 0)) {
                            this.jobTypeChangeTime++
                        } else {
                            this.formPanel.getForm().findField("deptId").setValue("");
                            this.formPanel.getForm().findField("deptName").setValue("")
                        }
                        if (d.inputValue == "user") {
                            this.jobType = "user"
                        } else {
                            if (d.inputValue == "admin") {
                                this.jobType = "admin";
                                if ((this.jobTypeAdminChangeTime == 0) && (this.action == "edit")) {
                                    this.jobTypeAdminChangeTime++
                                }
                            }
                        }
                        var f = "index.php?app=main&func=job&action=" + this.action + "&task=getStructTree&jobType=" + this.jobType;
                        Ext.getCmp(this.ID_triggerForDept_deptName).getLoader().dataUrl = f
                    }.createDelegate(this)
                }
            },
            {
                xtype: "departmentselectorfield",
                id: this.ID_triggerForDept_deptName,
                fieldLabel: lang("inDepartment"),
                width: 350,
                allowBlank: false,
                name: "deptId",
                dataUrl: "index.php?app=main&func=job&action=" + this.action + "&task=getStructTree&jobType=" + this.jobType,
                multiSelect: false
            },
            {
                xtype: "textarea",
                name: "about",
                fieldLabel: lang("jobDescription"),
                inputType: "text",
                height: 80,
                width: 350,
                validationEvent: "click"
            }];
        this.permissionList = new Ext.Panel({
            region: "center",
            hideBorders: true,
            border: false,
            padding: 10,
            style: "border-top: 1px solid #A3BAE9;",
            autoScroll: true,
            tbar: ["<b>" + lang("permitList") + "</b>", "->", (lang("permitName") + ":"), {
                xtype: "search",
                key: "$('span.search_class')",
                searchIcon: false
            }],
            items: []
        });
        this.permitScopeStructTreeRoot = new Ext.tree.AsyncTreeNode({
            id: this.ID_tree_structAreaTreeRoot,
            expanded: true,
            rootVisible: false,
            checked: false,
            draggable: false
        });
        this.permitScopeStructTreeLoader = new Ext.tree.TreeLoader({
            //dataUrl: "index.php?app=main&func=job&action=" + this.action + "&task=getStructTree",
            dataUrl: "common/getSelectorDataOfDept",
            preloadChildren: true,
            clearOnLoad: false,
            baseAttrs: {
                uiProvider: Ext.ux.TreeCheckNodeUI
            }
        });
        this.permitScopeStructTree = new Ext.tree.TreePanel({
            animate: false,
            enableDD: false,
            hideBorders: true,
            border: false,
            hidden: true,
            containerScroll: true,
            checkModel: "multiple",
            loader: this.permitScopeStructTreeLoader,
            root: this.permitScopeStructTreeRoot,
            rootVisible: false,
            padding: 10,
            autoScroll: true,
            listeners: {
                checkchange: this.permitScopeStructTreeCheckChange
            }
        });
        this.permitScopeStructTree.getRootNode().expand();
        this.permitScopeStructPanel = new Ext.Window({
            title: lang("selectPermitArea"),
            hideBorders: true,
            border: false,
            width: 350,
            layout: "fit",
            height: 398,
            modal: true,
            closeAction: "hide",
            items: [this.permitScopeStructTree],
            buttons: [{
                text: lang("save"),
                id: this.ID_btn_save,
                iconCls: "icon-btn-save",
                cls: "btn-blue4",
                handler: function() {
                    this.getSavedPermitScopeData();
                    this.permitScopeStructPanel.hide()
                }.createDelegate(this)
            },
                {
                    text: lang("close"),
                    iconCls: "icon-dialog-cancel",
                    cls: "btn-red1",
                    handler: function() {
                        this.permitScopeStructPanel.hide()
                    }.createDelegate(this)
                }]
        });
        this.formPanel = new Ext.form.FormPanel({
            width: 500,
            height: 450,
            autoWidth: true,
            border: false,
            monitorResize: true,
            labelWidth: 70,
            labelAlign: "right",
            monitorResize: true,
            waitMsgTarget: true,
            name: "CNOA_main_job_add_formpanel",
            id: "CNOA_main_job_add_formpanel",
            layout: "border",
            items: [new Ext.Panel({
                region: "north",
                height: 170,
                border: false,
                layout: "form",
                items: this.baseField
            }), this.permissionList]
        });
        this.mainPanel = new Ext.Window({
            width: 550,
            resizable: false,
            autoDistory: true,
            modal: true,
            maskDisabled: true,
            layout: "fit",
            items: [this.formPanel],
            plain: true,
            title: this.title,
            buttonAlign: "right",
            buttons: [{
                text: lang("save"),
                id: this.ID_btn_save,
                iconCls: "icon-btn-save",
                cls: "btn-blue4",
                handler: function() {
                    this.submitForm({
                        close: true
                    })
                }.createDelegate(this)
            },
                {
                    text: lang("apply"),
                    id: this.ID_btn_apply,
                    hidden: this.action == "edit" ? false: true,
                    iconCls: "icon-btn-save",
                    cls: "btn-blue4",
                    handler: function() {
                        this.submitForm({
                            close: false
                        })
                    }.createDelegate(this)
                },
                {
                    text: lang("close"),
                    cls: "btn-red1",
                    iconCls: "icon-dialog-cancel",
                    handler: function() {
                        this.close()
                    }.createDelegate(this)
                }],
            listeners: {
                afterrender: function(d) {
                    d.setHeight($(window).height() - 100)
                }
            }
        })
    },
    show: function(a) {
        this.mainPanel.show(null,
            function() {
                Ext.Ajax.request({
                    url: "index.php?app=main&func=job&action=" + this.action,
                    method: "POST",
                    params: {
                        task: "getPermitField"
                    },
                    success: function(c) {
                        var b = Ext.decode(c.responseText);
                        this.makePermissionField(a, b)
                    }.createDelegate(this)
                })
            }.createDelegate(this))
    },
    close: function() {
        this.mainPanel.close()
    },
    loadFormData: function(c, a) {
        var b = this;
        this.formPanel.getForm().load({
            url: "index.php?app=main&func=job&action=edit",
            params: {
                id: c,
                task: "loadData",
                type: a
            },
            method: "POST",
            waitMsg: lang("waiting"),
            success: function(d, e) {
                this.edit_id = c;
                this.permitList = e.result.data.perlist;
                Ext.getCmp(this.ID_radiogroup_jobTypeGroup).setValue(e.result.data.jobType);
                b.initPermitList()
            }.createDelegate(this),
            failure: function(d, e) {
                CNOA.msg.alert(e.result.msg,
                    function() {
                        b.close()
                    })
            }.createDelegate(this)
        })
    },
    submitForm: function(b) {
        this.getSavedPermitScopeData();
        var e = b.close ? this.ID_btn_save: this.ID_btn_apply;
        if (this.formPanel.getForm().isValid()) {
            if (this.action == "add" && this.tp != "clone") {
                var d = this.formPanel.getEl().query("input[name^=showPermitBox_]");
                if (d.length == 0) {
                    CNOA.miniMsg.alertShowAt(e, lang("noSetAreaNotice"), 35, 26);
                    return false
                }
                var a = false;
                for (var c = 0; c < d.length; c++) {
                    if (d[c].checked == true) {
                        a = true
                    }
                }
                if (a == false) {
                    CNOA.miniMsg.alertShowAt(e, lang("noSetAreaNotice"), 35, 26);
                    return false
                }
            }
            this.mainPanel.disable();
            this.formPanel.getForm().submit({
                url: this.actionUrl,
                waitTitle: lang("notice"),
                method: "POST",
                waitMsg: lang("waiting"),
                params: {
                    task: this.tp,
                    id: this.edit_id
                },
                success: function(f, g) {
                    this.parentPanel.jobStore.reload();
                    this.mainPanel.enable();
                    if (b.close) {
                        this.mainPanel.close()
                    }
                }.createDelegate(this),
                failure: function(f, g) {
                    CNOA.msg.alert(g.result.msg,
                        function() {
                            this.mainPanel.enable()
                        }.createDelegate(this))
                }.createDelegate(this)
            })
        } else {
            CNOA.miniMsg.alertShowAt(e, lang("formValid"), 35, 26)
        }
    },
    permitScopeStructTreeCheckChange: function(b, a) {
        b.expand();
        b.attributes.checked = a;
        if (CNOA_main_job_add_edit_ifCascade) {
            b.eachChild(function(c) {
                c.ui.toggleCheck(a);
                c.attributes.checked = a;
                c.fireEvent("checkchange", c, a)
            })
        }
    },
    makePermissionField: function(d, a) {
        var c = this;
        var b = new Ext.XTemplate('<table width="100%" id="permitMenuList">', '<tpl for=".">', "<tr>", '<td width="25%" style="margin-top; padding-bottom:2px;border-bottom: 1px dashed #D7D7D7;">', '<img src="./resources/images/cnoa/li-linepoint.gif" />', '<input type="checkbox" id="CNOA_USER_JOB_PERMIT_selectAll_{id}"  style="margin-right:7px">', '<span style="font-weight:bold;margin-top: 5px;">{name}</span>', "</td>", '<td style="border-bottom: 1px dashed #D7D7D7;" width="75%"><ul id="CNOA_USER_JOB_PERMIT_A_A_PANEL_{id}">', '<tpl for="action_list">', '<li class="x-form-check-wrap-border" style="margin:5px 0;">', '<input permitId="{id}" type="checkbox" name="showPermitBox_{id}" areaType="{permittype}" permitFor="{permitfor}" class=" x-form-checkbox x-form-field"/>', "<tpl if=\"values.permittype=='area'\">", '<input type="hidden" name="hiddenPermitBox_{id}"/>', "</tpl>", '<span ext:qtip="{about}" class="search_class" style="margin-left:4px;cursor:default;">{name}[{[values.permittype == "area" ? "<span class=cnoa_color_blue>' + lang("haveArea") + '</span>" : "<span class=cnoa_color_gray>' + lang("noArea") + '</span>"]}]</span>', "</li>", "</tpl>", "</ul></td>", "</tr>", "</tpl>", "</table>");
        b.overwrite(this.permissionList.body, a.data);
        this.permitMenuList = $("#permitMenuList");
        this.permitMenuList.find("input[id^=CNOA_USER_JOB_PERMIT_selectAll_]").each(function() {
            $(this).click(function() {
                var e = $(this).parent().next().find("input[name^=showPermitBox_]");
                var f = this.checked;
                e.each(function() {
                    this.checked = f;
                    if (f === true) {
                        var g = this;
                        $(this).parent().addClass("x-form-check-wrap-selected");
                        $(this).parent().click(function() {
                            c.permitBoxClick(g)
                        })
                    } else {
                        $(this).parent().removeClass("x-form-check-wrap-selected  x-form-check-wrap-active");
                        c.permitScopeStructTree.hide();
                        $(this).parent().unbind("click")
                    }
                })
            })
        });
        this.permitMenuList.find("input[name^=showPermitBox_]").each(function() {
            $(this).click(function() {
                if (this.checked === true) {
                    var e = this;
                    $(this).parent().click(function() {
                        c.permitBoxClick(e)
                    })
                } else {
                    $(this).parent().removeClass("x-form-check-wrap-selected x-form-check-wrap-active");
                    c.permitScopeStructTree.hide();
                    $(this).parent().unbind("click")
                }
            })
        });
        if (this.action == "edit") {
            this.loadFormData(d, "edit")
        } else {
            if (this.tp == "clone") {
                this.loadFormData(d, "clone")
            }
        }
    },
    permitBoxClick: function(a) {
        var c = this;
        if ((this.permitNowId != null)) {
            this.storeOldPermitScopeAreaList()
        }
        this.permitMenuList.find("li").removeClass("x-form-check-wrap-active");
        $(a).parent().addClass("x-form-check-wrap-selected x-form-check-wrap-active");
        if ($(a).attr("areaType") == "area") {
            this.permitScopeStructPanel.show();
            this.permitScopeStructTree.show();
            this.permitNowId = $(a).attr("permitId");
            this.loadPermission(this.permitNowId);
            this.enableAllPermitScopeTree(this.permitScopeStructTree.getRootNode().firstChild);
            var b = parseInt($(a).attr("permitFor"));
            if (b != 0) {
                this.disablePermitScopeForMaster(b)
            }
        }
        if (this.jobType == "admin") {
            this.permitScopeStructTree.hide()
        }
    },
    loadPermission: function(b) {
        var a = this.permitMenuList.find("input[name=hiddenPermitBox_" + b + "]").val();
        this.setPermitScopeStructTreeCheckNode(a.split(","))
    },
    initPermitList: function() {
        var c = this;
        var b = {};
        $.each(this.permitList,
            function(d, e) {
                b[e.id] = e.list
            });
        var a = this.permitMenuList.find("input[name]");
        $.each(a,
            function(e, h) {
                var d = $(this).attr("name");
                var g = parseInt(d.match(/\d+/g)[0]);
                if (b[g] != undefined) {
                    if (d.search(/^showPermitBox_/) != -1) {
                        h.checked = true;
                        var f = $(h).parent();
                        f.addClass("x-form-check-wrap-selected");
                        f.click(function() {
                            c.permitBoxClick($(h))
                        })
                    } else {
                        if (d.search(/^hiddenPermitBox_/) != -1) {
                            $(h).val(b[g])
                        }
                    }
                }
            })
    },
    setPermitScopeStructTreeCheckNode: function(b) {
        this.clearPermitScopeStructTreeCheckNode();
        CNOA_main_job_add_edit_ifCascade = false;
        if ((b == "")) {
            CNOA_main_job_add_edit_ifCascade = true;
            return
        }
        if ((b.length == 1) && (b == "0")) {
            this.permitScopeStructTree.getNodeById("CNOA_main_user_info_add_edit_permitScopeStructTreeRoot").ui.toggleCheck(true);
            return
        } else {
            if (b.length == 1) {
                this.permitScopeStructTree.getNodeById("CNOA_main_struct_list_tree_node_" + b).ui.toggleCheck(true)
            } else {
                for (var a = 0; a < b.length; a++) {
                    if (b[a] == "0") {
                        this.permitScopeStructTree.getNodeById(this.ID_tree_structAreaTreeRoot).ui.toggleCheck(true)
                    } else {
                        this.permitScopeStructTree.getNodeById("CNOA_main_struct_list_tree_node_" + b[a]).ui.toggleCheck(true)
                    }
                }
            }
        }
        CNOA_main_job_add_edit_ifCascade = true
    },
    clearPermitScopeStructTreeCheckNode: function() {
        a(this.permitScopeStructTree.getRootNode());
        function a(b) {
            b.expand();
            b.attributes.checked = false;
            b.eachChild(function(c) {
                c.ui.toggleCheck(false);
                c.attributes.checked = false;
                c.fireEvent("checkchange", c, false)
            })
        }
        return
    },
    storeOldPermitScopeAreaList: function() {
        var d = "";
        var a = this.permitScopeStructTree.getChecked("id");
        var b = "";
        if (a != "") {
            for (var c = 0; c < a.length; c++) {
                a[c] = a[c].replace("CNOA_main_struct_list_tree_node_", "").replace(this.ID_tree_structAreaTreeRoot, "0")
            }
        }
        d = a.join(",");
        this.permitMenuList.find("input[name=hiddenPermitBox_" + this.permitNowId + "]").val(d)
    },
    disablePermitScopeForMaster: function(d) {
        var b = d;
        var e = this.permitMenuList.find("input[name=showPermitBox_" + b + "]").val();
        var a = this.permitMenuList.find("input[name=hiddenPermitBox_" + b + "]").val();
        if (a != "") {
            permitMasterPermitsArr = a.split(",")
        } else {
            permitMasterPermitsArr = new Array()
        }
        c(this.permitScopeStructTree.getRootNode().firstChild);
        function c(f) {
            if (!CNOA.common.in_array(f.attributes.selfid, permitMasterPermitsArr)) {
                if (f.ui.isChecked()) {
                    f.getUI().checkbox.checked = false;
                    f.attributes.checked = false
                }
                f.disable()
            }
            f.eachChild(function(g) {
                if (!CNOA.common.in_array(g.attributes.selfid, permitMasterPermitsArr)) {
                    if (g.ui.isChecked()) {
                        g.getUI().checkbox.checked = false;
                        g.attributes.checked = false
                    }
                    g.disable()
                }
                c(g)
            })
        }
    },
    enableAllPermitScopeTree: function(a) {
        var b = this;
        a.expand();
        if (a.attributes.ds !== true) {
            a.enable()
        }
        a.eachChild(function(c) {
            if (c.attributes.ds !== true) {
                c.enable()
            }
            b.enableAllPermitScopeTree(c)
        })
    },
    getSavedPermitScopeData: function() {
        if (this.permitNowId != null) {
            this.storeOldPermitScopeAreaList()
        }
    }
};
var CNOA_main_job_listClass, CNOA_main_job_list;
CNOA_main_job_listClass = CNOA.Class.create();
CNOA_main_job_listClass.prototype = {
    init: function() {
        var a = this;
        this.tip_noPermission = lang("noPermitToUseFeature");
        this.searchParams = {
            deptId: 0,
            widthSon: true,
            word: ""
        };
        this.deptTree = this.createDeptTreePanel();
        this.treeRoot = this.deptTree.getRootNode();
        this.jobGrid = this.createJobGridPanel();
        this.jobStore = this.jobGrid.getStore();
        this.centerPanel = new Ext.Panel({
            title: lang("job") + lang("list"),
            region: "center",
            layout: "fit",
            bodyStyle: "border-left-width:1px;",
            autoScroll: true,
            items: [this.jobGrid],
            tbar: new CNOA.toolbar.SearchToolbar({
                style: "border-left-width:1px;",
                items: [(" " + lang("jobName") + "："), {
                    xtype: "textfield",
                    name: "word",
                    width: 230
                }],
                listeners: {
                    search: function(c, b) {
                        a.reloadJob(b)
                    },
                    clearsearch: function(b) {
                        a.reloadJob({
                            word: ""
                        })
                    }
                }
            })
        });
        this.mainPanel = new Ext.Panel({
            collapsible: false,
            hideBorders: true,
            border: false,
            layout: "border",
            autoScroll: true,
            items: [this.centerPanel, this.deptTree]
        })
    },
    createDeptTreePanel: function() {
        var b = this;
        var a = new CNOA.selector.DepartmentPanel({
            title: lang("struct"),
            region: "west",
            split: true,
            width: 180,
            minWidth: 80,
            maxWidth: 380,
            bodyStyle: "border-right-width:1px;",
            //dataUrl: "main/struct/getStructTree",
            dataUrl: "common/getSelectorDataOfDept",
            listeners: {
                render: function() {
                    Ext.state.Manager.set("CNOA_main_job_index_treeState", "")
                },
                dataloaded: function(f) {
                    var d = f.toggleButton.pressed;
                    var e = f.getRootNode();
                    e.expandChildNodes(d);
                    e.firstChild.select(true);
                    b.searchParams.deptId = e.firstChild.attributes.deptId;
                    var c = Ext.state.Manager.get("CNOA_main_job_index_treeState");
                    if (c) {
                        b.deptTree.expandPath(c)
                    }
                },
                click: function(c) {
                    if (c.disabled) {
                        return
                    }
                    b.reloadJob({
                        deptId: c.attributes.selfid
                    });
                    Ext.state.Manager.set("CNOA_main_job_index_treeState", c.getPath())
                }
            },
            tbar: new Ext.Toolbar({
                style: "border-right-width:1px;",
                items: ["->", {
                    text: lang("refresh"),
                    tooltip: lang("refresh"),
                    iconCls: "icon-system-refresh",
                    handler: function() {
                        b.reloadDept()
                    }
                }]
            }),
            bbar: new Ext.Toolbar({
                style: "border-right-width:1px;",
                items: [{
                    xtype: "checkbox",
                    checked: true,
                    boxLabel: lang("showDownDeptJob"),
                    listeners: {
                        check: function(d, c) {
                            b.reloadJob({
                                widthSon: c
                            })
                        }
                    }
                }]
            })
        });
        return a
    },
    createJobGridPanel: function() {
        var g = this;
        var a = [{
            name: "id"
        },
            {
                name: "name"
            },
            {
                name: "dept"
            },
            {
                name: "jobType"
            },
            {
                name: "about"
            }];
        var f = new Ext.data.Store({
            autoLoad: true,
            baseParams: this.searchParams,
            proxy: new Ext.data.HttpProxy({
                url: "main/job/getJsonData"
            }),
            reader: new Ext.data.JsonReader({
                totalProperty: "total",
                root: "data",
                fields: a
            })
        });
        var e = new Ext.grid.CheckboxSelectionModel({
            singleSelect: true
        });
        var d = function(h) {
            return '<a class="gridview" action="clone" jid="' + h + '">' + lang("clone") + "</a>"
        };
        var b = new Ext.grid.ColumnModel({
            defaults: {
                sortable: true
            },
            columns: [new Ext.grid.RowNumberer(), e, {
                header: "id",
                dataIndex: "id",
                width: 20,
                hidden: true
            },
                {
                    header: lang("jobName"),
                    dataIndex: "name",
                    width: 120
                },
                {
                    header: lang("inDepartment"),
                    dataIndex: "dept",
                    width: 200
                },
                {
                    header: lang("jobDescription"),
                    dataIndex: "about",
                    width: 300
                },
                {
                    header: lang("opt"),
                    dataIndex: "id",
                    width: 200,
                    renderer: d
                },
                {
                    header: "",
                    dataIndex: "noIndex",
                    width: 1,
                    menuDisabled: true,
                    resizable: false
                }]
        });
        var c = new Ext.grid.PageGridPanel({
            loadMask: {
                msg: lang("waiting")
            },
            autoWidth: true,
            stripeRows: true,
            viewConfig: {
                forceFit: true
            },
            cm: b,
            sm: e,
            store: f,
            listeners: {
                rowdblclick: function(h, i) {
                    g.showEditWindow(h.store.getAt(i).get("id"))
                },
                rowclick: function(h, j, i) {
                    if (i.target.getAttribute("action") === "clone") {
                        g.clone(i.target.getAttribute("jid"))
                    }
                }
            },
            tbar: new Ext.Toolbar({
                items: [{
                    text: lang("refresh"),
                    tooltip: lang("refresh"),
                    iconCls: "icon-system-refresh",
                    handler: function() {
                        g.reloadJob()
                    }
                },
                    {
                        text: lang("add"),
                        tooltip: CNOA.permitController.main_job.add ? lang("add") : this.tip_noPermission,
                        iconCls: "icon-utils-s-add",
                        disabled: !CNOA.permitController.main_job.add,
                        handler: function() {
                            CNOA_main_job_add = new CNOA_main_job_addClass(g, "add");
                            CNOA_main_job_add.show()
                        }
                    },
                    {
                        text: lang("modify"),
                        tooltip: CNOA.permitController.main_job.edit ? lang("modify") : this.tip_noPermission,
                        iconCls: "icon-utils-s-edit",
                        disabled: !CNOA.permitController.main_job.edit,
                        handler: function(i) {
                            var j = c.getSelectionModel().getSelections();
                            if (j.length == 0) {
                                var h = i.getEl().getBox();
                                CNOA.miniMsg.alert(lang("mustSelectOneRow"), [h.x + 12, h.y + 26])
                            } else {
                                g.showEditWindow(j[0].get("id"))
                            }
                        }
                    },
                    {
                        text: lang("del"),
                        tooltip: CNOA.permitController.main_job["delete"] ? lang("del") : this.tip_noPermission,
                        iconCls: "icon-utils-s-delete",
                        disabled: !CNOA.permitController.main_job["delete"],
                        handler: function(i) {
                            var j = c.getSelectionModel().getSelections();
                            if (j.length == 0) {
                                var h = i.getEl().getBox();
                                CNOA.miniMsg.alert(lang("mustSelectOneRow"), [h.x + 12, h.y + 26])
                            } else {
                                CNOA.msg.cf(lang("confirmToDelete"),
                                    function(k) {
                                        if (k == "yes") {
                                            if (j) {
                                                CNOA_main_job_list.deleteRecord(j[0].get("id"))
                                            }
                                        }
                                    })
                            }
                        }
                    },
                    (CNOA.permitController.main_job.edit ? "<span style='color:#999'>" + lang("dblclickToEdit") + "</span>": ""), "->", {
                        xtype: "cnoa_helpBtn",
                        helpid: 27
                    }]
            })
        });
        return c
    },
    reloadJob: function(a) {
        if (typeof a === "object") {
            for (key in a) {
                if (key in this.searchParams) {
                    this.searchParams[key] = a[key]
                }
            }
            this.jobStore.load()
        } else {
            this.jobStore.reload()
        }
    },
    reloadDept: function() {
        this.treeRoot.reload()
    },
    showEditWindow: function(a) {
        if (!CNOA.permitController.main_job.edit) {
            return
        }
        CNOA_main_job_edit = new CNOA_main_job_addClass(this, "edit");
        CNOA_main_job_edit.show(a)
    },
    deleteRecord: function(a) {
        var b = this;
        Ext.Ajax.request({
            url: "index.php?app=main&func=job&action=delete",
            method: "POST",
            params: {
                ids: a
            },
            success: function(d) {
                var c = Ext.decode(d.responseText);
                if (c.success === true) {
                    CNOA.msg.alert(c.msg,
                        function() {
                            b.reloadJob()
                        })
                } else {
                    CNOA.msg.alert(c.msg)
                }
            }
        })
    },
    clone: function(a) {
        if (!CNOA.permitController.main_job.edit) {
            return
        }
        CNOA_main_job_edit = new CNOA_main_job_addClass(this, "clone");
        CNOA_main_job_edit.show(a)
    },
    getSelectedDept: function() {
        return this.deptTree.getSelectionModel().getSelectedNode()
    }
};
var sm_main_job = 1;
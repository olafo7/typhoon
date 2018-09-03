var CNOA_main_user_info_add_edit_tabPermit_ifCascade = true;
CNOA_main_user_infoClass = CNOA.Class.create();
CNOA_main_user_infoClass.prototype = {
    init: function(a, b) {
        this.parent = a;
        this.tp = b;
        this.baseUrl = "main/user?action=";
        this.title = b == "edit" ? lang("editUser") : lang("addUser");
        this.action = b == "edit" ? "edit": "add";
        this.edit_uid = 0;
        this.partTimeListArray = new Array();
        this.tabPanelLoaded = false;
        this.jobType = "";
        this.partTimeJobType = "";
        this.partTime = new Array();
        this.partTimeGridStoreLoaded = false;
        this.tabPanelPermitPanelLoaed = false;
        this.ID_tabPermitNoAreaNotice = Ext.id();
        this.tabPanelPermitPanelOpendTime = 0;
        this.ID_btn_save = Ext.id();
        this.ID_btn_apply = Ext.id();
        this.ID_tree_structAreaTreeRoot = Ext.id();
        this.ID_tab_baseInfo = Ext.id();
        this.ID_tab_partTime = Ext.id();
        this.ID_tab_permission = Ext.id();
        this.ID_worktime = Ext.id();
        var c = this;
        this.jobComboBoxDataStore = new Ext.data.Store({
            proxy: new Ext.data.HttpProxy({
                url: null
            }),
            reader: new Ext.data.JsonReader({
                root: "data",
                fields: [{
                    name: "jobId",
                    mapping: "id"
                },
                    {
                        name: "value",
                        mapping: "name"
                    },
                    {
                        name: "jobType"
                    }]
            })
        });
        this.jobComboBox = new Ext.form.ComboBox({
            fieldLabel: lang("atJob"),
            name: "jobId",
            store: this.jobComboBoxDataStore,
            hiddenName: "jobId",
            valueField: "jobId",
            displayField: "value",
            mode: "local",
            width: 160,
            allowBlank: false,
            triggerAction: "all",
            forceSelection: true,
            editable: false,
            listeners: {
                select: function(f, d, e) {
                    this.jobType = d.data.jobType;
                    if (d.data.jobType == "user") {} else {
                        this.disablePermitTabPanel()
                    }
                }.createDelegate(this),
                change: function() {
                    this.disablePermitTabPanel()
                }.createDelegate(this)
            }
        });
        this.stationComboBoxDataStore = new Ext.data.Store({
            proxy: new Ext.data.HttpProxy({
                url: "main/user/getStationList"
            }),
            reader: new Ext.data.JsonReader({
                root: "data",
                fields: [{
                    name: "stationId"
                },
                    {
                        name: "name"
                    }]
            })
        });
        this.stationComboBoxDataStore.load();
        this.stationComboBox = new Ext.form.ComboBox({
            fieldLabel: lang("atStation"),
            name: "stationId",
            store: this.stationComboBoxDataStore,
            hiddenName: "stationId",
            valueField: "stationId",
            displayField: "name",
            mode: "local",
            width: 160,
            allowBlank: false,
            triggerAction: "all",
            forceSelection: true,
            editable: false
        });
        this.workStatusType = new Ext.form.ComboBox({
            fieldLabel: "在职状态",
            width: 160,
            typeAhead: true,
            triggerAction: "all",
            allowBlank: false,
            lazyRender: true,
            mode: "local",
            name: "workStatusId",
            hiddenName: "workStatusId",
            valueField: "workStatusId",
            displayField: "name",
            editable: false,
            store: new Ext.data.SimpleStore({
                fields: ["workStatusId", "name"],
                data: [[1, lang("inJob")], [2, lang("leaveJob")]]
            })
        });
        this.baseField = new Ext.Panel({
            padding: 10,
            items: [{
                xtype: "fieldset",
                title: lang("baseInfo"),
                width: 516,
                layout: "column",
                layoutConfig: {
                    columns: 2
                },
                defaults: {
                    border: false,
                    layout: "form",
                    labelAlign: "right",
                    labelWidth: 60,
                    width: 245
                },
                items: [{
                    items: [{
                        xtype: "textfield",
                        width: 160,
                        name: "trueName",
                        fieldLabel: lang("truename"),
                        inputType: "text",
                        allowBlank: false,
                        maxLength: 25,
                        maxLengthText: lang("maxLengthText") + 25
                    },
                        {
                            xtype: "departmentselectorfield",
                            fieldLabel: lang("inDepartment"),
                            allowBlank: false,
                            width: 160,
                            hiddenName: "deptId",
                            multiSelect: false,
                            listeners: {
                                confirm: function(f, d, e) {
                                    c.reloadJobComboBoxDataStore(d.getItemId(e[0]))
                                }
                            }
                        },
                        this.workStatusType]
                },
                    {
                        items: [this.stationComboBox, this.jobComboBox, {
                            xtype: "hidden",
                            name: "parttimeList"
                        }]
                    }]
            },
                {
                    xtype: "fieldset",
                    title: lang("loginInfo"),
                    id: "CNOA_main_user_baseInfo_Window_loginFieldSet",
                    width: 516,
                    layout: "table",
                    layoutConfig: {
                        columns: 3
                    },
                    defaults: {
                        border: false
                    },
                    items: [{
                        xtype: "checkbox",
                        checked: true,
                        colspan: 3,
                        boxLabel: lang("isSysUser"),
                        name: "isSystemUser",
                        listeners: {
                            check: function(e, d) {
                                if (!d) {
                                    this.formPanel.getForm().findField("username").disable().clearInvalid();
                                    this.formPanel.getForm().findField("password").disable().clearInvalid()
                                } else {
                                    this.formPanel.getForm().findField("username").enable();
                                    this.formPanel.getForm().findField("password").enable()
                                }
                            }.createDelegate(this)
                        }
                    },
                        {
                            layout: "form",
                            labelAlign: "right",
                            width: 245,
                            labelWidth: 60,
                            items: [{
                                xtype: "textfield",
                                name: "username",
                                width: 160,
                                allowBlank: false,
                                fieldLabel: lang("loginUserName"),
                                maxLength: 100,
                                maxLengthText: lang("maxLengthText") + 100
                            }]
                        },
                        {
                            layout: "form",
                            labelAlign: "right",
                            width: 224,
                            labelWidth: 60,
                            items: [{
                                xtype: "textfield",
                                width: 153,
                                name: "password",
                                allowBlank: false,
                                fieldLabel: lang("loginPassWord"),
                                allowBlank: false,
                                maxLength: 20,
                                maxLengthText: lang("maxLengthText") + 20
                            }]
                        },
                        {
                            border: false,
                            html: '<a href="javascript:void(0);" onclick="CNOA_main_user_info.makeCode(6);">生成</a>'
                        }]
                },
                {
                    xtype: "fieldset",
                    title: lang("userOtherInfo"),
                    id: "CNOA_main_user_baseInfo_Window_BaseInfoFieldSet",
                    width: 505,
                    style: "margin-bottom: 15px;",
                    layout: "column",
                    defaults: {
                        xtype: "container",
                        layout: "form",
                        labelWidth: 60
                    },
                    items: [{
                        columnWidth: 0.5,
                        items: [{
                            xtype: "radiogroup",
                            fieldLabel: lang("sex"),
                            width: 100,
                            name: "sex",
                            hiddenName: "sex",
                            items: [{
                                boxLabel: lang("male"),
                                name: "sex",
                                inputValue: 1,
                                checked: true
                            },
                                {
                                    boxLabel: lang("female"),
                                    name: "sex",
                                    inputValue: 2
                                }]
                        },
                            {
                                xtype: "textfield",
                                name: "mobile",
                                width: 160,
                                fieldLabel: lang("mobile")
                            },
                            {
                                xtype: "textfield",
                                name: "qq",
                                width: 160,
                                fieldLabel: "QQ"
                            }]
                    },
                        {
                            columnWidth: 0.5,
                            items: [{
                                xtype: "datefield",
                                fieldLabel: lang("birthday"),
                                name: "birthday",
                                width: 160,
                                format: "Y-m-d",
                                maxLength: 10,
                                maxLengthText: lang("maxLengthText") + 10
                            },
                                {
                                    xtype: "textfield",
                                    name: "workPhone",
                                    width: 160,
                                    fieldLabel: lang("workPhone")
                                },
                                {
                                    xtype: "textfield",
                                    name: "wangwang",
                                    width: 160,
                                    fieldLabel: lang("wangwang")
                                }]
                        },
                        {
                            columnWidth: 1,
                            defaults: {
                                xtype: "textfield",
                                width: 401
                            },
                            items: [{
                                name: "email",
                                vtype: "email",
                                fieldLabel: lang("email")
                            },
                                {
                                    name: "address",
                                    fieldLabel: lang("nowAddress")
                                },
                                {
                                    name: "idCard",
                                    vtype: "idCard",
                                    vtypeText: lang("idCardHaoMaWrong"),
                                    fieldLabel: lang("idCardHaoMa")
                                }]
                        },
                        {
                            columnWidth: 0.5,
                            items: [{
                                xtype: "textfield",
                                name: "partTimeJob",
                                width: 160,
                                fieldLabel: "兼职职位"
                            }]
                        },
                        {
                            columnWidth: 0.5,
                            items: [{
                                xtype: "textfield",
                                name: "qyUsername",
                                width: 160,
                                fieldLabel: "企业账号"
                            }]
                        }]
                },
                {
                    xtype: "fieldset",
                    title: lang("userSpaceSizeSet"),
                    width: 506,
                    defaults: {
                        border: false
                    },
                    labelWidth: 120,
                    labelAlign: "right",
                    items: [{
                        xtype: "compositefield",
                        items: [{
                            xtype: "numberfield",
                            name: "maxsize",
                            fieldLabel: lang("userDiskSpace")
                        },
                            {
                                xtype: "label",
                                text: "(M)"
                            }]
                    },
                        {
                            xtype: "compositefield",
                            layout: "table",
                            items: [{
                                xtype: "numberfield",
                                name: "fssize",
                                fieldLabel: lang("attachSpace")
                            },
                                {
                                    xtype: "label",
                                    text: "(M)"
                                }]
                        },
                        {
                            xtype: "compositefield",
                            layout: "table",
                            items: [{
                                xtype: "numberfield",
                                name: "insidesize",
                                fieldLabel: lang("inMailSpace")
                            },
                                {
                                    xtype: "label",
                                    text: "(M)"
                                }]
                        },
                        {
                            xtype: "displayfield",
                            value: lang("sysSetDefault0"),
                            fieldLabel: lang("description")
                        }]
                },
                {
                    xtype: "fieldset",
                    title: lang("set2") + " " + lang("oaClientSet"),
                    width: 506,
                    defaults: {
                        border: false
                    },
                    labelWidth: 120,
                    labelAlign: "right",
                    items: [{
                        xtype: "checkbox",
                        name: "usemessager",
                        fieldLabel: lang("allowUseClient"),
                        checked: true,
                        boxLabel: lang("allowOrNotAllow"),
                        listeners: {
                            check: function(e, d) {
                                if (d) {
                                    c.formPanel.getForm().findField("usesend").enable();
                                    c.formPanel.getForm().findField("usereceive").enable()
                                } else {
                                    c.formPanel.getForm().findField("usesend").disable();
                                    c.formPanel.getForm().findField("usereceive").disable()
                                }
                            }
                        }
                    },
                        {
                            xtype: "checkbox",
                            name: "usesend",
                            fieldLabel: lang("allowSendFile"),
                            checked: true,
                            boxLabel: lang("allowOrNotAllow")
                        },
                        {
                            xtype: "checkbox",
                            name: "usereceive",
                            fieldLabel: lang("allowReceiveFile"),
                            checked: true,
                            boxLabel: lang("allowOrNotAllow")
                        }]
                }]
        });
        this.partTimeJobComboBoxDataStore = new Ext.data.Store({
            proxy: new Ext.data.HttpProxy({
                url: null
            }),
            reader: new Ext.data.JsonReader({
                root: "data",
                fields: [{
                    name: "partTimeJobId",
                    mapping: "id"
                },
                    {
                        name: "value",
                        mapping: "name"
                    },
                    {
                        name: "jobType"
                    }]
            })
        });
        this.partTimeJobComboBox = new Ext.form.ComboBox({
            fieldLabel: lang("atJob"),
            name: "partTimeJobId",
            store: this.partTimeJobComboBoxDataStore,
            hiddenName: "partTimeJobId",
            valueField: "partTimeJobId",
            displayField: "value",
            mode: "local",
            width: 145,
            triggerAction: "all",
            forceSelection: true,
            editable: false
        });
        this.partTimeGridStore = new Ext.data.Store({
            proxy: new Ext.data.HttpProxy({
                url: null
            }),
            reader: new Ext.data.JsonReader({
                root: "data",
                fields: [{
                    name: "dept",
                    mapping: "dept"
                },
                    {
                        name: "deptId"
                    },
                    {
                        name: "job",
                        mapping: "job"
                    },
                    {
                        name: "jobId"
                    }]
            })
        });
        this.partTimeGridSm = new Ext.grid.CheckboxSelectionModel({
            singleSelect: true
        });
        this.partTimeGrid = new Ext.grid.GridPanel({
            store: this.partTimeGridStore,
            sm: this.partTimeGridSm,
            autoScroll: true,
            border: false,
            height: 175,
            hideBorders: true,
            autoWidth: true,
            loadMask: lang("waiting"),
            bodyStyle: "border-top-width:1px;",
            cm: new Ext.grid.ColumnModel([this.partTimeGridSm, {
                header: lang("department"),
                dataIndex: "dept",
                width: 260,
                sortable: true,
                menuDisabled: true,
                sortable: false
            },
                {
                    header: lang("department"),
                    dataIndex: "deptId",
                    hidden: true
                },
                {
                    header: lang("job"),
                    dataIndex: "job",
                    width: 200,
                    sortable: true,
                    menuDisabled: true,
                    sortable: false
                },
                {
                    header: lang("job"),
                    dataIndex: "jobId",
                    hidden: true
                }]),
            viewConfig: {
                forceFit: true
            }
        });
        this.partTimeField = new Ext.Panel({
            autoHeight: true,
            bodyStyle: "padding:10px 10px 0 10px;",
            items: [{
                xtype: "fieldset",
                title: lang("addPartTime"),
                width: 516,
                layout: "table",
                layoutConfig: {
                    columns: 4
                },
                defaults: {
                    border: false
                },
                items: [{
                    layout: "form",
                    labelAlign: "right",
                    width: 232,
                    labelWidth: 60,
                    items: [{
                        xtype: "departmentselectorfield",
                        width: 160,
                        fieldLabel: lang("inDepartment") + "11",
                        name: "partTimeDeptId"
                    }]
                },
                    {
                        layout: "form",
                        labelAlign: "right",
                        width: 210,
                        labelWidth: 55,
                        items: [this.partTimeJobComboBox]
                    },
                    {
                        xtype: "button",
                        iconCls: "icon-utils-s-add",
                        id: "CNOA_main_user_Window_info_addPartTimeButton",
                        tooltip: lang("add"),
                        handler: function(d, e) {
                            this.addPartTime(d)
                        }.createDelegate(this)
                    },
                    {
                        xtype: "button",
                        style: "margin-left:3px;",
                        iconCls: "icon-utils-s-delete",
                        tooltip: lang("del"),
                        id: "CNOA_main_user_Window_info_deletePartTimeButton",
                        handler: function(d, e) {
                            this.deletePartTime()
                        }.createDelegate(this)
                    }]
            }]
        });
        this.permissionCustomCheckbox = new Ext.form.Checkbox({
            name: "customPermit",
            listeners: {
                check: function(e, d) {
                    if (d == true) {
                        if (this.tabPanelPermitPanelOpendTime != 0) {
                            CNOA.msg.alert(lang("noJobPermitNotice"))
                        }
                        this.permissionEditState.getEl().update(lang("userPermitStatus1"));
                        this.leftClickEvent();
                        this.rightClickEvent();
                        this.enabledAllPermitCheckBox()
                    } else {
                        CNOA.msg.alert(lang("userPermitToJobNotice"));
                        this.permissionEditState.getEl().update(lang("userPermitStatus2"));
                        this.loadPermissionField({
                            nocheck: true
                        })
                    }
                }.createDelegate(this)
            }
        });
        this.permissionEditState = new Ext.BoxComponent({
            autoEl: {
                tag: "div",
                html: lang("userPermitStatus2")
            }
        });
        this.permissionList = new Ext.Panel({
            hideBorders: true,
            border: false,
            width: 467,
            padding: 10,
            height: 358,
            autoScroll: true,
            tbar: [lang("permitList"), "->", (lang("permitName") + ":"), {
                xtype: "search",
                key: "$('span.search_class')",
                searchIcon: false
            }],
            items: []
        });
        this.permitScopeStructTreeRoot = new Ext.tree.AsyncTreeNode({
            id: this.ID_tree_structAreaTreeRoot,
            expanded: true,
            checked: false,
            draggable: false
        });
        this.permitScopeStructTreeLoader = new Ext.tree.TreeLoader({
            dataUrl: "index.php?app=main&func=user&action=list&task=getStructTree",
            preloadChildren: true,
            clearOnLoad: false,
            baseAttrs: {
                uiProvider: Ext.ux.TreeCheckNodeUI
            },
            listeners: {
                load: function(f, e, d) {
                    this.permitScopeStructTree.expandAll()
                }.createDelegate(this)
            }
        });
        this.permitScopeStructTree = new Ext.tree.TreePanel({
            animate: false,
            enableDD: false,
            hideBorders: true,
            border: false,
            containerScroll: true,
            checkModel: "multiple",
            loader: this.permitScopeStructTreeLoader,
            root: this.permitScopeStructTreeRoot,
            rootVisible: false,
            padding: 10,
            listeners: {
                checkchange: this.permitScopeStructTreeCheckChange
            }
        });
        this.permitScopeStructPanel = new Ext.Panel({
            hideBorders: true,
            border: false,
            width: 265,
            layout: "fit",
            height: 398,
            autoScroll: true,
            tbar: new Ext.Toolbar({
                items: [lang("permitArea")]
            }),
            items: [new Ext.Panel({
                id: this.ID_tabPermitNoAreaNotice,
                hidden: true,
                width: 100,
                html: lang("noNeedSetPermitArea")
            }), new Ext.Panel({
                autoScroll: true,
                style: "border-left:1px dashed #8DB2E3;",
                items: this.permitScopeStructTree
            })]
        });
        this.tabPanel = new Ext.TabPanel({
            activeTab: 0,
            height: Ext.isIE6 ? 423 : 431,
            width: 536,
            defaults: {
                hideBorders: true,
                border: false
            },
            items: [{
                title: lang("baseInfo"),
                id: this.ID_tab_baseInfo,
                items: [this.baseField],
                autoScroll: true,
                listeners: {
                    activate: function() {
                        if (this.tabPanelLoaded == true) {
                            tp_h = Ext.isIE6 ? 423 : 431;
                            this.tabPanel.setHeight(tp_h);
                            this.tabPanel.setWidth(536);
                            this.mainPanel.setHeight(520);
                            this.mainPanel.setWidth(550)
                        }
                        this.tabPanelLoaded = true
                    }.createDelegate(this)
                }
            },
                {
                    title: lang("userPermit"),
                    id: this.ID_tab_permission,
                    layout: "anchor",
                    disabled: true,
                    items: [{
                        xtype: "panel",
                        height: 0,
                        width: 735,
                        tbar: [lang("isUseCusPermit"), this.permissionCustomCheckbox, "->", this.permissionEditState]
                    },
                        {
                            xtype: "panel",
                            height: 400,
                            layout: "table",
                            layoutConfig: {
                                columns: 2
                            },
                            items: [this.permissionList, this.permitScopeStructPanel]
                        }],
                    listeners: {
                        activate: function() {
                            tp_h = Ext.isIE6 ? 449 : 458;
                            this.tabPanel.setHeight(tp_h);
                            this.tabPanel.setWidth(734);
                            this.mainPanel.setHeight(525);
                            this.mainPanel.setWidth(748);
                            if (this.tabPanelPermitPanelLoaed == false) {
                                this.tabPanelPermitPanelLoaed = true;
                                this.loadPermissionField({
                                    nocheck: false
                                })
                            }
                        }.createDelegate(this)
                    }
                }]
        });
        this.formPanel = new Ext.form.FormPanel({
            autoWidth: true,
            border: false,
            hideBorders: true,
            labelWidth: 70,
            labelAlign: "right",
            waitMsgTarget: true,
            items: [this.tabPanel]
        });
        this.mainPanel = new Ext.Window({
            title: this.title,
            resizable: false,
            modal: true,
            width: 550,
            height: makeWindowHeight(500),
            items: [this.formPanel],
            buttonAlign: "right",
            keys: [{
                key: Ext.EventObject.ENTER,
                fn: function() {
                    this.submitForm({
                        close: true
                    })
                }.createDelegate(this),
                scope: this
            }],
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
                }]
        })
    },
    show: function() {
        this.mainPanel.show(null,
            function() {
                if (this.action == "add") {
                    this.tabPanel.remove(this.ID_tab_permission, true)
                }
            }.createDelegate(this))
    },
    close: function() {
        this.mainPanel.close()
    },
    disablePartTimeTabPanel: function() {
        try {
            Ext.getCmp(this.ID_tab_partTime).disable()
        } catch(a) {}
    },
    enablePartTimeTabPanel: function() {
        try {
            Ext.getCmp(this.ID_tab_partTime).enable()
        } catch(a) {}
    },
    disablePermitTabPanel: function() {
        try {
            Ext.getCmp(this.ID_tab_permission).disable()
        } catch(a) {}
    },
    enablePermitTabPanel: function() {
        try {
            Ext.getCmp(this.ID_tab_permission).enable()
        } catch(a) {}
    },
    reloadJobComboBoxDataStore: function(a) {
        this.jobComboBox.clearValue();
        this.jobComboBoxDataStore.removeAll();
        this.jobComboBoxDataStore.proxy = new Ext.data.HttpProxy({
            //url: "index.php?app=main&func=user&action=" + this.action + "&task=getJobListByDeptId&deptId=" + a
            url: "main/user/getJobListByDeptId?deptId=" + a
        });
        this.jobComboBoxDataStore.load()
    },
    reloadPartTimeJobComboBoxDataStore: function(a) {
        this.partTimeJobComboBox.clearValue();
        this.partTimeJobComboBoxDataStore.removeAll();
        this.partTimeJobComboBoxDataStore.proxy = new Ext.data.HttpProxy({
            url: "index.php?app=main&func=user&action=" + this.action + "&task=getJobListByDeptId&deptId=" + a + "&uid=" + this.edit_uid
        });
        this.partTimeJobComboBoxDataStore.load()
    },
    submitForm: function(b) {
        this.getSavedPermitScopeData();
        var e = "no";
        if (this.tabPanelPermitPanelLoaed) {
            e = "yes"
        }
        var c = this.getPartTimeData();
        c = c === false ? "noEditParTime": c;
        if (this.formPanel.getForm().isValid()) {
            this.formPanel.getForm().submit({
                url: this.baseUrl + this.tp,
                waitMsg: lang("waiting"),
                params: {
                    task: this.action + "Info",
                    uid: this.edit_uid,
                    partTimeData: c,
                    isClickUserPermit: e
                },
                method: "POST",
                success: function(f, g) {
                    if (b.close) {
                        this.close()
                    } else {
                        this.tabPanelPermitPanelLoaed = false;
                        if (this.jobType == "user") {
                            this.enablePermitTabPanel()
                        }
                        f.findField("parttimeList").setValue(c)
                    }
                    this.parent.reloadUser()
                }.createDelegate(this),
                failure: function(f, g) {
                    CNOA.msg.alert(g.result.msg)
                }.createDelegate(this)
            })
        } else {
            var d = b.close ? this.ID_btn_save: this.ID_btn_apply;
            var a = Ext.getCmp(d).getEl().getBox();
            a = [a.x + 35, a.y + 26];
            CNOA.miniMsg.alert(lang("formValid"), a)
        }
    },
    makeCode: function(a) {
        var c = "";
        for (var b = 0; b < a; b++) {
            var d = Math.floor(Math.random() * 10);
            c += d
        }
        this.formPanel.getForm().findField("password").setValue(c)
    },
    loadFormData: function(a) {
        var b = this;
        this.formPanel.getForm().load({
            url: "index.php?app=main&func=user&action=edit",
            params: {
                uid: a,
                task: "loadDataInfo"
            },
            method: "POST",
            waitTitle: lang("notice"),
            success: function(d, f) {
                var c = 0;
                this.edit_uid = a;
                this.reloadJobComboBoxDataStore(f.result.data.deptId);
                this.jobComboBoxDataStore.on("load",
                    function() {
                        if (c == 0) {
                            this.jobComboBox.setValue(f.result.data.jobId);
                            c++
                        }
                    }.createDelegate(this));
                if (f.result.data.isSystemUser == "on") {
                    var e = this.formPanel.getForm().findField("password");
                    e.allowBlank = true;
                    e.validationEvent = false;
                    e.clearInvalid();
                    new Ext.ToolTip({
                        target: e.getId(),
                        html: lang("editUserPasswordTip"),
                        showDelay: 0,
                        trackMouse: true
                    })
                }
                this.jobType = f.result.data.jobType;
                if ((this.jobType != "user")) {
                    this.disablePermitTabPanel()
                } else {
                    this.enablePermitTabPanel()
                }
            }.createDelegate(this),
            failure: function(c, d) {
                CNOA.msg.alert(d.result.msg,
                    function() {
                        b.close()
                    })
            }.createDelegate(this)
        })
    },
    loadPartTimeGridData: function() {
        var b = new Array();
        var a = function(c) {
            this.partTimeListArray = c
        }.createDelegate(this);
        if (!this.partTimeGridStoreLoaded) {
            this.partTimeGridStore.proxy = new Ext.data.HttpProxy({
                url: "index.php?app=main&func=user&action=edit&task=getPartTimeList&uid=" + this.edit_uid
            });
            this.partTimeGridStore.load();
            this.partTimeGridStore.on("load",
                function() {
                    this.partTimeGridStoreLoaded = true
                }.createDelegate(this))
        }
    },
    addPartTime: function(e) {
        var g = this.formPanel.getForm().findField("partTimeDeptId").getValue();
        var f = this.formPanel.getForm().findField("partTimeJobId").getValue();
        if (g == "") {
            var a = this.formPanel.getForm().findField("partTimeDeptName").getEl().getBox();
            a = [a.x + 12, a.y + 26];
            CNOA.miniMsg.alert("请选择兼职部门", a);
            return
        }
        if (f == "") {
            var a = this.formPanel.getForm().findField("partTimeJobId").getEl().getBox();
            a = [a.x + 12, a.y + 26];
            CNOA.miniMsg.alert("请选择兼职职位", a);
            return
        }
        var d = this.partTimeJobComboBox.getExtValue("jobType");
        if ((d == "admin") || (d == "superAdmin")) {
            var a = this.formPanel.getForm().findField("partTimeJobId").getEl().getBox();
            a = [a.x + 12, a.y + 26];
            CNOA.miniMsg.alert("系统管理职位不能被兼职", a);
            return
        }
        var c = this.formPanel.getForm().findField("partTimeDeptName").getValue();
        var h = this.formPanel.getForm().findField("partTimeJobId").getEl().dom.value;
        var b = false;
        this.partTimeGridStore.each(function(k, j, l) {
            if ((k.data.deptId == g) && (k.data.jobId == f)) {
                b = true
            }
        });
        if (b) {
            var a = e.getBox();
            a = [a.x + 12, a.y + 26];
            CNOA.miniMsg.alert("已经添加了该职位", a)
        } else {
            this.partTimeGridStore.add(new Ext.data.Record({
                dept: c,
                deptId: g,
                job: h,
                jobId: f
            }))
        }
        b = false;
        this.disablePermitTabPanel()
    },
    deletePartTime: function(c) {
        var a = Ext.getCmp("CNOA_main_user_Window_info_deletePartTimeButton").getEl().getBox();
        a = [a.x + 12, a.y + 26];
        var b = this.partTimeGrid.getSelectionModel().getSelections();
        if (b.length == 0) {
            CNOA.miniMsg.alert(lang("mustSelectOneRow"), a)
        } else {
            CNOA.miniMsg.show({
                msg: lang("confirmToDelete"),
                xy: a,
                modal: false,
                fn: function(d) {
                    if (d == "confirm") {
                        this.partTimeGrid.store.remove(b[0]);
                        this.disablePermitTabPanel()
                    }
                }.createDelegate(this)
            })
        }
    },
    getPartTimeData: function() {
        if (!this.partTimeGridStoreLoaded) {
            return false
        }
        var a = new Array();
        this.partTimeGridStore.each(function(e, d, f) {
            a.push(e.data.jobId)
        });
        return a.join(",")
    },
    permitScopeStructTreeCheckChange: function(b, a) {
        b.expand();
        b.attributes.checked = a;
        if (CNOA_main_user_info_add_edit_tabPermit_ifCascade) {
            b.eachChild(function(c) {
                c.ui.toggleCheck(a);
                c.attributes.checked = a;
                c.fireEvent("checkchange", c, a)
            })
        }
    },
    loadPermissionField: function(a) {
        Ext.Ajax.request({
            url: "index.php?app=main&func=user&action=" + this.action,
            method: "POST",
            params: {
                task: "getPermitField",
                uid: this.edit_uid,
                nocheck: a.nocheck
            },
            success: function(c) {
                var b = Ext.decode(c.responseText);
                if (a.nocheck == true) {
                    b.customPermit = false
                }
                this.makePermissionField(b)
            }.createDelegate(this)
        })
    },
    makePermissionField: function(a) {
        var c = this;
        var b = new Ext.XTemplate('<table width="100%" id="permitMenuList">', '<tpl for=".">', "<tr>", '<td width="30%" style="margin-top; padding-bottom:2px;border-bottom: 1px dashed #D7D7D7;">', '<img src="./resources/images/cnoa/li-linepoint.gif" />', '<input type="checkbox" id="CNOA_USER_JOB_PERMIT_selectAll_{id}"  style="margin-right:7px">', '<span style="font-weight:bold;margin-top: 5px;">{name}<span>', "</td>", '<td style="border-bottom: 1px dashed #D7D7D7;" width="70%"><ul id="CNOA_USER_JOB_PERMIT_A_A_PANEL_{id}">', '<tpl for="action_list">', '<li class="x-form-check-wrap-border" style="margin:5px 0;">', '<input permitId="{id}" type="checkbox" name="showPermitBox_{id}" areaType="{permittype}" permitFor="{permitfor}" class=" x-form-checkbox x-form-field"/>', "<tpl if=\"values.permittype=='area'\">", '<input type="hidden" name="hiddenPermitBox_{id}"/>', "</tpl>", '<span ext:qtip="{about}" class="search_class" style="margin-left:4px;cursor:default;">{name}[{[values.permittype == "area" ? "<span class=cnoa_color_blue>' + lang("haveArea") + '</span>" : "<span class=cnoa_color_gray>' + lang("noArea") + '</span>"]}]</span>', "</li>", "</tpl>", "</ul></td>", "</tr>", "</tpl>", "</table>");
        b.overwrite(this.permissionList.body, a.data);
        this.permitMenuList = $("#permitMenuList");
        this.permitObj = {};
        $.each(a.data,
            function(d, e) {
                $.each(e.action_list,
                    function(f, g) {
                        if (g.permitData != null) {
                            c.permitObj[g.id] = g.permitData
                        }
                    })
            });
        if (a.customPermit) {
            this.permissionCustomCheckbox.setValue(true);
            this.leftClickEvent();
            this.rightClickEvent();
            this.enabledAllPermitCheckBox()
        } else {
            this.disabledAllPermitCheckBox()
        }
        this.selectSelfPermit();
        this.tabPanelPermitPanelOpendTime++
    },
    isSelfPermit: function(a) {
        if (a) {
            this.permissionCustomCheckbox.setValue(true);
            this.leftClickEvent();
            this.rightClickEvent();
            this.enabledAllPermitCheckBox()
        } else {
            this.disabledAllPermitCheckBox()
        }
    },
    selectSelfPermit: function() {
        var b = this;
        var a = this.permitMenuList.find("input[name]");
        $.each(a,
            function(d, g) {
                var c = $(this).attr("name");
                var f = parseInt(c.match(/\d+/g)[0]);
                if (b.permitObj[f] != undefined) {
                    if (c.search(/^showPermitBox_/) != -1) {
                        g.checked = true;
                        var e = $(g).parent();
                        e.addClass("x-form-check-wrap-selected");
                        e.click(function() {
                            b.permitBoxClick($(g))
                        })
                    } else {
                        if (c.search(/^hiddenPermitBox_/) != -1) {
                            $(g).val(b.permitObj[f])
                        }
                    }
                } else {
                    $(g).parent().removeClass("x-form-check-wrap-selected x-form-check-wrap-active")
                }
            })
    },
    leftClickEvent: function() {
        var a = this;
        this.permitMenuList.find("input[id^=CNOA_USER_JOB_PERMIT_selectAll_]").each(function() {
            $(this).click(function() {
                var b = $(this).parent().next().find("input[name^=showPermitBox_]");
                var c = this.checked;
                b.each(function() {
                    this.checked = c;
                    if (c === true) {
                        var d = this;
                        $(this).parent().addClass("x-form-check-wrap-selected");
                        $(this).parent().click(function() {
                            a.permitBoxClick(d)
                        })
                    } else {
                        $(this).parent().removeClass("x-form-check-wrap-selected x-form-check-wrap-active");
                        a.permitScopeStructTree.hide();
                        $(this).parent().unbind("click")
                    }
                })
            })
        })
    },
    rightClickEvent: function() {
        var a = this;
        this.permitMenuList.find("input[name^=showPermitBox_]").each(function() {
            $(this).click(function() {
                if (this.checked === true) {
                    var b = this;
                    $(this).parent().click(function() {
                        a.permitBoxClick(b)
                    })
                } else {
                    $(this).parent().removeClass("x-form-check-wrap-selected x-form-check-wrap-active");
                    a.permitScopeStructTree.hide();
                    $(this).parent().unbind("click")
                }
            })
        })
    },
    permitBoxClick: function(a) {
        var c = this;
        if ((this.permitNowId != null)) {
            this.storeOldPermitScopeAreaList()
        }
        this.permitMenuList.find("li").removeClass("x-form-check-wrap-active");
        $(a).parent().addClass("x-form-check-wrap-selected x-form-check-wrap-active");
        if ($(a).attr("areaType") == "area") {
            Ext.getCmp(this.ID_tabPermitNoAreaNotice).hide();
            this.permitScopeStructTree.show();
            this.permitNowId = $(a).attr("permitId");
            this.loadPermission(this.permitNowId);
            this.enableAllPermitScopeTree(this.permitScopeStructTree.getRootNode().firstChild);
            var b = parseInt($(a).attr("permitFor"));
            if (b != 0) {
                this.disablePermitScopeForMaster(b)
            }
        } else {
            Ext.getCmp(this.ID_tabPermitNoAreaNotice).show();
            this.permitScopeStructTree.hide()
        }
        if (this.jobType == "admin") {
            this.permitScopeStructTree.hide()
        }
    },
    enabledAllPermitCheckBox: function() {
        this.permitMenuList.find("input[type=checkbox]").attr("disabled", false);
        this.permitScopeStructTree.enable()
    },
    disabledAllPermitCheckBox: function() {
        this.permitMenuList.find("input[type=checkbox]").attr("disabled", true);
        this.permitScopeStructTree.disable()
    },
    loadPermission: function(b) {
        var a = this.permitMenuList.find("input[name=hiddenPermitBox_" + b + "]").val();
        this.setPermitScopeStructTreeCheckNode(a.split(","))
    },
    setPermitScopeStructTreeCheckNode: function(b) {
        this.clearPermitScopeStructTreeCheckNode();
        CNOA_main_user_info_add_edit_tabPermit_ifCascade = false;
        if ((b == "")) {
            CNOA_main_user_info_add_edit_tabPermit_ifCascade = true;
            return
        }
        if ((b.length == 1) && (b == "0")) {
            this.permitScopeStructTree.getNodeById(this.ID_tree_structAreaTreeRoot).ui.toggleCheck(true);
            CNOA_main_user_info_add_edit_tabPermit_ifCascade = true;
            return
        } else {
            if (b.length == 1) {
                this.permitScopeStructTree.getNodeById("CNOA_main_struct_list_tree_node_" + b).ui.toggleCheck(true)
            } else {
                for (var a = 0; a < b.length; a++) {
                    if (b[a] == "0") {
                        this.permitScopeStructTree.getNodeById(this.ID_tree_structAreaTreeRoot).ui.toggleCheck(true)
                    } else {
                        try {
                            this.permitScopeStructTree.getNodeById("CNOA_main_struct_list_tree_node_" + b[a]).ui.toggleCheck(true)
                        } catch(c) {}
                    }
                }
            }
        }
        CNOA_main_user_info_add_edit_tabPermit_ifCascade = true
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
var CNOA_main_user_listClass, CNOA_main_user_list, CNOA_main_user_baseInfo, CNOA_main_user_infoClass, CNOA_main_user_info;
CNOA_main_user_listClass = CNOA.Class.create();
CNOA_main_user_listClass.prototype = {
    init: function() {
        var a = this;
        this.baseUrl = "main/user/list";
        this.tip_noPermission = lang("noPermitToUseFeature");
        this.searchParams = {
            widthSon: true,
            deptId: 0,
            trueName: "",
            username: "",
            mobile: "",
            isSystemUser: "",
            workStatusType: "",
            stationId: "",
            atjod: ""
        };
        this.deptTree = this.createDeptTreePanel();
        this.searchToolbar = this.createSearchToolbar();
        this.userGridPanel = this.createUserGridPanel();
        this.store = this.userGridPanel.store;
        this.mainPanel = new Ext.Panel({
            collapsible: false,
            hideBorders: true,
            border: false,
            layout: "border",
            autoScroll: true,
            items: [this.userGridPanel, this.deptTree],
            listeners: {
                beforedestroy: function() {
                    a.searchToolbar.destroy();
                    if (a.orderWindow) {
                        a.orderWindow.destroy()
                    }
                }
            }
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
            dataUrl: "main/struct/getStructTree",
            tbar: new Ext.Toolbar({
                style: "border-right-width:1px;",
                items: ["->", {
                    text: lang("refresh"),
                    tooltip: lang("refresh"),
                    iconCls: "icon-system-refresh",
                    handler: function() {
                        a.getRootNode().reload()
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
                            b.reloadUser({
                                widthSon: c
                            })
                        }
                    }
                }]
            }),
            listeners: {
                render: function() {
                    Ext.state.Manager.set("CNOA_main_user_index_treeState", "")
                },
                dataloaded: function(f) {
                    var d = f.toggleButton.pressed;
                    var e = f.getRootNode();
                    e.expandChildNodes(d);
                    e.firstChild.select(true);
                    b.searchParams.deptId = e.firstChild.attributes.deptId;
                    var c = Ext.state.Manager.get("CNOA_main_user_index_treeState");
                    if (c) {
                        b.deptTree.expandPath(c)
                    }
                },
                click: function(c) {
                    if (c.disabled) {
                        return
                    }
                    b.reloadUser({
                        deptId: c.attributes.selfid
                    });
                    Ext.state.Manager.set("CNOA_main_user_index_treeState", c.getPath())
                }
            }
        });
        return a
    },
    createUserGridPanel: function() {
        var g = this;
        var a = [{
            name: "uid",
            mapping: "uid"
        },
            {
                name: "bianhao",
                mapping: "bianhao"
            },
            {
                name: "trueName",
                mapping: "trueName"
            },
            {
                name: "username",
                mapping: "username"
            },
            {
                name: "workStatusType",
                mapping: "workStatusType"
            },
            {
                name: "sex",
                mapping: "sex"
            },
            {
                name: "jobId",
                mapping: "jobId"
            },
            {
                name: "partTimeJob",
                mapping: "partTimeJob"
            },
            {
                name: "station",
                mapping: "station"
            },
            {
                name: "deptId",
                mapping: "deptId"
            }];
        var b = new Ext.data.Store({
            autoLoad: true,
            baseParams: this.searchParams,
            proxy: new Ext.data.HttpProxy({
                url: "main/user/getUsersJsonData?type=1"
            }),
            reader: new Ext.data.JsonReader({
                totalProperty: "total",
                root: "data",
                fields: a
            })
        });
        var f = new Ext.grid.CheckboxSelectionModel();
        var e = function(h) {
            return '<a class="gridview4" action="edit" uid="' + h + '" >' + lang("modify") + "</a>"
        };
        var c = new Ext.grid.ColumnModel({
            defaults: {
                sortable: true
            },
            columns: [new Ext.grid.RowNumberer(), f, {
                header: "UID",
                dataIndex: "uid",
                width: 20,
                hidden: true
            },
                {
                    header: lang("truename"),
                    dataIndex: "trueName",
                    id: "trueName",
                    width: 66
                },
                {
                    header: lang("loginUserName"),
                    dataIndex: "username",
                    width: 100,
                    sortable: false,
                    menuDisabled: true
                },
                {
                    header: lang("jobStatus"),
                    dataIndex: "workStatusType",
                    width: 120
                },
                {
                    header: lang("sex"),
                    dataIndex: "sex",
                    width: 50
                },
                {
                    header: lang("department"),
                    dataIndex: "deptId",
                    width: 120
                },
                {
                    header: lang("station"),
                    dataIndex: "station",
                    width: 120
                },
                {
                    header: lang("job"),
                    dataIndex: "jobId",
                    width: 120
                },
                {
                    header: "兼职职位",
                    dataIndex: "partTimeJob",
                    width: 200,
                    renderer: function(j, k, h) {
                        Ext.QuickTips.init();
                        k.attr = ' ext:qtip="' + j + '"';
                        if (j.length > 30) {
                            k.attr += " ext:qwidth=300"
                        }
                        return j
                    }
                },
                {
                    header: lang("opt"),
                    dataIndex: "uid",
                    width: 80,
                    renderer: e,
                    hidden: !CNOA.permitController.main_user.edit
                },
                {
                    header: "",
                    dataIndex: "noIndex",
                    width: 1,
                    menuDisabled: true,
                    resizable: false
                }]
        });
        var d = new Ext.grid.PageGridPanel({
            region: "center",
            stripeRows: true,
            hideBorders: true,
            autoExpandColumn: "trueName",
            sm: f,
            cm: c,
            store: b,
            bodyStyle: "border-left-width:1px;",
            pagingCfg: {
                style: "border-left-width:1px;"
            },
            tbar: new Ext.Toolbar({
                style: "border-left-width:1px;",
                items: [{
                    text: lang("refresh"),
                    tooltip: lang("refresh"),
                    iconCls: "icon-system-refresh",
                    handler: function() {
                        g.reloadUser()
                    }
                },
                    {
                        text: lang("add"),
                        tooltip: CNOA.permitController.main_user.add ? lang("add") : this.tip_noPermission,
                        disabled: !CNOA.permitController.main_user.add,
                        iconCls: "icon-utils-s-add",
                        handler: function() {
                            CNOA_main_user_info = new CNOA_main_user_infoClass(g, "add");
                            CNOA_main_user_info.show()
                        }
                    },
                    {
                        text: lang("modify"),
                        tooltip: CNOA.permitController.main_user.edit ? lang("modify") : this.tip_noPermission,
                        disabled: !CNOA.permitController.main_user.edit,
                        iconCls: "icon-utils-s-edit",
                        handler: function(j) {
                            var k = d.getSelectionModel().getSelections();
                            if (k.length == 0) {
                                var h = j.getEl().getBox();
                                CNOA.miniMsg.alert(lang("mustSelectOneRow"), [h.x + 12, h.y + 26])
                            } else {
                                g.showEditUserWindow(k[0].get("uid"))
                            }
                        }
                    },
                    {
                        text: lang("del"),
                        tooltip: CNOA.permitController.main_user["delete"] ? lang("del") : this.tip_noPermission,
                        disabled: !CNOA.permitController.main_user["delete"],
                        iconCls: "icon-utils-s-delete",
                        handler: function(j) {
                            var k = d.getSelectionModel().getSelections();
                            if (k.length == 0) {
                                var h = j.getEl().getBox();
                                CNOA.miniMsg.alert(lang("mustSelectOneRow"), [h.x + 12, h.y + 26])
                            } else {
                                CNOA.msg.cf(lang("confirmToDelete"),
                                    function(n) {
                                        if (n !== "yes") {
                                            return
                                        }
                                        var l = false,
                                            o = "";
                                        for (var m = 0; m < k.length; m++) {
                                            if (k[m].get("uid") == 1) {
                                                l = true
                                            }
                                            o += k[m].get("uid") + ","
                                        }
                                        if (l) {
                                            CNOA.msg.alert(lang("deleteForDataHaveSuperUser"),
                                                function() {
                                                    CNOA_main_user_list.deleteRecord(o)
                                                })
                                        } else {
                                            CNOA_main_user_list.deleteRecord(o)
                                        }
                                    })
                            }
                        }
                    },
                    {
                        text: lang("order"),
                        tooltip: lang("order"),
                        iconCls: "icon-order",
                        cls: "btn-yellow1",
                        handler: function(h, j) {
                            g.showOrderWin()
                        }
                    },
                    {
                        text: lang("userPermitList"),
                        cls: "btn-blue3",
                        iconCls: "icon-channel-loginSet",
                        handler: function(h, j) {
                            g.seePermitList()
                        }
                    },
                    {
                        text: lang("exportUserPermitList"),
                        cls: "btn-blue3",
                        iconCls: "icon-excel",
                        handler: function() {
                            g.exportPermitList()
                        }
                    },
                    {
                        text: "导出用户列表",
                        cls: "btn-blue3",
                        iconCls: "icon-excel",
                        handler: function() {
                            g.exportUserList()
                        }
                    },
                    (CNOA.permitController.main_user.edit ? "<span style='color:#999'>" + lang("dblclickToEdit") + "</span>": "")]
            }),
            listeners: {
                render: function(h) {
                    g.searchToolbar.render(h.tbar, 0)
                },
                rowclick: function(h, k, j) {
                    if (j.target.getAttribute("action") === "edit") {
                        g.showEditUserWindow(j.target.getAttribute("uid"))
                    }
                },
                rowdblclick: function(h, j) {
                    g.showEditUserWindow(h.store.getAt(j).get("uid"))
                },
                rowcontextmenu: function(h, k, j) {
                    j.stopEvent();
                    if (!h.getSelectionModel().isSelected(k)) {
                        h.getSelectionModel().selectRow(k)
                    }
                }
            }
        });
        return d
    },
    createSearchToolbar: function() {
        var a = this;
        return new CNOA.toolbar.SearchToolbar({
            style: "border-left-width:1px;",
            items: [lang("truename"), {
                xtype: "textfield",
                width: 80,
                name: "trueName"
            },
                lang("loginUserName"), {
                    xtype: "textfield",
                    width: 80,
                    name: "username"
                },
                lang("phoneNumber"), {
                    xtype: "textfield",
                    width: 80,
                    name: "mobile"
                },
                lang("onOffPerson"), {
                    xtype: "combo",
                    width: 80,
                    typeAhead: true,
                    triggerAction: "all",
                    lazyRender: true,
                    mode: "local",
                    editable: false,
                    valueField: "id",
                    displayField: "name",
                    name: "workStatusType",
                    store: new Ext.data.SimpleStore({
                        fields: ["id", "name"],
                        data: [[1, lang("inJob")], [2, lang("leaveJob")]]
                    })
                },
                "是/不是" + lang("sysUser"), {
                    xtype: "combo",
                    width: 80,
                    typeAhead: true,
                    triggerAction: "all",
                    lazyRender: true,
                    mode: "local",
                    editable: false,
                    valueField: "id",
                    displayField: "name",
                    name: "isSystemUser",
                    store: new Ext.data.SimpleStore({
                        fields: ["id", "name"],
                        data: [[1, "是"], [2, "不是"]]
                    })
                },
                lang("job"), {
                    xtype: "jobselectorfield",
                    width: 80,
                    hiddenName: "atjod",
                    multiSelect: false
                },
                lang("station"), {
                    xtype: "stationselectorfield",
                    width: 80,
                    hiddenName: "stationId",
                    multiSelect: false
                }],
            listeners: {
                search: function(c, b) {
                    a.reloadUser(b)
                },
                clearsearch: function(b) {
                    a.reloadUser({
                        trueName: "",
                        username: "",
                        mobile: "",
                        isSystemUser: "",
                        workStatusType: "",
                        stationId: "",
                        atjod: ""
                    })
                }
            }
        })
    },
    createOrderWindow: function() {
        var g = this;
        var a = [{
            name: "uid",
            mapping: "uid"
        },
            {
                name: "trueName",
                mapping: "trueName"
            },
            {
                name: "username",
                mapping: "username"
            },
            {
                name: "sex",
                mapping: "sex"
            },
            {
                name: "jobId",
                mapping: "jobId"
            },
            {
                name: "partTimeJob",
                mapping: "partTimeJob"
            },
            {
                name: "deptId",
                mapping: "deptId"
            }];
        var c = new Ext.data.Store({
            proxy: new Ext.data.HttpProxy({
                url: "index.php?app=main&func=user&action=list&task=getJsonData&type=2"
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
                header: "UID",
                dataIndex: "uid",
                width: 20,
                hidden: true
            },
                {
                    header: lang("truename"),
                    dataIndex: "trueName",
                    id: "trueName",
                    width: 66
                },
                {
                    header: lang("loginUserName"),
                    dataIndex: "username",
                    width: 100
                },
                {
                    header: lang("sex"),
                    dataIndex: "sex",
                    width: 50
                },
                {
                    header: lang("department"),
                    dataIndex: "deptId",
                    width: 140
                },
                {
                    header: lang("job"),
                    dataIndex: "jobId",
                    width: 140
                },
                {
                    header: "兼职职位",
                    dataIndex: "partTimeJob",
                    width: 140
                },
                {
                    header: "",
                    dataIndex: "noIndex",
                    width: 1,
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
                        notifyDrop: function(k, p, o) {
                            var n = o.selections;
                            var l = k.getDragData(p).rowIndex;
                            if (typeof(l) == "undefined") {
                                return
                            }
                            for (i = 0; i < n.length; i++) {
                                var m = n[i];
                                if (!this.copy) {
                                    c.remove(m)
                                }
                                if (l == 0) {
                                    m.data.orderNum -= 1
                                } else {
                                    if (l == c.data.items.length) {
                                        m.data.id = c.data.items[l - 1].data.id + 1
                                    } else {
                                        m.data.id = (c.data.items[l - 1].data.id + c.data.items[l].data.id) / 2
                                    }
                                }
                                c.insert(l, m)
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
                    h.push(j.data.uid)
                });
            Ext.Ajax.request({
                method: "POST",
                url: "index.php?app=main&func=user&action=list&task=orderSubmitForm",
                params: {
                    order: h.join(",")
                },
                success: function(l, k) {
                    var j = Ext.decode(l.responseText);
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
            width: 700,
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
                cls: "btn-blue4",
                handler: function() {
                    e()
                }
            },
                {
                    text: lang("close"),
                    cls: "btn-red1",
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
    seePermitList: function() {
        x = (screen.availWidth - 850) / 2;
        y = (screen.availHeight - 600) / 2;
        window.open(this.baseUrl + "&task=PermitList&opt=see", "seePermitList", "width=900,height=500,left=" + x + ",top=" + y + ",scrollbars=yes,resizable=yes,status=no")
    },
    exportPermitList: function() {
        Ext.Ajax.request({
            url: this.baseUrl + "&task=PermitList&opt=export",
            method: "POST",
            success: function(a, b) {
                ajaxDownload(a.responseText)
            }
        })
    },
    exportUserList: function() {
        var a = this;
        Ext.Ajax.request({
            url: this.baseUrl + "&task=UserList&opt=export",
            method: "POST",
            params: a.searchParams,
            success: function(b, c) {
                ajaxDownload(b.responseText)
            }
        })
    },
    showOrderWin: function() {
        var a = this.deptTree.getSelectionModel().getSelectedNode();
        if (!this.orderWindow) {
            this.orderWindow = this.createOrderWindow()
        }
        this.orderWindow.getStore().load({
            params: {
                deptId: a.attributes.selfid,
                widthSon: "false"
            }
        });
        this.orderWindow.setTitle(lang("userOrder") + " - " + lang("department") + ": " + a.text);
        this.orderWindow.show()
    },
    showEditUserWindow: function(a) {
        if (!CNOA.permitController.main_user.edit) {
            return
        }
        CNOA_main_user_info = new CNOA_main_user_infoClass(this, "edit");
        CNOA_main_user_info.show();
        CNOA_main_user_info.loadFormData(a)
    },
    deleteRecord: function(a) {
        var b = this;
        Ext.Ajax.request({
            url: "index.php?app=main&func=user&action=delete",
            method: "POST",
            params: {
                ids: a
            },
            success: function(d) {
                var c = Ext.decode(d.responseText);
                if (c.success === true) {
                    CNOA.msg.notice2(c.msg);
                    b.reloadUser()
                } else {
                    CNOA.msg.alert(c.msg)
                }
            }
        })
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
    }
};
var sm_main_user = 1;
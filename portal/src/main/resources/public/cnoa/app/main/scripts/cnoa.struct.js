var CNOA_main_user_deptClass;
CNOA_main_user_deptClass = CNOA.Class.create();
CNOA_main_user_deptClass.prototype = {
    init: function(a) {
        this.baseUrl = "main/struct";
        this.permitController = CNOA.permitController.main_struct;
        this.rootNodeId = 1;
        this.currentDeptId = 0;
        this.action = a;
        this.initDepartmentPanel();
        this.initEditPanel();
        this.mainPanel = new Ext.Panel({
            title: lang("structMgr"),
            layout: "border",
            border: false,
            hideBorders: true,
            items: [this.deptTree, this.formPanel]
        })
    },
    initDepartmentPanel: function() {
        var a = this;
        this.deptTree = new CNOA.selector.DepartmentPanel({
            region: "west",
            split: true,
            width: 250,
            minWidth: 200,
            maxWidth: 380,
            bodyStyle: "border-right-width:1px;",
            dataUrl: this.baseUrl + "/getStructTree",
            listeners: {
                dataloaded: function(c) {
                    var b = c.toggleButton.pressed;
                    c.getRootNode().expandChildNodes(b)
                },
                click: function(b) {
                    if (a.currentDeptId == b.attributes.selfid) {
                        return
                    }
                    a.currentDeptId = parseInt(b.attributes.selfid) || 0;
                    if (a.permitController.edit) {
                        a.action = "edit";
                        a.updateView();
                        a.loadFormData(a.currentDeptId)
                    }
                }
            },
            tbar: new Ext.Toolbar({
                style: "border-right-width:1px;",
                items: ["->", {
                    text: lang("add"),
                    iconCls: "icon-utils-s-add",
                    hidden: !this.permitController.add,
                    handler: function() {
                        a.action = "add";
                        a.initForm();
                        a.updateView()
                    }
                },
                    {
                        text: lang("del"),
                        iconCls: "icon-utils-s-delete",
                        hidden: !this.permitController["delete"],
                        handler: function() {
                            a.deleteDept()
                        }
                    },
                    {
                        text: lang("refresh"),
                        iconCls: "icon-system-refresh",
                        handler: function() {
                            a.reloadDept("refresh")
                        }
                    }]
            })
        });
        this.treeRoot = this.deptTree.getRootNode()
    },
    initEditPanel: function() {
        var a = this;
        this.parentDeptAddSelector = new CNOA.selector.DepartmentSelector({
            closeAction: "hide",
            //dataUrl: this.baseUrl + "add&task=getStructTree",
            dataUrl: this.baseUrl + "/getStructTree",
            multiSelect: false
        });
        this.parentDeptEditSelector = new CNOA.selector.DepartmentSelector({
            closeAction: "hide",
            //dataUrl: this.baseUrl + "edit&task=getStructTree",
            dataUrl: this.baseUrl + "/getStructTree",
            multiSelect: false
        });
        this.parentDeptField = new CNOA.selector.form.DepartmentSelectorField({
            fieldLabel: lang("inDepartment"),
            allowBlank: false,
            hiddenName: "fid",
            selector: this.parentDeptAddSelector,
            listeners: {
                confirm: function(d, b, c) {
                    a.reloadPositionComboBox(b.getItemId(c[0]))
                }
            }
        });
        this.positionSetView = this.createPositionSetView();
        this.formPanel = new Ext.form.FormPanel({
            region: "center",
            waitMsgTarget: true,
            bodyStyle: "border-left-width:1px; padding: 10px",
            labelWidth: 90,
            labelAlign: "right",
            buttonAlign: "left",
            disabled: !this.permitController.add && !this.permitController.edit,
            items: [{
                title: lang("addDept"),
                xtype: "fieldset",
                autoWidth: true,
                bodyStyle: "padding:4px",
                defaults: {
                    width: 300
                },
                items: [{
                    xtype: "textfield",
                    fieldLabel: lang("deptName"),
                    allowBlank: false,
                    name: "name"
                },
                    this.parentDeptField, {
                        xtype: "textarea",
                        fieldLabel: lang("deptDescription"),
                        height: 60,
                        name: "about"
                    }]
            },
                this.positionSetView],
            tbar: new Ext.Toolbar({
                style: "border-left-width:1px;",
                items: [{
                    text: lang("save"),
                    iconCls: "icon-btn-save",
                    cls: "btn-blue4",
                    disabled: !this.permitController.add && !this.permitController.edit,
                    handler: function() {
                        a.submitForm()
                    }
                }]
            }),
            keys: [{
                key: Ext.EventObject.ENTER,
                scope: this,
                fn: this.submitForm
            }]
        })
    },
    createPositionSetView: function() {
        var e = this;
        var d = new Ext.form.ComboBox({
            hiddenName: "broId",
            width: 265,
            disabled: true,
            editable: false,
            allowBlank: false,
            mode: "local",
            triggerAction: "all",
            valueField: "broId",
            displayField: "value",
            store: new Ext.data.ArrayStore({
                fields: ["broId", "value"],
                listeners: {
                    load: function(k, g) {
                        var f = g.length,
                            j;
                        for (var h = 0; h < f; h++) {
                            if (g[h].data.broId == e.currentDeptId) {
                                j = g[h + 1];
                                k.removeAt(h);
                                break
                            }
                        }
                        if (j) {
                            a.enable();
                            a.setValue(true);
                            d.setValue(j.data.broId)
                        } else {
                            if (f == 1) {
                                a.disable();
                                c.setValue(true);
                                d.setValue("")
                            } else {
                                a.enable();
                                c.setValue(true);
                                d.setValue("")
                            }
                        }
                    }
                }
            })
        });
        this.positionComboBox = d;
        var c = new Ext.form.Radio({
            boxLabel: lang("lastAtSameDept"),
            name: "edit_radio",
            checked: true,
            inputValue: "last"
        });
        var a = new Ext.form.Radio({
            boxLabel: lang("at2"),
            name: "edit_radio",
            width: 55,
            inputValue: "in",
            listeners: {
                check: function(g, f) {
                    if (f) {
                        d.enable()
                    } else {
                        d.disable()
                    }
                }
            }
        });
        var b = new Ext.form.FieldSet({
            title: lang("deptPosition"),
            labelWidth: 75,
            items: [c, {
                xtype: "container",
                layout: "hbox",
                style: "margin-left: 80px;",
                items: [a, this.positionComboBox, {
                    xtyp: "box",
                    border: false,
                    style: "margin-left:5px;",
                    html: lang("front")
                }]
            }]
        });
        return b
    },
    reloadDept: function() {
        var a = this.getSelectionDept(),
            b;
        if (a) {
            b = a.getPath()
        }
        this.treeRoot.reload();
        if (b) {
            this.deptTree.selectPath(b)
        }
    },
    reloadPositionComboBox: function(f) {
        this.positionComboBox.clearValue();
        var d = this.deptTree.root.findChild("deptId", f, true);
        if (d) {
            var e = d.childNodes,
                a = e.length;
            var b = [];
            for (var c = 0; c < a; c++) {
                b.push([e[c].attributes.deptId, e[c].attributes.text])
            }
            this.positionComboBox.store.loadData(b)
        }
    },
    initForm: function() {
        this.formPanel.getForm().reset();
        this.parentDeptField.setValue(this.currentDeptId)
    },
    loadFormData: function(b) {
        var a = this;
        this.formPanel.getForm().load({
            url: this.baseUrl+"/loadData",
            method: "POST",
            params: {
                id: b
            },
            waitMsg: lang("waiting"),
            success: function(d, c) {
                a.reloadPositionComboBox(c.result.data.fid)
            },
            failure: function(c, d) {
                CNOA.msg.alert(d.result.msg)
            }
        })
    },
    submitForm: function() {
        if (!this.formPanel.getForm().isValid()) {
            return
        }
        var b = this;
        var a = this.action == "add" ? 0 : this.currentDeptId;
        this.formPanel.getForm().submit({
            waitMsg: lang("waiting"),
            url: this.baseUrl + "/submit?action="+this.action,
            method: "POST",
            params: {
                id: a
            },
            success: function() {
                if (b.action === "add") {
                    b.initForm()
                }
                b.reloadDept()
            },
            failure: function(c, d) {
                CNOA.msg.alert(d.result.msg)
            }
        })
    },
    deleteDept: function() {
        var a = this;
        CNOA.msg.cf(lang("confirmToDelete"),
            function(b) {
                if (b == "yes") {
                    Ext.Ajax.request({
                        url: "/delete",
                        method: "POST",
                        params: {
                            id: a.currentDeptId
                        },
                        success: function(d) {
                            var c = Ext.decode(d.responseText);
                            if (c.success === true) {
                                CNOA.msg.alert(c.msg,
                                    function() {
                                        a.reloadDept();
                                        a.initForm()
                                    })
                            } else {
                                CNOA.msg.alert(c.msg)
                            }
                        }
                    })
                }
            })
    },
    updateView: function() {
        var b = this.getSelectionDeptId();
        if (b === this.rootNodeId || (CNOA_USER_JID != 1 && b == CNOA_USER_DID)) {
            this.parentDeptField.disable();
            this.hideDeptPositionView(true)
        } else {
            this.parentDeptField.enable();
            this.hideDeptPositionView(false)
        }
        var a = this.action === "add" ? this.parentDeptAddSelector: this.parentDeptEditSelector;
        this.parentDeptField.bindSelector(a);
        this.parentDeptField.setValue(this.currentDeptId)
    },
    hideDeptPositionView: function(a) {
        if (a) {
            this.positionSetView.hide()
        } else {
            this.positionSetView.show()
        }
    },
    getSelectionDept: function() {
        return this.deptTree.getSelected()
    },
    getSelectionDeptId: function() {
        return parseInt(this.currentDeptId) || 0
    }
};
var sm_main_struct = 1;
var CNOA_main_user_deptClass;

/**
 权限列表：
 CNOA.permitController.main_struct['list'];
 CNOA.permitController.main_struct['add'];
 CNOA.permitController.main_struct['edit'];
 CNOA.permitController.main_struct['delete'];
 */
CNOA_main_user_deptClass = CNOA.Class.create();
CNOA_main_user_deptClass.prototype = {

    init: function(action) {
        this.baseUrl = 'index.php?app=main&func=struct&action=';
        this.permitController = CNOA.permitController.main_struct;

        this.rootNodeId = 1;
        this.currentDeptId = 0;
        this.action = action;

        this.initDepartmentPanel();
        this.initEditPanel();

        //主面板
        this.mainPanel = new Ext.Panel({
            title: lang('structMgr'),
            layout: 'border',
            border: false,
            hideBorders: true,
            items: [this.deptTree, this.formPanel]
        });
    },

    // private
    initDepartmentPanel: function() {
        var _this = this;

        this.deptTree = new CNOA.selector.DepartmentPanel({
            region: 'west',
            split: true,
            width: 250,
            minWidth: 200,
            maxWidth: 380,
            bodyStyle: 'border-right-width:1px;',
            dataUrl: this.baseUrl + 'list&task=getStructTree',
            listeners: {
                dataloaded: function(th) {
                    var deep = th.toggleButton.pressed;
                    th.getRootNode().expandChildNodes(deep);
                },
                click: function(node) {
                    if (_this.currentDeptId == node.attributes.selfid) {
                        return;
                    }

                    _this.currentDeptId = parseInt(node.attributes.selfid) || 0;

                    if (_this.permitController.edit) {
                        _this.action = 'edit';
                        _this.updateView();
                        _this.loadFormData(_this.currentDeptId);
                    }
                }
            },
            tbar: new Ext.Toolbar({
                style: 'border-right-width:1px;',
                items: [
                    '->',
                    {
                        text: lang('add'),
                        iconCls: 'icon-utils-s-add',
                        hidden: ! this.permitController.add,
                        handler: function() {
                            _this.action = 'add';
                            _this.initForm();
                            _this.updateView();
                        }
                    },{
                        text: lang('del'),
                        iconCls: 'icon-utils-s-delete',
                        hidden: ! this.permitController['delete'],
                        handler: function() {
                            _this.deleteDept();
                        }
                    },{
                        text: lang('refresh'),
                        iconCls: 'icon-system-refresh',
                        handler: function() {
                            _this.reloadDept('refresh');
                        }
                    }
                ]
            })
        });

        this.treeRoot = this.deptTree.getRootNode();
    },

    // private
    initEditPanel: function() {
        var _this = this;

        this.parentDeptAddSelector = new CNOA.selector.DepartmentSelector({
            closeAction: 'hide',
            dataUrl: this.baseUrl + 'add&task=getStructTree',
            multiSelect: false
        });
        this.parentDeptEditSelector = new CNOA.selector.DepartmentSelector({
            closeAction: 'hide',
            dataUrl: this.baseUrl + 'edit&task=getStructTree',
            multiSelect: false
        });

        this.parentDeptField = new CNOA.selector.form.DepartmentSelectorField({
            fieldLabel: lang('inDepartment'),
            allowBlank: false,
            hiddenName: 'fid',
            selector: this.parentDeptAddSelector,
            listeners: {
                confirm: function(th, selector, selections) {
                    _this.reloadPositionComboBox(selector.getItemId(selections[0]));
                }
            }
        });

        this.positionSetView = this.createPositionSetView();

        this.formPanel = new Ext.form.FormPanel({
            region: 'center',
            waitMsgTarget: true,
            bodyStyle: 'border-left-width:1px; padding: 10px',
            labelWidth: 90,
            labelAlign: 'right',
            buttonAlign: 'left',
            disabled: !this.permitController.add && !this.permitController.edit,
            items: [
                {
                    title: lang('addDept'),
                    xtype: 'fieldset',
                    autoWidth: true,
                    bodyStyle: 'padding:4px',
                    defaults: {
                        width: 300
                    },
                    items: [
                        {
                            xtype: 'textfield',
                            fieldLabel: lang('deptName'),
                            allowBlank: false,
                            name: 'name'
                        },
                        this.parentDeptField,
                        {
                            xtype:'textarea',
                            fieldLabel: lang('deptDescription'),
                            height: 60,
                            name: 'about'
                        }
                    ]
                },
                this.positionSetView
            ],
            tbar: new Ext.Toolbar({
                style: 'border-left-width:1px;',
                items: [
                    {
                        text: lang('save'),
                        iconCls: 'icon-btn-save',
                        cls: 'btn-blue4',
                        disabled: ! this.permitController.add && ! this.permitController.edit,
                        handler: function() {
                            _this.submitForm();
                        }
                    }
                ]
            }),
            //回车提交表单
            keys: [{
                key: Ext.EventObject.ENTER,
                scope: this,
                fn: this.submitForm
            }]
        });
    },

    // private
    createPositionSetView: function() {
        var _this = this;

        // 位置combobox
        var locateComboBox = new Ext.form.ComboBox({
            hiddenName: 'broId',
            width: 265,
            disabled: true,
            editable: false,
            allowBlank: false,
            mode: 'local',
            triggerAction: 'all',
            valueField: 'broId',
            displayField: 'value',
            store: new Ext.data.ArrayStore({
                fields: ['broId', 'value'],
                listeners: {
                    load: function(th, records) {
                        var len = records.length, nextRecord;
                        for (var i = 0; i < len; i++) {
                            if (records[i].data.broId == _this.currentDeptId) {
                                nextRecord = records[i + 1];
                                th.removeAt(i);
                                break;
                            }
                        }

                        if (nextRecord) {
                            locateRadio.enable();
                            locateRadio.setValue(true);
                            locateComboBox.setValue(nextRecord.data.broId);
                        } else if (len == 1) {
                            locateRadio.disable();
                            lastRadio.setValue(true);
                            locateComboBox.setValue('');
                        } else {
                            locateRadio.enable();
                            lastRadio.setValue(true);
                            locateComboBox.setValue('');
                        }
                    }
                }
            })
        });
        this.positionComboBox = locateComboBox;

        var lastRadio = new Ext.form.Radio({
            boxLabel: lang('lastAtSameDept'),
            name: 'edit_radio',
            checked: true,
            inputValue: 'last'
        });

        var locateRadio = new Ext.form.Radio({
            boxLabel: lang('at2'),
            name: 'edit_radio',
            width: 55,
            inputValue: 'in',
            listeners: {
                check: function(th, checked) {
                    if (checked) {
                        locateComboBox.enable();
                    } else {
                        locateComboBox.disable();
                    }
                }
            }
        });

        var fieldSet = new Ext.form.FieldSet({
            title: lang('deptPosition'),
            labelWidth: 75,
            items: [
                lastRadio,
                {
                    xtype: 'container',
                    layout: 'hbox',
                    style: 'margin-left: 80px;',
                    items: [
                        locateRadio,
                        this.positionComboBox,
                        {
                            xtyp: 'box',
                            border: false,
                            style: 'margin-left:5px;',
                            html: lang('front')
                        }
                    ]
                }
            ]
        });

        return fieldSet;
    },

    /**
     * 重新加载部门树列表数据
     */
    reloadDept: function() {
        var node = this.getSelectionDept(), path;
        if (node) path = node.getPath();

        this.treeRoot.reload();

        // 选中刷新前选中的数据
        if(path) this.deptTree.selectPath(path);
    },

    /**
     * 重新载入位置combobox数据
     * @param  {Integer} fid
     */
    reloadPositionComboBox: function(fid) {
        this.positionComboBox.clearValue();

        var node = this.deptTree.root.findChild('deptId', fid, true);
        if (node) {
            var childNodes = node.childNodes, len = childNodes.length;
            var records = [];

            for (var i = 0; i < len; i++) {
                records.push([
                    childNodes[i].attributes.deptId,
                    childNodes[i].attributes.text
                ]);
            }
            this.positionComboBox.store.loadData(records);
        }
    },

    /**
     * 初始化编辑表单
     * @return {[type]} [description]
     */
    initForm: function() {
        this.formPanel.getForm().reset();
        this.parentDeptField.setValue(this.currentDeptId);
    },

    /**
     * 加载部门数据
     * @param  {Integer} id 部门id
     */
    loadFormData: function(id) {
        var _this = this;
        this.formPanel.getForm().load({
            url: 'index.php?app=main&func=struct&action=edit&task=loadData',
            method: 'POST',
            params: { id: id },
            waitMsg: lang('waiting'),
            success: function(from, action) {
                _this.reloadPositionComboBox(action.result.data.fid);
            },
            failure: function(form, action) {
                CNOA.msg.alert(action.result.msg);
            }
        });
    },

    /**
     * 提交表单的数据
     * @param  {Integer} id
     */
    submitForm: function(){
        if (! this.formPanel.getForm().isValid()) return;

        var _this = this;
        var id = this.action=='add' ? 0 : this.currentDeptId;

        this.formPanel.getForm().submit({
            waitMsg: lang('waiting'),
            url: this.baseUrl + this.action + '&task=submit',
            method: 'POST',
            params: {id: id},
            success: function() {
                if(_this.action === 'add'){
                    _this.initForm();
                }

                _this.reloadDept();
            },
            failure: function(form, action) {
                CNOA.msg.alert(action.result.msg);
            }
        });
    },

    /**
     * 删除部门
     */
    deleteDept: function() {
        var _this = this;

        CNOA.msg.cf(lang('confirmToDelete'), function(btn){
            if(btn == 'yes'){
                Ext.Ajax.request({
                    url: 'index.php?app=main&func=struct&action=delete',
                    method: 'POST',
                    params: { id: _this.currentDeptId },
                    success: function(r) {
                        var result = Ext.decode(r.responseText);
                        if (result.success === true) {
                            CNOA.msg.alert(result.msg, function() {
                                _this.reloadDept();
                                _this.initForm();
                            });
                        } else {
                            CNOA.msg.alert(result.msg);
                        }
                    }
                });
            }
        });
    },

    /**
     * 更新视图
     * @return
     */
    updateView: function() {
        var deptId = this.getSelectionDeptId();

        //如果是根节点
        if (deptId === this.rootNodeId ||
            (CNOA_USER_JID != 1 && deptId == CNOA_USER_DID) ) {

            this.parentDeptField.disable();
            this.hideDeptPositionView(true);
        } else {
            this.parentDeptField.enable();
            this.hideDeptPositionView(false);
        }

        // 更换选择器
        var selector = this.action === 'add' ? this.parentDeptAddSelector : this.parentDeptEditSelector;
        // selector.selectorItem
        this.parentDeptField.bindSelector(selector);
        this.parentDeptField.setValue(this.currentDeptId);
    },

    /**
     * 隐藏设置部门位置的视图
     * @param  {Boolean} ishide
     */
    hideDeptPositionView: function(ishide) {
        if (ishide) {
            this.positionSetView.hide();
        } else {
            this.positionSetView.show();
        }
    },

    /**
     * 获取选中的部门
     * @return {Object}
     */
    getSelectionDept: function() {
        return this.deptTree.getSelected();
    },

    /**
     * 获取选中的部门id
     * @return {Object}
     */
    getSelectionDeptId: function() {
        return parseInt(this.currentDeptId) || 0;
    }
};
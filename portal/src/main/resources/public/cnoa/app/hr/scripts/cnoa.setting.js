var CNOA_hr_person_setting_noticeClass;CNOA_hr_person_setting_noticeClass=CNOA.Class.create();CNOA_hr_person_setting_noticeClass.prototype={init:function(){var a=this;this.baseUrl="index.php?app=hr&func=person&action=setting&for=notice";this.ID_btn_save=Ext.id();this.fields=[{name:"id"},{name:"uid"},{name:"jid"},{name:"type"},{name:"status"}];this.store=new Ext.data.Store({autoLoad:true,proxy:new Ext.data.HttpProxy({url:this.baseUrl+"&task=getTargetList"}),reader:new Ext.data.JsonReader({totalProperty:"total",root:"data",fields:this.fields})});this.sm=new Ext.grid.CheckboxSelectionModel({singleSelect:true});this.colum=new Ext.grid.ColumnModel([new Ext.grid.RowNumberer(),this.sm,{header:"id",dataIndex:"id",sortable:true,hidden:true},{header:lang("noticePerson"),width:200,sortable:true,dataIndex:"uid"},{header:lang("remindPosition"),width:200,sortable:true,dataIndex:"jid"},{header:lang("reminderType"),width:200,sortable:true,dataIndex:"type",renderer:this.makeType.createDelegate(this)},{header:lang("takeEffect"),width:200,sortable:true,dataIndex:"status",renderer:this.makeStatus.createDelegate(this)},{header:"",dataIndex:"noIndex",width:1,menuDisabled:true,resizable:false}]);this.grid=new Ext.grid.GridPanel({stripeRows:true,store:this.store,sm:this.sm,loadMask:{msg:lang("waiting")},height:358,colModel:this.colum,viewConfig:{forceFit:true},tbar:new Ext.Toolbar({items:[lang("remindObject"),{id:this.ID_btn_refreshList,handler:function(b,c){this.store.reload()}.createDelegate(this),iconCls:"icon-system-refresh",tooltip:lang("refresh"),text:lang("refresh")},{text:lang("add"),iconCls:"icon-utils-s-add",handler:function(){a.showAddWindow()}.createDelegate(this)},{text:lang("del"),iconCls:"icon-utils-s-delete",handler:function(b,c){var d=this.grid.getSelectionModel().getSelections();if(d.length==0){CNOA.miniMsg.alertShowAt(b,lang("mustSelectOneRow"))}else{CNOA.msg.cf(lang("confirmToDelete"),function(e){if(e=="yes"){if(d){id=d[0].get("id");a.deleteTargetList(id)}}})}}.createDelegate(this)},lang("noteClick")]})});this.baseField=[{xtype:"fieldset",title:lang("contractRemindSet"),defaults:{style:{marginBottom:"10px"}},items:[{xtype:"cnoa_checkbox",name:"notice_enable",helpTip:lang("checkedAuto"),fieldLabel:lang("enableConRemind")},{xtype:"cnoa_textfield",name:"notice_forward",fieldLabel:lang("howDays"),allowBlank:false,width:300,regex:/^\d{1,}$/i,regexText:lang("howDays"),helpTip:lang("setHowDays")}]},this.grid,{xtype:"hidden",listeners:{afterrender:function(){a.loadForm()}}}];this.mainPanel=new Ext.form.FormPanel({title:lang("contractRemindSet"),hideBorders:true,border:false,waitMsgTarget:true,autoScroll:true,layout:"border",labelWidth:130,bodyStyle:"border-left-width:1px;",labelAlign:"right",items:[{xtype:"panel",border:false,bodyStyle:"padding:10px",layout:"form",region:"center",items:[this.baseField]}],tbar:new Ext.Toolbar({style:"border-left-width:1px;",items:[{text:lang("save"),iconCls:"icon-btn-save",cls:"btn-blue4",id:this.ID_btn_save,handler:function(){a.submitForm()}.createDelegate(this)},"->",{xtype:"cnoa_helpBtn",helpid:160}]})})},submitForm:function(a){var c=this;var b=this.mainPanel.getForm();if(b.isValid()){b.submit({url:c.baseUrl+"&task=submitFormDataInfo",waitTitle:lang("notice"),method:"POST",waitMsg:lang("waiting"),params:{},success:function(d,e){CNOA.msg.notice(e.result.msg,lang("hrMgr"));c.store.reload()}.createDelegate(this),failure:function(d,e){CNOA.msg.alert(e.result.msg)}.createDelegate(this)})}else{CNOA.miniMsg.alertShowAt(this.ID_btn_save,lang("formValid"))}},loadForm:function(){var a=this;this.mainPanel.getForm().load({url:a.baseUrl+"&task=editLoadFormDataInfo",method:"POST",waitMsg:lang("waiting"),success:function(b,c){},failure:function(b,c){CNOA.msg.alert(c.result.msg)}})},makeType:function(a){return a=="u"?lang("people"):lang("job")},makeStatus:function(a){var b="";if(a==1){b="<span class='cnoa_color_green'>"+lang("takeEffect")+"</span>"}else{if(a=="0"){b="<span class='cnoa_color_red'>"+lang("noEffect")+"</span>"}else{if(a=="2"){b="<span class='cnoa_color_gray'>"+lang("AlreadyDel")+"</span>"}}}return b},showAddWindow:function(){var g=this;var c=Ext.id();var b=Ext.id();var d=Ext.id();var e=function(h){var i=a.getForm();if(i.isValid()){i.submit({url:g.baseUrl+"&task=addTarget",waitTitle:lang("notice"),method:"POST",waitMsg:lang("waiting"),params:{},success:function(j,k){f.close();g.store.reload()}.createDelegate(this),failure:function(j,k){CNOA.msg.alert(k.result.msg)}.createDelegate(this)})}else{CNOA.miniMsg.alertShowAt(d,lang("formValid"))}};var a=new Ext.form.FormPanel({waitMsgTarget:true,border:false,bodyStyle:"padding: 10px",labelWidth:60,labelAlign:"right",items:[{xtype:"radiogroup",fieldLabel:lang("reminderType"),name:"typeGroup",items:[{boxLabel:lang("people"),name:"type",inputValue:"u",checked:true},{boxLabel:lang("job"),name:"type",inputValue:"j"}],listeners:{change:function(l,k){var i=Ext.getCmp(c);var h=Ext.getCmp(b);if(k.inputValue==="u"){i.setDisabled(false);h.setDisabled(true)}else{i.setDisabled(true);h.setDisabled(false)}}}},{xtype:"userselectorfield",id:c,fieldLabel:lang("noticePerson"),width:200,allowBlank:false,hiddenName:"uid",multiSelect:false},{xtype:"jobselectorfield",id:b,fieldLabel:lang("remindPosition"),width:200,disabled:true,allowBlank:false,hiddenName:"jid",multiSelect:false}]});var f=new Ext.Window({width:357,height:190,modal:true,autoDestroy:true,closeAction:"close",resizable:false,title:lang("addRemindObject"),layout:"fit",items:[a],buttons:[{text:lang("save"),id:d,iconCls:"icon-btn-save",handler:function(){e()}.createDelegate(this)},{text:lang("cancel"),id:this.ID_btn_close,iconCls:"icon-dialog-cancel",handler:function(){f.close()}}]}).show()},deleteTargetList:function(b){var a=this;Ext.Ajax.request({url:this.baseUrl+"&task=deleteTarget",method:"POST",params:{id:b},success:function(d){var c=Ext.decode(d.responseText);if(c.success===true){a.store.reload()}else{CNOA.msg.alert(c.msg)}}})}};var CNOA_hr_person_setting,CNOA_hr_person_settingClass;CNOA_hr_person_settingClass=CNOA.Class.create();CNOA_hr_person_settingClass.prototype={init:function(){_this=this;this.noticePanel=new CNOA_hr_person_setting_noticeClass();this.centerPanel=new Ext.ux.VerticalTabPanel({region:"center",border:false,tabPosition:"left",tabWidth:100,activeItem:0,items:[this.noticePanel.mainPanel]});this.mainPanel=new Ext.Panel({border:false,layout:"border",items:[this.centerPanel]})}};var sm_hr_setting=1;
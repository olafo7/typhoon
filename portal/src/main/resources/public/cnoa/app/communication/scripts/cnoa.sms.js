var CNOA_communication_sms_outboxListClass,CNOA_communication_sms_outboxList;CNOA_communication_sms_outboxListClass=CNOA.Class.create();CNOA_communication_sms_outboxListClass.prototype={init:function(){var a=this;this.baseUrl="index.php?app=communication&func=sms&action=smsmgr";this.search={mobile:"",touid:0,stime:0,etime:0};this.ID_Search_mobile=Ext.id();this.ID_Search_touid=Ext.id();this.ID_Search_stime=Ext.id();this.ID_Search_etime=Ext.id();this.searchBar=new Ext.Toolbar({items:[lang("receiveMan")+":",{xtype:"hidden",id:this.ID_Search_touid},{xtype:"triggerForPeople",dataUrl:this.baseUrl+"&task=getAllUserListsInPermitDeptTree",width:100,id:this.ID_Search_touid+"Tr",listeners:{selected:function(d,c,b){Ext.getCmp(a.ID_Search_touid).setValue(c)}}},lang("receivingPhone"),{xtype:"textfield",id:this.ID_Search_mobile,width:100},"&nbsp;"+lang("posttime")+":",{xtype:"datefield",format:"Y-m-d",id:this.ID_Search_stime,width:100},lang("to"),{xtype:"datefield",id:this.ID_Search_etime,format:"Y-m-d",width:100},{xtype:"button",text:lang("search"),style:"margin-left:5px",handler:function(){a.doSearch()}},{xtype:"button",text:lang("clear"),style:"margin-left:5px",handler:function(){a.resetSearch()}}]});this.fields=[{name:"cid"},{name:"id"},{name:"mobile"},{name:"toname"},{name:"sendtime"},{name:"text"},{name:"from"},{name:"replies"}];this.store=new Ext.data.Store({proxy:new Ext.data.HttpProxy({url:this.baseUrl+"&task=getJsonDataOutbox"}),reader:new Ext.data.JsonReader({totalProperty:"total",root:"data",fields:this.fields}),listeners:{exception:function(g,f,i,e,d,c){var b=Ext.decode(d.responseText);if(b.failure){CNOA.msg.alert(b.msg)}}}});this.store.load({params:{start:0}});this.sm=new Ext.grid.CheckboxSelectionModel();this.colModel=new Ext.grid.ColumnModel([new Ext.grid.RowNumberer(),this.sm,{header:"id",dataIndex:"id",width:20,sortable:true,hidden:true},{header:lang("receivingPhone")+" / "+lang("truename"),dataIndex:"id",width:120,sortable:true,renderer:this.makeName.createDelegate(this)},{header:lang("posttime"),dataIndex:"sendtime",width:80,sortable:true},{header:lang("sourceType"),dataIndex:"from",width:80,sortable:true},{header:lang("smsContent"),dataIndex:"text",width:200,menuDisabled:true},{header:lang("replyContent"),dataIndex:"replies",width:130,menuDisabled:true},{header:lang("opt"),dataIndex:"",width:40,renderer:this.makeOperate.createDelegate(this)},{header:"",dataIndex:"noIndex",width:1,menuDisabled:true,resizable:false}]);this.grid=new Ext.grid.GridPanel({stripeRows:true,store:this.store,loadMask:{msg:lang("waiting")},cm:this.colModel,sm:this.sm,hideBorders:true,autoWidth:true,border:false,viewConfig:{forceFit:true}});this.pagingBar=new Ext.PagingToolbar({displayInfo:true,store:this.store,pageSize:15,listeners:{beforechange:function(b,c){if(a.search.mobile!=""){c.mobile=a.search.mobile}if(a.search.touid!=0&&a.search.touid!=""){c.touid=a.search.touid}if(a.search.stime!=""){c.stime=a.search.stime}if(a.search.etime!=""){c.etime=a.search.etime}}}});this.centerPanel=new Ext.Panel({region:"center",layout:"fit",border:false,autoScroll:true,items:[this.grid],tbar:new Ext.Toolbar({items:[{id:this.ID_btn_refreshList,handler:function(b,c){a.store.reload()}.createDelegate(this),iconCls:"icon-system-refresh",text:lang("refresh")},"->",{xtype:"cnoa_helpBtn",helpid:6}]}),bbar:this.pagingBar});this.mainPanel=new Ext.Panel({collapsible:false,border:false,layout:"border",autoScroll:true,items:[this.centerPanel],tbar:this.searchBar})},makeName:function(f,d,a,g,b,e){var c=a.data;h=c.mobile;if(c.toname!=""){h+="<br />"+c.toname+""}return h},makeOperate:function(f,d,a,g,b,e){var c=a.data;h="<a href='javascript:void(0);' class='gridview' onclick='CNOA_communication_sms_outboxList.view("+c.id+', "out");\'>查看</a>';return h},doSearch:function(){this.search.mobile=Ext.getCmp(this.ID_Search_mobile).getValue();this.search.touid=Ext.getCmp(this.ID_Search_touid).getValue();this.search.stime=Ext.getCmp(this.ID_Search_stime).getRawValue();this.search.etime=Ext.getCmp(this.ID_Search_etime).getRawValue();this.store.load({params:{mobile:this.search.mobile,touid:this.search.touid,stime:this.search.stime,etime:this.search.etime}})},resetSearch:function(){Ext.getCmp(this.ID_Search_mobile).setValue("");Ext.getCmp(this.ID_Search_touid).setValue("");Ext.getCmp(this.ID_Search_stime).setValue("");Ext.getCmp(this.ID_Search_etime).setValue("");Ext.getCmp(this.ID_Search_touid+"Tr").setValue("");this.search={mobile:"",touid:0,stime:0,etime:0};this.store.load()},view:function(b,a){mainPanel.closeTab("CNOA_MENU_MAIN_SMS_VIEW");mainPanel.loadClass(this.baseUrl+"&task=loadPage&from=smsview&id="+b+"&inout="+a,"CNOA_MENU_MAIN_SMS_VIEW","查看短信","icon-page-view")}};var CNOA_communication_sms_sendboxListClass,CNOA_communication_sms_sendboxList;CNOA_communication_sms_sendboxListClass=CNOA.Class.create();CNOA_communication_sms_sendboxListClass.prototype={init:function(){var a=this;this.baseUrl="index.php?app=communication&func=sms&action=smsmgr";this.search={mobile:"",touid:0,stime:0,etime:0};this.ID_Search_mobile=Ext.id();this.ID_Search_touid=Ext.id();this.ID_Search_stime=Ext.id();this.ID_Search_etime=Ext.id();this.searchBar=new Ext.Toolbar({items:["&nbsp;"+lang("receiveMan")+":",{xtype:"hidden",id:this.ID_Search_touid},{xtype:"triggerForPeople",dataUrl:this.baseUrl+"&task=getAllUserListsInPermitDeptTree",width:100,id:this.ID_Search_touid+"Tr",listeners:{selected:function(d,c,b){Ext.getCmp(a.ID_Search_touid).setValue(c)}}},lang("receivingPhone"),{xtype:"textfield",id:this.ID_Search_mobile,width:100},"&nbsp;"+lang("posttime")+":",{xtype:"datefield",format:"Y-m-d",id:this.ID_Search_stime,width:100},lang("to"),{xtype:"datefield",id:this.ID_Search_etime,format:"Y-m-d",width:100},{xtype:"button",text:lang("search"),style:"margin-left:5px",handler:function(){a.doSearch()}},{xtype:"button",text:lang("clear"),style:"margin-left:5px",handler:function(){a.resetSearch()}}]});this.fields=[{name:"cid"},{name:"id"},{name:"mobile"},{name:"toname"},{name:"sendtime"},{name:"text"},{name:"from"},{name:"status"}];this.store=new Ext.data.Store({proxy:new Ext.data.HttpProxy({url:this.baseUrl+"&task=getJsonDataSendbox"}),reader:new Ext.data.JsonReader({totalProperty:"total",root:"data",fields:this.fields}),listeners:{exception:function(g,f,i,e,d,c){var b=Ext.decode(d.responseText);if(b.failure){CNOA.msg.alert(b.msg)}}}});this.store.load({params:{start:0}});this.sm=new Ext.grid.CheckboxSelectionModel();this.colModel=new Ext.grid.ColumnModel([new Ext.grid.RowNumberer(),this.sm,{header:"id",dataIndex:"id",width:20,sortable:true,hidden:true},{header:lang("receivingPhone")+"/ "+lang("truename"),dataIndex:"id",width:120,sortable:true,renderer:this.makeName.createDelegate(this)},{header:lang("posttime"),dataIndex:"sendtime",width:80,sortable:true},{header:lang("sourceType"),dataIndex:"from",width:80,sortable:true},{header:lang("status"),dataIndex:"status",width:80,menuDisabled:true},{header:lang("smsContent"),dataIndex:"text",width:200,menuDisabled:true},{header:lang("opt"),dataIndex:"",width:40,renderer:this.makeOperate.createDelegate(this)},{header:"",dataIndex:"noIndex",width:1,menuDisabled:true,resizable:false}]);this.grid=new Ext.grid.GridPanel({stripeRows:true,store:this.store,loadMask:{msg:lang("waiting")},cm:this.colModel,sm:this.sm,hideBorders:true,autoWidth:true,border:false,viewConfig:{forceFit:true}});this.pagingBar=new Ext.PagingToolbar({displayInfo:true,store:this.store,pageSize:15,listeners:{beforechange:function(b,c){if(a.search.mobile!=""){c.mobile=a.search.mobile}if(a.search.touid!=0&&a.search.touid!=""){c.touid=a.search.touid}if(a.search.stime!=""){c.stime=a.search.stime}if(a.search.etime!=""){c.etime=a.search.etime}}}});this.centerPanel=new Ext.Panel({region:"center",layout:"fit",border:false,autoScroll:true,items:[this.grid],tbar:new Ext.Toolbar({items:[{id:this.ID_btn_refreshList,handler:function(b,c){a.store.reload()}.createDelegate(this),iconCls:"icon-system-refresh",text:lang("refresh")},{id:this.ID_btn_delete,text:lang("del"),iconCls:"icon-utils-s-delete",handler:function(b,c){var d=this.grid.getSelectionModel().getSelections();if(d.length==0){CNOA.miniMsg.alertShowAt(b,lang("mustSelectOneRow"))}else{CNOA.miniMsg.cfShowAt(b,lang("confirmToDelete"),function(){if(d){var f="";for(var e=0;e<d.length;e++){f+=d[e].get("id")+","}a.deleteList(f)}})}}.createDelegate(this)},"->",{xtype:"cnoa_helpBtn",helpid:6}]}),bbar:this.pagingBar});this.mainPanel=new Ext.Panel({collapsible:false,border:false,layout:"border",autoScroll:true,items:[this.centerPanel],tbar:this.searchBar})},makeName:function(f,d,a,g,b,e){var c=a.data;h=c.mobile;if(c.toname!=""){h+="<br />"+c.toname+""}return h},makeOperate:function(f,d,a,g,b,e){var c=a.data;h="<a  class='gridview' href='javascript:void(0);' onclick='CNOA_communication_sms_sendboxList.view("+c.id+', "send");\'>查看</a>';return h},doSearch:function(){this.search.mobile=Ext.getCmp(this.ID_Search_mobile).getValue();this.search.touid=Ext.getCmp(this.ID_Search_touid).getValue();this.search.stime=Ext.getCmp(this.ID_Search_stime).getRawValue();this.search.etime=Ext.getCmp(this.ID_Search_etime).getRawValue();this.store.load({params:{mobile:this.search.mobile,touid:this.search.touid,stime:this.search.stime,etime:this.search.etime}})},resetSearch:function(){Ext.getCmp(this.ID_Search_mobile).setValue("");Ext.getCmp(this.ID_Search_touid).setValue("");Ext.getCmp(this.ID_Search_stime).setValue("");Ext.getCmp(this.ID_Search_etime).setValue("");Ext.getCmp(this.ID_Search_touid+"Tr").setValue("");this.search={mobile:"",touid:0,stime:0,etime:0};this.store.load()},deleteList:function(a){var b=this;Ext.Ajax.request({url:this.baseUrl+"&task=deleteSendbox",method:"POST",params:{ids:a},success:function(d){var c=Ext.decode(d.responseText);if(c.success===true){CNOA.msg.notice(c.msg,lang("sms2"));b.store.reload()}else{CNOA.msg.alert(c.msg)}}})},view:function(b,a){mainPanel.closeTab("CNOA_MENU_MAIN_SMS_VIEW");mainPanel.loadClass(this.baseUrl+"&task=loadPage&from=smsview&id="+b+"&inout="+a,"CNOA_MENU_MAIN_SMS_VIEW","查看短信","icon-page-view")}};var CNOA_communication_sms_viewClass,CNOA_communication_sms_view;CNOA_communication_sms_viewClass=CNOA.Class.create();CNOA_communication_sms_viewClass.prototype={init:function(){var a=this;this.type=CNOA.communication.sms.view.type;this.id=CNOA.communication.sms.view.id;this.baseUrl="index.php?app=communication&func=sms&action=smsmgr";this.Panel=new Ext.Panel({border:false,bodyStyle:"padding:10px",labelAlign:"right",labelWidth:"100",autoScroll:true,listeners:{render:function(){a.getviewInfo()}}});this.mainPanel=new Ext.Panel({collapsible:false,hideBorders:true,border:false,layout:"fit",autoScroll:false,items:[this.Panel],tbar:new Ext.Toolbar({items:[{id:this.ID_btn_refreshList,handler:function(b,c){a.getviewInfo()}.createDelegate(this),iconCls:"icon-system-refresh",text:lang("refresh")},{text:lang("close"),iconCls:"icon-dialog-cancel",handler:function(){a.closeTab()}.createDelegate(this)},"->",{xtype:"cnoa_helpBtn",helpid:8}]})})},closeTab:function(){mainPanel.closeTab(CNOA.communication.sms.view.parentID.replace("docs-",""))},getviewInfo:function(){var a=this;a.mainPanel.getEl().mask(lang("waiting"));Ext.Ajax.request({url:this.baseUrl+"&task=view",method:"POST",disableCaching:true,params:{type:this.type,id:this.id},success:function(c){a.mainPanel.getEl().unmask();var b=Ext.decode(c.responseText);if(b.success===true){a.makeContents(b.data)}else{CNOA.msg.alert(b.msg)}}})},makeContents:function(c){var d=this;var a=new Array();if(this.type=="in"){a.push(d.makeInView(c))}else{if(this.type=="out"){a.push(d.makeOutView(c));if(c.list.length>0){for(var b=0;b<c.list.length;b++){a.push(d.makeInView(c.list[b]))}}}else{if(this.type=="send"){a.push(d.makeOutView(c))}}}this.Panel.add(a);this.Panel.doLayout()},makeOutView:function(a){var c=this;var b=new Ext.Panel({title:lang("msgType"),style:"margin-bottom:10px",bodyStyle:"padding:10px",frame:true,layout:"form",items:[{xtype:"displayfield",fieldLabel:lang("sender"),value:a.fromname},{xtype:"displayfield",fieldLabel:lang("receiveMan"),value:a.toname},{xtype:"displayfield",fieldLabel:lang("receivingPhone"),value:a.mobile},{xtype:"displayfield",fieldLabel:lang("posttime"),value:a.sendtime},{xtype:"displayfield",fieldLabel:lang("smsContent"),value:a.text},{xtype:"displayfield",fieldLabel:lang("msgType1"),value:a.from}]});return b},makeInView:function(a){var d=this;var c;if(this.type=="in"){c=lang("msgType2")}else{c=lang("reply")}var b=new Ext.Panel({title:c,style:"margin-bottom:10px",bodyStyle:"padding:10px",frame:true,layout:"form",items:[{xtype:"displayfield",fieldLabel:lang("sender"),value:a.fromname},{xtype:"displayfield",fieldLabel:lang("sourceNum"),value:a.mobile},{xtype:"displayfield",fieldLabel:lang("receiveTime"),value:a.posttime},{xtype:"displayfield",fieldLabel:lang("smsContent"),value:a.text}]});return b}};CNOA_communication_sms_linkmanSortClass=CNOA.Class.create();CNOA_communication_sms_linkmanSortClass.prototype={init:function(a){var b=this;this.baseUrl="index.php?app=communication&func=sms&action=smsmgr";this.tp=a;this.title=lang("sortSet");this.action=a=="edit"?"edit":"add";this.edit_id=0;this.ID_btn_delete=Ext.id();this.ID_btn_edit=Ext.id();this.ID_groupComboBox=Ext.id();this.fields=[{name:"id",mapping:"id"},{name:"name",mapping:"name"}];this.store=new Ext.data.Store({autoLoad:true,proxy:new Ext.data.HttpProxy({url:this.baseUrl+"&task=getGroupList"}),reader:new Ext.data.JsonReader({totalProperty:"total",root:"data",fields:this.fields})});this.sm=new Ext.grid.CheckboxSelectionModel({singleSelect:true});this.colum=new Ext.grid.ColumnModel([new Ext.grid.RowNumberer(),this.sm,{header:"id",dataIndex:"id",sortable:true,hidden:true},{header:lang("sortName"),width:440,editor:new Ext.form.TextField({allowBlank:false}),sortable:true,dataIndex:"name"}]);this.grid=new Ext.grid.EditorGridPanel({store:this.store,sm:this.sm,hideBorders:true,border:false,height:258,collapsible:true,animCollapse:true,allowDomMove:true,colModel:this.colum,viewConfig:{forceFit:true},clicksToEdit:1,listeners:{afteredit:function(e){var f=e.record;var c=e.field;var g=f.get("id");var d=f.get("name");Ext.Ajax.request({url:this.baseUrl+"&task=updateGroup",params:"id="+g+"&name="+encodeURIComponent(d),success:function(j){var i=Ext.decode(j.responseText);if(i.success===true){}else{CNOA.msg.alert(i.msg,function(){b.store.reload()})}}})}.createDelegate(this)}});this.mainPanel=new Ext.Window({width:500,height:390,resizable:false,autoDistory:true,modal:true,maskDisabled:true,items:[this.grid],plain:true,title:this.title,buttonAlign:"right",autoScroll:true,tbar:[{text:lang("addSort"),iconCls:"icon-utils-s-add",cls:"btn-blue4",handler:function(){var c=this.grid.getStore().recordType;var d=new c({name:""});this.grid.stopEditing();this.store.insert(0,d);this.grid.startEditing(0,3)}.createDelegate(this)},{text:lang("del"),iconCls:"icon-utils-s-delete",id:this.ID_btn_delete,handler:function(c,d){var e=this.grid.getSelectionModel().getSelections();if(e.length==0){CNOA.miniMsg.alertShowAt(c,lang("mustSelectOneRow"))}else{CNOA.msg.cf(lang("delLinkSortSure"),function(f){if(f=="yes"){if(e){var g="";g+=e[0].get("id");b.deleteList(g)}}})}}.createDelegate(this)}],buttons:[{text:lang("close"),iconCls:"icon-dialog-cancel",handler:function(){b.close();b.store.reload();CNOA_communication_sms_linkman.sortStore.reload();CNOA_communication_sms_linkman.store.reload()}}],listeners:{close:function(){b.store.reload();CNOA_communication_sms_linkman.sortStore.reload();CNOA_communication_sms_linkman.store.reload()}}})},show:function(b){var a=this;a.mainPanel.show()},close:function(){this.mainPanel.close()},deleteList:function(a){var b=this;Ext.Ajax.request({url:this.baseUrl+"&task=deleteGroup",method:"POST",params:{ids:a},success:function(d){var c=Ext.decode(d.responseText);if(c.success===true){b.store.reload()}else{CNOA.msg.alert(c.msg)}}})}};CNOA_communication_sms_linkmanClass=CNOA.Class.create();CNOA_communication_sms_linkmanClass.prototype={init:function(){var d=this;var c=Ext.id();var b=Ext.id();var a=Ext.id();this.baseUrl="index.php?app=communication&func=sms&action=smsmgr";this.storeBar={name:"",type:0,mobile:0};this.sortStore=new Ext.data.Store({proxy:new Ext.data.HttpProxy({url:this.baseUrl+"&task=getSortList",method:"POST"}),reader:new Ext.data.JsonReader({root:"data",fields:[{name:"id",mapping:"id"},{name:"name",mapping:"name"}]})});this.sortStore.load();this.sortCombo=new Ext.form.ComboBox({autoLoad:true,triggerAction:"all",selectOnFocus:true,editable:false,store:this.sortStore,valueField:"id",displayField:"name",allowBlank:false,mode:"local",forceSelection:true});this.dsc=Ext.data.Record.create([{name:"mobile",type:"string"},{name:"name",type:"string"},{name:"sid",type:"string"}]);this.fields=[{name:"id",mapping:"id"},{name:"sid",mapping:"sid"},{name:"mobile",mapping:"mobile"},{name:"name",mapping:"name"}];this.store=new Ext.data.GroupingStore({autoLoad:true,proxy:new Ext.data.HttpProxy({url:this.baseUrl+"&task=getLinkmanList"}),reader:new Ext.data.JsonReader({totalProperty:"total",root:"data",fields:this.fields}),listeners:{update:function(i,e,g){var f=e.data;if(g==Ext.data.Record.EDIT){d.submit(f)}}},sortInfo:{field:"sid",direction:"DESC"}});this.editor=new Ext.ux.grid.RowEditor({cancelText:lang("cancel"),saveText:lang("update"),errorSummary:false});this.sm=new Ext.grid.CheckboxSelectionModel({singleSelect:false});this.cm=[new Ext.grid.RowNumberer(),this.sm,{header:"",dataIndex:"id",hidden:true},{header:"",dataIndex:"sid",hidden:true},{id:"mobile",header:lang("phoneNumber"),dataIndex:"mobile",width:200,sortable:true,editor:{xtype:"textfield",vtype:"mobile",vtypeText:lang("wrongMobile"),allowBlank:false}},{header:lang("truename"),dataIndex:"name",width:200,sortable:true,editor:{xtype:"textfield",allowBlank:false}},{id:"sid",header:lang("sort2"),dataIndex:"sid",width:200,sortable:true,editor:this.sortCombo,renderer:function(e){var f;d.sortStore.each(function(g){if(g.get("id")==e){f=g;return}});return f==null?e:f.get("name")}}];this.grid=new Ext.grid.GridPanel({stripeRows:true,store:this.store,sm:this.sm,width:600,plain:false,loadMask:{msg:lang("waiting")},region:"center",border:false,autoScroll:true,autoExpandColumn:"sid",plugins:[this.editor],columns:this.cm,view:new Ext.grid.GroupingView({markDirty:false}),tbar:[{handler:function(e,f){d.store.reload()}.createDelegate(this),iconCls:"icon-system-refresh",text:lang("refresh")},{iconCls:"icon-utils-s-add",text:lang("add"),handler:function(){var f=new d.dsc({mobile:"",sid:"",name:""});d.editor.stopEditing();d.store.insert(0,f);d.grid.getView().refresh();d.grid.getSelectionModel().selectRow(0);d.editor.startEditing(0)}},{iconCls:"icon-utils-s-delete",text:lang("del"),tooltip:lang("articleDelRecord"),handler:function(e){d.editor.stopEditing();var f=d.grid.getSelectionModel().getSelections();if(f.length==0){CNOA.miniMsg.alertShowAt(e,lang("mustSelectOneRow"))}else{CNOA.msg.cf(lang("confirmToDelete"),function(j){if(j=="yes"){if(f){var k="";for(var g=0;g<f.length;g++){k+=f[g].get("id")+",";var l=f[g];d.store.remove(l)}d.deleteList(k)}}})}}},{handler:function(e,f){d.showImportWindow()}.createDelegate(this),tooltip:lang("importLinkman"),iconCls:"icon-importLinkman",cls:"btn-blue3",text:lang("importLinkman")},"<span style='color:#676767'>"+lang("typeSet")+"</span>",{text:lang("typeMgr"),iconCls:"icon-type-mgr",cls:"btn-blue3",handler:function(e,f){CNOA_communication_sms_linkmanSort=new CNOA_communication_sms_linkmanSortClass();CNOA_communication_sms_linkmanSort.show()}.createDelegate(this)},(lang("sort")+":"),{xtype:"panel",border:false,items:[new CNOA.form.ComboBox({autoLoad:true,triggerAction:"all",selectOnFocus:true,editable:false,width:100,store:d.sortStore,id:c,valueField:"id",displayField:"name",mode:"local",forceSelection:true})]},lang("truename"),{xtype:"cnoa_textfield",width:100,id:b},lang("phoneNumber"),{xtype:"cnoa_textfield",width:100,id:a},{text:lang("search"),handler:function(){d.storeBar.type=Ext.getCmp(c).getValue();d.storeBar.name=Ext.getCmp(b).getValue();d.storeBar.mobile=Ext.getCmp(a).getValue();d.store.load({params:d.storeBar})}},{text:lang("clear"),handler:function(){Ext.getCmp(c).setValue("");Ext.getCmp(b).setValue("");Ext.getCmp(a).setValue("");d.storeBar.name="";d.storeBar.mobile="";d.storeBar.type=0;d.store.load({params:d.storeBar})}},"<span class='cnoa_color_gray'>"+lang("dblclickToEdit")+"</span>","->",{xtype:"cnoa_helpBtn",helpid:99}]});this.mainPanel=new Ext.Panel({collapsible:false,hideBorders:true,border:false,layout:"border",autoScroll:false,items:[this.grid]})},submit:function(a){var b=this;Ext.Ajax.request({url:this.baseUrl+"&task=submitLinkman",params:a,method:"POST",success:function(e,d){var c=Ext.decode(e.responseText);if(c.success===true){}else{CNOA.msg.alert(c.msg,function(){b.store.reload()})}},failure:function(c,d){CNOA.msg.alert(result.msg,function(){b.store.reload()})}})},deleteList:function(a){var b=this;Ext.Ajax.request({url:this.baseUrl+"&task=deleteLinkman",method:"POST",params:{ids:a},success:function(d){var c=Ext.decode(d.responseText);if(c.success===true){b.store.reload()}else{CNOA.msg.alert(c.msg)}}})},showImportWindow:function(){var a=this;this.UPLOAD_WINDOW_FORMPANEL=new Ext.FormPanel({fileUpload:true,autoHeight:true,autoScroll:false,waitMsgTarget:true,hideBorders:true,border:false,bodyStyle:"padding: 5px;",buttonAlign:"right",items:[{xtype:"fileuploadfield",name:"face",allowBlank:false,buttonCfg:{text:lang("browserLinkmanExcel")},hideLabel:true,width:370},{xtype:"displayfield",hideLabel:true,value:lang("must0307Excel")},{xtype:"displayfield",hideLabel:true,value:lang("attention")+"<span class='cnoa_color_red'>"+lang("recommendedUse")+"<a href='http://cnoa.cn/download/s/messager' target='_blank' ext:qtip='点击下载小秘书' style='font-weight:bold;'>["+lang("oaClient")+"]</a>"+lang("enterTheOA")+"</span>"}],buttons:[{text:lang("import2"),iconCls:"document-excel-import",cls:"btn-blue3",handler:function(){if(this.UPLOAD_WINDOW_FORMPANEL.getForm().isValid()){this.UPLOAD_WINDOW_FORMPANEL.getForm().submit({url:a.baseUrl+"&task=import_linkman_upload",waitMsg:lang("waiting"),params:{},success:function(b,c){a.showImportToDbWindow(c.result.msg);a.UPLOAD_WINDOW.close()},failure:function(b,c){CNOA.msg.alert(c.result.msg,function(){})}})}}.createDelegate(this)},{text:lang("close"),handler:function(){a.UPLOAD_WINDOW.close()}}]});this.UPLOAD_WINDOW=new Ext.Window({width:398,height:183,autoScroll:false,modal:true,resizable:false,title:lang("importLinkManList"),items:[this.UPLOAD_WINDOW_FORMPANEL]}).show()},showImportToDbWindow:function(e){var f=this;var d=Ext.getBody().getBox();var b=d.width-100;var c=d.height-100;var a=Ext.id();f.ImportToDbWindow=new Ext.Window({title:lang("adjustCustomer"),width:b,height:c,layout:"fit",modal:true,maximizable:true,resizable:true,html:'<iframe scrolling="auto" frameborder="0" id="'+a+'" width="100%" height="100%" src="'+f.baseUrl+"&task=import_linkman_todb&from="+e+'"></iframe>',buttons:[{text:lang("save"),iconCls:"icon-order-s-accept",handler:function(){Ext.getDom(a).contentWindow.submit()}},{text:lang("cancel"),iconCls:"icon-dialog-cancel",handler:function(){f.ImportToDbWindow.close()}}],listeners:{close:function(){try{Ext.getDom(a).src=""}catch(g){}}}}).show()},closeImportToDbWindow:function(){this.ImportToDbWindow.close()}};var sm_communication_sms=1;
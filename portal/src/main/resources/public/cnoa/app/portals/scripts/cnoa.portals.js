var CNOA_portals_portals_my,CNOA_portals_portals_myClass;CNOA_portals_portals_myClass=new CNOA.Class.create();CNOA_portals_portals_myClass.prototype={init:function(){var a=this;this.baseUrl="index.php?app=portals&func=portals&action=my";this.mainPanel=new Ext.Panel({layout:{type:"hbox",align:"middle ",pack:"center"},border:false,bodyBorder:false,listeners:{afterrender:function(b){b.add(a.tablePanel);b.doLayout()}}});this.tablePanel=new Ext.Panel({layout:"table",layoutConfig:{columns:4},defaults:{style:"margin:20px"},border:false,bodyBorder:false,width:1040,listeners:{afterrender:function(){Ext.Ajax.request({url:a.baseUrl+"&task=getUserPortalsList",success:function(b){var c=Ext.decode(b.responseText);a.loadPortals(c.data)}})}}})},loadPortals:function(c){var d=this,a;for(var b=0;b<c.length;b++){a=new Ext.Panel({width:220,height:135,border:false,bodyBorder:false,portalsID:c[b].id,portalsName:c[b].portalsName,portalsMids:c[b].mids,listeners:{afterrender:function(e){e.mon(e.el,"click",function(){mainPanel.closeTab("CNOA_MENU_PORTALS_"+e.portalsID);mainPanel.loadClass("index.php?app=portals&func=portals&action=my&task=getUserPortals&portalsID="+e.portalsID+"&mids="+e.portalsMids,"CNOA_MENU_PORTALS_"+e.portalsID,e.portalsName,"icon-award-star-gold-3")})}},html:d.getHtml(c[b])});d.tablePanel.add(a)}this.tablePanel.doLayout();this.mainPanel.doLayout()},getHtml:function(a){html="<div style=\"width:220px;height:135px;cursor:pointer;background:url('resources/portals/images/bg"+a.bgColor+'.png\') no-repeat;"><img id="image" src="resources/portals/images/'+a.imageID+'.png" style="margin:15px 73px 5px 73px;"/><span style="display:block;font-size:18px;font-family:微软雅黑;color:#fff;text-align:center;">'+a.portalsName+"</span><div>";return html}};var CNOA_portals_portals_setting,CNOA_portals_portals_settingClass;CNOA_portals_portals_editWinClass=new CNOA.Class.create();CNOA_portals_portals_editWinClass.prototype={init:function(k){var i=this;this.baseUrl="index.php?app=portals&func=portals&action=setting";var a="index.php?action=commonJob&act=getSelectorData";this.portalsID="";this.showHidenID=Ext.id();if(k){this.portalsID="&portalsID="+k.id}var d=new Ext.Panel({layout:"form",border:false,bodyBorder:false,columnWidth:0.5,items:[{xtype:"textfield",fieldLabel:lang("portalsName"),allowBlank:false,width:270,name:"portalsName",id:"portalsName",listeners:{change:function(){Ext.getCmp("portals_image").body.dom.innerHTML=i.getHtml()}}},{xtype:"hidden",name:"id"},{xtype:"hidden",name:"uids"},{xtype:"textarea",width:270,height:60,fieldLabel:lang("selectPeople"),name:"people",readOnly:true,listeners:{afterrender:function(l){l.mon(l.el,"click",function(){var m=b.getForm();var n=m.findField("uids").getValue();new_selector("user",m.findField("people"),m.findField("uids"),true,a+"&target=user",n)})}}},{xtype:"hidden",name:"deptIds"},{xtype:"textarea",width:270,height:60,fieldLabel:lang("selectDept"),name:"dept",readOnly:true,listeners:{afterrender:function(l){l.mon(l.el,"click",function(){var m=b.getForm();var n=m.findField("deptIds").getValue();new_selector("dept",m.findField("dept"),m.findField("deptIds"),true,a+"&target=dept",n)})}}},{xtype:"hidden",name:"jobIds"},{xtype:"textarea",width:270,height:60,fieldLabel:lang("selectJob"),name:"job",readOnly:true,listeners:{afterrender:function(l){l.mon(l.el,"click",function(){var m=b.getForm();var n=m.findField("jobIds").getValue();new_selector("job",m.findField("job"),m.findField("jobIds"),true,a+"&target=job",n)})}}}]});var j=new Ext.form.ComboBox({width:100,displayField:"name",valueField:"id",triggerAction:"all",fieldLabel:lang("selectImage"),editable:false,allowBlank:false,hiddenName:"imageID",id:"imageID",mode:"local",store:new Ext.data.Store({autoLoad:true,proxy:new Ext.data.HttpProxy({url:"index.php?app=portals&func=portals&action=setting&task=getImagesList"}),reader:new Ext.data.JsonReader({totalProperty:"total",root:"data",fields:["id","name"]}),listeners:{load:function(){var n=b.getForm().findField("imageID");if(k){var m=n.getValue();n.setValue(m)}else{var l=n.store.reader.jsonData.data[0].id;n.setValue(l)}}}}),listeners:{select:function(l){Ext.getCmp("portals_image").body.dom.innerHTML=i.getHtml()}}});var c=new Ext.form.ComboBox({width:100,displayField:"name",valueField:"id",triggerAction:"all",fieldLabel:lang("bgColor"),editable:false,allowBlank:false,hiddenName:"bgColor",id:"bgColor",mode:"local",store:new Ext.data.Store({autoLoad:true,proxy:new Ext.data.HttpProxy({url:"index.php?app=portals&func=portals&action=setting&task=getBackgroundColor"}),reader:new Ext.data.JsonReader({totalProperty:"total",root:"data",fields:["id","name"]}),listeners:{load:function(){var n=b.getForm().findField("bgColor");if(k){var m=n.getValue();n.setValue(m)}else{var l=n.store.reader.jsonData.data[0].id;n.setValue(l)}}}}),listeners:{select:function(l){Ext.getCmp("portals_image").body.dom.innerHTML=i.getHtml()}}});var h=new Ext.Panel({layout:"form",columnWidth:0.5,border:false,bodyBorder:false,items:[{layout:"column",border:false,bodyBorder:false,defaults:{layout:"form",border:false,bodyBorder:false},items:[{items:[j],columnWidth:0.5},{columnWidth:0.5,items:[c]}]},{width:220,height:135,border:false,bodyBorder:false,id:"portals_image",style:"margin: 15px 0 0 65px;"},{xtype:"fieldset",title:"选择模块控件",autoHeight:true,items:[{xtype:"panel",layout:"table",autoWidth:true,border:false,layoutConfig:{columns:2},defaults:{border:false},items:[{xtype:"button",text:"显示/隐藏选择模块",enableToggle:true,pressed:false,toggleHandler:function(l,m){if(m){Ext.getCmp(i.showHidenID).hide()}else{Ext.getCmp(i.showHidenID).show()}}},{xtype:"displayfield",html:'<font style="color:red; margin-left:10px;">PS:隐藏出现蒙版时，拖动下窗口即可</font>'}]}]}]});var f=new Ext.Panel({layout:"column",border:false,bodyBorder:false,style:"margin: 5px;",height:250,items:[d,h]});this.firstGrid=new Ext.grid.GridPanel({ddGroup:"secondGridDDGroup",cm:new Ext.grid.ColumnModel({columns:[{header:"id",dataIndex:"id",hidden:true},{id:"name",header:lang("chooseModule"),dataIndex:"name"}]}),enableDragDrop:true,autoExpandColumn:"name",store:new Ext.data.Store({autoLoad:true,proxy:new Ext.data.HttpProxy({url:i.baseUrl+"&task=getDesktopList&step=1"+i.portalsID,disableCaching:true}),reader:new Ext.data.JsonReader({root:"data",fields:[{name:"id"},{name:"name"}]})})});this.secondGrid=new Ext.grid.GridPanel({ddGroup:"firstGridDDGroup",cm:new Ext.grid.ColumnModel({columns:[{header:"id",dataIndex:"id",hidden:true},{id:"name",header:lang("selectedModule"),dataIndex:"name"}]}),enableDragDrop:true,autoExpandColumn:"name",enableDragDrop:true,autoExpandColumn:"name",store:new Ext.data.Store({autoLoad:true,proxy:new Ext.data.HttpProxy({url:i.baseUrl+"&task=getDesktopList"+i.portalsID,disableCaching:true}),reader:new Ext.data.JsonReader({root:"data",fields:[{name:"id"},{name:"name"}]})})});var e=new Ext.Panel({width:700,height:400,hidden:false,id:i.showHidenID,defaults:{flex:1,border:false},layout:"hbox",layoutConfig:{align:"stretch"},items:[this.firstGrid,this.secondGrid],tbar:[lang("dragModule")],listeners:{afterrender:function(){var l=i.firstGrid.getView().scroller.dom;var n=new Ext.dd.DropTarget(l,{ddGroup:"firstGridDDGroup",notifyDrop:function(s,r,q){var p=s.dragData.selections;Ext.each(p,s.grid.store.remove,s.grid.store);i.firstGrid.store.add(p);i.firstGrid.store.sort("name","ASC");return true}});var m=i.secondGrid.getView().scroller.dom;var o=new Ext.dd.DropTarget(m,{ddGroup:"secondGridDDGroup",notifyDrop:function(s,r,q){var p=s.dragData.selections;Ext.each(p,s.grid.store.remove,s.grid.store);i.secondGrid.store.add(p);i.secondGrid.store.sort("name","ASC");return true}})}}});var g=function(){var l=new Array();Ext.each(i.secondGrid.store.data.items,function(n,o){l.push(n.json.id)});var m=0;if(k){m=1}if(b.getForm().isValid()){b.getForm().submit({url:i.baseUrl+"&task=submit",method:"POST",params:{mids:l.join(","),edit:m},waitMsg:lang("notice"),success:function(n,o){CNOA.msg.notice2(o.result.msg);CNOA_portals_portals_setting.store.reload();i.mainPanel.close()},failure:function(n,o){CNOA.msg.alert(o.result.msg)}})}};var b=new Ext.form.FormPanel({labelWidth:60,width:700,items:[f,e],buttons:[{text:lang("save"),iconCls:"icon-btn-save",handler:g},{text:lang("close"),handler:function(){i.mainPanel.close()}}]});this.mainPanel=new Ext.Window({title:k?lang("modify"):lang("add"),layout:"form",modal:true,items:[b],listeners:{afterrender:function(){if(k){b.getForm().setValues(k)}Ext.getCmp("portals_image").body.dom.innerHTML=i.getHtml()}}}).show()},getHtml:function(){var b=Ext.getCmp("bgColor").getValue(),a=Ext.getCmp("imageID").getValue(),c=Ext.getCmp("portalsName").getValue();if(!a){a=1;b=1}html="<div style=\"width:220px;height:135px;background:url('resources/portals/images/bg"+b+'.png\') no-repeat;"><img id="image" src="resources/portals/images/'+a+'.png" style="margin:15px 73px 5px 73px;"/><span style="display:block;font-size:18px;font-family:微软雅黑;color:#fff;text-align:center;">'+c+"</span><div>";return html}};CNOA_portals_portals_settingClass=new CNOA.Class.create();CNOA_portals_portals_settingClass.prototype={init:function(){var a=this;this.baseUrl="index.php?app=portals&func=portals&action=setting";this.mainPanel=this.getPortalsGrid()},getPortalsGrid:function(){var d=this;var b=[{name:"id"},{name:"portalsName"},{name:"uids"},{name:"jobIds"},{name:"deptIds"},{name:"imageID"},{name:"mids"},{name:"people"},{name:"dept"},{name:"job"},{name:"order"}];var e=new Ext.grid.CheckboxSelectionModel({singleSelect:false});var a=new Ext.grid.ColumnModel({defaults:{menuDisabled:true},columns:[new Ext.grid.RowNumberer(),e,{header:"id",dataIndex:"id",hidden:true},{header:"uids",dataIndex:"uids",hidden:true},{header:"jobIds",dataIndex:"jobIds",hidden:true},{header:"deptIds",dataIndex:"deptIds",hidden:true},{header:"mids",dataIndex:"mids",hidden:true},{header:"imageID",dataIndex:"imageID",hidden:true},{header:lang("portalsName"),dataIndex:"portalsName"},{header:lang("usePeople"),dataIndex:"people",width:200},{header:lang("useJob"),dataIndex:"job",width:200},{header:lang("useDept"),dataIndex:"dept",width:200},{header:"排序",dataIndex:"order",width:50,editor:new Ext.form.NumberField({nanText:"只能是数字",allowNegative:false,allowDecimals:false,allowBlank:false})}]});this.store=new Ext.data.Store({autoLoad:true,proxy:new Ext.data.HttpProxy({url:d.baseUrl+"&task=getJsonData",disableCaching:true}),reader:new Ext.data.JsonReader({root:"data",fields:b})});var c=new Ext.grid.EditorGridPanel({store:this.store,cm:a,sm:e,tbar:new Ext.Toolbar({items:[{text:lang("refresh"),iconCls:"icon-system-refresh",handler:function(){d.store.reload()}},"-",{text:lang("add"),iconCls:"icon-utils-s-add",handler:function(){new CNOA_portals_portals_editWinClass()}},"-",{text:lang("modify"),iconCls:"icon-utils-s-edit",handler:function(f){var g=c.getSelectionModel().getSelections();if(g.length==0){CNOA.miniMsg.alertShowAt(f,lang("mustSelectOneRow"))}else{if(g.length>1){CNOA.miniMsg.alertShowAt(f,lang("onlyOneItem"))}else{new CNOA_portals_portals_editWinClass(g[0].json)}}}},"-",{text:lang("del"),iconCls:"icon-utils-s-delete",handler:function(){var h=c.getSelectionModel().getSelections();if(h.length==0){CNOA.miniMsg.alertShowAt(btn,lang("mustSelectOneRow"))}else{var g=[];for(var f=0;f<h.length;f++){g.push(h[f].json.id)}d.deletePortals(g.join(","))}}}]}),listeners:{afteredit:function(h){var g=h.field,i=h.record.get("id"),f=h.value;d.updateOrder(i,g,f,d.store)}}});return c},deletePortals:function(a){var b=this;Ext.Ajax.request({url:b.baseUrl+"&task=deletePortals",params:{ids:a},success:function(c){var d=Ext.decode(c.responseText);CNOA.msg.notice2(d.msg);b.store.reload()}})},updateOrder:function(e,d,c,a){var b=this;Ext.Ajax.request({url:b.baseUrl+"&task=updateOrder",params:{id:e,field:d,value:c},success:function(g){var f=Ext.decode(g.responseText);if(f.failure===true){CNOA.msg.alert(f.msg);a.reload()}else{CNOA.msg.notice2(f.msg);a.reload()}}})}};var CNOA_portals_portals_portalsDesktop,CNOA_portals_portals_portalsDesktopClass;CNOA_portals_portals_portalsDesktopClass=new CNOA.Class.create();CNOA_portals_portals_portalsDesktopClass.prototype={init:function(){var _this=this;this.baseUrl="index.php?action=index";this.mainPanel={xtype:"portal",region:"center",border:false,listeners:{afterrender:function(){var code=CNOA.portals.portals.portalsDesktop.code;var portalsID=CNOA.portals.portals.portalsDesktop.portalsID;for(var i=0;i<code.length;i++){eval(code[i]+"_POR = new "+code[i]+"_CLASS("+portalsID+");");eval("Ext.getCmp('portalsID_column_"+portalsID+"_"+i%2+"').add("+code[i]+"_POR.mainPanel);")}}},items:[{columnWidth:0.5,id:"portalsID_column_"+CNOA.portals.portals.portalsDesktop.portalsID+"_0",style:"padding:5px 0 0 5px",items:[]},{columnWidth:0.5,id:"portalsID_column_"+CNOA.portals.portals.portalsDesktop.portalsID+"_1",style:"padding:5px 5px 0 5px",items:[]}]}}};var sm_portals_portals=1;
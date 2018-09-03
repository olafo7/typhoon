var CNOA_doc_api_flow;


var CNOA_doc_api_flow_SIV = setInterval(function(){
	try{
		if(sm_flow_flow == 1){
			clearInterval(CNOA_doc_api_flow_SIV);
			SET_CNOA_doc_api_flow();
		}
	}catch(e){}
}, 50);
/*
deleteFlow
editPanel
recall
repeal
viewFlowEvent
viewFlowProgress
viewFlow2
 */

function SET_CNOA_doc_api_flow(){
	CNOA_doc_api_flow = {
		showFlow : function(value, readOnly){
			try{
				CNOA_flow_flow_user_myflow.showFlow(value, readOnly);
			}catch(e){
				CNOA_flow_flow_user_myflow = new CNOA_flow_flow_user_myflowClass();
				CNOA_flow_flow_user_myflow.showFlow(value, readOnly);
			}
		},
		deleteFlow : function(value){
			try{
				CNOA_flow_flow_user_myflow.deleteFlow(value, function(){
					try{CNOA_doc_send_apply_list.store.reload();}catch(e){}
					try{CNOA_doc_receive_apply_list.store.reload();}catch(e){}
				});
			}catch(e){
				CNOA_flow_flow_user_myflow = new CNOA_flow_flow_user_myflowClass();
				CNOA_flow_flow_user_myflow.deleteFlow(value, function(){
					try{CNOA_doc_send_apply_list.store.reload();}catch(e){}
					try{CNOA_doc_receive_apply_list.store.reload();}catch(e){}
				});
			}
		},
		editPanel : function(value){
			try{
				CNOA_flow_flow_user_myflow.editPanel(value, "doc");
			}catch(e){
				CNOA_flow_flow_user_myflow = new CNOA_flow_flow_user_myflowClass();
				CNOA_flow_flow_user_myflow.editPanel(value, "doc");
			}
		},
		recall : function(value){
			try{
				CNOA_flow_flow_user_myflow.recall(value, function(){
					try{CNOA_doc_send_apply_list.store.reload();}catch(e){}
					try{CNOA_doc_receive_apply_list.store.reload();}catch(e){}
				});
			}catch(e){
				CNOA_flow_flow_user_myflow = new CNOA_flow_flow_user_myflowClass();
				CNOA_flow_flow_user_myflow.recall(value, function(){
					try{CNOA_doc_send_apply_list.store.reload();}catch(e){}
					try{CNOA_doc_receive_apply_list.store.reload();}catch(e){}
				});
			}
		},
		repeal : function(value){
			try{
				CNOA_flow_flow_user_myflow.repeal(value, function(){
					try{CNOA_doc_send_apply_list.store.reload();}catch(e){}
					try{CNOA_doc_receive_apply_list.store.reload();}catch(e){}
				});
			}catch(e){
				CNOA_flow_flow_user_myflow = new CNOA_flow_flow_user_myflowClass();
				CNOA_flow_flow_user_myflow.repeal(value, function(){
					try{CNOA_doc_send_apply_list.store.reload();}catch(e){}
					try{CNOA_doc_receive_apply_list.store.reload();}catch(e){}
				});
			}
		},
		viewFlowEvent : function(value, name){
			try{
				CNOA_flow_flow_user_myflow.viewFlowEvent(value, name);
			}catch(e){
				CNOA_flow_flow_user_myflow = new CNOA_flow_flow_user_myflowClass();
				CNOA_flow_flow_user_myflow.viewFlowEvent(value, name);
			}
		},
		viewFlowProgress : function(value, name){
			try{
				CNOA_flow_flow_user_myflow.viewFlowProgress(value, name);
			}catch(e){
				CNOA_flow_flow_user_myflow = new CNOA_flow_flow_user_myflowClass();
				CNOA_flow_flow_user_myflow.viewFlowProgress(value, name);
			}
		},
		viewFlow2 : function(value, name){
			try{
				CNOA_flow_flow_user_myflow.viewFlow2(value, name);
			}catch(e){
				CNOA_flow_flow_user_myflow = new CNOA_flow_flow_user_myflowClass();
				CNOA_flow_flow_user_myflow.viewFlow2(value, name);
			}
		},
		
		//办理流程
		dealFlow : function(value, name){
			try{
				CNOA_flow_flow_user_dealflow.viewFlow(value, "doc");
			}catch(e){
				CNOA_flow_flow_user_dealflow = new CNOA_flow_flow_user_dealflowClass();
				CNOA_flow_flow_user_dealflow.viewFlow(value, "doc");
			}
		},

		showReaderWindow : function(ulid){
			var gridReader = new CNOA_flow_flow_flowViewReaderClass(ulid);
			var readerPanel = new Ext.Panel({
				border: false,
				autoScroll: true,
				items: [gridReader.grid],
				tbar: ["<span class='cnoa_color_gray'>"+lang('grayNotRead')+"</span>&nbsp;&nbsp;&nbsp;&nbsp;<span class='cnoa_color_red'>红色：表示已阅</span>"]
			});

			var win = new Ext.Window({
				title: lang('checkRecord'),
				layout: "fit",
				width: 686,
				height: makeWindowHeight(490),
				modal: true,
				maximizable: true,
				items: [readerPanel],
				buttons : [
					{
						text : lang('close'),
						iconCls: 'icon-dialog-cancel',
						handler : function() {
							win.close();
						}.createDelegate(this)
					}
				]
			}).show();
		},
		
		//显示分发界面
		dispenseFlow : function(ulid){
			var _this = this;
			var ID_dispenseFlow = Ext.id();
			
			var submit = function(btn){
				var f = form.getForm();
				if(f.isValid()){
					f.submit({
						url: "index.php?app=flow&func=flow&action=user&task=dispenseFlow",
						waitTitle: lang('notice'),
						method: 'POST',
						waitMsg: lang('waiting'),
						params: {ulid: ulid},
						success: function(form, action) {
							CNOA.msg.alert(action.result.msg, function(){
								win.close();
							});
						}.createDelegate(this),
						failure: function(form, action) {
							CNOA.msg.alert(action.result.msg);
						}.createDelegate(this)
					});
				}else{
					CNOA.miniMsg.alertShowAt(btn, lang('didNotChoose'), 30);
				}
			}
			
			var form = new Ext.form.FormPanel({
				autoWidth: true,
				border: false,
				hideBorders: true,
				labelWidth: 70,
				labelAlign: 'right',
				waitMsgTarget: true,
				bodyStyle: "padding:10px;",
				items: [
					{
						xtype: "textarea",
						style: 'margin: 0; padding: 0;',
						height: 65,
						width: 365,
						readOnly: true,
						allowBlank: false,
						fieldLabel: lang('distributedtoWho'),
						name: "allUserNames"
					},
					{
						xtype: "hidden",
						name: "allUids"
					},
					{
						xtype: "btnForPoepleSelector",
						style: 'margin: 0; padding: 0',
						autoWidth: true,
						dataUrl: "index.php?app=flow&func=flow&action=user&task=getAllUserListsInPermitDeptTree",
						style: "margin-left:75px;",
						text: lang('selectPeople'),
						listeners: {
							"selected" : function(th, data){
								var names = new Array();
								var uids = new Array();
								if (data.length>0){
									for (var i=0;i<data.length;i++){
										names.push(data[i].uname);
										uids.push(data[i].uid);
									}
								}
								form.getForm().findField("allUserNames").setValue(names.join(","));
								form.getForm().findField("allUids").setValue(uids.join(","));
							},		
							"onrender" : function(th){
								th.setSelectedUids(form.getForm().findField("allUids").getValue().split(","));
							}
						}
					}
				]
			});
			
			var win = new Ext.Window({
				title: lang('distributedWorkflow'),
				width: 475,
				height: 180,
				layout: "fit",
				modal: true,
				maximizable: false,
				resizable: false,
				items: [form],
				buttons: [
					{
						text: lang('distribute'),
						id: ID_dispenseFlow,
						handler: function(btn){
							submit(btn);
						}
					},
					{
						text: lang('close'),
						handler: function(){
							win.close();
						}
					}
				]
			}).show();
		}
	}
}


//初始化
var tForm, tBase, tStep, tEvent, tRead;
var init = function(){
	var form	= tForm = $("#wf_export_form");
	var base	= tBase = $("#wf_export_info");
	var step	= tStep = $("#wf_export_step");
	var event	= tEvent = $("#wf_export_event");
	var read	= tRead = $("#wf_export_read");
	
	this.bindEvent = function(frameWindow){
		
		try {
			var swin = $("#wf_frame").get(0).contentWindow.window;
			swin.resizeContentWidth(CNOA.wf.use_export.printwidth);
		}catch(e){}
		
		var baseH	= frameWindow.$("#base_header");
		var baseB	= frameWindow.$("#base_body");
		var formH	= frameWindow.$("#form_header");
		var formB	= frameWindow.$("#form_body");
		var stepH	= frameWindow.$("#step_header");
		var stepB	= frameWindow.$("#step_body");
		var eventH	= frameWindow.$("#event_header");
		var eventB	= frameWindow.$("#event_body");
		var readH	= frameWindow.$("#read_header");
		var readB	= frameWindow.$("#read_body");
		
		form.click(function(){
			if($(this).attr("checked")){
				//显示
				formH.show();
				formB.show();
			}else{
				//隐藏
				formH.hide();
				formB.hide();
			};
		});
		
		base.click(function(){
			if($(this).attr("checked")){
				//显示
				baseH.show();
				baseB.show();
			}else{
				//隐藏
				baseH.hide();
				baseB.hide();
			};
		});
		
		step.click(function(){
			if($(this).attr("checked")){
				//显示
				stepH.show();
				stepB.show();
			}else{
				//隐藏
				stepH.hide();
				stepB.hide();
			};
		});
		
		event.click(function(){
			if($(this).attr("checked")){
				//显示
				eventH.show();
				eventB.show();
			}else{
				//隐藏
				eventH.hide();
				eventB.hide();
			};
		});
		
		read.click(function(){
			if($(this).attr("checked")){
				//显示
				readH.show();
				readB.show();
			}else{
				//隐藏
				readH.hide();
				readB.hide();
			};
		});
	};
};

var panel = new Ext.Panel({
	hideBorders: true,
	border: false,
	region: "center",
	autoScroll: false,
	html: '<iframe id="wf_frame" scrolling=auto frameborder=0 width=100% height=100% src="index.php?app=wf&func=flow&action=use&modul=todo&uFlowId='+CNOA.wf.use_export.uFlowId+'&flowId='+CNOA.wf.use_export.flowId+'&stepId='+CNOA.wf.use_export.stepId+'&task=getExportFlowInfo"></iframe>',
	tbar: [
		'<label><input type="checkbox" name="showInfo" id="wf_export_info" />' + lang('flowInformation') + '</label>',
		'<label><input type="checkbox" name="showform" id="wf_export_form" checked="checked"/>' + lang('processForm') + '</label>',
		'<label><input type="checkbox" name="showStep" id="wf_export_step" />' + lang('flowStep') + '</label>',
		'<label><input type="checkbox" name="showEvent" id="wf_export_event" />' + lang('flowEvent') + '</label>',
		'<label><input type="checkbox" name="showRead" id="wf_export_read" />' + lang('flowReview') + '</label>',
		"->",
		{
			text: lang('startPrint'),
			cls: "x-btn-over",
			listeners: {
				"mouseout" : function(btn){
					btn.addClass("x-btn-over");
				}
			},
			handler:function(){
				document.getElementById('wf_frame').contentWindow.focus();
				document.getElementById('wf_frame').contentWindow.print();
			}
		},
		{
			text: lang('exportTo') + "..",
			cls: "x-btn-over",
			style: "margin:0 10px;",
			listeners: {
				"mouseout" : function(btn){
					btn.addClass("x-btn-over");
				}
			},
			menu : {
				items: [
					{
						iconCls:'x-edit-picture',
						text: lang('exportPic'),
						handler: function(){
							exportFlow('image');
						}.createDelegate(this)
					},
					{
						iconCls:'document-html',
						text: lang('exportWebDoc') + '(html)',
						handler: function(){
							exportFlow('html');
						}.createDelegate(this)
					},
					{
						iconCls:'document-word',
						text: lang('exportToWordDoc'),
						handler: function(){
							exportFlow('word');
						}.createDelegate(this)
					},
					"-",
					{
						text: lang('dumpNetworkDrive'),
						handler: function(){
							exportFlow('image', true);
						}
					}
				]
			}
		},
		{
			text : lang('close'),
			cls: "x-btn-over",
			listeners: {
				"mouseout" : function(btn){
					btn.addClass("x-btn-over");
				}
			},
			iconCls: 'icon-dialog-cancel',
			handler : function(button, event) {
				window.close();
			}.createDelegate(this)
		}
	]/*,
	bbar: [
		'打印宽度设置:',
		{
			xtype:'slider',
			id:'ID_slider',
			value: CNOA.wf.use_export.printwidth,
			width:150,
			minValue: 0,
			maxValue: 200,
			listeners:{
				change:function(th, newValue, oldValue){
					try {
						var swin = $("#wf_frame").get(0).contentWindow.window;
						swin.resizeContentWidth(newValue);
					}catch(e){}
				}
			}
		},
		{
			text:'保存设置',
			style: 'margin-left:10px',
			cls: "x-btn-over",
			listeners: {
				"mouseout" : function(btn){
					btn.addClass("x-btn-over");
				}
			},
			handler:function(){
				var width = Ext.getCmp('ID_slider').getValue();
				Ext.Ajax.request({
				    url: "index.php?app=wf&func=flow&action=use&modul=done&task=saveprint",
				    method: 'POST',
					params:{width:width},
				    success: function(r) {
				        var result = Ext.decode(r.responseText);
				        if(result.success === true){

				        }else{
				            CNOA.msg.alert(result.msg, function(){});
				        }
				    }
				});
			}
		}
	],
	listeners: {
		afterrender : function(){
			
		}
	}*/
});

function getMyHtml(obj) {
    var box = $('<div></div>');
    for (var i = 0; i < obj.length; i ++) {
        box.append($(obj[i]).clone());
    }
    return box.html();
}

var exportFlow = function(target, toDisk){
	var tFormV = tForm.attr("checked"),
		tBaseV = tBase.attr("checked"),
		tStepV = tStep.attr("checked"),
		tEventV = tEvent.attr("checked"),
		tReadV = tRead.attr("checked"),
		html = "";

	mainPanel.getEl().mask(lang('waiting'));

	if(target == 'html' || target == 'word'){
		$("#hiddenpage").remove();
	
		var showedpage = $(getMyHtml($("#wf_frame").get(0).contentWindow.$("#wf_export_total")));

		var hiddenpage = $("<div id='hiddenpage' style='display:none;'></div>");
			hiddenpage.attr('style', showedpage.attr('style'));
			hiddenpage.appendTo("body");
		
		$(showedpage.html()).appendTo(hiddenpage);
		
		hiddenpage.children("div").each(function(){
			var th = $(this),
				id = th.attr('id'),
				newid = id + "2";
		
			th.attr('id', newid);
			if(th.css('display') == 'none'){
				th.remove();
			}
		});
		html = hiddenpage.attr('outerHTML');
	}
		
	var uFlowId = CNOA.wf.use_export.uFlowId;

	var url = "index.php?app=wf&func=flow&action=use&modul=done&task=exportWord&target="+target+"&uFlowId="+uFlowId+"&flowId="+CNOA.wf.use_export.flowId+"&stepId="+CNOA.wf.use_export.stepId;
	if(toDisk){
		url += "&toDisk=1"
	}
	Ext.Ajax.request({
		url: url,
		method: 'POST',
		params: {
			showForm: tFormV,
			showBase: tBaseV,
			showStep: tStepV,
			showEvent: tEventV,
			showRead: tReadV,
			html: html
		},
		success: function(r) {
			var result = Ext.decode(r.responseText);
			if(result.success === true){
				if(toDisk){
					var dt = Ext.decode(result.msg);
					new Ext.MoveToDisk({
						data: dt.file,
						name: dt.name,
						from: 'wfExport'
					});
				}else{
					ajaxDownload(result.msg);
				}
			}else{
				CNOA.msg.alert(result.msg, function(){});
			}

			mainPanel.getEl().unmask();
		}
	});
};

/*

*/

var mainPanel;
Ext.onReady(function() {
	mainPanel = new Ext.Viewport({
		layout:'border',
		border:false,
		items: [panel]
	});
	mainPanel.doLayout();
})
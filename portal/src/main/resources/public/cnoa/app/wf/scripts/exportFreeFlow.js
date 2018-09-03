/*  独立文件，不加入压缩  */
//格式化宽度

var resizWidth = function(width){
	$("#form_body").find("table,div,tr,td,input,textarea,select,span").each(function(){
		if($(this).attr('owidth') > width){
			$(this).width(width);
		}else{
			$(this).width($(this).attr('owidth'));
		};
	})
}

var storeOwidth = function(){
	$("#form_body").find("table,div,tr,td,input,textarea,select,span").each(function(){
		$(this).attr('owidth', $(this).width());
	})
}

var initRadioCheckboxShowHide = function(){
	var els = $(".wf_form_content input:hidden:[showhide=true]");
	els.each(function(){
		var shows, hides, stmp, htmp;
		
		shows = stmp = $(this).attr('display').split(",");
		hides = htmp = $(this).attr('undisplay').split(",");
		
		if($(this).attr('fromtype')=='checkbox' && $(this).attr('checkboxck') == '0'){
			hides = stmp;
			shows = htmp;
		}
		
		try{
			for(var i=0;i<shows.length;i++){
				$(".wf_form_content span[class=group][oname="+shows[i]+"]").attr("showhide", "show").show();
			}
		}catch(e){}
		try{
			for(var i=0;i<hides.length;i++){
				$(".wf_form_content span[class=group][oname="+hides[i]+"]").attr("showhide", "hide").hide();
			}
		}catch(e){}
	});
};
	

//初始化
var init = function(){
	var total = $("#wf_export_total");
		total.css("width", 640+(CNOA.wf.use_export.printwidth-100)*6);
	resizWidth(640+(CNOA.wf.use_export.printwidth-100)*6);
	
	var form	= $("#wf_export_form");
	var word	= $("#wf_export_word");
	var base	= $("#wf_export_info");
	var step	= $("#wf_export_step");
	var event	= $("#wf_export_event");
	var read	= $("#wf_export_read");
	
	var baseH	= $("#base_header");
	var baseB	= $("#base_body");
	var formH	= $("#form_header");
	var formB	= $("#form_body");
	var wordA	= $("#word_body");
	var stepH	= $("#step_header");
	var stepB	= $("#step_body");
	var eventH	= $("#event_header");
	var eventB	= $("#event_body");
	var readH	= $("#read_header");
	var readB	= $("#read_body");
	
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

	word.click(function(){
		if($(this).attr("checked")){
			//显示
			try{
				jQuery('#CNOA_WEBOFFICE').parent().css("visibility", "visible");
			}catch(e){}
		}else{
			//隐藏
			try{
				jQuery('#CNOA_WEBOFFICE').parent().css("visibility", "hidden");
			}catch(e){}
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

//打印
function printAreaWf(DomID, PrintTitle){
	var ele = document.getElementById(DomID);
	
	PrintTitle = PrintTitle == undefined ? " " : PrintTitle;
	
	try{
		var fele = document.getElementById("printArea_"+DomID);
		fele.parentElement.removeChild(fele);
	}catch(e){}

	var windowAttr = "location=yes,statusbar=no,directories=no,menubar=no,titlebar=no,toolbar=no,dependent=no";
	windowAttr += ",width=800,height=600";
	windowAttr += ",resizable=yes,screenX=0,screenY=0,personalbar=no,scrollbars=no";
	var printWindow = window.open( "", "_blank",  windowAttr);
	printWindow.doc = printWindow.document;

	var head = "<head><title>"+PrintTitle+"</title>";
	var S = Ext.query("link[rel=stylesheet],[type=text/css]");
	Ext.each(S, function(v, i, c){
		head += '<link type="text/css" rel="stylesheet" href="' + v.attributes['href'].nodeValue + '" >';
	});
	head += "</head>";
	var body = "<body onload='document.title=\""+PrintTitle+"\";window.print();window.close();' scroll='yes' style='overflow:auto;'>";
	
	var eleclass;
	try{
		eleclass = 'class="' + ele.attributes['class'].nodeValue + '"';
	}catch (e){
		eleclass = "";
	}

	body += '<div '+eleclass+'>' + ele.outerHTML + '</div>';
	
	body += "</body>";

	printWindow.document.open();
	printWindow.document.write("<html>" + head + body + "</html>");
	printWindow.document.close();

	printWindow.focus();
}

var formPanel = new Ext.form.FormPanel({
	fileUpload: false,
	hideBorders: true,
	border: false,
	region: "center",
	autoScroll: true,
	layout: 'htmlform',
	defaults: {
		xtype: 'displayfield'
	},
	layoutConfig: {
		cache: false,
		template : CNOA.wf.use_export.tplSort == 3 ? 'app/wf/tpl/default/flow/use/export_freeflow_form_tplSort3.tpl.html' : 'app/wf/tpl/default/flow/use/export_freeflow_form.tpl.html',
		templateData: {
		}
	},
	items: [
		{name: 'flowNumber'},
		{name: 'flowName'},
		{name: 'status'},
		{name: 'uname'},
		{name: 'posttime'},
		{name: 'reason', cls: 'wf-reason'},
		{name: 'level'}
	],
	listeners: {
		afterrender: function(){
			var flowId	 = CNOA.wf.use_export.flowId;
			var uFlowId	 = CNOA.wf.use_export.uFlowId;
			var flowType = CNOA.wf.use_export.flowType;
			var tplSort	 = CNOA.wf.use_export.tplSort;
			var stepId	 = CNOA.wf.use_export.stepId;
			
			var baseUrl = "index.php?app=wf&func=flow&action=use&modul=todo"+"&uFlowId="+uFlowId+"&flowId="+flowId;
			var baseURI = location.href.substr(0, location.href.lastIndexOf("/")+1);
			
			setTimeout(function(){			
				if(flowType == 0){
					if(tplSort != 0){
						try{
							var webObj = document.getElementById("CNOA_WEBOFFICE");
							if(tplSort == 1 || tplSort == 3){
								webObj.LoadOriginalFile(baseURI + baseUrl + "&task=ms_loadTemplateFile&tplSort="+tplSort, "doc");
							}else if(tplSort == 2){
								webObj.LoadOriginalFile(baseURI + baseUrl + "&task=ms_loadTemplateFile&tplSort="+tplSort, "xls");
							}
							webObj.ShowRevisions(0);
							webObj.ProtectDoc(1, 2, Math.random());
							webObj.ShowToolBar = false;
						}catch(e){}
					}
				}else{
					if(tplSort == 0 || tplSort == 3){
						Ext.Ajax.request({
							url: "index.php?app=wf&func=flow&action=use&modul=todo&task=loadUflowInfo",
							method: 'POST',
							params: {type: "done", flowId: flowId, uFlowId: uFlowId, stepId: stepId, flowType: flowType, tplSort: tplSort},
							success: function(r) {
								var result = Ext.decode(r.responseText);
								if(result.success === true){
									if (CNOA.wf.use_export.tplSort == 0){
										Ext.fly('wf_form_content').update(result.data.htmlFormContent + '<div style="clear:both;"></div>');
									} else {
										Ext.fly('wf_form_content2').update(result.data.htmlFormContent + '<div style="clear:both;"></div>');
									}
									storeOwidth();
									resizWidth(640+(CNOA.wf.use_export.printwidth-100)*6);
								}else{
									CNOA.msg.alert(result.msg, function(){});
								}
							}
						});
					}else{
						try{
							var webObj = document.getElementById("CNOA_WEBOFFICE");
							if(tplSort == 1){
								webObj.LoadOriginalFile(baseURI + baseUrl + "&task=ms_loadTemplateFile&tplSort="+tplSort, "doc");
							}else if(tplSort == 2){
								webObj.LoadOriginalFile(baseURI + baseUrl + "&task=ms_loadTemplateFile&tplSort="+tplSort, "xls");
							}
							webObj.ShowRevisions(0);
							webObj.ProtectDoc(1, 2, Math.random());
							webObj.ShowToolBar = false;
						}catch(e){}
					}
				}
			}, 200);
			
			//获取流程步骤信息
			var f = formPanel.getForm();
			
			f.load({
				url: "index.php?app=wf&func=flow&action=use&modul=done&task=printList",
				method:'POST',
				params: {uFlowId: CNOA.wf.use_export.uFlowId, flowId: CNOA.wf.use_export.flowId, flowType: flowType, tplSort: tplSort},
				success: function(form, action){
					$(action.result.step).insertAfter($("#step_body").find("tr:last"));
					$(action.result.event).insertAfter($("#event_body").find("tr:last"));
					$(action.result.read).insertAfter($("#read_body").find("tr:last"));
					
				},
				failure: function(form, action){
					CNOA.msg.alert(action.result.msg, function(){
						
					});
				}.createDelegate(this)
			});

			//公文类 HTML + WORD
			if(CNOA.wf.use_export.tplSort == 3){
				$.post("index.php?app=wf&func=flow&action=use&modul=todo&task=loadFormHtmlView", {type: "done", flowId: flowId, uFlowId: uFlowId, stepId: stepId}, function(data){
					$('#wf_form_content').html(data.data.formHtml + '<div style="clear:both;"></div>');
					storeOwidth();
					
					var pageset = Ext.decode(data.pageset);
					
					
					var page = pageset.pageSize;
					var width = 794;
					var height = 1123;
					//设置纸张大小
					switch(page){
						case 'a1page' :
							if(pageset.pageDir == 'lengthways'){
								width = 2245;
								height = 3174;
							}else{
								width = 3174;
								height = 2245;
							}
						break;
						case 'a2page' :
							if(pageset.pageDir == 'lengthways'){
								width = 1587;
								height = 2245;
							}else{
								width = 2245;
								height = 1587;
							}
						break;
						case 'a3page' :
							if(pageset.pageDir == 'lengthways'){
								width = 1123;
								height = 1587;
							}else{
								width = 1587;
								height = 1123;
							}
						break;
						case 'a4page' :
							if(pageset.pageDir == 'lengthways'){
								width = 794;
								height = 1123;
							}else{
								width = 1123;
								height = 794;
							}
						break;
						case 'a5page' :
							if(pageset.pageDir =='lengthways'){
								width = 559;
								height = 794;
							}else{
								width = 794;
								height = 559;
							}
						break;
					}
					
					//设置边距大小
					var up = Math.ceil(pageset.pageUp*3.4);
					var down = Math.ceil(pageset.pageDown*3.4);
					var left = Math.ceil(pageset.pageLeft*3.4);
					var right = Math.ceil(pageset.pageRight*3.4);
				
					var padding = up+'px '+right+'px '+down+'px '+left+'px';
					
					pageBody = $("#wf_export_total");
					
					pageBody.css('padding', padding);
						   
					pageBody.width(width-left-right);
					
					if(Ext.isIE){
						
					}
					
					//$("#wf_export_total").css('padding',7);
					//签章显示
					try {
						var sealData = $(".wf_form_content input[sealstoredata=true]");
						if (sealData.length > 0) {
							//加载JS
							loadJs('app/wf/scripts/signature.electron.js', true, function(){
								CNOA_wf_signature_electron = new CNOA_signature_electronClass();
								CNOA_wf_signature_electron.SetSealValue(sealData);
							});
						}
					}catch(e){}

					initRadioCheckboxShowHide();
					
				}, "json");
			}
		}
	},
	tbar: [
		'<label><input type="checkbox" name="showInfo" id="wf_export_info" />' + lang('flowInformation') + '</label>',
		'<label><input type="checkbox" name="showform" id="wf_export_form" checked="checked"/>' + lang('processForm') + '</label>',
		(CNOA.wf.use_export.tplSort==3?'<label><input type="checkbox" name="showWord" id="wf_export_word" checked="checked"/>' + lang('textContent') + '</label>':''),
		'<label><input type="checkbox" name="showStep" id="wf_export_step" />' + lang('flowStep') + '</label>',
		'<label><input type="checkbox" name="showEvent" id="wf_export_event" />' + lang('flowEvent') + '</label>',
		'<label><input type="checkbox" name="showRead" id="wf_export_read" />' + lang('flowReview') + '</label>',
		"->",
		{
			text: lang('refresh'),
			cls: "x-btn-over",
			listeners: {
				"mouseout" : function(btn){
					btn.addClass("x-btn-over");
				}
			},
			handler:function(){
				location.reload();
			}
		},'-',
		{
			text: lang('printFlowInformation'),
			cls: "x-btn-over",
			listeners: {
				"mouseout" : function(btn){
					btn.addClass("x-btn-over");
				}
			},
			handler:function(){
				printAreaWf("wf_export_total", lang('printFlowInformation'));
			}
		},'-',
		{
			text: lang('printText'),
			cls: "x-btn-over",
			hidden: CNOA.wf.use_export.tplSort ==0 ? true : false,
			listeners: {
				"mouseout" : function(btn){
					btn.addClass("x-btn-over");
				}
			},
			handler:function(){
				var webObj = document.getElementById('CNOA_WEBOFFICE');
				webObj.ProtectDoc(0, 0, Math.random());
				webObj.ShowRevisions(0);
				webObj.ProtectDoc(1, 2, Math.random());
				try{
					webObj.PrintDoc(1);
				}catch(e){}
				//printAreaWf("CNOA_WEBOFFICE", "打印正文");
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
					var total = $("#wf_export_total");
					total.css("width", 640+(newValue-100)*6);
					resizWidth(640+(newValue-100)*6);
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
	]*/
});

var exportFlow = function(target){
	$("#hiddenpage").remove();
	
	var showedpage = $("#wf_export_total");
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
	var html = hiddenpage.attr('outerHTML');
	
	var uFlowId = CNOA.wf.use_export.uFlowId;
	Ext.Ajax.request({
		url: "index.php?app=wf&func=flow&action=use&modul=done&task=exportWord&target="+target+"&uFlowId="+uFlowId,
		method: 'POST',
		params: {html: html},
		success: function(r) {
			var result = Ext.decode(r.responseText);
			if(result.success === true){
				ajaxDownload(result.msg);
			}else{
				CNOA.msg.alert(result.msg, function(){});
			}
		}
	});
	$("#hiddenpage").remove();
};

Ext.onReady(function() {
	var mainPanel = new Ext.Viewport({
		layout:'border',
		border:false,
		items: [formPanel]
	});
	mainPanel.doLayout();
	init();

	$("#wf_form_content2").html(CNOA.webObject.getWebOffice("100%", "100%", ''));
		
		/*
	var baseURI = location.href.substr(0, location.href.lastIndexOf("/")+1);
	try{
		var webObj = document.getElementById("CNOA_WEBOFFICE");
		//webObj.OptionFlag |= 128;
		webObj.ShowToolBar = false;
		setTimeout(function(){
			webObj.LoadOriginalFile(baseURI + "<?=$GLOBALS['URL']?>", "<?=$GLOBALS['DOCTYPE']?>");
			webObj.OptionFlag &= 0xff7f;
			webObj.LoadOriginalFile(baseURI + "<?=$GLOBALS['URL']?>", "<?=$GLOBALS['DOCTYPE']?>");
			webObj.SetTrackRevisions(<?=$GLOBALS['RECORD']?>);
			webObj.ShowRevisions(<?=$GLOBALS['RECORD']?>);
			webObj.setCurrUserName("<?=$GLOBALS['TRUENAME']?>");
		}, 500)
		//webObj.SetToolBarButton2("Menu Bar",1,0);//隐藏2003文件菜单
	}catch(e){}
	*/
})
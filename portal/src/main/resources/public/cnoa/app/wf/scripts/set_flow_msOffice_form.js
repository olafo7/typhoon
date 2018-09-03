/**
 * 表单设计
 * 此文件已经废弃，程序中并未发现此文件的调用
 */
var CNOA_wf_set_flow_makeMsofficeFormClass, CNOA_wf_set_flow_makeMsofficeForm;
CNOA_wf_set_flow_makeMsofficeFormClass = CNOA.Class.create();
CNOA_wf_set_flow_makeMsofficeFormClass.prototype = {
	init: function(flowId, tplSort){
		var _this = this;
		
		var box = Ext.getBody().getBox();
		var w	= box.width - 60;
		var h	= box.height - 60;
		
		this.flowId		= flowId;
		this.tplSort	= tplSort;
		this.title 		= this.tplSort == 1 ? "Word表单设计" : "Excel表单设计";  
		this.baseUrl = "index.php?app=wf&func=flow&action=set&modul=flow&flowId="+_this.flowId+"&tplSort="+_this.tplSort+"&"+getSessionStr();
		this.baseURI = location.href.substr(0, location.href.lastIndexOf("/")+1);
	
		//this.webofficeHtml  = '<object id="CNOA_WEBOFFICE" height="768" width="100%" style="left: 0px; TOP: 0px" classid="clsid:E77E049B-23FC-4DB8-B756-60529A35FAD5" codebase="resources/weboffice/v6.0.5.0.cab#version=6,0,5,0">';
		//this.webofficeHtml  = '<object id="CNOA_WEBOFFICE" height="768" width="100%" style="left: 0px; TOP: 0px" clsid="{E77E049B-23FC-4DB8-B756-60529A35FAD5}" progid="WEBOFFICE.WebOfficeCtrl.1" TYPE="application/x-itst-activex">';
		//this.webofficeHtml += '<param name="_ExtentX" value="6350">';
		//this.webofficeHtml += '<param name="_ExtentY" value="6350">';
		//this.webofficeHtml += '要显示模板内容，请切换到IE浏览器下查看';
		//this.webofficeHtml += '</object>';

		this.webofficeHtml = CNOA.webObject.getWebOffice("100%", 768, 'left: 0px; TOP: 0px');
		
		this.ID_officeHtml	= Ext.id();
		this.ID_dealStep	= Ext.id();
		this.ID_dealStepUid = Ext.id();
		this.ID_getDealName = Ext.id();
		
		this.baseField = [
			{
				xtype: "fieldset",
				title: lang('templateContent'),
				items: [
					{
						xtype: 'panel',
						id: _this.ID_officeHtml,
						html: this.webofficeHtml,
						listeners: {
							afterrender : function(){
								try{
									var webObj = document.getElementById("CNOA_WEBOFFICE");
									webObj.OptionFlag &= 0xff7f;
									if(_this.tplSort == 1){
										webObj.LoadOriginalFile(_this.baseURI + _this.baseUrl + "&task=ms_loadTemplateFile&flowId="+_this.flowId, "doc");
									}else{
										webObj.LoadOriginalFile(_this.baseURI + _this.baseUrl + "&task=ms_loadTemplateFile&flowId="+_this.flowId, "xls");
									}
									webObj.ShowToolBar = false;
								}catch(e){}
							}
						}
					}
				]
			}
		];
		
		this.form = new Ext.form.FormPanel({
			labelWidth: 120,
			labelAlign: 'right',
			region : "center",
			waitMsgTarget: true,
			layout: "fit",
			items:[
				{
					xtype: "panel",
					bodyStyle: "padding:10px;",
					border: false,
					autoScroll : true,
					region: "center",
					items: [this.baseField]
				}
			],
			tbar: [
				lang('editTemp') + "： ",
				{
					text: lang('save'),
					iconCls : "icon-btn-save",
					handler : function(){
						_this.submitMsOfficeData(_this.tplSort);
					}
				},
				{
					text : lang('close'),
					iconCls: 'icon-dialog-cancel',
					style: "margin-left:5px",
					handler : function(){
						_this.mainPanel.close();
					}
				}
			]
		});
		
		this.mainPanel = new Ext.Window({
			title: this.title,
			layout: 'fit',
			width: w,
			height: h,
			modal: true,
			maximizable: true,
			maximized:true,
			resizable: true,
			style: 'background-color:#fff;',
			autoScroll: true,
			items: [this.form]
		}).show();
	},
	
	submitMsOfficeData : function(type){
		var _this = this;
		
		try{
			var webObj = document.getElementById("CNOA_WEBOFFICE");
			var returnValue;
			webObj.HttpInit();
			webObj.HttpAddPostString("flowId", _this.flowId);
			if(type == 1){
				webObj.HttpAddPostCurrFile("msOffice", "");
			}else{
				webObj.HttpAddPostCurrFile("msOffice", "");
			}
			returnValue = webObj.HttpPost(_this.baseURI + _this.baseUrl + "&task=ms_submitMsOfficeData" +"&type="+type);
			CNOA.msg.alert(lang('saved'));
			_this.mainPanel.close();
		}catch(e){
			alert("异常\r\nError:"+e+"\r\nError Code:"+e.number+"\r\nError Des:"+e.description);
		}
	}
}
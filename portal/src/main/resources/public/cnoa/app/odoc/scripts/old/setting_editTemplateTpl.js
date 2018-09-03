var CNOA_odoc_setting_editTemplateTpl, CNOA_odoc_setting_editTemplateTplClass;

CNOA_odoc_setting_editTemplateTplClass = CNOA.Class.create();
CNOA_odoc_setting_editTemplateTplClass.prototype = {
	init: function(){
		var _this = this;
		
		this.id = CNOA.odoc.templateid;
		
		
		this.baseUrl = "index.php?app=odoc&func=setting&action=template";
		this.baseURI = location.href.substr(0, location.href.lastIndexOf("/")+1);

		/*
		this.webofficeHtml  = '<object id="CNOA_WEBOFFICE" height="100%" width="100%" classid="clsid:E77E049B-23FC-4DB8-B756-60529A35FAD5" codebase="'+this.baseURI+'resources/weboffice/v6.0.5.0.cab#version=6,0,5,0">';
		this.webofficeHtml += '<param name="_ExtentX" value="6350">';
		this.webofficeHtml += '<param name="_ExtentY" value="6350">';
		this.webofficeHtml += '</object>';
		*/

		this.webofficeHtml = CNOA.webObject.getWebOffice("100%", 768, 'left: 0px; TOP: 0px');

		var noietip = "";
		if(!Ext.isIE){
			noietip = "<span class='cnoa_color_red'>编辑红头模板文件需要使用IE浏览器，请切换到IE浏览器再试。</span>";
		}

		this.tplPanel = new Ext.Panel({
			border: false,
			region: "center",
			html: this.webofficeHtml,
			tbar: [
				"编辑红头模板： ",
				{
					text: lang('save'),
					iconCls: 'icon-btn-save',
					handler : function(){
						_this.submitForm();
					}
				},'-',
				//刷新
				{
					text:lang('refresh'),
					iconCls:'icon-system-refresh',
					handler:function(button, event){
						location.reload();
					}
				},noietip,'->',
				//关闭
				{
					text : lang('close'),
					iconCls: 'icon-dialog-cancel',
					handler : function() {
						window.close();
					}.createDelegate(this)
				}
			]
		});
		
		this.mainPanel = new Ext.Viewport({
			collapsible: false,
			hideBorders: true,
			border: false,
			layout: 'border',
			autoScroll: false,
			items: [this.tplPanel]
		});
	},

	submitForm : function() {
		//this.formPanel.getForm().findField('content').syncValue(); 	
		try{
			var webObj = document.getElementById("CNOA_WEBOFFICE");
			var returnValue;
			webObj.HttpInit();
			webObj.HttpAddPostString("id", this.id);
			webObj.HttpAddPostCurrFile("msOffice", "");
			returnValue = webObj.HttpPost(this.baseURI + this.baseUrl + "&task=saveDocTpl");
			if("1" == returnValue){
				alert("操作成功，请返回发文模板界面继续操作！");
				try{
					opener.CNOA_odoc_setting_editTemplate.reloadDocTpl();
				}catch(e){}
				//window.close();
			}else if("0" == returnValue)
				alert("操作失败");
		}catch(e){
			alert("异常\r\nError:"+e+"\r\nError Code:"+e.number+"\r\nError Des:"+e.description);
		}

	}
}


Ext.onReady(function(){
	CNOA_odoc_setting_editTemplateTpl = new CNOA_odoc_setting_editTemplateTplClass();
	CNOA_odoc_setting_editTemplateTpl.mainPanel.doLayout();
});
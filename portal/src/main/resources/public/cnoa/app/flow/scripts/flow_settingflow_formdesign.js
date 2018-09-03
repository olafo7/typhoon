function CheckForm(){
	var oEditor = CKEDITOR.instances['FORM_CONTENT'];
	if (oEditor.mode == 'wysiwyg'){
		var FORM_HTML = oEditor.document.$.body.innerHTML;
	
		if(FORM_HTML == ""){
			alert("表单内容不能为空！");
			return (false);
		}
		return (true);
	}else{
		CNOA.msg.alert("请先切换到设计模式界面再保存");
		return false;
	}
}

function getContentText(){
	if(CheckForm()){
		//var FCK = FCKeditorAPI.GetInstance('FORM_CONTENT');
		//return FCK.GetXHTML(true);
		
		var oEditor = CKEDITOR.instances['FORM_CONTENT'];
		return oEditor.getData();
	}else{
		return false;
	}
}
	

function ExecuteCommand(commandName) {
	var oEditor = CKEDITOR.instances['FORM_CONTENT'];

	if (oEditor.mode == 'wysiwyg'){
		// Execute the command.
		// http://docs.cksource.com/ckeditor_api/symbols/CKEDITOR.editor.html#execCommand
		oEditor.execCommand(commandName);

	}else{
		CNOA.msg.alert("请先切换到设计模式界面");
	}
}


var CNOA_flow_flow_settingformdesignClass, CNOA_flow_flow_settingformdesign;

CNOA_flow_flow_settingformdesignClass = CNOA.Class.create();
CNOA_flow_flow_settingformdesignClass.prototype = {
	init: function(){
		var _this = this;

		this.leftPanel = new Ext.Panel({
			border: false,
			region: 'west',
			width: 110,
			layout: "fit",
			//autoScroll: true,
			items: [
				{
					xtype: "panel",
					border: false,
					bodyStyle: "background-color:#E7E7E7",
					layout: {
						type:'vbox',
						padding:'5',
						align:'stretch'
					},
					defaults:{
						xtype:'button',
						margins: '0 0 5 0',
						//iconAlign: 'left',
						scale: 'medium'
					},
					items:[
						{
							iconCls: "icon-form-textfield",
							text: '单行文本框',
							handler: function(){
								ExecuteCommand('textfield');
							}
						},
						{
							iconCls: "icon-form-textarea",
							text: '多行文本框',
							handler: function(){
								ExecuteCommand('textarea');
							}
						},
						{
							iconCls: "icon-form-combobox",
							text: '下拉列表框',
							handler: function(){
								ExecuteCommand('select');
							}
						},
						{
							iconCls: "icon-form-combobox",
							text: '宏控件',
							handler: function(){
								ExecuteCommand('macro');
							}
						}
					]
				}
			],
			tbar:['<span style="color:#666">表单工具：</span>']
		});
		
		this.formPanel = new Ext.form.FormPanel({
			region: 'center',
			border: false,
			layout: "fit",
			autoScroll: false,
			items: [
				{
	                xtype: 'ckeditor',
	                fieldLabel: 'Editor',
	                name: 'htmlcode',
					hideLabel: true,
					value: Ext.getDom("FORM_CONTENT_2").value,
					id: "FORM_CONTENT",
					name: "FORM_CONTENT",
					CKConfig: {
						skin : 'v2',
						toolbarCanCollapse: false,
						resize_enabled: false,
						toolbar : [
							['Source'],['-','Bold','Italic','Underline','Strike','-','Subscript','Superscript'],
							['Cut','Copy','Paste','PasteText','PasteFromWord'],
							['Undo','Redo','-','Find','Replace','-','SelectAll','RemoveFormat'],
							'/',
							['NumberedList','BulletedList','-','Outdent','Indent','Blockquote','CreateDiv'],
							['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],
							['BidiLtr', 'BidiRtl' ],
							['Link','Unlink'],
							['Image','Table','HorizontalRule','SpecialChar','PageBreak'],
							'/',
							['Styles','Format','Font','FontSize'],
							['TextColor','BGColor'],
							['ShowBlocks', "Pager"]
							//,['TextField', 'Textarea', 'Select', 'Macro']
						]
						,extraPlugins : "pager"//,tableresize
					},
					listeners: {
						afterrender : function(){
							//fckLoaded
							try {
								parent.CNOA_flow_flow_settingform.fckLoaded();
							} catch (e) {}
						}
					}
	            }
			]
		});
		
		this.centerPanel = new Ext.Panel({
			border: false,
			layout: "border",
			region: 'center',
			items: [this.formPanel, this.leftPanel],
			tbar: new Ext.Toolbar({
				items: ['<span style="color:#666">提示：先将网页设计工具或Word编辑好的表格框架粘贴到表单设计区。然后再在粘贴好的表单上创建表单控件(设计表单建议用IE浏览器来设计)。</span>',{text: "&nbsp;", disabled: true}]
			})
		});
		
		this.viewPort = new Ext.Viewport({
			layout: 'border',
			items: [this.centerPanel]
		});
		
		this.viewPort.doLayout();
	}
};

var oFCKeditor;
Ext.onReady(function() {
	CNOA_flow_flow_settingformdesign = new CNOA_flow_flow_settingformdesignClass();
});
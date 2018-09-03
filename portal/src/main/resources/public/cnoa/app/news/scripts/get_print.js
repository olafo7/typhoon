//初始化
$(document).ready(function(){
		var _this = this;

		this.id 		= CNOA.news.notice.print.id;
	
		this.baseUrl 	= "index.php?app=news&func=notice&action="+CNOA.news.notice.print.fromaction;

	this.tpl = new Ext.XTemplate(
			'<div >',
			'  <div align="center"><font size=6px><strong>{title}</strong></font></div>',
			'  <div align="center" style="padding-top:5px">' + lang('deptToPost')+ ':<a>{dept}</a>&nbsp;&nbsp;&nbsp;&nbsp; ' + lang('postter') + ':<a>{user}</a>&nbsp;&nbsp;&nbsp;&nbsp;' + lang('posttime2') + ':<a>{posttime}</a><br>' + lang('enableTime') + ':<a>{stime}</a>&nbsp;&nbsp;&nbsp;&nbsp;' + lang('endTime') + ':<a>{etime}</a></div></div>',
			'<div style="padding:5px 30px 5px 30px;">',
			'{content}&nbsp;',
			'</div>'
		);
	
		this.mainPanel = new Ext.Panel({
			collapsible:false,
			hideBorders: true,
			border: false,
			region: "center",
			layout:'border',
			autoScroll: false,
			listeners: {
				render : function(p){
					Ext.Ajax.request({  
						url: _this.baseUrl + "&task=view",
						method: 'POST',
						params: {id: _this.id},
						success: function(r) {
							var result = Ext.decode(r.responseText);
							if(result.success === true){
								var rd = result.data;
								_this.tpl.overwrite(_this.mainPanel.body, rd);
							}
						}
					});
				}
			},
			renderTo:document.body
		});
})
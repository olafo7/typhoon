var CNOA_domain_domain_mygroup, CNOA_domain_domain_mygroupClass;

CNOA_domain_domain_mygroupClass = new CNOA.Class.create();
CNOA_domain_domain_mygroupClass.prototype = {
	init: function() {
		var me = this;
		this.baseUrl = "index.php?app=domain&func=domain&action=mygroup";
		this.mainPanel = new Ext.Panel({
        	layout: {
			    type: 'hbox',
			    align: 'middle ',
			    pack: 'center'
			},
        	border: false,
			bodyBorder: false,
			listeners:{
				'afterrender': function(th){
					th.add(me.tablePanel);
					th.doLayout()
				}
			}
		});

		this.tablePanel = new Ext.Panel({
			layout: 'table',
			layoutConfig: {columns: 4},
			defaults: {
	    		style:'margin:20px'
	    	},
	    	border: false,
			bodyBorder: false,
	    	width: 1040,
			listeners:{
				'afterrender': function(){
					Ext.Ajax.request({
						url: me.baseUrl+"&task=getMygroup",
						success: function(response){
							var responseText = Ext.decode(response.responseText);
							me.loadMygroup(responseText.data);
						}
					})
				}
			}
		});
	},

	loadMygroup: function(json){
		var me = this, panel;
		for(var i = 0; i < json.length; i++){
			panel = new Ext.Panel({
				width: 220,
				height: 135,
				border: false,
				bodyBorder: false,
				groupUrl: json[i].domain,
				groupName: json[i].name,
				listeners: {
					afterrender: function(th){
						th.mon(th.el, 'click', function(){
							window.open('http://' + th.groupUrl);
						});
					}
				},
				html: me.getHtml(json[i])
			});
			me.tablePanel.add(panel);
		}
		this.tablePanel.doLayout();
		this.mainPanel.doLayout();
	},

	getHtml: function(json){
		html = '<div style="width:220px;height:135px;cursor:pointer;' +
				'background:url(\'resources/portals/images/bg'+( json.domainID % 6 + 1 )+'.png\') no-repeat;'+
    			'"><img id="image" src="resources/portals/images/'+( json.domainID % 8 + 1 )+'.png" style="margin:15px 73px 5px 73px;"/>'+
    			'<span style="display:block;font-size:18px;font-family:微软雅黑;color:#fff;'+
    			'text-align:center;">'+json.name+'</span>'+
    			'<div>';
		return html;
	}

}

var sm_domain_domain2 = 1;
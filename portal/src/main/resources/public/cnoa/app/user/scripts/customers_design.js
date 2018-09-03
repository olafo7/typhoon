var CNOA_user_customers_design;
CNOA_user_customers_design = new CNOA_custom_field_panel('index.php?app=user&func=customers&action=design').mainPanel;
Ext.getCmp(CNOA.user.customers.design.parentID).add(CNOA_user_customers_design);
Ext.getCmp(CNOA.user.customers.design.parentID).doLayout();
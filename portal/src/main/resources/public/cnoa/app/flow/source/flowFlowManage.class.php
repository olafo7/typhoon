<?php
//decode by qq2859470

class flowFlowManage extends model
{

    private $table_sort = "flow_flow_sort";
    private $table_list = "flow_flow_list";
    private $table_list_node = "flow_flow_list_node";
    private $table_form = "flow_flow_form";
    private $table_form_item = "flow_flow_form_item";
    private $table_u_list = "flow_flow_u_list";
    private $table_u_node = "flow_flow_u_node";
    private $table_u_formdata = "flow_flow_u_formdata";
    private $table_u_event = "flow_flow_u_event";
    private $table_u_entrust = "flow_flow_u_entrust";
    private $eventType = array
    (
        1 => "开始",
        2 => "已办理",
        3 => "撤销",
        4 => "召回",
        5 => "退件",
        6 => "退回上一步",
        7 => "结束"
    );
    private $statusType = array
    (
        1 => "办理中",
        2 => "已办理",
        3 => "退件"
    );
    private $entrustType = array
    (
        0 => "禁用",
        1 => "启用",
        2 => "未设置"
    );

    public function __construct( )
    {
    }

    public function __destruct( )
    {
    }

    public function run( )
    {
        global $CNOA_SESSION;
        $task = getpar( $_GET, "task", getpar( $_POST, "task" ) );
        if ( $task == "loadPage" )
        {
            $this->_loadPage( );
        }
    }

    private function _loadPage( )
    {
        global $CNOA_SESSION;
        global $CNOA_CONTROLLER;
        $from = getpar( $_GET, "from", "" );
        if ( $from == "flowlist" )
        {
            $tplPath = $CNOA_CONTROLLER->appPath."/tpl/default/flow/manage_flowlist.htm";
        }
        $CNOA_CONTROLLER->loadExtraTpl( $tplPath );
        exit( );
    }

}

?>

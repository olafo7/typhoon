<?php
//decode by qq2859470

class flowTable extends model
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

    public function __construct( )
    {
    }

    public function __destruct( )
    {
    }

    public function actionList( )
    {
        global $CNOA_SESSION;
        $task = getpar( $_GET, "task", getpar( $_POST, "task" ) );
        if ( $task == "loadPage" )
        {
            $this->_loadPage( );
        }
        else if ( $task == "getMyFlowJsonData" )
        {
            $this->_getMyFlowJsonData( );
        }
        else if ( $task == "show_loadFlowInfo" )
        {
            $this->_show_loadFlowInfo( );
        }
    }

    private function _loadPage( )
    {
        global $CNOA_SESSION;
        global $CNOA_CONTROLLER;
        $from = getpar( $_GET, "from", "" );
        if ( $from == "list" )
        {
            $GLOBALS['GLOBALS']['lid'] = getpar( $_GET, "lid", 0 );
            $tplPath = $CNOA_CONTROLLER->appPath."/tpl/default/table/list.htm";
        }
        else if ( $from == "view" )
        {
            $GLOBALS['GLOBALS']['ulid'] = getpar( $_GET, "ulid", 0 );
            $tplPath = $CNOA_CONTROLLER->appPath."/tpl/default/table/view.htm";
        }
        $CNOA_CONTROLLER->loadExtraTpl( $tplPath );
        exit( );
    }

    private function _getMyFlowJsonData( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $uid = $CNOA_SESSION->get( "UID" );
        $start = getpar( $_POST, "start", 0 );
        $rows = 15;
        $lid = intval( getpar( $_GET, "lid", 0 ) );
        $where = "WHERE `lid`='".$lid."' AND 1";
        $where .= $this->__findFlowInfo( );
        $order = "ORDER BY `posttime` DESC LIMIT ".$start.", {$rows} ";
        $dbList = $CNOA_DB->db_select( "*", $this->table_u_list, $where.$order );
        if ( !is_array( $dbList ) )
        {
            $dbList = array( );
        }
        foreach ( $dbList as $k => $v )
        {
            $dbList[$k]['posttime'] = date( "Y-m-d H:i", $v['posttime'] );
        }
        ( );
        $dataStore = new dataStore( );
        $dataStore->total = $CNOA_DB->db_getcount( $this->table_u_list, $where );
        $dataStore->data = $dbList;
        echo $dataStore->makeJsonData( );
        exit( );
    }

    private function __findFlowInfo( )
    {
        $name = getpar( $_POST, "name" );
        $title = getpar( $_POST, "title" );
        $stime = getpar( $_POST, "beginTime" );
        $etime = getpar( $_POST, "endTime" );
        $uid = getpar( $_POST, "buildUser" );
        $s = "";
        if ( !empty( $uid ) )
        {
            $s .= " AND `uid`=".$uid;
        }
        if ( !empty( $name ) )
        {
            $s .= " AND `name` LIKE '%".$name."%'";
        }
        if ( !empty( $title ) )
        {
            $s .= " AND `title` LIKE '%".$title."%'";
        }
        if ( !empty( $stime ) || empty( $etime ) )
        {
            $stime = strtotime( $stime." 00:00:00" );
            $s .= " AND `posttime` >= ".$stime;
        }
        if ( !empty( $etime ) || empty( $stime ) )
        {
            $etime = strtotime( $etime." 23:59:59" );
            $s .= " AND `posttime` <= ".$etime;
        }
        if ( !empty( $stime ) || !empty( $etime ) )
        {
            $stime = strtotime( $stime." 00:00:00" );
            $etime = strtotime( $etime." 23:59:59" );
            if ( $etime < $stime )
            {
                msg::callback( FALSE, "查询开始时间不能大于结束时间" );
            }
            else
            {
                $s .= " AND `posttime` > ".$stime." AND `posttime` < {$etime}";
            }
        }
        return " ".$s." ";
    }

    private function _show_loadFlowInfo( )
    {
        app::loadapp( "flow", "flowUser" )->api_show_loadFlowInfo( );
    }

}

?>

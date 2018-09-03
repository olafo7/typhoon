<?php
//decode by qq2859470

class docReceiveTome extends model
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
        $this->cachePath = CNOA_PATH_FILE."/cache";
    }

    public function __destruct( )
    {
    }

    public function run( )
    {
        $task = getpar( $_GET, "task", "" );
        if ( $task == "loadPage" )
        {
            $this->_loadPage( );
        }
        else if ( $task == "getList" )
        {
            $this->_getList( );
        }
    }

    private function _loadPage( )
    {
        global $CNOA_CONTROLLER;
        $from = getpar( $_GET, "from", "list" );
        if ( $from == "list" )
        {
            $tplPath = $CNOA_CONTROLLER->appPath."/tpl/default/receive/tome_list.htm";
        }
        else
        {
        }
        $CNOA_CONTROLLER->loadExtraTpl( $tplPath );
        exit( );
    }

    private function _getList( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $uid = $CNOA_SESSION->get( "UID" );
        $isread = intval( getpar( $_POST, "isread", 0 ) );
        $start = getpar( $_POST, "start", 0 );
        $rows = 15;
        $where = "WHERE  `sort`=112 AND `ulid` IN (SELECT `ulid` FROM ".tname( "flow_flow_u_dispense" ).( " WHERE `to_uid`='".$uid."' AND `isread`='{$isread}') " );
        $sql = "SELECT * FROM ".tname( $this->table_u_list ).( " ".$where." " );
        $order = " ORDER BY `posttime` DESC LIMIT ".$start.", {$rows} ";
        $dbList = array( );
        $queryList = $CNOA_DB->query( $sql.$order );
        while ( $list = $CNOA_DB->get_array( $queryList ) )
        {
            $dbList[] = $list;
        }
        if ( !is_array( $dbList ) )
        {
            $dbList = array( );
        }
        foreach ( $dbList as $k => $v )
        {
            $dbList[$k]['posttime'] = date( "Y-m-d H:i", $v['posttime'] );
            if ( $v['level'] == 2 )
            {
                $dbList[$k]['level'] = "<span class='cnoa_color_orange'>重要</span>";
            }
            else if ( $v['level'] == 3 )
            {
                $dbList[$k]['level'] = "<span class='cnoa_color_red'>非常重要</span>";
            }
            else
            {
                $dbList[$k]['level'] = "普通";
            }
            if ( $v['status'] == 0 )
            {
                $dbList[$k]['step'] = $CNOA_DB->db_getfield( "name", $this->table_list_node, "WHERE `lid`='".$v['lid']."' AND `stepid`='0'" );
            }
            else
            {
                $cacheFile = include( $this->cachePath.( "/flow/user/".$v['ulid']."/" )."flow_node.php" );
                $dbList[$k]['step'] = $cacheFile[$v['step']]['name'];
            }
            $uids[] = $v['uid'];
        }
        $usersInfo = app::loadapp( "main", "user" )->api_getUserNamesByUids( $uids );
        foreach ( $dbList as $k => $v )
        {
            $dbList[$k]['uname'] = $usersInfo[$v['uid']]['truename'];
        }
        ( );
        $dataStore = new dataStore( );
        $dataStore->total = $CNOA_DB->db_getcount( $this->table_u_list, $where );
        $dataStore->data = $dbList;
        echo $dataStore->makeJsonData( );
        exit( );
    }

    private function _list( )
    {
        global $CNOA_DB;
        $dblist = $CNOA_DB->db_select( "*", $this->table );
        ( );
        $dataStore = new dataStore( );
        $dataStore->total = $CNOA_DB->db_getcount( $this->table );
        $dataStore->data = $dblist;
        echo $dataStore->makeJsonData( );
        exit( );
    }

}

?>

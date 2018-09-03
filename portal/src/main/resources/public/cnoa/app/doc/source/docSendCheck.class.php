<?php
//decode by qq2859470

class docSendCheck extends model
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
            $tplPath = $CNOA_CONTROLLER->appPath."/tpl/default/send/check_list.htm";
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
        $start = getpar( $_POST, "start", 0 );
        $rows = 15;
        $sql = "SELECT * FROM ".tname( $this->table_u_list );
        $where = "WHERE 1 ";
        $where .= $this->__findFlowInfo( );
        $order = " ORDER BY `posttime` DESC LIMIT ".$start.", {$rows} ";
        $query = $CNOA_DB->query( $sql.$where.$order );
        $dbList = array( );
        while ( $r = $CNOA_DB->get_array( $query ) )
        {
            $dbList[] = $r;
        }
        $uids = array( );
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
                $dbList[$k]['stepText'] = $CNOA_DB->db_getfield( "name", $this->table_list_node, "WHERE `lid`='".$v['lid']."' AND `stepid`='0'" );
            }
            else
            {
                $cacheFile = include( $this->cachePath.( "/flow/user/".$v['ulid']."/" )."flow_node.php" );
                $dbList[$k]['stepText'] = $cacheFile[$v['step']]['name'];
                $allowOperate = $CNOA_DB->db_getone( array( "uid" ), $this->table_u_node, "WHERE `ulid`='".$v['ulid']."' AND `uid`='{$uid}' AND `stepid`='{$v['step']}' AND `status`=1" );
                $dbList[$k]['allowOperate'] = $v['status'] == 1 === FALSE ? 0 : 1;
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

    private function __findFlowInfo( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $uid = $CNOA_SESSION->get( "UID" );
        $name = getpar( $_POST, "name" );
        $title = getpar( $_POST, "title" );
        $stime = getpar( $_POST, "beginTime" );
        $etime = getpar( $_POST, "endTime" );
        $doing = getpar( $_POST, "doing", 1 );
        $s = "";
        $s .= " AND `sort`=113 ";
        if ( $doing == 1 )
        {
            $s .= " AND `status`=1";
            $s2 = "`status`=1";
        }
        else if ( $doing == 2 )
        {
            $s .= " AND `status`=2";
            $s2 = "`status`=2";
        }
        else
        {
            $s .= " AND `status` IN (1,2)";
            $s2 = "`status` IN (1,2)";
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
        $s .= " AND `ulid` IN (SELECT `ulid` FROM ".tname( $this->table_u_node ).( " WHERE ".$s2." AND `uid`='{$uid}') " );
        return $s;
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

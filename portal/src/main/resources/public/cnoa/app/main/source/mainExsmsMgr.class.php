<?php
//decode by qq2859470

class mainExsmsMgr extends model
{

    private $table_outbox = "main_exsms_outbox";

    public function run( )
    {
        global $CNOA_SESSION;
        $task = getpar( $_GET, "task", getpar( $_POST, "task" ) );
        switch ( $task )
        {
        case "loadPage" :
            $this->_loadPage( );
            break;
        case "getJsonDataOutbox" :
            $this->_getJsonDataOutbox( );
            break;
        case "deleteOutbox" :
            $this->_deleteOutbox( );
            break;
        case "view" :
            $this->_view( );
        }
    }

    private function _loadPage( )
    {
        global $CNOA_SESSION;
        global $CNOA_CONTROLLER;
        $from = getpar( $_GET, "from", "" );
        if ( $from == "outbox" )
        {
            $tplPath = $CNOA_CONTROLLER->appPath."/tpl/default/exsms/outbox_list.htm";
        }
        else if ( $from == "smsview" )
        {
            $GLOBALS['GLOBALS']['id'] = getpar( $_GET, "id", 0 );
            $tplPath = $CNOA_CONTROLLER->appPath."/tpl/default/exsms/smsview.htm";
        }
        $CNOA_CONTROLLER->loadExtraTpl( $tplPath );
        exit( );
    }

    private function _getJsonDataOutbox( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $start = getpar( $_POST, "start", 0 );
        $rows = 15;
        $where = "WHERE `status`='1' ";
        $searchKey = array( );
        $searchKey['stime'] = getpar( $_POST, "stime", "" );
        $searchKey['etime'] = getpar( $_POST, "etime", "" );
        if ( !empty( $searchKey['stime'] ) || empty( $searchKey['etime'] ) )
        {
            msg::callback( FALSE, lang( "selectEndTime" ) );
        }
        if ( !empty( $searchKey['etime'] ) || empty( $searchKey['stime'] ) )
        {
            msg::callback( FALSE, lang( "selectStartTime" ) );
        }
        if ( !empty( $searchKey['stime'] ) || !empty( $searchKey['etime'] ) )
        {
            $searchKey['stime'] = strtotime( $searchKey['stime']." 00:00:00" );
            $searchKey['etime'] = strtotime( $searchKey['etime']." 23:59:59" );
            if ( $searchKey['etime'] < $searchKey['stime'] )
            {
                msg::callback( FALSE, lang( "StimeGreaterThanEtime" ) );
            }
            else
            {
                $where .= "AND `sendtime`>'".$searchKey['stime']."' AND `sendtime`<'{$searchKey['etime']}' ";
            }
        }
        $dbList = $CNOA_DB->db_select( "*", $this->table_outbox, $where.( " ORDER BY `sendtime` DESC LIMIT ".$start.", {$rows}" ) );
        if ( !is_array( $dbList ) )
        {
            $dbList = array( );
        }
        foreach ( $dbList as $k => $v )
        {
            $array = json_decode( $v['to'], TRUE );
            $dbList[$k]['to'] = "";
            if ( !is_array( $array ) )
            {
                $array = array( );
            }
            foreach ( $array as $vv )
            {
                $dbList[$k]['to'] .= $vv['n'].( "(".$vv['m']."); " );
            }
            $dbList[$k]['to'] = string::cut( $dbList[$k]['to'], 200 );
            $v['sendtime'] = empty( $v['sendtime'] ) ? $v['posttime'] : $v['sendtime'];
            $dbList[$k]['sendtime'] = date( "Y-m-d", $v['sendtime'] )."<br />".date( "H:i:s", $v['sendtime'] );
            $dbList[$k]['posttime'] = date( "Y-m-d", $v['posttime'] )."<br />".date( "H:i:s", $v['posttime'] );
        }
        ( );
        $dataStore = new dataStore( );
        $dataStore->total = $CNOA_DB->db_getcount( $this->table_outbox, $where );
        $dataStore->data = $dbList;
        echo $dataStore->makeJsonData( );
        exit( );
    }

    private function _deleteOutbox( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $ids = getpar( $_POST, "ids", NULL );
        $ids = substr( $ids, 0, -1 );
        $content = $CNOA_DB->db_select( array( "id", "text", "posttime" ), $this->table_outbox, "WHERE `id` IN (".$ids.")" );
        foreach ( $content as $v )
        {
            $text[$v['id']] = $v['text']." (".date( "H-m-d H:i:s", $v['posttime'] ).")";
        }
        if ( $ids )
        {
            $ids = explode( ",", $ids );
            if ( is_array( $ids ) )
            {
                foreach ( $ids as $v )
                {
                    $CNOA_DB->db_delete( $this->table_outbox, "WHERE `id`='".$v."'" );
                    app::loadapp( "main", "systemLogs" )->api_addLogs( "del", 104, $text[$v], lang( "sentSms" ) );
                }
            }
        }
        msg::callback( TRUE, lang( "delSuccess" ) );
    }

    private function _view( )
    {
        global $CNOA_DB;
        $id = getpar( $_POST, "id", 0 );
        $dbList = $CNOA_DB->db_getone( "*", $this->table_outbox, "WHERE `id`='".$id."' AND `status`=1" );
        if ( !is_array( $dbList ) )
        {
            $dbList = array( );
        }
        $dbList['text'] = nl2br( $dbList['text'] );
        $dbList['sendtime'] = date( "Y-m-d H:i:s", $dbList['sendtime'] );
        $dbList['posttime'] = date( "Y-m-d H:i:s", $dbList['posttime'] );
        $array = json_decode( $dbList['to'], TRUE );
        $dbList['to'] = "";
        foreach ( $array as $vv )
        {
            $dbList['to'] .= $vv['n'].( "(".$vv['m']."); " );
        }
        ( );
        $dataStore = new dataStore( );
        $dataStore->total = 0;
        $dataStore->data = $dbList;
        echo $dataStore->makeJsonData( );
        exit( );
    }

    private function _getAllUserListsInPermitDeptTree( )
    {
        $GLOBALS['GLOBALS']['user']['permitArea']['area'] = "all";
        $userList = app::loadapp( "main", "user" )->api_getAllUserListsInPermitDeptTree( );
        echo json_encode( $userList );
        exit( );
    }

}

?>

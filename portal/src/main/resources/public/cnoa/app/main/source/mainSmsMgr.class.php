<?php
//decode by qq2859470

class mainSmsMgr extends model
{

    private $table_inbox = "main_sms_inbox";
    private $table_outbox = "main_sms_outbox";
    private $table_sendbox = "main_sms_sendbox";

    public function run( )
    {
        global $CNOA_SESSION;
        $task = getpar( $_GET, "task", getpar( $_POST, "task" ) );
        switch ( $task )
        {
        case "loadPage" :
            $this->_loadPage( );
            break;
        case "getJsonDataInbox" :
            $this->_getJsonDataInbox( );
            break;
        case "getJsonDataOutbox" :
            $this->_getJsonDataOutbox( );
            break;
        case "getJsonDataSendbox" :
            $this->_getJsonDataSendbox( );
            break;
        case "deleteInbox" :
            $this->_deleteInbox( );
            break;
        case "deleteOutbox" :
            $this->_deleteOutbox( );
            break;
        case "deleteSendbox" :
            $this->_deleteSendbox( );
            break;
        case "getAllUserListsInPermitDeptTree" :
            $this->_getAllUserListsInPermitDeptTree( );
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
        if ( $from == "inbox" )
        {
            $tplPath = $CNOA_CONTROLLER->appPath."/tpl/default/sms/inbox_list.htm";
        }
        else if ( $from == "outbox" )
        {
            $tplPath = $CNOA_CONTROLLER->appPath."/tpl/default/sms/outbox_list.htm";
        }
        else if ( $from == "sendbox" )
        {
            $tplPath = $CNOA_CONTROLLER->appPath."/tpl/default/sms/sendbox_list.htm";
        }
        else if ( $from == "smsview" )
        {
            $GLOBALS['GLOBALS']['type'] = getpar( $_GET, "inout", "" );
            $GLOBALS['GLOBALS']['id'] = getpar( $_GET, "id", 0 );
            $tplPath = $CNOA_CONTROLLER->appPath."/tpl/default/sms/smsview.htm";
        }
        $CNOA_CONTROLLER->loadExtraTpl( $tplPath );
        exit( );
    }

    private function _getJsonDataInbox( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $start = getpar( $_POST, "start", 0 );
        $rows = 15;
        $where = "WHERE `status`='1' ";
        $searchKey = array( );
        $searchKey['mobile'] = getpar( $_POST, "mobile", "" );
        $searchKey['fromuid'] = intval( getpar( $_POST, "fromuid", 0 ) );
        $searchKey['stime'] = getpar( $_POST, "stime", "" );
        $searchKey['etime'] = getpar( $_POST, "etime", "" );
        if ( !empty( $searchKey['mobile'] ) )
        {
            $where .= "AND `mobile` LIKE '%".$searchKey['mobile']."%' ";
        }
        if ( $searchKey['fromuid'] !== 0 )
        {
            $where .= "AND `fromuid`='".$searchKey['fromuid']."' ";
        }
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
                $where .= "AND `posttime`>'".$searchKey['stime']."' AND `posttime`<'{$searchKey['etime']}' ";
            }
        }
        $dbList = $CNOA_DB->db_select( "*", $this->table_inbox, $where.( " ORDER BY `posttime` DESC LIMIT ".$start.", {$rows}" ) );
        if ( !is_array( $dbList ) )
        {
            $dbList = array( );
        }
        foreach ( $dbList as $k => $v )
        {
            $dbList[$k]['posttime'] = date( "Y-m-d", $v['posttime'] )."<br />".date( "H:i:s", $v['posttime'] );
        }
        ( );
        $dataStore = new dataStore( );
        $dataStore->total = $CNOA_DB->db_getcount( $this->table_inbox, $where );
        $dataStore->data = $dbList;
        echo $dataStore->makeJsonData( );
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
        $searchKey['mobile'] = getpar( $_POST, "mobile", "" );
        $searchKey['fromuid'] = intval( getpar( $_POST, "fromuid", 0 ) );
        $searchKey['touid'] = intval( getpar( $_POST, "touid", 0 ) );
        $searchKey['stime'] = getpar( $_POST, "stime", "" );
        $searchKey['etime'] = getpar( $_POST, "etime", "" );
        if ( !empty( $searchKey['mobile'] ) )
        {
            $where .= "AND `mobile` LIKE '%".$searchKey['mobile']."%' ";
        }
        if ( $searchKey['fromuid'] !== 0 )
        {
            $where .= "AND `fromuid`='".$searchKey['fromuid']."' ";
        }
        if ( $searchKey['touid'] !== 0 )
        {
            $where .= "AND `touid`='".$searchKey['touid']."' ";
        }
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
            $dbList[$k]['from'] = sms::$form[$v['from']];
            $dbList[$k]['sendtime'] = date( "Y-m-d", $v['sendtime'] )."<br />".date( "H:i:s", $v['sendtime'] );
            if ( 0 < $v['replies'] )
            {
                $dbList[$k]['replies'] = $CNOA_DB->db_getfield( "text", $this->table_inbox, "WHERE `fid`='".$v['id']."' ORDER BY `posttime` DESC" );
            }
            else
            {
                $dbList[$k]['replies'] = "";
            }
        }
        ( );
        $dataStore = new dataStore( );
        $dataStore->total = $CNOA_DB->db_getcount( $this->table_outbox, $where );
        $dataStore->data = $dbList;
        echo $dataStore->makeJsonData( );
        exit( );
    }

    private function _getJsonDataSendbox( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $start = getpar( $_POST, "start", 0 );
        $rows = 15;
        $where = "WHERE 1 ";
        $searchKey = array( );
        $searchKey['mobile'] = getpar( $_POST, "mobile", "" );
        $searchKey['fromuid'] = intval( getpar( $_POST, "fromuid", 0 ) );
        $searchKey['touid'] = intval( getpar( $_POST, "touid", 0 ) );
        $searchKey['stime'] = getpar( $_POST, "stime", "" );
        $searchKey['etime'] = getpar( $_POST, "etime", "" );
        if ( !empty( $searchKey['mobile'] ) )
        {
            $where .= "AND `mobile` LIKE '%".$searchKey['mobile']."%' ";
        }
        if ( $searchKey['fromuid'] !== 0 )
        {
            $where .= "AND `fromuid`='".$searchKey['fromuid']."' ";
        }
        if ( $searchKey['touid'] !== 0 )
        {
            $where .= "AND `touid`='".$searchKey['touid']."' ";
        }
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
        $dbList = $CNOA_DB->db_select( "*", $this->table_sendbox, $where.( " ORDER BY `sendtime` DESC LIMIT ".$start.", {$rows}" ) );
        if ( !is_array( $dbList ) )
        {
            $dbList = array( );
        }
        foreach ( $dbList as $k => $v )
        {
            $dbList[$k]['from'] = sms::$form[$v['from']];
            $v['sendtime'] = $v['sendtime'] == "0" ? $v['posttime'] : $v['sendtime'];
            $dbList[$k]['sendtime'] = date( "Y-m-d", $v['sendtime'] )." ".date( "H:i:s", $v['sendtime'] );
            switch ( $v['status'] )
            {
            case 1 :
                $dbList[$k]['status'] = lang( "unsent" );
                break;
            case 2 :
                $dbList[$k]['status'] = lang( "sendFailure" )."<br />";
                $dbList[$k]['status'] .= "<span class='cnoa_color_red'>".$v['statusText']."</span>";
            }
        }
        ( );
        $dataStore = new dataStore( );
        $dataStore->total = $CNOA_DB->db_getcount( $this->table_sendbox, $where );
        $dataStore->data = $dbList;
        echo $dataStore->makeJsonData( );
        exit( );
    }

    private function _deleteInbox( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $ids = getpar( $_POST, "ids", NULL );
        $ids = substr( $ids, 0, -1 );
        $content = $CNOA_DB->db_select( array( "mobile", "id", "text", "posttime" ), $this->table_inbox, "WHERE `id` IN (".$ids.")" );
        foreach ( $content as $v )
        {
            $text[$v['id']] = $v['text'].( " (To:".$v['mobile']." " ).date( "H:m:d H:i:s", $v['posttime'] ).")";
        }
        if ( $ids )
        {
            $ids = explode( ",", $ids );
            if ( is_array( $ids ) )
            {
                foreach ( $ids as $v )
                {
                    $info = $CNOA_DB->db_getone( array( "fid", "id" ), $this->table_inbox, "WHERE `id`='".$v."'" );
                    if ( $info !== FALSE )
                    {
                        $CNOA_DB->db_update( array( "status" => 0 ), $this->table_inbox, "WHERE `id`='".$v."'" );
                        if ( $info['fid'] != 0 )
                        {
                            $CNOA_DB->db_updateNum( "replies", "-1", $this->table_outbox, "WHERE `id`='".$info['fid']."'" );
                        }
                        app::loadapp( "main", "systemLogs" )->api_addLogs( "del", 98, $text[$v], lang( "receivedSms" ) );
                    }
                }
            }
        }
        msg::callback( TRUE, lang( "delSuccess" ) );
    }

    private function _deleteOutbox( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $ids = getpar( $_POST, "ids", NULL );
        $ids = substr( $ids, 0, -1 );
        $content = $CNOA_DB->db_select( array( "mobile", "id", "text", "posttime" ), $this->table_outbox, "WHERE `id` IN (".$ids.")" );
        foreach ( $content as $v )
        {
            $text[$v['id']] = $v['text'].( " (To:".$v['mobile']." " ).date( "H:m:d H:i:s", $v['posttime'] ).")";
        }
        if ( $ids )
        {
            $ids = explode( ",", $ids );
            if ( is_array( $ids ) )
            {
                foreach ( $ids as $v )
                {
                    $CNOA_DB->db_update( array( "status" => 0 ), $this->table_outbox, "WHERE `id`='".$v."'" );
                    $CNOA_DB->db_update( array( "status" => 0 ), $this->table_inbox, "WHERE `fid`='".$v."'" );
                    app::loadapp( "main", "systemLogs" )->api_addLogs( "del", 99, $text[$v], lang( "receivedSms" ) );
                }
            }
        }
        msg::callback( TRUE, lang( "delSuccess" ) );
    }

    private function _deleteSendbox( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $ids = getpar( $_POST, "ids", NULL );
        $ids = substr( $ids, 0, -1 );
        $content = $CNOA_DB->db_select( array( "mobile", "id", "text", "posttime" ), $this->table_sendbox, "WHERE `id` IN (".$ids.")" );
        foreach ( $content as $v )
        {
            $text[$v['id']] = $v['text'].( " (To:".$v['mobile']." " ).date( "H:m:d H:i:s", $v['posttime'] ).")";
        }
        if ( $ids )
        {
            $ids = explode( ",", $ids );
            if ( is_array( $ids ) )
            {
                foreach ( $ids as $v )
                {
                    $CNOA_DB->db_delete( $this->table_sendbox, "WHERE `id`='".$v."'" );
                    app::loadapp( "main", "systemLogs" )->api_addLogs( "del", 100, $text[$v], lang( "toSms" ) );
                }
            }
        }
        msg::callback( TRUE, lang( "delSuccess" ) );
    }

    private function _view( )
    {
        global $CNOA_DB;
        $type = getpar( $_POST, "type", "in" );
        $id = getpar( $_POST, "id", 0 );
        if ( $type == "in" )
        {
            $dbList = $CNOA_DB->db_getone( "*", $this->table_inbox, "WHERE `id`='".$id."' AND `status`=1" );
            $dbList['posttime'] = date( "Y-m-d H:i:s", $dbList['posttime'] );
        }
        else if ( $type == "out" )
        {
            $dbList = $CNOA_DB->db_getone( "*", $this->table_outbox, "WHERE `id`='".$id."' AND `status`=1" );
            if ( 0 < $dbList['replies'] )
            {
                $dbList['list'] = $CNOA_DB->db_select( "*", $this->table_inbox, "WHERE `fid`='".$id."' AND `status`=1 ORDER BY `posttime` DESC" );
                if ( !is_array( $dbList['list'] ) )
                {
                    $dbList['list'] = array( );
                }
                foreach ( $dbList['list'] as $ks => $vs )
                {
                    $dbList['list'][$ks]['text'] = nl2br( $vs['text'] );
                    $dbList['list'][$ks]['posttime'] = date( "Y-m-d H:i:s", $vs['posttime'] );
                }
            }
            else
            {
                $dbList['list'] = array( );
            }
        }
        else if ( $type == "send" )
        {
            $dbList = $CNOA_DB->db_getone( "*", $this->table_sendbox, "WHERE `id`='".$id."'" );
            $dbList['list'] = array( );
        }
        if ( !$dbList )
        {
            msg::callback( FALSE, lang( "nodata" ) );
        }
        $dbList['text'] = nl2br( $dbList['text'] );
        $dbList['from'] = sms::$form[$dbList['from']];
        $dbList['sendtime'] = date( "Y-m-d H:i:s", $dbList['sendtime'] );
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
